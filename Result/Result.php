<?php
namespace Simple\Result;
/**
*   $artigos = Articles::get_list( $_GET['page'] )
*
*   $artigos->current_page()
*   $artigos->total_pages()
*   $artigos->has_next_page()
*   $artigos->has_previous_page()
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
  protected $_values_binds;
  protected $_opts;
  protected $_i=0;
  protected $_row;
  protected $_count=null;

  //return new Result($sth->execute($values_binds), $this, $where, $values_binds, $opts);
  public function __construct(&$sth, \Simple\Model\Model $model, $where, $values_binds=array(), $opts=array())
  {
    $this->_sth=$sth;
    $this->_model=$model;
    $this->_where=$where;
    $this->_values_binds=$values_binds;
    $this->_opts=$opts;

    $this->_init();
  }

  protected function _init(){}
  

  //public function count($where='', $values_binds=array(), $opts=array(), $optsCount=array()){
  public function count()
  {
    if($this->_count==null)
      $this->_count=$this->_model->count($this->_where, $this->_values_binds, $this->_opts);//from sql builder
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
  public function previous_page(){
    return ($this->has_previous_page())? $this->current_page()-1 : $this->current_page();
  }
  public function next_page(){
    return ($this->has_next_page()) ? $this->current_page()+1 : $this->current_page();
  }
  public function has_previous_page(){
    return ($this->current_page()>1);
  }
  public function has_next_page(){
    return ($this->total_pages() > $this->current_page());
  }
  public function current_page(){
    return $this->_opts['limit']/$this->_opts['offset']+1;
  }
  public function total_pages(){
    $total = $this->count()/$this->_opts['offset'];
    return (!$total)? 1 : ceil($total);
  }
  
}