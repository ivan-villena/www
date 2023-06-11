  
<?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>
<article>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>

  </header>

  
  <!-- Introduccion -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','cro_fam','pla_cen','flu_res_des'],'tit_cic'=>['cro_ele']])?>

  </section>
  
  <!--centros galácticos-->        
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-07-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.pla_cen',['atr'=>['ide','nom','des_fun','fam','sel']])?>

  </section>

  <!--flujos de la fuerza-g-->        
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-16-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.pla_res')?>

  </section>  


  <!--campos dimensionales-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>Un Tratado del Tiempo</cite> <a href="<?=$Dir->libro?>dinamicas_del_tiempo#" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.pla_tie',['atr'=>['ide','nom','tra','des']])?>

  </section>

</article>