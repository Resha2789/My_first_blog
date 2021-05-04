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
     * @param $request
     * @param $article
     */
    public function create_comment($request, $article)
    {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_create', [
                'article' => $article->getId()
            ]),
            'method' => 'POST'
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
     * @param $request
     * @param Article $article
     * @param Comment $comment
     */
    public function update_comment($request, Article $article, Comment $comment)
    {


        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_update', [
                'article' => $article->getId(),
                'comment' => $comment->getId()
            ]),
            'method' => 'POST'
        ]);

//        $request = $request->getRequest();

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