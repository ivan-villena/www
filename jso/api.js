
// WINDOW
'use strict';

const SYS_NAV = "http://localhost/";
const SYS_REC = "http://localhost/_/";

var $_log = { 'php':[], 'jso':[] };

// Interface
class _api {

  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){     

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }

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

          $_ = $_api[`_${$ide}`] && $_api[`_${$ide}`][$.cod = parseInt($dat)-1] !== undefined ? $_api[`_${$ide}`][$.cod] : {};
        }
        // por identificador
        else{
          $_ = $_api[`_${$ide}`] && $_api[`_${$ide}`][$dat] !== undefined ? $_api[`_${$ide}`][$dat] : {};
        }
        break;
      }
    }
    else{
      $_ = $_api[`_${$ide}`] !== undefined ? $_api[`_${$ide}`] : [];
    }
    return $_;
  }  

  // devuelvo enlace desde url
  uri( ...$opc ){
    let $_="",$={};
  
    if( !!this._uri.esq ){
      
      $_ = `${this._uri.esq}`;
  
      if( !!this._uri.cab ){
  
        $_ += `/${this._uri.cab}`;
  
        if( !!this._uri.art ){
  
          $_ += `/${this._uri.art}`;
  
          if( $opc.includes('val') && !!this._uri.val ){
  
            $_ += `/${this._uri.val}`;
          }
        }
      }
    }        
    return $_;
  }

  // sesion del usuario
  ses(){
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Dato : esq.est[ide].atr
class _dat {
  
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
  // identificador: esq.est[...atr]
  static ide( $dat='', $val={} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }
  // valores : nombre, descripcion, titulo, imagen, color...
  static val( $esq, $est, $atr, $dat ) {
    let $={}, $_ = false;         
    $._val = $_api._dat_val[$esq][$est];
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
  }
  // ver valores : imagen, color...
  static ver( $tip, $esq, $est, $atr, $dat ){
    
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
    if( !!$_api._var_tip[$ide] ){
      $_ = $_api._var_tip[$ide];
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
  static dom( $ele, $rel, $dep, $ope, $val ){    
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
  // propiedades
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
}
