<?php

namespace Phu1237\LaravelSettings;

class Setting
{
    public $key;
    public $value;
    public $meta;
    public $locked;

    /**
     * Setting object.
     *
     * @param mixed $meta
     */
    public function __construct(string $key = '', string $value = '', $meta = null, bool $default_meta = true, bool $locked = false)
    {
        $this->key = $key;
        $this->value = $value;
        $this->meta = $this->handleMeta($meta, $default_meta);
        $this->locked = $locked;
    }

    /**
     * Change meta type to json object.
     *
     * @param mixed $meta
     *
     * @return mixed
     */
    private function handleMeta($meta, $default_meta)
    {
        if (is_array($meta)) {
            $meta = json_encode($meta);

            return json_decode(json_encode($meta));
        } elseif (is_string($meta)) {
            if ($meta == '{}' && $default_meta == false) {
                return json_decode($meta);
            }
        }

        return json_decode(config('settings.default.meta'));
    }

    /**
     * Get the key.
     *
     * @return string|null
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the value.
     *
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Get the meta.
     */
    public function meta()
    {
        return $this->meta;
    }

    public function is_locked()
    {
        return $this->locked;
    }
}
