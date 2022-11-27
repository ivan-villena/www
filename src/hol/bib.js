// WINDOW
'use strict';

// Bibliografìa
class hol_bib {

  // Encantamiento del sueño
  static enc( $atr, $dat, $ope ){

    let $ = doc.var($dat);

    if( $api_app.var ) $.lis = $api_app.var.nextElementSibling;
    
    switch( $atr ){
    // libro del kin
    case 'kin':
      $.res = $api_app.var.querySelector('.hol-kin');
      if( $.val = num.val( $api_app.var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${num.val($.val,3)}`;
      
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