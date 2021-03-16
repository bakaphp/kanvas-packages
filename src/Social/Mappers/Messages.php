<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Mappers;

use AutoMapperPlus\CustomMapper\CustomMapper;
use function Baka\isJson;
use Kanvas\Packages\Social\Dto\Messages as DtoMessages;
use Kanvas\Packages\Social\ElasticDocuments\Messages as ElasticDocumentsMessages;

class Messages extends CustomMapper
{
    /**
     * @param ElasticDocumentsMessages $message
     * @param DtoMessages $messageDto
     *
     * @return ElasticDocumentsMessages
     */
    public function mapToObject($message, $messageDto, array $context = [])
    {
        //no need to convert , we will interact directly with the elastic document
        $message->custom_fields = $message->custom_fields ? $this->formatCustomFields($message->custom_fields) : [];
        return $message;
    }

    /**
     * Format json custom fields.
     *
     * @param array $customFields
     *
     * @return array
     */
    protected function formatCustomFields(array $customFields) : array
    {
        foreach ($customFields as $key => $customField) {
            $customFields[$key] = is_string($customField) && isJson($customField) ? json_decode($customField, true) : $customField;
        }

        return $customFields;
    }
}
