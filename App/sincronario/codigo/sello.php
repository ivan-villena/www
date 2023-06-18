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

    <p>En <cite>La Tierra en Ascenso</cite> se comparan los Signos o Glifos del <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-02-" target="_blank">Calendario Sagrado Maya</a> con los <q><n>20</n> aminoácidos construidos de los <n>64</n> codones del ADN</q>.</p>

    <p>En el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-07-" target="_blank">mapa <n>7</n></a> se analiza el I<c>-</c>Ching como una descripción del código genético<c>.</c> Aquí se relacionan los <n>64</n> codones del ADN con los <n>64</n> Hexagramas<c>,</c> de los cuales se deriban dichos <b>aminoácidos</b><c>.</c></p>

    <p>En el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-04-04-" target="_blank">mapa <n>32</n></a> se retoma la relación entre los <n>64</n> hexagramas<c>,</c> esta vez con el arreglo del Cuadrado Mágico de Franklin<c>,</c> y el Calendario Sagrado de los Mayas<c>,</c> en los cuales están presentes tanto los <n>20</n> aminoácidos como los <n>20</n> signos sagrados<c>.</c></p>

  </section>
  
  <!--Rangos de Frecuencia para Símbolos ideográficos -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Factor Maya</cite> los <a href="<?=$Dir->libro?>factor_maya#_04-04-02-" target="_blank"><n>20</n> Signos Sagrados</a> se definen como <q>posibilidades de rangos de frecuencia<c>,</c> que permiten que las estructuras armónicas lleguen a existir</q> al ser combinados con los <n>13</n> números que representan <b>Rayos de Pulsación</b><c>.</c></p>

    <p>Por otro lado<c>,</c> como símbolos ideográficos requieren un entendimiento analógico y que se los imprima o dibuje<c>,</c> ya que así funcionan como <q>Disparadores de la Memoria</q><c>.</c> Comparados con los jerogríficos que utilizan imágenes para describir palabras o sonidos<c>,</c> los ideogramas utilizan signos de naturaleza abstracta para transmitir ideas<c>,</c> sin usar palabras o frases particulares<c>.</c></p>

    <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','nom_may'],'opc'=>['cab_ocu']]);?>

    <!--La Matriz Direccional -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite> se relacionan los Signos con <a href="<?=$Dir->libro?>factor_maya#_04-04-02-03-" target="_blank">Direcciones Específicas</a> que van en sentido contrario a las manecillas del reloj<c>.</c> Esto crea una relación de reciprocidad así como con los <n>13</n> números y la <b>Simetría Especular</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cic_dir',['atr'=>['ide','nom','des_col','des','sel']]);?>

    </section>
    
    <!--3 etapas del desarrollo-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite><c>:</c> <q>los signos describen un <a href="<?=$Dir->libro?>factor_maya#_04-04-02-04-" target="_blank">proceso de desarrollo</a><c>,</c> que es el mismo sendero de la vida</q><c>.</c> Este ciclo que representa las <q>progresiones de la Luz</q> se divide en <n>3</n> etapas evolutivas del ser que van del cuerpo físico al cuerpo mental<c>.</c></p>

      <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','nom_may','cic_dir','cic_ser_des'],'tit_cic'=>['cic_ser'],'opc'=>['cab_ocu']]);?>

    </section>  
    
    <!--5 familias ciclicas-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite> se organizan los <n>20</n> Signos en <a href="<?=$Dir->libro?>factor_maya#_04-04-02-05-" target="_blank">Grupos de Ruedas Radiales</a><c>,</c> cada una <q>girando en sentido contrario a las manecillas del reloj<c>,</c> desde el Oriente hacia el Norte<c>,</c> Oeste<c>,</c> y al Sur</q><c>.</c> De esta manera se forma un modelo mandálico donde cada rueda es un fractal u holograma de la progresión completa<c>.</c> Estos grupos funcionan como <b>Engranajes de la Memoria</b> y son llamados <b>Familias Cíclicas</b><c>.</c></p>

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

    <!--El código 0-19-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-02-01-02-" target="_blank">Cubo de Color</a> del <cite>Encantamiento del Sueño</cite> se encuentran las <b>Fichas de los <b>Sellos Solares</b></b> codificadas por el <b>Código Galáctico <n>0<c>-</c>19</n></b><c>,</c> representados por puntos y barras<c>.</c> Son utilizados para posicionar las fichas en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-06-" target="_blank">Tablero del Oráculo</a> y en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">Tablero del Viaje</a><c>.</c> Este arreglo<c>,</c> que comienza desde <n>0</n><c>,</c> también codifica la <b>Colocación Cromática</b> que incluye el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-02-" target="_blank">Clan Galáctico</a> y la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-14-" target="_blank">Familia Terrestre</a><c>,</c> quienes intervienen en la construcción y alineación de los Holones Solar<c>,</c> Planetario y Humano con los Ciclos del Centro Galáctico<c>.</c></p>

      <p>En <cite>las <n>13</n> Lunas en Movimiento</cite> se retoma la configuración del <a href="<?=$Dir->libro?>lunas_en_movimiento#_04-04-" target="_blank">Holon Humano</a> y el papel que juegan las <a href="<?=$Dir->libro?>lunas_en_movimiento#_04-05-" target="_blank">Familias Terrestres</a> en el Camino de la Nave del Tiempo por las <n>13</n> lunas<c>.</c></p>

      <p>Luego<c>,</c> en <cite>un Tratado del Tiempo</cite> se presentan las <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-01-" target="_blank">pruebas y demostraciones matemáticas</a> del <b>tiempo cuatridimensional</b> que subyacen en este sistema numérico vigesimal<c>.</c></p>

      <p>Y posteriormente<c>,</c> en <cite>El Telektonon</cite> <a href="<?=$Dir->libro?>telektonon#_02-03-05-02-" target="_blank">las Líneas de Fuerza Horizontales</a> son constituídas por los <b>Circuitos de Telepatía</b> entre las distintas <b>órbitas planetarias</b> cargadas con los poderes que portan los <b>sellos solares</b><c>,</c> descriptos en el <a href="<?=$Dir->libro?>telektonon#_02-03-05-02-" target="_blank">Libro de la Forma Cósmica</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','des_cod','des_pod_tel','cro_fam','cro_ele']]);?>

    </section>    

  </section>
  
  <!--Colocacion Cromática-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> la <b>Colocación Cromática</b> consiste en ordenar secuencialmente los <b>Sellos Solares</b> comenzando desde <n>00</n> a <n>19</n><c>,</c> creando así los <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank"><n>4</n> Clanes Galácticos</a> compuestos por las <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-14-" target="_blank"><n>5</n> Familias Terrestres</a> siguiendo el código galáctico de punto<c>-</c>barra<c>.</c> Esta secuencia en el <b>Tzolkin</b> codifica <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">las <n>4</n> Estaciones del Giro Espectral</a><c>.</c></p>

    <p>Luego<c>,</c> en <cite>Un Tratado del Tiempo</cite> se profundiza en el concepto de la <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-05-" target="_blank">La Cromática o Quinto Entonado</a><c>,</c> como una función del <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-02-05-" target="_blank">Factor Más Uno</a> que consiste en <q>una secuencia codificada por cinco colores<c>,</c> en que la primera unidad y la quinta son del mismo color<c>,</c> y la secuencia cromática base del código del encantamiento</q><c>.</c></p>

    <p>Por otro lado<c>,</c> dentro de la matriz del <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-01-" target="_blank">código <n>0<c>-</c>19</n></a> hay <n>4</n> cromáticas de <n>5</n> unidades cada una<c>,</c> que cubren una o dos células del tiempo<c>,</c> por lo que representan el <b>poder de Circulación</b><c>:</c> <q>Este poder de circulación es referido como <c>'</c>entonado<c>'</c> porque el quinto tono tiene siempre el mismo valor de color que el primer tono<c>;</c> por eso<c>,</c> el quinto siempre entona al primero</q><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['sel','cro_ele_des'],'tit_cic'=>['cro_ele']]);?>

    <!--4 clanes cromáticos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank">los Clanes Galácticos</a> surgen desde los <b><n>4</n> Elementos Galácticos</b> que recapitulan las <b><n>4</n> Estaciones del Giro Espectral</b><c>,</c> siendo estas las <q>articulaciones</q> de la <b>Grandeza Galáctica</b> codificando distintas cuentas<c>.</c></p>

      <p>Luego<c>,</c> para ser útiles al propósito de la Quinta Fuerza con el Sistema Solar<c>,</c> los clanes se dividen en las <b><n>10</n> Parejas Planetarias</b> del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">código <n>0<c>-</c>19</n></a><c>,</c> cuya misión en mantener la <b>Respiración Galáctica</b><c>:</c> <q>el flujo del tiempo galáctico hacia dentro y solar hacia fuera</q><c>,</c> regulando las <b><n>5</n> Células Solares del Tiempo Galáctico</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cro_ele',['atr'=>['ide','nom','des_col','des_men','sel']]);?>

    </section>       

    <!--5 familias terrestres -->       
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> las <b>Familias Terrestres</b> recrean el ciclo de cada <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank">Elemento Galáctico</a><c>,</c> codificando los <n>5</n> Centros del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">Holon Planetario</a> y del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">Holon Humano</a><c>.</c></p>

      <p>Por otro lado<c>,</c> en la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-14-" target="_blank">Cuenta atrás para la Nave del tiempo Tierra</a> las <b>Familias Terrestres</b> cumplen roles fundamentales en el cumplimiento de esta misión codificando distintas cuentas<c>.</c></p>

      <p>Además<c>,</c> codifican el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-08-" target="_blank">Sendero del Destino de <n>52</n> años</a> para cada <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-01-" target="_blank">Kin Planetario</a> que se encuentre en aventuras del <b>Servicio Planetario</b><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_cro_fam',['atr'=>['ide','nom','des_cod','des_mis','des_rol','des_acc','sel']]);?>

    </section> 

  </section>  
  
  <!--Colocación Armónica-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> la <b>Colocación Armónica</b> consiste en ordenar secuencialmente los <b>Sellos Solares</b> de <n>1</n> a <n>20</n><c>,</c> creando las <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-05-" target="_blank"><n>5</n> Células del Tiempo</a> compuestas por <b><n>4</n> Sellos Solares</b> de distintas <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-04-" target="_blank">Razas Raiz Cósmicas</a> codificadas en el <b>Código de Color</b><c>.</c> Esta secuencia de <n>20</n> en el Tzolkin codifica las <b><n>13</n> Trayectorias Armónicas</b> del <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-07-" target="_blank">Índice Armónico</a><c>.</c></p>

    <p>Luego<c>,</c> en <cite>Un Tratado del Tiempo</cite> se profundiza en el <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-04-" target="_blank">Cubo de Color<c>,</c> Armónicas y Células del Tiempo</a><c>,</c> y se dice que las células del tiempo crean una <q>Curva de Información Galáctica</q><c>.</c></p>

    <p>Más adelante<c>,</c> en <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-05-" target="_blank">La Cromática o Quinto Entonado</a> se habla sobre <b>el poder de la circulación</b> que se genera al combinar el ciclo de la <b>armónica</b> con el ciclo de la <b>cromática</b><c>:</c> <q>Debido al quinto entonado<c>,</c> hay cinco y no cuatro células del tiempo<c>.</c> En la curva de información galáctica<c>,</c> la quinta célula del tiempo asegura que la matriz entonada se auto<c>-</c>regula y sincroniza durante el intervalo entre la salida y la siguiente entrada</q><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','arm_cel_des'],'tit_cic'=>['arm_cel']]);?>

    <!--4 Razas raiz cósmicas -->       
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-04-" target="_blank">las Razas Raíz Cósmicas</a> surgen por la reagrupación de las <b><n>10</n> Parejas Planetarias</b> luego de la <b>Guerra del Tiempo</b><c>,</c> permitiendo mantener la <b>Armonía Solar<c>-</c>Galáctica</b> en cada órbita del <b>Sistema Solar</b><c>.</c></p>

      <p>Estas razas crean las <b><n>4</n> Familias de Color</b> de la Nación Arcoíris<c>,</c> que esperan ser inseminadas en el Planeta Tierra para colocar en su sitio a la <q>Nave del Tiempo Tierra</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.sel_arm_raz',['atr'=>['ide','nom','des_pod','des_dir','des_dia','des_pol','sel']]);?>
      
    </section>    

    <!--5 Células del Tiemp-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-05-" target="_blank">las Células del Tiempo</a> son codificadas por las <b><n>4</n> Razas Cósmicas</b> y se vinculan entre sí por los <b><n>4</n> Clanes</b><c>,</c> volviéndose <q>auto<c>-</c>circulantes</q><c>.</c> Al combinarse con los <b><n>13</n> Tonos Galácticos</b> se crean las <b><n>65</n> Armónicas</b> del Giro Galáctico<c>,</c> que representan el <b>ADN del Holon cuatri<c>-</c>dimensional</b><c>.</c></p>

      <p>El objetivo es <b>colocarlas en su sitio</b> para construir una <b>nave del tiempo</b> que insemine al <b>planeta Tierra</b> en el <b>Tiempo cuatri<c>-</c>dimensional</b><c>,</c> y armonice su <b>órbita</b> en la misión de mantener el <b>Tunel del Tiempo Cromático</b>.</c></p>

      <?=Doc_Dat::lis('hol.sel_arm_cel',['atr'=>['ide','nom','des_fun','des_pod','sel']]);?>

    </section>

  </section>

  <!--El Holon Solar -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>
    

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">Código <n>0<c>-</c>19</n></a> del <cite>Encantamiento del Sueño</cite> <c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','sol_pla_des'],'tit_cic'=>['sol_cel'],'opc'=>['cab_ocu']])?>    

    <!--respiracion s-g-->  
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      
    </section>    

    <!--orbitas planetarias-->  
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Factor Maya</cite> <a href="<?=$Dir->libro?>factor_maya" target="_blank">...</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.sol_pla',['atr'=>['ide','nom','nom_cod','sel','orb','cel','cir']])?>      
      
    </section>
        
    <!--células solares-->  
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-03-" target="_blank">...</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.sol_cel',['atr'=>['ide','nom','des','pla','sel']])?>      
      
    </section>    
    
    <!--circuitos de telepatía-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  


      <p>En <a href="<?=$Dir->libro?>telektonon" target="_blank">Telektonon</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.sol_cir',['atr'=>['ide','nom','des','cue','pla','sel']])?>      
      
    </section>

  </section>
  
  <!--El Holon Planetario -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>  

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','cro_fam','pla_cen','flu_res_des'],'tit_cic'=>['cro_ele']])?>      

    <!-- flujos de la fuerza-g  -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">...</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.pla_res')?>      
      
    </section>        
    
    <!-- centros galácticos -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">...</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.pla_cen',['atr'=>['ide','nom','des_fun','fam','sel']])?>      
      
    </section>      

  </section>
  
  <!--El Holon Humano -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>  

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','hum_des'],'tit_cic'=>['cro_ele']])?>          

    <!--lados-->        
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <?=Doc_Dat::lis('hol.hum_res')?>
      
    </section> 

    <!--Centros Galácticos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <?=Doc_Dat::lis('hol.hum_cen')?>
      
    </section>

    <!--Extremidades-->     
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <?=Doc_Dat::lis('hol.hum_ext')?>
      
    </section>
    
    <!--dedos-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <?=Doc_Dat::lis('hol.hum_ded')?>
      
    </section>         
       

  </section>    

</article>  