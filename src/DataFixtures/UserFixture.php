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
        $user->setEmailaddress("admin".$i."@example.com");
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setHiringDate(new \DateTime('2019-01-01'));
        $user->setSalary(1400.00);
        $manager->persist($user);
        $manager->flush();
        }



    }
}
