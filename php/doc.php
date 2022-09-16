<?php

  // documento 
  class _doc {

    static string $IDE = "_doc-";
    static string $EJE = "_doc.";

    // icono
    static function ico( string $ide, array $ele=[] ) : string {
      $_="";
      $_ico = _api::_('ico');      
        // [ 'nom'=>'material-icons',					'url'=>'https://fonts.googleapis.com/css?family=Material+Icons' ],
        // [ 'nom'=>'material-icons-outlined', 'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Outlined' ],
        // [ 'nom'=>'material-icons-two-tone', 'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Two+Tone' ],
        // [ 'nom'=>'material-icons-round',		'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Round' ],
        // [ 'nom'=>'material-icons-sharp',		'url'=>'https://fonts.googleapis.com/css?family=Material+Icons+Sharp' ]            
      if( isset($_ico[$ide]) ){
        if( preg_match("/\//",$_ico[$ide]->val) ){
          
        }
        else{
          $ele['eti'] = isset($ele['eti']) ? $ele['eti'] : 'span';
          if( $ele['eti'] == 'button' ){
            if( empty($ele['type']) ) $ele['type'] = "button";
          }
          $ele['ide'] = $ide;
          $htm = $_ico[$ide]->val;
          $_ = "
          <{$ele['eti']}"._htm::atr(_ele::cla($ele,"ico material-icons-outlined",'ini')).">
            {$htm}
          </{$ele['eti']}>";
        }    
      }
      return $_;
    }

    // letra : ( n, c )
    static function let( string $dat, array $ele=[] ) : string {
      $_let = _api::_('let');
      $_pal = [];
      foreach( explode(' ',$dat) as $pal ){
        // numero completo
        if( is_numeric($pal) ){
          if( preg_match("/,/",$pal) ) $pal = str_replace(",","<c>,</c>",$pal);
          if( preg_match("/\./",$pal) ) $pal = str_replace(".","<c>.</c>",$pal);
          $_pal []= "<n>{$pal}</n>";
        }// caracteres por palabra
        else{
          $let = [];
          foreach( _tex::let($pal) as $car ){
            $ele_let = $ele;
            if( is_numeric($car) ){
              $let []= "<n"._htm::atr($ele_let).">{$car}</n>";
            }elseif( isset($_let[$car]) ){
              _ele::cla($ele_let,"{$_let[$car]->tip} _{$_let[$car]->var}",'ini');
              $let []= "<c"._htm::atr($ele_let).">{$car}</c>";        
            }else{            
              $let []= $car;
            }
          }
          $_pal []= implode('',$let);
        }
      }
      return implode(' ',$_pal);
    }

    // imagen : (span,button)[ima]
    static function ima( ...$dat ) : string {    
      $_ = "";
      // por aplicacion
      if( isset($dat[2]) ){

        $ele = isset($dat[3]) ? $dat[3] : [];

        // if( preg_match("/_ide$/",$dat[1]) ) $dat[1] = preg_replace("/_ide$/",'',$dat[1]);
        
        $ele['ima'] = "{$dat[0]}.{$dat[1]}";

        $_ = _app_dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
      }
      // por directorio: localhost/_/esquema/image
      else{
        $ele = isset($dat[1]) ? $dat[1] : [];
        $dat = $dat[0];

        if( is_array($dat) ){

          $ele = _ele::jun( $dat, $ele );
        }
        // por valor
        elseif( is_string($dat)){

          $ima = explode('.',$dat);
          $dat = $ima[0];
          $tip = isset($ima[1]) ? $ima[1] : 'png';
          $dir = SYS_NAV."_/{$dat}";
          $fic_ide ="{$dir}.{$tip}";
          _ele::css( $ele, _ele::fon($dir,['tip'=>$tip]) );
        }
        
        $ele['eti'] = isset($ele['eti']) ? $ele['eti'] : 'span';

        if( $ele['eti'] == 'button' ){
          if( empty($ele['type']) ) $ele['type'] = "button";
        }
        
        if( empty($ele['ima']) ){
          $ele['ima'] = $fic_ide;
        }

        $htm = "";
        if( !empty($ele['htm']) ){
          _ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');
          $htm = $ele['htm'];
        }

        $_ = "<{$ele['eti']}"._htm::atr($ele).">{$htm}</{$ele['eti']}>";
      }
      return $_;
    }

    // variable : div.atr > label + (input,textarea,select,button)[name]
    static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
      $_var_ope = [ 
        'ico'=>"", 'nom'=>"", 'des'=>"", 'ite'=>[], 'eti'=>[], 'ope'=>[], 'htm'=>"", 'htm_pre'=>"", 'htm_med'=>"", 'htm_pos'=>"" 
      ];
      $_ = "";

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

      // por formulario de la base
      if( $tip == 'atr' ){        

        if( !empty($_atr = _dat::atr($esq,$est,$atr)) ){

          $_var = [ 'nom'=>$_atr->nom, 'ope'=>$_atr->var ];
        }
      }
      // carga operadores: esquema - dato - valor
      elseif( $tip != 'val' ){ 

        $_var = _app::var($tip,$esq,$est,$atr);
      }

      // combino operadores
      if( !empty($_var) ){

        if( !empty($_var['ope']) ){
          $ele['ope'] = _ele::jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
          unset($_var['ope']);
        }
        $ele = _obj::jun($ele,$_var);
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
      $agr = _htm::dat($ele);

      // etiqueta
      if( !isset($ele['eti']) ) $ele['eti'] = [];
      $eti_htm='';
      if( !in_array('eti',$opc) ){
        if( !empty($ele['ico']) ){

          $eti_htm = _doc::ico($ele['ico']);
        }
        elseif( !empty($ele['nom']) ){
    
          $eti_htm = _doc::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
        }
        if( !empty($eti_htm) ){
    
          if( isset($ele['ope']['id']) ) $ele['eti']['for'] = $ele['ope']['id']; 
    
          $eti_htm = "<label"._htm::atr($ele['eti']).">{$eti_htm}</label>";
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
        $val_htm = _htm::val($ele['ope']);
      }
      // contenedor
      if( !isset($ele['ite']) ) $ele['ite']=[];      
      if( !isset($ele['ite']['title']) ){
        $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';
      }    
      return "
      <div"._htm::atr(_ele::cla($ele['ite'],"atr",'ini')).">
        ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
        {$eti_ini}
        {$val_htm}
        {$eti_fin}
        ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
      </div>
      ";   
    }

    // pestañas con secciones : nav + * > ...[nav="ide"]
    static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['nav','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."nav";
      $_ = "";
      
      $val_sel = isset($ele['sel']) ? $ele['sel'] : '';

      // navegador 
      _ele::cla($ele['nav'], $tip, 'ini');
      $_ .= "
      <nav"._htm::atr($ele['nav']).">";    
      foreach( $dat as $ide => $val ){

        if( is_object($val) ) $val = _obj::nom($val);

        if( isset($val['ide']) ) $ide = $val['ide'];

        $ele_nav = isset($val['nav']) ? $val['nav'] : [];

        $ele_nav['eti'] = 'a';

        if( !isset($val['ico']) && !isset($ele_nav['htm']) && !empty($val['nom']) ) $ele_nav['htm'] = $val['nom'];

        _ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

        if( $val_sel == $ide ) _ele::cla($ele_nav,FON_SEL);
    
        $_ .= _htm::val($ele_nav);
      }$_.="
      </nav>";
      // contenido
      $eti_sec = isset($ele['sec']['eti']) ? $ele['sec']['eti'] : 'section';
      $eti_ite = isset($ele['ite']['eti']) ? $ele['ite']['eti'] : 'div';
      $_ .= "
      <$eti_sec"._htm::atr($ele['sec']).">";
        foreach( $dat as $ide => $val ){
          $ele_ite = $ele['ite'];
          $ele_ite['data-ide'] = $ide;
          if( $val_sel != $ide ) _ele::cla($ele_ite,DIS_OCU); 
          $_ .= "
          <$eti_ite"._htm::atr($ele_ite).">
            ".( isset($val['htm']) ? ( is_array($val['htm']) ? _ele::val($val['htm']) : $val['htm'] ) : '' )."
          </$eti_ite>";
        }$_.="
      </$eti_sec>";

      return $_;
    }
  }

  // contenido : tog + ver
  class _doc_val {

    static string $IDE = "_doc_val-";
    static string $EJE = "_doc_val.";

    // visibilidad de bloques
    static function tog( string | array $dat = NULL, array $ele = [] ) : string {
      $_ide = self::$IDE."tog";
      $_eje = self::$EJE."tog";
      foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
      
      // contenido textual
      if( is_string($dat) ) $dat = [
        'eti'=>"p", 'class'=>"let-tit", 'htm'=>_doc::let($dat) 
      ];

      // contenedor : icono + ...elementos          
      _ele::eje( $dat,'cli',"$_eje(this);",'ini');

      return "
      <div"._htm::atr( _ele::cla( $ele['val'],"val tog",'ini') ).">
      
        "._doc_val::tog_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

        ".( isset($ele['htm_ini']) ? _htm::val($ele['htm_ini']) : '' )."
        
        "._htm::val( $dat )."

        ".( isset($ele['htm_fin']) ? _htm::val($ele['htm_fin']) : '' )."

      </div>";
    }
    // icono
    static function tog_ico( array $ele = [] ) : string {

      $_eje = self::$EJE."tog";

      return _doc::ico('ope_tog', _ele::eje($ele,'cli',"$_eje(this);",'ini'));
    }
    // operadores: expandir / contraer
    static function tog_ope( array $ele = [], ...$opc ) : string {
      $_ide = self::$IDE."tog";
      $_eje = self::$EJE."tog";      

      if( !isset($ele['ope']) ) $ele['ope'] = [];
      _ele::cla($ele['ope'],"ope",'ini');

      $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
      return "
      <fieldset"._htm::atr($ele['ope']).">
        "._doc::ico('ope_tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
        "._doc::ico('ope_nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');" ] )."        
      </fieldset>";
    }
    
    // filtros : texto
    static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ver";
      $_eje = self::$EJE."ver";

      switch( $tip ){
      // textuales
      case 'tex': 
        $dat['eje'] = !empty($dat['eje']) ? $dat['eje'] : NULL;
        // opciones de filtro por texto
        $_ .= _app::var_ope('opc',['ver','tex'],[
          'eti'=>[ 
            'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
            'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
          ],
          'ite'=>[ 'dat'=>"()($)dat()" ]
        ]);
        // ingreso de valor a filtrar
        $_ .= _doc_tex::ope('ora', isset($dat['val']) ? $dat['val'] : '', [ 'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
          'name'=>"val", 'title'=>"Introducir un valor de búsqueda...", 
          'oninput'=>$dat['eje'],
          'class'=>isset($dat['ele_val']['class']) ? $dat['ele_val']['class'] : NULL,
          'style'=>isset($dat['ele_val']['style']) ? $dat['ele_val']['class'] : NULL
        ]);
        if( isset($dat['cue']) ){ $_ .= "
          <c class='sep'>(</c><n name='tot' title='Items totales'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c class='sep'>)</c>";
        }
        break;        
      }
      
      return $_;
    }
    
  }

  // listado : ite + val + nav + bar
  class _doc_lis {

    static string $IDE = "_doc_lis-";

    static string $EJE = "_doc_lis.";

    // operadores : tog + filtro
    static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      
      $_ = "";

      $tod = empty($opc);
      
      if( $tod || in_array('tog',$opc) ){        
        
        $_ .= _doc_val::tog_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
      }
      if( $tod || in_array('ver',$opc) ){
        $_ .= _doc::var('val','ver',[ 
          'des'=>"Filtrar...",
          'htm'=>_doc_val::ver('tex',[ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
        ]);
      }

      if( !empty($_) ){ $_ = "
        <form"._htm::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
          {$_}
        </form>";        
      }      
      return $_;
    }

    // por opciones : <option value="" ...dat>
    static function opc( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      $ope_eti = !empty($ope['eti']) ? _obj::dec($ope['eti'],[],'nom') : [];

      if( isset($ope_eti['data-opc']) ){
        $opc = array_merge($opc,is_array($ope_eti['data-opc']) ? $ope_eti['data-opc'] : explode(',',$ope_eti['data-opc']) );
      }

      // etiqueta del contenedor
      $eti = isset($ope_eti['eti']) ? $ope_eti['eti'] : 'select';

      // aseguro valor
      $val = NULL;
      if( isset($ope['val']) ){
        $val = $ope['val'];
      }
      elseif( isset($ope_eti['val']) ){
        $val = $ope_eti['val'];
        unset($ope_eti['val']);
      }
      
      $_ = "
      <{$eti}"._htm::atr($ope_eti).">";

        if( in_array('nad',$opc) ){ $_ .= "
          <option default value=''>{-_-}</option>"; 
        }
        // items
        $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
        if( !empty($ope['gru']) ){

          foreach( $ope['gru'] as $ide => $nom ){ 

            if( isset($dat[$ide]) ){ $_.="
              <optgroup data-ide='{$ide}' label='{$nom}'>
                "._doc_lis::opc_ite( $dat[$ide], $val, $ope_ite, ...$opc )."                
              </optgroup>";
            }
          }
        }
        else{                        
          $_ .= _doc_lis::opc_ite( $dat, $val, $ope_ite, ...$opc );
        }
        $_ .= "
      </{$eti}>";

      return $_;
    }
    static function opc_ite( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);

      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = _obj::tip($v);
        break;
      }

      foreach( $dat as $i => $v){ 
        $atr=''; 
        $htm=''; 
        $e = $ope;

        // literal
        if( !$obj_tip ){  
          $e['value'] = $i;
          $htm = !!$opc_ide ? "{$i}: ".strval($v) : strval($v) ;
          $atr = _htm::atr($e);
        }
        // elemento
        elseif( $obj_tip == 'nom' ){
          $e = _ele::jun($e,$v);
          if( !isset($e['value']) ) $e['value'] = $i;
          $htm = isset($e['htm']) ? $e['htm'] : $i;
          $atr = _htm::atr($e);
        }
        // objeto ( ide + nom + des + tit )
        elseif( $obj_tip == 'atr' ){
          $_ide = isset($v->ide) ? $v->ide : FALSE ;
          $_htm = isset($v->nom) ? $v->nom : FALSE ;
          // valor
          if( isset($e['value']) ){ 
            $e['value'] = _obj::val($v,$e['value']); 
          }else{ 
            $e['value'] = $i;
            if( $_ide ){ $e['value'] = $_ide; }elseif( $_htm ){ $e['value'] = $_htm; }
          }
          // titulo con descripcion
          if( !isset($e['title']) ){ 
            if( isset($v->des) ){ 
              $e['title'] = $v->des; 
            }elseif( isset($v->tit) ){ 
              $e['title'] = $v->tit; 
            }
          }
          // contenido
          if( isset($e['htm']) ){
            $htm = _obj::val($v,$e['htm']);
          }else{
            if( !!$opc_ide && $_ide && $_htm ){
              $htm = "{$_ide}: {$_htm}";
            }elseif( $_htm ){
              $htm = $_htm;
            }else{
              $htm = $_ide; 
            }
          }
          $atr = _htm::atr($e,$v);            
        }// por posiciones
        else{
          $htm = "( \"".implode( '", "', $v )."\" )" ;
          $atr = _htm::atr($e);
        }
        // agrego atributo si está en la lista
        if( $val_ite ){ 
          if( $val_arr ){
            if( in_array($e['value'],$val) ) $atr .= " selected";
          }
          elseif( $val == $e['value'] ){

            $atr .= " selected";
          }
        }
        $_ .= "<option{$atr}>{$htm}</option>";
      }   
      return $_;
    }    

    // por posicion : dl, ul, ol
    static function ite( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
      $_ide = self::$IDE."ite";
      $_eje = self::$EJE."ite";
      $_ = "";

      // operador
      if( isset($ope['opc']) ){
        $_ .= _doc_lis::ope('ite', $ope['opc'] = _lis::ite($ope['opc']), $ope);
      }

      // por punteo o numerado
      if( _obj::pos($dat) ){
        $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
        $_ .= "
        <{$eti}"._htm::atr($ope['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= _doc_lis::ite_pos( 1, $pos, $val, $ope, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        // agrego toggle del item
        _ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
        $_ .= "
        <dl"._htm::atr($ope['lis']).">";
          foreach( $dat as $nom => $val ){ 

            $ope_ite = $ope['ite'];

            if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt"._htm::atr($ope_ite).">
              "._doc::let($nom)."
            </dt>";
            foreach( _lis::ite($val) as $ite ){ $_ .= "
              <dd"._htm::atr($ope['val']).">
                "._doc::let($ite)."
              </dd>";
            }
          }$_.="
        </dl>";
      }

      return $_;
    }
    static function ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
      $_ = "
      <li"._htm::atr($ope['ite']).">";
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
            $_ .= "<li>"._doc_lis::ope('ite',$opc,$ope)."</li>";
          }
          foreach( $val as $ide => $val ){
            $_ .= _doc_lis::ite_pos( $niv, $ide, $val, $ope, $eti );
          }$_.="
          </$eti>";
        }
        $_ .= "
      </li>";
      return $_;
    }

    // por valores : ul > ...li > .val(.ico + tex-tit) + lis/htm
    static function val( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      $_ide = self::$IDE."val";
      $_eje = self::$EJE."val";
      $_ = "";

      // elementos        
      _ele::cla($ope['lis'],"lis",'ini');
      _ele::cla($ope['ite'],"ite",'ini');
      _ele::cla($ope['dep'],"lis",'ini');
      _ele::cla($ope['ope'],"ite",'ini');
      
      // operadores
      if( isset($ope['opc']) ){
        $_ .= _doc_lis::ope('val', _lis::ite($ope['opc']), $ope);
      }

      // listado
      $_ .= "
      <ul"._htm::atr($ope['lis']).">";
      foreach( $dat as $ide => $val ){

        $_ .= _doc_lis::val_pos( 1, $ide, $val, $ope );
      }$_ .= "
      </ul>";      

      return $_;
    }    
    static function val_pos( int $niv, int | string $ide, string | array $val, array $ope ) : string {
    
      $ope_ite = $ope['ite'];      
      // con dependencia : evalúo rotacion de icono
      if( ( $val_lis = is_array($val) ) ){         
        $ope_ico = $ope['ico'];
        $ele_dep = isset($ope["lis-$niv"]) ? _ele::jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
        if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) _ele::cla($ope_ico,"ocu");
      }// sin dependencias : separo item por icono vacío
      else{
        _ele::cla($ope_ite,"sep");
      }
      $_ = "
      <li"._htm::atr( isset($ope["ite-$niv"]) ? _ele::jun($ope_ite  ,$ope["ite-$niv"]) : $ope_ite  ).">

        ".( $val_lis ? _doc_val::tog( isset($val['ite']) ? $val['ite'] : $ide, isset($val['ite_ope']) ? $val['ite_ope'] : ['ico'=>$ope_ico] ) : $val );
        
        if( $val_lis ){
          // sublista
          if( isset($val['lis']) ){
            $ope['dep']['data-niv'] = $niv;
            $_ .= "
            <ul"._htm::atr($ele_dep).">";

            if( is_array($val['lis'])  ){
              // operador por dependencias : 1° item de la lista
              if( isset($ope['opc'])){
                $opc = [];
                foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
                if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
                if( !empty($opc) ) $_ .= "
                <li>"._doc_lis::ope('val',$opc,$ope)."</li>";
              }
              foreach( $val['lis'] as $i => $v ){

                $_ .= _doc_lis::val_pos( $niv+1, $i, $v, $ope );
              }
            }// listado textual
            elseif( is_string($val['lis']) ){

              $_ .= $val['lis'];
            }$_ .= "
            </ul>";
          }// contenido html directo ( asegurar elemento único )
          elseif( isset($val['htm']) ){

            $_ .= is_string($val['htm']) ? $val['htm'] : _ele::val($val['htm']);
          }
        }$_ .= "          
      </li>";        
      return $_;
    }

    // indices : a[href] > ...a[href]
    static function nav( array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."nav_";// val | ver
      $_ = "";

      // operador
      _ele::cla( $ele['ope'], "ite", 'ini' );
      $_ .= "
      <form"._htm::atr($ele['ope']).">

        "._doc_val::tog_ope()."

        "._doc_val::ver('tex',[ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}ver(this);" ])."

      </form>";
      // dependencias
      $tog_dep = FALSE;
      if( in_array('tog_dep',$opc) ){
        _ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
        <form"._htm::atr($ele['ope_dep']).">
  
          "._doc_val::tog_ope()."
  
        </form>";
      }
      // armo listado de enlaces
      $_lis = [];
      $opc_ide = in_array('ide',$opc);
      _ele::cla( $ele['lis'], "nav", 'ini' );
      foreach( $dat[1] as $nv1 => $_nv1 ){
        $ide = $opc_ide ? $_nv1->ide : $nv1;
        $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv1->nom}") ];
        if( !isset($dat[2][$nv1]) ){
          $_lis []= _htm::val($eti_1);
        }
        else{
          $_lis_2 = [];
          foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
            $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
            $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv2->nom}") ];
            if( !isset($dat[3][$nv1][$nv2])  ){
              $_lis_2 []= _htm::val($eti_2);
            }
            else{
              $_lis_3 = [];              
              foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
                $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
                $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv3->nom}") ];
                if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                  $_lis_3 []= _htm::val($eti_3);
                }
                else{
                  $_lis_4 = [];                  
                  foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                    $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                    $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv4->nom}") ];
                    if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                      $_lis_4 []= _htm::val($eti_4);
                    }
                    else{
                      $_lis_5 = [];                      
                      foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                        $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                        $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv5->nom}") ];
                        if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                          $_lis_5 []= _htm::val($eti_5);
                        }
                        else{
                          $_lis_6 = [];
                          foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                            $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                            $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv6->nom}") ];
                            if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                              $_lis_6 []= _htm::val($eti_6);
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
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [];
      $_ .= _doc_lis::val($_lis,$ele);
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
      <ul"._htm::atr(_ele::cla($ope['lis'],"bar",'ini')).">";
        foreach( $dat as $ite ){ 
          $pos++;
          $ope['ite']['data-pos'] = $pos;
          $ope['ite']['class'] = ( $pos == $pos_ver ) ? "" : DIS_OCU;
          $_.="
          <li"._htm::atr($ope['ite']).">";
            // contenido html
            if( is_string($ite) ){
              $_ .= $ite;
            }// elementos html
            elseif( is_array($ite) ){
              $_ .= _ele::val($ite);
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
      <form class='ope anc-100 jus-cen mar_arr-1'>

        "._doc_num::ope('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
                
        "._doc::ico("nav_pre",['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

        "._doc_num::ope('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

        "._doc::ico("nav_pos",['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

        "._doc_num::ope('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

      </form>";
      return $_;
    }
  }

  // Opción : vacio + binario + unico + multiple + colores
  class _doc_opc {

    static string $IDE = "_doc_opc-";
    static string $EJE = "_doc_opc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      // vacíos : null
      case 'vac':
        $ope['type'] = 'radio'; 
        $ope['disabled'] = '1';
        if( is_nan($dat) ){ 
          $ope['val']="non";
        }elseif( is_null($dat) ){ 
          $ope['val']="nov";
        }                    
        break;
      // binarios : input checkbox = true/false
      case 'bin':
        $ope['type']='checkbox';
        if( !empty($dat) ){ $ope['checked']='checked'; }
        break;
      // opcion unica : input radio
      case 'uni':
        if( isset($ope['dat']) ){
          $_dat = $ope['dat'];
          unset($ope['dat']); 
          $_ .= "
          <div var='opc_uni'>";
          $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
          foreach( $_dat as $ide => $val ){
            $_ .= "
              <div class='val'>
                <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
                <input id='{$ope_ide}-{$ide}' type='radio' name='{$ide}' value='{$ide}'>
              </div>";
          }$_ .= "
          </div>";
        }
        break;
      // opcion múltiple : select[multiple]
      case 'mul':
        if( isset($ope['dat']) ){
          $val = $dat;
          $dat = $ope['dat'];
          unset($ope['dat']);
          $ope['multiple'] = '1';
          $_ = _doc_lis::opc( $dat, $ope, ...$opc);
        }
        break;          
      // medidas
      case 'col':
        $ope['type']='color';
        $ope['value'] = empty($dat) ? $dat : '#000000';
        break;
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";            
      }

      return $_;
    }
  }

  // Numero : codigo + separador + entero + decimal + rango
  class _doc_num {

    static string $IDE = "_doc_num-";

    static string $EJE = "_doc_num.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      if( $tip == 'val' ){

        if( !isset($ope['htm']) ) $ope['htm'] = $dat;

        $_ = "<n"._htm::atr($ope).">{$ope['htm']}</n>";
      }
      else{

        if( $tip != 'bit' ){
  
          if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;
  
          if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }
  
          if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
        }
  
        // controlo valores al actualizar
        _ele::eje($ope,'inp',"$_eje"."act(this);",'ini');
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');        
        
        switch( $tip ){
        case 'bit':
  
          $ope['type']='text';  
          break;          
        case 'int':
  
          if( !isset($ope['step']) ) $ope['step']='1'; 
  
          if( !empty($ope['value']) ){
  
            if( !empty($ope['num_pad']) ){
              
              if( !empty($ope['maxlength']) ){ 
                $tam = $ope['maxlength']; 
              }
              elseif( !empty($ope['max']) ){ 
                $tam = count(explode('',$ope['max'])); 
              }
            }
            if( !empty($tam) ){ 
              $ope['value'] = _num::val($ope['value'],$tam); 
            }
            if( !empty($ope['num_sep']) ){ 
              $ope['value'] = _num::int($ope['value']); 
            }
          }
          break;
        case 'dec':
          if( !empty($ope['value']) ){
  
            $ope['value'] = floatval($ope['value']);
  
            if( !empty($ope['maxlength']) ){
              $tam = explode(',',$ope['maxlength']); 
              $int = $tam[0]; 
              $dec = isset($tam[1]) ? $tam[1] : 0;
            }
            else{
  
              if( !empty($ope['num_dec']) ){ 
  
                $dec = $ope['num_dec']; 
              }
              elseif( isset($ope['step']) ){ 
                $dec = explode('.',$ope['step']); 
                $dec = isset($dec[1]) ? strlen($dec[1]) : 0;
              }
              if( isset($ope['num']) ){ 
  
                $int = strlen($ope['num']); 
              }
              elseif( isset($ope['max']) ){ 
  
                $int = strlen($ope['max']); 
              }
            }
            $tam = intval($int) + 1;
  
            if( empty($dec) ) $dec = 2;
  
            $ope['num_dec'] = $dec;
  
            if( !isset($ope['step']) ) $ope['step'] = '0.'._num::val('1',$dec);
  
            if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = _num::val($ope['value'],$tam);
  
            if( !empty($ope['num_sep']) ) $ope['value'] = _num::dec($ope['value']);
          }
          break;
        case 'ran':
          $ope['type']='range'; 
          if( !isset($ope['step']) ) $ope['step']=1; 
          if( !isset($ope['min']) )  $ope['min']=0; 
          if( !isset($ope['max']) )  $ope['max']=$ope['step'];
          // armo bloques : min < --- val --- > max / output
          if( !in_array('ver',$opc) ){            
            $cla = "";
            if( isset($ope['class']) ){ 
              $cla = "{$ope['class']}"; 
              unset($ope['class']); 
            }
            if( !isset($ope['id']) ){ 
              $ope['id'] = "_num_ran-"._app::var_ide('_num-ran');
            }
            $_ = "
            <div var='num_ran' class='{$cla}'>
  
              <div class='val'>
                <n class='_min'>{$ope['min']}</n>
                <c class='sep'><</c>
                <input"._htm::atr($ope).">
                <c class='sep'>></c>
                <n class='_max'>{$ope['max']}</n>
              </div>
  
              <output for='{$ope['id']}'>
                <n class='_val'>{$ope['value']}</n>
              </output>
            </div>";
          }
          break;
        }

        if( empty($_) && !empty($ope['type']) ){
          $_ = "<input"._htm::atr($ope).">";
        }
      }        

      return $_;
    }
  }

  // Fecha : aaaa-mm-dd hh::mm:ss
  class _doc_fec {

    static string $IDE = "_doc_fec-";

    static string $EJE = "_doc_fec.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      

      switch( $tip ){
      case 'val':
        $ope['value'] = $dat; $_ = "
        <time"._htm::atr($ope).">
          "._doc::let(_fec::var($dat))."
        </time>";
      case 'tie': 
        $ope['value'] = intval($dat);
        $ope['type']='numeric';
        break;
      case 'dyh': 
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='datetime-local';
        break;
      case 'hor':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='time';
        break;
      case 'dia':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='date';
        break;
      case 'sem':
        $ope['value'] = intval($dat);
        $ope['type']='week';
        break;
      case 'mes':
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      case 'año': 
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');
        $_ = "<input"._htm::atr($ope).">";
      }      

      return $_;
    }        
  }  

  // Texto : letra + palabra + párrafo + libro
  class _doc_tex {

    static string $IDE = "_doc_tex-";

    static string $EJE = "_doc_tex.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      // valor
      if( $tip == 'val' ){

        $ope['eti'] = !empty($ope['eti']) ? $ope['eti'] : 'font';

        $ope['htm'] = _doc::let( is_null($dat) ? '' : strval($dat) );

        $_ = _htm::val($ope);

      }// por tipos
      else{

        if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? _obj::cod($dat) : $dat );

        $ope['value'] = str_replace('"','\"',$dat);

        if( $tip == 'par' ){

          if( empty($ope['rows']) ) $ope['rows']="2";      
        }
        else{
          $ope['type']='text';
        }
        if( isset($ope['type']) ){
          $lis_htm = "";
          if( isset($ope['lis']) || isset($ope['dat']) ){
            if( isset($ope['lis']) ){
              $dat_lis = _obj::dec($ope['lis']);
              unset($ope['lis']);          
            }else{
              $dat_lis = [];
            }        
            if( empty($ope['id']) ){ 
              $ope['id']="_tex-{$tip}-"._app::var_ide("_tex-{$tip}-");
            }
            $ope['list'] = "{$ope['id']}-lis";
            $lis_htm = "
            <datalist id='{$ope['list']}'>";
              foreach( $dat_lis as $pos => $ite ){ $lis_htm .= "
                <option data-ide='{$pos}' value='{$ite}'></option>";
              }$lis_htm .="
            </datalist>";
          }
          // seleccion automática
          _ele::eje($ope,'foc',"this.select();",'ini');  
          $_ = "<input"._htm::atr($ope).">".$lis_htm;
        }
        else{
          $_ = "<textarea"._htm::atr($ope).">{$dat}</textarea>";
        }
      }      

      return $_;
    }
  }

  // Archivo del directorio: ./ "." tex ima mus vid app
  class _doc_arc {

    static string $IDE = "_doc_arc-";

    static string $EJE = "_doc_arc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      if( isset($ope['tip']) ) $ope['accept'] = _arc::tip($ope['tip']);

      switch( $tip ){
      case 'val':
        $ope['type']='file';
        if( isset($ope['multiple']) ) unset($ope['multiple']);
        break;
      case 'lis':
        $ope['type']='file';
        $ope['multiple'] = '1';
        break;
      case 'url':
        $ope['type']='url';
        break;
      // ima - vid - mus
      default:
        $ope['type']='file';
        $ope['accept'] = _arc::tip($tip);
        break;      
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";
      }
      return $_;
    }    
    
  }  

  // Obejeto : {}, []
  class _doc_obj {

    static string $IDE = "_doc_obj-";

    static string $EJE = "_doc_obj.";

    // operadores 
    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      // texto : json
      if( !isset($dat) || is_string($dat) ){
        $ope['value'] = strval($dat); $_ = "
        <textarea"._htm::atr($ope).">{$dat}</textarea>";
      }
      // por tipos: pos - nom - atr
      elseif( $tip = _obj::tip($dat) ){
        $cue = 0; 
        $htm = '';
        $cla_agr = ''; 
        $cla_tog = ' dis-ocu';
        if( in_array('ocu',$opc) ){ 
          $cla_agr = ' dis-ocu'; 
          $cla_tog = ''; 
        }
        $atr_agr = in_array('dat',$opc) ? '' : " disabled";
        // separadores
        if( $tip=='pos' ){ $ini='('; $fin=')'; }elseif( $tip=='nom' ){ $ini='['; $fin=']'; }else{ $ini='{'; $fin='}'; }
        // conteido
        if( is_object($dat) ){
          // ... incluir metodos
        }
        foreach( $dat as $i=>$v ){ 
          $cue++; 
          $htm .= _doc_obj::ite( $i, $v, $tip, ...$opc);
        }
        $ope['var']= "obj_{$tip}";
        $_ = "
        <div"._htm::atr($ope).">
          <div class='jus-ini mar_ver-1'>
            <p>
              <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
            </p>
            "._doc::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
            <ul class='ope _tog{$cla_agr}'>"; 
              if( empty($atr_agr) ){ $_.="
              "._doc::ico('ope_mar',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
              "._doc::ico('ope_des',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
              "._doc::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
              "._doc::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
              ";
              }$_.="
            </ul>
            <p class=' _tog{$cla_tog}'>
              <c class='sep _lis-fin'>{$fin}</c>
            </p>
          </div>
          <ul class='lis _atr ali-ini _tog{$cla_agr}'> 
            {$htm}
          </ul>
          <p class='_tog{$cla_agr}'>
            <c class='_lis-fin'>{$fin}</c>
          </p>
        </div>";
      }

      return $_;
    }
    // item por tipo
    static function ite( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      $ope['ent']=isset($ope['ent'])?$ope['ent']:'alm';
      
      $ope['eti']=isset($ope['eti'])?$ope['eti']:[]; 
      $ope['ite']=isset($ope['ite'])?$ope['ite']:[];      

      if( is_null($dat) ){ 
        $dat=''; 
        $tip_dat='val'; 
        $tip_val='vac'; 
        $_="<input type='radio' disabled>";
      }
      else{ 
        $tip = _val::tip($dat); 
        $tip_dat = $tip['dat']; 
        $tip_val = $tip['val']; 
      }

      $ite = "";
      if( in_array('dat',$opc) && $tip != 'val' ){ 
        $ite = "<input type='checkbox'>"; 
      }
      // items de lista -> // reducir dependencias
      $cla_ide = "_doc_{$tip_dat}";
      if( in_array($tip_dat,[ 'lis' ]) ){

        if( $ite != "" ){          
          $_ = $cla_ide::ope( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
        }
        else{
          $_ = _doc_lis::opc( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
        }
      }// controladores
      else{

        $dat = is_string($dat) ? $dat : strval($dat); 
        $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p"._htm::atr($ope['ite']).">{$dat}</p>";
      }
      $ide='';
      if( !empty($ite) ){ 
        $agr = "";
        if( $tip == 'pos' ){
          $agr = " tam='2'";
          $tip = "number";
        }else{
          $tip = "text";
        }
        $ide="<input class='ide' type='{$tip}'{$agr} value='{$ide}' title='{$ide}'>";
      }
      else{ 
        $ide="<c class='sep'>[</c><n>{$ide}</n><c class='sep'>]</c>";
      }
      if( $tip == 'pos' ){
        $sep='='; 
      }else{ 
        $sep=( $tip == 'nom' ) ? '=>' : ':' ; 
      }  
      $sep = "<c class='sep'>{$sep}</c>"; 

      return "
      <li class='atr' data-ide='{$ide}'>
        {$ite}{$ide}{$sep}{$_}
      </li>";  
    }
  }

  // Ejecucion : () => {} : $
  class _doc_eje {

    static string $IDE = "_doc_eje-";

    static string $EJE = "_doc_eje.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }  

  // Elemento : <>...</>
  class _doc_ele {

    static string $IDE = "_doc_ele-";

    static string $EJE = "_doc_ele.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }