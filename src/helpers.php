<?php

use Phu1237\LaravelSettings\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

if (!function_exists('settings')) {
    /**
     * Get or set settings manager
     *
     * @param  null                       $key     Key of setting
     * @param  null                       $default Default value
     * @return Collection|mixed|Setting
     */
    function settings($key = null)
    {
        $settings = app('settings');
        if (is_array($key)) {
            return $settings->set($key);
        } else {
            if ($key != null) {
                return $settings->get($key);
            }
        }

        return $settings;
    }
}

if (!function_exists('setting')) {
    /**
     * Get or set setting item
     *
     * @param  null          $key     Key of setting
     * @param  null          $default Default value
     * @return string|null
     */
    function setting($key, $default = null)
    {
        $settings = app('settings');
        if ($default != null) {
            return $settings->value($key, $default);
        }

        return $settings->value($key);
    }
}
