<?php

namespace AntoninMasek\Hashids\Commands;

use Illuminate\Console\Command;

class HashidsCommand extends Command
{
    public $signature = 'laravel-model-hashids';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
