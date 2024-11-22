<?php declare(strict_types=1);

namespace App\Api\Resource;

class CreateContent
{
    public ?string $title = null;
    public ?string $content = null;
    public ?string $thumbnail = null;
    public ?array $tags = [];
    public ?array $meta = [];
}
