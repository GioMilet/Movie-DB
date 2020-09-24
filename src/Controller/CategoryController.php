<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/category", name="category_")
     */
class CategoryController extends AbstractController
{

     /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/list.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/{id}/view", name="view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function view($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findMoviesByCategory($id);
        return $this->render('category/view.html.twig', [
            'category' => $category
        ]);
    }

     
    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request)
    {
        $newCategory = new Category();

        $form = $this->createForm(CategoryType::class, $newCategory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newCategory);
            $manager->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->render(
            'category/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

    

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function delete(Category $category)
    {
       
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('category_list');
    }
        
    /**
     * @Route("/{id}/update", name="update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function update(Category $category, Request $request)
    {
        
        $form = $this->createForm(CategoryType::class, $category);

       
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "La catégorie a été mise à jour !");
            return $this->redirectToRoute('category_view', ['id'=>$category->getId()]);
        }

        return $this->render(
            'category/update.html.twig',
            [   
                "category"=>$category,
                "form" => $form->createView()
            ]
        );
    }
}
