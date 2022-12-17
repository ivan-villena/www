// WINDOW
'use strict';

// listado - tabla : []
class api_lis {

  _ope = {
    // acumulados
    acu : [ "pos", "mar", "ver", "opc" ],
    // filtros
    ver : {
      dat : `form.ide-dat select[name="val"]`,
      fec : `form.ide-fec input[name="ini"]`,
      pos : `form.ide-pos input[name="ini"]`
    }    
  };

  _est = {
    // Valores
    val :     `.ide-est div.lis.est`,
    // Operador
    val_acu : `.ide-est .ide-ver .ide-acu`,      
    val_sum : `.ide-est .ide-ver .ide-sum`,
    val_cue : `.ide-est .ide-ver .ide-cue`,    
    // filtros
    ver : `.ide-est .ide-ver`,
    // Descripciones
    des : `.ide-est .ide-des`    
  };

  _tab = {
    ide : null,
    dep : null,
    cla : null,
    // Valores
    val : `main > article > .lis.tab`,
    // operador
    val_acu : `.doc_pan > .ide-val .ide-acu`,
    val_sum : `.doc_pan > .ide-val .ide-sum`,
    val_cue : `.doc_pan > .ide-val .ide-cue`,
    // Seleccion
    ver : `.doc_pan > .ide-ver`,
    // seccion + posicion
    sec : `.doc_pan > .ide-opc .ide-sec`,    
    pos : `.doc_pan > .ide-opc .ide-pos`,
    // ...atributos
    atr : `.doc_pan > .ide-opc .ide-atr`
  };

