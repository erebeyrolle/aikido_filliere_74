<?php

namespace App\Controller;

use App\Entity\MessageSender;
use App\Form\MessageToSendType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function insert(Request $request, ManagerRegistry $doctrine): Response
    {
        $message = new MessageSender;

        $formMessage = $this->createForm(MessageToSendType::class, $message,);
        $formMessage->handleRequest($request);

        if ($request->isMethod('post') && $formMessage->isSubmitted() && $formMessage->isValid()) {
            // $message->setCreatedDate(new \DateTime); // Best way to have date by the Entity directly and the __construct method
            $entityManager = $doctrine->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
        }
        return $this->render('contact/contact.html.twig', [
            'message_form' => $formMessage->createView()
        ]);
    }
}
