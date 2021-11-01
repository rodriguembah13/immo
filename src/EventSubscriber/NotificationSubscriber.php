<?php

namespace App\EventSubscriber;

use App\Event\NotificationListEvent;
use App\Repository\NotificationRepository;
use App\Utils\Constants;
use App\Utils\NotificationModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class NotificationSubscriber implements EventSubscriberInterface
{
    private $notificationRepository;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $security;


    /**
     * @param AuthorizationCheckerInterface $security
     */
    public function __construct(NotificationRepository $notificationRepository,Security $security)
    {
        $this->security = $security;
        $this->notificationRepository=$notificationRepository;
    }


    public static function getSubscribedEvents()
    {
        return [
            NotificationListEvent::class => ['onNotifications', 100],
        ];
    }
    /**
     * @param NotificationListEvent $event
     */
    public function onNotifications(NotificationListEvent $event)
    {
        $notifications=$this->notificationRepository->findBy(['receiver'=>$this->security->getUser(),'status'=>'pending']);
        foreach ($notifications as $notifi){
            $notification = new NotificationModel();

            $notification
                ->setId($notifi->getNotifiedId())
                ->setMessage($notifi->getMessage())
                ->setType($notifi->getType())
                ->setIcon($notifi->getSender())
            ;
            $event->addNotification($notification);
        }

        $lists=$this->wplocationRepository->findByProductPerimeAll();
        if (sizeof($lists)){
            $notification = new NotificationModel();

            $notification
                ->setId("")
                ->setMessage("Vous avez ".sizeof($lists)." produits perimés")
                ->setType("perimable")
                ->setIcon("")
            ;
            $event->addNotification($notification);
        }


        $liststock=$this->stockRepository->findByProductAlertAll();
        if (sizeof($liststock)>0){
            $notification2 = new NotificationModel();
            $notification2
                ->setId("")
                ->setMessage("Vous avez ".sizeof($liststock)." produits en alerte stock")
                ->setType("alertestock")
                ->setIcon("")
            ;
            $event->addNotification($notification2);
        }


    }
}
