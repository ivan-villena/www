// WINDOW
'use strict';

// Dato : esq.est[ide].atr
class api_dat {

  _var = {};
 
  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_dat || $api_dat[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_dat[$est];

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
  
  // getter por : objetos | consultas
  static get( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $.esq = $dat;
      $.est = $ope;
      $_ = $val;

      if( !$val || !api_obj.val_tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval(`api_${$.esq}`) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = api_ele.val_ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = api_lis.val_est('ver',$dat,$ope);
    }
    return $_;
  }// tipo
  static tip( $val ){
    let $tam = 0, $ide = typeof($val);
    
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
    }
    // busco
    $.tip_lis = api_dat._('tip');
    return !!$.tip_lis[$ide] ? $.tip_lis[$ide] : false;     
  }// comparacion de valores
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
    case '^^':  $_ = api_tex.val_dec(`^${api_tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!^':  $_ = api_tex.val_dec(`^[^${api_tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    case '$$':  $_ = api_tex.val_dec( `${api_tex.val_cod($val)}$`, $opc.join('') ).test( $dat.toString() ); break;
    case '!$':  $_ = api_tex.val_dec( `[^${api_tex.val_cod($val)}]$`, $opc.join('') ).test( $dat.toString() ); break;
    case '**':  $_ = api_tex.val_dec( `${api_tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!*':  $_ = api_tex.val_dec( `[^${api_tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    }
    return $_;
  }// identificador: esq.est[...atr]
  static ide( $dat='', $val={} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }
  
  // Variable : form > .dov_var > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $api_doc._var = api_ele.val_ver($dat,{'eti':'form'});
      $.var_ide = $dat.getAttribute('name');
    }
    else{
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
  }// toggles por : form > fieldsets > ...
  static var_tog( $ide ){
    api_lis.val_cod( api_ele.val_ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }  

  /* Estructura
  */
  // Cargo operador
  static est_ope( $esq, $est, $ope, $dat ) {

    let $={}, $_ = $api_dat._est_ope[$esq][$est];

    // cargo atributo
    $.ope_atr = $ope.split('.');
    $.ope_atr.forEach( $ide => {
      $_ = ( typeof($_) == 'object' && !!($_[$ide]) ) ? $_[$ide] : false;
    });

    // proceso valores con datos
    if( $_ && $.ope_atr[0] == 'val' && !!($dat) ) $_ = api_obj.val( api_dat.get($esq,$est,$dat), $_ );

    return $_;
  }  

  /* Valor
  */
  // p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = api_dat.ide($dat,$);
    // cargo datos
    $._dat = api_dat.get($.esq,$.est,$ope);
    // cargo valores
    $._val = api_dat.est_ope($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = api_obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+api_obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $_ = api_obj.val($._dat,$._val[$tip]);  
    }
    // armo ficha
    if( $tip == 'ima' ){
      $.ele = !!$opc[0] ? $opc[0] : {};

      // identificador
      $.ele['data-esq'] = $.esq;
      $.ele['data-est'] = $.est;
      $.ele['data-ide'] = $._dat.ide;
      
      // titulos
      if( $.ele.title === undefined ){

        $.ele.title = api_dat.val('tit',`${$.esq}.${$.est}`,$._dat);
      }
      else if( $.ele.title === false ){        
        delete($.ele.title);
      }
      // acceso informe
      if( $.ele.onclick === undefined ){
        if( api_dat.est_ope($.esq,$.est,'inf') ) $.ele.onclick = `dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
      }
      else if( $.ele.onclick === false ){

        delete($.ele.onclick);
      }
      // informe      
      $_ = api_fig.ima( { 'style': $_ }, $.ele );
    }
    else if( !!$opc[0] ){
      
      if( !($opc[0]['eti']) ) $opc[0]['eti'] = 'p'; 
      $opc[0]['htm'] = api_tex.let($_);
      $_ = api_ele.val($opc[0]);
    }

    return $_;
  }// opciones : esquema.estructura.atributos.valores
  static val_opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = api_dat.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $api_doc._var.querySelector(`[name="${$i}"]`) ) api_ele.val_eli( $.ope, `option:not([value=""])` ); 
      });
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
        api_ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          api_ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        api_ele.val_eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = api_dat.ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          api_eje.val(['api_dat::get', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = api_opc.lis( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }// identificador por seleccion : imagen, color...
  static val_ide( $tip, $esq, $est, $atr, $dat ){
    
    // dato
    let $={}, $_ = { 'esq': $esq, 'est': $est };

    // armo identificador
    if( !!($atr) ) $_['est'] = $atr == 'ide' ? $est : `${$est}_${$atr}`;
    
    // valido dato
    if( !!( $.dat_Val = api_dat.est_ope($_['esq'],$_['est'],`val.${$tip}`,$dat) ) ){
      $_['ide'] = `${$_['esq']}.${$_['est']}`;
      $_['val'] = $.dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;    
  }// imagen por identificadores
  static val_ima( $dat, $ope ){

    let $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){

      if( !$ope.fic ) $ope.fic = api_dat.opc('ima', $ope.esq, $ope.est );

      $_ = api_fig.ima($ope.fic.esq, $ope.fic.est, api_dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
  
  /* Ficha : imagenes por valor ( relaciones por estructura ) 
  */
  static fic( $dat, $ope, ...$opc ){
    let $_="", $ = api_dat.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.dat_var`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? api_num.val_ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.dat_var [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      api_ele.val_eli($ite,'.fig_ima');
      if( $.val = $.dat[$.est] ){
        api_ele.val_agr( api_fig.ima( $.ima[0], $.ima[1], api_dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }

  /* Informe : 
  */
  static inf( $esq, $est, $val ){
    // pido ficha
    api_eje.val([ `api_dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) api_doc.win('doc_ope',{ 
        ico: "", 
        cab: "", 
        htm: $htm, 
        opc: [ "pos" ] 
      });
    });
  }

  // alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $ = api_dat.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $api_doc._var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $api_doc._var.querySelectorAll(`.dat_var > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $api_doc._var.querySelectorAll(`.dat_var > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $api_doc._var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
        $.ide=$atr.name;

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
            <li>${_tex.let($e)}</li>`
            ); $._tex += `
          </ul>`;
          api_ele.val_agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $api_doc._var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$api_doc._var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $api_doc._var.dataset.esq ) && ( $.est = $api_doc._var.dataset.est ) ){
          api_eje.val(['dat::abm', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $api_doc._var.reset();              
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
}