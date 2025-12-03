<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/images/{path}', name: 'app_images', requirements: ['path' => '.+'])]
    public function serveImage(string $path): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/assets/images/' . $path;
        
        if (!file_exists($filePath) || !is_file($filePath)) {
            throw $this->createNotFoundException('Image not found');
        }
        
        return new BinaryFileResponse($filePath);
    }
}

