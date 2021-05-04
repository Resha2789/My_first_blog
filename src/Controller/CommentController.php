<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Comments;

class CommentController extends AbstractController
{
    /**
     * @Route ("/article/{article}/comment/create", name="comment_create")
     * @param Request $request
     * @param Article $article
     * @param Comments $comments
     */
    public function comment_create(Request $request, Article $article, Comments $comments)
    {
        $data = $comments->create_comment($request, $article);

        if ($data['save']) {
            return $this->redirectToRoute('single_article', ['article' => $article->getId(), 'comment_text'=>'', 'comment_val'=>'']);
        }

        return $this->render('comment/form.html.twig', [
            'article' => $article,
            'form' => $data['form']
        ]);
    }

    /**
     * @Route ("/article/{article}/comment/{comment}/update", name="comment_update")
     * @param Request $request
     * @param Article $article
     * @param Comments $comments
     * @param Comment $comment
     * @return Response
     */
    public function comment_update(Request $request, Article $article, Comment $comment, Comments $comments)
    {
        $data = $comments->update_comment($request, $article, $comment);

        if ($data['save']) {
            return $this->redirectToRoute('single_article', ['article' => $article->getId(), 'comment_text'=>'', 'comment_val'=>'']);
        }

        return $this->render('comment/form.html.twig', [
            'article' => $article,
            'form' => $data['form']
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

        return $this->redirectToRoute('single_article', ['article' => $article->getId(), 'comment_text'=>'', 'comment_val'=>'']);
    }
}
