<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogExampleController extends AbstractController
{
    #[Route('/log/example', name: 'log_example')]
    public function index(LoggerInterface $logger): Response
    {
        // Log a message
        $logger->info('Visited the log example page.');

        // Generate a response (using Twig or any other response type)
        return $this->render('log_example/index.html.twig', [
            'controller_name' => 'LogExampleController',
        ]);
    }
}