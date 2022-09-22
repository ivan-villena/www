<?php

  if( !isset($tip) ) $tip = 'usu';
  
  $win = "";

  switch( $tip ){
    // datos del usuario
    case 'usu':
      $esq = 'api'; 
      $est = 'usu';

      $_kin = _hol::_('kin',$_usu->kin);
      $_psi = _hol::_('psi',$_usu->psi);
      
      $win = [ 'ico' => "ses_usu", 'nom' => "Cuenta de Usuario", 'htm' => "
      
        <form class='api_dat' data-esq='{$esq}' data-est='{$est}'>

          <fieldset class='ren'>

            "._doc_val::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_usu->$atr  ], 'eti')."

            "._doc_val::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_usu->$atr  ], 'eti')."                        
          
          </fieldset>

          <fieldset class='ren'>

            "._doc_val::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$_usu->$atr  ],'eti')."

            "._doc_val::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."

          </fieldset>

        </form>"
      ];      
      break;
    // inicio de sesion por usuario
    case 'ini':
      $win = [ 'ico' => "ses_ini", 'nom' => "Loggin", 'htm' => "
      
        <form class='api_dat' action=''>

          <fieldset>

            <label for=''>Email</label>
            <input type='mail'>

          </fieldset>

          <fieldset>

            <label for=''>Password</label>
            <input type='password'>

          </fieldset>

        </form>"
      ];
      break;
    // finalizo sesion del usuario
    case 'fin': 
      $win = [ 'ico' => "ses_ini", 'nom' => "Loggin", 'htm' => "

        <form class='api_dat' action=''>
        </form>"
      ];
      break;
  }

  // modales [] || url ""
  if( is_array($win) ){
    echo _app_win::art("ses_$tip",$win);
  }
  else{

  }
  