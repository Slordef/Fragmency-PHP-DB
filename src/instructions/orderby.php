<?php


namespace Fragmency\Database\Instructions;


trait orderby
{
    protected function _orderby(String $column, Bool $reverse = null){
        if(!isset($this->instructions['order'])) $this->instructions['order'] = [];
        $this->instructions['order'][] = ['column' => $column,'reverse' => $reverse];
        return $this;
    }
}