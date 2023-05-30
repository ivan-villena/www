'use strict';

class App {

  // Peticion url: esq/cab/art/par=val
  Uri = {};

  // Log de peticiones
  Log = { php: [], jso: [] };

  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }    

  }
  
  /* Inicializo Página */
  ini(){

    let $ ={};
    
    // desde Articulo
    if( $.cab = this.Uri.cab ){
      
      // Menu: expando seleccionado
      if( $.app_cab = $Doc.Ope.pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 

        $.app_cab.click();
        
        if( $.art = this.Uri.art ){
          
          // Pinto fondo si hay opcion seleccionada
          if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){
            $.app_art.parentElement.classList.add('fon-sel');
          }
          
          // Índice: hago click y muestro panel
          if( $.art && ( $.art_nav = $Doc.Ope.pan.querySelector('.ide-app_nav ul.ope_lis.nav') ) ){
            
            // inicializo enlace local
            this.nav('tog',$.art_nav);
            
            // muestro panel
            Doc_Ope.pan('app_nav');
          }        
        }
      }  
    }
    // o muestro menú principal
    else{

      // if( $.bot_ini = $Doc.Ope.bot.querySelector('.val_ico.ide-app_cab') ) $.bot_ini.click();
    }  
  }

  /* Indice del Artículo */
  nav( $tip, $dat, $ope ){

    let $ = {};

    switch( $tip ){
    // - Toggles de lista
    case 'tog': 
      if( $ope ){

        return Doc_Ope.val($dat,$ope);
      }
      else if( $.nav = $dat ? this.nav('mar',$dat) : false ){
        // hago toogles ascendentes
        while(
          ( $.lis = dom.ver($.nav,{'eti':'ul'}) ) 
          && 
          ( $.val = $.lis.previousElementSibling ) &&  $.val.classList.contains('doc_ope') &&  $.val.classList.contains('val')
          && 
          ( $.nav = $.val.querySelector('a[href^="#"]') )
        ){
          if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('val_ico') ){
            Doc_Ope.val($.ico);
          }
        }
      }
      break;
    // - Filtros de lista
    case 'ver': 
      if( !$ope ) $ope = 'a[href]';

      // ejecuto filtros
      Doc_Ope.lis_dep('ver', $dat, $ope);

      // volver a marcar el fondo del elemento seleccionado
      this.nav('tog',$Doc.Ope.var.nextElementSibling);

      break;
    // - seleccion
    case 'val': 

      if( !$ope ) $ope = FON_SEL;

      $.lis = dom.ver($dat,{'eti':'nav'});

      if( $.lis ){
        // elimino marcas previas
        $.lis.querySelectorAll(`ul.ope_lis.nav :is( li.pos.sep, li.pos:not(.sep) > .ope_val ).${$ope}`).forEach( 
          $e => $e.classList.remove($ope) 
        );

        // controlo el toggle automatico por dependencias
        if( 
          ( $.dep = $dat.parentElement.parentElement.querySelector('ul.ope_lis') ) 
          &&
          ( $dat.classList.contains('val_ico') || $.dep.classList.contains(DIS_OCU) ) 
        ){
          Doc_Ope.val($dat);
        }

        // pinto fondo
        if( !( $.bot = $dat.parentElement.querySelector('.val_ico') ) || !$.bot.classList.contains('ocu') ){

          $dat.parentElement.classList.add($ope);
        }
      }      
      break;
    // - Marcas
    case 'mar': 
      $.val = location.href.split('#')[1];

      // hago toogle por item
      if( $.val && ( $.nav = $dat.querySelector(`a[href="#${$.val}"]`) ) ){
          
        this.nav('val',$.nav);
      }

      return $.nav;
      break;
    }

  }

  /* Consola del Sistema */
  adm( $tip, $dat, $val ){
  
    let $ = Doc_Ope.var($dat);
    
    // -> desde form : vacío resultados previos
    if( $Doc.Ope.var && ( $.res = $Doc.Ope.var.parentElement.querySelector('.res') ) ){ 

      dom.eli($.res);
    }
    // -> desde menu : capturo form
    else if( $dat.nodeName && $dat.nodeName == 'A' ){

      $Doc.Ope.var = $dat.parentElement.nextElementSibling.querySelector(`.ide-${$tip}`);
    }
    
    switch( $tip ){
    // peticiones al servidor
    case 'aja':
      $.lis = $Doc.Ope.var.parentElement.querySelector(`nav.lis`);

      dom.eli($.lis);

      this.Log.php.forEach( $log => {
        $.ver = document.createElement('a'); 
        $.ver.href = $log;
        $.ver.innerHTML = Doc_Val.let($log); 
        $.ver.target = '_blank'; 
        $.lis.appendChild($.ver);
      });
      break;
    // listado de iconos
    case 'ico':
      
      $.lis = $Doc.Ope.var.parentElement.querySelector(`ul.lis`);
      
      if( !$val ){
        // limpio listado
        dom.eli($.lis);

        for( let $ico in ( $._ico = Dat._('sis.tex_ico') ) ){
          
          $ico = $._ico[$ico];
          $.ico = document.createElement('span');
          $.ico.classList.add('val_ico');
          $.ico.classList.add($ico.ide);
          $.ico.classList.add('mar_der-1');
          $.ico.innerHTML = $ico.val;
          
          $.nom = document.createElement('p');
          $.nom.innerHTML = `<c>-</c> <b class='ide'>${$ico.ide}</b> <c>=</c> ${$ico.val}`;

          $.ite = document.createElement('li');
          for( const $pad in $.ele = { 'ite':['ico','nom'], 'lis':['ite'] } ){
            $.ele[$pad].forEach( $e => $[$pad].appendChild($[$e]) );
          }

          $.lis.appendChild($.ite);
        }
      }
      else{
        if( !$dat.value ){
          Obj.pos($.lis.children).forEach( $e => 
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU) 
          );
        }
        else{
          Obj.pos($.lis.children).forEach( $e => {

            if( Dat.ver( $e.querySelector('.ide').innerHTML, '^^', $dat.value ) ){
              $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU);
            }
            else if( !$e.classList.contains(DIS_OCU) ){
              $e.classList.add(DIS_OCU);
            }
          });
        }
      }
      break;
    // ejecucion desde servidor
    case 'php':
      $.res.classList.add(DIS_OCU);
      $.res.innerHTML = '';

      $.val = $Doc.Ope.var.parentElement.querySelector('pre.res-htm');      
      $.val.classList.add(DIS_OCU);
      $.val.innerText = '';

      Eje.val([ "adm_log", [] ], $res => {

        if( $Doc.Ope.var.querySelector('[name="htm"]').checked ){
          $.res.innerHTML = $res;
          $.res.classList.remove(DIS_OCU);
        }else{
          $.val.innerText = $res;
          $.val.classList.remove(DIS_OCU);
        }
      });
      break;
    }

    return $;
  }

  /* Sesion del Usuario */
  usu(){    
    let $ = {};
    return $;
  }// finalizar sesion
  usu_ses( $tip ){
    let $ = {};

    if( $tip == 'ini' ){



    }
    else if( $tip == 'fin' ){

      console.log("finalizando sesion, hacer cartel de confirmacion");

    }else if( $tip == 'pas' ){

      console.log("regenerando contraseña");

    }

    return $;
  }// datos del perfil
  usu_dat( $tip, $ope ){
    let $ = {};    

    switch( $tip ){
    // administrar datos
    case 'ver':
      
      Doc_Ope.nav_bot( $ope, '$Usu.dat', [ $tip ] );
      break;
    }
    
    return $; 
  }
}