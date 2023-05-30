// WINDOW
'use strict';

// Texto : caracter + letra + oracion + parrafo
class Tex {

  // Contenido
  static val(){
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

  // Letras
  static let(){
  }// - Capitalizar primer letra
  static let_pal( $val = "" ){

    let $tex = [];

    $tex = $val.split(' ');

    if( $tex[0] ){

      $tex[0] = $tex[0][0].toUpperCase() + $tex[0].substring(1)
    }      

    return $tex.join(' ');
  }// - Capitalizar todas las palabras
  static let_ora( $val = "" ){

    let $tex = [];

    $val.split(' ').forEach( $pal  => {
      $pal = $pal.toLocaleLowerCase();
      $tex.push( $pal[0].toUpperCase() + $pal.substring(1) );
    } );

    return $tex.join(' ');
  }

}