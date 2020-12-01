<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakePolicy extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_policy
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Policy';

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
        $this->fileType = 'Policy';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'policy.stub';

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
            $this->registerPolicy();
            $this->info($this->fileType . ' created successfully.');
        }
    }

    public function setOutputName()
    {
        $this->outputName = $this->data['POLICY_CLASS'];
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $replaceData = [
            'POLICY_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['policy'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'COLUMN' => $this->getColumnName(),
        ];

        return $replaceData;
    }

    public function getColumnName()
    {
        $column = 'user_id';
        if ($this->settings && isset($this->settings['policy']['column'])) {
            $column = $this->settings['policy']['column'];
        }

        return $column;
    }

    public function registerPolicy()
    {
        if ($this->option('output') == 0) {
            return false;
        }

        $replaceData = $this->getReplaceData();

        $modelClass = $replaceData['MODEL_CLASS'];
        $modelNamespace = $replaceData['MODEL_CLASS_NAMESPACE'];
        $policyClass = $replaceData['POLICY_CLASS'];
        $policyNamespace = $replaceData['NAMESPACE'];

        $model = "'{$modelNamespace}\\{$modelClass}'";
        $policy = "'{$policyNamespace}\\{$policyClass}'";
        $policyItem = "{$model} => {$policy},";

        $authServiceProvideDirectory = $this->basepath.'app/Providers/';
        $authServiceProvideFile = $authServiceProvideDirectory . 'AuthServiceProvider.php';
        $isExists = $this->fileIsExists($authServiceProvideFile);
        if ($isExists) {
            $currentContent = File::get($authServiceProvideFile);

            $policyAlreadyExists = false;
            if (strpos($currentContent, $policyItem) !== false) {
                $policyAlreadyExists = true;
            }

            if ($policyAlreadyExists) {
                return;
            }

            $posStart = strpos($currentContent, 'protected $policies = [');
            $posEnd = strpos($currentContent, '];', $posStart);

            $policyItem = '    ' . $policyItem . "\n" . '    ';
            $newContent = substr_replace($currentContent, $policyItem, $posEnd, 0);
            File::put($authServiceProvideFile, $newContent);
        }
    }
}
