<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Canvas\Filesystem\Helper;
use Canvas\Template;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\WorkflowsRules\Actions;
use Kanvas\Packages\WorkflowsRules\Contracts\WorkflowsEntityInterfaces;
use mikehaertl\wkhtmlto\Pdf as PDFLibrary;
use Phalcon\Di;
use Throwable;

class PDF extends Actions
{
    /**
     * handle.
     *
     * @param WorkflowsEntityInterfaces $entity
     * @param array $params
     *
     * @return void
     */
    public function handle(WorkflowsEntityInterfaces $entity) : void
    {
        $di = Di::getDefault();
        $args = $entity->getRulesRelatedEntities();

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

            $data = $this->getModelsInArray(...$args);
            $data['entity'] = $entity;
            // Set config for pdf settings (example deleted floating)
            $template = Template::generate(
                $this->params['template_pdf'],
                $data
            ); // Generate html from emails_templates table

            $pdf->addPage($template);
            $rand = uniqid();
            $path = $di->get('config')->filesystem->local->path . "/{$rand}.pdf";

            if (!$pdf->saveAs($path)) {
                $error = $pdf->getError();
                $this->setStatus(Actions::FAIL);
                $this->setError('Error processing PDF - ' . $error);
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
                $messages->getParentMessage()->saveOrFail([
                    'files' => $files
                ]);
                $entity->saveOrFail([
                    'files' => $files
                ]);
            } else {
                $entity->uploadedFiles[] = [
                    'filesystem_id' => $filesystem->getId()
                ];
                $entity->saveOrFail();
                $entity->afterRules();
            }

            $this->setStatus(Actions::SUCCESSFUL);
            $this->setResults($filesystem->toArray());
        } catch (Throwable $e) {
            $this->setStatus(Actions::FAIL);

            $this->setError('Error processing PDF - ' . $e->getMessage());
        }
    }
}
