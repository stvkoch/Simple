<?php
namespace Simple\Model;
/**
* Folk's: - Funciona mais como um muito simples 'sql builder'
* e retorna os resultados encapsulados na classe Result que implementando interator sobre o streaming do resource PDO, de borla 'Result' dá algumas funcoes uteis para paginacao
* Exemplo:
*   $result->count()
*
* \Simple\Model\Base::setWriteHandler( $pdo );
* @author steven koch <steven.koch@co.sapo.pt>
*/

/* *
* @depents \Simple\Config\PHP
* @depents \Simple\Result\Base
* @depents \Simple\Model\Exception\InvalidValue
* @depents \Simple\Model\Exception\Invalid
*/

class Base
{

  protected $resultClassName = '\Simple\Model\Result\Base';

  protected $tableName = '';

  protected $joinsMap = array();

  protected $perPage = 20;

  public static $instance = null;

  public static $handlers = array('ro'=>null,'w'=>null);

  protected $validations_all = array();
  protected $validations_insert = array();
  protected $validations_update = array();


  static private function handler($mode='ro')
  {
    return self::$handlers[$mode];
  }

  static public function setReadOnlyHandler($handler)
  {
    if(is_null(self::$handlers['w']))
      self::$handlers['w'] = $handler;
    return self::$handlers['ro'] = $handler;
  }

  static public function setWriteHandler($handler)
  {
    if(is_null(self::$handlers['ro']))
      self::$handlers['ro'] = $handler;
      return self::$handlers['w'] = $handler;
  }


  static public function instance()
  {
    if(is_null(self::$instance)){
      $className = \get_called_class();
      self::$instance = new $className();
    }
    return self::$instance;
  }


  //Validations--------------------------------------------------------------------------------------
  public function validation( $fields, $validations_all, $action)
  {
    $msgs = array();
    if(!is_array($validations_all)) $validations_all = array($validations_all);
    foreach ($validations_all as $validations)
      foreach ($validations as $key => $call) {
        try {
          $opts = array('fieldName' => $key, 'action' => $action);
          if(isset($fields[$key])) $opts['value'] = $fields[$key];
          if(is_array($call)) foreach ($call as $callItem) {
            $this->callValidation($callItem, $opts);
          }else
            $this->callValidation($call, $opts);
        } catch (\Simple\Model\InvalidValue $e) {
          $msgs[] = $e;
        }

        if(count($msgs)) throw new \Simple\Model\ValidationException($msgs);
      }
    return $msgs;
  }

  protected function callValidation($callName, $opts)
  {
    //detect string
    if(preg_match('@^\s*([^\(|\)]+)?\s*(\(\s*([^\[|\]]*)\s*\))?\s*$@', $callName, $matches))
    {
      if(isset($matches[3])){ 
        $opts['config'] = $matches[3]; 
      }
    //detect array
    }
    elseif(preg_match('@\s*([^\(|\)|\[|\]]+)?\s*(\(\s*\[\s*(.+)\s*\]\s*\))\s*$@', $callName, $matches))
    {
      if(isset($matches[3])){
        $opts['config'] = explode(',',$matches[3]);
      }
    }
    call_user_func($matches[1], $opts);
  }

  //Transform data--------------------------------------------------------------------------------------

  //->insert(array('name'=>'steven', 'role'=>'admin') )
  public function insert($fields)
  {

    $this->validation( $fields, array($this->validations_all, $this->validations_insert), 'new' );

    $sql = $this->_buildSthInsert($fields);
    $sth = self::handler('w')->prepare($sql);
    $this->_bindParams($sth, $this->_buildSthBindParams($fields));
    $result = $sth->execute();
    return $result;
  }

  //->update(array('nome'=>'steven'), 'id = ? AND date > ?', array(1, '14-12-1975'));
  public function update($fields, $where, $values_binds=array())
  {

    $this->validation( $fields, array($this->validations_all, $this->validations_update), 'update' );

    try {
      self::handler('w')->beginTransaction();
        $sth = self::handler('w')->prepare($this->_buildSthUpdate($fields, $where));
        $this->_bindParams($sth, $this->_buildSthBindParams($fields, $values_binds));
        $result = $sth->execute();
      return self::handler('w')->commit();
    } catch (\PDOException $e) {
      self::handler('w')->rollback();
      return false;
    }
  }


