<?php

// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class api_ele {

  // {} => "<>"
  static function val( ...$ele ) : string {
    $_ = "";
    foreach( $ele as $ele ){
        
      if( is_string($ele) ){
  
        $_ .= $ele;
      }
      else{
        $ele = api_obj::dec($ele,[],'nom');
        // operador
        if( isset($ele['_let']) ){
          $htm = $ele['_let'];
          unset($ele['_let']);
          $_ .= app::let($htm,$ele);
        }
        // por icono
        elseif( isset($ele['ico']) ){
          $_ .= app::ico($ele['ico'],$ele);
        }
        // por imagen
        elseif( isset($ele['ima']) ){
          $est = explode('.',$ele['ima']);
          array_push($est,!empty($ele['ide'])?$ele['ide']:0,$ele);
          $_ .= app::ima(...$est);
        }
        // por tipo de valor
        elseif( isset($ele['_tip']) ){
          $tip = explode('_',$ele['_tip']);
          unset($ele['_tip']);
          // valores
          $val = NULL;
          if( isset($ele['val']) ){
            $val = $ele['val'];
            unset($ele['val']);
          }
          // funciones
          $eje = array_shift($tip);
          if( class_exists($cla_ide = "app_var") && method_exists($cla_ide,$eje) ){

            $_ = $cla_ide::$eje( empty($tip) ? 'val' : implode('_',$tip), $val, $ele );
          }
          else{
            $_ = "<span class='err' title='no existe el operador $cla_ide'></span>";
          }                    
        }
        // por etiqueta
        else{
          $_ .= api_ele::eti($ele);
        }
      }
    }
    return $_;
  }
  // "<>" => {}
  static function dec( string | array $ele, array | object $dat = NULL ) : array {
    $_ = $ele;
    // convierto "" => []
    if( is_string($ele) ){
      $_ = api_obj::dec($ele,$dat,'nom');
    }
    // convierto {} => []
    elseif( is_object($ele) ){
      $_ = api_obj::nom($ele);
    }
    // proceso atributos con variables : ()($)nom()
    elseif( is_array($_) && isset($dat) ){

      foreach( $_ as &$atr ){

        if( is_string($atr) ){ // && preg_match("/\(\)\(\$\).*\(\)/",$atr) 

          $atr = api_obj::val($dat,$atr);            
        }
      }
    }
    return $_;
  }
  // combino elementos
  static function jun( string | array $ele, array $lis, array $ope = [] ) : array {
    // proceso opciones
    $dat = isset($ope['dat']) ? $ope['dat'] : NULL;
    // si es "", convierto a []
    $_ = api_ele::dec($ele,$dat);
    // recorro 2ºs elementos
    foreach( api_lis::ite($lis) as $ele ){
      // recorro atributos
      foreach( api_ele::dec($ele,$dat) as $atr => $val ){
        // agrego
        if( !isset($_[$atr]) ){
          $_[$atr] = $val;
        }
        // actualizo
        else{
          switch($atr){
          case 'onclick':   api_ele::eje($_,'cli',$val); break;
          case 'onchange':  api_ele::eje($_,'cam',$val); break;
          case 'oninput':   api_ele::eje($_,'inp',$val); break;
          case 'class':     api_ele::cla($_,$val); break;// agrego con separador: " "
          case 'style':     api_ele::css($_,$val); break;// agrego con separador: ";"
          default:          $_[$atr] = $val; break;// reemplazo
          }
        }
      }
    }
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
      $_htm = "";
      foreach( api_lis::ite($htm) as $ele ){
        $_htm .= is_string($ele) ? $ele : api_ele::val($ele);
      }
      $htm = $_htm;
    }
    $_ = "
    <{$eti}".api_ele::atr($ele).">
      ".( !in_array($eti,['input','img','br','hr']) ? "{$htm}
    </{$eti}>" : '' );
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
          $_[$tip] = api_ele::val($ele[$tip]);
        }
        unset($ele[$tip]);
      }
    }
    return $_;
  }
  // fondos
  static function fon( string $dir, array $ope=[] ) : string {
    if( empty($ope['tip']) ) $ope['tip']='png';
    if( empty($ope['ali']) ) $ope['ali']='center';
    if( empty($ope['tam']) ) $ope['tam']='contain';
    if( empty($ope['rep']) ) $ope['rep']='no-repeat';
    return "background: {$ope['rep']} {$ope['ali']}/{$ope['tam']} url('{$dir}.{$ope['tip']}');";
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
  
      $ele = api_obj::dec($ele,[],'nom');
  
      if( isset($ele['class']) ){
  
        foreach( explode(' ',$ele['class']) as $val ){ 
  
          if( !empty($val) ) $_[] = trim($val);
        }
      }
    }// operaciones
    else{
      
      if( in_array('eli',$opc) ){

        foreach( api_lis::ite($val) as $v ){
          
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

        $css = api_ele::css($ele);

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
  }
}