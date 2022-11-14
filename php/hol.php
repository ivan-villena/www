<?php

// Sincronario
class hol {

  public array $_cab = [ "bib", "art", "ope", "usu"  ];
  public array $_val = [];
  public array $_dat = [];
  public array $_ide = [];

  // main : app.cab.art/val
  public function __construct( app $_app ){
    // inicializo datos    
    $_uri = $_app->uri();

    // Diario : Fecha del Sistema => valor por peticion : hol/$cab/$art/"ide=val"      
    $this->_val = api_hol::val( !empty($_SESSION['hol-val']) ? api_hol::cod($_SESSION['hol-val']) : date('Y/m/d') );
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
        $this->_dat = api_hol::dat($this->_ide[0], $this->_val);
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
      var \$_hol = new hol({ val : ".api_obj::cod($this->_val)." });
    ";
  }
  // Inicio
  public function ini( app $_app ) : void {
    $_hol = $this->_val;
    // cargo articulo
    ob_start(); ?>

    <?=app::tex('adv', "Este sitio aún se está en construcción, puede haber contenido incompleto, errores o fallas.")?>

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
        <?= app::ico('fec_val',['class'=>"mar_hor-1"]) ?>

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
          <?= app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= app::ico('tex_lib',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>Aquí podrás encontrar la mayoría de los libros en los cuales se basa la teoría del Sincronario<c>:</c> <q>La ley del Tiempo</q><c>.</c> Esta fué desarrollada por <a href="https://es.wikipedia.org/wiki/Jos%C3%A9_Arg%C3%BCelles" target="_blank">José Argüelles</a> quien organizó una fundación con el mismo nombre <c>(</c><a href="http://www.lawoftime.org" target="_blank">The Law of Time</a><c>)</c><c>.</c> Todos sus libros y materiales se pueden descargar gratuitamente desde <a href="https://13lunas.net/mapa.htm#biblioteca" target="_blank">La Biblioteca de <n>13</n> Lunas</a><c>.</c></p>

        <p>En este sitio se adaptó el formato de texto de cada libro para una página web<c>,</c> se agregaron los íconos correspondientes a cada símbolo e imágenes que amplían su contenido visualamente<c>.</c> También se muestran en orden cronológico<c>,</c> ya que este conocimiento es incremental y los temas se entrelazan y completan en cada publicación<c>.</c></p>

        <p>En la página de cada libro hay un índice en el panel izquierdo<c>,</c> que puedes ocultar o mostrar haciendo click en el botón Correspondiente<c>.</c> Los items del índice que figuran en el libro son los mismos<c>,</c> pero se agregaron nuevos para segmentar la información y poder acceder desde enlaces<c>.</c></p>

      </section>
      <!-- Artículos -->
      <section>          
        <h3>Los Artículos</h3>

        <div class="val jus-cen">
          <?= app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= app::ico('tex_inf',['class'=>"mar_hor-1"]) ?>
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
          <?= app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= app::ico('num_cod',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>En esta sección podrás encontrar datos que van apareciendo en los distintos libros y están relacionados a cada código y cuenta<c>,</c> junto con sus respectivas agrupaciones y subciclos<c>.</c></p>

        <p>Haciendo referencia a la fuente<c>,</c> se describen brevemente para introducir al lector en sus conceptos y bridarle acceso directo al material donde puede encontrarlo<c>.</c> A partir de su comprensión se pueden realizar lecturas y relaciones entre distintas posiciones<c>,</c> fechas o firmas galácticas<c>.</c></p>

      </section>
      <!-- Módulos -->
      <section>
        <h3>Los Módulos</h3>

        <div class="val jus-cen">
          <?= app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= app::ico('tab',['class'=>"mar_hor-1"]) ?>
        </div>
        
        <p>Desde el menú principal puedes acceder a un listado de tableros que representan las cuentas principales del sincronario<c>,</c> a estos los llamaremos módulos<c>.</c></p>

        <p>Para cada módulo se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por Ej<c>:</c> el <a href="<?=SYS_NAV."hol/tab/kin-tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

        <p>Desde allí podrás cambiar la fecha y acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos de las posiciones<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones seleccionadas para comparar sus características y ubicaciones<c>.</c></p>
        
      </section>
    <?php
    $_app->htm['dat'] = ob_get_clean();
  }
  // Diario
  public function dia( app $_app ) : void {
    $_uri = $_app->uri();
    if( !empty($_uri->val) ){
      
      if( isset($_uri->val[1]) ){

        if( $_uri->val[0] == 'fec' || $_uri->val[0] == 'sin' ){
          $this->_val = api_hol::val($_uri->val[1],$_uri->val[0]);
          // actualizo fecha del sistema
          $_SESSION['hol-val'] = $_uri->val[1];
        }// tomo 
        else{
          $this->_val[ $_uri->val[0] ] = $_uri->val[1];
        }
      }
    }
    // panel con operadores
    $ope = [
      'kin' => [ 'ide'=>"kin", 'ico'=>"", 'nom'=>"Sincrónico",   'des'=>"", 'htm'=>app_dat::pos("hol","kin",[ 
        'kin'=>$_kin = api_hol::_('kin',$this->_val['kin']),
        'sel'=>api_hol::_('sel',$_kin->arm_tra_dia),
        'ton'=>api_hol::_('ton',$_kin->nav_ond_dia)
      ])],
      'psi' => [ 'ide'=>"psi", 'ico'=>"", 'nom'=>"Cíclico",      'des'=>"", 'htm'=>app_dat::pos("hol","psi",[ 
        'psi'=>$_psi = api_hol::_('psi',$this->_val['psi']),
        'est'=>api_hol::_('est',$_psi->est),
        'lun'=>api_hol::_('lun',$_psi->lun),
        'hep'=>api_hol::_('hep',$_psi->hep)
      ])]      
    ];
    $_app->ope['dia'] = [ 'ico'=>"fec_val", 'tip'=>"pan", 'nom'=>"Diario", 'htm'=>"

      <section class='mar_aba-1'>

        ".app_var::hol('fec',$this->_val,[ 'eje'=>"hol_app.dia" ])."

        <div class='mar-1'>
          ".app_dat::inf('hol','kin',$this->_val['kin'],['opc'=>"nom",'cit'=>"des"])."
        </div>

      </section>

      ".app::nav('bar',$ope,[ 'sel' => "kin" ])
    ];
  }
  // Bibliografía
  public function bib( app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_dir = $_app->dir();
    $_bib = SYS_NAV."hol/bib/";
    // busco archivos : html-php
    if( !empty( $rec = api_arc::ide($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){
      include( $rec );
    }
    else{
      echo app::tex('err',"No existe el archivo '$val'");
    }
  }
  // Artículos
  public function art( app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_dir = $_app->dir();
    $_bib = SYS_NAV."hol/bib/";
    // busco archivos : html-php
    if( !empty( $rec = api_arc::ide($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){
      include( $rec );
    }
    else{
      echo app::tex('err',"No existe el archivo '$val'");
    }
  }    
  // Cuentas
  public function dat( app $_app ) : void {
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();
    $_bib = SYS_NAV."hol/bib/";
    $_art = SYS_NAV."hol/art/";
    switch( $_uri->art ){
    case 'rad': ?>
      <!-- dìas de la semana -->
      <article>          
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=app::let($_nav[1]['01']->nom)?></h2>

        <p>En <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">El encantamiento del Sueño</a> se divide al año en <n>13</n> lunas de <n>28</n> días cada una<c>.</c> Cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>

        <p>Posteriormente<c>,</c> en el libro de <a href="<?=$_bib?>lun#_02-07-" target="_blank">Las <n>13</n> lunas en movimiento</a>, se mencionan los plasmas para nombrar a cada uno de los días de la semana<c>-</c>heptada<c>.</c></p>

        <?=app_est::lis('hol.rad',[ 'atr'=>['ide','nom','pod'] ])?>

      </article>
      <!-- sellos de la profecia -->
      <article>          
        <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=app::let($_nav[1]['02']->nom)?></h2>

        <p>En <a href="<?=$_bib?>tel#_02-06-" target="_blank">El telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

        <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario<c>:</c> familia portal y familia señal <c>(</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

        <?=app_est::lis('hol.rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ])?>

        <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

        <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

        <?=app_est::lis('hol.rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ])?>

      </article>
      <!-- heptágono de la mente -->
      <article>
        <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=app::let($_nav[1]['03']->nom)?></h2>

        <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

        <p>En el <a href="<?=$_bib?>rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>            

        <?=app_est::lis('hol.rad',[ 'atr'=>['ide','nom','pla_cub','pla_cub_pos'] ])?>

      </article>
      <!-- autodeclaraciones diarias -->
      <article>
        <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=app::let($_nav[1]['04']->nom)?></h2>

        <p>En <a href="<?=$_bib?>ato#_03-06-" target="_blank">Átomo del Tiempo</a> se describen las afirmaciones correspondientes a las Autodeclaraciones Diarias de Padmasambhava para cada plasma<c>.</c></p>

        <?=app_est::lis('hol.rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','pla_lec'] ])?>

      </article>
      <!-- componenetes electrónicos -->
      <article>
        <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=app::let($_nav[1]['05']->nom)?></h2>

        <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

        <?=app_est::lis('hol.rad',[ 'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_fue','pla_fue_pre','pla_fue_pos'] ])?>

      </article>      
      <?php
      break;
    case 'ton': ?>
      <!-- rayos de pulsación -->
      <article>
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=app::let($_nav[1]['01']->nom)?></h2>
        <p>En <cite>el Factor Maya</cite> se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c></p>
        <p>Luego<c>,</c> los <n>13</n> tonos se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c></p>
        <?=app_est::lis('hol.ton',[ 'atr'=>['ide','gal'] ])?>
      </article>
      <!-- simetría especular -->
      <article>
        <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=app::let($_nav[1]['02']->nom)?></h2>
        <p>En el <cite>Factor Maya</cite> se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c></p>
        <p>Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a><c>.</c></p>
        <?=app_est::lis('hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>          
      </article>        
      <!-- principios de la creacion -->
      <article>
        <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=app::let($_nav[1]['03']->nom)?></h2>
        <p>En <cite>el Encantamiento del sueño</cite> se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c></p>
        <p>De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c></p>
        <?=app_est::lis('hol.ton',[ 'atr'=>['ide','nom','des','acc'] ])?>
      </article>
      <!-- O.E. de la Aventura -->
      <article>
        <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=app::let($_nav[1]['04']->nom)?></h2>
        <p>En el <cite>Encantamiento del sueño</cite> se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c></p>
        <p>Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c></p>
        <?=app_est::lis('hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc']])?>
      </article>        
      <!-- pulsar dimensional -->
      <article>
        <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=app::let($_nav[1]['05']->nom)?></h2>
        <p>En <cite>el Encantamiento del sueño</cite> <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a> <c>.</c></p>
        <?=app_est::lis('hol.ton_dim')?>
      </article>
      <!-- pulsar matiz -->
      <article>
        <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=app::let($_nav[1]['06']->nom)?></h2>
        <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>
        <?=app_est::lis('hol.ton_mat')?>
      </article>          
      <?php
      break;
    case 'sel': ?>
      <!-- signos direccionales -->
      <article>
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=app::let($_nav[1]['01']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=app_est::lis('hol.sel_cic_dir')?>
      </article>
      <!-- 3 etapas del desarrollo -->
      <article>
        <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=app::let($_nav[1]['02']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=app_est::lis('hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ])?>
      </article>
      <!-- 4 etapas evolutivas -->
      <article>
        <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=app::let($_nav[1]['03']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=app_est::lis('hol.kin_cro_est',[ 'atr'=>['sel','nom','des','det','lec'] ])?>
      </article>
      <!-- 5 familias ciclicas -->
      <article>
        <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=app::let($_nav[1]['04']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>
        <?=app_est::lis('hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ])?>
      </article>        
      <!-- Colocacion cromática -->
      <article>
        <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=app::let($_nav[1]['05']->nom)?></h2>          
        <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>20</n> o <n>00</n> a <n>19</n><c>.</c></p>          
        <?=app_est::lis('hol.sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>
      </article>
      <!-- 5 familias terrestres -->        
      <article>
        <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=app::let($_nav[1]['06']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ])?>
      </article>
      <!-- 4 clanes cromáticos -->
      <article>
        <h2 id="<?="_{$_nav[1]['07']->pos}-"?>"><?=app::let($_nav[1]['07']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ])?>
      </article>        
      <!-- Colocación armónica -->
      <article>
        <h2 id="<?="_{$_nav[1]['08']->pos}-"?>"><?=app::let($_nav[1]['08']->nom)?></h2>
        <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>1</n> a <n>20</n><c>.</c></p>
        <?=app_est::lis('hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>
      </article>
      <!-- 4 Razas raiz cósmicas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['09']->pos}-"?>"><?=app::let($_nav[1]['09']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ])?>
      </article>
      <!-- células del tiempo-->
      <article>
        <h2 id="<?="_{$_nav[1]['10']->pos}-"?>"><?=app::let($_nav[1]['10']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ])?>
      </article>
      <!-- parejas del oráculo -->
      <article>
        <h2 id="<?="_{$_nav[1]['11']->pos}-"?>"><?=app::let($_nav[1]['11']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>
      </article>                  
      <!-- parejas analogas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['12']->pos}-"?>"><?=app::let($_nav[1]['12']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_par_ana')?>
      </article>
      <!-- parejas antípodas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['13']->pos}-"?>"><?=app::let($_nav[1]['13']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_par_ant')?>
      </article>
      <!-- parejas ocultas -->        
      <article>
        <h2 id="<?="_{$_nav[1]['14']->pos}-"?>"><?=app::let($_nav[1]['14']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_par_ocu')?>
      </article>        
      <!-- holon Solar -->
      <article>
        <h2 id="<?="_{$_nav[1]['15']->pos}-"?>"><?=app::let($_nav[1]['15']->nom)?></h2>
        <p>El código 0-19</p>              
        <?=app_est::lis('hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] ])?>
      </article>
      <!-- orbitas planetarias -->        
      <article>
        <h2 id="<?="_{$_nav[1]['16']->pos}-"?>"><?=app::let($_nav[1]['16']->nom)?></h2>
        <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>
        <?=app_est::lis('hol.uni_sol_pla')?>
      </article>
      <!-- células solares -->        
      <article>
        <h2 id="<?="_{$_nav[1]['17']->pos}-"?>"><?=app::let($_nav[1]['17']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.uni_sol_cel')?>
      </article>
      <!-- circuitos de telepatía -->        
      <article>
        <h2 id="<?="_{$_nav[1]['18']->pos}-"?>"><?=app::let($_nav[1]['18']->nom)?></h2>
        <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>
        <?=app_est::lis('hol.uni_sol_cir')?>
      </article>             
      <!-- holon planetario -->
      <article>
        <h2 id="<?="_{$_nav[1]['19']->pos}-"?>"><?=app::let($_nav[1]['19']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>
      </article>
      <!-- campos dimensionales -->        
      <article>
        <h2 id="<?="_{$_nav[1]['20']->pos}-"?>"><?=app::let($_nav[1]['20']->nom)?></h2>
        <p></p>
      </article>          
      <!-- centros galácticos -->        
      <article>
        <h2 id="<?="_{$_nav[1]['21']->pos}-"?>"><?=app::let($_nav[1]['21']->nom)?></h2>
        <p></p>
        <?=app_est::lis('hol.uni_pla_cen')?>
      </article>
      <!-- flujos de la fuerza-g -->        
      <article>
        <h2 id="<?="_{$_nav[1]['22']->pos}-"?>"><?=app::let($_nav[1]['22']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.uni_pla_res')?>
      </article>        
      <!-- holon humano -->
      <article>
        <h2 id="<?="_{$_nav[1]['23']->pos}-"?>"><?=app::let($_nav[1]['23']->nom)?></h2>
        <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] ])?>
      </article>        
      <!-- Centros Galácticos -->        
      <article>
        <h2 id="<?="_{$_nav[1]['24']->pos}-"?>"><?=app::let($_nav[1]['24']->nom)?></h2>
        <?=app_est::lis('hol.uni_hum_cen')?>
      </article>
      <!-- Extremidades -->        
      <article>
        <h2 id="<?="_{$_nav[1]['25']->pos}-"?>"><?=app::let($_nav[1]['25']->nom)?></h2>
        <?=app_est::lis('hol.uni_hum_ext')?>
      </article>                     
      <!-- dedos -->        
      <article>            
        <h2 id="<?="_{$_nav[1]['26']->pos}-"?>"><?=app::let($_nav[1]['26']->nom)?></h2>
        <?=app_est::lis('hol.uni_hum_ded')?>
      </article>
      <!-- lados -->        
      <article>
        <h2 id="<?="_{$_nav[1]['27']->pos}-"?>"><?=app::let($_nav[1]['27']->nom)?></h2>
        <?=app_est::lis('hol.uni_hum_res')?>
      </article>
      <?php 
      break;
    case 'lun': ?>
      <!-- días -->
      <article>
        <h2>Días Lunares</h2>
        <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
        <?=app_est::lis('hol.lun',[ 'atr'=>['ide','arm','rad','ato_des'] ])?> 
      </article>
      <!-- 4 heptadas -->
      <article>          
        <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=app::let($_nav[1]['01']->nom)?></h2>
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
  // Operadores : Módulos
  public function ope( app $_app ) : void {
    $_uri = $_app->uri();
    $_val = $this->_val;
    $_ide = $this->_ide;

    // valido tablero      
    if( !isset($_ide[1]) ){
      echo app::tex('err',"No existe el tablero '$_uri->art'");
      return;
    }

    // operadores del tablero
    $tab_ide = "hol.{$_ide[0]}";  
    if( !( $tab_ope =  app::dat('hol',$_uri->art,'tab') ) ) $tab_ope = [];    

    // inicializo valores
    $tab_ope['val'] = [];
    $ope_atr = ['kin','psi'];
    // fecha => muestro listado por ciclos
    if( !empty( $_val['fec'] ) ){
      // joins
      $tab_ope['est'] = [ 
        'fec'=>["dat"],
        'hol'=>$ope_atr
      ];
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
    $ope = api_obj::nom(app_tab::$OPE,'ver',['ver','opc','val']);
    foreach( $ope as $ope_ide => $ope_tab ){

      if( !empty( $htm = app_tab::ope($ope_ide, $tab_ide, $tab_ope) ) ){

        $_app->ope[$ope_ide] = [ 'ico'=>$ope_tab['ico'], 'tip'=>"pan", 'nom'=>$ope_tab['nom'], 'htm'=>$htm];
      }
    }
    // operador de lista
    $ope = app_tab::$OPE['lis'];
    $_app->ope['est'] = [ 'ico'=>$ope['ico'], 'tip'=>"win", 'nom'=>$ope['nom'], 'htm'=>app_tab::ope('lis',"hol.{$_ide[0]}",$tab_ope) ];
    // imprimo tablero en página principal
    echo "
    <article>
      ".app_tab::hol($_ide[0], $_ide[1], $tab_ope, [
        'pos'=>[ 'onclick'=>"app_tab.val('mar',this);" ], 
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
        <?= app::ico('fec_val',['class'=>"mar_hor-1"]) ?>          

        <p>Desde esta sección podrás cambiar la fecha para la posición principal del tablero<c>,</c> y ver un detalle de cada código correspondiente<c>.</c></p>

        <p>También puedes <i>marcar</i> otras posiciones del tablero haciendo click directamente sobre ellas<c>.</c> Estas serán cargardas en la seccion de acumulados<c>.</c></p>

      </section>        
      <!-- Seleccion -->
      <section>          
        <h3>Selección por Valores</h3>
        <?= app::ico('dat_ver',['class'=>"mar_hor-1"]) ?>

        <p>Accede a esta sección para seleccionar múltiples posiciones y luego comparar sus datos<c>.</c> Puedes aplicar criterios de selección por estructuras de datos<c>,</c> fechas<c>,</c> o posiciones<c>.</c></p>

        <p>Si buscas por estructuras de datos<c>,</c> deberás seleccionar primero el grupo de cuentas<c>(</c> Kin<c>,</c> Sello<c>,</c> Tono<c>,</c> Psi<c>-</c>Cronos<c>,</c> etc<c>.</c> <c>)</c><c>,</c> luego la cuenta <c>(</c> las ondas encantadas del kin<c>,</c> las familias de los sellos<c>,</c> los pulsares de los tonos<c>,</c> etc<c>.</c> <c>)</c> y por último el valor <c>(</c> la <n>3</n><c>°</c> onda encantada<c>,</c> la familia cardinal<c>,</c> el pulsar dimensional del tiempo<c>,</c> etc<c>.</c> <c>)</c><c>.</c></p>

        <p>También puedes buscar por fechas del calendario o el número de posiciones en el tablero<c>.</c> En estos casos deberás ingresar un valor inicial<c>,</c> y puedes limitar la sección con un valor final<c>.</c> También puedes indicar un incremento<c>,</c> es decir<c>,</c> un salto entre valores seleccionados<c>;</c> una cantidad límite de resultados<c>;</c> y si quieres que sean los primeros o los últimos de la lista<c>.</c></p>

        <p>Los valores seleccionados serán marcados con un recuadro en el tablero<c>.</c> También serán cargados en la sección de acumulados<c>,</c> serán tenidos en cuenta en los totales por estructura<c>,</c> y se mostrarán en el listado<c>.</c></p>

      </section>        
      <!-- Opciones -->
      <section>          
        <h3>Opciones del Tablero</h3>
        <?= app::ico('opc_bin',['class'=>"mar_hor-1"]) ?>

        <p>Desde aquí puedes cambiar los colores de fondo<c>,</c> seleccionar el tipo de ficha y ver contenido numérico o textual para cada posición<c>.</c></p>

        <p>Según los atributos del tablero<c>,</c> definidos por su cuenta<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

        <p>Estas posiciones serán tenidas en cuenta en los acumulados del tablero y los elementos de la lista<c>.</c></p>

      </section>
      <!-- Operador -->
      <section>
        <h3>Datos y Valores</h3>
        <?= app::ico('est',['class'=>"mar_hor-1"]) ?>

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
        <?= app::ico('lis_ite',['class'=>"mar_hor-1"]) ?>

        <p>Se arma con los datos de todas las posiciones acumuladas de los distintos operadores<c>:</c> <b class="ide">Fecha del diario</b><c>,</c> <b class="ide">Marca directa</b><c>,</c> <b class="ide">Selección por Valores</b> y <b class="ide">Opciones del Tablero</b><c>.</c> Puedes elegir entre los distintos critrios de acumulación o ver todas las posiciones<c>.</c></p>

        <p>Los items de la lista contienen los mismos datos sobre los códigos y cuentas que cada posición en el tablero <c>(</c>la Fecha<c>,</c> el Kin y el Psi<c>)</c><c>.</c> Puedes ver y comparar cada atributo que está representado por su ficha correspondiente<c>,</c> al pasar el mouse sobre ellas aparecerá un pequeño contenido descriptivo<c>.</c></p>

        <p>También puedes filtrar las posiciones por las distintas estructuras de códigos y cuentas<c>,</c> de la misma manera que con la selección de posiciones en el Tablero<c>.</c> Tambien puedes aplicarlos por fecha siguiendo los mismos criterios <c>(</c> desde<c>,</c> hasta<c>,</c> cada<c>,</c> cuántos<c>,</c> al<c>...</c> <c>)</c></p>

        <p>Luego puedes seleccionar las columnas que representen a los datos que deseas ver<c>,</c> y revelar los titulos por Ciclos y Agrupaciones o analizar las lecturas disponibles para cada posición<c>.</c></p>

      </section>        
    <?php
    $_app->htm['dat'] = ob_get_clean();
  }
  // Kin Planetario
  public function usu( app $_app ) : void {
    global $_usu;
    $_nav = $_app->doc['nav'];
    $_uri = $_app->uri();

  }
}

// Bibliografía : libros
class hol_bib {
  static string $IDE = "hol_bib-";
  static string $EJE = "hol_bib.";

  // Tierra en ascenso
  static function asc( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Factor Maya
  static function fac( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // tonos : rayo de pulsacion
    case 'ton':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('ton') as $_ton ){ $_ []= "
        ".api_hol::ima("ton",$_ton,['class'=>"mar_der-1"])."
        <p>
          <n>".intval($_ton->ide)."</n><c>.</c> El Rayo de Pulsación ".preg_replace("/^(del|de la)/","$1<b class='ide'>",api_tex::art_del($_ton->gal))."</b>
        </p>";
      }        
      break;
    // tonos : simetría especular
    case 'ton_sim': 
      foreach( api_hol::_('ton_sim') as $_sim ){ $_ []= "
        <p>".app::let($_sim->des)."</p>";
      }        
      break;
    // sellos : posiciones direccionales
    case 'sel_cic_dir':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_($ide) as $_dir ){ $_ []=
        api_hol::ima($ide,$_dir,['class'=>"mar_der-1 tam-11"])."
        <div>
          <p><b class='ide'>".explode(' ',$_dir->nom)[1]."</b><c>:</c></p>
          <ul>
            <li><p><c>-></c> ".app::let($_dir->des)."</p></li>
            <li><p><c>-></c> Color<c>:</c> <c class='let_col-4-{$_dir->ide}'>{$_dir->col}</c></p></li>
          </ul>
        </div>";
      }
      break;
    // sellos : desarrollo del ser con etapas evolutivas
    case 'sel_cic_ser':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('sel') as $_sel ){
        if( $lis_pos != $_sel->cic_ser ){
          $lis_pos = $_sel->cic_ser;
          $_ser = api_hol::_($ide,$lis_pos);
          $_ []= "
          <p class='tit'>
            DESARROLLO".( api_tex::let_may( api_tex::art_del($_ser->nom) ) ).( !empty($_ser->det) ? " <c>-</c> Etapa {$_ser->det}" : '' )."
          </p>";
        }                
        $_dir = api_hol::_('sel_cic_dir',$_sel->arm_raz); $_ []= 

        api_hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p><n>{$_sel->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".explode(' ',$_dir->nom)[1]."<c>.</c>
          <br>".app::let($_sel->cic_ser_des)."
        </p>";
      }        
      break;
    // sellos : familias ciclicas
    case 'sel_cic_luz': 
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('sel') as $_sel ){
        if( $lis_pos != $_sel->cic_luz ){
          $lis_pos = $_sel->cic_luz;
          $_luz = api_hol::_($ide,$lis_pos); $_ []= "
          <p><b class='tit'>".api_tex::let_may("Familia Cíclica ".api_tex::art_del($_luz->nom)."")."</b>
            <br><b class='des'>{$_luz->des}</b><c>.</c>
          </p>";
        }                
        $_dir = api_hol::_('sel_cic_dir',$_sel->arm_raz);                 
        
        $_ []= 

        api_hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p>".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
          <br>".app::let($_sel->cic_luz_des)."
        </p>";                
      }          
      break;
    // kin : katun del kin
    case 'kin':
      $_kin = api_hol::_('kin',$ope['ide']);
      $_sel = api_hol::_('sel',$_kin->arm_tra_dia);
      $_pol = api_hol::_('sel_res',$_sel->res_flu);
      $_pla = api_hol::_('uni_sol_pla',$_sel->sol_pla);
      $_ond = api_hol::_('kin_nav_ond',$_kin->nav_ond);
      $_arq = api_hol::_('sel',$_ond->sel);
      $ton = intval($_kin->nav_ond_dia);
      $_ = "
      <div class='val'>

        ".api_hol::ima("kin",$_kin)."

        <p class='tit tex_ali-izq'>
          Katún <n>".intval($_sel->ide-1)."</n><c>:</c> Kin <n>$ton</n> <b class='ide'>$_sel->may</b>".( !empty($_kin->pag) ? "<c>(</c> Activación Galáctica <c>)</c>" : '' )."<c>.</c>
        </p>
      
      </div>
      <ul>
        <li>Regente Planetario<c>:</c> $_pla->nom $_pol->tip<c>.</c></li>
        <li>Etapa <n>$ton</n><c>,</c> Ciclo $_arq->may<c>.</c></li>
        <li>Índice Armónico <n>".api_num::int($_kin->fac)."</n><c>:</c> período ".app::let($_kin->fac)."</li>
        <li><q>".app::let($_sel->arm_tra_des)."</q></li>
      </ul>";
      break;
    // kin : portales de activacion
    case 'kin_pag':
      $arm_tra = 0;
      $ope['lis'] = ['class'=>"ite"];
      foreach( array_filter(api_hol::_('kin'), function( $ite ){ return !empty($ite->pag); }) as $_kin ){ 
        $lis_pos++; 
        $_sel = api_hol::_('sel',$_kin->arm_tra_dia);
        if( $arm_tra != $_kin->arm_tra ){
          $arm_tra = $_kin->arm_tra;
          $_tra = api_hol::_('kin_arm_tra',$arm_tra); $_ []= "

          ".api_hol::ima("ton",$arm_tra,['class'=>"mar_der-1"])."

          <p class='tit'>".app::let(api_tex::let_may("CICLO ".($num = intval($_tra->ide)).", Baktún ".( $num-1 )))."</p>";
        }
        $_ []= "

        ".api_hol::ima("kin",$_kin,['class'=>"mar_der-1"])."

        <p>
          <n>{$lis_pos}</n><c>.</c> <b class='ide'>{$_sel->may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
          <br>".app::let($_kin->fac)."
        </p>";
      }          
      break;
    // kin : 1 trayectoria con detalles por katun ( ciclos del modelo morfogenetico )
    case 'kin_fec':
      $ond = 0;
      $_ = "
      <table>";
        if( !empty($ope['tit']) ){ $_.="
          <caption>".( !empty($ope['tit']['htm']) ? "<p class='tit'>".app::let($ope['tit']['htm'])."</p>" : '' )."</caption>";
        }$_.="

        <thead>
          <tr>
            <td></td>
            <td>CICLO AHAU</td>
            <td>CICLO KATUN <c>(</c><i>índice armónico y año</i><c>)</c></td>
            <td>CUALIDAD MORFOGENÉTICA</td>
          </tr>
        </thead>

        <tbody>";
        foreach( ( !empty($dat) ? $dat : api_hol::_('kin') ) as $_kin ){

          if( $ond != $_kin->nav_ond ){
            $_ond = api_hol::_('kin_nav_ond', $ond = $_kin->nav_ond); 
            $_sel = api_hol::_('sel', $_ond->sel);
            $_ .= "
            <tr class='tex_ali-izq'>
              <td>
                ".api_hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."
              </td>
              <td colspan='3'>{$_sel->may}<c>:</c> ".app::let($_ond->fac)." <q>".app::let($_ond->fac_des)."</q></td>
            </tr>";
          }
          $_sel = api_hol::_('sel',$sel = intval($_kin->arm_tra_dia));
          $_ .= "
          <tr data-kin='{$_kin->ide}'>
            <td>
              Etapa <n>".($ton = intval($_kin->nav_ond_dia))."</n>
            </td>
            <td></td>
            <td>
              <n>$sel</n><c>.</c><n>$ton</n> <b class='ide'>$_sel->may</b><c>:</c>
              <br><n>".api_num::int($_kin->fac)."</n><c>,</c> año <n>".api_num::int($_kin->fac_ini)."</n>
            </td>
            <td>
              <q>".app::let($_sel->arm_tra_des)."</q>
            </td>
          </tr>";
        }$_.="
        </tbody>

      </table>";
      break;
    // kin : 13 baktunes
    case 'kin_arm_tra':

      foreach( api_hol::_($ide) as $_tra ){
        $htm = "
        <div class='val'>
          ".api_hol::ima("ton",$_tra->ide,['class'=>"mar_der-1"])."
          <p>
            <b class='tit'>Baktún <n>".(intval($_tra->ide)-1)."</n><c>.</c> Baktún ".api_tex::art_del($_tra->tit)."</b>
            <br>".app::let($_tra->fac)." <c><=></c> ".app::let($_tra->may)."
          </p>
        </div>";
        $lis = [];
        foreach( explode('; ',$_tra->lec) as $ite ){
          $lis []= "<c>-></c> ".app::let($ite);
        }
        $_[] = $htm.app_lis::val($lis,[ 'lis'=>['class'=>"pun"] ]);
      }          
      break;
    // kin : 20 katunes
    case 'kin_arm_sel':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_('sel') as $_sel ){ $_ [] = "

        ".api_hol::ima("sel_arm_tra",$_sel,['class'=>"mar_der-2"])."

        <p>
          <b class='ide'>{$_sel->may}</b><c>:</c> Katún <n>".(intval($_sel->ide)-1)."</n>
          <br>{$_sel->arm_tra_des}
        </p>";
      }
      break;
    // kin : sellos guardianes de la evolucion mental
    case 'kin_cro_est':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('kin_cro_est') as $_est ){
        $_sel = api_hol::_('sel',$_est->sel); 
        $_dir = api_hol::_('sel_cic_dir',$_est->ide); $_ []= 
        
        api_hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p><n>".intval($_sel->ide)."</n><c>°</c> Signo<c>.</c> ".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
          <br><b class='val des'>{$_est->nom}</b><c>:</c> {$_est->des}<c>.</c>
        </p>";
      }        
      break;
    // kin : guardianes por estacion cromatica
    case 'kin_cro_sel':
      foreach( api_hol::_($ide) as $_est ){
        $_sel = api_hol::_('sel',$_est->sel); $htm = "
        <div class='val'>
          ".api_hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
          <p>
            <b class='tit'>ESTACIÓN ".api_tex::let_may(api_tex::art_del("el {$_est->dir}"))."</b>
            <br>Guardían<c>:</c> <b class='ide'>{$_sel->may}</b> <c>(</c> {$_sel->nom} <c>)</c>
          </p>
        </div>";
        $lis = [];
        foreach( api_hol::_('kin_cro_ond') as $_ond ){ $lis []= "

          ".api_hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."

          <p>El quemador {$_ond->que} el Fuego<c>.</c>
            <br><n>".intval($_ond->ton)."</n> {$_sel->may}
          </p>";
        }                
        $_[] = $htm.app_lis::val($lis,[ 'lis'=>['class'=>'ite'] ]);
      }          
      break;
    // kin : ciclo ahau / onda encantada
    case 'kin_nav_sel':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_('kin_nav_ond') as $_ond ){ 
        $_sel = api_hol::_('sel',$_ond->sel); $_ [] = "

        ".api_hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-2"])."

        <p>
          <n>{$_ond->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".app::let($_ond->fac)."
          <br><q>{$_ond->fac_des}</q>
        </p>";
      }            
      break;
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Encantamiento del Sueño
  static function enc( string $ide, array $ope = [] ) : string {
    $_ = []; 
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    $_eje = self::$EJE."enc('{$ide}',";
    switch( $ide ){
    // tonos : descripciones
    case 'ton':
      $est_ope['atr'] = ['ide','nom','des','acc'];
      $_ = app_est::lis("hol.ton", $est_ope, $ope );
      break;
    // tonos : aventura de la onda encantada 
    case 'ton_ond':
      $_atr = array_merge([ 
        'ima'=>api_obj::atr(['ide'=>'ima','nom'=>''])
        ], api_dat::atr('hol',"ton", [ 'ide','ond_pos','ond_pod','ond_man' ])
      );
      // cargo valores
      foreach( ( $_dat = api_obj::atr(api_hol::_('ton')) ) as $_ton ){
        $_ton->ima = [ 'htm'=>api_hol::ima("ton",$_ton) ];
        $_ton->ide = "Tono {$_ton->ide}";
      }
      // cargo titulos
      $ond = 0;
      $_tit = [];
      foreach( $_dat as $lis_pos => $_ton ){
        if( $_ton->ond_enc != 0 && $ond != $_ton->ond_enc ){              
          $_ond = api_hol::_('ton_ond',$ond = $_ton->ond_enc);
          $_tit[$lis_pos] = $_ond->des;
        }
      }

      $_ = app_est::lis($_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit, 'opc'=>['cab_ocu'] ],$ope);              
      break;
          
    // tonos : pulsares dimensionales
    case 'ton_dim':
      foreach( api_hol::_('ton_dim') as $_dat ){ $htm = "
        <p>
          <n>{$_dat->ide}</n><c>.</c> <b class='ide'>Pulsar de la {$_dat->pos} dimensión</b><c>:</c> <b class='val'>Dimensión {$_dat->nom}</b>
          <br>Tonos ".app::let("{$_dat->ton}: {$_dat->ond}")."
        </p>
        <div class='fic ite'>
          ".api_hol::ima("ton_dim",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
            foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= api_hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "
          <c class='_lis fin'>}</c>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // tonos : pulsares matiz
    case 'ton_mat':
      foreach( api_hol::_('ton_mat') as $_dat ){ $htm = "
        <p><n>{$_dat->ide}</n><c>.</c> <b class='ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='val'>".app::let($_dat->cod)."</b><c>:</c>
          <br>Tonos ".app::let("{$_dat->ton}: {$_dat->ond}")."
        </p>
        <div class='fic ite'>
          ".api_hol::ima("ton_mat",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
            foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= api_hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "              
          <c class='_lis fin'>}</c>
        </div>";
        $_ []= $htm;
      }        
      break;
    // sello : colocacion armónica => razas raíz cósmica
    case 'sel_arm_raz':
      $sel = 1;
      foreach( api_hol::_($ide) as $_dat ){
        $_raz_pod = api_hol::_('sel',$_dat->ide)->pod; 
        $htm = "
        <p class='tit'>Familia <b class='let_col-4-{$_dat->ide}'>{$_dat->nom}</b><c>:</c> de la <b class='ide'>Raza Raíz ".api_tex::let_min($_dat->nom)."</b></p>
        <p>Los {$_dat->pod}dores<c>.</c> Nota clave<c>:</c> ".explode(' ',$_raz_pod)[1]."</p>
        <ul class='ite'>";
        foreach( api_hol::_('sel_arm_cel') as $lis_pos ){
          $_sel = api_hol::_('sel',$sel); $htm .= "
          <li>
            ".api_hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
            <p>
              <n>{$lis_pos->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
              <br><q>".app::let($_sel->arm_raz_des)."</q>
            </p>
          </li>";
          $sel += 4;
          if( $sel > 20 ) $sel -= 20;                  
        }
        $htm.="
        </ul>
        <q>".app::let(api_tex::let_pal($_raz_pod)." ha sido ".api_tex::art_gen("realizado",$_raz_pod).".")."</q>";
        $_ []= $htm;
      }        
      break;
    // sello : colocacion armónica => células del tiempo
    case 'sel_arm_cel':
      $lis_pos = 1;

      foreach( api_hol::_($ide) as $_dat ){ $htm = "
        <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='ide'>{$_dat->nom}</b></p>
        <q>".app::let($_dat->des)."</q>
        <ul class='ite'>";
        foreach( api_hol::_('sel_arm_raz') as $cro ){ $_sel = api_hol::_('sel',$lis_pos); $htm .= "
          <li>
            ".api_hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
            <p>
              <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
              <br><q>".app::let($_sel->arm_cel_des)."</q>
            </p>
          </li>";
          $lis_pos ++;
        }$htm .= "
        </ul>";
        $_ []= $htm;
      }           
      break;
    // sello : colocacion cromática => clanes galácticos
    case 'sel_cro_ele':
      $sel = 20;      
      foreach( api_hol::_($ide) as $_dat ){
        $ele_nom = explode(' ',$_dat->nom)[1]; $htm = "
        <p class='tit'><b class='ide'>Clan ".api_tex::art_del($_dat->nom)."</b>".app::let(": Cromática {$_dat->col}.")."</p>
        ".( !empty($_dat->des_ini) ? "<p>".app::let($_dat->des_ini)."</p>" : '' )."
        <ul class='ite'>";
        for( $fam=1; $fam<=5; $fam++ ){ 
          $_sel = api_hol::_('sel',$sel); 
          $_fam = api_hol::_('sel_cro_fam',$fam); $htm .= "
          <li sel='{$_sel->ide}' cro_fam='{$fam}'>
            ".api_hol::ima("sel",$_sel,[ 'class'=>"mar_der-1" ])."
            <p>
              <n>{$sel}</n><c>.</c> <b class='ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
              <br><q>".app::let($_sel->cro_ele_des)."</q>
            </p>
          </li>";
          $sel++;
          if( $sel > 20 ) $sel -= 20;
        }$htm .= "
        </ul>";
        $_ []= $htm;
      }          
      break;
    // sello : colocacion cromática => familias terrestres
    case 'sel_cro_fam':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_('uni_pla_cen') as $_pla ){
        $_hum = api_hol::_('uni_hum_cen',$_pla->ide);
        $_fam = api_hol::_($ide,$_pla->fam);
        $htm = 
        api_hol::ima("uni_pla_cen",$_pla,['class'=>"mar_der-2",'style'=>"min-width: 9rem; height:7rem;"])."
        <div>
          <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> {$_fam->mis}</p>
          <div class='val fic mar-2'>
            ".api_hol::ima("uni_hum_cen",$_hum)."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_fam->sel) as $sel ){
                $htm .= api_hol::ima("sel",$sel,['class'=>"mar_hor-2"]);
              }$htm .= "
            <c class='_lis fin'>}</c>
          </div>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // sello : holon solar => celulas solares y planentas
    case 'uni_sol_cel': 
      $orb = 0;
      $pla = 10;
      $sel = 20;
      $val_sel = empty( $val = isset($ope['val']) ? $ope['val'] : [] );

      foreach( api_hol::_($ide) as $_dat ){
        if( $val_sel || in_array($_dat->ide,$val) ){ 
          $htm = "
          <p class='tit'><b class='ide'>{$_dat->nom}</b><c>:</c> Célula Solar ".api_num::dat($_dat->ide,'nom')."<c>.</c></p>                  
          <ul est='sol_pla'>";
          for( $sol_pla=1; $sol_pla<=2; $sol_pla++ ){
            $_pla = api_hol::_('uni_sol_pla',$pla);
            $_sel = api_hol::_('sel',$sel);
            $_par = api_hol::_('sel',$_sel->par_ana); 
            if( $orb != $_pla->orb ){
              $_orb = api_hol::_('uni_sol_orb',$orb = $_pla->orb); $htm .= "
              <li>Los <n>5</n> <b class='ide'>planetas {$_orb->nom}es</b><c>:</c> ".app::let($_orb->des)."</li>";                        
            }
            $htm .= "
            <li>
              <p><b class='ide'>{$_pla->nom}</b><c>,</c> <n>{$pla}</n><c>°</c> órbita<c>:</c></p>
              <div class='ite'>

                ".api_hol::ima("uni_sol_pla",$_pla,['class'=>"mar_der-1"])."

                <ul class='ite' est='sel'>
                  <li>
                    ".api_hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
                    <p>
                      <b class='val'>Dentro</b><c>:</c> Sello Solar <n>{$_sel->ide}</n>
                      <br><q>".app::let($_sel->sol_pla_des)."</q>
                    </p>
                  </li>
                  <li>
                    ".api_hol::ima("sel",$_par,['class'=>"mar_der-1"])."
                    <p>
                      <b class='val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                      <br><q>".app::let($_par->sol_pla_des)."</q>
                    </p>
                  </li>
                </ul>
              </div>
            </li>";                    
            $pla--;
            $sel++;
            if( $sel > 20 ) $sel=1;
          } $htm .= "
          </ul>";
          $_ []= $htm;
        }
      }        
      break;
    // sello : holon planetario => centros planetarios
    case 'uni_pla_cen': 
      $ope['lis'] = ['class'=>"ite"];

      $_fam_sel = [
        1=>[  5, 10, 15, 20 ],
        2=>[  1,  6, 11, 16 ],
        3=>[ 17,  2,  7, 12 ],
        4=>[ 13, 18,  3,  8 ],
        5=>[  9, 14, 19,  4 ]
      ]; 
      $lis_pos = 1;
      foreach( api_hol::_('uni_pla_cen') as $_dat ){
        $_fam = api_hol::_('sel_cro_fam',$_dat->fam); $htm= "
        <div class='val'>
          ".api_hol::ima("sel_cro_fam",$_fam,['class'=>"mar_der-1"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
          foreach( $_fam_sel[$_dat->ide] as $sel ){
            $htm .= api_hol::ima("sel",$sel,['class'=>"mar_hor-1"]);
          }$htm.="
          <c class='_lis fin'>}</c>
          <c class='sep'>:</c>
        </div>
        <p>
          <n>{$_dat->ide}</n><c>.</c> El Kin <b class='ide'>{$_fam->nom}</b><c>:</c>
          <br><q>{$_dat->fun} desde el {$_dat->nom}</q>
        </p>";
        $_ []= $htm;
      }        
      break;
    // sello : holon planetario => rol de familias terrestres
    case 'uni_pla_pos':
      $_fam_sel = [
        1=>[ 20,  5, 10, 15 ],
        2=>[  1,  6, 11, 16 ],
        3=>[ 17,  2,  7, 12 ],
        4=>[ 18,  3,  8, 13 ],
        5=>[ 14, 19,  4,  9 ]
      ];
      foreach( api_hol::_('uni_pla_cen') as $_dat ){
        $_fam = api_hol::_('sel_cro_fam',$_dat->fam);
        $htm = "
        <div class='tex_ali-cen'>
          <p>Kin <b class='ide'>{$_fam->nom}</b></p>
          ".api_hol::ima("uni_pla_cen",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 17rem; height: 11rem;"])."
        </div>
        <ul class='ite'>";
          foreach( $_fam_sel[$_dat->ide] as $sel ){
            $_sel = api_hol::_('sel',$sel);
            $_pla_mer = api_hol::_('uni_pla_mer',$_sel->pla_mer);
            $_pla_hem = api_hol::_('uni_pla_hem',$_sel->pla_hem);
            $htm .= "
            <li>
              ".api_hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
              <p>
                Sello <n>{$_sel->ide}</n><c>:</c> {$_sel->nom}<c>,</c>
                <br><n>".intval($_sel->pla_hem_cod)."</n><c>°</c> {$_pla_hem->nom}<c>,</c> <n>".intval($_sel->pla_mer_cod)."</n><c>°</c> {$_pla_mer->nom}
              </p>
            </li>";
          }$htm .= "
        </ul>";
        $_ []= $htm;
      }        
      break;
    // sello : holon humano => colocacion cromática
    case 'uni_hum_ele': 
      $ele_tit = []; $lis_pos = 0; $col = 4;
      foreach( api_hol::_('uni_hum_ext') as $_ext ){
        $_ele = api_hol::_('sel_cro_ele',$_ext->ele); 
        $nom = explode(' ',api_tex::art_del($_ele->nom)); $cla = array_pop($nom); $nom = implode(' ',$nom);
        $ele_tit[$lis_pos] = [ 
          'eti'=>"div", 'class'=>"ite", 'htm'=> api_hol::ima("uni_hum_ext",$_ext,['class'=>"mar_der-1"])."                  
          <p class='tit tex_ali-izq'><b class='ide'>$_ext->nom</b><c>:</c>
            <br>Clan {$nom} <c class='let_col-4-$col'>{$cla} $_ele->col</c></p>" 
        ];
        $lis_pos += 5; 
        $col = api_num::ran($col+1,4);
      }
      $sel_lis = [];
      foreach( api_hol::_('sel_cod') as $_sel ){
        $_fam = api_hol::_('sel_cro_fam',$_sel->cro_fam);
        $_hum_ded = api_hol::_('uni_hum_ded',$_fam->hum_ded);
        $sel_lis []= api_obj::atr([ 
          'hum_ded'=>$_hum_ded->nom, 
          'nom'=>"Tribu ".api_tex::art_del($_sel->nom)." $_sel->nom_col", 
          'ima_nom'=>[ 'htm'=>api_hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
          'nom_cod'=>$_sel->nom_cod,
          'ima_cod'=>[ 'htm'=>api_hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ]
        ]);
      }
      $_ = app_est::lis($sel_lis,[ 'tit'=>$ele_tit, 'opc'=>['cab_ocu'] ]);
      break;
    // sello : holon humano => rol de familias terrestres
    case 'uni_hum_fam':
      $fam_tit = [];
      $sel_lis = [];

      foreach( api_hol::_('uni_hum_ded') as $_ded ){
        $_fam = api_hol::_('sel_cro_fam',$_ded->fam);
        $fam_tit[$lis_pos] = [
          'eti'=>"div", 'class'=>"ite", 'htm'=> api_hol::ima("uni_hum_ded",$_ded,['class'=>"mar_der-1"])."                  
          <p class='tit tex_ali-izq'><b class='ide'>Familia Terrestre $_fam->nom</b><c>:</c>
            <br>Familia de $_fam->cod<c>:</c> Dedos {$_ded->nom}".( in_array($_ded->nom,['Anular','Pulgar']) ? "es" : "s" )." </p>" 
        ];
        $lis_pos += 4;
        foreach( explode(', ',$_fam->sel) as $_sel ){
          $_sel = api_hol::_('sel',$_sel);
          $_hum_ext = api_hol::_('uni_hum_ext',$_sel->hum_ext);
          $sel_lis []= api_obj::atr([
            'nom'=>"Tribu ".api_tex::art_del($_sel->nom)." $_sel->nom_col", 
            'ima_nom'=>[ 'htm'=>api_hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
            'nom_cod'=>$_sel->nom_cod,
            'ima_cod'=>[ 'htm'=>api_hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ],
            'hum_ext'=>$_hum_ext->nom
          ]);
        }
      }

      $_ = app_est::lis($sel_lis,[ 'tit'=>$fam_tit, 'opc'=>['cab_ocu'] ]);
      break;
    // sello : holon humano => extremidades del humano
    case 'uni_hum_ext':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_($ide) as $_dat ){
        $_ele = api_hol::_('sel_cro_ele',$_dat->ele); $_ []= "

          ".api_hol::ima("uni_hum_ext",$_dat,['class'=>"mar_der-1"])."

          <p><b class='ide'>Cromática ".api_tex::art_del($_ele->nom)."</b><c>:</c>
            <br>{$_dat->nom}
          </p>";
      }        
      break;
    // sello : holon humano => dedos del humano
    case 'uni_hum_ded':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_($ide) as $_dat ){
        $_fam = api_hol::_('sel_cro_fam',$_dat->fam); $_ []= "

          ".api_hol::ima("uni_hum_ded",$_dat,['class'=>"mar_der-1"])."

          <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
            <br>{$_dat->nom}
          </p>";
      }        
      break;
    // sello : holon humano => centros galácticos del humano
    case 'uni_hum_cen':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_($ide) as $_dat ){
        $_fam = api_hol::_('sel_cro_fam',$_dat->fam); 
        $_hum = api_hol::_('uni_hum_cen',$_fam->hum_cen);
        $_ []= "

        ".api_hol::ima("uni_hum_cen",$_dat,['class'=>"mar_der-1"])."

        <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
          <br>".api_tex::art($_dat->nom)." <c>-></c> {$_hum->fun}
        </p>";
      }            
      break;
    // encantamiento : libro del kin        
    case 'kin':
      $_ = "
      <!-- libro del kin -->
      <form class='inf' esq='hol' est='$ide'>

        <div class = 'val'>

          <fieldset class='val'>

            ".app::val_ope()."

            ".app::var('atr',"hol.kin.ide",[ 'nom'=>"ver el kin", 'ope'=>[ 
              'title'=>"Introduce un número de kin...", 'oninput'=>"{$_eje}this);" 
            ]])."
          </fieldset>

          <fieldset class='ope'>
            ".app::ico('dat_fin',[ 'eti'=>"button", 'type'=>"reset", 'title'=>"Vaciar Casillero...", 'onclick'=>"{$_eje}this,'fin');" ])."
            ".app::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"{$_eje}this,'nav');" ])."
          </fieldset>

        </div>

        <output class='hol-kin'></output>
        
      </form>
              
      <nav>";
        $_nav_cas = 0; $_nav_ond = 0;
        $arm_tra = 0; $arm_cel = 0;
        $cro_est = 0; $cro_ele = 0;
        $gen_enc = 0; $gen_cel = 0;
        foreach( api_hol::_('kin') as $_kin ){

          // castillo
          if( $_kin->nav_cas != $_nav_cas ){
            $_nav_cas = $_kin->nav_cas;
            $_cas = api_hol::_('kin_nav_cas',$_kin->nav_cas); 
            if( $_nav_cas != 1 ){ $_ .= "
                </section>

              </section>
              ";
            }$_ .= "
            ".app::val(['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."
            <section data-kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>
              <p cas='{$_cas->ide}'>".app::let("Corte {$_cas->cor}: {$_cas->fun}")."</p>
            ";
          }
          // génesis
          if( $_kin->gen_enc != $gen_enc ){
            $gen_enc = $_kin->gen_enc;
            $_gen = api_hol::_('kin_gen_enc',$_kin->gen_enc); $_ .= "
            <p class='tit' data-gen='{$_gen->ide}'>GÉNESIS ".api_tex::let_may($_gen->nom)."</p>";
          }
          // onda encantada
          if( $_kin->nav_ond != $_nav_ond ){
            $_nav_ond = $_kin->nav_ond;
            $_ond = api_hol::_('kin_nav_ond',$_kin->nav_ond);
            $_sel = api_hol::_('sel',$_ond->sel); 
            $ond = api_num::ran($_ond->ide,4);

            if( $_nav_ond != 1 && $ond != 1 ){ $_ .= "
              </section>";
            }
            $_ .= "
            ".app::val([
              'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 
              'htm'=> app::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
            ])."
            <section data-kin_nav_ond='{$_ond->ide}'>
              <p class='let-enf' ond='{$_ond->ide}'>Poder ".api_tex::art_del($_sel->pod)."</p>";
          }
          // célula armónica : titulo + lectura
          if( $_kin->arm_cel != $arm_cel ){
            $arm_cel = $_kin->arm_cel;
            $_cel = api_hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
            </section>

            ".app::val([
              'eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,
              'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>".app::let(api_tex::let_may($_cel->des))
            ])."
            <section data-kin_arm_cel='{$_cel->ide}'>
            ";
          }
          // kin : ficha + nombre + encantamiento
          $_ .= "
          <div data-kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
            <div class='hol-kin'>
              ".api_hol::ima("kin",$_kin->ide,['class'=>'mar-aut'])."
              <p>
                <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='let_col-4-{$_kin->arm_cel_dia}'>".app::let(api_tex::let_may($_kin->nom))."</c>
                <br><q>".app::let($_kin->des)."</q>                  
              </p>
            </div>
          </div>";
        }$_ .= "
        </section>
      </nav>";        
      break;
    // encantamiento : índice armónico de 13 trayectorias y 65 células
    case 'kin_arm':
      $arm_cel = 0;
      $_lis = [];
      if( !isset($ope['nav']) ) $ope['nav'] = [];

      foreach( api_hol::_('kin_arm_tra') as $_tra ){

        $_lis_cel = [];
        foreach( api_hol::_('sel_arm_cel') as $_cel ){
          $arm_cel++;
          $_cel = api_hol::_('kin_arm_cel',$arm_cel); $_lis_cel []= "
          <a class='tex' href='#kin_arm_cel-{$_cel->ide}-'>
            <n>{$_cel->ide}</n><c>.</c> <b class='ide'>{$_cel->nom}</b>".app::let(": {$_cel->des}")."
          </a>";
        }
        $_lis []= [
          'ite'=>"Trayectoria $_tra->ide: Tono $_tra->ton, $_tra->ton_des",
          'lis'=>$_lis_cel
        ];
      }
      api_ele::cla( $ope['nav'], "dis-ocu" );
      $ope['opc'] = ['tog','ver'];
      $_ = "

      ".app::val(
        [ 'eti'=>'h3', 'htm'=> app::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], 
        [ 'ico'=>['class'=>"ocu"] ]
      )."
      <nav".api_ele::atr($ope['nav']).">

        ".app_lis::val($_lis,$ope)."

      </nav>";    
      break;        
    // kin : espectros galácticos
    case 'kin_cro':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('kin_cro_est') as $_est ){ 
        $_sel = api_hol::_('sel',$_est->sel); $_ []= "
        ".api_hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
        <p>
          <n>{$_est->ide}</n><c>.</c> El espectro galáctico <b class='let_col-4-{$_est->ide}'>{$_est->col}</b><c>:</c> 
          Estación ".api_tex::art_del($_sel->nom)."
        </p>";
      }          
      break;
    // kin : aventura por guardián
    case 'kin_cro_sel':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('kin_cro_ond') as $_ond ){ $_ []= "
        ".api_hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
        <p>
          Tono <n>".intval($_ond->ton)."</n> de la familia terrestre polar<c>:</c> 
          {$_ond->nom} <n>1</n> de los <n>4</n> Espectros Galácticos<c>.</c>
        </p>";
      }
      break;
    // kin : aventura por estaciones
    case 'kin_cro_ton':
      $ope['lis'] = ['class'=>"ite"];
      foreach( api_hol::_('kin_cro_ond') as $_ond ){ $_ []= "
        ".api_hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
        <p>
          Tono <n>".intval($_ond->ton)."</n><c>:</c> 
          {$_ond->nom} <n>".($_ond->cue*5)."</n> Kines <c>(</c> <n>{$_ond->cue}</n> cromática".( $_ond->cue > 1 ? "s" : "")." <c>)</c>
        </p>";
      }            
      break;
    // kin : génesis
    case 'kin_gen':
      $_ = [
        "<b class='ide'>Génesis del Dragón</b><c>:</c> <n>13.000</n> años del Encantamiento del Sueño<c>,</c> poder del sueño<c>.</c>",
        "<b class='ide'>Génesis del Mono</b><c>:</c> <n>7.800</n> años del Encantamiento del Sueño<c>,</c> poder de la magia<c>.</c>",
        "<b class='ide'>Génesis de la Luna</b><c>:</c> <n>5.200</n> años del Encantamiento del Sueño<c>,</c> poder del vuelo mágico<c>.</c>",
      ];     
      break;
    // kin : ondas y castillos con células del génesis
    case 'kin_nav':
      $gen = 0;
      $cel = 0;
      $cas = 0;
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_('kin_nav_ond') as $_ond ){
        // génesis
        if( $gen != $_ond->gen_enc ){ 
          $_gen = api_hol::_('kin_gen_enc',$gen = $_ond->gen_enc); $_[]="
          <p class='tit'>{$_gen->lec}<c>:</c> <b class='ide'>Génesis {$_gen->nom}</b><c>.</c></p>";
        }
        if( $cel != $_ond->gen_cel ){ 
          $_cel = api_hol::_('kin_gen_cel',$cel = $_ond->gen_cel); $_[]="
          <p class='tit'>Célula <n>{$_cel->ide}</n> de la memoria del Génesis<c>:</c> <b class='val'>{$_cel->nom}</b></p>";
        }
        if( $cas != $_ond->nav_cas ){ 
          $_cas = api_hol::_('kin_nav_cas',$cas = $_ond->nav_cas); $_[]="
          <p class='tit'>
            El Castillo <b class='let_col-5-{$_cas->ide}'>".str_replace('del ','',$_cas->nom)."</b> ".api_tex::art_del($_cas->acc)."<c>:</c> La corte ".api_tex::art_del($_cas->cor)."<c>,</c> poder {$_cas->pod}
          </p>";
        }              
        $_ []= api_hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."              
        <p><n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c> <q>".app::let($_ond->enc_des)."</q></p>";
      }          
      break;
    // psi : por tonos galácticos
    case 'psi_lun':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_($ide) as $_lun ){
        $_ []= api_hol::ima("ton",$_lun->ton,['class'=>"mar_der-2"])."
        <p>
          <b class='ide'>".api_tex::let_pal(api_num::dat($_lun->ide,'pas'))." Luna</b>
          <br>Luna ".api_tex::art_del($_lun->ton_car)."<c>:</c> ".app::let($_lun->ton_pre)."
        </p>";
      }    
      break;        
    // psi : fechas desde - hasta
    case 'psi_lun_fec':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_('psi_lun') as $_lun ){
        $_[] = api_hol::ima("ton",$_lun->ton,['class'=>"mar_der-3"])."
        <p>
          <b class='ide'>$_lun->nom</b> <n>".intval($_lun->ton)."</n>
          <br>".app::let($_lun->fec_ran)."
        </p>";
      }$_[] = "
      <span ima></span>
      <p>
        <b class='ide'>Día Verde</b> o Día Fuera del Tiempo
        <br><n>25</n> de Julio
      </p>";        
      break;
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // 13 Lunas en Movimiento
  static function lun( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // luna : heptadas - cuarto armónica
    case 'lun_arm':
      if( isset($_atr[1]) ){
        switch( $_atr[1] ){
        // descripcion
        case 'des': 
          foreach( api_hol::_('lun_arm') as $_hep ){
            $_ []= app::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>".app::let("): $_hep->des");
          }
          break;
        case 'pod':
          foreach( api_hol::_('lun_arm') as $_hep ){
            $_ []= app::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>".app::let(", $_hep->pod $_hep->car");
          }        
          break;            
        }
      }
      break;
    // luna : heptadas lunares
    case 'lun_arm_col':
      $est_ope['atr'] = [ 'ide','nom','col','dia','pod' ];
      $est_ope['opc'] []= 'cab_ocu';
      $_ = app_est::lis("hol.lun_arm", $est_ope, $ope );
      break;
    // kin : castillos del encantamiento
    case 'kin_nav_cas':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_($ide) as $_cas ){ $_ [] = 

        api_hol::ima($ide,$_cas,['class'=>"mar_der-2"])."

        <p>
          <b class='ide'>Castillo $_cas->col $_cas->dir ".api_tex::art_del($_cas->acc)."</b><c>:</c>
          <br>Ondas Encantadas ".app::let($_cas->nav_ond)."
        </p>";
      }          
      break;              
    // psi : totems lunares
    case 'psi_lun_tot':
      $ope['lis'] = ['class'=>"ite"];
      $_lis = [
        1=>"El <b class='ide'>Murciélago Magnético</b> va con la Primera Luna",
        2=>"El <b class='ide'>Escorpión Lunar</b> va con la Segunda Luna",
        3=>"El <b class='ide'>Venado Eléctrico</b> va con la Tercera Luna",
        4=>"El <b class='ide'>Búho Auto<c>-</c>Existente</b> va con la Cuarta Luna",
        5=>"El <b class='ide'>Pavo Real Entonado</b> va con la Quinta Luna",
        6=>"El <b class='ide'>Lagarto Rítmico</b> va con la Sexta Luna",
        7=>"El <b class='ide'>Mono Resonante</b> va con la Séptima Luna",
        8=>"El <b class='ide'>Halcón Galáctico</b> va con la Octava ",
        9=>"El <b class='ide'>Jaguar Solar</b> va con la Novena Luna",
        10=>"El <b class='ide'>Perro Planetario</b> va con la Décima Luna",
        11=>"La <b class='ide'>Serpiente Espectral</b> va con la Undécima Luna",
        12=>"El <b class='ide'>Conejo Cristal</b> va con la Duodécima Luna",
        13=>"La <b class='ide'>Tortuga Cósmica</b> va con la Decimotercera Luna"
      ];

      foreach( $_lis as $ite => $htm ){
        $_ []= api_hol::ima("psi_lun",$_lun = api_hol::_('psi_lun',$ite),['class'=>"mar_der-2"])."
        <p>
          $htm
          <br>".app::let($_lun->fec_ran)."
        </p>";
      }
      $_ []= "
      <p>
        Día fuera del tiempo
        <br><n>25</n> de Julio
      </p>";        
      break;
    // anillo : años (desde-hasta) por anillos solares
    case 'ani':
      $ini = 1992;
      $cue = 8;
      $_[] = "
      <b class='ide'>Año Uno</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',39),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio  <n>1.992</n> <c>-</c> <n>25</n> Julio <n>1.993</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Dos</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',144),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.993</n> <c>-</c> <n>25</n> Julio <n>1.994</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Tres</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',249),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.994</n> <c>-</c> <n>25</n> Julio <n>1.995</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Cuatro</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',94),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.995</n> <c>-</c> <n>25</n> Julio <n>1.996</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Cinco</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',199),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.996</n> <c>-</c> <n>25</n> Julio <n>1.997</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Seis</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',44),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.997</n> <c>-</c> <n>25</n> Julio <n>1.998</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Siete</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',149),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.998</n> <c>-</c> <n>25</n> Julio <n>1.999</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Ocho</b>
      <div class='ite'>
        ".api_hol::ima("kin",$_kin = api_hol::_('kin',254),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.999</n> <c>-</c> <n>25</n> Julio <n>2.000</n><c>.</c></p>
      </div>";
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Sonda de Arcturus
  static function arc( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Tratado del Tiempo
  static function tie( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // campos planetarios por agrupacion
    case 'pla_cam': 
      $_ = "
      <ul>
        <li>el gravitacional <c>(</c>las Cuatro Razas Raíz<c>)</c><c>,</c> </li>
        <li>el electromagnético <c>(</c>los Cuatro Clanes<c>)</c> </li>
        <li>y el biopsíquico <c>(</c>las Cinco Familias Terrestres<c>)</c><c>.</c></li>
      </ul>";
      break;
    // 
    case 'pla_cam.nom':
      $_ = "      
      <dl>
        <dt>el campo electromagnético</dt>
        <dd><c>(</c>magnetosfera y cinturores de radiación<c>,</c> incluyendo la ionosfera<c>)</c><c>,</c> </dd>
        <dt>el campo biopsíquico</dt>
        <dd> <c>(</c>biosfera incluyendo la simbiosis de eco<c>-</c>ciclos que integran el <c>\"</c>corpus inerte<c>\"</c> con el <c>\"</c>vivo<c>\"</c><c>)</c><c>,</c></dd>
        <dt>y el campo gravitacional</dt>
        <dd><c>(</c>incluyendo la estructura de placas tectónicas<c>,</c> mantos y núcleo de la Tierra<c>)</c><c>.</c></dd>
      </dl>";
      break; 
    //
    case 'pla_cam.des': 
      $_ = "
      <ul>
        <li>el campo electromagnético se reconstituye psicofísicamente a través de los sentidos<c>;</c> </li>
        <li>el campo bio<c>-</c>psíquico se reorganiza como orden cósmico telepático de la sociedad humana indistinguible de los órdenes vivos de la naturaleza<c>,</c> </li>
        <li>y el campo gravitacional es conducido a un nuevo nivel de equilibrio a través de una vibrante correlación y simbiosis de los dos órdenes geoquímicos tridimensionales<c>,</c> SiO<sup>2</sup> <c>(</c>dióxido de silicio<c>)</c> y CO<sup>2</sup> <c>(</c>dióxido de carbono<c>)</c><c>.</c></li>
      </ul>";
      break;
    // kin : trayectorias + castillos
    case 'kin':
      $_ = "
      <dl>
        <dt>Célula del Tiempo Uno <c class='col-roj'>Roja</c><c>,</c> Entrada<c>:</c></dt>      
        <dd>Informar el girar<c>,</c> iniciar el nacimiento de la semilla</dd>
        <dt>Célula del Tiempo Dos <c class='col-bla'>Blanca</c><c>,</c> Almacén<c>:</c></dt>
        <dd>Recordar el cruzar<c>,</c> refinar la muerte del guerrero</dd>
        <dt>Célula del Tiempo Tres <c class='col-azu'>Azul</c><c>,</c> Proceso<c>:</c></dt>
        <dd>Formular el quemar<c>,</c> transformar la magia de la estrella</dd>
        <dt>Célula del Tiempo Cuatro <c class='col-ama'>Amarilla</c><c>,</c> Salida<c>:</c></dt>
        <dd>Expresar el dar<c>,</c> madurar la inteligencia del sol</dd>
        <dt>Célula del Tiempo Cinco <c class='col-ver'>Verde</c><c>,</c> Matriz<c>:</c></dt>
        <dd>Auto<c>-</c>regular el encantamiento<c>,</c> sincronizar el libre albedrío del humano</dd>
      </dl>";
      break;
    // psi : vinales
    case 'psi_vin':
      $est_ope['atr'] = ['ide','nom','fec','sin','cro'];
      $est_ope['det_des'] = ['des'];
      //$ope['lis']['class'] = "anc-100 mar-2";
      $_ = app_est::lis("hol.{$ide}", $est_ope, $ope);

      break;
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Telektonon
  static function tel( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // libros-cartas
    case 'fic-lib':
      $_dat = [
        4  => ['ide'=> 4, 'nom'=>"Libro de la Forma Cósmica" ],
        7  => ['ide'=> 7, 'nom'=>"Libro de las Siete Generaciones Perdidas" ],
        13 => ['ide'=>13, 'nom'=>"Libro del Tiempo Galáctico" ],
        28 => ['ide'=>28, 'nom'=>"Libro Telepático para la Redención de los Planetas Perdidos" ]
      ];
      $ide = isset($ope['ide']) ? $ope['ide'] : 4;
      $opc = isset($ope['opc']) ? $ope['opc'] : [];
      $opc_ini = empty($opc) || in_array('ini',$opc);
      $opc_fin = empty($opc) || in_array('fin',$opc);
      if( !$opc_ini && !$opc_fin ) $opc_ini = $opc_fin = TRUE;
      foreach( ( isset($ope['lis']) && is_array($ope['lis']) ? $ope['lis'] : range(1,$ide) ) as $pos ){ 
        $htm = "
        <div class='ite jus-cen'>";
          if( $opc_ini ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta {$pos}-1' class='mar_der-1' style='width:24rem;'>";
          if( $opc_fin ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta {$pos}-2' class='mar_izq-1' style='width:24rem;'>";
          $htm .= "
        </div>";
        $_ []= $htm;
      }
      $_ = app_lis::bar( $_, $ope);          
      break;
    // sello : holon solar => circuitos de telepatía
    case 'uni_sol_cir':
      $ope['lis'] = ['class'=>"ite"];

      foreach( api_hol::_($ide) as $_cir ){
        $pla = explode('-',$_cir->pla);
        $_pla_ini = api_hol::_('uni_sol_pla',$pla[0]);
        $_pla_fin = api_hol::_('uni_sol_pla',$pla[1]);
        $htm = 
        api_hol::ima($ide,$_cir,['class'=>""])."
        <div>
          <p class='tit'>Circuito <n>$_cir->ide</n><c>:</c> <b class='ide'>$_pla_ini->nom <c>-</c> $_pla_fin->nom</b></p>
          <ul>
            <li>Circuito ".app::let($_cir->nom)."</li>
            <li><p>".app::let("$_cir->cod unidades - $_cir->des")."</p></li>
            <li><p>Notación Galáctica<c>,</c> números de código ".app::let("{$_cir->sel}: ");
            $lis_pos = 0;
            foreach( explode(', ',$_cir->sel) as $sel ){ 
              $lis_pos++; 
              $_sel = api_hol::_('sel', $sel == 00 ? 20 : $sel);                      
              $htm .= app::let( $_sel->pod_tel.( $lis_pos == 3 ? " y " : ( $lis_pos == 4 ? "." : ", " ) ) );
            } $htm .= "
            </p></li>
          </ul>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // luna : por poderes
    case 'lun_arm': 
      foreach( api_hol::_($ide) as $_hep ){
        $_ []= app::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>".app::let(", $_hep->pod $_hep->car");
      }        
      break;              
    // luna : lines de fuerza
    case 'lun_fue': 
      foreach( api_hol::_($ide) as $_lin ){
        $_ []= app::let("{$_lin->nom}: {$_lin->des}");
      }
      break;
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Proyecto Rinri
  static function rin( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // plasma : años por oráculos de la profecía
    case 'rad_ani': 
      $ope['lis'] = ['class'=>"ite"];
      $ope['ite'] = ['class'=>"mar_aba-1"];      

      foreach( api_hol::_('rad') as $_rad ){ $_ []=
        api_hol::ima("rad",$_rad,['class'=>"mar_der-1"])."
        <p>
          <b class='ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
          <br><q>".app::let($_rad->rin_des)."<c>.</c></q>
        </p>";
      }
      $_ = app_lis::val($_,$ope);
      break;                    
    // luna : días del cubo
    case 'lun_cub':
      foreach( api_hol::_($ide) as $_cub ){
        $_ []= 
        "<div class='ite'>
          ".api_hol::ima("sel",$_cub->sel,['class'=>"mar_der-1"])."              
          <div>
            <p class='tit'>Día <n>$_cub->lun</n><c>,</c> CUBO <n>$_cub->ide</n><c>:</c> $_cub->nom</p>
            <p class='des'>$_cub->des</p>
          </div>              
        </div>
        <p class='let-enf tex_ali-cen'>".app::let($_cub->tit)."</p>
        ".( !empty($_cub->lec) ? "<p class='let-cur tex_ali-cen'>".app::let($_cub->lec)."</p>" : ""  )."
        <q>".app::let($_cub->afi)."</q>";
      }        
      break;                    
    // psi-cronos : dias pag + cubo
    case 'psi_lun_dia':
      if( isset($est_ope['atr']) && is_string($est_ope['atr']) ){
        foreach( ['lis'] as $e ){ if( !isset($ope[$e]) ) $ope[$e]=[]; }
        switch( $est_ope['atr'] ){
        // días psi de cuartetos ocultos        
        case 'pag':
          $_ = "
          <table".api_ele::atr($ope['lis']).">
            <thead>
              <tr>
                <th scope='col'></th>
                <th scope='col'>Torre Día <n>1</n></th>
                <th scope='col'>Torre Día <n>6</n></th>
                <th scope='col'>Torre Día <n>23</n></th>
                <th scope='col'>Torre Día <n>28</n></th>
              </tr>
              <tr>
                <th scope='col'></th>
                <th scope='col'><c>(</c>Pareado con día <n>28</n><c>)</c></th>
                <th scope='col'><c>(</c>Pareado con día <n>23</n><c>)</c></th>
                <th scope='col'><c>(</c>Pareado con día <n>6</n><c>)</c></th>
                <th scope='col'><c>(</c>Pareado con día <n>1</n><c>)</c></th>
              </tr>
            </thead>
            <tbody>";
              foreach( api_hol::_($ide) as $_lun ){ $_ .= "
                <tr>
                  <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                  foreach( explode(', ',$_lun->kin_pag) as $kin ){ $_ .= "
                    <td>".api_hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                  }$_ .= "   
                </tr>";
              }$_ .= "
            </tbody>
          </table>";        
          break;
        // días psi del cubo - laberinto del guerrero
        case 'cub': 
          $_ = "
          <table".api_ele::atr($ope['lis']).">
            <tbody>";
              foreach( api_hol::_($ide) as $_lun ){ $_ .= "
                <tr>
                  <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                  foreach( explode('-',$_lun->kin_cub) as $kin ){ $_ .= "
                    <td>".api_hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                  }$_ .= "
                  <td>Kines ".app::let(api_tex::mod($_lun->kin_cub,"-"," - "))."</td>
                </tr>";
                if( $_lun->ide == 7 ){ $_ .= "
                  <tr>
                    <td colspan='4'>
                      <p>Perro <n>13</n><c>,</c> Kin <n>130</n> <c>=</c> <n>14</n> Luna Resonante<c>,</c> </p>
                      <p><b>Cambio de Polaridad</b></p>
                      <p>Mono <n>1</n><c>,</c> Kin <n>131</n> <c>=</c> <n>15</n> Luna Resonante</p>
                    </td>
                  </tr>";
                }
              }$_ .= "
            </tbody>
          </table>";        
          break;
        }
      }
      elseif( empty($est_ope['atr']) ){
        $est_ope['atr'] = [];
        $_ = app_est::lis("hol.lun", $est_ope, $ope );
      }
      break;
    // psi-cronos : cromaticas entonadas
    case 'psi_cro_arm':
      foreach( [ 1, 2, 3, 4 ] as $arm ){
      
        $cro_arm = api_hol::_('psi_cro_arm',$arm);

        $_ []= "Cromática <c class='let_col-4-$arm'>$cro_arm->col</c><br>".app::let("$cro_arm->nom: $cro_arm->des");
      }        
      break;      
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Dinámicas del Tiempo
  static function din( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Tablas del Tiempo
  static function tab( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Atomo del tiempo 
  static function ato( string $ide, array $ope = [] ) : string {
    $_ = [];
    foreach( ['lis'] as $ele ){ if( !isset($ope[$ele]) ) $ope[$ele] = []; }
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    $_ide = explode('-',$ide);
    switch( $ide = $_ide[0] ){
    // cartas del plasma
    case 'fic':
      switch( $_ide[1] ){
      case 'lun':
        $ide = isset($ope['ide']) && is_array($ope['ide']) ? $ope['ide'] : range(1,28);
        $opc = isset($ope['opc']) ? $ope['opc'] : [];
        $opc_ini = empty($opc) || in_array('ini',$opc);
        $opc_fin = empty($opc) || in_array('fin',$opc);
        if( !$opc_ini && !$opc_fin ) $opc_ini = $opc_fin = TRUE;
        foreach( $ide as $pos ){ 
          $cod = api_num::val($pos,2);
          $htm = "
          <div class='ite jus-cen'>";
            if( $opc_ini ) $htm .= "
            <img src='".SYS_NAV."img/hol/bib/ato/fic/{$cod}-1.gif' alt='Carta {$cod}-1' class='mar_der-1' style='width:20rem;'>";
            if( $opc_fin ) $htm .= "
            <img src='".SYS_NAV."img/hol/bib/ato/fic/{$cod}-2.gif' alt='Carta {$cod}-2' class='mar_izq-1' style='width:20rem;'>";
            $htm .= "
          </div>";
          $_ []= $htm;
        }
        $_ = app_lis::bar( $_, $ope);                 
        break;
      }        
      break;        
    // 7 plasmas radiales
    case 'rad_pla':
      $ide = "rad";
      $pla_qua = [3,4,7];
      api_ele::cla($ope['lis'],'ite');
      switch( $_ide[1] ){
      // lineas de fuerza + quantums
      case 'fue':
        foreach( api_hol::_($ide) as $rad ){
          $fue_pre = api_hol::_('rad_pla_fue',$rad->pla_fue_pre);
          $fue_pos = api_hol::_('rad_pla_fue',$rad->pla_fue_pos);
          $_ []= 
          api_hol::ima($ide,$rad,['class'=>"mar_der-2"])."
          <div>        
            <p><b class='ide'>$rad->nom</b> <b class='col-".substr($rad->col,0,3)."'>$rad->col</b></p>
            <div class='ite'>
              $fue_pre->nom
              ".api_hol::ima("rad_pla_fue",$fue_pre)."
              <c class='sep'>+</c>
              $fue_pos->nom
              ".api_hol::ima("rad_pla_fue",$fue_pos)."
              
              <p><c class='sep'>:</c> ".app::let($rad->pla_fue)." <c>(</c>Días ".app::let($rad->dia)."<c>)</c></p>
            </div>
          </div>";
          if( in_array($rad->ide,$pla_qua) ){
            $qua = api_hol::_('rad_pla_qua',$rad->pla_qua);
            $_ []= 
            api_hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".app::let($qua->ato_des)."</p>";
          }
        }          
        break;
      // afirmaciones + quantums
      case 'des': 
        foreach( api_hol::_($ide) as $rad ){
          $_ []= 
          api_hol::ima($ide,$rad,['class'=>"mar_der-2"])."
          <p>
            ".app::let("$rad->nom: $rad->pla_des.")."
            <br>
            <q>".app::let($rad->pla_lec)."</q>
          </p>";            
          if( in_array($rad->ide,$pla_qua) ){
            $qua = api_hol::_('rad_pla_qua',$rad->pla_qua);
            $_ []= 
            api_hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".app::let($qua->pla_des)."</p>";
          }
        }
        break;
      // cubo del radion + quantums
      case 'cub':
        $qua = NULL;
        $qua_ide = 0;
        foreach( api_hol::_($ide) as $rad ){
          // titulo por quantum
          if( $qua_ide != $rad->pla_qua ){
            $qua = api_hol::_('rad_pla_qua',$rad->pla_qua); 
            $qua_ide = $rad->pla_qua; $_ []= "
            <p class='tit anc-100 tex_ali-cen'>".app::let($qua->nom)."</p>";
          }
          $cub = api_hol::_('rad_pla_cub', $rad->ide);
          $cha = api_hol::_('uni_hum_cha', $rad->hum_cha);
          $_ []= 
          "<div>".              
            api_hol::ima('uni_hum_cha',$cha,['class'=>"mar_der-2"]).
            api_hol::ima('rad_pla_cub',$cub,['class'=>"mar_der-2"])."
          </div>
          <div>
            <p>".app::let("$rad->nom (Días $rad->dia): $cha->pos Chakra, $cha->cod o $cha->nom")."</p>
            <p>".app::let("Cubo del Radión - $cub->nom")."</p>
          </div>
          ";
          if( in_array($rad->ide,$pla_qua) ){              
            $_ []= 
            api_hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".app::let($qua->cub_des)."</p>";
          }
        }
        break;
      }
      break;
    // 6 tipos de electricidad
    case 'rad_pla_ele': 
      api_ele::cla($ope['lis'],'ite');
      foreach( api_hol::_($ide) as $pla_ele ){
        $_ []= 
        api_hol::ima($ide,$pla_ele,['class'=>"mar_der-2"])."
        <p>
          <b class='ide'>$pla_ele->nom</b> o <b class='ide'>$pla_ele->cod</b>
          <br>
          ".app::let($pla_ele->des)."
        </p>";
      }
      break;
    // 12 lineas de fuerza
    case 'rad_pla_fue': 
      api_ele::cla($ope['lis'],'ite');
      foreach( api_hol::_($ide) as $pla_fue ){
        $ele_pre = api_hol::_('rad_pla_ele',$pla_fue->ele_pre);
        $ele_pos = api_hol::_('rad_pla_ele',$pla_fue->ele_pos);
        $_ []= 
        api_hol::ima($ide,$pla_fue,['class'=>"mar_der-2"])."
        <div>
          <p><b class='ide'>$pla_fue->nom</b></p>
          <div class='val'>
            <b class='mar_hor-1'>$ele_pre->cod</b>
            ".api_hol::ima("rad_pla_ele",$ele_pre)."
            <c class='ope sep'>$pla_fue->ele_ope</c>
            <b class='mar_hor-1'>$ele_pos->cod</b>              
            ".api_hol::ima("rad_pla_ele",$ele_pos)."
          </div>                        
        </div>";
      }        
      break;
    // 4 atómos y 2 tetraedros
    case 'lun_pla_ato':
      switch( $_ide[1] ){
      // Atomo telepatico del tiempo
      case 'tie': 
        api_ele::cla($ope['lis'],'ite');
        $pla_tet = [2,4];
        $tet_ide = 0;
        foreach( api_hol::_($ide) as $ato ){
          $_ []= 
          api_hol::ima($ide,$ato,['class'=>"mar_der-2"])."

          <p>Semana <n>$ato->ide</n><c>:</c> Átomo Telepático del <b class='ide'>".app::let($ato->nom)."</b></p>";
          // tetraedros
          if( in_array($ato->ide,$pla_tet) ){
            $tet_ide++;
            $tet = api_hol::_('lun_pla_tet',$tet_ide); $_ []= 
            api_hol::ima('lun_pla_tet',$tet,['class'=>"mar_der-2"])."
            <p>".app::let($tet->des.".")."</p>";
          }
        }$_ []= 
        app::ima('hol/ima/lun',['class'=>"mar_der-2 tam-15"])."
        <p>También el Día <n>28</n><c>,</c> la transposición fractal de las ocho caras de los dos tetraedros resulta en la creación del Octaedro de Cristal en el centro de la Tierra<c>.</c></p>";            
        break;
      // Cargas por Colores Semanales 
      case 'car':
        api_ele::cla($ope['lis'],'ite');
        foreach( api_hol::_($ide) as $ato ){
          $col = api_hol::_('lun_arm',$ato->ide)->col;                        
          $_ []= 
          api_hol::ima($ide,$ato,['class'=>"mar_der-2"])."
          <p>
            Semana <n>$ato->ide</n><c>,</c> <b class='col-".substr($col,0,3)."'>{$col}</b>".app::let(": $ato->car".".")."
            <br>
            Secuencia ".app::let($ato->car_sec.".")."
          </p>";
        }
        break;
      // ficha semanal
      case 'hep':
        $ato = api_hol::_($ide,$ope['ide']);
        $_ = "
        <p class='tit tex_ali-izq'>".app::let("Semana $ato->ide, Heptágono de la Mente ".api_tex::art_del($ato->hep))."</p>
        <div class='ite'>
          ".api_hol::ima($ide, $ato, ['class'=>'mar_der-2'])."
          <ul class='mar_arr-0'>
            <li>".app::let("Un día = $ato->val.")."</li>
            <li>".app::let("Valor lunar = $ato->val_lun.")."</li>
            <li>".app::let("Forma $ato->hep_cub en el Holograma Cúbico 7:28.")."</li>
          </ul>                        
        </div>";
        break;          
      }
      break;
    // 4 semanas: cualidad + poder + kin
    case 'lun_arm':
      foreach( api_hol::_($ide) as $arm ){
        $ato = api_hol::_('lun_pla_ato',$arm->ide);          
        $_[]="
        <p class='tit'>$arm->nom<c>,</c> <b class='col-".substr($arm->col,0,3)."'>$arm->col</b><c>:</c></p>          
        <div class='ite'>            
          ".api_hol::ima($ide,$arm,['class'=>"mar_der-2"])."
          <ul>
            <li>".app::let($arm->des)."</li>
            <li>".app::let($arm->tel_des)."</li>
            <li>".( count(explode(', ',$ato->val_kin)) == 1 ? "Código del Kin" : "Códigos de Kines" )." ".app::let($ato->val_kin)."</li>            
          </ul>
        </div>";
      }
      break;
    // 7 tierras de ur
    case 'lun_pla_tie':
      foreach( api_hol::_($ide) as $tie ){
        $rad = api_hol::_('rad',$tie->rad);
        $_[]="
        <p class='tit tex_ali-izq'>".app::let("$tie->nom, Tierra de UR $tie->ide")."</p>
        <div class='ite'>
          ".api_hol::ima('rad',$tie->rad,['class'=>"mar_der-2"])."
          <p>
            <q>$tie->des</q>
            <br>".app::let("Día $tie->dia, $tie->tel, Tablero del Plasma.")."
            <br>".app::let("Plasma Radial $rad->ide, $rad->nom: $rad->pla_fue")."
            <br>".app::let("( $tie->pos última Luna, $tie->pos Luna Mística )")."
          </p>
        </div>";
      }
      break;
    // ejes del Cubo Primigenio y el Átomo Telepático del Tiempo
    case 'lun_pla_eje':
      api_ele::cla($ope['lis'],'ite');
      foreach( api_hol::_($ide) as $eje ){
        $tie = explode(', ',$eje->tie);
        $ini = api_hol::_('lun_pla_tie',$tie[0]);
        $fin = api_hol::_('lun_pla_tie',$tie[1]);
        $_[]=
        api_hol::ima('rad',$ini->rad,['class'=>"mar_der-2"]).
        api_hol::ima('rad',$fin->rad,['class'=>"mar_der-2"])."
        <div>
          <p class='tit'>Eje $eje->nom</p>
          <p>".app::let("{$ini->ide}° Tierra de UR $ini->nom y {$fin->ide}° Tierra de UR $fin->nom")."</p>
        </div>
        ";
      }
      break;
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Sincronotron
  static function umb( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
}

// Ficha-Valores
class hol_val {

  static function ton( mixed $val ) : void {
    $_bib = SYS_NAV."hol/bib/";
    $_art = SYS_NAV."hol/art/";
    $_ton = api_hol::_('ton',$val); ?>
    <!-- Ficha -->
    <article>

      <?= app_dat::inf('hol','ton',$_ton,[ 'opc'=>["atr"] ]) ?>

      <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    

    </article>
    <!-- Aventura de la Onda Encantada -->

    <!-- Simetría Especular -->

    <!-- Pulsares Dimensionales -->

    <!-- Pulsares Matiz Entonado -->             
    <?php          
  }

  static function sel( mixed $val ) : void {
    $_bib = SYS_NAV."hol/bib/";
    $_art = SYS_NAV."hol/art/";
    $_sel = api_hol::_('sel',$val); ?>
    <!-- Ficha -->
    <article>

      <?= app_dat::inf('hol','sel',$_sel,[ 'opc'=>[ "atr" ] ]) ?>

      <p><?= app::let($_sel->des_pro) ?></p>

      <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'></a> en </p>

    </article>
    <!-- Desarrollo del ser -->

    <!-- Colocacion Cromática -->

    <!-- Colocacion Armónica -->

    <!-- Holon Solar -->

    <!-- Holon Planetario -->

    <!-- Holon Humano -->
    <?php
  }

  static function kin( int | string | object $val ) : void {    
    $_bib = SYS_NAV."hol/bib/";
    $_art = SYS_NAV."hol/art/";
    $_kin = api_hol::_('kin',$val); 
    $_cas = api_hol::_('kin_nav_cas',$_kin->nav_cas);
    $_ond = api_hol::_('kin_nav_ond',$_kin->nav_ond);
    $_ton = api_hol::_('ton',$_kin->nav_ond_dia);  
    $_tra = api_hol::_('kin_arm_tra',$_kin->arm_tra);
    $_cel = api_hol::_('kin_arm_cel',$_kin->arm_cel);    
    $_est = api_hol::_('kin_cro_est',$_kin->cro_est);    
    $_ele = api_hol::_('kin_cro_ele',$_kin->cro_ele);
    $_sel = api_hol::_('sel',$_kin->arm_tra_dia);
    ?>
    <!-- ficha del encantamiento -->
    <article>
      <h2></h2>

      <?= app_dat::inf('hol','kin',$_kin,['cit'=>"des"]) ?>

      <br>

      <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

      <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

    </article>
    <!-- parejas -->
    <article>
      <h2></h2>

      <?= app_tab::hol('kin','par',[ 'ide'=>$_kin->ide, 'sec'=>[ 'par'=>1 ], 'pos'=>[ 'ima'=>'hol.kin.ide' ] ],[
        'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ], 'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
      ])?>
      <!-- Descripciones -->      
      <section>
        <h3></h3>

        <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            

        <?= app_var::hol('kin-par',$_kin) ?>

      </section>
      <!-- Lecturas diarias -->      
      <section>
        <h3></h3>

        <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

        <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>

        <?php // Propiedades : palabras clave del kin + sello + tono
          $_par_atr = ['fun','acc','mis'];
          $_ton_atr = ['acc'];  
          $_sel_atr = ['car','des'];  
          foreach( api_hol::_('sel_par') as $_par ){
            
            $_kin_par = $_par->ide == 'des' ? $_kin : api_hol::_('kin',$_kin->{"par_{$_par->ide}"});
    
            $ite = [ api_hol::ima("kin",$_kin_par) ];
    
            foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= app::let($_par->$atr); }
    
            $_ton_par = api_hol::_('ton',$_kin_par->nav_ond_dia);
            foreach( $_ton_atr as $atr ){ if( isset($_ton_par->$atr) ) $ite []= app::let($_ton_par->$atr); }
    
            $_sel_par = api_hol::_('sel',$_kin_par->arm_tra_dia);            
            foreach( $_sel_atr as $atr ){  if( isset($_sel_par->$atr) ) $ite []= app::let($_sel_par->$atr); }
    
            $_ []= $ite;
          }
          $ope['lis'] = ['class'=>"anc-100 mar_aba-2"];
          echo app_est::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        ?>
        
        <p>En <a href="<?=$_art?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

        <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>

        <?php // lecturas por parejas          
          $_ = [];
          foreach( api_hol::_('sel_par') as $_par ){

            if( $_par->ide == 'des' ) continue;
            $_kin_par = api_hol::_('kin',$_kin->{"par_{$_par->ide}"});
            $_sel_par = api_hol::_('sel',$_kin_par->arm_tra_dia);
            $_ []=
            api_hol::ima("kin",$_kin_par)."

            <div>
              <p><b class='tit'>{$_kin_par->nom}</b> <c>(</c> ".app::let($_par->dia)." <c>)</c></p>
              <p>".app::let("{$_sel_par->acc} {$_par->pod} {$_sel_par->car}, que {$_par->mis} {$_sel->car}, {$_par->acc} {$_sel_par->pod}.")."</p>
            </div>";
          }          
          $ope['lis']=[];  
          api_ele::cla($ope['lis'],'ite');
          echo app_lis::val($_,$ope);
        ?>

      </section>  
      <!-- Posiciones en el tzolkin -->        
      <section>
        <h3></h3>

        <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

        <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>

        <?php // Ciclos : posiciones en ciclos del kin
          $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
      
          foreach( api_hol::_('sel_par') as $_par ){
            
            $_kin_par = $_par->ide == 'des' ? $_kin : api_hol::_('kin',$_kin->{"par_{$_par->ide}"});

            $ite = [ api_hol::ima("kin",$_kin_par) ];

            foreach( $_atr as $atr ){
              $ite []= api_hol::ima("kin_{$atr}",$_kin_par->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          $ope['lis'] = ['class'=>"anc-100"];
          echo app_est::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        ?>

      </section>  
      <!-- Sincronometría del holon -->      
      <section>
        <h3></h3>

        <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

        <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

        <?php // Grupos : sincronometría del holon por sellos      

          $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];  

          foreach( api_hol::_('sel_par') as $_par ){
            
            $_kin_par = $_par->ide == 'des' ? $_kin : api_hol::_('kin',$_kin->{"par_{$_par->ide}"});                            
    
            $_sel_par = api_hol::_('sel',$_kin_par->arm_tra_dia);
    
            $ite = [ api_hol::ima("kin",$_kin_par), $_par->nom, $_sel_par->pod ];
    
            foreach( $_atr as $atr ){
              $ite []= api_hol::ima("sel_{$atr}",$_sel_par->$atr,[ 'class'=>"tam-5" ]);
            }            
            $_ []= $ite;
          }
          $ope['lis'] = ['class'=>"anc-100"];
          echo app_est::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        ?>

      </section>
      
    </article>
    <!-- Nave del tiempo -->
    <article>
      <h2></h2>
      <!-- x52 : Castillo Fractal -->        
      <section>
        <h3></h3>

        <p>Ver el <a href='<?=$_bib?>enc#_01-01-' target='_blank'>Génesis del Encantamiento del Sueño</a> en el encantamiento del sueño<c>...</c></p>

        <?= app_dat::inf('hol','kin_nav_cas',$_cas) ?>
        
        <?= app_tab::hol('kin','nav_cas',[ 'ide'=>$_cas->ide, 'val'=>[ 'pos'=>$_kin->ide ], 'pos'=>[ 'ima'=>'hol.kin.ide' ] ], [
          'cas'=>['class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen"], 
          'pos'=>['style'=>"width:2.5rem; height:2.5rem;"]  
        ]) ?>

        <h3>En los <n>26.000</n> años del <a href='<?=$_bib?>enc#_01-' target='_blank'>Génesis del Encantamiento del Sueño</a></h3>

        <p>Ver <a href='<?=$_bib?>enc#_03-06-' target='_blank'>el Índice de los castillos</a> en el Encantamiento del Sueño</p>

        <h3>En los <n>5.200</n> años del <a href='<?=$_bib?>fac#_01-'> Rayo de Sincronización Galáctica</a></h3>

        <p>Ver <a href='<?=$_bib?>fac#' target='_blank'>los 20 ciclos AHAU</a> en el Factor Maya</p>

      </section>
      <!-- x13 : Onda Encantada -->        
      <section>
        <h3></h3>

        <p>Ver <a href='<?=$_bib?>enc#_03-12-' target='_blank'>la Onda Encantada de la Aventura</a> en el Encantamiento del Sueño</p>

        <?= app_tab::hol('kin','nav_ond', [ 'ide'=>$_ond, 'sec'=>[ 'par'=>1 ], 'pos'=>[ 'ima'=>'hol.kin.ide' ] ], [
          'ond'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
          'pos'=>[ 'style'=>"width:6rem; height:6rem;" ]
        ]) ?>

      </section>  
    </article>
    <!-- Giro Galáctico -->
    <article>
      <h2></h2>
      <!-- x20 : Trayectoria Armónica -->        
      <section>
        <h3></h3>

        <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>el Gran Ciclo</a> en el <cite>Factor Maya</cite><c>...</c></p>

        <?= app_dat::inf('hol','kin_arm_tra',$_tra) ?>

        <p><?= app::let($_tra->lec) ?></p>

        <?= app_tab::hol('kin','arm_tra', [ 'ide'=>$_tra, 'sec'=>[ 'par'=>1 ], 'pos'=>[ 'ima'=>'hol.kin.ide' ] ], [
          'tra'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
          'pos'=>[ 'style'=>"width:5rem; height:5rem;" ],
          'pos-0'=>[ 'style'=>"width:4rem; height:4rem;" ]
        ]) ?>

        <p class='tit let-4'>Codificado por el tono <?= $_ton->nom ?></p>

        <p><?= $_ton->des ?> del Giro Galáctico<c>.</c></p>

        <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Células del Tiempo</a> en el Encantamiento del Sueño<c>...</c></p>

      </section>
      <!-- x4 : Célula del Tiempo -->        
      <section>
        <h3></h3>
        
        <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Razas Raíz Cósmicas</a> en el Encantamiento del Sueño</p>

      </section>  
    </article>
    <!-- Giro Espectral -->
    <article>
      <h2></h2>
      <!-- x65 : Estación Galáctica -->      
      <section>
        <h3></h3>

        <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>

      </section>
      <!-- x5 : Elemento Cromático -->        
      <section>
        <h3></h3>
        
        <p>Ver <a href='<?=$_bib?>enc#_03-16-' target='_blank'>Colocación Cromática</a> en el Encantamiento del Sueño</p>

      </section>
    </article>
    <?php          
  }
}

// Usuario
class hol_usu {

  // ficha
  static function fic( array $ope = [], ...$opc ) : string {
    $_ = "";
    global $_usu;      
    $_fec = api_fec::_('dat',$_usu->fec);
    $_kin = api_hol::_('kin',$_usu->kin);
    $_psi = api_hol::_('psi',$_usu->psi);
    // sumatoria : kin + psi
    $sum = $_kin->ide + $_psi->tzo;

    // nombre + fecha : kin + psi
    $_ = "
    <section class='inf ren esp-ara'>

      <div>
        <p class='let-tit let-3 mar_aba-1'>".app::let("$_usu->nom $_usu->ape")."</p>
        <p>".app::let($_fec->val." ( $_usu->eda años )")."</p>
      </div>        

      <div class='val'>
        ".api_hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
        <c>+</c>
        ".api_hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
        <c>=></c>
        ".api_hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
      </div>

    </section>";

    return $_;
  }    
  
  // ciclos: listado
  static function nav( array $ope = [], ...$opc ) : string {
    $_ = "";
    global $_usu;
    foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
    $opc_des = !in_array('not-des',$opc);
    // descripciones
    $_kin_des = function( $kin ){
      $sel = api_hol::_('sel',$kin->arm_tra_dia);
      $ton = api_hol::_('ton',$kin->nav_ond_dia);
      return app::let($kin->nom.": ")."<q>".app::let("$ton->des ".api_tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
    };
    // listado
    $_lis = [];
    foreach( api::dat("usu_cic") as $_arm ){
      $_lis_cic = [];
      foreach( api::dat('usu_cic_ani',[ 'ver'=>"`usu` = '{$_usu->ide}' AND `arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
        // ciclos lunares
        $_lis_lun = [];
        foreach( api::dat('usu_cic_lun',[ 'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
          $_fec = api_fec::_('dat',$_lun->fec);
          $_lun_ton = api_hol::_('ton',$_lun->ide);
          $_kin = api_hol::_('kin',$_lun->kin);
          $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>".app::let($_lun->sin)."</a>";
          $_lis_lun []= 
          "<div class='ite'>
            ".api_hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
            <p>
              ".app::let(intval($_lun_ton->ide)."° ciclo, ").$_nav.app::let(" ( $_fec->val ).")."
              <br>".app::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
            </p>              
          </div>
          <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
        }
        // ciclo anual
        $_fec = api_fec::_('dat',$_cic->fec);
        $_cas = api_hol::_('cas',$_cic->ide);
        $_cas_ton = api_hol::_('ton',$_cic->ton);
        $_cas_arm = api_hol::_('cas_arm',$_cic->arm);            
        $_kin = api_hol::_('kin',$_cic->kin);            
        $_lis_cic []= [
          'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
            "<div class='ite'>
              ".api_hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                ".app::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                <br>".app::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>".app::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
              </p>
            </div>
            <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>"
          ],
          'lis'=>$_lis_lun
        ];
      }
      $_lis []= [ 'ite'=>$_arm->nom, 'lis'=>$_lis_cic ];
    }
    // configuro listado
    api_ele::cla($ope['dep'],DIS_OCU);
    $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog_dep' ];
    return app_lis::val($_lis,$ope);
  }

  // ciclos: informe
  static function inf( array $ele = [], ...$opc ) : string {
    $dat = api_usu::cic_dat();
    $_ = "
    <section>
      ".hol_usu::inf_ani( $dat, $ele, ...$opc )."
      ".hol_usu::inf_lun( $dat, $ele, ...$opc )."
      ".hol_usu::inf_dia( $dat, $ele, ...$opc )."
    </section>"; 
    return $_;
  }// - anual
  static function inf_ani( array $dat, array $ele = [], ...$opc ) : string {
    global $_usu;      
    $_ani = $dat['ani'];
    $_cas_arm = api_hol::_('cas_arm',$dat['ani']->arm);
    $_ani_arm = api::dat("usu_cic",['ver'=>"`ide` = $_ani->arm",'opc'=>"uni"]);
    $_ani_fec = api_fec::_('dat',$_ani->fec);      
    $_ani_ton = api_hol::_('ton',$dat['ani']->ton);
    $_kin = api_hol::_('kin',$_ani->kin);
    $_ = "
    <h3>Tránsito Anual</h3>

    <p>".app::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

    ".app_var::num('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='ite mar_ver-1'>
      ".api_hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='let-3'>
        <p class='let-tit'>".app::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>".app_var::num('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    ".app_dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }// - lunar
  static function inf_lun( array $dat, array $ele = [], ...$opc ) : string {
    global $_usu;
    $_lun = $dat['lun'];
    $_lun_fec = api_fec::_('dat',$_lun->fec);
    $_lun_ton = api_hol::_('ton',$_lun->ide);
    $_kin = api_hol::_('kin',$_lun->kin);
    $_ = "
    <h3>Tránsito Lunar</h3>

    <p>".app::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

    ".app_var::num('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='ite mar_ver-1'>
      ".api_hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='let-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    ".app_dat::inf('hol','kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }// - diario
  static function inf_dia( array $dat, array $ele = [], ...$opc ) : string {
    global $_usu;
    $_dat = api_hol::val( date('Y/m/d') );
    $_kin = api_hol::_('kin',$dat['dia']->kin);

    $_ = "
    <h3>Tránsito Diario</h3>

    ".app_dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }
}