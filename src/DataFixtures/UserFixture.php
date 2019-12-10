<?php

namespace App\DataFixtures;

use App\Entity\Person;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 10; $i++) {

            $user = new Person();
            $user->setUserName("lid" . $i);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'qwerty'
            ));
            $user->setFirstName("lid".$i);
            $user->setLastName("achternaam".$i);
            $user->setDateOfBirth(new \DateTime('1996-01-01'));
            $user->setGender("m");
            $user->setEmailAdress("lid".$i."@example.com");
            $user->setRoles(array('ROLE_LID'));
            $user->setStreet("straat".$i);
            $user->setPostalCode("123".$i."AB");
            $user->setPlace("Den Haag");
            $manager->persist($user);
            $manager->flush();
        }


        for ($i = 0; $i < 3; $i++) {

        $user = new Person();
        $user->setUserName("admin" . $i);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'qwerty'
        ));
        $user->setFirstName("admin".$i);
        $user->setLastName("achternaam".$i);
        $user->setDateOfBirth(new \DateTime('1996-01-01'));
        $user->setGender("m");
        $user->setEmailAdress("admin".$i."@example.com");
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setHiringDate(new \DateTime('2019-01-01'));
        $user->setSalary(1400.00);
        $manager->persist($user);
        $manager->flush();
        }

        for ($i = 0; $i < 3; $i++) {

            $user = new Person();
            $user->setUserName("instructor" . $i);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'qwerty'
            ));
            $user->setFirstName("instructor".$i);
            $user->setLastName("achternaam".$i);
            $user->setDateOfBirth(new \DateTime('1996-01-02'));
            $user->setGender("m");
            $user->setEmailAdress("instructor".$i."@example.com");
            $user->setRoles(array('ROLE_INSTR'));
            $user->setHiringDate(new \DateTime('2019-02-01'));
            $user->setSalary(1400.00);
            $manager->persist($user);
            $manager->flush();
        }





    }
}
