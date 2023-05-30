<?php
// Ejecucion : ( ...par ) => { ...cod } : val 
class Eje {

  // ejecucion del entorno : funcion() |o| [namespace/]clase(...par).objeto->método(...par)
  static function val( mixed $ide, mixed $par=[], array $ini=[] ) : mixed {

    // php.fun(,)par(,)... || rec/cla.met(,)par(,)...      
    if( is_string($ide) ){

      if( preg_match("/^\[.+\]$/",$ide) ){
        // FALSE : convierto en objetos stdClass
        $var_eve = Obj::val_dec($ide);
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
      
      $_ = Eje::met( $ide, $par, $ini );
    }
    // funcion del entorno
    else{
      
      $_ = Eje::fun( $ide, ...Obj::pos_ite($par) );      
    }
    return $_;
  } 

  // ejecuto funciones
  static function fun( string $ide, ...$par ) : mixed {
    
    if( function_exists($ide) ){

      $_ = empty($par) ? $ide() : $ide( ...$par );
    }
    else{ 
      $_ = "<p class='err'>{-_-}.err: No existe la función '{$ide}' en el entorno global...</p>";
    }

    return $_;
  }

  // ejecuto método de clase
  static function met( string $ide, mixed $par=[], array $ini=[] ) : mixed {
    $_ = FALSE;

    // tipo de uso
    $sep = preg_match("/::/",$ide) ? "::" : ".";
    $_ide = explode($sep,$ide);
    $cla = str_replace("$",'',$_ide[0]);
    $met = $_ide[1];

    // busco clase
    $_cla = Eje::cla_val( $cla );
    $err = !empty($_cla['dir']) ? " en el directorio '{$_cla['dir']}'" : "";
    $cla = $_cla['ide'];

    if( $cla ){

      if( in_array($met, get_class_methods($cla)) ){

        if( $sep == "::" ){

          try{

            $_ = empty($par) ? $cla::$met() : $cla::$met( ...Obj::pos_ite($par) ) ;
          }
          catch( Exception $e ){

            $_ = "<p class='err'>{-_-}.err: {$e->getMessage()}</p>";
          }
        }
        else{

          $obj = "";
          // objeto existente 
          if( preg_match("/\$/",$ide) ){

            if( isset($GLOBALS[ $cla ]) ){

              $obj = $GLOBALS[ $cla ];
            }
            else{

              $obj = "No existe el objeto '$cla' en el entorno actual...";
            }

          }// instancio
          else{

            $obj = Eje::cla( $cla, ...$ini );
          }

          // ejecuto      
          if( is_object($obj) ){

            try{

              $_ = empty($par) ? $obj->$met() : $obj->$met( ...Obj::pos_ite($par) ) ;
            }
            catch( Exception $e ){

              $_ = "<p class='err'>{-_-}.err: {$e->getMessage()}</p>";
            }
          }
          else{
            $_ = "<p class='err'>{-_-}.err: $obj</p>";
          }
        }
      }
      else{
        $_ = "<p class='err'>{-_-}.err: No existe el método '{$met}' de la clase '{$cla}'{$err}...</p>";
      }
    }
    else{
      $_ = "<p class='err'>{-_-}.err: No existe la clase '{$cla}'{$err}...</p>";
    }

    return $_;
  }

  // instancio objeto de clase
  static function cla( string $ide, ...$ini ) : object | array {

    $_ide = explode('/',$ide);
    $dir = './';

    if( isset($_ide[1]) ){
      $ide = array_pop($_ide); 
      $dir .= implode('_',$_ide)."/";
    }
    else{      
      $ide = $_ide[0];
    }

    if( is_string($ide) && file_exists($rec = "{$dir}{$ide}.php") ){ 

      if( !class_exists($ide) ){

        require_once $rec; 
      }

      // instancio
      if( class_exists($ide) ){

        $_ = empty($ini) ? new $ide() : new $ide( ...$ini );
      }
      else{ 
        $_ = "<p class='err'>{-_-}.err: No existe la clase solicitada en el directorio '{$ide}'...</p>";
      }
    }
    else{ 
      $_ = "<p class='err'>{-_-}.err: No existe el directorio '{$ide}' en este servidor...</p>";
    }  
    return $_;
  }// valido existencia de clase, y busco archivo de require para declararla
  static function cla_val( string $ide ) : array {

    $_ = [ 
      'dir' => "",
      'ide' => $ide
    ];

    // busco clase
    if( preg_match("/\//",$ide) ){
      $dir = explode('/',$ide);
      $_['ide'] = array_pop($dir);
      $_['dir'] = implode('/',$dir)."/";
    }

    // nomenclatura: primer letra mayuscula
    $_['ide'] = Tex::let_pal($_['ide']);

    // valido clase y archivo; si existe, declaro la clase...
    if( class_exists($_['ide']) || file_exists($rec = "./{$_['dir']}".str_replace('_','/',$_['ide']).".php") ){

      if( !class_exists($_['ide']) ){
        
        require_once( $rec );
      }      
    }
    // no encuentro clase ni archivo
    else{
      $_['ide'] = FALSE;
    }

    return $_;
  }

  // devuelvo contenido html
  static function htm( string $arc, ...$cla ) : string {  
    $_ = "";

    // cargo clases necesarias
    if( !empty($cla) ){

      foreach( $cla as $cla_arc ){

        Eje::cla_val($cla_arc);
      }
    }

    // cargo contenido
    if( !empty( $rec = Arc::val_rec($arc) ) ){
      
      ob_start();

      include( $rec );

      $_ = ob_get_clean();
    }

    return $_;
  }  
  
}