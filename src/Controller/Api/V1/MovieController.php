<?php

namespace App\Controller\Api\V1;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/v1/movies", name="api_v1_movies_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository, ObjectNormalizer $objetNormalizer)
    {
        $movies = $movieRepository->findAll();

        // On initialise le le Serializer en lui précisant de travailler avec le normaliseur d'objets
        $serializer = new Serializer([$objetNormalizer]);

        $json = $serializer->normalize($movies, null, ['groups' => 'api_v1_movies']);

        return $this->json($json);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Movie $movie, ObjectNormalizer $objetNormalizer)
    {
        $serializer = new Serializer([$objetNormalizer]);

        $json = $serializer->normalize($movie, null, ['groups' => 'api_v1_movies']);

        return $this->json($json);
    }

    /**
     * @Route("", name="new", methods={"POST"})
     */
    public function new(Request $request, ObjectNormalizer $objetNormalizer)
    {
       
        $movie = new Movie();
      
        $form = $this->createForm(MovieType::class, $movie, ['csrf_protection' => false]);
        
        $json = json_decode($request->getContent(), true);

        $form->submit($json);
        
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $serializer = new Serializer([$objetNormalizer]);
            $movieJson = $serializer->normalize($movie, null, ['groups' => 'api_v1_movies']);

            // On précise le code de status de réponse 201 Created
            return $this->json($movieJson, 201);
        } else {
            return $this->json((string) $form->getErrors(true), 400);
        }
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     */
    public function update()
    {
        return $this->json([
            'message' => 'coucou c\'est le GET',
            'path' => 'src/Controller/Api/V1/MovieController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete()
    {
        return $this->json([
            'message' => 'coucou c\'est le GET',
            'path' => 'src/Controller/Api/V1/MovieController.php',
        ]);
    }
}
