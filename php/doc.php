<?php

  // estilos y clases
  define('DIS_OCU', "dis-ocu" );
  define('FON_SEL', "fon-sel" );
  define('BOR_SEL', "bor-sel" );
  define('ROT_VER', "rotate(0deg)" );
  define('ROT_OCU', "rotate(270deg)" );

  // documento 
  class _doc {  

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

        $_var = _api::var($tip,$esq,$est,$atr);
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
    // operaciones : tog (ver|ocu) , val(mar|des)
    static function ope( string $tip, string | array $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_tip = explode('_',$tip);
      $_ide = "_doc-ope-{$tip}";
      $_eje = "_doc.ope";
      switch( $_tip[0] ){
      // contenido : div.val [ > .ico + htm ] + *        
      case 'tog':
        if( empty($_tip[1]) ){
          foreach( ['val','ico'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
          // contenido textual
          if( is_string($dat) ) $dat = [ [ 'eti'=>"p", 'htm'=>_doc::let($dat) ] ];
          // contenedor : icono + ...elementos          
          _ele::eje( $dat,'cli',"$_eje('{$_tip[0]}',this);");          
          $_ .= "
          <div"._htm::atr( _ele::cla( $ope['val'],"val nav",'ini') ).">
          
            "._doc::ope('tog_ico', isset($ope['ico']) ? $ope['ico'] : NULL )."

            ".( isset($ope['htm_ini']) ? _htm::val($ope['htm_ini']) : '' )."
            
            "._htm::val( $dat )."

            ".( isset($ope['htm_fin']) ? _htm::val($ope['htm_fin']) : '' )."

          </div>";
        }
        else{
          switch( $_tip[1] ){
          case 'ico':// icono con rotacion
            $_ = _doc::ico('ope_tog', [
              'onclick' => "$_eje('{$_tip[0]}',this);".( !empty($dat['eje']) ? $dat['eje'] : '' ),
              'style' => !empty($dat['ocu']) ? "transform: ".ROT_OCU : ""
            ]);
            break;
          case 'ver':// expandir | contraer
            $_eje_val = !empty($dat['eje']) ? $dat['eje'] : "$_eje('{$_tip[0]}',this);";
            $_ .= "
            <fieldset class='ope' tip='win_tog'>";
              $ico = [ 'eti'=>"button", 'class'=>"tam-2", 'value'=>"tod", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val ];
              $_ .= _doc::ico('ope_tod', isset($dat['ico']) ? _ele::jun($ico,$dat['ico']) : $ico );
              $ico = [ 'eti'=>"button", 'class'=>"tam-2", 'value'=>"nad", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val ];
              $_ .= _doc::ico('ope_nad', isset($dat['ico']) ? _ele::jun($ico,$dat['ico']) : $ico );
              $_ .= "
            </fieldset>";
            break;
          }
        }      
        break;        
      // valores : toggles, cheks
      case 'val': break;
      }
      return $_;
    }    

  }
  // articulo
  class _doc_art {

    static string $IDE = "_doc_art-";

    static string $EJE = "_doc_art.";

    // pantalla : #sis > article > header + section
    static function win( string $ide, array $ope = [] ) : string {
      foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
      $_ide = self::$IDE."win";
      $_eje = self::$EJE."win";
      $_ = "";
            
      // identificador
      $ope['art']['ide'] = $ide;
      _ele::cla($ope['art'],'dis-ocu');

      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _doc::ico($ope['ico'],['class'=>"mar_hor-1"]);

      $cab_tit = "";
      if( !empty($ope['nom']) ) $cab_tit = "<h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>";

      $_ = "
      <article"._htm::atr($ope['art']).">

        <header> 
          {$cab_ico} 
          {$cab_tit} 
          "._doc::ico('eje_fin',[ 'title'=>'Cerrar', 'onclick'=>"$_eje();" ])."
        </header>

        <section"._htm::atr($ope['sec']).">

          ".( !empty($ope['htm'] && is_string($ope['htm']) ) ? $ope['htm'] : '' )."

        </section>

      </article>";
      
      return $_;
    }  

    // genero articulo por seccion principal : main > aside + article
    static function sec( string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";

      // contenido directo
      if( is_string($dat) ){
    
        $_ .= $dat;
      }
      // listado de articulos
      else{
        foreach( $dat as $ide => $art ){
          
          if( isset($art['htm'])){ $art['ide'] = $ide; $_ .= "
            <article"._htm::atr($art).">
              {$art['htm']}
            </article>";
          }
        }
      }          

      return $_;
    }

    // genero articulo por menu de cabecera
    static function val( array $nav, string $esq, string $cab ) : string {
      $_ = "";      

      $agr = _htm::dat($nav->ope);

      $_art = _dat::var("_api.doc_art",[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

      $_ = "
      <article class='inf'>";
        // introduccion
        if( !empty($agr['htm_ini']) ){
          $_ .= $agr['htm_ini'];
        }
        else{ $_ .= "
          <h1>{$nav->nom}</h1>";
        }
        // listado de contenidos
        if( !empty($_art) ){ $_ .= "
  
          <nav class='lis'>";
            foreach( $_art as $art ){
              $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>"._doc::let($art->nom)."</a>";
              if( !empty($art->ope['tex']) ){
                $_ .= "            
                <div class='val nav'>
                  "._doc::ope('tog_ico')."
                  {$art_url}
                </div>
                <div class='dat'>
                  "._htm::val($art->ope['tex'])."
                </div>
                ";
              }else{
                $_ .= $art_url;
              }
              
            }$_.="
          </nav>";
        }
        // pie de pagina
        if( !empty($agr['htm_fin']) ){
          $_ .= $agr['htm_fin'];
        }
        $_ .= "
      </article>";          

      return $_;
    }        

    // genero secciones del artículo por indices
    static function nav( string $ide ) : string {

      $_ = "";

      $_ide = explode('.',$ide);

      $_nav = _dat::var("_api.doc_nav",[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

      if( isset($_nav[1]) ){

        foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
          
          <?=_doc::ope('tog',['eti'=>'h2','id'=>'_{$nv1}-','htm'=>'"._doc::let($_nv1->nom)."'])?>
          <section>";
            if( isset($_nav[2][$nv1]) ){
              foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "
  
            <?=_doc::ope('tog',['eti'=>'h3','id'=>'_{$nv1}-{$nv2}-','htm'=>'"._doc::let($_nv2->nom)."'])?>
            <section>";
              if( isset($_nav[3][$nv1][$nv2]) ){
                foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "
  
              <?=_doc::ope('tog',['eti'=>'h4','id'=>'_{$nv1}-{$nv2}-{$nv3}-','htm'=>'"._doc::let($_nv3->nom)."'])?>
              <section>";
                if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                  foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "
  
                <?=_doc::ope('tog',['eti'=>'h5','id'=>'_{$nv1}-{$nv2}-{$nv3}-{$nv4}-','htm'=>'"._doc::let($_nv4->nom)."'])?>
                <section>";
                  if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                    foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "
  
                  <?=_doc::ope('tog',['eti'=>'h6','id'=>'_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-','htm'=>'"._doc::let($_nv5->nom)."'])?>
                  <section>                      
  
                  </section>";
                    }
                  }$_ .= "                  
                </section>";
                  }
                }$_ .= "                
              </section>";
                }
              }$_ .= "              
            </section>";
              }
            }$_ .= "              
          </section>";
        }
      }      

      return $_;
    }

    // genero glosarios por esquema
    static function ide( string $ide, array $ele = [] ) : string {
      
      $_ = [];
      $_ide = explode('.',$ide);      
      
      if( is_array( $tex = _dat::var('_api.doc_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

        foreach( $tex as $pal ){
          $_[ $pal->nom ] = $pal->des;
        }
      }      

      return _doc_lis::ite($_,$ele,'ope');
    }

  }
  // navegadores : nav > a 
  class _doc_nav {

    static string $IDE = "_doc_nav-";

    static string $EJE = "_doc_nav.";

    // enlaces : win + cab + pan + sec + pie
    static function ver( $dat, ...$opc ) : string {
      $_ide = self::$IDE."ver";
      $_eje = self::$EJE."ver";      
      $_ = "";

      foreach( ( !empty($opc) ? $opc : [ 'pan', 'sec', 'win', 'cab' ] ) as $tip ){

        if( isset($dat[$tip]) ){

          foreach( $dat[$tip] as $ide => $art ){

            $eje_val = "$_eje('$tip','$ide');";

            if( is_string($art) ){

              $_ .= _doc::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_val ]);
            }
            elseif( is_array($art) ){

              if( isset($art[0]) ){

                $_ .= _doc::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_val ]);
              }
              elseif( isset($art['ico']) ){

                $_ .= _doc::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_val ]);
              }
              
            }
            elseif( is_object($art) && isset($art->ico) ){

              $_ .= _doc::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_val ]);
            }
          }
        }
      }
      return $_;
    }
    // cabecera : lis > a[href]
    static function cab( array $dat, array $ele = [] ) : string {
      foreach( ['nav'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_ide = self::$IDE."cab";
      $_eje = self::$EJE."cab";
      $_ = "";

      // cargo menú principal
      $nav_1 = isset($dat[0]) ? $dat[0] : [];
      $nav_2 = isset($dat[1]) ? $dat[1] : [];
      $nav_3 = isset($dat[2]) ? $dat[2] : [];
      $nav_4 = isset($dat[3]) ? $dat[3] : [];

      $ele_ico = [ 'class'=>'mar_der-1' ]; 
      _ele::cla($ele['nav'], "cab", 'ini' ); $_ = "
      <nav"._htm::atr($ele['nav']).">

        <ul"._htm::atr(isset($ele['lis']) ? $ele['lis'] : []).">";        
        foreach( $nav_1 as $app => $app_dat ){

          if( isset($nav_2[$app]) ){
            
            // 1° nivel : menú
            foreach( $nav_2[$app] as $nav => $_nav ){
              $ope = _dat::dec($_nav->ope);
              $pos = isset($ope->pos);
              $ico = !empty($ope->ico) ? _doc::ico( $ope->ico, $ele_ico ) : ''; 
              $doc_url = empty($nav_3[$app][$nav]) ? " href='".SYS_NAV."{$app}/{$nav}'" : ''; $_ .= "
              <li>
                <a{$doc_url}>{$ico}{$_nav->nom}</a>";
                if( !empty($nav_3[$app][$nav]) ){ $_ .= "
                  <ul app='{$app}' art='{$nav}'>";
                  // 2° nivel : articulo
                  foreach( $nav_3[$app][$nav] as $art => $_art ){
                    $ope = $_art->ope;
                    $nom_agr = !!$pos ? "{$_art->pos} - " : ''; 
                    $ico = !empty($ope['ico']) ? _doc::ico( $ope['ico'], $ele_ico ) : '';
                    $doc_url = empty($nav_4[$app][$nav][$art]) ? " href='".SYS_NAV."{$app}/{$nav}/{$art}'" : ''; $_ .= "
                    <li>
                      <a{$doc_url}>{$ico}"._doc::let("{$nom_agr}{$_art->nom}")."</a>";
                      if( empty($doc_url) ){ $_ .= "
                        <ul>";
                        // 3° nivel : valores
                        foreach( $nav_4[$app][$nav][$art] as $val => $_val ){
                          $ope = _dat::dec($_val->ope);
                          $ico = !empty($ope->ico) ? _doc::ico( $ope->ico, $ele_ico ) : ''; 
                          $doc_url = empty($nav_4[$app][$nav][$art]) ? " href='".SYS_NAV."{$app}/{$nav}/{$art}/{$_val->ide}'" : ''; $_ .= "
                          <li>
                            <a{$doc_url}>{$ico}{$_val->nom}}</a>
                          </li>";
                        } $_ .= "
                        </ul>";
                      } $_ .= "
                    </li>";
                  } $_ .= "
                  </ul>";
                } $_ .= "
              </li>";
            }
          }          
        }$_ .= "
        </ul>

      </nav>";        
      return $_;
    }
    // listado : titulo + descripcion + listado > item = [icono] + enlace
    static function lis( array $dat, array $ope = [] ) : string {
      foreach( ['nav','lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i] = []; }      
      $_ide = self::$IDE."lis";
      $_eje = self::$EJE."lis";
      $_ = "";
      
      _ele::cla($ope['nav'],"lis val",'ini');

      _ele::cla($ope['lis'],"lis ite",'ini');

      $_.="
      <nav"._htm::atr($ope['nav']).">";
        if( isset($ope['nom']) ){
          $_.="<p class='tit mar_arr-0 let-4 tex_ali-cen'>"._doc::let($ope['nom'])."</p>";
        }
        if( isset($ope['des']) ){
          $_.="<p class='des let-4 tex_ali-cen'>"._doc::let($ope['des'])."</p>";
        }
        $_.="
        <ul"._htm::atr($ope['lis']).">";
          $ite_ico = isset($ope['ico']) ? _doc::ico( $ope['ico'], [ 'class'=>"mar_der-1" ] ) : "";
          foreach( $dat as $pos => $ite ){
            $ope_val = _ele::val($ope['val'],$ite);// proceso variables del item            
            if( !isset($ope_val['htm']) ) $ope_val['htm'] = isset($ite->nom) ? $ite->nom : $pos;
            if( !empty($ite->des) && empty($ope_val['title']) ) $ope_val['title'] = $ite->des;
            if( empty($ope_val['href'])){
              if( isset($ite->nav) ){
                $ope_val['href'] = SYS_NAV."/$ite->nav";
              }elseif( isset($ite->ide) ){
                $ope_val['href'] = SYS_NAV."/"._tex::mod($ite->nav,'_','/');
              } 
            } $_ .= "
            <li"._htm::atr($ope['ite']).">
              {$ite_ico}
              <a"._htm::atr($ope_val).">"._doc::let($ope_val['htm'])."</a>
            </li>";
          }$_.="
        </ul>";
        $_.="
      </nav>";      
      return $_;
    }
    // articulo : nav + article > ...section[id]
    static function art( array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['ope','nav','lis'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_ide = self::$IDE."art";
      $_eje = self::$EJE."art";
      $_ = "";      

      _ele::cla( $ele['nav'], "lis art", 'ini' );

      _ele::cla( $ele['lis'], "lis alt-100", 'ini' );

      _ele::cla( $ele['ope'], "inf ite mar-0", 'ini' );

      $opc_ide = in_array('ide',$opc);
      $nav_ite = " onclick=\"$_eje('val',this);\"";
      $win_tog = _doc::ope('tog_ico',[ 'ocu'=>1 ]);

      $_ .= "
      <nav"._htm::atr($ele['nav']).">

        <form"._htm::atr($ele['ope']).">

          "._doc::ope('tog_ver',[ 'eje'=>"$_eje('val',this);" ])."

          "._doc_val::ver('tex',[ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"$_eje('ver',this);" ])."

        </form>

        <ul"._htm::atr($ele['lis']).">";          
        foreach( $dat[1] as $nv1 => $_nv1 ){
          $cla = empty($ico = isset($dat[2][$nv1]) ? $win_tog : '') ? ' sep' : ''; 
          $ide = $opc_ide ? $_nv1->ide : $nv1; $_ .= "
          <li class='ite{$cla}'>
            <div class='val'>{$ico}<a href='#_{$ide}-'{$nav_ite}>"._doc::let("{$_nv1->nom}")."</a></div>";
            if( !empty($ico) ){ $_ .= "
              <ul class='lis dis-ocu'>";
              foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
                $cla = empty($ico = isset($dat[3][$nv1][$nv2]) ? $win_tog : '') ? ' sep' : '';
                $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; $_ .= "
                <li class='ite{$cla}'>
                  <div class='val'>{$ico}<a href='#_{$ide}-'{$nav_ite}>"._doc::let("{$_nv2->nom}")."</a></div>";
                  if( !empty($ico) ){ $_ .= "
                    <ul class='lis dis-ocu'>";
                    foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
                      $cla = empty($ico = isset($dat[4][$nv1][$nv2][$nv3]) ? $win_tog : '') ? ' sep' : '';
                      $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}"; $_ .= "
                      <li class='ite{$cla}'>
                        <div class='val'>{$ico}<a href='#_{$ide}-'{$nav_ite}>"._doc::let("{$_nv3->nom}")."</a></div>";
                        if( !empty($ico) ){ $_ .= "
                          <ul class='lis dis-ocu'>";
                          foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){                        
                            $cla = empty($ico = isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ? $win_tog : '') ? ' sep' : '';
                            $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; $_ .= "
                            <li class='ite{$cla}'>
                              <div class='val'>{$ico}<a href='#_{$ide}-'{$nav_ite}>"._doc::let("{$_nv4->nom}")."</a></div>";
                              if( !empty($ico) ){ $_ .= "
                                <ul class='lis dis-ocu'>";
                                foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                                  $cla = empty($ico = isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ? $win_tog : '') ? ' sep' : ''; 
                                  $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; $_ .= "
                                  <li class='ite{$cla}'>
                                    <div class='val'>{$ico}<a href='#_{$ide}-'{$nav_ite}>"._doc::let("{$_nv5->nom}")."</a></div>";
                                    if( !empty($ico) ){ $_ .= "
                                      <ul class='lis dis-ocu'>";
                                      foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                                        $cla = empty($ico = isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ? $win_tog : '') ? ' sep' : ''; 
                                        $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; $_ .= "
                                        <li class='ite{$cla}'>
                                          <div class='val'>
                                            {$ico}
                                            <a href='#_{$ide}-'{$nav_ite}>"._doc::let("{$_nv6->nom}")."</a>
                                          </div>";
                                          if( !empty($ico) ){ 
                                            $_ .= "";
                                          }$_ .= "
                                        </li>";
                                      }$_ .= "
                                      </ul>";
                                    }$_ .= "
                                  </li>";
                                }$_ .= "
                                </ul>";
                              }$_ .= "
                            </li>";
                          }$_ .= "
                          </ul>";
                        } $_ .= "
                      </li>";
                    }$_ .= "
                    </ul>";
                  } $_ .= "
                </li>";
              }$_ .= "
              </ul>";
            } $_ .= "
          </li>";
        }$_.="
        </ul>";
        $_.="
      </nav>";           
      return $_;
    }
    // horizontales : nav(.bar, .pes, .ope) + * > ...[nav="ide"]
    static function val( string $tip, array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['nav','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_ide = self::$IDE."val";
      $_eje = self::$EJE."val";
      $_ = "";
      
      _ele::cla($ele['nav'], $tip, 'ini');

      $_ .= "
      <nav"._htm::atr($ele['nav']).">";
    
      foreach( $dat as $ide => $val ){

        if( is_object($val) ) $val = _obj::nom($val);

        if( isset($val['ide']) ) $ide = $val['ide'];

        $ele = isset($val['ele']) ? _dat::dec($val['ele'],[],'nom') : [];

        $ele['eti'] = 'a';

        if( !isset($val['ico']) && empty($ele['htm']) && !empty($val['nom']) ) $ele['htm'] = $val['nom'];

        _ele::eje($ele,'cli',"$_eje(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');
    
        $_ .= _htm::val($ele);
      }$_.="
      </nav>";      
      return $_;
    }
  }  
  // listados [] : ul.lis
  class _doc_lis {

    static string $IDE = "_doc_lis-";

    static string $EJE = "_doc_lis.";

    // por items : dl, ul, ol
    static function ite( array $dat, array $ope = [], ...$opc ) : string {
      foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
      $_ide = self::$IDE."ite";
      $_eje = self::$EJE."ite";
      $_ = "";      
      // operador
      if( in_array('ope',$opc) ){ $_ .= "

        <form class='inf ren jus-ini mar-2'>
    
          <fieldset class='ope'>
            "._doc::ico('ope_tod',['eti'=>"button", 'title'=>"Ver todos...",     'value'=>"tod", 'onclick'=>"$_eje('tog',this);"])."
            "._doc::ico('ope_nad',['eti'=>"button", 'title'=>"Ocultar todos...", 'value'=>"nad", 'onclick'=>"$_eje('tog',this);"])."
          </fieldset>
    
          "._doc::var('val','ver',['nom'=>"Filtrar", 'htm'=>_doc_val::ver('tex',[ 'cue'=>count($dat), 'eje'=>"$_eje('ver',this);" ]) ])."
    
        </form>";
      }
      // por punteo o numerado
      if( _obj::pos($dat) ){
        $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
        $_ .= "
        <{$eti}"._htm::atr($ope['lis']).">";
          foreach( $dat as $pos => $val ){

            if( is_string($val) ){ $_ .= "
              <li"._htm::atr($ope['ite']).">{$val}</li>";
            }
            else{ $_.="
              <{$eti}>";
              foreach( $val as $i_2 => $v_2 ){
                if( is_string($v_2) ){ $_ .= "
                  <li"._htm::atr($ope['ite']).">{$v_2}</li>";
                }
                else{ $_.="
                  <{$eti}>";
                  foreach( $v_2 as $i_3 => $v_3 ){
                    if( is_string($v_3) ){ $_ .= "
                      <li"._htm::atr($ope['ite']).">{$v_3}</li>";
                    }else{ $_.="
                      <{$eti}>";
                      foreach( $v_3 as $i_4 => $v_4 ){
                        if( is_string($v_4) ){ $_ .= "
                          <li"._htm::atr($ope['ite']).">{$v_4}</li>";
                        }else{$_.="
                          <{$eti}>";
                          foreach( $v_4 as $i_5 => $v_5 ){
                            if( is_string($v_5) ){ $_ .= "
                              <li"._htm::atr($ope['ite']).">{$v_5}</li>";
                            }else{$_.="
                              <{$eti}>";
                              $_.="
                              </{$eti}>";
                            }
                          }
                          $_.="
                          </{$eti}>";
                        }
                      }$_.="
                      </{$eti}>";
                    }
                  }$_.="
                  </{$eti}>";
                }
              }$_.="
              </{$eti}>";
            }
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        // agrego toggle del item
        _ele::eje($ope['ite'],'cli',"$_eje('tex',this);",'ini');
        $_ .= "
        <dl"._htm::atr($ope['lis']).">";
          foreach( $dat as $nom => $val ){ 
            $ope_ite = $ope['ite'];

            if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom)); 

            $_ .= "
            <dt"._htm::atr($ope_ite).">"._doc::let($nom)."</dt>";
            foreach( _var::ite($val) as $ite ){ $_ .= "
              <dd"._htm::atr($ope['val']).">"._doc::let($ite)."</dd>";
            }
          }$_.="
        </dl>";
      }
      return $_;
    }    
    // por valores : ul > ...li > .ico + .val[tex-tit]
    static function val( array $dat, array $ope = [], ...$opc ) : string {
      $_ide = self::$IDE."val";
      $_eje = self::$EJE."val";
      $_ = "";

      foreach( ['lis','ite','val','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      // datos
      $dat = _dat::dec($dat);
      // elementos        
      _ele::cla($ope['lis'],"lis",'ini');
      _ele::cla($ope['dep'],"lis",'ini');
      _ele::cla($ope['ite'],"ite",'ini');
      _ele::cla($ope['ope'],"ite",'ini');

      _ele::eje($ope['val'],'cli',"_doc.ope('tog',this);",'ini');

      $_tog = _doc::ope('tog_ico', ( isset($ope['dep']['class']) && preg_match("/dis-ocu/",$ope['dep']['class']) ) ? [ 'ocu'=>1 ] : [] );

      // operador
      $htm="";
      if( in_array('tog',$opc) ){
        $htm .= _doc::ope("tog_ver");
      }
      if( in_array('ver',$opc) ){
        $htm .= _doc::var('val','ver',[ 
          'des'=>"Filtrar...", 
          'htm'=>_doc_val::ver('tex',[ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"$_eje('ver',this);" ]) 
        ]);
      }
      if( !empty($htm) ){ $_ .= "        
        <form"._htm::atr($ope['ope']).">
          {$htm}
        </form>";        
      }
      if( !isset($ope['val']['eti']) ){ 
        $ope['val']['eti']='p';
        _ele::cla($ope['val'],"let-tit");
      }
      $htm_fin = isset($ope['htm_fin']) ? _htm::val($ope['htm_fin']) : "";
      $_ .= "
      <ul"._htm::atr($ope['lis']).">";     
      foreach( $dat as $i_1 => $v_1 ){
        $ite_ele = isset($ope['ite-1']) ? _ele::jun($ope['ite'],$ope['ite-1']) : $ope['ite'];
        if( $val_dep = is_iterable($v_1) ){ 
          $ope['val']['htm'] = _doc::let($i_1);
          $htm = "<div class='val'>$_tog"._htm::val($ope['val'])."$htm_fin</div>";
        }else{
          $htm = $v_1;
        }$_ .= "
        <li"._htm::atr($ite_ele).">
          {$htm}";
          if( $val_dep ){ 
            $ope_lis_1 = isset($ope['lis-1']) ? _ele::jun($ope['dep'],$ope['lis-1']) : $ope['dep'] ; $_ .= "
            <ul"._htm::atr($ope_lis_1).">";
            foreach( $v_1 as $i_2 => $v_2 ){
              $ite_ele = isset($ope['ite-1']) ? _ele::jun($ope['ite'],$ope['ite-1']) : $ope['ite'];
              if( $val_dep = is_iterable($v_2) ){ 
                $ope['val']['htm'] = _doc::let($i_2);
                $htm = "<div class='val'>$_tog"._htm::val($ope['val'])."$htm_fin</div>";
              }else{
                $htm = $v_2;
              } $_ .= "
              <li"._htm::atr($ite_ele).">
                {$htm}";
                if( $val_dep ){ 
                  $ope_lis_2 = isset($ope['lis-2']) ? _ele::jun($ope['dep'],$ope['lis-2']) : $ope['dep'] ; $_ .= "
                  <ul"._htm::atr($ope_lis_2).">";
                  foreach( $v_2 as $i_3 => $v_3 ){
                    $ite_ele = isset($ope['ite-2']) ? _ele::jun($ope['ite'],$ope['ite-2']) : $ope['ite'];
                    if( $val_dep = is_iterable($v_3) ){ 
                      $ope['val']['htm'] = _doc::let($i_3);
                      $htm = "<div class='val'>$_tog"._htm::val($ope['val'])."$htm_fin</div>";
                    }else{
                      $htm = $v_3;
                    } $_ .= "
                    <li"._htm::atr($ite_ele).">
                      {$htm}";
                      if( $val_dep ){ 
                        $ope_lis_3 = isset($ope['lis-3']) ? _ele::jun($ope['dep'],$ope['lis-3']) : $ope['dep']; $_ .= "
                        <ul"._htm::atr($ope_lis_3).">";
                        foreach( $v_3 as $i_4 => $v_4 ){
                          $ite_ele = isset($ope['ite-3']) ? _ele::jun($ope['ite'],$ope['ite-3']) : $ope['ite'];
                          if( $val_dep = is_iterable($v_4) ){ 
                            $ope['val']['htm'] = _doc::let($i_4);
                            $htm = "<div class='val'>$_tog"._htm::val($ope['val'])."$htm_fin</div>";
                          }else{
                            $htm = $v_4;
                          } $_ .= "
                          <li"._htm::atr($ite_ele).">
                            {$htm}";
                            if( $val_dep ){ 
                              $ope_lis_4 = isset($ope['lis-4']) ? _ele::jun($ope['dep'],$ope['lis-4']) : $ope['dep']; $_ .= "
                              <ul"._htm::atr($ope_lis_4).">";
                              foreach( $v_4 as $i_5 => $v_5 ){
                                $ite_ele = isset($ope['ite-4']) ? _ele::jun($ope['ite'],$ope['ite-4']) : $ope['ite'];
                                if( $val_dep = is_iterable($v_5) ){ 
                                  $ope['val']['htm'] = _doc::let($i_5);
                                  $htm = "<div class='val'>$_tog"._htm::val($ope['val'])."$htm_fin</div>";
                                }else{
                                  $htm = $v_5;
                                } $_ .= "
                                <li"._htm::atr($ite_ele).">
                                  {$htm}
                                </li>";
                              }$_ .= "
                              </ul>";
                            }$_ .= "
                          </li>";
                        }$_ .= "
                        </ul>";
                      } $_ .= "
                    </li>";
                  }$_ .= "
                  </ul>";
                } $_.="
              </li>";
            }$_ .= "
            </ul>";
          }$_ .= "
        </li>";
      }$_ .= "
      </ul>";      

      return $_;
    }
    // horizontal con barra de desplazamiento por item
    static function bar( array $dat, array $ope = [], ...$opc ) : string {
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
          $ope['ite']['pos'] = $pos;
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
      if( !in_array('ope',$opc) ){
        $min = $pos == 0 ? 0 : 1;
        $max = $pos;
        $_ .= "
        <form class='ope anc-100 jus-cen mar_arr-1'>

          <n name='min' class='mar_hor-1' title='La Primer Posicion...'>$min</n>

          <c class='sep'>[</c>
          "._doc::ico("nav_ini",['eti'=>"button", 'title'=>"Ver el primero...",   'value'=>"ini", 'oninput'=>"$_eje('val',this);"])."  
          "._doc::ico("nav_pre",['eti'=>"button", 'title'=>"Ver el anterior...",  'value'=>"pre", 'oninput'=>"$_eje('val',this);"])."
          "._doc_num::ope('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'onchange'=>"$_eje('val',this);" ])."
          "._doc::ico("nav_pos",['eti'=>"button", 'title'=>"Ver el siguiente...", 'value'=>"pos", 'oninput'=>"$_eje('val',this);"])."  
          "._doc::ico("nav_fin",['eti'=>"button", 'title'=>"Ver el último...",    'value'=>"fin", 'oninput'=>"$_eje('val',this);"])."
          <c class='sep'>]</c>

          <n name='max' class='mar_hor-1' title='La Última Posicion...'>$max</n>

        </form>";
      }
      return $_;
    }
    // listado de ...atributo: valor
    static function atr( string | array | object $dat, array $ope = [], ...$opc ) : string {
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";
      $_ = "";

      $_ide = $_ide;
      if( is_string($dat) ){
        $var_dat = _var::ide($dat,$var_dat);        
        $esq = $var_dat['esq'];
        $est = $var_dat['est'];                
        $_ide = "{$_ide} _$esq.$est";
        // datos
        $dat = _dat::var($dat,$ope);
      }

      // estructura
      $ide = _var::ide($ope['est']);
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
      extract( _var::ide($ide) );

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
      extract( _var::ide($ide) );
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
          
          if( $col = _dat::ver('col',$esq,$est,$atr) ){
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
          if( !empty($_ima) || !empty( $_ima = _dat::ver('ima',$esq,$est,$atr) ) ){
            
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
    // selectores de estructuras / valores
    static function opc( string $tip, string $ide, mixed $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;            
      $_opc = function( $esq, $est, $ide, $val, ...$opc ) : string {// genero opciones y agrupo por titulo de estructura
        $_ = "";
        // atributos parametrizados
        if( ( $dat_opc_ide = _dat::ope($esq,$est,$ide) ) && is_array($dat_opc_ide) ){

          // recorro atributos + si tiene el operador = agrego la opcion      
          foreach( $dat_opc_ide as $atr ){

            if( !empty( $_atr = _dat::atr($esq,$est,$atr) ) ){

              $eti = [ 'value' => "{$esq}.{$est}.{$atr}", 'dat'=>"{$esq}." ];

              if( !empty($_atr->var['dat']) ){ $eti['dat'] = $_atr->var['dat']; }else{ $eti['dat'] .= _dat::ide($esq,$est,$atr); }

              if( $val == $eti['value'] ){ $eti['selected']="1"; } 

              $_ .= "
              <option"._htm::atr($eti).">{$_atr->nom}</option>";
            }
          }
          if( in_array('gru',$opc) && !empty($_) ){
            $_est = _dat::est($esq,$est); $_ = "
            <optgroup ide='{$_est->ide}' label='".(isset($_est->nom) ? $_est->nom : $est)."'>
              {$_}
            </optgroup>";
          }      
        }
        return $_;
      };

      extract( _var::ide($ide) );

      switch( $tip ){
      // opciones de propiedadades en estructura por atributos
      case 'est':
        $ele_ope = isset($ope['ope']) ? $ope['ope'] : [];
        $ele_ite = isset($ope['ite']) ? $ope['ite'] : [];
        
        if( !empty(_api::var('doc',$esq,$est,$atr)) ){

          // esquemas => estructuras
          $dat = [];
          if( isset( $ele_ope['dat'] ) ){
            $dat = $ele_ope['dat'];
            unset($ele_ope['dat']);
            if( is_string($dat) || _obj::pos($dat) ){
              $ide = is_string($dat) ? explode('.',$dat) : $dat;
              $dat = [ $ide[0] => [ $ide[1] ] ];
            }
          }

          // atributos por estructuras : filtro + color + imagen + texto + numeros + fechas
          $dat_atr = isset($ele['dat_atr']) ? $ele['dat_atr'] : $atr;

          // aseguro seleccion
          $val = "";
          if( isset($ele['val']) ){
            $val = $ele['val'];
          }
          elseif( isset($ele_ope['val']) ){
            $val = $ele_ope['val'];
            unset($ele_ope['val']);
          }

          $htm_opc = "";
          foreach( $dat as $esq_ide => $est_lis ){

            foreach( $est_lis as $est_ide ){

              if( $dat_opc_est = _dat::ope($esq_ide,$est_ide,'est') ){

                foreach( $dat_opc_est as $dep_ide ){

                  $htm_opc .= $_opc( $esq_ide, $dep_ide, $dat_atr, $val, 'gru' );
                }
              }
              else{

                $htm_opc .= $_opc( $esq_ide, $est_ide, $dat_atr, $val );
              }
            }
          }

          // selector de esquema
          // ...

          // selector de estructuras
          // ...

          // selector de atributos
          $ele_ope['name'] = $atr;
          $ele['htm'] = "
          <select"._htm::atr($ele_ope).">
            <option value=''>{-_-}</option>
            {$htm_opc}
          </select>";

          // selector de valores por relacion
          // ...

          $_ = _doc::var('doc', "$esq.$est.$atr", $ele);

        }
        else{
          $ele['nom'] = "desconocido: _{$esq}-{$est}-{$atr}".( isset($ele_ope['dat']) ? " -> {$ele_ope['dat']}" : '' );
        }            
        break;            
      }

      return $_;
    }
    // imagen : identificadores + ficha
    static function ima( string $tip, string $ide, mixed $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      
      // proceso estructura
      extract( _var::ide($ide) );

      switch( $tip ){
      case 'fic': 
        $_fic = _dat::ope($esq,$est,'fic');

        if( isset($_fic->ide) ){
          
          if( !empty($val) ) $val = _dat::var($esq,$est,$val); 
        
          $ima = _dat::ver('ima',$esq,$est,$atr = $_fic->ide);          
          
          $_ .= "
          <div class='val' data-ima='{$ima['ide']}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>";
            if( !empty($val) ){ 
              $_ .= _doc::ima($esq,$est,$val); 
            } $_ .= "
          </div>

          <c class='sep'>=></c>
      
          <c class='lis ini'>{</c>";
          foreach( $_fic->atr as $atr ){
            
            $_ima = _dat::ver('ima',$esq,$est,$atr); 
            $_ .= "
            <div class='val mar_hor-1' data-ima='{$_ima['ide']}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>";
              if( !empty($val) ){ 
                $_ .= _doc::ima($esq,$ima,$val->$atr); 
              }$_ .= "
            </div>";
          }$_ .= "
          <c class='lis fin'>}</c>";
        }        
        break;
      }

      return $_;
    }

  }
  // operador de valores : acumulados, sumatorias, filtros, cuentas
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
    static function dat( string $tip, array $ope = [], array $ele = [], ...$opc ) : string {
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
    // filtros
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

        // pedir estructuras por filtro        

        // armar selector de valores vacío con ícono

        // agregar opciones: incremento
        $_ .= _doc_val::ver('lim',$dat,isset($ele['ope']) ? $ele['ope'] : []);

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
          $_eje = "_doc.ope('val_mar', this, 'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
          $ele['htm_fin'] = "
          <fieldset class='ope'>
            "._doc::ico('nav_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            "._doc::ico('nav_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>"; 
          $_ .=
          _doc::var('doc', "val.ver.cue", $_ite('cue',$dat,$ele) );
        }
        break;
      // textuales
      case 'tex': 
        $dat['eje'] = !empty($dat['eje']) ? $dat['eje'] : NULL;
        // opciones de filtro por texto
        $_ .= _api::var_ope('opc',['ver','tex'],[
          'eti'=>[ 
            'name'=>"ope", 'title'=>"Seleccionar un operador de filtro...", 'val'=>'**', 
            'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
          ],
          'ite'=>[ 'dat'=>"()($)dat()" ]
        ]);
        // ingreso de valor a filtrar
        $_ .= _doc_tex::ope('ora', isset($dat['val']) ? $dat['val'] : '', [ 'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
          'name'=>"val", 'title'=>"Introducir un valor de filtro...", 
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
    // acumulado
    static function acu( array $dat, array $ope = [], array $opc = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."acu";
      $_eje = self::$EJE."acu";

      if( empty($opc) ) $opc = array_keys($dat);

      $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

      if( !empty($ope['ide']) ) $_ide = $ope['ide'];
      $_ = "
      <div class='val esp-ara'>";
        foreach( $opc as $ide ){        
          $_ .= _doc::var('doc',"val.acu.$ide", [
            'ope'=> [ 
              'id'=>"{$_ide}-{$ide}", 
              'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 
              'onchange'=>$_eje_val
            ],
            'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
          ]);
        }
        if( !empty($ope['htm_fin']) ){
          $_ .= $ope['htm_fin'];
        }$_ .= "
      </div>";         

      return $_;
    }
    // sumatorias
    static function sum(  string | array $dat, array $ope = [] ) : string {

      $_ = "";
      $_ide = self::$IDE."sum";
      $_eje = self::$EJE."sum";

      if( is_string($dat) ){
        extract( _var::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      if( isset($ope['ide']) ) $_ide = $ope['ide'];
      // operadores por esquemas
      foreach( _api::var($esq,'val','sum') as $ide => $ite ){
    
        $_ .= _doc::var($esq, ['val','sum',$ide], [
          'ope'=>[
            'id'=>"{$_ide} sum-{$ide}" 
          ],
          'htm_fin'=> !empty($ite['var_fic']) ? _doc_dat::ima('fic',$ite['var_fic']) : ''
        ]);
      }    

      return $_;
    }
    // conteos
    static function cue( string $tip, string | array $dat, array $ope = [], ...$opc ) : string | array {
      $_ = "";
      $_ide = self::$IDE."cue";
      $_eje = self::$EJE."cue";

      if( is_string($dat) ){
        extract( _var::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      switch( $tip ){        
      case 'act':
        if( isset($ope['ide']) ) $_ide = $ope['ide'];
        // armo relacion por atributo
        $ide = !empty($atr) ? _dat::ide($esq,$est,$atr) : $est;
        $_ = "
        <!-- filtros -->
        <form>
          <fieldset class='val'>
            "._doc::var('val','ver',[ 'nom'=>"Filtrar", 'id'=>"{$_ide}-ver {$esq}-{$ide}",
              'htm'=>_doc_val::ver('tex',[ 
                'ide'=>"{$_ide}-ver {$esq}-{$ide}", 
                'eje'=>"$_eje('ver',this);" 
              ])
            ])."
          </fieldset>
        </form>
  
        <!-- valores -->
        <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
          <tbody>";
          foreach( _dat::var($esq,$ide) as $ide => $_var ){
          
            $ide = isset($_var->ide) ? $_var->ide : $ide;
  
            if( !empty($atr) ){
              $ima = !empty( $_ima = _dat::ver('ima',$esq,$est,$atr) ) ? _doc::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
            }
            else{
              $ima = _doc::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
            }            
            $_ .= "
            <tr data-ide='{$ide}'>
              <td data-atr='ima'>
                {$ima}
              </td>
              <td data-atr='ide'>
                "._doc::let($ide)."
              </td>            
              <td data-atr='nom'>
                "._doc::let(isset($_var->nom) ? $_var->nom : '')."
              </td>
              <td>
                <c>:</c>
              </td>
              <td data-atr='tot' title='Cantidad seleccionada...'>
                <n>0</n>
              </td>
              <td>
                <c>=></c>
              </td>
              <td data-atr='por' title='Porcentaje sobre el total...'>
                <n>0</n><c>%</c>
              </td>
            </tr>";
          } $_ .= "
          </tbody>
        </table>";
        break;
      case 'dat': 
        $_ = [];
        foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){
          // -> por estructuras
          foreach( $est_lis as $est_ide ){
            // -> por dependencias ( est_atr )
            foreach( ( !empty($dat_opc_est = _dat::ope($esq,$est_ide,'est')) ? $dat_opc_est : [ $est_ide ] ) as $est ){      
              // armo listado para aquellos que permiten filtros
              if( $dat_opc_ver = _dat::ope($esq,$est,'ver') ){

                // cargo datos de la estructura + atributos
                $est_nom = _dat::est($esq,$est)->nom;              
                $_[$est_nom] = [];
      
                foreach( $dat_opc_ver as $atr ){
                  // armo relacion por atributo
                  $rel = _dat::ide($esq,$est,$atr);
                  // busco nombre de estructura relacional                      
                  $rel_nom = _dat::est($esq,$rel)->nom;
                  $_[$est_nom][$rel_nom] = [ _doc_val::cue('act',"{$esq}.{$est}.{$atr}",$ope) ];      
                }
              }
            }
          }
        }      
        break;
      }    

      return $_;
    }
  }
  // tablero ( grilla ) : opciones + seccion + posiciones
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
      extract( _var::ide($dat) );
      $_ide .= " _$esq.$est";
      
      // por aplicacion : posicion + seleccion
      if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];

      // imprimo operadores
      if( empty($tip) ){

        foreach( ['sec'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }                
        $_ .= "
  
        "._doc_tab::ope('val',$dat,$ope,$ele)."
  
        "._doc_nav::val('pes', _obj::nom($_ope,'ver',$ope_lis = ['opc','pos','ver']),[ 'nav'=>['class'=>"mar_arr-2"] ])."
  
        <div>";
          $ope_ver = 'opc';
          foreach( $ope_lis as $ide ){
            $ele['sec'] = _ele::jun($ele['sec'],[ 'class'=> $ope_ver != $ide ? DIS_OCU : '' ]);
            $_ .= _doc_tab::ope($ide,$dat,$ope,$ele,...$opc);
          }$_ .= "
        </div>";
      }// por modulos
      else{
        switch( $tip ){
        // valores : totales + acumulados + sumatorias
        case 'val':
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">
            <h3 class='dis-ocu'>Valores</h3>

            <form ide='acu'>
              <fieldset class='inf ren'>
                <legend>{$_ope[$tip]['nom']}</legend>";

                $_ .= _doc::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ]);
                
                if( isset($ope[$tip]) ){
                  $_ .= "
                  <div class='ren'>";             
                  $_ .= _doc_val::acu($ope[$tip],[ 
                    'ide'=>$_ide, 
                    'eje'=>"$_eje('acu',this);", 
                    'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
                  ]);
                  $_ .="
                  </div>";
                }
                $_ .="
              </fieldset>
            </form>";

            $_ .= "
            <form ide='sum'>

              <fieldset class='inf ren' data-esq='hol' data-est='kin'>
                <legend>Sumatorias del Kin</legend>

                "._doc_val::sum('hol',[
                  'ide'=>$_ide,
                  'eje'=>"$_eje(this);"
                ])."

              </fieldset>
            </form>";
              
            $_ .= "
          </section>";
          break;
        // Filtros : estructuras/db + posiciones + fechas
        case 'ver':
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">
            <h4 class='dis-ocu'>Filtros</h4>

            <form ide='dat'>
              <fieldset class='inf ren'>
                <legend>por Datos</legend>

                "._doc_val::ver('dat', [ 'inc'=>[], 'cue'=>[] ], [ 
                  'ope'=>[ 'id'=>"{$_ide}", 'dat'=>$ope['est'], 'onchange'=>"$_eje('dat',this);" ] 
                ])."
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
              
            </form>";
            $_ .="    
          </section>";          
          break;
        // Opciones : sección + posición
        case 'opc':
          if( !empty($ope['sec']) || !empty($ope['pos']) ){
            $ele['sec']['ide'] = $tip; $_ .= "
            <section"._htm::atr($ele['sec']).">";
              // secciones
              $tip = 'sec';
              $ele_tip = "{$_ide}-{$tip}";
              $ele_eve = "$_eje('$tip',this);";
              if( !empty($ope[$tip]) ){
                $ele['ope']['ide'] = $tip; 
                $_ .= "
                <form"._htm::atr($ele['ope']).">
                  <fieldset class='inf ren'>
                    <legend>Secciones</legend>";

                    // operadores globales
                    if( !empty($tab_sec = _api::var('doc','tab',$tip)) ){ $_ .= "
                      <div class='val'>";
                      foreach( _api::var('doc','tab',$tip) as $ide => $ite ){
                        if( isset($ope[$tip][$ide]) ){ 
                          $_ .= _doc::var('doc', "tab.$tip.$ide", [ 
                            'ope'=>[ 'id'=>"{$ele_tip}-{$ide}", 'val'=>$ope[$tip][$ide], 'onchange'=>$ele_eve ] 
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
              /////////////////////////////////
              // posiciones
              $tip = 'pos';
              $ele_tip = "{$_ide}-{$tip}";
              $ele_eve = "$_eje('$tip',this);";
              if( !empty($ope[$tip]) ){ 
                $ele['ope']['ide'] = $tip; 
                $_ .= "
                <form"._htm::atr($ele['ope']).">    
                  <fieldset class='inf ren'>
                    <legend>Posiciones</legend>";
                    // bordes            
                    $ide = 'bor';
                    $_ .= _doc::var('doc', "tab.$tip.$ide",[
                      'ope'=>[ 'id'=>"{$ele_tip}-bor", 'onchange'=>$ele_eve, 'val'=>isset($ope[$tip][$ide]) ? $ope[$tip][$ide] : 0 ] 
                    ]);                
                    // color de fondo - numero - texto - fecha
                    foreach( ['col','num','tex','fec'] as $ide ){                  
                      if( isset($ope[$tip][$ide]) ){
                        $_ .= _doc_dat::opc('est', "tab.{$tip}.{$ide}", [
                          'ope'=>[ 'id'=>"{$ele_tip}-{$ide}", 'val'=>$ope[$tip][$ide], 'dat'=>$ope['est'], 'onchange'=>$ele_eve ]
                        ]);
                      }
                    }
                    // imagen de fondo - ( ficha )
                    if( isset($ope[$tip][$ide = 'ima']) ){ $_ .= "
                      <div class='ren'>";
                        if( isset($ope[$tip][$ide]) ){
                          $_ .= _doc_dat::opc('est', "tab.{$tip}.{$ide}", [
                            'ope'=>[ 'id'=>"{$ele_tip}-{$ide}", 'val'=>$ope[$tip][$ide], 'dat'=>$ope['est'], 'onchange'=>$ele_eve ]
                          ]);
                        }
                        if( isset($ope['val']) ){ $_ .= "
                          <div class='val' ide='acu'>";
                            foreach( array_keys($ope['val']) as $ite ){
          
                              $_ .= _doc::var('doc', "tab.$tip.{$ide}_$ite", [                        
                                'ope'=>[ 
                                  'id'=>"{$ele_tip}-{$ide}_{$ite}", 'onchange'=>$ele_eve,
                                  'val'=>isset($ope[$tip]["{$ide}_{$ite}"]) ? $ope[$tip]["{$ide}_{$ite}"] : FALSE
                                ]
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
              }
              $_ .= "
            </section>";
          }
          break;
        // Posicion principal : atributos por aplicación
        case 'pos':
          if( !empty($ope[$tip]) ){

            $ele['sec']['ide'] = $tip; $_ .= "
            <section"._htm::atr($ele['sec']).">
              <h3 class='dis-ocu'>Atributos</h3>";

              $_ .= _doc_tab::pos($dat,$ope,$ele,...$opc);
              
              $_ .= "
            </section>";
          }
          break;

        }
      }      

      return $_;
    }
    // por opciones : seccion + posicion
    static function opc( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."opc";
      $_eje = self::$EJE."opc";

      // proceso estructura de la base
      extract( _var::ide($dat) );
      $_ide .= " _$esq.$est";        
      $_eje = "_{$esq}_tab.opc";

      // solo muestro las declaradas en el operador
      $atr = array_keys($ope);

      foreach( _api::var($esq,'tab',$tip) as $ide => $_dat ){

        if( in_array($ide,$atr) ){

          $_ .= _doc::var($esq, ['tab',$tip,$ide], [
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
      $_eje = self::$EJE."pos";

      extract( _var::ide($dat) );
      $_ide .= " _$esq.$est";
      $_eje = "_{$esq}_tab.pos";

      foreach( $opc as $atr ){
        $htm = "";
        foreach( _api::var($esq,'pos',$atr) as $ide => $val ){
          $var = [
            'ope'=>[ 
              'id'=>"{$_ide}-{$atr}-{$ide}", 
              'dat'=>$atr, 
              'onchange'=>"$_eje('$atr',this)" ]
          ];
          if( isset($ope[$atr][$ide]) ){
            $var['var']['val'] = $ope[$atr][$ide];
          }
          $htm .= _doc::var($esq, ['pos',$atr,$ide], $var);
        }        
        // busco datos del operador 
        if( !empty($htm) && !empty($_ope = _api::var($esq,'val','pos',$atr)) ){
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
    // por secciones : sub-tableros
    static function sec( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."sec";
      $_eje = self::$EJE."sec";

      return $_;
    }
    
  }
  // estructura ( table ) : columnas( atributo + tipo ) + cuerpo( titulos + datos + detalles) + pie( calculos)
  class _doc_est {

    static string $IDE = "_doc_est-";

    static string $EJE = "_doc_est.";

    static array $OPE = [
      'val' => [ 'nom'=>"Valores"       ], 
      'ver' => [ 'nom'=>"Filtros"       ], 
      'atr' => [ 'nom'=>"Columnas"      ], 
      'des' => [ 'nom'=>"Descripciones" ],      
      'cue' => [ 'nom'=>"Cuentas"       ]
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
    
    // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
    static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _var::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _api::est($esq,$est,$ope);
      }// por listado
      else{        
        if( isset($ope['ide']) ){
          extract( _var::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      if( !isset($ope['dat']) ){
        $ope['dat'] = is_array($dat) ? : _dat::var($esq,$est);
      }
      // genero operadores
      if( empty($tip) ){
        $ope_ver = '';
        $ope['var']['var'] = "est";
        $_ .= "
  
        "._doc_est::ope('val',$dat,$ope,$ele)."
  
        "._doc_nav::val('pes', _obj::nom($_ope,'ver',$ope_lis = ['ver','atr','des','cue']), ['nav'=>['class'=>"mar_arr-2"]], 'tog')."
  
        <div>";
          foreach( $ope_lis as $tip ){
            $ele['sec']=[ 'class'=> $ope_ver != $tip ? DIS_OCU : '' ];
            $_ .= _doc_est::ope($tip,$dat,$ope,$ele);
          }$_ .= "
        </div>

        <div"._htm::atr($ope['var']).">

          "._doc_est::ope('lis',$dat,$ope,$ele,...$opc)."

        </div>
        ";
      }
      // listado + secciones del operador
      else{
        switch( $tip ){
        // genero tabla
        case 'lis':
          foreach( [ 'lis','cue','tit_ite','tit_val','dat_ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
          // identificadores de la base        
          if( isset($esq) ){

            if( !isset($ope['est']) ){ $ope['est'] = [ $esq => [ $est ] ]; }

            $ele['lis']['data-esq'] = $esq;
            $ele['lis']['data-est'] = $est;
          }
          // centrado de texto
          if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';          

          $_ = "
          <table"._htm::atr($ele['lis']).">";
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
            $ope['atr_tot'] = _api::est_atr($dat,$ope);
            // imprimo cabecera
            if( !in_array('cab_ocu',$opc) ){ 
              foreach( [ 'cab','cab_ite','cab_val' ] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
              $_ .= "
              <thead"._htm::atr($ele['cab']).">
                "._doc_est::atr('col',$dat,$ope,$ele,...$opc)."
              </thead>";
            }////////////////////////////////////////////
            // imprimo cuerpo ///////////////////////////
            $_.="
            <tbody"._htm::atr($ele['cue']).">";
              // titulos y detalles por operador directo
              $_ite = function( string $tip, int $pos, array $ope, array $ele ) : string {              
                $_ = "";
                foreach( _var::ite($ope[$tip][$pos]) as $val ){ 
                  $_.="
                  <tr"._htm::atr($ele["{$tip}_ite"]).">
                    <td"._htm::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
                      ".( is_array($val) ? _htm::val($val) : "<p class='{$tip} tex_ali-izq'>"._doc::let($val)."</p>" )."
                    </td>
                  </tr>";
                  return $_;
                }    
              };
              // recorro
              // por listado $dat = []
              if( $dat_val_lis ){

                foreach( $ope['dat'] as $pos => $_dat ){
                  // titulos
                  if( !empty($ope['tit'][$pos]) ) $_ .= $_ite('tit',$pos,$ope,$ele);

                  // fila-columnas
                  $ope['val'] = $_dat; $_.="
                  <tr"._htm::atr($ele['dat_ite']).">
                    "._doc_est::ite($dat,$ope,$ele,...$opc)."
                  </tr>";

                  // detalles
                  if( !empty($ope['det'][$pos]) ) $_ .= $_ite('det',$pos,$ope,$ele);                    
                }
              }// estructuras de la base esquema
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

                      $_ .= _doc_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru']);
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
                      if( $dat_opc_est = _dat::ope($esq,$est,'est') ){

                        foreach( $dat_opc_est as $atr => $ref ){

                          $ele['ite']["{$esq}-{$ref}"] = $_val_dat_obj ? $_dat->$atr : $_dat["{$esq}-{$ref}"];
                        }
                      }
                      // cargo titulos de ciclos                
                      if( $_val['tit'] || $_val['tit_cic'] ){

                        $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                        $_ .= _doc_est::tit('cic', "{$esq}.{$est}", $ope, $ele_ite['tit_cic']);
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
                  // cargo detalles
                  foreach( $ope['est'] as $esq => $est_lis ){

                    foreach( $est_lis as $est ){

                      foreach( ['des','cic','gru'] as $ide ){

                        if( isset($ele_ite["det_{$ide}"]) ){

                          $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                          $_ .= _doc_est::det($ide, "{$esq}.{$est}", $ope, $ele_ite["det_{$ide}"], 'det_cit' );
                        }
                      }                  
                    } 
                  }                    
                }
              }
              $_.="              
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
          break;
        // valores : cantidad + acumulado + operaciones
        case 'val':
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">
            <h3 class='dis-ocu'>Valores</h3>";
            // acumulados
            if( isset($ope[$tip]) ){
              $_ .= "
              <form ide='acu'>

                <fieldset class='inf ren'>
                  <legend>Valores</legend>
                  
                  "._doc::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ])."
                  
                  "._doc::var('doc', "val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"$_eje('tod',this);" ] ])."
                  
                  "._doc_val::acu($ope[$tip],[
                    'ide'=>$_ide, 
                    'eje'=>"$_eje('acu',this); ".self::$EJE."ver('val',this);",
                    'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c> <n>0</n> <c class='sep'>)</c></span>" ]
                  ]); 
                  $_ .= "
                </fieldset>
              </form>";
            }// por datos de la base ?
            else{
            }
            $_ .= "
          </section>";
          break;
        // filtros
        case 'ver':
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">
            <h4 class='dis-ocu'>Filtros</h4>";
            $dat_tot = count($ope['dat']);
            $_ .= "
            <form ide='dat'>
              <fieldset class='inf ren'>
                <legend>por Datos</legend>      
                "._doc_val::ver('dat', [ 'inc'=>[], 'cue'=>[] ], [
                  'ope'=>[ 'id'=>"{$_ide}-dat", 'max'=>$dat_tot, 'dat'=>$ope['est'], 'onchange'=>"$_eje('dat',this);" ]
                ])."
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
            $_ .= "        
          </section>"; 
          break;
        // columnas : ver/ocultar
        case 'atr':
          $lis_val = [];
          foreach( $ope['est'] as $esq => $est_lis ){
                
            foreach( $est_lis as $est ){

              $_est = _api::est($esq,$est);

              $lis_val[$est_nom = _dat::est($esq,$est)->nom] = []; 
              $htm = "  
              <form ide='{$tip}' class='ren jus-ini mar_izq-2'>";
                foreach( $_est->atr as $atr ){
                  $htm.= _doc::var('val',$atr,[
                    'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                    'val'=>!isset($_est->atr_ocu) || !in_array($atr,$_est->atr_ocu),
                    'ope'=>[ '_tip'=>'opc_bin', 
                      'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 
                      'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr",
                      'onchange'=>"$_eje('tog',this);"
                    ] 
                  ]);
                } $htm.="
              </form>";

              $lis_val[$est_nom][] = $htm;                
            }              
          }

          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">
            <h3 class='dis-ocu'>Columnas</h3>

            "._doc_lis::val($lis_val,[
                'htm_fin'=>[ 'eti'=>"ul", 'class'=>"ope mar_izq-1", 'htm'=>"
                  "._doc::ico('ope_ver',['eti'=>"li", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2", 'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"$_eje('tog',this);"])."
                  "._doc::ico('ope_ocu',['eti'=>"li", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2", 'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"$_eje('tog',this);"])
                ]
            ],'tog')."

          </section>";
          break;
        // descripciones : titulo + detalle
        case 'des':
          $lis_val = [];
          foreach( $ope['est'] as $esq => $est_lis ){
              
            foreach( $est_lis as $est ){
              
              $_est =  _api::est($esq,$est,$ope);

              // ciclos, agrupaciones y lecturas
              if( !empty($_est->tit_cic) || !empty($_est->tit_gru) || !empty($_est->det_des) ){
                
                $lis_val[$est_nom = _dat::est($esq,$est)->nom] = [];
                foreach( ['cic','gru','des'] as $ide ){

                  $pre = $ide == 'des' ? 'det' : 'tit';

                  if( !empty($_est->{"{$pre}_{$ide}"}) ){  

                    $lis_val[$est_nom][ $gru_nom = _api::var('doc','est','ver',$ide)['nom'] ] = [];

                    $htm = "
                    <form ide='{$ide}' data-esq='{$esq}' data-est='{$est}' class='ren jus-ini'>";
                    foreach( $_est->{"{$pre}_{$ide}"} as $atr ){

                      $htm .= _doc::var('val',$atr,[ 
                        'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                        'ope'=>[ '_tip'=>'opc_bin',
                          'id'=>"{$_ide}-{$atr}-{$ide}",
                          'onchange'=>"$_eje('val',this);",
                        ] 
                      ]);
                    }$htm .= "
                    </form>";                  
                    $lis_val[$est_nom][$gru_nom][] = $htm;
                  }
                }
              }
            }
          }
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">
            <h3 class='dis-ocu'>Descripciones</h3>

            "._doc_lis::val($lis_val,[],'tog')."

          </section>";          
          break;
        // cuentas : total + porcentaje
        case 'cue':
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">            
            <h3 class='dis-ocu'>Cuentas</h3>

            "._doc_lis::val( _doc_val::cue('dat',$ope['est'],[
                'ide'=>$_ide,
                'eje'=>"$_eje('act',this);"
              ]), [ 
                'dep'=>[ 'class'=>"dis-ocu" ] 
              ],
              'tog','ver','cue'
            )."
          </section>";
          break;                        
        // estructura : atributos y tipos de dato con filtros
        case 'est':
          foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
          $esq = $var_dat['esq'];
          $est = $var_dat['est'];
          $_cue = [ 
            'opc'=>["Opción",0], 
            'num'=>["Número",0,['ini'=>'','fin'=>'']], 
            'tex'=>["Texto",0], 
            'fec'=>["Fecha",0,['ini'=>'','fin'=>'']], 
            'lis'=>["Lista",0] 
          ];
          foreach( $_est->atr as $atr ){
            $tip_dat = explode('_',_dat::atr($esq,$est,$atr)->ope['_tip'])[0];
            if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
          }
          $_ .= "
          <form class='val jus-ini' dat='atr'>
    
            <fieldset class='ope'>
              "._doc::ico('ope_ocu',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'value'=>"ocu", 'onclick'=>"$_eje('val',this,'tod');"])."
              "._doc::ico('ope_ver',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'value'=>"ver", 'onclick'=>"$_eje('val',this,'tod');"])."
            </fieldset>
    
            "._doc::var('val','ver',[ 'nom'=>"Filtrar", 'htm'=>_doc_val::ver('tex',[ 'eje'=>"$_eje('ver',this);" ]) ])."
    
            <fieldset class='ite'>";
            foreach( $_cue as $atr => $val ){ $_ .= "
              <div class='val'>
                "._doc::ico($atr,['eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'value'=>$atr, 'onclick'=>"$_eje('ver',this);"])."
                <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
              </div>";
            }$_ .= "
            </fieldset>
    
          </form>";
          $pos = 0; $_ .= "
          <table"._htm::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
          foreach( $_est->atr as $atr ){
            $pos++;
            $_atr = _dat::atr($esq,$est,$atr);            
            $_var = [
              'id'=>"{$_ide}-{$atr}",
              'onchange'=>"$_eje('val',this,'dat');"
            ];
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
        }
      }

      return $_;
    }
    // atributos: por columnas
    static function atr( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";
      // proceso estructura de la base
      if( is_string($dat) ){

        extract( _var::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _api::est($esq,$est);
      }
      // por listado
      else{
        if( isset($ope['ide']) ){
          extract( _var::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
            
      switch( $tip ){
      case 'col': 
        // por muchos      
        if( isset($ope['est']) ){

          $ope_est = $ope['est'];
          unset($ope['est']);
  
          foreach( $ope_est as $esq => $est_lis ){
  
            foreach( $est_lis as $est ){
  
              $_ .= _doc_est::atr('col',"{$esq}.{$est}",$ope,$ele,...$opc);
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
        break;
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

        extract( _var::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _api::est($esq,$est);
      }
      // por listado
      elseif( isset($ope['ide']) ){
        extract( _var::ide($ope['ide']) );
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

            if( !empty($ide = _dat::ide($esq,$est,$atr) ) && $pos != $val->$atr ){
              if( !empty($val->$atr) ){
                $ite_ele = $ele;
                if( !isset($ope[$tip]) || !in_array($atr,$ope[$tip]) ) _ele::cla($ite_ele['ite'],'dis-ocu');
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

            if( !empty($ide = _dat::ide($esq,$est,$atr)) ){

              foreach( _dat::var($esq,$ide) as $val ){                
                $ite_ele = $ele;
                if( !isset($ope[$tip]) || !in_array($atr,$ope[$tip]) ) _ele::cla($ite_ele['ite'],'dis-ocu');
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
        extract( _var::ide($dat) );
        $_est = _api::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _var::ide($ope['ide']) );
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
        $est_ima = _dat::ope($esq,$est,'ima');  
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
            }elseif( !empty( $_var = _var::tip( $dat ) ) ){
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
        extract( _var::ide($dat) );
        $_est = _api::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _var::ide($ope['ide']) );
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
          if( !isset($ope[$tip]) || !in_array($atr,$ope[$tip]) ) _ele::cla($ite_ele['ite'],'dis-ocu');
          $_ .= _doc_est::det('pos',$dat,[$atr,$val],$ite_ele,...$opc);
        }
      }

      return $_;
    }
    
  }
  // obejetos del entorno : pos[] + nom[]/{} + atr{}
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
        $tip = _var::tip($dat); 
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
          $_ = _doc_opc::ope('val', NULL, [ 'dat'=>$dat, 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] ); 
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
  // elementos del documento : etiqueta + lista + nodos
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
  // ejecuciones del programa : fun() + eve<= + dis=>
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
  // archivos del directorio
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
  // fechas del calendario
  class _doc_fec {

    static string $IDE = "_doc_fec-";

    static string $EJE = "_doc_fec.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_fec_val = function( $dat, $tip ){
  
        if( $tip=='dia' || $tip=='dyh' ){
  
          if( !empty($dat) ){ $dat = _fec::val($dat,'año'); }
        }
  
        return str_replace(' ','T',str_replace('/','-',$dat));
      };
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      

      switch( $tip ){
      case 'val':
        $ope['value'] = $dat; $_ = "
        <time"._htm::atr($ope).">
          "._doc::let($_fec_val($dat))."
        </time>";
      case 'tie': 
        $ope['value'] = intval($dat);
        $ope['type']='numeric';
        break;
      case 'dyh': 
        $ope['value'] = $_fec_val($dat,$tip);
        $ope['type']='datetime-local';
        break;
      case 'hor':
        $ope['value'] = $_fec_val($dat,$tip);
        $ope['type']='time';
        break;
      case 'dia':
        $ope['value'] = $_fec_val($dat,$tip);
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
        $_ = "<input"._htm::atr($ope).">";
      }      

      return $_;
    }        
  }
  // textos : letra + palabra + párrafo + libro
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

        if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? _dat::cod($dat) : $dat );

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
              $dat_lis = _dat::dec($ope['lis']);
              unset($ope['lis']);          
            }else{
              $dat_lis = [];
            }        
            if( empty($ope['id']) ){ 
              $ope['id']="_tex-{$tip}-"._api::var_ide("_tex-{$tip}-");
            }
            $ope['list'] = "{$ope['id']}-lis";
            $lis_htm = "
            <datalist id='{$ope['list']}'>";
              foreach( $dat_lis as $pos => $ite ){ $lis_htm .= "
                <option data-ide='{$pos}' value='{$ite}'></option>";
              }$lis_htm .="
            </datalist>";
          }
          $_ = "<input"._htm::atr($ope).">".$lis_htm;
        }
        else{
          $_ = "<textarea"._htm::atr($ope).">{$dat}</textarea>";
        }
      }      

      return $_;
    }
  }
  // numeros : codigo + separador + entero + decimal + rango
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
        _ele::eje($ope,'inp',"$_eje"."act(this)",'ini');
        
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
              $ope['id'] = "_num_ran-"._api::var_ide('_num-ran');
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
  // opciones : binario + unico + multiple
  class _doc_opc {

    static string $IDE = "_doc_opc-";

    static string $EJE = "_doc_opc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      // selector   
      case 'val':
        $ope_eti = !empty($ope['eti']) ? _dat::dec($ope['eti'],[],'nom') : [];

        if( isset($ope_eti['opc']) ){
          $opc = array_merge($opc,is_array($ope_eti['opc']) ? $ope_eti['opc'] : explode(',',$ope_eti['opc']) );
        }

        $eti = isset($ope_eti['eti']) ? $ope_eti['eti'] : 'select';
        if( empty($dat) && isset($ope_eti['val']) ){
          $dat = $ope_eti['val'];
          unset($ope_eti['val']);
        }

        $_ = "
        <{$eti}"._htm::atr($ope_eti).">";
          if( in_array('nad',$opc) ){ 
            $_ .= "<option default value=''>{-_-}</option>"; 
          }
          // items
          $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
          $opc_lis = isset($ope['dat']) ? _dat::dec($ope['dat']) : [];
          if( isset($ope['gru']) ){
            foreach( $ope['gru'] as $i => $v ){ 
              if( isset($opc_lis[$i]) && is_string($v) ){ $_.="
                <optgroup data-ide='{$i}' label='{$v}'>
                  "._doc_opc::ite( $opc_lis[$i], $dat, $ope_ite, ...$opc )."
                </optgroup>";
              }
            }
          }
          else{                        
            $_ .= _doc_opc::ite( $opc_lis, $dat, $ope_ite, ...$opc );
          }
          $_ .= "
        </{$eti}>";
        break;
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
      // binarios : true/false
      case 'bin':
        $ope['type']='checkbox';
        if( !empty($dat) ){ $ope['checked']='checked'; }
        break;
      // opcion unica : ""
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
      // opcion múltiple : []
      case 'mul':
        if( !isset($ope['multiple']) ){ $ope['multiple']='1'; }      
        $_ = _doc_opc::ope('val', $dat, $ope, ...$opc);
        break;          
      }

      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";            
      }

      return $_;
    }
    // opciones
    static function ite( mixed $dat = NULL, $val = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);

      foreach( $dat as $i => $v){ 
        $atr=''; 
        $htm=''; 
        $e = $ope;
        // listado de objetos: [ {i:v},{i:v},{i:v} ]
        if( !_obj::pos($v) && !( $obj_tip = _obj::tip($v) ) ){  
          $e['value'] = $i;
          $htm = !!$opc_ide ? "{$i}: ".strval($v) : strval($v) ;
          $atr = _htm::atr($e);
        }
        else{
          if( $obj_tip == 'atr' ){
            $_ide = isset($v->ide) ? $v->ide : FALSE ;
            $_htm = isset($v->nom) ? $v->nom : FALSE ;
            $_des = isset($v->des) ? $v->des : FALSE ;
            $_tit = isset($v->tit) ? $v->tit : FALSE ;
          }
          elseif( $obj_tip == 'nom' ){
            $_ide = isset($v['ide']) ? $v['ide'] : FALSE ;
            $_htm = isset($v['nom']) ? $v['nom'] : FALSE ;
            $_des = isset($v['des']) ? $v['des'] : FALSE ;
            $_tit = isset($v['tit']) ? $v['tit'] : FALSE ;
          }
          else{
            $_ide = FALSE ;
            $_htm = "( \"".implode( '", "', $v )."\" )" ;
            $_des = FALSE ;
            $_tit = FALSE ;
          }
          
          if( isset($e['value']) ){ 
            $e['value'] = _obj::val($v,$e['value']); 
          }else{
            $e['value'] = $i;
            if( $_ide ){ $e['value'] = $_ide; }elseif( $_htm ){ $e['value'] = $_htm; }
          }
  
          if( !isset($e['title']) ){ 
            if( $_des ){ $e['title'] = $_des; }elseif( $_tit ){ $e['title'] = $_tit; }
          }
  
          if( isset($e['htm']) ){            
            $htm = _obj::val($v,$e['htm']);            
            unset($e['htm']);
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
        }
        // agrego atributo si está en la lista
        if( $val_ite ){ 
          if( $val_arr ){
            if( in_array($e['value'],$val) ) 
              $atr.=" selected='1'";
          }elseif( $val == $e['value'] ){
            $atr.=" selected='1'";
          }
        }
        $_ .= "<option{$atr}>{$htm}</option>";
      }   
      return $_;
    }
  }
  // figura : color + imagen + dibujos( pun + lin + pol + geo  )
  class _doc_fig {

    static string $IDE = "_doc_nav-";

    static string $EJE = "_doc_nav.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      switch( $tip ){
      // colores
      case 'col':
        $ope['type']='color';
        $ope['value'] = empty($dat) ? $dat : '#000000';
        break;
      // dibujos        
      case 'pun':
        $ope['type']='text';
        break;
      case 'lin':
        $ope['type']='text';
        break;
      case 'geo':
        $ope['type']='text';
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";
      }      

      return $_;
    }    
    
  }