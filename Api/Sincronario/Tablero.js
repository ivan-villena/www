// WINDOW
'use strict';

class dat_tab {

  // Inicializo : cargo elementos
  static ini(){

    // cargo elementos del documento
    $Doc.Dat.lis = {
      // Valores
      val : `.ope_win .ide-tab_lis .dat_lis`,
      val_acu : `.ope_win .ide-tab_lis .ide-ver .ide-acu`,      
      val_sum : `.ope_win .ide-tab_lis .ide-ver .ide-sum`,
      val_cue : `.ope_win .ide-tab_lis .ide-ver .ide-cue`,    
      // filtros
      ver : `.ope_win .ide-tab_lis .ide-ver`,
      // atributos
      atr : `.ope_win .ide-tab_lis .ide-atr`,      
      // Descripciones
      des : `.ope_win .ide-tab_lis .ide-des`          
    };

    $Doc.Dat.tab = {
      // Valores
      val : `main > article > .dat_tab`,
      val_acu : `.ope_pan > .ide-val .ide-acu`,
      val_sum : `.ope_pan > .ide-val .ide-sum`,
      val_cue : `.ope_pan > .ide-val .ide-cue`,
      // Seleccion
      ver : `.ope_pan > .ide-ver`,
      ver_dat : `.ope_pan > .ide-ver form.ide-dat`,
      ver_pos : `.ope_pan > .ide-ver form.ide-pos`,
      ver_fec : `.ope_pan > .ide-ver form.ide-fec`,
      // secciones
      sec : `.ope_pan > .ide-opc form.ide-sec`,    
      // posiciones
      pos : `.ope_pan > .ide-opc form.ide-pos`,
      // estructuras
      est : `.ope_pan > .ide-opc section.ide-est`,
      // atributos
      atr : `.ope_pan > .ide-ver section.ide-atr`      
    }

    Doc_Dat.tab_ini();    
  }

  // valor por posicion principal
  static val_pos( $var ){
    
    let $_ = [], $ = $var ? Doc_Ope.var($var) : {};

    $.ide = $Doc.Dat.tab.ide;      

    if( $Sincronario && $Sincronario.Val && ( $.val = $Sincronario.Val[$.ide] ) ){        

      // tomo solo posicion diaria
      $.cla_dep = $Doc.Dat.tab.dep ? `.dep[var-fec="${$Sincronario.Val.fec}"] ` : '';

      $_ = $Doc.Dat.tab.val.querySelectorAll(`${$.cla_dep}${$Doc.Dat.tab.cla}[hol-${$.ide}="${$.val}"]`);
    }

    return $_;
  }

  // Seleccion por Atributos
  static atr( $ide ){
    
    let $ = { 'ide': $ide.split('-') }, $var = false;

    if( $.ide[1] ){
      $var = $Doc.Dat.tab.atr.querySelector(`form.ide-${$.ide[0]}`).querySelector(`[name="${$.ide[1]}"]`);
      $ = Doc_Ope.var($var,$);
    }

    $.tab = $Doc.Dat.tab.ide;

    // para acumulados
    $.cla = [`${$ide}`, `_atr-${$ide}`, `_atr_act-${$ide}`];        

    // para seleccion por posicion
    $.kin = $Sincronario.Val.kin;

    switch( $.ide[0] ){
    // kines: portales de activacion      
    case 'pag':

      // galácticos
      if( $.ide[1] == 'kin' ){

        $Doc.Dat.tab.val.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin]`).forEach( $pos => {

          $.kin = Dat._('hol.kin',$pos.getAttribute('hol-kin'));      

          if( $.kin.pag != 0 ) $var.checked ? Doc.act('cla_agr',$pos,$.cla) : Doc.act('cla_eli',$pos,$.cla);

        });
      }
      // solares
      else if( $.ide[1] == 'psi' ){

        $Doc.Dat.tab.val.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-psi]`).forEach( $pos => {

          $.psi = Dat._('hol.psi',$pos.getAttribute('hol-psi'));

          $.kin = Dat._('hol.kin',$.psi.kin);

          if( $.kin.pag != 0 ) $var.checked ? Doc.act('cla_agr',$pos,$.cla) : Doc.act('cla_eli',$pos,$.cla);
        });
      }

      // Actualizo acumulados
      Doc_Dat.tab_val('act',$var);

