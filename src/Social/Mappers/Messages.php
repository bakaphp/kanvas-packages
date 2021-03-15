<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Mappers;

use AutoMapperPlus\CustomMapper\CustomMapper;

class Messages extends CustomMapper
{
    /**
     * @param ElasticDocumentsMessages $message
     * @param DtoMessages $messageDto
     *
     * @return DtoMessages
     */
    public function mapToObject($message, $messageDto, array $context = [])
    {
        //no need to convert , we will interact directly with the elastic document
        return $message;
    }
}
