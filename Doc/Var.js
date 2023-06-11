// WINDOW
'use strict';

class Doc_Var {

  // Numero
  static num( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
  static num_act( $dat ){

    let $={};

    $.val = Num.val($dat.value);

    // excluyo bits
    if( $dat.type != 'text' ){

      // valido minimos y máximos
      if( ( $.min = Num.val($dat.min) ) && $dat.value && $.val < $.min ) $dat.value = $.val = $.min;    

      if( ( $.max = Num.val($dat.max) ) && $dat.value && $.val > $.max ) $dat.value = $.val = $.max;

      // relleno con ceros
      if( $dat.getAttribute('num_pad') && ( $.num_cue = $dat.maxlength ) ) $.num_pad = Num.val($.val,$.num_cue);

      // actualizo valores por rango
      if( $dat.type == 'range' ){

        if( $.val = $dat.parentElement.nextElementSibling ) $.val.innerHTML = $.num_pad ? $.num_pad : $dat.value;
      }
      // por entero o decimales
      else{

        if( $.num_pad ) $dat.value = $.num_pad;
      }      
    }
  }
  
  // Objeto
  static obj( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = Dat.tip($v);

      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-1');
      $.ite.innerHTML = `
        <q class='ide'>${$i}</q> <c class='sep'>:</c>
      `;
      if( ![undefined,NaN,null,true,false].includes($v) ){

        $.ite.innerHTML += Doc_Val.let( ( $.tip.dat=='obj' ) ? JSON.stringify($v) : $v.toString() ) ;          
      }
      else{
        $.ite.innerHTML += `<c>${$v}</c>`;
      }

      $_.appendChild($.ite);
    }

    return $_;      
  }

  // Elemento html
  static ele( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $ = {};

    switch( $tip ){
    // elemento por etiqueta      
    case 'eti':
      $.nom = $dat.nodeName.toLowerCase();
      $.fin=`<c></</c>/<b class='ide'>${$.nom}</b><c>></c>`;
      $.eti_htm = '';
      if( $dat.childNodes.length>0 ){ 
        Obj.pos($dat.childNodes).forEach( $e =>{
          if( $e.nodeName.toLowerCase() != '#text' ){ $.eti_htm += `
            <li>${Ele.var('eti',$e)}</li>`;
          }else{ $.eti_htm += `
            <li>${Doc_Val.let($e.textContent)}</li>`;
          }
        });
      }
      $.eti_atr=[];
      $dat.getAttributeNames().forEach( $atr => $.eti_atr.push(`
        <li class='mar_hor-1'>
          <p><b class='val'>${$atr}</b><c class='sep'>=</c><q>${$dat.getAttribute($atr)}</q></p>
        </li>`)
      );
      $_ =`
      <div class='ele_eti dat'>
        <p class='ini'>  
          <c class='sep'><</c>
          <b class='ide' onclick='ele.var_act(this,'ver');'>${$.nom}</b>
          <ul class='lis val -atr'>${$.eti_atr.join('')}</ul>
          <c class='sep'>></c>
          <span class='tog dis-ocu'>
            <c onclick='ele.var_act(this,'ver');' class='tog dis-ocu'>...</c>
            ${$.fin}
          </span>
        </p>`;
        if( !['input','img','br','hr'].includes($.nom) ){ $_ += `
          <ul class='lis mar_hor-3'>
            ${$.eti_htm}
          </ul>
          <p class='tog fin'>
            ${$.fin}
          </p>`;
        }$_ += `
      </div>`;
      break;      
    // herencia: body > ...
    case 'her':
      $.her = [];
      $.ele = $dat; 
      while( $.ele ){ 
        $.nom = $.ele.nodeName.toLowerCase();
        if( $.nom == '#document' || $.nom == 'html' ) break;

        $.tex = `<b class='ide'>${$.nom}</b>`;
        if( $.ele.id ){ 
          $.tex += `<c>#</c><b class='val'>${$.ele.id}</b>`;
        }
        else if( $.ele.name ){
          $.tex += `<c>[</c><b class='val'>${$.ele.name}</b><c>]</c>`;
        }
        if( $.ele.className){
          $.cla_lis = $.ele.className.split(' ').map( $ite => `<c>.</c><b class='val'>${$ite}</b>` );
          $.tex += `${$.cla_lis.join('')}`;
        }
        $.her.push($.tex);
        $.ele = ( $.ele.parentNode) ? $.ele.parentNode : false;
      }
      $_ = $.her.reverse().join(`<c class='sep'>></c>`);
      break;
    }
    
    return $_;      
  }// ejecuto selector
  static ele_act( $dat ){
    let $={};

    $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )

    $dat.nextElementSibling.classList.add(FON_SEL);

    $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');
    
    // resultado: div.ele
    $.res = $dat.parentElement.parentElement.parentElement.previousElementSibling;

    Doc.eli($.res);

    $.res.innerHTML = Ele.var('eti',document.querySelector($.ver));

  }// listado de nodos
  static ele_val( $dat ){
    let $={};
    $.ope = document.createElement('form');
    $.lis = document.createElement('ul');
    $.lis.classList.add('lis');
    $dat.forEach( $e => {
      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-2');
      $.ite.innerHTML = `${Doc_Val.ico('dat_ver',{'class':"tam-1 mar_der-1",'onclick':"Ele.var_act(this);"})}`;
      $.tex = document.createElement('p');
      $.tex.innerHTML = Ele.var('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });
    return [ $.ope, $.lis ];     
  }

}