<?php /*by galfrano@gmail.com*/

namespace Model;

class Schema{

	private $pdoh;

	function __construct(Pdoh $pdoh){
		$this->db = $pdoh;
		$this->dbDir = __DIR__.'/'.\Configuration\D.'/';
		is_dir($this->dbDir) ?: $this->writeSchema();}

	private function writeSchema(){
		mkdir($this->dbDir);
		$tables = $this->db->query('show tables')->fetchAll(function(&$line){$line = current($line);});
		foreach($tables as $table){
			$tableSchema = [];
			$tableSchema['columns'] = $this->db->query('show columns from '.$table)->fetchAll(function($line)use(&$tableSchema){
				$line['Key'] != 'PRI' ?: $tableSchema['pk'] = $line['Field'];
				return [$line['Field']=>$line['Type']];});
			$tableSchema['parents'] = $this->getParents($table);
			$tableSchema['children'] = $this->getChildren($table);
			file_put_contents($this->dbDir.$table.'.php', '<?php '."\n".'return '.var_export($tableSchema, 1).';');}}

	private function getChildren($table){
		$ref = $this->db->query('select * from information_schema.key_column_usage where referenced_table_name=?', [$table])->fetchAll();
		$children = [];
		foreach($ref as $row){
			$children[$row['TABLE_NAME']] = [$row['REFERENCED_COLUMN_NAME']=>$row['COLUMN_NAME']];}
		return $children;}

	private function getParents($table){
		$ref = $this->db->query('select * from information_schema.key_column_usage where table_name=? and referenced_table_name is not null', [$table])->fetchAll();
		$parents = [];
		foreach($ref as $row){
			$parents[$row['COLUMN_NAME']] = [$row['REFERENCED_TABLE_NAME']=>$row['REFERENCED_COLUMN_NAME']];}
		return $parents;}

	function __get($name){
		is_file($file = $this->dbDir.$name.'.php') or die('no such file');
		return include $file;}}
