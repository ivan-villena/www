// WINDOW
'use strict';

// consola
function sis_adm( $tip, $dat, $val, ...$opc ){
  
  let $ = api_dat.var($dat);
  
  // -> desde form : vacío resultados previos
  if( $sis_app.dat.var && ( $.res = $sis_app.dat.var.querySelector('.ope_res') ) ){ 

    sis_dom.eli($.res);
  }
  // -> desde menu : capturo form
  else if( $dat.nodeName && $dat.nodeName == 'A' ){

    $sis_app.dat.var = $dat.parentElement.nextElementSibling.querySelector(`.ide-${$tip}`);
  }
  
  switch( $tip ){
  // peticiones
  case 'aja':
    $.lis = $sis_app.dat.var.querySelector(`nav.lis`);
    sis_dom.eli($.lis);
    $sis_app.log.php.forEach( $log => {
      $.ver = document.createElement('a'); 
      $.ver.href = $log;
      $.ver.innerHTML = api_tex.let($log); 
      $.ver.target = '_blank'; 
      $.lis.appendChild($.ver);
    });
    break;
  // iconos
  case 'ico':
    $.lis = $sis_app.dat.var.querySelector(`ul.lis`);
    if( !$val ){
      // limpio listado
      sis_dom.eli($.lis);
      for( let $ico in ( $._ico = api_fig._('ico') ) ){ 
        $ico = $._ico[$ico];
        $.ico = document.createElement('span');
        $.ico.classList.add('fig_ico');
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
        api_lis.val_cod($.lis.children).forEach( $e => 
          $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU) 
        );
      }
      else{
        api_lis.val_cod($.lis.children).forEach( $e => {

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
    $.cod = $sis_app.dat.var.querySelector('[name="cod"]').value;
    if( $.cod ){

      api_eje.val( ['sis_sql::dec', [ $.cod ] ], $res => {
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
          $.htm.innerHTML = api_tex.let($res);
          $.res.appendChild($.htm);
        }  
      });
    }
    break;
  // servidor
  case 'php':    
    $.val = $sis_app.dat.var.querySelector('pre.ope_res');
    $.val.classList.add(DIS_OCU);
    $.val.innerText = '';
    $.res.classList.add(DIS_OCU);
    $.htm = $sis_app.dat.var.querySelector('[name="htm"]').checked;
    if( $.ide = $sis_app.dat.var.querySelector('[name="ide"]').value ){
      api_eje.val([ $.ide, eval(`[${$sis_app.dat.var.querySelector('[name="par"]').value}]`) ], $res => {
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
    $.cod = $sis_app.dat.var.querySelector('[name="cod"]');

    try{

      $.val = eval($.cod.value);

      $.dat_tip = api_dat.tip($.val);

      if( $.dat_tip.dat == 'obj' ){

        $.res.appendChild( app_var.obj('val',$.val) );
      }
      else if( $.dat_tip.dat == 'eje' ){

        $.res.innerHTML = api_tex.let( $.val.toString() );
      }
      else if( ![undefined,NaN,null,true,false].includes($.val) ){

        $.res.innerHTML = api_tex.let( $.val );
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
    // consulta por query
    case 'val':

      $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) );

      $dat.nextElementSibling.classList.add(FON_SEL);

      $.res = $sis_app.dat.var.querySelector('div.ele');

      sis_dom.eli($.res);

      $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');

      $.res.innerHTML = api_ele.var('eti',document.querySelector($.ver));

      break;
    // Listado de elementos resultante
    case 'cod':
      $.res = $sis_app.dat.var.querySelector('div.ele_nod');          

      sis_dom.eli($sis_app.dat.var.querySelector('div.ele'));

      sis_dom.eli($.res);

      $.cod = $sis_app.dat.var.querySelector('[name="cod"]');

      $.val = document.querySelectorAll($.cod.value);

      $.tit = document.createElement('h4');
      $.tit.innerHTML = 'Listado';
      $.tex = document.createElement('p');
      $.tex.innerHTML = api_tex.let(`Total: ${$.val.length}`);        
      $.res.appendChild($.tit);
      $.res.appendChild($.tex);
      // genero elemento
      $.res.appendChild( api_ele.var_val($.val)[1] );
      break;
    }
    break;        
  }

  return $;
}