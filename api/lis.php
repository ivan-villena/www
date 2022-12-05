<?php
// array [ ... ] : listado / tabla
class lis {

  static string $IDE = "lis-";
  static string $EJE = "lis.";

  function __construct(){
  }
  // getter
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

  // aseguro iteraciones 
  static function val( mixed $dat, mixed $ope = NULL ) : array {
    $_ = [];
    if( empty($ope) ) $_ = obj::pos($dat) ? $dat : [ $dat ];
    // ejecuto funciones
    elseif( is_array($dat) && is_callable($ope) ){
      
      foreach( $dat as $pos => $val ){

        $_ []= $ope( $val, $pos );
      }
    }  
    return $_;
  }// convierto a listado : [ ...$$ ]
  static function val_dec( array | object $dat ) : array {
    $_ = $dat;
    if( obj::val_tip($dat) ){
      $_ = [];
      foreach( $dat as $v ){
        $_[] = $v;
      }
    }
    return $_;
  }// elimina elementos
  static function val_eli( array &$dat, string $ope, mixed $val, ...$opc ) : array {
    $_ = [];
    $pos = 0;
    $opc_ide = in_array('ide',$opc);
    $lis_tip = obj::pos($dat);
    
    foreach( $dat as $i => $v ){

      if( !dat::ver( $opc_ide ? $i : $v, $ope, $val ) ) 
      
        $_[ $lis_tip ? $pos : $i ] = $v;

      $pos++;
    }
    
    return $dat = $_;
  }

  // operadores : tog + filtro
  static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      
    $_ = "";

    $tod = empty($opc);
    
    if( $tod || in_array('tog',$opc) ){        
      
      $_ .= doc::val_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
    }
    if( $tod || in_array('ver',$opc) ){ 
      $_ .= dat::var('val','ver',[ 
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

  // items : dl, ul, ol
  static function pos( string | array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
    $_ = "";
    $_eje = self::$EJE."pos";
    // operador
    if( isset($ope['opc']) ) 
      $_ .= lis::ope('ite', $ope['opc'] = lis::val($ope['opc']), $ope);
    
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
    elseif( obj::pos($dat) ){
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
          foreach( lis::val($val) as $ite ){ $_ .= "
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
          $_ .= "<li>".lis::ope('ite',$opc,$ope)."</li>";
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

  // contenedores : ul > ...li > .val(.ico + tex-tit) + lis/htm
  static function ite( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_ = "";
    // elementos        
    ele::cla($ope['lis'],"lis mar_hor-0",'ini');
    ele::cla($ope['dep'],"lis mar_hor-0",'ini');
    ele::cla($ope['ope'],"ite",'ini');      
    // operadores
    if( isset($ope['opc']) ) $_ .= lis::ope('val', lis::val($ope['opc']), $ope);
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
              <li>".lis::ope('val',$opc,$ope)."</li>";
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

  // tabla
  static function tab( array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    foreach( ['lis','ite','dat_val'] as $i ){ 
      if( !isset($ele[$i]) ) $ele[$i]=[]; 
    }
    // cargo datos de columnas    
    if( !isset($ope['atr_dat']) ) $ope['atr_dat'] =  dat::atr_ver($dat);
    if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['dat_atr']);
    
    // imprimo cabecera
    if( in_array('cab',$ope['opc']) ){ $_ .= "
      <thead>";
      foreach( $ope['atr'] as $atr ){ $_ .= "
        <th scope='col' data-atr='$atr'>
          <p>".tex::let( isset($ope['atr_dat'][$atr]->nom) ? $ope['atr_dat'][$atr]->nom : $atr )."</p>
        </th>";
      }$_ .= "
      </thead>";
    }    
    // imprimo contenido
    $_ .= "
    <tbody>
      ".lis::tab_ite($dat,$ope,$ele)."
    </tbody>";
    // armo pie
    if( isset($ope['pie']) ){

    }
    return $_;
  }// filas con datos
  static function tab_ite( array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc_htm = in_array('htm',$ope['opc']);
    // aseguro columnas
    foreach( $dat as $pos => $val ){
      $_val_obj = is_object($val);
      break;
    }
    // cargo columnas
    if( !isset($ope['atr_dat']) ) $ope['atr_dat'] =  dat::atr_ver($dat);
    if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['dat_atr']);
    if( !isset($ope['atr_tot']) ) $ope['atr_tot'] = count($ope['atr']);
    // recorro datos
    $pos_val = 0;    
    foreach( $dat as $pos => $val ){
      // titulos
      if( !empty($ope['tit'][$pos]) ) $_ .= lis::tab_pos('tit',$pos,$ope,$ele);
      // fila-columnas
      $pos_val++; 
      $ele_pos = $ele['ite'];
      ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_.="
      <tr".ele::atr($ele_pos).">";
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_obj ? $val->{$ide} : $val[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : ele::val($dat_val);
          }// textos
          else{
            $htm = tex::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td".ele::atr($ele['dat_val']).">{$htm}</td>";
        }      
        $_ .= "
      </tr>";
      // detalles
      if( !empty($ope['det'][$pos]) ) $_ .= lis::tab_pos('det',$pos,$ope,$ele);                    
    }
    return $_;
  }// titulo + detalle
  static function tab_pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {
    $_ = "";    
    if( isset($ope[$tip][$ide]) ){
      foreach( lis::val($ope[$tip][$ide]) as $val ){ 
        $_.="
        <tr".ele::atr($ele["{$tip}_ite"]).">

          <td".ele::atr(ele::val_jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">

            ".( is_array($val) ? ele::val($val) : "<p class='{$tip} tex_ali-izq'>".tex::let($val)."</p>" )."

          </td>
        </tr>";
      }        
    }
    return $_;
  }

  // Indice: a[href] > ...a[href]
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

  // horizontal con barra de desplazamiento por item
  static function bar( array $dat, array $ope = [] ) : string {
    $_ide = self::$IDE."bar";
    $_eje = self::$EJE."bar";      
    $_ = "";

    $pos = 0;
    $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
    if( !isset($ope['lis']) ) $ope['lis']=[];
    $_.="
    <ul".ele::atr(ele::cla($ope['lis'],"lis_bar",'ini')).">";
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
              
      ".dat::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

      ".num::var('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

      ".dat::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

      ".num::var('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

    </form>";
    return $_;
  }
}