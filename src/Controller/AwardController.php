<?php

namespace App\Controller;

use App\Entity\Award;
use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/award", name="award_")
     */
class AwardController extends AbstractController
{

      /** 
     * @Route("/list", name="list", methods={"GET"})
     */
    public function showAllAward()
    {

        $awards = $this->getDoctrine()->getRepository(Award::class)->findAll();
        return $this->render('award/list.html.twig', [
            'awards' => $awards
        ]);
    }


    /**
     * @Route("/{id}/view", name="view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewAward(Award $award)
    {

        return $this->render('award/view.html.twig', [
            'award' => $award
        ]);
    }

      /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request) {

        if ($request->getMethod() == Request::METHOD_POST) {
            $name = $request->request->get('name');
            if (empty($name)) {
                $this->addFlash('warning', 'Hep toi la ! Donne un nom à cette award !');
            }

             if(!empty($name)) {
                $manager = $this->getDoctrine()->getManager();
                $award = new Award();
                $award->setname($name);
  
                $manager->persist($award);
                $manager->flush();

                return $this->redirectToRoute('award_list');
            }

        }
    

        return $this->render('award/add.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function delete($id){
        //je recupére mon entité
        $award = $this->getDoctrine()->getRepository(Award::class)->find($id);

        if(!$award){
            throw $this->createNotFoundException("Cette catégorie n'existe pas !");
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($award);
        $manager->flush();

        return $this->redirectToRoute('award_list');
    }
        
    /**
     * @Route("/{id}/update", name="update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function update(Award $award, Request $request)
    {
        if(!$award) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        
        if($request->getMethod() == Request::METHOD_POST) {
            
            $name = $request->request->get('name');
            if(empty($name)) {
                $this->addFlash('warning', 'La catégorie ne peut pas être vide !');
            }

             if(!empty($name) ) {
                $manager = $this->getDoctrine()->getManager();
                $award->setname($name);
                $manager->flush();

                return $this->redirectToRoute('award_list');
            }

        }
        return $this->render('award/update.html.twig', [
            'award' => $award
        ]);
    }

}
