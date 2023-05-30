<?php

if( !class_exists("Sincronario") ){

  require_once("./App/Sincronario.php");
}

class Usuario {

  public int $Eda;

  public string $Fec;

  public int $Kin;

  public int $Psi;  

  public function __construct(){

    global $Usu;

    $this->Fec = $Usu->fec;

    $this->Eda = $Usu->eda;

    $this->Kin = 105;

    $this->Psi = 47;

  }

  /* Ficha */
  public function fic( array $ope = [], ...$opc ) : string {
    global $Usu;
    $_ = "";
    $_fec = Fec::dat( $Usu->fec );
    $_kin = Dat::_('hol.kin', 105 ); // caclular kines del usuario
    $_psi = Dat::_('hol.psi', 47 ); // caclular kines del usuario
    // sumatoria : kin + psi
    $sum = $_kin->ide + $_psi->kin;

    // nombre + fecha : kin + psi
    $_ = "
    <section class='ope_inf -ren esp-ara'>

      <div>
        <p class='tex-tit tex-3 mar_aba-1'>".Doc_Val::let("$Usu->nom $Usu->ape")."</p>
        <p>".Doc_Val::let($_fec->val." ( $Usu->eda años )")."</p>
      </div>        

      <div class='-val'>
        ".Doc_Val::ima('hol',"kin",$_kin,['class'=>"mar_hor-1"])."
        <c>+</c>
        ".Doc_Val::ima('hol',"psi",$_psi,['class'=>"mar_hor-1"])."
        <c>=></c>
        ".Doc_Val::ima('hol',"kin",$sum,['class'=>"mar_hor-1"])."
      </div>

    </section>";

    return $_;
  }

