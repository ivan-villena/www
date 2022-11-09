<?php
// Tabla
class app_est {
  
  static string $IDE = "app_est-";
  static string $EJE = "app_est.";
  // datos
  static array $DAT = [
    'esq-1'=>[ 
      "est-1" => [
        // columnas
        'atr'=>[],
        'atr_tot'=>0,// totales
        'atr_ocu'=>[],// ocultas
        // titulos
        'tit_cic'=>[],
        'tit_gru'=>[],
        // detalle
        'det_cic'=>[],
        'det_gru'=>[],
        'det_des'=>[]
      ]
    ]
  ];
  static array $LIS = [
    // estructuras por esquema => [ ...$esq =>[ ...$est ] ]
    'est'=>[],
    // columnas
    'atr_dat'=>[],
    'atr_tot'=>0,
    // titulos : item superior
    'tit'=>[],
    // datos de filas
    'dat'=>[],
    // detalles : item inferior
    'det'=>[],
    // Valores : acumulado + posicion principal
    'val'=>[ 'acu'=>[], 'pos'=>[] ],
    // opciones
    'opc'=>[
      'cab_ocu',  // ocultar cabecera de columnas
      'ite_ocu',  // oculto items: en titulo + detalle
      'det_cit',  // en detalle: agrego comillas
      'ima',      // buscar imagen para el dato
      'var',      // mostrar variable en el dato
      'htm'       // convertir texto html en el dato
    ]
  ];
  static array $OPE = [
    'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
    'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
    'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
    'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
    'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
  ];

