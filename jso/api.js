// WINDOW
'use strict';

const SYS_NAV = "http://localhost/";
const SYS_REC = "http://localhost/_/";

// operativas
const DIS_OCU = "dis-ocu";
const FON_SEL = "fon-sel";
const BOR_SEL = "bor-sel";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

const $_log = { 'php':[], 'jso':[] };

// Interface
class _api {

  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){     

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }
  // getter
  static _( $ide, $dat ){
    let $_=[], $={};

    if( $dat !== undefined ){
      switch( $ide ){
      default:
        if( typeof($dat)=='object' ){
          $_ = $dat;
        }
        // por posicion: 1-n
        else if( _num.tip($dat) ){

          $_ = $_api[$ide] && $_api[$ide][$.cod = _num.val($dat)-1] !== undefined ? $_api[$ide][$.cod] : {};
        }
        // por identificador
        else{
          $_ = $_api[$ide] && $_api[$ide][$dat] !== undefined ? $_api[$ide][$dat] : {};
        }
        break;
      }
    }
    else{
      $_ = $_api[$ide] !== undefined ? $_api[$ide] : [];
    }
    return $_;
  }
}

// Código HTML
class _htm {

  static val( ...$ele ){

    let $_ = "", $={};

    $ele.forEach( $ele => {
      if( typeof($ele)=='string' ){
        
        $_ += $ele;
      }
      else{      
        // por ícono
        if( $ele['ico'] ){
          $_ += _doc.ico($ele['ico'],$ele);
        }
        // por ficha
        else if( $ele['fic'] ){
          $.est = $ele['fic'].split('.');
          $.est.push( !!($ele['ide']) ? $ele['ide'] : 0, $ele);
          $_ += _doc.ima(...$.est);
        }
        // por variable
        else if( $ele['_tip'] ){
          $.tip = $ele['_tip']; 
          delete($ele['_tip']);
          $.val = ''; 
          if( $ele['val'] || [false,0,''].includes($ele['val']) ){ 
            $.val = $ele['val'];
            delete($ele['val']);
          }
          $.tip = $.tip.split('_');
          $._eje = $.tip.shift();
          if( _doc[$._eje] && typeof(_doc[$._eje])=='function' ){
            $_ += _doc[$._eje]( $.tip, $.val, $ele );
          }else{
            $_ += `<div class='err' title='No existe el operador ${$._eje}'></div>`
          }      
        }
        else{
          $.eti = !!$ele['eti'] ? $ele['eti'] : 'span'; 
          $.htm = !!$ele['htm'] ? $ele['htm'] : ''; 
          if( typeof($.htm)!='string' ){
            $._htm = '';
            _lis.ite($.htm).forEach( $e => 
              $._htm = typeof($e) == 'string' ? $e : _htm.val($e) 
            );
          }
          $_ +=`
          <${$.eti}${_htm.atr($ele)}>
            ${!['input','img','br','hr'].includes($.eti) ? `${$.htm}
          </${$.eti}>` : ''}`;
        }
      }
    });
    return $_;
  }

  static dat( $dat, ...$opc ){
    let $_ = false, $={ 'tip':typeof($dat) };
    // desde texto : <> 
    if( $.tip == 'string' ){
      $_ = document.createElement('span');
      $_.innerHTML = $dat;
      // devuelvo nodos: todos o el 1°
      if( $_.children[0] ){
        $_ = $opc.includes('nod') ? _lis.val($_.children) : $_.children[0];
      }
    }// desde 1 objeto : {}
    else if( $.tip == 'object' && !$dat.nodeName ){
      $.ele = _obj.dec($dat);
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
  }

  static atr( $val, $dat ){
    let $_='',$={};// genero atributos : { ide = "val",... }
    ['eti','htm','htm_ini','htm_med','htm_fin'].forEach( $atr => ( $val[$atr] || $val[$atr]=='' ) && delete($val[$atr]) );
    if( !!$dat ){
      for( const $i in $val ){ 
        $.tex=[];
        $val[$i].split(' ').forEach( ($pal)=>{ 
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
      for( const $i in $val ){ 
        $_ +=` ${$i} = "${$val[$i] && $val[$i].toString().replaceAll('"',`\'`)}"`; 
      }
    }
    return $_;
  }

  static eti( $dat ){
    let $_="",$={};
    $.eti = !!$dat['eti'] ? $dat['eti'] : 'span'; 
    $.htm = !!$dat['htm'] ? $dat['htm'] : ''; 
    if( typeof($.htm)!='string' ){
      $._htm = '';
      _lis.ite($.htm).forEach( $e => 
        $._htm = typeof($e) == 'string' ? $e : _htm.val($e) 
      );
    }
    $_ = `
    <${$.eti}${_htm.atr($dat)}>
      ${!['input','img','br','hr'].includes($.eti) ? `${$.htm}
    </${$.eti}>` : ''}`;
    return $_;
  }  
}

// Modelo del Documento
class _dom {

  static tog( $ele, $eti, $css, $ide, $cla='dis-ocu', $ope='cla_agr' ){  
    let $={};// cambios de clase
    $.htm = _ele.act('nod',$ele);
    if( $.htm[$eti] && $.htm[$eti].nodeName ){
      $.obj = $.htm[$eti];
    }else{
      switch( $eti ){
      case 'pad': $.obj = $ele.parentElement; break;
      case 'abu': $.obj = $ele.parentElement.parentElement; break;
      case 'tar': $.obj = $ele.parentElement.parentElement.parentElement; break;
      }
    }
    _ele.ope( $.obj.querySelectorAll($css), $ope, $cla );// :not(${$ide})
    _ele.ope( $.obj.querySelectorAll(`${$css}${$ide}`), 'cla_tog', $cla );
    return $ele;
  }    

  static val( $ele, $rel, $dep, $ope, $val ){    
    let $ = {};
    // selector por documento
    $.htm = _ele.act('nod',$ele);
    if( !!$rel && $.htm[$rel] && $.htm[$rel].nodeName ){
      $rel = ( $.htm[$rel] && $.htm[$rel].nodeName ) ? $.htm[$rel] : document;
    }
    else{
      $.rel = $rel.split('-');
      $.lim = !!($.rel[1]) ? parseInt($.rel[1]) : 0;
      switch( $.rel[0] ){
      case 'asc': $rel = $ele.parentElement; 
        for( let $i=0; $i<$.lim; $i++ ){ if( $rel.parentElement ){ $rel=$rel.parentElement; } } 
        break;
      case 'des': $rel = $ele.firstElementChild; 
        for( let $i=0; $i<$.lim; $i++ ){ if( $rel.nextElementSibling ){ $rel=$rel.nextElementSibling; } } 
        break;
      case 'pre': $rel = $ele.previousElementSibling; 
        for( let $i=0; $i<$.lim; $i++ ){ if( $rel.previousElementSibling ){ $rel=$rel.previousElementSibling; } } 
        break;
      case 'pos': $rel = $ele.nextElementSibling; 
        for( let $i=0; $i<$.lim; $i++ ){ if( $rel.nextElementSibling ){ $rel=$rel.nextElementSibling; } } 
        break;
      default:
        $rel = document;
      }        
    }// selector, operador, valor  
    return _ele.ope( $rel.querySelectorAll($dep), $ope, $val );
  }

  static act( $tip, $ele, $val, $ope ){
    let $_ = [], $={
      tip : $tip.split('_')
    };
    if( typeof($ele) == 'string' ){ 
      $ele = document.querySelectorAll($ele);       
    }
    else if( Array.isArray($ele) ){
      $ele[0] = ( !$ele[0] || typeof($ele[0]) == 'string' ) ? document.querySelector( $ele[0] ? $ele[0] : 'body' ) : $ele[0];
      $ele = $ele[0].querySelectorAll($ele[1] ? $ele[1] : '*');
    }
    $.lis = _lis.val($ele);
    switch( $.tip[0] ){
    case 'nod':
      if( !$.tip[1] ){             
        $.htm = {
          'main':'app', 'article':'art', 'form':'for', 'fieldset':'fie', 'div':'div'
        };
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
    case 'cla':
      switch( $.tip[1] ){
      case 'val': $.lis.forEach( $v => $_.push( $v.classList.contains($val) ) ); break;
      case 'pos': $.lis.forEach( $v => $_.push( $v.classList.item($val) ) ); break;
      case 'tog': $.lis.forEach( $v => $_.push( $v.classList.toggle($val) ) ); break;
      case 'agr': $.lis.forEach( $v => $_.push( !$v.classList.contains($val) && $v.classList.add($val) ) ); break;
      case 'mod': $.lis.forEach( $v => $_.push( $v.classList.replace($val, $ope) ) ); break;
      case 'eli': $.lis.forEach( $v => $_.push( $v.classList.remove($val) ) ); break;    
      }
      break;
    case 'eje':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': $.lis.forEach( $v => $_.push( $v.addEventListener( $val, eval($ope) ) ) ); break;
      case 'eli': $.lis.forEach( $v => $_.push( $v.removeEventListener( $val, $ope ) ) ); break;
      }
      break;    
    case 'art':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': break;
      case 'mod': break;
      case 'eli': break;
      }
      break;
    }  
  }

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
  }

  static agr( $val, $pad, ...$opc ){
    let $_=[],$={};
    // recibo 1 o muchos
    $.val_uni = !Array.isArray($val);
    _lis.ite($val).forEach( $ele => {
      $.ite = $ele;
      if( typeof($ele) == 'string' ){
        $ele = _htm.dat($ele);
      }
      $_.push( $ele );
    });
    // agrego o modifico  
    if( typeof($pad)=='string' ){
      $pad = document.querySelector($pad);      
    }
    if( $pad ){
      $.val_ini = $opc.includes('ini');
      $_.forEach( $ele => {
        if( $.val_ini && $pad.children[0] ){
          $pad.insertBefore( $ele, $pad.children[0] );
        }else{
          $pad.appendChild( $ele );
        }
      });

    }
    return ( $.val_uni && $_[0] ) ? $_[0] : $_;
  }

  static mod( $val, $pad, $mod, ...$opc ){
    let $_={},$={};
    // aseguro valor
    $.eti = _htm.dat($val);
    // busco padre
    if( typeof($pad)=='string' ){
      $pad = document.querySelector($pad); 
    }
    if( $pad ){
      // busco hermano
      if( typeof($mod)=='string' ){
        $mod = $pad.querySelector($mod); 
      }
      if( $mod ){
        $pad.replaceChild( $.eti, $mod );
      }else{
        $_ = _ele.agr( $.eti, $pad, ...$opc );
      }
    }
    return $_;
  }

  static eli( $pad, $nod ){
    let $_=[];
    // elimino todos
    if( !$nod ){
      $nod = $pad.children;
    }// por seleccion
    else if( typeof($nod)=='string' ){
      $nod = $pad.querySelectorAll($nod);
    }
    if( $nod ){
      _lis.val($nod).forEach( $ele => $_.push( $pad.removeChild($ele) ) ); 
    }
    return $_;
  }
}

// usuario del sistema : sesion + transitos
class _usu {

  constructor( $dat ){
    
    // datos propios
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Dato : esq.est[ide].atr
class _dat {
    
  // identificador: esq.est[...atr]
  static ide( $dat='', $val={} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }
  // busco dato: [ {}, {}, {} ]
  static var( $dat, $ope, $val='' ){
    let $_=[], $={};
    // por objeto[->propiedad]
    if( typeof($ope)=='string' ){
      $.esq = $dat;
      $.est = $ope;
      $_ = $val;
      if( !$val || !_obj.tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval(`_${$.esq}`) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = _ele.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{
      $_ = _est.ope('ver',$dat,$ope);
    }
    return $_;
  }
  // valores : nombre, descripcion, titulo, imagen, color...
  static val( $esq, $est, $atr, $dat ) {
    let $={}, $_ = false;         
    $._val = $_api.dat_val[$esq][$est];
    if( !($atr) ){

      $_ = $._val;
    }
    else if( !!($._val[$atr]) ){

      $_ = $._val[$atr];

      // valores variables ()($)...()
      if( !!($dat) ){

        $_ = _obj.val( _dat.var($esq,$est,$dat), $._val[$atr] );
      }
    }
    return $_;
  }// ver valores : imagen, color...
  static val_ver( $tip, $esq, $est, $atr, $dat ){
    
    // dato
    let $={}, $_ = { 'esq': $esq, 'est': $est };
    // armo identificador
    if( !!($atr) ) $_['est'] = $atr == 'ide' ? $est : `${$est}_${$atr}`;
    // valido dato
    if( !!( $.dat_Val = _dat.val($_['esq'],$_['est'],$tip,$dat) ) ){
      $_['ide'] = `${$_['esq']}.${$_['est']}`;
      $_['val'] = $.dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;    
  }
}
// Valor : tip + ver
class _val {

  // tipo
  static tip( $val ){
    let $_ = false, 
      $ide = typeof($val), 
      $tam = 0
    ;  
    if( $val === null ){
      $ide='null'; 
    }// ejecuciones
    else if( $ide=='function' ){ 
      if( $val.prototype && /^class /.test( $val.prototype.constructor.toString() ) ){ $ide='class'; }
    }// listados
    else if( Array.isArray($val) ){ 
      $ide = 'array';
    }// objetos + archivo + documento
    else if( $ide=='object' ){
      if( $val.constructor.name=="Object" ){ 
        $ide='asoc';
      }else{ 
        if( $val.constructor.name=='Event' ){ 
          $ide='event';
        }else if( /(NodeList|^HTML[a-zA-Z]*Collection$)/.test($val.constructor.name) ){ 
          $ide='elementlist'; 
        }else if( /^HTML[a-zA-Z]*Element$/.test($val.constructor.name) ){ 
          $ide='element';
        }else if( $val.constructor.name=='FileList' ){ 
          $ide='fileList';
        }else if( $val.constructor.name=='File' ){ 
          if( /^image/.test($val.type) ){       $ide='image'
          }else if( /^audio/.test($val.type) ){ $ide='audio'
          }else if( /^video/.test($val.type) ){ $ide='video'
          }
        }else{ 
          $ide='object';
        }
      }  
    }// numeros
    else if( $ide=='number' ){
      if( Number.isNaN($val) ){ 
        $ide = 'nan';
      }else{
        $tam = $val.length;
        if( Number.isInteger($val) ){ 
          if( $tam <= 5 ){ $ide="smallint";
          }else if( $tam <= 7 ){ $ide="mediumint";
          }else if( $tam <= 10 ){ $ide="int";
          }else if( $tam <= 19 ){ $ide="bigint";
          }        
        }else{
          if( $tam <= 10 ){ $ide="decimal";
          }else if( $tam <= 12 ){ $ide="float";
          }else if( $tam <= 22 ){ $ide="double";
          }
        }
      }
    }// textos
    else if( $ide=='string' ){
      $ide="varchar";
      if( $val.length>50 ){        
        if( /^[0-9]\(>\)[0-9]\(>\)[0-9]$/.test($val) || /^[0-9]\(>\)[0-9]$/.test($val) ){ 
          $ide="range";            
        }else if( /^#k[0-9]{3}$/.test($val) ){ 
          $ide='kin';
        }else if( /^#[a-zA-Z0-9]{6}$/.test($val) || /^rgb\(/.test($val) ){ 
          $ide='color';
        }else if( /^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/.test($val) ){ 
          $ide="datetime";
        }else if( /^\d{4}([\-/.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/.test($val) ){ 
          $ide="date";
        }else if( /^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/.test($val) ){ 
          $ide="time";
        }
      }else if( $val.length<=255 && $val.length>=100 ){
        $ide="tinytext";
      }else if( $val.length<=65535 ){      
        $ide="text";
      }else if( $val.length<=16777215 ){   
        $ide="mediumtext";
      }else if( $val.length<=4294967295 ){ 
        $ide="longtext";
      }
    }// busco
    if( !!$_api.var_tip[$ide] ){
      $_ = $_api.var_tip[$ide];
    }
    return $_;
  }
  // Comparaciones
  static ver( $dat, $ide, $val, $opc=['g','i'] ){
    let $_ = false;
    switch($ide){
    case '===': $_ = ( $dat === $val );  break;
    case '!==': $_ = ( $dat !== $val );  break;
    case '=':   $_ = ( $dat ==  $val );  break;
    case '<>':  $_ = ( $dat !=  $val );  break;                  
    case '==':  $_ = ( $dat ==  $val );  break;
    case '!=':  $_ = ( $dat !=  $val );  break;          
    case '>>':  $_ = ( $dat  >  $val );  break;
    case '<<':  $_ = ( $dat  <  $val );  break;
    case '>=':  $_ = ( $dat >=  $val );  break;
    case '<=':  $_ = ( $dat <=  $val );  break;
    case '^^':  $_ = _tex.dec(`^${_tex.cod($val)}`,     $opc.join('') ).test( $dat.toString() ); break;
    case '!^':  $_ = _tex.dec(`^[^${_tex.cod($val)}]`,  $opc.join('') ).test( $dat.toString() ); break;
    case '$$':  $_ = _tex.dec( `${_tex.cod($val)}$`,    $opc.join('') ).test( $dat.toString() ); break;
    case '!$':  $_ = _tex.dec( `[^${_tex.cod($val)}]$`, $opc.join('') ).test( $dat.toString() ); break;
    case '**':  $_ = _tex.dec( `${_tex.cod($val)}`,     $opc.join('') ).test( $dat.toString() ); break;
    case '!*':  $_ = _tex.dec( `[^${_tex.cod($val)}]`,  $opc.join('') ).test( $dat.toString() ); break;
    }
    return $_;
  }
}
// listado : []
class _lis {

  // aseguro iteracion : []
  static ite( $dat = [] ){

    return _obj.tip($dat) == 'pos' ? $dat : [ $dat ] ;
  }
  // convierto a listado : []
  static val( $dat ){
  
    let $_ = $dat;
  
    // elemento : armo listado o convierto a iterable
    if( $dat.constructor && /(NodeList|^HTML[a-zA-Z]*(Element|Collection)$)/.test($dat.constructor.name) ){

      $_ = ( /^HTML[a-zA-Z]*Element/.test($dat.constructor.name) ) ? _lis.ite($dat) : Array.from( $dat ) ;
    }
    // convierto : {} => []
    else if( typeof($dat) == 'object' ){

      $_=[]; 

      for( const $i in $dat ){ 
        
        $_.push( $dat[$i] ); 
      }
    }
    return $_;
  }
}
// Estructura : [ ...{} ] 
class _est {

  static ope( $dat, $ope, $tip='' ){
    let $_ = $dat;
    if( !$tip ){
      // nivelacion por identificador
      if( !!($ope['niv']) ){

        $_ = _est.ope($dat,$ope,'niv');
      }
      // reduccion muchos a uno
      if( !!($ope['opc']) ){
        
        if( $ope['opc'].includes('uni') ){ 
  
          $_ = _est.ope($dat,$ope,'uni');
        }
      }
    }
    else{
      switch( $tip ){
      case 'niv': 
        $_ = $dat;
        $.tip = typeof($ope['niv']);
        // key-pos = numérica
        if( $.tip == 'number' ){ 
          $_=[];
          $.k = parseInt($.key);
          for( const $i in $dat ){ $_[$.k++]=$dat[$i]; }
        }// key-ide = Literal
        else if( $.tip=='string' ){ 
          $_={}; 
          $.k = $.key.split('(.)');
          for( const $i in $dat ){
            $.ide=[];
            for( let $ide of $.k ){ $.ide.push($dat[$i][$ide]); }
            $_[$.ide.join('(.)')] = $dat[$i];
          }
        }// multidimensional => keys-[ [ [ [],[ {-_-} ],[], ] ] ]
        else if( Array.isArray($ope['niv']) ){ 
          $_ = {};
          $.k = $ope['niv'];
          switch( $k.length ){
          case 0: $_ = $dat; break;
          case 1: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]]=$d; } break;
          case 2: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]]=$d; } break;
          case 3: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]]=$d; } break;
          case 4: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]]=$d; } break;
          case 5: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]]=$d; } break;
          case 6: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]][$d[$.k[5]]]=$d; } break;
          case 7: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]][$d[$.k[5]]][$d[$.k[6]]]=$d; } break;
          }
        }    
        break;
      case 'uni':
        for( const $pos in $_ ){ 
          $_ = $_[$pos]; 
          break; 
        } 
        break;
      }
    }
    return $_;
  }  

}
// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class _obj {

  // valores por atributo : ()($)atr()
  static val( $dat, $val='' ){
    let $_={},$={};
    $_ = [];
    $val.split(' ').forEach( $pal =>{ 
      $.let=[];
      $pal.split('()').forEach( $cad =>{ 
        $.val = $cad;
        if( $cad.substring(0,3)=='($)' ){ 
          $.val = !!($dat[ $.atr = $cad.substring(3) ]) ? $dat[$.atr] : ''; 
        }
        $.let.push($.val );
      });
      $_.push($.let.join(''));
    });
    $_ = $_.join(' ');
    return $_;
  }  
  // convierto : {}-[] => "..."
  static cod( $dat ){
  
    let $_ = $dat;
  
    if( typeof($dat) == 'object' ){ 
  
      $_ = JSON.stringify($dat); 
    }
  
    return $_;
  }
  // convierto : "..." => {}-[]
  static dec( $dat, $ope ){
  
    let $_ = $dat;
  
    if( typeof($dat) == 'string' ){
  
      if( !!$ope && /\(\)\(\$\).+\(\)/.test($dat) ){
        $dat = _obj.val($ope,$dat);
      }
      // json : {} + []
      if( /^({|\[).*(}|\])$/.test($dat) ){ 
  
        $_ = JSON.parse($dat);
      }
      // valores textuales : ('v_1','v_2','v_3')
      else if( /^\('*.*'*\)$/.test($dat) ){ 
  
        $_ = /','/.test($dat) ? $dat.substr(1,$dat.length-1).split("','") : [ $dat.trim().substr(1,$dat.length-1) ] ;      
      }
      // elemento del documento : "a_1(=)v_1(,,)a_2(=)v_2"
      else if( /\(,,\)/.test($dat) && /\(=\)/.test($dat) ){
        $dat.split('(,,)').forEach( $v => { 
          $eti = $v.split('(=)'); 
          $_[$eti[0]] = $eti[1]; 
        });
      }
    }
    return $_;
  }
  // valido tipos : pos | nom | atr
  static tip( $dat ){
  
    let $_ = false;
  
    if( Array.isArray($dat) ){
      $_ = "pos";
    }
    else if( !!$dat && typeof($dat)=='object' ){
  
      $_ = ( $dat.constructor.name == 'Object' ) ? "nom" : "atr";
    }
    return $_;
  }
}
// Ejecucion : ( ...par ) => { ...cod } : val 
class _eje {

