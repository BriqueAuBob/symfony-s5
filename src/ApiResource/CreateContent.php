<?php

namespace App\ApiResource;

class CreateContent
{
    public ?string $title = null;
    public ?string $content = null;
    public ?string $thumbnail = null;
    public ?array $tags = [];
    public ?User $author = null;
    public ?array $meta = [];
}
