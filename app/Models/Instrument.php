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

        //Getting the role
        $roles = auth()->user()->getRoleNames();

        if (isset($roles[0])) {
            if ($roles[0] == "Usuario") {

                $userDependencies = UsersDependenciesDetail::where('user_id', auth()->user()->id)
                    ->get()
                    ->pluck('dependency_id')
                    ->toArray();

                return InstrumentsDependeciesDetail::select(
                    'dependency_id',
                    'inst.*',
                    'inst.id as instrument_id',
                    'dep.*',
                    'ti.type_instrument_name',
                    'e.entity_name',
                    's.sector_name'
                )
                    ->join('instruments as inst', 'instruments_dependecies_detail.instrument_id', '=', 'inst.id')
                    ->join('dependences as dep', 'instruments_dependecies_detail.dependency_id', '=', 'dep.id')
                    ->join('type_instruments as ti', 'inst.type_instrument_id', '=', 'ti.id')
                    ->join('entities as e', 'inst.entity_id', '=', 'e.id')
                    ->join('sectors as s', 'inst.sector_id', '=', 's.id')
                    ->where('inst.instrument_name', 'like', $search)
                    ->where('dep.id', $userDependencies)
                    ->skip($skip)
                    ->take($itemsPerPage)
                    ->orderBy("inst.$sortBy", $sort)
                    ->get();
            } else {
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
        }
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
