<?php

// Tablero : opciones + posiciones + secciones
class tab {

  static string $IDE = "tab-";
  static string $EJE = "tab.";
  static array $OPE = [
    'ver' => [ 'ide'=>"ver", 'ico'=>"dat_ver", 'nom'=>"Selección",'des'=>"" ],
    'opc' => [ 'ide'=>"opc", 'ico'=>"opc_bin", 'nom'=>"Opciones", 'des'=>"" ],
    'val' => [ 'ide'=>"val", 'ico'=>"est",     'nom'=>"Datos",    'des'=>"" ],
    'lis' => [ 'ide'=>"lis", 'ico'=>"lis_ite", 'nom'=>"Listado",  'des'=>"" ]
  ];
  static array $ATR = [
    'est'=>[],// joins      
    'dat'=>[],// datos
    // valores
    'val'=>[ 'acu'=>[], 'pos'=>[], 'mar'=>[], 'ver'=>[], 'opc'=>[] ],
    'val_acu'=>[],
    'val_pos'=>[],
    'val_mar'=>[],
    'val_ver'=>[],
    'val_opc'=>[],
    // opciones
    'opc'=>[]
  ];

  function __construct(){
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_tab;
    $est = "_$ide";
    if( !isset($api_tab->$est) ) $api_tab->$est = dat::est_ini(DAT_ESQ,"tab{$est}");
    $_dat = $api_tab->$est;
    
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

  // operadores : valores + seleccion + posicion + opciones( posicion | secciones )
  static function ope( string $tip, string $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE.$tip;
    $_eje = self::$EJE.$tip;
    $_ope = self::$OPE;
    // elementos
    if( !isset($ele['ope']) ) $ele['ope'] = [];
    // opciones
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    // proceso datos del tablero
    extract( dat::ide($dat) );      
    // por aplicacion : posicion + seleccion
    if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];
    
    switch( $tip ){
    // Valores : acumulados + sumatorias + cuentas
    case 'val':
      // por acumulados
      $_ .= "
      <form class='ide-acu'>
        <fieldset class='inf ren'>
          <legend>Acumulados</legend>";

          $_ .= doc::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ]);
          
          $_ .= val::acu($ope['val']['acu'],[ 
            'ide'=>"{$_ide}_acu", 
            'eje'=>"{$_eje}_acu(this);",
            'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
          ]);
          $_ .="
        </fieldset>
      </form>";
      // sumatorias por aplicacion
      if( isset($ope['val']['pos']['kin']) ){ $_ .= "
        <form class='ide-sum'>
  
          <fieldset class='inf ren' data-esq='hol' data-est='kin'>
            <legend>Sumatorias del Kin</legend>

            ".val::sum('hol.kin',$ope['val']['pos']['kin'])."

          </fieldset>          
        </form>";
      }
      // cuentas por estructura
      $_ .= "
      <section class='ide-cue inf'>
        <h3>Totales por Estructura</h3>

        ".lis::ite( val::cue('dat',$ope['est'],['ide'=>$_ide]), [ 'dep'=>['class'=>DIS_OCU], 'opc'=>['tog','ver','cue'] ])."
        
      </section>";
      break;
    // Opciones : sección + posición
    case 'opc':
      // controladores
      $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
        $_ = "";
        $_eje = "{$esq}_ope.tab_{$tip}";
        
        // solo muestro las declaradas en el operador
        $ope_val = isset($ope[$tip]) ? $ope[$tip] : $opc;

        $ope_atr = array_keys($ope_val);

        $ope_var = doc::var_dat($esq,'tab',$tip);
  
