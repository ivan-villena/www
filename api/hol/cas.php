<?php 

  function hol_cas(){
    $_ = "";
    foreach( api_hol::_('cas') as $_cas ){
      // armo nombre
      $_arm = api_hol::_('arm',$_cas->pos_arm);
      $_ton = api_hol::_('ton',$_cas->ton);
      $nom = api_tex::let_pal($_arm->des_col)." $_ton->nom";
      // armo descripcion
      $_ond = api_hol::_('cas_ond',$_cas->ond);
      $_dim = api_hol::_('ton_dim',$_ton->dim);
      $des = "$_arm->des_pod $_dim->nom $_ton->des_acc_lec $_ton->des_car para $_ond->des.";
      $_ .= "
      UPDATE `hol_cas` SET 
        `nom` = '$nom',
        `des` = '$des'
      WHERE 
        `ide` = $_cas->ide;<br>";
    }
    return $_;
  }