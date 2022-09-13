<?php

  // usuario : sesion + tránsitos  
  class _usu {

    public int $ide = 0;

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

        foreach( _dat::var("_api.usu", [ 'ver'=>"`ide`='{$ide}'", 'opc'=>'uni' ]) as $atr => $val ){

          $this->$atr = $val;
        }

        // calculo edad actual
        $this->eda = _fec::cue('eda',$this->fec);
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
          foreach( _hol::dat_ani( $_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

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

  // holon del sincronario
  class _usu_hol {

    // menu
    
    // ficha
    static function fic( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;      
      $_fec = _api::_('fec',$_usu->fec);
      $_kin = _hol::_('kin',$_usu->kin);
      $_psi = _hol::_('psi',$_usu->psi);

      $sum = $_kin->ide + $_psi->tzo;

      $_ = "
      <section class='inf ren esp-ara'>

        <div>

          <p class='let-tit let-3 mar_aba-1'>"._doc::let("$_usu->nom $_usu->ape")."</p>

          <p>"._doc::let($_fec->val." ( $_usu->eda años )")."</p>

        </div>        

        <div class='val'>

          "._doc::ima('hol','kin',$_kin,['class'=>"mar_hor-1"])."

          <c class='sep'>+</c>

          "._doc::ima('hol','psi',$_psi,['class'=>"mar_hor-1"])."

        </div>

      </section>";

      return $_;
    }
    // tránsitos
    static function cic( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;
      foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
      $opc = isset($ope['opc']) ? $ope['opc'] : $opc;
      $opc_des = !in_array('not-des',$opc);
      // operador
      $_ = "
      <form>
      </form>";
      // listado
      $_lis = [];
      foreach( _dat::var('_api.usu_cic') as $_arm ){
        $_lis_cic = [];
        foreach( _dat::var("_api.usu_cic_ani",[ 'ver'=>"`usu` = '{$_usu->ide}' AND `arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
          // ciclos lunares
          $_lis_lun = [];
          foreach( _dat::var("_api.usu_cic_lun",[ 'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
            $_fec = _api::_('fec',$_lun->fec);
            $_lun_ton = _hol::_('ton',$_lun->ide);
            $_kin = _hol::_('kin',$_lun->kin);
            $nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>"._doc::let($_lun->sin)."</a>";
            if( $opc_des ){
              $_lis_lun []= 
              _doc::ima('hol','kin',$_kin,['class'=>"tam-6 mar_der-1"])."
              <p>
                "._doc::let(intval($_lun_ton->ide)."° ciclo, ").$nav._doc::let(" ( $_fec->val ). $_lun_ton->ond_nom: $_lun_ton->ond_man")."
                <br>"._hol_des::kin('enc',$_kin)."
              </p>";
            }else{

            }
          }
          // ciclo anual
          $_fec = _api::_('fec',$_cic->fec);
          $_cas = _hol::_('cas',$_cic->ide);
          $_cas_ton = _hol::_('ton',$_cic->ton);
          $_cas_arm = _hol::_('cas_arm',$_cic->arm);            
          $_kin = _hol::_('kin',$_cic->kin);            
          $_lis_cic []= [
            'ite'=>[ 'eti'=>"div", 'class'=>"ite", 'htm'=> $opc_des ?
              _doc::ima('hol','kin',$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                "._doc::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ): $_cas_arm->nom $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>"._doc::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."
                <br>"._hol_des::kin('enc',$_kin)."
              </p>" 
              : ""
            ],
            'lis'=>$_lis_lun
          ];
        }
        $_lis []= [
          'ite'=>$_arm->nom,
          'lis'=>$_lis_cic
        ];
      }
      // configuro listado
      _ele::cla($ope['dep'],DIS_OCU);
      array_push($ope['opc'],'tog','ver','cue','tog_dep');
      $ope['lis-2']['class'] = "ite";
      return _doc_lis::val($_lis,$ope);
    }    
    // firma galáctica
    static function val( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;

      return $_;
    }
    // relaciones
    static function rel( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;

      return $_;
    }

  }