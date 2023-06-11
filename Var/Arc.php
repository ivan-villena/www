<?php
// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class Arc {

  /* Contenido */

  // valido archivos por formatos para include/s
  static function val_rec( string $ide, array $arc = [ 'html', 'php' ] ) : string {

    $_ = '';

    foreach( $arc as $tip ){

      if( file_exists( $rec = "{$ide}.{$tip}" ) ){

        $_ = $rec;

        break;
      }        
    }
    return $_;
  }

  /* Enlace */

  // valida y redirecciona
  static function url( string $dat ) : void {

    if( is_link($dat) ){
      
      header("Location: {$dat}"); 
    }
  }
}