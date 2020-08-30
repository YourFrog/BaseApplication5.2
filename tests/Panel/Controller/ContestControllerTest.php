<?php


namespace App\Tests\Panel\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContestControllerTest extends WebTestCase
{
    public function testWrongPost()
    {
        $httpClient = static::createClient();
        $httpClient->request('GET', 'http://application/');

        $this->assertSelectorExists('input[name="_username"]', 'Brak pola "login" do zalogowania');
        $this->assertSelectorExists('input[name="_password"]', 'Brak pola "hasło" do zalogowania');


        $httpClient->request('POST', 'http://application/', [
            '_username' => 'admin',
            '_password' => 'NG3yX3_qJ~w{VfhF'
        ]);

        $this->assertSelectorExists('a[href="/x-panel"]', 'Brak linku do panelu administracyjnego');
    }
}