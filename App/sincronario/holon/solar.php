<!--el Holon Solar-->
<article>
  <?php $nv1="00";$nv2="00";$nv3="00";$nv4="00";?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header> 

  <p>En el <a href="<?=$Bib?>encantamiento_del_sueño#_03-03-" target="_blank">Código <n>0<c>-</c>19</n></a> del <cite>Encantamiento del Sueño</cite> <c>.</c></p>

  <?=Doc_Dat::lis('hol.sel_cod',['atr'=>['ide','sel','sol_pla_des'],'tit_cic'=>['sol_cel'],'opc'=>['cab_ocu']])?>

  <!--orbitas planetarias-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Factor Maya</cite> <a href="<?=$Bib?>factor_maya" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sol_pla',['atr'=>['ide','nom','nom_cod','sel','orb','cel','cir']])?>

  </section>

  <!--células solares-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>El Encantamiento del Sueño</cite> <a href="<?=$Bib?>encantamiento_del_sueño#_03-03-" target="_blank">...</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sol_cel',['atr'=>['ide','nom','des','pla','sel']])?>

  </section>

  <!--circuitos de telepatía-->        
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <a href="<?=$Bib?>telektonon" target="_blank">Telektonon</a><c>.</c></p>

    <?=Doc_Dat::lis('hol.sol_cir',['atr'=>['ide','nom','des','cue','pla','sel']])?>

  </section>       
  
</article>