  static val( $dat, $val ){
    let $_=[], $={};
    
    if( Array.isArray($dat) && typeof($val)=='function' ){
  
      const $_ajax = async($) =>{ 
        let $v = await fetch($); 
        $v = await $v.json();
        return $v;
      };
  
      // armo peticion
      $_ = `${SYS_NAV}index.php?_=${JSON.stringify($dat)}`;
  
      // cargo log 
      $_log.php.push($_);      
  
      $_ = $_ajax( encodeURI($_) ).then( $ => {
  
          if( $['_err'] ){ 
            console.error($['_err']); 
          }
          $val($);
  
      }).catch( $ =>{ 
  
          console.error( $, $val.toString() );
        })
      ;
    }
    return $_;
  }  

  static cod( $val, $par ){
    let $_=false,$={}; 
    $.par=[];
    $.fun = $val.split('.');
    if( $.fun[1] ){
      $.cla = ( $.fun[0]!='' ) ? $.fun[0] : '_doc' ; // objeto por defecto
      $.met = $.fun[1].split('-'); if( $.met[1] ){ $.par.push($.met[1]); } $.met=$.met[0];
      $_=[$.cla,'',$.met];
    }// ejecucion entorno
    else if( !!$.fun[0] ){
      $.fun = $.fun[0].split('-'); if( $.fun[1] ){ $.par.push($.fun[1]); } $.fun=$.fun[0];
      $_ = ['fun',$.fun];
    }// proceso parámetros de operador
    if( Array.isArray($par) ){ 
      $.par.push( ...$par ); 
    }
    if( Array.isArray($_) ){ 
      $_.push([...$.par]);
    }
    return $_;
  }

  static lis( $dat, $ope ){  
    let $_ = $dat;
    if( !$ope ){
      $_ =  _obj.tip($dat)=='pos' ? $dat : [ $dat ] ;
    }
    else{
  
    }
    return $_;
  }

  static fun( $dat, $val ){
    if( $dat.nodeName ){
      $.pad = $dat.parentElement.parentElement;
      ['par','val','ini','inv'].forEach( $v => $[$v] = $.pad.querySelector(`[${$v}]`).value );
      $.fun = eval(`( ${$.par} ) =>{ ${$.val} return ${$.ini} }`);
      if( $.fun ){// defino un array para desestructurar e invocar
        $_ = !!($.inv) ? $.fun( ...eval(`[${$.inv}]`) ) : $.fun() ;
        $.tip = _val.tip($_);// evaluo e imprimo resultado 
        // $.pad.querySelector(`[ini]`).innerHTML = ( `${$.tip.dat}_${$.tip.val}`, { 'val':$_ } );
      }else{ 
        $.pad.querySelector(`[ini]`).innerHTML = `<span class="let err">${$.fun._err}</span>`;
      }
    }else if( !$val ){
      $_ = $dat(); 
    }else{ 
      $_ = !Array.isArray($val) ? $dat($val) : $dat(...$val) ; 
    }
    return $_;
  }

  static cla( $val ){
    let $_,$={
      'tip':typeof($val)
    };
    if( $.tip=='object' ){
      $_=(!$d[1]) ? new $val.constructor() : ( !Array.isArray($d[1]) ) ? new $val.constructor($d[1]) : new $val.constructor(...$d[1]) ;
    }
    else if( $.tip=='string' ){
      $_=(!$d[1]) ? new window[$val]() : ( !Array.isArray($d[1]) ) ? new window[$val]($d[1]) : new window[$val](...$d[1]) ; 
    }
    else if( $.tip=='function' ){
      $_=(!$d[1]) ? new $_fun() : ( !Array.isArray($d[1]) ) ? new $_fun($d[1]) : new $_fun(...$d[1]) ;
    }
    return $_;
  }

