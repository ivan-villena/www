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

  function est_psi(){
    
    $_ = "";

    $psi_pos = 1;
    
    foreach( Dat::_('hol.psi_est') as $_est ){

      $psi = Num::val($psi_pos,3)." - ".Num::val($psi_pos + 90, 3);      
      $psi_pos += 91;

      $_ .= "UPDATE `hol-psi_est` SET 
        `psi` = '$psi'
      WHERE  
        `ide` = $_est->ide
      ;<br>";
    }
    return $_;
  }

  function hep(){
    $_ = "";
    $psi_pos = 1;
    foreach( Dat::_('hol.psi_hep') as $Hep ){
      
      $Ton = Dat::_('hol.ton',$Hep->ton);      
      $_lun = Dat::_('hol.psi_lun',$Ton->ide);
      $_est = Dat::_('hol.psi_est',$Hep->arm);
      $_psi = Dat::_('hol.psi',$psi_pos);
      $Arm = Dat::_('hol.sel_arm_raz',$Hep->pos_arm);
      
      $nom = explode(' ',$_lun->nom)[1]." $Arm->nom";
      $des = "$Ton->des de la Estación Solar ".Tex::let_pal(preg_replace("/o$/","a",$_est->des_col))."";
      $lun = $_psi->lun;
      $est = $Hep->arm;
      
      $psi = Num::val($psi_pos,3)." - ".Num::val($psi_pos + 6, 3);
      $psi_pos += 7;

      $_ .= "UPDATE `hol-psi_hep` SET 
        `nom` = '$nom', 
        `des` = '$des', 
        `est` = $est, 
        `lun` = $lun, 
        `psi` = '$psi' 
      WHERE  
        `ide`=$Hep->ide
      ;<br>";
    }
    return $_;
  }

  function hep_fec(){
    
    $_ = "";

    $psi_pos = 1;
    
    foreach( Dat::_('hol.psi_hep') as $Hep ){

      $psi = Num::val($psi_ini = $psi_pos,3)." - ".Num::val($psi_fin = $psi_pos + 6, 3);      
      $psi_pos += 7;

      $Psi_ini = Dat::_('hol.psi',$psi_ini);
      $Psi_fin = Dat::_('hol.psi',$psi_fin);
      
      $fec_ini = $Psi_ini->fec;
      $fec_fin = $Psi_fin->fec;
      
      $fec_ran = "{$Psi_ini->fec_dia} de ".Fec::mes_nom($Psi_ini->fec_mes)." - {$Psi_fin->fec_dia} de ".Fec::mes_nom($Psi_fin->fec_mes);

      $_ .= "UPDATE `hol-psi_hep` SET 
        `psi`     = '$psi',
        `fec_ran` = '$fec_ran',
        `fec_ini` = '$fec_ini',
        `fec_fin` = '$fec_fin'
      WHERE  
        `ide`=$Hep->ide
      ;<br>";
    }
    return $_;
  }

  function vin_fec(){

    $_ = "";

    $psi_pos = 1;
    
    foreach( Dat::_('hol.psi_vin') as $_vin ){

      $psi_ini = Num::val($psi_pos,3);
      $psi_fin = Num::val($psi_pos + 19, 3);

      if( $psi_fin > 364 ) $psi_fin = '364';

      $psi = "{$psi_ini} - {$psi_fin}";
      $psi_pos += 20;

      $Psi_ini = Dat::_('hol.psi',$psi_ini);
      $Psi_fin = Dat::_('hol.psi',$psi_fin);
      
      $fec_ini = $Psi_ini->fec;
      $fec_fin = $Psi_fin->fec;
      
      $fec_ran = "{$Psi_ini->fec_dia} de ".Fec::mes_nom($Psi_ini->fec_mes)." - {$Psi_fin->fec_dia} de ".Fec::mes_nom($Psi_fin->fec_mes);      

      $_ .= "UPDATE `hol-psi_vin` SET 
        `psi` = '$psi',
        `fec_ran` = '$fec_ran',
        `fec_ini` = '$fec_ini',
        `fec_fin` = '$fec_fin'        
      WHERE  
        `ide` = $_vin->ide
      ;<br>";
    }
    return $_;
  }

  function cro_fec(){

    $_ = "";

    $psi_pos = 1;
    
    foreach( Dat::_('hol.psi_cro') as $_vin ){

      $psi_ini = Num::val($psi_pos,3);
      $psi_fin = Num::val($psi_pos + 4, 3);

      $psi = "{$psi_ini} - {$psi_fin}";
      $psi_pos += 5;

      $Psi_ini = Dat::_('hol.psi',$psi_ini);
      $Psi_fin = Dat::_('hol.psi',$psi_fin);
      
      $fec_ini = $Psi_ini->fec;
      $fec_fin = $Psi_fin->fec;
      
      $fec_ran = "{$Psi_ini->fec_dia} de ".Fec::mes_nom($Psi_ini->fec_mes)." - {$Psi_fin->fec_dia} de ".Fec::mes_nom($Psi_fin->fec_mes);      

      $_ .= "UPDATE `hol-psi_cro` SET 
        `psi` = '$psi',
        `fec_ran` = '$fec_ran',
        `fec_ini` = '$fec_ini',
        `fec_fin` = '$fec_fin'        
      WHERE  
        `ide` = $_vin->ide
      ;<br>";
    }
    return $_;
  }  