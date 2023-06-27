// WINDOW
'use strict';

class Sincronario {

  Val = {};

  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
  }

  /* proceso de datos */
  static dat( $ide, $ele, $var ){

    let $ = Doc_Ope.var($ele);

    switch( $ide ){
    case 'val':

      // peticion: el articulo debe estar definido
      $.uri = ( $var && $var.uri ) ? $var.uri : `${$Doc.Uri.esq}/${$Doc.Uri.cab}/${$Doc.Uri.art}`;
      
      // calendario gregoriano
      if( $Doc.Ope.var.dataset.est == 'fec' ){
        
        if( $.fec = $Doc.Ope.var.querySelector('[name="fec"]').value ){

          Arc.url(`${$.uri}/fec=${$.fec.replaceAll('/','-')}`);
        }
        else{

          alert('La fecha del calendario es inválida...');
        }
      }
      // sincronario
      else if( $Doc.Ope.var.dataset.est == 'val' ){
        
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

      break;
    }
  }

  // sumatorias
  static dat_val_sum( $lis, $dat ){

    let $ = {};
    
    // actualizo: sumatorias + fichas
    $dat.querySelectorAll('fieldset[data-esq][data-est]').forEach( $val => {

      $.sum = 0;
      $lis.forEach( $ite => $.sum += parseInt( $ite.getAttribute(`${$val.dataset.esq}-${$val.dataset.est}`) ) );

      Doc_Dat.fic( $val, $.sum);
    });

  }
}