  static eve( $dat, $val  ){
    let $_=[], $={};
    // recivo un evento
    if( $dat.target ){
      $.eve = $dat;
      $.ele = $.eve.target;
      if( $.eje = $.ele.getAttribute('_eje') ){
    
        $.eje.split('(;;)').forEach( $eje_eve =>{
          
          $._eje = $eje_eve.split('(=>)'); 
    
          // evento(=>)
          $._eje.shift().split(',').forEach( $eve_tip => {
        
            if( $eve_tip == $.eve.type ){
        
              $._eje.forEach( $eje => {
                
                // [obj].met[-tip](,)ele(,)...par
                $.par = $eje.split('(,)');
                $.fun = $.par.shift().split('-');
                // [-tip(,)]ele(,)par
                $.par = !!$.fun[1] ? [ $.fun[1], $.ele, ...$.par ] : [ $.ele, ...$.par ];
                // obj.met
                $.fun = $.fun[0].split('.');
                if( $.fun[1] ){ 
                  $.obj = !$.fun[0] ? '_doc' : $.fun[0] ; 
                  $.fun = $.fun[1]; 
                }else{ 
                  $.obj = 'window';
                  $.fun = $.fun[0];
                }
                try{ 
                  $.ins = eval( $.obj ); 
                }catch( $_err ){ 
                  console.error(`{-_-}.err: No existe el objeto '${$.obj}'...`); 
                }
                if( !!$.ins ){
                  if( !!$.ins[$.fun] && typeof($.ins[$.fun])=='function' ){
                    console.log( $.log=`{-_-}.${$.eve.type} => ${($.obj=='window')?'':$.obj+'.'}${$.fun}( ${$.par.join('(,)')} )` );
                    $_log.jso.push($.log);// log
                    $_.push( $.ins[$.fun]( ...$.par ) );// invocacion
                  }else{ 
                    console.error(`{-_-}.err: el método '${$.fun}' del objeto '${$.obj}' no es ejecutable...`);
                  }
                }        
              });
        
              return;
        
            }
          });
        });
      }
    }// ejecuto desde un elemento '_eje'
    else if( $dat.nodeName ){
      // ejecuciones asociadas : evento_1-1,evt_1-2(=>)obj.met-tip(,)ele(,)...opc(;;)evento_2(=>)...
      if( $dat.getAttribute('_eje') ){
        // cuál evento?
        if( $val ){
  
        }
      }
      
    }
    return $_;
  }

}
// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class _ele {

  // convierto y combino
  static val( $dat, $ele, $val ){
    let $_, $={};
    // convierto
    $_ = _obj.dec($dat,$val);
    // recorro 2° elemento
    _lis.ite($ele).forEach( $ele => {    
      for( const $atr in _obj.dec($ele,$val) ){ const $v = $ele[$atr];
        if( !($_[$atr]) ){ 
          $_[$atr] = $v;
        }else{
          switch($atr){
          case '_eje':// separador: "(;;)"
            _ele.eje($_,$v); 
            break;
          case 'class':
            _ele.cla($_,$v); 
            break;// separador: " "
          case 'style':
            _ele.css($_,$v); 
            break;// separador: ";"
          default: 
            $_[$atr] = $v;
            break;
          }
        }
      }
    });
    return $_;
  }
  // evento-ejecucion
  static eje( $dat, $ide, $val, ...$opc ){

    let $_ = $dat, $ = {
      '_eve':{ 'cli':"onclick", 'cam':"onchange", 'inp':"oninput" }
    };

    if( $ide = $._eve[$ide] ){

      if( !$ide || $val === undefined ){
        
        $_ = [];
        if( $ide ){
          
          if( $dat[$ide] ) $_ = $dat[$ide].split(';');
        }// todos
        else{          
          $_ = {};
          for( const $i in $._eve ){ const $v = $._eve[$i];
            $_[$v] = [];
            if( $dat[$v] ) $_[$v] = $dat[$v].split(';');
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
            $dat[$ide] = $val+( !!($dat[$ide]) ? ` ${$dat[$ide]}` : '' ); 
          }
          else if( $dat[$ide] ){
            $dat[$ide] += $val; 
          }
          else{
            $dat[$ide] = $val; 
          }
        }
        $_ = $dat;
      }
    }
    return $dat;
  }
  // clases
  static cla( $dat, $val, ...$opc ){
    let $_=$dat,$={};

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
          $dat.classList.forEach( $cla => _tex.dec($.ver).test($cla) && $dat.classList.remove($cla) && ( $_.push($cla) ) );
        }// cambio : si la tiene, la saca; sino, la pone
        else if( !!$.tog ){
          $dat.classList.forEach( $cla => _tex.dec($.ver).test($cla) && $dat.classList.toggle($ope) && ( $_.push($cla) ) );
        }        
      }// agrego clase/s
      else{
        
        if( $opc.includes('ini') ){

          $dat['class'] = $val + ( $dat['class'] ? ` ${$dat['class']}` : '' );
        }
        else if( $dat['class'] ){

          $dat['class'] += ` ${$val}`; 
        }
        else{

          $dat['class'] = $val; 
        }

        $_ = $dat;
      }
    }
    return $_;
  }
  // estilos
  static css( $dat, $val, ...$opc ){
    let $_=$dat, $={};

    if( $val === undefined ){
      $_ = {};
      for( const $i in $dat.style ){ 
        $_[$i] = $dat.style[$i];
      }
    }// operaciones
    else{
      // eliminar una regla
      if( $opc.includes('eli') ){

      }// actualizar una regla
      else if( $opc.includes('mod') ){

      }// agregar 1 o muchas
      else{
        if( !($dat['style']) ){ $dat['style']=''; } 
        if( $opc.includes('ini') ){ 
          $dat['style'] += $val+( $dat['style'] ? `;${$dat['style']}` : '' ); 
        }
        else if( $dat['style'] ){ 
          $dat['style'] += `;${$val}`;
        }
        else{
          $dat['style'] = $val; 
        }
      }
      $_ = $dat;
    }
    return $_;
  }
  // - fondo : background
  static fon( $val, $ope={} ){
    if( !$ope['tip'] ) $ope['tip']='png';
    if( !$ope['ali'] ) $ope['ali']='center';
    if( !$ope['tam'] ) $ope['tam']='contain';
    if( !$ope['rep'] ) $ope['rep']='no-repeat';
    return `background: ${$ope['rep']} ${$ope['ali']} / ${$ope['tam']} url('${$val}.${$ope['tip']}');`;
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
      _lis.ite($ope).forEach( $v => $.res.push( _ele.act( $ope, $val, ...$opc ) ) );
    }

    // resultados: [<>] => <> // si hay 1 solo, devuelvo único elemento
    $_ = _lis.val($.res);
    if( !$_.length ){ 
      $_ = false; 
    }else if( $_.length == 1 ){ 
      $_ = $_[0]; 
    }
    return $_;
  }
  // busco nodos
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
  }
  // actualizo propiedades
  static act( $tip, $ele, $val, $ope ){

    let $_ = [], $={ tip : $tip.split('_') };

    if( typeof($ele) == 'string' ){ 
      $ele = document.querySelectorAll($ele);       
    }
    else if( Array.isArray($ele) ){
      $ele[0] = ( !$ele[0] || typeof($ele[0]) == 'string' ) ? document.querySelector( $ele[0] ? $ele[0] : 'body' ) : $ele[0];
      $ele = $ele[0].querySelectorAll($ele[1] ? $ele[1] : '*');
    }

    $.lis = _lis.val($ele);
    switch( $.tip[0] ){
    case 'nod':
      if( !$.tip[1] ){             
        $.htm = { 
          'main':'app', 'article':'art', 'form':'for', 'fieldset':'fie', 'div':'div'
        };
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
    case 'cla':
      switch( $.tip[1] ){
      case 'val': $.lis.forEach( $v => $_.push( $v.classList.contains($val) ) ); break;
      case 'pos': $.lis.forEach( $v => $_.push( $v.classList.item($val) ) ); break;
      case 'tog': $.lis.forEach( $v => $_.push( $v.classList.toggle($val) ) ); break;
      case 'agr': $.lis.forEach( $v => $_.push( !$v.classList.contains($val) && $v.classList.add($val) ) ); break;
      case 'mod': $.lis.forEach( $v => $_.push( $v.classList.replace($val, $ope) ) ); break;
      case 'eli': $.lis.forEach( $v => $_.push( $v.classList.remove($val) ) ); break;    
      }
      break;
    case 'eje':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': $.lis.forEach( $v => $_.push( $v.addEventListener( $val, eval($ope) ) ) ); break;
      case 'eli': $.lis.forEach( $v => $_.push( $v.removeEventListener( $val, $ope ) ) ); break;
      }
      break;    
    case 'art':
      switch( $.tip[1] ){
      case 'ver': break;
      case 'agr': break;
      case 'mod': break;
      case 'eli': break;
      }
      break;
    }  
  }
  // agrego nodo/s
  static agr( $val, $pad, ...$opc ){
    let $_=[],$={};
    // recibo 1 o muchos
    $.val_uni = !Array.isArray($val);
    _lis.ite($val).forEach( $ele => {
      $.ite = $ele;
      if( typeof($ele) == 'string' ){
        $ele = _htm.dat($ele);
      }
      $_.push( $ele );
    });
    // agrego o modifico  
    if( typeof($pad)=='string' ){
      $pad = document.querySelector($pad);      
    }
    if( $pad ){
      $.val_ini = $opc.includes('ini');
      $_.forEach( $ele => {
        if( $.val_ini && $pad.children[0] ){
          $pad.insertBefore( $ele, $pad.children[0] );
        }else{
          $pad.appendChild( $ele );
        }
      });

    }
    return ( $.val_uni && $_[0] ) ? $_[0] : $_;
  }
  // modifico nodo
  static mod( $val, $pad, $mod, ...$opc ){
    let $_={},$={};
    // aseguro valor
    $.eti = _htm.dat($val);
    // busco padre
    if( typeof($pad)=='string' ){
      $pad = document.querySelector($pad); 
    }
    if( $pad ){
      // busco hermano
      if( typeof($mod)=='string' ){
        $mod = $pad.querySelector($mod); 
      }
      if( $mod ){
        $pad.replaceChild( $.eti, $mod );
      }else{
        $_ = _ele.agr( $.eti, $pad, ...$opc );
      }
    }
    return $_;
  }
  // elimino nodo/s
  static eli( $pad, $nod ){
    let $_=[];
    // elimino todos
    if( !$nod ){
      $nod = $pad.children;
    }// por seleccion
    else if( typeof($nod)=='string' ){
      $nod = $pad.querySelectorAll($nod);
    }
    if( $nod ){
      _lis.val($nod).forEach( $ele => $_.push( $pad.removeChild($ele) ) ); 
    }
    return $_;
  }
}
// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class _arc {  

  static val( $val ){ 
    let $ ={},
        $_=[];
    $._tip={ 'image':'ima', 'audio':'mus', 'video':'vid', 'text' :'tex' };
    for( let $i=0, $arc ; $arc = $val[$i] ; $i++ ){
      $.nom = $arc.name;
      $.val = $.nom.split('.');
      $.dia = new Date($arc.lastModified);
      $_.push({
        'obj':$arc,
        'ide':$.val[0].toLowerCase(),
        'ext':$.val[1].toLowerCase(),
        'tam':$arc.size,// convertir a rec.bit - KB, MB, ... -
        'mod':$.dia.toLocaleDateString(),
        'tip':$._tip[$arc.type.split('/')[0]]
      });
    }
    return $_;
  }
  
  static cod( $val, $tip ){
    let $_ = false;
    if( typeof($val)=='string' ){
      $_ = ( $tip=='dec' ) ? decodeURI($val) : encodeURI($val);
    }
    return $_;
  }

  static url( $val, $tip ){

    let $_ = `http://localhost/${ Array.isArray($val) ? $val.join('/') : $val }`;

    if( $tip === undefined ){
      window.location.href = $_;
    }
    else{

    }
    return $_;
  }

  static url_sec( $val, $tip ) {

    let $_ = window.location.href.split('#');

    if( $tip === undefined ){
      window.location.href = $_[0] + '#' + $val;
    }
    else{
      
    }
    return $_;    

  }

  static ima( $arc, $pad ){
    let $={
      'lis':[]
    };  
    _lis.ite($arc).forEach( $_arc => {
      if( typeof($_arc)=='object' ){
        $.ima = document.createElement('img');
        $.ima.src = URL.createObjectURL($_arc);
        $.lis.push($.ima);
      }
    });
    if( !!$pad && $pad.nodeName ){
      _ele.eli($pad);
      _ele.agr($.lis,$pad);
    }
    return $;
  }

}
// Texto : caracter + letra + oracion + parrafo
class _tex {  

  static cod( $dat ){
    // $& significa toda la cadena coincidente
    return $dat.toString().replace(/[.*+\-?^${}()|[\]\\]/g,'\\$&');
  }

  static dec( $dat, $opc = '' ){
    return new RegExp( $dat, $opc );
  }

  static agr( $dat, $tot = 0, $val = '', $lad = 'izq' ){
    let $_ = '';
    if( $lad=='izq' ){
      $_ = $dat.toString().padStart($tot,$val);
    }else if( $lad=='der' ){
      $_ = $dat.toString().padEnd($tot,$val);
    }else{
      $_ = $dat.toString();
    }
    return $_;
  }
  
  static fun( $ide, $val, ...$opc ){

    let $_ = "";

    switch( $ide ){
      case '': // Devuelve una cadena creada utilizando la secuencia de valores Unicode especificada.
        String.fromCharCode( num1, ...numN );
        break;
      case '': // Devuelve una cadena creada utilizando la secuencia de puntos de código especificada.
        String.fromCodePoint( num1, ...numN );
        break;
      case '': // Devuelve una cadena creada a partir de una plantilla literal sin formato.
        String.raw();
        break;
      case '': // Refleja la length de la cadena. Solo lectura.
        $_ = $val.length;
        break;
      case '': // Devuelve el caracter (exactamente una unidad de código UTF-16) en el index especificado.
        $_ = $val.charAt(index);
        break;
      case '': // Devuelve un número que es el valor de la unidad de código UTF-16 en el index dado.
        $_ = $val.charCodeAt( index );        
        break;
      case '': // Devuelve un número entero no negativo que es el valor del punto de código codificado en UTF-16 que comienza en la pos especificada.
        $_ = $val.codePointAt( pos );
        break;
      case '': // Combina el texto de dos (o más) cadenas y devuelve una nueva cadena.
        $_ = $val.concat( str, ...strN );
        break;
      case '': // Determina si la cadena de la llamada contiene searchString.
        $_ = $val.includes( searchString, position );
        break;
      case '': // Determina si una cadena termina con los caracteres de la cadena searchString.
        $_ = $val.endsWith( search, length );
        break;
      case '': // Devuelve el índice dentro del objeto String llamador de la primera aparición de searchValue, o -1 si no lo encontró.
        $_ = $val.indexOf( search, from );
        break;
      case '': // Devuelve el índice dentro del objeto String llamador de la última aparición de searchValue, o -1 si no lo encontró.
        $_ = $val.lastIndexOf( search, from );
        break;
      case '': // Devuelve un número que indica si la cadena de referencia compareString viene antes, después o es equivalente a la cadena dada en el orden de clasificación.
        $_ = $val.localeCompare( compare, locales, options );
        break;
      case '': // Se utiliza para hacer coincidir la expresión regular regexp con una cadena.
        $_ = $val.match( regexp );
        break;
      case '': // Devuelve un iterador de todas las coincidencias de regexp.
        $_ = $val.matchAll( regexp );
        break;
      case '': // Devuelve la forma de normalización Unicode del valor de la cadena llamada.
        $_ = $val.normalize( form ); 
        break;
      case '': // Rellena la cadena actual desde el final con una cadena dada y devuelve una nueva cadena de longitud targetLength.
        $_ = $val.padEnd( targetLength, padString );
        break;
      case '': // Rellena la cadena actual desde el principio con una determinada cadena y devuelve una nueva cadena de longitud targetLength.
        $_ = $val.padStart( targetLength, padString );
        break;
      case '': // Devuelve una cadena que consta de los elementos del objeto repetidos count veces.
        $_ = $val.repeat( count );         
        break;
      case '': // Se usa para reemplazar ocurrencias de searchFor usando replaceWith. searchFor puede ser una cadena o expresión regular, y replaceWith puede ser una cadena o función.        
        $_ = $val.replace( searchFor, replaceWith );
        break;
      case '': // Se utiliza para reemplazar todas las apariciones de searchFor usando replaceWith. searchFor puede ser una cadena o expresión regular, y replaceWith puede ser una cadena o función.
        $_ = $val.replaceAll(searchFor, replaceWith);        
        break;
      case '': // Busca una coincidencia entre una expresión regular regexp y la cadena llamadora.
        $_ = $val.search( regexp );
        break;
      case '': // Extrae una sección de una cadena y devuelve una nueva cadena.
        $_ = $val.slice( start, end );
        break;
      case '': // Devuelve un arreglo de cadenas pobladas al dividir la cadena llamadora en las ocurrencias de la subcadena sep.
        $_ = $val.split( sep, limit );
        break;        
      case '': // Determina si la cadena llamadora comienza con los caracteres de la cadena searchString.
        $_ = $val.startsWith( search, position );
        break;        
      case '': // Devuelve los caracteres en una cadena que comienza en la ubicación especificada hasta el número especificado de caracteres.
        $_ = $val.substr( from, length );
        break;
      case '': // Devuelve una nueva cadena que contiene caracteres de la cadena llamadora de (o entre) el índice (o indeces) especificados.
        $_ = $val.substring( start, end );
        break;
      case '': // Devuelve una cadena que representa el objeto especificado. Redefine el método toString().
        $_ = $val.toString();
        break;        
      case '': // Los caracteres dentro de una cadena se convierten a minúsculas respetando la configuración regional actual.
        $_ = $val.toLocaleLowerCase()
        break;
      case '': // Devuelve el valor de la cadena llamadora convertido a minúsculas.
        $_ = $val.toLowerCase();
        break;
      case '': // Devuelve el valor de la cadena llamadora convertido a mayúsculas.
        $_ = $val.toUpperCase();
        break;
      case '': // Recorta los espacios en blanco desde el principio y el final de la cadena. Parte del estándar ;
        $_ = $val.trim()
        break;
      case '': // Recorta los espacios en blanco desde el principio de la cadena.
        $_ = $val.trimStart();
        break;
      case '': // Recorta los espacios en blanco del final de la cadena.
        $_ = $val.trimEnd();
        break;
      case '': // Devuelve el valor primitivo del objeto especificado. Redefine el método valueOf().
        $_ = $val.valueOf(); 
        break;
    }

    return $_;
  } 

}
// Numero : separador + operador + entero + decimal + rango
class _num {  

