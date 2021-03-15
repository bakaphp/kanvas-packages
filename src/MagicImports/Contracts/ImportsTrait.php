<?php

namespace Kanvas\Packages\MagicImports\Contracts;

use Exception;
use Kanvas\Packages\MagicImports\Imports;
use Kanvas\Packages\MagicImports\Structure;
use Phalcon\Http\Response;

trait ImportsTrait
{
    /**
     * @return Phalcon\Http\Response
     */
    public function structure() : Response
    {
        $structure = isset($this->structure) ? $this->structure : new Structure($this->model);
        $array = $structure->getStructure();

        return $this->response($array);
    }

    /**
     * @return Phalcon\Http\Response
     */
    public function import() : Response
    {
        $post = $this->request->getPostData();

        if (!isset($post['commit'])) {
            throw new Exception('Commit status is required', 1);
        }

        if (!isset($post['fileValues']) || !is_array($post['fileValues']) || count($post['fileValues']) == 0) {
            throw new Exception('fileValues array is required', 1);
        }

        if (!isset($post['mapping']) || !is_array($post['mapping']) || count($post['mapping']) == 0) {
            throw new Exception('mapping array is required', 1);
        }

        $structure = isset($this->structure) ? $this->structure : new Structure($this->model);
        $imports = new Imports($structure, (boolean) $post['commit']);
        $results = $imports->processData($post);

        return $this->response($results);
    }
}
