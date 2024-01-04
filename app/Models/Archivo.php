<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archivo extends Model
{
   use HasFactory, SoftDeletes;

   protected $table = "archivos";

   protected $fillable = ["id", "documento", "nombre", "instrument_id"];

   public $timestamps = false;

   protected $data = ['deleted_at'];

   protected $hidden = [
      'created_at',
      'updated_at',
      'deleted_at',
   ];

   public function instrument()
   {
      return $this->belongsTo(Archivo::class, "instrument_id")->withTrashed();
   }
}
