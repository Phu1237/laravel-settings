<?php

namespace Phu1237\LaravelSettings\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Phu1237\LaravelSettings\Facades\Setting;

class Input extends Component
{
    public $key;
    public $class;

    /**
     * Create a new component instance.
     *
     * @param $key
     * @param $class
     */
    public function __construct($key, $class = null)
    {
        $this->key = $key;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        $setting = Setting::get($this->key);
        if ($setting) {
            $key = $setting->key;
            $value = $setting->value;
            $meta = $setting->meta;
            $class = $this->class;
            $locked = $setting->is_locked();

            return view('settings::components.input', compact('key', 'value', 'meta', 'class', 'locked'));
        }

        return view('settings::components.input');
    }
}
