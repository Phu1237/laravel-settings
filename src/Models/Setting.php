<?php

namespace Phu1237\LaravelSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'meta', 'locked'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'object',
        'locked' => 'boolean'
    ];
    // primary key of table
    protected $attributes = [
        'meta' => '{"type":"text","placeholder":null,"required":false}'
    ];
    protected $appends = [
    ];
    protected $primaryKey = 'key';

    public function getMetaAttribute($value)
    {
        $value = json_decode($value);
        debug($value);
        $output = [];
        $output['type'] = isset($value->type) ? $value->type : 'text';
        $output['placeholder'] = isset($value->placeholder) ? $value->placeholder : null;
        $output['required'] = isset($value->required) ? $value->required : false;

        return json_decode(json_encode($output));
    }

    public function meta($key)
    {
        return $this->meta->$key;
    }

    public function is_locked()
    {
        return $this->locked;
    }
}
