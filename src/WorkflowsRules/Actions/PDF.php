<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Canvas\Filesystem\Helper;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use mikehaertl\wkhtmlto\Pdf as PDFLibrary;
use Phalcon\Di;
use Throwable;

class PDF extends Action
{
    /**
     * handle.
     *
     * @param  WorkflowsEntityInterfaces $entity
     * @param  array $params
     *
     * @return array
     */
    public function handle(WorkflowsEntityInterfaces $entity, array $params = []) : array
    {
        $response = null;
        $di = Di::getDefault();
        $appMode = $di->get('config')->production;
        try {
            $pdf = new PDFLibrary($params['config']); // Set config for pdf settings (example deleted floating)
            $templateServiceClass = get_class($di->get('templates'));
            $template = $templateServiceClass::generate($params['template_name'], ['entity' => $entity]); // Generate html from emails_templates table
            $pdf->addPage($template);
            $rand = uniqid();
            $path = $di->get('config')->filesystem->local->path . "/{$rand}.pdf";
            if (!$pdf->saveAs($path)) {
                $error = $pdf->getError();
                if (!$appMode) {
                    $di->get('log')->error('Error processing pdf', $error);
                }
                $this->status = FAIL;
                $this->message = $error;
            }
            $filesystem = Helper::uploadToS3("{$rand}.pdf", $entity->users_id, $entity->companies_id);

            $this->message = $template;
            $this->data = array_merge($entity->toArray(), $params);
            $this->status = Action::SUCCESSFUL;
            $files = $filesystem->toArray();
            $files['file'] = $filesystem;
            $entity->attach([
                $files
            ]);
        } catch (Throwable $e) {
            $this->message = 'Error processing PDF - ' . $e->getMessage();
            if (!$appMode) {
                $di->get('log')->error('Error processing PDF - ' . $e->getMessage(), [$e->getTraceAsString()]);
            }
            $this->status = Action::FAIL;
            $response = $e->getTraceAsString();
        }

        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'body' => $response
        ];
    }
}