        foreach( $ope_atr as $ide ){
  
          if( isset($ope_var[$ide]) ){
  
            $_ .= doc::var($esq,"tab.$tip.$ide", [
              'ope'=>[
                'id'=>"{$_ide}-{$ide}", 
                'val'=>!empty($ope_val[$ide]) ? !empty($ope_val[$ide]) : NULL, 
                'onchange'=>"$_eje(this);"
              ]
            ]);
          }
        } 
        return $_;
      };        
      // secciones        
      if( !empty($ope[$tip_opc = 'sec']) ){
        $ele_ide = "{$_ide}-{$tip_opc}";
        $ele_eve = "{$_eje}_{$tip_opc}(this);";
        $ele_ope = $ele['ope'];
        ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".ele::atr($ele_ope).">
          <fieldset class='inf ren'>
            <legend>Secciones</legend>";
            // operadores globales
            if( !empty($tab_sec = doc::var_dat('app','tab',$tip_opc)) ){ $_ .= "
              <div class='val'>";
              foreach( doc::var_dat('app','tab',$tip_opc) as $ide => $ite ){
                if( isset($ope[$tip_opc][$ide]) ){ 
                  $_ .= doc::var('app',"tab.$tip_opc.$ide", [ 
                    'val'=>$ope[$tip_opc][$ide], 
                    'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ] 
                  ]); 
                }
              }$_ .= "
              </div>";
            }
            // operadores por aplicación
            $_ .= $_opc_var($_ide,$tip_opc,$esq,$ope)."

          </fieldset>
        </form>";          
      }
      // posiciones
      if( !empty($ope[$tip_opc = 'pos']) ){ 
        $ele_ide = "{$_ide}-{$tip_opc}";
        $ele_eve = "{$_eje}_{$tip_opc}(this);";
        $ele_ope = $ele['ope'];
        ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".ele::atr($ele_ope).">
          <fieldset class='inf ren'>
            <legend>Posiciones</legend>";
            // bordes            
            $ide = 'bor';
            $_ .= doc::var('app',"tab.$tip_opc.$ide",[
              'val'=>isset($ope[$tip_opc][$ide]) ? $ope[$tip_opc][$ide] : 0,
              'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
            ]);                
            // sin acumulados : color de fondo - numero - texto - fecha
            foreach( ['col','num','tex','fec'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){
                $_ .= doc::var('app',"tab.{$tip_opc}.{$ide}", [
                  'id'=>"{$ele_ide}-{$ide}",
                  'htm'=>dat::val_opc($ide, $ope['est'], [
                    'val'=>$ope[$tip_opc][$ide], 
                    'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                  ])
                ]);                      
              }
            }
            // con acumulados : imagen de fondo - ( ficha )
            foreach( ['ima'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){ $_ .= "
                <div class='ren'>";
                  // vistas por acumulados
                  $_ .= doc::var('app',"tab.{$tip_opc}.{$ide}",[
                    'id'=>"{$ele_ide}-{$ide}",
                    'htm'=>dat::val_opc($ide, $ope['est'], [ 
                      'val'=>$ope[$tip_opc][$ide], 
                      'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                    ])
                  ]);
                  if( isset($ope['val']['acu']) ){ 
                    foreach( array_keys($ope['val']['acu']) as $ite ){
                      $_ .= doc::var('app',"tab.$tip_opc.{$ide}_{$ite}", [
                        'val'=>isset($ope[$tip_opc]["{$ide}_{$ite}"]) ? $ope[$tip_opc]["{$ide}_{$ite}"] : FALSE,
                        'ope'=>[ 'id'=>"{$ele_ide}-{$ide}_{$ite}", 'onchange'=>$ele_eve ]
                      ]);
                    }
                  }
                  $_ .= "
                </div>";
              }
            }
            // operadores por aplicaciones                  
            $_ .= $_opc_var($_ide,$tip_opc,$esq,$ope)."
          </fieldset>    
        </form>";          
      }
      // atributos por aplicacion
      if( !empty($opc) ){
        $tip_opc = "atr"; $_ .= "
        
        <section class='ide-$tip_opc'>";
        foreach( $opc as $atr ){  
          $htm = "";
          $_eje = "{$esq}_ope.tab_{$atr}";

          foreach( doc::var_dat($esq,$tip_opc,$atr) as $ide => $val ){
            $htm .= doc::var($esq,"$tip_opc.$atr.$ide", [
              'ope'=>[ 
                'id'=>"{$_ide}-{$atr}-{$ide}", 'dat'=>$atr, 
                'val'=>isset($ope[$atr][$ide]) ? $ope[$atr][$ide] : NULL,
                'onchange'=>"$_eje(this)" 
              ]
            ]);
          }          
          // busco datos del operador 
          if( !empty($htm) && !empty($_ope = doc::var_dat($esq,'tab',$tip_opc,$atr)) ){
            $ele_ope = $ele['ope'];
            ele::cla($ele_ope,"ide-$tip_opc-$atr",'ini'); $_ .= "
            <form".ele::atr($ele_ope).">
              <fieldset class='inf ren'>
                <legend>{$_ope['nom']}</legend>
                  {$htm}
              </fieldset>
            </form>";          
          }
        }$_ .= "
        </section>";
      }
      break;
    // Seleccion : estructuras/db + posiciones + fechas
    case 'ver':         
      $_ .= "
      <form class='ide-val'>
        <fieldset class='inf ren'>
          <legend>Seleccionar por Datos</legend>

          ".val::ver('dat', $ope['est'], [ 
            'ope'=>[ 'onchange'=>"{$_eje}('val',this);" ] 
          ], 'ope_tam')."

        </fieldset>
      </form>

      <form class='ide-pos'>
        <fieldset class='inf ren'>
          <legend>Seleccionar por Posiciones</legend>

          ".val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [
            'ope'=>[ 'tip'=>"num_int", 
              'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 
              'onchange'=>"{$_eje}('pos',this);" 
            ] 
          ])."
        </fieldset>
      </form>

      <form class='ide-fec'>
        <fieldset class='inf ren'>
          <legend>Seleccionar por Fechas</legend>

          ".val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
            'ope'=>[ 'tip'=>"fec_dia", 
              'id'=>"{$_ide}-fec", 'onchange'=>"{$_eje}('fec',this);" 
            ] 
          ])."            
        </fieldset>          
      </form> 
      ";
      break;
    // listado : Valores + Columnas + Descripciones
    case 'lis':
      // cargo operador con datos del tablero
      $_ = est::lis($dat,[ 
        'est' => isset($ope['est']) ? $ope['est'] : NULL,
        'dat' => isset($ope['dat']) ? $ope['dat'] : NULL,
        'val' => isset($ope['val']) ? $ope['val'] : NULL,
        'ope' => [ "val", "ver", "atr", "des" ],
        'opc' => [ "ite_ocu" ] 
      ],$ele);    
      break;
    }
    return $_;
  }
  // armo tablero
  static function dat( string $esq, string $est, string $atr, array &$ope = [], array &$ele = [] ) : array {
    $_ = [ 
      'esq' => $esq,
      'tab' => $est,
      'est' => $est = $est.( !empty($atr) ? "_$atr" : $atr ) 
    ];

    // cargo elementos del tablero
    foreach( ['sec','pos'] as $v ){ 
      if( !isset($ele[$v]) ) $ele[$v] = []; 
    }
    if( empty($ele['sec']['class']) || !preg_match("/tab/",$ele['sec']['class']) ) ele::cla($ele['sec'],
      "tab {$_['tab']} {$atr}",'ini'
    );
    // opciones
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc = $ope['opc'];

    // items
    if( !isset($ope['eti']) ) $ope['eti'] = "li";
    $ope['_tab_pos'] = 0;// contador de posiciones

    // operador de opciones
    if( !empty($ope['pos']['bor']) ) ele::cla($ele['pos'],"bor-1");      
    $ope['_val'] = [];
    $ope['_val']['pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
    $ope['_val']['pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen
    // posiciones
    $ope['_val']['pos_dep'] = !empty($ope['sec']['pos_dep']);// dependencia por patrones del destino

    // identificadores de datos
    if( is_object( $ide = !empty($ope['ide']) ? $ope['ide'] : 0 ) ) $ide = $ide->ide;
    
    // valor por posicion 
    $val = NULL;
    if( !empty($ope['val']['pos']) ){
      $val = $ope['val']['pos'];
      if( is_object($val) ){
        if( isset($val->ide) ) $val = intval($val->ide);       
      }
      else{
        $val = is_numeric($val) ? intval($val) : $val;
      }
    }
    
    $_['ide'] = $ide;
    $_['val'] = $val;
    $_['ope'] = $ope;
    $_['ele'] = $ele;
    $_['opc'] = $opc;

    return $_;     

  }
  // posicion
  static function pos( string $esq, string $est, mixed $val, array &$ope, array $ele ){    
    // recibo objeto 
    if( is_object( $val_ide = $val ) ){
      $_dat = $val;
      $val_ide = intval($_dat->ide);
    }// o identificador
    else{
      if( class_exists($cla_ide = $esq) && method_exists($cla_ide,'_') ){
        $_dat = $esq::_("{$est}",$val);
      }      
    }

    $_val = isset($ope['_val']) ? $ope['_val'] : [];
    //////////////////////////////////////////////
    // cargo datos ///////////////////////////////
    $e = isset($ele['pos']) ? $ele['pos'] : [];      
    // por acumulados
    if( isset($ope['dat']) ){

      foreach( $ope['dat'] as $pos => $_ref ){

        if( isset($_ref["{$esq}_{$est}"]) && intval($_ref["{$esq}_{$est}"]) == $val_ide ){

          foreach( $_ref as $ref => $ref_dat ){

            $e["data-{$ref}"] = $ref_dat;
          }            
          break;
        }
      }
    }// por dependencias estructura
    else{
      if( !empty( $dat_est = dat::ope($esq,$est,'rel') ) ){

        foreach( $dat_est as $atr => $ref ){

          if( empty($e["data-{$ref}"]) ){

            $e["data-{$ref}"] = $_dat->$atr;
          }        
        }
      }// pos posicion
      elseif( empty($e["data-{$esq}_{$est}"]) ){    
        $e["data-{$esq}_{$est}"] = $_dat->ide;
      }
    }
    //////////////////////////////////////////////
    // .posiciones del tablero principal /////////
    $cla_agr = [];
    // habilito operador
    if( !$_val['pos_dep'] ){
      $cla_agr []= "ope";
      if( isset($ope['val']['pos']) ){

        $dat_ide = $ope['val']['pos'];

        if( is_array($dat_ide) && isset($dat_ide[$est]) ){
          $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
        }
        // agrego seleccion
        if( $_dat->ide == $dat_ide ) $cla_agr []= '_val-pos _val-pos_bor';
      }
    }// clases adicionales
    if( !empty($cla_agr) ) ele::cla($e,implode(' ',$cla_agr),'ini');
    //////////////////////////////////////////////    
    // contenido por esquema /////////////////////
    $htm = "";
    if( method_exists("tab",$eje_ide = "{$esq}_pos") ) $htm = tab::$eje_ide($est,$val,$ope,$e);
    //////////////////////////////////////////////
    // Contenido html ///////////////////////////
    if( empty($htm) ){
      // color de fondo
      if( $_val['pos_col'] ){
        $_ide = dat::ide($_val['pos_col']);
        if( isset($e[$dat_ide = "data-{$_ide['esq']}_{$_ide['est']}"]) && !empty( $_dat = dat::get($_ide['esq'],$_ide['est'],$e[$dat_ide]) ) ){
          $col = dat::val_ide('col', ...explode('.',$_val['pos_col']));          
          if( isset($col['val']) ){
            $col = $col['val'];
            $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
            ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : num::ran($val,$col) ) );
          }
        }
      }
      // imagen + numero + texto + fecha
      if( !isset($ele['ima']) ) $ele['ima'] = [];
      if( !empty($e['title']) ) $ele['ima']['title'] = FALSE;     
      foreach( ['ima','num','tex','fec'] as $tip ){
        if( !empty($ope['pos'][$tip]) ){
          $ide = dat::ide($ope['pos'][$tip]);
          $htm .= dat::val_ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
        }
      }
    }
    //////////////////////////////////////////////
    // devuelvo posicion /////////////////////////
    $ope['_tab_pos']++;// agrego posicion automatica-incremental
    ele::cla($e,"pos-{$ope['_tab_pos']}",'ini');    
    return "<{$ope['eti']}".ele::atr($e).">{$htm}</{$ope['eti']}>";
  }
}