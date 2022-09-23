// WINDOW
'use strict';

// administrador
function _adm( $tip, $dat, $val, ...$opc ){
  
  let $=_doc_val.var($dat);
  
  // -> desde form : vacío resultados previos
  if( $_app.var && ( $.res = $_app.var.querySelector('.ope_res') ) ){ 

    _ele.eli($.res);
  }
  // -> desde menu : capturo form
  else if( $dat.nodeName && $dat.nodeName == 'A' ){      

    $_app.var = $dat.parentElement.nextElementSibling.querySelector(`[data-ide="${$tip}"]`);
  }
  
  switch( $tip ){
  // peticiones
  case 'aja':
    $.lis = $_app.var.querySelector(`nav.lis`);
    _ele.eli($.lis);
    $_api.log.php.forEach( $log => {
      $.ver = document.createElement('a'); 
      $.ver.href = $log;
      $.ver.innerHTML = _doc.let($log); 
      $.ver.target='_blank'; 
      $.lis.appendChild($.ver);
    });                          
    break;
  // iconos
  case 'ico':
    $.lis = $_app.var.querySelector(`ul.lis`);
    if( !$val ){
      // limpio listado
      _ele.eli($.lis);
      for( let $ico in ( $._api_ico = $_api.doc_ico ) ){ 
        $ico = $._api_ico[$ico];
        $.ico = document.createElement('span');
        $.ico.classList.add('ico','mar_der-1','material-icons-outlined');
        $.ico.innerHTML = $ico.val;
        $.nom = document.createElement('p');
        $.nom.innerHTML = `
          <c>-</c> <b class='ide'>${$ico.ide}</b> <c>=</c> 
          ${$ico.val}
        `;
        $.ite = document.createElement('li');
        for( const $pad in $.ele = { 'ite':['ico','nom'], 'lis':['ite'] } ){
          $.ele[$pad].forEach( $e => $[$pad].appendChild($[$e]) );
        }
        $.lis.appendChild($.ite);        
      }
    }
    else{
      if( !$dat.value ){
        _lis.val($.lis.children).forEach( $e => 
          $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU) 
        );
      }
      else{
        _lis.val($.lis.children).forEach( $e => {

          if( _dat.ope_ver( $e.querySelector('.ide').innerHTML, '^^', $dat.value ) ){
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU);
          }
          else if( !$e.classList.contains(DIS_OCU) ){
            $e.classList.add(DIS_OCU);
          }
        });
      }
    }
    break;
  // base de datos
  case 'sql':
    $.cod = $_app.var.querySelector('[name="cod"]').value;
    if( $.cod ){

      _eje.val( ['_sql::dec', [ $.cod ] ], $res => {
        // pido tabla
        if( Array.isArray($res) ){

          $.res.appendChild( _doc_lis.tab($res) );
        }// errores: html
        else if( typeof($res)=='object' ){

          $.res.innerHTML = $res._err;
        }// literales: mensajes
        else{                        
          $.htm = document.createElement('p');
          $.htm.classList.add('sql');
          $.htm.innerHTML = _doc.let($res);
          $.res.appendChild($.htm);
        }  
      });
    }
    break;
  // servidor
  case 'php':
    $.val = $_app.var.querySelector('pre.ope_res');
    $.val.innerText = '';        
    $.htm = $_app.var.querySelector('[name="htm"]').checked;
    if( $.ide = $_app.var.querySelector('[name="ide"]').value ){          
      _eje.val([ $.ide, eval(`[${$_app.var.querySelector('[name="par"]').value}]`) ], $res => {
        if( $.htm ){
          $.res.innerHTML = !! $res._ ? $res._ : `${_doc.let( JSON.stringify($res) )}`;
        }else{
          $.val.innerText = !! $res._ ? $res._ : JSON.stringify($res);
        }
      });
    }
    break;
  // terminal
  case 'jso':
    $.cod = $_app.var.querySelector('[name="cod"]');

    try{

      $.val = eval($.cod.value);

      $.dat_tip = _dat.tip($.val);

      if( $.dat_tip.dat == 'obj' ){

        $.res.appendChild( _doc_obj.ope('val',$.val) );
      }
      else if( $.dat_tip.dat == 'eje' ){

        $.res.innerHTML = _doc.let( $.val.toString() );
      }
      else if( ![undefined,NaN,null,true,false].includes($.val) ){

        $.res.innerHTML = _doc.let( $.val );
      }
      else{

        $.res.innerHTML = `<c>${$.val}</c>`;
      }
    }
    catch( $err ){
      $.err = document.createElement('b');
      $.err.classList.add('err');
      $.err.innerHTML = $err;
      $.res.appendChild($.err);
    }
    break;
  // documento
  case 'htm':
    switch( $val ){
    case 'cod':
      $.res = $_app.var.querySelector('div.nod');          
      _ele.eli($_app.var.querySelector('div.ele'));
      _ele.eli($.res);
      $.cod = $_app.var.querySelector('[name="cod"]');

      $.val = document.querySelectorAll($.cod.value);

      $.tit = document.createElement('h4');
      $.tit.innerHTML = 'Listado';
      $.tex = document.createElement('p');
      $.tex.innerHTML = _doc.let(`Total: ${$.val.length}`);        
      $.res.appendChild($.tit);
      $.res.appendChild($.tex);
      // genero elemento
      $.res.appendChild( _doc_ele.val($.val)[1] );
      break;
    case 'val':
      $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )
      $dat.nextElementSibling.classList.add(FON_SEL);
      $.res = $_app.var.querySelector('div.ele');
      _ele.eli($.res);
      $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');            
      $.res.innerHTML = _doc_ele.ope('eti',document.querySelector($.ver));
      break;
    }
    break;        
  }

  return $;
}