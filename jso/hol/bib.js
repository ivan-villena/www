// WINDOW
'use strict';

// Bibliografìa
class hol_bib {

  // Encantamiento del sueño
  static enc( $atr, $dat, $ope ){

    let $ = app.var($dat);

    if( $_app.ope.var ) $.lis = $_app.ope.var.nextElementSibling;
    
    switch( $atr ){
    // libro del kin
    case 'kin':
      $.res = $_app.ope.var.querySelector('.hol-kin');
      if( $.val = api_num.val( $_app.ope.var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${api_num.val($.val,3)}`;
      
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