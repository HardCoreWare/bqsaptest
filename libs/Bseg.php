<?php

    class Bseg{

        public function getSchema($route){

            //abrimos el archivo en forma de string gigante
            $headerString=file_get_contents($route);

            //generamos arreglo separado por comas
            $headers=explode(",",$headerString);

            //creamos esquema vacio para ser llenado
            $schema=[];
            $schema['fields']=[];

            // iteramos todos los headers para hacer un array asociativo
            foreach ($headers as $header) {

                // creamos campo por cada header
                $field=[];
                $field['name']=$header;
                $field['type']='string';


                // agregamos el campo al array de schema
                $schema['fields'][]=$field;

            }
            
            return $schema;

        }
    }


?>