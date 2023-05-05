<?php

class sql {
  
  // ejecuto codigo
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
          <p class='err'>".Tex::let($v)."</p>
          ".( isset($var_eve[$i]) ? "<c class='sep'>=></c><q>".Tex::let($var_eve[$i])."</q>" : "" )."
        </li>";
      }
      $_['_err'] .= "
      </ul>";
      echo $_['_err'];
    }
    // resultados
    elseif( isset($sql_res) && is_object($sql_res) ){
      unset($sql_res);
    }
    // cierro conexion
    $_sql->close();

    return $_;
  }
  // codifico instrucciones
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
      foreach( Lis::val_ite($ope['val']) as $pos=>$ite ){
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
  // Tipos de dato
  static array $Tip = [];
  static function tip( string $ide ){

    if( empty(self::$Tip) ){
      self::$Tip = Dat::get('dat_tip',['ver'=>"`len` LIKE '%sql%'",'niv'=>["ide"],'ele'=>["ope"]]);
    }

    return isset(self::$Tip[$ide]) ? self::$Tip[$ide] : new stdClass;
    
  }
  // esquemas
  static function esq( string $ope, string $ide='', ...$opc ) : string | array | object {
    $_ = [];
    if( empty($ope) ){
      foreach( sql::dec("SHOW DATABASES") as $esq ){
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
  }
  // estructuras
  static function est( string $ope, $ide='', ...$opc ) : bool | string | array | object {
    $_=[];      
    $esq = DAT_ESQ;
    // valido si existe una vista o una tabla por $ide
    if( $ope == 'val' ){

      $_ = FALSE;
      foreach( sql::dec("SHOW TABLE STATUS FROM `{$esq}` WHERE `Name` = '{$ide}'") as $v ){
  
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
      $lis = sql::dec("SHOW TABLE STATUS FROM `{$esq}`".( !empty($ver) ? " WHERE ".implode(' AND ',$ver) : '' ));

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
  }
  // atributos
  static function atr( string $est, string $ope = 'ver', ...$opc ) : array | object | string {
    $_=[];
    $esq = DAT_ESQ;
    $dat_lis = sql::dec("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");    
    if( isset($dat_lis['_err']) ){
      $dat_lis = sql::dec("SHOW FULL COLUMNS FROM `{$esq}`.`{$est}`");
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
        $_tip = explode('(',$atr->Type);      
        if( isset($_tip[1]) ) $var_cue = explode(')',$_tip[1])[0]; 
        $_var_atr = sql::tip($sql_tip = $_tip[0]);
        $var_dat = $_var_atr->dat;
        $var_val = $_var_atr->val;
        $dat_tip = "{$var_dat}_{$var_val}";
        // operador automático
        $var = !empty($_var_atr->ope) ? $_var_atr->ope : [];
        // tipo de variable
        $var['tip'] = $dat_tip;
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
            $dat_tip = $var['tip'] = 'opc_bin';              
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
            $var['tip'] = "dir_bit"; 
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
          $var['dat'] = $var_cue = Obj::val_dec($var_cue);
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
    case 'nom': 
      break;      
    case 'ver': 
      if( !empty($ide = $opc[0]) ){
        foreach( sql::dec("SHOW KEYS FROM `$esq`.`$est` WHERE `Key_name` = '".( $ide == 'pri' ? "PRIMARY" : $ide )."'") as $key ){
          $_[] = $key->Column_name;
        }
      }      
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

    $e = sql::cod($ide,$ope, ( $tip == 'cue' ) ? 'ver' : $tip );

    switch( $tip ){
    case 'ver': 
      $_ = sql::dec("SELECT {$e['atr']} FROM {$e['est']}{$e['jun']}{$e['ver']}{$e['ord']}");
      break;
    case 'cue':  
      $_ = sql::dec("SELECT COUNT( {$e['atr']} ) AS `cue` FROM {$e['est']}{$e['ver']}")[0]['cue'];
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