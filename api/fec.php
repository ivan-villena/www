<?php
// Fecha : aaaa-mm-dia hh:mm:ss utc
class fec {
  
  static string $IDE = "fec-";
  static string $EJE = "fec.";

  function __construct(){
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];
    global $api_fec;
    $est = "_$ide";
    if( !isset($api_fec->$est) ) $api_fec->$est = dat::est_ini(DAT_ESQ,"fec{$est}");
    $_dat = $api_fec->$est;
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        case 'dat':
          $_ = fec::dat($val);
          break;
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
  
  // validor de fecha : "año/mes/dia" | "dia/mes/año"
  static function val( object $dat, ...$opc ) : bool | string {
    $_ = FALSE;
  
    $año = !empty($dat->año) ? $dat->año : 1900;
    $mes = !empty($dat->mes) ? $dat->mes : 1;
    $dia = !empty($dat->dia) ? $dat->dia : 1;

    if( checkdate($mes, $dia, $año) ){
      
      $_ = !in_array('año',$opc) ? num::val($dia,2).'/'.num::val($mes,2).'/'.num::val($año,4) : num::val($año,4).'/'.num::val($mes,2).'/'.num::val($dia,2);
    }

    return $_;
  }// codifico fecha : [ año, mes, dia ]
  static function val_cod( string $val, string $sep = NULL ) : array {
    $_ = [];

    $val = explode(' ',str_replace('T','',$val))[0];

    if( empty($sep) ){
      $sep = preg_match("/-/",$val) ? '-' : ( preg_match("/\//",$val) ? '/' : '.' );
    }

    $dat = array_map( function($v){ return intval($v); }, explode($sep,$val) );

    if( strlen( strval($dat[0]) ) == 4 || $dat[0] > 31 ){

      $_ = [ $dat[0], $dat[1], $dat[2] ];
    }
    else{        
      $_ = [ $dat[2], $dat[1], $dat[0] ];
    }
    return $_;
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
          $_ = "<p class='err'>{$_err}</p>"; 
        }
      }
      elseif( is_string($dat) ){ 
        try{ 
          $_ = fec::dat($dat);
          $_ = !! $_ ? new DateTime( "{$_->año}-{$_->mes}-{$_->dia}" ) : new DateTime('NOW');
        }
        catch( Throwable $_err ){ 
          $_ = "<p class='err'>{$_err}</p>"; 
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
  }// devuelvo por tipo
  static function val_tip( mixed $dat, $tip = NULL ) : bool | string {
    $_ = FALSE;

    if( empty($tip) ){
      $_ = $dat;
      if( is_string($dat) ) $_ = fec::dat($dat);
    }
    else{
      $_fec = $dat;
      // aseguro objeto nativo
      if( !is_object($dat) || get_class($dat)=='stdClass' ) $_fec = fec::val_dec($dat); 
      // busco tipo
      switch( $tip ){
      case 'dyh': $_ = $_fec->format('Y/m/d H:i:s');  break;
      case 'hor': $_ = $_fec->format('H:i:s');        break;
      case 'sem': $_ = $_fec->format('w');            break;
      case 'dia': $_ = $_fec->format('Y/m/d');        break;
      }
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

    if( !!$_ ){
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
    }
    return $_;
  }// cuento dias pos periodo : mes | año
  static function val_cue( string $tip, string | object $val ) : mixed {

    $_ = is_string($val) ? fec::dat($val) : $val;

    switch( $tip ){
    case 'mes':
      if( !!$_ ){
        $_ = cal_days_in_month( CAL_GREGORIAN, $_->mes, $_->año );
      }
      break;
    case 'año':
      $lis = [];
      $tot = 0; 
      $num = 0; 
      $mes = 0;
      for( $i = 1; $i <= 12; $i++ ){

        $lis[$i] = fec::val_cue('mes',"{$_->año}/$i/1");
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
      $_=[ 
        'tex'=>"{$num} de {$tot}", 
        'val'=>$num, 
        'cue'=>$tot
      ];
      break;    
    case 'eda':
      $ano_dif = date("Y") - $_->año;
      $mes_dif = date("m") - $_->mes;
      $dia_dif = date("d") - $_->dia;
      if( $dia_dif < 0 || $mes_dif < 0 ) $ano_dif--;
      $_ = $ano_dif;
      break;
    }
    return $_;
  }// defino valor por rangos : AC - DC
  static function val_ran( int $ini, int $fin ) : string {

    $_ = "";

    if( $ini < 0 && $fin < 0  ){

      $_ = num::int( $ini * - 1 )." - ".num::int( $fin * - 1). " A.C.";
    }
    elseif( $ini > 0 && $fin > 0 ){

      $_ = num::int( $ini )." - ".num::int( $fin ). " D.C.";
    }
    else{
      $_ = num::int( $ini * - 1 )." A.C. - ".num::int( $fin ). " D.C.";
    }

    return $_;
  }// año bisciesto ?
  static function val_año_bis( string | object $fec ) : bool {

    if( is_string($fec) ) $fec = fec::dat($fec);

    return date('L', strtotime("$fec->año-01-01"));
  }
  
  // objeto fecha: { val, año, mes, dia, sem, hor, min, seg, ubi }
  static function dat( string $val, ...$opc ) : object {

    $_ = new stdClass();
    $_->val = "";
    $_->dia = 0;
    $_->mes = 0;
    $_->año = 0;
    $_->tie = "";
    $_->hor = 0;
    $_->min = 0;
    $_->seg = 0;
    $_->ubi = "";

    // separo valores
    $val = explode( preg_match("/T/i",$val) ? 'T' : ' ', $val );

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
        if( $_->val = fec::val($_,...$opc) ){
          // busco valor semanal
          $_->sem = fec::val_tip($_,'sem');
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
      }
    }    
    return $_;
  }

  // controladores
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."var_{$tip}";
    $_eje = self::$EJE."var_{$tip}";  

    switch( $tip ){
    case 'val':
      $ope['value'] = $dat; $_ = "
      <time".ele::atr($ope).">
        ".tex::let(fec::var_val($dat))."
      </time>";
    case 'tie': 
      $ope['value'] = intval($dat);
      $ope['type']='numeric';
      break;
    case 'dyh': 
      $ope['value'] = fec::var_val($dat,$tip);
      $ope['type']='datetime-local';
      break;
    case 'hor':
      $ope['value'] = fec::var_val($dat,$tip);
      $ope['type']='time';
      break;
    case 'dia':
      $ope['value'] = fec::var_val($dat,$tip);
      $ope['type']='date';
      break;
    case 'sem':
      $ope['value'] = intval($dat);
      $ope['type']='week';
      break;
    case 'mes':
      $ope['value'] = intval($dat);
      $ope['type']='number';
      break;
    case 'año': 
      $ope['value'] = intval($dat);
      $ope['type']='number';
      break;
    }

    if( empty($_) && !empty($ope['type']) ){
      // seleccion automática
      ele::eje($ope,'foc',"this.select();",'ini');
      $_ = "<input".ele::atr($ope).">";
    }      

    return $_;
  }// formateo para input : "aaa-mm-ddThh:mm:ss"
  static function var_val( string $val = NULL, string $tip = 'dia' ) : string {
    $_ = "";

    if( !empty($val) ){

      if( ( $tip == 'dia' || $tip == 'dyh' ) ){

        $_fec = fec::dat($val,'año');

        $val = $_fec->val;
      }

      $_ = str_replace(' ','T',str_replace('/','-',$val));
    }
    
    return $_;
  }

}