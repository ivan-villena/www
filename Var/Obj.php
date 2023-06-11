<?php
// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class Obj {

  /* Contenido : valor por ()($)atr_ide() */
  static function val( object | array $dat, string $val='' ) : string {
    $_ = [];
    $val_arr = Obj::tip($dat) == 'nom';
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
  }// Convierto valores del objeto
  static function val_lis( array | object $obj, array | object $dat ) : array | object {
    
    // iteraciones
    foreach( $obj as &$val ){

      $val = Obj::val_lis_ite($val, $dat);
    }

    return $obj;

  }// Convierto por propiedades 
  static function val_lis_ite( mixed $val, array | object $dat ) : mixed {

    if( is_array($val) || is_object($val) ){

      foreach( $val as $var_ide => $val_atr ){

        $val[$var_ide] = Obj::val_lis_ite($val_atr,$dat);
      }
    }
    elseif( is_string($val) ){ // && preg_match("/\(\)\(\$\).+\(\)/",$val)

      $val = Obj::val($dat,$val);
    }

    return $val;
  }// Codifico a string : {}-[] => "..."
  static function val_cod( object | array | string $dat ) : string {
    $_ = [];
    
    if( is_array($dat) || is_object($dat) ){
      // https://www.php.net/manual/es/function.json-encode.php
      // https://www.php.net/manual/es/json.constants.php
      $_ = json_encode( $dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT );
    }
    return $_;
  }// Decodifico desde string : "..." => {}-[]
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
      $_ = Obj::nom($dat);
    }
    return $_;
  }// Decodifico por listado de objetos
  static function val_dec_lis( array $dat, array $atr, ...$opc ) : array {

    foreach( $dat as &$ite ){

      if( is_object($ite) ){

        foreach( $atr as $ide ){
          
          if( isset($ite->$ide) ) $ite->$ide = Obj::val_dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);
        }
      }
    }
    return $dat;
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
        $val = ( Obj::tip($val) && Obj::tip($val_ite) ) ? Obj::val_jun($val_ite,$val,...$opc) : $val;
      }
      // agrego / actualizo atributo
      if( $val_obj ){ $_->$atr = $val; }else{ $_[$atr] = $val; }
    }
    
    return $_;
  }

  // devuelvo tipo : pos | nom | atr
  static function tip( mixed $dat ) : bool | string {
    
    $_ = FALSE;

    if( Obj::pos_val($dat) ){

      $_ = 'pos';
    }
    elseif( is_array($dat) ){

      $_ = 'nom';
    }
    elseif( is_object($dat) ){

      $_ = get_class($dat) == 'stdClass' ? 'atr' : 'atr';
    }

    return $_;
  }

  // Busco dato por propiedad ( nombre o atributo )
  static function val_dat( array | object $dat, string | array $atr ) : mixed {

    $_ = $dat;

    foreach( ( is_string($atr) ? explode('.',$atr) : $atr ) as $atr_ide ){

      if( is_array($_) && isset($_[$atr_ide]) ){

        $_ = $_[$atr_ide];
      }
      elseif( is_object($_) && isset($_->$atr_ide) ){

        $_ = $_->$atr_ide;
      }
      else{
        $_ = FALSE;
        break;
      }
    }
    return $_;
  }  

  /* por posiciones : [ ...# => $$ ] */
  static function pos( mixed $dat ) : array {

    $_ = [];

    if( is_iterable($dat) ){

      foreach( $dat as $v ){

        $_[] = $v;
      }
    }
    else{
      
      $_ = [ $dat ];
    }
    
    return $_;    
  }// Valido tipo
  static function pos_val( mixed $dat, mixed $ope = NULL ) : bool {
     
    return is_array($dat) && array_keys($dat) === range( 0, count( array_values($dat) ) - 1 );

  }// Aseguro iteraciones
  static function pos_ite( mixed $dat ) : array {

    return Obj::pos_val($dat) ? $dat : [ $dat ];
    
  }
  
  // por nombre : [ ..."" => $$ ]
  static function nom( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
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
        if( empty($ope = Obj::pos_ite($ope)) ){

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
  }
  static function nom_ver( array | object $dat, array $ope = [] ) : array {

    $_ = [];

    // copio objeto
    if( empty($ope) ){

      foreach( $dat as $atr => $val ){ 
        $_[$atr] = $val;
      }
    }// copio atributos
    elseif( is_object($dat) ){

      foreach( $ope as $atr ){ 

        if( isset($dat->$atr) ) $_[$atr] = $dat->$atr; 
      }
    }
    else{
      foreach( $ope as $atr ){ 

        if( isset($dat[$atr]) ) $_[$atr] = $dat[$atr]; 
      }
    }

    return $_;
  }

  // por atributo : { ..."" : $$ }
  static function atr( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
    $_ = $dat;

    if( !isset($tip) ){
      
      // listado de objetos
      if( Obj::pos_val($dat) ){
        
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
        if( empty($ope = Obj::pos_ite($ope)) ){

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
  static function atr_ver( array | object $dat, array $ope = [] ) : object {

    $_ = new stdClass();

    // copio objeto
    if( empty($ope) ){

      foreach( $dat as $atr => $val ){ 
        $_->$atr = $val; 
      }
    }// copio atributos
    elseif( is_object($dat) ){

      foreach( $ope as $atr ){ 

        if( isset($dat->$atr) ) $_->$atr = $dat->$atr; 
      }
    }
    else{
      foreach( $ope as $atr ){ 

        if( isset($dat[$atr]) ) $_->$atr = $dat[$atr]; 
      }
    }

    return $_;
  }

  // por estructura : [ # | "key" : { $$ } ]
  static function est( array $dat, array $ope = NULL, ...$opc ) : array | object {

    $_ = [];

    if( isset($ope) ){

      // 1- junto estructuras
      if( isset($ope['jun']) ){

        if( is_array($ope['jun']) ){

          $dat = in_array('ini',$opc) ? array_merge($ope['jun'], $dat) : array_merge($dat, $ope['jun']);
        }
      }

      // 2- ejecuto filtro
      if( isset($ope['ver']) ){

        $_ = [];
        foreach( $dat as $pos => $ite ){ 
    
          $val_ite = [];

          foreach( $ite as $atr => $val ){
    
            foreach( $ope['ver'] as $ver ){
    
              if( $atr == $ver[0] ) $val_ite []= Dat::ver( $val, $ver[1], $ver[2] );
            }
          }
          // evaluo resultados
          if( count($val_ite) > 0 && !in_array(FALSE,$val_ite) ) $_[] = $ite;
    
        }
        $dat = $_;
      }

      /* 3- Decodifico atributos : ( "" => {} / [] ) */      
      if( isset($ope['ele']) ){ 
        // - elementos
        $dat = Obj::val_dec_lis($dat, Obj::pos_ite($ope['ele']), 'nom'); 
      }
      
      if( isset($ope['obj']) ){ 
        // - objetos
        $dat = Obj::val_dec_lis($dat, Obj::pos_ite($ope['obj']) ); 
      }

      // 4- nivelo estructura
      if( isset($ope['niv']) ){
        $_ = [];
        $ide = $ope['niv'];
        // numérica => pos
        if( is_numeric($ide) ){
          $k = intval($ide);
          foreach( $dat as $val ){ 
            $_[$k++]=$val; 
          }
        }
        // Literal => nom
        elseif( is_string($ide) ){
          $k = explode('(.)',$ide);
          foreach( $dat as $i => $val ){ 
            $i=[]; 
            foreach( $k as $ide ){ $i []= $val[$ide]; }
            $_[ implode('(.)',$i) ] = $val; 
          }
        }
        // Clave Múltiple => keys-[ [ [ [],[ {-_-} ],[], ] ] ]
        elseif( is_array($ide) ){
          $k = array_values($ide);
          switch( count($k) ){
          case 1: foreach( $dat as $v ){ $_[$v->{$k[0]}]=$v; } break;
          case 2: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}]=$v; } break;
          case 3: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}]=$v; } break;
          case 4: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}]=$v; } break;
          case 5: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}]=$v; } break;
          case 6: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}][$v->{$k[5]}]=$v; } break;
          case 7: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}][$v->{$k[5]}][$v->{$k[6]}]=$v; } break;
          }
        }
        $dat = $_;
      }
      // 5- armo navegacion por posicion para indice : xx-xx-xx
      elseif( isset($ope['nav']) && is_string($ope['nav']) ){

        $_ = [];        
        $atr = $ope['nav'];

        // creo subniveles
        for( $nav=1; $nav<=7; $nav++ ){
          $_[$nav] = [];
        }
        // cargo por subnivel
        foreach( $dat as $val ){

          switch( $cue = count( $niv = explode('-', is_object($val) ? $val->$atr : $val[$atr] ) ) ){
            case 1: $_[$cue][$niv[0]] = $val; break;
            case 2: $_[$cue][$niv[0]][$niv[1]] = $val; break;
            case 3: $_[$cue][$niv[0]][$niv[1]][$niv[2]] = $val; break;
            case 4: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]] = $val; break;
            case 5: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]] = $val; break;
            case 6: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]] = $val; break;
            case 7: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]][$niv[6]] = $val; break;
          }
        }
        // valido carga por subnivel
        if( !empty($_[1]) ){
          $dat = $_;
        }
      }

      // 6- reduzco elemento a un atributo
      if( isset($ope['red']) && is_string($ope['red']) ){

        $atr = $ope['red'];

        foreach( $dat as &$n_1 ){
          if( is_object($n_1) ){ if( isset($n_1->$atr) ) $n_1 = $n_1->$atr;
          }
          elseif( is_array($n_1) ){  
            foreach( $n_1 as &$n_2 ){  
              if( is_object($n_2) ){ if( isset($n_2->$atr) ) $n_2 = $n_2->$atr;
              }
              elseif( is_array($n_2) ){  
                foreach( $n_2 as &$n_3 ){  
                  if( is_object($n_3) ){ if( isset($n_3->$atr) ) $n_3 = $n_3->$atr;
                  }
                  elseif( is_array($n_3) ){  
                    foreach( $n_3 as &$n_4 ){  
                      if( is_object($n_4) ){ if( isset($n_4->$atr) ) $n_4 = $n_4->$atr;
                      }
                      elseif( is_array($n_4) ){  
                        foreach( $n_4 as &$n_5 ){  
                          if( is_object($n_5) ){ if( isset($n_5->$atr) ) $n_5 = $n_5->$atr;
                          }
                          elseif( is_array($n_5) ){  
                            foreach( $n_5 as &$n_6 ){
                              if( is_object($n_6) ){ if( isset($n_6->$atr) ) $n_6 = $n_6->$atr;
                              }
                              elseif( is_array($n_6) ){  
                                foreach( $n_6 as &$n_7 ){  
                                  if( is_object($n_7) ){ if( isset($n_7->$atr) ) $n_7 = $n_7->$atr;
                                  }
                                }
                              }
                            }                   
                          }
                        }              
                      }
                    }  
                  }
                }    
              }
            }
          }
        }
      }
      
      // 7- resultado: uno / muchos
      if( isset($ope['opc']) ){

        // aseguro parámetro      
        $ope['opc'] = Obj::pos_ite($ope['opc']);

        // valor unico
        if( in_array('uni',$ope['opc']) ){
          // primero, o el último
          if( in_array('fin',$ope['opc']) ) $dat = array_reverse($dat); 
          foreach( $dat as $i => $v ){ $dat = $dat[$i]; break; }
        }
      }
      
      $_ = $dat;
    }

    return $_;
  }

}