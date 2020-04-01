<?php


namespace Fragmency\Database\Instructions;


trait insert
{
    protected function _insert(array $array){
        if(isset($array['id'])) unset($array['id']);
        $this->instructions['action'] = ['action' => 'insert'];
        $this->instructions['data'] = $array;
        return $this->query();
    }

    protected function _insertGetModifier(){
        if(isset($this->instructions['data'][0]) && gettype($this->instructions['data'][0]) === 'array'){
            $multiple = true;
            $multiple_data = $this->instructions['data'];
        }else{
            $multiple = false;
            $multiple_data = [$this->instructions['data']];
        }

        $insert_maxup_keys = [];
        $insert_maxup_values = [];
        if(!$multiple && isset($this->instructions['maxup'])) {
            $insert_maxup_keys = array_map(function ($m) {
                return $m['column'];
            }, $this->instructions['maxup']);
            $insert_maxup_keys = array_combine($insert_maxup_keys,$insert_maxup_keys);
            $insert_maxup_values = array_map(function ($m) {
                return "MAX(" . $this->formatColumn($m['column']) . ")+" . $m['increment'];
            }, $this->instructions['maxup']);
        }

        $keys = array_map(function ($v) {
            return $this->formatColumn($v);
        }, array_keys(array_merge($insert_maxup_keys, $multiple_data[0])));
        $keys = join(',', $keys);
        $values_array = array_map(function ($va) use ($insert_maxup_keys,$insert_maxup_values) {
            $values = array_map(function ($v) {
                return $this->formatValue($v);
            }, array_values($va));
            $values = join(',', array_merge($insert_maxup_values, $values));
            return "($values)";
        },$multiple_data);

        if(isset($this->instructions['maxup']) && !$multiple) $insert = "SELECT ".join(',',$values_array)." FROM ".$this->table;
        else{
            $insert = "VALUES ".join(',',$values_array);
        }
        return "($keys) $insert";
    }
}