<?php


namespace Fragmency\Database\Instructions;


trait maxup
{
    protected function _maxup(string $column,int $increment){
        $this->instructions['maxup'][] = [
            'column' => $column,
            'increment' => $increment
        ];
        return $this;
    }
}