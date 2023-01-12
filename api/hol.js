// WINDOW
'use strict';

// Holon : ns.ani.lun.dia:kin
class api_hol {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('hol',$ide,'dat');

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        case 'sel_cod':
          if( $val == 20 ) $val = 1;
          if( $_dat && $_dat[$val] ) $_ = $_dat[$val];
          break;
        default:
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];
          break;
        }
      }
    }
    
    return $_;
  }

  /* -- Imagen -- */ 
  static ima( $est, $dat, $ele ){

    return api_fig.ima('hol',`${$est}`,$dat,$ele);
  }

  /* -- Tablero -- */
  static tab(){
  }// Valores
  static tab_val( $ope ){

    let $ = sis_app.var($ope);

    // clases: portales + parejas + pulsares + dimensionales + matices + especulares
    $.cla = `_hol-${$dom.app.var.classList[0].split('-')[2]}_`;
    
    // Actualizo total por item
    if( $ope.nextElementSibling && ( $.tot = $ope.nextElementSibling.querySelector('n') ) ){

      $.tot.innerHTML = $dom.est.tab.val.querySelectorAll(`.${$.cla}${$.var_ide}`).length;
    }

    // Actualizo total general
    if( $.tot = $dom.app.var.querySelector('.dat_var > [name="cue"]') ){

      $.tot.innerHTML = $dom.est.tab.val.querySelectorAll(`[class*="${$.cla}"]`).length;
    }

    // Actualizo Acumulados
    api_est.tab_act('opc');

  }// Secciones
  static tab_sec( $dat ){

    let $ = sis_app.var($dat);    

    $.tab = $dom.est.tab.ide;

    $.kin = $_hol.val.kin;

    $.tip = $.var_ide.split('-');

    switch( $.tip[0] ){
    // parejas del oráculo
    case 'par':
      if( $dat.checked ){
        if( $dom.est.tab.val.querySelector(`.est.tab.par.bor-0`) )
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.est.tab.par.bor-0`),'bor-0');
      }
      else{
        if( $dom.est.tab.val.querySelector(`.est.tab.par:not(.bor-0)`) )
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.est.tab.par:not(.bor-0)`),'bor-0');
      }      
      break;
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
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.sec.fon[class*="fon_col-"].${$.cla}`),$.cla);
        }else{
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.sec.fon[class*="fon_col-"]:not(.${$.cla})`),$.cla);
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
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_tra"] > .pos.ide-0.${DIS_OCU}`),DIS_OCU);
          }else{
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_tra"] > .pos.ide-0:not(.${DIS_OCU})`),DIS_OCU);
          }
          break;
        case 'bor':
          $.cla = 'bor-1';
          if( $dat.checked ){ 
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_tra"]`),$.cla);
          }else{
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_tra"]`),$.cla);
          }            
          break;
        case 'col':
          $.cla = 'fon-0';
          if( $dat.checked ){ 
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_tra"]`),$.cla);
          }else{
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_tra"]`),$.cla);
          }            
          break;
        }
        break;
      // Célula
      case 'arm_cel':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_cel"] > .pos.ide-0.${DIS_OCU}`),DIS_OCU);
          }else{
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_cel"] > .pos.ide-0:not(.${DIS_OCU})`),DIS_OCU);
          }          
          break;
        case 'bor': 
          $.cla = 'bor-1'; 
          if( $dat.checked ){
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_cel"]`),$.cla);
          }else{
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_cel"]`),$.cla);
          }              
          break;
        case 'col': 
          $.cla = 'fon-0'; 
          if( $dat.checked ){
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_cel"]`),$.cla);
          }else{
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" arm_cel"]`),$.cla);
          }              
          break;
        }
        break;          
      // Elemento
      case 'cro_ele':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab[class*=" cro"] > .pos.ide-0.${DIS_OCU}`),DIS_OCU);
          }else{
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab[class*=" cro"] > .pos.ide-0:not(.${DIS_OCU})`),DIS_OCU);
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
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`[data-cab="ton"].${DIS_OCU}`),DIS_OCU);
        }else{
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`[data-cab="ton"]:not(.${DIS_OCU})`),DIS_OCU);
        }        
        break;
      // columas: plasma radial
      case 'rad': 
        if( $dat.checked ){
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`[data-cab="rad"].${DIS_OCU}`),DIS_OCU);
        }else{
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`[data-cab="rad"]:not(.${DIS_OCU})`),DIS_OCU);
        }      
        break;
      // filas: heptadas
      case 'hep':
        if( $dat.checked ){
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.sec.hep.${DIS_OCU}`),DIS_OCU);
        }else{
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.sec.hep:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;            
      } 
      break;
    // castillo
    case 'cas':
      if( !$.tip[1] ){
        if( $dom.est.tab.val.classList[1] == 'cas' ){
          $dom.est.tab.val.querySelectorAll('.pos.ope').forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $dom.est.tab.val.querySelectorAll(`.pos.ide-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $dom.est.tab.val.querySelectorAll(`.pos.ide-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }
      }else{
        switch( $.tip[1] ){
        // posicion
        case 'pos': 
          if( $dom.est.tab.val.classList[1] == 'cas' ){
            $dom.est.tab.val.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
          }
          else{
            if( $dat.checked ){
              $dom.est.tab.val.querySelectorAll(`.pos.ide-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
            }else{
              $dom.est.tab.val.querySelectorAll(`.pos.ide-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
            }        
          }        
          break;
        // bordes
        case 'bor': 
          $.cla = "bor-1";
          if( $dom.est.tab.val.querySelector(`.tab.hol_cas`) ){
            if( $dat.checked ){
              $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab.hol_cas:not(.${$.cla})`),$.cla);
            }else{
              $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab.hol_cas.${$.cla}`),$.cla);
            }
          }else{
            $dat.checked ? $dom.act('cla_agr',$dom.est.tab.val,$.cla) : $dom.act('cla_eli',$dom.est.tab.val,$.cla);
          }
          break;          
        // color de fondo : 1-5
        case 'col':
          $.cla = "fon-0";
          if( $dom.est.tab.val.querySelector(`.tab.hol_cas`) ){
            if( $dat.checked ){
              $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.tab.hol_cas[class*="fon_col-"].${$.cla}`),$.cla);
            }else{
              $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.tab.hol_cas[class*="fon_col-"]:not(.${$.cla})`),$.cla);
            } 
          }else{
            $dat.checked ? $dom.act('cla_eli',$dom.est.tab.val,$.cla) : $dom.act('cla_agr',$dom.est.tab.val,$.cla);
          }
          break;
        // tog: orbitales
        case 'orb':
          if( $dat.checked ){
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.sec[class*=" orb-"].${DIS_OCU}`),DIS_OCU);            
          }else{
            $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.sec[class*=" orb-"]:not(.${DIS_OCU})`),DIS_OCU);
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
        $.sec_ini = $dom.est.tab.val.querySelector('.sec.ini');
        $dom.act('cla_agr',$.sec_ini,DIS_OCU);
        if( $dat.checked ){
          $dom.est.tab.val.style.gridTemplateRows = 'repeat(21,1fr)';
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.sec.ton.${DIS_OCU}`),DIS_OCU);
          // muestro seccion
          if( $dom.est.tab.val.querySelector(`.sec.sel:not(.${DIS_OCU})`) ){ 
            $dom.act('cla_eli',$.sec_ini,DIS_OCU);
          }
        }else{
          $dom.est.tab.val.style.gridTemplateRows = 'repeat(20,1fr)';
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.sec.ton:not(.${DIS_OCU})`),DIS_OCU);
        }        
        break;
      // lateral izquierdo
      case 'sel':
        $.sec_ini = $dom.est.tab.val.querySelector('.sec.ini');
        $dom.act('cla_agr',$.sec_ini,DIS_OCU);
        if( $dat.checked ){
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.sec.sel.${DIS_OCU}`),DIS_OCU);
          // muestro seccion
          if( $dom.est.tab.val.querySelector(`.sec.ton:not(.${DIS_OCU})`) ){ 
            $dom.act('cla_eli',$.sec_ini,DIS_OCU);
          }
        }else{
          $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.sec.sel:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;
      }
      break;
    }
  }// Operadores
  static tab_opc( $tip, $dat = {} ){    

    let $ = sis_app.var($dat);    

    $.tab = $dom.est.tab.ide;    

    $.ide = `${$tip}_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`, `_val-opc-${$.ide}`, `_val-opc_act-${$.ide}`];    

    $.kin = $_hol.val.kin;

    switch( $tip ){
    // kines: portales de activacion      
    case 'pag':
      // galácticos
      if( $.var_ide == 'kin' ){

        $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}[data-hol_kin]`).forEach( $pos => {

          $.kin = api_hol._('kin',$pos.dataset['hol_kin']);      

          if( $.kin.pag != 0 ) $dat.checked ? $dom.act('cla_agr',$pos,$.cla) : $dom.act('cla_eli',$pos,$.cla);

        });
      }
      // solares
      else if( $.var_ide == 'psi' ){

        $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}[data-hol_psi]`).forEach( $pos => {

          $.psi = api_hol._('psi',$pos.dataset['hol_psi']);

          $.kin = api_hol._('kin',$.psi.kin);

          if( $.kin.pag != 0 ) $dat.checked ? $dom.act('cla_agr',$pos,$.cla) : $dom.act('cla_eli',$pos,$.cla);
        });
      }
      // Actualizo acumulados
      api_hol.tab_val($dat);    
      break;
    // sellos: oráculo por posicion
    case 'par':
      $._par_lis = ['ana','gui','ant','ocu'];
      // marcar todos los patrones del oráculo
      if( !$.var_ide ){
  
        $._par_lis.forEach( $ide => {
  
          api_hol.tab_opc('par', $dom.app.var.querySelector(`[name="${$ide}"]`) );
        });
      }// por pareja
      else{
        // marco pareja
        if( $._par_lis.includes($.var_ide) ){
          // desmarco todos los anteriores
          $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
          // marco correspondientes
          if( $dat.checked ){
            $dom.est.tab.val.querySelectorAll(
              `${$dom.est.tab.cla}[data-hol_kin="${api_hol._('kin',$.kin)[`par_${$.var_ide}`]}"]:not(.${$.cla})`
            ).forEach( $ele =>{ 
              
              $dom.act('cla_agr',$ele,$.cla);
            })
          }
          // evaluo extensiones
          api_hol.tab_opc('par', $dom.app.var.querySelector(`[name="ext"]`) );
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
            $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
  
            // marco extensiones
            if( 
              $dat.checked && $dom.app.var.querySelector(`[name="${$i}"]`).checked && 
              ( $.ele = $dom.est.tab.val.querySelector(`${$dom.est.tab.cla}[data-hol_kin="${api_hol._('kin',$.kin)[`par_${$i}`]}"]:not(.${$.cla[0]})`) ) 
            ){
              $._kin = api_hol._('kin',$.ele.dataset['hol_kin']);
  
              $._par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {
  
                $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}[data-hol_kin="${$._kin[$ide_ext]}"]`).forEach( $ext => {
                  $.val_tot++;                
                  $dom.act('cla_agr',$ext,$.cla);
                })
              });
            }
          });
          // actualizo cantidades
          $._par_lis.forEach( $ide => {
  
            if( $.tot = $dom.app.var.querySelector(`.dat_var > [name="${$ide}"] ~ span > n`) ){
  
              $.tot.innerHTML = $dom.est.tab.val.querySelectorAll(`[class*="_hol-par_${$ide}"]`).length;
            }
          });
          // total general
          if( $.tot = $dom.app.var.querySelector('.dat_var > [name="cue"]') ){
  
            $.tot.innerHTML = $dom.est.tab.val.querySelectorAll(`[class*="_hol-par_"]`).length;
          }
          // actualizo acumulado por opciones
          api_est.tab_act('opc');        
        }
      }      
      break;
    // tonos: pulsares por posicion
    case 'pul':
      // elimino todos los pulsares anteriores
      $dom.act('htm_eli',$dom.est.tab.val.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`));
      
      // inicializo acumulados
      $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
      
      // posicion principal por kin      
      if( $dat.checked && ( $.pos = $dom.est.tab.val.querySelector(`${$dom.est.tab.cla}[data-hol_kin="${$.kin}"]`) ) ){
        switch( $.tab ){
        // estaciones cromáticas : 1 x 5
        case 'kin_cro': 
          $.val = api_num.val_ran($.pos.parentElement.getAttribute('pos'),13);
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

          $.ton = api_hol._('ton',$.val);

          $.ton_pul = $.ton[ $.pul_ide = $.ide.split('_')[1] ];
          
          // marco acumulos
          $dom.est.tab.val.querySelectorAll(`${$dom.est.tab.cla}[data-hol_kin]`).forEach( $e => {

            $.ton = api_hol._('ton',$e.dataset['hol_ton']);

            if( $.ton_pul == $.ton[$.pul_ide] ) $dom.act('cla_agr',$e,$.cla);
            
          });
          // muestro pulsares de la o.e.
          $dom.est.tab.val.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`).forEach( $e => {
            
            $e.innerHTML += hol.ima(`ton_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
          });
        }
      }
      // actualizo acumulados
      hol.tab_val($dat);
      break;
    // tonos: pulsares por seleccion
    case 'dim':
    case 'mat':
    case 'sim':
      
      // inicializo acumulados      
      $.cla_ver = $dom.est.tab.val.querySelector(`.pos.dep`) ? ".pos.dep" : $dom.est.tab.cla;

      $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`${$.cla_ver}.${$.cla[0]}`),$.cla);      
      if( $dat.checked ){
        // muestro pulsar seleccionado
        $dom.act('cla_eli',$dom.est.tab.val.querySelectorAll(`.sec.pul.${$tip}-${$dat.value}.${DIS_OCU}`),DIS_OCU);
        // acumulo posiciones sin considerar oráculos
        
        $dom.est.tab.val.querySelectorAll(`${$.cla_ver}[data-hol_ton]`).forEach( $ele_pos => {
          if( ( $.ton = api_hol._(`ton`,$ele_pos.dataset.hol_ton) ) && $.ton[$tip] == $dat.value ) $dom.act('cla_agr',$ele_pos,$.cla);
        });
      }// oculto pulsar seleccionado
      else{
        $dom.act('cla_agr',$dom.est.tab.val.querySelectorAll(`.sec.pul.${$tip}-${$dat.value}:not(.${DIS_OCU})`),DIS_OCU);
      }
      // actualizo acumulados
      api_hol.tab_val($dat);
    }

  }
}