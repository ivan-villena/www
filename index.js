/* 
  Eventos Globales 
*/
let $ = {};
// evento-1: teclado
document.onkeyup = ( $eve ) => {

  switch( $eve.keyCode ){
  // Escape => ocultar modales
  case 27: 
    // 1- opciones
    if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
      $.men.classList.add(DIS_OCU);
    }
    // 2- operador
    else if( $.ope = document.querySelector(`nav.ope ~ * > [class*="ide-"]:not(.${DIS_OCU})`) ){
      $.nav = $.ope.parentElement.previousElementSibling;
      if( $.ico = $.nav.querySelector(`.fig_ico.fon-sel`) ) $.ico.click();
    }
    // 3- pantalla
    else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
      // oculto la ultima pantalla
      $.art = $api_doc._win.children;          
      for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
        const $art = $.art[$ide];
        if( !$art.classList.contains(DIS_OCU) ){
          api_doc.win( $art.querySelector(`header:first-child .fig_ico[data-ope="fin"]`) );
          break;
        }
      }
    }
    // 4- paneles
    else if( document.querySelector(`.doc_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
      api_doc.pan();
    }
    break;
  }
};
// evento-2: anulo formularios
document.querySelectorAll('form').forEach( 
  $ele => api_ele.val_ope( $ele,'eje_agr','submit',`evt => evt.preventDefault()`) 
);

/* 
  Inicio
*/
// por Articulo
if( $.cab = $sis_log.uri.cab ){
  // Menu: expando seleccionado
  if( $.app_cab = $api_doc._pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 
    $.app_cab.click();
    if( $.art = $sis_log.uri.art ){
      // Pinto fondo si hay opcion seleccionada
      if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){
        $.app_art.parentElement.classList.add('fon-sel');
      }
      // Índice: hago click y muestro panel
      if( $.art && ( $.art_nav = $api_doc._pan.querySelector('.ide-app_nav ul.lis.nav') ) ){
        // inicializo enlace local
        api_lis.nav_tog($.art_nav);
        // muestro panel
        api_doc.pan('app_nav');
      }        
    }
  }  
}// muestro menú
else{
  // if( $.bot_ini = $api_doc._bot.querySelector('.fig_ico.ide-app_cab') ) $.bot_ini.click();
}