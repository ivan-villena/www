<?php
// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class Obj {

  static string $IDE = "Obj-";
  static string $EJE = "Obj.";

  function __construct(){
  }// getter 
  static function _( string $ide, $val = NULL ) : string | array | object {
    
    $_ = $_dat = Dat::get_est('obj',$ide,'dat');
    
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

  // controlador
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_eje = self::$EJE."obj";

    $_ite = function( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
      $_ = "";
  
      $ope['ent']=isset($ope['ent'])?$ope['ent']:'alm';
      
      $ope['eti']=isset($ope['eti'])?$ope['eti']:[]; 
      $ope['ite']=isset($ope['ite'])?$ope['ite']:[];      
  
      if( is_null($dat) ){ 
        $dat=''; 
        $tip_dat='val'; 
        $tip_val='vac'; 
        $_="<input type='radio' disabled>";
      }
      else{ 
        $tip = Dat::tip($dat); 
        $tip_dat = $tip['dat']; 
        $tip_val = $tip['val']; 
      }
  
      $ite = "";
      if( in_array('dat',$opc) && $tip != 'val' ){ 
        $ite = "<input type='checkbox'>"; 
      }
      // items de lista -> // reducir dependencias
      $cla_ide = "{$tip_dat}";
      if( in_array($tip_dat,[ 'lis' ]) ){
  
        if( $ite != "" ){          
          $_ = $cla_ide::var( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
        }
        else{
          $_ = Lis::opc( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
        }
      }// controladores
      else{
  
        $dat = is_string($dat) ? $dat : strval($dat); 
        $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p".Ele::atr($ope['ite']).">{$dat}</p>";
      }
      $ide='';
      if( !empty($ite) ){ 
        $agr = "";
        if( $tip == 'pos' ){
          $agr = " tam='2'";
          $tip = "number";
        }else{
          $tip = "text";
        }
        $ide="<input class='ide' type='{$tip}'{$agr} value='{$ide}' title='{$ide}'>";
      }
      else{ 
        $ide="<c class='sep'>[</c><n>{$ide}</n><c class='sep'>]</c>";
      }
      if( $tip == 'pos' ){
        $sep='='; 
      }else{ 
        $sep=( $tip == 'nom' ) ? '=>' : ':' ; 
      }  
      $sep = "<c class='sep'>{$sep}</c>"; 
  
      return "
      <li class='atr' data-ide='{$ide}'>
        {$ite}{$ide}{$sep}{$_}
      </li>";  
    };
    // texto : json
    if( !isset($dat) || is_string($dat) ){
      $ope['value'] = strval($dat); $_ = "
      <textarea".Ele::atr($ope).">{$dat}</textarea>";
    }
    // por tipos: pos - nom - atr
    elseif( $tip = Obj::val_tip($dat) ){
      $cue = 0; 
      $htm = '';
      $cla_agr = ''; 
      $cla_tog = ' dis-ocu';
      if( in_array('ocu',$opc) ){ 
        $cla_agr = ' dis-ocu'; 
        $cla_tog = ''; 
      }
      $atr_agr = in_array('dat',$opc) ? '' : " disabled";
      // separadores
      if( $tip=='pos' ){ $ini='('; $fin=')'; }elseif( $tip=='nom' ){ $ini='['; $fin=']'; }else{ $ini='{'; $fin='}'; }
      // conteido
      if( is_object($dat) ){
        // ... incluir metodos
      }
      foreach( $dat as $i=>$v ){ 
        $cue++; 
        $htm .= $_ite($i,$v,$tip,...$opc);
      }
      Ele::cla($ope,"Obj {$tip}",'ini');
      $_ = "
      <div".Ele::atr($ope).">
        <div class='jus-ini mar_ver-1'>
          <p>
            <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
          </p>
          ".Fig::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
          <ul class='doc_bot _tog{$cla_agr}'>"; 
            if( empty($atr_agr) ){ $_.="
            ".Fig::ico('dat_tod',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
            ".Fig::ico('dat_nad',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
            ".Fig::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
            ".Fig::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
            ";
            }$_.="
          </ul>
          <p class=' _tog{$cla_tog}'>
            <c class='sep _lis-fin'>{$fin}</c>
          </p>
        </div>
        <ul class='lis _atr ali-ini _tog{$cla_agr}'> 
          {$htm}
        </ul>
        <p class='_tog{$cla_agr}'>
          <c class='_lis-fin'>{$fin}</c>
        </p>
      </div>";
    }

    return $_;
  }

  // valor : ()($)atr_ide()
  static function val( object | array $dat, string $val='' ) : string {
    $_ = [];
    $val_arr = Obj::val_tip($dat) == 'nom';
    foreach( explode(' ',$val) as $pal ){ 
      $let=[];
      foreach( explode('()',$pal) as $cad ){ 
        $sep = $cad;
        if( substr($cad,0,3)=='($)' ){ $sep='';
          $ide=substr($cad,3);
          if( $val_arr ){
            if( isset($dat[$ide]) ){ $sep = $dat[$ide]; }
          }else{
            if( isset($dat->$ide) ){ $sep = $dat->$ide; }
          }
        }
        $let[]=$sep;
      }
      $_[] = implode('',$let);
    }
    $_ = implode(' ',$_);
    return $_;
  }// recorro atributos 
  static function val_lis( array | object $obj, array | object $dat ) : array | object {
    // iteraciones
    foreach( $obj as &$val ){
      $val = Obj::val_ite($val,$dat);
    }
    return $obj;
  }// y convierto valor en caso de tener alguno
  static function val_ite( mixed $val, array | object $dat ) : mixed {

    if( is_array($val) || is_object($val) ){
      foreach( $val as $var_ide => $val_atr ){
        $val[$var_ide] = Obj::val_ite($val_atr,$dat);
      }
    }
    elseif( is_string($val) ){ // && preg_match("/\(\)\(\$\).+\(\)/",$val)      
      $val = Obj::val($dat,$val);
    }

    return $val;
  }// convierto a string: {} => ""
  static function val_cod( object | array | string $dat ) : string {
    $_ = [];
    
    if( is_array($dat) || is_object($dat) ){
      // https://www.php.net/manual/es/function.json-encode.php
      // https://www.php.net/manual/es/json.constants.php
      $_ = json_encode( $dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT );
    }
    return $_;
  }// convierto a objeto : "" => {}/[]
  static function val_dec( object | array | string $dat, array | object $ope = NULL, ...$opc ){
    $_ = $dat;
    // convierto : "" => {}
    if( is_string($dat) ){  
      // busco : ()($)atributo-valor()
      if( !empty($ope) && preg_match("/\(\)\(\$\).+\(\)/",$dat) ){
        $dat = Obj::val($ope,$dat);
      }
      // json : { "atr": val, ... } || [ val, val, ... ]
      if( preg_match("/^({|\[).*(}|\])$/",$dat) ){ 
        // https://www.php.net/manual/es/function.json-decode
        // https://www.php.net/manual/es/json.constants.php
        $_ = json_decode($dat, in_array('nom',$opc) ? TRUE : FALSE, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK );
  
      }
      // valores textuales : ('v_1','v_2','v_3')
      elseif( preg_match("/^\('*.*'*\)$/",$dat) ){
        
        $_ = preg_match("/','/",$dat) ? explode("','",substr($dat,1,-1 )) : [ trim(substr($dat,1,-1 )) ] ;
  
      }
      // elemento del documento : "a_1(=)v_1(,,)a_2(=)v_2"
      elseif( preg_match("/\(,,\)/",$dat) && preg_match("/\(=\)/",$dat) ){
  
        foreach( explode('(,,)',$dat) as $v ){ 
  
          $eti = explode('(=)',$v);
  
          $_[$eti[0]] = $eti[1];
        }
      }
      // esquema.estructura : tabla de la base
      elseif( preg_match("/[A-Za-z0-9_]+\.[A-Za-z0-9_]+$/",$dat) ){
  
        $_ = Dat::get($dat,$ope);
        
      }
    }// convierto : {} => []
    elseif( in_array('nom',$opc) && is_object($dat) && get_class($dat)=='stdClass' ){    
      $_ = Obj::val_nom($dat);
    }
    return $_;
  }// busco dato por propiedad
  static function val_dat( array | object $dat, string | array $atr ) : mixed {
    $_ = $dat;
    foreach( ( is_string($atr) ? explode('.',$atr) : $atr ) as $atr_ide ){
      if( is_array($_) && isset($_[$atr_ide]) ){
        $_ = $_[$atr_ide];
      }elseif( is_object($_) && isset($_->$atr_ide) ){
        $_ = $_->$atr_ide;
      }else{
        $_ = FALSE;
        break;
      }        
    }
    return $_;
  }// combino por contenido
  static function val_jun( array | object $dat, array | object $ope, ...$opc ) : array | object {
    // devuelvo original  
    $val_obj = is_object($_ = $dat);
    $opc_act = in_array('mod',$opc);
    // recorro y agrego atributos del secundario
    foreach( $ope as $atr => $val ){
      // si tienen el mismo atributo
      if( $opc_act && ( $val_obj ? isset($_->$atr) : isset($_[$atr]) ) ){
        // valor del original
        $val_ite = $val_obj ? $_->$atr : $_[$atr];
        // combino objetos o reemplazo
        $val = ( Obj::val_tip($val) && Obj::val_tip($val_ite) ) ? Obj::val_jun($val_ite,$val,...$opc) : $val;
      }
      // agrego / actualizo atributo
      if( $val_obj ){ $_->$atr = $val; }else{ $_[$atr] = $val; }
    }
    return $_;
  }// tipos : pos | nom | atr
  static function val_tip( mixed $dat ) : bool | string {
    
    $_ = FALSE;

    if( Lis::val($dat) ){

      $_ = 'pos';
    }
    elseif( is_array($dat) ){

      $_ = 'nom';
    }
    elseif( is_object($dat) ){

      $_ = get_class($dat) == 'stdClass' ? 'atr' : 'atr';
    }

    return $_;
  }// nombre : [ ..."" => $$ ]
  static function val_nom( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
    $_ = $dat;
    if( empty($tip) ){
      if( is_object($dat) && get_class($dat)=='stdClass' ){
        $_ = [];
        foreach( $dat as $atr => $val ){
          $_[$atr] = $val;
        }
      }
    }else{
      switch( $tip ){
      case 'ver':
        $_ = [];
        if( empty($ope = Lis::val_ite($ope)) ){

          foreach( $dat as $atr => $val ){ $_[$atr] = $val; }
        }
        elseif( is_object($dat) ){

          foreach( $ope as $atr ){ if( isset($dat->$atr) ) $_[$atr] = $dat->$atr; }
        }
        else{
          foreach( $ope as $atr ){ if( isset($dat[$atr]) ) $_[$atr] = $dat[$atr]; }
        }
        break;
      }
    }
    return $_;
  }// objeto : { ..."" : $$ }
  static function val_atr( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
    $_ = $dat;

    if( !isset($tip) ){
      // listado de objetos
      if( Lis::val($dat) ){
        
        $_ = array_map( function($i){ return clone $i; }, $dat );
      }
      // creo un objeto desde un array
      elseif( is_array($dat) ){
        $_ = new stdClass();
        foreach( $dat as $atr => $val ){
          $_->$atr = $val;
        }
      }
      // copio objeto
      elseif( is_object($dat) ){
        $_ = clone $dat;
      }
    }
    else{
      switch( $tip ){
      case 'ver':
        $_ = new stdClass();
        if( empty($ope = Lis::val_ite($ope)) ){

          foreach( $dat as $atr => $val ){ $_->$atr = $val; }
        }
        elseif( is_object($dat) ){

          foreach( $ope as $atr ){ if( isset($dat->$atr) ) $_->$atr = $dat->$atr; }
        }
        else{
          foreach( $ope as $atr ){ if( isset($dat[$atr]) ) $_->$atr = $dat[$atr]; }
        }
        break;
      }
    }
    return $_;
  }
}