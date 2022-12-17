
// libro del kin
function kin( $dat, $ope ){
  
  let $ = api_doc.var($dat);

  if( $api_doc._var ) $.lis = $api_doc._var.nextElementSibling;

  $.res = $api_doc._var.querySelector('.hol-kin');

  if( $.val = api_num.val( $api_doc._var.querySelector('[name="ide"]').value ) ) $.kin_ide = `#kin-${api_num.val($.val,3)}`;
  
  if( !$ope ){                
    if( $.kin_ide ){
      $.res.innerHTML = $.lis.querySelector(`${$.kin_ide} > .hol-kin`).innerHTML;
    }else{
      $.res.innerHTML = '';
    }
  }
  else{
    switch($ope){
    case 'nav': 
      if( $.kin_ide ) location.href = location.href.split('#')[0] + $.kin_ide;
      break;
    case 'fin': 
      $.res.innerHTML = '';
      break;
    }
  }     
}