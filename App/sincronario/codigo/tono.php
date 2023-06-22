<!--13 Tonos Galácticos-->
<article>  
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header> 

  <!--Rayos de pulsación-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>el Factor Maya</cite> se introduce el concepto de <a href="<?=$Dir->libro?>factor_maya#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n><c>,</c> en dos series de ciclos constantes con sentido inverso<c>,</c> para describir <b>comentes de energía</b> provenientes del centro de la galaxia <b class="ide" title="Dador de Movimiento y Medida">Hunab Ku</b>.</p>

    <p>Luego<c>,</c> los <n>13</n> números se definen como <a href="<?=$Dir->libro?>factor_maya#_04-04-01-01-" target="_blank">Rayos de Pulsación</a> dotados con una <q>función radio<c>-</c>resonante en particular que pulsa e irradia simultáneamente</q>. Esta progresión describe <q>la naturaleza formal que fundamenta la apariencia de las cosas</q> y <q>dan alguna idea de la progresión del ciclo estructural en el que se fundamenta la operación de la galaxia</q>.</p>

    <?=Doc_Dat::lis('hol.ton',['atr'=>['ide','gal'],'opc'=>['cab_ocu']])?>

    <!--Simetría especular-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <cite>Factor Maya</cite> se establecen los <a href="<?=$Dir->libro?>factor_maya#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición de la columna <n>7</n> en el Módulo Armónico o Tzolkin. Luego<c>,</c> se describen sus relaciones recíprocas aplicando el concepto de los <a href="<?=$Dir->libro?>factor_maya#_04-04-01-02-" target="_blank">rayos de pulsación</a>.</p>

      <?=Doc_Dat::lis('hol.ton_sim',['atr'=>['ide','nom','ton']])?>
      
    </section>      

  </section>
    
  
  <!--Principios de la creacion-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>    

    <p>En <cite>el Encantamiento del Sueño</cite> se definenen los <n>13</n> números como la <q>cosmología de movimiento</q> representada por los <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-11-" target="_blank">tonos galácticos de la creación</a>.</p>

    <p><q>Los <b class="ide">13 tonos galácticos de la creación</b> unidos forman la cosmología del movimiento. La cosmología del movimiento es llamada Onda Encantada</q>.</p>    

    <?=Doc_Dat::lis('hol.ton',['atr'=>['ide','nom','des','des_acc']])?>

    <p>Entonces, estos 13 principios codifican la <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-10-" target="_blank">onda encantdada</a> como <q>el módulo del tiempo galáctico a través del cual se mueven las <n>20</n> tribus</q> en el Encantamiento del Sueño.</p>

    <p>Posteriormente, en <cite>Un Tratado del Tiempo</cite>, se profundiza el concepto de Onda Encantada como una <a href="<?=$Dir->libro?>tratado_del_tiempo#_04-03-02-" target="_blank">unidad fractal tipo de medida</a>:</p>

    <p><q>En las palabras onda encantada, onda se refiere al poder del movimiento, encantada al poder que uno puede ganar estando en armonía con la realidad. Por eso, conocer y cabalgar una onda encantada es demostrar poder autónomo aumentado mediante la identificación armónica con el tiempo cuatri-dimensional</q>.</p>

    <!--Pulsar Dimensional-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>el Encantamiento del Sueño</cite> se define el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-13-" target="_blank">Código Púlsar</a> como una función para <q>la integración del campo del tiempo de la Onda Encantada</q>.</p>

      <p>Los <b>Pulsares Dimensionales</b> están codificados por el código armónico de color y muestran la unidad de las dimensiones permitiendo crear relaciones a través del tiempo.</p>

      <p>En <cite>Las <n>13</n> Lunas en Movimiento</cite> se amplía la definición de los<a href="<?=$Dir->libro?>lunas_en_movimiento#_02-04-" target="_blank">Pulsares</a> como la Estructura o <q>Esqueleto</q> de la <b>Onda Encantada</b><c>,</c> permitiendo proyectar y construir una onda encantada del Servicio Planetario para Sincronizar las metas y acciones del Kin Planetario en cada Anillo Solar.</p>

      <p>En <cite>Un Tratado del Tiempo</cite> se profundiza con los <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-03-" target="_blank">Campos de Aplicación</a> y se describen sus funciones dentro de las <b>Articulaciones de la Onda Encantada</b>.</p>

      <?=Doc_Dat::lis('hol.ton_dim',['atr'=>['ide','nom','des_dim','des_cam','des_fun','ton']])?>

    </section>

    <!--Pulsar Matiz-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>el Encantamiento del Sueño</cite> se define el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-13-" target="_blank">Púlsar Entonado</a> como una función del <b>Quinto Matiz</b> representado por el código <b>Punto<c>-</c>Raya</b>. Este código está asociado a la aparición de las <b>Familias Terrestres</b> en la <b>Onda Encantada</b>.</p>

      <p>En <cite>Las <n>13</n> Lunas en Movimiento</cite> se amplía la definición de los <a href="<?=$Dir->libro?>lunas_en_movimiento#_02-04-" target="_blank">Pulsares Entonados</a> como proveedores de los poderes de <q>locomoción</q> de la <b>Onda Encantada</b><c>,</c> relacionando y unificando las distintas dimensiones que la componen.</p>

      <p>En <cite>Un Tratado del Tiempo</cite> se profundiza en ellos como <a href="<?=$Dir->libro?>dinamicas_del_tiempo#_04-03-03-" target="_blank">Estructuras de Tiempo</a> que atraviesan las Dimensiones correspondientes a los <b>Pulsares Dimensionales</b><c>,</c> los cuales definen a los distintos reinos sincrónicamente interconectados.</p>

      <?=Doc_Dat::lis('hol.ton_mat',['atr'=>['ide','nom','des','ton']])?>
      
    </section>      

  </section>
  
  <!--Onda Encantada de la Aventura-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>    
    
    <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">glosario</a> del <cite>Encantamiento del Sueño</cite> se define a la Onda Encantada como: <q>Plantilla del tiempo y Módulo de Sincronización basado en trece tonos galácticos</q>.</p>    

    <p>Como <q>Plantilla de Tiempo</q> puede ser utilizada para <q><u>planear acciones durante cualquier ciclo de tiempo</u> operando por medio de la Onda Encantada<c>:</c> <n>13</n> días<c>,</c> <n>13</n> Lunas<c>,</c> <n>13</n> años<c>,</c> etc<c>...</c></q>.</p>

    <p>Como <q>Módulo de Sincronización</q> cada posición está cargada con un determinado <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente. Estos mandatos otorgan un <b>código de aventura</b> para el Kin Planetario y <q>toman la forma del movimiento a través de pórticos<c>,</c> torres y cámaras</q>.</p>

    <?=Doc_Dat::lis('hol.ton',['atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'],'tit_cic'=>['ond'],'opc'=>['cab_ocu']])?>

    <!-- Posiciones del Castillo -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">    
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-05-" target="_blank">glosario</a> del <cite>Encantamiento del Sueño</cite> se define al Castillo del Destino como: <q>Cuatro Ondas Encantadas codificadas por el color, la estructura del tiempo galáctico de 52 Kin, la base para trazar varias equivalencias del Kin, incluyendo el sendero del destino de la vida de 52 años, el año de 52 semanas, etc</q>.</p>

      <p>El <a href="<?=$Dir->libro?>encantamiento_del_sueño#_02-03-08-" target="_blank">Castillo del Destino</a> <q>demuestra el poder de la Magia del Tiempo</q>, por el cual el valor de la unidad del kin puede variar y el tono puede cambiar de posición.</p>

      <p>De esta forma surgen distintos tipos de Castillos que son utilizados para medir los distintos ciclos de tiempo:</p>

      <ul>
        <li><i>El Castillo de la Nave</i>: está compuesto por 52 kines de los 260 que tiene el Giro Galáctico y cada posición puede representar 1 día, 20 años o 100 años.</li>
        <li><i>El Castillo del Giro Espectral</i>: compuesto por las 52 cromáticas del Giro Espectral, cada posicion corresponde a 5 kines.</li>
        <li><i>El Castillo del Giro Solar</i>: compuesto por las 52 héptadas del anilllo solar, cada posición corresponde a 7 días.</li>
        <li><i>El Castillo del Destino</i>: codificado por los kines de una misma Familia Terrestre, cada posición corresponde a un año.</li>
        <li><i>El Castillo de Nuevo Sirio</i>: codificado por los kines de la Familia Portal, cada posición se corresponde con un Anillo Solar del Sincronario.</li>
      </ul>

      <p>Para todos ellos, las 52 posiciones del Castillo del Destino están codificadas por un Color y un Tono Galáctico, mientras que las 4 Ondas Encantadas que lo componenen se codifican por el código de color.</p>

      <p>Por otro lado, a cada posición se le asocia un Precepto, el cual es utilizado en las Meditaciones Diarias del Orden Cíclico y Sincrónico del Sincronario Diario:</p>

      <?=Doc_Dat::lis('hol.cas',['atr'=>['ide','pos','nom','des'],'tit_cic'=>['arm'],'det_des'=>['lec'],'opc'=>['cab_ocu']])?>

    </section>

  </section>

  <!-- El Holon Humano -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>
    
    <!-- Las 13 Articulaciones Principales -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">    
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En el <cite>Factor Maya</cite> se se dice que las 13 Columnas del Tzolkin representan las <a href="<?=$Dir->libro?>factor_maya#_08-03-01-" target="_blank">13 Articulaciones Principales del cuerpo Humano</a>, <q>que dividen también a los principales canales nerviosos que van desde los pies y las manos hasta el cerebro, unidas y mediadas por la columna central</q>.</p>

      <figure style='max-width:30rem;'>
        <img src="<?=$Dir->libro_ima?>factor_maya/08-03.jpg" alt="">
      </figure>

      <p>Posteriormente, en otras aplicaciones del sincronario, los 13 tonos galácticos, que forman la cosmología del movimiento, han sigo asignados a las mismas articulaciones dentro del Holon Humano. Esta asignación se corresponde con la Simetría Especular de los Tonos Galácticos como rayo de pulsación, donde el tono 7 resonante al ser central se correlaciona consigo mismo.</p>

      <?=Doc_Dat::lis('hol.hum_art',['atr'=>['nom','ton'],'opc'=>['cab_ocu']])?>

    </section>    

    <!-- Los 7 sentidos -->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">    
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <cite>Factor Maya</cite> también se representan los <a href="<?=$Dir->libro?>factor_maya#_08-03-02-" target="_blank">Órganos de los Sentidos</a> a través de las 13 Columnas del Tzolkin.</p>

      <p><q>En la mitad está el canal central, la gran mente abriéndose al universo vasto, fluido y abierto. A lado y lado van las columnas que representan la mente local, y los sentidos de la vista, oído, olor, sabor y finalmente el tacto. Agrupados a lo largo de los lados del canal central, y representados por las diez unidades del Telar Maya, están los radares neuro-cerebrales, que son los receptores de los órganos sensitivos.</q></p>      

      <figure style='max-width:30rem;'>
        <img src="<?=$Dir->libro_ima?>factor_maya/08-04.jpg" alt="">
      </figure>

      <p>Estos sentidos son informados por el campo electromagnético activado por el sol, ya que el <a href="<?=$Dir->libro?>factor_maya#_08-02-" target="_blank">ser Humano como una batería electromagnética</a> hace contacto con las baterías electromagnéticas solares y planetarias a través de ellos.</p>

      <?=Doc_Dat::lis('hol.hum_sen',['atr'=>['nom','ton'],'opc'=>['cab_ocu']])?>

    </section>    

  </section>

</article>