<?php


namespace YourFrog\App\Service\Authentication;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use YourFrog\App\Entity;

/**
 *  Odkładanie informacji o zalogowaniu
 *
 * @package YourFrog\App\Service\Authentication
 */
class KeepAuthentication
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     *  Konstruktor
     *
     * @param EntityManagerInterface $em
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    /**
     *  Poprawne zalogowanie do systemu
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var Entity\Security\User $user */
        $user = $event->getAuthenticationToken()->getUser();

        $this->saveEvent($user, Entity\Security\AccountAuthenticationHistory::TYPE_SUCCESS);
    }

    /**
     *  Błędne logowanie do systemu
     *
     * @param AuthenticationFailureEvent $event
     */
    public function onSecurityFailure(AuthenticationFailureEvent $event)
    {
        $username = $event->getAuthenticationToken()->getUsername();

        /** @var Entity\Security\User $user */
        $user = $this->em->getRepository(Entity\Security\User::class)->findOneBy(['username' => $username]);

        $this->saveEvent($user, Entity\Security\AccountAuthenticationHistory::TYPE_FAILED);
    }

    /**
     *  Zapisanie zdarzenia w bazie danych
     *
     * @param Entity\Security\User|null $user
     * @param string $type
     */
    private function saveEvent(?Entity\Security\User $user, string $type)
    {
        $now = new DateTime();

        $entity = new Entity\Security\AccountAuthenticationHistory();
        $entity->setUser($user);
        $entity->setDate($now);
        $entity->setIp($this->requestStack->getCurrentRequest()->getClientIp());
        $entity->setType($type);

        $this->em->persist($entity);
        $this->em->flush();
    }
}