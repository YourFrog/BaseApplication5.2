<?php

namespace YourFrog\App\Repository\Security;

use YourFrog\App\Entity;
use Doctrine\ORM\EntityRepository;

/**
 *  Repozytorium dotyczące historii logowań
 *
 * @package YourFrog\App\Repository\Security
 */
class AccountAuthenticationHistoryRepository extends EntityRepository
{
    /**
     *  Pobranie wszystkich wpisów pasujących do użytkownika
     *
     * @param
     *
     * @return Entity\Security\AccountAuthenticationHistory[]
     */
    public function findByUser(Entity\Security\User $user): array
    {
        $criteria = [
            'user' => $user
        ];

        return $this->findBy($criteria, ['id' => 'DESC']);
    }

    /**
     *  Pobranie wpisów pasujących do użytkownika które zostały odłożone jako poprawne
     *
     * @param Entity\Security\User $user
     *
     * @return Entity\Security\AccountAuthenticationHistory[]
     */
    public function onlySuccessByUser(Entity\Security\User $user): array
    {
        $criteria = [
            'user' => $user,
            'type' => Entity\Security\AccountAuthenticationHistory::TYPE_SUCCESS
        ];

        return $this->findBy($criteria, ['id' => 'DESC']);
    }

    /**
     *  Pobranie wpisów pasujących do użytkownika które zostały odłożone jako poprawne
     *
     * @param Entity\Security\User $user
     *
     * @return Entity\Security\AccountAuthenticationHistory[]
     */
    public function onlyFailedByUser(Entity\Security\User $user): array
    {
        $criteria = [
            'user' => $user,
            'type' => Entity\Security\AccountAuthenticationHistory::TYPE_FAILED
        ];

        return $this->findBy($criteria, ['id' => 'DESC']);
    }
}