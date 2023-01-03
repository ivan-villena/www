

function hol_ini(){

  // operadores
  if( ['kin','psi'].includes($sis_log.uri.cab) ){
    
    // inicializo: tablero + listado
    api_est.tab_ini('api_hol');
    api_est.lis_ini();
  }
}

// proceso diario
function hol_dia( $dat ){

  // operador : fecha + sincronario
  let $ = api_dat.var($dat);
  
  $.uri_cab = ['kin','psi'].includes($sis_log.uri.cab) ? $sis_log.uri.cab : 'kin';
  $.uri_art = $sis_log.uri.art ? $sis_log.uri.art : 'tzo';

  $.uri = `${$sis_log.uri.esq}/${$.uri_cab}/${$.uri_art}`;
  
  // calendario gregoriano
  if( $dom.dat.var.classList.contains('fec') ){
    
    if( $.fec = $dom.dat.var.querySelector('[name="fec"]').value ){

      api_arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
    }
    else{
      alert('La fecha del calendario es inválida...')
    }
  }
  // sincronario
  else if( $dom.dat.var.classList.contains('sin') ){
    $.atr = {};
    $.hol = [];
    $.val = true;
    ['gal','ani','lun','dia'].forEach( $v => {

      $.atr[$v] = $dom.dat.var.querySelector(`[name="${$v}"]`).value;

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
