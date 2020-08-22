<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeFactory extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_factory
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Factory';

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
        $this->fileType = 'Factory';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'factory.stub';

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
        $this->outputName = $this->data['FACTORY_CLASS'];
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $replaceData = [
            'MODEL_CLASS' => $this->data['MODEL_CLASS'],
            'MODEL_CLASS_NAMESPACE' => $this->pathToNamespace(
                str_replace(
                    rtrim($fileDirectories['factory'], '/'),
                    rtrim($fileDirectories['model'], '/'),
                    $this->classNamespace
                )
            ),
            'FAKER_FIELDS' => $this->getFakerFields(),
        ];

        return $replaceData;
    }

    public function getFakerFields()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['fields'])){
            $result .= "\n";
            foreach ($settings['fields'] as $field => $fieldSettings) {
                $fakeData = '$faker->sentence';

                if (isset($fieldSettings[0])) {
                    $fieldSetting = explode('|', $fieldSettings[0]);
                    $fieldType = array_shift($fieldSetting);

                    // string
                    if (in_array($fieldType, ['char', 'string'])) {
                        $fakeData = '$faker->sentence';
                    }

                    // text
                    if (in_array($fieldType, ['longText', 'mediumText', 'text'])) {
                        $fakeData = '$faker->paragraph';
                    }

                    // number
                    if (in_array($fieldType, [
                        'bigInteger', 'decimal', 'double', 'float',
                        'integer', 'mediumInteger', 'smallInteger',
                        'unsignedDecimal', 'unsignedInteger',
                        'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedBigInteger'])) {
                        $fakeData = '$faker->numberBetween(1000, 9999)';
                    }

                    // boolean
                    if (in_array($fieldType, ['boolean', 'tinyInteger', 'unsignedTinyInteger'])) {
                        $fakeData = '$faker->boolean(80)';
                    }

                    // date
                    if (in_array($fieldType, ['date', 'dateTime', 'dateTimeTz',
                        'softDeletes', 'softDeletesTz'])) {
                        $fakeData = 'date(\'Y-m-d\')';
                    }

                    // time
                    if (in_array($fieldType, ['time', 'timeTz'])) {
                        $fakeData = 'date(\'H-i-s\')';
                    }

                    // other
                    if (in_array($fieldType, [
                        'morphs', 'uuidMorphs',
                        'nullableMorphs', 'nullableUuidMorphs'])) {
                        // $fakeData = 'null';
                        continue;
                    }

                    if (in_array($fieldType, ['uuid'])) {
                        $fakeData = '$faker->uuid';
                    }
                }

                $result .= "        ";
                $result .= '\''.$field.'\' => '.$fakeData.',';
                $result .= "\n";
            }
        }

        return $result;
    }
}
