// WINDOW
'use strict';

// articulos
class hol_dat {

  // glosario
  static ide( $tip, $dat, $ope ){

    let $ = dat.var($dat);

    switch( $tip ){
    case 'ver':
      $.lis = $api_app.var.nextElementSibling;
      $.ope = $api_app.var.querySelector(`[name="ope"]`);
      $.val = $api_app.var.querySelector(`[name="val"]`);
      $.tot = $api_app.var.querySelector(`[name="tot"]`);

      if( $.val.value ){
        // oculto todo
        $.lis.querySelectorAll(`tbody > tr:not(.${DIS_OCU})`).forEach( $ite => $ite.classList.add(DIS_OCU) );
        // recorro y ejecuto filtro
        $.lis.querySelectorAll(`tbody > tr > td[data-atr="nom"]`).forEach( $ite => {

          if( dat.ver($ite.innerText, $.ope.value, $.val.value) ) $ite.parentElement.classList.remove(DIS_OCU);
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