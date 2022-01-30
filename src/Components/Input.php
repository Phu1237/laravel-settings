<?php

namespace Phu1237\LaravelSettings\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
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
        // Values
        $key = $this->key;
        $value = $setting ? $setting->value : $this->default;
        $meta = $setting ? $setting->meta : null;
        $class = $this->class;
        $style = $this->style;
        $locked = $setting ? $setting->is_locked() : true;

        return view('settings::components.input', compact('key', 'value', 'meta', 'class', 'style', 'locked'));
    }
}
