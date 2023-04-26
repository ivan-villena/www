
/* Listados */
function listado( $lib, $ide, $tip, $dat ){

  let $ = Doc.var($dat);

  switch( $lib ){
  /* Encantamiento del Sueño... */
  case 'enc': 
    switch( $ide ){
    // libro del kin
    case 'kin':

      if( $App.dom.dat.var ) $.lis = $App.dom.dat.var.nextElementSibling;

      $.res = $App.dom.dat.var.querySelector('.hol-kin');

      if( $.val = Num.val( $App.dom.dat.var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${Num.val($.val,3)}`;
      
      switch($tip){
      case 'val':
        $.res.innerHTML = $.kin_ide ? $.lis.querySelector(`${$.kin_ide} > .hol-kin`).innerHTML : "";
        break;
      case 'nav': 
        if( $.kin_ide ) location.href = location.href.split('#')[0] + $.kin_ide;
        break;
      case 'fin': 
        $.res.innerHTML = '';
        break;
      }
      break;
    }
    break;
  }
}