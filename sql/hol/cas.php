<?php 

  function hol_cas(){
    $_ = "";
    foreach( api_hol::_('cas') as $_cas ){
      $_arm = api_hol::_('arm',$_cas->pos_arm);
      $_ton = api_hol::_('ton',$_cas->ton);
      $_ .= "
      UPDATE `hol_cas` SET 
        `nom` = '".api_tex::let_pal($_arm->des_col)." $_ton->nom'
      WHERE 
        `ide` = $_cas->ide;<br>";
    }
    return $_;
  }


