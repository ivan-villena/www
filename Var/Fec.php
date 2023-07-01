<?php
// Fecha : aaaa-mm-dia hh:mm:ss utc
class Fec {

  // objeto fecha: { val, año, mes, dia, sem, hor, min, seg, ubi }
  static function dat( string | object $val = "", ...$opc ) : object {

    if( is_object($val) ){

      $_ = $val;

    }
    else{

      $_ = new stdClass();
      $_->val = "";// fecha
      $_->dia = 0;
      $_->mes = 0;
      $_->año = 0;
      $_->tie = "";// horario
      $_->hor = 0;
      $_->min = 0;
      $_->seg = 0;
      $_->ubi = "";// ubicacion
  
      if( empty($val) ) $val = date('Y/m/d');
  
      // separo valores
      $val = explode( preg_match("/T/i",$val) ? 'T' : ' ', $val );
      
      // proceso fecha
      if( isset($val[0]) ){
  
        $fec = explode( preg_match("/-/",$val[0]) ? '-' : '/', $val[0] );      
  
        if( isset($fec[2]) ){
          // mes
          $_->mes = intval($fec[1]);
          // año
          if( strlen($fec[0]) > 2 ){
            $_->año = intval($fec[0]);    
            $_->dia = intval($fec[2]);    
          }else{
            $_->año = intval($fec[2]);    
            $_->dia = intval($fec[0]);
          }  
          // valido fecha resultante
          if( $_->val = Fec::val($_,...$opc) ){
            // busco valor semanal
            $_->sem = Fec::sem($_);
            // proceso horario
            if( isset($val[1]) ){
              $hor = explode(':', $_->tie = $val[1]);
              // segundos
              if( isset($hor[2]) ) $_->seg = intval($hor[2]);
              // minutos
              if( isset($hor[1]) ) $_->min = intval($hor[1]);
              // horas
              $_->hor = intval($hor[0]);
            }        
          }
          // cargo sincronario
          // ...
        }
      } 
    }
    
    return $_;
  }// cargo datos de un ciclo
  static function dat_cic( string | object $pos, int $tot, array $ope = [] ) : object {

    $_ = new stdClass;

    if( !is_object($pos) ) $pos = Fec::dat($pos);
    
    // cantidad total del ciclo
    $_->tot = $tot;
    
    // posicion actual
    $_->pos = $pos->val;
    
    // fecha inicial
    $_->ini = 0;
    
    // fecha final
    $_->fin = 0;

    return $_;
  }
  
