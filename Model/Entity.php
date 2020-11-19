<?php /*by galfrano@gmail.com*/

namespace Model;

class Entity{

	protected $pdoh, $id, $fieldExceptions = [];

	public $schema, $tn, $rpp = 20, $columns = '*', $where = [], $tokens = [];

	function __construct($table){
		$this->tn = $table;
		$this->db = new Pdoh;
		$this->schema = (new Schema($this->db))->$table;
	}
	function __get($name){
		$fields = $this->columns !== '*' ? explode(', ', $this->columns) : array_keys($this->schema['columns']) ;
		$gettables = ['fields'=>$fields, 'pk'=>$this->schema['pk'], 'types'=>$this->schema['columns']];
		return empty($gettables[$name]) ? NULL : $gettables[$name] ;
	}
	function setColumns($columns){
		//TODO: validate agains schema
		$this->columns = $columns;
		return $this;
	}
	function getList($page = 1, $filters = [], $callback = false){
		$list = $this->db->query('select SQL_CALC_FOUND_ROWS '.$this->columns.' from '.$this->tn.$this->filters($filters).$this->page($page), $this->tokens)->fetchAll($callback);
		list($this->tokens, $this->where) = [[], []];
		return $list;
	}
	private function page($page){
		return is_numeric($page) && $this->rpp > 0 ? ' limit '.(($this->rpp*$page)-$this->rpp).', '.$this->rpp : '' ;
	}
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
					}
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

	protected function filters($filters){
		if($filters){
			foreach($filters as $field=>$value){
				$this->filter($field, $value);
			}
		}
		return count($this->where) ? ' where '.implode(' AND ', $this->where) : '' ;
	}
	//TODO: consider making public
	protected function filter($column, $value, $operator = '='){
		if(key_exists($column, $this->schema['columns'])){
			$this->where[] = ' '.$column.$operator.'? ';
			$this->tokens[] = $value;
		}
		return $this;
	}
	//TODO: escape html
	function add($post){
		$start = $this->db->start();
		$columnSchema = $this->schema['columns'];
		unset($columnSchema[$this->schema['pk']]);
		$columns = array_keys($columnSchema);
		$tokens = [];
		foreach($columns as $column){
			$tokens[] = key_exists($column, $post) ? htmlentities($post[$column]) : NULL ;
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
		foreach($columns as $k => $column){
			if(key_exists($column, $post)){
				$tokens[] = htmlentities($post[$column]);
			}
			else{
				unset($columns[$k]);
			}
		}
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
