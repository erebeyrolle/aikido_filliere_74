<?php

namespace App\Controller;

use App\Entity\MessageSender;
use App\Form\MessageToSendType;
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
    public function insertDataSendMail(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        $message = new MessageSender;

        $formMessage = $this->createForm(MessageToSendType::class, $message, );
        $formMessage->handleRequest($request);

        if ($request->isMethod('post') && $formMessage->isSubmitted() && $formMessage->isValid()) {
            // $message->setCreatedDate(new \DateTime); // Best way to have date by the Entity directly and the __construct method
            $entityManager = $doctrine->getManager();
            // $email = (new Email())
            $email = (new TemplatedEmail())
            ->from(new Address($message->getEmail(), ($message->getFirstName().' '.($message->getLastName()))))
            ->to('aikidofilliere74@gmail.com')
            ->subject('Demande de renseignements sur la pratique')
            // ->text('Sending emails is fun again!')
            // ->html('<p>' . $message->getMessage() .'</p>');
            ->htmlTemplate('mail/mail.html.twig')
            ->context(['firstname' => ($message->getFirstName()),
                        'lastname' => ($message->getLastName()),
                        'content' => ($message->getMessage()),
            ]);
            $entityManager->persist($message);
            $entityManager->flush();
            $mailer->send($email);
            $this->addFlash('success', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');
            // return new Response('Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');
        }
        return $this->render('contact/contact.html.twig', [
            'message_form' => $formMessage->createView()
        ]);
    }
}
