<?php

namespace App\Tests\Website;

use YourFrog\App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 *  Sprawdzenie zapisywania informacji o autoryzacji
 *
 * @package App\Tests\Website
 */
class KeepAuthenticationTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var KernelBrowser
     */
    private $httpClient;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = static::createClient();
        $this->em = self::bootKernel()->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    /**
     *  Sprawdzenie czy na stronie zostanie wyświetlona informacja o prawidłowym zalogowaniu się do konta
     */
    public function testSuccessLoginAndKeepAuthentication()
    {
        $username = 'admin';

        $this->signIn($username, 'NG3yX3_qJ~w{VfhF');
        $this->httpClient->request('GET', 'http://functional-test.application/user/authenticate-history');

        $crawler = $this->httpClient->getCrawler();
        $count = $crawler->filter('table[class*="authenticate-history"] > tbody > tr')->count();
        $date = $crawler->filter('table[class*="authenticate-history"] > tbody > tr > td:nth-child(2)')->html();
        $ip = $crawler->filter('table[class*="authenticate-history"] > tbody > tr > td:nth-child(3)')->html();
        $status = $crawler->filter('table[class*="authenticate-history"] > tbody > tr > td:nth-child(4)')->html();

        /** @var Entity\Security\User $user */
        $user = $this->em->getRepository(Entity\Security\User::class)->findOneBy(['username' => $username]);

        /** @var Entity\Security\AccountAuthenticationHistory $authentication */
        $authentication = $this->em->getRepository(Entity\Security\AccountAuthenticationHistory::class)->findOneBy(['user' => $user]);

        $this->assertNotNull($user, 'Nie istnieje konto "' . $username . '"');
        $this->assertNotNull($authentication, 'Problem z pobraniem informacji o zalogowaniu');

        $this->assertEquals(1, $count);
        $this->assertEquals($authentication->getDate()->format('Y-m-d H:i:s'), $date);
        $this->assertEquals('127.0.0.1', $ip);
        $this->assertEquals(Entity\Security\AccountAuthenticationHistory::TYPE_SUCCESS, $status);
    }

    /**
     *  Sprawdzenie czy przy próbie zalogowania na nie istniejące konto zostaną odłożone prawidłowo dane
     */
    public function testFailedLoginOnNotExistsUser()
    {
        $repository = $this->em->getRepository(Entity\Security\AccountAuthenticationHistory::class);

        $oldCount = $repository->findAll();
        $this->signIn('adminx', 'NG3yX3_qJ~w{VfhF');
        
        $newCount = $repository->findAll();
        $this->assertNotSameSize($oldCount, $newCount, 'Próba zalogowania na nie istniejące konto nie powiodła się jendak nie odłożono informacji');
    }

    /**
     *  Sprawdzenie czy na stronie zostanie wyświetlona informacja o próbie zalogowania zakończonej nie powodzeniem
     */
    public function testFailedLoginAndKeepAuthentication()
    {
        $username = 'admin';

        $this->signIn($username, 'wrongPassword');
        $this->signIn($username, 'NG3yX3_qJ~w{VfhF');
        $this->httpClient->request('GET', 'http://functional-test.application/user/authenticate-history');

        $crawler = $this->httpClient->getCrawler();
        $count = $crawler->filter('table[class*="authenticate-history"] > tbody > tr')->count();
        $date = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(1) > td:nth-child(2)')->html();
        $ip = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(1) > td:nth-child(3)')->html();
        $status = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(1) > td:nth-child(4)')->html();

        /** @var Entity\Security\User $user */
        $user = $this->em->getRepository(Entity\Security\User::class)->findOneBy(['username' => $username]);

        /** @var Entity\Security\AccountAuthenticationHistory $authentication */
        $authentication = $this->em->getRepository(Entity\Security\AccountAuthenticationHistory::class)->findBy(['user' => $user], ['id' => 'ASC'])[0];

        $this->assertNotNull($user, 'Nie istnieje konto "' . $username . '"');
        $this->assertNotNull($authentication, 'Problem z pobraniem informacji o zalogowaniu');

        $this->assertEquals(2, $count); // Ponieważ za drugim razem logujemy się poprawnie
        $this->assertEquals($authentication->getDate()->format('Y-m-d H:i:s'), $date);
        $this->assertEquals('127.0.0.1', $ip);
        $this->assertEquals(Entity\Security\AccountAuthenticationHistory::TYPE_FAILED, $status);
    }

    private function signIn(string $username, $password)
    {
        $this->httpClient->request('GET', 'http://functional-test.application/');

        $this->assertSelectorExists('input[name="_username"]', 'Brak pola "login" do zalogowania');
        $this->assertSelectorExists('input[name="_password"]', 'Brak pola "hasło" do zalogowania');
        $this->assertSelectorExists('input[name="_csrf_token"]', 'Brak pola "CSRF" do zalogowania');

        $this->httpClient->submitForm('Zaloguj się', [
            '_username' => $username,
            '_password' => $password,
            '_csrf_token' => $this->httpClient->getCrawler()->filter('input[name="_csrf_token"]')->attr('value')
        ]);
        $this->httpClient->followRedirect();
    }
}