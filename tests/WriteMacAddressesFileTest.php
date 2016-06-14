<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Jobs\WriteMacAddresses;
use App\Models\User;
use App\Models\MacAddress;

class WriteMacAddressesFileTest extends TestCase
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

    public function testEmpty()
    {
        $file = storage_path('test.list');
        $job = new WriteMacAddresses($file);
        $job->handle();

        $contents = file_get_contents($file);

        $this->assertEmpty($contents);
    }

    public function testWrite()
    {
        $user = $this->createUser('edzo');
        $user->macAddresses()->save(new MacAddress(['mac_address' => 'te:st:in:g']));

        $file = storage_path('test.list');
        $job = new WriteMacAddresses($file);
        $job->handle();

        $contents = file_get_contents($file);

        $this->assertNotEmpty($contents);
        $this->assertEquals("te:st:in:g edzo\n", $contents);
    }

    public function testWriteMultiple()
    {
        $user = $this->createUser('otto');
        $user->macAddresses()->save(new MacAddress(['mac_address' => 'ot:to:to:ot']));
        $user->macAddresses()->save(new MacAddress(['mac_address' => 'bl:ah']));

        $user = $this->createUser('edzo');
        $user->macAddresses()->save(new MacAddress(['mac_address' => 'te:st:in:g']));

        $file = storage_path('test.list');
        $job = new WriteMacAddresses($file);
        $job->handle();

        $contents = file_get_contents($file);

        $this->assertNotEmpty($contents);
        $this->assertContains("ot:to:to:ot otto\n", $contents);
        $this->assertContains("bl:ah otto\n", $contents);
        $this->assertContains("te:st:in:g edzo\n", $contents);
    }
}
