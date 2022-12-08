// WINDOW
'use strict';

// Holon : ns.ani.lun.dia:kin
class hol {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_hol || $api_hol[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_hol[$est];

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        case 'fec':
          // ...calculo fecha
          break;
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

  /* Imagen 
  */ 
  static ima( $est, $dat, $ele ){

    return arc.ima('hol',`${$est}`,$dat,$ele);
  }

  /* Tablero
  */
  // Valores acumulados
  static tab_val( $ope ){

    let $ = doc.var($ope);

    // portales + parejas + pulsares
    $.ide = $api_doc._var.classList[0].split('-')[2];
    
    // Actualizo total por item
    if( $ope.nextElementSibling && ( $.tot = $ope.nextElementSibling.querySelector('n') ) ){

      $.tot.innerHTML = $api_lis._tab.val.querySelectorAll(`._hol-${$.ide}_${$.var_ide}`).length;
    }

    // Actualizo total general
    if( $.tot = $api_doc._var.querySelector('div.var > [name="cue"]') ){

      $.tot.innerHTML = $api_lis._tab.val.querySelectorAll(`[class*="_hol-${$.ide}_"]`).length;
    }

    // Actualizo operador de acumulados
    lis.tab_act('opc');

  }
  // Secciones
  static tab_sec( $dat, $ope, ...$opc ){

    let $ = doc.var($dat);    

    $.tab = $api_lis._tab.ide;

    $.kin = $_hol.val.kin;

    $.tip = $.var_ide.split('-');

    switch( $.tip[0] ){
    // plasma radial
    case 'rad':
      switch( $.tip[1] ){
      }
      break;
    // tonos galácticos
    case 'ton':
      switch( $.tip[1] ){
      // posiciones de una onda encantada
      case 'ond': 
        break;
      // color de fondo: 1-4
      case 'col':
        $.cla = 'fon-0';
        if( $dat.checked ){
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.sec.-fon[class*="fon_col-"].${$.cla}`),$.cla);
        }else{
          ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.sec.-fon[class*="fon_col-"]:not(.${$.cla})`),$.cla);
        }             
        break;
      }
      break;
    // sellos solares
    case 'sel':
      switch( $.tip[1] ){
      // Trayectoria
      case 'arm_tra':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_tra"] > .pos-0.${DIS_OCU}`),DIS_OCU);
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_tra"] > .pos-0:not(.${DIS_OCU})`),DIS_OCU);
          }
          break;
        case 'bor':
          $.cla = 'bor-1';
          if( $dat.checked ){ 
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_tra"]`),$.cla);
          }else{
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_tra"]`),$.cla);
          }            
          break;
        case 'col':
          $.cla = 'fon-0';
          if( $dat.checked ){ 
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_tra"]`),$.cla);
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_tra"]`),$.cla);
          }            
          break;
        }
        break;
      // Célula
      case 'arm_cel':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_cel"] > .pos-0.${DIS_OCU}`),DIS_OCU);
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_cel"] > .pos-0:not(.${DIS_OCU})`),DIS_OCU);
          }          
          break;
        case 'bor': 
          $.cla = 'bor-1'; 
          if( $dat.checked ){
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_cel"]`),$.cla);
          }else{
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_cel"]`),$.cla);
          }              
          break;
        case 'col': 
          $.cla = 'fon-0'; 
          if( $dat.checked ){
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_cel"]`),$.cla);
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_arm_cel"]`),$.cla);
          }              
          break;
        }
        break;          
      // Elemento
      case 'cro_ele':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_cro"] > .pos-0.${DIS_OCU}`),DIS_OCU);
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_cro"] > .pos-0:not(.${DIS_OCU})`),DIS_OCU);
          }          
          break;
        }
        break;
      }
      break;
    // luna
    case 'lun':
      switch( $.tip[1] ){
      // cabecera
      case 'cab':
        if( $dat.checked ){
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`[data-cab="ton"].${DIS_OCU}`),DIS_OCU);
        }else{
          ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`[data-cab="ton"]:not(.${DIS_OCU})`),DIS_OCU);
        }        
        break;
      // columas: plasma radial
      case 'rad': 
        if( $dat.checked ){
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`[data-cab="rad"].${DIS_OCU}`),DIS_OCU);
        }else{
          ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`[data-cab="rad"]:not(.${DIS_OCU})`),DIS_OCU);
        }      
        break;
      // filas: heptadas
      case 'hep':
        if( $dat.checked ){
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.sec-hep.${DIS_OCU}`),DIS_OCU);
        }else{
          ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.sec-hep:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;            
      } 
      break;
    // castillo
    case 'cas':
      if( !$.tip[1] ){
        if( $api_lis._tab.val.classList[1] == 'cas' ){
          $api_lis._tab.val.querySelectorAll('.app_ope').forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $api_lis._tab.val.querySelectorAll(`.pos-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $api_lis._tab.val.querySelectorAll(`.pos-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }
      }else{
        switch( $.tip[1] ){
        // posicion
        case 'pos': 
          if( $api_lis._tab.val.classList[1] == 'cas' ){
            $api_lis._tab.val.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
          }
          else{
            if( $dat.checked ){
              $api_lis._tab.val.querySelectorAll(`.pos-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
            }else{
              $api_lis._tab.val.querySelectorAll(`.pos-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
            }        
          }        
          break;
        // bordes
        case 'bor': 
          $.cla = "bor-1";
          if( $dat.checked ){
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_cas"]:not(.${$.cla})`),$.cla);
          }else{
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_cas"].${$.cla}`),$.cla);
          }
          break;          
        // color de fondo : 1-5
        case 'col':
          $.cla = "fon-0";
          if( $dat.checked ){
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_cas"][class*="fon_col-"].${$.cla}`),$.cla);
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.lis.tab[class*="_cas"][class*="fon_col-"]:not(.${$.cla})`),$.cla);
          }             
          break;
        // tog: orbitales
        case 'orb':
          if( $dat.checked ){
            ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.sec.-orb.${DIS_OCU}`),DIS_OCU);            
          }else{
            ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.sec.-orb:not(.${DIS_OCU})`),DIS_OCU);
          }
          break;
        }
      }      
      break;
    // tzolkin
    case 'kin':
      switch( $.tip[1] ){
      // cabecera
      case 'ton':
        $.sec_ini = $api_lis._tab.val.querySelector('.sec.ini');
        ele.act('cla_agr',$.sec_ini,DIS_OCU);
        if( $dat.checked ){
          $api_lis._tab.val.style.gridTemplateRows = 'repeat(21,1fr)';
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.sec.ton.${DIS_OCU}`),DIS_OCU);
          // muestro seccion
          if( $api_lis._tab.val.querySelector(`.sec.sel:not(.${DIS_OCU})`) ){ 
            ele.act('cla_eli',$.sec_ini,DIS_OCU);
          }
        }else{
          $api_lis._tab.val.style.gridTemplateRows = 'repeat(20,1fr)';
          ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.sec.ton:not(.${DIS_OCU})`),DIS_OCU);
        }        
        break;
      // lateral izquierdo
      case 'sel':
        $.sec_ini = $api_lis._tab.val.querySelector('.sec.ini');
        ele.act('cla_agr',$.sec_ini,DIS_OCU);
        if( $dat.checked ){
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.sec.sel.${DIS_OCU}`),DIS_OCU);
          // muestro seccion
          if( $api_lis._tab.val.querySelector(`.sec.ton:not(.${DIS_OCU})`) ){ 
            ele.act('cla_eli',$.sec_ini,DIS_OCU);
          }
        }else{
          ele.act('cla_agr',$api_lis._tab.val.querySelectorAll(`.sec.sel:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;
      }
      break;
    }
  }
  // portales de activacion
  static tab_pag( $dat, $ope, ...$opc ){

    let $ = doc.var($dat);

    $.kin = $_hol.val.kin;

    $.ide = `pag_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`, `_val-opc-${$.ide}`, `_val-opc_act-${$.ide}`];

    $.tab = $api_lis._tab.ide;

    // galácticos
    if( $.var_ide == 'kin' ){

      $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}[data-hol_kin]`).forEach( $pos => {

        $.kin = hol._('kin',$pos.dataset['hol_kin']);      

        if( $.kin.pag != 0 ) $dat.checked ? ele.act('cla_agr',$pos,$.cla) : ele.act('cla_eli',$pos,$.cla);

      });
    }
    // solares
    else if( $.var_ide == 'psi' ){

      $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}[data-hol_psi]`).forEach( $pos => {

        $.psi = hol._('psi',$pos.dataset['hol_psi']);

        $.kin = hol._('kin',$.psi.tzo);

        if( $.kin.pag != 0 ) $dat.checked ? ele.act('cla_agr',$pos,$.cla) : ele.act('cla_eli',$pos,$.cla);
      });
    }
    // Actualizo acumulados
    hol.tab_val($dat);
  }// parejas del oráculo
  static tab_par( $dat, $ope, ...$opc ){

    let $ = doc.var($dat);

    $.kin = $_hol.val.kin;

    $.ide = `par_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`,"_val-opc-par","_val-opc_act-par"];

    $._par_lis = ['ana','gui','ant','ocu'];
    // marcar todos los patrones del oráculo
    if( !$.var_ide ){

      $._par_lis.forEach( $ide => {

        hol.tab_par( $api_doc._var.querySelector(`[name="${$ide}"]`) );
      });
    }// por pareja
    else{
      // marco pareja
      if( $._par_lis.includes($.var_ide) ){
        // desmarco todos los anteriores
        ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
        // marco correspondientes
        if( $dat.checked ){
          $api_lis._tab.val.querySelectorAll(
            `${$api_lis._tab.cla}[data-hol_kin="${hol._('kin',$.kin)[`par_${$.var_ide}`]}"]:not(.${$.cla})`
          ).forEach( $ele =>{ 
            
            ele.act('cla_agr',$ele,$.cla);
          })
        }
        // evaluo extensiones
        hol.tab_par( $api_doc._var.querySelector(`[name="ext"]`) );
      }
      // extiendo oráculo
      else if( $.var_ide == 'ext' ){
        
        $.val_tot = 0;
        $.cla[1] = "_val-opc-par_ext";
        $.cla[2] = "_val-opc_act-par_ext";
        $._par_lis.forEach( $i => {

          // elimino marcas previas + marco extensiones por pareja
          $.cla[0] = `_hol-par_${$i}-ext`;                    
          // agrgo 3 clases : -ext , _val-opc, _val-opc_act
          ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);

          // marco extensiones
          if( 
            $dat.checked && $api_doc._var.querySelector(`[name="${$i}"]`).checked && 
            ( $.ele = $api_lis._tab.val.querySelector(`${$api_lis._tab.cla}[data-hol_kin="${hol._('kin',$.kin)[`par_${$i}`]}"]:not(.${$.cla[0]})`) ) 
          ){
            $._kin = hol._('kin',$.ele.dataset['hol_kin']);

            $._par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {

              $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}[data-hol_kin="${$._kin[$ide_ext]}"]`).forEach( $ext => {
                $.val_tot++;                
                ele.act('cla_agr',$ext,$.cla);
              })
            });
          }
        });
        // actualizo cantidades
        $._par_lis.forEach( $ide => {

          if( $.tot = $api_doc._var.querySelector(`div.var > [name="${$ide}"] ~ span > n`) ){

            $.tot.innerHTML = $api_lis._tab.val.querySelectorAll(`[class*="_hol-par_${$ide}"]`).length;
          }
        });
        // total general
        if( $.tot = $api_doc._var.querySelector('div.var > [name="cue"]') ){

          $.tot.innerHTML = $api_lis._tab.val.querySelectorAll(`[class*="_hol-par_"]`).length;
        }
        // actualizo acumulado por opciones
        lis.tab_act('opc');        
      }
    }
  }// pulsares de onda
  static tab_pul( $dat, $ope, ...$opc ){

    let $ = doc.var($dat);

    $.kin = $_hol.val.kin;

    $.ide = `pul_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`,"_val-opc-pul","_val-opc_act-pul"];

    $.tab = $api_lis._tab.ide;

    // elimino todos los pulsares anteriores
    ele.act('htm_eli',$api_lis._tab.val.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`));
    
    // inicializo acumulados
    ele.act('cla_eli',$api_lis._tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
    
    // posicion principal por kin      
    if( $dat.checked && ( $.pos = $api_lis._tab.val.querySelector(`${$api_lis._tab.cla}[data-hol_kin="${$.kin}"]`) ) ){
      switch( $.tab ){
      // estaciones cromáticas : 1 x 5
      case 'kin_cro': 
        $.val = num.val_ran($.pos.parentElement.getAttribute('pos'),13);
        break;
      // trayectorias armónicas : 1 x 20
      case 'kin_arm': 
        $.val = $.pos.parentElement.parentElement.getAttribute('pos');
        break;
      // lunas : 1 x 28
      case 'psi_lun': 
        $.val = $.pos.parentElement.parentElement.parentElement.getAttribute('pos');
        break;
      // posicion directa x 1
      default:
        if( $.pos.dataset['hol_ton'] ) $.val = $.pos.dataset['hol_ton'];         
        break;
      }
      // busco tono del elemento y cargo la ficha del pulsar
      if( $.val ){

        $.ton = hol._('ton',$.val);

        $.ton_pul = $.ton[ $.pul_ide = $.ide.split('_')[1] ];
        
        // marco acumulos
        $api_lis._tab.val.querySelectorAll(`${$api_lis._tab.cla}[data-hol_kin]`).forEach( $e => {

          $.ton = hol._('ton',$e.dataset['hol_ton']);

          if( $.ton_pul == $.ton[$.pul_ide] ) ele.act('cla_agr',$e,$.cla);
          
        });
        // muestro pulsares de la o.e.
        $api_lis._tab.val.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`).forEach( $e => {
          
          $e.innerHTML += hol.ima(`ton_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
        });
      }
    }
    // actualizo acumulados
    hol.tab_val($dat);
  }    
}