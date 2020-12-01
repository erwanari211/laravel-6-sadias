<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait MyAppCommandTrait
{
    public function setData()
    {
        $data = [];
        $name = $this->name;

        $studly = Str::studly($name);
        $singular = Str::singular($studly);
        $plural = Str::plural($studly);

        $data['MODEL_CLASS'] = $singular;
        $data['MODEL_VARIABLE'] = Str::camel($singular);
        $data['MODEL_VARIABLE_PLURAL'] = Str::camel($plural);
        $data['MIGRATION_CLASS'] = "Create{$plural}Table";
        $data['MIGRATION_FILENAME'] = date('Y_m_d_His').'_create_'.strtolower(Str::snake($plural)).'_table';
        $data['TABLE_NAME'] = strtolower(Str::snake($plural));
        $data['PRESENTER_CLASS'] = "{$singular}Presenter";
        $data['FACTORY_CLASS'] = "{$singular}Factory";
        $data['SEEDER_CLASS'] = "{$plural}TableSeeder";
        $data['POLICY_CLASS'] = "{$singular}Policy";

        $data['CONTROLLER_CLASS'] = "{$singular}Controller";
        $data['REQUEST_CLASS'] = "{$singular}Request";
        $data['SERVICE_CLASS'] = "{$singular}Service";
        $data['RESOURCE_CLASS'] = "{$singular}Resource";

        $data['VIEW_DIRECTORY'] = Str::kebab($plural);
        $data['VIEW_PATH'] = $this->getViewPath();
        $data['LANG_FILE'] = Str::snake($singular);

        $data['ROUTE_NAME'] = Str::kebab($plural);
        $data['ROUTE_VARIABLE'] = Str::snake($singular);

        $data['SUBJECT'] = preg_replace('/([a-z])([A-Z])/s','$1 $2', $singular);

        $data['file_directory'] = $this->getFileDirectoryData();

        $this->data = $data;
    }

    public function getFileDirectoryData()
    {
        $directories = [
            'model' => 'Models/',
            'presenter' => 'Presenters/',
            'migration' => 'Database/Migrations/',
            'factory' => 'Database/Factories/',
            'seeder' => 'Database/Seeders/',
            'policy' => 'Policies/',
            'controller' => 'Http/Controllers/',
            'request' => 'Http/Requests/',
            'service' => 'Services/',
            'resource' => 'Http/Resources/',
            'view' => 'resources/views/',
            'language' => 'resources/lang/',
            'route' => 'routes/',

            'controller test' => 'tests/Feature/Http/Controllers/',
            'request test' => 'tests/Feature/Http/Requests/',
            'model test' => 'tests/Unit/Models/',
            'policy test' => 'tests/Unit/Policies/',
            'service test' => 'tests/Unit/Services/',
        ];

        return $directories;
    }

    public function setOutputName()
    {
        $this->outputName = Str::studly($this->name);
    }

    public function setClassNamespace()
    {
        if ($this->option('module')) {
            $module = $this->option('module');
            $this->classNamespace = rtrim('Modules/' . $module . '/' . $this->fileDirectory, '/');
        } else {
            $this->classNamespace = rtrim('app/' . $this->fileDirectory, '/');
        }
    }

    public function setOutputPath()
    {
        $this->setClassNamespace();
        if ($this->option('module')) {
            $this->classNamespace = str_replace('/tests/', '/Tests/', $this->classNamespace);
        }

        if ($this->option('output') == 1) {
            $this->outputPath = $this->basepath . $this->classNamespace;
        } else {
            $this->outputPath = $this->basepath . 'my_app/output/' . $this->classNamespace;
        }
    }

    public function setOutputPathInRoot()
    {
        if (!$this->option('module')) {
            $this->outputPath = str_replace(
                $this->basepath.'app',
                $this->basepath,
                $this->outputPath
            );
        }
    }

    public function makeFileFromStub($path)
    {
        $file = $this->stubFile;
        $newFile = $path . '/' . $this->outputName . '.php';

        if ((!$this->hasOption('force') || !$this->option('force')) &&
            $this->fileIsExists($newFile)) {
            $this->error('File '. $this->outputName . ' already exists!');
            return false;
        }

        $this->makeDirectory($newFile);
        $isCopied = File::copy($file, $newFile);
        if (!$isCopied) {
            $this->error("failed to copy $file...\n");
        } else {
            $this->replaceVars($newFile);
        }

        return true;
    }

    protected function fileIsExists($path)
    {
        return File::exists($path);
    }

    protected function makeDirectory($path)
    {
        if (! File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    protected function replaceVars($file)
    {
        $this->delimiter = ['$', '$'];
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];

        $replaceData = $this->getReplaceData();

        foreach ($replaceData as $key => $value) {
            $search = $start . $key . $end;
            File::put($file, str_replace($search, $value, File::get($file)));
        }
    }

    public function getReplaceData()
    {
        return [];
    }

    public function pathToNamespace($path)
    {
        $path = str_replace('/', '\\', $path);
        if (!$this->option('module')) {
            $path = str_replace('app', 'App', $path);
        }
        return $path;
    }

    public function readFileSettings()
    {
        $fileSettings = $this->option('file-settings');
        if ($fileSettings) {
            if ($this->fileIsExists($fileSettings)) {
                $this->settings = json_decode(File::get($fileSettings), TRUE);
            }
        }
    }

    public function getViewPath()
    {
        $name = $this->argument('name');
        $viewPath = Str::kebab(Str::plural(Str::studly($name)));

        if ($this->option('module')) {
            $module = $this->option('module');
            $viewPath = strtolower($module).'::'.$viewPath;
        }
        return $viewPath;
    }
}
