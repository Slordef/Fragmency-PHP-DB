<?php


namespace Fragmency\Database\Instructions;


trait where
{
    protected function _where(String $instruction,String $operator = null,$value = null){
        if(!isset($this->instructions['where'])) $this->instructions['where'] = [];
        if($operator === null && $value === null) $this->instructions['where'][] = ['operation' => $instruction];
        else $this->instructions['where'][] = [
            'column' => $instruction,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }
}