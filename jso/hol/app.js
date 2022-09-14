// WINDOW
'use strict';

// articulo
class _hol_app {

  // valor seleccioando
  _val = { };
  // valores acumulados
  _val_acu = {

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
  };

  constructor( $dat ){
    
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    }
  }

  ini( $ = {} ){
    // inicializo
    if( $_app.uri.cab == 'tab' ){

      _app_tab.ini();

      _app_est.ini();
  
      $_app.tab.cla = ( $_app.tab.dep = $_app.tab.dat.querySelector('.pos > [tab="uni_par"]') ) ? '.pos > [tab="uni_par"] > [pos]' : '.pos';

      ['opc'].forEach( $ope => {
  
        if( $_app.tab[$ope] ){
  
          $_app.tab[$ope].querySelectorAll(`form[ide] [name][onchange*="_hol_app_tab."]`).forEach( $inp => 
  
            _hol_app_tab[`${$ope}`](`${_ele.ver($inp,{'eti':`form`}).getAttribute('ide')}`, $inp )
          );
        }
      });
    }
  }
}

// -> libros
class _hol_app_bib {
  
  // Encantamiento del sueño
  static enc( $atr, $dat, $ope ){

    let $ = _doc.var($dat);

    if( $_app.var ) $.lis = $_app.var.nextElementSibling;

    switch( $atr ){
    // libro del kin
    case 'kin': 
      if( !$ope ){

        $.val = _num.val( $_app.var.querySelector('[name="ide"]').value );

        $.res = $_app.var.querySelector('.hol-kin');

        $.res.innerHTML = ( $.val && ( $.kin = $.lis.querySelector(`#kin-${_num.val($.val,3)} > .hol-kin`) ) ) ? $.kin.innerHTML : '';
      }
      else{

      }    
      break;
    }
  }     
}

// -> Valores
class _hol_app_val {

  // actualizo acumulados por seleccion de clase
  static acu( $dat, ...$ide ){

    let $ope = $ide.join('_');

    $_hol_app._val_acu[$ope] = $dat.querySelectorAll(`._val-${$ope}`);

  }
  // calculo totales por tipos de operador : portales + parejas + pulsares
  static tot( $dat, $ope ){

    let $ = _doc.var($dat);

    $.lis = [];
    switch( $ope ){
    case 'pag': $.lis = ['kin','psi']; break;
    case 'par': $.lis = ['par_ana','par_gui','par_ant','par_ocu','par_ora','par_ext']; break;
    case 'pul': $.lis = ['pul_dim','pul_mat','pul_sim']; break;
    }

    // actualizo totales
    $_hol_app._val_acu[$ope] = [];    
    $.lis.forEach( $ide => $_hol_app._val_acu[$ope].push(...$_hol_app._val_acu[$ide]));    
  }
  // actualizo cuentas y porcentajes sobre totales 
  static cue( $dat, ...$ide ){

    let $ = {
      ide : `${$ide[0]}_${$ide[1]}`,
      tot : false
    };

    // muestro totales
    if( $dat.nextElementSibling && ( $.tot = $dat.nextElementSibling.querySelector('n') )){

      $.tot.innerHTML = $_hol_app._val_acu[$.ide].length;
    }

    // actualizo acumulado total
    _hol_app_val.tot($ide[0]);
  }
}

// -> Tablero
class _hol_app_tab {