  // armo Listado-Tabla 
  static function dat( string $esq, string $est, array $ope = [] ) : array {    
    $_ = [];

    $_ite = function( string $esq, string $est, array $ope = [] ) : array {
      
      if( !( $_ = app::dat($esq,$est,'est') ) ) $_ = [];      
              
      // reemplazo atributos por defecto
      if( isset($ope['atr']) ){
        $_['atr'] = api_lis::ite($ope['atr']);
        if( isset($_['atr_ocu']) ) unset($_['atr_ocu']);
      }
      $_ = api_obj::jun($_,$ope);

      // columnas
      if( empty($_['atr']) ) $_['atr'] = !empty( $_atr = api_dat::atr($esq,$est) ) ? array_keys($_atr) : [];
      // ocultas
      if( isset($ope['atr_ocu']) ) $_['atr_ocu'] = api_lis::ite($ope['atr_ocu']);
      // totales
      $_['atr_tot'] = count($_['atr']);
      
      // ciclos y agrupaciones: busco descripciones + inicio de operadores      
      foreach( ['cic','gru'] as $ide ){

        if( isset($_["tit_{$ide}"]) ){

          foreach( $_["tit_{$ide}"] as $atr ){
            
            // inicio ciclo
            if( $ide == 'cic' ) $_['cic_val'][$atr] = -1;

            // busco descripciones
            if( isset( $_atr["{$atr}_des"] ) ){
              if( !isset($_["det_{$ide}"]) ) $_["det_{$ide}"] = []; 
              $_["det_{$ide}"] []= "{$atr}_des";
            }
          }
        }
      }
      return $_;
    };
    // carga inicial
    foreach( ( isset($ope['est']) ? $ope['est'] : [ $esq => [ $est => $ope ] ] ) as $esq_ide => $est_lis ){

      foreach( $est_lis as $est_pos => $est_dat ){
        
        if( is_string($est_pos) ){
          $_[$esq_ide][$est_pos] = $_ite($esq_ide,$est_pos,$est_dat);
        }
        else{          
          $_[$esq_ide][$est_dat] = $_ite($esq_ide,$est_dat);
        }
      }
    }
    return $_;
  }
  // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
  static function lis( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis";
    self::$DAT = [];
    if( !isset($ope['opc']) ) $ope['opc']=[];
    foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ 
      if( !isset($ele[$i]) ) $ele[$i]=[]; 
    }
    // proceso estructura de la base
    if( $_val_dat = is_string($dat) ){
      extract( api_dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
      // identificador unico
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
      // cargo operadores
      self::$DAT = app_est::dat($esq,$est,$ope);      
      $_val = [ 'tit_cic'=>[], 'tit_gru'=>[], 'det_des'=>[], 'det_cic'=>[], 'det_gru'=>[] ];
      $_val_tit = [ 'cic', 'gru' ];
      $_val_det = [ 'des', 'cic', 'gru'];
      $ele_ite = [];
      $ope['atr_tot'] = 0;
      foreach( self::$DAT as $esq_ide => $est_lis ){
        foreach( $est_lis as $est_ide => $est_ope ){
          // total de columnas    
          $ope['atr_tot'] =+ $est_ope['atr_tot'];
          // valido contenido : titulos y detalles por estructura de la base
          foreach( $_val as $i => &$v ){
            if( isset($est_ope[$i]) ){
              $v []= "{$esq_ide}.{$est_ide}";
              if( !isset($ele_ite[$i]) ){
                $_i = explode('_',$i);
                $ele_ite[$i] = [ 'ite'=>[ 'data-opc'=>$_i[0], 'data-ope'=>$_i[1] ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
              }
            }
          }
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = api::dat($esq,$est);
      // valido item por { objeto( tabla) / array( joins) }
      foreach( $ope['dat'] as $val ){ $_val_obj = is_object($val); break; }
    }
    else{
      // datos de atributos
      if( !isset($ope['atr_dat']) ) $ope['atr_dat'] = api_dat::atr_ver($dat);
      // listado de columnas
      if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['atr_dat']);
      // total de columnas
      $ope['atr_tot'] = count($ope['atr']);
    }
    // imprimo operador
    if( isset($ope['ope']) ){
      if( empty($ope['ope']) ) $ope['ope'] = [ "dat" ];
      if( !empty( $_ope = api_obj::nom(app_est::$OPE,'ver',api_lis::ite($ope['ope'])) ) ){
        foreach( $_ope as $ope_ide => &$ope_lis ){
          $ope_lis['htm'] = app_est::ope($ope_ide,$dat,$ope,$ele);
        }
        $_ = app::nav('ope', $_ope,[ 'lis'=>['class'=>"mar_hor-1"] ],'ico','tog');
      }    
    }
    // imprimo listado
    api_ele::cla($ele['lis'],"app_est",'ini'); 
    $_ .= "
    <div".api_ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ $_ .= "
          <thead>
            ".app_est::atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo        
        $_.="
        <tbody>";
          $pos_val = 0;   
          // recorro: por listado $dat = []                     
          if( !$_val_dat ){
            $_ .= app_lis::tab_ite( $dat, $ope, $ele );
          }
          // estructuras de la base esquema
          else{
            // contenido previo : titulos por agrupaciones
            if( !empty($_val['tit_gru']) ){
              foreach( self::$DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_gru']) ){
                    $_ .= app_est::tit('gru', $dat_ide, $est_ope, $ele_ite['tit_gru'], $ope['opc']);
                  }                  
                }
              }
            }            
            // recorro datos
            $ope['opc'] []= "det_cit";
            foreach( $ope['dat'] as $pos => $val ){
              // 1- arriba: referencias + titulos por ciclos
              foreach( self::$DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  // recorro por relaciones
                  if( $dat_rel = app::dat($esq,$est,'rel') ){
                    foreach( $dat_rel as $atr => $ref ){
                      $ele['ite']["data-{$ref}"] = $_val_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_cic']) ){
                    $_ .= app_est::tit('cic', $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite['tit_cic'], $ope['opc']);
                  }
                }
              }
              // 2- item por [ ...esquema.estructura ]
              $pos_val ++;
              $ele_pos = $ele['ite'];
              api_ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr".api_ele::atr($ele_pos).">";
              foreach( self::$DAT as $esq => $est_lis ){
                // recorro la copia y leo el contenido desde la propiedad principal
                foreach( $est_lis as $est => $est_ope){
                  $_ .= app_est::ite("{$esq}.{$est}", $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele, $ope['opc']);
                }
              }$_ .= "
              </tr>";
              // 3- abajo: detalles
              foreach( self::$DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  foreach( $_val_det as $ide ){
                    if( in_array($dat_ide = "{$esq}.{$est}", $_val["det_{$ide}"]) ){
                      $_ .= app_est::det($ide, $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite["det_{$ide}"], $ope['opc']);
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
  // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
  static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE.$tip;
    $_eje = self::$EJE.$tip;
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( api_dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
    }
    switch( $tip ){
    // Dato : abm por columnas
    case 'dat':
      foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
      $est_ope = self::$DAT;
      // tipos de dato
      $_cue = [
        'opc'=>[ "Opción", 0 ], 
        'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
        'tex'=>[ "Texto",  0 ], 
        'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
        'obj'=>[ "Objeto",  0 ] 
      ];
      // cuento atributos por tipo
      foreach( $est_ope['atr'] as $atr ){
        $tip_dat = explode('_', api_dat::atr($esq,$est,$atr)->ope['_tip'])[0];
        if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
      }
      // operador : toggles + filtros
      $_ .= "
      <form class='val ide-dat jus-ini'>

        <fieldset class='ope'>
          ".app::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          ".app::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        ".app::var('val','ver',[ 
          'nom'=>"Filtrar", 'htm'=> app::val_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='val'>
            ".app::ico($atr,[
              'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
            ])."
            <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
          </div>";
        }$_ .= "
        </fieldset>

      </form>";
      // listado
      $pos = 0; $_ .= "
      <table".api_ele::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
      foreach( $est_ope['atr'] as $atr ){
        $pos++;
        $_atr = api_dat::atr($esq,$est,$atr);
        $_var = [ 'id'=>"{$_ide}-{$atr}", 'onchange'=>"{$_eje}_val(this,'dat');" ];

        $dat_tip = explode('_',$_atr->ope['_tip'])[0];
        if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
        if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
        if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
        if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
        $htm = "
        <form class='ren esp-bet'>
        
          ".app_val::ver('lis', isset($_cue[$dat_tip][2]) ? $_cue[$dat_tip][2] : [], [ 'ope'=>$_var ] )."

        </form>";
        $_ .= "
        <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
          <td data-atr='val'>
            ".app::ico( isset($app_lis->ocu) && in_array($atr,$app_lis->ocu) ? "ope_ver" : "ope_ocu",[
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
      <h3 class='mar_arr-0 tex_ali-izq'>Valores</h3>";
      // acumulados
      if( isset($ope['val']['acu']) ){
        $_ .= "
        <form class='ide-acu'>
          <fieldset class='inf ren'>
            <legend>Acumulados</legend>

            ".app::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
            
            ".app::var('app',"val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
            
            ".app_val::acu($ope['val']['acu'],[
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
      <h3 class='mar_arr-0 tex_ali-izq'>Filtros</h3>";
      // filtros : datos + posicion + atributos
      if( isset($ope['val']) ){
        $dat_tot = count($ope['dat']);
        $_ .= "
        <form class='ide-val'>
          <fieldset class='inf ren'>
            <legend>por Datos</legend>

            ".app_val::ver('dat', $ope['est'], [
              'ope'=>[ 'id'=>"{$_ide}-val", 'max'=>$dat_tot, 'onchange'=>"$_eje();" ] 
            ])."
          </fieldset>
        </form>  

        <form class='ide-fec'>
          <fieldset class='inf ren'>
            <legend>por Fechas</legend>

            ".app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
              'ope'=>[ 'id'=>"{$_ide}-fec", '_tip'=>"fec_dia", 'onchange'=>"$_eje();" ] 
            ])."            
          </fieldset>          
        </form>
        <!--
        <form class='ide-pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>

            ".app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [                  
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
      foreach( self::$DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          // datos de la estructura
          $est_dat = api_dat::est($esq,$est);
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip ren jus-ini mar_izq-2'>
            <fieldset class='ope'>
              ".app::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
              ".app::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
            </fieldset>";
            foreach( $est_ope['atr'] as $atr ){
              $_atr = api_dat::atr($esq,$est,$atr);
              $atr_nom = empty($_atr->nom) && $atr=='ide' ? api_dat::atr($esq,$est,'nom')->nom : $_atr->nom ;
              $htm .= app::var('val',$atr,[
                'nom'=>"¿{$atr_nom}?", 
                'val'=>!isset($est_ope['atr_ocu']) || !in_array($atr,$est_ope['atr_ocu']),
                'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 
                  'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 'onchange'=>"{$_eje}_tog(this);"
                ] 
              ]);
            } $htm.="
          </form>";
          $lis_val []= [ 'ite'=>$est_dat->nom, 'htm'=>$htm ];
        }              
      }        
      $_ = "        
      <h3 class='mar_arr-0 tex_ali-izq'>Columnas</h3>

      ".app_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Descripciones : titulo + detalle
    case 'des':
      $lis_val = [];        
      foreach( self::$DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope){
          // ciclos, agrupaciones y lecturas
          if( !empty($est_ope['tit_cic']) || !empty($est_ope['tit_gru']) || !empty($est_ope['det_des']) ){
            $lis_dep = [];
            foreach( ['cic','gru','des'] as $ide ){
              $pre = $ide == 'des' ? 'det' : 'tit';
              if( !empty($est_ope["{$pre}_{$ide}"]) ){ $htm = "
                <form class='ide-{$ide} ren ali-ini mar_izq-2' data-esq='{$esq}' data-est='{$est}'>";
                foreach( $est_ope["{$pre}_{$ide}"] as $atr ){
                  $htm .= app::var('val',$atr,[ 
                    'nom'=>"¿".api_dat::atr($esq,$est,$atr)->nom."?",
                    'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                  ]);
                }$htm .= "
                </form>";
                $lis_dep[] = [ 
                  'ite'=> app::var_dat('app','est','ver',$ide)['nom'], 
                  'htm'=> $htm
                ];
              }
            }
            $lis_val[] = [
              'ite'=> api_dat::est($esq,$est)->nom,
              'lis'=> $lis_dep
            ];
          }
        }
      } 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Descripciones</h3>

      ".app_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Cuentas : total + porcentaje
    case 'cue':
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Cuentas</h3>
      ".app_lis::val( app_val::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

      break;
    }
    return $_;
  }
  // columnas : por atributos
  static function atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // por muchos      
    if( isset($ope['est']) ){
      foreach( self::$DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          if( isset($est_ope['est']) ) unset($est_ope['est']);
          $_ .= app_est::atr("{$esq}.{$est}",$est_ope,$ele);
        }
      }
    }
    // por 1: esquema.estructura
    else{
      // proceso estructura de la base
      if( $_val_dat = is_string($dat) ){
        extract( api_dat::ide($dat) );      
      }
      $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
      // cargo datos
      $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val_dat ? api_dat::atr($esq,$est) : api_dat::atr_ver($dat) );
      // ocultos por estructura
      $atr_ocu = isset($ope['atr_ocu']) ? $ope['atr_ocu'] : [];
      // genero columnas :
      $ele['cab_ite']['scope'] = "col";
      foreach( ( isset($ope['atr']) ? $ope['atr'] : array_keys($dat_atr) ) as $atr ){
        $ele_ite = $ele['cab_ite'];
        if( $_val_dat ){
          $ele_ite['data-esq'] = $esq;
          $ele_ite['data-est'] = $est;
        } 
        $ele_ite['data-atr'] = $atr;
        if( in_array($atr,$atr_ocu) ) api_ele::cla($ele_ite,DIS_OCU);
        // poner enlaces
        $htm = app::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
        if( $ope_nav ) $htm = "<a href='".SYS_NAV."{$ope['nav']}' target='_blank'>{$htm}</a>";
        // ...agregar operadores ( iconos )
        $htm_ope = "";
        $_ .= "
        <th".api_ele::atr($ele_ite).">
          <p>{$htm}</p>
          {$htm_ope}
        </th>";
      }         
    }
    return $_;
  }
  // titulo : posicion + ciclos + agrupaciones
  static function tit( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( api_dat::ide($dat) );        
    }
    // 1 titulo : nombre + detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $ide = $val[1];
      $val = $val[2];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      $htm = "";
      
      if( !empty($htm_val = app_dat::val('nom',"{$esq}.{$ide}",$val)) ) $htm .= "
      <p class='tit'>".app::let($htm_val)."</p>";
      
      if( !empty($htm_val = app_dat::val('des',"{$esq}.{$ide}",$val)) ) $htm .= "
      <q class='mar_arr-1'>".app::let($htm_val)."</q>";
      
      if( in_array('ite_ocu',$opc) ) api_ele::cla($ele['ite'],'dis-ocu'); $_ .= "
      <tr".api_ele::atr($ele['ite']).">
        <td".api_ele::atr($ele['atr']).">{$htm}</td>
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
        api_ele::cla($ele['atr'],"anc-100");
      }
      // por ciclos : secuencias
      if( $tip == 'cic' ){
        // acumulo posicion actual, si cambia -> imprimo ciclo
        if( isset($ope['cic_val']) ){

          $val = api::dat($esq,$est,$val);
          // actualizo ultimo titulo para no repetir por cada item
          foreach( $ope['cic_val'] as $atr => &$pos ){
            
            if( !empty($ide = api_dat::rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= app_est::tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele,$opc);
              }
              self::$DAT[$esq][$est]['cic_val'][$atr] = $pos = $val->$atr;
            }
          }
        }
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($ope["tit_$tip"]) ){

          foreach( $ope["tit_$tip"] as $atr ){

            if( !empty($ide = api_dat::rel($esq,$est,$atr)) ){

              foreach( api::dat($esq,$ide) as $val ){

                $_ .= app_est::tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
              }
            }
          }
        }
      }        
    }
    return $_;
  }
  // fila : datos de la estructura
  static function ite( string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    $opc_ima = !in_array('ima',$opc);
    $opc_var = in_array('var',$opc);
    $opc_htm = in_array('htm',$opc);
    $atr_ocu = isset($ope['atr_ocu']) ? $ope['atr_ocu'] : FALSE;
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( api_dat::ide($dat) );
      $_atr = api_dat::atr($esq,$est);
      $ele['dat_val']['data-esq'] = $esq;
      $ele['dat_val']['data-est'] = $est;
      $est_ima = app::dat($esq,$est,'opc.ima');
      // recorro atributos y cargo campos
      foreach( $ope['atr'] as $atr ){
        $ele_dat = $ele['dat_val'];
        $ele_dat['data-atr'] = $atr;         
        //ocultos
        if( $atr_ocu && in_array($atr,$atr_ocu) ) api_ele::cla($ele_dat,'dis-ocu');
        // contenido
        $ele_val = $ele['val'];
        
        if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
          api_ele::cla($ele_val,"tam-5");
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
          elseif( !empty( $_var = api_dat::tip( $val ) ) ){
            $var_dat = $_var->dat;
            $var_val = $_var->val;
          }
          else{
            $var_dat = "val";
            $var_val = "nul";
          }
          // - limito texto vertical
          if( $var_dat == 'tex' ){
            if( $var_dat == 'par' ) api_ele::css($ele_val,"max-height:4rem;overflow-y:scroll");
          }
        }$_ .= "
        <td".api_ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? api_ele::cla($ele_dat,DIS_OCU) : $ele_dat ).">      
          ".app_dat::ver($ide,"{$esq}.{$est}.{$atr}",$val,$ele_val)."
        </td>";
      }
    }
    // por listado del entorno
    else{
      $_val_obj = is_object($val);
      foreach( $ope['atr'] as $ide ){
        // valor
        $dat_val = $_val_obj ? $val->{$ide} : $val[$ide];
        // html
        if( $opc_htm ){
          $htm = $dat_val;
        }
        // elementos
        elseif( is_array( $dat_val ) ){
          $htm = isset($dat_val['htm']) ? $dat_val['htm'] : api_ele::val($dat_val);
        }
        // textos
        else{
          $htm = app::let($dat_val);
        }
        $ele['dat_val']['data-atr'] = $ide;
        $_.="
        <td".api_ele::atr($ele['dat_val']).">
          {$htm}
        </td>";
      }
    }      
    return $_;
  }
  // detalles : posicion + descripciones + lecturas
  static function det( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // 1 detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $val = $val[1];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      if( in_array('ite_ocu',$opc) ) api_ele::cla($ele['ite'],'dis-ocu');
      $_ = "
      <tr".api_ele::atr($ele['ite']).">
        <td".api_ele::atr($ele['atr']).">
          ".( in_array('det_cit',$opc) ? "<q>".app::let($val->$atr)."</q>" : app::let($val->$atr) )."
        </td>
      </tr>";
    }
    // por tipos : descripciones + ciclos + agrupaciones
    elseif( isset($ope["det_{$tip}"]) ){
      if( is_string($dat) ){
        extract( api_dat::ide($dat) );
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
        $val = api::dat($esq,$est,$val);        
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        api_ele::cla($ele['atr'],"anc-100");
      }

      foreach( $ope["det_{$tip}"] as $atr ){
        $_ .= app_est::det('pos',$dat,[$atr,$val],$ope,$ele,$opc);
      }
    }

    return $_;
  }
}