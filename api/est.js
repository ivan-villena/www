// WINDOW
'use strict';

// tabla
class est {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    // aseguro datos
    if( !$api_est || $api_est[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_est[$est];

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

  // operador
  static val( $dat, $ope ){
    let $_ = $dat;

    // nivelacion por identificador
    if( !!($ope['niv']) ) $_ = est.val_niv($dat,$ope);

    // valor unico : objeto
    if( !!($ope['opc']) && $ope['opc'].includes('uni')) $_ = est.val_uni($dat);

    return $_;

  }// nivelacion de clave : num + ide + lis
  static val_niv( $dat, $ope ){

    let $_ = $dat, $={ tip : typeof($ope['niv']) };

    // key-pos = numérica : []
    if( $.tip == 'number' ){ 
      $_=[];
      $.k = parseInt($.key);
      for( const $i in $dat ){ 
        $_[$.k++]=$dat[$i]; 
      }
    }
    // key-ide = Literal : {}
    else if( $.tip=='string' ){ 
      $_={}; 
      $.k = $.key.split('(.)');
      for( const $i in $dat ){
        $.ide=[];
        for( let $ide of $.k ){ $.ide.push($dat[$i][$ide]); }
        $_[$.ide.join('(.)')] = $dat[$i];
      }
    }
    // keys-[1-7] => [ [ [ [],[ {-_-} ],[], ] ] ]
    else if( Array.isArray($ope['niv']) ){ 
      $_ = {};
      $.k = $ope['niv'];
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
    return $_;
  }// devuelvo valor unico : objeto
  static val_uni( $dat ){

    let $_ = {};

    for( const $pos in $dat ){ 
      $_ = $dat[$pos]; 
      break;
    }

    return $_;
  }

  // inicializo : acumulados
  static lis_ini(){

    let $={};   

    if( $api_app.est.val.acu ){

      if( $.ele = $api_app.est.val.acu.querySelector(`[name="tod"]`) ){

        est.lis_val_tod($.ele);
      }
    }

  }// actualizo : acumulado + cuentas + descripciones
  static lis_act(){

    let $={};
    // actualizo total
    if( $api_app.est.val.acu && ( $.tot = $api_app.est.val.acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = est.lis_val('tot');
    }    
    // actualizo cuentas
    if( $api_app.est.val.cue ){

      val.cue('act', $api_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $api_app.est.val.cue);
    }
    // actualizo descripciones
    if( $api_app.est.des ){

      $api_app.est.des.querySelectorAll(`[name]:checked`).forEach( $e => est.lis_des_tog($e) );
    }
  }
  // valores : todos | acumulados
  static lis_val( $tip ){

    switch( $tip ){
    // cuento items en pantalla
    case 'tot':
      if( $api_app.est.lis ){
      
        return $api_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        return 'err: no hay tabla relacionada';
      }      
      break;
    }
  }// - todos ? o por acumulados
  static lis_val_tod( $dat ){

    let $ = doc.var($dat);  
    
    if( $api_app.est.val.acu ){
      // ajusto controles acumulados
      $api_app.val.acu.forEach( $i => {

        if( $.val = $api_app.est.val.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
      });
    }
    // ejecuto todos los filtros y actualizo totales
    est.lis_ver();

  }// - acumulados : posicion - marcas - seleccion
  static lis_val_acu(){

    let $={};
    
    if( ( $.esq = $api_app.est.lis.dataset.esq ) && ( $.est = $api_app.est.lis.dataset.est ) ){
      
      // oculto todos los items de la tabla
      ele.act('cla_agr',$api_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

      // actualizo por acumulado
      $api_app.val.acu.forEach( $ide => {

        if( $.val = $api_app.est.val.acu.querySelector(`[name='${$ide}']`) ){

          $.tot = 0;
          if( $.val.checked ){
            // recorro seleccionados
            $api_app.tab.lis.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
              
              if( $.ele = $api_app.est.lis.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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
  // filtros : Valores + Fecha + Posicion
  static lis_ver( $tip, $dat, $ope ){

    let $ = doc.var($dat);

    // ejecuto filtros
    if( !$tip ){

      // 1º - muestro todos
      if( !$api_app.est.val.acu || $api_app.est.val.acu.querySelector(`[name="tod"]:checked`) ){

        ele.act('cla_eli',$api_app.est.lis.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        est.lis_val_acu();
      }
      // 2º - cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $api_app.val.ope_ver ){
        // tomo solo los que tienen valor
        if( ( $.val = $api_app.est.ver.querySelector(`${$api_app.val.ope_ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){

        $.eje.forEach( $ope_ide => {

          val.ver($ope_ide, lis.val_dec( $api_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $api_app.est.ver);
          // oculto valores no seleccionados
          ele.act('cla_agr',$api_app.est.lis.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else{
      if( ['cic','gru'].includes($tip) ){
        // muestro todos los items
        ele.act('cla_eli',$api_app.est.lis.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
        
        // aplico filtro
        // ... 
      }
    }
    // actualizo total, cuentas y descripciones
    est.lis_act();
  }
  // columnas : toggles + atributos
  static lis_atr(){
  }// - muestro-oculto
  static lis_atr_tog( $dat ){

    let $ = doc.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $api_app.est.lis.querySelectorAll(
        `:is(thead,tbody) :is(td,th)[data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$dat.name}"]`
      ).forEach( $ite => {
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
      $dat.parentElement.parentElement.querySelectorAll('input[type="checkbox"]').forEach( $e => {
          
        $e.checked = ( $dat.dataset.val == 'ver' );

        est.lis_atr_tog($e);
      });
    }
  }
  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static lis_des(){
  }// - muestro-oculto
  static lis_des_tog( $dat ){

    let $ = doc.var($dat);
    $.ope  = $api_app.var.classList[0].split('-')[1];
    $.esq = $api_app.var.dataset.esq;
    $.est = $api_app.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    ele.act('cla_agr',$api_app.est.lis.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $api_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          ele.act('cla_eli',$api_app.est.lis.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static lis_des_ver( $dat ){

    let $ = doc.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $api_app.est.lis.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $api_app.est.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$api_app.est.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            ele.act('cla_eli',$api_app.est.lis.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $api_app.est.lis.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $api_app.est.lis.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      ele.act('atr_act',$api_app.est.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      ele.act('cla_agr',$api_app.est.lis.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $api_app.est.lis.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $api_app.est.lis.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
}