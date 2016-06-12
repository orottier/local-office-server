<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Models\User;

class UserTest extends TestCase
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

    public function testAuthRequired()
    {
        $this->get('/api/users/12')
            ->assertResponseStatus(401);
    }

    public function testGetMe()
    {
        $user = $this->createUser('otto');

        $response = $this->actingAs($user)
            ->get('/api/users/me')
            ->seeJson([
                'username' => 'otto',
            ]);
    }

    public function testDoNotLeakToken()
    {
        $user = $this->createUser('otto');

        $response = $this->actingAs($user)
            ->get('/api/users/me')
            ->dontSeeJson([
                'token' => 'aaa',
            ]);
    }

    public function testGetOtherUser()
    {
        $target = $this->createUser('test');
        $user = $this->createUser('otto');

        $response = $this->actingAs($user);
        $this->get('/api/users/' . $target->id)
            ->assertResponseStatus(403);
    }
}