  constructor( $dat = {} ){
    
    ['_est','_tab'].forEach( $ope => {
      for( const $ide in this[$ope] ){

        if( typeof(this[$ope][$ide]) == 'string' ){

          this[$ope][$ide] = document.querySelector(this[$ope][$ide]); 
        }
        else if( typeof(this[$ope][$ide]) == 'object' ){

          for( const $i in this[$ope][$ide] ){
            this[$ope][$ide][$i] = document.querySelector(this[$ope][$ide][$i]);
          }
        }
      }
    });

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

  /* 
  Valores del Entorno
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

  /* 
  Posicion: punteos, numeraciones, y términos
  */
  static pos(){
  }
  static pos_val( $dat, $ope ){
    let $ = api_dat.var($dat);
    
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

    let $ = api_dat.var($dat);

    if( !$dat || !$ope ){
      api_ele.act('cla_tog',$.lis.children,DIS_OCU); 
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
  }  
  static pos_ver( $dat, $ope ){
    let $ = api_dat.var($dat);
    
    // filtro por valor textual        
    if( !$ope ){
      // muestro por coincidencias
      if( $.val = $api_doc._var.querySelector('[name="val"]').value ){
        // oculto todos
        api_ele.act('cla_agr',$.lis.children,DIS_OCU); 

        $.ope = $api_doc._var.querySelector('[name="ope"]').value;
        
        if( $.lis.nodeName == 'DL' ){
          $.lis.querySelectorAll(`dt`).forEach( $e => {
            if( $.ope_val = api_dat.ver($e.innerHTML,$.ope,$.val) ){
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
          api_lis.val_cod($.lis.children).forEach( $e => 
            api_dat.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
          );
        }
      }
      else{
        api_ele.act('cla_eli',$.lis.children,DIS_OCU);
      }
    }
    // operadores
    else{
      switch( $ope ){
      case 'tod': api_ele.act('cla_eli',$.lis.children,DIS_OCU); break;
      case 'nad': api_ele.act('cla_agr',$.lis.children,DIS_OCU); break;
      }
    }

    // actualizo cuenta
    if( $.tot = $api_doc._var.querySelector('[name="tot"]') ){
      if( $.lis.nodeName == 'DL' ){
        $.tot.innerHTML = api_lis.val_cod($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
      }else{
        $.tot.innerHTML = api_lis.val_cod($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
      }
    }    
  }

  /* 
  Contenedor: toggles + filtros
  */
  static ite(){      
  }
  static ite_ver( $dat, $ope = 'p:first-of-type', $cla = 'tex-luz' ){
    let $ = api_dat.var($dat);
    // busco listado
    if( $api_doc._var ){
      $.lis = !! $api_doc._var.nextElementSibling ? $api_doc._var.nextElementSibling : $api_doc._var.parentElement;
      if( $.lis.nodeName == 'LI' ){
        $.lis = $.lis.parentElement;
        $.val_dep = true;
      }
    }
    // ejecuto filtros
    if( $.lis && ( $.ope = $api_doc._var.querySelector(`[name="ope"]`) ) && ( $.val = $api_doc._var.querySelector(`[name="val"]`) ) ){      
      // elimino marcas anteriores      
      $.lis.querySelectorAll(`li.pos ${$ope}.${$cla}`).forEach( $ite => $ite.classList.remove($cla) );
      // muestro u oculto por coincidencias
      $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {

        // capturo item : li > [.val] (p / a)
        $.ite = api_ele.val_ver($ite,{'eti':'li'});
        // ejecuto comparacion por elemento selector ( p / a )
        if( !$.val.value || api_dat.ver($ite.innerText, $.ope.value, $.val.value) ){
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
      if( $.tog[1] && ( $.ico = $api_doc._var.querySelector(`.fig_ico.ide-val_tog-${$.tog[1]}`) ) ) api_doc.val($.ico,$.tog[1]);
      
      // actualizo total
      if( $.tot_val = $api_doc._var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;           
    }      
  }
  static ite_tog( $dat, $val ){
    
    return api_doc.val($dat,$val);
  }

  /* 
  indice
  */
  static nav( $dat, $cla = FON_SEL ){

    let $ = { lis : api_ele.val_ver($dat,{'eti':'nav'}) };

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
        ( $dat.classList.contains('fig_ico') || $.dep.classList.contains(DIS_OCU) ) 
      ){
        api_doc.val($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.fig_ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }// - hago toogle por item
  static nav_tog( $lis ){

    let $={};

    if( $.nav = $lis ? api_lis.nav_mar($lis) : false ){
      // hago toogles ascendentes
      while( 
        ( $.lis = api_ele.val_ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('doc_val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('fig_ico') ){                
          api_doc.val($.ico);
        }                
      }
    }
  }// - marco valor seleccionado
  static nav_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      api_lis.nav($nav);
    }

    return $nav;
  }// - ejecuto filtros
  static nav_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    api_lis.ite_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    api_lis.nav_tog($api_doc._var.nextElementSibling);

  }    

  /* 
  desplazamiento horizontal x item
  */
  static bar(){
  }
  static bar_ite( $tip, $dat ){
    
    let $ = api_dat.var($dat);

    if( $tip == 'val' ){

      $.lis = $api_doc._var.previousElementSibling;

      $.val = $api_doc._var.querySelector('[name="val"]');
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

      api_ele.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`),DIS_OCU);

      if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove(DIS_OCU);
    }
  }

  /*  
  Operador: valores
  */
  // acumulados : posicion + marca + seleccion
  static ope_acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $api_lis._ope.acu : $opc ).forEach( $ide => {

      // acumulo elementos del listado
      $.acu_val[$ide] = $dat.querySelectorAll(`[class*="_val-${$ide}-"]`);

      // actualizo total del operador
      if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

        $.tot.innerHTML = $.acu_val[$ide].length;
      }
    });

    // calculo el total grupal    
    if( $.tot = $ope.querySelector(`[name="tot"]`) ){
      $.tot.innerHTML = $dat.querySelectorAll(`[class*=_val-]:is([class*="_bor"],[class*="_act"])`).length;
    }

    // devuelvo seleccion
    return $.acu_val;
  }// sumatorias
  static ope_sum( $dat, $ope ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;
      $dat.forEach( $ite => $.sum += parseInt( $ite.dataset[`${$val.dataset.esq}_${$val.dataset.est}`] ) );

      api_dat.fic( $val, $.sum);
    });
  }// filtros : dato + variables
  static ope_ver( $tip, $dat, $ope, ...$opc ){

    let $ = api_dat.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver-`;
    $.cla_ide=`${$.cla_val}_${$tip}`;
    
    api_ele.act('cla_eli',$dat,[$.cla_val, $.cla_ide]);

    $api_doc._var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'dat' ){

      $.dat_est = $api_doc._var.querySelector(`[name="est"]`);
      $.dat_ide=$api_doc._var.querySelector(`[name="ver"]`);
      $.dat_val = $api_doc._var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = api_dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = api_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $tip == 'pos' || $tip == 'fec' ){
      
      // elimino valor de dato por seleccion
      if( ( $.ver = $ope.querySelector(`form.ide-dat select[name="val"]`) ) && $.ver.value ) $.ver.value = '';
            
      // valores
      $.val = {};
      $.var = {};
      ['ini','fin','inc','lim'].forEach( $ide => {
        // capturo valores
        if( ( $.ite = $api_doc._var.querySelector(`[name="${$ide}"]`) ) ){
          $.var[$ide] = $.ite;
          if( !!$.ite.value ) $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? api_num.val($.ite.value) : $.ite.value;
        }
      });
      
      // valido inicial: si es mayor que el final      
      if( $.val.ini && $.val.fin && $.val.ini > $.val.fin){
        $.var.ini.value = $.val.ini = $.val.fin;
      }
      // valido final
      if( $.val.fin ){
        // si es mayor que el inicio
        if( $.val.fin < $.val.ini ) $.var.fin.value = $.val.ini;
      }
      // inicializo valor final
      else if( $.val.ini && $.var.fin ){
        $.max = $.var.fin.getAttribute('max');
        $.var.fin.value = $.val.fin = $.max;
      }

      // inicializo incremento
      $.inc_val = 1;
      if( $.var.inc && ( !$.val.inc || $.val.inc <= 0 ) ){
        $.var.inc.value = $.val.inc = 1;
      }

      // filtro por posicion de lista      
      if( $tip == 'pos' ){
        
        $dat.forEach( $e => {
          // valor por desde-hasta
          $.pos_val = $e.classList[1].split('-')[1];
          if( $.inc_val == 1 && $.pos_val >= $.val.ini && $.pos_val <= $.val.fin ){
            api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
          // aplico salto
          $.inc_val++;
          if( $.inc_val > $.val.inc ) $.inc_val = 1;
        });
      }
      // filtro por valor de fecha
      else if( $tip == 'fec' ){

        $.val.ini = $.val.ini ? $.val.ini.split('-') : '';
        $.val.fin = $.val.fin ? $.val.fin.split('-') : '';

        $dat.forEach( $e => {
          // desde-hasta
          if( $.inc_val == 1 && api_fec.val_ver( $e.dataset['fec_dat'], $.val.ini, $.val.fin ) ){

            api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
          // aplico salto
          $.inc_val++;
          if( $.inc_val > $.val.inc ) $.inc_val = 1;
        });
      }

      // limito resultado
      if( $.val.lim ){

        $.lis = $dat.filter( $e => $e.classList.contains($.cla_ide) );
        // ultimos
        if( $api_doc._var.querySelector(`.fig_ico.ide-lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) api_ele.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }// conteos : valores de estructura relacionada por atributo
  static ope_cue( $tip, $dat, $ope, ...$opc ){
    let $ = api_dat.var($dat);

    switch( $tip ){
    // actualizo cuentas por valores
    case 'act':
      $.val_tot = $dat.length;

      $ope.querySelectorAll(`table[data-esq][data-est]`).forEach( $tab => {

        $.esq = $tab.dataset.esq;
        $.est = $tab.dataset.est;
                  
        if( $.atr = $tab.dataset.atr ){

          $tab.querySelectorAll(`tr[data-ide]`).forEach( $ite => {
            $.ide = $ite.dataset.ide;
            $.tot = 0;            
            $dat.forEach( $v => {

              if( $.dat = $v.dataset[`${$.esq}_${$.est}`] ){

                if( ( $.dat_val = api_dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
              }
            });

            $ite.querySelector('td[data-atr="tot"] > n').innerHTML = $.tot;
            $ite.querySelector('td[data-atr="por"] > n').innerHTML = $.val_tot ? api_num.val_dec( ( $.tot / $.val_tot ) * 100 ) : $.val_tot;
          });
        }
      });
      break;
    // filtro por valor textual
    case 'ver':

      $.ope = $api_doc._var.querySelector('[name="ope"]').value;
      $.val = $api_doc._var.querySelector('[name="val"]').value;
      $.lis = $api_doc._var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll(`tr.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( api_dat.ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU);
          }
          else if( !$e.classList.contains(DIS_OCU) ){
            $e.classList.add(DIS_OCU);
          }
        });
      }
      break;              
    }    
  }  

  /* 
  Tabla
  */
  // inicializo : acumulados
  static est_ini(){

    let $={};   

    if( $api_lis._est.val_acu ){

      if( $.ele = $api_lis._est.val_acu.querySelector(`[name="tod"]`) ){

        api_lis.est_val('tod',$.ele);
      }
    }

  }// actualizo : acumulado + cuentas + descripciones
  static est_act(){

    let $={};
    // actualizo total
    if( $api_lis._est.val_acu && ( $.tot = $api_lis._est.val_acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = api_lis.est_val('tot');
    }    
    // actualizo cuentas
    if( $api_lis._est.val_cue ){

      api_lis.ope_cue('act', $api_lis._est.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $api_lis._est.val_cue);
    }
    // actualizo descripciones
    if( $api_lis._est.des ){

      $api_lis._est.des.querySelectorAll(`[name]:checked`).forEach( $e => api_lis.est_des_tog($e) );
    }
  }// Valores: totales + acumulados
  static est_val( $tip, $dat ){
    let $_, $ = {};
    switch( $tip ){
    case 'tot': 
      $_ = 0;
      if( $api_lis._est.val ){
        $_ = $api_lis._est.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        console.error('No hay tabla relacionada...');
      }
      break;
    case 'tod': 
      $ = api_dat.var($dat);  
      
      if( $api_lis._est.val_acu ){
        // ajusto controles acumulados
        $api_lis._ope.acu.forEach( $i => {

          if( $.val = $api_lis._est.val_acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }
      // ejecuto todos los filtros y actualizo totales
      api_lis.est_ver();    
      break;
    case 'acu':
      if( ( $.esq = $api_lis._est.val.dataset.esq ) && ( $.est = $api_lis._est.val.dataset.est ) ){
        
        // oculto todos los items de la tabla
        api_ele.act('cla_agr',$api_lis._est.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

        // actualizo por acumulado
        $api_lis._ope.acu.forEach( $ide => {

          if( $.val = $api_lis._est.val_acu.querySelector(`[name='${$ide}']`) ){

            $.tot = 0;
            if( $.val.checked ){
              // recorro seleccionados
              $api_lis._tab.val.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
                
                if( $.ele = $api_lis._est.val.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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
      break;
    }
    return $_;
  }// Filtros : Valores + Fecha + Posicion
  static est_ver( $tip, $dat, $ope ){

    let $ = api_dat.var($dat);

    // ejecuto filtros
    if( !$tip ){

      // 1º - muestro todos
      if( !$api_lis._est.val_acu || $api_lis._est.val_acu.querySelector(`[name="tod"]:checked`) ){

        api_ele.act('cla_eli',$api_lis._est.val.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        api_lis.est_val('acu');
      }
      // 2º - cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $api_lis._ope.ver ){
        // tomo solo los que tienen valor
        if( ( $.val = $api_lis._est.ver.querySelector(`${$api_lis._ope.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){

        $.eje.forEach( $ope_ide => {

          api_lis.ope_ver($ope_ide, api_lis.val_cod( $api_lis._est.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $api_lis._est.ver);
          // oculto valores no seleccionados
          api_ele.act('cla_agr',$api_lis._est.val.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else{
      if( ['cic','gru'].includes($tip) ){
        // muestro todos los items
        api_ele.act('cla_eli',$api_lis._est.val.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
        
        // aplico filtro
        // ... 
      }
    }
    // actualizo total, cuentas y descripciones
    api_lis.est_act();
  }// Columnas : toggles + atributos
  static est_atr(){
  }// - muestro-oculto
  static est_atr_tog( $dat ){

    let $ = api_dat.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $api_lis._est.val.querySelectorAll(
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

        api_lis.est_atr_tog($e);
      });
    }
  }// Descripcion : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static est_des(){
  }// - muestro-oculto
  static est_des_tog( $dat ){

    let $ = api_dat.var($dat);
    $.ope  = $api_doc._var.classList[0].split('-')[1];
    $.esq = $api_doc._var.dataset.esq;
    $.est = $api_doc._var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    api_ele.act('cla_agr',$api_lis._est.val.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $api_lis._est.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = api_dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          api_ele.act('cla_eli',$api_lis._est.val.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static est_des_ver( $dat ){

    let $ = api_dat.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $api_lis._est.val.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $api_lis._est.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$api_lis._est.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            api_ele.act('cla_eli',$api_lis._est.val.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $api_lis._est.val.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $api_lis._est.val.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      api_ele.act('atr_act',$api_lis._est.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      api_ele.act('cla_agr',$api_lis._est.val.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $api_lis._est.val.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $api_lis._est.val.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
  
  /* 
    Tablero
  */
  static tab( $dat, $ele, ...$opc ){
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
  }// Inicio : opciones, posicion, filtros
  static tab_ini( $cla ){
    let $={ cla : !!$cla ? eval($cla) : false };

    // clase por posicion
    $api_lis._tab.cla = '.pos.ope'; 
    $api_lis._tab.ide = $api_lis._tab.val.classList[3];
    
    // inicializo opciones
    ['sec','pos'].forEach( $ope => {
      if( $api_lis._tab[$ope] ){
        $api_lis._tab[$ope].querySelectorAll(
          `form[class*="ide-"] [onchange*=".tab_"]:is( input:checked, select:not([value=""]) )`
        ).forEach( 
          $inp => api_lis[`tab_${$ope}`]( $inp )
        );
      }
    });

    // marco posicion principal
    api_lis.tab_val('pos');

    // actualizo opciones
    $api_lis._ope.acu.forEach( $ite => 
      ( $.ele = $api_lis._tab.val_acu.querySelector(`[name="${$ite}"]:checked`) ) && api_lis.tab_val_acu($.ele) 
    );

    // inicializo operador por aplicacion
    if( $.cla ){
      // secciones y posiciones por aplicacion
      ['sec','pos'].forEach( $ope => {
        if( $api_lis._tab[$ope] ){
          $.eje = `tab_${$ope}`;
          $api_lis._tab[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$cla}.${$.eje}"]`).forEach(

            $inp => $.cla[$.eje] && $.cla[$.eje]( $inp )
          );
        }
      });
      
      // atributos
      if( $api_lis._tab.atr ){
        $api_lis._tab.atr.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
          
          $.eje = `tab_${$for.classList[0].split('-')[2]}`;

          $for.querySelectorAll(`[name][onchange*="${$cla}.${$.eje}"]`).forEach( 

            $inp => !!$.cla[$.eje] && $.cla[$.eje]( $inp )
          );
        });
      }
    }
  }// Actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static tab_act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $api_lis._ope.acu : api_lis.val_ite($dat);

    $.dat = $api_lis._tab.val;

    // acumulados + listado
    if( $api_lis._tab.val_acu ){ 

      // actualizo toales acumulados
      api_lis.ope_acu($api_lis._tab.val, $api_lis._tab.val_acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $api_lis._tab.val_sum ){
        $.tot = [];
        $api_lis._ope.acu.forEach( $acu_ide => {

          if( $api_lis._tab.val_acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$api_lis._tab.val.querySelectorAll(`[class*="_val-${$acu_ide}-"]`) );
          }
        });
        api_lis.ope_sum($.tot, $api_lis._tab.val_sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$api_lis._est.val_acu.querySelector(`[name="tod"]:checked`) ) api_lis.est_val('acu');

      // -> ejecuto filtros + actualizo totales
      if( $api_lis._est.ver ) api_lis.est_ver();
    }

    // fichas del tablero
    if( ( $api_lis._tab.pos ) && ( $.ima = $api_lis._tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $api_lis._tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) api_lis.tab_pos($.ima);
    }

    // actualizo cuentas
    if( $api_lis._tab.val_cue ){

      api_lis.ope_cue('act', $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}[class*=_val-]:is([class*=_bor],[class*=_act])`), $api_lis._tab.val_cue );
    }
  }// Valores
  static tab_val( $tip, $dat ){

    let $ = api_dat.var($dat);

    switch( $tip ){
    case 'pos': 
      if( $_hol && $_hol.val && ( $.kin = $_hol.val.kin ) ){

        $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}[data-hol_kin="${$.kin}"]`).forEach( $e => {
          $e.classList.add(`_val-pos-`);
          if( $api_lis._tab.val_acu && $api_lis._tab.val_acu.querySelector(`[name="pos"]:checked`) ){
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
        if( $api_lis._tab.val_acu ){
          if( $.pos.classList.contains(`_val-mar-`) && $api_lis._tab.val_acu.querySelector(`[name="mar"]:checked`) ){
            $.pos.classList.add(`_val-mar_bor`);
          }
          else if( !$.pos.classList.contains(`_val-mar-`) && $.pos.classList.contains(`_val-mar_bor`) ){
            $.pos.classList.remove(`_val-mar_bor`);
          }
        }
      }
      break;
    case 'ver':
      for( const $ide in $api_lis._ope.ope_ver ){

        if( $.ele = $api_lis._tab.ver.querySelector(`${$api_lis._ope.ope_ver[$ide]}:not([value=""])`) ){
  
          api_lis.tab_ver($ide,$.ele,$api_lis._tab.val);

          break;
        }
      }
      break;
    case 'opc':
      // las 
      break;
    }
    // actualizo totales
    api_lis.tab_act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static tab_val_acu( $dat, $ope ){
    
    let $ = api_dat.var($dat);

    if( !$.var_ide && $ope ) $ = api_dat.var( $dat = $api_lis._tab.val_acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $api_lis._tab.val.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( api_dat.ver($cla,'^^',`${$.cla_ide}-`) ){
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
      api_ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.${$.cla_ide}_bor`),`${$.cla_ide}_bor`);
      if( $dat.checked ) api_ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}_bor`);
    }
    // actualizo calculos + vistas( fichas + items )        
    api_lis.tab_act($.var_ide);

  }// Seleccion : datos, posicion, fecha
  static tab_ver( $tip ){

    // ejecuto filtros por tipo : pos, fec      
    api_lis.ope_ver( $tip, api_lis.val_cod($api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}`)), $api_lis._tab.ver );

    // marco seleccionados
    api_ele.act('cla_eli',$api_lis._tab.val.querySelectorAll('._val-ver_bor'),'_val-ver_bor');
    if( $api_lis._tab.val_acu && $api_lis._tab.val_acu.querySelector(`[name="ver"]:checked`) ){
      api_ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`._val-ver-`),'_val-ver_bor');
    }

    // actualizo calculos + vistas( fichas + items )
    api_lis.tab_act('ver');
    
  }// Secciones : bordes + colores + imagen + ...
  static tab_sec( $dat ){

    let $ = api_dat.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$api_lis._tab.val.classList.contains('bor-1') ){ $api_lis._tab.val.classList.add('bor-1'); }
        $api_lis._tab.val.querySelectorAll('.tab:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $api_lis._tab.val.classList.contains('bor-1') ){ $api_lis._tab.val.classList.remove('bor-1'); }
        $api_lis._tab.val.querySelectorAll('.tab.bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $api_lis._tab.val.querySelectorAll(`.tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $api_lis._tab.val.classList.contains('fon-0') ){
          $api_lis._tab.val.classList.remove('fon-0');
        }
      }else{
        // secciones
        $api_lis._tab.val.querySelectorAll(`.tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$api_lis._tab.val.classList.contains('fon-0') ){
          $api_lis._tab.val.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $api_lis._tab.val.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $api_lis._tab.val.style.backgroundImage = '';
      }
      break;      
    }     
  }// Posiciones : borde + color + imagen + texto + numero + fecha
  static tab_pos( $dat ){

    let $ = api_dat.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $api_lis._tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $api_lis._ope.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $api_lis._tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      if( $api_lis._tab.dep ){
        $.eli = `${$api_lis._tab.cla} .pos:not(.tab)[class*='${$.ope}']`;
        $.agr = `${$api_lis._tab.cla} .pos:not(.tab)`;
      }
      else{
        $.eli = `${$api_lis._tab.cla}[class*='${$.ope}']`;
        $.agr = `${$api_lis._tab.cla}`;
      }

      $api_lis._tab.val.querySelectorAll($.eli).forEach( $e => api_ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = api_dat.ide($dat.value,$);

        $.col = api_dat.val_ide('col', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );

        $.col = ( $.col && $.col.val ) ? $.col.val : 0;

        $api_lis._tab.val.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = api_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : api_num.val_ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $api_lis._tab.val.querySelectorAll($api_lis._tab.cla).forEach( $e => {

        $e.querySelectorAll('.fig_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = api_dat.ide($dat.value,$);        
        // busco valores de ficha
        $.fic = api_dat.val_ide('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $api_lis._tab.val.querySelectorAll($api_lis._tab.cla).forEach( $e => {
          $.htm = '';
          $.ele = { 
            title : false, 
            onclick : false 
          };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos-') ){ 
              $.htm = api_dat.val_ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar-') ){ 
              $.htm = api_dat.val_ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver-') ){ 
              $.htm = api_dat.val_ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_val-opc-/.test($cla_nom) ) return $.htm = api_dat.val_ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = api_dat.val_ima($e,$);
          }
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.fig_ima') ) ? api_ele.val_mod($.htm,$.ima_ele) : api_ele.val_agr($.htm,$e,'ini');
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
      $api_lis._tab.val.querySelectorAll($api_lis._tab.cla).forEach( $e => api_ele.val_eli($e,$.eti) );

      if( $dat.value ){

        $ = api_dat.ide($dat.value,$);

        $api_lis._tab.val.querySelectorAll($api_lis._tab.cla).forEach( $e =>{

          if( $.obj = api_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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