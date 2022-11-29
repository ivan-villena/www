<?php
// Texto : caracter + letra + oracion + parrafo
class tex {

  static string $IDE = "tex-";
  static string $EJE = "tex.";

  function __construct(){

    $this->_let = dat::get('tex_let', [ 'niv'=>['ide'] ]);

  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_tex;
    $est = "_$ide";
    if( !isset($api_tex->$est) ) $api_tex->$est = dat::est_ini(DAT_ESQ,"tex{$est}");
    $_dat = $api_tex->$est;
    
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

  // salvo caracteres con "\"
  static function val_cod( string $val, string $cod = ".*+\-?^{}()|[\]\$\\", string $opc = "" ) : string {
  
    return preg_replace("/[{$cod}]/{$opc}",$val,'\\$&');// $& significa toda la cadena coincidente

  }// devuelvo longitud
  static function val_cue( string $val, string $cod = "UTF-8") : int {
  
    return mb_strlen($val, $cod);
  }// extraigo subcadena
  static function val_ver( string $val, int $ini, int $fin, string $cod="UTF-8" ) : string {
  
    return mb_substr($val, $ini, $fin, $cod);
  }// relleno por lado con valor repetitivo
  static function val_agr( int | float | string $val, int $cue = 1, string $rep = ' ', string $lad = 'izq' ) : string {
  
    return str_pad( $val, $cue, $rep, $lad == 'izq' ? STR_PAD_LEFT : STR_PAD_RIGHT );
  }// modifico subcadena
  static function val_mod( string $dat, string $ver, string $val ) : string {

    return str_replace($ver,$val,$dat);
  }

  // letra : ( n, c )
  static function let( string $dat, array $ele=[] ) : string {
    $_ = [];
    $pal = [];
    $tex_let = tex::_('let');
    // saltos de linea
    foreach( explode('\n',$dat) as $tex_pal ){
      // espacios
      foreach( explode(' ',$tex_pal) as $pal_val ){
        // numero completo
        if( is_numeric($pal_val) ){
          $pal []= "<n>{$pal_val}</n>";
        }// caracteres
        else{
          $let = [];
          foreach( tex::let_sep($pal_val) as $car ){
            if( is_numeric($car) ){
              $let []= "<n>{$car}</n>";
            }elseif( isset($tex_let[$car]) ){
              $let []= "<c>{$car}</c>";        
            }else{
              $let []= $car;
            }
          }
          $pal []= implode('',$let);
        }
      }
      $_ []= implode(' ',$pal);
    }
    return implode('<br>',$_);    
  }
  // separador con acentos y caracteres especiales
  static function let_sep( string $val, int $gru = 0, string $cod = "UTF-8" ) : array {
    // => https://diego.com.es/extraccion-de-strings-en-php    
    if( $gru > 0 ) {
      $_ = []; $len = mb_strlen($val, $cod);
      for ( $i = 0; $i < $len; $i += $gru ) { $_[] = mb_substr($val, $i, $gru, $cod); }
      return $_;
    }
    return preg_split("//u", $val, -1, PREG_SPLIT_NO_EMPTY);
  }  
  // todo a minúsculas
  static function let_min( string $val, string $cod = "UTF-8" ) : string {

    return mb_strtolower($val, $cod);
  }
  // todo a mayúsculas
  static function let_may( string $val, string $cod = "UTF-8" ) : string {

    return mb_strtoupper($val, $cod);
  }
  // Capitalizar primer palabra
  static function let_pal( string $val, string $cod = "UTF-8" ) : string {
  
    return ucfirst( mb_strtolower($val, $cod) );
  }
  // capitalizar todas las palabras
  static function let_ora( string $val, string $cod = "UTF-8" ) : string {    

    return ucwords( mb_strtolower($val, $cod) );
  }

  // extraigo : de - el/a
  static function art( string $val ) : string {
    $_ = "";

    $pal = explode(' ',$val);

    if( in_array($pal[0],['de','del']) ) array_shift($pal);

    if( in_array($pal[0],['la','las','el','lo','los','ellos','ella','ellas']) ) array_shift($pal);

    if( isset($pal[0]) ) $_ = $pal[0];

    return $_;
  }
  // agrego : de - de la
  static function art_del( string $val ) : string {
    
    $_ = explode(' ',$val);

    $_[0] = ( strtolower($_[0]) == 'la' ) ? 'de la' : 'del';

    $_ = implode(' ',$_);

    return $_;
  }
  // convierto género : artículo - terminacion
  static function art_gen( string $val, string $pal = 'o' ) : string {
    $_ = trim($val);
    $pal = trim($pal);
    $art = explode(' ',$pal);
    // por articulo
    if( isset($art[1]) ){

      if( $art[0]=='de' ) array_shift($art);

      $pal = $art[0];

      if( ( in_array($pal,['el','del','lo','los','él','ellos']) ) && preg_match("/as?$/", $_) ){

        $_ = preg_match("/as$/", $_) ? preg_replace("/as$/","os",$_) : preg_replace("/a$/","o",$_);
      }
      elseif( ( in_array($pal,['la','las','ella','ellas']) ) && preg_match("/[oe]s?$/", $_) ){

        $_ = preg_match("/[oe]s$/", $_) ? preg_replace("/[oe]s$/","as",$_) : preg_replace("/o$/","a",$_);
      }
    }// por palabra
    elseif( preg_match("/[oe]s?$/",trim($pal)) && preg_match("/as?$/", $_) ){

      $_ = preg_match("/as$/", $_) ? preg_replace("/as$/","os",$_) : preg_replace("/a$/","o",$_);
    }
    elseif( preg_match("/os?$/",trim($pal)) && preg_match("/as?$/", $_) ){

      $_ = preg_match("/[oe]s$/", $_) ? preg_replace("/[oe]s$/","as",$_) : preg_replace("/o$/","a",$_);
    }
    return $_;
  }

  // controlador
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
    $_ = "";
    $_ide = self::$IDE."var";
    $_eje = self::$EJE."var";

    // valor
    if( $tip == 'val' ){
      $ope['eti'] = !empty($ope['eti']) ? $ope['eti'] : 'p';
      $ope['htm'] = tex::let( is_null($dat) ? '' : strval($dat) );
      $_ = ele::val($ope);
    }// por tipos
    else{

      if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? obj::val_cod($dat) : $dat );

      $ope['value'] = str_replace('"','\"',$dat);

      if( $tip == 'par' ){

        if( empty($ope['rows']) ) $ope['rows']="2";      
      }
      else{
        $ope['type']='text';
      }
      if( isset($ope['type']) ){
        $lis_htm = "";
        if( isset($ope['lis']) || isset($ope['dat']) ){
          if( isset($ope['lis']) ){
            $dat_lis = obj::val_dec($ope['lis']);
            unset($ope['lis']);          
          }else{
            $dat_lis = [];
          }        
          if( empty($ope['id']) ){ 
            $ope['id']="_tex-{$tip}-".doc::var_ide("_tex-{$tip}-");
          }
          $ope['list'] = "{$ope['id']}-lis";
          $lis_htm = "
          <datalist id='{$ope['list']}'>";
            foreach( $dat_lis as $pos => $ite ){ $lis_htm .= "
              <option data-ide='{$pos}' value='{$ite}'></option>";
            }$lis_htm .="
          </datalist>";
        }
        // seleccion automática
        ele::eje($ope,'foc',"this.select();",'ini');  
        $_ = "<input".ele::atr($ope).">".$lis_htm;
      }
      else{
        $_ = "<textarea".ele::atr($ope).">{$dat}</textarea>";
      }
    }      

    return $_;
  }
}