/* 
  Eventos Globales 
*/
let $ = {};
// evento-1: teclado
document.onkeyup = $eve => {
  switch( $eve.keyCode ){
  // Escape => ocultar modales
  case 27: 
    // menú contextual
    if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
      $.men.classList.add(DIS_OCU);
    }
    // operadores
    else if( $.ope = document.querySelector(`nav.ope ~ * > [class*="ide-"]:not(.${DIS_OCU})`) ){
      $.nav = $.ope.parentElement.previousElementSibling;
      if( $doc.ico = $.nav.querySelector(`.doc_ico.fon-sel`) ) $doc.ico.click();
    }
    // pantallas
    else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
      // oculto la ultima pantalla
      $.art = $api_doc._win.children;          
      for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
        const $art = $.art[$ide];
        if( !$art.classList.contains(DIS_OCU) ){
          doc.win( $art.querySelector(`header:first-child .doc_ico[data-ope="fin"]`) );
          break;
        }
      }
    }
    // navegacion
    else if( document.querySelector(`.doc_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
      doc.pan();
    }
    break;
  }
};
// evento-2: anulo formularios
document.querySelectorAll('form').forEach( 
  $ele => ele.val_ope( $ele,'eje_agr','submit',`evt => evt.preventDefault()`) 
);

/* 
  Inicio
*/
// muestro menu
if( !$sis_log.uri.cab ){

  ( $.bot_ini = $api_doc._bot.querySelector('.doc_ico.app_cab') ) && $.bot_ini.click();
}
// articulo
else{
  // menu: expando seleccionado
  if( $.cab = $api_doc._pan.querySelector(`.ide-app_cab p.ide-${$sis_log.uri.cab}`) )  $.cab.click();

  // operadores
  if( $sis_log.uri.cab == 'ope' ){

    if( $.cla_app = eval($.cla = `${$sis_log.uri.esq}`) ){
      // inicializo: tablero + listado
      lis.tab_ini( $.cla );
      lis.est_ini();
      // 
    }
  }
  // indice por artículo
  else if( $sis_log.uri.art ){
    if( $.art_nav = $api_doc._pan.querySelector('.ide-app_nav ul.lis.nav') ){
      // inicio indice
      lis.nav_tog($.art_nav);
      // muestro panel
      doc.pan('app_nav');
    }
  }
}