<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlController extends AbstractController
{
    /**
     * @Route("/", name="article")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticlController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route ("/article/single/{article}", name="single_article")
     * @param Article $article
     */
    public function single(Article $article)
    {
        return $this->render('article/single.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/create", name="create_article")
     * @param Request $request
     */
    public function create(Request $request)
    {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            $article->setCreatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article');
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/article/update/{article}", name="update_article")
     * @param Article $article
     * @return Response
     */
    public function update(Request $request, Article $article)
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('update_article', ['article' => $article->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            $article->setUpdateAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article');
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/article/delete/{article}", name="article_delete")
     * @param Article $article
     * @return Response
     */
    public function delete(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('article');
    }
}
