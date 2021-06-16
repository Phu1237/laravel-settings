<?php

namespace Phu1237\LaravelSettings\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Phu1237\LaravelSettings\Facades\Setting;

class Input extends Component
{
    public $key;
    public $default;
    public $class;
    public $style;

    /**
     * Create a new component instance.
     *
     * @param $key
     * @param $class
     */
    public function __construct($key, $default = null, $class = null, $style = null)
    {
        $this->key = $key;
        $this->default = $default;
        $this->class = $class;
        $this->style = $style;
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
            $style = $this->style;
            $locked = $setting->is_locked();

            return view('settings::components.input', compact('key', 'value', 'meta', 'class', 'style', 'locked'));
        }

        return view('settings::components.input');
    }
}
