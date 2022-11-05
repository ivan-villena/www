<?php

// Tabla
class _app_est {
  
  static string $IDE = "_app_est-";
  static string $EJE = "_app_est.";    
  static array $LIS = [
    // identificador : esq.est
    'ide'=>"",
    // opciones
    'opc'=>[
      'cab_ocu',  // ocultar cabecera de columnas
      'ite_ocu',  // oculto items: en titulo + detalle
      'det_cit',  // en detalle: agrego comillas
      'ima',      // buscar imagen para el dato
      'var',      // mostrar variable en el dato
      'htm'       // convertir texto html en el dato
    ],
    // columnas del listado
    'atr'=>[],
    'atr_tot'=>0,// columnas totales
    'atr_ocu'=>[],// columnas ocultas
    'atr_dat'=>[],// datos de las columnas
    // estructuras por esquema => [ ...$esq =>[ ...$est ] ]
    'est'=>[],
    'est_dat'=>[],// datos y operadores por estructura
    // filas: valores por estructura [...{...$}]
    'dat'=>[],
    'dat_ite'=>[],// titulos + detalles
    'dal_val'=>[],// datos por fila
    // Valores : acumulado + posicion principal
    'val'=>[ 'acu'=>[], 'pos'=>[] ],
    // titulos: por base {'cic','gru','des'} o por operador [$pos]
    'tit'=>[],
    'tit_cic'=>[],// titulos por ciclos
    'tit_gru'=>[],// titulos por agrupaciones
    // detalles: por base {'cic','gru','des'} o por operador [$pos]
    'det'=>[],
    'det_cic'=>[],// detalle por ciclos
    'det_gru'=>[],// detalle por agrupaciones
    'det_des'=>[] // detalle por descripciones
  ];
  static array $OPE = [
    'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
    'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
    'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
    'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
    'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
  ];

  // armo Listado-Tabla 
  static function dat( string $esq, string $est, array $ope = NULL ) : array {
    global $_api;
    
    if( !isset($_api->app_est[$esq][$est]) || isset($ope) ){

      // combinado        
      $_est = _app::dat($esq,$est,'est');        

      if( !$_est ) $_est = [];

      // cargo atributos por estructura de la base      
      $_atr = _dat::atr($esq,$est);
              
      // reemplazo atributos por defecto
      if( isset($ope['atr']) ){
        $_est['atr'] = _lis::ite($ope['atr']);
        // descarto columnas ocultas
        if( isset($_est['atr_ocu']) ) unset($_est['atr_ocu']);
      }
      // columnas totales
      if( empty($_est['atr']) ){ 
        $_est['atr'] = !empty($_atr) ? array_keys($_atr) : [];
      }
      // columnas ocultas
      if( isset($ope['atr_ocu']) ) $_est['atr_ocu'] = _lis::ite($ope['atr_ocu']);

      // calculo totales
      $_est['atr_tot'] = count($_est['atr']);
      
      // ciclos y agrupaciones: busco descripciones + inicio de operadores      
      foreach( ['cic','gru'] as $ide ){

        if( isset($_est["tit_{$ide}"]) ){

          foreach( $_est["tit_{$ide}"] as $atr ){
            
            // inicio ciclo
            if( $ide == 'cic' ) $_est['cic_val'][$atr] = -1;

            // busco descripciones
            if( isset( $_atr["{$atr}_des"] ) ){
              
              if( !isset($_est["det_{$ide}"]) ) $_est["det_{$ide}"]=[]; 
              $_est["det_{$ide}"] []= "{$atr}_des";
            }
          }
        }
      }
      $_api->app_est[$esq][$est] = $_est;
    }

    return $_api->app_est[$esq][$est];
  }
  // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
  static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ope = self::$OPE;
    $_ide = self::$IDE.$tip;
    $_eje = self::$EJE.$tip;
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( _dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";        
    }// por listado
    elseif( isset($ope['ide']) ){
      extract( _dat::ide($ope['ide']) );
      $_ide = "_$esq-$est $_ide";
    }
    // aseguro valores
    if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::get($esq,$est);
    
