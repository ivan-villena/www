<?php
// operadores
class api_doc {

  static string $IDE = "api_doc-";
  static string $EJE = "api_doc.";

  public function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_doc;
    $est = "_$ide";
    if( !isset($api_doc->$est) ) $api_doc->$est = api_dat::est_ini(DAT_ESQ,"doc{$est}");
    $_dat = $api_doc->$est;
    
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

  // botones : ( pan + win )
  static function bot( $dat ) : string {
    $_ = "";      
    $_eje = self::$EJE;
    
    foreach( $dat as $ide => $art ){
      
      $tip = isset($art['tip']) ? $art['tip'] : 'nav';

      $eje_tog = "{$_eje}{$tip}('$ide');";

      if( is_string($art) ){

        $_ .= api_fig::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_tog ]);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){

          $_ .= api_fig::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_tog ]);
        }
        elseif( isset($art['ico']) ){

          $_ .= api_fig::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_tog ]);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){

        $_ .= api_fig::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_tog ]);
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
        $cab_ico = api_fig::ico($ope['ico'],['class'=>"mar_hor-1"]);
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
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? api_tex::let($ope['nom']) : "" )."</h2>
    ";
    // botones de flujo
    $cab_bot = "
    <div class='doc_ope'>
      ".api_fig::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
    </div>";
    // contenido 
    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = api_ele::val_dec( $ope['htm'] );
    }      
    // imprimo con identificador
    api_ele::cla($ope['art'],"ide-$ide",'ini');
    api_ele::cla($ope['art'],DIS_OCU);
    $_ = "
    <article".api_ele::atr($ope['art']).">

      <header".api_ele::atr($ope['cab']).">      
        {$cab_ico} 
        {$cab_tit} 
        {$cab_bot}
      </header>

      <div".api_ele::atr($ope['sec']).">
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
    if( !empty($ope['ico']) ) $cab_ico = api_fig::ico($ope['ico'],['class'=>"mar_hor-1"]);

    $cab_tit = "";
    if( !empty($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
    ";
    // etiquetas
    $eti_nav = 'nav';
    if( isset($ope['nav']['eti']) ){
      $eti_nav = $ope['nav']['eti'];
      unset($ope['nav']['eti']);
    }
    $eti_sec = 'div';
    if( isset($ope['sec']['eti']) ){
      $eti_sec = $ope['sec']['eti'];
      unset($ope['sec']['eti']);
    }

    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = api_ele::val_dec( $ope['htm'] );
    }

    // imprimo con identificador
    api_ele::cla($ope['nav'],"ide-$ide",'ini');
    api_ele::cla($ope['nav'],DIS_OCU);
    $_ = "
    <$eti_nav".api_ele::atr($ope['nav']).">

      <header".api_ele::atr($ope['cab']).">
      
        {$cab_ico} {$cab_tit} ".api_fig::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

      </header>

      <$eti_sec".api_ele::atr($ope['sec']).">

        {$ope['htm']}

      </$eti_sec>

    </$eti_nav>";
    
    return $_;
  }

  // Seccion : main > ...article
  static function sec( string | array $dat, array $ele = [] ) : string {
    $_ = "";
    if( isset($ele['tit']) ){ $_ .= "
      <header".api_ele::atr( isset($ele['cab']) ? $ele['cab'] : [] ).">";
        if( is_string($ele['tit']) ){ $_ .= "
          <h1>".api_tex::let($ele['tit'])."</h1>";
        }else{
          $_ .= api_ele::val_dec(...$ele['tit']);
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
          api_ele::cla($art,"ide-$ide",'ini');
          $_ .= "
          <article".api_ele::atr($art).">
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
    api_ele::cla($ele['lis'], "doc_nav $tip", 'ini');
    $_ .= "
    <nav".api_ele::atr($ele['lis']).">";    
    foreach( $dat as $ide => $val ){

      if( is_object($val) ) $val = api_obj::val_nom($val);

      if( isset($val['ide']) ) $ide = $val['ide'];

      $ele_nav = isset($val['nav']) ? $val['nav'] : [];

      $ele_nav['eti'] = 'a';
      api_ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

      if( $val_sel && $val_sel == $ide ) api_ele::cla($ele_nav,FON_SEL);

      if( $opc_ico && isset($val['ico']) ){
        $ele_nav['title'] = $val['nom'];
        api_ele::cla($ele_nav,"mar-0 pad-1 cir-1 tam-4",'ini');
        $_ .= api_fig::ico($val['ico'],$ele_nav);
      }
      else{
        $ele_nav['htm'] = $val['nom'];
        $_ .= api_ele::val($ele_nav);
      }        
    }$_.="
    </nav>";
    
    // contenido
    $eti_sec = 'div';
    if( isset($ele['sec']['eti'])  ){
      $eti_sec = $ele['sec']['eti'];
      unset($ele['sec']['eti']);
    }
    $eti_ite = 'section';
    if( isset($ele['ite']['eti']) ){
      $eti_ite = $ele['ite']['eti'];
      unset($ele['ite']['eti']);
    }
    if( $tip != 'pes' && !$val_sel ) api_ele::cla($ele['sec'],DIS_OCU);
    $_ .= "
    <$eti_sec".api_ele::atr($ele['sec']).">";
      foreach( $dat as $ide => $val ){
        $ele_ite = $ele['ite'];
        api_ele::cla($ele_ite,"ide-$ide",'ini');
        if( !$val_sel || $val_sel != $ide ) api_ele::cla($ele_ite,DIS_OCU);
        $_ .= "
        <$eti_ite".api_ele::atr($ele_ite).">
          ".( isset($val['htm']) ? ( is_array($val['htm']) ? api_ele::val_dec($val['htm']) : $val['htm'] ) : '' )."
        </$eti_ite>";
      }$_.="
    </$eti_sec>";

    // agrupo contenedor del operador
    if( $tip == 'ope' ){
      $_ = "
      <div class='doc_ope-nav'>
        {$_}
      </div>";
    }

    return $_;
  }

  // Conenedor : visible/oculto
  static function val( string | array $dat = NULL, array $ele = [] ) : string {
    $_eje = self::$EJE."val";
    foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
    
    // contenido textual
    if( is_string($dat) ) $dat = [
      'eti'=>"p", 'class'=>"tex-enf tex-cur", 'htm'=> api_tex::let($dat) 
    ];

    // contenedor : icono + ...elementos          
    api_ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".api_ele::atr( api_ele::cla( $ele['val'],"doc_val",'ini') ).">
    
      ".api_doc::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? api_ele::val($ele['htm_ini']) : '' )."
      
      ".api_ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? api_ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {
    $_eje = self::$EJE."val";
    return api_fig::ico('val_tog', api_ele::eje($ele,'cli',"$_eje(this);",'ini'));
  }// - expandir / contraer
  static function val_ope( array $ele = [], ...$opc ) : string {
    $_eje = self::$EJE."val";      

    if( !isset($ele['ope']) ) $ele['ope'] = [];
    api_ele::cla($ele['ope'],"doc_ope",'ini');

    $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
    return "
    <fieldset".api_ele::atr($ele['ope']).">
      ".api_fig::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".api_fig::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";
  }// - Filtros : operador + valor textual + ( totales )
  static function val_ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "
    <fieldset class='doc_ite'>";      
    // opciones de filtro por texto
    $_ .= api_dat::var_ope(['ver','tex'],[
      'ite'=>[ 
        'dat'=>"()($)dat()" 
      ],
      'eti'=>[ 
        'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
        'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
      ]
    ]);
    // ingreso de valor a filtrar
    $_ .= api_tex::var('ora', isset($dat['val']) ? $dat['val'] : '', [ 
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
  static function tex( array $ope = [], array $ele = [] ) : string {
    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    api_ele::cla($ele['sec'],"doc_tex".( !empty($ope['tip']) ? " -{$ope['tip']}" : "" ),'ini');

    $_ = "
    <div".api_ele::atr($ele['sec']).">";

      // cabecera: icono + titulo
      if( isset($ope['tip']) || $ope['tit'] ){
        $_.="
        <header>";
          if( isset($ope['tip']) ){
            switch( $ope['tip'] ){
            case 'err': $ele['ico']['title'] = "Error..."; break;
            case 'adv': $ele['ico']['title'] = "Advertencia..."; break;
            case 'opc': $ele['ico']['title'] = "Consultas..."; break;
            case 'val': $ele['ico']['title'] = "Notificación..."; break;
            }
            api_ele::cla($ele['ico'],"mar-1");
            $_ .= api_fig::ico("val_tex-{$ope['tip']}", $ele['ico']);
          }
          if( isset($ope['tit']) ){
            if( is_string($ope['tit']) ) $_.="<p class='tit'>".api_tex::let($ope['tit'])."</p>";
          }
          $_.="
        </header>";
      }

      // contenido: texto
      if( isset($ope['tex']) ){        
        $ope_tex = [];
        if( is_array($ope['tex']) ){
          foreach( $ope['tex'] as $tex ){
            $ope_tex []= api_tex::let($tex);
          }
        }else{
          $ope_tex []= $ope['tex'];
        }
        $_ .= "<p".api_ele::atr($ele['tex']).">".implode('<br>',$ope_tex)."</p>" ;
      }
      // elementos
      if( isset($ope['htm']) ){
        $_ .= api_ele::val_dec($ope['tex']);
      }

      // botones: aceptar - cancelar
      if( isset($ope['opc']) ){
        $_ .= "
        <form class='doc_ope'>";
        if( isset($ope['opc']['ini']) ){
          
        }
        if( isset($ope['opc']['fin']) ){
        
        }
        $_ .= "
        </form>";
      }

      $_.="
    </div>";
    return $_;
  }

  // Menú de opciones
  static function opc(){
  }

}