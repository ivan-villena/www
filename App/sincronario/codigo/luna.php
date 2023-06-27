<!--365 psi<c>-</c>cronos del Giro Solar-->
<article>
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header>

  <!--El Servicio Planetario de 13 Lunas-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>
    
    <p>El giro lunar corresponde a cada uno de los <n>13</n> ciclos lunares de <n>28</n> días dentro del Anillo Solar para el Sincronario<c>.</c></p>

    <p>Cada giro lunar se compone de <n>28</n> días en total que son divididos en <n>4</n> heptadas de <n>7</n> días siguiendo el código de color armónico<c>.</c></p>

    <p>En el Servicio Planetario del Sincronario<c>,</c> cada Luna puede ser codificada por un Kin según el Anillo Solar en el que se encuentre<c>.</c></p>        

    <!--Héptadas Semanales-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Según <a href="<?=$Dir->libro?>lunas_en_movimiento#_02-07-01-" target="_blank">la Naturaleza del tiempo</a> de <cite>Las <n>13</n> lunas en movimiento</cite><c>:</c></p>

      <p><q>Cada luna de <n>28</n> días es un conjunto perfecto de cuatro semanas/heptadas de <n>7</n> días<c>.</c> Cada conjunto de cuatro semanas del calendario de <n>13</n> lunas es una "armónica aumentada"<c>.</c> La armónica es una unidad de cuatro kin<c>,</c> la unidad más pequeña agregada del giro galáctico de <n>260</n> Kin<c>.</c></q></p>      

      <p>Cada <a href="<?=$Dir->libro?>lunas_en_movimiento#_02-07-04-" target="_blank">incremento armónico</a> se corresponde a una función de código de color<c>:</c></p>

      <?=Doc_Dat::lis('hol.lun_arm',['atr'=>['ide','nom','des_col','dia','des_pod' ],'opc'=>['cab_ocu']]);?>

      <p>Los <a href="<?=$Dir->libro?>lunas_en_movimiento#_02-07-03-" target="_blank">7 días de la héptada</a> están codificados por los <n>7</n> plasmas radiales que van de Dali a Silio<c>.</c></p>

      <div class="-ren jus-cen">
        <?php
          foreach( Dat::_('hol.rad') as $Rad ){echo Doc_Val::ima('hol','rad',$Rad,['class'=>"mar_hor-2"]);}
        ?>
      </div>      

    </section>    

  </section>

  <!--Tubo por el que habla el Espíritu de la Tierra-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En la <a href="<?=$Dir->libro?>telektonon#_01-08-" target="_blank">Introducción</a> al <cite>Telektonon</cite> se define al Tubo por el que Habla el Espíritu de la Tierra como el nombre dado al 3° circuito de telepatía<c>,</c> entre la Tierra y Urano<c>,</c> el cual consta de <n>28</n> unidades coordinadas o días correspondientes a los días de cada luna para el Anillo Solar Anual<c>.</c></p>

    <p><q>Juntas<c>,</c> la 3° y la 8° órbita<c>,</c> teniendo ambas el centro de los campos atómico y telepático<c>,</c> constituyen el circuito del Telektonon alcanzando la trascendencia universal<c>:</c> el poder del Cielo en la Tierra<c>,</c> el florecimiento de la consciencia solar<c>-</c>galáctica<c>.</c></q></p>

    <p>Este ciclo de <n>28</n> días es registrado por la biosfera de la tierra activando las coordenadas del circuito del telektonon<c>:</c></p>

    <p><q>Como la frecuencia colectiva de sincronización de la raza<c>,</c> el ciclo de <n>28</n> días es el instrumento mental para producir la telepatía universal entre las especies<c>.</c> Sin la aceptación consciente y colectiva del ciclo de <n>28</n> días<c>,</c> la especie humana vacilará en su voluntad y espíritu colectivos<c>.</c> Más que alcanzar la transición de la Biosfera a la Noosfera<c>,</c> los humanos terminarán su cielo evolutivo en una catástrofe biosférica<c>.</c></q></p>

    <figure style='max-width:40rem;'>
      <img src="<?=$Dir->libro_ima?>telektonon/01-08.png" alt="">
    </figure>    
    
    <!--Las Líneas de Fuerza Verticlaes-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En el <a href="<?=$Dir->libro?>telektonon#_02-03-01-" target="_blank">1° nivel del Juego</a> se recorren los <n>28</n> días de cada luna posicionando las <n>3</n> tortugas que representan las figuras de Pacal Votan<c>,</c> Bolon Ik<c>,</c> y el Guerrero que se aventura en el Cubo de la Ley<c>.</c></p>

      <?=Doc_Dat::lis('hol.lun_tel_fas',['atr'=>[ 'lun','nom' ],'opc'=>['cab_ocu']]);?>

      <figure style='max-width:40rem;'>
        <img src="<?=$Dir->libro_ima?>telektonon/02-03-01-01.png" alt="">
      </figure>

      <p>Estas posiciones diarias definene las <a href="<?=$Dir->libro?>telektonon#_02-03-05-01-" target="_blank">Líneas de Fuerza Verticales</a> del Tablero<c>:</c></p>

      <?=Doc_Dat::lis('hol.lun_tel_fue',['atr'=>[ 'nom','des' ],'opc'=>['cab_ocu']]);?>

      <figure style='max-width:40rem;'>
        <img src="<?=$Dir->libro_ima?>telektonon/02-03-05-01.png" alt="">
      </figure>       

    </section>

    <!--Las Cartas del Cronomero Diario Universal-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En el <a href="<?=$Dir->libro?>telektonon#_02-03-05-01-" target="_blank">Libro Telepático de la Redención de los Planetas Perdidos</a> se presentan las cartas correspondientes a los <n>28</n> días de este ciclo<c>.</c></p>

      <?=Sincronario::dat_fic('telektonon-cartas',['ide'=>28 ])?>

      <p>Posteriormente se utilizan en la <a href="<?=$Dir->libro?>telektonon#_02-05-03-03-" target="_blank">Posición Corona</a> y <a href="<?=$Dir->libro?>telektonon#_02-05-03-04-" target="_blank">Posición Torre</a> del <a href="<?=$Dir->libro?>telektonon#_02-05-03-" target="_blank">Esquema Diario de las Cartas</a> correspondiente al <a href="<?=$Dir->libro?>telektonon#_02-05-01-" target="_blank">Cronómetro Diario Universal del mismo juego</a><c>.</c></p>

      <figure style='max-width:40rem;'>
        <img src="<?=$Dir->libro_ima?>telektonon/02-05-01.png" alt="">
      </figure>      

    </section>
    
    <!--Los 16 días por el Cubo del Guerrero-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>En el <cite>Telektonon</cite> <q>la recuperación del tiempo perdido y del poder se consigue durante los días <n>7</n> al <n>22</n> de cada luna de <n>28</n> días</q><c>.</c></p>

      <p>El periodo de <n>16</n> días de recuperación telepática del Cubo de la Ley es conocido como el <a href="<?=$Dir->libro?>telektonon#_01-03-" target="_blank">Viaje en el Laberinto del Guerrero</a><c>:</c> <q>El tablero de juego muestra gráficamente las <n>16</n> posiciones del Viaje de la Inteligencia del Guerrero<c>,</c> así como el olvidado Cubo de la Ley ocultado por la Torre de Babel<c>.</c> Es durante este período de <n>16</n> días que la mente colectiva<c>,</c> la voluntad y el espíritu de todos los que juegan el Telektonon está simultáneamente sincronizado e intensificado en una fuerza telepática común<c>.</c></q></p>

      <p>Estas posiciones también puede represetar <q>la Marcha Sagrada de la Victoria de los primeros 5 años de profecía</q><c>.</c> En este caso<c>:</c> <q>Los <n>4</n> cuartos de cada uno de los primeros <n>4</n> años de la Marcha Sagrada de la Victoria constituyen las <n>16</n> posiciones del Cubo del Guerrero<c>.</c> La victoria del <n>5</n><c>°</c> año de Profecía hace surgir al cubo desde su matriz bidimensional a su forma holográfica tridimensional completa<c>.</c></q></p>

      <figure style='max-width:40rem;'>
        <img src="<?=$Dir->libro_ima?>telektonon/01-03.png" alt="">
      </figure>

      <p>Las lecturas para cada posición<c>,</c> correspondiente a los días lunares <n>7 <c>-</c> 22</n><c>,</c> pueden verse en las cartas del <a href="<?=$Dir->libro?>telektonon#_02-05-02-04-" target="_blank">Libro Telepático de la Redención de los Planetas Perdidos</a><c>:</c></p>      

      <?=Doc_Dat::lis('hol.tel_cub',['atr'=>['lun','ide','nom','des'],'det_des'=>['afi'],'opc'=>['cab_ocu']]);?>

      <p>Posteriormente<c>,</c> en <a href="<?=$Dir->libro?>proyecto_rinri#_02-04-03-" target="_blank">Las <n>16</n> Posiciones del Cubo y los <n>208</n> Pasos a la Torre del Mago</a> del <cite>Proyecto Rinri</cite> se definen las correlaciones entre el psi<c>-</c>crono<c>,</c> los portales de activación galáctica del Telar Maya<c>,</c> y el Viaje del Cubo<c>.</c></p>

      <p>Para cada giro lunar de <n>28</n> días hay <n>20</n> uniades psi<c>-</c>crono<c>:</c> <n>4</n> son <abbr title="Portal de Activación Galáctica">PAG</abbr> que valen por <n>3</n> días cada uno <c>(</c> <n>12</n> días en total <c>)</c><c>;</c> y los otros <n>16</n> corresponde al Cubo del Guerrero<c>.</c></p>

      <p>Los <n>4</n> portales se ubican al comienzo y al final de cada ciclo lunar<c>,</c> los dias<c>:</c> <n>1</n><c>,</c> <n>2</n> y <n>3</n> corresponden al primer portal<c>;</c> los días <n>4</n><c>,</c> <n>5</n> y <n>6</n> corresponden al segundo<c>.</c> Luego<c>,</c> los días <n>23</n><c>,</c> <n>24</n> y <n>25</n> corresponden al tercer PAG<c>;</c> y los días <n>26</n><c>,</c> <n>27</n> y <n>28</n> al cuarto y último de los portales.</p>

      <p>Los <n>16</n> días que están entre los <n>6</n> días <b class="ide">PAG</b> de cada lado de la luna son los correspondientes al <a href="<?=$Dir->libro?>proyecto_rinri#_02-04-04-" target="_blank">Viaje en el Cubo del Guerrero</a><c>:</c> <q> Es durante cada uno de los <n>16</n> días por Luna <c>(</c> días <n>7</n> al <n>22</n> de la Luna <c>)</c> que la visualización Telepática Magnética efectivamente ocurre<c>.</c> Es durante estos <n>16</n> días que puedes estar mandando o recibiendo (dependiendo de cuál Hemisferio y cuál mitad del año sea) la imagen del Magneto Invisible girando en el centro de la Tierra</q><c>.</c></p>

      <figure style='max-width:35rem;'>
        <img src="<?=$Dir->libro_ima?>proyecto_rinri/02-04-03.jpg" alt="">
      </figure>

      <p>Posteriormente, en el apartado dedicado al <a href="<?=$Dir->libro?>proyecto_rinri#_02-04-03-" target="_blank">Viaje del Guerrero por el Cubo de la Ley</a> se presentan las afirmaciones diarias para poder <q>practicar despertar tus poderes telepáticos de inteligencia y profecía</q><c>.</c></p>

      <?=Doc_Dat::lis('hol.tel_cub',['atr'=>['lun','ide','nom','tit'],'det_des'=>['lec'],'opc'=>['cab_ocu']]);?>      

    </section>    

  </section>
  
  <!--Revelación del 7:7::7:7-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>  

    <p>En el <cite>Átomo del Tiempo</cite><c>:</c> <q>El <n>7<c>:</c>7<c>::</c>7<c>:</c>7</n> se practica mediante el uso de las <n>28</n> <c>(</c><n>7</n> x <n>4</n> ó <n>4</n> x <n>7</n>) Cartas del Plasma y el Tablero del Plasma de Compresión Fractal del Tiempo que monitorea la secuencia de <n>28</n> días para la activación de los siete plasmas Radiales</q><c>.</c></p>

    <?=Sincronario::dat_fic('atomo_del_tiempo-cartas')?>

    <p>En <a href="<?=$Dir->libro?>atomo_del_tiempo#_02-" target="_blank">las Prácticas</a> del juego se muestras los posicionamientos semanales para las cartas del juego que representan cada día del Ciclo Lunar<c>.</c></p>      

    <figure style='max-width:40rem;'>
      <img src="<?=$Dir->libro_ima?>atomo_del_tiempo/_02-03-01.gif" alt="">
    </figure>        

    <!--Átomo Telepático del Tiempo-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Cada semana se construye un <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-02-" target="_blank">Átomo telepático del Tiempo</a><c>.</c> Al completar cada luna resulta en la creación del Octaedro de Cristal en el centro de la Tierra<c>.</c></p>

      <?=Doc_Dat::lis('hol.lun_pla_ato',['atr'=>['ide','nom'],'opc'=>['cab_ocu']]);?>

      <p><q>En el Octaedro de Cristal<c>,</c> el Átomo Telepático del Tiempo del Análogo y el del Oculto son visualizados en el vértice biopsíquico polar<c>,</c> el del Análogo girando en el sentido de las agujas del reloj<c>,</c> el del Oculto en sentido inverso<c>,</c> mientras que el Átomo Telepático del Tiempo del Antípoda y el del Campo Unificado mantienen el plano gravitacional ecuatorial girando en la misma dirección en que la Tierra rota alrededor del sol<c>.</c> Cuando esta visualización se haya completado<c>,</c> sitúala en el corazón y disuélvela en ondas de compasión y amor universales<c>.</c></q></p>

      <figure style='max-width:40rem;'>
        <img src="<?=$Dir->libro_ima?>atomo_del_tiempo/_02-03-04.gif" alt="">
      </figure>         

      <p>Para su construcción<c>,</c> cada semana se realiza un juego por <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-03-" target="_blank">combinación de colores</a> que determina la carga de los valores acumulados por día<c>.</c></p>

      <p>Cada carta contiene tres juegos de color<c>:</c></p>

      <ul>
        <li>De Plasma<c>:</c> depende del color asignado al plasma diario de la carta.</li>
        <li>Planetario<c>:</c> corresponde color del Sello Solar en el Flujo G<c>-</c>S para esa carta.</li>
        <li>Semanal<c>:</c> representa el código armónico de la heptada para el día de esa carta</li>
      </ul>

      <p>Siguiendo el código del plasma<c>,</c> se forman los quantum semanales<c>:</c></p>

      <?=Doc_Dat::lis('hol.rad_pla_qua',['atr'=>['ide','nom','des'],'det_des'=>['lec_col'],'opc'=>['cab_ocu']]);?>      

      <p>Por otro lado<c>,</c> según la relación de colores se establece la carga Semanal<c>:</c></p>

      <?=Doc_Dat::lis('hol.lun_pla_ato',['atr'=>['ide','nom','car'],'det_des'=>['car_sec']]);?>      

      <figure style='max-width:40rem;'>
        <img src="<?=$Dir->libro_ima?>atomo_del_tiempo/_02-03-03.gif" alt="">
      </figure>      

    </section>
    
    <!--El heptágono de la Mente-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>El resultado de las meditaciones semanales para la construcción del Átomo es un <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-04-" target="_blank">heptágono de la mente</a> que contiene los valores del ciclo<c>.</c></p>

      <p><q>Cada semana los <n>7</n> plasmas constituyen uno de los <n>4</n> Heptágonos de la Mente que corresponden a las proporciones de la Compresión Fractal del Tiempo<c>.</c></q></p>

      <?=Doc_Dat::lis('hol.lun_pla_ato',['atr'=>['ide','hep','val','val_lun','hep_cub'],'det_des'=>['hep_des']]);?>          

    </section>

    <!--Las 4 Iniciaciones-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p>Por último se realizan las <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-05-" target="_blank">Cuatro Iniciaciones Semanales</a> a partir de los Valores obtenidos en la Combinación de Colores<c>.</c></p>

      <p><q>Las iniciaciones ocurren automáticamente al completarse la séptima etapa de cada visualización semanal del Heptágono-Cubo-Átomo Telepático del Tiempo<c>,</c> y corresponden a la posición semanal en el Holograma Cúbico <n>7</n>:28<c>.</c></q></p>

      <?=Doc_Dat::lis('hol.lun_pla_ato',['atr'=>['ide','ini','val_hep','val_rad','val_kin'],'det_des'=>['ini_des']]);?>            

    </section>    

  </section>  

</article>    