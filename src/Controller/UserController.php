<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "Un nouvel utilisateur a été ajouté" );
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/view", name="view", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            
            if ($password != null) {
                $encodedPassword = $passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);
            }
        
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/update.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_list');
    }
}