  static val( $dat, $tot ){

    let $_ = $dat;

    if( !!$tot ){

      $_ = _tex.agr( $_, $tot, "0" );
    }
    // parse-int o parse-float
    else{
      
      $_ = Number($dat);
    }
    return $_;
  }

  static tip( $dat ){

    let $_ = false;

    if( Number($dat) !== NaN ){

      $_ = /\./.test( String($dat) ) ? 'dec' : 'int' ;
    }

    return $_;
  }

  static int( $dat ){

    if( typeof($dat) == 'string' ) $dat = parseInt($dat);

    return $dat.toFixed(0);
  }

  static dec( $dat, $val = 2 ){

    if( typeof($dat) == 'string' ) $dat = parseFloat($dat);

    return $dat.toFixed($val);
  }

  static ran( $dat, $max = 1, $min = 1 ){
    let $_ = $dat;

    if( typeof($_) == 'string' ) $_ = _num.val($_);

    while( $_ > $max ){ $_ -= $max; }

    while( $_ < $min ){ $_ += $max; }

    return $_;
  }
  
  static mat( $ide, $val, ...$opc ){

    switch( $ide ){
    case '': // Constante de Euler, la base de los logaritmos naturales, aproximadamente 2.718.
      $_ = Math.E;
      break;
    case '': // Logaritmo natural de 2, aproximadamente 0.693.
      $_ = Math.LN;
      break;
    case '': // Logaritmo natural de 10, aproximadamente 2.303.
      $_ = Math.LN1;
      break;
    case '': // Logaritmo de E con base 2, aproximadamente 1.443.
      $_ = Math.LOG2;
      break;
    case '': // Logaritmo de E con base 10, aproximadamente 0.434.
      $_ = Math.LOG10;       
      break;
    case '': // Ratio de la circunferencia de un circulo respecto a su diámetro, aproximadamente 3.14159.
      $_ = Math.P;
      break;
    case '': // Raíz cuadrada de 1/2; Equivalentemente, 1 sobre la raíz cuadrada de 2, aproximadamente 0.707.
      $_ = Math.SQRT1_;
      break;
    case '': // Raíz cuadrada de 2, aproximadamente 1.414.
      $_ = Math.SQRT;
      break;
    // Tené en cuenta que las funciones trigonométricas (sin(), cos(), tan(), asin(), acos(), atan(), atan2()) devuelven ángulos en radianes. 
    // Para convertir radianes a grados, dividí por (Math.PI / 180), y multiplicá por esto para convertir a la inversa.
    case '': // Devuelve el valor absoluto de un número. 
      $_ = Math.abs(x);
      break;
    case '': // Devuelve el arco coseno de un número.
      $_ = Math.acos(x);
      break;
    case '': // Devuelve el arco coseno hiperbólico de un número.
      $_ = Math.acosh(x);
      break;
    case '': // Devuelve el arco seno de un número.
      $_ = Math.asin(x);
      break;
    case '': // Devuelve el arco seno hiperbólico de un número.
      $_ = Math.asinh(x);      
      break;
    case '': // Devuelve el arco tangente de un número.
      $_ = Math.atan(x);
      break;
    case '': // Devuelve el arco tangente hiperbólico de un número.
      $_ = Math.atanh(x);    
      break;
    case '': // Devuelve el arco tangente del cuociente de sus argumentos.
      $_ = Math.atan2(y, x);
      break;
    case '': // Devuelve la raíz cúbica de un número.
      $_ = Math.cbrt();
      break;
    case '': // Devuelve el entero más pequeño mayor o igual que un número.
      $_ = Math.ceil(x);
      break;
    case '': // Devuelve el número de ceros iniciales de un entero de 32 bits.
      $_ = Math.clz32(x);
      break;
    case '': // Devuelve el coseno de un número.
      $_ = Math.cos(x);
      break;
    case '': // Devuelve el coseno hiperbólico de un número.
      $_ = Math.cosh();
      break;
    case '': // Devuelve Ex, donde x es el argumento, y E es la constante de Euler (2.718...), la base de los logaritmos naturales.
      $_ = Math.exp();
      break;
    case '': // Devuelve ex - 1.
      $_ = Math.expm1();
      break;
    case '': // Devuelve el mayor entero menor que o igual a un número.
      $_ = Math.floor(x);
      break;
    case '': // Devuelve la representación flotante de precisión simple más cercana de un número.
      $_ = Math.fround(x);
      break;
    case '': // Devuelve la raíz cuadrada de la suma de los cuadrados de sus argumentos.
      $_ = Math.hypot( x, y, ...z );
      break;
    case '': // Devuelve el resultado de una multiplicación de enteros de 32 bits.
      $_ = Math.imul(x, y);
      break;
    case '': // Devuelve el logaritmo natural (log, también ln) de un número.
      $_ = Math.log(x);
      break;
    case '': // Devuelve el logaritmo natural de x + 1 (loge, también ln) de un número.
      $_ = Math.log1p(x);
      break;
    case '': // Devuelve el logaritmo en base 10 de x.
      $_ = Math.log10(x);
      break;
    case '': // Devuelve el logaritmo en base 2 de x.
      $_ = Math.log2(x);
      break;
    case '': // Devuelve el mayor de cero o más números.
      $_ = Math.max( ...x );
      break;
    case '': // Devuelve el más pequeño de cero o más números.
      $_ = Math.min( ...x );
      break;
    case '': // Las devoluciones de base a la potencia de exponente, que es, baseexponent.
      $_ = Math.pow(x, y);
      break;
    case '': // Devuelve un número pseudo-aleatorio entre 0 y 1.
      $_ = Math.random();
      break;
    case '': // Devuelve el valor de un número redondeado al número entero más cercano.
      $_ = Math.round(x);
      break;
    case '': // Devuelve el signo de la x, que indica si x es positivo, negativo o cero.
      $_ = Math.sign(x);
      break;
    case '': // Devuelve el seno de un número.
      $_ = Math.sin(x);
      break;
    case '': // Devuelve el seno hiperbólico de un número.
      $_ = Math.sinh(x);
      break;
    case '': // Devuelve la raíz cuadrada positiva de un número.
      $_ = Math.sqrt(x);
      break;
    case '': // Devuelve la tangente de un número.
      $_ = Math.tan(x);
      break;
    case '': // Devuelve la tangente hiperbólica de un número.
      $_ = Math.tanh(x);
      break;
    case '': // Devuelve la cadena "Math".
      $_ = Math.toSource();
      break;
    case '': // Devuelve la parte entera del número x, la eliminación de los dígitos fraccionarios.
      $_ = Math.trunc(x);
      break;
    }
  }
}
// Fecha : aaaa-mm-dia hh:mm:ss utc
class _fec {

  static dec( $dat ){ 
    let $={},$_;
    $.tip = typeof($dat);
    if( !$dat ){
      $_ = new Date();
    }// por timestamp
    else if( $.tip == 'number' || ( $.tip == 'string' && /^\d+$/g.test($dat) ) ){ 
      $_ = new Date( parseInt($dat) );
    }// formateo e instancio
    else if( $.tip == 'string' ){ 
      $.fec = _fec.dat($dat);
      if( $.fec.año && $.fec.mes && $.fec.dia ){ 
        $_ = new Date( $.fec.año, $.fec.mes, $.fec.dia ); 
      }else{
        $_ = $.fec;
      }
    }// {}: objeto vacío
    else if( $.tip=='object' ){
      if( $dat.año && $dat.mes && $dat.dia ){
        $_ = new Date( $dat.año, $dat.mes, $dat.dia );
      }else{
        $_ = $dat;
      }
    }
    return $_;
  }

  static cod( $val, $sep ){
    let $_ = [], $ = {};
    $.val = $val;
    if( typeof($val) == 'string' ){
      if( !$sep ){
        $sep = /-/.test($val) ? '-' : ( /\//.test($val) ? '/' : '.' );
      }
      $.val = $val.split($sep).map( ite => parseInt(ite) );
    }
    if( $.val[0].length > 2 ){
      $_ = [ $.val[0], $.val[1], $.val[2] ];
    }else{
      $_ = [ $.val[2], $.val[1], $.val[0] ];
    }
    return $_;
  }

  static dat( $val, $sep='/' ){
    let $_={}, $={};
  
    if( typeof($val) != 'string' ){
      $val = _fec.val($val);
      if( !$val ){
        return $val;
      }
    }
  
    $.tie = $val.replace('T',' ').split(' ');  
    $.fec = $.tie[0].replace($sep,'-').split('-');
    $.hor = !!($.tie[1]) ? $.tie[1] : false;
  
    $_.mes = parseInt($.fec[1]);  
    if( $.fec[0].length==4){ 
      $_.año = parseInt($.fec[0]);
      $_.dia = parseInt($.fec[2]);
    }else{
      $_.año = parseInt($.fec[2]);
      $_.dia = parseInt($.fec[1]);
    }
  
    if( $.hor ){
      $_.tie = $.hor;
      $.hor = $.hor.split(':');
      if( !!($.hor[2]) ){
        $_.seg = parseInt($.hor[2]);
      }
      if( !!($.hor[1]) ){
        $_.min = parseInt($.hor[1]);
      }
      $_.hor = parseInt($.hor[0]);
    }
    $_.val = [$_.dia,$_.mes,$_.año].join($sep);
    if( _fec.val($_.val) ){
      $_.sem = _fec.tip($_,'sem');
    }else{
      $_ = false;
    }
    
    return $_;  
  }

  static val( $dat, ...$opc ){
    let $_=$dat, $={};
    $.fec_val = ( $año, $mes, $dia ) => {
      $_ = false;
      if( !!($año) && !!($mes) && !!($dia) ){
        if( ['1','3','5','7','8','10','12'].includes($mes) ){ 
          if( $dia > 0 && $dia <= 31){ 
            $_ = true; 
          }
        }else if( $mes != '2' ){ 
          if( $dia > 0 && $dia <= 30){ 
            $_ = true; 
          }
        }// CANTIDAD DE DIAS DE FEBRERO PARA ESE AÑO
        else{
          let $val_dia = new Date( $año || new Date().getFullYear(),2,0 ).getDate();
          if( $dia > 0 && $dia <= $val_dia){ 
            $_ = true; 
          }
        }
      }
      return $_;
    }  
  
    if( typeof($dat) == 'string' ){
      $dat = $dat.split(' ')[0];
      $dat = /-/.test($dat) ? $dat.split('-') : $dat.split('/');
    }
  
    if( $.tip_obj = _obj.tip($dat) ){
  
      if( $.tip_obj == 'pos' ){
        $.mes = $dat[1];
        if( $dat[0].length == 4 ){ 
          $.año = $dat[0]; 
          $.dia = $dat[2];
        }else{ 
          $.año = $dat[2]; 
          $.dia = $dat[0];
        }
      }else{
        $.año = !!($dat.año) ? $dat.año : 1900;
        $.mes = !!($dat.mes) ? $dat.mes : 1;
        $.dia = !!($dat.dia) ? $dat.dia : 1;
      }
  
      if( $.fec_val($.mes, $.dia, $.año) ){
        $_ = !$opc.includes('año') ? _num.val($.dia,2)+'/'+_num.val($.mes,2)+'/'+_num.val($.año,4) : _num.val($.año,4)+'/'+_num.val($.mes,2)+'/'+_num.val($.dia,2);
      }else{
        $_ = false;
      } 
    }
    return $_;
  }

  static tip( $dat, $tip='' ){
    let $_,$={};
    if( !$tip ){
      $_ = false;
      if( typeof($dat) != 'object' ){ 
        $dat = _fec.dat($dat);
      }
    }else{
      $ = _fec.dec($dat);
      switch( $tip ){
      case 'dyh':       
        $_ = `${$.getFullYear()}/${$.getMonth()+1}/${$.getDate()} ${$.getHours()}:${$.getMinutes()}:${$.getSeconds()}`;  
        break;
      case 'hor': 
        $_ = `${$_.getHours()}:${$_.getMinutes()}:${$_.getSeconds()}`;
        break;
      case 'dia': 
        $_ = `${$_.getFullYear()}/${$_.getMonth()+1}/${$_.getDate()}`;
        break;
      case 'sem': 
        $_ = `${$_.getMonth()+1}/${$_.getDate()}`;
        break;
      }
    }
    return $_;
  }

  static cue( $tip, $dat ){
    let $={},$_ = _fec.dat($dat)
    switch( $tip ){
      case 'mes':
        $_ = new Date( $_['año'] || new Date().getFullYear(), $_['mes'], 0 ).getDate()
        break;
      case 'año':
        $.tab=[]; $.tot=0; $.num=0; $.mes=0;
        for( let $i = 1; $i <= 12 ; $i++ ){ 
          $.tab[$i] = _fec.cue('mes',`${$_['año']}/${$i}/1`); 
          $.tot += $.tab[$i]; 
        }
        if( $_['mes'] == 1 ){ 
          $.num = $_['dia']; 
        }else{ 
          $.mes++;
          while( $.mes < $_['mes'] ){ $.num += $.tab[$mes_con]; $.mes++; } 
          $.num += $_['dia'];
        }
        $_={ 
          'tex':`${$.num} de ${$.tot}`, 
          'val':$.num, 
          'cue':$.tot 
        };
        break;
    }
    return $_;
  }

  static ver( $val, $ini, $fin ){
    let $_ = true, $ = {};
    if( !!$val ){
      $.val = Array.isArray($val) ? $val : _fec.cod($val);
      // fecha desde
      if( !!$ini ){
        $.ini = Array.isArray($ini) ? $ini : _fec.cod($ini);
        if( $.val[0] < $.ini[0] ){// el año es menor, oculto
          $_ = false;
        }else if( $.val[0] == $.ini[0] ){// mismo año
          if( $.val[1] < $.ini[1] ){
            $_ = false;
          }else if( $.val[1] == $.ini[1] ){// mismo mes
            if( $.val[2] < $.ini[2] ){
              $_ = false;
            }
          }
        }
      }
      // fecha hasta
      if( !!$fin && !!$_ ){
        $.fin = Array.isArray($fin) ? $fin : _fec.cod($fin);
        if( $.val[0] > $.fin[0] ){// si el año es mayor, oculto
          $_ = false;
        }else if( $.val[0] == $.fin[0] ){// mismo año
          if( $.val[1] > $.fin[1] ){
            $_ = false;
          }else if( $.val[1] == $.fin[1] ){// mismo mes
            if( $.val[2] > $.fin[2] ){
              $_ = false;
            }
          }
        }      
      }
    }  
    return $_;
  }  

}
// sincronario
class _hol {

