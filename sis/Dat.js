// WINDOW
'use strict';

// Dato
class Dat {  

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = Dat.get_est('dat',$ide,'dat');

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
  
  // identificador: esq.est[...atr]
  static ide( $dat = '', $val = {} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
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
    $dat_tip = Fig._('ico');
    return !!$dat_tip[$ide] ? $dat_tip[$ide] : false;
  }

  /* Operadores */
  // Comparaciones
  static ope_ver( $dat, $ide, $val, $opc=['g','i'] ){
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
    case '^^':  $_ = Tex.val_dec(`^${Tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!^':  $_ = Tex.val_dec(`^[^${Tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    case '$$':  $_ = Tex.val_dec( `${Tex.val_cod($val)}$`, $opc.join('') ).test( $dat.toString() ); break;
    case '!$':  $_ = Tex.val_dec( `[^${Tex.val_cod($val)}]$`, $opc.join('') ).test( $dat.toString() ); break;
    case '**':  $_ = Tex.val_dec( `${Tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!*':  $_ = Tex.val_dec( `[^${Tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    }
    return $_;
  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /* estructura | objeto */
  static get( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $.esq = $dat;
      $.est = $ope;
      $_ = $val;

      if( !$val || !Obj.val_tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval( Tex.let_pal($.esq) ) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = dom.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = Lis.val_est('ver',$dat,$ope);
    }
    return $_;
  }// Cargo estructura desde app.dat
  static get_est( $esq, $est, $ope, $dat ) {

    let $={}, $_ = $App.dat[$esq][$est];

    // Estructura de la base
    if( !$_ ){
      // ...
    }

    // Propiedades
    if( $ope ){
      $.ope_atr = typeof($ope) == 'string' ? $ope.split('.') : $ope;
      $.ope_atr.forEach( $ide => {
        $_ = ( typeof($_) == 'object' && !!($_[$ide]) ) ? $_[$ide] : false;
      });

      if( $_ && $dat ){
        switch( $.ope_atr[0] ){
        case 'val':
          $_ = Obj.val( Dat.get($esq,$est,$dat), $_ );
          break;
        }
      }
    }

    return $_;
  }// busco dato relacional por atributo
  static get_rel( $esq, $est, $atr ){

    let $={}, $_ = false;
    
    if( $App.dat[$esq][$est]?.atr[$atr] && ( $.dat_atr = $App.dat[$esq][$est]?.atr[$atr].var.dat ) ){

      $.dat_atr = $.dat_atr.split('_');

      $_ = { 'esq': $.dat_atr.shift(), 'est': $.dat_atr.join('_') };
    }
    return $_;
  }// opciones : esquema.estructura.atributos.valores
  static get_opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = Dat.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $App.dom.dat.var.querySelector(`[name="${$i}"]`) ) dom.eli( $.ope, `option:not([value=""])` ); 
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
        Ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          Ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        dom.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = Dat.ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          Eje.val(['Dat::get', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = Lis.opc( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }// busco Valores: nombre, descripcion, tablero, imagen, color, cantidad, texto, numero
  static get_val( $tip, $ide, $dat, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = Dat.ide($ide,$);
    // cargo datos
    $._dat = Dat.get($.esq,$.est,$dat);
    // cargo valores
    $._val = Dat.get_est($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = Obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+Obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $.tip_val = typeof($._val[$tip]);
      if( $tip == 'ima' ){
        if( $.tip_val != 'string' ) $tip = 'tab';
      }
      else if( $.tip_val == 'string' ){
        $_ = Obj.val($._dat,$._val[$tip]);  
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

        $._dat = Dat.get($.esq,$.est,$dat_val);

        $.ima_ele['data-ide'] = $._dat.ide;
        
        // titulos
        if( $.ima_ele.title === undefined ){

          $.ima_ele.title = Dat.get_val('tit',`${$.esq}.${$.est}`,$._dat);
        }
        else if( $.ima_ele.title === false ){
          delete($.ima_ele.title);
        }
        // acceso informe
        if( $.ima_ele.onclick === undefined ){
          if( Dat.get_est($.esq,$.est,'inf') ) $.ima_ele.onclick = `Dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
        }
        else if( $.ima_ele.onclick === false ){

          delete($.ima_ele.onclick);
        }
        // informe      
        $_ += Fig.ima( { 'style': Obj.val($._dat,$._val[$tip]) }, $.ima_ele );
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
      $opc[0]['htm'] = Tex.let($_);
      $_ = Ele.val($opc[0]);
    }

    return $_;
  }// identificador por seleccion : imagen, color...
  static get_ide( $tip, $esq, $est, $atr, $dat ){
      
    let $={}, 
    // armo identificadores
    $_ = { 'esq': $esq, 'est': $est, 'atr':$atr, 'ide':"", 'val':null };

    if( !!($atr) ) $_['est'] = ( $atr == 'ide' ) ? $est : `${$est}_${$atr}`;

    $_['ide'] = `${$_['esq']}.${$_['est']}`;

    // busco estructura relacionada por atributo
    $.esq = $_.esq;
    $.est = $_.est;
    if( $.dat_atr = Dat.get_rel($esq,$est,$atr) ){
      $.esq = $.dat_atr.esq;
      $.est = $.dat_atr.est;
    }
    
    if( !!( $.dat_val = Dat.get_est($.esq,$.est,`val.${$tip}`,$dat) ) ){
      $_['val'] = $.dat_val;
    }

    return $_;    
  }// imagen por identificadores
  static get_ima( $dat, $ope ){

    let $ = {}, $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){
      
      // por atributo
      if( $.dat_atr = Dat.get_rel($ope.esq,$ope.est,$ope.atr) ){
        if( !$ope.fic ) $ope.fic = {};
        $ope.fic.esq = $.dat_atr.esq;
        $ope.fic.est = $.dat_atr.est;
      }// por seleccion
      else if( !$ope.fic ){
        $ope.fic = Dat.get_opc('ima', $ope.esq, $ope.est );
      }

      $_ = Fig.ima($ope.fic.esq, $ope.fic.est, Dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /* Variable : form > .dat_var > label + (select,input,textarea,button)[name] */
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $App.dom.dat.var = dom.ver($dat,{'eti':'form'});
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
    
    Lis.val_cod( dom.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }
  
  /* Ficha */
  static fic( $dat, $ope, ...$opc ){
    let $_="", $ = Dat.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.dat_var`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? Num.val_ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.dat_var [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      dom.eli($ite,'.fig_ima');
      if( $.val = $.dat[$.est] ){
        dom.agr( Fig.ima( $.ima[0], $.ima[1], Dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }

  /* Informe */
  static inf( $esq, $est, $val ){
    // pido ficha
    Eje.val([ `Dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) Doc.win('app_ope',{ 
        ico: "", 
        cab: "", 
        htm: $htm, 
        opc: [ "pos" ] 
      });
    });
  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /*-- Procesos de Lista --*/
  static val(){
  }// alta, baja y modificacion
  static val_abm( $tip, $dat, $ope, ...$opc ){
    let $ = Dat.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $App.dom.dat.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $App.dom.dat.var.querySelectorAll(`.dat_var > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $App.dom.dat.var.querySelectorAll(`.dat_var > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $App.dom.dat.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
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
          dom.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $App.dom.dat.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$App.dom.dat.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $App.dom.dat.var.dataset.esq ) && ( $.est = $App.dom.dat.var.dataset.est ) ){
          Eje.val(['Dat::abm', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $App.dom.dat.var.reset();              
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
  static val_acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $App.dom.dat.ope.acu : $opc ).forEach( $ide => {

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
  static val_sum( $dat, $ope ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;
      $dat.forEach( $ite => $.sum += parseInt( $ite.dataset[`${$val.dataset.esq}_${$val.dataset.est}`] ) );

      Dat.fic( $val, $.sum);
    });
  }// filtros : dato + variables
  static val_ver( $tip, $dat, $ope, ...$opc ){

    let $ = Dat.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver-`;
    $.cla_ide = `_val-ver_${$tip}`;
    // las clases previas se eliminan desde el proceso que me llama ( tab + est )

    $App.dom.dat.var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'dat' ){

      $.dat_est = $App.dom.dat.var.querySelector(`[name="est"]`);
      $.dat_ide = $App.dom.dat.var.querySelector(`[name="ver"]`);
      $.dat_val = $App.dom.dat.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = Dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = Dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) Ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
        if( ( $.ite = $App.dom.dat.var.querySelector(`[name="${$ide}"]`) ) ){
          $.var[$ide] = $.ite;
          if( !!$.ite.value ) $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? Num.val($.ite.value) : $.ite.value;
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
            Ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
          if( $.inc_val == 1 && Fec.val_ver( $e.dataset['fec_dat'], $.val.ini, $.val.fin ) ){

            Ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
        if( $App.dom.dat.var.querySelector(`.fig_ico.ide-lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) Ele.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }// conteos : valores de estructura relacionada por atributo
  static val_cue( $tip, $dat, $ope, ...$opc ){
    let $ = Dat.var($dat);

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

                if( ( $.dat_val = Dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
              }
            });

            $ite.querySelector('td[data-atr="tot"] > n').innerHTML = $.tot;
            $ite.querySelector('td[data-atr="por"] > n').innerHTML = $.val_tot ? Num.val_dec( ( $.tot / $.val_tot ) * 100 ) : $.val_tot;
          });
        }
      });
      break;
    // filtro por valor textual
    case 'ver':

      $.ope = $App.dom.dat.var.querySelector('[name="ope"]').value;
      $.val = $App.dom.dat.var.querySelector('[name="val"]').value;
      $.lis = $App.dom.dat.var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll(`tr.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( Dat.ope_ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
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
  }// Inicializo : acumulados
  static lis_ini(){

    let $={};   

    if( $App.dom.dat.lis.val_acu ){

      if( $.ele = $App.dom.dat.lis.val_acu.querySelector(`[name="tod"]`) ){

        Dat.lis_val('tod',$.ele);
      }
    }

  }// Actualizo : acumulado + cuentas + descripciones
  static lis_act(){

    let $={};
    // actualizo total
    if( $App.dom.dat.lis.val_acu && ( $.tot = $App.dom.dat.lis.val_acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = Dat.lis_val('tot');
    }    
    // actualizo cuentas
    if( $App.dom.dat.lis.val_cue ){

      Dat.val_cue('act', $App.dom.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $App.dom.dat.lis.val_cue);
    }
    // actualizo descripciones
    if( $App.dom.dat.lis.des ){

      $App.dom.dat.lis.des.querySelectorAll(`[name]:checked`).forEach( $e => Dat.lis_des_tog($e) );
    }
  }// Valores: totales + acumulados
  static lis_val( $tip, $dat ){
    let $_, $ = {};
    switch( $tip ){
    case 'tot': 
      $_ = 0;
      if( $App.dom.dat.lis.val ){
        $_ = $App.dom.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        console.error('No hay tabla relacionada...');
      }
      break;
    case 'tod': 
      $ = Dat.var($dat);  
      
      if( $App.dom.dat.lis.val_acu ){
        // ajusto controles acumulados
        $App.dom.dat.ope.acu.forEach( $i => {

          if( $.val = $App.dom.dat.lis.val_acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }
      // ejecuto todos los filtros y actualizo totales
      Dat.lis_ver();    
      break;
    case 'acu':
      if( ( $.esq = $App.dom.dat.lis.val.dataset.esq ) && ( $.est = $App.dom.dat.lis.val.dataset.est ) ){
        
        // oculto todos los items de la tabla
        Ele.act('cla_agr',$App.dom.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

        // actualizo por acumulado
        $App.dom.dat.ope.acu.forEach( $ide => {

          if( $.val = $App.dom.dat.lis.val_acu.querySelector(`[name='${$ide}']`) ){

            $.tot = 0;
            if( $.val.checked ){
              // recorro seleccionados
              $App.dom.dat.tab.val.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
                
                if( $.ele = $App.dom.dat.lis.val.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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

    let $ = Dat.var($dat);

    // ejecuto filtros
    if( !$tip || ['dat','pos','fec'].includes($tip) ){

      // 1- muestro todos
      if( !$App.dom.dat.lis.val_acu || $App.dom.dat.lis.val_acu.querySelector(`[name="tod"]:checked`) ){

        Ele.act('cla_eli',$App.dom.dat.lis.val.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        Dat.lis_val('acu');
      }

      // 2- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $App.dom.dat.ope.ver ){
        // Elimino todas las clases
        Ele.act('cla_eli',$App.dom.dat.lis.val.querySelectorAll(`._val-ver_${$ope_ide}`),[`_val-ver-`,`_val-ver_${$ope_ide}`]);
        // tomo solo los que tienen valor
        if( ( $.val = $App.dom.dat.lis.ver.querySelector(`${$App.dom.dat.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){
        $.eje.forEach( $ope_ide => {
          Dat.val_ver($ope_ide, Lis.val_cod( $App.dom.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $App.dom.dat.lis.ver);
          // oculto valores no seleccionados
          Ele.act('cla_agr',$App.dom.dat.lis.val.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else if( ['cic','gru'].includes($tip) ){
      // muestro todos los items
      Ele.act('cla_eli',$App.dom.dat.lis.val.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
      
      // aplico filtro
      // ... 
    }
    
    // actualizo total, cuentas y descripciones
    Dat.lis_act();

  }// Columnas : toggles + atributos
  static lis_atr(){
  }// - muestro-oculto
  static lis_atr_tog( $dat ){

    let $ = Dat.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $App.dom.dat.lis.val.querySelectorAll(
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

        Dat.lis_atr_tog($e);
      });
    }
  }// Descripcion : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static lis_des(){
  }// - muestro-oculto
  static lis_des_tog( $dat ){

    let $ = Dat.var($dat);
    $.ope  = $App.dom.dat.var.classList[0].split('-')[1];
    $.esq = $App.dom.dat.var.dataset.esq;
    $.est = $App.dom.dat.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    Ele.act('cla_agr',$App.dom.dat.lis.val.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $App.dom.dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = Dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          Ele.act('cla_eli',$App.dom.dat.lis.val.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static lis_des_ver( $dat ){

    let $ = Dat.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $App.dom.dat.lis.val.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $App.dom.dat.lis.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$App.dom.dat.lis.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            Ele.act('cla_eli',$App.dom.dat.lis.val.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $App.dom.dat.lis.val.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $App.dom.dat.lis.val.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      Ele.act('atr_act',$App.dom.dat.lis.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      Ele.act('cla_agr',$App.dom.dat.lis.val.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $App.dom.dat.lis.val.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $App.dom.dat.lis.val.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
  
  /*-- Tablero --*/
  static tab(){
  }// Inicializo : opciones, posicion, filtros
  static tab_ini( $cla ){

    $cla = Tex.let_pal($cla);

    let $ = { cla : !!$cla ? eval( $cla ) : false };

    // clase por posicion
    $App.dom.dat.tab.ide = $App.dom.dat.tab.val.classList[3];
    
    // inicializo opciones
    ['sec','pos'].forEach( $ope => {

      if( $App.dom.dat.tab[$ope] ){

        $App.dom.dat.tab[$ope].querySelectorAll(          
          `form[class*="ide-"] [onchange*=".tab_"]:is( input:checked, select:not([value=""]) )`
        ).forEach( 

          $inp => Dat[`tab_${$ope}`]( $inp )
        );
      }
    });

    // marco posicion principal
    Dat.tab_val('pos');

    // actualizo opciones
    $App.dom.dat.ope.acu.forEach( $ite => {
      if( $.ele = $App.dom.dat.tab.val_acu.querySelector(`[name="${$ite}"]:checked`) ) Dat.tab_val_acu($.ele) 
    });

    // inicializo operador por aplicacion
    if( $.cla ){
      // secciones y posiciones por aplicacion
      ['sec','pos'].forEach( $ope => {

        if( $App.dom.dat.tab[$ope] ){

          $.eje = `tab_${$ope}`;
          
          $App.dom.dat.tab[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$cla}.${$.eje}"]`).forEach(

            $inp => $.cla[$.eje] && $.cla[$.eje]( $inp )
          );
        }
      });
      
      // atributos
      if( $App.dom.dat.tab.opc ){
        $App.dom.dat.tab.opc.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
          
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
    
    $dat = !$dat ? $App.dom.dat.ope.acu : Lis.val_ite($dat);

    $.dat = $App.dom.dat.tab.val;

    // acumulados + listado
    if( $App.dom.dat.tab.val_acu ){ 

      // actualizo toales acumulados
      Dat.val_acu($App.dom.dat.tab.val, $App.dom.dat.tab.val_acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $App.dom.dat.tab.val_sum ){
        $.tot = [];
        $App.dom.dat.ope.acu.forEach( $acu_ide => {

          if( $App.dom.dat.tab.val_acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$App.dom.dat.tab.val.querySelectorAll(`[class*="_val-${$acu_ide}-"]`) );
          }
        });
        Dat.val_sum($.tot, $App.dom.dat.tab.val_sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$App.dom.dat.lis.val_acu.querySelector(`[name="tod"]:checked`) ) Dat.lis_val('acu');

      // -> ejecuto filtros + actualizo totales
      if( $App.dom.dat.lis.ver ) Dat.lis_ver();
    }

    // fichas del tablero
    if( ( $App.dom.dat.tab.pos ) && ( $.ima = $App.dom.dat.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $App.dom.dat.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) Dat.tab_pos($.ima);
    }

    // actualizo cuentas
    if( $App.dom.dat.tab.val_cue ){

      Dat.val_cue('act', $App.dom.dat.tab.val.querySelectorAll(`.pos[class*=_val-]:is([class*=-bor],[class*=_act])`), $App.dom.dat.tab.val_cue );
    }

  }// Valores
  static tab_val( $tip, $dat ){

    let $ = Dat.var($dat);

    switch( $tip ){
    case 'pos': 

      Ele.act('cla_eli',$App.dom.dat.tab.val.querySelectorAll(`${$App.dom.dat.tab.cla}._val-pos-`),['_val-pos-','_val-pos-bor']);

      if( $_hol && $_hol.val && ( $.kin = $_hol.val.kin ) ){        

        $App.dom.dat.tab.val.querySelectorAll(`${$App.dom.dat.tab.cla}[data-hol_kin="${$.kin}"]`).forEach( $e => {

          $e.classList.add(`_val-pos-`);

          if( $App.dom.dat.tab.val_acu && $App.dom.dat.tab.val_acu.querySelector(`[name="pos"]:checked`) ){

            $e.classList.add(`_val-pos-bor`);
          }
        });
      }

      break;
    case 'mar':
      $dat.classList.toggle(`_val-mar-`);
      // marco bordes
      if( $App.dom.dat.tab.val_acu ){
        if( $dat.classList.contains(`_val-mar-`) && $App.dom.dat.tab.val_acu.querySelector(`[name="mar"]:checked`) ){
          $dat.classList.add(`_val-mar-bor`);
        }
        else if( !$dat.classList.contains(`_val-mar-`) && $dat.classList.contains(`_val-mar-bor`) ){
          $dat.classList.remove(`_val-mar-bor`);
        }
      }
      break;
    case 'ver':
      for( const $ide in $App.dom.dat.ope.ope_ver ){

        if( $.ele = $App.dom.dat.tab.ver.querySelector(`${$App.dom.dat.ope.ope_ver[$ide]}:not([value=""])`) ){
  
          Dat.tab_ver($ide,$.ele,$App.dom.dat.tab.val);

          break;
        }
      }
      break;
    case 'opc': break;
    }
    // actualizo totales
    Dat.tab_act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static tab_val_acu( $dat, $ope ){
    
    let $ = Dat.var($dat);

    if( !$.var_ide && $ope ) $ = Dat.var( $dat = $App.dom.dat.tab.val_acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $App.dom.dat.tab.val.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( Dat.ope_ver($cla,'^^',`${$.cla_ide}-`) ){

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
      Ele.act('cla_eli',$App.dom.dat.tab.val.querySelectorAll(`.${$.cla_ide}-bor`),`${$.cla_ide}-bor`);

      if( $dat.checked ) Ele.act('cla_agr',$App.dom.dat.tab.val.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}-bor`);
    }

    // actualizo calculos + vistas( fichas + items )
    Dat.tab_act($.var_ide);

  }// Seleccion : datos, posicion, fecha
  static tab_ver(){

    let $ = {};

    // 1- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
    $.eje = [];
    for( const $ope_ide in $App.dom.dat.ope.ver ){      
      // Elimino todas las clases
      Ele.act('cla_eli',$App.dom.dat.tab.val.querySelectorAll(`._val-ver_${$ope_ide}`),[`_val-ver-`,`_val-ver_${$ope_ide}`]);
      // tomo solo los que tienen valor
      if( ( $.val = $App.dom.dat.tab.ver.querySelector(`${$App.dom.dat.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
        $.eje.push($ope_ide);
      }
    }

    // 2- ejecuto todos los filtros
    if( $.eje.length > 0 ){
      $.eje.forEach( ($ope_ide, $ope_pos) => {
        Dat.val_ver($ope_ide, Lis.val_cod(
          // si es el 1° paso todas las posiciones, sino solo las filtradas
          $App.dom.dat.tab.val.querySelectorAll( $ope_pos == 0 ? $App.dom.dat.tab.cla : `._val-ver-` )
        ), 
          $App.dom.dat.tab.ver, 'tab'
        );
      });
    }

    // 3- marco bordes de seleccionados
    Ele.act('cla_eli',$App.dom.dat.tab.val.querySelectorAll('._val-ver-bor'),'_val-ver-bor');
    if( $App.dom.dat.tab.val_acu && $App.dom.dat.tab.val_acu.querySelector(`[name="ver"]:checked`) ){
      Ele.act('cla_agr',$App.dom.dat.tab.val.querySelectorAll(`._val-ver-`),'_val-ver-bor');
    }

    // actualizo calculos + vistas( fichas + items )
    Dat.tab_act('ver');
    
  }// Secciones : bordes + colores + imagen + ...
  static tab_sec( $dat ){

    let $ = Dat.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$App.dom.dat.tab.val.classList.contains('bor-1') ){ $App.dom.dat.tab.val.classList.add('bor-1'); }
        $App.dom.dat.tab.val.querySelectorAll('.tab:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $App.dom.dat.tab.val.classList.contains('bor-1') ){ $App.dom.dat.tab.val.classList.remove('bor-1'); }
        $App.dom.dat.tab.val.querySelectorAll('.tab.bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $App.dom.dat.tab.val.querySelectorAll(`.tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $App.dom.dat.tab.val.classList.contains('fon-0') ){
          $App.dom.dat.tab.val.classList.remove('fon-0');
        }
      }else{
        // secciones
        $App.dom.dat.tab.val.querySelectorAll(`.tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$App.dom.dat.tab.val.classList.contains('fon-0') ){
          $App.dom.dat.tab.val.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $App.dom.dat.tab.val.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $App.dom.dat.tab.val.style.backgroundImage = '';
      }
      break;      
    }     
  }// Posiciones : borde + color + imagen + texto + numero + fecha
  static tab_pos( $dat ){

    let $ = Dat.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $App.dom.dat.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $App.dom.dat.ope.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $App.dom.dat.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $App.dom.dat.tab.val.querySelectorAll(`${$App.dom.dat.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $App.dom.dat.tab.val.querySelectorAll(`${$App.dom.dat.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      $.eli = `${$App.dom.dat.tab.cla}[class*='${$.ope}']`;
      $.agr = `${$App.dom.dat.tab.cla}`;

      $App.dom.dat.tab.val.querySelectorAll($.eli).forEach( $e => Ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = Dat.ide($dat.value,$);

        $.dat_ide = ( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.');

        $.col_dat = Dat.get_ide('col', ...$.dat_ide );

        $.col = ( $.col_dat && $.col_dat.val ) ? $.col_dat.val : 1;

        $App.dom.dat.tab.val.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = Dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : Num.val_ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $App.dom.dat.tab.val.querySelectorAll($App.dom.dat.tab.cla).forEach( $e => {

        $e.querySelectorAll('.fig_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = Dat.ide($dat.value,$);        
        // busco valores de ficha
        $.fic = Dat.get_ide('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $App.dom.dat.tab.val.querySelectorAll($App.dom.dat.tab.cla).forEach( $e => {
          // capturar posicion .dep
          $.htm = '';
          $.ele = { title : false, onclick : false  };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos-') ){ 
              $.htm = Dat.get_ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar-') ){ 
              $.htm = Dat.get_ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver-') ){ 
              $.htm = Dat.get_ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_val-opc-/.test($cla_nom) ) return $.htm = Dat.get_ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = Dat.get_ima($e,$);
          }
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.fig_ima') ) ? dom.mod($.htm,$.ima_ele) : dom.agr($.htm,$e,'ini');
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
      $App.dom.dat.tab.val.querySelectorAll($App.dom.dat.tab.cla).forEach( $e => dom.eli($e,$.eti) );

      if( $dat.value ){

        $ = Dat.ide($dat.value,$);

        $App.dom.dat.tab.val.querySelectorAll($App.dom.dat.tab.cla).forEach( $e =>{

          if( $.obj = Dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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