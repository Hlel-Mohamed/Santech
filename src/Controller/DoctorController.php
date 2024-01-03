<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/doctor')]
#[IsGranted("ROLE_DOCTOR")]
class DoctorController extends AbstractController
{
    #[Route('/', name: 'doctor_index', methods: ['GET'])]
    public function index(DoctorRepository $doctorRepository): Response
    {

        if (!$this->isGranted('ROLE_DOCTOR')) {
            throw new AccessDeniedException('Accès limité aux docteurs!!!!');
        }

        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'doctor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $doctor = new Doctor();

        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'doctor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'doctor_delete', methods: ['POST'])]
    public function delete(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $doctor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($doctor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('doctor_index', [], Response::HTTP_SEE_OTHER);
    }
}
