<?php

  // cargo fechas
  $Cic = $Sincronario->Val;

  /* 
    Cargo objetos 
  */

  // ciclo nuevo sirio
  $Sir = Sincronario::dat('sir',$Cic);

  // anillo solar
  $Ani = Sincronario::dat('ani',$Cic);
  
  // orden cíclico
  $Psi = Dat::_('hol.psi',$Sincronario->Val['psi']);

  // orden sincrónico
  $Kin = Dat::_('hol.kin',$Sincronario->Val['kin']);
  $Sel = Dat::_('hol.sel',$Kin->arm_tra_dia);
  $Ton = Dat::_('hol.ton',$Kin->nav_ond_dia);

  // Holones
  $Sol_pla = Dat::_('hol.sol_pla',$Sel->sol_pla);