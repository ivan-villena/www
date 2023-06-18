<!--28 días del giro lunar -->
<article>
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <!-- Introducción -->
  <?php $nv1=Num::val($nv1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>  
    
  </section>

  <!-- Heptada lunar -->
  <section>
    
    <p>Según <a href="<?=$Dir->libro?>lunas_en_movimiento#_02-07-01-" target="_blank">la Naturaleza del tiempo</a> de <cite>Las 13 lunas en movimiento</cite>:</p>

    <p><q>Cada luna de 28 días es un conjunto perfecto de cuatro semanas/heptadas de 7 días. Cada conjunto de cuatro semanas del calendario de 13 lunas es una "armónica aumentada". La armónica es una unidad de cuatro kin, la unidad más pequeña agregada del giro galáctico de 260 Kin.</q></p>

    <p>Cada <a href="<?=$Dir->libro?>lunas_en_movimiento#_02-07-01-" target="_blank">incremento armónico</a> se corresponde a una función de código de color:</p>

    <?=Doc_Dat::lis('hol.lun_arm',['atr'=>[ 'ide','nom','des_col','dia','des_pod' ],'opc'=>['cab_ocu']]);?>

  </section>

  <!-- El tubo por el que habla la tierra -->

  <!-- El tubo por el que habla la tierra -->

</article>