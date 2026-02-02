<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Motif;
class Costumer extends Model
{



    use HasFactory;
     protected $fillable = [
        'number',
        'nom',
    ];




    public function motifs(){
        return $this->hasMany(Motif::class);
    }
}
