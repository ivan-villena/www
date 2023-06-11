<?php

if( !isset($Doc) ) exit;

// - cargo Diario: valor por peticion => { hol/$cab/$art/$ide=val }
$Sincronario = new Sincronario( !empty($_SESSION['hol-val']) ? Sincronario::val_cod($_SESSION['hol-val']) : date('Y/m/d') );

// proceso fecha para: Tableros + Diario + Kin Planetario
if( !empty($Uri->val) ){

  $uri_val = explode('=',$Uri->val);

  if( in_array($uri_val[0],[ 'fec', 'sin' ])  ){

    // Actualizo fecha del sincronario
    $Sincronario->Val = Sincronario::val($uri_val[1]);
    
    // actualizo fecha del sistema
    $_SESSION['hol-val'] = $uri_val[1];
  }
  else{
    
    $Sincronario->Val[ $uri_val[0] ] = $uri_val[1];
  }
}
