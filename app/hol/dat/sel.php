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

      <p>En <cite>el Factor Maya</cite> se organizan los <n>20</n> Signos en <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">Grupos de Ruedas Radiales</a><c>,</c> cada una <q>girando en sentido contrario a las manecillas del reloj<c>,</c> desde el Oriente hacia el Norte<c>,</c> Oeste<c>,</c> y al Sur</q><c>.</c> De esta manera se forma un modelo mandálico donde cada rueda es un fractal u holograma de la progresión completa<c>.</c> Estos grupos funcionan como <i>Engranajes de la Memoria</i> y son llamados <i>Familias Cíclicas</i><c>.</c></p>

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

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_02-03-05-" target="_blank">las Fichas de los <n>20</n> <i>Sellos Solares</i></a> codifican el Color de la Raza de un lado<c>,</c> y del otro el número <i>código galáctico</i><c>,</c> que identifica la posición diaria en el <i>tablero del oráculo</i><c>,</c> junto con el <i>código encantado</i> que identifica el <i>nombre</i><c>,</c> la <i>acción</i> y el <i>poder</i> del Sello Solar y su Tribu<c>.</c></p>

      <?=api_est::lis('hol.sel',[ 'atr'=>[ 'ide', 'ord', 'nom', 'des_col', 'des' ] ])?>      

    </section>

    <!-- El código 0-19 -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En el <a href="<?=$_bib?>enc#_02-02-01-02-" target="_blank">Cubo de Color</a> del <cite>Encantamiento del Sueño</cite> se encuentran las <i>Fichas de los <i>Sellos Solares</i></i> codificadas por el <i>Código Galáctico <n>0<c>-</c>19</n></i> <c>.</c> Estos números código<c>,</c> representados por puntos y barras<c>,</c> son utilizados para posicionar las fichas en el <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">Tablero del Oráculo</a> y en el <a href="<?=$_bib?>enc#_03-03-" target="_blank">Tablero del Viaje</a><c>.</c> Este arreglo<c>,</c> que comienza desde <n>0</n><c>,</c> también codifica la <i>Colocación Cromática</i> que incluye el <a href="<?=$_bib?>enc#_02-03-02-" target="_blank">Clan Galáctico</a> y la <a href="<?=$_bib?>enc#_02-03-14-" target="_blank">Familia Terrestre</a><c>,</c> quienes intervienen en la construcción y alienación de los Holones Solar<c>,</c> Planetario y Humano con los Ciclos del Centro Galáctico<c>.</c></p>

      <p>En <cite>las <n>13</n> Lunas en Movimiento</cite> se retoma la configuración del <a href="<?=$_bib?>lun#_04-04-" target="_blank">Holon Humano</a> y el papel que juegan las <a href="<?=$_bib?>lun#_04-05-" target="_blank">Familias Terrestres</a> en el Camino de la Nave del Tiempo por las <n>13</n> lunas<c>.</c></p>

      <p>Luego<c>,</c> en <cite>un Tratado del Tiempo</cite> se presentan las <a href="<?=$_bib?>tie#_04-03-01-" target="_blank">pruebas y demostraciones matemáticas</a> que subyacen en este sistema numérico vigesimal<c>.</c></p>

      <p>Y posteriormente<c>,</c> en <cite>el Telektonon</cite> <a href="<?=$_bib?>tel#_02-03-05-02-" target="_blank">las Líneas de Fuerza Horizontales</a> son constituídas por los <i>Circuitos de Telepatía</i> entre las distintas <i>órbitas planetarias</i> cargadas con los poderes que portan los <i>sellos solares</i><c>,</c> descriptos en el <a href="<?=$_bib?>tel#_02-03-05-02-" target="_blank">Libro de la Forma Cósmica</a><c>.</c></p>

      <?=api_est::lis('hol.sel_cod',[ 'atr'=>[ 'ide', 'des_cod', 'des_pod_tel' ] ])?>

    </section>    

  </article>

  <!-- Colocación Armónica -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> la <i>Colocación Armónica</i> consiste en ordenar secuencialmente los <i>Sellos Solares</i> de <n>1</n> a <n>20</n><c>,</c> creando las <a href="<?=$_bib?>enc#_03-05-" target="_blank"><n>5</n> Células del Tiempo</a> compuestas por <i><n>4</n> Sellos Solares</i> de distintas <a href="<?=$_bib?>enc#_03-04-" target="_blank">Razas Raiz Cósmicas</a> codificadas en el <i>Código de Color</i><c>.</c> Esta secuencia de <n>20</n> en el Tzolkin codifica las <i><n>13</n> Trayectorias Armónicas</i> del <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">Índice Armónico</a><c>.</c></p>

    <p>Luego<c>,</c> en <cite>Un Tratado del Tiempo</cite> se profundiza en el <a href="<?=$_bib?>tie#_04-03-04-" target="_blank">Cubo de Color<c>,</c> Armónicas y Células del Tiempo</a><c>,</c> y se dice que las células del tiempo crean una <q>Curva de Información Galáctica</q><c>.</c></p>

    <p>Más adelante<c>,</c> en <a href="<?=$_bib?>tie#_04-03-05-" target="_blank">La Cromática o Quinto Entonado</a> se habla sobre <i>el poder de la circulación</i> que se genera al combinar el ciclo de la <i>armónica</i> con el ciclo de la <i>cromática</i><c>:</c> <q>Debido al quinto entonado<c>,</c> hay cinco y no cuatro células del tiempo<c>.</c> En la curva de información galáctica<c>,</c> la quinta célula del tiempo asegura que la matriz entonada se auto<c>-</c>regula y sincroniza durante el intervalo entre la salida y la siguiente entrada</q><c>.</c></p>

    <?=api_est::lis('hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>

    <!-- 5 Células del Tiempo-->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-05-" target="_blank">las Células del Tiempo</a><c>.</c> </p>

      <?=api_est::lis('hol.sel_arm_cel',[ 'atr'=>['ide','nom','des_fun','des_pod','sel'] ])?>

    </section>

    <!-- 4 Razas raiz cósmicas -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-04-" target="_blank">las Razas Raíz Cósmicas</a><c>.</c> </p>

      <?=api_est::lis('hol.sel_arm_raz',[ 'atr'=>['ide','nom','des_pod','des_dir','sel'] ])?>
      
    </section>

  </article>  

  <!-- Colocacion Cromática -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> la <i>Colocación Cromática</i> consiste en ordenar secuencialmente los <i>Sellos Solares</i> comenzando desde <n>00</n> a <n>19</n><c>,</c> creando así los <a href="<?=$_bib?>enc#_03-02-" target="_blank"><n>4</n> Clanes Galácticos</a> compuestos por las <a href="<?=$_bib?>enc#_03-14-" target="_blank"><n>5</n> Familias Terrestres</a> siguiendo el código galáctico de punto<c>-</c>barra<c>.</c> Esta secuencia en el <i>Tzolkin</i> codifica <i>las <n>4</n> Estaciones del Giro Espectral</i><c>.</c></p>

    <p>Luego<c>,</c> en <cite>Un Tratado del Tiempo</cite> se profundiza en el concepto de la <a href="<?=$_bib?>tie#_04-03-05-" target="_blank">La Cromática o Quinto Entonado</a><c>,</c> como una función del <a href="<?=$_bib?>tie#_04-02-05-" target="_blank">Factor Más Uno</a> que consiste en <q>una secuencia codificada por cinco colores<c>,</c> en que la primera unidad y la quinta son del mismo color<c>,</c> y la secuencia cromática base del código del encantamiento</q><c>.</c></p>

    <p>Por otro lado<c>,</c> dentro de la matriz del <a href="<?=$_bib?>tie#_04-03-01-" target="_blank">código <n>0<c>-</c>19</n></a> hay <n>4</n> cromáticas de <n>5</n> unidades cada una<c>,</c> que cubren una o dos células del tiempo<c>,</c> por lo que representan el <i>poder de Circulación</i><c>:</c> <q>Este poder de circulación es referido como <c>'</c>entonado<c>'</c> porque el quinto tono tiene siempre el mismo valor de color que el primer tono<c>;</c> por eso<c>,</c> el quinto siempre entona al primero</q><c>.</c></p>

    <?=api_est::lis('hol.sel_cod',[ 'atr'=>['sel','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>

    <!-- 4 clanes cromáticos -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-02-" target="_blank">los Clanes Galácticos</a> <c>.</c></p>

      <?=api_est::lis('hol.sel_cro_ele',[ 'atr'=>['ide','nom','des_col','des','sel'] ])?>

    </section>       

    <!-- 5 familias terrestres -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2); $nv3 = 0; $nv4 = 0; ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$_bib?>enc#_03-14-" target="_blank">las Familias Terrestres</a> <c>.</c></p>

      <p>En <cite>Las <n>13</n> Lunas en Movimiento</cite><c>,</c> se amplían los datos correspondientes a las funciones de las <a href="<?=$_bib?>lun#_04-04-" target="_blank">Familias Terrestres</a><c>.</c></p>

      <?=api_est::lis('hol.sel_cro_fam',[ 'atr'=>['ide','nom','des_cod','des','sel'] ])?>

    </section> 

  </article>

  <!-- Las Parejas del Oráculo -->
  <article>
    <?php $nv1 = api_num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=api_tex::let($_nav[1][$nv1]->nom)?></h2>

    <!-- Las Relaciones de Color -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En el <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">Átomo del Tiempo Cromático</a> del <cite>Encantamiento del Sueño</cite> se presentan las relaciones de color en las que se basan las Parejas del Oráculo<c>:</c></p>

      <ul>
        <li>
          <p><b class="let-ide">Análogos</b><c>:</c></p>
          <ul class="lis">
            <li><i class="col-roj">Rojo</i> y <i class="col-bla">Blanco</i></li>
            <li><i class="col-azu">Azul</i> y <i class="col-ama">Amarillo</i></li>
          </ul>
        </li>
        <li>
          <p><b class="let-ide">Antípodas</b><c>:</c></p>
          <ul class="lis">
            <li><i class="col-roj">Rojo</i> y <i class="col-azu">Azul</i></li>
            <li><i class="col-bla">Blanco</i> y <i class="col-ama">Amarillo</i></li>
          </ul>
        </li>
        <li>
          <p><b class="let-ide">Ocultos</b><c>:</c></p>
          <ul class="lis">
            <li><i class="col-roj">Rojo</i> y <i class="col-ama">Amarillo</i></li>
            <li><i class="col-bla">Blanco</i> y <i class="col-azu">Azul</i></li>
          </ul>
        </li>      
      </ul>

    </section>

    <!-- Los Patrones del Destino -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>    

      <p>El <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">Tablero del Oráculo</a> esta compuesto por los <q><n>20</n> patrones básicos del destino</q> que se expanden en su posición guía por el poder del <n>5</n> para crear los <q><n>100</n> Patrones del Destino</q><c>.</c></p>    

      <?= api_hol::tab('sel','arm_tra',[ 
        'sec'=>[ 'par'=>1 ], 'pos'=>[ 'bor'=>1, 'col'=>"hol.sel.ide", 'ima'=>"hol.sel.ord" ] 
      ],[ 
        'sec'=>[ 'class'=>"mar_arr-3 mar_aba-3 bor-1 bor_col-5-0-" ] 
      ])?>

      <p>Estos patrones<c>,</c> que se definen por las relaciones de colores y combinación de númeos código entre dos sellos<c>,</c> son utilizados para las tres <a href="<?=$_bib?>enc#_02-03-06-01-" target="_blank">Claves de Lectura del Oráculo</a><c>:</c> <i>del Destino</i><c>,</c> <i>Diaria</i> o <i>del Azar</i><c>.</c></p>

      <figure>
        <img src='<?=$_dir->ima?>4-1.png' alt='Patrón del Destino' title='Guía del Oráculo del Encantamiento del Sueño'>
      </figure>    

    </section>

    <!-- La Sincronometría del Oráculo -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Telektonon</cite><c>,</c> el <a href="<?=$_bib?>tel#_02-03-04-" target="_blank"><n>4</n><c>°</c> nivel<c>,</c> Juego del Oráculo</a> establece las relaciones del oráculo en diagramas que miden la <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">Sincronometría</a> de los <q>Flujos del Oráculo</q><c>.</c></p>

      <figure>
        <img src='<?=$_dir->ima?>4-2.png' alt='Sincronometría del Oráculo' title='Sincronometría de los Flujos del Oráculo'>
      </figure>

    </section>

    <!-- El Tiempo N.E.T.  -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En el <cite>Tutorial del Sincronario de <n>13</n> Lunas</cite> se presenta un aplicación especial del Oráculo llamada <a href="<?=$_bib?>tut#_04-04-" target="_blank">el Tiempo Net</a> que se basa en dividir el día en <n>4</n> subciclos llamados <q>Miradas</q><c>,</c> uno por cada pareja del oráculo<c>:</c> <q>Siguiendo el patrón de tiempo net cada día llega a ser un mandala de sincronicidad<c>.</c> Sigue las miradas cambiantes del Oráculo de Quinta Fuerza del Tiempo NET <c>¡</c>para obtener una visión más profunda con el flujo de la realidad diaria<c>!</c></q><c>.</c></p>

      <figure>
        <img src='<?=$_dir->ima?>4-3.png' alt='Oráculo del Tiempo NET' title='Oráculo del Tiempo NET'>
      </figure>    

    </section>    
    
    <!-- Pareja Análoga -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> se define como <a href="<?=$_bib?>enc#_02-03-06-01-" target="_blank">Pareja Análoga</a> a dos <i>Sellos Solares</i> <q>cuyos números código sumen <n>19</n></q><c>,</c> los cuales <q>Van juntos y dan refuerzo</q><c>.</c></p>

      <p>Esta relación se vé gráficamente en el <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">Tablero del Viaje</a> donde ambos <i>Sellos Solares</i> de cada pareja análoga codifican una de las <i><n>10</n> Órbitas Planetarias</i><c>,</c> formando las <a href="<?=$_bib?>enc#_03-03-" target="_blank"><n>5</n> Células Solares Análogas</a><c>:</c> <q>La creación de equipos de células solares con el propósito de explorar los túneles del tiempo es una forma avanzada de jugar el Encantamiento del Sueño</q><c>.</c></p>

      <?=api_est::lis('hol.sel_par_ana',[ 'atr'=>['ini','ini_car','ini_des','fin','fin_car','fin_des'] ])?>

    </section>

    <!-- Pareja Antípoda -->        
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> se define como <a href="<?=$_bib?>enc#_02-03-06-01-" target="_blank">Pareja Antípoda</a> a dos <i>Sellos Solares</i> <q>cuyos números código entén separados entre sí por <n>10</n></q><c>,</c> los cuales <q>Se oponen y desafían para fortalecer la memoria del génesis</q><c>.</c></p>

      <p>Esta relación se vé gráficamente en el <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">Tablero del Génesis</a> donde los <i>Sellos Solares</i><c>,</c> correspondientes a las Ondas Encantadas<c>,</c> se agrupan con sus parejas antípodas formando las <a href="<?=$_bib?>enc#_03-06-" target="_blank"><n>5</n> Células de la Memoria del Génesis</a><c>:</c> <q>La creación de los equipos de memoria celular para viajar en el tiempo es un nivel avanzado del Oráculo del Encantamiento del Sueño</q><c>.</c></p>

      <?=api_est::lis('hol.sel_par_ant',[ 'atr'=>['ini','ini_car','ini_des','fin','fin_car','fin_des'] ])?>

    </section>

    <!-- Pareja Oculta -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> se define como <a href="<?=$_bib?>enc#_02-03-06-01-" target="_blank">Pareja Oculta</a> a dos <i>Sellos Solares</i> <q>cuyos números código sumen <n>21</n></q><c>,</c> los cuales <q>Representan factores inesperados y escondidos</q><c>.</c> A diferencia de las otras parejas<c>,</c> en el caso de dos <i>Kines</i><c>,</c> sus tonos deben sumar <n>14</n><c>.</c> Así surgen los cuartetos ocultos<c>,</c> como la combinación de dos parejas de Kines Ocultos cuyos <i>Tonos</i> sumen <n>28</n><c>.</c></p>

      <p>Esta relación se vé gráficamente en el <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">Índice Armónico</a> donde ambos <i>Sellos Solares</i> se encuentran en <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">Armónicas Inversas</a><c>,</c> representando el principio de <b class="let-ide">matriz radial.</b><c>:</c> <q>El encontrar las parejas ocultas y los cuartetos aumenta el poder de tu holón y amplifica las posibilidades de jugar</q><c>.</c></p>

      <?=api_est::lis('hol.sel_par_ocu',[ 'atr'=>['ini','ini_car','ini_des','fin','fin_car','fin_des'] ])?>

    </section>

    <!-- Parejas Guía -->
    <section>
      <?php $nv2 = api_num::val(intval($nv2) + 1,2) ?>
      <h3 id="<?="_{$_nav[2][$nv1][$nv2]->pos}-"?>"><?=api_tex::let($_nav[2][$nv1][$nv2]->nom)?></h3>

      <p>En <cite>el Encantamiento del Sueño</cite> se define la <a href="<?=$_bib?>enc#_02-03-06-01-" target="_blank">Pareja Guía</a> como dos <i>Sellos Solares</i> del mismo Color<c>,</c> los cuales <q>Representan el resultado favorecido por la dominación cromática de un color</q><c>.</c></p>

      <p>A diferencia de las demás parejas<c>,</c> para determinar el guía es necesario conocer el <i>Tono Galáctico</i> del <i>Kin</i><c>.</c> Dado que cada hay <n>5</n> <i>Sellos Solares</i> por Color<c>,</c> el Sello Guía puede variar<c>,</c> lo que crea la expansión de los <i><n>20</n> Patrones Base</i> en los <i><n>100</n> Patrones del Destino</i><c>.</c></p>

      <?=api_est::lis('hol.sel_par_gui')?>

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