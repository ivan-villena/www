<?php

  function hol_psi_hep_est_dia(){
    $_ = "";
    $ton = 1;
    $pos = 0;
    foreach( api_hol::_('psi_est_dia') as $_dia ){
      $pos++;
      if( $pos > 7 ){
        $pos = 0;
        $ton ++;
      }
      $_ .= "
      UPDATE `hol_psi_hep_est_dia` SET
        `ton` = $ton
      WHERE 
        `ide` = $_dia->ide;<br>";
    }
    return $_;
  }

  function hol_psi_hep_pla(){
    $_ = "";
    $psi_pos = 1;
    foreach( api_hol::_('psi_hep_pla') as $_hep ){
      $_ton = api_hol::_('ton',$_hep->ton);      
      $_lun = api_hol::_('psi_ani_lun',$_ton->ide);
      $_est = api_hol::_('psi_hep_est',$_hep->arm);
      $_psi = api_hol::_('psi',$psi_pos);
      $_arm = api_hol::_('sel_arm_raz',$_hep->pos_arm);
      $nom = explode(' ',$_lun->nom)[1]." $_arm->nom";
      $des = "$_ton->des de la Estación Solar ".api_tex::let_pal(preg_replace("/o$/","a",$_est->des_col))."";
      $lun = $_psi->ani_lun;
      $est = $_hep->arm;
      $psi = api_num::val($psi_pos,3)." - ".api_num::val($psi_pos + 6, 3);
      $psi_pos += 7;
      $_ .= "UPDATE `hol_psi_hep_pla` SET `nom`='$nom', `des`='$des', `est`=$est, `lun`=$lun, `psi`='$psi' WHERE  `ide`=$_hep->ide;<br>";
    }
    return $_;
  }