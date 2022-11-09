// Aplicacion
'use strict';

class app {
  
  // peticion
  uri = {};
  // página
  ope = {
    bot : 'body > .app_bot',// botonera
    win : 'body > .app_win',// pantalla modal
    pan : 'body > .app_pan',// panel
    sec : 'body > .app_sec',// principal
    bar : 'body > .app_bar',// lateral
    pie : 'body > .app_pie',// barra inferior
    var : null// Formulario,

  };// Valores
  ope_val = {
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
    lis : `main > article > .app_tab`,
    // Valores
    val : {
      acu : `aside.app_pan > .ide-val .ide-acu`,      
      sum : `aside.app_pan > .ide-val .ide-sum`,
      cue : `aside.app_pan > .ide-val .ide-cue`
    },
    // Seleccion
    ver : `aside.app_pan > .ide-ver`,
    // Opciones : seccion + posicion + ...atributos
    opc : {
      sec : `aside.app_pan > .ide-opc .ide-sec`,    
      pos : `aside.app_pan > .ide-opc .ide-pos`,
      atr : `aside.app_pan > .ide-opc .ide-atr`
    }
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
        }
        // operadores
        else if( $.ope = document.querySelector(`nav.ope ~ div > section[class*="ide-"]:not(.${DIS_OCU})`) ){
          $.nav = $.ope.parentElement.previousElementSibling;
          if( $.ico = $.nav.querySelector(`.ico.fon-sel`) ) $.ico.click();
        }
        // pantallas
        else if( document.querySelector(`.app_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $.art = $_app.ope.win.children;          
          for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
            const $art = $.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              app.win( $art.querySelector(`header:first-child .ico[data-ope="fin"]`) );
              break;
            }
          }
        }// navegacion
        else if( document.querySelector(`.app_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
          app.pan();
        }
        break;
      }
    };
    // anulo formularios
    document.querySelectorAll('form').forEach( 
      $ele => api_ele.ope( $ele,'eje_agr','submit',`evt => evt.preventDefault()`) 
    );

    // peticion
    this.uri = !! $_api.app_uri ? $_api.app_uri : {};    
    // cargo operadores
    for( const $ide in this.ope ){ this.ope[$ide] = document.querySelector(this.ope[$ide]); }
    // - de operadores
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
    if( !$_app.uri.cab ){       
      ( $.bot_ini = $_app.ope.bot.querySelector('.ico.app_cab') ) && $.bot_ini.click();
    }
    // articulo
    else{
      // expando menu seleccionado      
      if( $.cab = $_app.ope.pan.querySelector(`nav.ide-app_cab p.ide-${$_app.uri.cab}`) ) $.cab.click();
      // inicializo operadores  : sec + pos + atr
      if( $_app.uri.cab == 'ope' ){
        if( $.cla_app = eval($.cla = `${$_app.uri.esq}`) ){
          app_tab.ini();
          app_est.ini();
          // secciones y posiciones
          ['sec'].forEach( $ope => {
            if( $_app.tab.opc[$ope] ){
              $.eje = `tab_${$ope}`;
              $_app.tab.opc[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$.cla}.${$.eje}"]`).forEach(
                $inp => $.cla_app[$.eje] && $.cla_app[$.eje]( $inp )
              );
            }
          });
          // + atributos
          if( $_app.tab.opc.atr ){  
            $_app.tab.opc.atr.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
              
              $.eje = `tab_${$for.classList[0].split('-')[2]}`;

              $for.querySelectorAll(`[name][onchange*="${$.cla}.${$.eje}"]`).forEach( $inp => {

                if( !!$.cla_app[$.eje] ) $.cla_app[$.eje]( $inp );
              });
            });
          }
        }
      }
      // inicializo indice por artículo
      else if( $_app.uri.art && ( $.art_nav = $_app.ope.pan.querySelector('nav.ide-app_nav ul.lis.nav') ) ){
        // inicio indice
        app.art_tog($.art_nav);        
        // muestro panel
        app.pan('app_nav');
      }
    }
  }

  // devuelvo enlace desde url
  static uri( ...$opc ) {

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
  // datos : nombre, descripcion, titulo, imagen, color...
  static dat( $esq, $est, $ope, $dat ) {

    let $={}, $_ = $_api.app_dat[$esq][$est];

    // cargo atributo
    $.ope_atr = $ope.split('.');
    $.ope_atr.forEach( $ide => {
      $_ = ( typeof($_) == 'object' && !!($_[$ide]) ) ? $_[$ide] : false;
    });

    // proceso valores con datos
    if( $_ && $.ope_atr[0] == 'val' && !!($dat) ) $_ = api_obj.val( api.dat($esq,$est,$dat), $_ );

    return $_;
  }

  // letras : c - n
  static let( $dat, $ele={} ){

    let $_="", $pal, $_pal=[], $let=[], $_let = $_api.tex_let, $num = 0;
    
    if( $dat !== null && $dat !== undefined && $dat !== NaN ){      

      $dat.toString().split(' ').forEach( $pal => {
        
        $num = api_num.val($pal);
        if( !!$num || $num == 0 ){
          // if( /,/.test($pal) ) $pal.replaceAll(",","<c>,</c>");
          // if( /\./.test($pal) ) $pal.replaceAll(".","<c>.</c>");
          $_pal.push( `<n>${$pal}</n>` );
        }
        else{
          $let = [];
          $pal.split('').forEach( $car =>{
            $num = api_num.val($car);
            if( !!$num || $num == 0 ){
              $let.push( `<n>${$car}</n>` );
            }
            else if( $_let[$car] ){
              // ${api_ele.atr(api_ele.cla($ele,`${$_let[$car].cla}`,'ini'))}
              $let.push( `<c>${$car}</c>` );
            }
            else{
              $let.push( $car );
            }
          });
          $_pal.push( $let.join('') );
        }
      });
      $_ = $_pal.join(' ');
    }
    return $_;
  }
  // iconos : .app_ico.$ide
  static ico( $ide, $ele = {} ){

    let $_="<span class='ico'></span>", $={ _ico : $_api.app_ico };

    if( !!($._ico[$ide]) ){
      $.eti = 'span';
      if( $ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }      
      if( $.eti == 'button' && !($ele['type']) ) $ele['type'] = "button"; 
      $_ = `<${$.eti}${api_ele.atr(api_ele.cla($ele,`ico ${$ide} material-icons-outlined`,'ini'))}>${$._ico[$ide].val}</${$.eti}>`;
    }
    return $_;
  }
  // imagen : .app_ima.$ide
  static ima( ...$dat ){  

    let $_="", $={}, $ele={};
    
    if( $dat[2] !== undefined ){
      $.ele = !!($dat[3]) ? $dat[3] : {};      
      $_ = app_dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $.ele);
    }
    else{
      $ele = !!$dat[1] ? $dat[1] : {};
      $.tip = typeof($dat = $dat[0]);
      // por estilos : bkg
      if( $.tip == 'object' ){
        $ele = api_ele.jun( $dat, $ele );
      }
      // por directorio : localhost/_/esq/ima/...
      else if( $.tip == 'string' ){    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `img/${$dat}`;
        api_ele.css( $ele, api_ele.fon($.dir,{'tip':$.tip}) );
      }
      // etiqueta
      $.eti = 'span';
      if( !!$ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }// codifico botones
      if( $.eti == 'button' && !$ele['type'] ) $ele['type'] = "button";
      // aseguro identificador
      api_ele.cla($ele,`ima`,'ini');
      // contenido 
      $.htm = '';
      if( !!($ele['htm']) ){
        api_ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
        delete($ele['htm']);
      }
      $_ = `<${$.eti}${api_ele.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }

  // pantalla : .app_win > article > header + section
  static win( $ide, $ope ){
    let $ = {};    
    // botones
    if( $ide.nodeName && $ide.classList.contains('ico') ){
      // cierro pantalla
      $.art = $ide.parentElement.parentElement.parentElement;
      if( $ide.dataset.ope == 'fin' || $ide.dataset.ope == 'pre' ){        
        $.art.classList.add(DIS_OCU);
        // si es una pantalla por posicion, elimino el articulo
        if( $.art.dataset.pos ){
          if( $ide.dataset.ope == 'pre' ){
            $.art.parentElement.removeChild($.art);
          }else if( $ide.dataset.ope == 'fin' ){
            $.art.parentElement.querySelectorAll(`article.${$.art.classList[0]}[data-pos]`).forEach( $art => {
              $art.parentElement.removeChild($art);
            });
          }
        }
      }
    }// articulos
    else{
      $.art = typeof($ide) == 'string' ?  $_app.ope.win.querySelector(`article.ide-${$ide}`) : $ide;
      $.htm = $.art.querySelector(`div:nth-child(2)`);
      // actualizo contenido
      if( !!$ope ){
        $.opc = $ope.opc ? api_lis.ite($ope.opc) : [];
        // creo nueva ventana con mismo identificador
        if( $.opc.includes('pos') ){
          $_app.ope.win.appendChild( $.art = $.art.cloneNode(true) );
          $.art.dataset.pos = $_app.ope.win.querySelectorAll(`article[data-pos]`).length + 1;
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){
            $.ope = $.art.querySelector('header:first-child > .ope');
            $.ope.insertBefore( api_ele.cod(app.ico('val_mov-izq',{ 
              'title': "Volver a la pantalla anterior", 'data-ope':"pre", 'onclick':"app.win(this)" 
            })), $.ope.querySelector('.ico.dat_fin') );
          }
          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }
        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .ico:first-of-type`) ){
          $.ico.innerHTML = '';
          if( $ope.ico !== undefined ){
            if( typeof($ope.ico) == 'string' ) $ope.ico = app.ico($ope.ico,{ class:'mar_hor-1' });
            api_ele.mod($ope.ico,$.ico);
          }
        }
        // titulo
        if( $.tit = $.art.querySelector(`header:first-child > h2`) ){
          $.tit.innerHTML = ( $ope.cab !== undefined ) ? app.let($ope.cab) : '';
        }
        // contenido
        api_ele.eli($.htm);
        if( $ope.htm !== undefined ) api_ele.agr($ope.htm,$.htm);
      }
      // muestro por valor
      $.art.classList.contains(DIS_OCU) && $.art.classList.remove(DIS_OCU);
      $.htm.scroll(0,0);// scroll
    }
    // pantalla de fondo
    if( $_app.ope.win.querySelector(`article[class*="ide-"]:not(.${DIS_OCU})`) ){
      $_app.ope.win.classList.contains(DIS_OCU) && $_app.ope.win.classList.remove(DIS_OCU);
    }
    else if( !$_app.ope.win.classList.contains(DIS_OCU) ){
      $_app.ope.win.classList.add(DIS_OCU);
    }    
  }
  // navegadores : aside.app_pan
  static pan( $ide ){

    if( $ide && $ide.nodeName ) $ide = $ide.classList[0].split('-')[1];

    $_app.ope.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$ide}, .${DIS_OCU} )`).forEach( $e => $e.classList.add(DIS_OCU) );
    $_app.ope.pan.querySelectorAll(`:is(nav,article).ide-${$ide}`).forEach( $e => $e.classList.toggle(DIS_OCU) );

    // aculto-muestro contenedor
    if( $_app.ope.pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.${DIS_OCU})`) ){
      $_app.ope.pan.classList.contains(DIS_OCU) && $_app.ope.pan.classList.remove(DIS_OCU);
     // $_app.ope.sec.style.gridColumn = "2/sp";
    }
    else if( !$_app.ope.pan.classList.contains(DIS_OCU) ){
      $_app.ope.pan.classList.add(DIS_OCU);
      //$_app.ope.sec.style.gridColumn = "1/sp";
    }    
  }
  // seccion : main.app_sec > article
  static sec( $ide ){
    $_app.ope.sec.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $_app.ope.sec.querySelectorAll(`article.ide-${$ide}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $_app.ope.sec.scroll(0, 0);
  }
  // Contenido : bloque + visible/oculto  
  static val( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){
      $.ite = $dat.parentElement;
      if( 
        ( $.bot = $.ite.querySelector('.ico.val_tog') ) 
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

      if( $_app.ope.var = api_ele.ver($dat,{'eti':"form"}) ){        

        $.lis = !!$_app.ope.var.nextElementSibling ? $_app.ope.var.nextElementSibling : $_app.ope.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.val > .ico.val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }
  // campos : form > fieldsets
  static atr( $ide ){
    api_lis.val( api_ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }  
  // .atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};
    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $_app.ope.var = api_ele.ver($dat,{'eti':'form'});
      $.var_ide = $dat.getAttribute('name');
    }else{
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
  }
  
  // contenido : pestaña-barra-operador
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
      api_lis.val( $.lis.children ).forEach( $e => {
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
  // indice 
  // - click en item
  static art_val( $dat, $cla = FON_SEL ){

    let $ = { lis : api_ele.ver($dat,{'eti':'nav'}) };

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
        app.val($dat);
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
        ( $.lis = api_ele.ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.getAttribute('ico') ){                
          app.val($.ico);
        }                
      }
    }
  }// - marco valor seleccionado
  static art_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      app.art_val($nav);
    }

    return $nav;
  }// - ejecuto filtros
  static art_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    app_lis.val_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    app.art_tog($_app.ope.var.nextElementSibling);

  }

}
