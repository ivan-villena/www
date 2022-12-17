<?php

  function hol_psi_est_dia(){
    $_ = "";
    $ton = 1;
    $pos = 0;
    foreach( hol::_('psi_est_dia') as $_dia ){
      $pos++;
      if( $pos > 7 ){
        $pos = 0;
        $ton ++;
      }
      $_ .= "
      UPDATE `hol_psi_est_dia` SET
        `ton` = $ton
      WHERE 
        `ide` = $_dia->ide;<br>";
    }
    return $_;
  }