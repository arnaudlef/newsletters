<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Service\MarkdownHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        $newsletter = $this->getDoctrine()
            ->getRepository(Newsletter::class)
            ->findAll();

        return $this->render('newsletter/homepage.html.twig', [
            'controller_name' => 'NewsletterController',
            'newsletters' => $newsletter
        ]);
    }

    /**
     * @Route("/newsletter/show/{id}", name="app_newsletter_show")
     */
    public function show($id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Newsletter::class);
        $newsletter = $repository->findOneBy(['id' => $id]);
        return $this->render('newsletter/show.html.twig', [
            'newsletterId' => $id,
            'newsletterText' => $newsletter->getText()
        ]);
    }

    /**
     * @Route("/newsletter/add", name="app_newsletter_add")
     */
    public function addNewsletter(Request $request, EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $newsletter = new Newsletter();

        $newsletter->setText($request->request->get('text'));

        $entityManager->persist($newsletter);

        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("/newsletter/update/{id}", name="app_newsletter_update")
     */
    public function updateNewsletter($id, Request $request, EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $newsletter = $entityManager->getRepository(Newsletter::class)->find($id);

        if (!$newsletter) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $newsletter->setText($request->request->get('text'));
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("/newsletter/delete/{id}", name="app_newsletter_delete")
     */
    public function deleteNewsletter($id, Request $request, EntityManagerInterface $entityManager)
    {
        $newsletter = $this->getDoctrine()
            ->getRepository(Newsletter::class)
            ->find($id);
        $entityManager->remove($newsletter);
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }


}
