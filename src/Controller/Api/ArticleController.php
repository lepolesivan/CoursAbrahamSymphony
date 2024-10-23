<?php
    namespace App\Controller\Api;

    use App\Entity\Article;
    use Symfony\Component\Routing\Attribute\Route;
    use App\Repository\ArticleRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Serializer\SerializerInterface;

    class ArticleController extends AbstractController
    {
        #[Route('/api/articles', name: 'api_articles')]
        public function getArticle(ArticleRepository $repository, SerializerInterface $serialiser)
        {
            $articles = $repository->findAll();
            //Je transforme mes articlkes en format Json
            $jsonArticle = $serialiser->serialize($articles, 'json');

            return new JsonResponse($jsonArticle, 200, [], true);
        }
        
        #[Route('/api/post_article', methods: ['POST'])]
        public function creatArticle(Request $request, SerializerInterface $serialiser, EntityManagerInterface $em)
        {
            $article = $request->getContent();
            $deserialisedArticle = $serialiser->deserialize($article, Article::class, 'json');
            $em->persist($deserialisedArticle);
            $em->flush();

            return new JsonResponse(['message' => 'article ajoute'], 201);
        }

        #[Route('/api/update/{id}', methods: ['PUT'])]
        public function updateArticle(EntityManagerInterface $em, ArticleRepository $repository, int $id, Request $request, SerializerInterface $serialiser)
        {
            $article = $repository->find($id);
            if(!$article){
                return new JsonResponse(['message' => 'Cet article existe pas'], 404);
            }

            $data = $request->getContent();
            $deserialisedArticle = $serialiser->deserialize($data, Article::class, 'json', ['object_to_populate' => $article]);
            $em->flush();

            return new JsonResponse(['message' => 'article modifier avec succès'], 201);
        }

        #[Route('api/delete/{id}', methods: ['DELETE'])]
        public function delete(EntityManagerInterface $em, ArticleRepository $repository, int $id)
        {
            $article = $repository->find($id);
            if(!$article){
                return new JsonResponse(['message' => 'Cet article existe pas'], 404);
            }
            
            $em->remove($article);
            $em->flush();
            
            return new JsonResponse(['message' => 'Article supprimé'], 201);
        }
    }