  /* Transitos */
  public function cic( string $tip = NULL, mixed $val = NULL ) : string {
    global $Usu;
    $_ = "";
    if( empty($tip) ){
    }
    else{
      switch( $tip ){
      // genero tránsitos anuales > lunares
      case 'ani':
        // elimino previos
        $_ .= "DELETE FROM `sis-usu_cic_ani` WHERE usu = $Usu->ide;<br>";
        $_ .= "DELETE FROM `sis-usu_cic_lun` WHERE usu = $Usu->ide;<br>";
        // pido tránsitos : $Usu->sin
        foreach( Sincronario::val_cic( 0, 1, 52, 'not-lun') as $_cic_ani ){

          $_ .= "INSERT INTO `sis-usu_cic_ani` VALUES( 
            $Usu->ide, 
            $_cic_ani->ide, 
            $_cic_ani->eda, 
            $_cic_ani->arm, 
            $_cic_ani->ond, 
            $_cic_ani->ton, 
            '".Fec::val_var($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
          );<br>";

          if( empty($_cic_ani->lun) ) continue;

          foreach( $_cic_ani->lun as $_cic_lun ){

            $_ .= "INSERT INTO `sis-usu_cic_lun` VALUES( 
              $Usu->ide, 
              $_cic_lun->ani, 
              $_cic_lun->ide, 
              '".Fec::val_var($_cic_lun->fec,'dia')."', 
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
  // - calculo
  public function cic_dat( string $fec = '' ) : array {
    global $Usu;

    // valido fecha
    if( empty($fec) ) $fec = date( 'Y/m/d' );

    // cargo holon por fecha
    $_['hol'] = Sincronario::val( $fec );

    // busco anillo actual
    $_['ani'] = Dat::get('sis-usu_cic_ani',[ 
      'ver'=>"`usu`='{$Usu->ide}' AND `fec` <= '".Fec::val_var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
    ]);

    // busco transito lunar
    $_['lun'] = Dat::get('sis-usu_cic_lun',[ 
      'ver'=>"`usu`='{$Usu->ide}' AND `ani`={$_['ani']->ide} AND `fec` <= '".Fec::val_var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
    ]);

    // calculo diario
    $_['dia'] = new stdClass;
    $_['dia']->kin = Dat::_('hol.kin', intval($_['hol']['kin']) + intval( 0 ) ); // $Usu->kin

    return $_;
  }
  // - listado
  public function cic_nav( array $ope = [], ...$opc ) : string {
    global $Usu;
    $_ = "";
    foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
    $opc_des = !in_array('not-des',$opc);
    // descripciones
    $_kin_des = function( $kin ){
      $sel = Dat::_('hol.sel',$kin->arm_tra_dia);
      $ton = Dat::_('hol.ton',$kin->nav_ond_dia);
      return Doc_Val::let($kin->nom.": ")."<q>".Doc_Val::let("$ton->des ".Tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
    };
    // listado
    $_lis = [];
    foreach( Dat::get("hol-hum_cas_arm") as $_arm ){
      $_lis_cic = [];
      foreach( Dat::get('sis-usu_cic_ani',[ 'ver'=>"`usu`='{$Usu->ide}' AND `arm`=$_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
        // ciclos lunares
        $_lis_lun = [];
        foreach( Dat::get('sis-usu_cic_lun',[ 'ver'=>"`usu`='{$Usu->ide}' AND `ani`=$_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
          $_fec = Fec::dat($_lun->fec);
          $_lun_ton = Dat::_('hol.ton',$_lun->ide);
          $_kin = Dat::_('hol.kin',$_lun->kin);
          $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>".Doc_Val::let($_lun->sin)."</a>";
          $_lis_lun []= 
          "<div class='-ite'>
            ".Doc_Val::ima('hol',"kin",$_kin,['class'=>"tam-6 mar_der-1"])."
            <p>
              ".Doc_Val::let(intval($_lun_ton->ide)."° ciclo, ").$_nav.Doc_Val::let(" ( $_fec->val ).")."
              <br>".Doc_Val::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
            </p>              
          </div>
          <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
        }
        // ciclo anual
        $_fec = Fec::dat($_cic->fec);
        $_cas = Dat::_('hol.cas',$_cic->ide);
        $_cas_ton = Dat::_('hol.ton',$_cic->ton);
        $_cas_arm = Dat::_('hol.cas_arm',$_cic->arm);            
        $_kin = Dat::_('hol.kin',$_cic->kin);            
        $_lis_cic []= [
          'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
            "<div class='-ite'>
              ".Doc_Val::ima('hol',"kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                ".Doc_Val::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                <br>".Doc_Val::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>".Doc_Val::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
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
    Ele::cla($ope['dep'],DIS_OCU);
    $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog-dep' ];
    return Doc_Ope::lis('dep',$_lis,$ope);
  }
  // - informe
  public function cic_inf( array $ele = [], ...$opc ) : string {
    global $Usu;
    $dat = $this->cic_dat();
    $_ = "
    <section>
      ".$this->cic_inf_ani( $dat, $ele, ...$opc )."
      ".$this->cic_inf_lun( $dat, $ele, ...$opc )."
      ".$this->cic_inf_dia( $dat, $ele, ...$opc )."
    </section>"; 
    return $_;
  }
  // -> anual
  public function cic_inf_ani( array $dat, array $ele = [], ...$opc ) : string {
    global $Usu;
    $_ani = $dat['ani'];
    $_cas_arm = Dat::_('hol.cas_arm',$dat['ani']->arm);
    $_ani_arm = Dat::get("hol-hum_cas_arm",[ 'ver'=>"`ide`=$_ani->arm", 'opc'=>"uni"] );
    $_ani_fec = Fec::dat($_ani->fec);      
    $_ani_ton = Dat::_('hol.ton',$dat['ani']->ton);
    $_kin = Dat::_('hol.kin',$_ani->kin);
    $_ = "
    <h3>Tránsito Anual</h3>

    <p>".Doc_Val::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

    ".Doc_Var::num('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='-ite mar_ver-1'>
      ".Doc_Val::ima('hol',"cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p class='tex-tit'>".Doc_Val::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>".Doc_Var::num('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    ".Doc_Dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }
  // -> lunar
  public function cic_inf_lun( array $dat, array $ele = [], ...$opc ) : string {
    global $Usu;
    $_lun = $dat['lun'];
    $_lun_fec = Fec::dat($_lun->fec);
    $_lun_ton = Dat::_('hol.ton',$_lun->ide);
    $_kin = Dat::_('hol.kin',$_lun->kin);
    $_ = "
    <h3>Tránsito Lunar</h3>

    <p>".Doc_Val::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

    ".Doc_Var::num('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='-ite mar_ver-1'>
      ".Doc_Val::ima('hol',"ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    ".Doc_Dat::inf('hol','kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }
  // -> diario
  public function cic_inf_dia( array $dat, array $ele = [], ...$opc ) : string {
    global $Usu;
    $_dat = Sincronario::val( date('Y/m/d') );
    $_kin = Dat::_('hol.kin',$dat['dia']->kin);

    $_ = "
    <h3>Tránsito Diario</h3>

    ".Doc_Dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }

}