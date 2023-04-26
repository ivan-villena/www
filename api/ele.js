// WINDOW
'use strict';

// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class Ele {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = Dat.get_est('ele',$ide,'dat');

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        default:        
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];        
          break;
        }        
      }
    }
    return $_;
  }

  // {} / {dom} => ""
  static val( ...$ele ){
    let $_ = "", $={};
    $ele.forEach( $ele => {
      if( typeof($ele)=='string' ){        
        $_ += $ele;
      }
      else{
        if( $ele['let'] ){
          $_ += Tex.let($ele['let']);
        }// por ícono
        else if( $ele['ico'] ){
          $.ico_ide = $ele['ico'];
          delete($ele['ico']);
          $_ += Fig.ico($.ico_ide,$ele);
        }// por ficha
        else if( $ele['ima'] ){
          $.est = $ele['ima'].split('.');
          delete($ele['ima']);
          $.est.push( !!($ele['ide']) ? $ele['ide'] : 0, $ele);
          $_ += Fig.ima(...$.est);
        }// por variable
        else if( $ele['tip'] ){
          $.tip = $ele['tip']; 
          delete($ele['tip']);
          $.val = ''; 
          if( $ele['val'] !== undefined ){ 
            $.val = $ele['val'];
            delete($ele['val']);
          }
          $.tip = $.tip.split('_');          
          $.nom = Tex.let_pal( $.tip.shift() );
          if( ( $.cla = window[$.nom] ) && $.cla.var ){
            
            $_ += $.cla.var( $.tip, $.val, $ele );
          }
          else{
            $_ += `<div class='err' title='No existe el operador ${$._eje}'></div>`
          }      
        }// por etiqueta
        else{
          $_ += Ele.eti($ele);
        }
      }
    });
    return $_;
  }// "<>" / {dom} => {}
  static val_dec( $ele, $dat ){

    let $_ = $ele, $={ tip : typeof($ele) };
    // "" => {}
    if( $.tip == 'string' ){
      $_ = Obj.val_dec($ele,$dat);
    }
    // {html} => {}
    else if( false ){

    }
    return $_;
  }// combino
  static val_jun( $ele, $mod, $ope = {} ){
    let $_ = $ele, $={};
    $.dat = ( $ope['dat'] !== undefined ) ? $ope['dat'] : null;
    // aseguo elemento
    $ele = Ele.val_dec($ele,$.dat);
    // recorro 2°dos elemento...
    Lis.val_ite($mod).forEach( $mod => {
      for( const $atr in ( $.lis_atr = Ele.val_dec($mod,$.dat) ) ){ const $v = $.lis_atr[$atr];
        // si no tiene el atributo, lo agrego
        if( !($_[$atr]) ){ 
          $_[$atr] = $v;
        }// combino, actualizo
        else{
          switch($atr){          
          case 'onclick':   Ele.eje($_,'cli',$v); break;// separador: "(;;)"
          case 'onchange':  Ele.eje($_,'cam',$v); break;// separador: "(;;)"
          case 'oninput':   Ele.eje($_,'inp',$v); break;// separador: "(;;)"
          case 'class':     Ele.cla($_,$v); break;// separador: " "
          case 'style':     Ele.css($_,$v); break;// separador: ";"
          default:          $_[$atr] = $v;   break;
          }
        }
      }
    });
    return $_;
  }  
  // actualizo propiedades
  static act( $tip, $ele, $val, $ope ){

    let $_ = [], $ = { tip : $tip.split('_')  };

    if( typeof($ele) == 'string' ) $ele = document.querySelectorAll($ele);

    $.lis = Lis.val_cod($ele);

    switch( $.tip[0] ){
    case 'nod':
      if( !$.tip[1] ){             
        $.htm = { 'main':'app', 'article':'art', 'form':'for', 'fieldset':'fie', 'div':'div' };
        $_ = {};
        $.her = $ele;
        // armo ascendencia
        while( $.her.parentElement ){ 
          $.her = $.her.parentElement; 
          $.ele.nod = $.her.nodeName.toLowerCase();         
          if( $.htm[$.ele.nod] ){ 
            $_[ $.htm[$.ele.nod] ] = $.her; 
          }
        }
      }
      break;
    case 'htm': 
      switch( $.tip[1] ){
      // actualizacion creando elemento
      case 'val': $.lis.forEach( $v => $_.push( $v.innerHTML = $val ) ); break;
      case 'eli': $.lis.forEach( $v => { Lis.val_cod($v.children).forEach( $v_2 => $_.push($v.removeChild($v_2)) ) } ); break;
      }
      break;
    case 'atr': 
      switch( $.tip[1] ){
      case 'act': $.lis.forEach( $v => $_.push( $v.setAttribute($val,$ope) ) ); break;
      case 'val': $.lis.forEach( $v => $_ = $v.getAttribute($val) ); break;
      }    
      break;
    case 'cla':
      switch( $.tip[1] ){
      case 'val': 
        $.lis.forEach( $v => $_.push( $v.classList.contains($val) ) ); 
        break;
      case 'pos': 
        $.lis.forEach( $v => $_.push( $v.classList.item($val) ) ); 
        break;
      case 'tog': 
        $.lis.forEach( $v => $_.push( $v.classList.toggle($val) ) ); 
        break;
      case 'agr': 
        $.lis.forEach( $v => Lis.val_ite($val).forEach( $val_cla => $_.push( $v.classList.add($val_cla) ) ) ); 
        break;
      case 'mod': 
        $.lis.forEach( $v => $_.push( $v.classList.replace($val, $ope) ) ); 
        break;
      case 'eli': 
        $.lis.forEach( $v => Lis.val_ite($val).forEach( $val_cla => $_.push( $v.classList.remove($val_cla) ) ) );
        break;    
      }
      break;
    case 'eje':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': $.lis.forEach( $v => $_.push( $v.addEventListener( $val, eval($ope) ) ) ); break;
      case 'eli': $.lis.forEach( $v => $_.push( $v.removeEventListener( $val, $ope ) ) ); break;
      }
      break;    
    case 'css':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': break;
      case 'mod': break;
      case 'eli': break;
      }
      break;
    }

    return $_;
  }

  // etiqueta : <eti atr="">...htm</eti>
  static eti( $ele ){
    let $_="",$={};
    $.eti = 'span'; 
    if( $ele['eti'] !== undefined ){
      $.eti = $ele['eti'];
      delete($ele['eti']);
    }
    $.htm = ''; 
    if( $ele['htm'] !== undefined ){
      $.htm = $ele['htm'];
      delete($ele['htm']);
      if( typeof($.htm) != 'string' ){
        $._htm_val = '';
        Lis.val_ite($.htm).forEach( $e => $._htm_val =+ ( typeof($e) == 'string' ) ? $e : Ele.val($e) );
      }
    }
    $_ = `
    <${$.eti}${Ele.atr($ele)}>
      ${!['input','img','br','hr'].includes($.eti) ? `${$.htm}
    </${$.eti}>` : ''}`;
    return $_;
  }
  // atributos : "< ...atr="">"
  static atr( $ele, $dat ){
    let $_='', $={};
    if( !!$dat ){
      for( const $i in $ele ){ 
        $.tex=[];
        $ele[$i].split(' ').forEach( ($pal)=>{ 
          $.let=[];
          $pal.split('()').forEach( ($cad)=>{ 
            $.sep = $cad;
            if( $cad.substring(0,3)=='($)' ){ 
              $.atr = $cad.substring(3); 
              if( $dat[$.atr] !== undefined ){ 
                $.sep = $dat[$.atr]; 
              }
            }
            $.let.push($.sep);
          });
          $.tex.push($.let.join(''));
        });// junto por espacios
        $_ += ` ${$i} = "${$.tex.join(' ').replaceAll('"',`\'`)}"`;
      }
    }// devuelvo "atr=val ..."
    else{
      for( const $i in $ele ){ 
        $_ +=` ${$i} = "${$ele[$i] && $ele[$i].toString().replaceAll('"',`\'`)}"`; 
      }
    }
    return $_;
  }
  // ejecucion
  static eje( $ele, $ide, $val, ...$opc ){

    let $_ = $ele, $ = {
      '_eve':{ 'cli':"onclick", 'cam':"onchange", 'inp':"oninput" }
    };

    if( $ide = $._eve[$ide] ){

      if( !$ide || $val === undefined ){
        
        $_ = [];
        if( $ide ){
          
          if( $ele[$ide] ) $_ = $ele[$ide].split(';');
        }// todos
        else{          
          $_ = {};
          for( const $i in $._eve ){ const $v = $._eve[$i];
            $_[$v] = [];
            if( $ele[$v] ) $_[$v] = $ele[$v].split(';');
          }
        }
        return $_;  
      }
      // operaciones
      else{
        // elimino funcion + parametros
        if( $opc.includes('eli') ){

        }// modifico funcion con parametros
        else if( $opc.includes('mod') ){

        }// agrego una funcion
        else{
          // al inicio
          if( $opc.includes('ini') ){
            $ele[$ide] = $val+( !!($ele[$ide]) ? ` ${$ele[$ide]}` : '' ); 
          }
          else if( $ele[$ide] ){
            $ele[$ide] += $val; 
          }
          else{
            $ele[$ide] = $val; 
          }
        }
        $_ = $ele;
      }
    }
    return $ele;
  }
  // clases
  static cla( $ele, $val, ...$opc ){
    let $_=$ele,$={};

    if( $val === undefined ){

      $_ = $val.className.split(' ');

    }// operaciones
    else{
      // elimino o cambio una clase
      if( ( $.eli = $opc.includes('eli') ) || ( $.tog = $opc.includes('tog') ) ){
        
        if( $opc.includes('ini') ){ 
          $.ver = '^'+$val; 
        }
        else if( $opc.includes('fin') ){ 
          $.ver = $val+'$'; 
        }
        else{
          $.ver = '^'+$val+'$';
        }
        $_ = [];
        // elimino
        if( !!$.eli ){
          $ele.classList.forEach( $cla => Tex.val_dec($.ver).test($cla) && $ele.classList.remove($cla) && ( $_.push($cla) ) );
        }// cambio : si la tiene, la saca; sino, la pone
        else if( !!$.tog ){
          $ele.classList.forEach( $cla => Tex.val_dec($.ver).test($cla) && $ele.classList.toggle($ope) && ( $_.push($cla) ) );
        }        
      }// agrego clase/s
      else{
        
        if( $opc.includes('ini') ){

          $ele['class'] = $val + ( $ele['class'] ? ` ${$ele['class']}` : '' );
        }
        else if( $ele['class'] ){

          $ele['class'] += ` ${$val}`; 
        }
        else{

          $ele['class'] = $val; 
        }

        $_ = $ele;
      }
    }
    return $_;
  }
  // estilos
  static css( $ele, $val, ...$opc ){
    let $_=$ele, $={};

    if( $val === undefined ){
      $_ = {};
      for( const $i in $ele.style ){ 
        $_[$i] = $ele.style[$i];
      }
    }// operaciones
    else{
      // eliminar una regla
      if( $opc.includes('eli') ){

      }// actualizar una regla
      else if( $opc.includes('mod') ){

      }// agregar 1 o muchas
      else{
        if( !($ele['style']) ){ $ele['style']=''; } 
        if( $opc.includes('ini') ){ 
          $ele['style'] += $val+( $ele['style'] ? `;${$ele['style']}` : '' ); 
        }
        else if( $ele['style'] ){ 
          $ele['style'] += `;${$val}`;
        }
        else{
          $ele['style'] = $val; 
        }
      }
      $_ = $ele;
    }
    return $_;
  }// - fondo : background
  static css_fon( $val, $ope={} ){
    if( !$ope['tip'] ) $ope['tip']='png';
    if( !$ope['ali'] ) $ope['ali']='center';
    if( !$ope['tam'] ) $ope['tam']='contain';
    if( !$ope['rep'] ) $ope['rep']='no-repeat';
    return `background: ${$ope['rep']} ${$ope['ali']} / ${$ope['tam']} url('${$val}.${$ope['tip']}');`;
  }

  // controladores
  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    // elemento por etiqueta      
    case 'eti':
      $.nom = $dat.nodeName.toLowerCase();
      $.fin=`<c></</c>/<b class='ide'>${$.nom}</b><c>></c>`;
      $.eti_htm = '';
      if( $dat.childNodes.length>0 ){ 
        Lis.val_cod($dat.childNodes).forEach( $e =>{
          if( $e.nodeName.toLowerCase() != '#text' ){ $.eti_htm += `
            <li>${Ele.var('eti',$e)}</li>`;
          }else{ $.eti_htm += `
            <li>${Tex.let($e.textContent)}</li>`;
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
  static var_act( $dat ){
    let $={};

    $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )

    $dat.nextElementSibling.classList.add(FON_SEL);

    $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');
    
    // resultado
    $.res = $dat.parentElement.parentElement.previousElementSibling.querySelector('div.ele');
    dom.eli($.res);
    $.res.innerHTML = Ele.var('eti',document.querySelector($.ver));

  }// listado de nodos
  static var_val( $dat ){
    let $={};
    $.ope = document.createElement('form');
    $.lis = document.createElement('ul');
    $.lis.classList.add('lis');
    $dat.forEach( $e => {
      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-2');
      $.ite.innerHTML = `${doc.ico('dat_ver',{'class':"tam-1 mar_der-1",'onclick':"ele.var_act(this);"})}`;
      $.tex = document.createElement('p');
      $.tex.innerHTML = Ele.var('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });
    return [ $.ope, $.lis ];     
  }
}