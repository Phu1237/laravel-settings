<?php

namespace Phu1237\LaravelSettings;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Phu1237\LaravelSettings\Models\Setting;
use Psr\SimpleCache\InvalidArgumentException;

class CacheManager
{
    private $isEnabled;
    private $cache;

    public function __construct()
    {
        $this->isEnabled = config('settings.cache_enabled');
        if ($this->isEnabled()) {
            $prefix = config('settings.cache_prefix');
            if ($this->isSupportTags()) {
                $this->cache = Cache::tags($prefix);
            }
            $this->cache = cache(); // Laravel cache helper
        }
    }

    /**
     * Check cache enable or disable
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * Check cache driver support tags or not
     *
     * @return bool
     */
    private function isSupportTags(): bool
    {
        $driver = config('cache.default');
        if (in_array($driver, ['file', 'dynamodb', 'database'])) {
            return false;
        }

        return true;
    }

    public function forget(string $key)
    {
        return $this->cache()->forget($key);
    }

    /**
     * Get value from $cache
     *
     * @return \Illuminate\Cache\CacheManager|Application|mixed
     */
    private function cache()
    {
        return $this->cache;
    }

    /**
     * Refresh cache value
     *
     * @param string $key Setting key
     * @return bool
     */
    public function refresh(string $key)
    {
        $this->cache()->forget($key);
        $this->get($key);

        return $this->has($key);
    }

    /**
     * Get setting from cache
     *
     * @param string $key
     * @return SettingManager
     */
    public function get(string $key)
    {
        return $this->cache()->rememberForever($key, function () use ($key) {
            return Setting::find($key);
        });
    }

    /**
     * Check has setting key in cache or not
     *
     * @param string $key
     * @return bool
     * @throws InvalidArgumentException
     */
    public function has(string $key): bool
    {
        if ($this->isSupportTags()) {
            // Check key value from cache tags
            return $this->cache()->has($key);
        }
        // Ex: settings.key
        $prefix = config('settings.cache_prefix');
        $cache_key = $prefix . '.' . $key;

        // Check key value from cache if cache not support tags
        return $this->cache()->has($cache_key);
    }
}
