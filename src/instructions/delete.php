<?php


namespace Fragmency\Database\Instructions;


trait delete
{
    protected function _delete(Int $id = null){
        if($id !== null) $this->where('id','=',$id);
        $this->instructions['action'] = ['action' => 'delete'];
        return $this->query();
    }
}