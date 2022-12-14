<?php

  $_eje = "sis_adm";  
  $_ide = "sis_adm";  
  
  // secciones
  $_ope = [
    'aja' => [ 'nom'=>"AJAX",   'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('aja',this);" ] ],
    'ico' => [ 'nom'=>"Íconos", 'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('ico',this);" ] ],
    'jso' => [ 'nom'=>"J.S.",   'htm'=>"" ],  
    'php' => [ 'nom'=>"P.H.P.", 'htm'=>"" ],
    'sql' => [ 'nom'=>"S.Q.L",  'htm'=>"" ],
    'htm' => [ 'nom'=>"D.O.M.", 'htm'=>"" ] 
  ];

  // datos por ajax
    ob_start();
    ?>
      <nav class='lis'>

      </nav>
    <?php
    $_ope['aja']['htm'] = ob_get_clean();

  // iconos del sistema
    ob_start();
    ?>
      <?=sis_app::var('val','ver',['nom'=>"Filtrar",'ope'=>[ 
        'tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"$_eje('ico',this,'ver')"  
      ]])?>

      <ul class='lis ite mar-2'>
      </ul>

    <?php
    $_ope['ico']['htm'] = ob_get_clean();

  // javascript: consola
    ob_start();
    ?>
      <fieldset class='app_inf pad-3'>
        <legend>Ejecutar JavaScript</legend>      

        <?=sis_app::var('val','cod',[ 
          'ite'=>[ 'class'=>"tam-cre" ], 
          'ope'=>[ 'tip'=>"tex_par", 'rows'=>"10", 'class'=>"anc-100", 'oninput'=>"$_eje('jso',this)" ] 
        ])?>

      </fieldset>

      <div class='ope_res mar-1'>
      </div>  
    <?php
    $_ope['jso']['htm'] = ob_get_clean();

  // php: ejecucion
    ob_start();
    ?>
      <fieldset class='app_inf dir-hor pad-3'>
        <legend>Ejecutar en PHP</legend>

        <?=sis_app::var('val','ide',[ 'ope'=>[ 'tip'=>"tex_ora" ] ])?>
        
        <?=sis_app::var('val','par',[ 
          'ite'=>['class'=>"tam-cre"], 
          'ope'=>['tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
          'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
        ])?>

        <?=sis_app::var('val','htm',[
          'nom'=>"¿HTML?",
          'ope'=>[ 'tip'=>"opc_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
        ])?>
        
        <?=api_fig::ico('dat_ope',[
          'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('php',this)"
        ])?>

      </fieldset>

      <div class='ope_res mar-1'></div>

      <pre class='ope_res'></pre>

    <?php
    $_ope['php']['htm'] = ob_get_clean();

  // sql: consultas
    ob_start();
    ?>
      <fieldset class='app_inf dir-hor pad-3'>
        <legend>Ejecutar S.Q.L.</legend>

        <?=sis_app::var('val','cod',[ 
          'ite'=>[ 'class'=>"tam-cre" ], 
          'ope'=>[ 'tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1" ],
          'htm_fin'=> api_fig::ico('dat_ope',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('sql',this,'cod')" ])
        ])?>

      </fieldset>

      <div class='lis est ope_res mar-1'></div>
    <?php
    $_ope['sql']['htm'] = ob_get_clean();

  // html: consultas dom
    ob_start();
    ?>
      <fieldset class='app_inf dir-hor pad-3'>
        <legend>Ejecutar Selector</legend>

        <?=sis_app::var('val','cod',[ 
          'ite'=>['class'=>"tam-cre"], 
          'ope'=>['tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
          'htm_fin'=> api_fig::ico('dat_ope',['eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('htm',this,'cod')"])
        ])?>

      </fieldset>

      <div class='ele'></div>

      <div class='ele_nod mar-1'></div>

    <?php
    $_ope['htm']['htm'] = ob_get_clean();

  echo sis_app::nav('bar', $_ope, [ 'sel' => "php", 'ite' => [ 'eti'=>"form" ] ]);