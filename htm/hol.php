<?php

  // aplicacion
  class _hol_app {

    // main : app.cab.art/val
    public function __construct( array &$_ ){

      global $_app;

      $_uri = $_app->uri;

      // inicializo datos    
      $_val = _hol::val( date('Y/m/d') );
      // proceso y actualizo fecha en sesion
      if( in_array($_uri->cab,[ 'inf', 'tab' ]) ){

        $dat_ide = empty($_uri->art) ? 'kin' : explode('-',$_uri->art)[0];

        $hol_val = !empty($_uri->val) ? $_uri->val : ( !empty($_SESSION['hol-val']) ? $_SESSION['hol-val'] : '' );    

        // proceso valor e identificadores por peticion : ?ide=val
        if( !empty($_uri->val) ){

          $val = explode('=',$hol_val);

          if( isset($val[1]) ){

            $val[1] = isset($val[1]) ? $val[1] : '';

            if( $val[0]=='fec' || $val[0]=='sin' ){
              $_val = _hol::val($val[1],$val[0]);
            }
            else{        
              $_val[ $val[0] ] = $val[1];
            }
          }
          else{
            $_val[ $dat_ide ] = _hol::val( $dat_ide, $val[0] );
          }
        }

        // actualizo valor de sesion
        $_SESSION['hol-val'] = $hol_val;
      }
      // muestro fecha en el menu
      $_['nav_htm_ini'] = "
      <section class='mar_aba-2'>
        
        "._hol_app::val($_val)."

      </section>";

      // inicio : 
      if( empty($_uri->cab) ){

        $_ = $this->ini($_,$_val);
      }
      // por seccion : introduccion
      elseif( empty($_uri->art) ){
        
        $_ = $this->sec($_, $_uri, $_val);
      }
      // por articulo : bibliografía + informes + tableros
      else{
        ob_start();
        switch( $_uri->cab ){
        // tablero
        case 'tab': $_ = $this->tab($_,$_uri,$_val); break;
        // bib + dat + val
        default:    $_ = $this->{$_uri->cab}($_,$_uri,$_app->ope['nav_art'],$_val); break;
        }

        $_['sec'] = ob_get_clean();
        
        // cargo todos los datos utilizados por esquema
        if( $_uri->cab == 'tab' ) $_app->rec['dat']['hol'] = [];
      }

      // recursos del documento
      $_app->rec['htm'] []= $_uri->esq;
      $_app->rec['css'] []= $_uri->esq;

      $_app->rec['eje'] .= "
        var \$_hol_app = new _hol_app( { val : "._obj::cod($_val)." } );
      ";
      return $_;
    }
    // Inicio : explicacion del sitio
    public function ini( array $_, array $_hol ) : array {
      
      // cargo articulo
      ob_start(); ?>

      <?=_doc_val::tex('adv',"Este sitio aún se está en construcción, puede haber contenido incompleto, errores o fallas. Estamos trabajando en ello...")?>

      <article>
        <h2>Inicio</h2>

        <p>En el panel de la izquierda encontrarás el menú y las aplicaciones del sitio<c>.</c></p>

        <ul class="lis sep">
          <li><?=_doc::ico('app_nav',[ 'class'=>"mar_der-1" ])?>Desde el menú podés seleccionar la fecha del sistema y acceder a los distintos contenidos<c>,</c> donde encontrarás libros y glosarios<c>,</c> datos relacionados a las cuentas del sincronario<c>,</c> Tableros e Informes según el valor diario seleccionado<c>.</c></li>
          <li><?=_doc::ico('dat_des',[ 'class'=>"mar_der-1" ])?>En el mismo panel<c>,</c> pero en la seccion inferior<c>,</c> está el botón de ayuda que aparecerá en la páginas que lo requieran por su complejidad<c>.</c> Haz click en él para saber más sobre el contenido de este sitio<c>.</c></li>
        </ul>
        
        <p>Las demás aplicaciones irán apareciendo según la página en la que te encuentres<c>:</c> en los artículos aparecerá un índice<c>,</c> en los tableros aparecerán los operadores<c>,</c> etc<c>.</c> A medida que el sitio crezca se irán agregando nuevas funcionalidades<c>...</c></p>

    
      </article>
      <?php
      $_['sec'] = ob_get_clean();

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
      $_['app_tut'] = ob_get_clean();

      return $_;
    }
    // Menú : contenido de ayuda
    public function sec( array $_, object $_uri, array $_hol ) : array {

      switch( $_uri->cab ){
      case 'bib': 
        ?>
        <?php
        break;
      case 'inf': 
        ?>      
        <?php
        break; 
      case 'dia': 
        ?>      
        <?php
        break;
      case 'tab': 
        ?>
        <?php
        break;
      }
      return $_;
    }
    // valor : fecha + ns:kin
    static function val( array $dat ) : string {
      $_eje = "_hol_app.val";

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
    // Bibliografía : glosarios y libros
    public function bib( array $_, object $_uri, array $nav, array $_val ) : array {
      // cargo referencia
      $_bib = SYS_NAV."hol/bib/";            
      // busco archivos : html-php
      if( !empty( $rec = $_uri->rec($val = "htm/$_uri->esq/$_uri->cab/$_uri->art") ) ){
        // cargo directorio para imágenes del libro
        $_dir = $_uri->dir();

        include( $rec );
      }
      else{
        echo _doc_val::tex('err',"No existe el archivo '$val'");
      }

      return $_;
    }
    // Artículos
    public function art( array $_, object $_uri, array $nav, array $_val ) : array {
      // cargo referencia
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
              'htm'=>_doc_val::ver(['cue'=>0, 'eje'=>"_hol_app_bib.ide('ver',this)" ], [ 'class'=>"anc-100" ])
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
      }      
      return $_;      
    }
    // Cuentas
    public function dat( array $_, object $_uri, array $nav, array $_val ) : array {
      // cargo referencia
      $_bib = SYS_NAV."hol/bib/";
      switch( $_uri->art ){
      case 'rad': 
        ?>
        <!-- dìas de la semana -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">El encantamiento del Sueño</a> se divide al año en <n>13</n> lunas de <n>28</n> días cada una<c>.</c> Cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>

          <p>Posteriormente<c>,</c> en el libro de <a href="<?=$_bib?>lun#_02-07-" target="_blank">Las <n>13</n> lunas en movimiento</a>, se mencionan los plasmas para nombrar a cada uno de los días de la semana<c>-</c>heptada<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','nom','pod'] ])?>

        </article>
        <!-- sellos de la profecia -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>

          <p>En <a href="<?=$_bib?>tel#_02-06-" target="_blank">El telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

          <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario<c>:</c> familia portal y familia señal <c>(</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ])?>

          <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

          <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ])?>

        </article>
        <!-- heptágono de la mente -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>

          <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

          <p>En el <a href="<?=$_bib?>rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>            

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','nom','hep','hep_pos'] ])?>

        </article>
        <!-- autodeclaraciones diarias -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>

          <p>En <a href="<?=$_bib?>ato#_03-06-" target="_blank">Átomo del Tiempo</a> se describen las afirmaciones correspondientes a las Autodeclaraciones Diarias de Padmasambhava para cada plasma<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','pla_des'] ])?>

        </article>
        <!-- componenetes electrónicos -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>

          <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 
            'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_ene','pla_fue_pre','pla_fue_pos'] 
          ])?>

          <p>Desde este paradigma los plasmas son <q>componenees electrónicos</q> constituídos por la combinación de <n>12</n> líneas electrónicas de fuerza que convergen en <n>6</n> tipos de electricidad clasificadas según la cantidad de cargas positivas o negativas que contengan<c>.</c></p>

          <p>Los <n>12</n> tipos de líneas electrónicas<c>:</c></p>

          <?=_doc_lis::tab('api.hol_rad_pla_fue',[ 'atr'=>['ide','nom','ele_pre','ele_ope','ele_pos'] ])?>

          <p>Los <n>6</n> tipos de electricidad son<c>:</c></p>

          <?=_doc_lis::tab('api.hol_rad_pla_ele',[ 'atr'=>['ide','cod','nom','des'] ])?>            

        </article>        
        <?php
        break;
      case 'ton': 
        ?>
        <!-- rayos de pulsación -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <cite>el Factor Maya</cite><c>,</c>
            se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c>
            Y se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton',[ 'atr'=>['ide','gal'] ])?>

        </article>
        <!-- simetría especular -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>

          <p>En el <cite>Factor Maya</cite> 
            se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c>
            Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a>>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>
          
        </article>        
        <!-- principios de la creacion -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>

          <p>En <cite>el Encantamiento del sueño</cite> 
            se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c> 
            De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton',[ 'atr'=>['ide','nom','des','acc'] ])?>

        </article>
        <!-- O.E. de la Aventura -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>

          <p>En el <cite>Encantamiento del sueño</cite> 
            se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c>
            Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 
            'tit_cic'=>['ond_enc']
          ])?>
          
        </article>        
        <!-- pulsar dimensional -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>

          <p>En <cite>el Encantamiento del sueño</cite> 
            
            <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a>
            <c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton_dim')?>
          
        </article>
        <!-- pulsar matiz -->
        <article>
          <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>

          <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_ton_mat')?>
          
        </article>        
        <?php
        break;
      case 'sel': 
        ?>
        <!-- signos direccionales -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_sel_cic_dir')?>

          <!-- desarrollo del ser -->
          <h3 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ])?>

          </section>
          <!-- etapas evolutivas de la mente -->
          <h3 id="<?="_{$nav[2]['01']['02']->pos}-"?>"><?=_doc::let($nav[2]['01']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_cic_men',[ 'atr'=>['sel','nom','des','lec'] ])?>

          </section>
          <!-- familias ciclicas -->
          <h3 id="<?="_{$nav[2]['01']['03']->pos}-"?>"><?=_doc::let($nav[2]['01']['03']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ])?>

          </section>

        </article>
        <!-- colocacion cromática -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>
          
          <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
          
          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>

          <!-- familias -->
          <h3 id="<?="_{$nav[2]['02']['01']->pos}-"?>"><?=_doc::let($nav[2]['02']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ])?>

          </section>
          <!-- clanes -->
          <h3 id="<?="_{$nav[2]['02']['02']->pos}-"?>"><?=_doc::let($nav[2]['02']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ])?>

          </section>

        </article>
        <!-- colocación armónica -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>

          <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>

          <?=_doc_lis::tab('api.hol_sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>

          <!-- razas -->
          <h3 id="<?="_{$nav[2]['03']['01']->pos}-"?>"><?=_doc::let($nav[2]['03']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ])?>

          </section>
          <!-- células -->
          <h3 id="<?="_{$nav[2]['03']['02']->pos}-"?>"><?=_doc::let($nav[2]['03']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ])?>

          </section>

        </article>            
        <!-- parejas del oráculo -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>

          <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>

          <!-- analogos -->
          <h3 id="<?="_{$nav[2]['04']['01']->pos}-"?>"><?=_doc::let($nav[2]['04']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_par_ana')?>

          </section>
          <!-- antipodas -->
          <h3 id="<?="_{$nav[2]['04']['02']->pos}-"?>"><?=_doc::let($nav[2]['04']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_par_ant')?>

          </section>
          <!-- ocultos -->
          <h3 id="<?="_{$nav[2]['04']['03']->pos}-"?>"><?=_doc::let($nav[2]['04']['03']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_par_ocu')?>

          </section>

        </article>                  
        <!-- holon Solar -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>

          <p>El código 0-19</p>              

          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','sol_pla_des'], 
            'tit_cic'=>['sol_cel','sol_cir','sol_pla'] 
          ])?>

          <!-- orbitas planetarias -->
          <h3 id="<?="_{$nav[2]['05']['01']->pos}-"?>"><?=_doc::let($nav[2]['05']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_sol_pla')?>

          </section>
          <!-- células solares -->
          <h3 id="<?="_{$nav[2]['05']['02']->pos}-"?>"><?=_doc::let($nav[2]['05']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_sol_cel')?>

          </section>
          <!-- circuitos de telepatía -->
          <h3 id="<?="_{$nav[2]['05']['03']->pos}-"?>"><?=_doc::let($nav[2]['05']['03']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_sol_cir')?>

          </section>              

        </article>
        <!-- holon planetario -->
        <article>  
          <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>
          
          <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>

          <!-- centros galácticos -->
          <h3 id="<?="_{$nav[2]['06']['01']->pos}-"?>"><?=_doc::let($nav[2]['06']['01']->nom)?></h3>
          <section>

            <?=_doc_lis::tab('api.hol_sel_pla_cen')?>

          </section>
          <!-- flujos de la fuerza-g -->
          <h3 id="<?="_{$nav[2]['06']['02']->pos}-"?>"><?=_doc::let($nav[2]['06']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_pla_res')?>

          </section>              

        </article>
        <!-- holon humano -->
        <article>
          <h2 id="<?="_{$nav[1]['07']->pos}-"?>"><?=_doc::let($nav[1]['07']->nom)?></h2>

          <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 
            'tit_cic'=>['cro_ele'] 
          ])?>

          <!-- Centros Galácticos -->
          <h3 id="<?="_{$nav[2]['07']['01']->pos}-"?>"><?=_doc::let($nav[2]['07']['01']->nom)?></h3>
          <section>

            <?=_doc_lis::tab('api.hol_sel_hum_cen')?>

          </section>
          <!-- Extremidades -->
          <h3 id="<?="_{$nav[2]['07']['02']->pos}-"?>"><?=_doc::let($nav[2]['07']['02']->nom)?></h3>
          <section>

            <?=_doc_lis::tab('api.hol_sel_hum_ext')?>

          </section>                     
          <!-- dedos -->
          <h3 id="<?="_{$nav[2]['07']['03']->pos}-"?>"><?=_doc::let($nav[2]['07']['03']->nom)?></h3>
          <section>            
            
            <?=_doc_lis::tab('api.hol_sel_hum_ded')?>

          </section>
          <!-- lados -->
          <h3 id="<?="_{$nav[2]['07']['04']->pos}-"?>"><?=_doc::let($nav[2]['07']['04']->nom)?></h3>
          <section>
            
            <?=_doc_lis::tab('api.hol_sel_hum_res')?>

          </section>              

        </article>        
        <?php
        break;
      case 'lun': 
        ?>

        <article>
          <h2></h2>

          <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_lun',[ 'atr'=>['ide','arm','rad','ato_des'] ])?> 
        </article>

        <!-- 4 heptadas -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <a href="<?=$_bib?>lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>" target="_blank">el Telektonon</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>" target="_blank">el átomo del tiempo</a><c>.</c></p>

        </article>        
        <?php
        break;
      case 'cas': 
        ?>
        <!-- -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h3>

        </article>        
        <?php
        break;
      case 'kin': 
        ?>
        <!-- -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <!-- -->
          <h4 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h4>
          <section>

          </section>            

        </article>        
        <?php
        break;
      case 'psi': 
        ?>
        <!-- -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <!-- -->
          <h4 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h4>
          <section>

          </section>            

        </article>           
        <?php
        break;
      }
      // cargo tutorial    
      ob_start(); ?>
        <!-- Introduccion -->
        <section>

          <h3>Introducción</h3>

          <p>Los sistemas del Sincronario están basados en códigos y cuentas<c>.</c> Los <n>13</n> tonos galácticos crean el módulo de sincronización para las <n>13</n> lunas del giro solar y las <n>13</n> trayectorias armónicas del giro galáctico<c>.</c> Cada kin está compuesto por uno de los <n>13</n> tonos galácticos<c>,</c> y uno de los <n>20</n> sellos solares<c>.</c> Cada día del año se encuentra en una de las <n>13</n> lunas y se asocia a uno de los <n>7</n> plasma radiales<c>.</c> Un castillo está compuesto por <n>52</n> posiciones que se dividen en <n>4</n> ondas encantadas de <n>13</n> unidades<c>.</c> Con el castillo se codifican las <n>4</n> estaciones espectrales del giro galáctico<c>,</c> las <n>4</n> estaciones cíclicas del giro solar<c>,</c> los <n>52</n> anillos solares del ciclo Nuevo Siario y los <n>52</n> años del sendero del destino para el kin planetario<c>.</c> A su vez<c>,</c> la nave del tiempo tierra está compuesta de <n>5</n> castillos para abarcar los <n>260</n> kines del giro galáctico<c>.</c> Todos estos son ejemplos de las cuentas utilizadas para medir el tiempo con el concepto de Matriz Radial<c>.</c> Cada cuenta va del <n>1</n> al <n>n</n><c>,</c> siendo <n>n</n> el valor total que define la cuenta<c>.</c> De esta manera<c>:</c> los plasmas val del <n>1<c>-</c>7</n><c>,</c> los tonos del <n>1<c>-</c>13</n><c>,</c> los sellos del <n>1<c>-</c>20</n><c>,</c> las lunas del <n>1<c>-</c>28</n><c>,</c>etc<c>.</c></p>

        </section>
      <?php
      $_['app_tut'] = ob_get_clean();      

      return $_;
    }
    // Diario : valores por posicion
    public function dia( array $_, object $_uri, array $nav, array $_val ) : array {
      $_bib = SYS_NAV."hol/bib/";
      // galáctico
      $_kin = _hol::_('kin', $_val['kin']);
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      // solar
      $_psi = _hol::_('psi', $_val['psi']);

      switch( $_uri->art ){      
      case 'kin': 
        ?>
        <!-- ficha del encantamiento -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <?= _hol_fic::kin('enc',$_kin) ?>

          <br>

          <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

          <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

        </article>
        <!-- parejas -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>

          <?= _hol_tab::kin('par',[ 
            'ide'=>$_kin->ide,
            'sec'=>[ 'par'=>0 ],
            'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
          ],[
            'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
            'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
          ])?>
          <!-- Descripciones -->
          <h3 id="<?="_{$nav[2]['02']['01']->pos}-"?>"><?=_doc::let($nav[2]['02']['01']->nom)?></h3>
          <section>

            <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            

            <?= _hol_fic::kin('par',$_kin) ?>

          </section>
          <!-- Lecturas diarias -->
          <h3 id="<?="_{$nav[2]['02']['02']->pos}-"?>"><?=_doc::let($nav[2]['02']['02']->nom)?></h3>
          <section>

            <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

            <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>

            <?= _hol_lis::kin('par',[ 'dat'=>$_kin,  'atr'=>"pro",  'ele'=>[ 'lis'=>['class'=>"anc-100"] ] ]) ?>

            <br>
            
            <p>En <a href="<?=$_bib?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

            <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>

            <?= _hol_fic::kin('par_lec',$_kin) ?>

          </section>  
          <!-- Posiciones en el tzolkin -->  
          <h3 id="<?="_{$nav[2]['02']['03']->pos}-"?>"><?=_doc::let($nav[2]['02']['03']->nom)?></h3>
          <section>

            <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

            <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>

            <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"cic", 'ele'=>[ 'lis'=>['class'=>"anc-100"] ] ]) ?>

          </section>  
          <!-- Sincronometría del holon -->
          <h3 id="<?="_{$nav[2]['02']['04']->pos}-"?>"><?=_doc::let($nav[2]['02']['04']->nom)?></h3>
          <section>

            <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

            <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

            <br>

            <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"gru", 'ele'=>[ 'lis'=>['class'=>"anc-100"] ] ]) ?>

          </section>
          
        </article>
        <!-- Nave del tiempo -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>
          <!-- x52 : Castillo Fractal -->  
          <h3 id="<?="_{$nav[2]['03']['01']->pos}-"?>"><?=_doc::let($nav[2]['03']['01']->nom)?></h3>
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
          <h3 id="<?="_{$nav[2]['03']['02']->pos}-"?>"><?=_doc::let($nav[2]['03']['02']->nom)?></h3>
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
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>
          <!-- x20 : Trayectoria Armónica -->  
          <h3 id="<?="_{$nav[2]['04']['01']->pos}-"?>"><?=_doc::let($nav[2]['04']['01']->nom)?></h3>
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
          <h3 id="<?="_{$nav[2]['04']['02']->pos}-"?>"><?=_doc::let($nav[2]['04']['02']->nom)?></h3>
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
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>
          <!-- x65 : Estación Galáctica -->
          <h3 id="<?="_{$nav[2]['05']['01']->pos}-"?>"><?=_doc::let($nav[2]['05']['01']->nom)?></h3>
          <section>
            <?php
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            ?>

            <nav>
              <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
            </nav>    

          </section>
          <!-- x5 : Elemento Cromático -->  
          <h3 id="<?="_{$nav[2]['05']['02']->pos}-"?>"><?=_doc::let($nav[2]['05']['02']->nom)?></h3>
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
          <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>
        </article>        
        <?php
        break;
      case 'ton': 
        ?>
        <!-- Ficha -->
        <article>

          <?= _hol_fic::ton([],$_ton) ?>

          <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    

        </article>
        <!-- Aventura de la Onda Encantada -->

        <!-- Simetría Especular -->

        <!-- Pulsares Dimensionales -->

        <!-- Pulsares Matiz Entonado -->         
        <?php
        break;
      case 'sel': 
        ?>
        <!-- Ficha -->
        <article>

          <?= _hol_fic::sel([],$_sel) ?>

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
      return $_;
    }
    // Tableros : posicionamiento y seleccion
    public function tab( array $_, object $_uri, array $_val ) : array {
          
      $art_tab = explode('-',$_uri->art);
      if( isset($art_tab[1]) && method_exists("_hol_tab",$ide = $art_tab[0]) ){
        
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

        // operadores del tablero
        $_ope = _obj::nom(_app_tab::$OPE,'ver',['val','opc','cue']);
        foreach( $_ope as $ope_ide => $ope_tab ){

          if( !empty( $htm = _app_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele ) ) ){
            $_['nav'][$ope_ide] = [ 
              'ico' => $ope_tab['ico'], 'nom' => $ope_tab['nom'], 
              'nav' => [ 'style'=>"" ],
              'htm' => $htm
            ];
          }
        }

        // valores diario
        $dia_ele = [ 
          'ope' => [ 'class'=>"mar_arr-1" ], 'lis'=>[ 'style'=>"height: 75vh;" ], 'opc'=>['tog','ver'] 
        ];
        $_['nav']['ope'] = [ 'ico'=>"fec_val", 'nom'=>"Diario", 'htm'=>
          _doc_val::nav('bar',[
            'kin' => [ 'nom'=>"Orden Sincrónico", 'des'=>"", 'htm'=>_hol_app_dia::kin( $_val['kin'], $dia_ele) ],
            'psi' => [ 'nom'=>"Orden Cíclico",    'des'=>"", 'htm'=>_hol_app_dia::psi( $_val['psi'], $dia_ele) ]
            ],[            
            'sel' => "kin"
          ])
        ];        

        // operador de lista
        $_ope = _app_tab::$OPE['lis'];
        $_['win']['est'] = [ 'ico' => $_ope['ico'], 'nom' => $_ope['nom'],
          'art' => [ 'style'=>"max-width: 55rem; height: 90vh;" ],
          'htm' => _app_tab::ope('lis',"api.hol_{$ide}",$tab_ope)
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
      $_['app_tut'] = ob_get_clean();

      return $_;
    }
    // Kin Planetario : transitos + firma galàctica
    public function usu( array $_, object $_uri, array $nav, array $_val ) : array {
      global $_usu;

      return $_;
    }  
  }

  // Bibliografìa : indices, listados, cartas, tableros
  class _hol_app_bib { 

    static string $IDE = "_hol_app_bib-";
    static string $EJE = "_hol_app_bib.";

    // tierra en ascenso
    static function asc( string $ide, array $ope = [] ) : string {
      $_ = "";
      return $_;
    }
    // encantamiento
    static function enc( string $ide, array $ope = [] ) : string {
      $_ = []; $esq = "hol"; $est = str_replace('.','_',$ide); $lis_tip = "val"; $lis_pos = 0;
      $_eje = self::$EJE."enc('$ide',";
      switch( $ide ){
      // encantamiento : libro del kin        
      case 'kin':
        $_ = "
        <!-- libro del kin -->
        <form class='inf' esq='$esq' est='$est'>

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
          $nav_cas = 0; $nav_ond = 0;
          $arm_tra = 0; $arm_cel = 0;
          $cro_est = 0; $cro_ele = 0;
          $gen_enc = 0; $gen_cel = 0;
          foreach( _hol::_('kin') as $_kin ){

            // castillo
            if( $_kin->nav_cas != $nav_cas ){
              $nav_cas = $_kin->nav_cas;
              $_cas = _hol::_('kin_nav_cas',$_kin->nav_cas); 
              if( $nav_cas != 1 ){ $_ .= "
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
            if( $_kin->nav_ond != $nav_ond ){
              $nav_ond = $_kin->nav_ond;
              $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
              $_sel = _hol::_('sel',$_ond->sel); 
              $ond = _num::ran($_ond->ide,4);

              if( $nav_ond != 1 && $ond != 1 ){ $_ .= "
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
      case 'kin.arm':
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
      
      // brujula galáctica
      case 'bru': break;
      }
      return is_array($_) ? _app_dat::lis( $_, $est, $lis_tip, $ope ) : $_;
    }
    // telektonon
    static function tel( string $ide, array $ope = [] ) : string {
      $_ = "";
      switch( $ide ){
      // libros-cartas
      case 'lib': 
        $ide = isset($ope['ide']) ? $ope['ide'] : 4;
        $_dat = [
          4  => ['ide'=> 4, 'tit'=>"Libro de la Forma Cósmica" ],
          7  => ['ide'=> 7, 'tit'=>"Libro de las Siete Generaciones Perdidas" ],
          13 => ['ide'=>13, 'tit'=>"Libro del Tiempo Galáctico" ],
          28 => ['ide'=>28, 'tit'=>"Libro Telepático para la Redención de los Planetas Perdidos" ]
        ];
        $_ = [];
        for( $pos = 1; $pos <= intval($ide); $pos++ ){ $_ []= "
          <figure class='mar-0'>
            <div class='ite'>
              <img src='".SYS_NAV."_/hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta ' class='mar_der-1' style='width:24rem; height: 30rem;'>
              <img src='".SYS_NAV."_/hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta ' class='mar_izq-1' style='width:24rem; height: 30rem;'>              
            </div>
          </figure>";
        }
        $_ = _doc_lis::bar( $_, $ope);
        break;
      // tablero del telektonon
      case 'tab': break;
      }
      return $_;
    }
    // atomo del tiempo 
    static function ato( string $ide, array $ope = [] ) : string {
      $_ = "";
      switch( $ide ){
      case 'fic': break;
      }
      return $_;
    }
  }

  // diario : ciclico + sincronico
  class _hol_app_dia {

    static string $IDE = "_hol_app_dia-";
    static string $EJE = "_hol_app_dia.";

    // ciclos del orden sincronico
    static function kin( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ = [];
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_kin = _hol::_('kin',$dat);
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      $_kin_atr = _dat::atr('api',"hol_kin");
      
      $_est = [
        'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
        'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
        'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
        'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
        'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
        'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ],
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
    // ciclos del orden ciclico
    static function psi( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ = []; $esq = 'hol';
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

  // Usuario : ficha + tránsitos + firma galáctica
  class _hol_app_usu {

    static string $IDE = "_hol_app_usu-";
    static string $EJE = "_hol_app_usu.";
      
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
            $nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>"._doc::let($_lun->sin)."</a>";
            $_lis_lun []= 
            "<div class='ite'>
              "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p>
                "._doc::let(intval($_lun_ton->ide)."° ciclo, ").$nav._doc::let(" ( $_fec->val ).")."
                <br>"._doc::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
              </p>              
            </div>
            <p class='mar-1 tex_ali-cen'>"._hol_des::kin('enc',$_kin)."</p>";
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
              <p class='mar-1 tex_ali-cen'>"._hol_des::kin('enc',$_kin)."</p>"
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
        "._hol_app_usu::cic_inf_ani( $dat, $ele, ...$opc )."
        "._hol_app_usu::cic_inf_lun( $dat, $ele, ...$opc )."
        "._hol_app_usu::cic_inf_dia( $dat, $ele, ...$opc )."
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