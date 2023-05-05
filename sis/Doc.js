'use strict';

class Doc {

  // Sección Principal
  static sec( $ide ){
    $App.Dom.doc.sec.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $App.Dom.doc.sec.querySelectorAll(`article.ide-${$ide}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $App.Dom.doc.sec.scroll(0, 0);
  }  

  // Panel
  static pan( $ide ){

    let $ = { 'ide':"", 'cla': ['bor-sel','fon-sel'] };
    
    // oculto todo
    if( !$ide ){

      Ele.act('cla_agr',$App.Dom.doc.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]`),DIS_OCU);
    }
    // muestro uno articulo/navegador
    else{
      // Enlace
      Ele.act('cla_eli',$App.Dom.doc.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      if( typeof($ide) == 'string' ) $ide = $App.Dom.doc.bot.querySelector(`a[data-ide="${$ide}"]`);
      Ele.act('cla_agr',$ide,$.cla);
      
      // Contenedor
      $.ide = $ide.dataset.ide;
      Ele.act('cla_agr',$App.Dom.doc.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$.ide}, .${DIS_OCU} )`),DIS_OCU);
      Ele.act('cla_tog',$App.Dom.doc.pan.querySelectorAll(`:is(nav,article).ide-${$.ide}`),DIS_OCU);
    }

    // Panel
    if( $App.Dom.doc.pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.${DIS_OCU})`) ){      
      Ele.act('cla_eli',$App.Dom.doc.pan,DIS_OCU);
    }
    else{
      Ele.act('cla_eli',$App.Dom.doc.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      Ele.act('cla_agr',$App.Dom.doc.pan,DIS_OCU);
    }    
  }

  // Modal
  static win( $ide, $ope ){
    let $ = {};    
    
    // botones
    if( $ide.nodeName && $ide.classList.contains('fig_ico') ){
      // cierro pantalla
      $.art = $ide.parentElement.parentElement.parentElement;
      if( $ide.dataset.ope == 'fin' || $ide.dataset.ope == 'pre' ){
        $.art.classList.add(DIS_OCU);
        // si es una pantalla por posicion, elimino el articulo
        if( $.art.dataset.pos ){
          if( $ide.dataset.ope == 'pre' ){
            $.art.parentElement.removeChild($.art);
          }
          else if( $ide.dataset.ope == 'fin' ){
            $.art.parentElement.querySelectorAll(`article.${$.art.classList[0]}[data-pos]`).forEach( $art => {
              $art.parentElement.removeChild($art);
            });
          }
        }
      }
    }
    // articulos
    else{
      $.art = typeof($ide) == 'string' ?  $App.Dom.doc.win.querySelector(`article.ide-${$ide}`) : $ide;
      $.htm = $.art.querySelector(`div:nth-child(2)`);
      // actualizo contenido
      if( !!$ope ){
        $.opc = $ope.opc ? Lis.val_ite($ope.opc) : [];
        // creo nueva ventana con mismo identificador
        if( $.opc.includes('pos') ){
          $App.Dom.doc.win.appendChild( $.art = $.art.cloneNode(true) );
          $.art.dataset.pos = $App.Dom.doc.win.querySelectorAll(`article[data-pos]`).length + 1;
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){
            $.ope = $.art.querySelector('header:first-child > .doc_bot');
            $.ope.insertBefore( dom.val( Fig.ico('val_mov-izq',{ 
              'title': "Volver a la pantalla anterior", 'data-ope':"pre", 'onclick':"Doc.win(this)" 
            })), $.ope.querySelector('.fig_ico.ide-dat_fin') );
          }
          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }
        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .fig_ico:first-of-type`) ){
          $.ico.innerHTML = '';
          if( $ope.ico !== undefined ){
            if( typeof($ope.ico) == 'string' ) $ope.ico = Fig.ico($ope.ico,{ class:'mar_hor-1' });
            dom.mod($ope.ico,$.ico);
          }
        }
        // titulo
        if( $.tit = $.art.querySelector(`header:first-child > h2`) ){
          $.tit.innerHTML = ( $ope.cab !== undefined ) ? Tex.let($ope.cab) : '';
        }
        // contenido
        dom.eli($.htm);
        if( $ope.htm !== undefined ) dom.agr($ope.htm,$.htm);
      }
      // muestro por valor
      $.art.classList.contains(DIS_OCU) && $.art.classList.remove(DIS_OCU);
      $.htm.scroll(0,0);// scroll
    }

    // pantalla de fondo
    if( $App.Dom.doc.win.querySelector(`article[class*="ide-"]:not(.${DIS_OCU})`) ){
      $App.Dom.doc.win.classList.contains(DIS_OCU) && $App.Dom.doc.win.classList.remove(DIS_OCU);
    }
    else if( !$App.Dom.doc.win.classList.contains(DIS_OCU) ){
      $App.Dom.doc.win.classList.add(DIS_OCU);
    }    
  }  

  /* Contenedor : bloque + visible/oculto */
  static val( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){
      $.ite = $dat.parentElement;
      if( 
        ( $.bot = $.ite.querySelector('.fig_ico.ide-val_tog') ) 
        && ( $.sec = $.ite.nextElementSibling )
      ){        
      
        if( $.bot.classList.contains('ocu') ){
          $.bot.classList.remove('ocu');
          $.sec.classList.remove(DIS_OCU);
        }
        else{
          $.bot.classList.add('ocu');
          $.sec.classList.add(DIS_OCU);
        }
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $App.Dom.dat.var = dom.ver($dat,{'eti':"form"}) ){

        $.lis = !!$App.Dom.dat.var.nextElementSibling ? $App.Dom.dat.var.nextElementSibling : $App.Dom.dat.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.doc_val > .fig_ico.ide-val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  // Navegacion de Contenido : pestaña-barra-operador
  static nav( $dat, $ope, ...$opc ){
    let $={};
    // capturo contenedor
    $.nav = $dat.parentElement;
    $.tip = $.nav.classList[0];
    $.tip_ver = false;
    // capturo contenido
    $.lis = $.nav.nextElementSibling;
    // con toggles
    $.val_tog = $opc.includes('tog');
    // elimino fondo por seleccion anterior
    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){
      // recorro items
      Lis.val_cod( $.lis.children ).forEach( $e => {
        // coincide con el seleccionado
        if( $e.classList.contains(`ide-${$ope}`) ){          
          // hago toogles
          if( $.val_tog ){
            $e.classList.toggle(DIS_OCU);
            $dat.classList.toggle(FON_SEL);
          }
          // muestro y selecciono
          else if( $e.classList.contains(DIS_OCU) ){
            $e.classList.remove(DIS_OCU);
            $dat.classList.add(FON_SEL);
          }
          $.tip_ver = !$e.classList.contains(DIS_OCU);

        }// oculto los no coincidentes
        else if( !$e.classList.contains(DIS_OCU) ){ 
          $e.classList.add(DIS_OCU); 
        }
      });
    }
    // oculto o muestro contenedor
    if( $.tip != 'pes' ){
      if( $.tip_ver ){
        $.lis.classList.contains(DIS_OCU) && $.lis.classList.remove(DIS_OCU);
      }else{
        !$.lis.classList.contains(DIS_OCU) && $.lis.classList.add(DIS_OCU);
      }
    }  
  }

}