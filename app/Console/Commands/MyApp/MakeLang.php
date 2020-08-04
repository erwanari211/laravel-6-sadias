<?php

namespace App\Console\Commands\MyApp;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\MyApp\MyAppCommandTrait;

class MakeLang extends Command
{
    use MyAppCommandTrait;

    protected $signature = 'my_app:make_lang
                            {name : Name}
                            {--module= : Module name}
                            {--file-settings= : File Settings}
                            {--lang=id : Language}
                            {--output=1 : Use default folder output}
                            {--force=0 : Force}
                            {--debug : Debug}';

    protected $description = 'Create Custom Language';

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
        $this->fileType = 'Language';

        $fileDirectories = $this->getFileDirectoryData();
        $fileDirectory = $fileDirectories[strtolower($this->fileType)];
        $this->fileDirectory = $fileDirectory;

        $this->stubPath = __DIR__ . '/Stubs/' . $fileDirectory;
        $this->stubFile = $this->stubPath . 'lang.stub';

        $defaultOutputPath = $this->basepath . 'my_app/output/';
        $this->outputPath = $defaultOutputPath;
    }

    public function handle()
    {
        $this->name = $this->argument('name');

        $this->setData();
        $this->setOutputName();
        $this->setOutputPath();

        if($this->option('lang')){
            $lang = $this->option('lang');
            $this->outputPath .= '/' . $lang;
        }

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
        $this->outputName = $this->data['LANG_FILE'];
    }

    public function getReplaceData()
    {
        $fileDirectories = $this->getFileDirectoryData();
        $replaceData = [
            'ATTRIBUTES' => $this->getAttributes(),
        ];

        return $replaceData;
    }

    public function getAttributes()
    {
        $settings = $this->settings;
        $result = '';
        if($settings && isset($settings['lang'])){
            $result .= "\n";
            foreach ($settings['lang'] as $field => $lang) {
                $result .= "\t\t";
                $result .= '\''.$field.'\' => \''.$lang.'\',';
                $result .= "\n";
            }
        }

        return $result;
    }
}
