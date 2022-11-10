// WINDOW
'use strict';

// Dato
class app_dat {

  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = api_dat.ide($dat,$);
    // cargo datos
    $._dat = api.dat($.esq,$.est,$ope);
    // cargo valores
    $._val = app.dat($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = api_obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+api_obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $_ = api_obj.val($._dat,$._val[$tip]);  
    }
    // armo ficha
    if( $tip == 'ima' ){
      $.ele = !!$opc[0] ? $opc[0] : {};
      // identificador
      api_ele.cla($.ele,`${$.esq}_${$.est} ide-${$._dat.ide}`,'ini');
      // titulos
      if( $.ele.title === undefined ){

        $.ele.title = app_dat.val('tit',`${$.esq}.${$.est}`,$._dat);
      }
      else if( $.ele.title === false ){        
        delete($.ele.title);
      }
      // acceso informe
      if( $.ele.onclick === undefined ){
        if( app.dat($.esq,$.est,'inf') ) $.ele.onclick = `app_dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
      }
      else if( $.ele.onclick === false ){

        delete($.ele.onclick);
      }
      // informe      
      $_ = app.ima( { 'style': $_ }, $.ele );
    }
    else if( !!$opc[0] ){
      
      if( !($opc[0]['eti']) ) $opc[0]['eti'] = 'p'; 
      $opc[0]['htm'] = app.let($_);
      $_ = api_ele.val($opc[0]);
    }

    return $_;
  }
  // opciones : esquema.estructura.atributos.valores
  static opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = app.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $_app.ope.var.querySelector(`[name="${$i}"]`) ) api_ele.eli( $.ope, `option:not([value=""])` ); 
      });
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
        api_ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          api_ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        api_ele.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = api_dat.ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          api_eje.val(['api::dat', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = app_var.opc_val( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }
  // ficha : imagenes por valor con relaciones por estructura
  static fic( $dat, $ope, ...$opc ){
    let $_="", $=app.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.atr`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? api_num.ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.atr [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      api_ele.eli($ite,'.ima');
      if( $.val = $.dat[$.est] ){
        api_ele.agr( app.ima( $.ima[0], $.ima[1], api.dat($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }
  // informe : 
  static inf( $esq, $est, $val ){
    // pido ficha
    api_eje.val([ `app_dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) app.win('app_ope',{ ico:"", cab:"", htm:$htm, opc:[ "pos" ] });
    });
  }
}