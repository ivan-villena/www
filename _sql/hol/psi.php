<?php

  function hol_psi_hep_est_dia(){
    $_ = "";
    $ton = 1;
    $pos = 0;
    foreach( Hol::_('psi_est_dia') as $_dia ){
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
    foreach( Hol::_('psi_hep_pla') as $_hep ){
      $_ton = Hol::_('ton',$_hep->ton);      
      $_lun = Hol::_('psi_ani_lun',$_ton->ide);
      $_est = Hol::_('psi_hep_est',$_hep->arm);
      $_psi = Hol::_('psi',$psi_pos);
      $_arm = Hol::_('sel_arm_raz',$_hep->pos_arm);
      $nom = explode(' ',$_lun->nom)[1]." $_arm->nom";
      $des = "$_ton->des de la Estación Solar ".Tex::let_pal(preg_replace("/o$/","a",$_est->des_col))."";
      $lun = $_psi->ani_lun;
      $est = $_hep->arm;
      $psi = Num::val($psi_pos,3)." - ".Num::val($psi_pos + 6, 3);
      $psi_pos += 7;
      $_ .= "UPDATE `hol_psi_hep_pla` SET `nom`='$nom', `des`='$des', `est`=$est, `lun`=$lun, `psi`='$psi' WHERE  `ide`=$_hep->ide;<br>";
    }
    return $_;
  }