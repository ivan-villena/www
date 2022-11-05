// WINDOW
'use strict';

// Sincronario
class _hol_app {

  constructor( $dat ){
    
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    }

    // inicializo tablero con clase principal
    if( $_app.uri.cab == 'ope' ){

      $_app.tab.cla = ( $_app.tab.dep = $_app.tab.lis.querySelector('.app_ope > .app_tab.par') ) 
        ? '.app_ope > .app_tab.par > [class*="pos-"]' 
        : '.app_ope'
      ;
      // muestro diario
      let $ = {};
      // ( $.bot_ini = $_app.ope.bot.querySelector('.ico.fec_val') ) && $.bot_ini.click();
    }
  }
  // proceso diario
  static dia( $dat ){
    // operador : fecha + sincronario
    let $ = _app.var($dat);
    
    $.uri = `${$_app.uri.esq}/ope/${ $_app.uri.cab == 'ope' ? $_app.uri.art : 'kin_tzo' }`;
    // calendario gregoriano
    if( $_app.ope.var.classList.contains('fec') ){
      
      if( $.fec = $_app.ope.var.querySelector('[name="fec"]').value ){

        _arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
      }
      else{
        alert('La fecha del calendario es inválida...')
      }
    }
    // sincronario
    else if( $_app.ope.var.classList.contains('sin') ){
      $.atr = {};
      $.hol = [];
      $.val = true;
      ['gal','ani','lun','dia'].forEach( $v => {

        $.atr[$v] = $_app.ope.var.querySelector(`[name="${$v}"]`).value;

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