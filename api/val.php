<?php
// Valores
class val {

  static string $IDE = "val-";
  static string $EJE = "val.";  
  // operadores
  static array $OPE = [
    'acu'=>['nom'=>"Acumulados" ], 
    'ver'=>['nom'=>"Selección"  ], 
    'sum'=>['nom'=>"Sumatorias" ], 
    'cue'=>['nom'=>"Conteos"    ]
  ];  

  public function __construct(){
    $this->_dat = [];
    $this->_tip = dat::get('val_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
    $this->_ope = dat::get('val_ope', [ 'niv'=>['ide'] ]);
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_val;
    $est = "_$ide";
    if( !isset($api_val->$est) ) $api_val->$est = dat::est_ini(DAT_ESQ,"val{$est}");
    $_dat = $api_val->$est;
    
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

  // tipo : dato + valor
  static function tip_ver( mixed $val ) : bool | object {
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
    
    global $api_val;
    if( isset( $api_val->_tip[$ide] ) ){
      $_ = $api_val->_tip[$ide];
    }
    return $_;
  }

  // comparaciones de valores
  static function ope_ver( mixed $dat, string $ide, mixed $val ) : bool {
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

  // abm de la base
  static function abm( string $tip, array $ope = [], array $ele = [] ) : string {
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";
    $_ope = [
      'ver'=>['nom'=>"Ver"        ], 
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ]
    ];      
    switch( $tip ){
    case 'nav':        
      $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
      if( !empty($url) ){
        $url_agr = "{$url}/0";
        $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
      }
      $_ .= "
      <fieldset class='ope' abm='{$tip}'>    
        ".fig::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."

        ".fig::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."

        ".fig::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
      </fieldset>";
      break;
    case 'abm':
      $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
      $_ = "
      <fieldset class='ope mar-2 esp-ara'>

        ".fig::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_ope[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

        if( in_array('eli',$ope['opc']) ){

          $_ .= fig::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
        }$_ .= "

        ".fig::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_ope['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

      </fieldset>";
      break;              
    case 'est':
      $_ .= "
      <fieldset class='ope'>    
        ".fig::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
        
        ".fig::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
      </fieldset>";                  
      break;                
    }

    return $_;
  }  

  // cargo datos de un proceso : absoluto o con dependencias
  static function dat( array $ope ) : array {      
    $_ = [];
    // cargo temporal
    foreach( $ope as $esq => $est_lis ){
      // recorro estructuras del esquema
      foreach( $est_lis as $est => $dat ){
        // recorro dependencias            
        foreach( ( !empty($dat_est = dat::ope($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
          // acumulo valores
          if( isset($dat->$ide) ) $_["{$ref}"] = $dat->$ide;
        }
      }
    }
    global $api_val;
    $api_val->_dat []= $_;

    return $_;
  }// acumulado : posicion + marcas + seleccion
  static function acu( array $dat, array $ope = [], array $opc = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."acu";
    $_eje = self::$EJE."acu";

    if( empty($opc) ) $opc = array_keys($dat);

    $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

    if( !empty($ope['ide']) ) $_ide = $ope['ide'];

    $_ .= "
    <div class='ren'>";
      foreach( $opc as $ide ){        
        $_ .= doc::var('app',"val.acu.$ide", [
          'ope'=> [ 
            'id'=>"{$_ide}-{$ide}", 'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 'onchange'=>$_eje_val
          ],
          'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
        ]);
      }
      if( !empty($ope['htm_fin']) ){
        $_ .= $ope['htm_fin'];
      } $_ .= "
    </div>";
    return $_;
  }// sumatorias
  static function sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
    extract( dat::ide($dat) );

    $_ = "";
    $_ide = self::$IDE."sum"." _$esq-$est";

    // estructuras por esquema
    foreach( doc::var_dat($esq,'val','sum') as $ide => $ite ){
  
      $_ .= doc::var($esq,"val.sum.$ide",[
        'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
        // busco fichas del operador
        'htm_fin'=> !empty($ite['var_fic']) ? dat::fic($ite['var_fic'], $val, $ope) : ''
      ]);
    }    

    return $_;
  }// filtros : texto + listado + datos
  static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "";
    $_ite = function( $ide, $dat=[], $ele=[] ){

      if( !empty($ele['ope']['id']) ) $ele['ope']['id'] .= "-{$ide}"; 

      // impido tipos ( para fechas )
      if( ( $ide == 'inc' || $ide == 'lim' ) && isset($ele['ope']['tip']) ) unset($ele['ope']['tip']);
      
      // combino elementos
      if( !empty($dat[$ide]) && is_array($dat[$ide]) ) $ele['ope'] = ele::val_jun($ele['ope'],$dat[$ide]);

      return $ele;
    };
    switch( $tip ){
    // dato : estructura => valores 
    case 'dat':
      // selector de estructura.relaciones para filtros
      array_push($opc,'est','val');
      $_ .= doc::var('app',"val.ver.dat",[ 
        'ite'=>[ 'class'=>"tam-mov" ],
        'htm'=>dat::val_opc('ver',$dat,$ele,...$opc)
      ]);
      break;
    // listado : desde + hasta + cada + cuantos
    case 'lis': 
      // por defecto
      if( empty($dat) ) $dat = [ 'ini'=>[], 'fin'=>[] ];

      // desde - hasta
      foreach( ['ini','fin'] as $ide ){

        if( isset($dat[$ide]) ) $_ .= doc::var('app',"val.ver.$ide", $_ite($ide,$dat,$ele));
      }

      // limites : incremento + cuantos ? del inicio | del final
      if( isset($dat['inc']) || isset($dat['lim']) ){
        $_ .= "
        <div class='ren'>";
          // cada
          if( isset($dat['inc']) ){
            $_ .= doc::var('app',"val.ver.inc", $_ite('inc',$dat,$ele));
          }
          // cuántos
          if( isset($dat['lim']) ){
            $_eje = "dat.var('mar',this,'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
            $ele['htm_fin'] = "
            <fieldset class='ope'>
              ".fig::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
              ".fig::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
            </fieldset>"; 
            $_ .=
            doc::var('app',"val.ver.lim", $_ite('lim',$dat,$ele) );
          }$_ .= "
        </div>";
      }
      break;
    }
    return $_;
  }// conteos : por valores de estructura relacionada por atributo
  static function cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
    $_ = "";
    $_ide = self::$IDE."cue";
    $_eje = self::$EJE."cue";

    if( is_string($dat) ){
      extract( dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
    }

    switch( $tip ){        
    case 'dat': 
      $_ = [];
      // -> por esquemas
      foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){
        // -> por estructuras
        foreach( $est_lis as $est_ide ){
          // -> por dependencias ( est_atr )
          foreach( ( !empty($dat_opc_est = dat::ope($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){
            $est = str_replace("{$esq}_",'',$est);
            // armo listado para aquellos que permiten filtros
            if( $dat_opc_ver = dat::ope($esq,$est,'opc.ver') ){
              // nombre de la estructura
              $est_nom = dat::est($esq,$est)->nom;                
              $htm_lis = [];
              foreach( $dat_opc_ver as $atr ){
                // armo relacion por atributo
                $rel = dat::ide_rel($esq,$est,$atr);
                // busco nombre de estructura relacional
                $rel_nom = dat::est($esq,$rel)->nom;
                // armo listado : form + table por estructura
                $htm_lis []= [ 
                  'ite'=>$rel_nom, 'htm'=>"
                  <div class='var mar_izq-2 dis-ocu'>
                    ".val::cue('est',"{$esq}.{$est}.{$atr}",$ope)."
                  </div>"
                ];
              }
              $_[] = [ 'ite'=> $est_nom, 'lis'=> $htm_lis ];
            }
          }
        }
      }
      break;
    case 'est':
      if( isset($ope['ide']) ) $_ide = $ope['ide'];
      // armo relacion por atributo
      $ide = !empty($atr) ? dat::ide_rel($esq,$est,$atr) : $est;
      $_ = "
      <!-- filtros -->
      <form class='val'>

        ".doc::var('val','ver',[ 
          'nom'=>"Filtrar", 
          'id'=> "{$_ide}-ver {$esq}-{$ide}",
          'htm'=> doc::val_ver([ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
        ])."
      </form>

      <!-- valores -->
      <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
        <tbody>";
        foreach( dat::get($esq,$ide) as $ide => $_var ){
        
          $ide = isset($_var->ide) ? $_var->ide : $ide;

          if( !empty($atr) ){
            $ima = !empty( $_ima = dat::val_ide('ima',$esq,$est,$atr) ) ? arc::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
          }
          else{
            $ima = arc::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
          }$_ .= "
          <tr class='pos' data-ide='{$ide}'>
            <td data-atr='ima'>{$ima}</td>
            <td data-atr='ide'>".tex::let($ide)."</td>
            <td data-atr='nom'>".tex::let(isset($_var->nom) ? $_var->nom : '')."</td>
            <td><c class='sep'>:</c></td>
            <td data-atr='tot' title='Cantidad seleccionada...'><n>0</n></td>
            <td><c class='sep'>=></c></td>
            <td data-atr='por' title='Porcentaje sobre el total...'><n>0</n><c>%</c></td>
          </tr>";
        } $_ .= "
        </tbody>
      </table>";
      break;
    }

    return $_;
  }
}