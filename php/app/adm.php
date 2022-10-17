<?php

  $_ide = "api-adm";
  $_eje = "_adm";  

  // Ventana
  $win = [
    'ico'=>"eje",
    'nom'=>"Administrador del Sistema",
    'art'=> [ 'style'=>"max-width: 55rem;" ],
    'htm'=> _doc_val::nav('bar', 
    [
      'aja' => [ 'nom'=>"AJAX",   
        'nav'=>[ 'onclick'=>"_adm('aja',this);" ],
        'htm'=>"
  
        <nav class='lis'>
        </nav>
        "
      ],
      'ico' => [ 'nom'=>"Íconos", 
        'nav'=>[ 'onclick'=>"_adm('ico',this);" ],
        'htm'=>"
        
        "._doc_val::var('val','ver',['nom'=>"Filtrar",'ope'=>[ '_tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"_adm('ico',this,'ver')" ]])."
  
        <ul class='lis ite mar-2' style='height: 53vh;'>
        </ul>
        "
      ],
      'jso' => [ 'nom'=>"J.S.", 
        'htm'=>"
  
        <fieldset class='inf pad-3'>
          <legend>Ejecutar JavaScript</legend>      
  
          "._doc_val::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ '_tip'=>"tex_par", 'rows'=>"10", 'class'=>"anc-100", 'oninput'=>"_adm('jso',this)" ] 
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1'>
        </div>"
      ],  
      'php' => [ 'nom'=>"P.H.P.",
        'htm'=>"
  
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar en PHP</legend>
  
          "._doc_val::var('val','ide',[ 'ope'=>[ '_tip'=>"tex_ora" ] ])."
          
          "._doc_val::var('val','par',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
            'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
          ])."
  
          "._doc_val::var('val','htm',[
            'nom'=>"¿HTML?",
            'ope'=>[ '_tip'=>"opc_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
          ])."
          
          "._doc::ico('dat_ope',[
            'eti'=>"button", 'type'=>"submit", 'onclick'=>"_adm('php',this)"
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1' style='height: 40vh; overflow: auto;'>
        </div>
  
        <pre class='ope_res' style='height: 40vh; overflow: auto;'>
        </pre>
        "
      ],
      'sql' => [ 'nom'=>"S.Q.L",
        'htm'=>"
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar S.Q.L.</legend>
  
          "._doc_val::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ '_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1" ],
            'htm_fin'=>_doc::ico('dat_ope',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"_adm('sql',this,'cod')" ])
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1' var='est' style='height: 47vh;'>
        </div>"
      ],
      'htm' => [ 'nom'=>"D.O.M.",
        'htm'=>"
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar Selector</legend>
  
          "._doc_val::var('val','cod',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
            'htm_fin'=>_doc::ico('dat_ope',['eti'=>"button", 'type'=>"submit", 'onclick'=>"_adm('htm',this,'cod')"])
          ])."
  
        </fieldset>
  
        <div class='ele'>
        </div>
  
        <div class='nod mar-1'>
        </div>"
      ] 
    ], [
      'sel' => "php",
      'ite' => [ 'eti'=>"form" ]
    ])
  ];  

  // articulo en ventana emergente
  echo _app_ope::win('api_adm',$win);