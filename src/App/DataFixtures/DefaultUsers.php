<?php

namespace YourFrog\App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use YourFrog\App\Entity;

/**
 *  Domyślne dane użytkowników
 *
 * @package YourFrog\App\DataFixtures
 */
class DefaultUsers extends Fixture
{
    private $passwordEncoder;

    /**
     *  Konstruktor
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $users = [
            ['fullname' => 'Administrator Techniczny', 'username' => 'admin', 'password' => 'NG3yX3_qJ~w{VfhF', 'email' => 'pawel.stelmasiak.91@gmail.com', 'roles' => ['ROLE_ADMIN']],
            ['fullname' => 'Administrator Techniczny', 'username' => 'admincc', 'password' => 'NG3yX3_qJ~w{VfhF', 'email' => 'pawel.stelmasiak.91+1@gmail.com', 'roles' => ['ROLE_ADMIN']],
            ['fullname' => 'Zwyczajny użytkownik', 'username' => 'user', 'password' => 'NG3yX3_qJ~w{VfhF', 'email' => 'pawel.stelmasiak.91+2@gmail.com', 'roles' => ['ROLE_USER']],
        ];

        foreach($users as $item) {
            $user = new Entity\Security\User();
            $user->setFullName($item['fullname']);
            $user->setUsername($item['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $item['password']));
            $user->setEmail($item['email']);
            $user->setRoles($item['roles']);
            $user->setSeriesOfFailsLogin(0);

            $manager->persist($user);
        }

        $manager->flush();
    }
}