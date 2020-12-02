<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeRequest extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_request
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Request';

    protected $basepath;
    protected $fileType;
    protected $stubPath;
    protected $stubFile;
    protected $outputPath;
    protected $fileDirectory;
    protected $classNamespace;
    protected $delimiter;
    protected $settings = [];
    protected $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->basepath = base_path('/');
        $this->fileType = 'Request';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'request.stub';

        $defaultOutputPath = $this->basepath . 'my_app/output/';
        $this->outputPath = $defaultOutputPath;
    }

    public function handle()
    {
        $this->name = $this->argument('name');

        $this->setData();
        $this->setOutputName();
        $this->setOutputPath();

        $this->readFileSettings();

        if($this->option('debug')){
            $this->line('Output Path is : ' . $this->outputPath);
        }

        $success = $this->makeFileFromStub($this->outputPath);
        if ($success) {
            $this->info($this->fileType . ' created successfully.');
        }
    }

    public function setOutputName()
    {
        $this->outputName = $this->data['REQUEST_CLASS'];
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $replaceData = [
            'REQUEST_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['request'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'ROUTE_VARIABLE' => $this->data['ROUTE_VARIABLE'],
            'RULES' => $this->getRules(),
            'ATTRIBUTES' => $this->getAttributes(),
        ];

        return $replaceData;
    }

    public function getRules()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['rules'])){
            $result .= "\n";
            foreach ($settings['rules'] as $field => $rule) {
                $result .= "            ";
                $result .= '\''.$field.'\' => \''.$rule.'\',';
                $result .= "\n";
            }
        }

        return $result;
    }

    public function getAttributes()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['lang'])){
            $result .= "\n";
            foreach ($settings['lang'] as $field => $lang) {
                $result .= "            ";
                $result .= '\''.$field.'\' => \''.$lang.'\',';
                $result .= "\n";
            }
        }

        return $result;
    }
}
