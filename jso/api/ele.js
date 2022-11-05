// WINDOW
'use strict';

// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class _ele {

  // {} / {dom} => ""
  static val( ...$ele ){
    let $_ = "", $={};
    $ele.forEach( $ele => {
      if( typeof($ele)=='string' ){        
        $_ += $ele;
      }
      else{      
        // por ícono
        if( $ele['ico'] ){
          $.ico = $ele['ico'];
          delete($ele['ico']);
          $_ += _app.ico($.ico,$ele);
        }// por ficha
        else if( $ele['ima'] ){
          $.est = $ele['ima'].split('.');
          $.est.push( !!($ele['ide']) ? $ele['ide'] : 0, $ele);
          $_ += _app.ima(...$.est);
        }// por variable
        else if( $ele['_tip'] ){
          $.tip = $ele['_tip']; 
          delete($ele['_tip']);
          $.val = ''; 
          if( $ele['val'] !== undefined ){ 
            $.val = $ele['val'];
            delete($ele['val']);
          }
          $.tip = $.tip.split('_');
          $._eje = $.tip.shift();
          if( _app_var ){
            $_ += _app_var[$._eje]( $.tip, $.val, $ele );
          }else{
            $_ += `<div class='err' title='No existe el operador ${$._eje}'></div>`
          }      
        }// por etiqueta
        else{
          $_ += _ele.eti($ele);
        }
      }
    });
    return $_;
  }// "<>" / {dom} => {}
  static dec( $ele, $dat ){

    let $_ = $ele, $={ tip : typeof($ele) };
    // "" => {}
    if( $.tip == 'string' ){
      $_ = _obj.dec($ele,$dat);
    }
    // {html} => {}
    else if( false ){

    }
    return $_;
  }// {} / "" => {dom}
  static cod( $ele, ...$opc ){
    let $_ = false, $={ 'tip':typeof($ele) };
    // desde texto : <> 
    if( $.tip == 'string' ){
      $_ = document.createElement('span');
      $_.innerHTML = $ele;
      // devuelvo nodos: todos o el 1°
      if( $_.children[0] ){
        $_ = $opc.includes('nod') ? _lis.val($_.children) : $_.children[0];
      }
    }// desde 1 objeto : {}
    else if( $.tip == 'object' && !$ele.nodeName ){
      $.ele = _obj.dec($ele);
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
        _lis.val($.ele.htm).forEach( 
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
  static jun( $ele, $mod, $ope = {} ){
    let $_ = $ele, $={};
    $.dat = ( $ope['dat'] !== undefined ) ? $ope['dat'] : null;
    // aseguo elemento
    $ele = _ele.dec($ele,$.dat);
    // recorro 2°dos elemento...
    _lis.ite($mod).forEach( $mod => {
      for( const $atr in ( $.lis_atr = _ele.dec($mod,$.dat) ) ){ const $v = $.lis_atr[$atr];
        // si no tiene el atributo, lo agrego
        if( !($_[$atr]) ){ 
          $_[$atr] = $v;
        }// combino, actualizo
        else{
          switch($atr){          
          case 'onclick':   _ele.eje($_,'cli',$v); break;// separador: "(;;)"
          case 'onchange':  _ele.eje($_,'cam',$v); break;// separador: "(;;)"
          case 'oninput':   _ele.eje($_,'inp',$v); break;// separador: "(;;)"
          case 'class':     _ele.cla($_,$v); break;// separador: " "
          case 'style':     _ele.css($_,$v); break;// separador: ";"
          default:          $_[$atr] = $v;   break;
          }
        }
      }
    });
    return $_;
  }// atributos : "< ...atr="">"
  static atr( $ele, $dat ){
    let $_='',$={};
    if( $ele['htm'] !== undefined ) delete($ele['htm']);

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
  }// armo etiqueta : <eti atr="">...htm</eti>
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
      if( typeof($.htm)!='string' ){
        $._htm_val = '';
        _lis.ite($.htm).forEach( $e => 
          $._htm_val =+ ( typeof($e) == 'string' ) ? $e : _ele.val($e)
        );
      }
    }
    $_ = `
    <${$.eti}${_ele.atr($ele)}>
      ${!['input','img','br','hr'].includes($.eti) ? `${$.htm}
    </${$.eti}>` : ''}`;
    return $_;
  }// - fondo : background
  static fon( $val, $ope={} ){
    if( !$ope['tip'] ) $ope['tip']='png';
    if( !$ope['ali'] ) $ope['ali']='center';
    if( !$ope['tam'] ) $ope['tam']='contain';
    if( !$ope['rep'] ) $ope['rep']='no-repeat';
    return `background: ${$ope['rep']} ${$ope['ali']} / ${$ope['tam']} url('${$val}.${$ope['tip']}');`;
  }
  // evento-ejecucion
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
  }// clases
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
          $ele.classList.forEach( $cla => _tex.dec($.ver).test($cla) && $ele.classList.remove($cla) && ( $_.push($cla) ) );
        }// cambio : si la tiene, la saca; sino, la pone
        else if( !!$.tog ){
          $ele.classList.forEach( $cla => _tex.dec($.ver).test($cla) && $ele.classList.toggle($ope) && ( $_.push($cla) ) );
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
  }
  // operaciones
  static ope( $val, $ope, ...$opc ){
    let $_=false,$={};
    // busco elementos
    if( typeof($val) == 'string' ){ 
      $val = document.querySelectorAll($val);
    }
    else if( Array.isArray($val) ){
      $val[0] = ( !$val[0] || typeof($val[0]) == 'string' ) ? document.querySelector( $val[0] ? $val[0] : 'body' ) : $val[0];
      $val = $val[0].querySelectorAll($val[1] ? $val[1] : '*');
    }
    // ejecuto operaciones
    $.res = $val;
    if( $ope ){
      $.res = [];
      _lis.ite($ope).forEach( $ope_ite => $.res.push( _ele.act( $ope_ite, $val, ...$opc ) ) );
    }
    // resultados: [<>] => <> // si hay 1 solo, devuelvo único elemento
    $_ = _lis.val($.res);
    if( !$_.length ){ 
      $_ = false; 
    }else if( $_.length == 1 ){ 
      $_ = $_[0]; 
    }
    return $_;
  }// actualizo propiedades
  static act( $tip, $ele, $val, $ope ){
    let $_ = [], $={ 
      tip : $tip.split('_') 
    };
    if( typeof($ele) == 'string' ) $ele = document.querySelectorAll($ele);
    $.lis = _lis.val($ele);
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
      case 'eli': $.lis.forEach( $v => { _lis.val($v.children).forEach( $v_2 => $_.push($v.removeChild($v_2)) ) } ); break;
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
      case 'val': $.lis.forEach( $v => $_.push( $v.classList.contains($val) ) ); break;
      case 'pos': $.lis.forEach( $v => $_.push( $v.classList.item($val) ) ); break;
      case 'tog': $.lis.forEach( $v => $_.push( $v.classList.toggle($val) ) ); break;
      case 'agr': $.lis.forEach( $v => _lis.ite($val).forEach( $val_cla => $_.push( $v.classList.add($val_cla) ) ) ); break;
      case 'mod': $.lis.forEach( $v => $_.push( $v.classList.replace($val, $ope) ) ); break;
      case 'eli': $.lis.forEach( $v => _lis.ite($val).forEach( $val_cla => $_.push( $v.classList.remove($val_cla) ) ) );break;    
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
  }// busco nodos
  static ver( $ele, $ope={} ){

    let $_ = false, $ = {};
    $.opc = $ope.opc ? $ope.opc : []      
    // ejecuto valicaciones : etiqueta | clases | atributos
    $._ele_ver = ( $ele, $ope ) =>{
      let $_ = true;
      // etiqueta
      if( $ope.eti && $ope.eti != $ele.nodeName.toLowerCase() ) $_ = false;
      // clases
      if( $_ && $ope.cla ){
        $ope.cla.forEach( $v =>{ 
          if( !$ele.classList.contains($v) ) return $_ = false;
        });
      }
      // atributo = valor
      if( $_ && $ope.atr ){
        $ope.atr.forEach( ($v,$i) =>{ 
          if( !($.atr_val = $ele.getAttribute($i)) || ( $v && $.atr_val != $v ) ) return $_ = false;
        });
      }
      return $_;

    };
    // proceso filtros
    $.val = [];
    $.opc_mul = $.opc.includes('mul');
    
    // por nodos descendentes
    if( $.opc.includes('nod') ){

      _lis.val($ele.children).forEach( $ele => {

        if( $._ele_ver($ele,$ope) ){           
          $.val.push($ele);
          if( !$.opc_mul ){ return; }
        }
      });
    }// por ascendentes
    else{
      while( $ele.parentElement ){

        $ele = $ele.parentElement;

        if( $._ele_ver($ele,$ope) ){ 
          $.val.push($ele); 
          if( !$.opc_mul ){ break; }
        }
      }
    }
    // devuelvo 1 o muchos
    if( $.val.length > 0 ){
      $_ = $.val.length == 1 ? $.val[0] : $.val;
    }
    return $_;
  }// agrego nodo/s al inicio o al final
  static agr( $ele, $pad, ...$opc ){
    let $_=[], $={};
    $.opc_ini = $opc.includes('ini');
    $.val_uni = !Array.isArray($ele);
    // recibo 1 o muchos    
    _lis.ite($ele).forEach( $ele => {
      if( typeof($ele) == 'string' ){
        $_.push( ..._ele.cod($ele,'nod') );
      }else{
        $_.push( $ele );
      }
    });
    // agrego o modifico  
    if( typeof($pad)=='string' ) $pad = document.querySelector($pad);

    if( $pad ){      
      $_.forEach( $ele => {
        if( $.opc_ini && $pad.children[0] ){
          $pad.insertBefore( $ele, $pad.children[0] );
        }else{
          $pad.appendChild( $ele );
        }
      });

    }
    return ( $.val_uni && $_[0] ) ? $_[0] : $_;
  }// modifico nodo : si no encuentro anterior, puedo agregar
  static mod( $ele, $mod = {}, ...$opc ){
    let $_={},$={};
    $.opc_agr = !$opc.includes('-agr');
    // aseguro valor
    $.eti = _ele.cod($ele);
    if( $.eti.nodeName ){
      if( $mod.nodeName ) $mod.parentElement.replaceChild( $.eti, $mod );
    }
    return $_;
  }// elimino nodo/s : todos o por seleccion, y los devuelvo
  static eli( $pad, $nod ){
    let $_ = [];
    // elimino todos
    if( $nod === undefined ){
      $nod = $pad.children;
    }// por seleccion
    else if( typeof($nod)=='string' ){
      $nod = $pad.querySelectorAll($nod);
    }// por elemento
    else if( !$nod.nodeName ){
      $nod = false;
    }
    if( !!$nod ){
      _lis.val($nod).forEach( $ele => $_.push( $pad.removeChild($ele) ) );
    }
    return $_;
  }
}