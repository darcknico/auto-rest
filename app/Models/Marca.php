<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class Marca extends Model
{
  use Eloquence, Mappable;

  protected $table ='marcas';

}
