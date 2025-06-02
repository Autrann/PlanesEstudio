<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Planes extends Model
{
    public $timestamps = false;
    protected $connection = "mysql";
    protected $table = "si_cat_planes";
    protected $primaryKey = ['cve_materia', 'cve_carrera'];
    public $incrementing = false;


    protected $fillable = [
        "cve_materia","cve_carrera","cve_cacei","nivel","columna","optativa","vigente","version","tipo_materia","notas_academicas"
    ];


    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }
    
        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }
    
        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

}