    // aseguro estructura
    if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];      
    
    switch( $tip ){
    // Dato : abm por columnas
    case 'dat':
      foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
      $_est = _app_est::dat($esq,$est);
      // tipos de dato
      $_cue = [
        'opc'=>[ "Opción", 0 ], 
        'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
        'tex'=>[ "Texto",  0 ], 
        'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
        'obj'=>[ "Objeto",  0 ] 
      ];
      // cuento atributos por tipo
      foreach( $_est['atr'] as $atr ){
        $tip_dat = explode('_', _dat::atr($esq,$est,$atr)->ope['_tip'])[0];
        if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
      }
      // operador : toggles + filtros
      $_ .= "
      <form class='val ide-dat jus-ini'>

        <fieldset class='ope'>
          "._app::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          "._app::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        "._app::var('val','ver',[ 
          'nom'=>"Filtrar", 'htm'=>_app::val_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='val'>
            "._app::ico($atr,[
              'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
            ])."
            <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
          </div>";
        }$_ .= "
        </fieldset>

      </form>";
      // listado
      $pos = 0; $_ .= "
      <table"._ele::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
      foreach( $_est['atr'] as $atr ){
        $pos++;
        $_atr = _dat::atr($esq,$est,$atr);
        $_var = [ 'id'=>"{$_ide}-{$atr}", 'onchange'=>"{$_eje}_val(this,'dat');" ];

        $dat_tip = explode('_',$_atr->ope['_tip'])[0];
        if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
        if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
        if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
        if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
        $htm = "
        <form class='ren esp-bet'>
        
          "._app_val::ver('lis', isset($_cue[$dat_tip][2]) ? $_cue[$dat_tip][2] : [], [ 'ope'=>$_var ] )."

        </form>";
        $_ .= "
        <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
          <td data-atr='val'>
            "._app::ico( isset($app_lis->ocu) && in_array($atr,$app_lis->ocu) ? "ope_ver" : "ope_ocu",[
              'eti'=>"button",'title'=>"Mostrar",'class'=>"tam-2{$cla_ver}",'value'=>"tog",'onclick'=>"$_eje('val',this);"
            ])."
          </td>
          <td data-atr='pos'>
            <n>{$pos}</n>
          </td>
          <td data-atr='ide' title='".( !empty($_atr->ope['des']) ? $_atr->ope['des'] : '' )."'>
            <font class='ide'>{$_atr->nom}</font>
          </td>
          <td data-atr='ope'>
            {$htm}
          </td>
        </tr>";
      }$_ .= "
      </table>";            
      break;
    // Valores : cantidad + acumulado + filtros
    case 'val': 
      $_ = "
      <h3 class='mar_arr-0'>Valores</h3>";
      // acumulados
      if( isset($ope['val']['acu']) ){
        $_ .= "
        <form class='ide-acu'>
          <fieldset class='inf ren'>
            <legend>Acumulados</legend>

            "._app::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
            
            "._app::var('app',"val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
            
            "._app_val::acu($ope['val']['acu'],[
              'ide'=>$_ide, // agrego evento para ejecutar todos los filtros
              'eje'=>"{$_eje}_acu(this); ".self::$EJE."ver();",
              'ope'=>[ 'htm_fin'=>"<span class='mar_izq-1'><c>(</c> <n>0</n> <c>)</c></span>" ]
            ]); 
            $_ .= "
          </fieldset>
        </form>";
      }
      break;
    // Filtros :
    case 'ver': 
      $_ = "
      <h3 class='mar_arr-0'>Filtros</h3>";
      // filtros : datos + posicion + atributos
      if( isset($ope['val']) ){
        $dat_tot = count($ope['dat']);
        $_ .= "
        <form class='ide-val'>
          <fieldset class='inf ren'>
            <legend>por Datos</legend>

            "._app_val::ver('dat', $ope['est'], [
              'ope'=>[ 'id'=>"{$_ide}-val", 'max'=>$dat_tot, 'onchange'=>"$_eje();" ] 
            ])."
          </fieldset>
        </form>  

        <form class='ide-fec'>
          <fieldset class='inf ren'>
            <legend>por Fechas</legend>

            "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
              'ope'=>[ 'id'=>"{$_ide}-fec", '_tip'=>"fec_dia", 'onchange'=>"$_eje();" ] 
            ])."            
          </fieldset>          
        </form>
        <!--
        <form class='ide-pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>

            "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [                  
              'ope'=>[ 'id'=>"{$_ide}-pos", 
                '_tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'onchange'=>"$_eje();" 
              ]
            ])."
          </fieldset>
        </form>
        -->";
      }// filtros por : cic + gru
      else{
      }
      break;
    // Columnas : ver/ocultar
    case 'atr':
      $lis_val = [];
      foreach( $ope['est'] as $esq => $est_lis ){
        foreach( $est_lis as $est ){
          // estrutura por aplicacion
          $_est = _app_est::dat($esq,$est);
          // datos de la estructura
          $est_nom = _dat::est($esq,$est)->nom;
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip ren jus-ini mar_izq-2'>
            <fieldset class='ope'>
              "._app::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
              "._app::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
            </fieldset>";
            foreach( $_est['atr'] as $atr ){
              $htm .= _app::var('val',$atr,[
                'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                'val'=>!isset($_est['atr_ocu']) || !in_array($atr,$_est['atr_ocu']),
                'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 'onchange'=>"{$_eje}_tog(this);"
                ] 
              ]);
            } $htm.="
          </form>";
          
          $lis_val []= [
            'ite'=>$est_nom,
            'htm'=>$htm
          ];
        }              
      }        
      $_ = "        
      <h3 class='mar_arr-0'>Columnas</h3>

      "._app_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Descripciones : titulo + detalle
    case 'des':
      $lis_val = [];        
      foreach( $ope['est'] as $esq => $est_lis ){
        foreach( $est_lis as $est ){
          $_est =  _app_est::dat($esq,$est);
          // ciclos, agrupaciones y lecturas
          if( !empty($_est['tit_cic']) || !empty($_est['tit_gru']) || !empty($_est['det_des']) ){              
            $lis_dep = [];
            foreach( ['cic','gru','des'] as $ide ){
              $pre = $ide == 'des' ? 'det' : 'tit';
              if( !empty($_est["{$pre}_{$ide}"]) ){ $htm = "
                <form class='ide-{$ide} lis ali-ini mar_izq-2' data-esq='{$esq}' data-est='{$est}'>";
                foreach( $_est["{$pre}_{$ide}"] as $atr ){
                  $htm .= _app::var('val',$atr,[ 
                    'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                    'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                  ]);
                }$htm .= "
                </form>";
                $lis_dep[] = [ 
                  'ite'=>_app::var_dat('app','est','ver',$ide)['nom'], 
                  'htm'=>$htm
                ];
              }
            }
            $lis_val[] = [
              'ite'=>_dat::est($esq,$est)->nom,
              'lis'=>$lis_dep
            ];
          }
        }
      } 
      $_ = "
      <h3 class='mar_arr-0'>Descripciones</h3>

      "._app_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Cuentas : total + porcentaje
    case 'cue':
      $_ = "
      <h3 class='mar_arr-0'>Cuentas</h3>
      "._app_lis::val( _app_val::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

      break;
    }
    return $_;
  }
  // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
  static function lis( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis";
    if( !isset($ope['opc']) ) $ope['opc']=[];
    foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ 
      if( !isset($ele[$i]) ) $ele[$i]=[]; 
    }      
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( _dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
      // ! inicializo estructura ! // aunq no se use la variable, se usan los datos que esta fun carga
      $_est = _app_est::dat($esq,$est,$ope);
    }// por listado
    else{
      if( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
        $_ide = "_$esq-$est $_ide";
      }
    }      
    // aseguro valores
    if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::get($esq,$est);
    // aseguro estructura
    if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];

    // identificadores de la base        
    if( isset($esq) ){
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
    }
    _ele::cla($ele['lis'],"app_est",'ini'); 
    $_ = "
    <div"._ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // columnas:
        if( $dat_val_lis = is_array($dat) ){
          // datos de atributos
          if( !isset($ope['atr_dat']) ) $ope['atr_dat'] = _dat::atr_ver($dat);
          // listado de columnas
          if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['atr_dat']);
        }
        // caclulo total de columnas
        $ope['atr_tot'] = _dat::atr_cue($dat,$ope);

        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ 
          foreach( ['cab_ite','cab_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
          $_ .= "
          <thead>
            "._app_est::atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo
        $_.="
        <tbody>";
          // recorro: por listado $dat = []
          $pos_val = 0;            
          if( $dat_val_lis ){
            foreach( $ope['dat'] as $pos => $val ){
              // titulos
              if( !empty($ope['tit'][$pos]) ) $_ .= _app_est::pos('tit',$pos,$ope,$ele);
              // fila-columnas
              $pos_val++; 
              $ele_pos = $ele['ite'];
              _ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_.="
              <tr"._ele::atr($ele_pos).">"._app_est::ite($dat,$val,$ope,$ele)."</tr>";
              // detalles
              if( !empty($ope['det'][$pos]) ) $_ .= _app_est::pos('det',$pos,$ope,$ele);                    
            }
          }
          // estructuras de la base esquema
          else{
            // valido item por objeto-array
            foreach( $ope['dat'] as $val ){ $_val_dat_obj = is_object($val); break; }
            // valido contenido : titulos y detalles por estructura de la base
            $ele_ite = [];
            foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){ 
              $_val[$i] = isset($ope[$i]);
              foreach( $v as $e ){
                $_val["{$i}_{$e}"] = isset($ope["{$i}_{$e}"]);
                $ele_ite["{$i}_{$e}"] = [ 'ite'=>[ 'data-opc'=>$i, 'data-ope'=>$e ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
              }            
            }
            // contenido previo : titulos por agrupaciones
            if( $_val['tit_gru'] ){
              foreach( $ope['est'] as $esq => $est_lis ){
                foreach( $est_lis as $est ){
                  $_ .= _app_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru']);
                }
              }
            }
            // recorro datos
            $ope['opc'] []= "det_cit";
            foreach( $ope['dat'] as $pos => $val ){
              // titulos y referencias
              foreach( $ope['est'] as $esq => $est_lis ){
                // recorro referencias
                foreach( $est_lis as $est){
                  // cargo relaciones                  
                  if( $dat_opc_est = _app::dat($esq,$est,'rel') ){

                    foreach( $dat_opc_est as $atr => $ref ){

                      $ele['ite']["data-{$ref}"] = $_val_dat_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos
                  if( $_val['tit'] || $_val['tit_cic'] ){
                    $_ .= _app_est::tit('cic',"{$esq}.{$est}", $_val_dat_obj ? $val : $val["{$esq}_{$est}"], $ope, $ele_ite['tit_cic']);
                  }
                }
              }
              // item por esquema.estructuras
              $pos_val ++;
              $ele_pos = $ele['ite'];
              _ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr"._ele::atr($ele_pos).">";
              foreach( $ope['est'] as $esq => $est_lis ){
                foreach( $est_lis as $est ){
                  $_ .= _app_est::ite("{$esq}.{$est}", $_val_dat_obj ? $val : $val["{$esq}_{$est}"], $ope, $ele);
                } 
              }$_ .= "
              </tr>";
              // detalles
              foreach( $ope['est'] as $esq => $est_lis ){
                foreach( $est_lis as $est ){
                  foreach( ['des','cic','gru'] as $ide ){
                    if( $_val["det_{$ide}"] ){
                      $_ .= _app_est::det($ide,"{$esq}.{$est}", $_val_dat_obj ? $val : $val["{$esq}_{$est}"], $ope, $ele_ite["det_{$ide}"]);
                    }
                  }                  
                } 
              }                    
            }
          }$_ .= "              
        </tbody>";
        // pie
        if( !empty($ope['pie']) ){
          foreach( ['pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } $_.="
          <tfoot>";
            // fila de operaciones
            $_.="
            <tr data-tip='ope'>";
              foreach( $_atr as $atr ){ $_.="
                <td data-atr='{$atr->ide}' data-ope='pie'></td>";
              }$_.="
            </tr>";
            $_.="
          </tfoot>";
        }
        $_.="
      </table>
    </div>";
    return $_;
  }
  // columnas : por atributos
  static function atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";

    // proceso estructura de la base
    if( is_string($dat) ){
      extract( _dat::ide($dat) );
      $_est = _app_est::dat($esq,$est);
    }
    // por listado
    else{
      if( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
    }
    
    // por muchos      
    if( isset($ope['est']) ){

      $ope_est = $ope['est'];
      unset($ope['est']);

      foreach( $ope_est as $esq => $est_lis ){

        foreach( $est_lis as $est ){

          $_ .= _app_est::atr("{$esq}.{$est}",$ope,$ele);
        }
      }
    }
    // por 1: esquema.estructura
    else{
      $_val['dat'] = isset($esq);

      $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
      // cargo datos
      $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val['dat'] ? _dat::atr($esq,$est) : _dat::atr_ver($dat) );
      // ocultos por estructura
      $atr_ocu = isset($_est['atr_ocu']) ? $_est['atr_ocu'] : [];
      // genero columnas :
      foreach( ( !empty($ope['atr']) ? $ope['atr'] : ( !empty($_est['atr']) ? $_est['atr'] : array_keys($dat_atr) ) ) as $atr ){
        $e = [];
        if( $_val['dat'] ){
          $e['data-esq'] = $esq;
          $e['data-est'] = $est;
        } 
        $e['data-atr'] = $atr;
        if( in_array($atr,$atr_ocu) ) _ele::cla($e,"dis-ocu");
        // poner enlaces
        $htm = _app::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
        if( !$ope_nav ){
          $htm = "<p>{$htm}</p>";
        }else{
          $htm = "<a href='' target='_blank'>{$htm}</a>";
        }$_ .= "
        <th"._ele::atr($e).">
          {$htm}
        </th>";
      }         
    }   

    return $_;
  }
  // posicion : titulo + detalle
  static function pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    if( isset($ope[$tip][$ide]) ){

      foreach( _lis::ite($ope[$tip][$ide]) as $val ){ 
        $_.="
        <tr"._ele::atr($ele["{$tip}_ite"]).">
          <td"._ele::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
            ".( is_array($val) ? _ele::val($val) : "<p class='{$tip} tex_ali-izq'>"._app::let($val)."</p>" )."
          </td>
        </tr>";
      }        
    }
    return $_;
  }
  // titulo : posicion + ciclos + agrupaciones
  static function tit( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( _dat::ide($dat) );        
    }
    // 1 titulo : nombre + detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $ide = $val[1];
      $val = $val[2];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      $htm = "";
      if( !empty($htm_val = _app_dat::val('nom',"{$esq}.{$ide}",$val)) ){ $htm .= "
        <p class='tit'>"._app::let($htm_val)."</p>";
      }
      if( !empty($htm_val = _app_dat::val('des',"{$esq}.{$ide}",$val)) ){ $htm .= "
        <q class='mar_arr-1'>"._app::let($htm_val)."</q>";
      }
      if( in_array('ite_ocu',$ope['opc']) ) _ele::cla($ele['ite'],'dis-ocu');
      $_ .="
      <tr"._ele::atr($ele['ite']).">
        <td"._ele::atr($ele['atr']).">{$htm}</td>
      </tr>";
    }
    // ciclos + agrupaciones
    else{
      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }
      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }
      // por ciclos : secuencias
      if( $tip == 'cic' ){        
        // acumulo posicion actual, si cambia -> imprimo ciclo
        $_est = _app_est::dat($esq,$est);
        if( isset($_est['cic_val']) ){
          $val = _dat::get($esq,$est,$val);            
          foreach( $_est['cic_val'] as $atr => &$pos ){
            
            if( !empty($ide = _dat::rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= _app_est::tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele);
              }
              $pos = $val->$atr;
            }
          }
        }        
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($_est[$tip]) ){
          foreach( $_est[$tip] as $atr ){

            if( !empty($ide = _dat::rel($esq,$est,$atr)) ){

              foreach( _dat::get($esq,$ide) as $val ){
                $_ .= _app_est::tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
              }
            }
          }
        }
      }        
    }
    return $_;
  }
  // fila : datos de la estructura
  static function ite( string | array $dat, mixed $val, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( _dat::ide($dat) );
      $_est = _app_est::dat($esq,$est);
    }// por listado
    elseif( isset($ope['ide']) ){
      extract( _dat::ide($ope['ide']) );
    }
    
    $opc_ima = !in_array('ima',$ope['opc']);
    $opc_var = in_array('var',$ope['opc']);
    $opc_htm = in_array('htm',$ope['opc']);

    // identificadores
    if( $_val['dat'] = isset($esq) ){
      $ele['dat_val']['data-esq'] = $esq;
      $ele['dat_val']['data-est'] = $est;
    }
    // datos de la base
    if( isset($_est) ){

      $_atr    = _dat::atr($esq,$est);
      $est_ima = _app::dat($esq,$est,'opc.ima');
      $atr_ocu = isset($_est['atr_ocu']) ? $_est['atr_ocu'] : FALSE;

      // recorro atributos y cargo campos
      foreach( ( isset($ope['atr']) ? $ope['atr'] : $_est['atr'] ) as $atr ){
        $ele_dat = $ele['dat_val'];
        $ele_dat['data-atr'] = $atr;         
        //ocultos
        if( $atr_ocu && in_array($atr,$atr_ocu) ) _ele::cla($ele_dat,'dis-ocu');
        // contenido
        $ele_val = $ele['val'];
        
        if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
          _ele::cla($ele_val,"tam-5");
          $ide = 'ima';
        }
        // variables
        else{
          $ide = $opc_var ? 'var' : 'tip';
          // adapto estilos por tipo de valor
          if( !empty($_atr[$atr]) ){
            $var_dat = $_atr[$atr]->var_dat;
            $var_val = $_atr[$atr]->var_val;
          }
          elseif( !empty( $_var = _dat::tip( $val ) ) ){
            $var_dat = $_var->dat;
            $var_val = $_var->val;
          }
          else{
            $var_dat = "val";
            $var_val = "nul";
          }
          // - limito texto vertical
          if( $var_dat == 'tex' ){
            if( $var_dat == 'par' ) _ele::css($ele_val,"max-height:4rem;overflow-y:scroll");
          }
        }$_ .= "
        <td"._ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? _ele::cla($ele_dat,'dis-ocu') : $ele_dat ).">      
          "._app_dat::ver($ide,"{$esq}.{$est}.{$atr}",$val,$ele_val)."
        </td>";
      }
    }
    // por listado del entorno
    else{
      $_atr = $ope['atr_dat'];
      $_val_dat_obj = is_object($val);
      foreach( $ope['atr'] as $ide ){
        // valor
        $dat_val = $_val_dat_obj ? $val->{$ide} : $val[$ide];
        // html
        if( $opc_htm ){
          $htm = $dat_val;
        }// variable por tipo
        elseif( $opc_var ){
          $htm = "";
        }// elementos
        elseif( is_array( $dat_val ) ){
          $htm = isset($dat_val['htm']) ? $dat_val['htm'] : _ele::val($dat_val);
        }// textos
        else{
          $htm = _app::let($dat_val);
        }
        $ele['dat_val']['data-atr'] = $ide;
        $_.="
        <td"._ele::atr($ele['dat_val']).">
          {$htm}
        </td>";
      }
    }      

    return $_;
  }
  // detalles : posicion + descripciones + lecturas
  static function det( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( _dat::ide($dat) );
      $_est = _app_est::dat($esq,$est);
    }// por listado
    elseif( isset($ope['ide']) ){
      extract( _dat::ide($ope['ide']) );
    }
    // 1 detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $val = $val[1];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      if( in_array('ite_ocu',$ope['opc']) ) _ele::cla($ele['ite'],'dis-ocu');
      $_ = "
      <tr"._ele::atr($ele['ite']).">
        <td"._ele::atr($ele['atr']).">
          ".( in_array('det_cit',$ope['opc']) ? "<q>"._app::let($val->$atr)."</q>" : _app::let($val->$atr) )."
        </td>
      </tr>";
    }
    // por tipos : descripciones + ciclos + agrupaciones
    elseif( isset($_est["det_{$tip}"]) ){
      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }
      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }        
      $val = _dat::get($esq,$est,$val);
      foreach( $_est["det_{$tip}"] as $atr ){
        
        $_ .= _app_est::det('pos',$dat,[$atr,$val],$ope,$ele);
      }
    }

    return $_;
  }
}