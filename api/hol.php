<?php
// holon : ns.ani.lun.dia:kin
class hol {

  static string $IDE = "hol-";
  static string $EJE = "hol.";

  function __construct(){
  }
  // getter
  static function _( string $ide, mixed $val = NULL ) : string | array | object {
    $_ = [];
    global $api_hol;
    $est = "_$ide";
    if( !isset($api_hol->$est) ) $api_hol->$est = dat::est_ini(DAT_ESQ,"hol{$est}");
    $_dat = $api_hol->$est;

    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        case 'fec':
          $fec = fec::_('dat',$val);
          if( isset($fec->dia)  ) $_ = dat::get( hol::_('psi'), [ 
            'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 
            'opc'=>['uni'] 
          ]);
          break;
        case 'kin':
          $_ = $_dat[ num::ran( num::val_sum($val), 260 ) - 1 ];
          break;
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];     
          break;
        }
      }
    }// toda la lista
    else{
      $_ = $_dat;
    }
    return $_;    
  }// consults sql
  static function sql( string $ide ) : string {
    $_ = "";
    $_ide = explode('_',$ide);
    $est = array_shift($_ide);
    $atr = implode('_',$_ide);
    switch( $est ){
    case 'cas':
      if( empty($atr) ){
        foreach( hol::_('cas') as $_cas ){
          $_arm = hol::_('arm',$_cas->pos_arm);
          $_ton = hol::_('ton',$_cas->ton);
          $_ .= "
          UPDATE `hol_cas` SET 
            `nom` = '".tex::let_pal($_arm->col)." $_ton->nom'
          WHERE 
            `ide` = $_cas->ide;<br>";
        }
        break;
      }
      break;
    case 'kin':
      switch( $atr ){
      case 'fac':
        $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
        $_add = [ '052','130','208' ];
        $ini = -3113;
        foreach( hol::_('kin') as $_kin ){    
    
          $fin = $ini + $_lim[intval($_kin->arm_tra_dia)-1];
    
          if( in_array($_kin->ide,$_add) ){ $fin ++; }
    
          $_ .= "
          UPDATE `_hol`.`_kin` SET 
            `fac` = '".fec::ran($ini,$fin)."'
          WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $ini = $fin;
    
        }
        break;
      case 'enc':
    
        $enc_ini = -26000;    
        foreach( hol::_('kin') as $_kin ){    
    
          $enc_fin = $enc_ini + 100;
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '".fec::ran($enc_ini,$enc_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $enc_ini = $enc_fin;
    
        }
        break;
      case 'cro_ele':
        $kin = 185;
        $kin_lis = "{$kin} - 189";
        foreach( hol::_('kin_cro_ele') as $_ele ){
          $_cas = hol::_('cas',$_ele->ide);        
          $_ton = hol::_('ton',$_ele->ton);
          $_est = hol::_('kin_cro_est',$_cas->arm);
          $_ .= "
          UPDATE `hol_kin_cro_ele` SET
            `des` = '$_ton->des del Espectro Galáctico ".tex::let_pal($_est->col)."',
            `est` = $_est->ide,
            `kin` = '$kin_lis'
          WHERE 
            `ide` = $_ele->ide;<br>";
          // contadores
          $kin = num::ran($kin + 5, 260);
          $kin_lis = num::val($kin,3)." - ".num::val(num::ran($kin + 4,260),3);
        }
        break;
      case 'cro_est_dia': 
        $ton = 1;
        $pos = 0;
        foreach( hol::_('kin_cro_est_dia') as $_dia ){
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
        break;
      case 'nav_ond':
        foreach( hol::_('kin_nav_ond') as $_ond ){
          $_sel = hol::_('sel',$_ond->sel);
          $_cas_arm = hol::_('cas_arm',$_ond->cas_arm);
          $_ .= "
          UPDATE `hol_kin_nav_ond` SET
            `des` = 'Se ".substr($_cas_arm->pod,0,-1)." el cuadrante $_cas_arm->col ".tex::art_del($_cas_arm->dir)." $_sel->acc_pal $_sel->car con el poder ".tex::art_del($_sel->pod)." '
          WHERE 
            `ide` = $_ond->ide;<br>";
        }
        break;
      case 'arm_cel':
        $kin = 1;
        foreach( hol::_('kin_arm_cel') as $cel ){
          $kin_val = num::val($kin, 3). " - ".num::val($kin = $kin + 3, 3);
          $_ .= "UPDATE `hol_kin_arm_cel` SET `kin` = '$kin_val' WHERE `ide` = $cel->ide;<br>";
          $kin++;
        }
        break;
      default:
        foreach( hol::_('kin') as $kin => $_kin ){
          $sel = hol::_('sel',$_kin->arm_tra_dia);
          $cel = hol::_('sel_arm_cel',$sel->arm_cel);
          $ton = hol::_('ton',$_kin->nav_ond_dia);      
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
            \nSello {$cel->nom} ".tex::art_del($sel->pod)." con el tono {$ton->nom} ".tex::art_del($ton->pod).". ";
          $enc .= "\nMe guía ";
          if( $ton->pul_mat == 1 ){
            $enc .= "mi propio Poder duplicado. ";
          }else{
            $gui = hol::_('sel', hol::_('kin',$_kin->par_gui)->arm_tra_dia );
            $enc .= " el poder ".tex::art_del($gui->pod).".";
          }
          if( in_array($kin+1, $_kin->val_est) ){
            $_est = hol::_('kin_cro_est',$_kin->cro_est);
            $_ele = hol::_('kin_cro_ele',$_kin->cro_ele);
            $_arm = hol::_('kin_cro_ond',hol::_('ton',$_ele['ton'])->ond_arm);
            $enc .= "\nSoy un Kin Polar, {$_arm->enc} {$_est->col}. ";
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
      break;
    case 'psi':
      switch( $atr ){
      case 'est_dia': 
        $ton = 1;
        $pos = 0;
        foreach( hol::_('psi_est_dia') as $_dia ){
          $pos++;
          if( $pos > 7 ){
            $pos = 0;
            $ton ++;
          }
          $_ .= "
          UPDATE `hol_psi_est_dia` SET
            `ton` = $ton
          WHERE 
            `ide` = $_dia->ide;<br>";
        }
        break;
      }
      break;
    }
    return $_;
  }

  // valores:
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
      $_psi = dat::get( hol::_('psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);
  
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

    $_ = !is_object($val) ? fec::dat($val) : $val ;

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

            $_->$atr = num::ran($_->$atr+105, 260); 
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
            
            $_->$atr = num::ran($_->$atr-105, 260); 
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

          $_->$atr = num::ran($_->$atr-105, 260); 
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
            $lun += num::val_red($cue / 28);
            $dia = num::ran($dia, 28);
            
            if( $lun > 13 ){
              $ani += num::val_red($lun / 13);
              $lun = num::ran($lun, 13);
  
              if( $ani > 51 ){
                $sir += num::val_red($ani / 51);
                $ani = num::ran($ani, 51, 0);
              }
            }
          }
        }
        elseif( $tip == '-' ){
  
          $dia -= $cue;        
  
          if( $dia < 1 ){
            $lun -= num::val_red($cue / 28);
            $dia = num::ran($dia, 28);
            
            if( $lun < 1 ){    
              $ani -= num::val_red($lun / 13);
              $lun = num::ran($lun, 13);
  
              if( $ani < 0 ){    
                $sir -= num::val_red($ani / 51);
                $ani = num::ran($ani, 51, 0);
              }
            }
          }
        }        
        break;
      case 'lun': 
        if( $tip == '+' ){

          $lun += $cue;
            
          if( $lun > 13 ){
            $ani += num::val_red($lun / 13);
            $lun = num::ran($lun, 13);

            if( $ani > 51 ){  
              $sir += num::val_red($ani / 51);
              $ani = num::ran($ani, 51, 0);                
            }
          }
        }
        elseif( $tip == '-' ){

          $lun -= $cue;
            
          if( $lun < 1 ){  
            $ani -= num::val_red($lun / 13);
            $lun = num::ran($lun, 13);

            if( $ani < 0 ){
              $sir -= num::val_red($ani / 51);
              $ani = num::ran($ani, 51, 0);
            }
          }
        }        
        break;
      case 'ani': 
        if( $tip == '+' ){

          $ani += $cue;

          if( $ani > 51 ){
            $sir += num::val_red($ani / 51);
            $ani = num::ran($ani, 51, 0);
          }
        }
        elseif( $tip == '-' ){

          $ani -= $cue;

          if( $ani < 0 ){
            $sir -= num::val_red($ani / 51);
            $ani = num::ran($ani, 51, 0);
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

      $_ = "$sir.".num::val($ani,2).".".num::val($lun,2).".".num::val($dia,2);
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
        $_ = hol::val($fec);
        if( is_string($_) ){ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
        }
      }
      // decodifico N.S.( cod.ani.lun.dia:kin )
      elseif( $tip == 'sin' ){
        // busco año          
        if( $_fec = hol::cod($val) ){

          $_ = hol::val($_fec);

          if( is_string($_) ) $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
        }
        else{ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
        }
      }
    }
    // armo datos de una fecha
    elseif( $fec = fec::dat($val) ){
      // giro solar => año
      $_['fec'] = $fec->val;

      $_fec = hol::dec($fec);

      // giro lunar => mes + día
      if( $_psi = hol::_('fec',$_['fec']) ){

        $_['psi'] = $_psi->ide;

        $_['sin'] = "{$_fec->sir}.".num::val($_fec->ani,2).".{$_psi->lun}.{$_psi->lun_dia}";
        
        // giro galáctico => kin
        $_kin = hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);

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

      $fec = fec::ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
    }
    // x 364+1 dias por psi-cronos
    elseif( $est == 'psi' && isset($dat['psi']) && isset($dat['fec']) ){

      $cue = 364;

      $fec = fec::ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
    }

    if( isset($fec) ){
  
      for( $pos = 0; $pos < $cue; $pos++ ){

        $_dat = hol::val($fec);

        $_ []= val::dat([
          'fec'=>[ 
            'dat'=>fec::_('dat',$fec),
          ],
          'hol'=>[               
            'kin'=>hol::_('kin',$_dat['kin']), 
            'psi'=>hol::_('psi',$_dat['psi']) 
          ]
        ]);

        $fec = fec::ope($fec, $inc, $val);
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
      
      $_val = hol::val($val,'sin');

      $_cas = hol::_('cas',$cic_año);
      
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

          $_val_lun = hol::val($val_lun,'sin');

          $_cic_lun = new stdClass;  
          $_cic_lun->ani = $cic_año;
          $_cic_lun->ide = $cic_mes;
          $_cic_lun->fec = $_val_lun['fec'];
          $_cic_lun->sin = $_val_lun['sin'];
          $_cic_lun->kin = $_val_lun['kin'];
          
          $_cic_año->lun []= $_cic_lun;
          // incremento 1 luna
          $val_lun = hol::ope('+',$val_lun,1,'lun');            
        }
      }        
      $_ []= $_cic_año;
      // incremento 1 anillo      
      $val = hol::ope('+',$val,1,'ani');
    }

    return $_;
  }

  // imagen
  static function ima( string $est, mixed $dat, array $ele = [] ) : string {
    
    return arc::ima('hol',$est,$dat,$ele);
  }  

  // variable:
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";    
    $_ide = self::$IDE."var_$tip";
    $_eje = self::$EJE."var_$tip";
    $_tip = explode('-',$tip);
    $atr = isset($_tip[1]) ? $_tip[1] : '';
    switch( $est = $_tip[0] ){
    case 'fec':
      $_eje =  !empty($ope['eje']) ? $ope['eje'] : self::$EJE."val";
      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : hol::_('kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : hol::_('psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      
  
      $_ = "
      <!-- Fecha del Calendario -->
      <form class='val fec mar-1'>
  
        ".fig::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol_val-fec", 'class'=>"mar_hor-1", 
          'title'=>"Desde aquí puedes cambiar la fecha..." 
        ])."
        ".fec::var('dia', $_fec, [ 'id'=>"hol_val-fec", 'name'=>"fec", 
          'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
        ])."
        ".fig::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);", 
          'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
        ])."
  
      </form>
  
      <!-- Fecha del Sincronario -->
      <form class='val sin mar-1'>
        
        <label>N<c>.</c>S<c>.</c></label>
  
        ".num::var('int', $_sin[0], [ 
          'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
        ])."
        <c>.</c>
        ".opc::lis( hol::_('ani'), [
          'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
          'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        ".opc::lis( hol::_('psi_lun'), [
          'eti'=>[ 'name'=>"lun", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
          'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        ".opc::lis( hol::_('lun'), [ 
          'eti'=>[ 'name'=>"dia", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
          'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
        ])."          
        <c class='sep'>:</c>
    
        <n name='kin'>$_kin->ide</n>
  
        ".fig::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);",
          'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
        ])."
  
      </form>";
      break;
    case 'kin':
      $dat = hol::_($est,$dat);
      $_bib = SYS_NAV."hol/bib/";
      switch( $atr ){
      // parejas del oráculo
      case 'par':        
        if( !isset($_tip[2]) ){          
          $_ = "
          <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='{$_bib}enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>

          <div class='lis'>";
          foreach( hol::_('sel_par') as $_par ){
            // salteo el destino
            if( ( $ide = $_par->cod ) == 'des' ) continue;
            // busco datos de parejas
            $_par = dat::get( hol::_('sel_par'), [ 'ver'=>[ ['cod','==',$ide] ], 'opc'=>'uni' ]);
            $kin = hol::_('kin',$dat->{"par_{$ide}"});
            $_ .= "
            <p class='mar_arr-2 tex_ali-izq'>
              <b class='ide let-sub'>{$_par->nom}</b><c>:</c>
              <br><q>".tex::let($_par->des)."</q>
              ".( !empty($_par->lec) ? "<br><q>".tex::let($_par->lec)."</q>" : "" )."
            </p>
            
            ".dat::inf('hol','kin',$kin,['cit'=>"des"])
            ;
          } $_ .= "
          </div>";
        }
        else{          
          $_ = [];          
          $_kin = $dat;          
          $_sel = hol::_('sel',$dat->arm_tra_dia);
          $ope['lis'] = ['class'=>"anc-100 mar_aba-2"];
          $htm = "";
          switch( $_tip[2] ){
          // Propiedades : palabras clave del kin + sello + tono
          case 'des':
            $htm = "
            <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

            <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>";

            $_par_atr = ['fun','acc','mis'];
            $_ton_atr = ['acc'];  
            $_sel_atr = ['car','des'];  
            foreach( hol::_('sel_par') as $_par ){
              
              $_kin_par = $_par->ide == 'des' ? $_kin : hol::_('kin',$_kin->{"par_{$_par->ide}"});
      
              $ite = [ hol::ima("kin",$_kin_par) ];
      
              foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= tex::let($_par->$atr); }
      
              $_ton_par = hol::_('ton',$_kin_par->nav_ond_dia);
              foreach( $_ton_atr as $atr ){ if( isset($_ton_par->$atr) ) $ite []= tex::let($_ton_par->$atr); }
      
              $_sel_par = hol::_('sel',$_kin_par->arm_tra_dia);            
              foreach( $_sel_atr as $atr ){  if( isset($_sel_par->$atr) ) $ite []= tex::let($_sel_par->$atr); }
      
              $_ []= $ite;
            }
            break;
          // lecturas por parejas
          case 'lec':
            $htm = "
            <p>En <a href='{$_bib}tut#_04-04-' target='_blank'>este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

            <p>Puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>";
            
            foreach( hol::_('sel_par') as $_par ){
  
              if( $_par->ide == 'des' ) continue;
              $_kin_par = hol::_('kin',$_kin->{"par_{$_par->ide}"});
              $_sel_par = hol::_('sel',$_kin_par->arm_tra_dia);
              $_ []=
              hol::ima("kin",$_kin_par)."
  
              <div>
                <p><b class='tit'>{$_kin_par->nom}</b> <c>(</c> ".tex::let($_par->dia)." <c>)</c></p>
                <p>".tex::let("{$_sel_par->acc} {$_par->pod} {$_sel_par->car}, que {$_par->mis} {$_sel->car}, {$_par->acc} {$_sel_par->pod}.")."</p>
              </div>";
            }
            ele::cla($ope['lis'],'ite');
            break;
          // Ciclos : posiciones en ciclos del kin
          case 'cic': 
            $htm = "
            <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

            <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>";

            $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
      
            foreach( hol::_('sel_par') as $_par ){
              
              $_kin_par = $_par->ide == 'des' ? $_kin : hol::_('kin',$_kin->{"par_{$_par->ide}"});
  
              $ite = [ hol::ima("kin",$_kin_par) ];
  
              foreach( $_atr as $atr ){
                $ite []= hol::ima("kin_{$atr}",$_kin_par->$atr,[ 'class'=>"tam-5" ]);
              }
              
              $_ []= $ite;
            }
            break;
          // Grupos : sincronometría del holon por sellos      
          case 'gru': 
            $htm = "
            <p>Puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='{$_bib}tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

            <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>";

            $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];  

            foreach( hol::_('sel_par') as $_par ){
              
              $_kin_par = $_par->ide == 'des' ? $_kin : hol::_('kin',$_kin->{"par_{$_par->ide}"});                            
      
              $_sel_par = hol::_('sel',$_kin_par->arm_tra_dia);
      
              $ite = [ hol::ima("kin",$_kin_par), $_par->nom, $_sel_par->pod ];
      
              foreach( $_atr as $atr ){
                $ite []= hol::ima("sel_{$atr}",$_sel_par->$atr,[ 'class'=>"tam-5" ]);
              }            
              $_ []= $ite;
            }            
            break;
          }
          $_ = $htm.est::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        }
        break;
      }
      break;
    }
    return $_;
  }

  // listados
  static function lis(){
  }

  // tableros:
  static function tab( string $est, string $atr, array $ope = [], array $ele = [] ) : string {
    extract( tab::dat("hol",$est,$atr,$ope,$ele) );
    $_ = "";
    switch( $tab ){
    case 'uni':
      switch( $atr ){
      // holon solar por flujo vertical ( T.K. )
      case 'sol': 
        $ope_val = []; 
        foreach( ['res','ele','cel','cir'] as $i ){ 
          $ope_val[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
        }$_ = "
        <ul".ele::atr($ele['sec']).">";
          // imágenes: galaxia + sol
          foreach( ['gal'=>[ 'Galaxia' ],'sol'=>[ 'Sol' ] ] as $i=>$v ){
            $_ .= arc::ima("hol/tab/$i",[ 'eti'=>"li", 'class'=>"sec $i" ]);
          }
          // 2 respiraciones x10 flechas
          foreach( hol::_('uni_sol_res') as $v ){ 
            for( $i = 1; $i <= 10; $i++ ){
              $_ .= hol::ima("uni_sol_res",$v,[ 'eti'=>"li", 'class'=>"sec res-{$v->ide} ide-$i" ]);
            }
          }// x 4 flujos
          foreach( hol::_('uni_flu') as $v ){
            $_ .= hol::ima("uni_flu_pod",$v->pod,[ 'eti'=>"li", 'class'=>"sec flu-{$v->ide} pod-{$v->pod}" ]); 
          }
          // 10 planetas
          foreach( hol::_('uni_sol_pla') as $v ){ 
            $_ .= hol::ima("uni_sol_pla",$v,[ 'eti'=>"li", 'class'=>"sec pla-{$v->ide}" ]);
          }
          // bordes: 
          // - 4 elementos/clanes
          foreach( hol::_('sel_cro_ele') as $v ){ $_ .= "
            <li class='sec ele-{$v->ide}{$ope_val['ele']}' title='".dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }// - 5 células del tiempo
          foreach( hol::_('uni_sol_cel') as $v ){ $_ .= "
            <li class='sec cel-{$v->ide}{$ope_val['cel']}' title='".dat::val('tit',"hol.uni_sol_cel",$v)."'></li>";
          }// - 5 circuitos de telepatía
          foreach( hol::_('uni_sol_cir') as $v ){ $_ .= "
            <li class='sec cir-{$v->ide}{$ope_val['cir']}' title='".dat::val('tit',"hol.uni_sol_cir",$v)."'></li>";
          }
          // posicion: 20 sellos solares
          if( !isset($ele['sel']) ) $ele['sel'] = [];
          foreach( hol::_('sel') as $v ){            
            $ele_ite = $ele['sel']; 
            ele::cla($ele_ite,"pos sel-{$v->ide}"); $_ .= "
            <li".ele::atr($ele_ite).">
              ".hol::ima("sel_cod",$v)."
            </li>";
          }$_ .= " 
        </ul>";        
        break;
      // holon solar por flujo circular ( E.S. )
      case 'sol_enc':
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          // fondos: 
          foreach( ['map','ato'] as $i ){ 
            $ele_ite = isset($ele["fon-$i"]) ? $ele["fon-$i"] : [];
            ele::cla($ele_ite,"sec fon $i",'ini'); $_ .= "
            <li".ele::atr( isset($ele["fon-$i"]) ? $ele["fon-$i"] : [])."></li>";
          }
          // respiracion, clanes, celulas, circuitos
          foreach( ['res','cel','cir'] as $i ){ 
            $ele_ite = isset($ele["fon-$i"]) ? $ele["fon-$i"] : [];
            ele::cla($ele_ite,"sec fon $i",'ini'); $_ .= "
            <li".ele::atr($ele_ite)."></li>";
          }
          // planetas
          foreach( hol::_('uni_sol_pla') as $v ){
            $ele_ite = [ 'eti'=>"li" ];
            ele::cla($ele_ite,"sec pla-$v->ide",'ini');             
            $ele_ite['title'] = dat::val('tit',"{$esq}.uni_sol_pla",$v); 
            $_ .= hol::ima('uni_sol_pla',$v,$ele_ite);
          }
          // sellos
          if( !isset($ele['pos']) ) $ele['pos'] = [];        
          foreach( hol::_('sel') as $v ){
            $ele_ite = $ele['pos'];
            ele::cla($ele_ite,"pos sel-$v->ide",'ini'); $_ .= "
            <li".ele::atr($ele_ite).">
              ".hol::ima('sel_cod',$v)."
            </li>";
          }
          $_ .= " 
        </ul>";        
        break;
      // holon planetario 
      case 'pla':
        $ope_val = [];
        foreach( ['res','cen','ele'] as $i ){ 
          $ope_val[$i] = ( !isset($ope['sec'][$i]) || empty($ope['sec'][$i]) ) ? " ".DIS_OCU : ""; 
        }
        $_ = "
        <ul".ele::atr($ele['sec']).">
          <li class='sec fon map'></li>
          <li class='sec fon sel'></li>";
          // fondos: flujos, 
          foreach( ['res','ele'] as $i ){ $_ .= "
            <li class='sec fon {$i}{$ope_val[$i]}'></li>";
          }
          // 5 centros galácticos
          foreach( hol::_('uni_pla_cen') as $v ){ 
            $_ .= hol::ima("sel_cro_fam",$v->fam,[ 'eti'=>"li", 'class'=>"sec fam-{$v->ide}" ]);
          }
          // 20 posiciones por sello solar
          foreach( hol::_('sel') as $v ){
            $ele_pos = $ele['pos'];
            ele::cla($ele_pos,"pos sel-{$v->ide}",'ini'); $_ .= "
            <li".ele::atr($ele_pos).">
              ".hol::ima("sel",$v)."
            </li>";
          }
          $_ .= "
        </ul>";        
        break;
      // holon humano
      case 'hum':
        $ope_val = []; 
        foreach( ['res','cen','ext','ded','art','cha'] as $i ){ 
          $ope_val[$i] = ( !isset($ope['sec'][$i]) || empty($ope['sec'][$i]) ) ? " ".DIS_OCU : ''; 
        }
        $_ = "
        <ul".ele::atr($ele['sec']).">
          <li class='sec fon map'></li>";
          // fondos: respiracion, centro, extremidad, circuitos, articulaciones
          foreach( ['res','cen','ext','ded','art'] as $i ){ $_ .= "
            <li class='sec fon {$i}{$ope_val[$i]}'></li>";
          }
          // 5 centros : familias
          foreach( hol::_('uni_hum_cen') as $v ){ 
            $_ .= hol::ima("uni_hum_cen",$v,[ 'eti'=>"li", 'class'=>"sec fam-{$v->ide}{$ope_val['cen']}"]);
          }
          // 20 dedos : sellos
          if( !isset($ele['sel']) ) $ele['sel'] = [];
          foreach( hol::_('sel') as $v ){
            $ele_ite = $ele['sel'];
            ele::cla($ele_ite,"pos sel-{$v->ide}",'ini'); $_ .= "
            <li".ele::atr($ele_ite).">".hol::ima("sel",$v)."</li>";
          }
          // 13 articulaciones : tonos
          if( !isset($ele['ton']) ) $ele['ton'] = [];
          foreach( hol::_('ton') as $v ){
            $ele_ite = $ele['ton'];
            ele::cla($ele_ite,"pos ton-{$v->ide}{$ope_val['art']}",'ini'); $_ .= "
            <li".ele::atr($ele_ite).">".hol::ima("ton",$v)."</li>";
          }
          $_ .= "
        </ul>";        
        break;
      }
      break;
    case 'rad':
      switch( $atr ){
      }
      break;
    case 'ton':
      switch( $atr ){
      // onda encantada
      case 'ond':
        ele::cla($ele['sec'],"hol_ton");
        $_ .= "
        <ul".ele::atr($ele['sec']).">
          ".hol::tab_sec('ton',$ope)
          ;
          foreach( hol::_('ton') as $_ton ){            
            $_ .= tab::pos('hol','ton',$_ton,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      }
      break;
    case 'sel': 
      switch( $atr ){
      // codigo
      case 'cod':
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          foreach( hol::_('sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='val jus-cen'>
                ".hol::ima("sel",$_sel,['eti'=>"li"])."
                ".hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='mar-0 ali_pro-cen'>
                {$_sel->arm}
                <br>{$_sel->acc}
                <br>{$_sel->pod}
              </p>
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // colocacion cromática
      case 'cro':
        $_ = "
        <ul".ele::atr($ele['sec']).">
          <li class='pos-0'></li>";
          foreach( hol::_('sel_cro_fam') as $v ){ 
            $_ .= hol::ima("sel_cro_fam",$v,[ 'eti'=>"li", 'class'=>"sec fam-{$v->ide}" ]);
          } 
          foreach( hol::_('sel_cro_ele') as $v ){ 
            $_ .= hol::ima("sel_cro_ele",$v,[ 'eti'=>"li", 'class'=>"sec ele-{$v->ide}"]);
          }
          $ele_pos = $ele['pos'];
          for( $i=0; $i<=19; $i++ ){ 
            $v = hol::_('sel', ( $i == 0 ) ? 20 : $i);
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _val-pos' : '' ;
            $ele['pos'] = $ele_pos;
            ele::cla($ele['pos'],"{$agr}");
            $_ .= tab::pos('hol','sel',$v,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      // colocacion armónica
      case 'arm':
        $_ .= "
        <ul".ele::atr($ele['sec']).">
          <li class='pos-0'></li>
          ";
          foreach( hol::_('sel_arm_cel') as $v ){ 
            $_ .= hol::ima("sel_arm_cel",$v,[ 'eti'=>"li", 'class'=>"sec cel-{$v->ide}"]);
          } 
          foreach( hol::_('sel_arm_raz') as $v ){ 
            $_ .= hol::ima("sel_arm_raz",$v,[ 'eti'=>"li", 'class'=>"sec raz-{$v->ide}"]);
          }
          $ele_pos = $ele['pos'];
          foreach( hol::_('sel') as $v ){
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _val-pos' : '' ;
            $ele['pos'] = $ele_pos;
            ele::cla($ele['pos'],"{$agr}");
            $_ .= tab::pos('hol','sel',$v,$ope,$ele);
          }
          $_ .= "
        </ul>";        
        break;
      // tablero del oráculo
      case 'arm_tra':
        $_ .= "
        <ul".ele::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){
            $ope['ide'] = $i;
            $_ .= hol::tab('kin','arm_cel',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;      
      // célula del tiempo para el oráculo
      case 'arm_cel':
        $_arm = hol::_('sel_arm_cel',$ide);        
        $ele['cel']['title'] = dat::val('tit',"hol.{$est}",$_arm); 
        ele::cla($ele['cel'],"tab sel {$atr}",'ini');
        $_ = "
        <ul".ele::atr($ele['cel']).">
          ".hol::ima("sel_arm_cel", $_arm, ['eti'=>"li", 'class'=>"pos-0", 'htm'=>$_arm->ide ] );
          foreach( hol::_('sel_arm_raz') as $_raz ){
            $_ .= tab::pos('hol','sel',$sel,$ope,$ele);
            $sel++;
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    case 'lun': 
      switch( $atr ){
        case 'hep': break;
        case 'tel': break;
      }
      break;
    case 'cas': 
      switch( $atr ){
      // ondas encantadas
      case 'ond':
        ele::cla($ele['sec'],"hol_cas");
        $_ = "
        <ul".ele::atr($ele['sec']).">
          <li class='sec fon-ima'></li>";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          ele::cla($ele_ite,"pos-0",'ini'); $_ .= "
          <li".ele::atr($ele_ite)."></li>
          ".hol::tab_sec('cas',$ope)
          ;
          foreach( hol::_('cas') as $_cas ){
            $_ .= tab::pos('hol','cas',$_cas,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      }
      break;
    case 'kin': 
      switch( $atr ){
      // tzolkin
      case 'tzo':
        $ton_htm = isset($ope['sec']['kin-ton']);
        $ton_val = !empty($ope['sec']['kin-ton']);
        $ele_ton = [ 'class'=> "sec ton" ];
        $sel_htm = isset($ope['sec']['kin-sel']);
        $sel_val = !empty($ope['sec']['kin-sel']);
        // ajusto grilla
        if( $ton_val ) ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");

        $_ = "
        <ul".ele::atr($ele['sec']).">";
          // 1° columna
          if( $ton_htm && $sel_htm ){ $_ .= "
            <li".ele::atr([ 'class' => "sec ini".( $ton_val && $sel_val ? "" : " dis-ocu" )])."></li>";
          }
          // filas por sellos
          if( $sel_htm ){
            foreach( hol::_('sel') as $v ){ 
              $_ .= hol::ima("sel",$v,[ 'eti'=>"li", 'class'=>"sec sel-{$v->ide}".( $sel_val ? "" : " dis-ocu" ) ]);
            }
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( hol::_('kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){ $_ .= 
              hol::ima("kin_arm_tra",$_kin->arm_tra,['eti'=>"li",'class'=>"sec ton-{$_kin->arm_tra}".( $ton_val ? "" : " dis-ocu" )]);
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            if( isset($ele["pos-{$_kin->ide}"]) ) $ele['pos'] = ele::val_jun( $ele["pos-{$_kin->ide}"], $ele['pos'] );
            $_ .= tab::pos('hol','kin',$_kin,$ope,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </ul>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par': 
        if( empty($ide) ) $ide = 1;
        $_kin = is_object($ide) ? $ide : hol::_('kin',$ide);           
        ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( hol::_('sel_par') as $_par ){
            $par_ide = $_par->cod;
            $par_kin = ( $par_ide == 'des' ) ? $_kin : hol::_('kin',$_kin->{"par_{$par_ide}"});
            // combino elementos :
            $ele['pos'] = isset($ele["par-{$par_ide}"]) ? ele::val_jun($ele_pos,$ele["par-{$par_ide}"]) : $ele_pos;
            ele::cla($ele['pos'],"par-{$par_ide}");
            $_ .= tab::pos('hol','kin',$par_kin,$ope,$ele);
          }$_ .= "
        </ul>";
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_ = "
        <ul".ele::atr($ele['sec']).">
          ".hol::tab_sec('cas',$ope);
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          ele::cla($ele_ite,"pos-0",'ini'); $_ .= "
          <li".ele::atr($ele_ite)."></li>";
          $_fam_kin = [ 1 => 1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ];
          $_fam = hol::_('sel_cro_fam',$ide);            
          $kin = intval($_fam['kin']);
          foreach( hol::_('cas') as $_cas ){
            $_kin = hol::_('kin',$kin);
            $_ .= tab::pos('hol','kin',$kin,$ope,$ele);
            $kin = num::ran($kin + 105, 260);
          } $_ .= "
        </ul>";          
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          $ele_cas = $ele['cas'];
          foreach( hol::_('kin_nav_cas') as $cas => $_cas ){
            $ope['ide'] = $_cas->ide;
            $ele['cas'] = $ele_cas;
            ele::cla($ele['cas'],"pos-$_cas->ide",'ini');
            $_ .= hol::tab('kin','nav_cas',$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      case 'nav_cas':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_cas = hol::_($est,$ide);
        $ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ini + 4;
        $ele['cas']['title'] = dat::val('tit',"hol.{$est}",$_cas);
        for( $ond = $ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = hol::_('kin_nav_ond',$ond);
          $ele['cas']['title'] .= "\n".$_ond->enc_des;
        }
        ele::cla($ele['cas'],"tab kin {$atr} hol_cas fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ),'ini');
        $_ = "
        <ul".ele::atr($ele['cas']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          ele::cla($ele_ite,"pos-0 bor_col-5-{$ide} fon_col-5-{$ide}");
          $_ .= "
          <li".ele::atr($ele_ite).">{$ide}</li>
          ".hol::tab_sec('cas',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 52 ) + 1;
          foreach( hol::_('cas') as $_cas ){
            $_ .= tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;
      case 'nav_ond':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_ond = hol::_($est,$ide); 
        $_cas = hol::_('kin_nav_cas',$_ond->nav_cas);
        $ele['ond']['title'] = dat::val('tit',"hol.kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
        ele::cla($ele['ond'],"tab kin {$atr} hol_ton",'ini');
        $_ = "
        <ul".ele::atr($ele['ond']).">
          ".hol::tab_sec('ton',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          foreach( hol::_('ton') as $_ton ){
            $_ .= tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;      
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        ele::cla($ele['sec'],"hol_ton");
        $_ = "
        <ul".ele::atr($ele['sec']).">
          ".hol::tab_sec('ton',$ope)
          ;
          $ele_tra = $ele['tra'];
          foreach( hol::_('kin_arm_tra') as $_tra ){ 
            $ope['ide'] = $_tra->ide;
            $ele['tra'] = $ele_tra;
            ele::cla($ele['tra'],"pos-{$_tra->ide}",'ini');
            $_ .= hol::tab('kin','arm_tra',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'arm_tra':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        ele::cla($ele['tra'],"tab kin {$atr} hol_cro",'ini');
        $_ = "
        <ul".ele::atr($ele['tra']).">";
          $_tra = hol::_('kin',$ide);
          $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
          $cel_fin = $cel_ini + 5;
          $ele_pos = $ele['cel'];
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $ope['ide'] = $cel;
            $ele['cel'] = $ele_pos;
            ele::cla($ele['cel'],"pos-".num::ran($cel,5));
            $_ .= hol::tab('kin','arm_cel',$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      case 'arm_cel': 
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }         
        $_arm = hol::_($est,$ide);
        ele::cla($ele['cel'],"tab kin {$atr} hol_arm fon_col-5-$_arm->cel fon-0");
        $_ = "
        <ul".ele::atr($ele['cel']).">";
          $ele_ite = isset($ele['pos-0']) ? $ele['pos-0'] : []; 
          ele::cla($ele_ite,"pos-0 col-bla",'ini');
          $ele_ite['eti'] = "li"; $ele_ite['htm'] = "$_arm->ide"; $ele_ite['onclick'] = FALSE;
          $ele_ite['title'] = dat::val('tit',"hol.{$est}",$_arm);
          $_ .= hol::ima("sel_arm_cel",$_arm->cel,$ele_ite);
          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro': 
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_cas';
        ele::cla($ele['sec'],"hol_cas");
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          $ele_ite['eti'] = "li";
          ele::cla($ele_ite,"pos-0",'ini'); 
          $_ .= arc::ima('hol/tab/gal',$ele_ite)
          .hol::tab_sec('cas',$ope)
          ;
          $ele_ele = $ele['ele'];
          foreach( hol::_('kin_cro_ele') as $_ele ){
            $ope['ide'] = $_ele->ide;
            $ele['ele'] = $ele_ele;
            ele::cla($ele['ele'],"pos-{$_ele->ide}");
            $_ .= hol::tab('kin','cro_ele',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'cro_est':
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_ond';

        ele::cla($ele['est'],"tab kin {$atr} hol_ton",'ini');
        $_ = "
        <ul".ele::atr($ele['est']).">
          ".hol::tab_sec('ton',$ope)
          ;
          $_est = hol::_('kin_cro_est',$ide); 
          $cas = $_est->cas;
          $ele_ele = $ele['ele'];
          foreach( hol::_('ton') as $_ton ){
            $ope['ide'] = $cas;
            $ele['ele'] = $ele_ele;
            ele::cla($ele['ele'],"pos-".intval($_ton->ide));
            $_ .= hol::tab('kin','cro_ele',$ope,$ele);
            $cas = num::ran($cas + 1, 52);
          } $_ .= "
        </ul>";
        break;
      case 'cro_ele':
        foreach(['ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        $_ele = hol::_('kin_cro_ele',$ide);
        $ELE = [
          "rot-ton" => [ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
          "rot-cas" => [ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
        ];
        // cuenta de inicio
        $kin_ini = 185;
        $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";
        // del castillo | onda : rotaciones
        if( in_array('fic_cas',$opc) || in_array('fic_ond',$opc) ){ ele::css($ele['ele'],
          "transform: rotate(".(in_array('fic_cas',$opc) ? $ELE['rot-cas'][$ide-1] : $ELE['rot-ton'][$ide-1])."deg)");
        }
        ele::cla($ele['ele'],"tab kin {$atr} hol_cro-cir",'ini');
        $_ .= "
        <ul".ele::atr($ele['ele']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          ele::cla($ele_ite,"pos-0",'ini'); $_ .= "
          <li".ele::atr($ele_ite).">".hol::ima('kin_cro_ele',$_ele->ide)."</li>";

          $kin = num::ran($kin_ini + ( ( $ide - 1 ) * 5 ), 260);
          foreach( hol::_('sel_cro_fam') as $cro_fam ){
            $_ .= tab::pos('hol','kin',$kin,$ope,$ele);
            $kin++;// por verdad eléctrica
            if( $kin > 260 ) $kin = 1;
          }$_ .= "
        </ul>";
        break;
      }
      break;
    case 'psi': 
      switch( $atr ){
      case 'ban': 
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        ele::cla($ele['sec'],'ond');
        $_ = "
        <ul".ele::atr($ele['sec']).">
          ".arc::ima('hol/tab/sol',['eti'=>"li", 'class'=>"sec uni_sol"])."
          ".arc::ima('hol/tab/pla',['eti'=>"li", 'class'=>"sec uni_lun"])."
          ".hol::tab_sec('ton',$ope)
          ;
          if( !in_array('cab_nom',$ope['opc']) ) $ope['opc'] []= 'cab_nom';
          foreach( hol::_('psi_lun') as $_lun ){            
            $ele_pos = isset($ele["lun-{$_lun->ide}"]) ? $ele["lun-{$_lun->ide}"] : [];
            ele::cla($ele_pos,"pos-$_lun->ide",'ini');
            $ope['ide'] = $_lun->ide; $_ .= "
            <li".ele::atr($ele_pos).">
              ".hol::tab('psi','lun',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // anillos solares por ciclo de sirio
      case 'ani':
        $kin = 34;
        $ope['sec']['orb_cir'] = '1';
        ele::cla($ele['sec'],'cas-cir');
        $_ = "
        <ul".ele::atr($ele['sec']).">
          <li class='pos-0'></li>
          ".hol::tab_sec('cas_cir',$ope)
          ;          
          foreach( hol::_('cas') as $_cas ){
            $_kin = hol::_('kin',$kin);
            $ele_pos = $ele['pos'];
            ele::cla($ele_pos,"pos-".intval($_cas->ide),'ini');
            $_ .= "
            <li".ele::atr($ele_pos).">
              ".hol::ima("kin",$_kin)."
            </li>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </ul>";        
        break;
      // estaciones de 91 días
      case 'est':
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        ele::cla($ele['sec'],'cas');
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          $ele_ite = isset($ele['pos-00']) ? $ele['pos-00'] : [];
          $ele_ite['eti'] = "li";
          $_ .= 
            arc::ima('hol/tab/pla',$ele_ite)
            .hol::tab_sec('cas',$ope)
          ;
          foreach( hol::_('cas') as $_cas ){                        
            $ope['ide'] = $_cas->ide;
            $_ .= hol::tab('psi','hep',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      // luna de 28 días
      case 'lun':
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = hol::_('psi',$val['psi'])->lun;
        $_lun = hol::_($est,$ide);
        $_ton = hol::_('ton',$ide);
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);

        ele::cla($ele['lun'],"tab psi {$atr}",'ini'); $_ = "
        <table".ele::atr($ele['lun']).">";
          if( !$cab_ocu ){ $_ .= "
            <thead>
              <tr data-cab='ton'>
                <th colspan='8'>
                  <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    ".hol::ima("{$est}",$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-16 mar-1" )])."

                    ".( $cab_nom ? "
                      <p><n>{$ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."</p>                      
                    " : "
                      <div>
                        <p class='tit let-4'>
                          <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                        </p>
                        <p class='let-3 mar-1'>
                          ".tex::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                          <br>".tex::let($_lun->ond_man)."
                        </p>                   
                        <p class='let-3 mar-1'>
                          Totem<c>:</c> $_lun->tot
                          <br>Propiedades<c>:</c> ".tex::let($_lun->tot_pro)."
                        </p> 
                      </div>                      
                    " )."
                  </div>
                </th>
              </tr>";
              // agrego plasmas
              if( !$cab_nom ){ $_ .= "
                <tr data-cab='rad'>
                  <th>
                    <span class='tex_ali-der'>Plasma</span>
                    <span class='tex_ali-cen'><c>/</c></span>                    
                    <span class='tex_ali-izq'>Héptada</span>
                  </th>";
                  foreach( hol::_('rad') as $_rad ){ $_ .= "
                    <th>".hol::ima("rad",$_rad,[])."</th>";
                  }$_ .= "                  
                </tr>";
              }$_ .="
            </thead>";
          }
          $dia = 1;    
          $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
          $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
          $ope['eti']='td'; $_ .= "
          <tbody>";
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= "
            <tr class='ite-$arm'>
              <td".ele::atr(ele::val_jun([ 'data-arm'=>$arm, 'data-hep'=>$hep, 'class'=>"sec -hep fon_col-4-{$arm}" ], 
                  isset($ele[$ide = "hep-{$arm}"]) ? $ele[$ide] : []
                )).">";
                if( $cab_ocu || $cab_nom ){
                  $_ .= "<n>$hep</n>";
                }else{
                  $_ .= hol::ima("psi_hep",$hep,[]);
                }$_ .= "
              </td>";
              for( $rad=1; $rad<=7; $rad++ ){
                $_ .= tab::pos('hol','psi',$psi,$ope,$ele);
                $dia++;
                $psi++;
              }
              $hep++; 
              $_ .= "
            </tr>";
          }$_ .= "
          </tbody>
        </table>";
        break;
      // heptada de 7 días
      case 'hep': 
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = hol::_('psi',$val['psi'])->hep;
        
        $_hep = hol::_('psi_hep',$ide);
        ele::cla($ele['hep'],"tab psi {$atr}",'ini');
        $_ = "
        <ul".ele::atr($ele['hep']).">";
          $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
          foreach( hol::_('rad') as $_rad ){
            $_ .= tab::pos('hol','psi',$psi,$ope,$ele);
            $psi++;
          } $_ .= "
        </ul>";        
        break;
      // banco-psi de 8 tzolkin con psi-cronos
      case 'tzo': 
        $_ = "
        <ul".ele::atr($ele['sec']).">";
          $ele_tzo = isset($ele['tzo']) ? $ele['tzo'] : [];
          for( $i=1 ; $i<=8 ; $i++ ){
            $ele['sec'] = $ele_tzo;
            if( isset($ele["tzo-$i"]) ) $ele['sec'] = ele::val_jun($ele['sec'],$ele["tzo-$i"]);
            $_ .= hol::tab('kin','tzo',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    }
    return $_;
  }// Seccion: onda encantada + castillo
  static function tab_sec( string $tip, array $ope=[], array $ele=[] ) : string {
    $_ = "";
    $_tip = explode('_',$tip);
    // fondos: imagen y color
    $ele_ite = isset($ele['fon-ima']) ? $ele['fon-ima'] : [];
    ele::cla($ele_ite,"sec fon-ima ".DIS_OCU,'ini'); $_ .= "
    <{$ope['eti']}".ele::atr($ele_ite)."></{$ope['eti']}>";

    $ele_ite = isset($ele['fon-col']) ? $ele['fon-col'] : [];
    ele::cla($ele_ite,"sec fon-col ".DIS_OCU,'ini'); $_ .= "
    <{$ope['eti']}".ele::atr($ele_ite)."></{$ope['eti']}>";

    // pulsares
    if( in_array($_tip[0],['ton','cas']) ){
      $_pul = [ 'dim'=>'', 'mat'=>'', 'sim'=>'' ];
      if( isset($ope['val']['pos']) ){
        $_val = $ope['val']['pos'];
        if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || is_numeric($_val) ||( is_object($_val) && isset($_val->ide) ) ){
          if( is_numeric($_val) ){
            $_ton = hol::_('ton',num::ran($_val,13));
          }else{
            $_ton = hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
          }
          foreach( $_pul as $i => &$v ){
            if( !empty($ope['opc']["pul_{$i}"]) ) $v = hol::ima("ton_{$i}", $_ton->$i);
          }
        }
      }
    }
    switch( $_tip[0] ){
    // onda
    case 'ton':
      // pulsares
      foreach( $_pul as $ide => $val ){ 
        $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
        ele::cla($ele_ite,"sec fon pul-$ide",'ini'); $_ .= "
        <{$ope['eti']}".ele::atr($ele_ite).">{$val}</{$ope['eti']}>";
      }
      break;
    // castillo
    case 'cas':
      $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : ' dis-ocu';
      $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';
      
      // fondos: imagen
      $ele_pos = isset($ele["fon-ima"]) ? $ele["fon-ima"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_pos;
        ele::cla($ele_ite,"sec fon-ima ond-$i ".DIS_OCU,'ini'); $_ .= "
        <{$ope['eti']}".ele::atr($ele_ite)."></{$ope['eti']}>";
      }
      // fondos: color
      $ele_pos = isset($ele["fon-col"]) ? $ele["fon-col"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_pos;
        ele::cla($ele_ite,"sec fon-col ond-$i fon_col-4-{$i}{$col_ocu}",'ini'); $_ .= "
        <{$ope['eti']}".ele::atr($ele_ite)."></{$ope['eti']}>";
      }        
      // bordes: orbitales
      $ele_pos = isset($ele["orb"]) ? $ele["orb"] : [];
      for( $i = 1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ 
        $ele_ite = $ele_pos;
        ele::cla($ele_ite,"sec fon orb-{$i}{$orb_ocu}",'ini'); $_ .= "
        <{$ope['eti']}".ele::atr($ele_ite)."></{$ope['eti']}>";
      }      
      // fondos: pulsares
      foreach( $_pul as $ide => $val ){ 
        $ele_pos = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
        for( $i = 1; $i <= 4; $i++ ){
          $ele_ite = $ele_pos;
          ele::cla($ele_ite,"sec fon ond-$i pul-{$ide}",'ini'); $_ .= "
          <{$ope['eti']}".ele::atr($ele_ite).">{$val}</{$ope['eti']}>";
        }
      }
      break;      
    }
    return $_;
  }// Posicion: titulos + patrones
  static function tab_pos( string $est, mixed $val, array &$ope, array &$ele ) : string {
    $_ = "";    
    // opciones:
    if( !isset($ope['_val']['kin_pag']) ) $ope['_val']['kin_pag'] = !empty($ope['pag']['kin']);
    if( !isset($ope['_val']['psi_pag']) ) $ope['_val']['psi_pag'] = !empty($ope['pag']['psi']);
    if( !isset($ope['_val']['sec_par']) ) $ope['_val']['sec_par'] = !empty($ope['sec']['par']);
    $_val = $ope['_val'];
    // armo titulos y cargo operadores
    $cla_agr = [];
    $pos_tit = [];
    if( isset($ele["data-fec_dat"]) ){
      $pos_tit []= "Calendario: {$ele["data-fec_dat"]}";
    }
    if( isset($ele["data-hol_kin"]) ){
      $_kin = hol::_('kin',$ele["data-hol_kin"]);
      $pos_tit []= dat::val('tit',"hol.kin",$_kin);
      if( $_val['kin_pag'] && !empty($_kin->pag) ) $cla_agr []= "_hol-pag_kin";
    }
    if( isset($ele["data-hol_sel"]) ){
      $pos_tit []= dat::val('tit',"hol.sel",$ele["data-hol_sel"]);
    }
    if( isset($ele["data-hol_ton"]) ){
      $pos_tit []= dat::val('tit',"hol.ton",$ele["data-hol_ton"]);
    }
    if( isset($ele["data-hol_psi"]) ){
      $_psi = hol::_('psi',$ele["data-hol_psi"]);
      $pos_tit []= dat::val('tit',"hol.psi",$_psi);          
      if( $_val['psi_pag'] ){
        $_psi->tzo = hol::_('kin',$_psi->tzo);
        if( !empty($_psi->tzo->pag) ) $cla_agr []= "_hol-pag_psi";
      }
    }
    if( isset($ele["data-hol_rad"]) ){
      $pos_tit []= dat::val('tit',"hol.rad",$ele["data-hol_rad"]);
    }
    if( !empty($pos_tit) ){
      $ele['title'] = implode("\n\n",$pos_tit);
    }
    if( !empty($cla_agr) ) ele::cla($ele,implode(' ',$cla_agr));

    // por patrones: posicion por dependencia
    if( !!$_val['sec_par'] ){

      $ele_sec = $ele;
      if( isset($ele_sec['class']) ) unset($ele_sec['class']);
      if( isset($ele_sec['style']) ) unset($ele_sec['style']);      

      ele::cla($ele,'dep');
      
      $_ = hol::tab($est,'par',[
        'ide' => $val,
        'sec' => [ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal
        'pos' => [ 'ima'=>isset($par_ima) ? $par_ima : "hol.{$est}.ide" ]
      ],[
        'sec'=>$ele_sec
      ]);
    }

    return $_;
  }
}