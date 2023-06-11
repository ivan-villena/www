// WINDOW
'use strict';

class Sincronario {

  inicio(){
  
    let $ = {};
  
    if( $App.Cab == 'tablero' ){
  
      Doc_Dat.tab_ini();
      Doc_Dat.lis_ini();
    }
    else if( $App.Cab == 'ciclo' || $App.Cab == 'codigo' ){
      
      // indices, muestro todo
      if( $.bot = $Doc.Ope.pan.querySelector('.ide-app_nav .ope_bot button.val_ico.ide-ope_tog-tod') ){    
  
        $.bot.click();
      } 
    }
  }  

  // proceso diario
  diario( $ele ){

    // operador : fecha + sincronario
    let $ = Doc_Ope.var($ele);

    // peticion: el articulo debe estar definido
    $.uri = `${$App.Esq}/${$App.Cab}/${$App.Art}`;
    
    // calendario gregoriano
    if( $Doc.Ope.var.classList.contains('fec') ){
      
      if( $.fec = $Doc.Ope.var.querySelector('[name="fec"]').value ){

        Arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
      }
      else{

        alert('La fecha del calendario es inválida...');
      }
    }
    // sincronario
    else if( $Doc.Ope.var.classList.contains('sin') ){
      $.atr = {};
      $.hol = [];
      $.val = true;
      ['gal','ani','lun','dia'].forEach( $v => {

        $.atr[$v] = $Doc.Ope.var.querySelector(`[name="${$v}"]`).value;

        if( !$.atr[$v] ){ 

          return $.val = false;          
        }
        else{ 

          $.hol.push($.atr[$v]);
        }
      });

      if( !!$.val ){

        Arc.url(`${$.uri}/sin=${$.hol.join('.')}`);
      }
      else{

        alert('La fecha del sincronario es inválida...');
      }
    }
  }
    
}