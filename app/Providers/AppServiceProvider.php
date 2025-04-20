<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB; // Import DB facade
use Doctrine\DBAL\Types\Type; // Import Doctrine Type
use Doctrine\DBAL\Platforms\AbstractPlatform; // Import AbstractPlatform

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Map the database 'year' type to Doctrine 'smallint' type
        // This is necessary because Doctrine DBAL doesn't natively support the YEAR type
        // when modifying tables.
        if (class_exists(Type::class)) {
            $platform = DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform();
            if (!$platform->hasDoctrineTypeMappingFor('year')) {
                $platform->registerDoctrineTypeMapping('year', 'smallint');
            }
        }
    }
}
