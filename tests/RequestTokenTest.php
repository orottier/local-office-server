<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class RequestTokenTest extends TestCase
{
    use DatabaseMigrations;

    private function expectSlackJob()
    {
        app('Illuminate\Contracts\Bus\Dispatcher');
        $this->expectsJobs(App\Jobs\SendSlackMessage::class);
    }

    public function testGetRequestToken()
    {
        $response = $this->call('GET', '/api/request-token');
        $this->assertEquals(405, $response->status());
    }

    public function testRequestTokenCreatesUser()
    {
        $this->expectSlackJob();
        $this->post('/api/request-token', ['username' => 'otto']);
        $this->seeInDatabase('users', ['username' => 'otto']);
    }

    public function testRequestTokenReturns()
    {
        $this->expectSlackJob();
        $this->post('/api/request-token', ['username' => 'otto'])
            ->seeJsonEquals([
                'username' => 'otto',
                'status' => 'error',
            ]);
    }

    public function testRequestTokenCapitalization()
    {
        $this->expectSlackJob();
        $this->post('/api/request-token', ['username' => 'oToT2'])
            ->seeJsonEquals([
                'username' => 'otot2',
                'status' => 'error',
            ]);

        $this->seeInDatabase('users', ['username' => 'otot2']);
    }

    public function testRequestTokenNoData()
    {
        $response = $this->call('POST', '/api/request-token');
        $this->assertEquals(400, $response->status());
    }

    public function testRequestTokenEmptyUsername()
    {
        $response = $this->call('POST', '/api/request-token', ['username' => '']);
        $this->assertEquals(400, $response->status());
    }
}
