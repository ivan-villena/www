<?php

// listado / tabla : [ ... ]
class api_lis {

  // aseguro iteraciones 
  static function ite( mixed $dat, mixed $ope = NULL ) : array {

    $_ = [];

    if( empty($ope) ){

      $_ = api_obj::pos($dat) ? $dat : [ $dat ];
    }
    // ejecuto funciones
    elseif( is_array($dat) && is_callable($ope) ){
      
      foreach( $dat as $pos => $val ){

        $_ []= $ope( $val, $pos );
      }
    }  
    return $_;
  }
  // convierto a listado : [ ...$$ ]
  static function val( array | object $dat ) : array {

    $_ = $dat;

    if( api_obj::tip($dat) ){

      $_ = [];

      foreach( $dat as $v ){

        $_[] = $v;
      }
    }
    return $_;
  }
  // proceso estructura
  static function ope( array &$dat, array $ope=[], ...$opc ) : array | object {
    
    // junto estructuras
    if( isset($ope['jun']) ){
      api_lis::jun($dat, $ope['jun'], ...$opc);
    }
    // ejecuto filtro
    if( isset($ope['ver']) ){
      api_lis::ver($dat, $ope['ver'], ...$opc);
    }      
    // genero elementos
    if( isset($ope['ele']) ){
      api_lis::dec($dat, $ope['ele'], 'nom');
    }
    // genero objetos  
    if( isset($ope['obj']) ){
      api_lis::dec($dat, $ope['obj'] );
    }
    // nivelo estructura
    if( isset($ope['niv']) ){
      api_lis::niv($dat, $ope['niv'] );
    }// o por indice
    elseif( isset($ope['nav']) && is_string($ope['nav']) ){
      api_lis::nav($dat, $ope['nav'] );
    }
    // reduccion por atributo
    if( isset($ope['red']) && is_string($ope['red']) ){
      api_lis::red($dat, $ope['red'] );
    }      
    // devuelvo unico objeto
    if( isset($ope['opc']) ){

      $ope['opc'] = api_lis::ite($ope['opc']);

      if( in_array('uni',$ope['opc']) ) api_lis::uni($dat, ...$opc );
    }
    return $dat;
  }
  // decodifica : "" => {} , []
  static function dec( array &$dat, string | array $atr, ...$opc ) : array {

    $atr = api_lis::ite($atr);

    foreach( $dat as &$ite ){

      if( is_object($ite) ){

        foreach( $atr as $ide ){

          if( isset($ite->$ide) ){

            $ite->$ide = api_obj::dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);            
          }
        }
      }
    }
    
    return $dat;
  }
  // nivelar indice
  static function niv( array &$dat, mixed $ide ) : array {

    $_ = [];
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
    return $dat = $_;
  }
  // armar navegacion por posicion : xx-xx-xx
  static function nav( array &$dat, string $atr = 'pos' ) : array {
    $_ = [];      
    // creo subniveles
    for( $nav=1; $nav<=7; $nav++ ){
      $_[$nav] = [];
    }
    // cargo por subnivel
    foreach( 
      $dat as $val 
    ){
      switch( 
        $cue = count( $niv = explode('-', is_object($val) ? $val->$atr : $val[$atr] ) ) 
      ){
      case 1: $_[$cue][$niv[0]] = $val; break;
      case 2: $_[$cue][$niv[0]][$niv[1]] = $val; break;
      case 3: $_[$cue][$niv[0]][$niv[1]][$niv[2]] = $val; break;
      case 4: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]] = $val; break;
      case 5: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]] = $val; break;
      case 6: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]] = $val; break;
      case 7: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]][$niv[6]] = $val; break;
      }
    }  
    return $dat = $_;
  }
  // reducir elemento a un atributo
  static function red( array &$dat, string $atr ) : array {    
    
    foreach( $dat as &$n_1 ){

      if( is_object($n_1) ){
        if( isset($n_1->$atr) ) $n_1 = $n_1->$atr;
      }
      elseif( is_array($n_1) ){

        foreach( $n_1 as &$n_2 ){

          if( is_object($n_2) ){
            if( isset($n_2->$atr) ) $n_2 = $n_2->$atr;
          }
          elseif( is_array($n_2) ){

            foreach( $n_2 as &$n_3 ){

              if( is_object($n_3) ){
                if( isset($n_3->$atr) ) $n_3 = $n_3->$atr;
              }
              elseif( is_array($n_3) ){

                foreach( $n_3 as &$n_4 ){

                  if( is_object($n_4) ){
                    if( isset($n_4->$atr) ) $n_4 = $n_4->$atr;
                  }
                  elseif( is_array($n_4) ){

                    foreach( $n_4 as &$n_5 ){

                      if( is_object($n_5) ){
                        if( isset($n_5->$atr) ) $n_5 = $n_5->$atr;
                      }
                      elseif( is_array($n_5) ){

                        foreach( $n_5 as &$n_6 ){

                          if( is_object($n_6) ){
                            if( isset($n_6->$atr) ) $n_6 = $n_6->$atr;
                          }
                          elseif( is_array($n_6) ){

                            foreach( $n_6 as &$n_7 ){

                              if( is_object($n_7) ){
                                if( isset($n_7->$atr) ) $n_7 = $n_7->$atr;
                              }
                              elseif( is_array($n_7) ){                        
                                // ...
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
    return $dat;
  }
  // filtrar elementos
  static function ver( array &$dat, array $ope = [], ...$opc ) : array {
    $_ = [];
    foreach( $dat as $pos => $ite ){ 
      $val_ite = [];           
      foreach( $ite as $atr => $val ){ 

        foreach( $ope as $ver ){ 

          if( $atr == $ver[0] ) 
            $val_ite []= api_dat::ver( $val, $ver[1], $ver[2] );
        }
      }
      // evaluo resultados
      if( count($val_ite) > 0 && !in_array(FALSE,$val_ite) )
        $_[] = $ite;
    }
    return $dat = $_;
  }
  // juntar estructuras
  static function jun( array &$dat, array $ope = [], ...$opc ) : array {

    return $dat = array_merge($ope, $dat);
  }
  // agrupar elementos por atributo con funcion
  static function gru( array &$dat, array $ope = [], ...$opc ) : array {
    return $dat;
  }
  // ordenar elementos
  static function ord( array &$dat, array $ope = [], ...$opc ) : array {
    return $dat;
  }
  // limitar resultados
  static function lim( array &$dat, array $ope = [], ...$opc ) : array {
    return $dat;
  }
  // transforma a unico elemento
  static function uni( array &$dat, ...$opc ) : array | object {
    $_ = new stdClass;

    if( in_array('fin',$opc) ){ $dat = array_reverse($dat); }

    foreach( $dat as $i => $v ){
      $_ = $dat[$i];
      break;
    }

    return $dat = $_;
  }
  // elimina elementos
  static function eli( array &$dat, string $ope, mixed $val, ...$opc ) : array {
    $_ = [];
    $pos = 0;
    $opc_ide = in_array('ide',$opc);
    $lis_tip = api_obj::pos($dat);
    
    foreach( $dat as $i => $v ){

      if( !api_dat::ver( $opc_ide ? $i : $v, $ope, $val ) ) 
      
        $_[ $lis_tip ? $pos : $i ] = $v;

      $pos++;
    }
    
    return $dat = $_;
  }    
}