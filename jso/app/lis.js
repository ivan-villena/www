// WINDOW
'use strict';

// Listado
class _app_lis {

  // punteos, numeraciones, y términos
  static ite(){
  }
  static ite_val( $dat, $ope ){
    let $ = _app.var($dat);
    
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

    let $ = _app.var($dat);

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
    let $ = _app.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $_app.ope.var.querySelector('[name="val"]').value ){
        // oculto todos
        _ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $_app.ope.var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            if( $.ope_val = _dat.ver($e.innerHTML,$.ope,$.val) ){
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
            _dat.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
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
    let $ = _app.var($dat);
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
        if( !$.val.value || _dat.ver($ite.innerText, $.ope.value, $.val.value) ){
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
      if( $.tog[1] && ( $.ico = $_app.ope.var.querySelector(`.ico.val_tog-${$.tog[1]}`) ) ) _app.val($.ico,$.tog[1]);
      
      // actualizo total
      if( $.tot_val = $_app.ope.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;           
    }      
  }

  // desplazamiento horizontal x item
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $ = _app.var($dat);

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