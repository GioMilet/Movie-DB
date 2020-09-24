<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Movie;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/person", name="person_")
     */
class PersonController extends AbstractController
{

      /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function showAllPerson()
    {

        $persons = $this->getDoctrine()->getRepository(Person::class)->findAll();
        return $this->render('person/list.html.twig', [
            'persons' => $persons
        ]);
    }


    /**
     * @Route("/{id}/view", name="view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewPerson($id)
    {
        
        $person = $this->getDoctrine()->getRepository(Person::class)->findMoviesByDirector($id);
        return $this->render('person/view.html.twig', [
            'person' => $person
        ]);
    }

      /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
     public function addPerson(Request $request)
    {

        $newPerson = new Person();

        $form = $this->createForm(PersonType::class, $newPerson);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $manager =$this->getDoctrine()->getManager();
            $manager->persist($newPerson);
            $manager->flush();
            
            return $this->redirectToRoute('person_view',['id'=>$newPerson->getId()]);
        }

        return $this->render(
            'person/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function delete(Person $person){

        $personName = $person->getName();

        if(!$person->getDirectedMovies()->isEmpty()) {
            $this->addFlash('warning', "Supprimer $personName ne sera possible que s'il n'est plus réalisateur");
            return $this->redirectToRoute('person_update', ['id' => $person->getId()]);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($person);
        $manager->flush();
        // on retourne sur la liste des films
        $this->addFlash('info', "$personName a été supprimée");
        return $this->redirectToRoute('person_list');
    }

    /**
     * @Route("/{id}/update", name="update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function updatePerson(Request $request, Person $person)
    {
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute('person_view', ['id' => $person->getId()]);
        }

        return $this->render('person/update.html.twig', [
            "personForm" => $form->createView(),
            "person"=>$person
        ]);
    }

    
}

