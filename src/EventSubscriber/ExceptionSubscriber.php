<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $data = [
            'status' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file'=>$exception->getFile(),
            'line'=>$exception->getLine(),
            'trace'=>$exception->getTrace()
        ];

        $response = new JsonResponse($data);
        if ($exception instanceof HttpExceptionInterface){
            $response->setStatusCode($exception->getStatusCode());
            //$response->headers->replace($exception->getHeaders());
        }else{
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
