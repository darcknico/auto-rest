<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class Modelo extends Model
{
  use Eloquence, Mappable;

  protected $table ='modelos';

  public function usuario(){
    return $this->hasOne('App\Models\Marca','id','id_marca');
  }

}
