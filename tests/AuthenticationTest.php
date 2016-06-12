<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Models\User;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    private function createUser($name)
    {
        $user = new User([
            'username' => $name,
        ]);
        $user->token = 'aaa';
        $user->save();

        return $user;
    }

    public function testLoggedInStatus()
    {
        $user = $this->createUser('otto');

        $this->actingAs($user)
            ->get('/api/status')
            ->seeJson([
                'logged_in' => 'otto'
            ]);
    }

    public function testTokenAuth()
    {
        $user = $this->createUser('otto');

        $this->get('/api/status', [
                'X-Authorization' => 'Bearer ' . $user->token,
            ])
            ->seeJson([
                'logged_in' => 'otto'
            ]);
    }

    public function testTokenNoAuth()
    {
        $this->get('/api/status', [
                'X-Authorization' => 'Bearer asdfasdfasdfasdfsa',
            ])
            ->seeJson([
                'logged_in' => false,
            ]);
    }
}
