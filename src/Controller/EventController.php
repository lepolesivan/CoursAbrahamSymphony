<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
use App\Repository\EventRepository;
use DateTime;
use App\Form\EventType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'Evenement',
        ]);
    }
    
    #[Route('/add_event', name: 'app_add_event')]
    public function addEvent( EntityManagerInterface $em) // $em est égual à $pdo
    {
        $event = new Event();
        $event->setTitle('Concert')->setDescription('Le concert de matre gims')->setDate(new DateTime('2024-10-22 10:00:00'))->setLocalisation('Paris')->setMaxParticipant(10);
        
        $em->persist($event); //persist() garde l'information dans son cache avant d'envoyer dans la BDD
        $em->flush();

        return new Response("Event à été créé avec succès");
    }

    #[Route('/get_listEvent', name: 'app_get_listEvent')]
    public function getList( EventRepository $repository)
    {
        $events = $repository->findAll();
        // dd($events); // permet de vérifier si tout fonctionne bien
        return $this->render('event/index.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/update/{id}', name: 'app_update')]
    public function update(EntityManagerInterface $em, EventRepository $repository, int $id)
    {
        $event = $repository->find($id);
        $event->setTitle('update event');
        $event->setCompleted(true);

        $em->flush();

        return new Response("event mise à jour!");
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $em, EventRepository $repository, int $id)
    {
        $event = $repository->find($id);
        if(!$event){
           return new Response("Cette event n'existe pas");
        }
        $em->remove($event);
        $em->flush();

        $events = $repository->findAll();
        return $this->render('event/index.html.twig' , [
            'events' => $events
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        // creer unce instance du formulaire EventType
        $form = $this->createForm(EventType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return new Response("Evenement créer");
        }

        return $this->render('event/create.html.twig', [
            'form' => $form //transmet le formulaire au template
        ]);
    }

}
