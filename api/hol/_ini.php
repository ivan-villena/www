<?php

function todo(){
  $_ = "";
  foreach( [ 'tono'=>'ton', 'sello'=>'sel', 'kin'=>'kin'] as $ide => $est ){
    foreach( hol::_($est) as $dat ){

      $_ .= "UPDATE `cac_react`.`$ide` SET 
        `imagen` = '".str_replace("localhost","www.icpv.com.ar",str_replace(";","",str_replace("background: ","",dat::est_ope('hol',$est,'val.ima',$dat))))."'
      WHERE 
        `id` = $dat->ide;<br>";
    }
  }
  return $_;
}

function kin_actualizar(){
  $_ = "";
  foreach( hol::_('kin') as $kin ){
    $_ .= "UPDATE `cac`.`kin` SET 
      `tono` = $kin->nav_ond_dia,
      `sello` = $kin->arm_tra_dia,
      `onda_encantada` = $kin->nav_ond
      WHERE 
      `id` = $kin->ide;<br>";
  }
  return $_;
}