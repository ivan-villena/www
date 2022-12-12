<?php 

function hol_cas(){
  $_ = "";
  foreach( hol::_('cas') as $_cas ){
    $_arm = hol::_('arm',$_cas->pos_arm);
    $_ton = hol::_('ton',$_cas->ton);
    $_ .= "
    UPDATE `hol_cas` SET 
      `nom` = '".tex::let_pal($_arm->des_col)." $_ton->nom'
    WHERE 
      `ide` = $_cas->ide;<br>";
  }
  return $_;
}


