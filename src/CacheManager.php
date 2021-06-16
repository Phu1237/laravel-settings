<?php

namespace Phu1237\LaravelSettings;

use Illuminate\Support\Facades\Cache;
use Phu1237\LaravelSettings\Models\Setting;

class CacheManager
{
    private $isSupportTags;
    private $cache;
    private $prefix;
    private $ttl;

    public function __construct()
    {
        $this->prefix = config('settings.sessions.cache.prefix');
        $this->ttl = config('settings.sessions.cache.ttl');
        $this->isSupportTags = $this->isSupportTags();
        if ($this->isSupportTags) {
            $this->cache = Cache::tags($this->prefix);
        }
        $this->cache = cache(); // Laravel cache helper
    }

    /**
     * Get value from $cache
     *
     * @return mixed
     */
    private function cache()
    {
        return $this->cache;
    }

    /**
     * Check if cache driver support tags or not
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

    /**
     * If cache driver support tags, return original key
     * If not return key with prefix
     *
     * @param  string   $key
     * @return string
     */
    private function key(string $key): string
    {
        return $this->isSupportTags ? $key : $this->prefix.$key;
    }

    /**
     * Get all cache items
     *
     * @return mixed
     */
    public function all()
    {
        if ($this->isSupportTags == true) {
            return $this->cache();
        }

        return null;
    }

    /**
     * Check has setting key in cache or not
     *
     * @param  string                     $key
     * @throws InvalidArgumentException
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->cache()->has($this->key($key));
    }

    /**
     * Get setting from cache
     *
     * @param  string       $key
     * @return array|null
     */
    public function get(string $key)
    {
        return $this->cache()->get($this->key($key));
    }

    /**
     *
     */
    public function set(string $key, string $value, $meta = null)
    {
        if ($this->ttl == null) {
            return $this->cache()->rememberForever($this->key($key), function () use ($key, $value, $meta) {
                return [
                    'key' => $key,
                    'value' => $value,
                    'meta' => $meta,
                ];
            });
        }

        return $this->cache()->remember($this->key($key), $this->ttl, function () use ($key, $value, $meta) {
            return [
                'key' => $key,
                'value' => $value,
                'meta' => $meta,
            ];
        });
    }

    /**
     * Forget cache of setting
     *
     * @param  string $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->cache()->forget($this->key($key));
    }

    /**
     * Flush all cache
     */
    public function flush(): bool
    {
        return $this->cache()->flush();
    }
}
