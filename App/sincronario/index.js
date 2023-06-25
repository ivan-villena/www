

// inicializo tableros
if( ['orden_sincronico','orden_ciclico'].includes($Doc.Uri.cab) ){

  dat_tab.ini();

}
// expando indice del articulo
else if( ['codigo','tutorial','informe'].includes($Doc.Uri.cab) ){

  let $bot;
  
  // indices, muestro todo
  if( $bot = $Doc.Ope.pan.querySelector('.ide-app_nav .ope_bot .val_ico.ide-ope_tog-tod') ){

    $bot.click();
  }
}