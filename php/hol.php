<?php

  // Aplicacion
  class _hol_app {

    // main : app.cab.art/val
    public function __construct( _app $_app ){
      // inicializo datos    
      $_uri = $_app->uri;
      $_val = _hol::val( date('Y/m/d') );

      // inicio : 
      if( empty($_uri->cab) ){

        $this->ini($_app,$_val);
      }
      // por seccion : introduccion
      elseif( empty($_uri->art) ){
        
      }
      // por articulo : bibliografía + informes + tableros
      else{
        ob_start();
        switch( $_uri->cab ){
        // tablero
        case 'tab': 
          $this->tab($_app,$_val); 
          break;
        // bib + dat + val
        default: 
          $this->{$_uri->cab}($_app,$_val); 
          break;
        }
        // cargo html
        $_app->sec = ob_get_clean();        
        // cargo todos los datos utilizados por esquema
        if( $_uri->cab == 'tab' ) $_app->rec['dat']['hol'] = [];
      }
      // recursos del documento
      $_app->rec['jso'] []= $_uri->esq;
      $_app->rec['css'] []= $_uri->esq;
      $_app->rec['eje'] .= "
        var \$_hol_app = new _hol_app( { val : "._obj::cod($_val)." } );
      ";
    }
    // Inicio : explicacion del sitio
    public function ini( _app $_app, array $_hol ) : void {
      
      // cargo articulo
      ob_start(); ?>

      <?=_doc_val::tex('adv',"Este sitio aún se está en construcción, puede haber contenido incompleto, errores o fallas. Estamos trabajando en ello...")?>

      <article class="">
        <h2>Inicio</h2>

        <p>En el panel de la izquierda encontrarás el menú y las aplicaciones del sitio<c>.</c></p>

        <ul class="lis sep">
          <li>
            <?=_doc::ico('app_nav',['class'=>"mar_der-1"])?>Desde el menú podés seleccionar la fecha del sistema y acceder a los distintos contenidos<c>,</c> donde encontrarás libros y glosarios<c>,</c> datos relacionados a las cuentas del sincronario<c>,</c> Tableros e Informes según el valor diario seleccionado<c>.</c>
          </li>
          <li>
            <?=_doc::ico('dat_des',['class'=>"mar_der-1"])?>En el mismo panel<c>,</c> pero en la seccion inferior<c>,</c> está el botón de ayuda que aparecerá en la páginas que lo requieran por su complejidad<c>.</c> Haz click en él para saber más sobre el contenido de este sitio<c>.</c>
          </li>
        </ul>
        
        <p>Las demás aplicaciones irán apareciendo según la página en la que te encuentres<c>:</c> en los artículos aparecerá un índice<c>,</c> en los tableros aparecerán los operadores<c>,</c> etc<c>.</c> A medida que el sitio crezca se irán agregando nuevas funcionalidades<c>...</c></p>

    
      </article>
      <?php
      $_app->sec = ob_get_clean();

      // cargo tutorial:
      ob_start(); ?>
        <!-- Fecha del sistema -->
        <section>
          <h3>La Fecha del Sistema</h3>

          <div class="val jus-cen">
            <?= _doc::ico('app_nav',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _doc::ico('fec_val',['class'=>"mar_hor-1"]) ?>
          </div>

          <p>Desde aquí puedes cambiar la fecha operativa del sistema<c>.</c> Esta fecha se utiliza para posicionarte en los tableros y mostrar los datos correspondientes en el Diario<c>.</c> La fecha se inicializa con el día actual<c>,</c> pero puedes cambiarla en cualquier momento utilizando el calendario gregoriano o las fechas del sincronario <c>(</c>ciclo NS<c>)</c><c>.</c> Si quieres saber más sobre los ciclos del sincronario<c>,</c> haz click <a href="https://www.13lunas.net/boletines/Rinri/V03N3_1.htm" target="_blank">aquí</a><c>.</c></p>
          
        </section>      
        <!-- Bibliografía -->
        <section>

          <h3>La bibliografía</h3>

          <div class="val jus-cen">
            <?= _doc::ico('app_nav',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _doc::ico('tex_lib',['class'=>"mar_hor-1"]) ?>
          </div>

          <p>Aquí podrás encontrar la mayoría de los libros en los cuales se basa la teoría del Sincronario<c>:</c> <q>La ley del Tiempo</q><c>.</c> Esta fué desarrollada por <a href="https://es.wikipedia.org/wiki/Jos%C3%A9_Arg%C3%BCelles" target="_blank">José Argüelles</a> quien organizó una fundación con el mismo nombre <c>(</c><a href="http://www.lawoftime.org" target="_blank">The Law of Time</a><c>)</c><c>.</c> Todos sus libros y materiales se pueden descargar gratuitamente desde <a href="https://13lunas.net/mapa.htm#biblioteca" target="_blank">La Biblioteca de <n>13</n> Lunas</a><c>.</c></p>

          <p>En este sitio se adaptó el formato de texto de cada libro para una página web<c>,</c> se agregaron los íconos correspondientes a cada símbolo e imágenes que amplían su contenido visualamente<c>.</c> También se muestran en orden cronológico<c>,</c> ya que este conocimiento es incremental y los temas se entrelazan y completan en cada publicación<c>.</c></p>

          <p>En la página de cada libro hay un índice en el panel izquierdo<c>,</c> que puedes ocultar o mostrar haciendo click en el botón<c>.</c> Los items del índice que figuran en el libro son los mismos<c>,</c> pero se agregaron nuevos para segmentar la información y poder acceder desde enlaces<c>.</c></p>

        </section>
        <!-- Artículos -->
        <section>          

          <h3>Los Artículos</h3>

          <div class="val jus-cen">
            <?= _doc::ico('app_nav',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _doc::ico('tex_inf',['class'=>"mar_hor-1"]) ?>
          </div>

          <p>En esta sección se cargan los Articulos con información relacionada a los temas del Sincronario<c>.</c></p>

          <p>En primer lugar<c>,</c> el glosario es un rejunte de todos los que aparecen en los distintos libros<c>,</c> al cual se agregó un filtro para buscar entre sus términos y accesos al libro donde se encuentra<c>.</c></p>
          
          <p>Luego<c>,</c> se irán agregando nuevos a medida que el sitio crezca o sean requeridos para ampliar la información tratada<c>.</c></p>

        </section>
        <!-- Cuentas -->
        <section>

          <h3>Las cuentas</h3>

          <div class="val jus-cen">
            <?= _doc::ico('app_nav',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _doc::ico('num_val',['class'=>"mar_hor-1"]) ?>
          </div>

          <p>En esta sección podrás encontrar datos que van apareciendo en los distintos libros y están relacionados a cada código y cuenta<c>,</c> junto con sus respectivas agrupaciones y subciclos<c>.</c> Haciendo referencia a la fuente<c>,</c> se describen brevemente para introducir al lector en sus conceptos y bridarle acceso directo al material donde puede encontrarlo<c>.</c> A partir de su comprensión se pueden realizar lecturas y relaciones entre distintas posiciones<c>,</c> fechas o firmas galácticas<c>.</c></p>      

        </section>
        <!-- Tableros -->
        <section>
          <h3>Los tableros</h3>

          <div class="val jus-cen">
            <?= _doc::ico('app_nav',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _doc::ico('lis_tab',['class'=>"mar_hor-1"]) ?>
          </div>
          
          <p>Desde el menú principal puedes acceder a un listado de tableros relacionados al posicionamiento según la fecha seleccionada en el Menú<c>.</c> Para cada tablero se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por ej<c>:</c> el <a href="<?=SYS_NAV."hol/tab/kin-tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

          <p>Desde allí podrás acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones<c>.</c></p>
          
        </section>
        <!-- Diario -->
        <section>
          <h3>El Diario</h3>

          <div class="val jus-cen">
            <?= _doc::ico('app_nav',['class'=>"mar_hor-1"]) ?>
            <c>-></c>
            <?= _doc::ico('fec_dia',['class'=>"mar_hor-1"]) ?>
          </div>

          <p>Desde esta sección podrás ver un detalle de cada código por cuenta correspondiente al valor seleccionado en la fecha del sistema<c>.</c> La información se presenta en forma de fichas<c>,</c> con los atributos principales del código<c>,</c> descripciones simbólicas<c>,</c> las subcuentas relacionadas<c>,</c> y las posiciones en los tableros por sus ciclos de tiempo<c>.</c></p>
          
        </section>
      <?php
      $_app->ope['doc_dat']['htm'] = ob_get_clean();
    }
    // Bibliografía
    public function bib( _app $_app ) : void {
      $_nav = $_app->nav;
      $_uri = $_app->uri;
      $_bib = SYS_NAV."hol/bib/";            
      // busco archivos : html-php
      if( !empty( $rec = $_uri->rec($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){
        // cargo directorio para imágenes del libro
        $_dir = $_uri->dir();
        include( $rec );
      }
      else{
        echo _doc_val::tex('err',"No existe el archivo '$val'");
      }
    }
    // Artículos
    public function art( _app $_app ) : void {
      $_nav = $_app->nav;
      $_uri = $_app->uri;
      $_bib = SYS_NAV."hol/bib/";
      switch( $_uri->art ){
      // glosarios
      case 'ide':
        ?>
        <article>
          <h2>Buscar</h2>

          <p>En el siguiente listado podés encontrar los términos y sus significados por Libro.</p>

          <form class="ite">

            <?= _doc_val::var('val','ver',[ 
              'des'=>"Filtrar...",
              'ite'=>[ 'class'=>"tam-cre" ],
              'htm'=>_doc_val::ver(['cue'=>0, 'eje'=>"_hol_art.ide('ver',this)" ], [ 'class'=>"anc-100" ])
            ]) ?>

          </form>

          <div style="height: 75vh; overflow: auto;">
            <table>

              <thead>
                <tr>
                  <th scope="col" data-atr="ide" >Libro</th>
                  <th scope="col" data-atr="nom" >Término</th>
                  <th scope="col" data-atr="des" >Definicion</th>
                </tr>
              </thead>

              <tbody>
              <?php
              $_lib = FALSE;
              foreach( _dat::get("api.app_art_ide",[
                'ver'=>"`esq` = 'hol'", 'ord'=>"`ide` ASC, `nom` ASC"
              ]) as $i => $v ){ 
                if( !$_lib || $_lib->ide != explode('_',$v->ide)[0] ){
                  $_lib = _dat::get("api.app_art",[ 
                    'ver'=>"esq = 'hol' AND cab = 'bib' AND ide = '$v->ide'", 
                    'opc'=>"uni" 
                  ]);
                }
                echo "
                <tr>
                  <td data-atr='ide'><a href='$_bib/$_lib->ide' target='_blank'>"._doc::let($_lib->nom)."</a></td>
                  <td data-atr='nom'>"._doc::let($v->nom)."</td>
                  <td data-atr='des'>"._doc::let($v->des)."</td>            
                </tr>";
              }?>
              </tbody>

            </table>
          </div>

        </article>
        <?php          
        break;
      // codigos y cuentas
      case 'dat':
        ?>
        <!-- 7 Plasmas -->
        <article>
          <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_doc::let($_nav[1]['01']->nom)?></h2>
          <!-- dìas de la semana -->
          <section>          
            <h3 id="<?="_{$_nav[2]['01']['01']->pos}-"?>"><?=_doc::let($_nav[2]['01']['01']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">El encantamiento del Sueño</a> se divide al año en <n>13</n> lunas de <n>28</n> días cada una<c>.</c> Cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>

            <p>Posteriormente<c>,</c> en el libro de <a href="<?=$_bib?>lun#_02-07-" target="_blank">Las <n>13</n> lunas en movimiento</a>, se mencionan los plasmas para nombrar a cada uno de los días de la semana<c>-</c>heptada<c>.</c></p>

            <?=_doc_est::lis('api.hol_rad',[ 'atr'=>['ide','nom','pod'] ])?>

          </section>
          <!-- sellos de la profecia -->
          <section>          
            <h3 id="<?="_{$_nav[2]['01']['02']->pos}-"?>"><?=_doc::let($_nav[2]['01']['02']->nom)?></h3>

            <p>En <a href="<?=$_bib?>tel#_02-06-" target="_blank">El telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

            <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario<c>:</c> familia portal y familia señal <c>(</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

            <?=_doc_est::lis('api.hol_rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ])?>

            <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

            <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

            <?=_doc_est::lis('api.hol_rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ])?>

          </section>
          <!-- heptágono de la mente -->
          <section>          
            <h3 id="<?="_{$_nav[2]['01']['03']->pos}-"?>"><?=_doc::let($_nav[2]['01']['03']->nom)?></h3>

            <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

            <p>En el <a href="<?=$_bib?>rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>            

            <?=_doc_est::lis('api.hol_rad',[ 'atr'=>['ide','nom','pla_cub','pla_cub_pos'] ])?>

          </section>
          <!-- autodeclaraciones diarias -->
          <section>          
            <h3 id="<?="_{$_nav[2]['01']['04']->pos}-"?>"><?=_doc::let($_nav[2]['01']['04']->nom)?></h3>

            <p>En <a href="<?=$_bib?>ato#_03-06-" target="_blank">Átomo del Tiempo</a> se describen las afirmaciones correspondientes a las Autodeclaraciones Diarias de Padmasambhava para cada plasma<c>.</c></p>

            <?=_doc_est::lis('api.hol_rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','pla_lec'] ])?>

          </section>
          <!-- componenetes electrónicos -->
          <section>          
            <h3 id="<?="_{$_nav[2]['01']['05']->pos}-"?>"><?=_doc::let($_nav[2]['01']['05']->nom)?></h3>

            <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

            <?=_doc_est::lis('api.hol_rad',[ 'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_fue','pla_fue_pre','pla_fue_pos'] ])?>

          </section>      
        </article>
        <!-- 13 Tonos -->
        <article>
          <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_doc::let($_nav[1]['02']->nom)?></h2>
          <!-- rayos de pulsación -->
          <section>          
            <h3 id="<?="_{$_nav[2]['02']['01']->pos}-"?>"><?=_doc::let($_nav[2]['02']['01']->nom)?></h3>

            <p>En <cite>el Factor Maya</cite><c>,</c>
              se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c>
              Y se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c>
            </p>

            <?=_doc_est::lis('api.hol_ton',[ 'atr'=>['ide','gal'] ])?>

          </section>
          <!-- simetría especular -->
          <section>          
            <h3 id="<?="_{$_nav[2]['02']['02']->pos}-"?>"><?=_doc::let($_nav[2]['02']['02']->nom)?></h3>

            <p>En el <cite>Factor Maya</cite> 
              se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c>
              Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a>>.</c>
            </p>

            <?=_doc_est::lis('api.hol_ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>
            
          </article>        
          <!-- principios de la creacion -->
          <section>          
            <h3 id="<?="_{$_nav[2]['02']['03']->pos}-"?>"><?=_doc::let($_nav[2]['02']['03']->nom)?></h3>

            <p>En <cite>el Encantamiento del sueño</cite> 
              se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c> 
              De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c>
            </p>

            <?=_doc_est::lis('api.hol_ton',[ 'atr'=>['ide','nom','des','acc'] ])?>

          </section>
          <!-- O.E. de la Aventura -->
          <section>          
            <h3 id="<?="_{$_nav[2]['02']['04']->pos}-"?>"><?=_doc::let($_nav[2]['02']['04']->nom)?></h3>

            <p>En el <cite>Encantamiento del sueño</cite> 
              se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c>
              Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c>
            </p>

            <?=_doc_est::lis('api.hol_ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 
              'tit_cic'=>['ond_enc']
            ])?>
            
          </section>        
          <!-- pulsar dimensional -->
          <section>          
            <h3 id="<?="_{$_nav[2]['02']['05']->pos}-"?>"><?=_doc::let($_nav[2]['02']['05']->nom)?></h3>

            <p>En <cite>el Encantamiento del sueño</cite> 
              
              <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a>
              <c>.</c>
            </p>

            <?=_doc_est::lis('api.hol_ton_dim')?>
            
          </section>
          <!-- pulsar matiz -->
          <section>          
            <h3 id="<?="_{$_nav[2]['02']['06']->pos}-"?>"><?=_doc::let($_nav[2]['02']['06']->nom)?></h3>

            <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_ton_mat')?>
            
          </section>        
        </article>
        <!-- 20 Sellos -->
        <article>
          <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_doc::let($_nav[1]['03']->nom)?></h2>
          <!-- signos direccionales -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['01']->pos}-"?>"><?=_doc::let($_nav[2]['03']['01']->nom)?></h3>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cic_dir')?>

          </section>
          <!-- 3 etapas del desarrollo -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['02']->pos}-"?>"><?=_doc::let($_nav[2]['03']['02']->nom)?></h3>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ])?>

          </section>
          <!-- 4 etapas evolutivas -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['03']->pos}-"?>"><?=_doc::let($_nav[2]['03']['03']->nom)?></h3>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cic_men',[ 'atr'=>['sel','nom','des','lec'] ])?>

          </section>
          <!-- 5 familias ciclicas -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['04']->pos}-"?>"><?=_doc::let($_nav[2]['03']['04']->nom)?></h3>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ])?>

          </section>        
          <!-- Colocacion cromática -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['05']->pos}-"?>"><?=_doc::let($_nav[2]['03']['05']->nom)?></h3>
            
            <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
            
            <?=_doc_est::lis('api.hol_sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>

          </section>
          <!-- 5 familias terrestres -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['06']->pos}-"?>"><?=_doc::let($_nav[2]['03']['06']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ])?>

          </section>
          <!-- 4 clanes cromáticos -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['07']->pos}-"?>"><?=_doc::let($_nav[2]['03']['07']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ])?>

          </section>        
          <!-- Colocación armónica -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['08']->pos}-"?>"><?=_doc::let($_nav[2]['03']['08']->nom)?></h3>

            <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>

            <?=_doc_est::lis('api.hol_sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>

          </section>
          <!-- 4 Razas raiz cósmicas -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['09']->pos}-"?>"><?=_doc::let($_nav[2]['03']['09']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ])?>

          </section>
          <!-- células del tiempo-->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['10']->pos}-"?>"><?=_doc::let($_nav[2]['03']['10']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ])?>

          </section>
          <!-- parejas del oráculo -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['11']->pos}-"?>"><?=_doc::let($_nav[2]['03']['11']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>

          </section>                  
          <!-- parejas analogas -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['12']->pos}-"?>"><?=_doc::let($_nav[2]['03']['12']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_par_ana')?>

          </section>
          <!-- parejas antípodas -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['13']->pos}-"?>"><?=_doc::let($_nav[2]['03']['13']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_par_ant')?>

          </section>
          <!-- parejas ocultas -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['14']->pos}-"?>"><?=_doc::let($_nav[2]['03']['14']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_par_ocu')?>

          </section>        
          <!-- holon Solar -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['15']->pos}-"?>"><?=_doc::let($_nav[2]['03']['15']->nom)?></h3>

            <p>El código 0-19</p>              

            <?=_doc_est::lis('api.hol_sel_cod',[ 'atr'=>['ide','sol_pla_des'], 
              'tit_cic'=>['sol_cel','sol_cir','sol_pla'] 
            ])?>
          </section>
          <!-- orbitas planetarias -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['16']->pos}-"?>"><?=_doc::let($_nav[2]['03']['16']->nom)?></h3>

            <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_sol_pla')?>

          </section>
          <!-- células solares -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['17']->pos}-"?>"><?=_doc::let($_nav[2]['03']['17']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_sol_cel')?>

          </section>
          <!-- circuitos de telepatía -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['18']->pos}-"?>"><?=_doc::let($_nav[2]['03']['18']->nom)?></h3>

            <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_sol_cir')?>

          </section>             
          <!-- holon planetario -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['19']->pos}-"?>"><?=_doc::let($_nav[2]['03']['19']->nom)?></h3>
            
            <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>

          </section>
          <!-- campos dimensionales -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['20']->pos}-"?>"><?=_doc::let($_nav[2]['03']['20']->nom)?></h3>

            <p></p>

          </section>          
          <!-- centros galácticos -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['21']->pos}-"?>"><?=_doc::let($_nav[2]['03']['21']->nom)?></h3>

            <p></p>

            <?=_doc_est::lis('api.hol_sel_pla_cen')?>

          </section>
          <!-- flujos de la fuerza-g -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['22']->pos}-"?>"><?=_doc::let($_nav[2]['03']['22']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_pla_res')?>

          </section>        
          <!-- holon humano -->
          <section>
            <h3 id="<?="_{$_nav[2]['03']['23']->pos}-"?>"><?=_doc::let($_nav[2]['03']['23']->nom)?></h3>

            <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 
              'tit_cic'=>['cro_ele'] 
            ])?>
          </section>        
          <!-- Centros Galácticos -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['24']->pos}-"?>"><?=_doc::let($_nav[2]['03']['24']->nom)?></h3>

            <?=_doc_est::lis('api.hol_sel_hum_cen')?>

          </section>
          <!-- Extremidades -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['25']->pos}-"?>"><?=_doc::let($_nav[2]['03']['25']->nom)?></h3>

            <?=_doc_est::lis('api.hol_sel_hum_ext')?>

          </section>                     
          <!-- dedos -->        
          <section>            
            <h3 id="<?="_{$_nav[2]['03']['26']->pos}-"?>"><?=_doc::let($_nav[2]['03']['26']->nom)?></h3>

            <?=_doc_est::lis('api.hol_sel_hum_ded')?>

          </section>
          <!-- lados -->        
          <section>
            <h3 id="<?="_{$_nav[2]['03']['27']->pos}-"?>"><?=_doc::let($_nav[2]['03']['27']->nom)?></h3>

            <?=_doc_est::lis('api.hol_sel_hum_res')?>

          </section>
        </article>
        <!-- 28 Días de la Luna -->
        <article>
          <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_doc::let($_nav[1]['04']->nom)?></h2>
          <!-- días -->
          <section>          
            <h3></h3>

            <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_lun',[ 'atr'=>['ide','arm','rad','ato_des'] ])?> 

          </section>
          <!-- 4 heptadas -->
          <section>          
            <h3 id="<?="_{$_nav[2]['04']['01']->pos}-"?>"><?=_doc::let($_nav[2]['04']['01']->nom)?></h3>

            <p>En <a href="<?=$_bib?>lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>

            <p>En <a href="<?=$_bib?>" target="_blank">el Telektonon</a><c>.</c></p>

            <p>En <a href="<?=$_bib?>" target="_blank">el átomo del tiempo</a><c>.</c></p>

          </section>    
        </article>        
        <!-- 52 Posiciones del Castillo -->
        <article>
          <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_doc::let($_nav[1]['05']->nom)?></h2>

        </article>
        <!-- 64 Codones del ADN -->
        <article>
          <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=_doc::let($_nav[1]['06']->nom)?></h2>

        </article>        
        <!-- 260 kines -->
        <article>
          <h2 id="<?="_{$_nav[1]['07']->pos}-"?>"><?=_doc::let($_nav[1]['07']->nom)?></h2>

        </article>  
        <!-- 365 psi-cronos -->
        <article>
          <h2 id="<?="_{$_nav[1]['08']->pos}-"?>"><?=_doc::let($_nav[1]['08']->nom)?></h2>

        </article>                        
        <?php
        // cargo ayuda
        ob_start(); ?>
          <!-- Introduccion -->
          <section>

            <h3>Introducción</h3>

            <p>Los sistemas del Sincronario están basados en códigos y cuentas<c>.</c> Los <n>13</n> tonos galácticos crean el módulo de sincronización para las <n>13</n> lunas del giro solar y las <n>13</n> trayectorias armónicas del giro galáctico<c>.</c> Cada kin está compuesto por uno de los <n>13</n> tonos galácticos<c>,</c> y uno de los <n>20</n> sellos solares<c>.</c> Cada día del año se encuentra en una de las <n>13</n> lunas y se asocia a uno de los <n>7</n> plasma radiales<c>.</c> Un castillo está compuesto por <n>52</n> posiciones que se dividen en <n>4</n> ondas encantadas de <n>13</n> unidades<c>.</c> Con el castillo se codifican las <n>4</n> estaciones espectrales del giro galáctico<c>,</c> las <n>4</n> estaciones cíclicas del giro solar<c>,</c> los <n>52</n> anillos solares del ciclo Nuevo Siario y los <n>52</n> años del sendero del destino para el kin planetario<c>.</c> A su vez<c>,</c> la nave del tiempo tierra está compuesta de <n>5</n> castillos para abarcar los <n>260</n> kines del giro galáctico<c>.</c> Todos estos son ejemplos de las cuentas utilizadas para medir el tiempo con el concepto de Matriz Radial<c>.</c> Cada cuenta va del <n>1</n> al <n>n</n><c>,</c> siendo <n>n</n> el valor total que define la cuenta<c>.</c> De esta manera<c>:</c> los plasmas val del <n>1<c>-</c>7</n><c>,</c> los tonos del <n>1<c>-</c>13</n><c>,</c> los sellos del <n>1<c>-</c>20</n><c>,</c> las lunas del <n>1<c>-</c>28</n><c>,</c>etc<c>.</c></p>

          </section>
        <?php
        $_app->ope['doc_dat']['htm'] = ob_get_clean();  
        break;
      }      
    }
    // Valores
    public function val( _app $_app ) : void {
      $_nav = $_app->nav;
      $_uri = $_app->uri;
      $_bib = SYS_NAV."hol/bib/";
      $_ide = explode('_',$_uri->art);
      switch( $_ide[0] ){
      case 'kin': 
        $_kin = _hol::_('kin',$_uri->val);
        if( empty($_ide[1]) ){ ?>
          <!-- ficha del encantamiento -->
          <article>
            <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=_doc::let($_nav[1]['01']->nom)?></h2>

            <?= _hol_fic::kin('enc',$_kin) ?>

            <br>

            <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

            <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

          </article>
          <!-- parejas -->
          <article>
            <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=_doc::let($_nav[1]['02']->nom)?></h2>

            <?= _hol_tab::kin('par',[ 
              'ide'=>$_kin->ide,
              'sec'=>[ 'par'=>0 ],
              'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
            ],[
              'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
              'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
            ])?>
            <!-- Descripciones -->
            <h3 id="<?="_{$_nav[2]['02']['01']->pos}-"?>"><?=_doc::let($_nav[2]['02']['01']->nom)?></h3>
            <section>

              <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            

              <?= _hol_fic::kin('par',$_kin) ?>

            </section>
            <!-- Lecturas diarias -->
            <h3 id="<?="_{$_nav[2]['02']['02']->pos}-"?>"><?=_doc::let($_nav[2]['02']['02']->nom)?></h3>
            <section>

              <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

              <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>

              <?= _hol_lis::kin('par-pro',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>

              <br>
              
              <p>En <a href="<?=$_bib?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

              <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>

              <?= _hol_fic::kin('par-lec',$_kin) ?>

            </section>  
            <!-- Posiciones en el tzolkin -->  
            <h3 id="<?="_{$_nav[2]['02']['03']->pos}-"?>"><?=_doc::let($_nav[2]['02']['03']->nom)?></h3>
            <section>

              <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

              <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>

              <?= _hol_lis::kin('par-cic',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>

            </section>  
            <!-- Sincronometría del holon -->
            <h3 id="<?="_{$_nav[2]['02']['04']->pos}-"?>"><?=_doc::let($_nav[2]['02']['04']->nom)?></h3>
            <section>

              <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

              <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

              <br>

              <?= _hol_lis::kin('par-gru',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>

            </section>
            
          </article>
          <!-- Nave del tiempo -->
          <article>
            <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=_doc::let($_nav[1]['03']->nom)?></h2>
            <!-- x52 : Castillo Fractal -->  
            <h3 id="<?="_{$_nav[2]['03']['01']->pos}-"?>"><?=_doc::let($_nav[2]['03']['01']->nom)?></h3>
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
                  'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
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
            <h3 id="<?="_{$_nav[2]['03']['02']->pos}-"?>"><?=_doc::let($_nav[2]['03']['02']->nom)?></h3>
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
                'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
              ], [
                'ond'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
                'pos'=>[ 'style'=>"width:6rem; height:6rem;" ]
              ]) ?>

            </section>  
          </article>
          <!-- Giro Galáctico -->
          <article>
            <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=_doc::let($_nav[1]['04']->nom)?></h2>
            <!-- x20 : Trayectoria Armónica -->  
            <h3 id="<?="_{$_nav[2]['04']['01']->pos}-"?>"><?=_doc::let($_nav[2]['04']['01']->nom)?></h3>
            <section>
              <?php
              $_tra = _hol::_('kin_arm_tra',$_kin->arm_tra);
              $_ton = _hol::_('ton',$_kin->nav_ond_dia);
              ?>    

              <nav>
                <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>el Gran Ciclo</a> en el <cite>Factor Maya</cite><c>...</c></p>
              </nav>

              <?= _hol_fic::kin('arm_tra',$_tra) ?>

              <p><?= _doc::let($_tra->lec) ?></p>

              <?= _hol_tab::kin('arm_tra', [
                'ide'=>$_tra,
                'sec'=>[ 'par'=>1 ],
                'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
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
            <h3 id="<?="_{$_nav[2]['04']['02']->pos}-"?>"><?=_doc::let($_nav[2]['04']['02']->nom)?></h3>
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
            <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=_doc::let($_nav[1]['05']->nom)?></h2>
            <!-- x65 : Estación Galáctica -->
            <h3 id="<?="_{$_nav[2]['05']['01']->pos}-"?>"><?=_doc::let($_nav[2]['05']['01']->nom)?></h3>
            <section>
              <?php
              $_est = _hol::_('kin_cro_est',$_kin->cro_est);
              ?>

              <nav>
                <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
              </nav>    

            </section>
            <!-- x5 : Elemento Cromático -->  
            <h3 id="<?="_{$_nav[2]['05']['02']->pos}-"?>"><?=_doc::let($_nav[2]['05']['02']->nom)?></h3>
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
            <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=_doc::let($_nav[1]['06']->nom)?></h2>
          </article>
          <?php
        }
        break;
      case 'ton': 
        $_ton = _hol::_('ton',$_uri->val);
        ?>
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
        break;
      case 'sel': 
        $_sel = _hol::_('sel',$_uri->val);
        ?>
        <!-- Ficha -->
        <article>

          <?= _hol_fic::sel('atr',$_sel) ?>

          <p><?= _doc::let($_sel->des_pro) ?></p>

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
      case 'psi': 
        $_psi = _hol::_('psi',$_uri->val);
        ?>
        <!-- x91: Estaciones Solares -->

        <!-- x28: Giros Lunares -->

        <!-- x20: Vinales -->

        <!-- x7: Heptadas -->

        <!-- x5: Cromaticas -->        
        <?php
        break;
      case 'lun': 
        ?>
        <?php
        break;
      case 'rad': 
        ?>
        <?php
        break;
      }      
    }
    // Tableros
    public function tab( _app $_app, array &$_val ) : void {
      $_uri = $_app->uri;
      $art_tab = explode('-',$_uri->art);
      if( isset($art_tab[1]) && method_exists("_hol_tab",$ide = $art_tab[0]) ){
        
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
        }// actualizo valor de sesion
        $_SESSION['hol-val'] = $hol_val;

        // operadores del tablero
        $_tab =  _app::tab('hol',str_replace('-','_',$_uri->art));

        $tab_ide = "hol.{$ide}";
        $tab_ele = [];
        $tab_ope = !empty($_tab->ope) ? $_tab->ope : [];

        // inicializo valores
        $tab_ope['val'] = [];
        // fecha => muestro listado por ciclos
        if( !empty( $_val['fec'] ) ){
          // joins
          if( in_array($ide,['kin','psi']) ){
            // cargo estructuras
            $tab_ope['est'] = [ 'api'=>[ "fec", "hol_kin", "hol_psi" ] ];
            // cargo datos
            $tab_ope['dat'] = _hol::dat( $ide, $_val );
            // acumulados
            $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
            if( !empty($tab_ope['opc']) ){
              $tab_ope['val']['acu']['opc'] = 1;
            }
          }
          // valor seleccionado
          $tab_ope['val']['pos'] = $_val;
        }
        // valores diario
        $dia_ele = [ 'lis'=>[ 'style'=>"height: 63vh;" ], 'opc'=>['tog','ver'] ];
        
        $_app->ope['dia'] = [ 'ico'=>"fec_val", 'bot'=>"ini", 'tip'=>"pan", 'nom'=>"Diario", 'htm'=>"

          <section class='mar_aba-1'>
          
            "._hol_val::fec($_val,['eje'=>"_hol_tab._val"])."

          </section>

          "._doc_val::nav('bar',[
            'kin' => [ 'nom'=>"Orden Sincrónico", 'des'=>"", 'htm'=>_hol_val::kin($_val['kin'],$dia_ele) ],
            'psi' => [ 'nom'=>"Orden Cíclico",    'des'=>"", 'htm'=>_hol_val::psi($_val['psi'],$dia_ele) ]
            ],[            
            'sel' => "kin"
          ])
        ];
        // operadores del tablero
        $ope = _obj::nom(_app_tab::$OPE,'ver',['val','opc','cue']);
        foreach( $ope as $ope_ide => $ope_tab ){

          if( !empty( $htm = _app_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele ) ) ){

            $_app->ope[$ope_ide] = [ 'ico'=>$ope_tab['ico'], 'bot'=>"ini", 'tip'=>"pan", 'nom'=>$ope_tab['nom'], 
              'nav'=>[ 'style'=>"" ],
              'htm'=>$htm
            ];
          }
        }
        // operador de lista
        $ope = _app_tab::$OPE['lis'];
        $_app->ope['est'] = [ 'ico'=>$ope['ico'], 'bot'=>"ini", 'tip'=>"win", 'nom'=>$ope['nom'],  
          'art'=>[ 'style'=>"max-width: 55rem; height: 90vh;" ],
          'htm'=>_app_tab::ope('lis',"api.hol_{$ide}",$tab_ope)
        ];
        // imprimo tablero en página principal
        echo "
        <article class='anc_max-fit'>
          "._hol_tab::$ide($art_tab[1], $tab_ope, [ 
            'sec'=>[ 'class'=>"mar-aut" ],
            'pos'=>[ 'onclick'=>"_app_tab.val('mar',this);" ]
          ])."
        </article>";
      }
      else{ 
        echo _doc_val::tex('err',"No existe el tablero '$_uri->art'");
      }
      // cargo tutorial    
      ob_start(); ?>
        <!-- Diario -->
        <section>

          <div class="val jus-cen">
            <?= _doc::ico('fec_val',['class'=>"mar_hor-1"]) ?>
            <h3>Diario</h3>
          </div>

          <p></p>

        </section>
        <!-- Opciones -->
        <section>

          <div class="val jus-cen">
            <?= _doc::ico('opc_bin',['class'=>"mar_hor-1"]) ?>
            <h3>Opciones</h3>
          </div>

          <p>Puedes cambiar los colores de fondo<c>,</c> seleccionar fichas y ver contenido numero o textual para cada posición<c>.</c> Según los atributos del tablero definido por sus cuentas<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

        </section>
        <!-- Operador -->
        <section>

          <div class="val jus-cen">
            <?= _doc::ico('lis_est',['class'=>"mar_hor-1"]) ?>
            <h3>Operador</h3>
          </div>

          <p>Puedes seleccionar aquellas posiciones activas y ver la sumatoria del kin correspondiente a ellas<c>.</c> Tambien puedes realizar selecciones grupales aplicando filtros por estructuras de cuentas<c>,</c> fechas o posiciones<c>.</c></p>

        </section>
        <!-- Indice -->
        <section>

          <div class="val jus-cen">
            <?= _doc::ico('lis_nav',['class'=>"mar_hor-1"]) ?>
            <h3>Índice</h3>
          </div>

          <p>Encontrarás un listado de los códigos y cuentas incluidos en el armado de tablero<c>.</c> Para cada código se muestra un total de elementos activos <c>(</c> por posición<c>,</c> marcas<c>,</c> seleccion y opciones <c>)</c> </p>

        </section>
        <!-- Listado -->
        <section>

          <div class="val jus-cen">
            <?= _doc::ico('lis_ite',['class'=>"mar_hor-1"]) ?>
            <h3>Listado</h3>
          </div>

          <p>Puedes acceder a los datos de las posiciones ya sea por los acumulados<c>,</c> o puedes verlos todos<c>,</c> y aplicar filtros<c>.</c> 
          Luego puedes seleccionar las columnas con los datos que deseas ver<c>,</c> y mostrar los titulos por agrupaciones o lecturas para cada posición<c>.</c></p>

        </section>        
      <?php
      $_app->ope['doc_dat']['htm'] = ob_get_clean();

    }
    // Kin Planetario
    public function usu( _app $_app, array &$_val ) : void {
      global $_usu;
      $_nav = $_app->nav;
      $_uri = $_app->uri;

    }
  }

  // Bibliografía
  class _hol_bib {
    static string $IDE = "_hol_bib-";
    static string $EJE = "_hol_bib.";

    // Tutorial del Sincronario  
    static function tut( string $ide, array $ope = [] ) : string {
      $_ = [];
      $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $ide ){
      // tonos : propiedades
      case 'ton': 
        $est_ope['atr'] = ['ide','nom','car','pod','acc'];
        $_ = _doc_est::lis("api.hol_ton", $est_ope, $ope );
        break;
      // sellos : propiedades
      case 'sel': 
        $est_ope['atr'] = ['ide','nom','pod','acc','car'];
        $_ = _doc_est::lis("api.hol_sel", $est_ope, $ope );
        break;              
      // luna : descripcion de las heptadas
      case 'lun_arm': 
        foreach( _hol::_($ide) as $_hep ){
          $_ []= _doc::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let("): $_hep->des");
        }
        break;
              
      }
      return is_array($_) ? _app_dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
    }// Tierra en ascenso
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
          <p>"._doc::let($_sim->des)."</p>";
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
              <li><p><c>-></c> "._doc::let($_dir->des)."</p></li>
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
            <br>"._doc::let($_sel->cic_ser_des)."
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
            <br>"._doc::let($_sel->cic_luz_des)."
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
          <li>Índice Armónico <n>"._num::int($_kin->fac)."</n><c>:</c> período "._doc::let($_kin->fac_ran)."</li>
          <li><q>"._doc::let($_sel->arm_tra_des)."</q></li>
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
  
            <p class='tit'>"._doc::let(_tex::let_may("CICLO ".($num = intval($_tra->ide)).", Baktún ".( $num-1 )))."</p>";
          }
          $_ []= "
  
          "._hol::ima("kin",$_kin,['class'=>"mar_der-1"])."
  
          <p>
            <n>{$lis_pos}</n><c>.</c> <b class='ide'>{$_sel->may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
            <br>"._doc::let($_kin->fac_ran)."
          </p>";
        }          
        break;
      // kin : 1 trayectoria con detalles por katun ( ciclos del modelo morfogenetico )
      case 'kin_fec':
        $ond = 0;
        $_ = "
        <table>";
          if( !empty($ope['tit']) ){ $_.="
            <caption>".( !empty($ope['tit']['htm']) ? "<p class='tit'>"._doc::let($ope['tit']['htm'])."</p>" : '' )."</caption>";
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
                <td colspan='3'>{$_sel->may}<c>:</c> "._doc::let($_ond->fac_ran)." <q>"._doc::let($_ond->fac_des)."</q></td>
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
                <q>"._doc::let($_sel->arm_tra_des)."</q>
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
              <br>"._doc::let($_tra->ran)." <c><=></c> "._doc::let($_tra->may)."
            </p>
          </div>";
          $lis = [];
          foreach( explode('; ',$_tra->lec) as $ite ){
            $lis []= "<c>-></c> "._doc::let($ite);
          }
          $_[] = $htm._doc_lis::val($lis,[ 'lis'=>['class'=>"pun"] ]);
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
          $_[] = $htm._doc_lis::val($lis,[ 'lis'=>['class'=>'ite'] ]);
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
            <n>{$_ond->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> "._doc::let($_ond->fac_ran)."
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
        $_ = _doc_est::lis("api.hol_ton", $est_ope, $ope );
        break;
      // tonos : aventura de la onda encantada 
      case 'ton_ond':
        $_atr = array_merge([ 
          'ima'=>_obj::atr(['ide'=>'ima','nom'=>''])
          ], _app::dat_atr('api',"hol_ton", [ 'ide','ond_pos','ond_pod','ond_man' ])
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
  
        $_ = _doc_est::lis($_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit, 'opc'=>['cab_ocu'] ],$ope);              
        break;
            
      // tonos : pulsares dimensionales
      case 'ton_dim':
        foreach( _hol::_('ton_dim') as $_dat ){ $htm = "
          <p>
            <n>{$_dat->ide}</n><c>.</c> <b class='ide'>Pulsar de la {$_dat->pos} dimensión</b><c>:</c> <b class='val'>Dimensión {$_dat->nom}</b>
            <br>Tonos "._doc::let("{$_dat->ton}: {$_dat->ond}")."
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
          <p><n>{$_dat->ide}</n><c>.</c> <b class='ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='val'>"._doc::let($_dat->cod)."</b><c>:</c>
            <br>Tonos "._doc::let("{$_dat->ton}: {$_dat->ond}")."
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
                <br><q>"._doc::let($_sel->arm_raz_des)."</q>
              </p>
            </li>";
            $sel += 4;
            if( $sel > 20 ) $sel -= 20;                  
          }
          $htm.="
          </ul>
          <q>"._doc::let(_tex::let_ora($_raz_pod)." ha sido "._tex::art_gen("realizado",$_raz_pod).".")."</q>";
          $_ []= $htm;
        }        
        break;
      // sello : colocacion armónica => células del tiempo
      case 'sel_arm_cel':
        $lis_pos = 1;

        foreach( _hol::_($ide) as $_dat ){ $htm = "
          <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='ide'>{$_dat->nom}</b></p>
          <q>"._doc::let($_dat->des)."</q>
          <ul class='ite'>";
          foreach( _hol::_('sel_arm_raz') as $cro ){ $_sel = _hol::_('sel',$lis_pos); $htm .= "
            <li>
              "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
              <p>
                <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                <br><q>"._doc::let($_sel->arm_cel_des)."</q>
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
          <p class='tit'><b class='ide'>Clan "._tex::art_del($_dat->nom)."</b>"._doc::let(": Cromática {$_dat->col}.")."</p>
          ".( !empty($_dat->des_ini) ? "<p>"._doc::let($_dat->des_ini)."</p>" : '' )."
          <ul class='ite'>";
          for( $fam=1; $fam<=5; $fam++ ){ 
            $_sel = _hol::_('sel',$sel); 
            $_fam = _hol::_('sel_cro_fam',$fam); $htm .= "
            <li sel='{$_sel->ide}' cro_fam='{$fam}'>
              "._hol::ima("sel",$_sel,[ 'class'=>"mar_der-1" ])."
              <p>
                <n>{$sel}</n><c>.</c> <b class='ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
                <br><q>"._doc::let($_sel->cro_ele_des)."</q>
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
                <li>Los <n>5</n> <b class='ide'>planetas {$_orb->nom}es</b><c>:</c> "._doc::let($_orb->des)."</li>";                        
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
                        <br><q>"._doc::let($_sel->sol_pla_des)."</q>
                      </p>
                    </li>
                    <li>
                      "._hol::ima("sel",$_par,['class'=>"mar_der-1"])."
                      <p>
                        <b class='val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                        <br><q>"._doc::let($_par->sol_pla_des)."</q>
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
        $_ = _doc_est::lis($sel,[ 'tit'=>$ope, 'opc'=>['cab_ocu'] ]);
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
  
        $_ = _doc_est::lis($sel,[ 'tit'=>$fam, 'opc'=>['cab_ocu'] ]);
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

              "._doc_val::tog_ope()."

              "._doc_val::var('atr',"api.hol_kin.ide",[ 
                'nom'=>"ver kin", 'ope'=>[ 'onchange'=>"{$_eje}this);" ] 
              ])."
            </fieldset>

            <fieldset class='ope'>

              "._doc::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"{$_eje}this,'nav');" ])."
            
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
              "._doc_val::tog(['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."
              <section data-kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>
                <p cas='{$_cas->ide}'>"._doc::let("Corte {$_cas->cor}: {$_cas->fun}")."</p>
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
              "._doc_val::tog([
                'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 
                'htm'=>_doc::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
              ])."
              <section data-kin_nav_ond='{$_ond->ide}'>
                <p class='let-enf' ond='{$_ond->ide}'>Poder "._tex::art_del($_sel->pod)."</p>";
            }
            // célula armónica : titulo + lectura
            if( $_kin->arm_cel != $arm_cel ){
              $arm_cel = $_kin->arm_cel;
              $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
              </section>

              "._doc_val::tog([
                'eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,
                'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>"._doc::let(_tex::let_may($_cel->des))
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
                  <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='let_col-4-{$_kin->arm_cel_dia}'>"._doc::let(_tex::let_may($_kin->nom))."</c>
                  <br><q>"._doc::let($_kin->des)."</q>                  
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
              <n>{$_cel->ide}</n><c>.</c> <b class='ide'>{$_cel->nom}</b>"._doc::let(": {$_cel->des}")."
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

        "._doc_val::tog(
          [ 'eti'=>'h3', 'htm'=>_doc::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], 
          [ 'ico'=>['class'=>"ocu"] ]
        )."
        <nav"._htm::atr($ope['nav']).">

          "._doc_lis::val($_lis,$ope)."

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
          <p><n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c> <q>"._doc::let($_ond->enc_des)."</q></p>";
        }          
        break;
      // psi : por tonos galácticos
      case 'psi_lun':
        $ope['lis'] = ['class'=>"ite"];

        foreach( _hol::_($ide) as $_lun ){
          $_ []= _hol::ima("ton",$_lun->ton,['class'=>"mar_der-2"])."
          <p>
            <b class='ide'>"._tex::let_ora(_num::dat($_lun->ide,'pas'))." Luna</b>
            <br>Luna "._tex::art_del($_lun->ton_car)."<c>:</c> "._doc::let($_lun->ton_pre)."
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
            <br>"._doc::let($_lun->fec_ran)."
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
              $_ []= _doc::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let("): $_hep->des");
            }
            break;
          case 'pod':
            foreach( _hol::_('lun_arm') as $_hep ){
              $_ []= _doc::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let(", $_hep->pod $_hep->car");
            }        
            break;            
          }
        }
        break;
      // luna : heptadas lunares
      case 'lun_arm_col':
        $est_ope['atr'] = [ 'ide','nom','col','dia','pod' ];
        $est_ope['opc'] []= 'cab_ocu';
        $_ = _doc_est::lis("api.hol_lun_arm", $est_ope, $ope );
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
            <br>"._doc::let($_lun->fec_ran)."
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
        $_ = _doc_est::lis("api.hol_{$ide}", $est_ope, $ope);

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
            <img src='".SYS_NAV."_/hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta {$pos}-1' class='mar_der-1' style='width:24rem;'>";
            if( $opc_fin ) $htm .= "
            <img src='".SYS_NAV."_/hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta {$pos}-2' class='mar_izq-1' style='width:24rem;'>";
            $htm .= "
          </div>";
          $_ []= $htm;
        }
        $_ = _doc_lis::bar( $_, $ope);          
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
              <li>Circuito "._doc::let($_cir->nom)."</li>
              <li><p>"._doc::let("$_cir->cod unidades - $_cir->des")."</p></li>
              <li><p>Notación Galáctica<c>,</c> números de código "._doc::let("{$_cir->sel}: ");
              $lis_pos = 0;
              foreach( explode(', ',$_cir->sel) as $sel ){ 
                $lis_pos++; 
                $_sel = _hol::_('sel', $sel == 00 ? 20 : $sel);                      
                $htm .= _doc::let( $_sel->pod_tel.( $lis_pos == 3 ? " y " : ( $lis_pos == 4 ? "." : ", " ) ) );
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
          $_ []= _doc::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let(", $_hep->pod $_hep->car");
        }        
        break;              
      // luna : lines de fuerza
      case 'lun_fue': 
        foreach( _hol::_($ide) as $_lin ){
          $_ []= _doc::let("{$_lin->nom}: {$_lin->des}");
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
            <br><q>"._doc::let($_rad->rin_des)."<c>.</c></q>
          </p>";
        }
        $_ = _doc_lis::val($_,$ope);
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
          <p class='let-enf tex_ali-cen'>"._doc::let($_cub->tit)."</p>
          ".( !empty($_cub->lec) ? "<p class='let-cur tex_ali-cen'>"._doc::let($_cub->lec)."</p>" : ""  )."
          <q>"._doc::let($_cub->afi)."</q>";
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
                    <td>Kines "._doc::let(_tex::mod($_lun->kin_cub,"-"," - "))."</td>
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
          $_ = _doc_est::lis("api.hol_lun", $est_ope, $ope );
        }
        break;
      // psi-cronos : cromaticas entonadas
      case 'psi_cro_arm':
        foreach( [ 1, 2, 3, 4 ] as $arm ){
        
          $cro_arm = _hol::_('psi_cro_arm',$arm);
  
          $_ []= "Cromática <c class='let_col-4-$arm'>$cro_arm->col</c><br>"._doc::let("$cro_arm->nom: $cro_arm->des");
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
              <img src='".SYS_NAV."_/hol/bib/ato/fic/{$cod}-1.gif' alt='Carta {$cod}-1' class='mar_der-1' style='width:20rem;'>";
              if( $opc_fin ) $htm .= "
              <img src='".SYS_NAV."_/hol/bib/ato/fic/{$cod}-2.gif' alt='Carta {$cod}-2' class='mar_izq-1' style='width:20rem;'>";
              $htm .= "
            </div>";
            $_ []= $htm;
          }
          $_ = _doc_lis::bar( $_, $ope);                 
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
                
                <p><c class='sep'>:</c> "._doc::let($rad->pla_fue)." <c>(</c>Días "._doc::let($rad->dia)."<c>)</c></p>
              </div>
            </div>";
            if( in_array($rad->ide,$pla_qua) ){
              $qua = _hol::_('rad_pla_qua',$rad->pla_qua);
              $_ []= 
              _hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
              "<p>"._doc::let($qua->ato_des)."</p>";
            }
          }          
          break;
        // afirmaciones + quantums
        case 'des': 
          foreach( _hol::_($ide) as $rad ){
            $_ []= 
            _hol::ima($ide,$rad,['class'=>"mar_der-2"])."
            <p>
              "._doc::let("$rad->nom: $rad->pla_des.")."
              <br>
              <q>"._doc::let($rad->pla_lec)."</q>
            </p>";            
            if( in_array($rad->ide,$pla_qua) ){
              $qua = _hol::_('rad_pla_qua',$rad->pla_qua);
              $_ []= 
              _hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
              "<p>"._doc::let($qua->pla_des)."</p>";
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
              <p class='tit anc-100 tex_ali-cen'>"._doc::let($qua->nom)."</p>";
            }
            $cub = _hol::_('rad_pla_cub', $rad->ide);
            $cha = _hol::_('rad_hum_cha', $rad->hum_cha);
            $_ []= 
            "<div>".              
              _hol::ima('rad_hum_cha',$cha,['class'=>"mar_der-2"]).
              _hol::ima('rad_pla_cub',$cub,['class'=>"mar_der-2"])."
            </div>
            <div>
              <p>"._doc::let("$rad->nom (Días $rad->dia): $cha->pos Chakra, $cha->cod o $cha->nom")."</p>
              <p>"._doc::let("Cubo del Radión - $cub->pos")."</p>
            </div>
            ";
            if( in_array($rad->ide,$pla_qua) ){              
              $_ []= 
              _hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
              "<p>"._doc::let($qua->cub_des)."</p>";
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
            "._doc::let($pla_ele->des)."
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

            <p>Semana <n>$ato->ide</n><c>:</c> Átomo Telepático del <b class='ide'>"._doc::let($ato->nom)."</b></p>";
            // tetraedros
            if( in_array($ato->ide,$pla_tet) ){
              $tet_ide++;
              $tet = _hol::_('lun_pla_tet',$tet_ide); $_ []= 
              _hol::ima('lun_pla_tet',$tet,['class'=>"mar_der-2"])."
              <p>"._doc::let($tet->des.".")."</p>";
            }
          }$_ []= 
          _doc::ima('hol/ima/lun',['class'=>"mar_der-2 tam-15"])."
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
              Semana <n>$ato->ide</n><c>,</c> <b class='col-".substr($col,0,3)."'>{$col}</b>"._doc::let(": $ato->car".".")."
              <br>
              Secuencia "._doc::let($ato->car_sec.".")."
            </p>";
          }
          break;
        // ficha semanal
        case 'hep':
          $ato = _hol::_($ide,$ope['ide']);
          $_ = "
          <p class='tit tex_ali-izq'>"._doc::let("Semana $ato->ide, Heptágono de la Mente "._tex::art_del($ato->hep))."</p>
          <div class='ite'>
            "._hol::ima($ide, $ato, ['class'=>'mar_der-2'])."
            <ul class='mar_arr-0'>
              <li>"._doc::let("Un día = $ato->val.")."</li>
              <li>"._doc::let("Valor lunar = $ato->val_lun.")."</li>
              <li>"._doc::let("Forma $ato->hep_cub en el Holograma Cúbico 7:28.")."</li>
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
              <li>"._doc::let($arm->des)."</li>
              <li>"._doc::let($arm->tel_des)."</li>
              <li>".( count(explode(', ',$ato->val_kin)) == 1 ? "Código del Kin" : "Códigos de Kines" )." "._doc::let($ato->val_kin)."</li>            
            </ul>
          </div>";
        }
        break;
      // 7 tierras de ur
      case 'lun_pla_tie':
        foreach( _hol::_($ide) as $tie ){
          $rad = _hol::_('rad',$tie->rad);
          $_[]="
          <p class='tit tex_ali-izq'>"._doc::let("$tie->nom, Tierra de UR $tie->ide")."</p>
          <div class='ite'>
            "._hol::ima('rad',$tie->rad,['class'=>"mar_der-2"])."
            <p>
              <q>$tie->des</q>
              <br>"._doc::let("Día $tie->dia, $tie->tel, Tablero del Plasma.")."
              <br>"._doc::let("Plasma Radial $rad->ide, $rad->nom: $rad->pla_fue")."
              <br>"._doc::let("( $tie->pos última Luna, $tie->pos Luna Mística )")."
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
            <p>"._doc::let("{$ini->ide}° Tierra de UR $ini->nom y {$fin->ide}° Tierra de UR $fin->nom")."</p>
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

  // Informe
  class _hol_inf {

    static function ton( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      switch( $atr ){

      }
      return $_;
    }
  }

  // Ficha
  class _hol_fic {

    static function ton( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      $_ide = explode('-',$atr);
      $dat = _hol::_('ton',$val);
      switch( $_ide[0] ){
      case 'atr':
        $htm_ini = isset($ope['htm_ini']) ? _doc::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._doc::let($ope['htm_fin']) : "";        
        $_ = "

        <p class='des'>{$htm_ini}Tono <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->nom}<c>.</c>{$htm_fin}</p>
        
        <div class='val jus-cen'>
  
          "._hol::ima("ton",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::fic_atr($dat,[ 
            'est'=>"api.hol_ton", 
            'atr'=>isset($ope['atr']) ? $ope['atr'] : ['car','pod','acc']
          ])."
  
        </div>";
        break;
      }
      return $_;
    }
    static function sel( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      $_ide = explode('-',$atr);
      $dat = _hol::_('sel',$val);
      switch( $_ide[0] ){
      case 'atr': 
        $htm_ini = isset($ope['htm_ini']) ? _doc::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._doc::let($ope['htm_fin']) : "";
        $_ = "
        <p class='des'>{$htm_ini}Sello <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}<c>.</c>{$htm_fin}</p>
  
        <div class='val jus-cen'>
  
          "._hol::ima("sel",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::fic_atr($dat,[
            'est'=>"api.hol_sel", 
            'atr'=>isset($ope['atr']) ? $ope['atr'] : ['car','acc','pod'] 
          ])."
        </div>";          
        break;
      }
      return $_;
    }    
    static function kin( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      $_ide = explode('-',$atr);
      $dat = _hol::_('kin',$val);
      switch( $_ide[0] ){
      case 'atr':
        $_ = "
        
        <p class='des'>Kin <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}<c>.</c></p>
  
        <div class='val jus-cen'>
  
          "._hol::ima("kin",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::fic_atr($dat,[
            'est'=>"api.hol_kin", 
            'atr'=>isset($ope['atr']) ? $ope['atr'] : [] 
          ])."
        </div>";

        if( isset($ope['ima']) ) $_ .= _app_dat::fic_ima('api',"hol_kin",$ope['ima'],$dat);

        break;
      // libro del kin
      case 'enc':
        $htm_ini = isset($ope['htm_ini']) ? _doc::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._doc::let($ope['htm_fin']) : "";
        
        $_ = "
        <p class='des'>
          {$htm_ini}Kin <c>#</c><n>{$dat->ide}</n><c>:</c> "._doc::let($dat->nom)."<c>.</c>{$htm_fin}
        </p>
  
        <div class='val jus-cen mar-1'>
      
          "._hol::ima("kin",$dat,['class'=>"mar_der-2"])."
  
          <q>"._doc::let("{$dat->des}")."</q>
  
        </div>";

        if( isset($ope['ima']) ) $_ .= _app_dat::fic_ima('api',"hol_kin",$ope['ima'],$dat);

        break;
      // parejas del oráculo
      case 'par':
        if( empty($_ide[1]) ){
          $_ ="
          <div class='lis'>";
          foreach( _hol::_('sel_par') as $_par ){
            
            $ide = $_par->ide;
  
            $par_ide = "par_{$ide}";
            $atr_ide = ( $ide=='des' ) ? 'ide' : $par_ide;
      
            // busco datos de parejas
            $_par = _dat::get(_hol::_('sel_par'),[ 'ver'=>[ ['ide','==',$ide] ], 'opc'=>'uni' ]);
            $kin = _hol::_('kin',$dat->$atr_ide);
            $_ .= "
      
            <p class='mar_arr-2 tex_ali-izq'>
              <b class='ide let-sub'>{$_par->nom}</b><c>:</c>
              <br><q>"._doc::let($_par->des)."</q>
              ".( !empty($_par->lec) ? "<br><q>"._doc::let($_par->lec)."</q>" : "" )."
            </p>
            
            "._hol_fic::kin('enc',$kin)
            ;
    
          } $_ .= "
          </div>";
        }else{
          switch( $_ide[1] ){
          // lecturas por parejas
          case 'lec':

            $_lis = [];
            $_des_sel = _hol::_('sel',$dat->arm_tra_dia);

            foreach( _hol::_('sel_par') as $_par ){

              if( $_par->ide == 'des' ) continue;

              $_kin = _hol::_('kin',$dat->{"par_{$_par->ide}"});
              $_sel = _hol::_('sel',$_kin->arm_tra_dia);

              $_lis []=
                _hol::ima("kin",$_kin)."

                <div>
                  <p><b class='tit'>{$_kin->nom}</b> <c>(</c> "._doc::let($_par->dia)." <c>)</c></p>
                  <p>"._doc::let("{$_sel->acc} {$_par->pod} {$_sel->car}, que {$_par->mis} {$_des_sel->car}, {$_par->acc} {$_sel->pod}.")."</p>
                </div>";
            }
            
            if( !isset($ope['lis']) ) $ope['lis']=[];

            _ele::cla($ope['lis'],'ite');
            
            $_ = _doc_lis::val($_lis,$ope);          
            break;

          }
        }
        break;
      // castillo de la nave
      case 'nav_cas': 
        $_ = "
        <div class='val jus-cen'>
            
          "._hol::ima("kin_nav_cas",$dat,['style'=>'margin: 0 2em;'])."
  
          <ul>
            <li><b class='ide'>Corte</b><c>:</c> {$dat->cor}</li>
            <li><b class='ide'>Poder</b><c>:</c> {$dat->pod}</li>
            <li><b class='ide'>Acción</b><c>:</c> {$dat->acc}</li>
          </ul>
  
        </div>";               
        break;
      // etapa evolutiva - estacion galáctica
      case 'cro_est':
        $_ = "
        <div class='val jus-cen'>

          "._hol::ima("kin_cro_est",$dat,['class'=>"mar_der-2"])."

          <ul>
            <li><p><b class='ide'>Guardían</b><c>:</c> {$dat->may}</p></li>
            <li><p><b class='ide'>Etapa Evolutiva</b><c>:</c> "._doc::let("{$dat->nom}, {$dat->des}")."</p></li>
          </ul>

        </div>";          
        break;
      // baktún - trayectoria armónica
      case 'arm_tra': 
        $_ = "
        <div class='val jus-cen'>
          
          "._hol::ima("kin_arm_tra",$dat,['class'=>"mar_der-2"])."

          <ul>
            <li><p><b class='ide'>Baktún</b><c>:</c> {$dat->may}</p></li>
            <li><p><b class='ide'>Período</b><c>:</c> {$dat->ran}</p></li>
          </ul>

        </div>";          
        break;        
      }
      return $_;
    }
  }

  // Listas
  class _hol_lis {

    static string $IDE = "_hol_lis-";
    static string $EJE = "_hol_lis.";

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
    
            foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= _doc::let($_par->$atr); }
    
            $_ton = _hol::_('ton',$_kin->nav_ond_dia);
            foreach( $_ton_atr as $atr ){ if( isset($_ton->$atr) ) $ite []= _doc::let($_ton->$atr); }
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);            
            foreach( $_sel_atr as $atr ){  if( isset($_sel->$atr) ) $ite []= _doc::let($_sel->$atr); }
    
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
        $_ = _doc_est::lis( $_, $est_ope, $ope);
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_$ide", $lis_tip, $ope ) : $_;      
    }
    
  }

  // Valores
  class _hol_val {

    static string $IDE = "_hol_val-";
    static string $EJE = "_hol_val.";

    // Fecha + ns:kin
    static function fec( array $dat, array $ope ) : string {

      $_eje =  !empty($ope['eje']) ? $ope['eje'] : self::$EJE."fec";

      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : _hol::_('kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : _hol::_('psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      

      $_ = "
      <!-- Fecha del Calendario -->
      <form class='val mar-1' ide='fec'>

        "._doc::ico('fec_val',[ 'eti'=>"label", 'for'=>"_hol_app-val-fec", 'class'=>"mar_hor-1", 
          'title'=>"Desde aquí puedes cambiar la fecha operativa del sistema..." 
        ])."
        "._doc_fec::ope('dia', $_fec, [ 'id'=>"_hol_app-val-fec", 'name'=>"fec", 
          'title'=>"Selecciona o escribe una fecha del Calendario para buscar en el Sincronario..."
        ])."
        "._doc::ico('dat_ver',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);", 
          'title'=>'Haz click para buscar esta fecha en el Calendario Gregoriano...'
        ])."

      </form>

      <!-- Fecha del Sincronario -->
      <form class='val mar-1' ide='sin'>
        
        <label>N<c>.</c>S<c>.</c></label>

        "._doc_num::ope('int', $_sin[0], [ 
          'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
        ])."
        <c>.</c>
        "._doc_lis::opc( _hol::_('ani'), [
          'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
          'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        "._doc_lis::opc( _hol::_('psi_lun'), [
          'eti'=>[ 'name'=>"lun", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
          'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        "._doc_lis::opc( _hol::_('lun'), [ 
          'eti'=>[ 'name'=>"dia", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
          'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
        ])."          
        <c class='sep'>:</c>
    
        <n name='kin'>$_kin->ide</n>

        "._hol::ima("kin",$_kin,['class'=>"mar_hor-1", 'style'=>'min-width:2.5rem; height:2.5rem;'])."

        "._doc::ico('dat_ver',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje(this);",
          'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
        ])."

      </form>";

      return $_;
    }
    static function kin( mixed $dat, array $ope = [] ) : string {
      $_ = [];
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_kin = _hol::_('kin',$dat);
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      $_kin_atr = _app::dat_atr('api',"hol_kin");
      
      $_est = [
        'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
        'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
        'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
        'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
        'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
        'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ]
      ];
      
      $_[0] = [ 'ite'=>"Nave del Tiempo", 'lis'=>[] ];
      foreach( [ 'nav_cas'=>52, 'nav_ond'=>13 ] as $atr => $cue ){ 
        $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
        $_[0]['lis'] []= 
        
        _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

        <div class='tam-cre'>
          <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
          <p>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>            
        </div>";          
      }        

      $_[1] = [ 'ite'=>"Giro Galáctico", 'lis'=>[] ];
      foreach( [ 'arm_tra'=>13, 'arm_tra_dia'=>20, 'arm_cel'=>65, 'arm_cel_dia'=>4 ] as $atr => $cue ){ 
        $est = isset($_est[$atr]['est']) ? $_est[$atr]['est'] : "kin_$atr"; 
        $_dat = _hol::_($est,$_kin->$atr); 
        $_[1]['lis'] []= 
        
        _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

        <div class='tam-cre'>
          <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          <p>"._doc::let( _app_dat::val('des',"api.hol_{$est}",$_dat) )."</p>
          <p>"._doc_num::ope('ran',$_kin->$atr,[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>          
        </div>";
      }

      $_[2] = [ 'ite'=>"Giro Espectral", 'lis'=>[] ];
      foreach( [ 'cro_est'=>65, 'cro_ele'=>5 ] as $atr => $cue ){ 
        $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
        
        $_[2]['lis'] []= 
        
          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
            <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
            <p>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>
          </div>          
        ";          
      } 

      $_[3] = [ 'ite'=>"Holon Solar", 'lis'=>[] ];
      foreach( ['sol_pla','sol_cel','sol_cir'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[3]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          </div>              
        ";          
      }

      $_[4] = [ 'ite'=>"Holon Planetario", 'lis'=>[] ];
      foreach( ['pla_cen','pla_hem','pla_mer'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[4]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          </div>              
        ";
      }

      $_[5] = [ 'ite'=>"Holon Humano", 'lis'=>[] ];
      foreach( ['hum_cen','hum_ext','hum_ded','hum_mer'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[5]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          </div>              
        ";
      }              

      $ope['lis-1'] = [ 'class'=>"ite" ];
      return _doc_lis::val($_,$ope);
    }    
    static function psi( mixed $dat, array $ope = [] ) : string {
      $_ = [];
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_psi = _hol::_('psi',$dat);
      $_lun = _hol::_('lun',$_psi->lun);
      $_rad = _hol::_('rad',$_psi->hep_dia);
      $ope['lis']['ide'] = 'psi';         

      $_[0] = [ 'ite'=>"Estación Solar", 'lis'=>[] ];
      $_est = _hol::_('psi_est',$_psi->est); 
      $_[0]['lis'] []= 
        
        _hol::ima("psi_est",$_est,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>"; 
      $_hep = _hol::_('psi_hep',$_psi->hep); 
      $_[0]['lis'] []= 
        
        _hol::ima("psi_hep",$_hep,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";

      $_[1] = [ 'ite'=>"Giro Lunar", 'lis'=>[] ];
      $_lun = _hol::_('psi_lun',$_psi->lun); 
      $_[1]['lis'] []= 
        
        _hol::ima("psi_lun",$_lun,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>";
      $_arm = _hol::_('lun_arm',_num::ran($_psi->hep,'4')); 
      $_[1]['lis'] []= 
        
        _hol::ima("lun_arm",$_arm,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";

      $_[2] = [ 'ite'=>"Héptada", 'lis'=>[] ];
      $_rad = _hol::_('rad',$_psi->hep_dia);
      $_[2]['lis'] []= 
        
        _hol::ima("rad",$_rad,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";          

      $ope['lis-1'] = [ 'class'=>"ite" ];
      return _doc_lis::val($_,$ope);
    }
  }  
  
  // Tablero
  class _hol_tab {
    
    // armo tablero
    static function _( string $est, string $atr, array $ope = [], array $ele = [] ) : array {

      $_ = [ 
        'esq'=>"hol", 
        'ide'=>$est, 
        'est'=> $est = $est.( !empty($atr) ? "_$atr" : $atr ) 
      ];

      // cargo elementos del tablero
      $ele = _app::tab('hol',$est,$ele);      
      foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }

      // opciones
      $opc = isset($ope['opc']) ? $ope['opc'] : [];

      // operador de opciones
      if( !empty($ope['pos']['bor']) ) _ele::cla($ele['pos'],"bor-1");
      
      // identificadores de datos
      if( is_object( $ide = !empty($ope['ide']) ? $ope['ide'] : 0 ) ) $ide = $ide->ide;

      // valor por posicion 
      $val = NULL;
      if( !empty($ope['val']['pos']) ){
        $val = $ope['val']['pos'];
        if( is_object($val) ){
          if( isset($val->ide) ) $val = intval($val->ide);       
        }
        else{
          $val = is_numeric($val) ? intval($val) : $val;
        }
      }
      
      $_['ide'] = $ide;
      $_['val'] = $val;
      $_['ope'] = $ope;
      $_['ele'] = $ele;
      $_['opc'] = $opc;

      return $_;     

    }// Seccion: onda encantada + castillo => fondos + pulsares + orbitales
    static function _sec( string $tip, array $ope=[], array $ele=[] ) : string {
      $_ = "";

      $_tip = explode('_',$tip);

      $_tab = _app::tab('hol',$_tip[0])->ele;

      $_pul = ['dim'=>'','mat'=>'','sim'=>''];

      // opciones por seccion
      $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : 'dis-ocu';
      $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';

      // pulsares por posicion
      if( in_array($_tip[0],['ton','cas']) && isset($ope['val']['pos']) ){
        
        $_val = $ope['val']['pos'];

        if( 
          ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) 
          || 
          ( is_object($_val) && isset($_val->ide) ) 
        ){

          $_ton = _hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
            
          foreach( $_pul as $i => $v ){
            
            if( !empty($ope['opc']["ton-pul_{$i}"]) ){
              $_pul[$i] = _hol::ima("ton_pul_[$i]", $_ton["pul_{$i}"], ['class'=>'fon'] );
            }
          }
        }
      }

      switch( $_tip[0] ){
      // onda encantada
      case 'ton':
        // fondo: imagen
        $_ .= "
        <div"._htm::atr(_ele::jun($_tab['ima'],[ 'class'=>DIS_OCU ])).">
        </div>";
        // fondos: color
        $_ .= "
        <div"._htm::atr(_ele::jun($_tab['fon'],[ 'class'=>"{$col_ocu}" ])).">
        </div>";
        // pulsares
        foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab['ond'],[ 'data-pul'=>$ide ])).">
            {$_pul[$ide]}
          </div>";
        }
        break;
      // castillo del destino
      case 'cas':
        // fondos: imagen
        for( $i = 1; $i <= 4; $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab["ond-{$i}"],[ $_tab['ima'], [ 'class'=>DIS_OCU ] ])).">
          </div>";
        }
        // fondos: color
        for( $i = 1; $i <= 4; $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab["ond-{$i}"],[ $_tab['fon'], [ 'class'=>"fon_col-4-{$i}{$col_ocu}" ] ])).">
          </div>";
        }        
        // bordes: orbitales
        for( $i=1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun(['class'=>$orb_ocu ],[ $_tab['orb'], $_tab["orb-{$i}"] ])).">
          </div>";
        }        
        // fondos: pulsares
        for( $i=1; $i<=4; $i++ ){
          foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
            <div"._htm::atr(_ele::jun($_tab['ond'],[ [ 'data-pul'=>$ide ] , $_tab["ond-{$i}"] ])).">
              {$_pul[$ide]}
            </div>";
          }
        }
        break;      
      }
      return $_;
    }// Posicion: datos + titulos + contenido[ ima, num, tex]
    static function _pos( string $est, mixed $val, array &$ope, array $ele ) : string {
      $esq = 'hol';

      // recibo objeto o identificador
      $val_ide = $val;
      if( is_object($val) ){
        $_dat = $val;
        $val_ide = intval($_dat->ide);
      }
      else{
        $_dat = _hol::_($est,$val);
      }

      // seccion
      $_val['sec_par'] = !empty($ope['sec']['par']) ? 'sec_par' : FALSE;
      // posicion
      $_val['pos_dep'] = !empty($ope['sec']['pos_dep']);// patrones
      $_val['pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
      $_val['pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen

      //////////////////////////////////////////////////////////////////////////
      // cargo datos ///////////////////////////////////////////////////////////

        $e = isset($ele['pos']) ? $ele['pos'] : [];      
        // por acumulados
        if( isset($ope['dat']) ){

          foreach( $ope['dat'] as $pos => $_ref ){

            if( isset($_ref["api-{$esq}_{$est}"]) && intval($_ref["api-{$esq}_{$est}"]) == $val_ide ){

              foreach( $_ref as $ref => $ref_dat ){

                $e["{$ref}"] = $ref_dat;
              }            
              break;
            }
          }
        }
        // por dependencias estructura
        else{
          if( !empty( $dat_est = _app::dat("api","{$esq}_{$est}",'est') ) ){

            foreach( $dat_est as $atr => $ref ){

              if( empty($e["api-{$ref}"]) ){

                $e["api-{$ref}"] = $_dat->$atr;
              }        
            }
          }// pos posicion
          elseif( empty($e["api-{$esq}_{$est}"]) ){    
            $e["api-{$esq}_{$est}"] = $_dat->ide;
          }
        }    
      //////////////////////////////////////////////////////////////////////////
      // posiciones del tablero principal //////////////////////////////////////    

        $agr = "";    
        // omito dependencias
        if( !$_val['pos_dep'] ){

          $agr = "pos";

          if( $_val['sec_par'] ){ 
            $agr .= !empty($agr) ? ' ': '';
            $agr .= $_val['sec_par']; 
            $par_ima = !empty($_val['pos_ima']) ? $_val['pos_ima'] : "{$esq}.{$est}.ide";
          }

          if( isset($ope['val']['pos']) ){

            $dat_ide = $ope['val']['pos'];

            if( is_array($dat_ide) && isset($dat_ide[$est]) ){
              $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
            }

            if( $_dat->ide == $dat_ide ){
              $agr .= !empty($agr) ? ' ': ''; 
              $agr .= '_val-pos _val-pos-bor';
            }
          }
        }
      //////////////////////////////////////////////////////////////////////////    
      // armo titulos //////////////////////////////////////////////////////////
        $pos_tit = [];
        if( isset($e['api-fec']) ){
          $pos_tit []= "Calendario: {$e['api-fec']}";
        }
        if( isset($e['api-hol_kin']) ){
          $_kin = _hol::_('kin',$e['api-hol_kin']);
          $pos_tit []= _app_dat::val('tit',"api.hol_kin",$_kin);
        }
        if( isset($e['api-hol_sel']) ){
          $pos_tit []= _app_dat::val('tit',"api.hol_sel",$e['api-hol_sel']);
        }
        if( isset($e['api-hol_ton']) ){
          $pos_tit []= _app_dat::val('tit',"api.hol_ton",$e['api-hol_ton']);
        }
        if( isset($e['api-hol_psi']) ){
          $_psi = _hol::_('psi',$e['api-hol_psi']);
          $pos_tit []= _app_dat::val('tit',"api.hol_psi",$_psi);
        }
        if( isset($e['api-hol_rad']) ){
          $pos_tit []= _app_dat::val('tit',"api.hol_rad",$e['api-hol_rad']);
        }
        $e['title'] = implode("\n\n",$pos_tit);

      //////////////////////////////////////////////////////////////////////////
      // Contenido html ////////////////////////////////////////////////////////
        // clases adicionales
        if( !empty($agr) ){ _ele::cla($e,$agr,'ini'); }

        $htm = ""; 
        // por patrones: posicion por dependencia
        if( !empty($_dat) && !!$_val['sec_par'] ){

          $ele_sec = $e;

          if( isset($ele_sec['class']) ){
            unset($ele_sec['class']);
          }
          if( isset($ele_sec['style']) ){ 
            unset($ele_sec['style']);
          }
          
          // $ope['sec']['par'] = $ope['sec']['par'] - 1;

          $htm = _hol_tab::$est('par',[
            'ide'=>$_dat,
            'sec'=>[ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal ( [pos].pos )
            'pos'=>[ 'ima'=>isset($par_ima) ? $par_ima : "api.hol_{$est}.ide" ]
          ],[
            'sec'=>$ele_sec
          ]);

        }
        // genero posicion
        elseif( !empty($_dat) ){
          // color de fondo
          if( $_val['pos_col'] ){            
            $_ide = _dat::ide($_val['pos_col']);
            if( 
              isset($e["{$_ide['esq']}-{$_ide['est']}"]) 
              && 
              !empty( $_dat = _dat::get($_ide['esq'],$_ide['est'],$e["{$_ide['esq']}-{$_ide['est']}"]) ) 
            ){
              $col = _app::dat_opc('col', ...explode('.',$_val['pos_col']));
              if( isset($col['val']) ){
                $col = $col['val'];
                $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
                _ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : _num::ran($val,$col) ) );
              }              
            }
          }
          // contenido
          foreach( ['ima','num','tex','fec'] as $tip ){

            if( !empty($ope['pos'][$tip]) ){                        
              $ide = _dat::ide($ope['pos'][$tip]);

              $htm .= _app_dat::ver($tip, $ope['pos'][$tip], $e["{$ide['esq']}-{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
            }
          }
        }
        // agrego posicion automatica-incremental
        if( !$_val['pos_dep'] ){

          if( !isset($e['pos']) ){
            if( empty($ope['_tab_pos']) ){
              $ope['_tab_pos'] = 0;
            }
            $ope['_tab_pos']++;
            $e['pos'] = $ope['_tab_pos'];
          }
        }
      //////////////////////////////////////////////////////////////////////////
      // devuelvo posicion /////////////////////////////////////////////////////
      
      $pos_eti = isset($ope['eti']) ? $ope['eti'] : 'div';

      return "
      <{$pos_eti}"._htm::atr($e).">
        {$htm}
      </{$pos_eti}>";
    }

    // por estructuras:
    static function rad( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('rad',$atr,$ope,$ele) );

      return $_;
    }
    static function ton( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('ton',$atr,$ope,$ele) );
      $_tab = _app::tab('hol','ton')->ele;
      $_ .= "
      <div"._htm::atr(_ele::jun($ele['sec'],$_tab['sec'])).">
        <div fon='ima'></div>
        "._hol_tab::_sec('ton',$ope)
        ;
        $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

        foreach( _hol::_('ton') as $_ton ){
          $i = "pos-{$_ton->ide}";
          $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
          $_ .= _hol_tab::_pos('ton',$_ton,$ope,$ele);
        } $_ .= "
      </div>";
      return $_;
    }
    static function sel( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('sel',$atr,$ope) );

      switch( $atr ){
      // codigo
      case 'cod': 
        $_ = "
        <div"._htm::atr($ele['sec']).">";
          foreach( _hol::_('sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
            $_ .= "
            <div class='sec{$agr}'>
              <ul class='val jus-cen'>
                "._hol::ima("sel",$_sel,['eti'=>"li"])."
                "._hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='mar-0 ali_pro-cen'>
                {$_sel->arm}
                <br>{$_sel->acc}
                <br>{$_sel->pod}
              </p>
            </div>";
          } $_ .= "
        </div>";        
        break;
      // colocacion cromática
      case 'cro':

        $e = $ele['sec'];

        _ele::cla($e,"sel");         
        $_ = "
        <div"._htm::atr($e).">
          <div pos='0'></div>";
          foreach( _hol::_('sel_cro_fam') as $_dep ){ 
            $_ .= _hol::ima("sel_cro_fam",$_dep,['fam'=>$_dep->ide]);
          } 
          foreach( _hol::_('sel_cro_ele') as $_dep ){ 
            $_ .= _hol::ima("sel_cro_ele",$_dep,['ele'=>$_dep->ide]);
          }
          for( $i=0; $i<=19; $i++ ){ 
            $_sel = _hol::_('sel', ( $i == 0 ) ? 20 : $i);
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
            $e = $ele['pos'];
            _ele::cla($e,"pos{$agr}");             
            $e['pos'] = $_sel->ide;
            $e['hol-sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['_eje']) ? $ele['pos']['_eje'] : "" ])."
            </div>";
          } $_ .= "
        </div>";        
        break;
      // colocacion armónica
      case 'arm':
        $_ .= "
        <div"._htm::atr($ele['sec']).">
          <div pos='0'></div>
          ";
          foreach( _hol::_('sel_arm_cel') as $_dep ){ 
            $_ .= _hol::ima("sel_arm_cel",$_dep,['cel'=>$_dep->ide]);
          } 
          foreach( _hol::_('sel_arm_raz') as $_dep ){ 
            $_ .= _hol::ima("sel_arm_raz",$_dep,['raz'=>$_dep->ide]);
          }
          foreach( _hol::_('sel') as $_sel ){
            $agr = ( !empty($ide) && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
            $e = $ele['pos'];
            _ele::cla($e,"pos{$agr}"); 
            $e['pos'] = $_sel->ide;
            $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['onclick']) ? $ele['pos']['onclick'] : NULL ])."
            </div>";
          }
          $_ .= "
        </div>";        
        break;
      // tablero del oráculo
      case 'arm_tra': 
        $_ .= "
        <div"._htm::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){             
            $ope['ide'] = $i;
            $_ .= _hol_tab::kin('arm_cel',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      // célula del tiempo para el oráculo
      case 'arm_cel':
        
        $_arm = _hol::_('sel_arm_cel',$ide);
        
        $e = isset($ope['cel']) ? $ope['cel'] : [];

        _ele::cla($e,"sel");
        $e['title'] = _app_dat::val('tit',"api.hol_{$est}",$_arm); $_ = "
        <div"._htm::atr($e).">
          <div pos='00'>
            "._hol::ima("sel_arm_cel", $_arm, ['htm'=>$_arm->ide,'class'=>'ima'] )."
          </div>
          ";
          foreach( _hol::_('sel_arm_raz') as $_raz ){
            $_.= _hol_tab::_pos('sel',$sel,$ope,$ele);
            $sel++;
          } $_ .= "
        </div>";        
        break;
      }
      return $_;
    }  
    static function lun( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('lun',$atr,$ope,$ele) );

      return $_;
    }
    static function cas( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('cas',$atr,$ope,$ele) );

      $_tab = _app::tab('hol','cas')->ele;

      $_ = "
      <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
        <div fon='ima'></div>
        <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></div>
        "._hol_tab::_sec('cas',$ope)
        ;
        $ele_pos = $ele['pos'];
        foreach( _hol::_('cas') as $_cas ){
          $i = "pos-{$_cas->ide}";
          $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
          $_ .= _hol_tab::_pos('cas',$_cas,$ope,$ele);
        } $_ .= "
      </div>";

      return $_;
    }
    static function kin( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('kin',$atr,$ope,$ele) );
      $_ = "";
      
      switch( $atr ){
      // tzolkin
      case 'tzo':
        $ton_htm = isset($ope['sec']['kin-ton']);
        $ton_val = !empty($ope['sec']['kin-ton']);
        $ele_ton=[ 'sec'=>'ton', 'class'=> $ton_val ? "" : "dis-ocu" ];
        $sel_htm = isset($ope['sec']['kin-sel']);
        $sel_val = !empty($ope['sec']['kin-sel']);
        $ele_sel = [ 'sec'=>'sel', 'class' => $sel_val ? "" : "dis-ocu" ];
        $kin_pag = !empty($ope['pag']['kin']);
        // ajusto grilla
        if( $ton_val ) _ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");
        $_ = "
        <div"._htm::atr($ele['sec']).">";
          // 1° columna
          if( $ton_htm && $sel_htm ){ $_ .= "
            <div"._htm::atr([ 'sec'=>'ini', 'class' => $ton_val && $sel_val ? "" : "dis-ocu" ]).">
            </div>";
          }
          if( $sel_htm ){
            // filas por sellos            
            foreach( _hol::_('sel') as $_sel ){
              $ele_sel['data-ide'] = $_sel->ide; 
              $_ .= "
              <div"._htm::atr($ele_sel).">
                "._hol::ima("sel",$_sel)."
              </div>"; 
            }
          }
          // 260 kines por 13 columnas
          $kin_arm = 0;
          $ele_pos = $ele['pos'];
          foreach( _hol::_('kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){
              $ele_ton['data-ide'] = $_kin->arm_tra; $_ .= "
              <div"._htm::atr($ele_ton).">
                "._hol::ima("ton",$_kin->arm_tra)."
              </div>";
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            if( isset($ele["pos-{$_kin->ide}"]) ) $ele['pos'] = _ele::jun( $ele["pos-{$_kin->ide}"], $ele['pos'] );
            // marco portales PAG
            if( $kin_pag && !empty($_kin->pag) ){
              if( $kin_pag ) _ele::cla($ele['pos'],"_hol-pag_kin");
            }
            $_ .= _hol_tab::_pos('kin',$_kin,$ope,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </div>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par': 
        $_tab = _app::tab('hol','cro')->ele;
        
        if( empty($ide) ) $ide = 1;

        $_kin = is_object($ide) ? $ide : _hol::_('kin',$ide); 
  
        $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
          foreach( _hol::_('sel_par') as $_par  ){               
            
            $par_ide = $_par->ide;

            $par_kin = ( $par_ide == 'des' ) ? $_kin : _hol::_('kin',$_kin->{"par_{$par_ide}"});
            // combino elementos :
            $ele['pos'] = _ele::jun($_tab["pos-{$_par->pos}"], [ 
              $ele_pos, isset($ele["pos-{$par_ide}"]) ? $ele["pos-{$par_ide}"] : []
            ]);
            _ele::cla($ele['pos'],"pos-{$par_ide}",'ini');
            $_ .= _hol_tab::_pos('kin',$par_kin,$ope,$ele);
          }$_ .="
        </div>";        
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_tab = _app::tab('hol','cas')->ele;
      
        $_fam = _hol::_('sel_cro_fam',$ide);
  
        $_fam_kin = [ 1 => 1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ]; 
        
        $_="
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
          </div>"
          ._hol_tab::_sec('cas',$ope)
          ;
          $kin = intval($_fam['kin']);
          
          $ele_pos = $ele['pos'];

          foreach( _hol::_('cas') as $_cas ){
            $_kin = _hol::_('kin',$kin);
            $i = "pos-{$_cas->ide}";
            $ele['pos'] = _ele::jun($_tab[$i], [ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin = $kin + 105; 
            if( $kin > 260 ){ $kin = $kin - 260; }
          } $_ .= "
        </div>";          
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro': 
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }

        $_tab = _app::tab('hol','cas')->ele;

        if( !in_array('fic_cas',$opc) ) $opc []= 'fic_cas'; 

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
            "._doc::ima('hol/tab/gal')."
          </div>"
          ._hol_tab::_sec('cas',$ope)
          ;
          $ele_ele = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['ele']) : $ele['ele'];
          foreach( _hol::_('kin_cro_ele') as $_cro ){                
            $i = "pos-{$_cro->ide}";
            $ele['ele'] = _ele::jun($_tab[$i],[ $ele_ele, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_cro->ide;
            $_ .= _hol_tab::kin('cro_ele',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      case 'cro_est':
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }

        $_tab = _app::tab('hol','ton')->ele;

        if( !in_array('fic_cas',$opc) ) $opc []= 'fic_ond';
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['est'])).">
          "._hol_tab::_sec('ton',$ope)
          ;
          $_est = _hol::_('kin_cro_est',$ide); 
          $cas = $_est->cas;
          $ele_ele = isset($_tab['ele']) ? _ele::jun($_tab['ele'],$ele['ele']) : $ele['ele'];
          foreach( _hol::_('ton') as $_ton ){                
            $i = "pos-{$_ton->ide}";
            $ele['ele'] = _ele::jun($_tab[$i], [ $ele_ele, isset($ele[$i]) ? $ele[$i] : [] ]);                
            $ope['ide'] = $cas;
            $_ .= _hol_tab::kin('cro_ele',$ope,$ele);
            $cas++; if( $cas > 52 ){ $cas = 1; }
          } $_ .= "
        </div>";        
        break;
      case 'cro_ele':
        foreach(['ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }

        $_tab = _app::tab('hol','cro_cir')->ele;
        $_ele = _hol::_('kin_cro_ele',$ide);

        // cuenta de inicio
        $kin_ini = 185;
        $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";

        // del castillo | onda : rotaciones
        if( isset($ele['ele']['pos']) ){

          _ele::css($ele['ele'],"transform: rotate(".(in_array('fic_cas',$opc) ? $ele['rot-cas'][$ide-1] : $ele['rot-ton'][$ide-1])."deg)");
        }
        $_ .= "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ele'])).">
          <div"._htm::atr(_ele::jun($_tab['pos-0'],isset($ele['pos-0']) ? $ele['pos-0'] : [])).">
            "._doc::ima("hol/ima/arm/"._num::ran($_ele->ide,4), [ 'htm'=>$_ele->ide, 'class'=>"alt-100 anc-100" ])."
          </div>";

          $kin = $kin_ini + ( ( $ide - 1 ) * 5 ) + 1;

          if( $kin > 260 ) $kin -= 260;

          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

          foreach( _hol::_('sel_cro_fam') as $cro_fam ){
            $i = "pos-{$cro_fam->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;// por verdad eléctrica
            if( $kin > 260 ) $kin = 1;
          }$_ .= "
        </div>";        
        break;
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','ton')->ele;
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          "._hol_tab::_sec('ton',$ope)
          ;
          $ele_tra = isset($_tab['pos']) ? _ele::jun($ele['tra'],$_tab['pos']) : $ele['tra'];
          foreach( _hol::_('kin_arm_tra') as $_tra ){ 
            $i = "pos-{$_tra->ide}";
            $ele['tra'] = _ele::jun($_tab[$i],[ $ele_tra, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_tra->ide;
            $_ .= _hol_tab::kin('arm_tra',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      case 'arm_tra':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','cro')->ele;
        $_tra = _hol::_('kin',$ide); $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['tra'])).">";
          $cel_pos = 0;
          $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
          $cel_fin = $cel_ini + 5; 
          $ele_cel = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['cel']) : $ele['cel'];
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $cel_pos++;                
            $i = "pos-{$cel_pos}";
            $ele['cel'] = _ele::jun($_tab[$i],[ $ele_cel, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $cel;
            $_ .= _hol_tab::kin('arm_cel',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      case 'arm_cel': 
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','arm')->ele;
        $_arm = _hol::_($est,$ide);
        
        $ele_cel = $ele['cel'];
        $ele_cel['title'] = _app_dat::val('tit',"api.hol_{$est}",$_arm);
        _ele::cla($ele_cel,"fon_col-5-$_arm->cel fon-0");
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele_cel)).">";
          $_ .= "
          <div"._htm::atr(_ele::jun($_tab['pos-0'], isset($ele['pos-0']) ? $ele['pos-0'] : [] )).">
            "._hol::ima("sel_arm_cel", $_arm->cel, [ 'htm'=>$_arm->ide, 'class'=>'ima' ] )."
          </div>";

          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          
          $ele_pos = $ele['pos'];
          for( $arm = 1; $arm <= 4; $arm++ ){
            $i = "pos-{$arm}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','cro')->ele;
        
        $ele_cas = $ele['cas'];

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
          foreach( _hol::_('kin_nav_cas') as $cas => $_cas ){
            $i = "pos-{$_cas->ide}";
            $ele['cas'] = _ele::jun($_tab[$i],[ $ele_cas, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_cas->ide;
            $_ .= _hol_tab::kin('nav_cas',$ope,$ele);
          } $_ .= "
        </div>";

        break;
      case 'nav_cas':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','cas')->ele;
        $_cas = _hol::_($est,$ide);

        _ele::cla( $ele['cas'], "fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ) );

        $ele['cas']['title'] = _app_dat::val('tit',"api.hol_{$est}",$_cas);            
        $ond_ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ond_ini + 4;
        for( $ond = $ond_ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = _hol::_('kin_nav_ond',$ond);
          $ele['cas']['title'].="\n".$_ond->enc_des;
        }
        $_ = "
        <div"._htm::atr( _ele::jun($_tab['sec'],$ele['cas']) ).">
          <div"._htm::atr(_ele::jun($_tab['pos-00'],[ 
            [ 'class'=>"bor_col-5-{$ide} fon_col-5-{$ide}" ], isset($ele['pos-00']) ? $ele['pos-00'] : [] 
          ])).">
            {$ide}
          </div>
          "._hol_tab::_sec('cas',$ope);

          $kin = ( ( $ide - 1 ) * 52 ) + 1;

          $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);

          foreach( _hol::_('cas') as $_cas ){ 
            $i = "pos-{$_cas->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;
      case 'nav_ond':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','ton')->ele;
        $_ond = _hol::_($est,$ide); 
        $_cas = _hol::_('kin_nav_cas',$_ond->nav_cas);

        $ele['ond']['title'] = _app_dat::val('tit',"api.hol_kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ond'])).">
          "._hol_tab::_sec('ton',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          
          $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);

          foreach( _hol::_('ton') as $_ton ){
            $i = "pos-{$_ton->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;      
      }
      return $_;
    }
    static function psi( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_('psi',$atr,$ope,$ele) );
      $_ = "";

      switch( $atr ){
      // giro solar de 13 lunas con psi-cronos
      case 'ban': 
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }        

        $_tab = _app::tab('hol','ton')->ele;
  
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div sec='uni-sol' style='width:100px; height:100px; grid-row:1; grid-column:2; justify-self:end; align-self:end;'>
            "._doc::ima('hol/tab/sol')."
          </div>
          <div sec='uni-lun' style='width:60px; height:60px; grid-row:3; grid-column:3; align-self:center; justify-self:center;'>
            "._doc::ima('hol/tab/pla')."
          </div>
          "._hol_tab::_sec('ton',$ope)
          ;

          if( !in_array('cab_nom',$opc) ) $opc []= 'cab_nom';
          $ele_lun = _ele::jun($_tab['pos'],$ele['lun']);
          foreach( _hol::_('psi_lun') as $_lun ){
            $i = "pos-{$_lun->ide}";
            $ele['lun'] = _ele::jun($_tab[$i],[$ele_lun, isset($ele["lun-{$i}"]) ? $ele["lun-{$i}"] : [] ]);
            $ope['ide'] = $_lun->ide;
            $_ .= _hol_tab::psi('lun',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      // anillos solares por ciclo de sirio
      case 'ani': 

        $_tab = _app::tab('hol','cas_cir')->ele;

        $kin = 34;
        $ope['sec']['orb_cir'] = '1';

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
  
          "._hol_tab::_sec('cas_cir',$ope)
          ;
          foreach( _hol::_('cas') as $_cas ){
            $_kin = _hol::_('kin',$kin);
            $agr = '';
            $e = $ele['pos'];
            _ele::cla($e,"pos{$agr}"); 
            $e['pos'] = $_cas->ide;         
            $e['kin'] = $_kin->ide;
            $e['ton'] = $_cas['ton'];
            $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("kin",$_kin,[ 'onclick'=>isset($e['_eje'])?$e['_eje']:NULL ])."
            </div>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </div>";        
        break;
      // estaciones de 91 días
      case 'est':
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }

        $_tab = _app::tab('hol','cas')->ele;
        $_ = "
        <div"._htm::atr( _ele::jun($_tab['sec'],$ele['sec']) ).">
          "._hol_tab::_sec('cas',$ope)
          ; 
          $ele_hep = $ele['hep'];
          foreach( _hol::_('cas') as $_cas ){
            $i = "hep-{$_cas->ide}";
            $ele['hep'] = _ele::jun($_tab["pos-{$_cas->ide}"],[ $ele_hep, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_cas->ide;
            $_ .= _hol_tab::psi('hep',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      // luna de 28 días
      case 'lun':
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) ){
          if( is_array($val) && isset($val['psi']) ){
            $ide = _hol::_('psi',$val['psi'])->lun;
          }
        }
        $_lun = _hol::_($est,$ide);
        $_ton = _hol::_('ton',$ide);
        $_tab = _app::tab('hol','lun')->ele;
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);
        $_ = "
        <table"._htm::atr(_ele::jun($_tab['sec'],$ele['lun'])).">";
          if( !$cab_ocu ){
            $_ .= "
            <thead sec = 'cab'>
              <tr data-sec='ton'>
                <th colspan='8'>
                <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    "._hol::ima("{$est}",$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-16 mar-1" )])."

                    ".( $cab_nom ? "
                      <p><n>{$ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."</p>                      
                    " : "
                    <div>
                      <p class='tit let-4'>
                        <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                      </p>
                      <p class='let-3 let_col-tex mar-1'>
                        "._doc::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                        <br>"._doc::let($_lun->ond_man)."
                      </p>                   
                      <p class='let-3 let_col-tex mar-1'>
                        Totem<c>:</c> $_lun->tot
                        <br>Propiedades<c>:</c> "._doc::let($_lun->tot_pro)."
                      </p> 
                    </div>                      
                    " )."
                  </div>
                </th>
              </tr>";
              // agrego plasmas
              if( !$cab_nom ){ $_ .= "
                <tr sec='rad'>
                  <th>
                    <p class='tex_ali-der'>Plasma</p>
                    <p class='tex_ali-cen'><c>/</c></p>                    
                    <p class='tex_ali-izq'>Héptada</p>
                  </th>";
                  foreach( _hol::_('rad') as $_rad ){ $_ .= "
                    <th>"._hol::ima("rad",$_rad,[])."</th>";
                  }$_ .= "                  
                </tr>";
              }$_ .="
            </thead>";
          }  
          $dia = 1;    
          $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
          $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
          $ope['eti']='td'; $_ .= "
          <tbody>";
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= "
            <tr>
              <td"._htm::atr(_ele::jun(
                  [ 'sec'=>"hep", 'data-arm'=>$arm, 'data-hep'=>$hep, 'class'=>"fon_col-4-{$arm}", 'style'=>'min-width:1rem;' ], 
                  isset($ele[$i = "hep-{$arm}"]) ? $ele[$i] : []
                )).">";
                if( $cab_ocu || $cab_nom ){
                  $_ .= "<n>$hep</n>";
                }else{
                  $_ .= _hol::ima("psi_hep",$hep,[]);
                }$_ .= "
              </td>";

              for( $rad=1; $rad<=7; $rad++ ){
                $_dia = _hol::_('lun',$dia);
                $i = "pos-{$_dia->ide}";
                $ele['pos'] = _ele::jun($ele_pos, isset($ele[$i]) ? $ele[$i] : []);
                $_ .= _hol_tab::_pos('psi',$psi,$ope,$ele);
                $dia++;
                $psi++;
              }
              $hep++; $_ .= "
            </tr>";
          }$_ .= "
          </tbody>
  
        </table>";        
        break;
      // heptada de 7 días
      case 'hep': 
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }

        if( empty($ide) ){
          if( is_array($val) && isset($val['psi']) ){
            $ide = _hol::_('psi',$val['psi'])->hep;
          }
        }        
        $_tab = _app::tab('hol','rad')->ele;
        $_hep = _hol::_('psi_hep',$ide);        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['hep'])).">";

          $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;

          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'] ;

          foreach( _hol::_('rad') as $_rad ){
            $_psi = _hol::_('psi',$psi);            
            $i = "pos-{$_rad->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele["rad_{$i}"]) ? $ele["rad_{$i}"] : [] ]);
            $_ .= _hol_tab::_pos('psi',$psi,$ope,$ele);
            $psi++;
          } $_ .= "
        </div>";        
        break;
      // banco-psi de 8 tzolkin con psi-cronos
      case 'tzo': 
        $_ = "
        <div"._htm::atr($ele['sec']).">";

          $ele_tzo = $ele['sec'];
          for( $i=1 ; $i<=8 ; $i++ ){
            $ele['sec'] = $ele_tzo;
            $ele['sec']['pos'] = $i;
            if( isset($ele["tzo-$i"]) ) $ele['sec'] = _ele::jun($ele['sec'],$ele["tzo-$i"]);
            $_ .= _hol_tab::kin('tzo',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      }
      return $_;
    }
    static function ani( string $atr, array $ope = [], array $ele = [] ) : string {
      $esq = "hol";
      $_ = "";
      if( isset($val['kin']) ){
        $_kin = _hol::_('kin',$val['kin']);
        $_sel = _hol::_('sel',$_kin->arm_tra_dia);
        $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      }else{
        if( isset($val['sel']) ) $_sel = _hol::_('sel',$val['sel']);
        if( isset($val['ton']) ) $_ton = _hol::_('ton',$val['ton']);
      }      
      switch( $atr ){
      // telektonon : tablero completo
      case 'tel': 
        $ocu = []; 
        foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
  
        $_ = "
        <div"._htm::atr($ele['sec']).">"
          ._doc::ima("hol/gal",['eti'=>"div",'fic'=>'gal','title'=>'Fin de la Exhalación Solar. Comienzo de la Inhalación Galáctica.'])
          ._doc::ima("hol/sol",['eti'=>"div",'fic'=>'sol','title'=>'Fin de la Inhalación Galáctica. Comienzo de la Exhalación Solar.']);
          foreach( _hol::_('sel_pol_flu') as $v ){ 
            $_ .= _hol::ima("sel_pol_flu",$v,[
              'eti'=>"div",'flu'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"api.hol_sel_pol_flu",$v)
            ]);
          }
          foreach( _hol::_('sel_sol_res') as $v ){ 
            $_ .= _hol::ima("sel_sol_res",$v,[
              'eti'=>"div",'res'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"api.hol_res_flu",$v)
            ]);
          }          
          foreach( _hol::_('sel_sol_pla') as $v ){
            $_ .= _hol::ima("sol_pla",$v,[
              'eti'=>"div",'pla'=>$v['ide'],'class'=>$ocu['pla'],'title'=> _app_dat::val('tit',"api.hol_sol_pla",$v)
            ]);
          }
          foreach( _hol::_('sel_cro_ele') as $v ){ $_ .= "
            <div ele='{$v['ide']}' class='{$ocu['cla']}' title='"._app_dat::val('tit',"api.hol_sel_cro_ele",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cel') as $v ){ $_ .= "
            <div cel='{$v['ide']}' class='{$ocu['cel']}' title='"._app_dat::val('tit',"api.hol_sol_cel",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cir') as $v ){ $_ .= "
            <div cir='{$v['ide']}' class='{$ocu['cir']}' title='"._app_dat::val('tit',"api.hol_sol_cir",$v)."'></div>";
          }
          foreach( _hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            _ele::cla($e,"pos{$ocu['sel']}"); 
            $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel_cod",$_sel)."
            </div>";
          }
          $_ .= " 
        </div>";        
        break;
      // telektonon : holon solar por flujo vertical
      case 'sol': 
        $ocu = []; 
        foreach( ['cel','pla','sel'] as $i ){ 
          $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
        }
        $_="
        <div"._htm::atr($ele['sec']).">
          <div fon='map'></div>
          <div fon='ato'></div>";
          // circuitos
          foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ 
            $_.="<div fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></div>";          
  
          }
          foreach( _hol::_('sel_sol_pla') as $v ){ $_ .= "
            <div pla='{$v->ide}' class='{$ocu['pla']}' title='"._app_dat::val('tit',"{$esq}.sol_pla",$v)."'></div>";
          }
          foreach( _hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            _ele::cla($e,"pos"); 
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel_cod",$_sel->cod)."
            </div>";
          }
          $_ .= " 
        </div>";        
        break;
      // encantamiento : holon planetario
      case 'pla':
        $_="
        <div"._htm::atr($ele['sec']).">
          <div fon='map'></div>";
          foreach( ['res','flu','cen','sel'] as $i ){
            $_.="<div fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></div>";
          }
          $ocu = [];
          foreach( ['fam'] as $i ){ 
            $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
          }
          foreach( _hol::_('sel_cro_fam') as $_dat ){
            $_.=_hol::ima("sel_cro_fam",$_dat,[ 'fam'=>$_dat->ide, 'class'=>$ocu['fam'] ]);
          }
          foreach( _hol::_('sel') as $_sel ){
            $e = $ele['pos'];
            _ele::cla($e,"pos");
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_sel)."
            </div>";
          }
          $_ .= "
        </div>";        
        break;
      // encantamiento : holon humano
      case 'hum':
        $_ = "
        <div"._htm::atr($ele['sec']).">
          <div fon='map'></div>";
          foreach( ['res','ext','cir','cen'] as $i ){
            $_.="<div fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></div>";
          }
          $ocu = []; 
          foreach( ['ext','cen'] as $i ){ 
            $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
          }
          foreach( _hol::_('sel_cro_ele') as $_dat ){ 
            $_ .= _hol::ima("sel_cro_ele",$_dat,['ele'=>$_dat->ide,'class'=>$ocu['ext']]);
          }
          foreach( _hol::_('sel_cro_fam') as $_dat ){ 
            $_ .= _hol::ima("sel_cro_fam",$_dat,['fam'=>$_dat->ide,'class'=>$ocu['cen']]);
          }
          foreach( _hol::_('sel') as $_dat ){
            $e = $ele['pos'];
            _ele::cla($e,"pos");
            $e['pos'] = $e['data-sel'] = $_dat->ide; 
            $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_dat)."
            </div>";
          }
          $_ .= "
        </div>";        
        break;
      }
      return $_;
    }    
  }  

  // Usuario
  class _hol_usu {

    static string $IDE = "_hol_usu-";
    static string $EJE = "_hol_usu.";
      
    // ficha
    static function fic( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;      
      $_fec = _api::_('fec',$_usu->fec);
      $_kin = _hol::_('kin',$_usu->kin);
      $_psi = _hol::_('psi',$_usu->psi);
      // sumatoria : kin + psi
      $sum = $_kin->ide + $_psi->tzo;

      // nombre + fecha : kin + psi
      $_ = "
      <section class='inf ren esp-ara'>

        <div>
          <p class='let-tit let-3 mar_aba-1'>"._doc::let("$_usu->nom $_usu->ape")."</p>
          <p>"._doc::let($_fec->val." ( $_usu->eda años )")."</p>
        </div>        

        <div class='val'>
          "._hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
          <c>+</c>
          "._hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
          <c>=></c>
          "._hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
        </div>

      </section>";

      return $_;
    }

    // firma galáctica
    static function inf( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;

      return $_;
    }
    
    // tránsitos : listado
    static function cic_nav( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;
      foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
      $opc_des = !in_array('not-des',$opc);
      // descripciones
      $_kin_des = function( $kin ){
        $sel = _hol::_('sel',$kin->arm_tra_dia);
        $ton = _hol::_('ton',$kin->nav_ond_dia);
        return _doc::let($kin->nom.": ")."<q>"._doc::let("$ton->des "._tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
      };
      // listado
      $_lis = [];
      foreach( _dat::get('api.usu_cic') as $_arm ){
        $_lis_cic = [];
        foreach( _dat::get("api.usu_cic_ani",[ 'ver'=>"`usu` = '{$_usu->ide}' AND `arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
          // ciclos lunares
          $_lis_lun = [];
          foreach( _dat::get("api.usu_cic_lun",[ 'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
            $_fec = _api::_('fec',$_lun->fec);
            $_lun_ton = _hol::_('ton',$_lun->ide);
            $_kin = _hol::_('kin',$_lun->kin);
            $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>"._doc::let($_lun->sin)."</a>";
            $_lis_lun []= 
            "<div class='ite'>
              "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p>
                "._doc::let(intval($_lun_ton->ide)."° ciclo, ").$_nav._doc::let(" ( $_fec->val ).")."
                <br>"._doc::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
              </p>              
            </div>
            <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
          }
          // ciclo anual
          $_fec = _api::_('fec',$_cic->fec);
          $_cas = _hol::_('cas',$_cic->ide);
          $_cas_ton = _hol::_('ton',$_cic->ton);
          $_cas_arm = _hol::_('cas_arm',$_cic->arm);            
          $_kin = _hol::_('kin',$_cic->kin);            
          $_lis_cic []= [
            'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
              "<div class='ite'>
                "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
                <p title = '$_cas->des'>
                  "._doc::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                  <br>"._doc::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                  <br>"._doc::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
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
      _ele::cla($ope['dep'],DIS_OCU);
      $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog_dep' ];
      return _doc_lis::val($_lis,$ope);
    }
    // tránsitos : informe
    static function cic_inf( array $ele = [], ...$opc ) : string {
      $dat = _usu::cic_dat();
      $_ = "
      <section>
        "._hol_usu::cic_inf_ani( $dat, $ele, ...$opc )."
        "._hol_usu::cic_inf_lun( $dat, $ele, ...$opc )."
        "._hol_usu::cic_inf_dia( $dat, $ele, ...$opc )."
      </section>"; 
      return $_;
    }// informe anual
    static function cic_inf_ani( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;      
      $_ani = $dat['ani'];
      $_cas_arm = _hol::_('cas_arm',$dat['ani']->arm);
      $_ani_arm = _dat::get('api.usu_cic',['ver'=>"`ide` = $_ani->arm",'opc'=>"uni"]);
      $_ani_fec = _api::_('fec',$_ani->fec);      
      $_ani_ton = _hol::_('ton',$dat['ani']->ton);
      $_kin = _hol::_('kin',$_ani->kin);
      $_ = "
      <h3>Tránsito Anual</h3>

      <p>"._doc::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

      "._doc_num::ope('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

      <div class='ite mar_ver-1'>
        "._hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
        <div class='let-3'>
          <p class='let-tit'>"._doc::let($_ani_arm->nom)."</p>
          <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
          <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
          <p>"._doc_num::ope('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
        </div>
      </div>

      "._hol_fic::kin('enc',$_kin,[ 'ima'=>[] ])."

      ";
      return $_;
    }// informe lunar
    static function cic_inf_lun( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;
      $_lun = $dat['lun'];
      $_lun_fec = _api::_('fec',$_lun->fec);
      $_lun_ton = _hol::_('ton',$_lun->ide);
      $_kin = _hol::_('kin',$_lun->kin);
      $_ = "
      <h3>Tránsito Lunar</h3>

      <p>"._doc::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

      "._doc_num::ope('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

      <div class='ite mar_ver-1'>
        "._hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
        <div class='let-3'>
          <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
        </div>
      </div>


      "._hol_fic::kin('enc', $_kin, [ 'atr'=>[] ])."

      ";
      return $_;
    }// informe diario
    static function cic_inf_dia( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;
      $_dat = _hol::val( date('Y/m/d') );
      $_kin = _hol::_('kin',$dat['dia']->kin);

      $_ = "
      <h3>Tránsito Diario</h3>

      "._hol_fic::kin('enc',$_kin,[ 'ima'=>[] ])."

      ";
      return $_;
    }
  }
