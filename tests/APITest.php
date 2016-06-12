<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class APITest extends TestCase
{
    public function testGetStatus()
    {
        $this->get('/api/status')
            ->seeJsonEquals([
                'status' => 'amazeballs',
                'logged_in' => false,
            ]);
    }

    public function testPostStatus()
    {
        $response = $this->call('POST', '/api/status');
        $this->assertEquals(405, $response->status());
    }
}
