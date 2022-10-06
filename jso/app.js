// WINDOW
'use strict';

// Aplicacion
class _app {

  // página
  ope = {
    // botonera
    bot : 'body > aside.bot',
    // pantalla emergente
    win : '#win',
    // panel de navegacion
    nav : 'body > aside.nav',
    // seccion principal
    sec : 'body > main',
    // panel lateral
    pan : 'body > aside.pan',
    // barra inferior
    pie : 'body > footer',
  };
  // Formulario
  var = null
  ;
  // Valores
  val = {
    // acumulados
    acu : [ "pos", "mar", "ver", "opc" ],
    // filtros
    ver : {
      val : `form[ide = "val"] select[name="val"]`,
      fec : `form[ide = "fec"] input[name="ini"]`,
      pos : `form[ide = "pos"] input[name="ini"]`
    }
  };  
  // Estructura
  est = {
    lis : `article[ide = "est"] div[var="est"] > table`,
    // Valores
    val : {
      acu : `article[ide = "est"] [data-ide = "val"] [ide = "acu"]`,
      ver : `article[ide = "est"] [data-ide = "val"] [ide = "ver"]`,
      sum : `article[ide = "est"] [data-ide = "val"] [ide = "sum"]`
    },
    // Descripciones
    des : `article[ide = "est"] [data-ide = "des"]`,
    // Conteos
    cue : `article[ide = "est"] [data-ide = "cue"]`
  };
  // Tablero
  tab = {
    lis : `main > article > [tab]`,
    // Valores
    val : {
      acu : `aside.nav > [ide = "val"] [ide = "acu"]`,
      ver : `aside.nav > [ide = "val"] [ide = "ver"]`,
      sum : `aside.nav > [ide = "val"] [ide = "sum"]`
    },
    // Opciones : seccion + posicion + ...atributos
    opc : {
      sec : `aside.nav > [ide = "opc"] form[ide = "sec"]`,    
      pos : `aside.nav > [ide = "opc"] form[ide = "pos"]`,
      atr : `aside.nav > [ide = "opc"] section[ide = "atr"]`
    },
    // Conteos
    cue : `aside.nav > [ide = "cue"]`
  }

  // cargo elementos
  constructor(){

    // cargo eventos de teclado
    document.onkeyup = $eve => {
      let $ = {};
      switch( $eve.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // menú contextual
        if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
          $.men.classList.add(DIS_OCU);
        }// pantallas
        else if( document.querySelector(`#win:not(.${DIS_OCU}) article header .ico[ide="eje_fin"]`) ){ 
          _app_ope.win();
        }// navegacion
        else if( document.querySelector(`aside.nav > [ide]:not(.${DIS_OCU})`) ){ 
          _app_ope.nav();
        }
        break;
      }
    };

    // anulo formularios
    document.querySelectorAll('form').forEach( 
      $ele => _ele.ope( $ele,'eje_agr','submit',`evt => evt.preventDefault()`) 
    );

    // peticion
    this.uri = !! $_api.app_uri ? $_api.app_uri : {};    

    // cargo operadores
    for( const $ide in this.ope ){

      this.ope[$ide] = document.querySelector(this.ope[$ide]);
    }

