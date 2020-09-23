<?php

namespace GaeDelPrete\DockerArtisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DockerInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docker:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init docker environment';

    /**
     * @var string
     */
    protected $dockerEnvDir;

    /**
     * @var string
     */
    protected $dockerEnvFile;

    /**
     * @var string
     */
    protected $phpVersion;

    /**
     * @var bool
     */
    protected $installXdebug;

    /**
     * @var string
     */
    protected $mysqlVersion;

    /**
     * @var string
     */
    protected $nginxPort;

    /**
     * @var string
     */
    protected $mysqlPort;

    public function __construct()
    {
        parent::__construct();
        $this->dockerEnvFile = base_path('.docker.env');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (file_exists($this->dockerEnvFile) && !$this->confirm('Do you wish to continue?')) {
            return 0;
        }

        $this->name = Str::slug(env('APP_NAME'));
        $this->phpVersion = $this->choice('Which PHP version do you want to use?', ['7.2', '7.3', '7.4'], 2);
        $this->installXdebug = $this->confirm('Do you want to add XDEBUG to PHP?');
        $this->mysqlVersion = $this->choice('Which MYSQL version do you want to use?', ['5.7', '8.0'], 0);
        $this->nginxPort = $this->askValid('NGINX port?', '80', 'nginx_port', ['required', 'numeric', 'min:2']);
        $this->mysqlPort = $this->askValid('MYSQL port?', '3306', 'mysql_port', ['required', 'numeric', 'min:2']);

        file_put_contents($this->dockerEnvFile, $this->buildDockerEnvContent());

        $this->line('All done! run php artisan docker:start and enjoy!');

        return 0;
    }

    protected function buildDockerEnvContent()
    {
        $contents = file_get_contents(__DIR__ . '/../../stubs/env.stub');

        return str_replace([
            '{{ name }}',
            '{{ phpVersion }}',
            '{{ installXdebug }}',
            '{{ mysqlVersion }}',
            '{{ nginxPort }}',
            '{{ mysqlPort }}',
        ], [
            $this->name,
            str_replace('.', '', $this->phpVersion),
            (bool) $this->installXdebug ? 'true' : 'false',
            str_replace('.', '', $this->mysqlVersion),
            $this->nginxPort,
            $this->mysqlPort,
        ], $contents);
    }

    protected function askValid($question, $default, $field, $rules)
    {
        $value = $this->ask($question, $default);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $default, $field, $rules);
        }

        return $value;
    }


    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make([
            $fieldName => $value,
        ], [
            $fieldName => $rules,
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
