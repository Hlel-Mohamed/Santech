<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{

    #[Route('/', name: 'users_index', methods: ['GET'])]
    public function index(UsersRepository $usersRepository): Response
    {

        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'users_new', methods: ['GET', 'POST'])]
    public function news(Request $request, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMINER');

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword('mdp');
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('users_index');
        }
        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'users_delete', methods: ['DELETE'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('users_index');
    }
}
