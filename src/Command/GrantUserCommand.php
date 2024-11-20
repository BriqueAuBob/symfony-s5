<?php

namespace App\Command;

use App\Entity\User;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:security:grant-user',
    description: 'Grant privileges to a user.',
)]
class GrantUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'User email to change roles');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        if (!$email) {
            $io->error('Please provide an email address.');
            return Command::FAILURE;
        }

        // Grant privileges to user with email $email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            $io->error('User with email: ' . $email . ' not found.');
            return Command::FAILURE;
        }

        $roles = Role::getRoles();
        $selectedRoles = $io->choice('Select roles', $roles, null, true);
        $user->setRoles($selectedRoles);

        $this->entityManager->flush();

        $io->success('Granted privileges to user with email: ' . $email . '. Roles: ' . implode(', ', $selectedRoles));

        return Command::SUCCESS;
    }
}
