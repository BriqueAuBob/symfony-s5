<?php

namespace App\Service;

use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Slug
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function generate(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function getEntityWithSlug(string $slug): ?object
    {
        return $this->entityManager->getRepository(Content::class)->findOneBy(['slug' => $slug]);
    }

    public function getUniqueSlug(string $slug): string
    {
        $originalSlug = $slug;
        $i = 1;
        while ($this->getEntityWithSlug($slug)) {
            $slug = $originalSlug . '-' . $i++;
        }
        return $slug;
    }

    public function get(string $title): string
    {
        $slug = $this->generate($title);
        return $this->getUniqueSlug($slug);
    }
}
