<?php
// array [ ... ] : listado / tabla
class lis {

  static array $EST = [
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
  static array $EST_DAT = [
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
  static array $EST_OPE = [
    'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
    'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
    'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
    'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
    'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
  ];  
  static array $TAB_OPE = [
    'ver' => [ 'ide'=>"ver", 'ico'=>"dat_ver", 'nom'=>"Selección",'des'=>"" ],
    'opc' => [ 'ide'=>"opc", 'ico'=>"opc_bin", 'nom'=>"Opciones", 'des'=>"" ],
    'val' => [ 'ide'=>"val", 'ico'=>"est",     'nom'=>"Datos",    'des'=>"" ],
    'lis' => [ 'ide'=>"lis", 'ico'=>"lis_ite", 'nom'=>"Listado",  'des'=>"" ]
  ];
  static array $TAB_ATR = [
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
  static string $IDE = "lis-";
  static string $EJE = "lis.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_lis;
    $est = "_$ide";
    if( !isset($api_lis->$est) ) $api_lis->$est = dat::est_ini(DAT_ESQ,"lis{$est}");
    $_dat = $api_lis->$est;
    
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

  /* 
  Valores 
  */
  static function val( mixed $dat, mixed $ope = NULL ) : bool | array {
    $_ = [];
    if( empty($ope) ){ 
      $_ = is_array($dat) && array_keys($dat) === range( 0, count( array_values($dat) ) - 1 );
    }
    // ejecuto funciones
    elseif( is_array($dat) && is_callable($ope) ){
      
      foreach( $dat as $pos => $val ){

        $_ []= $ope( $val, $pos );
      }
    }  
    return $_;
  }// aseguro iteraciones
  static function val_ite( mixed $dat ) : array {

    return lis::val($dat) ? $dat : [ $dat ];
  }// convierto a listado : [ ...$$ ]
  static function val_cod( array | object $dat ) : array {
    $_ = $dat;
    if( obj::val_tip($dat) ){
      $_ = [];
      foreach( $dat as $v ){
        $_[] = $v;
      }
    }
    return $_;
  }// decodifica : "" => {} , []
  static function val_dec( array $dat, array $atr, ...$opc ) : array {
    foreach( $dat as &$ite ){

      if( is_object($ite) ){

        foreach( $atr as $ide ){
          
          if( isset($ite->$ide) ) $ite->$ide = obj::val_dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);
        }
      }
    }
    return $dat;
  }// proceso estructura
  static function val_est( array $dat, array $ope=[], ...$opc ) : array | object {    
    
    // junto estructuras
    if( isset($ope['jun']) ){
      if( is_array($ope['jun']) ){
        $dat = in_array('ini',$opc) ? array_merge($ope['jun'], $dat) : array_merge($dat, $ope['jun']);
      }
    }

    // ejecuto filtro
    if( isset($ope['ver']) ){
      $_ = [];
      foreach( $dat as $pos => $ite ){ 
  
        $val_ite = [];

        foreach( $ite as $atr => $val ){
  
          foreach( $ope['ver'] as $ver ){
  
            if( $atr == $ver[0] ) $val_ite []= dat::ver( $val, $ver[1], $ver[2] );
          }
        }
        // evaluo resultados
        if( count($val_ite) > 0 && !in_array(FALSE,$val_ite) ) $_[] = $ite;
  
      }      
      $dat = $_;
    }

    /* Decodifico atributos : ( "" => {} / [] ) */
    // - elementos
    if( isset($ope['ele']) ){ $dat = lis::val_dec($dat, lis::val_ite($ope['ele']), 'nom'); }
    // - objetos
    if( isset($ope['obj']) ){ $dat = lis::val_dec($dat, lis::val_ite($ope['obj']) ); }

    // nivelo estructura
    if( isset($ope['niv']) ){
      $_ = [];
      $ide = $ope['niv'];
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
      $dat = $_;
    }// armar navegacion por posicion para indice : xx-xx-xx
    elseif( isset($ope['nav']) && is_string($ope['nav']) ){
      $_ = [];
      $atr = $ope['nav'];
      // creo subniveles
      for( $nav=1; $nav<=7; $nav++ ){
        $_[$nav] = [];
      }
      // cargo por subnivel
      foreach( $dat as $val ){

        switch( $cue = count( $niv = explode('-', is_object($val) ? $val->$atr : $val[$atr] ) ) ){
          case 1: $_[$cue][$niv[0]] = $val; break;
          case 2: $_[$cue][$niv[0]][$niv[1]] = $val; break;
          case 3: $_[$cue][$niv[0]][$niv[1]][$niv[2]] = $val; break;
          case 4: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]] = $val; break;
          case 5: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]] = $val; break;
          case 6: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]] = $val; break;
          case 7: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]][$niv[6]] = $val; break;
        }
      }  
      $dat = $_;
    }

    // reducir elemento a un atributo
    if( isset($ope['red']) && is_string($ope['red']) ){
      $atr = $ope['red'];
      foreach( $dat as &$n_1 ){
        if( is_object($n_1) ){ if( isset($n_1->$atr) ) $n_1 = $n_1->$atr;
        }
        elseif( is_array($n_1) ){  
          foreach( $n_1 as &$n_2 ){  
            if( is_object($n_2) ){ if( isset($n_2->$atr) ) $n_2 = $n_2->$atr;
            }
            elseif( is_array($n_2) ){  
              foreach( $n_2 as &$n_3 ){  
                if( is_object($n_3) ){ if( isset($n_3->$atr) ) $n_3 = $n_3->$atr;
                }
                elseif( is_array($n_3) ){  
                  foreach( $n_3 as &$n_4 ){  
                    if( is_object($n_4) ){ if( isset($n_4->$atr) ) $n_4 = $n_4->$atr;
                    }
                    elseif( is_array($n_4) ){  
                      foreach( $n_4 as &$n_5 ){  
                        if( is_object($n_5) ){ if( isset($n_5->$atr) ) $n_5 = $n_5->$atr;
                        }
                        elseif( is_array($n_5) ){  
                          foreach( $n_5 as &$n_6 ){
                            if( is_object($n_6) ){ if( isset($n_6->$atr) ) $n_6 = $n_6->$atr;
                            }
                            elseif( is_array($n_6) ){  
                              foreach( $n_6 as &$n_7 ){  
                                if( is_object($n_7) ){ if( isset($n_7->$atr) ) $n_7 = $n_7->$atr;
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
    }
    
    // resultado: uno / muchos
    if( isset($ope['opc']) ){
      // aseguro parámetro      
      $ope['opc'] = lis::val_ite($ope['opc']);
      // valor unico
      if( in_array('uni',$ope['opc']) ){
        // primero, o el último
        if( in_array('fin',$ope['opc']) ) $dat = array_reverse($dat); 
        foreach( $dat as $i => $v ){ $dat = $dat[$i]; break; }
      }
    }

    return $dat;
  }
  
  /* 
  Variable
  */
  static function var( string $tip, string | array $dat, array $ope ){
    $_ = "";
    switch( $tip ){
    case 'pos': break;
    case 'ite': break;
    }
    return $_;
  }// operadores : tog + filtro 
  static function var_ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      
    $_ = "";

    $tod = empty($opc);
    
    if( $tod || in_array('tog',$opc) ){        
      
      $_ .= doc::val_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
    }
    if( $tod || in_array('ver',$opc) ){ 
      $_ .= doc::var('val','ver',[ 
        'des'=> "Filtrar...",
        'htm'=> doc::val_ver([ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
      ]);
    }

    if( !empty($_) ){ $_ = "
      <form".ele::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
        {$_}
      </form>";        
    }      
    return $_;
  }

  /* 
  Posicion: puteos, numerados, términos
  */
  static function pos( string | array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
    $_ = "";
    $_eje = self::$EJE."pos";
    // operador
    if( isset($ope['opc']) ) 
      $_ .= lis::var_ope('ite', $ope['opc'] = lis::val_ite($ope['opc']), $ope);
    
    // listado
    $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul';
    // por saltos de línea
    if( is_string($dat) ){
      if( empty($ope['lis']['class']) ) $ope['lis']['class'] = "tex_ali-izq mar-0 mar_ver-2";
      $_ .= "
      <$eti".ele::atr($ope['lis']).">";
      foreach( explode("\n",$dat) as $val ){ $_ .= "
        <li".ele::atr($ope['ite']).">".tex::let($val)."</li>";
      }$_ .= "
      </$eti>";
    }// por punteo o numerado
    elseif( lis::val($dat) ){
      $_ .= "
      <{$eti}".ele::atr($ope['lis']).">";
        foreach( $dat as $pos => $val ){
          $_ .= lis::pos_val( 1, $pos, $val, $ope, $eti );
        }$_.="
      </{$eti}>";
    }
    // por términos
    else{
      $eti = "dl";
      // agrego toggle del item
      ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
      $_ .= "
      <$eti".ele::atr($ope['lis']).">";
        foreach( $dat as $nom => $val ){ 
          $ope_ite = $ope['ite'];
          if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
          $_ .= "
          <dt".ele::atr($ope_ite).">
            ".tex::let($nom)."
          </dt>";
          foreach( lis::val_ite($val) as $ite ){ $_ .= "
            <dd".ele::atr($ope['val']).">
              ".tex::let($ite)."
            </dd>";
          }
        }$_.="
      </$eti>";
    }
    return $_;
  }
  static function pos_val( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
    $_ = "
    <li".ele::atr($ope['ite']).">";
      if( is_string($val) ){ 
        $_ .= $val;
      }// sublistas
      else{
        $niv++;
        $_.="
        <$eti data-niv='$niv'>";
        if( isset($ope['opc']) ){
          $opc = [];
          if( in_array('tog_dep',$ope['opc']) ) $opc []= "tog";
          $_ .= "<li>".lis::var_ope('ite',$opc,$ope)."</li>";
        }
        foreach( $val as $ide => $val ){
          $_ .= lis::pos_val( $niv, $ide, $val, $ope, $eti );
        }$_.="
        </$eti>";
      }
      $_ .= "
    </li>";
    return $_;
  }

  /* 
  Contenedor : ul > ...li > .val(.ico + tex-tit) + lis/htm
  */
  static function ite( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_ = "";
    // elementos        
    ele::cla($ope['lis'],"lis mar_hor-0",'ini');
    ele::cla($ope['dep'],"lis mar_hor-0",'ini');
    ele::cla($ope['ope'],"ite",'ini');      
    // operadores
    if( isset($ope['opc']) ) $_ .= lis::var_ope('val', lis::val_ite($ope['opc']), $ope);
    // listado
    $_ .= "
    <ul".ele::atr($ope['lis']).">";
    $ide = 0;
    foreach( $dat as $val ){
      $ide++;
      $_ .= lis::ite_val( 1, $ide, $val, $ope );
    }$_ .= "
    </ul>";
    return $_;
  }    
  static function ite_val( int $niv, int | string $ide, string | array $val, array $ope ) : string {  
    $ope_ite = $ope['ite'];      
    ele::cla($ope_ite,"pos ide-$ide",'ini');
    // con dependencia : evalúo rotacion de icono
    if( $val_lis = is_array($val) ){
      $ope_ico = $ope['ico'];
      $ele_dep = isset($ope["lis-$niv"]) ? ele::val_jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
      if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) ele::cla($ope_ico,"ocu");
      if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
      $val['ite_ope']['ico'] = $ope_ico;
    }// sin dependencias : separo item por icono vacío
    else{
      ele::cla($ope_ite,"sep");
    }
    $_ = "
    <li".ele::atr( isset($ope["ite-$ide"]) ? ele::val_jun($ope["ite-$ide"],$ope_ite) : $ope_ite  ).">

      ".( $val_lis ? doc::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
      
      if( $val_lis ){
        // sublista
        if( isset($val['lis']) ){
          $ope['dep']['data-niv'] = $niv;
          $_ .= "
          <ul".ele::atr($ele_dep).">";

          if( is_array($val['lis'])  ){
            // operador por dependencias : 1° item de la lista
            if( isset($ope['opc'])){
              $opc = [];
              foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
              if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
              if( !empty($opc) ) $_ .= "
              <li>".lis::var_ope('val',$opc,$ope)."</li>";
            }
            foreach( $val['lis'] as $i => $v ){

              $_ .= lis::ite_val( $niv+1, $i, $v, $ope );
            }
          }
          // listado textual
          elseif( is_string($val['lis']) ){

            $_ .= $val['lis'];
          }$_ .= "
          </ul>";
        }// contenido html directo ( asegurar elemento único )
        elseif( isset($val['htm']) ){

          $_ .= is_string($val['htm']) ? $val['htm'] : ele::val_dec($val['htm']);
        }
      }$_ .= "          
    </li>";        
    return $_;
  }

  /* 
  Indice: a[href] > ...a[href]
  */
  static function nav( array $dat, array $ele = [], ...$opc ) : string {    
    $_ = "";
    $_eje = self::$EJE."nav";// val | ver
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // operador
    ele::cla( $ele['ope'], "ren", 'ini' );
    $_ .= "
    <form".ele::atr($ele['ope']).">

      ".doc::val_ope()."

      ".doc::val_ver([ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}_ver(this);" ])."      

    </form>";
    
    // dependencias
    $tog_dep = FALSE;
    if( in_array('tog_dep',$opc) ){
      ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
      <form".ele::atr($ele['ope_dep']).">

        ".doc::val_ope()."

      </form>";
    }
    
    // armo listado de enlaces
    $_lis = [];
    $opc_ide = in_array('ide',$opc);
    ele::cla( $ele['lis'], "lis nav", 'ini' );
    foreach( $dat[1] as $nv1 => $_nv1 ){
      $ide = $opc_ide ? $_nv1->ide : $nv1;
      $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv1->nom}") ];
      if( !isset($dat[2][$nv1]) ){
        $_lis []= ele::val($eti_1);
      }
      else{
        $_lis_2 = [];
        foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
          $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
          $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv2->nom}") ];
          if( !isset($dat[3][$nv1][$nv2])  ){
            $_lis_2 []= ele::val($eti_2);
          }
          else{
            $_lis_3 = [];              
            foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
              $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
              $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv3->nom}") ];
              if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                $_lis_3 []= ele::val($eti_3);
              }
              else{
                $_lis_4 = [];                  
                foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                  $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                  $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv4->nom}") ];
                  if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                    $_lis_4 []= ele::val($eti_4);
                  }
                  else{
                    $_lis_5 = [];                      
                    foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                      $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                      $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv5->nom}") ];
                      if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                        $_lis_5 []= ele::val($eti_5);
                      }
                      else{
                        $_lis_6 = [];
                        foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                          $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                          $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv6->nom}") ];
                          if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                            $_lis_6 []= ele::val($eti_6);
                          }
                          else{
                            $_lis_7 = [];
                            // ... continuar ciclo
                            $_lis_6 []= [ 'ite'=>$eti_6, 'lis'=>$_lis_7 ];                              
                          }
                        }
                        $_lis_5 []= [ 'ite'=>$eti_5, 'lis'=>$_lis_6 ];
                      }
                    }
                    $_lis_4 []= [ 'ite'=>$eti_4, 'lis'=>$_lis_5 ];
                  }
                }
                $_lis_3 []= [ 'ite'=>$eti_3, 'lis'=>$_lis_4 ];
              }
            }
            $_lis_2 []= [ 'ite'=>$eti_2, 'lis'=>$_lis_3 ];  
          }
        }
        $_lis []= [ 'ite'=>$eti_1, 'lis'=>$_lis_2 ];
      }
    }
    // pido listado
    ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [];
    $_ .= lis::ite($_lis,$ele);
    return $_;
  }    

  /* 
  Barra: desplazamiento por item
  */
  static function bar( array $dat, array $ope = [] ) : string {
    $_ide = self::$IDE."bar";
    $_eje = self::$EJE."bar";      
    $_ = "";

    $pos = 0;
    $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
    if( !isset($ope['lis']) ) $ope['lis']=[];
    $_.="
    <ul".ele::atr(ele::cla($ope['lis'],"lis bar",'ini')).">";
      if( !isset($ope['ite']) ) $ope['ite'] = [];
      foreach( $dat as $ite ){ 
        $pos++;
        $ele_ite = $ope['ite'];
        ele::cla($ele_ite,"pos ide-$pos",'ini');
        if( $pos != $pos_ver ) ele::cla($ele_ite,DIS_OCU);
        $_.="
        <li".ele::atr($ope['ite']).">";
          // contenido html
          if( is_string($ite) ){
            $_ .= $ite;
          }// elementos html
          elseif( is_array($ite) ){
            $_ .= ele::val_dec($ite);
          }// modelo : titulo + detalle + imagen
          elseif( is_object($ite) ){

          } $_.= "
        </li>";
      }$_.="
    </ul>";
    // operadores
    $min = $pos == 0 ? 0 : 1;
    $max = $pos;
    $_eje .= "_ite";
    $_ .= "
    <form class='ope anc-100 jus-cen mar_ver-2'>

      ".num::var('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
              
      ".doc::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

      ".num::var('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

      ".doc::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

      ".num::var('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

    </form>";
    return $_;
  }
  
  /* 
  Operador
  */
  // cargo datos de un proceso ( absoluto o con dependencias )
  static function ope( array $dat ) : array {
    $_ = [];
    // cargo temporal
    foreach( $dat as $esq => $est_lis ){
      // recorro estructuras del esquema
      foreach( $est_lis as $est => $dat ){
        // recorro dependencias            
        foreach( ( !empty($dat_est = dat::est_ope($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
          // acumulo valores
          if( isset($dat->$ide) ) $_[$ref] = $dat->$ide;
        }
      }
    }
    global $api_dat;
    $api_dat->_ope_val []= $_;

    return $_;
  }// acumulado : posicion + marcas + seleccion
  static function ope_acu( array $dat, array $ope = [], array $opc = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."ope_acu";

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
  }// sumatorias por valores
  static function ope_sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
    $_ = "";
    extract( dat::ide($dat) );
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
  static function ope_ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
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
            $_eje = "doc.var('mar',this,'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
            $ele['htm_fin'] = "
            <fieldset class='ope'>
              ".doc::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
              ".doc::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
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
  static function ope_cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
    $_ = "";
    $_ide = self::$IDE."ope_cue";
    $_eje = self::$EJE."ope_cue";

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
          foreach( ( !empty($dat_opc_est = dat::est_ope($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){
            $est = str_replace("{$esq}_",'',$est);
            // armo listado para aquellos que permiten filtros
            if( $dat_opc_ver = dat::est_ope($esq,$est,'opc.ver') ){
              // nombre de la estructura
              $est_nom = dat::est($esq,$est)->nom;                
              $htm_lis = [];
              foreach( $dat_opc_ver as $atr ){
                // armo relacion por atributo
                $rel = dat::est_rel($esq,$est,$atr);
                // busco nombre de estructura relacional
                $rel_nom = dat::est($esq,$rel)->nom;
                // armo listado : form + table por estructura
                $htm_lis []= [ 
                  'ite'=>$rel_nom, 'htm'=>"
                  <div class='var mar_izq-2 dis-ocu'>
                    ".lis::ope_cue('est',"{$esq}.{$est}.{$atr}",$ope)."
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
      $ide = !empty($atr) ? dat::est_rel($esq,$est,$atr) : $est;
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

  /* 
  Tabla: thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
  */
  static function est( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."est";
    self::$EST_DAT = [];
    if( !isset($ope['opc']) ) $ope['opc']=[];
    foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
    // proceso estructura de la base
    if( $_val_dat = is_string($dat) ){
      extract( dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
      // identificador unico
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
      // cargo operadores
      self::$EST_DAT = lis::est_dat($esq,$est,$ope);      
      $_val = [ 'tit_cic'=>[], 'tit_gru'=>[], 'det_des'=>[], 'det_cic'=>[], 'det_gru'=>[] ];
      $_val_tit = [ 'cic', 'gru' ];
      $_val_det = [ 'des', 'cic', 'gru'];
      $ele_ite = [];
      $ope['atr_tot'] = 0;
      foreach( self::$EST_DAT as $esq_ide => $est_lis ){
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
      if( !empty( $_ope = obj::val_nom(lis::$EST_OPE,'ver',lis::val_ite($ope['ope'])) ) ){
        foreach( $_ope as $ope_ide => &$ope_lis ){
          $ope_lis['htm'] = lis::est_ope($ope_ide,$dat,$ope,$ele);
        }
        $_ = doc::nav('ope', $_ope,[ 'lis'=>['class'=>"mar_hor-1"] ],'ico','tog');
      }    
    }
    // imprimo listado
    ele::cla($ele['lis'],"lis est",'ini'); 
    $_ .= "
    <div".ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ $_ .= "
          <thead>
            ".lis::est_atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo        
        $_.="
        <tbody>";
          $pos_val = 0;   
          // recorro: por listado $dat = []                     
          if( !$_val_dat ){
            $_ .= lis::est_ite( $dat, $ope, $ele );
          }
          // estructuras de la base esquema
          else{
            // contenido previo : titulos por agrupaciones
            if( !empty($_val['tit_gru']) ){
              foreach( self::$EST_DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_gru']) ){
                    $_ .= lis::est_tit('gru', $dat_ide, $est_ope, $ele_ite['tit_gru'], $ope['opc']);
                  }                  
                }
              }
            }            
            // recorro datos
            $ope['opc'] []= "det_cit";
            foreach( $ope['dat'] as $pos => $val ){
              // 1- arriba: referencias + titulos por ciclos
              foreach( self::$EST_DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  // recorro por relaciones
                  if( $dat_rel = dat::est_ope($esq,$est,'rel') ){
                    foreach( $dat_rel as $atr => $ref ){
                      $ele['ite']["data-{$ref}"] = $_val_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_cic']) ){
                    $_ .= lis::est_tit('cic', $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite['tit_cic'], $ope['opc']);
                  }
                }
              }
              // 2- item por [ ...esquema.estructura ]
              $pos_val ++;
              $ele_pos = $ele['ite'];
              ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr".ele::atr($ele_pos).">";
              foreach( self::$EST_DAT as $esq => $est_lis ){
                // recorro la copia y leo el contenido desde la propiedad principal
                foreach( $est_lis as $est => $est_ope){
                  $_ .= lis::est_ite("{$esq}.{$est}", $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele, $ope['opc']);
                }
              }$_ .= "
              </tr>";
              // 3- abajo: detalles
              foreach( self::$EST_DAT as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  foreach( $_val_det as $ide ){
                    if( in_array($dat_ide = "{$esq}.{$est}", $_val["det_{$ide}"]) ){
                      $_ .= lis::est_det($ide, $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite["det_{$ide}"], $ope['opc']);
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
  }// armo Listado-Tabla 
  static function est_dat( string $esq, string $est, array $ope = [] ) : array {     
    $_ = [];

    $_ite = function( string $esq, string $est, array $ope = [] ) : array {
      
      // inicializo atributos de lista
      $_ = [];
              
      // reemplazo atributos por defecto
      if( isset($ope['atr']) ){
        $_['atr'] = lis::val_ite($ope['atr']);
        if( isset($_['atr_ocu']) ) unset($_['atr_ocu']);
      }
      $_ = obj::val_jun($_,$ope);

      // columnas
      if( empty($_['atr']) ) $_['atr'] = !empty( $_atr = dat::atr($esq,$est) ) ? array_keys($_atr) : [];
      // ocultas
      if( isset($ope['atr_ocu']) ) $_['atr_ocu'] = lis::val_ite($ope['atr_ocu']);
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
  }// operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
  static function est_ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."est_$tip";
    $_eje = self::$EJE."est_$tip";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
    }
    switch( $tip ){
    // Dato : abm por columnas
    case 'dat':
      foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
      $est_ope = self::$EST_DAT;
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
          ".doc::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          ".doc::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        ".doc::var('val','ver',[ 
          'nom'=>"Filtrar", 'htm'=> doc::val_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='val'>
            ".doc::ico($atr,[
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
        
          ".lis::ope_ver('lis', isset($_cue[$dat_tip][2]) ? $_cue[$dat_tip][2] : [], [ 'ope'=>$_var ] )."

        </form>";
        $_ .= "
        <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
          <td data-atr='val'>
            ".doc::ico( isset($lis->ocu) && in_array($atr,$lis->ocu) ? "ope_ver" : "ope_ocu",[
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
            
            ".lis::ope_acu($ope['val']['acu'],[
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

            ".lis::ope_ver('dat', $ope['est'], [
              'ope'=>[ 'id'=>"{$_ide}-val", 'max'=>$dat_tot, 'onchange'=>"$_eje();" ] 
            ])."
          </fieldset>
        </form>  

        <form class='ide-fec'>
          <fieldset class='inf ren'>
            <legend>por Fechas</legend>

            ".lis::ope_ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
              'ope'=>[ 'id'=>"{$_ide}-fec", 'tip'=>"fec_dia", 'onchange'=>"$_eje();" ] 
            ])."            
          </fieldset>          
        </form>
        <!--
        <form class='ide-pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>

            ".lis::ope_ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [                  
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
      foreach( self::$EST_DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          // datos de la estructura
          $est_dat = dat::est($esq,$est);
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip ren jus-ini mar_izq-2'>
            <fieldset class='ope'>
              ".doc::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
              ".doc::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
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
      foreach( self::$EST_DAT as $esq => $est_lis ){
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
      ".lis::ite( lis::ope_cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

      break;
    }
    return $_;
  }// columnas : por atributos
  static function est_atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // por muchos      
    if( isset($ope['est']) ){
      foreach( self::$EST_DAT as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          if( isset($est_ope['est']) ) unset($est_ope['est']);
          $_ .= lis::est_atr("{$esq}.{$est}",$est_ope,$ele);
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
  static function est_tit( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
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
            
            if( !empty($ide = dat::est_rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= lis::est_tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele,$opc);
              }
              self::$EST_DAT[$esq][$est]['cic_val'][$atr] = $pos = $val->$atr;
            }
          }
        }
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($ope["tit_$tip"]) ){

          foreach( $ope["tit_$tip"] as $atr ){

            if( !empty($ide = dat::est_rel($esq,$est,$atr)) ){

              foreach( dat::get($esq,$ide) as $val ){

                $_ .= lis::est_tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
              }
            }
          }
        }
      }        
    }
    return $_;
  }// fila : datos de la estructura
  static function est_ite( string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
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
          elseif( !empty( $_var = dat::tip( $val ) ) ){
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
  static function est_det( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
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
        $_ .= lis::est_det('pos',$dat,[$atr,$val],$ope,$ele,$opc);
      }
    }

    return $_;
  }

  /*
  Tablero
  */
  static function tab_dat( string $esq, string $est, string $atr, array &$ope = [], array &$ele = [] ) : array {
    foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ) $ele[$v] = []; }

    $_ = [ 
      'esq' => $esq,
      'tab' => $est,
      'est' => $est = $est.( !empty($atr) ? "_$atr" : $atr ) 
    ];

    if( empty($ele['sec']['class']) || !preg_match("/^tab/",$ele['sec']['class']) ) ele::cla($ele['sec'],
      "lis tab {$_['esq']} {$_['tab']} {$atr}",'ini'
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
    $ope['_val']['pos_eje'] = class_exists($_['esq']) && method_exists($_['esq'],"tab_pos");
    
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
          
          $_ .= lis::ope_acu($ope['val']['acu'],[ 
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

            ".lis::ope_sum('hol.kin',$ope['val']['pos']['kin'])."

          </fieldset>          
        </form>";
      }
      // cuentas por estructura
      $_ .= "
      <section class='ide-cue inf'>
        <h3>Totales por Estructura</h3>

        ".lis::ite( lis::ope_cue('dat',$ope['est'],['ide'=>$_ide]), [ 'dep'=>['class'=>DIS_OCU], 'opc'=>['tog','ver','cue'] ])."
        
      </section>";
      break;
    // Opciones : sección + posición
    case 'opc':
      // controladores por aplicacion
      $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
        $_ = "";
        $_eje = "{$esq}.tab_{$tip}";
        
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
        $ele_ide = self::$IDE."tab-{$tip_opc}";
        $ele_eve = self::$EJE."tab_{$tip_opc}(this);";
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
        $ele_ide = self::$IDE."tab-{$tip_opc}";
        $ele_eve = self::$EJE."tab_{$tip_opc}(this);";
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
          $_eje = "{$esq}.tab_{$atr}";

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

          ".lis::ope_ver('dat', $ope['est'], [ 
            'ope'=>[ 'onchange'=>"{$_eje}('val',this);" ] 
          ], 'ope_tam')."

        </fieldset>
      </form>

      <form class='ide-pos'>
        <fieldset class='inf ren'>
          <legend>Seleccionar por Posiciones</legend>

          ".lis::ope_ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [
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

          ".lis::ope_ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
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
      if( !isset($ope['ope']) ) $ope['ope'] = [ "val", "ver", "atr", "des" ];
      if( !isset($ope['opc']) ) $ope['opc'] = [];
      array_push($ope['opc'],"ite_ocu");
      var_dump($ope);
      $_ = lis::est($dat,$ope,$ele);
      break;
    }
    return $_;
  }// posicion
  static function tab_pos( string $esq, string $est, mixed $val, array &$ope, array $ele ){    
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
      if( !empty( $dat_est = dat::est_ope($esq,$est,'rel') ) ){

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
    // Contenido html ///////////////////////////
    $htm = !!$ope['_val']['pos_eje'] ? $esq::tab_pos($est,$val,$ope,$e) : "";
    if( empty($htm) ){
      // color de fondo
      if( $_val['pos_col'] ){
        $_ide = dat::ide($_val['pos_col']);
        if( isset($e[$dat_ide = "data-{$_ide['esq']}_{$_ide['est']}"]) && !empty( $_dat = dat::get($_ide['esq'],$_ide['est'],$e[$dat_ide]) ) ){
          $col = dat::val_ide('col', ...explode('.',$_val['pos_col']));          
          if( isset($col['val']) ){
            $col = $col['val'];
            $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
            ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : num::val_ran($val,$col) ) );
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
    ele::cla($e,"pos ide-{$ope['_tab_pos']}",'ini');    
    return "<{$ope['eti']}".ele::atr($e).">{$htm}</{$ope['eti']}>";
  }
}