<?php

namespace YourFrog\App\DataFixtures;

use DateTime;
use YourFrog\Panel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 *  Domyślne ustawienia konkursu
 */
class ContestSettings extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $now = new DateTime();

        $settings = new Panel\Entity\Contest\Settings();
        $settings->setRegisterStartTime($now);
        $settings->setRegisterFinishTime($now);

        $settings->setVoteStartTime($now);
        $settings->setVoteFinishTime($now);

        $settings->setVoteMode(Panel\Entity\Contest\Settings::MODE_UNREGISTER_USERS);
        $settings->setVoteConfirmationTime(60 * 60);

//        $manager->persist($settings);
        $manager->flush();
    }
}