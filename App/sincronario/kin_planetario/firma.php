  
<?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>
<article>
  
  <header>
    <h1><?=Doc_Val::let($App->Art->nom)?></h1>

  </header>

  <section>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$Dir->libro?>enc#_02-03-01-" target="_blank">la Firma Galáctica</a> es el nombre código del <b>Kin Planetario</b><c>,</c> aquella persona que se identifica con su kin y desea participar en el juego de roles<c>.</c> Este nombre está compuesto por el <b>Color</b><c>,</c> el <b>Tono Galáctico</b><c>,</c> y el <b>Sello Solar</b><c>.</c></p>

  </section>  

  <!-- El kin del Destino -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>

  <!-- Las parejas del Oráculo -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>
  
  <!-- La Onda Encantada -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>
  
  <!-- La Célula del Tiempo -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>
  
  <!-- La Familia Terrestre -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>
  
  <!-- Las posiciones del Holon -->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->key}-"?>">    
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

    <p></p>

  </section>  
  
</article>