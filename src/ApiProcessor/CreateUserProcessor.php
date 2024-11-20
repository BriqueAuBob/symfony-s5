<?php declare(strict_types=1);

namespace App\ApiProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\ApiResource\CreateUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;

final readonly class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private ValidatorInterface $validator,
    ) {
    }

    /** @param CreateUser $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): User {
        $user = new User();
        $user->id = UuidV4::v4();
        $user->email = $data->email;
        $user->password = $data->password;

        $this->validator->validate($user);

        $user->password = $this->hasher->hashPassword($user, $data->password);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
