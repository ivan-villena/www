<!--260 kines del Giro Galáctico-->
<article>  
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <!-- Introducción -->
  <?php $nv1=Num::val($nv1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>El kin es la uniadad básica utilizada en el tzolkin para medir distintos ciclos de tiempo. En el Sincronario al ciclo de 260 días se lo llama <q>Orden Sincrónico</q>.</p>    

    <figure style='max-width:35rem;'>
      <img src='<?="{$Dir->art_ima}{$nv1}"?>.png'>
    </figure>    

    <p>Estos kines expresan rangos de frecuencia de la energía galáctica-solar que recibimos diariamente desde el Centro Galáctico a través del Sol, por ello se los relaciona con los códigos de la Luz y de la Vida a través del Tiempo Galáctico.</p>

    <p>Como rangos de frecuencia, ( formas de vibración, o estados del ser ) cada kin está compuesto por la combinación de un sello ( frecuencia solar ) y un tono ( frecuencia galáctica ). Por ello, visto como una Matriz Armónica, esta expresa las distintas condiciones del ser como vibraciones emanadas desde el centro de la galaxia y recibidas como información a través de la energía lumínica del Sol.</p>

    <p>Según sus distintas colocaiones y formas de agrupar surgen las cuentas del Orden Sincrónico Diario.</p>

    <ul>
      <li><a href="#_03-" target="_blank">Cromática del Giro Espectral</a>: comenzando desde el sello 20 o 0, Sol, se cuentan ciclos de 5 días formando 4 estaciones de de 65 días.</li>
      <li><a href="#_04-" target="_blank">Armónica del Giro Galáctico</a>: comenzando desde el sello 1, Dragón, se cuentan ciclos de 4 días formando 13 trayectorias de 20 días.</li>
      <li><a href="#_05-" target="_blank">La Nave del Tiempo</a>: comenzando desde el tono 1 del sello 1, Dragón Magnético Rojo, se cuentan ciclos de 13 días formando 5 castillos de 52 días.</li>
    </ul>

    <p>Entonces, podemos entender a un kin como la información que se transmite en un cierto momento del tiempo expresado en forma de patrón de comportamiento dentro de los ciclos de la existencia.</p>

    <p>En la Firma Galáctica del Kin Planetario dentro del <cite>Encantamiento del Sueño</cite>, estos kines pueden ser relacionados a una persona según su fecha de nacimiento, dotándolos con ciertas características energéticas y asignando ciertos grupos de relación.</p>

  </section>

  <!-- El Calendario Sagrado Tzolkin -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>
    
    <p>En <cite>la Tierra en Ascenso</cite> se presenta al <b>Tzolkin</b> como <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-02-" target="_blank">la Matriz Primaria</a><c>:</c> un modelo numérico compuesto por la combinación de dos series<c>,</c> <n>13 <c>x</c> 20</n><c>,</c> dando como resultado <n>260</n> permutaciones<c>.</c> Entre los Mayas este patrón de permutaciones era conocido como el <q>Calendario Sagrado</q> puesto que las <n>20</n> filas representadas en los <n>20</n> Signos simbolizaban <q>los más primarios aspectos del proceso vida<c>/</c>muerte</q><c>.</c></p>

    <p>Este Calendario de 260 días se utilizaba para medir los ciclos de tiempos que duran las Manchas Solares<c>.</c> En este caso se relaciona el número <n>13</n> con <q>asociaciones calendáricas<c>/</c>cíclicas</q><c>;</c> y al <n>20</n> con los <q>Aminoácidos construídos de los <n>64</n> codones del ADN</q><c>.</c></p>

    <p>En el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-09-" target="_blank">mapa <n>9</n></a> se presenta al <b>Tzolkin</b> como un arreglo horizontal del Calendario Sagrado Maya, donde figuran los 20 Signos o Glifos Sagrados asociados a una dirección cardinal<c>;</c> y los 13 números como una progresión del tiempo.</p>

    <p>En el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-04-02-" target="_blank">mapa <n>30</n></a> se aplica el Calendario Sagrado como una porción del <b>Banco-Psi</b> para medir los Ciclos de las Manchas Solares.</p>    

    <figure>
      <img src="<?=$Dir->libro_ima?>tierra_en_ascenso/01-09.GIF" alt="El Tzolkin, la versión Maya del Calendario Sagrado escrito en anotación matemática binaria con los glifos Mayas del día">
    </figure>

    <!-- Los 20 sellos -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>    

      <p>Esta serie de 20 se describe como los Signos Sagrados, Glifos o Sellos Solares.</p>

      <?=Doc_Dat::lis('hol.sel',['atr'=>['ide'],'opc'=>['cab_ocu']]);?>

      <p>Como signos sagrados están asociados a los procesos de la vida y la muerte. También en esta consideración se los compara con los 20 aminoácidos del ADN.</p>

      <p>Como Glifos representan Rangos de Frecuencia para las Estructuras Armónicas, es decir, los distintos aspectos del ser.</p>

      <p>Como Sellos Solares encapsulan la información correspondiente a distintos Arquetipos de Comportamiento o Acción.</p>

      <p>Para saber más sobre los 20 Sellos Solares, haz click <a href="<?=$Dir->codigo?>sello" target="_blank">aquí</a>...</p>

    </section>

    <!-- Los 13 tonos -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>    

      <p>Esta serie de 13 se describe como Números, Rayos de Pulsación o Tonos Galácticos.</p>

      <?=Doc_Dat::lis('hol.ton',['atr'=>['ide'],'opc'=>['cab_ocu']]);?>

      <p>Como Números estos tienen asociaciones calendáricas/cíclicas que se relacionan al Calendario Sagrado Maya.</p>

      <p>Como Rayos de Pulsación emanados desde el Centro Galáctico estos tienen distintas funciones radio-rensonantes que están presenten en las transducciones resonantes del módulo armónico.</p>

      <p>Como Tonos Galácticos de la Creación describen una secuencia cíclica y definen la Onda Encantada como el Módulo de Sincronización para lograr la Resonancia Armonica con el centro Galáctico.</p>

      <p>Para saber más sobre los 13 Tonos Galácticos, haz click <a href="<?=$Dir->codigo?>tono" target="_blank">aquí</a>...</p>

    </section>    

  </section>

  <!-- El Módulo Armónico -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>
    
    <p>En la <a href="<?=$Dir->libro?>factor_maya#_00-" target="_blank">Introducción</a> del <cite>Factor Maya</cite> se presenta el Módulo Armónico como un <b>Calendario Galáctico</b> basado en el Código Maya o Código Armónico.</p>

    <p>Más adelante se habla sobe la <a href="<?=$Dir->libro?>factor_maya#_02-06-" target="_blank">Transducción Resonante</a>. 

    <p>Definiendo a <b>la Armonía</b> como el resultado de mantener comunicación directa con el centro galáctico, a través de la estrella local, para alinearse con el todo estableciendo y extendiendo la comprension de este; la Transduccion Resonante se presenta como la Capaccidad de aplicar el conocimiento sobre las ondas armónicas, y los cambios de frecuencia, para pasar de una condición del ser a otra y entre los distintos sistemas estelares.</p>    
  
    <p>Bajo este contexto, el <b>Código Armónico</b> era utilizado por los mayas para coordinar armonicamente los distintos sistemas estelares de comprensión, sistematizando el conocimiento o la información común de una forma simple.</p>

    <p>De esta forma surge el <b>Tzolkin</b> o Módulo Armónico como un instrumento con forma de matriz matemática que expresa las transformaciones armónicas, transmisiones y transducciones como una tabla periódica de frecuencias galácticas...</p>        

    <p>Por otra parte, con respecto al concepto de <a href="<?=$Dir->libro?>factor_maya#_02-07-" target="_blank">información</a>:

      <br><q>De acuerdo al Factor Maya, el viaje espacial es información, una información transmitida por el principio de la resonancia armónica. Nosotros somos información. El universo es información. La información, igual que el número, es en última instancia una propiedad de resonancia mental. La información es energía estructurada de acuerdo al receptor para el cual está destinada. El elemento limitante o forma que lleva el aspecto de la información no oculta el hecho de que el contenedor está in-formado por una cualidad de energía. Escuchamos música, ondas de sonido propagadas a través del espacio, y algo dentro de nosotros experimenta una carga emocional. Ha ocurrido una transducción - una transformación de sonido, un tipo de información, en energía emocional, que es otro tipo de información.</p>
    </p>

    <p>Entonces, la <b>Transducción Resonante</b> representa los medios por los cuales la onda armónica se transmite, se comunica, y pasa de una condición del ser a otra, mediante la utilización práctica del Kuxan Suum.</p>

    <p>El <a href="<?=$Dir->libro?>factor_maya#_02-08-" target="_blank">Tzolkin</a> es el instrumento que nos sirve para identificar esas frecuencias de Resonancia Armónica, ya que da cuentas sobre los ciclos de las manchas solares; funcionando como una <a href="<?=$Dir->libro?>factor_maya#_02-07-" target="_blank">Constante del Ciclo Galáctico</a>: 
      
      <br><q>El Tzolkin o Módulo Armónico, se presenta como una metáfora perfecta del circuito galáctico que se genera y renueva a sí mismo.</q>
    </p>

    <figure style='max-width:25rem;'>
      <img src='<?=$Dir->libro_ima?>factor_maya/03-03.jpg' alt="MATRIZ RADIAL Y TZOLKIN CON GUÍA DIRECCIONAL">
    </figure>

    <p>Compuesto por 260 unidades que son el resultado de factorizar las 20 posiciones direccionales ( identificadas con los signos sagrados o sellos solares ) y los 13 números ( identificados con series de frecuencias o tonos galácticos ), esta Matriz primaria es Emitida por el núcleo galáctico y remitida de nuevo a él.</p>    

    <p>
      <q>entonces también podemos presumir que de una u otra forma esta matriz pulsante -la constante galáctica- penetrará y estará en todos los aspectos de las funciones galácticas a través de todos los remotos sistemas estelares de la galaxia. 
      <br>Hay que recordar que los números y posiciones direccionales, describen el rango total de las relaciones armónicas tonales, con todos sus tonos secundarios resonantes y con todas sus posibilidades transformadoras.
      <br>En resumen, el Tzolkin es un teclado o tablero de frecuencias periódicas de aplicación universal.</q>
    </p>    

    <!-- Los 52 portales de Activación Galáctica -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Se presenta por primera vez en <cite>La Tierra en Ascenso</cite> como <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-02-" target="_blank">La Triple Configuración Binaria</a>, una configuración inherente al Calendario Sagrado, definiéndola como una <q>Estructura Resonante Primaria</q> común a todos los procesos y sistemas. Su triple aspecto es determinado por las 20 filas horizontales o latitudinales, y es doble dada su simetría bilateral.</p>

      <figure style="max-width:35rem;">
        <img src='<?=$Dir->libro_ima?>tierra_en_ascenso/01-02.GIF' alt="La Triple Configuración Binaria">
      </figure>      

      <p>Para su estudio, esta estructura es organizada en distintos grupos:</p>

      <ul>
        <li>En sentido horizontal hay 4 configuraciones de 5 filas cada una. Estas se dividen en 3 grupos:
          <ul>
            <li>Zona Polar Norte: primeras 5 filas.</li>
            <li>Zona de Transformación: las 10 filas centrales sub-divididas en dos grupos.</li>
            <li>Zona Polar Sur: últimas 5 filas.</li>
          </ul>
        </li>
        <li>En sentido vertical, cada lado del patrón consta de 26 unidades, para las 52 que tiene en total. Esta configuración representa uma imagen básica de carga y flujo eléctrico polar.
          <ul>
            <li>El lado izquierdo representa un flujo negativo de energía</li>
            <li>El lado derecho representa un flujo positivo de energía</li>
          </ul>
          <p>Esta configuración también se relaciona con el mecanismo básico de la doble hélice del código genético: <q>2 ramales del ADN cruzando y enrollándose uno alrededor del otro</q>.</p>
          <p>Por otro lado, se las asocia a los dos flujos de información biopsíquica de la vida y consciencia humana:</p>
          <ul>
            <li>Izquierdo: la corriente CA del futuro al pasado ( continuidad aboriginal ). Es el flujo o corriente de la visión, intuición, sueño ( <q>se mueve del futuro sintetizado hacia el presente particularizado.</q> ).</li>
            <li>Derecho: la corriente AC del pasado al futuro ( avance civilizacional ). Representa la información como está manifiesta y codificada en símbolos, formas escritas, estructuras materiales, libros, bibliotecas y así sucesivamente ( <q>métodos analíticos que articulan modos de acción específica construidos sobre modelos del comportamiento y conocimiento pasados.</q> ).</li>
          </ul>
        </li>
        <li>En un sentido que va desde el exterior en sus extremos hacia al centro en el interior, hay 13 grupos de 4 unidades.
          <ul>
            <li>En este caso, para cada grupo de 4, la suma de los tonos da 28, que la cantidad de días en un giro lunar;</li>
            <li>También hay 13 giros lunares por cada giro solar. Por lo que la cantidad total de 28 * 13 da 364, que son la cantidad de días del anillo solar anual.</li>                        
            <li><q>Esta es una clave numérica para la naturaleza calendárica básica de la estructura.</q></li>
            <li><q>Cuando este movimiento numérico es considerado como un patrón de resonancia concéntrica, u ondas de sonido que emanan de un punto central (la unión de Omega y Alfa de la Columna Mística), pueden tomarse en una forma tridimensional o esférica.</q></li>                      
          </ul>
        </li>
      </ul>

      <p>Posteriormente, de esta configuración se deriva el <a href="<?=$Dir->libro?>tierra_en_ascenso#_02-01-03-" target="_blank">Modelo de Campo Resonante</a> ( topocosmos holonómico ), compuesto por 3 campos de resonancia mutuamente intersectantes:</p>

      <ul>
        <li>Campo Electromagnético</li>
        <li>Campo Gravitacional</li>
        <li>Campo Biopsíquico</li>
      </ul>

      <figure style="max-width:35rem;">
        <img src='<?=$Dir->libro_ima?>tierra_en_ascenso/01-03.GIF' alt="Modelo de Campo Resonante">
      </figure>        

      <p><q>Mientras que la triple configuración binaria es una estructura de información, el modelo de campo resonante o topocosmos holonómico representa la matriz primaria del espacio/tiempo de una estructura total. Como tal, es el modelo esquemático del átomo, de un organismo vivo, del planeta, del sistema solar o del universo mismo.</q></p>

      <p>En <cite>El Factor Maya</cite> se menciona como <a href="<?=$Dir->libro?>factor_maya#_04-" target="_blank">El Telar Maya</a>: un conjunto de 52 unidades comprendidas en el tzolkin. En este caso, también se ve como una combinación de 13 x 4 y representa <q>las posibilidades de las posiciones direccionales que se reflejan en un modelo unificado</q>.</p>

      <p>Se le llama telar porque la matriz del tzolkin es tejida como una tela de 260 componentes o símbolos <q>que informan a nuestros sentidos y a nuestra mente con las claves informáticas necesarias para relacionarse y trabajar con ese mundo más grande que nos rodea.</q> Estos son lo que llama <a href="<?=$Dir->libro?>factor_maya#_04-02-" target="_blank">los hilos del destino</a>.</p>

      <p><q>Los veinte símbolos representan el ciclo de posibles rangos de frecuencia, para la transformación o evolución que cada una de estas radio-pulsaciones pueda sufrir. La combinación de cualquiera de los trece números y las veinte posiciones direccionales, crea un símbolo o modelo de pulsación radiante que contiene una clase particular de información. Las 260 pulsaciones tejidas por el Telar Maya, dan origen a todo el campo resonante que experimentamos como realidad.</q></p>

      <p>Más adelante, se considera <a href="<?=$Dir->libro?>factor_maya#_04-03-" target="_blank">la estructura galáctica resonante de <n>52</n> unidades</a> como un cuerpo que contiene a las 260 unidades del módulo armónico, encapsulando la información que este transmite.</p>

      <p>Si multiplicamos las 28 unidades por los 13 grupos, pasamos de 260 a 364 unidades en total, es decir, de un giro galáctico a un giro anillo solar/anual.</p>

      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/04-04.jpg' alt="El telar Maya y los 13 juegos de 4 unidades sumando 28">
      </figure>

    </section>

    <!-- Los 64 Hexagramas del I-Ching -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En <cite>El Factor Maya</cite> se habla sobre <a href="<?=$Dir->libro?>factor_maya#_04-01-01-" target="_blank">las relaciones entre el Tzolkin y el I-ching</a>:</p>

      <p><q>Mientras que el I-Ching está sincronizado de manera exacta con el código genético, el Tzolkin está sincronizado con el código galáctico: como el código genético gobierna la información concerniente a la actividad de todos los niveles del ciclo de vida, inclusive de todas las plantas y formas animales, el código galáctico rige la información que afecta las operaciones de los ciclos de la Luz. El ciclo de luz define las clases de frecuencia resonantes de la energía radiante incluso de la electricidad, el calor, la luz, y las ondas de radio, que les dan información a las funciones auto-generadoras pertenecientes a todos los fenómenos, orgánicos e inorgánicos. Obviamente los dos códigos están interpenetrados y son complementarios.</q></p>

      <p>Más adelante, se relaciona <a href="<?=$Dir->libro?>factor_maya#_04-01-01-" target="_blank">el código genético y el código galáctico</a> en el sentido que la luz es la que permite la vida.</p>

      <p><q>El poder de llevar información, al cual designamos como -transmisión- y el de trasmutar la energía al cual designamos como -transformación- es inherente a la energía radiante, y al igual que el ADN, está regido por un código. Además, debemos recordar que el ADN posee una infraestructura vibratoria paralela a la estructura molecular. Y es esta infraestructura radiante y vibratoria -el cuerpo de luz-, la que corresponde al espectro de energía radiante regido por el código de Tzolkin, el Módulo Armónico de los mayas.</q></p>

      <p>Posteriormente, se habla sobre la aplicación de la matriz armónica en la historia y los procesos en que esta afecta la <a href="<?=$Dir->libro?>factor_maya#_07-03-02-" target="_blank">estructura vibratorial del ADN</a>.</p>

      <p>Esto lleva a definir la estructura del código ADN dentro de la <a href="<?=$Dir->libro?>factor_maya#_07-03-03-" target="_blank">matriz del Tzolkin</a> utilizando un arreglo ideado por Ben Franklin.</p>

      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/07-06.jpg' alt="El Tzolkin con el cuadrado mágico del 8 y cubicación del código ADN/I-ching">
      </figure>
      
      <p>En este modelo, cada kin se asocia con un Hexagrama del I-ching aplicados al movimiento del Rayo de Sincronización Galáctica que está representado por el Módulo Armónico - Tzolkin. De esta forma se relacionan los procesos históricos descriptos como ciclos morfogenéticos y los cambios vibracionales en la estructura del ADN.</p>
      
      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/07-07.jpg' alt="">
      </figure>

      <p>A esta zona también se le llama <a href="<?=$Dir->libro?>factor_maya#_07-03-" target="_blank">Zona de Cruce de Polaridad</a></p>

      <p><q>En otras palabras, así como la característica principal del ADN es un modelo de doble hélice, por el cual se crea un camino para el cruce de información de una corriente molecular a la otra, así el modelo del Telar Maya puede concebirse como el cruce de un lado a otro de la columna mística central de los dos flujos simétricos que comprenden el modelo de activación galáctica. El movimiento de este modelo de cuatro unidades a la derecha y a la izquierda de la columna central, es lo que define al campo simétrico de 64 unidades. El “tablero” de 64 unidades, es la matriz genética de la transformación que unifica a todas las 260 unidades del Tzolkin.</q></p>

      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/07-03.jpg' alt="Modelo de la Doble Hélice y Telar de los Mayas">
      </figure>

    </section>      

    <!-- Los 13 Campos de Energía -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En <cite>El Factor Maya</cite> se definen <a href="<?=$Dir->libro?>factor_maya#_07-03-01-" target="_blank">los <n>13</n> campos de Energía</a>. Doce de ellos contienen el código de información sobre el resplandor pre- y post- genético del desarrollo galáctico, y el decimotercero representa el código ADN.</p>

      <p>Estos son:</p>

      <ul>
        <li>La Columna Mística de 20 unidades en el centro del tzolkin.</li>
        <li>Un cuerpo de energía radiante de cuatro fases y 144 unidades.</li>
        <li>Un cuerpo simétrico y cristalino de ocho partes y 32 unidades.</li>
        <li>Un cuerpo de Energía Genética de dos partes y 64 unidades.</li>
      </ul>

      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/07-05.jpg' alt="Los 13 Campos de Energía">
      </figure>

      <p><q>Moldeando la fórmula pre- y post-tecnológico que define a la historia, el ADN es la matriz transformadora que mantiene unidas las fases primordial y sintética de la activación energética radiante y cristalina. Por su posición central en la matriz, la función del ADN es vitalizar todo el modelo de activación galáctica. Como fractal del conjunto galáctico y de la geometría del mismo ADN, la función de la tecnología/historia, es la de vitalizar igualmente los campos de energía radiante que definen la pre- y la post-historia.</q></p>

    </section>
    
    <!-- el ciclo galáctico -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>
    <section>
      <h3>El Ciclo Galáctico</h3>

      <p>Existe una relación entre la colocacion armónica y cromática en el Tzolkin.</p>

      <p>En <cite>Un Tratado sobre el Tiempo</cite> se amplía el concepto matemático de la cromática y se habla sobre el <a href="<?=$Dir->libro?>tratado_del_tiempo#_04-03-05-" target="_blank">poder de circulación</a>, referido como entonado, debido a que la primer y ultima posición de cada cromática tienen el mismo color. Este poder se aplica a la curva de información galáctica compuesta por las 5 células del tiempo, en tanto que las cromáticas incluyen una o dos de las celulas del tiempo, es decir, funcionan como un nexo entre las partes.</p>

      <p>Entonces, si las células del tiempo representan las partes de un proceso de información galáctica ( la comunicacion con el centro galáctico ), los elementos galácticos o cromáticas son como el combustible o la materia prima de ese sistema. De esta manera, surgen las siguientes combinaciones:</p>

      <ul>
        <li>La Entrada del Fuego Amarillo (o luz)</li>
        <li>El Almacén de la Sangre Roja</li>
        <li>El Proceso de la Sangre Roja</li>
        <li>El Proceso de la Verdad Blanca</li>
        <li>La Salida de la Verdad Blanca</li>
        <li>La Salida del Cielo Azul</li>
        <li>La Matriz del Cielo Azul</li>
        <li>La Matriz del Fuego Amarillo (o Luz)</li>
      </ul>

      <p>Podemos Aplicar este esquema de las 52 cromáticas del giro espectral a las 65 armónicas del giro galáctico y obtener un nombre y descripción más completas de cada cromática.</p>    

    </section>

  </section>  
  
  <!-- El Giro Espectral -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>El giro espectral es un ciclo de tiempo que surge por la Colocación Cromática de los Sellos Solares, es decir, comenzando a contar desde el Sol Amarillo.</p>

    <p>Cada <b class="ide">Cromática</b> es un ciclo de 5 kines.</p>

    <p>Una <b class="ide">Estación Galáctica</b> de 65 kines esta compuesta de 13 Cromáticas.</p>

    <p>Hay 52 cromáticas y 4 Estaciones Galácticas comprendidas en el Tzolkin de 260 kines.</p>

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define al Giro espectral como: <q>Castillo del tiempo de 52 cromáticas en donde cada Onda encantada es una estación galáctica de 65 Kin (trece cromáticas); las estaciones están codificadas por el color de la familia Terrestre Polar: estación de la Serpiente roja del Este, estación del Perro blanco del Norte, estación del Águila azul del Oeste y la estación del Sol amarillo del Sur; el propósito de los giros espectrales es entrelazar los giros galácticos con armónicas superiores de la quinta fuerza.</q></p>
    
    <!-- Estación Galáctica -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Una Estación Galáctica está compuesta por 13 cromáticas de 5 días codificadas en una onda encantada ( módulo de sincronización ), por lo que las 4 estaciones en su conjunto están codificadas por un Castillo Fractal de 52 posiciones que representa el tzolkin en su totalidad.</p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>

      <p>En <cite>El Factor Maya</cite> se considera el Tzolkin o Calendario Sagrado como un modelo del Fractal aplicado a nuestra ruta planetaria, y se lo divide en <a href="<?=$Dir->libro?>factor_maya#_04-04-03-01-" target="_blank">Las 4 Grandes Estaciones Galácticas</a> de 65 días que representan <q>la incesante descarga de energía galáctica en un modelo cíclico cuádruple.  Las cuádruples energías corresponden, entre otras cosas, a las cuatro direcciones</q>.</p>      

      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/04-11.jpg' alt="Rueda Interior de los Guardianes Direccionales y Evolutivos">
      </figure>

      <p>Cada Estación esta asociada con una de <a href="<?=$Dir->libro?>factor_maya#_04-04-02-06-" target="_blank">las 4 Etapas evolutivas de la Mente</a> representadas los sellos solares de la familia polar. Estos Sellos como guardianes direccionales están relacionados con la imagen del Quemador: <q>aquel ser primordial, intemporal que trae el fuego, el héroe de la visión y la luz venerado en todas partes con nombres diferentes como prometeico dador de cultura</q>.</p>

      <p>
        <q>Hay cuatro Quemadores correspondientes a las Cuatro Estaciones Evolutivas, que están regidas por los Cuatro Guardianes Evolutivos. 
        <br>Cada Estación Evolutiva se divide en cuatro etapas, tres de veinte días cada una, y una de cinco días, para un total de 65 días para cada Estación Evolutiva.
        <br>Así pues, hay cuatro días iniciáticos por Estación que son importantes para los Ciclos del Quemador.</q>
      </p>
    
      <figure style="max-width:25rem;">
        <img src='<?=$Dir->libro_ima?>factor_maya/04-12.jpg' alt="El Tzolkin con los Ciclos del Quemador">
      </figure>

      <p>Estas etapas se repiten por cada estación galáctica marcando los ciclos del quemador dentro de su propia <q>estructura mítica conmemorativa de las estaciones de luz</q>.</p>

      <?=Doc_Dat::lis('hol.kin_cro_ond',['atr'=>['ton','fac'],'opc'=>['cab_ocu']]);?>

      <p><q>Para una imagen del Quemador, podemos visualizar las Cuatro Estaciones Sagradas de las Cuatro Direcciones, cada una protegida por su Guardián.</q></p>

      <ul>
        <li><q>En la primera fase, el Quemador Toma el Fuego, el Guardián toma el conocimiento del fuego, de la Estación anterior a la nueva Estación. El número asociado con la primera fase es el 3, el rayo del ritmo y de la sinergia.</q></li>
        <li><q>En la Segunda Fase, el Quemador Inicia el Fuego, el conocimiento del fuego es aplicado realmente para iluminar la estación evolutiva en curso. El número asociado a esta fase es el 10, el rayo de la manifestación.</q></li>
        <li><q>En la tercera fase, el Quemador Corre con el Fuego, el Guardián toma el fuego y difunde su influencia. El número asociado a esta fase es el 4, el Rayo de la Medida y la extensión en las cuatro direcciones.</q></li>
        <li><q>Finalmente, en la cuarta fase, el Quemador arroja el fuego y concluye la influencia del fuego para la Etapa Evolutiva en curso. El número asociado con esta etapa es el 11, el Rayo de Disonancia.</q></li>
      </ul>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define a la Estación Galáctica como: <q>Espectro de 65 Kin, un cuarto de giro espectral codificado por los cuatro Kin polares: Serpiente roja, Perro blanco, Águila azul y sol amarillo.</q></p>

      <p>De esta manera, los kines de la familia Polar codifican el giro galáctico de 260 kines en 4 partes iguales llamadas <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">espectros o estaciones galácticas</a> de 65 kin cada una, recapitulando el poder transformador de los elementos raíz: Fuego Amarillo, Sangre Roja, Verdad Blanca y Cielo Azul.</p>      

      <?=Doc_Dat::lis('hol.kin_cro_est',['atr'=>['ide','sel','pos','nom','fac_des','enc_des']]);?>      

      <p>Cada Espectro Galáctico puede ser traspueto en una de las 4 ondas encantadas del castillo del destino, donde 1 kin iguala a una cromática de 5 kines.</p>

      <p>De esta manera, las cromáticas que comienzan con los tonos 3, 10, 4 y 11 corresponden a los pórticos y torres de cada onda encantada.</p>

      <?=Doc_Dat::lis('hol.kin_cro_ond',['atr'=>['ide','ton','cue','fac','enc']]);?>

      <p><q>El giro espectral comprime los 260 Kin del giro galáctico en 52 Kin: porque el comienzo del giro espectral 3 Serpiente, es discontinuo con el comienzo del giro galáctico, 1 Dragón, su matiz comprimido entrelaza un giro galáctico con el siguiente. El Kin polar, Perro blanco, es el agente espectral que cruza de un giro galáctico al otro.</q></p>      

      <p>Este ciclo da cuentas sobre la liberación visible del cuerpo Arco Iris de la Tierra, ya que desde el punto de vista del <b class="ide">Holon Planetario</b>, la familia terrestre polar, que lleva el poder del kin espectral, tiene sus posiciones en el polo norte del Holon Planetario donde se Recibe la Afluencia del Aliento Galáctico; mientras que la familia portal, custodiando el polo Sur, es la encargada de mantener la Expiración Solar. <q>La entonación del quinto acorde galáctico de Kinich Ahau está protegida por este entendimiento</q>.</p>

    </section>

    <!-- Elemento Cromático -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Una cromática es una secuencia de 5 kines siguiendo la colocación cromática de los sellos solares. Hay 13 Cromáticas en cada Estación Galáctica y 52 Cromáticas en total denro del Tzolkin.</p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define al Elemento Galáctico como: <q>El fuego amarillo, la sangre roja, la verdad blanca, y el celo azul,- la base de las cuatro estaciones galácticas y las cuatro cromáticas</q>; mientras que lo cromático se entiende como la <q>Cualidad del tiempo cuatri-dimensional como un Génesis que vuelve a circular continuamente; la secuencia de cinco Kin donde el primero y el quinto kin son del mismo color; dinamismo de las cuatro constantes de los colores que evocan y son movidos por el quinto matiz.</q></p>

      <p>De esta manera, las 4 cromáticas como elementos galácticos surgen desde la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-02-" target="_blank">Colocacion Cromática de Clanes</a> para los Sellos Solares.</p>

      <p><q>Dando vueltas desde el átomo del tiempo primordial cromático, la grandeza galáctica está articulada por 4 "estaciones". En su movimiento las 4 estaciones galácticas recapitulan la cosmología de los 4 elementos galácticos: el fuego, la sangre, la verdad y el cielo. Surgiendo desde los 4 elementos galácticos se encuentran los 4 clanes.</q></p>

      <p>Dado que el Giro Espectral codificado como un Castillo comienza con el kin 185, Serpiente Eléctrica Roja, el listado completo toma la siguiente forma:</p>

      <?=Doc_Dat::lis('hol.kin_cro_ele',['atr'=>['ide','nom','des','kin'],'tit_cic'=>['est'],'opc'=>['cab_ocu']]);?>

    </section>    

  </section>
  
  <!-- El Giro Galáctico -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>    

    <p>El Giro Galáctico es un ciclo de tiempo que surge por la Colocación Armónica de los Sellos Solares, es decir, comenzando a contar desde el Dragón Rojo.</p>

    <p>Cada <b class="ide">Armónica</b> o <b class="ide">Célula del Tiempo</b> es un ciclo de 4 kines.</p>

    <p>Una <b class="ide">Trayectoria Armónica</b> de 20 kines esta compuesta de 5 Armónicas.</p>

    <p>Hay 65 Armónicas y 13 Trayectorias comprendidas en el Tzolkin de 260 kines.</p>

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-02-" target="_blank">Glosario del Apendice I</a> del <cite>Encantamiento del Sueño</cite> se define al Giro Galáctico como: <q>la secuencia de 260 kin entrelaza los 365 días del año solar precisamente cada 52 años solares; proporción de giros con los años solares 7: 5; 7 giros galácticos = 5 años solares; 36,5 giros galácticos = 26 años solares, 73 giros galácticos 52 años solares.</q></p>

    <!-- Trayectoria Armónica -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Una Trayectoria Armónica está compuesta por 5 armónicas ( células del tiempo ) de 4 días. Las 13 trayectorias armónicas están codificadas por el módulo de sincronización de 13 tonos galácticos: La Onda Encantada, en donde cada posición equivale a una trayectoria de 20 kines.</p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>      

      <p>En <cite>El Factor Maya</cite> el <a href="<?=$Dir->libro?>factor_maya#_05-04-" target="_blank">Tiempo Galáctico</a> se Mide como un Rayo de Sincronización de 13 Baktunes que va desde los años 3.113 A.C. a 2.012 D.C..</p>

      <p>Cada Baktún está asociado a una trayectoria de 20 kines y representa un <a href="<?=$Dir->libro?>factor_maya#_05-06-" target="_blank">Modelo Morfgenético</a> en el desarrollo de la Historia Humana.</p>

      <?=Doc_Dat::lis('hol.kin_arm_tra',['atr'=>['ide','tit','fac','may'],'det_des'=>['lec']]);?>

      <p><a href="<?=$Dir->libro?>factor_maya#_05-06-03-" target="_blank">Los 20 Katunes</a> dan la estructura general de modelo orgánico al baktún como ciclos de casi 20 años representados uno de los 20 sellos solares ( glifos como capacitores resonantes ).</p>

      <?=Doc_Dat::lis('hol.sel',['atr'=>['ide','nom_may','arm_tra_des']]);?>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define a la Trayectoria Armónica como: <q>20 Sellos Solares en la rotación de las cinco células del tiempo desde el Dragón rojo al Sol amarillo; combinados con los trece tonos galácticos crean las trece trayectorias armónicas del Índice Armónico; sigue la Ley de Simetría Inversa estableciendo las seis series de trayectorias Espejo; 1 y 13, 2 y 12, 3 y 11, 4 y 10, 5 y 9, 6 y 8, mientras que la trayectoria 7 no tiene ningún Espejo</q>.</p>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-07-" target="_blank">Índice Armónico</a> figuran las 13 trayectorias como columnas del tzolkin y sirven para buscar referencias y relaciones ocultas entre los kines.</p>

      <p>Estas pueden ser codificadas dentro de una Onda Encantada para ampliar las lecturas y posicionamientos del kin.</p>

      <?=Doc_Dat::lis('hol.kin_arm_tra',['atr'=>['ide','nom','des']]);?>

    </section>

    <!-- Célula del Tiempo -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Una Armónica ( o Célula del Tiempo ) es una secuencia de 4 kines siguiendo la colocación armónica de los sellos solares. Hay 5 Células del Tiempo en cada Trayectoria Armónica y 65 Células en total denro del Tzolkin.</p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>

      <p>En <cite>El Factor Maya</cite> estas células pueden ser asociadas a las <a href="<?=$Dir->libro?>factor_maya#_04-04-02-05-" target="_blank">familias cíclicas</a> de la Luz. Desde esta visión se considera a los 20 signos sagrados como 5 grupos de <b>Rudas Radiales</b> girando en sentdio contrario a las manecillas del reloj. Estas ruedas con 4 brazos se mueven en espiral interactuando entre ellas de forma armónica. En su constante movimiento estas ruedas describen el ciclo de la información solar desde que se recibe la luz y se hace forma, hasta llegar a la adquisisión de la <b>mente luminosa</b>.</p>

      <p>En <cite>El Encantamiento del Sueño</cite> las 65 Células codifican las Armónicas en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_04-" target="_blank">Libro del Kin</a> y en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-07-" target="_blank">Índice Armónico</a>. Su composición está definida por la Colocación Armónica de los Sellos Solares. En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">Glosario</a> se define como: <q>Una de las cinco unidades creadas por el poder de la quinta fuerza codificando las cuatro razas cósmicas raíz; base de auto-circulación de la Nave del Tiempo; combinada con los trece tonos galácticos crea la base de las 65 armónicas del Índice Armónico y el Libro del Kin.</q></p>

      <p>En <cite>Un Tratado del Tiempo</cite> se amplía este concepto estableciendo la Trayectoria Armónica como una <a href="<?=$Dir->libro?>tratado_del_tiempo#_04-03-04-" target="_blank"><q>Curva de Información Galáctica</q></a> que es desarrollada como un proceso sistémico por las 5 células del tiempo ( Entrada, Almacén, Proceso, Salida y Matriz ). Al relacionarse con las 52 cromáticas estas obtienen el <a href="<?=$Dir->libro?>tratado_del_tiempo#_04-03-05-" target="_blank">poder de circulación</a> dando cuenta del ciclo galáctico expresado en los elementos galácticos del giro espectral.</p>

      <p>Por otro lado, cada célula, excepto la 33, esta relacionada con un hexagrama del I-ching. Esto fué presentado en <cite>Las 20 Tablas del Tiempo</cite> como el <a href="<?=$Dir->libro?>tablas_del_tiempo#_06-04-" target="_blank">I-ching galáctico</a>.</p>

      <?=Doc_Dat::lis('hol.kin_arm_cel',['atr'=>['ide','chi','nom','des'],'tit_cic'=>['tra'],'opc'=>['cab_ocu']]);?>

    </section>      

  </section>
  
  <!-- La Nave del Tiempo -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>La Nave del Tiempo es un ciclo de tiempo que surge por la Colocación de Tonos, es decir, comenzando a contar desde el tono 1 al 13, donde empieza el próximo ciclo de 13.</p>

    <p>Cada <b class="ide">Onda Encantada</b> es un ciclo de 13 kines.</p>

    <p>Un <b class="ide">Castillo Direccional</b> de 52 kines esta compuesta de 4 Ondas Encantadas.</p>

    <p>Hay 20 Ondas Encantadas y 5 Castillos Direccionales comprendidos en el Tzolkin de 260 kines.</p>

    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-02-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define a la Nave del Tiempo Tierra como: <q>Vehículo de transporte interdimensional; giro de 26.000 años consistiendo en cinco Castillos codificados por 20 Ondas Encantadas y 260 umbrales galácticos con el propósito de estabilizar el planeta Tierra y el sistema estelar Kinich Ahau.</q> y más adelante, en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-02-" target="_blank">Apéndice I</a> como: <q>El planeta Tierra en su órbita, creando la cuarta dimensión que encierra en sí la tercera.</q></p>

    <p>Este modelo es aplicable en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-10-" target="_blank">tablero del viaje</a> donde los 5 castillos pueden codificar a distintas escalas de tiempo, es decir: la <b>Nave del Tiempo</b> pueden representar un ciclo de 26.000 años donde cada kin equivale a 100 años; o el ciclo de 5.200 años del rayo de sincronizacion galáctica donde cada kin equivale a 20 años; o bien, puede representar un giro galáctico de 260 días donde cada kin se corresponde a 1 día.</p>

    <!-- Castillo Direccional/Fractal -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Un Castillo está compuesta por 4 ondas Encantadas de 13 kines. Los 5 castillos en su totalidad se corresponden a los 260 kines del giro galáctico.</p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-02-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define al Castillo como: <q>Estructura del tiempo cuatri-dimensional, cuatro Ondas Encantadas de 52 Kin: base estructural de la Nave Tiempo Tierra 2013.</q>; mientras que un Castillo del Destino es <q>Cuatro Ondas Encantadas codificadas por el color, la estructura del tiempo galáctico de 52 Kin, la base para trazar varias equivalencias del Kin, incluyendo el sendero del destino de la vida de 52 años, el año de 52 semanas, etc...</q></p>

      <p>Esto quiere decir que cada Castillo es un componente o parte de la Nave del Tiempo, y puede representar distintos ciclos.</p>

      <p>Luego, en el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-06-" target="_blank">índice del Génesis de los Castillos</a> se aplica este ciclo a los 26.000 años que duró el Encantamiento del Sueño codificados en un tablero que contiene las 5 células de la memoria del génesis, dos castillos por cada una.</p>

      <p><q>Para restaurar y mantener la naturaleza radial del tiempo solar-planetario, los Génesis del Encantamiento del Sueño y los 5 castillos de la Nave del Tiempo, están organizados en 5 células memoria de génesis. Dentro de cada una de las 5 células de la memoria, las 20 tribus están organizadas de acuerdo con los pares antípodas.</q></p>
      
      <?=Doc_Dat::lis('hol.kin_nav_cas',['atr'=>['ide','nom','des']]);?>

    </section>

    <!-- Onda Encantada -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->key}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Una Onda Encantada ( o <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-02-" target="_blank">Forma de Onda</a> del Holon ) es una secuencia de 13 kines siguiendo la colocación de los 13 Tonos Galácticos. Hay 4 Ondas por cada Castillo y 20 Ondas en total denro del Tzolkin.</p>

      <figure style='max-width:35rem;'>
        <img src='<?="{$Dir->art_ima}{$nv1}-{$nv2}"?>.png'>
      </figure>      

      <p>En <cite>El Factor Maya</cite> los <a href="<?=$Dir->libro?>factor_maya#_05-07-" target="_blank">Ciclos Ahau</a> codifican secuencias de 13 Katunes ( 20 años ) que <q>proporcionan una segunda capa de un modelo de onda armónica de sincronización galáctica, cada uno de 256 años de duración</q>. Al ser ciclos mas cortos que los baktun, de 20 katunes y 400 tun, estos representan una impresión galáctica superior.</p>

      <p><q>De este modo, mientras que pueden verse los trece ciclos baktún como una onda que recoge las siete montañas y los seis valles, se pueden imaginar los veinte ciclos AHAU como la espiral del ADN planetario, girando veinte veces en una dirección que corre paralela a los ciclos Baktún, y actuando en forma recíproca con ellos, pero desde una fuente que está por encima de la forma de onda del baktún. Además de llevar su propia y diferente cualidad morfo-galáctica, los ciclos AHAU también dan cuenta del transporte de la información morfogenética desde un baktún hasta el siguiente.</q></p>      

      <p>El siguiente listado con los 20 ciclos Ahau se presentan las <a href="<?=$Dir->libro?>factor_maya#_05-07-01-" target="_blank">descripciones mítico-poéticas</a> que explican el simple y más amplio <b>movimiento del cuerpo de luz planetario</b>.</p>

      <p>En este contexto se define al <a href="<?=$Dir->libro?>factor_maya#_05-07-02-" target="_blank">Cuerpo de Luz</a> como: <q>la infraestructura vibratoria impresa por el código galáctico de 260 unidades, actúa en todos los niveles, ya sea en el de un planeta, una especie, o un organismo individual, en la evolución de un planeta situado dentro de un mayor sistema estelar, es de gran significación cuando este cuerpo de luz alcanza un nivel de brillantez consciente.</q></p>

      <?=Doc_Dat::lis('hol.kin_nav_ond',['atr'=>['ide','fac','fac_des'],'opc'=>['cab_ocu']]);?>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_05-02-" target="_blank">Glosario</a> del <cite>Encantamiento del Sueño</cite> se define a la onda encantada como: <q>Plantilla del tiempo y módulo de sincronización basado en trece tonos galácticos</q>. Este <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-10-" target="_blank">Módulo de Sincronización</a> se utiliza para codificar los distintos ciclos de tiempo: Galáctico-Solar-Lunar.</p>

      <p>Para este caso el módulo se aplica dentro de los Castillos que codifican las distintas misiones durante los 26.000 años del Encantamiento del Sueño cuyo fin es el de regenerar el planeta por una nave del tiempo que restaure y mantenga la naturaleza radial del tiempo solar-planetario.</p>

      <p><q>Al sincronizar las 13 Lunas del planeta Tierra de acuerdo con los 13 tonos galácticos, las 4 razas cósmicas raíz y las 20 tribus siembran las 20 Ondas Encantadas de la Nave del Tiempo 2.013. Codificadas por el poder de la quinta fuerza, las 20 Ondas Encantadas se mueven a través del tiempo como los 5 castillos de la Nave del Tiempo 2.013.</q></p>

      <p>Cada Onda Encantada está codificada por el sello de su tono magnético, por lo que la onda toma el nombre de este. Es decir, la onda del Dragón Rojo es aquella que empieza con el Dragón. La onda del Viento es aquella cuyo tono magnético esta codificado por el Viento. De esta forma las ondas encantadas cambian cada 13 sellos solares.</p>

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_01-" target="_blank">Génesis del Encantamiento del Sueño</a> se encuentra una referencia narrativa sobre cada onda encantada y cada castillo durante este período, y la trama sobre la guerra del tiempo que se desata al entrar en la onda encantada de la luna en el castillo verde del centro.</p>

      <?=Doc_Dat::lis('hol.kin_nav_ond',['atr'=>['ide','nom','des'],'tit_cic'=>['nav_cas'],'opc'=>['cab_ocu']]);?>

    </section>      

  </section>  

</article>  