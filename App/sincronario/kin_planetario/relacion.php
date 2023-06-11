  
<article>
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>
  </header>

  <!--  -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>
  
</article>