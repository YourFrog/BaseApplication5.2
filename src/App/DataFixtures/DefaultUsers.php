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
        $user = new Entity\Security\User();
        $user->setFullName('Administrator Techniczny');
        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'NG3yX3_qJ~w{VfhF'));
        $user->setEmail('pawel.stelmasiak.91@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }
}