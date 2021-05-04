<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\Comments;

class CommentController extends AbstractController
{
    /**
     * @Route ("/article/{article}/comment/create", name="comment_create")
     * @param Article $article
     */
    public function create(Article $article, Comments $comments)
    {
//        dd($article->getId());
        $data = $comments->create_comment($article);

        if ($data['save']) {
            return $this->redirectToRoute('single_article', ['article' => $article->getId()]);
        }

        return $this->render('comment/form.html.twig', [
            'article' => $article,
            'form' => $data['form']
        ]);
    }

    /**
     * @Route ("/article/{article}/comment/{comment}", name="comment_update")
     * @param Article $article
     * @param Comments $comments
     * @param Comment $comment
     */
    public function comment_update(Article $article, Comment $comment, Comments $comments)
    {

        $data = $comments->update_comment($article, $comment);

        if ($data['save']) {
            return $this->redirectToRoute('single_article');
        }

        return $this->render('article/single.html.twig', [
            'article' => $article,
            'comment' => $comment,
        ]);
    }

    /**
     * @Route ("/comment/delete/{article}/{comment}", name="comment_delete")
     * @param Article $article
     * @param Comment $comment
     * @return Response
     */
    public function delete(Article $article, Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('single_article', ['article' => $article->getId()]);
    }
}
