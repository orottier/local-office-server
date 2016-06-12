<?php


class OnlineTest extends TestCase
{
    public function testOnline()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }

    public function testSPA()
    {
        $response = $this->call('GET', '/');
        $this->assertContains('router-view', $response->getContent());
    }
}
