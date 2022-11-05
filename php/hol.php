<?php
foreach([ "bib", "art", "ope", "usu"  ] as $cla_ide ){ 
  if( file_exists($rec = "hol/$cla_ide.php") ) 
    require_once($rec);
}

// Sincronario
class _hol_app {

  public array $_cab = [ "bib", "art", "ope", "usu"  ];
  public array $_val = [];
  public array $_dat = [];
  public array $_ide = [];

  // main : app.cab.art/val
  public function __construct( _app $_app ){
    // inicializo datos    
    $_uri = $_app->uri();

    // Diario : Fecha del Sistema => valor por peticion : hol/$cab/$art/"ide=val"      
    $this->_val = _hol::val( !empty($_SESSION['hol-val']) ? _hol::cod($_SESSION['hol-val']) : date('Y/m/d') );
    // - cargo identificadores
    $this->_ide = !empty($_uri->art) ? explode('_',$_uri->art) : [ "kin" ];
    // - proceso fecha e imrpimo panel
    $this->dia($_app);

    // inicio : 
    if( empty($_uri->cab) ){

      $this->ini($_app);
    }
    // por seccion : introduccion
    elseif( empty($_uri->art) ){
      
      $this->ini($_app);
    }
    // por articulo : bibliografía + informes + tableros
    else{
      // inicializo operador
      if( $_uri->cab == 'ope' ){
        // genero datos
        $this->_dat = _hol::dat($this->_ide[0], $this->_val);
        // cargo todos los datos utilizados por esquema
        $_app->rec['dat']['hol'] = [];
      }
      // imprimo contenido
      ob_start();
      $this->{$_uri->cab}($_app);
      $_app->htm['sec'] = ob_get_clean();
    }
    // recursos del documento
    $_app->rec['jso'][$_uri->esq] = $this->_cab;
    $_app->rec['css'] []= $_uri->esq;
    $_app->rec['eje'] .= "
      var \$_hol_app = new _hol_app({ val : "._obj::cod($this->_val)." });
    ";
  }
  // Inicio
  public function ini( _app $_app ) : void {
    $_hol = $this->_val;
    // cargo articulo
    ob_start(); ?>

    <?=_app::tex('adv', "Este sitio aún se está en construcción, puede haber contenido incompleto, errores o fallas.")?>

    <img src="../../img/hol/fon/sis.png" alt="Entrada" style="max-width:45rem;" 
      title="Entrada al Sincronario... Accede al menú en la barra superior, o busca ayuda con el ícono que tiene el signo de pregunta ( ? )"
    >
    
    <?php
    $_app->htm['sec'] = ob_get_clean();

    // cargo tutorial:
    ob_start(); ?>
      <!-- Diario -->
      <section>
        <h3>Fecha del Sistema</h3>
        <?= _app::ico('fec_val',['class'=>"mar_hor-1"]) ?>

        <p>Desde este panel podrás buscar fechas en el Calendario Gregoriano o en El sincronario<c>.</c></p>

        <p>La página se inicia con la fecha corresponde al día actual<c>,</c> pero puedes cambiarla en cualquier momento<c>.</c></p>

        <p>Según la fecha seleccionada podrás ver los datos correspondiente a<c>:</c></p>

        <ul>
          <li>El <b class="ide">Orden Sincrónico</b> de <n>260</n> días</li>
          <li>El <b class="ide">Oredn Cíclico</b> de <n>365</n> días</li>            
        </ul>

        <p>La información se presenta en forma listado con fichas<c>,</c> que contienen los datos propios de la cuenta que representa<c>,</c> y sus valores posicionales correspondientes a su ciclo de tiempo<c>.</c></p>

      </section>      
      <!-- Bibliografía -->
      <section>

        <h3>La bibliografía</h3>

        <div class="val jus-cen">
          <?= _app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= _app::ico('tex_lib',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>Aquí podrás encontrar la mayoría de los libros en los cuales se basa la teoría del Sincronario<c>:</c> <q>La ley del Tiempo</q><c>.</c> Esta fué desarrollada por <a href="https://es.wikipedia.org/wiki/Jos%C3%A9_Arg%C3%BCelles" target="_blank">José Argüelles</a> quien organizó una fundación con el mismo nombre <c>(</c><a href="http://www.lawoftime.org" target="_blank">The Law of Time</a><c>)</c><c>.</c> Todos sus libros y materiales se pueden descargar gratuitamente desde <a href="https://13lunas.net/mapa.htm#biblioteca" target="_blank">La Biblioteca de <n>13</n> Lunas</a><c>.</c></p>

        <p>En este sitio se adaptó el formato de texto de cada libro para una página web<c>,</c> se agregaron los íconos correspondientes a cada símbolo e imágenes que amplían su contenido visualamente<c>.</c> También se muestran en orden cronológico<c>,</c> ya que este conocimiento es incremental y los temas se entrelazan y completan en cada publicación<c>.</c></p>

        <p>En la página de cada libro hay un índice en el panel izquierdo<c>,</c> que puedes ocultar o mostrar haciendo click en el botón Correspondiente<c>.</c> Los items del índice que figuran en el libro son los mismos<c>,</c> pero se agregaron nuevos para segmentar la información y poder acceder desde enlaces<c>.</c></p>

      </section>
      <!-- Artículos -->
      <section>          
        <h3>Los Artículos</h3>

        <div class="val jus-cen">
          <?= _app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= _app::ico('tex_inf',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>En esta sección se publican Artículos con información relacionada a los temas del Sincronario<c>.</c></p>

        <p>El <a href="<?=SYS_NAV."hol/art/ide"?>" target="_blank">glosario</a> es un rejunte de todos los que aparecen en los distintos libros<c>,</c> al cual se agregó un filtro para buscar entre sus términos y accesos al libro donde se encuentra<c>.</c></p>

        <p>El <a href="<?=SYS_NAV."hol/art/tut"?>" target="_blank">Tutorial de <n>13</n> Lunas</a> es una introducción a los códigos y cuentas del Sincronario para su aplicación Diaria<c>.</c></p>

        <p>A medida que este sitio crezca se irán agregando nuevos artículos<c>.</c></p>

      </section>
      <!-- Códigos -->
      <section>
        <h3>Códigos y Cuentas</h3>

        <div class="val jus-cen">
          <?= _app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= _app::ico('num_cod',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>En esta sección podrás encontrar datos que van apareciendo en los distintos libros y están relacionados a cada código y cuenta<c>,</c> junto con sus respectivas agrupaciones y subciclos<c>.</c></p>

        <p>Haciendo referencia a la fuente<c>,</c> se describen brevemente para introducir al lector en sus conceptos y bridarle acceso directo al material donde puede encontrarlo<c>.</c> A partir de su comprensión se pueden realizar lecturas y relaciones entre distintas posiciones<c>,</c> fechas o firmas galácticas<c>.</c></p>

      </section>
      <!-- Módulos -->
      <section>
        <h3>Los Módulos</h3>

        <div class="val jus-cen">
          <?= _app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= _app::ico('tab',['class'=>"mar_hor-1"]) ?>
        </div>
        
        <p>Desde el menú principal puedes acceder a un listado de tableros que representan las cuentas principales del sincronario<c>,</c> a estos los llamaremos módulos<c>.</c></p>

        <p>Para cada módulo se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por Ej<c>:</c> el <a href="<?=SYS_NAV."hol/tab/kin-tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

        <p>Desde allí podrás cambiar la fecha y acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos de las posiciones<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones seleccionadas para comparar sus características y ubicaciones<c>.</c></p>
        
      </section>
    <?php
    $_app->htm['dat'] = ob_get_clean();
  }
  // Diario
  public function dia( _app $_app ) : void {    
    $_uri = $_app->uri();
    if( !empty($_uri->val) ){
      
      if( isset($_uri->val[1]) ){

        if( $_uri->val[0] == 'fec' || $_uri->val[0] == 'sin' ){
          $this->_val = _hol::val($_uri->val[1],$_uri->val[0]);
          // actualizo fecha del sistema
          $_SESSION['hol-val'] = $_uri->val[1];
        }// tomo 
        else{
          $this->_val[ $_uri->val[0] ] = $_uri->val[1];
        }
      }
    }
    // panel con operadores
    $ope = _obj::nom(_app_hol::$DIA,'ver',$ope_atr = ["kin","psi"]);
    foreach( $ope_atr as $ope_ide ){
      $ope[$ope_ide]['htm'] = _app_hol::dia($ope_ide,$this->_val[$ope_ide],[ 'opc'=>['tog','ver'] ]);
    }
    $_app->ope['dia'] = [ 'ico'=>"fec_val", 'tip'=>"pan", 'nom'=>"Diario", 'htm'=>"

      <section class='mar_aba-1'>

        "._app_hol::val($this->_val,[ 'eje'=>"_hol_app.dia" ])."

        <div class='mar-1'>
          "._app_dat::inf('hol','kin',$this->_val['kin'],['opc'=>"nom",'cit'=>"des"])."
        </div>

      </section>

      "._app::nav('bar',$ope,[ 'sel' => "kin" ])
    ];
  }
  // Bibliografía
  public function bib( _app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_dir = $_app->dir();
    $_bib = SYS_NAV."hol/bib/";
    // busco archivos : html-php
    if( !empty( $rec = _arc::ide($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){
      include( $rec );
    }
    else{
      echo _app::tex('err',"No existe el archivo '$val'");
    }
  }
  // Artículos
  public function art( _app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_dir = $_app->dir();
    $_bib = SYS_NAV."hol/bib/";
    // busco archivos : html-php
    if( !empty( $rec = _arc::ide($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){
      include( $rec );
    }
    else{
      echo _app::tex('err',"No existe el archivo '$val'");
    }
  }    
  // Cuentas
  public function dat( _app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_bib = SYS_NAV."hol/bib/";
    $_art = SYS_NAV."hol/art/";
    switch( $_uri->art ){
    case 'rad': ?>
      <!-- dìas de la semana -->
      <article>          
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>

        <p>En <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">El encantamiento del Sueño</a> se divide al año en <n>13</n> lunas de <n>28</n> días cada una<c>.</c> Cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>

        <p>Posteriormente<c>,</c> en el libro de <a href="<?=$_bib?>lun#_02-07-" target="_blank">Las <n>13</n> lunas en movimiento</a>, se mencionan los plasmas para nombrar a cada uno de los días de la semana<c>-</c>heptada<c>.</c></p>

        <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','pod'] ])?>

      </article>
      <!-- sellos de la profecia -->
      <article>          
        <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_app::let($_nav[1]['02']->nom)?></h2>

        <p>En <a href="<?=$_bib?>tel#_02-06-" target="_blank">El telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

        <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario<c>:</c> familia portal y familia señal <c>(</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

        <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ])?>

        <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

        <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

        <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ])?>

      </article>
      <!-- heptágono de la mente -->
      <article>
        <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_app::let($_nav[1]['03']->nom)?></h2>

        <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

        <p>En el <a href="<?=$_bib?>rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>            

        <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','pla_cub','pla_cub_pos'] ])?>

      </article>
      <!-- autodeclaraciones diarias -->
      <article>
        <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_app::let($_nav[1]['04']->nom)?></h2>

        <p>En <a href="<?=$_bib?>ato#_03-06-" target="_blank">Átomo del Tiempo</a> se describen las afirmaciones correspondientes a las Autodeclaraciones Diarias de Padmasambhava para cada plasma<c>.</c></p>

        <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','pla_lec'] ])?>

      </article>
      <!-- componenetes electrónicos -->
      <article>
        <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_app::let($_nav[1]['05']->nom)?></h2>

        <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

        <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_fue','pla_fue_pre','pla_fue_pos'] ])?>

      </article>      
      <?php
      break;
    case 'ton': ?>
      <!-- rayos de pulsación -->
      <article>
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>
        <p>En <cite>el Factor Maya</cite> se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c></p>
        <p>Luego<c>,</c> los <n>13</n> tonos se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c></p>
        <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','gal'] ])?>
      </article>
      <!-- simetría especular -->
      <article>
        <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_app::let($_nav[1]['02']->nom)?></h2>
        <p>En el <cite>Factor Maya</cite> se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c></p>
        <p>Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a><c>.</c></p>
        <?=_app_est::lis('hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>          
      </article>        
      <!-- principios de la creacion -->
      <article>
        <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_app::let($_nav[1]['03']->nom)?></h2>
        <p>En <cite>el Encantamiento del sueño</cite> se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c></p>
        <p>De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c></p>
        <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','nom','des','acc'] ])?>
      </article>
      <!-- O.E. de la Aventura -->
      <article>
        <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_app::let($_nav[1]['04']->nom)?></h2>
        <p>En el <cite>Encantamiento del sueño</cite> se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c></p>
        <p>Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c></p>
        <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc']])?>
      </article>        
      <!-- pulsar dimensional -->
      <article>
        <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_app::let($_nav[1]['05']->nom)?></h2>
        <p>En <cite>el Encantamiento del sueño</cite> <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a> <c>.</c></p>
        <?=_app_est::lis('hol.ton_dim')?>
      </article>
      <!-- pulsar matiz -->
      <article>
        <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=_app::let($_nav[1]['06']->nom)?></h2>
        <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.ton_mat')?>
      </article>          
      <?php
      break;
    case 'sel': ?>
      <!-- signos direccionales -->
      <article>
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_cic_dir')?>
      </article>
      <!-- 3 etapas del desarrollo -->
      <article>
        <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_app::let($_nav[1]['02']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=_app_est::lis('hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ])?>
      </article>
      <!-- 4 etapas evolutivas -->
      <article>
        <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_app::let($_nav[1]['03']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=_app_est::lis('hol.kin_cro_est',[ 'atr'=>['sel','nom','des','det','lec'] ])?>
      </article>
      <!-- 5 familias ciclicas -->
      <article>
        <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_app::let($_nav[1]['04']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=_app_est::lis('hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ])?>
      </article>        
      <!-- Colocacion cromática -->
      <article>
        <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_app::let($_nav[1]['05']->nom)?></h2>          
        <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>20</n> o <n>00</n> a <n>19</n><c>.</c></p>          
        <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>
      </article>
      <!-- 5 familias terrestres -->        
      <article>
        <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=_app::let($_nav[1]['06']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ])?>
      </article>
      <!-- 4 clanes cromáticos -->
      <article>
        <h2 id="<?="_{$_nav[1]['07']->pos}-"?>"><?=_app::let($_nav[1]['07']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ])?>
      </article>        
      <!-- Colocación armónica -->
      <article>
        <h2 id="<?="_{$_nav[1]['08']->pos}-"?>"><?=_app::let($_nav[1]['08']->nom)?></h2>
        <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>1</n> a <n>20</n><c>.</c></p>
        <?=_app_est::lis('hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>
      </article>
      <!-- 4 Razas raiz cósmicas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['09']->pos}-"?>"><?=_app::let($_nav[1]['09']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ])?>
      </article>
      <!-- células del tiempo-->
      <article>
        <h2 id="<?="_{$_nav[1]['10']->pos}-"?>"><?=_app::let($_nav[1]['10']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ])?>
      </article>
      <!-- parejas del oráculo -->
      <article>
        <h2 id="<?="_{$_nav[1]['11']->pos}-"?>"><?=_app::let($_nav[1]['11']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>
      </article>                  
      <!-- parejas analogas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['12']->pos}-"?>"><?=_app::let($_nav[1]['12']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_par_ana')?>
      </article>
      <!-- parejas antípodas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['13']->pos}-"?>"><?=_app::let($_nav[1]['13']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_par_ant')?>
      </article>
      <!-- parejas ocultas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['14']->pos}-"?>"><?=_app::let($_nav[1]['14']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_par_ocu')?>
      </article>        
      <!-- holon Solar -->
      <article>
        <h2 id="<?="_{$_nav[1]['15']->pos}-"?>"><?=_app::let($_nav[1]['15']->nom)?></h2>
        <p>El código 0-19</p>              
        <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] ])?>
      </article>
      <!-- orbitas planetarias -->        
      <article>
        <h2 id="<?="_{$_nav[1]['16']->pos}-"?>"><?=_app::let($_nav[1]['16']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>
        <?=_app_est::lis('hol.uni_sol_pla')?>
      </article>
      <!-- células solares -->        
      <article>
        <h2 id="<?="_{$_nav[1]['17']->pos}-"?>"><?=_app::let($_nav[1]['17']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.uni_sol_cel')?>
      </article>
      <!-- circuitos de telepatía -->        
      <article>
        <h2 id="<?="_{$_nav[1]['18']->pos}-"?>"><?=_app::let($_nav[1]['18']->nom)?></h2>
        <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>
        <?=_app_est::lis('hol.uni_sol_cir')?>
      </article>             
      <!-- holon planetario -->
      <article>
        <h2 id="<?="_{$_nav[1]['19']->pos}-"?>"><?=_app::let($_nav[1]['19']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>
      </article>
      <!-- campos dimensionales -->        
      <article>
        <h2 id="<?="_{$_nav[1]['20']->pos}-"?>"><?=_app::let($_nav[1]['20']->nom)?></h2>
        <p></p>
      </article>          
      <!-- centros galácticos -->        
      <article>
        <h2 id="<?="_{$_nav[1]['21']->pos}-"?>"><?=_app::let($_nav[1]['21']->nom)?></h2>
        <p></p>
        <?=_app_est::lis('hol.uni_pla_cen')?>
      </article>
      <!-- flujos de la fuerza-g -->        
      <article>
        <h2 id="<?="_{$_nav[1]['22']->pos}-"?>"><?=_app::let($_nav[1]['22']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.uni_pla_res')?>
      </article>        
      <!-- holon humano -->
      <article>
        <h2 id="<?="_{$_nav[1]['23']->pos}-"?>"><?=_app::let($_nav[1]['23']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] ])?>
      </article>        
      <!-- Centros Galácticos -->        
      <article>
        <h2 id="<?="_{$_nav[1]['24']->pos}-"?>"><?=_app::let($_nav[1]['24']->nom)?></h2>
        <?=_app_est::lis('hol.uni_hum_cen')?>
      </article>
      <!-- Extremidades -->        
      <article>
        <h2 id="<?="_{$_nav[1]['25']->pos}-"?>"><?=_app::let($_nav[1]['25']->nom)?></h2>
        <?=_app_est::lis('hol.uni_hum_ext')?>
      </article>                     
      <!-- dedos -->        
      <article>            
        <h2 id="<?="_{$_nav[1]['26']->pos}-"?>"><?=_app::let($_nav[1]['26']->nom)?></h2>
        <?=_app_est::lis('hol.uni_hum_ded')?>
      </article>
      <!-- lados -->        
      <article>
        <h2 id="<?="_{$_nav[1]['27']->pos}-"?>"><?=_app::let($_nav[1]['27']->nom)?></h2>
        <?=_app_est::lis('hol.uni_hum_res')?>
      </article>
      <?php 
      break;
    case 'lun': ?>
      <!-- días -->
      <article>
        <h2>Días Lunares</h2>
        <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=_app_est::lis('hol.lun',[ 'atr'=>['ide','arm','rad','ato_des'] ])?> 
      </article>
      <!-- 4 heptadas -->
      <article>          
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>
        <p>En <a href="<?=$_bib?>lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>
        <p>En <a href="<?=$_bib?>" target="_blank">el Telektonon</a><c>.</c></p>
        <p>En <a href="<?=$_bib?>" target="_blank">el átomo del tiempo</a><c>.</c></p>
      </article>        
      <?php
      break;
    case 'chi': ?>
      <article>
      </article>
      <?php
      break;
    case 'cas': ?>
      <article>
      </article>      
      <?php
      break;
    case 'kin': ?>
      <article>
      </article>      
      <?php
      break;
    case 'psi': ?>
      <article>
      </article>      
      <?php
      break;
    }
    // cargo ayuda
    ob_start(); ?>
      <!-- Introduccion -->
      <section>

        <h3>Introducción</h3>

        <p>Los sistemas del Sincronario están basados en códigos y cuentas<c>.</c></p>

        <p>Los <n>13</n> tonos galácticos crean el módulo de sincronización para las <n>13</n> lunas del giro solar y las <n>13</n> trayectorias armónicas del giro galáctico<c>.</c></p>

        <ul>
          <li>Cada Tono Galáctico se encuentra dentro de uno de los <n>4</n> Pulsares Dimensionales<c>,</c> relacionado por uno de los <n>5</n> Pulsares Matiz y se conecta con otro tono por una de las <n>7</n> Simetrías Inversas<c>.</c> A su vez<c>,</c> dentro de una Onda Encantada cumple uno de los <n>4</n> Mandatos de Acción<c>.</c></li>
          <li>Cada sello Solar pertecene a una de las <n>5</n> Familias Terrestres<c>,</c> a una de las <n>4</n> Razas Cósmicas<c>,</c> a uno de los <n>4</n> Clanes Galácticos y a una de las <n>5</n> Células del Tiempo<c>.</c> Por otro lado<c>,</c> el Sello se relaciona con uno de las <n>10</n> Órbitas Planetarias del Sistema Solar<c>,</c> establece uno de los <n>5</n> Centros Planetarios del Holon Terrestre y codifica uno de los <n>20</n> dedos del Holon Humano<c>.</c></li>
          <li>Cada uno de los <n>260</n> kines está compuesto por uno de los <n>13</n> tonos galácticos<c>,</c> y uno de los <n>20</n> sellos solares<c>.</c></li>
          <li>Cada día del año se encuentra en una de los <n>13</n> Giros Lunares y se asocia a uno de los <n>7</n> Plasma Radiales<c>.</c></li>
          <li>Cada Giro Lunar se divide en <n>4</n> semanas<c>/</c>héptadas de <n>7</n> días cada una<c>,</c> codificados por uno de los <n>7</n> Plasmas Radiales<c>.</c> Durante este ciclo se incluye el viaje del Guerrero por el Cubo de la Ley compuesto de <n>16</n> pasos<c>.</c> El resto de los <n>12</n> días se dividen en <n>2</n> caminatas de <n>6</n> días cada una<c>,</c> al inicio y al final de cada ciclo<c>.</c></li>
          <li>Cada Plasma Radial está compuesto por <n>2</n> de las <n>12</n> Líneas de Fuerza<c>,</c> las cuales se generan por la combinacion de dos de los <n>6</n> Tipos de Electricidad en la energía Cósmica<c>.</c> Por otro lado<c>,</c> a cada Plasma se lo relaciona con uno de los <n>7</n> chakras del Cuerpo Humano<c>,</c> y una de las <n>7</n> posiciones del Heptágono de la Mente<c>.</c></li>
          <li>Un Castillo Fractal está compuesto por <n>52</n> posiciones que se dividen en <n>4</n> ondas encantadas de <n>13</n> unidades cada una<c>.</c> Con el castillo se codifican las <n>4</n> estaciones espectrales del giro galáctico<c>,</c> las <n>4</n> estaciones cíclicas del giro solar<c>,</c> los <n>52</n> anillos solares del ciclo Nuevo Siario y los <n>52</n> años del sendero del destino para el kin planetario<c>.</c> A su vez<c>,</c> la nave del tiempo tierra está compuesta de <n>5</n> castillos para abarcar los <n>260</n> kines del giro galáctico<c>.</c></li>
        </ul>

        <p>Todos estos son ejemplos de las cuentas utilizadas para medir el tiempo con el concepto de Matriz Radial<c>.</c> Cada cuenta va del <n>1</n> al <n>n</n><c>,</c> siendo <n>n</n> el valor total que define la cuenta<c>.</c> De esta manera<c>:</c> los plasmas val del <n>1<c>-</c>7</n><c>,</c> los tonos del <n>1<c>-</c>13</n><c>,</c> los sellos del <n>1<c>-</c>20</n><c>,</c> las lunas del <n>1<c>-</c>28</n><c>,</c>etc<c>.</c></p>        

      </section>
    <?php
    $_app->htm['dat'] = ob_get_clean();
  }
  // Códigos
  public function val( _app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_bib = SYS_NAV."hol/bib/";
    $_art = SYS_NAV."hol/art/";
    $_ide = explode('_',$_uri->art);
    switch( $_ide[0] ){
    case 'rad':
      switch( $_ide[1] ){
      case 'ide': 
        break;
      }break;
    case 'ton':
      switch( $_ide[1] ){
      case 'ide':
        $_ton = _hol::_('ton',$_uri->val); ?>
        <!-- Ficha -->
        <article>
    
          <?= _app_dat::inf('hol','ton',$_ton,[ 'opc'=>["atr"] ]) ?>
    
          <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    
    
        </article>
        <!-- Aventura de la Onda Encantada -->
    
        <!-- Simetría Especular -->
    
        <!-- Pulsares Dimensionales -->
    
        <!-- Pulsares Matiz Entonado -->             
        <?php          
        break;
      }break;
    case 'sel':
      switch( $_ide[1] ){
      case 'ide':
        $_sel = _hol::_('sel',$_uri->val); ?>
        <!-- Ficha -->
        <article>
    
          <?= _app_dat::inf('hol','sel',$_sel,[ 'opc'=>[ "atr" ] ]) ?>
    
          <p><?= _app::let($_sel->des_pro) ?></p>
    
          <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'></a> en </p>
    
        </article>
        <!-- Desarrollo del ser -->
    
        <!-- Colocacion Cromática -->
    
        <!-- Colocacion Armónica -->
    
        <!-- Holon Solar -->
    
        <!-- Holon Planetario -->
    
        <!-- Holon Humano -->
        <?php
        break;
      }break;
    case 'lun':
      switch( $_ide[1] ){
      case 'ide': 
        break;
      }break;
    case 'chi':
      switch( $_ide[1] ){
      case 'ide': 
        break;
      }break;
    case 'cas':
      switch( $_ide[1] ){
      case 'ide': 
        break;
      }break;
    case 'kin':
      switch( $_ide[1] ){
      case 'ide':
        $_kin = _hol::_('kin',$_uri->val); ?>
        <!-- ficha del encantamiento -->
        <article>
          <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>
    
          <?= _app_dat::inf('hol','kin',$_kin,['cit'=>"des"]) ?>
    
          <br>
    
          <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>
    
          <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>
    
        </article>
        <!-- parejas -->
        <article>
          <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_app::let($_nav[1]['02']->nom)?></h2>
    
          <?= _app_hol::tab('kin','par',[ 
            'ide'=>$_kin->ide, 'sec'=>[ 'par'=>0 ], 'pos'=>[ 'ima'=>'hol.kin.ide' ]
          ],[
            'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ], 'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
          ])?>
          <!-- Descripciones -->
          <h3 id="<?="_{$_nav[2]['02']['01']->pos}-"?>"><?=_app::let($_nav[2]['02']['01']->nom)?></h3>
          <section>
    
            <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            
    
            <?= _app_hol::fic('kin','par',$_kin) ?>
    
          </section>
          <!-- Lecturas diarias -->
          <h3 id="<?="_{$_nav[2]['02']['02']->pos}-"?>"><?=_app::let($_nav[2]['02']['02']->nom)?></h3>
          <section>
    
            <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>
    
            <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>
    
            <?= _app_hol::fic('kin','par-pro',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
            <br>
            
            <p>En <a href="<?=$_art?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>
    
            <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>
    
            <?= _app_hol::fic('kin','par-lec',$_kin) ?>
    
          </section>  
          <!-- Posiciones en el tzolkin -->  
          <h3 id="<?="_{$_nav[2]['02']['03']->pos}-"?>"><?=_app::let($_nav[2]['02']['03']->nom)?></h3>
          <section>
    
            <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        
    
            <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>
    
            <?= _app_hol::fic('kin','par-cic',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
          </section>  
          <!-- Sincronometría del holon -->
          <h3 id="<?="_{$_nav[2]['02']['04']->pos}-"?>"><?=_app::let($_nav[2]['02']['04']->nom)?></h3>
          <section>
    
            <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>
    
            <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>
    
            <br>
    
            <?= _app_hol::fic('kin','par-gru',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
          </section>
          
        </article>
        <!-- Nave del tiempo -->
        <article>
          <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_app::let($_nav[1]['03']->nom)?></h2>
          <!-- x52 : Castillo Fractal -->  
          <h3 id="<?="_{$_nav[2]['03']['01']->pos}-"?>"><?=_app::let($_nav[2]['03']['01']->nom)?></h3>
          <section>
    
              <?php
              $_cas = _hol::_('kin_nav_cas',$_kin->nav_cas);      
              ?>
              
              <nav>
                <p>Ver el <a href='<?=$_bib?>enc#_01-01-' target='_blank'>Génesis del Encantamiento del Sueño</a> en el encantamiento del sueño<c>...</c></p>
              </nav>
    
              <?= _app_dat::inf('hol','kin_nav_cas',$_cas) ?>
              
              <?= _app_hol::tab('kin','nav_cas',[ 
                'ide'=>$_cas->ide, 
                'val'=>[ 'pos'=>$_kin->ide ],
                'pos'=>[ 'ima'=>'hol.kin.ide' ]
              ], [
                'cas'=>['class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen"], 
                'pos'=>['style'=>"width:2.5rem; height:2.5rem;"]  
              ]) ?>
    
              <h3>En los <n>26.000</n> años del <a href='<?=$_bib?>enc#_01-' target='_blank'>Génesis del Encantamiento del Sueño</a></h3>
    
              <nav>    
                <p>Ver <a href='<?=$_bib?>enc#_03-06-' target='_blank'>el Índice de los castillos</a> en el Encantamiento del Sueño</p>
              </nav>
    
              <h3>En los <n>5.200</n> años del <a href='<?=$_bib?>fac#_01-'> Rayo de Sincronización Galáctica</a></h3>
    
              <nav>
                <p>Ver <a href='<?=$_bib?>fac#' target='_blank'>los 20 ciclos AHAU</a> en el Factor Maya</p>
              </nav>  
    
          </section>
          <!-- x13 : Onda Encantada -->  
          <h3 id="<?="_{$_nav[2]['03']['02']->pos}-"?>"><?=_app::let($_nav[2]['03']['02']->nom)?></h3>
          <section>
            <?php
            $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);  
            ?>
    
            <nav>
              <p>Ver <a href='<?=$_bib?>enc#_03-12-' target='_blank'>la Onda Encantada de la Aventura</a> en el Encantamiento del Sueño</p>
            </nav>
    
            <?= _app_hol::tab('kin','nav_ond', [
              'ide'=>$_ond,
              'sec'=>[ 'par'=>1 ],
              'pos'=>[ 'ima'=>'hol.kin.ide' ]
            ], [
              'ond'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
              'pos'=>[ 'style'=>"width:6rem; height:6rem;" ]
            ]) ?>
    
          </section>  
        </article>
        <!-- Giro Galáctico -->
        <article>
          <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_app::let($_nav[1]['04']->nom)?></h2>
          <!-- x20 : Trayectoria Armónica -->  
          <h3 id="<?="_{$_nav[2]['04']['01']->pos}-"?>"><?=_app::let($_nav[2]['04']['01']->nom)?></h3>
          <section>
            <?php
            $_tra = _hol::_('kin_arm_tra',$_kin->arm_tra);
            $_ton = _hol::_('ton',$_kin->nav_ond_dia);
            ?>    
    
            <nav>
              <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>el Gran Ciclo</a> en el <cite>Factor Maya</cite><c>...</c></p>
            </nav>
    
            <?= _app_dat::inf('hol','kin_arm_tra',$_tra) ?>
    
            <p><?= _app::let($_tra->lec) ?></p>
    
            <?= _app_hol::tab('kin','arm_tra', [
              'ide'=>$_tra,
              'sec'=>[ 'par'=>1 ],
              'pos'=>[ 'ima'=>'hol.kin.ide' ]
            ], [
              'tra'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
              'pos'=>[ 'style'=>"width:5rem; height:5rem;" ],
              'pos-0'=>[ 'style'=>"width:4rem; height:4rem;" ]
            ]) ?>
    
            <p class='tit let-4'>Codificado por el tono <?= $_ton->nom ?></p>
    
            <p><?= $_ton->des ?> del Giro Galáctico.</p>
    
            <nav>
              <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Células del Tiempo</a> en el Encantamiento del Sueño<c>...</c></p>
            </nav>
    
          </section>
          <!-- x4 : Célula del Tiempo -->  
          <h3 id="<?="_{$_nav[2]['04']['02']->pos}-"?>"><?=_app::let($_nav[2]['04']['02']->nom)?></h3>
          <section>
            <?php
            $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel);    
            ?>    
    
            <nav>
              <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Razas Raíz Cósmicas</a> en el Encantamiento del Sueño</p>
            </nav>
    
          </section>  
        </article>
        <!-- Giro Espectral -->
        <article>
          <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_app::let($_nav[1]['05']->nom)?></h2>
          <!-- x65 : Estación Galáctica -->
          <h3 id="<?="_{$_nav[2]['05']['01']->pos}-"?>"><?=_app::let($_nav[2]['05']['01']->nom)?></h3>
          <section>
            <?php
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            ?>
    
            <nav>
              <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
            </nav>    
    
          </section>
          <!-- x5 : Elemento Cromático -->  
          <h3 id="<?="_{$_nav[2]['05']['02']->pos}-"?>"><?=_app::let($_nav[2]['05']['02']->nom)?></h3>
          <section>
            <?php
            $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
            ?>
            
            <nav>
              <p>Ver <a href='<?=$_bib?>enc#_03-16-' target='_blank'>Colocación Cromática</a> en el Encantamiento del Sueño</p>
            </nav>
    
          </section>
        </article>
        <!-- Módulo Armónico : pag + ene + chi -->  
        <article>
          <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=_app::let($_nav[1]['06']->nom)?></h2>
        </article>          
        <?php          
        break;
      }break;
    case 'psi':
      switch( $_ide[1] ){
      case 'ban': 
        $_psi = _hol::_('psi',$_uri->val); ?>
        <!-- x91: Estaciones Solares -->
    
        <!-- x28: Giros Lunares -->
    
        <!-- x20: Vinales -->
    
        <!-- x7: Heptadas -->
    
        <!-- x5: Cromaticas -->        
        <?php
        break;
      }break;
    }      
  }
  // Operadores : Módulos
  public function ope( _app $_app ) : void {
    $_uri = $_app->uri();
    $_val = $this->_val;
    $_ide = $this->_ide;

    // valido tablero      
    if( !isset($_ide[1]) ){
      echo _app::tex('err',"No existe el tablero '$_uri->art'");
      return;
    }

    // operadores del tablero
    $_tab =  _app_tab::dat('hol',$_uri->art);
    $tab_ide = "hol.{$_ide[0]}";
    $tab_ope = !empty($_tab->ope) ? $_tab->ope : [];
    $tab_ele = [];

    // inicializo valores
    $tab_ope['val'] = [];
    $ope_atr = ['kin','psi'];
    // fecha => muestro listado por ciclos
    if( !empty( $_val['fec'] ) ){
      // joins        
      $tab_ope['est'] = [ 'fec'=>["dat"], 'hol'=>$ope_atr ];
      // cargo datos
      $tab_ope['dat'] = $this->_dat;
      // kin + psi : activo acumulados
      if( in_array($_ide[0],$ope_atr) ){
        $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
        // agrego opciones
        if( !empty($tab_ope['opc']) ) $tab_ope['val']['acu']['opc'] = 1;
      }
      // valor seleccionado
      $tab_ope['val']['pos'] = $_val;
    }

    // operadores del tablero
    $ope = _obj::nom(_app_tab::$OPE,'ver',['ver','opc','val']);
    foreach( $ope as $ope_ide => $ope_tab ){

      if( !empty( $htm = _app_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele ) ) ){

        $_app->ope[$ope_ide] = [ 'ico'=>$ope_tab['ico'], 'tip'=>"pan", 'nom'=>$ope_tab['nom'], 'htm'=>$htm];
      }
    }
    // operador de lista
    $ope = _app_tab::$OPE['lis'];
    $_app->ope['est'] = [ 'ico'=>$ope['ico'], 'tip'=>"win", 'nom'=>$ope['nom'], 'htm'=>_app_tab::ope('lis',"hol.{$_ide[0]}",$tab_ope) ];
    // imprimo tablero en página principal
    echo "
    <article>
      "._app_hol::tab($_ide[0], $_ide[1], $tab_ope, [
        'pos'=>[ 'onclick'=>"_app_tab.val('mar',this);" ], 
        'ima'=>[ 'onclick'=>FALSE ]
      ])."
    </article>";
    // cargo tutorial    
    ob_start(); ?>
      <!-- Introducción --> 
      <section>
        <h3>Introducción</h3>          

        <p>Las posiciones del tablero se arman generando ciclos de tiempo según una fecha seleccionada<c>.</c> La primer fecha que se muestra es la del día<c>,</c> pero puedes cambiarla desde la seccion Diario<c>.</c></p>

        <p>Cada elemento del tablero contiene datos sobre los <n>3</n> principales ciclos de tiempo<c>,</c> y sus correspondientes cuentas<c>:</c></p>

        <ul>
          <li>El Kin <c>(</c>Órden Sincrónico de <n>260</n> días<c>)</c><c>:</c> Número de Kin<c>,</c> Tono Galáctico y Sello Solar</li>
          <li>El Psi<c>-</c>Cronos <c>(</c>Órden Cíclico de <n>364<c>+</c>1</n> días<c>)</c><c>:</c> Número del Día Anual<c>,</c> Día Lunar y Plasma</li>
          <li>La Fecha <c>(</c>Calendario Gregoriano de <n>365</n> días<c>)</c><c>:</c> El año, el mes y el día</li>
        </ul>

        <p>Con estos datos se cargan los componentes de los distintos operadores<c>,</c> de los que se trata a continuación<c>...</c></p>

      </section>
      <!-- Diario -->
      <section>
        <h3>Diario</h3>
        <?= _app::ico('fec_val',['class'=>"mar_hor-1"]) ?>          

        <p>Desde esta sección podrás cambiar la fecha para la posición principal del tablero<c>,</c> y ver un detalle de cada código correspondiente<c>.</c></p>

        <p>También puedes <i>marcar</i> otras posiciones del tablero haciendo click directamente sobre ellas<c>.</c> Estas serán cargardas en la seccion de acumulados<c>.</c></p>

      </section>        
      <!-- Seleccion -->
      <section>          
        <h3>Selección por Valores</h3>
        <?= _app::ico('dat_ver',['class'=>"mar_hor-1"]) ?>

        <p>Accede a esta sección para seleccionar múltiples posiciones y luego comparar sus datos<c>.</c> Puedes aplicar criterios de selección por estructuras de datos<c>,</c> fechas<c>,</c> o posiciones<c>.</c></p>

        <p>Si buscas por estructuras de datos<c>,</c> deberás seleccionar primero el grupo de cuentas<c>(</c> Kin<c>,</c> Sello<c>,</c> Tono<c>,</c> Psi<c>-</c>Cronos<c>,</c> etc<c>.</c> <c>)</c><c>,</c> luego la cuenta <c>(</c> las ondas encantadas del kin<c>,</c> las familias de los sellos<c>,</c> los pulsares de los tonos<c>,</c> etc<c>.</c> <c>)</c> y por último el valor <c>(</c> la <n>3</n><c>°</c> onda encantada<c>,</c> la familia cardinal<c>,</c> el pulsar dimensional del tiempo<c>,</c> etc<c>.</c> <c>)</c><c>.</c></p>

        <p>También puedes buscar por fechas del calendario o el número de posiciones en el tablero<c>.</c> En estos casos deberás ingresar un valor inicial<c>,</c> y puedes limitar la sección con un valor final<c>.</c> También puedes indicar un incremento<c>,</c> es decir<c>,</c> un salto entre valores seleccionados<c>;</c> una cantidad límite de resultados<c>;</c> y si quieres que sean los primeros o los últimos de la lista<c>.</c></p>

        <p>Los valores seleccionados serán marcados con un recuadro en el tablero<c>.</c> También serán cargados en la sección de acumulados<c>,</c> serán tenidos en cuenta en los totales por estructura<c>,</c> y se mostrarán en el listado<c>.</c></p>

      </section>        
      <!-- Opciones -->
      <section>          
        <h3>Opciones del Tablero</h3>
        <?= _app::ico('opc_bin',['class'=>"mar_hor-1"]) ?>

        <p>Desde aquí puedes cambiar los colores de fondo<c>,</c> seleccionar el tipo de ficha y ver contenido numérico o textual para cada posición<c>.</c></p>

        <p>Según los atributos del tablero<c>,</c> definidos por su cuenta<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

        <p>Estas posiciones serán tenidas en cuenta en los acumulados del tablero y los elementos de la lista<c>.</c></p>

      </section>
      <!-- Operador -->
      <section>
        <h3>Datos y Valores</h3>
        <?= _app::ico('est',['class'=>"mar_hor-1"]) ?>

        <p>En la parte de acumulados se carga la posición principal por fecha<c>,</c> las posiciones marcadas<c>,</c> seleccionadas y las correspondientes a las opciones del tablero activadas<c>.</c></p>

        <p>En la sección de sumatorias del kin<c>,</c> según la cantidad de posiciones seleccionadas se realizará un cálculo automático que consiste en sumar todos los número de kines y mostrar su valor correspondiente<c>:</c></p>
        <ul>
          <li>El <b class="ide">Kin</b> refleja la sumatoria limitada por <n>260</n><c>:</c> si el total de kines es mayor que <n>260</n> <c>(</c>por ejemplo <n>465</n><c>)</c><c>,</c> se le resta <n>n</n> veces el <n>260</n> hasta obtener un número dentro del ciclo <c>(</c> <n>465 <c>-</c> 260 <c>=</c> 205</n><c>,</c> como este número es menor al límite de <n>260</n> no se le sigue restando ese valor<c>)</c></li>
          <li>El <b class="ide">Psi</b> refleja la sumatoria limitada por <n>365</n><c>:</c> en este caso al total se le va restando de a <n>365</n><c>.</c> Por ejemplo<c>:</c> si la suma de todos los kines da <n>750 <c>=></c> 750 <c>-</c> 365 <c>=</c> 385 <c>-</c> 365 <c>=</c> 20</n><c>.</c> Entonces se mostrará el psi<c>-</c>cronos número <n>20</n></li>
          <li>El <b class="ide">UMB</b> refleja la sumatoria limitada por <n>441</n><c>:</c> se realiza el mismo procedimiento para obtener un valor de <dfn>Unidad de Matriz Base</dfn> correspondiente a la aplicación del Sincronotrón</li>
        </ul>

        <p>También<c>,</c> encontrarás un listado de los códigos y cuentas incluidos en el armado de tablero<c>.</c> Para cada Código de cada Cuenta se muestra un total de elementos activos <c>(</c> por posición<c>,</c> marcas<c>,</c> seleccion y opciones <c>)</c> y su porcentaje sobre el total<c>.</c></p>

      </section>
      <!-- Listado -->
      <section>
        <h3>Listado</h3>
        <?= _app::ico('lis_ite',['class'=>"mar_hor-1"]) ?>

        <p>Se arma con los datos de todas las posiciones acumuladas de los distintos operadores<c>:</c> <b class="ide">Fecha del diario</b><c>,</c> <b class="ide">Marca directa</b><c>,</c> <b class="ide">Selección por Valores</b> y <b class="ide">Opciones del Tablero</b><c>.</c> Puedes elegir entre los distintos critrios de acumulación o ver todas las posiciones<c>.</c></p>

        <p>Los items de la lista contienen los mismos datos sobre los códigos y cuentas que cada posición en el tablero <c>(</c>la Fecha<c>,</c> el Kin y el Psi<c>)</c><c>.</c> Puedes ver y comparar cada atributo que está representado por su ficha correspondiente<c>,</c> al pasar el mouse sobre ellas aparecerá un pequeño contenido descriptivo<c>.</c></p>

        <p>También puedes filtrar las posiciones por las distintas estructuras de códigos y cuentas<c>,</c> de la misma manera que con la selección de posiciones en el Tablero<c>.</c> Tambien puedes aplicarlos por fecha siguiendo los mismos criterios <c>(</c> desde<c>,</c> hasta<c>,</c> cada<c>,</c> cuántos<c>,</c> al<c>...</c> <c>)</c></p>

        <p>Luego puedes seleccionar las columnas que representen a los datos que deseas ver<c>,</c> y revelar los titulos por Ciclos y Agrupaciones o analizar las lecturas disponibles para cada posición<c>.</c></p>

      </section>        
    <?php
    $_app->htm['dat'] = ob_get_clean();
  }
  // Kin Planetario
  public function usu( _app $_app ) : void {
    global $_usu;
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();

  }
}