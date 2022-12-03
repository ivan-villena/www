<?php
// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class ele {

  static string $IDE = "ele-";
  static string $EJE = "ele.";

  function __construct(){
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_ele;
    $est = "_$ide";
    if( !isset($api_ele->$est) ) $api_ele->$est = dat::est_ini(DAT_ESQ,"ele{$est}");
    $_dat = $api_ele->$est;
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];
          break;
        }
      }
    }// toda la lista
    elseif( isset($_dat) ){
      $_ = $_dat;
    }
    return $_;
  }

  // {} => "<>"
  static function val( ...$ele ) : string {
    $_ = "";
    $ele_lis = $ele;
    foreach( $ele_lis as &$ele ){
        
      if( is_string($ele) ){
  
        $_ .= $ele;
      }
      else{
        $ele = obj::val_dec($ele,[],'nom');
        // por icono
        if( isset($ele['ico']) ){
          $ico_ide = $ele['ico'];
          unset($ele['ico']);
          $_ .= dat::ico($ico_ide,$ele);
        }
        // por imagen
        elseif( isset($ele['ima']) ){
          $est = explode('.',$ele['ima']);
          unset($ele['ima']);
          array_push($est,!empty($ele['ide'])?$ele['ide']:0,$ele);
          $_ .= arc::ima(...$est);
        }
        // por tipo de valor
        elseif( isset($ele['tip']) ){
          $tip = explode('_',$ele['tip']);
          unset($ele['tip']);
          // valores
          $val = NULL;
          if( isset($ele['val']) ){
            $val = $ele['val'];
            unset($ele['val']);
          }
          // funciones
          $eje = array_shift($tip);
          if( class_exists($cla_ide = $eje) && method_exists($cla_ide,'var') ){

            $_ = $eje::var( empty($tip) ? 'val' : implode('_',$tip), $val, $ele );
          }
          else{
            $_ = "<span class='err' title='no existe el operador $cla_ide'></span>";
          }                    
        }
        // por etiqueta
        else{
          $_ .= ele::eti($ele);
        }
      }
    }
    return $_;
  }// "<>" => {}
  static function val_dec( string | array $ele, array | object $dat = NULL ) : array {
    $_ = $ele;
    // convierto "" => []
    if( is_string($ele) ){
      $_ = obj::val_dec($ele,$dat,'nom');
    }
    // convierto {} => []
    elseif( is_object($ele) ){
      $_ = obj::nom($ele);
    }
    // proceso atributos con variables : ()($)nom()
    elseif( is_array($_) && isset($dat) ){

      foreach( $_ as &$atr ){

        if( is_string($atr) ){ // && preg_match("/\(\)\(\$\).*\(\)/",$atr) 

          $atr = obj::val($dat,$atr);            
        }
      }
    }
    return $_;
  }// combino elementos
  static function val_jun( string | array $ele, array $lis, array $ope = [] ) : array {
    // proceso opciones
    $dat = isset($ope['dat']) ? $ope['dat'] : NULL;
    // si es "", convierto a []
    $_ = ele::val_dec($ele,$dat);
    // recorro 2ºs elementos
    foreach( lis::val($lis) as $ele ){
      // recorro atributos
      foreach( ele::val_dec($ele,$dat) as $atr => $val ){
        // agrego
        if( !isset($_[$atr]) ){
          $_[$atr] = $val;
        }
        // actualizo
        else{
          switch($atr){
          case 'onclick':   ele::eje($_,'cli',$val); break;
          case 'onchange':  ele::eje($_,'cam',$val); break;
          case 'oninput':   ele::eje($_,'inp',$val); break;
          case 'class':     ele::cla($_,$val); break;// agrego con separador: " "
          case 'style':     ele::css($_,$val); break;// agrego con separador: ";"
          default:          $_[$atr] = $val; break;// reemplazo
          }
        }
      }
    }
    return $_;
  }

  // armo etiqueta : <eti atr="val" >...htm</eti>
  static function eti( array $ele ) : string {
    $_ = "";
    $eti = 'span';
    if( isset($ele['eti']) ){
      $eti = $ele['eti'];
      unset($ele['eti']);
    }
    $htm = '';
    if( isset($ele['htm']) ){
      $htm = $ele['htm'];
      unset($ele['htm']);
    }
    if( !is_string($htm) ){
      $htm_lis = "";
      foreach( lis::val($htm) as $e ){ $htm_lis .= is_string($e) ? $e : ele::val($e); }
      $htm = $htm_lis;
    }
    $_ = "
    <{$eti}".ele::atr($ele).">
      ".( !in_array($eti,['input','img','br','hr']) ? "{$htm}
    </{$eti}>" : '' );
    return $_;
  }
  // devuelvo atributos : "< ...atr="">"
  static function atr( array $ele, array | object $dat = NULL ) : string {
    $_ = '';
    if( isset($dat) ){
      $dat_arr = is_array($dat);
      foreach( $ele as $i=>$v ){ 
        $tex=[];
        foreach( explode(' ',$v) as $pal ){ 
          $let=[];
          foreach( explode('()',$pal) as $cad ){ 
            $res = $cad;
            if( substr($cad,0,3)=='($)' ){ 
              $atr = substr($cad,3);
              if( $dat_arr ){
                if( isset($dat[$atr]) ){ $res = $dat[$atr]; }
              }else{
                if( isset($dat->$atr) ){ $res = $dat->$atr; }
              }
            }$let[] = $res;
          }$tex[] = implode('',$let);
        }// junto por espacios
        $_.=" {$i} = \"".str_replace('"','\'',implode(' ',$tex))."\"";
      }
    }
    else{
      foreach( $ele as $i=>$v ){
        $_ .= " {$i} = \"".str_replace('"','\'',strval($v))."\"";
      }
    }
    return $_;
  }
  // contenido: htm + htm_ini + htm_med + htm_fin
  static function htm( array &$ele ) : array {
    $_=[];
    foreach( ['htm','htm_ini','htm_med','htm_fin'] as $tip ){
      if( isset($ele[$tip]) ){
        if( is_string($ele[$tip]) ){
          $_[$tip] = $ele[$tip];
        }else{
          $_[$tip] = ele::val($ele[$tip]);
        }
        unset($ele[$tip]);
      }
    }
    return $_;
  }

  // ejecuciones
  static function eje( array &$ele, string $ide, string $val = NULL, ...$opc ) : array {
    $_ = $ele;
    $_eve = [ 
      'cli'=>"onclick",
      'cam'=>"onchange",
      'inp'=>"oninput",
      'foc'=>"onfocus",
      'hov'=>"onhover",
      'tec'=>"onkeypress"
    ];
    if( isset($_eve[$ide]) ){

      $ide = $_eve[$ide];

      if( !isset($val) ){

        $_ = [];

        if( isset($ele[$ide]) ){
          $_ = explode(';',$ele[$ide]);
        }

      }// operaciones por valor
      else{
        // eliminar
        if( in_array('eli',$opc) ){

        }// modificar
        elseif( in_array('mod',$opc) ){

        }// agregar
        else{
          if( in_array('ini',$opc) ){
            $ele[$ide] = $val.( !empty($ele[$ide]) ? ' '.$ele[$ide] : '' );
          }
          elseif( !empty($ele[$ide]) ){
            $ele[$ide] .= " ".$val;
          }
          else{
            $ele[$ide] = $val;
          }
        }
        $_ = $ele;
      }
    }
    return $_;
  }
  // clases
  static function cla( array &$ele, mixed $val = NULL, ...$opc ) : array {
    $_ = $ele;
    if( !isset($val) ){

      $_ = [];
  
      $ele = obj::val_dec($ele,[],'nom');
  
      if( isset($ele['class']) ){
  
        foreach( explode(' ',$ele['class']) as $val ){ 
  
          if( !empty($val) ) $_[] = trim($val);
        }
      }
    }// operaciones
    else{
      
      if( in_array('eli',$opc) ){

        foreach( lis::val($val) as $v ){
          
        }    
      }
      elseif( in_array('mod',$opc) ){

        if( is_array($val) ){

        }
      }
      else{
        if( !isset($ele['class']) ) $ele['class']='';
  
        if( in_array('ini',$opc) ){

          $ele['class'] = $val.( !empty($ele['class']) ? " {$ele['class']}" : "" );
        }// agrego
        elseif( !empty($ele['class']) ){ 
          $ele['class'] .= " ".$val; 
        }// inicializo
        else{
          $ele['class'] = $val; 
        }
      }
      $_ = $ele;
    }
    return $_;
  }
  // estilos
  static function css( array &$ele, mixed $val = NULL, ...$opc ) : array {
    $_ = $ele;
    // listado
    if( !isset($val) ){

      $_ = [];

      if( isset($ele['style']) ){

        foreach( explode(';',$ele['style']) as $art ){

          if( !empty($art) ){

            $val = explode(':',$art);

            $_[ trim($val[0]) ] = trim($val[1]);
          }
        }
      }
    }// operaciones
    else{        
      // por atributos
      if( is_array($val) ){

        $css = ele::css($ele);

        if( in_array('eli',$opc) ){

          foreach( $val as $v ){

            if( isset($css[$v]) ) unset($css[$v]);
          }
        }// agrego, actualizo o modifico
        else{
          foreach( $val as $i => $v ){

            if( isset($css[$i]) && in_array('mod',$opc) ){
              $css[$i] .= $v;
            }
            else{
              $css[$i] = $v;
            }
          }
        }
        $css_val = [];
        foreach( $css as $i => $v ){

          $css_val []= "{$i} : {$v}";
        }

        $ele['style'] = implode('; ',$css_val);
      }
      // por texto
      else{

        if( in_array('eli',$opc) ){

        }
        else{

          if( in_array('ini',$opc) ){
            $ele['style'] = $val.( !empty($ele['style']) ? " {$ele['style']}" : "" );
          }
          elseif( !empty($ele['style']) ){
            $ele['style'] .= " ".$val;
          }else{
            $ele['style'] = $val;
          }
        }
      }
      $_ = $ele;
    }
    return $_;
  }// - fondo
  static function css_fon( string $dir, array $ope=[] ) : string {
    if( empty($ope['tip']) ) $ope['tip']='png';
    if( empty($ope['ali']) ) $ope['ali']='center';
    if( empty($ope['tam']) ) $ope['tam']='contain';
    if( empty($ope['rep']) ) $ope['rep']='no-repeat';
    return "background: {$ope['rep']} {$ope['ali']}/{$ope['tam']} url('{$dir}.{$ope['tip']}');";
  }

  
}