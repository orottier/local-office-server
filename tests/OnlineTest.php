<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class OnlineTest extends TestCase
{
    public function testOnline()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }
}
