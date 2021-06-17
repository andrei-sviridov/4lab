<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{topic}', name: 'createCommentForm')]
    public function createComment(Topic $topic, EntityManagerInterface $em, Request $request): Response
    {
		$comment = new Comment();
		
		$form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('createCommentForm', [
                'topic' => $topic->getId()
            ]),
            'method' => 'POST',
        ]);
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getUser();
			
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setTopic($topic);
			$comment->setAutor($user);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('topic_show', ['id' => $topic->getId()]);
        }
		
        return $this->render('comment/form.html.twig', [
            'form' => $form->createView(),
			'topic' => $topic,
        ]);
    }
}
