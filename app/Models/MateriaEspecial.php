<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MateriaEspecial extends Model
{
    public $timestamps = false;
    protected $connection = "mysql";
    protected $table = "si_cat_materias_especiales";
    protected $primaryKey = ['cve_carrera', 'cve_materia'];
    public $incrementing = false;


    protected $fillable = [
        'cve_carrera', 'cve_materia','nivel_ocultar','nivel','columna'
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
