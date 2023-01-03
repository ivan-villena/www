

class hol_bib {

  /* Encantamiento del Sueño... */
  static enc( $ide, $tip, $dat ){
    
    let $ = api_doc.var($dat);

    switch( $ide ){
    // libro del kin
    case 'kin':

      if( $dom.dat.var ) $.lis = $dom.dat.var.nextElementSibling;

      $.res = $dom.dat.var.querySelector('.hol-kin');

      if( $.val = api_num.val( $dom.dat.var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${api_num.val($.val,3)}`;
      
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
  }

}