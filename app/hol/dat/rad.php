<!-- 7 Plasmas Radiales -->
<?php 
  $nv1 = "00"; $nv2 = "00"; $nv3 = "00"; $nv4 = "00";
  ?>
  <!-- Días de la Semana -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El encantamiento del Sueño</cite> se divide al año en <a href="<?=$_bib?>enc#_02-03-09-" target="_blank"><n>13</n> lunas de <n>28</n> días</a> cada una<c>.</c> A su vez, cada luna está conformada por <n>4</n> semanas<c>-</c>héptadas de <n>7</n> días<c>.</c></p>

    <p>Posteriormente<c>,</c> en el libro de <cite>Las <n>13</n> lunas en movimiento</cite><c>,</c> se mencionan los plasmas para nombrar a cada uno de los <a href="<?=$_bib?>lun#_02-07-" target="_blank">días de la semana<c>-</c>heptada</a><c>.</c></p>

    <?=api_lis::est('hol.rad',[ 'atr'=>['ide','nom','des_pod'] ])?>

  </article>

  <!-- Sellos de la Profecia -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El telektonon</cite> se presentan los plasmas como <a href="<?=$_bib?>tel#_02-06-" target="_blank">sellos de la profecía</a><c>.</c> Estas profecías describen el desarrollo de los acontecimientos para el final de ciclo y la transición al nuevo <q>paradigma resonante</q><c>.</c></p>

    <p>Para la lectura anual se crean 3 oráculos en base a los kines que <a href="<?=$_bib?>enc#_03-14-" target="_blank">codifican los ciclos del sincronario</a><c>:</c> familia portal y familia señal<c>.</c></p>

    <?=api_lis::est('hol.rad',[ 'atr'=>['ide','tel','tel_año','tel_des','tel_ora_año','tel_ora_ani','tel_ora_gen'] ])?>

    <p>En el <cite>Proyecto Rinri</cite> se amplía el contenido de los <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">sellos de la profecía</a> del Telektonon<c>.</c></p> 
    
    <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

    <?=api_lis::est('hol.rad',[ 'atr'=>['ide','tel_año','tel_des','rin_des'] ])?>

  </article>

  <!-- Heptágono de la Mente -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En el <cite>telektonon</cite> se crea un <a href="<?=$_bib?>tel#_02-06-03-" target="_blank">arreglo en forma de heptágono</a> con los plasmas para recrear los <n>7</n> años de la profecía durante los días <n>14</n> a <n>22</n> de cada Luna<c>.</c> Este ejercicio sirve para <q> la restauración de la mente<c>,</c> la voluntad y el espíritu colectivos<c>,</c> unificados como el triunfo del Cubo de la Ley de la divina unidad<c>,</c> el advenimiento de la Noosfera planetaria</q><c>.</c></p>

    <p>En el <cite>Proyecto Rinri</cite> la <a href="<?=$_bib?>rin#_02-06-01-" target="_blank"><n>1</n><c>°</c> fase del sexto paso<c>:</c> Lanzamiento de la Liberación del Plasma Radial</a><c>,</c> se realiza un procedimiento de meditación óptica sobre los plasmas para liberar su energía hacia la biósfera en la transición Biósfera<c>-</c>Noosfera<c>.</c></p>

    <p>Luego<c>,</c> en <cite>El Átomo del Tiempo</cite> se diferencian los <n>5</n> Centros psico<c>-</c>físicos del Holon Humano de <a href="<?=$_bib?>ato#_03-07-" target="_blank">los <n>7</n> chakras</a> de la filosofía tradicional esotérica<c>.</c> En este caso se asocia cada plasma a un chakra y se arma una secuencia para meditar sobre cada uno en la construcción del <i>Cubo del Radión</i> que sigue el mismo patrón que el <i>Heptágono de la Mente</i><c>.</c></p>

    <?=api_lis::est('hol.rad',[ 'atr'=>['ide','pla_cub_pos','pla_cub','hum_cha'] ])?>

  </article>

  <!-- Autodeclaraciones Diarias -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>Átomo del Tiempo</cite> se relaciona cada plasma con una <a href="<?=$_bib?>ato#_03-06-" target="_blank">Autodeclaración de Padmasambhava</a> para <q>integrar la mente en el proceso electromagnético continuo plásmicamente autocreativo del universo</q><c>.</c> En esa misma sección se describen los relaciones simbólicas para cada término correspondiente a su afirmación<c>.</c></p>

    <?=api_lis::est('hol.rad',[ 'atr'=>['ide','pla_lec'] ])?>

  </article>

  <!-- Componenetes Electrónicos -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Átomo del tiempo</cite> se establecen los principios de los plasmas en el marco de la <a href="<?=$_bib?>ato#_03-01-" target="_blank">energía o electricidad cósmica</a><c>.</c> En este caso<c>,</c> cada plasma está compuesto por dos <i>Líneas Electrónicas de Fuerza</i> con cierta combinación de cargas<c>.</c></p>

    <?=api_lis::est('hol.rad',[ 'atr'=>['ide','des_col','pla_fue','pla_des'] ])?>

    <p>Cada <i>Línea Electrónica de Fuerza</i> está compuesta por dos tipos de <i>Electricidad Cósmica Primigenia</i></p>

    <?=api_lis::est('hol.rad_pla_fue',[ 'atr'=>['ide','nom','ele_pre','ele_pos'] ])?>

    <p>Estos tipos de <i>Electricidad Cósmica</i> están compuestos por distintas <i>Cargas Electricas</i> y constituyen el <i title="o Partón">Patrón Cúbico Primario</i> cuyo diagrama tiene la misma forma que los electrones rodeando su nucleo atómico<c>.</c></p>

    <?=api_lis::est('hol.rad_pla_ele',[ 'atr'=>['ide','nom','des'] ])?>

    <p>Por otro lado<c>,</c> los quántum surgen por la combinación de distintos <a href="<?=$_bib?>ato#_03-01-" target="_blank">Plasmas Radiales</a> cumpliendo funciones estructurales en la composición del <a href="<?=$_bib?>ato#_03-02-" target="_blank">Átomo Telepático del Tiempo</a> por la <a href="<?=$_bib?>ato#_03-03-" target="_blank">Combinación de Colores y Cargas</a><c>.</c> Son establecidos con las <a href="<?=$_bib?>ato#_03-06-" target="_blank">Autodeclaraciones de Padmasambhava</a> y habilitados con los <a href="<?=$_bib?>ato#_03-07-" target="_blank">Chakras en el Cubo del Radión</a><c>.</c></p>

    <?=api_lis::est('hol.rad_pla_qua',[ 'atr'=>['ide','nom','pla','lec_col'] ])?>

  </article>