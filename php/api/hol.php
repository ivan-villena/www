<?php
// holon : ns.ani.lun.dia:kin
class api_hol {

  // estructuras por posicion
  static function _( string $ide, mixed $val = NULL ) : string | array | object {
    global $_api;
    $_ = [];
    // aseguro carga      
    $est = "hol_$ide";
    if( !isset($_api->$est) ) $_api->$est = api_dat::ini(DAT_ESQ,$est);
    // busco dato
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        case 'fec':
          $fec = api_fec::_('dat',$val);
          if( isset($fec->dia)  ) $_ = api::dat( api_hol::_('psi'), [ 'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 'opc'=>['uni'] ]);
          break;
        case 'kin':
          $_ = $_api->$est[ api_num::ran( api_num::sum($val), 260 ) - 1 ];
          break;
        default:
          if( isset($_api->$est[ $val = intval($val) - 1 ]) ) $_ = $_api->$est[$val]; 
          break;
        }
      }
    }
    // devuelvo toda la lista
    else{
      $_ = $_api->$est;
    }
    return $_;    
  }
  // consults sql
  static function sql( string $ide ) : string {
    $_ = "";
    $_ide = explode('_',$ide);
    $est = array_shift($_ide);
    $atr = implode('_',$_ide);
    switch( $est ){
    case 'cas':
      if( empty($atr) ){
        foreach( api_hol::_('cas') as $_cas ){
          $_arm = api_hol::_('arm',$_cas->pos_arm);
          $_ton = api_hol::_('ton',$_cas->ton);
          $_ .= "
          UPDATE `hol_cas` SET 
            `nom` = '".api_tex::let_pal($_arm->col)." $_ton->nom'
          WHERE 
            `ide` = $_cas->ide;<br>";
        }
        break;
      }
      break;
    case 'kin':
      if( empty($atr) ){
        foreach( api_hol::_('kin') as $kin => $_kin ){
          $sel = api_hol::_('sel',$_kin->arm_tra_dia);
          $cel = api_hol::_('sel_arm_cel',$sel->arm_cel);
          $ton = api_hol::_('ton',$_kin->nav_ond_dia);      
          // poder del sello x poder del tono
          if( preg_match("/(o|a)$/i",$ton->nom) ){
            $pod = explode(' ',$sel->pod);
            $art = $pod[0];
            if( preg_match("/agua/i",$pod[1]) ){ 
              $art = 'la';
            }
            $pod = "{$sel->pod} ".( ( strtolower($art) == 'la' ) ? substr($ton->nom,0,-1).'a' : substr($ton->nom,0,-1).'o' );
          }else{
            $pod = "{$sel->pod} {$ton->nom}";
          }
          // encantamiento del kin
          $enc = "Yo ".($ton->pod_lec)." con el fin de ".ucfirst($sel->acc).", \n".($ton->acc_lec)." {$sel->car}. 
            \nSello {$cel->nom} ".api_tex::art_del($sel->pod)." con el tono {$ton->nom} ".api_tex::art_del($ton->pod).". ";
          $enc .= "\nMe guía ";
          if( $ton->pul_mat == 1 ){
            $enc .= "mi propio Poder duplicado. ";
          }else{
            $gui = api_hol::_('sel', api_hol::_('kin',$_kin->par_gui)->arm_tra_dia );
            $enc .= " el poder ".api_tex::art_del($gui->pod).".";
          }
          if( in_array($kin+1, $_kin->val_est) ){
            $_est = api_hol::_('kin_cro_est',$_kin->cro_est);
            $_ele = api_hol::_('kin_cro_ele',$_kin->cro_ele);
            $_arm = api_hol::_('kin_cro_ond',api_hol::_('ton',$_ele['ton'])->ond_arm);
            $enc .= "\nSoy un Kin Polar, {$_arm->enc} el Espectro Galáctico {$_est->col}. ";
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
        break;
      }
      switch( $atr ){
      case 'fac':
        $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
        $_add = [ '052','130','208' ];
        $ini = -3113;
        foreach( api_hol::_('kin') as $_kin ){    
    
          $fin = $ini + $_lim[intval($_kin->arm_tra_dia)-1];
    
          if( in_array($_kin->ide,$_add) ){ $fin ++; }
    
          $_ .= "
          UPDATE `_hol`.`_kin` SET 
            `fac` = '".api_fec::ran($ini,$fin)."'
          WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $ini = $fin;
    
        }
        break;
      case 'enc':
    
        $enc_ini = -26000;    
        foreach( api_hol::_('kin') as $_kin ){    
    
          $enc_fin = $enc_ini + 100;
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '".api_fec::ran($enc_ini,$enc_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $enc_ini = $enc_fin;
    
        }
        break;
      case 'cro_ele':
        foreach( api_hol::_('kin_cro_ele') as $_ele ){
          $_cas = api_hol::_('cas',$_ele->ide);        
          $_ton = api_hol::_('ton',$_ele->ton);
          $_est = api_hol::_('kin_cro_est',$_cas->arm);
          $_ .= "
          UPDATE `hol_kin_cro_ele` SET
            `des` = '$_ton->des del Espectro Galáctico ".api_tex::let_pal($_est->col)."'
          WHERE 
            `ide` = $_ele->ide;<br>";
        }
        break;
      case 'nav_ond':
        foreach( api_hol::_('kin_nav_ond') as $_ond ){
          $_sel = api_hol::_('sel',$_ond->sel);
          $_cas_arm = api_hol::_('cas_arm',$_ond->cas_arm);
          $_ .= "
          UPDATE `hol_kin_nav_ond` SET
            `des` = 'Se ".substr($_cas_arm->pod,0,-1)." el cuadrante $_cas_arm->col ".api_tex::art_del($_cas_arm->dir)." $_sel->acc_pal $_sel->car con el poder ".api_tex::art_del($_sel->pod)." '
          WHERE 
            `ide` = $_ond->ide;<br>";
        }
        break;
      }
      break;
    }
    return $_;
  }
  // armo imagen
  static function ima( string $est, mixed $dat, array $ele = [] ) : string {
    
    return app::ima('hol',"$est",$dat,$ele);
  }
  // convierto NS => d/m/a
  static function cod( array | string $val ) : bool | string {
    $_ = $val;

    if( is_string($val) ) $val = explode('.',$val);

    if( isset($val[3]) ){

      $sir = intval($val[0]);
      $ani = intval($val[1]);
      $lun = intval($val[2]);
      $dia = intval($val[3]);

      // mes y día
      $_psi = api::dat( api_hol::_('psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);
  
      if( isset($_psi->fec_mes) && isset($_psi->fec_dia) ){

        $_ = $_psi->fec_mes.'/'.$_psi->fec_dia;
      
        $ini_sir = 1;
        $ini_ani = 0;
        $año = 1987;
        // ns.
        if( $sir != $ini_sir ){

          $año = $año + ( 52 * ( $sir - $ini_sir ) );
        }
        // ns.ani.        
        if( $ani != $ini_ani ){          

          $año = $año + ( $ani - $ini_sir ) + 1;
        }
        // ajusto año
        if( $año == 1987 && ( $lun == 6 && $dia > 19 ) || $lun > 6 ){
          $año ++;
        }
        $_ = $año.'/'.$_;
      }
    }
    return $_;
  }
  // convierto d/m/a => NS
  static function dec( mixed $val ) : object | string {

    $_ = !is_object($val) ? api_fec::dat($val) : $val ;

    if( !!$_ ){
      // SE TOMA COMO PUNTO DE REFERENCIA EL AÑO 26/07/1987
      $año      = 1987; 
      $_->sir   = 1;
      $_->ani   = 0; 
      $_->fam_2 = 87;
      $_->fam_3 = 38;
      $_->fam_4 = 34;

      if ($año < $_->año ){

        while( $año < $_->año ){ 

          $año++;

          $_->ani++;

          foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 

            $_->$atr = api_num::ran($_->$atr+105, 260); 
          }

          if ($_->ani > 51){ 
            $_->ani = 0; 
            $_->sir++; 
          }
        }
      }
      elseif( $año > $_->año ){
        
        $_->sir = 0;
        while( $_->año < $año ){ 

          $año--; 
          
          $_->ani--;

          foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 
            
            $_->$atr = api_num::ran($_->$atr-105, 260); 
          }

          if ($_->ani < 0){ 
            $_->ani = 51; 
            $_->sir--; 
          }
        } 
        // sin considerar 0, directo a -1 : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
        if( $_->sir == 0 ) $_->sir = -1;
      }      
      if( $_->dia <= 25 && $_->mes <= 7){
        
        $_->ani--;
        
        foreach( ['fam_3','fam_4'] as $atr ){ 

          $_->$atr = api_num::ran($_->$atr-105, 260); 
        }
      }
    }
    else{
      $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }
    return $_;
  }
  // sumo o resto dias de un fecha dada
  static function ope( string $tip, string $val, int $cue = 1, string $opc = 'dia' ) : string {

    $_ = $val;

    if( isset($val[3]) ){

      $val = explode('.',$val);
      $sir = intval($val[0]);
      $ani = intval($val[1]);
      $lun = intval($val[2]);
      $dia = intval($val[3]);

      switch( $opc ){
      case 'dia':
        if( $tip == '+' ){

          $dia += $cue;        
  
          if( $dia > 28 ){
            $lun += api_num::red($cue / 28);
            $dia = api_num::ran($dia, 28);
            
            if( $lun > 13 ){
              $ani += api_num::red($lun / 13);
              $lun = api_num::ran($lun, 13);
  
              if( $ani > 51 ){
                $sir += api_num::red($ani / 51);
                $ani = api_num::ran($ani, 51, 0);
              }
            }
          }
        }
        elseif( $tip == '-' ){
  
          $dia -= $cue;        
  
          if( $dia < 1 ){
            $lun -= api_num::red($cue / 28);
            $dia = api_num::ran($dia, 28);
            
            if( $lun < 1 ){    
              $ani -= api_num::red($lun / 13);
              $lun = api_num::ran($lun, 13);
  
              if( $ani < 0 ){    
                $sir -= api_num::red($ani / 51);
                $ani = api_num::ran($ani, 51, 0);
              }
            }
          }
        }        
        break;
      case 'lun': 
        if( $tip == '+' ){

          $lun += $cue;
            
          if( $lun > 13 ){
            $ani += api_num::red($lun / 13);
            $lun = api_num::ran($lun, 13);

            if( $ani > 51 ){  
              $sir += api_num::red($ani / 51);
              $ani = api_num::ran($ani, 51, 0);                
            }
          }
        }
        elseif( $tip == '-' ){

          $lun -= $cue;
            
          if( $lun < 1 ){  
            $ani -= api_num::red($lun / 13);
            $lun = api_num::ran($lun, 13);

            if( $ani < 0 ){
              $sir -= api_num::red($ani / 51);
              $ani = api_num::ran($ani, 51, 0);
            }
          }
        }        
        break;
      case 'ani': 
        if( $tip == '+' ){

          $ani += $cue;

          if( $ani > 51 ){
            $sir += api_num::red($ani / 51);
            $ani = api_num::ran($ani, 51, 0);
          }
        }
        elseif( $tip == '-' ){

          $ani -= $cue;

          if( $ani < 0 ){
            $sir -= api_num::red($ani / 51);
            $ani = api_num::ran($ani, 51, 0);
          }
        }
        break;
      case 'sir':
        if( $tip == '+' ){

          $sir += $cue;
        }
        elseif( $tip == '-' ){

          $sir -= $cue;
        }
        if( $sir == 0 ) $sir = -1;

        break;
      }

      $_ = "$sir.".api_num::val($ani,2).".".api_num::val($lun,2).".".api_num::val($dia,2);
    }
    return $_;
  }
  // busco valores : fecha - sincronario - tránsitos
  static function val( mixed $val, string $tip = '', array $ope = [] ) : array | object | string {
    $_=[];
    // por tipo
    if( !empty($tip) ){
      // proceso fecha
      if( $tip == 'fec' ){
        $fec = $val;
        $_ = api_hol::val($fec);
        if( is_string($_) ){ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
        }
      }
      // decodifico N.S.( cod.ani.lun.dia:kin )
      elseif( $tip == 'sin' ){
        // busco año          
        if( $_fec = api_hol::cod($val) ){

          $_ = api_hol::val($_fec);

          if( is_string($_) ) $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
        }
        else{ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
        }
      }
    }
    // armo datos de una fecha
    elseif( $fec = api_fec::dat($val) ){
      // giro solar => año
      $_['fec'] = $fec->val;

      $_fec = api_hol::dec($fec);

      // giro lunar => mes + día
      if( $_psi = api_hol::_('fec',$_['fec']) ){

        $_['psi'] = $_psi->ide;

        $_['sin'] = "{$_fec->sir}.".api_num::val($_fec->ani,2).".{$_psi->lun}.{$_psi->lun_dia}";
        
        // giro galáctico => kin
        $_kin = api_hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);

        if( is_object($_kin) ){

          $_['kin'] = $_kin->ide;
        }
        else{
          $_ = '{-_-} Error de Cálculo con la fecha galáctica...'; 
        }
      }
      else{ 
        $_ = '{-_-} Error de Cálculo con la fecha solar...'; 
      }
    }
    // error
    else{ 
      $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }
    return $_;
  }
  // genero acumulados por valor principal
  static function dat( string $est, array $dat, array $ope = [] ) : array {
    $_ = [];

    $ini = isset($ope['ini']) ? intval($ope['ini']) : 1;
    $inc = isset($ope['inc']) ? intval($ope['inc']) : 1;
    $val = isset($ope['val']) ? intval($ope['val']) : "+";
    
    $cue = 0;
    // x 260 dias por kin 
    if( $est == 'kin' && isset($dat['kin']) && isset($dat['fec']) ){

      $cue = 260;

      $fec = api_fec::ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
    }
    // x 364+1 dias por psi-cronos
    elseif( $est == 'psi' && isset($dat['psi']) && isset($dat['fec']) ){

      $cue = 364;

      $fec = api_fec::ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
    }

    if( isset($fec) ){
  
      for( $pos = 0; $pos < $cue; $pos++ ){

        $_dat = api_hol::val($fec);

        $_ []= app_val::dat([
          'fec'=>[ 
            'dat'=>api_fec::_('dat',$fec),
          ],
          'hol'=>[               
            'kin'=>api_hol::_('kin',$_dat['kin']), 
            'psi'=>api_hol::_('psi',$_dat['psi']) 
          ]
        ]);

        $fec = api_fec::ope($fec, $inc, $val);
      }      

    }
    return $_;
  }
  // genero transitos por fecha del sincronario
  static function cic( string $val, ...$opc ) : array {
    $_ = [];
    $ver_lun = !in_array('not-lun',$opc);
    
    // recorro el castillo anual
    for( $cic_año = 1 ; $cic_año <= 52; $cic_año++ ){
      
      $_val = api_hol::val($val,'sin');

      $_cas = api_hol::_('cas',$cic_año);
      
      // creo el transito anual
      $_cic_año = new stdClass;
      $_cic_año->ide = $cic_año;
      $_cic_año->eda = $cic_año-1;
      $_cic_año->arm = $_cas->arm;
      $_cic_año->ond = $_cas->ond;
      $_cic_año->ton = $_cas->ton;
      $_cic_año->fec = $_val['fec'];
      $_cic_año->sin = $_val['sin'];
      $_cic_año->kin = $_val['kin'];
      // genero transitos lunares
      if( $ver_lun ){
        $_cic_año->lun = [];
        
        $val_lun = $val;

        for( $cic_mes = 1 ; $cic_mes <= 13; $cic_mes++ ){

          $_val_lun = api_hol::val($val_lun,'sin');

          $_cic_lun = new stdClass;  
          $_cic_lun->ani = $cic_año;
          $_cic_lun->ide = $cic_mes;
          $_cic_lun->fec = $_val_lun['fec'];
          $_cic_lun->sin = $_val_lun['sin'];
          $_cic_lun->kin = $_val_lun['kin'];
          
          $_cic_año->lun []= $_cic_lun;
          // incremento 1 luna
          $val_lun = api_hol::ope('+',$val_lun,1,'lun');            
        }
      }        
      $_ []= $_cic_año;
      // incremento 1 anillo      
      $val = api_hol::ope('+',$val,1,'ani');
    }

    return $_;
  }
}