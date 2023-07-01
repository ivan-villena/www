<?php

// Dato
class Dat {

  // cargo datos en memoria por "esq.est"
  static function _( string $ide, mixed $key = NULL ) : mixed {

    extract( Dat::ide($ide) );

    $_ = $_dat = Dat::est($esq,$est,'dat');
    
    if( isset($key) ){

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
        $_ = Dat::sql_reg('ver', $ide, isset($ope) ? $ope : [] );

        if( isset($ope) ){
          // elimino marcas
          foreach( ['ver','jun','gru','ord','lim'] as $i ){ if( isset($ope[$i]) ) unset($ope[$i]); }
          // busco clave primaria
          if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){

            $ope['niv'] = Dat::sql_ind($ide,'ver','pri');
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

    $dat_tip = Dat::_("var.tip");
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
      
      $_dat = Dat::get( 'var-ope', [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      self::$Ope[$dat[0]][$dat[1]] = Doc_Val::opc( $_dat, $ope, ...$opc);
    }

    return self::$Ope[$dat[0]][$dat[1]];

  }

  /* Estructura */  
  static array $Est = [    
  ];// Cargo Estructura : dat_est
  static function est( string $esq, string $ide, mixed $ope = NULL, mixed $val = NULL ) : mixed {

    $_ = [];

    // cargo una estructura
    if( !isset($ope) ){

      if( !isset(self::$Est[$esq][$ide]) ){
        
        // Cargo Estructura
        self::$Est[$esq][$ide] = Dat::sql_est('ver', $sql_est = "{$esq}-{$ide}",'uni');

        if( empty(self::$Est[$esq][$ide]) ){

          self::$Est[$esq][$ide] = new stdClass;
        }// de la Base
        else{

          $sql_vis = "_{$sql_est}";
        }

        // ...Propiedades extendidas
        $_est = Dat::get('sis-dat_est',[ 
          'ver'=>"`esq` = '{$esq}' AND `ide` = '{$ide}'", 
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
          self::$Est[$esq][$ide]->atr = Dat::sql_atr( !empty( Dat::sql_est('nom',"_{$sql_est}",'uni') )  ? "_{$sql_est}" : $sql_est );

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
          
          self::$Est[$esq][$ide]->dat = Dat::get( Dat::sql_est('val',$sql_vis) == 'vis' ? $sql_vis : $sql_est, $est_ope );          
        }
      }

      // devuelvo estructura completa: esq + ide + nom + atr + dat + ...ope
      $_ = self::$Est[$esq][$ide];

    }
    // cargo operadores
    elseif( is_string($ope) ){

      // pido estructura con propiedad
      $_ = Obj::val_dat( Dat::est($esq,$ide), $ope_atr = explode('.',$ope) );

      // proceso datos
      if( !!$_ && isset($val) ){

        switch( $ope_atr[0] ){
        // devuelvo atributo/s
        case 'atr':
          $atr_lis = $_;
          // devuelvo 1
          if( is_string($val) ){
            $_ = new stdClass;
            if( isset($atr_lis[$val]) ) $_ = $atr_lis[$val];
          }// o muchos
          else{
            $_ = [];
            foreach( $val as $atr ){
              if( isset($atr_lis[$atr]) ) $_[$atr] = $atr_lis[$atr];
            }
          }
          break;
        // devuelvo valores
        case 'val':
          if( isset($val) ){
            $_ = Obj::val( Dat::get($esq,$ide,$val), $_ );
          }
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
      'ide'=>"{$esq}-{$est}_{$atr}",
      'dat'=>"{$esq}.{$est}_{$atr}",
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
      elseif( !!Dat::sql_est('val',$_['ide']) ){ 
        
        $_['est'] = "{$est}_{$atr}";
      }
      else{

        $_['est'] = $atr;
      }
    }

    $_['dat'] = "{$_['esq']}.{$_['est']}";
    $_['ide'] = "{$_['esq']}-{$_['est']}";

    return $_;
  }// Atributos 
  static function est_atr( string | array $dat, string $ope = "", mixed $ide = NULL ) : array | object {
    $_ = [];

    // armo listado de atributos
    if( empty($ope) || $ope == 'ver' ){

      // de la base
      if( is_string($dat) ){

        $dat_ide = Dat::ide($dat);

        $_ = Dat::est($dat_ide['esq'],$dat_ide['est'],'atr',$ide);

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
    // proceso atributos de una estructura
    else{
    }
    return $_;
  }// actualizo registros de la base
  static function est_abm( string $tip, string $ide, mixed $dat ) : string {
    $_ = "";
    $dat = Obj::pos_ite($dat);

    // pido clave primaria
    $pri = Dat::sql_ind($ide,'ver','pri');

    // pido atributos
    $_atr = Dat::sql_atr($ide);

    // elimino atributos que no existan en la tabla
    if( $tip != 'eli' ){

      foreach( $dat as &$_dat ){
        
        foreach( $_dat as $atr_ide => $dat_atr ){

          if( !isset($_atr[$atr_ide]) ) unset($_dat->$atr_ide);
        }
      }
    }

    // pido consulta
    $sql = [];
    if( $tip == 'agr' ){

      // elimino autonuméricos
      foreach( $pri as $key ){

        if( !empty($_atr[$key]->var['num_inc']) ){

          foreach( $dat as $_dat ){

            if( isset($_dat->$key) ) unset($_dat->$key);
          }
        }
      }

      // ejecuto consultas
      foreach( $dat as $_dat ){

        $sql []= Dat::sql_reg($tip, $ide, [ 'val'=>$_dat ]);
      }
    }
    else{
      
      foreach( $dat as $_dat ){

        $sql []= Dat::sql_reg($tip, $ide, [ 'ver'=>Dat::sql_ind($ide,'val',$_dat,$pri) ]);
      }   
    }    
    
    // ejecuto consultas
    $_ = Dat::sql( implode('; ',$sql).";" );   
    
    // si hay error, devuelvo texto
    if( isset($_['_err']) ){
      $_ = $_['_err'];
    }
    else{
      $_ = count($_);
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
          'ver'=>"`app` = '{$app}'",
          'ord'=>"pos ASC",
          'niv'=>['dat','val','ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }// cargo por agrupacion
    elseif( empty($est) ){
      if( !isset(self::$Var[$app][$esq]) ){
        self::$Var[$app][$esq] = Dat::get('sis-dat_var',[
          'ver'=>"`app` = '{$app}' AND `esq` = '{$esq}'", 
          'ord'=>"pos ASC",
          'niv'=>['val','ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }// cargo uno
    else{
      if( !isset(self::$Var[$app][$esq][$est]) ){
        self::$Var[$app][$esq][$est] = Dat::get('sis-dat_var',[
          'ver'=>"`app` = '{$app}' AND `esq` = '{$esq}' AND `est` = '{$est}'",
          'ord'=>"pos ASC",
          'niv'=>['ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
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

  /* Lenguaje SQL */
  static array $Sql = [
    'var' => [
      'nom'=>"",        // nombre descriptivo
      'tip'=>"",        // tipo de dato-valor
      'val_ope'=>"",    // editable
      'val_req'=>"",    // obligatorio
      'val_ocu'=>"",    // oculto
      'maxlength'=>"",  // longitud maxima
      'min'=>"",        // valor minimo
      'max'=>"",        // valor máximo
      'step'=>"",       // valor incremental
      'num_inc'=>"",    // auto-incremental
      'num_pad'=>"",    // rellenar con 0
      'fec_ini'=>"",    // fecha automatica
      'fec_act'=>"",    // fecha actualizable
      'opc_dat'=>""    // datos de lista
    ]
  ];
  // ejecuto codigo
  static function sql( ...$val ){  
    $_ = []; 
    $err = [];

    $_sql = new mysqli(DAT_SER,DAT_USU,DAT_PAS,DAT_ESQ);
  
    if( $_sql->connect_errno ){ 
  
      $err[] = $_sql->connect_error;
    }
    // ejecuto consulta/s
    else{
      
      $_sql->set_charset("utf8");

      if( count($val) > 1 ){ 
        array_unshift($val,"START TRANSACTION"); 
        array_push($val,"COMMIT"); 
      }

      foreach( $val as $dat ){
  
        if( $sql_res = $_sql->query($dat) ){

          if( is_object($sql_res) ){

            while( $row = $sql_res->fetch_object() ){ 
              $_ []= $row; 
            }
          }
          else{
            $_ []= $dat;
          }
        }
        else{
          $var_eve[] = $dat;
          $err[] = $_sql->error;
        }
      }
    }
    // proceso errores
    if( !empty($err) ){
      $_['_err'] = "
      <ul class='lis'>";
      foreach( $err as $i => $v ){ $_['_err'] .= "
        <li>
          <p class='err'>".Doc_Val::let($v)."</p>
          ".( isset($var_eve[$i]) ? "<c class='sep'>=></c><q>".Doc_Val::let($var_eve[$i])."</q>" : "" )."
        </li>";
      }
      $_['_err'] .= "
      </ul>";
      
      // echo $_['_err'];
    }
    // resultados
    elseif( isset($sql_res) && is_object($sql_res) ){
      unset($sql_res);
    }
    // cierro conexion
    $_sql->close();

    return $_;
  }// codifico instrucciones
  static function sql_val( string $ide = '', array $ope = [], string $tip='ver' ) : array {

    $ide = explode('.',$ide);

    $_=[

      'est'=> isset($ide[1]) ? "`{$ide[0]}`.`{$ide[1]}`" : "`{$ide[0]}`",
      'atr'=>'',// * / fun(expr) / esq.obj.atr [ TOP num]
      'ver'=>'',// WHERE atr (ope) 'val'/`atr`/expr
      'jun'=>'',// INNER/LEFT/RIGTH JOIN p.tab_2 ON tab_1.atr = tab_2.atr
      'gru'=>'',// GROUP BY esq.obj.atr, atr_2
      'div'=>'',// HAVING 
      'ord'=>'',// ORDER BY esq.obj.atr [ ASC/DESC ], atr_2
      'lim'=>'',// LIMIT 'num'
      // insert, update
      'val'=>''// VALUES ('v_1','2','3', ...)
  
    ];
    // solo agregar 1 registro :: ( ``,`` ) values( '','' )
    if( $tip == 'agr' ){     
      $_['atr'] = [];
      $_['val'] = [];
      
      // recorro objeto
      foreach( Obj::pos_ite($ope['val']) as $pos => $atr_lis ){

        $_['ite'] = [];

        // recorro atributos
        foreach( $atr_lis as $atr => $val  ){

          if( $pos == 0 )
            $_['atr'] []= "`{$atr}`";

          if( is_string($val) ){
            $_['ite'] []= "'".str_replace("'","\'",$val)."'"; 
          }
          elseif( is_null($val) ) {
            $_['ite'] []= "NULL"; 
          }
          elseif( is_bool($val) ) {
            $_['ite'] []= !! $val ? "1" : "0";
          }
          else{
            $_['ite'] []= strval($val);
          }
        }
        $_['val'] []= "( ".implode(',',$_['ite'])." )";
      }
      $_['atr'] = implode(', ',$_['atr']);
      $_['val'] = implode(', ',$_['val']);
    }
    // solo modificar 1 o más campos de 1 o más registros :: set `atr` = 'val', 
    elseif( $tip == 'mod' ){

      $_['val'] = [];

      foreach( $ope['val'] as $atr => &$v ){ 

        if( is_string($v) ){ 

          $val = "'".str_replace("'","\'",$v)."'"; 
        }
        elseif( is_bool($v) ){
          
          $val = !! $v ? "1" : "0";
        }
        elseif( !is_null($v) ) {
          
          $val = strval($v); 
        }
        else{ 
          $val = "NULL"; 
        }
        
        $_['val'] []= " `{$atr}` = {$val}";
      }
      $_['val'] = implode(', ', $_['val'])." ";
  
    }
    // consultar :: campos, juntar, agrupar, ordenar, limitar
    elseif( $tip == 'ver' ){
      // selecciono campos
      $_['atr']='*';
      if( isset($ope['atr']) ){
        if( is_string($ope['atr']) ){
          $_['atr'] = $ope['atr'];
        }else{          
          $atr = [];
          foreach( $ope['atr'] as $v ){ 
            $atr []= ( substr($v,0,1) == '$' ) ? substr($v,1) : "`{$v}`";
          }
          $_['atr'] = implode(', ',$atr);
        }
      }// joins
      if( isset($ope['jun']) ){
        $_['jun'] .= $ope['jun'];
      }// agrupamiento
      if( isset($ope['gru']) ){
        $_['gru'] = " GROUP BY ".( is_array($ope['gru']) ? implode(', ',$ope['gru']) : $ope['gru'] );
        // condicion de agrupamiento        
        if( isset($ope['div']) ){
          $_['div']=" HAVING ".( is_array($ope['div']) ? implode(', ',$ope['div']) : $ope['div'] );
        }
      }// ordenamiento
      if( isset($ope['ord']) ){ 
        $_['ord'] = " ORDER BY ".( is_array($ope['ord']) ? implode(', ',$ope['ord']) : $ope['ord'] );
      }
      // junto condiciones + limite
      $_['ord'] = $_['ord'].$_['gru'].$_['div'].( isset($ope['lim']) ? " LIMIT ".intval($ope['lim']) : '' );
      
    }

    // ++ filtros: where `atr` ope val/var/expr
    if( isset($ope['ver']) && $tip!='agr' ){

      if( is_array($ope['ver']) ){

        foreach( $ope['ver'] as $i => $v ){

          switch( $v[1] ){
          case '==': $v[1]=' = ';                           break;
          case '!=': $v[1]=' <> ';                          break;
          case 'is': $v[1]=' IS ';                          break;// casos nulos y funciones
          case '!s': $v[1]=' NOT IS ';                      break;// casos nulos y funciones
          case '^^': $v[1]=' LIKE ';     $v[2]="{$v[2]}%";  break;
          case '!^': $v[1]=' NOT LIKE '; $v[2]="{$v[2]}%";  break;
          case '**': $v[1]=' LIKE ';     $v[2]="%{$v[2]}%"; break;
          case '!*': $v[1]=' NOT LIKE '; $v[2]="%{$v[2]}%"; break;
          case '$$': $v[1]=' LIKE ';     $v[2]="%{$v[2]}";  break;
          case '!$': $v[1]=' NOT LIKE '; $v[2]="%{$v[2]}";  break;
          default:
            if( $v[1]=='[]' || $v[1]=='!]' ){

              $v[1] = ( $v[1]=='[]' ) ? 'IN(' : 'NOT IN(';              
              if( is_string($v[2]) ) $v[2] = explode(',,',$v[2]); 
              $a['v']='';

              foreach( $v[2] as $dat ){ 
                $a['v'] .= ( substr($dat,0,3)=='$$' ) ? substr($dat,3).", " : "'{$dat}', " ; 
              }

              $v[2] = substr($a['v'],0,-2)." )"; 
            }
            break;
          }
          
          // operadores 
          if( $i == 0 ){
            $bin = '';
          }
          else{ 
            $bin = ( isset($v[3]) && ( $v[3]=='OR' || $v[3]=='||' ) )  ? 'OR ' : 'AND ' ; 
          }

          // proceso valores
          $v[0] = ( substr($v[0],0,1) == '$' ) ? substr($v[0],1) : "`{$v[0]}`";


          if( !isset($a['v']) ){

            $v[2] = ( substr($v[2],0,1) == '$' ) ? substr($v[2],1) : "'{$v[2]}'" ; 
          }

          $_['ver'].=" {$bin}{$v[0]}{$v[1]}{$v[2]}";
        }
      }
      elseif( is_string($ope['ver']) && !empty($ope['ver']) ){ 

        $_['ver']=" {$ope['ver']}";
      }

      if( !empty($_['ver']) ) $_['ver']=" WHERE{$_['ver']}";
    }

    return $_;
  }// Tipos de dato  
  static function sql_tip( string $ide ){

    if( empty(self::$Sql['tip']) ){

      self::$Sql['tip'] = Dat::get('var-tip',['ver'=>"`len` LIKE '%sql%'",'niv'=>["ide"],'ele'=>["ope"]]);
    }

    return isset(self::$Sql['tip'][$ide]) ? self::$Sql['tip'][$ide] : new stdClass;
    
  }// esquemas
  static function sql_esq( string $ope, string $ide='', ...$opc ) : string | array | object {
    $_ = [];
    if( empty($ope) ){
      foreach( Dat::sql("SHOW DATABASES") as $esq ){
        $_[] = $esq->Database;
      }
    }
    else{
      
      switch( $ope ){
      case 'cop':
        // copio estructuras
        // elimino base inicial
        $_sql []= "DROP SCHEMA `{$ide}`";
        $_ = implode(';<br>',$_sql);
        break;
      }
    }
    return $_;
  }// estructuras
  static function sql_est( string $ope, $ide='', ...$opc ) : bool | string | array | object {
    $_=[];      
    $esq = DAT_ESQ;
    // valido si existe una vista o una tabla por $ide
    if( $ope == 'val' ){

      $_ = FALSE;
      foreach( Dat::sql("SHOW TABLE STATUS FROM `{$esq}` WHERE `Name` = '{$ide}'") as $v ){
  
        $_ = ( $v->Comment == 'VIEW' ) ? 'vis' : 'tab';
      }
    }
    else{
      // proceso ides
      $ver = [];
      if( !empty($ide) ){
        if( $opc_uni = in_array('uni',$opc) ){
          $ver []= "`Name` = '{$ide}'"; 
        }else{
          $ver []= "`Name` LIKE '".( in_array('ini',$opc) ? "%{$ide}" : ( in_array('tod',$opc) ? "%{$ide}%" : "{$ide}%" ) )."'"; 
        }
      }
      // tablas o vistas
      if( in_array('vis',$opc) ){ 
        $ver []= "`Comment` = 'VIEW'"; 
      }
      elseif( in_array('tab',$opc) ){ 
        $ver []= "`Comment` <> 'VIEW'"; 
      }
      $lis = Dat::sql("SHOW TABLE STATUS FROM `{$esq}`".( !empty($ver) ? " WHERE ".implode(' AND ',$ver) : '' ));

      // listado de nombres
      if( $ope == 'nom' ){        
    
        foreach( $lis as $v ){
          $_[] = $v->Name;
        }
      }// o muestro datos por estructura
      elseif( $ope == 'ver' ){
    
        foreach( $lis as $v ){ 
          $_est = new stdClass();
          $_est->esq = $esq;
          $_est->ide = $v->Name;
          $_est->nom = $v->Comment;
          $_est->fec = $v->Create_time;
          $_[ $_est->ide ] = $_est;
        }
        // devuelvo uno solo
        if( !empty($ide) && $opc_uni && isset($_est) ){
          $_ = $_est;
        }
      }
    }
    return $_;
  }// atributos
  static function sql_atr( string $est, string $ope = 'ver', ...$opc ) : array | object | string {
    $_=[];
    $esq = DAT_ESQ;
    $dat_lis = Dat::sql("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");    
    if( isset($dat_lis['_err']) ){
      $dat_lis = Dat::sql("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");
    }
    if( $ope == 'nom' ){
      foreach( $dat_lis as $atr ){
        $_[] = $atr->Field;
      }
    }
    elseif( $ope == 'ver' ){
      $pos = 0;    
      // si existe una vista, veo esas columnas...
      foreach( $dat_lis as $i => $atr ){

        $pos++;      

        // busco tipo de sql
        $_tip = explode('(',$atr->Type);
        if( isset($_tip[1]) ) $var_cue = explode(')',$_tip[1])[0]; 
        $_var_atr = Dat::sql_tip($sql_tip = $_tip[0]);
        
        // armo tipo del sistema
        $var_dat = $_var_atr->dat;
        $var_val = $_var_atr->val;
        $dat_tip = "{$var_dat}_{$var_val}";
        
        // operador automático
        $var = !empty($_var_atr->ope) ? $_var_atr->ope : [];

        // nombre de la variable
        $var['title'] = $atr->Comment;

        // tipo de variable
        $var['tip'] = $dat_tip;
        if( empty($var_cue) && isset($var['maxlength']) ){
          $var_cue = $var['maxlength'];
        }

        // tipo de dato

        // numericos
        if( $var_dat == 'num' ){
          
          // booleano
          if( $atr->Type == 'tinyint(1)' ){
            if( isset($var['min']) ) unset($var['min']);
            if( isset($var['max']) ) unset($var['max']);
            if( isset($var['step']) ) unset($var['step']);
            if( isset($var['maxlength']) ) unset($var['maxlength']);              
            $dat_tip = $var['tip'] = 'opc_bin';              
            $var_dat = "opc";
            $var_val = "bin";
            $var_cue = NULL;
          }
          // autoincremental
          elseif( preg_match("/auto_increment/",$atr->Extra) ){ 
            $var['num_inc'] = 1;
          }
          else{
            // solo positivos y con relleno izquierdo 000-
            if( preg_match("/unsigned/",$atr->Type) ){
              
              if( $var['min'] < 0 ) $var['min'] = 0;
              if( $var['max'] < 0 ) $var['max'] = 0;
              // duplico valores maximos
              if( !empty($var['max']) ){
                $var['max'] = ( $var['max'] * 2 ) + 1;
              }
              // rellenar con 000-
              if( preg_match("/zerofill/",$atr->Type) ){ 
                $var['num_pad'] = 1; 
              }
            }
            // enteros
            if( $var_val == 'int' ){
              // limito minimos y maximos por longitud
              if( !empty($var_cue) ){
                $tot_val = ''; 
                for( $i=1; $i <= intval($var_cue); $i++ ){ 
                  $tot_val .= '9'; 
                }
                $tot_val = intval($tot_val);
                if( isset($var['max']) && $var['max'] > $tot_val  ){
                  $var['max'] = $tot_val;
                }
                if( isset($var['min']) && $var['min'] < -$tot_val  ){
                  $var['min'] = -$tot_val;
                }
              }
            }// decimales
            elseif( $var_val == 'dec' ){
              if( !empty($var_cue) ){
                $tot_dec = explode(',',$var_cue);
              }
            }              
          }
        }// textuales
        elseif( $var_dat == 'tex' ){
          if( preg_match("/_bin/", $atr->Collation) ){ 
            $var['tip'] = "dir_bit"; 
          }
        }// fechas
        elseif( $var_dat == 'fec' ){
          // valor por defecto
          if( preg_match("/CURRENT_TIMESTAMP/",$atr->Extra) ){ 
            $var['fec_ini'] = 1; 
            // actualizar al modificar
            if( preg_match("/on update/",$atr->Extra) ){ 
              $var['fec_act'] = 1; 
            }
          }
        }// listados de opciones
        elseif( $var_dat == 'opc' ){
          $var['opc_dat'] = $var_cue = Obj::val_dec($var_cue);
        }

        // proceso valores
        if( !is_null($atr->Default) ){
          $var['val'] = $atr->Default;
        }
        if( isset($var_cue) && !is_array($var_cue) ){
          $var['maxlength'] = $var_cue;
        }
        if( isset($var['fec_ini']) || isset($var['fec_act']) || isset($var['num_inc']) ){
          $var['val_ope'] = 0;
        }
        elseif( $atr->Null == 'NO' ){
          $var['val_req'] = 1;
        }

        $_atr = new stdClass();
        $_atr->esq = $esq;
        $_atr->est = $est;
        $_atr->ide = $atr->Field;
        $_atr->pos = $pos;
        $_atr->nom = $atr->Comment;
        $_atr->var = $var;
        $_atr->var_ide = $sql_tip;
        $_atr->dat_tip = $dat_tip;
        $_atr->var_dat = $var_dat;
        $_atr->var_val = $var_val;
        // ...
        $_[$_atr->ide] = $_atr;
      }
    }
    return $_;
  }// indice
  static function sql_ind( string $ide, string $ope = 'ver', ...$opc ) : array | object | string {
    $_ = [];
    $ide = explode('.',$ide);
    if( empty($ide[1]) ){
      $esq = DAT_ESQ;
      $est = $ide[0];
    }else{
      $esq = $ide[0];
      $est = $ide[1];
    }

    switch( $ope ){
    // busco datos por clave primaria para filtro
    case 'val':
      if( isset($opc[0]) && is_object($opc[0]) ){

        $dat = $opc[0];

        $pri = isset($opc[1]) && is_array($opc[1]) ? $opc[1] : Dat::sql_ind("{$esq}.{$est}",'ver','pri');

        foreach( $pri as $key ){
          
          if( isset($dat->$key) ){
            
            $tex = "`{$key}` = ";
            
            if( is_string($dat->$key) ){

              $tex .= "'{$dat->$key}'";
            }
            elseif( is_numeric($dat->$key) ){

              $tex .= "{$dat->$key}";
            }

            $_ []= $tex;
          }
        }
  
        $_ = implode(' AND ', $_);      
      }      
      break;
    // busco indices por : "pri" o $ide
    case 'ver':
      if( isset($opc[0]) ){

        $ide = $opc[0];

        foreach( Dat::sql("SHOW KEYS FROM `$esq`.`$est` WHERE `Key_name` = '".( $ide == 'pri' ? "PRIMARY" : $ide )."'") as $key ){
          $_[] = $key->Column_name;
        }
      }
      break;
    // nuevo indice
    case 'agr':
      // ALTER TABLE `$est` ADD PRIMARY KEY;<br>
      break;
    // elimino indice
    case 'eli':
      // ALTER TABLE `$est` DROP PRIMARY KEY;<br>
      break;        
    }

    return $_;
  }// registros
  static function sql_reg( string $tip, string $ide, mixed $ope=[] ) : array | object | string {
    $_ = [];

    $e = Dat::sql_val($ide, $ope, ( $tip == 'cue' ) ? 'ver' : $tip );

    switch( $tip ){
    case 'ver': 
      $_ = Dat::sql("SELECT {$e['atr']} FROM {$e['est']}{$e['jun']}{$e['ver']}{$e['ord']}");
      break;
    case 'cue':  
      $_ = Dat::sql("SELECT COUNT( {$e['atr']} ) AS `cue` FROM {$e['est']}{$e['ver']}")[0]['cue'];
      break;
    case 'agr': 
      $_ = "INSERT INTO {$e['est']} ( {$e['atr']} ) VALUES {$e['val']}";
      break;
    case 'mod': 
      $_ = "UPDATE {$e['est']} SET {$e['val']}{$e['ver']}";
      break; 
    case 'eli': 
      $_ = "DELETE FROM {$e['est']}{$e['ver']}";
      break;
    }
    return $_;
  }  
}