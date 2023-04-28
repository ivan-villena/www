// WINDOW
'use strict';

// listado - tabla : []
class Lis {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = Dat.get_est('lis',$ide,'dat');

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

    return Lis.val($dat) ? $dat : [ $dat ] ;

  }// convierto : {} => []
  static val_cod( $dat ){
  
    let $_ = $dat;
  
    // elemento : armo listado o convierto a iterable
    if( $dat.constructor 
      && 
      /(NodeList|^HTML[a-zA-Z]*(Element|Collection)$)/.test($dat.constructor.name) 
    ){
      $_ = ( /^HTML[a-zA-Z]*Element/.test($dat.constructor.name) ) ? Lis.val_ite($dat) : Array.from( $dat ) ;
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
      $ope['opc'] = Lis.val_ite($ope['opc']);
      if( $ope['opc'].includes('uni') ){
        for( const $pos in $_ ){ 
          $_ = $_[$pos]; 
          break;
        }
      }
    }

    return $_;
  }

  /* -- Controladores -- */  
  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $={};

    switch( $tip ){
    }
    return $_;      
  }

  /* -- Selector de Opciones -- */
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

    Lis.opc_ite($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }// ...<options>
  static opc_ite( $dat, $val, ...$opc ){
    let $_=[], $={};    

    if( $opc.includes('nad') ){
      $.ite = document.createElement('option');
      $.ite.value = ''; 
      $.ite.innerHTML = '{-_-}';
      $_.push($.ite);
    }

    $.val_ide = $opc.includes('ide');

    for( const $ide in $dat ){ 
      $.obj_tip = Obj.val_tip($dat[$ide]); 
      break; 
    }
    $.obj_pos = ( $.obj_tip == 'pos' );

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

  /* -- Barra -- */
  static bar(){
  }// - Desplazamiento
  static bar_ite( $tip, $dat ){
    
    let $ = Dat.var($dat);

    if( $tip == 'val' ){

      $.lis = $App.dom.dat.var.previousElementSibling;

      $.val = $App.dom.dat.var.querySelector('[name="val"]');
      $.pos = Num.val($.val.value);

      switch( $dat.getAttribute('name') ){
      case 'ini': $.pos = Num.val($.val.min);
        break;
      case 'pre': $.pos = $.pos > Num.val($.val.min) ? $.pos-1 : $.pos;
        break;
      case 'pos': $.pos = $.pos < Num.val($.val.max) ? $.pos+1 : $.pos;
        break;
      case 'fin': $.pos = Num.val($.val.max);
        break;
      }
      // valido y muestro item
      $.val.value = $.pos;

      Ele.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove(DIS_OCU);
    }
  }

  /* -- Items -- */
  static pos(){
  }// - posicion
  static pos_val( $dat, $ope ){
    let $ = Dat.var($dat);
    
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

    let $ = Dat.var($dat);

    if( !$dat || !$ope ){
      Ele.act('cla_tog',$.lis.children,DIS_OCU); 
    }
    else{
      Lis.val_cod($.lis.children).forEach( $ite => {

        if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){

          if( $ite.nextElementSibling ){
            if( 
              ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
              ||
              ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
            ){
              Lis.pos_val($ite);
            }
          }
        }
      } );
    }
  }// - Filtro
  static pos_ver( $dat, $ope ){

    let $ = Dat.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      $.lis = $App.dom.dat.var.nextElementSibling;
      // muestro por coincidencias
      if( $.val = $App.dom.dat.var.querySelector('[name="val"]').value ){
        // oculto todos
        Ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $App.dom.dat.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            // valido coincidencia
            $.ope_val = Dat.ope_ver($e.innerHTML,$.ope,$.val) ? $e.classList.remove(DIS_OCU) : $e.classList.add(DIS_OCU);
            $.dd = $e.nextElementSibling;
            while( $.dd && $.dd.nodeName == 'DD' ){
              $.ope_val ? $.dd.classList.remove(DIS_OCU) : $.dd.classList.add(DIS_OCU);
              $.dd = $.dd.nextElementSibling;
            }
          });
        }
        else{
          Lis.val_cod($.lis.children).forEach( $e => 
            Dat.ope_ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        Ele.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': Ele.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': Ele.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $App.dom.dat.var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = Lis.val_cod($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = Lis.val_cod($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  /* -- Dependencias por Titulo -- */
  static dep(){      
  }// - Toggles
  static dep_tog( $dat, $val ){
    
    return Doc.val($dat,$val);
  }// - Filtros
  static dep_ver( $dat, $ope = 'p:first-of-type', $cla = 'tex-luz' ){
    let $ = Dat.var($dat);

    // busco listado
    if( $App.dom.dat.var ){
      $.lis = !! $App.dom.dat.var.nextElementSibling ? $App.dom.dat.var.nextElementSibling : $App.dom.dat.var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }

    // ejecuto filtros
    if( $.lis && ( $.ope = $App.dom.dat.var.querySelector(`[name="ope"]`) ) && ( $.val = $App.dom.dat.var.querySelector(`[name="val"]`) ) ){      
      
      // elimino marcas anteriores
      if( $cla ) $.lis.querySelectorAll(`li.pos ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );

      // 1- muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {
        // capturo item : li > [.val] (p / a)
        $.ite = dom.ver($ite,{'eti':'li'});
        // ejecuto comparacion por elemento selector ( p / a )
        if( !$.val.value || Dat.ope_ver($ite.innerText, $.ope.value, $.val.value) ){
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
      if( $.tog[1] && ( $.ico = $App.dom.dat.var.querySelector(`.fig_ico.ide-val_tog-${$.tog[1]}`) ) ){ 
        Doc.val($.ico,$.tog[1]);
      }
      
      // actualizo total
      if( $.tot_val = $App.dom.dat.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;           
    }      
  }

  /* -- Indice -- */
  static nav( $dat, $cla = FON_SEL ){

    let $ = { lis : dom.ver($dat,{'eti':'nav'}) };

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
        Doc.val($dat);
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

      return Doc.val($lis,$ope);
    }
    else if( $.nav = $lis ? Lis.nav_mar($lis) : false ){
      // hago toogles ascendentes
      while(
        ( $.lis = dom.ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) &&  $.val.classList.contains('app_val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('fig_ico') ){                
          Doc.val($.ico);
        }
      }
    }
  }// - Marcas
  static nav_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      Lis.nav($nav);
    }

    return $nav;
  }// - Filtros
  static nav_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    Lis.dep_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    Lis.nav_tog($App.dom.dat.var.nextElementSibling);

  }

  /* Tabla */
  static tab(){
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

}