  // getter
  static _( $ide, $val ){
    let 
      $_ = [], 
      $est = `hol_${$ide}`;
    
    if( $_api[$est] === undefined ){
      // pido datos
      // .. vuelvo a llamar esta funcion
    }
    if( !!($val) ){
      $_ = $val;
      switch( $ide ){
      case 'fec':
        // calculo fecha
        break;
      default:
        if( typeof($val) != 'object' ){

          if( Number($val) ) $val = parseInt($val)-1;

          $_ = $_api[$est] && !!($_api[$est][$val]) ? $_api[$est][$val] : {};
        }
        break;
      }
    }
    else{
      $_ = $_api[$est] ? $_api[$est] : [];
    }
    return $_;
  }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// documento
class _doc {

  // telcas : escape
  static inp( $dat ){
    let $={};

    switch( $dat.keyCode ){
    // Escape => ocultar modales
    case 27: 
      // menú contextual
      if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
        $.men.classList.add(DIS_OCU);
      }// pantallas
      else if( $.win = document.querySelector(`#win:not(.${DIS_OCU}) article header .ico[ide="eje_fin"]`) ){ 
        _app_ope.bot('win');
      }// navegacion
      else if( $.nav = document.querySelector(`aside.nav > [ide]:not(.${DIS_OCU})`) ){ 
        _app_ope.bot('nav');
      }
      break;
    }
  }
  // iconos
  static ico( $ide, $ele={} ){
    let $_="", $={
      '_ico':_api._('ico')
    };
    if( !!($._ico[$ide]) ){
      $ele['eti'] = !!($ele['eti']) ? $ele['eti'] : 'span';
      if( $ele['eti'] == 'button' ){
        if( !($ele['type']) ) $ele['type'] = "button";
      }
      $ele['ide'] = $ide;
      $htm = $._ico[$ide].val;
      $_ = `
      <${$ele['eti']}${_htm.atr(_ele.cla($ele,"ico material-icons-outlined",'ini'))}>
        ${$htm}
      </${$ele['eti']}>`;
    }
    return $_;
  }
  // letras : c - n
  static let( $dat, $ele={} ){

    let $_="", $pal, $_pal=[], $let=[], $_let = _api._('let'), $num = 0;
    
    if( $dat !== null && $dat !== undefined && $dat !== NaN ){      

      $dat.toString().split(' ').forEach( $pal => {
        
        $num = _num.val($pal);
        if( !!$num || $num == 0 ){
          if( /,/.test($pal) ) $pal.replaceAll(",","<c>,</c>");
          if( /\./.test($pal) ) $pal.replaceAll(".","<c>.</c>");
          $_pal.push( `<n>${$pal}</n>` );
        }
        else{
          $let = [];
          $pal.split('').forEach( $car =>{
            $ele = $ele;
            $num = _num.val($car);
            if( !!$num || $num == 0 ){
              $let.push( `<n>${$car}</n>` );
            }
            else if( $_let[$car] ){
              $let.push( `<c${_htm.atr(_ele.cla($ele,`${$_let[$car].tip} _${$_let[$car].cla}`,'ini'))}>${$car}</c>` );
            }
            else{
              $let.push( $car );
            }
          });
          $_pal.push( $let.join('') );
        }
      });
      $_ = $_pal.join(' ');
    }
    return $_;
  }
  // imagen : (span,button)[ima]
  static ima( ...$dat ){  
    let $_="",$={},$ele={};

    if( $dat[2] !== undefined ){

      if( /_ide$/.test($dat[1]) ){ $dat[1] = $dat[1].replace(/_ide$/,''); }

      $dat[3] = !!($dat[3]) ? $dat[3] : {};
      $dat[3]['ima'] = `${$dat[0]}.${$dat[1]}`;

      $_ = _doc_dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $dat[3]);
    }
    else{
      $ele = $dat[1] ? $dat[1] : {};
      $dat = $dat[0];
      $.tip = typeof($dat);
      $.fic_ide = '';
      if( $.tip == 'object' ){
    
        $ele = _ele.val( $dat, $ele );
      }
      else if( $.tip == 'string' ){
    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `_/${$dat}/ima`;
        $.fic_ide = $.dir+"."+$.tip;
        _ele.css( $ele, _ele.fon($.dir,{'tip':$.tip}) );
      }
    
      $ele['eti'] = !!($ele['eti']) ? $ele['eti'] : 'span';
      if( $ele['eti'] == 'button' ){
        if( !($ele['type']) ) $ele['type'] = "button";
      }      
      $.eti = $ele['eti'];
      $.htm='';
      if( !!($ele['htm']) ){
        _ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
      }
      if( !$ele['ima'] && $.fic_ide ){ $ele['ima'] = $.fic_ide; }
      $_ = `<${$.eti}${_htm.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }
}
// contenedor
class _doc_val {

  // pestañas por contenido
  static nav( $dat, $ope, ...$opc ){
    let $={};
    // capturo contenedor
    $.nav = $dat.parentElement;
    // con toggles
    $.val_tog = $opc.includes('tog');
    // seleccion anterior
    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){
      // capturo contenido
      $.lis = $.nav.nextElementSibling;
      // recorro items
      _lis.val( $.lis.children ).forEach( $e => {
        // coincide con el seleccionado
        if( $e.dataset.ide == $ope ){
          // hago toogles
          if( $.val_tog ){
            
            $e.classList.toggle(DIS_OCU);
            $dat.classList.toggle(FON_SEL);
          }
          // muestro y selecciono
          else if( $e.classList.contains(DIS_OCU) ){

            $e.classList.remove(DIS_OCU);
            $dat.classList.add(FON_SEL);
          }              
        }// oculto los no coincidentes
        else if( !$e.classList.contains(DIS_OCU) ){ 

          $e.classList.add(DIS_OCU); 
        }
      });
    }      
  }
  // variable : div.atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};
    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $_app.var = _ele.ver($dat,{'eti':'form'});
      $.var_ide = '';
      if( $dat.name ){
        $.var_ide = $dat.name;
      }
    }else{
      switch( $tip ){
      case 'mar':
        if( $ope ){
          $dat.parentElement.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e.classList.remove($ope) );
          $dat.classList.add($ope);
        }
        break;
      case 'tog':
        if( $ope ){
          $dat.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e != $dat && $e.classList.remove($ope) );
          $dat.classList.toggle($ope);
        }
        break;
      }
    }
    return $;
  }  
  // visibilidad de bloques
  static tog( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){
      
      $.ite = $dat.parentElement;
      if( 
        ( $.bot = $.ite.querySelector('.ico[ide="ope_tog"]') ) 
        && ( $.sec = $.ite.nextElementSibling )
      ){        
      
        if( $.bot.classList.contains('ocu') ){
          $.bot.classList.remove('ocu');
          $.sec.classList.remove(DIS_OCU);
        }
        else{
          $.bot.classList.add('ocu');
          $.sec.classList.add(DIS_OCU);
        }
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $_app.var = _ele.ver($dat,{'eti':"form"}) ){        

        $.lis = !!$_app.var.nextElementSibling ? $_app.var.nextElementSibling : $_app.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.val > .ico[ide="ope_tog"]${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }
  // filtros : texto
  static ver(){    
  }    
}
// Dato
class _doc_dat {

  // alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $=_doc_val.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $_app.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $_app.var.querySelectorAll(`div.atr > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $_app.var.querySelectorAll(`div.atr > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $_app.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
        $.ide = $atr.name;

        $.tip = '';
        if( $atr.type ){
          if( /^date/.test($atr.type) ){
            $.tip = 'fec';
          }else if( $atr.type=='text' ){
            $.tip = 'tex';
          }else if( $atr.type=='number' ){
            $.tip = 'num';
          }
        }

        $._err[$.ide] = [];
        $._val[$.ide] = $.val = $atr.value;
        
        // obligatorio ( not null )
        if( !$.val ){          
          if( $atr.required ){
            $._err[$.ide].push(`Es Obligatorio...`);
          }// formateo nulos
          else{
            switch( $.tip ){
            case 'fec': $._val[$.ide] = null; break;
            case 'num': $._val[$.ide] = null; break;
            }
          }
        }
        // máximos ( longitud[tex] - valor[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            if( !$.val ){
              // $._err[$.ide].push(`No puede ser anterior al ${$.min}...`);
            }break;
          case 'num':
            if( $.val < parseInt($.min) ){
              $._err[$.ide].push(`No puede ser menor a ${$.min}...`);
            }break;
          case 'tex':
            if( $.val.length < parseInt($.min) ){
              $._err[$.ide].push(`No puede tener menos de ${$.min} caractéres...`);
            }break;
          }
        }
        // mínimos ( valores[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            if( !$.val ){
              // $._err[$.ide].push(`No puede ser posterior que el ${$.max}...`);
            }break;
          case 'num': 
            if( $.val > parseInt($.max) ){
              $._err[$.ide].push(`No puede ser mayor que ${$.max}...`);
            }break;
          case 'tex': 
            if( $.val.length > parseInt($.max) ){
              $._err[$.ide].push(`No puede tener más de ${$.max} caractéres...`);
            }break;
          }
        }
        // proceso errores
        if( $._err[$.ide].length ){
          // pinto fondo
          if( !$atr.classList.contains('fon-roj') ){ 
            $atr.classList.add('fon-roj'); 
          }
          // cargo lista
          $._tex = `
          <ul class='col-roj'>`; 
            $._err[$.ide].forEach( $e => $._tex += `
            <li>${_doc.let($e)}</li>`
            ); $._tex += `
          </ul>`;
          _ele.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $_app.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$_app.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $_app.var.dataset.esq ) && ( $.est = $_app.var.dataset.est ) ){
          _eje.val(['_doc.dat_val', [ $.esq, $.est, $tip, $._val ] ], $e => {            
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $_app.var.reset();              
              // reiniciar página
              window.location.href = ( $.tip_eli ) ? window.location.href.split('/').slice(0,-1).join('/') : window.location.href;
            }
          });
        }// proceso propio
        else{

        }
      }   
      break;    
    }
    return $;
  }
  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_doc_val.var($dat);

    $ = _dat.ide($dat,$);
    $ope = _dat.var($.esq,$.est,$ope);

    if( ($.dat_val = $_api.dat_val[$.esq][$.est]) && typeof($ope)=='object' ){

      if( $tip == 'tit' ){
        $_ = _obj.val($ope,$.dat_val.nom) + ( $.dat_val.des ? "\n"+_obj.val($ope,$.dat_val.des) : '' );
      }
      else if( !!($.dat_val[$tip]) ){
        $_ = _obj.val($ope,$.dat_val[$tip]);  
      }
      if( $tip == 'ima' ){
        $_ = _doc.ima( { 'style':$_ }, !!$opc[0] ? $opc[0] : {} );
      }
      else if( !!$opc[0] ){
        if( !($opc[0]['eti']) ){ 
          $opc[0]['eti'] = 'p'; 
        }
        $opc[0]['htm'] = _doc.let($_);
        $_ = _htm.val($opc[0]);
      }
    }
    else{
      $_ = `<span ima='' title='error de ficha: ${ !$.dat_val ? ` para el dato ${$.esq}.${$.est}` : ` no existe el objeto asociado` }'></span>`;
    }

    return $_;
  }
  // opciones : esquema.estructura.atributos.valores
  static opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_doc_val.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { if( $.ope = $_app.var.querySelector(`[name="${$i}"]`) ) _ele.eli( $.ope, `option:not([value=""])` ); });
    };
    switch( $tip ){
    case 'esq':
      $.ini();
      break;
    case 'est':
      $.ini();
      $.val = $dat.value.split('.');
      if( $.ope = $dat.nextElementSibling.nextElementSibling ){
        $.ope.value = "";
        _ele.act('cla_agr', [$.ope,`[data-esq][data-est]`], DIS_OCU );
        if( $.val[1] ) _ele.act('cla_eli', [$.ope,`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`], DIS_OCU );
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector 
      if( $.opc = $dat.nextElementSibling.querySelector('select') ){
        _ele.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = _dat.ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          _eje.val(['_dat::var', [ `_${$.esq}.${$.est}` ] ], $dat => {
            $.opc = _doc_opc.val( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }  
  // filtros : dato + variables
  static ver( $tip, $dat, $ope, ...$opc ){

    let $=_doc_val.var($dat);

    $._tip = $tip.split('-');

    $.cla_ide = `_val${ !! $opc[0] ? `-${$opc[0]}` : '-ver' }`;

    $dat.forEach( $e => $e.classList.contains($.cla_ide) && $e.classList.remove($.cla_ide) );

    $dat.forEach( $e => $e.classList.contains(`${$.cla_ide}-bor`) && $e.classList.remove(`${$.cla_ide}-bor`) );

    $_app.var = $ope.querySelector(`form[ide="${$tip}"]`);

    // datos de la base : estructura > valores [+ima]
    if( $._tip[0] == 'dat' ){
      $.dat_est = $_app.var.querySelector(`[name="est"]`);
      $.dat_val = $_app.var.querySelector(`[name="val"]`);
      $.dat_ide = $_app.var.querySelector(`[name="ver"]`);
      // actualizo dependencia
      if( $.dat_ide && $.dat_ide.value && $.dat_val && $.dat_val.value ){
          
        $ = _dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = _dat.var($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ) ){

            if( $.dat[$.atr] == $.dat_val.value ){

              $e.classList.add($.cla_ide);
              $e.classList.add(`${$.cla_ide}-bor`);
            } 
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $._tip[0]=='pos' || $._tip[0]=='fec' ){
      
      // elimino valor de dato por seleccion
      if( ( $.ver = $ope.querySelector(`form[ide="dat"] select[name="val"]`) ) && !!$.ver.value ) $.ver.value = '';
            
      // valores
      $.val = {};
          
      ['ini','fin','inc','cue'].forEach( $ide => {
        // capturo valores
        if( ( $.ite = $_app.var.querySelector(`[name="${$ide}"]`) ) && !!$.ite.value ){

          $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? _num.val($.ite.value) : $.ite.value;
        }
      });
      
      // valido: si el inicio es mayor que el final
      if( $.val.ini && $.val.ini > $.val.fin ){
        
        $_app.var.querySelector(`[name="ini"]`).value = $.val.ini = $.val.fin;
      }
      // si el final es mejor que el inicio
      if( $.val.fin && $.val.fin < $.val.ini ){

        $_app.var.querySelector(`[name="fin"]`).value = $.val.fin = $.val.ini;
      }    
      // inicializo incremento
      if( ( !$.val.inc || $.val.inc <= 0 ) && ( $.ite = $_app.var.querySelector(`[name="inc"]`) ) ){
        $.ite.value = $.val.inc = 1;
      }
      $.inc_val = 1;
      // inicializo limites desde
      if( !$.val.fin && ( $.ite = $_app.var.querySelector(`[name="fin"]`) ) && ( $.max = $.ite.getAttribute('max') ) ){
        $.val.fin = $.max;
      }
      // filtro por posicion de lista
      if( $tip == 'pos' ){
        
        $.pos_val = 0;

        $dat.forEach( $e => {
          // valor por desde-hasta
          $.pos_val++;            
          if( $.inc_val == 1 && $.pos_val >= $.val.ini && $.pos_val <= $.val.fin ){
            $e.classList.add($.cla_ide); 
            $e.classList.add(`${$.cla_ide}-bor`);
          }// aplico salto
          $.inc_val++;
          if( $.inc_val > $.val.inc ) $.inc_val = 1;
        });
      }
      // filtro por valor de fecha
      else if( $tip == 'fec' ){

        $.val.ini = $.val.ini ? $.val.ini.split('-') : '';
        $.val.fin = $.val.fin ? $.val.fin.split('-') : '';

        $dat.forEach( $e => {
          // valor por desde-hasta
          if( $.inc_val == 1 && _fec.ver( $e.getAttribute('api-fec'), $.val.ini, $.val.fin ) ){
            $e.classList.add($.cla_ide);
            $e.classList.add(`${$.cla_ide}-bor`);
          }// aplico salto
          $.inc_val++;
          if( $.inc_val > $.val.inc ) $.inc_val = 1;
        });
      }

      // limito resultado
      if( $.val.cue ){

        $.lis = $dat.filter( $e => $e.classList.contains($.cla_ide) );
        // ultimos
        if( $_app.var.querySelector(`.ico[ide="nav_fin"].bor-sel`) ) $.lis = $.lis.reverse();

        $.val_cue = 0;
        $.lis.forEach( $e => {
          $.val_cue ++;
          if( $.val_cue > $.val.cue ){
            $e.classList.remove($.cla_ide);
            $e.classList.remove(`${$.cla_ide}-bor`);            
          }
        });
      }
    }
    
  }  
  // acumulados : posicion + marca + seleccion
  static acu( $tip, $dat, $ope, ...$opc ){

    let $_, $=_doc_val.var($dat);

    switch( $tip ){
    case 'act':
      // actualizo acumulados
      if( $opc.length == 0 ) $opc = Object.keys($_app.val.acu);

      $opc.forEach( $ide => {

        // acumulo elementos del listado
        $_app.val.acu[$ide] = [];
        $dat.querySelectorAll(`._val-${$ide}`).forEach( $e => $_app.val.acu[$ide].push($e) );

        // actualizo total del operador
        if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

          $.tot.innerHTML = $_app.val.acu[$ide].length;
        }
      });

      // calculo el total grupal
      $.val_tot = _doc_dat.acu('tot',$tip);
      if( $.tot = $ope.querySelector(`[name="cue"]`) ){
        $.tot.innerHTML = $.val_tot.length;
      }

      // devuelvo el total
      $_ = $.val_tot;           
      break;
    case 'tot': 
      $.tot = [];    
      for( const $ide in $_app.val.acu ){ 
        if( $dat ){
          // hacer algo con esto
        }
        for( const $ite of $_app.val.acu[$ide] ){
          if( !$.tot.includes($ite) ) $.tot.push($ite);
        }
      }
      // devuelvo total    
      $_ = $.tot;
      break;
    }
    return $_; 
  }
  // sumatorias
  static sum( $dat, $ope ){
    let $=_doc_val.var($dat);

    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;

      $dat.forEach( $ite => $.sum += parseInt($ite.getAttribute(`${$val.dataset.esq}-${$val.dataset.est}`)) );

      _doc_dat.fic( $val, $.sum);
      
    });
  }
  // conteos : valores de estructura relacionada por atributo
  static cue( $tip, $dat, $ope, ...$opc ){
    let $=_doc_val.var($dat);

    switch( $tip ){
    // actualizo cuentas por valores
    case 'act':
      $.val_tot = $dat.length;

      $ope.querySelectorAll(`table[data-esq][data-est]`).forEach( $tab => {

        $.esq = $tab.dataset.esq;
        $.est = $tab.dataset.est;
                  
        if( $.atr = $tab.dataset.atr ){

          $tab.querySelectorAll(`tr[data-ide]`).forEach( $ite => {

            $.ide = $ite.dataset.ide;
            $.tot = 0;
            
            $dat.forEach( $v => {

              if( $.dat = $v.getAttribute(`${$.esq}-${$.est}`) ){

                if( ( $.dat_val = _dat.var($.esq,$.est,$.dat) ) && ( $.dat_ide = $.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
              }
            });

            $ite.querySelector('td[data-atr="tot"] > n').innerHTML = $.tot;
            $ite.querySelector('td[data-atr="por"] > n').innerHTML = $.val_tot ? _num.dec( ( $.tot / $.val_tot ) * 100 ) : $.val_tot;
          });
        }
      });
      break;
    // filtro por valor textual
    case 'ver':

      $.ope = $_app.var.querySelector('[name="ope"]').value;
      $.val = $_app.var.querySelector('[name="val"]').value;
      $.lis = $_app.var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll('tr.dis-ocu').forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( _val.ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU);
          }
          else if( !$e.classList.contains(DIS_OCU) ){
            $e.classList.add(DIS_OCU);
          }
        });
      }
      break;              
    }    
  }
  // imagen : identificadores
  static ima( $dat, $ope, ...$opc ){
    let $_="";

    if( $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.getAttribute(`${$ope.esq}-${$ope.est}`) ) ){

      if( !$ope.fic ) $ope.fic = _dat.val_ver('ima', $ope.esq, $ope.est );

      $_ = _doc.ima($ope.fic.esq, $ope.fic.est, _dat.var($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }

    return $_;
  }
  // ficha : imagenes por valor con relaciones por estructura
  static fic( $dat, $ope, ...$opc ){
    let $_="", $=_doc_val.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`div.atr`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[$.atr] = ( $ope > 0 ) ? _num.ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[$.atr];
    });

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`div.atr div[data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      
      if( $.val = $.dat[$.est] ){

        $.ima = $ite.dataset.ima.split('.');
        _ele.mod( _doc.ima( $.ima[0], $.ima[1], _dat.var($.esq,$.est,$.val)[$.atr], {'class':`tam-2`} ), $ite, '[ima]' );
      }
      else{
        _ele.eli($ite,'[ima]');          
      }
    });   
    
    return $_;
  }
}
// Tablero
class _doc_tab {

