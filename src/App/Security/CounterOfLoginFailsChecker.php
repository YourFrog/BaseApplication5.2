<?php


namespace YourFrog\App\Security;


use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use YourFrog\App\Entity;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *  Klasa sprawdzająca czy konto posiada zbyt dużo prób logowania
 *
 * @package YourFrog\App\Security
 */
class CounterOfLoginFailsChecker implements UserCheckerInterface
{
    /**
     *  Maksymalna ilość błędnych prób logowań
     *
     * @var int
     */
    private $maximumLoginFails;

    /**
     *  Konstruktor
     *
     * @param int $maximumLoginFails
     */
    public function __construct(int $maximumLoginFails)
    {
        $this->maximumLoginFails = $maximumLoginFails;
    }

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user)
    {
        $instanceOf = $user instanceof Entity\Security\User;

        if( !$instanceOf ) {
            return;
        }

        /** @var Entity\Security\User $user */

        if( $user->getSeriesOfFailsLogin() >= $this->maximumLoginFails ) {
            throw new CustomUserMessageAccountStatusException('Konto czasowo zablokowane');
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {
        // Nothing
    }

}