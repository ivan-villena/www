

// inicializo pagina
function inicio(){

  // operadores
  if( ['kin','psi'].includes($App.rec.uri.cab) ){
    
    // tablero + listado
    Dat.tab_ini('Hol');
    Dat.lis_ini();
  }
}

// proceso diario
function diario( $dat ){

  // operador : fecha + sincronario
  let $ = Doc.var($dat);
  
  $.uri_cab = ['kin','psi'].includes($App.rec.uri.cab) ? $App.rec.uri.cab : 'kin';

  $.uri_art = $App.rec.uri.art ? $App.rec.uri.art : 'tzolkin';

  $.uri = `${$App.rec.uri.esq}/${$.uri_cab}/${$.uri_art}`;
  
  // calendario gregoriano
  if( $App.dom.dat.var.classList.contains('fec') ){
    
    if( $.fec = $App.dom.dat.var.querySelector('[name="fec"]').value ){

      Arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
    }
    else{
      alert('La fecha del calendario es inválida...')
    }
  }
  // sincronario
  else if( $App.dom.dat.var.classList.contains('sin') ){
    $.atr = {};
    $.hol = [];
    $.val = true;
    ['gal','ani','lun','dia'].forEach( $v => {

      $.atr[$v] = $App.dom.dat.var.querySelector(`[name="${$v}"]`).value;

      if( !$.atr[$v] ){ 
        return $.val = false;          
      }
      else{ 
        $.hol.push($.atr[$v]) 
      }
    });
    if( !!$.val ){

      Arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
    }
    else{
      alert('La fecha del sincronario es inválida...')
    }
  }
}

// Artículos


// biblioteca