  // inicializo : opciones, posicion, filtros
  static ini(){

    let $={};      

    if( !$_app.tab.cla ){
      $_app.tab.cla = '.pos';
    }
    // inicializo opciones
    $_app.tab.ide = $_app.tab.dat.getAttribute('tab');
          
    ['opc'].forEach( $ope => {
      
      if( $_app.tab[$ope] ){

        $_app.tab[$ope].querySelectorAll(`form[ide] [onchange*="_doc_tab."]:is( input:checked, select:not([value=""]) )`).forEach( 
          
          $inp => _doc_tab[`${$ope}_${_ele.ver($inp,{'eti':`form`}).getAttribute('ide')}`]( $inp )
        );
      }
    });
    // marco posicion principal
    _doc_tab.val_pos();

    // actualizo opciones
    Object.keys($_app.val.acu).forEach( $ite =>
      ( $.ele = $_app.tab.acu.querySelector(`[name="${$ite}"]:checked`) ) && _doc_tab.val_acu($.ele) 
    );          

  }

  // actualizo : acumulados, sumatorias, fichas, listado
  static act( $dat ){
    
    let $={};
    
    $dat = !$dat ? Object.keys($_app.val.acu) : _lis.ite($dat);

    $.dat = $_app.tab.dat;

    // actualizo valores acumulados
    if( $_app.tab.acu ) $.tot = _doc_dat.acu('act',$.dat,$_app.tab.acu,...$dat );
    
    // actualizo sumatorias
    if( $_app.tab.sum ) _doc_dat.sum($.tot, $_app.tab.sum);

    // fichas del tablero
    if( ( $_app.tab.pos ) && ( $.ima = $_app.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $_app.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );

      if( $.ope.length > 0 ) _doc_tab.opc_pos($.ima);
    }

    // actualizo valores de lista
    if( $_app.est.acu ){
      // actualizo acumulados
      if( !$_app.est.acu.querySelector(`[name="tod"]:checked`) ) _doc_est.val_acu();
      // ejecuto filtros + actualizo totales      
      if( $_app.est.ver ) _doc_est.ver('val');
    }    
  }

