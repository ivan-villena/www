<!--365 psi-cronos del Giro Solar-->
<article>
  <?php $nv1="00";$nv2="00";$nv3="00";$nv4="00";?>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header>
  
  <!---->
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

  </section>

</article>  