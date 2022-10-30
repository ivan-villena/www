<?php

  // Sincronario
  class _hol_app {

    public array $_val = [];

    // main : app.cab.art/val
    public function __construct( _app $_app ){
      // inicializo datos    
      $_uri = $_app->uri;
      $this->_val = _hol::val( date('Y/m/d') );

      // inicio : 
      if( empty($_uri->cab) ){

        $this->ini($_app);
      }
      // por seccion : introduccion
      elseif( empty($_uri->art) ){
        
      }
      // por articulo : bibliografía + informes + tableros
      else{
        ob_start();
        $this->{$_uri->cab}($_app);
        $_app->sec = ob_get_clean();
        // cargo todos los datos utilizados por esquema
        if( $_uri->cab == 'tab' ) $_app->rec['dat']['hol'] = [];
      }
      // recursos del documento
      $_app->rec['htm'] []= "$_uri->esq";
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

      <?=_app_ope::tex('adv', "Este sitio aún se está en construcción, puede haber contenido incompleto, errores o fallas.")?>

      <img src="../../img/hol/fon/sis.png" alt="Entrada" style="max-width:45rem;" 
        title="Entrada al Sincronario... Accede al menú en la barra superior, o busca ayuda con el ícono que tiene el signo de pregunta ( ? )"
      >      
      
      <?php
      $_app->sec = ob_get_clean();

      // cargo tutorial:
      ob_start(); ?>
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
        <!-- Códigos y Cuentas -->
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
        <!-- Tableros -->
        <section>
          <h3>Los tableros</h3>

          <div class="val jus-cen">
            <?= _app::ico('app_cab',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _app::ico('tab',['class'=>"mar_hor-1"]) ?>
          </div>
          
          <p>Desde el menú principal puedes acceder a un listado de tableros<c>.</c></p>

          <p>Para cada tablero se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por Ej<c>:</c> el <a href="<?=SYS_NAV."hol/tab/kin-tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

          <p>Desde allí podrás cambiar la fecha y acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos de las posiciones<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones seleccionadas para comparar sus características y ubicaciones<c>.</c></p>
          
        </section>
      <?php
      $_app->doc['dat'] = ob_get_clean();
    }
    // Bibliografía
    public function bib( _app $_app ) : void {
      $_nav = $_app->nav;
      $_uri = $_app->uri;
      $_dir = $_uri->dir();
      $_bib = SYS_NAV."hol/bib/";
      // busco archivos : html-php
      if( !empty( $rec = $_uri->rec($val = "htm/$_uri->esq/$_uri->cab/$_uri->art") ) ){
        include( $rec );
      }
      else{
        echo _app_ope::tex('err',"No existe el archivo '$val'");
      }
    }
    // Artículos
    public function art( _app $_app ) : void {
      $_nav = $_app->nav;
      $_uri = $_app->uri;
      $_dir = $_uri->dir();
      $_bib = SYS_NAV."hol/bib/";
      // busco archivos : html-php
      if( !empty( $rec = $_uri->rec($val = "htm/$_uri->esq/$_uri->cab/$_uri->art") ) ){
        include( $rec );
      }
      else{
        echo _app_ope::tex('err',"No existe el archivo '$val'");
      }
    }
    // Códigos y cuentas
    public function dat( _app $_app ) : void {
      $_nav = $_app->nav;
      $_uri = $_app->uri;
      $_bib = SYS_NAV."hol/bib/";
      $_art = SYS_NAV."hol/art/";
      switch( $_uri->art ){
      case 'rad': 
        if( empty($_uri->val) ){ ?>
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
        }else{ ?>
          <?php
        }
        break;
      case 'ton': 
        if( empty($_uri->val) ){ ?>
          <!-- rayos de pulsación -->
          <article>          
            <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>

            <p>En <cite>el Factor Maya</cite><c>,</c>
              se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c>
              Y se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c>
            </p>

            <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','gal'] ])?>

          </article>
          <!-- simetría especular -->
          <article>          
            <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_app::let($_nav[1]['02']->nom)?></h2>

            <p>En el <cite>Factor Maya</cite> 
              se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c>
              Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a>>.</c>
            </p>

            <?=_app_est::lis('hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>
            
          </article>        
          <!-- principios de la creacion -->
          <article>          
            <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_app::let($_nav[1]['03']->nom)?></h2>

            <p>En <cite>el Encantamiento del sueño</cite> 
              se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c> 
              De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c>
            </p>

            <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','nom','des','acc'] ])?>

          </article>
          <!-- O.E. de la Aventura -->
          <article>          
            <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_app::let($_nav[1]['04']->nom)?></h2>

            <p>En el <cite>Encantamiento del sueño</cite> 
              se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c>
              Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c>
            </p>

            <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 
              'tit_cic'=>['ond_enc']
            ])?>
            
          </article>        
          <!-- pulsar dimensional -->
          <article>          
            <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_app::let($_nav[1]['05']->nom)?></h2>

            <p>En <cite>el Encantamiento del sueño</cite> 
              
              <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a>
              <c>.</c>
            </p>

            <?=_app_est::lis('hol.ton_dim')?>
            
          </article>
          <!-- pulsar matiz -->
          <article>          
            <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=_app::let($_nav[1]['06']->nom)?></h2>

            <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

            <?=_app_est::lis('hol.ton_mat')?>
            
          </article>          
          <?php
        }else{
          $_ton = _hol::_('ton',$_uri->val); ?>
          <!-- Ficha -->
          <article>

            <?= _hol_fic::ton('atr',$_ton) ?>

            <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    

          </article>
          <!-- Aventura de la Onda Encantada -->

          <!-- Simetría Especular -->

          <!-- Pulsares Dimensionales -->

          <!-- Pulsares Matiz Entonado -->             
          <?php
        }
        break;
      case 'sel': 
        if( empty($_uri->val) ){ ?>
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

            <?=_app_est::lis('hol.sel_cic_men',[ 'atr'=>['sel','nom','des','lec'] ])?>

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
            
            <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
            
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

            <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>

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

            <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 
              'tit_cic'=>['sol_cel','sol_cir','sol_pla'] 
            ])?>
          </article>
          <!-- orbitas planetarias -->        
          <article>
            <h2 id="<?="_{$_nav[1]['16']->pos}-"?>"><?=_app::let($_nav[1]['16']->nom)?></h2>

            <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>

            <?=_app_est::lis('hol.sel_sol_pla')?>

          </article>
          <!-- células solares -->        
          <article>
            <h2 id="<?="_{$_nav[1]['17']->pos}-"?>"><?=_app::let($_nav[1]['17']->nom)?></h2>

            <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_app_est::lis('hol.sel_sol_cel')?>

          </article>
          <!-- circuitos de telepatía -->        
          <article>
            <h2 id="<?="_{$_nav[1]['18']->pos}-"?>"><?=_app::let($_nav[1]['18']->nom)?></h2>

            <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

            <?=_app_est::lis('hol.sel_sol_cir')?>

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

            <?=_app_est::lis('hol.sel_pla_cen')?>

          </article>
          <!-- flujos de la fuerza-g -->        
          <article>
            <h2 id="<?="_{$_nav[1]['22']->pos}-"?>"><?=_app::let($_nav[1]['22']->nom)?></h2>

            <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_app_est::lis('hol.sel_pla_res')?>

          </article>        
          <!-- holon humano -->
          <article>
            <h2 id="<?="_{$_nav[1]['23']->pos}-"?>"><?=_app::let($_nav[1]['23']->nom)?></h2>

            <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 
              'tit_cic'=>['cro_ele'] 
            ])?>
          </article>        
          <!-- Centros Galácticos -->        
          <article>
            <h2 id="<?="_{$_nav[1]['24']->pos}-"?>"><?=_app::let($_nav[1]['24']->nom)?></h2>

            <?=_app_est::lis('hol.sel_hum_cen')?>

          </article>
          <!-- Extremidades -->        
          <article>
            <h2 id="<?="_{$_nav[1]['25']->pos}-"?>"><?=_app::let($_nav[1]['25']->nom)?></h2>

            <?=_app_est::lis('hol.sel_hum_ext')?>

          </article>                     
          <!-- dedos -->        
          <article>            
            <h2 id="<?="_{$_nav[1]['26']->pos}-"?>"><?=_app::let($_nav[1]['26']->nom)?></h2>

            <?=_app_est::lis('hol.sel_hum_ded')?>

          </article>
          <!-- lados -->        
          <article>
            <h2 id="<?="_{$_nav[1]['27']->pos}-"?>"><?=_app::let($_nav[1]['27']->nom)?></h2>

            <?=_app_est::lis('hol.sel_hum_res')?>

          </article>
          <?php 
        }else{
          $_sel = _hol::_('sel',$_uri->val); ?>
          <!-- Ficha -->
          <article>
  
            <?= _hol_fic::sel('atr',$_sel) ?>
  
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
        }
        break;
      case 'lun': 
        if( empty($_uri->val) ){ ?>
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
        }else{ ?>
          <?php
        }
        break;
      case 'chi': 
        if( empty($_uri->val) ){ ?>
          <?php
        }else{ ?>
          <?php
        }
        break;
      case 'cas': 
        if( empty($_uri->val) ){ ?>
          <?php
        }else{ ?>
          <?php
        }
        break;
      case 'kin': 
        if( empty($_uri->val) ){ ?>
          <?php
        }else{ 
          $_kin = _hol::_('kin',$_uri->val); ?>
          <!-- ficha del encantamiento -->
          <article>
            <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_app::let($_nav[1]['01']->nom)?></h2>

            <?= _hol_fic::kin('enc',$_kin) ?>

            <br>

            <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

            <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

          </article>
          <!-- parejas -->
          <article>
            <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_app::let($_nav[1]['02']->nom)?></h2>

            <?= _hol_tab::kin('par',[ 
              'ide'=>$_kin->ide,
              'sec'=>[ 'par'=>0 ],
              'pos'=>[ 'ima'=>'hol.kin.ide' ]
            ],[
              'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
              'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
            ])?>
            <!-- Descripciones -->
            <h3 id="<?="_{$_nav[2]['02']['01']->pos}-"?>"><?=_app::let($_nav[2]['02']['01']->nom)?></h3>
            <section>

              <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            

              <?= _hol_fic::kin('par',$_kin) ?>

            </section>
            <!-- Lecturas diarias -->
            <h3 id="<?="_{$_nav[2]['02']['02']->pos}-"?>"><?=_app::let($_nav[2]['02']['02']->nom)?></h3>
            <section>

              <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

              <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>

              <?= _hol_dat::kin('par-pro',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>

              <br>
              
              <p>En <a href="<?=$_art?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

              <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>

              <?= _hol_fic::kin('par-lec',$_kin) ?>

            </section>  
            <!-- Posiciones en el tzolkin -->  
            <h3 id="<?="_{$_nav[2]['02']['03']->pos}-"?>"><?=_app::let($_nav[2]['02']['03']->nom)?></h3>
            <section>

              <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

              <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>

              <?= _hol_dat::kin('par-cic',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>

            </section>  
            <!-- Sincronometría del holon -->
            <h3 id="<?="_{$_nav[2]['02']['04']->pos}-"?>"><?=_app::let($_nav[2]['02']['04']->nom)?></h3>
            <section>

              <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

              <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

              <br>

              <?= _hol_dat::kin('par-gru',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>

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

                <?= _hol_fic::kin('nav_cas',$_cas) ?>
                
                <?= _hol_tab::kin('nav_cas',[ 
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

              <?= _hol_tab::kin('nav_ond', [
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

              <?= _hol_fic::kin('arm_tra',$_tra) ?>

              <p><?= _app::let($_tra->lec) ?></p>

              <?= _hol_tab::kin('arm_tra', [
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
        }
        break;
      case 'psi': 
        if( empty($_uri->val) ){ ?>
          <?php
        }else{
          $_psi = _hol::_('psi',$_uri->val); ?>
          <!-- x91: Estaciones Solares -->
  
          <!-- x28: Giros Lunares -->
  
          <!-- x20: Vinales -->
  
          <!-- x7: Heptadas -->
  
          <!-- x5: Cromaticas -->        
          <?php
        }
        break;
      }
      // cargo ayuda
      ob_start(); ?>
        <!-- Introduccion -->
        <section>

          <h3>Introducción</h3>

          <p>Los sistemas del Sincronario están basados en códigos y cuentas<c>.</c> Los <n>13</n> tonos galácticos crean el módulo de sincronización para las <n>13</n> lunas del giro solar y las <n>13</n> trayectorias armónicas del giro galáctico<c>.</c> Cada kin está compuesto por uno de los <n>13</n> tonos galácticos<c>,</c> y uno de los <n>20</n> sellos solares<c>.</c> Cada día del año se encuentra en una de las <n>13</n> lunas y se asocia a uno de los <n>7</n> plasma radiales<c>.</c> Un castillo está compuesto por <n>52</n> posiciones que se dividen en <n>4</n> ondas encantadas de <n>13</n> unidades<c>.</c> Con el castillo se codifican las <n>4</n> estaciones espectrales del giro galáctico<c>,</c> las <n>4</n> estaciones cíclicas del giro solar<c>,</c> los <n>52</n> anillos solares del ciclo Nuevo Siario y los <n>52</n> años del sendero del destino para el kin planetario<c>.</c> A su vez<c>,</c> la nave del tiempo tierra está compuesta de <n>5</n> castillos para abarcar los <n>260</n> kines del giro galáctico<c>.</c> Todos estos son ejemplos de las cuentas utilizadas para medir el tiempo con el concepto de Matriz Radial<c>.</c> Cada cuenta va del <n>1</n> al <n>n</n><c>,</c> siendo <n>n</n> el valor total que define la cuenta<c>.</c> De esta manera<c>:</c> los plasmas val del <n>1<c>-</c>7</n><c>,</c> los tonos del <n>1<c>-</c>13</n><c>,</c> los sellos del <n>1<c>-</c>20</n><c>,</c> las lunas del <n>1<c>-</c>28</n><c>,</c>etc<c>.</c></p>

        </section>
      <?php
      $_app->doc['dat'] = ob_get_clean();  
    }
    // Tableros
    public function tab( _app $_app ) : void {
      $_uri = $_app->uri;
      $_val = &$this->_val;
      // valido tablero
      $art_tab = explode('-',$_uri->art);
      if( !isset($art_tab[1]) || !method_exists("_hol_tab",$ide = $art_tab[0]) ){
        echo _app_ope::tex('err',"No existe el tablero '$_uri->art'");
        return;
      }
      // proceso valor e identificadores por peticion : ?ide=val
      $hol_val = !empty($_uri->val) ? $_uri->val : ( !empty($_SESSION['hol-val']) ? $_SESSION['hol-val'] : '' );        
      if( !empty($_uri->val) ){

        $val = explode('=',$hol_val);

        if( isset($val[1]) ){

          $val[1] = isset($val[1]) ? $val[1] : '';

          if( $val[0]=='fec' || $val[0]=='sin' ){
            $_val = _hol::val($val[1],$val[0]);
          }
          else{
            $_val[$val[0]] = $val[1];
          }
        }// por valor directo : ej. kin/ide
        else{
          $_val[$ide] = _hol::val($ide,$val[0]);
        }
      }// actualizo sesion
      $_SESSION['hol-val'] = $hol_val;

      // operadores del tablero
      $_tab =  _app::tab('hol',str_replace('-','_',$_uri->art));
      $tab_ide = "hol.{$ide}";
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
        $tab_ope['dat'] = _hol::dat($ide,$_val);
        // kin + psi : activo acumulados
        if( in_array($ide,$ope_atr) ){
          $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
          // agrego opciones
          if( !empty($tab_ope['opc']) ) $tab_ope['val']['acu']['opc'] = 1;
        }
        // valor seleccionado
        $tab_ope['val']['pos'] = $_val;
      }
      // valores diario
      $ope = _obj::nom(_hol_inf::$OPE,'ver',$ope_atr);
      foreach( $ope_atr as $ope_ide ){
        $ope[$ope_ide]['htm'] = _hol_inf::$ope_ide($_val[$ope_ide],[ 'opc'=>['tog','ver'] ]);
      }
      $_app->ope['dia'] = [ 'ico'=>"fec_val", 'tip'=>"pan", 'nom'=>"Diario", 'htm'=>"

        <section class='mar_aba-1'>
          "._hol_val::fec($_val,['eje'=>"_hol_tab._val"])."
        </section>

        "._app_nav::sec('bar', $ope, [ 'sel'=>"kin" ])

      ];
      // operadores del tablero
      $ope = _obj::nom(_app_tab::$OPE,'ver',['ver','opc','val']);
      foreach( $ope as $ope_ide => $ope_tab ){

        if( !empty( $htm = _app_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele ) ) ){

          $_app->ope[$ope_ide] = [ 'ico'=>$ope_tab['ico'], 'tip'=>"pan", 'nom'=>$ope_tab['nom'], 'htm'=>$htm];
        }
      }
      // operador de lista
      $ope = _app_tab::$OPE['lis'];
      $_app->ope['est'] = [ 'ico'=>$ope['ico'], 'tip'=>"win", 'nom'=>$ope['nom'], 'htm'=>_app_tab::ope('lis',"hol.{$ide}",$tab_ope) ];
      // imprimo tablero en página principal
      echo "
      <article>
        "._hol_tab::$ide($art_tab[1], $tab_ope, [ 'pos'=>[ 'onclick'=>"_app_tab.val('mar',this);" ] ])."
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
          <div class="val jus-cen">
            <?= _app::ico('fec_val',['class'=>"mar_hor-1"]) ?>
            <h3>Posición Principal</h3>
          </div>

          <p>Desde esta sección podrás cambiar la fecha de la posición principal del tablero<c>,</c> y ver un detalle de cada código correspondiente<c>.</c></p>

          <p>Puedes ver los datos correspondiente al Orden Sincrónico <c>(</c> de <n>260</n> días <c>)</c> o al Oredn Cíclico <c>(</c> de <n>365</n> días <c>)</c><c>.</c> La información se presenta en forma listado con fichas<c>,</c> que contienen los datos propios de la cuenta que representa<c>,</c> y sus valores posicionales correspondientes a su ciclo de tiempo<c>.</c></p>

          <p>También puedes <i>marcar</i> otras posiciones del tablero haciendo click directamente sobre ellas<c>.</c> Estas serán cargardas en la seccion de acumulados<c>.</c></p>

        </section>
        <!-- Seleccion -->
        <section>
          <div class="val jus-cen">
            <?= _app::ico('dat_ver',['class'=>"mar_hor-1"]) ?>
            <h3>Selección por Valores</h3>
          </div>

          <p>Accede a esta sección para seleccionar múltiples posiciones y luego comparar sus datos<c>.</c> Puedes aplicar criterios de selección por estructuras de datos<c>,</c> fechas<c>,</c> o posiciones<c>.</c></p>

          <p>Si buscas por estructuras de datos<c>,</c> deberás seleccionar primero el grupo de cuentas<c>(</c> Kin<c>,</c> Sello<c>,</c> Tono<c>,</c> Psi<c>-</c>Cronos<c>,</c> etc<c>.</c> <c>)</c><c>,</c> luego la cuenta <c>(</c> las ondas encantadas del kin<c>,</c> las familias de los sellos<c>,</c> los pulsares de los tonos<c>,</c> etc<c>.</c> <c>)</c> y por último el valor <c>(</c> la <n>3</n><c>°</c> onda encantada<c>,</c> la familia cardinal<c>,</c> el pulsar dimensional del tiempo<c>,</c> etc<c>.</c> <c>)</c><c>.</c></p>

          <p>También puedes buscar por fechas del calendario o el número de posiciones en el tablero<c>.</c> En estos casos deberás ingresar un valor inicial<c>,</c> y puedes limitar la sección con un valor final<c>.</c> También puedes indicar un incremento<c>,</c> es decir<c>,</c> un salto entre valores seleccionados<c>;</c> una cantidad límite de resultados<c>;</c> y si quieres que sean los primeros o los últimos de la lista<c>.</c></p>

          <p>Los valores seleccionados serán marcados con un recuadro en el tablero<c>.</c> También serán cargados en la sección de acumulados<c>,</c> serán tenidos en cuenta en los totales por estructura<c>,</c> y se mostrarán en el listado<c>.</c></p>

        </section>        
        <!-- Opciones -->
        <section>
          <div class="val jus-cen">
            <?= _app::ico('opc_bin',['class'=>"mar_hor-1"]) ?>
            <h3>Opciones del Tablero</h3>
          </div>

          <p>Desde aquí puedes cambiar los colores de fondo<c>,</c> seleccionar el tipo de ficha y ver contenido numérico o textual para cada posición<c>.</c></p>

          <p>Según los atributos del tablero<c>,</c> definidos por su cuenta<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

          <p>Estas posiciones serán tenidas en cuenta en los acumulados del tablero y los elementos de la lista<c>.</c></p>

        </section>
        <!-- Operador -->
        <section>
          <div class="val jus-cen">
            <?= _app::ico('est',['class'=>"mar_hor-1"]) ?>
            <h3>Datos y Valores</h3>
          </div>          

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
          <div class="val jus-cen">
            <?= _app::ico('lis_ite',['class'=>"mar_hor-1"]) ?>
            <h3>Listado</h3>
          </div>

          <p>Este listao se arma con los datos de todas las posiciones acumuladas de los distintos operadores<c>:</c> <b class="ide">Fecha del diario</b><c>,</c> <b class="ide">Marca directa</b><c>,</c> <b class="ide">Selección por Valores</b> y <b class="ide">Opciones del Tablero</b><c>.</c> Puedes elegir entre los distintos critrios de acumulación o ver todas las posiciones<c>.</c></p>

          <p>Los items de la lista contienen los mismos datos sobre los códigos y cuentas que cada posición en el tablero <c>(</c>la Fecha<c>,</c> el Kin y el Psi<c>)</c><c>.</c> Puedes ver y comparar cada atributo que está representado por su ficha correspondiente<c>,</c> al pasar el mouse sobre ellas aparecerá un pequeño contenido descriptivo<c>.</c></p>

          <p>También puedes filtrar las posiciones por las distintas estructuras de códigos y cuentas<c>,</c> de la misma manera que con la selección de posiciones en el Tablero<c>.</c> Tambien puedes aplicarlos por fecha siguiendo los mismos criterios <c>(</c> desde<c>,</c> hasta<c>,</c> cada<c>,</c> cuántos<c>,</c> al<c>...</c> <c>)</c></p>

          <p>Luego puedes seleccionar las columnas que representen a los datos que deseas ver<c>,</c> y revelar los titulos por Ciclos y Agrupaciones o analizar las lecturas disponibles para cada posición<c>.</c></p>

        </section>        
      <?php
      $_app->doc['dat'] = ob_get_clean();

    }
    // Kin Planetario
    public function usu( _app $_app ) : void {
      global $_usu;
      $_nav = $_app->nav;
      $_uri = $_app->uri;

    }
  }
  
  // Bibliografía : libros
  class _hol_bib {
    static string $IDE = "_hol_bib-";
    static string $EJE = "_hol_bib.";

    // Tierra en ascenso
    static function asc( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Factor Maya
    static function fac( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      // tonos : rayo de pulsacion
      case 'ton':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('ton') as $_ton ){ $_ []= "
          "._hol::ima("ton",$_ton,['class'=>"mar_der-1"])."
          <p>
            <n>".intval($_ton->ide)."</n><c>.</c> El Rayo de Pulsación ".preg_replace("/^(del|de la)/","$1<b class='ide'>",_tex::art_del($_ton->gal))."</b>
          </p>";
        }        
        break;
      // tonos : simetría especular
      case 'ton_sim': 
        foreach( _hol::_('ton_sim') as $_sim ){ $_ []= "
          <p>"._app::let($_sim->des)."</p>";
        }        
        break;
      // sellos : posiciones direccionales
      case 'sel_cic_dir':
        $ope['lis'] = ['class'=>"ite"];
        foreach( _hol::_($ide) as $_dir ){ $_ []=
          _hol::ima($ide,$_dir,['class'=>"mar_der-1 tam-11"])."
          <div>
            <p><b class='ide'>".explode(' ',$_dir->nom)[1]."</b><c>:</c></p>
            <ul>
              <li><p><c>-></c> "._app::let($_dir->des)."</p></li>
              <li><p><c>-></c> Color<c>:</c> <c class='let_col-4-{$_dir->ide}'>{$_dir->col}</c></p></li>
            </ul>
          </div>";
        }
        break;
      // sellos : desarrollo del ser con etapas evolutivas
      case 'sel_cic_ser':
        $ope['lis'] = ['class'=>"ite"];
        foreach( _hol::_('sel') as $_sel ){
          if( $lis_pos != $_sel->cic_ser ){
            $lis_pos = $_sel->cic_ser;
            $_ser = _hol::_($ide,$lis_pos);
            $_ []= "
            <p class='tit'>
              DESARROLLO".( _tex::let_may( _tex::art_del($_ser->nom) ) ).( !empty($_ser->det) ? " <c>-</c> Etapa {$_ser->det}" : '' )."
            </p>";
          }                
          $_dir = _hol::_('sel_cic_dir',$_sel->arm_raz); $_ []= 
  
          _hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
  
          <p><n>{$_sel->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".explode(' ',$_dir->nom)[1]."<c>.</c>
            <br>"._app::let($_sel->cic_ser_des)."
          </p>";
        }        
        break;
      // sellos : familias ciclicas
      case 'sel_cic_luz': 
        $ope['lis'] = ['class'=>"ite"];
        foreach( _hol::_('sel') as $_sel ){
          if( $lis_pos != $_sel->cic_luz ){
            $lis_pos = $_sel->cic_luz;
            $_luz = _hol::_($ide,$lis_pos); $_ []= "
            <p><b class='tit'>"._tex::let_may("Familia Cíclica "._tex::art_del($_luz->nom)."")."</b>
              <br><b class='des'>{$_luz->des}</b><c>.</c>
            </p>";
          }                
          $_dir = _hol::_('sel_cic_dir',$_sel->arm_raz);                 
          
          $_ []= 
  
          _hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
  
          <p>".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
            <br>"._app::let($_sel->cic_luz_des)."
          </p>";                
        }          
        break;
      // sellos : guardianes evolutivos de la mente
      case 'sel_cic_men':
        $ope['lis'] = ['class'=>"ite"];
        foreach( _hol::_($ide) as $_est ){
          $_sel = _hol::_('sel',$_est->sel); 
          $_dir = _hol::_('sel_cic_dir',$_est->ide); $_ []= 
          
          _hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
  
          <p><n>".intval($_sel->ide)."</n><c>°</c> Signo<c>.</c> ".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
            <br><b class='val des'>{$_est->nom}</b><c>:</c> {$_est->des}<c>.</c>
          </p>";
        }        
        break;
      // kin : katun del kin
      case 'kin':
        $_kin = _hol::_('kin',$ope['ide']);
        $_sel = _hol::_('sel',$_kin->arm_tra_dia);
        $_pol = _hol::_('sel_res',$_sel->res_flu);
        $_pla = _hol::_('sel_sol_pla',$_sel->sol_pla);
        $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
        $_arq = _hol::_('sel',$_ond->sel);
        $ton = intval($_kin->nav_ond_dia);
        $_ = "
        <div class='val'>

          "._hol::ima("kin",$_kin)."

          <p class='tit tex_ali-izq'>
            Katún <n>".intval($_sel->ide-1)."</n><c>:</c> Kin <n>$ton</n> <b class='ide'>$_sel->may</b>".( !empty($_kin->pag) ? "<c>(</c> Activación Galáctica <c>)</c>" : '' )."<c>.</c>
          </p>
        
        </div>
        <ul>
          <li>Regente Planetario<c>:</c> $_pla->nom $_pol->tip<c>.</c></li>
          <li>Etapa <n>$ton</n><c>,</c> Ciclo $_arq->may<c>.</c></li>
          <li>Índice Armónico <n>"._num::int($_kin->fac)."</n><c>:</c> período "._app::let($_kin->fac_ran)."</li>
          <li><q>"._app::let($_sel->arm_tra_des)."</q></li>
        </ul>";
        break;
      // kin : portales de activacion
      case 'kin_pag':
        $arm_tra = 0;
        $ope['lis'] = ['class'=>"ite"];
        foreach( array_filter(_hol::_('kin'), function( $ite ){ return !empty($ite->pag); }) as $_kin ){ 
          $lis_pos++; 
          $_sel = _hol::_('sel',$_kin->arm_tra_dia);
          if( $arm_tra != $_kin->arm_tra ){
            $arm_tra = $_kin->arm_tra;
            $_tra = _hol::_('kin_arm_tra',$arm_tra); $_ []= "
  
            "._hol::ima("ton",$arm_tra,['class'=>"mar_der-1"])."
  
            <p class='tit'>"._app::let(_tex::let_may("CICLO ".($num = intval($_tra->ide)).", Baktún ".( $num-1 )))."</p>";
          }
          $_ []= "
  
          "._hol::ima("kin",$_kin,['class'=>"mar_der-1"])."
  
          <p>
            <n>{$lis_pos}</n><c>.</c> <b class='ide'>{$_sel->may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
            <br>"._app::let($_kin->fac_ran)."
          </p>";
        }          
        break;
      // kin : 1 trayectoria con detalles por katun ( ciclos del modelo morfogenetico )
      case 'kin_fec':
        $ond = 0;
        $_ = "
        <table>";
          if( !empty($ope['tit']) ){ $_.="
            <caption>".( !empty($ope['tit']['htm']) ? "<p class='tit'>"._app::let($ope['tit']['htm'])."</p>" : '' )."</caption>";
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
          foreach( ( !empty($dat) ? $dat : _hol::_('kin') ) as $_kin ){
  
            if( $ond != $_kin->nav_ond ){
              $_ond = _hol::_('kin_nav_ond', $ond = $_kin->nav_ond); 
              $_sel = _hol::_('sel', $_ond->sel);
              $_ .= "
              <tr class='tex_ali-izq'>
                <td>
                  "._hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."
                </td>
                <td colspan='3'>{$_sel->may}<c>:</c> "._app::let($_ond->fac_ran)." <q>"._app::let($_ond->fac_des)."</q></td>
              </tr>";
            }
            $_sel = _hol::_('sel',$sel = intval($_kin->arm_tra_dia));
            $_ .= "
            <tr data-kin='{$_kin->ide}'>
              <td>
                Etapa <n>".($ton = intval($_kin->nav_ond_dia))."</n>
              </td>
              <td></td>
              <td>
                <n>$sel</n><c>.</c><n>$ton</n> <b class='ide'>$_sel->may</b><c>:</c>
                <br><n>"._num::int($_kin->fac)."</n><c>,</c> año <n>"._num::int($_kin->fac_ini)."</n>
              </td>
              <td>
                <q>"._app::let($_sel->arm_tra_des)."</q>
              </td>
            </tr>";
          }$_.="
          </tbody>
  
        </table>";
        break;
      // kin : 13 baktunes
      case 'kin_arm_tra':

        foreach( _hol::_($ide) as $_tra ){
          $htm = "
          <div class='val'>
            "._hol::ima("ton",$_tra->ide,['class'=>"mar_der-1"])."
            <p>
              <b class='tit'>Baktún <n>".(intval($_tra->ide)-1)."</n><c>.</c> Baktún "._tex::art_del($_tra->nom)."</b>
              <br>"._app::let($_tra->ran)." <c><=></c> "._app::let($_tra->may)."
            </p>
          </div>";
          $lis = [];
          foreach( explode('; ',$_tra->lec) as $ite ){
            $lis []= "<c>-></c> "._app::let($ite);
          }
          $_[] = $htm._app_lis::val($lis,[ 'lis'=>['class'=>"pun"] ]);
        }          
        break;
      // kin : 20 katunes
      case 'kin_arm_sel':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('sel') as $_sel ){ $_ [] = "
  
          "._hol::ima("sel_arm_tra",$_sel,['class'=>"mar_der-2"])."
  
          <p>
            <b class='ide'>{$_sel->may}</b><c>:</c> Katún <n>".(intval($_sel->ide)-1)."</n>
            <br>{$_sel->arm_tra_des}
          </p>";
        }
        break;
      // kin : guardianes por estacion cromatica
      case 'kin_cro_est':

        foreach( _hol::_($ide) as $_est ){

          $_sel = _hol::_('sel',$_est->sel); $htm = "
  
          <div class='val'>
            "._hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
  
            <p>
              <b class='tit'>ESTACIÓN "._tex::let_may(_tex::art_del("el {$_est->nom}"))."</b>
              <br>Guardían<c>:</c> <b class='ide'>{$_sel->may}</b> <c>(</c> {$_sel->nom} <c>)</c>
            </p>
          </div>";
  
          $lis = [];
          foreach( _hol::_('kin_cro_ond') as $_ond ){ $lis []= "
  
            "._hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
  
            <p>El quemador {$_ond->que} el Fuego<c>.</c>
              <br><n>".intval($_ond->ton)."</n> {$_sel->may}
            </p>";
          }                
          $_[] = $htm._app_lis::val($lis,[ 'lis'=>['class'=>'ite'] ]);
        }          
        break;
      // kin : aventura por guardián
      case 'kin_cro_ond':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_cro_ond') as $_ond ){ $_ []= "
          "._hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
          <p>
            Tono <n>".intval($_ond->ton)."</n> de la familia terrestre polar<c>:</c> 
            {$_ond->nom} <n>1</n> de los <n>4</n> Espectros Galácticos<c>.</c>
          </p>";
        }          
        break;
      // kin : ciclo ahau / onda encantada
      case 'kin_nav_sel':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_nav_ond') as $_ond ){ 
          $_sel = _hol::_('sel',$_ond->sel); $_ [] = "
  
          "._hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-2"])."
  
          <p>
            <n>{$_ond->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> "._app::let($_ond->fac_ran)."
            <br><q>{$_ond->fac_des}</q>
          </p>";
        }            
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Encantamiento del Sueño
    static function enc( string $ide, array $ope = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      $_eje = self::$EJE."enc('{$ide}',";
      switch( $ide ){
      // tonos : descripciones
      case 'ton':
        $est_ope['atr'] = ['ide','nom','des','acc'];
        $_ = _app_est::lis("hol.ton", $est_ope, $ope );
        break;
      // tonos : aventura de la onda encantada 
      case 'ton_ond':
        $_atr = array_merge([ 
          'ima'=>_obj::atr(['ide'=>'ima','nom'=>''])
          ], _dat::atr('hol',"ton", [ 'ide','ond_pos','ond_pod','ond_man' ])
        );
        // cargo valores
        foreach( ( $_dat = _obj::atr(_hol::_('ton')) ) as $_ton ){
          $_ton->ima = [ 'htm'=>_hol::ima("ton",$_ton) ];
          $_ton->ide = "Tono {$_ton->ide}";
        }
        // cargo titulos
        $ond = 0;
        $_tit = [];
        foreach( $_dat as $lis_pos => $_ton ){
          if( $_ton->ond_enc != 0 && $ond != $_ton->ond_enc ){              
            $_ond = _hol::_('ton_ond',$ond = $_ton->ond_enc);
            $_tit[$lis_pos] = $_ond->des;
          }
        }
  
        $_ = _app_est::lis($_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit, 'opc'=>['cab_ocu'] ],$ope);              
        break;
            
      // tonos : pulsares dimensionales
      case 'ton_dim':
        foreach( _hol::_('ton_dim') as $_dat ){ $htm = "
          <p>
            <n>{$_dat->ide}</n><c>.</c> <b class='ide'>Pulsar de la {$_dat->pos} dimensión</b><c>:</c> <b class='val'>Dimensión {$_dat->nom}</b>
            <br>Tonos "._app::let("{$_dat->ton}: {$_dat->ond}")."
          </p>
          <div class='fic ite'>
            "._hol::ima("ton_dim",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= _hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "
            <c class='_lis fin'>}</c>
          </div>
          ";
          $_ []= $htm;
        }        
        break;
      // tonos : pulsares matiz
      case 'ton_mat':
        foreach( _hol::_('ton_mat') as $_dat ){ $htm = "
          <p><n>{$_dat->ide}</n><c>.</c> <b class='ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='val'>"._app::let($_dat->cod)."</b><c>:</c>
            <br>Tonos "._app::let("{$_dat->ton}: {$_dat->ond}")."
          </p>
          <div class='fic ite'>
            "._hol::ima("ton_mat",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= _hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "              
            <c class='_lis fin'>}</c>
          </div>";
          $_ []= $htm;
        }        
        break;
      // sello : colocacion armónica => razas raíz cósmica
      case 'sel_arm_raz':
        $sel = 1;
        foreach( _hol::_($ide) as $_dat ){
          $_raz_pod = _hol::_('sel',$_dat->ide)->pod; 
          $htm = "
          <p class='tit'>Familia <b class='let_col-4-{$_dat->ide}'>{$_dat->nom}</b><c>:</c> de la <b class='ide'>Raza Raíz "._tex::let_min($_dat->nom)."</b></p>
          <p>Los {$_dat->pod}dores<c>.</c> Nota clave<c>:</c> ".explode(' ',$_raz_pod)[1]."</p>
          <ul class='ite'>";
          foreach( _hol::_('sel_arm_cel') as $lis_pos ){
            $_sel = _hol::_('sel',$sel); $htm .= "
            <li>
              "._hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
              <p>
                <n>{$lis_pos->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                <br><q>"._app::let($_sel->arm_raz_des)."</q>
              </p>
            </li>";
            $sel += 4;
            if( $sel > 20 ) $sel -= 20;                  
          }
          $htm.="
          </ul>
          <q>"._app::let(_tex::let_ora($_raz_pod)." ha sido "._tex::art_gen("realizado",$_raz_pod).".")."</q>";
          $_ []= $htm;
        }        
        break;
      // sello : colocacion armónica => células del tiempo
      case 'sel_arm_cel':
        $lis_pos = 1;

        foreach( _hol::_($ide) as $_dat ){ $htm = "
          <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='ide'>{$_dat->nom}</b></p>
          <q>"._app::let($_dat->des)."</q>
          <ul class='ite'>";
          foreach( _hol::_('sel_arm_raz') as $cro ){ $_sel = _hol::_('sel',$lis_pos); $htm .= "
            <li>
              "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
              <p>
                <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                <br><q>"._app::let($_sel->arm_cel_des)."</q>
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
        foreach( _hol::_($ide) as $_dat ){
          $ele_nom = explode(' ',$_dat->nom)[1]; $htm = "
          <p class='tit'><b class='ide'>Clan "._tex::art_del($_dat->nom)."</b>"._app::let(": Cromática {$_dat->col}.")."</p>
          ".( !empty($_dat->des_ini) ? "<p>"._app::let($_dat->des_ini)."</p>" : '' )."
          <ul class='ite'>";
          for( $fam=1; $fam<=5; $fam++ ){ 
            $_sel = _hol::_('sel',$sel); 
            $_fam = _hol::_('sel_cro_fam',$fam); $htm .= "
            <li sel='{$_sel->ide}' cro_fam='{$fam}'>
              "._hol::ima("sel",$_sel,[ 'class'=>"mar_der-1" ])."
              <p>
                <n>{$sel}</n><c>.</c> <b class='ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
                <br><q>"._app::let($_sel->cro_ele_des)."</q>
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

        foreach( _hol::_('sel_pla_cen') as $_pla ){
          $_hum = _hol::_('sel_hum_cen',$_pla->ide);
          $_fam = _hol::_($ide,$_pla->fam);
          $htm = 
          _hol::ima("sel_pla_cen",$_pla,['class'=>"mar_der-2",'style'=>"min-width: 9rem; height:7rem;"])."
          <div>
            <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> {$_fam->enc_fun}</p>
            <div class='val fic mar-2'>
              "._hol::ima("sel_hum_cen",$_hum)."
              <c class='sep'>=></c>
              <c class='_lis ini'>{</c>";
                foreach( explode(', ',$_fam->sel) as $sel ){
                  $htm .= _hol::ima("sel",$sel,['class'=>"mar_hor-2"]);
                }$htm .= "
              <c class='_lis fin'>}</c>
            </div>
          </div>
          ";
          $_ []= $htm;
        }        
        break;
      // sello : holon solar => celulas solares y planentas
      case 'sel_sol_cel': 
        $orb = 0;
        $pla = 10;
        $sel = 20;
        $val_sel = empty( $val = isset($ope['val']) ? $ope['val'] : [] );
  
        foreach( _hol::_($ide) as $_dat ){
          if( $val_sel || in_array($_dat->ide,$val) ){ 
            $htm = "
            <p class='tit'><b class='ide'>{$_dat->nom}</b><c>:</c> Célula Solar "._num::dat($_dat->ide,'nom')."<c>.</c></p>                  
            <ul est='sol_pla'>";
            for( $sol_pla=1; $sol_pla<=2; $sol_pla++ ){
              $_pla = _hol::_('sel_sol_pla',$pla);
              $_sel = _hol::_('sel',$sel);
              $_par = _hol::_('sel',$_sel->par_ana); 
              if( $orb != $_pla->orb ){
                $_orb = _hol::_('sel_sol_orb',$orb = $_pla->orb); $htm .= "
                <li>Los <n>5</n> <b class='ide'>planetas {$_orb->nom}es</b><c>:</c> "._app::let($_orb->des)."</li>";                        
              }
              $htm .= "
              <li>
                <p><b class='ide'>{$_pla->nom}</b><c>,</c> <n>{$pla}</n><c>°</c> órbita<c>:</c></p>
                <div class='ite'>
  
                  "._hol::ima("sel_sol_pla",$_pla,['class'=>"mar_der-1"])."
  
                  <ul class='ite' est='sel'>
                    <li>
                      "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
                      <p>
                        <b class='val'>Dentro</b><c>:</c> Sello Solar <n>{$_sel->ide}</n>
                        <br><q>"._app::let($_sel->sol_pla_des)."</q>
                      </p>
                    </li>
                    <li>
                      "._hol::ima("sel",$_par,['class'=>"mar_der-1"])."
                      <p>
                        <b class='val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                        <br><q>"._app::let($_par->sol_pla_des)."</q>
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
      case 'sel_pla_cen': 
        $ope['lis'] = ['class'=>"ite"];

        $_fam_sel = [
          1=>[  5, 10, 15, 20 ],
          2=>[  1,  6, 11, 16 ],
          3=>[ 17,  2,  7, 12 ],
          4=>[ 13, 18,  3,  8 ],
          5=>[  9, 14, 19,  4 ]
        ]; 
        $lis_pos = 1;
        foreach( _hol::_('sel_pla_cen') as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam); $htm= "
          <div class='val'>
            "._hol::ima("sel_cro_fam",$_fam,['class'=>"mar_der-1"])."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
            foreach( $_fam_sel[$_dat->ide] as $sel ){
              $htm .= _hol::ima("sel",$sel,['class'=>"mar_hor-1"]);
            }$htm.="
            <c class='_lis fin'>}</c>
            <c class='sep'>:</c>
          </div>
          <p>
            <n>{$_dat->ide}</n><c>.</c> El Kin <b class='ide'>{$_fam->nom}</b><c>:</c>
            <br><q>{$_fam->pla} desde el {$_dat->nom}</q>
          </p>";
          $_ []= $htm;
        }        
        break;
      // sello : holon planetario => rol de familias terrestres
      case 'sel_pla_pos':
        $_fam_sel = [
          1=>[ 20,  5, 10, 15 ],
          2=>[  1,  6, 11, 16 ],
          3=>[ 17,  2,  7, 12 ],
          4=>[ 18,  3,  8, 13 ],
          5=>[ 14, 19,  4,  9 ]
        ];
        foreach( _hol::_('sel_pla_cen') as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam);
          $htm = "
          <div class='tex_ali-cen'>
            <p>Kin <b class='ide'>{$_fam->nom}</b></p>
            "._hol::ima("sel_pla_cen",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 17rem; height: 11rem;"])."
          </div>
          <ul class='ite'>";
            foreach( $_fam_sel[$_dat->ide] as $sel ){
              $_sel = _hol::_('sel',$sel);
              $_pla_mer = _hol::_('sel_pla_mer',$_sel->pla_mer);
              $_pla_hem = _hol::_('sel_pla_hem',$_sel->pla_hem);
              $htm .= "
              <li>
                "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
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
      case 'sel_hum_ele': 
        $ope = []; $lis_pos = 0; $col = 4;
        foreach( _hol::_('sel_hum_ext') as $_ext ){
          $_ele = _hol::_('sel_cro_ele',$_ext->ele); 
          $nom = explode(' ',_tex::art_del($_ele->nom)); $cla = array_pop($nom); $nom = implode(' ',$nom);
          $ope[$lis_pos] = [ 
            'eti'=>"div", 'class'=>"ite", 'htm'=> _hol::ima("sel_hum_ext",$_ext,['class'=>"mar_der-1"])."                  
            <p class='tit tex_ali-izq'><b class='ide'>$_ext->nom</b><c>:</c>
              <br>Clan {$nom} <c class='let_col-4-$col'>{$cla} $_ele->col</c></p>" 
          ];
          $lis_pos += 5; 
          $col = _num::ran($col+1,4);
        }
        $sel = [];
        foreach( _hol::_('sel_cod') as $_sel ){
          $_fam = _hol::_('sel_cro_fam',$_sel->cro_fam);
          $_hum_ded = _hol::_('sel_hum_ded',$_fam->hum_ded);
          $sel []= _obj::atr([ 
            'hum_ded'=>$_hum_ded->nom, 
            'nom'=>"Tribu "._tex::art_del($_sel->nom)." $_sel->nom_col", 
            'ima_nom'=>[ 'htm'=>_hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
            'nom_cod'=>$_sel->nom_cod,
            'ima_cod'=>[ 'htm'=>_hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ]
          ]);
        }
        $_ = _app_est::lis($sel,[ 'tit'=>$ope, 'opc'=>['cab_ocu'] ]);
        break;
      // sello : holon humano => rol de familias terrestres
      case 'sel_hum_fam':
        $fam = [];
        $sel = [];
  
        foreach( _hol::_('sel_hum_ded') as $_ded ){
          $_fam = _hol::_('sel_cro_fam',$_ded->fam);
          $fam[$lis_pos] = [
            'eti'=>"div", 'class'=>"ite", 'htm'=> _hol::ima("sel_hum_ded",$_ded,['class'=>"mar_der-1"])."                  
            <p class='tit tex_ali-izq'><b class='ide'>Familia Terrestre $_fam->nom</b><c>:</c>
              <br>Familia de $_fam->cod<c>:</c> Dedos {$_ded->nom}".( in_array($_ded->nom,['Anular','Pulgar']) ? "es" : "s" )." </p>" 
          ];
          $lis_pos += 4;
          foreach( explode(', ',$_fam->sel) as $_sel ){
            $_sel = _hol::_('sel',$_sel);
            $_hum_ext = _hol::_('sel_hum_ext',$_sel->hum_ext);
            $sel []= _obj::atr([
              'nom'=>"Tribu "._tex::art_del($_sel->nom)." $_sel->nom_col", 
              'ima_nom'=>[ 'htm'=>_hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
              'nom_cod'=>$_sel->nom_cod,
              'ima_cod'=>[ 'htm'=>_hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ],
              'hum_ext'=>$_hum_ext->nom
            ]);
          }
        }
  
        $_ = _app_est::lis($sel,[ 'tit'=>$fam, 'opc'=>['cab_ocu'] ]);
        break;
      // sello : holon humano => extremidades del humano
      case 'sel_hum_ext':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_dat ){
          $_ele = _hol::_('sel_cro_ele',$_dat->ele); $_ []= "
  
            "._hol::ima("sel_hum_ext",$_dat,['class'=>"mar_der-1"])."
  
            <p><b class='ide'>Cromática "._tex::art_del($_ele->nom)."</b><c>:</c>
              <br>{$_dat->nom}
            </p>";
        }        
        break;
      // sello : holon humano => dedos del humano
      case 'sel_hum_ded':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam); $_ []= "
  
            "._hol::ima("sel_hum_ded",$_dat,['class'=>"mar_der-1"])."
  
            <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
              <br>{$_dat->nom}
            </p>";
        }        
        break;
      // sello : holon humano => centros galácticos del humano
      case 'sel_hum_cen':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam); $_ []= "
  
          "._hol::ima("sel_hum_cen",$_dat,['class'=>"mar_der-1"])."
  
          <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
            <br>"._tex::art($_dat->nom)." <c>-></c> {$_fam->hum}
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

              "._app_ope::tog_ope()."

              "._app_ope::var('atr',"hol.kin.ide",[ 
                'nom'=>"ver kin", 'ope'=>[ 'onchange'=>"{$_eje}this);" ] 
              ])."
            </fieldset>

            <fieldset class='ope'>

              "._app::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"{$_eje}this,'nav');" ])."
            
            </fieldset>

          </div>

          <output class='hol-kin'></output>
          
        </form>
                
        <nav>";
          $_nav_cas = 0; $_nav_ond = 0;
          $arm_tra = 0; $arm_cel = 0;
          $cro_est = 0; $cro_ele = 0;
          $gen_enc = 0; $gen_cel = 0;
          foreach( _hol::_('kin') as $_kin ){

            // castillo
            if( $_kin->nav_cas != $_nav_cas ){
              $_nav_cas = $_kin->nav_cas;
              $_cas = _hol::_('kin_nav_cas',$_kin->nav_cas); 
              if( $_nav_cas != 1 ){ $_ .= "
                  </section>

                </section>
                ";
              }$_ .= "
              "._app_ope::tog(['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."
              <section data-kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>
                <p cas='{$_cas->ide}'>"._app::let("Corte {$_cas->cor}: {$_cas->fun}")."</p>
              ";
            }
            // génesis
            if( $_kin->gen_enc != $gen_enc ){
              $gen_enc = $_kin->gen_enc;
              $_gen = _hol::_('kin_gen_enc',$_kin->gen_enc); $_ .= "
              <p class='tit' data-gen='{$_gen->ide}'>GÉNESIS "._tex::let_may($_gen->nom)."</p>";
            }
            // onda encantada
            if( $_kin->nav_ond != $_nav_ond ){
              $_nav_ond = $_kin->nav_ond;
              $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
              $_sel = _hol::_('sel',$_ond->sel); 
              $ond = _num::ran($_ond->ide,4);

              if( $_nav_ond != 1 && $ond != 1 ){ $_ .= "
                </section>";
              }
              $_ .= "
              "._app_ope::tog([
                'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 
                'htm'=>_app::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
              ])."
              <section data-kin_nav_ond='{$_ond->ide}'>
                <p class='let-enf' ond='{$_ond->ide}'>Poder "._tex::art_del($_sel->pod)."</p>";
            }
            // célula armónica : titulo + lectura
            if( $_kin->arm_cel != $arm_cel ){
              $arm_cel = $_kin->arm_cel;
              $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
              </section>

              "._app_ope::tog([
                'eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,
                'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>"._app::let(_tex::let_may($_cel->des))
              ])."
              <section data-kin_arm_cel='{$_cel->ide}'>
              ";
            }
            // kin : ficha + nombre + encantamiento
            $_ .= "
            <div data-kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
              <div class='hol-kin'>
                "._hol::ima("kin",$_kin->ide,['class'=>'mar-aut'])."
                <p>
                  <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='let_col-4-{$_kin->arm_cel_dia}'>"._app::let(_tex::let_may($_kin->nom))."</c>
                  <br><q>"._app::let($_kin->des)."</q>                  
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

        foreach( _hol::_('kin_arm_tra') as $_tra ){

          $_lis_cel = [];
          foreach( _hol::_('sel_arm_cel') as $_cel ){
            $arm_cel++;
            $_cel = _hol::_('kin_arm_cel',$arm_cel); $_lis_cel []= "
            <a class='tex' href='#kin_arm_cel-{$_cel->ide}-'>
              <n>{$_cel->ide}</n><c>.</c> <b class='ide'>{$_cel->nom}</b>"._app::let(": {$_cel->des}")."
            </a>";
          }
          $_lis []= [
            'ite'=>"Trayectoria $_tra->ide: Tono $_tra->ton, $_tra->ton_des",
            'lis'=>$_lis_cel
          ];
        }
        _ele::cla( $ope['nav'], "dis-ocu" );
        $ope['opc'] = ['tog','ver'];
        $_ = "

        "._app_ope::tog(
          [ 'eti'=>'h3', 'htm'=>_app::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], 
          [ 'ico'=>['class'=>"ocu"] ]
        )."
        <nav"._htm::atr($ope['nav']).">

          "._app_lis::val($_lis,$ope)."

        </nav>";    
        break;        
      // kin : espectros galácticos
      case 'kin_cro_sel':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_cro_est') as $_est ){ 
          $_sel = _hol::_('sel',$_est->sel); $_ []= "
          "._hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
          <p>
            <n>{$_est->ide}</n><c>.</c> El espectro galáctico <b class='let_col-4-{$_est->ide}'>{$_est->col}</b><c>:</c> 
            Estación "._tex::art_del($_sel->nom)."
          </p>";
        }          
        break;
      // kin : aventura por estaciones
      case 'kin_cro_ton':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_cro_ond') as $_ond ){ $_ []= "
          "._hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
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
  
        foreach( _hol::_('kin_nav_ond') as $_ond ){
          // génesis
          if( $gen != $_ond->gen_enc ){ 
            $_gen = _hol::_('kin_gen_enc',$gen = $_ond->gen_enc); $_[]="
            <p class='tit'>{$_gen->lec}<c>:</c> <b class='ide'>Génesis {$_gen->nom}</b><c>.</c></p>";
          }
          if( $cel != $_ond->gen_cel ){ 
            $_cel = _hol::_('kin_gen_cel',$cel = $_ond->gen_cel); $_[]="
            <p class='tit'>Célula <n>{$_cel->ide}</n> de la memoria del Génesis<c>:</c> <b class='val'>{$_cel->nom}</b></p>";
          }
          if( $cas != $_ond->nav_cas ){ 
            $_cas = _hol::_('kin_nav_cas',$cas = $_ond->nav_cas); $_[]="
            <p class='tit'>
              El Castillo <b class='let_col-5-{$_cas->ide}'>".str_replace('del ','',$_cas->nom)."</b> "._tex::art_del($_cas->acc)."<c>:</c> La corte "._tex::art_del($_cas->cor)."<c>,</c> poder {$_cas->pod}
            </p>";
          }              
          $_ []= _hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."              
          <p><n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c> <q>"._app::let($_ond->enc_des)."</q></p>";
        }          
        break;
      // psi : por tonos galácticos
      case 'psi_lun':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_lun ){
          $_ []= _hol::ima("ton",$_lun->ton,['class'=>"mar_der-2"])."
          <p>
            <b class='ide'>"._tex::let_ora(_num::dat($_lun->ide,'pas'))." Luna</b>
            <br>Luna "._tex::art_del($_lun->ton_car)."<c>:</c> "._app::let($_lun->ton_pre)."
          </p>";
        }    
        break;        
      // psi : fechas desde - hasta
      case 'psi_lun_fec':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_('psi_lun') as $_lun ){
          $_[] = _hol::ima("ton",$_lun->ton,['class'=>"mar_der-3"])."
          <p>
            <b class='ide'>$_lun->nom</b> <n>".intval($_lun->ton)."</n>
            <br>"._app::let($_lun->fec_ran)."
          </p>";
        }$_[] = "
        <span ima></span>
        <p>
          <b class='ide'>Día Verde</b> o Día Fuera del Tiempo
          <br><n>25</n> de Julio
        </p>";        
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// 13 Lunas en Movimiento
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
            foreach( _hol::_('lun_arm') as $_hep ){
              $_ []= _app::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._app::let("): $_hep->des");
            }
            break;
          case 'pod':
            foreach( _hol::_('lun_arm') as $_hep ){
              $_ []= _app::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._app::let(", $_hep->pod $_hep->car");
            }        
            break;            
          }
        }
        break;
      // luna : heptadas lunares
      case 'lun_arm_col':
        $est_ope['atr'] = [ 'ide','nom','col','dia','pod' ];
        $est_ope['opc'] []= 'cab_ocu';
        $_ = _app_est::lis("hol.lun_arm", $est_ope, $ope );
        break;
      // kin : castillos del encantamiento
      case 'kin_nav_cas':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_cas ){ $_ [] = 

          _hol::ima($ide,$_cas,['class'=>"mar_der-2"])."
  
          <p>
            <b class='ide'>Castillo $_cas->col $_cas->dir "._tex::art_del($_cas->acc)."</b><c>:</c>
            <br>Ondas Encantadas <n>"._num::int($_cas->ond_ini)."</n> <c>-</c> <n>"._num::int($_cas->ond_fin)."</n>
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
          $_ []= _hol::ima("psi_lun",$_lun = _hol::_('psi_lun',$ite),['class'=>"mar_der-2"])."
          <p>
            $htm
            <br>"._app::let($_lun->fec_ran)."
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
          "._hol::ima("kin",$_kin = _hol::_('kin',39),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio  <n>1.992</n> <c>-</c> <n>25</n> Julio <n>1.993</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Dos</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',144),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.993</n> <c>-</c> <n>25</n> Julio <n>1.994</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Tres</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',249),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.994</n> <c>-</c> <n>25</n> Julio <n>1.995</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Cuatro</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',94),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.995</n> <c>-</c> <n>25</n> Julio <n>1.996</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Cinco</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',199),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.996</n> <c>-</c> <n>25</n> Julio <n>1.997</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Seis</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',44),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.997</n> <c>-</c> <n>25</n> Julio <n>1.998</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Siete</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',149),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.998</n> <c>-</c> <n>25</n> Julio <n>1.999</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Ocho</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',254),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.999</n> <c>-</c> <n>25</n> Julio <n>2.000</n><c>.</c></p>
        </div>";
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Sonda de Arcturus
    static function arc( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Tratado del Tiempo
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
        $_ = _app_est::lis("hol.{$ide}", $est_ope, $ope);

        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Telektonon
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
        $_ = _app_lis::bar( $_, $ope);          
        break;
      // sello : holon solar => circuitos de telepatía
      case 'sel_sol_cir':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_cir ){
          $pla = explode('-',$_cir->pla);
          $_pla_ini = _hol::_('sel_sol_pla',$pla[0]);
          $_pla_fin = _hol::_('sel_sol_pla',$pla[1]);
          $htm = 
          _hol::ima($ide,$_cir,['class'=>""])."
          <div>
            <p class='tit'>Circuito <n>$_cir->ide</n><c>:</c> <b class='ide'>$_pla_ini->nom <c>-</c> $_pla_fin->nom</b></p>
            <ul>
              <li>Circuito "._app::let($_cir->nom)."</li>
              <li><p>"._app::let("$_cir->cod unidades - $_cir->des")."</p></li>
              <li><p>Notación Galáctica<c>,</c> números de código "._app::let("{$_cir->sel}: ");
              $lis_pos = 0;
              foreach( explode(', ',$_cir->sel) as $sel ){ 
                $lis_pos++; 
                $_sel = _hol::_('sel', $sel == 00 ? 20 : $sel);                      
                $htm .= _app::let( $_sel->pod_tel.( $lis_pos == 3 ? " y " : ( $lis_pos == 4 ? "." : ", " ) ) );
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
        foreach( _hol::_($ide) as $_hep ){
          $_ []= _app::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._app::let(", $_hep->pod $_hep->car");
        }        
        break;              
      // luna : lines de fuerza
      case 'lun_fue': 
        foreach( _hol::_($ide) as $_lin ){
          $_ []= _app::let("{$_lin->nom}: {$_lin->des}");
        }
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Proyecto Rinri
    static function rin( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      // plasma : años por oráculos de la profecía
      case 'rad_ani': 
        $ope['lis'] = ['class'=>"ite"];
        $ope['ite'] = ['class'=>"mar_aba-1"];      

        foreach( _hol::_('rad') as $_rad ){ $_ []=
          _hol::ima("rad",$_rad,['class'=>"mar_der-1"])."
          <p>
            <b class='ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
            <br><q>"._app::let($_rad->rin_des)."<c>.</c></q>
          </p>";
        }
        $_ = _app_lis::val($_,$ope);
        break;                    
      // luna : días del cubo
      case 'lun_cub':
        foreach( _hol::_($ide) as $_cub ){
          $_ []= 
          "<div class='ite'>
            "._hol::ima("sel",$_cub->sel,['class'=>"mar_der-1"])."              
            <div>
              <p class='tit'>Día <n>$_cub->lun</n><c>,</c> CUBO <n>$_cub->ide</n><c>:</c> $_cub->nom</p>
              <p class='des'>$_cub->des</p>
            </div>              
          </div>
          <p class='let-enf tex_ali-cen'>"._app::let($_cub->tit)."</p>
          ".( !empty($_cub->lec) ? "<p class='let-cur tex_ali-cen'>"._app::let($_cub->lec)."</p>" : ""  )."
          <q>"._app::let($_cub->afi)."</q>";
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
            <table"._htm::atr($ope['lis']).">
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
                foreach( _hol::_($ide) as $_lun ){ $_ .= "
                  <tr>
                    <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                    foreach( explode(', ',$_lun->kin_pag) as $kin ){ $_ .= "
                      <td>"._hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                    }$_ .= "   
                  </tr>";
                }$_ .= "
              </tbody>
            </table>";        
            break;
          // días psi del cubo - laberinto del guerrero
          case 'cub': 
            $_ = "
            <table"._htm::atr($ope['lis']).">
              <tbody>";
                foreach( _hol::_($ide) as $_lun ){ $_ .= "
                  <tr>
                    <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                    foreach( explode('-',$_lun->kin_cub) as $kin ){ $_ .= "
                      <td>"._hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                    }$_ .= "
                    <td>Kines "._app::let(_tex::mod($_lun->kin_cub,"-"," - "))."</td>
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
          $_ = _app_est::lis("hol.lun", $est_ope, $ope );
        }
        break;
      // psi-cronos : cromaticas entonadas
      case 'psi_cro_arm':
        foreach( [ 1, 2, 3, 4 ] as $arm ){
        
          $cro_arm = _hol::_('psi_cro_arm',$arm);
  
          $_ []= "Cromática <c class='let_col-4-$arm'>$cro_arm->col</c><br>"._app::let("$cro_arm->nom: $cro_arm->des");
        }        
        break;      
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Dinámicas del Tiempo
    static function din( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Tablas del Tiempo
    static function tab( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Atomo del tiempo 
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
            $cod = _num::val($pos,2);
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
          $_ = _app_lis::bar( $_, $ope);                 
          break;
        }        
        break;        
      // 7 plasmas radiales
      case 'rad_pla':
        $ide = "rad";
        $pla_qua = [3,4,7];
        _ele::cla($ope['lis'],'ite');
        switch( $_ide[1] ){
        // lineas de fuerza + quantums
        case 'fue':
          foreach( _hol::_($ide) as $rad ){
            $fue_pre = _hol::_('rad_pla_fue',$rad->pla_fue_pre);
            $fue_pos = _hol::_('rad_pla_fue',$rad->pla_fue_pos);
            $_ []= 
            _hol::ima($ide,$rad,['class'=>"mar_der-2"])."
            <div>        
              <p><b class='ide'>$rad->nom</b> <b class='col-".substr($rad->col,0,3)."'>$rad->col</b></p>
              <div class='ite'>
                $fue_pre->nom
                "._hol::ima("rad_pla_fue",$fue_pre)."
                <c class='sep'>+</c>
                $fue_pos->nom
                "._hol::ima("rad_pla_fue",$fue_pos)."
                
                <p><c class='sep'>:</c> "._app::let($rad->pla_fue)." <c>(</c>Días "._app::let($rad->dia)."<c>)</c></p>
              </div>
            </div>";
            if( in_array($rad->ide,$pla_qua) ){
              $qua = _hol::_('rad_pla_qua',$rad->pla_qua);
              $_ []= 
              _hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
              "<p>"._app::let($qua->ato_des)."</p>";
            }
          }          
          break;
        // afirmaciones + quantums
        case 'des': 
          foreach( _hol::_($ide) as $rad ){
            $_ []= 
            _hol::ima($ide,$rad,['class'=>"mar_der-2"])."
            <p>
              "._app::let("$rad->nom: $rad->pla_des.")."
              <br>
              <q>"._app::let($rad->pla_lec)."</q>
            </p>";            
            if( in_array($rad->ide,$pla_qua) ){
              $qua = _hol::_('rad_pla_qua',$rad->pla_qua);
              $_ []= 
              _hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
              "<p>"._app::let($qua->pla_des)."</p>";
            }
          }
          break;
        // cubo del radion + quantums
        case 'cub':
          $qua = NULL;
          $qua_ide = 0;
          foreach( _hol::_($ide) as $rad ){
            // titulo por quantum
            if( $qua_ide != $rad->pla_qua ){
              $qua = _hol::_('rad_pla_qua',$rad->pla_qua); 
              $qua_ide = $rad->pla_qua; $_ []= "
              <p class='tit anc-100 tex_ali-cen'>"._app::let($qua->nom)."</p>";
            }
            $cub = _hol::_('rad_pla_cub', $rad->ide);
            $cha = _hol::_('rad_hum_cha', $rad->hum_cha);
            $_ []= 
            "<div>".              
              _hol::ima('rad_hum_cha',$cha,['class'=>"mar_der-2"]).
              _hol::ima('rad_pla_cub',$cub,['class'=>"mar_der-2"])."
            </div>
            <div>
              <p>"._app::let("$rad->nom (Días $rad->dia): $cha->pos Chakra, $cha->cod o $cha->nom")."</p>
              <p>"._app::let("Cubo del Radión - $cub->nom")."</p>
            </div>
            ";
            if( in_array($rad->ide,$pla_qua) ){              
              $_ []= 
              _hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
              "<p>"._app::let($qua->cub_des)."</p>";
            }
          }
          break;
        }
        break;
      // 6 tipos de electricidad
      case 'rad_pla_ele': 
        _ele::cla($ope['lis'],'ite');
        foreach( _hol::_($ide) as $pla_ele ){
          $_ []= 
          _hol::ima($ide,$pla_ele,['class'=>"mar_der-2"])."
          <p>
            <b class='ide'>$pla_ele->nom</b> o <b class='ide'>$pla_ele->cod</b>
            <br>
            "._app::let($pla_ele->des)."
          </p>";
        }
        break;
      // 12 lineas de fuerza
      case 'rad_pla_fue': 
        _ele::cla($ope['lis'],'ite');
        foreach( _hol::_($ide) as $pla_fue ){
          $ele_pre = _hol::_('rad_pla_ele',$pla_fue->ele_pre);
          $ele_pos = _hol::_('rad_pla_ele',$pla_fue->ele_pos);
          $_ []= 
          _hol::ima($ide,$pla_fue,['class'=>"mar_der-2"])."
          <div>
            <p><b class='ide'>$pla_fue->nom</b></p>
            <div class='val'>
              <b class='mar_hor-1'>$ele_pre->cod</b>
              "._hol::ima("rad_pla_ele",$ele_pre)."
              <c class='ope sep'>$pla_fue->ele_ope</c>
              <b class='mar_hor-1'>$ele_pos->cod</b>              
              "._hol::ima("rad_pla_ele",$ele_pos)."
            </div>                        
          </div>";
        }        
        break;
      // 4 atómos y 2 tetraedros
      case 'lun_pla_ato':
        switch( $_ide[1] ){
        // Atomo telepatico del tiempo
        case 'tie': 
          _ele::cla($ope['lis'],'ite');
          $pla_tet = [2,4];
          $tet_ide = 0;
          foreach( _hol::_($ide) as $ato ){
            $_ []= 
            _hol::ima($ide,$ato,['class'=>"mar_der-2"])."

            <p>Semana <n>$ato->ide</n><c>:</c> Átomo Telepático del <b class='ide'>"._app::let($ato->nom)."</b></p>";
            // tetraedros
            if( in_array($ato->ide,$pla_tet) ){
              $tet_ide++;
              $tet = _hol::_('lun_pla_tet',$tet_ide); $_ []= 
              _hol::ima('lun_pla_tet',$tet,['class'=>"mar_der-2"])."
              <p>"._app::let($tet->des.".")."</p>";
            }
          }$_ []= 
          _app::ima('hol/ima/lun',['class'=>"mar_der-2 tam-15"])."
          <p>También el Día <n>28</n><c>,</c> la transposición fractal de las ocho caras de los dos tetraedros resulta en la creación del Octaedro de Cristal en el centro de la Tierra<c>.</c></p>";            
          break;
        // Cargas por Colores Semanales 
        case 'car':
          _ele::cla($ope['lis'],'ite');
          foreach( _hol::_($ide) as $ato ){
            $col = _hol::_('lun_arm',$ato->ide)->col;                        
            $_ []= 
            _hol::ima($ide,$ato,['class'=>"mar_der-2"])."
            <p>
              Semana <n>$ato->ide</n><c>,</c> <b class='col-".substr($col,0,3)."'>{$col}</b>"._app::let(": $ato->car".".")."
              <br>
              Secuencia "._app::let($ato->car_sec.".")."
            </p>";
          }
          break;
        // ficha semanal
        case 'hep':
          $ato = _hol::_($ide,$ope['ide']);
          $_ = "
          <p class='tit tex_ali-izq'>"._app::let("Semana $ato->ide, Heptágono de la Mente "._tex::art_del($ato->hep))."</p>
          <div class='ite'>
            "._hol::ima($ide, $ato, ['class'=>'mar_der-2'])."
            <ul class='mar_arr-0'>
              <li>"._app::let("Un día = $ato->val.")."</li>
              <li>"._app::let("Valor lunar = $ato->val_lun.")."</li>
              <li>"._app::let("Forma $ato->hep_cub en el Holograma Cúbico 7:28.")."</li>
            </ul>                        
          </div>";
          break;          
        }
        break;
      // 4 semanas: cualidad + poder + kin
      case 'lun_arm':
        foreach( _hol::_($ide) as $arm ){
          $ato = _hol::_('lun_pla_ato',$arm->ide);          
          $_[]="
          <p class='tit'>$arm->nom<c>,</c> <b class='col-".substr($arm->col,0,3)."'>$arm->col</b><c>:</c></p>          
          <div class='ite'>            
            "._hol::ima($ide,$arm,['class'=>"mar_der-2"])."
            <ul>
              <li>"._app::let($arm->des)."</li>
              <li>"._app::let($arm->tel_des)."</li>
              <li>".( count(explode(', ',$ato->val_kin)) == 1 ? "Código del Kin" : "Códigos de Kines" )." "._app::let($ato->val_kin)."</li>            
            </ul>
          </div>";
        }
        break;
      // 7 tierras de ur
      case 'lun_pla_tie':
        foreach( _hol::_($ide) as $tie ){
          $rad = _hol::_('rad',$tie->rad);
          $_[]="
          <p class='tit tex_ali-izq'>"._app::let("$tie->nom, Tierra de UR $tie->ide")."</p>
          <div class='ite'>
            "._hol::ima('rad',$tie->rad,['class'=>"mar_der-2"])."
            <p>
              <q>$tie->des</q>
              <br>"._app::let("Día $tie->dia, $tie->tel, Tablero del Plasma.")."
              <br>"._app::let("Plasma Radial $rad->ide, $rad->nom: $rad->pla_fue")."
              <br>"._app::let("( $tie->pos última Luna, $tie->pos Luna Mística )")."
            </p>
          </div>";
        }
        break;
      // ejes del Cubo Primigenio y el Átomo Telepático del Tiempo
      case 'lun_pla_eje':
        _ele::cla($ope['lis'],'ite');
        foreach( _hol::_($ide) as $eje ){
          $tie = explode(', ',$eje->tie);
          $ini = _hol::_('lun_pla_tie',$tie[0]);
          $fin = _hol::_('lun_pla_tie',$tie[1]);
          $_[]=
          _hol::ima('rad',$ini->rad,['class'=>"mar_der-2"]).
          _hol::ima('rad',$fin->rad,['class'=>"mar_der-2"])."
          <div>
            <p class='tit'>Eje $eje->nom</p>
            <p>"._app::let("{$ini->ide}° Tierra de UR $ini->nom y {$fin->ide}° Tierra de UR $fin->nom")."</p>
          </div>
          ";
        }
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Sincronotron
    static function umb( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }
  }

  // Datos : códigos y cuentas
  class _hol_dat {

    static string $IDE = "_hol_dat-";
    static string $EJE = "_hol_dat.";

    static function kin( string $ide, mixed $dat, array $ope = [] ) : string {
      $_ = []; 
      $_ide = explode('-',$ide);
      $lis_tip = "val"; $lis_pos = 0;
      $est_ope = [ 'opc'=>['htm','cab_ocu'] ];      
      switch( $_ide[0] ){
      // Parejas del oraculo
      case 'par':
        $dat = _hol::_('kin',$dat);
        switch( $_ide[1] ){
        // Propiedades : palabras clave del kin + sello + tono
        case 'pro':

          $_par_atr = !empty($ope['par']) ? $ope['par'] : ['fun','acc','mis'];
    
          $_ton_atr = !empty($ope['ton']) ? $ope['ton'] : ['acc'];
    
          $_sel_atr = !empty($ope['sel']) ? $ope['sel'] : ['car','des'];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});
    
            $ite = [ _hol::ima("kin",$_kin) ];
    
            foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= _app::let($_par->$atr); }
    
            $_ton = _hol::_('ton',$_kin->nav_ond_dia);
            foreach( $_ton_atr as $atr ){ if( isset($_ton->$atr) ) $ite []= _app::let($_ton->$atr); }
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);            
            foreach( $_sel_atr as $atr ){  if( isset($_sel->$atr) ) $ite []= _app::let($_sel->$atr); }
    
            $_ []= $ite;
          }
          break;
        // Ciclos : posiciones en ciclos del kin
        case 'cic':
          $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});
    
            $ite = [ _hol::ima("kin",$_kin) ];
    
            foreach( $_atr as $atr ){
              $ite []= _hol::ima("kin_{$atr}",$_kin->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          break;
        // Grupos : sincronometría del holon por sellos
        case 'gru':
          $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});                            
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);
    
            $ite = [ _hol::ima("kin",$_kin), $_par->nom, $_sel->pod ];
    
            foreach( $_atr as $atr ){
              $ite []= _hol::ima("sel_{$atr}",$_sel->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          break;
        }
        $_ = _app_est::lis( $_, $est_ope, $ope);
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_$ide", $lis_tip, $ope ) : $_;      
    }
  }