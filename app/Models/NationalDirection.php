<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NationalDirection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'national_directions';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'national_direction_name',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = false;

    public function dependences()
    {
        return $this->hasMany(NationalDirection::class);
    }

    public function dependence(){
        return $this->hasMany(Dependence::class, "national_direction_id")->withTrashed();
    }

}
