<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Todo;
use App\Repository\TodoRepository;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(): Response
    {
        $tasks = [
            ['title' => 'Acheter du pain', 'completed' => false],
            ['title' => 'Finir le projet Symfony', 'completed' => true],
            ['title' => 'Appeler le médecin pour un rendez-vous', 'completed' => false],
            ['title' => 'Faire du sport', 'completed' => true],
            ['title' => 'Lire un livre sur les bonnes pratiques en développement', 'completed' => false],
        ];
        
        return $this->render('todo/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/add_task', name: 'app_add_task')]
    public function addTask( EntityManagerInterface $em) // $em est égual à $pdo
    {
        $todo = new Todo();
        $todo->setTitle('Task two')->setCompleted(false);
        
        $em->persist($todo); //persist() garde l'information dans son cache avant d'envoyer dans la BDD
        $em->flush();

        return new Response("Todo à été créé avec succès");
    }

    #[Route('/get_list', name: 'app_get_list')]
    public function getList( TodoRepository $repository)
    {
        $todos = $repository->findAll();
        // dd($todos); // permet de vérifier si tout fonctionne bien
        return $this->render('todo/index.html.twig', [
            'todos' => $todos
        ]);
    }

    #[Route('/update/{id}', name: 'app_update')]
    public function update(EntityManagerInterface $em, TodoRepository $repository, int $id)
    {
        $todo = $repository->find($id);
        $todo->setTitle('update todo');
        $todo->setCompleted(true);

        $em->flush();

        return new Response("todo mise à jour!");
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $em, TodoRepository $repository, int $id)
    {
        $todo = $repository->find($id);
        if(!$todo){
           return new Response("Cette todo n'existe pas");
        }
        $em->remove($todo);
        $em->flush();

        $todos = $repository->findAll();
        return $this->render('todo/index.html.twig' , [
            'todos' => $todos
        ]);
    }
}
