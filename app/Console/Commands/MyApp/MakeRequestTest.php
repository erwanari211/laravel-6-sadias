<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeRequestTest extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_request_test
                            {name : Name}
                            {--output=1 : Use default folder output}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Request Test';

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
        $this->fileType = 'Request Test';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'request-test.stub';

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
        $this->outputName = $this->data['REQUEST_CLASS'] . 'Test';
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $module = $this->option('module');
        $storeRequestData = $this->getStoreRequestData();
        $replaceData = [
            'REQUEST_TEST_CLASS' => $this->outputName,
            'NAMESPACE' => $this->pathToNamespace($this->classNamespace),
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['request test'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'MODULE_ROUTE_NAME' => $module ? Str::kebab($module).'.' : '',
            'ROUTE_NAME' => $this->data['ROUTE_NAME'],
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'MODEL_VARIABLE_PLURAL' => $this->data['MODEL_VARIABLE_PLURAL'],
            'VIEW_PATH' => $this->data['VIEW_PATH'],
            'STORE_ITEM_DATA_PROVIDER' => $storeRequestData['dataProvider'],
            'BEFORE_STORE_REQUEST' => $storeRequestData['beforeRequest'],
            'UPDATE_ITEM_DATA_PROVIDER' => $storeRequestData['dataProvider'],
            'BEFORE_UPDATE_REQUEST' => $storeRequestData['beforeRequest'],
            'ITEM_USER_COLUMN' => $this->getItemUserColumn(),
        ];

        return $replaceData;
    }

    public function getStoreRequestData()
    {
        $settings = $this->settings;
        $result = '';
        $beforeRequest = '';

        $tab2 = "        ";
        $tab3 = "            ";
        $tab4 = "                ";

        if($settings && isset($settings['rules'])){
            $result .= "\n";
            foreach ($settings['rules'] as $field => $fieldRules) {
                $rules = explode('|', $fieldRules);
                foreach ($rules as $rule) {

                    $containColon = Str::contains($rule, ':');
                    if ($containColon) {
                        $ruleArray = explode(':', $rule);
                        $rule = array_shift($ruleArray);
                    }

                    if ($rule == 'unique') {
                        $result .= $tab3 . "'request_should_fail_when_no_{$field}_is_not_unique' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => 'old-{$field}']," . "\n";
                        $result .= $tab4 . "'passed' => false," . "\n";
                        $result .= $tab3 . "]," . "\n";
                        $result .= "\n";

                        $beforeRequest .= $tab2 . "\$attributes = \$this->itemAttributes;" . "\n";
                        $beforeRequest .= $tab2 . "\$attributes['{$field}'] = 'old-{$field}';" . "\n";
                        $beforeRequest .= $tab2 . "\$this->newItem(\$attributes);" . "\n";
                        $beforeRequest .= "\n";
                    }

                    if ($rule == 'required') {
                        $result .= $tab3 . "'request_should_fail_when_no_{$field}_is_provided' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '']," . "\n";
                        $result .= $tab4 . "'passed' => false," . "\n";
                        $result .= $tab3 . "]," . "\n";

                        $containNumeric = Str::contains($fieldRules, ['integer', 'numeric']);
                        $fieldData = $containNumeric ? '99' : $field;
                        $result .= $tab3 . "'request_should_success_when_{$field}_is_provided' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '{$fieldData}']," . "\n";
                        $result .= $tab4 . "'passed' => true," . "\n";
                        $result .= $tab3 . "]," . "\n";
                        $result .= "\n";
                    }

                    if ($rule == 'accepted') {
                        $result .= $tab3 . "'request_should_fail_when_{$field}_is_not_accepted' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '']," . "\n";
                        $result .= $tab4 . "'passed' => false," . "\n";
                        $result .= $tab3 . "]," . "\n";

                        $result .= $tab3 . "'request_should_success_when_{$field}_is_accepted' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '1']," . "\n";
                        $result .= $tab4 . "'passed' => true," . "\n";
                        $result .= $tab3 . "]," . "\n";
                        $result .= "\n";
                    }

                    if ($rule == 'integer') {
                        $result .= $tab3 . "'request_should_fail_when_{$field}_value_is_not_integer' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => 'not-integer']," . "\n";
                        $result .= $tab4 . "'passed' => false," . "\n";
                        $result .= $tab3 . "]," . "\n";

                        $result .= $tab3 . "'request_should_success_when_{$field}_value_is_not_integer' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '99']," . "\n";
                        $result .= $tab4 . "'passed' => true," . "\n";
                        $result .= $tab3 . "]," . "\n";
                        $result .= "\n";
                    }

                    if ($rule == 'nullable') {
                        $result .= $tab3 . "'request_should_success_when_{$field}_is_filled_or_not' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '']," . "\n";
                        $result .= $tab4 . "'passed' => true," . "\n";
                        $result .= $tab3 . "]," . "\n";
                        $result .= "\n";
                    }

                    if ($rule == 'numeric') {
                        $result .= $tab3 . "'request_should_fail_when_{$field}_value_is_not_numeric' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => 'not-numeric']," . "\n";
                        $result .= $tab4 . "'passed' => false," . "\n";
                        $result .= $tab3 . "]," . "\n";

                        $result .= $tab3 . "'request_should_success_when_{$field}_value_is_not_numeric' => [" . "\n";
                        $result .= $tab4 . "'field' => '{$field}'," . "\n";
                        $result .= $tab4 . "'data' => ['{$field}' => '99']," . "\n";
                        $result .= $tab4 . "'passed' => true," . "\n";
                        $result .= $tab3 . "]," . "\n";
                        $result .= "\n";
                    }
                }
            }
        }

        return $data = [
            'beforeRequest' => $beforeRequest,
            'dataProvider' => $result,
        ];
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
