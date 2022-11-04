<?php    

  // Base de datos
  class _api {

    // Interfaces
    public array
      // Dato
      $dat_tip = [],
      $dat_ope = [],
      $dat_est = [],
      $dat_atr = [],
      // Numero
      $num = [],
      // Texto
      $tex = [],
      $tex_let = [],
      // Fecha
      $fec = [],
      // Holon
      $hol = []
    ;
    // Aplicacion
    public object 
      // peticion : esq/cab/art/val -
      $app_uri;

      public array
      // iconos      
      $app_ico = [],
      // formularios
      $app_var = [],
      $app_var_ide = [],// id de variables
      $app_var_ope = [],// selector de operadores      
      // datos
      $app_dat = [],
      // valores
      $app_val = [],
      // tablas
      $app_est = [],
      // tableros
      $app_tab = []
    ;

    function __construct(){

      // aplicacion
      $this->app_uri = new stdClass;
      $this->app_ico = _dat::get('app_ico', [ 'niv'=>['ide'] ]);
      
      // variable: tipos + operaciones
      $this->dat_tip = _dat::get('dat_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
      $this->dat_ope = _dat::get('dat_ope', [ 'niv'=>['ide'] ]);

      // textos
      $this->tex_let = _dat::get('tex_let', [ 'niv'=>['ide'] ]);
      
      // fechas : mes + semana + dias
      foreach( ['mes','sem','dia'] as $ide ){

        $this->{"fec_$ide"} = _dat::get("fec_$ide");
      }
    }
    // get : estructura-objetos
    static function _( string $ide, $val = NULL ) : string | array | object {
      global $_api;
      $_ = [];
      // aseguro carga      
      if( !isset($_api->$ide) ) $_api->$ide = _dat::ini(DAT_ESQ,$ide);      
      // cargo datos
      $_dat = $_api->$ide;
      
      if( !empty($val) ){
        $_ = $val;
        if( !is_object($val) ){
          switch( $ide ){
          case 'fec':
            $_ = _fec::dat($val);
            break;
          default:
            if( is_numeric($val) ){
              $ide = intval($val)-1;
              if( isset($_dat[$ide]) ) $_ = $_dat[$ide];
            }
            elseif( isset($_dat[$val]) ){ 
              $_ = $_dat[$val];
            }
            break;
          }
        }
      }// toda la lista
      elseif( isset($_dat) ){
        $_ = $_dat;
      }
      return $_;
    }
  }
  // Código sql 
  class _sql {
    // ejecuto codigo sql 
    static function dec( ...$val ){  
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
            <p class='err'>"._app::let($v)."</p>
            ".( isset($var_eve[$i]) ? "<c class='sep'>=></c><q>"._app::let($var_eve[$i])."</q>" : "" )."
          </li>";
        }$_['_err'] .= "
        </ul>";
      }// resultados
      elseif( isset($sql_res) && is_object($sql_res) ){
        unset($sql_res);
      }
      // cierro conexion
      $_sql->close();

      return $_;
    }
    // codifica instrucciones de consultas
    static function cod( string $ide = '', array $ope = [], string $tip='ver' ) : array {
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
      if( $tip=='agr' ){     
        $_['atr'] = [];
        $_['val'] = [];    
        foreach( _lis::ite($ope['val']) as $pos=>$ite ){
          $_['ite'] = [];
          foreach( $ite as $i=>$v  ){
            if( $pos==0 )
              $_['atr'] []= "`{$i}`";
            if( is_string($v) ){
              $_['ite'] []= "'".str_replace("'","\'",$v)."'"; 
            }elseif( is_null($v) ) {
              $_['ite'] []= "NULL"; 
            }elseif( is_bool($v) ) {
              $_['ite'] []= !! $v ? "1" : "0";
            }else{
              $_['ite'] []= strval($v);
            }
          }
          $_['val'] []= "( ".implode(',',$_['ite'])." )";
        }
        $_['atr'] = implode(', ',$_['atr']);
        $_['val'] = implode(', ',$_['val']);
      }
      // solo modificar 1 o más campos de 1 o más registros :: set `atr` = 'val', 
      elseif( $tip=='mod' ){
        $_['val'] = [];
        foreach( $ope['val'] as $i=>&$v ){ 
          if( is_string($v) ){ 
            $val = "'".str_replace("'","\'",$v)."'"; 
          }elseif( is_bool($v) ) {
            $val = !! $v ? "1" : "0";
          }elseif( !is_null($v) ) { 
            $val = strval($v); 
          }else{ 
            $val = "NULL"; 
          } 
          $_['val'] []= " `{$i}` = {$val}";
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
          foreach( $ope['ver'] as $i=>$v ){
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
                if( is_string($v[2]) ){ 
                  $v[2] = explode(',,',$v[2]); 
                } 
                $a['v']='';
                foreach( $v[2] as $dat ){ 
                  $a['v'] .= ( substr($dat,0,3)=='$$' ) ? substr($dat,3).", " : "'{$dat}', " ; 
                } 
                $v[2] = substr($a['v'],0,-2)." )"; 
              }
              break;
            }
            if( $i==0 ){
              $bin=''; 
            }else{ 
              $bin=( isset($v[3]) && ( $v[3]=='OR' || $v[3]=='||' ) )  ? 'OR ' : 'AND ' ; 
            }
            $v[0] = ( substr($v[0],0,1)=='$' ) ? substr($v[0],1) : "`{$v[0]}`";
            if( !isset($a['v']) ){
              $v[2] = ( substr($v[2],0,1)=='$' ) ? substr($v[2],1) : "'{$v[2]}'" ; 
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
    }
    // esquemas
    static function esq( string $ope, string $ide='', ...$opc ) : string | array | object {
      $_ = [];
      if( empty($ope) ){
        foreach( _sql::dec("SHOW DATABASES") as $esq ){
          $_[] = $esq->Database;
        }
      }else{
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
    }
    // estructuras
    static function est( string $ope, $ide='', ...$opc ) : bool | string | array | object {
      $_=[];      
      $esq = DAT_ESQ;
      // valido si existe una vista o una tabla por $ide
      if( $ope == 'val' ){

        $_ = FALSE;
        foreach( _sql::dec("SHOW TABLE STATUS FROM `{$esq}` WHERE `Name` = '{$ide}'") as $v ){
    
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
        // muestro listado de nombres
        if( $ope == 'lis' ){
      
          if( in_array('vis',$opc) ){ $ver []= "`Comment` = 'VIEW'";  }
      
          if( in_array('tab',$opc) ){ $ver []= "`Comment` <> 'VIEW'"; }
      
          $ver = !empty($ver) ? " WHERE ".implode(' AND ',$ver) : '';
      
          foreach( _sql::dec("SHOW TABLE STATUS FROM `{$esq}`{$ver}") as $v ){
      
            $_[] = $v->Name;
          }
        }// o : armo datos por estructura
        elseif( $ope == 'ver' ){
      
          if( in_array('vis',$opc) ){ 
            $ver []= "`Comment` = 'VIEW'"; 
          }
          elseif( in_array('tab',$opc) ){ 
            $ver []= "`Comment` <> 'VIEW'"; 
          }    
          $ver = !empty($ver) ? " WHERE ".implode(' AND ',$ver) : '';
      
          foreach( _sql::dec("SHOW TABLE STATUS FROM `{$esq}`{$ver}") as $v ){ 
            $_est = new stdClass();
            $_est->esq = $esq;
            $_est->ide = $v->Name;
            $_est->nom = $v->Comment;
            $_est->fec = $v->Create_time;
            $_[$_est->ide] = $_est;
          }
          // devuelvo uno solo
          if( !empty($ide) && $opc_uni && isset($_est) ){
            $_ = $_est;
          }
        }
      }
      return $_;
    }
    // atributos
    static function atr( string $est, string $ope='ver', ...$opc ) : array | object | string {
      $_=[];
      $esq = DAT_ESQ;
      $dat_lis = _sql::dec("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");
      if( isset($dat_lis['_err']) ){
        $dat_lis = _sql::dec("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");
      }
      if( $ope == 'lis' ){
        foreach( $dat_lis as $atr ){
          $_[] = $atr->Field;
        }
      }
      elseif( $ope == 'ver' ){
        $_var = _api::_('dat_tip');
        $pos = 0;    
        // si existe una vista, veo esas columnas...
        foreach( $dat_lis as $i => $atr ){
          $pos++;      
          $_tip = explode('(',$atr->Type);      
          if( isset($_tip[1]) ) $var_cue = explode(')',$_tip[1])[0]; 
          $_var_atr = $_var[$sql_tip = $_tip[0]];
          $var_dat = $_var_atr->dat;
          $var_val = $_var_atr->val;
          $dat_tip = "{$var_dat}_{$var_val}";
          // operador automático
          $var = !empty($_var_atr->ope) ? $_var_atr->ope : [];
          // tipo de variable
          $var['_tip'] = $dat_tip;
          if( empty($var_cue) && isset($var['maxlength']) ){
            $var_cue = $var['maxlength'];
          }
          // tipo de dato
          if( $var_dat == 'num' ){
            // booleano
            if( $atr->Type == 'tinyint(1)' ){
              if( isset($var['min']) ) unset($var['min']);
              if( isset($var['max']) ) unset($var['max']);
              if( isset($var['step']) ) unset($var['step']);
              if( isset($var['maxlength']) ) unset($var['maxlength']);              
              $dat_tip = $var['_tip'] = 'opc_bin';              
              $var_dat = "opc";
              $var_val = "bin";
              $var_cue = NULL;
            }// autoincremental
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
          }      
          elseif( $var_dat == 'tex' ){
            if( preg_match("/_bin/", $atr->Collation) ){ 
              $var['_tip'] = "dir_bit"; 
            }
          }
          elseif( $var_dat == 'fec' ){
            // valor por defecto
            if( preg_match("/CURRENT_TIMESTAMP/",$atr->Extra) ){ 
              $var['fec_ini'] = 1; 
              // actualizar al modificar
              if( preg_match("/on update/",$atr->Extra) ){ 
                $var['fec_act'] = 1; 
              }
            }
          }
          elseif( $var_dat == 'opc' ){
            $var['dat'] = $var_cue = _obj::dec($var_cue);
          }
          // valores
          if( !is_null($atr->Default) ){
            $var['val'] = $atr->Default;
          }
          if( isset($var_cue) && !is_array($var_cue) ){
            $var['maxlength'] = $var_cue;
          }
          if( isset($var['fec_ini']) || isset($var['fec_act']) || isset($var['num_inc']) ){
            $var['val_ope'] = 0;
            $var['disabled'] = 1;
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
    }
    // indice
    static function ind( string $ide, string $ope = 'ver', ...$opc ) : array | object | string {
      $_ = [];
      $ide = explode('.',$ide);
      $esq = $ide[0];
      $est = $ide[1];

      switch( $ope ){
      case 'ver': 
        if( !empty($ide = $opc[0]) ){
          foreach( _sql::dec("SHOW KEYS FROM `$esq`.`$est` WHERE `Key_name` = '".( $ide == 'pri' ? "PRIMARY" : $ide )."'") as $key ){

            $_[] = $key->Column_name;
          }
        }      
        break;
      case 'lis': 
        break;
      case 'agr':
        // ALTER TABLE `$est` ADD PRIMARY KEY;<br>
        break;
      case 'eli':
        // ALTER TABLE `$est` DROP PRIMARY KEY;<br>
        break;        
      }

      return $_;
    }
    // registros
    static function reg( string $tip, string $ide, mixed $ope=[] ) : array | object | string {
      $_ = [];

      $e = _sql::cod($ide,$ope, ( $tip == 'cue' ) ? 'ver' : $tip );

      switch( $tip ){
      case 'ver': 
        $_ = _sql::dec("SELECT {$e['atr']} FROM {$e['est']}{$e['jun']}{$e['ver']}{$e['ord']}");
        break;
      case 'cue':  
        $_ = _sql::dec("SELECT COUNT( {$e['atr']} ) AS `cue` FROM {$e['est']}{$e['ver']}")[0]['cue'];
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

  // Dato : (api).esq.est[ide].atr
  class _dat {

    // getter : estructura - objeto
    static function get( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

      // objeto->propiedad 
      if( is_string($dat) && is_string($ope) ){

        $esq = $dat;
        $est = $ope;        
        // busco datos por $clase::_($identificador)
        $_ = isset($val) ? $val : new stdClass;
        if( ( !isset($val) || !_obj::tip($val) ) && class_exists($_cla = "_$esq") && method_exists($_cla,'_') ){

          $_ = !isset($val) ? $_cla::_($est) : $_cla::_($est,$val);
        }
      }// estructuras de la base
      else{
        $_ = $dat;
        // datos de la base 
        if( is_string($ide = $dat) ){

          // ejecuto consulta
          $_ = _sql::reg('ver',$ide,isset($ope) ? $ope : []);

          if( isset($ope) ){
            // elimino marcas
            foreach( ['ver','jun','gru','ord','lim'] as $i ){

              if( isset($ope[$i]) ) unset($ope[$i]);
            }
            // busco clave primaria
            if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){
              
              $ope['niv'] = _sql::ind($ide,'ver','pri');
            }
          }
        }
        // resultados y operaciones
        if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) ) _lis::ope($_,$ope);

      }
      return $_;
    }
    // identificadores
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
    // tipo : dato + valor
    static function tip( mixed $val ) : bool | object {
      $_ = FALSE;
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
      
      global $_api;
      if( isset( $_api->dat_tip[$ide] ) ){
        $_ = $_api->dat_tip[$ide];
      }
      return $_;
    }
    // inicio estructura: busco datos por vista o tabla
    static function ini( string $esq, string $est, array $ope = [] ) : string | array {
      $_ = [];

      $val_est = _sql::est('val',$est);

      $vis = "_{$est}";

      $val_vis = _sql::est('val',$vis);

      if( $val_est || $val_vis ){

        $ide = ( $val_vis == 'vis' ) ? $vis : $est;

        $_ = _dat::get("{$esq}.{$ide}",$ope);
      }

      return $_;
    }
    // estructura : datos + operadores
    static function est( string $esq, string $ide, mixed $tip = NULL, mixed $ope = NULL ) : mixed {
      $_ = [];
      global $_api;
      // cargo una estructura
      if( !isset($tip) ){

        if( !isset($_api->dat_est[$esq][$ide]) ){

          $_api->dat_est[$esq][$ide] = _sql::est('ver',"{$esq}_{$ide}",'uni');
        }
        $_ = $_api->dat_est[$esq][$ide];
      }
      else{
        switch( $tip ){
        }
      }
      return $_;
    }
    // atributo : datos + tipo + variable
    static function atr( string $esq, string $est, mixed $ide = NULL, string $tip = NULL, mixed $ope = NULL ) : mixed {
      $_ = [];
      global $_api;            
      // cargo atributos de la estructura
      if( !isset($_api->dat_atr[$esq][$est]) ){
        
        // busco atributos de una vista ( si existe ) o de una tabla
        $sql_ide = "{$esq}_{$est}";
        $_api->dat_atr[$esq][$est] = _sql::atr( !empty( _sql::est('lis',"_{$sql_ide}",'uni') )  ? "_{$sql_ide}" : $sql_ide );

        // cargo operadores del atributo
        $dat = &$_api->dat_atr[$esq][$est];

        if( $dat_atr = _app::dat($esq,$est,'atr') ){

          foreach( $dat_atr as $i => $v ){
          
            if( isset($dat[$i]) ) $dat[$i]->var = _ele::jun($dat[$i]->var, _obj::nom($v));
          }
        }
      }
      $_ = $_api->dat_atr[$esq][$est];
      // devuelvo todos los atributos
      if( isset($ide) ){
        $_atr = $_;
        $_ = [];
        // devuelvo 1-n atributos
        if( !isset($tip) ){
          // uno
          if( is_string($ide) ){

            if( isset($_atr[$ide]) ) $_ = $_atr[$ide];
          }// muchos
          else{
            foreach( $ide as $atr ){ 

              if( isset($_atr[$atr]) ) $_[$atr] = $_atr[$atr];
            }
          }
        }
        else{
          switch( $tip ){
          }
        }
      }
      return $_;
    }// genero atributos desde listado o desde la base
    static function atr_ver( string | array $dat, string $ope = "" ) : array {
      $_ = [];
      if( empty($ope) ){
        // de la base
        if( is_string($dat) ){        
          $ide = _dat::ide($dat);
          $_ = _dat::atr($ide['esq'],$ide['est']);
        }
        // listado variable por objeto
        else{
          foreach( $dat as $ite ){
            // del 1° objeto: cargo atributos
            foreach( $ite as $ide => $val ){ 
              $atr = new stdClass;
              $atr->ide = $ide;
              $atr->nom = $ide;
              $atr->var = _dat::tip($val);
              $_ [$ide] = $atr;
            }
            break;
          }        
        }
      }
      return $_;
    }// cuento columnas totales
    static function atr_cue( string | array $dat, array $ope=[] ) : int {
      $_ = 0;
      
      // atributos
      if( isset($ope['atr']) ){
        
        $_ = count($ope['atr']);
      }
      // joins
      elseif( isset($ope['est']) ){
        foreach( $ope['est'] as $esq => $est_lis ){  
          foreach( $est_lis as $est ){
            $dat_est = _app_est::dat($esq,$est,$ope);
            $_ += count($dat_est['atr']);
          }
        }
      }// 1 estructura de la base
      elseif( !( $obj_tip = _obj::tip($dat) ) ){

        $ide = _dat::ide($dat);

        $dat_est = _app_est::dat($ide['esq'],$ide['est']);

        $_ = isset($dat_est['atr']) ? count($dat_est['atr']) : 0;

      }
      // por listado                    
      elseif( $obj_tip == 'pos' ){

        foreach( $dat as $ite ){

          foreach( $ite as $val ){ 
            $_ ++; 
          }
          break;
        }
      }
      return $_;
    }
    // proceso abm : alta , modificacion y baja de registro-objeto
    static function reg( string $est, string $tip, object $dat ) : string {
      $_="";
      $_sql = [];
      // ejecuto transacciones    
      $var_eve = [];
      foreach( $_sql as $est => $ope ){ 

        $eje []= _sql::reg( $tip, $est, $ope);
      }
      if( !empty($eje) ){
        $_ = _sql::dec( ...$eje );
      }
      return $_;
    }
    // relaciones : esq.est_atr | api.dat_atr[ide].dat
    static function rel( string $esq, string $est, string $atr ) : string {
      $_ = '';
      // armo identificador por nombre de estructura + atributo
      if( $atr == 'ide' ){
        $_ = $est;
      }
      // parametrizado en : $_app.dat_atr
      elseif( ( $_atr = _dat::atr($esq,$est,$atr) ) && !empty($_atr->var['dat']) ){        
        $_ = explode('.',$_atr->var['dat'])[1];
      }
      // valido existencia de tabla relacional : "_api.esq_est_atr"
      elseif( !!_sql::est('val',"{$esq}_{$est}_{$atr}") ){ 
        $_ = "{$est}_{$atr}";
      }
      else{
        $_ = $atr;
      }
      return $_;
    }
    // seleccion : imagen, color...
    static function opc( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
      // dato
      $_ = [ 'esq' => $esq, 'est' => $est ];
      if( !empty($atr) ){
        // armo identificador
        $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";  
        // busco dato en atributos
        $_atr = _dat::atr($esq,$est,$atr);
        if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
          $dat = explode('.',$var_dat);
          $_['esq'] = $dat[0];
          $_['est'] = $dat[1];
        }
      }
      // valido dato
      if( !empty( $dat_Val = _app::dat($_['esq'],$_['est'],"val.$tip",$dat) ) ){
        $_['ide'] = "{$_['esq']}.{$_['est']}";
        $_['val'] = $dat_Val;
      }
      else{
        $_ = [];
      }
      return $_;
    }
  }
  // Valores
  class _val {
    
    // comparaciones de valores
    static function ver( $dat, string $ide, $val ) : bool {
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
  }
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
  // Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
  class _obj {

    // valor : ()($)atr_ide()
    static function val( object | array $dat, string $val='' ) : string {
      $_ = [];
      $val_arr = _obj::tip($dat) == 'nom';
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
    }
    // convierto a string: {} => ""
    static function cod( object | array | string $dat ) : string {
      $_ = [];
      
      if( is_array($dat) || is_object($dat) ){
        // https://www.php.net/manual/es/function.json-encode.php
        // https://www.php.net/manual/es/json.constants.php
        $_ = json_encode( $dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT );
      }
      return $_;
    }
    // convierto a objeto : "" => {}/[]
    static function dec( object | array | string $dat, array | object $ope = NULL, ...$opc ){
      $_ = $dat;
      // convierto : "" => {}
      if( is_string($dat) ){  
        // busco : ()($)atributo-valor()
        if( !empty($ope) && preg_match("/\(\)\(\$\).+\(\)/",$dat) ){
          $dat = _obj::val($ope,$dat);
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
    
          $_ = _dat::get($dat,$ope);
          
        }
      }// convierto : {} => []
      elseif( in_array('nom',$opc) && is_object($dat) && get_class($dat)=='stdClass' ){    
        $_ = _obj::nom($dat);
      }
      return $_;
    }
    // combino
    static function jun( array | object $dat, array | object $ope ) : array | object {
            
      $val_obj = is_object($_ = $dat);      

      foreach( $ope as $i => $v ){

        if( $val_obj ? isset($_->$i) : isset($_[$i]) ){

          $val_ite = $val_obj ? $_->$i : $_[$i];

          $val = _obj::tip($v) ? _obj::jun($v,$val_ite) : $val_ite;

          if( $val_obj ){ $_->$i = $val; }else{ $_[$i] = $val; }

        }
        else{

          if( $val_obj ){ $_->$i = $v; }else{ $_[$i] = $v; }          
        }
      }
      return $_;
    }
    // tipos : pos | nom | atr
    static function tip( mixed $dat ) : bool | string {
      
      $_ = FALSE;

      if( _obj::pos($dat) ){

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
    // posicion : [ # => $$ ]
    static function pos( mixed $dat, string $tip = NULL, mixed $ope = NULL ) : bool | array {
      $_ = [];
      if( !isset($tip) ){
        // valido tipo : []
        $_ = is_array($dat) && array_keys($dat) === range( 0, count( array_values($dat) ) - 1 );
      }
      else{
        switch( $tip ){
        }
      }
      return $_;
    }    
    // nombre : [ ..."" => $$ ]
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
          if( empty($ope = _lis::ite($ope)) ){

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
    // objeto : { ..."" : $$ }
    static function atr( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
      $_ = $dat;

      if( !isset($tip) ){
        // listado de objetos
        if( _obj::pos($dat) ){
          
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
          if( empty($ope = _lis::ite($ope)) ){

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
  // Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
  class _ele {

    // {} => "<>"
    static function val( ...$ele ) : string {
      $_ = "";
      foreach( $ele as $ele ){
          
        if( is_string($ele) ){
    
          $_ .= $ele;
        }
        else{
          $ele = _obj::dec($ele,[],'nom');
          // operador
          if( isset($ele['_let']) ){
            $htm = $ele['_let'];
            unset($ele['_let']);
            $_ .= _app::let($htm,$ele);
          }
          // por icono
          elseif( isset($ele['ico']) ){
            $_ .= _app::ico($ele['ico'],$ele);
          }
          // por imagen
          elseif( isset($ele['ima']) ){
            $est = explode('.',$ele['ima']);
            array_push($est,!empty($ele['ide'])?$ele['ide']:0,$ele);
            $_ .= _app::ima(...$est);
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
            if( class_exists( $cla_ide = "_app_".array_shift($tip) ) ){

              $_ = $cla_ide::ope( empty($tip) ? 'val' : implode('_',$tip), $val, $ele );
            }
            else{
              $_ = "<span class='err' title='no existe el operador $cla_ide'></span>";
            }                    
          }
          // por etiqueta
          else{
            $_ .= _ele::eti($ele);
          }
        }
      }
      return $_;
    }// "<>" => {}
    static function dec( string | array $ele, array | object $dat = NULL ) : array {
      $_ = $ele;
      // convierto "" => []
      if( is_string($ele) ){
        $_ = _obj::dec($ele,$dat,'nom');
      }
      // convierto {} => []
      elseif( is_object($ele) ){
        $_ = _obj::nom($ele);
      }
      // proceso atributos con variables : ()($)nom()
      elseif( is_array($_) && isset($dat) ){

        foreach( $_ as &$atr ){

          if( is_string($atr) ){ // && preg_match("/\(\)\(\$\).*\(\)/",$atr) 

            $atr = _obj::val($dat,$atr);            
          }
        }
      }
      return $_;
    }// combino elementos
    static function jun( string | array $ele, array $lis, array $ope = [] ) : array {
      // proceso opciones
      $dat = isset($ope['dat']) ? $ope['dat'] : NULL;
      // si es "", convierto a []
      $_ = _ele::dec($ele,$dat);
      // recorro 2ºs elementos
      foreach( _lis::ite($lis) as $ele ){
        // recorro atributos
        foreach( _ele::dec($ele,$dat) as $atr => $val ){
          // agrego
          if( !isset($_[$atr]) ){
            $_[$atr] = $val;
          }
          // actualizo
          else{
            switch($atr){
            case 'onclick':   _ele::eje($_,'cli',$val); break;
            case 'onchange':  _ele::eje($_,'cam',$val); break;
            case 'oninput':   _ele::eje($_,'inp',$val); break;
            case 'class':     _ele::cla($_,$val); break;// agrego con separador: " "
            case 'style':     _ele::css($_,$val); break;// agrego con separador: ";"
            default:          $_[$atr] = $val; break;// reemplazo
            }
          }
        }
      }
      return $_;
    }// devuelvo atributos : "< ...atr="">"
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
    }// armo etiqueta : <eti atr="val" >...htm</eti>
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
        foreach( _lis::ite($htm) as $ele ){
          $_htm .= is_string($ele) ? $ele : _ele::val($ele);
        }
        $htm = $_htm;
      }
      $_ = "
      <{$eti}"._ele::atr($ele).">
        ".( !in_array($eti,['input','img','br','hr']) ? "{$htm}
      </{$eti}>" : '' );
      return $_;
    }// contenido: htm + htm_ini + htm_med + htm_fin
    static function htm( array &$ele ) : array {
      $_=[];
      foreach( ['htm','htm_ini','htm_med','htm_fin'] as $tip ){
        if( isset($ele[$tip]) ){
          if( is_string($ele[$tip]) ){
            $_[$tip] = $ele[$tip];
          }else{
            $_[$tip] = _ele::val($ele[$tip]);
          }
          unset($ele[$tip]);
        }
      }
      return $_;
    }// fondos
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
    }// clases
    static function cla( array &$ele, mixed $val = NULL, ...$opc ) : array {
      $_ = $ele;
      if( !isset($val) ){

        $_ = [];
    
        $ele = _obj::dec($ele,[],'nom');
    
        if( isset($ele['class']) ){
    
          foreach( explode(' ',$ele['class']) as $val ){ 
    
            if( !empty($val) ) $_[] = trim($val);
          }
        }
      }// operaciones
      else{
        
        if( in_array('eli',$opc) ){

          foreach( _lis::ite($val) as $v ){
            
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
    }// estilos
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

          $css = _ele::css($ele);

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
  // Archivo : fichero + texto + imagen + audio + video + app + ...tipos
  class _arc {  

    static function val( mixed $dat ) : mixed {
      $_=FALSE;
      // remoto
      if( is_link($dat) ){
        header("Location: {$dat}"); // -> llama a la pagina
      }
      // local: carpeta
      elseif( is_dir($dat) ){ 
        $_ = scandir($dat); 
      }
      // local: archivo
      elseif( file_exists($dat) ){ 
        $_=[]; 
        $val = opendir($dat); 
        while( $tex = readdir($val) ){ 
          $_[] = $tex; 
        } 
        closedir($val);
      }
      return $_;
    }

    static function tip( $tip ){
      $_ = "";
      switch( $tip ){
      case 'ima': $_ = ""; break;
      case 'mus': $_ = ""; break;
      case 'vid': $_ = ""; break;
      case 'tex': $_ = ""; break;
      case 'tab': $_ = ""; break;
      case 'eje': $_ = ""; break;
      }
      return $_;
    }    
    // contenido html : valido archivo
    static function ide( string $ide, array $arc = [ 'html', 'php' ] ) : string {

      $_ = '';

      foreach( $arc as $tip ){

        if( file_exists( $rec = "{$ide}.{$tip}" ) ){

          $_ = $rec;

          break;
        }        
      }
      return $_;
    }
  }
  // listado / tabla : [ ... ]
  class _lis {

    // aseguro iteraciones 
    static function ite( mixed $dat, mixed $ope = NULL ) : array {

      $_ = [];

      if( empty($ope) ){

        $_ = _obj::pos($dat) ? $dat : [ $dat ];
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

      if( _obj::tip($dat) ){

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
        _lis::jun($dat, $ope['jun'], ...$opc);
      }
      // ejecuto filtro
      if( isset($ope['ver']) ){
        _lis::ver($dat, $ope['ver'], ...$opc);
      }      
      // genero elementos
      if( isset($ope['ele']) ){
        _lis::dec($dat, $ope['ele'], 'nom');
      }
      // genero objetos  
      if( isset($ope['obj']) ){
        _lis::dec($dat, $ope['obj'] );
      }
      // nivelo estructura
      if( isset($ope['niv']) ){
        _lis::niv($dat, $ope['niv'] );
      }// o por indice
      elseif( isset($ope['nav']) && is_string($ope['nav']) ){
        _lis::nav($dat, $ope['nav'] );
      }
      // reduccion por atributo
      if( isset($ope['red']) && is_string($ope['red']) ){
        _lis::red($dat, $ope['red'] );
      }      
      // devuelvo unico objeto
      if( isset($ope['opc']) ){

        $ope['opc'] = _lis::ite($ope['opc']);

        if( in_array('uni',$ope['opc']) ) _lis::uni($dat, ...$opc );
      }
      return $dat;
    }
    // decodifica : "" => {} , []
    static function dec( array &$dat, string | array $atr, ...$opc ) : array {

      $atr = _lis::ite($atr);

      foreach( $dat as &$ite ){

        if( is_object($ite) ){

          foreach( $atr as $ide ){

            if( isset($ite->$ide) ){

              $ite->$ide = _obj::dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);            
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
              $val_ite []= _val::ver( $val, $ver[1], $ver[2] );
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
      $lis_tip = _obj::pos($dat);
      
      foreach( $dat as $i => $v ){

        if( !_val::ver( $opc_ide ? $i : $v, $ope, $val ) ) 
        
          $_[ $lis_tip ? $pos : $i ] = $v;

        $pos++;
      }
      
      return $dat = $_;
    }    
  }
  // Texto : caracter + letra + oracion + parrafo
  class _tex {
    
    // salvo caracteres con "\"
    static function cod( string $val, string $cod = ".*+\-?^{}()|[\]\$\\", string $opc = "g" ) : string {
    
      return preg_replace("/[{$cod}]/{$opc}",$val,'\\$&');// $& significa toda la cadena coincidente
    }
    // devuelvo longitud
    static function cue( string $val, string $cod = "UTF-8") : int {
    
      return mb_strlen($val, $cod);
    }
    // extraigo subcadena
    static function ver( string $val, int $ini, int $fin, string $cod="UTF-8" ) : string {
    
      return mb_substr($val, $ini, $fin, $cod);
    }
    // relleno por lado con valor repetitivo
    static function agr( int | float | string $val, int $cue = 1, string $rep = ' ', string $lad = 'izq' ) : string {
    
      return str_pad( $val, $cue, $rep, $lad == 'izq' ? STR_PAD_LEFT : STR_PAD_RIGHT );
    }
    // modifico subcadena
    static function mod( string $dat, string $ver, string $val ) : string {

      return str_replace($ver,$val,$dat);
    }
    // separador con acentos y caracteres especiales
    static function let( string $val, int $gru = 0, string $cod = "UTF-8" ) : array {
      // => https://diego.com.es/extraccion-de-strings-en-php    
      if( $gru > 0 ) {
        $_ = []; $len = mb_strlen($val, $cod);
        for ( $i = 0; $i < $len; $i += $gru ) { $_[] = mb_substr($val, $i, $gru, $cod); }
        return $_;
      }
      return preg_split("//u", $val, -1, PREG_SPLIT_NO_EMPTY);
    }
    // todo a minúsculas
    static function let_min( string $val, string $cod = "UTF-8" ) : string {

      return mb_strtolower($val, $cod);
    }
    // todo a mayúsculas
    static function let_may( string $val, string $cod = "UTF-8" ) : string {

      return mb_strtoupper($val, $cod);
    }
    // capitalizar todas las palabras
    static function let_pal( string $val, string $cod = "UTF-8" ) : string {    

      return ucwords( mb_strtolower($val, $cod) );
    }
    // Capitalizar primer palabra
    static function let_ora( string $val, string $cod = "UTF-8" ) : string {
    
      return ucfirst( mb_strtolower($val, $cod) );
    }
    // extraigo : de - el/a
    static function art( string $val ) : string {
      $_ = "";

      $pal = explode(' ',$val);

      if( in_array($pal[0],['de','del']) ) array_shift($pal);

      if( in_array($pal[0],['la','las','el','lo','los','ellos','ella','ellas']) ) array_shift($pal);

      if( isset($pal[0]) ) $_ = $pal[0];

      return $_;
    }
    // agrego : de - de la
    static function art_del( string $val ) : string {
      
      $_ = explode(' ',$val);

      $_[0] = ( strtolower($_[0]) == 'la' ) ? 'de la' : 'del';

      $_ = implode(' ',$_);

      return $_;
    }
    // convierto género : artículo - terminacion
    static function art_gen( string $val, string $pal = 'o' ) : string {
      $_ = trim($val);
      $pal = trim($pal);
      $art = explode(' ',$pal);
      // por articulo
      if( isset($art[1]) ){

        if( $art[0]=='de' ) array_shift($art);

        $pal = $art[0];

        if( ( in_array($pal,['el','del','lo','los','él','ellos']) ) && preg_match("/as?$/", $_) ){

          $_ = preg_match("/as$/", $_) ? preg_replace("/as$/","os",$_) : preg_replace("/a$/","o",$_);
        }
        elseif( ( in_array($pal,['la','las','ella','ellas']) ) && preg_match("/[oe]s?$/", $_) ){

          $_ = preg_match("/[oe]s$/", $_) ? preg_replace("/[oe]s$/","as",$_) : preg_replace("/o$/","a",$_);
        }
      }// por palabra
      elseif( preg_match("/[oe]s?$/",trim($pal)) && preg_match("/as?$/", $_) ){

        $_ = preg_match("/as$/", $_) ? preg_replace("/as$/","os",$_) : preg_replace("/a$/","o",$_);
      }
      elseif( preg_match("/os?$/",trim($pal)) && preg_match("/as?$/", $_) ){

        $_ = preg_match("/[oe]s$/", $_) ? preg_replace("/[oe]s$/","as",$_) : preg_replace("/o$/","a",$_);
      }
      return $_;
    }
  }
  // Numero : separador + operador + entero + decimal + rango
  class _num {

    // datos del objeto
    static function dat( int | float | string $ide, string $atr='' ) : object | string {
      $_num_int = _api::_('num_int');
      
      // aseguro valor
      if( is_string($ide) ) $ide = _num::val($ide);
      
      // busco datos
      $_ = isset($_num_int[$ide]) ? $_num_int[$ide] : new stdClass();
      
      // devuelvo atributo
      if( !empty($atr) ) $_ = isset($_->$atr) ? $_->$atr : "";

      return $_;
    }
    // devuelvo valor con "0-" o numérico : int | float
    static function val( mixed $dat, int $tot = 0 ) : string | int | float {
      $_ = $dat;
      if( !empty($tot) ){

        $_ = _tex::agr( abs( $dat = _num::val($dat) ), $tot, "0");

        if( $dat < 0 ) $_ = "-".$_;
      }
      // parse-int o parse-float
      elseif( is_string($dat) ){

        $_ = preg_match("/\d+,\d+$/",$dat) ? floatval($dat) : intval($dat);
      }
      return $_;
    }
    // formato de bits
    static function bit( $dat, $ini='KB', $fin='MB' ) : string {
      $_ = floatval($dat); 
      $ini = strtoupper($ini); 
      $fin = strtoupper($fin);
      $_bit = [
        'B' =>1, 
        'KB'=>1024, 
        'MB'=>1048576, 
        'GB'=>1073741824, 
        'TB'=>1099511627776, 
        'PB'=>1125899906842624, 
        'EB'=>1152921504606846976, 
        'ZB'=>1180591620717411303424, 
        'YB'=>1208925819614629174706176
      ];
      if( is_numeric($_) && isset($_bit[$ini]) && isset($_bit[$fin]) ){      
        $_ = $_ * $_bit[$ini];// normalizo a bytes : <-
        $_ = $_ / $_bit[$fin];// convierto a valor final : ->
        if( is_numeric($_) && $_ >= 0 ){ 
          $_ = "<n class='bit'>".number_format($_,2,',','.')."</n><font class='ide'>{$fin}</font>";
        }else{ $_ = "
          <p><font class='err'>Error de conversión</font><c>:</c> {$_}</p>";
        }
      }// error de conversion
      else{ $_ = "
        <p>
          <font class='err'>Error de tipos</font><c>:</c>{$_}
          <br>
          <c class='sep'>-></c>{$ini}<c class='sep'>-></c>{$fin}
        </p>";
      }
      return $_;
    }
    // formato entero : ...mil.num
    static function int( string | float | int $dat, string $mil = '.' ) : string {
      
      return number_format( intval($dat), 0, ( $mil == '.' ) ? ',' : '.', $mil );
    }
    // formato decimal : ...num,dec
    static function dec( string | float | int $val, int $dec = 2, string $sep = ',' ) : string {
      
      return number_format( floatval($val), intval($dec), $sep, ( $sep == ',' ) ? '.' : ','  );
    }
    // reduzco a valor dentro del rango
    static function ran( string | float | int $num, int | float $max, int | float $min = 1 ) : int | float {
      
      $_ = is_string($num) ? _num::val($num) : $num;
      
      while( $_ > $max ){ $_ -= $max; } 
      
      while( $_ < $min ){ $_ += $max; } 
      
      return $_;
    }
    // sumatorias
    static function sum( int | float | string | array $val ) : int | float {

      if( !is_array($val) ) $val = explode(',', is_string($val) ? $val : strval($val) );

      return array_reduce( $val,
      
        function( $acu, $ite ){
          
          return $acu += _num::val($ite); 
        }
      );
    }
    // redondeos
    static function red( mixed $val, int $dec = 0, string $tip = 'min' ) : mixed {
      // https://www.php.net/manual/es/function.round.php
      return round($val, $dec, $tip == 'min' ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP );
    }
  }
  // Fecha : aaaa-mm-dia hh:mm:ss utc
  class _fec {    
    // get : estructura-objetos
    static function _( string $ide, $val = NULL ) : string | array | object {
      global $_api;
      $_ = [];
      // aseguro carga      
      $est = "fec_$ide";
      if( !isset($_api->$est) ){
        $_api->$est = _dat::ini(DAT_ESQ,$est);
      }// cargo datos
      $_dat = $_api->$est;
      
      if( !empty($val) ){
        $_ = $val;
        if( !is_object($val) ){
          switch( $ide ){
          case 'dat':
            $_ = _fec::dat($val);
            break;
          default:
            if( is_numeric($val) ){
              $ide = intval($val)-1;
              if( isset($_dat[$ide]) ) $_ = $_dat[$ide];
            }
            elseif( isset($_dat[$val]) ){ 
              $_ = $_dat[$val];
            }
            break;
          }
        }
      }// toda la lista
      elseif( isset($_dat) ){
        $_ = $_dat;
      }
      return $_;
    }
    // codifico fecha : [ año, mes, dia ]
    static function cod( string $val, string $sep = NULL ) : array {
      $_ = [];

      $val = explode(' ',str_replace('T','',$val))[0];

      if( empty($sep) ){
        $sep = preg_match("/-/",$val) ? '-' : ( preg_match("/\//",$val) ? '/' : '.' );
      }

      $dat = array_map( function($v){ return intval($v); }, explode($sep,$val) );

      if( strlen( strval($dat[0]) ) == 4 || $dat[0] > 31 ){

        $_ = [ $dat[0], $dat[1], $dat[2] ];
      }
      else{        
        $_ = [ $dat[2], $dat[1], $dat[0] ];
      }
      return $_;
    }
    // objeto: DateTime
    static function dec( int | string | object | array $dat = NULL ) : DateTime | string {
      $_ = $dat;
      if( empty($dat) ){
        $_ = new DateTime('NOW');
      }
      else{
        if( is_numeric($dat) && preg_match("/^\d+$/",strval($dat)) ){
          try{ 
            $_ = new DateTime( intval($dat) );
          }
          catch( Throwable $_err ){ 
            $_ = "<p class='err'>{$_err}</p>"; 
          }
        }
        elseif( is_string($dat) ){ 
          try{ 
            $_ = _fec::dat($dat);
            $_ = !! $_ ? new DateTime( "{$_->año}-{$_->mes}-{$_->dia}" ) : new DateTime('NOW');
          }
          catch( Throwable $_err ){ 
            $_ = "<p class='err'>{$_err}</p>"; 
          }
        }
        elseif( is_object($dat) ){

          if( get_class($dat)=='stdClass' ){
            $_ = new DateTime( "{$dat->año}-{$dat->mes}-{$dat->dia}" );
          }
          else{
            $_ = $dat;
          }
        }
        elseif( is_array($dat) ){
          $_ = new DateTime( "{$dat[0]}-{$dat[1]}-{$dat[2]}" );
        }
      }
      return $_;
    }
    // devuelvo por tipo
    static function tip( mixed $dat, $tip = NULL ) : bool | string {
      $_ = FALSE;

      if( empty($tip) ){
        $_ = $dat;
        if( is_string($dat) ) $_ = _fec::dat($dat);
      }
      else{
        $_fec = $dat;
        // aseguro objeto nativo
        if( !is_object($dat) || get_class($dat)=='stdClass' ) $_fec = _fec::dec($dat); 
        // busco tipo
        switch( $tip ){
        case 'dyh': $_ = $_fec->format('Y/m/d H:i:s');  break;
        case 'hor': $_ = $_fec->format('H:i:s');        break;
        case 'sem': $_ = $_fec->format('w');            break;
        case 'dia': $_ = $_fec->format('Y/m/d');        break;
        }
      }
      return $_;
    }
    // objeto fecha: { val, año, mes, dia, sem, hor, min, seg, ubi }
    static function dat( string $val, ...$opc ) : object {

      $_ = new stdClass();
      $_->val = "";
      $_->dia = 0;
      $_->mes = 0;
      $_->año = 0;
      $_->tie = "";
      $_->hor = 0;
      $_->min = 0;
      $_->seg = 0;
      $_->ubi = "";

      // separo valores
      $val = explode( preg_match("/T/i",$val) ? 'T' : ' ', $val );

      if( isset($val[0]) ){

        $fec = explode( preg_match("/-/",$val[0]) ? '-' : '/', $val[0] );

        if( isset($fec[2]) ){
          // mes
          $_->mes = intval($fec[1]);
          // año
          if( strlen($fec[0]) > 2 ){
            $_->año = intval($fec[0]);    
            $_->dia = intval($fec[2]);    
          }else{
            $_->año = intval($fec[2]);    
            $_->dia = intval($fec[0]);
          }  
          // valido fecha resultante
          if( $_->val = _fec::val($_,...$opc) ){
            // busco valor semanal
            $_->sem = _fec::tip($_,'sem');
            // proceso horario
            if( isset($val[1]) ){
              $hor = explode(':', $_->tie = $val[1]);
              // segundos
              if( isset($hor[2]) ) $_->seg = intval($hor[2]);
              // minutos
              if( isset($hor[1]) ) $_->min = intval($hor[1]);
              // horas
              $_->hor = intval($hor[0]);
            }        
          }
        }
      }    
      return $_;
    }
    // validor de fecha : "año/mes/dia" | "dia/mes/año"
    static function val( object $dat, ...$opc ) : bool | string {
      $_ = FALSE;
    
      $año = !empty($dat->año) ? $dat->año : 1900;
      $mes = !empty($dat->mes) ? $dat->mes : 1;
      $dia = !empty($dat->dia) ? $dat->dia : 1;
  
      if( checkdate($mes, $dia, $año) ){
        
        $_ = !in_array('año',$opc) ? _num::val($dia,2).'/'._num::val($mes,2).'/'._num::val($año,4) : _num::val($año,4).'/'._num::val($mes,2).'/'._num::val($dia,2);
      }

      return $_;
    }
    // formateo para input : "aaa-mm-ddThh:mm:ss"
    static function var( string $val = NULL, string $tip = 'dia' ) : string {
      $_ = "";
  
      if( !empty($val) ){

        if( ( $tip == 'dia' || $tip == 'dyh' ) ){

          $_fec = _fec::dat($val,'año');

          $val = $_fec->val;
        }

        $_ = str_replace(' ','T',str_replace('/','-',$val));
      }
      
      return $_;
    }
    // operaciones numericas por tipos
    static function ope( string | object $dat, int $val = 0, string $ope = '+', string $tip = 'dia' ){
      $_ = $dat;
      
      if( is_object($dat) ){

        $_ = "{$dat->año}-{$dat->mes}-{$dat->dia}";
      }
      elseif( is_string($dat) ){

        $_ = str_replace('/','-',$dat);
      }

      if( !!$_ ){
        $tie = '';
        switch( $tip ){
        case 'seg': $tie = 'second'; break;
        case 'min': $tie = 'minute'; break;
        case 'hor': $tie = 'hour';   break;
        case 'dia': $tie = 'day';    break;
        case 'sem': $tie = 'week';   break;
        case 'mes': $tie = 'month';  break;
        case 'año': $tie = 'year';   break;
        } 
        if( $val > 1 ) $tie .= "s";
        // strtotime devuelve un timestamp        
        $_ = date( 'd-m-Y', strtotime( $ope.strval($val)." $tie", strtotime($_) ) );
      }
      return $_;
    }
    // cuento dias pos periodo : mes | año
    static function cue( string $tip, string | object $val ) : mixed {

      $_ = is_string($val) ? _fec::dat($val) : $val;

      switch( $tip ){
      case 'mes':
        if( !!$_ ){
          $_ = cal_days_in_month( CAL_GREGORIAN, $_->mes, $_->año );
        }
        break;
      case 'año':
        $lis = [];
        $tot = 0; 
        $num = 0; 
        $mes = 0;
        for( $i = 1; $i <= 12; $i++ ){

          $lis[$i] = _fec::cue('mes',"{$_->año}/$i/1");
          $tot += $lis[$i]; 
        }
        if( $_->mes == 1 ){ 
          $num = $_->dia;
        }
        else{
          $mes++;
          while( $mes < $_->mes ){ 
            $num += $lis[$mes]; 
            $mes++; 
          }
          $num += $_->dia;
        }
        $_=[ 
          'tex'=>"{$num} de {$tot}", 
          'val'=>$num, 
          'cue'=>$tot
        ];
        break;    
      case 'eda':
        $ano_dif = date("Y") - $_->año;
        $mes_dif = date("m") - $_->mes;
        $dia_dif = date("d") - $_->dia;
        if( $dia_dif < 0 || $mes_dif < 0 ) $ano_dif--;
        $_ = $ano_dif;
        break;
      }
      return $_;
    }
    // defino valor por rangos : AC - DC
    static function ran( $ini, $fin ){

      $_ = "";

      if( $ini < 0 && $fin < 0  ){

        $_ = _num::int( $ini * - 1 )." - "._num::int( $fin * - 1). " A.C.";
      }
      elseif( $ini > 0 && $fin > 0 ){

        $_ = _num::int( $ini )." - "._num::int( $fin ). " D.C.";
      }
      else{
        $_ = _num::int( $ini * - 1 )." A.C. - "._num::int( $fin ). " D.C.";
      }

      return $_;
    }
    // año bisciesto ?
    static function año_bis( string | object $fec ) : bool {

      if( is_string($fec) ) $fec = _fec::dat($fec);

      return date('L', strtotime("$fec->año-01-01"));
    }

  }
  // holon : ns.ani.lun.dia:kin
  class _hol {

    // estructuras por posicion
    static function _( string $ide, mixed $val = NULL ) : string | array | object {
      global $_api;
      $_ = [];
      // aseguro carga      
      $est = "hol_$ide";
      if( !isset($_api->$est) ) $_api->$est = _dat::ini(DAT_ESQ,$est);
      // busco dato
      if( !empty($val) ){
        $_ = $val;
        if( !is_object($val) ){
          switch( $ide ){
          case 'fec':
            $fec = _api::_('fec',$val);
            if( isset($fec->dia)  ) $_ = _dat::get( _hol::_('psi'), [ 'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 'opc'=>['uni'] ]);
            break;
          case 'kin':
            $_ = $_api->$est[ _num::ran( _num::sum($val), 260 ) - 1 ];
            break;
          default:
            if( isset($_api->$est[ $val = intval($val) - 1 ]) ) $_ = $_api->$est[$val]; 
            break;
          }
        }
      }
      // devuelvo toda la lista
      else{
        $_ = $_api->$est;
      }
      return $_;    
    }
    // consults sql
    static function sql( string $ide ) : string {
      $_ = "";
      switch( $ide ){
      case 'kin': 
        foreach( _hol::_('kin') as $kin => $_kin ){
          $sel = _hol::_('sel',$_kin->arm_tra_dia);
          $cel = _hol::_('sel_arm_cel',$sel->arm_cel);
          $ton = _hol::_('ton',$_kin->nav_ond_dia);      
          // poder del sello x poder del tono
          if( preg_match("/(o|a)$/i",$ton->nom) ){
            $pod = explode(' ',$sel->pod);
            $art = $pod[0];
            if( preg_match("/agua/i",$pod[1]) ){ 
              $art = 'la';
            }
            $pod = "{$sel->pod} ".( ( strtolower($art) == 'la' ) ? substr($ton->nom,0,-1).'a' : substr($ton->nom,0,-1).'o' );
          }else{
            $pod = "{$sel->pod} {$ton->nom}";
          }
          // encantamiento del kin
          $enc = "Yo ".($ton->pod_lec)." con el fin de ".ucfirst($sel->acc).", \n".($ton->acc_lec)." {$sel->car}. 
            \nSello {$cel->nom} "._tex::art_del($sel->pod)." con el tono {$ton->nom} "._tex::art_del($ton->pod).". ";
          $enc .= "\nMe guía ";
          if( $ton->pul_mat == 1 ){
            $enc .= "mi propio Poder duplicado. ";
          }else{
            $gui = _hol::_('sel', _hol::_('kin',$_kin->par_gui)->arm_tra_dia );
            $enc .= " el poder "._tex::art_del($gui->pod).".";
          }
          if( in_array($kin+1, $_kin->val_est) ){
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
            $_arm = _hol::_('kin_cro_ond',_hol::_('ton',$_ele['ton'])->ond_arm);
            $enc .= "\nSoy un Kin Polar, {$_arm->enc} el Espectro Galáctico {$_est->col}. ";
          }
          if( in_array($kin+1, $_kin->val_pag) ){
            $enc .= "\nSoy un Portal de Activación Galáctica, entra en mí.";
          }
          $_ .= "
          <p>
            UPDATE `_hol`.`kin` SET 
              `pod` = '{$pod}', 
              `des` = '{$enc}'
            WHERE 
              `ide` = '{$_kin->ide}';
          </p>";
        }
        break;
      case 'kin_fac':
        $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
        $_add = [ '052','130','208' ];
        $fac_ini = -3113;
        foreach( _hol::_('kin') as $_kin ){    
    
          $fac_fin = $fac_ini + $_lim[intval($_kin->arm_tra_dia)-1];
    
          if( in_array($_kin->ide,$_add) ){
            $fac_fin ++;
          }
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `fac_ini` = $fac_ini, `fac_fin` = $fac_fin, `fac_ran` = '"._fec::ran($fac_ini,$fac_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $fac_ini = $fac_fin;
    
        }
        break;
      case 'kin_enc':
    
        $enc_ini = -26000;    
        foreach( _hol::_('kin') as $_kin ){    
    
          $enc_fin = $enc_ini + 100;
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '"._fec::ran($enc_ini,$enc_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $enc_ini = $enc_fin;
    
        }
        break;
      case 'kin_cro_ele':
        foreach( _hol::_('kin_cro_ele') as $_ele ){
          $_cas = _hol::_('cas',$_ele->ide);
          $_est = _hol::_('kin_cro_est',$_cas->arm);
          $_ton = _hol::_('ton',$_ele->ton);
          $_ .= "
          UPDATE `_hol``.`kin_cro_ele` 
            SET `des` = '$_ton->des del Espectro Galáctico "._tex::let_ora($_est->col)."'
          WHERE `ide` = $_ele->ide;<br>";
        }
        break;
      }
      return $_;
    }
    // armo imagen
    static function ima( string $est, mixed $dat, array $ele = [] ) : string {
      
      return _app::ima('hol',"$est",$dat,$ele);
    }
    // convierto NS => d/m/a
    static function cod( array | string $val ) : bool | string {
      $_ = $val;

      if( is_string($val) ) $val = explode('.',$val);

      if( isset($val[3]) ){

        $sir = intval($val[0]);
        $ani = intval($val[1]);
        $lun = intval($val[2]);
        $dia = intval($val[3]);

        // mes y día
        $_psi = _dat::get( _hol::_('psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);
    
        if( isset($_psi->fec_mes) && isset($_psi->fec_dia) ){

          $_ = $_psi->fec_mes.'/'.$_psi->fec_dia;
        
          $ini_sir = 1;
          $ini_ani = 0;
          $año = 1987;
          // ns.
          if( $sir != $ini_sir ){

            $año = $año + ( 52 * ( $sir - $ini_sir ) );
          }
          // ns.ani.        
          if( $ani != $ini_ani ){          

            $año = $año + ( $ani - $ini_sir ) + 1;
          }
          // ajusto año
          if( $año == 1987 && ( $lun == 6 && $dia > 19 ) || $lun > 6 ){
            $año ++;
          }
          $_ = $año.'/'.$_;
        }
      }
      return $_;
    }
    // convierto d/m/a => NS
    static function dec( mixed $val ) : object | string {

      $_ = !is_object($val) ? _fec::dat($val) : $val ;

      if( !!$_ ){
        // SE TOMA COMO PUNTO DE REFERENCIA EL AÑO 26/07/1987
        $año      = 1987; 
        $_->sir   = 1;
        $_->ani   = 0; 
        $_->fam_2 = 87;
        $_->fam_3 = 38;
        $_->fam_4 = 34;

        if ($año < $_->año ){

          while( $año < $_->año ){ 

            $año++;

            $_->ani++;

            foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 

              $_->$atr = _num::ran($_->$atr+105, 260); 
            }

            if ($_->ani > 51){ 
              $_->ani = 0; 
              $_->sir++; 
            }
          }
        }
        elseif( $año > $_->año ){
          
          $_->sir = 0;
          while( $_->año < $año ){ 

            $año--; 
            
            $_->ani--;

            foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 
              
              $_->$atr = _num::ran($_->$atr-105, 260); 
            }

            if ($_->ani < 0){ 
              $_->ani = 51; 
              $_->sir--; 
            }
          } 
          // sin considerar 0, directo a -1 : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
          if( $_->sir == 0 ) $_->sir = -1;
        }      
        if( $_->dia <= 25 && $_->mes <= 7){
          
          $_->ani--;
          
          foreach( ['fam_3','fam_4'] as $atr ){ 

            $_->$atr = _num::ran($_->$atr-105, 260); 
          }
        }
      }
      else{
        $_ = "{-_-} la Fecha {$val} no es Válida"; 
      }
      return $_;
    }
    // sumo o resto dias de un fecha dada
    static function ope( string $tip, string $val, int $cue = 1, string $opc = 'dia' ) : string {

      $_ = $val;

      if( isset($val[3]) ){

        $val = explode('.',$val);
        $sir = intval($val[0]);
        $ani = intval($val[1]);
        $lun = intval($val[2]);
        $dia = intval($val[3]);
  
        switch( $opc ){
        case 'dia':
          if( $tip == '+' ){
  
            $dia += $cue;        
    
            if( $dia > 28 ){
              $lun += _num::red($cue / 28);
              $dia = _num::ran($dia, 28);
              
              if( $lun > 13 ){
                $ani += _num::red($lun / 13);
                $lun = _num::ran($lun, 13);
    
                if( $ani > 51 ){
                  $sir += _num::red($ani / 51);
                  $ani = _num::ran($ani, 51, 0);
                }
              }
            }
          }
          elseif( $tip == '-' ){
    
            $dia -= $cue;        
    
            if( $dia < 1 ){
              $lun -= _num::red($cue / 28);
              $dia = _num::ran($dia, 28);
              
              if( $lun < 1 ){    
                $ani -= _num::red($lun / 13);
                $lun = _num::ran($lun, 13);
    
                if( $ani < 0 ){    
                  $sir -= _num::red($ani / 51);
                  $ani = _num::ran($ani, 51, 0);
                }
              }
            }
          }        
          break;
        case 'lun': 
          if( $tip == '+' ){
  
            $lun += $cue;
              
            if( $lun > 13 ){
              $ani += _num::red($lun / 13);
              $lun = _num::ran($lun, 13);
  
              if( $ani > 51 ){  
                $sir += _num::red($ani / 51);
                $ani = _num::ran($ani, 51, 0);                
              }
            }
          }
          elseif( $tip == '-' ){
  
            $lun -= $cue;
              
            if( $lun < 1 ){  
              $ani -= _num::red($lun / 13);
              $lun = _num::ran($lun, 13);
  
              if( $ani < 0 ){
                $sir -= _num::red($ani / 51);
                $ani = _num::ran($ani, 51, 0);
              }
            }
          }        
          break;
        case 'ani': 
          if( $tip == '+' ){
  
            $ani += $cue;
  
            if( $ani > 51 ){
              $sir += _num::red($ani / 51);
              $ani = _num::ran($ani, 51, 0);
            }
          }
          elseif( $tip == '-' ){
  
            $ani -= $cue;
  
            if( $ani < 0 ){
              $sir -= _num::red($ani / 51);
              $ani = _num::ran($ani, 51, 0);
            }
          }
          break;
        case 'sir':
          if( $tip == '+' ){
  
            $sir += $cue;
          }
          elseif( $tip == '-' ){
  
            $sir -= $cue;
          }
          if( $sir == 0 ) $sir = -1;
  
          break;
        }

        $_ = "$sir."._num::val($ani,2)."."._num::val($lun,2)."."._num::val($dia,2);
      }
      return $_;
    }
    // busco valores : fecha - sincronario - tránsitos
    static function val( mixed $val, string $tip = '', array $ope = [] ) : array | object | string {
      $_=[];
      // por tipo
      if( !empty($tip) ){
        // proceso fecha
        if( $tip == 'fec' ){
          $fec = $val;
          $_ = _hol::val($fec);
          if( is_string($_) ){ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
          }
        }
        // decodifico N.S.( cod.ani.lun.dia:kin )
        elseif( $tip == 'sin' ){
          // busco año          
          if( $_fec = _hol::cod($val) ){

            $_ = _hol::val($_fec);

            if( is_string($_) ) $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
          }
          else{ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
          }
        }
      }
      // armo datos de una fecha
      elseif( $fec = _fec::dat($val) ){
        // giro solar => año
        $_['fec'] = $fec->val;

        $_fec = _hol::dec($fec);

        // giro lunar => mes + día
        if( $_psi = _hol::_('fec',$_['fec']) ){

          $_['psi'] = $_psi->ide;

          $_['sin'] = "{$_fec->sir}."._num::val($_fec->ani,2).".{$_psi->lun}.{$_psi->lun_dia}";
          
          // giro galáctico => kin
          $_kin = _hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);

          if( is_object($_kin) ){

            $_['kin'] = $_kin->ide;
          }
          else{
            $_ = '{-_-} Error de Cálculo con la fecha galáctica...'; 
          }
        }
        else{ 
          $_ = '{-_-} Error de Cálculo con la fecha solar...'; 
        }
      }
      // error
      else{ 
        $_ = "{-_-} la Fecha {$val} no es Válida"; 
      }
      return $_;
    }
    // genero acumulados por valor principal
    static function dat( string $est, array $dat, array $ope = [] ) : array {
      $_ = [];

      $ini = isset($ope['ini']) ? intval($ope['ini']) : 1;
      $inc = isset($ope['inc']) ? intval($ope['inc']) : 1;
      $val = isset($ope['val']) ? intval($ope['val']) : "+";
      
      $cue = 0;
      // x 260 dias por kin 
      if( $est == 'kin' && isset($dat['kin']) && isset($dat['fec']) ){

        $cue = 260;

        $fec = _fec::ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
      }
      // x 364+1 dias por psi-cronos
      elseif( $est == 'psi' && isset($dat['psi']) && isset($dat['fec']) ){

        $cue = 364;

        $fec = _fec::ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
      }

      if( isset($fec) ){
    
        for( $pos = 0; $pos < $cue; $pos++ ){

          $_dat = _hol::val($fec);

          $_ []= _app_val::dat([
            'fec'=>[ 
              'dat'=>_fec::_('dat',$fec),
            ],
            'hol'=>[               
              'kin'=>_hol::_('kin',$_dat['kin']), 
              'psi'=>_hol::_('psi',$_dat['psi']) 
            ]
          ]);

          $fec = _fec::ope($fec, $inc, $val);
        }      

      }
      return $_;
    }
    // genero transitos por fecha del sincronario
    static function cic( string $val, ...$opc ) : array {
      $_ = [];
      $ver_lun = !in_array('not-lun',$opc);
      
      // recorro el castillo anual
      for( $cic_año = 1 ; $cic_año <= 52; $cic_año++ ){
        
        $_val = _hol::val($val,'sin');

        $_cas = _hol::_('cas',$cic_año);
        
        // creo el transito anual
        $_cic_año = new stdClass;
        $_cic_año->ide = $cic_año;
        $_cic_año->eda = $cic_año-1;
        $_cic_año->arm = $_cas->arm;
        $_cic_año->ond = $_cas->ond;
        $_cic_año->ton = $_cas->ton;
        $_cic_año->fec = $_val['fec'];
        $_cic_año->sin = $_val['sin'];
        $_cic_año->kin = $_val['kin'];
        // genero transitos lunares
        if( $ver_lun ){
          $_cic_año->lun = [];
          
          $val_lun = $val;
  
          for( $cic_mes = 1 ; $cic_mes <= 13; $cic_mes++ ){

            $_val_lun = _hol::val($val_lun,'sin');
  
            $_cic_lun = new stdClass;  
            $_cic_lun->ani = $cic_año;
            $_cic_lun->ide = $cic_mes;
            $_cic_lun->fec = $_val_lun['fec'];
            $_cic_lun->sin = $_val_lun['sin'];
            $_cic_lun->kin = $_val_lun['kin'];
            
            $_cic_año->lun []= $_cic_lun;
            // incremento 1 luna
            $val_lun = _hol::ope('+',$val_lun,1,'lun');            
          }
        }        
        $_ []= $_cic_año;
        // incremento 1 anillo      
        $val = _hol::ope('+',$val,1,'ani');
      }

      return $_;
    }
  }  
  // usuario : sesion + tránsitos  
  class _usu {

    public int    $ide = 0;
    public string $pas = "";
    public string $nom = "Usuario";
    public string $ape = "Público";
    public string $eda = "";
    public string $fec = "";
    public string $sin = "";
    public string $kin = "";    
    public string $psi = "";
    public string $mai = "";
    public string $tel = "";
    public string $ubi = "";

    public function __construct( int $ide = NULL ){

      if( !empty($ide) ){
        // cargo datos
        foreach( _dat::get('usu', [ 'ver'=>"`ide`='{$ide}'", 'opc'=>'uni' ]) as $atr => $val ){

          $this->$atr = $val;
        }
        // calculo edad actual
        $this->eda = _fec::cue('eda',$this->fec);
      }      
    }

    // genero transitos
    static function cic_act( string $tip = NULL, mixed $val = NULL ) : string {
      global $_usu;
      $_ = "";
      if( empty($tip) ){
      }
      else{
        switch( $tip ){
        // genero tránsitos anuales > lunares
        case 'ani':          
          // elimino previos
          $_ .= "DELETE FROM `_api`.`usu_cic_ani` WHERE usu = $_usu->ide;<br>";
          $_ .= "DELETE FROM `_api`.`usu_cic_lun` WHERE usu = $_usu->ide;<br>";

          // pido tránsitos
          foreach( _hol::cic( $_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

            $_ .= "INSERT INTO `_usu`.`cic_ani` VALUES( 
              $_usu->ide, 
              $_cic_ani->ide, 
              $_cic_ani->eda, 
              $_cic_ani->arm, 
              $_cic_ani->ond, 
              $_cic_ani->ton, 
              '"._fec::var($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
            );<br>";

            if( empty($_cic_ani->lun) ) continue;

            foreach( $_cic_ani->lun as $_cic_lun ){

              $_ .= "INSERT INTO `_usu`.`cic_lun` VALUES( 
                $_usu->ide, 
                $_cic_lun->ani, 
                $_cic_lun->ide, 
                '"._fec::var($_cic_lun->fec,'dia')."', 
                '$_cic_lun->sin', $_cic_lun->kin 
              );<br>";
            }
          }

          break;
        // genero transito diario por ciclo lunar
        case 'dia':
          
          break;
        }
      }
      return $_;
    }
    // calculo tránsito actual
    static function cic_dat( string $fec = '' ) : array {
      global $_usu;

      // valido fecha
      if( empty($fec) ) $fec = date( 'Y/m/d' );

      // cargo holon por fecha
      $_['hol'] = _hol::val( $fec );

      // busco anillo actual
      $_['ani'] = _dat::get('usu_cic_ani',[ 
        'ver'=>"`usu` = '{$_usu->ide}' AND `fec` <= '"._fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
      ]);

      // busco transito lunar
      $_['lun'] = _dat::get('usu_cic_lun',[ 
        'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = {$_['ani']->ide} AND `fec` <= '"._fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
      ]);

      // calculo diario
      $_['dia'] = new stdClass;
      $_['dia']->kin = _hol::_('kin', intval($_['hol']['kin']) + intval($_usu->kin) );

      return $_;
    }
  }