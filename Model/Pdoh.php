<?php /*by galfrano@gmail.com*/
//the role of this class is solely to provide interactions with database adapter and statements. database agnostic.
namespace Model;
class Pdoh{

	//TODO: create and import some constants for readability

	protected $dbi, $stmt = [], $cstmt = -1;

	protected static $pdo = [];

	function __construct(){
		key_exists(\Configuration\D, self::$pdo) ?: self::$pdo[\Configuration\D] = new \PDO('mysql:host='.\Configuration\H.';dbname='.\Configuration\D, \Configuration\U, \Configuration\P);
		$this->dbi = \Configuration\D;}

	//throws exception
	protected static function kill($stmt, $tokens = []){
		$message = $stmt instanceof \PDOStatement ? $stmt->errorInfo()[2]."\n\n\t".'Query string: '.$stmt->queryString."\n\t".'Tokens: '.str_replace("\n", '', var_export($tokens, 1)) : $stmt ;
		throw new \Exception($message."\n\n");}

	//prepares query, executes it and returns this or returns lamda-set-statement/this.
	//use 1: $table = $pdoh->query($query, $tokens)->fetchAll();
	//use 2: $stmt = $pdoh->query($query, false); $stmt()->execute($tokens)->fetchAll();
	function query($query, $tokens = []){
		$this->stmt[] = self::$pdo[$this->dbi]->prepare($query);
		($bool = is_bool($tokens)) ? list($c, $this->cstmt) = [count($this->stmt)-1, -1] : end($this->stmt)->execute((is_array($tokens) ? $tokens : [$tokens])) or self::kill(end($this->stmt), $tokens);
		return !$bool ? $this : function()use($c){
			$this->cstmt = $c;
			return $this;};}

	function id(){
		return self::$pdo[$this->dbi]->lastInsertId();}

	function execute($tokens = [], $stmt = -1){
		$this->valid($stmt);
		$this->stmt[$stmt]->execute($tokens) or self::kill($this->stmt[$stmt], $tokens);
		return $this;}

	function fetchAll($callback = false, $stmt = -1, $fs = 2){
		$this->valid($stmt);
		if($c = is_callable($callback)){
			for($r = []; $line = $this->stmt[$stmt]->fetch($fs); $cr = $callback($line), !is_array($cr) ? $r[] = $line : $r += $cr);}
		return $c ? $r : $this->stmt[$stmt]->fetchAll($fs);}

	protected function valid(&$stmt){
		$stmt = $stmt < 0 ? ($this->cstmt < 0 ? count($this->stmt)-1 : $this->cstmt) : $stmt ;
		$stmt >= 0 && key_exists($stmt, $this->stmt) or die('No such statement');}}
