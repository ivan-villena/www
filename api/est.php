<?php
// Valores
class est {

  static string $IDE = "est-";
  static string $EJE = "est.";  

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
  static array $OPE = [
    'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
    'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
    'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
    'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
    'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
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

  public function __construct(){    
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_est;
    $est = "_$ide";
    if( !isset($api_est->$est) ) $api_est->$est = dat::est_ini(DAT_ESQ,"est{$est}");
    $_dat = $api_est->$est;
    
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

  // proceso estructura
  static function val( array &$dat, array $ope=[], ...$opc ) : array | object {
    
    // junto estructuras
    if( isset($ope['jun']) ){
      est::val_jun($dat, $ope['jun'], ...$opc);
    }
    // ejecuto filtro
    if( isset($ope['ver']) ){
      est::val_ver($dat, $ope['ver'], ...$opc);
    }      
    // genero elementos
    if( isset($ope['ele']) ){
      est::val_dec($dat, $ope['ele'], 'nom');
    }
    // genero objetos  
    if( isset($ope['obj']) ){
      est::val_dec($dat, $ope['obj'] );
    }
    // nivelo estructura
    if( isset($ope['niv']) ){
      est::val_niv($dat, $ope['niv'] );
    }// o por indice
    elseif( isset($ope['nav']) && is_string($ope['nav']) ){
      est::val_nav($dat, $ope['nav'] );
    }
    // reduccion por atributo
    if( isset($ope['red']) && is_string($ope['red']) ){
      est::val_red($dat, $ope['red'] );
    }      
    // devuelvo unico objeto
    if( isset($ope['opc']) ){

      $ope['opc'] = lis::val($ope['opc']);

      if( in_array('uni',$ope['opc']) ) est::val_uni($dat, ...$opc );
    }
    return $dat;
  }// decodifica : "" => {} , []
  static function val_dec( array &$dat, string | array $atr, ...$opc ) : array {

    $atr = lis::val($atr);

    foreach( $dat as &$ite ){

      if( is_object($ite) ){

        foreach( $atr as $ide ){
          
          if( isset($ite->$ide) ) $ite->$ide = obj::val_dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);
        }
      }
    }
    
    return $dat;
  }// nivelar indice
  static function val_niv( array &$dat, mixed $ide ) : array {

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
  }// armar navegacion por posicion : xx-xx-xx
  static function val_nav( array &$dat, string $atr = 'pos' ) : array {
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
  }// reducir elemento a un atributo
  static function val_red( array &$dat, string $atr ) : array {    
    
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
  }// filtrar elementos
  static function val_ver( array &$dat, array $ope = [], ...$opc ) : array {
    $_ = [];

    foreach( $dat as $pos => $ite ){ 

      $val_ite = [];

      foreach( $ite as $atr => $val ){ 

        foreach( $ope as $ver ){ 

          if( $atr == $ver[0] ) 
            $val_ite []= val::ope_ver( $val, $ver[1], $ver[2] );
        }
      }
      // evaluo resultados
      if( count($val_ite) > 0 && !in_array(FALSE,$val_ite) ) $_[] = $ite;

    }

    return $dat = $_;
  }// juntar estructuras
  static function val_jun( array &$dat, array $ope = [], ...$opc ) : array {

    return $dat = array_merge($ope, $dat);
  }// agrupar elementos por atributo con funcion
  static function val_gru( array &$dat, array $ope = [], ...$opc ) : array {

    return $dat;
  }// ordenar elementos
  static function val_ord( array &$dat, array $ope = [], ...$opc ) : array {

    return $dat;
  }// limitar resultados
  static function val_lim( array &$dat, array $ope = [], ...$opc ) : array {

    return $dat;
  }// transforma a unico elemento
  static function val_uni( array &$dat, ...$opc ) : array | object {
    $_ = new stdClass;

    if( in_array('fin',$opc) ){ $dat = array_reverse($dat); }

    foreach( $dat as $i => $v ){
      $_ = $dat[$i];
      break;
    }

    return $dat = $_;
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
      extract( dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
      // identificador unico
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
      // cargo operadores
      self::$DAT = est::lis_dat($esq,$est,$ope);      
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
      if( !isset($ope['dat']) ) $ope['dat'] = dat::get($esq,$est);
      // valido item por { objeto( tabla) / array( joins) }
      foreach( $ope['dat'] as $val ){ $_val_obj = is_object($val); break; }
    }
    else{
      // datos de atributos
      if( !isset($ope['atr_dat']) ) $ope['atr_dat'] = dat::atr_ver($dat);
      // listado de columnas
      if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['atr_dat']);
      // total de columnas
      $ope['atr_tot'] = count($ope['atr']);
    }
    // imprimo operador
    if( isset($ope['ope']) ){
      if( empty($ope['ope']) ) $ope['ope'] = [ "dat" ];
      if( !empty( $_ope = obj::nom(est::$OPE,'ver',lis::val($ope['ope'])) ) ){
        foreach( $_ope as $ope_ide => &$ope_lis ){
          $ope_lis['htm'] = est::lis_ope($ope_ide,$dat,$ope,$ele);
        }
        $_ = doc::nav('ope', $_ope,[ 'lis'=>['class'=>"mar_hor-1"] ],'ico','tog');
      }    
    }
    // imprimo listado
    ele::cla($ele['lis'],"est",'ini'); 
    $_ .= "
    <div".ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ $_ .= "
          <thead>
            ".est::lis_atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo        
        $_.="
        <tbody>";
          $pos_val = 0;   
          // recorro: por listado $dat = []                     
          if( !$_val_dat ){
            $_ .= lis::tab_ite( $dat, $ope, $ele );
          }
          // estructuras de la base esquema
          else{
            // contenido previo : titulos por agrupaciones
            if( !empty($_val['tit_gru']) ){
              foreach( self::$DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_gru']) ){
                    $_ .= est::lis_tit('gru', $dat_ide, $est_ope, $ele_ite['tit_gru'], $ope['opc']);
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
                  if( $dat_rel = dat::est_ope($esq,$est,'rel') ){
                    foreach( $dat_rel as $atr => $ref ){
                      $ele['ite']["data-{$ref}"] = $_val_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_cic']) ){
                    $_ .= est::lis_tit('cic', $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite['tit_cic'], $ope['opc']);
                  }
                }
              }
              // 2- item por [ ...esquema.estructura ]
              $pos_val ++;
              $ele_pos = $ele['ite'];
              ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr".ele::atr($ele_pos).">";
              foreach( self::$DAT as $esq => $est_lis ){
                // recorro la copia y leo el contenido desde la propiedad principal
                foreach( $est_lis as $est => $est_ope){
                  $_ .= est::lis_ite("{$esq}.{$est}", $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele, $ope['opc']);
                }
              }$_ .= "
              </tr>";
              // 3- abajo: detalles
              foreach( self::$DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  foreach( $_val_det as $ide ){
                    if( in_array($dat_ide = "{$esq}.{$est}", $_val["det_{$ide}"]) ){
                      $_ .= est::lis_det($ide, $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite["det_{$ide}"], $ope['opc']);
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
  }// operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
  static function lis_ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis_$tip";
    $_eje = self::$EJE."lis_$tip";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( dat::ide($dat) );
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
        $tip_dat = explode('_', dat::atr($esq,$est,$atr)->ope['tip'])[0];
        if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
      }
      // operador : toggles + filtros
      $_ .= "
      <form class='val ide-dat jus-ini'>

        <fieldset class='ope'>
          ".fig::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          ".fig::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        ".doc::var('val','ver',[ 
          'nom'=>"Filtrar", 'htm'=> doc::val_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='val'>
            ".fig::ico($atr,[
              'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
            ])."
            <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
          </div>";
        }$_ .= "
        </fieldset>

      </form>";
      // listado
      $pos = 0; $_ .= "
      <table".ele::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
      foreach( $est_ope['atr'] as $atr ){
        $pos++;
        $_atr = dat::atr($esq,$est,$atr);
        $_var = [ 'id'=>"{$_ide}-{$atr}", 'onchange'=>"{$_eje}_val(this,'dat');" ];

        $dat_tip = explode('_',$_atr->ope['tip'])[0];
        if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
        if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
        if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
        if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
        $htm = "
        <form class='ren esp-bet'>
        
          ".val::ver('lis', isset($_cue[$dat_tip][2]) ? $_cue[$dat_tip][2] : [], [ 'ope'=>$_var ] )."

        </form>";
        $_ .= "
        <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
          <td data-atr='val'>
            ".fig::ico( isset($lis->ocu) && in_array($atr,$lis->ocu) ? "ope_ver" : "ope_ocu",[
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

            ".doc::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
            
            ".doc::var('app',"val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
            
            ".val::acu($ope['val']['acu'],[
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

            ".val::ver('dat', $ope['est'], [
              'ope'=>[ 'id'=>"{$_ide}-val", 'max'=>$dat_tot, 'onchange'=>"$_eje();" ] 
            ])."
          </fieldset>
        </form>  

