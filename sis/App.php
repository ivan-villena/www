<?php

// Página-app
class App {

  static string $IDE = "App-";
  static string $EJE = "\$App.";

  /* Pido Contenido */
  static function htm( string $arc ){

    return Eje::htm( "./App/".$arc, "./App/".Tex::let_pal( explode('/',$arc)[0] ) );
  }

  /* Ejecuto método */
  static function eje( string $esq, string $ide, ...$par ){
  }

  /* peticion */
  public object $Uri;
  // - Armo objeto
  public function uri( string $ide ) : object {

    // armo peticion
    $dir = explode('/',$ide);

    $Uri = new stdClass;

    $Uri->esq = $dir[0];
    $Uri->cab = !empty($dir[1]) ? $dir[1] : FALSE;
    $Uri->val = FALSE;

    if( $Uri->art = !empty($dir[2]) ? $dir[2] : FALSE ){

      $val = explode('#',$Uri->art);

      if( isset($val[1]) ){
        $Uri->art = $val[0];
        $Uri->val = $val[1];
      }
      elseif( !empty($dir[3]) ){
        $Uri->val = $dir[3];
      }
    }

    return $this->Uri = $Uri;

  }// - Cargo Datos de la Aplicacion por Peticion url
  public function uri_dat() : void {

    // cargp rutas
    $Uri = $this->Uri;

    // cargo datos : esquema - cabecera - articulo - valor
    $this->Esq = Dat::get('sis-app_esq',[ 'ver'=>"`ide`='{$Uri->esq}'", 'opc'=>'uni' ]);

    if( !empty($this->Esq->key) ){

      $esq_key = $this->Esq->key;
    
      if( !empty($Uri->cab) ){
        
        // cargo datos del menu
        $this->Cab = Dat::get('sis-app_cab',[ 
          'ver'=>"`esq`='{$esq_key}' AND `ide`='{$Uri->cab}'", 
          'ele'=>'ope', 
          'opc'=>'uni'
        ]);

        $cab_key = $this->Cab->key;
        // cargo datos del artículo
        if( !empty($Uri->art) ){
          $this->Art = Dat::get('sis-app_art',[ 
            'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `ide`='{$Uri->art}'", 
            'ele'=>'ope', 
            'opc'=>'uni' 
          ]);

          $art_key = $this->Art->key;

          // busco índice de contenidos
          $this->Nav = Dat::get('sis-app_nav',[ 
            'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `art`='{$art_key}'", 
            'ord'=>"`key` ASC",
            'nav'=>'key'
          ]);
        }
      }
    }
  }// - Cargo Directorios
  public function uri_dir() : object {

    $Uri = $this->Uri;

    $Dir = new stdClass();
    
    $Dir->esq = SYS_NAV."{$Uri->esq}";
      
    $Dir->cab = "{$Uri->esq}/{$Uri->cab}";

    $Dir->ima = SYS_NAV."_img/{$Dir->cab}/";

    if( !empty($Uri->art) ){

      $Dir->art = $Dir->cab."/{$Uri->art}";
    
      $Dir->ima .= "{$Uri->art}/";
    }

    return $Dir;
  }

  /* Esquema : nombre de la aplicacion */
  public object $Esq;

