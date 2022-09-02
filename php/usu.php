<?php

  // usuario del sistema con sincronario
  class _usu {

    public object $_dat;

    public array $_cic;

    public function __construct( string $ide ){

      if( !empty($ide) ){
        // cargo datos
        $this->_dat = _dat::var("_usu.dat", [ 'ver'=>"`ide`='{$ide}'", 'opc'=>['uni'] ]);
        // calculo edad actual
        $this->_dat->eda = _fec::cue('eda',$this->_dat->fec);
      }
    }
    // datos
    public function dat( string $atr = NULL ) : mixed {

      if( empty($atr) ){

        return $this->_dat;
      }
      elseif( isset($this->_dat->$atr) ){

        return $this->_dat->$atr;
      }
    }
    // sesion
    public function ses( string $tip ) : mixed {

      $_;

      if( $tip == 'ini' ){

      }elseif( $tip == 'fin' ){

      }

      return $_;
    }    
    // transitos
    public function cic( string $tip = NULL, mixed $val = NULL ) : string {
      $_ = "";
      
      if( empty($tip) ){
      }
      else{
        switch( $tip ){
        // genero tránsitos anuales > lunares
        case 'ani':
          // elimino previos
          $_ .= "DELETE FROM `_usu`.`cic_ani` WHERE usu = $_usu->ide;<br>";
          $_ .= "DELETE FROM `_usu`.`cic_lun` WHERE usu = $_usu->ide;<br>";
          $_ .= "DELETE FROM `_usu`.`cic_dia` WHERE usu = $_usu->ide;<br>";

          // pido tránsitos
          foreach( _hol::dat_cic( $_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

            $_ .= "INSERT INTO `_usu`.`cic_ani` VALUES( 
              $_usu->ide, 
              $_cic_ani->ide, 
              $_cic_ani->eda, 
              $_cic_ani->arm, 
              $_cic_ani->ond, 
              $_cic_ani->ton, 
              '"._fec::var($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
            );<br>";

            if( empty($_cic_ani->lun) ) continue;

            foreach( $_cic_ani->lun as $_cic_lun ){

              $_ .= "INSERT INTO `_usu`.`cic_lun` VALUES( 
                $_usu->ide, 
                $_cic_lun->ani, 
                $_cic_lun->ide, 
                '"._fec::var($_cic_lun->fec,'dia')."', 
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
  }

  // datos personales
  class _usu_dat {

    static string $IDE = "_usu_dat-";
    static string $EJE = "_usu_dat.";

    // ficha : nombres + kines + transitos
    static function val( mixed $ope = NULL ) : string {
      global $_usu;
      $_dat = $_usu->_dat;
      $_ = "";

      $_ide = self::$IDE."val";
      $_eje = self::$EJE."val";
      
      if( !empty($_dat->ide) ){

        $esq = 'usu'; 
        $est = 'dat';

        $_kin = _hol::_('kin',$_dat->kin);
        $_psi = _hol::_('psi',$_dat->psi);
        
        $win = [
          'ico' => "ses_usu",
          'nom' => "Cuenta de Usuario",
          'htm' => "
          
          <form class='api_dat' data-ope='val' data-esq='{$esq}' data-est='{$est}'>

            <fieldset class='ren'>
  
              "._doc::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_dat->$atr  ], 'eti')."
  
              "._doc::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_dat->$atr  ], 'eti')."                        
            
            </fieldset>
  
            <fieldset class='ren'>
  
              "._doc::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$_dat->$atr  ],'eti')."
  
              "._doc::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_dat->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."            
  
              <div class='ite'>
  
                "._doc::ima('hol','kin',$_kin,['class'=>"mar_hor-1"])."

                <c class='sep'>+</c>
  
                "._doc::ima('hol','psi',$_psi,['class'=>"mar_hor-1"])."
  
                <c class='sep'>=</c>
  
                "._doc::ima('hol','kin',$_kin->ide + $_psi->tzo,['class'=>"mar_hor-1"])."          
  
              </div>
  
            </fieldset>
  
          </form>
          
          "._usu_cic_ani::nav()
        ];
  
        echo _doc_art::win('usu_dat',$win);
      }

      return $_;      
    }
  }

  // sesion y preferencias de la cuenta
  class _usu_ses {

    static function nav() : string {
      $_ = "";

      return $_;
    }

  }

  // transito anual
  class _usu_cic_ani {

    static string $IDE = "_usu_cic-";
    static string $EJE = "_usu_cic.";
    
    static function nav( array $ope = [] ) : string {
      foreach(['nav','lis'] as $eti ){ if( !isset($ope["ele_$eti"]) ) $ope["ele_$eti"] = []; }
      $opc = isset($ope['opc']) ? $ope['opc'] : [];

      $_ = "
      <nav>
        <p class='tit'>Tránsitos</p>";
        $_.="
        <form>
        </form>

        <ul class='lis'>";
        foreach( _dat::var('_usu.cic_ani_arm') as $_arm ){ 
          $_.="
          <li>";
            $_.="
            <ul class='lis'>";
            foreach( _dat::var("_usu.cic_ani",[ 'ver'=>"`arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
              $_.="
              <li>";
                $_.="
                <ul class='lis'>";
                foreach( _dat::var("_usu.cic_lun",[ 'ver'=>"`ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){
                  $_.="
                  <li>";
                  $_.="
                  </li>";
                }$_.="
                </ul>";
                $_.="
              </li>";
            }$_.="
            </ul>";
            $_.="
          <li>";
        }$_.="
        </ul>";

                

        $_ .= "
      </nav>";
      return $_;
    }
  }