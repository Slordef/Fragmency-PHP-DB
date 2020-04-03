<?php


namespace Fragmency\Database;


use Fragmency\Database\Instructions\delete;
use Fragmency\Database\Instructions\insert;
use Fragmency\Database\Instructions\join;
use Fragmency\Database\Instructions\limit;
use Fragmency\Database\Instructions\maxup;
use Fragmency\Database\Instructions\orderby;
use Fragmency\Database\Instructions\select;
use Fragmency\Database\Instructions\update;
use Fragmency\Database\Instructions\where;

abstract class Database
{
    use where,orderby,limit,select,insert,update,delete,maxup,join;

    public function __call($name, $arguments)
    {
        $call = '_'.$name;
        if(is_callable([$this,$call])) return call_user_func_array([$this,$call],$arguments);
        else switch ($name){
            //case 'test':return $this->_get(); break;
            default: throw new \Exception("Fragmency DATABASE class : no method called \"$name\" !");
        }
    }

    public static function table(String $name){ return new DB($name,'table'); }

    private $table;
    private $intention;
    private $instructions = array();
    private $template = [];
    /*
     * CREATE TABLE `table` ( `id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`) )
     * ALTER TABLE `table` ADD `column` INT NOT NULL
     * ALTER TABLE `table`
     *
     * SELECT `column`|* FROM `table` WHERE `column` = 'value' AND|OR () ORDER BY `column` ASC|DESC LIMIT int,int
     * SELECT `column` FROM `table` INNER|LEFT|RIGHT JOIN `table` ON `table.column` = `table.column`
     * INSERT INTO `table` (`column`) VALUES ('value') WHERE `column` = 'value'
     * UPDATE `table` SET `column` = 'value' WHERE `column` = 'value'
     * DELETE FROM `table` WHERE `column` = 'value'
     */

    public function __construct(String $table){
        $this->table = $table;
    }

    private function _get(){
        if(!isset($this->instructions['action'])) $this->select();
        return $this->query();
    }

    private function _first(){
        if(!isset($this->instructions['action'])) $this->select();
        if(!isset($this->instructions['limit'])) $this->limit(1);
        return $this->query();
    }


    private function compile(){
        if(!isset($this->instructions['action'])) $this->instructions['action'] = ['action' => 'select', 'value' => '*'];
        switch ($this->instructions['action']['action']) {
            case 'insert':
                $action = "INSERT INTO ";
                break;
            case 'update':
                $action = "UPDATE ";
                break;
            case 'delete':
                $action = "DELETE FROM ";
                break;
            case 'select':
            default:
                if(gettype($this->instructions['action']['value']) === 'array')
                    $selectValues = join(',',array_map(function ($v){return $this->formatColumn(strtolower($v));},$this->instructions['action']['value']));
                else $selectValues = strtolower($this->formatColumn($this->instructions['action']['value']));
                $action = "SELECT " . $selectValues . " FROM ";
                break;
        }
        if($this->instructions['action']['action'] !== 'insert') $join = $this->_joinGetModifier();
        else $join = "";

        $modifier = "";

        $where = "";
        if(isset($this->instructions['where'])) {
            $mods_where = array_map(function ($w) {
                if (isset($w['operation'])) return "(" . $w['operation'] . ")";
                else return "(`" . $w['column'] . "` " . $w['operator'] . " " . $this->formatValue($w['value']) . ")";
            }, $this->instructions['where']);
            if (count($mods_where)) $where = "WHERE " . join(' AND ', $mods_where) . " ";
        }

        $order = "";
        if(isset($this->instructions['order'])) {
            $mods_order = array_map(function ($o) {
                return "`" . $o['column'] . "` " . ($o['reverse'] ? "DESC" : "ASC");
            }, $this->instructions['order']);
            if (count($mods_order)) $order = "ORDER BY " . join(', ', $mods_order) . " ";
        }

        $limit = "";
        if(isset($this->instructions['limit'])){
            $limit = "LIMIT ";
            if($this->instructions['limit']['min'] !== null) $limit .= $this->instructions['limit']['min'].",";
            $limit .= $this->instructions['limit']['max']." ";
        }

        $modifier = $where.$order.$limit;

        if(isset($this->instructions['data'])) {
            switch ($this->instructions['action']['action']) {
                case 'insert':
                    $modifier = $this->_insertGetModifier();
                    break;
                case 'update':
                    $modifier = $this->_updateGetModifier()." ".$where;
                    break;
                case 'delete':
                    $modifier = $where;
                    break;
            }
        }

        $query = $action."".$this->table.$join." ".$modifier;
        return $query;
    }

    private function formatValue ($value) {
        $type = gettype($value);
        switch ($type){
            case "boolean": return $value;
            case "integer": return $value;
            case "double": return "'$value'";
            case "string": return "'".htmlentities($value,ENT_QUOTES)."'";
            case "array": throw new \Exception("Type of value for Database can't be an array !");
            case "object": throw new \Exception("Type of value for Database can't be an object !");
            case "resource": throw new \Exception("Type of value for Database can't be a resource !");
            case "NULL": return "NULL";
        }
    }

    private function formatColumn($column){
        switch ($column){
            case '*': return $column;
            default: return "`$column`";
        }
    }

    private function query(){
        $query = $this->compile();
        return $query;
    }

    private function _query(string $query){

    }

}