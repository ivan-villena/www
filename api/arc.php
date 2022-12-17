<?php
// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class api_arc {  

  static string $IDE = "api_arc-";
  static string $EJE = "api_arc.";

  function __construct(){
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_arc;
    $est = "_$ide";
    if( !isset($api_arc->$est) ) $api_arc->$est = api_dat::est_ini(DAT_ESQ,"arc{$est}");
    $_dat = $api_arc->$est;
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];
          break;
        }
      }
    }// toda la lista
    elseif( isset($_dat) ){
      $_ = $_dat;
    }
    return $_;
  }

  // valido archivos por formatos para include/s
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
  // tipos de archivo/formato
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
  // carpeta: cargo directorio
  static function dir( string $url ) : array {
    $_ = [];
    if( is_dir($url) ){

      foreach( scandir($url) as $arc ){
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
  }// listado de archivos por carpeta
  static function dir_lis( mixed $dat, array $ope = [] ) : string {
    $_ = "";
    if( is_dir($dat) ){
      if( !isset($ope['lis']) ) $ope['lis'] = [];
      if( !isset($ope['ite']) ) $ope['ite'] = [];

      api_ele::cla($ope['lis'],"lis arc dir mar-1",'ini'); $_ .= "
      <ul".api_ele::atr($ope['lis']).">";
      foreach( api_arc::dir($dat) as $arc ){
        $ele_ite = $ope['ite'];
        api_ele::cla($ele_ite,"{$arc['tip']}",'ini'); $_ .= "
        <li".api_ele::atr($ele_ite).">
          {$arc['nom']}";
          if( $arc['tip'] == 'dir' ){
            $_ .= api_arc::dir_lis($dat."\\".$arc['nom'], [ 'lis'=>[ 'data-pos'=>isset($ope['lis']['data-pos']) ? $ope['lis']['data-pos']+1 : 1 ] ] );
          }
          $_ .= "
        </li>";
      }
      $_ .= "
      </ul>";
    }
    return $_;
  }
  // enlace
  static function url( string $dat ) : void {

    if( is_link($dat) ){
      // -> llama a la pagina
      header("Location: {$dat}"); 
    }
  }
  // fichero: leo contenido
  static function fic( string $dat ) : array {
    $_ = []; 
    if( file_exists($dat) ){      
      $val = opendir($dat); 
      while( $tex = readdir($val) ){ 
        $_[] = $tex; 
      } 
      closedir($val);
    }
    return $_;
  }

  // controladores
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";
    switch( $tip ){
    case 'fic':
      $ope['type'] = 'file';
      if( isset($ope['tip']) ) $ope['accept'] = api_arc::tip($ope['tip']);
      if( isset($ope['multiple']) ) unset($ope['multiple']);
      break;
    case 'dir':
      $ope['type'] = 'file';
      if( isset($ope['tip']) ) $ope['accept'] = api_arc::tip($ope['tip']);
      $ope['multiple'] = '1';
      break;
    case 'url':
      $ope['type']='url';
      break;
    // ima - vid - mus
    default:
      $ope['type']='file';
      $ope['accept'] = api_arc::tip($tip);
      break;      
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".api_ele::atr($ope).">";
    }
    return $_;
  }    
}