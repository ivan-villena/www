// WINDOW
'use strict';

// sincronario
class _hol {

  // valor seleccioando
  _val = {};
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

    // datos propios
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }  
  // getter
  static _( $ide, $val ){
    let $_=[], $est = `_${$ide}`;
    
    if( $_hol[$est] === undefined ){
      // pido datos
      // .. vuelvo a llamar esta funcion
    }
    if( !!($val) ){
      $_ = $val;
      switch( $ide ){
      case 'fec':
        break;
      default:
        if( typeof($val) != 'object' ){

          if( Number($val) ) $val = parseInt($val)-1;

          $_ = $_hol[$est] && !!($_hol[$est][$val]) ? $_hol[$est][$val] : {};
        }
        break;
      }
    }
    else{
      $_ = $_hol[$est] ? $_hol[$est] : [];
    }
    return $_;
  }

}

// listados
class _hol_lis {

  static val( $tip, $dat, $ope ){

    let $ = _doc.var($dat);

    if( $$.for ) $.lis = $$.for.nextElementSibling;

    $._tip = $tip.split('_');

    switch( $._tip[0] ){
    case 'kin': 
      // libro del kin
      if( !$._tip[1] ){

        if( $.val = _num.val( $$.for.querySelector('[name="ide"]').value ) ){
          
          $.val_kin = `kin-${_num.val($.val,3)}`;

          if( !$ope ){          

            $.res = $$.for.querySelector('output.hol-kin');
      
            $.res.innerHTML = '';
      
            if( $.val && ( $.kin = $.lis.querySelector(`#${$.val_kin} > .hol-kin`) ) ){
              
              $.res.innerHTML = $.kin.innerHTML;
            }        
          }// enlace al kin
          else if( $ope == 'nav' ){
            
            _arc.url_sec( $.val_kin );
          }
        }
      }
      else{
        switch( $._tip[1] ){

        }
      }    
      break;
    }
  }  

  static kin( $dat, $tip, $ope ){

    let $ = _doc.var($dat);

    if( $$.for ) $.lis = $$.for.nextElementSibling;

    // libro del kin
    if( !$tip ){

      $.val = _num.val( $$.for.querySelector('[name="ide"]').value );

      if( !$ope ){

        $.res = $$.for.querySelector('.hol-kin');
  
        $.res.innerHTML = '';
  
        if( $.val && ( $.kin = $.lis.querySelector(`#kin-${_num.val($.val,3)} > .hol-kin`) ) ){
          
          $$.for.replaceChild($.kin,$.res);
        }        

      }
    }
    else{
      switch( $tip ){

      }
    }
  }
}

// valores
class _hol_val {

  // operador : fecha + sincronario
  static ver( $dat ){

    let $ = _doc.var($dat);

    if( !$_api._uri.cab || !['dat','inf'].includes($_api._uri.cab) ){
      $_api._uri.cab = 'dat';
      $_api._uri.art = 'kin';
    }
    
    $.uri = $_api.uri();

    // calendario gregoriano
    if( ( $.ope = $$.for.getAttribute('ide') ) == 'fec' ){
      
      if( $.fec = $$.for.querySelector('[name="fec"]').value ){

        _arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
      }
      else{
        alert('La fecha del calendario es inválida...')
      }
    }
    // sincronario
    else if( $.ope == 'sin' ){
      $.atr = {};
      $.hol = [];
      $.val = true;
      ['gal','ani','lun','dia'].forEach( $v => {

        $.atr[$v] = $$.for.querySelector(`[name="psi_${$v}"]`).value;

        if( !$.atr[$v] ){ 
          return $.val = false;          
        }
        else{ 
          $.hol.push($.atr[$v]) 
        }
      });
      if( !!$.val ){

        $_arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
      }
      else{
        alert('La fecha del sincronario es inválida...')
      }
    }     
  }
  // actualizo acumulados por seleccion de clase
  static acu( $dat, ...$ide ){

    let $ope = $ide.join('_');

    $_hol._val_acu[$ope] = $dat.querySelectorAll(`._val-${$ope}`);

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
    $_hol._val_acu[$ope] = [];    
    $.lis.forEach( $ide => $_hol._val_acu[$ope].push(...$_hol._val_acu[$ide]));    
  }
  // actualizo cuentas y porcentajes sobre totales 
  static cue( $dat, ...$ide ){

    let $ = {
      ide : `${$ide[0]}_${$ide[1]}`,
      tot : false
    };

    // muestro totales
    if( $dat.nextElementSibling && ( $.tot = $dat.nextElementSibling.querySelector('n') )){

      $.tot.innerHTML = $_hol._val_acu[$.ide].length;
    }

    // actualizo acumulado total
    _hol_val.tot($ide[0]);

  }
}

// tableros
class _hol_tab {

