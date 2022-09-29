// WINDOW
'use strict';

// articulo
class _hol_app {

  // Datos : acumulados
  dat = {

    acu : {

      'pag':[], 
      'pag_kin':[], 
      'pag_psi':[], 
  
      'par':[], 
      'par_ana':[], 
      'par_gui':[],
      'par_ant':[],
      'par_ocu':[],
      'par_ora':[],
      'par_ext':[],
  
      'pul':[], 
      'pul_dim':[], 
      'pul_mat':[],
      'pul_sim':[]
    }
  };

  constructor( $dat ){
    
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    }
  }

  // inicializo pàgina
  ini( $ = {} ){
    // inicializo
    if( $_api.app_uri.cab == 'tab' ){

      _doc_tab.ini();

      _doc_est.ini();
  
      $_app.tab.cla = ( $_app.tab.dep = $_app.tab.lis.querySelector('.pos > [tab="uni_par"]') ) 
        ? '.pos > [tab="uni_par"] > [pos]' 
        : '.pos'
      ;
      ['sec','atr'].forEach( $ope => {
  
        if( $_app.tab.opc[$ope] ){
  
          $_app.tab.opc[$ope].querySelectorAll(
            `form[ide] [name][onchange*="_hol_app.tab_"]`
          ).forEach( 
            $inp => _hol_app[`tab_${$ope}`]( $inp )
          );
        }
      });
    }
  }
  
  // actualizo acumulados por seleccion de clase
  static dat_acu( $dat, ...$ide ){

    let $ope = $ide.join('_');

    $_hol_app.dat.acu[$ope] = $dat.querySelectorAll(`._val-${$ope}`);

  }
  // calculo totales por tipos de operador : portales + parejas + pulsares
  static dat_tot( $dat, $ope ){

    let $ = _doc_val.var($dat);

    $.lis = [];
    switch( $ope ){
    case 'pag': $.lis = ['kin','psi']; break;
    case 'par': $.lis = ['par_ana','par_gui','par_ant','par_ocu','par_ora','par_ext']; break;
    case 'pul': $.lis = ['pul_dim','pul_mat','pul_sim']; break;
    }

    // actualizo totales
    $_hol_app.dat.acu[$ope] = [];
    $.lis.forEach( 
      $ide => $_hol_app.dat.acu[$ope].push(...$_hol_app.dat.acu[$ide])
    );
  }
  // actualizo cuentas y porcentajes sobre totales 
  static dat_cue( $dat, ...$ide ){

    let $ = { ide : `${$ide[0]}_${$ide[1]}`, tot : false };

    // muestro totales
    if( $dat.nextElementSibling && ( $.tot = $dat.nextElementSibling.querySelector('n') )){

      $.tot.innerHTML = $_hol_app.dat.acu[$.ide].length;
    }

    // actualizo acumulado total
    _hol_app.dat_tot($ide[0]);
  }

  // Atributos : parejas + pulsares
  static tab_atr( $tip, $dat, $ope, ...$opc ){

    let $ = _doc_val.var($dat);

    $.pos = $_app.tab.lis.querySelector(`${$_app.tab.cla}[api-hol_kin]._val-pos`);

    $.kin = $.pos.getAttribute('api-hol_kin');

    $.ide = `${$tip}_${$.var_ide}`;

    $.cla = `_val-${$.ide}`;

    $.tab = $_app.tab.ide;

    switch( $tip ){
    // parejas del oráculo
    case 'par':
      $._par_lis = ['ana','gui','ant','ocu'];      
      // marcar todos los patrones del oráculo
      if( !$.var_ide ){

        $._par_lis.forEach( $ide => {

          _hol_app.tab_atr(`${$tip}`, $_app.var.querySelector(`[name="${$ide}"]`) );
        });
      }// por pareja
      else{        
        // marco pareja
        if( $._par_lis.includes($.var_ide) ){

          // desmarco todos los anteriores
          _ele.act('cla_eli',[$_app.tab.lis,`.${$.cla}`],$.cla);

          console.log( $.kin, $.var_ide,  _hol._('kin',$.kin)[`par_${$.var_ide}`] );
          
          // marco correspondientes
          if( 
            $dat.checked 
            && 
            ( $.ele = $_app.tab.lis.querySelector(`${$_app.tab.cla}[api-hol_kin="${_hol._('kin',$.kin)[`par_${$.var_ide}`]}"]`) ) 
          ){
            _ele.act('cla_agr',$.ele,$.cla);          
          }
          // evaluo extensiones
          _hol_app.tab_atr(`${$tip}`, $_app.var.querySelector(`[name="ext"]`) );
          
        }
        // extiendo oráculo      
        else if( $.var_ide == 'ext' ){
          
          $.val_tot = 0;
          $._par_lis.forEach( $i => {
            // elimino marcas previas + marco extensiones por pareja
            $.cla_pos = `_val-par_${$i}-ext`;
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`.${$.cla_pos}`),$.cla_pos);
            // marco extensiones
            if( 
              $dat.checked && $_app.var.querySelector(`[name="${$i}"]`).checked 
              && 
              ( $.ele = $_app.tab.lis.querySelector(`${$_app.tab.cla}[api-hol_kin="${_hol._('kin',$.kin)[`par_${$i}`]}"]`) ) 
            ){
              $._kin = _hol._('kin',$.ele.getAttribute('api-hol_kin'));
              $._par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {
                if( 
                  $.ele_ext = $_app.tab.lis.querySelector(`${$_app.tab.cla}[api-hol_kin="${$._kin[$ide_ext]}"]`) 
                ){
                  $.val_tot++;
                  _ele.act('cla_agr',$.ele_ext,$.cla_pos);
                }
              });
            }
          });
          // actualizo cantidades
          $._par_lis.forEach( $ide => {

            if( $.tot = $_app.var.querySelector(`div.atr > [name="${$ide}"] ~ span > n`) ){

              $.tot.innerHTML = $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[class*="_val-par_${$ide}"]`).length;
            }
          });
          // total general
          if( $.tot = $_app.var.querySelector('div.atr > [name="cue"]') ){

            $.tot.innerHTML = $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[class*="_val-par_"]`).length;
          }
        }
      }
      break;
    // pulsares de la o.e.
    case 'pul':
      // elimino todos los pulsares anteriores
      $_app.tab.lis.querySelectorAll(`[sec="ond"][data-pul="${$.var_ide}"]`).forEach( $v => $v.innerHTML = '' );            
      // inicializo acumulados
      $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}.${$.cla}`).forEach( $e => $e.classList.remove($.cla) );      
      // posicion principal por kin      
      if( $dat.checked && ( $.pos = $_app.tab.lis.querySelector(`${$_app.tab.cla}[api-hol_kin="${$.kin}"]`) ) ){
        switch( $.tab ){
        // estaciones cromáticas : 1 x 5
        case 'kin_cro': 
          $.val = _num.ran($.pos.parentElement.getAttribute('pos'),13);
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
          if( $.pos.getAttribute('api-hol_ton') ) $.val = $.pos.getAttribute('api-hol_ton');         
          break;
        }
        if( $.val ){

          $.ton_pul = _hol._('ton',$.val)[ $.pul_ide = $.ide.split('_')[1] ];
          
          // marco acumulos
          $_app.tab.lis.querySelectorAll(`${$_app.tab.cla}[api-hol_kin]`).forEach( $e => {
            if( $.ton_pul == _hol._('ton',$e.getAttribute('api-hol_ton'))[$.pul_ide] ){                
              $e.classList.add($.cla);
            }
          });
          // muestro pulsares de la o.e.
          $_app.tab.lis.querySelectorAll(`[sec^="ond"][data-pul="${$.var_ide}"]`).forEach( $e => {
            
            $e.innerHTML += _doc.ima('api',`hol_ton_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
          });
        }
      }
      // acumulados
      _hol_app.dat_acu($_app.tab.lis,'pul',$.var_ide);

      // totales por valor
      _hol_app.dat_cue($dat,'pul',$.var_ide);

      break;
    }    
  }
  // Secciones por tablero
  static tab_sec( $dat, $ope, ...$opc ){

    let $ = _doc_val.var($dat);

    $.tab = $_app.tab.ide;

    $.pos = $_app.tab.lis.querySelector(`${$_app.tab.cla}[api-hol_kin]._val-pos`);

    $.kin = $.pos.getAttribute('api-hol_kin');

    $.tip = $.var_ide.split('-');

    switch( $.tip[0] ){
    // plasma radial
    case 'rad':
      if( !$.tip[1] ){
      }else{
        switch( $.tip[1] ){
        }
      }
      break;
    // tonos galácticos
    case 'ton':
      if( !$.tip[1] ){
        if( $.tab == 'kin-tzo' ){
          $.sec_ini = $_app.tab.lis.querySelector('[sec="ini"]');
          _ele.act('cla_agr',$.sec_ini,DIS_OCU);
          if( $dat.checked ){
            $_app.tab.lis.style.grid = 'repeat(21,1fr) / 1fr';
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[sec="ton"].${DIS_OCU}`),DIS_OCU);
            // muestro seccion
            if( $_app.tab.lis.querySelector(`[sec="sel"]:not(.${DIS_OCU})`) ){ 
              _ele.act('cla_eli',$.sec_ini,DIS_OCU);
            }
          }else{
            $_app.tab.lis.style.grid = 'repeat(20,1fr) / 1fr';
            _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[sec="ton"]:not(.${DIS_OCU})`),DIS_OCU);
          }
        }// holones
        else{
        }
      }else{
        switch( $.tip[1] ){
        // posiciones de una onda encantada
        case 'ond': 
          break;
        // color de fondo: 1-4
        case 'col':
          $.cla = 'fon-0';
          if( $dat.checked ){
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[sec="fon"][class*="fon_col-"].${$.cla}`),$.cla);
          }else{
            _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[sec="fon"][class*="fon_col-"]:not(.${$.cla})`),$.cla);
          }             
          break;
        }
      }
      break;
    // sellos solares
    case 'sel':
      if( !$.tip[1] ){
        if( $.tab == 'kin-tzo' ){
          $.sec_ini = $_app.tab.lis.querySelector('[sec="ini"]');
          _ele.act('cla_agr',$.sec_ini,DIS_OCU);          
          if( $dat.checked ){
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[sec="sel"].${DIS_OCU}`),DIS_OCU);
            // muestro seccion
            if( $_app.tab.lis.querySelector(`[sec="ton"]:not(.${DIS_OCU})`) ){ 
              _ele.act('cla_eli',$.sec_ini,DIS_OCU);
            }
          }else{
            _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[sec="sel"]:not(.${DIS_OCU})`),DIS_OCU);
          }
        }// holones
        else{

        }
      }else{
        switch( $.tip[1] ){
        // Trayectoria
        case 'arm_tra':
          if( !$.tip[2] ){
            if( $dat.checked ){
              _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_arm_tra"] > [pos="0"].${DIS_OCU}`),DIS_OCU);
            }else{
              _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_arm_tra"] > [pos="0"]:not(.${DIS_OCU})`),DIS_OCU);
            }            
          }else{
            switch( $.tip[2] ){
            case 'bor':
              $.cla = 'bor-1';
              if( $dat.checked ){ 
                _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_arm_tra"]`),$.cla);
              }else{
                _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_arm_tra"]`),$.cla);
              }            
              break;
            case 'col':
              $.cla = 'fon-0';
              if( $dat.checked ){ 
                _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_arm_tra"]`),$.cla);
              }else{
                _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_arm_tra"]`),$.cla);
              }            
              break;
            }
          }
          break;
        // Célula
        case 'arm_cel':
          if( !$.tip[2] ){
            if( $dat.checked ){
              _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_arm_cel"] > [pos="0"].${DIS_OCU}`),DIS_OCU);
            }else{
              _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_arm_cel"] > [pos="0"]:not(.${DIS_OCU})`),DIS_OCU);
            }
          }else{
            switch( $.tip[2] ){
            case 'bor': 
              $.cla = 'bor-1'; 
              if( $dat.checked ){
                _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_arm_cel"]`),$.cla);
              }else{
                _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_arm_cel"]`),$.cla);
              }              
              break;
            case 'col': 
              $.cla = 'fon-0'; 
              if( $dat.checked ){
                _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_arm_cel"]`),$.cla);
              }else{
                _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_arm_cel"]`),$.cla);
              }              
              break;
            }            
          }          
          break;          
        // Elemento
        case 'cro_ele':
          if( !$.tip[2] ){
            if( $dat.checked ){
              _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab*="_cro"] > [pos="0"].${DIS_OCU}`),DIS_OCU);
            }else{
              _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab*="_cro"] > [pos="0"]:not(.${DIS_OCU})`),DIS_OCU);
            }            
          }
          break;
        }
      }
      break;
    // castillo
    case 'cas':
      if( !$.tip[1] ){
        if( $_app.tab.lis.getAttribute('tab') == 'cas' ){
          $_app.tab.lis.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $_app.tab.lis.querySelectorAll(`[pos="00"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $_app.tab.lis.querySelectorAll(`[pos="00"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }
      }else{
        switch( $.tip[1] ){
        // bordes
        case 'bor': 
          $.cla = "bor-1";
          if( $dat.checked ){
            _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_cas"]:not(.${$.cla})`),$.cla);
          }else{
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_cas"].${$.cla}`),$.cla);
          }
          break;          
        // color de fondo : 1-5
        case 'col':
          $.cla = "fon-0";
          if( $dat.checked ){
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[tab$="_cas"][class*="fon_col-"].${$.cla}`),$.cla);
          }else{
            _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[tab$="_cas"][class*="fon_col-"]:not(.${$.cla})`),$.cla);
          }             
          break;
        // tog: orbitales
        case 'orb':
          if( $dat.checked ){
            _ele.act('cla_eli',$_app.tab.lis.querySelectorAll(`[sec="orb"].${DIS_OCU}`),DIS_OCU);            
          }else{
            _ele.act('cla_agr',$_app.tab.lis.querySelectorAll(`[sec="orb"]:not(.${DIS_OCU})`),DIS_OCU);
          }
          break;
        }
      }      
      break;
    // luna
    case 'lun':
      if( !$.tip[1] ){

      }else{
        switch( $.tip[1] ){
        // tog-columas: plasma radial
        case 'rad': 
          break;
        // tog-filas: heptadas
        case 'hep':
          if( $dat.checked ){
            $_app.tab.lis.querySelectorAll(`[hep].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $_app.tab.lis.querySelectorAll(`[hep]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );        
          }
          break;            
        }
      }      
      break;
    }    

  }
}