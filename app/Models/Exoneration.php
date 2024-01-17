<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Exoneration extends Model
{
    use HasFactory;

    protected $table = 'exonerations';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'instrument_id',
        'date_event',
        'service_place_name',
        'number_hour',
        'number_people',
        'non_tariff_concept',
        'tariff_type_charge',
        'non_tariff_amount',
        'amount_people',
        'is_tariffed',
        'tariff_amount',
        'total_amount',
        'concept',
        'quantity',
        'estimated_price',
        'exonerated_description',
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
}