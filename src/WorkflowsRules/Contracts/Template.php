<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts;

class Template
{
    public static function generate(string $name, array $params = []) : string
    {
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            Hola Mundo
        </body>
        </html>';
    }
}
