<?php

namespace App\Controller;

use App\Class\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'app_contact')]
    public function contact(MailerInterface $mailer, Request $request): Response
    {
        $siteKey = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($contact->getEmail())
                ->to('info@uzume-gallery.be')
                ->subject($contact->getSubject())
                ->text($contact->getMessage());
            $mailer->send($email);
            return $this->render('contact/thanks.html.twig');
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
            'recaptcha_site_key' => $siteKey,
        ]);

    }
}

