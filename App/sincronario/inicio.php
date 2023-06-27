
<?php
  // proceso valores del ciclo
  $Cic = Sincronario::val( date('Y/m/d') );    
?>

<article>

  <!--    
  <section class="inicio">

    <?=Doc_Ope::tex([ 
      'tip'=>"adv", 
      'tit'=>"¡Atención!", 
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
  -->

  <section class="ope_tex">

    <p class="tex-4 tex-cur"><?=Doc_Val::let("N.S. {$Cic['val']} - Kin {$Cic['kin']}")?></p>
    
  </section>

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.sir_ani',$Cic['ani']) ?>

  </section>  

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.psi_lun',$Cic['lun']) ?>

  </section>

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.lun',$Cic['dia']) ?>

  </section>  

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.kin',$Cic['kin']) ?>    

  </section>