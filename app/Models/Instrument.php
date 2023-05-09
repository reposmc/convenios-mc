<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instruments';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'type_instrument_id',
        'instrument_name',
        'sector_id',
        'entity_id',
        'description',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return Instrument::select('instruments.*', 'ti.type_instrument_name', 'e.entity_name', 's.sector_name')
            ->join('type_instruments as ti', 'instruments.type_instrument_id', '=', 'ti.id')
            ->join('entities as e', 'instruments.entity_id', '=', 'e.id')
            ->join('sectors as s', 'instruments.sector_id', '=', 's.id')
            ->where('instruments.instrument_name', 'like', $search)

            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("instruments.$sortBy", $sort)
            ->get();
    }

    public static function counterPagination($search)
    {
        return Instrument::select('instruments.*', 'ti.type_instrument_name', 'e.entity_name', 's.sector_name')
            ->join('type_instruments as ti', 'instruments.type_instrument_id', '=', 'ti.id')
            ->join('entities as e', 'instruments.entity_id', '=', 'e.id')
            ->join('sectors as s', 'instruments.sector_id', '=', 's.id')
            ->where('instruments.instrument_name', 'like', $search)

            ->count();
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id', 'id');
    }

    public function typeInstrument()
    {
        return $this->belongsTo(TypeInstrument::class, 'type_instrument_id', 'id');
    }

    public function exonerations()
    {
        return $this->hasMany(Exoneration::class);
    }
}
