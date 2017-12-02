<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BuildPageFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'build:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build the files needed for a new page';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['className', InputArgument::REQUIRED, 'The class name of the page being created']
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['git', 'g', InputOption::VALUE_NONE, 'Add files to git'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Generate migration'],
            ['seeder', 's', InputOption::VALUE_NONE, 'Generate seeder']
        ];
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $className = studly_case(str_singular($this->argument('className')));

        $controllerPath = app_path('/Http/Controllers');
        $controllerFile = $className . 'Controller.php';

        $servicePath = app_path('/Services/Entities/' . $className);
        $serviceFile = $className . 'Service.php';

        $modelPath = app_path('/Entities/' . $className);
        $modelFile = $className . '.php';
        $repositoryFile = $className . 'Repository.php';

        $requestPath = app_path('/Http/Requests/' . $className);
        $createRequestFile = 'Create' . $className . 'Request.php';
        $updateRequestFile = 'Update' . $className . 'Request.php';

        $stylePath = resource_path('/assets/sass');
        $styleFile = str_plural(snake_case($className, '-')) . '.scss';

        $languageFile = str_plural(snake_case($className, '-')) . '.php';

        // check for existing controller; no need to create if files exist
        if (\File::exists($controllerFile)) {
            $this->error($className . ' already exists!');
        } else {
            // create controller
            $controller = $this->buildFile($className, 'controller');
            $this->saveFile($controllerPath, $controllerFile, $controller);

            // create service
            $service = $this->buildFile($className, 'service');
            $this->saveFile($servicePath, $serviceFile, $service);

            // create repository
            $repository = $this->buildFile($className, 'repository');
            $this->saveFile($modelPath, $repositoryFile, $repository);

            // create model
            $model = $this->buildFile($className, 'model');
            $this->saveFile($modelPath, $modelFile, $model);

            // create create request
            $requestCreate = $this->buildFile($className, 'request.create');
            $this->saveFile($requestPath, $createRequestFile, $requestCreate);

            // create update request
            $requestUpdate = $this->buildFile($className, 'request.update');
            $this->saveFile($requestPath, $updateRequestFile, $requestUpdate);

            // create style
            $this->saveFile($stylePath, $styleFile, '');

            // create languages
            foreach (\File::directories(resource_path('/lang')) as $languagePath) {
                $language = $this->buildFile(snake_case($className, '-'), 'lang');
                $this->saveFile($languagePath, $languageFile, $language);
            }

            // create seeder
            if ($this->option('seeder')) {
                $seederPath = database_path('/seeds');
                $seederFile = str_plural($className) . 'TableSeeder.php';

                $seeder = $this->buildFile($className, 'seeder');
                $this->saveFile($seederPath, $seederFile, $seeder);
            }

            // create migration
            if ($this->option('migration')) {
                $this->buildMigration($className);
            }

            // add files to git
            if ($this->option('git')) {
                $this->gitAdd($controllerPath . '/' . $controllerFile);
                $this->gitAdd($servicePath . '/' . $serviceFile);
                $this->gitAdd($modelPath . '/' . $repositoryFile);
                $this->gitAdd($modelPath . '/' . $modelFile);
                $this->gitAdd($requestPath . '/' . $createRequestFile);
                $this->gitAdd($requestPath . '/' . $updateRequestFile);
                $this->gitAdd($stylePath . '/' . $styleFile);

                // add seeder file
                if ($this->option('seeder')) {
                    $this->gitAdd($seederPath . '/' . $seederFile);
                }

                // add migration file
                if ($this->option('migration')) {
                    $this->gitAdd('$(git ls-files database/migrations --other --exclude-standard)');
                    $this->gitAdd('$(git ls-files resources/lang --other --exclude-standard)');
                }

                $this->info('Files added to git');
            }
        }
    }

    /**
     * Get file data from stub and build the file contents.
     *
     * @param string $name
     * @param string $type
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function buildFile($name, $type)
    {
        $file = \File::get($this->getStub($type));

        return str_replace(['DummyRootNamespace', 'Dummy', 'Dummies'], [$this->laravel->getNamespace(), $name, str_plural($name)], $file);
    }

    /**
     * Generate migration file.
     *
     * @param string $name
     */
    private function buildMigration($name)
    {
        $table = str_plural(snake_case($name));
        $migration = 'create_' . $table . '_table';

        \Artisan::call('make:migration', [
            'name' => $migration,
            '--create' => $table
        ]);

        $this->line('<info>Created Migration:</info> ' . $migration);
    }

    /**
     * Pull specified stub.
     *
     * @param string $type
     * @return string
     */
    private function getStub($type)
    {
        return __DIR__ . '/stubs/' . $type . '.stub';
    }

    /**
     * Add file to git.
     *
     * @param string $file
     */
    private function gitAdd($file)
    {
        exec('git add ' . $file);
    }

    /**
     * Save the generated file.
     *
     * @param string $path
     * @param string $filename
     * @param string $file
     */
    private function saveFile($path, $filename, $file)
    {
        if (!\File::isDirectory($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        \File::put($path . '/' . $filename, $file);

        $this->line('<info>Created File:</info> ' . $filename);
    }
}
