<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeControllerTest extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_controller_test
                            {name : Name}
                            {--output=1 : Use default folder output}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Controller Test';

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
        $this->fileType = 'Controller Test';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'controller-test.stub';

        $defaultOutputPath = $this->basepath . 'my_app/output/';
        $this->outputPath = $defaultOutputPath;
    }

    public function handle()
    {
        $this->name = $this->argument('name');

        $useAuth = $this->checkUseAuth();
        if (!$useAuth) {
            $this->stubFile = str_replace('.stub', '-no-auth.stub', $this->stubFile);
        }

        $this->setData();
        $this->setOutputName();
        $this->setOutputPath();
        $this->setOutputPathInRoot();

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
        $this->outputName = $this->data['CONTROLLER_CLASS'] . 'Test';
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        if ($this->option('module')) {
            foreach ($fileDirectories as $file => $directory) {
                if (strpos($file, 'test')) {
                    $fileDirectories[$file] = str_replace('tests/', 'Tests/', $fileDirectories[$file]);
                }
            }
        }

        $module = $this->option('module');
        $useDatatables = $this->checkUseDatatables();
        $itemColumn = $this->checkItemColumn();

        $replaceData = [
            'CONTROLLER_TEST_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['controller test'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'MODULE_ROUTE_NAME' => $module ? Str::kebab($module).'.' : '',
            'ROUTE_NAME' => $this->data['ROUTE_NAME'],
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'MODEL_VARIABLE_PLURAL' => $this->data['MODEL_VARIABLE_PLURAL'],
            'VIEW_PATH' => $this->data['VIEW_PATH'],
            'ITEM_USER_COLUMN' => $this->getItemUserColumn(),
            'USE_DATATABLES' => $useDatatables,
            'ITEM_COLUMN' => $itemColumn,
        ];

        return $replaceData;
    }

    public function getItemUserColumn()
    {
        $column = 'user_id';
        if ($this->settings && isset($this->settings['policy']['column'])) {
            $column = $this->settings['policy']['column'];
        }

        return $column;
    }
}
