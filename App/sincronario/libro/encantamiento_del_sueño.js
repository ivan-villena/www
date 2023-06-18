// WINDOW
'use strict';


function libro_del_kin( $tip, $dat ){

  let $ = Doc_Ope.var($dat);

  if( $Doc.Ope.var ) $.lis = $Doc.Ope.var.nextElementSibling;

  $.res = $Doc.Ope.var.querySelector('.hol-kin');

  if( $.val = Num.val( $Doc.Ope.var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${Num.val($.val,3)}`;
  
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

}