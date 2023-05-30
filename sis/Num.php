<?php
// Numero : separador + operador + entero + decimal + rango
class Num {
  
  /* Contenido */    
  static function val( mixed $dat, int $tot = 0 ) : string | int | float {
    // devuelvo valor con "00..." o numérico : int | float
    $_ = $dat;

    if( !empty($tot) ){

      $_ = Tex::val_agr( abs( $dat = Num::val($dat) ), $tot, "0");

      if( $dat < 0 ) $_ = "-".$_;
    }
    // parse-int o parse-float
    elseif( is_string($dat) ){

      $_ = preg_match("/\d+,\d+$/",$dat) ? floatval($dat) : intval($dat);
    }

    return $_;
  }// - sumatorias
  static function val_sum( int | float | string | array $val ) : int | float {

    if( !is_array($val) ) $val = explode(',', is_string($val) ? $val : strval($val) );

    return array_reduce( $val,
    
      function( $acu, $ite ){
        
        return $acu += Num::val($ite); 
      }
    );
  }// - redondeos
  static function val_red( mixed $val, int $dec = 0, string $tip = 'min' ) : mixed {
    // https://www.php.net/manual/es/function.round.php
    return round($val, $dec, $tip == 'min' ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP );
  }

  // formato de bits
  static function bit( $dat, $ini='KB', $fin='MB' ) : string {
    $_ = floatval($dat); 
    $ini = strtoupper($ini); 
    $fin = strtoupper($fin);
    $_bit = [
      'B' =>1, 
      'KB'=>1024, 
      'MB'=>1048576, 
      'GB'=>1073741824, 
      'TB'=>1099511627776, 
      'PB'=>1125899906842624, 
      'EB'=>1152921504606846976, 
      'ZB'=>1180591620717411303424, 
      'YB'=>1208925819614629174706176
    ];
    if( is_numeric($_) && isset($_bit[$ini]) && isset($_bit[$fin]) ){      
      $_ = $_ * $_bit[$ini];// normalizo a bytes : <-
      $_ = $_ / $_bit[$fin];// convierto a valor final : ->
      if( is_numeric($_) && $_ >= 0 ){ 
        $_ = "<n class='bit'>".number_format($_,2,',','.')."</n><font class='ide'>{$fin}</font>";
      }else{ $_ = "
        <p><font class='err'>Error de conversión</font><c>:</c> {$_}</p>";
      }
    }// error de conversion
    else{ $_ = "<span title='Error de tipos: $_ -> $ini - $fin'></span>";
    }

    return $_;
  }

  /* Entero */
  // devuelvo entero : ...mil.num
  static function int( string | float | int $dat, string $mil = '.' ) : string {
    
    return number_format( intval($dat), 0, ( $mil == '.' ) ? ',' : '.', $mil );

  }// datos del
  static function int_dat( int | float | string $ide, string $atr='' ) : object | string {
    
    // busco datos
    $num_int = Dat::_("sis.num_int");
    
    // aseguro valor
    if( is_string($ide) ) $ide = Num::val($ide);
    
    // busco datos
    $_ = isset($num_int[$ide]) ? $num_int[$ide] : new stdClass();
    
    // devuelvo atributo
    if( !empty($atr) ) $_ = isset($_->$atr) ? $_->$atr : "";

    return $_;
  }

  // devuelvo decimal : ...num,dec
  static function dec( string | float | int $val, int $dec = 2, string $sep = ',' ) : string {
    
    return number_format( floatval($val), intval($dec), $sep, ( $sep == ',' ) ? '.' : ','  );

  }

  // reduzco a valor dentro del rango
  static function ran( string | float | int $num, int | float $max, int | float $min = 1 ) : int | float {
    
    $_ = is_string($num) ? Num::val($num) : $num;
    
    while( $_ > $max ){ $_ -= $max; } 
    
    while( $_ < $min ){ $_ += $max; } 
    
    return $_;
  }
}