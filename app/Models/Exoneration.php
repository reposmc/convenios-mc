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
        'agreement_id',
        'dependence_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function agreement()
    {
        return $this->belongsTo(Agreement::class, 'agreement_id', 'id');
    }

    public function dependence()
    {
        return $this->belongsTo(ServicePlace::class, 'dependence_id', 'id');
    }

    public function exonerationDetail()
    {
        return $this->hasMany(ExonerationDetail::class);
    }
}