<?php

class sis_usu {

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
      foreach( api_dat::get('usu_dat', ['ver'=>is_numeric($ide) ? "`ide`='{$ide}'" : "`ema`='{$ide}'", 'opc'=>'uni']) as $atr => $val ){
        $this->$atr = $val;
      }
      // calculo edad actual
      if( !empty($this->fec) ) $this->eda = api_fec::val_cue('eda',$this->fec);      
    }
  }

  /* Sesión */
  public function ses( string $tip ) : mixed {
    $_ = "";

    switch( $tip ){
    // inicio sesión
    case 'ini':
      // valido usuario
      if( isset($_REQUEST['ema']) && isset($_REQUEST['pas']) ){
        
        $sis_usu = new sis_usu( $_REQUEST['ema'] );

        if( isset($sis_usu->pas) ){
          if( $sis_usu->pas == $_REQUEST['pas'] ){
            $_SESSION['usu'] = $_REQUEST['ide'];
          }
          else{
            $_ = "Password Incorrecto";
          }
        }else{
          $_ = "Usuario Inexistente";
        }
      }
      break;
    // finalizo sesión
    case 'fin':
      $_SESSION['usu'] = 0;
      break;
    }

    return $_;
  }// - inicio
  public function ses_ini() : string {
    $_ = "
    <form class='app_dat' onsubmit='sis_usu.ses_ini'>

      <fieldset class='dat_var'>
        <input id='usu-ses_ini-mai' name='mai' type='email' placeholder='Ingresa tu Email...'>
      </fieldset>

      <fieldset class='dat_var'>
        <input id='usu-ses_ini-pas' name='pas' type='password' placeholder='Ingresa tu Password...'>
      </fieldset>

      <fieldset class='dat_var'>
        <label>Mantener Sesión Activa en este Equipo:</label>
        <input id='usu-ses_ini-val' name='val' type='checkbox'>
      </fieldset>

      <fieldset class='doc_ope'>
        <button type='submit'></button>
      </fieldset>

    </form>";
    return $_;
  }  
  public function ses_ope() : string {
    $_ = "
    <nav class='lis'>
      <a href='' target='_blank'>Datos</a>
      <a href='' target='_blank'>Tránsitos</a>
    </nav>

    <nav class='app_ope'>
      ".api_fig::ico('ses_fin',[ 'title'=>"Cerrar Sesión..." ])."
    <nav>";

    return $_;
  }

}