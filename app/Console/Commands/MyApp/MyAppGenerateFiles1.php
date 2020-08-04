<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

class MyAppGenerateFiles1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my_app:generate_files1
                            {name : Name}
                            {--output=1 : Use default folder output}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--force=0 : Force}
                            {--debug : Debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate some files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        $this->subject = $this->argument('Subject'); // Post
        $this->line('Subject is : ' . $this->subject);

        $this->stubPath = __DIR__ . '/Stubs/Controllers/';
        $this->line('Stub Path is : ' . $this->stubPath);

        $this->controllerName = $this->subject;
        $this->line('Controller Name is : ' . $this->subject);

        $this->base_path = base_path('/');

        if ($this->option('destination-path')) {
            $this->destinationPath = $this->option('destination-path');
            $this->path = $this->base_path . $this->destinationPath;
        } else {
            $this->path = $this->base_path;
        }
        $this->line('Controller Path is : ' . $this->path);

        $success = $this->makeFileFromStub($this->path);

        if ($success) {
            $this->info('Controller created successfully.');
        }
    }

    public function makeFileFromStub($path)
    {
        // $this->makeDirectory($path);

        $file = $this->stubPath . 'controller.stub';
        $newFile = $path . 'app/Http/Controllers/' . $this->subject . 'Controller.php';
        $this->line('Source file is : ' . $file);
        $this->line('Destination file is : ' . $newFile);

        dump($this->files->exists($file));
        dump($this->files->exists($newFile));

        if ((! $this->hasOption('force') ||
            ! $this->option('force')) &&
            $this->alreadyExists($newFile)) {
            $this->error('File already exists!');

            return false;
        }

        $isCopied = File::copy($file, $newFile);
        $this->line('Is copied: ' . $isCopied);
        if (!$isCopied) {
            $this->error("failed to copy $file...\n");
        } else {
            $this->templateVars($newFile);
            // $this->userDefinedVars($newFile);
        }

        return true;
    }

    protected function alreadyExists($path)
    {
        return $this->files->exists($path);
    }

    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    private function defaultTemplating()
    {
        return [
            'index' => ['crudName'],
            'form' => ['crudName'],
            'create' => ['crudName'],
            'edit' => ['crudName'],
            'show' => ['crudName'],
        ];
    }

    protected function templateVars($file)
    {
        $this->delimiter = ['$', '$'];
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $replaceData = [
            'CLASS' => $this->subject.'Controller',
            'CLASS_NAMESPACE' => 'App\Http\Controllers',
            'LOWER_NAME' => Str::slug($this->subject),
        ];

        foreach ($replaceData as $var => $value) {
            $replace = $start . $var . $end;
            dump($var);
            if (in_array($var, $this->vars)) {
                File::put($file, str_replace($replace, $replaceData[$var], File::get($file)));
            }
        }
    }

    protected $vars = [
        'CLASS',
        'CLASS_NAMESPACE',
        'LOWER_NAME',
    ];
}
