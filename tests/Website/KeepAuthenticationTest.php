<?php

namespace App\Tests\Website;

use YourFrog\App\Entity;
use YourFrog\App\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 *  Scenariusze:
 *   - Sprawdzenie poprawności odłożenia adresu IP: testSuccessLoginFromDifferenceIP
 *   - Sprawdzenie czy inni użytkownicy widzą inormacje o logowaniach innych użytkowników: testSuccessLoginAndNotVisibleOnOtherUserKeepAuthentication
 *   - Sprawdzenie czy konto widzi informacje o poprawnym zalogowaniu: testSuccessLoginAndKeepAuthentication
 *   - Sprawdzenie czy próba logowania na nie istniejące konto została odnotowana: testFailedLoginOnNotExistsUser
 *   - Sprawdzenie czy widzimy informacje o błędnych próbach logowania: testFailedLoginAndKeepAuthentication
 *   - Sprawdzenie czy w menu widnieje link do przekierowania na stronę: testVisibleMenuItem
 *   - Sprawdzenie czy administrator zobaczy link do panelu administracyjnego: testAdminVisibleAdministrationPanelLink
 *   - Sprawdzenie czy zwykły użytkownik zobaczy link do panelu administracyjnego: testUserVisibleAdministrationPanelLink
 *   - Sprawdzenie czy administrator wejdzie na panel administracyjny wpisując link: testAdminVisitAdministrationPanel
 *   - Sprawdzenie czy zwykły użytkownik wejdzie na panel administracyjny wpisując link: testUserVisitAdministrationPanel
 *   - Sprawdzenie czy użytkownik po zalogowaniu zobaczy link do wylogowania: testVisibleLogoutLink
 *   - Sprawdzenie czy użytkownik może wylgować się z aplikacji: testPossibleLogout
 *
 *
 *
 *
 *  Do zrobienia
 *   - Sprawdzenie czy wyświetla się paginacja na stronie
 *   - Sprawdzenie czy został wysłany e-mail do użytkownika o błędnym logowaniu (po drugiej próbie)
 *   - Sprawdzenie czy konto zablokowało się po paru próbach błędnego logowania (po 3 próbach)
 *   - Sprawdzenie czy można zalogować się na inne konto po zablokowaniu jednego
 *   - Sprawdzenie czy zostanie zablokowany adres IP po wielu próbach błędnego logowania (po 5)
 *   - Sprawdzenie czy konto zostało zablokowane na określony czas przy błędnych próbach logowania (30 minut)
 *   - Sprawdzenie czy adres IP został zablokowany na określony czas przy błędnych próbach logowania (2h)
 *
 */


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
     *  Sprawdzenie czy istnieje link do wylogowania
     */
    public function testVisibleLogoutLink()
    {
        $this->signIn('user', 'NG3yX3_qJ~w{VfhF');

        $crawler = $this->httpClient->getCrawler();
        $menuItems = $crawler->filter('li > a[href="/user/logout"]');

        $this->assertCount(1, $menuItems);
    }

    /**
     *  Sprawdzenie czy użytkownik może się wylogowac
     */
    public function testPossibleLogout()
    {
        $this->signIn('user', 'NG3yX3_qJ~w{VfhF');

        $this->httpClient->request('GET', 'http://functional-test.application/user/logout');
        $this->httpClient->followRedirect();

        // Sprawdzenie czy klient widzi input od zalogowania się
        $this->assertCount(1, $this->httpClient->getCrawler()->filter('form input[name="_username"]'));

        $links = [
            'http://functional-test.application/user/dashboard',
            'http://functional-test.application/x-panel'
        ];

        foreach($links as $link) {
            // Sprawdzenie czy klient może wejść w chroniony link
            $this->httpClient->request('GET', $link);
            $this->httpClient->followRedirect();

            $crawler = $this->httpClient->getCrawler();

            // Sprawdzenie czy klient widzi input od zalogowania się to oznacza że trafił na stronę główną
            $this->assertCount(1, $crawler->filter('form input[name="_username"]'));
        }
    }

    /**
     *  Sprawdzenie czy administrator widzi link do panelu administracyjnego
     */
    public function testAdminVisibleAdministrationPanelLink()
    {
        $this->signIn('admin', 'NG3yX3_qJ~w{VfhF');

        $crawler = $this->httpClient->getCrawler();
        $menuItems = $crawler->filter('li > a[href="/x-panel/"]');

        $this->assertCount(1, $menuItems);
    }

    /**
     *  Sprawdzenie czy użytkownik widzi link do panelu administracyjnego
     */
    public function testUserVisibleAdministrationPanelLink()
    {
        $this->signIn('user', 'NG3yX3_qJ~w{VfhF');

        $crawler = $this->httpClient->getCrawler();
        $menuItems = $crawler->filter('li > a[href="/x-panel/"]');

        $this->assertCount(0, $menuItems);
    }

    /**
     *  Sprawdzenie czy administrator wejdzie na stronę panelu administracyjnego
     */
    public function testAdminVisitAdministrationPanel()
    {
        $this->signIn('admin', 'NG3yX3_qJ~w{VfhF');
        $this->openAdministrationPanelPage();

        // User must take forbidden
        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     *  Sprawdzenie czy zwykły użytkownik wejdzie na stronę panelu administracyjnego
     */
    public function testUserVisitAdministrationPanel()
    {
        $this->signIn('user', 'NG3yX3_qJ~w{VfhF');
        $this->openAdministrationPanelPage();

        // User must take forbidden
        $this->assertEquals(403, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     *  Sprawdzeie czy w menu widoczny jest link do strony z historią logowania
     */
    public function testVisibleMenuItem()
    {
        // Zalogujmy się
        $this->signIn('admin', 'NG3yX3_qJ~w{VfhF');

        $crawler = $this->httpClient->getCrawler();
        $menuItems = $crawler->filter('li > a[href="/user/authenticate-history"]');

        $this->assertCount(1, $menuItems);
    }

    /**
     *  Sprawdzenie czy prawidłowo odłuży się adres IP
     */
    public function testSuccessLoginFromDifferenceIP()
    {
        $ip = '11.11.11.11';

        // Najpierw zmieńmy adres IP
        $this->httpClient->setServerParameter('REMOTE_ADDR', $ip);

        // Zalogujmy się błędnie !!
        $user = $this->signIn('admin', 'wrongPassword');

        // Zalogujmy się
        $user = $this->signIn('admin', 'NG3yX3_qJ~w{VfhF');

        $repository = $this->getRepositoryOfAccountAuthenticationHistory();

        $listOfSuccess = $repository->onlySuccessByUser($user);
        $listOfFails = $repository->onlyFailedByUser($user);
        $all = $repository->findByUser($user);

        $this->assertCount(1, $listOfSuccess);
        $this->assertCount(1, $listOfFails);
        $this->assertCount(2, $all);

        $this->assertEquals($ip, $listOfSuccess[0]->getIp());
        $this->assertEquals($ip, $listOfFails[0]->getIp());

        $this->checkAuthenticateHistoryPage($all);
    }

    /**
     *  Sprawdzenie czy innym użytkownikom nie wyświetlają się informacje o koncie
     */
    public function testSuccessLoginAndNotVisibleOnOtherUserKeepAuthentication()
    {
        $repository = $this->getRepositoryOfAccountAuthenticationHistory();

        // Najpierw zaloguj się na jednego z adminów
        $admin = $this->signIn('admin', 'NG3yX3_qJ~w{VfhF');
        $this->signOut();

        // Sprawdźmy czy na adminie prawidłowo odłożyła się informacja o logowaniu
        $this->assertCount(1, $repository->onlySuccessByUser($admin)); // Powinien posiadać prawidłowe zalogowanie
        $this->assertCount(0, $repository->onlyFailedByUser($admin)); // Nie powinien posiadać błędnych logowań

        // Teraz zaloguj się na drugie konto admina
        $user = $this->signIn('admincc', 'NG3yX3_qJ~w{VfhF');

        $listOfSuccess = $repository->onlySuccessByUser($user);
        $listOfFails = $repository->onlyFailedByUser($user);

        // Poprawnie zalogowałem się tylko raz na użytkownika więc powinna wyświetlić się jedna autoryzacja
        $this->assertCount(1, $listOfSuccess);

        // Ten użytkownik nie powinien posiadać żadnej błędnej próby logowania
        $this->assertCount(0, $listOfFails);

        // Ale na bazie powinny znajdować się dwie
        $this->assertCount(2, $repository->findAll());

        $this->checkAuthenticateHistoryPage($listOfSuccess);
    }

    /**
     *  Sprawdzenie czy na stronie zostanie wyświetlona informacja o prawidłowym zalogowaniu się do konta
     */
    public function testSuccessLoginAndKeepAuthentication()
    {
        $user = $this->signIn('admin', 'NG3yX3_qJ~w{VfhF');

        $authentications = $this->getRepositoryOfAccountAuthenticationHistory()->onlySuccessByUser($user);

        // Użytkownik powinien mieć odłożony tylko jeden wpis
        $this->assertCount(1, $authentications);

        // Odłożony wpis powinien być oznaczony jako success
        $this->assertEquals(Entity\Security\AccountAuthenticationHistory::TYPE_SUCCESS, $authentications[0]->getType());

        // Sprawdzenie czy wyświetliły się poprawnie informacje
        $this->checkAuthenticateHistoryPage($authentications);
    }

    /**
     *  Sprawdzenie czy przy próbie zalogowania na nie istniejące konto zostaną odłożone prawidłowo dane
     */
    public function testFailedLoginOnNotExistsUser()
    {
        $repository = $this->em->getRepository(Entity\Security\AccountAuthenticationHistory::class);

        $oldCount = $repository->findAll();
        $this->signIn('adminx', 'emptyPassword', false);

        $newCount = $repository->findAll();
        $this->assertNotSameSize($oldCount, $newCount, 'Próba zalogowania na nie istniejące konto nie powiodła się jendak nie odłożono informacji');
    }

    /**
     *  Sprawdzenie czy na stronie zostanie wyświetlona informacja o próbie zalogowania zakończonej nie powodzeniem
     */
    public function testFailedLoginAndKeepAuthentication()
    {
        $username = 'admin';
        $repository = $this->getRepositoryOfAccountAuthenticationHistory();

        $user = $this->signIn($username, 'wrongPassword');
        $user = $this->signIn($username, 'NG3yX3_qJ~w{VfhF');

        $listOfSuccess = $repository->onlySuccessByUser($user);
        $listOfFails = $repository->onlyFailedByUser($user);
        $alls = $repository->findAll();
        $authentications = $repository->findByUser($user);

        // Użytkownik powinien mieć jedną poprawnie zalogowaną akcje
        $this->assertCount(1, $listOfSuccess);

        // Użytkownik powinien mieć jedną akcje z błędem
        $this->assertCount(1, $listOfFails);

        // Użytkownik łącznie powinien mieć 2 akcje
        $this->assertCount(2, $authentications);

        // Wszystkich akcji na bazie powinno być 2
        $this->assertCount(2, $alls);

        $this->checkAuthenticateHistoryPage($authentications);
        $this->assertEquals(Entity\Security\AccountAuthenticationHistory::TYPE_FAILED, $authentications[1]->getType());
    }

    /**
     *  Zalogowanie użytkownika
     *
     * @param string $username
     * @param string $password
     * @param bool $verify
     *
     * @return Entity\Security\User
     */
    private function signIn(string $username, string $password, bool $verify = true): ?Entity\Security\User
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

        /** @var Entity\Security\User $user */
        $user = $this->em->getRepository(Entity\Security\User::class)->findOneBy(['username' => $username]);

        if( $verify ) {
            $this->assertNotNull($user, 'Nie udało się pobrać użytkonikwa "' . $username . '"');
        }

        return $user;
    }

    /**
     *  Wylogowanie użytkownika
     */
    private function signOut()
    {
        $this->httpClient->request('GET', 'http://functional-test.application/user/logout');
        $this->httpClient->followRedirect();
        $this->httpClient->getCookieJar()->clear();
    }

    /**
     *  Pobranie repozytorium obsługującego historie logowań
     *
     * @return Repository\Security\AccountAuthenticationHistoryRepository
     */
    private function getRepositoryOfAccountAuthenticationHistory(): Repository\Security\AccountAuthenticationHistoryRepository
    {
        /** @var Repository\Security\AccountAuthenticationHistoryRepository $repository */
        $repository = $this->em->getRepository(Entity\Security\AccountAuthenticationHistory::class);

        return $repository;
    }

    /**
     *  Sprawdzenie czy na stronie wyświetliły się autoryzacje podane w parametrze
     *
     * @param Entity\Security\AccountAuthenticationHistory[] $authentications
     */
    private function checkAuthenticateHistoryPage(array $authentications)
    {
        $this->openAuthenticateHistoryPage();

        $crawler = $this->httpClient->getCrawler();

        // Sprawdźmy czy ilość elementów zgadza się z tym co otrzymaliśmy w parametrze
        $rowCount = $crawler->filter('table[class*="authenticate-history"] > tbody > tr')->count();
        $this->assertCount($rowCount, $authentications, 'Nie prawidłowa ilość wierszy'); // Ponieważ za drugim razem logujemy się poprawnie

        foreach($authentications as $index => $authentication) {
            $nthChild = $index + 1;

            $columnCount = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(' . $nthChild . ') > td')->count();
            $date = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(' . $nthChild . ') > td:nth-child(2)')->html();
            $ip = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(' . $nthChild . ') > td:nth-child(3)')->html();
            $status = $crawler->filter('table[class*="authenticate-history"] > tbody > tr:nth-child(' . $nthChild . ') > td:nth-child(4)')->html();

            $this->assertEquals(4, $columnCount, 'Nie prawidłowa ilość kolumn');
            $this->assertEquals($authentication->getDate()->format('Y-m-d H:i:s'), $date, 'Wyświetlona data jest nie prawidłowa');
            $this->assertEquals($authentication->getIp(), $ip, 'Wyświetlony adres IP jest nie prawidłowy');
            $this->assertEquals($authentication->getType(), $status, 'Błędny status');
        }
    }

    /**
     *  Otwarcie strony z informacją o logowaniach
     */
    private function openAuthenticateHistoryPage()
    {
        $this->httpClient->request('GET', 'http://functional-test.application/user/authenticate-history');
    }

    /**
     *  Otwarcie strony panelu
     */
    private function openAdministrationPanelPage()
    {

        $this->httpClient->request('GET', 'http://functional-test.application/x-panel/');
    }
}