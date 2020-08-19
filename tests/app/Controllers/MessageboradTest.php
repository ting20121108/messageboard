<?php namespace App;

use CodeIgniter\Test\FeatureTestCase;

class MessageboardTest extends FeatureTestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    public function tearDown() : void
    {
        parent::tearDown();
    }

    public function testIndex(){
        $result = $this->call('GET', '/Messageboard/index');
        $result->assertStatus(200);
    }
}