  //->delete('id = ? AND date > ?', array(1, '14-12-1975'));
  public function delete($where, $values_binds=array())
  {
    $sth = self::handler('w')->prepare($this->_buildSthDelete($where));
    $this->_bindParams($sth, $this->_buildSthBindParams($values_binds));
    return $sth->execute();
  }

  //User()->transaction(function(){User()->insert(..);User()->update(...);});
  public function transaction($callback)
  {
    try {
      self::handler('w')->beginTransaction();
      $callback();
      return self::handler('w')->commit();
    } catch (\PDOException $e) {
      self::handler('w')->rollback();
      return false;
    }
  }

  public function begin(){
    return self::handler('w')->beginTransaction();
  }
  public function commit(){
    return self::handler('w')->commit();
  }
  public function rollback(){
    self::handler('w')->rollback();
  }






  //Selects--------------------------------------------------------------------------------------


  //->select('name, date', date>?', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  public function select($select='', $where='', $values_binds=array(), $opts=array())
  {
    $sth = self::handler()->prepare($this->_buildSthSelect($select, $where, $opts));
    $this->_bindParams($sth, $this->_buildSthBindParams($values_binds));
    $sth->execute();
    $result = new $this->_resultClassName($sth, $this, $where, $values_binds, $opts);
    return $result;
  }

  //->one('id=?', array(1));
  public function one($where='', $values_binds=array())
  {
    $result = $this->select('*', $where, $values_binds);
    return $result->next();
  }

  //->all('date>?', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  public function all($where='', $values_binds=array(), $opts=array())
  {
    return $this->select('*', $where, $values_binds, $opts);
  }

  //result $this->model->count($this->_where, $this->_values_binds, $this->_opts)
  public function count($where='', $values_binds=array(), $opts=array(), $optsCount=array()){
    $sth = self::handler()->prepare($this->_buildSthSelectCount($where, $opts, $optsCount));
    $sth->execute($values_binds);
    $result = $sth->fetch( \PDO::FETCH_ASSOC );
    return $result['total'];
  }







  //@/*protected*/ publics ------------------------------------------------------------------------------------

