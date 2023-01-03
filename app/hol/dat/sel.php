<!-- 20 Sellos Solares -->
<?php 
  $nv1 = "00"; $nv2 = "00"; $nv3 = "00"; $nv4 = "00"; 
  ?>
  <!-- Códigos de la Luz y de la Vida -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>la Tierra en Ascenso</cite> se presenta al <i>Tzolkin</i> como <a href="<?=$_bib?>asc#_02-01-02-" target="_blank">la Matriz Primaria</a><c>:</c> un modelo numérico compuesto por la combinación de dos series<c>,</c> <n>13 <c>x</c> 20</n><c>,</c> dando como resultado <n>260</n> permutaciones<c>.</c> Entre los Mayas este patrón de permutaciones era conocido como el <q>Calendario Sagrado</q> puesto que las <n>20</n> filas representadas en los <n>20</n> Signos simbolizaban <q>los más primarios aspectos del proceso vida<c>/</c>muerte</q><c>.</c></p>

    <p>Este instrumento se utilizaba para medir los ciclos de tiempos que duran las Manchas Solares<c>.</c> En este caso se relaciona el número <n>13</n> con <q>asociaciones calendáricas<c>/</c>cíclicas</q><c>;</c> y al <n>20</n> con los <q>Aminoácidos construídos de los <n>64</n> codones del <abbr title="">ADN</abbr></q><c>.</c></p>

    <p>Luego se analiza el <a href="<?=$_bib?>asc#_02-01-07-" target="_blank">I<c>-</c>Ching</a> como una descripción del código genético<c>.</c> Aquí se relacionan los <n>64</n> codones del ADN con los <n>64</n> Hexagramas<c>,</c> de los cuales se deriban dichos <i>aminoácidos</i><c>.</c></p>

    <p>En el <a href="<?=$_bib?>asc#_02-04-04-" target="_blank">mapa <n>32</n></a> se retoma la relación entre los <n>64</n> hexagramas<c>,</c> esta vez con el arreglo del Cuadrado Mágico de Franklin<c>,</c> y el Calendario Sagrado de los Mayas<c>,</c> en los cuales están presentes tanto los <n>20</n> aminoácidos como los <n>20</n> signos sagrados<c>.</c></p>

  </article>

  <!-- Rangos de Frecuencia para Símbolos ideográficos  -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Factor Maya</cite> los <a href="<?=$_bib?>fac#_04-04-02-" target="_blank"><n>20</n> Signos Sagrados</a> se definen como <q>posibilidades de rangos de frecuencia<c>,</c> que permiten que las estructuras armónicas lleguen a existir</q> al ser combinados con los <n>13</n> números que representan <i>Rayos de Pulsación</i><c>.</c></p>

    <p>Por otro lado<c>,</c> como símbolos ideográficos requieren un entendimiento analógico y que se los imprima o dibuje<c>,</c> ya que así funcionan como <q>Disparadores de la Memoria</q><c>.</c> Comparados con los jerogríficos que utilizan imágenes para describir palabras o sonidos<c>,</c> los ideogramas utilizan signos de naturaleza abstracta para transmitir ideas<c>,</c> sin usar palabras o frases particulares<c>.</c></p>

    <?=api_est::lis('hol.sel',[ 'atr'=>[ 'ide','nom_may' ], 'opc'=>['cab_ocu'] ])?>

    <!-- La Matriz Direccional  -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Factor Maya</cite> se relacionan los Signos con <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">Direcciones Específicas</a> que van en sentido contrario a las manecillas del reloj<c>.</c> Esto crea una relación de reciprocidad así como con los <n>13</n> números y la <i>Simetría Especular</i><c>.</c></p>

      <?=api_est::lis('hol.sel_cic_dir',[ 'atr'=>[ 'ide','nom','des_col','des','sel' ] ])?>

    </section>
    
    <!-- 3 etapas del desarrollo -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Factor Maya</cite><c>:</c> <q>los signos describen un <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">proceso de desarrollo</a><c>,</c> que es el mismo sendero de la vida</q><c>.</c> Este ciclo que representa las <q>progresiones de la Luz</q> se divide en <n>3</n> etapas evolutivas del ser que van del cuerpo físico al cuerpo mental<c>.</c></p>

      <?=api_est::lis('hol.sel',[ 'atr'=>['ide','nom_may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'], 'opc'=>['cab_ocu'] ])?>

    </section>  
    
    <!-- 5 familias ciclicas -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Factor Maya</cite> se organizan los <n>20</n> Signos en <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">Grupos de Ruedas Radiales</a><c>,</c> cada una <q>girando en sentido contrario a las manecillas del reloj<c>,</c> desde el Oriente hacia el Norte<c>,</c> Oeste<c>,</c> y al Sur</q><c>.</c>De esta manera se forma un modelo mandálico donde cada rueda es un fractal u holograma de la progresión completa<c>.</c> Estos grupos funcionan como <i>Engranajes de la Memoria</i> y son llamados <i>Familias Cíclicas</i><c>.</c></p>

      <?=api_est::lis('hol.sel',[ 'atr'=>['ide','nom_may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'], 'opc'=>['cab_ocu'] ])?>

    </section>
    
    <!-- 4 etapas evolutivas de la mente -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En el proceso de desarrollo del ser descripto en <cite>el Factor Maya</cite> hay <n>4</n> signos que representan articlaciones claves de las <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">etapas evolutivas de la mente</a><c>.</c> Cada uno de estos signos es regente de su dirección particular y juega un papel de <q>Guardianes Direccionales Evolutivos</q><c>.</c></p>

      <?=api_est::lis('hol.kin_cro_est',[ 'atr'=>['sel','nom','des_dir','fac_des'], 'opc'=>['cab_ocu'] ])?>

      <p>Estos signos codifican las <n>4</n> <a href="<?=$_bib?>fac#_04-04-03-01-" target="_blank">Estaciones Galácticas</a> del Tzolkin<c>,</c> que <q>representan la incesante descarga de energía galáctica en un modelo cíclico cuádruple<c>.</c> Las cuádruples energías corresponden<c>,</c> entre otras cosas<c>,</c> a las cuatro direcciones<c>.</c></q></p>

      <p>En esta descripción<c>,</c> extraída de textos proféticos<c>,</c> los Guardianes Direccionales se asocian con la imagen del <q>Quemador<c>,</c> aquel ser primordial<c>,</c> intemporal que trae el fuego<c>,</c> el héroe de la visión y la luz venerado en todas partes con nombres diferentes como prometeico dador de cultura</q><c>.</c> Cada Estación Evolutiva está relacionada a un Quemador que divide su misión en cuatro etapas<c>.</c></p>

      <p>Luego<c>,</c> en <cite>el Encantamiento del Sueño</cite> se establece <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Giro Espectral</a> como las <i><n>4</n> Estaciones o Espectros Galácticos</i> protegidas por estos guardianes<c>.</c></p>

      <?=api_est::lis('hol.kin_cro_ond',[ 'atr'=>['ide','ton','fac','enc'] ])?>

    </section>
    
  </article>

  <!-- Códigos del Encantamiento del Sueño -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <!-- El código Encantado -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_02-03-05-" target="_blank">las Fichas de los <n>20</n> Sellos Solares</a> está codificado el Color de la Raza de un lado<c>,</c> y del otro el número <i>código galáctico</i><c>,</c> que identifica la posición diaria en el <i>tablero del oráculo</i><c>,</c> junto con el <i>código encantado</i> que identifica el <i>nombre</i><c>,</c> la <i>acción</i> y el <i>poder</i> del Sello Solar y su Tribu<c>.</c></p>

      <?=api_est::lis('hol.sel',[ 'atr'=>[ 'ide','ord','nom','des_col','des' ] ])?>      

    </section>

    <!-- El código 0-19 -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_-" target="_blank">...</a> <c>.</c></p>

      <?=api_est::lis('hol.sel_cod',[ 'atr'=>[ 'ord','des_cod' ] ])?>

    </section>    

  </article>
  
  <!-- Las Parejas del Oráculo  -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_02-03-" target="_blank">...</a><c>.</c></p>

    <p>En <cite>el Telektonon</cite> <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">...</a><c>.</c></p>
    
    <!-- parejas analogas -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_par_ana',[ 'atr'=>['ini','ini_car','ini_des','fin','fin_car','fin_des'] ])?>

    </section>

    <!-- parejas antípodas -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_par_ant',[ 'atr'=>['ini','ini_car','ini_des','fin','fin_car','fin_des'] ])?>

    </section>

    <!-- parejas ocultas -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_par_ocu',[ 'atr'=>['ini','ini_car','ini_des','fin','fin_car','fin_des'] ])?>

    </section>  

  </article>

  <!-- Colocacion Cromática -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-14-" target="_blank">...</a><c>.</c></p>

    <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>20</n> o <n>00</n> a <n>19</n><c>.</c></p>

    <?=api_est::lis('hol.sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>

    <!-- 5 familias terrestres -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-14-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_cro_fam',[ 'atr'=>['ide','nom','des_cod','des_fun','des_pod','pla_cen','hum_cen','hum_ded','des','sel'] ])?>

    </section>

    <!-- 4 clanes cromáticos -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-02-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_cro_ele',[ 'atr'=>['ide','nom','des_col','des_men','des','sel'] ])?>

    </section>    

  </article>
  
  <!-- Colocación Armónica -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>1</n> a <n>20</n><c>.</c></p>

    <?=api_est::lis('hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>

    <!-- 4 Razas raiz cósmicas -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-04-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_arm_raz',[ 'atr'=>['ide','nom','des_pod','des_dir','sel'] ])?>
      
    </section>

    <!-- células del tiempo-->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-05-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.sel_arm_cel',[ 'atr'=>['ide','nom','des_fun','des_pod','des','sel'] ])?>

    </section>    

  </article>
  
  <!-- Holon Solar -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>El código 0-19</p>              

    <?=api_est::lis('hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] ])?>

    <!-- orbitas planetarias -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Factor Maya</cite> <a href="<?=$_bib?>fac" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.uni_sol_pla')?>

    </section>

    <!-- células solares -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-03-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.uni_sol_cel')?>

    </section>

    <!-- circuitos de telepatía -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

      <?=api_est::lis('hol.uni_sol_cir')?>

    </section>    

  </article>
  
  <!-- Holon planetario -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-07-" target="_blank">...</a><c>.</c></p>

    <?=api_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>

    <!-- campos dimensionales -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p></p>

      

    </section>

    <!-- centros galácticos -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p></p>

      <?=api_est::lis('hol.uni_pla_cen')?>

    </section>

    <!-- flujos de la fuerza-g -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-16-" target="_blank">...</a><c>.</c></p>

      <?=api_est::lis('hol.uni_pla_res')?>

    </section>    

  </article>
  
  <!-- Holon humano -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-08-" target="_blank">...</a><c>.</c></p>

    <?=api_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] ])?>

    <!-- Centros Galácticos -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <?=api_est::lis('hol.uni_hum_cen')?>

    </section>

    <!-- Extremidades -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <?=api_est::lis('hol.uni_hum_ext')?>

    </section>                     

    <!-- dedos -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <?=api_est::lis('hol.uni_hum_ded')?>

    </section>

    <!-- lados -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <?=api_est::lis('hol.uni_hum_res')?>

    </section>    

  </article>