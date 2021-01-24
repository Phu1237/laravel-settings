<?php

namespace Phu1237\LaravelSettings;

use Illuminate\Database\Eloquent\Collection;
use Phu1237\LaravelSettings\Models\Setting as SettingModel;

class SettingManager
{
    public function __construct()
    {

    }

    /**
     * Check has setting key or not
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return (bool)SettingModel::find($key);
    }

    /**
     * @param string|array $key Setting key
     * @param null $value New setting value
     * @return \Illuminate\Support\Collection|SettingModel
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $collection = collect();
            foreach ($key as $item => $val) {
                $collection->push($this->setValue($item, $val));
            }

            return $collection;
        }

        return $this->setValue($key, $value);
    }

    /**
     * Set setting value
     *
     * @param string|array $key Setting key
     * @param $value
     * @return mixed
     */
    private function setValue($key, $value = null)
    {
        if (is_array($key)) {
            $collection = collect();
            foreach ($key as $item => $val) {
                $collection->push($this->setValue($item, $val));
            }

            return $collection;
        }

        if ($this->store($key, $value)) {
            return SettingModel::find($key);
        }

        return null;
    }

    /**
     * @param string $key Key of Setting
     * @param string|null $value Value of Setting
     * @param array $metas Meta(s) of Setting
     * @return mixed
     */
    public function store(string $key, string $value = null, array $metas = [])
    {
        if (!SettingModel::find($key)) {
            $setting = [
                'key' => $key,
                'value' => $value,
            ];
            if ($metas != null) $setting['meta'] = json_decode(json_encode($metas));
            SettingModel::create($setting);
        } else {
            $setting = SettingModel::find($key);
            if ($value != null) {
                $setting->value = $value;
            }
            if (count($metas) > 0) {
                $setting->meta = json_decode(json_encode($metas));
            }
            $setting->save();
            $this->cache()->refresh($key);
        }

        return SettingModel::find($key);
    }

    public function cache()
    {
        return new CacheManager();
    }

    /**
     * Get all the settings
     *
     * @return Collection
     */
    public function all()
    {
        return SettingModel::all();
    }

    public function metaHTML($key, $meta)
    {
        if (!isset(SettingModel::find($key)->meta->$meta)) {
            return false;
        }
        $meta = $this->meta($key);
        $type = $meta->type;
        $placeholder = $meta->placeholder;
        $value = $this->getValue($key);

        return view('settings::components.input', compact('key', 'type', 'placeholder', 'value'));
    }

    /**
     * Get or set setting meta(s)
     *
     * @param string|null $key Setting key
     * @param string|null $attribute Meta attribute(s)
     * @param null $value
     * @return mixed
     */
    public function meta(string $key, $attribute = null, $value = null)
    {
        if ($attribute != null) {
            if (is_array($attribute)) {
                return $this->setMetaAttr($key, $attribute);
            } else if (is_string($attribute)) {
                if ($value != null) {
                    return $this->setMetaAttr($key, [$attribute => $value]);
                }

                return $this->getMetaAttr($key, $attribute);
            }
        }

        return $this->getMeta($key);
    }

    /**
     * Set setting meta attribute value
     *
     * @param string $key Key of setting
     * @param array $attributes
     * @return bool|SettingModel
     */
    private function setMetaAttr(string $key, array $attributes)
    {
        $setting = SettingModel::where('key', $key);
        $meta = [];
        foreach ($attributes as $key => $value) {
            $meta['meta->' . $key] = $value;
        }
        $setting->update($meta);
        $this->cache()->refresh($key);

        return $setting->first();
    }

    /**
     * Get setting meta attribute value
     *
     * @param string $key Key of setting
     * @param string $attribute Meta attribute want to get
     * @return string|null
     */
    private function getMetaAttr(string $key, string $attribute)
    {
        $meta = $this->getMeta($key);

        return isset($meta->$attribute) ? $meta->$attribute : null;
    }

    /**
     * Get setting meta
     *
     * @param string $key Key of setting
     * @return Object
     */
    private function getMeta(string $key)
    {
        if ($this->get($key) == null) {
            return null;
        }

        return $this->get($key)->meta;
    }

    /**
     * Get setting
     *
     * @param string $key Key of setting
     * @return SettingModel
     */
    public function get(string $key)
    {
        if ($this->cache()->isEnabled()) {
            $setting = $this->cache()->get($key);
        } else {
            $setting = SettingModel::find($key);
        }

        return $setting;
    }

    /**
     * Get setting value
     *
     * @param string $key Key of setting
     * @param string|null $default Default value if not found key value
     * @return string
     */
    private function getValue(string $key, string $default = null)
    {
        $settings = $this->get($key);
        if (!isset($settings) && isset($default)) {
            return $default; // If not found setting value
        }

        return $settings->value;
    }

    public function value($key, $value = null)
    {
        if (is_array($key)) {
            return $this->setValue($key);
        }
        if ($value != null) {
            return $this->setValue($key, $value);
        }

        return $this->getValue($key);
    }

    /**
     * Delete setting(s)
     *
     * @param string|array $key Key(s) of setting want to forget
     * @return bool
     */
    public function forget($key)
    {
        SettingModel::destroy($key);
        $this->cache()->forget($key);

        return true;
    }
}
