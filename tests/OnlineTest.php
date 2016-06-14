<?php


class OnlineTest extends TestCase
{
    public function testOnline()
    {
        $this->get('/')
            ->assertResponseOk();
    }

    public function testSPA()
    {
        $response = $this->call('GET', '/');
        $this->assertContains('router-view', $response->getContent());
    }

    public function testNotFound()
    {
        $response = $this->call('GET', '/page-not-found');
        $this->assertResponseStatus(404);
        $this->assertContains('not found', $response->getContent());
    }

    public function testAPINotFound()
    {
        $this->call('GET', '/api/page-not-found');
        $this->assertResponseStatus(404);
        $this->seeJson(['message' => 'Not Found']);
    }

    public function testAPINotAuthorized()
    {
        $this->call('GET', '/api/users/me');
        $this->assertResponseStatus(401);
        $this->seeJson(['message' => 'Unauthorized']);
    }

    public function testAPIMethodNotAllowed()
    {
        $this->call('DELETE', '/api/users/me');
        $this->assertResponseStatus(405);
        $this->seeJson(['message' => 'Method Not Allowed']);
    }
}
