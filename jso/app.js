// WINDOW
'use strict';

// Aplicacion
class _app {

  // peticion
  uri = null
  ;
  // articulo : botonera
  ope = document.querySelector('body > aside.ope')
  ; // pantalla emergente
  win = document.querySelector('#win')
  ; // panel de navegacion
  nav = document.querySelector('body > aside.nav')
  ; // seccion principal
  sec = document.querySelector('body > main')
  ; // panel lateral
  pan = document.querySelector('body > aside.pan')
  ; // barra inferior
  pie = document.querySelector('body > footer')
  ;
  // variable : form > [name]
  var = null
  ;
  // valores : acumulado + seleccion
  val = {
    // acumulados
    acu : { 
      pos : [], 
      mar : [], 
      ver : []
    },
    // filtros
    ver : {
      dat : `form[ide="dat"] select[name="dat-dep"]:not([value=""])`,
      pos : `form[ide="pos"] input[name="ini"]:not([value=""])`,
      fec : `form[ide="fec"] input[name="ini"]:not([value=""])`
    }
  };  
  // Estructura : valores + opciones + filtros + columnas + descripciones + cuentas
  est = {
    dat : `article[ide="est"] div[var="est"] > table`,
    ope : `article[ide="est"]`,
    val : `article[ide="est"] section[ide="val"]`,
    acu : `article[ide="est"] section[ide="val"] form[ide="acu"]`,
    sum : `article[ide="est"] section[ide="val"] form[ide="sum"]`,
    opc : `article[ide="est"] section[ide="opc"]`,
    ver : `article[ide="est"] section[ide="ver"]`,
    atr : `article[ide="est"] section[ide="atr"]`,      
    des : `article[ide="est"] section[ide="des"]`,
    cue : `article[ide="est"] section[ide="cue"]`
  };
  // Tablero : valores + opciones + posicion + seleccion
  tab = {
    dat : `main > article > [tab]`,
    ope : `article[ide="tab"]`,
    val : `article[ide="tab"] section[ide="val"]`,
    acu : `article[ide="tab"] section[ide="val"] form[ide="acu"]`,
    sum : `article[ide="tab"] section[ide="val"] form[ide="sum"]`,
    opc : `article[ide="tab"] section[ide="opc"]`,
    pos : `article[ide="tab"] section[ide="opc"] form[ide="pos"]`,
    sec : `article[ide="tab"] section[ide="opc"] form[ide="sec"]`,
    ver : `article[ide="tab"] section[ide="ver"]`,
    atr : `article[ide="tab"] section[ide="atr"]`
  }

  constructor(){
  }

  // inicializo documento
  ini = ( $ = {} ) => {

    // cargo eventos de teclado
    document.onkeyup = $eve => _doc.inp($eve);

    // anulo formularios
    document.querySelectorAll('form').forEach( $ele => _ele.ope( $ele, 'eje_agr', 'submit', `evt => evt.preventDefault()` ) );

    this.uri = new _app_uri();
    
    // cargo operadores
    if( this.sec ){
      // por menu
      if( this.uri.cab ){
        // operadores del tablero
        if( this.uri.cab == 'tab' ){
          // cargo operadores : article + forms
          ['est','tab'].forEach( $ope => {
            for( const $ide in this[$ope] ){ this[$ope][$ide] = document.querySelector(this[$ope][$ide]); }
          });
          // muestro panel
          _app_ope.bot('nav','tab');
        } 
        // indice por artículo
        else if( this.uri.art && ( $.art_nav = this.nav.querySelector('nav[ide="art"] > section > ul.lis') ) ){          
          // muestro panel
          _app_ope.bot('nav','art');
          // inicio indice
          _doc_lis.nav_tog($.art_nav);
        }
      }
    }      
  }
}

// peticion
class _app_uri {

  // aplicacion
  esq = '';
  // cabecera
  cab = '';
  // articulo
  art = '';
  // valor
  val = '';

