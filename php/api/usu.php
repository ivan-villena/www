<?php
// usuario : sesion + tránsitos  
class api_usu {

  public int    $ide = 0;
  public string $pas = "";
  public string $nom = "Usuario";
  public string $ape = "Público";
  public string $eda = "";
  public string $fec = "";
  public string $sin = "";
  public string $kin = "";    
  public string $psi = "";
  public string $mai = "";
  public string $tel = "";
  public string $ubi = "";

  public function __construct( int $ide = NULL ){

    if( !empty($ide) ){
      // cargo datos
      foreach( api::dat('usu', [ 'ver'=>"`ide`='{$ide}'", 'opc'=>'uni' ]) as $atr => $val ){

        $this->$atr = $val;
      }
      // calculo edad actual
      $this->eda = api_fec::cue('eda',$this->fec);
    }      
  }

  // genero transitos
  static function cic_act( string $tip = NULL, mixed $val = NULL ) : string {
    global $_usu;
    $_ = "";
    if( empty($tip) ){
    }
    else{
      switch( $tip ){
      // genero tránsitos anuales > lunares
      case 'ani':          
        // elimino previos
        $_ .= "DELETE FROM `_api`.`usu_cic_ani` WHERE usu = $_usu->ide;<br>";
        $_ .= "DELETE FROM `_api`.`usu_cic_lun` WHERE usu = $_usu->ide;<br>";

        // pido tránsitos
        foreach( api_hol::cic( $_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

          $_ .= "INSERT INTO `_usu`.`cic_ani` VALUES( 
            $_usu->ide, 
            $_cic_ani->ide, 
            $_cic_ani->eda, 
            $_cic_ani->arm, 
            $_cic_ani->ond, 
            $_cic_ani->ton, 
            '".api_fec::var($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
          );<br>";

          if( empty($_cic_ani->lun) ) continue;

          foreach( $_cic_ani->lun as $_cic_lun ){

            $_ .= "INSERT INTO `_usu`.`cic_lun` VALUES( 
              $_usu->ide, 
              $_cic_lun->ani, 
              $_cic_lun->ide, 
              '".api_fec::var($_cic_lun->fec,'dia')."', 
              '$_cic_lun->sin', $_cic_lun->kin 
            );<br>";
          }
        }

        break;
      // genero transito diario por ciclo lunar
      case 'dia':
        
        break;
      }
    }
    return $_;
  }
  // calculo tránsito actual
  static function cic_dat( string $fec = '' ) : array {
    global $_usu;

    // valido fecha
    if( empty($fec) ) $fec = date( 'Y/m/d' );

    // cargo holon por fecha
    $_['hol'] = api_hol::val( $fec );

    // busco anillo actual
    $_['ani'] = api::dat('usu_cic_ani',[ 
      'ver'=>"`usu` = '{$_usu->ide}' AND `fec` <= '".api_fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
    ]);

    // busco transito lunar
    $_['lun'] = api::dat('usu_cic_lun',[ 
      'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = {$_['ani']->ide} AND `fec` <= '".api_fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
    ]);

    // calculo diario
    $_['dia'] = new stdClass;
    $_['dia']->kin = api_hol::_('kin', intval($_['hol']['kin']) + intval($_usu->kin) );

    return $_;
  }
}