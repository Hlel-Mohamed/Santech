<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteAndRecreateDatabaseWithStructureAndDataCommand extends Command
{
    protected static $defaultName = 'app:recreate-database';

    protected function configure(): void
    {
        $this
            ->setDescription('Deletes and recreates the database with structure and data.')
            ->setHelp('This command allows you to recreate your database...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('suppression et recreation');

        try {
            $this->runSymfonyCommand($input, $output, 'doctrine:database:drop', true);
            $this->runSymfonyCommand($input, $output, 'doctrine:database:create');
            $this->runSymfonyCommand($input, $output, 'doctrine:migration:migrate');
            $this->runSymfonyCommand($input, $output, 'doctrine:fixtures:load');
        } catch (\LogicException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        } catch (ExceptionInterface $e) {
            echo "ExceptionInterface: " . $e->getMessage() . "\n";
        }

        $output->writeln('Database recreated successfully!');

        return Command::SUCCESS;
    }

    /**
     * @throws ExceptionInterface
     */
    public function runSymfonyCommand(
        InputInterface $input,
        OutputInterface $output,
        string $command,
        bool $forceOptions = false
    ): void {
        $application = $this->getApplication();
        if (!$application) {
            throw new \LogicException("No application : (");
        }
        $command = $application->find($command);
        if ($forceOptions) {
            $input = new ArrayInput(['--force' => true]);
        }
        $input->setInteractive(false);
        $command->run($input, $output);
    }
}