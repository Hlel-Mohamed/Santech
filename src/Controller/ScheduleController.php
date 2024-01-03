<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Users;
use App\Form\ModifierUserType;
use App\Form\ScheduleType;
use App\Repository\AppointmentRepository;
use App\Repository\ScheduleRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ScheduleController extends AbstractController
{

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('doctor/calendar', name: 'calendar')]
    #[IsGranted("ROLE_DOCTOR")]
    public function index(ScheduleRepository $scheduleRepository, AppointmentRepository $appointmentRepository, UsersRepository $usersRepository): Response
    {
        $now = new DateTime();

        $user = $this->getUser()->getUsername();

        $iduser = $usersRepository->findIdByUsername($user);

        $appointment = $appointmentRepository->findAppointmentById($iduser->getId());

        return $this->render('schedule/index.html.twig', [
            'appointment' => $appointment

        ]);
    }


    #[Route('/status/{id}/{checked}', name: 'activer')]
    #[IsGranted("ROLE_DOCTOR")]
    public function activer(Request $request, AppointmentRepository $appointmentRepository,
                            ScheduleRepository $scheduleRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        /* numero id d'appointment */

        $id = $request->attributes->get('id');
        $status = $request->attributes->get('checked');;

        /* je recupere objet appointment  */
        $rdv = $appointmentRepository->find($id);

        $rdv->setStatus(!$rdv->isStatus());

        /* je recupere numero de schedule  */
        $numeroidSchedule = $rdv->getSchedule();

        $scheduleRepository->updateDisponibilite($i = 0, $status, $numeroidSchedule,
            $disponible = "estdisponible", $nondisponible = "estnondisponible");

        $entityManager->persist($rdv);
        $entityManager->flush();

        // return new Response("ok");
        return $this->json($status);

        // SI J AVAIS CHOISI PLUTOT FETCH QUE AJAX

        /* $switchValue = $request->getContent();
           dd(json_decode($switchValue));

        //ICI ON FAIT DES REQUETES POUR AVOIR UN RESULTAT A PASSER
        return $this->json($dataAPasserAuFetch);*/
    }


    #[Route('doctor/schedule/new', name: 'schedulecreate')]
    #[IsGranted("ROLE_DOCTOR")]
    public function create(Request $request, ScheduleRepository $scheduleRepository, EntityManagerInterface $entityManager): Response
    {

        $Schedule = new Schedule();

        //$jourdelasemaine = array( 0=> 'Lundi',  1=> 'Mardi',  2=> 'Mercredi',  3=> 'Jeudi',  4=> 'Vendredi',  5=> 'Samedi',  6=> 'Dimanche') ;

        $form = $this->createForm(ScheduleType::class, $Schedule,
        /*  ,[
              'jourdelasemaine' => $jourdelasemaine
          ]*/
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Schedule->setBookAvail($form->get('BookAvail')->getData());
            $Schedule->setEndTime($form->get('EndTime')->getData());
            $Schedule->setScheduleDate($form->get('ScheduleDate')->getData());
            $Schedule->setStartTime($form->get('StartTime')->getData());
            $Schedule->setScheduleDay($form->get('ScheduleDay')->getData());
            $entityManager->persist($Schedule);
            $entityManager->flush();

            $this->addFlash(
                'alert alert-primary',
                "Le rendez vous du {$Schedule->getScheduleDate()->format('d-m-Y')} a bien été enregistrée !"
            );

            return $this->redirectToRoute('schedulecreate');
        }
        return $this->render('schedule/new.html.twig', [
            'form' => $form->createView(),
            'schedules' => $scheduleRepository->findAll()
        ]);
    }

    #[Route('/delete-rdv/{scheduleid}', name: 'delete_rdv')]
    public function deleteRdv(int $scheduleid, AppointmentRepository $appointmentRepository, ScheduleRepository $scheduleRepository, EntityManagerInterface $entityManager): Response
    {


        $Appointment = $appointmentRepository->find($scheduleid);

        $entityManager->remove($Appointment);

        $entityManager->flush();

        return $this->redirectToRoute("calendar");
    }

    #[Route('doctor/listPatient', name: 'listPatient')]
    #[IsGranted("ROLE_DOCTOR")]
    public function listPatient(UsersRepository $usersRepository): Response
    {
        return $this->render('patient/list.html.twig', [
            'listPatients' => $usersRepository->findAll()

        ]);
    }

    #[Route('doctor/listPatientupdate/{id}', name: 'listPatientupdate')]
    #[IsGranted("ROLE_DOCTOR")]
    public function listPatientupdate(Users $user, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(ModifierUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                "alert alert-danger",
                "Le patient {$user->getPatientNom()} a bien été modifié !"
            );
            return $this->redirectToRoute('listPatient');
        }

        return $this->render('patient/edit.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('doctor/listPatientdelete/{listPatientid}', name: 'listPatientdelete')]
    #[IsGranted("ROLE_DOCTOR")]
    public function listPatientdelete(int $listPatientid, UsersRepository $usersRepository, EntityManagerInterface $entityManager): Response
    {

        $patients = $usersRepository->find($listPatientid);

        $entityManager->remove($patients);

        $entityManager->flush();

        $this->addFlash(
            'success',
            "Le patient {$patients->getPatientNom()} a bien été supprimée !"
        );

        return $this->redirectToRoute("listPatient");

        //return $this->render('patient/list.html.twig', []);
    }


}
