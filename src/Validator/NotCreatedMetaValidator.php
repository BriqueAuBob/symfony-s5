<?php

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotCreatedMetaValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof NotCreatedMeta) {
            throw new UnexpectedTypeException($constraint, NotCreatedMeta::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->entityManager->getRepository(User::class)->findOneBy(['name' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