  // inicializo operadores
  static act(){

    $$.tab.cla = ( $$.tab.dat_dep = $$.tab.dat.querySelector('.pos > [tab="uni_par"]') ) ? '.pos > [tab="uni_par"] > [pos]' : '.pos';      

    ['opc'].forEach( $ope => {

      if( $$.tab[$ope] ){

        $$.tab[$ope].querySelectorAll(`form[ide] [name][onchange*="_hol_tab."]`).forEach( $inp => 

          _hol_tab[$ope](`${_ele.ver($inp,{'eti':`form`}).getAttribute('ide')}`, $inp )
        );
      }
    });    
  }
  // por posicion principal: parejas + pulsares  
  static pos( $tip, $dat, $ope, ...$opc ){
    
    let $=_doc.var($dat);

    $.kin = $_hol._val.kin;

    $.ide = `${$tip}_${$.var_ide}`;

    $.cla = `_val-${$.ide}`;

    $.tab = $$.tab.ide;

    switch( $tip ){
    // parejas del oráculo
    case 'par':
      $._par_lis = ['ana','gui','ant','ocu'];      
      // marcar todos los patrones del oráculo
      if( !$.var_ide ){

        $._par_lis.forEach( $ide => {

          _hol_tab.pos(`${$tip}`, $$.for.querySelector(`[name="${$ide}"]`) );
        });
      }// por pareja
      else{        
        // marco pareja
        if( $._par_lis.includes($.var_ide) ){
          // desmarco todos los anteriores
          _ele.val('cla_eli',$$.tab.dat.querySelectorAll(`.${$.cla}`),$.cla);
          // marco correspondientes
          if( $dat.checked && ( $.ele = $$.tab.dat.querySelector(`${$$.tab.cla}[hol-kin="${_hol._('kin',$.kin)[`par_${$.var_ide}`]}"]`) ) ){
            _ele.val('cla_agr',$.ele,$.cla);          
          }
          // evaluo extensiones
          _hol_tab.pos(`${$tip}`, $$.for.querySelector(`[name="ext"]`) );
          
        }// extiendo oráculo      
        else if( $.var_ide=='ext' ){
          
          $.val_tot = 0;
          $._par_lis.forEach( $i => {
            $.cla_pos = `_val-par_${$i}-ext`;// elimino marcas previas + marco extensiones por pareja
            _ele.val('cla_eli',$$.tab.dat.querySelectorAll(`.${$.cla_pos}`),$.cla_pos);
            // marco extensiones
            if( $dat.checked && $$.for.querySelector(`[name="${$i}"]`).checked 
              && ( $.ele = $$.tab.dat.querySelector(`${$$.tab.cla}[hol-kin="${_hol._('kin',$.kin)[`par_${$i}`]}"]`) ) 
            ){
              $._kin = _hol._('kin',$.ele.getAttribute('hol-kin'));
              $._par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {
                if( $.ele_ext = $$.tab.dat.querySelector(`${$$.tab.cla}[hol-kin="${$._kin[$ide_ext]}"]`) ){
                  $.val_tot++;
                  _ele.val('cla_agr',$.ele_ext,$.cla_pos);
                }
              });
            }
          });

          // actualizo cantidades
          $._par_lis.forEach( $ide => {
            if( $.tot = $$.for.querySelector(`div.atr > [name="${$ide}"] ~ span > n`) ){
              $.tot.innerHTML = $$.tab.dat.querySelectorAll(`${$$.tab.cla}[class*="_val-par_${$ide}"]`).length;
            }
          });
          // total general
          if( $.tot = $$.for.querySelector('div.atr > [name="cue"]') ){
            $.tot.innerHTML = $$.tab.dat.querySelectorAll(`${$$.tab.cla}[class*="_val-par_"]`).length;
          }
        }
      }
      break;
    // pulsares de la o.e.
    case 'pul':
      if( !$ope ){ $ope = 'kin'; }
      // elimino todos los pulsares anteriores
      $$.tab.dat.querySelectorAll(`[ond][pul="${$.var_ide}"]`).forEach( $v => $v.innerHTML = '' );            
      // inicializo acumulados
      $$.tab.dat.querySelectorAll(`${$$.tab.cla}.${$.cla}`).forEach( $e => $e.classList.remove($.cla) );      
      // posicion principal por kin      
      if( $dat.checked && ( $.pos = $$.tab.dat.querySelector(`${$$.tab.cla}[hol-${$ope}="${$[$ope]}"]`) ) ){
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
            $$.tab.dat.querySelectorAll(`${$$.tab.cla}[hol-kin]`).forEach( $e => {
              if( $.ton_pul == _hol._('ton',$e.getAttribute('hol-ton'))[$.ide] ){                
                $e.classList.add($.cla);
              }
            });
            // muestro pulsares de la o.e.
            $$.tab.dat.querySelectorAll(`[ond][pul="${$.var_ide}"]`).forEach( $e => {
              
              $e.innerHTML += _doc.ima('hol',`ton_pul_${$.var_ide}`, $.ton_pul, {'class':'fon'} );
            });
          }        
          break;
        }
      }
      // acumulados
      _hol_val.acu($$.tab.dat,'pul',$.var_ide);      
      // totales por valor
      _hol_val.cue($dat,'pul',$.var_ide);

      break;
    }
  }  
  // opciones: secciones + posiciones + ...operadores
  static opc( $tip, $dat, $ope, ...$opc ){
    
    let $=_doc.var($dat);

    $.kin = $_hol._val.kin;

    $.cla_ide = `${$.var_ide}_${$ope}`;

    switch( $tip ){
    case 'sec':
      switch( $.var_ide ){
      // células armónicas
      case 'arm': 
        if( $$.tab.dat_dep  ){
          $$.tab.dat.querySelectorAll(`.pos [tab="uni_par"]`).forEach( $v => $v.classList.toggle('bor-0') ); 
        }
        else{
          if( $dat.checked ){
            $$.tab.dat.querySelectorAll(`[tab*="_arm"] > [pos="0"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $$.tab.dat.querySelectorAll(`[tab*="_arm"] > [pos="0"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }
        }
        break;
      // elementos cromáticos
      case 'cro':

        if( $$.tab.dat_dep  ){

          $$.tab.dat.querySelectorAll(`.pos [tab="uni_par"]`).forEach( $v => $v.classList.toggle('bor-0') ); 
        }
        else{
          if( $dat.checked ){
            $$.tab.dat.querySelectorAll(`[tab*="_cro"] > [pos="0"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $$.tab.dat.querySelectorAll(`[tab*="_cro"] > [pos="0"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }
        break;
      // sellos solares
      case 'sel':
        if( $$.tab.ide == 'kin' ){
          $.sec_ini = $$.tab.dat.querySelector('[sec="ini"]');
          $.sec_ini.classList.add(DIS_OCU);
          if( $dat.checked ){
            $$.tab.dat.querySelectorAll(`[sec="sel"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) ); 
            if( $$.tab.dat.querySelector(`[sec="ton"]:not(.${DIS_OCU})`) ){ $.sec_ini.classList.remove(DIS_OCU); }
          }else{
            $$.tab.dat.querySelectorAll(`[sec="sel"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }
        }
        break;                
      // tonos galácticos
      case 'ton':
        if( $$.tab.ide == 'kin' ){
          $.sec_ini = $$.tab.dat.querySelector('[sec="ini"]');
          $.sec_ini.classList.add(DIS_OCU);
          if( $dat.checked ){
            $$.tab.dat.style.grid = 'repeat(21,1fr) / 1fr';
            $$.tab.dat.querySelectorAll(`[sec="ton"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
            if( $$.tab.dat.querySelector(`[sec="sel"]:not(.${DIS_OCU})`) ){ $.sec_ini.classList.remove(DIS_OCU); }
          }else{
            $$.tab.dat.style.grid = 'repeat(20,1fr) / 1fr';
            $$.tab.dat.querySelectorAll(`[sec="ton"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }
        }
        break;
      // onda encantada
      case 'ond':
        if( $dat.checked ){
          // secciones
          $$.tab.dat.querySelectorAll(`[sec="ond"][class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
          // principal
          if( $$.tab.dat.classList.contains('fon-0') ){
            $$.tab.dat.classList.remove('fon-0');
          }
        }else{
          // secciones
          $$.tab.dat.querySelectorAll(`[sec="ond"][class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
          // principal
          if( !$$.tab.dat.classList.contains('fon-0') ){
            $$.tab.dat.classList.add('fon-0');
          }
        }        
        break;
      // castillo
      case 'cas':
        if( $$.tab.dat.getAttribute('tab') == 'cas' ){
          $$.tab.dat.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $dat.checked ){
            $$.tab.dat.querySelectorAll(`[pos="00"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
          }else{
            $$.tab.dat.querySelectorAll(`[pos="00"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
          }        
        }      
        break;                            
      // orbitales
      case 'orb':
        if( $dat.checked ){
          $$.tab.dat.querySelectorAll(`[sec="orb"].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
        }else{
          $$.tab.dat.querySelectorAll(`[sec="orb"]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );
        }      
        break;            
      // héptada
      case 'hep':
        if( $dat.checked ){
          $$.tab.dat.querySelectorAll(`[hep].${DIS_OCU}`).forEach( $v => $v.classList.remove(DIS_OCU) );
        }else{
          $$.tab.dat.querySelectorAll(`[hep]:not(.${DIS_OCU})`).forEach( $v => $v.classList.add(DIS_OCU) );        
        }
        break;
      }
      break;
    case 'pos':
      switch( $.var_ide ){
      }
      break;
    case 'ond': 
      switch( $.var_ide ){
      }
      break;
    }
  }  

}