  protected function _bindParams($sth, $values_binds){
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
  protected function _buildSthSelectCount($where='', $opts=array(), $optsCount=array()){
    //if(isset($opts['group'])) $optsCount['group']=$opts['group'];
    if(isset($opts['left'])) $optsCount['left']=$opts['left'];
    if(isset($opts['inner'])) $optsCount['inner']=$opts['inner'];
    if(isset($opts['right'])) $optsCount['right']=$opts['right'];
    if(isset($opts['having'])) $optsCount['having']=$opts['having'];
    if(isset($opts['procedure'])) $optsCount['procedure']=$opts['procedure'];
    return $this->_buildSthSelect('COUNT(*) AS total', $where, $optsCount);
  }

  //->all('*', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
  protected function _buildSthSelect($select='', $where=array(), &$opts=array()){
    $sql = 'SELECT '.$select.' FROM '.$this->tableName;
    if(isset($opts['inner'])){
      if(is_array($opts['inner'])){
        foreach ($opts['inner'] as $join){
          $sql .= ' INNER JOIN '.$this->joinsMap[$join];
        }
      }else
        $sql .= ' INNER JOIN '.$this->joinsMap[$opts['inner']];
    }
    if(isset($opts['left'])){
      if(is_array($opts['left'])){
        foreach ($$opts['left'] as $join)
          $sql .= ' LEFT JOIN '.$this->joinsMap[$join];
      }else
        $sql .= ' LEFT JOIN '.$this->joinsMap[$opts['left']];
    }
    if(isset($opts['right'])){
      if(is_array($opts['right'])){
        foreach ($$opts['right'] as $join)
          $sql .= ' RIGHT JOIN '.$this->joinsMap[$join];
      }else
        $sql .= ' RIGHT JOIN '.$this->joinsMap[$opts['right']];
    }
    if($where) $sql .= ' WHERE '.$where;
    if(isset($opts['group'])) $sql .= ' GROUP BY '.$opts['group'];
    if(isset($opts['having'])) $sql .= ' HAVING '.$opts['having'];
    if(isset($opts['order'])) $sql .= ' ORDER BY '.$opts['order'];
    if(isset($opts['page'])){
      if(!isset($opts['offset'])) $opts['offset'] = $this->perPage;
      if($opts['page']>0) $opts['page']--;
     $opts['limit'] = $opts['page'] * $opts['offset'];
    }
    if(isset($opts['limit'])) $sql .= ' LIMIT '.$opts['limit'];
    if(isset($opts['offset'])) $sql .= ' , '.$opts['offset'];
    if(isset($opts['procedure'])) $sql .= ' PROCEDURE '.$opts['procedure'];

    return $sql;
  }

  //->insert(array('name'=>'steven', 'role'=>'admin'))
  protected function _buildSthInsert($fields){
    $keys = array_keys($fields);
    $sql = 'INSERT INTO ' . $this->tableName . ' ('.implode(', ', $keys);
    array_walk($keys, function(&$v){$v=':'.$v;});
    return $sql.') VALUES ('.implode(', ', $keys).')';
    
  }

  //->update(array('nome'=>'steven'), 'id = ? AND date > ?', array(1, '14-12-1975'));
  protected function _buildSthUpdate($fields, $where){
    $sql = 'UPDATE \''.$this->tableName.'\' SET  ';
    foreach ($fields as $key => $value) {
      $sql .= '\''.$key.'\'=:'.$key. ',' ;
    }
    $sql = substr($sql,0,-1) . '  ';

    if($where) $sql .= ' WHERE ' . $where;

    return $sql;
  }

  //->delete('id = ? AND date > ?', array(1, '14-12-1975'));
  protected function _buildSthDelete($where){
    return 'DELETE FROM '.$this->tableName.' WHERE '.$where;
  }

  protected function _buildSthBindParams(){
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
    echo $this->_buildSthInsert(array('name'=>'steven', 'role'=>'admin'));
    echo '<br>';
    var_dump( $this->_buildSthBindParams(array('name'=>'steven', 'role'=>'admin')) );
    echo '<br>';
    echo "//->update(array('nome'=>'steven'), 'id = ? AND date > ?', array(1, '14-12-1975'));<br>";
    echo $this->_buildSthUpdate(array('nome'=>'steven'), 'id = ? AND date > ?');
    echo '<br>';
    var_dump( $this->_buildSthBindParams(array('nome'=>'steven'),  array(1, '14-12-1975') ) );
    echo '<br>';
    echo "//->delete('id = ? AND date > ?', array(1, '14-12-1975'));<br>";
    echo $this->_buildSthDelete('name=?');
    echo '<br>';
    //->select('name, date', date>?', array('14-12-1975'), array('order'=>'id', 'limit'=>1, 'offset'=>12'));
    echo $this->_buildSthSelect();
    echo '<br>';
    echo $this->_buildSthSelectCount();
    echo '<br>';
    echo $this->_buildSthSelect('name, date', 'date>?', array('order'=>'id', 'page'=>2));
    echo '<br>';
    echo $this->_buildSthSelect('name, date', 'date>?', array('order'=>'id', 'page'=>1));
    echo '<br>';
    echo $this->_buildSthSelect('name, date', 'date>?', array('order'=>'id', 'page'=>0, 'inner'=>'role'));
    echo '<br>';
    echo $this->_buildSthSelect('name, date', 'date>?', array('order'=>'id', 'page'=>0, 'inner'=>array('role', 'order')));
    echo '<br>';
    echo $this->_buildSthSelectCount('date>?', array('order'=>'id', 'page'=>0));

    echo "<br>";
    echo Model::DEFAULT_FETCH_TYPE;
    echo "<br>";
    echo Model::handler();
  }
*/
}


