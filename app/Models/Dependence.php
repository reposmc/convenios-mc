<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Dependence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dependences';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'dependence_name',
        'national_direction_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function exonerations()
    {
        return $this->hasMany(Exoneration::class);
    }

    public function national_directions()
    {
        return $this->belongsTo(NationalDirection::class, 'national_direction_id', 'id');
    }

    public function instrument()
    {
        return $this->hasMany(InstrumentsDependeciesDetail::class, "dependency_id");
    }

    public function user()
    {
        return $this->hasMany(UsersDependenciesDetail::class, "dependency_id");
    }

    public function tariff()
    {
        return $this->hasMany(Tariff::class, "dependence_id");
    }
}
