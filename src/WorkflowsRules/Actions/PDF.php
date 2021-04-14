<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

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

        try {
            $pdf = new PDFLibrary($params['config']);
            $template = get_class($di->get('templates'))::generate($params['template_name'], ['entity' => $entity]);
            $pdf->addPage($template);
            $rand = uniqid();
            $path = $di->get('config')->filesystem->local->path . "/{$rand}.pdf";
            if (!$pdf->saveAs($path)) {
                $error = $pdf->getError();
                $di->get('log')->error('Error processing pdf', $error);
                $this->status = 0;
            }
            $this->status = 1 ;
        } catch (Throwable $e) {
            $this->message = 'Error processing PDF - ' . $e->getMessage();
            $di->get('log')->error('Error processing PDF - ' . $e->getMessage(), [$e->getTraceAsString()]);
            $this->status = 0;
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
