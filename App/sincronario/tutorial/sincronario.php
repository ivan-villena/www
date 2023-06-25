
<?php
  require_once("./Api/Sincronario/Listado.php");
?>

<article class="tex_bib">
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1>Tutorial del Sincronario</h1>
    <p>Fuente<c>:</c> <a href="https://www.13lunas.net/tutorial/tutorial.html" target="_blank">página de <n>13</n> lunas</a></p>
  </header>  
  
  <section>
    <h2>Introducción</h2>
    
    <p>La nueva era que se avecina en nuestro planeta tiene mucho que ver con un cambio de la frecuencia del tiempo<c>.</c> El Sincronario de <n>13</n> Lunas<c>-</c><n>28</n> días es una herramienta simple que nos ayuda a elevar nuestra frecuencia y nos ofrece una nueva lente para ver tanto nuestro día a día como los acontecimientos planetarios<c>.</c> Debido a que tanto el Sincronario gregoriano como las <n>13</n> Lunas operan con <n>52</n> semanas de siete días al año <c>(</c><n>364</n> días<c>)</c><c>,</c> <c>¡</c>el Sincronario de <n>13</n> Lunas es una herramienta perfecta de transición diaria para conectar de nuevo con el orden dimensional superior<c>!</c> Es fácil seguir el día a día<c>,</c> ya que está marcado con las fechas del Sincronario gregoriano<c>.</c></p>

  </section>  

  <!-- ciclos simples -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>El Sincronario de <n>13</n> Lunas se compone de ciclos elegantemente simples<c>.</c> Específicamente<c>:</c> La semana de siete días y la Luna de <n>28</n> días<c>.</c> A diferencia del Sincronario gregoriano<c>,</c> los días de la Luna <c>(</c>mes<c>)</c> y los días de la semana van alineados perfectamente<c>,</c> semana a semana y Luna a Luna<c>.</c> Esto hace del Sincronario de <n>13</n> Lunas de <n>28</n> días un Sincronario perpetuo<c>.</c> Vamos a entrar en detalle y verás lo que queremos decir<c>.</c></p>

    <figure>
      <img src='<?=$Dir->art_ima?>01-01.png' class='mar-1'>
      <figcaption>Esta es la semana de siete días<c>,</c> todas las semanas en el Sincronario de <n>13</n> Lunas es exactamente lo mismo<c>,</c> un ciclo de <n>7</n> días<c>,</c> que también se llama una <dfn>heptada</dfn><c>.</c></figcaption>
    </figure>

    <p>Cada Luna <c>(</c>o mes<c>)</c> en el Sincronario de <n>13</n> Lunas contiene exactamente cuatro semanas de siete días o heptadas<c>.</c></p>

    <p><n>7</n> <c>x</c> <n>4</n> <c>=</c> <n>28</n><c>.</c></p>

    <p>Cada una de las cuatro semanas tiene un poder único<c>:</c></p>

    <?=Listado::tutorial_del_sincronario('lun_arm')?>

    <figure>
      <img src='<?=$Dir->art_ima?>01-02.jpg' class='mar-1'>
      <figcaption>En la parte superior lo que se ve son los nuevos nombres y símbolos de los días de la semana <c>(</c>Dali<c>,</c> Seli<c>,</c> Gama<c>,</c> Kali<c>,</c> Alfa<c>,</c> Limi y Silio<c>)</c> <c>-</c>aprenderás más sobre esto más adelante<c>.</c> De momento este gráfico es simplemente para ilustrar esta estructura de <n>28</n> días y cuatro semanas perfectas de <n>7</n> días que es exactamente la misma para las <n>13</n> Lunas del Sincronario de <n>13</n> Lunas<c>.</c></figcaption>
    </figure>
    
    <p>Los días <n>1</n><c>,</c> <n>8</n><c>,</c> <n>15</n> y <n>22</n> de la Luna siempre serán días <c>"</c>Dali<c>"</c><c>.</c></p>
    
    <p>Los días <n>2</n><c>,</c> <n>9</n><c>,</c> <n>16</n> y <n>23</n> de la Luna siempre serán días <c>"</c>Seli<c>"</c><c>.</c></p>

    <p>Y así sucesivamente…</p>

    <p>Así que cuando repites esto <n>13</n> veces tienes las <n>13</n> Lunas del Sincronario de <n>13</n> Lunas<c>:</c></p>

    <figure>
      <img src='<?=$Dir->art_ima?>01-03.png' class='mar-1'>
      <figcaption><n>13</n> Lunas <c>x</c> <n>28</n> días <c>=</c> <n>364</n> días <c>=</c> <n>52</n> semanas de siete días<c>.</c> El día <n>365</n> del año es llamado el Día Fuera del Tiempo<c>,</c> <c>¡</c>un día para celebrar la paz a través de la cultura<c>,</c> el tiempo es arte y la práctica del perdón universal para que todos podamos comenzar el próximo año frescos<c>!</c></figcaption>
    </figure>
    
    <p>En el gráfico anterior<c>,</c> los números pequeños en la parte inferior de cada cuadro son las fechas del Sincronario gregoriano de <n>12</n> meses<c>.</c> Por ejemplo<c>,</c> para encontrar el primer día del Sincronario de <n>13</n> Lunas <c>-</c>mira abajo donde dice <c>"</c><n>7</n><c>-</c><n>26</n><c>"</c> <c>-</c><c>¡</c>esta es la fecha gregoriana <n>26</n> de Julio<c>!</c> Todos los días en el Sincronario de <n>13</n> Lunas incluye la fecha gregoriana <c>-</c>esto es para que no nos perdamos en este tiempo de transición<c>.</c></p>

    <p>Encuentra ahora tu cumpleaños en el Sincronario de <n>13</n> Lunas<c>.</c> Por ejemplo<c>,</c> si tu cumpleaños es el <n>13</n> de Octubre<c>,</c> busca el día en el Sincronario que está marcado como <c>"</c><n>10<c>-</c>13</n><c>"</c><c>.</c> Observa que el <n>13</n> de Octubre es el día <n>24</n> de la Luna Eléctrica<c>.</c> La Luna Eléctrica es la tercera Luna de las <n>13</n> Lunas<c>.</c> Puedes escribir esta fecha como <n>3</n><c>.</c><n>24</n><c>.</c> Ahora busca la fecha de hoy en el Sincronario de <n>13</n> Lunas<c>.</c> Observa la Luna y en qué día de la Luna cae<c>.</c> <c>¡</c>Esto es todo lo que necesitas saber para comenzar a seguir este ciclo básico de <n>13</n> Lunas<c>!</c> No hay necesidad de memorizar canciones  como <c>"</c><n>30</n> días tiene Septiembre<c>...</c><c>"</c> porque cada Luna es exactamente de <n>28</n> días <c>-</c><c>¡</c>el primer día de la Luna es siempre el primer día de la semana<c>,</c> el último día de la Luna es siempre el último día de la semana<c>!</c></p>

    <p>Esta regularidad constante es lo que hace del Sincronario de <n>13</n> Lunas una matriz armónica del tiempo-espacio<c>.</c> Esto es sólo la punta del iceberg del tiempo cuatridimensional<c>...</c></p>

    <p><c>¡</c>Felicidades<c>!</c> <c>¡</c>Ahora has aprendido cómo marcar el ritmo del tiempo en una matriz armónica<c>!</c></p>

  </section>

  <!-- Cada dia es un portal galáctico -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>Así que ahora has visto la punta del iceberg<c>.</c> El ciclo de <n>28</n> días<c>,</c> hace el cálculo del año extremadamente fácil<c>.</c> Siempre sabes que<c>,</c> por ejemplo<c>,</c> cinco Lunas desde ahora<c>,</c> será el mismo día de la Luna<c>,</c> y el mismo día de la semana<c>.</c> Puedes ver que si fueras a calcular algo en el futuro todavía sería el mismo año del Sincronario<c>.</c> Lo que es más<c>,</c> este Sincronario<c>,</c> como una matriz armónica<c>,</c> es un reflejo perfecto del tiempo dimensional superior<c>.</c></p>

    <p>Ahora estás listo para aprender acerca de los <n>260</n> Portales Galácticos del Módulo o Tzolkin<c>.</c></p>

    <figure>
      <img src='<?=$Dir->art_ima?>02-01.png' class='mar-1'>
      <figcaption>
        Éste es el <dfn>Módulo Armónico</dfn><c>,</c> llamado Tzolkin por los mayas<c>,</c> que significa <c>"</c>cuenta sagrada<c>"</c><c>.</c> Esta es una matriz perfecta <n>13<c>:</c>20</n> <c>(</c><n>13</n> <c>x</c> <n>20</n> <c>=</c> <n>260</n><c>)</c><c>.</c> Si ya has aprendido sobre la Ley del Tiempo<c>,</c> entonces conoces que éste es el ciclo perfecto de la frecuencia de sincronización <n>13<c>:</c>20</n>.
        <br>
        El Tzolkin se lee de arriba a abajo<c>,</c> de izquierda a derecha<c>.</c> Inicia en la esquina de la parte superior izquierda y cuentas hacia abajo hasta llegar a <n>20</n><c>,</c> entonces el <n>21</n> estará en la parte superior de la columna siguiente<c>,</c> y así sucesivamente<c>.</c>        
      </figcaption>
    </figure>
    
    <p>Cada día es una combinación de uno de los <n>20</n> símbolos <c>(</c>Sellos Solares<c>)</c> y los <n>13</n> números <c>(</c>Tonos Galácticos<c>)</c><c>.</c> Esto hace un total de <n>260</n> <c>(</c><n>20</n> <c>x</c> <n>13</n><c>)</c> combinaciones de sellos y tonos<c>.</c></p>

    <p>Por ejemplo<c>,</c> aquí están las primeras cinco unidades en el Tzolkin<c>:</c></p>

    <?=Listado::tutorial_del_sincronario('kin_ima',[1,2,3,4,5])?>

    <p><i>Aprenderás más acerca de los <n>13</n> Tonos Galácticos y los <n>20</n> Sellos Solares en la siguiente sección<c>.</c></i></p>
    
    <p>El ciclo de <n>260</n> días es la medida aproximada del período de gestación humana<c>,</c> y también es un fractal perfecto del ciclo galáctico de <n>26.000</n> años que estaremos completando el <n>21</n> de Diciembre de <n>2.012</n><c>,</c> por lo que también se llama Giro Galáctico<c>.</c></p>

    <p>Este ciclo de <n>260</n> días avanza diariamente<c>,</c> todos los días por todos los años del Sincronario de <n>13</n> Lunas<c>.</c> En combinación con los <n>364</n> <c>+</c> <n>1</n> días del Sincronario de <n>13</n> Lunas<c>,</c> una amplia matriz de permutación es establecida<c>.</c> Esto crea un ciclo de <n>52</n> años <c>-</c><n>18.980</n> días <c>(</c><c>=</c> <n>260</n> <c>x</c> <n>365</n><c>)</c> <c>-</c>donde no hay dos días iguales<c>,</c> <c>¡</c>por lo que los símbolos tienen un patrón cambiante de movimiento para cada día<c>!</c></p>

    <p>En la siguiente sección de este tutorial te mostraremos una muestra de una Luna completa del Sincronario de <n>13</n> Lunas para que puedas ver cómo encajan todas las piezas</p>
    
  </section>

  <!-- Incorporandolo todo -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p class="des">Lo que vemos aquí es una Luna <c>/</c> mes del Sincronario de <n>13</n> Lunas<c>:</c></p>

    <figure>
      <img src='<?=$Dir->art_ima?>03.png' class='mar-1'>
      <figcaption>Aprenderás más acerca de los <n>13</n> Tonos Galácticos y los <n>20</n> Sellos Solares en la siguiente sección</figcaption>
    </figure>

    <p>Aquí hay un montón de símbolos y signos nuevos<c>,</c> pero que es fácil de seguir<c>.</c> Una vez que hayas aprendido los códigos de estos signos y símbolos<c>,</c> sólo tienes que seguirlos día a día<c>.</c></p>

    <p>Vamos paso a paso<c>...</c></p>

    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <figure>
        <img src='<?=$Dir->art_ima?>03-01-01.png' class='mar-aut'>        
      </figure>

      <p>Las imágenes superiores son los siete nombres y sellos de los siete Plasmas Radiales<c>:</c> los fluidos eléctricos que son los componentes básicos de la creación <c>(</c><c>¡</c>incluso antes de los quarks!<c>)</c><c>.</c> Estos plasmas emergen desde el centro de la galaxia <c>-</c> ellos también son absorbidos  y radiados a partir de los siete chakras<c>.</c> Estos plasmas son la base de una nueva tecnología telepática<c>,</c> por la que incluso podemos crear el puente arco iris alrededor de la Tierra<c>...</c></p>

      <p class="des">Cada uno de los Siete Plasmas Radiales tiene su propio poder único<c>:</c></p>

      <div class="ite">

        <?=Listado::tutorial_del_sincronario('rad')?>

        <img src='<?=$Dir->art_ima?>03-01-02.png' class='mar-1' style="max-width:20rem;">

      </div>

      <p>Por lo tanto<c>,</c> en el Sincronario de <n>13</n> Lunas cada día de la semana corresponde a uno de estos plasmas radiales <c>¡</c>que se corresponde entonces a uno de tus chakras<c>!</c> Cada día puedes visualizar el Sello de plasma en el chakra y repetir las afirmaciones que se enumeran a continuación<c>.</c></p>

      <div class="ite">

        <?=Listado::tutorial_del_sincronario('rad-lec')?>

        <img src='<?=$Dir->art_ima?>03-01-03.png' class='mar-aut' style="max-width:10rem;">        

      </div>

      <p>Más información sobre la ciencia de los Plasmas Radiales está contenida en el Manual del <a href="https://www.13lunas.net/Cartas7777/7777.htm" target="_blank">Telektonon <n>7</n>:7::7:7 Revelación del Telektonon</a> como en su <a href="https://lawoftime.org/bookstore/insides/7777.html" target="_blank">Kit gratuito</a><c>.</c></p>

    </section>

    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <figure>
        <img src='<?=$Dir->art_ima?>03-02-01.png' class='mar-1'>        
      </figure>      

      <p>En el Sincronario de <n>13</n> Lunas<c>,</c> los números grandes del <n>1</n> al <n>28</n> son el día de la Luna <c>/</c> mes<c>.</c> Cada Luna en el Sincronario de <n>13</n> Lunas tiene exactamente <c>¡</c>cuatro semanas de siete días<c>,</c> que son <n>28</n> días<c>!</c> Esto hace que sea muy sencillo planear con antelación el futuro<c>.</c> El ciclo de <n>28</n> días es en realidad una matriz de <n>28</n> <c>-</c>también llamado Tiempo Estándar Galáctico<c>,</c> un reflejo del orden dimensional superior<c>.</c></p>

      <p>El Sincronario de <n>13</n> Lunas es una herramienta para armonizarnos con el orden dimensional superior<c>.</c> El uso más elevado de lo que nosotros llamamos <c>"</c>Sincronario<c>"</c> es en realidad una medida de sincronicidad <c>-</c><c>¡</c>es por eso que llamamos al Sincronario de <n>13</n> Lunas <b>un sincronario de <n>13</n> Lunas</b><c>!</c></p>

      <p>Para tener más información sobre el tiempo galáctico ver Boletín Rinri <c>"</c><a href="https://www.13lunas.net/boletines/Rinri/V03N2_2.htm" target="_blank">Recordando el Camino Sagrado <c>-</c> Tiempo Estándar Galáctico <c>(</c>TEG<c>)</c><c>:</c> El significado esencial del Sincronario de Trece Lunas de <n>28</n> días</a><c>"</c> <c>(</c>Rinri III <n>2<c>.</c>2</n><c>)</c></p>
      
    </section>
    
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <figure>
        <img src='<?=$Dir->art_ima?>03-03-01.png' class='mar-1'>        
      </figure>      

      <p><c>¿</c>Sabías que el Sincronario que todos conocemos <c>(</c><c>"</c><n>30</n> días tiene Septiembre<c>,</c> etc<c>.</c> etc<c>.</c><c>"</c><c>)</c> se llama Sincronario Gregoriano<c>?</c> Esto se debe a que es el Sincronario establecido por el Papa Gregorio XIII del Vaticano<c>.</c></p>

      <p>Estas fechas se incluyen en cada Sincronario de <n>13</n> Lunas para que no te <c>"</c>pierdas en el tiempo<c>"</c> <c>-</c> Todos estamos en el tiempo medio<c>,</c> es decir<c>,</c> cuando estás configurando tus citas para el médico no puedes decir<c>:</c> <c>"</c>Está bien<c>.</c> Estaré allí en Luna Galáctica <n>13</n> <c>"</c><c>-</c> por lo menos todavía no se puede<c>.</c> Así<c>,</c> que mientras el Sincronario de <n>12</n> meses todavía está en uso<c>,</c> estas fechas están incluidas para que puedas mantenerte al día con el mundo en general<c>.</c></p>

      <p>Mientras continuamos en el tema<c>,</c> vamos a descubrir algunos de los orígenes de este Sincronario gregoriano<c>.</c></p>

      <p>Este Sincronario es realmente quien comenzó utilizando la palabra <c>"</c>Sincronario<c>"</c> para mantener actualizada la fecha<c>.</c> La palabra <c>"</c>Sincronario<c>"</c> viene de la palabra raíz <c>"</c>calendas<c>"</c><c>,</c> que significa <c>¡</c>libro de cuentas<c>!</c> Esto se debe a que el uso principal de este Sincronario para el hombre común fue conocer las fechas para pagar los impuestos<c>.</c> De hecho<c>,</c> este Sincronario fue impuesto al pueblo indígena del <c>"</c>Nuevo Mundo<c>"</c><c>,</c> bajo la doctrina del descubrimiento de <n>1.582</n><c>...</c></p>
      
      <p>Después de conocer los Plasmas Radiales y la Matriz de <n>28</n> puedes ver cómo un sincronario puede ser mágico <c>-</c>esto contrasta fuertemente con decir <c>"</c>Hoy es Viernes<c>,</c> 02 de Octubre<c>"</c><c>.</c></p>
      
      <!-- <p>Visita nuestra sección de Descargas gratuitas para un Sincronario perpetuo de Onda Encantada de <n>13</n> Lunas<c>.</c></p> -->
      
      <p>Para obtener más información acerca de los orígenes del Sincronario de <n>12</n> meses<c>,</c> lee <c>"</c><a href="https://www.13lunas.net/parando_el_tiempo.htm" target="_blank">Parando el Tiempo y Entrando en la Segunda Creación</a><c>"</c><c>,</c> de José Argüelles <c>/</c> Valum Votan      </p>
      
    </section>
    
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <figure>
        <img src='<?=$Dir->art_ima?>03-04-01.png' class='mar-1'>        
      </figure>

      <p>Todos los días hay un Sello Solar y un Tono Galáctico<c>,</c> esta combinación se llama <c>¡</c>la Firma Galáctica o Portal Galáctico del día<c>!</c> Esto te da algo muy interesante en qué meditar por la mañana cuando compruebas tu sincronario<c>!</c></p>

      <p>Los <n>20</n> Sellos Solares y <n>13</n> Tonos Galácticos describen los procesos de creación cósmica<c>.</c> Se puede practicar dibujando los símbolos arquetípicos<c>,</c> que están inspirando y activando en muchos niveles<c>.</c> Se describen en las <cite>Crónicas de la Historia Cósmica</cite> de la siguiente manera<c>:</c></p>
      
      <q>Los tonos galácticos puede considerarse como pulsos de radio que son similares a las pulsaciones de ondas de radio del núcleo denso del pulsar o quásar<c>.</c> Los sellos solares representan el ciclo de posibilidades del rango de frecuencia para la transformación o evolución que cada uno de estos pulsos de radio pueda experimentar.</q>

      <h4>Los <n>13</n> Tonos Galácticos</h4>

      <?=Listado::tutorial_del_sincronario('ton')?>

      <h4>Los <n>20</n> Sellos Solares</h4>

      <?=Listado::tutorial_del_sincronario('sel')?>

      <p>El portal galáctico siempre se lee <c>"</c>Sello<c>,</c> Tono<c>,</c> Color<c>"</c><c>.</c> Por ejemplo<c>,</c> un día dragón rojo<c>,</c> con el tono magnético se lee<c>:</c> <c>"</c> Dragón Magnético Rojo<c>"</c><c>.</c> Las palabras clave del Dragón Rojo son<c>:</c> nutrir<c>,</c> nacimiento<c>,</c> ser <c>-</c>Las palabras claves para el tono magnético son<c>:</c> unificar<c>,</c> atraer propósito<c>.</c></p>

      <p>Ahora vamos a tratar el portal galáctico de hoy<c>.</c> Hoy es Mono Cósmico Azul <c>-</c>Ahora estudiamos las palabras clave para el sello Mono Azul y el tono Cósmico<c>...</c></p>
      
      <p>Bastante interesante <c>¿</c>no<c>?</c></p>
      
      <p>No hay comparación entre esta experiencia y decir<c>:</c> <c>"</c>Hoy es  Lunes<c>,</c> <n>27</n> de Agosto de <n>2.012</n><c>"</c><c>.</c></p>
      
    </section>
    
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <figure>
        <img src='<?=$Dir->art_ima?>03-05-01.png' class='mar-1'>
      </figure>
      
      <p>Por último<c>,</c> pero no menos importante <c>-</c>las Fases Lunares <c>-</c>una gran presentación tridimensional de los ciclos armónicos del tiempo<c>.</c> El ciclo de <n>28</n> días<c>,</c> que es la base del Sincronario de <n>13</n> Lunas es tanto el tiempo estándar galáctico<c>,</c> como el promedio del ciclo lunar<c>:</c> el promedio entre el ciclo sinódico de la luna de <n>29</n>,5 días y el ciclo sideral de poco más de <n>27</n> días<c>.</c></p>

      <p>El ciclo sinódico es el ciclo de las fases de la luna <c>(</c>luna nueva<c>,</c> luna llena<c>,</c> menguante<c>,</c> etc<c>.</c><c>)</c></p>
      
      <p>El ciclo sideral es la medida desde el lugar donde la luna aparece en el mismo lugar en el cielo<c>.</c></p>
      
      <p>Mira cómo las fases lunares se muestran en el Sincronario gregoriano<c>,</c> y compáralas con la forma en que aparecen en el Sincronario de <n>13</n> Lunas <c>-</c>te darás cuenta de que la regularidad armónica de las fases de la Luna es mucho más evidente en la Matriz del Sincronario de <n>13</n> Lunas de <n>28</n> días<c>.</c></p>
      
      <p class="cit">
        Una Luna Nueva se levanta con el Sol
        <br>Su cuarto creciente se muestra al mediodía
        <br>La Luna Llena asciende a la hora del atardecer
        <br>y el cuarto menguante conoce la medianoche       
      </p>
      
    </section>    

  </section>

  <!-- El oráculo de la quinta fuerza -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>Así que ahora que has aprendido a seguir el Sincronario de <n>13</n> Lunas día a día<c>,</c> vamos a ir un poco más adentro de este <c>¡</c>agujero del conejo galáctico<c>!</c></p>

    <p>Dentro de la firma galáctica diaria <c>(</c>también llamado mandato galáctico<c>)</c> está también el Oráculo de Quinta Fuerza<c>.</c> Una metáfora para describir el oráculo de Quinta Fuerza es que si la firma galáctica es el brote de la flor cuatridimensional<c>,</c> entonces el Oráculo de Quinta Fuerza es el florecer de la flor brillante<c>.</c></p>
    
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>La ciencia contemporánea describe cuatro grandes fuerzas del universo<c>:</c> fuerte<c>,</c> débil<c>,</c> electromagnética y gravitacional<c>.</c> La Quinta Fuerza<c>,</c> entonces<c>,</c> es la que las aglutina a todas<c>,</c> es la Fuerza<c>-</c>G sincrónica<c>,</c> a veces llamada éter o akasha<c>.</c> <c>¡</c>Es la fuerza que sincroniza el universo<c>!</c></p>

      <p>Cada portal galáctico como sabes tiene un sello solar y un tono galáctico<c>,</c> pero también empaquetado dentro está el denso poder sincrónico de la Fuerza<c>-</c>G <c>-</c><c>¡</c>es el propósito del Oráculo de Quinta Fuerza aprovechar este patrón cíclico más profundamente y amplificar el nivel de significado que se puede extraer de cada portal galáctico<c>,</c> y más<c>!</c>      </p>

    </section>
    
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <figure>
        <img src='<?=$Dir->art_ima?>04-02.png' class='mar-1'>
      </figure>      
      
      <p>Si el Oráculo de Quinta Fuerza es nuestra florecida flor cuatridimensional<c>,</c> entonces el Tablero del Oráculo es <c>¡</c>el jardín de flores de la Fuerza<c>-</c>g galáctica<c>!</c> El Tablero del Oráculo completo <c>(</c>ver imagen superior<c>)</c> se compone de cuatro hojas más un núcleo <c>"</c>matriz<c>"</c> central<c>-</c> estas hojas contienen el oráculo básico de Quinta Fuerza para cuatro de los veinte sellos solares<c>.</c> Estudia la imagen de arriba y familiarízate con sus partes<c>.</c></p>

      <p>Las Cuatro Hojas más la Matriz Central<c>:</c></p>

      <ul>        
        <li>El Tablero del Oráculo se lee<c>:</c> Este <c>(</c>rojo<c>)</c><c>,</c> Norte <c>(</c>blanco<c>)</c><c>,</c> Oeste <c>(</c>azul<c>)</c><c>,</c> Sur <c>(</c>amarillo<c>)</c><c>,</c> Centro <c>(</c>verde<c>)</c><c>.</c></li>

        <li>Empezando por el Este <c>(</c>mano derecha Hoja Roja<c>)</c> encuentra el oráculo de Quinta Fuerza con el Sello Dragon Solar Rojo en el centro<c>,</c> este es el Oráculo del Dragón Rojo<c>.</c> Este es el inicio<c>.</c> Ahora busca el Oráculo del Viento Blanco<c>;</c> el siguiente es el Oráculo de la Noche Azul<c>,</c> y luego el Oráculo de la Semilla Amarilla<c>.</c> Después del Oráculo de la Semilla Amarilla te mueves a la Hoja Norte Blanca <c>(</c>arriba<c>)</c> para encontrar el Oráculo de la Serpiente Roja<c>,</c> continúa siguiendo la secuencia a través de los veinte oráculos<c>.</c></li>

        <li>El patrón siempre sigue la armónica de cuatro partes<c>:</c> Rojo<c>,</c> Blanco<c>,</c> Azul<c>,</c> Amarillo <c>-</c>una vez que hayas llegado al final de la cuarta hoja Amarilla del Sur entra en la zona verde de la matriz central<c>,</c> que contiene los oráculos de la Tierra Roja<c>,</c> el Espejo Blanco<c>,</c> la Tormenta Azul y el Sol Amarillo<c>.</c></li>
      </ul>      

    </section>    
    
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>El gráfico siguiente es la clave para la comprensión de los oráculos como se ven en el gráfico del Tablero del Oráculo anterior<c>.</c></p>
      
      <figure>
        <img src='<?=$Dir->art_ima?>04-03.png' class='mar-1'>
      </figure>
      
      <p>Cada sello solar cuenta con<c>:</c></p>

      <ul>
        <li>Un kin análogo o poder del apoyo<c>;</c></li>
        <li>Un kin antípoda o poder de fortalecimiento<c>/</c>desafío<c>;</c></li>
        <li>Un kin oculto o poder de lo inesperado o escondido<c>;</c></li>
        <p class="cit">Estos tres son constantes para cada sello solar<c>,</c> como se muestra en el gráfico del Tablero del Oráculo superior<c>.</c> El gráfico en blanco en la parte superior de cada diseño del oráculo representa el resultado de Quinta Fuerza<c>,</c> llamado</p>            
        <li>Kin guía o poder del ser superior<c>,</c> que es siempre del mismo color que el Sello Solar en el centro<c>.</c></li>
      </ul>
      
      <p>El gráfico clave del Oráculo del Destino anterior muestra cómo calcular el Guía para cualquier firma galáctica dada <c>(</c>portal galáctico<c>)</c><c>.</c> Resumiendo<c>:</c></p>

      <?=Listado::tutorial_del_sincronario('par_gui')?>

      <p>Por ejemplo<c>:</c></p>

      <p>Kin <n>1</n><c>,</c> Dragón Magnético Rojo tiene el número de tono <n>1</n> <c>(</c>Magnético<c>)</c> de modo que el Dragón Magnético Rojo es guiado por su propio poder duplicado<c>.</c></p>
      
      <p>Kin <n>2</n><c>,</c> Viento Lunar Blanco tiene el número de tono <n>2</n> <c>(</c>Lunar<c>)</c><c>.</c> Para el tono <n>2</n> el guía es de <n>12</n> sellos adelante<c>,</c> Viento Lunar Blanco es guiado por el Mago Lunar Blanco<c>.</c></p>
      
      <p>Estudia los patrones del Tablero del Oráculo y familiarízate con el método de cálculo del guía<c>,</c> y puedes lanzar el Oráculo de Quinta Fuerza todos los días<c>.</c></p>
      
      <p>El Oráculo de Quinta Fuerza es una meditación fascinante<c>.</c> Por ejemplo<c>,</c> observa que el Viento y la Tormenta son poderes ocultos<c>/</c>escondidos entre si<c>,</c> que el Guerrero <c>(</c>cuyo poder es la intrepidez<c>)</c> y el Enlazador de Mundos <c>(</c>cuyo poder es la muerte<c>)</c> son compañeros de desafío/antípodas<c>.</c> Contempla las relaciones entre todos los Sellos Solares y observa lo que puedes encontrar en sus códigos<c>...</c></p>
      
      <p>Tu propio Oráculo de Quinta Fuerza puede revelar muchos niveles de significado cargados sincrónicamente<c>.</c> <c>¡</c>Medita sobre los patrones y siente las luces de la Fuerza<c>-</c>g cómo llegan<c>!</c>      </p>

    </section>   
        
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Otra aplicación del Oráculo de Quinta Fuerza es que divide el ciclo diario en cuatro sub-ciclos<c>,</c> llamadas miradas<c>.</c> Esto se llama <dfn>Tiempo NET</dfn> <c>-</c>Tiempo Noosférico de la Tierra<c>.</c></p>

      <figure>
        <img src='<?=$Dir->art_ima?>04-04.png' class='mar-1'>
      </figure>
      
      <p>En el Tiempo NET<c>,</c> para cada kin diario<c>,</c> utilizas el oráculo de quinta fuerza para encontrar las cuatro miradas del día que son<c>:</c></p>

      <?=Listado::tutorial_del_sincronario('par-dia')?>
                  
      <p>Siguiendo el patrón de tiempo net cada día llega a ser un mandala de sincronicidad<c>.</c> Sigue las miradas cambiantes del Oráculo de Quinta Fuerza del Tiempo NET <c>¡</c>para obtener una visión más profunda con el flujo de la realidad diaria<c>!</c></p>

    </section>

  </section>
   
  <!-- La Firma Galáctica -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p><c>¡</c>Esperamos que este tutorial te haya ayudado para comenzar a utilizar el Sincronario de <n>13</n> Lunas a diario y así entrar en una nueva experiencia del tiempo<c>!</c> En la parte de <c>"</c><a href="http://www.13lunas.net/inical.htm" target="_blank">Sincronarios</a><c>"</c> puedes descargar el Sincronario de bolsillo de <n>13</n> Lunas del año en curso<c>.</c> </p>

    <p>Este tutorial será actualizado constantemente para describir niveles más avanzados o más profundos del Sincronario de <n>13</n> Lunas<c>...</c> Mientras tanto<c>,</c> si tienes alguna pregunta no dudes en <a href="http://www.13lunas.net/contacto.htm" target="_blank">contactar con nosotros</a><c>.</c></p>

    <p>Si aún no conoces tu Firma Galáctica o Kin Planetario</p>

    <a href="http://www.13lunas.net/fechafirmaGalacticaBasica.html" target="_blank">pulsa aquí</a>

    <figure>
      <a href="http://www.13lunas.net/fechafirmaGalacticaBasica.html" target="_blank"><img src='<?=$Dir->art_ima?>05.png' class='mar-1'></a>        
    </figure>            

  </section>

  <!-- El Banco-Psi -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>  

    <p>Fuente: <a href="https://13lunas.net/tutorial/bancopsi/" target="_blank">13lunas.net</a></p>

    <p>El banco psi es la frecuencia 13:20, el programa cuatridimensional que informa al ADN de sus ciclos de tiempo. Hasta el descubrimiento de la Ley del Tiempo (1989), el banco psi funcionaba inconscientemente.</p>
  
    <p>El banco psi es una estructura resonante, que opera en paralelo con los campos de resonancia electromagnéticos, o los Cinturones de Radiación de Van Allen, que circundan el globo en unos 17.700 kilómetros y a unos 3.200 kilómetros por encima de la Tierra. Para nosotros, como especie, los cambios en la evolución son, inevitablemente, cambios en la consciencia.</p>
  
    <p>De acuerdo con la teoría de la noosfera, los cambios en la evolución de la biosfera (vida sobre la Tierra) están programados en el ADN y registrados como estructuras de frecuencia almacenadas por el banco psi, el campo de memoria planetaria cuatridimensional.</p>
  
    <p>El banco psi también refleja el magnetismo bipolar de la Tierra y el cuádruple desplazamiento de la Tierra en una rotación alrededor del Sol.</p>
  
    <figure>
      <img src='<?=$Dir->art_ima?>06-01.jpg' alt=''>
    </figure>
  
    <p>Es el reflejo del magnetismo bipolar el que crea una placa doble del tzolkin, que se extiende desde el Polo Norte al Sur. El cuádruple desplazamiento (dos equinoccios y dos solsticios) de la Tierra en su rotación alrededor del Sol crea cuatro placas diferentes. Las cuatro placas psi grandes son divididas a su vez en una placa psi norte y otra sur, cada una es un espejo que refleja una cualidad estacional. Esto significa que los cuatro desplazamientos en el eje de la Tierra que se producen durante una órbita, son las cuentas para el invierno, primavera, verano y otoño en las zonas norte y sur de las zonas tropicales a 23,5 grados Norte y Sur del ecuador.</p>
  
    <p>La atribución del banco psi, facilita la conceptualización de la noosfera. Considera que la totalidad de los sistemas de pensamiento y conocimiento están contenidos dentro del banco psi regulador de la noosfera. Este banco psi regulador, situado entre estos dos campos electromagnéticos, no es sólo una unidad de almacenamiento para todo el pensamiento, sino que también contiene todo el conocimiento de los programas evolutivos en el tiempo.</p>
  
    <h3>Activación del Banco Psi en el Calendario de 13 Lunas como Unidades Psi Crono diarias (PSI / UPC)</h3>
  
    <figure>
      <img src='<?=$Dir->art_ima?>06-02.jpg' alt=''>
    </figure>
  
    <p>Cada día el calendario de 13 Lunas incluye una Unidad Psi Crono, que día a día es activada a través de los 364 (+1) días del año. Consulta tu <i>Almanaque de 13 Lunas Sincrónico del Viajero Estelar</i> para ver la Unidad Psi Crono diaria. </p>
  
    <ul>
      <li>260 Unidades Tzolkin = 1/2 de una Placa Psi (como se muestra arriba)</li>
      <li>1 Placa Psi = 520 unidades (un Tzolkin arriba de 260 unidades, con otro Tzolkin invertido de 260 unidades abajo - como se muestra arriba)</li>
      <li>4 Placas Psi = Completan 2080 unidades del Banco Psi</li>
    </ul>
  
    <p>Visualiza el banco psi, con la Unidad Psi Crono del día pulsando en las ocho placas del banco psi. Puedes visualizarlo brillando y pulsando en su color correspondiente. (Ejemplo, Rojo, las unidades del Dragón rojo, Blanco para las unidades de Viento Blanco, etc.)</p>
  
    <p>La descripción de cómo las 260 unidades Psi Crono encajan en el ciclo de 13 Lunas se encuentra en la <a href="http://www.13lunas.net/boletines/Rinri/introduccion.htm">Guía completa del Proyecto Rinri</a>.</p>
  
    <p class="tex_ali-cen">A continuación está el Tzolkin mostrando las fechas de 13 Lunas de todas las Unidades Psi Crono (por ejemplo, 1.1 = Luna Magnética 1, 2.10 = Luna Lunar 10, 13.13 = Luna Cósmica 13, etc.):</p>
    
    <figure>
      <img src='<?=$Dir->art_ima?>06-03.png' alt=''>
    </figure>      

  </section>

  <!-- El Nuevo Ciclo de Sirio: Sistema de Fechación Cronológica -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>  

    <p>Fuente: <a href="https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html" target="_blank">lawoftime.org</a></p>

    <p>Todo conocimiento, es con el permiso Dios, recibido a través del canal central (= 365– fractal 36.5 x 2 = 73), para esta especial misión, mediada por el Consejo Directivo de Sirio. Desde la fuente Omnigaláctica (13) a Hunab Ku, el Templo (12), a Sirio (nueve) es la cadena de comando. Ahora, es directo desde el Alto Comando de Sirio a la Misión de la Tierra. Los Nueve de Sirio son los miembros del Consejo, encargado del comando.</p>

    <p>Los Nueve de Sirio, son los Nueve Señores Del Tiempo y el Destino. La directa Cadena de Comando, fue primero establecida por la Única Luminosa Blanca "A," durante el primer Anillo del Misterio de la Piedra, del 2004 al 2005. Ahora, durante el tercer Anillo, a través del Moño del Renombrado Caracol, el directo Comando es revelado. Los Nueve-Bolontiku de Sirio son los pintados en la tumba de Pacal Votan, porque fueron ellos, quienes recibieron su Esencia, cuando su cuerpo fue dejado en la tumba (en el 683 D.A). Un ciclo de Sirio más tarde – 52 años – en el año 735 D.A, Pacal Votan retornó, como la emanación de Cha’way U Ko’kan, el cambiador de forma interdimensional, para oficiar la coronación de Janaab’ Pakal II. Esto está claramente pintado, en el segundo trono de Ah K’al Mo Nab, en el templo 20 de Nah Chan</p>

    <p>En el 10. 0. 0. 13. 0. 0 (843 DA) el ciclo profético Siriano fue iniciado – 22 ciclos de 52 años cada uno, Los 13 Cielos y los Nueve Infiernos (el Inframundo). El ciclo de 1144 (502 x 22) años, fue completado el 16-17 de Agosto de 1987. El 16 de agosto, fue Luna Magnética 22 y el 17 de Agosto, kin 56, Luna Magnética 23, fue el 22º día, después del kin 34, el 26 de julio de 1987. Estas dos fechas, conocidas como la Convergencia Armónica, conmemoraron el completamiento fractal de los 22 ciclos de Sirio de la profecía de Quetzalcoatl. De aquí en adelante, la fecha, Mago Galáctico Blanco, kin 34, Luna Magnética 1, marcó el comienzo de la nueva dispensación del tiempo, el primer Nuevo ciclo de 52 años de Sirio.</p>

    <p>Un nuevo sistema de cronología es posicionado desde esta fecha. La nueva cronología es conocida como "la Cronología de los Ciclos del Nuevo Sirio," y se aumenta, en incrementos de 52 años por ciclo de Sirio. El 26 de julio gregoriano correlacionado, fue la fecha original para marcar la elevación helíaca de Sirio, en el antiguo Egipto y entre los Mayas. El 26 de julio, es también el 34º día, después del solsticio Cristal. Es por esta razón, por la que la primera cromática de  Sirio de 260 kines, que inicia la Nueva Cronología, empezó el 23 de junio de 1987, el primer día, después del solsticio. Hay mucho, acerca la perfección de la sabiduría Siriana, que contemplar, en la recitación de los hechos precedentes.</p>

    <h3>Índice de los Ciclos del Nuevo Sirio (N. S.)</h3>

    <p>Todas las fechas, desde la correlacionada 26.7.1987. (26. 7 es también, un número codificado).</p>

    <ul>
      <li>NS 1 = 1987-2039 = 52 Anillos – 1 órbita de Sirio Beta o "año" Siriano</li>
      <li>NS 2 = 2039-2091 = 104 Anillos –2 órbitas de Sirio Beta</li>
      <li>NS 3 = 2091-2143 = 156 Anillos –3 órbitas de Sirio Beta</li>
      <li>NS 4 = 2143-2195 = 208 Anillos – 4 órbitas de Sirio Beta</li>
      <li>NS 5 = 2195-2247 = 260 Anillos –5 órbitas de Sirio Beta</li>
    </ul>

    <p><i>Completadas primero, las cinco Cromáticas orbitales de Sirio Beta. Entrada a la Era Dorada. La Noosfera absorbida en el Consejo Galáctico.</i></p>
    
    <ul>
      <li>NS 6 = 2247-2299 = 312 Anillos –6 órbitas de Sirio Beta</li>
      <li>NS 7 = 2299-2351 = 364 Anillos – 7 órbitas de Sirio Beta</li>
    </ul>

    <p><i>Completada la restauración de los siete días de la creación, cada órbita de Sirio Beta, siendo uno de los siete días de la Creación. La Segunda Creación, firmemente establecida. La verdadera Era Dorada, en la vía y unida a la civilización cósmica pentadimensional. La Noosfera absorbida en la Civilización Cósmica, tipo 2 y 3.</i></p>

    <p>Un Gran ciclo Siriano de 365 órbitas o años Sirianos = 18.980 años terrestres. El actual Gran Ciclo Siriano concluirá en el equivalente año Juliano, el año 20.967, y el previo empezó en el -16.993.</p>

    <p>También observa, que el ciclo del Nuevo Sirio está perfectamente dividido en dos mitades:
      <br>De Mago 8-1987, a Semilla 8 - 2013, y de Semilla 8 -2013, a Mago 8 - 2039.
      <br>Se completan exactamente 36.5 (fractal de 365) Cromáticas de Sirio, en Semilla 8.
    </p>

    <p>La primera mitad de este ciclo, es el Gran Tiempo de la Semilla (Mahapralaya), el agotamiento del viejo ciclo y la preparación para el nuevo, incluyendo los años de los siete años de profecía (1993-2000) y los 13 años de la Resurrección (2000-2013). La segunda mitad del NS 1, es el triunfo de la Noosfera, la alborada de la Era Psicozoica, y la nueva era solar de la Segunda Creación.</p>

    <p>Para facilidad de la fechación, el sistema cronológico está abreviado, en la siguiente forma, de izquierda a derecha: la primera posición es el número del ciclo del Nuevo Sirio; la segunda posición, es el número de años pasados desde el comienzo del actual ciclo del Nuevo Sirio; la tercera posición, es el número de la Luna del presente año; y la cuarta posición, el número del día de la Luna. Aquí están algunos ejemplos:</p>

    <p><b>NS 1.19.6.9 - kin 97</b></p>

    <p>Donde:</p>

    <ul>
      <li>NS significa Nuevo Sirio (Ciclo).</li>
      <li>El 1 se refiere al primer ciclo iniciado en el kin 34, Luna Magnética 1, año Mago Galáctico Blanco.</li>
      <li>El 19 se refiere al número de años pasados, desde el principio del presente ciclo, (1987 +19 = 2006, el actual año es Luna Magnética Roja).</li>
      <li>El 6 se refiere a la luna del presente Anillo, Luna Rítmica.</li>
      <li>El nueve se refiere al día de la presente luna,</li>
    </ul>

    <p>de ahí, NS 1.19.6.9 = Ciclo 1 del Nuevo Sirio, 19 años pasados para el año Luna Magnética Roja, Luna Rítmica 6 del Lagarto, Seli 9, kin 97 – fecha del solsticio, año seis de la cuenta regresiva hacia el 2012.</p>

    <p>La fecha inicial de arranque de la cronología Nueva Siriana, sería escrita así:</p>

    <p><b>NS 1.0.1.1 - kin 34</b></p>

    <p>Ciclo 1 del Nuevo Sirio, sin años aún pasados, desde el comienzo, de ahí 0, Luna Magnética 1, Día 1, kin 34</p>

    <p><b>El Primer Año de cualquier Ciclo del Nuevo Sirio, es siempre registrado, como 0.</b></p>

    <p>Para escribir Luna Cósmica 28, año Mago Galáctico Blanco:</p>

    <p><b>NS 1.0.13.28 - kin 137</b></p>

    <p>El Día Fuera del Tiempo, siempre completa un año, de modo que, siempre se escribe en la siguiente forma, tomando el Día Fuera del Tiempo de 1988, como un ejemplo:</p>

    <p><b>NS 1.1.0.0 - kin 138</b></p>

    <p>significando, que el primer año ha sido completado, pero no es Luna o día de la semana, de ninguna manera, de ahí, 1. 0.0. Donde, el último día del año, es siempre escrito 13. 28 (= 364), el 0. 0, como el velocímetro del giro del carro, significa 365 días o un Anillo completo.</p>

    <p>NS 1. 1. 0. 0 = 365º día del año Mago Galáctico Blanco, kin 138 (significa que el Mago 8, fue el primer año del ciclo del Nuevo Sirio).</p>

    <p>NS1. 20. 0. 0 = 365º día del año Luna Magnética Roja, kin 53 (significa, que Luna 1 fue el 20º año del ciclo del Nuevo Sirio)</p>

    <p>Otros ejemplos:</p>

    <ul>
      <li>NS 1. 6. 1. 1 = 1987 +6 = 1993, año Semilla Magnética Amarilla, Luna Magnética 1, kin 144</li>
      <li>NS 1. 20. 1. 1 = 1987 + 20 = 2007, año Mago Lunar Blanco, Luna Magnética 1, kin 54</li>
      <li>NS 1. 25. 6. 9 = 1987 + 25 = 2012, año  Tormenta Resonante Azul, Luna Rítmica 9, kin 207 (El Cierre del Gran Ciclo, 21 de Diciembre Gregoriano del 2012)</li>
      <li>NS 1. 26. 1. 1= 1987 + 26 = 2013, año Semilla Galáctica Amarilla, Luna Magnética 1, kin 164</li>
      <li>NS 2. 13. 7. 15 = Ciclo 2 del Nuevo Sirio (2039-2091), año 13 – 2039 + 13 = 2052 = año Tormenta Galáctica Azul, 2052-53, Luna Resonante, día 15, kin 21, Dragón Galáctico Rojo.</li>
      <li>NS 1. 19. 6. 20 - kin 108 = año Luna Magnética Roja, Luna Rítmica 20, kin 108 (día de Año Nuevo Gregoriano, 1º de enero del 2007).</li>
    </ul>

    <p>Para los ciclos, antes del N. S. 1, simplemente agrega un signo menos, antes de N. S:</p>

    <ul>
      <li>-NS 1 = 1935 - 1987</li>
      <li>-NS 2 = 1883 - 1935</li>
      <li>-NS 3 = 1831 - 1883</li>
      <li>-NS 4 = 1779 - 1831</li>
      <li>-NS 5 = 1727 - 1779</li>
      <li>-NS 6 = 1675 - 1727</li>
      <li>-NS 7 = 1623 - 1675</li>
      <li>-NS 8 = 1571 - 1623</li>
      <li>-NS 9 = 1519 - 1571</li>
    </ul>

    <p><i>Nota: éstos son los nueve ciclos del Infierno. -NS22, sería el primer ciclo de Cielo, del 843 al 897. En esta forma, la Cronología del Nuevo Sirio se restablece,  menos 22 días, donde la profecía de los 13 Cielos y los Nueve Infiernos, parte.</i></p>

    <p>En los ciclos previos al NS1. 0. 1. 1, la cuenta de años/anillos siguen el mismo orden, que los ciclos NS. Tú no cuentas hacia atrás. Sólo la numeración de los ciclos de Sirio corren hacia atrás, desde NS 1. El primer año de los ciclos previos al NS1, sigue desde el primer año listado arriba y termina con el último año.</p>

    <p>-NS 1. 3. 7. 15 = -NS 1 = 1935-1987, 1935 + 3 = año Luna Espectral Roja (1938-39), Luna Resonante, día 15, kin 11.</p>

    <p>-NS 1. 52. 0. 0 = Día fuera del Tiempo - 25 de Julio de 1987, kin 33</p>

    <p>(Información decodificada NS 1. 19. 5. 13 - kin 73, 54º aniversario de la revelación de la máscara de jade de Pacal Votan. 54 = seis ciclos de nueve años, El Cubo de los Nueve Señores de Sirio).</p>

    <p><i>Por el Poder de la Máscara de Jade, vestida por Votan y por la Reina Roja, también, puede el mandato del Consejo de Sirio ser cumplido.</i></p>

  </section>

</article>