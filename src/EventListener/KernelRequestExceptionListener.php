<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class KernelRequestExceptionListener implements EventSubscriberInterface
{
    public function onKernelRequest(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof TooManyRequestsHttpException) {
            $response = new JsonResponse([
                'message' => 'Too many requests'
            ], Response::HTTP_TOO_MANY_REQUESTS);

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onKernelRequest',
        ];
    }
}