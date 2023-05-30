<!--el Holon Planetario-->
<article>
  <?php $nv1="00";$nv2="00";$nv3="00";$nv4="00";?>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header>
  
  <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Bib?>encantamiento_del_sueño#_03-07-" target="_blank">...</a><c>.</c></p>

  <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','cro_fam','pla_cen','flu_res_des'],'tit_cic'=>['cro_ele']])?>

  <!--centros galácticos-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Bib?>encantamiento_del_sueño#_03-07-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.pla_cen',['atr'=>['ide','nom','des_fun','fam','sel']])?>

  </section>

  <!--flujos de la fuerza-g-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Bib?>encantamiento_del_sueño#_03-16-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.pla_res')?>

  </section>

  <!--campos dimensionales-->
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>Un Tratado del Tiempo</cite> <a href="<?=$Bib?>dinamicas_del_tiempo#" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.pla_tie',['atr'=>['ide','nom','tra','des']])?>
    
  </section>  

</article>