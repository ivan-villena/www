<?php
// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class api_arc {  

  static function val( mixed $dat ) : mixed {
    $_=FALSE;
    // remoto
    if( is_link($dat) ){
      header("Location: {$dat}"); // -> llama a la pagina
    }
    // local: carpeta
    elseif( is_dir($dat) ){ 
      $_ = scandir($dat); 
    }
    // local: archivo
    elseif( file_exists($dat) ){ 
      $_=[]; 
      $val = opendir($dat); 
      while( $tex = readdir($val) ){ 
        $_[] = $tex; 
      } 
      closedir($val);
    }
    return $_;
  }

  static function tip( $tip ){
    $_ = "";
    switch( $tip ){
    case 'ima': $_ = ""; break;
    case 'mus': $_ = ""; break;
    case 'vid': $_ = ""; break;
    case 'tex': $_ = ""; break;
    case 'tab': $_ = ""; break;
    case 'eje': $_ = ""; break;
    }
    return $_;
  }    
  // contenido html : valido archivo
  static function ide( string $ide, array $arc = [ 'html', 'php' ] ) : string {

    $_ = '';

    foreach( $arc as $tip ){

      if( file_exists( $rec = "{$ide}.{$tip}" ) ){

        $_ = $rec;

        break;
      }        
    }
    return $_;
  }
}