// WINDOW
'use strict';

// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class api_ele {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('ele',$ide,'dat');

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
          $_ += api_tex.let($ele['let']);
        }// por ícono
        else if( $ele['ico'] ){
          $.ico_ide = $ele['ico'];
          delete($ele['ico']);
          $_ += api_fig.ico($.ico_ide,$ele);
        }// por ficha
        else if( $ele['ima'] ){
          $.est = $ele['ima'].split('.');
          delete($ele['ima']);
          $.est.push( !!($ele['ide']) ? $ele['ide'] : 0, $ele);
          $_ += api_fig.ima(...$.est);
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
          $._eje = $.tip.shift();
          if( app_var ){
            $_ += app_var[$._eje]( $.tip, $.val, $ele );
          }else{
            $_ += `<div class='err' title='No existe el operador ${$._eje}'></div>`
          }      
        }// por etiqueta
        else{
          $_ += api_ele.eti($ele);
        }
      }
    });
    return $_;
  }// "<>" / {dom} => {}
  static val_dec( $ele, $dat ){

    let $_ = $ele, $={ tip : typeof($ele) };
    // "" => {}
    if( $.tip == 'string' ){
      $_ = api_obj.val_dec($ele,$dat);
    }
    // {html} => {}
    else if( false ){

    }
    return $_;
  }// {} / "" => {dom}
  static val_cod( $ele, ...$opc ){
    let $_ = false, $={ 'tip':typeof($ele) };
    // desde texto : <> 
    if( $.tip == 'string' ){
      $_ = document.createElement('span');
      $_.innerHTML = $ele;
      // devuelvo nodos: todos o el 1°
      if( $_.children[0] ){
        $_ = $opc.includes('nod') ? api_lis.val_cod($_.children) : $_.children[0];
      }
    }// desde 1 objeto : {}
    else if( $.tip == 'object' && !$ele.nodeName ){
      $.ele = api_obj.val_dec($ele);
      // creo etiqueta
      $.eti = 'span';
      if( $.ele.eti ){
        $.eti = $.ele.eti;
        delete($.ele.eti);
      }
      $_ = document.createElement($.eti);
      // copio contenido : texto | 1-n elementos
      if( $.ele.htm ){
        if( typeof($.ele.htm)=='string' ){ 
          $.ele_doc = document.createElement('span');
          $.ele_doc.innerHTML = $.ele.htm; 
          $.ele.htm = $.ele_doc.children;
        }
        api_lis.val_cod($.ele.htm).forEach( 
          $htm => $_.appendChild($htm)
        );
        delete($.ele.htm);
      }
      // copio atributos
      for( const $i in $val ){ 
        $_.setAttribute($i,$val[$i]); 
      }
    }
    return $_;
  }// combino
  static val_jun( $ele, $mod, $ope = {} ){
    let $_ = $ele, $={};
    $.dat = ( $ope['dat'] !== undefined ) ? $ope['dat'] : null;
    // aseguo elemento
    $ele = api_ele.val_dec($ele,$.dat);
    // recorro 2°dos elemento...
    api_lis.val_ite($mod).forEach( $mod => {
      for( const $atr in ( $.lis_atr = api_ele.val_dec($mod,$.dat) ) ){ const $v = $.lis_atr[$atr];
        // si no tiene el atributo, lo agrego
        if( !($_[$atr]) ){ 
          $_[$atr] = $v;
        }// combino, actualizo
        else{
          switch($atr){          
          case 'onclick':   api_ele.eje($_,'cli',$v); break;// separador: "(;;)"
          case 'onchange':  api_ele.eje($_,'cam',$v); break;// separador: "(;;)"
          case 'oninput':   api_ele.eje($_,'inp',$v); break;// separador: "(;;)"
          case 'class':     api_ele.cla($_,$v); break;// separador: " "
          case 'style':     api_ele.css($_,$v); break;// separador: ";"
          default:          $_[$atr] = $v;   break;
          }
        }
      }
    });
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
        api_lis.val_ite($.htm).forEach( $e => $._htm_val =+ ( typeof($e) == 'string' ) ? $e : api_ele.val($e) );
      }
    }
    $_ = `
    <${$.eti}${api_ele.atr($ele)}>
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
          $ele.classList.forEach( $cla => api_tex.val_dec($.ver).test($cla) && $ele.classList.remove($cla) && ( $_.push($cla) ) );
        }// cambio : si la tiene, la saca; sino, la pone
        else if( !!$.tog ){
          $ele.classList.forEach( $cla => api_tex.val_dec($.ver).test($cla) && $ele.classList.toggle($ope) && ( $_.push($cla) ) );
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
  }// estilos
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
        api_lis.val_cod($dat.childNodes).forEach( $e =>{
          if( $e.nodeName.toLowerCase() != '#text' ){ $.eti_htm += `
            <li>${api_ele.var('eti',$e)}</li>`;
          }else{ $.eti_htm += `
            <li>${api_tex.let($e.textContent)}</li>`;
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
    $dom.eli($.res);
    $.res.innerHTML = api_ele.var('eti',document.querySelector($.ver));

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
      $.tex.innerHTML = api_ele.var('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });
    return [ $.ope, $.lis ];     
  }
}