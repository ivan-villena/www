// WINDOW
'use strict';

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
    $_app.ope_val.acu.forEach( 
      $ite => ( $.ele = $_app.tab.val.acu.querySelector(`[name="${$ite}"]:checked`) ) && _app_tab.val_acu($.ele) 
    );

  }
  // actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $_app.ope_val.acu : _lis.ite($dat);

    $.dat = $_app.tab.lis;

    // acumulados + listado
    if( $_app.tab.val.acu ){ 

      // actualizo toales acumulados
      _app_val.acu($_app.tab.lis, $_app.tab.val.acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $_app.tab.val.sum ){
        $.tot = [];
        $_app.ope_val.acu.forEach( $acu_ide => {

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

    let $ = _app.var($dat);

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
      $.pos = $dat.classList.contains('pos') ? $dat : $dat.parentElement;
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
      for( const $ide in $_app.ope_val.ver ){

        if( $.ele = $_app.tab.ver.querySelector(`${$_app.ope_val.ver[$ide]}:not([value=""])`) ){
  
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
    
    let $ = _app.var($dat);

    if( !$.var_ide && $ope ) $ = _app.var( $dat = $_app.tab.val.acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $_app.tab.lis.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( _dat.ver($cla,'^^',`${$.cla_ide}-`) ){
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

    let $ = _app.var($dat); 

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

    let $ = _app.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $_app.tab.opc.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $_app.ope_val.acu.forEach( $ver =>{
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
        $.fic = _dat.opc('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $_app.tab.lis.querySelectorAll($_app.tab.cla).forEach( $e => {
          $.htm = '';
          $.ele = { 
            title : false, 
            onclick : false 
          };
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
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.ima') ) ? _ele.mod($.htm,$.ima_ele) : _ele.agr($.htm,$e,'ini');
          }
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