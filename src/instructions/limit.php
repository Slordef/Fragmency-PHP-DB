<?php


namespace Fragmency\Database\Instructions;


trait limit
{
    protected function _limit(Int $min, Int $max = null){
        if($max === null) $this->instructions['limit']['max'] = $min;
        else{
            $this->instructions['limit']['min'] = $min;
            $this->instructions['limit']['max'] = $max;
        }
        return $this;
    }
}