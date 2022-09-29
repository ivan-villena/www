// WINDOW
'use strict';

// Aplicacion
class _app {

  // peticion
  uri = null;
  // variable : form > [name]
  var = null;  
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
  // Datos 
  dat = {
    // acumulados
    acu : { 
      pos : [], mar : [], ver : []
    },
    // filtros
    ver : {
      val : `form[ide="val"] select[name="val"]:not([value=""])`,
      pos : `form[ide="pos"] input[name="ini"]:not([value=""])`,
      fec : `form[ide="fec"] input[name="ini"]:not([value=""])`
    }
  };  
  // Estructura
  est = {
    lis : `article[ide = "est"] div[var="est"] > table`,
    // datos
    dat : {
      acu : `article[ide = "est"] [data-ide = "dat"] [ide = "acu"]`,
      ver : `article[ide = "est"] [data-ide = "dat"] [ide = "ver"]`,
      sum : `article[ide = "est"] [data-ide = "dat"] [ide = "sum"]`
    },
    // descripciones
    des : `article[ide = "est"] [data-ide = "des"]`,
    // conteos
    cue : `article[ide = "est"] [data-ide = "cue"]`
  };
  // Tablero
  tab = {
    lis : `main > article > [tab]`,
    // datos
    dat : {
      acu : `aside.nav > [ide = "dat"] [ide = "acu"]`,
      ver : `aside.nav > [ide = "dat"] [ide = "ver"]`,
      sum : `aside.nav > [ide = "dat"] [ide = "sum"]`
    },
    // opciones
    opc : {
      sec : `aside.nav > [ide = "opc"] [ide = "sec"]`,    
      pos : `aside.nav > [ide = "opc"] [ide = "pos"]`,
      atr : `aside.nav > [ide = "opc"] [ide = "atr"]`
    },
    // conteos
    cue : `aside.nav > [ide = "cue"]`
  }
  
  // inicializo documento
  ini = ( $ = {} ) => {

    // cargo eventos de teclado
    document.onkeyup = $eve => _doc.inp($eve);

    // anulo formularios
    document.querySelectorAll('form').forEach( $ele => _ele.ope( $ele, 'eje_agr', 'submit', `evt => evt.preventDefault()` ) );
    
    // cargo operadores
    for( const $ide in $_app.ope ){
      $_app.ope[$ide] = document.querySelector($_app.ope[$ide]);
    }
    // por menu
    if( $_app.ope.sec && $_api.app_uri && $_api.app_uri.cab ){      
      // operadores de datos
      if( $_api.app_uri.cab == 'tab' ){
        // cargo operadores : article + forms
        ['est','tab'].forEach( $ope => {
          for( const $ide in $_app[$ope] ){
            if( typeof($_app[$ope][$ide]) == 'string' ){
              $_app[$ope][$ide] = document.querySelector($_app[$ope][$ide]); 
            }
            else{
              for( const $i in $_app[$ope][$ide] ){ 
                $_app[$ope][$ide][$i] = document.querySelector($_app[$ope][$ide][$i]);
              }
            }              
          }
        });
        // muestro panel
        _app_ope.tog('nav','dat');
      }
      // indice por artículo
      else if( $_api.app_uri.art && ( $.art_nav = $_app.ope.nav.querySelector('nav[ide="art"] ul.lis.nav') ) ){          
        // muestro panel
        _app_ope.tog('nav','art');
        // inicio indice
        _doc_lis.nav_tog($.art_nav);
      }
    }      
  }
}

class _app_uri {

  // devuelvo enlace desde url
  static val( ...$opc ) {

    let $_ = [];
  
    if( $_api.app_uri && $_api.app_uri.esq ){
      
      $_.push($_api.app_uri.esq);
  
      if( !!$_api.app_uri.cab ){
  
        $_.push($_api.app_uri.cab);
  
        if( !!$_api.app_uri.art ){
  
          $_.push($_api.app_uri.art);
  
          if( $opc.includes('val') && $_api.app_uri.val ){
  
            $_.push($_api.app_uri.val);
          }
        }
      }
    }        
    return $_.join('/');
  }
}

class _app_ope {

  // botonera : muestro contenido ( paneles + pantallas + seccion + opciones )
  static tog( $tip, $ide ){

    switch( $tip ){
    // pantalla : #win > article > header + section
    case 'win':
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
      break;
    // navegadores : aside.nav
    case 'nav':      
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
      break;
    // seccion : main > article
    case 'sec':
      $_app.ope.sec.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      $_app.ope.sec.querySelectorAll(`article[ide="${$ide}"].${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      $_app.ope.sec.scroll(0, 0);
      break;      
    // campos : form > fieldsets
    case 'var':      
      _lis.val( _ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );      
      break;
    }
  }

}