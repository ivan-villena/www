// WINDOW
'use strict';
// Tablero
class tab {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    // aseguro datos
    if( !$api_tab || $api_tab[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_tab[$est];

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
  }// inicializo : opciones, posicion, filtros
  static lis_ini( $cla ){
    let $={};

    // clase por posicion
    $api_app.tab.cla = '.pos.ope';
    $api_app.tab.ide = $api_app.tab.lis.classList[1];

    // inicializo opciones
    ['sec','pos'].forEach( $ope => {
      
      if( $api_app.tab[$ope] ){

        $api_app.tab[$ope].querySelectorAll(`form[class*="ide-"] [onchange*="tab."]:is( input:checked, select:not([value=""]) )`).forEach( 

          $inp => tab[`opc_${$ope}`]( $inp )
        );
      }
    });

    // marco posicion principal
    tab.val('pos');

    // actualizo opciones
    $api_app.val.acu.forEach( 
      $ite => ( $.ele = $api_app.tab.val.acu.querySelector(`[name="${$ite}"]:checked`) ) && tab.val_acu($.ele) 
    );

    // inicializo operador por aplicacion
    if( !!$cla ){

      // secciones y posiciones por aplicacion
      ['sec','pos'].forEach( $ope => {
        if( $api_app.tab[$ope] ){
          $.eje = `tab_${$ope}`;
          $api_app.tab[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$.cla}.${$.eje}"]`).forEach(

            $inp => $cla[$.eje] && $cla[$.eje]( $inp )
          );
        }
      });
      
      // atributos
      if( $api_app.tab.atr ){
        $api_app.tab.atr.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
          
          $.eje = `tab_${$for.classList[0].split('-')[2]}`;

          $for.querySelectorAll(`[name][onchange*="${$.cla}.${$.eje}"]`).forEach( 

            $inp => !!$cla[$.eje] && $cla[$.eje]( $inp )
          );
        });
      }
    }
  }// actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static lis_act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $api_app.val.acu : lis.val($dat);

    $.dat = $api_app.tab.lis;

    // acumulados + listado
    if( $api_app.tab.val.acu ){ 

      // actualizo toales acumulados
      dat.ope_acu($api_app.tab.lis, $api_app.tab.val.acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $api_app.tab.val.sum ){
        $.tot = [];
        $api_app.val.acu.forEach( $acu_ide => {

          if( $api_app.tab.val.acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$api_app.tab.lis.querySelectorAll(`[class*="_val-${$acu_ide}-"]`) );
          }
        });
        dat.ope_sum($.tot, $api_app.tab.val.sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$api_app.est.val.acu.querySelector(`[name="tod"]:checked`) ) est.lis_val_acu();

      // -> ejecuto filtros + actualizo totales
      if( $api_app.est.ver ) est.lis_ver();
    }

    // fichas del tablero
    if( ( $api_app.tab.pos ) && ( $.ima = $api_app.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $api_app.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) tab.pos($.ima);
    }

    // actualizo cuentas
    if( $api_app.tab.val.cue ){

      dat.ope_cue('act', $api_app.tab.lis.querySelectorAll(`${$api_app.tab.cla}[class*=_val-]:is([class*=_bor],[class*=_act])`), $api_app.tab.val.cue );
    }
  }  
  // Valores
  static val( $tip, $dat ){

    let $ = dat.var($dat);

    switch( $tip ){
    case 'pos': 
      if( $_hol && $_hol.val && ( $.kin = $_hol.val.kin ) ){

        $api_app.tab.lis.querySelectorAll(`${$api_app.tab.cla}[data-hol_kin="${$.kin}"]`).forEach( $e => {
          $e.classList.add(`_val-pos-`);
          if( $api_app.tab.val.acu && $api_app.tab.val.acu.querySelector(`[name="pos"]:checked`) ){
            $e.classList.add(`_val-pos_bor`);
          }
        });
      }
      break;
    case 'mar':
      $.pos = $dat.classList.contains('pos') ? $dat : $dat.parentElement;
      // si no es un posicion de tablero
      if( !$.pos.classList.contains('tab') ){

        $.pos.classList.toggle(`_val-mar-`);
        // marco bordes
        if( $api_app.tab.val.acu ){
          if( $.pos.classList.contains(`_val-mar-`) && $api_app.tab.val.acu.querySelector(`[name="mar"]:checked`) ){
            $.pos.classList.add(`_val-mar_bor`);
          }
          else if( !$.pos.classList.contains(`_val-mar-`) && $.pos.classList.contains(`_val-mar_bor`) ){
            $.pos.classList.remove(`_val-mar_bor`);
          }
        }
      }
      break;
    case 'ver':
      for( const $ide in $api_app.val.ope_ver ){

        if( $.ele = $api_app.tab.ver.querySelector(`${$api_app.val.ope_ver[$ide]}:not([value=""])`) ){
  
          tab.ver($ide,$.ele,$api_app.tab.lis);

          break;
        }
      }
      break;
    case 'opc':
      // las 
      break;
    }
    // actualizo totales
    tab.lis_act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static val_acu( $dat, $ope ){
    
    let $ = dat.var($dat);

    if( !$.var_ide && $ope ) $ = dat.var( $dat = $api_app.tab.val.acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $api_app.tab.lis.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( dat.ver($cla,'^^',`${$.cla_ide}-`) ){
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
      ele.act('cla_eli',$api_app.tab.lis.querySelectorAll(`.${$.cla_ide}_bor`),`${$.cla_ide}_bor`);
      if( $dat.checked ) ele.act('cla_agr',$api_app.tab.lis.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}_bor`);
    }
    // actualizo calculos + vistas( fichas + items )        
    tab.lis_act($.var_ide);

  }
  // Seleccion : datos, posicion, fecha
  static ver( $tip ){

    // ejecuto filtros por tipo : pos, fec      
    dat.ope_ver($tip, lis.val_dec($api_app.tab.lis.querySelectorAll(`${$api_app.tab.cla}`)), $api_app.tab.ver );

    // marco seleccionados
    ele.act('cla_eli',$api_app.tab.lis.querySelectorAll('._val-ver_bor'),'_val-ver_bor');
    if( $api_app.tab.val.acu && $api_app.tab.val.acu.querySelector(`[name="ver"]:checked`) ){
      ele.act('cla_agr',$api_app.tab.lis.querySelectorAll(`._val-ver-`),'_val-ver_bor');
    }

    // actualizo calculos + vistas( fichas + items )
    tab.lis_act('ver');
  }    
  // Secciones : bordes + colores + imagen + ...
  static sec( $dat ){

    let $ = dat.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$api_app.tab.lis.classList.contains('bor-1') ){ $api_app.tab.lis.classList.add('bor-1'); }
        $api_app.tab.lis.querySelectorAll('.tab:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $api_app.tab.lis.classList.contains('bor-1') ){ $api_app.tab.lis.classList.remove('bor-1'); }
        $api_app.tab.lis.querySelectorAll('.tab.bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $api_app.tab.lis.querySelectorAll(`.tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $api_app.tab.lis.classList.contains('fon-0') ){
          $api_app.tab.lis.classList.remove('fon-0');
        }
      }else{
        // secciones
        $api_app.tab.lis.querySelectorAll(`.tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$api_app.tab.lis.classList.contains('fon-0') ){
          $api_app.tab.lis.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $api_app.tab.lis.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $api_app.tab.lis.style.backgroundImage = '';
      }
      break;      
    }     
  }
  // Posiciones : borde + color + imagen + texto + numero + fecha
  static pos( $dat ){

    let $ = dat.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $api_app.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $api_app.val.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $api_app.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $api_app.tab.lis.querySelectorAll(`${$api_app.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $api_app.tab.lis.querySelectorAll(`${$api_app.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      if( $api_app.tab.dep ){
        $.eli = `${$api_app.tab.cla} .pos:not(.tab)[class*='${$.ope}']`;
        $.agr = `${$api_app.tab.cla} .pos:not(.tab)`;
      }
      else{
        $.eli = `${$api_app.tab.cla}[class*='${$.ope}']`;
        $.agr = `${$api_app.tab.cla}`;
      }

      $api_app.tab.lis.querySelectorAll($.eli).forEach( $e => ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = dat.ide($dat.value,$);

        $.col = dat.val_ide('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        $.col = ( $.col && $.col.val ) ? $.col.val : 0;

        $api_app.tab.lis.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : num.ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $api_app.tab.lis.querySelectorAll($api_app.tab.cla).forEach( $e => {

        $e.querySelectorAll('.arc_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = dat.ide($dat.value,$);        
        // busco valores de ficha
        $.fic = dat.val_ide('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $api_app.tab.lis.querySelectorAll($api_app.tab.cla).forEach( $e => {
          $.htm = '';
          $.ele = { 
            title : false, 
            onclick : false 
          };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos-') ){ 
              $.htm = dat.val_ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar-') ){ 
              $.htm = dat.val_ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver-') ){ 
              $.htm = dat.val_ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_val-opc-/.test($cla_nom) ) return $.htm = dat.val_ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = dat.val_ima($e,$);
          }
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.arc_ima') ) ? ele.val_mod($.htm,$.ima_ele) : ele.val_agr($.htm,$e,'ini');
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
      $api_app.tab.lis.querySelectorAll($api_app.tab.cla).forEach( $e => ele.val_eli($e,$.eti) );

      if( $dat.value ){

        $ = dat.ide($dat.value,$);

        $api_app.tab.lis.querySelectorAll($api_app.tab.cla).forEach( $e =>{

          if( $.obj = dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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