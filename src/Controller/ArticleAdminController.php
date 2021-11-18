<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function new(EntityManagerInterface $em,Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /** @var Article $article */
            $article = $form->getData();
            $em->persist($article);
            $em->flush();

            $this->addFlash("success", "It's been added successfully");

            return $this->redirectToRoute('app_articles');
        }

        return $this->render("article_admin/new.html.twig",[
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article)
    {
        dd($article);
    }

    /**
     * @Route("/admin/article", name="app_articles")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function list(ArticleRepository $articleRepository)
    {
        $articles =  $articleRepository->findAll();

        return $this->render('article_admin/list.html.twig',[
            'articles' => $articles,
        ]);
    }
}
