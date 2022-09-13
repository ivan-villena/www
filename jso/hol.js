// WINDOW
'use strict';

// sincronario
class _hol {

  constructor( $dat ){

    // datos propios
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }  
  // getter
  static _( $ide, $val ){
    let $_=[], $est = `_${$ide}`;
    
    if( $_hol[$est] === undefined ){
      // pido datos
      // .. vuelvo a llamar esta funcion
    }
    if( !!($val) ){
      $_ = $val;
      switch( $ide ){
      case 'fec':
        
        break;
      default:
        if( typeof($val) != 'object' ){

          if( Number($val) ) $val = parseInt($val)-1;

          $_ = $_hol[$est] && !!($_hol[$est][$val]) ? $_hol[$est][$val] : {};
        }
        break;
      }
    }
    else{
      $_ = $_hol[$est] ? $_hol[$est] : [];
    }
    return $_;
  }
}

class _hol_val {

  // proceso valores 
  static dia( $dat ){
    // operador : fecha + sincronario
    let $ = _doc.var($dat);

    if( !$_api._uri.cab || !['tab','inf'].includes($_api._uri.cab) ){
      $_api._uri.cab = 'tab';
      $_api._uri.art = 'kin-tzo';
    }
    
    $.uri = $_api.uri();

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

class _hol_des {
}

class _hol_fic {
}

class _hol_inf {
}

class _hol_lis {
}

class _hol_est {
}

class _hol_tab {
}