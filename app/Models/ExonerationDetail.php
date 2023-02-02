<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ExonerationDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exoneration_details';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'hour',
        'people',
        'date',
        'exonerated_amount',
        'exoneration_id',
        'service_place_id',
        'tariff_id',
        'not_charged',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function exoneration()
    {
        return $this->belongsTo(Exoneration::class, "exoneration_id", "id");
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class, "tariff_id", "id");
    }
}
