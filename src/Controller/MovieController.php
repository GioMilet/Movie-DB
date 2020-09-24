<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\MovieActor;
use App\Entity\Person;
use App\Form\MovieActorType;
use App\Form\MovieType;
use App\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/movie", name="movie_"))
 */
class MovieController extends AbstractController
{

    
    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function showAllMovies(Request $request)
    {
        $search = $request->query->get("search", "");
              
        $movies = $this->getDoctrine()->getRepository(Movie::class)->searchMovies($search);
            
        return $this->render('movie/list.html.twig', [
            "movies" => $movies,
            "search" => $search
        ]);
    }


  
     
    /**
     * @Route("/{id}/view", name="view", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function showMovie($id)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->findWithActors($id);

        if (!$movie) {
            throw $this->createNotFoundException('Ce film n\'existe pas ou n\'est pas repertorié');
        }

        return $this->render('movie/view.html.twig', ["movie"=>$movie]);
    }

 
    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function addMovie(Request $request,Slugger $slugger)    {
        $newMovie = new Movie();

        
        $form = $this->createForm(MovieType::class, $newMovie);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

              // On ajoute le slug du $movie à partir de son titre
              $slug = $slugger->slugify($newMovie->getTitle());
              $newMovie->setSlug($slug);
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $filename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $filename
                );

                $newMovie->setImageFilename($filename);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newMovie);
            $manager->flush();
            $movieTitle = $newMovie->getTitle();
            $this->addFlash('success', "Le film $movieTitle a été ajouté" );

            return $this->redirectToRoute('movie_list');
        }

        return $this->render('movie/add.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function delete(Movie $movie)
    {
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($movie);
        $manager->flush();
        $movieTitle = $movie->getTitle();
        $this->addFlash('info', "$movieTitle a été supprimé" );
        return $this->redirectToRoute('movie_list');
    }

    /**
     * @Route("/{id}/update", name="update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function update(Request $request, Movie $movie, Slugger $slugger)
    {
        
        $form = $this->createForm(MovieType::class, $movie);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slugify($movie->getTitle());
            $movie->setSlug($slug);
          $imageFile = $form->get('image')->getData();

          if ($imageFile) {
              $filename = uniqid() . '.' . $imageFile->guessExtension();
              $imageFile->move(
                  $this->getParameter('images_directory'),
                  $filename
              );

              $movie->setImageFilename($filename);
          }
            
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute('movie_view', ['id'=> $movie->getId()]);
        }

        return $this->render('movie/update.html.twig', ["form" => $form->createView(), 'movie'=>$movie]);
    }

    /**
     * @Route("/{id}/actor/add", name="actor_add", requirements ={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function addMovieActor(Movie $movie, Request $request){

        $movieActor = new MovieActor();
        $movieActor->setMovie($movie);

        $form = $this->createForm(MovieActorType::class, $movieActor);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($movieActor);
            $manager->flush();
            return $this->redirectToRoute('movie_view', ['id'=>$movie->getId()]);
        }

        return $this->render('movie/add_actor.html.twig', [
            "form" => $form->createView(),
            "movie" => $movie
        ]);

    }
}