<?php

// When using from package (vendor):
namespace QuintenMadari\QuiltLaravelBase\Seeders;

// When published, comment the line above and uncomment this:
// namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Log;

class FrontendTokenIssuerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userName = 'FrontendTokenIssuer';
        $user = User::where('name',$userName)->first();
        if (is_null($user)) {
            $user = new User();
            $user->name = $userName;
            $user->email = $userName;
            $user->password = Hash::make(substr(md5(microtime()),rand(0,26),5));
            $user->save();
            $message = 'FrontendTokenIssuerSeeder: User '.$userName.' created.';
            Log::info($message);
        } else {
            $message = 'FrontendTokenIssuerSeeder: User '.$userName.' already exists in the database.';
            Log::info($message);
        }

    }
}
