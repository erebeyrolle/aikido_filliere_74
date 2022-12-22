<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function sendMessage(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        $message = new Message;

        $formMessage = $this->createForm(MessageType::class, $message);
        $formMessage->handleRequest($request);

        if ($request->isMethod('post') && $formMessage->isSubmitted() && $formMessage->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $this->sendMail($mailer, $message);

            $this->addFlash('success', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');
        }
        return $this->render('contact/contact.html.twig', [
            'message_form' => $formMessage->createView()
        ]);
    }

    private function sendMail(MailerInterface $mailer, Message $message): void{
        $email = (new TemplatedEmail())
            ->from(new Address($message->getEmail(), ($message->getFirstName() . ' ' . ($message->getLastName()))))
            ->to('aikidofilliere74@gmail.com')
            ->subject('Demande de renseignements sur la pratique')
            // ->text('Sending emails is fun again!')
            // ->html('<p>' . $message->getMessage() .'</p>');
            ->htmlTemplate('mail/mail.html.twig')
            ->context(['firstname' => ($message->getFirstName()),
                'lastname' => ($message->getLastName()),
                'content' => ($message->getMessage()),
            ]);

        $mailer->send($email);
    }
}
