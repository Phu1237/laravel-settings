<?php

namespace Phu1237\LaravelSettings;

use Phu1237\LaravelSettings\Models\Setting as SettingModel;

class SettingManager
{
    /**
     * Access session manager.
     */
    private function session(): SessionManager
    {
        return new SessionManager();
    }

    /**
     * Get all the settings.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->generateCollect(SettingModel::all());
    }

    private function generateCollect($list) {
        $collect = collect();
        foreach ($list as $item) {
            $setting = new Setting($item->key, $item->value, $item->meta);
            $collect->push($setting);
        }
        return $collect;
    }

    /**
     * Check has setting key or not.
     */
    public function has(string $key = null): bool
    {
        return SettingModel::whereKey($key)->count() > 0;
    }

    /**
     * @param string       $key   Key of Setting
     * @param string|null  $value Value of Setting
     * @param string|array $metas Meta(s) of Setting
     *
     * @return mixed
     */
    public function store(string $key, ?string $value, $meta = null)
    {
        $new_setting_value = ['key' => $key, 'value' => $value];
        if ($meta != null) {
            if (is_array($meta)) {
                $meta = json_encode($meta);
            }

            $new_setting_value['meta'] = json_decode(json_encode($meta));
        }

        SettingModel::updateOrCreate(
            ['key' => $key],
            $new_setting_value,
        );
        $this->session()->forget($key);

        return true;
    }

    /**
     * Get setting.
     *
     * @param string $key Key of setting
     *
     * @return SettingModel|null
     */
    public function get(string $key)
    {
        if ($this->session()->isEnable()) {
            $setting = $this->session()->getOrSet($key);
            return $setting;
        }
        $model = SettingModel::find($key);
        if ($model != null) {
            $setting = new Setting($model->key, $model->value, $model->meta);
            return $setting;
        }

        return null;
    }

    /**
     * @param string|array $key   Setting key
     * @param null         $value New setting value
     *
     * @return mixed
     */
    public function set($key, $value = null)
    {
        // If $key is an array
        if (is_array($key)) {
            $collection = collect();
            // Loop set value for each item of array
            foreach ($key as $item => $val) {
                $collection->push($this->setValue($item, $val));
            }

            return $collection;
        }

        return $this->setValue($key, $value);
    }

    /**
     * Delete setting(s).
     *
     * @param string|array $key Key(s) of setting want to forget
     *
     * @return bool
     */
    public function forget($key, bool $destroy = true)
    {
        if (is_array($key)) {
            foreach ($key as $item) {
                $this->forget($item);
            }
        }
        if ($destroy == true) {
            SettingModel::destroy($key);
        }
        $this->session()->forget($key);

        return true;
    }

    /**
     * Flush all setting items.
     */
    public function flush()
    {
        SettingModel::truncate();
        $this->session()->flush();
    }

    /**
     * Get or set setting value.
     *
     * @param string|array $key
     * @param null         $default
     *
     * @return mixed
     */
    public function value($key, $default = '')
    {
        if (is_array($key)) {
            $collection = collect();
            // Loop set value for each item of array
            foreach ($key as $item => $val) {
                $collection->push($this->setValue($item, $val));
            }

            return $collection;
        }

        return $this->getValue($key, $default);
    }

    /**
     * Get setting value.
     *
     * @param string      $key     Key of setting
     * @param string|null $default Default value if not found key value
     */
    private function getValue(string $key, string $default = ''): string
    {
        $settings = $this->get($key);
        if (!isset($settings)) {
            return $default;
        }

        return $settings->value;
    }

    /**
     * Set setting value.
     *
     * @param string $key   Setting key
     * @param null   $value
     *
     * @return mixed
     */
    private function setValue(string $key, $value = null)
    {
        // Successfully store item
        if ($store = $this->store($key, $value)) {
            return $store;
        }

        return null;
    }

    /**
     * Get or set setting meta(s).
     *
     * @param string $key       Setting key
     * @param null   $attribute Meta attribute(s)
     *
     * @return mixed
     */
    public function meta(string $key, $attribute = null, string $default = '')
    {
        // If attribute is exist
        if ($attribute == null) {
            return $this->getMeta($key);
        }

        /*
        * If attribute is an array, set value for each attribute in array
        * If not, get the value of attribute
        */
        if (is_array($attribute)) {
            return $this->setMetaAttr($key, $attribute);
        }
        return $this->getMetaAttr($key, $attribute, $default);
    }

    /**
     * Get setting meta.
     *
     * @param string $key Key of setting
     *
     * @return object
     */
    private function getMeta(string $key)
    {
        if ($this->get($key) == null) {
            return null;
        }

        return $this->get($key)->meta;
    }

    /**
     * Get setting meta attribute value.
     *
     * @param string $key       Key of setting
     * @param string $attribute Meta attribute want to get
     *
     * @return string|null
     */
    private function getMetaAttr(string $key, string $attribute, string $default = ''): string
    {
        return $this->getMeta($key)->$attribute ?? $default;
    }

    /**
     * Set setting meta attribute value.
     *
     * @param string $key Key of setting
     *
     * @return bool|SettingModel
     */
    private function setMetaAttr(string $key, array $attributes)
    {
        $setting = SettingModel::where('key', $key);
        $meta = [];
        foreach ($attributes as $key => $value) {
            $meta['meta->'.$key] = $value;
        }
        $setting->update($meta);
        $this->session()->refresh($key);

        return true;
    }

    /**
     * Get lock status.
     *
     * @return bool
     */
    public function is_locked(string $key)
    {
        return $this->get($key)->locked;
    }
}
