// WINDOW
'use strict';

class Doc_Dat {

  /* Valores: nombre, descripcion, tablero, imagen, color, cantidad, texto, numero */
  static val( $tip, $ide, $dat, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = Dat.ide($ide,$);
    // cargo datos
    $._dat = Dat.get($.esq,$.est,$dat);
    // cargo valores
    $._val = Dat.est($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = Obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+Obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $.tip_val = typeof($._val[$tip]);
      if( $tip == 'ima' ){
        if( $.tip_val != 'string' ) $tip = 'tab';
      }
      else if( $.tip_val == 'string' ){
        $_ = Obj.val($._dat,$._val[$tip]);  
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

        $._dat = Dat.get($.esq,$.est,$dat_val);

        $.ima_ele['data-ide'] = $._dat.ide;
        
        // titulos
        if( $.ima_ele.title === undefined ){

          $.ima_ele.title = Doc_Dat.val('tit',`${$.esq}.${$.est}`,$._dat);
        }
        else if( $.ima_ele.title === false ){
          delete($.ima_ele.title);
        }
        // acceso informe
        if( $.ima_ele.onclick === undefined ){
          if( Dat.est($.esq,$.est,'inf') ) $.ima_ele.onclick = `Doc_Dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
        }
        else if( $.ima_ele.onclick === false ){

          delete($.ima_ele.onclick);
        }
        // informe      
        $_ += Doc_Val.ima( { 'style': Obj.val($._dat,$._val[$tip]) }, $.ima_ele );
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
      $opc[0]['htm'] = Doc_Val.let($_);
      $_ = Ele.val($opc[0]);
    }

    return $_;
  }
  
  /* Informe por Estructura */
  static inf( $esq, $est, $val ){
    // pido ficha
    Eje.val([ `Doc_Dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) Doc_Ope.win('app_ope',{ 
        ico: "",
        cab: "",
        htm: $htm,
        opc: [ "pos" ]
      });
    });
  }
  
