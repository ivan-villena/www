<!--260 kines del Giro Galáctico-->
<article>  
  <?php $nv1="00";$nv2="00";$nv3="00";$nv4="00";?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>
  
  <!--El Kin Planetario-->
  <section>
    <?php $nv1=Num::val(intval($nv1)+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <h2 id="<?="_{$Nav[1][$nv1]->key}-"?>"><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p>En <cite>el Encantamiento del Sueño</cite> <a href="<?=$Bib?>enc#_02-03-01-" target="_blank">la Firma Galáctica</a> es el nombre código del <b>Kin Planetario</b><c>,</c> aquella persona que se identifica con su kin y desea participar en el juego de roles<c>.</c> Este nombre está compuesto por el <b>Color</b><c>,</c> el <b>Tono Galáctico</b><c>,</c> y el <b>Sello Solar</b><c>.</c></p>

  </section>

  <!---->


</article>  