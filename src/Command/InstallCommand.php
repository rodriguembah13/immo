<?php

namespace App\Command;

use App\Entity\Configuration;
use App\Utils\Constants;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallCommand extends Command
{
    public const ERROR_PERMISSIONS = 1;
    public const ERROR_CACHE_CLEAN = 2;
    public const ERROR_CACHE_WARMUP = 4;
    public const ERROR_DATABASE = 8;
    public const ERROR_MIGRATIONS = 32;
    protected static $defaultName = 'app:install';
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var string
     */
    protected $rootDir;
    /**
     * @var Connection
     */
    private $connection;
    private $entityManager;
    public function __construct(EntityManagerInterface $em,Connection $connection)
    {
        parent::__construct();
       // $this->rootDir = $projectDirectory;
        $this->connection = $connection;
        $this->entityManager=$em;
    }
    protected function configure()
    {
        $this
            ->setName(self::$defaultDescription)
            ->setDescription('Basic installation for GE-COM')
            ->setHelp('This command will perform the basic installation steps to get GE-COM up and running.');  ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Ge-COM installation running ...');
        $environment = getenv('APP_ENV');

        // create the database, in case it is not yet existing
        try {
            $this->createDatabase($io, $input, $output);
        } catch (\Exception $ex) {
            $io->error('Failed to create database: '.$ex->getMessage());

            return self::ERROR_DATABASE;
        }

        // bootstrap database ONLY via doctrine migrations, so all installation will have the correct and same state
        try {
            $this->importMigrations($io, $output);
        } catch (\Exception $ex) {
            $io->error('Failed to set migration status: '.$ex->getMessage());

            return self::ERROR_MIGRATIONS;
        }
        try {
            $this->initData($io, $output);
           // $this->importSeed($io, $output);
        } catch (\Exception $exception) {
            $io->error('Failed to set fixtures: '.$exception->getMessage());
        }
        try {
            $this->installAsset($io, $output);
        } catch (\Exception $exception) {
            $io->error('Failed to set assets: '.$exception->getMessage());
        }
        // flush the cache, just to make sure ... and ignore result
        $this->rebuildCaches($environment, $io, $input, $output);

        $io->success(
            sprintf('Congratulations! Successfully installed %s version %s (%s)', Constants::SOFTWARE, Constants::VERSION, Constants::STATUS)
        );

        return Command::SUCCESS;
    }
    protected function createDatabase(SymfonyStyle $io, InputInterface $input, OutputInterface $output)
    {
        if ($this->connection->isConnected()) {
            $io->note(sprintf('Database is existing and connection could be established'));

            return;
        }

        if (!$this->askConfirmation($input, $output, sprintf('Create the database "%s" (yes) or skip (no)?', $this->connection->getDatabase()), true)) {
            throw new \Exception('Skipped database creation, aborting installation');
        }

        $options = [];
        if ('sqlite' !== $this->connection->getDatabasePlatform()->getName()) {
            $options = ['--if-not-exists' => true];
        }

        $command = $this->getApplication()->find('doctrine:database:create');
        $result = $command->run(new ArrayInput($options), $output);

        if (0 !== $result) {
            throw new \Exception('Failed creating database. Check your credentials in DATABASE_URL');
        }
    }

    /**
     * @param string $question
     * @param bool   $default
     *
     * @return bool
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $question, $default = false)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelperSet()->get('question');
        $text = sprintf('<info>%s (yes/no)</info> [<comment>%s</comment>]:', $question, $default ? 'yes' : 'no');
        $question = new ConfirmationQuestion(' '.$text.' ', $default, '/^y|yes/i');

        return $questionHelper->ask($input, $output, $question);
    }

    protected function rebuildCaches(string $environment, SymfonyStyle $io, InputInterface $input, OutputInterface $output)
    {
        $io->text('Rebuilding your cache, please be patient ...');

        $command = $this->getApplication()->find('cache:clear');
        try {
            $command->run(new ArrayInput(['--env' => $environment]), $output);
        } catch (\Exception $ex) {
            $io->error('Failed to clear cache: '.$ex->getMessage());

            return self::ERROR_CACHE_CLEAN;
        }

        $command = $this->getApplication()->find('cache:warmup');
        try {
            $command->run(new ArrayInput(['--env' => $environment]), $output);
        } catch (\Exception $ex) {
            $io->error('Failed to warmup cache: '.$ex->getMessage());

            return self::ERROR_CACHE_WARMUP;
        }

        return 0;
    }

    protected function importMigrations(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:migrations:migrate');
        $cmdInput = new ArrayInput(['--allow-no-migration' => true]);
        $cmdInput->setInteractive(false);
        $command->run($cmdInput, $output);

        $io->writeln('');
    }
    protected function importSeed(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $cmdInput = new ArrayInput(['--no-interaction' => true]);
        $cmdInput->setInteractive(false);
        $command->run($cmdInput, $output);

        $io->writeln('');
    }
    protected function installAsset(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('assets:install');
        $cmdInput = new ArrayInput(['--no-interaction' => true]);
        $cmdInput->setInteractive(false);
        $command->run($cmdInput, $output);

        $io->writeln('');
    }
    protected function initData(SymfonyStyle $io, OutputInterface $output)
    {
        $configuration=new Configuration();
        $configuration->setName("Soft house");
        $configuration->setPhone("675066919");
        $this->entityManager->persist($configuration);
        $this->entityManager->flush();

        $this->io->success(sprintf("Successfull"));


        $io->writeln('');
    }
}
