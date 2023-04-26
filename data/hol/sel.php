<?php

function hol_sel_par_gui() {

  $_ = "DELETE FROM `hol_sel_par_gui`;";
  foreach( Hol::_('sel') as $_sel ){
    $_par = explode(', ',$_sel->par_gui);
    $_ .= "INSERT INTO `hol_sel_par_gui` VALUES ( $_sel->ide, {$_par[0]}, {$_par[1]}, {$_par[2]}, {$_par[3]}, {$_par[4]} ); <br>";
  }
  return $_;

}