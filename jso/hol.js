// WINDOW
'use strict';

// Holon : ns.ani.lun.dia:kin
class _hol {

  // getter
  static _( $ide, $val ){
    let 
      $_ = [], 
      $est = `hol_${$ide}`;
    
    if( $_api[$est] === undefined ){
      // pido datos
      // .. vuelvo a llamar esta funcion
    }
    if( !!($val) ){
      $_ = $val;
      switch( $ide ){
      case 'fec':
        // calculo fecha
        break;
      default:
        if( typeof($val) != 'object' ){

          if( Number($val) ) $val = parseInt($val)-1;

          $_ = $_api[$est] && !!($_api[$est][$val]) ? $_api[$est][$val] : {};
        }
        break;
      }
    }
    else{
      $_ = $_api[$est] ? $_api[$est] : [];
    }
    return $_;
  }
}

// Bibliografìa
class _hol_bib {
  
  static ide( $tip, $dat, $ope ){

    let $ = _doc_val.var($dat);

    switch( $tip ){
    case 'ver':
      $.lis = $_app.var.nextElementSibling;
      $.ope = $_app.var.querySelector(`[name="ope"]`);
      $.val = $_app.var.querySelector(`[name="val"]`);
      $.tot = $_app.var.querySelector(`[name="tot"]`);

      if( $.val.value ){
        // oculto todo
        $.lis.querySelectorAll(`tbody > tr:not(.${DIS_OCU})`).forEach( $ite => $ite.classList.add(DIS_OCU) );
        // recorro y ejecuto filtro
        $.lis.querySelectorAll(`tbody > tr > td[data-atr="nom"]`).forEach( $ite => {

          if( _dat.ver($ite.innerText, $.ope.value, $.val.value) ) $ite.parentElement.classList.remove(DIS_OCU);
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
  // Encantamiento del sueño
  static enc( $atr, $dat, $ope ){

    let $ = _doc_val.var($dat);

    if( $_app.var ) $.lis = $_app.var.nextElementSibling;

    switch( $atr ){
    // libro del kin
    case 'kin': 
      $.val = _num.val( $_app.var.querySelector('[name="ide"]').value );
      if( $.val ) $.kin_ide = `#kin-${_num.val($.val,3)}`;

      if( !$ope ){        
        $.res = $_app.var.querySelector('.hol-kin');
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
        }
      }     
      break;
    }
  }
}
// Diario
class _hol_dia {
}
// Usuario
class _hol_usu {
}

// Valores
class _hol_val {

  // proceso valores 
  static fec( $dat ){
    // operador : fecha + sincronario
    let $ = _doc_val.var($dat);

    if( !$_api.app_uri.cab || !['tab','inf'].includes($_api.app_uri.cab) ){
      $_api.app_uri.cab = 'tab';
      $_api.app_uri.art = 'kin-tzo';
    }
    
    $.uri = _app_uri,val();

    // calendario gregoriano
    if( ( $.ope = $_app.var.getAttribute('ide') ) == 'fec' ){
      
      if( $.fec = $_app.var.querySelector('[name="fec"]').value ){

        _arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
      }
      else{
        alert('La fecha del calendario es inválida...')
      }
    }
    // sincronario
    else if( $.ope == 'sin' ){
      $.atr = {};
      $.hol = [];
      $.val = true;
      ['gal','ani','lun','dia'].forEach( $v => {

        $.atr[$v] = $_app.var.querySelector(`[name="${$v}"]`).value;

        if( !$.atr[$v] ){ 
          return $.val = false;          
        }
        else{ 
          $.hol.push($.atr[$v]) 
        }
      });
      if( !!$.val ){

        _arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
      }
      else{
        alert('La fecha del sincronario es inválida...')
      }
    }
  }
}
// Descripción
class _hol_des {
}
// Ficha
class _hol_fic {
}

// Listado
class _hol_lis {
}
// Tablero
class _hol_tab {
}
// Informe
class _hol_inf {
}