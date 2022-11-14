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
  static array $HOL = [
    'kin_cro_ele'=>[
      "rot-ton" => [ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
      "rot-cas" => [ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
    ]
  ];
  
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
    if( empty($ele['sec']['class']) || !preg_match("/app_tab/",$ele['sec']['class']) ) api_ele::cla($ele['sec'],
      "app_tab {$_['tab']} {$atr}",'ini'
    );
    // opciones
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc = $ope['opc'];

    // items
    if( !isset($ope['eti']) ) $ope['eti'] = "li";
    $ope['_tab_pos'] = 0;// contador de posiciones

    // operador de opciones
    if( !empty($ope['pos']['bor']) ) api_ele::cla($ele['pos'],"bor-1");      
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
      $_dat = api::_("{$esq}_{$est}",$val);
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
      if( !empty( $dat_est = app::dat($esq,$est,'rel') ) ){

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
      $cla_agr []= "app_ope";
      if( isset($ope['val']['pos']) ){

        $dat_ide = $ope['val']['pos'];

        if( is_array($dat_ide) && isset($dat_ide[$est]) ){
          $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
        }
        // agrego seleccion
        if( $_dat->ide == $dat_ide ) $cla_agr []= '_val-pos _val-pos_bor';
      }
    }// clases adicionales
    if( !empty($cla_agr) ) api_ele::cla($e,implode(' ',$cla_agr),'ini');
    //////////////////////////////////////////////    
    // contenido por esquema /////////////////////
    $htm = "";
    if( method_exists("app_tab",$eje_ide = "{$esq}_pos") ) $htm = app_tab::$eje_ide($est,$val,$ope,$e);
    //////////////////////////////////////////////
    // Contenido html ///////////////////////////
    if( empty($htm) ){
      // color de fondo
      if( $_val['pos_col'] ){
        $_ide = api_dat::ide($_val['pos_col']);
        if( isset($e[$dat_ide = "data-{$_ide['esq']}_{$_ide['est']}"]) && !empty( $_dat = api::dat($_ide['esq'],$_ide['est'],$e[$dat_ide]) ) ){
          $col = api_dat::opc('col', ...explode('.',$_val['pos_col']));
          if( isset($col['val']) ){
            $col = $col['val'];
            $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
            api_ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : api_num::ran($val,$col) ) );
          }
        }
      }
      // imagen + numero + texto + fecha
      if( !isset($ele['ima']) ) $ele['ima'] = [];
      $ele['ima']['title'] = FALSE;
      foreach( ['ima','num','tex','fec'] as $tip ){
        if( !empty($ope['pos'][$tip]) ){
          $ide = api_dat::ide($ope['pos'][$tip]);
          $htm .= app_dat::ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
        }
      }
    }
    //////////////////////////////////////////////
    // devuelvo posicion /////////////////////////
    $ope['_tab_pos']++;// agrego posicion automatica-incremental
    api_ele::cla($e,"tab_pos-{$ope['_tab_pos']}",'ini');    
    return "<{$ope['eti']}".api_ele::atr($e).">{$htm}</{$ope['eti']}>";
  }

  // Holon
  static function hol( string $est, string $atr, array $ope = [], array $ele = [] ) : string {
    extract( app_tab::dat("hol",$est,$atr,$ope,$ele) );
    $_ = "";
    switch( $tab ){
    case 'uni':
      switch( $atr ){
      // holon solar por flujo vertical ( T.K. )
      case 'sol': 
        $ocu = []; 
        foreach( ['ele','cel','cir','sel'] as $i ){ 
          $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
        }$_ = "
        <ul".api_ele::atr($ele['sec']).">";
          // imágenes: galaxia + sol
          foreach( ['gal'=>[ 'Galaxia' ],'sol'=>[ 'Sol' ] ] as $i=>$v ){
            $_ .= app::ima("hol/tab/$i",[ 'eti'=>"li", 'class'=>"tab_sec $i" ]);
          }
          // 2 respiraciones
          foreach( api_hol::_('uni_sol_res') as $v ){ 
            for( $i = 1; $i <= 10; $i++ ){
              $_ .= api_hol::ima("uni_sol_res",$v,[ 'eti'=>"li", 'class'=>"tab_sec res-{$v->ide} ide-$i" ]);
            }
          }// 4 flujos
          foreach( api_hol::_('uni_flu') as $v ){
            $_ .= api_hol::ima("uni_flu_pod",$v->pod,[ 'eti'=>"li", 'class'=>"tab_sec flu-{$v->ide} pod-{$v->pod}" ]); 
          }
          // 10 planetas
          foreach( api_hol::_('uni_sol_pla') as $v ){ 
            $_ .= api_hol::ima("uni_sol_pla",$v,[ 'eti'=>"li", 'class'=>"tab_sec pla-{$v->ide}" ]);
          }
          // bordes: 
          // - 4 elementos/clanes
          foreach( api_hol::_('sel_cro_ele') as $v ){ $_ .= "
            <li class='tab_sec ele-{$v->ide}{$ocu['ele']}' title='".app_dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }// - 5 células del tiempo
          foreach( api_hol::_('uni_sol_cel') as $v ){ $_ .= "
            <li class='tab_sec cel-{$v->ide}{$ocu['cel']}' title='".app_dat::val('tit',"hol.uni_sol_cel",$v)."'></li>";
          }// - 5 circuitos de telepatía
          foreach( api_hol::_('uni_sol_cir') as $v ){ $_ .= "
            <li class='tab_sec cir-{$v->ide}{$ocu['cir']}' title='".app_dat::val('tit',"hol.uni_sol_cir",$v)."'></li>";
          }
          // posicion: 20 sellos solares
          if( !isset($ele['sel']) ) $ele['sel'] = [];
          foreach( api_hol::_('sel') as $v ){            
            $ele_ite = $ele['sel']; api_ele::cla($ele_ite,"tab_pos sel-{$v->ide}"); $_ .= "
            <li".api_ele::atr($ele_ite).">
              ".api_hol::ima("sel_cod",$v)."
            </li>";
          }$_ .= " 
        </ul>";        
        break;
      // holon solar por flujo circular ( E.S. )
      case 'sol_enc':
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          // fondos: 
          foreach( ['map','ato'] as $i ){ 
            $ele_ite = isset($ele["fon-$i"]) ? $ele["fon-$i"] : [];
            api_ele::cla($ele_ite,"tab_sec fon $i",'ini'); $_ .= "
            <li".api_ele::atr( isset($ele["fon-$i"]) ? $ele["fon-$i"] : [])."></li>";
          }
          // respiracion, clanes, celulas, circuitos
          foreach( ['res','cel','cir'] as $i ){ 
            $ele_ite = isset($ele["fon-$i"]) ? $ele["fon-$i"] : [];
            api_ele::cla($ele_ite,"tab_sec fon $i",'ini'); $_ .= "
            <li".api_ele::atr($ele_ite)."></li>";
          }
          // planetas
          foreach( api_hol::_('uni_sol_pla') as $v ){
            $ele_ite = [ 'eti'=>"li" ];
            api_ele::cla($ele_ite,"tab_sec pla-$v->ide",'ini');             
            $ele_ite['title'] = app_dat::val('tit',"{$esq}.uni_sol_pla",$v); 
            $_ .= api_hol::ima('uni_sol_pla',$v,$ele_ite);
          }
          // sellos
          if( !isset($ele['pos']) ) $ele['pos'] = [];        
          foreach( api_hol::_('sel') as $v ){
            $ele_ite = $ele['pos'];
            api_ele::cla($ele_ite,"tab_pos sel-$v->ide",'ini'); $_ .= "
            <li".api_ele::atr($ele_ite).">
              ".api_hol::ima('sel_cod',$v)."
            </li>";
          }
          $_ .= " 
        </ul>";        
        break;
      // holon planetario 
      case 'pla':
        $ocu = [];
        foreach( ['fam'] as $i ) $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li class = 'tab_sec fon map'></li>";
          foreach( ['res','flu','cen','sel'] as $i ){ $_ .= "
            <li class='tab_sec fon $i".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></li>";
          }
          foreach( api_hol::_('sel_cro_fam') as $v ){
            $_ .= api_hol::ima("sel_cro_fam",$v,[ 'eti'=>"li", 
              'class'=>"tab_sec fam-{$v->ide}{$ocu['fam']}" 
            ]);
          }
          $ele_pos = $ele['pos'];
          foreach( api_hol::_('sel') as $v ){
            api_ele::cla($ele_pos,"tab_pos-".intval($v->ide).'ini'); $_ .= "
            <li".api_ele::atr($ele_pos).">
              ".api_hol::ima("sel",$v)."
            </li>";
          }
          $_ .= "
        </ul>";        
        break;
      // holon humano
      case 'hum':
        $ocu = []; 
        foreach( ['ext','cen'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li class='tab_sec fon map'></li>";
          foreach( ['res','ext','cir','cen'] as $ide ){ $_ .= "
            <li class='tab_sec fon $ide".isset($ope['sec'][$ide]) ? '' : ' dis-ocu'."'></li>";
          }
          foreach( api_hol::_('sel_cro_ele') as $v ){ 
            $_ .= api_hol::ima("sel_cro_ele",$v,[ 'eti'=>"li", 'class'=>"tab_sec ele-{$v->ide}{$ocu['ext']}"]);
          }
          foreach( api_hol::_('sel_cro_fam') as $v ){ 
            $_ .= api_hol::ima("sel_cro_fam",$v,[ 'eti'=>"li", 'class'=>"tab_sec fam-{$v->ide}{$ocu['cen']}"]);
          }
          if( !isset($ele['sel']) ) $ele['sel'] = [];
          foreach( api_hol::_('sel') as $v ){
            $ele_ite = $ele['sel'];
            api_ele::cla($ele_ite,"tab_pos sel-".intval($_sel->ide),'ini'); $_ .= "
            <li".api_ele::atr($ele_ite).">".api_hol::ima("sel",$v)."</li>";
          }
          if( !isset($ele['ton']) ) $ele['ton'] = [];
          foreach( api_hol::_('ton') as $v ){
            $ele_ite = $ele['ton'];
            api_ele::cla($ele_ite,"tab_pos ton-".intval($_sel->ide),'ini'); $_ .= "
            <li".api_ele::atr($ele_ite).">".api_hol::ima("ton",$v)."</li>";
          }
          $_ .= "
        </ul>";        
        break;
      }
      break;
    case 'rad':
      switch( $atr ){
      }
      break;
    case 'ton':
      switch( $atr ){
      // onda encantada
      case 'ond':
        api_ele::cla($ele['sec'],"hol_ton");
        $_ .= "
        <ul".api_ele::atr($ele['sec']).">
          ".app_tab::hol_sec('ton',$ope)
          ;
          foreach( api_hol::_('ton') as $_ton ){            
            $_ .= app_tab::pos('hol','ton',$_ton,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      }
      break;
    case 'sel': 
      switch( $atr ){
      // codigo
      case 'cod':
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          foreach( api_hol::_('sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='val jus-cen'>
                ".api_hol::ima("sel",$_sel,['eti'=>"li"])."
                ".api_hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='mar-0 ali_pro-cen'>
                {$_sel->arm}
                <br>{$_sel->acc}
                <br>{$_sel->pod}
              </p>
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // colocacion cromática
      case 'cro':
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li class='pos-0'></li>";
          foreach( api_hol::_('sel_cro_fam') as $v ){ 
            $_ .= api_hol::ima("sel_cro_fam",$v,[ 'eti'=>"li", 'class'=>"tab_sec fam-{$v->ide}" ]);
          } 
          foreach( api_hol::_('sel_cro_ele') as $v ){ 
            $_ .= api_hol::ima("sel_cro_ele",$v,[ 'eti'=>"li", 'class'=>"tab_sec ele-{$v->ide}"]);
          }
          $ele_pos = $ele['pos'];
          for( $i=0; $i<=19; $i++ ){ 
            $v = api_hol::_('sel', ( $i == 0 ) ? 20 : $i);
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _val-pos' : '' ;
            $ele['pos'] = $ele_pos;
            api_ele::cla($ele['pos'],"{$agr}");
            $_ .= app_tab::pos('hol','sel',$v,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      // colocacion armónica
      case 'arm':
        $_ .= "
        <ul".api_ele::atr($ele['sec']).">
          <li class='pos-0'></li>
          ";
          foreach( api_hol::_('sel_arm_cel') as $v ){ 
            $_ .= api_hol::ima("sel_arm_cel",$v,[ 'eti'=>"li", 'class'=>"tab_sec cel-{$v->ide}"]);
          } 
          foreach( api_hol::_('sel_arm_raz') as $v ){ 
            $_ .= api_hol::ima("sel_arm_raz",$v,[ 'eti'=>"li", 'class'=>"tab_sec raz-{$v->ide}"]);
          }
          $ele_pos = $ele['pos'];
          foreach( api_hol::_('sel') as $v ){
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _val-pos' : '' ;
            $ele['pos'] = $ele_pos;
            api_ele::cla($ele['pos'],"{$agr}");
            $_ .= app_tab::pos('hol','sel',$v,$ope,$ele);
          }
          $_ .= "
        </ul>";        
        break;
      // tablero del oráculo
      case 'arm_tra':
        $_ .= "
        <ul".api_ele::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){
            $ope['ide'] = $i;
            $_ .= app_tab::hol('kin','arm_cel',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;      
      // célula del tiempo para el oráculo
      case 'arm_cel':
        $_arm = api_hol::_('sel_arm_cel',$ide);        
        $ele['cel']['title'] = app_dat::val('tit',"hol.{$est}",$_arm); 
        api_ele::cla($ele['cel'],"app_tab sel {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr($ele['cel']).">
          ".api_hol::ima("sel_arm_cel", $_arm, ['eti'=>"li", 'class'=>"tab_pos-0", 'htm'=>$_arm->ide ] );
          foreach( api_hol::_('sel_arm_raz') as $_raz ){
            $_ .= app_tab::pos('hol','sel',$sel,$ope,$ele);
            $sel++;
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    case 'lun': 
      switch( $atr ){
        case 'hep': break;
        case 'tel': break;
      }
      break;
    case 'cas': 
      switch( $atr ){
      // ondas encantadas
      case 'ond':
        api_ele::cla($ele['sec'],"hol_cas");
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li class='tab_sec fon-ima'></li>";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          api_ele::cla($ele_ite,"tab_pos-0",'ini'); $_ .= "
          <li".api_ele::atr($ele_ite)."></li>
          ".app_tab::hol_sec('cas',$ope)
          ;
          foreach( api_hol::_('cas') as $_cas ){
            $_ .= app_tab::pos('hol','cas',$_cas,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      }
      break;
    case 'kin': 
      switch( $atr ){
      // tzolkin
      case 'tzo':
        $ton_htm = isset($ope['sec']['kin-ton']);
        $ton_val = !empty($ope['sec']['kin-ton']);
        $ele_ton = [ 'class'=> "tab_sec ton" ];
        $sel_htm = isset($ope['sec']['kin-sel']);
        $sel_val = !empty($ope['sec']['kin-sel']);
        // ajusto grilla
        if( $ton_val ) api_ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");

        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          // 1° columna
          if( $ton_htm && $sel_htm ){ $_ .= "
            <li".api_ele::atr([ 'class' => "tab_sec ini".( $ton_val && $sel_val ? "" : " dis-ocu" )])."></li>";
          }
          // filas por sellos
          if( $sel_htm ){
            foreach( api_hol::_('sel') as $v ){ 
              $_ .= api_hol::ima("sel",$v,[ 'eti'=>"li", 'class'=>"tab_sec sel-{$v->ide}".( $sel_val ? "" : " dis-ocu" ) ]);
            }
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( api_hol::_('kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){ $_ .= 
              api_hol::ima("kin_arm_tra",$_kin->arm_tra,['eti'=>"li",'class'=>"tab_sec ton-{$_kin->arm_tra}".( $ton_val ? "" : " dis-ocu" )]);
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            if( isset($ele["tab_pos-{$_kin->ide}"]) ) $ele['pos'] = api_ele::jun( $ele["tab_pos-{$_kin->ide}"], $ele['pos'] );
            $_ .= app_tab::pos('hol','kin',$_kin,$ope,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </ul>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par': 
        if( empty($ide) ) $ide = 1;
        $_kin = is_object($ide) ? $ide : api_hol::_('kin',$ide);           
        api_ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( api_hol::_('sel_par') as $_par ){
            $par_ide = $_par->cod;
            $par_kin = ( $par_ide == 'des' ) ? $_kin : api_hol::_('kin',$_kin->{"par_{$par_ide}"});
            // combino elementos :
            $ele['pos'] = isset($ele["par-{$par_ide}"]) ? api_ele::jun($ele_pos,$ele["par-{$par_ide}"]) : $ele_pos;
            api_ele::cla($ele['pos'],"par-{$par_ide}");
            $_ .= app_tab::pos('hol','kin',$par_kin,$ope,$ele);
          }$_ .= "
        </ul>";
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          ".app_tab::hol_sec('cas',$ope);
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          api_ele::cla($ele_ite,"tab_pos-0",'ini'); $_ .= "
          <li".api_ele::atr($ele_ite)."></li>";
          $_fam_kin = [ 1 => 1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ];
          $_fam = api_hol::_('sel_cro_fam',$ide);            
          $kin = intval($_fam['kin']);
          foreach( api_hol::_('cas') as $_cas ){
            $_kin = api_hol::_('kin',$kin);
            $_ .= app_tab::pos('hol','kin',$kin,$ope,$ele);
            $kin = api_num::ran($kin + 105, 260);
          } $_ .= "
        </ul>";          
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        api_ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          $ele_cas = $ele['cas'];
          foreach( api_hol::_('kin_nav_cas') as $cas => $_cas ){
            $ope['ide'] = $_cas->ide;
            $ele['cas'] = $ele_cas;
            api_ele::cla($ele['cas'],"tab_pos-$_cas->ide",'ini');
            $_ .= app_tab::hol('kin','nav_cas',$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      case 'nav_cas':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_cas = api_hol::_($est,$ide);
        $ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ini + 4;
        $ele['cas']['title'] = app_dat::val('tit',"hol.{$est}",$_cas);
        for( $ond = $ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = api_hol::_('kin_nav_ond',$ond);
          $ele['cas']['title'] .= "\n".$_ond->enc_des;
        }
        api_ele::cla($ele['cas'],"app_tab kin {$atr} hol_cas fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ),'ini');
        $_ = "
        <ul".api_ele::atr($ele['cas']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          api_ele::cla($ele_ite,"tab_pos-0 bor_col-5-{$ide} fon_col-5-{$ide}");
          $_ .= "
          <li".api_ele::atr($ele_ite).">{$ide}</li>
          ".app_tab::hol_sec('cas',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 52 ) + 1;
          foreach( api_hol::_('cas') as $_cas ){
            $_ .= app_tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;
      case 'nav_ond':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_ond = api_hol::_($est,$ide); 
        $_cas = api_hol::_('kin_nav_cas',$_ond->nav_cas);
        $ele['ond']['title'] = app_dat::val('tit',"hol.kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
        api_ele::cla($ele['ond'],"app_tab kin {$atr} hol_ton",'ini');
        $_ = "
        <ul".api_ele::atr($ele['ond']).">
          ".app_tab::hol_sec('ton',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          foreach( api_hol::_('ton') as $_ton ){
            $_ .= app_tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;      
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        api_ele::cla($ele['sec'],"hol_ton");
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          ".app_tab::hol_sec('ton',$ope)
          ;
          $ele_tra = $ele['tra'];
          foreach( api_hol::_('kin_arm_tra') as $_tra ){ 
            $ope['ide'] = $_tra->ide;
            $ele['tra'] = $ele_tra;
            api_ele::cla($ele['tra'],"tab_pos-{$_tra->ide}",'ini');
            $_ .= app_tab::hol('kin','arm_tra',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'arm_tra':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        api_ele::cla($ele['tra'],"app_tab kin {$atr} hol_cro",'ini');
        $_ = "
        <ul".api_ele::atr($ele['tra']).">";
          $_tra = api_hol::_('kin',$ide);
          $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
          $cel_fin = $cel_ini + 5;
          $ele_pos = $ele['cel'];
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $ope['ide'] = $cel;
            $ele['cel'] = $ele_pos;
            api_ele::cla($ele['cel'],"tab_pos-".api_num::ran($cel,5));
            $_ .= app_tab::hol('kin','arm_cel',$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      case 'arm_cel': 
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }         
        $_arm = api_hol::_($est,$ide);
        api_ele::cla($ele['cel'],"app_tab kin {$atr} hol_arm fon_col-5-$_arm->cel fon-0");
        $_ = "
        <ul".api_ele::atr($ele['cel']).">";
          $ele_ite = isset($ele['pos-0']) ? $ele['pos-0'] : []; 
          api_ele::cla($ele_ite,"tab_pos-0 col-bla",'ini');
          $ele_ite['eti'] = "li"; $ele_ite['htm'] = "$_arm->ide"; $ele_ite['onclick'] = FALSE;
          $ele_ite['title'] = app_dat::val('tit',"hol.{$est}",$_arm);
          $_ .= api_hol::ima("sel_arm_cel",$_arm->cel,$ele_ite);
          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= app_tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro': 
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_cas';
        api_ele::cla($ele['sec'],"hol_cas");
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          $ele_ite['eti'] = "li";
          api_ele::cla($ele_ite,"tab_pos-0",'ini'); 
          $_ .= app::ima('hol/tab/gal',$ele_ite)
          .app_tab::hol_sec('cas',$ope)
          ;
          $ele_ele = $ele['ele'];
          foreach( api_hol::_('kin_cro_ele') as $_ele ){
            $ope['ide'] = $_ele->ide;
            $ele['ele'] = $ele_ele;
            api_ele::cla($ele['ele'],"tab_pos-{$_ele->ide}");
            $_ .= app_tab::hol('kin','cro_ele',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'cro_est':
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_ond';

        api_ele::cla($ele['est'],"app_tab kin {$atr} hol_ton",'ini');
        $_ = "
        <ul".api_ele::atr($ele['est']).">
          ".app_tab::hol_sec('ton',$ope)
          ;
          $_est = api_hol::_('kin_cro_est',$ide); 
          $cas = $_est->cas;
          $ele_ele = $ele['ele'];
          foreach( api_hol::_('ton') as $_ton ){
            $ope['ide'] = $cas;
            $ele['ele'] = $ele_ele;
            api_ele::cla($ele['ele'],"tab_pos-".intval($_ton->ide));
            $_ .= app_tab::hol('kin','cro_ele',$ope,$ele);
            $cas = api_num::ran($cas + 1, 52);
          } $_ .= "
        </ul>";
        break;
      case 'cro_ele':
        foreach(['ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        $_ele = api_hol::_('kin_cro_ele',$ide);
        $ELE = self::$HOL['kin_cro_ele'];
        // cuenta de inicio
        $kin_ini = 185;
        $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";
        // del castillo | onda : rotaciones
        if( in_array('fic_cas',$opc) || in_array('fic_ond',$opc) ){ api_ele::css($ele['ele'],
          "transform: rotate(".(in_array('fic_cas',$opc) ? $ELE['rot-cas'][$ide-1] : $ELE['rot-ton'][$ide-1])."deg)");
        }
        api_ele::cla($ele['ele'],"app_tab kin {$atr} hol_cro-cir",'ini');
        $_ .= "
        <ul".api_ele::atr($ele['ele']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          api_ele::cla($ele_ite,"tab_pos-0",'ini'); $_ .= "
          <li".api_ele::atr($ele_ite).">
            ".app::ima("hol/fic/uni/col/".api_num::ran($_ele->ide,4), ['htm'=>$_ele->ide, 'class'=>"alt-100 anc-100"])."
          </li>";

          $kin = api_num::ran($kin_ini + ( ( $ide - 1 ) * 5 ), 260);
          foreach( api_hol::_('sel_cro_fam') as $cro_fam ){
            $_ .= app_tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;// por verdad eléctrica
            if( $kin > 260 ) $kin = 1;
          }$_ .= "
        </ul>";
        break;
      }
      break;
    case 'psi': 
      switch( $atr ){
      case 'ban': 
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        api_ele::cla($ele['sec'],'ond');
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          ".app::ima('hol/tab/sol',['eti'=>"li", 'class'=>"tab_sec uni_sol"])."
          ".app::ima('hol/tab/pla',['eti'=>"li", 'class'=>"tab_sec uni_lun"])."
          ".app_tab::hol_sec('ton',$ope)
          ;
          if( !in_array('cab_nom',$ope['opc']) ) $ope['opc'] []= 'cab_nom';
          foreach( api_hol::_('psi_lun') as $_lun ){            
            $ele_pos = isset($ele["lun-{$_lun->ide}"]) ? $ele["lun-{$_lun->ide}"] : [];
            api_ele::cla($ele_pos,"tab_pos-$_lun->ide",'ini');
            $ope['ide'] = $_lun->ide; $_ .= "
            <li".api_ele::atr($ele_pos).">
              ".app_tab::hol('psi','lun',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // anillos solares por ciclo de sirio
      case 'ani':
        $kin = 34;
        $ope['sec']['orb_cir'] = '1';
        api_ele::cla($ele['sec'],'cas-cir');
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li class='pos-0'></li>
          ".app_tab::hol_sec('cas_cir',$ope)
          ;          
          foreach( api_hol::_('cas') as $_cas ){
            $_kin = api_hol::_('kin',$kin);
            $ele_pos = $ele['pos'];
            api_ele::cla($ele_pos,"tab_pos-".intval($_cas->ide),'ini');
            $_ .= "
            <li".api_ele::atr($ele_pos).">
              ".api_hol::ima("kin",$_kin)."
            </li>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </ul>";        
        break;
      // estaciones de 91 días
      case 'est':
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        api_ele::cla($ele['sec'],'cas');
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          $ele_ite['eti'] = "li";
          $_ .= 
            app::ima('hol/tab/pla',$ele_ite)
            .app_tab::hol_sec('cas',$ope)
          ;
          foreach( api_hol::_('cas') as $_cas ){                        
            $ope['ide'] = $_cas->ide;
            $_ .= app_tab::hol('psi','hep',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      // luna de 28 días
      case 'lun':
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = api_hol::_('psi',$val['psi'])->lun;
        $_lun = api_hol::_($est,$ide);
        $_ton = api_hol::_('ton',$ide);
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);

        api_ele::cla($ele['lun'],"app_tab psi {$atr}",'ini'); $_ = "
        <table".api_ele::atr($ele['lun']).">";
          if( !$cab_ocu ){ $_ .= "
            <thead>
              <tr data-cab='ton'>
                <th colspan='8'>
                  <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    ".api_hol::ima("{$est}",$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-16 mar-1" )])."

                    ".( $cab_nom ? "
                      <p><n>{$ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."</p>                      
                    " : "
                      <div>
                        <p class='tit let-4'>
                          <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                        </p>
                        <p class='let-3 mar-1'>
                          ".app::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                          <br>".app::let($_lun->ond_man)."
                        </p>                   
                        <p class='let-3 mar-1'>
                          Totem<c>:</c> $_lun->tot
                          <br>Propiedades<c>:</c> ".app::let($_lun->tot_pro)."
                        </p> 
                      </div>                      
                    " )."
                  </div>
                </th>
              </tr>";
              // agrego plasmas
              if( !$cab_nom ){ $_ .= "
                <tr data-cab='rad'>
                  <th>
                    <span class='tex_ali-der'>Plasma</span>
                    <span class='tex_ali-cen'><c>/</c></span>                    
                    <span class='tex_ali-izq'>Héptada</span>
                  </th>";
                  foreach( api_hol::_('rad') as $_rad ){ $_ .= "
                    <th>".api_hol::ima("rad",$_rad,[])."</th>";
                  }$_ .= "                  
                </tr>";
              }$_ .="
            </thead>";
          }
          $dia = 1;    
          $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
          $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
          $ope['eti']='td'; $_ .= "
          <tbody>";
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= "
            <tr class='ite-$arm'>
              <td".api_ele::atr(api_ele::jun([ 'data-arm'=>$arm, 'data-hep'=>$hep, 'class'=>"sec -hep fon_col-4-{$arm}" ], 
                  isset($ele[$ide = "hep-{$arm}"]) ? $ele[$ide] : []
                )).">";
                if( $cab_ocu || $cab_nom ){
                  $_ .= "<n>$hep</n>";
                }else{
                  $_ .= api_hol::ima("psi_hep",$hep,[]);
                }$_ .= "
              </td>";
              for( $rad=1; $rad<=7; $rad++ ){
                $_ .= app_tab::pos('hol','psi',$psi,$ope,$ele);
                $dia++;
                $psi++;
              }
              $hep++; 
              $_ .= "
            </tr>";
          }$_ .= "
          </tbody>
        </table>";
        break;
      // heptada de 7 días
      case 'hep': 
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = api_hol::_('psi',$val['psi'])->hep;
        
        $_hep = api_hol::_('psi_hep',$ide);
        api_ele::cla($ele['hep'],"app_tab psi {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr($ele['hep']).">";
          $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
          foreach( api_hol::_('rad') as $_rad ){
            $_ .= app_tab::pos('hol','psi',$psi,$ope,$ele);
            $psi++;
          } $_ .= "
        </ul>";        
        break;
      // banco-psi de 8 tzolkin con psi-cronos
      case 'tzo': 
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          $ele_tzo = isset($ele['tzo']) ? $ele['tzo'] : [];
          for( $i=1 ; $i<=8 ; $i++ ){
            $ele['sec'] = $ele_tzo;
            if( isset($ele["tzo-$i"]) ) $ele['sec'] = api_ele::jun($ele['sec'],$ele["tzo-$i"]);
            $_ .= app_tab::hol('kin','tzo',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    }
    return $_;
  }// Seccion: onda encantada + castillo
  static function hol_sec( string $tip, array $ope=[], array $ele=[] ) : string {
    $_ = "";
    $_tip = explode('_',$tip);
    $_pul = [ 'dim'=>'', 'mat'=>'', 'sim'=>'' ];
    // opciones por seccion
    $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : ' dis-ocu';
    $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';
    // pulsares por posicion
    if( in_array($_tip[0],['ton','cas']) && isset($ope['val']['pos']) ){      
      $_val = $ope['val']['pos'];
      if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || ( is_object($_val) && isset($_val->ide) ) ){
        $_ton = api_hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
        foreach( $_pul as $i => $v ){
          if( !empty($ope['opc']["ton-pul_{$i}"]) )
            $_pul[$i] = api_hol::ima("ton_pul_[$i]", $_ton["pul_{$i}"], ['class'=>'alt-100 anc-100'] );
        }
      }
    }
    switch( $_tip[0] ){
    // oraculo
    case 'par':

      break;
    // onda
    case 'ton':
      // fondo: imagen
      $ele_ite = isset($ele['fon-ima']) ? $ele['fon-ima'] : [];
      api_ele::cla($ele_ite,"tab_sec ond fon-ima ".DIS_OCU,'ini'); $_ .= "
      <{$ope['eti']}".api_ele::atr($ele_ite)."></{$ope['eti']}>";
      // fondos: color
      $ele_ite = isset($ele['fon-col']) ? $ele['fon-col'] : [];
      api_ele::cla($ele_ite,"tab_sec ond fon-col{$col_ocu}",'ini');       
      $_ .= "
      <{$ope['eti']}".api_ele::atr($ele_ite)."></{$ope['eti']}>";
      // pulsares
      foreach( ['dim','mat','sim'] as $ide ){ 
        $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
        api_ele::cla($ele_ite,"tab_sec ond pul-$ide",'ini'); $_ .= "
        <{$ope['eti']}".api_ele::atr($ele_ite).">{$_pul[$ide]}</{$ope['eti']}>";
      }
      break;
    // castillo
    case 'cas':
      // fondos: imagen      
      $ele_pos = isset($ele["fon-ima"]) ? $ele["fon-ima"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_pos;
        api_ele::cla($ele_ite,"tab_sec ond-$i ".DIS_OCU,'ini'); $_ .= "
        <{$ope['eti']}".api_ele::atr($ele_ite)."></{$ope['eti']}>";
      }
      // fondos: color
      $ele_pos = isset($ele["fon-col"]) ? $ele["fon-col"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_pos;
        api_ele::cla($ele_ite,"tab_sec ond-$i fon_col-4-{$i}{$col_ocu}",'ini'); $_ .= "
        <{$ope['eti']}".api_ele::atr($ele_ite)."></{$ope['eti']}>";
      }        
      // bordes: orbitales
      $ele_pos = isset($ele["orb"]) ? $ele["orb"] : [];
      for( $i = 1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ 
        $ele_ite = $ele_pos;
        api_ele::cla($ele_ite,"tab_sec orb-{$i}{$orb_ocu}",'ini'); $_ .= "
        <{$ope['eti']}".api_ele::atr($ele_ite)."></{$ope['eti']}>";
      }      
      // fondos: pulsares
      foreach( ['dim','mat','sim'] as $ide ){ 
        $ele_pos = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
        for( $i = 1; $i <= 4; $i++ ){
          $ele_ite = $ele_pos;
          api_ele::cla($ele_ite,"tab_sec ond-$i pul-{$ide}",'ini'); $_ .= "
          <{$ope['eti']}".api_ele::atr($ele_ite).">{$_pul[$ide]}</{$ope['eti']}>";
        }
      }
      break;      
    }
    return $_;
  }// Posicion: titulos + patrones
  static function hol_pos( string $est, mixed $val, array &$ope, array &$ele ) : string {
    $_ = "";    
    // opciones:
    if( !isset($ope['_val']['kin_pag']) ) $ope['_val']['kin_pag'] = !empty($ope['pag']['kin']);
    if( !isset($ope['_val']['psi_pag']) ) $ope['_val']['psi_pag'] = !empty($ope['pag']['psi']);
    if( !isset($ope['_val']['sec_par']) ) $ope['_val']['sec_par'] = !empty($ope['sec']['par']);
    $_val = $ope['_val'];
    // armo titulos y cargo operadores
    $cla_agr = [];
    $pos_tit = [];
    if( isset($ele["data-fec_dat"]) ){
      $pos_tit []= "Calendario: {$ele["data-fec_dat"]}";
    }
    if( isset($ele["data-hol_kin"]) ){
      $_kin = api_hol::_('kin',$ele["data-hol_kin"]);
      $pos_tit []= app_dat::val('tit',"hol.kin",$_kin);
      if( $_val['kin_pag'] && !empty($_kin->pag) ) $cla_agr []= "_hol-pag_kin";
    }
    if( isset($ele["data-hol_sel"]) ){
      $pos_tit []= app_dat::val('tit',"hol.sel",$ele["data-hol_sel"]);
    }
    if( isset($ele["data-hol_ton"]) ){
      $pos_tit []= app_dat::val('tit',"hol.ton",$ele["data-hol_ton"]);
    }
    if( isset($ele["data-hol_psi"]) ){
      $_psi = api_hol::_('psi',$ele["data-hol_psi"]);
      $pos_tit []= app_dat::val('tit',"hol.psi",$_psi);          
      if( $_val['psi_pag'] ){
        $_psi->tzo = api_hol::_('kin',$_psi->tzo);
        if( !empty($_psi->tzo->pag) ) $cla_agr []= "_hol-pag_psi";
      }
    }
    if( isset($ele["data-hol_rad"]) ){
      $pos_tit []= app_dat::val('tit',"hol.rad",$ele["data-hol_rad"]);
    }
    $ele['title'] = implode("\n\n",$pos_tit);
    if( !empty($cla_agr) ) api_ele::cla($ele,implode(' ',$cla_agr));

    // por patrones: posicion por dependencia
    if( !!$_val['sec_par'] ){

      $ele_sec = $ele;
      if( isset($ele_sec['class']) ) unset($ele_sec['class']);
      if( isset($ele_sec['style']) ) unset($ele_sec['style']);      

      api_ele::cla($ele,'pos_dep');
      
      $_ = app_tab::hol($est,'par',[
        'ide' => $val,
        'sec' => [ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal
        'pos' => [ 'ima'=>isset($par_ima) ? $par_ima : "hol.{$est}.ide" ]
      ],[
        'sec'=>$ele_sec
      ]);
    }

    return $_;
  }
}