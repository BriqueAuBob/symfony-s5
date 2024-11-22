<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Tokens;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    public function __construct(private Tokens $tokenService)
    {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            throw $this->createAccessDeniedException();
        }

        return $this->json(['token' => $this->tokenService->generateTokenForUser(
            $user->getUserIdentifier()
        ), 'user' => $user->getUserIdentifier()]);
    }

    #[Route('/api/user', name: 'api_test', methods: ['GET'])]
    public function test(#[CurrentUser] User $user): Response
    {
        return $this->json(['user' => $user->getUserIdentifier()]);
    }
}
