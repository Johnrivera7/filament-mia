<?php

return [

    /*
     * Copy for the pages the theme draws outside a panel.
     *
     * Written to be read by whoever hit the wall, not by whoever built it. It
     * says what happened, whether anything was lost, and what to do next — in
     * that order — and it never blames the reader or asks them to try again
     * without saying why that might help.
     */

    'errors' => [

        '403' => [
            'title' => 'Access denied',
            'heading' => 'You do not have access to this page',
            'body' => 'Your account can reach the panel but not this particular page. If that looks wrong, ask whoever administers it to check your permissions.',
        ],

        '404' => [
            'title' => 'Page not found',
            'heading' => 'There is nothing at this address',
            'body' => 'The page may have moved, or the record it pointed to may have been deleted. The rest of the panel is unaffected.',
        ],

        '419' => [
            'title' => 'Session expired',
            'heading' => 'Your session has expired',
            'body' => 'You were away long enough that the panel signed you out to keep the account safe. Signing in again brings you back.',
        ],

        '500' => [
            'title' => 'Something went wrong',
            'heading' => 'Something went wrong on our side',
            'body' => 'The panel could not finish what it was doing, and the failure has been logged. Trying again often works; if it does not, tell whoever administers the panel.',
        ],

    ],

    'maintenance' => [
        'title' => 'Down for maintenance',
        'heading' => 'Back shortly',
        'body' => 'The panel is closed for a scheduled update. Nothing has been lost — try again in a few minutes.',
    ],

    'actions' => [
        'back' => 'Back to the panel',
        'back_to' => 'Back to :name',
        'sign_in' => 'Sign in again',
    ],

];
