// WINDOW
'use strict';

// Estructura
class api_est {

  // getter 
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('est',$ide,'dat');

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

  /* Valores */
  static val( $tip, $ide, $dat, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = sis_dat.est_ide($ide,$);
    // cargo datos
    $._dat = sis_dat.get($.esq,$.est,$dat);
    // cargo valores
    $._val = sis_dat.est($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = api_obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+api_obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $.tip_val = typeof($._val[$tip]);
      if( $tip == 'ima' ){
        if( $.tip_val != 'string' ) $tip = 'tab';
      }
      else if( $.tip_val == 'string' ){
        $_ = api_obj.val($._dat,$._val[$tip]);  
      }
    }

    // armo ficha
    if( $tip == 'ima' ){
      
      // identificador
      $.ele = !!$opc[0] ? $opc[0] : {};      
      $.ele['data-esq'] = $.esq;
      $.ele['data-est'] = $.est;
      
      // 1 o muchos...
      $_ = "";
      $.ima_ele = $.ele;
      $.ima_lis = ( typeof($dat) == 'string' ? ( $dat.split( /, /.test($dat) ? ", " : " - " )  ) : [ $dat ] );
      $.ima_lis.forEach( $dat_val => {

        $._dat = sis_dat.get($.esq,$.est,$dat_val);

        $.ima_ele['data-ide'] = $._dat.ide;
        
        // titulos
        if( $.ima_ele.title === undefined ){

          $.ima_ele.title = api_est.val('tit',`${$.esq}.${$.est}`,$._dat);
        }
        else if( $.ima_ele.title === false ){
          delete($.ima_ele.title);
        }
        // acceso informe
        if( $.ima_ele.onclick === undefined ){
          if( sis_dat.est($.esq,$.est,'inf') ) $.ima_ele.onclick = `api_est.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
        }
        else if( $.ima_ele.onclick === false ){

          delete($.ima_ele.onclick);
        }
        // informe      
        $_ += api_fig.ima( { 'style': api_obj.val($._dat,$._val[$tip]) }, $.ima_ele );
      })
    }
    // pido tablero por identificador
    else if( $tip == 'tab' ){
      $.par = $_val['ima'];
      $.ele_ima = $.ele;
    }
    // por contenido
    else if( !!$opc[0] ){
      if( !($opc[0]['eti']) ) $opc[0]['eti'] = 'p'; 
      $opc[0]['htm'] = api_tex.let($_);
      $_ = api_ele.val($opc[0]);
    }

    return $_;
  }// opciones : esquema.estructura.atributos.valores
  static val_opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = api_doc.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $dom.app.var.querySelector(`[name="${$i}"]`) ) $dom.eli( $.ope, `option:not([value=""])` ); 
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
        $dom.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          $dom.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        $dom.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = sis_dat.est_ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          api_eje.val(['sis_dat::get', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = api_opc.lis( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }// identificador por seleccion : imagen, color...
  static val_ide( $tip, $esq, $est, $atr, $dat ){
      
    let $={}, 
    // armo identificadores
    $_ = { 'esq': $esq, 'est': $est, 'atr':$atr, 'ide':"", 'val':null };

    if( !!($atr) ) $_['est'] = ( $atr == 'ide' ) ? $est : `${$est}_${$atr}`;

    $_['ide'] = `${$_['esq']}.${$_['est']}`;

    // busco estructura relacionada por atributo
    $.esq = $_.esq;
    $.est = $_.est;
    if( $.dat_atr = sis_dat.est_atr_dat($esq,$est,$atr) ){
      $.esq = $.dat_atr.esq;
      $.est = $.dat_atr.est;
    }
    
    if( !!( $.dat_val = sis_dat.est($.esq,$.est,`val.${$tip}`,$dat) ) ){
      $_['val'] = $.dat_val;
    }

    return $_;    
  }// imagen por identificadores
  static val_ima( $dat, $ope ){

    let $ = {}, $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){
      
      // por atributo
      if( $.dat_atr = sis_dat.est_atr_dat($ope.esq,$ope.est,$ope.atr) ){
        if( !$ope.fic ) $ope.fic = {};
        $ope.fic.esq = $.dat_atr.esq;
        $ope.fic.est = $.dat_atr.est;
      }// por seleccion
      else if( !$ope.fic ){
        $ope.fic = api_est.val_opc('ima', $ope.esq, $ope.est );
      }

      $_ = api_fig.ima($ope.fic.esq, $ope.fic.est, sis_dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
  
  /* Ficha */
  static fic( $dat, $ope, ...$opc ){
    let $_="", $ = api_doc.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.dat_var`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? api_num.val_ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.dat_var [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      $dom.eli($ite,'.fig_ima');
      if( $.val = $.dat[$.est] ){
        $dom.agr( api_fig.ima( $.ima[0], $.ima[1], sis_dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }

  /* Informe */
  static inf( $esq, $est, $val ){
    // pido ficha
    api_eje.val([ `api_est::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) sis_app.win('doc_ope',{ 
        ico: "", 
        cab: "", 
        htm: $htm, 
        opc: [ "pos" ] 
      });
    });
  }

  /*-- Operador --*/
  static ope( $tip, $dat, $ope, ...$opc ){
    let $ = api_doc.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $dom.app.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $dom.app.var.querySelectorAll(`.dat_var > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $dom.app.var.querySelectorAll(`.dat_var > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $dom.app.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
        $.ide=$atr.name;

        $.tip = '';
        if( $atr.type ){
          if( /^date/.test($atr.type) ){
            $.tip = 'fec';
          }else if( $atr.type=='text' ){
            $.tip = 'tex';
          }else if( $atr.type=='number' ){
            $.tip = 'num';
          }
        }

        $._err[$.ide] = [];
        $._val[$.ide] = $.val = $atr.value;
        
        // obligatorio ( not null )
        if( !$.val ){          
          if( $atr.required ){
            $._err[$.ide].push(`Es Obligatorio...`);
          }// formateo nulos
          else{
            switch( $.tip ){
            case 'fec': $._val[$.ide] = null; break;
            case 'num': $._val[$.ide] = null; break;
            }
          }
        }
        // máximos ( longitud[tex] - valor[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            if( !$.val ){
              // $._err[$.ide].push(`No puede ser anterior al ${$.min}...`);
            }break;
          case 'num':
            if( $.val < parseInt($.min) ){
              $._err[$.ide].push(`No puede ser menor a ${$.min}...`);
            }break;
          case 'tex':
            if( $.val.length < parseInt($.min) ){
              $._err[$.ide].push(`No puede tener menos de ${$.min} caractéres...`);
            }break;
          }
        }
        // mínimos ( valores[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            if( !$.val ){
              // $._err[$.ide].push(`No puede ser posterior que el ${$.max}...`);
            }break;
          case 'num': 
            if( $.val > parseInt($.max) ){
              $._err[$.ide].push(`No puede ser mayor que ${$.max}...`);
            }break;
          case 'tex': 
            if( $.val.length > parseInt($.max) ){
              $._err[$.ide].push(`No puede tener más de ${$.max} caractéres...`);
            }break;
          }
        }
        // proceso errores
        if( $._err[$.ide].length ){
          // pinto fondo
          if( !$atr.classList.contains('fon-roj') ){ 
            $atr.classList.add('fon-roj'); 
          }
          // cargo lista
          $._tex = `
          <ul class='col-roj'>`; 
            $._err[$.ide].forEach( $e => $._tex += `
            <li>${_tex.let($e)}</li>`
            ); $._tex += `
          </ul>`;
          $dom.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $dom.app.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$dom.app.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $dom.app.var.dataset.esq ) && ( $.est = $dom.app.var.dataset.est ) ){
          api_eje.val(['api_est::abm', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $dom.app.var.reset();              
              // reiniciar página
              window.location.href = ( $.tip_eli ) ? window.location.href.split('/').slice(0,-1).join('/') : window.location.href;
            }
          });
        }// proceso propio
        else{

        }
      }   
      break;    
    }
    return $;
  }// acumulados : posicion + marca + seleccion
  static ope_acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $dom.est.ope.acu : $opc ).forEach( $ide => {

      // acumulo elementos del listado
      $.acu_val[$ide] = $dat.querySelectorAll(`[class*="_val-${$ide}-"]`);

      // actualizo total del operador
      if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

        $.tot.innerHTML = $.acu_val[$ide].length;
      }
    });

    // calculo el total grupal    
    if( $.tot = $ope.querySelector(`[name="tot"]`) ){
      $.tot.innerHTML = $dat.querySelectorAll(`[class*=_val-]:is([class*="-bor"],[class*="_act"])`).length;
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

      api_est.fic( $val, $.sum);
    });
  }// filtros : dato + variables
  static ope_ver( $tip, $dat, $ope, ...$opc ){

    let $ = api_doc.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver-`;
    $.cla_ide = `_val-ver_${$tip}`;
    // las clases previas se eliminan desde el proceso que me llama ( tab + est )

    $dom.app.var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'dat' ){

      $.dat_est = $dom.app.var.querySelector(`[name="est"]`);
      $.dat_ide = $dom.app.var.querySelector(`[name="ver"]`);
      $.dat_val = $dom.app.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = sis_dat.est_ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = sis_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) $dom.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $tip == 'pos' || $tip == 'fec' ){

      // valores
      $.val = {};
      $.var = {};
      ['ini','fin','inc','lim'].forEach( $ide => {
        // capturo valores
        if( ( $.ite = $dom.app.var.querySelector(`[name="${$ide}"]`) ) ){
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
          $.pos_val = parseInt($e.dataset.pos ? $e.dataset.pos : $e.classList[1].split('-')[1]);
          // valor por desde-hasta
          if( $.inc_val == 1 && $.pos_val >= $.val.ini && $.pos_val <= $.val.fin ){
            $dom.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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

            $dom.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
        if( $dom.app.var.querySelector(`.fig_ico.ide-lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) $dom.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }// conteos : valores de estructura relacionada por atributo
  static ope_cue( $tip, $dat, $ope, ...$opc ){
    let $ = api_doc.var($dat);

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

                if( ( $.dat_val = sis_dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
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

      $.ope = $dom.app.var.querySelector('[name="ope"]').value;
      $.val = $dom.app.var.querySelector('[name="val"]').value;
      $.lis = $dom.app.var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll(`tr.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( sis_dat.val( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
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

  /*-- Tabla --*/
  // inicializo : acumulados
  static lis_ini(){

    let $={};   

    if( $dom.est.lis.val_acu ){

      if( $.ele = $dom.est.lis.val_acu.querySelector(`[name="tod"]`) ){

        api_est.lis_val('tod',$.ele);
      }
    }

  }// actualizo : acumulado + cuentas + descripciones
  static lis_act(){

    let $={};
    // actualizo total
    if( $dom.est.lis.val_acu && ( $.tot = $dom.est.lis.val_acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = api_est.lis_val('tot');
    }    
    // actualizo cuentas
    if( $dom.est.lis.val_cue ){

      api_est.ope_cue('act', $dom.est.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $dom.est.lis.val_cue);
    }
    // actualizo descripciones
    if( $dom.est.lis.des ){

      $dom.est.lis.des.querySelectorAll(`[name]:checked`).forEach( $e => api_est.lis_des_tog($e) );
    }
  }// Valores: totales + acumulados
  static lis_val( $tip, $dat ){
    let $_, $ = {};
    switch( $tip ){
    case 'tot': 
      $_ = 0;
      if( $dom.est.lis.val ){
        $_ = $dom.est.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        console.error('No hay tabla relacionada...');
      }
      break;
    case 'tod': 
      $ = api_doc.var($dat);  
      
      if( $dom.est.lis.val_acu ){
        // ajusto controles acumulados
        $dom.est.ope.acu.forEach( $i => {

          if( $.val = $dom.est.lis.val_acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }
      // ejecuto todos los filtros y actualizo totales
      api_est.lis_ver();    
      break;
    case 'acu':
      if( ( $.esq = $dom.est.lis.val.dataset.esq ) && ( $.est = $dom.est.lis.val.dataset.est ) ){
        
        // oculto todos los items de la tabla
        $dom.act('cla_agr',$dom.est.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

        // actualizo por acumulado
        $dom.est.ope.acu.forEach( $ide => {

          if( $.val = $dom.est.lis.val_acu.querySelector(`[name='${$ide}']`) ){

            $.tot = 0;
            if( $.val.checked ){
              // recorro seleccionados
              $dom.est.tab.val.querySelectorAll(`.pos[class*="_val-${$ide}-"]`).forEach( $e =>{
                
                if( $.ele = $dom.est.lis.val.querySelector(`tr.pos[data-${$.esq}_${$.est}="${$e.dataset[`${$.esq}_${$.est}`]}"].${DIS_OCU}`) ){
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
  static lis_ver( $tip, $dat ){

    let $ = api_doc.var($dat);

    // ejecuto filtros
    if( !$tip || ['dat','pos','fec'].includes($tip) ){

      // 1- muestro todos
      if( !$dom.est.lis.val_acu || $dom.est.lis.val_acu.querySelector(`[name="tod"]:checked`) ){

        $dom.act('cla_eli',$dom.est.lis.val.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        api_est.lis_val('acu');
      }

      // 2- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $dom.est.ope.ver ){
        // Elimino todas las clases
        $dom.act('cla_eli',$dom.est.lis.val.querySelectorAll(`._val-ver_${$ope_ide}`),[`_val-ver-`,`_val-ver_${$ope_ide}`]);
        // tomo solo los que tienen valor
        if( ( $.val = $dom.est.lis.ver.querySelector(`${$dom.est.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){
        $.eje.forEach( $ope_ide => {
          api_est.ope_ver($ope_ide, api_lis.val_cod( $dom.est.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $dom.est.lis.ver);
          // oculto valores no seleccionados
          $dom.act('cla_agr',$dom.est.lis.val.querySelectorAll(`tr.pos:not(._val-ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else if( ['cic','gru'].includes($tip) ){
      // muestro todos los items
      $dom.act('cla_eli',$dom.est.lis.val.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
      
      // aplico filtro
      // ... 
    }
    
    // actualizo total, cuentas y descripciones
    api_est.lis_act();

  }// Columnas : toggles + atributos
  static lis_atr(){
  }// - muestro-oculto
  static lis_atr_tog( $dat ){

    let $ = api_doc.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $dom.est.lis.val.querySelectorAll(
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

        api_est.lis_atr_tog($e);
      });
    }
  }// Descripcion : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static lis_des(){
  }// - muestro-oculto
  static lis_des_tog( $dat ){

    let $ = api_doc.var($dat);
    $.ope  = $dom.app.var.classList[0].split('-')[1];
    $.esq = $dom.app.var.dataset.esq;
    $.est = $dom.app.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    $dom.act('cla_agr',$dom.est.lis.val.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $dom.est.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = sis_dat.get($.esq,$.est,$ite.dataset[`${$.esq}_${$.est}`]) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.dataset[`${$.esq}_${$.est}`] : $.val[$.atr];

          $dom.act('cla_eli',$dom.est.lis.val.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static lis_des_ver( $dat ){

    let $ = api_doc.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $dom.est.lis.val.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $dom.est.lis.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$dom.est.lis.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            $dom.act('cla_eli',$dom.est.lis.val.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $dom.est.lis.val.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $dom.est.lis.val.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      $dom.act('atr_act',$dom.est.lis.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      $dom.act('cla_agr',$dom.est.lis.val.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $dom.est.lis.val.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $dom.est.lis.val.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
  
  /*-- Tablero --*/
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

    let $ = { cla : !!$cla ? eval($cla) : false };

    // clase por posicion
    $dom.est.tab.ide = $dom.est.tab.val.classList[3];
    
    // inicializo opciones
    ['sec','pos'].forEach( $ope => {
      if( $dom.est.tab[$ope] ){
        $dom.est.tab[$ope].querySelectorAll(
          `form[class*="ide-"] [onchange*=".tab_"]:is( input:checked, select:not([value=""]) )`
        ).forEach( 
          $inp => api_est[`tab_${$ope}`]( $inp )
        );
      }
    });

    // marco posicion principal
    api_est.tab_val('pos');

    // actualizo opciones
    $dom.est.ope.acu.forEach( $ite => {
      if( $.ele = $dom.est.tab.val_acu.querySelector(`[name="${$ite}"]:checked`) ) api_est.tab_val_acu($.ele) 
    });

    // inicializo operador por aplicacion
    if( $.cla ){
      // secciones y posiciones por aplicacion
      ['sec','pos'].forEach( $ope => {
        if( $dom.est.tab[$ope] ){
          $.eje = `tab_${$ope}`;
          $dom.est.tab[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$cla}.${$.eje}"]`).forEach(

            $inp => $.cla[$.eje] && $.cla[$.eje]( $inp )
          );
        }
      });
      
      // atributos
      if( $dom.est.tab.opc ){
        $dom.est.tab.opc.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {
          
          $.eje = `tab_opc`;

          $for.querySelectorAll(`[name][onchange*="${$cla}.${$.eje}"]`).forEach( 

            $inp => !!$.cla[$.eje] && $.cla[$.eje]( $for.classList[0].split('-')[2], $inp )
          );
        });
      }
    }
  }// Actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static tab_act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $dom.est.ope.acu : api_lis.val_ite($dat);

    $.dat = $dom.est.tab.val;

    // acumulados + listado
    if( $dom.est.tab.val_acu ){ 

      // actualizo toales acumulados
      api_est.ope_acu($dom.est.tab.val, $dom.est.tab.val_acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $dom.est.tab.val_sum ){
        $.tot = [];
        $dom.est.ope.acu.forEach( $acu_ide => {

          if( $dom.est.tab.val_acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$dom.est.tab.val.querySelectorAll(`[class*="_val-${$acu_ide}-"]`) );
          }
        });
        api_est.ope_sum($.tot, $dom.est.tab.val_sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$dom.est.lis.val_acu.querySelector(`[name="tod"]:checked`) ) api_est.lis_val('acu');

      // -> ejecuto filtros + actualizo totales
      if( $dom.est.lis.ver ) api_est.lis_ver();
    }

    // fichas del tablero
    if( ( $dom.est.tab.pos ) && ( $.ima = $dom.est.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $dom.est.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) api_est.tab_pos($.ima);
    }

    // actualizo cuentas
    if( $dom.est.tab.val_cue ){

      api_est.ope_cue('act', $dom.est.tab.val.querySelectorAll(`.pos[class*=_val-]:is([class*=-bor],[class*=_act])`), $dom.est.tab.val_cue );
    }

  }// Valores
  static tab_val( $tip, $dat ){

    let $ = api_doc.var($dat);

    switch( $tip ){
    case 'pos': 

      $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}`),['_val-pos-','_val-pos-bor']);

      if( $_hol && $_hol.val && ( $.kin = $_hol.val.kin ) ){        
        $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}[data-hol_kin="${$.kin}"]`).forEach( $e => {
          $e.classList.add(`_val-pos-`);
          if( $dom.est.tab.val_acu && $dom.est.tab.val_acu.querySelector(`[name="pos"]:checked`) ){
            $e.classList.add(`_val-pos-bor`);
          }
        });
      }

      break;
    case 'mar':
      $dat.classList.toggle(`_val-mar-`);
      // marco bordes
      if( $dom.est.tab.val_acu ){
        if( $dat.classList.contains(`_val-mar-`) && $dom.est.tab.val_acu.querySelector(`[name="mar"]:checked`) ){
          $dat.classList.add(`_val-mar-bor`);
        }
        else if( !$dat.classList.contains(`_val-mar-`) && $dat.classList.contains(`_val-mar-bor`) ){
          $dat.classList.remove(`_val-mar-bor`);
        }
      }
      break;
    case 'ver':
      for( const $ide in $dom.est.ope.ope_ver ){

        if( $.ele = $dom.est.tab.ver.querySelector(`${$dom.est.ope.ope_ver[$ide]}:not([value=""])`) ){
  
          api_est.tab_ver($ide,$.ele,$dom.est.tab.val);

          break;
        }
      }
      break;
    case 'opc':
      // las 
      break;
    }
    // actualizo totales
    api_est.tab_act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static tab_val_acu( $dat, $ope ){
    
    let $ = api_doc.var($dat);

    if( !$.var_ide && $ope ) $ = api_doc.var( $dat = $dom.est.tab.val_acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_val-${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $dom.est.tab.val.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( sis_dat.val($cla,'^^',`${$.cla_ide}-`) ){
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
      $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.${$.cla_ide}-bor`),`${$.cla_ide}-bor`);
      if( $dat.checked ) $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}-bor`);
    }
    // actualizo calculos + vistas( fichas + items )        
    api_est.tab_act($.var_ide);

  }// Seleccion : datos, posicion, fecha
  static tab_ver(){

    // 1- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
    $.eje = [];
    for( const $ope_ide in $dom.est.ope.ver ){      
      // Elimino todas las clases
      $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`._val-ver_${$ope_ide}`),[`_val-ver-`,`_val-ver_${$ope_ide}`]);
      // tomo solo los que tienen valor
      if( ( $.val = $dom.est.tab.ver.querySelector(`${$dom.est.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
        $.eje.push($ope_ide);
      }
    }

    // 2- ejecuto todos los filtros
    if( $.eje.length > 0 ){
      $.eje.forEach( ($ope_ide, $ope_pos) => {
        api_est.ope_ver($ope_ide, api_lis.val_cod(
          // si es el 1° paso todas las posiciones, sino solo las filtradas
          $dom.est.tab.val.querySelectorAll( $ope_pos == 0 ? $dom.est.tab.cla : `._val-ver-` )
        ), 
          $dom.est.tab.ver, 'tab'
        );
      });
    }

    // 3- marco bordes de seleccionados
    $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll('._val-ver-bor'),'_val-ver-bor');
    if( $dom.est.tab.val_acu && $dom.est.tab.val_acu.querySelector(`[name="ver"]:checked`) ){
      $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`._val-ver-`),'_val-ver-bor');
    }

    // actualizo calculos + vistas( fichas + items )
    api_est.tab_act('ver');
    
  }// Secciones : bordes + colores + imagen + ...
  static tab_sec( $dat ){

    let $ = api_doc.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$dom.est.tab.val.classList.contains('bor-1') ){ $dom.est.tab.val.classList.add('bor-1'); }
        $dom.est.tab.val.querySelectorAll('.tab:not(.bor-1)').forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $dom.est.tab.val.classList.contains('bor-1') ){ $dom.est.tab.val.classList.remove('bor-1'); }
        $dom.est.tab.val.querySelectorAll('.tab.bor-1').forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $dom.est.tab.val.querySelectorAll(`.tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $dom.est.tab.val.classList.contains('fon-0') ){
          $dom.est.tab.val.classList.remove('fon-0');
        }
      }else{
        // secciones
        $dom.est.tab.val.querySelectorAll(`.tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$dom.est.tab.val.classList.contains('fon-0') ){
          $dom.est.tab.val.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $dom.est.tab.val.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $dom.est.tab.val.style.backgroundImage = '';
      }
      break;      
    }     
  }// Posiciones : borde + color + imagen + texto + numero + fecha
  static tab_pos( $dat ){

    let $ = api_doc.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $dom.est.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $dom.est.ope.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $dom.est.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      $.eli = `${$dom.est.tab.cla}[class*='${$.ope}']`;
      $.agr = `${$dom.est.tab.cla}`;

      $dom.est.tab.val.querySelectorAll($.eli).forEach( $e => api_ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = sis_dat.est_ide($dat.value,$);

        $.dat_ide = ( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.');

        $.col_dat = api_est.val_ide('col', ...$.dat_ide );

        $.col = ( $.col_dat && $.col_dat.val ) ? $.col_dat.val : 1;

        $dom.est.tab.val.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = sis_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : api_num.val_ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $dom.est.tab.val.querySelectorAll($dom.est.tab.cla).forEach( $e => {

        $e.querySelectorAll('.fig_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = sis_dat.est_ide($dat.value,$);        
        // busco valores de ficha
        $.fic = api_est.val_ide('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $dom.est.tab.val.querySelectorAll($dom.est.tab.cla).forEach( $e => {
          // capturar posicion .dep
          $.htm = '';
          $.ele = { title : false, onclick : false  };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_val-pos-') ){ 
              $.htm = api_est.val_ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_val-mar-') ){ 
              $.htm = api_est.val_ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_val-ver-') ){ 
              $.htm = api_est.val_ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_val-opc-/.test($cla_nom) ) return $.htm = api_est.val_ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = api_est.val_ima($e,$);
          }
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.fig_ima') ) ? $dom.mod($.htm,$.ima_ele) : $dom.agr($.htm,$e,'ini');
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
      $dom.est.tab.val.querySelectorAll($dom.est.tab.cla).forEach( $e => $dom.eli($e,$.eti) );

      if( $dat.value ){

        $ = sis_dat.est_ide($dat.value,$);

        $dom.est.tab.val.querySelectorAll($dom.est.tab.cla).forEach( $e =>{

          if( $.obj = sis_dat.get($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ){

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