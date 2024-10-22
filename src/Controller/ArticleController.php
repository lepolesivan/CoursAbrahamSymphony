<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    // #[IsGranted("ROLE_ADMIN")]
    public function index(ArticleRepository $repository): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_denied');
        }
        $articles = $repository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/create_article', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        // creer unce instance du formulaire ArticleType
        $form = $this->createForm(ArticleType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return new Response("Article ajouter");
        }

        return $this->render('article/create.html.twig', [
            'form' => $form //transmet le formulaire au template
        ]);
    }

    #[Route('/update_article/{id}', name: 'app_update_article')]
    public function update(EntityManagerInterface $em, ArticleRepository $repository, int $id, Request $request)
    {
        // Je récupère un article
        $articles = $repository->find($id);

        // dd($articles);

        // Je créai un formulaire
        $form = $this->createForm(ArticleType::class);
        // // Sert à vérifier la soumission du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            // dd($data);

            $article = $repository->find($data->getId());
            $article->setTitle($data->getTitle())
                    ->setContent($data->getContent());

            $em->flush();

            return new Response("Evenement créer");
        }

        return $this->render("article/update.html.twig", [
            'form' => $form,
            'articles' => $articles
        ]);

    }

    #[Route('delete_article/{id}', name: 'app_delete_article')]
    public function delete(EntityManagerInterface $em, ArticleRepository $repository, int $id)
    {
        $article = $repository->find($id);
        $em->remove($article);
        $em->flush();

        $articles = $repository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
