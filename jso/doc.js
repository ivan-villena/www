// WINDOW
'use strict';

// operativas
const DIS_OCU = "dis-ocu";
const FON_SEL = "fon-sel";
const BOR_SEL = "bor-sel";
const ROT_VER = "rotate(0deg)";
const ROT_OCU = "rotate(270deg)";

// documento
class _doc {

  _val = {
    'pos':[],
    'mar':[],
    'ver':[]
  };

  _ope = {

    'val' : {
      
      'ver':{
        'dat':`form[ide="dat"] select[name="dat-dep"]:not([value=""])`,
        'pos':`form[ide="pos"] input[name="ini"]:not([value=""])`,
        'fec':`form[ide="fec"] input[name="ini"]:not([value=""])`
      }
    },
    'est' : {
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
    'tab' : {
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

  constructor(){

    // cargo eventos de teclado
    document.onkeyup = $eve => _doc.ope('inp',$eve);

    // anulo formularios
    document.querySelectorAll('form').forEach( $ele => _ele.ope( $ele, 'eje_agr', 'submit', `evt => evt.preventDefault()` ) );

    // cargo operadores
    if( $$.doc = document.querySelector('main') ){      

      $$.doc.scroll(0,0);
      
      if( $_api._uri.cab ){

        // operadores de dato : tablero + listado + informe + navegador
        if( $_api._uri.cab == 'dat' ){

          ['est','tab'].forEach( $ope => {

            $$[$ope] = {};

            for( const $ide in this._ope[$ope] ){ 

              $$[$ope][$ide] = document.querySelector(this._ope[$ope][$ide]); 
            }
          });
        } 
        // indice por artículo
        else if( $_api._uri.art && ( $$.art_nav = document.querySelector('main > aside > nav[ide="nav"] > ul.lis') ) ){          
          
          _doc_nav.art('ini',$$.art_nav);
        }
      }
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
    }
    return $;
  }  
  // operaciones :  vistas, valores
  static ope( $tip, $dat, $ope, ...$opc ){
    let $={};
    $._tip = $tip.split('_');
    switch( $._tip[0] ){
    // teclas
    case 'inp':
      switch( $dat.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // menú contextual
        if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
          $.men.classList.add(DIS_OCU);
        }// pantallas
        else if( $.win = document.querySelector(`#win:not(.${DIS_OCU}) article header .ico[ide="eje_fin"]`) ){ 
          _doc_art.win();
        }// paneles
        else if( $.pan = document.querySelector(`main > aside > [ide]:not(.${DIS_OCU})`) ){ 
          $.pan.classList.add(DIS_OCU);
        }
        break;
      }
      break;
    // vistas
    case 'tog':
      // elementos del documento
      if( $ope ){

        if( $ope == 'ele' ){

          if( $.nod = $opc[0] ){

            $.nod = $.nod.split('=');
  
            if( !$.nod[1] ){
              $.nod[1] = $.nod[0];
              $.nod[0] = 'eti';
            }
            $.nod_ver = {};
            $.nod_ver[$.nod[0]] = $.nod[1];
            $.nod = _ele.ver($dat,$.nod_ver);
          }
  
          if( $.nod.nodeName && $opc[1] && ( $.dat = $.nod.querySelector($opc[1]) ) ){
  
            $.dat.classList.toggle($opc[2] ? $opc[2] : DIS_OCU);
          }          
        }
      }
      // cambio por icono
      else if( !$dat.value ){

        if( $dat.nodeName != 'DIV' ) $dat = $dat.parentElement;
        $.bot = $dat.querySelector('.ico[ide="ope_tog"]');
        $.sec = $dat.nextElementSibling;
        
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
      else if( ['tod','nad'].includes($dat.value) ){

        if( ( $$.for = _ele.ver($dat,{'eti':"form"}) ) && ( $.lis = $$.for.nextElementSibling ) ){

          if( $$.for.classList.contains('var_lis') ) $.lis = $.lis.parentElement;

          $.rot = ( $dat.value == 'tod' ) ? ROT_VER : ROT_OCU;

          $.lis.querySelectorAll(`.val > .ico[ide="ope_tog"]:not([style*="${$.rot}"])`).forEach( $e => $e.click() );
        }
      }
      break;
    // valores
    case 'val':
      switch( $._tip[1] ){
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
      break;
    // datos
    case 'dat': break;
    }
    return $;
  }
}
// articulos
class _doc_art {

  static _win = document.querySelector('#win');
 
  // pantallas : articulo > cabecera( ico + tit + ocu ) + contenido
  static win( $ide ){

    // muestro ventada por identificador
    if( $ide ){
      // muestro fondo
      this._win.classList.remove(DIS_OCU);
      // oculto articulos
      this._win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // muestro por valor
      if( typeof($ide) == 'string' ) $ide = this._win.querySelector(`article[ide="${$ide}"].${DIS_OCU}`);

      if( $ide ) $ide.classList.remove(DIS_OCU); 
    }
    // oculto
    else{      
      // oculto articulos
      this._win.querySelectorAll(`article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      // oculto fondo
      this._win.classList.add(DIS_OCU);
    }
  }

}
// enlaces
class _doc_nav {  

  // enlaces por tipo
  static ver( $tip, $dat, $ope ){
    
    switch( $tip ){
    // pantalla : #win > article > header + section
    case 'win':
      _doc_art.win($dat);
      break;
    // paneles : main > aside
    case 'pan':
      $$.doc.querySelectorAll(`aside > [ide]:not([ide="${$dat}"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      $$.doc.querySelectorAll(`aside > [ide="${$dat}"]`).forEach( $e => $e.classList.toggle(DIS_OCU) );      
      break;
    // seccion : main > section > article
    case 'sec':
      $$.doc.querySelectorAll(`section > article[ide]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
      $$.doc.querySelectorAll(`section > article[ide="${$dat}"].${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      $$.doc.scroll(0, 0);
      break;      
    }
  }
  // indice por secciones
  static art( $tip, $dat, $ope ){

    let $={};

    switch( $tip ){
    // inicializo 
    case 'ini':
      $.ide = location.toString().split('#');

      if( $dat && $.ide[1] ){          

        if( $.nav = $dat.querySelector(`a[href="#${$.ide[1]}"]`) ){

          // hago toogle por item
          _doc_nav.art('val',$.nav);

          // hago toogles ascendentes
          while( 
            ( $.lis = $.nav.parentElement.parentElement.parentElement ) 
            && ( $.val = $.lis.previousElementSibling ) && ( $.val.nodeName == 'DIV' &&  $.val.classList.contains('val') )
            && ( $.nav = $.val.querySelector('a[href^="#"]') )
          ){
            if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('ico') ){                
              _doc.ope('tog',$.ico);
            }                
          }
        }
      }
      break;
    // click en items
    case 'val':      
      // elimino marcas previas
      ( $.nav = _ele.ver($dat,{'eti':'nav'}) ).querySelectorAll(`.val.${FON_SEL}`).forEach( $e => $e.classList.remove(FON_SEL) );
      
      // valor seleccionado
      if( !$dat.value ){
        // controlo el toggle automatico por dependencias
        if( 
          ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
          && 
          ( $dat.classList.contains('ico') || $.dep.classList.contains(DIS_OCU) ) 
        ){
          _doc.ope('tog',$dat);
        }
        // pinto fondo
        if( !($.bot = $dat.parentElement.querySelector('ico')) || $.bot.style.transform == ROT_VER ) 
          $dat.parentElement.classList.add(FON_SEL);
      }
      else{
        _doc.ope('tog',$dat);
      }        
      break;
    // filtros
    case 'ver':
      $ = _doc_lis.val('ver',$dat,'a[href]');
      break;
    }
  }
  // valores : bar + pes + ope
  static val( $dat, $ope, ...$opc ){
    let $={};

    $.nav = $dat.parentElement;

    $.val_tog = $opc.includes('tog');

    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){

      $.lis = $.nav.nextElementSibling;

      _var.lis( $.lis.children ).forEach( $e => {

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
  // form > fieldsets
  static var( $dat ){    
    if( $dat.nodeName=='LEGEND' ){
      // oculto atributos
      _var.lis( _ele.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
    }
  }
}
// listado []: ul.lis > ite > val[ico + tex] + lis
class _doc_lis {

  // punteos, numeraciones, términos y definiciones      
  static ite( $tip, $dat, $ope ){
    let $=_doc.var($dat);
    // tog de lista por click en item
    if( $tip == 'tex' ){

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
    // operadores
    else if( !!$$.for ){
      $.lis = $$.for.nextElementSibling;
      switch( $tip ){
      case 'tog':
        if( !$dat || !$dat.value ){
          _ele.val('cla_tog',$.lis.children,DIS_OCU); 
        }
        else{
          _var.lis($.lis.children).forEach( $ite => {

            if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){
              if( $ite.nextElementSibling ){
                if( 
                  ( $dat.value == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
                  ||
                  ( $dat.value == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
                ){
                  _doc_lis.ite('tex',$ite);
                }
              }
            }
          } );
        }
        break;
      case 'ver':
        // filtro por valor textual        
        if( !$ope ){
          // muestro por coincidencias
          if( $.val = $$.for.querySelector('[name="val"]').value ){
            // oculto todos
            _ele.val('cla_agr',$.lis.children,DIS_OCU); 

            $.ope = $$.for.querySelector('[name="ope"]').value;
            
            if( $.lis.nodeName == 'DL' ){
              $.lis.querySelectorAll(`dt`).forEach( $e => {
                if( $.ope_val = _var.ver($e.innerHTML,$.ope,$.val) ){
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
              _var.lis($.lis.children).forEach( $e => 
                _var.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
              );
            }
          }
          else{
            _ele.val('cla_eli',$.lis.children,DIS_OCU);
          }
        }
        else{
          switch( $ope ){
          case 'tod': _ele.val('cla_eli',$.lis.children,DIS_OCU); break;
          case 'nad': _ele.val('cla_agr',$.lis.children,DIS_OCU); break;
          }
        }
        // actualizo cuenta
        if( $.tot = $$.for.querySelector('[name="tot"]') ){
          if( $.lis.nodeName == 'DL' ){
            $.tot.innerHTML = _var.lis($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
          }else{
            $.tot.innerHTML = _var.lis($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
          }
        }     
        break;
      }
    }
  }
  // valores con titulo
  static val( $tip, $dat, $ope, ...$opc ){
    
    let $=_doc.var($dat);
    // busco listado
    if( $$.for && $$.for.nextElementSibling ) $.lis = $$.for.nextElementSibling;

    switch( $tip ){
    // filtro por titulo
    case 'ver':
      // operador por defecto
      if( !$ope ) $ope = ':first-of-type:not(.ico)';

      // ejecuto filtros
      if( $.lis && ( $.ope = $$.for.querySelector(`[name="ope"]`) ) && ( $.val = $$.for.querySelector(`[name="val"]`) ) ){

        console.log( 'ver' );
        
        // clase de la seleccion
        $opc[0] = !$opc[0] ? 'let-tit' : $opc[0];
        
        // elimino marcas anteriores
        $.lis.querySelectorAll(`li.ite > .val > ${$ope}.${$opc[0]}`).forEach( $ite => $ite.classList.remove($opc[0]) );
        
        // muestro u oculto por coincidencias
        $.lis.querySelectorAll(`li.ite > .val > ${$ope}`).forEach( $ite => {
          if( $.ite = $ite.parentElement.parentElement ){

            if( !$.val.value || _var.ver($ite.innerText, $.ope.value, $.val.value) ){
              $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
              !!$.val.value && $ite.classList.add($opc[0]);                
            }
            else if( !$.ite.classList.contains(DIS_OCU) ){
              $.ite.classList.add(DIS_OCU);
            }
          }
        } );
        
        // por cada item mostrado, muestro ascendentes
        $.tot = 0;
        if( $.val.value ){
          $.lis.querySelectorAll(`li.ite:not(.${DIS_OCU}) > .val`).forEach( $i => {
            $.tot++;
            $.val = $i.parentElement;
            while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI'  ){
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
        if( $.tog[1] && ( $.ico = $$.for.querySelector(`.ico[value="${$.tog[1]}"]`) ) ){

          /^a/i.test($ope) ? _doc_nav.art('val',$.ico) : _doc.ope('tog',$.ico);
        }            
        
        // actualizo total
        if( $.tot_val = $$.for.querySelector(`[name="tot"]`) ){

          $.tot_val.innerHTML = $.tot;
        }            
      }       
      break;
    }   
  }
  // desplazamiento horizontal x item
  static bar( $tip, $dat ){
    
    let $=_doc.var($dat);

    if( $tip == 'val' ){

      $.lis = $$.for.previousElementSibling;
      $.val = $$.for.querySelector('[name="val"]');
      $.pos = _num.val($.val.value);

      if( $dat.classList.contains('ico') ){
        switch( $dat.value ){
        case 'ini': $.pos = _num.val($.val.min);
          break;
        case 'pre': $.pos = $.pos > _num.val($.val.min) ? $.pos-1 : $.pos;
          break;
        case 'pos': $.pos = $.pos < _num.val($.val.max) ? $.pos+1 : $.pos;
          break;
        case 'fin': $.pos = _num.val($.val.max);
          break;
        }
      }
      // valido y muestro item
      $.val.value = $.pos;

      _ele.val('cla_agr',$.lis.querySelectorAll(`li[pos]:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li[pos="${$.pos}"]`) ) $.ite.classList.remove(DIS_OCU);
    }
  }
}
// datos : valores + seleccion + opciones ( esq + est + atr + val ) + imagen( ide + fic )
class _doc_dat {
    
  // formularios con operaciones de la base : alta, baja, modificacion por tabla-informe  
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
  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_doc.var($dat);

    $ = _var.ide($dat,$);
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
  // opciones : esquema . estructura . atributos . valores
  static opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_doc.var($dat);

    switch( $tip ){
    case 'est':
      if( $ope ) $opc = [ $ope, ...$opc ];

      $.dep = $dat.parentElement.querySelector('.dep');
      
      // elimino selector 
      $.opc = $.dep.querySelector('select');
      $.opc.querySelectorAll('option').forEach( $ite => $.opc.removeChild($ite) );
      $.opc.dataset.esq = '';
      $.opc.dataset.est = '';
      
      if( $dat.value ){

        $.dat = $dat.options[$dat.selectedIndex];
        
        // identificadores
        $ = _var.ide( ($.dat_ide = $.dat.getAttribute('dat')) ? $.dat_ide : $.dat.value, $);
        $.opc.dataset.esq = $.esq;
        $.opc.dataset.est = $.est;

        _eje.val(['_dat::var', [ `_${$.esq}.${$.est}` ] ], $e => {

          $.val = {};

          $.dep.appendChild( _doc_opc.ope('val',$e,$.opc,'nad','ide') );
        });
      }     
      ///////////////////////////////////////////////////////////////
      // reinicio relaciones ( selectores paralelos por _dat + _atr )
      if( $opc.includes('rel') ){
        $.atr = $dat.parentElement.parentElement.dataset.atr;
        $.gru = $dat.parentElement.parentElement.parentElement.parentElement;
        $.rel = $.gru.querySelectorAll(`.dat > select:not([name="${$.atr}"],[value=""])`);
        $.rel.forEach( $rel => {
          $rel.value = '';
          $.dep = $rel.parentElement.nextElementSibling;
          if( $.dep ){
            $.dep.classList.add(DIS_OCU);
            $.dep.querySelectorAll(`select:not(.${DIS_OCU})`).forEach( $dep =>{ 
              $dep.value='';
              $dep.classList.add(DIS_OCU); 
            });
          }
        });      
      }         
      break;
    }
    
    return $_;
  }
  // imagen : identificadores + ficha
  static ima( $tip, $dat, $ope, ...$opc ){
    let $_="", $=_doc.var($dat);

    switch( $tip ){
    case 'ide':
      if( $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.getAttribute(`${$ope.esq}-${$ope.est}`) ) ){

        if( !$ope.fic ) $ope.fic = _var.val('ima', $ope.esq, $ope.est );
  
        $_ = _doc.ima($ope.fic.esq, $ope.fic.est, _dat.var($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
      }
      break;
    case 'fic': 
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
        
        ['esq','est','atr'].forEach( $i => $[$i] = $ite.dataset[$i] );        
        
        if( $.val = $.dat[$.est] ){
          $.ima = $ite.dataset.ima.split('.');

          _ele.mod( 
            _doc.ima( $.ima[0], $.ima[1], _dat.var($.esq,$.est,$.val)[$.atr], {'class':`tam-2`} ), 
            $ite, '[ima]'
          );
        }
        else{
          _ele.eli($ite,'[ima]');          
        }
      });        
      break;
    }
    
    return $_;
  }

}
// valores : acumulados, sumatorias, filtros, cuentas
class _doc_val {

  // filtros : dato + variables
  static ver( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);

    $.cla_ide = `_val${ !! $opc[0] ? `-${$opc[0]}` : ''}`;

    _ele.val('cla_eli', $dat.filter( $e => $e.classList.contains($.cla_ide) ), $.cla_ide);

    _ele.val('cla_eli', $dat.filter( $e => $e.classList.contains(`${$.cla_ide}-bor`) ), `${$.cla_ide}-bor`);

    $$.for = $ope.querySelector(`form[ide="${$tip}"]`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'dat' ){

      // actualizo dependencia
      if( 
        ( $.dat_ide = $$.for.querySelector(`[name="est"]`) ) && ( $.dat_val = $$.for.querySelector(`[name="val"]`) )
      ){
        if( $.dat_ide.value && $.dat_val.value ){
          
          $ = _var.ide($.dat_ide.value,$);
        
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
    }
    // listado : posicion + fecja
    else if( $tip=='pos' || $tip=='fec' ){
      
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
  // acumulados : 
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

      _doc_dat.ima('fic', $val, $.sum);
      
    });
  }
  // cuentas
  static cue( $tip, $dat, $ope, ...$opc ){
    let $=_doc.var($dat);

    switch( $tip ){        
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
    // filtro en valores finales por tabla
    case 'ver':

      $.ope = $$.for.querySelector('[name="ope"]').value;
      $.val = $$.for.querySelector('[name="val"]').value;
      $.lis = $$.for.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll('tr.dis-ocu').forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( _var.ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
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
// tableros : seccion + posicion
class _doc_tab {

  // procesos : inicializo, actualizo 
  static act( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    // inicializo operadores: opciones, posicion, filtros
    case 'dat':

      if( !$$.tab.cla ){
        $$.tab.cla = '.pos';
      }
      // inicializo opciones
      $$.tab.ide = $$.tab.dat.getAttribute('tab');
            
      ['opc'].forEach( $ope => {
        
        if( $$.tab[$ope] ){

          $$.tab[$ope].querySelectorAll(`form[ide] [onchange*="_doc_tab."]:is( input:checked, select:not([value=""]) )`).forEach( $inp => 

            _doc_tab[$ope](`${_ele.ver($inp,{'eti':`form`}).getAttribute('ide')}`, $inp )
          );
        }
      });
      // marco posicion principal
      _doc_tab.val('pos');

      // actualizo opciones
      Object.keys($_doc._val).forEach( $ite =>
        ( $.ele = $$.tab.acu.querySelector(`[name="${$ite}"]:checked`) ) && _doc_tab.val('acu',$.ele) 
      );      
      break;
    // actualizo valores: acumulados, sumatorias, fichas, listado
    case 'val': 
      $dat = !$dat ? Object.keys($_doc._val) : _var.ite($dat);

      $.dat = $ope ? $ope : $$.tab.dat;

      // actualizo valores acumulados
      if( $ope = $$.tab.acu ) $.tot = _doc_val.acu('act',$.dat,$ope,...$dat );
      
      // actualizo sumatorias
      if( $ope = $$.tab.sum ) _doc_val.sum($.tot, $ope);

      // fichas del tablero
      if( ( $ope = $$.tab.pos ) && ( $.ima = $ope.querySelector(`[name="ima"]`) ) ){

        $.ope = [];
        $dat.forEach( $ide => ( $.val = $ope.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );

        if( $.ope.length > 0 ) _doc_tab.opc('pos',$.ima);
      }

      // actualizo valores de lista
      if( $ope = $$.est.acu ){
        // actualizo acumulados
        if( !$ope.querySelector(`[name="tod"]:checked`) ) _doc_est.val('acu');
        // ejecuto filtros + actualizo totales      
        if( $$.est.ver ) _doc_est.ver('val');
      }    
      break;
    }
  }
  // valores : acumulados( posicion + marcas + filtros )
  static val( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    case 'acu': 
    
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
      _doc_tab.act('val',$.var_ide);

      break;        
    // selecciono por posicion principal
    case 'pos':
      if( $_hol && $_hol._dat && ( $.kin = $_hol._dat.kin ) ){
        $$.tab.dat.querySelectorAll(`.pos[hol-kin="${$.kin}"], .pos[hol-kin="${$.kin}"] [pos][hol-kin="${$.kin}"]`).forEach( $e => {

          $e.classList.add(`_val-pos`);
          $e.classList.add(`_val-pos-bor`);
        });
        // actualizo totales
        _doc_tab.act('val','pos');
      }        
      break;
    // selecciono por marcas del tablero
    case 'mar':
      if( $$.tab.acu && $$.tab.acu.querySelector(`[name="mar"]:checked`) ){
        
        $.pos = $dat.getAttribute('pos') ? $dat : $dat.parentElement;// desde elemento del tablero / lista  

        if( !$.pos.getAttribute('tab') ){

          $.pos.classList.toggle(`_val-mar`);
          $.pos.classList.toggle(`_val-mar-bor`);
        }
        // actualizo totales
        _doc_tab.act('val','mar');
      }        
      break;
    // selecciono por filtros
    case 'ver': 
      for( const $ide in $_doc._ope.val.ver ){

        if( $.ele = $$.tab.ver.querySelector($_doc._ope.val.ver[$ide]) ){

          _doc_tab.ver($ide,$.ele,$$.tab.dat);

          break;
        } 
      }
      _doc_tab.act('val','ver',$$.tab.dat);
      break;              
    }
  }
  // filtros : por dato, por variables ( posicion + fecha )
  static ver( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    if( $tip == 'val' ){

      console.log( 'estoy en val: revisar la invocacion de este proceso' );

      for( const $ide in $_doc._ope.val.ver ){

        if( $.ele = $$.tab.ver.querySelector($_doc._ope.val.ver[$ide]) ){

          _doc_tab.ver($ide,$.ele,$$.tab.dat);

          break;
        } 
      }
    }
    else{
      // ejecuto filtros por tipo : pos, fec
      _doc_val.ver( $tip, _var.lis( $$.tab.dat.querySelectorAll(`${$$.tab.cla}`)), $$.tab.ver, 'ver');
    }
    // actualizo calculos + vistas( fichas + items )
    _doc_tab.act('val','ver',$$.tab.dat);

  }
  // opciones : posicion ( borde + color + imagen + texto ) + secciones ( borde + fondo )
  static opc( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    // secciones : bordes + colores + imagen + ...
    case 'sec':
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
      break;
    // posiciones : borde + color + imagen + texto + numero + fecha
    case 'pos':
      $ope = $$.tab.pos;
      $.var_ide = $.var_ide.split('_')[0];
      if( $.var_ide != 'bor' ){
        // aseguro selector
        if( !$dat.options  ){
          $dat = $ope.querySelector(`[name="${$.var_ide}"]`);
        }
        // opciones por valores
        $[$.var_ide] = {};
        Object.keys($_doc._val).forEach( $ver =>{
          if( $[$.var_ide][$ver] = $ope.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
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

          $ = _var.ide($dat.value,$);

          $.col = _var.val('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

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
          $ = _var.ide($dat.value,$);
          
          // busco valores de ficha
          $.fic = _var.val('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

          // actualizo por opciones
          $$.tab.dat.querySelectorAll($$.tab.cla).forEach( $e => {
            $.htm = '';
            if( $.ima.pos || $.ima.ver || $.ima.mar ){

              if( $.ima.pos && $e.classList.contains('_val-pos') ){ 
                $.htm = _doc_dat.ima('ide',$e,$);
              }
              else if( $.ima.mar && $e.classList.contains('_val-mar') ){ 
                $.htm = _doc_dat.ima('ide',$e,$);
              }
              else if( $.ima.ver && $e.classList.contains('_val-ver') ){ 
                $.htm = _doc_dat.ima('ide',$e,$);
              }
            }// todos
            else{
              $.htm = _doc_dat.ima('ide',$e,$);
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

          $ = _var.ide($dat.value,$);

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
      break;
    }
  }
}
// estructuras : table + columas + titulo + dato + detalles
class _doc_est {

  // operadores : genero listado
  static ope( $tip, $dat, $ele, ...$opc ){
    let $ = {};

    if( $tip == 'val' ){
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
    }

    return $.tab;
  }
  // actualizo : acumulado + valores
  static act( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    // actualizo acumulado
    case 'dat':
      if( $ope = $$.est.acu ){

        if( $.ele = $ope.querySelector(`[name="tod"]`) ) _doc_est.val('tod',$.ele);
      }
      break;
    // valores : acumulado + cuentas + descripciones
    case 'val':
      if( !$dat && $$.est.dat ) $dat = $$.est.dat;

      // actualizo total
      if( $$.est.acu ){
        
        if( $.tot = $$.est.acu.querySelector('[name="cue"]') ) $.tot.innerHTML = _doc_est.val('cue',$dat);
      }
      // actualizo cuentas
      if( $$.est.cue ){

        _doc_val.cue('act',$dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`),$$.est.cue);
      }
      // actualizo descripciones
      if( $$.est.des ){

        $$.est.des.querySelectorAll(`[name]:checked`).forEach( $e => _doc_est.des('val',$e) );
      }    
      break;
    }
  }
  // valores
  static val( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    case 'tod':
      // acumulados
      if( $$.est.acu ){
        // ajusto controles acumulados
        Object.keys($_doc._val).forEach( $i => {

          if( $.val = $$.est.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }
      // muestro todos
      if( $dat.checked  ){

        _ele.val('cla_eli',$$.est.dat.querySelectorAll(`tbody tr[pos].${DIS_OCU}`),DIS_OCU);

      }// o por acumulados
      else if( $$.est.acu ){        

        _doc_est.val('acu');
      }
      // ejecuto filtros
      if( $$.est.ver ){

        _doc_est.ver('val');
      }
      // actualizo total, cuentas y descripciones
      _doc_est.act('val',$$.est.dat);

      break;
    case 'acu':

      if( ( $.esq = $$.est.dat.dataset.esq ) && ( $.est = $$.est.dat.dataset.est ) ){

        // oculto todos los items de la tabla
        _ele.val('cla_agr',$$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`),DIS_OCU);

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
      break;
    case 'cue':

      return ( $dat ? $dat : $$.est.dat ).querySelectorAll(`tr[pos]:not(.${DIS_OCU})`).length;
      break;
    }
  }
  // filtros : datos + variables ( posicion )
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
      else if( $ope = $$.est.ver ){        
        // ejecuto acumulados
        if( $$.est.acu ){
          // muestro todos
          if( $$.est.acu.querySelector(`[name="tod"]:checked`) ){

            _ele.val('cla_eli',$$.est.dat.querySelectorAll(`tbody tr[pos].${DIS_OCU}`),DIS_OCU);
          }// muestro solo acumulados
          else{
            _doc_est.val('acu');
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
          _doc_val.ver( $tip, _var.lis( $$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`) ), $ope, 'ver');

          // oculto valores no seleccionados
          $$.est.dat.querySelectorAll(`tbody tr[pos]:not(._val-ver,.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );      
        }
      }
      // actualizo total, cuentas y descripciones
      _doc_est.act('val',$$.est.dat);
    }
  }
  // columnas : toggles + filtros
  static atr( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    case 'tog':
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

          _doc_est.atr('tog',$e);
        });
      }
      break;            
    case 'ver':
      switch( $ope ){
      case 'nom': break;
      // por tipo
      default: break;
      }
      break;
    }
  }
  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static des( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);      

    switch( $tip ){
    case 'val':
      $ope  = $$.for.getAttribute('ide');
      $.esq = $$.for.dataset.esq;
      $.est = $$.for.dataset.est;
      $.atr = $.var_ide;
      // oculto todos
      $$.est.dat.querySelectorAll(
        `tbody tr[data-ope="${$ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
      ).forEach( 
        $ite => $ite.classList.add(DIS_OCU)
      );
      // muestro titulos y lecturas para los que no están ocultos
      if( $dat.checked ){

        $$.est.dat.querySelectorAll(`tbody tr[pos]:not(.${DIS_OCU})`).forEach( $ite => {

          if( ( $.val = _dat.var($.esq,$.est,$ite.getAttribute(`${$.esq}-${$.est}`)) ) && $.val[$.atr] ){

            $.ide = ( $ope == 'des' ) ? $ite.getAttribute(`${$.esq}-${$.est}`) : $.val[$.atr];            

            $$.est.dat.querySelectorAll(
              `tbody tr[data-ope="${$ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
            ).forEach( 
              $e => $e.classList.remove(DIS_OCU)
            );
          }
        });
      }      
      break;
    case 'ver':
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
      break;
    }
  }    

}
// objetos
class _doc_obj {

  // operadores
  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = _var.tip($v);

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
// ejecuciones
class _doc_eje {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

}
// elementos
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
        _var.lis($dat.childNodes).forEach( $e =>{
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
// archivos
class _doc_arc {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// fechas
class _doc_fec {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// textos
class _doc_tex {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }    
}
// números
class _doc_num {
  
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
// opciones
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
// figuras
class _doc_fig {

  static ope( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
}