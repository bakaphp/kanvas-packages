<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Canvas\Filesystem\Helper;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use mikehaertl\wkhtmlto\Pdf as PDFLibrary;
use Phalcon\Di;
use Throwable;

class PDF extends Action
{
    /**
     * handle.
     *
     * @param WorkflowsEntityInterfaces $entity
     * @param array $params
     *
     * @return void
     */
    public function handle(WorkflowsEntityInterfaces $entity, ...$args) : void
    {
        $response = null;
        $di = Di::getDefault();
        $appMode = $di->get('config')->production;

        try {
            $pdf = new PDFLibrary([
                'encoding' => 'UTF-8',
                'no-outline',
                'margin-top' => 0,
                'margin-right' => 0,
                'margin-bottom' => 0,
                'margin-left' => 0,
                'disable-smart-shrinking',
                'enable-local-file-access',
                'page-width' => 200,
                'page-height' => 265
            ]);

            $data = $this->formatArgs(...$args);
            $data['entity'] = $args[0];
            $data['leads'] = $entity;
            // Set config for pdf settings (example deleted floating)
            $templateServiceClass = get_class($di->get('templates'));
            $template = $templateServiceClass::generate(
                $this->params['template_pdf'],
                $data
            ); // Generate html from emails_templates table

            $pdf->addPage($template);
            $rand = uniqid();
            $path = $di->get('config')->filesystem->local->path . "/{$rand}.pdf";

            if (!$pdf->saveAs($path)) {
                $error = $pdf->getError();
                $this->setStatus(Action::FAIL);
                $this->setError('Error processing PDF - ' . $e->getMessage());
            }

            $filesystem = Helper::upload(Helper::pathToFile($path));

            //meanwhile, i going to check if entity is a messages for attach file to this filesystem
            if (is_subclass_of($entity, Messages::class)) {
                $messages = Messages::findFirstOrFail($entity->id);
                $messages->uploadedFiles[] = [
                    'filesystem_id' => $filesystem->getId()
                ];
                $messages->saveOrFail();
                $files = [
                    [
                        'filesystem_id' => $filesystem->getId()
                    ]
                ];
                $messages->parentMessage->saveOrFail([
                    'files' => $files
                ]);
                $entity->afterRules();
            } else {
                $entity->uploadedFiles[] = [
                    'filesystem_id' => $filesystem->getId()
                ];
                $entity->saveOrFail();
                $entity->afterRules();
            }

            $this->setStatus(Action::SUCCESSFUL);
            $this->setResults($filesystem->toArray());
        } catch (Throwable $e) {
            $this->setStatus(Action::FAIL);
            $this->setError('Error processing PDF - ' . $e->getMessage());
        }
    }
}
