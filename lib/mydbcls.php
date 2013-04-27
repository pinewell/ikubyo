<?php
class mydbcls{
	private $srv;
	private $username;
	private $pass;
	private $dbname;
	private $pdo;

	function __construct($_srv,$_username,$_pass,$_dbname){
		$this->srv = $_srv;
		$this->username = $_username;
		$this->pass = $_pass;
		$this->dbname = $_dbname;
		$this->connect();
	}
	
	function connect(){
		$dsn = "mysql:host=".$this->srv."; dbname=".$this->dbname;
		try{
			$this->pdo = new PDO($dsn,$this->username,$this->pass,array(
			            PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`"
       					 ));
			$this->pdo->query("SET NAMES utf8;");
		} catch(PDOException $e) {
			var_dump($e->getMessage());
		}

	}
	function disconnect() {
		$this->pdo = null;
	}

	function getRecordCount() {
		$numargs = func_num_args();
		$ret=0;
		switch($numargs) {
			case 1:
				try{
					$sqlstr = func_get_arg(0);
					$stmt = $this->pdo->query($sqlstr);
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$ret = implode(", ", $row);
				} catch(PDOException $e) {
					$ret = 0;
				}
				break;
			case 3:
			 	$sqlstr =func_get_arg(0);
				$sqlstr = str_replace("@",":",$sqlstr);
				$p = func_get_arg(1);
				$v = func_get_arg(2);
			
				try{	
					$stmt = $this->pdo->prepare($sqlstr);
					if (is_array($p)) {
						for($i=0;$i<count($p);$i++){
							$p[$i] = str_replace("@",":",$p[$i]);
							$pcnt = strpos($sqlstr,$p[$i]);
							if (! $pcnt === false){
								$stmt->bindValue($p[$i],$v[$i]);						
							}
							
						}
						$stmt->execute();	
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						$ret = implode(", ", $row);
					} else {
						$p = str_replace("@",":",$p);
						$stmt->bindValue($p,$v);
						$stmt->execute();	
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						$ret = implode(", ", $row);
					}
				} catch(PDOException $e) {
					$ret = 0;
				}
				break;
			default:
				throw new exception("The argument is insufficient.(5~)");
				return NULL;
				break;
		}
		return $ret;

	}
	function getDataTable() {
		/*
		require 'lib/mydbcls.php';
		$db = new mydbcls("svr","dbuser","pass","dbname");
		$p=array("@COD","@NAM");
		$v=array("1","大豆");
		$tb = $db->getDataTable("SELECT cod,nam,ctim,uptim FROM crops WHERE cod=@COD and nam LIKE @NAM order by cod",p,v);
		if (count($tb)>0) {
        		for ($r=0;$r<count($tb);$r++) {
                		echo $tb[$r]['cod']."\t".$tb[$r]['nam'];
        		}       
		}
		$db->disconnect();  
		*/		

		$numargs = func_num_args();
		$ret="";
		unset($ret);
		switch($numargs) {
			case 1:
				try{
					$sqlstr = func_get_arg(0);
					$stmt = $this->pdo->query($sqlstr);
					$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

				} catch(PDOException $e) {
					return NULL;
				}
				break;
			case 3:
			 	$sqlstr =func_get_arg(0);
				$sqlstr = str_replace("@",":",$sqlstr);
				$p = func_get_arg(1);
				$v = func_get_arg(2);
			
				try{	
					$stmt = $this->pdo->prepare($sqlstr);
					if (is_array($p)) {
						for($i=0;$i<count($p);$i++){
							$p[$i] = str_replace("@",":",$p[$i]);
							$pcnt = strpos($sqlstr,$p[$i]);
							if (! $pcnt === false){
								$stmt->bindValue($p[$i],$v[$i]);						
							}
							
						}
						$stmt->execute();	
						$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
					} else {
						$p = str_replace("@",":",$p);
						$stmt->bindValue($p,$v);
						$stmt->execute();	
						$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
				} catch(PDOException $e) {
					return NULL;
				}
				break;
			default:
				throw new exception("The argument is insufficient.");
				return NULL;
				break;
		}
		return $ret;
	}

	function beginTransaction() {
		$this->pdo->beginTransaction();
	}
	function commit(){
		$this->pdo->commit();	
	}
	function rollBack(){
		$this->pdo->rollBack();	
	}

	function executeSql() {
		$numargs = func_num_args();
		$ret=0;
		switch($numargs) {
			case 1:
				try{
					$sqlstr = func_get_arg(0);
					$ret=$this->pdo->exec($sqlstr);	

				} catch (PDOException $e) {
					return 0;
				}
				break;
			case 3:
			 	$sqlstr =func_get_arg(0);
				$sqlstr = str_replace("@",":",$sqlstr);
				$p = func_get_arg(1);
				$v = func_get_arg(2);
			
				try{	
					$stmt = $this->pdo->prepare($sqlstr);
					if (is_array($p)) {
						for($i=0;$i<count($p);$i++){		
							$p[$i] = str_replace("@",":",$p[$i]);
							$pcnt = strpos($sqlstr,$p[$i]);
							if (! $pcnt === false){
								$stmt->bindValue($p[$i],$v[$i]);						
							}
																
						}
						$stmt->execute();	
						$ret = $stmt->rowCount();
					} else {
						$p = str_replace("@",":",$p);
						$stmt->bindValue($p,$v);
						$stmt->execute();	
						$ret = $stmt->rowCount();
					}
				} catch(PDOException $e) {
					return 0;
				}
				break;
			default:
				throw new exception("The argument is insufficient.");
				break;
		}
		return $ret;

	}

	function dLookup() {
		$numargs = func_num_args();
		$ret="";
		unset($ret);
		switch($numargs) {
			case 1:
				try{
					$sqlstr = func_get_arg(0);
					$stmt = $this->pdo->query($sqlstr);
					$row=$stmt->fetch(PDO::FETCH_ASSOC);

					if (is_array($row)) {
						$ret = implode(", ", $row);
					} else {
						$ret = "";
					}

				} catch(PDOException $e) {
					$ret = "";
				}
				break;
			case 3:
			 	$sqlstr =func_get_arg(0);
				$sqlstr = str_replace("@",":",$sqlstr);
				$p = func_get_arg(1);
				$v = func_get_arg(2);
			
				try{	
					$stmt = $this->pdo->prepare($sqlstr);
					if (is_array($p)) {
						for($i=0;$i<count($p);$i++){
							$p[$i] = str_replace("@",":",$p[$i]);
							$pcnt = strpos($sqlstr,$p[$i]);
							if (! $pcnt === false){
								$stmt->bindValue($p[$i],$v[$i]);						
							}
						}
						$stmt->execute();	
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						if (is_array($row)) {
							$ret = implode(", ", $row);
						} else {
							$ret = "";
						}
					} else {
						$p = str_replace("@",":",$p);
						$stmt->bindValue($p,$v);
						$stmt->execute();	
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						if (is_array($row)) {
							$ret = implode(", ", $row);
						} else {
							$ret = "";
						}

					}
				} catch(PDOException $e) {
					$ret = 0;
				}
				break;
			default:
				throw new exception("The argument is insufficient.(5~)");
				return NULL;
				break;
		}
		if (! isset($ret)) {$ret="";}
		return $ret;

	}
}
