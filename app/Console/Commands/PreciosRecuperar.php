<?php

namespace App\Console\Commands;

use App\Models\Modelo;
use App\Models\ModeloPrecio;
use App\Models\Marca;
use Carbon\Carbon;
use Goutte\Client;

use Illuminate\Console\Command;

class PreciosRecuperar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'precios:recuperar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraper de SR. recolector de datos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $marcas = Marca::all();
        foreach($marcas as $marca_v)
        {
            for($i = 0; $i <= 15; $i++) 
            {
                $id_marca = $marca_v->id;
                $anio = (2018 - $i);

                $res = $client->request('POST', 'http://www.santanderrioseguros.com.ar/agents/common/optionsLovs.jsp?tpLov=cdModelo',[
                    'form_params' => [
                        'cdMarca' => $id_marca,
                        'anio' => $anio,
                    ]
                ]);
                $client = new \GuzzleHttp\Client();
                $res = $client->request('POST', 'http://www.santanderrioseguros.com.ar/agents/common/optionsLovs.jsp?tpLov=cdModelo', [
                    'form_params' => [
                        'cdMarca' => $id_marca,
                        'anio' => $anio,
                    ]
                ]);

                $string = $res->getBody()->getContents();
                $array = explode("\n", $string);
                $array_final = array();

                foreach($array as $elemento)
                {
                    if(strlen($elemento) > 1)
                    {
                        $cadena = str_replace("P-UP", "PICKUP", $elemento);
                        $array = explode("-", $cadena);
                        $elemento = str_replace("</option>", "",  $array[0]);
                        $elemento = str_replace('<option value=', "", $elemento);
                        $final = explode(">", $elemento);
                        sscanf($final[0], "'%d|%d'", $id, $precio);

                        $modelo = Modelo::where('anio',$anio)
                            ->where('id_ext',$id)
                            ->where('id_marca', $id_marca)
                            ->first();

                        if($modelo) {
                            /* se usa para arreglar el bug de la s10, soluciona2
                            $modelo = $existe->first();
                            if($modelo->nombre == "P")
                            {
                                $modelo->update(['nombre' => $final[1]]);
                                echo "se actualizo una VW s10 a ".$final[1];
                            }*/
                            //echo "| existe en DB | id ". $modelo->first()->id ." <br>";
                            // almacenar los precios en el historial
                            ModeloPrecio::Create([
                                'id_vehiculo'=>$modelo->id,
                                'precio'=>$precio,
                            ]);
                        } else {
                            
                            $modelo = new Modelo;
                            $modelo->id_ext = $id;
                            $modelo->id_marca = $id_marca;
                            $modelo->nombre = $final[1];
                            $modelo->anio = $anio;
                            $modelo->save();
                        }
                        $precio = new ModeloPrecio;
                        $precio->id_modelo = $modelo->id;
                        $precio->precio = $precio;
                        $precio->save();
                    }
                }
            }
        }
    }
}
