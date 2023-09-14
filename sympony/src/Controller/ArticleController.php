<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $article->setTitle("Article1");
        $em = $doctrine->getManager();
        // $em->persist($article);
        // $em->flush();
        $getArticle = $em ->getRepository(Article::class)->findOneBy(
            [
                'id'=>1
            ]
        );
        $em->remove($getArticle);
        $em->flush();
        // return new Response("Article was created");
        return $this->render('article/index.html.twig', [
            'article' => $getArticle,
        ]);
    }
}
