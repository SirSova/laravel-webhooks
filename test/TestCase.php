<?php


namespace SirSova\Webhooks\Test;


use Illuminate\Foundation\Testing\WithFaker;
use SirSova\Webhooks\Test\Traits\PackageSetUp;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use PackageSetUp,
        WithFaker;
    
    public function setUp(): void
    {
        parent::setUp(); 
        
        $this->setUpMigrate();
    }

}