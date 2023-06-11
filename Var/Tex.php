<?php

// Texto : caracter + letra + oracion + parrafo

class Tex {

  /* Contenido */
  static function val(){
  }// - salvo caracteres con "\"
  static function val_cod( string $val, string $cod = ".*+\-?^{}()|[\]\$\\", string $opc = "" ) : string {
  
    return preg_replace("/[{$cod}]/{$opc}",$val,'\\$&');// $& significa toda la cadena coincidente

  }// - devuelvo longitud
  static function val_cue( string $val, string $cod = "UTF-8") : int {
  
    return mb_strlen($val, $cod);
  }// - extraigo subcadena
  static function val_ver( string $val, int $ini, int $fin, string $cod="UTF-8" ) : string {
  
    return mb_substr($val, $ini, $fin, $cod);
  }// - relleno por lado con valor repetitivo
  static function val_agr( int | float | string $val, int $cue = 1, string $rep = ' ', string $lad = 'izq' ) : string {
  
    return str_pad( $val, $cue, $rep, $lad == 'izq' ? STR_PAD_LEFT : STR_PAD_RIGHT );
  }// - modifico subcadena
  static function val_mod( string $dat, string $ver, string $val ) : string {

    return str_replace($ver,$val,$dat);
  }
  
  // Letras : Separador con acentos y caracteres especiales
  static function let( string $val, int $gru = 0, string $cod = "UTF-8" ) : array {
    // => https://diego.com.es/extraccion-de-strings-en-php    
    if( $gru > 0 ) {
      $_ = []; $len = mb_strlen($val, $cod);
      for ( $i = 0; $i < $len; $i += $gru ) { $_[] = mb_substr($val, $i, $gru, $cod); }
      return $_;
    }
    return preg_split("//u", $val, -1, PREG_SPLIT_NO_EMPTY);
  }// - Todo a minúsculas
  static function let_min( string $val, string $cod = "UTF-8" ) : string {

    return mb_strtolower($val, $cod);
  }// - Todo a mayúsculas
  static function let_may( string $val, string $cod = "UTF-8" ) : string {

    return mb_strtoupper($val, $cod);
  }// - Capitalizar primer palabra
  static function let_pal( string $val, string $cod = "UTF-8" ) : string {
  
    return ucfirst( mb_strtolower($val, $cod) );
    
  }// - Capitalizar todas las palabras
  static function let_ora( string $val, string $cod = "UTF-8" ) : string {    

    return ucwords( mb_strtolower($val, $cod) );
  }

  // Artículos : de - el/a
  static function art( string $val ) : string {
    $_ = "";

    $pal = explode(' ',$val);

    if( in_array($pal[0],['de','del']) ) array_shift($pal);

    if( in_array($pal[0],['la','las','el','lo','los','ellos','ella','ellas']) ) array_shift($pal);

    if( isset($pal[0]) ) $_ = $pal[0];

    return $_;
    
  }// - Agrego : de - de la
  static function art_del( string $val ) : string {
    
    $_ = explode(' ',$val);

    $_[0] = ( strtolower($_[0]) == 'la' ) ? 'de la' : 'del';

    $_ = implode(' ',$_);

    return $_;
  }// - Convierto género : artículo-terminacion
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

}