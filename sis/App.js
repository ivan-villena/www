// WINDOW
'use strict';

class App {

  // Aplicacion
  Esq = "";

  // Menú
  Cab = "";

  // Artículo
  Art = "";

  // Indice
  Nav = "";

  // Log de peticiones
  Log = { php: [], jso: [] };

  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

  }
  
  /* Inicializo Página: menú e indices */
  ini(){

    let $ ={};
    
    // desde Articulo
    if( $.cab = this.Cab ){
      
      // Menu: expando seleccionado
      if( $.app_cab = $Doc.Ope.pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 

        $.app_cab.click();
        
        if( $.art = this.Art ){
          
          // Pinto fondo si hay opcion seleccionada
          if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){

            $.app_art.parentElement.classList.add('fon-sel');
          }
          
          // Índice: hago click y muestro panel
          if( $.art && ( $.art_nav = $Doc.Ope.pan.querySelector('.ide-app_nav ul.ope_lis.nav') ) ){
            
            // inicializo enlace local
            $App.nav('tog',$.art_nav);
            
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
  nav( $tip, $ele, $ope ){

    let $ = {};

    switch( $tip ){
    // - Toggles por item
    case 'tog':
      
      if( $ope ){

        return Doc_Ope.val($ele,$ope);

      }
      else if( $.nav = $ele ? this.nav('mar',$ele) : false ){
        
        // hago toogles ascendentes
        while(
          ( $.lis = Doc.ver($.nav,{'eti':'ul'}) ) 
          && 
          ( $.val = $.lis.previousElementSibling ) &&  $.val.classList.contains('ope_val')
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
      Doc_Ope.lis_dep('ver', $ele, $ope);

      // volver a marcar el fondo del elemento seleccionado
      this.nav('tog',$Doc.Ope.var.nextElementSibling);

      break;
    // - seleccion
    case 'val': 

      if( !$ope ) $ope = FON_SEL;

      $.lis = Doc.ver($ele,{'eti':'nav'});

      if( $.lis ){
        // elimino marcas previas
        $.lis.querySelectorAll(`ul.ope_lis.nav :is( li.pos.sep, li.pos:not(.sep) > .ope_val ).${$ope}`).forEach( 
          $e => $e.classList.remove($ope) 
        );

        // controlo el toggle automatico por dependencias
        if( 
          ( $.dep = $ele.parentElement.parentElement.querySelector('ul.ope_lis') ) 
          &&
          ( $ele.classList.contains('val_ico') || $.dep.classList.contains(DIS_OCU) ) 
        ){
          Doc_Ope.val($ele);
        }

        // pinto fondo
        if( !( $.bot = $ele.parentElement.querySelector('.val_ico') ) || !$.bot.classList.contains('ocu') ){

          $ele.parentElement.classList.add($ope);
        }
      }      
      break;
    // - Marcas
    case 'mar': 
      $.val = location.href.split('#')[1];

      // hago toogle por item
      if( $.val && ( $.nav = $ele.querySelector(`a[href="#${$.val}"]`) ) ){
          
        this.nav('val',$.nav);
      }

      return $.nav;
      break;
    }
  }  

  /* Consola del Sistema */
  adm( $tip, $ele, $val ){
  
    let $ = Doc_Ope.var($ele);
    
    // -> desde form : vacío resultados previos
    if( $Doc.Ope.var && ( $.res = $Doc.Ope.var.parentElement.querySelector('.res') ) ){ 

      Doc.eli($.res);
    }
    // -> desde menu : capturo form
    else if( $ele.nodeName && $ele.nodeName == 'A' ){

      $Doc.Ope.var = $ele.parentElement.nextElementSibling.querySelector(`.ide-${$tip}`);
    }
    
    switch( $tip ){
    // peticiones al servidor
    case 'aja':
      $.lis = $Doc.Ope.var.parentElement.querySelector(`nav.lis`);

      Doc.eli($.lis);

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
        Doc.eli($.lis);

        for( let $ico in ( $._ico = Dat._('var.tex_ico') ) ){
          
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
        if( !$ele.value ){
          Obj.pos($.lis.children).forEach( $e => 
            $e.classList.contains(DIS_OCU) && $e.classList.remove(DIS_OCU) 
          );
        }
        else{
          Obj.pos($.lis.children).forEach( $e => {

            if( Dat.ver( $e.querySelector('.ide').innerHTML, '^^', $ele.value ) ){
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

      Eje.val("adm_log", [], $res => {

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

  // menú de usuario
  usu( $ide, $ope ){

    Doc_Ope.nav_bot( $ope, `App/${this.Esq}/Usuario.ver_${$ide}` );
  }
}