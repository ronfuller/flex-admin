<?php

namespace Psi\FlexAdmin\Commands;

use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
class FlexAdminCommand extends Command
{
    public $signature = 'flex-admin';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
