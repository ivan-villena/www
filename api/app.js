// Aplicacion
'use strict';

class app {
  
  // peticion
  uri = {    
  };
  // Valores
  val = {
    // acumulados
    acu : [ "pos", "mar", "ver", "opc" ],
    // filtros
    ver : {
      val : `form.ide-val select[name="val"]`,
      fec : `form.ide-fec input[name="ini"]`,
      pos : `form.ide-pos input[name="ini"]`
    }
  };
  // Estructura
  est = {
    lis : `article.ide-est .app_est`,
    // Valores
    val : {
      acu : `article.ide-est .ide-val .ide-acu`,      
      sum : `article.ide-est .ide-val .ide-sum`,
      cue : `article.ide-est .ide-val .ide-cue`
    },
    // filtros
    ver : `article.ide-est .ide-ver`,
    // Descripciones
    des : `article.ide-est .ide-des`
  };
  // Tablero
  tab = {
    lis : `main > article > .tab`,
    // Valores
    val : {
      acu : `aside.doc_pan > .ide-val .ide-acu`,      
      sum : `aside.doc_pan > .ide-val .ide-sum`,
      cue : `aside.doc_pan > .ide-val .ide-cue`
    },
    // Seleccion
    ver : `aside.doc_pan > .ide-ver`,
    // Opciones : seccion + posicion + ...atributos
    opc : {
      sec : `aside.doc_pan > .ide-opc .ide-sec`,    
      pos : `aside.doc_pan > .ide-opc .ide-pos`,
      atr : `aside.doc_pan > .ide-opc .ide-atr`
    }
  }

  // cargo elementos
  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    }  
    
    // cargo operadores : val + est + tab
    if( this.uri.cab == 'ope' ){

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

    // inicio : muestro menu
    if( !$api_app.uri.cab ){

      ( $.bot_ini = $api_doc._bot.querySelector('.ico.app_cab') ) && $.bot_ini.click();
    }
    // articulo
    else{
      // expando menu seleccionado      
      if( $.cab = $api_doc._pan.querySelector(`nav.ide-app_cab p.ide-${$api_app.uri.cab}`) ) $.cab.click();

      // inicializo operadores  : sec + pos + atr
      if( $api_app.uri.cab == 'ope' ){

        if( $.cla_app = eval($.cla = `${$api_app.uri.esq}_ope`) ){

          tab.lis_ini();
          est.lis_ini();
          // secciones y posiciones
          ['sec'].forEach( $ope => {
            if( $api_app.tab.opc[$ope] ){
              $.eje = `tab_${$ope}`;
              $api_app.tab.opc[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$.cla}.${$.eje}"]`).forEach(

                $inp => $.cla_app[$.eje] && $.cla_app[$.eje]( $inp )
              );
            }
          });
          // + atributos
          if( $api_app.tab.opc.atr ){  
            $api_app.tab.opc.atr.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
              
              $.eje = `tab_${$for.classList[0].split('-')[2]}`;

              $for.querySelectorAll(`[name][onchange*="${$.cla}.${$.eje}"]`).forEach( 

                $inp => !!$.cla_app[$.eje] && $.cla_app[$.eje]( $inp )
              );
            });
          }
        }
      }
      // inicializo indice por artículo
      else if( $api_app.uri.art && ( $.art_nav = $api_doc._pan.querySelector('nav.ide-app_nav ul.lis.nav') ) ){
        // inicio indice
        app.art_tog($.art_nav);        
        // muestro panel
        doc.pan('app_nav');
      }
    }
  }

  // indice
  static art( $dat, $cla = FON_SEL ){

    let $ = { lis : ele.val_ver($dat,{'eti':'nav'}) };

    if( $.lis ){
      // elimino marcas previas
      $.lis.querySelectorAll(
        `ul.lis.nav :is( li.pos.sep, li.pos:not(.sep) > div.val ).${$cla}`
      ).forEach( 
        $e => $e.classList.remove($cla) 
      );

      // controlo el toggle automatico por dependencias
      if( 
        ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
        &&
        ( $dat.classList.contains('ico') || $.dep.classList.contains(DIS_OCU) ) 
      ){
        doc.val($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }// - hago toogle por item
  static art_tog( $lis ){

    let $={};

    if( $.nav = $lis ? app.art_mar($lis) : false ){
      // hago toogles ascendentes
      while( 
        ( $.lis = ele.val_ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.getAttribute('ico') ){                
          doc.val($.ico);
        }                
      }
    }
  }// - marco valor seleccionado
  static art_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      app.art($nav);
    }

    return $nav;
  }// - ejecuto filtros
  static art_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    lis.ite_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    app.art_tog($api_doc._var.nextElementSibling);

  }    
}
