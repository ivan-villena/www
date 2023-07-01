<?php 

  // cargo datos de los ciclos para tableros
  $dat_psi = Sincronario::dat_val( $Cic['Fec'], 365, $Psi->ide );
  
  /* busco fechas de ciclos */
  $Cic_psi = [];
  $psi_ini = $dat_psi[0]['var-fec'];
  $psi_fin = $dat_psi[count($dat_psi)-1]['var-fec'];
?>
<article>  
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <section class="ope_tex ali-cen">

    <?=Sincronario::dat_var('val',$Cic);?>

  </section>

  
  <!--Cilo N.S.-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>      

    <p><?=Doc_Val::let("N.S. {$Cic['sir']}");?></p>

    <p><?=Doc_Val::let("Desde el año {$Sir->año_ini} - Hasta el año {$Sir->año_fin}");?></p>

    <p><?=Doc_Val::let("Completados {$Sir->ani} Anillos desde la Convergencia Armónica: Ha".( $Sir->orb == 1 ? "" : "n" )." sido {$Sir->orb} órbita".( $Sir->orb == 1 ? "" : "s" )." de Sirio Beta.");?></p>

    <p>Para saber más sobre estos ciclos<c>,</c> visita el <a href="<?=$Dir->tutorial?>sincronario#_07-" target="_blank">tutorial del Sincronario</a><c>...</c></p>

  </section>

  <!--Anillo solar-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p><?=Doc_Val::let("Desde el {$Ani->fec_ini} - Hasta el {$Ani->fec_fin}");?></p>
    
    <?=Doc_Dat::fic('hol.psi_ani',$Cic['ani'] + 1)?>    

    <?=Sincronario::dat_tab('psi_ani',[
      'val'=>[ 'pos'=>$Cic ],
      'atr-pul'=>[]
    ])?>
    
    <!--Luna del Servicio Planetario-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <?=Doc_Dat::fic('hol.psi_lun',$Psi->lun)?>

      <?=Sincronario::dat_tab('psi_lun',[
        'val'=>[ 'pos'=>$Cic ],
        'opc'=>[ "gru", "kin" ],
        'atr-pul'=>[]
      ])?>

      <?=Doc_Dat::fic('hol.lun',$Psi->lun_dia)?>

      <?=Sincronario::dat_tab('psi_lun_dia',[
        'dat'=>$dat_psi,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'col'=>"hol.psi.hep", 'num'=>"hol.psi.lun_dia", 'ima'=>"hol.kin.ide", 'fec'=>"val" ]
      ])?>
      
      <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>psi#_01-01-" target="_blank">enlace</a><c>...</c></p>

    </section>     

    <!--Estación Solar-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <?=Doc_Dat::fic('hol.psi_est',$Psi->est)?>

      <?=Doc_Dat::fic('hol.psi_est_dia',$Psi->est_dia)?>

      <?=Sincronario::dat_tab('psi_est',[
        'dat'=>$dat_psi,
        'val'=>[ 'pos'=>$Psi->hep ],        
        'opc'=>[ "gru" ],
        'atr-pul'=>[]
      ])?>

      <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>psi#_01-02-" target="_blank">enlace</a><c>...</c></p>

    </section>     
    
    <!--Heptada Semanal-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <?=Doc_Dat::fic('hol.psi_hep',$Psi->hep)?>

      <?=Doc_Dat::fic('hol.rad',$Psi->hep_dia)?>

      <?=Sincronario::dat_tab('psi_hep',[ 
        'dat'=>$dat_psi,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.psi.hep_dia", 'num'=>"hol.rad.ide" ]
      ])?>

      <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>psi#_01-03-" target="_blank">enlace</a><c>...</c></p>          

    </section>         

  </section> 
  
  <!--Psi-Cronos-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>      

    <?=Doc_Dat::fic('hol.psi',$Psi)?>

    <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>psi#_03-01-" target="_blank">enlace</a><c>...</c></p>    
    
    <!--Cromática Entonada-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <?=Doc_Dat::fic('hol.psi_cro',$Psi->cro)?>

      <p><?=Doc_Val::let("Día {$Psi->cro_dia} de 5");?></p>

      <?=Sincronario::dat_tab('psi_cro',[ 
        'dat'=>$dat_psi,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ]
      ])?>
      
      <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>psi#_03-02-" target="_blank">enlace</a><c>...</c></p>          

    </section> 

    <!--Vinal del Haab-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <?=Doc_Dat::fic('hol.psi_vin',$Psi->vin)?>

      <p><?=Doc_Val::let("Día {$Psi->vin_dia} de ".( $Psi->vin != 19 ? "20" : "5"  ));?></p>
      
      <?=Sincronario::dat_tab('psi_vin',[ 
        'dat'=>$dat_psi,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ]
      ])?>

      <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>psi#_03-03-" target="_blank">enlace</a><c>...</c></p>          
      
    </section>     

  </section>
  
</article>