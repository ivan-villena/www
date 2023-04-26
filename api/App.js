'use strict';

class App {

  // Log de peticiones
  log = { php: [], jso: [] };

  // Estructuras de datos
  dat = {};

  // Recursos de la Aplicacion
  rec = { uri : {} };

  // Elementos del documento
  dom = {
    // Pagina
    doc : {
      bot: 'body > .doc_bot',
      win: 'body > .doc_win',
      pan: 'body > .doc_pan',
      sec: 'body > .doc_sec',
      bar: 'body > .doc_bar',
      pie: 'body > .doc_pie'
    },
    // Datos de Estructura: Operador + Listado + tablero
    dat : {
      var: null
      ,
      ope: {
        // acumulados
        acu : [ "pos", "mar", "ver", "opc" ],
        // filtros
        ver : {
          dat : `form.ide-dat select[name="val"]`,
          fec : `form.ide-fec input[name="ini"]`,
          pos : `form.ide-pos input[name="ini"]`
        }    
      },
      lis: {
        // Valores
        val : `.ide-lis .dat.lis`,
        // Operador
        val_acu : `.ide-lis .ide-ver .ide-acu`,      
        val_sum : `.ide-lis .ide-ver .ide-sum`,
        val_cue : `.ide-lis .ide-ver .ide-cue`,    
        // filtros
        ver : `.ide-lis .ide-ver`,
        // Descripciones
        des : `.ide-lis .ide-des`    
      },
      tab: {
        ide : null,
        dep : null,
        cla : null,
        // Valores
        val : `main > article > .dat.tab`,
        // operador
        val_acu : `.doc_pan > .ide-val .ide-acu`,
        val_sum : `.doc_pan > .ide-val .ide-sum`,
        val_cue : `.doc_pan > .ide-val .ide-cue`,
        // Seleccion
        ver : `.doc_pan > .ide-ver`,
        // seccion + posicion
        sec : `.doc_pan > .ide-opc .ide-sec`,    
        pos : `.doc_pan > .ide-opc .ide-pos`,
        // ...opciones
        opc : `.doc_pan > .ide-opc .ide-opc`
      }
    }
  };

  constructor( $dat = {} ){

    let $ = {};

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }    

    /* Cargo Eventos */