    if( this.uri.cab == 'tab' ){
      // cargo operadores : article + forms
      ['est','tab'].forEach( $ope => {
        for( const $ide in this[$ope] ){
          if( typeof(this[$ope][$ide]) == 'string' ){
            this[$ope][$ide] = document.querySelector(this[$ope][$ide]); 
          }
          else{
            for( const $i in this[$ope][$ide] ){ 
              this[$ope][$ide][$i] = document.querySelector(this[$ope][$ide][$i]);
            }
          }              
        }
      });
    }    
  }  
  // inicializo aplicacion : tablero + indices
  ini( $ = {} ){  

    // inicializo por aplicacion
    if( $_app.uri && $_app.uri.cab ){

      // operadores de datos
      if( $_app.uri.cab == 'tab' ){

        // inicializo opciones por esquema : sec + pos + atr
        if( $.cla_app = eval($.cla = `_${$_app.uri.esq}_app`) ){          

          _app_tab.ini();

          _app_est.ini();
  
          // secciones y posiciones
          ['sec'].forEach( $ope => {
      
            if( $_app.tab.opc[$ope] ){

              $.eje = `tab_${$ope}`;
      
              $_app.tab.opc[$ope].querySelectorAll(`form[ide] [name][onchange*="${$.cla}.${$.eje}"]`).forEach( 
  
                $inp => $.cla_app[$.eje] && $.cla_app[$.eje]( $inp )
              );
            }
          });
          // + atributos
          if( $_app.tab.opc.atr ){
  
            $_app.tab.opc.atr.querySelectorAll(`form[ide]`).forEach( $for => {
              
              $.eje = `tab_${$for.getAttribute('ide')}`;
              
              $for.querySelectorAll(`[name][onchange*="${$.cla}.${$.eje}"]`).forEach( $inp => {

                if( !!$.cla_app[$.eje] ) $.cla_app[$.eje]( $inp );
              });
            });
          }
        }

        // muestro panel : opciones
        _app_ope.nav('opc');
      }
      // inicializo indice por artículo
      else if( $_app.uri.art && ( $.art_nav = $_app.ope.nav.querySelector('nav[ide="art"] ul.lis.nav') ) ){          
        // inicio indice
        _doc_lis.nav_tog($.art_nav);
        
        // muestro panel
        _app_ope.nav('art');
      }
    }      
  }

  // peticion
  uri = null;
  // devuelvo enlace desde url
  uri_val( ...$opc ) {

    let $_ = [];
  
    if( $_app.uri && $_app.uri.esq ){
      
      $_.push($_app.uri.esq);
  
      if( !!$_app.uri.cab ){
  
        $_.push($_app.uri.cab);
  
        if( !!$_app.uri.art ){
  
          $_.push($_app.uri.art);
  
          if( $opc.includes('val') && $_app.uri.val ){
  
            $_.push($_app.uri.val);
          }
        }
      }
    }        
    return $_.join('/');
  }

  // datos
  dat = null;
  // imagen por identificadores
  static dat_ima( $dat, $ope ){

    let $_ = "";

    if( $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.getAttribute(`${$ope.esq}-${$ope.est}`) ) ){

      if( !$ope.fic ) $ope.fic = _dat.val_ver('ima', $ope.esq, $ope.est );

      $_ = _doc.ima($ope.fic.esq, $ope.fic.est, _dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
}

// Página
class _app_ope {

  // pantalla : #win > article > header + section
  static win( $ide ){
    // muestro articulo
    if( $ide ){
      // muestro fondo
      $_app.ope.win.classList.remove(DIS_OCU);
      // oculto articulos  
      $_app.ope.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );        
      // muestro por valor
      if( typeof($ide) == 'string' ) $ide = $_app.ope.win.querySelector(`article[ide="${$ide}"].${DIS_OCU}`);

      if( $ide ) $ide.classList.remove(DIS_OCU); 
    }// oculto articulos
    else{        
      $_app.ope.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // oculto fondo
      $_app.ope.win.classList.add(DIS_OCU);
    }
  }
  // navegadores : aside.nav
  static nav( $ide ){

    if( $ide && $ide.nodeName ) $ide = $ide.getAttribute('ide');

    $_app.ope.nav.querySelectorAll(`:is(nav,article)[ide]:not( [ide="${$ide}"], .${DIS_OCU} )`).forEach( $e => $e.classList.add(DIS_OCU) );
    $_app.ope.nav.querySelectorAll(`:is(nav,article)[ide="${$ide}"]`).forEach( $e => $e.classList.toggle(DIS_OCU) );

    // aculto-muestro contenedor
    if( $_app.ope.nav.querySelector(`:is(nav,article)[ide]:not(.${DIS_OCU})`) ){
      $_app.ope.nav.classList.contains(DIS_OCU) && $_app.ope.nav.classList.remove(DIS_OCU);
    }
    else if( !$_app.ope.nav.classList.contains(DIS_OCU) ){
      $_app.ope.nav.classList.add(DIS_OCU);
    }
  }
  // seccion : main > article
  static sec( $ide ){
    $_app.ope.sec.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $_app.ope.sec.querySelectorAll(`article[ide="${$ide}"].${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $_app.ope.sec.scroll(0, 0);
  }
  // campos : form > fieldsets
  static var( $ide ){
    _lis.val( _ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }
}

// Dato
class _app_dat {

  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $ = {};

    $ = _dat.ide($dat,$);

    $.dat_var = _dat.get($.esq,$.est,$ope);
    $.dat_val = $_api.dat_val[$.esq][$.est];

    if( $.dat_val && typeof($.dat_var) == 'object' ){

      if( $tip == 'tit' ){
        $_ = _obj.val($.dat_var,$.dat_val.nom) + ( $.dat_val.des ? "\n"+_obj.val($.dat_var,$.dat_val.des) : '' );
      }
      else if( !!($.dat_val[$tip]) ){
        $_ = _obj.val($.dat_var,$.dat_val[$tip]);  
      }
      if( $tip == 'ima' ){

        $.ele = !!$opc[0] ? $opc[0] : {};

        if( $.ele.title === undefined ){

          $.ele.title = _app_dat.val('tit',`${$.esq}.${$.est}`,$.dat_var);
        }
        else if( $.ele.title === false ){

          delete($.ele.title);
        }        
        $_ = _doc.ima( { 'style': $_ }, $.ele );
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
        _ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          _ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
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
          _eje.val(['_dat::get', [`_${$.esq}.${$.est}`] ], $dat => {
            $.opc = _doc_opc.val( $dat, $.opc, 'ide');
          });
        }
      }
      break;
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
      $.dat[`hol_${$.atr}`] = ( $ope > 0 ) ? _num.ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`hol_${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`div.atr [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      _ele.eli($ite,'[ima]');      
      if( $.val = $.dat[$.est] ){
        _ele.agr( 
          _doc.ima( $.ima[0], $.ima[1], _dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite
        );
      }
    });   
    
    return $_;
  }
}

// Valor
class _app_val {

  // alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $ = _doc_val.var($dat);
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
  // acumulados : posicion + marca + seleccion
  static acu( $dat, $ope, ...$opc ){

    let $ = {};
    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $_app.val.acu : $opc ).forEach( $ide => {

      // acumulo elementos del listado
      $.acu_val[$ide] = $dat.querySelectorAll(`._val-${$ide}`);

      // actualizo total del operador
      if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

        $.tot.innerHTML = $.acu_val[$ide].length;
      }
    });
    // calculo el total grupal
    if( $.tot = $ope.querySelector(`[name="tot"]`) ){

      $.tot.innerHTML = $dat.querySelectorAll(`[class*="_val-"]:is([class*="-bor"],[class*="-fon"])`).length;
    }

    // devuelvo seleccion
    return $.acu_val;
  }
  // sumatorias
  static sum( $dat, $ope ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;

      $dat.forEach( 
        $ite => $.sum += parseInt( $ite.getAttribute(`${$val.dataset.esq}-${$val.dataset.est}`) ) 
      );

      _app_dat.fic( $val, $.sum);
    });
  }
  // filtros : dato + variables
  static ver( $tip, $dat, $ope, ...$opc ){

    let $ = _doc_val.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver`;
    $.cla_ide = `${$.cla_val}_${$tip}`;
    
    _ele.act('cla_eli',$dat,[$.cla_val, $.cla_ide]);

    $_app.var = $ope.querySelector(`form[ide="${$tip}"]`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'val' ){

      $.dat_est = $_app.var.querySelector(`[name="est"]`);
      $.dat_ide = $_app.var.querySelector(`[name="ver"]`);
      $.dat_val = $_app.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = _dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = _dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) _ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $tip == 'pos' || $tip == 'fec' ){
      
      // elimino valor de dato por seleccion
      if( ( $.ver = $ope.querySelector(`form[ide="dat"] select[name="val"]`) ) && !!$.ver.value ) $.ver.value = '';
            
      // valores
      $.val = {};
      ['ini','fin','inc','lim'].forEach( $ide => {
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
      $.inc_val = 1;
      if( ( !$.val.inc || $.val.inc <= 0 ) && ( $.ite = $_app.var.querySelector(`[name="inc"]`) ) ){
        $.ite.value = $.val.inc = 1;
      }
      // inicializo limites desde
      if( !$.val.fin 
        && ( $.ite = $_app.var.querySelector(`[name="fin"]`) ) && ( $.max = $.ite.getAttribute('max') ) 
      ){
        $.val.fin = $.max;
      }
      // filtro por posicion de lista      
      if( $tip == 'pos' ){
        
        $dat.forEach( $e => {
          // valor por desde-hasta
          $.pos_val = $e.getAttribute('pos');
          if( $.inc_val == 1 && $.pos_val >= $.val.ini && $.pos_val <= $.val.fin ){
            _ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
          if( $.inc_val == 1 && _fec.ver( $e.getAttribute('api-fec'), $.val.ini, $.val.fin ) ){

            _ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
        if( $_app.var.querySelector(`.ico[ide="nav_fin"].bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) _ele.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }
  // conteos : valores de estructura relacionada por atributo
  static cue( $tip, $dat, $ope, ...$opc ){
    let $ = _doc_val.var($dat);

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

                if( ( $.dat_val = _dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide = $.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
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

          if( _dat.ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
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
}

// Tablero
class _app_tab {

  // inicializo : opciones, posicion, filtros
  static ini(){

    let $={};      

    if( !$_app.tab.cla ){
      $_app.tab.cla = '.pos';
    }
    // inicializo opciones
    $_app.tab.ide = $_app.tab.lis.getAttribute('tab');
    ['sec','pos'].forEach( $ope => {
      
      if( $_app.tab.opc[$ope] ){
        $_app.tab.opc[$ope].querySelectorAll(
          `form[ide] [onchange*="_app_tab."]:is( input:checked, select:not([value=""]) )`
        ).forEach( 
          $inp => _app_tab[`opc_${$ope}`]( $inp )
        );
      }
    });
    // marco posicion principal
    _app_tab.val('pos');

    // actualizo opciones
    $_app.val.acu.forEach( $ite =>
      ( $.ele = $_app.tab.val.acu.querySelector(`[name="${$ite}"]:checked`) ) && _app_tab.val_acu($.ele) 
    );          

  }
  // actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $_app.val.acu : _lis.ite($dat);

    $.dat = $_app.tab.lis;

    // acumulados
    if( $_app.tab.val.acu ){ 

      // actualizo toales acumulados
      _app_val.acu($_app.tab.lis, $_app.tab.val.acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $_app.tab.val.sum ){
        $.tot = [];
        $_app.val.acu.forEach( $acu_ide => {

          if( $_app.tab.val.acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$_app.tab.lis.querySelectorAll(`._val-${$acu_ide}`) );
          }
        });
        _app_val.sum($.tot, $_app.tab.val.sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$_app.est.val.acu.querySelector(`[name="tod"]:checked`) ) _app_est.val_acu();

      // -> ejecuto filtros + actualizo totales
      if( $_app.est.val.ver ) _app_est.val_ver();
    }

    // fichas del tablero
    if( ( $_app.tab.opc.pos ) && ( $.ima = $_app.tab.opc.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => 
        ( $.val = $_app.tab.opc.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) 
      );

      if( $.ope.length > 0 ) _app_tab.opc_pos($.ima);
    }

    // actualizo cuentas
    if( $_app.tab.cue ){

      _app_val.cue('act', $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[class*=_val-]`), $_app.tab.cue );
    }
  }
  // Datos
  static val( $tip, $dat ){

    let $ = _doc_val.var($dat);

    switch( $tip ){
    case 'pos': 
      if( $_hol_app && $_hol_app.val && ( $.kin = $_hol_app.val.kin ) ){

        $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[api-hol_kin="${$.kin}"]`).forEach( $e => {
          $e.classList.add(`_val-pos`);
          if( $_app.tab.val.acu && $_app.tab.val.acu.querySelector(`[name="pos"]:checked`) ){
            $e.classList.add(`_val-pos-bor`);
          }
        });
      }
      break;
    case 'mar':
      $.pos = $dat.getAttribute('pos') ? $dat : $dat.parentElement;
  
      if( !$.pos.getAttribute('tab') ){

        $.pos.classList.toggle(`_val-mar`);
        // marco bordes
        if( $_app.tab.val.acu ){
          if( $.pos.classList.contains(`_val-mar`) && $_app.tab.val.acu.querySelector(`[name="mar"]:checked`) ){
            $.pos.classList.add(`_val-mar-bor`);
          }
          else if( !$.pos.classList.contains(`_val-mar`) && $.pos.classList.contains(`_val-mar-bor`) ){
            $.pos.classList.remove(`_val-mar-bor`);
          }
        }
      }
      break;
    case 'ver':
      for( const $ide in $_app.val.ver ){

        if( $.ele = $_app.tab.val.ver.querySelector(`${$_app.val.ver[$ide]}:not([value=""])`) ){
  
          _app_tab.val_ver($ide,$.ele,$_app.tab.lis);

          break;
        }
      }
      break;
    }
    // actualizo totales
    _app_tab.act($tip);
    
  }// acumulados( posicion + marcas + seleccion )
  static val_acu( $dat, $ope ){
    
    let $ = _doc_val.var($dat);

    if( !$.var_ide && $ope ) $ = _doc_val.var( $dat = $_app.tab.val.acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas y aplico bordes
    $.cla_ide = `_val-${$.var_ide}`;

    _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.${$.cla_ide}-bor`),`${$.cla_ide}-bor`);    
    if( $dat.checked ){
      _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.${$.cla_ide}:not(.${$.cla_ide}-fon)`),`${$.cla_ide}-bor`);
    }

    // actualizo calculos + vistas( fichas + items )        
    _app_tab.act($.var_ide);

  }// Seleccion : datos, posicion, fecha
  static val_ver( $tip ){

    // ejecuto filtros por tipo : pos, fec      
    _app_val.ver($tip, _lis.val($_app.tab.lis.querySelectorAll(`${$_app.tab.cla}`)), $_app.tab.val.ver );

    // marco seleccionados
    _ele.act('cla_eli',$_app.tab.lis.querySelectorAll('._val-ver-bor'),'_val-ver-bor');
    if( $_app.tab.val.acu && $_app.tab.val.acu.querySelector(`[name="ver"]:checked`) ){
      _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`._val-ver`),'_val-ver-bor');
    }

    // actualizo calculos + vistas( fichas + items )
    _app_tab.act('ver');
  }
  // Opciones
  static opc(){
  }// secciones : bordes + colores + imagen + ...
  static opc_sec( $dat ){

    let $ = _doc_val.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$_app.tab.lis.classList.contains('bor-1') ){ $_app.tab.lis.classList.add('bor-1'); }
        $_app.tab.lis.querySelectorAll('ul[tab]:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $_app.tab.lis.classList.contains('bor-1') ){ $_app.tab.lis.classList.remove('bor-1'); }
        $_app.tab.lis.querySelectorAll('ul[tab].bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $_app.tab.lis.querySelectorAll(`[tab][class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $_app.tab.lis.classList.contains('fon-0') ){
          $_app.tab.lis.classList.remove('fon-0');
        }
      }else{
        // secciones
        $_app.tab.lis.querySelectorAll(`[tab][class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$_app.tab.lis.classList.contains('fon-0') ){
          $_app.tab.lis.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $_app.tab.lis.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $_app.tab.lis.style.backgroundImage = '';
      }
      break;      
    }     
  }// posiciones : borde + color + imagen + texto + numero + fecha
  static opc_pos( $dat ){

    let $ = _doc_val.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $_app.tab.opc.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $_app.val.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $_app.tab.opc.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
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

      $_app.tab.lis.querySelectorAll($.eli).forEach( $e => _ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $.col = _dat.val_ver('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        $.col = ( $.col && $.col.val ) ? $.col.val : 0;

        $_app.tab.lis.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = _dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : _num.ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $_app.tab.lis.querySelectorAll($_app.tab.cla).forEach( $e => {

        $e.querySelectorAll('[ima]').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = _dat.ide($dat.value,$);
        
        // busco valores de ficha
        $.fic = _dat.val_ver('ima', ...( 
          ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value 
        ).split('.') );

        // actualizo por opciones        
        $_app.tab.lis.querySelectorAll($_app.tab.cla).forEach( $e => {
          $.htm = '';
          $.ele = { 'title' : false };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos') ){ 
              $.htm = _app.dat_ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar') ){ 
              $.htm = _app.dat_ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver') ){ 
              $.htm = _app.dat_ima($e,$);
            }
            else if( $.ima.opc && $e.classList.contains('_val-opc') ){ 
              $.htm = _app.dat_ima($e,$);
            }
          }// todos
          else{
            $.htm = _app.dat_ima($e,$);
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
      $_app.tab.lis.querySelectorAll($_app.tab.cla).forEach( $e => _ele.eli($e,$.eti) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $_app.tab.lis.querySelectorAll($_app.tab.cla).forEach( $e =>{

          if( $.obj = _dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

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
class _app_est {
 
  // inicializo por acumulados
  static ini(){

    let $={};   

    if( $_app.est.val.acu ){

      if( $.ele = $_app.est.val.acu.querySelector(`[name="tod"]`) ){

        _app_est.val_tod($.ele);
      }
    }

  }
  // actualizo valores : acumulado + cuentas + descripciones
  static act(){

    let $={};
    // actualizo total
    if( $_app.est.val.acu && ( $.tot = $_app.est.val.acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = _app_est.val('tot');
    }    
    // actualizo cuentas
    if( $_app.est.cue ){

      _app_val.cue('act', $_app.est.lis.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`), $_app.est.cue);
    }
    // actualizo descripciones
    if( $_app.est.des ){

      $_app.est.des.querySelectorAll(`[name]:checked`).forEach( $e => _app_est.des_tog($e) );
    }
  }
  // datos : todos | acumulados
  static val( $tip ){

    switch( $tip ){
    // cuento items en pantalla
    case 'tot':
      if( $_app.est.lis ){
      
        return $_app.est.lis.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`).length;
      }
      else{
        return 'err: no hay tabla relacionada';
      }      
      break;
    }
  }// check : todos ? - o por acumulados
  static val_tod( $dat ){

    let $ = _doc_val.var($dat);  
    
    if( $_app.est.val.acu ){
      // ajusto controles acumulados
      $_app.val.acu.forEach( $i => {

        if( $.val = $_app.est.val.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
      });
    }
    // ejecuto todos los filtros y actualizo totales
    _app_est.val_ver();

  }// acumulados : posicion - marcas - seleccion
  static val_acu(){

    let $={};
    
    if( ( $.esq = $_app.est.lis.dataset.esq ) && ( $.est = $_app.est.lis.dataset.est ) ){
      
      // oculto todos los items de la tabla
      _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`),DIS_OCU);

      // actualizo por acumulado
      $_app.val.acu.forEach( $ide => {

        if( $.val = $_app.est.val.acu.querySelector(`[name='${$ide}']`) ){

          $.tot = 0;
          if( $.val.checked ){
            // recorro seleccionados
            $_app.tab.lis.querySelectorAll(`[pos]._val-${$ide}`).forEach( $e =>{
              
              if( $.ele = $_app.est.lis.querySelector(
                `tr[pos][${$.esq}-${$.est}="${$e.getAttribute(`${$.esq}-${$.est}`)}"].${DIS_OCU}`
              ) ){
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
  }// filtros : Valores + Fecha + Posicion
  static val_ver( $tip, $dat, $ope, ...$opc ){

    let $ = _doc_val.var($dat);

    // ejecuto filtros
    if( !$tip ){

      // 1º - muestro todos
      if( !$_app.est.val.acu || $_app.est.val.acu.querySelector(`[name="tod"]:checked`) ){

        _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tr[pos].${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        _app_est.val_acu();
      }
      // 2º - cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $_app.val.ver ){
        // tomo solo los que tienen valor
        if( ( $.val = $_app.est.val.ver.querySelector(`${$_app.val.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){

        $.eje.forEach( $ope_ide => {

          _app_val.ver($ope_ide, _lis.val( $_app.est.lis.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`) ), 
            $_app.est.val.ver
          );

          // oculto valores no seleccionados
          _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr[pos]:not(._val-ver, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else{
      if( ['cic','gru'].includes($tip) ){
        // muestro todos los items
        _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tbody tr:not([pos]).${DIS_OCU}`),DIS_OCU);        
        
        // aplico filtro
        // ... 
      }
    }
    // actualizo total, cuentas y descripciones
    _app_est.act();
  }
  // columnas : toggles + atributos
  static atr(){
  }// muestro-oculto
  static atr_tog( $dat ){

    let $ = _doc_val.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $_app.est.lis.querySelectorAll(
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

        _app_est.atr_tog($e);
      });
    }
  }
  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static des(){
  }// muestro-oculto
  static des_tog( $dat ){

    let $ = _doc_val.var($dat);
    $.ope  = $_app.var.getAttribute('ide');
    $.esq = $_app.var.dataset.esq;
    $.est = $_app.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    _ele.act('cla_agr',$_app.est.lis.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $_app.est.lis.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = _dat.get($.esq,$.est,$ite.getAttribute(`${$.esq}-${$.est}`)) ) && $.val[$.atr] ){

          $.ide = ( $.ope == 'des' ) ? $ite.getAttribute(`${$.esq}-${$.est}`) : $.val[$.atr];

          _ele.act('cla_eli',$_app.est.lis.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// filtro por descripciones
  static des_ver( $dat, $ope, ...$opc ){

    let $ = _doc_val.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $_app.est.lis.querySelectorAll(`tbody tr:not([data-ope="des"])[opc="${$.ite}"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $_app.est.val.ver.querySelector(`form[ide="dat"] select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide = $_app.est.val.ver.querySelector(`form[ide="dat"] select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `[ide="${$.ide.value}"]` : '';

            _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $_app.est.lis.querySelectorAll(`tbody tr:not([pos]):not(.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $_app.est.lis.querySelector(
                `table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`
            ) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      _ele.act('atr_act',$_app.est.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $_app.est.lis.querySelectorAll(`tbody tr:not([pos]):not(.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $_app.est.lis.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"][ide="${$e.dataset.ide}"].${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
}