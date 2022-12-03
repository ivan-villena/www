// WINDOW
'use strict';

// listado - tabla : []
class lis {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    // aseguro datos
    if( !$api_lis || $api_lis[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_lis[$est];

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        default:        
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];        
          break;
        }        
      }
    }
    else{
      $_ = $_dat ? $_dat : [];
    }
    return $_;
  }
    
  // aseguro : []
  static val( $dat = [] ){

    return obj.val_tip($dat) == 'pos' ? $dat : [ $dat ] ;
  }// convierto : []
  static val_dec( $dat ){
  
    let $_ = $dat;
  
    // elemento : armo listado o convierto a iterable
    if( $dat.constructor && /(NodeList|^HTML[a-zA-Z]*(Element|Collection)$)/.test($dat.constructor.name) ){

      $_ = ( /^HTML[a-zA-Z]*Element/.test($dat.constructor.name) ) ? lis.val($dat) : Array.from( $dat ) ;
    }
    // convierto : {} => []
    else if( typeof($dat) == 'object' ){

      $_=[]; 

      for( const $i in $dat ){ 
        
        $_.push( $dat[$i] ); 
      }
    }
    return $_;
  }

  // punteos, numeraciones, y términos
  static pos(){
  }
  static pos_val( $dat, $ope ){
    let $ = dat.var($dat);
    
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
  static pos_tog( $dat, $ope ){

    let $ = dat.var($dat);

    if( !$dat || !$ope ){
      ele.act('cla_tog',$.lis.children,DIS_OCU); 
    }
    else{
      lis.val_dec($.lis.children).forEach( $ite => {

        if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){

          if( $ite.nextElementSibling ){
            if( 
              ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
              ||
              ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
            ){
              lis.pos_val($ite);
            }
          }
        }
      } );
    }
  }  
  static pos_ver( $dat, $ope ){
    let $ = dat.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $api_app.var.querySelector('[name="val"]').value ){
        // oculto todos
        ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $api_app.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            if( $.ope_val = dat.ver($e.innerHTML,$.ope,$.val) ){
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
          lis.val_dec($.lis.children).forEach( $e => 
            dat.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        ele.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': ele.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': ele.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $api_app.var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = lis.val_dec($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = lis.val_dec($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  // contenedores
  static ite(){      
  }
  static ite_ver( $dat, $ope = 'p:first-of-type', $cla = 'tex-luz' ){
    let $ = dat.var($dat);
    // busco listado
    if( $api_app.var ){
      $.lis = !! $api_app.var.nextElementSibling ? $api_app.var.nextElementSibling : $api_app.var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }
    // ejecuto filtros
    if( $.lis && ( $.ope = $api_app.var.querySelector(`[name="ope"]`) ) && ( $.val = $api_app.var.querySelector(`[name="val"]`) ) ){      
      // elimino marcas anteriores      
      $.lis.querySelectorAll(`li.pos ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );
      // muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {

        // capturo item : li > [.val] (p / a)
        $.ite = ele.val_ver($ite,{'eti':'li'});
        // ejecuto comparacion por elemento selector ( p / a )
        if( !$.val.value || dat.ver($ite.innerText, $.ope.value, $.val.value) ){
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
      if( $.tog[1] && ( $.ico = $api_app.var.querySelector(`.dat_ico.val_tog-${$.tog[1]}`) ) ) doc.val($.ico,$.tog[1]);
      
      // actualizo total
      if( $.tot_val = $api_app.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;           
    }      
  }

  // indice por artículos
  static nav( $dat, $cla = FON_SEL ){

    let $ = { lis : ele.val_ver($dat,{'eti':'nav'}) };

    if( $.lis ){
      // elimino marcas previas
      $.lis.querySelectorAll(
        `ul.lis.nav :is( li.pos.sep, li.pos:not(.sep) > div.doc_val ).${$cla}`
      ).forEach( 
        $e => $e.classList.remove($cla) 
      );

      // controlo el toggle automatico por dependencias
      if( 
        ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
        &&
        ( $dat.classList.contains('dat_ico') || $.dep.classList.contains(DIS_OCU) ) 
      ){
        doc.val($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.dat_ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }// - hago toogle por item
  static nav_tog( $lis ){

    let $={};

    if( $.nav = $lis ? lis.nav_mar($lis) : false ){
      // hago toogles ascendentes
      while( 
        ( $.lis = ele.val_ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('doc_val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('dat_ico') ){                
          doc.val($.ico);
        }                
      }
    }
  }// - marco valor seleccionado
  static nav_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      lis.nav($nav);
    }

    return $nav;
  }// - ejecuto filtros
  static nav_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    lis.ite_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    lis.nav_tog($api_app.var.nextElementSibling);

  }    

  // desplazamiento horizontal x item
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $ = dat.var($dat);

    if( $tip == 'val' ){

      $.lis = $api_app.var.previousElementSibling;

      $.val = $api_app.var.querySelector('[name="val"]');
      $.pos = num.val($.val.value);

      switch( $dat.getAttribute('name') ){
      case 'ini': $.pos = num.val($.val.min);
        break;
      case 'pre': $.pos = $.pos > num.val($.val.min) ? $.pos-1 : $.pos;
        break;
      case 'pos': $.pos = $.pos < num.val($.val.max) ? $.pos+1 : $.pos;
        break;
      case 'fin': $.pos = num.val($.val.max);
        break;
      }
      // valido y muestro item
      $.val.value = $.pos;

      ele.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove(DIS_OCU);
    }
  }    

}