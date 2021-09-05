<?php

namespace Phu1237\LaravelSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    public function __construct()
    {
        $driver = config('settings.driver');
        $this->table = config('settings.connections.'.$driver.'.provider');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'meta', 'locked',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'locked' => 'boolean',
    ];
    // primary key of table
    protected $primaryKey = 'key';
}
