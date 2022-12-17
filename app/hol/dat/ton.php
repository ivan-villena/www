<!-- 13 Tonos Galácticos -->
<div>  

  <!-- rayos de pulsación 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=api_tex::let($_nav[1]['01']->nom)?></h2>
    <p>En <cite>el Factor Maya</cite> se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c></p>
    <p>Luego<c>,</c> los <n>13</n> tonos se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c></p>
    <?=api_lis::est('hol.ton',[ 'atr'=>['ide','gal'] ])?>
  </article>

  <!-- simetría especular -->
  <article>
    <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=api_tex::let($_nav[1]['02']->nom)?></h2>
    <p>En el <cite>Factor Maya</cite> se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c></p>
    <p>Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a><c>.</c></p>
    <?=api_lis::est('hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>          
  </article>
  
  <!-- principios de la creacion -->
  <article>
    <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=api_tex::let($_nav[1]['03']->nom)?></h2>
    <p>En <cite>el Encantamiento del sueño</cite> se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c></p>
    <p>De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c></p>
    <?=api_lis::est('hol.ton',[ 'atr'=>['ide','nom','des','des_acc'] ])?>
  </article>

  <!-- O.E. de la Aventura 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=api_tex::let($_nav[1]['04']->nom)?></h2>
    <p>En el <cite>Encantamiento del sueño</cite> se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c></p>
    <p>Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c></p>
    <?=api_lis::est('hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc']])?>
  </article>
  
  <!-- pulsar dimensional -->
  <article>
    <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=api_tex::let($_nav[1]['05']->nom)?></h2>
    <p>En <cite>el Encantamiento del sueño</cite> <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a> <c>.</c></p>
    <?=api_lis::est('hol.ton_dim')?>
  </article>

  <!-- pulsar matiz -->
  <article>
    <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=api_tex::let($_nav[1]['06']->nom)?></h2>
    <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>
    <?=api_lis::est('hol.ton_mat')?>
  </article>

</div>