<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogoutSuccessful
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $event->subject = 'logout';
        $event->description = 'Logout successful';
        $base = session()->get('base');
        $base = $base[0]['sigla'];
        $email = $event->user->email;
        
        activity($event->subject)
            ->withProperties(['Email' => $email, 'Base' => $base])
            ->by($event->user)
            ->log($event->description);
    }
}
