<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'entities';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'entity_name',
        'representative_name',
        'phone',
        'mail',
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
        return $this->hasMany(Instrument::class, "entity_id")->withTrashed();
    }
}