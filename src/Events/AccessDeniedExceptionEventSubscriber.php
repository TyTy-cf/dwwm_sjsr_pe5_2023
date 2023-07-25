<?php

namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

class AccessDeniedExceptionEventSubscriber implements EventSubscriberInterface
{

    public function __construct(private Environment $twig) { }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                'onKernelException', 2
            ]
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (false === $exception instanceof AccessDeniedException) {
            return;
        }

        $event->setResponse(
            new Response(
                $this->twig->render('front/exception/access_denied.html.twig'),
                403
            )
        );
    }
}
