<?php

namespace Model;

class Journaled extends Entity{

	protected $created, $modified, $by, $inactive, $group;

	//public $schema, $tn, $rpp = 20, $columns = '*';

	function __construct($table, $config = false){
		parent::__construct($table);
		$config && $this->config = $config;
		$this->inactive = array_search('tinyint(1)', $this->schema['columns']);
		$this->by = key(array_filter($this->schema['parents'], function($parent){
			return key_exists(Configuration\USER['usersTable'], $parent);
		}));
		list($this->created, $this->modified) = array_keys($this->schema['columms'], 'timestamp');
	}
	function getList($page = 1, $filters = [], $callback = false){
		$this->inactive && $filters[$this->inactive] = 0;
		return parent::getList($page, $filters, $callback);
	}
	function getInactiveList($page = 1, $filters = [], $callback = false){
		$this->inactive && $filters[$this->inactive] = 1;
		return parent::getList($page, $filters, $callback);
	}
	function getListByUser($id){
		$this->by && $filters[$this->by] = $id;
		return parent::getList($page, $filters, $callback);
	}
	function getByUser
	//FIXME!! children
	function get($id, $children = [], $default = true){
		$query = 'select '.$this->columns.' from '.$this->tn.(is_array($id) ? $this->filters($id) : ' where '.$this->schema['pk'].'=?');
		$lines = $this->db->query($query, $id)->fetchAll();
		$data = count($lines) ? current($lines) : false;
		if(count($children) && $data){
			foreach($children as $child){
				if(key_exists($child, $this->schema['children'])){
					$camel = self::camel($child);
					if($default){
						$l = 0;
						(new self($child))->getList(false, [$this->schema['children'][$child][$this->pk]=>$id], function($line) use(&$l, $camel, &$data){
							$prefix = $camel.'['.$l++.']';
							foreach($line as $field => $value){
								$data[$prefix.'['.$field.']'] = $value;
							}
						});
					}//var_dump($data);die;
					else{
						$data[$camel] = (new self($child))->getList(false, [$this->schema['children'][$child][$this->pk]=>$id]);
					}
				}
			}
		}
		return $data;
	}

	function pages(){
		return ceil(current(current($this->db->query('select found_rows()')->fetchAll()))/$this->rpp);
	}

	function child($childName){
		return empty($this->schema['children'][$childName]) ?: new self($childName);
	}

	protected function filters(&$filters){
		$where = [];
		foreach($filters as $field=>$value){
			!key_exists($field, $this->schema['columns']) ?: $where[] = $field;
		}
		$filters = array_values($filters);
		return !count($where) ? '' : ' where '.implode('=? and ', $where).'=?';
	}

	function add($post){
		$start = $this->db->start();
		$columnSchema = $this->schema['columns'];
		unset($columnSchema[$this->schema['pk']]);
		$columns = array_keys($columnSchema);
		$tokens = [];
		foreach($columns as $column){
			$tokens[] = key_exists($column, $post) ? $post[$column] : NULL ;
		}
		$id = $this->db->query('insert into '.$this->tn.' ('.implode(', ', $columns).') values ('.substr(str_repeat('?, ', count($columns)), 0, -2).')', $tokens)->id();
		foreach($this->schema['children'] as $child=>$relation){
			if(key_exists($camel = self::camel($child), $post)){
				$childEntity = new self($child);
				foreach($post[$camel] as $record){
					$childEntity->add($record+[$relation[$this->pk]=>$id]);
				}
			}
		}
		$start && $this->db->finish();
	}

	protected static function camel($field){
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
	}
	/*function underscore($str){
		for($l = strlen($str), $x = 1, $_ = $str[$x]; $x<$l; $_ .= (ctype_upper($str[$x]) ? '_' : '').$str[$x++]);
		return strtolower($_);
	}*/
	function edit($post, $id){
		!empty($id) or die('no pk');
		$columnSchema = $this->schema['columns'];
		unset($columnSchema[$this->schema['pk']]);
		$columns = array_keys($columnSchema);
		$tokens = [];
		foreach($columns as $column){
			$tokens[] = key_exists($column, $post) ? $post[$column] : NULL ;}
		$tokens[] = $id;
		$this->db->query('update '.$this->tn.' set '.implode('=?, ', $columns).'=? where '.$this->pk.'=?', $tokens);
		foreach($this->schema['children'] as $child=>$relation){
			if(key_exists($camel = self::camel($child), $post)){
				$childEntity = new self($child);
				$rel = [$relation[$this->pk]=>$id];
				$childPK = $childEntity->pk;
				foreach($post[$camel] as $id => $record){
					$id<0 ? $childEntity->delete($record[$childPK]) : (empty($record[$childPK]) ? $childEntity->add($record+$rel) : $childEntity->edit($record+$rel, $record[$childPK]));
				}
			}
		}
	}
	function getParents(){//FIXME!!!! (workaround for catalog: first column filled on trigger)
		$parents = [];
		foreach($this->schema['parents'] as $column => $parent){
			$parents[$column] = (new Entity(key($parent)))->catalog();
		}
		return $parents;
	}
	function catalog(){//TODO optional filter for lists (create requires all)
		return $this->db->query('select * from '.$this->tn)->fetchAll(function($row){
			return [$row[$this->schema['pk']]=>current($row)];
		});
	}
	function delete($id){
		$this->db->start();
		$this->db->query('delete from '.$this->tn.' where '.$this->schema['pk'].'=?', [$id]);
		$this->db->finish();
	}
}

