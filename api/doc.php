<?php
// operadores
class doc {

  static string $IDE = "doc-";
  static string $EJE = "doc.";

  // botones : ( pan + win )
  static function bot( $dat ) : string {
    $_ = "";      
    $_eje = self::$EJE;
    
    foreach( $dat as $ide => $art ){
      
      $tip = isset($art['tip']) ? $art['tip'] : 'nav';

      $eje_tog = "{$_eje}{$tip}('$ide');";

      if( is_string($art) ){

        $_ .= dat::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_tog ]);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){

          $_ .= dat::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_tog ]);
        }
        elseif( isset($art['ico']) ){

          $_ .= dat::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_tog ]);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){

        $_ .= dat::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_tog ]);
      }
    }
    return $_;
  }

  // Modal : #sis > article[ide] > header + section
  static function win( string $ide, array $ope = [] ) : string {
    foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_eje = self::$EJE."win";
    $_ = "";
    // icono de lado izquierdo
    $cab_ico = "";
    if( isset($ope['ico']) ){
      if( is_string($ope['ico']) ){
        $cab_ico = dat::ico($ope['ico'],['class'=>"mar_hor-1"]);
      }// con menú
      else{
        $_ .= "
        <div class='ini'>";
          $_.="
        </div>";
      }
    }
    // titulo al centro
    $cab_tit = "";
    if( isset($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? tex::let($ope['nom']) : "" )."</h2>
    ";
    // botones de flujo
    $cab_bot = "
    <div class='ope'>
      ".dat::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
    </div>";
    // contenido 
    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = ele::val_dec( $ope['htm'] );
    }      
    // imprimo con identificador
    ele::cla($ope['art'],"ide-$ide",'ini');
    ele::cla($ope['art'],DIS_OCU);
    $_ = "
    <article".ele::atr($ope['art']).">

      <header".ele::atr($ope['cab']).">      
        {$cab_ico} 
        {$cab_tit} 
        {$cab_bot}
      </header>

      <div".ele::atr($ope['sec']).">
        {$ope['htm']}
      </div>
    </article>";
    return $_;
  }

  // Panel : nav|article[ide] > header + section
  static function pan( string $ide, array $ope = [] ) : string {
    foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
    $_eje = self::$EJE."pan";
    $_ = "";
    $cab_ico = "";
    if( !empty($ope['ico']) ) $cab_ico = dat::ico($ope['ico'],['class'=>"mar_hor-1"]);

    $cab_tit = "";
    if( !empty($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
    ";

    $eti_nav = isset($ope['nav']['eti']) ? $ope['nav']['eti'] : 'nav';

    $eti_sec = isset($ope['sec']['eti']) ? $ope['sec']['eti'] : 'div';

    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = ele::val_dec( $ope['htm'] );
    }

    // imprimo con identificador
    ele::cla($ope['nav'],"ide-$ide",'ini');
    ele::cla($ope['nav'],DIS_OCU);
    $_ = "
    <$eti_nav".ele::atr($ope['nav']).">

      <header".ele::atr($ope['cab']).">
      
        {$cab_ico} {$cab_tit} ".dat::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

      </header>

      <$eti_sec".ele::atr($ope['sec']).">

        {$ope['htm']}

      </$eti_sec>

    </$eti_nav>";
    
    return $_;
  }

  // Seccion : main > ...article
  static function sec( string | array $dat, array $ele = [] ) : string {
    $_ = "";
    if( isset($ele['tit']) ){ $_ .= "
      <header".ele::atr( isset($ele['cab']) ? $ele['cab'] : [] ).">";
        if( is_string($ele['tit']) ){ $_ .= "
          <h1 class='mar-0'>".tex::let($ele['tit'])."</h1>";
        }else{
          $_ .= ele::val_dec(...$ele['tit']);
        }$_ .= "
      </header>";
    }
    // contenido directo
    if( is_string($dat) ){ 
      $_ .= $dat;
    }
    // listado de articulos
    else{
      foreach( $dat as $ide => $art ){
        
        if( isset($art['htm'])){
          ele::cla($art,"ide-$ide",'ini');
          $_ .= "
          <article".ele::atr($art).">
            {$art['htm']}
          </article>";
        }
      }
    }
    return $_;
  }

  // Navegador : nav + * > ...[nav="ide"]
  static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
    $_ = "";
    $_eje = self::$EJE."nav";
    foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    $opc_ico = in_array('ico',$opc);
    $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
    // navegador 
    ele::cla($ele['lis'], "doc_nav $tip", 'ini');
    $_ .= "
    <nav".ele::atr($ele['lis']).">";    
    foreach( $dat as $ide => $val ){

      if( is_object($val) ) $val = obj::nom($val);

      if( isset($val['ide']) ) $ide = $val['ide'];

      $ele_nav = isset($val['nav']) ? $val['nav'] : [];

      $ele_nav['eti'] = 'a';
      ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

      if( $val_sel && $val_sel == $ide ) ele::cla($ele_nav,FON_SEL);

      if( $opc_ico && isset($val['ico']) ){
        $ele_nav['title'] = $val['nom'];
        ele::cla($ele_nav,"mar-0 pad-1 cir-1 tam-4",'ini');
        $_ .= dat::ico($val['ico'],$ele_nav);
      }
      else{
        $ele_nav['htm'] = $val['nom'];
        $_ .= ele::val($ele_nav);
      }        
    }$_.="
    </nav>";
    // contenido
    $eti_sec = isset($ele['sec']['eti']) ? $ele['sec']['eti'] : 'div';
    $eti_ite = isset($ele['ite']['eti']) ? $ele['ite']['eti'] : 'section';
    if( $tip != 'pes' && !$val_sel ) ele::cla($ele['sec'],DIS_OCU);
    $_ .= "
    <$eti_sec".ele::atr($ele['sec']).">";
      foreach( $dat as $ide => $val ){
        $ele_ite = $ele['ite'];
        ele::cla($ele_ite,"ide-$ide",'ini');
        if( !$val_sel || $val_sel != $ide ) ele::cla($ele_ite,DIS_OCU);
        $_ .= "
        <$eti_ite".ele::atr($ele_ite).">
          ".( isset($val['htm']) ? ( is_array($val['htm']) ? ele::val_dec($val['htm']) : $val['htm'] ) : '' )."
        </$eti_ite>";
      }$_.="
    </$eti_sec>";

    return $_;
  }

  // Conenedor : visible/oculto
  static function val( string | array $dat = NULL, array $ele = [] ) : string {
    $_eje = self::$EJE."val";
    foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
    
    // contenido textual
    if( is_string($dat) ) $dat = [
      'eti'=>"p", 'class'=>"tex-enf tex-cur", 'htm'=> tex::let($dat) 
    ];

    // contenedor : icono + ...elementos          
    ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".ele::atr( ele::cla( $ele['val'],"doc_val",'ini') ).">
    
      ".doc::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? ele::val($ele['htm_ini']) : '' )."
      
      ".ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {
    $_eje = self::$EJE."val";
    return dat::ico('val_tog', ele::eje($ele,'cli',"$_eje(this);",'ini'));
  }// - expandir / contraer
  static function val_ope( array $ele = [], ...$opc ) : string {
    $_eje = self::$EJE."val";      

    if( !isset($ele['ope']) ) $ele['ope'] = [];
    ele::cla($ele['ope'],"doc_ope",'ini');

    $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
    return "
    <fieldset".ele::atr($ele['ope']).">
      ".dat::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".dat::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";
  }// - Filtros : operador + valor textual + ( totales )
  static function val_ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "
    <fieldset class='ite'>";      
    // opciones de filtro por texto
    $_ .= dat::var_ope(['ver','tex'],[
      'ite'=>[ 
        'dat'=>"()($)dat()" 
      ],
      'eti'=>[ 
        'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
        'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
      ]
    ]);
    // ingreso de valor a filtrar
    $_ .= tex::var('ora', isset($dat['val']) ? $dat['val'] : '', [ 
      'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
      'name'=>"val",
      'title'=>"Introducir un valor de búsqueda...",
      'oninput'=>!empty($dat['eje']) ? $dat['eje'] : NULL,
      'class'=>isset($ele['class']) ? $ele['class'] : NULL,
      'style'=>isset($ele['style']) ? $ele['class'] : NULL
    ]);
    // agrego totales
    if( isset($dat['cue']) ){ $_ .= "
      <p class='mar_izq-1' title='Items totales'>
        <c>(</c><n name='tot'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c>)</c>
      </p>";
    }
    $_ .= "
    </fieldset>";
    return $_;
  }

  // Carteles : advertencia + confirmacion
  static function tex( string $tip, string | array $val, array $ope = [] ) : string {
    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ope[$i]) ) $ope[$i] = []; }
    ele::cla($ope['sec'],"doc_tex".( !empty($tip) ? " -$tip" : "" ),'ini');

    $_ = "
    <div".ele::atr($ope['sec']).">";

      if( !empty($ope['cab']) ){
        $_ .= "
        <div class='ite esp-ara'>
          <span></span>
          ".tex::let($ope['cab'])."
          <span></span>
        </div>";
      }

      if( !empty($tip) ){
        switch( $tip ){
        case 'err': $ope['ico']['title'] = "Error..."; break;
        case 'adv': $ope['ico']['title'] = "Advertencia..."; break;
        case 'opc': $ope['ico']['title'] = "Consultas..."; break;
        case 'val': $ope['ico']['title'] = "Notificación..."; break;
        }
        $_ .= dat::ico("val_tex-{$tip}", $ope['ico']);
      }

      $_ .= ( is_string($val) ? "<p".ele::atr($ope['tex']).">".tex::let($val)."</p>" : ele::val_dec($val) )."

    </div>";
    return $_;
  }

  // Menú de opciones
  static function opc(){
  }

}