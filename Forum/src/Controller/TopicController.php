<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Comment;
use App\Form\Topic1Type;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TopicController extends AbstractController
{
    #[Route('/', name: 'topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(Topic1Type::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $topic->setAutor($user);
            $topic->setCreatedAt(new \DateTime('now'));
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('topic_index');
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'topic_show', methods: ['GET'])]
    public function show(Topic $topic, EntityManagerInterface $em): Response
    {
        $comments = $em->getRepository(Comment::class)->findBy(['topic' => $topic], ['id' => 'DESC']);

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'comments'  =>  $comments,
        ]);
    }
}
