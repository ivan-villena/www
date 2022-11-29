// WINDOW
'use strict';

// Sincronario
class hol_ope {

  // proceso diario
  static dia( $dat ){
    // operador : fecha + sincronario
    let $ = doc.var($dat);
    
    $.uri = `${$api_app.uri.esq}/ope/${ $api_app.uri.cab == 'ope' ? $api_app.uri.art : 'kin_tzo' }`;
    
    // calendario gregoriano
    if( $api_app.var.classList.contains('fec') ){
      
      if( $.fec = $api_app.var.querySelector('[name="fec"]').value ){

        arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
      }
      else{
        alert('La fecha del calendario es inválida...')
      }
    }
    // sincronario
    else if( $api_app.var.classList.contains('sin') ){
      $.atr = {};
      $.hol = [];
      $.val = true;
      ['gal','ani','lun','dia'].forEach( $v => {

        $.atr[$v] = $api_app.var.querySelector(`[name="${$v}"]`).value;

        if( !$.atr[$v] ){ 
          return $.val = false;          
        }
        else{ 
          $.hol.push($.atr[$v]) 
        }
      });
      if( !!$.val ){

        arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
      }
      else{
        alert('La fecha del sincronario es inválida...')
      }
    }
    
  }
}