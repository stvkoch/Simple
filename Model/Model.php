<?php
namespace Simple\Model;
/**
* Folk's: - Funciona mais como um muito simples 'sql builder'
* e retorna os resultados encapsulados na classe Result que implementando interator sobre o streaming do resource PDO, de borla 'Result' dá algumas funcoes uteis para paginacao
* Exemplo:
*   $result->count()
*
* 
* @author steven koch <steven.koch@co.sapo.pt>
*/

/* *
* @depents \Simple\Result\Result
* @depents \Simple\Config\Config
*/

class Model
{

  protected $resultClassName = '\Simple\Result\Result';

  public $table_name = '';


  public $joins_map = array();


  static private function handler(){
    return \Simple\Config\Config::get('Model', 'handler');
  }
  public function __invoke()
  {
    return self::handler();
  }





  //Transform data--------------------------------------------------------------------------------------


  //->insert(array('name'=>'steven', 'role'=>'admin'))
  public function insert($fields){
    $sql = $this->_build_sth_sql_insert($fields);
    $sth = self::handler()->prepare($sql);
    $this->_bind_params($sth, $this->_build_sth_bind_params($fields));
    $result = $sth->execute();
    return $result;
  }

  //->update(array('nome'=>'steven'), 'id = ? AND date > ?', array(1, '14-12-1975'));
  public function update($fields, $where, $values_binds=array()){
    if(count($fields)==0) return false;
    try {
      self::handler()->beginTransaction();
        $sth = self::handler()->prepare($this->_build_sth_sql_update($fields, $where));
        $this->_bind_params($sth, $this->_build_sth_bind_params($fields, $values_binds));
        $result = $sth->execute();
      return self::handler()->commit();
    } catch (\PDOException $e) {
      self::handler()->rollback();
      return false;
    }
  }


  //->delete('id = ? AND date > ?', array(1, '14-12-1975'));
  public function delete($where, $values_binds=array()){
    $sth = self::handler()->prepare($this->_build_sth_sql_delete($where));
    $this->_bind_params($sth, $this->_build_sth_bind_params($values_binds));
    return $sth->execute();
  }

  //User()->transaction(function(){User()->insert(..);User()->update(...);});
  public function transaction($callback){
    try {
      self::handler()->beginTransaction();
      $callback();
      return self::handler()->commit();
    } catch (\PDOException $e) {
      self::handler()->rollback();
      return false;
    }
  }






  //Selects--------------------------------------------------------------------------------------


