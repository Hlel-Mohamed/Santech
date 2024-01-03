<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private \Faker\Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;
    private ObjectManager $manager;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher=$passwordHasher;
    }
    public function load(ObjectManager $manager):void
    {
        $this->manager=$manager;
        $this->faker=Factory::create();
        $this->generateUsers(2);
        $manager->flush();
    }

    private function generateUsers(int $number):void
    {
        $Roles=[['ROLE_USER'],['ROLE_ADMIN']];
        for($i=0;$i<$number;$i++){
            $user= new Users();
            $user->setPatientNom($this->faker->sentence(4));
            $user->setEmail($this->faker->email);
            $user->setUsername($this->faker->userName);
            $user->setPassword($this->passwordHasher->hashPassword($user,'mdp'));
            $user->setRoles($Roles[$i]);

            $this->manager->persist($user);
        }
    }
}