<?php

class Usu {

  public $ide;
  public $mai;
  public $pas;
  public $nom;
  public $ape;
  public $fec;
  public $eda;
  public $tel;
  public $ubi;

  public function __construct( int | string $ide = 0 ){
    
    if( empty($ide) ){
      $this->ide = 0;
      $this->nom = "Usuario";      
      $this->ape = "Público";
    }
    else{
      // cargo datos
      foreach( Dat::get('sis-usu_dat', [
        'ver'=>is_numeric($ide) ? "`ide`='{$ide}'" : "`ema`='{$ide}'", 
        'opc'=>'uni'
      ]) as $atr => $val ){

        $this->$atr = $val;
      }
      
      // calculo edad actual
      if( !empty($this->fec) ) $this->eda = Fec::dia_cue('eda',$this->fec);
    }
  }

  // Administrar perfil
  public function dat( string $tip ) : string {
    $_ = "";
    $esq = 'sis';
    $est = 'usu_dat';

    // imprimo formulario 
    switch( $tip ){
    case 'ver':
      $_ = "
      <form class='dat' data-esq='{$esq}' data-est='{$est}'>
  
        <fieldset class='-ren'>
          ".Doc_Ope::var('dat.atr', [$esq,$est,$atr='nom'], [ 'val'=>$this->$atr ], 'eti' )."
          ".Doc_Ope::var('dat.atr', [$esq,$est,$atr='ape'], [ 'val'=>$this->$atr ], 'eti' )."                              
        </fieldset>
  
        ".Doc_Ope::var('dat.atr', [$esq,$est,$atr='fec'], [ 'val'=>$this->$atr ] )."

        ".Doc_Ope::var('dat.atr', [$esq,$est,$atr='mai'], [ 'val'=>$this->$atr ] )."

        ".Doc_Ope::var('dat.atr', [$esq,$est,$atr='tel'], [ 'val'=>$this->$atr ] )."
  
      </form>";
      
      break;
    }

    return $_;

  }

}