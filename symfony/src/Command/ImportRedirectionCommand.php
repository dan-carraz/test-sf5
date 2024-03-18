<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Redirection;
use App\Repository\ProjectRepository;
use App\Repository\RedirectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

#[AsCommand(name: 'app:import:redirection')]
class ImportRedirectionCommand extends Command
{
    private const REDIRECT_DIR = __DIR__.'/../../config/redirect/';
    private const LINE_PATTERN = '/^~(.*) (.*);$/';

    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly RedirectionRepository $redirectionRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projects = $this->projectRepository->findAll();

        foreach ($projects as $project) {
            $directory = self::REDIRECT_DIR.$project->getName();

            if (!is_dir($directory)) {
                continue;
            }

            $files = scandir($directory);

            foreach ($files as $filename) {
                try {
                    $file = new File($directory.'/'.$filename);

                    if ('partial' !== $file->getExtension()) {
                        continue;
                    }

                    $handler = $file->openFile();
                    $redirections = [];

                    foreach ($handler as $index => $line) {
                        $matches = [];
                        if (!preg_match(self::LINE_PATTERN, $line, $matches)) {
                            continue;
                        }

                        $redirections[] = [$matches[1], $matches[2]];


                        if (0 === $index % 100) {
                            $this->redirectionRepository->createRedirections($project, $redirections);
                            $redirections = [];
                        }
                    }

                    $this->redirectionRepository->createRedirections($project, $redirections);

                } catch (\Throwable $t) {
                    $output->writeln($t->getMessage());
                }
            }
        }

        return self::SUCCESS;
    }
}
