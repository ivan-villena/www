// WINDOW
'use strict';

// Artículos
class hol_dat {

  // glosario
  static ide( $tip, $dat, $ope ){

    let $ = doc.var($dat);

    switch( $tip ){
    case 'ver':
      $.lis = $api_doc._var.nextElementSibling;
      $.ope = $api_doc._var.querySelector(`[name="ope"]`);
      $.val = $api_doc._var.querySelector(`[name="val"]`);
      $.tot = $api_doc._var.querySelector(`[name="tot"]`);

      if( $.val.value ){
        // oculto todo
        $.lis.querySelectorAll(`tbody > tr:not(.${DIS_OCU})`).forEach( $ite => $ite.classList.add(DIS_OCU) );
        // recorro y ejecuto filtro
        $.lis.querySelectorAll(`tbody > tr > td[data-atr="nom"]`).forEach( $ite => {

          if( val.ope_ver($ite.innerText, $.ope.value, $.val.value) ) $ite.parentElement.classList.remove(DIS_OCU);
        });
      }// muestro todo
      else{
        $.lis.querySelectorAll(`tbody > tr.${DIS_OCU}`).forEach( $ite => $ite.classList.remove(DIS_OCU) );
      }
      // actualizo cuenta
      $.tot.innerHTML = $.lis.querySelectorAll(`tbody > tr:not(.${DIS_OCU})`).length;

      break;
    }
  }
}