<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ExamFormRepositoryInterface;
use App\Repositories\Eloquent\ExamFormRepository;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExamFormRepositoryInterface::class,ExamFormRepository::class);
        // $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive(); 

    }
}
