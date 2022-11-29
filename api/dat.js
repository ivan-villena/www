// WINDOW
'use strict';

// Dato : esq.est[ide].atr
class dat {
 
  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_dat || $api_dat[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_dat[$est];

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
  
  // getter por : objetos | consultas
  static get( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $.esq = $dat;
      $.est = $ope;
      $_ = $val;

      if( !$val || !obj.val_tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval(`${$.esq}`) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = ele.val_ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = est.val('ver',$dat,$ope);
    }
    return $_;
  }  

  // identificador: esq.est[...atr]
  static ide( $dat='', $val={} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }

  // Estructura
  // ...
  // Cargo operador
  static est_ope( $esq, $est, $ope, $dat ) {

    let $={}, $_ = $api_dat._ope[$esq][$est];

    // cargo atributo
    $.ope_atr = $ope.split('.');
    $.ope_atr.forEach( $ide => {
      $_ = ( typeof($_) == 'object' && !!($_[$ide]) ) ? $_[$ide] : false;
    });

    // proceso valores con datos
    if( $_ && $.ope_atr[0] == 'val' && !!($dat) ) $_ = obj.val( dat.get($esq,$est,$dat), $_ );

    return $_;
  }  

  // valor : p[tit, nom, des] + ima 
  static val( $tip, $dat, $ope, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = dat.ide($dat,$);
    // cargo datos
    $._dat = dat.get($.esq,$.est,$ope);
    // cargo valores
    $._val = dat.est_ope($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $_ = obj.val($._dat,$._val[$tip]);  
    }
    // armo ficha
    if( $tip == 'ima' ){
      $.ele = !!$opc[0] ? $opc[0] : {};

      // identificador
      $.ele['data-esq'] = $.esq;
      $.ele['data-est'] = $.est;
      $.ele['data-ide'] = $._dat.ide;
      
      // titulos
      if( $.ele.title === undefined ){

        $.ele.title = dat.val('tit',`${$.esq}.${$.est}`,$._dat);
      }
      else if( $.ele.title === false ){        
        delete($.ele.title);
      }
      // acceso informe
      if( $.ele.onclick === undefined ){
        if( dat.est_ope($.esq,$.est,'inf') ) $.ele.onclick = `dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
      }
      else if( $.ele.onclick === false ){

        delete($.ele.onclick);
      }
      // informe      
      $_ = arc.ima( { 'style': $_ }, $.ele );
    }
    else if( !!$opc[0] ){
      
      if( !($opc[0]['eti']) ) $opc[0]['eti'] = 'p'; 
      $opc[0]['htm'] = tex.let($_);
      $_ = ele.val($opc[0]);
    }

    return $_;
  }// opciones : esquema.estructura.atributos.valores
  static val_opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = doc.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $api_app.var.querySelector(`[name="${$i}"]`) ) ele.val_eli( $.ope, `option:not([value=""])` ); 
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
        ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        ele.val_eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = dat.ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          eje.val(['dat::get', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = opc.lis( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }// identificador por seleccion : imagen, color...
  static val_ide( $tip, $esq, $est, $atr, $dat ){
    
    // dato
    let $={}, $_ = { 'esq': $esq, 'est': $est };

    // armo identificador
    if( !!($atr) ) $_['est'] = $atr == 'ide' ? $est : `${$est}_${$atr}`;
    
    // valido dato
    if( !!( $.dat_Val = dat.est_ope($_['esq'],$_['est'],`val.${$tip}`,$dat) ) ){
      $_['ide'] = `${$_['esq']}.${$_['est']}`;
      $_['val'] = $.dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;    
  }// imagen por identificadores
  static val_ima( $dat, $ope ){

    let $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){

      if( !$ope.fic ) $ope.fic = dat.opc('ima', $ope.esq, $ope.est );

      $_ = arc.ima($ope.fic.esq, $ope.fic.est, dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }

  // ficha : imagenes por valor con relaciones por estructura
  static fic( $dat, $ope, ...$opc ){
    let $_="", $=doc.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.atr`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? num.ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.atr [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      ele.val_eli($ite,'.arc_ima');
      if( $.val = $.dat[$.est] ){
        ele.val_agr( arc.ima( $.ima[0], $.ima[1], dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }

  // informe : 
  static inf( $esq, $est, $val ){
    // pido ficha
    eje.val([ `dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) doc.win('app_ope',{ ico:"", cab:"", htm:$htm, opc:[ "pos" ] });
    });
  }


}