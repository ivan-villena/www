<?php
// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class api_ele {

  static string $IDE = "api_ele-";
  static string $EJE = "api_ele.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = api_app::est('ele',$ide,'dat');
    
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
    }
    
    return $_;
  }

  // {} => "<>"
  static function val( ...$ele ) : string {
    $_ = "";
    $ele_lis = $ele;
    foreach( $ele_lis as $ele ){
        
      if( is_string($ele) ){
  
        $_ .= $ele;
      }      
      // 1- letra
      elseif( isset($ele['let']) ){
        $_ .= api_tex::let($ele['let']);
      }// 2- icono
      elseif( isset($ele['ico']) ){
        $ico_ide = $ele['ico'];
        unset($ele['ico']);
        $_ .= api_fig::ico($ico_ide,$ele);
      }// 3- imagen
      elseif( isset($ele['ima']) ){
        $est = explode('.',$ele['ima']);
        unset($ele['ima']);
        array_push($est,!empty($ele['ide'])?$ele['ide']:0,$ele);
        $_ .= api_fig::ima(...$est);
      }// 4- variable
      elseif( isset($ele['tip']) ){
        $tip = explode('_',$ele['tip']);
        unset($ele['tip']);
        // valores
        if( isset($ele['val']) ){
          $val = $ele['val'];
          unset($ele['val']);
        }        
        // funciones
        $eje = array_shift($tip);
        if( class_exists($cla = "api_$eje") && method_exists($cla,'var') ){
          $_ = $cla::var( empty($tip) ? 'val' : implode('_',$tip), isset($val) ? $val : NULL, $ele );
        }
        else{
          $_ = "<span class='err' title='no existe el operador $cla'></span>";
        }
        if( isset($val) ) unset($val);
      }// 5- etiqueta
      else{
        $_ .= api_ele::eti($ele);
      }
    }
    return $_;
  }// "<>" => {}
  static function val_dec( string | array $ele, array | object $dat = NULL ) : array {
    $_ = $ele;
    // convierto "" => []
    if( is_string($ele) ){
      $_ = api_obj::val_dec($ele,$dat,'nom');
    }
    // convierto {} => []
    elseif( is_object($ele) ){
      $_ = api_obj::val_nom($ele);
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
  }// combino elementos
  static function val_jun( string | array $ele, array $lis, array $ope = [] ) : array {
    // proceso opciones
    $dat = isset($ope['dat']) ? $ope['dat'] : NULL;
    // si es "", convierto a []
    $_ = api_ele::val_dec($ele,$dat);
    // recorro 2ºs elementos
    foreach( api_lis::val_ite($lis) as $ele ){
      // recorro atributos
      foreach( api_ele::val_dec($ele,$dat) as $atr => $val ){
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
    if( isset($ele['val']) ){
      if( !isset($ele['value']) ) $ele['value'] = $ele['val'];
      unset($ele['val']);
    }
    if( !is_string($htm) ){
      $htm_lis = "";
      foreach( api_lis::val_ite($htm) as $e ){ $htm_lis .= is_string($e) ? $e : api_ele::val($e); }
      $htm = $htm_lis;
    }
    $_ = "
    <{$eti}".api_ele::atr($ele).">
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
          $_[$tip] = api_ele::val($ele[$tip]);
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
    // listado
    if( !isset($val) ){
      $_ = [];
      if( isset($ele['class']) ){
        foreach( explode(' ',trim($ele['class'])) as $val ){
          $_[] = $val;
        }
      }
    }
    else{
      $ele_cla = api_ele::cla($ele);
      // elimino
      if( in_array('eli',$opc) ){
        foreach( api_lis::val_ite($val) as $cla_val ){

          foreach( $ele_cla as $cla_pos => $cla_ide ){

            if( $cla_ide == $cla_val ){ 
              unset($ele_cla[$cla_pos]);
            }
          }          
        }
      }// reemplazo
      elseif( in_array('mod',$opc) ){
        if( is_string($val) ) $val = explode(':',$val);
        
        $cla_ver = trim($val[0]);
        $cla_val = trim($val[1]);

        foreach( $ele_cla as $cla_pos => $cla_ide ){

          if( $cla_ide == $cla_ver ) $ele_cla[$cla_pos] = $cla_val;
        }
      }// agrego
      else{        
        $cla_val = api_lis::val_ite($val);
        in_array('ini',$opc) ? array_unshift($ele_cla, ...$cla_val) : array_push($ele_cla, ...$cla_val);
      }
      $ele['class'] = implode(' ',array_values($ele_cla));
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
        foreach( explode(';',trim($ele['style'])) as $art ){
          if( !empty( $val = explode(':',$art) ) ){
            if( isset($val[1]) ) $_[$val[0]] = $val[1];
          }          
        }
      }
    }// operaciones
    else{
      // por atributos : { pro = val }
      if( is_array($val) ){
        $css = api_ele::css($ele);
        // elimino
        if( in_array('eli',$opc) ){
          foreach( $val as $v ){
            if( isset($css[$v]) ) unset($css[$v]);
          }
        }// agrego, actualizo o modifico
        else{
          foreach( $val as $i => $v ){
            // concanteno o modifico
            if( isset($css[$i]) && in_array('mod',$opc) ){ $css[$i] .= $v; }else{ $css[$i] = $v; }
          }
        }

        $css_val = [];
        foreach( $css as $i => $v ){ $css_val []= "{$i} : {$v}"; }
        $ele['style'] = implode('; ',$css_val);
      }
      // por texto: agrego al inicio o al final
      else{
        if( in_array('ini',$opc) ){
          $ele['style'] = $val.( !empty($ele['style']) ? " {$ele['style']}" : "" );
        }
        elseif( !empty($ele['style']) ){
          $ele['style'] .= " ".$val;
        }
        else{
          $ele['style'] = $val;
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