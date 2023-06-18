<?php

  function est_dia(){
    $_ = "";
    $ton = 1;
    $pos = 0;
    foreach( Dat::_('hol.psi_est_dia') as $_dia ){
      $pos++;
      if( $pos > 7 ){
        $pos = 0;
        $ton ++;
      }
      $_ .= "
      UPDATE `hol-psi_est_dia` SET
        `ton` = $ton
      WHERE 
        `ide` = $_dia->ide;<br>";
    }
    return $_;
  }

  function hep(){
    $_ = "";
    $psi_pos = 1;
    foreach( Dat::_('hol.psi_hep') as $_hep ){
      $_ton = Dat::_('hol.ton',$_hep->ton);      
      $_lun = Dat::_('hol.psi_lun',$_ton->ide);
      $_est = Dat::_('hol.psi_est',$_hep->arm);
      $_psi = Dat::_('hol.psi',$psi_pos);
      $_arm = Dat::_('hol.sel_arm_raz',$_hep->pos_arm);
      $nom = explode(' ',$_lun->nom)[1]." $_arm->nom";
      $des = "$_ton->des de la Estación Solar ".Tex::let_pal(preg_replace("/o$/","a",$_est->des_col))."";
      $lun = $_psi->lun;
      $est = $_hep->arm;
      $psi = Num::val($psi_pos,3)." - ".Num::val($psi_pos + 6, 3);
      $psi_pos += 7;
      $_ .= "UPDATE `hol-psi_hep` SET `nom` = '$nom', `des` = '$des', `est`=$est, `lun`=$lun, `psi` = '$psi' WHERE  `ide`=$_hep->ide;<br>";
    }
    return $_;
  }