  /* opciones : esquema.estructura.atributos.valores */
  static opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = Doc_Ope.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $Doc.Ope.var.querySelector(`[name="${$i}"]`) ) dom.eli( $.ope, `option:not([value=""])` ); 
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
        Ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          Ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        dom.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';

        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = Dat.ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          Eje.val(['Dat::get', [`${$.esq}-${$.est}`] ], $dat => {
            $.opc = Doc_Val.opc( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }
  
  /* imagen por identificadores */
  static ima( $dat, $ope ){

    let $ = {}, $_ = "";    

    if( $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.getAttribute(`${$ope.esq}-${$ope.est}`) ) ){
      
      // por atributo
      if( $.dat_atr = Dat.est_rel($ope.esq,$ope.est,$ope.atr) ){
        if( !$ope.fic ) $ope.fic = {};
        $ope.fic.esq = $.dat_atr.esq;
        $ope.fic.est = $.dat_atr.est;
      }// por seleccion
      else if( !$ope.fic ){
        $ope.fic = Doc_Dat.opc('ima', $ope.esq, $ope.est );
      }
      
      $_ = Doc_Val.ima($ope.fic.esq, $ope.fic.est, Dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
  
  /* Ficha */
  static fic( $dat, $ope, ...$opc ){
    let $_="", $ = Doc_Ope.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.ope_var`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? Num.ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.ope_var [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('-');
      // actualizo fichas
      dom.eli($ite,'.val_ima');
      if( $.val = $.dat[$.est] ){
        dom.agr( Doc_Val.ima( $.ima[0], $.ima[1], Dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }  

  /*-- Operaciones de Lista --*/

  /* Procesos */
  static ope(){
  }// acumulados : posicion + marca + seleccion
  static ope_acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $Doc.Dat.ope.acu : $opc ).forEach( $ide => {

      // acumulo elementos del listado
      $.acu_val[$ide] = $dat.querySelectorAll(`[class*="_${$ide}-"]`);

      // actualizo total del operador
      if( $.tot = $ope.querySelector(`[name="${$ide}"] ~ span > n`) ){

        $.tot.innerHTML = $.acu_val[$ide].length;
      }
    });

    // calculo el total grupal    
    if( $.tot = $ope.querySelector(`[name="tot"]`) ){
      $.tot.innerHTML = $dat.querySelectorAll(`.pos.ope:is([class*="-bor"],[class*="_act"])`).length;
    }

    // devuelvo seleccion
    return $.acu_val;
  }// sumatorias
  static ope_sum( $dat, $ope ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $ope.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;
      $dat.forEach( $ite => $.sum += parseInt( $ite.getAttribute(`${$val.dataset.esq}-${$val.dataset.est}`) ) );

      Doc_Dat.fic( $val, $.sum);
    });
  }// filtros : dato + variables
  static ope_ver( $tip, $dat, $ope, ...$opc ){

    let $ = Doc_Ope.var($dat);

    $._tip = $tip.split('-');

    $.cla_val = `_ver-`;
    $.cla_ide = `_ver_${$tip}`;
    // las clases previas se eliminan desde el proceso que me llama ( tab + est )

    $Doc.Ope.var = $ope.querySelector(`form.ide-${$tip}`);

    // datos de la base : estructura > valores [+ima]
    if( $tip == 'dat' ){

      $.dat_est = $Doc.Ope.var.querySelector(`[name="est"]`);
      $.dat_ide = $Doc.Ope.var.querySelector(`[name="ver"]`);
      $.dat_val = $Doc.Ope.var.querySelector(`[name="val"]`);     

      // actualizo dependencia
      if( $.dat_ide.value && $.dat_val.value ){
          
        $ = Dat.ide($.dat_ide.value,$);
      
        $dat.forEach( $e =>{

          if( ( $.dat = Dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ) ){

            if( $.dat[$.atr] == $.dat_val.value ) Ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
        if( ( $.ite = $Doc.Ope.var.querySelector(`[name="${$ide}"]`) ) ){
          $.var[$ide] = $.ite;
          if( !!$.ite.value ) $.val[$ide] = ( $.ite.getAttribute('type') == 'number' ) ? Num.val($.ite.value) : $.ite.value;
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
            Ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
          if( $.inc_val == 1 && Fec.val_ver( $e.getAttribute('sis-fec'), $.val.ini, $.val.fin ) ){

            Ele.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
        if( $Doc.Ope.var.querySelector(`.val_ico.ide-lis_fin.bor-sel`) ) $.lis = $.lis.reverse();

        $.lim_cue = 0;
        $.lis.forEach( $e => {
          $.lim_cue ++;
          if( $.lim_cue > $.val.lim ) Ele.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }// conteos : valores de estructura relacionada por atributo
  static ope_cue( $tip, $dat, $ope, ...$opc ){
    let $ = Doc_Ope.var($dat);

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

              if( $.dat = $v.getAttribute(`${$.esq}-${$.est}`) ){

                if( ( $.dat_val = Dat.get($.esq,$.est,$.dat) ) && ( $.dat_ide=$.dat_val[$.atr] ) && $.dat_ide == $.ide ) $.tot++;
              }
            });

            $ite.querySelector('td[data-atr="tot"] > n').innerHTML = $.tot;
            $ite.querySelector('td[data-atr="por"] > n').innerHTML = $.val_tot ? Num.dec( ( $.tot / $.val_tot ) * 100 ) : $.val_tot;
          });
        }
      });
      break;
    // filtro por valor textual
    case 'ver':

      $.ope = $Doc.Ope.var.querySelector('[name="ope"]').value;
      $.val = $Doc.Ope.var.querySelector('[name="val"]').value;
      $.lis = $Doc.Ope.var.nextElementSibling.querySelector('tbody');
      if( !$.val ){

        $.lis.querySelectorAll(`tr.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
      }
      else{
        
        $.lis.querySelectorAll('tr').forEach( $e => {

          if( Dat.ver( $e.querySelector('td[data-atr="nom"]').innerHTML, $.ope, $.val ) ){
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU);
          }
          else if( !$e.classList.contains(DIS_OCU) ){
            $e.classList.add(DIS_OCU);
          }
        });
      }
      break;              
    }    
  }// alta, baja y modificacion
  static ope_abm( $tip, $dat, $ope, ...$opc ){
    let $ = Doc_Ope.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $Doc.Ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $Doc.Ope.var.querySelectorAll(`.ope_var > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $Doc.Ope.var.querySelectorAll(`.ope_var > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $Doc.Ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
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
          dom.agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $Doc.Ope.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$Doc.Ope.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $Doc.Ope.var.dataset.esq ) && ( $.est = $Doc.Ope.var.dataset.est ) ){
          Eje.val(['Dat::abm', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $Doc.Ope.var.reset();              
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

  /* Tabla */
  static lis(){
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
  }// Inicializo : acumulados
  static lis_ini(){

    let $={};   

    if( $Doc.Dat.lis.ope_acu ){

      if( $.ele = $Doc.Dat.lis.ope_acu.querySelector(`[name="tod"]`) ){

        Doc_Dat.lis_ope('tod',$.ele);
      }
    }

  }// Actualizo : acumulado + cuentas + descripciones
  static lis_act(){

    let $={};
    // actualizo total
    if( $Doc.Dat.lis.ope_acu && ( $.tot = $Doc.Dat.lis.ope_acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = Doc_Dat.lis_ope('tot');
    }    
    // actualizo cuentas
    if( $Doc.Dat.lis.ope_cue ){

      Doc_Dat.ope_cue('act', $Doc.Dat.lis.ope.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $Doc.Dat.lis.ope_cue);
    }
    // actualizo descripciones
    if( $Doc.Dat.lis.des ){

      $Doc.Dat.lis.des.querySelectorAll(`[name]:checked`).forEach( $e => Doc_Dat.lis_des_tog($e) );
    }
  }// Valores: totales + acumulados
  static lis_ope( $tip, $dat ){
    let $_, $ = {};
    switch( $tip ){
    case 'tot': 
      $_ = 0;
      if( $Doc.Dat.lis.ope ){
        $_ = $Doc.Dat.lis.ope.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{
        console.error('No hay tabla relacionada...');
      }
      break;
    case 'tod': 
      $ = Doc_Ope.var($dat);  
      
      if( $Doc.Dat.lis.ope_acu ){
        // ajusto controles acumulados
        $Doc.Dat.ope.acu.forEach( $i => {

          if( $.val = $Doc.Dat.lis.ope_acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }
      // ejecuto todos los filtros y actualizo totales
      Doc_Dat.lis_ver();    
      break;
    case 'acu':
      if( ( $.esq = $Doc.Dat.lis.ope.dataset.esq ) && ( $.est = $Doc.Dat.lis.ope.dataset.est ) ){
        
        // oculto todos los items de la tabla
        Ele.act('cla_agr',$Doc.Dat.lis.ope.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

        // actualizo por acumulado
        $Doc.Dat.ope.acu.forEach( $ide => {

          if( $.val = $Doc.Dat.lis.ope_acu.querySelector(`[name='${$ide}']`) ){

            $.tot = 0;
            if( $.val.checked ){
              // recorro seleccionados
              $Doc.Dat.tab.ope.querySelectorAll(`.pos[class*="_${$ide}-"]`).forEach( $e =>{
                
                if( $.ele = $Doc.Dat.lis.ope.querySelector(`tr.pos[${$.esq}-${$.est}="${$e.getAttribute(`${$.esq}_${$.est}`)}"].${DIS_OCU}`) ){
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

    let $ = Doc_Ope.var($dat);

    // ejecuto filtros
    if( !$tip || ['dat','pos','fec'].includes($tip) ){

      // 1- muestro todos
      if( !$Doc.Dat.lis.ope_acu || $Doc.Dat.lis.ope_acu.querySelector(`[name="tod"]:checked`) ){

        Ele.act('cla_eli',$Doc.Dat.lis.ope.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }// o muestro solo acumulados
      else{
        Doc_Dat.lis_ope('acu');
      }

      // 2- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $Doc.Dat.ope.ver ){
        // Elimino todas las clases
        Ele.act('cla_eli',$Doc.Dat.lis.ope.querySelectorAll(`._ver_${$ope_ide}`),[`_ver-`,`_ver_${$ope_ide}`]);
        // tomo solo los que tienen valor
        if( ( $.val = $Doc.Dat.lis.ver.querySelector(`${$Doc.Dat.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){
        $.eje.forEach( $ope_ide => {
          Doc_Dat.ope_ver($ope_ide, Obj.pos( $Doc.Dat.lis.ope.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $Doc.Dat.lis.ver);
          // oculto valores no seleccionados
          Ele.act('cla_agr',$Doc.Dat.lis.ope.querySelectorAll(`tr.pos:not(._ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else if( ['cic','gru'].includes($tip) ){
      // muestro todos los items
      Ele.act('cla_eli',$Doc.Dat.lis.ope.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
      
      // aplico filtro
      // ... 
    }
    
    // actualizo total, cuentas y descripciones
    Doc_Dat.lis_act();

  }// Columnas : toggles + atributos
  static lis_atr(){
  }// - muestro-oculto
  static lis_atr_tog( $dat ){

    let $ = Doc_Ope.var($dat);      

    $.esq = $dat.dataset.esq;
    $.est = $dat.dataset.est;

    // checkbox
    if( $dat.nodeName == 'INPUT' ){

      $Doc.Dat.lis.ope.querySelectorAll(
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

        Doc_Dat.lis_atr_tog($e);
      });
    }
  }// Descripcion : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static lis_des(){
  }// - muestro-oculto
  static lis_des_tog( $dat ){

    let $ = Doc_Ope.var($dat);
    $.ope  = $Doc.Ope.var.classList[0].split('-')[1];
    $.esq = $Doc.Ope.var.dataset.esq;
    $.est = $Doc.Ope.var.dataset.est;
    $.atr = $.var_ide;
    
    // oculto todos
    Ele.act('cla_agr',$Doc.Dat.lis.ope.querySelectorAll(
      `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
    ),DIS_OCU);
    
    // muestro titulos y lecturas para los que no están ocultos
    if( $dat.checked ){

      $Doc.Dat.lis.ope.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {

        if( ( $.val = Dat.get($.esq,$.est,$ite.getAttribute(`${$.esq}-${$.est}`)) ) && $.val[$.atr] ){

          $.ide=( $.ope == 'des' ) ? $ite.getAttribute(`${$.esq}-${$.est}`) : $.val[$.atr];

          Ele.act('cla_eli',$Doc.Dat.lis.ope.querySelectorAll(
            `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
          ),DIS_OCU)          
        }
      });
    }   

  }// - filtro por descripciones
  static lis_des_ver( $dat ){

    let $ = Doc_Ope.var($dat);    

    // por selectores : titulo + detalle + lectura 
    if( ['tit','det'].includes($.var_ide) ){
  
      // oculto por cilcos y agrupaciones
      $Doc.Dat.lis.ope.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

      // estructura
      if( $.est = $Doc.Dat.lis.ver.querySelector(`form.ide-dat select[name] + .dep:not(.${DIS_OCU})`) ){
        $.est = $.est.previousElementSibling.querySelector('select');
        $.opc = $.est.parentElement.parentElement.dataset.atr;
        // valor de dependencia
        $.ide=$Doc.Dat.lis.ver.querySelector(`form.ide-dat select[name="${$.opc}"] + div.dep > select:not(.${DIS_OCU})`);
      }
      // muestro        
      if( $dat.checked && ( $.est || $.ide ) ){
        $.atr = $.est.value.split('-')[1];
        // titulo por atributo seleccionado
        if( $.ite == 'tit' ){
          // no considero agrupaciones sin valor
          if( $.opc != 'gru' || ( !!$.ide && !!$.ide.value ) ){

            $.agr = !!$.ide && $.ide.value ? `.ide-${$.ide.value}` : '';

            Ele.act('cla_eli',$Doc.Dat.lis.ope.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
          }
        }// descripciones por item no oculto
        else{
          $Doc.Dat.lis.ope.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

            if( $.lis_ite = $Doc.Dat.lis.ope.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
              $.lis_ite.classList.remove(DIS_OCU);
            }
          });
        }
      }
    }
    // muestro por lecturas
    else if( $.var_ide == 'des' ){

      // desmarco otras opciones
      Ele.act('atr_act',$Doc.Dat.lis.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

      // oculto todas las leyendas
      Ele.act('cla_agr',$Doc.Dat.lis.ope.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

      // muestro por atributo seleccionado      
      if( $dat.checked ){

        $Doc.Dat.lis.ope.querySelectorAll(`tbody trnot(.pos,.${DIS_OCU})`).forEach( $e => {

          if( $.lec = $Doc.Dat.lis.ope.querySelector(
            `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
          ) ){
            $.lec.classList.remove(DIS_OCU);
          }
        });
      }
    }
  }
  
  /* Tablero */
  static tab(){
  }// Inicializo : opciones, posicion, filtros
  static tab_ini(){

    let $ = {};

    // identificador de clase principal: .dat_tab.$esq.$est.$atr
    $Doc.Dat.tab.ide = $Doc.Dat.tab.ope.classList[1].split('_')[0];
    
    // inicializo opciones
    ['sec','pos'].forEach( $ope => {

      if( $Doc.Dat.tab[$ope] ){

        $Doc.Dat.tab[$ope].querySelectorAll(
          `form[class*="ide-"] [onchange*="Doc_Dat.tab_"]:is( input:checked, select:not([value=""]) )`
        ).forEach( 

          $inp => Doc_Dat[`tab_${$ope}`]( $inp )
        );
      }
    });

    // marco posicion principal
    Doc_Dat.tab_ope('pos');

    // actualizo opciones
    $Doc.Dat.ope.acu.forEach( $ite => {

      if( $.ele = $Doc.Dat.tab.ope_acu.querySelector(`[name="${$ite}"]:checked`) ) Doc_Dat.tab_val_acu($.ele) 
    });
    
    // secciones y posiciones por aplicacion
    ['sec','pos'].forEach( $ope => {

      if( ( $.eje = window[$.eje_ide = `tab_${$ope}`] ) && $Doc.Dat.tab[$ope] ){
        
        $Doc.Dat.tab[$ope].querySelectorAll(`form[class*="ide-"] [name][onchange*="${$.eje_ide}("]`).forEach(

          $inp => $.eje( $inp )
        );
      }
    });
    
    // opciones por aplicacion
    if( ( $.eje = window[$.eje_ide = `tab_opc`] ) && $Doc.Dat.tab.opc ){      

      $Doc.Dat.tab.opc.querySelectorAll(`form[class*="ide-"]`).forEach( $for => {        

        $for.querySelectorAll(`[name][onchange*="${$.eje_ide}("]`).forEach( 

          $inp => $.eje( $for.classList[0].split('-')[2], $inp )
        );
      });
    }
    
  }// Actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static tab_act( $dat ){
    
    let $={};
    
    $dat = !$dat ? $Doc.Dat.ope.acu : Obj.pos_ite($dat);

    $.dat = $Doc.Dat.tab.ope;

    // acumulados + listado
    if( $Doc.Dat.tab.ope_acu ){ 

      // actualizo toales acumulados
      Doc_Dat.ope_acu($Doc.Dat.tab.ope, $Doc.Dat.tab.ope_acu, ...$dat);
            
      // actualizo sumatorias por acumulados
      if( $Doc.Dat.tab.ope_sum ){
        $.tot = [];
        $Doc.Dat.ope.acu.forEach( $acu_ide => {

          if( $Doc.Dat.tab.ope_acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$Doc.Dat.tab.ope.querySelectorAll(`[class*="_${$acu_ide}-"]`) );
          }
        });
        Doc_Dat.ope_sum($.tot, $Doc.Dat.tab.ope_sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$Doc.Dat.lis.ope_acu.querySelector(`[name="tod"]:checked`) ) Doc_Dat.lis_ope('acu');

      // -> ejecuto filtros + actualizo totales
      if( $Doc.Dat.lis.ver ) Doc_Dat.lis_ver();
    }

    // fichas del tablero
    if( ( $Doc.Dat.tab.pos ) && ( $.ima = $Doc.Dat.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $dat.forEach( $ide => ( $.val = $Doc.Dat.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ) && $.ope.push($.val) );
      if( $.ope.length > 0 ) Doc_Dat.tab_pos($.ima);
    }

    // actualizo cuentas
    if( $Doc.Dat.tab.ope_cue ){

      Doc_Dat.ope_cue('act', $Doc.Dat.tab.ope.querySelectorAll(`.pos.ope:is([class*=-bor],[class*=_act])`), $Doc.Dat.tab.ope_cue );
    }

  }// Valores
  static tab_ope( $tip, $dat ){

    let $ = Doc_Ope.var($dat);

    switch( $tip ){
    case 'pos':       

      if( window['tab_val_pos'] ){

        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}._pos-`),['_pos-','_pos-bor']);

        tab_val_pos( $dat );

      }
      break;
    case 'mar':
      $dat.classList.toggle(`_mar-`);
      // marco bordes
      if( $Doc.Dat.tab.ope_acu ){
        if( $dat.classList.contains(`_mar-`) && $Doc.Dat.tab.ope_acu.querySelector(`[name="mar"]:checked`) ){
          $dat.classList.add(`_mar-bor`);
        }
        else if( !$dat.classList.contains(`_mar-`) && $dat.classList.contains(`_mar-bor`) ){
          $dat.classList.remove(`_mar-bor`);
        }
      }
      break;
    case 'ver':
      for( const $ide in $Doc.Dat.ope.ope_ver ){

        if( $.ele = $Doc.Dat.tab.ver.querySelector(`${$Doc.Dat.ope.ope_ver[$ide]}:not([value=""])`) ){
  
          Doc_Dat.tab_ver($ide,$.ele,$Doc.Dat.tab.ope);

          break;
        }
      }
      break;
    case 'opc': break;
    }
    // actualizo totales
    Doc_Dat.tab_act($tip);
    
  }// - acumulados( posicion + marcas + seleccion )
  static tab_val_acu( $dat, $ope ){
    
    let $ = Doc_Ope.var($dat);

    if( !$.var_ide && $ope ) $ = Doc_Ope.var( $dat = $Doc.Dat.tab.ope_acu.querySelector(`[name="${$ope}"]`) );
    
    // busco marcas 
    $.cla_ide = `_${$.var_ide}`;
    
    // marcas por opciones
    if( $.var_ide == 'opc' ){
      $Doc.Dat.tab.ope.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
        // recorro clases de la posicion
        $ite.classList.forEach( $cla => {
          // si tiene alguna opcion activa
          if( Dat.ver($cla,'^^',`${$.cla_ide}-`) ){

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
      Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.${$.cla_ide}-bor`),`${$.cla_ide}-bor`);

      if( $dat.checked ) Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}-bor`);
    }

    // actualizo calculos + vistas( fichas + items )
    Doc_Dat.tab_act($.var_ide);

  }// Seleccion : datos, posicion, fecha
  static tab_ver(){

    let $ = {};

    // 1- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
    $.eje = [];
    for( const $ope_ide in $Doc.Dat.ope.ver ){      
      
      // Elimino todas las clases
      Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`._ver_${$ope_ide}`),[`_ver-`,`_ver_${$ope_ide}`]);
      
      // tomo solo los que tienen valor
      if( ( $.val = $Doc.Dat.tab.ver.querySelector(`${$Doc.Dat.ope.ver[$ope_ide]}`) ) && !!$.val.value ){
        $.eje.push($ope_ide);
      }
    }

    // 2- ejecuto todos los filtros
    if( $.eje.length > 0 ){
      $.eje.forEach( ($ope_ide, $ope_pos) => {
        Doc_Dat.ope_ver($ope_ide, Obj.pos(
          // si es el 1° paso todas las posiciones, sino solo las filtradas
          $Doc.Dat.tab.ope.querySelectorAll( $ope_pos == 0 ? $Doc.Dat.tab.cla : `._ver-` )
        ), 
          $Doc.Dat.tab.ver, 'tab'
        );
      });
    }

    // 3- marco bordes de seleccionados
    Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll('._ver-bor'),'_ver-bor');
    if( $Doc.Dat.tab.ope_acu && $Doc.Dat.tab.ope_acu.querySelector(`[name="ver"]:checked`) ){
      Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`._ver-`),'_ver-bor');
    }

    // actualizo calculos + vistas( fichas + items )
    Doc_Dat.tab_act('ver');
    
  }// Secciones : bordes + colores + imagen + ...
  static tab_sec( $dat ){

    let $ = Doc_Ope.var($dat); 

    switch( $.var_ide ){
    case 'bor':
      if( $dat.checked ){
        if( !$Doc.Dat.tab.ope.classList.contains('bor-1') ){ $Doc.Dat.tab.ope.classList.add('bor-1'); }
        $Doc.Dat.tab.ope.querySelectorAll(`.dat_tab:not(.bor-1)`).forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $Doc.Dat.tab.ope.classList.contains('bor-1') ){ $Doc.Dat.tab.ope.classList.remove('bor-1'); }
        $Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.bor-1`).forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    case 'col' :
      if( $dat.checked ){
        // secciones
        $Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $Doc.Dat.tab.ope.classList.contains('fon-0') ){
          $Doc.Dat.tab.ope.classList.remove('fon-0');
        }
      }else{
        // secciones
        $Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$Doc.Dat.tab.ope.classList.contains('fon-0') ){
          $Doc.Dat.tab.ope.classList.add('fon-0');
        }
      }
      break;
    case 'ima' :
      if( $dat.files && $dat.files[0] ){
        $Doc.Dat.tab.ope.style.backgroundImage = `url('${URL.createObjectURL($dat.files[0])}')`;
      }else{
        $Doc.Dat.tab.ope.style.backgroundImage = '';
      }
      break;      
    }     
  }// Posiciones : borde + color + imagen + texto + numero + fecha
  static tab_pos( $dat ){

    let $ = Doc_Ope.var($dat); 
    
    if( ( $.var_ide = $.var_ide.split('_')[0] ) != 'bor' ){
      // aseguro selector
      if( !$dat.options  ){
        $dat = $Doc.Dat.tab.pos.querySelector(`[name="${$.var_ide}"]`);
      }
      // opciones por valores
      $[$.var_ide] = {};
      $Doc.Dat.ope.acu.forEach( $ver =>{
        if( $[$.var_ide][$ver] = $Doc.Dat.tab.pos.querySelector(`[name="${$.var_ide}_${$ver}"]`) ){ 
          $[$.var_ide][$ver] = $[$.var_ide][$ver].checked;
        }
      });    
    }

    switch( $.var_ide ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $dat.checked ){
        $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      $.eli = `${$Doc.Dat.tab.cla}[class*='${$.ope}']`;
      $.agr = `${$Doc.Dat.tab.cla}`;

      $Doc.Dat.tab.ope.querySelectorAll($.eli).forEach( $e => Ele.cla($e,$.ope,'eli','ini' ) );

      if( $dat.value ){

        $ = Dat.ide($dat.value,$);

        $.dat_ide = ( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.');

        $.col_dat = Dat.est_ide('col', ...$.dat_ide );

        $.col = ( $.col_dat && $.col_dat.val ) ? $.col_dat.val : 1;

        $Doc.Dat.tab.ope.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = Dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : Num.ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      // elimino fichas
      $Doc.Dat.tab.ope.querySelectorAll($Doc.Dat.tab.cla).forEach( $e => {

        $e.querySelectorAll('.val_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });      
      if( $dat.value ){
        // busco identificadores de datos
        $ = Dat.ide($dat.value,$);        
        // busco valores de ficha
        $.fic = Dat.est_ide('ima', ...( ( $.dat = $dat.options[$dat.selectedIndex].getAttribute('dat') ) ? $.dat : $dat.value ).split('.') );
        // actualizo por opciones                
        $Doc.Dat.tab.ope.querySelectorAll($Doc.Dat.tab.cla).forEach( $e => {
          // capturar posicion .dep
          $.htm = '';
          $.ele = { title : false, onclick : false  };
          if( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ){

            if( $.ima.pos && $e.classList.contains('_pos-') ){ 
              $.htm = Doc_Dat.ima($e,$);
            }
            else if( $.ima.mar && $e.classList.contains('_mar-') ){ 
              $.htm = Doc_Dat.ima($e,$);
            }
            else if( $.ima.ver && $e.classList.contains('_ver-') ){ 
              $.htm = Doc_Dat.ima($e,$);
            }
            else if( $.ima.opc ){
              $e.classList.forEach( $cla_nom => {
                if( /_opc-/.test($cla_nom) ) return $.htm = Doc_Dat.ima($e,$);
              });
            }
          }// todos
          else{
            $.htm = Doc_Dat.ima($e,$);
          }
          if( $.htm ){
            ( $.ima_ele = $e.querySelector('.val_ima') ) ? dom.mod($.htm,$.ima_ele) : dom.agr($.htm,$e,'ini');
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
      $Doc.Dat.tab.ope.querySelectorAll($Doc.Dat.tab.cla).forEach( $e => dom.eli($e,$.eti) );

      if( $dat.value ){

        $ = Dat.ide($dat.value,$);

        $Doc.Dat.tab.ope.querySelectorAll($Doc.Dat.tab.cla).forEach( $e =>{

          if( $.obj = Dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

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