  // valido de fecha : "año/mes/dia" | "dia/mes/año"
  static function val( object $dat, ...$opc ) : bool | string {
    $_ = FALSE;
  
    $año = !empty($dat->año) ? $dat->año : 1900;
    $mes = !empty($dat->mes) ? $dat->mes : 1;
    $dia = !empty($dat->dia) ? $dat->dia : 1;

    if( Fec::val_dat($año, $mes, $dia) ){
      
      $_ = !in_array('año',$opc) ? Num::val($dia,2).'/'.Num::val($mes,2).'/'.Num::val($año,4) : Num::val($año,4).'/'.Num::val($mes,2).'/'.Num::val($dia,2);
    }

    return $_;
  }// valido datos
  static function val_dat( $año, $mes, $dia ) : bool | string {

    return checkdate($mes, $dia, $año);
  }// objeto: DateTime
  static function val_dec( int | string | object | array $dat = NULL ) : DateTime | string {
    $_ = $dat;
    if( empty($dat) ){
      $_ = new DateTime('NOW');
    }
    else{
      if( is_numeric($dat) && preg_match("/^\d+$/",strval($dat)) ){
        try{ 
          $_ = new DateTime( intval($dat) );
        }
        catch( Throwable $_err ){ 
          $_ .= "<p class='err'>{$_err}</p>";
        }
      }
      elseif( is_string($dat) ){ 
        try{ 
          $_ = Fec::dat($dat);
          $_ = !! $_ ? new DateTime( "{$_->año}-{$_->mes}-{$_->dia}" ) : new DateTime('NOW');
        }
        catch( Throwable $_err ){ 
          $_ .= "<p class='err'>{$_err}</p>"; 
        }
      }
      elseif( is_object($dat) ){

        if( get_class($dat)=='stdClass' ){
          $_ = new DateTime( "{$dat->año}-{$dat->mes}-{$dat->dia}" );
        }
        else{
          $_ = $dat;
        }
      }
      elseif( is_array($dat) ){
        $_ = new DateTime( "{$dat[0]}-{$dat[1]}-{$dat[2]}" );
      }
    }
    return $_;
  }// codifico fecha : [ año, mes, dia ]
  static function val_cod( string $val, string $sep = NULL ) : array {
    $_ = [];

    $val = explode(' ',str_replace('T','',$val))[0];

    if( empty($sep) ) $sep = preg_match("/-/",$val) ? '-' : ( preg_match("/\//",$val) ? '/' : '.' );

    $dat = array_map( function($v){ return intval($v); }, explode($sep,$val) );

    if( strlen( strval($dat[0]) ) == 4 || $dat[0] > 31 ){

      $_ = [ $dat[0], $dat[1], $dat[2] ];
    }
    else{        
      $_ = [ $dat[2], $dat[1], $dat[0] ];
    }
    return $_;
  }// operaciones numericas por tipos
  static function val_ope( string | object $dat, int $val = 0, string $ope = '+', string $tip = 'dia' ){
    
    $_ = $dat;
    
    if( is_object($dat) ){

      $_ = "{$dat->año}-{$dat->mes}-{$dat->dia}";
    }
    elseif( is_string($dat) ){

      $_ = str_replace('/','-',$dat);
    }

    $tie = '';
    switch( $tip ){
    case 'seg': $tie = 'second'; break;
    case 'min': $tie = 'minute'; break;
    case 'hor': $tie = 'hour';   break;
    case 'dia': $tie = 'day';    break;
    case 'sem': $tie = 'week';   break;
    case 'mes': $tie = 'month';  break;
    case 'año': $tie = 'year';   break;
    } 
    
    if( $val > 1 ) $tie .= "s";

    // strtotime devuelve un timestamp        
    $_ = date( 'd-m-Y', strtotime( $ope.strval($val)." $tie", strtotime($_) ) );
    
    return $_;
  }// formateo para input : "aaa-mm-ddThh:mm:ss"
  static function val_var( string $val = NULL, string $tip = 'dia' ) : string {
    $_ = "";

    if( !empty($val) ){

      if( ( $tip == 'dia' || $tip == 'dyh' ) ){

        $_fec = Fec::dat($val,'año');

        $val = $_fec->val;
      }

      $_ = str_replace(' ','T',str_replace('/','-',$val));
    }
    
    return $_;
  }

  // dia y hora
  static function dyh( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;

    return $_fec->format('Y/m/d H:i:s');

  }    

  // dia completo
  static function dia( string | object $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;

    return $_fec->format('Y/m/d');

  }// cantidad de dias entre dos fechas
  static function dia_cue( string | object $ini, string | object $fin ) : int {

    $ini = ( !is_object($ini) || get_class($ini)=='stdClass' ) ? Fec::val_dec($ini) : $ini;    

    $fin = ( !is_object($fin) || get_class($fin)=='stdClass' ) ? Fec::val_dec($fin) : $fin;

    $dif = $ini->diff($fin);

    return $dif->days;
  }

  // horario
  static function tie( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;
    
    return $_fec->format('H:i:s');
  }

  // hora
  static function hor( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;
    
    return $_fec->format('H');
  }

  // minuto
  static function min( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;
    
    return $_fec->format('i');
  } 
  
  // segundo
  static function seg( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;
    
    return $_fec->format('s');
  }   

  // dia semanal
  static function sem( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;

    return $_fec->format('w');
  }

