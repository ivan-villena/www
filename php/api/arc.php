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
  
  static function dir( $url ) : array {
    $_ = [];

    if( is_dir($url) ){

      foreach( api_arc::val($url) as $arc ){
        if( $arc !== "." && $arc !== ".."){
          // Carpeta          
          if( is_dir($ide = $url."\\".$arc) ){          
            $_ []= [ 'tip'=>"dir", 'nom'=>$arc, 'val'=>api_arc::dir($ide) ];
          }// Links
          elseif( is_link($ide) ){
            $_ []= [ 'tip'=>"url", 'nom'=>$arc, 'val'=>[] ];
          }// Fichero
          elseif( is_file($ide) ){
            $_ []= [ 'tip'=>"fic", 'nom'=>$arc, 'val'=>[] ];
          }          
        }        
      }


    }
    return $_;  
  }
  static function dir_lis( mixed $val, array $ele = [] ) : string {
    $_ = "";
    if( is_dir($val) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      if( !isset($ele['ite']) ) $ele['ite'] = [];

      api_ele::cla($ele['lis'],"app_arc-dir",'ini');
      $_ .= "
      <ul".api_ele::atr($ele['lis']).">";
      foreach( api_arc::dir($val) as $arc ){
        $ele_ite = $ele['ite'];
        api_ele::cla($ele_ite,"{$arc['tip']}",'ini'); $_ .= "
        <li".api_ele::atr($ele_ite).">
          {$arc['nom']}";
          if( $arc['tip'] == 'dir' ){
            $_ .= api_arc::dir_lis( $val."\\".$arc['nom'], [ 'lis'=>[ 'data-pos'=>isset($ele['lis']['data-pos']) ? $ele['lis']['data-pos']+1 : 1 ] ] );
          }
          $_ .= "
        </li>";
      }
      $_ .= "
      </ul>";
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