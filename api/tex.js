// WINDOW
'use strict';

// Texto : caracter + letra + oracion + parrafo
class api_tex {
  
  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('tex',$ide,'dat');

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        default:        
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];        
          break;
        }        
      }
    }
    
    return $_;
  }

  static val_cod( $dat ){
    // $& significa toda la cadena coincidente
    return $dat.toString().replace(/[.*+\-?^${}()|[\]\\]/g,'\\$&');
  }
  static val_dec( $dat, $opc = '' ){
    return new RegExp( $dat, $opc );
  }
  static val_agr( $dat, $tot = 0, $val = '', $lad = 'izq' ){
    let $_ = '';
    if( $lad=='izq' ){
      $_ = $dat.toString().padStart($tot,$val);
    }else if( $lad=='der' ){
      $_ = $dat.toString().padEnd($tot,$val);
    }else{
      $_ = $dat.toString();
    }
    return $_;
  }

  // letras : c - n
  static let( $dat, $ele={} ){

    let $_ = [], $pal = [], $let = [], $num = 0, $tex_let = api_tex._('let');

    if( $dat !== null && $dat !== undefined && $dat !== NaN ){

      if( typeof($dat) != 'string' ) $dat = $dat.toString();

      $dat.split("\n").forEach( $pal_lis => {

        $pal_lis.split(' ').forEach( $pal_val => {
          
          $num = api_num.val($pal_val);
          if( !!$num || $num == 0 ){
            $pal.push( `<n>${$pal_val}</n>` );
          }
          else{
            $let = [];
            $pal_val.split('').forEach( $car =>{
              $num = api_num.val($car);
              if( !!$num || $num == 0 ){
                $let.push( `<n>${$car}</n>` );
              }
              else if( !!$tex_let[$car] ){
                $let.push( `<c>${$car}</c>` );
              }
              else{
                $let.push( $car );
              }
            });
            $pal.push( $let.join('') );
          }
        });
        $_.push($pal.join(' '));
      });
    }
    return $_.join('<br>');
  }
}