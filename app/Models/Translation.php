<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translations';

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'set',
        'language',
        'code',
        'value',
    ];
}
