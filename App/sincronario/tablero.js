// WINDOW
'use strict';

// Valores
function tab_ope( $ope ){

  let $ = Doc_Ope.var($ope);

  // clases: portales + parejas + pulsares + dimensionales + matices + especulares
  $.cla = `${$Doc.Ope.var.classList[0].split('-')[2]}_`;
  
  // Actualizo total por item
  if( $ope.nextElementSibling && ( $.tot = $ope.nextElementSibling.querySelector('n') ) ){

    $.tot.innerHTML = $Doc.Dat.tab.ope.querySelectorAll(`.${$.cla}${$.var_ide}`).length;
  }

  // Actualizo total general
  if( $.tot = $Doc.Ope.var.querySelector('.ope_var > [name="cue"]') ){

    $.tot.innerHTML = $Doc.Dat.tab.ope.querySelectorAll(`[class*="${$.cla}"]`).length;
  }

  // Actualizo Acumulados
  Doc_Dat.tab_act('opc');

}// - marco posicion principal
function tab_val_pos( $dat ){

  let $ = Doc_Ope.var($dat);

  if( $Hol && $Hol.val && ( $.kin = $Hol.val.kin ) ){        

    $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin="${$.kin}"]`).forEach( $e => {

      $e.classList.add(`_pos-`);

      if( $Doc.Dat.tab.ope_acu && $Doc.Dat.tab.ope_acu.querySelector(`[name="pos"]:checked`) ){

        $e.classList.add(`_pos-bor`);
      }
    });
  }  

}

// Secciones
function tab_sec( $dat ){

  let $ = Doc_Ope.var($dat);    

  $.tab = $Doc.Dat.tab.ide;

  $.kin = $Hol.val.kin;

  $.tip = $.var_ide.split('-');

  switch( $.tip[0] ){
  // parejas del oráculo
  case 'par':
    if( $dat.checked ){
      if( $Doc.Dat.tab.ope.querySelector(`.dat_tab.par.bor-0`) )
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.par.bor-0`),'bor-0');
    }
    else{
      if( $Doc.Dat.tab.ope.querySelector(`.dat_tab.par:not(.bor-0)`) )
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.par:not(.bor-0)`),'bor-0');
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
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec.fon[class*="fon_col-"].${$.cla}`),$.cla);
      }else{
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.sec.fon[class*="fon_col-"]:not(.${$.cla})`),$.cla);
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
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_tra"] > .pos.ide-0.${DIS_OCU}`),DIS_OCU);
        }else{
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_tra"] > .pos.ide-0:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;
      case 'bor':
        $.cla = 'bor-1';
        if( $dat.checked ){ 
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
        }else{
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
        }            
        break;
      case 'col':
        $.cla = 'fon-0';
        if( $dat.checked ){ 
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
        }else{
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
        }            
        break;
      }
      break;
    // Célula
    case 'arm_cel':
      switch( $.tip[2] ){
      case 'pos': 
        if( $dat.checked ){
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_cel"] > .pos.ide-0.${DIS_OCU}`),DIS_OCU);
        }else{
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_cel"] > .pos.ide-0:not(.${DIS_OCU})`),DIS_OCU);
        }          
        break;
      case 'bor': 
        $.cla = 'bor-1'; 
        if( $dat.checked ){
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
        }else{
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
        }              
        break;
      case 'col': 
        $.cla = 'fon-0'; 
        if( $dat.checked ){
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
        }else{
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
        }              
        break;
      }
      break;          
    // Elemento
    case 'cro_ele':
      switch( $.tip[2] ){
      case 'pos': 
        if( $dat.checked ){
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" cro"] > .pos.ide-0.${DIS_OCU}`),DIS_OCU);
        }else{
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab[class*=" cro"] > .pos.ide-0:not(.${DIS_OCU})`),DIS_OCU);
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
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`[data-cab="ton"].${DIS_OCU}`),DIS_OCU);
      }else{
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`[data-cab="ton"]:not(.${DIS_OCU})`),DIS_OCU);
      }        
      break;
    // columas: plasma radial
    case 'rad': 
      if( $dat.checked ){
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`[data-cab="rad"].${DIS_OCU}`),DIS_OCU);
      }else{
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`[data-cab="rad"]:not(.${DIS_OCU})`),DIS_OCU);
      }      
      break;
    // filas: heptadas
    case 'hep':
      if( $dat.checked ){
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec.hep.${DIS_OCU}`),DIS_OCU);
      }else{
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.sec.hep:not(.${DIS_OCU})`),DIS_OCU);
      }
      break;            
    } 
    break;
  // castillo
  case 'cas':
    if( !$.tip[1] ){
      if( $Doc.Dat.tab.ope.classList[1] == 'cas' ){
        $Doc.Dat.tab.ope.querySelectorAll('.pos.ope').forEach( $v => $v.classList.toggle('bor-1') );
      }
      else{
        if( $dat.checked ){
          $Doc.Dat.tab.ope.querySelectorAll(`.pos.ide-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
        }else{
          $Doc.Dat.tab.ope.querySelectorAll(`.pos.ide-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
        }        
      }
    }else{
      switch( $.tip[1] ){
      // posicion
      case 'pos': 
        if( $Doc.Dat.tab.ope.classList[1] == 'cas' ){
          $Doc.Dat.tab.ope.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $Doc.Dat.tab.ope.querySelectorAll(`.pos.ide-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $Doc.Dat.tab.ope.querySelectorAll(`.pos.ide-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }        
        break;
      // bordes
      case 'bor': 
        $.cla = "bor-1";
        if( $Doc.Dat.tab.ope.querySelector(`.dat_tab.hol-cas`) ){
          if( $dat.checked ){
            Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.hol-cas:not(.${$.cla})`),$.cla);
          }else{
            Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.hol-cas.${$.cla}`),$.cla);
          }
        }else{
          $dat.checked ? Ele.act('cla_agr',$Doc.Dat.tab.ope,$.cla) : Ele.act('cla_eli',$Doc.Dat.tab.ope,$.cla);
        }
        break;          
      // color de fondo : 1-5
      case 'col':
        $.cla = "fon-0";
        if( $Doc.Dat.tab.ope.querySelector(`.dat_tab.hol-cas`) ){
          if( $dat.checked ){
            Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.hol-cas[class*="fon_col-"].${$.cla}`),$.cla);
          }else{
            Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.dat_tab.hol-cas[class*="fon_col-"]:not(.${$.cla})`),$.cla);
          } 
        }else{
          $dat.checked ? Ele.act('cla_eli',$Doc.Dat.tab.ope,$.cla) : Ele.act('cla_agr',$Doc.Dat.tab.ope,$.cla);
        }
        break;
      // tog: orbitales
      case 'orb':
        if( $dat.checked ){
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec[class*=" orb-"].${DIS_OCU}`),DIS_OCU);            
        }else{
          Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.sec[class*=" orb-"]:not(.${DIS_OCU})`),DIS_OCU);
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
      $.sec_ini = $Doc.Dat.tab.ope.querySelector('.sec.ini');
      Ele.act('cla_agr',$.sec_ini,DIS_OCU);
      if( $dat.checked ){
        $Doc.Dat.tab.ope.style.gridTemplateRows = 'repeat(21,1fr)';
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec.ton.${DIS_OCU}`),DIS_OCU);
        // muestro seccion
        if( $Doc.Dat.tab.ope.querySelector(`.sec.sel:not(.${DIS_OCU})`) ){ 
          Ele.act('cla_eli',$.sec_ini,DIS_OCU);
        }
      }else{
        $Doc.Dat.tab.ope.style.gridTemplateRows = 'repeat(20,1fr)';
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.sec.ton:not(.${DIS_OCU})`),DIS_OCU);
      }        
      break;
    // lateral izquierdo
    case 'sel':
      $.sec_ini = $Doc.Dat.tab.ope.querySelector('.sec.ini');
      Ele.act('cla_agr',$.sec_ini,DIS_OCU);
      if( $dat.checked ){
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec.sel.${DIS_OCU}`),DIS_OCU);
        // muestro seccion
        if( $Doc.Dat.tab.ope.querySelector(`.sec.ton:not(.${DIS_OCU})`) ){ 
          Ele.act('cla_eli',$.sec_ini,DIS_OCU);
        }
      }else{
        Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.sec.sel:not(.${DIS_OCU})`),DIS_OCU);
      }
      break;
    }
    break;
  }
}

