<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Users;
use App\Form\DoctorType;
use App\Form\EditUserType;
use App\Repository\DoctorRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'utilisateur')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
    }

    #[Route('/utilisateurs', name: 'utilisateurs')]
    #[IsGranted("ROLE_ADMINER")]
    public function usersList(UsersRepository $users): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    #[Route('/utilisateurs/modifier/{id}', name: 'modifier_utilisateur')]
    #[IsGranted("ROLE_ADMINER")]
    public function editUser(Users $user, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');

            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/doctor', name: 'doctor_index', methods: ['GET'])]
    #[IsGranted("ROLE_ADMINER")]
    public function listdoctor(DoctorRepository $doctorRepository): Response
    {

        return $this->render('admin/doctor/index.html.twig', [
            'doctors' => $doctorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'doctor_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMINER")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $doctor = new Doctor();

        $form = $this->createForm(DoctorType::class, $doctor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('admin_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'doctor_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMINER")]
    public function edit(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/doctor/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'doctor_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMINER")]
    public function delete(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($doctor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_doctor_index', [], Response::HTTP_SEE_OTHER);
    }
}
