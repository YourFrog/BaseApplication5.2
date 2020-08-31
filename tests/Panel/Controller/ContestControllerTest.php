<?php


namespace App\Tests\Panel\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\CssSelector\CssSelectorConverter;

class ContestControllerTest extends WebTestCase
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
     *  Sprawdzenie czy można zalogować się na administratora
     */
    public function testCorrectLogin()
    {
        $this->httpClient->request('GET', 'http://functional-test.application/');

        $this->assertSelectorExists('input[name="_username"]', 'Brak pola "login" do zalogowania');
        $this->assertSelectorExists('input[name="_password"]', 'Brak pola "hasło" do zalogowania');
        $this->assertSelectorExists('input[name="_csrf_token"]', 'Brak pola "CSRF" do zalogowania');

        $this->httpClient->submitForm('Zaloguj się', [
            '_username' => 'admin',
            '_password' => 'NG3yX3_qJ~w{VfhF',
            '_csrf_token' => $this->httpClient->getCrawler()->filter('input[name="_csrf_token"]')->attr('value')
        ]);
        $this->httpClient->followRedirect();

        $this->assertSelectorExists("a[href='/x-panel/']", 'Brak linku do panelu administracyjnego');
    }

    /**
     *  Sprawdzenie czy po podaniu błędnego hasła zostawie wyświetlony komunikat
     */
    public function testIncorrectLogin()
    {
        $this->httpClient->request('GET', 'http://functional-test.application/');

        $this->assertSelectorExists('input[name="_username"]', 'Brak pola "login" do zalogowania');
        $this->assertSelectorExists('input[name="_password"]', 'Brak pola "hasło" do zalogowania');
        $this->assertSelectorExists('input[name="_csrf_token"]', 'Brak pola "CSRF" do zalogowania');

        $this->httpClient->submitForm('Zaloguj się', [
            '_username' => 'admin',
            '_password' => 'wrongPassword',
            '_csrf_token' => $this->httpClient->getCrawler()->filter('input[name="_csrf_token"]')->attr('value')
        ]);
        $this->httpClient->followRedirect();

        $this->assertStringContainsStringIgnoringCase('Bad credentials.', $this->httpClient->getCrawler()->html(), 'Brak komunikatu o błędnych danych');
    }
}