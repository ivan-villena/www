<?php
// Página-app
class sis_app {

  /* Recursos */
  public array $rec = [
    // peticion
    'uri' => [
    ],
    // Datos de la aplicacion
    'app' => [
    ],    
    // archivos clase js/php
    'cla' => [
      
      'api'=>[
        'dat', 'arc', 'eje', 'ele', 'est', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol'
      ],
      'sis'=>[
        'dom', 'doc', 'app', 'usu' 
      ],
      'app/sis'=>[]
    ],
    // Estructuras
    'est' => [
      'api'=>[]
    ],
    // Registros
    'dat' => [
      'api'=>[
        'dat'=>[ 'tip' ],
        'fig'=>[ 'ico' ],
        'tex'=>[ 'let' ]
      ]
    ],
    // Ejecucion por aplicacion
    'eje' => ""
    ,
    // Elementos del documento
    'ele' => [
    ],    
    // operadores: botones de la cabecera
    'ope' => [
      'ini'=>[
        'app_ini'=>[ 'ico'=>"app",     'url'=>SYS_NAV,  'nom'=>"Página de Inicio" ],
        'app_cab'=>[ 'ico'=>"app_cab", 'tip'=>"pan",    'nom'=>"Menú Principal" ],
        'app_nav'=>[ 'ico'=>"app_nav", 'tip'=>"pan",    'nom'=>"Índice", 'htm'=>"" ]        
      ],
      'med'=>[
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win",    'nom'=>"Ayuda" ]
      ],
      'fin'=>[
        'ses_ini'=>[ 'ico'=>"app_ini", 'tip'=>"win",    'nom'=>"Iniciar Sesión..."  ],
        'ses_ope'=>[ 'ico'=>"usu",     'tip'=>"win",    'nom'=>"Cuenta de Usuario..." ],
        'sis_adm'=>[ 'ico'=>"eje",     'tip'=>"win",    'nom'=>"Consola del Sistema..." ]        
      ]
    ],
    // Contenido de la Página
    'htm' => [
      // titulo
      'tit'=>"{-_-}",
      // botones
      'ope'=>[ 'ini'=>"", 'med'=>"", 'fin'=>"" ],
      // paneles
      'pan'=>"",
      // main
      'sec'=>"",
      // modales
      'win'=>"",
      // barra lateral
      'bar'=>"",
      // barra inferior
      'pie'=>""
    ]
  ];//- Clases del programa
  public function rec_cla( string $tip = "", array $dat = [] ) : string {
    $_ = "";
    if( empty($dat) ) $dat = $this->rec['cla'];
    // estilos
    if( $tip == 'css' ){

      foreach( $dat as $mod_ide => $mod_lis ){
        // por módulos
        foreach( $mod_lis as $cla_ide ){
          if( file_exists( "./".($rec = "{$mod_ide}/{$cla_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
        }
        // por página
        if( file_exists( "./".($rec = "{$mod_ide}/index.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
      }
    }// prorama : clases 
    elseif( $tip == 'jso' ){
    
      foreach( $dat as $mod_ide => $mod_lis ){
        // por página
        if( file_exists( "./".($rec = "{$mod_ide}/index.js") ) ) $_ .= "
          <script src='".SYS_NAV."$rec'></script>";        
        // por modulos        
        foreach( $mod_lis as $cla_ide ){ 
          
          if( file_exists( "./".($rec = "{$mod_ide}/{$cla_ide}.js") ) ) $_ .= "
            <script src='".SYS_NAV."$rec'></script>";
        }
      }
    }
    return $_;
  }// - Proceso Petición
  public function rec_uri( string $uri ) : object {

    // armo peticion
    $uri = explode('/',$uri);

    $this->rec['uri'] = new stdClass;
    $this->rec['uri']->esq = !empty($uri[0]) ? $uri[0] : "hol";
    $this->rec['uri']->cab = !empty($uri[1]) ? $uri[1] : FALSE;
    $this->rec['uri']->val = FALSE;

    if( $this->rec['uri']->art = !empty($uri[2]) ? $uri[2] : FALSE ){
      $_val = explode('#',$this->rec['uri']->art);
      if( isset($_val[1]) ){
        $this->rec['uri']->art = $_val[0];
        $this->rec['uri']->val = $_val[1];
      }
      elseif( !empty($uri[3]) ){
        $this->rec['uri']->val = $uri[3];
      }
    }

    $_uri = $this->rec['uri'];

    // ajusto enlace de inicio
    $this->rec['ope']['ini']['app_ini']['url'] .= $_uri->esq;

    // cargo menú principal
    $this->rec['ope']['ini']['app_cab']['htm'] = $this->cab();

    // cargo elemento principal
    $this->rec['ele']['body'] = [
      'data-doc'=>$_uri->esq, 
      'data-cab'=>!!$_uri->cab ? $_uri->cab : NULL, 
      'data-art'=>!!$_uri->art ? $_uri->art : NULL 
    ];
    
    // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
    $this->rec['app']['esq'] = api_dat::get('app_esq',[ 'ver'=>"`ide`='{$_uri->esq}'", 'opc'=>'uni' ]);
    if( !empty($_uri->cab) ){
      
      // cargo datos del menu
      $this->rec['app']['cab'] = api_dat::get('app_cab',[ 'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 
        'ele'=>'ope', 'opc'=>'uni'
      ]);

      // cargo datos del artículo
      if( !empty($_uri->art) ){
        $this->rec['app']['art'] = api_dat::get('app_art',[ 'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);

        // cargo índice de contenidos
        if( !empty($this->rec['app']['cab']->nav) ){

          $this->rec['app']['nav'] = api_dat::get('app_nav',[ 'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$this->rec['uri']->cab}' AND `ide`='{$this->rec['uri']->art}'", 
            'ord'=>"pos ASC", 
            'nav'=>'pos'
          ]);

          // pido listado por navegacion
          if( !empty($this->rec['app']['nav'][1]) ) $this->rec['ope']['ini']['app_nav']['htm'] = api_lis::nav($this->rec['app']['nav']);
        }          
      }
    }

    return $_uri;

  }// - Armo directorios
  public function rec_dir( object $uri = NULL ) : object {

    if( !isset($uri) ) $uri = $this->rec['uri'];

    $_ = new stdClass();
    
    $_->esq = SYS_NAV."{$uri->esq}";
      
    $_->cab = "{$uri->esq}/{$uri->cab}";

    $_->ima = SYS_NAV."img/{$_->cab}/";

    if( !empty($uri->art) ){

      $_->art = $_->cab."/{$uri->art}";
    
      $_->ima .= "{$uri->art}/";
    }

    return $_;
  }// - Inicializo Pagina
  public function rec_htm() : array {

    global $sis_usu;

    /* 
    // loggin
    $eje = "ses_".( empty($sis_usu->ide) ? 'ini' : 'ope' );
    $this->rec['ope']['fin'][$eje]['htm'] = $sis_usu->$eje();
    */

    // consola del sistema
    if( $sis_usu->ide == 1 ){
      $this->rec['cla']['app/sis'] []= "adm";
      ob_start();
      include("./app/sis/adm.php");
      $this->rec['ope']['fin']['sis_adm']['htm'] = ob_get_clean();
    }

    // cargo operadores del documento ( botones + contenidos )
    foreach( $this->rec['ope'] as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->rec['htm']['ope'][$tip] .= api_fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->rec['htm']['ope'][$tip] .= sis_doc::bot([ $ide => $ope ]);
          // contenido
          $this->rec['htm'][$ope['tip']] .= sis_doc::{$ope['tip']}($ide,$ope);
        }
      }  
    }

    // modal de operadores
    $this->rec['htm']['win'] .= sis_doc::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){ 
      if( !empty($this->rec['htm'][$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->rec['ele']['body']['data-ver'] = implode(',',$_ver);

    // titulo
    if( !empty($this->rec['app']['art']->nom) ){
      $this->rec['htm']['tit'] = $this->rec['app']['art']->nom;
    }
    elseif( !empty($this->rec['app']['cab']->nom) ){
      $this->rec['htm']['tit'] = $this->rec['app']['cab']->nom;
    }
    elseif( !empty($this->rec['app']['esq']->nom) ){
      $this->rec['htm']['tit'] = $this->rec['app']['esq']->nom; 
    }

    return $this->rec['htm'];
  }

  /* Estructuras */
  public array $dat = [];
  // Cargo Estructura
  static function dat_est( string $esq, string $ide, mixed $ope = NULL, mixed $dat = NULL ) : mixed {
    $_ = [];
    global $sis_app;
    // cargo una estructura
    if( !isset($ope) ){

      if( !isset($sis_app->dat[$esq][$ide]) ){        
        
        // Cargo Estructura
        $sis_app->dat[$esq][$ide] = sis_sql::est('ver',$sql_est = "{$esq}_{$ide}",'uni');
        if( empty($sis_app->dat[$esq][$ide]) ){
          $sis_app->dat[$esq][$ide] = new stdClass;
        }// de la Base
        else{
          $sql_vis = "_{$sql_est}";
        }
        // ...Propiedades extendidas
        $_est = api_dat::get('app_dat',[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'ele'=>["ope"], 'opc'=>"uni" ]);
        // si existe la estructura
        if( isset($_est->ope) ){
          // Propiedades
          foreach( $_est->ope as $ope_ide => $ope ){
            $sis_app->dat[$esq][$ide]->$ope_ide = $ope;
          }
        }
        // Estructura de la base
        if( isset($sql_vis) ){
          // Atributos/columnas: de una vista ( si existe ) o de la tabla
          $sis_app->dat[$esq][$ide]->atr = sis_sql::atr( !empty( sis_sql::est('lis',"_{$sql_est}",'uni') )  ? "_{$sql_est}" : $sql_est );
          if( isset($_est->ope['atr']) ){
            // cargo variables del operador
            foreach( $_est->ope['atr'] as $atr_ide => $atr_var ){
              $sis_app->dat[$esq][$ide]->atr[$atr_ide]->var = api_ele::val_jun(
                $sis_app->dat[$esq][$ide]->atr[$atr_ide]->var, $atr_var
              );
            }
          }
          // Datos/registros: de una vista ( si existe ) o de la tabla
          $est_ope = isset($sis_app->dat[$esq][$ide]->dat) ? $sis_app->dat[$esq][$ide]->dat : [];
          $sis_app->dat[$esq][$ide]->dat = api_dat::get( sis_sql::est('val',$sql_vis) == 'vis' ? $sql_vis : $sql_est, $est_ope );          
        }
      }
      // devuelvo estructura completa: esq + ide + nom + atr + dat + ...ope
      $_ = $sis_app->dat[$esq][$ide];
    }
    // cargo operadores
    elseif( is_string($ope) ){
      // cargo propiedad
      $ope_atr = explode('.',$ope);
      $_ = api_obj::val_dat(sis_app::dat_est($esq,$ide),$ope_atr);
      // proceso datos
      if( !!$_ && isset($dat) ){
        switch( $ope_atr[0] ){
        // devuelvo atributo/s
        case 'atr':
          $atr_lis = $_;
          // devuelvo 1
          if( is_string($dat) ){
            $_ = new stdClass;
            if( isset($atr_lis[$dat]) ) $_ = $atr_lis[$dat];
          }// o muchos
          else{
            $_ = [];
            foreach( $dat as $atr ){
              if( isset($atr_lis[$atr]) ) $_[$atr] = $atr_lis[$atr];
            }
          }
          break;
        // devuelvo valores
        case 'val':
          $_ = api_obj::val( api_dat::get($esq,$ide,$dat), $_ );
          break;
        }        
      }      
    }
    return $_;
  }// Identificadores
  static function dat_ide( $dat, array $ope=[] ) : array {

    if( is_string($dat) ) $dat = explode('.',$dat);

    if( !isset($dat[1]) ){
      $dat[1] = $dat[0];
      $dat[0] = DAT_ESQ;
    }

    $ope['esq'] = !empty($dat[0]) ? $dat[0] : DAT_ESQ;

    $ope['est'] = $dat[1];
    
    $ope['atr'] = isset($dat[2]) ? $dat[2] : FALSE;

    return $ope;
  }// Atributos desde listado o desde la base
  static function dat_atr( string | array $dat, string $ope = "" ) : array {
    $_ = [];
    if( empty($ope) ){
      // de la base
      if( is_string($dat) ){
        $ide = sis_app::dat_ide($dat);
        $_ = sis_app::dat_est($ide['esq'],$ide['est'],'atr');
      }
      // listado variable por objeto
      else{
        foreach( $dat as $ite ){
          // del 1° objeto: cargo atributos
          foreach( $ite as $ide => $val ){ 
            $atr = new stdClass;
            $atr->ide = $ide;
            $atr->nom = $ide;
            $atr->var = api_dat::tip($val);
            $_ [$ide] = $atr;
          }
          break;
        }        
      }
    }
    return $_;
  }// Relaciones : esq.est_atr | api.dat_atr[ide].dat
  static function dat_rel( string $esq, string $est, string $atr ) : string {
    $_ = '';
    // armo identificador por nombre de estructura + atributo
    if( $atr == 'ide' ){
      $_ = $est;
    }
    // parametrizado en : $sis_app.est_atr
    elseif( ( $_atr = sis_app::dat_est($esq,$est,'atr',$atr) ) && !empty($_atr->var['dat']) ){        
      $_ide = explode('_',$_atr->var['dat']);
      array_shift($_ide);
      $_ = implode('_',$_ide);
    }
    // valido existencia de tabla relacional : "_api.esq_est_atr"
    elseif( !!sis_sql::est('val',"{$esq}_{$est}_{$atr}") ){ 
      $_ = "{$est}_{$atr}";
    }
    else{
      $_ = $atr;
    }
    return $_;
  }
  
  /* Variables */  
  public array $_var = [];
  static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
    
    $_ = [];
    global $sis_app;
    
    // cargo todas las estructuras del esquema
    if( empty($dat) ){
      if( !isset($sis_app->_var[$esq]) ){
        $sis_app->_var[$esq] = api_dat::get('app_var',[
          'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo por agrupacion
    elseif( empty($val) ){
      if( !isset($sis_app->_var[$esq][$dat]) ){
        $sis_app->_var[$esq][$dat] = api_dat::get('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo uno
    else{
      if( !isset($sis_app->_var[$esq][$dat][$val]) ){
        $sis_app->_var[$esq][$dat][$val] = api_dat::get('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }

    if( !empty($ide) ){
      $_ = isset($sis_app->_var[$esq][$dat][$val][$ide]) ? $sis_app->_var[$esq][$dat][$val][$ide] : [];
    }
    elseif( !empty($val) ){
      $_ = isset($sis_app->_var[$esq][$dat][$val]) ? $sis_app->_var[$esq][$dat][$val] : [];
    }
    elseif( !empty($dat) ){
      $_ = isset($sis_app->_var[$esq][$dat]) ? $sis_app->_var[$esq][$dat] : [];
    }
    else{
      $_ = isset($sis_app->_var[$esq]) ? $sis_app->_var[$esq] : [];
    }

    return $_;
  }// id secuencial
  public array $_var_ide = [];
  static function var_ide( string $ope ) : string {
    global $sis_app;

    if( !isset($sis_app->_var_ide[$ope]) ) $sis_app->_var_ide[$ope] = 0;

    $sis_app->_var_ide[$ope]++;

    return $sis_app->_var_ide[$ope];
  }

  /* Pagina */
  // Menu : titulo + descripcion + listado > item = [icono] + enlace
  public function cab( array $ele = [] ) : string {
    
    global $sis_usu;
    
    $esq = $this->rec['uri']->esq;
    
    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( api_dat::get('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($sis_usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? api_fig::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( api_dat::get('app_art',[ 
        'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art 
      ){
        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        $ele_val['href'] = SYS_NAV."/$_art->esq/$_art->cab/$_art->ide";

        $_lis_val []= "
        <a".api_ele::atr($ele_val).">
          <p>"
          .( !empty($_art->ico) ? api_fig::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          .api_tex::let($_art->nom)."
          </p>
        </a>";
      }
      $_lis []= [ 
        'ite'=>[ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 tex-tit tex-enf", 'htm'=>$ite_ico.api_tex::let($_cab->nom) ],
        'lis'=>$_lis_val 
      ];
    }
    // reinicio opciones
    api_ele::cla($ele['lis'],"nav");
    api_ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
    return api_lis::dep($_lis,$ele);
  }
  // Articulo : + ...secciones + pie de página
  public function art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = api_ele::htm($nav->ope);

    $_art = api_dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

    $_ = "
    <article class='app_art'>";
      // introduccion
      if( !empty($agr['htm_ini']) ){
        $_ .= $agr['htm_ini'];
      }
      else{ $_ .= "
        <h2>{$nav->nom}</h2>";
      }
      // listado de contenidos
      if( !empty($_art) ){ $_ .= "

        <nav class='lis'>";
          foreach( $_art as $art ){
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".api_tex::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='doc_val nav'>
                ".sis_doc::val_ico()."
                {$art_url}
              </div>
              <div class='doc_dat'>
                ".api_ele::val($art->ope['tex'])."
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
  // - Articulos : article > h2 + ...section > h3 + ...section > ...
  public function art_nav( string $ide ) : string {
    $_ = "";
    
    $_ide = explode('.',$ide);
    
    $app_nav = api_dat::get('app_nav',[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

    if( isset($app_nav[1]) ){

      foreach( $app_nav[1] as $nv1 => $_nv1 ){ $_ .= "        
        <h2 id='_{$nv1}-'>".api_tex::let($_nv1->nom)."</h2>
        <article>";
          if( isset($app_nav[2][$nv1]) ){
            foreach( $app_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".api_tex::let($_nv2->nom)."</h3>
          <section>";
            if( isset($app_nav[3][$nv1][$nv2]) ){
              foreach( $app_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".api_tex::let($_nv3->nom)."</h4>
            <section>";
              if( isset($app_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $app_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".api_tex::let($_nv4->nom)."</h5>
              <section>";
                if( isset($app_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $app_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

                <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>".api_tex::let($_nv5->nom)."</h6>
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
        </article>";
      }
    }

    return $_;
  }
  
}