<?php

use App\Providers\AppServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;

return [
    AppServiceProvider::class,
    L5SwaggerServiceProvider::class,
    SanctumServiceProvider::class,
];
