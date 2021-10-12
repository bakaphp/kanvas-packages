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
     * @param ElasticDocumentsMessages $source
     * @param DtoMessages $destination
     *
     * @return \Kanvas\Packages\Social\ElasticDocuments\Messages|\stdClass
     */
    public function mapToObject($source, $destination, array $context = [])
    {
        //when its empty , its from the message list
        $message = !empty($source->getData()) ? (object) $source->getData() : $source;

        $message = $this->decodeMessage($message);

        if ($message->related_messages_count) {
            foreach ($message->related_messages as $key => $relatedMessage) {
                $message->related_messages[$key] = $this->decodeMessage((object) $relatedMessage);
            }
        }
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

    /**
     * decodeMessage.
     *
     * @param  object $message
     *
     * @return object
     */
    public function decodeMessage(object $message) : object
    {
        if (isset($message->message['data'])) {
            $message->message['data'] = is_string($message->message['data']) && isJson($message->message['data']) ? json_decode($message->message['data'], true) : [];
        }
        return $message;
    }
}
