<?php  

  /* Datos */

  // Informe: nuevo / editar
  function dat_inf() : string {
    global $Usu;
    $esq = 'usu';
    $est = 'dat';
    $_kin = Hol::_('kin',$Usu->kin);
    $_psi = Hol::_('psi',$Usu->psi);
    $_ = "
    <form class='dat' data-esq='{$esq}' data-est='{$est}'>

      <fieldset class='doc_ren'>
        ".Dat::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$Usu->$atr  ], 'eti')."
        ".Dat::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$Usu->$atr  ], 'eti')."                              
      </fieldset>

      <fieldset class='doc_ren'>
        ".Dat::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$Usu->$atr  ],'eti')."
        ".Dat::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$Usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."
      </fieldset>

    </form>";
    return $_;
  }// Ficha: ver datos
  function dat_fic( array $ope = [], ...$opc ) : string {
    global $Usu;
    $_ = "";
    $_fec = Fec::_('dat',$Usu->fec);
    $_kin = Hol::_('kin',$Usu->kin);
    $_psi = Hol::_('psi',$Usu->psi);
    // sumatoria : kin + psi
    $sum = $_kin->ide + $_psi->tzo;

    // nombre + fecha : kin + psi
    $_ = "
    <section class='doc_inf ren esp-ara'>

      <div>
        <p class='tex-tit tex-3 mar_aba-1'>".Tex::let("$Usu->nom $Usu->ape")."</p>
        <p>".Tex::let($_fec->val." ( $Usu->eda años )")."</p>
      </div>        

      <div class='doc_val'>
        ".Hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
        <c>+</c>
        ".Hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
        <c>=></c>
        ".Hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
      </div>

    </section>";

    return $_;
  }

  /* Transitos */  
  function cic( string $tip = NULL, mixed $val = NULL ) : string {
    global $Usu;
    $_ = "";
    if( empty($tip) ){
    }
    else{
      switch( $tip ){
      // genero tránsitos anuales > lunares
      case 'ani':
        // elimino previos
        $_ .= "DELETE FROM `usu_cic_ani` WHERE usu = $Usu->ide;<br>";
        $_ .= "DELETE FROM `usu_cic_lun` WHERE usu = $Usu->ide;<br>";
        // pido tránsitos
        foreach( Hol::val_cic( $Usu->sin, 1, 52, 'not-lun') as $_cic_ani ){

          $_ .= "INSERT INTO `usu_cic_ani` VALUES( 
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

            $_ .= "INSERT INTO `usu_cic_lun` VALUES( 
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
  function cic_dat( string $fec = '' ) : array {
    global $Usu;

    // valido fecha
    if( empty($fec) ) $fec = date( 'Y/m/d' );

    // cargo holon por fecha
    $_['hol'] = Hol::val( $fec );

    // busco anillo actual
    $_['ani'] = Dat::get('usu_cic_ani',[ 
      'ver'=>"`usu`='{$Usu->ide}' AND `fec` <= '".Fec::val_var( $_['hol']['fec'] )."'", 'ord'=>"`ide` DESC", 'lim'=>1, 'opc'=>"uni"
    ]);

    // busco transito lunar
    $_['lun'] = Dat::get('usu_cic_lun',[ 
      'ver'=>"`usu`='{$Usu->ide}' AND `ani`={$_['ani']->ide} AND `fec` <= '".Fec::val_var( $_['hol']['fec'] )."'", 'ord'=>"`ani`, `ide` DESC", 'lim'=>1, 'opc'=>"uni" 
    ]);

    // calculo diario
    $_['dia'] = new stdClass;
    $_['dia']->kin = Hol::_('kin', intval($_['hol']['kin']) + intval($Usu->kin) );

    return $_;
  }
  // - listado
  function cic_nav( array $ope = [], ...$opc ) : string {
    global $Usu;
    $_ = "";
    foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
    $opc_des = !in_array('not-des',$opc);
    // descripciones
    $_kin_des = function( $kin ){
      $sel = Hol::_('sel',$kin->arm_tra_dia);
      $ton = Hol::_('ton',$kin->nav_ond_dia);
      return Tex::let($kin->nom.": ")."<q>".Tex::let("$ton->des ".Tex::art_del($sel->pod).", $ton->acc_lec $sel->car")."</q>";
    };
    // listado
    $_lis = [];
    foreach( Dat::get("usu_cic") as $_arm ){
      $_lis_cic = [];
      foreach( Dat::get('usu_cic_ani',[ 'ver'=>"`usu`='{$Usu->ide}' AND `arm`=$_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
        // ciclos lunares
        $_lis_lun = [];
        foreach( Dat::get('usu_cic_lun',[ 'ver'=>"`usu`='{$Usu->ide}' AND `ani`=$_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
          $_fec = Fec::_('dat',$_lun->fec);
          $_lun_ton = Hol::_('ton',$_lun->ide);
          $_kin = Hol::_('kin',$_lun->kin);
          $_nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>".Tex::let($_lun->sin)."</a>";
          $_lis_lun []= 
          "<div class='doc_ite'>
            ".Hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
            <p>
              ".Tex::let(intval($_lun_ton->ide)."° ciclo, ").$_nav.Tex::let(" ( $_fec->val ).")."
              <br>".Tex::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
            </p>              
          </div>
          <p class='mar-1 tex_ali-cen'>".$_kin_des($_kin)."</p>";
        }
        // ciclo anual
        $_fec = Fec::_('dat',$_cic->fec);
        $_cas = Hol::_('cas',$_cic->ide);
        $_cas_ton = Hol::_('ton',$_cic->ton);
        $_cas_arm = Hol::_('cas_arm',$_cic->arm);            
        $_kin = Hol::_('kin',$_cic->kin);            
        $_lis_cic []= [
          'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
            "<div class='doc_ite'>
              ".Hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                ".Tex::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                <br>".Tex::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>".Tex::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
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
    return Lis::dep($_lis,$ope);
  }
  // - informe
  function cic_inf( array $ele = [], ...$opc ) : string {
    global $Usu;
    $dat = cic_dat();
    $_ = "
    <section>
      ".cic_inf_ani( $dat, $ele, ...$opc )."
      ".cic_inf_lun( $dat, $ele, ...$opc )."
      ".cic_inf_dia( $dat, $ele, ...$opc )."
    </section>"; 
    return $_;
  }
  // -> anual
  function cic_inf_ani( array $dat, array $ele = [], ...$opc ) : string {
    global $Usu;
    $_ani = $dat['ani'];
    $_cas_arm = Hol::_('cas_arm',$dat['ani']->arm);
    $_ani_arm = Dat::get("usu_cic",['ver'=>"`ide`=$_ani->arm",'opc'=>"uni"]);
    $_ani_fec = Fec::_('dat',$_ani->fec);      
    $_ani_ton = Hol::_('ton',$dat['ani']->ton);
    $_kin = Hol::_('kin',$_ani->kin);
    $_ = "
    <h3>Tránsito Anual</h3>

    <p>".Tex::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

    ".Num::var('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='doc_ite mar_ver-1'>
      ".Hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p class='tex-tit'>".Tex::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>".Num::var('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    ".Dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }
  // -> lunar
  function cic_inf_lun( array $dat, array $ele = [], ...$opc ) : string {
    global $Usu;
    $_lun = $dat['lun'];
    $_lun_fec = Fec::_('dat',$_lun->fec);
    $_lun_ton = Hol::_('ton',$_lun->ide);
    $_kin = Hol::_('kin',$_lun->kin);
    $_ = "
    <h3>Tránsito Lunar</h3>

    <p>".Tex::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

    ".Num::var('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='doc_ite mar_ver-1'>
      ".Hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    ".Dat::inf('hol','kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }
  // -> diario
  function cic_inf_dia( array $dat, array $ele = [], ...$opc ) : string {
    global $Usu;
    $_dat = Hol::val( date('Y/m/d') );
    $_kin = Hol::_('kin',$dat['dia']->kin);

    $_ = "
    <h3>Tránsito Diario</h3>

    ".Dat::inf('hol','kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }  