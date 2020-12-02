<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeController extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_controller
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Controller';

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
        $this->fileType = 'Controller';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'controller.stub';

        $defaultOutputPath = $this->basepath . 'my_app/output/';
        $this->outputPath = $defaultOutputPath;
    }

    public function handle()
    {
        $this->name = $this->argument('name');

        $this->setData();
        $this->setOutputName();
        $this->setOutputPath();

        if($this->option('debug')){
            $this->line('Output Path is : ' . $this->outputPath);
        }

        $success = $this->makeFileFromStub($this->outputPath);
        if ($success) {
            $this->addRoute();
            $this->info($this->fileType . ' created successfully.');
        }
    }

    public function setOutputName()
    {
        $this->outputName = $this->data['CONTROLLER_CLASS'];
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $module = $this->option('module');

        $useDatatables = $this->checkUseDatatables();

        $replaceData = [
            'CONTROLLER_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['controller'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'SERVICE_CLASS' => $this->data['SERVICE_CLASS'],
            'SERVICE_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['controller'], '/'),
                    rtrim($fileDirectories['service'], '/'),
                    $this->classNamespace
                )
            ),
            'REQUEST_CLASS' => $this->data['REQUEST_CLASS'],
            'REQUEST_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['controller'], '/'),
                    rtrim($fileDirectories['request'], '/'),
                    $this->classNamespace
                )
            ),
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'MODEL_VARIABLE_PLURAL' => $this->data['MODEL_VARIABLE_PLURAL'],
            'SUBJECT' => $this->data['SUBJECT'],
            'VIEW_PATH' => $this->data['VIEW_PATH'],
            'MODULE' => $module ? strtolower($module) . '::' : '' ,
            'MODULE_ROUTE_NAME' => $module ? Str::kebab($module).'.' : '',
            'ROUTE_NAME' => $this->data['ROUTE_NAME'],
            'INDEX_VIEW' => (!$useDatatables ? 'index' : 'datatable-index'),
        ];

        return $replaceData;
    }

    public function addRoute()
    {
        $fileDirectories = $this->getFileDirectoryData();

        $module = $this->option('module');
        if ($module) {
            $routeDirectory = str_replace(
                rtrim($fileDirectories['controller'], '/'),
                rtrim($fileDirectories['route'], '/'),
                $this->outputPath
            );
        } else {
            $routeDirectory = str_replace(
                'app/'.rtrim($fileDirectories['controller'], '/'),
                rtrim($fileDirectories['route'], '/'),
                $this->outputPath
            );
        }

        $routeFile = $routeDirectory . '/web.php';
        $isExists = $this->fileIsExists($routeFile);
        if ($isExists) {
            $routeName = $this->data['ROUTE_NAME'];
            $controllerClass = $this->data['CONTROLLER_CLASS'];
            $newResourceRoute = 'Route::resource(\''.$routeName.'\', \''.$controllerClass.'\')';

            $currentContent = File::get($routeFile);

            $routeAlreadyExists = false;
            if (strpos($currentContent, $newResourceRoute) !== false) {
                $routeAlreadyExists = true;
            }

            if (!$routeAlreadyExists) {
                $newContent = $currentContent;
                $newResourceRoute .= '->middleware(\'auth\')';
                $newContent .= "\n" . $newResourceRoute . ';';
                File::put($routeFile, $newContent);
            }

        }
    }
}
