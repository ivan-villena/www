
<article>
  <h2>Buscar</h2>

  <p>En el siguiente listado podés encontrar los términos y sus significados por Libro.</p>

  <form class="ite">

    <?= doc::var('val','ver',[ 
      'des'=> "Filtrar...",
      'ite'=> [ 'class'=>"tam-cre" ],
      'htm'=> doc::val_ver(['cue'=>0, 'eje'=>"dat_ide('ver',this)" ], [ 'class'=>"anc-100" ])
    ]) ?>

  </form>

  <div style="height: 75vh; overflow: auto;">
  
    <table>

      <thead>
        <tr>
          <th scope="col" data-atr="ide" >Libro</th>
          <th scope="col" data-atr="nom" >Término</th>
          <th scope="col" data-atr="des" >Definicion</th>
        </tr>
      </thead>

      <tbody>
      <?php
      $_lib = FALSE;
      foreach( dat::get("app_ide",[
        'ver'=>"`esq` = 'hol'",
        'ord'=>"`ide` ASC, `nom` ASC"
      ]) as $i => $v ){
        if( !$_lib || $_lib->ide != explode('_',$v->ide)[0] ){
          $_lib = dat::get("app_art",[ 
            'ver'=>"esq = 'hol' AND cab = 'bib' AND ide = '$v->ide'", 
            'opc'=>"uni"
          ]);
        }echo "
        <tr>
          <td data-atr='ide'><a href='$_bib/$_lib->ide' target='_blank'>".tex::let($_lib->nom)."</a></td>
          <td data-atr='nom'>".tex::let($v->nom)."</td>
          <td data-atr='des'>".tex::let($v->des)."</td>
        </tr>";
      }?>
      </tbody>

    </table>
  </div>

</article>    