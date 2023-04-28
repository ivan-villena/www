<?php 

  function hol_cas(){
    $_ = "";
    foreach( Hol::_('cas') as $_cas ){
      // armo nombre
      $_arm = Hol::_('arm',$_cas->pos_arm);
      $_ton = Hol::_('ton',$_cas->ton);
      $nom = Tex::let_pal($_arm->des_col)." $_ton->nom";
      // armo descripcion
      $_ond = Hol::_('cas_ond',$_cas->ond);
      $_dim = Hol::_('ton_dim',$_ton->dim);
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