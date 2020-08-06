<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeMigration extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_migration
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Migration';

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
        $this->fileType = 'Migration';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'migration.stub';

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
        $this->outputName = $this->data['MIGRATION_FILENAME'];
    }

    public function getReplaceData()
    {
        $replaceData = [
            'MIGRATION_CLASS' => $this->data['MIGRATION_CLASS'],
            'TABLE' => $this->data['TABLE_NAME'],
            'FIELDS' => $this->getFields(),
        ];

        return $replaceData;
    }

    public function getFields()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['fields'])){
            $result .= "\n";
            foreach ($settings['fields'] as $field => $fieldSettings) {
                $fieldType = 'string';
                $fieldTypeParams = '';
                $fieldModifier = '';

                if (isset($fieldSettings[0])) {
                    $fieldSetting = explode('|', $fieldSettings[0]);
                    $params = [];
                    $fieldType = array_shift($fieldSetting);
                    $params = $fieldSetting;

                    if ($params) {
                        $fieldTypeParams .= ', ';
                        $fieldTypeParams .= implode(', ', $params);
                    }
                }

                if (isset($fieldSettings[1])) {
                    $modifierSettings = explode('|', $fieldSettings[1]);
                    foreach ($modifierSettings as $modifierSetting) {
                        $modifier = explode(':', $modifierSetting);
                        $params = [];
                        $modifierType = array_shift($modifier);
                        $params = $modifier;
                        $modifierParam = '';

                        if (isset($params[0])) {
                            $modifierParam .= '"'.$params[0].'"';
                        }

                        $fieldModifier .= '->'.$modifierType.'('.$modifierParam.')';
                    }
                }

                $result .= "            ";
                $result .= '$table->'.$fieldType;
                $result .= '(\''.$field.'\''.$fieldTypeParams.')';
                $result .= $fieldModifier;
                $result .= ';';
                $result .= "\n";
            }
        }

        return $result;
    }
}
