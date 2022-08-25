<?php
$_ide = "api-adm";
$_eje = "_adm";

// Ventana
$win = [
  'ico'=>"eje",
  'nom'=>"Administrador del Sistema",
  'art'=> [ 'style'=>"max-width: 75rem;" ],
  'htm'=>"",
];

ob_start();
?>

<?= _doc_nav::val('bar',[
  'aja' => [ 'nom'=>"Ajax",   'ele'=>['onclick'=>"_adm('aja',this);"] ],
  'ico' => [ 'nom'=>"Íconos", 'ele'=>['onclick'=>"_adm('ico',this);"] ],
  'php' => [ 'nom'=>"PHP",    'ele'=>[ 'class'=>FON_SEL ]  ],
  'sql' => [ 'nom'=>"SQL" ],
  'jso' => [ 'nom'=>"JS"  ],
  'htm' => [ 'nom'=>"DOM" ]
]) ?>

<div>

  <form ide='aja' class='dis-ocu'>

    <nav class='lis'></nav>

  </form>

  <form ide='ico' class='dis-ocu'>

    <?=_doc::var('val','ver',['nom'=>"Filtrar",'ope'=>[ '_tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"_adm('ico',this,'ver')" ]])?>

    <ul class='lis ite mar-2'></ul>

  </form>

  <form ide='jso' class='dis-ocu'>

    <fieldset class='inf pad-3'>
      <legend>Ejecutar JavaScript</legend>      

      <?=_doc::var('val','cod',[ 
        'ite'=>[ 'class'=>"tam-cre" ], 
        'ope'=>[ '_tip'=>"tex_par", 'rows'=>"10", 'class'=>"anc-100", 'oninput'=>"_adm('jso',this)" ] 
      ])?>

    </fieldset>

    <div class='ope_res mar-1'></div>

  </form>          

  <form ide='php' class=''>

    <fieldset class='inf ite pad-3'>
      <legend>Ejecutar PHP</legend>

      <?=_doc::var('val','ide',[ 'ope'=>[ '_tip'=>"tex_ora" ] ])?>
      
      <?=_doc::var('val','par',[ 
        'ite'=>['class'=>"tam-cre"], 
        'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
        'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
      ])?>

      <?=_doc::var('val','htm',[
        'nom'=>"¿HTML?",
        'ope'=>[ '_tip'=>"opc_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
      ])?>
      
      <?=_doc::ico('eje_val',['eti'=>"button", 'type'=>"submit", 'onclick'=>"_adm('php',this)"])?>

    </fieldset>

    <div class='ope_res mar-1'></div>

    <pre class='ope_res'></pre>

  </form>  

  <form ide='sql' class='dis-ocu'>

    <fieldset class='inf ite pad-3'>
      <legend>Ejecutar SQL</legend>

      <?=_doc::var('val','cod',[ 
        'ite'=>['class'=>"tam-cre"], 'ope'=>['_tip'=>"tex_par", 'row'=>5, 'class'=>"anc-100 mar_der-1"],
        'htm_fin'=>_doc::ico('eje_val',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"_adm('sql',this,'cod')" ])
      ])?>

    </fieldset>

    <div class='ope_res mar-1'></div>

  </form>              

  <form ide='htm' class='dis-ocu'>

    <fieldset class='inf ite pad-3'>
      <legend>Ejecutar Selector</legend>

      <?=_doc::var('val','cod',[ 
        'ite'=>['class'=>"tam-cre"], 
        'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
        'htm_fin'=>_doc::ico('eje_val',['eti'=>"button", 'type'=>"submit", 'onclick'=>"_adm('htm',this,'cod')"])
      ])?>

    </fieldset>

    <div class='ele'></div>

    <div class='nod mar-1'></div>

  </form>

</div>

<?php

$win['htm'] = ob_get_clean();

// articulo en ventana emergente
echo _doc_art::win('api_adm',$win);