      break;
    // sellos: oráculo por posicion
    case 'par':
      $.par_lis = ['ana','gui','ant','ocu'];
      // marcar todos los patrones del oráculo
      if( !$.ide[1] ){

        $.par_lis.forEach( $ide => {

          dat_tab.atr(`par-${$ide}`);
        });
      }
      // por pareja
      else{
        // marco pareja
        if( $.par_lis.includes($.ide[1]) ){
          
          // desmarco todos los anteriores
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
          
          // marco correspondientes
          if( $var.checked ){

            $Doc.Dat.tab.val.querySelectorAll(
              `${$Doc.Dat.tab.cla}[hol-kin="${Dat._('hol.kin',$.kin)[`par_${$.ide[1]}`]}"]:not(.${$.cla})`
            ).forEach( $ele =>{ 
              
              Doc.act('cla_agr',$ele,$.cla);
            })
          }

          // evaluo extensiones
          dat_tab.atr('par-ext');
        }
        // extiendo oráculo
        else if( $.ide[1] == 'ext' ){
          
          $.val_tot = 0;
          $.cla[1] = "_atr-par-ext";
          $.cla[2] = "_atr_act-par-ext";
          $.par_lis.forEach( $i => {

            // elimino marcas previas + marco extensiones por pareja
            $.cla[0] = `par-${$i}-ext`;                    
            
            // agrgo 3 clases : -ext , _atr, _atr_act
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);

            // marco extensiones
            if( $var.checked && $Doc.Ope.var.querySelector(`[name="${$i}"]`).checked 
              && 
              ( $.ele = $Doc.Dat.tab.val.querySelector(`${$Doc.Dat.tab.cla}[hol-kin="${Dat._('hol.kin',$.kin)[`par_${$i}`]}"]:not(.${$.cla[0]})`) ) 
            ){
              $._kin = Dat._('hol.kin',$.ele.getAttribute('hol-kin'));

              $.par_lis.map( $ide => `par_${$ide}` ).forEach( $ide_ext => {

                $Doc.Dat.tab.val.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin="${$._kin[$ide_ext]}"]`).forEach( $ext => {
                  $.val_tot++;                
                  Doc.act('cla_agr',$ext,$.cla);
                })
              });
            }
          });
          // actualizo cantidades
          $.par_lis.forEach( $ide => {

            if( $.tot = $Doc.Ope.var.querySelector(`.ope_var > [name="${$ide}"] ~ span > n`) ){

              $.tot.innerHTML = $Doc.Dat.tab.val.querySelectorAll(`[class*="par-${$ide}"]`).length;
            }
          });
          // total general
          if( $.tot = $Doc.Ope.var.querySelector('.ope_var > [name="cue"]') ){

            $.tot.innerHTML = $Doc.Dat.tab.val.querySelectorAll(`[class*="par-"]`).length;
          }

          // actualizo acumulado por opciones
          Doc_Dat.tab_act('opc');        
        }
      }
      
      break;
    // tonos: pulsares por posicion
    case 'pul':

      $.pul_ide = $.ide[1];
      
      // oculto todos los pulsares
      Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec.pul[class*="${$.pul_ide}-"]:not(.dis-ocu)`),"dis-ocu");
      
      // inicializo acumulados
      Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.${$.cla[0]}`),$.cla);
      
      // posicion principal por kin      
      if( $var.checked && ( $.pos = $Doc.Dat.tab.val.querySelector(`${$Doc.Dat.tab.cla}[hol-kin="${$.kin}"]`) ) ){

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
          
          // marco acumulos
          $.ton = Dat._('hol.ton',$.val);

          $.ton_pul = $.ton[$.pul_ide];
          
          $Doc.Dat.tab.val.querySelectorAll(`${$Doc.Dat.tab.cla}[hol-kin]`).forEach( $e => {

            $.ton = Dat._('hol.ton',$e.getAttribute('hol-ton'));

            if( $.ton_pul == $.ton[$.pul_ide] ) Doc.act('cla_agr',$e,$.cla);
            
          });

          // muestro pulsares
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec.pul[class*="${$.pul_ide}-${$.ton_pul}"]`),"dis-ocu");
        }
      }

      // actualizo acumulados
      Doc_Dat.tab_val('act',$var);

      break;
    // tonos: pulsares por seleccion
    case 'dim':
    case 'mat':
    case 'sim':

      $.pul_ide = $.ide[0];
      $.pul_val = $var.value;
      
      // inicializo acumulados      
      $.cla_ver = $Doc.Dat.tab.val.querySelector(`.pos.dep`) ? `.pos.dep` : $Doc.Dat.tab.cla;

      // desmarco pulsares anteriores
      Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`${$.cla_ver}.${$.cla[0]}`),$.cla);

      // oculto pulsar seleccionado
      Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec.pul.${$.pul_ide}-${$.pul_val}:not(.dis-ocu)`),"dis-ocu");      

      if( $var.checked ){
        
        // muestro pulsar seleccionado
        Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec.pul.${$.pul_ide}-${$.pul_val}.dis-ocu`),"dis-ocu");
        
        // acumulo posiciones sin considerar oráculos
        $Doc.Dat.tab.val.querySelectorAll(`${$.cla_ver}[hol-ton]`).forEach( $ele_pos => {
          
          $.ton = Dat._('hol.ton',$ele_pos.getAttribute('hol-ton'));

          if( $.ton && $.ton[$.pul_ide] == $.pul_val ){

            Doc.act('cla_agr',$ele_pos,$.cla);
          }
        });
      }

      // actualizo acumulados
      Doc_Dat.tab_val('act',$var);
    }
  }

  // Estilos por estructura
  static est( $ide ){

    let $ = { 'ide': $ide.split('-') }, $var = false;
    
    if( $.ide[1] ){
      $var = $Doc.Dat.tab.est.querySelector(`form.ide-${$.ide[0]}`).querySelector(`[name="${$.ide[1]}"]`);    
      $ = Doc_Ope.var($var,$);
    }

    switch( $.ide[0] ){
    // parejas del oráculo
    case 'par':
      if( $var.checked ){
        if( $Doc.Dat.tab.val.querySelector(`.dat_tab.par.bor-0`) )
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab.par.bor-0`),'bor-0');
      }
      else{
        if( $Doc.Dat.tab.val.querySelector(`.dat_tab.par:not(.bor-0)`) )
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab.par:not(.bor-0)`),'bor-0');
      }      
      break;
    // plasma radial
    case 'rad':
      switch( $.ide[1] ){
      }
      break;
    // tonos galácticos
    case 'ton':
      switch( $.ide[1] ){
      // posiciones de una onda encantada
      case 'ond': 
        break;
      // color de fondo: 1-4
      case 'col':
        $.cla = 'fon-0';
        if( $var.checked ){
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec.fon.col.${$.cla}`),$.cla);
        }else{
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec.fon.col:not(.${$.cla})`),$.cla);
        }             
        break;
      }
      break;
    // sellos solares
    case 'sel':
      switch( $.ide[1] ){
      // Trayectoria
      case 'arm_tra':
        switch( $.tip[2] ){
        case 'pos': 
          if( $var.checked ){
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_tra"] > .pos.ide-0.dis-ocu`),"dis-ocu");
          }else{
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_tra"] > .pos.ide-0:not(.dis-ocu)`),"dis-ocu");
          }
          break;
        case 'bor':
          $.cla = 'bor-1';
          if( $var.checked ){ 
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
          }else{
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
          }            
          break;
        case 'col':
          $.cla = 'fon-0';
          if( $var.checked ){ 
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
          }else{
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_tra"]`),$.cla);
          }            
          break;
        }
        break;
      // Célula
      case 'arm_cel':
        switch( $.tip[2] ){
        case 'pos': 
          if( $var.checked ){
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_cel"] > .pos.ide-0.dis-ocu`),"dis-ocu");
          }else{
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_cel"] > .pos.ide-0:not(.dis-ocu)`),"dis-ocu");
          }          
          break;
        case 'bor': 
          $.cla = 'bor-1'; 
          if( $var.checked ){
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
          }else{
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
          }              
          break;
        case 'col': 
          $.cla = 'fon-0'; 
          if( $var.checked ){
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
          }else{
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" arm_cel"]`),$.cla);
          }              
          break;
        }
        break;          
      // Elemento
      case 'cro_ele':
        switch( $.tip[2] ){
        case 'pos': 
          if( $var.checked ){
            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" cro"] > .pos.ide-0.dis-ocu`),"dis-ocu");
          }else{
            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab[class*=" cro"] > .pos.ide-0:not(.dis-ocu)`),"dis-ocu");
          }          
          break;
        }
        break;
      }
      break;
    // luna
    case 'lun':
      switch( $.ide[1] ){
      // cabecera
      case 'cab':
        if( $var.checked ){
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`[data-cab="ton"].dis-ocu`),"dis-ocu");
        }else{
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`[data-cab="ton"]:not(.dis-ocu)`),"dis-ocu");
        }        
        break;
      // columas: plasma radial
      case 'rad': 
        if( $var.checked ){
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`[data-cab="rad"].dis-ocu`),"dis-ocu");
        }else{
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`[data-cab="rad"]:not(.dis-ocu)`),"dis-ocu");
        }      
        break;
      // filas: heptadas
      case 'hep':
        if( $var.checked ){
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec.hep.dis-ocu`),"dis-ocu");
        }else{
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec.hep:not(.dis-ocu)`),"dis-ocu");
        }
        break;            
      } 
      break;
    // castillo
    case 'cas':
      // bordes y vistas del centro
      if( !$.ide[1] ){

        if( $Doc.Dat.tab.val.classList[1] == 'cas' ){
          $Doc.Dat.tab.val.querySelectorAll('.pos.ope').forEach( $v => $v.classList.toggle('bor-1') );
        }
        else{
          if( $var.checked ){
            $Doc.Dat.tab.val.querySelectorAll(`.pos.ide-0.dis-ocu`).forEach( $v => $v.classList.remove("dis-ocu") );
          }else{
            $Doc.Dat.tab.val.querySelectorAll(`.pos.ide-0:not(.dis-ocu)`).forEach( $v => $v.classList.add("dis-ocu") );
          }        
        }

      }
      else{
        switch( $.ide[1] ){
        // posicion
        case 'pos': 
          if( $Doc.Dat.tab.val.classList[1] == 'cas' ){
            $Doc.Dat.tab.val.querySelectorAll(`.pos`).forEach( $v => $v.classList.toggle('bor-1') );
          }
          else{
            if( $var.checked ){
              $Doc.Dat.tab.val.querySelectorAll(`.pos.ide-0.dis-ocu`).forEach( $v => $v.classList.remove("dis-ocu") );
            }else{
              $Doc.Dat.tab.val.querySelectorAll(`.pos.ide-0:not(.dis-ocu)`).forEach( $v => $v.classList.add("dis-ocu") );
            }        
          }        
          break;
        // bordes
        case 'bor': 
          $.cla = "bor-1";
          if( $Doc.Dat.tab.val.querySelector(`.dat_tab.hol-cas`) ){
            if( $var.checked ){
              Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab.hol-cas:not(.${$.cla})`),$.cla);
            }else{
              Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab.hol-cas.${$.cla}`),$.cla);
            }
          }else{
            $var.checked ? Doc.act('cla_agr',$Doc.Dat.tab.val,$.cla) : Doc.act('cla_eli',$Doc.Dat.tab.val,$.cla);
          }
          break;          
        // color de fondo : 1-5
        case 'col':
          $.cla = "fon-0";
          // por subcastillos
          if( $Doc.Dat.tab.val.querySelector(`.dat_tab.hol-cas`) ){

            if( $var.checked ){
              Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab.hol-cas[class*="fon_col-"].${$.cla}`),$.cla);
            }else{
              Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.dat_tab.hol-cas[class*="fon_col-"]:not(.${$.cla})`),$.cla);
            } 
          }
          else{

            $var.checked ? Doc.act('cla_eli',$Doc.Dat.tab.val,$.cla) : Doc.act('cla_agr',$Doc.Dat.tab.val,$.cla);
          }
          break;
        // tog: orbitales
        case 'orb':

          if( $var.checked ){

            Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec[class*=" orb-"].dis-ocu`),"dis-ocu");            
          }
          else{

            Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec[class*=" orb-"]:not(.dis-ocu)`),"dis-ocu");
          }
          break;
        }
      }      
      break;
    // tzolkin
    case 'kin':
      switch( $.ide[1] ){
      // cabecera
      case 'ton':

        Doc.act('cla_agr',$.sec_ini = $Doc.Dat.tab.val.querySelector('.sec.ini'),"dis-ocu");
        Doc.act('cla_agr',$.sec_fin = $Doc.Dat.tab.val.querySelector('.sec.fin'),"dis-ocu");

        if( $var.checked ){

          $Doc.Dat.tab.val.style.gridTemplateRows = 'repeat(21,1fr)';
          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec.ton.dis-ocu`),"dis-ocu");
          
          // muestro seccion
          if( $Doc.Dat.tab.val.querySelector(`.sec.sel:not(.dis-ocu)`) ){ 
            Doc.act('cla_eli',$.sec_ini,"dis-ocu");
          }
          if( $Doc.Dat.tab.val.querySelector(`.sec.arm_cel:not(.dis-ocu)`) ){ 
            Doc.act('cla_eli',$.sec_fin,"dis-ocu");
          }          
        }
        else{

          $Doc.Dat.tab.val.style.gridTemplateRows = 'repeat(20,1fr)';
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec.ton:not(.dis-ocu)`),"dis-ocu");
        }        
        break;
      // laterales
      case 'sel': // izquierdo
      case 'arm_cel': // derecho
        
        Doc.act('cla_agr',$.col = $Doc.Dat.tab.val.querySelector(`.sec.${ $.ide[1] == 'sel' ? 'ini' : 'fin' }`),"dis-ocu");
        
        if( $var.checked ){

          Doc.act('cla_eli',$Doc.Dat.tab.val.querySelectorAll(`.sec.${$.ide[1]}.dis-ocu`),"dis-ocu");
          
          // muestro seccion
          if( $Doc.Dat.tab.val.querySelector(`.sec.ton:not(.dis-ocu)`) ){ 
            Doc.act('cla_eli',$.col,"dis-ocu");
          }
        }
        else{
          Doc.act('cla_agr',$Doc.Dat.tab.val.querySelectorAll(`.sec.${$.ide[1]}:not(.dis-ocu)`),"dis-ocu");
        }

        break;
      }
      break;
    }
  }
}