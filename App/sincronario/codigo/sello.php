<!--20 Sellos Solares-->
<article>
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <!--Códigos de la Luz y de la Vida-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>La Tierra en Ascenso</cite> se comparan los Signos o Glifos del <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-02-" target="_blank">Calendario Sagrado Maya</a> con los <q><n>20</n> aminoácidos construidos de los <n>64</n> codones del ADN</q><c>.</c></p>

    <p>En el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-07-" target="_blank">mapa <n>7</n></a> se analiza el I<c>-</c>Ching como una descripción del código genético<c>.</c> Aquí se relacionan los <n>64</n> codones del ADN con los <n>64</n> Hexagramas<c>,</c> de los cuales se deriban dichos <b>aminoácidos</b><c>.</c></p>

    <p>En el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-04-04-" target="_blank">mapa <n>32</n></a> se retoma la relación entre los <n>64</n> hexagramas<c>,</c> esta vez con el arreglo del Cuadrado Mágico de Franklin<c>,</c> y el Calendario Sagrado de los Mayas<c>,</c> en los cuales están presentes tanto los <n>20</n> aminoácidos como los <n>20</n> signos sagrados<c>.</c></p>

  </section>
  
  <!--Rangos de Frecuencia para Símbolos ideográficos-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Factor Maya</cite> los <a href="<?=$Dir->libro?>factor_maya#_04-04-02-" target="_blank"><n>20</n> Signos Sagrados</a> se definen como <q>posibilidades de rangos de frecuencia<c>,</c> que permiten que las estructuras armónicas lleguen a existir</q> al ser combinados con los <n>13</n> números que representan <b>Rayos de Pulsación</b><c>.</c></p>

    <p>Por otro lado<c>,</c> como símbolos ideográficos requieren un entendimiento analógico y que se los imprima o dibuje<c>,</c> ya que así funcionan como <q>Disparadores de la Memoria</q><c>.</c> Comparados con los jerogríficos que utilizan imágenes para describir palabras o sonidos<c>,</c> los ideogramas utilizan signos de naturaleza abstracta para transmitir ideas<c>,</c> sin usar palabras o frases particulares<c>.</c></p>

    <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','nom_may'],'opc'=>['cab_ocu']]);?>

    <!--La Matriz Direccional-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite> se relacionan los Signos con <a href="<?=$Dir->libro?>factor_maya#_04-04-02-03-" target="_blank">Direcciones Específicas</a> que van en sentido contrario a las manecillas del reloj<c>.</c></p>

      <p>Esto crea una relación de reciprocidad así como con los <n>13</n> números y la <b>Simetría Especular</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cic_dir',['atr'=>['ide','nom','des_col','des','sel']]);?>

    </section>
    
    <!--3 etapas del desarrollo-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite><c>:</c> <q>los signos describen un <a href="<?=$Dir->libro?>factor_maya#_04-04-02-04-" target="_blank">proceso de desarrollo</a><c>,</c> que es el mismo sendero de la vida</q><c>.</c></p>

      <p>Este ciclo que representa las <q>progresiones de la Luz</q> se divide en <n>3</n> etapas evolutivas del ser que van del cuerpo físico al cuerpo mental<c>.</c></p>

      <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','nom_may','cic_dir','cic_ser_des'],'tit_cic'=>['cic_ser'],'opc'=>['cab_ocu']]);?>

    </section>  
    
    <!--5 familias ciclicas-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite> se organizan los <n>20</n> Signos en <a href="<?=$Dir->libro?>factor_maya#_04-04-02-05-" target="_blank">Grupos de Ruedas Radiales</a><c>,</c> cada una <q>girando en sentido contrario a las manecillas del reloj<c>,</c> desde el Oriente hacia el Norte<c>,</c> Oeste<c>,</c> y al Sur</q><c>.</c> De esta manera se forma un modelo mandálico donde cada rueda es un fractal u holograma de la progresión completa<c>.</c></p>

      <p>Estos grupos funcionan como <b>Engranajes de la Memoria</b> y son llamados <b>Familias Cíclicas</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','nom_may','cic_dir','cic_luz_des'],'tit_cic'=>['cic_luz'],'opc'=>['cab_ocu']]);?>

    </section>
    
  </section>
  
  <!--Códigos del Encantamiento del Sueño-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <!--El código Encantado-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p><a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-05-" target="_blank">Las Fichas de los <n>20</n> <b>Sellos Solares</b></a> del <cite>Encantamiento del Sueño</cite> están codificadas por el Color de la <b>Raza Cósmica</b> de un lado<c>,</c> y del otro por el número de <b>Código galáctico</b> que identifica la posición diaria en el <b>tablero del oráculo</b><c>,</c> junto con el <b>código encantado</b> que identifica<c>:</c> el <b>nombre</b><c>,</c> la <b>acción</b> y el <b>poder</b> del Sello Solar y su Tribu<c>.</c></p>

      <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','ord','nom','des_col','des']]);?>

    </section>

    <!--El código <n>0<c>-</c>19</n>-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-02-01-02-" target="_blank">Cubo de Color</a> del <cite>Encantamiento del Sueño</cite> se encuentran las <b>Fichas de los <b>Sellos Solares</b></b> codificadas por el <b>Código Galáctico <n>0<c>-</c>19</n></b><c>,</c> representados por puntos y barras<c>.</c> Son utilizados para posicionar las fichas en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-06-" target="_blank">Tablero del Oráculo</a> y en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">Tablero del Viaje</a><c>.</c> Este arreglo<c>,</c> que comienza desde <n>0</n><c>,</c> también codifica la <b>Colocación Cromática</b> que incluye el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-02-" target="_blank">Clan Galáctico</a> y la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-14-" target="_blank">Familia Terrestre</a><c>,</c> quienes intervienen en la construcción y alineación de los Holones Solar<c>,</c> Planetario y Humano con los Ciclos del Centro Galáctico<c>.</c></p>

      <p>En <cite>las <n>13</n> Lunas en Movimiento</cite> se retoma la configuración del <a href="<?=$Dir->libro?>lunas_en_movimiento#_04-04-" target="_blank">Holon Humano</a> y el papel que juegan las <a href="<?=$Dir->libro?>lunas_en_movimiento#_04-05-" target="_blank">Familias Terrestres</a> en el Camino de la Nave del Tiempo por las <n>13</n> lunas<c>.</c></p>

      <p>Luego<c>,</c> en <cite>un Tratado del Tiempo</cite> se presentan las <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-01-" target="_blank">pruebas y demostraciones matemáticas</a> del <b>tiempo cuatridimensional</b> que subyacen en este sistema numérico vigesimal<c>.</c></p>

      <p>Y posteriormente<c>,</c> en <cite>El Telektonon</cite> <a href="<?=$Dir->libro?>telektonon#_02-03-05-02-" target="_blank">las Líneas de Fuerza Horizontales</a> son constituídas por los <b>Circuitos de Telepatía</b> entre las distintas <b>órbitas planetarias</b> cargadas con los poderes que portan los <b>sellos solares</b><c>,</c> descriptos en el <a href="<?=$Dir->libro?>telektonon#_02-03-05-02-" target="_blank">Libro de la Forma Cósmica</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['sel','ide','des_cod','des_pod_tel','cro_fam','cro_ele']]);?>

    </section>    

  </section>
  
  <!--Colocacion Cromática-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> la <b>Colocación Cromática</b> consiste en ordenar secuencialmente los <b>Sellos Solares</b> comenzando desde <n>00</n> a <n>19</n><c>,</c> creando así los <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank"><n>4</n> Clanes Galácticos</a> compuestos por las <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-14-" target="_blank"><n>5</n> Familias Terrestres</a> siguiendo el código galáctico de punto<c>-</c>barra<c>.</c> Esta secuencia en el <b>Tzolkin</b> codifica <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">las <n>4</n> Estaciones del Giro Espectral</a><c>.</c></p>

    <p>Luego<c>,</c> en <cite>Un Tratado del Tiempo</cite> se profundiza en el concepto de la <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-05-" target="_blank">La Cromática o Quinto Entonado</a><c>,</c> como una función del <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-02-05-" target="_blank">Factor Más Uno</a> que consiste en <q>una secuencia codificada por cinco colores<c>,</c> en que la primera unidad y la quinta son del mismo color<c>,</c> y la secuencia cromática base del código del encantamiento</q><c>.</c></p>

    <p>Por otro lado<c>,</c> dentro de la matriz del <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-01-" target="_blank">código <n>0<c>-</c>19</n></a> hay <n>4</n> cromáticas de <n>5</n> unidades cada una<c>,</c> que cubren una o dos células del tiempo<c>,</c> por lo que representan el <b>poder de Circulación</b><c>:</c> <q>Este poder de circulación es referido como <c>'</c>entonado<c>'</c> porque el quinto tono tiene siempre el mismo valor de color que el primer tono<c>;</c> por eso<c>,</c> el quinto siempre entona al primero</q><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['sel','cro_ele_des'],'tit_cic'=>['cro_ele'],'opc'=>['cab_ocu']]);?>

    <!--4 clanes cromáticos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>Equipo de colonización galáctica primordial basado en uno de los cuatro elementos galácticos y correspondiendo a uno de los cuatro cromáticos<c>;</c> clan del fuego amarillo<c>,</c> clan de la sangre roja<c>,</c> clan de la verdad blanca<c>,</c> clan del cielo azul</q><c>.</c></p>

      <p>Los <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank">Clanes Galácticos</a> surgen desde los <b><n>4</n> Elementos Galácticos</b> que recapitulan las <b><n>4</n> Estaciones del Giro Espectral</b><c>,</c> siendo estas las <q>articulaciones</q> de la <b>Grandeza Galáctica</b><c>.</c></p>

      <p>Luego<c>,</c> para ser útiles al propósito de la Quinta Fuerza con el Sistema Solar<c>,</c> los clanes se dividen en las <b><n>10</n> Parejas Planetarias</b> del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">código <n>0<c>-</c>19</n></a><c>,</c> cuya misión en mantener la <b>Respiración Galáctica</b><c>:</c> <q>el flujo del tiempo galáctico hacia dentro y solar hacia fuera</q><c>,</c> regulando las <b><n>5</n> Células Solares del Tiempo Galáctico</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cro_ele',['atr'=>['ide','nom','des_col','des_men','sel']]);?>

    </section>       

    <!--5 familias terrestres-->       
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>Una de las cinco series de sellos de cuatro colores codificados<c>;</c> combinados con los trece tonos galácticos<c>,</c> codifican los cumpleaños solares de acuerdo con los <n>52</n> años del Castillo del Destino<c>;</c> código horizontal del Holón del Planeta</q><c>.</c></p>

      <p>Las <b class="ide">Familias Terrestres</b> recrean el ciclo de cada <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank">Elemento Galáctico</a><c>,</c> codificando los <n>5</n> Centros del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">Holon Planetario</a> y del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">Holon Humano</a><c>.</c></p>

      <p>Por otro lado<c>,</c> en la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-14-" target="_blank">Cuenta atrás para la Nave del tiempo Tierra</a> las <b>Familias Terrestres</b> cumplen roles fundamentales en el cumplimiento de esta misión codificando distintas cuentas<c>.</c></p>

      <p>Además<c>,</c> codifican el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-08-" target="_blank">Sendero del Destino de <n>52</n> años</a> para cada <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-01-" target="_blank">Kin Planetario</a> que se encuentre en aventuras del <b>Servicio Planetario</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cro_fam',['atr'=>['ide','nom','des_cod','des_mis','des_rol','sel'],'det_des'=>['des_acc']]);?>

    </section> 

  </section>  
  
  <!--Colocación Armónica-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> la <b>Colocación Armónica</b> consiste en ordenar secuencialmente los <b>Sellos Solares</b> de <n>1</n> a <n>20</n><c>,</c> creando las <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-05-" target="_blank"><n>5</n> Células del Tiempo</a> compuestas por <b><n>4</n> Sellos Solares</b> de distintas <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-04-" target="_blank">Razas Raiz Cósmicas</a> codificadas en el <b>Código de Color</b><c>.</c></p>

    <p>Esta secuencia de <n>20</n> en el Tzolkin codifica las <b><n>13</n> Trayectorias Armónicas</b> del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-07-" target="_blank">Índice Armónico</a><c>.</c></p>

    <p>Luego<c>,</c> en <cite>Un Tratado del Tiempo</cite> se profundiza en el <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-04-" target="_blank">Cubo de Color<c>,</c> Armónicas y Células del Tiempo</a><c>,</c> y se dice que las células del tiempo crean una <q>Curva de Información Galáctica</q><c>.</c></p>

    <p>Más adelante<c>,</c> en <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-05-" target="_blank">La Cromática o Quinto Entonado</a> se habla sobre <b>el poder de la circulación</b> que se genera al combinar el ciclo de la <b>armónica</b> con el ciclo de la <b>cromática</b><c>:</c> <q>Debido al quinto entonado<c>,</c> hay cinco y no cuatro células del tiempo<c>.</c> En la curva de información galáctica<c>,</c> la quinta célula del tiempo asegura que la matriz entonada se auto<c>-</c>regula y sincroniza durante el intervalo entre la salida y la siguiente entrada</q><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','arm_cel_des'],'tit_cic'=>['arm_cel'],'opc'=>['cab_ocu']]);?>

    <!--5 Células del Tiempo-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>Una de las cinco unidades creadas por el poder de la quinta fuerza codificando las cuatro razas cósmicas raíz<c>;</c> base de auto<c>-</c>circulación de la Nave del Tiempo<c>;</c> combinada con los trece tonos galácticos crea la base de las <n>65</n> armónicas del Índice Armónico y el Libro del Kin</q><c>.</c></p>

      <p>Las <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-05-" target="_blank">Células del Tiempo</a> son codificadas por las <b><n>4</n> Razas Cósmicas</b> y se vinculan entre sí por los <b><n>4</n> Clanes</b><c>,</c> volviéndose <q>auto<c>-</c>circulantes</q><c>.</c></p>

      <p>Al combinarse con los <b><n>13</n> Tonos Galácticos</b> se crean las <b><n>65</n> Armónicas</b> del Giro Galáctico<c>,</c> que representan el <b>ADN del Holon cuatri<c>-</c>dimensional</b><c>.</c></p>

      <p>El objetivo es <b>colocarlas en su sitio</b> para construir una <b>nave del tiempo</b> que insemine al <b>planeta Tierra</b> en el <b>Tiempo cuatri<c>-</c>dimensional</b><c>,</c> y armonice su <b>órbita</b> en la misión de mantener el <b>Tunel del Tiempo Cromático</b>.</c></p>

      <?=Doc_Dat::lis('hol.sel_arm_cel',['atr'=>['ide','nom','des_fun','des_pod','sel']]);?>

    </section>

    <!--4 Razas raiz cósmicas-->       
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>Génesis cuatri-dimensional de las razas humanas de acuerdo con el código de color<c>:</c> rojo<c>,</c> blanco<c>,</c> azul<c>,</c> amarillo<c>;</c> base de la rotación de las células del tiempo de las <n>20</n> tribus solares<c>;</c> el destino realizado como la Nación del Arco Iris de la Nave del Tiempo Tierra 2013</q><c>.</c></p>

      <p>Las <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-04-" target="_blank">Razas Raíz Cósmicas</a> surgen por la reagrupación de las <b><n>10</n> Parejas Planetarias</b> luego de la <b>Guerra del Tiempo</b><c>,</c> permitiendo mantener la <b>Armonía Solar<c>-</c>Galáctica</b> en cada órbita del <b>Sistema Solar</b><c>.</c></p>

      <p>Estas razas crean las <b><n>4</n> Familias de Color</b> de la Nación Arcoíris<c>,</c> que esperan ser inseminadas en el Planeta Tierra para colocar en su sitio a la <q>Nave del Tiempo Tierra</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_arm_raz',['atr'=>['ide','nom','des_pod','des_dir','des_dia','sel'],'det_des'=>['des_pol']]);?>
      
    </section>    

  </section>

  <!--El Holon Solar-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define al Sol como<c>:</c> <q>Receptor-transmisor local de la fuerza-g cuatri-dimensional<c>;</c> base de la red de <n>20</n> sellos solares uniendo el cuerpo cuatri-dimensional del planeta Tierra<c>,</c> u holón planetario</q><c>.</c></p>

    <p>Y al <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">Código <n>0<c>-</c>19</n></a> como<c>:</c> <q>Circulación de información galáctica desde y hacia el Sol a través de diez órbitas planetarias<c>;</c> cada órbita planetaria lleva un número codificado de inhalación <c>(</c>galáctica) y exhalación solar asociado con dos de los <n>20</n> sellos solares<c>;</c> la suma de los números codificados emparejados es siempre diecinueve<c>;</c> los pares forman Kin análogos</q><c>.</c></p>

    <p>De esta forma<c>,</c> el Holon Solar representa el cuerpo cuatridimensional del Sistema Solar compuesto por <n>10</n> orbitas planetarias que mantienen el flujo de la información galáctico<c>-</c>solar<c>.</c> Estos órbitales están asociados a dos de los <n>20</n> Sellos Solares y pueden ser organizadas en Células Solares o Circuitos de Telepatía según la colocación del Tablero utilizado<c>.</c></p>

    <figure style='max-width:35rem;'>
      <img src='<?="{$Dir->art_ima}{$nv1}"?>.png'>
    </figure>     

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','sol_pla_des'],'tit_cic'=>['sol_res','sol_cel'],'opc'=>['cab_ocu']])?>    

    <!--orbitas planetarias-->  
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>En <cite>El Factor Maya</cite> el <a href="<?=$Dir->libro?>factor_maya#_05-05-02-" target="_blank">papel de los Planetas</a> es mantener la Frecuencia Resonante dentro del Heliocosmos<c>.</c></p>

      <p><q>Coordinado por una estrella central que continuamente supervisa la información galáctica a través de la pulsación cíclica de sus transmisores y receptores binarios<c>,</c> el cuerpo solar está articulado como una serie de ondas sutiles que corresponden a las órbitas de los diez planetas</q></p>      

      <figure style='max-width:35rem;'>
        <img src="<?=$Dir->libro_ima?>factor_maya/05-05.jpg" alt="">
      </figure>            
      
      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del sueño</cite> se definen a las parejas planetarias como<c>:</c> <q>Sellos solares emparejados de acuerdo al planeta<c>;</c> suma de los sellos emparejados cuyos números código es siempre <n>19</n><c>;</c> lo mismo que las parejas o pares de color análogo rojo/blanco<c>,</c> azul/amarillo</q><c>.</c></p>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">Código <n>0<c>-</c>19</n></a> se establece que<c>:</c> <q>El propósito de las parejas planetarias es mantener la respiración galáctica<c>,</c> el flujo del tiempo galáctico hacia dentro y solar hacia fuera<c>,</c> y regular las <n>5</n> células solares del tiempo galáctico</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.sol_pla',['atr'=>['ide','nom','nom_cod','sel']])?>
      
    </section>    

    <!--respiracion s-g-->  
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En <cite>El Factor Maya</cite> se define al Heliocosmos como el cuerpo del Sistema Solar<c>,</c> y la <a href="<?=$Dir->libro?>factor_maya#_05-05-01-" target="_blank">Heliopausa</a> como el ciclo de pulsaciones de 23 años<c>:</c> <n>11</n>,3 años pulsa hacia adentro como una inspiración galáctica<c>;</c> y otros <n>11</n>,3 años pulsa hacia afuera como una exhalación solar<c>.</c></p>

      <p>
        <q>Así pues<c>,</c> su inhalación consta de fuerzas cósmicas<c>,</c> frecuencias galácticas supervisadas ya sea directamente desde el núcleo galáctico<c>,</c> y/o desde otros sistemas solares dotados de inteligencia.
        <br>Su exhalación representa comentes transmutadas de energía<c>/</c>información<c>,</c> que regresan al núcleo galáctico<c>,</c> Hunab Ku.
        <br>Los planetas<c>,</c> que son giróscopos armónicos orbitales<c>,</c> ayudan en la mediación del flujo de información energética hacia y desde el núcleo galáctco<c>.</c></q>
      </p>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> los puntos en los cuales este ciclo cambia su pulsación son llamados poder Omega<c>:</c> <q>En el código Solar<c>-</c>Galáctico <n>0<c>-</c>19</n><c>,</c> Plutón y Mercurio tienen el comienzo alfa y el final omega de la inspiración galáctica y expiración solar del aliento de Kinich Ahau</q><c>.</c></p>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">Código <n>0<c>-</c>19</n></a> se establece que este ciclo reprensenta el flujo del Tiempo Galáctico hacia adentro y Solar hacia afuera mantenido por las <n>20</n> Tribus Solares desde las <n>10</n> órbitas planetarias<c>.</c></p>

      <p>Posteriormente<c>,</c> en los <a href="<?=$Dir->libro?>telektonon#_01-07-" target="_blank">Campos<c>,</c> Flujos y Circuitos</a> del Tablero del <cite>Telektonon</cite> se establece que la interacción de sus cuatro campos crea la galaxia<c>,</c> y nuestro sistema solar <q>como un vasto campo unificado de coordenadas conscientes</q><c>.</c></p>

      <p>La interacción de estas coordenadas universales crea <n>2</n> movimientos<c>:</c> galáctico<c>-</c>kármico<c>,</c> involucionado y otro solar<c>-</c>profético<c>,</c> evolucionado<c>;</c> que en nuestro Sisetma Solar <q>están contenidos en las <n>10</n> órbitas planetarias<c>,</c> relacionadas unas con otras</q><c>.</c></p>

      <p>Relacionando los Planteas con sus Sellos Solares surgen desde aquí los <q>10 poderes galáctico<c>-</c>kármicos de conversión que inician<c>,</c> Y <n>10</n> poderes solar<c>-</c>proféticos de sueño que completan<c>.</c> Estos <n>20</n> poderes representan las <n>20</n> frecuencias solares del ciclo de <n>260</n> días del tiempo cuatridimensional</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.sol_res',['atr'=>['ide','nom','sel'],'det_des'=>['des']])?>
      
    </section>    
        
    <!--células solares-->  
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>     

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>Una de las cinco series que consisten en dos pares de parejas planetarias<c>;</c> las cinco células solares juntas regulan la inhalación galáctica y la exhalación solar autocirculantes del tiempo galáctico<c>;</c> interrumpida durante “las Guerras del tiempo”<c>,</c> la reparación de la célula de transbordo intermedio es esencial para poder entonar el quinto acorde galáctico de Kinich Ahau en el 2013 d.C</q><c>.</c></p>

      <p>Para saber más sobre las guerras del tiempo<c>,</c> lee <a href="<?=$Dir->libro?>encantamiento_del_sueño#_01-" target="_blank">el Génesis</a> del <cite>Encantamiento del Sueño</cite><c>.</c></p>

      <figure style='max-width:30rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>          

      <?=Doc_Dat::lis('hol.sol_cel',['atr'=>['ide','nom','des','pla','sel']])?>      
      
    </section>    
    
    <!--circuitos de telepatía-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En los <a href="<?=$Dir->libro?>telektonon#_01-07-" target="_blank">Campos<c>,</c> Flujos y Circuitos</a> del <cite>Telektonon</cite> se definen los Circuitos de Telepatía Interplanetarios al parear las <n>10</n> órbitas planetarias entre sí<c>.</c> Esto crea <q>la red maestra de 140 unidades de tu tablero de juego</q><c>.</c></p>

      <p>Por otro lado<c>,</c> la <a href="<?=$Dir->libro?>telektonon#_02-03-05-" target="_blank">sincronometría</a> del talero se alcanza practicando las líneas de fuerza al contemplar las posiciones relacionales entre las piezas<c>.</c></p>

      <p>Las <a href="<?=$Dir->libro?>telektonon#_02-03-05-01-" target="_blank">Líneas de Fuerza Verticales</a> corresponden a los <n>28</n> días del Giro Lunar<c>,</c> mientras que las <a href="<?=$Dir->libro?>telektonon#_02-03-05-02-" target="_blank">Líneas de fuerza Horizontales</a> están determinadas por los pares de órbitas planetarias que crean los <n>5</n> circuitos<c>.</c></p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>

      <?=Doc_Dat::lis('hol.sol_cir',['atr'=>['ide','nom','des','cue','pla','sel']])?>
      
    </section>

  </section>
  
  <!--El Holon Planetario-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>  

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>La estructura cuatri-dimensional de la Nave del Tiempo Tierra<c>:</c> codificada horizontalmente por las cinco familias planetarias<c>;</c> codificadas diagonalmente desde el polo norte al polo sur<c>,</c> del oeste al este<c>,</c> como las cuatro cromáticas de <n>5</n> Kin<c>;</c> codificadas diagonalmente desde el polo norte al polo sur<c>,</c> del este al oeste<c>,</c> como las cuatro familias de color de <n>5</n> Kin<c>;</c> estos tres códigos actúan como el giroscopio del holón planetario del tiempo galáctico</q><c>.</c></p>

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">Índice del Oráculo</a> que los <n>4</n> Clanes crean la estructura icosaédrica del holon planetario al comenzar el génesis del Dragón<c>.</c></p>

    <p><q>La Nave del Tiempo es una estación de emisión de la quinta fuerza radial galáctica<c>.</c> La red de transmisión de la Nave del Tiempo es mantenida por la cooperación del Kin planetario cuatri-dimensional</q><c>.</c></p>

    <p>El propósito de inseminar las familias terrestres en el planeta es el de transducir/traducr las transmisiones galácticas desde dimensiones superiores para poder usarse en la tercera dimensión<c>.</c> Esto se logra al hacer pasar por el holon el movimiento de los <n>13</n> tonos cargándolo con el poder del tiempo galáctico<c>.</c></p>

    <figure style='max-width:35rem;'>
      <img src="<?=$Dir->libro_ima?>encantamiento_del_sueño/sel/pla.png" alt="">
    </figure>       

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','cro_fam','pla_cen','flu_res_des'],'tit_cic'=>['cro_ele']])?>      

    <!--flujos de la fuerza-g-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">Índice del Oráculo</a> del <cite>Encantamiento del Sueño</cite> se habla sobre la relación existente en los papeles de las familias polar y portal para la liberación visible del cuerpo del Arco Iris de la Tierra<c>;</c> y la protección en la entonación del quinto acorde galáctico de Kinich Ahau<c>.</c></p>

      <p><q>La familia terrestre polar que lleva el poder del Kin espectral tiene sus posiciones en el Polo Norte de la Nave del Tiempo Tierra<c>.</c> Es aquí donde la afluencia del aliento Galáctico es recibida.
        <br>Es en el Polo Sur<c>,</c> custodiado por la familia terrestre Portal<c>,</c> donde sucede la expiración Solar.
        <br>Una de las primeras metas de la Nación del Arco Iris es entender la relación entre las familias terrestres Portal de <n>4</n> puntos y la Polar de una raya<c>.</c></q>
      </p>

      <?=Doc_Dat::lis('hol.pla_res')?>      
      
    </section>        
    
    <!--centros galácticos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">Holon Planetario</a> del <cite>Encantamiento del Sueño</cite> las <n>5</n> familias terrestres codifican los <n>5</n> centros para que la red de transmisión de la Nave del Tiempo Tierra se posible<c>.</c></p>

      <p>En <a href="<?=$Dir->libro?>proyecto_rinri#_02-01-01-" target="_blank">Las cromáticas de la Biomasa</a> del <cite>Proyecto Rinri</cite> se describe una serie de meditaciones respecto al flujo de la información galáctico<c>-</c>solar a través de estas posiciones<c>.</c></p>

      <?=Doc_Dat::lis('hol.pla_cen',['atr'=>['ide','nom','des_fun','fam','sel'],'opc'=>['cab_ocu']])?>
      
    </section>

    <!--los <n>3</n> campos de la cronosfera-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>En <cite>Un Tratado del Tiempo</cite> se define a la <a href="<?=$Dir->libro?>tratado_del_tiempo#_05-04-" target="_blank">Cronósfera</a> como<c>:</c> <q>el campo cuatri-dimensional creado por el holón planetario en resonancia con la rotación del cuerpo planetario tridimensional<c>.</c> La unidad cuatri-dimensional básica de la cronosfera es el kin<c>,</c> el patrón planetario del tiempo que corresponde a la duración de una sola rotación del eje<c>,</c> un día y noche</q><c>.</c></p>

      <p>De esta manera se definen los <a href="<?=$Dir->libro?>tratado_del_tiempo#_05-02-" target="_blank">3 campos</a> o tramas del holon que generan la Cronósfera<c>:</c></p>

      <?=Doc_Dat::lis('hol.pla_tie',['atr'=>['ide','nom','des'],'det_des'=>['tie_des']])?>

    </section>
    
  </section>
  
  <!--El Holon Humano-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>  

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define como<c>:</c> <q>La quinta fuerza<c>,</c> la codificación de las <n>20</n> tribus solares que conecta el cuerpo cuatri-dimensional con el traje espacial tri-dimensional</q><c>.</c></p>

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">Índice del Oráculo</a> se establece que<c>:</c> <q>En el momento de nacer<c>,</c> el holon humano imprime el traje espacial genético tridimensional<c>.</c> Los órganos de los sentidos y los centros de placer del traje espacial son los puntos de conexión entre el traje espacial tridimensional y el holon cuatridimensional</q><c>.</c></p>

    <figure style='max-width:35rem;'>
      <img src="<?=$Dir->libro_ima?>encantamiento_del_sueño/sel/hum.png" alt="">
    </figure>       

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','hum_des'],'tit_cic'=>['cro_ele'],'opc'=>['cab_ocu']])?>          

    <!--lados del Cuerpo-->        
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>Así como en el Holon Solar el flujo de la información galáctica sigue el sentido de los elementos galácticos<c>,</c> en el Holon Humano este flujo se distribuye entre los dos lados del cuerpo donde se codifican los clanes en las manos y los pies<c>.</c></p>

      <?=Doc_Dat::lis('hol.hum_res',['atr'=>['ide','nom','sel'],'det_des'=>['des']])?>
      
    </section>
    
    <!--dedos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">Índice del Oráculo</a> se define que<c>:</c> <q>Por el poder del girar<c>,</c> el icosaedro del holon planetario codifica los <n>20</n> dedos de las manos y pies<c>,</c> estableciendo el holón humano</q><c>.</c> <q>Siguiendo la ley del quinto entonado<c>,</c> los <n>5</n> sellos solares de cada cromática codifican los dedos de la mano y del pie</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.hum_ded',['atr'=>['ide','nom','fam','sel'],'det_des'=>['des_acc']])?>
      
    </section>

    <!--Extremidades-->     
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">Índice del Oráculo</a> se define que <q>Las <n>4</n> cromáticas son asignadas a manos y pies</q><c>.</c></p>

      <p>Dada la distribución de los Sellos Solares en los Dedos de las manos y los pies<c>,</c> las cromáticas de <n>5</n> kin codifican las extremidades del cuerpo humano<c>.</c></p>

      <p>En <cite>un Tratado del Tiempo</cite> se relaciona el campo electromagnético de la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-04-" target="_blank">Cronósfera terrestre</a> con esta asignación<c>:</c> <q>En el holón humano<c>,</c> la trama electromagnética bi-polar de las cromáticas se manifiesta como las cuatro extremidades <c>-</c> las manos y los pies <c>-</c><c>,</c> como también los cuatro circuitos corporales que conectan los <n>20</n> dígitos de manos y pies con los cinco centros psicofísicos internos<c>.</c> Codificados con la trama eletromagnética<c>,</c> están la dinámica del génesis solar<c>-</c>planetario y el movimiento de las células del tiempo y las armónicas<c>.</c> De este modo<c>,</c> el holón humano con su raíz tridimensional o "traje espacial" es<c>,</c> en toda verdad<c>,</c> una biocomputadora planetaria ya evolucionada</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.hum_ext',['atr'=>['ide','nom','ele','sel'],'det_des'=>['des']])?>
      
    </section>

    <!--Centros Psicofísicos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> de define Secuencia Biocromática como<c>:</c> <q>Internalización del código cromático del holón humano que se expande a lo largo del ciclo cromático de <n>5</n> Kin<c>;</c> base para identificar el vínculo del Kin humano con la red del holón planetario</q><c>.</c></p>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">Índice del Oráculo</a> se establece que<c>:</c> <q>El poder de la quinta fuerza codifica los <n>5</n> centros del holón humano<c>.</c> Cinco circuitos conectan los <n>5</n> centros del holón humano con los <n>5</n> grupos de dedos de la mano y del pie<c>.</c> Estos <n>5</n> circuitos están codificados por las <n>5</n> células galáctico<c>-</c>solares de Kinich Ahau<c>.</c> A través de esta codificación <n>0<c>-</c>19</n> solar<c>-</c>planetaria de la quinta fuerza dentro del holón humano vienen las <n>5</n> familias terrestres que codifican los <n>5</n> castillos del destino<c>:</c> la familia terrestre polar<c>,</c> la familia terrestre cardinal<c>,</c> la familia terrestre central<c>,</c> la familia terrestre señal y la familia terrestre portal</q><c>.</c></p>

      <p>En las meditaciones del proyecto rinri con respecto a las <a href="<?=$Dir->libro?>proyecto_rinri#_02-01-02-" target="_blank">Cromáticas de la Constante de la Biomasa</a> se menciona que<c>:</c> <q>En el Holón Humano<c>,</c> la familia Portal corresponde al centro Raíz<c>;</c> la Polar a la Corona<c>.</c> Cada vez que coordinas una cromática entonada<c>,</c> del primer al segundo día<c>,</c> el movimiento salta desde tu Centro Raíz a la Corona<c>.</c> La salida por el Plexo Solar en el quinto día libera el movimiento hacia el Kuxan Suum<c>,</c> el tejido que te conecta con la galaxia<c>.</c> Esta es la clave para la sintonización biotelepática de la mente humana y la biosfera <c>-</c>73 veces al año</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.hum_cen',['atr'=>['ide','nom','fam','sel'],'det_des'=>['des']])?>
      
    </section>

  </section>    

</article>  