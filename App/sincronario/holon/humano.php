<!--el Holon Humano-->
<article>  
  <?php $nv1="00";$nv2="00";$nv3="00";$nv4="00";?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header>

  <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Bib?>encantamiento_del_sueño#_03-08-" target="_blank">...</a><c>.</c></p>

  <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','hum_des'],'tit_cic'=>['cro_ele']])?>

  <!--Centros Galácticos-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_cen')?>

  </section>

  <!--Extremidades-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_ext')?>

  </section>                     

  <!--dedos-->
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_ded')?>

  </section>

  <!--lados-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <?=Doc_Dat::lis('hol.hum_res')?>

  </section>      

</article>