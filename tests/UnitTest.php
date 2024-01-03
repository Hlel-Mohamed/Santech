<?php

namespace App\Tests;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\DateSearch;
use App\Entity\Schedule;
use App\Entity\Users;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testDoctor(): void
    {
        $doctor = new Doctor();

        // Test setters and getters for Nom
        $doctor->setNom('John');
        $this->assertSame('John', $doctor->getNom(), "The getter or setter for Nom is defective.");

        // Test setters and getters for Prenom
        $doctor->setPrenom('Doe');
        $this->assertSame('Doe', $doctor->getPrenom(), "The getter or setter for Prenom is defective.");

        // Verify the instance is of the correct type
        $this->assertInstanceOf(Doctor::class, $doctor, "The instance is not of the type Doctor.");
    }

    public function testDateSearch(): void
    {
        $dateSearch = new DateSearch();

        // Test setters and getters for dateSearch1
        $date = new \DateTime();
        $dateSearch->setDateSearch1($date);
        $this->assertSame($date, $dateSearch->getDateSearch1(), "The getter or setter for dateSearch1 is defective.");

        // Verify the instance is of the correct type
        $this->assertInstanceOf(DateSearch::class, $dateSearch, "The instance is not of the type DateSearch.");
    }

    public function testSchedule(): void
    {
        $schedule = new Schedule();

        // Test setters and getters for ScheduleDate
        $date = new \DateTime();
        $schedule->setScheduleDate($date);
        $this->assertSame($date, $schedule->getScheduleDate(), "The getter or setter for ScheduleDate is defective.");

        // Test setters and getters for ScheduleDay
        $schedule->setScheduleDay('Monday');
        $this->assertSame('Monday', $schedule->getScheduleDay(), "The getter or setter for ScheduleDay is defective.");

        // Verify the instance is of the correct type
        $this->assertInstanceOf(Schedule::class, $schedule, "The instance is not of the type Schedule.");
    }

    public function testUsers(): void
    {
        $user = new Users();

        // Test setters and getters for username
        $user->setUsername('johndoe');
        $this->assertSame('johndoe', $user->getUsername(), "The getter or setter for username is defective.");

        // Test setters and getters for email
        $user->setEmail('johndoe@example.com');
        $this->assertSame('johndoe@example.com', $user->getEmail(), "The getter or setter for email is defective.");

        // Verify the instance is of the correct type
        $this->assertInstanceOf(Users::class, $user, "The instance is not of the type Users.");
    }

    public function testAppointment(): void
    {
        $appointment = new Appointment();

        // Test setters and getters for Symptome
        $appointment->setSymptome('Headache');
        $this->assertSame('Headache', $appointment->getSymptome(), "The getter or setter for Symptome is defective.");

        // Test setters and getters for Comments
        $appointment->setComments('Patient has a severe headache.');
        $this->assertSame('Patient has a severe headache.', $appointment->getComments(), "The getter or setter for Comments is defective.");

        // Test setters and getters for Status
        $appointment->setStatus(true);
        $this->assertSame(true, $appointment->isStatus(), "The getter or setter for Status is defective.");

        // Test setters and getters for Users
        $user = new Users();
        $appointment->setUsers($user);
        $this->assertSame($user, $appointment->getUsers(), "The getter or setter for Users is defective.");

        // Test setters and getters for Schedule
        $schedule = new Schedule();
        $appointment->setSchedule($schedule);
        $this->assertSame($schedule, $appointment->getSchedule(), "The getter or setter for Schedule is defective.");

        // Verify the instance is of the correct type
        $this->assertInstanceOf(Appointment::class, $appointment, "The instance is not of the type Appointment.");
    }
}