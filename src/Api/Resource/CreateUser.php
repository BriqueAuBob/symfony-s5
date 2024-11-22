<?php declare(strict_types=1);

namespace App\Api\Resource;

use App\Validator\UnregisteredEmail;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 180)]
    #[Assert\Email]
    #[UnregisteredEmail]
    public ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public ?string $password = null;
}
