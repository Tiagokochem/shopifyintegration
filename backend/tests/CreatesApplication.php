<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require Application::inferBasePath().'/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        
        // Garantir que APP_KEY está definida para testes
        if (empty($app['config']['app.key'])) {
            $app['config']['app.key'] = 'base64:' . base64_encode(random_bytes(32));
        }

        return $app;
    }
}
