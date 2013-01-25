<?php
namespace Simple\Model\Result;
/**
*   $artigos = Articles::get_list( $_GET['page'] )
*
*   $artigos->currentPage()
*   $artigos->totalPages()
*   $artigos->hasNextPage()
*   $artigos->hasPreviousPage()
*
*   foreach( $artigos as $artigo ){
*     
*   }
*   @author steven koch <steven.koch@co.sapo.pt>
* 
*   @depents \Simple\Model\Model
*/
class Result implements \Iterator
{
  
  const DEFAULT_FETCH_TYPE = \PDO::FETCH_ASSOC;//PDO::FETCH_OBJ;

  protected $_sth;
  protected $_model;
  protected $_where;
  protected $_valuesBinds;
  protected $_opts;
  protected $_i=0;
  protected $_row;
  protected $_count=null;

  //return new Result($sth->execute($valuesBinds), $this, $where, $valuesBinds, $opts);
  public function __construct(&$sth, \Simple\Model\Model $model, $where, $values_binds=array(), $opts=array())
  {
    $this->_sth=$sth;
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
    $this->_row = $this->_sth->fetch( self::DEFAULT_FETCH_TYPE);
    $this->_i++;
    return $this->_row;
  }
  public function valid() 
  {
    return $this->_row;
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