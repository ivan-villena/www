// WINDOW
'use strict';

class Doc_Dat {
  
  /* opciones : esquema.estructura.atributos.valores */
  static opc( $tip, $dat, $ope, ...$opc ){

    let $_="", $ = Doc_Ope.var($dat);

    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $Doc.Ope.var.querySelector(`[name="${$i}"]`) ) Doc.eli( $.ope, `option:not([value=""])` ); 
      });
    };

    switch( $tip ){
    // Seleccion de esquema
    case 'esq':
      $.ini();
      break;
    // Seleccion de estructura
    case 'est':
      $.ini();
      $.val = $dat.value.split('.');
      if( $.ope = $dat.nextElementSibling.nextElementSibling ){
        $.ope.value = "";
        Doc.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          Doc.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    // seleccion de valor por atributo
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        Doc.eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';

        if( $dat.value ){
          
          $.dat = $dat.options[$dat.selectedIndex];        
          
          // identificadores
          $ = Dat.ide( $.dat.dataset.dat ? $.dat.dataset.dat : $.dat.value, $ );

          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;

          Eje.val('Dat::get', [`${$.esq}-${$.est}`], $dat => {
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
      }
      // por seleccion
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
      Doc.eli($ite,'.val_ima');
      if( $.val = $.dat[$.est] ){
        Doc.agr( Doc_Val.ima( $.ima[0], $.ima[1], Dat.get($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }
  
  /* Informe */
  static inf( $ide, $val ){
    
    // pido ficha
    Eje.val(`Doc_Dat::inf`, [ $ide, $val ], $htm => {
      
      // muestro en ventana
      if( $htm ) Doc_Ope.win('ope',{ 

        htm: $htm 
      });

    });
  }  

  /* formularios */
  static Abm = {
    'ope':null,
    'var':{},
    'val':{},
    'err':{}
  };// procesos
  static abm( $tip, $ope ){
      
    let $ = $ope ? Doc_Ope.var($ope) : {};

    Doc_Dat.Abm.ope = $ope;

    Doc_Dat.abm_ini( $ope );
    
    // reinicio formulario
    if( $tip == 'fin' ){      

      $Doc.Ope.var.reset();
    }
    // cargo datos y proceso errores
    else{

      if( $.tip_eli = ( $tip == 'eli' ) ){

        Doc_Ope.win_opc( "¿Confirma Eliminación?", `Doc_Dat.abm_val('${$tip}')` );

      }// proceso errores: var, val y err
      else{
        
        Doc_Dat.abm_var_err();

        if( $.err_cue = Obj.val_cue( Doc_Dat.Abm.err ) ){

          Doc_Dat.abm_err();
        }
      }
      
      // ejecuto procesos
      if( $.tip_eli || !$.err_cue ){
        
        Doc_Dat.abm_val( $tip, $ope );
      }
    }

    return $;

  }// ejecucion
  static abm_val( $tip, $ope ){

    if( !$ope && Doc_Dat.Abm.ope ) $ope = Doc_Dat.Abm.ope;

    let $ = $ope ? Doc_Ope.var($ope) : {};

    // aseguro carga de valores
    Doc_Dat.abm_var();

    // busco ejecuciones
    $.eje = $ope.dataset.eje;

    // ejecuto proceso de javascript
    if( $.eje && /\(/.test($.eje) ){

      try{
      
        eval( $.eje );
      }
      catch( $err ){

        console.error($err);
      }
    }// ejecucion del servidor
    else{

      $.par = [ Doc_Dat.Abm.val ];

      // proceso tipo de operacion
      if( ['agr','mod','eli'].includes($tip) ){

        $.par.push($tip);
      }

      // proceso generico o especificado
      if( !$.eje ){
        // agrego identificador
        $.par.push( `${$.esq}-${$.est}` );
        // funcion generica
        $.eje = "Doc_Dat::abm_val";
      }

      Eje.val($.eje, $.par, $res => {

        if( Num.val($res) ){

          // operacion exitosa: reinicio pagina
          window.location.href = window.location.href;               
        }
        else{

          // muestro mensaje
          if( typeof($res) == 'string' ){            

            if( $res && ( $.ope_err = $Doc.Ope.var.querySelector('.ope_err') ) ){

              Doc_Dat.abm_ini( $ope );
              Doc.agr( $res, $.ope_err );
              Doc.act('cla_eli',$.ope_err,'dis-ocu');
            }
          }// muestro errores por atributo
          else{

            Doc_Dat.Abm.err = $res;
            Doc_Dat.abm_err();
          }
        }
      });
    }
  }// inicializo formulario
  static abm_ini( $ope ){

    let $ = $ope ? Doc_Ope.var($ope) : {};
    
    // despinto fondos
    $Doc.Ope.var.querySelectorAll(`.ope_var :is(select,input,textarea).fon-roj`).forEach( 

      $e => $e.classList.remove('fon-roj') 
    );
    
    // limpio listado de errores
    if( $.ope_err = $Doc.Ope.var.querySelector(`.ope_err`) ){

      Doc.eli($.ope_err);

      Doc.act('cla_agr',$.ope_err,'dis-ocu');      
    }

  }// cargo valores
  static abm_var( $ope ){

    let $_ = { 'var':{}, 'val':{}, 'err':{} }, $ = $ope ? Doc_Ope.var($ope) : {};

    $Doc.Ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {
      
      $_.var[ $atr.name ] = $atr;
      $_.val[ $atr.name ] = $atr.value;
    });

    Doc_Dat.Abm.var = $_.var;
    Doc_Dat.Abm.val = $_.val;
    Doc_Dat.Abm.err = $_.err;

    return $_;

  }// cargo errores y valores
  static abm_var_err( $ope ){

    let $_ = { 'var':{}, 'val':{}, 'err':{} }, $ = $ope ? Doc_Ope.var($ope) : {};
    
    $Doc.Ope.var.querySelectorAll(`[id][name]`).forEach( $atr => {
      
      $.ide = $atr.name;

      $.tip = '';
      $.tip_val = false;

      if( $atr.type ){

        if( /^date/.test($atr.type) ){
          $.tip = 'fec';
        }
        else if( $atr.type == 'text' ){
          $.tip = 'tex';
        }
        else if( $atr.type == 'number' ){
          $.tip = 'num';
        }
        else if( ['email','password','telephone'].includes($atr.type) ){
          $.tip = 'tex';
          switch( $atr.type ){
          case 'email':     $.tip_val = 'mai'; break;
          case 'password':  $.tip_val = 'pas'; break;
          case 'telephone': $.tip_val = 'tel'; break;
          }
          
        }
      }

      $_.err[$.ide] = [];
      $_.var[$.ide] = $atr;
      $_.val[$.ide] = $.val = $atr.value;
      
      // obligatorio ( not null )
      if( !$.val ){

        if( $atr.required || $atr.getAttribute('val_req') ){

          $_.err[$.ide].push(`Es Obligatorio...`);
        }
        // formateo nulos
        else{
          switch( $.tip ){
          case 'fec': $_.val[$.ide] = null; break;
          case 'num': $_.val[$.ide] = null; break;
          }
        }
      }
      // Validaciones de contenido
      else{
        
        // longitud máxima
        if( $.cue = $atr.getAttribute('maxlength') && $.val.length > parseInt($.cue) ){

          $_.err[$.ide].push(`No puede tener más de ${$.cue} caractéres...`);
        }

        // longitud mínima
        if( $.cue = $atr.getAttribute('minlength') && $.val.length < parseInt($.cue) ){

          $_.err[$.ide].push(`Debe tener al menos ${$.cue} caractéres...`);
        }

        // mínimos ( longitud[tex] - valor[num-fec] )
        if( $.min = $atr.getAttribute('min') ){
          switch( $.tip ){
          case 'fec':
            // hacer comparador de fechas 
            break;
          case 'num':
            if( $.val < parseInt($.min) ){
              $_.err[$.ide].push(`No puede ser menor a ${$.min}...`);
            }
            break;
          }
        }

        // máximos ( valores[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            // hacer comparador de fechas 
            break;
          case 'num': 
            if( $.val > parseInt($.max) ){
              $_.err[$.ide].push(`No puede ser mayor que ${$.max}...`);
            }
            break;
          }
        }
        
        // formatos:
        if( $.tip_val ){
          switch( $.tip_val ){
          case 'mai':
            $.reg = /^(([^<>()\[\]\\.,;:\s@”]+(\.[^<>()\[\]\\.,;:\s@”]+)*)|(“.+”))@((\[[0–9]{1,3}\.[0–9]{1,3}\.[0–9]{1,3}\.[0–9]{1,3}])|(([a-zA-Z\-0–9]+\.)+[a-zA-Z]{2,}))$/;
            if( !$.reg.test($.val) ){
              $_.err[$.ide].push(`Su formato es incorrecto...`);
            }
            break;
          }
        }
      }

      // vacio atributos sin errores
      if( !$_.err[$.ide].length ){
        delete($_.err[$.ide]);
      }
    });

    Doc_Dat.Abm.var = $_.var;
    Doc_Dat.Abm.val = $_.val;
    Doc_Dat.Abm.err = $_.err;
    
    return $_;
  }// imprimo errores
  static abm_err( $tex ){

    let $ = {}, $_ = "";

    // elimino errores y colores de fondo
    Doc_Dat.abm_ini();

    // por errores cargados
    if( !$tex ){
      // recorro atributos
      for( const $atr in Doc_Dat.Abm.err ){

        $.err_lis = Obj.pos_ite(Doc_Dat.Abm.err[$atr]);
        
        // capturo elemento  
        if( $.atr = $Doc.Ope.var.querySelector(`[name="${$atr}"]`) ){

          if( $.nom = $.atr.parentElement.querySelector('label') ){

            $.nom = $.atr.parentElement.querySelector('label').innerText;
          }
          else if( $.atr.getAttribute('title') ){

            $.nom = $.atr.getAttribute('title');
          }
          else if( $.atr.getAttribute('title') ){

            $.nom = $.atr.getAttribute('title');
          }else{

            $.nom = $.atr.getAttribute('name');
          }
          
          $_ += `
          <p>El campo <b class='ide err'>${$.nom}</b><c>:</c></p>
          <ul>`;
          // recorro errores
          Obj.pos_ite(Doc_Dat.Abm.err[$atr]).forEach( $err => {
            $_ += `<li><p class='err'>${Doc_Val.let($err)}</p></li>`
          }); $_ += `
          </ul>`;
          // pinto fondo
          if( !$.atr.classList.contains('fon-roj') ){

            $.atr.classList.add('fon-roj');
          }
        }
      }
    }// imprimo texto directo
    else if( typeof($tex) == 'string' ){
      $_ = $tex;
    }

    if( $.ope_err = $Doc.Ope.var.querySelector('.ope_err') ){

      Doc.agr( $_, $.ope_err );

      Doc.act('cla_eli',$.ope_err,'dis-ocu');
    }
    // devuelvo hmtl
    else{

      return $_;
    }    
  }  

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

    // armo imagen
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

          if( Dat.est($.esq,$.est,'inf') ) $.ima_ele.onclick = `Doc_Dat.inf('${$.esq}.${$.est}', ${parseInt($._dat.ide)} )`;
        }
        else if( $.ima_ele.onclick === false ){

          delete($.ima_ele.onclick);
        }
        
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
  }// acumulados : posicion + marca + seleccion
  static val_acu( $dat, $ope, ...$opc ){
    let $ = {};

    // actualizo acumulados
    $.acu_val = {};
    ( $opc.length == 0 ? $Doc.Dat.val.acu : $opc ).forEach( $ide => {

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

  }// filtros : dato + variables
  static val_ver( $tip, $dat, $ope, ...$opc ){

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

            if( $.dat[$.atr] == $.dat_val.value ) Doc.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
            Doc.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
          if( $.inc_val == 1 && Fec.val_ver( $e.getAttribute('var-fec'), $.val.ini, $.val.fin ) ){

            Doc.act('cla_agr',$e,[$.cla_val, $.cla_ide]);
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
          if( $.lim_cue > $.val.lim ) Doc.act('cla_eli',$e,[$.cla_val, $.cla_ide]);
        });
      }
    }
    
  }// conteos : valores de estructura relacionada por atributo
  static val_cue( $tip, $dat, $ope, ...$opc ){
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
  }

  /* Tablero */
  static tab(){ 
  }// inicializo : opciones, posicion, filtros
  static tab_ini(){
    
    let $ = {};

    // Cargo Formularios de Datos
    ['lis','tab'].forEach( $atr => {

      for( const $ide in $Doc.Dat[$atr] ){ 

        if( !!$Doc.Dat[$atr][$ide] && typeof($Doc.Dat[$atr][$ide]) == 'string' ){

          $Doc.Dat[$atr][$ide] = document.querySelector( $Doc.Dat[$atr][$ide] ); 
        }
      }
    });

    // - Actualizo clase principal del tablero
    $Doc.Dat.tab.cla = ".pos.ope";

    // identificador de clase principal: .dat_tab.$esq.$est.$atr
    $Doc.Dat.tab.ide = $Doc.Dat.tab.val.classList[1].split('_')[0];    
    
    // evaluo si la posicion es por dependencia : por oraculos
    $Doc.Dat.tab.dep = $Doc.Dat.tab.val.querySelector(`.pos.dep`) ? true : false;
    
    // inicializo variables: opciones y selectores
    ['sec','pos','est','atr'].forEach( $ide => {      

      if( $Doc.Dat.tab[$ide] ){

        $.eje = ['est','atr'].includes($ide) ? "dat_tab" : "Doc_Dat";

        $Doc.Dat.tab[$ide].querySelectorAll(
          `form[class*="ide-"] [onchange*="${$.eje}.${$ide}"]:is( input:checked, select:not([value=""]) )`
        ).forEach( 
          $inp => {

            $.var = ( $.eje == 'dat_tab' ) ? Doc.ver($inp,{'eti':"form"}).classList[0].split('-')[1]+"-" : "";

            if( $.eje_cla = window[$.eje] ) $.eje_cla[$ide]( $.var + $inp.name );
          }
        );
      }
    })

    // marco posicion principal
    Doc_Dat.tab_val('pos');

    // actualizo opciones
    $Doc.Dat.val.acu.forEach( $ite => {

      if( $.ele = $Doc.Dat.tab.val_acu.querySelector(`[name="${$ite}"]:checked`) ) Doc_Dat.tab_val('acu',$.ele);
    });  

    // inicializo listado
    Doc_Dat.lis_ini();

  }// Actualizo : acumulados, sumatorias, cuentas, fichas, listado
  static tab_act( $var ){
    
    let $={};
    
    $var = !$var ? $Doc.Dat.val.acu : Obj.pos_ite($var);

    $.dat = $Doc.Dat.tab.val;

    // acumulados + listado
    if( $Doc.Dat.tab.val_acu ){ 

      // actualizo toales acumulados
      Doc_Dat.val_acu($Doc.Dat.tab.val, $Doc.Dat.tab.val_acu, ...$var);
            
      // actualizo sumatorias por acumulados
      if( $Doc.Dat.tab.val_sum ){
        
        $.tot = [];
        
        $Doc.Dat.val.acu.forEach( $acu_ide => {

          if( $Doc.Dat.tab.val_acu.querySelector(`[name="${$acu_ide}"]:checked`) ){

            $.tot.push( ...$Doc.Dat.tab.val.querySelectorAll(`[class*="_${$acu_ide}-"]`) );
          }
        });

        Sincronario.dat_val_sum($.tot, $Doc.Dat.tab.val_sum);
      }

      // listado asociado:

      // -> actualizo acumulados
      if( !$Doc.Dat.lis.val_acu.querySelector(`[name="tod"]:checked`) ) Doc_Dat.lis_val('acu');

      // -> ejecuto filtros + actualizo totales
      if( $Doc.Dat.lis.ver ) Doc_Dat.lis_ver();
    }

    // fichas del tablero
    if( ( $Doc.Dat.tab.pos ) && ( $.ima = $Doc.Dat.tab.pos.querySelector(`[name="ima"]`) ) ){

      $.ope = [];
      $var.forEach( $ide => {

        if( $.val = $Doc.Dat.tab.pos.querySelector(`[name="ima_${$ide}"]:checked`) ){

          $.ope.push($.val);
        }        
      });

      if( $.ope.length > 0 ) Doc_Dat.tab_pos($.ima);
    }

    // actualizo cuentas
    if( $Doc.Dat.tab.val_cue ){

      Doc_Dat.val_cue('act', $Doc.Dat.tab.val.querySelectorAll(`.pos.ope:is([class*=-bor],[class*=_act])`), $Doc.Dat.tab.val_cue );
    }
  }// Valores : posicion + marcas + seleccion + opciones por atributo
  static tab_val( $tip, $var, $ope ){

    let $ = Doc_Ope.var($var);

    switch( $tip ){
    // operaciones
    case 'act':
      $.pos_cla = $Doc.Dat.tab.cla;

      // clases: portales + parejas + pulsares + dimensionales + matices + especulares
      $.cla = `${$Doc.Ope.var.classList[0].split('-')[1]}-`;
      
      // Actualizo total por item
      if( $var.nextElementSibling && ( $.tot = $var.nextElementSibling.querySelector('n') ) ){
  
        $.tot.innerHTML = $Doc.Dat.tab.val.querySelectorAll(`${$.pos_cla}.${$.cla}${$.var_ide}`).length;
      }
  
      // Actualizo total general
      if( $.tot = $Doc.Ope.var.querySelector('.ope_var > [name="cue"]') ){
  
        $.tot.innerHTML = $Doc.Dat.tab.val.querySelectorAll(`${$.pos_cla}[class*="${$.cla}"]`).length;
      }
  
      // Actualizo Acumulados
      Doc_Dat.tab_act( $ope ? $ope : "atr" );      
      break;
    case 'acu':
      if( !$.var_ide && $ope ) $ = Doc_Ope.var( $var = $Doc.Dat.tab.val_acu.querySelector(`[name="${$ope}"]`) );
    
      // busco marcas 
      $.cla_ide = `_${$.var_ide}`;    
      
      // marcas por opciones
      if( $.var_ide == 'opc' ){
        
        $Doc.Dat.tab.val.querySelectorAll(`[class*="${$.cla_ide}-"]`).forEach( $ite => {
          
          // recorro clases de la posicion
          $ite.classList.forEach( $cla => {
            
            // si tiene alguna opcion activa
            if( Dat.ver($cla,'^^',`${$.cla_ide}-`) ){
  
              $.cla = $cla.split('-');
  
              $.cla.shift();
  
              $.ite_ide = `${$.cla_ide}_act-${$.cla.join('-')}`;
              
              if( $var.checked && !$ite.classList.contains($.ite_ide) ){
                
                $ite.classList.add($.ite_ide);
              }
              else if( !$var.checked && $ite.classList.contains($.ite_ide) ){
  
                $ite.classList.remove($.ite_ide);
              }
            }
          });
        });
      }
      // aplico bordes
      else{
        Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.${$.cla_ide}-bor`),`${$.cla_ide}-bor`);
  
        if( $var.checked ) Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.${$.cla_ide}-`),`${$.cla_ide}-bor`);
      }    
  
      // actualizo calculos + vistas( fichas + items )
      Doc_Dat.tab_act( $.var_ide );      
      break;
    // por tipo
    case 'pos':

      if( window['dat_tab'] && window['dat_tab']['val_pos'] ){

        $.lis_ele = dat_tab.val_pos($var,$ope);

        if( $.lis_ele.length ){

          $.lis_ele.forEach( $ele => {

            $ele.classList.add(`_pos-`);
    
            if( $Doc.Dat.tab.val_acu && $Doc.Dat.tab.val_acu.querySelector(`[name="pos"]:checked`) ){
    
              $ele.classList.add(`_pos-bor`);
            }
          });
        }
      }

      break;
    case 'mar':
      $var.classList.toggle(`_mar-`);
      // marco bordes
      if( $Doc.Dat.tab.val_acu ){

        if( $var.classList.contains(`_mar-`) && $Doc.Dat.tab.val_acu.querySelector(`[name="mar"]:checked`) ){

          $var.classList.add(`_mar-bor`);
        }
        else if( !$var.classList.contains(`_mar-`) && $var.classList.contains(`_mar-bor`) ){

          $var.classList.remove(`_mar-bor`);
        }
      }
      break;
    case 'ver':

      for( const $ide in $Doc.Dat.val.ope_ver ){

        if( $.ele = $Doc.Dat.tab.ver.querySelector(`${$Doc.Dat.val.ope_ver[$ide]}:not([value=""])`) ){

          Doc_Dat.tab_ver($ide,$.ele,$Doc.Dat.tab.val);

          break;
        }
      }
      break;
    }

    // actualizo totales por tipo
    if( !['act','acu'].includes($tip) ) Doc_Dat.tab_act($tip);  
    
  }// Seleccion : datos, posicion, fecha, ...atributos
  static tab_ver(){

    let $ = {};

    // 1- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
    $.eje = [];
    for( const $ope_ide in $Doc.Dat.val.ver ){
      
      // Elimino todas las clases
      Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`._ver_${$ope_ide}`),[`_ver-`,`_ver_${$ope_ide}`]);
      
      // tomo solo los que tienen valor
      if( ( $.val = $Doc.Dat.tab.ver.querySelector(`${$Doc.Dat.val.ver[$ope_ide]}`) ) && !!$.val.value ){

        $.eje.push($ope_ide);
      }
    }

    // 2- ejecuto todos los filtros
    if( $.eje.length > 0 ){
      $.eje.forEach( ($ope_ide, $ope_pos) => {

        Doc_Dat.val_ver($ope_ide, Obj.pos(
          // si es el 1° paso todas las posiciones, sino solo las filtradas
          $Doc.Dat.tab.val.querySelectorAll( $ope_pos == 0 ? $Doc.Dat.tab.cla : `._ver-` )
        ), 
          $Doc.Dat.tab.ver, 'tab'
        );
        
      });
    }

    // 3- marco bordes de seleccionados
    Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll('._ver-bor'),'_ver-bor');
    if( $Doc.Dat.tab.val_acu && $Doc.Dat.tab.val_acu.querySelector(`[name="ver"]:checked`) ){
      Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`._ver-`),'_ver-bor');
    }

    // actualizo calculos + vistas( fichas + items )
    Doc_Dat.tab_act('ver');    
  }// Secciones : bordes + colores + imagen, ...estructuras
  static tab_sec( $ide ){

    let $var = $Doc.Dat.tab.sec.querySelector(`[name="${$ide}"]`), $ = Doc_Ope.var($var);    

    $.ide = $ide.split('-');

    $.ide = $ide.split('-');

    switch( $.ide[0] ){
    // bordes
    case 'bor':
      if( $var.checked ){
        if( !$Doc.Dat.tab.val.classList.contains('bor-1') ){ $Doc.Dat.tab.val.classList.add('bor-1'); }
        $Doc.Dat.tab.val.querySelectorAll(`.dat_tab:not(.bor-1)`).forEach( $e => $e.classList.add('bor-1') );
      }else{
        if( $Doc.Dat.tab.val.classList.contains('bor-1') ){ $Doc.Dat.tab.val.classList.remove('bor-1'); }
        $Doc.Dat.tab.val.querySelectorAll(`.dat_tab.bor-1`).forEach( $e => $e.classList.remove('bor-1') );
      }
      break;
    // color
    case 'col' :
      if( $var.checked ){
        // secciones
        $Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*="fon_col-"].fon-0`).forEach( $e => $e.classList.remove('fon-0') );
        // principal
        if( $Doc.Dat.tab.val.classList.contains('fon-0') ){
          $Doc.Dat.tab.val.classList.remove('fon-0');
        }
      }else{
        // secciones
        $Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*="fon_col-"]:not(.fon-0)`).forEach( $e => $e.classList.add('fon-0') );
        // principal
        if( !$Doc.Dat.tab.val.classList.contains('fon-0') ){
          $Doc.Dat.tab.val.classList.add('fon-0');
        }
      }
      break;
    // imagen
    case 'ima' :
      if( $var.files && $var.files[0] ){
        $Doc.Dat.tab.val.style.backgroundImage = `url('${URL.createObjectURL($var.files[0])}')`;
      }
      else{
        $Doc.Dat.tab.val.style.backgroundImage = '';
      }
      break;      
    //
    }     
  }// Posiciones : borde + color + imagen + texto + numero + fecha
  static tab_pos( $ide ){

    let $var = $Doc.Dat.tab.pos.querySelector(`[name="${$ide}"]`), $ = Doc_Ope.var($var);    

    $.ide = $ide.split('-');
    
    // proceso dependencias por acumulados
    if( $.ide[1] ){
      
      // aseguro selector
      if( !$var.options  ){

        $var = $Doc.Dat.tab.pos.querySelector(`[name="${$.ide[0]}"]`);
      }

      // opciones por valores
      $[$.ide[0]] = {};

      $Doc.Dat.val.acu.forEach( $ver =>{

        if( $[$.ide[0]][$ver] = $Doc.Dat.tab.pos.querySelector(`[name="${$.ide[0]}-${$ver}"]`) ){ 
          $[$.ide[0]][$ver] = $[$.ide[0]][$ver].checked;
        }
      });      
    }

    switch( $.ide[0] ){
    // marco bordes
    case 'bor':
      $.ope = `bor-1`;
      if( $var.checked ){
        $Doc.Dat.tab.val.querySelectorAll(`${$Doc.Dat.tab.cla}:not(.${$.ope})`).forEach( $e => $e.classList.add($.ope) );
      }else{
        $Doc.Dat.tab.val.querySelectorAll(`${$Doc.Dat.tab.cla}.${$.ope}`).forEach( $e => $e.classList.remove($.ope) );
      }      
      break;                    
    // color de fondo
    case 'col':
      $.ope = `fon_col-`;

      $.eli = `${$Doc.Dat.tab.cla}[class*='${$.ope}']`;
      $.agr = `${$Doc.Dat.tab.cla}`;

      $Doc.Dat.tab.val.querySelectorAll($.eli).forEach( $e => Ele.cla($e,$.ope,'eli','ini' ) );

      if( $var.value ){

        $ = Dat.ide($var.value,$);

        $.dat_ide = ( ( $.dat = $var.options[$var.selectedIndex].getAttribute('dat') ) ? $.dat : $var.value ).split('.');

        $.col_dat = Dat.est_ide('col', ...$.dat_ide );

        $.col = ( $.col_dat && $.col_dat.val ) ? $.col_dat.val : 1;

        $Doc.Dat.tab.val.querySelectorAll($.agr).forEach( $e =>{

          if( $._dat = Dat.get($.esq,$.est,$e.getAttribute(`${$.esq}-${$.est}`)) ){

            $.val = ( $.col == 1 && $._dat[$.atr] > $.col ) ?  0 : $._dat[$.atr];

            $e.classList.add(`${$.ope}${$.col}-${ $.val === 0 ? $.val : Num.ran($.val,$.col) }`);
          }
        });
      }
      break;          
    // imagen / ficha
    case 'ima':
      
      // elimino fichas previas
      $Doc.Dat.tab.val.querySelectorAll($Doc.Dat.tab.cla).forEach( $e => {

        $e.querySelectorAll('.val_ima').forEach( $fic => $fic.parentElement.removeChild($fic) );
      });
      
      if( $var.value ){
        // busco identificadores de datos
        $ = Dat.ide($var.value,$);        

        // busco valores de ficha
        $.fic = Dat.est_ide('ima', ...( ( $.dat = $var.options[$var.selectedIndex].getAttribute('dat') ) ? $.dat : $var.value ).split('.') );

        // actualizo por opciones                
        $Doc.Dat.tab.val.querySelectorAll($Doc.Dat.tab.cla).forEach( $e => {
          // capturar posicion .dep
          $.htm = '';
          $.ele = { title : false, onclick : false  };
          if( $.ima && ( $.ima.pos || $.ima.mar || $.ima.ver || $.ima.opc ) ){

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
            ( $.ima_ele = $e.querySelector('.val_ima') ) ? Doc.mod($.htm,$.ima_ele) : Doc.agr($.htm,$e,'ini');
          }
        });      
      }

      break;
    // valores: num - tex - fec
    default:

      // numerico
      if( $.ide[0] == 'num' ){ 
        $.eti = 'n';           
      }// fecha
      else if( $.ide[0] == 'fec' ){ 
        $.eti = 'time'; 
      }// texto
      else{
        $.eti = 'p';
      }
      
      $Doc.Dat.tab.val.querySelectorAll($Doc.Dat.tab.cla).forEach( $e => Doc.eli($e,$.eti) );

      if( $var.value ){

        $ = Dat.ide($var.value,$);

        $Doc.Dat.tab.val.querySelectorAll($Doc.Dat.tab.cla).forEach( $e =>{

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

    if( $Doc.Dat.lis.val_acu ){

      if( $.ele = $Doc.Dat.lis.val_acu.querySelector(`[name="tod"]`) ){

        Doc_Dat.lis_val('tod',$.ele);
      }
    }

  }// Actualizo : acumulado + cuentas + descripciones
  static lis_act(){

    let $={};
    
    // actualizo total
    if( $Doc.Dat.lis.val_acu && ( $.tot = $Doc.Dat.lis.val_acu.querySelector('[name="tot"]') ) ){
      
      $.tot.innerHTML = Doc_Dat.lis_val('tot');
    }    

    // actualizo cuentas
    if( $Doc.Dat.lis.val_cue ){

      Doc_Dat.val_cue('act', $Doc.Dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`), $Doc.Dat.lis.val_cue);
    }

    // actualizo descripciones
    if( $Doc.Dat.lis.des ){

      $Doc.Dat.lis.des.querySelectorAll(`[name]:checked`).forEach( $e => Doc_Dat.lis_des('tog',$e) );
    }

  }// Valores: totales + acumulados
  static lis_val( $tip, $dat ){
    
    let $_, $ = {};
    
    switch( $tip ){
    // calculo  actualizo totales
    case 'tot': 
      $_ = 0;
      if( $Doc.Dat.lis.val ){

        $_ = $Doc.Dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).length;
      }
      else{

        console.error('No hay tabla relacionada...');
      }

      break;
    // ver todas las filas
    case 'tod': 
      $ = Doc_Ope.var($dat);  
      
      if( $Doc.Dat.lis.val_acu ){
        // ajusto controles acumulados
        $Doc.Dat.val.acu.forEach( $i => {

          if( $.val = $Doc.Dat.lis.val_acu.querySelector(`[name='${$i}']`) ) $.val.disabled = $dat.checked;
        });
      }

      // ejecuto todos los filtros y actualizo totales
      Doc_Dat.lis_ver();
      
      break;
    // actualizo acumulados
    case 'acu':

      if( ( $.esq = $Doc.Dat.lis.val.dataset.esq ) && ( $.est = $Doc.Dat.lis.val.dataset.est ) ){
        
        // oculto todos los items de la tabla
        Doc.act('cla_agr',$Doc.Dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`),DIS_OCU);

        // actualizo por acumulado
        $Doc.Dat.val.acu.forEach( $ide => {

          if( $.val = $Doc.Dat.lis.val_acu.querySelector(`[name='${$ide}']`) ){

            $.tot = 0;
            if( $.val.checked ){
              // recorro seleccionados
              $Doc.Dat.tab.val.querySelectorAll(`.pos[class*="_${$ide}-"]`).forEach( $e =>{
                
                if( $.ele = $Doc.Dat.lis.val.querySelector(`tr.pos[${$.esq}-${$.est}="${$e.getAttribute(`${$.esq}-${$.est}`)}"].${DIS_OCU}`) ){
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
      if( !$Doc.Dat.lis.val_acu || $Doc.Dat.lis.val_acu.querySelector(`[name="tod"]:checked`) ){

        Doc.act('cla_eli',$Doc.Dat.lis.val.querySelectorAll(`tr.pos.${DIS_OCU}`),DIS_OCU);
      }
      // o muestro solo acumulados
      else{

        Doc_Dat.lis_val('acu');
      }

      // 2- cargo filtros : - dato(val) -fecha(ini) -posicion(ini)
      $.eje = [];
      for( const $ope_ide in $Doc.Dat.val.ver ){
        
        // Elimino todas las clases
        Doc.act('cla_eli',$Doc.Dat.lis.val.querySelectorAll(`._ver_${$ope_ide}`),[`_ver-`,`_ver_${$ope_ide}`]);
        
        // tomo solo los que tienen valor
        if( ( $.val = $Doc.Dat.lis.ver.querySelector(`${$Doc.Dat.val.ver[$ope_ide]}`) ) && !!$.val.value ){
          $.eje.push($ope_ide);
        }
        
      }
      // 3º - ejecuto todos los filtros
      if( $.eje.length > 0 ){

        $.eje.forEach( $ope_ide => {

          Doc_Dat.val_ver($ope_ide, Obj.pos( $Doc.Dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`) ), $Doc.Dat.lis.ver);
          
          // oculto valores no seleccionados
          Doc.act('cla_agr',$Doc.Dat.lis.val.querySelectorAll(`tr.pos:not(._ver-, .${DIS_OCU})`),DIS_OCU);
        });
      }
    }
    // por ciclos + agrupaciones
    else if( ['cic','gru'].includes($tip) ){
      
      // muestro todos los items
      Doc.act('cla_eli',$Doc.Dat.lis.val.querySelectorAll(`tbody tr:not(.pos).${DIS_OCU}`),DIS_OCU);        
      
      // aplico filtro
      // ... 
    }
    
    // actualizo total, cuentas y descripciones
    Doc_Dat.lis_act();

  }// Columnas : toggles + atributos
  static lis_atr( $tip, $dat ){

    let $ = Doc_Ope.var($dat);      

    switch( $tip ){
    case 'tog':
      $.esq = $dat.dataset.esq;
      $.est = $dat.dataset.est;
  
      // checkbox
      if( $dat.nodeName == 'INPUT' ){
  
        $Doc.Dat.lis.val.querySelectorAll(
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
  
          Doc_Dat.lis_atr('tog',$e);
        });
      }      
      break;
    }

  }// Descripcion : titulo ( posicion + ciclos + agrupaciones) + detalle ( descripciones, lecturas )
  static lis_des( $tip, $dat ){

    let $ = Doc_Ope.var($dat);      

    switch( $tip ){
    case 'tog':
      $.ope  = $Doc.Ope.var.classList[0].split('-')[1];
      $.esq = $Doc.Ope.var.dataset.esq;
      $.est = $Doc.Ope.var.dataset.est;
      $.atr = $.var_ide;
      
      // oculto todos
      Doc.act('cla_agr',$Doc.Dat.lis.val.querySelectorAll(
        `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"]:not(.${DIS_OCU})`
      ),DIS_OCU);
      
      // muestro titulos y lecturas para los que no están ocultos
      if( $dat.checked ){
  
        $Doc.Dat.lis.val.querySelectorAll(`tr.pos:not(.${DIS_OCU})`).forEach( $ite => {
  
          if( ( $.val = Dat.get($.esq,$.est,$ite.getAttribute(`${$.esq}-${$.est}`)) ) && $.val[$.atr] ){
  
            $.ide=( $.ope == 'des' ) ? $ite.getAttribute(`${$.esq}-${$.est}`) : $.val[$.atr];
  
            Doc.act('cla_eli',$Doc.Dat.lis.val.querySelectorAll(
              `tbody tr[data-ope="${$.ope}"][data-esq="${$.esq}"][data-est="${$.est}"][data-atr="${$.atr}"][data-ide="${$.ide}"].${DIS_OCU}`
            ),DIS_OCU)          
          }
        });
      }        
      break;
    case 'ver':
      // por selectores : titulo + detalle + lectura 
      if( ['tit','det'].includes($.var_ide) ){
    
        // oculto por cilcos y agrupaciones
        $Doc.Dat.lis.val.querySelectorAll(`tbody tr[opc="${$.ite}"]:not([data-ope="des"],.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );

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

              Doc.act('cla_eli',$Doc.Dat.lis.val.querySelectorAll(`tbody tr[data-atr="${$.atr}"]${$.agr}.${DIS_OCU}`),DIS_OCU);            
            }
          }// descripciones por item no oculto
          else{
            $Doc.Dat.lis.val.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e =>{

              if( $.lis_ite = $Doc.Dat.lis.val.querySelector(`table tr[data-atr="${$.atr}_des"][data-ide="${$e.dataset.ide}"].${DIS_OCU}`) ){ 
                $.lis_ite.classList.remove(DIS_OCU);
              }
            });
          }
        }
      }
      // muestro por lecturas
      else if( $.var_ide == 'des' ){

        // desmarco otras opciones
        Doc.act('atr_act',$Doc.Dat.lis.lec.querySelectorAll(`input[name]:not([name="${$.ite}"]):checked`),'checked',false);

        // oculto todas las leyendas
        Doc.act('cla_agr',$Doc.Dat.lis.val.querySelectorAll(`tr[data-ope="${$tip}"]:not(.${DIS_OCU})`),DIS_OCU);

        // muestro por atributo seleccionado      
        if( $dat.checked ){

          $Doc.Dat.lis.val.querySelectorAll(`tbody tr:not(.pos,.${DIS_OCU})`).forEach( $e => {

            if( $.lec = $Doc.Dat.lis.val.querySelector(
              `table tr[data-ope="${$tip}"][data-atr="${$dat.value}"].ide-${$e.dataset.ide}.${DIS_OCU}`
            ) ){
              $.lec.classList.remove(DIS_OCU);
            }
          });
        }
      }      
      break;      
    }

  }
}