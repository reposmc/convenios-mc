<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Exoneration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exonerations';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'exonerated_description',
        'instrument_id',
        'service_place_name',
        //'dependence_id',
        'number_hour',
        'number_people',
        'non_tariff_concept',
        'non_tariff_amount',
        'amount_people',
        'date_event',
        'is_tariffed',
        //'tariff_id',
        'tariff_amount',
        'total_amount',
        //'total_value',
        'concept',
        'quantity',
        'estimated_price',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function instrument()
    {
        return $this->belongsTo(Instrument::class, 'instrument_id', 'id');
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class, "tariff_id_hour", "id");
    }

    /* public function dependence()
    {
        return $this->belongsTo(Dependence::class, "dependence_id", "id");
    } */
}
