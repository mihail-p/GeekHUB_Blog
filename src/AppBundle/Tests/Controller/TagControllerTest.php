<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\TestBaseWeb;

class TagControllerTest extends TestBaseWeb
{
    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/tag/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('lemon', $crawler->filter('body')->text());
    }
}