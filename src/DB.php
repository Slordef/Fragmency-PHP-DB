<?php


namespace Fragmency\Database;

/**
 * Class DB
 * @package Fragmency
 *
 * @method static DB table(string $name)
 * @method static array query(string $query)
 * @method DB where(string $instruction,string $operator = null,$value = null)
 * @method DB limit(int $min,int $max)
 * @method DB orderby(mixed $column,bool $reverse = null)
 * @method string get()
 * @method string first()
 * @method DB select(string $value)
 * @method int insert(array $array)
 * @method int update(array $array)
 * @method bool delete(int $id = null)
 * @method DB maxup(string $column, int $increment)
 * @method DB innerjoin(string $table, string $on, string $operator, string $column)
 * @method DB leftjoin(string $table, string $on, string $operator, string $column)
 * @method DB rightjoin(string $table, string $on, string $operator, string $column)
 */

class DB extends Database {}