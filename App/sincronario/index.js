// WINDOW
'use strict';

// inicializo pagina
function inicio(){

  // operadores
  if( $App.Uri.cab == 'tablero' ){
    // tablero + listado
    Doc_Dat.tab_ini();
    Doc_Dat.lis_ini();
  }
}

// proceso diario
function diario( $dat ){

  // operador : fecha + sincronario
  let $ = Doc_Ope.var($dat);

  // peticion: el articulo debe estar definido
  $.uri = `${$App.Uri.esq}/${$App.Uri.cab}/${$App.Uri.art}`;
  
  // calendario gregoriano
  if( $Doc.Ope.var.classList.contains('fec') ){
    
    if( $.fec = $Doc.Ope.var.querySelector('[name="fec"]').value ){

      Arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
    }
    else{
      alert('La fecha del calendario es inválida...')
    }
  }
  // sincronario
  else if( $Doc.Ope.var.classList.contains('sin') ){
    $.atr = {};
    $.hol = [];
    $.val = true;
    ['gal','ani','lun','dia'].forEach( $v => {

      $.atr[$v] = $Doc.Ope.var.querySelector(`[name="${$v}"]`).value;

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

// menú de usuario
function usuario( opcion, elemento ){

  Doc_Ope.nav_bot( elemento, 'App::htm', [ `sincronario/usuario/${opcion}` ] );
}