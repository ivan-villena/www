<?php

// Listado
class _app_lis {

  static string $IDE = "_app_lis-";
  static string $EJE = "_app_lis.";
      
  // operadores : tog + filtro
  static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      
    $_ = "";

    $tod = empty($opc);
    
    if( $tod || in_array('tog',$opc) ){        
      
      $_ .= _app::val_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
    }
    if( $tod || in_array('ver',$opc) ){ 
      $_ .= _app::var('val','ver',[ 
        'des'=>"Filtrar...",
        'htm'=>_app::val_ver([ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
      ]);
    }

    if( !empty($_) ){ $_ = "
      <form"._ele::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
        {$_}
      </form>";        
    }      
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
    <ul"._ele::atr(_ele::cla($ope['lis'],"bar",'ini')).">";
      if( !isset($ope['ite']) ) $ope['ite'] = [];
      foreach( $dat as $ite ){ 
        $pos++;
        $ele_ite = $ope['ite'];
        _ele::cla($ele_ite,"pos ide-$pos",'ini');
        if( $pos != $pos_ver ) _ele::cla($ele_ite,DIS_OCU);
        $_.="
        <li"._ele::atr($ope['ite']).">";
          // contenido html
          if( is_string($ite) ){
            $_ .= $ite;
          }// elementos html
          elseif( is_array($ite) ){
            $_ .= _ele::dec($ite);
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

      "._app_var::num('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
              
      "._app::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

      "._app_var::num('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

      "._app::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

      "._app_var::num('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

    </form>";
    return $_;
  }
  // items : dl, ul, ol
  static function ite( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
    $_ = "";
    $_eje = self::$EJE."ite";
    // operador
    if( isset($ope['opc']) ) $_ .= _app_lis::ope('ite', $ope['opc'] = _lis::ite($ope['opc']), $ope);
    // por punteo o numerado
    if( _obj::pos($dat) ){
      $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
      $_ .= "
      <{$eti}"._ele::atr($ope['lis']).">";
        foreach( $dat as $pos => $val ){
          $_ .= _app_lis::ite_pos( 1, $pos, $val, $ope, $eti );
        }$_.="
      </{$eti}>";
    }
    // por términos
    else{
      // agrego toggle del item
      _ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
      $_ .= "
      <dl"._ele::atr($ope['lis']).">";
        foreach( $dat as $nom => $val ){ 

          $ope_ite = $ope['ite'];

          if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
          $_ .= "
          <dt"._ele::atr($ope_ite).">
            "._app::let($nom)."
          </dt>";
          foreach( _lis::ite($val) as $ite ){ $_ .= "
            <dd"._ele::atr($ope['val']).">
              "._app::let($ite)."
            </dd>";
          }
        }$_.="
      </dl>";
    }
    return $_;
  }
  static function ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
    $_ = "
    <li"._ele::atr($ope['ite']).">";
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
          $_ .= "<li>"._app_lis::ope('ite',$opc,$ope)."</li>";
        }
        foreach( $val as $ide => $val ){
          $_ .= _app_lis::ite_pos( $niv, $ide, $val, $ope, $eti );
        }$_.="
        </$eti>";
      }
      $_ .= "
    </li>";
    return $_;
  }
  // contenedores : ul > ...li > .val(.ico + tex-tit) + lis/htm
  static function val( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_ = "";
    // elementos        
    _ele::cla($ope['lis'],"lis",'ini');
    _ele::cla($ope['dep'],"lis",'ini');
    _ele::cla($ope['ope'],"ite",'ini');      
    // operadores
    if( isset($ope['opc']) ) $_ .= _app_lis::ope('val', _lis::ite($ope['opc']), $ope);
    // listado
    $_ .= "
    <ul"._ele::atr($ope['lis']).">";
    $ide = 0;
    foreach( $dat as $val ){
      $ide++;
      $_ .= _app_lis::val_pos( 1, $ide, $val, $ope );
    }$_ .= "
    </ul>";
    return $_;
  }    
  static function val_pos( int $niv, int | string $ide, string | array $val, array $ope ) : string {
  
    $ope_ite = $ope['ite'];      
    _ele::cla($ope_ite,"pos ide-$ide",'ini');
    // con dependencia : evalúo rotacion de icono
    if( $val_lis = is_array($val) ){
      $ope_ico = $ope['ico'];
      $ele_dep = isset($ope["lis-$niv"]) ? _ele::jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
      if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) _ele::cla($ope_ico,"ocu");
      if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
      $val['ite_ope']['ico'] = $ope_ico;
    }// sin dependencias : separo item por icono vacío
    else{
      _ele::cla($ope_ite,"sep");
    }
    $_ = "
    <li"._ele::atr( isset($ope["ite-$ide"]) ? _ele::jun($ope["ite-$ide"],$ope_ite) : $ope_ite  ).">

      ".( $val_lis ? _app::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
      
      if( $val_lis ){
        // sublista
        if( isset($val['lis']) ){
          $ope['dep']['data-niv'] = $niv;
          $_ .= "
          <ul"._ele::atr($ele_dep).">";

          if( is_array($val['lis'])  ){
            // operador por dependencias : 1° item de la lista
            if( isset($ope['opc'])){
              $opc = [];
              foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
              if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
              if( !empty($opc) ) $_ .= "
              <li>"._app_lis::ope('val',$opc,$ope)."</li>";
            }
            foreach( $val['lis'] as $i => $v ){

              $_ .= _app_lis::val_pos( $niv+1, $i, $v, $ope );
            }
          }
          // listado textual
          elseif( is_string($val['lis']) ){

            $_ .= $val['lis'];
          }$_ .= "
          </ul>";
        }// contenido html directo ( asegurar elemento único )
        elseif( isset($val['htm']) ){

          $_ .= is_string($val['htm']) ? $val['htm'] : _ele::dec($val['htm']);
        }
      }$_ .= "          
    </li>";        
    return $_;
  }
  // tabla
  static function tab( array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    foreach( ['lis'] as $i ){ 
      if( !isset($ele[$i]) ) $ele[$i]=[]; 
    }  
    return $_;
  }
}