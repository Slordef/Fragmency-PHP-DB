<?php


namespace Fragmency\Database\Instructions;


trait select
{
    protected function _select($value = '*'){
        $this->instructions['action'] = [
            'action' => 'select',
            'value' => $value
        ];
        return $this;
    }
}