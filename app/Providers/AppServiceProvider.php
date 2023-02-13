<?php

namespace App\Providers;

use App\Models\User;
use App\Services\FakerImageService;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new FakerImageService($faker));
            return $faker;
        });

        LogViewer::auth(function ($request) {
            return $request->user() && $request->user()->email === User::ADMIN_EMAIL && $request->user()->hasVerifiedEmail();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
