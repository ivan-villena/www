<?php

// Estructura : valores + listado + tablero
class api_est {

  static string $IDE = "api_est-";
  static string $EJE = "api_est.";

  static array $OPE = [
    'acu'=>['nom'=>"Acumulados" ], 
    'ver'=>['nom'=>"Selección"  ], 
    'sum'=>['nom'=>"Sumatorias" ], 
    'cue'=>['nom'=>"Conteos"    ]
  ];
  static array $OPE_VAL = [
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
      'ima',      // buscar imagen para el dato
      'var',      // mostrar variable en el dato
      'htm'       // convertir texto html en el dato
    ]
  ];
  static array $LIS_DAT = [
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
      ],
      "est-2" => []
    ]
  ];    
  static array $LIS_OPE = [
    'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
    'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
    'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
    'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
    'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
  ];
  static array $TAB_DAT = [
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
  static array $TAB_OPE = [
    'ver' => [ 'ide'=>"ver", 'ico'=>"dat_ver", 'nom'=>"Selección",'des'=>"" ],
    'opc' => [ 'ide'=>"opc", 'ico'=>"opc_bin", 'nom'=>"Opciones", 'des'=>"" ],
    'val' => [ 'ide'=>"val", 'ico'=>"est",     'nom'=>"Datos",    'des'=>"" ],
    'lis' => [ 'ide'=>"lis", 'ico'=>"lis_ite", 'nom'=>"Listado",  'des'=>"" ]
  ];  

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = api_app::est('dat',$ide,'dat');
    
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
    }
    return $_;
  }
    
  /* Operadores */
  static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      
    $_ = "";

    return $_;
  }// cargo datos de un proceso ( absoluto o con dependencias )
  static function ope_val( array $dat ) : array {
    $_ = [];
    // cargo temporal
    foreach( $dat as $esq => $est_lis ){
      // recorro estructuras del esquema
      foreach( $est_lis as $est => $dat ){
        // recorro dependencias
        foreach( ( !empty($dat_est = api_app::est($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
          // acumulo valores
          if( isset($dat->$ide) ) $_[$ref] = $dat->$ide;
        }
      }
    }
    // agrego
    $_['pos'] = count(self::$OPE_VAL) + 1;
    self::$OPE_VAL []= $_;

    return $_;
  }// acumulado : posicion + marcas + seleccion
  static function ope_acu( array $dat, array $ope = [], array $opc = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."ope_acu";

    if( empty($opc) ) $opc = array_keys($dat);

    $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

    if( !empty($ope['ide']) ) $_ide = $ope['ide'];

    $_ .= "
    <div class='doc_ren'>";
      foreach( $opc as $ide ){        
        $_ .= api_dat::var('est',"ope.acu.$ide", [
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
  }// sumatorias por valores
  static function ope_sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
    $_ = "";
    extract( api_app::est_ide($dat) );
    $_ide = self::$IDE."sum"." _$esq-$est";
    // estructuras por esquema
    foreach( api_app::var($esq,'ope','sum') as $ide => $ite ){
  
      $_ .= api_dat::var($esq,"ope.sum.$ide",[
        'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
        // busco fichas del operador
        'htm_fin'=> !empty($ite['var_fic']) ? api_dat::fic_atr($ite['var_fic'], $val, $ope) : ''
      ]);
    }
    return $_;
  }// filtros : datos + valores
  static function ope_ver( array $ope, string $ide, string $eje, ...$opc ) : string {
    $_ = "";        
    $tip = isset($ope['tip']) && !empty($ope['tip']) ? $ope['tip'] : [ 'pos','fec' ];
    // Controladores: ( desde - hasta ) + ( cada - cuántos: del inicio, del final )
    $ope_ver_var = function ( array $var = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      
      // proceso controlador 
      $_ite = function ( $ide, &$var ) {
  
        if( !isset($var[$ide]) ) $var[$ide] = [];
        
        if( !empty($var[$ide]['id']) ) $var[$ide]['id'] .= "-{$ide}";
  
        // aseguro tipos numericos para incremento y limite
        if( ( $ide == 'inc' || $ide == 'lim' ) ) $var[$ide]['tip'] = "num_int";
  
        return $var[$ide];
      };
      
      // operadores por defecto
      if( empty($var) ) $var = [ 'ini'=>[], 'fin'=>[] ];
  
      // imprimo: desde - hasta
      foreach( ['ini','fin'] as $ide ){
        
        if( isset($var[$ide]) ) $_ .= api_dat::var('est',"ope.ver.$ide", [ 'ope'=>$_ite($ide,$var) ]);
      }

      // imprimo cada
      if( isset($var['inc']) ){
        $_ .= api_dat::var('est',"ope.ver.inc", [ 'ope'=>$_ite('inc',$var) ]);
      }

      // imprimo cuántos
      if( isset($var['lim']) ){
        $_eje = "api_dat.var('mar',this,'bor-sel');".( isset($var['lim']['onchange']) ? " {$var['lim']['onchange']}" : "" );
        $_ .= "
        <div class='doc_ren tam-ini'>

          ".api_dat::var('est',"ope.ver.lim", [ 'ope'=>$_ite('lim',$var) ] )."

          <fieldset class='doc_ope'>
            ".api_fig::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            ".api_fig::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>

        </div>";
      }

      return $_;
    };

    // por listado de registros
    if( isset($ope['dat']) ){

      // form: dato por estructuras
      if( isset($ope['est']) ){         
        // aseguro estructuras
        $ope_dat = [];
        foreach( $ope['est'] as $esq_ide => $est_lis ){
          $ope_dat[$esq_ide] = api_lis::val($est_lis) ? $est_lis : array_keys($est_lis);
        }
        // // opciones: pido selectores ( + ajustar tamaño... )
        array_push($opc,'est','val');
        $_ .= "
        <form class='ide-dat'>
          <fieldset class='doc_inf doc_ren'>
            <legend>por Datos</legend>

            ".api_dat::var('est',"ope.ver.dat",[ 
              'ite'=>[ 'class'=>"tam-mov" ], 
              'htm'=>api_dat::val_opc('ver',$ope_dat,[ 'ope'=>[ 'id'=>"{$ide}-val", 'onchange'=>"$eje('dat');" ] ], ...$opc)
            ])."

          </fieldset>
        </form>";
      }

      // form: posicion
      $dat_cue = count($ope['dat']);
      if( in_array('pos',$tip) ){
        $pos_var = [ 'id'=>"{$ide}-pos", 'min'=>"1", 'max'=>$dat_cue, 'onchange'=>"$eje('pos');" ];
        $pos_var_val = array_merge($pos_var,[ 'tip'=>"num_int" ]); 
        $_ .= "
        <form class='ide-pos'>
          <fieldset class='doc_inf doc_ren'>
            <legend>por Posiciones</legend>

            ".$ope_ver_var([ 'ini'=>$pos_var_val, 'fin'=>$pos_var_val, 'inc'=>$pos_var, 'lim'=>$pos_var ])."

          </fieldset>
        </form>";
      }

      // form: fecha principal
      $fin = $dat_cue-1;
      if( in_array('fec',$tip) && isset($ope['dat'][0]['fec_dat']) && isset($ope['dat'][$fin]['fec_dat']) ){
        $fec_var = [ 'id'=>"{$ide}-fec", 'min'=>1, 'max'=>$dat_cue, 'onchange'=>"$eje('fec');" ];
        $fec_var_val = array_merge($fec_var,[ 'tip'=>"fec_dia",
          'min'=>api_fec::val_var($ope['dat'][0]['fec_dat']),
          'max'=>api_fec::val_var($ope['dat'][$fin]['fec_dat'])
        ]);
        $_ .= "
        <form class='ide-fec'>
          <fieldset class='doc_inf doc_ren'>
            <legend>por Fechas</legend>

            ".$ope_ver_var([ 'ini'=>$fec_var_val, 'fin'=>$fec_var_val, 'inc'=>$fec_var, 'lim'=>$fec_var ])."
            
          </fieldset>          
        </form>";
      }          
    }
    // valores por atributo
    elseif( isset($ope['var']) ){
      $var = $ope['var'];
      $var_ope = array_merge($ope['var'],[ 'tip'=>"num_int", 'min'=>1 ]);
      $_ .= "
      <form>
        <fieldset>

          ".$ope_ver_var([ 'ini'=>$var, 'fin'=>$var, 
            'inc'=>in_array('inc',$opc) ? $var_ope : NULL, 
            'lim'=>in_array('lim',$opc) ? $var_ope : NULL 
          ])."
          
        </fieldset>          
      </form>";
    }

    return $_;
  }// conteos : por valores de estructura relacionada por atributo
  static function ope_cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
    $_ = "";
    $_ide = self::$IDE."ope_cue";
    $_eje = self::$EJE."ope_cue";

    if( is_string($dat) ){
      extract( api_app::est_ide($dat) );
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
          foreach( ( !empty($dat_opc_est = api_app::est($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){
            $est = str_replace("{$esq}_",'',$est);
            // armo listado para aquellos que permiten filtros
            if( $dat_opc_ver = api_app::est($esq,$est,'opc.ver') ){
              // nombre de la estructura
              $est_nom = api_app::est($esq,$est)->nom;                
              $htm_lis = [];
              foreach( $dat_opc_ver as $atr ){
                // armo relacion por atributo
                $rel = api_app::est_rel($esq,$est,$atr);
                // busco nombre de estructura relacional
                $rel_nom = api_app::est($esq,$rel)->nom;
                // armo listado : form + table por estructura
                $htm_lis []= [ 
                  'ite'=>$rel_nom, 'htm'=>"
                  <div class='val mar_izq-2 dis-ocu'>
                    ".api_est::ope_cue('est',"{$esq}.{$est}.{$atr}",$ope)."
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
      $ide = !empty($atr) ? api_app::est_rel($esq,$est,$atr) : $est;
      $_ = "
      <!-- filtros -->
      <form class='doc_val'>

        ".api_dat::var('val','ver',[ 
          'nom'=>"Filtrar", 
          'id'=> "{$_ide}-ver {$esq}-{$ide}",
          'htm'=> api_doc::val_ver([ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
        ])."
      </form>

      <!-- valores -->
      <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
        <tbody>";
        foreach( api_app::dat($esq,$ide) as $ide => $_var ){
        
          $ide = isset($_var->ide) ? $_var->ide : $ide;

          if( !empty($atr) ){
            $ima = !empty( $_ima = api_dat::val_ide('ima',$esq,$est,$atr) ) ? api_fig::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
          }
          else{
            $ima = api_fig::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
          }$_ .= "
          <tr class='pos' data-ide='{$ide}'>
            <td data-atr='ima'>{$ima}</td>
            <td data-atr='ide'>".api_tex::let($ide)."</td>
            <td data-atr='nom'>".api_tex::let(isset($_var->nom) ? $_var->nom : '')."</td>
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

  /* Tabla: thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td ) */
  static function lis( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis";
    self::$LIS_DAT = [];
    if( !isset($ope['opc']) ) $ope['opc']=[];
    foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
    /////////////////////////////////////////
    // 1- proceso estructura de la base /////
    if( $_val_dat = is_string($dat) ){
      extract( api_app::est_ide($dat) );
      $_ide = "_$esq-$est $_ide";
      // identificador unico
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
      // cargo operadores
      self::$LIS_DAT = api_est::lis_dat($esq,$est,$ope);      
      $_val = [ 'tit_cic'=>[], 'tit_gru'=>[], 'det_des'=>[], 'det_cic'=>[], 'det_gru'=>[] ];
      $_val_tit = [ 'cic', 'gru' ];
      $_val_det = [ 'des', 'cic', 'gru'];
      $ele_ite = [];
      $ope['atr_tot'] = 0;
      foreach( self::$LIS_DAT as $esq_ide => $est_lis ){
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
      if( !isset($ope['dat']) ) $ope['dat'] = api_app::dat($esq,$est);
      // valido item por { objeto( tabla) / array( joins) }
      foreach( $ope['dat'] as $val ){ $_val_obj = is_object($val); break; }
    }
    else{
      // datos de atributos
      if( !isset($ope['atr_dat']) ) $ope['atr_dat'] = api_app::est_atr($dat);
      // listado de columnas
      if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['atr_dat']);
      // total de columnas
      $ope['atr_tot'] = count($ope['atr']);
    }
    /////////////////////////////////////////
    // 2- imprimo operador //////////////////
    if( isset($ope['ope']) ){

      if( empty($ope['ope']) ) $ope['ope'] = [ "dat" ];

      if( !empty( $_ope = api_obj::val_nom(self::$LIS_OPE,'ver',api_lis::val_ite($ope['ope'])) ) ){

        foreach( $_ope as $ope_ide => &$ope_lis ){
          $ope_lis['htm'] = api_est::lis_ope($ope_ide,$dat,$ope,$ele);
        }
        $_ .= api_doc::nav('ope', $_ope,[ 'lis'=>['class'=>"mar_hor-1"] ],'ico','tog');
      }    
    }
    /////////////////////////////////////////
    // 3- imprimo listado ///////////////////
    api_ele::cla($ele['lis'],"est lis",'ini'); 
    $_ .= "
    <div".api_ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ $_ .= "
          <thead>
            ".api_est::lis_atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo        
        $_.="
        <tbody>";
          $pos_val = 0;   
          // recorro: por listado $dat = []                     
          if( !$_val_dat ){
            foreach( $dat as $ite => $val ){ 
              $ele_pos = $ele['ite'];
              api_ele::cla($ele_pos,"pos ide-$ite",'ini'); $_ .= "
              <tr".api_ele::atr($ele_pos).">
                ".api_est::lis_ite( $dat, $val, $ope, $ele )."
              </tr>";
            }
          }
          // estructuras de la base esquema
          else{
            // contenido previo : titulos por agrupaciones
            if( !empty($_val['tit_gru']) ){
              foreach( self::$LIS_DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_gru']) ){
                    $_ .= api_est::lis_tit('gru', $dat_ide, $est_ope, $ele_ite['tit_gru'], $ope['opc']);
                  }                  
                }
              }
            }            
            // recorro datos
            foreach( $ope['dat'] as $pos => $val ){
              // 1- arriba: referencias + titulos por ciclos
              foreach( self::$LIS_DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  // recorro por relaciones
                  if( $dat_rel = api_app::est($esq,$est,'rel') ){
                    foreach( $dat_rel as $atr => $ref ){
                      $ele['ite']["data-{$ref}"] = $_val_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_cic']) ){
                    $_ .= api_est::lis_tit('cic', $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite['tit_cic'], $ope['opc']);
                  }
                }
              }
              // 2- item por [ ...esquema.estructura ]
              $pos_val ++;
              $ele_pos = $ele['ite'];
              api_ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr".api_ele::atr($ele_pos).">";
              foreach( self::$LIS_DAT as $esq => $est_lis ){
                // recorro la copia y leo el contenido desde la propiedad principal
                foreach( $est_lis as $est => $est_ope){
                  $_ .= api_est::lis_ite("{$esq}.{$est}", $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele, $ope['opc']);
                }
              }$_ .= "
              </tr>";
              // 3- abajo: detalles
              foreach( self::$LIS_DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  foreach( $_val_det as $ide ){
                    if( in_array($dat_ide = "{$esq}.{$est}", $_val["det_{$ide}"]) ){
                      $_ .= api_est::lis_det($ide, $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite["det_{$ide}"], $ope['opc']);
                    }
                  }                  
                } 
              }                    
            }
          }
          $_ .= "              
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
  }// armo Listado-Tabla 
  static function lis_dat( string $esq, string $est, array $ope = [] ) : array {     
    $_ = [];

    $_ite = function( string $esq, string $est, array $ope = [] ) : array {
      
      // inicializo atributos de lista
      $_ = $ope;

      /* columnas 
      */
      if( empty($_['atr']) ) $_['atr'] = !empty( $_atr = api_app::est($esq,$est,'atr') ) ? array_keys($_atr) : [];
      // totales
      $_['atr_tot'] = count($_['atr']);
      
      /* ciclos y agrupaciones 
      */
      // busco descripciones + inicio de operadores      
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

      foreach( $est_lis as $est_ide => $est_ope ){

        $_[$esq_ide][$est_ide] = $_ite($esq_ide,$est_ide,$est_ope);
      }
    }

    return $_;
  }// operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
  static function lis_ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis_$tip";
    $_eje = self::$EJE."lis_$tip";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( api_app::est_ide($dat) );
      $_ide = "_$esq-$est $_ide";
    }
    switch( $tip ){
    // Dato : abm por columnas
    case 'abm':
      foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
      $est_ope = self::$LIS_DAT;
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
        $tip_dat = explode('_', api_app::est($esq,$est,'atr',$atr)->ope['tip'])[0];
        if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
      }
      // operador : toggles + filtros
      $_ .= "
      <form class='doc_val ide-dat jus-ini'>

        <fieldset class='doc_ope'>
          ".api_fig::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          ".api_fig::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        ".api_dat::var('val','ver',[ 
          'nom'=>"Filtrar", 'htm'=> api_doc::val_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='doc_ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='doc_val'>
            ".api_fig::ico($atr,[
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
        $_atr = api_app::est($esq,$est,'atr',$atr);
        $dat_tip = explode('_',$_atr->ope['tip'])[0];

        $_var = [];        
        if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
        if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
        if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
        if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
        $htm = "
        <form class='doc_ren esp-bet'>
        
          ".api_est::ope_ver([ 'var'=>$_var ],"{$_ide}-{$atr}","{$_eje}_val")."

        </form>";
        $_ .= "
        <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
          <td data-atr='val'>
            ".api_fig::ico( isset($lis->ocu) && in_array($atr,$lis->ocu) ? "ope_ver" : "ope_ocu",[
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
    // Filtros :
    case 'ver': 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Filtros</h3>";
      // filtros : datos + posicion + atributos
      if( isset($ope['val']) ){
        // acumulados
        if( isset($ope['val']['acu']) ){
          // cambio método
          $eje_val = self::$EJE."lis_val";
          $_ .= "
          <form class='ide-acu'>
            <fieldset class='doc_inf doc_ren'>
              <legend>Acumulados</legend>

              ".api_dat::var('est',"ope.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
              
              ".api_dat::var('est',"ope.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$eje_val}('tod',this);" ] ])."
              
              ".api_est::ope_acu($ope['val']['acu'],[
                'ide'=>$_ide, 
                'eje'=>"{$eje_val}('acu'); api_est.lis_ver();",// agrego evento para ejecutar todos los filtros
                'ope'=>[ 'htm_fin'=>"<span class='mar_izq-1'><c>(</c> <n>0</n> <c>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }
        // pido operadores de filtro: dato + posicion + fecha
        $_ .= api_est::ope_ver([ 'dat'=>$ope['dat'], 'est'=>$ope['est'] ], $_ide, $_eje );

      }// filtros por : cic + gru
      else{
      }
      break;
    // Columnas : ver/ocultar
    case 'atr':
      $lis_val = [];
      foreach( self::$LIS_DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          // datos de la estructura
          $est_dat = api_app::est($esq,$est);
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip doc_ren jus-ini mar_izq-2'>
            <fieldset class='doc_ope'>
              ".api_fig::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
              ".api_fig::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
            </fieldset>";
            foreach( $est_ope['atr'] as $atr ){
              $_atr = api_app::est($esq,$est,'atr',$atr);
              $atr_nom = empty($_atr->nom) && $atr=='ide' ? api_app::est($esq,$est,'atr','nom')->nom : $_atr->nom ;
              $htm .= api_dat::var('val',$atr,[
                'nom'=>"¿{$atr_nom}?", 
                'val'=>!isset($est_ope['atr_ocu']) || !in_array($atr,$est_ope['atr_ocu']),
                'ope'=>[ 'tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 
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

      ".api_lis::dep($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Descripciones : titulo + detalle
    case 'des':
      $lis_val = [];        
      foreach( self::$LIS_DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope){
          // ciclos, agrupaciones y lecturas
          if( !empty($est_ope['tit_cic']) || !empty($est_ope['tit_gru']) || !empty($est_ope['det_des']) ){
            $lis_dep = [];
            foreach( ['cic','gru','des'] as $ide ){
              $pre = $ide == 'des' ? 'det' : 'tit';
              if( !empty($est_ope["{$pre}_{$ide}"]) ){ $htm = "
                <form class='ide-{$ide} doc_ren ali-ini mar_izq-2' data-esq='{$esq}' data-est='{$est}'>";
                foreach( $est_ope["{$pre}_{$ide}"] as $atr ){
                  $htm .= api_dat::var('val',$atr,[ 
                    'nom'=>"¿".api_app::est($esq,$est,'atr',$atr)->nom."?",
                    'ope'=>[ 'tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                  ]);
                }$htm .= "
                </form>";
                $lis_dep[] = [ 
                  'ite'=> api_app::var('est','lis','ver',$ide)['nom'], 
                  'htm'=> $htm
                ];
              }
            }
            $lis_val[] = [
              'ite'=> api_app::est($esq,$est)->nom,
              'lis'=> $lis_dep
            ];
          }
        }
      } 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Descripciones</h3>

      ".api_lis::dep($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Cuentas : total + porcentaje
    case 'cue':
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Cuentas</h3>
      ".api_lis::dep( api_est::ope_cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

      break;
    }
    return $_;
  }// columnas : por atributos
  static function lis_atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // por muchos      
    if( isset($ope['est']) ){
      
      foreach( self::$LIS_DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          if( isset($est_ope['est']) ) unset($est_ope['est']);
          $_ .= api_est::lis_atr("{$esq}.{$est}",$est_ope,$ele);
        }
      }
    }
    // por 1: esquema.estructura
    else{
      // proceso estructura de la base
      if( $_val_dat = is_string($dat) ){
        extract( api_app::est_ide($dat) );      
      }
      $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
      // cargo datos
      $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val_dat ? api_app::est($esq,$est,'atr') : api_app::est_atr($dat) );
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
        $htm = api_tex::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
        if( $ope_nav ) $htm = "<a href='".SYS_NAV."{$ope['nav']}' target='_blank'>{$htm}</a>";
        // ...agregar operadores ( iconos )
        $htm_ope = "";
        $_ .= "
        <th".api_ele::atr($ele_ite).">
          <p class='let-ide'>{$htm}</p>
          {$htm_ope}
        </th>";
      }         
    }
    return $_;
  }// titulo : posicion + ciclos + agrupaciones
  static function lis_tit( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( api_app::est_ide($dat) );        
    }
    // 1 titulo : nombre + detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $ide = $val[1];
      $val = $val[2];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      $htm = "";
      
      if( !empty($htm_val = api_dat::val('nom',"{$esq}.{$ide}",$val)) ) $htm .= "
      <p class='tit'>".api_tex::let($htm_val)."</p>";
      
      if( !empty($htm_val = api_dat::val('des',"{$esq}.{$ide}",$val)) ) $htm .= "
      <p class='des mar_arr-1'>".api_tex::let($htm_val)."</p>";
      
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

          $val = api_app::dat($esq,$est,$val);
          // actualizo ultimo titulo para no repetir por cada item
          foreach( $ope['cic_val'] as $atr => &$pos ){
            
            if( !empty($ide = api_app::est_rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= api_est::lis_tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele,$opc);
              }
              self::$LIS_DAT[$esq][$est]['cic_val'][$atr] = $pos = $val->$atr;
            }
          }
        }
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($ope["tit_$tip"]) ){

          foreach( $ope["tit_$tip"] as $atr ){

            if( !empty($ide = api_app::est_rel($esq,$est,$atr)) ){

              foreach( api_app::dat($esq,$ide) as $val ){

                $_ .= api_est::lis_tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
              }
            }
          }
        }
      }        
    }
    return $_;
  }// fila : datos de la estructura
  static function lis_ite( string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    $opc_ima = !in_array('ima',$opc);
    $opc_var = in_array('var',$opc);
    $opc_htm = in_array('htm',$opc);
    $atr_ocu = isset($ope['atr_ocu']) ? $ope['atr_ocu'] : FALSE;
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( api_app::est_ide($dat) );
      $_atr = api_app::est($esq,$est,'atr');
      $ele['dat_val']['data-esq'] = $esq;
      $ele['dat_val']['data-est'] = $est;
      $est_ima = api_app::est($esq,$est,'opc.ima');
      // recorro atributos y cargo campos
      foreach( $ope['atr'] as $atr ){
        $ele_dat = $ele['dat_val'];
        $ele_dat['data-atr'] = $atr;         
        //ocultos
        if( $atr_ocu && in_array($atr,$atr_ocu) ) api_ele::cla($ele_dat,'dis-ocu');
        // contenido
        $ele_val = $ele['val'];

        $ide = "";
        if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
          api_ele::cla($ele_val,"tam-5 mar_hor-1");
          $ide = 'ima';
        }
        // variables
        else{
          // adapto estilos por tipo de valor
          if( !empty($_atr[$atr]) ){
            $var_dat = $_atr[$atr]->var_dat;
            $var_val = $_atr[$atr]->var_val;
          }
          elseif( !empty( $_var = api_app::tip( $val ) ) ){
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
          $ele_dat['data-val_dat'] = $var_dat;
          $ele_dat['data-val_tip'] = $var_val;
          $ide = $opc_var ? 'var' : 'tip';
        }
        $htm = api_dat::val_ver($ide,"{$esq}.{$est}.{$atr}",$val,$ele_val);
        if( $ide == "ima" ) $htm = "<div class='doc_val'>$htm</div>";
        $ele_dat['data-val'] = $ide;
        $_ .= "
        <td".api_ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? api_ele::cla($ele_dat,DIS_OCU) : $ele_dat ).">      
          {$htm}
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
          $htm = api_tex::let($dat_val);
        }
        $ele['dat_val']['data-atr'] = $ide;
        $_.="
        <td".api_ele::atr($ele['dat_val']).">
          {$htm}
        </td>";
      }
    }      
    return $_;
  }// detalles : posicion + descripciones + lecturas
  static function lis_det( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
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
          <p class='tex des'>".api_tex::let($val->$atr)."</p>
        </td>
      </tr>";
    }
    // por tipos : descripciones + ciclos + agrupaciones
    elseif( isset($ope["det_{$tip}"]) ){
      if( is_string($dat) ){
        extract( api_app::est_ide($dat) );
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
        $val = api_app::dat($esq,$est,$val);        
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        api_ele::cla($ele['atr'],"anc-100");
      }

      foreach( $ope["det_{$tip}"] as $atr ){
        $_ .= api_est::lis_det('pos',$dat,[$atr,$val],$ope,$ele,$opc);
      }
    }

    return $_;
  }

  /* Tablero */
  static function tab( string | array $ide, object $dat = NULL, array $ope = NULL, array $ele = NULL ) : string {
    $_ = "";
    if( is_string($ide) ){
      // tablero por aplicacion: esq.est.atr
      extract( api_app::est_ide($ide) );
      // convierto parametros por valores ($)
      if( isset($dat) && !empty($ope) ) $ope = api_obj::val_lis($ope,$dat);
      // aseguro identificador del tablero
      if( !isset($ope['ide']) && isset($dat->ide) ) $ope['ide'] = $dat->ide;
      if( $atr && class_exists($cla = "api_{$esq}") && method_exists($cla,"tab") ){
        $_ = $cla::tab( $est, $atr, $ope, $ele );
      }
    }
    return $_;
  }// datos: ide + est + dat + val[ pos, ver, opc ] + sec[ ima col ...opc ] + pos[ bor + ima + num + fec + tex ] + ...opc
  static function tab_dat( string $esq, string $est, string $atr, array $ope = [], array $ele = [] ) : array {
    foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ) $ele[$v] = []; }

    $_ = [ 
      'esq' => $esq,
      'tab' => $est,
      'est' => $est = $est.( !empty($atr) ? "_$atr" : $atr ) 
    ];

    if( empty($ele['sec']['class']) || !preg_match("/^tab/",$ele['sec']['class']) ) api_ele::cla($ele['sec'],
      "est tab {$_['esq']} {$_['tab']} {$atr}",'ini'
    );    
    // opciones
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc = $ope['opc'];

    // operador de opciones    
    if( !isset($ele['pos']['eti']) ) $ele['pos']['eti'] = "li";
    $ope['pos_cue'] = 0;// inicializo contador de posiciones
    if( !empty($ope['pos']['bor']) ) api_ele::cla($ele['pos'],"bor-1");
    
    // opciones
    $ope['val_pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
    $ope['val_pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen
    
    // dependencia por patrones del destino
    $ope['val_pos_dep'] = !empty($ope['sec']['pos_dep']);
    
    // ejecucion por contenido
    $ope['pos_eje'] = class_exists($cla = "api_{$_['esq']}") && method_exists($cla,"tab_pos");    
    
    // completo datos por aplicacion
    if( class_exists($cla) && method_exists($cla,"tab_dat") ) $cla::tab_dat($est,$atr,$ope,$ele);
    
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

  }// operadores : valores + seleccion + posicion + opciones( posicion | secciones )
  static function tab_ope( string $tip, string $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."tab_{$tip}";
    $_eje = self::$EJE."tab_{$tip}";
    $_ope = self::$TAB_OPE;
    // elementos
    if( !isset($ele['ope']) ) $ele['ope'] = [];
    // opciones
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    // proceso datos del tablero
    extract( api_app::est_ide($dat) );      
    // por aplicacion : posicion + seleccion
    if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];
    
    switch( $tip ){
    // Valores : acumulados + sumatorias + cuentas
    case 'val':
      // por acumulados
      $_ .= "
      <form class='ide-acu'>
        <fieldset class='doc_inf doc_ren'>
          <legend>Acumulados</legend>";

          $_ .= api_dat::var('est',"ope.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ]);
          
          $_ .= api_est::ope_acu($ope['val']['acu'],[ 
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
  
          <fieldset class='doc_inf doc_ren' data-esq='hol' data-est='kin'>
            <legend>Sumatorias del Kin</legend>

            ".api_est::ope_sum('hol.kin',$ope['val']['pos']['kin'])."

          </fieldset>          
        </form>";
      }
      // cuentas por estructura
      $_ .= "
      <section class='ide-cue inf pad_hor-2'>
        <h3>Totales por Estructura</h3>

        ".api_lis::dep( api_est::ope_cue('dat',$ope['est'],['ide'=>$_ide]), [ 
          'dep'=>['class'=>DIS_OCU], 
          'opc'=>['tog','ver','cue'] 
        ])."
        
      </section>";
      break;
    // Operador : Sección + Posición + Opción
    case 'opc':
      // -- controladores por aplicacion
      $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
        $_ = "";
        $_eje = "api_{$esq}.tab_{$tip}";
        
        // solo muestro las declaradas en el operador
        $ope_val = isset($ope[$tip]) ? $ope[$tip] : $opc;

        $ope_atr = array_keys($ope_val);

        $ope_var = api_app::var($esq,'tab',$tip);
  
        foreach( $ope_atr as $ide ){
  
          if( isset($ope_var[$ide]) ){
  
            $_ .= api_dat::var($esq,"tab.$tip.$ide", [
              'val'=>!empty($ope_val[$ide]) ? !empty($ope_val[$ide]) : NULL, 
              'ope'=>[ 'id'=>"{$_ide}-{$ide}", 'onchange'=>"$_eje(this);" ]
            ]);
          }
        } 
        return $_;
      };
      // Secciones        
      if( !empty($ope[$tip_opc = 'sec']) ){
        $ele_ide = self::$IDE."tab-{$tip_opc}";
        $ele_eve = self::$EJE."tab_{$tip_opc}(this);";
        $ele_ope = $ele['ope'];
        api_ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".api_ele::atr($ele_ope).">
          <fieldset class='doc_inf doc_ren'>
            <legend>Secciones</legend>";
            // operadores globales
            if( !empty($tab_sec = api_app::var('est','tab',$tip_opc)) ){ $_ .= "
              <div class='doc_val'>";
              foreach( api_app::var('est','tab',$tip_opc) as $ide => $ite ){
                if( isset($ope[$tip_opc][$ide]) ){ 
                  $_ .= api_dat::var('est',"tab.$tip_opc.$ide", [ 
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
      // Posiciones
      if( !empty($ope[$tip_opc = 'pos']) ){ 
        $ele_ide = self::$IDE."tab-{$tip_opc}";
        $ele_eve = self::$EJE."tab_{$tip_opc}(this);";
        $ele_ope = $ele['ope'];
        api_ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".api_ele::atr($ele_ope).">
          <fieldset class='doc_inf doc_ren'>
            <legend>Posiciones</legend>";
            // bordes            
            $ide = 'bor';
            $_ .= api_dat::var('est',"tab.$tip_opc.$ide",[
              'val'=>isset($ope[$tip_opc][$ide]) ? $ope[$tip_opc][$ide] : 0,
              'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
            ]);                
            // sin acumulados : color de fondo - numero - texto - fecha
            foreach( ['col','num','tex','fec'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){
                $_ .= api_dat::var('est',"tab.{$tip_opc}.{$ide}", [
                  'id'=>"{$ele_ide}-{$ide}",
                  'htm'=>api_dat::val_opc($ide, $ope['est'], [
                    'val'=>$ope[$tip_opc][$ide], 
                    'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                  ])
                ]);                      
              }
            }
            // con acumulados : imagen de fondo - ( ficha )
            foreach( ['ima'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){ $_ .= "
                <div class='doc_ren'>";
                  // vistas por acumulados
                  $_ .= api_dat::var('est',"tab.{$tip_opc}.{$ide}",[
                    'id'=>"{$ele_ide}-{$ide}",
                    'htm'=>api_dat::val_opc($ide, $ope['est'], [ 
                      'val'=>$ope[$tip_opc][$ide], 
                      'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                    ])
                  ]);
                  if( isset($ope['val']['acu']) ){ 
                    foreach( array_keys($ope['val']['acu']) as $ite ){
                      $_ .= api_dat::var('est',"tab.$tip_opc.{$ide}_{$ite}", [
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
      // Opciones
      if( !empty($opc) ){
        $tip = "opc";
        $_eje = "api_{$esq}.tab_{$tip}";
        $_ .= "

        <section class='ide-{$tip}'>";        
        foreach( $opc as $atr ){  
          $htm = "";
          foreach( api_app::var($esq,'tab',"{$tip}-{$atr}") as $ide => $val ){
            $val_ope = [
              'val'=>isset($ope[$atr][$ide]) ? $ope[$atr][$ide] : NULL,
              'ope'=>[ 'id'=>"{$_ide}-{$esq}-{$tip}_{$atr}-{$ide}" ]
            ];
            if( isset($val['ope']['tip']) && $val['ope']['tip'] != 'num' ){
              $val_ope['ope']['onchange'] = "$_eje('$atr',this)";
            }
            $htm .= api_dat::var($esq,"tab.{$tip}-{$atr}.$ide", $val_ope);
          }          
          // busco datos del operador 
          if( !empty($htm) && !empty($_ope = api_app::var($esq,'tab',$tip,$atr)) ){
            $ele_ope = $ele['ope'];
            api_ele::cla($ele_ope,"ide-{$esq}_tab_{$tip}-$atr",'ini'); $_ .= "
            <form".api_ele::atr($ele_ope).">
              <fieldset class='doc_inf doc_ren'>
                <legend>{$_ope['nom']}</legend>
                  {$htm}
              </fieldset>
            </form>";          
          }
        }$_ .= "
        </section>";
      }
      break;
    // Seleccion : Datos + Posiciones + Fechas
    case 'ver':
      $_ .= api_est::ope_ver([ 'dat'=>$ope['dat'], 'est'=>$ope['est'] ], $_ide, $_eje, 'ope_tam' );
      break;
    // Listado : Valores + Columnas + Descripciones
    case 'lis':
      // cargo operador con datos del tablero
      if( !isset($ope['ope']) ) $ope['ope'] = [ "ver", "atr", "des" ];
      if( !isset($ope['opc']) ) $ope['opc'] = [];
      array_push($ope['opc'],"ite_ocu");
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      api_ele::cla($ele['lis'],"mar_aba-0");
      $_ = api_est::lis($dat,$ope,$ele);
      break;
    }
    return $_;
  }// posicion
  static function tab_pos( string $esq, string $est, mixed $val, array &$ope, array $ele ) : string {

    // recibo objeto 
    if( is_object( $val_ide = $val ) ){
      $_dat = $val;
      $val_ide = intval($_dat->ide);
    }// o identificador
    else{
      if( class_exists($cla_dat = "api_$esq") && method_exists($cla_dat,'_') ){
        $_dat = $cla_dat::_("{$est}",$val);
      }      
    }
    //////////////////////////////////////////////
    // cargo datos ///////////////////////////////
    $e = $ele['pos'];
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
      if( !empty( $dat_est = api_app::est($esq,$est,'rel') ) ){

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
    if( !$ope['val_pos_dep'] ){
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
    if( !empty($cla_agr) ) api_ele::cla($e,implode(' ',$cla_agr),'ini');
    
    //////////////////////////////////////////////
    // Contenido html ///////////////////////////
    $htm = "";
    // metodo por aplicacion
    if( $ope['pos_eje'] ){
      $cla = "api_$esq";
      $htm = $cla::tab_pos($est,$val,$ope,$e);
    }
    // contenido automático
    if( empty($htm) && !isset($e['htm']) ){
      // color de fondo
      if( $ope['val_pos_col'] ){
        $_ide = api_app::est_ide($ope['val_pos_col']);
        if( isset($e[$dat_ide = "data-{$_ide['esq']}_{$_ide['est']}"]) && !empty( $_dat = api_app::dat($_ide['esq'],$_ide['est'],$e[$dat_ide]) ) ){
          $col = api_dat::val_ide('col', ...explode('.',$ope['val_pos_col']));          
          if( isset($col['val']) ){
            $col = $col['val'];
            $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
            api_ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : api_num::val_ran($val,$col) ) );
          }
        }
      }// imagen + numero + texto + fecha
      if( !isset($ele['ima']) ) $ele['ima'] = [];
      if( !empty($e['title']) ) $ele['ima']['title'] = FALSE;
      foreach( ['ima','num','tex','fec'] as $tip ){
        if( !empty($ope['pos'][$tip]) ){
          $ide = api_app::est_ide($ope['pos'][$tip]);
          $htm .= api_dat::val_ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
        }
      }
    }
    if( !isset($e['htm']) && !empty($htm) ){
      $e['htm'] = $htm;
    }
    //////////////////////////////////////////////
    // devuelvo posicion /////////////////////////
    $ope['pos_cue']++;// valor de posicion automatica-incremental
    api_ele::cla($e,"pos ide-{$ope['pos_cue']}",'ini');    
    return api_ele::eti($e);
  }// Secciones
  static function tab_sec( array $ope, array $ide ) : array {
    $_ = [];
    if( isset($ope['sec']) ){
      foreach( $ide as $i ){
        $_[$i] = isset($ope['sec'][$i]) ? ( 
          is_string($ope['sec'][$i]) ? explode(', ',$ope['sec'][$i]) : $ope['sec'][$i] 
        ) : FALSE;
      }
    }
    return $_;
  }

}
