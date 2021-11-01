<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * When visiting the homepage, this listener redirects the user to the most
 * appropriate localized version according to the browser settings.
 *
 * See http://symfony.com/doc/current/components/http_kernel/introduction.html#the-kernel-request-event
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class RedirectToLocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $supportedLocales;

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(array $supportedLocales, string $locale = 'fr')
    {
        $this->supportedLocales = $supportedLocales;
        $this->defaultLocale = $locale;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $localeNeeded = $request->getSession()->get('locale');
        $referrer = $request->headers->get('referer');
        if (in_array($localeNeeded, $this->supportedLocales)) {
            $request->setLocale($localeNeeded);
        } else {
            $request->setLocale($this->defaultLocale);
        }
    }
}
