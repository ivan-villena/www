<?php

class Usu {

  public $ide;
  public $mai;
  public $pas;
  public $nom;
  public $ape;
  public $fec;
  public $eda;
  public $sin;
  public $kin;
  public $psi;
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
      foreach( Dat::get('usu', ['ver'=>is_numeric($ide) ? "`ide`='{$ide}'" : "`ema`='{$ide}'", 'opc'=>'uni']) as $atr => $val ){
        $this->$atr = $val;
      }
      // calculo edad actual
      if( !empty($this->fec) ) $this->eda = Fec::val_cue('eda',$this->fec);      
    }
  }

  

}