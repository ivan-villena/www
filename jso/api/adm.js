// WINDOW
'use strict';

// administrador
function api_adm( $tip, $dat, $val, ...$opc ){
  
  let $ = app.var($dat);
  
  // -> desde form : vacío resultados previos
  if( $_app.ope.var && ( $.res = $_app.ope.var.querySelector('.ope_res') ) ){ 

    api_ele.eli($.res);
  }
  // -> desde menu : capturo form
  else if( $dat.nodeName && $dat.nodeName == 'A' ){

    $_app.ope.var = $dat.parentElement.nextElementSibling.querySelector(`.ide-${$tip}`);
  }
  
  switch( $tip ){
  // peticiones
  case 'aja':
    $.lis = $_app.ope.var.querySelector(`nav.lis`);
    api_ele.eli($.lis);
    $_api.log.php.forEach( $log => {
      $.ver = document.createElement('a'); 
      $.ver.href = $log;
      $.ver.innerHTML = app.let($log); 
      $.ver.target='_blank'; 
      $.lis.appendChild($.ver);
    });                          
    break;
  // iconos
  case 'ico':
    $.lis = $_app.ope.var.querySelector(`ul.lis`);
    if( !$val ){
      // limpio listado
      api_ele.eli($.lis);
      for( let $ico in ( $._api_ico = $_api.app_ico ) ){ 
        $ico = $._api_ico[$ico];
        $.ico = document.createElement('span');
        $.ico.classList.add('ico');
        $.ico.classList.add('material-icons-outlined');
        $.ico.classList.add($ico.ide);
        $.ico.classList.add('mar_der-1');
        $.ico.innerHTML = $ico.val;
        
        $.nom = document.createElement('p');
        $.nom.innerHTML = `<c>-</c> <b class='ide'>${$ico.ide}</b> <c>=</c> ${$ico.val}`;

        $.ite = document.createElement('li');
        for( const $pad in $.ele = { 'ite':['ico','nom'], 'lis':['ite'] } ){
          $.ele[$pad].forEach( $e => $[$pad].appendChild($[$e]) );
        }
        $.lis.appendChild($.ite);        
      }
    }
    else{
      if( !$dat.value ){
        api_lis.val($.lis.children).forEach( $e => 
          $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU) 
        );
      }
      else{
        api_lis.val($.lis.children).forEach( $e => {

          if( api_dat.ver( $e.querySelector('.ide').innerHTML, '^^', $dat.value ) ){
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
    $.cod = $_app.ope.var.querySelector('[name="cod"]').value;
    if( $.cod ){

      api_eje.val( ['_sql::dec', [ $.cod ] ], $res => {
        // pido tabla
        if( Array.isArray($res) ){

          $.res.appendChild( app_est.lis($res) );
        }// errores: html
        else if( typeof($res)=='object' ){

          $.res.innerHTML = $res._err;
        }// literales: mensajes
        else{                        
          $.htm = document.createElement('p');
          $.htm.classList.add('sql');
          $.htm.innerHTML = app.let($res);
          $.res.appendChild($.htm);
        }  
      });
    }
    break;
  // servidor
  case 'php':    
    $.val = $_app.ope.var.querySelector('pre.ope_res');
    $.val.classList.add(DIS_OCU);
    $.val.innerText = '';
    $.res.classList.add(DIS_OCU);
    $.htm = $_app.ope.var.querySelector('[name="htm"]').checked;
    if( $.ide = $_app.ope.var.querySelector('[name="ide"]').value ){
      api_eje.val([ $.ide, eval(`[${$_app.ope.var.querySelector('[name="par"]').value}]`) ], $res => {
        if( $.htm ){
          $.res.innerHTML = $res;
          $.res.classList.remove(DIS_OCU);
        }else{
          $.val.innerText = $res;
          $.val.classList.remove(DIS_OCU);
        }
      });
    }
    break;
  // terminal
  case 'jso':
    $.cod = $_app.ope.var.querySelector('[name="cod"]');

    try{

      $.val = eval($.cod.value);

      $.dat_tip = api_dat.tip($.val);

      if( $.dat_tip.dat == 'obj' ){

        $.res.appendChild( app_var.obj('val',$.val) );
      }
      else if( $.dat_tip.dat == 'eje' ){

        $.res.innerHTML = app.let( $.val.toString() );
      }
      else if( ![undefined,NaN,null,true,false].includes($.val) ){

        $.res.innerHTML = app.let( $.val );
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
      $.res = $_app.ope.var.querySelector('div.nod');          
      api_ele.eli($_app.ope.var.querySelector('div.ele'));
      api_ele.eli($.res);
      $.cod = $_app.ope.var.querySelector('[name="cod"]');

      $.val = document.querySelectorAll($.cod.value);

      $.tit = document.createElement('h4');
      $.tit.innerHTML = 'Listado';
      $.tex = document.createElement('p');
      $.tex.innerHTML = app.let(`Total: ${$.val.length}`);        
      $.res.appendChild($.tit);
      $.res.appendChild($.tex);
      // genero elemento
      $.res.appendChild( app_var.ele_val($.val)[1] );
      break;
    case 'val':
      $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )
      $dat.nextElementSibling.classList.add(FON_SEL);
      $.res = $_app.ope.var.querySelector('div.ele');
      api_ele.eli($.res);
      $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');            
      $.res.innerHTML = app_var.ele('eti',document.querySelector($.ver));
      break;
    }
    break;        
  }

  return $;
}