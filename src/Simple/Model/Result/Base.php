<?php
namespace Simple\Model\Result;
/**
*   $articlesModel = new Articles();
*   $articles = $articlesModel->get_list( $_GET['page'] )
*
*   $articles->currentPage()
*   $articles->totalPages()
*   $articles->hasNextPage()
*   $articles->hasPreviousPage()
*
*   foreach( $articles as $article ){
*     $articles->getUserById($artigo->userId);
* 
*   }
*   @author steven koch <steven.koch@co.sapo.pt>
*
*   @depents \Simple\Model\Base
*/
class Base implements \Iterator
{

  protected $_sth;
  protected $_model;
  protected $_where;
  protected $_valuesBinds;
  protected $_opts;
  protected $_i=0;
  protected $_row;
  protected $_count=null;



  //return new Result($sth->execute($valuesBinds), $this, $where, $valuesBinds, $opts);
  public function __construct(&$sth, \Simple\Model\Base $model, $where, $values_binds=array(), $opts=array())
  {
    $this->_sth=$sth;
    $this->_sth->setFetchMode(PDO::FETCH_CLASS, get_class($model));
    $this->_model=$model;
    $this->_where=$where;
    $this->_valuesBinds=$values_binds;
    $this->_opts=$opts;

    $this->_init();
  }

  //your stuff initializations
  protected function _init(){}


  //public function count($where='', $valuesBinds=array(), $opts=array(), $optsCount=array()){
  public function count()
  {
    if($this->_count==null)
      $this->_count=$this->_model->count($this->_where, $this->_valuesBinds, $this->_opts);//from sql builder
    return $this->_count;
  }

  //iterator
  public function rewind(){
    $this->_i = 0;
    return $this->next();
  }
  public function current()
  {
    return $this->_row;
  }
  public function key()
  {
    return $this->_i;
  }
  public function next()
  {
    $this->_row = $this->_sth->fetch();
    $this->_i++;
    return $this->_row;
  }
  public function valid()
  {
    return $this->_row;
  }

  public function model()
  {
    return $this->_model;
  }

  public function getTableName()
  {
    return $this->tableName;
  }

  public function __call($callName, $args)
  {
    return call_user_func_array(array($this->_model, $callName), $args);
  }


  //@paginator stuffs
  public function previousPage(){
    return ($this->hasPreviousPage())? $this->currentPage()-1 : $this->currentPage();
  }
  public function nextPage(){
    return ($this->hasNextPage()) ? $this->currentPage()+1 : $this->currentPage();
  }
  public function hasPreviousPage(){
    return ($this->currentPage()>1);
  }
  public function hasNextPage(){
    return ($this->totalPages() > $this->currentPage());
  }
  public function currentPage(){
    return $this->_opts['limit']/$this->_opts['offset']+1;
  }
  public function totalPages(){
    $total = $this->count()/$this->_opts['offset'];
    return (!$total)? 1 : ceil($total);
  }



}
