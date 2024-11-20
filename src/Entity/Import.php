<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Api\Action\ImportAction;

#[ApiResource]
#[Post(controller: ImportAction::class, deserialize: false)]
class Import
{}
