<?php

namespace GaeDelPrete\DockerArtisan\Tests;

use Orchestra\Testbench\TestCase;
use GaeDelPrete\DockerArtisan\DockerArtisanServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [DockerArtisanServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
