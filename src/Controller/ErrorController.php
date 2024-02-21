<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorController extends AbstractController
{
    public function show(Throwable $exception): Response
    {
        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() === 403) {
            return $this->render('access_denied.html.twig', ['error' => $exception]);
        }

        return $this->render('error.html.twig', ['exception' => $exception]);
    }
}