  // Seleccion : datos, posicion, fecha
  static ver( $tip, $dat, $ope ){

    let $=_doc_val.var($dat);      

    if( $tip == 'val' ){

      for( const $ide in $_app.val.ver ){

        if( $.ele = $_app.tab.ver.querySelector($_app.val.ver[$ide]) ){

          _doc_tab.ver($ide,$.ele,$_app.tab.dat);

          break;
        } 
      }
    }
    else{
      // ejecuto filtros por tipo : pos, fec
      
      _doc_dat.ver( $tip, _lis.val( $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}`)), $_app.tab.ver, 'ver');
    }
    // actualizo calculos + vistas( fichas + items )
    _doc_tab.act('ver');

  }  

  // valores
  static val(){
  }// acumulados( posicion + marcas + seleccion )
  static val_acu( $dat, $ope ){
    
    let $=_doc_val.var($dat);

    if( !$.var_ide && $ope ) $ = _doc_val.var( $dat = $_app.tab.acu.querySelector(`[name="${$ope}"]`) );

    $.cla_ide = `_val-${$.var_ide}`;
    
    if( !$dat.checked ){            
      $_app.tab.dat.querySelectorAll(`.${$.cla_ide}`).forEach( $e => $e.classList.remove($.cla_ide) );
    }
    // busco marcas y aplico bordes
    else{
      $_app.tab.dat.querySelectorAll(`.${$.cla_ide}-bor`).forEach( $e => $e.classList.add($.cla_ide) );
    }

    // actualizo calculos + vistas( fichas + items )        
    _doc_tab.act($.var_ide);

  }// por posicion principal
  static val_pos( $dat ){
    
    let $=_doc_val.var($dat);
    
    if( $_hol_app && $_hol_app._val && ( $.kin = $_hol_app._val.kin ) ){

      $_app.tab.dat.querySelectorAll(`.pos[hol-kin="${$.kin}"], .pos[hol-kin="${$.kin}"] [pos][hol-kin="${$.kin}"]`).forEach( $e => {

        $e.classList.add(`_val-pos`);
        $e.classList.add(`_val-pos-bor`);
      });
      // actualizo totales
      _doc_tab.act('pos');
    }

  }// por marcas del tablero
  static val_mar( $dat ){
    
    let $=_doc_val.var($dat);      

    if( $_app.tab.acu && $_app.tab.acu.querySelector(`[name="mar"]:checked`) ){
        
      $.pos = $dat.getAttribute('pos') ? $dat : $dat.parentElement;// desde elemento del tablero / lista  

      if( !$.pos.getAttribute('tab') ){

        $.pos.classList.toggle(`_val-mar`);
        $.pos.classList.toggle(`_val-mar-bor`);
      }
      // actualizo totales
      _doc_tab.act('mar');
    }
    
  }// por seleccion : datos + posicion + fecha
  static val_ver( $dat ){
    
    let $=_doc_val.var($dat);    

    for( const $ide in $_app.val.ver ){

      if( $.ele = $_app.tab.ver.querySelector($_app.val.ver[$ide]) ){

        _doc_tab.ver($ide,$.ele,$_app.tab.dat);

        break;
      } 
    }

    _doc_tab.act('ver');

  }

  // opciones
  static opc(){
  }// secciones : bordes + colores + imagen + ...
  static opc_sec( $dat ){

    let $=_doc_val.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$_app.tab.dat.classList.contains('bor-1') ){ $_app.tab.dat.classList.add('bor-1'); }
        $_app.tab.dat.querySelectorAll('ul[tab]:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $_app.tab.dat.classList.contains('bor-1') ){ $_app.tab.dat.classList.remove('bor-1'); }
        $_app.tab.dat.querySelectorAll('ul[tab].bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $_app.tab.dat.querySelectorAll(`[tab][class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $_app.tab.dat.classList.contains('fon-0') ){
          $_app.tab.dat.classList.remove('fon-0');
        }
      }else{
        // secciones
        $_app.tab.dat.querySelectorAll(`[tab][class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$_app.tab.dat.classList.contains('fon-0') ){
          $_app.tab.dat.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $_app.tab.dat.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $_app.tab.dat.style.backgroundImage = '';
      }
      break;      
    }     
  }// posiciones : borde + color + imagen + texto + numero + fecha
  static opc_pos( $dat ){

    let $=_doc_val.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $_app.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      Object.keys($_app.val.acu).forEach( $ver =>{
        if( $[$.var_ide][$ver] = $_app.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      if( $_app.tab.dep ){
        $.eli = `${$_app.tab.cla} [pos]:not([tab])[class*='${$.ope}']`;
        $.agr = `${$_app.tab.cla} [pos]:not([tab])`;
      }
      else{
        $.eli = `${$_app.tab.cla}[class*='${$.ope}']`;
        $.agr = `${$_app.tab.cla}`;
      }

      $_app.tab.dat.querySelectorAll($.eli).forEach( $e => _ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $.col = _dat.val_ver('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        $.col = ( $.col && $.col.val ) ? $.col.val : 0;

        $_app.tab.dat.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = _dat.var($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : _num.ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $_app.tab.dat.querySelectorAll($_app.tab.cla).forEach( $e => {
        $e.querySelectorAll('[ima]').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = _dat.ide($dat.value,$);
        
        // busco valores de ficha
        $.fic = _dat.val_ver('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        // actualizo por opciones
        $_app.tab.dat.querySelectorAll($_app.tab.cla).forEach( $e => {
          $.htm = '';
          if( $.ima.pos || $.ima.ver || $.ima.mar ){

            if( $.ima.pos && $e.classList.contains('_val-pos') ){ 
              $.htm = _doc_dat.ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar') ){ 
              $.htm = _doc_dat.ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver') ){ 
              $.htm = _doc_dat.ima($e,$);
            }
          }// todos
          else{
            $.htm = _doc_dat.ima($e,$);
          }
          if( $.htm ) _ele.mod($.htm,$e,'[ima]','ini');
        });      
      }
      break;
    // valores: num - tex - fec
    default:
      if( $.var_ide == 'num' ){ 
        $.eti = 'n';           
      }
      else if( $.var_ide == 'fec' ){ 
        $.eti = 'time'; 
      }
      // textos
      else{
        $.eti = 'p';
      }
      $_app.tab.dat.querySelectorAll($_app.tab.cla).forEach( $e => _ele.eli($e,$.eti) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $_app.tab.dat.querySelectorAll($_app.tab.cla).forEach( $e =>{

          if( $.obj = _dat.var($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

            if( !($.tex = $e.querySelector($.eti)) ){
              
              $.tex = document.createElement($.eti);
              $e.appendChild($.tex);
            }
            $.tex.innerHTML = $.obj[$.atr];
          }
        });
      }
      break;
    }
  }  
}
// Estructura
class _doc_est {
 
  // inicializo por acumulados
  static ini(){

    let $={};   

    if( $_app.est.acu ){

      if( $.ele = $_app.est.acu.querySelector(`[name="tod"]`) ){

        _doc_est.val_tod($.ele);
      }
    }

  }
  // actualizo valores : acumulado + cuentas + descripciones
  static act(){

    let $={};

    // actualizo total
    if( $_app.est.acu && ( $.tot = $_app.est.acu.querySelector('[name="cue"]') ) ){
      
      $.tot.innerHTML = _doc_est.cue();
    }
    // actualizo cuentas
    if( $_app.est.cue ){

      _doc_dat.cue('act', $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`), $_app.est.cue);
    }
    // actualizo descripciones
    if( $_app.est.des ){

      $_app.est.des.querySelectorAll(`[name]:checked`).forEach( $e => _doc_est.des_tog($e) );
    }
  }
  // cuento items en pantalla
  static cue(){

    if( $_app.est.dat ){
      
      return $_app.est.dat.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`).length;
    }
    else{
      return 'err: no hay tabla relacionada';
    }
  }

  // valores : todos | acumulados
  static val(){
  }// check : todos ? - o por acumulados
  static val_tod( $dat ){

    let $=_doc_val.var($dat);  
    
    if( $_app.est.acu ){
      // ajusto controles acumulados
      Object.keys($_app.val.acu).forEach( $i => {

        if( $.val = $_app.est.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
      });
    }

    // muestro todos
    if( $dat.checked  ){

      _ele.act('cla_eli',$_app.est.dat.querySelectorAll(`tbody tr[pos].${DIS_OCU}`),DIS_OCU);

    }// o por acumulados
    else if( $_app.est.acu ){        

      _doc_est.val_acu();
    }

    // ejecuto filtros
    if( $_app.est.ver ){

      _doc_est.ver('val');
    }

    // actualizo total, cuentas y descripciones
    _doc_est.act();
  }// acumulados : posicion - marcas - seleccion
  static val_acu(){

    let $={};
    
    if( ( $.esq = $_app.est.dat.dataset.esq ) && ( $.est = $_app.est.dat.dataset.est ) ){
      
      // oculto todos los items de la tabla
      _ele.act('cla_agr',$_app.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`),DIS_OCU);

