<?php


namespace Fragmency\Database\Instructions;


trait join
{
    protected function _innerjoin(string $table,string $on,string $operator,string $column){
        $this->_join('inner',$table,$on,$operator,$column);
        return $this;// "INNER ".$this->_join($table,$on,$operator,$column);
    }
    protected function _leftjoin(string $table,string $on,string $operator,string $column){
        $this->_join('left',$table,$on,$operator,$column);
        return $this;// "LEFT ".$this->_join($table,$on,$operator,$column);
    }
    protected function _rightjoin(string $table,string $on,string $operator,string $column){
        $this->_join('right',$table,$on,$operator,$column);
        return $this;// "RIGHT ".$this->_join($table,$on,$operator,$column);
    }

    private function _join(string $join,string $table,string $on,string $operator,string $column){
        if(!isset($this->instructions['join'])) $this->instructions['join'] = [];
        $this->instructions['join'][] = [
            'join' => $join,
            'table' => $table,
            'on' => $on,
            'operator' => $operator,
            'column' => $column,
        ];
        //"JOIN $table ON ".$this->formatColumn($on)." ".$operator." ".$this->formatColumn($column);
    }

    protected function _joinGetModifier(){
        if(isset($this->instructions['join'])){
            $join_array = array_map(function ($j){
                return strtoupper($j['join'])." JOIN ".$j['table']." ON ".$this->formatColumn($j['on'])." ".$j['operator']." ".$this->formatColumn($j['column']);
            },$this->instructions['join']);

            return " ".join(' ',$join_array);
        }
        return "";
    }
}