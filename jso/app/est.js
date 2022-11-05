// WINDOW
'use strict';

// Estructura
class _app_est {
 
  // inicializo : acumulados
  static ini(){

    let $={};   

    if( $_app.est.val.acu ){

      if( $.ele = $_app.est.val.acu.querySelector(`[name="tod"]`) ){

        _app_est.val_tod($.ele);
      }
    }

  }
  // actualizo : acumulado + cuentas + descripciones
  static act(){

    let $={};
    // actualizo total
    if( $_app.est.val.acu && ( $.tot = $_app.est.val.acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = _app_est.val('tot');
    }    
    // actualizo cuentas
    if( $_app.est.val.cue ){

      _app_val.cue('act', $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $_app.est.val.cue);
    }
    // actualizo descripciones
    if( $_app.est.des ){

      $_app.est.des.querySelectorAll(`[name]:checked`).forEach( $e => _app_est.des_tog($e) );
    }
  }
  // datos : todos | acumulados
  static val( $tip ){

    switch( $tip ){
    // cuento items en pantalla
    case 'tot':
      if( $_app.est.lis ){
      
        return $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        return 'err: no hay tabla relacionada';
      }      
      break;
    }
  }// - todos ? o por acumulados
  static val_tod( $dat ){

    let $ = _app.var($dat);  
    
    if( $_app.est.val.acu ){
      // ajusto controles acumulados
      $_app.ope_val.acu.forEach( $i => {

        if( $.val = $_app.est.val.acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
      });
    }
    // ejecuto todos los filtros y actualizo totales
    _app_est.ver();

  }// - acumulados : posicion - marcas - seleccion
  static val_acu(){

    let $={};
    
    if( ( $.esq = $_app.est.lis.dataset.esq ) && ( $.est = $_app.est.lis.dataset.est ) ){
      
      // oculto todos los items de la tabla
      _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

      // actualizo por acumulado
      $_app.ope_val.acu.forEach( $ide => {

        if( $.val = $_app.est.val.acu.querySelector(`[name='${$ide}']`) ){

          $.tot = 0;
          if( $.val.checked ){
            // recorro seleccionados
            $_app.tab.lis.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
              
              if( $.ele = $_app.est.lis.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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
  static ver( $tip, $dat, $ope ){

    let $ = _app.var($dat);

    // ejecuto filtros
    if( !$tip ){

      // 1º - muestro todos
      if( !$_app.est.val.acu || $_app.est.val.acu.querySelector(`[name="tod"]:checked`) ){

        _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        _app_est.val_acu();
      }
      // 2º - cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $_app.ope_val.ver ){
        // tomo solo los que tienen valor
        if( ( $.val = $_app.est.ver.querySelector(`${$_app.ope_val.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){

        $.eje.forEach( $ope_ide => {

          _app_val.ver($ope_ide, _lis.val( $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $_app.est.ver);
          // oculto valores no seleccionados
          _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else{
      if( ['cic','gru'].includes($tip) ){
        // muestro todos los items
        _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
        
        // aplico filtro
        // ... 
      }
    }
    // actualizo total, cuentas y descripciones
    _app_est.act();
  }
  // columnas : toggles + atributos
  static atr(){
  }// - muestro-oculto
  static atr_tog( $dat ){

    let $ = _app.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $_app.est.lis.querySelectorAll(
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

        _app_est.atr_tog($e);
      });
    }
  }
  // descripciones : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static des(){
  }// - muestro-oculto
  static des_tog( $dat ){

    let $ = _app.var($dat);
    $.ope  = $_app.ope.var.classList[0].split('-')[1];
    $.esq = $_app.ope.var.dataset.esq;
    $.est = $_app.ope.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    _ele.act('cla_agr',$_app.est.lis.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $_app.est.lis.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = _dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          _ele.act('cla_eli',$_app.est.lis.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static des_ver( $dat ){

    let $ = _app.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $_app.est.lis.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $_app.est.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$_app.est.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            _ele.act('cla_eli',$_app.est.lis.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $_app.est.lis.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $_app.est.lis.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      _ele.act('atr_act',$_app.est.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      _ele.act('cla_agr',$_app.est.lis.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $_app.est.lis.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $_app.est.lis.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
}