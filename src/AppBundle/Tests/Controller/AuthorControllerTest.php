<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\TestBaseWeb;

class AuthorControllerTest extends TestBaseWeb
{
    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/authorList');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Petrov1', $crawler->filter('body')->text());
    }
}
