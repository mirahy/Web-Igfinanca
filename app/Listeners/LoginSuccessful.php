<?php

namespace App\Listeners;

use IlluminateAuthEventsLogin; 
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Models\Activity;
use DB;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->subject = 'login';
        $event->description = 'Login successful';
        $email = $event->user->email;
        $base = DB::connection()->getDatabaseName();
        
        activity($event->subject)
            ->withProperties(['Email' => $email])
            ->by($event->user)
            ->log($event->description);
            

    }
}
