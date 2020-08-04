<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeResource extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_resource
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Resource';

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
        $this->fileType = 'Resource';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'resource.stub';

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
        $this->outputName = $this->data['RESOURCE_CLASS'];
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $replaceData = [
            'RESOURCE_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'RESOURCE_FIELDS' => $this->getResourceFields(),
        ];

        return $replaceData;
    }

    public function getResourceFields()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['fields'])){
            $result .= "\n";
            foreach ($settings['fields'] as $field => $fieldSettings) {
                $result .= "\t\t\t";
                $result .= '\''.$field.'\' => $this->'.$field.',';
                $result .= "\n";
            }
            $result .= "\t\t\t".'\'created_at\' => (string) $this->created_at,'."\n";
            $result .= "\t\t\t".'\'updated_at\' => (string) $this->updated_at,'."\n";
        }

        return $result;
    }
}
