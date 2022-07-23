<?php

namespace AntoninMasek\Hashids;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ModelHashidsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-hashids')
            ->hasConfigFile('model-hashids');
    }
}
