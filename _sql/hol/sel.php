<?php

function par_gui() {

  $_ = "DELETE FROM `hol-sel_par_gui`;";
  foreach( Dat::_('hol.sel') as $_sel ){
    $_par = explode(', ',$_sel->par_gui);
    $_ .= "INSERT INTO `hol-sel_par_gui` VALUES ( $_sel->ide, {$_par[0]}, {$_par[1]}, {$_par[2]}, {$_par[3]}, {$_par[4]} ); <br>";
  }
  return $_;

}