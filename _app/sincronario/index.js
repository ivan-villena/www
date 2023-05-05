

// inicializo pagina
function inicio(){

  // operadores
  if( $App.Uri.cab == 'tablero' ){
    
    // tablero + listado
    Dat.tab_ini('Hol');
    Dat.lis_ini();
  }
}

// proceso diario
function diario( $dat ){

  // operador : fecha + sincronario
  let $ = Dat.var($dat);

  // peticion: el articulo debe estar definido
  $.uri = `${$App.Uri.esq}/${$App.Uri.cab}/${$App.Uri.art}`;
  
  // calendario gregoriano
  if( $App.Dom.dat.var.classList.contains('fec') ){
    
    if( $.fec = $App.Dom.dat.var.querySelector('[name="fec"]').value ){

      Arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
    }
    else{
      alert('La fecha del calendario es inválida...')
    }
  }
  // sincronario
  else if( $App.Dom.dat.var.classList.contains('sin') ){
    $.atr = {};
    $.hol = [];
    $.val = true;
    ['gal','ani','lun','dia'].forEach( $v => {

      $.atr[$v] = $App.Dom.dat.var.querySelector(`[name="${$v}"]`).value;

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
