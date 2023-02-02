<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tariffs';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'service_place_id',
        'type_charge',
        'amount',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function exonerationDetail()
    {
        return $this->hasMany(ExonerationDetail::class);
    }

    public function places()
    {
        return $this->belongsTo(ServicePlace::class, 'service_place_id', 'id');
    }
}