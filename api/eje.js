// WINDOW
'use strict';

// Ejecucion : ( ...par ) => { ...cod } : val 
class eje {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_eje || $api_eje[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_eje[$est];

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
    else{
      $_ = $_dat ? $_dat : [];
    }
    return $_;
  }

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
      $sis_log.php.push($_);
      // ejecuto peticion
      $_ = $_ajax( encodeURI($_) ).then( $ => {
          // proceso error
          if( $['_err'] ){ 
            console.error($['_err']); 
          }// descomprimo respuesta
          else if( $._ !== undefined ){
            $val( $._ );
          }// envio todo lo recibido
          else{
            $val( $ );
          }
      }).catch( $ =>{ 
          console.error( $, $val.toString() );
        })
      ;
    }
    return $_;
  }
  // codifico ejecucion
  static val_cod( $val, $par ){
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

  static fun( $dat, $val ){
    if( $dat.nodeName ){
      $.pad = $dat.parentElement.parentElement;
      ['par','val','ini','inv'].forEach( $v => $[$v] = $.pad.querySelector(`[${$v}]`).value );
      $.fun = eval(`( ${$.par} ) =>{ ${$.val} return ${$.ini} }`);
      if( $.fun ){// defino un array para desestructurar e invocar
        $_ = !!($.inv) ? $.fun( ...eval(`[${$.inv}]`) ) : $.fun() ;
        $.tip = dat.tip($_);// evaluo e imprimo resultado 
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
          $.eje.shift().split(',').forEach( $eve_tip => {
        
            if( $eve_tip == $.eve.type ){
        
              $.eje.forEach( $eje => {
                
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
                }
                else{ 
                  $.obj = 'window';
                  $.fun = $.fun[0];
                }
                // ejecucion
                try{ 
                  $.ins = eval( $.obj ); 
                }
                catch( $_err ){ 
                  console.error(`{-_-}.err: No existe el objeto '${$.obj}'...`); 
                }
                if( !!$.ins ){
                  if( !!$.ins[$.fun] && typeof($.ins[$.fun])=='function' ){

                    console.log( $.log=`{-_-}.${$.eve.type} => ${($.obj=='window')?'':$.obj+'.'}${$.fun}( ${$.par.join('(,)')} )` );

                    $sis_log.jso.push($.log);// log

                    $_.push( $.ins[$.fun]( ...$.par ) );// invocacion
                  }
                  else{ 
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