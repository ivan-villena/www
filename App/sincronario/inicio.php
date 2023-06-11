

<article>
  
  <section class="inicio">

    <?=Doc_Ope::tex([ 'tip'=>"adv", 'tit'=>"¡Atención!", 
      'tex'=>[
        "Este sitio aún está en construcción...",
        "Puede haber contenido incompleto, errores o faltas."
      ],
      'htm'=>[
        'eti'=>"div",
        'class'=>"-ite jus-cen mar-1", 
        'htm'=>"Contacto<c>:</c> 
          ".Doc_Val::ico('usu_mai',['eti'=>"a",
            'href'=>"mailto:ivan.pieszko@gmail.com",
            'class'=>"mar_hor-1",
            'title'=>"Enviar un correo electrónico..."
          ])."
          ".Doc_Val::ico('usu_tel',['eti'=>"a",
            'href'=>"tel:+5491131037776",
            'class'=>"mar_hor-1",
            'title'=>"Enviar un mensaje al celular..."
          ])
      ]
    ])?>

  </section>

</article>