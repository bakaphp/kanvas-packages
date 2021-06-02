<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Dto;

class Messages
{
    public int $id;
    public int $apps_id;
    public int $companies_id;
    public array $users;
    public array $message_types;
    public ?string $message = null;
    public int $reactions_count;
    public int $comments_count;
    public array $comments = [];
}