      // actualizo por acumulado
      Object.keys($_app.val.acu).forEach( $ide => {

        if( $.val = $_app.est.acu.querySelector(`[name='${$ide}']`) ){
          $.tot = 0;
          if( $.val.checked ){

            $_app.val.acu[$ide].forEach( $e =>{
              
              if( $.ele = $_app.est.dat.querySelector(`tbody tr[pos][${$.esq}-${$.est}="${$e.getAttribute(`${$.esq}-${$.est}`)}"].${DIS_OCU}`) ){
                $.tot++;
                $.ele.classList.remove(DIS_OCU);
              }
            });            
          }

          // actualizo total
          if( $.val.nextElementSibling && ( $.ele = $.val.nextElementSibling.querySelector('n') ) ){
            $.ele.innerHTML = $.tot;
          }
        }          
      });
    }

  }

  // filtros : datos + posicion + atributos
  static ver( $tip, $dat, $ope, ...$opc ){

    let $=_doc_val.var($dat);      

    // todos los filtros
    if( $tip == 'val' ){

      for( const $ide in $_app.val.ver ){

        if( $.ele = $_app.est.ver.querySelector($_app.val.ver[$ide]) ){

          _doc_est.ver($ide,$.ele);
        } 
      }
    }// por tipos
    else{
      // por ciclos + agrupaciones
      if( ['cic','gru'].includes($tip) ){
        
        // muestro todos los items
        $_app.est.dat.querySelectorAll(`tbody tr:not([pos]).${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
        
        // aplico filtro        
        // ... 

      }
      // por seleccion
      else if( $_app.est.ver ){        
        // ejecuto acumulados
        if( $_app.est.acu ){
          // muestro todos
          if( $_app.est.acu.querySelector(`[name="tod"]:checked`) ){

            _ele.act('cla_eli',$_app.est.dat.querySelectorAll(`tbody tr[pos].${DIS_OCU}`),DIS_OCU);
          }// muestro solo acumulados
          else{
            _doc_est.val_acu();
          }                  
        }             
        
        $.eje = false;
        if( $tip == 'dat' ){

        }// pos + fec : ejecuto filtro si tiene valor inicial
        else if( $tip == 'pos' || $tip == 'fec' ){

          $.ini = $_app.var.querySelector(`[name="ini"]`);
          $.eje = $.ini.value;
        }

        if( $.eje ){
          // ejecuto filtros
          // por selector
          _doc_dat.ver( $tip, _lis.val( $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`) ), $_app.est.ver, 'ver');

          // oculto valores no seleccionados
          $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(._val-ver,.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );      
        }
      }
      // actualizo total, cuentas y descripciones
      _doc_est.act();
    }
  }  
  
  // columnas : toggles + atributos
  static col(){
  }// muestro-oculto
  static col_tog( $dat ){

    let $=_doc_val.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $_app.est.dat.querySelectorAll(`:is(thead,tbody) :is(td,th)[data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$dat.name}"]`).forEach( $ite => {
        // muetro columna
        if( $dat.checked ){
          $ite.classList.contains(DIS_OCU) && $ite.classList.remove(DIS_OCU);
        }// oculto columna
        else if( !$ite.classList.contains(DIS_OCU) ){
          $ite.classList.add(DIS_OCU);
        }
      });
    }
    // botones: ver | ocultar x todas las columnas
    else{
      $dat.parentElement.parentElement.nextElementSibling.querySelectorAll('input[type="checkbox"]').forEach( $e => {
          
        $e.checked = ( $dat.dataset.val == 'ver' );

        _doc_est.col_tog($e);
      });
    }
  }

  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static des(){
  }// muestro-oculto
  static des_tog( $dat ){

    let $=_doc_val.var($dat);    

    $.ope  = $_app.var.getAttribute('ide');
    $.esq = $_app.var.dataset.esq;
    $.est = $_app.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    $_app.est.dat.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ).forEach( 
      $ite => $ite.classList.add(DIS_OCU)
    );
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = _dat.var($.esq,$.est,$ite.getAttribute(`${$.esq}-${$.est}`)) ) && $.val[$.atr] ){

          $.ide = ( $.ope == 'des' ) ? $ite.getAttribute(`${$.esq}-${$.est}`) : $.val[$.atr];            

          $_app.est.dat.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ).forEach( 
            $e => $e.classList.remove(DIS_OCU)
          );
        }
      });
    }   

  }// filtro por descripciones
  static des_ver( $dat, $ope, ...$opc ){

    let $=_doc_val.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $_app.est.dat.querySelectorAll(`tbody tr:not([data-ope="des"])[opc="${$.ite}"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $_app.est.ver.querySelector(`form[ide="dat"] select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide = $_app.est.ver.querySelector(`form[ide="dat"] select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite=='tit' ){
          if( $.opc!='gru' || (!!$.ide && $.ide.value) ){// no considero agrupaciones sin valor
            $.agr = !!$.ide && $.ide.value ? `[ide="${$.ide.value}"]` : '';
            $_app.est.dat.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) )
          }
        }// descripciones por item no oculto
        else{
          $_app.est.dat.querySelectorAll(`tbody tr:not([pos]):not(.${DIS_OCU})`).forEach( $e =>{                
            if( $.lis_ite = $_app.est.dat.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU); 
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){
      // desmarco otras opciones
      $_app.est.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`).forEach( $e => $e.checked = false );
      // oculto todas las leyendas
      $_app.est.dat.querySelectorAll(`tbody tr[data-ope="${$tip}"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $_app.est.dat.querySelectorAll(`tbody tr:not([pos]):not(.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $_app.est.dat.querySelector(`table tr[data-ope="${$tip}"][data-atr="${$dat.value}"][ide="${$e.dataset.ide}"].${DIS_OCU}`) ){                
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }    
    
  }
}
// Listado
class _doc_lis {

  // punteos, numeraciones, y términos
  static ite(){
  }
  static ite_val( $dat, $ope ){
    let $=_doc_val.var($dat);
    
    if( !$ope ){
      // toggles
      if( $dat.nodeName == 'DT' ){

        $.dd = $dat.nextElementSibling;

        while( $.dd && $.dd.nodeName == 'DD' ){
          $.dd.classList.toggle(DIS_OCU);
          $.dd = $.dd.nextElementSibling;
        }
      }
    }else{
      switch( $ope ){

      }
    }
  }
  static ite_tog( $dat, $ope ){

    let $=_doc_val.var($dat);

    if( !$dat || !$ope ){
      _ele.act('cla_tog',$.lis.children,DIS_OCU); 
    }
    else{
      _lis.val($.lis.children).forEach( $ite => {

        if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){

          if( $ite.nextElementSibling ){
            if( 
              ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
              ||
              ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
            ){
              _doc_lis.ite_val($ite);
            }
          }
        }
      } );
    }
  }  
  static ite_ver( $dat, $ope ){
    let $=_doc_val.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $_app.var.querySelector('[name="val"]').value ){
        // oculto todos
        _ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $_app.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            if( $.ope_val = _val.ver($e.innerHTML,$.ope,$.val) ){
              $e.classList.remove(DIS_OCU);
            }else{
              $e.classList.add(DIS_OCU);                 
            }
            $.dd = $e.nextElementSibling;
            while( $.dd && $.dd.nodeName == 'DD' ){
              if( $.ope_val ){ 
                $.dd.classList.remove(DIS_OCU); 
              }else{ 
                $.dd.classList.add(DIS_OCU); 
              }
              $.dd = $.dd.nextElementSibling;
            }
          });
        }
        else{
          _lis.val($.lis.children).forEach( $e => 
            _val.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        _ele.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': _ele.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': _ele.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $_app.var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  // valores con titulo
  static val(){      
  }
  static val_ver( $dat, $ope = 'p:first-of-type', $cla = 'let-luz' ){

    let $=_doc_val.var($dat);

    // busco listado
    if( $_app.var ){
      $.lis = !! $_app.var.nextElementSibling ? $_app.var.nextElementSibling : $_app.var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }

    // ejecuto filtros
    if( $.lis && ( $.ope = $_app.var.querySelector(`[name="ope"]`) ) && ( $.val = $_app.var.querySelector(`[name="val"]`) ) ){
      
      // elimino marcas anteriores      
      $.lis.querySelectorAll(`li.ite ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );
      
      // muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.ite ${$ope}`).forEach( $ite => {

        // capturo item : li > [.val] (p / a)
        $.ite = _ele.ver($ite,{'eti':'li'});

        // ejecuto comparacion por elemento selector ( p / a ), y oculto/mustro item
        if( !$.val.value || _val.ver($ite.innerText, $.ope.value, $.val.value) ){
          $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
          !!$.val.value && $ite.classList.add($cla);// agrego brillo
        }
        else if( !$.ite.classList.contains(DIS_OCU) ){
          $.ite.classList.add(DIS_OCU);
        }
      } );      
      // por cada item mostrado, muestro ascendentes
      $.tot = 0;
      if( $.val.value ){
        $.lis.querySelectorAll(`li.ite:not(.${DIS_OCU})`).forEach( $ite => {
          $.tot ++;
          $.val = $ite;
          while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI' && $.ite.classList.contains('ite') ){
            $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
            $.val = $.ite;
          }
        } );
        $.tog = ['ver','tod'];
      }
      else{
        $.tog = ['ocu','nad'];
      }
      
      // actualizo toggle
      if( $.tog[1] && ( $.ico = $_app.var.querySelector(`.ico[ide="ope_${$.tog[1]}"]`) ) ){
        
        _doc_val.tog($.ico,$.tog[1]);
      }            
      
      // actualizo total
      if( $.tot_val = $_app.var.querySelector(`[name="tot"]`) ){

        $.tot_val.innerHTML = $.tot;
      }            
    }      
  }

  // indice : enlaces del navegador
  static nav(){      
  }
  // click en item
  static nav_val( $dat, $cla = FON_SEL ){

    let $ = { lis : _ele.ver($dat,{'eti':'nav'}) };

    if( $.lis ){
      // elimino marcas previas
      $.lis.querySelectorAll(
        `ul.lis.nav :is( li.ite.sep, li.ite:not(.sep) > div.val ).${$cla}`
      ).forEach( 
        $e => $e.classList.remove($cla) 
      );

      // controlo el toggle automatico por dependencias
      if( 
        ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
        &&
        ( $dat.classList.contains('ico') || $.dep.classList.contains(DIS_OCU) ) 
      ){
        _doc_val.tog($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }
  // hago toogle por item
  static nav_tog( $lis ){

    let $={};

    if( $.nav = $lis ? _doc_lis.nav_mar($lis) : false ){
      // hago toogles ascendentes
      while( 
        ( $.lis = _ele.ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('ico') ){                
          _doc_val.tog($.ico);
        }                
      }
    }
  }
  // marco valor seleccionado
  static nav_mar( $lis ){

    let $nav;

    // hago toogle por item
    if( $_app.uri.val && ( $nav = $lis.querySelector(`a[href="#${$_app.uri.val}"]`) ) ){
        
      _doc_lis.nav_val($nav);
    }

    return $nav;
  }
  // ejecuto filtros
  static nav_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    _doc_lis.val_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    _doc_lis.nav_tog($_app.var.nextElementSibling);

  }  

  // desplazamiento horizontal x item
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $=_doc_val.var($dat);

    if( $tip == 'val' ){

      $.lis = $_app.var.previousElementSibling;

      $.val = $_app.var.querySelector('[name="val"]');
      $.pos = _num.val($.val.value);

      switch( $dat.getAttribute('name') ){
      case 'ini': $.pos = _num.val($.val.min);
        break;
      case 'pre': $.pos = $.pos > _num.val($.val.min) ? $.pos-1 : $.pos;
        break;
      case 'pos': $.pos = $.pos < _num.val($.val.max) ? $.pos+1 : $.pos;
        break;
      case 'fin': $.pos = _num.val($.val.max);
        break;
      }
      // valido y muestro item
      $.val.value = $.pos;

      _ele.act('cla_agr',$.lis.querySelectorAll(`li[pos]:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li[pos="${$.pos}"]`) ) $.ite.classList.remove(DIS_OCU);
    }
  }

  // tabla
  static tab( $dat, $ele, ...$opc ){
    let $ = {};

    // 1- cabecera
    $.tab_cab = document.createElement('thead');
    $.cab_lis = document.createElement('tr');

    if( $dat[0] ){
      for( const $atr in $dat[0] ){
        $.cab_ide = document.createElement('th');
        $.cab_ide.innerHTML = $atr;
        $.cab_lis.appendChild($.cab_ide);
      }
      $.tab_cab.appendChild($.cab_lis);
    }

    // 2-cuerpo
    $.tab_dat = document.createElement('tbody');
    $dat.forEach( $dat => {
      $.lis = document.createElement('tr');
      for( const $atr in $dat ){
        $.dat_ite = document.createElement('td');
        $.dat_ite.innerHTML = $dat[$atr];
        $.lis.appendChild($.dat_ite);
      }
      $.tab_dat.appendChild($.lis);
    });
    // 3-pie
    // ...

    $.tab = document.createElement('table');
    $.tab.appendChild($.tab_cab);
    $.tab.appendChild($.tab_dat);

    return $.tab;
  } 
}
// Opcion
class _doc_opc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $={};

    switch( $tip ){
    case 'val': 
      if( !$ope.nodeName ){
        $.opc = document.createElement('select');
        for( const $atr in $ope ){
          $.opc.setAttribute($atr,$ope[$atr]);
        }
        $ope = $.opc;
      }
      if( $opc.includes('nad') ){
        $.ite = document.createElement('option');
        $.ite.value = ''; $.ite.innerHTML = '{-_-}';
        $ope.appendChild($.ite);
      }
      $.val_ide = $opc.includes('ide');
      $dat.forEach( ($dat,$ite) => {
        $.ite = document.createElement('option');
        // identificador
        if( $dat.ide ){ $.ide = $dat.ide; }else if( $dat.pos ){ $.ide = $dat.pos; }else{ $.ide = $ite; }
        // valor
        $.ite.setAttribute('value',$.ide);
        // titulo
        if( !!$dat.des || !!$dat.tit ) $.ite.setAttribute('title', !! $dat.des ? $dat.des : $dat.tit );
        // contenido
        $.htm = "";
        if( !!$.val_ide && !!$dat.ide ){ $.htm += `${$dat.ide}: `; }
        if( !!$dat.nom ){ $.htm += $dat.nom; }
        $.ite.innerHTML = $.htm;
        $ope.appendChild($.ite);
      });
      $_ = $ope;
      break;
    }
    return $_;      
  }
  // selector
  static val( $dat, $ope, ...$opc ){
    let $_, $={};

    if( $ope && !$ope.nodeName ){
      $.opc = document.createElement('select');
      for( const $atr in $ope ){ $.opc.setAttribute($atr,$_[$atr]); }
      $_ = $.opc;
    }else{
      $_ = $ope;
    }
    $.val = '';
    if( $ope.value ){
      $.val = $ope.value;
    }else if( $ope.val ){
      $.val = $ope.val;
    }

    _doc_opc.lis($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }
  // opction
  static lis( $dat, $val, ...$opc ){
    let $_=[], $={};    

    if( $opc.includes('nad') ){
      $.ite = document.createElement('option');
      $.ite.value = ''; 
      $.ite.innerHTML = '{-_-}';
      $_.push($.ite);
    }

    $.val_ide = $opc.includes('ide');

    for( const $ide in $dat ){ $.obj_tip = _obj.tip($dat[$ide]); break; }
    $.obj_pos = $.obj_tip == 'pos';

    for( const $ide in $dat ){ const $ite = $dat[$ide];

      $.ite = document.createElement('option');
      $.val = $ide;
      $.htm = "";
      if( !$.obj_tip ){        
        $.htm = $ite;
      }
      else if( $.obj_pos ){        
        $.htm = $ite.join(', ');
      }
      else{
        // valor
        if( $ite.ide ){ $.val = $ite.ide; }else if( $ite.pos ){ $.val = $ite.pos; }
        // titulo
        if( !!$ite.des || !!$ite.tit ) $.ite.setAttribute('title', !! $ite.des ? $ite.des : $ite.tit );
        // contenido        
        if( !!$.val_ide && !!$ite.ide ) $.htm += `${$ite.ide}: `;
        if( !!$ite.nom ) $.htm += $ite.nom;
      }      
      $.ite.setAttribute('value',$.val);
      if( $val == $.val ) $.ite.setAttribute('selected',"");
      $.ite.innerHTML = $.htm;
      $_.push($.ite);
    }
    return $_;
  }  
}
// Número
class _doc_num {
  
  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
  // al actualizar
  static act( $dat ){

    let $={};

    $.val = _num.val($dat.value);

    // excluyo bits
    if( $dat.type != 'text' ){

      // valido minimos y máximos
      if( ( $.min = _num.val($dat.min) ) && $dat.value && $.val < $.min ) $dat.value = $.val = $.min;    

      if( ( $.max = _num.val($dat.max) ) && $dat.value && $.val > $.max ) $dat.value = $.val = $.max;

      // relleno con ceros
      if( $dat.getAttribute('num_pad') && ( $.num_cue = $dat.maxlength ) ) $.num_pad = _num.val($.val,$.num_cue);

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
}
// Fecha
class _doc_fec {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Texto
class _doc_tex {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Archivo
class _doc_arc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Objeto
class _doc_obj {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = _val.tip($v);

      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-1');
      $.ite.innerHTML = `
        <q class='ide'>${$i}</q> <c class='sep'>:</c>
      `;
      if( ![undefined,NaN,null,true,false].includes($v) ){

        $.ite.innerHTML += _doc.let( ( $.tip.dat=='obj' ) ? JSON.stringify($v) : $v.toString() ) ;          
      }
      else{
        $.ite.innerHTML += `<c>${$v}</c>`;
      }

      $_.appendChild($.ite);
    }

    return $_;      
  }
  // 
}
// Ejecucion
class _doc_eje {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

}
// Elemento
class _doc_ele {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    case 'her':
      $.her = [];
      $.ele = $dat; 
      while( $.ele ){ 
        $.nom = $.ele.nodeName.toLowerCase();
        if( $.nom=='#document' || $.nom=='html' ){
          break;
        }
        $.tex=`<font class='ide'>${$.nom}</font>`; 
        if( $.ele.id ){ 
          $.tex += `<c>#</c><font class='val'>${$.ele.id}</font>`; 
        }
        else if( $.ele.name ){ 
          $.tex += `<c>[</c><font class='val'>${$.ele.name}</font><c>]</c>`; 
        }
        if( $.ele.className){
          $.cla_lis = $.ele.className.split(' ').map( $ite => `<c>.</c><font class='val'>${$ite}</font>` );
          $.tex += `${$.cla_lis.join('')}`;
        }
        $.her.push($.tex);
        $.ele = ( $.ele.parentNode) ? $.ele.parentNode : false;
      }
      $_ = $.her.reverse().join(`<c class='sep'>></c>`);
      break;            
    case 'eti':
      $.nom = $dat.nodeName.toLowerCase();
      $.fin=`
      <c class='lis'><</font>
      <c>/</c>
      <font class='ide'>${$.nom}</font>
      <c class='lis'>></c>`;
      $.eti_htm=''; 
      if( $dat.childNodes.length>0 ){ 
        _lis.val($dat.childNodes).forEach( $e =>{
          if( $e.nodeName.toLowerCase() != '#text' ){ $.eti_htm += `
            <li>${this.ele('eti',$e)}</li>`;
          }else{ $.eti_htm += `
            <li>${_doc.let($e.textContent)}</li>`;
          }
        });
      }
      $.eti_atr=[];
      $dat.getAttributeNames().forEach( $atr => $.eti_atr.push(`
        <li class='mar_hor-1'>
          <font class='val'>${$atr}</font>
          <c class='sep'>=</c>
          <q>${$dat.getAttribute($atr)}</q>
        </li>`)
      );
      $_ =`
      <div var='ele_eti' class='dat'>

        <div class='ite ini'>

          <c class='lis sep'><</font>
          <font class='ide' onclick='_doc_ele.act(this,'ver');'>${$.nom}</font>
          <ul class='val _atr'>
            ${$.eti_atr.join('')}
          </ul>
          <c class='lis sep'>></c>          

          <p class='tog dis-ocu'>
            c onclick='_doc_ele.act(this,'ver');' class='tog dis-ocu'>...</>
            ${$.fin}
          </p>
        </div>`;
        if( !['input','img','br','hr'].includes($.nom) ){
          $_ += `
          <ul class='val _nod mar_hor-3'>
            ${$.eti_htm}
          </ul>

          <div class='ite tog fin'>
            ${$.fin}
          </div>  `;
        }$_ += `
      </div>`;
      break;
    }

    return $_;      
  }
  // ...
  static act( $dat ){
    let $={};

    $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )

    $dat.nextElementSibling.classList.add(FON_SEL);

    $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');
    // resultado
    $.res = $dat.parentElement.parentElement.previousElementSibling.querySelector('div.ele');

    _ele.eli($.res);

    $.res.innerHTML = _doc.ele('eti',document.querySelector($.ver));
  }
  // ...
  static val( $dat ){
    let $={};

    $.ope = document.createElement('form');

    $.lis = document.createElement('ul');

    $.lis.classList.add('lis');

    $dat.forEach( $e => {
      $.ite = document.createElement('li');            
      $.ite.classList.add('mar_ver-2');            
      $.ite.innerHTML = `${_doc.ico('val_ver',{'class':"tam-1 mar_der-1",'onclick':"_doc_ele.act(this);"})}`;
      $.tex = document.createElement('p');
      $.tex.innerHTML = _doc.ele('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });

    return [ $.ope, $.lis ];     
  }
}