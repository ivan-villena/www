// WINDOW
'use strict';

// operativas
const DIS_OCU = "dis-ocu";
const FON_SEL = "fon-sel";
const BOR_SEL = "bor-sel";
const ROT_VER = "rotate(0deg)";
const ROT_OCU = "rotate(270deg)";

var $$ = {  
  // articulo
  win : document.querySelector('#win'),
  ope : document.querySelector('body > aside.ope'),
  nav : document.querySelector('body > aside.nav'),
  sec : document.querySelector('body > main'),
  pan : document.querySelector('body > aside.pan'),
  pie : document.querySelector('body > footer'),
  // operadores
  est : {
    'dat':`article[ide="est"] div[var="est"] > table`,
    'ope':`article[ide="est"]`,
    'val':`article[ide="est"] section[ide="val"]`,
    'acu':`article[ide="est"] section[ide="val"] form[ide="acu"]`,
    'sum':`article[ide="est"] section[ide="val"] form[ide="sum"]`,
    'opc':`article[ide="tab"] section[ide="opc"]`,
    'ver':`article[ide="est"] section[ide="ver"]`,
    'atr':`article[ide="est"] section[ide="atr"]`,      
    'des':`article[ide="est"] section[ide="des"]`,
    'cue':`article[ide="est"] section[ide="cue"]`
  },
  tab : {
    'dat':`main > article > [tab]`,
    'ope':`article[ide="tab"]`,
    'val':`article[ide="tab"] section[ide="val"]`,
    'acu':`article[ide="tab"] section[ide="val"] form[ide="acu"]`,
    'sum':`article[ide="tab"] section[ide="val"] form[ide="sum"]`,
    'opc':`article[ide="tab"] section[ide="opc"]`,
    'pos':`article[ide="tab"] section[ide="opc"] form[ide="pos"]`,
    'sec':`article[ide="tab"] section[ide="opc"] form[ide="sec"]`,
    'ver':`article[ide="tab"] section[ide="ver"]`,
    'atr':`article[ide="tab"] section[ide="atr"]`
  }
};

// documento
class _doc {

  _val = { 'pos':[], 'mar':[], 'ver':[] };

  constructor(){

    // cargo eventos de teclado
    document.onkeyup = $eve => _doc.inp($eve);

    // anulo formularios
    document.querySelectorAll('form').forEach( $ele => _ele.ope( $ele, 'eje_agr', 'submit', `evt => evt.preventDefault()` ) );

    // cargo operadores
    if( $$.sec ){

      $$.sec.scroll(0,0);
      
      if( $_api._uri.cab ){

        // operadores de dato : tablero + listado + informe + navegador
        if( $_api._uri.cab == 'tab' ){

          ['est','tab'].forEach( $ope => {

            for( const $ide in $$[$ope] ){

              $$[$ope][$ide] = document.querySelector($$[$ope][$ide]); 
            }
          });
        } 
        // indice por artículo
        else if( $_api._uri.art && ( $.art_nav = document.querySelector('main > aside > nav[ide="nav"] > ul.lis') ) ){          
          
          _doc_art.nav($.art_nav);
        }
      }
    }    
  }

