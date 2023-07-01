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
    if( $.cab = $Doc.Uri.cab ){
      
      // Menu: expando seleccionado
      if( $.app_cab = $Doc.Ope.pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 

        $.app_cab.click();
        
        if( $.art = $Doc.Uri.art ){
          
          // Pinto fondo si hay opcion seleccionada
          if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){

            $.app_art.parentElement.classList.add('fon-sel');
          }
          
          // Índice: hago click y muestro panel
          if( $.art && ( $.art_nav = $Doc.Ope.pan.querySelector('.ide-app_nav ul.lis.nav') ) ){
            
            // inicializo enlace local
            Doc_Ope.art_nav('tog',$.art_nav);
            
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
            $e.classList.contains("dis-ocu") && $e.classList.remove("dis-ocu") 
          );
        }
        else{
          Obj.pos($.lis.children).forEach( $e => {

            if( Dat.ver( $e.querySelector('.ide').innerHTML, '^^', $ele.value ) ){
              $e.classList.contains("dis-ocu") && $e.classList.remove("dis-ocu");
            }
            else if( !$e.classList.contains("dis-ocu") ){
              $e.classList.add("dis-ocu");
            }
          });
        }
      }
      break;
    // ejecucion desde servidor
    case 'php':
      $.res.classList.add("dis-ocu");
      $.res.innerHTML = '';

      $.val = $Doc.Ope.var.parentElement.querySelector('pre.res-htm');      
      $.val.classList.add("dis-ocu");
      $.val.innerText = '';

      Eje.val("adm_log", [], $res => {

        if( $Doc.Ope.var.querySelector('[name="htm"]').checked ){
          $.res.innerHTML = $res;
          $.res.classList.remove("dis-ocu");
        }else{
          $.val.innerText = $res;
          $.val.classList.remove("dis-ocu");
        }
      });
      break;
    }

    return $;
  }

  // menú de usuario
  usu( $ide, $var ){

    Doc_Ope.nav_bot( $var, `Api/${$Doc.Uri.esq}/Usuario.ver_${$ide}` );
  }
}