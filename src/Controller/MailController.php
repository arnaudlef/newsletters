<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;

class MailController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function index(): Response
    {
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }

    /**
     * @Route("/email", name="app_email")
     */
    public function sendEmail(MailerInterface $mailer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $email = $request->request->get('email');


        $newsletter = $request->request->get('newsletter_text');

        $email = (new Email())
            ->from('hello@example.com')
            ->to($email)
            ->subject('Time for Symfony Mailer!')
            ->text($newsletter);

        $mailer->send($email);
    }
}
