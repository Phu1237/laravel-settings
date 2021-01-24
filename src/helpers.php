<?php

use Illuminate\Database\Eloquent\Collection;
use Phu1237\LaravelSettings\Models\Setting;

if (!function_exists('settings')) {
    /**
     * Helper function (get or set setting(s))
     *
     * @param null $key
     * @param null $default
     * @return Collection|mixed|Setting
     */
    function settings($key = null, $default = null)
    {
        $settings = app('settings');
        if (is_array($key)) {
            return $settings->set($key);
        } else if ($key != null) {
            return $settings->get($key);
        }

        return $settings;
    }
}
