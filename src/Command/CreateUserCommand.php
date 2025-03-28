<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;

#[AsCommand(
    name: 'app:security:create-user',
    description: 'Creates a new user.',
    hidden: false,
)]
class CreateUserCommand extends Command
{
    public function __construct(
        protected UserRepository $userRepository,
        protected EntityManagerInterface $entityManager,
        protected UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addOption('firstName', 'fn', InputArgument::OPTIONAL, 'User first name')
            ->addOption('lastName', 'ln', InputArgument::OPTIONAL, 'User last name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $firstName = $input->getOption('firstName');
        $lastName = $input->getOption('lastName');

        $user = new User();
        $user->id = UuidV4::v4();
        $user->email = $email;
        $user->password = $this->passwordHasher->hashPassword($user, $password);
        $user->firstName = $firstName;
        $user->lastName = $lastName;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User successfully generated!');

        return Command::SUCCESS;
    }
}
