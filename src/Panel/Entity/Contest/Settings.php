<?php

namespace YourFrog\Panel\Entity\Contest;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 *  Encja opisująca ustawienia konkursu
 *
 * @package YourFrog\Panel\Entity\Contest
 *
 * @ORM\Entity(repositoryClass="YourFrog\Panel\Repository\Contest\SettingsRepository")
 * @ORM\Table(schema="contest", name="settings")
 */
class Settings
{
    // Głosować mogą jedynie zarejestrowani użytkownicy
    const MODE_REGISTER_USERS = 'register.users';

    // Głosować możne każdy użytkownik po podaniu adresu e-mail
    const MODE_UNREGISTER_USERS = 'unregister.users';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id_settings")
     */
    private $id;

    /**
     *  Data od której można zacząć składać zgłoszenia
     *
     * @var DateTime
     *
     * @ORM\Column(name="register_start_time", type="datetime", nullable=false)
     */
    private $registerStartTime;

    /**
     *  Data od której można oddawać głosy
     *
     * @var DateTime
     *
     * @ORM\Column(name="vote_start_time", type="datetime", nullable=false)
     */
    private $voteStartTime;

    /**
     *  Data końca możliwości rejestrowania zgłoszeń
     *
     * @var DateTime
     *
     * @ORM\Column(name="register_finish_time", type="datetime", nullable=false)
     */
    private $registerFinishTime;

    /**
     *  Data końca możliwości oddania głosu
     *
     * @var DateTime
     *
     * @ORM\Column(name="vote_finish_time", type="datetime", nullable=false)
     */
    private $voteFinishTime;

    /**
     *  Sposób w jaki można głosować na zgłoszenia
     *
     * @var string
     *
     * @ORM\Column(name="vote_mode", type="string", nullable=false)
     */
    private $voteMode;

    /**
     * @param DateTime $registerStartTime
     */
    public function setRegisterStartTime(DateTime $registerStartTime): void
    {
        $this->registerStartTime = $registerStartTime;
    }

    /**
     * @param DateTime $voteStartTime
     */
    public function setVoteStartTime(DateTime $voteStartTime): void
    {
        $this->voteStartTime = $voteStartTime;
    }

    /**
     * @param DateTime $registerFinishTime
     */
    public function setRegisterFinishTime(DateTime $registerFinishTime): void
    {
        $this->registerFinishTime = $registerFinishTime;
    }

    /**
     * @param DateTime $voteFinishTime
     */
    public function setVoteFinishTime(DateTime $voteFinishTime): void
    {
        $this->voteFinishTime = $voteFinishTime;
    }

    /**
     * @param string $voteMode
     */
    public function setVoteMode(string $voteMode): void
    {
        $this->voteMode = $voteMode;
    }

    /**
     * @return DateTime
     */
    public function getRegisterStartTime(): DateTime
    {
        return $this->registerStartTime;
    }

    /**
     * @return DateTime
     */
    public function getVoteStartTime(): DateTime
    {
        return $this->voteStartTime;
    }

    /**
     * @return DateTime
     */
    public function getRegisterFinishTime(): DateTime
    {
        return $this->registerFinishTime;
    }

    /**
     * @return DateTime
     */
    public function getVoteFinishTime(): DateTime
    {
        return $this->voteFinishTime;
    }

    /**
     * @return string
     */
    public function getVoteMode(): string
    {
        return $this->voteMode;
    }
}