    // - 1: teclado
    document.onkeyup = ( $eve ) => {

      switch( $eve.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // 1- opciones
        if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
          $.men.classList.add(DIS_OCU);
        }
        // 2- operador
        else if( $.ope = document.querySelector(`nav.ope ~ * > [class*="ide-"]:not(.${DIS_OCU})`) ){
          $.nav = $.ope.parentElement.previousElementSibling;
          if( $.ico = $.nav.querySelector(`.fig_ico.fon-sel`) ) $.ico.click();
        }
        // 3- pantalla
        else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $.art = $App.dom.doc.win.children;          
          for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
            const $art = $.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              sis_doc.win( $art.querySelector(`header:first-child .fig_ico[data-ope="fin"]`) );
              break;
            }
          }
        }
        // 4- paneles
        else if( document.querySelector(`.doc_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
          sis_doc.pan();
        }
        break;
      }
    };
    // - 2: anulo formularios
    document.querySelectorAll('form').forEach( $ele => Ele.act('eje_agr',$ele,'submit',`evt => evt.preventDefault()`) );

    /* Cargo Elementos */

    // del Documento
    for( const $atr in this.dom.doc ){
      this.dom.doc[$atr] = document.querySelector(this.dom.doc[$atr]);
    }
    
    // de Datos
    ['lis','tab'].forEach( $ope => {

      for( const $ide in this.dom.dat[$ope] ){ 

        if( typeof(this.dom.dat[$ope][$ide]) == 'string' ){

          this.dom.dat[$ope][$ide] = document.querySelector(this.dom.dat[$ope][$ide]); 
        }
      }
    });
    // - Actualizo clase principal
    this.dom.dat.tab.cla = ".pos.ope";    

    /* Inicio Aplicacion */
    
    // desde Articulo
    if( $.cab = this.rec.uri.cab ){
      
      // Menu: expando seleccionado
      if( $.app_cab = $App.dom.doc.pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 

        $.app_cab.click();
        
        if( $.art = this.rec.uri.art ){
          
          // Pinto fondo si hay opcion seleccionada
          if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){
            $.app_art.parentElement.classList.add('fon-sel');
          }
          
          // Índice: hago click y muestro panel
          if( $.art && ( $.art_nav = $App.dom.doc.pan.querySelector('.ide-app_nav ul.lis.nav') ) ){
            // inicializo enlace local
            Lis.nav_tog($.art_nav);
            // muestro panel
            sis_doc.pan('app_nav');
          }        
        }
      }  
    }
    // o muestro menú principal
    else{

      // if( $.bot_ini = $App.dom.doc.bot.querySelector('.fig_ico.ide-app_cab') ) $.bot_ini.click();
    }    

  }  
  

  /*  Consola de Administracion */
  static doc_adm( $tip, $dat, $val, ...$opc ){
  
    let $ = Doc.var($dat);
    
    // -> desde form : vacío resultados previos
    if( $App.dom.dat.var && ( $.res = $App.dom.dat.var.querySelector('.ope_res') ) ){ 

      dom.eli($.res);
    }
    // -> desde menu : capturo form
    else if( $dat.nodeName && $dat.nodeName == 'A' ){

      $App.dom.dat.var = $dat.parentElement.nextElementSibling.querySelector(`.ide-${$tip}`);
    }
    
    switch( $tip ){
    // peticiones
    case 'aja':
      $.lis = $App.dom.dat.var.querySelector(`nav.lis`);
      dom.eli($.lis);
      $App.log.php.forEach( $log => {
        $.ver = document.createElement('a'); 
        $.ver.href = $log;
        $.ver.innerHTML = Tex.let($log); 
        $.ver.target = '_blank'; 
        $.lis.appendChild($.ver);
      });
      break;
    // iconos
    case 'ico':
      $.lis = $App.dom.dat.var.querySelector(`ul.lis`);
      if( !$val ){
        // limpio listado
        dom.eli($.lis);
        for( let $ico in ( $._ico = Fig._('ico') ) ){ 
          $ico = $._ico[$ico];
          $.ico = document.createElement('span');
          $.ico.classList.add('fig_ico');
          $.ico.classList.add($ico.ide);
          $.ico.classList.add('mar_der-1');
          $.ico.innerHTML = $ico.val;
          
          $.nom = document.createElement('p');
          $.nom.innerHTML = `<c>-</c> <b class='ide'>${$ico.ide}</b> <c>=</c> ${$ico.val}`;

          $.ite = document.createElement('li');
          for( const $pad in $.ele = { 'ite':['ico','nom'], 'lis':['ite'] } ){
            $.ele[$pad].forEach( $e => $[$pad].appendChild($[$e]) );
          }
          $.lis.appendChild($.ite);        
        }
      }
      else{
        if( !$dat.value ){
          Lis.val_cod($.lis.children).forEach( $e => 
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU) 
          );
        }
        else{
          Lis.val_cod($.lis.children).forEach( $e => {

            if( Dat.ope_ver( $e.querySelector('.ide').innerHTML, '^^', $dat.value ) ){
              $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU);
            }
            else if( !$e.classList.contains(DIS_OCU) ){
              $e.classList.add(DIS_OCU);
            }
          });
        }
      }
      break;
    // base de datos
    case 'sql':
      $.cod = $App.dom.dat.var.querySelector('[name="cod"]').value;
      if( $.cod ){

        Eje.val( ['sis_sql::dec', [ $.cod ] ], $res => {
          // pido tabla
          if( Array.isArray($res) ){

            $.res.appendChild( app_est.lis($res) );
          }// errores: html
          else if( typeof($res)=='object' ){

            $.res.innerHTML = $res._err;
          }// literales: mensajes
          else{                        
            $.htm = document.createElement('p');
            $.htm.classList.add('sql');
            $.htm.innerHTML = Tex.let($res);
            $.res.appendChild($.htm);
          }  
        });
      }
      break;
    // servidor
    case 'php':    
      $.val = $App.dom.dat.var.querySelector('pre.ope_res');
      $.val.classList.add(DIS_OCU);
      $.val.innerText = '';
      $.res.classList.add(DIS_OCU);
      $.htm = $App.dom.dat.var.querySelector('[name="htm"]').checked;
      if( $.ide = $App.dom.dat.var.querySelector('[name="ide"]').value ){
        Eje.val([ $.ide, eval(`[${$App.dom.dat.var.querySelector('[name="par"]').value}]`) ], $res => {
          if( $.htm ){
            $.res.innerHTML = $res;
            $.res.classList.remove(DIS_OCU);
          }else{
            $.val.innerText = $res;
            $.val.classList.remove(DIS_OCU);
          }
        });
      }
      break;
    // terminal
    case 'jso':
      $.cod = $App.dom.dat.var.querySelector('[name="cod"]');

      try{

        $.val = eval($.cod.value);

        $.dat_tip = Dat.tip($.val);

        if( $.dat_tip.dat == 'obj' ){

          $.res.appendChild( app_var.obj('val',$.val) );
        }
        else if( $.dat_tip.dat == 'eje' ){

          $.res.innerHTML = Tex.let( $.val.toString() );
        }
        else if( ![undefined,NaN,null,true,false].includes($.val) ){

          $.res.innerHTML = Tex.let( $.val );
        }
        else{

          $.res.innerHTML = `<c>${$.val}</c>`;
        }
      }
      catch( $err ){
        $.err = document.createElement('b');
        $.err.classList.add('err');
        $.err.innerHTML = $err;
        $.res.appendChild($.err);
      }
      break;
    // documento
    case 'htm':
      switch( $val ){
      // consulta por query
      case 'val':

        $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) );

        $dat.nextElementSibling.classList.add(FON_SEL);

        $.res = $App.dom.dat.var.querySelector('div.ele');

        dom.eli($.res);

        $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');

        $.res.innerHTML = Ele.var('eti',document.querySelector($.ver));

        break;
      // Listado de elementos resultante
      case 'cod':
        $.res = $App.dom.dat.var.querySelector('div.ele_nod');          

        dom.eli($App.dom.dat.var.querySelector('div.ele'));

        dom.eli($.res);

        $.cod = $App.dom.dat.var.querySelector('[name="cod"]');

        $.val = document.querySelectorAll($.cod.value);

        $.tit = document.createElement('h4');
        $.tit.innerHTML = 'Listado';
        $.tex = document.createElement('p');
        $.tex.innerHTML = Tex.let(`Total: ${$.val.length}`);        
        $.res.appendChild($.tit);
        $.res.appendChild($.tex);
        // genero elemento
        $.res.appendChild( Ele.var_val($.val)[1] );
        break;
      }
      break;        
    }

    return $;
  }

}