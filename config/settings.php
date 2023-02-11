<?php

return [
    /**
     * Driver to save settings
     */
    'driver' => env('SETTINGS_DRIVER', 'database'),

    /*
     * Session driver
     *
     * null = disable
     */
    'session' => env('SETTINGS_SESSION', 'cache'),

    /**
     * Save settings drivers
     */
    'connections' => [
        'database' => [
            'provider' => 'settings',
        ],
    ],

    /**
     * Session drivers
     *
     * prefix: save as prefix + key. Example: settings.key
     * ttl: time to live. null is forever
     */
    'sessions' => [
        'cache' => [
            'prefix' => 'settings.',
            'ttl' => null, // seconds
        ],
    ],

    /**
     * Default for setting
     */
    'default' => [
        'meta' => '{"type": "text", "placeholder": null}',
    ],
];
