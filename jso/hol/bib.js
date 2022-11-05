// WINDOW
'use strict';

// Bibliografìa
class _hol_bib {

  // Encantamiento del sueño
  static enc( $atr, $dat, $ope ){

    let $ = _app.var($dat);

    if( $_app.ope.var ) $.lis = $_app.ope.var.nextElementSibling;
    
    switch( $atr ){
    // libro del kin
    case 'kin':
      $.res = $_app.ope.var.querySelector('.hol-kin');
      if( $.val = _num.val( $_app.ope.var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${_num.val($.val,3)}`;
      
      if( !$ope ){                
        if( $.kin_ide ){
          $.res.innerHTML = $.lis.querySelector(`${$.kin_ide} > .hol-kin`).innerHTML;
        }else{
          $.res.innerHTML = '';
        }
      }
      else{
        switch($ope){
        case 'nav': 
          if( $.kin_ide ) location.href = location.href.split('#')[0] + $.kin_ide;
          break;
        case 'fin': 
          $.res.innerHTML = '';
          break;
        }
      }     
      break;
    }
  }
}