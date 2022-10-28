<?php

  // holon : ns.ani.lun.dia:kin
  class _hol {

    // estructuras por posicion
    static function _( string $ide, mixed $val = NULL ) : string | array | object {
      global $_api;
      $_ = [];
      // aseguro carga      
      $est = "hol_$ide";
      if( !isset($_api->$est) ) $_api->$est = _dat::ini(DAT_ESQ,$est);
      // busco dato
      if( !empty($val) ){
        $_ = $val;
        if( !is_object($val) ){
          switch( $ide ){
          case 'fec':
            $fec = _api::_('fec',$val);
            if( isset($fec->dia)  ) $_ = _dat::get( _hol::_('psi'), [ 'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 'opc'=>['uni'] ]);
            break;
          case 'kin':
            $_ = $_api->$est[ _num::ran( _num::sum($val), 260 ) - 1 ];
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
      switch( $ide ){
      case 'kin': 
        foreach( _hol::_('kin') as $kin => $_kin ){
          $sel = _hol::_('sel',$_kin->arm_tra_dia);
          $cel = _hol::_('sel_arm_cel',$sel->arm_cel);
          $ton = _hol::_('ton',$_kin->nav_ond_dia);      
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
            \nSello {$cel->nom} "._tex::art_del($sel->pod)." con el tono {$ton->nom} "._tex::art_del($ton->pod).". ";
          $enc .= "\nMe guía ";
          if( $ton->pul_mat == 1 ){
            $enc .= "mi propio Poder duplicado. ";
          }else{
            $gui = _hol::_('sel', _hol::_('kin',$_kin->par_gui)->arm_tra_dia );
            $enc .= " el poder "._tex::art_del($gui->pod).".";
          }
          if( in_array($kin+1, $_kin->val_est) ){
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
            $_arm = _hol::_('kin_cro_ond',_hol::_('ton',$_ele['ton'])->ond_arm);
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
      case 'kin_fac':
        $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
        $_add = [ '052','130','208' ];
        $fac_ini = -3113;
        foreach( _hol::_('kin') as $_kin ){    
    
          $fac_fin = $fac_ini + $_lim[intval($_kin->arm_tra_dia)-1];
    
          if( in_array($_kin->ide,$_add) ){
            $fac_fin ++;
          }
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `fac_ini` = $fac_ini, `fac_fin` = $fac_fin, `fac_ran` = '"._fec::ran($fac_ini,$fac_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $fac_ini = $fac_fin;
    
        }
        break;
      case 'kin_enc':
    
        $enc_ini = -26000;    
        foreach( _hol::_('kin') as $_kin ){    
    
          $enc_fin = $enc_ini + 100;
    
          $_ .= "
          UPDATE `_hol`.`_kin` 
            SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '"._fec::ran($enc_ini,$enc_fin)."' 
            WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $enc_ini = $enc_fin;
    
        }
        break;
      case 'kin_cro_ele':
        foreach( _hol::_('kin_cro_ele') as $_ele ){
          $_cas = _hol::_('cas',$_ele->ide);
          $_est = _hol::_('kin_cro_est',$_cas->arm);
          $_ton = _hol::_('ton',$_ele->ton);
          $_ .= "
          UPDATE `_hol``.`kin_cro_ele` 
            SET `des` = '$_ton->des del Espectro Galáctico "._tex::let_ora($_est->col)."'
          WHERE `ide` = $_ele->ide;<br>";
        }
        break;
      }
      return $_;
    }
    // convierto NS => d/m/a
    static function cod( array | string $val ) : bool | string {
      $_ = FALSE;

      if( is_string($val) ) $val = explode('.',$val);

      if( isset($val[3]) ){

        $sir = intval($val[0]);
        $ani = intval($val[1]);
        $lun = intval($val[2]);
        $dia = intval($val[3]);

        // mes y día
        $_psi = _dat::get( _hol::_('psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);
    
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

      $_ = !is_object($val) ? _fec::dat($val) : $val ;

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

              $_->$atr = _num::ran($_->$atr+105, 260); 
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
              
              $_->$atr = _num::ran($_->$atr-105, 260); 
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

            $_->$atr = _num::ran($_->$atr-105, 260); 
          }
        }
      }
      else{
        $_ = "{-_-} la Fecha {$val} no es Válida"; 
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
          $_ = _hol::val($fec);
          if( is_string($_) ){ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
          }
        }
        // decodifico N.S.( cod.ani.lun.dia:kin )
        elseif( $tip == 'sin' ){
          // busco año          
          if( $_fec = _hol::cod($val) ){

            $_ = _hol::val($_fec);

            if( is_string($_) ) $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
          }
          else{ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
          }
        }
      }
      // armo datos de una fecha
      elseif( $fec = _fec::dat($val) ){
        // giro solar => año
        $_['fec'] = $fec->val;

        $_fec = _hol::dec($fec);

        // giro lunar => mes + día
        if( $_psi = _hol::_('fec',$_['fec']) ){

          $_['psi'] = $_psi->ide;

          $_['sin'] = "{$_fec->sir}."._num::val($_fec->ani,2).".{$_psi->lun}.{$_psi->lun_dia}";
          
          // giro galáctico => kin
          $_kin = _hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);

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
              $lun += _num::red($cue / 28);
              $dia = _num::ran($dia, 28);
              
              if( $lun > 13 ){
                $ani += _num::red($lun / 13);
                $lun = _num::ran($lun, 13);
    
                if( $ani > 51 ){
                  $sir += _num::red($ani / 51);
                  $ani = _num::ran($ani, 51, 0);
                }
              }
            }
          }
          elseif( $tip == '-' ){
    
            $dia -= $cue;        
    
            if( $dia < 1 ){
              $lun -= _num::red($cue / 28);
              $dia = _num::ran($dia, 28);
              
              if( $lun < 1 ){    
                $ani -= _num::red($lun / 13);
                $lun = _num::ran($lun, 13);
    
                if( $ani < 0 ){    
                  $sir -= _num::red($ani / 51);
                  $ani = _num::ran($ani, 51, 0);
                }
              }
            }
          }        
          break;
        case 'lun': 
          if( $tip == '+' ){
  
            $lun += $cue;
              
            if( $lun > 13 ){
              $ani += _num::red($lun / 13);
              $lun = _num::ran($lun, 13);
  
              if( $ani > 51 ){  
                $sir += _num::red($ani / 51);
                $ani = _num::ran($ani, 51, 0);                
              }
            }
          }
          elseif( $tip == '-' ){
  
            $lun -= $cue;
              
            if( $lun < 1 ){  
              $ani -= _num::red($lun / 13);
              $lun = _num::ran($lun, 13);
  
              if( $ani < 0 ){
                $sir -= _num::red($ani / 51);
                $ani = _num::ran($ani, 51, 0);
              }
            }
          }        
          break;
        case 'ani': 
          if( $tip == '+' ){
  
            $ani += $cue;
  
            if( $ani > 51 ){
              $sir += _num::red($ani / 51);
              $ani = _num::ran($ani, 51, 0);
            }
          }
          elseif( $tip == '-' ){
  
            $ani -= $cue;
  
            if( $ani < 0 ){
              $sir -= _num::red($ani / 51);
              $ani = _num::ran($ani, 51, 0);
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

        $_ = "$sir."._num::val($ani,2)."."._num::val($lun,2)."."._num::val($dia,2);
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

        $fec = _fec::ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
      }
      // x 364+1 dias por psi-cronos
      elseif( $est == 'psi' && isset($dat['psi']) && isset($dat['fec']) ){

        $cue = 364;

        $fec = _fec::ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
      }

      if( isset($fec) ){
    
        for( $pos = 0; $pos < $cue; $pos++ ){

          $_dat = _hol::val($fec);

          $_ []= _app::val([
            'fec'=>[ 
              'dat'=>_fec::_('dat',$fec),
            ],
            'hol'=>[               
              'kin'=>_hol::_('kin',$_dat['kin']), 
              'psi'=>_hol::_('psi',$_dat['psi']) 
            ]
          ]);

          $fec = _fec::ope($fec, $inc, $val);
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
        
        $_val = _hol::val($val,'sin');

        $_cas = _hol::_('cas',$cic_año);
        
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

            $_val_lun = _hol::val($val_lun,'sin');
  
            $_cic_lun = new stdClass;  
            $_cic_lun->ani = $cic_año;
            $_cic_lun->ide = $cic_mes;
            $_cic_lun->fec = $_val_lun['fec'];
            $_cic_lun->sin = $_val_lun['sin'];
            $_cic_lun->kin = $_val_lun['kin'];
            
            $_cic_año->lun []= $_cic_lun;
            // incremento 1 luna
            $val_lun = _hol::ope('+',$val_lun,1,'lun');            
          }
        }        
        $_ []= $_cic_año;
        // incremento 1 anillo      
        $val = _hol::ope('+',$val,1,'ani');
      }

      return $_;
    }
    // armo imagen
    static function ima( string $est, mixed $dat, array $ele = [] ) : string {
      
      return _app::ima('hol',"$est",$dat,$ele);
    }
  }
  // Valor
  class _hol_val {
    static string $IDE = "_hol_val-";
    static string $EJE = "_hol_val.";

    // Fecha + ns:kin
    static function fec( array $dat, array $ope ) : string {

      $_eje =  !empty($ope['eje']) ? $ope['eje'] : self::$EJE."fec";

      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : _hol::_('kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : _hol::_('psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      

      $_ = "
      <!-- Fecha del Calendario -->
      <form class='val mar-1' ide='fec'>

        "._app::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol_val-fec", 'class'=>"mar_hor-1", 
          'title'=>"Desde aquí puedes cambiar la fecha..." 
        ])."
        "._app_fec::ope('dia', $_fec, [ 'id'=>"hol_val-fec", 'name'=>"fec", 
          'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
        ])."
        "._app::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);", 
          'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
        ])."

      </form>

      <!-- Fecha del Sincronario -->
      <form class='val mar-1' ide='sin'>
        
        <label>N<c>.</c>S<c>.</c></label>

        "._app_num::ope('int', $_sin[0], [ 
          'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
        ])."
        <c>.</c>
        "._app_opc::val( _hol::_('ani'), [
          'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
          'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        "._app_opc::val( _hol::_('psi_lun'), [
          'eti'=>[ 'name'=>"lun", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
          'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        "._app_opc::val( _hol::_('lun'), [ 
          'eti'=>[ 'name'=>"dia", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
          'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
        ])."          
        <c class='sep'>:</c>
    
        <n name='kin'>$_kin->ide</n>

        "._hol::ima("kin",$_kin,['class'=>"mar_hor-1", 'style'=>'min-width:2.5rem; height:2.5rem;'])."

        "._app::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje(this);",
          'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
        ])."

      </form>";

      return $_;
    }
  }
  // Informe
  class _hol_inf {

    static string $IDE = "_hol_inf-";
    static string $EJE = "_hol_inf.";
    static array $OPE = [
      'kin' => [ 'ide'=>"kin", 'nom'=>"Orden Sincrónico", 'des'=>"" ],
      'psi' => [ 'ide'=>"psi", 'nom'=>"Orden Cíclico",    'des'=>"" ]
    ];

    static function kin( mixed $dat, array $ope = [] ) : string {
      $_ = [];
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_kin = _hol::_('kin',$dat);
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      $_kin_atr = _dat::atr('hol',"kin");
      
      $_est = [
        'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
        'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
        'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
        'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
        'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
        'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ]
      ];
      
      $_[0] = [ 'ite'=>"Nave del Tiempo", 'lis'=>[] ];
      foreach( [ 'nav_cas'=>52, 'nav_ond'=>13 ] as $atr => $cue ){ 
        $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
        $_[0]['lis'] []= 
        
        _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

        <div class='tam-cre'>
          <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
          <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
          <p>"._app_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>            
        </div>";          
      }        

      $_[1] = [ 'ite'=>"Giro Galáctico", 'lis'=>[] ];
      foreach( [ 'arm_tra'=>13, 'arm_tra_dia'=>20, 'arm_cel'=>65, 'arm_cel_dia'=>4 ] as $atr => $cue ){ 
        $est = isset($_est[$atr]['est']) ? $_est[$atr]['est'] : "kin_$atr"; 
        $_dat = _hol::_($est,$_kin->$atr); 
        $_[1]['lis'] []= 
        
        _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

        <div class='tam-cre'>
          <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
          <p>"._app::let( _app_dat::val('des',"hol.{$est}",$_dat) )."</p>
          <p>"._app_num::ope('ran',$_kin->$atr,[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>          
        </div>";
      }

      $_[2] = [ 'ite'=>"Giro Espectral", 'lis'=>[] ];
      foreach( [ 'cro_est'=>65, 'cro_ele'=>5 ] as $atr => $cue ){ 
        $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
        
        $_[2]['lis'] []= 
        
          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

          <div class='tam-cre'>
            <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
            <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
            <p>"._app_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>
          </div>          
        ";          
      } 

      $_[3] = [ 'ite'=>"Holon Solar", 'lis'=>[] ];
      foreach( ['sol_pla','sol_cel','sol_cir'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[3]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
          </div>              
        ";          
      }

      $_[4] = [ 'ite'=>"Holon Planetario", 'lis'=>[] ];
      foreach( ['pla_cen','pla_hem','pla_mer'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[4]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
          </div>              
        ";
      }

      $_[5] = [ 'ite'=>"Holon Humano", 'lis'=>[] ];
      foreach( ['hum_cen','hum_ext','hum_ded','hum_mer'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[5]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
          </div>              
        ";
      }              

      $ope['lis-1'] = [ 'class'=>"ite" ];
      return _app_lis::val($_,$ope);
    }    
    static function psi( mixed $dat, array $ope = [] ) : string {
      $_ = [];
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_psi = _hol::_('psi',$dat);
      $_lun = _hol::_('lun',$_psi->lun);
      $_rad = _hol::_('rad',$_psi->hep_dia);
      $ope['lis']['ide'] = 'psi';         

      $_[0] = [ 'ite'=>"Estación Solar", 'lis'=>[] ];
      $_est = _hol::_('psi_est',$_psi->est); 
      $_[0]['lis'] []= 
        
        _hol::ima("psi_est",$_est,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>"; 
      $_hep = _hol::_('psi_hep',$_psi->hep); 
      $_[0]['lis'] []= 
        
        _hol::ima("psi_hep",$_hep,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";

      $_[1] = [ 'ite'=>"Giro Lunar", 'lis'=>[] ];
      $_lun = _hol::_('psi_lun',$_psi->lun); 
      $_[1]['lis'] []= 
        
        _hol::ima("psi_lun",$_lun,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>";
      $_arm = _hol::_('lun_arm',_num::ran($_psi->hep,'4')); 
      $_[1]['lis'] []= 
        
        _hol::ima("lun_arm",$_arm,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";

      $_[2] = [ 'ite'=>"Héptada", 'lis'=>[] ];
      $_rad = _hol::_('rad',$_psi->hep_dia);
      $_[2]['lis'] []= 
        
        _hol::ima("rad",$_rad,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";          

      $ope['lis-1'] = [ 'class'=>"ite" ];
      return _app_lis::val($_,$ope);
    }
  }
  // Ficha
  class _hol_fic {
    
    // usuario
    static function usu( array $ope = [], ...$opc ) : string {
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
    static function ton( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      $_ide = explode('-',$atr);
      $dat = _hol::_('ton',$val);
      switch( $_ide[0] ){
      case 'atr':
        $htm_ini = isset($ope['htm_ini']) ? _app::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._app::let($ope['htm_fin']) : "";        
        $_ = "

        <p class='des'>{$htm_ini}Tono <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->nom}<c>.</c>{$htm_fin}</p>
        
        <div class='val jus-cen'>
  
          "._hol::ima("ton",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::atr($dat,[ 
            'est'=>"hol_ton", 
            'atr'=>isset($ope['atr']) ? $ope['atr'] : ['car','pod','acc']
          ])."
  
        </div>";
        break;
      }
      return $_;
    }
    static function sel( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      $_ide = explode('-',$atr);
      $dat = _hol::_('sel',$val);
      switch( $_ide[0] ){
      case 'atr': 
        $htm_ini = isset($ope['htm_ini']) ? _app::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._app::let($ope['htm_fin']) : "";
        $_ = "
        <p class='des'>{$htm_ini}Sello <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}<c>.</c>{$htm_fin}</p>
  
        <div class='val jus-cen'>
  
          "._hol::ima("sel",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::atr($dat,[
            'est'=>"hol_sel", 
            'atr'=>isset($ope['atr']) ? $ope['atr'] : ['car','acc','pod'] 
          ])."
        </div>";          
        break;
      }
      return $_;
    }    
    static function kin( string | array $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";
      $_ide = explode('-',$atr);
      $dat = _hol::_('kin',$val);
      switch( $_ide[0] ){
      case 'atr':
        $_ = "
        
        <p class='des'>Kin <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}<c>.</c></p>
  
        <div class='val jus-cen'>
  
          "._hol::ima("kin",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::atr($dat,[
            'est'=>"hol_kin", 
            'atr'=>isset($ope['atr']) ? $ope['atr'] : [] 
          ])."
        </div>";

        if( isset($ope['ima']) ) $_ .= _app_dat::ima('api',"hol_kin",$ope['ima'],$dat);

        break;
      // libro del kin
      case 'enc':
        $htm_ini = isset($ope['htm_ini']) ? _app::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._app::let($ope['htm_fin']) : "";
        
        $_ = "
        <p class='des'>
          {$htm_ini}Kin <c>#</c><n>{$dat->ide}</n><c>:</c> "._app::let($dat->nom)."<c>.</c>{$htm_fin}
        </p>
  
        <div class='val jus-cen mar-1'>
      
          "._hol::ima("kin",$dat,['class'=>"mar_der-2"])."
  
          <q>"._app::let("{$dat->des}")."</q>
  
        </div>";

        if( isset($ope['ima']) ) $_ .= _app_dat::ima('api',"hol_kin",$ope['ima'],$dat);

        break;
      // parejas del oráculo
      case 'par':
        if( empty($_ide[1]) ){
          $_ ="
          <div class='lis'>";
          foreach( _hol::_('sel_par') as $_par ){
            
            $ide = $_par->ide;
  
            $par_ide = "par_{$ide}";
            $atr_ide = ( $ide=='des' ) ? 'ide' : $par_ide;
      
            // busco datos de parejas
            $_par = _dat::get(_hol::_('sel_par'),[ 'ver'=>[ ['ide','==',$ide] ], 'opc'=>'uni' ]);
            $kin = _hol::_('kin',$dat->$atr_ide);
            $_ .= "
      
            <p class='mar_arr-2 tex_ali-izq'>
              <b class='ide let-sub'>{$_par->nom}</b><c>:</c>
              <br><q>"._app::let($_par->des)."</q>
              ".( !empty($_par->lec) ? "<br><q>"._app::let($_par->lec)."</q>" : "" )."
            </p>
            
            "._hol_fic::kin('enc',$kin)
            ;
    
          } $_ .= "
          </div>";
        }else{
          switch( $_ide[1] ){
          // lecturas por parejas
          case 'lec':

            $_lis = [];
            $_des_sel = _hol::_('sel',$dat->arm_tra_dia);

            foreach( _hol::_('sel_par') as $_par ){

              if( $_par->ide == 'des' ) continue;

              $_kin = _hol::_('kin',$dat->{"par_{$_par->ide}"});
              $_sel = _hol::_('sel',$_kin->arm_tra_dia);

              $_lis []=
                _hol::ima("kin",$_kin)."

                <div>
                  <p><b class='tit'>{$_kin->nom}</b> <c>(</c> "._app::let($_par->dia)." <c>)</c></p>
                  <p>"._app::let("{$_sel->acc} {$_par->pod} {$_sel->car}, que {$_par->mis} {$_des_sel->car}, {$_par->acc} {$_sel->pod}.")."</p>
                </div>";
            }
            
            if( !isset($ope['lis']) ) $ope['lis']=[];

            _ele::cla($ope['lis'],'ite');
            
            $_ = _app_lis::val($_lis,$ope);          
            break;

          }
        }
        break;
      // castillo de la nave
      case 'nav_cas': 
        $_ = "
        <div class='val jus-cen'>
            
          "._hol::ima("kin_nav_cas",$dat,['style'=>'margin: 0 2em;'])."
  
          <ul>
            <li><b class='ide'>Corte</b><c>:</c> {$dat->cor}</li>
            <li><b class='ide'>Poder</b><c>:</c> {$dat->pod}</li>
            <li><b class='ide'>Acción</b><c>:</c> {$dat->acc}</li>
          </ul>
  
        </div>";               
        break;
      // etapa evolutiva - estacion galáctica
      case 'cro_est':
        $_ = "
        <div class='val jus-cen'>

          "._hol::ima("kin_cro_est",$dat,['class'=>"mar_der-2"])."

          <ul>
            <li><p><b class='ide'>Guardían</b><c>:</c> {$dat->may}</p></li>
            <li><p><b class='ide'>Etapa Evolutiva</b><c>:</c> "._app::let("{$dat->nom}, {$dat->des}")."</p></li>
          </ul>

        </div>";          
        break;
      // baktún - trayectoria armónica
      case 'arm_tra': 
        $_ = "
        <div class='val jus-cen'>
          
          "._hol::ima("kin_arm_tra",$dat,['class'=>"mar_der-2"])."

          <ul>
            <li><p><b class='ide'>Baktún</b><c>:</c> {$dat->may}</p></li>
            <li><p><b class='ide'>Período</b><c>:</c> {$dat->ran}</p></li>
          </ul>

        </div>";          
        break;        
      }
      return $_;
    }
  }
  // Tablero
  class _hol_tab {
    
    // armo tablero
    static function _dat( string $est, string $atr, array $ope = [], array $ele = [] ) : array {

      $_ = [ 
        'esq'=>"hol", 
        'ide'=>$est, 
        'est'=> $est = $est.( !empty($atr) ? "_$atr" : $atr ) 
      ];

      // cargo elementos del tablero
      $ele = _app::tab('hol',$est,$ele);      
      foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }

      // opciones
      $opc = isset($ope['opc']) ? $ope['opc'] : [];

      // operador de opciones
      if( !empty($ope['pos']['bor']) ) _ele::cla($ele['pos'],"bor-1");
      
      // identificadores de datos
      if( is_object( $ide = !empty($ope['ide']) ? $ope['ide'] : 0 ) ) $ide = $ide->ide;

      // valor por posicion 
      $val = NULL;
      if( !empty($ope['val']['pos']) ){
        $val = $ope['val']['pos'];
        if( is_object($val) ){
          if( isset($val->ide) ) $val = intval($val->ide);       
        }
        else{
          $val = is_numeric($val) ? intval($val) : $val;
        }
      }
      
      $_['ide'] = $ide;
      $_['val'] = $val;
      $_['ope'] = $ope;
      $_['ele'] = $ele;
      $_['opc'] = $opc;

      return $_;     

    }// Seccion: onda encantada + castillo => fondos + pulsares + orbitales
    static function _sec( string $tip, array $ope=[], array $ele=[] ) : string {
      $_ = "";

      $_tip = explode('_',$tip);

      $_tab = _app::tab('hol',$_tip[0])->ele;

      $_pul = ['dim'=>'','mat'=>'','sim'=>''];

      // opciones por seccion
      $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : 'dis-ocu';
      $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';

      // pulsares por posicion
      if( in_array($_tip[0],['ton','cas']) && isset($ope['val']['pos']) ){
        
        $_val = $ope['val']['pos'];

        if( 
          ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) 
          || 
          ( is_object($_val) && isset($_val->ide) ) 
        ){

          $_ton = _hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
            
          foreach( $_pul as $i => $v ){
            
            if( !empty($ope['opc']["ton-pul_{$i}"]) ){
              $_pul[$i] = _hol::ima("ton_pul_[$i]", $_ton["pul_{$i}"], ['class'=>'fon'] );
            }
          }
        }
      }

      switch( $_tip[0] ){
      // onda encantada
      case 'ton':
        // fondo: imagen
        $_ .= "
        <div"._htm::atr(_ele::jun($_tab['ima'],[ 'class'=>DIS_OCU ])).">
        </div>";
        // fondos: color
        $_ .= "
        <div"._htm::atr(_ele::jun($_tab['fon'],[ 'class'=>"{$col_ocu}" ])).">
        </div>";
        // pulsares
        foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab['ond'],[ 'data-pul'=>$ide ])).">
            {$_pul[$ide]}
          </div>";
        }
        break;
      // castillo del destino
      case 'cas':
        // fondos: imagen
        for( $i = 1; $i <= 4; $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab["ond-{$i}"],[ $_tab['ima'], [ 'class'=>DIS_OCU ] ])).">
          </div>";
        }
        // fondos: color
        for( $i = 1; $i <= 4; $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab["ond-{$i}"],[ $_tab['fon'], [ 'class'=>"fon_col-4-{$i}{$col_ocu}" ] ])).">
          </div>";
        }        
        // bordes: orbitales
        for( $i=1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun(['class'=>$orb_ocu ],[ $_tab['orb'], $_tab["orb-{$i}"] ])).">
          </div>";
        }        
        // fondos: pulsares
        for( $i=1; $i<=4; $i++ ){
          foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
            <div"._htm::atr(_ele::jun($_tab['ond'],[ [ 'data-pul'=>$ide ] , $_tab["ond-{$i}"] ])).">
              {$_pul[$ide]}
            </div>";
          }
        }
        break;      
      }
      return $_;
    }// Posicion: datos + titulos + contenido[ ima, num, tex]
    static function _pos( string $est, mixed $val, array &$ope, array $ele ) : string {
      $esq = 'hol';

      // recibo objeto o identificador
      $val_ide = $val;
      if( is_object($val) ){
        $_dat = $val;
        $val_ide = intval($_dat->ide);
      }
      else{
        $_dat = _hol::_($est,$val);
      }

      // seccion
      $_val['sec_par'] = !empty($ope['sec']['par']) ? 'sec_par' : FALSE;
      // posicion
      $_val['pos_dep'] = !empty($ope['sec']['pos_dep']);// patrones
      $_val['pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
      $_val['pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen
      
      //////////////////////////////////////////////////////////////////////////
      // cargo datos ///////////////////////////////////////////////////////////

        $e = isset($ele['pos']) ? $ele['pos'] : [];      
        // por acumulados
        if( isset($ope['dat']) ){

          foreach( $ope['dat'] as $pos => $_ref ){

            if( isset($_ref["{$esq}_{$est}"]) && intval($_ref["{$esq}_{$est}"]) == $val_ide ){

              foreach( $_ref as $ref => $ref_dat ){

                $e["data-{$ref}"] = $ref_dat;
              }            
              break;
            }
          }
        }
        // por dependencias estructura
        else{
          if( !empty( $dat_est = _app::dat($esq,$est,'rel') ) ){

            foreach( $dat_est as $atr => $ref ){

              if( empty($e["data-{$ref}"]) ){

                $e["data-{$ref}"] = $_dat->$atr;
              }        
            }
          }// pos posicion
          elseif( empty($e["data-{$esq}_{$est}"]) ){    
            $e["data-{$esq}_{$est}"] = $_dat->ide;
          }
        }    
      //////////////////////////////////////////////////////////////////////////
      // posiciones del tablero principal //////////////////////////////////////    

        $agr = "";    
        // omito dependencias
        if( !$_val['pos_dep'] ){

          $agr = "pos";

          if( $_val['sec_par'] ){ 
            $agr .= !empty($agr) ? ' ': '';
            $agr .= $_val['sec_par']; 
            $par_ima = !empty($_val['pos_ima']) ? $_val['pos_ima'] : "{$esq}.{$est}.ide";
          }

          if( isset($ope['val']['pos']) ){

            $dat_ide = $ope['val']['pos'];

            if( is_array($dat_ide) && isset($dat_ide[$est]) ){
              $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
            }

            if( $_dat->ide == $dat_ide ){
              $agr .= !empty($agr) ? ' ': ''; 
              $agr .= '_val-pos _val-pos-bor';
            }
          }
        }
      //////////////////////////////////////////////////////////////////////////    
      // armo titulos //////////////////////////////////////////////////////////
        $pos_tit = [];
        if( isset($e["data-fec_dat"]) ){
          $pos_tit []= "Calendario: {$e["data-fec_dat"]}";
        }
        if( isset($e["data-hol_kin"]) ){
          $_kin = _hol::_('kin',$e["data-hol_kin"]);
          $pos_tit []= _app_dat::val('tit',"hol.kin",$_kin);
        }
        if( isset($e["data-hol_sel"]) ){
          $pos_tit []= _app_dat::val('tit',"hol.sel",$e["data-hol_sel"]);
        }
        if( isset($e["data-hol_ton"]) ){
          $pos_tit []= _app_dat::val('tit',"hol.ton",$e["data-hol_ton"]);
        }
        if( isset($e["data-hol_psi"]) ){
          $_psi = _hol::_('psi',$e["data-hol_psi"]);
          $pos_tit []= _app_dat::val('tit',"hol.psi",$_psi);
        }
        if( isset($e["data-hol_rad"]) ){
          $pos_tit []= _app_dat::val('tit',"hol.rad",$e["data-hol_rad"]);
        }
        $e['title'] = implode("\n\n",$pos_tit);

      //////////////////////////////////////////////////////////////////////////
      // Contenido html ////////////////////////////////////////////////////////
        // clases adicionales
        if( !empty($agr) ){ _ele::cla($e,$agr,'ini'); }

        $htm = ""; 
        // por patrones: posicion por dependencia
        if( !empty($_dat) && !!$_val['sec_par'] ){

          $ele_sec = $e;

          if( isset($ele_sec['class']) ){
            unset($ele_sec['class']);
          }
          if( isset($ele_sec['style']) ){ 
            unset($ele_sec['style']);
          }
          
          // $ope['sec']['par'] = $ope['sec']['par'] - 1;

          $htm = _hol_tab::$est('par',[
            'ide'=>$_dat,
            'sec'=>[ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal ( [pos].pos )
            'pos'=>[ 'ima'=>isset($par_ima) ? $par_ima : "hol.{$est}.ide" ]
          ],[
            'sec'=>$ele_sec
          ]);

        }
        // genero posicion
        elseif( !empty($_dat) ){
          // color de fondo
          if( $_val['pos_col'] ){            
            $_ide = _dat::ide($_val['pos_col']);
            if( 
              isset($e["data-{$_ide['esq']}_{$_ide['est']}"]) 
              && 
              !empty( $_dat = _dat::get($_ide['esq'],$_ide['est'],$e["data-{$_ide['esq']}_{$_ide['est']}"]) ) 
            ){
              $col = _dat::opc('col', ...explode('.',$_val['pos_col']));
              if( isset($col['val']) ){
                $col = $col['val'];
                $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
                _ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : _num::ran($val,$col) ) );
              }              
            }
          }
          // contenido
          foreach( ['ima','num','tex','fec'] as $tip ){

            if( !empty($ope['pos'][$tip]) ){                        
              $ide = _dat::ide($ope['pos'][$tip]);

              $htm .= _app_dat::ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
            }
          }
        }
        // agrego posicion automatica-incremental
        if( !$_val['pos_dep'] ){

          if( !isset($e['pos']) ){
            if( empty($ope['_tab_pos']) ){
              $ope['_tab_pos'] = 0;
            }
            $ope['_tab_pos']++;
            $e['pos'] = $ope['_tab_pos'];
          }
        }
      //////////////////////////////////////////////////////////////////////////
      // devuelvo posicion /////////////////////////////////////////////////////
      
      $pos_eti = isset($ope['eti']) ? $ope['eti'] : 'div';

      return "
      <{$pos_eti}"._htm::atr($e).">
        {$htm}
      </{$pos_eti}>";
    }

    // por estructuras:
    static function rad( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('rad',$atr,$ope,$ele) );

      return $_;
    }
    static function ton( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('ton',$atr,$ope,$ele) );
      $_tab = _app::tab('hol','ton')->ele;
      $_ .= "
      <div"._htm::atr(_ele::jun($ele['sec'],$_tab['sec'])).">
        <div fon='ima'></div>
        "._hol_tab::_sec('ton',$ope)
        ;
        $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

        foreach( _hol::_('ton') as $_ton ){
          $i = "pos-{$_ton->ide}";
          $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
          $_ .= _hol_tab::_pos('ton',$_ton,$ope,$ele);
        } $_ .= "
      </div>";
      return $_;
    }
    static function sel( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('sel',$atr,$ope) );

      switch( $atr ){
      // codigo
      case 'cod': 
        $_ = "
        <div"._htm::atr($ele['sec']).">";
          foreach( _hol::_('sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
            $_ .= "
            <div class='sec{$agr}'>
              <ul class='val jus-cen'>
                "._hol::ima("sel",$_sel,['eti'=>"li"])."
                "._hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='mar-0 ali_pro-cen'>
                {$_sel->arm}
                <br>{$_sel->acc}
                <br>{$_sel->pod}
              </p>
            </div>";
          } $_ .= "
        </div>";        
        break;
      // colocacion cromática
      case 'cro':

        $e = $ele['sec'];

        _ele::cla($e,"sel");         
        $_ = "
        <div"._htm::atr($e).">
          <div pos='0'></div>";
          foreach( _hol::_('sel_cro_fam') as $_dep ){ 
            $_ .= _hol::ima("sel_cro_fam",$_dep,['fam'=>$_dep->ide]);
          } 
          foreach( _hol::_('sel_cro_ele') as $_dep ){ 
            $_ .= _hol::ima("sel_cro_ele",$_dep,['ele'=>$_dep->ide]);
          }
          for( $i=0; $i<=19; $i++ ){ 
            $_sel = _hol::_('sel', ( $i == 0 ) ? 20 : $i);
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
            $e = $ele['pos'];
            _ele::cla($e,"pos{$agr}");             
            $e['pos'] = $_sel->ide;
            $e['hol-sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['_eje']) ? $ele['pos']['_eje'] : "" ])."
            </div>";
          } $_ .= "
        </div>";        
        break;
      // colocacion armónica
      case 'arm':
        $_ .= "
        <div"._htm::atr($ele['sec']).">
          <div pos='0'></div>
          ";
          foreach( _hol::_('sel_arm_cel') as $_dep ){ 
            $_ .= _hol::ima("sel_arm_cel",$_dep,['cel'=>$_dep->ide]);
          } 
          foreach( _hol::_('sel_arm_raz') as $_dep ){ 
            $_ .= _hol::ima("sel_arm_raz",$_dep,['raz'=>$_dep->ide]);
          }
          foreach( _hol::_('sel') as $_sel ){
            $agr = ( !empty($ide) && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
            $e = $ele['pos'];
            _ele::cla($e,"pos{$agr}"); 
            $e['pos'] = $_sel->ide;
            $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['onclick']) ? $ele['pos']['onclick'] : NULL ])."
            </div>";
          }
          $_ .= "
        </div>";        
        break;
      // tablero del oráculo
      case 'arm_tra': 
        $_ .= "
        <div"._htm::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){             
            $ope['ide'] = $i;
            $_ .= _hol_tab::kin('arm_cel',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      // célula del tiempo para el oráculo
      case 'arm_cel':
        
        $_arm = _hol::_('sel_arm_cel',$ide);
        
        $e = isset($ope['cel']) ? $ope['cel'] : [];

        _ele::cla($e,"sel");
        $e['title'] = _app_dat::val('tit',"hol.{$est}",$_arm); $_ = "
        <div"._htm::atr($e).">
          <div pos='00'>
            "._hol::ima("sel_arm_cel", $_arm, ['htm'=>$_arm->ide,'class'=>'ima'] )."
          </div>
          ";
          foreach( _hol::_('sel_arm_raz') as $_raz ){
            $_.= _hol_tab::_pos('sel',$sel,$ope,$ele);
            $sel++;
          } $_ .= "
        </div>";        
        break;
      }
      return $_;
    }  
    static function lun( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('lun',$atr,$ope,$ele) );

      return $_;
    }
    static function cas( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('cas',$atr,$ope,$ele) );

      $_tab = _app::tab('hol','cas')->ele;

      $_ = "
      <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
        <div fon='ima'></div>
        <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></div>
        "._hol_tab::_sec('cas',$ope)
        ;
        $ele_pos = $ele['pos'];
        foreach( _hol::_('cas') as $_cas ){
          $i = "pos-{$_cas->ide}";
          $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
          $_ .= _hol_tab::_pos('cas',$_cas,$ope,$ele);
        } $_ .= "
      </div>";

      return $_;
    }
    static function kin( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('kin',$atr,$ope,$ele) );
      $_ = "";
      
      switch( $atr ){
      // tzolkin
      case 'tzo':
        $ton_htm = isset($ope['sec']['kin-ton']);
        $ton_val = !empty($ope['sec']['kin-ton']);
        $ele_ton=[ 'sec'=>'ton', 'class'=> $ton_val ? "" : "dis-ocu" ];
        $sel_htm = isset($ope['sec']['kin-sel']);
        $sel_val = !empty($ope['sec']['kin-sel']);
        $ele_sel = [ 'sec'=>'sel', 'class' => $sel_val ? "" : "dis-ocu" ];
        $kin_pag = !empty($ope['pag']['kin']);
        // ajusto grilla
        if( $ton_val ) _ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");
        $_ = "
        <div"._htm::atr($ele['sec']).">";
          // 1° columna
          if( $ton_htm && $sel_htm ){ $_ .= "
            <div"._htm::atr([ 'sec'=>'ini', 'class' => $ton_val && $sel_val ? "" : "dis-ocu" ]).">
            </div>";
          }
          if( $sel_htm ){
            // filas por sellos            
            foreach( _hol::_('sel') as $_sel ){
              $ele_sel['data-ide'] = $_sel->ide; 
              $_ .= "
              <div"._htm::atr($ele_sel).">
                "._hol::ima("sel",$_sel)."
              </div>"; 
            }
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( _hol::_('kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){
              $ele_ton['data-ide'] = $_kin->arm_tra; $_ .= "
              <div"._htm::atr($ele_ton).">
                "._hol::ima("ton",$_kin->arm_tra)."
              </div>";
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            if( isset($ele["pos-{$_kin->ide}"]) ) $ele['pos'] = _ele::jun( $ele["pos-{$_kin->ide}"], $ele['pos'] );
            // marco portales PAG
            if( $kin_pag && !empty($_kin->pag) ){
              if( $kin_pag ) _ele::cla($ele['pos'],"_hol-pag_kin");
            }
            $_ .= _hol_tab::_pos('kin',$_kin,$ope,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </div>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par': 
        $_tab = _app::tab('hol','cro')->ele;
        
        if( empty($ide) ) $ide = 1;

        $_kin = is_object($ide) ? $ide : _hol::_('kin',$ide); 
  
        $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
          foreach( _hol::_('sel_par') as $_par  ){               
            
            $par_ide = $_par->ide;

            $par_kin = ( $par_ide == 'des' ) ? $_kin : _hol::_('kin',$_kin->{"par_{$par_ide}"});
            // combino elementos :
            $ele['pos'] = _ele::jun($_tab["pos-{$_par->pos}"], [ 
              $ele_pos, isset($ele["pos-{$par_ide}"]) ? $ele["pos-{$par_ide}"] : []
            ]);
            _ele::cla($ele['pos'],"pos-{$par_ide}",'ini');
            $_ .= _hol_tab::_pos('kin',$par_kin,$ope,$ele);
          }$_ .="
        </div>";        
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_tab = _app::tab('hol','cas')->ele;
      
        $_fam = _hol::_('sel_cro_fam',$ide);
  
        $_fam_kin = [ 1 => 1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ]; 
        
        $_="
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
          </div>"
          ._hol_tab::_sec('cas',$ope)
          ;
          $kin = intval($_fam['kin']);
          
          $ele_pos = $ele['pos'];

          foreach( _hol::_('cas') as $_cas ){
            $_kin = _hol::_('kin',$kin);
            $i = "pos-{$_cas->ide}";
            $ele['pos'] = _ele::jun($_tab[$i], [ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin = $kin + 105; 
            if( $kin > 260 ){ $kin = $kin - 260; }
          } $_ .= "
        </div>";          
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro': 
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }

        $_tab = _app::tab('hol','cas')->ele;

        if( !in_array('fic_cas',$opc) ) $opc []= 'fic_cas'; 

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
            "._app::ima('hol/tab/gal')."
          </div>"
          ._hol_tab::_sec('cas',$ope)
          ;
          $ele_ele = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['ele']) : $ele['ele'];
          foreach( _hol::_('kin_cro_ele') as $_cro ){                
            $i = "pos-{$_cro->ide}";
            $ele['ele'] = _ele::jun($_tab[$i],[ $ele_ele, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_cro->ide;
            $_ .= _hol_tab::kin('cro_ele',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      case 'cro_est':
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }

        $_tab = _app::tab('hol','ton')->ele;

        if( !in_array('fic_cas',$opc) ) $opc []= 'fic_ond';
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['est'])).">
          "._hol_tab::_sec('ton',$ope)
          ;
          $_est = _hol::_('kin_cro_est',$ide); 
          $cas = $_est->cas;
          $ele_ele = isset($_tab['ele']) ? _ele::jun($_tab['ele'],$ele['ele']) : $ele['ele'];
          foreach( _hol::_('ton') as $_ton ){                
            $i = "pos-{$_ton->ide}";
            $ele['ele'] = _ele::jun($_tab[$i], [ $ele_ele, isset($ele[$i]) ? $ele[$i] : [] ]);                
            $ope['ide'] = $cas;
            $_ .= _hol_tab::kin('cro_ele',$ope,$ele);
            $cas++; if( $cas > 52 ){ $cas = 1; }
          } $_ .= "
        </div>";        
        break;
      case 'cro_ele':
        foreach(['ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }

        $_tab = _app::tab('hol','cro_cir')->ele;
        $_ele = _hol::_('kin_cro_ele',$ide);

        // cuenta de inicio
        $kin_ini = 185;
        $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";

        // del castillo | onda : rotaciones
        if( isset($ele['ele']['pos']) ){

          _ele::css($ele['ele'],"transform: rotate(".(in_array('fic_cas',$opc) ? $ele['rot-cas'][$ide-1] : $ele['rot-ton'][$ide-1])."deg)");
        }
        $_ .= "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ele'])).">
          <div"._htm::atr(_ele::jun($_tab['pos-0'],isset($ele['pos-0']) ? $ele['pos-0'] : [])).">
            "._app::ima("hol/ima/arm/"._num::ran($_ele->ide,4), [ 'htm'=>$_ele->ide, 'class'=>"alt-100 anc-100" ])."
          </div>";

          $kin = $kin_ini + ( ( $ide - 1 ) * 5 ) + 1;

          if( $kin > 260 ) $kin -= 260;

          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

          foreach( _hol::_('sel_cro_fam') as $cro_fam ){
            $i = "pos-{$cro_fam->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;// por verdad eléctrica
            if( $kin > 260 ) $kin = 1;
          }$_ .= "
        </div>";        
        break;
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','ton')->ele;
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          "._hol_tab::_sec('ton',$ope)
          ;
          $ele_tra = isset($_tab['pos']) ? _ele::jun($ele['tra'],$_tab['pos']) : $ele['tra'];
          foreach( _hol::_('kin_arm_tra') as $_tra ){ 
            $i = "pos-{$_tra->ide}";
            $ele['tra'] = _ele::jun($_tab[$i],[ $ele_tra, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_tra->ide;
            $_ .= _hol_tab::kin('arm_tra',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      case 'arm_tra':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','cro')->ele;
        $_tra = _hol::_('kin',$ide); $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['tra'])).">";
          $cel_pos = 0;
          $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
          $cel_fin = $cel_ini + 5; 
          $ele_cel = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['cel']) : $ele['cel'];
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $cel_pos++;                
            $i = "pos-{$cel_pos}";
            $ele['cel'] = _ele::jun($_tab[$i],[ $ele_cel, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $cel;
            $_ .= _hol_tab::kin('arm_cel',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      case 'arm_cel': 
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','arm')->ele;
        $_arm = _hol::_($est,$ide);
        
        $ele_cel = $ele['cel'];
        $ele_cel['title'] = _app_dat::val('tit',"hol.{$est}",$_arm);
        _ele::cla($ele_cel,"fon_col-5-$_arm->cel fon-0");
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele_cel)).">";
          $_ .= "
          <div"._htm::atr(_ele::jun($_tab['pos-0'], isset($ele['pos-0']) ? $ele['pos-0'] : [] )).">
            "._hol::ima("sel_arm_cel", $_arm->cel, [ 'htm'=>$_arm->ide, 'class'=>'ima' ] )."
          </div>";

          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          
          $ele_pos = $ele['pos'];
          for( $arm = 1; $arm <= 4; $arm++ ){
            $i = "pos-{$arm}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','cro')->ele;
        
        $ele_cas = $ele['cas'];

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
          foreach( _hol::_('kin_nav_cas') as $cas => $_cas ){
            $i = "pos-{$_cas->ide}";
            $ele['cas'] = _ele::jun($_tab[$i],[ $ele_cas, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_cas->ide;
            $_ .= _hol_tab::kin('nav_cas',$ope,$ele);
          } $_ .= "
        </div>";

        break;
      case 'nav_cas':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','cas')->ele;
        $_cas = _hol::_($est,$ide);

        _ele::cla( $ele['cas'], "fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ) );

        $ele['cas']['title'] = _app_dat::val('tit',"hol.{$est}",$_cas);            
        $ond_ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ond_ini + 4;
        for( $ond = $ond_ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = _hol::_('kin_nav_ond',$ond);
          $ele['cas']['title'].="\n".$_ond->enc_des;
        }
        $_ = "
        <div"._htm::atr( _ele::jun($_tab['sec'],$ele['cas']) ).">
          <div"._htm::atr(_ele::jun($_tab['pos-00'],[ 
            [ 'class'=>"bor_col-5-{$ide} fon_col-5-{$ide}" ], isset($ele['pos-00']) ? $ele['pos-00'] : [] 
          ])).">
            {$ide}
          </div>
          "._hol_tab::_sec('cas',$ope);

          $kin = ( ( $ide - 1 ) * 52 ) + 1;

          $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);

          foreach( _hol::_('cas') as $_cas ){ 
            $i = "pos-{$_cas->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;
      case 'nav_ond':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','ton')->ele;
        $_ond = _hol::_($est,$ide); 
        $_cas = _hol::_('kin_nav_cas',$_ond->nav_cas);

        $ele['ond']['title'] = _app_dat::val('tit',"hol.kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ond'])).">
          "._hol_tab::_sec('ton',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          
          $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);

          foreach( _hol::_('ton') as $_ton ){
            $i = "pos-{$_ton->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol_tab::_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;      
      }
      return $_;
    }
    static function psi( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol_tab::_dat('psi',$atr,$ope,$ele) );
      $_ = "";

      switch( $atr ){
      // giro solar de 13 lunas con psi-cronos
      case 'ban': 
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }        

        $_tab = _app::tab('hol','ton')->ele;
  
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div sec='uni-sol' style='width:100px; height:100px; grid-row:1; grid-column:2; justify-self:end; align-self:end;'>
            "._app::ima('hol/tab/sol')."
          </div>
          <div sec='uni-lun' style='width:60px; height:60px; grid-row:3; grid-column:3; align-self:center; justify-self:center;'>
            "._app::ima('hol/tab/pla')."
          </div>
          "._hol_tab::_sec('ton',$ope)
          ;

          if( !in_array('cab_nom',$opc) ) $opc []= 'cab_nom';
          $ele_lun = _ele::jun($_tab['pos'],$ele['lun']);
          foreach( _hol::_('psi_lun') as $_lun ){
            $i = "pos-{$_lun->ide}";
            $ele['lun'] = _ele::jun($_tab[$i],[$ele_lun, isset($ele["lun-{$i}"]) ? $ele["lun-{$i}"] : [] ]);
            $ope['ide'] = $_lun->ide;
            $_ .= _hol_tab::psi('lun',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      // anillos solares por ciclo de sirio
      case 'ani': 

        $_tab = _app::tab('hol','cas_cir')->ele;

        $kin = 34;
        $ope['sec']['orb_cir'] = '1';

        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
  
          "._hol_tab::_sec('cas_cir',$ope)
          ;
          foreach( _hol::_('cas') as $_cas ){
            $_kin = _hol::_('kin',$kin);
            $agr = '';
            $e = $ele['pos'];
            _ele::cla($e,"pos{$agr}"); 
            $e['pos'] = $_cas->ide;         
            $e['kin'] = $_kin->ide;
            $e['ton'] = $_cas['ton'];
            $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("kin",$_kin,[ 'onclick'=>isset($e['_eje'])?$e['_eje']:NULL ])."
            </div>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </div>";        
        break;
      // estaciones de 91 días
      case 'est':
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }

        $_tab = _app::tab('hol','cas')->ele;
        $_ = "
        <div"._htm::atr( _ele::jun($_tab['sec'],$ele['sec']) ).">
          "._hol_tab::_sec('cas',$ope)
          ; 
          $ele_hep = $ele['hep'];
          foreach( _hol::_('cas') as $_cas ){
            $i = "hep-{$_cas->ide}";
            $ele['hep'] = _ele::jun($_tab["pos-{$_cas->ide}"],[ $ele_hep, isset($ele[$i]) ? $ele[$i] : [] ]);
            $ope['ide'] = $_cas->ide;
            $_ .= _hol_tab::psi('hep',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      // luna de 28 días
      case 'lun':
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) ){
          if( is_array($val) && isset($val['psi']) ){
            $ide = _hol::_('psi',$val['psi'])->lun;
          }
        }
        $_lun = _hol::_($est,$ide);
        $_ton = _hol::_('ton',$ide);
        $_tab = _app::tab('hol','lun')->ele;
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);
        $_ = "
        <table"._htm::atr(_ele::jun($_tab['sec'],$ele['lun'])).">";
          if( !$cab_ocu ){
            $_ .= "
            <thead sec = 'cab'>
              <tr data-sec='ton'>
                <th colspan='8'>
                <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    "._hol::ima("{$est}",$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-16 mar-1" )])."

                    ".( $cab_nom ? "
                      <p><n>{$ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."</p>                      
                    " : "
                    <div>
                      <p class='tit let-4'>
                        <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                      </p>
                      <p class='let-3 let_col-tex mar-1'>
                        "._app::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                        <br>"._app::let($_lun->ond_man)."
                      </p>                   
                      <p class='let-3 let_col-tex mar-1'>
                        Totem<c>:</c> $_lun->tot
                        <br>Propiedades<c>:</c> "._app::let($_lun->tot_pro)."
                      </p> 
                    </div>                      
                    " )."
                  </div>
                </th>
              </tr>";
              // agrego plasmas
              if( !$cab_nom ){ $_ .= "
                <tr sec='rad'>
                  <th>
                    <p class='tex_ali-der'>Plasma</p>
                    <p class='tex_ali-cen'><c>/</c></p>                    
                    <p class='tex_ali-izq'>Héptada</p>
                  </th>";
                  foreach( _hol::_('rad') as $_rad ){ $_ .= "
                    <th>"._hol::ima("rad",$_rad,[])."</th>";
                  }$_ .= "                  
                </tr>";
              }$_ .="
            </thead>";
          }  
          $dia = 1;    
          $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
          $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
          $ope['eti']='td'; $_ .= "
          <tbody>";
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= "
            <tr>
              <td"._htm::atr(_ele::jun(
                  [ 'sec'=>"hep", 'data-arm'=>$arm, 'data-hep'=>$hep, 'class'=>"fon_col-4-{$arm}", 'style'=>'min-width:1rem;' ], 
                  isset($ele[$i = "hep-{$arm}"]) ? $ele[$i] : []
                )).">";
                if( $cab_ocu || $cab_nom ){
                  $_ .= "<n>$hep</n>";
                }else{
                  $_ .= _hol::ima("psi_hep",$hep,[]);
                }$_ .= "
              </td>";

              for( $rad=1; $rad<=7; $rad++ ){
                $_dia = _hol::_('lun',$dia);
                $i = "pos-{$_dia->ide}";
                $ele['pos'] = _ele::jun($ele_pos, isset($ele[$i]) ? $ele[$i] : []);
                $_ .= _hol_tab::_pos('psi',$psi,$ope,$ele);
                $dia++;
                $psi++;
              }
              $hep++; $_ .= "
            </tr>";
          }$_ .= "
          </tbody>
  
        </table>";        
        break;
      // heptada de 7 días
      case 'hep': 
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }

        if( empty($ide) ){
          if( is_array($val) && isset($val['psi']) ){
            $ide = _hol::_('psi',$val['psi'])->hep;
          }
        }        
        $_tab = _app::tab('hol','rad')->ele;
        $_hep = _hol::_('psi_hep',$ide);        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['hep'])).">";

          $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;

          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'] ;

          foreach( _hol::_('rad') as $_rad ){
            $_psi = _hol::_('psi',$psi);            
            $i = "pos-{$_rad->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele["rad_{$i}"]) ? $ele["rad_{$i}"] : [] ]);
            $_ .= _hol_tab::_pos('psi',$psi,$ope,$ele);
            $psi++;
          } $_ .= "
        </div>";        
        break;
      // banco-psi de 8 tzolkin con psi-cronos
      case 'tzo': 
        $_ = "
        <div"._htm::atr($ele['sec']).">";

          $ele_tzo = $ele['sec'];
          for( $i=1 ; $i<=8 ; $i++ ){
            $ele['sec'] = $ele_tzo;
            $ele['sec']['pos'] = $i;
            if( isset($ele["tzo-$i"]) ) $ele['sec'] = _ele::jun($ele['sec'],$ele["tzo-$i"]);
            $_ .= _hol_tab::kin('tzo',$ope,$ele);
          } $_ .= "
        </div>";        
        break;
      }
      return $_;
    }
    static function ani( string $atr, array $ope = [], array $ele = [] ) : string {
      $esq = "hol";
      $_ = "";
      if( isset($val['kin']) ){
        $_kin = _hol::_('kin',$val['kin']);
        $_sel = _hol::_('sel',$_kin->arm_tra_dia);
        $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      }else{
        if( isset($val['sel']) ) $_sel = _hol::_('sel',$val['sel']);
        if( isset($val['ton']) ) $_ton = _hol::_('ton',$val['ton']);
      }      
      switch( $atr ){
      // telektonon : tablero completo
      case 'tel': 
        $ocu = []; 
        foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
  
        $_ = "
        <div"._htm::atr($ele['sec']).">"
          ._app::ima("hol/gal",['eti'=>"div",'fic'=>'gal','title'=>'Fin de la Exhalación Solar. Comienzo de la Inhalación Galáctica.'])
          ._app::ima("hol/sol",['eti'=>"div",'fic'=>'sol','title'=>'Fin de la Inhalación Galáctica. Comienzo de la Exhalación Solar.']);
          foreach( _hol::_('sel_pol_flu') as $v ){ 
            $_ .= _hol::ima("sel_pol_flu",$v,[
              'eti'=>"div",'flu'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"hol.sel_pol_flu",$v)
            ]);
          }
          foreach( _hol::_('sel_sol_res') as $v ){ 
            $_ .= _hol::ima("sel_sol_res",$v,[
              'eti'=>"div",'res'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"hol.res_flu",$v)
            ]);
          }          
          foreach( _hol::_('sel_sol_pla') as $v ){
            $_ .= _hol::ima("sol_pla",$v,[
              'eti'=>"div",'pla'=>$v['ide'],'class'=>$ocu['pla'],'title'=> _app_dat::val('tit',"hol.sol_pla",$v)
            ]);
          }
          foreach( _hol::_('sel_cro_ele') as $v ){ $_ .= "
            <div ele='{$v['ide']}' class='{$ocu['cla']}' title='"._app_dat::val('tit',"hol.sel_cro_ele",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cel') as $v ){ $_ .= "
            <div cel='{$v['ide']}' class='{$ocu['cel']}' title='"._app_dat::val('tit',"hol.sol_cel",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cir') as $v ){ $_ .= "
            <div cir='{$v['ide']}' class='{$ocu['cir']}' title='"._app_dat::val('tit',"hol.sol_cir",$v)."'></div>";
          }
          foreach( _hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            _ele::cla($e,"pos{$ocu['sel']}"); 
            $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel_cod",$_sel)."
            </div>";
          }
          $_ .= " 
        </div>";        
        break;
      // telektonon : holon solar por flujo vertical
      case 'sol': 
        $ocu = []; 
        foreach( ['cel','pla','sel'] as $i ){ 
          $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
        }
        $_="
        <div"._htm::atr($ele['sec']).">
          <div fon='map'></div>
          <div fon='ato'></div>";
          // circuitos
          foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ 
            $_.="<div fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></div>";          
  
          }
          foreach( _hol::_('sel_sol_pla') as $v ){ $_ .= "
            <div pla='{$v->ide}' class='{$ocu['pla']}' title='"._app_dat::val('tit',"{$esq}.sol_pla",$v)."'></div>";
          }
          foreach( _hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            _ele::cla($e,"pos"); 
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel_cod",$_sel->cod)."
            </div>";
          }
          $_ .= " 
        </div>";        
        break;
      // encantamiento : holon planetario
      case 'pla':
        $_="
        <div"._htm::atr($ele['sec']).">
          <div fon='map'></div>";
          foreach( ['res','flu','cen','sel'] as $i ){
            $_.="<div fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></div>";
          }
          $ocu = [];
          foreach( ['fam'] as $i ){ 
            $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
          }
          foreach( _hol::_('sel_cro_fam') as $_dat ){
            $_.=_hol::ima("sel_cro_fam",$_dat,[ 'fam'=>$_dat->ide, 'class'=>$ocu['fam'] ]);
          }
          foreach( _hol::_('sel') as $_sel ){
            $e = $ele['pos'];
            _ele::cla($e,"pos");
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_sel)."
            </div>";
          }
          $_ .= "
        </div>";        
        break;
      // encantamiento : holon humano
      case 'hum':
        $_ = "
        <div"._htm::atr($ele['sec']).">
          <div fon='map'></div>";
          foreach( ['res','ext','cir','cen'] as $i ){
            $_.="<div fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></div>";
          }
          $ocu = []; 
          foreach( ['ext','cen'] as $i ){ 
            $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
          }
          foreach( _hol::_('sel_cro_ele') as $_dat ){ 
            $_ .= _hol::ima("sel_cro_ele",$_dat,['ele'=>$_dat->ide,'class'=>$ocu['ext']]);
          }
          foreach( _hol::_('sel_cro_fam') as $_dat ){ 
            $_ .= _hol::ima("sel_cro_fam",$_dat,['fam'=>$_dat->ide,'class'=>$ocu['cen']]);
          }
          foreach( _hol::_('sel') as $_dat ){
            $e = $ele['pos'];
            _ele::cla($e,"pos");
            $e['pos'] = $e['data-sel'] = $_dat->ide; 
            $_ .= "
            <div"._htm::atr($e).">
              "._hol::ima("sel",$_dat)."
            </div>";
          }
          $_ .= "
        </div>";        
        break;
      }
      return $_;
    }    
  }