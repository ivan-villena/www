<?php 

  function dat(){
    $_ = "";
    foreach( Dat::_('hol.cas') as $Cas ){
      // armo nombre
      $Arm = Dat::_('hol.arm',$Cas->pos_arm);
      $Ton = Dat::_('hol.ton',$Cas->ton);
      $nom = Tex::let_pal($Arm->des_col)." $Ton->nom";
      // armo descripcion
      $_ond = Dat::_('hol.cas_ond',$Cas->ond);
      $_dim = Dat::_('hol.ton_dim',$Ton->dim);
      $des = "$Arm->des_pod $_dim->nom $Ton->des_acc_lec $Ton->des_car para $_ond->des.";
      $_ .= "
      UPDATE `hol-cas` SET 
        `nom` = '$nom',
        `des` = '$des'
      WHERE 
        `ide` = $Cas->ide;<br>";
    }
    return $_;
  }