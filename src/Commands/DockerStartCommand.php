<?php

namespace GaeDelPrete\DockerArtisan\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;

class DockerStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docker:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start docker environment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws LogicException
     */
    public function handle()
    {
        $this->line('Starting ' . config('app.name') . '...');

        $process = new Process([
            'docker-compose',
            '-f',
            __DIR__ . '/../../docker/docker-compose.yml',
            '--env-file',
            base_path('.docker.env'),
            'up',
            '--build',
            '--force-recreate',
            '--remove-orphans',
            '-d',
        ]);

        $process->start(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        while ($process->isRunning()) {
            usleep(500 * 1000);
        }

        return 0;
    }
}
