<!--260 kines del Giro Galáctico-->
<article>
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <!-- La Cuenta del Factor Maya -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

  </section>

  <!-- Los Códigos del Sincronario -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

  </section>
  
  <!-- El Código NS: Los 52 años del Castillo -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

  </section>  

</article>