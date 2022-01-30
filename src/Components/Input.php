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
        // Meta of input
        $metas = [];
        if ($meta = $setting ? $setting->meta : null) {
            foreach ($meta as $key => $val) {
                $metas[$key] = $val;
            }
        }
        // Input attribute
        $input_attributes = array_merge($metas, [
            'key' => $this->key,
            'value' => $setting ? $setting->value : $this->default,
            'class' => $this->class,
            'style' => $this->style,
            'readonly' => $setting && $setting->is_locked() ? 'true' : 'false',
        ]);

        return view('settings::components.input', compact('input_attributes'));
    }
}
