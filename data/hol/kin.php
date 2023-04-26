<?php 

  function hol_kin(){
    $_ = "";
    foreach( Hol::_('kin') as $kin => $_kin ){
      $sel = Hol::_('sel',$_kin->arm_tra_dia);
      $cel = Hol::_('sel_arm_cel',$sel->arm_cel);
      $ton = Hol::_('ton',$_kin->nav_ond_dia);      
      // poder del sello x poder del tono
      if( preg_match("/(o|a)$/i",$ton->nom) ){
        $pod = explode(' ',$sel->des_pod);
        $art = $pod[0];
        if( preg_match("/agua/i",$pod[1]) ){ 
          $art = 'la';
        }
        $pod = "{$sel->des_pod} ".( ( strtolower($art) == 'la' ) ? substr($ton->nom,0,-1).'a' : substr($ton->nom,0,-1).'o' );
      }else{
        $pod = "{$sel->des_pod} {$ton->nom}";
      }
      // encantamiento del kin
      $enc = "Yo ".($ton->pod_lec)." con el fin de ".ucfirst($sel->acc).", \n".($ton->acc_lec)." {$sel->des_car}. 
        \nSello {$cel->nom} ".Tex::art_del($sel->des_pod)." con el tono {$ton->nom} ".Tex::art_del($ton->des_pod).". ";
      $enc .= "\nMe guía ";
      if( $ton->pul_mat == 1 ){
        $enc .= "mi propio Poder duplicado. ";
      }else{
        $gui = Hol::_('sel', Hol::_('kin',$_kin->par_gui)->arm_tra_dia );
        $enc .= " el poder ".Tex::art_del($gui->des_pod).".";
      }
      if( in_array($kin+1, $_kin->val_est) ){
        $_est = Hol::_('kin_cro_est',$_kin->cro_est);
        $_ele = Hol::_('kin_cro_ele',$_kin->cro_ele);
        $_arm = Hol::_('kin_cro_ond',Hol::_('ton',$_ele['ton'])->ond_arm);
        $enc .= "\nSoy un Kin Polar, {$_arm->enc} {$_est->des_col}. ";
      }
      if( in_array($kin+1, $_kin->val_pag) ){
        $enc .= "\nSoy un Portal de Activación Galáctica, entra en mí.";
      }
      $_ .= "
      <p>
        UPDATE `_hol`.`kin` SET 
          `pod` = '{$pod}', 
          `des` = '{$enc}'
        WHERE 
          `ide` = '{$_kin->ide}';
      </p>";
    }
    return $_;
  }

  function hol_kin_fac(){
    $_ = "";
    $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
    $_add = [ '052','130','208' ];
    $ini = -3113;
    foreach( Hol::_('kin') as $_kin ){    

      $fin = $ini + $_lim[intval($_kin->arm_tra_dia)-1];

      if( in_array($_kin->ide,$_add) ){ $fin ++; }

      $_ .= "
      UPDATE `_hol`.`_kin` SET 
        `fac` = '".Fec::año_ran($ini,$fin)."'
      WHERE `ide`='$_kin->ide'; 
      <br>";

      $ini = $fin;
    }
    return $_;
  }

  function hol_kin_enc(){
    $_ = "";
    $enc_ini = -26000;    
    foreach( Hol::_('kin') as $_kin ){    

      $enc_fin = $enc_ini + 100;

      $_ .= "
      UPDATE `_hol`.`_kin` 
        SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '".Fec::año_ran($enc_ini,$enc_fin)."' 
        WHERE `ide`='$_kin->ide'; 
      <br>";

      $enc_ini = $enc_fin;

    }
    return $_;
  }

  function hol_kin_cro_ele(){
    $_ = "";
    $kin = 185;
    $kin_lis = "{$kin} - 189";
    foreach( Hol::_('kin_cro_ele') as $_ele ){
      $_cas = Hol::_('cas',$_ele->ide);
      $_ton = Hol::_('ton',$_ele->ton);
      $_est = Hol::_('kin_cro_est',$_cas->arm);
      $_ .= "
      UPDATE `hol_kin_cro_ele` SET
        `des` = '$_ton->des del Espectro Galáctico ".Tex::let_pal($_est->des_col)."',
        `est` = $_est->ide,
        `kin` = '$kin_lis'
      WHERE 
        `ide` = $_ele->ide;<br>";
      // contadores
      $kin = Num::val_ran($kin + 5, 260);
      $kin_lis = Num::val($kin,3)." - ".Num::val(Num::val_ran($kin + 4,260),3);
    }
    return $_;
  }

  function hol_kin_cro_est_dia(){
    $_ = "";
    $ton = 1;
    $pos = 0;
    foreach( Hol::_('kin_cro_est_dia') as $_dia ){
      $pos++;
      if( $pos > 5 ){
        $pos = 0;
        $ton++;
      }
      $_ .= "
      UPDATE `hol_kin_cro_est_dia` SET
        `ton` = $ton
      WHERE 
        `ide` = $_dia->ide;<br>";
    }
    return $_;
  }

  function hol_kin_nav_ond(){
    $_ = "";
    foreach( Hol::_('kin_nav_ond') as $_ond ){
      $_sel = Hol::_('sel',$_ond->sel);
      $_cas_arm = Hol::_('cas_arm',$_ond->cas_arm);
      $_ .= "
      UPDATE `hol_kin_nav_ond` SET
        `des` = 'Se ".substr($_cas_arm->des_pod,0,-1)." el cuadrante $_cas_arm->des_col ".Tex::art_del($_cas_arm->dir)." $_sel->acc_pal $_sel->des_car con el poder ".Tex::art_del($_sel->des_pod)." '
      WHERE 
        `ide` = $_ond->ide;<br>";
    }
    return $_;
  }

  function hol_kin_arm_cel(){
    $_ = "";
    $kin = 1;
    foreach( Hol::_('kin_arm_cel') as $cel ){
      $kin_val = Num::val($kin, 3). " - ".Num::val($kin = $kin + 3, 3);
      $_ .= "UPDATE `hol_kin_arm_cel` SET `kin` = '$kin_val' WHERE `ide` = $cel->ide;<br>";
      $kin++;
    }
    return $_;
  }