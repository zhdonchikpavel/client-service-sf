<?php 
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        // Get incoming request
        $request   = $event->getRequest();

        // Check if it is a rest api request
        $this->logger->info($request->headers->get('Content-Type'));

        // if ('application/json' === $request->headers->get('Content-Type'))
        // {
        if ($exception instanceof NotFoundHttpException) {
            // Customize your response object to display the exception details
            preg_match('/\\\\Entity\\\\([^\s]+)/', $exception->getMessage(), $entity_match);
            $response = new JsonResponse([
                'message'       => $entity_match ? "{$entity_match[1]} not found" : $exception->getMessage(),
                'code'          => $exception->getCode(),
                'asdas'          => $entity_match,
                // 'traces'        => $exception->getTrace()
            ]);

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // sends the modified response object to the event
            $event->setResponse($response);
        }
    }
}