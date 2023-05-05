<!-- 260 kines del Giro Galáctico -->
<article>
<?php $nv1 = "00"; $nv2 = "00"; $nv3 = "00"; $nv4 = "00"; ?>
  <header>
    <h1><?=Tex::let($App->Art->nom)?></h1>
  </header>
  
  <!-- El Kin Planetario -->
  <section>
    <?php $nv1 = Num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Tex::let($Nav[1][$nv1]->nom)?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$Bib?>enc#_02-03-01-" target="_blank">la Firma Galáctica</a> es el nombre código del <i>Kin Planetario</i><c>,</c> aquella persona que se identifica con su kin y desea participar en el juego de roles<c>.</c> Este nombre está compuesto por el <i>Color</i><c>,</c> el <i>Tono Galáctico</i><c>,</c> y el <i>Sello Solar</i><c>.</c></p>

  </section>

</article>  