  constructor( $ = {} ){

    $.ide = window.location.toLocaleString().split('//')[2].split('/');

    this.esq = $.ide[0];

    this.cab = $.ide[1];

    this.art = $.ide[2];

    if( $.ide[3] ){

      this.val = $.ide[3];
    }
    else if( $.val = this.art.split('#')[1] ){

      this.val = $.val;
    }
  }

  // devuelvo enlace desde url
  ver = ( ...$opc ) => {

    let $_ = [];
  
    if( !!this.esq ){
      
      $_.push(this.esq);
  
      if( !!this.cab ){
  
        $_.push(this.cab);
  
        if( !!this.art ){
  
          $_.push(this.art);
  
          if( $opc.includes('val') && this.val ){
  
            $_.push(this.val);
          }
        }
      }
    }        
    return $_.join('/');
  }
}

// operador : botonera
class _app_ope {

  // botonera : navegadores + pantallas + paneles
  static bot( $tip, $ide ){
  
    switch( $tip ){
    // pantalla : #win > article > header + section
    case 'win':
      // muestro articulo
      if( $ide ){
        // muestro fondo
        $_app.win.classList.remove(DIS_OCU);
        // oculto articulos  
        $_app.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );        
        // muestro por valor
        if( typeof($ide) == 'string' ) $ide = $_app.win.querySelector(`article[ide="${$ide}"].${DIS_OCU}`);

        if( $ide ) $ide.classList.remove(DIS_OCU); 
      }// oculto articulos
      else{        
        $_app.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
        // oculto fondo
        $_app.win.classList.add(DIS_OCU);
      }
      break;
    // navegadores : aside.nav
    case 'nav':      
      if( $ide && $ide.nodeName ) $ide = $ide.getAttribute('ide');
      $_app.nav.querySelectorAll(`:is(nav,article)[ide]:not( [ide="${$ide}"], .${DIS_OCU} )`).forEach( $e => $e.classList.add(DIS_OCU) );
      $_app.nav.querySelectorAll(`:is(nav,article)[ide="${$ide}"]`).forEach( $e => $e.classList.toggle(DIS_OCU) );
      // aculto-muestro contenedor
      if( $_app.nav.querySelector(`:is(nav,article)[ide]:not(.${DIS_OCU})`) ){
        $_app.nav.classList.contains(DIS_OCU) && $_app.nav.classList.remove(DIS_OCU);
      }
      else if( !$_app.nav.classList.contains(DIS_OCU) ){
        $_app.nav.classList.add(DIS_OCU);
      }
      break;
    // seccion : main > article
    case 'sec':
      $_app.sec.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      $_app.sec.querySelectorAll(`article[ide="${$ide}"].${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      $_app.sec.scroll(0, 0);
      break;      
    // campos : form > fieldsets
    case 'var':      
      _lis.val( _ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );      
      break;
    }
  }
}

// pantalla emergente
class _app_win {
}

// panel de navegacion
class _app_nav {
}

// seccion principal
class _app_sec {
}

// Dato
class _app_dat {
    
  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_doc.var($dat);

    $ = _dat.ide($dat,$);
    $ope = _dat.var($.esq,$.est,$ope);

    if( ($.dat_val = $_api._dat_val[$.esq][$.est]) && typeof($ope)=='object' ){

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
    let $_="", $=_doc.var($dat);
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
            $.opc = _doc_lis.opc( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }  

  // conteos : valores de estructura relacionada por atributo
  static cue( $tip, $dat, $ope, ...$opc ){
    let $=_doc.var($dat);

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
    let $_="", $=_doc.var($dat);
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

// Valor
class _app_val {

  // operaciones : alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $=_doc.var($dat);
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

  // filtros : dato + variables
  static ver( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);

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

    let $_, $=_doc.var($dat);

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
      $.val_tot = _app_val.acu('tot',$tip);
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
    let $=_doc.var($dat);

    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;

      $dat.forEach( $ite => $.sum += parseInt($ite.getAttribute(`${$val.dataset.esq}-${$val.dataset.est}`)) );

      _app_dat.fic( $val, $.sum);
      
    });
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
    $_app.tab.ide = $_app.tab.dat.getAttribute('tab');
          
    ['opc'].forEach( $ope => {
      
      if( $_app.tab[$ope] ){

        $_app.tab[$ope].querySelectorAll(`form[ide] [onchange*="_app_tab."]:is( input:checked, select:not([value=""]) )`).forEach( 
          
          $inp => _app_tab[`${$ope}_${_ele.ver($inp,{'eti':`form`}).getAttribute('ide')}`]( $inp )
        );
      }
    });
    // marco posicion principal
    _app_tab.val_pos();

    // actualizo opciones
    Object.keys($_app.val.acu).forEach( $ite =>
      ( $.ele = $_app.tab.acu.querySelector(`[name="${$ite}"]:checked`) ) && _app_tab.val_acu($.ele) 
    );          

  }

  // actualizo : acumulados, sumatorias, fichas, listado
  static act( $dat ){
    
    let $={};
    
    $dat = !$dat ? Object.keys($_app.val.acu) : _lis.ite($dat);

    $.dat = $_app.tab.dat;

    // actualizo valores acumulados
    if( $_app.tab.acu ) $.tot = _app_val.acu('act',$.dat,$_app.tab.acu,...$dat );
    
    // actualizo sumatorias
    if( $_app.tab.sum ) _app_val.sum($.tot, $_app.tab.sum);

    // fichas del tablero
    if( ( $_app.tab.pos ) && ( $.ima = $_app.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $_app.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );

      if( $.ope.length > 0 ) _app_tab.opc_pos($.ima);
    }

    // actualizo valores de lista
    if( $_app.est.acu ){
      // actualizo acumulados
      if( !$_app.est.acu.querySelector(`[name="tod"]:checked`) ) _app_est.val_acu();
      // ejecuto filtros + actualizo totales      
      if( $_app.est.ver ) _app_est.ver('val');
    }    
  }

  // Seleccion : datos, posicion, fecha
  static ver( $tip, $dat, $ope ){

    let $=_doc.var($dat);      

    if( $tip == 'val' ){

      for( const $ide in $_app.val.ver ){

        if( $.ele = $_app.tab.ver.querySelector($_app.val.ver[$ide]) ){

          _app_tab.ver($ide,$.ele,$_app.tab.dat);

          break;
        } 
      }
    }
    else{
      // ejecuto filtros por tipo : pos, fec
      
      _app_val.ver( $tip, _lis.val( $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}`)), $_app.tab.ver, 'ver');
    }
    // actualizo calculos + vistas( fichas + items )
    _app_tab.act('ver');

  }  

  // valores
  static val(){
  }// acumulados( posicion + marcas + seleccion )
  static val_acu( $dat, $ope ){
    
    let $=_doc.var($dat);

    if( !$.var_ide && $ope ) $ = _doc.var( $dat = $_app.tab.acu.querySelector(`[name="${$ope}"]`) );

    $.cla_ide = `_val-${$.var_ide}`;
    
    if( !$dat.checked ){            
      $_app.tab.dat.querySelectorAll(`.${$.cla_ide}`).forEach( $e => $e.classList.remove($.cla_ide) );
    }
    // busco marcas y aplico bordes
    else{
      $_app.tab.dat.querySelectorAll(`.${$.cla_ide}-bor`).forEach( $e => $e.classList.add($.cla_ide) );
    }

    // actualizo calculos + vistas( fichas + items )        
    _app_tab.act($.var_ide);

  }// por posicion principal
  static val_pos( $dat ){
    
    let $=_doc.var($dat);
    
    if( $_hol_app && $_hol_app._val && ( $.kin = $_hol_app._val.kin ) ){

      $_app.tab.dat.querySelectorAll(`.pos[hol-kin="${$.kin}"], .pos[hol-kin="${$.kin}"] [pos][hol-kin="${$.kin}"]`).forEach( $e => {

        $e.classList.add(`_val-pos`);
        $e.classList.add(`_val-pos-bor`);
      });
      // actualizo totales
      _app_tab.act('pos');
    }

  }// por marcas del tablero
  static val_mar( $dat ){
    
    let $=_doc.var($dat);      

    if( $_app.tab.acu && $_app.tab.acu.querySelector(`[name="mar"]:checked`) ){
        
      $.pos = $dat.getAttribute('pos') ? $dat : $dat.parentElement;// desde elemento del tablero / lista  

      if( !$.pos.getAttribute('tab') ){

        $.pos.classList.toggle(`_val-mar`);
        $.pos.classList.toggle(`_val-mar-bor`);
      }
      // actualizo totales
      _app_tab.act('mar');
    }
    
  }// por seleccion : datos + posicion + fecha
  static val_ver( $dat ){
    
    let $=_doc.var($dat);    

    for( const $ide in $_app.val.ver ){

      if( $.ele = $_app.tab.ver.querySelector($_app.val.ver[$ide]) ){

        _app_tab.ver($ide,$.ele,$_app.tab.dat);

        break;
      } 
    }

    _app_tab.act('ver');

  }

  // opciones
  static opc(){
  }// secciones : bordes + colores + imagen + ...
  static opc_sec( $dat ){

    let $=_doc.var($dat); 

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

    let $=_doc.var($dat); 
    
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
              $.htm = _app_dat.ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar') ){ 
              $.htm = _app_dat.ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver') ){ 
              $.htm = _app_dat.ima($e,$);
            }
          }// todos
          else{
            $.htm = _app_dat.ima($e,$);
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
class _app_est {
 
  // inicializo por acumulados
  static ini(){

    let $={};   

    if( $_app.est.acu ){

      if( $.ele = $_app.est.acu.querySelector(`[name="tod"]`) ){

        _app_est.val_tod($.ele);
      }
    }

  }
  // actualizo valores : acumulado + cuentas + descripciones
  static act(){

    let $={};

    // actualizo total
    if( $_app.est.acu && ( $.tot = $_app.est.acu.querySelector('[name="cue"]') ) ){
      
      $.tot.innerHTML = _app_est.cue();
    }
    // actualizo cuentas
    if( $_app.est.cue ){

      _app_dat.cue('act', $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`), $_app.est.cue);
    }
    // actualizo descripciones
    if( $_app.est.des ){

      $_app.est.des.querySelectorAll(`[name]:checked`).forEach( $e => _app_est.des_tog($e) );
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

    let $=_doc.var($dat);  
    
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

      _app_est.val_acu();
    }

    // ejecuto filtros
    if( $_app.est.ver ){

      _app_est.ver('val');
    }

    // actualizo total, cuentas y descripciones
    _app_est.act();
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

    let $=_doc.var($dat);      

    // todos los filtros
    if( $tip == 'val' ){

      for( const $ide in $_app.val.ver ){

        if( $.ele = $_app.est.ver.querySelector($_app.val.ver[$ide]) ){

          _app_est.ver($ide,$.ele);
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
            _app_est.val_acu();
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
          _app_val.ver( $tip, _lis.val( $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`) ), $_app.est.ver, 'ver');

          // oculto valores no seleccionados
          $_app.est.dat.querySelectorAll(`tbody tr[pos]:not(._val-ver,.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );      
        }
      }
      // actualizo total, cuentas y descripciones
      _app_est.act();
    }
  }  
  
  // columnas : toggles + atributos
  static col(){
  }// muestro-oculto
  static col_tog( $dat ){

    let $=_doc.var($dat);      

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

        _app_est.col_tog($e);
      });
    }
  }

  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static des(){
  }// muestro-oculto
  static des_tog( $dat ){

    let $=_doc.var($dat);    

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

    let $=_doc.var($dat);    

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