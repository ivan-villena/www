<?php

  // holon : ns.ani.lun.dia:kin
  class _hol {

    public function __construct(){
    }
    // estructuras por posicion
    static function _( string $ide, mixed $val = NULL ) : string | array | object {
      $est = "hol_$ide";
      global $_api;
      $_ = [];
      // aseguro carga
      if( !isset($_api->$est) ) $_api->$est = _dat::ini('api',$est);
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
            'api'=>[ 
              'fec'=>_api::_('fec',$fec),
              'hol_kin'=>_hol::_('kin',$_dat['kin']), 
              'hol_psi'=>_hol::_('psi',$_dat['psi']) 
            ],
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
      
      return _doc::ima('api',"hol_$est",$dat,$ele);
    }
    // armo tablero
    static function tab( string $est, string $atr, array $ope = [], array $ele = [] ) : array {

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
    static function tab_sec( string $tip, array $ope=[], array $ele=[] ) : string {
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
    static function tab_pos( string $est, mixed $val, array &$ope, array $ele ) : string {
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

            if( isset($_ref["api-{$esq}_{$est}"]) && intval($_ref["api-{$esq}_{$est}"]) == $val_ide ){

              foreach( $_ref as $ref => $ref_dat ){

                $e["{$ref}"] = $ref_dat;
              }            
              break;
            }
          }
        }
        // por dependencias estructura
        else{
          if( !empty( $dat_est = _dat::est("api","{$esq}_{$est}",'ope','est') ) ){

            foreach( $dat_est as $atr => $ref ){

              if( empty($e["api-{$ref}"]) ){

                $e["api-{$ref}"] = $_dat->$atr;
              }        
            }
          }// pos posicion
          elseif( empty($e["api-{$esq}_{$est}"]) ){    
            $e["api-{$esq}_{$est}"] = $_dat->ide;
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
        if( isset($e['api-fec']) ){
          $pos_tit []= "Calendario: {$e['api-fec']}";
        }
        if( isset($e['api-hol_kin']) ){
          $_kin = _hol::_('kin',$e['api-hol_kin']);
          $pos_tit []= _app_dat::val('tit',"api.hol_kin",$_kin);
        }
        if( isset($e['api-hol_sel']) ){
          $pos_tit []= _app_dat::val('tit',"api.hol_sel",$e['api-hol_sel']);
        }
        if( isset($e['api-hol_ton']) ){
          $pos_tit []= _app_dat::val('tit',"api.hol_ton",$e['api-hol_ton']);
        }
        if( isset($e['api-hol_psi']) ){
          $_psi = _hol::_('psi',$e['api-hol_psi']);
          $pos_tit []= _app_dat::val('tit',"api.hol_psi",$_psi);
        }
        if( isset($e['api-hol_rad']) ){
          $pos_tit []= _app_dat::val('tit',"api.hol_rad",$e['api-hol_rad']);
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
            'pos'=>[ 'ima'=>isset($par_ima) ? $par_ima : "api.hol_{$est}.ide" ]
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
              isset($e["{$_ide['esq']}-{$_ide['est']}"]) 
              && 
              !empty( $_dat = _dat::get($_ide['esq'],$_ide['est'],$e["{$_ide['esq']}-{$_ide['est']}"]) ) 
            ){
              $col = _dat::val_ver('col', ...explode('.',$_val['pos_col']));
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

              $htm .= _app_dat::ver($tip, $ope['pos'][$tip], $e["{$ide['esq']}-{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
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
  }
  // Descripción : por valor => ..."$atr" + "$atr"
  class _hol_des {

    static function kin( string $tip, mixed $ide, array $ope = [] ) : string {
      $_ = "";
      $_kin = _hol::_('kin',$ide);      
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);

      switch( $tip ){
      case 'enc':
        $_ = _doc::let($_kin->nom.": ")."<q>"._doc::let("$_ton->des "._tex::art_del($_sel->pod).", $_ton->acc_lec $_sel->car")."</q>"; 
        break;
      case 'par': 
        $_ = "";
        break;
      }

      return $_;
    }
  }  
  // Ficha : por valor => imagen + ( nombre + descripcion ) + ...atributos + ...detalles
  class _hol_fic {

    static function ton( string | array $atr, mixed $val, array $ope = [] ) : string {
      
      $dat = _hol::_('ton',$val);
      
      if( is_array($atr) ){
        if( empty($atr) ) $atr = ['car','pod','acc'];
        $htm_ini = isset($ope['htm_ini']) ? _doc::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._doc::let($ope['htm_fin']) : "";        
        $_ = "

        <p class='des'>{$htm_ini}Tono <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->nom}<c>.</c>{$htm_fin}</p>
        
        <div class='val jus-cen'>
  
          "._hol::ima("ton",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::fic_atr($dat,[ 'est'=>"api.hol_ton", 'atr'=>$atr ])."
  
        </div>";                
      }
      else{        
        switch( $atr ){
        }
      }
      return $_;
    }
    static function sel( string | array $atr, mixed $val, array $ope = [] ) : string {
      
      $dat = _hol::_('sel',$val);

      if( is_array($atr) ){
        if( empty($atr) ) $atr = ['car','acc','pod'];        
        $htm_ini = isset($ope['htm_ini']) ? _doc::let($ope['htm_ini'])." " : "";
        $htm_fin = isset($ope['htm_fin']) ? " "._doc::let($ope['htm_fin']) : "";
        $_ = "
        <p class='des'>{$htm_ini}Sello <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}<c>.</c>{$htm_fin}</p>
  
        <div class='val jus-cen'>
  
          "._hol::ima("sel",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::fic_atr($dat,['est'=>"api.hol_sel", 'atr'=>$atr ])."
  
        </div>";
      }
      else{
        switch( $atr ){
        }
      }
      return $_;
    }    
    static function kin( string | array $atr, mixed $val, array $ope = [] ) : string {
      
      $dat = _hol::_('kin',$val);

      if( is_array($atr) ){
        if( empty($atr) ) $atr = [];
        $_ = "
        <p class='des'>Kin <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}<c>.</c></p>
  
        <div class='val jus-cen'>
  
          "._hol::ima("kin",$dat,['class'=>'mar_der-2'])."
  
          "._app_dat::fic_atr($dat,['est'=>"api.hol_kin", 'atr'=>$atr ])."
  
        </div>";

        if( isset($ope['ima']) ) $_ .= _app_dat::fic_ima('api',"hol_kin",$ope['ima'],$dat);
      }
      else{
        switch( $atr ){
        // factor : katun del kin
        case 'fac':
          $_kin = $dat;
          $_sel = _hol::_('sel',$_kin->arm_tra_dia);
          $_pol = _hol::_('sel_res',$_sel->res_flu);
          $_pla = _hol::_('sel_sol_pla',$_sel->sol_pla);
          $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
          $_arq = _hol::_('sel',$_ond->sel);
          $ton = intval($_kin->nav_ond_dia);
          $_ = "
          <div class='val'>
    
            "._hol::ima("kin",$_kin)."
    
            <p class='tit tex_ali-izq'>
              Katún <n>".intval($_sel->ide-1)."</n><c>:</c> Kin <n>$ton</n> <b class='ide'>$_sel->may</b>".( !empty($_kin->pag) ? "<c>(</c> Activación Galáctica <c>)</c>" : '' )."<c>.</c>
            </p>
          
          </div>
          <ul>
            <li>Regente Planetario<c>:</c> $_pla->nom $_pol->tip<c>.</c></li>
            <li>Etapa <n>$ton</n><c>,</c> Ciclo $_arq->may<c>.</c></li>
            <li>Índice Armónico <n>"._num::int($_kin->fac)."</n><c>:</c> período "._doc::let($_kin->fac_ran)."</li>
            <li><q>"._doc::let($_sel->arm_tra_des)."</q></li>
          </ul>";          
          break;
        // encantamiento : libro del kin
        case 'enc':
          $htm_ini = isset($ope['htm_ini']) ? _doc::let($ope['htm_ini'])." " : "";
          $htm_fin = isset($ope['htm_fin']) ? " "._doc::let($ope['htm_fin']) : "";
          
          $_ = "
          <p class='des'>
            {$htm_ini}Kin <c>#</c><n>{$dat->ide}</n><c>:</c> "._doc::let($dat->nom)."<c>.</c>{$htm_fin}
          </p>
    
          <div class='val jus-cen mar-1'>
        
            "._hol::ima("kin",$dat,['class'=>"mar_der-2"])."
    
            <q>"._doc::let("{$dat->des}")."</q>
    
          </div>";

          if( isset($ope['ima']) ) $_ .= _app_dat::fic_ima('api',"hol_kin",$ope['ima'],$dat);

          break;
        // encantamiento : parejas del oráculo
        case 'par':
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
              <br><q>"._doc::let($_par->des)."</q>
              ".( !empty($_par->lec) ? "<br><q>"._doc::let($_par->lec)."</q>" : "" )."
            </p>
            
            "._hol_fic::kin('enc',$kin)
            ;
    
          } $_ .= "
          </div>";
          break;
        case 'par_lec':
  
          $_lis = [];
          $_des_sel = _hol::_('sel',$dat->arm_tra_dia);
    
          foreach( _hol::_('sel_par') as $_par ){
    
            if( $_par->ide == 'des' ) continue;
    
            $_kin = _hol::_('kin',$dat->{"par_{$_par->ide}"});
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);
    
            $_lis []=
              _hol::ima("kin",$_kin)."
    
              <div>
                <p><b class='tit'>{$_kin->nom}</b> <c>(</c> "._doc::let($_par->dia)." <c>)</c></p>
                <p>"._doc::let("{$_sel->acc} {$_par->pod} {$_sel->car}, que {$_par->mis} {$_des_sel->car}, {$_par->acc} {$_sel->pod}.")."</p>
              </div>";
          }
          
          if( !isset($ope['lis']) ) $ope['lis']=[];
    
          _ele::cla($ope['lis'],'ite');
          
          $_ = _doc_lis::val($_lis,$ope);          
          break;
        // factor : etapa evolutiva - estacion galáctica
        case 'cro_est':
          $_ = "
          <div class='val jus-cen'>
      
            "._hol::ima("kin_cro_est",$dat,['class'=>"mar_der-2"])."
    
            <ul>
              <li><p><b class='ide'>Guardían</b><c>:</c> {$dat->may}</p></li>
              <li><p><b class='ide'>Etapa Evolutiva</b><c>:</c> "._doc::let("{$dat->nom}, {$dat->des}")."</p></li>
            </ul>
    
          </div>";          
          break;
        // factor : baktún - trayectoria armónica
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
        // encantamiento : castillo de la nave
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
        }
      }
      return $_;
    }
  }
  // Listado : por estructura => items + tabla
  class _hol_lis {

    static function uni( string $atr, array $ele = [] ) : string {
      $_ = []; $est = "hol_uni_".$atr; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // dinamicas del tiempo : campos planetarios
      case 'pla_cam.nom':
        $_ = "      
        <dl>
          <dt>el campo electromagnético</dt>
          <dd><c>(</c>magnetosfera y cinturores de radiación<c>,</c> incluyendo la ionosfera<c>)</c><c>,</c> </dd>
          <dt>el campo biopsíquico</dt>
          <dd> <c>(</c>biosfera incluyendo la simbiosis de eco<c>-</c>ciclos que integran el <c>\"</c>corpus inerte<c>\"</c> con el <c>\"</c>vivo<c>\"</c><c>)</c><c>,</c></dd>
          <dt>y el campo gravitacional</dt>
          <dd><c>(</c>incluyendo la estructura de placas tectónicas<c>,</c> mantos y núcleo de la Tierra<c>)</c><c>.</c></dd>
        </dl>";
        break; 
      case 'pla_cam.des': 
        $_ = "
        <ul>
          <li>el campo electromagnético se reconstituye psicofísicamente a través de los sentidos<c>;</c> </li>
          <li>el campo bio<c>-</c>psíquico se reorganiza como orden cósmico telepático de la sociedad humana indistinguible de los órdenes vivos de la naturaleza<c>,</c> </li>
          <li>y el campo gravitacional es conducido a un nuevo nivel de equilibrio a través de una vibrante correlación y simbiosis de los dos órdenes geoquímicos tridimensionales<c>,</c> SiO<sup>2</sup> <c>(</c>dióxido de silicio<c>)</c> y CO<sup>2</sup> <c>(</c>dióxido de carbono<c>)</c><c>.</c></li>
        </ul>";
        break;                              
      }
      return is_array($_) ? _app_dat::lis( $_, $est, $lis_tip, $ele ) : $_;
    }    
    static function rad( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // plasma : años por oráculos de la profecía
      case 'ani': 
        $ele['lis'] = ['class'=>"ite"];
        $ele['ite'] = ['class'=>"mar_aba-1"];      

        foreach( _hol::_('rad') as $_rad ){ $_ []=
          _hol::ima("rad",$_rad,['class'=>"mar_der-1"])."
          <p>
            <b class='ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
            <br><q>"._doc::let($_rad->rin_des)."<c>.</c></q>
          </p>";
        }
        $_ = _doc_lis::val($_,$ele);
        break;              
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_rad_$atr", $lis_tip, $ele ) : $_;
    }
    static function ton( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // tutorial : propiedades
      case 'pro': 
        $ope['atr'] = ['ide','nom','car','pod','acc'];
        $_ = _doc_lis::tab("api.hol_ton", $ope, $ele );
        break;
      // encantamiento : descripciones
      case 'des':
        $ope['atr'] = ['ide','nom','des','acc'];
        $_ = _doc_lis::tab("api.hol_ton", $ope, $ele );
        break;
      // factor : rayo de pulsacion
      case 'gal':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('ton') as $_ton ){ $_ []= "
          "._hol::ima("ton",$_ton,['class'=>"mar_der-1"])."
          <p>
            <n>".intval($_ton->ide)."</n><c>.</c> El Rayo de Pulsación ".preg_replace("/^(del|de la)/","$1<b class='ide'>",_tex::art_del($_ton->gal))."</b>
          </p>";
        }        
        break;
      // factor : simetría especular
      case 'sim': 
        foreach( _hol::_('ton_sim') as $_sim ){ $_ []= "
          <p>"._doc::let($_sim->des)."</p>";
        }        
        break;              
      // encantamiento: aventura de la onda encantada 
      case 'ond':
        $_atr = array_merge([ 
          'ima'=>_obj::atr(['ide'=>'ima','nom'=>''])
          ], _dat::atr('api',"hol_ton", [ 'ide','ond_pos','ond_pod','ond_man' ])
        );
  
        // cargo valores
        foreach( ( $_dat = _obj::atr(_hol::_('ton')) ) as $_ton ){
          $_ton->ima = [ 'htm'=>_hol::ima("ton",$_ton) ];
          $_ton->ide = "Tono {$_ton->ide}";
        }
        // cargo titulos
        $ond = 0;
        $_tit = [];
        foreach( $_dat as $lis_pos => $_ton ){
          if( $_ton->ond_enc != 0 && $ond != $_ton->ond_enc ){              
            $_ond = _hol::_('ton_ond',$ond = $_ton->ond_enc);
            $_tit[$lis_pos] = $_ond->des;
          }
        }
  
        $_ = _doc_lis::tab($_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit, 'opc'=>['cab_ocu'] ],$ele);              
        break;
            
      // encantamiento : pulsares dimensionales
      case 'dim':
        foreach( _hol::_('ton_dim') as $_dat ){ $htm = "
          <p>
            <n>{$_dat->ide}</n><c>.</c> <b class='ide'>Pulsar de la {$_dat->pos} dimensión</b><c>:</c> <b class='val'>Dimensión {$_dat->nom}</b>
            <br>Tonos "._doc::let("{$_dat->ton}: {$_dat->ond}")."
          </p>
          <div class='fic ite'>
            "._hol::ima("ton_dim",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= _hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "
            <c class='_lis fin'>}</c>
          </div>
          ";
          $_ []= $htm;
        }        
        break;
      // encantamiento : pulsares matiz
      case 'mat':
        foreach( _hol::_('ton_mat') as $_dat ){ $htm = "
          <p><n>{$_dat->ide}</n><c>.</c> <b class='ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='val'>"._doc::let($_dat->cod)."</b><c>:</c>
            <br>Tonos "._doc::let("{$_dat->ton}: {$_dat->ond}")."
          </p>
          <div class='fic ite'>
            "._hol::ima("ton_mat",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= _hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "              
            <c class='_lis fin'>}</c>
          </div>";
          $_ []= $htm;
        }        
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_ton_$atr", $lis_tip, $ele ) : $_;
    }
    static function sel( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0; 
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // tutorial : propiedades
      case 'pro': 
        $ope['atr'] = ['ide','nom','pod','acc','car'];
        $_ = _doc_lis::tab("api.hol_sel", $ope, $ele );
        break;        
      // factor : posiciones direccionales
      case 'cic_dir':
        $ele['lis'] = ['class'=>"ite"];
        $_ = [];
        foreach( _hol::_("sel_{$atr}") as $_dir ){ $_ []=
          _hol::ima("sel_{$atr}",$_dir,['class'=>"mar_der-1 tam-11"])."
          <div>
            <p><b class='ide'>".explode(' ',$_dir->nom)[1]."</b><c>:</c></p>
            <ul>
              <li><p><c>-></c> "._doc::let($_dir->des)."</p></li>
              <li><p><c>-></c> Color<c>:</c> <c class='let_col-4-{$_dir->ide}'>{$_dir->col}</c></p></li>
            </ul>
          </div>";
        }
        break;
      // factor : desarrollo del ser con etapas evolutivas
      case 'cic_ser':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('sel') as $_sel ){
          if( $lis_pos != $_sel->cic_ser ){
            $lis_pos = $_sel->cic_ser;
            $_ser = _hol::_("sel_{$atr}",$lis_pos);
            $_ []= "
            <p class='tit'>
              DESARROLLO".( _tex::let_may( _tex::art_del($_ser->nom) ) ).( !empty($_ser->det) ? " <c>-</c> Etapa {$_ser->det}" : '' )."
            </p>";
          }                
          $_dir = _hol::_('sel_cic_dir',$_sel->arm_raz); $_ []= 
  
          _hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
  
          <p><n>{$_sel->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".explode(' ',$_dir->nom)[1]."<c>.</c>
            <br>"._doc::let($_sel->cic_ser_des)."
          </p>";
        }        
        break;
      // factor : familias ciclicas
      case 'cic_luz': 
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('sel') as $_sel ){
          if( $lis_pos != $_sel->cic_luz ){
            $lis_pos = $_sel->cic_luz;
            $_luz = _hol::_("sel_{$atr}",$lis_pos); $_ []= "
            <p><b class='tit'>"._tex::let_may("Familia Cíclica "._tex::art_del($_luz->nom)."")."</b>
              <br><b class='des'>{$_luz->des}</b><c>.</c>
            </p>";
          }                
          $_dir = _hol::_('sel_cic_dir',$_sel->arm_raz);                 
          
          $_ []= 
  
          _hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
  
          <p>".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
            <br>"._doc::let($_sel->cic_luz_des)."
          </p>";                
        }          
        break;
      // factor : guardianes evolutivos de la mente
      case 'cic_men':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("sel_{$atr}") as $_est ){
          $_sel = _hol::_('sel',$_est->sel); 
          $_dir = _hol::_('sel_cic_dir',$_est->ide); $_ []= 
          
          _hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
  
          <p><n>".intval($_sel->ide)."</n><c>°</c> Signo<c>.</c> ".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
            <br><b class='val des'>{$_est->nom}</b><c>:</c> {$_est->des}<c>.</c>
          </p>";
        }        
        break;
      // encantamiento : colocacion armónica => razas raíz cósmica
      case 'arm_raz':
        $sel = 1;
        foreach( _hol::_("sel_{$atr}") as $_dat ){
          $_raz_pod = _hol::_('sel',$_dat->ide)->pod; 
          $htm = "
          <p class='tit'>Familia <b class='let_col-4-{$_dat->ide}'>{$_dat->nom}</b><c>:</c> de la <b class='ide'>Raza Raíz "._tex::let_min($_dat->nom)."</b></p>
          <p>Los {$_dat->pod}dores<c>.</c> Nota clave<c>:</c> ".explode(' ',$_raz_pod)[1]."</p>
          <ul class='ite'>";
          foreach( _hol::_('sel_arm_cel') as $lis_pos ){
            $_sel = _hol::_('sel',$sel); $htm .= "
            <li>
              "._hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
              <p>
                <n>{$lis_pos->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                <br><q>"._doc::let($_sel->arm_raz_des)."</q>
              </p>
            </li>";
            $sel += 4;
            if( $sel > 20 ) $sel -= 20;                  
          }
          $htm.="
          </ul>
          <q>"._doc::let(_tex::let_ora($_raz_pod)." ha sido "._tex::art_gen("realizado",$_raz_pod).".")."</q>";
          $_ []= $htm;
        }        
        break;
      // encantamiento : colocacion armónica => células del tiempo
      case 'arm_cel':
        $lis_pos = 1;

        foreach( _hol::_("sel_{$atr}") as $_dat ){ $htm = "
          <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='ide'>{$_dat->nom}</b></p>
          <q>"._doc::let($_dat->des)."</q>
          <ul class='ite'>";
          foreach( _hol::_('sel_arm_raz') as $cro ){ $_sel = _hol::_('sel',$lis_pos); $htm .= "
            <li>
              "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
              <p>
                <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                <br><q>"._doc::let($_sel->arm_cel_des)."</q>
              </p>
            </li>";
            $lis_pos ++;
          }$htm .= "
          </ul>";
          $_ []= $htm;
        }           
        break;
      // encantamiento : colocacion cromática => clanes galácticos
      case 'cro_ele':
        $sel = 20;      
        foreach( _hol::_("sel_{$atr}") as $_dat ){
          $ele_nom = explode(' ',$_dat->nom)[1]; $htm = "
          <p class='tit'><b class='ide'>Clan "._tex::art_del($_dat->nom)."</b>"._doc::let(": Cromática {$_dat->col}.")."</p>
          ".( !empty($_dat->des_ini) ? "<p>"._doc::let($_dat->des_ini)."</p>" : '' )."
          <ul class='ite'>";
          for( $fam=1; $fam<=5; $fam++ ){ 
            $_sel = _hol::_('sel',$sel); 
            $_fam = _hol::_('sel_cro_fam',$fam); $htm .= "
            <li sel='{$_sel->ide}' cro_fam='{$fam}'>
              "._hol::ima("sel",$_sel,[ 'class'=>"mar_der-1" ])."
              <p>
                <n>{$sel}</n><c>.</c> <b class='ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
                <br><q>"._doc::let($_sel->cro_ele_des)."</q>
              </p>
            </li>";
            $sel++;
            if( $sel > 20 ) $sel -= 20;
          }$htm .= "
          </ul>";
          $_ []= $htm;
        }          
        break;
      // encantamiento : colocacion cromática => familias terrestres
      case 'cro_fam':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('sel_pla_cen') as $_pla ){
          $_hum = _hol::_('sel_hum_cen',$_pla->ide);
          $_fam = _hol::_("sel_{$atr}",$_pla->fam);
          $htm = 
          _hol::ima("sel_pla_cen",$_pla,['class'=>"mar_der-2",'style'=>"min-width: 9rem; height:7rem;"])."
          <div>
            <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> {$_fam->enc_fun}</p>
            <div class='val fic mar-2'>
              "._hol::ima("sel_hum_cen",$_hum)."
              <c class='sep'>=></c>
              <c class='_lis ini'>{</c>";
                foreach( explode(', ',$_fam->sel) as $sel ){
                  $htm .= _hol::ima("sel",$sel,['class'=>"mar_hor-2"]);
                }$htm .= "
              <c class='_lis fin'>}</c>
            </div>
          </div>
          ";
          $_ []= $htm;
        }        
        break;
      // encantamiento : holon solar => celulas solares y planentas
      case 'sol_cel': 
        $orb = 0;
        $pla = 10;
        $sel = 20;
        $val_sel = empty( $val = isset($ele['val']) ? $ele['val'] : [] );
  
        foreach( _hol::_("sel_{$atr}") as $_dat ){
          if( $val_sel || in_array($_dat->ide,$val) ){ 
            $htm = "
            <p class='tit'><b class='ide'>{$_dat->nom}</b><c>:</c> Célula Solar "._num::dat($_dat->ide,'nom')."<c>.</c></p>                  
            <ul est='sol_pla'>";
            for( $sol_pla=1; $sol_pla<=2; $sol_pla++ ){
              $_pla = _hol::_('sel_sol_pla',$pla);
              $_sel = _hol::_('sel',$sel);
              $_par = _hol::_('sel',$_sel->par_ana); 
              if( $orb != $_pla->orb ){
                $_orb = _hol::_('sel_sol_orb',$orb = $_pla->orb); $htm .= "
                <li>Los <n>5</n> <b class='ide'>planetas {$_orb->nom}es</b><c>:</c> "._doc::let($_orb->des)."</li>";                        
              }
              $htm .= "
              <li>
                <p><b class='ide'>{$_pla->nom}</b><c>,</c> <n>{$pla}</n><c>°</c> órbita<c>:</c></p>
                <div class='ite'>
  
                  "._hol::ima("sel_sol_pla",$_pla,['class'=>"mar_der-1"])."
  
                  <ul class='ite' est='sel'>
                    <li>
                      "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
                      <p>
                        <b class='val'>Dentro</b><c>:</c> Sello Solar <n>{$_sel->ide}</n>
                        <br><q>"._doc::let($_sel->sol_pla_des)."</q>
                      </p>
                    </li>
                    <li>
                      "._hol::ima("sel",$_par,['class'=>"mar_der-1"])."
                      <p>
                        <b class='val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                        <br><q>"._doc::let($_par->sol_pla_des)."</q>
                      </p>
                    </li>
                  </ul>
                </div>
              </li>";                    
              $pla--;
              $sel++;
              if( $sel > 20 ) $sel=1;
            } $htm .= "
            </ul>";
            $_ []= $htm;
          }
        }        
        break;
      // telektonon : holon solar => circuitos de telepatía
      case 'sol_cir':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("sel_{$atr}") as $_cir ){
          $pla = explode('-',$_cir->pla);
          $_pla_ini = _hol::_('sel_sol_pla',$pla[0]);
          $_pla_fin = _hol::_('sel_sol_pla',$pla[1]);
          $htm = 
          _hol::ima("sel_{$atr}",$_cir,['class'=>""])."
          <div>
            <p class='tit'>Circuito <n>$_cir->ide</n><c>:</c> <b class='ide'>$_pla_ini->nom <c>-</c> $_pla_fin->nom</b></p>
            <ul>
              <li>Circuito "._doc::let($_cir->nom)."</li>
              <li><p>"._doc::let("$_cir->cod unidades - $_cir->des")."</p></li>
              <li><p>Notación Galáctica<c>,</c> números de código "._doc::let("{$_cir->sel}: ");
              $lis_pos = 0;
              foreach( explode(', ',$_cir->sel) as $sel ){ 
                $lis_pos++; 
                $_sel = _hol::_('sel', $sel == 00 ? 20 : $sel);                      
                $htm .= _doc::let( $_sel->pod_tel.( $lis_pos == 3 ? " y " : ( $lis_pos == 4 ? "." : ", " ) ) );
              } $htm .= "
              </p></li>
            </ul>
          </div>
          ";
          $_ []= $htm;
        }        
        break;
      // encantamiento : holon planetario => centros planetarios
      case 'pla_cen': 
        $ele['lis'] = ['class'=>"ite"];

        $_fam_sel = [
          1=>[  5, 10, 15, 20 ],
          2=>[  1,  6, 11, 16 ],
          3=>[ 17,  2,  7, 12 ],
          4=>[ 13, 18,  3,  8 ],
          5=>[  9, 14, 19,  4 ]
        ]; 
        $lis_pos = 1;
        foreach( _hol::_('sel_pla_cen') as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam); $htm= "
          <div class='val'>
            "._hol::ima("sel_cro_fam",$_fam,['class'=>"mar_der-1"])."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
            foreach( $_fam_sel[$_dat->ide] as $sel ){
              $htm .= _hol::ima("sel",$sel,['class'=>"mar_hor-1"]);
            }$htm.="
            <c class='_lis fin'>}</c>
            <c class='sep'>:</c>
          </div>
          <p>
            <n>{$_dat->ide}</n><c>.</c> El Kin <b class='ide'>{$_fam->nom}</b><c>:</c>
            <br><q>{$_fam->pla} desde el {$_dat->nom}</q>
          </p>";
          $_ []= $htm;
        }        
        break;
      // encantamiento : holon planetario => rol de familias terrestres
      case 'pla_pos':
        $_fam_sel = [
          1=>[ 20,  5, 10, 15 ],
          2=>[  1,  6, 11, 16 ],
          3=>[ 17,  2,  7, 12 ],
          4=>[ 18,  3,  8, 13 ],
          5=>[ 14, 19,  4,  9 ]
        ];
        foreach( _hol::_('sel_pla_cen') as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam);
          $htm = "
          <div class='tex_ali-cen'>
            <p>Kin <b class='ide'>{$_fam->nom}</b></p>
            "._hol::ima("sel_pla_cen",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 17rem; height: 11rem;"])."
          </div>
          <ul class='ite'>";
            foreach( $_fam_sel[$_dat->ide] as $sel ){
              $_sel = _hol::_('sel',$sel);
              $_pla_mer = _hol::_('sel_pla_mer',$_sel->pla_mer);
              $_pla_hem = _hol::_('sel_pla_hem',$_sel->pla_hem);
              $htm .= "
              <li>
                "._hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
                <p>
                  Sello <n>{$_sel->ide}</n><c>:</c> {$_sel->nom}<c>,</c>
                  <br><n>".intval($_sel->pla_hem_cod)."</n><c>°</c> {$_pla_hem->nom}<c>,</c> <n>".intval($_sel->pla_mer_cod)."</n><c>°</c> {$_pla_mer->nom}
                </p>
              </li>";
            }$htm .= "
          </ul>";
          $_ []= $htm;
        }        
        break;
      // tratado : campos planetarios por agrupacion
      case 'pla_cam': 
        $_ = "
        <ul>
          <li>el gravitacional <c>(</c>las Cuatro Razas Raíz<c>)</c><c>,</c> </li>
          <li>el electromagnético <c>(</c>los Cuatro Clanes<c>)</c> </li>
          <li>y el biopsíquico <c>(</c>las Cinco Familias Terrestres<c>)</c><c>.</c></li>
        </ul>";
        break;
      // encantamiento : holon humano => colocacion cromática
      case 'hum_ele': 
        $ele = []; $lis_pos = 0; $col = 4;
        foreach( _hol::_('sel_hum_ext') as $_ext ){
          $_ele = _hol::_('sel_cro_ele',$_ext->ele); 
          $nom = explode(' ',_tex::art_del($_ele->nom)); $cla = array_pop($nom); $nom = implode(' ',$nom);
          $ele[$lis_pos] = [ 
            'eti'=>"div", 'class'=>"ite", 'htm'=> _hol::ima("sel_hum_ext",$_ext,['class'=>"mar_der-1"])."                  
            <p class='tit tex_ali-izq'><b class='ide'>$_ext->nom</b><c>:</c>
              <br>Clan {$nom} <c class='let_col-4-$col'>{$cla} $_ele->col</c></p>" 
          ];
          $lis_pos += 5; 
          $col = _num::ran($col+1,4);
        }
        $sel = [];
        foreach( _hol::_('sel_cod') as $_sel ){
          $_fam = _hol::_('sel_cro_fam',$_sel->cro_fam);
          $_hum_ded = _hol::_('sel_hum_ded',$_fam->hum_ded);
          $sel []= _obj::atr([ 
            'hum_ded'=>$_hum_ded->nom, 
            'nom'=>"Tribu "._tex::art_del($_sel->nom)." $_sel->nom_col", 
            'ima_nom'=>[ 'htm'=>_hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
            'nom_cod'=>$_sel->nom_cod,
            'ima_cod'=>[ 'htm'=>_hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ]
          ]);
        }
        $_ = _doc_lis::tab($sel,[ 'tit'=>$ele, 'opc'=>['cab_ocu'] ]);
        break;
      // encantamiento : holon humano => rol de familias terrestres
      case 'hum_fam':
        $fam = [];
        $sel = [];
  
        foreach( _hol::_('sel_hum_ded') as $_ded ){
          $_fam = _hol::_('sel_cro_fam',$_ded->fam);
          $fam[$lis_pos] = [
            'eti'=>"div", 'class'=>"ite", 'htm'=> _hol::ima("sel_hum_ded",$_ded,['class'=>"mar_der-1"])."                  
            <p class='tit tex_ali-izq'><b class='ide'>Familia Terrestre $_fam->nom</b><c>:</c>
              <br>Familia de $_fam->cod<c>:</c> Dedos {$_ded->nom}".( in_array($_ded->nom,['Anular','Pulgar']) ? "es" : "s" )." </p>" 
          ];
          $lis_pos += 4;
          foreach( explode(', ',$_fam->sel) as $_sel ){
            $_sel = _hol::_('sel',$_sel);
            $_hum_ext = _hol::_('sel_hum_ext',$_sel->hum_ext);
            $sel []= _obj::atr([
              'nom'=>"Tribu "._tex::art_del($_sel->nom)." $_sel->nom_col", 
              'ima_nom'=>[ 'htm'=>_hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
              'nom_cod'=>$_sel->nom_cod,
              'ima_cod'=>[ 'htm'=>_hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ],
              'hum_ext'=>$_hum_ext->nom
            ]);
          }
        }
  
        $_ = _doc_lis::tab($sel,[ 'tit'=>$fam, 'opc'=>['cab_ocu'] ]);
        break;
      // encantamiento : holon humano => extremidades del humano
      case 'hum_ext':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("sel_{$atr}") as $_dat ){
          $_ele = _hol::_('sel_cro_ele',$_dat->ele); $_ []= "
  
            "._hol::ima("sel_hum_ext",$_dat,['class'=>"mar_der-1"])."
  
            <p><b class='ide'>Cromática "._tex::art_del($_ele->nom)."</b><c>:</c>
              <br>{$_dat->nom}
            </p>";
        }        
        break;
      // encantamiento : holon humano => dedos del humano
      case 'hum_ded':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("sel_{$atr}") as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam); $_ []= "
  
            "._hol::ima("sel_hum_ded",$_dat,['class'=>"mar_der-1"])."
  
            <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
              <br>{$_dat->nom}
            </p>";
        }        
        break;
      // encantamiento : holon humano => centros galácticos del humano
      case 'hum_cen':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("sel_{$atr}") as $_dat ){
          $_fam = _hol::_('sel_cro_fam',$_dat->fam); $_ []= "
  
          "._hol::ima("sel_hum_cen",$_dat,['class'=>"mar_der-1"])."
  
          <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
            <br>"._tex::art($_dat->nom)." <c>-></c> {$_fam->hum}
          </p>";
        }            
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_sel_$atr", $lis_tip, $ele ) : $_;
    }
    static function lun( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // 13 lunas : heptadas - cuarto armónica
      case 'arm':
        if( isset($_atr[1]) ){
          switch( $_atr[1] ){
          // descripcion
          case 'des': 
            foreach( _hol::_('lun_arm') as $_hep ){
              $_ []= _doc::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let("): $_hep->des");
            }
            break;
          case 'pod':
            foreach( _hol::_('lun_arm') as $_hep ){
              $_ []= _doc::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let(", $_hep->pod $_hep->car");
            }        
            break;            
          }
        }
        break;
      // 13 lunas : heptadas lunares
      case 'arm_col':
        $ope['atr'] = [ 'ide','nom','col','dia','pod' ];
        $ope['opc'] []= 'cab_ocu';
        $_ = _doc_lis::tab("api.hol_lun_arm", $ope, $ele );
        break;        
      // tutorial : descripcion de las heptadas
      case 'arm_des': 
        foreach( _hol::_("lun_{$atr}") as $_hep ){
          $_ []= _doc::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let("): $_hep->des");
        }
        break;
      // telektonon : por poderes
      case 'arm_pod': 
        foreach( _hol::_('lun_arm') as $_hep ){
          $_ []= _doc::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let(", $_hep->pod $_hep->car");
        }        
        break;              
      // telektonon : lines de fuerza
      case 'fue': 
        foreach( _hol::_("lun_{$atr}") as $_lin ){
          $_ []= _doc::let("{$_lin->nom}: {$_lin->des}");
        }
        break;
      // rinri : días del cubo
      case 'cub':
        foreach( _hol::_("lun_{$atr}") as $_cub ){
          $_ []= 
          "<div class='ite'>
            "._hol::ima("sel",$_cub->sel,['class'=>"mar_der-1"])."              
            <div>
              <p class='tit'>Día <n>$_cub->lun</n><c>,</c> CUBO <n>$_cub->ide</n><c>:</c> $_cub->nom</p>
              <p class='des'>$_cub->des</p>
            </div>              
          </div>
          <p class='let-enf tex_ali-cen'>"._doc::let($_cub->tit)."</p>
          ".( !empty($_cub->lec) ? "<p class='let-cur tex_ali-cen'>"._doc::let($_cub->lec)."</p>" : ""  )."
          <q>"._doc::let($_cub->afi)."</q>";
        }        
        break;                    
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_lun_$atr", $lis_tip, $ele ) : $_;
    }    
    static function kin( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // lecturas : parejas del oraculo
      case 'par':
        if( !isset($ele['atr']) ) $ele['atr'] = "";
        if( !isset($ele['dat']) ) $ele['dat'] = "001";
        $dat = _hol::_('kin',$ele['dat']);
        $ele_lis = isset($ele['ele']) ? $ele['ele'] : [];
        switch( $ele['atr'] ){
        // Propiedades : palabras clave del kin + sello + tono
        case 'pro':

          $_par_atr = !empty($ele['par']) ? $ele['par'] : ['fun','acc','mis'];
    
          $_ton_atr = !empty($ele['ton']) ? $ele['ton'] : ['acc'];
    
          $_sel_atr = !empty($ele['sel']) ? $ele['sel'] : ['car','des'];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});
    
            $ite = [ _hol::ima("kin",$_kin) ];
    
            foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= _doc::let($_par->$atr); }
    
            $_ton = _hol::_('ton',$_kin->nav_ond_dia);
            foreach( $_ton_atr as $atr ){ if( isset($_ton->$atr) ) $ite []= _doc::let($_ton->$atr); }
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);            
            foreach( $_sel_atr as $atr ){  if( isset($_sel->$atr) ) $ite []= _doc::let($_sel->$atr); }
    
            $_ []= $ite;
          }
          break;
        // Ciclos : posiciones en ciclos del kin
        case 'cic':
          $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});
    
            $ite = [ _hol::ima("kin",$_kin) ];
    
            foreach( $_atr as $atr ){
              $ite []= _hol::ima("kin_{$atr}",$_kin->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          break;
        // Grupos : sincronometría del holon por sellos
        case 'gru':
          $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});                            
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);
    
            $ite = [ _hol::ima("kin",$_kin), $_par->nom, $_sel->pod ];
    
            foreach( $_atr as $atr ){
              $ite []= _hol::ima("sel_{$atr}",$_sel->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          break;
        }
        $_ = _doc_lis::tab( $_, $ope, $ele_lis);
        break;
      // factor : portales de activacion
      case 'pag':
        $arm_tra = 0;
        $ele['lis'] = ['class'=>"ite"];
  
        foreach( array_filter(_hol::_('kin'), function( $ite ){ return !empty($ite->pag); }) as $_kin ){ 
          $lis_pos++; 
          $_sel = _hol::_('sel',$_kin->arm_tra_dia);
          if( $arm_tra != $_kin->arm_tra ){
            $arm_tra = $_kin->arm_tra;
            $_tra = _hol::_('kin_arm_tra',$arm_tra); $_ []= "
  
            "._hol::ima("ton",$arm_tra,['class'=>"mar_der-1"])."
  
            <p class='tit'>"._doc::let(_tex::let_may("CICLO ".($num = intval($_tra->ide)).", Baktún ".( $num-1 )))."</p>";
          }
          $_ []= "
  
          "._hol::ima("kin",$_kin,['class'=>"mar_der-1"])."
  
          <p>
            <n>{$lis_pos}</n><c>.</c> <b class='ide'>{$_sel->may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
            <br>"._doc::let($_kin->fac_ran)."
          </p>";
        }          
        break;
      // factor : 1 trayectoria con detalles por katun ( ciclos del modelo morfogenetico )
      case 'fec':
        $ond = 0;
        $_ = "
        <table>";
          if( !empty($ele['tit']) ){ $_.="
            <caption>".( !empty($ele['tit']['htm']) ? "<p class='tit'>"._doc::let($ele['tit']['htm'])."</p>" : '' )."</caption>";
          }$_.="
  
          <thead>
            <tr>
              <td></td>
              <td>CICLO AHAU</td>
              <td>CICLO KATUN <c>(</c><i>índice armónico y año</i><c>)</c></td>
              <td>CUALIDAD MORFOGENÉTICA</td>
            </tr>
          </thead>
  
          <tbody>";
          foreach( ( !empty($dat) ? $dat : _hol::_('kin') ) as $_kin ){
  
            if( $ond != $_kin->nav_ond ){
              $_ond = _hol::_('kin_nav_ond', $ond = $_kin->nav_ond); 
              $_sel = _hol::_('sel', $_ond->sel);
              $_ .= "
              <tr class='tex_ali-izq'>
                <td>
                  "._hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."
                </td>
                <td colspan='3'>{$_sel->may}<c>:</c> "._doc::let($_ond->fac_ran)." <q>"._doc::let($_ond->fac_des)."</q></td>
              </tr>";
            }
            $_sel = _hol::_('sel',$sel = intval($_kin->arm_tra_dia));
            $_ .= "
            <tr data-kin='{$_kin->ide}'>
              <td>
                Etapa <n>".($ton = intval($_kin->nav_ond_dia))."</n>
              </td>
              <td></td>
              <td>
                <n>$sel</n><c>.</c><n>$ton</n> <b class='ide'>$_sel->may</b><c>:</c>
                <br><n>"._num::int($_kin->fac)."</n><c>,</c> año <n>"._num::int($_kin->fac_ini)."</n>
              </td>
              <td>
                <q>"._doc::let($_sel->arm_tra_des)."</q>
              </td>
            </tr>";
          }$_.="
          </tbody>
  
        </table>";
        break;
      // tratado del tiempo : trayectorias + castillos
      case 'tie':
        $_ = "
        <dl>
          <dt>Célula del Tiempo Uno <c class='col-roj'>Roja</c><c>,</c> Entrada<c>:</c></dt>      
          <dd>Informar el girar<c>,</c> iniciar el nacimiento de la semilla</dd>
          <dt>Célula del Tiempo Dos <c class='col-bla'>Blanca</c><c>,</c> Almacén<c>:</c></dt>
          <dd>Recordar el cruzar<c>,</c> refinar la muerte del guerrero</dd>
          <dt>Célula del Tiempo Tres <c class='col-azu'>Azul</c><c>,</c> Proceso<c>:</c></dt>
          <dd>Formular el quemar<c>,</c> transformar la magia de la estrella</dd>
          <dt>Célula del Tiempo Cuatro <c class='col-ama'>Amarilla</c><c>,</c> Salida<c>:</c></dt>
          <dd>Expresar el dar<c>,</c> madurar la inteligencia del sol</dd>
          <dt>Célula del Tiempo Cinco <c class='col-ver'>Verde</c><c>,</c> Matriz<c>:</c></dt>
          <dd>Auto<c>-</c>regular el encantamiento<c>,</c> sincronizar el libre albedrío del humano</dd>
        </dl>";
        break;
      // factor : 13 baktunes
      case 'arm_tra':

        foreach( _hol::_("kin_{$atr}") as $_tra ){
          $htm = "
          <div class='val'>
            "._hol::ima("ton",$_tra->ide,['class'=>"mar_der-1"])."
            <p>
              <b class='tit'>Baktún <n>".(intval($_tra->ide)-1)."</n><c>.</c> Baktún "._tex::art_del($_tra->nom)."</b>
              <br>"._doc::let($_tra->ran)." <c><=></c> "._doc::let($_tra->may)."
            </p>
          </div>";
          $lis = [];
          foreach( explode('; ',$_tra->lec) as $ite ){
            $lis []= "<c>-></c> "._doc::let($ite);
          }
          $_[] = $htm._doc_lis::val($lis,[ 'lis'=>['class'=>"pun"] ]);
        }          
        break;
      // factor: 20 katunes
      case 'arm_sel':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('sel') as $_sel ){ $_ [] = "
  
          "._hol::ima("sel_arm_tra",$_sel,['class'=>"mar_der-2"])."
  
          <p>
            <b class='ide'>{$_sel->may}</b><c>:</c> Katún <n>".(intval($_sel->ide)-1)."</n>
            <br>{$_sel->arm_tra_des}
          </p>";
        }
        break;
      // factor : guardianes por estacion cromatica
      case 'cro_est':

        foreach( _hol::_("kin_{$atr}") as $_est ){

          $_sel = _hol::_('sel',$_est->sel); $htm = "
  
          <div class='val'>
            "._hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
  
            <p>
              <b class='tit'>ESTACIÓN "._tex::let_may(_tex::art_del("el {$_est->nom}"))."</b>
              <br>Guardían<c>:</c> <b class='ide'>{$_sel->may}</b> <c>(</c> {$_sel->nom} <c>)</c>
            </p>
          </div>";
  
          $lis = [];
          foreach( _hol::_('kin_cro_ond') as $_ond ){ $lis []= "
  
            "._hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
  
            <p>El quemador {$_ond->que} el Fuego<c>.</c>
              <br><n>".intval($_ond->ton)."</n> {$_sel->may}
            </p>";
          }                
          $_[] = $htm._doc_lis::val($lis,[ 'lis'=>['class'=>'ite'] ]);
        }          
        break;
      // factor : aventura por guardián
      case 'cro_ond':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_cro_ond') as $_ond ){ $_ []= "
          "._hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
          <p>
            Tono <n>".intval($_ond->ton)."</n> de la familia terrestre polar<c>:</c> 
            {$_ond->nom} <n>1</n> de los <n>4</n> Espectros Galácticos<c>.</c>
          </p>";
        }          
        break;
      // encantamiento : espectros galácticos
      case 'cro_sel':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_cro_est') as $_est ){ 
          $_sel = _hol::_('sel',$_est->sel); $_ []= "
          "._hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
          <p>
            <n>{$_est->ide}</n><c>.</c> El espectro galáctico <b class='let_col-4-{$_est->ide}'>{$_est->col}</b><c>:</c> 
            Estación "._tex::art_del($_sel->nom)."
          </p>";
        }          
        break;
      // encantamiento : aventura por estaciones
      case 'cro_ton':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_cro_ond') as $_ond ){ $_ []= "
          "._hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
          <p>
            Tono <n>".intval($_ond->ton)."</n><c>:</c> 
            {$_ond->nom} <n>".($_ond->cue*5)."</n> Kines <c>(</c> <n>{$_ond->cue}</n> cromática".( $_ond->cue > 1 ? "s" : "")." <c>)</c>
          </p>";
        }            
        break;
      // encantamiento : génesis
      case 'gen':
        $_ = [
          "<b class='ide'>Génesis del Dragón</b><c>:</c> <n>13.000</n> años del Encantamiento del Sueño<c>,</c> poder del sueño<c>.</c>",
          "<b class='ide'>Génesis del Mono</b><c>:</c> <n>7.800</n> años del Encantamiento del Sueño<c>,</c> poder de la magia<c>.</c>",
          "<b class='ide'>Génesis de la Luna</b><c>:</c> <n>5.200</n> años del Encantamiento del Sueño<c>,</c> poder del vuelo mágico<c>.</c>",
        ];     
        break;
      // encantamiento : ondas y castillos con células del génesis
      case 'nav':
        $gen = 0;
        $cel = 0;
        $cas = 0;
        $ele['lis'] = ['class'=>"ite"];
  
        foreach( _hol::_('kin_nav_ond') as $_ond ){
          // génesis
          if( $gen != $_ond->gen_enc ){ 
            $_gen = _hol::_('kin_gen_enc',$gen = $_ond->gen_enc); $_[]="
            <p class='tit'>{$_gen->lec}<c>:</c> <b class='ide'>Génesis {$_gen->nom}</b><c>.</c></p>";
          }
          if( $cel != $_ond->gen_cel ){ 
            $_cel = _hol::_('kin_gen_cel',$cel = $_ond->gen_cel); $_[]="
            <p class='tit'>Célula <n>{$_cel->ide}</n> de la memoria del Génesis<c>:</c> <b class='val'>{$_cel->nom}</b></p>";
          }
          if( $cas != $_ond->nav_cas ){ 
            $_cas = _hol::_('kin_nav_cas',$cas = $_ond->nav_cas); $_[]="
            <p class='tit'>
              El Castillo <b class='let_col-5-{$_cas->ide}'>".str_replace('del ','',$_cas->nom)."</b> "._tex::art_del($_cas->acc)."<c>:</c> La corte "._tex::art_del($_cas->cor)."<c>,</c> poder {$_cas->pod}
            </p>";
          }              
          $_ []= _hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."              
          <p><n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c> <q>"._doc::let($_ond->enc_des)."</q></p>";
        }          
        break;
      // factor : ciclo ahau / onda encantada
      case 'nav_sel':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('kin_nav_ond') as $_ond ){ 
          $_sel = _hol::_('sel',$_ond->sel); $_ [] = "
  
          "._hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-2"])."
  
          <p>
            <n>{$_ond->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> "._doc::let($_ond->fac_ran)."
            <br><q>{$_ond->fac_des}</q>
          </p>";
        }            
        break;
              
      // 13 lunas : castillos del encantamiento
      case 'nav_cas':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("kin_{$atr}") as $_cas ){ $_ [] = 
          _hol::ima("kin_{$atr}",$_cas,['class'=>"mar_der-2"])."
  
          <p>
            <b class='ide'>Castillo $_cas->col $_cas->dir "._tex::art_del($_cas->acc)."</b><c>:</c>
            <br>Ondas Encantadas <n>"._num::int($_cas->ond_ini)."</n> <c>-</c> <n>"._num::int($_cas->ond_fin)."</n>
          </p>";
        }          
        break;              
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_kin_$atr", $lis_tip, $ele ) : $_;
    }
    static function psi( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // encantamiento : por tonos galácticos
      case 'lun':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_("psi_{$atr}") as $_lun ){
          $_ []= _hol::ima("ton",$_lun->ton,['class'=>"mar_der-2"])."
          <p>
            <b class='ide'>"._tex::let_ora(_num::dat($_lun->ide,'pas'))." Luna</b>
            <br>Luna "._tex::art_del($_lun->ton_car)."<c>:</c> "._doc::let($_lun->ton_pre)."
          </p>";
        }    
        break;        
      // encantamiento : fechas desde - hasta
      case 'lun_fec':
        $ele['lis'] = ['class'=>"ite"];

        foreach( _hol::_('psi_lun') as $_lun ){
          $_[] = _hol::ima("ton",$_lun->ton,['class'=>"mar_der-3"])."
          <p>
            <b class='ide'>$_lun->nom</b> <n>".intval($_lun->ton)."</n>
            <br>"._doc::let($_lun->fec_ran)."
          </p>";
        }$_[] = "
        <span ima></span>
        <p>
          <b class='ide'>Día Verde</b> o Día Fuera del Tiempo
          <br><n>25</n> de Julio
        </p>";        
        break;
      // 13 lunas : totems lunares
      case 'lun_tot':
        $ele['lis'] = ['class'=>"ite"];
        $_lis = [
          1=>"El <b class='ide'>Murciélago Magnético</b> va con la Primera Luna",
          2=>"El <b class='ide'>Escorpión Lunar</b> va con la Segunda Luna",
          3=>"El <b class='ide'>Venado Eléctrico</b> va con la Tercera Luna",
          4=>"El <b class='ide'>Búho Auto<c>-</c>Existente</b> va con la Cuarta Luna",
          5=>"El <b class='ide'>Pavo Real Entonado</b> va con la Quinta Luna",
          6=>"El <b class='ide'>Lagarto Rítmico</b> va con la Sexta Luna",
          7=>"El <b class='ide'>Mono Resonante</b> va con la Séptima Luna",
          8=>"El <b class='ide'>Halcón Galáctico</b> va con la Octava ",
          9=>"El <b class='ide'>Jaguar Solar</b> va con la Novena Luna",
          10=>"El <b class='ide'>Perro Planetario</b> va con la Décima Luna",
          11=>"La <b class='ide'>Serpiente Espectral</b> va con la Undécima Luna",
          12=>"El <b class='ide'>Conejo Cristal</b> va con la Duodécima Luna",
          13=>"La <b class='ide'>Tortuga Cósmica</b> va con la Decimotercera Luna"
        ];
  
        foreach( $_lis as $ite => $htm ){
          $_ []= _hol::ima("psi_lun",$_lun = _hol::_('psi_lun',$ite),['class'=>"mar_der-2"])."
          <p>
            $htm
            <br>"._doc::let($_lun->fec_ran)."
          </p>";
        }
        $_ []= "
        <p>
          Día fuera del tiempo
          <br><n>25</n> de Julio
        </p>";        
        break;
      // rinri : dias pag + cubo
      case 'lun_dia':
        if( isset($ope['atr']) && is_string($ope['atr']) ){
          foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
          switch( $ope['atr'] ){
          // días psi de cuartetos ocultos        
          case 'pag':
            $_ = "
            <table"._htm::atr($ele['lis']).">
              <thead>
                <tr>
                  <th scope='col'></th>
                  <th scope='col'>Torre Día <n>1</n></th>
                  <th scope='col'>Torre Día <n>6</n></th>
                  <th scope='col'>Torre Día <n>23</n></th>
                  <th scope='col'>Torre Día <n>28</n></th>
                </tr>
                <tr>
                  <th scope='col'></th>
                  <th scope='col'><c>(</c>Pareado con día <n>28</n><c>)</c></th>
                  <th scope='col'><c>(</c>Pareado con día <n>23</n><c>)</c></th>
                  <th scope='col'><c>(</c>Pareado con día <n>6</n><c>)</c></th>
                  <th scope='col'><c>(</c>Pareado con día <n>1</n><c>)</c></th>
                </tr>
              </thead>
              <tbody>";
                foreach( _hol::_("psi_{$atr}") as $_lun ){ $_ .= "
                  <tr>
                    <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                    foreach( explode(', ',$_lun->kin_pag) as $kin ){ $_ .= "
                      <td>"._hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                    }$_ .= "   
                  </tr>";
                }$_ .= "
              </tbody>
            </table>";        
            break;
          // días psi del cubo - laberinto del guerrero
          case 'cub': 
            $_ = "
            <table"._htm::atr($ele['lis']).">
              <tbody>";
                foreach( _hol::_("psi_{$atr}") as $_lun ){ $_ .= "
                  <tr>
                    <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                    foreach( explode('-',$_lun->kin_cub) as $kin ){ $_ .= "
                      <td>"._hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                    }$_ .= "
                    <td>Kines "._doc::let(_tex::mod($_lun->kin_cub,"-"," - "))."</td>
                  </tr>";
                  if( $_lun->ide == 7 ){ $_ .= "
                    <tr>
                      <td colspan='4'>
                        <p>Perro <n>13</n><c>,</c> Kin <n>130</n> <c>=</c> <n>14</n> Luna Resonante<c>,</c> </p>
                        <p><b>Cambio de Polaridad</b></p>
                        <p>Mono <n>1</n><c>,</c> Kin <n>131</n> <c>=</c> <n>15</n> Luna Resonante</p>
                      </td>
                    </tr>";
                  }
                }$_ .= "
              </tbody>
            </table>";        
            break;
          }
          // devuelvo <table>
          return $_;
        }
        elseif( empty($ope['atr']) ){
          $ope['atr'] = [];
          $_ = _doc_lis::tab("api.hol_lun", $ope, $ele );
        }
        break;
      // tratado : vinales
      case 'vin':
        $ope['atr'] = ['ide','nom','fec','sin','cro'];
        $ope['det_des'] = ['des'];
        //$ele['lis']['class'] = "anc-100 mar-2";
        $_ = _doc_lis::tab("api.hol_psi_{$atr}", $ope, $ele);

        break;
      // proyecto rinri : cromaticas entonadas
      case 'cro_arm':
        foreach( [ 1, 2, 3, 4 ] as $arm ){
        
          $cro_arm = _hol::_('psi_cro_arm',$arm);
  
          $_ []= "Cromática <c class='let_col-4-$arm'>$cro_arm->col</c><br>"._doc::let("$cro_arm->nom: $cro_arm->des");
        }        
        break;        
      }
      return is_array($_) ? _app_dat::lis( $_, "api.psi_$atr", $lis_tip, $ele ) : $_;
    }
    static function ani( string $atr, array $ele = [] ) : string {
      $_ = []; 
      $lis_tip = "val"; $lis_pos = 0;
      $ope = [ 'opc'=>['htm','cab_ocu'] ];
      switch( $atr ){
      // 13 lunas : años (desde-hasta) por anillos solares
      case 'fec':
        $ini = 1992;
        $cue = 8;
        $_[] = "
        <b class='ide'>Año Uno</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',39),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio  <n>1.992</n> <c>-</c> <n>25</n> Julio <n>1.993</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Dos</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',144),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.993</n> <c>-</c> <n>25</n> Julio <n>1.994</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Tres</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',249),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.994</n> <c>-</c> <n>25</n> Julio <n>1.995</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Cuatro</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',94),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.995</n> <c>-</c> <n>25</n> Julio <n>1.996</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Cinco</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',199),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.996</n> <c>-</c> <n>25</n> Julio <n>1.997</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Seis</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',44),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.997</n> <c>-</c> <n>25</n> Julio <n>1.998</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Siete</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',149),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.998</n> <c>-</c> <n>25</n> Julio <n>1.999</n><c>.</c></p>
        </div>"; $_[] = "
        <b class='ide'>Año Ocho</b>
        <div class='ite'>
          "._hol::ima("kin",$_kin = _hol::_('kin',254),['class'=>"mar_der-1"])."
          <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.999</n> <c>-</c> <n>25</n> Julio <n>2.000</n><c>.</c></p>
        </div>";
      }
      return is_array($_) ? _app_dat::lis( $_, "hol_ani_$atr", $lis_tip, $ele ) : $_;
    }    
  }
  // Tablero : por estructura + valor => seccion + posicion
  class _hol_tab {
    
    static function rad( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('rad',$atr,$ope,$ele) );

      return $_;
    }
    static function ton( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('ton',$atr,$ope,$ele) );
      $_tab = _app::tab('hol','ton')->ele;
      $_ .= "
      <div"._htm::atr(_ele::jun($ele['sec'],$_tab['sec'])).">
        <div fon='ima'></div>
        "._hol::tab_sec('ton',$ope)
        ;
        $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

        foreach( _hol::_('ton') as $_ton ){
          $i = "pos-{$_ton->ide}";
          $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
          $_ .= _hol::tab_pos('ton',$_ton,$ope,$ele);
        } $_ .= "
      </div>";
      return $_;
    }
    static function sel( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('sel',$atr,$ope) );

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
        $e['title'] = _app_dat::val('tit',"api.hol_{$est}",$_arm); $_ = "
        <div"._htm::atr($e).">
          <div pos='00'>
            "._hol::ima("sel_arm_cel", $_arm, ['htm'=>$_arm->ide,'class'=>'ima'] )."
          </div>
          ";
          foreach( _hol::_('sel_arm_raz') as $_raz ){
            $_.= _hol::tab_pos('sel',$sel,$ope,$ele);
            $sel++;
          } $_ .= "
        </div>";        
        break;
      }
      return $_;
    }  
    static function lun( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('lun',$atr,$ope,$ele) );

      return $_;
    }
    static function cas( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('cas',$atr,$ope,$ele) );

      $_tab = _app::tab('hol','cas')->ele;

      $_ = "
      <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
        <div fon='ima'></div>
        <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></div>
        "._hol::tab_sec('cas',$ope)
        ;
        $ele_pos = $ele['pos'];
        foreach( _hol::_('cas') as $_cas ){
          $i = "pos-{$_cas->ide}";
          $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
          $_ .= _hol::tab_pos('cas',$_cas,$ope,$ele);
        } $_ .= "
      </div>";

      return $_;
    }
    static function kin( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('kin',$atr,$ope,$ele) );
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
            $_ .= _hol::tab_pos('kin',$_kin,$ope,$ele);
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
            $_ .= _hol::tab_pos('kin',$par_kin,$ope,$ele);
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
          ._hol::tab_sec('cas',$ope)
          ;
          $kin = intval($_fam['kin']);
          
          $ele_pos = $ele['pos'];

          foreach( _hol::_('cas') as $_cas ){
            $_kin = _hol::_('kin',$kin);
            $i = "pos-{$_cas->ide}";
            $ele['pos'] = _ele::jun($_tab[$i], [ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol::tab_pos('kin',$kin,$ope,$ele);
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
            "._doc::ima('hol/tab/gal')."
          </div>"
          ._hol::tab_sec('cas',$ope)
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
          "._hol::tab_sec('ton',$ope)
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
            "._doc::ima("hol/ima/arm/"._num::ran($_ele->ide,4), [ 'htm'=>$_ele->ide, 'class'=>"alt-100 anc-100" ])."
          </div>";

          $kin = $kin_ini + ( ( $ide - 1 ) * 5 ) + 1;

          if( $kin > 260 ) $kin -= 260;

          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

          foreach( _hol::_('sel_cro_fam') as $cro_fam ){
            $i = "pos-{$cro_fam->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol::tab_pos('kin',$kin,$ope,$ele);
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
          "._hol::tab_sec('ton',$ope)
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
        $ele_cel['title'] = _app_dat::val('tit',"api.hol_{$est}",$_arm);
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
            $_ .= _hol::tab_pos('kin',$kin,$ope,$ele);
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

        $ele['cas']['title'] = _app_dat::val('tit',"api.hol_{$est}",$_cas);            
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
          "._hol::tab_sec('cas',$ope);

          $kin = ( ( $ide - 1 ) * 52 ) + 1;

          $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);

          foreach( _hol::_('cas') as $_cas ){ 
            $i = "pos-{$_cas->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol::tab_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;
      case 'nav_ond':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 

        $_tab = _app::tab('hol','ton')->ele;
        $_ond = _hol::_($est,$ide); 
        $_cas = _hol::_('kin_nav_cas',$_ond->nav_cas);

        $ele['ond']['title'] = _app_dat::val('tit',"api.hol_kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
        
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ond'])).">
          "._hol::tab_sec('ton',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          
          $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);

          foreach( _hol::_('ton') as $_ton ){
            $i = "pos-{$_ton->ide}";
            $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
            $_ .= _hol::tab_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </div>";        
        break;      
      }
      return $_;
    }
    static function psi( string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _hol::tab('psi',$atr,$ope,$ele) );
      $_ = "";

      switch( $atr ){
      // giro solar de 13 lunas con psi-cronos
      case 'ban': 
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }        

        $_tab = _app::tab('hol','ton')->ele;
  
        $_ = "
        <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
          <div sec='uni-sol' style='width:100px; height:100px; grid-row:1; grid-column:2; justify-self:end; align-self:end;'>
            "._doc::ima('hol/tab/sol')."
          </div>
          <div sec='uni-lun' style='width:60px; height:60px; grid-row:3; grid-column:3; align-self:center; justify-self:center;'>
            "._doc::ima('hol/tab/pla')."
          </div>
          "._hol::tab_sec('ton',$ope)
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
  
          "._hol::tab_sec('cas_cir',$ope)
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
          "._hol::tab_sec('cas',$ope)
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
                        "._doc::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                        <br>"._doc::let($_lun->ond_man)."
                      </p>                   
                      <p class='let-3 let_col-tex mar-1'>
                        Totem<c>:</c> $_lun->tot
                        <br>Propiedades<c>:</c> "._doc::let($_lun->tot_pro)."
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
                $_ .= _hol::tab_pos('psi',$psi,$ope,$ele);
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
            $_ .= _hol::tab_pos('psi',$psi,$ope,$ele);
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
          ._doc::ima("hol/gal",['eti'=>"div",'fic'=>'gal','title'=>'Fin de la Exhalación Solar. Comienzo de la Inhalación Galáctica.'])
          ._doc::ima("hol/sol",['eti'=>"div",'fic'=>'sol','title'=>'Fin de la Inhalación Galáctica. Comienzo de la Exhalación Solar.']);
          foreach( _hol::_('sel_pol_flu') as $v ){ 
            $_ .= _hol::ima("sel_pol_flu",$v,[
              'eti'=>"div",'flu'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"api.hol_sel_pol_flu",$v)
            ]);
          }
          foreach( _hol::_('sel_sol_res') as $v ){ 
            $_ .= _hol::ima("sel_sol_res",$v,[
              'eti'=>"div",'res'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"api.hol_res_flu",$v)
            ]);
          }          
          foreach( _hol::_('sel_sol_pla') as $v ){
            $_ .= _hol::ima("sol_pla",$v,[
              'eti'=>"div",'pla'=>$v['ide'],'class'=>$ocu['pla'],'title'=> _app_dat::val('tit',"api.hol_sol_pla",$v)
            ]);
          }
          foreach( _hol::_('sel_cro_ele') as $v ){ $_ .= "
            <div ele='{$v['ide']}' class='{$ocu['cla']}' title='"._app_dat::val('tit',"api.hol_sel_cro_ele",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cel') as $v ){ $_ .= "
            <div cel='{$v['ide']}' class='{$ocu['cel']}' title='"._app_dat::val('tit',"api.hol_sol_cel",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cir') as $v ){ $_ .= "
            <div cir='{$v['ide']}' class='{$ocu['cir']}' title='"._app_dat::val('tit',"api.hol_sol_cir",$v)."'></div>";
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