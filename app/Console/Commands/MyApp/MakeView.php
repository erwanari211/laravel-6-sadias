<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeView extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_view
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom View';

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
        $this->fileType = 'View';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory . 'crud/';
        $this->stubFile = $this->stubPath . 'crud.stub';

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

        $viewDirectory = $this->data['VIEW_DIRECTORY'];
        $this->outputPath .= '/' . $viewDirectory;
        $this->setOutputPathInRoot();

        $views = ['index', 'table', 'create', 'show', 'edit', 'form_fields'];
        foreach ($views as $view) {
            $this->storeOutputSetting($view);

            if($this->option('debug')){
                $this->line('Output Path is : ' . $this->outputPath);
            }

            $success = $this->makeFileFromStub($this->outputPath);
            if ($success) {
                $this->info($this->fileType . ' (' . $view . ') created successfully.');
            }

            $this->restoreOutputSetting();
        }
    }

    public function storeOutputSetting($view)
    {
        $this->oldStubFile = $this->stubFile;
        $this->oldOutputName = $this->outputName;
        $this->stubFile = str_replace('crud.stub', "{$view}.stub", $this->stubFile);
        $this->outputName = str_replace('crud', "{$view}.blade", $this->outputName);
    }

    public function restoreOutputSetting()
    {
        $this->stubFile = $this->oldStubFile;
        $this->outputName = $this->oldOutputName;
    }

    public function setOutputName()
    {
        $this->outputName = 'crud';
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $module = $this->option('module');
        $replaceData = [
            'MODULE' => $module ? strtolower($module) . '::' : '' ,
            'VIEW_DIRECTORY' => $this->data['VIEW_DIRECTORY'],
            'LANG_FILE' => $this->data['LANG_FILE'],
            'ROUTE_NAME' => $this->data['ROUTE_NAME'],
            'MODULE_ROUTE_NAME' => $module ? Str::kebab($module).'.' : '',
            'MODEL_VARIABLE' => $this->data['MODEL_VARIABLE'],
            'MODEL_VARIABLE_PLURAL' => $this->data['MODEL_VARIABLE_PLURAL'],
            'FORM_FIELDS' => $this->getFormFields(),
            'TABLE_HEADER' => $this->getTableHeader(),
            'TABLE_BODY' => $this->getTableBody(),
        ];

        return $replaceData;
    }

    public function getFormFields()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['fields'])){
            $module = $this->option('module');
            $moduleName = $module ? strtolower($module) . '::' : '';
            $langFile = $this->data['LANG_FILE'];
            foreach ($settings['fields'] as $field => $fieldSettings) {
                $inputType = 'bsText';

                if (isset($fieldSettings[0])) {
                    $fieldSetting = explode('|', $fieldSettings[0]);
                    $fieldType = array_shift($fieldSetting);

                    // string
                    if (in_array($fieldType, ['char', 'string'])) {
                        $inputType = 'bsText';
                    }

                    // text
                    if (in_array($fieldType, ['longText', 'mediumText', 'text'])) {
                        $inputType = 'bsTextarea';
                    }

                    // number
                    if (in_array($fieldType, [
                        'bigInteger', 'decimal', 'double', 'float',
                        'integer', 'mediumInteger', 'smallInteger',
                        'unsignedDecimal', 'unsignedInteger',
                        'unsignedMediumInteger', 'unsignedSmallInteger'])) {
                        $inputType = 'bsNumber';
                    }

                    // boolean
                    if (in_array($fieldType, ['boolean', 'tinyInteger', 'unsignedTinyInteger'])) {
                        $inputType = 'bsNumber';
                    }

                    // date
                    if (in_array($fieldType, ['date', 'dateTime', 'dateTimeTz',
                        'softDeletes', 'softDeletesTz'])) {
                        $inputType = 'bsDatetimepicker';
                    }

                    // time
                    if (in_array($fieldType, ['time', 'timeTz'])) {
                        $inputType = 'bsDatetimepicker';
                    }

                    // other
                    if (in_array($fieldType, [
                        'morphs', 'uuidMorphs',
                        'nullableMorphs', 'nullableUuidMorphs'])) {
                        $inputType = null;
                        continue;
                    }

                    if (in_array($fieldType, ['uuid'])) {
                        $inputType = 'bsText';
                    }
                }

                $label = '\'label\' => __(\''.$moduleName.$langFile.'.attributes.'.$field.'\')';
                $formInput = '{!! Form::'.$inputType.'(\''.$field.'\', null, ['.$label.']) !!}';
                $result .= $formInput;
                $result .= "\n";
            }
        }

        return $result;
    }

    public function getTableHeader()
    {
        $settings = $this->settings;
        $result = '';
        $tab = '  ';
        if($settings && isset($settings['fields'])){
            $result .= "\n";
            $module = $this->option('module');
            $moduleName = $module ? strtolower($module) . '::' : '';
            $langFile = $this->data['LANG_FILE'];
            foreach ($settings['fields'] as $field => $fieldSettings) {
                $fieldSetting = explode('|', $fieldSettings[0]);
                $fieldType = array_shift($fieldSetting);

                if ($fieldType == 'morphs') {
                    continue;
                }

                $result .= str_repeat($tab, 4);
                $result .= '<th>{{ __(\''.$moduleName.$langFile.'.attributes.'.$field.'\') }}</th>';
                $result .= "\n";
            }
        }

        return $result;
    }

    public function getTableBody()
    {
        $settings = $this->settings;
        $result = '';
        $tab = '  ';
        if($settings && isset($settings['fields'])){
            $result .= "\n";
            $modelVariable = $this->data['MODEL_VARIABLE'];
            $module = $this->option('module');
            $moduleName = $module ? strtolower($module) . '::' : '';
            $langFile = $this->data['LANG_FILE'];
            foreach ($settings['fields'] as $field => $fieldSettings) {
                $fieldSetting = explode('|', $fieldSettings[0]);
                $fieldType = array_shift($fieldSetting);

                if ($fieldType == 'morphs') {
                    continue;
                }

                $result .= str_repeat($tab, 6);
                $result .= '<td>{{ $'.$modelVariable.'->'.$field.' }}</td>';
                $result .= "\n";
            }
        }

        return $result;
    }
}
