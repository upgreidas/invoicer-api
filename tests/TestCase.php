<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate');
        $this->artisan('db:seed');
        $this->app[Kernel::class]->setArtisan(null);
    }
}
