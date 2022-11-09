// WINDOW
'use strict';
// Artículos
class hol_art {

  // glosario
  static ide( $tip, $dat, $ope ){

    let $ = app.var($dat);

    switch( $tip ){
    case 'ver':
      $.lis = $_app.ope.var.nextElementSibling;
      $.ope = $_app.ope.var.querySelector(`[name="ope"]`);
      $.val = $_app.ope.var.querySelector(`[name="val"]`);
      $.tot = $_app.ope.var.querySelector(`[name="tot"]`);

      if( $.val.value ){
        // oculto todo
        $.lis.querySelectorAll(`tbody > tr:not(.${DIS_OCU})`).forEach( $ite => $ite.classList.add(DIS_OCU) );
        // recorro y ejecuto filtro
        $.lis.querySelectorAll(`tbody > tr > td[data-atr="nom"]`).forEach( $ite => {

          if( api_dat.ver($ite.innerText, $.ope.value, $.val.value) ) $ite.parentElement.classList.remove(DIS_OCU);
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