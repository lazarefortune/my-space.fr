<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    // Langue par défaut
    private mixed $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // Si la langue n'est pas définie dans la session, on la définit
        if (!$request->getSession()->has('_locale')) {
            $request->getSession()->set('_locale', $this->defaultLocale);
        }

        // On définit la langue de l'application
        $request->setLocale($request->getSession()->get('_locale'));
    }

    public static function getSubscribedEvents(): array
    {
        return [
//            RequestEvent::class => 'onRequestEvent',
            // On doit définir une priorité élevée
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
