<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class KernelTerminateSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $route = $event->getRequest()->getRequestUri();
        $statusCode = $event->getResponse()->getStatusCode();

        $this->logger->info('Coucou, quelqu\'un a visité '.$route.' et a obtenu un code '. $statusCode);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.terminate' => 'onKernelTerminate',
        ];
    }
}
