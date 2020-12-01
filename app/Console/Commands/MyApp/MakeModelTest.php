<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeModelTest extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_model_test
                            {name : Name}
                            {--output=1 : Use default folder output}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Model Test';

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
        $this->fileType = 'Model Test';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'model-test.stub';

        $defaultOutputPath = $this->basepath . 'my_app/output/';
        $this->outputPath = $defaultOutputPath;
    }

    public function handle()
    {
        $this->name = $this->argument('name');

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
        $this->outputName = $this->data['MODEL_CLASS'] . 'Test';
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

        $replaceData = [
            'MODEL_TEST_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['model test'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'MODEL_VARIABLE_PLURAL' => $this->data['MODEL_VARIABLE_PLURAL'],
            'ITEM_USER_COLUMN' => $this->getItemUserColumn(),
            'MODEL_RELATIONS_TESTS' => $this->getModelRelationTestCases(),
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

    public function getModelRelationTestCases()
    {
        $relations = '';

        $modelClass = $this->data['MODEL_CLASS'];
        $modelVariable = $this->data['MODEL_VARIABLE'];

        if ($this->settings && isset($this->settings['relations'])) {
            $settingsFields = $this->settings['relations'];
            $fields = array_keys($settingsFields);

            foreach ($fields as $field) {
                $relationType = $settingsFields[$field]['relation'] ?? null;
                $parameters = $settingsFields[$field]['parameters'] ?? null;
                $relationParams = '';
                if ($parameters && is_array($parameters)) {
                    $relationParams = implode('\', \'', $parameters);
                    $relationParams = '\''.$relationParams.'\'';
                }

                if ($relationType == 'belongsTo') {
                    $relations .= '    /** @test */'."\n";
                    $relations .= '    public function a_'.$modelVariable.'_belongs_to_a_'.$field.'()'."\n";
                    $relations .= '    {'."\n";
                    $relations .= '        $'.$field.' = create(\''.$parameters[0].'\');'."\n";
                    $relations .= '        $attributes = $this->itemAttributes;'."\n";
                    $relations .= '        $attributes[\''.$field.'_id\'] = $'.$field.'->id;'."\n";
                    $relations .= '        $'.$modelVariable.' = create('.$modelClass.'::class, $attributes);'."\n";
                    $relations .= '        $this->assertInstanceOf(\''.$parameters[0].'\', $'.$modelVariable.'->'.$field.');'."\n";
                    $relations .= '    }'."\n";
                    $relations .= "\n";
                }

                if ($relationType == 'hasMany') {

                }

            }
        }

        return $relations;

    }
}