  //->select('name, date', date>?', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  public function select($select='', $where='', $values_binds=array(), $opts=array()){
//var_dump($this->_build_sth_sql_select($select, $where, $opts));
    $sth = self::handler()->prepare($this->_build_sth_sql_select($select, $where, $opts));
    $this->_bind_params($sth, $this->_build_sth_bind_params($values_binds));
    $sth->execute();
    return new $this->_resultClassName ($sth, $this, $where, $values_binds, $opts);
  }

  //->one('id=?', array(1));
  public function one($where='', $values_binds=array()){
    $result = $this->select('*', $where, $values_binds);
    return $result->next();
  }

  //->all('date>?', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  public function all($where='', $values_binds=array(), $opts=array()){
    return $this->select('*', $where, $values_binds, $opts);
  }

  //result $this->model->count($this->_where, $this->_values_binds, $this->_opts)
  public function count($where='', $values_binds=array(), $opts=array(), $optsCount=array()){
    $sth = self::handler()->prepare($this->_build_sth_sql_select_count($where, $opts, $optsCount));
    $sth->execute($values_binds);
    $result = $sth->fetch( \PDO::FETCH_ASSOC );
//var_dump($this->_build_sth_sql_select_count($where, $opts, $optsCount), $values_binds);
    return $result['total'];
  }







  //@protecteds ------------------------------------------------------------------------------------

  protected function _bind_params($sth, $values_binds){
    foreach ($values_binds as $key => $value) {
      if(is_int($key)){
        
        $sth->bindValue($key+1, $value);
      }else{

        $sth->bindValue($key, $value);
      }
    }
    return $sth;
  }

  //->all('COUNT(*)', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  protected function _build_sth_sql_select_count($where='', $opts=array(), $optsCount=array()){
    //if(isset($opts['group'])) $optsCount['group']=$opts['group'];
    if(isset($opts['having'])) $optsCount['having']=$opts['having'];
    if(isset($opts['procedure'])) $optsCount['procedure']=$opts['procedure'];
    return $this->_build_sth_sql_select('COUNT(*) AS total', $where, $optsCount);
  }

  //->all('*', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  protected function _build_sth_sql_select($select='', $where=array(), &$opts=array()){
    $sql = 'SELECT '.$select.' FROM '.$this->table_name;
    if(isset($opts['inner'])){
      if(is_array($opts['inner'])){
        foreach ($opts['inner'] as $join){
          $sql .= ' INNER JOIN '.$this->joins_map[$join];
        }
      }else
        $sql .= ' INNER JOIN '.$this->joins_map[$opts['inner']];
    }
    if(isset($opts['left'])){
      if(is_array($opts['left'])){
        foreach ($$opts['inner'] as $join)
          $sql .= ' LEFT JOIN '.$this->joins_map[$join];
      }else
        $sql .= ' LEFT JOIN '.$this->joins_map[$opts['left']];
    }
    if(isset($opts['right'])){
      if(is_array($opts['right'])){
        foreach ($$opts['right'] as $join)
          $sql .= ' RIGHT JOIN '.$this->joins_map[$join];
      }else
        $sql .= ' RIGHT JOIN '.$this->joins_map[$opts['right']];
    }
    if($where) $sql .= ' WHERE '.$where;
    if(isset($opts['group'])) $sql .= ' GROUP BY '.$opts['group'];
    if(isset($opts['having'])) $sql .= ' HAVING '.$opts['having'];
    if(isset($opts['order'])) $sql .= ' ORDER BY '.$opts['order'];
    if(isset($opts['page'])){
      if(!isset($opts['offset'])) $opts['offset'] = (isset($this->per_page)) ? $this->per_page : \Simple\Config\Config::get('Model', 'per_page', 10);
      if($opts['page']>0) $opts['page']--;
     $opts['limit'] = $opts['page'] * $opts['offset'];
    }
    if(isset($opts['limit'])) $sql .= ' LIMIT '.$opts['limit'];
    if(isset($opts['offset'])) $sql .= ' , '.$opts['offset'];
    if(isset($opts['procedure'])) $sql .= ' PROCEDURE '.$opts['procedure'];

    return $sql;
  }

  //->insert(array('name'=>'steven', 'role'=>'admin'))
  protected function _build_sth_sql_insert($fields){
    $keys = array_keys($fields);
    $sql = 'INSERT INTO ' . $this->table_name . ' ('.implode(', ', $keys);
    array_walk($keys, function(&$v){$v=':'.$v;});
    return $sql.') VALUES ('.implode(', ', $keys).')';
    
  }

  //->update(array('nome'=>'steven'), 'id = ? AND date > ?', array(1, '14-12-1975'));
  protected function _build_sth_sql_update($fields, $where){
    $sql = 'UPDATE \''.$this->table_name.'\' SET  ';
    foreach ($fields as $key => $value) {
      $sql .= '\''.$key.'\'=:'.$key. ',' ;
    }
    $sql = substr($sql,0,-1) . '  ';

    if($where) $sql .= ' WHERE ' . $where;

    return $sql;
  }

  //->delete('id = ? AND date > ?', array(1, '14-12-1975'));
  protected function _build_sth_sql_delete($where){
    return 'DELETE FROM '.$this->table_name.' WHERE '.$where;
  }

  protected function _build_sth_bind_params(){
    $args = func_get_args();
    $fields_binds = array();
    foreach ($args as $arg) {
      foreach ($arg as $key => $value){
        if(is_int($key))
          $fields_binds[$key]=(strlen($value)==0 && !is_bool($value))? 'NULL' : $value;
        else
          $fields_binds[':'.$key]=(strlen($value)==0 && !is_bool($value))? 'NULL' : $value;
      }
    }
    return $fields_binds;
  }



  //@basic tests, show queries
 /*
  function test(){
    echo "//->insert(array('name'=>'steven', 'role'=>'admin'));<br>";
    echo $this->_build_sth_sql_insert(array('name'=>'steven', 'role'=>'admin'));
    echo '<br>';
    var_dump( $this->_build_sth_bind_params(array('name'=>'steven', 'role'=>'admin')) );
    echo '<br>';
    echo "//->update(array('nome'=>'steven'), 'id = ? AND date > ?', array(1, '14-12-1975'));<br>";
    echo $this->_build_sth_sql_update(array('nome'=>'steven'), 'id = ? AND date > ?');
    echo '<br>';
    var_dump( $this->_build_sth_bind_params(array('nome'=>'steven'),  array(1, '14-12-1975') ) );
    echo '<br>';
    echo "//->delete('id = ? AND date > ?', array(1, '14-12-1975'));<br>";
    echo $this->_build_sth_sql_delete('name=?');
    echo '<br>';
    //->select('name, date', date>?', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
    echo $this->_build_sth_sql_select();
    echo '<br>';
    echo $this->_build_sth_sql_select_count();
    echo '<br>';
    echo $this->_build_sth_sql_select('name, date', 'date>?', array('order'=>'id', 'page'=>2));
    echo '<br>';
    echo $this->_build_sth_sql_select('name, date', 'date>?', array('order'=>'id', 'page'=>1));
    echo '<br>';
    echo $this->_build_sth_sql_select('name, date', 'date>?', array('order'=>'id', 'page'=>0, 'inner'=>'role'));
    echo '<br>';
    echo $this->_build_sth_sql_select('name, date', 'date>?', array('order'=>'id', 'page'=>0, 'inner'=>array('role', 'order')));
    echo '<br>';
    echo $this->_build_sth_sql_select_count('date>?', array('order'=>'id', 'page'=>0));

    echo "<br>";
    echo Model::DEFAULT_FETCH_TYPE;
    echo "<br>";
    echo Model::handler();
  }
*/
}

