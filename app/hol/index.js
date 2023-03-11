

function hol_ini(){

  // operadores
  if( ['kin','psi'].includes($sis_app.rec.uri.cab) ){
    
    // inicializo: tablero + listado
    api_dat.tab_ini('api_hol');
    api_dat.lis_ini();
  }
}

// proceso diario
function hol_dia( $dat ){

  // operador : fecha + sincronario
  let $ = sis_doc.var($dat);
  
  $.uri_cab = ['kin','psi'].includes($sis_app.rec.uri.cab) ? $sis_app.rec.uri.cab : 'kin';
  $.uri_art = $sis_app.rec.uri.art ? $sis_app.rec.uri.art : 'tzo';

  $.uri = `${$sis_app.rec.uri.esq}/${$.uri_cab}/${$.uri_art}`;
  
  // calendario gregoriano
  if( $sis_app.dat.var.classList.contains('fec') ){
    
    if( $.fec = $sis_app.dat.var.querySelector('[name="fec"]').value ){

      api_arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
    }
    else{
      alert('La fecha del calendario es inválida...')
    }
  }
  // sincronario
  else if( $sis_app.dat.var.classList.contains('sin') ){
    $.atr = {};
    $.hol = [];
    $.val = true;
    ['gal','ani','lun','dia'].forEach( $v => {

      $.atr[$v] = $sis_app.dat.var.querySelector(`[name="${$v}"]`).value;

      if( !$.atr[$v] ){ 
        return $.val = false;          
      }
      else{ 
        $.hol.push($.atr[$v]) 
      }
    });
    if( !!$.val ){

      api_arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
    }
    else{
      alert('La fecha del sincronario es inválida...')
    }
  }
}

// Artículos


// biblioteca
