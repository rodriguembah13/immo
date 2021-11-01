<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Twig\Environment;

class TestMailCommand extends Command
{
    protected static $defaultName = 'app:testMail';
    private $classeRepository;
    private $swift_Mailer;
    private $inscriptionRepository;
    private $twig;

    /**
     * SendMailCommand constructor.
     *
     * @param $planningRepository
     */
    public function __construct(Environment $twig,  MailerInterface $swift_Mailer)
    {
        parent::__construct(null);
        $this->swift_Mailer = $swift_Mailer;
        $this->twig = $twig;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        //$arg2 = $input->getArgument('arg2');

        $this->sendMail('mbah', 'rodrigue@smartworldafriq.com', $arg1);

        $io->success('your is send ');

        return 0;
    }

    public function sendMail($name, $sendMail, $receiveMail)
    {
        $message=new RawMessage("test");
        $envelope= new Envelope(new Address("rodriguembah13@gmail.com"),[new Address("juliombah13@gmail.com")]);
        try {
            $this->swift_Mailer->send($message,$envelope);
        } catch (TransportExceptionInterface $e) {

        }
    }
}