  // posicion : parejas + pulsares
  static pos( $tip, $dat, $ope, ...$opc ){

    let $=_doc.var($dat);

    $.kin = $_hol_app._val.kin;

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

          _hol_app_tab.pos(`${$tip}`, $_app.var.querySelector(`[name="${$ide}"]`) );
        });
      }// por pareja
      else{        
        // marco pareja
        if( $._par_lis.includes($.var_ide) ){
          // desmarco todos los anteriores
          _ele.act('cla_eli',[$_app.tab.dat,`.${$.cla}`],$.cla);
          // marco correspondientes
          if( $dat.checked && ( $.ele = $_app.tab.dat.querySelector(`${$_app.tab.cla}[hol-kin="${_hol._('kin',$.kin)[`par_${$.var_ide}`]}"]`) ) ){
            _ele.act('cla_agr',$.ele,$.cla);          
          }
          // evaluo extensiones
          _hol_app_tab.pos(`${$tip}`, $_app.var.querySelector(`[name="ext"]`) );
          
        }// extiendo oráculo      
        else if( $.var_ide=='ext' ){
          
          $.val_tot = 0;
          $._par_lis.forEach( $i => {
            $.cla_pos = `_val-par_${$i}-ext`;// elimino marcas previas + marco extensiones por pareja
            _ele.act('cla_eli',$_app.tab.dat.querySelectorAll(`.${$.cla_pos}`),$.cla_pos);
            // marco extensiones
            if( $dat.checked && $_app.var.querySelector(`[name="${$i}"]`).checked 
              && ( $.ele = $_app.tab.dat.querySelector(`${$_app.tab.cla}[hol-kin="${_hol._('kin',$.kin)[`par_${$i}`]}"]`) ) 
            ){
              $._kin = _hol._('kin',$.ele.getAttribute('hol-kin'));
              $._par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {
                if( $.ele_ext = $_app.tab.dat.querySelector(`${$_app.tab.cla}[hol-kin="${$._kin[$ide_ext]}"]`) ){
                  $.val_tot++;
                  _ele.act('cla_agr',$.ele_ext,$.cla_pos);
                }
              });
            }
          });

          // actualizo cantidades
          $._par_lis.forEach( $ide => {
            if( $.tot = $_app.var.querySelector(`div.atr > [name="${$ide}"] ~ span > n`) ){
              $.tot.innerHTML = $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}[class*="_val-par_${$ide}"]`).length;
            }
          });
          // total general
          if( $.tot = $_app.var.querySelector('div.atr > [name="cue"]') ){
            $.tot.innerHTML = $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}[class*="_val-par_"]`).length;
          }
        }
      }
      break;
    // pulsares de la o.e.
    case 'pul':
      if( !$ope ){ $ope = 'kin'; }
      // elimino todos los pulsares anteriores
      $_app.tab.dat.querySelectorAll(`[ond][pul="${$.var_ide}"]`).forEach( $v => $v.innerHTML = '' );            
      // inicializo acumulados
      $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}.${$.cla}`).forEach( $e => $e.classList.remove($.cla) );      
      // posicion principal por kin      
      if( $dat.checked && ( $.pos = $_app.tab.dat.querySelector(`${$_app.tab.cla}[hol-${$ope}="${$[$ope]}"]`) ) ){
        switch( $ope ){
        case 'kin': 
          // cromáticas : 1 x 5
          if( /^kin/.test($.tab) && ( /_cro/.test($.tab) ) ){
            $.val = $.pos.parentElement.getAttribute('hol-ton');
          }
          // armónicas : 1 x 20
          else if(  /^kin/.test($.tab) && /_arm/.test($.tab) ){
            $.val = $.pos.parentElement.parentElement.getAttribute('hol-ton');
          }
          // lunas : 1 x 28
          else if(  /^psi/.test($.tab) && /_lun/.test($.tab) ){
            $.val = $.pos.parentElement.parentElement.parentElement.getAttribute('hol-ton');
          }
          // posicion directa x 1
          else if( $.pos.getAttribute('hol-ton') ){
            $.val = $.pos.getAttribute('hol-ton');
          }
          if( $.val ){

            $.ton_pul = _hol._('ton',$.val)[$.ide];
            // marco acumulos
            $_app.tab.dat.querySelectorAll(`${$_app.tab.cla}[hol-kin]`).forEach( $e => {
              if( $.ton_pul == _hol._('ton',$e.getAttribute('hol-ton'))[$.ide] ){                
                $e.classList.add($.cla);
              }
            });
            // muestro pulsares de la o.e.
            $_app.tab.dat.querySelectorAll(`[ond][pul="${$.var_ide}"]`).forEach( $e => {
              
              $e.innerHTML += _doc.ima('hol',`ton_pul_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
            });
          }        
          break;
        }
      }
      // acumulados
      _hol_app_val.acu($_app.tab.dat,'pul',$.var_ide);      
      // totales por valor
      _hol_app_val.cue($dat,'pul',$.var_ide);

      break;
    }    
  }
  // opciones : seccion ( vistas ) + ...posicion
  static opc( $tip, $dat, $ope, ...$opc ){
    let $=_doc.var($dat);
    $.kin = $_hol_app._val.kin;
    $.cla_ide = `${$.var_ide}_${$ope}`;

    switch( $tip ){
    // Seccion
    case 'sec':
      switch( $.var_ide ){
      // células armónicas
      case 'arm': 
        if( $_app.tab.dep  ){
          $_app.tab.dat.querySelectorAll(`.pos [tab="uni_par"]`).forEach( $v => $v.classList.toggle('bor-0') ); 
        }
        else{
          if( $dat.checked ){
            $_app.tab.dat.querySelectorAll(`[tab*="_arm"] > [pos="0"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $_app.tab.dat.querySelectorAll(`[tab*="_arm"] > [pos="0"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }
        }
        break;
      // elementos cromáticos
      case 'cro':

        if( $_app.tab.dep  ){

          $_app.tab.dat.querySelectorAll(`.pos [tab="uni_par"]`).forEach( $v => $v.classList.toggle('bor-0') ); 
        }
        else{
          if( $dat.checked ){
            $_app.tab.dat.querySelectorAll(`[tab*="_cro"] > [pos="0"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $_app.tab.dat.querySelectorAll(`[tab*="_cro"] > [pos="0"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }
        break;
      // sellos solares
      case 'sel':
        if( $_app.tab.ide == 'kin' ){
          $.sec_ini = $_app.tab.dat.querySelector('[sec="ini"]');
          $.sec_ini.classList.add(DIS_OCU);
          if( $dat.checked ){
            $_app.tab.dat.querySelectorAll(`[sec="sel"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) ); 
            if( $_app.tab.dat.querySelector(`[sec="ton"]:not(.${DIS_OCU})`) ){ $.sec_ini.classList.remove(DIS_OCU); }
          }else{
            $_app.tab.dat.querySelectorAll(`[sec="sel"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }
        }
        break;                
      // tonos galácticos
      case 'ton':
        if( $_app.tab.ide == 'kin' ){
          $.sec_ini = $_app.tab.dat.querySelector('[sec="ini"]');
          $.sec_ini.classList.add(DIS_OCU);
          if( $dat.checked ){
            $_app.tab.dat.style.grid = 'repeat(21,1fr) / 1fr';
            $_app.tab.dat.querySelectorAll(`[sec="ton"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
            if( $_app.tab.dat.querySelector(`[sec="sel"]:not(.${DIS_OCU})`) ){ $.sec_ini.classList.remove(DIS_OCU); }
          }else{
            $_app.tab.dat.style.grid = 'repeat(20,1fr) / 1fr';
            $_app.tab.dat.querySelectorAll(`[sec="ton"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }
        }
        break;
      // onda encantada
      case 'ond':
        if( $dat.checked ){
          // secciones
          $_app.tab.dat.querySelectorAll(`[sec="ond"][class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
          // principal
          if( $_app.tab.dat.classList.contains('fon-0') ){
            $_app.tab.dat.classList.remove('fon-0');
          }
        }else{
          // secciones
          $_app.tab.dat.querySelectorAll(`[sec="ond"][class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
          // principal
          if( !$_app.tab.dat.classList.contains('fon-0') ){
            $_app.tab.dat.classList.add('fon-0');
          }
        }        
        break;
      // castillo
      case 'cas':
        if( $_app.tab.dat.getAttribute('tab') == 'cas' ){
          $_app.tab.dat.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $_app.tab.dat.querySelectorAll(`[pos="00"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $_app.tab.dat.querySelectorAll(`[pos="00"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }      
        break;                            
      // orbitales
      case 'orb':
        if( $dat.checked ){
          $_app.tab.dat.querySelectorAll(`[sec="orb"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
        }else{
          $_app.tab.dat.querySelectorAll(`[sec="orb"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
        }      
        break;            
      // héptada
      case 'hep':
        if( $dat.checked ){
          $_app.tab.dat.querySelectorAll(`[hep].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
        }else{
          $_app.tab.dat.querySelectorAll(`[hep]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );        
        }
        break;
      }
      break;
    // Posicion
    case 'pos': break;
    }    
  }
}