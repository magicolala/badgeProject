<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Service\BadgeManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController
{
    /**
     * @Route("/create", name="comment_create")
     * @param Request $request
     * @param EntityManager $em
     * @param BadgeManager $badgeManager
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function newAction(Request $request, EntityManagerInterface $em, BadgeManager $badgeManager): Response
    {
        /** @var $user User */
        $user = $this->getUser();

        $comment = new Comment();
        $comment->setUser($user);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->getConnection()->beginTransaction();
            $em->flush();

            // Déblocage du badge
            $comments_count = $em->getRepository("App:Comment")->countForUser($user->getId());
            $badgeManager->checkAndUnlock($user, 'comment', $comments_count);
            $em->getConnection()->commit();
        }

        $comments = $em->getRepository("App:Comment")->findAll();
        $badgeUnlocks = $user->getBadgeUnlocks();

        return $this->render('comment/new.html.twig', [
            "comments" => $comments,
            'form' => $form->createView(),
            "badgeUnlocks" => $badgeUnlocks
        ]);
    }
}