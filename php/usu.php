<?php
  // usuario : sesion + tránsitos  
  class _usu {

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
        foreach( _dat::get('usu', [ 'ver'=>"`ide`='{$ide}'", 'opc'=>'uni' ]) as $atr => $val ){

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
          foreach( _hol::cic( $_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

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
    // calculo tránsito actual
    static function cic_dat( string $fec = '' ) : array {
      global $_usu;

      // valido fecha
      if( empty($fec) ) $fec = date( 'Y/m/d' );

      // cargo holon por fecha
      $_['hol'] = _hol::val( $fec );

      // busco anillo actual
      $_['ani'] = _dat::get('usu_cic_ani',[ 
        'ver'=>"`usu` = '{$_usu->ide}' AND `fec` <= '"._fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
      ]);

      // busco transito lunar
      $_['lun'] = _dat::get('usu_cic_lun',[ 
        'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = {$_['ani']->ide} AND `fec` <= '"._fec::var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
      ]);

      // calculo diario
      $_['dia'] = new stdClass;
      $_['dia']->kin = _hol::_('kin', intval($_['hol']['kin']) + intval($_usu->kin) );

      return $_;
    }
  }

  // Transitos del sincronario
  class _usu_cic {

    // listado
    static function nav( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;
      foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
      $opc_des = !in_array('not-des',$opc);
      // descripciones
      $_kin_des = function( $kin ){
        $sel = _hol::_('sel',$kin->arm_tra_dia);
        $ton = _hol::_('ton',$kin->nav_ond_dia);
        return _app::let($kin->nom.": ")."<q>"._app::let("$ton->des "._tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
      };
      // listado
      $_lis = [];
      foreach( _dat::get("usu_cic") as $_arm ){
        $_lis_cic = [];
        foreach( _dat::get('usu_cic_ani',[ 'ver'=>"`usu` = '{$_usu->ide}' AND `arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
          // ciclos lunares
          $_lis_lun = [];
          foreach( _dat::get('usu_cic_lun',[ 'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
            $_fec = _api::_('fec',$_lun->fec);
            $_lun_ton = _hol::_('ton',$_lun->ide);
            $_kin = _hol::_('kin',$_lun->kin);
            $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>"._app::let($_lun->sin)."</a>";
            $_lis_lun []= 
            "<div class='ite'>
              "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p>
                "._app::let(intval($_lun_ton->ide)."° ciclo, ").$_nav._app::let(" ( $_fec->val ).")."
                <br>"._app::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
              </p>              
            </div>
            <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
          }
          // ciclo anual
          $_fec = _api::_('fec',$_cic->fec);
          $_cas = _hol::_('cas',$_cic->ide);
          $_cas_ton = _hol::_('ton',$_cic->ton);
          $_cas_arm = _hol::_('cas_arm',$_cic->arm);            
          $_kin = _hol::_('kin',$_cic->kin);            
          $_lis_cic []= [
            'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
              "<div class='ite'>
                "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
                <p title = '$_cas->des'>
                  "._app::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                  <br>"._app::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                  <br>"._app::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
                </p>
              </div>
              <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>"
            ],
            'lis'=>$_lis_lun
          ];
        }
        $_lis []= [ 'ite'=>$_arm->nom, 'lis'=>$_lis_cic ];
      }
      // configuro listado
      _ele::cla($ope['dep'],DIS_OCU);
      $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog_dep' ];
      return _app_lis::val($_lis,$ope);
    }

    // informe
    static function inf( array $ele = [], ...$opc ) : string {
      $dat = _usu::cic_dat();
      $_ = "
      <section>
        "._usu_cic::inf_ani( $dat, $ele, ...$opc )."
        "._usu_cic::inf_lun( $dat, $ele, ...$opc )."
        "._usu_cic::inf_dia( $dat, $ele, ...$opc )."
      </section>"; 
      return $_;
    }// - anual
    static function inf_ani( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;      
      $_ani = $dat['ani'];
      $_cas_arm = _hol::_('cas_arm',$dat['ani']->arm);
      $_ani_arm = _dat::get("usu_cic",['ver'=>"`ide` = $_ani->arm",'opc'=>"uni"]);
      $_ani_fec = _api::_('fec',$_ani->fec);      
      $_ani_ton = _hol::_('ton',$dat['ani']->ton);
      $_kin = _hol::_('kin',$_ani->kin);
      $_ = "
      <h3>Tránsito Anual</h3>

      <p>"._app::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

      "._app_num::ope('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

      <div class='ite mar_ver-1'>
        "._hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
        <div class='let-3'>
          <p class='let-tit'>"._app::let($_ani_arm->nom)."</p>
          <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
          <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
          <p>"._app_num::ope('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
        </div>
      </div>

      "._hol_fic::kin('enc',$_kin,[ 'ima'=>[] ])."

      ";
      return $_;
    }// - lunar
    static function inf_lun( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;
      $_lun = $dat['lun'];
      $_lun_fec = _api::_('fec',$_lun->fec);
      $_lun_ton = _hol::_('ton',$_lun->ide);
      $_kin = _hol::_('kin',$_lun->kin);
      $_ = "
      <h3>Tránsito Lunar</h3>

      <p>"._app::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

      "._app_num::ope('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

      <div class='ite mar_ver-1'>
        "._hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
        <div class='let-3'>
          <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
        </div>
      </div>


      "._hol_fic::kin('enc', $_kin, [ 'atr'=>[] ])."

      ";
      return $_;
    }// - diario
    static function inf_dia( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;
      $_dat = _hol::val( date('Y/m/d') );
      $_kin = _hol::_('kin',$dat['dia']->kin);

      $_ = "
      <h3>Tránsito Diario</h3>

      "._hol_fic::kin('enc',$_kin,[ 'ima'=>[] ])."

      ";
      return $_;
    }
  }