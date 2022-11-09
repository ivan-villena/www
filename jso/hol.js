// WINDOW
'use strict';

// Sincronario
class hol {

  constructor( $dat ){
    
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    }

    // inicializo tablero con clase principal
    if( $_app.uri.cab == 'ope' ){

      $_app.tab.cla = ( $_app.tab.dep = $_app.tab.lis.querySelector('.pos.app_ope.sec_par') ) 
        ? '.pos.app_ope.sec_par > .app_tab[class*="_par"] > .pos[class*="ide-"]' 
        : '.pos.app_ope'
      ;
      // muestro diario      
      /* 
      let $ = {};
      ( $.bot_ini = $_app.ope.bot.querySelector('.ico.fec_val') ) && $.bot_ini.click(); 
      */
    }
  }

  // proceso diario
  static dia( $dat ){
    // operador : fecha + sincronario
    let $ = app.var($dat);
    
    $.uri = `${$_app.uri.esq}/ope/${ $_app.uri.cab == 'ope' ? $_app.uri.art : 'kin_tzo' }`;
    // calendario gregoriano
    if( $_app.ope.var.classList.contains('fec') ){
      
      if( $.fec = $_app.ope.var.querySelector('[name="fec"]').value ){

        api_arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
      }
      else{
        alert('La fecha del calendario es inválida...')
      }
    }
    // sincronario
    else if( $_app.ope.var.classList.contains('sin') ){
      $.atr = {};
      $.hol = [];
      $.val = true;
      ['gal','ani','lun','dia'].forEach( $v => {

        $.atr[$v] = $_app.ope.var.querySelector(`[name="${$v}"]`).value;

        if( !$.atr[$v] ){ 
          return $.val = false;          
        }
        else{ 
          $.hol.push($.atr[$v]) 
        }
      });
      if( !!$.val ){

        api_arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
      }
      else{
        alert('La fecha del sincronario es inválida...')
      }
    }
    
  }

  // Actualizo acumulados
  static val_acu( $ope ){

    let $ = app.var($ope);

    // portales + parejas + pulsares
    $.ide = $_app.ope.var.classList[0].split('-')[2];
    
    // Actualizo total por item
    if( $ope.nextElementSibling && ( $.tot = $ope.nextElementSibling.querySelector('n') ) ){

      $.tot.innerHTML = $_app.tab.lis.querySelectorAll(`._hol-${$.ide}_${$.var_ide}`).length;
    }    
    // Actualizo total general
    if( $.tot = $_app.ope.var.querySelector('div.atr > [name="cue"]') ){

      $.tot.innerHTML = $_app.tab.lis.querySelectorAll(`[class*="_hol-${$.ide}_"]`).length;
    }

    // Actualizo operador de acumulados
    app_tab.act('opc');
  }

  // Secciones por tablero
  static tab_sec( $dat, $ope, ...$opc ){

    let $ = app.var($dat);    

    $.tab = $_app.tab.ide;

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
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.sec.-fon[class*="fon_col-"].${$.cla}`),$.cla);
        }else{
          api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.sec.-fon[class*="fon_col-"]:not(.${$.cla})`),$.cla);
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
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_tra"] > .pos-0.${DIS_OCU}`),DIS_OCU);
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_tra"] > .pos-0:not(.${DIS_OCU})`),DIS_OCU);
          }
          break;
        case 'bor':
          $.cla = 'bor-1';
          if( $dat.checked ){ 
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_tra"]`),$.cla);
          }else{
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_tra"]`),$.cla);
          }            
          break;
        case 'col':
          $.cla = 'fon-0';
          if( $dat.checked ){ 
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_tra"]`),$.cla);
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_tra"]`),$.cla);
          }            
          break;
        }
        break;
      // Célula
      case 'arm_cel':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_cel"] > .pos-0.${DIS_OCU}`),DIS_OCU);
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_cel"] > .pos-0:not(.${DIS_OCU})`),DIS_OCU);
          }          
          break;
        case 'bor': 
          $.cla = 'bor-1'; 
          if( $dat.checked ){
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_cel"]`),$.cla);
          }else{
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_cel"]`),$.cla);
          }              
          break;
        case 'col': 
          $.cla = 'fon-0'; 
          if( $dat.checked ){
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_cel"]`),$.cla);
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_arm_cel"]`),$.cla);
          }              
          break;
        }
        break;          
      // Elemento
      case 'cro_ele':
        switch( $.tip[2] ){
        case 'pos': 
          if( $dat.checked ){
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_cro"] > .pos-0.${DIS_OCU}`),DIS_OCU);
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_cro"] > .pos-0:not(.${DIS_OCU})`),DIS_OCU);
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
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[data-cab="ton"].${DIS_OCU}`),DIS_OCU);
        }else{
          api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[data-cab="ton"]:not(.${DIS_OCU})`),DIS_OCU);
        }        
        break;
      // columas: plasma radial
      case 'rad': 
        if( $dat.checked ){
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[data-cab="rad"].${DIS_OCU}`),DIS_OCU);
        }else{
          api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[data-cab="rad"]:not(.${DIS_OCU})`),DIS_OCU);
        }      
        break;
      // filas: heptadas
      case 'hep':
        if( $dat.checked ){
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.sec-hep.${DIS_OCU}`),DIS_OCU);
        }else{
          api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.sec-hep:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;            
      } 
      break;
    // castillo
    case 'cas':
      if( !$.tip[1] ){
        if( $_app.tab.lis.classList[1] == 'cas' ){
          $_app.tab.lis.querySelectorAll('.app_ope').forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $_app.tab.lis.querySelectorAll(`.pos-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $_app.tab.lis.querySelectorAll(`.pos-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }
      }else{
        switch( $.tip[1] ){
        // posicion
        case 'pos': 
          if( $_app.tab.lis.classList[1] == 'cas' ){
            $_app.tab.lis.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
          }
          else{
            if( $dat.checked ){
              $_app.tab.lis.querySelectorAll(`.pos-0.${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
            }else{
              $_app.tab.lis.querySelectorAll(`.pos-0:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
            }        
          }        
          break;
        // bordes
        case 'bor': 
          $.cla = "bor-1";
          if( $dat.checked ){
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_cas"]:not(.${$.cla})`),$.cla);
          }else{
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_cas"].${$.cla}`),$.cla);
          }
          break;          
        // color de fondo : 1-5
        case 'col':
          $.cla = "fon-0";
          if( $dat.checked ){
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_cas"][class*="fon_col-"].${$.cla}`),$.cla);
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.app_tab[class*="_cas"][class*="fon_col-"]:not(.${$.cla})`),$.cla);
          }             
          break;
        // tog: orbitales
        case 'orb':
          if( $dat.checked ){
            api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.sec.-orb.${DIS_OCU}`),DIS_OCU);            
          }else{
            api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.sec.-orb:not(.${DIS_OCU})`),DIS_OCU);
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
        $.sec_ini = $_app.tab.lis.querySelector('.sec.-ini');
        api_ele.act('cla_agr',$.sec_ini,DIS_OCU);
        if( $dat.checked ){
          $_app.tab.lis.style.gridTemplateRows = 'repeat(21,1fr)';
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.sec.-ton.${DIS_OCU}`),DIS_OCU);
          // muestro seccion
          if( $_app.tab.lis.querySelector(`.sec.-sel:not(.${DIS_OCU})`) ){ 
            api_ele.act('cla_eli',$.sec_ini,DIS_OCU);
          }
        }else{
          $_app.tab.lis.style.gridTemplateRows = 'repeat(20,1fr)';
          api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.sec.-ton:not(.${DIS_OCU})`),DIS_OCU);
        }        
        break;
      // lateral izquierdo
      case 'sel':
        $.sec_ini = $_app.tab.lis.querySelector('.sec.-ini');
        api_ele.act('cla_agr',$.sec_ini,DIS_OCU);
        if( $dat.checked ){
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.sec.-sel.${DIS_OCU}`),DIS_OCU);
          // muestro seccion
          if( $_app.tab.lis.querySelector(`.sec.-ton:not(.${DIS_OCU})`) ){ 
            api_ele.act('cla_eli',$.sec_ini,DIS_OCU);
          }
        }else{
          api_ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`.sec.-sel:not(.${DIS_OCU})`),DIS_OCU);
        }
        break;
      }
      break;
    }
  }
  // portales de activacion
  static tab_pag( $dat, $ope, ...$opc ){

    let $ = app.var($dat);

    $.kin = $_hol.val.kin;

    $.ide = `pag_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`, `_val-opc-${$.ide}`, `_val-opc_act-${$.ide}`];

    $.tab = $_app.tab.ide;

    // galácticos
    if( $.var_ide == 'kin' ){

      $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[data-hol_kin]`).forEach( $pos => {

        $.kin = api_hol._('kin',$pos.dataset['hol_kin']);      

        if( $.kin.pag != 0 ){
          $dat.checked ? api_ele.act('cla_agr',$pos,$.cla) : api_ele.act('cla_eli',$pos,$.cla);
        }
      });
    }
    // solares
    else if( $.var_ide == 'psi' ){

      $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[data-hol_psi]`).forEach( $pos => {

        $.psi = api_hol._('psi',$pos.dataset['hol_psi']);

        $.kin = api_hol._('kin',$.psi.tzo);

        if( $.kin.pag != 0 ){          

          if( $dat.checked ){ 
            api_ele.act('cla_agr',$pos,$.cla);
          }else{
            api_ele.act('cla_eli',$pos,$.cla);
          }
        }
      });
    }
    // Actualizo acumulados
    hol.val_acu($dat);
  }
  // parejas del oráculo
  static tab_par( $dat, $ope, ...$opc ){

    let $ = app.var($dat);

    $.kin = $_hol.val.kin;

    $.ide = `par_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`,"_val-opc-par","_val-opc_act-par"];

    $._par_lis = ['ana','gui','ant','ocu'];
    // marcar todos los patrones del oráculo
    if( !$.var_ide ){

      $._par_lis.forEach( $ide => {

        hol.tab_par( $_app.ope.var.querySelector(`[name="${$ide}"]`) );
      });
    }// por pareja
    else{
      // marco pareja
      if( $._par_lis.includes($.var_ide) ){
        // desmarco todos los anteriores
        api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.${$.cla[0]}`),$.cla);
        // marco correspondientes
        if( $dat.checked ){
          $_app.tab.lis.querySelectorAll(
            `${$_app.tab.cla}[data-hol_kin="${api_hol._('kin',$.kin)[`par_${$.var_ide}`]}"]:not(.${$.cla})`
          ).forEach( $ele =>{ 
            
            api_ele.act('cla_agr',$ele,$.cla);
          })
        }
        // evaluo extensiones
        hol.tab_par( $_app.ope.var.querySelector(`[name="ext"]`) );
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
          api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.${$.cla[0]}`),$.cla);

          // marco extensiones
          if( 
            $dat.checked && $_app.ope.var.querySelector(`[name="${$i}"]`).checked && 
            ( $.ele = $_app.tab.lis.querySelector(`${$_app.tab.cla}[data-hol_kin="${api_hol._('kin',$.kin)[`par_${$i}`]}"]:not(.${$.cla[0]})`) ) 
          ){
            $._kin = api_hol._('kin',$.ele.dataset['hol_kin']);

            $._par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {

              $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[data-hol_kin="${$._kin[$ide_ext]}"]`).forEach( $ext => {
                $.val_tot++;                
                api_ele.act('cla_agr',$ext,$.cla);
              })
            });
          }
        });
        // actualizo cantidades
        $._par_lis.forEach( $ide => {

          if( $.tot = $_app.ope.var.querySelector(`div.atr > [name="${$ide}"] ~ span > n`) ){

            $.tot.innerHTML = $_app.tab.lis.querySelectorAll(`[class*="_hol-par_${$ide}"]`).length;
          }
        });
        // total general
        if( $.tot = $_app.ope.var.querySelector('div.atr > [name="cue"]') ){

          $.tot.innerHTML = $_app.tab.lis.querySelectorAll(`[class*="_hol-par_"]`).length;
        }
        // actualizo acumulado por opciones
        app_tab.act('opc');        
      }
    }
  }
  // pulsares de onda
  static tab_pul( $dat, $ope, ...$opc ){

    let $ = app.var($dat);

    $.kin = $_hol.val.kin;

    $.ide = `pul_${$.var_ide}`;

    $.cla = [`_hol-${$.ide}`,"_val-opc-pul","_val-opc_act-pul"];

    $.tab = $_app.tab.ide;

    // elimino todos los pulsares anteriores
    api_ele.act('htm_eli',$_app.tab.lis.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`));
    
    // inicializo acumulados
    api_ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.${$.cla[0]}`),$.cla);
    
    // posicion principal por kin      
    if( $dat.checked && ( $.pos = $_app.tab.lis.querySelector(`${$_app.tab.cla}[data-hol_kin="${$.kin}"]`) ) ){
      switch( $.tab ){
      // estaciones cromáticas : 1 x 5
      case 'kin_cro': 
        $.val = api_num.ran($.pos.parentElement.getAttribute('pos'),13);
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
        $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[data-hol_kin]`).forEach( $e => {

          $.ton = api_hol._('ton',$e.dataset['hol_ton']);

          if( $.ton_pul == $.ton[$.pul_ide] ) api_ele.act('cla_agr',$e,$.cla);
          
        });
        // muestro pulsares de la o.e.
        $_app.tab.lis.querySelectorAll(`.sec.-ond[data-pul="${$.var_ide}"]`).forEach( $e => {
          
          $e.innerHTML += _hol.ima(`ton_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
        });
      }
    }
    // actualizo acumulados
    hol.val_acu($dat);
  }
}