        <form class='ide-fec'>
          <fieldset class='inf ren'>
            <legend>por Fechas</legend>

            ".val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
              'ope'=>[ 'id'=>"{$_ide}-fec", 'tip'=>"fec_dia", 'onchange'=>"$_eje();" ] 
            ])."            
          </fieldset>          
        </form>
        <!--
        <form class='ide-pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>

            ".val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [                  
              'ope'=>[ 'id'=>"{$_ide}-pos", 
                'tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'onchange'=>"$_eje();" 
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
          $est_dat = dat::est($esq,$est);
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip ren jus-ini mar_izq-2'>
            <fieldset class='ope'>
              ".fig::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
              ".fig::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
            </fieldset>";
            foreach( $est_ope['atr'] as $atr ){
              $_atr = dat::atr($esq,$est,$atr);
              $atr_nom = empty($_atr->nom) && $atr=='ide' ? dat::atr($esq,$est,'nom')->nom : $_atr->nom ;
              $htm .= doc::var('val',$atr,[
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

      ".lis::ite($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

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
                  $htm .= doc::var('val',$atr,[ 
                    'nom'=>"¿".dat::atr($esq,$est,$atr)->nom."?",
                    'ope'=>[ 'tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                  ]);
                }$htm .= "
                </form>";
                $lis_dep[] = [ 
                  'ite'=> doc::var_dat('app','est','ver',$ide)['nom'], 
                  'htm'=> $htm
                ];
              }
            }
            $lis_val[] = [
              'ite'=> dat::est($esq,$est)->nom,
              'lis'=> $lis_dep
            ];
          }
        }
      } 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Descripciones</h3>

      ".lis::ite($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Cuentas : total + porcentaje
    case 'cue':
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Cuentas</h3>
      ".lis::ite( val::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

      break;
    }
    return $_;
  }// armo Listado-Tabla 
  static function lis_dat( string $esq, string $est, array $ope = [] ) : array {    
    $_ = [];

    $_ite = function( string $esq, string $est, array $ope = [] ) : array {
      
      // inicializo atributos de lista
      $_ = [];
              
      // reemplazo atributos por defecto
      if( isset($ope['atr']) ){
        $_['atr'] = lis::val($ope['atr']);
        if( isset($_['atr_ocu']) ) unset($_['atr_ocu']);
      }
      $_ = obj::val_jun($_,$ope);

      // columnas
      if( empty($_['atr']) ) $_['atr'] = !empty( $_atr = dat::atr($esq,$est) ) ? array_keys($_atr) : [];
      // ocultas
      if( isset($ope['atr_ocu']) ) $_['atr_ocu'] = lis::val($ope['atr_ocu']);
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
  }// columnas : por atributos
  static function lis_atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // por muchos      
    if( isset($ope['est']) ){
      foreach( self::$DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          if( isset($est_ope['est']) ) unset($est_ope['est']);
          $_ .= est::lis_atr("{$esq}.{$est}",$est_ope,$ele);
        }
      }
    }
    // por 1: esquema.estructura
    else{
      // proceso estructura de la base
      if( $_val_dat = is_string($dat) ){
        extract( dat::ide($dat) );      
      }
      $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
      // cargo datos
      $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val_dat ? dat::atr($esq,$est) : dat::atr_ver($dat) );
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
        if( in_array($atr,$atr_ocu) ) ele::cla($ele_ite,DIS_OCU);
        // poner enlaces
        $htm = tex::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
        if( $ope_nav ) $htm = "<a href='".SYS_NAV."{$ope['nav']}' target='_blank'>{$htm}</a>";
        // ...agregar operadores ( iconos )
        $htm_ope = "";
        $_ .= "
        <th".ele::atr($ele_ite).">
          <p>{$htm}</p>
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
      extract( dat::ide($dat) );        
    }
    // 1 titulo : nombre + detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $ide = $val[1];
      $val = $val[2];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      $htm = "";
      
      if( !empty($htm_val = dat::val('nom',"{$esq}.{$ide}",$val)) ) $htm .= "
      <p class='tit'>".tex::let($htm_val)."</p>";
      
      if( !empty($htm_val = dat::val('des',"{$esq}.{$ide}",$val)) ) $htm .= "
      <q class='mar_arr-1'>".tex::let($htm_val)."</q>";
      
      if( in_array('ite_ocu',$opc) ) ele::cla($ele['ite'],'dis-ocu'); $_ .= "
      <tr".ele::atr($ele['ite']).">
        <td".ele::atr($ele['atr']).">{$htm}</td>
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
        ele::cla($ele['atr'],"anc-100");
      }
      // por ciclos : secuencias
      if( $tip == 'cic' ){
        // acumulo posicion actual, si cambia -> imprimo ciclo
        if( isset($ope['cic_val']) ){

          $val = dat::get($esq,$est,$val);
          // actualizo ultimo titulo para no repetir por cada item
          foreach( $ope['cic_val'] as $atr => &$pos ){
            
            if( !empty($ide = dat::ide_rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= est::lis_tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele,$opc);
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

            if( !empty($ide = dat::ide_rel($esq,$est,$atr)) ){

              foreach( dat::get($esq,$ide) as $val ){

                $_ .= est::lis_tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
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
      extract( dat::ide($dat) );
      $_atr = dat::atr($esq,$est);
      $ele['dat_val']['data-esq'] = $esq;
      $ele['dat_val']['data-est'] = $est;
      $est_ima = dat::est_ope($esq,$est,'opc.ima');
      // recorro atributos y cargo campos
      foreach( $ope['atr'] as $atr ){
        $ele_dat = $ele['dat_val'];
        $ele_dat['data-atr'] = $atr;         
        //ocultos
        if( $atr_ocu && in_array($atr,$atr_ocu) ) ele::cla($ele_dat,'dis-ocu');
        // contenido
        $ele_val = $ele['val'];
        
        if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
          ele::cla($ele_val,"tam-5 mar-1");
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
          elseif( !empty( $_var = val::tip_ver( $val ) ) ){
            $var_dat = $_var->dat;
            $var_val = $_var->val;
          }
          else{
            $var_dat = "val";
            $var_val = "nul";
          }
          // - limito texto vertical
          if( $var_dat == 'tex' ){
            if( $var_dat == 'par' ) ele::css($ele_val,"max-height:4rem;overflow-y:scroll");
          }
        }$_ .= "
        <td".ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? ele::cla($ele_dat,DIS_OCU) : $ele_dat ).">      
          ".dat::val_ver($ide,"{$esq}.{$est}.{$atr}",$val,$ele_val)."
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
          $htm = isset($dat_val['htm']) ? $dat_val['htm'] : ele::val($dat_val);
        }
        // textos
        else{
          $htm = tex::let($dat_val);
        }
        $ele['dat_val']['data-atr'] = $ide;
        $_.="
        <td".ele::atr($ele['dat_val']).">
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
      if( in_array('ite_ocu',$opc) ) ele::cla($ele['ite'],'dis-ocu');
      $_ = "
      <tr".ele::atr($ele['ite']).">
        <td".ele::atr($ele['atr']).">
          ".( in_array('det_cit',$opc) ? "<q>".tex::let($val->$atr)."</q>" : tex::let($val->$atr) )."
        </td>
      </tr>";
    }
    // por tipos : descripciones + ciclos + agrupaciones
    elseif( isset($ope["det_{$tip}"]) ){
      if( is_string($dat) ){
        extract( dat::ide($dat) );
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
        $val = dat::get($esq,$est,$val);        
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        ele::cla($ele['atr'],"anc-100");
      }

      foreach( $ope["det_{$tip}"] as $atr ){
        $_ .= est::lis_det('pos',$dat,[$atr,$val],$ope,$ele,$opc);
      }
    }

    return $_;
  }

}