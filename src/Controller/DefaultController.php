<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->homepageMovies();
        $posts = $this->getDoctrine()->getRepository(Post::class)->homepagePost();
       return $this->render('homepage.html.twig', [
        "movies" => $movies,"posts" => $posts]);
    }
}
