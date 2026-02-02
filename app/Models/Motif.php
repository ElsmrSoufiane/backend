<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Costumer;
class Motif extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'user_id',
        'costumer_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function costumer(){
    return $this->belongsTo(Costumer::class);
    }
}
