<?php
// Ejecucion : ( ...par ) => { ...cod } : val 
class _eje {

  // ejecucion del entorno : funcion() |o| [namespace/]clase(...par).objeto->método(...par)
  static function val( mixed $ide, mixed $par=[], array $ini=[] ) : mixed {
    // php.fun(,)par(,)... || rec/cla.met(,)par(,)...      
    if( is_string($ide) ){

      if( preg_match("/^\[.+\]$/",$ide) ){
        // FALSE : convierto en objetos stdClass
        $var_eve = _obj::dec($ide);
        $ide = $var_eve[0];
        if( isset($var_eve[1]) ) $par = $var_eve[1];
      }
    }// por codificacion
    else{
      $ope = explode('-',$ide);
      $var_eve = explode('.',$ope[0]);
      $ide = empty($var_eve[1]) ? $var_eve[0] : "{$var_eve[0]}.{$var_eve[1]}";
      if( !empty($ope[1]) ) array_unshift($par,$ope[1]);
    }
    // metodos de clase
    if( preg_match("/\./",$ide) || preg_match("/::/",$ide) ){
      $_ = _eje::met( $ide, $par, $ini );
    }
    // funcion del entorno
    else{
      $_ = _eje::fun( $ide, ..._lis::ite($par) );      
    }
    return $_;
  }
  // ejecuto funciones
  static function fun( string $ide, ...$par ) : mixed {
    $_=FALSE;
    if( function_exists($ide) ){
      $_ = empty($par) ? $ide() : $ide( ...$par );
    }else{ 
      $_ = ['_err'=>"{-_-}.err: No existe la función '{$ide}' en el entorno php..."];
    }
    return $_;
  }
  // instancio objeto de clase
  static function cla( string $ide, ...$ini ) : object | array {

    $_ide = explode('/',$ide);

    if( isset($_ide[1]) ){
      $dir = $_ide[0];
      $ide = $_ide[1];
    }
    else{
      $dir = 'php';
      $ide = $_ide[0];
    }
    if( is_string($ide) && file_exists($rec = "{$dir}/".substr($ide,1).".php") ){ 

      $val = explode('/',$ide);

      if( !class_exists( $cla = array_pop($val) ) ){

        require_once $rec; 
      }
      // instancio
      if( class_exists($cla) ){

        $_ = empty($ini) ? new $cla() : new $cla( ...$ini );
      }
      else{ 
        $_ = ['_err'=>"{-_-}.err: No existe la clase solicitada en el directorio '{$ide}'..."];
      }
    }
    else{ 
      $_ = ['_err'=>"{-_-}.err: No existe el directorio '{$ide}' en este servidor..."];
    }  
    return $_;
  }
  // ejecuto método de clase
  static function met( string $ide, mixed $par=[], array $ini=[] ) : mixed {
    $_ = FALSE;
    // por clase
    if( preg_match("/::/",$ide) ){
      $_ide = explode('::',$ide);
      $cla = $_ide[0];
      $met = $_ide[1];
      
      if( class_exists($cla) ){       

        if( in_array($met,get_class_methods($cla)) ){

          try{

            $_ = empty($par) ? $cla::$met() : $cla::$met( ..._lis::ite($par) ) ;
          }
          catch( Exception $e ){

            $_ = ['_err' => $e->getMessage()];
          }
        }
        else{
          $_ = ['_err'=>"{-_-}.err: No existe el método estático '$met' de la clase '$cla'..."];
        }        
      }
      else{
        $_ = ['_err'=>"{-_-}.err: No existe la clase '$cla'..."];
      }
    }// por objeto
    else{
      $_ide = explode('.',$ide);
      $cla = $_ide[0];
      $met = $_ide[1];
      // instancio
      $obj = _eje::cla( $cla, ...$ini );
      // ejecuto      
      if( is_object($obj) ){

        if( method_exists($obj, $met) ){ 

          try{

            $_ = empty($par) ? $obj->$met() : $obj->$met( ..._lis::ite($par) ) ;
          }
          catch( Exception $e ){

            $_ = ['_err' => $e->getMessage()];
          }
        }
        else{
          $_ = ['_err'=>"{-_-}.err: No existe el método '$met' en el objeto '$cla'..."];
        }
      }else{
        $_ = [ '_err'=>$obj ];
      }
    }
    return $_;
  }
}