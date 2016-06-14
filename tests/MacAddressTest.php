<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Models\User;
use App\Models\MacAddress;

class MacAddressTest extends TestCase
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

    private function expectWriteFileJob()
    {
        app('Illuminate\Contracts\Bus\Dispatcher');
        $this->expectsJobs(App\Jobs\WriteMacAddresses::class);
    }

    public function testListMine()
    {
        $user = $this->createUser('otto');
        $user->macAddresses()->save(new MacAddress(['mac_address' => 'te:st:in:g']));

        $response = $this->actingAs($user)
            ->get('/api/users/me/mac-addresses')
            ->seeJson([
                'mac_address' => 'te:st:in:g',
            ]);
    }

    public function testListOthers()
    {
        $target = $this->createUser('test');
        $mac = new MacAddress(['mac_address' => 'te:st:in:g']);
        $target->macAddresses()->save($mac);

        $this->assertNotEmpty($mac->id);

        $user = $this->createUser('otto');

        $this->actingAs($user);
        $this->get('/api/users/' . $target->id . '/mac-addresses')
            ->assertResponseStatus(403);
    }

    public function testCreateById()
    {
        $user = $this->createUser('otto');
        $this->assertCount(0, $user->macAddresses);

        $this->expectWriteFileJob();

        $this->actingAs($user);
        $this->post('/api/users/' . $user->id . '/mac-addresses', [
                'address' => 'te:st',
            ])->seeJson([
                'mac_address' => 'te:st',
            ]);

        $user->load('macAddresses');
        $this->assertCount(1, $user->macAddresses);

        $this->assertEquals('te:st', $user->macAddresses[0]->mac_address);
    }

    public function testCreateByMe()
    {
        $user = $this->createUser('otto');
        $this->assertCount(0, $user->macAddresses);

        $this->expectWriteFileJob();

        $this->actingAs($user);
        $this->post('/api/users/me/mac-addresses', [
                'address' => 'te:st',
            ])->seeJson([
                'mac_address' => 'te:st',
            ]);
    }

    public function testCreateAuth()
    {
        $user = $this->createUser('otto');
        $this->post('/api/users/' . $user->id . '/mac-addresses', [
            'mac_address' => 'te:st',
        ])->assertResponseStatus(401);
    }

    public function testDelete()
    {
        $user = $this->createUser('otto');
        $mac = new MacAddress(['mac_address' => 'te:st:in:g']);
        $user->macAddresses()->save($mac);

        $this->assertNotEmpty($mac->id);

        $this->expectWriteFileJob();

        $this->actingAs($user)
            ->delete('/api/mac-addresses/' . $mac->id)
            ->seeJson([
                'result' => 'success',
            ]);

        $user->load('macAddresses');
        $this->assertArrayNotHasKey($mac->id, $user->macAddresses->keyBy('id'));
    }

    public function testDeleteNotExist()
    {
        $user = $this->createUser('otto');
        $this->actingAs($user)
            ->delete('/api/mac-addresses/120')
            ->assertResponseStatus(404);
    }

    public function testDeleteAuth()
    {
        $this->delete('/api/mac-addresses/12')
            ->assertResponseStatus(401);
    }

    public function testDeleteNotMine()
    {
        $target = $this->createUser('test');
        $mac = new MacAddress(['mac_address' => 'te:st:in:g']);
        $target->macAddresses()->save($mac);

        $this->assertNotEmpty($mac->id);

        $user = $this->createUser('otto');

        $this->actingAs($user);
        $this->delete('/api/mac-addresses/' . $mac->id)
            ->assertResponseStatus(403);
    }
}
