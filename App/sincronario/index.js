
  
if( ['orden_sincronico','orden_ciclico','holon'].includes($Doc.Uri.cab) ){

  dat_tab.ini();

}
else if( ['lectura','codigo'].includes($Doc.Uri.cab) ){

  let $bot;
  
  // indices, muestro todo
  if( $bot = $Doc.Ope.pan.querySelector('.ide-app_nav .ope_bot .val_ico.ide-ope_tog-tod') ){

    $bot.click();
  }
}