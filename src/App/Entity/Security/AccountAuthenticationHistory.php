<?php

namespace YourFrog\App\Entity\Security;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 *  Historia logowań do konta
 *
 * @package YourFrog\App\Entity\Security
 *
 * @ORM\Entity(repositoryClass="YourFrog\App\Repository\Security\AccountAuthenticationHistoryRepository")
 * @ORM\Table(schema="security", name="account_authentication")
 */
class AccountAuthenticationHistory
{
    const TYPE_SUCCESS = 'success';
    const TYPE_FAILED = 'failed';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \YourFrog\App\Entity\Security\User
     *
     * @ORM\ManyToOne(targetEntity="YourFrog\App\Entity\Security\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     */
    private $user;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", nullable=false)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \YourFrog\App\Entity\Security\User
     */
    public function getUser(): \YourFrog\App\Entity\Security\User
    {
        return $this->user;
    }

    /**
     * @param \YourFrog\App\Entity\Security\User|null $user
     */
    public function setUser(?\YourFrog\App\Entity\Security\User $user): void
    {
        $this->user = $user;
    }
}