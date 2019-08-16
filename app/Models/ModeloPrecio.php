<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class ModeloPrecio extends Model
{
  use Eloquence, Mappable;

  protected $table ='modelo_precio';

  protected $fillable = [
  			'id',
  			'id_modelo',
  			'precio',
  			'created_at',
  			'updated_at',
  ];

  public function usuario(){
    return $this->hasOne('App\Models\Modelo','id','id_modelo');
  }

}
