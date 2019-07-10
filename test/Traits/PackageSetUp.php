<?php


namespace SirSova\Webhooks\Test\Traits;

use SirSova\Webhooks\ServiceProvider;

trait PackageSetUp
{
    /**
     * @param $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
    }
    
    protected function setUpMigrate(): void
    {
        $this->artisan('migrate', ['--database' => 'testing']);
    }
}
