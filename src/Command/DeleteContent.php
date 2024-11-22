<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Content;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:content:delete',
    description: 'Delete all content.',
)]
class DeleteContent extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Delete all content');

        $this->entityManager->getRepository(Content::class)->createQueryBuilder('c')->delete()->getQuery()->execute();
        $this->entityManager->flush();

        $io->success('Deleted all content.');

        return Command::SUCCESS;
    }
}