  // mes
  static function mes( mixed $dat = "" ) : string {
  

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;

    return $_fec->format('Y/m');
  }// - dia del mes
  static function mes_dia( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;

    return $_fec->format('d');

  }// - cantidad de dias en un mes
  static function mes_dia_cue( string | object $dat ) : int {
    
    $_ = is_string($dat) ? Fec::dat($dat) : $dat;

    return cal_days_in_month( CAL_GREGORIAN, $_->mes, $_->año );
  }// - nombre del mes
  static function mes_nom( mixed $val ){

    $mes = $val;

    if( is_object($val) || ( is_string($val) && !is_numeric($val) ) ){
      
      $Fec = is_string($val) ? Fec::dat($val) : $val;
      $mes = $Fec->mes;
    }

    switch( intval($mes) ){
      case 1:  $mes = "Enero";      break;
      case 2:  $mes = "Febrero";    break;
      case 3:  $mes = "Marzo";      break;
      case 4:  $mes = "Abril";      break;
      case 5:  $mes = "Marzo";      break;
      case 6:  $mes = "Junio";      break;
      case 7:  $mes = "Julio";      break;
      case 8:  $mes = "Agosto";     break;
      case 9:  $mes = "Septiembre"; break;
      case 10: $mes = "Octubre";    break;
      case 11: $mes = "Noviembre";  break;
      case 12: $mes = "Diciembre";  break;
    }

    return $mes;
  }

  // año
  static function año( mixed $dat = "" ) : string {

    $_fec = ( !is_object($dat) || get_class($dat)=='stdClass' ) ? Fec::val_dec($dat) : $dat;

    return $_fec->format('Y');
    
  }// - año bisciesto ?
  static function año_bis( string | object $fec ) : bool {

    if( is_string($fec) ) $fec = Fec::dat($fec);

    return date('L', strtotime("$fec->año-01-01"));

  }// - defino valor por rangos : AC - DC
  static function año_ran( int $ini, int $fin ) : string {

    $_ = "";

    if( $ini < 0 && $fin < 0  ){

      $_ = Num::int( $ini * - 1 )." - ".Num::int( $fin * - 1). " A.C.";
    }
    elseif( $ini > 0 && $fin > 0 ){

      $_ = Num::int( $ini )." - ".Num::int( $fin ). " D.C.";
    }
    else{
      $_ = Num::int( $ini * - 1 )." A.C. - ".Num::int( $fin ). " D.C.";
    }

    return $_;
  }// - cantidad de años: edad
  static function año_cue( string | object $fin, string | object $ini = "" ) : int {

    // cargo año final: el año buscado
    $fin = is_string($fin) ? Fec::dat($fin) : $fin;

    // cargo año inicial: por defecto es hoy
    $ini = is_string($ini) ? Fec::dat($ini) : $ini;

    $ano_dif = $ini->año - $fin->año;
    $mes_dif = $ini->mes - $fin->mes;
    $dia_dif = $ini->dia - $fin->dia;
    
    if( $dia_dif < 0 || $mes_dif < 0 ) $ano_dif--;

    return $ano_dif;

  }// - cuento dias del año
  static function año_dia_cue( string | object $dat = "" ) : array {

    if( empty($dat) ) $dat = Fec::val_dec();

    $_ = is_string($dat) ? Fec::dat($dat) : $dat;
    
    $lis = [];
    $tot = 0; 
    $num = 0; 
    $mes = 0;
    
    for( $i = 1; $i <= 12; $i++ ){

      $lis[$i] = Fec::mes_dia_cue("{$_->año}/$i/1");
      $tot += $lis[$i]; 
    }

    if( $_->mes == 1 ){ 
      $num = $_->dia;
    }
    else{
      $mes++;
      while( $mes < $_->mes ){ 
        $num += $lis[$mes]; 
        $mes++; 
      }
      $num += $_->dia;
    }

    return [ 
      'tex'=>"{$num} de {$tot}", 
      'val'=>$num, 
      'cue'=>$tot
    ];    
  }
}