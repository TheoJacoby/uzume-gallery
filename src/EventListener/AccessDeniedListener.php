<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class AccessDeniedListener implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Priorité élevée pour intercepter avant les autres listeners
            KernelEvents::EXCEPTION => ['onKernelException', 255],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Intercepter uniquement les erreurs 403 (accès refusé)
        // Vérifier aussi si c'est une AccessDeniedException du Security
        if ($exception instanceof AccessDeniedHttpException 
            || ($exception instanceof \Symfony\Component\Security\Core\Exception\AccessDeniedException)) {
            
            $response = new Response();
            $response->setStatusCode(403);

            try {
                // Rendre notre template personnalisé
                $content = $this->twig->render('bundles/TwigBundle/Exception/error403.html.twig', [
                    'status_code' => 403,
                    'status_text' => 'Accès Refusé',
                ]);

                $response->setContent($content);
                $event->setResponse($response);
                $event->stopPropagation(); // Empêcher les autres listeners de traiter cette exception
            } catch (\Exception $e) {
                // Si le template ne peut pas être rendu, laisser Symfony gérer l'erreur
            }
        }
    }
}