  /* Menu : secciones de la aplicacion */
  public object $Cab;
  // Imprimo listado de accesos
  public function cab( array $ele = [] ) : string {
    
    global $Usu;
    
    $esq_key = $this->Esq->key;
    $esq_ide = $this->Esq->ide;

    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $lis = [];
    foreach( Dat::get('sis-app_cab',[ 'ver'=>"`esq`='$esq_key'", 'ord'=>"`key` ASC" ]) as $_cab ){

      if( !empty($_cab->opc_ocu) || ( !empty($_cab->opc_usu) && empty($Usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? Doc_Val::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $lis_ite = [];
      foreach( Dat::get('sis-app_art',[ 'ver'=>"`esq`='$esq_key' AND `cab`='$_cab->key'", 'ord'=>"`key` ASC" ]) as $_art ){

        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        // por titulo de separacion
        if( empty($_art->ide) ){
          $ite_val = "
          <p class='tex-tit'>".Doc_Val::let($_art->nom)."</p>";
        }
        // por enlace
        else{
          $ele_val['href'] = SYS_NAV."/$esq_ide/$_cab->ide/$_art->ide";
          $ite_val = "
          <p>"
          .( !empty($_art->ico) ? Doc_Val::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          .Doc_Val::let($_art->nom)."
          </p>";
        }       

        $lis_ite []= "
        <a".Ele::atr($ele_val).">
          {$ite_val}
        </a>";
      }

      // evaluo enlace principal
      $ele_ite = [ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 tex-tit tex-enf", 'htm'=>$ite_ico.Doc_Val::let($_cab->nom) ];      
      if( !empty($_cab->opc_url) ){
        $ele_ite['eti'] = "a";
        $ele_ite['href'] = SYS_NAV."/$esq_ide/$_cab->ide";
      }
    
      $lis []= [ 
        'ite'=>$ele_ite,
        'lis'=>$lis_ite 
      ];
    }
    
    // reinicio opciones
    Ele::cla($ele['lis'],"nav");
    Ele::cla($ele['dep'],DIS_OCU);

    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'

    return empty($lis) ? "" : Doc_Ope::lis('dep',$lis,$ele);
  }
  
  /* Articulo : contenidos principales de cada seccion */
  public object $Art;
  // - valido articulo o seccion principal
  public function art() : string | array {

    $Uri = $this->Uri;

    $_ = [];

    if( !empty($Uri->cab) ){


      // cargo seccion principal ( puede ser el generador del articulo )
      if( !empty( $rec = Arc::val_rec("./App/$Uri->esq/$Uri->cab") ) ){

        $_['eje'] = $rec; 
      }

      // imprimo articulo : html-php
      if( is_dir("./App/$Uri->esq/$Uri->cab") && !empty($Uri->art) ){
        
        if( !empty( $rec = Arc::val_rec($val = "./App/$Uri->esq/$Uri->cab/$Uri->art") ) ){

          $_['art'] = $rec;

        }// si no hay seccion principal...
        elseif( empty($_['eje']) ){   

          $_ = Doc_Ope::tex([ 'tip'=>"err", 'tex'=>"No existe el Artículo '$val'" ]);
        }
      }

    }
    
    return $_;

  }// - genero desde objeto de la base
  public function art_htm( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = Ele::htm($nav->ope);

    $_art = Dat::get('sis-app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".Doc_Val::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='ope_val nav'>
                ".Doc_Ope::val_ico()."
                {$art_url}
              </div>
              <div class='dat'>
                ".Ele::val($art->ope['tex'])."
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
  }// - genero secciones por Indice de la base : article > h2 + ...section > h3 + ...section > ...
  public function art_sec( string $ide ) : string {
    $_ = "";
    
    $_ide = explode('.',$ide);
    
    $app_nav = Dat::get('sis-app_nav',[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

    if( isset($app_nav[1]) ){

      foreach( $app_nav[1] as $nv1 => $_nv1 ){ $_ .= "
        <h2 id='_{$nv1}-'>".Doc_Val::let($_nv1->nom)."</h2>
        <article>";
          if( isset($app_nav[2][$nv1]) ){
            foreach( $app_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".Doc_Val::let($_nv2->nom)."</h3>
          <section>";
            if( isset($app_nav[3][$nv1][$nv2]) ){
              foreach( $app_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".Doc_Val::let($_nv3->nom)."</h4>
            <section>";
              if( isset($app_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $app_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".Doc_Val::let($_nv4->nom)."</h5>
              <section>";
                if( isset($app_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $app_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

                <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>".Doc_Val::let($_nv5->nom)."</h6>
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
  
  /* Indice por aplicacion */
  public array $Nav = [];
  /* Indice por atributo con enlaces => a[href] > ...a[href] */
  public function nav( array $ele = [], ...$opc ) : string {
    $_ = "";    
    $_eje = self::$EJE."nav";
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // operadores
    Ele::cla( $ele['ope'], "-ren", 'ini' );    
    $_ .= Doc_Ope::lis_ope(self::$EJE."nav",['tog','ver'],$ele);
    
    // armo listado de enlaces
    $_lis = [];
    $opc_ide = in_array('ide',$opc);
    Ele::cla($ele['lis'], "nav");
    // proceso nivelacion de indices
    $dat = $this->Nav;
    foreach( $dat[1] as $nv1 => $_nv1 ){
      $ide = $opc_ide ? $_nv1->ide : $nv1;
      $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv1->nom}") ];
      if( !isset($dat[2][$nv1]) ){
        $_lis []= Ele::val($eti_1);
      }
      else{
        $_lis_2 = [];
        foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
          $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
          $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv2->nom}") ];
          if( !isset($dat[3][$nv1][$nv2])  ){
            $_lis_2 []= Ele::val($eti_2);
          }
          else{
            $_lis_3 = [];              
            foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
              $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
              $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv3->nom}") ];
              if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                $_lis_3 []= Ele::val($eti_3);
              }
              else{
                $_lis_4 = [];                  
                foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                  $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                  $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv4->nom}") ];
                  if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                    $_lis_4 []= Ele::val($eti_4);
                  }
                  else{
                    $_lis_5 = [];                      
                    foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                      $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                      $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv5->nom}") ];
                      if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                        $_lis_5 []= Ele::val($eti_5);
                      }
                      else{
                        $_lis_6 = [];
                        foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                          $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                          $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv6->nom}") ];
                          if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                            $_lis_6 []= Ele::val($eti_6);
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
    $ele['opc'] = [];
    Ele::cla($ele['dep'],DIS_OCU);
    
    return $_ .= Doc_Ope::lis('dep',$_lis,$ele);
  }

  /* Glosario : palabras por esquema */
  public array $Ide = [];
  // genero listado de palabras clave
  public function ide( int $esq, string $art, array $ele = [] ) : string {

    $_ = [];
    
    if( is_array( $tex = Dat::get('sis-app_ide',['ver'=>"`esq`=$esq AND `art`='$art'"]) ) ){

      foreach( $tex as $pal ){

        $_[ $pal->nom ] = $pal->des;
      }
    }

    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return Doc_Ope::lis('pos',$_,$ele);
  }

  /* Consola del Sistema */
  public function adm(){

    $_eje = self::$EJE."adm";

    $_ope = [
      'aja' => [ 'nom'=>"AJAX",   'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('aja',this);" ] ],
      'ico' => [ 'nom'=>"Íconos", 'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('ico',this);" ] ],
      'php' => [ 'nom'=>"P.H.P.", 'htm'=>"" ]
    ];
  
    // - datos por ajax
    ob_start(); ?>

    <nav class='lis'>
    </nav>
    
    <?php
    $_ope['aja']['htm'] = ob_get_clean();
  
    // - iconos del sistema
    ob_start(); ?>

    <form>
      
      <fieldset class="ope_inf">
        <legend>Listado de Íconos del Sistema</legend>

        <?=Doc_Ope::var('_','ver',[ 'nom'=>"Filtrar",'ope'=>[ 
          'tip'=>"tex_ora", 
          'id'=>"_adm-ico-ver", 
          'oninput'=>"$_eje('ico',this,'ver')"
        ]])?>

      </fieldset>
    </form>

    <ul class='lis ite mar-2'>
    </ul>

    <?php
    $_ope['ico']['htm'] = ob_get_clean();
  
    // - ejecucion en php
    ob_start(); ?>

    <form>

      <fieldset class='ope_inf dir-hor'>
        <legend>Llamada al Sistema</legend>

        <?=Doc_Ope::var('_','htm',[
          'nom'=>"¿Resultado en HTML?",
          'ope'=>[ 'tip'=>"opc_bin", 'val'=>1, 'id'=>"app_adm-php-htm" ]
        ])?>
        
        <?=Doc_Val::ico('dat_ope',[
          'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('php',this)"
        ])?>            

      </fieldset>

    </form>

    <div class='res mar-1'></div>

    <pre class='res-htm'></pre>

    <?php
    $_ope['php']['htm'] = ob_get_clean();
    
    return Doc_Ope::nav('tex', $_ope, [ 'sel'=>"php" ]);

  }
  
  /* Menu del usuario */
  public array $Usu;
  // devuelvo accesos del Usuario
  public function usu() : array {
    
    $_ = [];
    $_eje = self::$EJE."usu";

    // busco opciones del menu para el usuario por aplicacion    
    foreach( Dat::get('sis-app_usu',[ 'ver'=>"`esq`='{$this->Esq->key}'" ]) as $usu ){
      
      $ele_ico = [ 'class'=>"mar_der-1" ];
      $ite_fig = "";

      if( !empty($usu->ima) ){
        $ite_fig = Doc_Val::ima( $usu->ima, $ele_ico );
      }elseif( !empty($usu->ico) ){
        $ite_fig = Doc_Val::ico( $usu->ico, $ele_ico );
      }      

      if( !empty( $usu->opc_url ) ){
        // enlace: esquema/usuario/identificador
        $_ []= [ "url:{$this->Esq->ide}/usuario/{$usu->ide}", $ite_fig.Doc_Val::let($usu->nom) ];
      }// por funcion en index.js
      else{
        $_ []= [ "usuario('{$usu->ide}',this)", $ite_fig.Doc_Val::let($usu->nom) ];
      }
    }
    // Accesos Globales del Usuario: perfil + sesión
    array_push( $_,
      [ "{$_eje}_dat('ver',this)", Doc_Val::ico( 'app_ses',     $ele_ico )."Administrar Perfil" ],
      [ "{$_eje}_ses('fin')",      Doc_Val::ico( 'app_ses-fin', $ele_ico )."Cerrar Sesión" ]
    );

    return $_;

  }// - Imprimo Inicio de Sesión
  public function usu_ses() : string {

    $_eje = self::$EJE."usu_ses";

    $_ = "
    <form class='app_dat' onsubmit='{$_eje}_ini'>

      <fieldset class='ope_var'>
        <input id='app-usu_ses-mai' name='mai' type='email' placeholder='Ingresa tu Email...'>
      </fieldset>

      <fieldset class='ope_var'>
        <input id='app-usu_ses-pas' name='pas' type='password' placeholder='Ingresa tu Password...'>
      </fieldset>

      <fieldset class='ope_var'>
        <label>Mantener Sesión Activa en este Equipo:</label>
        <input id='app-usu_ses-val' name='val' type='checkbox'>
      </fieldset>

      <a href=''>¿Olvidaste la contraseña?</a>

      <fieldset class='ope_bot tex'>
        <button type='submit'>Ingresar</button>
      </fieldset>

    </form>";

    return $_;
  }// - proceso inicio de sesion 
  public function usu_ses_ini( string $mai, string $pas ) : string {

    $_ = "";

    if( isset($_REQUEST['ema']) && isset($_REQUEST['pas']) ){
        
      $Usu = new Usu( $_REQUEST['ema'] );

      if( isset($Usu->pas) ){
        if( $Usu->pas == $_REQUEST['pas'] ){
          $_SESSION['usu'] = $_REQUEST['ide'];
        }
        else{
          $_ = "Password Incorrecto";
        }
      }
      else{
        $_ = "Usuario Inexistente";
      }
    }

    return $_;
  }// - finaliza la sesion
  public function usu_ses_fin() : void {

    // elimino datos de la sesion
    session_destroy();

    // reinicio 
    session_start();

  }// - reiniciar contraseña
  public function usu_ses_pas() : void {
  }
}