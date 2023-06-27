<?php

// cargo programa
$Sincronario = new Sincronario();

// proceso fecha para: Operador + Informes
if( !empty($Uri->val) ){

  $uri_val = explode('=',$Uri->val);

  // cargo fecha o valor del sincronario
  if( in_array($uri_val[0],[ 'fec', 'val' ])  ){

    // Actualizo fecha del sincronario
    $Sincronario->Val = Sincronario::val($uri_val[1]);
    
    // actualizo fecha del sistema
    $_SESSION['hol-val'] = $uri_val[1];
  }
  // cargo valor por estructura directa
  else{
    
    $Sincronario->Val[ $uri_val[0] ] = $uri_val[1];
  }
}

// - cargo Diario o valor por peticion => { sincronario/$cab/$art/$ide=val }
if( empty($Sincronario->Val) ) $Sincronario->Val = Sincronario::val( !empty($_SESSION['hol-val']) ? $_SESSION['hol-val'] : date('Y/m/d') );

// Cargo Programa
$Doc->Eje['app'] = "{ Val : ".Obj::val_cod( $Sincronario->Val )." }";

$Doc->Eje['ini'] = "";

// cargo modulos
if( in_array($Uri->cab,['libro']) ){

  require_once("./Api/Sincronario/Listado.php");
}