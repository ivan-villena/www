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
  // imagen : _/hol
  static ima( $est, $dat, $ele ){

    return _doc.ima('api',`hol_${$est}`,$dat,$ele);
  }
}

class _hol_val {

  // proceso valores
  static fec( $dat ){
    // operador : fecha + sincronario
    let $ = _doc_val.var($dat);

    if( !$_api.app_uri.cab || !['tab','inf'].includes($_api.app_uri.cab) ){
      $_api.app_uri.cab = 'tab';
      $_api.app_uri.art = 'kin-tzo';
    }
    
    $.uri = $_app.uri_val();

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