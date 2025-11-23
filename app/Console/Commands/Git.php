<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Git extends Command
{
    protected $signature = 'git:push {commit?}';
    protected $description = 'Run git add ., git commit and git push commands.';

    public function handle()
    {

        //Run rm --cached -r *
        $this->info('Running git commands...');

        exec('git rm --cached storage/logs/*');

        // Run git add .
        exec('git add .');

        // Get commit message from argument or use default
        $commit = $this->argument('commit') ?? 'Auto commit';

        // Run git commit
        exec('git commit -m "' . $commit . ' ' . date('Y-m-d H:i') . '"');

        // Run git push
        exec('git push');

        $this->info('Git commands executed successfully.');

        return 'Git commands executed successfully.';
    }
}
