<?php 

  function dat(){
    $_ = "";
    foreach( Dat::_('hol.cas') as $_cas ){
      // armo nombre
      $_arm = Dat::_('hol.arm',$_cas->pos_arm);
      $_ton = Dat::_('hol.ton',$_cas->ton);
      $nom = Tex::let_pal($_arm->des_col)." $_ton->nom";
      // armo descripcion
      $_ond = Dat::_('hol.cas_ond',$_cas->ond);
      $_dim = Dat::_('hol.ton_dim',$_ton->dim);
      $des = "$_arm->des_pod $_dim->nom $_ton->des_acc_lec $_ton->des_car para $_ond->des.";
      $_ .= "
      UPDATE `hol-cas` SET 
        `nom` = '$nom',
        `des` = '$des'
      WHERE 
        `ide` = $_cas->ide;<br>";
    }
    return $_;
  }