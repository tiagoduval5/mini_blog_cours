<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController extends AbstractController
{
    public function error(HttpException $exception): Response
    {
        $status = $exception->getStatusCode();

        if ($status === 403) {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig', [
                'status_code' => $status,
            ], new Response('', $status));
        }

        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => $status,
            'status_text' => $exception->getMessage(),
        ], new Response('', $status));
    }
}
