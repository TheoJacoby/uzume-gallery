<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController
{
    public function show(
        Request $request,
        FlattenException $exception,
        ?DebugLoggerInterface $logger = null
    ): Response {
        $statusCode = $exception->getStatusCode();
        
        // Pour l'erreur 403 (accès refusé), on utilise notre template personnalisé
        if ($statusCode === 403) {
            try {
                return $this->render('bundles/TwigBundle/Exception/error403.html.twig', [
                    'status_code' => $statusCode,
                    'status_text' => $exception->getStatusText(),
                    'exception' => $exception,
                ], new Response('', $statusCode));
            } catch (\Exception $e) {
                // Si le template n'est pas trouvé, utiliser le template générique
                return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
                    'status_code' => $statusCode,
                    'status_text' => $exception->getStatusText(),
                    'exception' => $exception,
                ], new Response('', $statusCode));
            }
        }
        
        // Pour les autres erreurs, on peut créer d'autres templates
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => $statusCode,
            'status_text' => $exception->getStatusText(),
            'exception' => $exception,
        ], new Response('', $statusCode));
    }
}

