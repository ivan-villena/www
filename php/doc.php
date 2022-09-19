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

        $_ = _doc_dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
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
  class _doc_ope {

    static string $IDE = "_doc_ope-";
    static string $EJE = "_doc_ope.";

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
      
        "._doc_ope::tog_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

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

  // listados : ite + val + nav + bar + est + tab
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
        
        $_ .= _doc_ope::tog_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
      }
      if( $tod || in_array('ver',$opc) ){
        $_ .= _doc::var('val','ver',[ 
          'des'=>"Filtrar...",
          'htm'=>_doc_ope::ver('tex',[ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
        ]);
      }

      if( !empty($_) ){ $_ = "
        <form"._htm::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
          {$_}
        </form>";        
      }      
      return $_;
    }

    // posicion : dl, ul, ol
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

    // valores : ul > ...li > .val(.ico + tex-tit) + lis/htm
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

        ".( $val_lis ? _doc_ope::tog( isset($val['ite']) ? $val['ite'] : $ide, isset($val['ite_ope']) ? $val['ite_ope'] : ['ico'=>$ope_ico] ) : $val );
        
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

        "._doc_ope::tog_ope()."

        "._doc_ope::ver('tex',[ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}ver(this);" ])."

      </form>";
      // dependencias
      $tog_dep = FALSE;
      if( in_array('tog_dep',$opc) ){
        _ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
        <form"._htm::atr($ele['ope_dep']).">
  
          "._doc_ope::tog_ope()."
  
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

  // datos : valores( nom + des + ima ) + seleccion( col + ima + tex + num + fec ) + opciones( esq + est + atr + val ) + imagen( ide + fic )
  class _doc_dat {

    static string $IDE = "_doc_dat-";
    static string $EJE = "_doc_dat.";

    // armo valores ( esq.est ): nombre, descripcion, imagen
    static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );

      $dat_var = _dat::var($esq,$est,$dat);
      $dat_val = _dat::val($esq,$est);

      // armo titulo : nombre <br> detalle
      if( $tip == 'ver' ){

        $_ = ( isset($dat_val->nom) ? _obj::val($dat_var,$dat_val->nom) : "" ).( isset($dat_val->des) ? "\n"._obj::val($dat_var,$dat_val->des) : "");
      }
      // por atributos con texto : nom + des + ima 
      elseif( isset($dat_val->$tip) ){

        if( is_string($dat_val->$tip) ) $_ = _obj::val($dat_var,$dat_val->$tip);
      }
      // armo imagen
      if( $tip == 'ima' ){

        // cargo titulo
        $tit = _doc_dat::val('ver',"$esq.$est",$dat);
        
        $_ = _doc::ima( [ 'style'=>$_, 'title'=>$tit ], $ele );
      }
      // armo variable
      elseif( $tip == 'var' ){
        
        $_ = "";

      }
      // armo textos
      elseif( !!$ele ){  

        if( empty($ele['eti']) ) $ele['eti'] = 'p';
        $ele['htm'] = _doc::let($_);
        $_ = _htm::val($ele);
      }    

      return $_;
    }

    // valor por seleccion ( esq.est.atr ) : texto, variable, icono, ficha, colores, html
    static function ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      // parametros: "esq.est.atr" 
      $ide = 'NaN';
      if( !is_object($dat) ){

        $ide = $dat;
        $dat = _dat::var($esq,$est,$dat);
      }
      elseif( isset($dat->ide) ){

        $ide = $dat->ide;
      }

      if( is_object($dat) && isset($dat->$atr) ){
        
        $_atr = _dat::atr($esq,$est,$atr);
        // variable por tipo
        if( $tip == 'var' ){
          $_var = $_atr->var;
          $_var['val'] = $dat->$atr;
          $_ = _htm::val($_val);
        }// proceso texto con letras
        elseif( $tip == 'htm' ){

          $_ = _doc::let($dat->$atr);
        }// color en atributo
        elseif( $tip == 'col' ){
          
          if( $col = _dat::val_ver('col',$esq,$est,$atr) ){
            $_ = "<div"._htm::atr(_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
          }else{
            $_ = "<div class='err fon-roj' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
          }
        }// imagen en atributo
        elseif( $tip == 'ima' ){

          if( !empty($_atr->var['dat']) ){
            $_ima_ide = explode('.',$_atr->var['dat']);
            $_ima['esq'] = $_ima_ide[0];
            $_ima['est'] = $_ima_ide[1];
          }
          if( !empty($_ima) || !empty( $_ima = _dat::val_ver('ima',$esq,$est,$atr) ) ){
            
            $_ = _doc::ima( $_ima['esq'], $_ima['est'], $dat->$atr, $ele );
          }
          else{
            $_ = "<div class='err fon-roj' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
          }
        }
        elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

          if( $tip=='tip' ){
            $tip = $_atr->var_dat;
          }
          if( $tip == 'num' ){
            $_ = "<n"._htm::atr($ele).">{$dat->$atr}</n>";
          }
          elseif( $tip == 'tex' ){
            $_ = "<p"._htm::atr($ele).">"._doc::let($dat->$atr)."</p>";
          }
          elseif( $tip == 'fec' ){
            $ele['value'] = $dat->$atr;
            $_ = "<time"._htm::atr($ele).">"._doc::let($dat->$atr)."</time>";
          }
          else{
            $_ = _doc::let($dat->$atr);
          }
        }
        else{

          $_ = $dat->$atr;
        }
      }
      else{
        if( is_null($dat->$atr) ){
          $_ = "<p title='Valor nulo para el objeto _{$esq}.{$est}[{$ide}].{$atr}'></p>";
        }else{
          $_ = "<div class='err fon-roj' title='No existe el atributo {$atr} para el objeto _{$esq}.{$est}[{$ide}]'>{-_-}</div>";
        }      
      }      

      return $_;
    }

    // selector por operador : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
    static function opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."opc(";
      $_eje = self::$EJE."opc(";
      // opciones
      $opc_esq = in_array('esq',$opc);
      $opc_est = in_array('est',$opc);
      $opc_val = in_array('val',$opc);
      $opc_ope_tam = in_array('ope_tam',$opc) ? "max-width: 6rem;" : NULL;
      // capturo elemento select
      if( !isset($ope['ope']) ) $ope['ope'] = [];
      if( empty($ope['ope']['name']) ) $ope['ope']['name'] = $ide;
      if( empty($ope['ope']['title']) ) $ope['ope']['title'] = "Seleccionar el Atributo de la Estructura...";
      // valor seleccionado
      if( isset($ope['val']) ) $_val = explode('-',$ope['val']);
      // cargo selector de estructura
      $ele_eje = isset($ope['ope']['onchange']) ? $ope['ope']['onchange'] : FALSE;
      $ele_val = [ 
        'eti'=>[ 'name'=>"val", 'title'=>"Seleccionar el valor buscado...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" ] 
      ];
      if( $opc_esq || $opc_est ){
        // operador por esquemas
        if( $opc_esq ){
          $dat_esq = [];
          $ele_esq = [ 
            'eti'=>[ 'name'=>"esq", 'title'=>"Seleccionar el Esquema de Datos...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" ] 
          ];
        }// operador por estructuras
        $ele_est = [ 
          'eti'=>[ 'name'=>"est", 'title'=>"Seleccionar la Estructura de datos...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" ] 
        ];
        // operador por relaciones de atributo
        $ope['ope'] = _ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
        if( !empty($opc_ope_tam) ) $ope['ope'] = _ele::css($ope['ope'],$opc_ope_tam);
        // oculto items
        $cla = DIS_OCU;
        // copio eventos
        if( $ele_eje ) $ele_est['eti'] = _ele::eje($ele_est['eti'],'cam',$ele_eje);
        // aseguro valores seleccionado
        if( $opc_esq ){          
          if( isset($_val[0]) ) $ele_esq['eti']['val'] = $_val[0];
          if( isset($_val[1]) ) $ele_est['eti']['val'] = $_val[1];
          if( isset($_val[2]) ) $ope['ope']['val'] = $_val[2];
          if( isset($_val[3]) ){ $ele_val['eti']['val'] = $_val[3]; $dat_val = []; }
        }else{
          if( isset($_val[0]) ) $ele_est['eti']['val'] = $_val[0];
          if( isset($_val[1]) ) $ope['ope']['val'] = $_val[1];
          if( isset($_val[2]) ){ $ele_val['eti']['val'] = $_val[2]; $dat_val = []; }
        }
      }else{
        if( isset($_val[0]) ) $ope['ope']['val'] = $_val[0];
        if( isset($_val[1]) ){ $ele_val['eti']['val'] = $_val[1]; $dat_val = []; }
      }
      // de donde tomo los datos? esquemas => estructuras
      $_ = "";
      // atributos por relacion
      $dat_ope = [];
      // estructuras
      $dat_est = [];
      // agrupador
      $ele_ope['gru'] = [];
      $ele_ope['eti'] = $ope['ope'];
      // proceso identificador de dato
      if( is_string($dat) || _obj::pos($dat) ){
        $_ide = is_string($dat) ? explode('.',$dat) : $dat;
        $dat = [ $_ide[0] => [ $_ide[1] ] ];
      }
      // opciones por operador de estructura
      $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
        $_ = [];
        // atributos parametrizados
        if( ( $dat_opc_ide = _dat::est_ope($esq,$est,$ide) ) && is_array($dat_opc_ide) ){  
          // recorro atributos + si tiene el operador, agrego la opcion      
          foreach( $dat_opc_ide as $atr ){
            // cargo atributo
            $_atr = _dat::atr($esq,$est,$atr);
            // identificador
            $dat = "{$esq}.";
            if( !empty($_atr->var['dat']) ){ $dat = $_atr->var['dat']; }else{ $dat .= _dat::atr_est($esq,$est,$atr); }  
            $_ []= [                 
              'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat, 'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 'htm'=>$_atr->nom 
            ];
          }
        }
        return $_;
      };
      $val_cla = isset($cla);
      $val_est = isset($ele_est['eti']['val']) ? $ele_est['eti']['val'] : FALSE;
      foreach( $dat as $esq_ide => $est_lis ){
        // cargo esquema [opcional]
        if( $opc_esq ){
          $dat_esq []= $esq_ide;
        }// recorro estructura/s por esquema
        foreach( $est_lis as $est_ide ){
          // busco estructuras dependientes
          if( $dat_opc_est = _dat::est_ope($esq_ide,$est_ide,'est') ){
            // recorro dependencias de la estructura
            foreach( $dat_opc_est as $dep_ide ){                
              // datos de la estructura relacional
              $_est = _dat::est($esq_ide,$dep_ide);
              $ite_val = "{$esq_ide}.{$dep_ide}";
              // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
              if( !empty( $_opc_val = $_opc_ite($esq_ide, $dep_ide, $ide, $val_cla && ( !$val_est || $val_est != $ite_val ) ? $cla : "") ) ){
                // con selector de estructura
                if( $opc_est ){
                  // cargo opcion de la estructura
                  $dat_est[] = [ 'value'=>$ite_val, 'htm'=>isset($_est->nom) ? $_est->nom : $est ];
                  // cargo todos los atributos a un listado general
                  array_push($dat_ope, ...$_opc_val);

                }// por agrupador
                else{
                  // agrupo por estructura
                  $ele_ope['gru'][$_est->ide] = isset($_est->nom) ? $_est->nom : $est;
                  // cargo atributos por estructura
                  $dat_ope[$_est->ide] = $_opc_val;
                }                    
              }
            }
          }// estructura sin dependencias
          else{
            $dat_ope[] = $_opc_ite($esq_ide, $dep_ide, $ide);
          }
        }
      }
      // selector de esquema [opcional]
      if( $opc_esq ){
        $_ .= _doc_opc::val($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
      }
      // selector de estructura [opcional]
      if( $opc_esq || $opc_est ){
        $_ .= _doc_opc::val($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
      }
      // selector de atributo con nombre de variable por operador
      $_ .= _doc_opc::val($dat_ope,$ele_ope,'nad');
      
      // selector de valor por relacion
      if( $opc_val ){
        // copio eventos
        if( $ele_eje ) $ele_val['eti'] = _ele::eje($ele_val['eti'],'cam',$ele_eje);
        $_ .= "
        <div class='val'>
          <c class='sep'>:</c>
          "._doc_opc::val( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
          <span class='ico'></span>
        </div>";
      }

      return $_;
    }

    // conteos : por valores de estructura relacionada por atributo
    static function cue( string $tip, string | array $dat, array $ope = [], ...$opc ) : string | array {
      $_ = "";
      $_ide = self::$IDE."cue";
      $_eje = self::$EJE."cue";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      switch( $tip ){        
      case 'dat': 
        $_ = [];
        
        // -> por esquemas
        foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){

          // -> por estructuras
          foreach( $est_lis as $est_ide ){

            // -> por dependencias ( est_atr )
            foreach( ( !empty($dat_opc_est = _dat::est_ope($esq,$est_ide,'est')) ? $dat_opc_est : [ $est_ide ] ) as $est ){

              // armo listado para aquellos que permiten filtros
              if( $dat_opc_ver = _dat::est_ope($esq,$est,'ver') ){
                // nombre de la estructura
                $est_nom = _dat::est($esq,$est)->nom;                
                $htm_lis = [];
                foreach( $dat_opc_ver as $atr ){
                  // armo relacion por atributo
                  $rel = _dat::atr_est($esq,$est,$atr);
                  // busco nombre de estructura relacional
                  $rel_nom = _dat::est($esq,$rel)->nom;
                  // armo listado : form + table por estructura
                  $htm_lis []= [ 
                    'ite'=>$rel_nom, 'htm'=>"
                    <div class='var mar_izq-2 dis-ocu'>
                      "._doc_dat::cue('est',"{$esq}.{$est}.{$atr}",$ope)."
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
        $ide = !empty($atr) ? _dat::atr_est($esq,$est,$atr) : $est;
        $_ = "
        <!-- filtros -->
        <form class='val'>

          "._doc::var('val','ver',[ 
            'nom'=>"Filtrar", 
            'id'=>"{$_ide}-ver {$esq}-{$ide}",
            'htm'=>_doc_ope::ver('tex', [ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
          ])."
        </form>
  
        <!-- valores -->
        <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
          <tbody>";
          foreach( _dat::var($esq,$ide) as $ide => $_var ){
          
            $ide = isset($_var->ide) ? $_var->ide : $ide;
  
            if( !empty($atr) ){
              $ima = !empty( $_ima = _dat::val_ver('ima',$esq,$est,$atr) ) ? _doc::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
            }
            else{
              $ima = _doc::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
            }$_ .= "
            <tr data-ide='{$ide}'>
              <td data-atr='ima'>{$ima}</td>
              <td data-atr='ide'>"._doc::let($ide)."</td>
              <td data-atr='nom'>"._doc::let(isset($_var->nom) ? $_var->nom : '')."</td>
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

    // ficha : valor[ima] => { ...imagenes por atributos } 
    static function fic( string $ide, mixed $val, ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."fic";
      $_eje = self::$EJE."fic";      
      // proceso estructura
      extract( _dat::ide($ide) );

      $_fic = _dat::est_ope($esq,$est,'fic');

      if( isset($_fic->ide) ){
        
        if( !empty($val) ) $val = _dat::var($esq,$est,$val);
      
        $ima = _dat::val_ver('ima',$esq,$est,$atr = $_fic->ide);
        
        $_ .= "
        <div class='val' data-ima='{$ima['ide']}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>";

          if( !empty($val) ) $_ .= _doc::ima($esq,$est,$val); $_ .= "

        </div>

        <c class='sep'>=></c>

        "._doc_dat::atr_ima($esq,$est,isset($_fic->atr) ? $_fic->atr : []);
      }     

      return $_;
    }

    // devuelvo listado por estructura
    static function lis( string | array $dat, string $ide, string $tip, array $ele = [] ) : string {

      $_ = $dat;

      if( is_array($dat) ){

        $ele['lis']['est'] = $ide;

        _ele::cla($ele['lis'], "anc_max-fit mar-aut mar_ver-3");        
        
        $_ = _doc_lis::$tip($dat, $ele);
      }

      return $_;
    }

    // devuelvo Tabla por Estructura
    static function est( string | array $dat, array $ope, array $ele = [], ...$opc ) : string {

      if( empty($opc) ) $opc = ['htm','cab_ocu'];

      if( !isset($ele['tab']) ){ $ele['tab'] = []; }

      if( !isset($ele['tab']['class']) ) _ele::cla($ele['tab'],"anc-100 mar-aut mar_aba-2");

      return _doc_est::lis($dat, $ope, $ele, ...$opc);
    }
  
    // listado de : ...atributo: valor
    static function atr( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";
      $_ = "";

      $_ide = $_ide;
      if( is_string($dat) ){
        $var_dat = _dat::ide($dat,$var_dat);        
        $esq = $var_dat['esq'];
        $est = $var_dat['est'];                
        $_ide = "{$_ide} _$esq.$est";
        // datos
        $dat = _dat::var($dat,$ope);
      }

      // estructura
      $ide = _dat::ide($ope['est']);
      unset($ope['est']);
      
      // atributos
      $dat_atr = _dat::atr($ide['esq'],$ide['est']);
      if( isset($ope['atr']) ){
        $lis_atr = $ope['atr'];
        unset($ope['atr']);
      }
      else{
        $lis_atr = array_keys($dat_atr);
      }

      // dato: objeto
      if( !is_object($dat) ){
        $dat = _dat::var($ide['esq'],$ide['est'],$dat);
      }
      $ite = [];
      foreach( $lis_atr as $atr ){
        if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ $ite []= "
          <b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> "._doc::let($dat->$atr);
        }
      }

      $_ = _doc_lis::ite($ite,$ope);          

      return $_;
    }// propiedades con <dl> : imagen + nombre > ...atributos
    static function atr_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
      
      $_ = [];
      // tipos de lista
      $tip = !empty($ele['tip']) ? $ele['tip'] : 'val';
      // atributos de la estructura
      $atr = _lis::ite($atr);
      // descripciones : cadena con " ()($)atr() "
      $des = !empty($ele['des']) ? $ele['des'] : FALSE;
      // elemento de lista
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      _ele::cla($ele['lis'],"ite",'ini');
      $ele['lis']['data-ide'] = "$esq.$est";

      if( class_exists($_cla = "_$esq") ){

        foreach( $_cla::_($est) as $pos => $_dat ){ 
          $htm = 
          _doc::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
          <dl>
            <dt>
              ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " "._obj::val($_dat,$des) : "" )."
            </dt>";
            foreach( $atr as $ide ){ 
              if( isset($_dat->$ide) ){ $htm .= "
                <dd>".( preg_match("/_des/",$ide) ? "<q>"._doc::let($_dat->$ide)."</q>" : _doc::let($_dat->$ide) )."</dd>";
              }
            }$htm .= "
          </dl>";
          $_ []= $htm;
        }
      }

      return _doc_lis::$tip( $_, $ele, ...$opc );
    }// imagenes : { ...[atr:ima] ; ...[atr:ima] }
    static function atr_ima( string $esq, string $est, array $atr, mixed $val, array $ope = [] ) : string {
      $_dat = _dat::var($esq,$est,$val);
      // busco atributos en : _dat.est.ope
      if( empty($atr) ){
        $atr = _dat::est_ope($esq,$est,'atr_ima');
      }
      $_lis = [];
      $_ = "
      <ul class='val'>  
        <li><c class='sep'>{</c></li>";        
        foreach( $atr as $atr ){
          // $_ima = _dat::val_ver('ima',$esq,$est,$atr);
          if( isset($_dat->$atr) ) $_lis []= _doc::ima($esq,"{$est}_{$atr}",$_dat->$atr,[ 
            'eti'=>"li", 'class'=>"tam-4 mar_hor-1", 'data-esq'=>$esq, 'data-est'=>$est, 'data-atr'=>$atr
            //, 'data-ima'=>$_ima['ide']
          ]);
        } $_ .= 
        implode("<c class='sep'>;</c>",$_lis)."
        <li><c class='sep'>}</c></li>
      </ul>";
      return $_;
    }

  }

  // valor : abm + acumulados, sumatorias, filtros, cuentas
  class _doc_val {

    static string $IDE = "_doc_val-";
    static string $EJE = "_doc_val.";

    static array $OPE = [
      'acu'=>['nom'=>"Acumulados" ], 
      'ver'=>['nom'=>"Selección"  ], 
      'sum'=>['nom'=>"Sumatorias" ], 
      'cue'=>['nom'=>"Conteos"    ]
    ];
    static array $BOT = [
      'ver'=>['nom'=>"Ver"        ], 
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ]
    ];

    // abm de la base
    static function abm( string $tip, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";
      $_ope = self::$BOT;
      switch( $tip ){
      case 'nav':
        $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
        if( !empty($url) ){
          $url_agr = "{$url}/0";
          $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
        }
        $_ .= "
        <fieldset class='ope' abm='{$tip}'>    
          "._doc::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']->nom, 'onclick'=>"{$_eje}('ver');"])."
  
          "._doc::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']->nom, 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."
  
          "._doc::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']->nom, 'onclick'=>"{$_eje}('eli');"])."
        </fieldset>";
        break;
      case 'abm':
        $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
        $_ = "
        <fieldset class='ope mar-2 esp-ara'>

          "._doc::ico('dat_val', [ 'eti'=>"button", 'title'=>$_ope[$tip]->nom, 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

          if( in_array('eli',$ope['opc']) ){

            $_ .= _doc::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']->nom, 'onclick'=>"{$_eje}('eli');" ]);
          }$_ .= "

          "._doc::ico('dat_act', [ 'eti'=>"button", 'title'=>$_ope['fin']->nom, 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

        </fieldset>";
        break;              
      case 'est':
        $_ .= "
        <fieldset class='ope'>    
          "._doc::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
          
          "._doc::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
        </fieldset>";                  
        break;                
      }

      return $_;
    }

    // filtros : dato + atributo + posicion + texto
    static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ver";
      $_eje = self::$EJE."ver";

      $_ite = function( $ide, $dat=[], $ele=[] ){

        if( !empty($ele['ope']['id']) ) $ele['ope']['id'] .= "-{$ide}"; 

        // impido tipos ( para fechas )
        if( ( $ide == 'inc' || $ide == 'cue' ) && isset($ele['ope']['_tip']) ) unset($ele['ope']['_tip']);
        
        // combino elementos
        if( !empty($dat[$ide]) && is_array($dat[$ide]) ) $ele['ope'] = _ele::jun($ele['ope'],$dat[$ide]);

        return $ele;
      };
      switch( $tip ){
      // datos : estructura => valores 
      case 'dat':
        // selector de estructura.relaciones para filtros
        array_push($opc,'est','val');
        $_ .= _doc::var('doc',"val.ver.dat",[ 
          'ite'=>[ 'class'=>"tam-mov" ], 
          'htm'=>_doc_dat::opc('ver',$dat,$ele,...$opc)
        ]);
        break;
      // atributo : nombre : valor por tipo ( bool, numero, texto, fecha, lista, archivo, figura )
      case 'atr':
        break;
      // listados : desde + hasta
      case 'lis': 
        // por defecto
        if( empty($dat) ) $dat = [ 'ini'=>[], 'fin'=>[] ];

        // desde - hasta
        foreach( ['ini','fin'] as $ide ){

          if( isset($dat[$ide]) ) $_ .= _doc::var('doc', "val.ver.$ide", $_ite($ide,$dat,$ele));
        }
        $_ .= _doc_val::ver('lim',$dat,$ele);
        break;
      // limites : incremento + cuantos ? del inicio | del final
      case 'lim':
        // cada
        if( isset($dat['inc']) ){
          $_ .= _doc::var('doc', "val.ver.inc", $_ite('inc',$dat,$ele));
        }
        // cuántos
        if( isset($dat['cue']) ){
          $_eje = "_doc.var('mar', this, 'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
          $ele['htm_fin'] = "
          <fieldset class='ope'>
            "._doc::ico('nav_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            "._doc::ico('nav_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>"; 
          $_ .=
          _doc::var('doc', "val.ver.cue", $_ite('cue',$dat,$ele) );
        }
        break;
      }

      return $_;
    }

    // acumulado : posicion + marcas + seleccion
    static function acu( array $dat, array $ope = [], array $opc = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."acu";
      $_eje = self::$EJE."acu";

      if( empty($opc) ) $opc = array_keys($dat);

      $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

      if( !empty($ope['ide']) ) $_ide = $ope['ide'];

      foreach( $opc as $ide ){        
        $_ .= _doc::var('doc',"val.acu.$ide", [
          'ope'=> [ 
            'id'=>"{$_ide}-{$ide}", 'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 'onchange'=>$_eje_val
          ],
          'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
        ]);
      }
      if( !empty($ope['htm_fin']) ){
        $_ .= $ope['htm_fin'];
      }
      return $_;
    }

    // sumatorias
    static function sum(  string | array $dat, array $ope = [] ) : string {

      $_ = "";
      $_ide = self::$IDE."sum";
      $_eje = self::$EJE."sum";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      if( isset($ope['ide']) ) $_ide = $ope['ide'];
      // operadores por esquemas
      foreach( _app::var($esq,'val','sum') as $ide => $ite ){
    
        $_ .= _doc::var($esq, ['val','sum',$ide], [          
          'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
          'htm_fin'=> !empty($ite['var_fic']) ? _doc_dat::fic($ite['var_fic']) : ''
        ]);
      }    

      return $_;
    }
  }

  // tablero : opciones + posiciones + secciones
  class _doc_tab {

    static string $IDE = "_doc_tab-";
    static string $EJE = "_doc_tab.";

    static array $OPE = [
      'val' => ['nom'=>"Valores"  ],
      'opc' => ['nom'=>"Opciones" ],
      'pos' => ['nom'=>"Posición" ],
      'ver' => ['nom'=>"Selección"]
    ];

    // operadores : valores + seleccion + posicion + opciones( posicion | secciones )
    static function ope( string $tip, string $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;

      // proceso datos del tablero
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";

      // por aplicacion : posicion + seleccion
      if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];

      switch( $tip ){
      // todos
      case 'tod':
        $ele['sec']['class'] = "mar_izq-2";
        foreach( $_ope as $ide => $_dat ){
          $_lis []= [
            'ite'=>[ 'eti'=>"h3", 'class'=>"mar-0", 'htm'=>$_dat['nom'] ],
            'htm'=>_doc_tab::ope($ide,$dat,$ope,$ele,...$opc)
          ];
        }
        $_ = "
        <h2>Operadores</h2>

        "._doc_lis::val($_lis,[ 'opc'=>['tog'], 'ope'=>[ 'class'=>"mar_hor-1" ] ]);

        break;
      // valores : totales + acumulados + sumatorias
      case 'val':
        $ele['sec']['ide'] = $tip; $_ .= "
        <section"._htm::atr($ele['sec']).">

          <form ide='acu'>
            <fieldset class='inf ren'>
              <legend>Acumulados</legend>";

              $_ .= _doc::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ]);
              
              $_ .= _doc_val::acu($ope[$tip],[ 
                'ide'=>$_ide, 
                'eje'=>"{$_eje}_acu(this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
              ]);
              $_ .="
            </fieldset>
          </form>

          <form ide='sum'>

            <fieldset class='inf ren' data-esq='hol' data-est='kin'>
              <legend>Sumatorias del Kin</legend>

              "._doc_val::sum('hol',[ 'ide'=>$_ide ])."

            </fieldset>
          </form>

        </section>";
        break;
      // Filtros : estructuras/db + posiciones + fechas
      case 'ver':
        $ele['sec']['ide'] = $tip; $_ .= "
        <section"._htm::atr($ele['sec']).">

          <form ide='dat'>
            <fieldset class='inf ren'>
              <legend>por Datos</legend>

              "._doc_val::ver('dat', $ope['est'], [ 'ope'=>[ 'onchange'=>"$_eje('dat',this);" ] ], 'ope_tam')."

            </fieldset>
          </form>

          <form ide='pos'>
            <fieldset class='inf ren'>
              <legend>por Posiciones</legend>

              "._doc_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [
                'ope'=>[ '_tip'=>"num_int", 'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 'onchange'=>"$_eje('pos',this);" ] 
              ])."
            </fieldset>
          </form>

          <form ide='fec'>
            <fieldset class='inf ren'>
              <legend>por Fechas</legend>

              "._doc_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [ 
                'ope'=>[ '_tip'=>"fec_dia", 'id'=>"{$_ide}-fec", 'onchange'=>"$_eje('fec',this);" ] 
              ])."
            </fieldset>
            
          </form>

        </section>";          
        break;
      // Opciones : sección + posición
      case 'opc':
        if( !empty($ope['sec']) || !empty($ope['pos']) ){
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">";
            // secciones
            $tip = 'sec';
            $ele_ide = "{$_ide}-{$tip}";
            $ele_eve = "{$_eje}_{$tip}(this);";
            if( !empty($ope[$tip]) ){
              $ele['ope']['ide'] = $tip; $_ .= "
              <form"._htm::atr($ele['ope']).">
                <fieldset class='inf ren'>
                  <legend>Secciones</legend>";
                  // operadores globales
                  if( !empty($tab_sec = _app::var('doc','tab',$tip)) ){ $_ .= "
                    <div class='val'>";
                    foreach( _app::var('doc','tab',$tip) as $ide => $ite ){
                      if( isset($ope[$tip][$ide]) ){ 
                        $_ .= _doc::var('doc', "tab.$tip.$ide", [ 
                          'val'=>$ope[$tip][$ide], 
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ] 
                        ]); 
                      }
                    }$_ .= "
                    </div>";
                  }
                  // operadores por aplicación
                  $_ .= _doc_tab::opc($tip,$dat,$ope[$tip])."

                </fieldset>
              </form>";          
            }
            // posiciones
            $tip = 'pos';
            $ele_ide = "{$_ide}-{$tip}";
            $ele_eve = "{$_eje}_{$tip}(this);";
            if( !empty($ope[$tip]) ){ 
              $ele['ope']['ide'] = $tip; $_ .= "
              <form"._htm::atr($ele['ope']).">    
                <fieldset class='inf ren'>
                  <legend>Posiciones</legend>";
                  // bordes            
                  $ide = 'bor';
                  $_ .= _doc::var('doc', "tab.$tip.$ide",[
                    'val'=>isset($ope[$tip][$ide]) ? $ope[$tip][$ide] : 0,
                    'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
                  ]);                
                  // color de fondo - numero - texto - fecha
                  foreach( ['col','num','tex','fec'] as $ide ){                  
                    if( isset($ope[$tip][$ide]) ){
                      $_ .= _doc::var('doc', "tab.{$tip}.{$ide}", [
                        'id'=>"{$ele_ide}-{$ide}",
                        'htm'=>_doc_dat::opc($ide, $ope['est'], [
                          'val'=>$ope[$tip][$ide], 
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                        ])
                      ]);                      
                    }
                  }
                  // imagen de fondo - ( ficha )
                  if( isset($ope[$tip][$ide = 'ima']) ){ $_ .= "
                    <div class='ren'>";
                      if( isset($ope[$tip][$ide]) ){
                        $_ .= _doc::var('doc',"tab.{$tip}.{$ide}",[
                          'id'=>"{$ele_ide}-{$ide}",
                          'htm'=>_doc_dat::opc($ide, $ope['est'], [ 
                            'val'=>$ope[$tip][$ide], 
                            'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                          ])
                        ]);
                      }
                      if( isset($ope['val']) ){ $_ .= "
                        <div class='val' ide='acu'>";
                          foreach( array_keys($ope['val']) as $ite ){        
                            $_ .= _doc::var('doc', "tab.$tip.{$ide}_{$ite}", [
                              'val'=>isset($ope[$tip]["{$ide}_{$ite}"]) ? $ope[$tip]["{$ide}_{$ite}"] : FALSE,
                              'ope'=>[ 'id'=>"{$ele_ide}-{$ide}_{$ite}", 'onchange'=>$ele_eve ]
                            ]);
                          }$_.="
                        </div>";
                        }
                      $_ .= "
                    </div>";
                  }    
                  // operadores por aplicaciones                  
                  $_ .= _doc_tab::opc($tip,$dat,$ope[$tip])."
                </fieldset>    
              </form>";          
            }$_ .= "
          </section>";
        }
        break;
      // Posicion principal : atributos por aplicación
      case 'pos':
        if( !empty($ope[$tip]) ){
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">

            "._doc_tab::pos($dat,$ope,$ele,...$opc)."

          </section>";
        }
        break;

      }  

      return $_;
    }

    // por opciones : seccion + posicion
    static function opc( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."opc";
      // proceso estructura de la base
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";        
      $_eje = "_{$esq}_app_tab.opc";

      // solo muestro las declaradas en el operador
      $atr = array_keys($ope);

      foreach( _app::var($esq,'tab',$tip) as $ide => $_dat ){

        if( in_array($ide,$atr) ){

          $_ .= _doc::var($esq, "tab.$tip.$ide", [
            'ope'=>[
              'id'=>"{$_ide}-{$ide}", 
              'val'=>!empty($ope[$ide]) ? !empty($ope[$ide]) : NULL, 
              'onchange'=>"$_eje('$tip',this);"
            ]
          ]);
        }
      }

      return $_;
    }

    // por posicion : valor principal
    static function pos( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."pos";      
      // proceso estructura
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";
      $_eje = "_{$esq}_app_tab.pos";

      foreach( $opc as $atr ){
        $htm = "";
        foreach( _app::var($esq,'pos',$atr) as $ide => $val ){
          $var = [
            'ope'=>[ 
              'id'=>"{$_ide}-{$atr}-{$ide}", 
              'dat'=>$atr, 
              'onchange'=>"$_eje('$atr',this)" ]
          ];
          if( isset($ope[$atr][$ide]) ){
            $var['var']['val'] = $ope[$atr][$ide];
          }
          $htm .= _doc::var($esq, "pos.$atr.$ide", $var);
        }        
        // busco datos del operador 
        if( !empty($htm) && !empty($_ope = _app::var($esq,'val','pos',$atr)) ){
          $ele['ope']['pos'] = $atr; $_ .= "
          <form"._htm::atr($ele['ope']).">
            <fieldset class='inf ren'>
              <legend>{$_ope['nom']}</legend>
                {$htm}
            </fieldset>
          </form>";          
        }
      }

      return $_;
    }
    
  }  
  
  // Estructura : atributos + valores + filtros + columnas + titulo + detalle
  class _doc_est {

    static string $IDE = "_doc_est-";
    static string $EJE = "_doc_est.";    

    static array $OPE = [
      'val' => [ 'nom'=>"Valores"       , 'des'=>"", 'htm'=>"" ], 
      'ver' => [ 'nom'=>"Filtros"       , 'des'=>"", 'htm'=>"" ], 
      'col' => [ 'nom'=>"Columnas"      , 'des'=>"", 'htm'=>"" ], 
      'des' => [ 'nom'=>"Descripciones" , 'des'=>"", 'htm'=>"" ],      
      'cue' => [ 'nom'=>"Cuentas"       , 'des'=>"", 'htm'=>"" ]
    ];
    static array $VAL = [
      'atr'=>[],// columnas del listado
      'atr_tot'=>0,// columnas totales
      'atr_ocu'=>[],// columnas ocultas
      'atr_dat'=>[],// datos de las columnas
      'est'=>[],// estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est_dat'=>[],// datos y operadores por estructura
      'dat'=>[],// filas: valores por estructura [...{...$}]
      'val'=>[],// dato por columna: valor por objeto {...$}
      'tit'=>[],// titulos: por base {'cic','gru','des'} o por operador [$pos]
      'tit_cic'=>[],// titulos por ciclos
      'tit_gru'=>[],// titulos por agrupaciones
      'det'=>[],// detalles: por base {'cic','gru','des'} o por operador [$pos]
      'det_cic'=>[],// detalle por ciclos
      'det_gru'=>[],// detalle por agrupaciones
      'det_des'=>[]// detalle por descripciones
    ];
    static array $OPC = [ 
      'cab_ocu',  // ocultar titulo de columnas
      'ima',      // buscar imagen para el dato
      'var',      // mostrar variable en el dato
      'htm'       // convertir texto html en el dato
    ];
    static array $ATR = [
      'opc'=>[ "Opción", 0 ], 
      'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
      'tex'=>[ "Texto",  0 ], 
      'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
      'obj'=>[ "Objeto",  0 ] 
    ];

    // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
    static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      else{        
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::var($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];      
      // todos
      switch( $tip ){      
      case 'tod':
        $ope_ver = '';
        $ope['var']['var'] = "est";
        // cargo operadores
        $_ope = _obj::nom($_ope,'ver',$ope_lis = ['ver','col','des','cue']);
        foreach( $ope_lis as $ide ){
          $_ope[$ide]['htm'] = _doc_est::ope($ide,$dat,$ope,$ele);
        }
        $_ = "
  
        "._doc_est::ope('val',$dat,$ope,$ele)."
  
        "._doc::nav('pes', $_ope, [ 
          'nav'=>['class' => "mar_arr-2" ]
        ],'tog')."        

        <div"._htm::atr($ope['var']).">

          "._doc_est::lis($dat,$ope,$ele,...$opc)."

        </div>
        ";          
        break;
      // atributos y tipos de dato con filtros
      case 'atr':
        foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
        // tipos de dato
        $_cue = self::$ATR;
        // cuento atributos por tipo
        foreach( $_est->atr as $atr ){
          $tip_dat = explode('_', _dat::atr($esq,$est,$atr)->ope['_tip'])[0];
          if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
        }
        // operador : toggles + filtros
        $_ .= "
        <form class='val jus-ini' dat='atr'>
  
          <fieldset class='ope'>
            "._doc::ico('ope_ocu',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
            "._doc::ico('ope_ver',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
          </fieldset>
  
          "._doc::var('val','ver',[ 'nom'=>"Filtrar", 'htm'=>_doc_ope::ver('tex',[ 'eje'=>"{$_eje}_ver(this);" ]) ])."
  
          <fieldset class='ite'>";
          foreach( $_cue as $atr => $val ){ $_ .= "
            <div class='val'>
              "._doc::ico($atr,[
                'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
              ])."
              <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
            </div>";
          }$_ .= "
          </fieldset>
  
        </form>";
        // listado
        $pos = 0; $_ .= "
        <table"._htm::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
        foreach( $_est->atr as $atr ){
          $pos++;
          $_atr = _dat::atr($esq,$est,$atr);
          $_var = [ 'id'=>"{$_ide}-{$atr}", 'onchange'=>"{$_eje}_val(this,'dat');" ];

          $var_tip = explode('_',$_atr->ope['_tip'])[0];
          if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
          if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
          if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
          if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
          $htm = "
          <form class='ren esp-bet'>
          
            "._doc_val::ver('lis', isset($_cue[$var_tip][2]) ? $_cue[$var_tip][2] : [], [ 'ope'=>$_var ] )."
  
          </form>";
          $_ .= "
          <tr data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}' pos='{$pos}'>
            <td data-atr='val'>
              "._doc::ico( isset($app_lis->ocu) && in_array($atr,$app_lis->ocu) ? "ope_ver" : "ope_ocu",[
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
      // valores : cantidad + acumulado + operaciones
      case 'val': 
        $_ = "
        <h3 class='dis-ocu'>Valores</h3>";
        // acumulados
        if( isset($ope['val']) ){
          $_ .= "
          <form ide='acu'>

            <fieldset class='inf ren'>
              <legend>Valores</legend>
              
              "._doc::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ])."
              
              "._doc::var('doc', "val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
              
              "._doc_val::acu($ope['val'],[
                'ide'=>$_ide, 
                'eje'=>"{$_eje}_acu(this); ".self::$EJE."ver('val',this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c> <n>0</n> <c class='sep'>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }// por datos de la base ?
        else{
        }
        break;
      // filtros : datos + posicion + atributos
      case 'ver':
        $dat_tot = count($ope['dat']);
        $_ = "
        <h3 class='dis-ocu'>Filtros</h3>

        <form ide='dat'>
          <fieldset class='inf ren'>
            <legend>por Datos</legend>
            
            "._doc_val::ver('dat', $ope['est'], [ 'ope'=>[ 'max'=>$dat_tot, 'onchange'=>"$_eje('dat',this);" ] ])."

          </fieldset>
        </form>

        <form ide='pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>
            "._doc_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [                  
              'ope'=>[ '_tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'id'=>"{$_ide}-pos", 'onchange'=>"$_eje('pos',this);" ]
            ])."
          </fieldset>
        </form>";
        break;
      // columnas : ver/ocultar
      case 'col':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
              
          foreach( $est_lis as $est ){
            // estrutura por aplicacion
            $_est = _app::est($esq,$est);
            // datos de la estructura
            $est_nom = _dat::est($esq,$est)->nom;
            // contenido : listado de checkbox en formulario
            $htm = "
            <form ide='{$tip}' class='ren jus-ini mar_izq-2'>";
              foreach( $_est->atr as $atr ){
                $htm .= _doc::var('val',$atr,[
                  'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                  'val'=>!isset($_est->atr_ocu) || !in_array($atr,$_est->atr_ocu),
                  'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 'onchange'=>"{$_eje}_tog(this);"
                  ] 
                ]);
              } $htm.="
            </form>";
            
            $lis_val []= [
              'ite'=>$est_nom,
              'htm'=>$htm
            ];
          }              
        }        
        $_ = "        
        <h3 class='dis-ocu'>Columnas</h3>

        "._doc_lis::val($lis_val,[
          'htm_fin'=>[ 'eti'=>"ul", 'class'=>"ope mar_izq-1", 'htm'=>"
            "._doc::ico('ope_ver',['eti'=>"li", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2", 
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
            "._doc::ico('ope_ocu',['eti'=>"li", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2", 
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])
          ],
          'opc'=>['tog']
        ]);
        break;
      // descripciones : titulo + detalle
      case 'des':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
            
          foreach( $est_lis as $est ){
            
            $_est =  _app::est($esq,$est,$ope);

            // ciclos, agrupaciones y lecturas
            if( !empty($_est->tit_cic) || !empty($_est->tit_gru) || !empty($_est->det_des) ){              

              $lis_dep = [];
              foreach( ['cic','gru','des'] as $ide ){

                $pre = $ide == 'des' ? 'det' : 'tit';
                
                if( !empty($_est->{"{$pre}_{$ide}"}) ){
                  $htm = "
                  <form ide='{$ide}' data-esq='{$esq}' data-est='{$est}' class='ren jus-ini mar_izq-2'>";
                  foreach( $_est->{"{$pre}_{$ide}"} as $atr ){
                    $htm .= _doc::var('val',$atr,[ 
                      'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                      'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                    ]);
                  }$htm .= "
                  </form>";
                  
                  $lis_dep[] = [ 
                    'ite'=>_app::var('doc','est','ver',$ide)['nom'], 
                    'htm'=>$htm
                  ];
                }
              }
              $lis_val[] = [
                'ite'=>_dat::est($esq,$est)->nom,
                'lis'=>$lis_dep
              ];
            }
          }
        }
        $_ = "
        <h3 class='dis-ocu'>Descripciones</h3>

        "._doc_lis::val($lis_val,[ 'opc'=>['tog'] ]);

        break;
      // cuentas : total + porcentaje
      case 'cue':
        $_ = "
        <h3 class='dis-ocu'>Cuentas</h3>

        "._doc_lis::val( _doc_dat::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[ 'class'=>"dis-ocu" ], 'opc'=>['tog', 'ver', 'cue'] ] );

        break;
      }

      return $_;
    }

    // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
    static function lis( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      foreach( [ 'lis','cue','tit_ite','tit_val','dat_ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
      $_ = "";
      $_ide = self::$IDE."lis";
      $_eje = self::$EJE."lis";
      
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::var($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];            
      
      // identificadores de la base        
      if( isset($esq) ){
        $ele['lis']['data-esq'] = $esq;
        $ele['lis']['data-est'] = $est;
      }
      $_ = "
      <table"._htm::atr($ele['lis']).">";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';

        // columnas:
        if( $dat_val_lis = is_array($dat) ){
          // datos de atributos
          if( !isset($ope['atr_dat']) ){
            $ope['atr_dat'] = _est::atr($dat);
          }
          // listado de columnas
          if( !isset($ope['atr']) ){
            $ope['atr'] = array_keys($ope['atr_dat']);
          }
        }
        // caclulo total de columnas
        $ope['atr_tot'] = _dat::est_atr($dat,$ope);

        // cabecera
        if( !in_array('cab_ocu',$opc) ){ 
          foreach( [ 'cab','cab_ite','cab_val' ] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
          $_ .= "
          <thead"._htm::atr($ele['cab']).">
            "._doc_est::col($dat,$ope,$ele,...$opc)."
          </thead>";
        }
        // cuerpo
        $_.="
        <tbody"._htm::atr($ele['cue']).">";          
          // recorro: por listado $dat = []
          if( $dat_val_lis ){

            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              if( !empty($ope['tit'][$pos]) ) $_ .= _doc_est::pos('tit',$pos,$ope,$ele);

              // fila-columnas
              $ope['val'] = $_dat; $_.="
              <tr"._htm::atr($ele['dat_ite']).">
                "._doc_est::ite($dat,$ope,$ele,...$opc)."
              </tr>";

              // detalles
              if( !empty($ope['det'][$pos]) ) $_ .= _doc_est::pos('det',$pos,$ope,$ele);                    
            }
          }
          // estructuras de la base esquema
          else{

            // valido item por objeto-array
            foreach( $ope['dat'] as $_dat ){ $_val_dat_obj = is_object($_dat); break; }
            
            // valido contenido : titulos y detalles por estructura de la base
            $ele_ite = [];
            foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){ 
              $_val[$i] = isset($ope[$i]);
              foreach( $v as $e ){

                if( !is_numeric($e) && ( $_val["{$i}_{$e}"] = isset($ope["{$i}_{$e}"]) ) || ( $_val[$i] && in_array($e,$ope[$i]) ) ){

                  $ele_ite["{$i}_{$e}"] = [ 'ite'=>[ 'data-opc'=>$i, 'data-ope'=>$e ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
                }
              }            
            }

            // contenido previo : titulos por agrupaciones
            if( isset($ele_ite['tit_gru']) ){

              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  $_ .= _doc_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru'], ...$opc);
                }
              }
            }

            // recorro datos
            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              foreach( $ope['est'] as $esq => $est_lis ){
                // recorro referencias
                foreach( $est_lis as $est){
                  // cargo relaciones                  
                  if( $dat_opc_est = _dat::est_ope($esq,$est,'est') ){

                    foreach( $dat_opc_est as $atr => $ref ){

                      $ele['ite']["{$esq}-{$ref}"] = $_val_dat_obj ? $_dat->$atr : $_dat["{$esq}-{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos                
                  if( $_val['tit'] || $_val['tit_cic'] ){

                    $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                    $_ .= _doc_est::tit('cic', "{$esq}.{$est}", $ope, $ele_ite['tit_cic'], ...$opc);
                  }
                }
              }
              // cargo item por esquema.estructuras
              $ele['ite']['pos'] = $pos; $_ .= "
              <tr"._htm::atr($ele['ite']).">";
              foreach( $ope['est'] as $esq => $est_lis ){
      
                foreach( $est_lis as $est ){
                  
                  $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                  $_ .= _doc_est::ite("{$esq}.{$est}", $ope, $ele, ...$opc);
                } 
              }$_ .= "
              </tr>";
              $opc []= "det_cit";
              // cargo detalles
              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  foreach( ['des','cic','gru'] as $ide ){

                    if( isset($ele_ite["det_{$ide}"]) ){

                      $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                      $_ .= _doc_est::det($ide, "{$esq}.{$est}", $ope, $ele_ite["det_{$ide}"], ...$opc );
                    }
                  }                  
                } 
              }                    
            }
          }$_ .= "              
        </tbody>";
        // pie
        if( !empty($ope['pie']) ){
          foreach( ['pie','pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
          $_.="
          <tfoot"._htm::atr($ele['pie']).">";
            // fila de operaciones
            $_.="
            <tr"._htm::atr($ele['pie_ite']).">";
              foreach( $_atr as $atr ){ $_.="
                <td data-atr='{$atr->ide}' data-ope='pie'></td>";
              }$_.="
            </tr>";
            $_.="
          </tfoot>";
        }
        $_.="
      </table>";
      return $_;
    }

    // columnas : por atributos
    static function col( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";

      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est);
      }
      // por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      
      // por muchos      
      if( isset($ope['est']) ){

        $ope_est = $ope['est'];
        unset($ope['est']);

        foreach( $ope_est as $esq => $est_lis ){

          foreach( $est_lis as $est ){

            $_ .= _doc_est::col("{$esq}.{$est}",$ope,$ele,...$opc);
          }
        }
      }
      // por 1: esquema.estructura
      else{
        $_val['dat'] = isset($esq);

        $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
        // cargo datos
        $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val['dat'] ? _dat::atr($esq,$est) : _est::atr($dat) );
        // ocultos por estructura
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : [];
        // genero columnas
        foreach( ( !empty($ope['atr']) ? $ope['atr'] : ( !empty($_est->atr) ? $_est->atr : array_keys($ope['atr_dat']) ) ) as $atr ){
          $e = [];
          if( $_val['dat'] ){
            $e['data-esq'] = $esq;
            $e['data-est'] = $est;
          } 
          $e['data-atr'] = $atr;
          if( in_array($atr,$atr_ocu) ) _ele::cla($e,"dis-ocu");
          // poner enlaces
          $htm = _doc::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
          if( !$ope_nav ){
            $htm = "<p>{$htm}</p>";
          }else{
            $htm = "<a href='' target='_blank'>{$htm}</a>";
          }$_ .= "
          <th"._htm::atr($e).">
            {$htm}
          </th>";
        }         
      }   

      return $_;
    }

    // posicion : titulo + detalle
    static function pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {              
      $_ = "";
      if( isset($ope[$tip][$ide]) ){

        foreach( _lis::ite($ope[$tip][$ide]) as $val ){ 
          $_.="
          <tr"._htm::atr($ele["{$tip}_ite"]).">
            <td"._htm::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
              ".( is_array($val) ? _htm::val($val) : "<p class='{$tip} tex_ali-izq'>"._doc::let($val)."</p>" )."
            </td>
          </tr>";
        }        
      }
      return $_;
    }

    // titulo : posicion + ciclos + agrupaciones
    static function tit( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."tit";
      $_eje = self::$EJE."tit";
      // proceso estructura de la base
      if( is_string($dat) ){

        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est);
      }
      // por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
        $_ide .= " _$esq.$est";
      }

      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }
      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }

      // 1 titulo : nombre + detalle
      if( $tip == 'pos' ){
        $ele['ite']['data-atr'] = $ope[0];
        $ele['ite']['data-ide'] = is_object($ope[2]) ? $ope[2]->ide : $ope[2];
        $htm = "";
        if( !empty($htm_val = _doc_dat::val('nom',"{$esq}.{$ope[1]}",$ope[2])) ){ $htm .= "
          <p class='tit'>"._doc::let($htm_val)."</p>";
        }
        if( !empty($htm_val = _doc_dat::val('des',"{$esq}.{$ope[1]}",$ope[2])) ){ $htm .= "
          <q class='mar_arr-1'>"._doc::let($htm_val)."</q>";
        }
        $_ .="
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">{$htm}</td>
        </tr>";
      }
      // por ciclos : secuencias
      elseif( $tip == 'cic' ){
        
        // acumulo posicion actual, si cambia -> imprimo ciclo        
        if( isset($_est->cic_val) ){
          
          $val = _dat::var($esq,$est,$ope['val']);            
  
          foreach( $_est->cic_val as $atr => &$pos ){

            if( !empty($ide = _dat::atr_est($esq,$est,$atr) ) && $pos != $val->$atr ){
              if( !empty($val->$atr) ){
                $ite_ele = $ele;

                if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');

                $_ .= _doc_est::tit('pos',$dat,[$atr,$ide,$val->$atr],$ite_ele);
              }
              $pos = $val->$atr;
            }
          }
        }        
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($_est->$tip) ){
          foreach( $_est->$tip as $atr ){

            if( !empty($ide = _dat::atr_est($esq,$est,$atr)) ){

              foreach( _dat::var($esq,$ide) as $val ){                
                $ite_ele = $ele;
                if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');
                $_ .= _doc_est::tit('pos',$dat,[$atr,$ide,$val],$ite_ele);
              }
            }
          }
        }
      }
      return $_;
    }

    // item : datos de la estructura
    static function ite( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ite";
      $_eje = self::$EJE."ite";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
      
      $dat = $ope['val'];
      $opc_ima = !in_array('ima',$opc);
      $opc_var = in_array('var',$opc);
      $opc_htm = in_array('htm',$opc);

      // identificadores
      if( $_val['dat'] = isset($esq) ){
        $ele['dat_val']['data-esq'] = $esq;
        $ele['dat_val']['data-est'] = $est;
      }
      // datos de la base
      if( isset($_est) ){

        $_atr    = _dat::atr($esq,$est);
        $est_ima = _dat::est_ope($esq,$est,'ima');  
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : FALSE;
        
        foreach( ( isset($ope['atr']) ? $ope['atr'] : $_est->atr ) as $atr ){
          $ele_dat = $ele['dat_val'];
          $ele_dat['data-atr'] = $atr;         
          //ocultos
          if( $atr_ocu && in_array($atr,$atr_ocu) ) _ele::cla($ele_dat,'dis-ocu');
          // contenido
          $ele_val = $ele['val'];
          
          if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
            _ele::cla($ele_val,"tam-3");
            $ide = 'ima';
          }
          // variables
          else{
            $ide = $opc_var ? 'var' : 'tip';
            // adapto estilos por tipo de valor
            if( !empty($_atr[$atr]) ){
              $var_dat = $_atr[$atr]->var_dat;
              $var_val = $_atr[$atr]->var_val;
            }elseif( !empty( $_var = _val::tip( $dat ) ) ){
              $var_dat = $_var->dat;
              $var_val = $_var->val;
            }else{
              $var_dat = "val";
              $var_val = "nul";
            }
            // - limito texto vertical
            if( $var_dat == 'tex' ){
              if( $var_dat == 'par' ) _ele::css($ele_val,"max-height:4rem; overflow-y:scroll");
            }
          }$_ .= "
          <td"._htm::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? _ele::cla($ele_dat,'dis-ocu') : $ele_dat ).">      
            "._doc_dat::ver($ide,"{$esq}.{$est}.{$atr}",$dat,$ele_val)."
          </td>";
        }
      }
      // por listado del entorno
      else{
        $_atr = $ope['atr_dat'];
        $_val_dat_obj = is_object($dat);
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_dat_obj ? $dat->{$ide} : $dat[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// variable por tipo
          elseif( $opc_var ){
            $htm = "";
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : _htm::val($dat_val);
          }// textos
          else{
            $htm = _doc::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td"._htm::atr($ele['dat_val']).">
            {$htm}
          </td>";
        }
      }      

      return $_;
    }

    // detalles : posicion + descripciones + lecturas
    static function det( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."det";
      $_eje = self::$EJE."det";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }

      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }

      // 1 detalle
      if( $tip == 'pos' ){
        $ele['ite']['data-atr'] = $ope[0];
        $ele['ite']['data-ide'] = isset($ope[1]->ide) ? $ope[1]->ide : ( isset($ope[1]->pos) ? $ope[1]->pos : '' ); $_ .= "
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">
            ".( in_array('det_cit',$opc) ? "<q>"._doc::let($ope[1]->{$ope[0]})."</q>" : _doc::let($ope[1]->{$ope[0]}) )."
          </td>
        </tr>";
      }
      // por tipos : descripciones + ciclos + agrupaciones
      elseif( isset($_est->{"det_$tip"}) ){
        $val = _dat::var($esq,$est,$ope['val']);
        foreach( $_est->{"det_$tip"} as $atr ){
          $ite_ele = $ele;
          if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');
          $_ .= _doc_est::det('pos',$dat,[$atr,$val],$ite_ele,...$opc);
        }
      }

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
          $_ = _doc_opc::val( $dat, $ope, ...$opc);
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
    // selector : <select>
    static function val( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
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
                "._doc_opc::lis( $dat[$ide], $val, $ope_ite, ...$opc )."                
              </optgroup>";
            }
          }
        }
        else{                        
          $_ .= _doc_opc::lis( $dat, $val, $ope_ite, ...$opc );
        }
        $_ .= "
      </{$eti}>";

      return $_;
    }
    // opciones : <option value>
    static function lis( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
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
            $htm_out = "";
            if( !in_array('val-ocu',$opc) ){ $htm_out = "
              <output for='{$ope['id']}'>
                <n class='_val'>{$ope['value']}</n>
              </output>";
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

              {$htm_out}

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
          $_ = _doc_opc::val( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
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