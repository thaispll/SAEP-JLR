<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    //colocamos o que é necessário no BD
    protected $fillable = [
        'nome', 'marca', 'estoque'
    ];

    public function movimentos(){
        return $this->hasMany(Movimento::class);
    } // relacionamento um para muitos 
}
