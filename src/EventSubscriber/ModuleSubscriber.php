<?php

namespace App\EventSubscriber;

use App\Repository\ConfigurationRepository;
use App\Repository\ModuleRepository;
use App\Repository\SiteRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class ModuleSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $moduleRepository;
    private $siteRepository;
    private $configurationRepository;

    /**
     * @param $twig
     * @param $moduleRepository
     */
    public function __construct(Environment $twig,ConfigurationRepository $configurationRepository,ModuleRepository $moduleRepository,SiteRepository $siteRepository)
    {
        $this->twig = $twig;
        $this->moduleRepository=$moduleRepository;
        $this->siteRepository=$siteRepository;
        $this->configurationRepository=$configurationRepository;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        if ($this->configurationRepository->findOneByLast()==null){
            $this->twig->addGlobal('configuration',$this->configurationRepository->findOneByLast());
        }else{
            $this->twig->addGlobal('configuration',$this->configurationRepository->findOneByLast());
        }

        $this->twig->addGlobal('sites',$this->siteRepository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
