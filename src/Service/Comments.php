<?php


namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Comments extends AbstractController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Comments constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * create_comment
     * @param $article
     */
    public function create_comment($article)
    {
        $request = $this->request->getRequest();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_create', ['article' => $article->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            /** @var $user User */
            $user = $this->getUser();

            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setArticle($article);
            $comment->setUserName($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return ['save' => true];
        }

        return ['save' => false, 'form' => $form->createView()];
    }

    /**
     * update_comment
     * @param $article
     * @param $comment
     */
    public function update_comment(Article $article, Comment $comment)
    {
        $request = $this->request->getRequest();

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_update', [
                'article' => $article->getId(),
                'comment' => $comment->getId(),
            ]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $comment->setUpdatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return ['save' => true];
        }

        return ['save' => false, 'form' => $form->createView()];
    }
}