<!--7 Plasmas Radiales-->
<article>
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <!--Días de la Semana-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El encantamiento del Sueño</cite> se divide al año en <a href="<?=$Dir->libro?>enc#_02-03-09-" target="_blank"><n>13</n> lunas de <n>28</n> días</a> cada una<c>.</c> A su vez<c>,</c> cada luna está conformada por <n>4</n> semanas<c>-</c>héptadas de <n>7</n> días<c>.</c></p>

    <p>Posteriormente<c>,</c> en el libro de <cite>Las <n>13</n> lunas en movimiento</cite><c>,</c> se mencionan los plasmas para nombrar a cada uno de los <a href="<?=$Dir->libro?>lun#_02-07-" target="_blank">días de la semana<c>-</c>heptada</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.rad',['atr'=>['ide','nom','des_pod']]);?>

    <!--Autodeclaraciones Diarias-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En <cite>Átomo del Tiempo</cite> se relaciona cada plasma con una <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-06-" target="_blank">Autodeclaración de Padmasambhava</a> para <q>integrar la mente en el proceso electromagnético continuo plásmicamente autocreativo del universo</q><c>.</c> En esa misma sección se describen los relaciones simbólicas para cada término correspondiente a su afirmación<c>.</c></p>

      <?=Doc_Dat::lis('hol.rad',['atr'=>['ide','pla_lec'],'opc'=>["cab_ocu"]]);?>

    </section>    

  </section>
  
  <!--Sellos de la Profecia-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El telektonon</cite> se presentan los plasmas como <a href="<?=$Dir->libro?>telektonon#_02-06-" target="_blank">sellos de la profecía</a><c>.</c> Estas profecías describen el desarrollo de los acontecimientos para el final de ciclo y la transición al nuevo <q>paradigma resonante</q><c>.</c></p>

    <p>Para la lectura anual se crean <n>3</n> oráculos en base a los kines que <a href="<?=$Dir->libro?>enc#_03-14-" target="_blank">codifican los ciclos del sincronario</a><c>:</c> familia portal y familia señal<c>.</c></p>

    <?=Doc_Dat::lis('hol.rad',['atr'=>['ide','tel','tel_año','tel_des','tel_ora_año','tel_ora_ani','tel_ora_gen']]);?>

    <p>En el <cite>Proyecto Rinri</cite> se amplía el contenido de los <a href="<?=$Dir->libro?>proyecto_rinri#_02-05-01-" target="_blank">sellos de la profecía</a> del Telektonon<c>.</c></p> 
    
    <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

    <?=Doc_Dat::lis('hol.rad',['atr'=>['ide','tel_año','rin_des'],'opc'=>["cab_ocu"]]);?>

    <!--Heptágono de la Mente-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>En el <cite>telektonon</cite> se crea un <a href="<?=$Dir->libro?>telektonon#_02-06-03-" target="_blank">arreglo en forma de heptágono</a> con los plasmas para recrear los <n>7</n> años de la profecía durante los días <n>14</n> a <n>22</n> de cada Luna<c>.</c> Este ejercicio sirve para <q>la restauración de la mente<c>,</c> la voluntad y el espíritu colectivos<c>,</c> unificados como el triunfo del Cubo de la Ley de la divina unidad<c>,</c> el advenimiento de la Noosfera planetaria</q><c>.</c></p>

      <p>En el <cite>Proyecto Rinri</cite> la <a href="<?=$Dir->libro?>proyecto_rinri#_02-06-01-" target="_blank"><n>1</n><c>°</c> fase del sexto paso<c>:</c> Lanzamiento de la Liberación del Plasma Radial</a><c>,</c> se realiza un procedimiento de meditación óptica sobre los plasmas para liberar su energía hacia la biósfera en la transición Biósfera<c>-</c>Noosfera<c>.</c></p>

      <?=Doc_Dat::lis('hol.rad',['atr'=>['ide','pla_cub_pos','pla_cub'],'opc'=>["cab_ocu"]]);?>

    </section>      

  </section>
  
  <!--Componenetes Electrónicos-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>el Átomo del tiempo</cite> se establecen los principios de los plasmas en el marco de la <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-01-" target="_blank">energía o electricidad cósmica</a><c>.</c> En este caso<c>,</c> cada plasma está compuesto por dos <b>Líneas Electrónicas de Fuerza</b> con cierta combinación de cargas<c>.</c></p>

    <?=Doc_Dat::lis('hol.rad',['atr'=>['ide','des_col','pla_des','pla_fue'],'opc'=>["cab_ocu"]]);?>

    <!--12 líneas de Fuerza-->        
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>Cada <b>Línea Electrónica de Fuerza</b> está compuesta por dos tipos de <b>Electricidad Cósmica Primigenia</b><c>:</c></p>

      <?=Doc_Dat::lis('hol.rad_pla_fue',['atr'=>['ide','nom','ele_pre','ele_ope','ele_pos'],'opc'=>["cab_ocu"]]);?>            

    </section>

    <!--6 Tipos de Electricidad-->        
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>Estos tipos de <b>Electricidad Cósmica</b> están compuestos por distintas <b>Cargas Electricas</b> y constituyen el <b>Patrón Cúbico Primario</b> cuyo diagrama tiene la misma forma que los electrones rodeando su nucleo atómico<c>.</c></p>

      <?=Doc_Dat::lis('hol.rad_pla_ele',['atr'=>['ide','nom','des'],'opc'=>["cab_ocu"]]);?>      
      
    </section>    

    <!--3 Quántum-->        
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>  

      <p>Surgen por la combinación de distintos <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-01-" target="_blank">Plasmas Radiales</a> cumpliendo funciones estructurales en la composición del <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-02-" target="_blank">Átomo Telepático del Tiempo</a> por la <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-03-" target="_blank">Combinación de Colores y Cargas</a><c>.</c></p>

      <p>Son establecidos con las <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-06-" target="_blank">Autodeclaraciones de Padmasambhava</a> y habilitados con los <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-07-" target="_blank">Chakras en el Cubo del Radión</a><c>.</c></p>

      <?=Doc_Dat::lis('hol.rad_pla_qua',['atr'=>['ide','nom','lec_col','pla'],'opc'=>["cab_ocu"]]);?>
      
    </section>

  </section>
  
  <!--El Holon Humano -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>El Átomo del Tiempo</cite> se diferencian los <n>5</n> Centros psico<c>-</c>físicos del Holon Humano de <a href="<?=$Dir->libro?>atomo_del_tiempo#_03-07-" target="_blank">los <n>7</n> chakras</a> de la filosofía tradicional esotérica<c>.</c></p>

    <p>En este caso se asocia cada plasma a un chakra y se arma una secuencia para meditar sobre cada uno en la construcción del <b>Cubo del Radión</b> que sigue el mismo patrón que el <b>Heptágono de la Mente</b><c>.</c></p>

    <?=Doc_Dat::lis('hol.rad',['atr'=>[ 'ide','nom','hum_cha','cha_nom','pla_cub'],'opc'=>["cab_ocu"]]);?>

  </section>

</article>  