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
        else if( this.uri.art && ( $.art_nav = this.nav.querySelector('nav[ide="art"] ul.lis.nav') ) ){          
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

    $.uri = window.location.toLocaleString().split('//');

    if( !$.uri[2] ) $.uri[2] = 'hol';

    $.ide = $.uri[2].split('/');

    this.esq = $.ide[0];

    this.cab = $.ide[1];

    this.art = $.ide[2];

    if( $.ide[3] ){

      this.val = $.ide[3];
    }
    else if( this.art && ( $.val = this.art.split('#')[1] ) ){

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