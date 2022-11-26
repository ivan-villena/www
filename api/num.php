<?php
// Numero : separador + operador + entero + decimal + rango
class num {

  static string $IDE = "num-";
  static string $EJE = "num.";

  function __construct(){
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_num;
    $est = "_$ide";
    if( !isset($api_num->$est) ) $api_num->$est = dat::est_ini(DAT_ESQ,"num{$est}");
    $_dat = $api_num->$est;
    
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

  // datos del objeto
  static function dat( int | float | string $ide, string $atr='' ) : object | string {
    $_num_int = num::_('int');
    // aseguro valor
    if( is_string($ide) ) $ide = num::val($ide);
    
    // busco datos
    $_ = isset($_num_int[$ide]) ? $_num_int[$ide] : new stdClass();
    
    // devuelvo atributo
    if( !empty($atr) ) $_ = isset($_->$atr) ? $_->$atr : "";

    return $_;
  }
  
  // devuelvo valor con "0-" o numérico : int | float
  static function val( mixed $dat, int $tot = 0 ) : string | int | float {
    $_ = $dat;
    if( !empty($tot) ){

      $_ = tex::val_agr( abs( $dat = num::val($dat) ), $tot, "0");

      if( $dat < 0 ) $_ = "-".$_;
    }
    // parse-int o parse-float
    elseif( is_string($dat) ){

      $_ = preg_match("/\d+,\d+$/",$dat) ? floatval($dat) : intval($dat);
    }
    return $_;
  }// sumatorias
  static function val_sum( int | float | string | array $val ) : int | float {

    if( !is_array($val) ) $val = explode(',', is_string($val) ? $val : strval($val) );

    return array_reduce( $val,
    
      function( $acu, $ite ){
        
        return $acu += num::val($ite); 
      }
    );
  }// redondeos
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
    else{ $_ = "
      <p>
        <font class='err'>Error de tipos</font><c>:</c>{$_}
        <br>
        <c class='sep'>-></c>{$ini}<c class='sep'>-></c>{$fin}
      </p>";
    }
    return $_;
  }

  // formato entero : ...mil.num
  static function int( string | float | int $dat, string $mil = '.' ) : string {
    
    return number_format( intval($dat), 0, ( $mil == '.' ) ? ',' : '.', $mil );
  }

  // formato decimal : ...num,dec
  static function dec( string | float | int $val, int $dec = 2, string $sep = ',' ) : string {
    
    return number_format( floatval($val), intval($dec), $sep, ( $sep == ',' ) ? '.' : ','  );
  }

  // reduzco a valor dentro del rango
  static function ran( string | float | int $num, int | float $max, int | float $min = 1 ) : int | float {
    
    $_ = is_string($num) ? num::val($num) : $num;
    
    while( $_ > $max ){ $_ -= $max; } 
    
    while( $_ < $min ){ $_ += $max; } 
    
    return $_;
  }  

  // controlador
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
    $_ = "";
    $_ide = self::$IDE."var";
    $_eje = self::$EJE."var";

    if( $tip == 'val' ){

      if( !isset($ope['htm']) ) $ope['htm'] = $dat;

      $_ = "<n".ele::atr($ope).">{$ope['htm']}</n>";
    }
    else{

      if( $tip != 'bit' ){

        if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;

        if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }

        if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
      }

      // controlo valores al actualizar
      ele::eje($ope,'inp',"$_eje"."_act(this);",'ini');
      // seleccion automática
      ele::eje($ope,'foc',"this.select();",'ini');        
      
      switch( $tip ){
      case 'bit':

        $ope['type']='text';  
        break;          
      case 'int':

        if( !isset($ope['step']) ) $ope['step']='1'; 

        if( !empty($ope['value']) ){

          if( !empty($ope['num_pad']) ){
            
            if( !empty($ope['maxlength']) ){ 
              $tam = $ope['maxlength']; 
            }
            elseif( !empty($ope['max']) ){ 
              $tam = count(explode('',$ope['max'])); 
            }
          }
          if( !empty($tam) ){ 
            $ope['value'] = num::val($ope['value'],$tam); 
          }
          if( !empty($ope['num_sep']) ){ 
            $ope['value'] = num::int($ope['value']); 
          }
        }
        break;
      case 'dec':
        if( !empty($ope['value']) ){

          $ope['value'] = floatval($ope['value']);

          if( !empty($ope['maxlength']) ){
            $tam = explode(',',$ope['maxlength']); 
            $int = $tam[0]; 
            $dec = isset($tam[1]) ? $tam[1] : 0;
          }
          else{

            if( !empty($ope['num_dec']) ){ 

              $dec = $ope['num_dec']; 
            }
            elseif( isset($ope['step']) ){ 
              $dec = explode('.',$ope['step']); 
              $dec = isset($dec[1]) ? strlen($dec[1]) : 0;
            }
            if( isset($ope['num']) ){ 

              $int = strlen($ope['num']); 
            }
            elseif( isset($ope['max']) ){ 

              $int = strlen($ope['max']); 
            }
          }
          $tam = intval($int) + 1;

          if( empty($dec) ) $dec = 2;

          $ope['num_dec'] = $dec;

          if( !isset($ope['step']) ) $ope['step'] = '0.'.num::val('1',$dec);

          if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = num::val($ope['value'],$tam);

          if( !empty($ope['num_sep']) ) $ope['value'] = num::dec($ope['value']);
        }
        break;
      case 'ran':
        $ope['type']='range'; 
        if( !isset($ope['step']) ) $ope['step']=1; 
        if( !isset($ope['min']) )  $ope['min']=0; 
        if( !isset($ope['max']) )  $ope['max']=$ope['step'];
        // armo bloques : min < --- val --- > max / output
        if( !in_array('ver',$opc) ){
          $cla = "";
          if( isset($ope['class']) ){ 
            $cla = "{$ope['class']}"; 
            unset($ope['class']); 
          }
          if( !isset($ope['id']) ){ 
            $ope['id'] = "_num_ran-".doc::var_ide('_num-ran');
          }
          $htm_out = "";
          if( !in_array('val-ocu',$opc) ){ $htm_out = "
            <output for='{$ope['id']}'>
              <n class='_val'>{$ope['value']}</n>
            </output>";
          }
          $_ = "
          <div class='num ran {$cla}'>
          
            <div class='val'>
              <n class='_min'>{$ope['min']}</n>
              <c class='sep'><</c>
              <input".ele::atr($ope).">
              <c class='sep'>></c>
              <n class='_max'>{$ope['max']}</n>
            </div>

            {$htm_out}

          </div>";
        }
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input".ele::atr($ope).">";
      }
    }        

    return $_;
  }
}