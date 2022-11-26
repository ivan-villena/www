<?php
// usuario : sesion + tránsitos  
class usu {

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
      foreach( dat::get('usu', [ 'ver'=>"`ide`='{$ide}'", 'opc'=>'uni' ]) as $atr => $val ){

        $this->$atr = $val;
      }
      // calculo edad actual
      $this->eda = fec::cue('eda',$this->fec);
    }      
  }

  // sesion
  static function ses( string $tip ) : string {
    switch( $tip ){
      // datos del usuario
      case 'dat':
        $esq = 'api'; 
        $est = 'usu';
        global $_usu;
        $_kin = hol::_('kin',$_usu->kin);
        $_psi = hol::_('psi',$_usu->psi);
        $_ = "
        <form class='dat' data-esq='{$esq}' data-est='{$est}'>

          <fieldset class='ren'>

            ".doc::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_usu->$atr  ], 'eti')."

            ".doc::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_usu->$atr  ], 'eti')."                        
          
          </fieldset>

          <fieldset class='ren'>

            ".doc::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$_usu->$atr  ],'eti')."

            ".doc::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."

          </fieldset>

        </form>";
        break;
      // inicio de sesion por usuario
      case 'ini':
        $_ = "
        <form class='dat' action=''>

          <fieldset>

            <label for=''>Email</label>
            <input type='mail'>

          </fieldset>

          <fieldset>

            <label for=''>Password</label>
            <input type='password'>

          </fieldset>

        </form>";
        break;
      // finalizo sesion del usuario
      case 'fin': 
        $_ = "    
        <form class='dat' action=''>

        </form>";
        break;
    }  
    return $_;
  }

  // ficha
  public function fic( array $ope = [], ...$opc ) : string {
    $_ = "";
    global $_usu;      
    $_fec = fec::_('dat',$_usu->fec);
    $_kin = hol::_('kin',$_usu->kin);
    $_psi = hol::_('psi',$_usu->psi);
    // sumatoria : kin + psi
    $sum = $_kin->ide + $_psi->tzo;

    // nombre + fecha : kin + psi
    $_ = "
    <section class='inf ren esp-ara'>

      <div>
        <p class='let-tit let-3 mar_aba-1'>".tex::let("$_usu->nom $_usu->ape")."</p>
        <p>".tex::let($_fec->val." ( $_usu->eda años )")."</p>
      </div>        

      <div class='val'>
        ".hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
        <c>+</c>
        ".hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
        <c>=></c>
        ".hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
      </div>

    </section>";

    return $_;
  }

  // genero transitos
  public function cic( string $tip = NULL, mixed $val = NULL ) : string {
    $_ = "";
    if( empty($tip) ){
    }
    else{
      switch( $tip ){
      // genero tránsitos anuales > lunares
      case 'ani':          
        // elimino previos
        $_ .= "DELETE FROM `_api`.`usu_cic_ani` WHERE usu = $this->ide;<br>";
        $_ .= "DELETE FROM `_api`.`usu_cic_lun` WHERE usu = $this->ide;<br>";

        // pido tránsitos
        foreach( hol::cic( $this->sin, 1, 52, 'not-lun') as $_cic_ani ){

          $_ .= "INSERT INTO `_usu`.`cic_ani` VALUES( 
            $this->ide, 
            $_cic_ani->ide, 
            $_cic_ani->eda, 
            $_cic_ani->arm, 
            $_cic_ani->ond, 
            $_cic_ani->ton, 
            '".fec::var_val($_cic_ani->fec,'dia')."', '$_cic_ani->sin', $_cic_ani->kin 
          );<br>";

          if( empty($_cic_ani->lun) ) continue;

          foreach( $_cic_ani->lun as $_cic_lun ){

            $_ .= "INSERT INTO `_usu`.`cic_lun` VALUES( 
              $this->ide, 
              $_cic_lun->ani, 
              $_cic_lun->ide, 
              '".fec::var_val($_cic_lun->fec,'dia')."', 
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
  }// calculo tránsito actual
  public function cic_dat( string $fec = '' ) : array {

    // valido fecha
    if( empty($fec) ) $fec = date( 'Y/m/d' );

    // cargo holon por fecha
    $_['hol'] = hol::val( $fec );

    // busco anillo actual
    $_['ani'] = dat::get('usu_cic_ani',[ 
      'ver'=>"`usu` = '{$this->ide}' AND `fec` <= '".fec::var_val( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
    ]);

    // busco transito lunar
    $_['lun'] = dat::get('usu_cic_lun',[ 
      'ver'=>"`usu` = '{$this->ide}' AND `ani` = {$_['ani']->ide} AND `fec` <= '".fec::var_val( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
    ]);

    // calculo diario
    $_['dia'] = new stdClass;
    $_['dia']->kin = hol::_('kin', intval($_['hol']['kin']) + intval($this->kin) );

    return $_;
  }
  // - listado
  public function cic_nav( array $ope = [], ...$opc ) : string {
    $_ = "";
    global $_usu;
    foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
    $opc_des = !in_array('not-des',$opc);
    // descripciones
    $_kin_des = function( $kin ){
      $sel = hol::_('sel',$kin->arm_tra_dia);
      $ton = hol::_('ton',$kin->nav_ond_dia);
      return tex::let($kin->nom.": ")."<q>".tex::let("$ton->des ".tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
    };
    // listado
    $_lis = [];
    foreach( dat::get("usu_cic") as $_arm ){
      $_lis_cic = [];
      foreach( dat::get('usu_cic_ani',[ 'ver'=>"`usu` = '{$_usu->ide}' AND `arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
        // ciclos lunares
        $_lis_lun = [];
        foreach( dat::get('usu_cic_lun',[ 'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
          $_fec = fec::_('dat',$_lun->fec);
          $_lun_ton = hol::_('ton',$_lun->ide);
          $_kin = hol::_('kin',$_lun->kin);
          $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>".tex::let($_lun->sin)."</a>";
          $_lis_lun []= 
          "<div class='ite'>
            ".hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
            <p>
              ".tex::let(intval($_lun_ton->ide)."° ciclo, ").$_nav.tex::let(" ( $_fec->val ).")."
              <br>".tex::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
            </p>              
          </div>
          <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
        }
        // ciclo anual
        $_fec = fec::_('dat',$_cic->fec);
        $_cas = hol::_('cas',$_cic->ide);
        $_cas_ton = hol::_('ton',$_cic->ton);
        $_cas_arm = hol::_('cas_arm',$_cic->arm);            
        $_kin = hol::_('kin',$_cic->kin);            
        $_lis_cic []= [
          'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
            "<div class='ite'>
              ".hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                ".tex::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                <br>".tex::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>".tex::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
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
    ele::cla($ope['dep'],DIS_OCU);
    $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog_dep' ];
    return lis::ite($_lis,$ope);
  }
  // - informe
  public function cic_inf( array $ele = [], ...$opc ) : string {    
    $dat = $this->cic_dat();
    $_ = "
    <section>
      ".$this->cic_inf_ani( $dat, $ele, ...$opc )."
      ".$this->cic_inf_lun( $dat, $ele, ...$opc )."
      ".$this->cic_inf_dia( $dat, $ele, ...$opc )."
    </section>"; 
    return $_;
  }// - anual
  public function cic_inf_ani( array $dat, array $ele = [], ...$opc ) : string {
    $_ani = $dat['ani'];
    $_cas_arm = hol::_('cas_arm',$dat['ani']->arm);
    $_ani_arm = dat::get("usu_cic",['ver'=>"`ide` = $_ani->arm",'opc'=>"uni"]);
    $_ani_fec = fec::_('dat',$_ani->fec);      
    $_ani_ton = hol::_('ton',$dat['ani']->ton);
    $_kin = hol::_('kin',$_ani->kin);
    $_ = "
    <h3>Tránsito Anual</h3>

    <p>".tex::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

    ".num::var('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='ite mar_ver-1'>
      ".hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='let-3'>
        <p class='let-tit'>".tex::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>".num::var('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    ".dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }// - lunar
  public function cic_inf_lun( array $dat, array $ele = [], ...$opc ) : string {
    global $_usu;
    $_lun = $dat['lun'];
    $_lun_fec = fec::_('dat',$_lun->fec);
    $_lun_ton = hol::_('ton',$_lun->ide);
    $_kin = hol::_('kin',$_lun->kin);
    $_ = "
    <h3>Tránsito Lunar</h3>

    <p>".tex::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

    ".num::var('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='ite mar_ver-1'>
      ".hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='let-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    ".dat::inf('hol','kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }// - diario
  public function cic_inf_dia( array $dat, array $ele = [], ...$opc ) : string {
    global $_usu;
    $_dat = hol::val( date('Y/m/d') );
    $_kin = hol::_('kin',$dat['dia']->kin);

    $_ = "
    <h3>Tránsito Diario</h3>

    ".dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }
}