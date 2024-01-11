<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $guarded = [];
    public $table = 'artikels';

    public function komentar() {
        return $this->hasMany(ArtikelKomentar::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
