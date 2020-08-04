<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeModel extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_model
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Model';

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
        $this->fileType = 'Model';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'model.stub';

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
        $this->outputName = $this->data['MODEL_CLASS'];
    }

    public function getReplaceData()
    {
        $replaceData = [
            'MODEL_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'FILLABLE' => $this->getFillableFields(),
        ];

        return $replaceData;
    }

    public function getFillableFields()
    {
        $fillable = '[]';
        if ($this->settings) {
            $fields = array_keys($this->settings['fields']);
            // $fillable = json_encode($fields);

            $fillable = '';
            $fillable .= '['."\n";
            $no = 0;
            foreach ($fields as $field) {
                if ($no % 3 == 0) {
                    $fillable .= "\n\t\t";
                }
                $fillable .= "'{$field}', ";
                $no++;
            }
            $fillable .= "\n\n\t".']';
        }

        return $fillable;

    }
}
