<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Dto;

class Comments
{
    public int $id;
    public int $apps_id;
    public int $companies_id;
    public $users;
    public int $message_id;
    public $message;
    public int $reactions_count;
    public int $parent_id;
    public string $created_at;
    public ?string $updated_at = null;
    public int $is_deleted = 0;
}
