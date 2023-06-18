
  
if( $Doc.Uri.cab == 'kin' || $Doc.Uri.cab == 'psi' ){

  dat_tab.ini();

}
else if( $Doc.Uri.cab == 'codigo' || $Doc.Uri.cab == 'tutorial' ){

  let $bot;
  
  // indices, muestro todo
  if( $bot = $Doc.Ope.pan.querySelector('.ide-app_nav .ope_bot .val_ico.ide-ope_tog-tod') ){    

    $bot.click();
  } 
}