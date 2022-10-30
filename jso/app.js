// WINDOW
'use strict';

// Aplicacion
class _app {

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
    var : null// Formulario
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
        }// pantallas
        else if( document.querySelector(`.app_win:not(.${DIS_OCU}) article header .ico.dat_fin`) ){ 
          _app_ope.win();
        }// navegacion
        else if( document.querySelector(`.app_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
          _app_ope.pan();
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
    for( const $ide in this.ope ){ this.ope[$ide] = document.querySelector(this.ope[$ide]); }
    // - de tableros
    if( this.uri.cab == 'tab' ){
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
      if( $.cab = $_app.ope.pan.querySelector(`nav.ide-doc_cab p.ide-${$_app.uri.cab}`) ) $.cab.click();
      // inicializo tableros  : sec + pos + atr
      if( $_app.uri.cab == 'tab' ){
        if( $.cla_app = eval($.cla = `_${$_app.uri.esq}_tab`) ){
          _app_tab.ini();
          _app_est.ini();
          // secciones y posiciones
          ['sec'].forEach( $ope => {

            if( $_app.tab.opc[$ope] ){
              $.eje = `_${$ope}`;
              $_app.tab.opc[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$.cla}.${$.eje}"]`).forEach(
                $inp => $.cla_app[$.eje] && $.cla_app[$.eje]( $inp )
              );
            }
          });
          // + atributos
          if( $_app.tab.opc.atr ){
  
            $_app.tab.opc.atr.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
              
              $.eje = `_${$for.classList[0].split('-')[2]}`;
              
              $for.querySelectorAll(`[name][onchange*="${$.cla}.${$.eje}"]`).forEach( $inp => {

                if( !!$.cla_app[$.eje] ) $.cla_app[$.eje]( $inp );
              });
            });
          }
        }
      }
      // inicializo indice por artículo
      else if( $_app.uri.art && ( $.art_nav = $_app.ope.pan.querySelector('nav.ide-doc_nav ul.lis.nav') ) ){
        // inicio indice
        _app_nav.art_tog($.art_nav);
        
        // muestro panel
        _app_ope.pan('doc_nav');
      }
    }
  }
  
  // letras : c - n
  static let( $dat, $ele={} ){

    let $_="", $pal, $_pal=[], $let=[], $_let = $_api.tex_let, $num = 0;
    
    if( $dat !== null && $dat !== undefined && $dat !== NaN ){      

      $dat.toString().split(' ').forEach( $pal => {
        
        $num = _num.val($pal);
        if( !!$num || $num == 0 ){
          // if( /,/.test($pal) ) $pal.replaceAll(",","<c>,</c>");
          // if( /\./.test($pal) ) $pal.replaceAll(".","<c>.</c>");
          $_pal.push( `<n>${$pal}</n>` );
        }
        else{
          $let = [];
          $pal.split('').forEach( $car =>{
            $num = _num.val($car);
            if( !!$num || $num == 0 ){
              $let.push( `<n>${$car}</n>` );
            }
            else if( $_let[$car] ){
              // ${_htm.atr(_ele.cla($ele,`${$_let[$car].cla}`,'ini'))}
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

    let $_="", $={ _ico : $_api.app_ico };

    if( !!($._ico[$ide]) ){
      $ele['eti'] = !!($ele['eti']) ? $ele['eti'] : 'span';
      if( $ele['eti'] == 'button' ){
        if( !($ele['type']) ) $ele['type'] = "button";
      }
      $ele['ide'] = $ide;
      $htm = $._ico[$ide].val;
      $_ = `
      <${$ele['eti']}${_htm.atr(_ele.cla($ele,"ico material-icons-outlined",'ini'))}>
        ${$htm}
      </${$ele['eti']}>`;
    }
    return $_;
  }
  // imagen : .app_ima.$ide
  static ima( ...$dat ){  

    let $_="", $={}, $ele={};
    
    if( $dat[2] !== undefined ){

      // if( /_ide$/.test($dat[1]) ){ $dat[1] = $dat[1].replace(/_ide$/,''); }

      $.ele = !!($dat[3]) ? $dat[3] : {};

      $.ele['ima'] = `${$dat[0]}.${$dat[1]}`;

      $_ = _app_dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $.ele);
    }
    else{

      $ele = !!$dat[1] ? $dat[1] : {};
      $.tip = typeof($dat = $dat[0]);      
      $.fic_ide = '';
      
      // por estilos : bkg
      if( $.tip == 'object' ){

        $ele = _ele.jun( $dat, $ele );   
        if( $ele['ima']){ 
          $.fic_ide = $ele['ima'];
          delete($ele['ima']);
        }
      }
      // por directorio : localhost/_/esq/ima/...
      else if( $.tip == 'string' ){
    
        $.ima = $dat.split('.');

        $dat = $.ima[0];

        $.tip = !!$.ima[1] ? $.ima[1] : 'png';

        $.dir = `img/${$dat}`;

        $.fic_ide = $.dir+"."+$.tip;

        _ele.css( $ele, _ele.fon($.dir,{'tip':$.tip}) );
      }
    
      // etiqueta
      $.eti = !!($ele['eti']) ? $ele['eti'] : 'span';
      // codifico botones
      if( $.eti == 'button' && !$ele['type'] ) $ele['type'] = "button";      
      // aseguro identificador
      _ele.cla($ele,`ima${ $.fic_ide ? " "+$.fic_ide.replaceAll('.','_') : "" }`);
      // contenido 
      $.htm = '';
      if( !!($ele['htm']) ){
        _ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
      }
      
      $_ = `<${$.eti}${_htm.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
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
    if( $_ && $.ope_atr[0] == 'val' && !!($dat) ) $_ = _obj.val( _dat.get($esq,$est,$dat), $_ );

    return $_;
  }

}
// Peticion
class _app_uri {
  
  // devuelvo enlace desde url
  static val( ...$opc ) {

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
}

// Página
class _app_ope {

  // pantalla : .app_win > article > header + section
  static win( $ide ){
    // muestro articulo
    if( $ide ){
      // muestro fondo
      $_app.ope.win.classList.remove(DIS_OCU);
      // oculto articulos  
      $_app.ope.win.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );        
      // muestro por valor
      if( typeof($ide) == 'string' ) $ide=$_app.ope.win.querySelector(`article.ide-${$ide}.${DIS_OCU}`);

      if( $ide ) $ide.classList.remove(DIS_OCU); 
    }// oculto articulos
    else{        
      $_app.ope.win.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // oculto fondo
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
  // campos : form > fieldsets
  static var( $ide ){
    _lis.val( _ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }
  // bloques 
  static tog( $dat, $ope ){
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

      if( $_app.ope.var = _ele.ver($dat,{'eti':"form"}) ){        

        $.lis = !!$_app.ope.var.nextElementSibling ? $_app.ope.var.nextElementSibling : $_app.ope.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.val > .ico.val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }
}
// Navegacion
class _app_nav {
  
  // indice 
  static art(){      
  }// click en item
  static art_val( $dat, $cla = FON_SEL ){

    let $ = { lis : _ele.ver($dat,{'eti':'nav'}) };

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
        _app_ope.tog($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }// hago toogle por item
  static art_tog( $lis ){

    let $={};

    if( $.nav = $lis ? _app_nav.art_mar($lis) : false ){
      // hago toogles ascendentes
      while( 
        ( $.lis = _ele.ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.getAttribute('ico') ){                
          _app_ope.tog($.ico);
        }                
      }
    }
  }// marco valor seleccionado
  static art_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      _app_nav.art_val($nav);
    }

    return $nav;
  }// ejecuto filtros
  static art_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    _app_lis.val_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    _app_nav.art_tog($_app.ope.var.nextElementSibling);

  }
  
  // contenido : pestaña-barra-operador
  static sec( $dat, $ope, ...$opc ){
    let $={};
    // capturo contenedor
    $.nav = $dat.parentElement;
    // con toggles
    $.val_tog = $opc.includes('tog');
    // seleccion anterior
    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){
      // capturo contenido
      $.lis = $.nav.nextElementSibling;
      // recorro items
      _lis.val( $.lis.children ).forEach( $e => {
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
        }// oculto los no coincidentes
        else if( !$e.classList.contains(DIS_OCU) ){ 

          $e.classList.add(DIS_OCU); 
        }
      });
    }      
  }  
}
// Dato
class _app_dat {

  // .atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};
    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $_app.ope.var = _ele.ver($dat,{'eti':'form'});
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
  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = _dat.ide($dat,$);
    // cargo datos
    $.dat_var = _dat.get($.esq,$.est,$ope);
    // cargo valores
    $.dat_val = _app.dat($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = _obj.val($.dat_var,$.dat_val.nom) + ( $.dat_val.des ? "\n"+_obj.val($.dat_var,$.dat_val.des) : '' );
    }
    else if( !!($.dat_val[$tip]) ){
      $_ = _obj.val($.dat_var,$.dat_val[$tip]);  
    }
    // armo ficha
    if( $tip == 'ima' ){

      $.ele = !!$opc[0] ? $opc[0] : {};

      if( $.ele.title === undefined ){

        $.ele.title = _app_dat.val('tit',`${$.esq}.${$.est}`,$.dat_var);
      }
      else if( $.ele.title === false ){

        delete($.ele.title);
      }        
      $_ = _app.ima( { 'style': $_ }, $.ele );
    }
    else if( !!$opc[0] ){
      
      if( !($opc[0]['eti']) ) $opc[0]['eti'] = 'p'; 
      $opc[0]['htm'] = _app.let($_);
      $_ = _htm.val($opc[0]);
    }

    return $_;
  }
  // opciones : esquema.estructura.atributos.valores
  static opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_app_dat.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide=["val"] ) => {
      $ide.forEach( $i => { if( $.ope = $_app.ope.var.querySelector(`[name="${$i}"]`) ) _ele.eli( $.ope, `option:not([value=""])` ); });
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
          _eje.val(['_dat::get', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = _app_opc.val( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }
  // ficha : imagenes por valor con relaciones por estructura
  static fic( $dat, $ope, ...$opc ){
    let $_="", $=_app_dat.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.atr`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? _num.ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.atr [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      _ele.eli($ite,'.ima');
      if( $.val = $.dat[$.est] ){
        _ele.agr( _app.ima( $.ima[0], $.ima[1], _dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }
}

// Valor
class _app_val {

  // alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $ = _app_dat.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $_app.ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $_app.ope.var.querySelectorAll(`.atr > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $_app.ope.var.querySelectorAll(`.atr > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $_app.ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
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
            <li>${_app.let($e)}</li>`
            ); $._tex += `
          </ul>`;
          _ele.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $_app.ope.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$_app.ope.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $_app.ope.var.dataset.esq ) && ( $.est = $_app.ope.var.dataset.est ) ){
          _eje.val(['_doc.dat_val', [ $.esq, $.est, $tip, $._val ] ], $e => {            
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $_app.ope.var.reset();              
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
      $.acu_val[$ide] = $dat.querySelectorAll(`[class*="_val-${$ide}-"]`);

      // actualizo total del operador
      if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

        $.tot.innerHTML = $.acu_val[$ide].length;
      }
    });

    // calculo el total grupal    
    if( $.tot = $ope.querySelector(`[name="tot"]`) ){
      $.tot.innerHTML = $dat.querySelectorAll(`[class*=_val-]:is([class*="_bor"],[class*="_act"])`).length;
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
      $dat.forEach( $ite => $.sum += parseInt( $ite.dataset[`${$val.dataset.esq}_${$val.dataset.est}`] ) );

      _app_dat.fic( $val, $.sum);
    });
  }
  // filtros : dato + variables
  static ver( $tip, $dat, $ope, ...$opc ){

    let $ = _app_dat.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver-`;
    $.cla_ide=`${$.cla_val}_${$tip}`;
    
    _ele.act('cla_eli',$dat,[$.cla_val, $.cla_ide]);

    $_app.ope.var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'val' ){

      $.dat_est = $_app.ope.var.querySelector(`[name="est"]`);
      $.dat_ide=$_app.ope.var.querySelector(`[name="ver"]`);
      $.dat_val = $_app.ope.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = _dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = _dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) _ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $tip == 'pos' || $tip == 'fec' ){
      
      // elimino valor de dato por seleccion
      if( ( $.ver = $ope.querySelector(`form.ide-dat select[name="val"]`) ) && !!$.ver.value ) $.ver.value = '';
            
      // valores
      $.val = {};
      ['ini','fin','inc','lim'].forEach( $ide => {
        // capturo valores
        if( ( $.ite = $_app.ope.var.querySelector(`[name="${$ide}"]`) ) && !!$.ite.value ){

          $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? _num.val($.ite.value) : $.ite.value;
        }
      });
      
      // valido: si el inicio es mayor que el final
      if( $.val.ini && $.val.ini > $.val.fin ){

        $_app.ope.var.querySelector(`[name="ini"]`).value = $.val.ini = $.val.fin;
      }
      // si el final es mejor que el inicio
      if( $.val.fin && $.val.fin < $.val.ini ){

        $_app.ope.var.querySelector(`[name="fin"]`).value = $.val.fin = $.val.ini;
      }    
      // inicializo incremento
      $.inc_val = 1;
      if( ( !$.val.inc || $.val.inc <= 0 ) && ( $.ite = $_app.ope.var.querySelector(`[name="inc"]`) ) ){
        $.ite.value = $.val.inc = 1;
      }
      // inicializo limites desde
      if( !$.val.fin 
        && ( $.ite = $_app.ope.var.querySelector(`[name="fin"]`) ) && ( $.max = $.ite.getAttribute('max') ) 
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
          if( $.inc_val == 1 && _fec.ver( $e.dataset['fec_dat'], $.val.ini, $.val.fin ) ){

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
        if( $_app.ope.var.querySelector(`.ico.lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

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
    let $ = _app_dat.var($dat);

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

                if( ( $.dat_val = _dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
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

      $.ope = $_app.ope.var.querySelector('[name="ope"]').value;
      $.val = $_app.ope.var.querySelector('[name="val"]').value;
      $.lis = $_app.ope.var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll(`tr.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
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
}
// Tablero
class _app_tab {

  // tabla
  static lis( $dat, $ele, ...$opc ){
    let $ = {};

    // 1- cabecera
    $.tab_cab = document.createElement('thead');
    $.cab_lis = document.createElement('tr');

    if( $dat[0] ){
      for( const $atr in $dat[0] ){
        $.cab_ide = document.createElement('th');
        $.cab_ide.innerHTML = $atr;
        $.cab_lis.appendChild($.cab_ide);
      }
      $.tab_cab.appendChild($.cab_lis);
    }

    // 2-cuerpo
    $.tab_dat = document.createElement('tbody');
    $dat.forEach( $dat => {
      $.lis = document.createElement('tr');
      for( const $atr in $dat ){
        $.dat_ite = document.createElement('td');
        $.dat_ite.innerHTML = $dat[$atr];
        $.lis.appendChild($.dat_ite);
      }
      $.tab_dat.appendChild($.lis);
    });
    // 3-pie
    // ...

    $.tab = document.createElement('table');
    $.tab.appendChild($.tab_cab);
    $.tab.appendChild($.tab_dat);

    return $.tab;
  }
  // inicializo : opciones, posicion, filtros
  static ini(){

    let $={};      

    if( !$_app.tab.cla ){
      $_app.tab.cla = '.app_ope';
    }
    // inicializo opciones
    $_app.tab.ide = $_app.tab.lis.classList[1];
    ['sec','pos'].forEach( $ope => {
      
      if( $_app.tab.opc[$ope] ){
        $_app.tab.opc[$ope].querySelectorAll(
          `form[class*="ide-"] [onchange*="_app_tab."]:is( input:checked, select:not([value=""]) )`
        ).forEach( 
          $inp => _app_tab[`opc_${$ope}`]( $inp )
        );
      }
    });
    // marco posicion principal
    _app_tab.val('pos');

    // actualizo opciones
    $_app.val.acu.forEach( 
      $ite => ( $.ele = $_app.tab.val.acu.querySelector(`[name="${$ite}"]:checked`) ) && _app_tab.val_acu($.ele) 
    );

  }
  // actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $_app.val.acu : _lis.ite($dat);

    $.dat = $_app.tab.lis;

    // acumulados + listado
    if( $_app.tab.val.acu ){ 

      // actualizo toales acumulados
      _app_val.acu($_app.tab.lis, $_app.tab.val.acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $_app.tab.val.sum ){
        $.tot = [];
        $_app.val.acu.forEach( $acu_ide => {

          if( $_app.tab.val.acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$_app.tab.lis.querySelectorAll(`[class*="_val-${$acu_ide}-"]`) );
          }
        });
        _app_val.sum($.tot, $_app.tab.val.sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$_app.est.val.acu.querySelector(`[name="tod"]:checked`) ) _app_est.val_acu();

      // -> ejecuto filtros + actualizo totales
      if( $_app.est.ver ) _app_est.ver();
    }

    // fichas del tablero
    if( ( $_app.tab.opc.pos ) && ( $.ima = $_app.tab.opc.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $_app.tab.opc.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) _app_tab.opc_pos($.ima);
    }

    // actualizo cuentas
    if( $_app.tab.val.cue ){

      _app_val.cue('act', $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[class*=_val-]:is([class*=_bor],[class*=_act])`), $_app.tab.val.cue );
    }
  }
  // Datos
  static val( $tip, $dat ){

    let $ = _app_dat.var($dat);

    switch( $tip ){
    case 'pos': 
      if( $_hol_app && $_hol_app.val && ( $.kin = $_hol_app.val.kin ) ){

        $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[data-hol_kin="${$.kin}"]`).forEach( $e => {
          $e.classList.add(`_val-pos-`);
          if( $_app.tab.val.acu && $_app.tab.val.acu.querySelector(`[name="pos"]:checked`) ){
            $e.classList.add(`_val-pos_bor`);
          }
        });
      }
      break;
    case 'mar':
      $.pos = $dat.getAttribute('pos') ? $dat : $dat.parentElement;
      // si no es un posicion de tablero
      if( !$.pos.classList.contains('app_tab') ){

        $.pos.classList.toggle(`_val-mar-`);
        // marco bordes
        if( $_app.tab.val.acu ){
          if( $.pos.classList.contains(`_val-mar-`) && $_app.tab.val.acu.querySelector(`[name="mar"]:checked`) ){
            $.pos.classList.add(`_val-mar_bor`);
          }
          else if( !$.pos.classList.contains(`_val-mar-`) && $.pos.classList.contains(`_val-mar_bor`) ){
            $.pos.classList.remove(`_val-mar_bor`);
          }
        }
      }
      break;
    case 'ver':
      for( const $ide in $_app.val.ver ){

        if( $.ele = $_app.tab.ver.querySelector(`${$_app.val.ver[$ide]}:not([value=""])`) ){
  
          _app_tab.ver($ide,$.ele,$_app.tab.lis);

          break;
        }
      }
      break;
    case 'opc':
      // las 
      break;
    }
    // actualizo totales
    _app_tab.act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static val_acu( $dat, $ope ){
    
    let $ = _app_dat.var($dat);

    if( !$.var_ide && $ope ) $ = _app_dat.var( $dat = $_app.tab.val.acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $_app.tab.lis.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( _val.ver($cla,'^^',`${$.cla_ide}-`) ){
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
      _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.${$.cla_ide}_bor`),`${$.cla_ide}_bor`);
      if( $dat.checked ) _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.${$.cla_ide}`),`${$.cla_ide}_bor`);
    }
    // actualizo calculos + vistas( fichas + items )        
    _app_tab.act($.var_ide);

  }
  // Opciones
  static opc(){
  }// - secciones : bordes + colores + imagen + ...
  static opc_sec( $dat ){

    let $ = _app_dat.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$_app.tab.lis.classList.contains('bor-1') ){ $_app.tab.lis.classList.add('bor-1'); }
        $_app.tab.lis.querySelectorAll('.app_tab:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $_app.tab.lis.classList.contains('bor-1') ){ $_app.tab.lis.classList.remove('bor-1'); }
        $_app.tab.lis.querySelectorAll('.app_tab.bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $_app.tab.lis.querySelectorAll(`.app_tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $_app.tab.lis.classList.contains('fon-0') ){
          $_app.tab.lis.classList.remove('fon-0');
        }
      }else{
        // secciones
        $_app.tab.lis.querySelectorAll(`.app_tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
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
  }// - posiciones : borde + color + imagen + texto + numero + fecha
  static opc_pos( $dat ){

    let $ = _app_dat.var($dat); 
    
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
        $.eli = `${$_app.tab.cla} .pos:not(.app_tab)[class*='${$.ope}']`;
        $.agr = `${$_app.tab.cla} .pos:not(.app_tab)`;
      }
      else{
        $.eli = `${$_app.tab.cla}[class*='${$.ope}']`;
        $.agr = `${$_app.tab.cla}`;
      }

      $_app.tab.lis.querySelectorAll($.eli).forEach( $e => _ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $.col = _dat.opc('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        $.col = ( $.col && $.col.val ) ? $.col.val : 0;

        $_app.tab.lis.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = _dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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

        $e.querySelectorAll('.ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = _dat.ide($dat.value,$);
        
        // busco valores de ficha
        $.fic = _dat.opc('ima', ...( 
          ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value 
        ).split('.') );

        // actualizo por opciones        
        $_app.tab.lis.querySelectorAll($_app.tab.cla).forEach( $e => {
          $.htm = '';
          $.ele = { 'title' : false };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos-') ){ 
              $.htm = _dat.ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar-') ){ 
              $.htm = _dat.ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver-') ){ 
              $.htm = _dat.ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_val-opc-/.test($cla_nom) ) return $.htm = _dat.ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = _dat.ima($e,$);
          }
          if( $.htm ) _ele.mod($.htm,$e,'.ima','ini');
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

          if( $.obj = _dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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
  // Seleccion : datos, posicion, fecha
  static ver( $tip ){

    // ejecuto filtros por tipo : pos, fec      
    _app_val.ver($tip, _lis.val($_app.tab.lis.querySelectorAll(`${$_app.tab.cla}`)), $_app.tab.ver );

    // marco seleccionados
    _ele.act('cla_eli',$_app.tab.lis.querySelectorAll('._val-ver_bor'),'_val-ver_bor');
    if( $_app.tab.val.acu && $_app.tab.val.acu.querySelector(`[name="ver"]:checked`) ){
      _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`._val-ver-`),'_val-ver_bor');
    }

    // actualizo calculos + vistas( fichas + items )
    _app_tab.act('ver');
  }
}
// Estructura
class _app_est {
 
  // inicializo : acumulados
  static ini(){

    let $={};   

    if( $_app.est.val.acu ){

      if( $.ele = $_app.est.val.acu.querySelector(`[name="tod"]`) ){

        _app_est.val_tod($.ele);
      }
    }

  }
  // actualizo : acumulado + cuentas + descripciones
  static act(){

    let $={};
    // actualizo total
    if( $_app.est.val.acu && ( $.tot = $_app.est.val.acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = _app_est.val('tot');
    }    
    // actualizo cuentas
    if( $_app.est.val.cue ){

      _app_val.cue('act', $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $_app.est.val.cue);
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
      
        return $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        return 'err: no hay tabla relacionada';
      }      
      break;
    }
  }// - todos ? o por acumulados
  static val_tod( $dat ){

    let $ = _app_dat.var($dat);  
    
    if( $_app.est.val.acu ){
      // ajusto controles acumulados
      $_app.val.acu.forEach( $i => {

        if( $.val = $_app.est.val.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
      });
    }
    // ejecuto todos los filtros y actualizo totales
    _app_est.ver();

  }// - acumulados : posicion - marcas - seleccion
  static val_acu(){

    let $={};
    
    if( ( $.esq = $_app.est.lis.dataset.esq ) && ( $.est = $_app.est.lis.dataset.est ) ){
      
      // oculto todos los items de la tabla
      _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

      // actualizo por acumulado
      $_app.val.acu.forEach( $ide => {

        if( $.val = $_app.est.val.acu.querySelector(`[name='${$ide}']`) ){

          $.tot = 0;
          if( $.val.checked ){
            // recorro seleccionados
            $_app.tab.lis.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
              
              if( $.ele = $_app.est.lis.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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
  // filtros : Valores + Fecha + Posicion
  static ver( $tip, $dat, $ope ){

    let $ = _app_dat.var($dat);

    // ejecuto filtros
    if( !$tip ){

      // 1º - muestro todos
      if( !$_app.est.val.acu || $_app.est.val.acu.querySelector(`[name="tod"]:checked`) ){

        _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        _app_est.val_acu();
      }
      // 2º - cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $_app.val.ver ){
        // tomo solo los que tienen valor
        if( ( $.val = $_app.est.ver.querySelector(`${$_app.val.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){

        $.eje.forEach( $ope_ide => {

          _app_val.ver($ope_ide, _lis.val( $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $_app.est.ver);
          // oculto valores no seleccionados
          _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else{
      if( ['cic','gru'].includes($tip) ){
        // muestro todos los items
        _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
        
        // aplico filtro
        // ... 
      }
    }
    // actualizo total, cuentas y descripciones
    _app_est.act();
  }
  // columnas : toggles + atributos
  static atr(){
  }// - muestro-oculto
  static atr_tog( $dat ){

    let $ = _app_dat.var($dat);      

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
  }// - muestro-oculto
  static des_tog( $dat ){

    let $ = _app_dat.var($dat);
    $.ope  = $_app.ope.var.classList[0].split('-')[1];
    $.esq = $_app.ope.var.dataset.esq;
    $.est = $_app.ope.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    _ele.act('cla_agr',$_app.est.lis.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = _dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          _ele.act('cla_eli',$_app.est.lis.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static des_ver( $dat ){

    let $ = _app_dat.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $_app.est.lis.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $_app.est.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$_app.est.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $_app.est.lis.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $_app.est.lis.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
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

        $_app.est.lis.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $_app.est.lis.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
}
// Listado
class _app_lis {

  // punteos, numeraciones, y términos
  static ite(){
  }
  static ite_val( $dat, $ope ){
    let $ = _app_dat.var($dat);
    
    if( !$ope ){
      // toggles
      if( $dat.nodeName == 'DT' ){

        $.dd = $dat.nextElementSibling;

        while( $.dd && $.dd.nodeName == 'DD' ){
          $.dd.classList.toggle(DIS_OCU);
          $.dd = $.dd.nextElementSibling;
        }
      }
    }else{
      switch( $ope ){

      }
    }
  }
  static ite_tog( $dat, $ope ){

    let $ = _app_dat.var($dat);

    if( !$dat || !$ope ){
      _ele.act('cla_tog',$.lis.children,DIS_OCU); 
    }
    else{
      _lis.val($.lis.children).forEach( $ite => {

        if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){

          if( $ite.nextElementSibling ){
            if( 
              ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
              ||
              ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
            ){
              _app_lis.ite_val($ite);
            }
          }
        }
      } );
    }
  }  
  static ite_ver( $dat, $ope ){
    let $ = _app_dat.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $_app.ope.var.querySelector('[name="val"]').value ){
        // oculto todos
        _ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $_app.ope.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            if( $.ope_val = _val.ver($e.innerHTML,$.ope,$.val) ){
              $e.classList.remove(DIS_OCU);
            }else{
              $e.classList.add(DIS_OCU);                 
            }
            $.dd = $e.nextElementSibling;
            while( $.dd && $.dd.nodeName == 'DD' ){
              if( $.ope_val ){ 
                $.dd.classList.remove(DIS_OCU); 
              }else{ 
                $.dd.classList.add(DIS_OCU); 
              }
              $.dd = $.dd.nextElementSibling;
            }
          });
        }
        else{
          _lis.val($.lis.children).forEach( $e => 
            _val.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        _ele.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': _ele.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': _ele.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $_app.ope.var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  // contenedores
  static val(){      
  }
  static val_ver( $dat, $ope = 'p:first-of-type', $cla = 'let-luz' ){
    let $ = _app_dat.var($dat);
    // busco listado
    if( $_app.ope.var ){
      $.lis = !! $_app.ope.var.nextElementSibling ? $_app.ope.var.nextElementSibling : $_app.ope.var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }
    // ejecuto filtros
    if( $.lis && ( $.ope = $_app.ope.var.querySelector(`[name="ope"]`) ) && ( $.val = $_app.ope.var.querySelector(`[name="val"]`) ) ){      
      // elimino marcas anteriores      
      $.lis.querySelectorAll(`li.pos ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );
      // muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {

        // capturo item : li > [.val] (p / a)
        $.ite = _ele.ver($ite,{'eti':'li'});
        // ejecuto comparacion por elemento selector ( p / a )
        if( !$.val.value || _val.ver($ite.innerText, $.ope.value, $.val.value) ){
          // oculto/mustro item
          $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
          // agrego brillo
          !!$.val.value && $ite.classList.add($cla);
        }
        else if( !$.ite.classList.contains(DIS_OCU) ){
          $.ite.classList.add(DIS_OCU);
        }
      });
      // por cada item mostrado, muestro ascendentes
      $.tot = 0;
      if( $.val.value ){
        $.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`).forEach( $ite => {
          $.tot ++;
          $.val = $ite;
          while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI' && $.ite.classList.contains('pos') ){
            $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
            $.val = $.ite;
          }
        } );
        $.tog = ['ver','tod'];
      }
      else{
        $.tog = ['ocu','nad'];
      }      
      // actualizo toggle
      if( $.tog[1] && ( $.ico = $_app.ope.var.querySelector(`.ico.val_tog-${$.tog[1]}`) ) ) _app_ope.tog($.ico,$.tog[1]);
      
      // actualizo total
      if( $.tot_val = $_app.ope.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;           
    }      
  }

  // desplazamiento horizontal x item
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $ = _app_dat.var($dat);

    if( $tip == 'val' ){

      $.lis = $_app.ope.var.previousElementSibling;

      $.val = $_app.ope.var.querySelector('[name="val"]');
      $.pos = _num.val($.val.value);

      switch( $dat.getAttribute('name') ){
      case 'ini': $.pos = _num.val($.val.min);
        break;
      case 'pre': $.pos = $.pos > _num.val($.val.min) ? $.pos-1 : $.pos;
        break;
      case 'pos': $.pos = $.pos < _num.val($.val.max) ? $.pos+1 : $.pos;
        break;
      case 'fin': $.pos = _num.val($.val.max);
        break;
      }
      // valido y muestro item
      $.val.value = $.pos;

      _ele.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove(DIS_OCU);
    }
  }  
}
// Opcion
class _app_opc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $={};

    switch( $tip ){
    case 'val': 
      if( !$ope.nodeName ){
        $.opc = document.createElement('select');
        for( const $atr in $ope ){
          $.opc.setAttribute($atr,$ope[$atr]);
        }
        $ope = $.opc;
      }
      if( $opc.includes('nad') ){
        $.ite = document.createElement('option');
        $.ite.value = ''; $.ite.innerHTML = '{-_-}';
        $ope.appendChild($.ite);
      }
      $.val_ide = $opc.includes('ide');
      $dat.forEach( ($dat,$ite) => {
        $.ite = document.createElement('option');
        // identificador
        if( $dat.ide ){ $.ide = $dat.ide; }else if( $dat.pos ){ $.ide = $dat.pos; }else{ $.ide = $ite; }
        // valor
        $.ite.setAttribute('value',$.ide);
        // titulo
        if( !!$dat.des || !!$dat.tit ) $.ite.setAttribute('title', !! $dat.des ? $dat.des : $dat.tit );
        // contenido
        $.htm = "";
        if( !!$.val_ide && !!$dat.ide ){ $.htm += `${$dat.ide}: `; }
        if( !!$dat.nom ){ $.htm += $dat.nom; }
        $.ite.innerHTML = $.htm;
        $ope.appendChild($.ite);
      });
      $_ = $ope;
      break;
    }
    return $_;      
  }
  // selector
  static val( $dat, $ope, ...$opc ){
    let $_, $={};

    if( $ope && !$ope.nodeName ){
      $.opc = document.createElement('select');
      for( const $atr in $ope ){ $.opc.setAttribute($atr,$_[$atr]); }
      $_ = $.opc;
    }else{
      $_ = $ope;
    }
    $.val = '';
    if( $ope.value ){
      $.val = $ope.value;
    }else if( $ope.val ){
      $.val = $ope.val;
    }

    _app_opc.lis($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }
  // opction
  static lis( $dat, $val, ...$opc ){
    let $_=[], $={};    

    if( $opc.includes('nad') ){
      $.ite = document.createElement('option');
      $.ite.value = ''; 
      $.ite.innerHTML = '{-_-}';
      $_.push($.ite);
    }

    $.val_ide = $opc.includes('ide');

    for( const $ide in $dat ){ $.obj_tip = _obj.tip($dat[$ide]); break; }
    $.obj_pos = $.obj_tip == 'pos';

    for( const $ide in $dat ){ const $ite = $dat[$ide];

      $.ite = document.createElement('option');
      $.val = $ide;
      $.htm = "";
      if( !$.obj_tip ){        
        $.htm = $ite;
      }
      else if( $.obj_pos ){        
        $.htm = $ite.join(', ');
      }
      else{
        // valor
        if( $ite.ide ){ $.val = $ite.ide; }else if( $ite.pos ){ $.val = $ite.pos; }
        // titulo
        if( !!$ite.des || !!$ite.tit ) $.ite.setAttribute('title', !! $ite.des ? $ite.des : $ite.tit );
        // contenido        
        if( !!$.val_ide && !!$ite.ide ) $.htm += `${$ite.ide}: `;
        if( !!$ite.nom ) $.htm += $ite.nom;
      }      
      $.ite.setAttribute('value',$.val);
      if( $val == $.val ) $.ite.setAttribute('selected',"");
      $.ite.innerHTML = $.htm;
      $_.push($.ite);
    }
    return $_;
  }  
}
// Número
class _app_num {
  
  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
  // al actualizar
  static act( $dat ){

    let $={};

    $.val = _num.val($dat.value);

    // excluyo bits
    if( $dat.type != 'text' ){

      // valido minimos y máximos
      if( ( $.min = _num.val($dat.min) ) && $dat.value && $.val < $.min ) $dat.value = $.val = $.min;    

      if( ( $.max = _num.val($dat.max) ) && $dat.value && $.val > $.max ) $dat.value = $.val = $.max;

      // relleno con ceros
      if( $dat.getAttribute('num_pad') && ( $.num_cue = $dat.maxlength ) ) $.num_pad = _num.val($.val,$.num_cue);

      // actualizo valores por rango
      if( $dat.type == 'range' ){

        if( $.val = $dat.parentElement.nextElementSibling ) $.val.innerHTML = $.num_pad ? $.num_pad : $dat.value;
      }
      // por entero o decimales
      else{

        if( $.num_pad ) $dat.value = $.num_pad;
      }      
    }
  }
}
// Texto
class _app_tex {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Figura
class _app_fig {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Fecha
class _app_fec {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Archivo
class _app_arc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Objeto
class _app_obj {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = _dat.tip($v);

      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-1');
      $.ite.innerHTML = `
        <q class='ide'>${$i}</q> <c class='sep'>:</c>
      `;
      if( ![undefined,NaN,null,true,false].includes($v) ){

        $.ite.innerHTML += _app.let( ( $.tip.dat=='obj' ) ? JSON.stringify($v) : $v.toString() ) ;          
      }
      else{
        $.ite.innerHTML += `<c>${$v}</c>`;
      }

      $_.appendChild($.ite);
    }

    return $_;      
  }
  // 
}
// Ejecucion
class _app_eje {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

}
// Elemento
class _app_ele {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    case 'her':
      $.her = [];
      $.ele = $dat; 
      while( $.ele ){ 
        $.nom = $.ele.nodeName.toLowerCase();
        if( $.nom=='#document' || $.nom=='html' ){
          break;
        }
        $.tex=`<font class='ide'>${$.nom}</font>`; 
        if( $.ele.id ){ 
          $.tex += `<c>#</c><font class='val'>${$.ele.id}</font>`; 
        }
        else if( $.ele.name ){ 
          $.tex += `<c>[</c><font class='val'>${$.ele.name}</font><c>]</c>`; 
        }
        if( $.ele.className){
          $.cla_lis = $.ele.className.split(' ').map( $ite => `<c>.</c><font class='val'>${$ite}</font>` );
          $.tex += `${$.cla_lis.join('')}`;
        }
        $.her.push($.tex);
        $.ele = ( $.ele.parentNode) ? $.ele.parentNode : false;
      }
      $_ = $.her.reverse().join(`<c class='sep'>></c>`);
      break;            
    case 'eti':
      $.nom = $dat.nodeName.toLowerCase();
      $.fin=`
      <c class='lis'><</font>
      <c>/</c>
      <font class='ide'>${$.nom}</font>
      <c class='lis'>></c>`;
      $.eti_htm=''; 
      if( $dat.childNodes.length>0 ){ 
        _lis.val($dat.childNodes).forEach( $e =>{
          if( $e.nodeName.toLowerCase() != '#text' ){ $.eti_htm += `
            <li>${this.ele('eti',$e)}</li>`;
          }else{ $.eti_htm += `
            <li>${_app.let($e.textContent)}</li>`;
          }
        });
      }
      $.eti_atr=[];
      $dat.getAttributeNames().forEach( $atr => $.eti_atr.push(`
        <li class='mar_hor-1'>
          <font class='val'>${$atr}</font>
          <c class='sep'>=</c>
          <q>${$dat.getAttribute($atr)}</q>
        </li>`)
      );
      $_ =`
      <div var='ele_eti' class='dat'>

        <div class='ite ini'>

          <c class='lis sep'><</font>
          <font class='ide' onclick='_app_ele.act(this,'ver');'>${$.nom}</font>
          <ul class='val _atr'>
            ${$.eti_atr.join('')}
          </ul>
          <c class='lis sep'>></c>          

          <p class='tog dis-ocu'>
            c onclick='_app_ele.act(this,'ver');' class='tog dis-ocu'>...</>
            ${$.fin}
          </p>
        </div>`;
        if( !['input','img','br','hr'].includes($.nom) ){
          $_ += `
          <ul class='val _nod mar_hor-3'>
            ${$.eti_htm}
          </ul>

          <div class='ite tog fin'>
            ${$.fin}
          </div>  `;
        }$_ += `
      </div>`;
      break;
    }

    return $_;      
  }
  // ...
  static act( $dat ){
    let $={};

    $dat.parentElement.parentElement.querySelectorAll(`.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) )

    $dat.nextElementSibling.classList.add(FON_SEL);

    $.ver = $dat.nextElementSibling.innerText.replaceAll('\n','');
    // resultado
    $.res = $dat.parentElement.parentElement.previousElementSibling.querySelector('div.ele');

    _ele.eli($.res);

    $.res.innerHTML = _doc.ele('eti',document.querySelector($.ver));
  }
  // ...
  static val( $dat ){
    let $={};

    $.ope = document.createElement('form');

    $.lis = document.createElement('ul');

    $.lis.classList.add('lis');

    $dat.forEach( $e => {
      $.ite = document.createElement('li');            
      $.ite.classList.add('mar_ver-2');            
      $.ite.innerHTML = `${_app.ico('dat_ver',{'class':"tam-1 mar_der-1",'onclick':"_app_ele.act(this);"})}`;
      $.tex = document.createElement('p');
      $.tex.innerHTML = _doc.ele('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });

    return [ $.ope, $.lis ];     
  }
}