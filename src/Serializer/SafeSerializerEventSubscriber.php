<?php


namespace App\Serializer;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SafeSerializerEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException']
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof SafeSerializerValidationException) {
            $event->setResponse(
                new JsonResponse(
                    $event->getThrowable()->payload,
                    Response::HTTP_BAD_REQUEST
                )
            );
        }
    }
}