// WINDOW
'use strict';

// Valor
class app_val {

  // alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $ = app.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $_app.ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $_app.ope.var.querySelectorAll(`.atr > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $_app.ope.var.querySelectorAll(`.atr > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $_app.ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
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
            <li>${_app.let($e)}</li>`
            ); $._tex += `
          </ul>`;
          api_ele.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $_app.ope.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$_app.ope.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $_app.ope.var.dataset.esq ) && ( $.est = $_app.ope.var.dataset.est ) ){
          api_eje.val(['app_dat.', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $_app.ope.var.reset();              
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
  }
  // acumulados : posicion + marca + seleccion
  static acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $_app.val_ope.acu : $opc ).forEach( $ide => {

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
  }
  // sumatorias
  static sum( $dat, $ope ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;
      $dat.forEach( $ite => $.sum += parseInt( $ite.dataset[`${$val.dataset.esq}_${$val.dataset.est}`] ) );

      app_dat.fic( $val, $.sum);
    });
  }
  // filtros : dato + variables
  static ver( $tip, $dat, $ope, ...$opc ){

    let $ = app.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_val-ver-`;
    $.cla_ide=`${$.cla_val}_${$tip}`;
    
    api_ele.act('cla_eli',$dat,[$.cla_val, $.cla_ide]);

    $_app.ope.var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'val' ){

      $.dat_est = $_app.ope.var.querySelector(`[name="est"]`);
      $.dat_ide=$_app.ope.var.querySelector(`[name="ver"]`);
      $.dat_val = $_app.ope.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = api_dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = api.dat($.esq,$.est,$e.dataset[`${$.esq}_${$.est}`]) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) api_ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
          }
        });
      }
    }
    // listado : posicion + fecja
    else if( $tip == 'pos' || $tip == 'fec' ){
      
      // elimino valor de dato por seleccion
      if( ( $.ver = $ope.querySelector(`form.ide-dat select[name="val"]`) ) && !!$.ver.value ) $.ver.value = '';
            
      // valores
      $.val = {};
      ['ini','fin','inc','lim'].forEach( $ide => {
        // capturo valores
        if( ( $.ite = $_app.ope.var.querySelector(`[name="${$ide}"]`) ) && !!$.ite.value ){

          $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? api_num.val($.ite.value) : $.ite.value;
        }
      });
      
      // valido: si el inicio es mayor que el final
      if( $.val.ini && $.val.ini > $.val.fin ){

        $_app.ope.var.querySelector(`[name="ini"]`).value = $.val.ini = $.val.fin;
      }
      // si el final es mejor que el inicio
      if( $.val.fin && $.val.fin < $.val.ini ){

        $_app.ope.var.querySelector(`[name="fin"]`).value = $.val.fin = $.val.ini;
      }    
      // inicializo incremento
      $.inc_val = 1;
      if( ( !$.val.inc || $.val.inc <= 0 ) && ( $.ite = $_app.ope.var.querySelector(`[name="inc"]`) ) ){
        $.ite.value = $.val.inc = 1;
      }
      // inicializo limites desde
      if( !$.val.fin 
        && ( $.ite = $_app.ope.var.querySelector(`[name="fin"]`) ) && ( $.max = $.ite.getAttribute('max') ) 
      ){
        $.val.fin = $.max;
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
          if( $.inc_val == 1 && api_fec.ver( $e.dataset['fec_dat'], $.val.ini, $.val.fin ) ){

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
        if( $_app.ope.var.querySelector(`.ico.lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) api_ele.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }
  // conteos : valores de estructura relacionada por atributo
  static cue( $tip, $dat, $ope, ...$opc ){
    let $ = app.var($dat);

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

                if( ( $.dat_val = api.dat($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
              }
            });

            $ite.querySelector('td[data-atr="tot"] > n').innerHTML = $.tot;
            $ite.querySelector('td[data-atr="por"] > n').innerHTML = $.val_tot ? api_num.dec( ( $.tot / $.val_tot ) * 100 ) : $.val_tot;
          });
        }
      });
      break;
    // filtro por valor textual
    case 'ver':

      $.ope = $_app.ope.var.querySelector('[name="ope"]').value;
      $.val = $_app.ope.var.querySelector('[name="val"]').value;
      $.lis = $_app.ope.var.nextElementSibling.querySelector('tbody');
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
}