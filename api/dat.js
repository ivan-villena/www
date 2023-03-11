// WINDOW
'use strict';

// Dato
class api_dat {  

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_app.dat_est('dat',$ide,'dat');

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

  /* Dato : estructura | objeto | valor */
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
      
      $_ = ( $.ver = api_ele.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = api_lis.val_est('ver',$dat,$ope);
    }
    return $_;
  }  
  /* Tipo */
  static tip( $val ){

    let $tam = 0, $ide = typeof($val), $dat_tip;
    
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
    $dat_tip = api_fig._('ico');
    return !!$dat_tip[$ide] ? $dat_tip[$ide] : false;
  }
  /* Comparaciones */
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
  }

  /* Variable : form > .doc_var > label + (select,input,textarea,button)[name] */
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $sis_app.dat.var = api_ele.ver($dat,{'eti':'form'});
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
    api_lis.val_cod( api_ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }  

  /* Valores */
  static val( $tip, $ide, $dat, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = sis_app.dat_ide($ide,$);
    // cargo datos
    $._dat = api_dat.get($.esq,$.est,$dat);
    // cargo valores
    $._val = sis_app.dat_est($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = api_obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+api_obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $.tip_val = typeof($._val[$tip]);
      if( $tip == 'ima' ){
        if( $.tip_val != 'string' ) $tip = 'tab';
      }
      else if( $.tip_val == 'string' ){
        $_ = api_obj.val($._dat,$._val[$tip]);  
      }
    }

    // armo ficha
    if( $tip == 'ima' ){
      
      // identificador
      $.ele = !!$opc[0] ? $opc[0] : {};      
      $.ele['data-esq'] = $.esq;
      $.ele['data-est'] = $.est;
      
      // 1 o muchos...
      $_ = "";
      $.ima_ele = $.ele;
      $.ima_lis = ( typeof($dat) == 'string' ? ( $dat.split( /, /.test($dat) ? ", " : " - " )  ) : [ $dat ] );
      $.ima_lis.forEach( $dat_val => {

        $._dat = api_dat.get($.esq,$.est,$dat_val);

        $.ima_ele['data-ide'] = $._dat.ide;
        
        // titulos
        if( $.ima_ele.title === undefined ){

          $.ima_ele.title = api_dat.val('tit',`${$.esq}.${$.est}`,$._dat);
        }
        else if( $.ima_ele.title === false ){
          delete($.ima_ele.title);
        }
        // acceso informe
        if( $.ima_ele.onclick === undefined ){
          if( sis_app.dat_est($.esq,$.est,'inf') ) $.ima_ele.onclick = `api_dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
        }
        else if( $.ima_ele.onclick === false ){

          delete($.ima_ele.onclick);
        }
        // informe      
        $_ += api_fig.ima( { 'style': api_obj.val($._dat,$._val[$tip]) }, $.ima_ele );
      })
    }
    // pido tablero por identificador
    else if( $tip == 'tab' ){
      $.par = $_val['ima'];
      $.ele_ima = $.ele;
    }
    // por contenido
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
        if( $.ope = $sis_app.dat.var.querySelector(`[name="${$i}"]`) ) api_ele.eli( $.ope, `option:not([value=""])` ); 
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
        api_ele.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = sis_app.dat_ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
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
      
    let $={}, 
    // armo identificadores
    $_ = { 'esq': $esq, 'est': $est, 'atr':$atr, 'ide':"", 'val':null };

    if( !!($atr) ) $_['est'] = ( $atr == 'ide' ) ? $est : `${$est}_${$atr}`;

    $_['ide'] = `${$_['esq']}.${$_['est']}`;

    // busco estructura relacionada por atributo
    $.esq = $_.esq;
    $.est = $_.est;
    if( $.dat_atr = sis_app.dat_atr_dat($esq,$est,$atr) ){
      $.esq = $.dat_atr.esq;
      $.est = $.dat_atr.est;
    }
    
    if( !!( $.dat_val = sis_app.dat_est($.esq,$.est,`val.${$tip}`,$dat) ) ){
      $_['val'] = $.dat_val;
    }

    return $_;    
  }// imagen por identificadores
  static val_ima( $dat, $ope ){

    let $ = {}, $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){
      
      // por atributo
      if( $.dat_atr = sis_app.dat_atr_dat($ope.esq,$ope.est,$ope.atr) ){
        if( !$ope.fic ) $ope.fic = {};
        $ope.fic.esq = $.dat_atr.esq;
        $ope.fic.est = $.dat_atr.est;
      }// por seleccion
      else if( !$ope.fic ){
        $ope.fic = api_dat.val_opc('ima', $ope.esq, $ope.est );
      }

      $_ = api_fig.ima($ope.fic.esq, $ope.fic.est, api_dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
  
  /* Ficha */
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
      api_ele.eli($ite,'.fig_ima');
      if( $.val = $.dat[$.est] ){
        api_ele.agr( api_fig.ima( $.ima[0], $.ima[1], api_dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }

  /* Informe */
  static inf( $esq, $est, $val ){
    // pido ficha
    api_eje.val([ `api_dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) sis_doc.win('app_ope',{ 
        ico: "", 
        cab: "", 
        htm: $htm, 
        opc: [ "pos" ] 
      });
    });
  }

  /*-- Operador --*/
  static ope_abm( $tip, $dat, $ope, ...$opc ){
    let $ = api_dat.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $sis_app.dat.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $sis_app.dat.var.querySelectorAll(`.dat_var > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $sis_app.dat.var.querySelectorAll(`.dat_var > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $sis_app.dat.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
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
          api_ele.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $sis_app.dat.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$sis_app.dat.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $sis_app.dat.var.dataset.esq ) && ( $.est = $sis_app.dat.var.dataset.est ) ){
          api_eje.val(['api_dat::abm', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $sis_app.dat.var.reset();              
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
  }// acumulados : posicion + marca + seleccion
  static ope_acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $sis_app.dat.ope.acu : $opc ).forEach( $ide => {

      // acumulo elementos del listado
      $.acu_val[$ide] = $dat.querySelectorAll(`[class*="_val-${$ide}-"]`);

      // actualizo total del operador
      if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

        $.tot.innerHTML = $.acu_val[$ide].length;
      }
    });

    // calculo el total grupal    
    if( $.tot = $ope.querySelector(`[name="tot"]`) ){
      $.tot.innerHTML = $dat.querySelectorAll(`[class*=_val-]:is([class*="-bor"],[class*="_act"])`).length;
    }

    // devuelvo seleccion
    return $.acu_val;
  }// sumatorias
  static ope_sum( $dat, $ope ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;
      $dat.forEach( $ite => $.sum += parseInt( $ite.dataset[`${$val.dataset.esq}_${$val.dataset.est}`] ) );

      api_dat.fic( $val, $.sum);
    });
  }// filtros : dato + variables
  static ope_ver( $tip, $dat, $ope, ...$opc ){

    let $ = api_dat.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver-`;
    $.cla_ide = `_val-ver_${$tip}`;
    // las clases previas se eliminan desde el proceso que me llama ( tab + est )

    $sis_app.dat.var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'dat' ){

      $.dat_est = $sis_app.dat.var.querySelector(`[name="est"]`);
      $.dat_ide = $sis_app.dat.var.querySelector(`[name="ver"]`);
      $.dat_val = $sis_app.dat.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = sis_app.dat_ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = api_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $tip == 'pos' || $tip == 'fec' ){

      // valores
      $.val = {};
      $.var = {};
      ['ini','fin','inc','lim'].forEach( $ide => {
        // capturo valores
        if( ( $.ite = $sis_app.dat.var.querySelector(`[name="${$ide}"]`) ) ){
          $.var[$ide] = $.ite;
          if( !!$.ite.value ) $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? api_num.val($.ite.value) : $.ite.value;
        }
      });
      
      // valido inicial: si es mayor que el final      
      if( $.val.ini && $.val.fin && $.val.ini > $.val.fin){
        $.var.ini.value = $.val.ini = $.val.fin;
      }
      // valido final
      if( $.val.fin ){
        // si es mayor que el inicio
        if( $.val.fin < $.val.ini ) $.var.fin.value = $.val.ini;
      }
      // inicializo valor final
      else if( $.val.ini && $.var.fin ){
        $.max = $.var.fin.getAttribute('max');
        $.var.fin.value = $.val.fin = $.max;
      }

      // inicializo incremento
      $.inc_val = 1;
      if( $.var.inc && ( !$.val.inc || $.val.inc <= 0 ) ){
        $.var.inc.value = $.val.inc = 1;
      }

      // filtro por posicion de lista
      if( $tip == 'pos' ){
        
        $dat.forEach( $e => {          
          $.pos_val = parseInt($e.dataset.pos ? $e.dataset.pos : $e.classList[1].split('-')[1]);
          // valor por desde-hasta
          if( $.inc_val == 1 && $.pos_val >= $.val.ini && $.pos_val <= $.val.fin ){
            api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
          // aplico salto
          $.inc_val++;
          if( $.inc_val > $.val.inc ) $.inc_val = 1;
        });
      }
      // filtro por valor de fecha
      else if( $tip == 'fec' ){

        $.val.ini = $.val.ini ? $.val.ini.split('-') : '';
        $.val.fin = $.val.fin ? $.val.fin.split('-') : '';

        $dat.forEach( $e => {
          // desde-hasta
          if( $.inc_val == 1 && api_fec.val_ver( $e.dataset['fec_dat'], $.val.ini, $.val.fin ) ){

            api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
          // aplico salto
          $.inc_val++;
          if( $.inc_val > $.val.inc ) $.inc_val = 1;
        });
      }

      // limito resultado
      if( $.val.lim ){

        $.lis = $dat.filter( $e => $e.classList.contains($.cla_ide) );
        // ultimos
        if( $sis_app.dat.var.querySelector(`.fig_ico.ide-lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) api_ele.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }// conteos : valores de estructura relacionada por atributo
  static ope_cue( $tip, $dat, $ope, ...$opc ){
    let $ = api_dat.var($dat);

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

              if( $.dat = $v.dataset[`${$.esq}_${$.est}`] ){

                if( ( $.dat_val = api_dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
              }
            });

            $ite.querySelector('td[data-atr="tot"] > n').innerHTML = $.tot;
            $ite.querySelector('td[data-atr="por"] > n').innerHTML = $.val_tot ? api_num.val_dec( ( $.tot / $.val_tot ) * 100 ) : $.val_tot;
          });
        }
      });
      break;
    // filtro por valor textual
    case 'ver':

      $.ope = $sis_app.dat.var.querySelector('[name="ope"]').value;
      $.val = $sis_app.dat.var.querySelector('[name="val"]').value;
      $.lis = $sis_app.dat.var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll(`tr.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( api_dat.ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
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

  /*-- Tabla --*/
  static lis(){
  }
  // inicializo : acumulados
  static lis_ini(){

    let $={};   

    if( $sis_app.dat.lis.val_acu ){

      if( $.ele = $sis_app.dat.lis.val_acu.querySelector(`[name="tod"]`) ){

        api_dat.lis_val('tod',$.ele);
      }
    }

  }// actualizo : acumulado + cuentas + descripciones
  static lis_act(){

    let $={};
    // actualizo total
    if( $sis_app.dat.lis.val_acu && ( $.tot = $sis_app.dat.lis.val_acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = api_dat.lis_val('tot');
    }    
    // actualizo cuentas
    if( $sis_app.dat.lis.val_cue ){

      api_dat.ope_cue('act', $sis_app.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $sis_app.dat.lis.val_cue);
    }
    // actualizo descripciones
    if( $sis_app.dat.lis.des ){

      $sis_app.dat.lis.des.querySelectorAll(`[name]:checked`).forEach( $e => api_dat.lis_des_tog($e) );
    }
  }// Valores: totales + acumulados
  static lis_val( $tip, $dat ){
    let $_, $ = {};
    switch( $tip ){
    case 'tot': 
      $_ = 0;
      if( $sis_app.dat.lis.val ){
        $_ = $sis_app.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        console.error('No hay tabla relacionada...');
      }
      break;
    case 'tod': 
      $ = api_dat.var($dat);  
      
      if( $sis_app.dat.lis.val_acu ){
        // ajusto controles acumulados
        $sis_app.dat.ope.acu.forEach( $i => {

          if( $.val = $sis_app.dat.lis.val_acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }
      // ejecuto todos los filtros y actualizo totales
      api_dat.lis_ver();    
      break;
    case 'acu':
      if( ( $.esq = $sis_app.dat.lis.val.dataset.esq ) && ( $.est = $sis_app.dat.lis.val.dataset.est ) ){
        
        // oculto todos los items de la tabla
        api_ele.act('cla_agr',$sis_app.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

        // actualizo por acumulado
        $sis_app.dat.ope.acu.forEach( $ide => {

          if( $.val = $sis_app.dat.lis.val_acu.querySelector(`[name='${$ide}']`) ){

            $.tot = 0;
            if( $.val.checked ){
              // recorro seleccionados
              $sis_app.dat.tab.val.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
                
                if( $.ele = $sis_app.dat.lis.val.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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
      break;
    }
    return $_;
  }// Filtros : Valores + Fecha + Posicion
  static lis_ver( $tip, $dat ){

    let $ = api_dat.var($dat);

    // ejecuto filtros
    if( !$tip || ['dat','pos','fec'].includes($tip) ){

      // 1- muestro todos
      if( !$sis_app.dat.lis.val_acu || $sis_app.dat.lis.val_acu.querySelector(`[name="tod"]:checked`) ){

        api_ele.act('cla_eli',$sis_app.dat.lis.val.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        api_dat.lis_val('acu');
      }

      // 2- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $sis_app.dat.ope.ver ){
        // Elimino todas las clases
        api_ele.act('cla_eli',$sis_app.dat.lis.val.querySelectorAll(`._val-ver_${$ope_ide}`),[`_val-ver-`,`_val-ver_${$ope_ide}`]);
        // tomo solo los que tienen valor
        if( ( $.val = $sis_app.dat.lis.ver.querySelector(`${$sis_app.dat.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){
        $.eje.forEach( $ope_ide => {
          api_dat.ope_ver($ope_ide, api_lis.val_cod( $sis_app.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $sis_app.dat.lis.ver);
          // oculto valores no seleccionados
          api_ele.act('cla_agr',$sis_app.dat.lis.val.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else if( ['cic','gru'].includes($tip) ){
      // muestro todos los items
      api_ele.act('cla_eli',$sis_app.dat.lis.val.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
      
      // aplico filtro
      // ... 
    }
    
    // actualizo total, cuentas y descripciones
    api_dat.lis_act();

  }// Columnas : toggles + atributos
  static lis_atr(){
  }// - muestro-oculto
  static lis_atr_tog( $dat ){

    let $ = api_dat.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $sis_app.dat.lis.val.querySelectorAll(
        `:is(thead,tbody) :is(td,th)[data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$dat.name}"]`
      ).forEach( $ite => {
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
      $dat.parentElement.parentElement.querySelectorAll('input[type="checkbox"]').forEach( $e => {
          
        $e.checked = ( $dat.dataset.val == 'ver' );

        api_dat.lis_atr_tog($e);
      });
    }
  }// Descripcion : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static lis_des(){
  }// - muestro-oculto
  static lis_des_tog( $dat ){

    let $ = api_dat.var($dat);
    $.ope  = $sis_app.dat.var.classList[0].split('-')[1];
    $.esq = $sis_app.dat.var.dataset.esq;
    $.est = $sis_app.dat.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    api_ele.act('cla_agr',$sis_app.dat.lis.val.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $sis_app.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = api_dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          api_ele.act('cla_eli',$sis_app.dat.lis.val.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static lis_des_ver( $dat ){

    let $ = api_dat.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $sis_app.dat.lis.val.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $sis_app.dat.lis.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$sis_app.dat.lis.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            api_ele.act('cla_eli',$sis_app.dat.lis.val.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $sis_app.dat.lis.val.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $sis_app.dat.lis.val.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      api_ele.act('atr_act',$sis_app.dat.lis.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      api_ele.act('cla_agr',$sis_app.dat.lis.val.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $sis_app.dat.lis.val.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $sis_app.dat.lis.val.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
  
  /*-- Tablero --*/
  static tab( $dat, $ele, ...$opc ){

  }// Inicio : opciones, posicion, filtros
  static tab_ini( $cla ){

    let $ = { cla : !!$cla ? eval($cla) : false };

    // clase por posicion
    $sis_app.dat.tab.ide = $sis_app.dat.tab.val.classList[3];
    
    // inicializo opciones
    ['sec','pos'].forEach( $ope => {
      if( $sis_app.dat.tab[$ope] ){
        $sis_app.dat.tab[$ope].querySelectorAll(
          `form[class*="ide-"] [onchange*=".tab_"]:is( input:checked, select:not([value=""]) )`
        ).forEach( 
          $inp => api_dat[`tab_${$ope}`]( $inp )
        );
      }
    });

    // marco posicion principal
    api_dat.tab_val('pos');

    // actualizo opciones
    $sis_app.dat.ope.acu.forEach( $ite => {
      if( $.ele = $sis_app.dat.tab.val_acu.querySelector(`[name="${$ite}"]:checked`) ) api_dat.tab_val_acu($.ele) 
    });

    // inicializo operador por aplicacion
    if( $.cla ){
      // secciones y posiciones por aplicacion
      ['sec','pos'].forEach( $ope => {
        if( $sis_app.dat.tab[$ope] ){
          $.eje = `tab_${$ope}`;
          $sis_app.dat.tab[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$cla}.${$.eje}"]`).forEach(

            $inp => $.cla[$.eje] && $.cla[$.eje]( $inp )
          );
        }
      });
      
      // atributos
      if( $sis_app.dat.tab.opc ){
        $sis_app.dat.tab.opc.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
          
          $.eje = `tab_opc`;

          $for.querySelectorAll(`[name][onchange*="${$cla}.${$.eje}"]`).forEach( 

            $inp => !!$.cla[$.eje] && $.cla[$.eje]( $for.classList[0].split('-')[2], $inp )
          );
        });
      }
    }
  }// Actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static tab_act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $sis_app.dat.ope.acu : api_lis.val_ite($dat);

    $.dat = $sis_app.dat.tab.val;

    // acumulados + listado
    if( $sis_app.dat.tab.val_acu ){ 

      // actualizo toales acumulados
      api_dat.ope_acu($sis_app.dat.tab.val, $sis_app.dat.tab.val_acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $sis_app.dat.tab.val_sum ){
        $.tot = [];
        $sis_app.dat.ope.acu.forEach( $acu_ide => {

          if( $sis_app.dat.tab.val_acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$sis_app.dat.tab.val.querySelectorAll(`[class*="_val-${$acu_ide}-"]`) );
          }
        });
        api_dat.ope_sum($.tot, $sis_app.dat.tab.val_sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$sis_app.dat.lis.val_acu.querySelector(`[name="tod"]:checked`) ) api_dat.lis_val('acu');

      // -> ejecuto filtros + actualizo totales
      if( $sis_app.dat.lis.ver ) api_dat.lis_ver();
    }

    // fichas del tablero
    if( ( $sis_app.dat.tab.pos ) && ( $.ima = $sis_app.dat.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $sis_app.dat.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) api_dat.tab_pos($.ima);
    }

    // actualizo cuentas
    if( $sis_app.dat.tab.val_cue ){

      api_dat.ope_cue('act', $sis_app.dat.tab.val.querySelectorAll(`.pos[class*=_val-]:is([class*=-bor],[class*=_act])`), $sis_app.dat.tab.val_cue );
    }

  }// Valores
  static tab_val( $tip, $dat ){

    let $ = api_dat.var($dat);

    switch( $tip ){
    case 'pos': 

      api_ele.act('cla_eli',$sis_app.dat.tab.val.querySelectorAll(`${$sis_app.dat.tab.cla}`),['_val-pos-','_val-pos-bor']);

      if( $_hol && $_hol.val && ( $.kin = $_hol.val.kin ) ){        
        $sis_app.dat.tab.val.querySelectorAll(`${$sis_app.dat.tab.cla}[data-hol_kin="${$.kin}"]`).forEach( $e => {
          $e.classList.add(`_val-pos-`);
          if( $sis_app.dat.tab.val_acu && $sis_app.dat.tab.val_acu.querySelector(`[name="pos"]:checked`) ){
            $e.classList.add(`_val-pos-bor`);
          }
        });
      }

      break;
    case 'mar':
      $dat.classList.toggle(`_val-mar-`);
      // marco bordes
      if( $sis_app.dat.tab.val_acu ){
        if( $dat.classList.contains(`_val-mar-`) && $sis_app.dat.tab.val_acu.querySelector(`[name="mar"]:checked`) ){
          $dat.classList.add(`_val-mar-bor`);
        }
        else if( !$dat.classList.contains(`_val-mar-`) && $dat.classList.contains(`_val-mar-bor`) ){
          $dat.classList.remove(`_val-mar-bor`);
        }
      }
      break;
    case 'ver':
      for( const $ide in $sis_app.dat.ope.ope_ver ){

        if( $.ele = $sis_app.dat.tab.ver.querySelector(`${$sis_app.dat.ope.ope_ver[$ide]}:not([value=""])`) ){
  
          api_dat.tab_ver($ide,$.ele,$sis_app.dat.tab.val);

          break;
        }
      }
      break;
    case 'opc':
      // las 
      break;
    }
    // actualizo totales
    api_dat.tab_act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static tab_val_acu( $dat, $ope ){
    
    let $ = api_dat.var($dat);

    if( !$.var_ide && $ope ) $ = api_dat.var( $dat = $sis_app.dat.tab.val_acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $sis_app.dat.tab.val.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( api_dat.ver($cla,'^^',`${$.cla_ide}-`) ){
            $.ite_ide = `${$.cla_ide}_act-${$cla.split('-')[2]}`;
            if( $dat.checked ){
              !$ite.classList.contains($.ite_ide) && $ite.classList.add($.ite_ide);
            }else{
              $ite.classList.contains($.ite_ide) && $ite.classList.remove($.ite_ide);
            }
          }
        });
      });
    }// aplico bordes
    else{
      api_ele.act('cla_eli',$sis_app.dat.tab.val.querySelectorAll(`.${$.cla_ide}-bor`),`${$.cla_ide}-bor`);
      if( $dat.checked ) api_ele.act('cla_agr',$sis_app.dat.tab.val.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}-bor`);
    }
    // actualizo calculos + vistas( fichas + items )        
    api_dat.tab_act($.var_ide);

  }// Seleccion : datos, posicion, fecha
  static tab_ver(){

    let $ = {};

    // 1- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
    $.eje = [];
    for( const $ope_ide in $sis_app.dat.ope.ver ){      
      // Elimino todas las clases
      api_ele.act('cla_eli',$sis_app.dat.tab.val.querySelectorAll(`._val-ver_${$ope_ide}`),[`_val-ver-`,`_val-ver_${$ope_ide}`]);
      // tomo solo los que tienen valor
      if( ( $.val = $sis_app.dat.tab.ver.querySelector(`${$sis_app.dat.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
        $.eje.push($ope_ide);
      }
    }

    // 2- ejecuto todos los filtros
    if( $.eje.length > 0 ){
      $.eje.forEach( ($ope_ide, $ope_pos) => {
        api_dat.ope_ver($ope_ide, api_lis.val_cod(
          // si es el 1° paso todas las posiciones, sino solo las filtradas
          $sis_app.dat.tab.val.querySelectorAll( $ope_pos == 0 ? $sis_app.dat.tab.cla : `._val-ver-` )
        ), 
          $sis_app.dat.tab.ver, 'tab'
        );
      });
    }

    // 3- marco bordes de seleccionados
    api_ele.act('cla_eli',$sis_app.dat.tab.val.querySelectorAll('._val-ver-bor'),'_val-ver-bor');
    if( $sis_app.dat.tab.val_acu && $sis_app.dat.tab.val_acu.querySelector(`[name="ver"]:checked`) ){
      api_ele.act('cla_agr',$sis_app.dat.tab.val.querySelectorAll(`._val-ver-`),'_val-ver-bor');
    }

    // actualizo calculos + vistas( fichas + items )
    api_dat.tab_act('ver');
    
  }// Secciones : bordes + colores + imagen + ...
  static tab_sec( $dat ){

    let $ = api_dat.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$sis_app.dat.tab.val.classList.contains('bor-1') ){ $sis_app.dat.tab.val.classList.add('bor-1'); }
        $sis_app.dat.tab.val.querySelectorAll('.tab:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $sis_app.dat.tab.val.classList.contains('bor-1') ){ $sis_app.dat.tab.val.classList.remove('bor-1'); }
        $sis_app.dat.tab.val.querySelectorAll('.tab.bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $sis_app.dat.tab.val.querySelectorAll(`.tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $sis_app.dat.tab.val.classList.contains('fon-0') ){
          $sis_app.dat.tab.val.classList.remove('fon-0');
        }
      }else{
        // secciones
        $sis_app.dat.tab.val.querySelectorAll(`.tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$sis_app.dat.tab.val.classList.contains('fon-0') ){
          $sis_app.dat.tab.val.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $sis_app.dat.tab.val.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $sis_app.dat.tab.val.style.backgroundImage = '';
      }
      break;      
    }     
  }// Posiciones : borde + color + imagen + texto + numero + fecha
  static tab_pos( $dat ){

    let $ = api_dat.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $sis_app.dat.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $sis_app.dat.ope.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $sis_app.dat.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $sis_app.dat.tab.val.querySelectorAll(`${$sis_app.dat.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $sis_app.dat.tab.val.querySelectorAll(`${$sis_app.dat.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      $.eli = `${$sis_app.dat.tab.cla}[class*='${$.ope}']`;
      $.agr = `${$sis_app.dat.tab.cla}`;

      $sis_app.dat.tab.val.querySelectorAll($.eli).forEach( $e => api_ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = sis_app.dat_ide($dat.value,$);

        $.dat_ide = ( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.');

        $.col_dat = api_dat.val_ide('col', ...$.dat_ide );

        $.col = ( $.col_dat && $.col_dat.val ) ? $.col_dat.val : 1;

        $sis_app.dat.tab.val.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = api_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : api_num.val_ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $sis_app.dat.tab.val.querySelectorAll($sis_app.dat.tab.cla).forEach( $e => {

        $e.querySelectorAll('.fig_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = sis_app.dat_ide($dat.value,$);        
        // busco valores de ficha
        $.fic = api_dat.val_ide('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $sis_app.dat.tab.val.querySelectorAll($sis_app.dat.tab.cla).forEach( $e => {
          // capturar posicion .dep
          $.htm = '';
          $.ele = { title : false, onclick : false  };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos-') ){ 
              $.htm = api_dat.val_ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar-') ){ 
              $.htm = api_dat.val_ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver-') ){ 
              $.htm = api_dat.val_ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_val-opc-/.test($cla_nom) ) return $.htm = api_dat.val_ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = api_dat.val_ima($e,$);
          }
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.fig_ima') ) ? api_ele.mod($.htm,$.ima_ele) : api_ele.agr($.htm,$e,'ini');
          }
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
      $sis_app.dat.tab.val.querySelectorAll($sis_app.dat.tab.cla).forEach( $e => api_ele.eli($e,$.eti) );

      if( $dat.value ){

        $ = sis_app.dat_ide($dat.value,$);

        $sis_app.dat.tab.val.querySelectorAll($sis_app.dat.tab.cla).forEach( $e =>{

          if( $.obj = api_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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