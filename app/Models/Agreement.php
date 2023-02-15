<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agreements';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'agreement_name',
        'description',
        'type_agreement_id',
        'entity_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'id');
    }

    public function typeAgreement()
    {
        return $this->belongsTo(TypeAgreement::class, 'type_agreement_id', 'id');
    }

    public function exonerations()
    {
        return $this->hasMany(Exoneration::class);
    }
}
