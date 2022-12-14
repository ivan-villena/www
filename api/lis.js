// WINDOW
'use strict';

// listado - tabla : []
class api_lis {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('lis',$ide,'dat');

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
    
    return $_;
  }

  /* Valores del Entorno
  */
  static val( $dat = [], $ope ){
    let $_ = $dat;    
    // valido tipo
    if( !$ope ){
      $_ = Array.isArray($dat);
    }
    // operaciones
    else if( $ope.tip ){
      switch( $ope.tip ){
      }
    }

    return $_;
  }// aseguro iteraciones : []
  static val_ite( $dat = [] ){

    return api_lis.val($dat) ? $dat : [ $dat ] ;

  }// convierto : {} => []
  static val_cod( $dat ){
  
    let $_ = $dat;
  
    // elemento : armo listado o convierto a iterable
    if( $dat.constructor 
      && 
      /(NodeList|^HTML[a-zA-Z]*(Element|Collection)$)/.test($dat.constructor.name) 
    ){
      $_ = ( /^HTML[a-zA-Z]*Element/.test($dat.constructor.name) ) ? api_lis.val_ite($dat) : Array.from( $dat ) ;
    }
    // convierto : {} => []
    else if( typeof($dat) == 'object' ){

      $_=[]; 

      for( const $i in $dat ){ 
        
        $_.push( $dat[$i] ); 
      }
    }
    return $_;
  }// proceso estructura
  static val_est( $dat, $ope ){
    let $_ = $dat;

    // nivelacion de clave : num + ide + lis
    if( !!($ope['niv']) ){ 
      $.key = $ope['niv'];
      $.tip = typeof($ope['niv']);
      // key-pos = numérica : []
      if( $.tip == 'number' ){ 
        $_ = [];
        $.k = parseInt($.key);
        for( const $i in $dat ){ 
          $_[$.k++]=$dat[$i]; 
        }
      }
      // key-ide = Literal : {}
      else if( $.tip=='string' ){ 
        $_ = {}; 
        $.k = $.key.split('(.)');
        for( const $i in $dat ){
          $.ide=[];
          for( let $ide of $.k ){ 
            $.ide.push($dat[$i][$ide]); 
          }
          $_[$.ide.join('(.)')] = $dat[$i];
        }
      }
      // keys-[1-7] => [ [ [ [],[ {-_-} ],[], ] ] ]
      else if( Array.isArray($.key) ){ 
        $_ = {};
        $.k = $.key;
        switch( $k.length ){
        case 0: $_ = $dat; break;
        case 1: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]]=$d; } break;
        case 2: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]]=$d; } break;
        case 3: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]]=$d; } break;
        case 4: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]]=$d; } break;
        case 5: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]]=$d; } break;
        case 6: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]][$d[$.k[5]]]=$d; } break;
        case 7: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]][$d[$.k[5]]][$d[$.k[6]]]=$d; } break;
        }
      }
    }

    // devuelvo valor unico : objeto
    if( !!($ope['opc']) ){
      $ope['opc'] = api_lis.val_ite($ope['opc']);
      if( $ope['opc'].includes('uni') ){
        for( const $pos in $_ ){ 
          $_ = $_[$pos]; 
          break;
        }
      }
    }

    return $_;
  }


  /* -- Barra -- */
  static bar(){
  }// - Desplazamiento
  static bar_ite( $tip, $dat ){
    
    let $ = sis_app.var($dat);

    if( $tip == 'val' ){

      $.lis = $dom.app.var.previousElementSibling;

      $.val = $dom.app.var.querySelector('[name="val"]');
      $.pos = api_num.val($.val.value);

      switch( $dat.getAttribute('name') ){
      case 'ini': $.pos = api_num.val($.val.min);
        break;
      case 'pre': $.pos = $.pos > api_num.val($.val.min) ? $.pos-1 : $.pos;
        break;
      case 'pos': $.pos = $.pos < api_num.val($.val.max) ? $.pos+1 : $.pos;
        break;
      case 'fin': $.pos = api_num.val($.val.max);
        break;
      }
      // valido y muestro item
      $.val.value = $.pos;

      $dom.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove(DIS_OCU);
    }
  }    

  /* -- Indice -- */
  static nav( $dat, $cla = FON_SEL ){

    let $ = { lis : $dom.ver($dat,{'eti':'nav'}) };

    if( $.lis ){
      // elimino marcas previas
      $.lis.querySelectorAll(
        `ul.lis.nav :is( li.pos.sep, li.pos:not(.sep) > .app_val ).${$cla}`
      ).forEach( 
        $e => $e.classList.remove($cla) 
      );

      // controlo el toggle automatico por dependencias
      if( 
        ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
        &&
        ( $dat.classList.contains('fig_ico') || $.dep.classList.contains(DIS_OCU) ) 
      ){
        sis_app.val($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.fig_ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }// - Toggles
  static nav_tog( $lis, $ope ){

    let $={};

    if( $ope ){

      return sis_app.val($lis,$ope);
    }
    else if( $.nav = $lis ? api_lis.nav_mar($lis) : false ){
      // hago toogles ascendentes
      while(
        ( $.lis = $dom.ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) &&  $.val.classList.contains('app_val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('fig_ico') ){                
          sis_app.val($.ico);
        }
      }
    }
  }// - Marcas
  static nav_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      api_lis.nav($nav);
    }

    return $nav;
  }// - Filtros
  static nav_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    api_lis.dep_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    api_lis.nav_tog($dom.app.var.nextElementSibling);

  }

  /* -- Posicion -- */
  static pos(){
  }// - Items
  static pos_val( $dat, $ope ){
    let $ = sis_app.var($dat);
    
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
  }// - Toggles
  static pos_tog( $dat, $ope ){

    let $ = sis_app.var($dat);

    if( !$dat || !$ope ){
      $dom.act('cla_tog',$.lis.children,DIS_OCU); 
    }
    else{
      api_lis.val_cod($.lis.children).forEach( $ite => {

        if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){

          if( $ite.nextElementSibling ){
            if( 
              ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
              ||
              ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
            ){
              api_lis.pos_val($ite);
            }
          }
        }
      } );
    }
  }// - Filtro
  static pos_ver( $dat, $ope ){

    let $ = sis_app.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      $.lis = $dom.app.var.nextElementSibling;
      // muestro por coincidencias
      if( $.val = $dom.app.var.querySelector('[name="val"]').value ){
        // oculto todos
        $dom.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $dom.app.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            // valido coincidencia
            $.ope_val = sis_dat.val($e.innerHTML,$.ope,$.val) ? $e.classList.remove(DIS_OCU) : $e.classList.add(DIS_OCU);
            $.dd = $e.nextElementSibling;
            while( $.dd && $.dd.nodeName == 'DD' ){
              $.ope_val ? $.dd.classList.remove(DIS_OCU) : $.dd.classList.add(DIS_OCU);
              $.dd = $.dd.nextElementSibling;
            }
          });
        }
        else{
          api_lis.val_cod($.lis.children).forEach( $e => 
            sis_dat.val($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        $dom.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': $dom.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': $dom.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $dom.app.var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = api_lis.val_cod($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = api_lis.val_cod($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  /* -- Contenedor -- */
  static dep(){      
  }// - Toggles
  static dep_tog( $dat, $val ){
    
    return sis_app.val($dat,$val);
  }// - Filtros
  static dep_ver( $dat, $ope = 'p:first-of-type', $cla = 'tex-luz' ){
    let $ = sis_app.var($dat);

    // busco listado
    if( $dom.app.var ){
      $.lis = !! $dom.app.var.nextElementSibling ? $dom.app.var.nextElementSibling : $dom.app.var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }

    // ejecuto filtros
    if( $.lis && ( $.ope = $dom.app.var.querySelector(`[name="ope"]`) ) && ( $.val = $dom.app.var.querySelector(`[name="val"]`) ) ){      
      
      // elimino marcas anteriores
      if( $cla ) $.lis.querySelectorAll(`li.pos ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );

      // 1- muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {
        // capturo item : li > [.val] (p / a)
        $.ite = $dom.ver($ite,{'eti':'li'});
        // ejecuto comparacion por elemento selector ( p / a )
        if( !$.val.value || sis_dat.val($ite.innerText, $.ope.value, $.val.value) ){
          // oculto/mustro item
          $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
          // agrego brillo
          if( !!$cla && !!$.val.value ) $ite.classList.add($cla);
        }
        else{          
          $.ite.classList.add(DIS_OCU);
        }
      });
      
      // 2- por cada item mostrado, muestro ascendentes
      $.tot = 0;
      if( $.val.value ){
        $.lis.querySelectorAll(`li.pos:not(.${DIS_OCU}) ${$ope}`).forEach( $ite => {
          $.tot ++;
          $.val = $ite.parentElement.parentElement;
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
      if( $.tog[1] && ( $.ico = $dom.app.var.querySelector(`.fig_ico.ide-val_tog-${$.tog[1]}`) ) ){ 
        sis_app.val($.ico,$.tog[1]);
      }
      
      // actualizo total
      if( $.tot_val = $dom.app.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;           
    }      
  }

}