  // telcas : escape
  static inp( $dat ){
    let $={};

    switch( $dat.keyCode ){
    // Escape => ocultar modales
    case 27: 
      // menú contextual
      if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
        $.men.classList.add(DIS_OCU);
      }// pantallas
      else if( $.win = document.querySelector(`#win:not(.${DIS_OCU}) article header .ico[ide="eje_fin"]`) ){ 
        _doc_art.ope('win');
      }// paneles
      else if( $.pan = document.querySelector(`main > aside > [ide]:not(.${DIS_OCU})`) ){ 
        $.pan.classList.add(DIS_OCU);
      }
      break;
    }
  }
  // iconos
  static ico( $ide, $ele={} ){
    let $_="", $={
      '_ico':$_api._('ico')
    };
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
  // letras : c - n
  static let( $dat, $ele={} ){

    let $_="", $pal, $_pal=[], $let=[], $_let=$_api._let, $num=0;
    
    if( $dat !== null && $dat !== undefined && $dat !== NaN ){      

      $dat.toString().split(' ').forEach( $pal => {
        
        $num = _num.val($pal);
        if( !!$num || $num == 0 ){
          if( /,/.test($pal) ) $pal.replaceAll(",","<c>,</c>");
          if( /\./.test($pal) ) $pal.replaceAll(".","<c>.</c>");
          $_pal.push( `<n>${$pal}</n>` );
        }
        else{
          $let = [];
          $pal.split('').forEach( $car =>{
            $ele = $ele;
            $num = _num.val($car);
            if( !!$num || $num == 0 ){
              $let.push( `<n>${$car}</n>` );
            }
            else if( $_let[$car] ){
              $let.push( `<c${_htm.atr(_ele.cla($ele,`${$_let[$car].tip} _${$_let[$car].cla}`,'ini'))}>${$car}</c>` );
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
  // imagen : (span,button)[ima]
  static ima( ...$dat ){  
    let $_="",$={},$ele={};

    if( $dat[2] !== undefined ){

      if( /_ide$/.test($dat[1]) ){ $dat[1] = $dat[1].replace(/_ide$/,''); }

      $dat[3] = !!($dat[3]) ? $dat[3] : {};
      $dat[3]['ima'] = `${$dat[0]}.${$dat[1]}`;

      $_ = _doc_dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $dat[3]);
    }
    else{
      $ele = $dat[1] ? $dat[1] : {};
      $dat = $dat[0];
      $.tip = typeof($dat);
      $.fic_ide = '';
      if( $.tip == 'object' ){
    
        $ele = _ele.val( $dat, $ele );
      }
      else if( $.tip == 'string' ){
    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `_/${$dat}/ima`;
        $.fic_ide = $.dir+"."+$.tip;
        _ele.css( $ele, _ele.fon($.dir,{'tip':$.tip}) );
      }
    
      $ele['eti'] = !!($ele['eti']) ? $ele['eti'] : 'span';
      if( $ele['eti'] == 'button' ){
        if( !($ele['type']) ) $ele['type'] = "button";
      }      
      $.eti = $ele['eti'];
      $.htm='';
      if( !!($ele['htm']) ){
        _ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
      }
      if( !$ele['ima'] && $.fic_ide ){ $ele['ima'] = $.fic_ide; }
      $_ = `<${$.eti}${_htm.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }
  // variable : div.atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};
    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $$.for = _ele.ver($dat,{'eti':'form'});
      $.var_ide = '';
      if( $dat.name ){
        $.var_ide = $dat.name;
      }
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
  // bloques de contenido
  static tog( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){

      $.ite = $dat.parentElement;

      $.bot = $.ite.querySelector('.ico[ide="ope_tog"]');

      $.sec = $.ite.nextElementSibling;
      
      if( $.sec.classList.contains(DIS_OCU) ){
        $.sec.classList.remove(DIS_OCU);
        $.bot.style.transform = ROT_VER;
      }
      else{
        $.sec.classList.add(DIS_OCU);
        $.bot.style.transform = ROT_OCU;
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $$.for = _ele.ver($dat,{'eti':"form"}) ){
        $.rot = ( $ope == 'tod' ) ? ROT_VER : ROT_OCU;

        $.lis = !!$$.for.nextElementSibling ? $$.for.nextElementSibling : $$.for.parentElement.parentElement;
              
        $.lis.querySelectorAll(`.val > .ico[ide="ope_tog"]:not([style*="${$.rot}"])`).forEach( $e => $e.click() );
      }
    }
  }
  // pestañas por contenido
  static nav( $dat, $ope, ...$opc ){
    let $={};

    $.nav = $dat.parentElement;

    $.val_tog = $opc.includes('tog');

    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){

      $.lis = $.nav.nextElementSibling;

      _lis.val( $.lis.children ).forEach( $e => {

        if( $.val = $e.getAttribute('ide') ){

          if( $.val == $ope ){
            
            if( $.val_tog ){

              $e.classList.toggle(DIS_OCU);
              $dat.classList.toggle(FON_SEL);
            }
            else if( $e.classList.contains(DIS_OCU) ){

              $e.classList.remove(DIS_OCU);
              $dat.classList.add(FON_SEL);
            }              
          }
          else if( !$e.classList.contains(DIS_OCU) ){ 

            $e.classList.add(DIS_OCU); 
          }
        }
      });
    }      
  }
}

// Articulo
class _doc_art {
 
  // botonera : navegadores + pantallas + paneles
  static ope( $tip, $ide ){
  
    switch( $tip ){
    // pantalla : #win > article > header + section
    case 'win':
      // muestro articulo
      if( $ide ){
        // muestro fondo
        $$.win.classList.remove(DIS_OCU);
        // oculto articulos  
        $$.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );        
        // muestro por valor
        if( typeof($ide) == 'string' ) $ide = $$.win.querySelector(`article[ide="${$ide}"].${DIS_OCU}`);

        if( $ide ) $ide.classList.remove(DIS_OCU); 
      }// oculto articulos
      else{        
        $$.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
        // oculto fondo
        $$.win.classList.add(DIS_OCU);
      }
      break;
    // navegadores : aside.nav
    case 'nav':
      $$.nav.querySelectorAll(`nav[ide]:not( [ide="${$ide}"], .${DIS_OCU} )`).forEach( $e => $e.classList.add(DIS_OCU) );
      $$.nav.querySelectorAll(`nav[ide="${$ide}"]`).forEach( $e => $e.classList.toggle(DIS_OCU) );
      // aculto-muestro contenedor
      if( $$.nav.querySelector(`nav[ide]:not(.${DIS_OCU})`) ){
        $$.nav.classList.contains(DIS_OCU) && $$.nav.classList.remove(DIS_OCU);
      }
      else if( !$$.nav.classList.contains(DIS_OCU) ){
        $$.nav.classList.add(DIS_OCU);
      }
      break;
    // seccion : main > article
    case 'sec':
      $$.sec.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      $$.sec.querySelectorAll(`article[ide="${$ide}"].${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      $$.sec.scroll(0, 0);
      break;      
    // campos : form > fieldsets
    case 'var':      
      _lis.val( _ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );      
      break;
    }
  }

  // pantallas : articulo > cabecera( ico + tit + ocu ) + contenido
  static win( $tip ){
    
    if( $tip ){
    }// oculto
    else{
      // oculto articulos
      $$.win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // oculto fondo
      $$.win.classList.add(DIS_OCU);
    }
  }

  // menu de navegacion
  static nav( $dat ){
    let $={
      ide : location.toString().split('#')
    };

    if( $dat && $.ide[1] ){

      // hago toogle por item
      if( $.nav = _doc_art.nav_mar($dat) ){

        // hago toogles ascendentes
        while( 
          ( $.lis = $.nav.parentElement.parentElement.parentElement ) 
          && ( $.val = $.lis.previousElementSibling ) && ( $.val.nodeName == 'DIV' &&  $.val.classList.contains('val') )
          && ( $.nav = $.val.querySelector('a[href^="#"]') )
        ){
          if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('ico') ){                
            _doc.tog($.ico);
          }                
        }
      }
    }
  }// click en item
  static nav_val( $dat, $cla = FON_SEL ){
    let $ = {
      nav : _ele.ver($dat,{'eti':'nav'})
    };
    // elimino marcas previas
    $.nav && $.nav.querySelectorAll(`.val.${$cla}`).forEach( $e => $e.classList.remove($cla) );

    // controlo el toggle automatico por dependencias
    if( 
      ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
      &&
      ( $dat.classList.contains('ico') || $.dep.classList.contains(DIS_OCU) ) 
    ){
      _doc.tog($dat);
    }

    // pinto fondo
    if( !( $.bot = $dat.parentElement.querySelector(`.ico`) ) || $.bot.style.transform == ROT_VER ){

      $dat.parentElement.classList.add($cla);
    }

  }// marco valor seleccionado
  static nav_mar( $dat ){
    let $={
      ide : location.toString().split('#')
    };

    // hago toogle por item
    if( $.nav = $dat.querySelector(`a[href="#${$.ide[1]}"]`) ){
        
      _doc_art.nav_val($.nav);
    }

    return $.nav;
  }// ejecuto filtros
  static nav_ver( $dat, $ope = 'a[href]' ){
    let $=_doc.var($dat);

    // ejecuto filtros
    _doc_lis.val_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    _doc_art.nav($$.for.nextElementSibling);

  }

  // contenido principal
  static sec( ){    
  }

  // paneles
  static pan( ){    
  }
}
// Lista
class _doc_lis {

  // opciones
  static opc( $dat, $ope, ...$opc ){
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

    _doc_lis.opc_ite($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }
  static opc_ite( $dat, $val, ...$opc ){
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

    for( const $ide in $dat ){ const $val = $dat[$ide];

      $.ite = document.createElement('option');
      $.val = $ide;
      $.htm = "";
      if( !$.obj_tip ){        
        $.htm = $val;
      }
      else if( $.obj_pos ){        
        $.htm = $val.join(', ');
      }
      else{
        // valor
        if( $val.ide ){ $.val = $val.ide; }else if( $val.pos ){ $.val = $val.pos; }
        // titulo
        if( !!$val.des || !!$val.tit ) $.ite.setAttribute('title', !! $val.des ? $val.des : $val.tit );
        // contenido        
        if( !!$.val_ide && !!$val.ide ) $.htm += `${$val.ide}: `;
        if( !!$val.nom ) $.htm += $val.nom;
      }      
      $.ite.setAttribute('value',$.val);
      if( $val && $val == $.val ) $.ite.setAttribute('selected',"");
      $.ite.innerHTML = $.htm;
      $_.push($.ite);
    }
    return $_;
  }

  // punteos, numeraciones, y términos
  static ite(){
  }
  static ite_tex( $dat ){
    let $=_doc.var($dat);

    if( $dat.nodeName == 'DT' ){

      $.dd = $dat.nextElementSibling;

      while( $.dd && $.dd.nodeName == 'DD' ){
        $.dd.classList.toggle(DIS_OCU);
        $.dd = $.dd.nextElementSibling;
      }
    }
    // por tipo : punteo o numerado
    else{

    }
  }
  static ite_tog( $dat, $ope ){
    let $=_doc.var($dat);

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
              _doc_lis.ite_tex($ite);
            }
          }
        }
      } );
    }
  }  
  static ite_ver( $dat, $ope ){
    let $=_doc.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $$.for.querySelector('[name="val"]').value ){
        // oculto todos
        _ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $$.for.querySelector('[name="ope"]').value;
        
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
    if( $.tot = $$.for.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = _lis.val($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  // valores con titulo
  static val(){      
  }
  static val_ver( $dat, $ope = 'p:first-of-type', $cla = 'let-luz' ){
    let $=_doc.var($dat);

    // busco listado
    if( $$.for ){
      $.lis = !! $$.for.nextElementSibling ? $$.for.nextElementSibling : $$.for.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }

    // ejecuto filtros
    if( $.lis && ( $.ope = $$.for.querySelector(`[name="ope"]`) ) && ( $.val = $$.for.querySelector(`[name="val"]`) ) ){
      
      // elimino marcas anteriores      
      $.lis.querySelectorAll(`li.ite ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );
      
      // muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.ite ${$ope}`).forEach( $ite => {

        // capturo item : li > [.val] (p / a)
        $.ite = _ele.ver($ite,{'eti':'li'});

        // ejecuto comparacion por elemento selector ( p / a ), y oculto/mustro item
        if( !$.val.value || _val.ver($ite.innerText, $.ope.value, $.val.value) ){
          $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
          !!$.val.value && $ite.classList.add($cla);// agrego brillo
        }
        else if( !$.ite.classList.contains(DIS_OCU) ){
          $.ite.classList.add(DIS_OCU);
        }
      } );      
      // por cada item mostrado, muestro ascendentes
      $.tot = 0;
      if( $.val.value ){
        $.lis.querySelectorAll(`li.ite:not(.${DIS_OCU})`).forEach( $ite => {
          $.tot ++;
          $.val = $ite;
          while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI' && $.ite.classList.contains('ite') ){
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
      if( $.tog[1] && ( $.ico = $$.for.querySelector(`.ico[ide="ope_${$.tog[1]}"]`) ) ){
        
        _doc.tog($.ico,$.tog[1]);
      }            
      
      // actualizo total
      if( $.tot_val = $$.for.querySelector(`[name="tot"]`) ){

        $.tot_val.innerHTML = $.tot;
      }            
    }      
  }

  // desplazamiento horizontal x item
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $=_doc.var($dat);

    if( $tip == 'val' ){

      $.lis = $$.for.previousElementSibling;

      $.val = $$.for.querySelector('[name="val"]');
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

      _ele.act('cla_agr',$.lis.querySelectorAll(`li[pos]:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li[pos="${$.pos}"]`) ) $.ite.classList.remove(DIS_OCU);
    }
  }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Dato
class _doc_dat {
    
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
      $ide.forEach( $i => { if( $.ope = $$.for.querySelector(`[name="${$i}"]`) ) _ele.eli( $.ope, `option:not([value=""])` ); });
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

      $.ope = $$.for.querySelector('[name="ope"]').value;
      $.val = $$.for.querySelector('[name="val"]').value;
      $.lis = $$.for.nextElementSibling.querySelector('tbody');
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

      if( !$ope.fic ) $ope.fic = _dat.ver('ima', $ope.esq, $ope.est );

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
class _doc_val {

  // operaciones : alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $=_doc.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $$.for.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $$.for.querySelectorAll(`div.atr > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $$.for.querySelectorAll(`div.atr > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $$.for.querySelectorAll(`[id][name]`).forEach( $atr => {
        
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
      $$.for.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$$.for);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $$.for.dataset.esq ) && ( $.est = $$.for.dataset.est ) ){
          _eje.val(['_doc.dat_val', [ $.esq, $.est, $tip, $._val ] ], $e => {            
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $$.for.reset();              
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

    $$.for = $ope.querySelector(`form[ide="${$tip}"]`);

    // datos de la base : estructura > valores [+ima]
    if( $._tip[0] == 'dat' ){
      $.dat_est = $$.for.querySelector(`[name="est"]`);
      $.dat_val = $$.for.querySelector(`[name="val"]`);
      $.dat_ide = $$.for.querySelector(`[name="ver"]`);
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
        if( ( $.ite = $$.for.querySelector(`[name="${$ide}"]`) ) && !!$.ite.value ){

          $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? _num.val($.ite.value) : $.ite.value;
        }
      });
      
      // valido: si el inicio es mayor que el final
      if( $.val.ini && $.val.ini > $.val.fin ){
        
        $$.for.querySelector(`[name="ini"]`).value = $.val.ini = $.val.fin;
      }
      // si el final es mejor que el inicio
      if( $.val.fin && $.val.fin < $.val.ini ){

        $$.for.querySelector(`[name="fin"]`).value = $.val.fin = $.val.ini;
      }    
      // inicializo incremento
      if( ( !$.val.inc || $.val.inc <= 0 ) && ( $.ite = $$.for.querySelector(`[name="inc"]`) ) ){
        $.ite.value = $.val.inc = 1;
      }
      $.inc_val = 1;
      // inicializo limites desde
      if( !$.val.fin && ( $.ite = $$.for.querySelector(`[name="fin"]`) ) && ( $.max = $.ite.getAttribute('max') ) ){
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
        if( $$.for.querySelector(`.ico[ide="nav_fin"].bor-sel`) ) $.lis = $.lis.reverse();

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
      if( $opc.length == 0 ) $opc = Object.keys($_doc._val);

      $opc.forEach( $ide => {

        // acumulo elementos del listado
        $_doc._val[$ide] = [];
        $dat.querySelectorAll(`._val-${$ide}`).forEach( $e => $_doc._val[$ide].push($e) );

        // actualizo total del operador
        if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

          $.tot.innerHTML = $_doc._val[$ide].length;
        }
      });

      // calculo el total grupal
      $.val_tot = _doc_val.acu('tot',$tip);
      if( $.tot = $ope.querySelector(`[name="cue"]`) ){
        $.tot.innerHTML = $.val_tot.length;
      }

      // devuelvo el total
      $_ = $.val_tot;           
      break;
    case 'tot': 
      $.tot = [];    
      for( const $ide in $_doc._val ){ 
        if( $dat ){
          // hacer algo con esto
        }
        for( const $ite of $_doc._val[$ide] ){
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

      _doc_dat.fic( $val, $.sum);
      
    });
  }
}
// Tablero
class _doc_tab {

  // inicializo : opciones, posicion, filtros
  static ini(){

    let $={};      

    if( !$$.tab.cla ){
      $$.tab.cla = '.pos';
    }
    // inicializo opciones
    $$.tab.ide = $$.tab.dat.getAttribute('tab');
          
    ['opc'].forEach( $ope => {
      
      if( $$.tab[$ope] ){

        $$.tab[$ope].querySelectorAll(`form[ide] [onchange*="_doc_tab."]:is( input:checked, select:not([value=""]) )`).forEach( 
          
          $inp => _doc_tab[`${$ope}_${_ele.ver($inp,{'eti':`form`}).getAttribute('ide')}`]( $inp )
        );
      }
    });
    // marco posicion principal
    _doc_tab.val_pos();

    // actualizo opciones
    Object.keys($_doc._val).forEach( $ite =>
      ( $.ele = $$.tab.acu.querySelector(`[name="${$ite}"]:checked`) ) && _doc_tab.val_acu($.ele) 
    );          

  }

  // actualizo : acumulados, sumatorias, fichas, listado
  static act( $dat ){
    
    let $={};
    
    $dat = !$dat ? Object.keys($_doc._val) : _lis.ite($dat);

    $.dat = $$.tab.dat;

    // actualizo valores acumulados
    if( $$.tab.acu ) $.tot = _doc_val.acu('act',$.dat,$$.tab.acu,...$dat );
    
    // actualizo sumatorias
    if( $$.tab.sum ) _doc_val.sum($.tot, $$.tab.sum);

    // fichas del tablero
    if( ( $$.tab.pos ) && ( $.ima = $$.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $$.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );

      if( $.ope.length > 0 ) _doc_tab.opc_pos($.ima);
    }

    // actualizo valores de lista
    if( $$.est.acu ){
      // actualizo acumulados
      if( !$$.est.acu.querySelector(`[name="tod"]:checked`) ) _doc_est.val_acu();
      // ejecuto filtros + actualizo totales      
      if( $$.est.ver ) _doc_est.ver('val');
    }    
  }

  // Seleccion : datos, posicion, fecha
  static ver( $tip, $dat, $ope ){

    let $=_doc.var($dat);      

    if( $tip == 'val' ){

      for( const $ide in $_doc._ope.val.ver ){

        if( $.ele = $$.tab.ver.querySelector($_doc._ope.val.ver[$ide]) ){

          _doc_tab.ver($ide,$.ele,$$.tab.dat);

          break;
        } 
      }
    }
    else{
      // ejecuto filtros por tipo : pos, fec
      
      _doc_val.ver( $tip, _lis.val( $$.tab.dat.querySelectorAll(`${$$.tab.cla}`)), $$.tab.ver, 'ver');
    }
    // actualizo calculos + vistas( fichas + items )
    _doc_tab.act('ver');

  }  

  // valores
  static val(){
  }// acumulados( posicion + marcas + seleccion )
  static val_acu( $dat, $ope ){
    
    let $=_doc.var($dat);

    if( !$.var_ide && $ope ) $ = _doc.var( $dat = $$.tab.acu.querySelector(`[name="${$ope}"]`) );

    $.cla_ide = `_val-${$.var_ide}`;
    
    if( !$dat.checked ){            
      $$.tab.dat.querySelectorAll(`.${$.cla_ide}`).forEach( $e => $e.classList.remove($.cla_ide) );
    }
    // busco marcas y aplico bordes
    else{
      $$.tab.dat.querySelectorAll(`.${$.cla_ide}-bor`).forEach( $e => $e.classList.add($.cla_ide) );
    }

    // actualizo calculos + vistas( fichas + items )        
    _doc_tab.act($.var_ide);

  }// por posicion principal
  static val_pos( $dat ){
    
    let $=_doc.var($dat);
    
    if( $_hol && $_hol._dat && ( $.kin = $_hol._dat.kin ) ){
      $$.tab.dat.querySelectorAll(`.pos[hol-kin="${$.kin}"], .pos[hol-kin="${$.kin}"] [pos][hol-kin="${$.kin}"]`).forEach( $e => {

        $e.classList.add(`_val-pos`);
        $e.classList.add(`_val-pos-bor`);
      });
      // actualizo totales
      _doc_tab.act('pos');
    }

  }// por marcas del tablero
  static val_mar( $dat ){
    
    let $=_doc.var($dat);      

    if( $$.tab.acu && $$.tab.acu.querySelector(`[name="mar"]:checked`) ){
        
      $.pos = $dat.getAttribute('pos') ? $dat : $dat.parentElement;// desde elemento del tablero / lista  

      if( !$.pos.getAttribute('tab') ){

        $.pos.classList.toggle(`_val-mar`);
        $.pos.classList.toggle(`_val-mar-bor`);
      }
      // actualizo totales
      _doc_tab.act('mar');
    }
    
  }// por seleccion : datos + posicion + fecha
  static val_ver( $dat ){
    
    let $=_doc.var($dat);    

    for( const $ide in $_doc._ope.val.ver ){

      if( $.ele = $$.tab.ver.querySelector($_doc._ope.val.ver[$ide]) ){

        _doc_tab.ver($ide,$.ele,$$.tab.dat);

        break;
      } 
    }

    _doc_tab.act('ver');

  }

  // opciones
  static opc(){
  }// secciones : bordes + colores + imagen + ...
  static opc_sec( $dat ){

    let $=_doc.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$$.tab.dat.classList.contains('bor-1') ){ $$.tab.dat.classList.add('bor-1'); }
        $$.tab.dat.querySelectorAll('ul[tab]:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $$.tab.dat.classList.contains('bor-1') ){ $$.tab.dat.classList.remove('bor-1'); }
        $$.tab.dat.querySelectorAll('ul[tab].bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $$.tab.dat.querySelectorAll(`[tab][class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $$.tab.dat.classList.contains('fon-0') ){
          $$.tab.dat.classList.remove('fon-0');
        }
      }else{
        // secciones
        $$.tab.dat.querySelectorAll(`[tab][class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$$.tab.dat.classList.contains('fon-0') ){
          $$.tab.dat.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $$.tab.dat.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $$.tab.dat.style.backgroundImage = '';
      }
      break;      
    }     
  }// posiciones : borde + color + imagen + texto + numero + fecha
  static opc_pos( $dat ){

    let $=_doc.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $$.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      Object.keys($_doc._val).forEach( $ver =>{
        if( $[$.var_ide][$ver] = $$.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $$.tab.dat.querySelectorAll(`${$$.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $$.tab.dat.querySelectorAll(`${$$.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      if( $$.tab.dep ){
        $.eli = `${$$.tab.cla} [pos]:not([tab])[class*='${$.ope}']`;
        $.agr = `${$$.tab.cla} [pos]:not([tab])`;
      }
      else{
        $.eli = `${$$.tab.cla}[class*='${$.ope}']`;
        $.agr = `${$$.tab.cla}`;
      }

      $$.tab.dat.querySelectorAll($.eli).forEach( $e => _ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $.col = _dat.ver('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        $.col = ( $.col && $.col.val ) ? $.col.val : 0;

        $$.tab.dat.querySelectorAll($.agr).forEach( $e =>{

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
      $$.tab.dat.querySelectorAll($$.tab.cla).forEach( $e => {
        $e.querySelectorAll('[ima]').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = _dat.ide($dat.value,$);
        
        // busco valores de ficha
        $.fic = _dat.ver('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        // actualizo por opciones
        $$.tab.dat.querySelectorAll($$.tab.cla).forEach( $e => {
          $.htm = '';
          if( $.ima.pos || $.ima.ver || $.ima.mar ){

            if( $.ima.pos && $e.classList.contains('_val-pos') ){ 
              $.htm = _doc_dat.ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar') ){ 
              $.htm = _doc_dat.ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver') ){ 
              $.htm = _doc_dat.ima($e,$);
            }
          }// todos
          else{
            $.htm = _doc_dat.ima($e,$);
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
      $$.tab.dat.querySelectorAll($$.tab.cla).forEach( $e => _ele.eli($e,$.eti) );

      if( $dat.value ){

        $ = _dat.ide($dat.value,$);

        $$.tab.dat.querySelectorAll($$.tab.cla).forEach( $e =>{

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
class _doc_est {

  // genero listado
  static lis( $tip, $dat, $ele, ...$opc ){
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
  
  // inicializo por acumulados
  static ini(){

    let $={};   

    if( $$.est.acu ){

      if( $.ele = $$.est.acu.querySelector(`[name="tod"]`) ){

        _doc_est.val_tod($.ele);
      }
    }

  }
  // actualizo valores : acumulado + cuentas + descripciones
  static act(){

    let $={};

    // actualizo total
    if( $$.est.acu && ( $.tot = $$.est.acu.querySelector('[name="cue"]') ) ){
      
      $.tot.innerHTML = _doc_est.cue();
    }
    // actualizo cuentas
    if( $$.est.cue ){

      _doc_dat.cue('act', $$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`), $$.est.cue);
    }
    // actualizo descripciones
    if( $$.est.des ){

      $$.est.des.querySelectorAll(`[name]:checked`).forEach( $e => _doc_est.des_tog($e) );
    }
  }
  // cuento items en pantalla
  static cue(){

    if( $$.est.dat ){
      
      return $$.est.dat.querySelectorAll(`tr[pos]:not(.${DIS_OCU})`).length;
    }
    else{
      return 'err: no hay tabla relacionada';
    }
  }

  // filtros : datos + posicion + atributos
  static ver( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    // todos los filtros
    if( $tip == 'val' ){

      for( const $ide in $_doc._ope.val.ver ){

        if( $.ele = $$.est.ver.querySelector($_doc._ope.val.ver[$ide]) ){

          _doc_est.ver($ide,$.ele);
        } 
      }
    }// por tipos
    else{
      // por ciclos + agrupaciones
      if( ['cic','gru'].includes($tip) ){
        
        // muestro todos los items
        $$.est.dat.querySelectorAll(`tbody tr:not([pos]).${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
        
        // aplico filtro        
        // ... 

      }
      // por seleccion
      else if( $$.est.ver ){        
        // ejecuto acumulados
        if( $$.est.acu ){
          // muestro todos
          if( $$.est.acu.querySelector(`[name="tod"]:checked`) ){

            _ele.act('cla_eli',$$.est.dat.querySelectorAll(`tbody tr[pos].${DIS_OCU}`),DIS_OCU);
          }// muestro solo acumulados
          else{
            _doc_est.val_acu();
          }                  
        }             
        
        $.eje = false;
        if( $tip == 'dat' ){

        }// pos + fec : ejecuto filtro si tiene valor inicial
        else if( $tip == 'pos' || $tip == 'fec' ){

          $.ini = $$.for.querySelector(`[name="ini"]`);
          $.eje = $.ini.value;
        }

        if( $.eje ){
          // ejecuto filtros
          // por selector
          _doc_val.ver( $tip, _lis.val( $$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`) ), $$.est.ver, 'ver');

          // oculto valores no seleccionados
          $$.est.dat.querySelectorAll(`tbody tr[pos]:not(._val-ver,.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );      
        }
      }
      // actualizo total, cuentas y descripciones
      _doc_est.act();
    }
  }

  // valores : todos | acumulados
  static val(){
  }// check : todos ? - o por acumulados
  static val_tod( $dat ){

    let $=_doc.var($dat);  
    
    if( $$.est.acu ){
      // ajusto controles acumulados
      Object.keys($_doc._val).forEach( $i => {

        if( $.val = $$.est.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
      });
    }

    // muestro todos
    if( $dat.checked  ){

      _ele.act('cla_eli',$$.est.dat.querySelectorAll(`tbody tr[pos].${DIS_OCU}`),DIS_OCU);

    }// o por acumulados
    else if( $$.est.acu ){        

      _doc_est.val_acu();
    }

    // ejecuto filtros
    if( $$.est.ver ){

      _doc_est.ver('val');
    }

    // actualizo total, cuentas y descripciones
    _doc_est.act();
  }// acumulados : posicion - marcas - seleccion
  static val_acu(){

    let $={};
    
    if( ( $.esq = $$.est.dat.dataset.esq ) && ( $.est = $$.est.dat.dataset.est ) ){
      
      // oculto todos los items de la tabla
      _ele.act('cla_agr',$$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`),DIS_OCU);

      // actualizo por acumulado
      Object.keys($_doc._val).forEach( $ide => {

        if( $.val = $$.est.acu.querySelector(`[name='${$ide}']`) ){
          $.tot = 0;
          if( $.val.checked ){

            $_doc._val[$ide].forEach( $e =>{
              
              if( $.ele = $$.est.dat.querySelector(`tbody tr[pos][${$.esq}-${$.est}="${$e.getAttribute(`${$.esq}-${$.est}`)}"].${DIS_OCU}`) ){
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
  
  // columnas : toggles + atributos
  static col(){
  }// muestro-oculto
  static col_tog( $dat ){

    let $=_doc.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $$.est.dat.querySelectorAll(`:is(thead,tbody) :is(td,th)[data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$dat.name}"]`).forEach( $ite => {
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

        _doc_est.col_tog($e);
      });
    }
  }

  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static des(){
  }// muestro-oculto
  static des_tog( $dat ){

    let $=_doc.var($dat);    

    $.ope  = $$.for.getAttribute('ide');
    $.esq = $$.for.dataset.esq;
    $.est = $$.for.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    $$.est.dat.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ).forEach( 
      $ite => $ite.classList.add(DIS_OCU)
    );
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = _dat.var($.esq,$.est,$ite.getAttribute(`${$.esq}-${$.est}`)) ) && $.val[$.atr] ){

          $.ide = ( $.ope == 'des' ) ? $ite.getAttribute(`${$.esq}-${$.est}`) : $.val[$.atr];            

          $$.est.dat.querySelectorAll(
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
      $$.est.dat.querySelectorAll(`tbody tr:not([data-ope="des"])[opc="${$.ite}"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $$.est.ver.querySelector(`form[ide="dat"] select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide = $$.est.ver.querySelector(`form[ide="dat"] select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite=='tit' ){
          if( $.opc!='gru' || (!!$.ide && $.ide.value) ){// no considero agrupaciones sin valor
            $.agr = !!$.ide && $.ide.value ? `[ide="${$.ide.value}"]` : '';
            $$.est.dat.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) )
          }
        }// descripciones por item no oculto
        else{
          $$.est.dat.querySelectorAll(`tbody tr:not([pos]):not(.${DIS_OCU})`).forEach( $e =>{                
            if( $.lis_ite = $$.est.dat.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU); 
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){
      // desmarco otras opciones
      $$.est.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`).forEach( $e => $e.checked = false );
      // oculto todas las leyendas
      $$.est.dat.querySelectorAll(`tbody tr[data-ope="${$tip}"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $$.est.dat.querySelectorAll(`tbody tr:not([pos]):not(.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $$.est.dat.querySelector(`table tr[data-ope="${$tip}"][data-atr="${$dat.value}"][ide="${$e.dataset.ide}"].${DIS_OCU}`) ){                
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }    
    
  }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Objeto
class _doc_obj {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = _val.tip($v);

      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-1');
      $.ite.innerHTML = `
        <q class='ide'>${$i}</q> <c class='sep'>:</c>
      `;
      if( ![undefined,NaN,null,true,false].includes($v) ){

        $.ite.innerHTML += _doc.let( ( $.tip.dat=='obj' ) ? JSON.stringify($v) : $v.toString() ) ;          
      }
      else{
        $.ite.innerHTML += `<c>${$v}</c>`;
      }

      $_.appendChild($.ite);
    }

    return $_;      
  }
}
// Ejecucion
class _doc_eje {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

}
// Elemento
class _doc_ele {

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
            <li>${_doc.let($e.textContent)}</li>`;
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
          <font class='ide' onclick='_doc_ele.act(this,'ver');'>${$.nom}</font>
          <ul class='val _atr'>
            ${$.eti_atr.join('')}
          </ul>
          <c class='lis sep'>></c>          

          <p class='tog dis-ocu'>
            c onclick='_doc_ele.act(this,'ver');' class='tog dis-ocu'>...</>
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
      $.ite.innerHTML = `${_doc.ico('val_ver',{'class':"tam-1 mar_der-1",'onclick':"_doc_ele.act(this);"})}`;
      $.tex = document.createElement('p');
      $.tex.innerHTML = _doc.ele('her',$e);
      $.ite.appendChild($.tex);
      $.lis.appendChild($.ite);
    });

    return [ $.ope, $.lis ];     
  }
}
// Archivo
class _doc_arc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Fecha
class _doc_fec {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Texto
class _doc_tex {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// Número
class _doc_num {
  
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
// Opcion
class _doc_opc {

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
}
// Figura
class _doc_fig {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
}