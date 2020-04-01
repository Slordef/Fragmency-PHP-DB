<?php


namespace Fragmency\Database\Instructions;


trait update
{
    protected function _update(array $array){
        if(isset($array['id'])) unset($array['id']);
        $this->instructions['action'] = ['action' => 'update'];
        $this->instructions['data'] = $array;
        return $this->query();
    }

    protected function _updateGetModifier(){
        $set = array_map(function ($k,$v){
            return $k." = ".$this->formatValue($v);
        },array_keys($this->instructions['data']),$this->instructions['data']);
        return "SET ".join(', ',$set);
    }
}