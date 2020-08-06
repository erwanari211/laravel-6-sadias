<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class GenerateFiles extends Command
{

    protected $signature = 'my_app:generate_files
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--lang= : Language}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Generate some files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->option('module');
        $fileSettings = $this->option('file-settings');
        $output = $this->option('output');
        $force = $this->option('force');
        $debug = $this->option('debug');
        $lang = $this->option('lang');

        /*
        $this->call('my_app:make_file', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);
        */

        $this->call('my_app:make_model', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_migration', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_factory', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_seeder', [
            'name' => $name,
            '--module' => $module,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_policy', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_resource', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_request', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_service', [
            'name' => $name,
            '--module' => $module,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_controller', [
            'name' => $name,
            '--module' => $module,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

        $this->call('my_app:make_lang', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
            '--lang' => $lang,
        ]);

        $this->call('my_app:make_view', [
            'name' => $name,
            '--module' => $module,
            '--file-settings' => $fileSettings,
            '--output' => $output,
            '--force' => $force,
            '--debug' => $debug,
        ]);

    }


}
