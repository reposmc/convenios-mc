<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TypeInstrument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'type_instruments';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'type_instrument_name',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function instrument()
    {
        return $this->hasMany(Instrument::class);
    }

    public function instruments()
    {
        return $this->hasMany(Instrument::class, "type_instrument_id")->withTrashed();
    }
}
