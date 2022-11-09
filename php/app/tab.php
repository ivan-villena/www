<?php

// Tablero : opciones + posiciones + secciones
class app_tab {

  static string $IDE = "app_tab-";
  static string $EJE = "app_tab.";
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
  
  // armo Tablero
  static function dat( string $esq, string $est, array $ele = NULL ) : array | object {
    global $_api;

    if( !isset($_api->app_tab[$esq][$est]) ){
      $_api->app_tab[$esq][$est] = api::dat('app_tab',[ 
        'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 
        'opc'=>'uni', 
        'ele'=>['ele','ope','opc'] 
      ]);
    }
    // devuelvo tablero : ele + ope + opc
    $_ = $_api->app_tab[$esq][$est];

    // combino elementos
    if( isset($ele) ){
      $_ = $ele;
      if( !empty($_api->app_tab[$esq][$est]->ele) ){

        foreach( $_api->app_tab[$esq][$est]->ele as $eti => $atr ){
          
          $_[$eti] = isset($_[$eti]) ? api_ele::jun( $atr, $_[$eti] ) : $atr;
        }
      }
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
    extract( api_dat::ide($dat) );      
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

          $_ .= app::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ]);
          
          $_ .= app_val::acu($ope['val']['acu'],[ 
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

            ".app_val::sum('hol.kin',$ope['val']['pos']['kin'])."

          </fieldset>          
        </form>";
      }
      // cuentas por estructura
      $_ .= "
      <section class='ide-cue inf'>
        <h3>Totales por Estructura</h3>

        ".app_lis::val( app_val::cue('dat',$ope['est'],['ide'=>$_ide]), [ 'dep'=>['class'=>DIS_OCU], 'opc'=>['tog','ver','cue'] ])."
        
      </section>";
      break;
    // Opciones : sección + posición
    case 'opc':
      // controladores
      $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
        $_ = "";
        $_eje = "{$esq}.tab_{$tip}";
        
        // solo muestro las declaradas en el operador
        $ope_val = isset($ope[$tip]) ? $ope[$tip] : $opc;

        $ope_atr = array_keys($ope_val);

        $ope_var = app::var_dat($esq,'tab',$tip);
  
        foreach( $ope_atr as $ide ){
  
          if( isset($ope_var[$ide]) ){
  
            $_ .= app::var($esq,"tab.$tip.$ide", [
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
        api_ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".api_ele::atr($ele_ope).">
          <fieldset class='inf ren'>
            <legend>Secciones</legend>";
            // operadores globales
            if( !empty($tab_sec = app::var_dat('app','tab',$tip_opc)) ){ $_ .= "
              <div class='val'>";
              foreach( app::var_dat('app','tab',$tip_opc) as $ide => $ite ){
                if( isset($ope[$tip_opc][$ide]) ){ 
                  $_ .= app::var('app',"tab.$tip_opc.$ide", [ 
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
        api_ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".api_ele::atr($ele_ope).">
          <fieldset class='inf ren'>
            <legend>Posiciones</legend>";
            // bordes            
            $ide = 'bor';
            $_ .= app::var('app',"tab.$tip_opc.$ide",[
              'val'=>isset($ope[$tip_opc][$ide]) ? $ope[$tip_opc][$ide] : 0,
              'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
            ]);                
            // sin acumulados : color de fondo - numero - texto - fecha
            foreach( ['col','num','tex','fec'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){
                $_ .= app::var('app',"tab.{$tip_opc}.{$ide}", [
                  'id'=>"{$ele_ide}-{$ide}",
                  'htm'=>app_dat::opc($ide, $ope['est'], [
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
                  $_ .= app::var('app',"tab.{$tip_opc}.{$ide}",[
                    'id'=>"{$ele_ide}-{$ide}",
                    'htm'=>app_dat::opc($ide, $ope['est'], [ 
                      'val'=>$ope[$tip_opc][$ide], 
                      'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                    ])
                  ]);
                  if( isset($ope['val']['acu']) ){ 
                    foreach( array_keys($ope['val']['acu']) as $ite ){
                      $_ .= app::var('app',"tab.$tip_opc.{$ide}_{$ite}", [
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
          $_eje = "{$esq}.tab_{$atr}";

          foreach( app::var_dat($esq,$tip_opc,$atr) as $ide => $val ){
            $htm .= app::var($esq,"$tip_opc.$atr.$ide", [
              'ope'=>[ 
                'id'=>"{$_ide}-{$atr}-{$ide}", 'dat'=>$atr, 
                'val'=>isset($ope[$atr][$ide]) ? $ope[$atr][$ide] : NULL,
                'onchange'=>"$_eje(this)" 
              ]
            ]);
          }          
          // busco datos del operador 
          if( !empty($htm) && !empty($_ope = app::var_dat($esq,'tab',$tip_opc,$atr)) ){
            $ele_ope = $ele['ope'];
            api_ele::cla($ele_ope,"ide-$tip_opc-$atr",'ini'); $_ .= "
            <form".api_ele::atr($ele_ope).">
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

          ".app_val::ver('dat', $ope['est'], [ 
            'ope'=>[ 'onchange'=>"{$_eje}('val',this);" ] 
          ], 'ope_tam')."

        </fieldset>
      </form>

      <form class='ide-pos'>
        <fieldset class='inf ren'>
          <legend>Seleccionar por Posiciones</legend>

          ".app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [
            'ope'=>[ '_tip'=>"num_int", 
              'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 
              'onchange'=>"{$_eje}('pos',this);" 
            ] 
          ])."
        </fieldset>
      </form>

      <form class='ide-fec'>
        <fieldset class='inf ren'>
          <legend>Seleccionar por Fechas</legend>

          ".app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
            'ope'=>[ '_tip'=>"fec_dia", 
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
      $_ = app_est::lis($dat,[ 
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
  // posicion
  static function pos(){
  }
  // seccion
  static function sec(){
  }
}