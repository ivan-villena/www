<?php

class api_usu {

  public function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = api_app::est('usu',$ide,'dat');
    
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
    }
    
    return $_;
  }

  // Datos
  static function dat( int | string $ide = 0 ) : object {
    $_ = new stdClass;
    if( empty($ide) ){
      $_->ide = 0;
      $_->nom = "Usuario Público";      
    }
    else{
      // cargo datos
      foreach( api_app::dat('usu_dat', [ 
        'ver'=>is_numeric($ide) ? "`ide`='{$ide}'" : "`ema`='{$ide}'", 'opc'=>'uni' 
      ]) as $atr => $val ){
        $_->$atr = $val;
      }
      // calculo edad actual
      if( !empty($_->fec) ) $_->eda = api_fec::val_cue('eda',$_->fec);      
    }
    return $_;
  }// Informe: nuevo / editar
  static function dat_inf() : string {
    global $sis_usu;
    $esq = 'usu';
    $est = 'dat';
    $_kin = api_hol::_('kin',$sis_usu->kin);
    $_psi = api_hol::_('psi',$sis_usu->psi);
    $_ = "
    <form class='dat' data-esq='{$esq}' data-est='{$est}'>

      <fieldset class='doc_ren'>
        ".api_dat::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$sis_usu->$atr  ], 'eti')."
        ".api_dat::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$sis_usu->$atr  ], 'eti')."                              
      </fieldset>

      <fieldset class='doc_ren'>
        ".api_dat::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$sis_usu->$atr  ],'eti')."
        ".api_dat::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$sis_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."
      </fieldset>

    </form>";
    return $_;
  }// Ficha: ver datos
  static function dat_fic( array $ope = [], ...$opc ) : string {
    global $sis_usu;
    $_ = "";
    $_fec = api_fec::_('dat',$sis_usu->fec);
    $_kin = api_hol::_('kin',$sis_usu->kin);
    $_psi = api_hol::_('psi',$sis_usu->psi);
    // sumatoria : kin + psi
    $sum = $_kin->ide + $_psi->tzo;

    // nombre + fecha : kin + psi
    $_ = "
    <section class='doc_inf ren esp-ara'>

      <div>
        <p class='tex-tit tex-3 mar_aba-1'>".api_tex::let("$sis_usu->nom $sis_usu->ape")."</p>
        <p>".api_tex::let($_fec->val." ( $sis_usu->eda años )")."</p>
      </div>        

      <div class='doc_val'>
        ".api_hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
        <c>+</c>
        ".api_hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
        <c>=></c>
        ".api_hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
      </div>

    </section>";

    return $_;
  }

  // Sesion
  static function ses( string $tip ){
    switch( $tip ){
    case 'ini':
      $_ = "dat";
      if( isset($_REQUEST['ema']) && isset($_REQUEST['pas']) ){
        // valido usuario
        $usu_dat = api_usu::dat( $_REQUEST['ema'] );
        if( isset($usu_dat->pas) ){
          // valido password
          if( $usu_dat->pas == $_REQUEST['pas'] ){
            $_SESSION['usu'] = $_REQUEST['ide'];
            $_ = "";
          }else{
            $_ = "pas";
          }
        }else{
          $_ = "usu";
        }
      }
      break;
    case 'fin':
      $_SESSION['usu'] = 0;
      break;
    }
  }// - inicio
  static function ses_ini() : string {
    $esq = 'usu';
    $est = 'dat';
    $_ = "
    <form class='dat' data-esq='{$esq}' data-est='{$est}' onsubmit='sis_usu.ses_ini'>

      <fieldset class='doc_var'>
        <input id='usu-ses_ini-mai' name='mai' type='email' placeholder='Ingresa tu Email...'>
      </fieldset>

      <fieldset class='doc_var'>
        <input id='usu-ses_ini-pas' name='pas' type='password' placeholder='Ingresa tu Password...'>
      </fieldset>

      <fieldset class='doc_var'>
        <label>Mantener Sesión Activa en este Equipo:</label>
        <input id='usu-ses_ini-val' name='val' type='checkbox'>
      </fieldset>

      <fieldset class='doc_ope'>
        <button type='submit'></button>
      </fieldset>

    </form>";
    return $_;
  }// - operador
  static function ses_ope() : string {
    global $sis_usu;
    $_ = "
    <nav class='lis'>
      <a href='' target='_blank'>Datos</a>
      <a href='' target='_blank'>Tránsitos</a>
    </nav>

    <nav class='doc_ope'>
      ".api_fig::ico('ses_fin',[ 'title'=>"Cerrar Sesión..." ])."
    <nav>";

    return $_;
  }

  // Transitos
  static function cic( string $tip = NULL, mixed $val = NULL ) : string {
    global $sis_usu;
    $_ = "";
    if( empty($tip) ){
    }
    else{
      switch( $tip ){
      // genero tránsitos anuales > lunares
      case 'ani':          
        // elimino previos
        $_ .= "DELETE FROM `usu_cic_ani` WHERE usu = $sis_usu->ide;<br>";
        $_ .= "DELETE FROM `usu_cic_lun` WHERE usu = $sis_usu->ide;<br>";
        // pido tránsitos
        foreach( api_hol::val_cic( $sis_usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

          $_ .= "INSERT INTO `usu_cic_ani` VALUES( 
            $sis_usu->ide, 
            $_cic_ani->ide, 
            $_cic_ani->eda, 
            $_cic_ani->arm, 
            $_cic_ani->ond, 
            $_cic_ani->ton, 
            '".api_fec::val_var($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
          );<br>";

          if( empty($_cic_ani->lun) ) continue;

          foreach( $_cic_ani->lun as $_cic_lun ){

            $_ .= "INSERT INTO `usu_cic_lun` VALUES( 
              $sis_usu->ide, 
              $_cic_lun->ani, 
              $_cic_lun->ide, 
              '".api_fec::val_var($_cic_lun->fec,'dia')."', 
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
  }// - calculo
  static function cic_dat( string $fec = '' ) : array {
    global $sis_usu;

    // valido fecha
    if( empty($fec) ) $fec = date( 'Y/m/d' );

    // cargo holon por fecha
    $_['hol'] = api_hol::val( $fec );

    // busco anillo actual
    $_['ani'] = api_app::dat('usu_cic_ani',[ 
      'ver'=>"`usu`='{$sis_usu->ide}' AND `fec` <= '".api_fec::val_var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
    ]);

    // busco transito lunar
    $_['lun'] = api_app::dat('usu_cic_lun',[ 
      'ver'=>"`usu`='{$sis_usu->ide}' AND `ani`={$_['ani']->ide} AND `fec` <= '".api_fec::val_var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
    ]);

    // calculo diario
    $_['dia'] = new stdClass;
    $_['dia']->kin = api_hol::_('kin', intval($_['hol']['kin']) + intval($sis_usu->kin) );

    return $_;
  }// - listado
  static function cic_nav( array $ope = [], ...$opc ) : string {
    global $sis_usu;
    $_ = "";
    foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
    $opc_des = !in_array('not-des',$opc);
    // descripciones
    $_kin_des = function( $kin ){
      $sel = api_hol::_('sel',$kin->arm_tra_dia);
      $ton = api_hol::_('ton',$kin->nav_ond_dia);
      return api_tex::let($kin->nom.": ")."<q>".api_tex::let("$ton->des ".api_tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
    };
    // listado
    $_lis = [];
    foreach( api_app::dat("usu_cic") as $_arm ){
      $_lis_cic = [];
      foreach( api_app::dat('usu_cic_ani',[ 'ver'=>"`usu`='{$sis_usu->ide}' AND `arm`=$_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
        // ciclos lunares
        $_lis_lun = [];
        foreach( api_app::dat('usu_cic_lun',[ 'ver'=>"`usu`='{$sis_usu->ide}' AND `ani`=$_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
          $_fec = api_fec::_('dat',$_lun->fec);
          $_lun_ton = api_hol::_('ton',$_lun->ide);
          $_kin = api_hol::_('kin',$_lun->kin);
          $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>".api_tex::let($_lun->sin)."</a>";
          $_lis_lun []= 
          "<div class='doc_ite'>
            ".api_hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
            <p>
              ".api_tex::let(intval($_lun_ton->ide)."° ciclo, ").$_nav.api_tex::let(" ( $_fec->val ).")."
              <br>".api_tex::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
            </p>              
          </div>
          <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
        }
        // ciclo anual
        $_fec = api_fec::_('dat',$_cic->fec);
        $_cas = api_hol::_('cas',$_cic->ide);
        $_cas_ton = api_hol::_('ton',$_cic->ton);
        $_cas_arm = api_hol::_('cas_arm',$_cic->arm);            
        $_kin = api_hol::_('kin',$_cic->kin);            
        $_lis_cic []= [
          'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
            "<div class='doc_ite'>
              ".api_hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                ".api_tex::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                <br>".api_tex::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>".api_tex::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
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
    api_ele::cla($ope['dep'],DIS_OCU);
    $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog-dep' ];
    return api_lis::dep($_lis,$ope);
  }// - informe
  static function cic_inf( array $ele = [], ...$opc ) : string {
    $dat = api_usu::cic_dat();
    $_ = "
    <section>
      ".api_usu::cic_inf_ani( $dat, $ele, ...$opc )."
      ".api_usu::cic_inf_lun( $dat, $ele, ...$opc )."
      ".api_usu::cic_inf_dia( $dat, $ele, ...$opc )."
    </section>"; 
    return $_;
  }// -- anual
  static function cic_inf_ani( array $dat, array $ele = [], ...$opc ) : string {
    global $sis_usu;
    $_ani = $dat['ani'];
    $_cas_arm = api_hol::_('cas_arm',$dat['ani']->arm);
    $_ani_arm = api_app::dat("usu_cic",['ver'=>"`ide`=$_ani->arm",'opc'=>"uni"]);
    $_ani_fec = api_fec::_('dat',$_ani->fec);      
    $_ani_ton = api_hol::_('ton',$dat['ani']->ton);
    $_kin = api_hol::_('kin',$_ani->kin);
    $_ = "
    <h3>Tránsito Anual</h3>

    <p>".api_tex::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

    ".api_num::var('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='doc_ite mar_ver-1'>
      ".api_hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p class='tex-tit'>".api_tex::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>".api_num::var('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    ".api_dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }// -- lunar
  static function cic_inf_lun( array $dat, array $ele = [], ...$opc ) : string {
    global $sis_usu;
    $_lun = $dat['lun'];
    $_lun_fec = api_fec::_('dat',$_lun->fec);
    $_lun_ton = api_hol::_('ton',$_lun->ide);
    $_kin = api_hol::_('kin',$_lun->kin);
    $_ = "
    <h3>Tránsito Lunar</h3>

    <p>".api_tex::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

    ".api_num::var('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='doc_ite mar_ver-1'>
      ".api_hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    ".api_dat::inf('hol','kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }// -- diario
  static function cic_inf_dia( array $dat, array $ele = [], ...$opc ) : string {
    global $sis_usu;
    $_dat = api_hol::val( date('Y/m/d') );
    $_kin = api_hol::_('kin',$dat['dia']->kin);

    $_ = "
    <h3>Tránsito Diario</h3>

    ".api_dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }  
}