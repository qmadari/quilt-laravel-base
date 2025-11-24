<?php

namespace QuintenMadari\QuiltLaravelBase\Commands;
use QuintenMadari\QuiltLaravelBase\Seeders\FrontendTokenIssuerSeeder;
use QuintenMadari\QuiltLaravelBase\Seeders\ApiDocsTokenIssuerSeeder;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EntrypointSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entrypoint:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Scribe Documentation and runs (if configured) seeders, and other commands as desired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         // Embedding specific entrypoint artisan call commands here for brevity in container creation

	$steps = config('quilt-base.entrypoint_steps', [
            'scribe-generate' => true,
            'seed-apidocstokenissuer' => true,
            'seed-frontendtokenissuer' => true,
    	]);

        Log::info('Running entrypoint:setup');
        try {
            $errorLogic = function($exitCode, $subject){
                if ($exitCode !== 0) {
                    $message = $subject.' failed with exit code: ' . $exitCode;
                    Log::error($message);
                    throw new \Exception($message);
                }
            };

	    if ($steps['scribe-generate']) {
                $subject = "Generating API documentation with scribe:generate";
                Log::info($subject);
                $exitCode = $this->call('scribe:generate');
                $errorLogic($exitCode, $subject);
	    }

	    if ($steps['seed-apidocstokenissuer']) {
	        $subject = "ApiDocsTokenIssuerSeeder db:seed";
                Log::info($subject);
                $exitCode = $this->call('db:seed', ['--class' => ApiDocsTokenIssuerSeeder::class,'--force' => true]); 
                $errorLogic($exitCode, $subject);
	    }
        
	    if ($steps['seed-frontendtokenissuer']) {
                $subject = "FrontendTokenIssuerSeeder db:seed";
                Log::info($subject);
                $exitCode = $this->call('db:seed', ['--class' => FrontendTokenIssuerSeeder::class,'--force' => true]); 
                $errorLogic($exitCode, $subject);
	    }

	    // Custom commands per project if defined in api-landing config
    	    $customCommands = config('quilt-base.custom_entrypoint_commands', []);
    	    foreach ($customCommands as $key => $value) {
        	if (is_numeric($command)) {
		    // $key = numeric, $value = command name
        	    // e.g. 'cache:clear'
		    $subject = 'Custom command: '.$value;
                    Log::info('Running '.$subject);
            	    $exitCode = $this->call($value);
                    $errorLogic($exitCode, $subject);
        	} else {
		    // $key = command name, $value = argument key-value pair array
            	    // e.g. key: 'db:seed', value: ['--class' => 'TestSeeder']
		    $subject = 'Custom command: '.$key;
                    Log::info('Running '.$subject);
            	    $exitCode = $this->call($key, $value);
                    $errorLogic($exitCode, $subject);
        	}

	    }

        } catch (\Exception $e) {
            Log::critical("Exception in 'entrypoint:setup' | ".$e->getMessage());
            throw $e;
        }

        Log::info('Entrypoint setup complete!');
    }
}
