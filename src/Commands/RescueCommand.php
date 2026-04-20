<?php

namespace Botble\Snippets\Commands;

use Illuminate\Console\Command;
use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Support\Facades\DB;

class RescueCommand extends Command
{
    protected $signature = 'snippets:rescue';
    protected $description = 'Emergency rescue mode: disables all active snippets by forcing them to Draft status to restore the system.';

    public function handle(): int
    {
        $this->components->info('Initiating Snippets Rescue Mode...');
        
        try {
            // Evaluated directly using raw DB query to avoid any Eloquent hooks or Boot events 
            // that might block the rescue operation in case of a fatal error cascade.
            $updated = DB::table('snippets')
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->update(['status' => BaseStatusEnum::DRAFT]);
                
            $this->components->info("Rescue successful! {$updated} snippets have been forcefully disabled (DRAFT status).");
            $this->components->warn('Your system should now be accessible. Please review your snippets one by one through the dashboard before enabling them again.');
            
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->components->error("Failed to rescue snippets: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