// Opciones
function tab_opc( $tip, $dat = {} ){

  let $ = Doc_Ope.var($dat);    

  $.tab = $Doc.Dat.tab.ide;    

  $.ide = `${$tip}_${$.var_ide}`;

  $.cla = [`${$.ide}`, `_opc-${$.ide}`, `_opc_act-${$.ide}`];    

  $.kin = $Hol.val.kin;

  switch( $tip ){
  // kines: portales de activacion      
  case 'pag':

    // galácticos
    if( $.var_ide == 'kin' ){

      $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin]`).forEach( $pos => {

        $.kin = Dat._('hol.kin',$pos.getAttribute('hol-kin'));      

        if( $.kin.pag != 0 ) $dat.checked ? Ele.act('cla_agr',$pos,$.cla) : Ele.act('cla_eli',$pos,$.cla);

      });
    }
    // solares
    else if( $.var_ide == 'psi' ){

      $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-psi]`).forEach( $pos => {

        $.psi = Dat._('hol.psi',$pos.getAttribute('hol-psi'));

        $.kin = Dat._('hol.kin',$.psi.kin);

        if( $.kin.pag != 0 ) $dat.checked ? Ele.act('cla_agr',$pos,$.cla) : Ele.act('cla_eli',$pos,$.cla);
      });
    }
    // Actualizo acumulados
    tab_ope($dat);    
    break;
  // sellos: oráculo por posicion
  case 'par':
    $.par_lis = ['ana','gui','ant','ocu'];
    // marcar todos los patrones del oráculo
    if( !$.var_ide ){

      $.par_lis.forEach( $ide => {

        tab_opc('par', $Doc.Ope.var.querySelector(`[name="${$ide}"]`) );
      });
    }// por pareja
    else{
      // marco pareja
      if( $.par_lis.includes($.var_ide) ){
        // desmarco todos los anteriores
        Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.${$.cla[0]}`),$.cla);
        // marco correspondientes
        if( $dat.checked ){
          $Doc.Dat.tab.ope.querySelectorAll(
            `${$Doc.Dat.tab.cla}[hol-kin="${Dat._('hol.kin',$.kin)[`par_${$.var_ide}`]}"]:not(.${$.cla})`
          ).forEach( $ele =>{ 
            
            Ele.act('cla_agr',$ele,$.cla);
          })
        }
        // evaluo extensiones
        tab_opc('par', $Doc.Ope.var.querySelector(`[name="ext"]`) );
      }
      // extiendo oráculo
      else if( $.var_ide == 'ext' ){
        
        $.val_tot = 0;
        $.cla[1] = "_opc-par_ext";
        $.cla[2] = "_opc_act-par_ext";
        $.par_lis.forEach( $i => {

          // elimino marcas previas + marco extensiones por pareja
          $.cla[0] = `par_${$i}-ext`;                    
          // agrgo 3 clases : -ext , _opc, _opc_act
          Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.${$.cla[0]}`),$.cla);

          // marco extensiones
          if( 
            $dat.checked && $Doc.Ope.var.querySelector(`[name="${$i}"]`).checked && 
            ( $.ele = $Doc.Dat.tab.ope.querySelector(`${$Doc.Dat.tab.cla}[hol-kin="${Dat._('hol.kin',$.kin)[`par_${$i}`]}"]:not(.${$.cla[0]})`) ) 
          ){
            $._kin = Dat._('hol.kin',$.ele.getAttribute('hol-kin'));

            $.par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {

              $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin="${$._kin[$ide_ext]}"]`).forEach( $ext => {
                $.val_tot++;                
                Ele.act('cla_agr',$ext,$.cla);
              })
            });
          }
        });
        // actualizo cantidades
        $.par_lis.forEach( $ide => {

          if( $.tot = $Doc.Ope.var.querySelector(`.ope_var > [name="${$ide}"] ~ span > n`) ){

            $.tot.innerHTML = $Doc.Dat.tab.ope.querySelectorAll(`[class*="par_${$ide}"]`).length;
          }
        });
        // total general
        if( $.tot = $Doc.Ope.var.querySelector('.ope_var > [name="cue"]') ){

          $.tot.innerHTML = $Doc.Dat.tab.ope.querySelectorAll(`[class*="par_"]`).length;
        }
        // actualizo acumulado por opciones
        Doc_Dat.tab_act('opc');        
      }
    }      
    break;
  // tonos: pulsares por posicion
  case 'pul':
    // elimino todos los pulsares anteriores
    Ele.act('htm_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`));
    
    // inicializo acumulados
    Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.${$.cla[0]}`),$.cla);
    
    // posicion principal por kin      
    if( $dat.checked && ( $.pos = $Doc.Dat.tab.ope.querySelector(`${$Doc.Dat.tab.cla}[hol-kin="${$.kin}"]`) ) ){
      switch( $.tab ){
      // estaciones cromáticas : 1 x 5
      case 'kin_cro': 
        $.val = Num.ran($.pos.parentElement.getAttribute('pos'),13);
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
        if( $.pos.getAttribute('hol-ton') ) $.val = $.pos.getAttribute('hol-ton');         
        break;
      }
      // busco tono del elemento y cargo la ficha del pulsar
      if( $.val ){

        $.ton = Dat._('hol.ton',$.val);

        $.ton_pul = $.ton[ $.pul_ide = $.ide.split('_')[1] ];
        
        // marco acumulos
        $Doc.Dat.tab.ope.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin]`).forEach( $e => {

          $.ton = Dat._('hol.ton',$e.getAttribute('hol-ton'));

          if( $.ton_pul == $.ton[$.pul_ide] ) Ele.act('cla_agr',$e,$.cla);
          
        });
        // muestro pulsares de la o.e.
        $Doc.Dat.tab.ope.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`).forEach( $e => {
          
          $e.innerHTML += Doc_Val.ima('hol',`ton_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
        });
      }
    }
    // actualizo acumulados
    hol.tab_ope($dat);
    break;
  // tonos: pulsares por seleccion
  case 'dim':
  case 'mat':
  case 'sim':
    
    // inicializo acumulados      
    $.cla_ver = $Doc.Dat.tab.ope.querySelector(`.pos.dep`) ? ".pos.dep" : $Doc.Dat.tab.cla;

    Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`${$.cla_ver}.${$.cla[0]}`),$.cla);      
    if( $dat.checked ){
      // muestro pulsar seleccionado
      Ele.act('cla_eli',$Doc.Dat.tab.ope.querySelectorAll(`.sec.pul.${$tip}-${$dat.value}.${DIS_OCU}`),DIS_OCU);
      // acumulo posiciones sin considerar oráculos
      
      $Doc.Dat.tab.ope.querySelectorAll(`${$.cla_ver}[hol-ton]`).forEach( $ele_pos => {
        if( ( $.ton = Dat._(`hol.ton`,$ele_pos.dataset.hol-ton) ) && $.ton[$tip] == $dat.value ) Ele.act('cla_agr',$ele_pos,$.cla);
      });
    }// oculto pulsar seleccionado
    else{
      Ele.act('cla_agr',$Doc.Dat.tab.ope.querySelectorAll(`.sec.pul.${$tip}-${$dat.value}:not(.${DIS_OCU})`),DIS_OCU);
    }
    // actualizo acumulados
    tab_ope($dat);
  }

}