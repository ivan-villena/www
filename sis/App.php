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
  
  public function __construct( object $Uri ){

    // cargo datos : esquema - cabecera - articulo - valor
    
    if( isset($Uri->esq) && is_object( $dat = Dat::get('sis-app_esq',[ 
      'ver'=>"`ide` = '{$Uri->esq}'", 
      'opc'=>'uni' 
    ]) ) ){

      $this->Esq = $dat;

      // cargo datos del menu
      if( !empty($Uri->cab) && is_object( $dat = Dat::get('sis-app_cab',[ 
        'ver'=>"`esq` = '{$this->Esq->ide}' AND `ide` = '{$Uri->cab}'",
        'ele'=>'ope', 
        'opc'=>'uni'
      ])) ){

        $this->Cab = $dat;
          
        // cargo datos del artículo
        if( !empty($Uri->art) && is_object( $dat = Dat::get('sis-app_art',[ 
          'ver'=>"`esq` = '{$this->Esq->ide}' AND `cab` = '{$this->Cab->ide}' AND `ide` = '{$Uri->art}'", 
          'ele'=>'ope',
          'opc'=>'uni' 
        ])) ){

          $this->Art = $dat;
          
          // busco índice de contenidos
          $this->Nav = Dat::get('sis-app_nav',[ 
            'ver'=>"`esq` = '{$this->Esq->ide}' AND `cab` = '{$this->Cab->ide}' AND `art` = '{$this->Art->ide}'", 
            'ord'=>"`pos` ASC",
            'nav'=>'pos'
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
      foreach( Dat::get('sis-app_art',[ 'ver'=>"`esq` = '{$this->Esq->ide}' AND `cab` = '$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art ){

        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        // por titulo de separacion
        if( empty($_art->ide) ){
          $ite_val = "
          <p class='tex-tit'>".Doc_Val::let($_art->nom)."</p>";
        }
        // por enlace
        else{
          $ele_val['href'] = SYS_NAV."/{$this->Esq->ide}/$_cab->ide/$_art->ide";
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
    Ele::cla($ele['dep'],"dis-ocu");

    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'

    return empty($lis) ? "" : Doc_Val::lis('dep',$lis,$ele);

  }// devuelvo listado del menu por esquema
  public function cab_ver( string $ide = "" ) : array {

    $ver = "`esq` = '{$this->Esq->ide}'";

    if( !empty($ide) ) $ver .= " AND `ide` = '{$ide}'";

    return Dat::get('sis-app_cab',[ 'ver'=>$ver, 'ord'=>"`pos` ASC" ]);
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
        }
        // si no hay seccion principal...
        elseif( empty($_['cab']) ){   

          $_ = "No existe el Artículo '$val'";
        }
      }

    }

    return $_;

  }// devuelvo listado de articulos por menu
  public function art_ver( string $ide = "" ) : array {
    
    $ver = "`esq` = '{$this->Esq->ide}' AND `cab` = '{$this->Cab->ide}'";

    if( !empty($ide) ) $ver .= " AND `ide` = '{$ide}'";

    return Dat::get('sis-app_art',[ 'ver'=>$ver, 'ord'=>"`pos` ASC" ]);

  }

  // imprimo indice
  public function nav( array $ele = [], ...$opc ) : string {

    return Doc_Ope::art_nav($this->Nav, $ele, ...$opc );
  }

  // imprimo palabras clave por articulo
  static function ide( string $esq, string $art, array $ele = [] ) : string {

    $_ = [];
    
    if( is_array( $tex = Dat::get('sis-app_ide',['ver'=>"`esq` = '$esq' AND `art` = '$art'"]) ) ){

      foreach( $tex as $pal ){

        $_[ $pal->nom ] = $pal->des;
      }
    }

    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return Doc_Val::lis('pos',$_,$ele);
    
  }// imprimo listado por conjuntos
  static function ide_lis( string $esq, string $cab, array $var = [] ) : string {

    if( empty($var['opc']) ) $var['opc'] = [ 'tog', 'ver', 'tog-dep' ];

    if( empty($var['ope']) ) $var['ope'] = [ 'class'=>"ope_inf pad-2" ];
    
    $_ = [];    
    // recorro articulos
    foreach( Dat::get("sis-app_art",[ 'ver'=>"`esq` = '{$esq}' AND `cab` = '{$cab}'" ]) as $_art ){
          
      // busco terminos
      if( !empty($_art->ide) && !empty( $_pal_lis = Dat::get("sis-app_ide",[

        'ver'=>"`esq` = '{$esq}' AND `art` LIKE '$_art->ide%'",

        'ord'=>"`art` ASC, `nom` ASC"
        ]) ) 
      ){
        $_pal_ite = [];

        foreach( $_pal_lis as $_pal ){
          $_pal_ite[] = [ 
            'ite'=>$_pal->nom,
            'lis'=>[ Doc_Val::let($_pal->des) ]
          ];
        }
        $_ []= [
          'ite'=>$_art->nom, 
          'lis'=>$_pal_ite
        ];
      }
    }

    // devuelvo lista
    return Doc_Val::lis( "dep", $_, $var );    
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
        
        <?=Doc_Val::ico('eje_val',[
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
    foreach( Dat::get('sis-app_usu',[ 'ver'=>"`esq` = '{$this->Esq->ide}'" ]) as $usu ){
      
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