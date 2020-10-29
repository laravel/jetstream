<?php

namespace Laravel\Jetstream\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetstream:install {stack : The development stack that should be installed}
                                              {--teams : Indicates if team support should be installed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Jetstream components and resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Publish...
        $this->callSilent('vendor:publish', ['--tag' => 'jetstream-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'jetstream-migrations', '--force' => true]);

        $this->callSilent('vendor:publish', ['--tag' => 'fortify-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'fortify-support', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'fortify-migrations', '--force' => true]);

        // "Home" Route...
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
            $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
        }

        // Fortify Provider...
        $this->installFortifyServiceProvider();

        // Configure Session...
        $this->configureSession();

        // AuthenticateSession Middleware...
        $this->replaceInFile(
            '// \Illuminate\Session\Middleware\AuthenticateSession::class',
            '\Laravel\Jetstream\Http\Middleware\AuthenticateSession::class',
            app_path('Http/Kernel.php')
        );

        // Install Stack...
        if ($this->argument('stack') === 'livewire') {
            $this->installLivewireStack();
        } elseif ($this->argument('stack') === 'inertia') {
            $this->installInertiaStack();
        }
    }

    /**
     * Install the Fortify service providers in the application configuration file.
     *
     * @return void
     */
    protected function installFortifyServiceProvider()
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\FortifyServiceProvider::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                "App\\Providers\RouteServiceProvider::class,",
                "App\\Providers\RouteServiceProvider::class,".PHP_EOL."        App\Providers\FortifyServiceProvider::class,",
                $appConfig
            ));
        }
    }

    /**
     * Configure the session driver for Jetstream.
     *
     * @return void
     */
    protected function configureSession()
    {
        if (! class_exists('CreateSessionsTable')) {
            try {
                $this->call('session:table');
            } catch (Exception $e) {
                //
            }
        }

        $this->replaceInFile("'SESSION_DRIVER', 'file'", "'SESSION_DRIVER', 'database'", config_path('session.php'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env.example'));
    }

    /**
     * Install the Livewire stack into the application.
     *
     * @return void
     */
    protected function installLivewireStack()
    {
        // Install Livewire...
        $this->requireComposerPackages('livewire/livewire:^2.0', 'laravel/sanctum:^2.6');

        // Sanctum...
        (new Process(['php', 'artisan', 'vendor:publish', '--provider=Laravel\Sanctum\SanctumServiceProvider', '--force'], base_path()))
                ->setTimeout(null)
                ->run(function ($type, $output) {
                    $this->output->write($output);
                });

        // Update Configuration...
        $this->replaceInFile('inertia', 'livewire', config_path('jetstream.php'));
        // $this->replaceInFile("'guard' => 'web'", "'guard' => 'sanctum'", config_path('auth.php'));

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/ui' => '^0.6.0',
                'postcss-import' => '^12.0.1',
                'tailwindcss' => '^1.8.0',
            ] + $packages;
        });

        // Tailwind Configuration...
        copy(__DIR__.'/../../stubs/livewire/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/webpack.config.js', base_path('webpack.config.js'));
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));

        // Directories...
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Fortify'));
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Jetstream'));
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->ensureDirectoryExists(public_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/api'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/auth'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/profile'));

        (new Filesystem)->deleteDirectory(resource_path('sass'));

        // Service Providers...
        copy(__DIR__.'/../../stubs/app/Providers/JetstreamServiceProvider.php', app_path('Providers/JetstreamServiceProvider.php'));

        $this->installJetstreamServiceProvider();

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        // Actions...
        copy(__DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path('Actions/Fortify/CreateNewUser.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path('Actions/Fortify/UpdateUserProfileInformation.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUser.php', app_path('Actions/Jetstream/DeleteUser.php'));

        // View Components...
        copy(__DIR__.'/../../stubs/livewire/app/View/Components/AppLayout.php', app_path('View/Components/AppLayout.php'));
        copy(__DIR__.'/../../stubs/app/View/Components/GuestLayout.php', app_path('View/Components/GuestLayout.php'));

        // Layouts...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire/resources/views/layouts', resource_path('views/layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));

        // Single Blade Views...
        copy(__DIR__.'/../../stubs/livewire/resources/views/dashboard.blade.php', resource_path('views/dashboard.blade.php'));
        copy(__DIR__.'/../../stubs/livewire/resources/views/navigation-dropdown.blade.php', resource_path('views/navigation-dropdown.blade.php'));

        // Other Views...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire/resources/views/api', resource_path('views/api'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire/resources/views/profile', resource_path('views/profile'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/auth', resource_path('views/auth'));

        // Routes...
        $this->replaceInFile('auth:api', 'auth:sanctum', base_path('routes/api.php'));

        if (! Str::contains(file_get_contents(base_path('routes/web.php')), "'/dashboard'")) {
            (new Filesystem)->append(base_path('routes/web.php'), $this->livewireRouteDefinition());
        }

        // Assets...
        copy(__DIR__.'/../../stubs/public/css/app.css', public_path('css/app.css'));
        copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));

        // Teams...
        if ($this->option('teams')) {
            $this->installLivewireTeamStack();
        }

        $this->line('');
        $this->info('Livewire scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    /**
     * Install the Livewire team stack into the application.
     *
     * @return void
     */
    protected function installLivewireTeamStack()
    {
        // Directories...
        (new Filesystem)->ensureDirectoryExists(resource_path('views/teams'));

        // Other Views...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire/resources/views/teams', resource_path('views/teams'));

        $this->ensureApplicationIsTeamCompatible();
    }

    /**
     * Get the route definition(s) that should be installed for Livewire.
     *
     * @return string
     */
    protected function livewireRouteDefinition()
    {
        return <<<'EOF'

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

EOF;
    }

    /**
     * Install the Inertia stack into the application.
     *
     * @return void
     */
    protected function installInertiaStack()
    {
        // Install Inertia...
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.2.4', 'laravel/sanctum:^2.6', 'tightenco/ziggy:^0.9.4');

        // Install NPM packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/inertia' => '^0.3.0',
                '@inertiajs/inertia-vue' => '^0.2.0',
                '@tailwindcss/ui' => '^0.6.0',
                'laravel-jetstream' => '^0.0.3',
                'portal-vue' => '^2.1.7',
                'postcss-import' => '^12.0.1',
                'tailwindcss' => '^1.8.0',
                'vue' => '^2.5.17',
                'vue-template-compiler' => '^2.6.10',
            ] + $packages;
        });

        // Sanctum...
        (new Process(['php', 'artisan', 'vendor:publish', '--provider=Laravel\Sanctum\SanctumServiceProvider', '--force'], base_path()))
                ->setTimeout(null)
                ->run(function ($type, $output) {
                    $this->output->write($output);
                });

        // Update Configuration...
        // $this->replaceInFile("'guard' => 'web'", "'guard' => 'sanctum'", config_path('auth.php'));

        // Tailwind Configuration...
        copy(__DIR__.'/../../stubs/inertia/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/webpack.config.js', base_path('webpack.config.js'));
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));

        // Directories...
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Fortify'));
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Jetstream'));
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->ensureDirectoryExists(public_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Jetstream'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Mixins'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/API'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Profile'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));

        (new Filesystem)->deleteDirectory(resource_path('sass'));

        // Service Providers...
        copy(__DIR__.'/../../stubs/app/Providers/JetstreamServiceProvider.php', app_path('Providers/JetstreamServiceProvider.php'));

        $this->installJetstreamServiceProvider();

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        // Actions...
        copy(__DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path('Actions/Fortify/CreateNewUser.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path('Actions/Fortify/UpdateUserProfileInformation.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUser.php', app_path('Actions/Jetstream/DeleteUser.php'));

        // View Components...
        copy(__DIR__.'/../../stubs/app/View/Components/GuestLayout.php', app_path('View/Components/GuestLayout.php'));

        // Blade Views...
        copy(__DIR__.'/../../stubs/inertia/resources/views/app.blade.php', resource_path('views/app.blade.php'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/auth', resource_path('views/auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));

        // Inertia Pages...
        copy(__DIR__.'/../../stubs/inertia/resources/js/Pages/Dashboard.vue', resource_path('js/Pages/Dashboard.vue'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia/resources/js/Jetstream', resource_path('js/Jetstream'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia/resources/js/Mixins', resource_path('js/Mixins'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia/resources/js/Pages/API', resource_path('js/Pages/API'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia/resources/js/Pages/Profile', resource_path('js/Pages/Profile'));

        // Routes...
        $this->replaceInFile('auth:api', 'auth:sanctum', base_path('routes/api.php'));

        if (! Str::contains(file_get_contents(base_path('routes/web.php')), "'/dashboard'")) {
            (new Filesystem)->append(base_path('routes/web.php'), $this->inertiaRouteDefinition());
        }

        // Assets...
        copy(__DIR__.'/../../stubs/public/css/app.css', public_path('css/app.css'));
        copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/inertia/resources/js/app.js', resource_path('js/app.js'));

        // Flush node_modules...
        // static::flushNodeModules();

        // Teams...
        if ($this->option('teams')) {
            $this->installInertiaTeamStack();
        }

        $this->line('');
        $this->info('Inertia scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    /**
     * Install the Inertia team stack into the application.
     *
     * @return void
     */
    protected function installInertiaTeamStack()
    {
        // Directories...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Profile'));

        // Pages...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia/resources/js/Pages/Teams', resource_path('js/Pages/Teams'));

        $this->ensureApplicationIsTeamCompatible();
    }

    /**
     * Get the route definition(s) that should be installed for Inertia.
     *
     * @return string
     */
    protected function inertiaRouteDefinition()
    {
        return <<<EOF

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

EOF;
    }

    /**
     * Ensure the installed user model is ready for team usage.
     *
     * @return void
     */
    protected function ensureApplicationIsTeamCompatible()
    {
        // Publish Team Migrations...
        $this->callSilent('vendor:publish', ['--tag' => 'jetstream-team-migrations', '--force' => true]);

        // Configuration...
        $this->replaceInFile('// Features::teams()', 'Features::teams()', config_path('jetstream.php'));

        // Directories...
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Jetstream'));
        (new Filesystem)->ensureDirectoryExists(app_path('Events'));
        (new Filesystem)->ensureDirectoryExists(app_path('Policies'));

        // Service Providers...
        copy(__DIR__.'/../../stubs/app/Providers/AuthServiceProvider.php', app_path('Providers/AuthServiceProvider.php'));
        copy(__DIR__.'/../../stubs/app/Providers/JetstreamWithTeamsServiceProvider.php', app_path('Providers/JetstreamServiceProvider.php'));

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/Membership.php', app_path('Models/Membership.php'));
        copy(__DIR__.'/../../stubs/app/Models/Team.php', app_path('Models/Team.php'));
        copy(__DIR__.'/../../stubs/app/Models/UserWithTeams.php', app_path('Models/User.php'));

        // Actions...
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/AddTeamMember.php', app_path('Actions/Jetstream/AddTeamMember.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/CreateTeam.php', app_path('Actions/Jetstream/CreateTeam.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/DeleteTeam.php', app_path('Actions/Jetstream/DeleteTeam.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUserWithTeams.php', app_path('Actions/Jetstream/DeleteUser.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/UpdateTeamName.php', app_path('Actions/Jetstream/UpdateTeamName.php'));

        copy(__DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUserWithTeams.php', app_path('Actions/Fortify/CreateNewUser.php'));

        // Policies...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/Policies', app_path('Policies'));
    }

    /**
     * Install the Jetstream service providers in the application configuration file.
     *
     * @return void
     */
    protected function installJetstreamServiceProvider()
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\JetstreamServiceProvider::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                "App\\Providers\FortifyServiceProvider::class,",
                "App\\Providers\FortifyServiceProvider::class,".PHP_EOL."        App\Providers\JetstreamServiceProvider::class,",
                $appConfig
            ));
        }
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerPackages($packages)
    {
        $command = array_merge(
            ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
