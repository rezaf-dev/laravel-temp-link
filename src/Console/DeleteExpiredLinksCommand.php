<?php

namespace RezafDev\LaravelTempLink\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RezafDev\LaravelTempLink\TempLink;

class DeleteExpiredLinksCommand extends Command
{
    protected $signature = 'templink:delete';

    protected $description = 'Remove expired links';

    public function handle(TempLink $tempLink)
    {
        $this->info('Remove expired links...');
        $tempLink->removeExpiredLinks();
        $this->info('Done.');
    }
}