
<?php
  $Dia = Sincronario::val( date('Y/m/d') );
  $Kin = Dat::_('hol.kin',$Dia['kin']);
  $Psi = Dat::_('hol.psi',$Dia['psi']);
  $Fec = Fec::dat($Dia['fec']);
  $Sin = explode('.',$Dia['sin']);
  /* 
    <?= ?>
  */
?>

<header>
  <h1>Inicio</h1>
</header>

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

    <p class="tex-4 tex-cur"><?=Doc_Val::let("N.S. {$Dia['sin']} - Kin {$Dia['kin']}")?></p>
    
  </section>

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.sir_ani',$Sin[1]) ?>

  </section>  

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.psi_lun',$Psi->lun) ?>

  </section>

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.lun',$Psi->lun_dia) ?>

  </section>  

  <section class="ope_tex anc-75">

    <?= Doc_Dat::fic('hol.kin',$Kin) ?>    

  </section>