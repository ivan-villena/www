// WINDOW
'use strict';

// Dato : esq.est[ide].atr
class api_dat {
 
  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = api_app.est('dat',$ide,'dat');

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        default:
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];        
          break;
        }        
      }
    }

    return $_;
  }
  
  // Variable : form > .dov_var > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $dom.dat.var = api_ele.val_ver($dat,{'eti':'form'});
      $.var_ide = $dat.getAttribute('name');
    }
    else{
      switch( $tip ){
      case 'mar':
        if( $ope ){
          $dat.parentElement.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e.classList.remove($ope) );
          $dat.classList.add($ope);
        }
        break;
      case 'tog':
        if( $ope ){
          $dat.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e != $dat && $e.classList.remove($ope) );
          $dat.classList.toggle($ope);
        }
        break;
      }
    }
    return $;
  }// toggles por : form > fieldsets > ...
  static var_tog( $ide ){
    api_lis.val_cod( api_ele.val_ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }  
}