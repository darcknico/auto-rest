<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class Modelo extends Model
{
  use Eloquence, Mappable;

  protected $table ='modelos';

  protected $fillable = [
  			'id',
  			'id_ext',
  			'id_marca',
  			'nombre',
  			'anio',	
  ];

  public function marca(){
    return $this->hasOne('App\Models\Marca','id','id_marca');
  }

  public function historial(){
    return $this->hasMany('App\Models\ModeloPrecio','id_modelo','id');
  }


}
