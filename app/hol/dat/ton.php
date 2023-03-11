<!-- 13 Tonos Galácticos -->
<?php 
  $nv1 = "00"; $nv2 = "00"; $nv3 = "00"; $nv4 = "00"; 
  ?>
  <!-- Rayos de pulsación -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Factor Maya</cite> se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n><c>,</c> en dos series de ciclos constantes con sentido invertido<c>,</c> para describir <i>comentes de energía</i> provenientes del centro de la galaxia <b class="let-ide" title="Dador de Movimiento y Medida">Hunab Ku</b><c>.</c></p>

    <p>Luego<c>,</c> los <n>13</n> números se definen como <a href="<?=$_bib?>fac#_04-04-01-01-" target="_blank">Rayos de Pulsación</a> dotados con una <q>función radio<c>-</c>resonante en particular que pulsa e irradia simultáneamente</q><c>.</c> Esta progresión describe <q>la naturaleza formal que fundamenta la apariencia de las cosas</q> y <q>dan alguna idea de la progresión del ciclo estructural en el que se fundamenta la operación de la galaxia</q><c>.</c></p>

    <?=api_dat::lis('hol.ton',[ 'atr'=>['ide','gal'], 'opc'=>['cab_ocu'] ])?>

  </article>
  
  <!-- Principios de la creacion -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del sueño</cite> se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a> como <q>el módulo del tiempo galáctico a través del cual se mueven las <n>20</n> tribus</q><c>,</c> y se definenen los <n>13</n> números como la <q>cosmología de movimiento</q> representada por los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c></p>

    <p>De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c></p>

    <?=api_dat::lis('hol.ton',[ 'atr'=>['ide','nom','des','des_acc'] ])?>

  </article>
  
  <!-- Onda Encantada de la Aventura -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En el <cite>Encantamiento del sueño</cite> se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c></p>

    <p>Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c></p>

    <p>La Onda Encantada puede ser utilizada para <q>planear acciones durante cualquier ciclo de tiempo operando por medio de la Onda Encantada<c>:</c> <n>13</n> días<c>,</c> <n>13</n> Lunas<c>,</c> <n>13</n> años<c>,</c> etc<c>...</c></q><c>.</c></p>

    <?=api_dat::lis('hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc'], 'opc'=>['cab_ocu'] ])?>

  </article>
  
  <!-- Código Pulsar -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <!-- Simetría especular -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h2>

      <p>En el <cite>Factor Maya</cite> se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición de la columna <n>7</n> en el Módulo Armónico o Tzolkin<c>.</c> Luego<c>,</c> se describen sus relaciones recíprocas aplicando el concepto de los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a><c>.</c></p>

      <?=api_dat::lis('hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>
      
    </section>  
    
    <!-- Pulsar Dimensional -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h2>

      <p>En <cite>el Encantamiento del sueño</cite> se define el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Código Púlsar</a> como una función para <q>la integración del campo del tiempo de la Onda Encantada</q><c>.</c> Los <i>Pulsares Dimensionales</i> están codificados por el código armónico de color y muestran la unidad de las dimensiones permitiendo crear relaciones a través del tiempo<c>.</c></p>

      <p>En <cite>Las <n>13</n> Lunas en Movimiento</cite> se amplía la definición de los<a href="<?=$_bib?>lun#_02-04-" target="_blank">Pulsares</a> como la Estructura o <q>Esqueleto</q> de la <i>Onda Encantada</i><c>,</c> permitiendo proyectar y construir una onda encantada del Servicio Planetario para Sincronizar las metas y acciones del Kin Planetario en cada Anillo Solar<c>.</c></p>

      <p>En <cite>Un Tratado del Tiempo</cite> se profundiza con los <a href="<?=$_bib?>tie#_04-03-03-" target="_blank">Campos de Aplicación</a> y se describen sus funciones dentro de las <i>Articulaciones de la Onda Encantada</i><c>.</c></p>

      <?=api_dat::lis('hol.ton_dim',[ 'atr'=>['ide','nom','des_dim','des_cam','des_fun','ton'] ])?>

    </section>

    <!-- Pulsar Matiz -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h2>

      <p>En <cite>el Encantamiento del sueño</cite> se define el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Púlsar Entonado</a> como una función del <i>Quinto Matiz</i> representado por el código <i>Punto<c>-</c>Raya</i><c>.</c> Este código está asociado a la aparición de las <i>Familias Terrestres</i> en la <i>Onda Encantada</i><c>.</c></p>

      <p>En <cite>Las <n>13</n> Lunas en Movimiento</cite> se amplía la definición de los<a href="<?=$_bib?>lun#_02-04-" target="_blank">Pulsares Entonados</a> como proveedores de los poderes de <q>locomoción</q> de la <i>Onda Encantada</i><c>,</c> relacionando y unificando las distintas dimensiones que la componen<c>.</c></p>

      <p>En <cite>Un Tratado del Tiempo</cite> se profundiza en ellos como <a href="<?=$_bib?>tie#_04-03-03-" target="_blank">Estructuras de Tiempo</a> que atraviesan las Dimensiones correspondientes a los <i>Pulsares Dimensionales</i><c>,</c> los cuales definen a los distintos reinos sincrónicamente interconectados<c>.</c></p>

      <?=api_dat::lis('hol.ton_mat',[ 'atr'=>['ide','nom','des','ton'] ])?>
      
    </section>  

  </article>