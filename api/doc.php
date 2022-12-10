<?php
// operadores
class doc {

  static string $IDE = "doc-";
  static string $EJE = "doc.";

  public function __construct(){

    $this->_ico = dat::get('doc_ico', [ 'niv'=>['ide'] ]);
    
    $this->_var = [];    
    $this->_var_ide = [];
    $this->_ope_val = [];
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_doc;
    $est = "_$ide";
    if( !isset($api_doc->$est) ) $api_doc->$est = dat::est_ini(DAT_ESQ,"doc{$est}");
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

  // icono : .doc_ico.$ide
  static function ico( string $ide, array $ele=[] ) : string {
    $_ = "<span class='doc_ico'></span>";
    $doc_ico = doc::_('ico');
    if( isset($doc_ico[$ide]) ){
      $eti = 'span';      
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button"; $_ = "
      <{$eti}".ele::atr(ele::cla($ele,"doc_ico $ide",'ini')).">
        {$doc_ico[$ide]->val}
      </{$eti}>";
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

        $_ .= doc::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_tog ]);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){

          $_ .= doc::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_tog ]);
        }
        elseif( isset($art['ico']) ){

          $_ .= doc::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_tog ]);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){

        $_ .= doc::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_tog ]);
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
        $cab_ico = doc::ico($ope['ico'],['class'=>"mar_hor-1"]);
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
    <div class='doc_ope'>
      ".doc::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
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
    if( !empty($ope['ico']) ) $cab_ico = doc::ico($ope['ico'],['class'=>"mar_hor-1"]);

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
      
        {$cab_ico} {$cab_tit} ".doc::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

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
          <h1>".tex::let($ele['tit'])."</h1>";
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

      if( is_object($val) ) $val = obj::val_nom($val);

      if( isset($val['ide']) ) $ide = $val['ide'];

      $ele_nav = isset($val['nav']) ? $val['nav'] : [];

      $ele_nav['eti'] = 'a';
      ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

      if( $val_sel && $val_sel == $ide ) ele::cla($ele_nav,FON_SEL);

      if( $opc_ico && isset($val['ico']) ){
        $ele_nav['title'] = $val['nom'];
        ele::cla($ele_nav,"mar-0 pad-1 cir-1 tam-4",'ini');
        $_ .= doc::ico($val['ico'],$ele_nav);
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
  
  // Variable : div.doc_var > label + (select,input,textarea,button)[name]  
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
    
    // identificadores
    $dat_ide = is_string($ide) ? explode('.',$ide) : $ide;
    if( isset($dat_ide[2]) ){
      $esq = $dat_ide[0]; 
      $est = $dat_ide[1];
      $atr = $dat_ide[2];
    }
    elseif( isset($dat_ide[1]) ){
      $est = $dat_ide[0];
      $atr = $dat_ide[1];
    }
    else{
      $atr = $dat_ide[0];
    }

    // por atributi de la base
    if( $tip == 'atr' ){

      if( !empty($_atr = dat::atr($esq,$est,$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = doc::var_dat($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        $ele['ope'] = ele::val_jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = obj::val_jun($ele,$_var);
    }
    // identificadores
    if( empty($ele['ope']['id'])  && !empty($ele['ide']) ){
      $ele['ope']['id'] = $ele['ide'];
    }
    // aseguro valor
    if( isset($ele['val']) && !isset($ele['ope']['val']) ){
      $ele['ope']['val'] = $ele['val'];
    }
    // nombre en formulario
    if( empty($ele['ope']['name']) ){
      $ele['ope']['name'] = $atr;
    }      
    // agregados
    $agr = ele::htm($ele);

    // etiqueta
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    $eti_htm='';
    if( !in_array('eti',$opc) ){
      if( !empty($ele['ico']) ){
        $eti_htm = doc::ico($ele['ico']);
      }
      elseif( !empty($ele['nom']) ){    
        $eti_htm = tex::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      if( !empty($eti_htm) ){    
        if( isset($ele['ope']['id']) ) $ele['eti']['for'] = $ele['ope']['id'];     
        $eti_htm = "<label".ele::atr($ele['eti']).">{$eti_htm}</label>";
      }
    }

    // contenido medio
    if( !in_array('eti_fin',$opc) ){
      $eti_ini = $eti_htm.( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ); 
      $eti_fin = "";
    }else{
      $eti_ini = ""; 
      $eti_fin = ( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ).$eti_htm;
    }
    // valor            
    if( isset($agr['htm']) ){
      $val_htm = $agr['htm'];
    }
    else{
      if( isset($ele['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }
      if( empty($ele['ope']['name']) && isset($ele['ide']) ){
        $ele['ope']['name'] = $ele['ide'];
      }
      $val_htm = ele::val($ele['ope']);
    }
    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ){
      $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';
    }    
    return "
    <div".ele::atr(ele::cla($ele['ite'],"doc_var",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  }// armo controlador : nombre => valor
  static function var_dat( string $esq, string $dat='', string $val='', string $ide='' ) : array {    
    $_ = [];

    global $api_dat;
    // cargo todas las estructuras del esquema
    if( empty($dat) ){
      if( !isset($api_dat->_var[$esq]) ){
        $api_dat->_var[$esq] = dat::get('doc_var',[
          'ver'=>"`esq`='{$esq}'", 
          'niv'=>['dat','val','ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }elseif( empty($val) ){
      if( !isset($api_dat->_var[$esq][$dat]) ){
        $api_dat->_var[$esq][$dat] = dat::get('doc_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 
          'niv'=>['val','ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }else{
      if( !isset($api_dat->_var[$esq][$dat][$val]) ){
        $api_dat->_var[$esq][$dat][$val] = dat::get('doc_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 
          'niv'=>['ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }
    if( !empty($ide) ){
      $_ = isset($api_dat->_var[$esq][$dat][$val][$ide]) ? $api_dat->_var[$esq][$dat][$val][$ide] : [];
    }elseif( !empty($val) ){
      $_ = isset($api_dat->_var[$esq][$dat][$val]) ? $api_dat->_var[$esq][$dat][$val] : [];
    }elseif( !empty($dat) ){      
      $_ = isset($api_dat->_var[$esq][$dat]) ? $api_dat->_var[$esq][$dat] : [];
    }else{
      $_ = isset($api_dat->_var[$esq]) ? $api_dat->_var[$esq] : [];
    }

    return $_;
  }// selector de operaciones : select > ...option
  static function var_ope( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {
    global $api_dat;

    if( !isset($api_dat->_var_ope[$dat[0]][$dat[1]]) ){

      $_dat = dat::get( dat::_('ope'), [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      $api_dat->_var_ope[$dat[0]][$dat[1]] = opc::lis( $_dat, $ope, ...$opc);
    }

    return $api_dat->_var_ope[$dat[0]][$dat[1]];

  }// id por posicion
  static function var_ide( string $ope ) : string {
    global $api_dat;

    if( !isset($api_dat->_var_ide[$ope]) ) $api_dat->_var_ide[$ope] = 0;

    $api_dat->_var_ide[$ope]++;

    return $api_dat->_var_ide[$ope];
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
    return doc::ico('val_tog', ele::eje($ele,'cli',"$_eje(this);",'ini'));
  }// - expandir / contraer
  static function val_ope( array $ele = [], ...$opc ) : string {
    $_eje = self::$EJE."val";      

    if( !isset($ele['ope']) ) $ele['ope'] = [];
    ele::cla($ele['ope'],"doc_ope",'ini');

    $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
    return "
    <fieldset".ele::atr($ele['ope']).">
      ".doc::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".doc::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";
  }// - Filtros : operador + valor textual + ( totales )
  static function val_ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "
    <fieldset class='doc_ite'>";      
    // opciones de filtro por texto
    $_ .= doc::var_ope(['ver','tex'],[
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
    <section".ele::atr($ope['sec']).">";

      if( !empty($ope['cab']) ){ $_ .= "
        <header class='doc_ite tex-cen'>
          ".tex::let($ope['cab'])."          
        </header>";
      }

      if( !empty($tip) ){
        switch( $tip ){
        case 'err': $ope['ico']['title'] = "Error..."; break;
        case 'adv': $ope['ico']['title'] = "Advertencia..."; break;
        case 'opc': $ope['ico']['title'] = "Consultas..."; break;
        case 'val': $ope['ico']['title'] = "Notificación..."; break;
        }
        $_ .= doc::ico("val_tex-{$tip}", $ope['ico']);
      }

      $_ .= ( is_string($val) ? "<p".ele::atr($ope['tex']).">".tex::let($val)."</p>" : ele::val_dec($val) )."

    </section>";
    return $_;
  }

  // Menú de opciones
  static function opc(){
  }

}