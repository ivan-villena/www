  
<?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>
<article>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>

  </header>

  <!-- introduccion -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>encantamiento_del_sueño#_03-08-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','hum_des'],'tit_cic'=>['cro_ele']])?>

  </section>

  <!--Centros Galácticos-->        
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_cen')?>

  </section>

  <!--Extremidades-->        
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_ext')?>

  </section>  

  <!--dedos-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_ded')?>

  </section>  

  <!--lados-->        
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_res')?>

  </section>

  <!-- -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>  
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

 

  </section>    
  
</article>