<?php

// Página-app
class App {

  static string $IDE = "App-";  
  static string $EJE = "\$App.";

  public object $Esq;

  public object $Cab;

  public object $Art;  

  public array $Nav = [];

  public array $Ide = [];

  public array $Usu = [];
  
  public function __construct(){

    // cargo datos : esquema - cabecera - articulo - valor
    $Uri = isset($_SESSION['Uri']) ? $_SESSION['Uri'] : new stdClass;

    if( isset($Uri->esq) && is_object( $dat = Dat::get('sis-app_esq',[ 
      'ver'=>"`ide`='{$Uri->esq}'", 
      'opc'=>'uni' 
    ]) ) ){

      $this->Esq = $dat;

      // cargo datos del menu
      if( !empty($Uri->cab) && is_object( $dat = Dat::get('sis-app_cab',[ 
        'ver'=>"`esq`='{$this->Esq->key}' AND `ide`='{$Uri->cab}'", 
        'ele'=>'ope', 
        'opc'=>'uni'
      ])) ){

        $this->Cab = $dat;
          
        // cargo datos del artículo
        if( !empty($Uri->art) && is_object( $dat = Dat::get('sis-app_art',[ 
          'ver'=>"`esq`='{$this->Esq->key}' AND `cab`='{$this->Cab->key}' AND `ide`='{$Uri->art}'", 
          'ele'=>'ope', 
          'opc'=>'uni' 
        ])) ){

          $this->Art = $dat;
          
          // busco índice de contenidos
          $this->Nav = Dat::get('sis-app_nav',[ 
            'ver'=>"`esq`='{$this->Esq->key}' AND `cab`='{$this->Cab->key}' AND `art`='{$this->Art->key}'", 
            'ord'=>"`key` ASC",
            'nav'=>'key'
          ]);
        }
        elseif( !empty($Uri->art) ){

          $_SESSION['Err'] []= "No existe el artículo '$Uri->art' en la opción '$Uri->cab' de la aplicación '$Uri->esq'";
        }
      }
      elseif( !empty($Uri->cab) ){

        $_SESSION['Err'] []= "No existe la opción del menú '$Uri->cab' en la aplicacion '$Uri->esq'";
      }
    }
    else{

      $_SESSION['Err'] []= "No existe la aplicacion '$Uri->esq'";
    }    

  }

  // Imprimo menú
  public function cab( array $ele = [] ) : string {
    
    global $Usu;
    
    $esq_key = $this->Esq->key;
    $esq_ide = $this->Esq->ide;

    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $lis = [];
    foreach( $this->cab_ver() as $_cab ){

      if( !empty($_cab->opc_ocu) || ( !empty($_cab->opc_usu) && empty($Usu->key) ) ){
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

  }// devuelvo listado del menu por esquema
  public function cab_ver( string $ide = "" ) : array {

    $ver = "`esq`='{$this->Esq->key}'";

    if( !empty($ide) ) $ver .= " AND `ide`='{$ide}'";

    return Dat::get('sis-app_cab',[ 'ver'=>$ver, 'ord'=>"`key` ASC" ]);
  }

  // Valido articulo de la Aplicacion por Peticion url
  public function art() : string | array {

    $_ = [];

    if( !empty($this->Cab->ide) ){


      // cargo seccion principal ( puede ser el generador del articulo )
      if( !empty( $rec = Arc::val_rec("./App/{$this->Esq->ide}/{$this->Cab->ide}") ) ){

        $_['cab'] = $rec; 
      }

      // imprimo articulo : html-php
      if( !empty($this->Art->ide) && is_dir("./App/{$this->Esq->ide}/{$this->Cab->ide}") ){
        
        if( !empty( $rec = Arc::val_rec($val = "./App/{$this->Esq->ide}/{$this->Cab->ide}/{$this->Art->ide}") ) ){

          $_['art'] = $rec;

        }// si no hay seccion principal...
        elseif( empty($_['cab']) ){   

          $_ = "No existe el Artículo '$val'";
        }
      }

    }

    return $_;

  }// devuelvo listado de articulos por menu
  public function art_ver( string $ide = "" ) : array {
    
    $ver = "`esq`='{$this->Esq->key}' AND `cab`='{$this->Cab->key}'";

    if( !empty($ide) ) $ver .= " AND `ide`='{$ide}'";

    return Dat::get('sis-app_art',[ 'ver'=>$ver, 'ord'=>"`key` ASC" ]);

  }

  /* Indice por atributo con enlaces => a[href] > ...a[href] */
  public function nav( array $ele = [], ...$opc ) : string {
    $_ = "";    
    $_eje = self::$EJE."nav";
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // operadores
    Ele::cla( $ele['ope'], "-ren", 'ini' );    
    $_ .= Doc_Ope::lis_ope(self::$EJE."nav",['tog','ver'],$ele);
    
    // armo listado de enlaces    
    $opc_ide = in_array('ide',$opc);
    Ele::cla($ele['lis'], "nav");

    // proceso nivelacion de indices
    $dat = $this->Nav;
    $_lis = [];
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

  // Listado de palabras clave
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

  /* imprimo Consola del Sistema */
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

  // devuelvo accesos del Usuario
  public function usu() : array {
    
    $_ = [];
    
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
        $_ []= [ "\$App.usu('{$usu->ide}',this)", $ite_fig.Doc_Val::let($usu->nom) ];
      }
    }
    // Accesos Globales del Usuario: perfil + sesión
    array_push( $_,
      [ "Usu.ses('dat',this)", Doc_Val::ico('app_usu',     $ele_ico )."Administrar Perfil" ],
      [ "Usu.ses('fin',this)", Doc_Val::ico('ope_nav-fin', $ele_ico )."Cerrar Sesión" ]
    );

    return $_;

  }
}