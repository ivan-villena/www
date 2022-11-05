<?php
// Usuario
class _hol_usu {

  // ficha
  static function fic( array $ope = [], ...$opc ) : string {
    $_ = "";
    global $_usu;      
    $_fec = _api::_('fec',$_usu->fec);
    $_kin = _hol::_('kin',$_usu->kin);
    $_psi = _hol::_('psi',$_usu->psi);
    // sumatoria : kin + psi
    $sum = $_kin->ide + $_psi->tzo;

    // nombre + fecha : kin + psi
    $_ = "
    <section class='inf ren esp-ara'>

      <div>
        <p class='let-tit let-3 mar_aba-1'>"._app::let("$_usu->nom $_usu->ape")."</p>
        <p>"._app::let($_fec->val." ( $_usu->eda años )")."</p>
      </div>        

      <div class='val'>
        "._hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
        <c>+</c>
        "._hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
        <c>=></c>
        "._hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
      </div>

    </section>";

    return $_;
  }    
  
  // ciclos: listado
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

  // ciclos: informe
  static function inf( array $ele = [], ...$opc ) : string {
    $dat = _usu::cic_dat();
    $_ = "
    <section>
      "._hol_usu::inf_ani( $dat, $ele, ...$opc )."
      "._hol_usu::inf_lun( $dat, $ele, ...$opc )."
      "._hol_usu::inf_dia( $dat, $ele, ...$opc )."
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

    "._app_var::num('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='ite mar_ver-1'>
      "._hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='let-3'>
        <p class='let-tit'>"._app::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>"._app_var::num('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    "._app_dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

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

    "._app_var::num('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='ite mar_ver-1'>
      "._hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='let-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    "._app_dat::inf('hol','kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }// - diario
  static function inf_dia( array $dat, array $ele = [], ...$opc ) : string {
    global $_usu;
    $_dat = _hol::val( date('Y/m/d') );
    $_kin = _hol::_('kin',$dat['dia']->kin);

    $_ = "
    <h3>Tránsito Diario</h3>

    "._app_dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }
}