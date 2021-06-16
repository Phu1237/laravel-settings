<?php

namespace Phu1237\LaravelSettings;

use Phu1237\LaravelSettings\Setting;
use Phu1237\LaravelSettings\Models\Setting as SettingModel;

class SessionManager
{
    public function __construct()
    {
    }

    private function session()
    {
        $session_driver = config('settings.session');
        if ($session_driver == 'cache') {
            return new CacheManager();
        }
    }

    /**
     * Check if session is enable or not
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return config('settings.session') != null;
    }

    /**
     * Setting object
     *
     * @param  string    $key
     * @param  string    $value
     * @param  array     $meta
     * @return Setting
     */
    private function setting(string $key = '', string $value = '', $meta = null)
    {
        return new Setting($key, $value, $meta);
    }

    /**
     * Get all session items
     * null if no items or driver don't supported
     *
     * @return mixed
     */
    public function all()
    {
        if ($this->isEnable() == false) {
            return null;
        }

        return $this->session()->all();
    }

    /**
     * Check has setting key in session storage or not
     *
     * @param  string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if ($this->isEnable() == false) {
            return false;
        }

        return $this->session()->has($key);
    }

    /**
     * Get session
     *
     * @param  string    $key
     * @return Setting
     */
    public function get(string $key)
    {
        if ($this->isEnable() == false) {
            return null;
        }
        $result = $this->session()->get($key);
        if ($result != null) {
            return $this->setting($result['key'], $result['value'], $result['meta']);
        }

        return null;
    }

    public function set(string $key, string $value, $meta = null)
    {
        if ($this->isEnable() == false) {
            return null;
        }
        $result = $this->session()->set($key, $value, $meta);

        return $this->setting($result['key'], $result['value'], $result['meta']);
    }

    /**
     * Get session item or set a new one
     */
    public function getOrSet(string $key)
    {
        if ($this->isEnable() == false) {
            return null;
        }
        $result = $this->get($key);
        if ($result != null) {
            return $result;
        }
        $model = SettingModel::find($key);
        if ($model != null) {
            return $this->set($key, $model->value, $model->meta);
        }

        return null;
    }

    /**
     * Forget session
     *
     * @param  string $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        if ($this->isEnable() == false) {
            return false;
        }

        return $this->session()->forget($key);
    }

    /**
     * Refresh session
     *
     * @param  string $key Setting key
     * @return bool
     */
    public function refresh(string $key): bool
    {
        if ($this->isEnable() == false) {
            return false;
        }
        $this->forget($key);
        $this->get($key);

        return true;
    }

    public function flush(): bool
    {
        if ($this->isEnable() == false) {
            return false;
        }

        return $this->session()->flush();
    }
}
