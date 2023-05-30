<?php

// Dato
class Dat {

  // cargo datos en memoria por "esq.est"
  static function _( string $ide, mixed $key = NULL ) : mixed {

    extract( Dat::ide($ide) );

    $_ = $_dat = Dat::est($esq,$est,'dat');
    
    if( !empty($key) ){

      $_ = $key;

      if( is_numeric($key) ){ 

        $key = intval($key) - 1;

        $_ = isset($_dat[$key]) ? $_dat[$key] : $key;
      }
      elseif( is_string($key) ){

        $_ = isset($_dat[$key]) ? $_dat[$key] : $key;
      }
      elseif( is_array($key) ){
        
        switch( count($key) ){
        case 1: $_ = isset($_dat[$key[0]]) ? $_dat[$key[0]] : $key; break;
        case 2: $_ = isset($_dat[$key[0]][$key[1]]) ? $_dat[$key[0]][$key[1]] : $key; break;
        case 3: $_ = isset($_dat[$key[0]][$key[1]][$key[2]]) ? $_dat[$key[0]][$key[1]][$key[2]] : $key; break;
        case 4: $_ = isset($_dat[$key[0]][$key[1]][$key[2]][$key[3]]) ? $_dat[$key[0]][$key[1]][$key[2]][$key[3]] : $key; break;
        case 5: $_ = isset($_dat[$key[0]][$key[1]][$key[2]][$key[3]][$key[4]]) ? $_dat[$key[0]][$key[1]][$key[2]][$key[3]][$key[4]] : $key; break;
        }
      }
    }

    return $_;
  }
  /* getter: objeto ( "esq", "est" ) | consulta con operadores ( "esquema.tabla" / [ ...] ) */
  static function get( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : mixed {

    // objeto->propiedad 
    if( is_string($dat) && is_string($ope) ){

      $_ = isset($val) ? $val : new stdClass;

      if( !isset($val) || !Obj::tip($val) ){

        $_ = Dat::_( "{$dat}.{$ope}", $val );
      }
    }
    // estructuras
    else{
      $_ = $dat;
      // datos de la base 
      if( is_string($ide = $dat) ){

        // ejecuto consulta
        $_ = sql::reg('ver', $ide, isset($ope) ? $ope : [] );

        if( isset($ope) ){
          // elimino marcas
          foreach( ['ver','jun','gru','ord','lim'] as $i ){ if( isset($ope[$i]) ) unset($ope[$i]); }
          // busco clave primaria
          if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){

            $ope['niv'] = sql::ind($ide,'ver','pri');
          }
        }
      }// resultados y operaciones
      if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) ) $_ = Obj::est($_,$ope);
    }
    return $_;
  }

  // Identificadores
  static function ide( $dat, array $ope=[] ) : array {

    if( is_string($dat) ) $dat = explode('.',$dat);

    if( !isset($dat[1]) ){
      $dat[1] = $dat[0];
      $dat[0] = DAT_ESQ;
    }

    $ope['esq'] = !empty($dat[0]) ? $dat[0] : DAT_ESQ;

    $ope['est'] = $dat[1];
    
    $ope['atr'] = isset($dat[2]) ? $dat[2] : FALSE;

    return $ope;
  }

  // Tipo : dato + valor
  static function tip( mixed $val ) : bool | object {
        
    $ide = strtolower(gettype($val));

    // vacios
    if( is_null($val) ){
      $ide = "null";
    }
    // logicos
    elseif( is_bool($val) ){
      $val = "bool";
    }
    // funciones
    elseif( is_callable($val) ){ 
      $ide = "function"; 
    }
    // listados
    elseif( is_array($val) && array_keys($val) !== range( 0, count( array_values($val) ) - 1 ) ){
      $ide = "asoc"; 
    }
    // numericos
    elseif( is_numeric($val) ){ 
      $ide="int";      
      if( is_nan($val) ){ 
        $ide = "nan";
      }// evaluar largos
      else{
        if( is_integer($val) || is_long($val) ){          
          $ide = "integer";
          if( $val >= -128 && $val <= 127 ){ 
            $ide = "tinyint";
          }elseif( $val >= -32768 && $val <= 32767 ){ 
            $ide = "smallint";
          }elseif( $val >= -8388608 && $val <= 8388607 ){ 
            $ide = "mediumint";
          }elseif( $val >= -2147483648 && $val <= 2147483647 ){ 
            $ide = "int";
          }elseif( $val >= -92233720368547 && $val <= 92233720368547 ){ 
            $ide = "bigint";
          }else{
            $ide = "long";
          }
        }else{
          $ide="decimal";
          if( is_double($val) ){ 
            $ide = "double";
          }
          elseif( is_float($val) ){ 
            $ide = "float";
          }
        }
      }
    }
    // textos
    elseif( is_string($val) ){
      $tam = strlen($val);
      $ide = "varchar";
      if( $tam <= 50 ){
        if( preg_match("/^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/",$val) ){ 
          $ide = "datetime";
        }elseif( preg_match("/^\d{4}([\-\/\.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/",$val) ){ 
          $ide = "date";              
        }elseif( preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/",$val) ){ 
          $ide = "time";                  
        }
      }
      elseif( $tam <= 255 && $tam >= 100 ){
        $ide = "tinytext";
      }
      elseif( $tam <= 65535 ){
        $ide = "text";
      }
      elseif( $tam <= 16777215 ){
        $ide = "mediumtext";
      }
      elseif( $tam <= 4294967295 ){
        $ide = "longtext";
      }
      else{ 
        $ide = "string";
      }
    }

    $dat_tip = Dat::_("sis.dat_tip");
    return isset($dat_tip[$ide]) ? $dat_tip[$ide] : FALSE;
  }

  // Comparación
  static function ver( mixed $dat, string $ide, mixed $val ) : bool {
    $_ = FALSE;
    switch( $ide ){
    case '===': $_ = ( $dat === $val );  break;
    case '!==': $_ = ( $dat !== $val );  break;
    case '=':   $_ = ( $dat ==  $val );  break;
    case '<>':  $_ = ( $dat !=  $val );  break;
    case '==':  $_ = ( $dat ==  $val );  break;
    case '!=':  $_ = ( $dat !=  $val );  break;          
    case '>':   $_ = ( $dat  >  $val );  break;
    case '>>':  $_ = ( $dat  >  $val );  break;
    case '<<':  $_ = ( $dat  <  $val );  break;
    case '<':   $_ = ( $dat  <  $val );  break;
    case '>=':  $_ = ( $dat >=  $val );  break;
    case '<=':  $_ = ( $dat <=  $val );  break;
    case '^^':  $_ =  preg_match("/^".$val."/",$dat); break;
    case '!^':  $_ = !preg_match("/^".$val."/",$dat); break;    
    case '$$':  $_ =  preg_match("/".$val."$/",$dat); break;
    case '!$':  $_ = !preg_match("/".$val."$/",$dat); break;
    case '**':  $_ =  preg_match("/".$val."/",$dat);  break;
    case '!*':  $_ = !preg_match("/".$val."/",$dat);  break;
    }
    return $_;
  }

  // operadores  
  static array $Ope = [];
  // Selectores de opciones
  static function ope_opc( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {

    if( !isset(self::$Ope[$dat[0]][$dat[1]]) ){
      
      $_dat = Dat::get( 'sis-dat_ope', [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      self::$Ope[$dat[0]][$dat[1]] = Doc_Val::opc( $_dat, $ope, ...$opc);
    }

    return self::$Ope[$dat[0]][$dat[1]];

  }

  /* Estructura */  
  static array $Est = [    
  ];// Cargo Estructura : dat_est
  static function est( string $esq, string $ide, mixed $ope = NULL, mixed $dat = NULL ) : mixed {

    $_ = [];

    // cargo una estructura
    if( !isset($ope) ){

      if( !isset(self::$Est[$esq][$ide]) ){
        
        // Cargo Estructura
        self::$Est[$esq][$ide] = sql::est('ver', $sql_est = "{$esq}-{$ide}",'uni');

        if( empty(self::$Est[$esq][$ide]) ){

          self::$Est[$esq][$ide] = new stdClass;
        }// de la Base
        else{

          $sql_vis = "_{$sql_est}";
        }

        // ...Propiedades extendidas
        $_est = Dat::get('sis-dat_est',[ 
          'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 
          'ele'=>["ope"], 
          'opc'=>"uni" 
        ]);

        // si existe la estructura
        if( isset($_est->ope) ){
          // Actualizo Propiedades
          foreach( $_est->ope as $ope_ide => $ope ){

            self::$Est[$esq][$ide]->$ope_ide = $ope;
          }
        }

        // Estructura de la base
        if( isset($sql_vis) ){
          
          // Atributos/columnas: de una vista ( si existe ) o de la tabla
          self::$Est[$esq][$ide]->atr = sql::atr( !empty( sql::est('nom',"_{$sql_est}",'uni') )  ? "_{$sql_est}" : $sql_est );

          if( isset($_est->ope['atr']) ){

            // cargo variables del operador
            foreach( $_est->ope['atr'] as $atr_ide => $atr_var ){

              self::$Est[$esq][$ide]->atr[$atr_ide]->var = Ele::val_jun(
                self::$Est[$esq][$ide]->atr[$atr_ide]->var, $atr_var
              );
            }
          }

          // Datos/registros: de una vista ( si existe ) o de la tabla
          $est_ope = isset(self::$Est[$esq][$ide]->dat) ? self::$Est[$esq][$ide]->dat : [];
          
          self::$Est[$esq][$ide]->dat = Dat::get( sql::est('val',$sql_vis) == 'vis' ? $sql_vis : $sql_est, $est_ope );          
        }
      }

      // devuelvo estructura completa: esq + ide + nom + atr + dat + ...ope
      $_ = self::$Est[$esq][$ide];

    }
    // cargo operadores
    elseif( is_string($ope) ){

      // propiedad
      $ope_atr = explode('.',$ope);
      
      $_ = Obj::val_dat( Dat::est($esq,$ide), $ope_atr );

      // proceso datos
      if( !!$_ && isset($dat) ){

        switch( $ope_atr[0] ){
        // devuelvo atributo/s
        case 'atr':
          $atr_lis = $_;
          // devuelvo 1
          if( is_string($dat) ){
            $_ = new stdClass;
            if( isset($atr_lis[$dat]) ) $_ = $atr_lis[$dat];
          }// o muchos
          else{
            $_ = [];
            foreach( $dat as $atr ){
              if( isset($atr_lis[$atr]) ) $_[$atr] = $atr_lis[$atr];
            }
          }
          break;
        // devuelvo valores
        case 'val':
          $_ = Obj::val( Dat::get($esq,$ide,$dat), $_ );
          break;
        }        
      }
      
    }

    return $_;
  }// busco identificadores por seleccion : imagen, color...
  static function est_ide( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
    
    $_ = [ 
      'esq' => $esq, 
      'est' => $est 
    ];

    if( !empty($atr) ){
      
      // armo identificador
      $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";
      
      // busco dato en atributos
      $_atr = Dat::est($esq,$est,'atr',$atr);

      if( !empty($_atr->var['dat']) ){

        $_ide = explode('-',$_atr->var['dat']);
        $_['esq'] = $_ide[0];
        $_['est'] = $_ide[1];
      }
    }

    // armo identificador
    $_['ide'] = "{$_['esq']}-{$_['est']}";

    // valido dato
    if( !empty( $dat_Val = Dat::est($_['esq'],$_['est'],"val.$tip",$dat) ) ){
      $_['val'] = $dat_Val;
    }
    else{
      $_ = [];
    }

    return $_;
    
  }// Relaciones entre estructuras por atributo : esq.est_atr | api.dat_atr[ide].dat
  static function est_rel( string $esq, string $est, string $atr ) : array {

    $_ = [
      'ide'=>"{$esq}.{$est}_{$atr}",
      'dat'=>"{$esq}-{$est}_{$atr}",
      'esq'=>$esq,
      'est'=>$est
    ];

    // armo identificador por nombre de estructura + atributo
    if( $atr != 'ide' ){

      // 1- parametrizado en atrbutos de la estructura: dat_est.atr
      if( ( $_atr = Dat::est($esq,$est,'atr',$atr) ) && !empty($_atr->var['dat']) ){
        
        $_ide = explode('-',$_atr->var['dat']);
        $_['esq'] = $_ide[0];
        $_['est'] = $_ide[1];
      }
      // valido existencia de tabla relacional dentro del mismo esquema: dat_est.rel
      elseif( !!sql::est('val',$_['dat']) ){ 
        
        $_['est'] = "{$est}_{$atr}";
      }
      else{

        $_['est'] = $atr;
      }
    }

    $_['dat'] = "{$_['esq']}.{$_['est']}";
    $_['ide'] = "{$_['esq']}-{$_['est']}";

    return $_;
  }// Atributos desde listado o desde la base
  static function est_atr( string | array $dat, string $ope = "" ) : array {
    $_ = [];

    if( empty($ope) ){

      // de la base
      if( is_string($dat) ){

        $ide = Dat::ide($dat);

        $_ = Dat::est($ide['esq'],$ide['est'],'atr');

      }
      // listado variable por objeto
      else{

        foreach( $dat as $ite ){
          
          if( is_iterable($ite) ){
            // del 1° objeto: cargo atributos
            foreach( $ite as $ide => $val ){ 
              $atr = new stdClass;
              $atr->ide = $ide;
              $atr->nom = $ide;
              $atr->var = Dat::tip($val);
              $_ [$ide] = $atr;
            }
          }
          break;
        }        
      }
    }
    return $_;
  }
  
  /* Variable */  
  static array $Var = [];
  // Cargo variables : api.ope_var
  static function var( string $app, string $esq='', string $est='', string $ide='' ) : array {
    $_ = [];
    
    // cargo todas las estructuras del esquema
    if( empty($esq) ){
      if( !isset(self::$Var[$app]) ){
        self::$Var[$app] = Dat::get('sis-dat_var',[
          'ver'=>"`app`='{$app}'", 'niv'=>['dat','val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo por agrupacion
    elseif( empty($est) ){
      if( !isset(self::$Var[$app][$esq]) ){
        self::$Var[$app][$esq] = Dat::get('sis-dat_var',[
          'ver'=>"`app`='{$app}' AND `esq`='{$esq}'", 'niv'=>['val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo uno
    else{
      if( !isset(self::$Var[$app][$esq][$est]) ){
        self::$Var[$app][$esq][$est] = Dat::get('sis-dat_var',[
          'ver'=>"`app`='{$app}' AND `esq`='{$esq}' AND `est`='{$est}'", 'niv'=>['ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }

    if( !empty($ide) ){
      $_ = isset(self::$Var[$app][$esq][$est][$ide]) ? self::$Var[$app][$esq][$est][$ide] : [];
    }
    elseif( !empty($est) ){
      $_ = isset(self::$Var[$app][$esq][$est]) ? self::$Var[$app][$esq][$est] : [];
    }
    elseif( !empty($esq) ){
      $_ = isset(self::$Var[$app][$esq]) ? self::$Var[$app][$esq] : [];
    }
    else{
      $_ = isset(self::$Var[$app]) ? self::$Var[$app] : [];
    }

    return $_;
  }
}