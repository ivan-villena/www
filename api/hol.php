<?php
// holon : ns.ani.lun.dia:kin
class Hol { 

  static string $IDE = "Hol-";
  static string $EJE = "Hol.";

  function __construct(){
  }// getter
  static function _( string $ide, mixed $val = NULL ) : string | array | object {

    $_ = $_dat = Dat::get_est('hol',$ide,'dat');
    
    if( isset($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        case 'fec':
          $fec = Fec::_('dat',$val);
          if( isset($fec->dia)  ) $_ = Dat::get( Hol::_('psi'), [ 
            'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ],
            'opc'=>['uni'] 
          ]);
          break;
        case 'kin':
          if( $val == -1 ){
            $_ = new stdClass;
            $_->ide = 261;
            $_->nom = "Hunab Ku";
            $_->des = "";
          }else{
            $_ = $_dat[ Num::val_ran( Num::val_sum($val), 260 ) - 1 ];
          }          
          break;
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];
          break;
        }
      }
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
        $_ = Hol::val($fec);
        if( is_string($_) ){ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
        }
      }
      // decodifico N.S.( cod.ani.lun.dia:kin )
      elseif( $tip == 'sin' ){
        // busco año          
        if( $_fec = Hol::val_cod($val) ){

          $_ = Hol::val($_fec);

          if( is_string($_) ) $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
        }
        else{ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
        }
      }
    }
    // armo datos de una fecha
    elseif( $fec = Fec::dat($val) ){
      // giro solar => año
      $_['fec'] = $fec->val;

      $_fec = Hol::val_dec( $fec );

      // giro lunar => mes + día
      if( $_psi = Hol::val_dia($_['fec']) ){

        $_['psi'] = $_psi->ide;

        $_['sin'] = "{$_fec->sir}.".Num::val($_fec->ani,2).".{$_psi->ani_lun}.{$_psi->ani_lun_dia}";
        
        // giro galáctico => kin
        $_kin = Hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);

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
    else{ $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }
    return $_;
  }// convierto NS => d/m/a
  static function val_cod( array | string $val ) : bool | string {
    $_ = $val;

    if( is_string($val) ) $val = explode('.',$val);

    if( isset($val[3]) ){

      $sir = intval($val[0]);
      $ani = intval($val[1]);
      $lun = intval($val[2]);
      $dia = intval($val[3]);

      // mes y día
      $_psi = Dat::get( Hol::_('psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);
  
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
  }// convierto d/m/a => NS
  static function val_dec( mixed $val ) : object | string {

    $_ = !is_object($val) ? Fec::dat($val) : $val ;

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
            $_->$atr = Num::val_ran($_->$atr+105, 260); 
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
            $_->$atr = Num::val_ran($_->$atr-105, 260); 
          }
          if ($_->ani < 0){ 
            $_->ani = 51; 
            $_->sir--; 
          }
        } 
        // sin considerar 0, directo a -1 : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
        if( $_->sir == 0 ) $_->sir = -1;
      }      
      if( $_->dia <= 25 && $_->mes <= 7 ){
        $_->ani--;        
        foreach( ['fam_3','fam_4'] as $atr ){ 
          $_->$atr = Num::val_ran($_->$atr-105, 260); 
        }
      }
    }
    else{
      $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }
    return $_;
  }// sumo o resto dias de un fecha dada
  static function val_fec( string $tip, string $val, int $cue = 1, string $opc = 'dia' ) : string {

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
            $lun += Num::val_red($cue / 28);
            $dia = Num::val_ran($dia, 28);
            
            if( $lun > 13 ){
              $ani += Num::val_red($lun / 13);
              $lun = Num::val_ran($lun, 13);
  
              if( $ani > 51 ){
                $sir += Num::val_red($ani / 51);
                $ani = Num::val_ran($ani, 51, 0);
              }
            }
          }
        }
        elseif( $tip == '-' ){
  
          $dia -= $cue;        
  
          if( $dia < 1 ){
            $lun -= Num::val_red($cue / 28);
            $dia = Num::val_ran($dia, 28);
            
            if( $lun < 1 ){    
              $ani -= Num::val_red($lun / 13);
              $lun = Num::val_ran($lun, 13);
  
              if( $ani < 0 ){    
                $sir -= Num::val_red($ani / 51);
                $ani = Num::val_ran($ani, 51, 0);
              }
            }
          }
        }        
        break;
      case 'lun': 
        if( $tip == '+' ){

          $lun += $cue;
            
          if( $lun > 13 ){
            $ani += Num::val_red($lun / 13);
            $lun = Num::val_ran($lun, 13);

            if( $ani > 51 ){  
              $sir += Num::val_red($ani / 51);
              $ani = Num::val_ran($ani, 51, 0);                
            }
          }
        }
        elseif( $tip == '-' ){

          $lun -= $cue;
            
          if( $lun < 1 ){  
            $ani -= Num::val_red($lun / 13);
            $lun = Num::val_ran($lun, 13);

            if( $ani < 0 ){
              $sir -= Num::val_red($ani / 51);
              $ani = Num::val_ran($ani, 51, 0);
            }
          }
        }        
        break;
      case 'ani': 
        if( $tip == '+' ){

          $ani += $cue;

          if( $ani > 51 ){
            $sir += Num::val_red($ani / 51);
            $ani = Num::val_ran($ani, 51, 0);
          }
        }
        elseif( $tip == '-' ){

          $ani -= $cue;

          if( $ani < 0 ){
            $sir -= Num::val_red($ani / 51);
            $ani = Num::val_ran($ani, 51, 0);
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

      $_ = "$sir.".Num::val($ani,2).".".Num::val($lun,2).".".Num::val($dia,2);
    }
    return $_;
  }// genero acumulados por valor principal
  static function val_dat( string $est, array $dat, array $ope = [] ) : array {
    $_ = [];

    $cue = 0;
    $ini = isset($ope['ini']) ? intval($ope['ini']) : 1;
    $inc = isset($ope['inc']) ? intval($ope['inc']) : 1;
    $val = isset($ope['val']) ? intval($ope['val']) : "+";
    $est_kin = ( $est == 'kin' && isset($dat['kin']) );
    $est_psi = ( $est == 'psi' && isset($dat['psi']) );
      
    if( isset($dat['fec']) ){
      // x 260 dias por kin 
      if( $est_kin ){
        $cue = 260;
        $fec = Fec::val_ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
      }
      // x 364+1 dias por psi-cronos
      elseif( $est_psi ){
        $cue = 364;
        $fec = Fec::val_ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
      }
      // recorro datos    
      for( $pos = 0; $pos < $cue; $pos++ ){

        // salteo el 29/02: no tiene ni kin, ni psicronos ( día hunab ku )
        if( preg_match("/^29-02/",$fec) ){
          $pos--;
        }
        else{
          $_dat = Hol::val($fec);

          $_ []= Dat::val([
            'fec'=>[ 
              'dat'=>Fec::_('dat',$fec),
            ],
            'hol'=>[
              'kin'=>Hol::_('kin',$_dat['kin']),
              'psi'=>Hol::_('psi',$_dat['psi']) 
            ]
          ]);
        }
        $fec = Fec::val_ope($fec, $inc, $val);
      }
    }
    return $_;
  }// busco valor diario por psi-crono
  static function val_dia( mixed $val ) : object {

    $fec = Fec::_('dat',$val);

    $_ = new stdClass;

    if( isset($fec->dia)  ) 
      $_ = Dat::get( Hol::_('psi'), [ 
        'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ],
        'opc'=>['uni'] 
      ]);

    return $_;
  }// genero transitos por fecha del sincronario
  static function val_cic( string $val, ...$opc ) : array {
    $_ = [];
    $ver_lun = !in_array('not-lun',$opc);
    
    // recorro el castillo anual
    for( $cic_año = 1 ; $cic_año <= 52; $cic_año++ ){
      
      $_val = Hol::val($val,'sin');

      $_cas = Hol::_('cas',$cic_año);
      
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

          $_val_lun = Hol::val($val_lun,'sin');

          $_cic_lun = new stdClass;  
          $_cic_lun->ani = $cic_año;
          $_cic_lun->ide = $cic_mes;
          $_cic_lun->fec = $_val_lun['fec'];
          $_cic_lun->sin = $_val_lun['sin'];
          $_cic_lun->kin = $_val_lun['kin'];
          
          $_cic_año->lun []= $_cic_lun;
          // incremento 1 luna
          $val_lun = Hol::val_fec('+',$val_lun,1,'lun');            
        }
      }        
      $_ []= $_cic_año;
      // incremento 1 anillo      
      $val = Hol::val_fec('+',$val,1,'ani');
    }

    return $_;
  }

  // imagen
  static function ima( string $est, mixed $dat, array $ele = [] ) : string {
    
    return Fig::ima('hol',$est,$dat,$ele);
  }  

  // variable
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";    
    $_ide = self::$IDE."var_$tip";
    $_eje = self::$EJE."var_$tip";
    $_tip = explode('-',$tip);
    $atr = isset($_tip[1]) ? $_tip[1] : '';
    switch( $est = $_tip[0] ){
    case 'fec':
      $_eje =  !empty($ope['eje']) ? $ope['eje'] : self::$EJE."val";
      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : Hol::_('kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : Hol::_('psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      
  
      $_ = "
      <!-- Fecha del Calendario -->
      <form class='doc_val fec mar-1'>
  
        ".Fig::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol_val-fec", 'class'=>"mar_hor-1", 
          'title'=>"Desde aquí puedes cambiar la fecha..." 
        ])."
        ".Fec::var('dia', $_fec, [ 'id'=>"hol_val-fec", 'name'=>"fec", 
          'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
        ])."
        ".Fig::ico('val-ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);", 
          'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
        ])."
  
      </form>
  
      <!-- Fecha del Sincronario -->
      <form class='doc_val sin mar-1'>
        
        <label>N<c>.</c>S<c>.</c></label>
  
        ".Num::var('int', $_sin[0], [ 
          'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
        ])."
        <c>.</c>
        ".Lis::opc( Hol::_('psi_ani'), [
          'eti'=>[ 'name'=>"ani", 'class'=>"num", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
          'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        ".Lis::opc( Hol::_('psi_ani_lun'), [
          'eti'=>[ 'name'=>"lun", 'class'=>"num", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
          'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        ".Lis::opc( Hol::_('lun'), [ 
          'eti'=>[ 'name'=>"dia", 'class'=>"num", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
          'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
        ])."          
        <c class='sep'>:</c>
    
        <n name='kin'>$_kin->ide</n>
  
        ".Fig::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);",
          'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
        ])."
  
      </form>";
      break;
    }
    return $_;
  }

  // informe
  static function inf( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";    
    $_ide = self::$IDE."lis_$tip";
    $_eje = self::$EJE."lis_$tip";
    $_tip = explode('-',$tip);
    $atr = isset($_tip[1]) ? $_tip[1] : '';
    switch( $est = $_tip[0] ){
    case 'kin':
      $_bib = SYS_NAV."hol/bib/";
      $_kin = $dat = Hol::_($est,$dat);
      $_sel = Hol::_('sel',$dat->arm_tra_dia);
      $_ton = Hol::_('ton',$dat->nav_ond_dia);
      switch( $atr ){
      // parejas del oráculo
      case 'par':
        $_ = "
          
        ".Hol::tab("kin","par",[ 'ide'=>$dat, 'pos'=>[ 'ima'=>"hol.kin.ide"  ] ], [ 'sec'=>[ 'class'=>"mar_aba-1" ] ])."

        <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='{$_bib}enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>

        <div class='lis'>";
        foreach( Hol::_('sel_par') as $_par ){
          // salteo el destino
          if( ( $ide = $_par->cod ) == 'des' ) continue;
          // busco datos de parejas
          $_par = Dat::get( Hol::_('sel_par'), [ 'ver'=>[ ['cod','==',$ide] ], 'opc'=>'uni' ]);
          $kin = Hol::_('kin',$dat->{"par_{$ide}"});
          $_ .= "
          <p class='tex mar_arr-2 tex_ali-izq'>
            <b class='let-ide tex-sub'>{$_par->nom}</b><c>:</c>
            <br>".Tex::let($_par->des)."
            ".( !empty($_par->lec) ? "<br>".Tex::let($_par->lec) : "" )."
          </p>
          
          ".Dat::inf('hol','kin',$kin,[ 'det'=>"des" ]);

        } $_ .= "
        </div>";
        break;
      // - Propiedades : palabras clave del kin + sello + tono
      case 'par_des':
        $_ = [];
        $htm = "
        <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

        <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>";

        $_par_atr = ['des_fun','des_acc','des_mis'];
        $_ton_atr = ['des_acc'];  
        $_sel_atr = ['des_car','des_des'];  
        foreach( Hol::_('sel_par') as $_par ){
          
          $_kin_par = $_par->ide == 'des' ? $_kin : Hol::_('kin',$_kin->{"par_{$_par->ide}"});
  
          $ite = [ Hol::ima("kin",$_kin_par) ];
  
          foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= Tex::let($_par->$atr); }
  
          $_ton_par = Hol::_('ton',$_kin_par->nav_ond_dia);
          foreach( $_ton_atr as $atr ){ if( isset($_ton_par->$atr) ) $ite []= Tex::let($_ton_par->$atr); }
  
          $_sel_par = Hol::_('sel',$_kin_par->arm_tra_dia);            
          foreach( $_sel_atr as $atr ){  if( isset($_sel_par->$atr) ) $ite []= Tex::let($_sel_par->$atr); }
  
          $_ []= $ite;
        }
        $_ = $htm.Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        break;
      // - Lectura: por palabras clave
      case 'par_lec':
        $_ = [];
        $htm = "
        <p>En <a href='{$_bib}tut#_04-04-' target='_blank'>este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

        <p>Puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>";
        
        foreach( Hol::_('sel_par') as $_par ){

          if( $_par->ide == 'des' ) continue;
          $_kin_par = Hol::_('kin',$_kin->{"par_{$_par->ide}"});
          $_sel_par = Hol::_('sel',$_kin_par->arm_tra_dia);
          $_ []=
          Hol::ima("kin",$_kin_par)."

          <div>
            <p><b class='tit'>{$_kin_par->nom}</b> <c>(</c> ".Tex::let($_par->dia)." <c>)</c></p>
            <p>".Tex::let("{$_sel_par->acc} {$_par->des_pod} {$_sel_par->des_car}, que {$_par->mis} {$_sel->des_car}, {$_par->acc} {$_sel_par->des_pod}.")."</p>
          </div>";
        }

        Ele::cla($ope['lis'],'ite');
        $_ = $htm.Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        break;
      // - Ciclos : Posiciones en ciclos del kin
      case 'par_cic': 
        $_ = [];
        $htm = "
        <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

        <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>";

        $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
  
        foreach( Hol::_('sel_par') as $_par ){
          
          $_kin_par = $_par->ide == 'des' ? $_kin : Hol::_('kin',$_kin->{"par_{$_par->ide}"});

          $ite = [ Hol::ima("kin",$_kin_par) ];

          foreach( $_atr as $atr ){
            $ite []= Hol::ima("kin_{$atr}",$_kin_par->$atr,[ 'class'=>"tam-5" ]);
          }
          
          $_ []= $ite;
        }

        $_ = $htm.Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        break;
      // - Grupos : Sincronometría del holon por sellos      
      case 'par_gru': 
        $_ = [];
        $htm = "
        <p>Puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='{$_bib}tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

        <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>";

        $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];  

        foreach( Hol::_('sel_par') as $_par ){
          
          $_kin_par = $_par->ide == 'des' ? $_kin : Hol::_('kin',$_kin->{"par_{$_par->ide}"});                            
  
          $_sel_par = Hol::_('sel',$_kin_par->arm_tra_dia);
  
          $ite = [ Hol::ima("kin",$_kin_par), $_par->nom, $_sel_par->des_pod ];
  
          foreach( $_atr as $atr ){
            $ite []= Hol::ima("sel_{$atr}",$_sel_par->$atr,[ 'class'=>"tam-5" ]);
          }            
          $_ []= $ite;
        }

        $_ = $htm.Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);       
        break;
      }
      break;
    }
    return $_;
  }

  // tablero
  static function tab( string $est, string $atr, array $ope = [], array $ele = [] ) : string {
    extract( Dat::tab_dat("hol",$est,$atr,$ope,$ele) );
    $_ = "";
    switch( $tab ){
    case 'sol':
      switch( $atr ){
      // Sistema Solar ( vertical : T.K. )
      case 'pla': 
        $sec = Dat::tab_sec($ope,['pla','orb','ele','cel','cir']); $_ = "
        <ul".Ele::atr($ele['sec']).">";
          // imágenes: galaxia + sol
          foreach( ['gal'=>[ 'Galaxia' ],'sol'=>[ 'Sol' ] ] as $i=>$v ){ $_ .= "
            <li class='sec ima $i'>
              ".Fig::ima("hol/tab/$i")."
            </li>";
          }
          // 2 respiraciones : x10 flechas
          foreach( Hol::_('sol_res') as $v ){ 
            for( $i = 1; $i <= 10; $i++ ){ $_ .= "
              <li class='sec ima res-{$v->ide} ide-$i'>".
                Hol::ima("sol_res",$v)."
              </li>";
            }
          }// x 4 flujos : alfa <-> omega
          foreach( Hol::_('flu') as $v ){ $_ .= "
            <li class='sec ima flu-{$v->ide} pod-{$v->pod}'>".
              Hol::ima("flu_pod",$v->pod)."
            </li>";
          }
          // 10 planetas
          foreach( Hol::_('sol_pla') as $v ){ 
            $cla = ( $sec['pla'] && ( empty($sec['pla']) || in_array($v->ide,$sec['pla']) ) ) ? "" : " ".DIS_OCU;
            $_ .= "
            <li class='sec bor pla-{$v->ide}{$cla}'></li>
            <li class='sec ima pla-{$v->ide}'>".Hol::ima("sol_pla",$v)."</li>";
          }
          // Secciones por Seleccion
          // - 2 grupos orbitales
          foreach( Hol::_('sol_orb') as $v ){ 
            $cla = ( $sec['orb'] !== FALSE && ( empty($sec['orb']) || in_array($v->ide,$sec['orb']) ) ) ? "" : " ".DIS_OCU; 
            $_ .= "
            <li class='sec bor orb-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.sol_orb",$v)."'></li>";
          }// - 4 elementos/clanes
          foreach( Hol::_('sel_cro_ele') as $v ){ 
            $cla = ( $sec['ele'] !== FALSE && ( empty($sec['ele']) || in_array($v->ide,$sec['ele']) ) ) ? "" : " ".DIS_OCU; 
            $_ .= "
            <li class='sec bor ele-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }// - 5 células solares
          foreach( Hol::_('sol_cel') as $v ){ 
            $cla = ( $sec['cel'] !== FALSE && ( empty($sec['cel']) || in_array($v->ide,$sec['cel']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor cel-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.sol_cel",$v)."'></li>";
          }// - 5 circuitos de telepatía
          foreach( Hol::_('sol_cir') as $v ){ 
            $cla = ( $sec['cir'] !== FALSE && ( empty($sec['cir']) || in_array($v->ide,$sec['cir']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor cir-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.sol_cir",$v)."'></li>";
          }
          // posicion: 20 sellos solares
          foreach( Hol::_('sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel'>
              ".Hol::ima("sel_cod",$v)."
            </li>";
          }$_ .= " 
        </ul>";        
        break;
      // Sistema Solar ( circular : E.S. )
      case 'cel':
        $sec = Dat::tab_sec($ope,['pla','orb','ele','cel','cir']);
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          // fondos: 
          foreach( ['map','ato'] as $i ){ $_ .= "
            <li class='sec fon $i'></li>";
          }
          // opciones: respiracion, clanes, celulas, circuitos
          foreach( ['res','cel','cir'] as $i ){ $_ .= "
            <li class='sec fon $i'></li>";
          }
          // fichas: planetas
          foreach( Hol::_('sol_pla') as $v ){ $_ .= "
            <li class='sec pla-$v->ide'>
              ".Hol::ima('sol_pla',$v)."
            </li>";
          }
          // posicion: sellos
          foreach( Hol::_('sel') as $v ){ $_ .= "
            <li class='pos ide-$v->ide sel'>
              ".Hol::ima('sel_cod',$v)."
            </li>";
          }
          $_ .= " 
        </ul>";            
        break;
      }
      break;
    case 'pla':
      switch( $atr ){
      case 'map': 
        $sec = Dat::tab_sec($ope,['res','ele','hem','mer','cen']); $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon map'></li>
          <li class='sec fon sel'></li>";
          // fondos: flujos, 
          foreach( ['res','ele'] as $i ){ 
            $cla = ( $sec[$i] !== FALSE ) ? "" : " ".DIS_OCU; $_ .= "
            <li class='sec fon {$i}{$cla}'></li>";
          }
          // 3 Hemisferios
          foreach( Hol::_('pla_hem') as $v ){
            $cla = ( $sec['hem'] !== FALSE && ( empty($sec['hem']) || in_array($v->ide,$sec['hem']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor hem-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.sol_hem",$v)."'></li>";
          }          
          // 2 Meridianos
          foreach( Hol::_('pla_mer') as $v ){
            $cla = ( $sec['mer'] !== FALSE && ( empty($sec['mer']) || in_array($v->ide,$sec['mer']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor mer-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.sol_mer",$v)."'></li>";
            if( $v->ide == 1 ){ $_ .= "
              <li class='sec bor mer-{$v->ide}-0{$cla}' title='".Dat::get_val('tit',"hol.sol_mer",$v)."'></li>";
            }
          }
          // 5 Centros galácticos
          foreach( Hol::_('pla_cen') as $v ){
            if( $sec['cen'] !== FALSE ){ $cla = in_array($v->ide,$sec['cen']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='sec ima cen-{$v->ide}{$cla}'>
              ".Hol::ima("sel_cro_fam",$v->fam)."
            </li>";
          }
          // 20 Sellos solares
          foreach( Hol::_('sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel'>
              ".Hol::ima("sel",$v)."
            </li>";
          }
          $_ .= "
        </ul>";          
        break;
      }      
      break;
    case 'hum':
      switch( $atr ){
      case 'map': 
        $sec = Dat::tab_sec($ope,['res','ext','cen','cha','art','ded']); $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon map'></li>";
          // 2 Lados del Cuerpo : Respiración del Holon
          foreach( Hol::_('hum_res') as $v ){
            $cla = ( $sec['res'] !== FALSE && ( empty($sec['res']) || in_array($v->ide,$sec['res']) ) ) ? "" : " ".DIS_OCU; $_ .= "
            <li class='sec bor res-{$v->ide}{$cla}' title='".Dat::get_val('tit',"hol.hum_res",$v)."'></li>";
          }          
          // 5 Centros Galácticos : Familias Terrestres
          if( $sec['cen'] !== FALSE ){ $_ .= "
            <li class='sec fon cen'></li>
            <li class='sec fon ded'></li>";
          }
          foreach( Hol::_('hum_cen') as $v ){
            if( $sec['cen'] !== FALSE ){ $cla = in_array($v->ide,$sec['cen']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='sec ima cen-{$v->ide}{$cla}'>
              ".Hol::ima("hum_cen",$v)."
            </li>";
          }
          // 4 Extremidades : Clanes Cromáticos
          foreach( Hol::_('hum_ext') as $v ){
            if( $sec['ext'] !== FALSE ){ $cla = in_array($v->ide,$sec['ext']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='sec bor ext-{$v->ide}{$cla}'></li>";
          }          
          // 20 Dedos : Sellos Solares
          foreach( Hol::_('sel') as $v ){ 
            if( $sec['ded'] !== FALSE ){ $cla = in_array($v->ide,$sec['ded']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='pos ide-{$v->ide} sel{$cla}'>
              ".Hol::ima("sel",$v)."
            </li>";
          }
          // 7 Chakras : Plasmas Radiales
          foreach( Hol::_('rad') as $v ){ 
            if( $sec['cha'] !== FALSE ){ $cla = in_array($v->ide,$sec['cha']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='pos ide-{$v->ide} rad{$cla}'>
              ".Hol::ima("rad",$v)."
            </li>";
          }
          // 13 Articulaciones : Tonos Galácticos
          foreach( Hol::_('ton') as $v ){ 
            if( $sec['art'] !== FALSE ){ $cla = in_array($v->ide,$sec['art']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='pos ide-{$v->ide} ton{$cla}'>
              ".Hol::ima("ton",$v)."
            </li>";
          }
          $_ .= "
        </ul>";     
        break;      
      }      
      break;
    case 'tel':
      switch( $atr ){
      case 'map': 
        $sec = Dat::tab_sec($ope,['pla','orb','ele','cel','cir']); $_ = "
        <ul".Ele::atr($ele['sec']).">";
          // posicion: 20 sellos del holon solar
          foreach( Hol::_('sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel sol'>
              ".Hol::ima("sel_cod",$v)."
            </li>";
          }
          // posicion: 20 sellos del circuito de recarga
          foreach( Hol::_('sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel cod'>
              ".Hol::ima("sel_cod",$v)."
            </li>";
          }
          // posicion: 28 plasmas
          foreach( Hol::_('lun') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} lun'>
              ".Hol::ima("rad",$v->rad)."
            </li>";
          }          
          $_ .= " 
        </ul>";            
        break;
      }      
      break;
    case 'rad':
      switch( $atr ){
      case 'pla':
        Ele::cla($ele['sec'],"hol_hep"); 
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Hol::_('rad') as $_rad ){ 
            $ele['pos'] = $ele_pos;
            // cargo datos de la posicion
            $ele['pos']['data-hol_rad'] = $_rad->ide;
            $_ .= Dat::tab_pos('hol',$est,$_rad,$ope,$ele);
          }$_ .= "
        </ul>";        
        break;
      }
      break;
    case 'ton':
      switch( $atr ){
      // onda encantada
      case 'ond':
        Ele::cla($ele['sec'],"dat tab hol ton ond hol_ton",'ini');
        $_ .= "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('ton',$ope,$ele)
          ;
          foreach( Hol::_('ton') as $_ton ){
            // cargo datos de la posicion
            $ele['pos']['data-hol_ton'] = $_ton->ide;
            $_ .= Dat::tab_pos('hol','ton',$_ton,$ope,$ele);
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
        <ul".Ele::atr($ele['sec']).">";
          foreach( Hol::_('sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='doc_val jus-cen'>
                ".Hol::ima("sel",$_sel,['eti'=>"li"])."
                ".Hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='mar-0 ali_pro-cen'>
                {$_sel->arm}
                <br>{$_sel->acc}
                <br>{$_sel->des_pod}
              </p>
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // parejas del oráculo
      case 'par':
        if( empty($ide) ) $ide = 1;
        $_sel = is_object($ide) ? $ide : Hol::_('sel',$ide);           
        Ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Hol::_('sel_par') as $_par ){
            $par_ide = $_par->cod;
            // combino elementos
            $ele['pos'] = isset($ele["par-{$par_ide}"]) ? Ele::val_jun($ele_pos,$ele["par-{$par_ide}"]) : $ele_pos;
            Ele::cla($ele['pos'],"par-{$par_ide}");            
            // todos menos el guia
            $par_sel = 0;
            if( $par_ide != 'gui' ){ $par_sel = ( $par_ide == 'des' ) ? $_sel : Hol::_('sel',$_sel->{"par_{$par_ide}"}); }
            $_ .= Dat::tab_pos('hol','sel',$par_sel,$ope,$ele);
          }$_ .= "
        </ul>";
        break;
      // colocacion cromática
      case 'cro':
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='pos ide-0'></li>";
          foreach( Hol::_('sel_cro_fam') as $v ){ 
            $_ .= Hol::ima("sel_cro_fam",$v,[ 'eti'=>"li", 'class'=>"sec fam-{$v->ide}" ]);
          } 
          foreach( Hol::_('sel_cro_ele') as $v ){ 
            $_ .= Hol::ima("sel_cro_ele",$v,[ 'eti'=>"li", 'class'=>"sec ele-{$v->ide}"]);
          }
          $ele_pos = $ele['pos'];
          for( $i=0; $i<=19; $i++ ){ 
            $v = Hol::_('sel', ( $i == 0 ) ? 20 : $i);
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _val-pos' : '' ;
            $ele['pos'] = $ele_pos;
            Ele::cla($ele['pos'],"{$agr}");
            $_ .= Dat::tab_pos('hol','sel',$v,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      // colocacion armónica
      case 'arm':
        $_ .= "
        <ul".Ele::atr($ele['sec']).">
          <li class='pos ide-0'></li>
          ";
          foreach( Hol::_('sel_arm_cel') as $v ){ 
            $_ .= Hol::ima("sel_arm_cel",$v,[ 'eti'=>"li", 'class'=>"sec cel-{$v->ide}"]);
          } 
          foreach( Hol::_('sel_arm_raz') as $v ){ 
            $_ .= Hol::ima("sel_arm_raz",$v,[ 'eti'=>"li", 'class'=>"sec raz-{$v->ide}"]);
          }
          $ele_pos = $ele['pos'];
          foreach( Hol::_('sel') as $v ){
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _val-pos' : '' ;
            $ele['pos'] = $ele_pos;
            Ele::cla($ele['pos'],"{$agr}");
            $_ .= Dat::tab_pos('hol','sel',$v,$ope,$ele);
          }
          $_ .= "
        </ul>";        
        break;
      // tablero del oráculo
      case 'arm_tra':
        Ele::cla($ele['sec'],"hol_cro");
        $_ .= "
        <ul".Ele::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){
            $ope['ide'] = $i;            
            $_ .= "
            <li class='pos ide-{$i}'>
              ".Hol::tab('sel','arm_cel',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;      
      // célula del tiempo para el oráculo
      case 'arm_cel':
        $_arm = Hol::_('sel_arm_cel',$ide);        
        $ele['cel']['title'] = Dat::get_val('tit',"hol.{$est}",$_arm); 
        Ele::cla($ele['cel'],"dat tab hol sel {$atr} hol_arm",'ini');
        $_ = "
        <ul".Ele::atr($ele['cel']).">
          ".Hol::ima("sel_arm_cel", $_arm, ['eti'=>"li", 'class'=>"pos ide-0", 'htm'=>$_arm->ide ] );
          foreach( explode(', ',$_arm->sel) as $sel ){
            $_ .= Dat::tab_pos('hol','sel',$sel,$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    case 'lun':
      switch( $atr ){
      // 28 plasmas en 4 heptadas
      case 'pla':
        Ele::cla($ele['sec'],"hol_lun bor-1"); 
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Hol::_('lun') as $_lun ){ 
            $ele['pos'] = $ele_pos;
            // cargo datos de la posicion
            $ele['pos']['data-hol_lun'] = $_lun->ide;
            $ele['pos']['data-hol_rad'] = $_lun->rad;
            Ele::cla($ele['pos'],"fon_col-4-".$_lun->arm." bor-1 bor-luz");
            $ele['pos']['htm'] = "
              ".Hol::ima('rad',$_lun->rad,['class'=>"mar-1"])."
              ".Num::var('val',$_lun->ide,['class'=>"tex-3"])."
            ";
            $_ .= Dat::tab_pos('hol',$est,$_lun,$ope,$ele);
          }$_ .= "
        </ul>";
        break;
      }
      break;
    case 'cas':
      switch( $atr ){
      // ondas encantadas
      case 'ond':
        Ele::cla($ele['sec'],"hol_cas");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon-ima'></li>          
          ".Hol::tab_sec('cas',$ope,$ele)."
          <li class='pos ide-0'></li>";

          foreach( Hol::_('cas') as $_cas ){
            // cargo datos de la posicion
            $ele['pos']['data-hol_cas'] = $_cas->ide;
            $ele['pos']['data-hol_ton'] = $_cas->ton;
            $_ .= Dat::tab_pos('hol','cas',$_cas,$ope,$ele);
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
        if( $ton_val ) Ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");

        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          // 1° columna
          if( $ton_htm && $sel_htm ){ $_ .= "
            <li".Ele::atr([ 'class' => "sec ini".( $ton_val && $sel_val ? "" : " dis-ocu" )])."></li>";
          }
          // filas por sellos
          if( $sel_htm ){
            foreach( Hol::_('sel') as $v ){ $_ .= "
              <li class='sec sel ide-{$v->ide}".( $sel_val ? "" : " dis-ocu" )."'>".Hol::ima("sel",$v)."</li>";
            }
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( Hol::_('kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){ $_ .= 
              "<li class='sec ton ide-{$_kin->arm_tra}".( $ton_val ? "" : " dis-ocu" )."'>
                ".Hol::ima("kin_arm_tra",$_kin->arm_tra)."
              </li>";
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            $_ .= Dat::tab_pos('hol','kin',$_kin,$ope,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </ul>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par':
        if( empty($ide) ) $ide = 1;
        $_kin = is_object($ide) ? $ide : Hol::_('kin',$ide);           
        Ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Hol::_('sel_par') as $_par ){
            $par_ide = $_par->cod;
            $par_kin = ( $par_ide == 'des' ) ? $_kin : Hol::_('kin',$_kin->{"par_{$par_ide}"});
            // combino elementos :
            $ele['pos'] = isset($ele["par-{$par_ide}"]) ? Ele::val_jun($ele_pos,$ele["par-{$par_ide}"]) : $ele_pos;
            Ele::cla($ele['pos'],"par-{$par_ide}");
            $_ .= Dat::tab_pos('hol','kin',$par_kin,$ope,$ele);
          }$_ .= "
        </ul>";
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_fam = Hol::_('sel_cro_fam',$ide);
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('cas',$ope,$ele)."
          <li class='pos ide-0'>
            ".Hol::ima('sel_cro_fam',$_fam)."
          </li>";

          $kin = intval($_fam['kin']);
          foreach( Hol::_('cas') as $_cas ){
            $_kin = Hol::_('kin',$kin);
            $_ .= Dat::tab_pos('hol','kin',$kin,$ope,$ele);
            $kin = Num::val_ran($kin + 105, 260);
          } $_ .= "
        </ul>";          
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        Ele::cla($ele['sec'],"hol_cro");
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          foreach( Hol::_('kin_nav_cas') as $cas => $_cas ){
            $ope['ide'] = $_cas->ide; $_ .= "
            <li class='pos ide-".intval($_cas->ide)."'>
              ".Hol::tab('kin','nav_cas',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";
        break;
      case 'nav_cas':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Hol::_('kin',$val['kin'])->$atr;
        $_cas = Hol::_($est,$ide);
        // clases del castillo
        if( !isset($ele['cas']) ) $ele['cas'] = [];
        Ele::cla($ele['cas'],"dat tab kin {$atr} hol_cas fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ),'ini');
        // titulos
        $ele['cas']['title'] = Dat::get_val('tit',"hol.{$est}",$_cas);        
        $ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ini + 4;        
        for( $ond = $ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = Hol::_('kin_nav_ond',$ond);
          $ele['cas']['title'] .= "\n".$_ond->enc_des;
        }
        $_ = "
        <ul".Ele::atr($ele['cas']).">
          ".Hol::tab_sec('cas',$ope,$ele)."
          <li class='pos ide-0'>
            ".Hol::ima('kin_nav_cas',$ide,[ 'title'=>$ele['cas']['title'] ])."
          </li>";
          $kin = ( ( $ide - 1 ) * 52 ) + 1;
          foreach( Hol::_('cas') as $_cas ){
            $_ .= Dat::tab_pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;
      case 'nav_ond':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Hol::_('kin',$val['kin'])->$atr;
        $_ond = Hol::_($est,$ide);
        // clases
        if( !isset($ele['ond']) ) $ele['ond'] = [];
        Ele::cla($ele['ond'],"dat tab kin {$atr} hol_ton",'ini');
        // titulo        
        $ele['ond']['title'] = Dat::get_val('tit',"hol.kin_nav_cas",$_ond->nav_cas)." .\n{$_ond->enc_des}"; 
        $_ = "
        <ul".Ele::atr($ele['ond']).">
          ".Hol::tab_sec('ton',$ope,$ele);

          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          foreach( Hol::_('ton') as $_ton ){
            $_ .= Dat::tab_pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;      
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        Ele::cla($ele['sec'],"hol_ton");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('ton',$ope,$ele);

          foreach( Hol::_('kin_arm_tra') as $_tra ){ 
            $ope['ide'] = $_tra->ide; $_ .= "
            <li class='pos ide-".intval($_tra->ide)."'>
              ".Hol::tab('kin','arm_tra',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      case 'arm_tra':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Hol::_('kin',$val['kin'])->$atr;
        $_tra = Hol::_('kin',$ide);
        $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
        $cel_fin = $cel_ini + 5;

        if( !isset($ele['tra']) ) $ele['tra']=[];
        Ele::cla($ele['tra'],"dat tab kin {$atr} hol_cro",'ini');
        $_ = "
        <ul".Ele::atr($ele['tra']).">";
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $ope['ide'] = $cel; $_ .= "
            <li class='pos ide-".Num::val_ran($cel,5)."'>
              ".Hol::tab('kin','arm_cel',$ope,$ele)."
            </li>";            
          } $_ .= "
        </ul>";
        break;
      case 'arm_cel': 
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Hol::_('kin',$val['kin'])->$atr;
        $_arm = Hol::_($est,$ide);

        if( !isset($ele['cel']) ) $ele['cel']=[];
        Ele::cla($ele['cel'],"dat tab kin {$atr} hol_arm fon_col-5-$_arm->cel fon-0");
        $_ = "
        <ul".Ele::atr($ele['cel']).">
          <li class='pos ide-0 col-bla'>
            ".Hol::ima("sel_arm_cel",$_arm->cel,[ 'htm'=>$_arm->ide, 'title'=>Dat::get_val('tit',"hol.{$est}",$_arm) ])."
          </li>";

          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= Dat::tab_pos('hol','kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro':
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_cas';
        Ele::cla($ele['sec'],"hol_cas");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('cas',$ope,$ele)."

          <li class='pos ide-0'>
            ".Fig::ima('hol/tab/gal')."
          </li>";
          foreach( Hol::_('kin_cro_ele') as $_ele ){
            $ope['ide'] = $_ele->ide; $_ .= "
            <li class='pos ide-".intval($_ele->ide)."'>
              ".Hol::tab('kin','cro_ele',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      case 'cro_est':
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_ond';
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Hol::_('kin',$val['kin'])->$atr;
        $_est = Hol::_('kin_cro_est',$ide); 
        $cas = explode(' - ',$_est->cas)[0];
        
        if( !isset($ele['est']) ) $ele['est']=[];
        Ele::cla($ele['est'],"dat tab kin {$atr} hol_ton",'ini');
        $_ = "
        <ul".Ele::atr($ele['est']).">
          ".Hol::tab_sec('ton',$ope,$ele);
          
          foreach( Hol::_('ton') as $_ton ){
            $ope['ide'] = $cas; $_ .= "
            <li class='pos ide-".intval($_ton->ide)."'>
              ".Hol::tab('kin','cro_ele',$ope,$ele)."
            </li>";
            $cas = Num::val_ran($cas + 1, 52);
          } $_ .= "
        </ul>";
        break;
      case 'cro_ele':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Hol::_('kin',$val['kin'])->$atr;
        $_ele = Hol::_('kin_cro_ele',$ide);
        $ele_rot = [
          "ton" => [ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
          "cas" => [ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
        ];
        
        if( !isset($ele['ele']) ) $ele['ele']=[];
        $ele['ele']['title'] = "{$_ele->ide}: {$_ele->nom}";
        // del castillo | onda : rotaciones
        if( in_array('fic_cas',$opc) || in_array('fic_ond',$opc) ){ Ele::css($ele['ele'],
          "transform: rotate(".(in_array('fic_cas',$opc) ? $ele_rot['cas'][$ide-1] : $ele_rot['ton'][$ide-1])."deg)");
        }
        Ele::cla($ele['ele'],"dat tab kin {$atr} hol_cro-cir",'ini');
        $_ .= "
        <ul".Ele::atr($ele['ele']).">
          <li class='pos ide-0'>
            ".Hol::ima('kin_cro_ele',$_ele->ide)."
          </li>";
          // cuenta desde : kin = 185
          $kin = Num::val_ran( 185 + ( ( $ide - 1 ) * 5 ), 260);
          foreach( Hol::_('sel_cro_fam') as $cro_fam ){
            $_ .= Dat::tab_pos('hol','kin',$kin,$ope,$ele);
            $kin++;// por verdad eléctrica
            if( $kin > 260 ) $kin = 1;
          }$_ .= "
        </ul>";
        break;
      }
      break;
    case 'psi':
      switch( $atr ){
      // Banco-psi
      case 'kin': 
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele['sec'] = isset($ele['tzo']) ? $ele['tzo'] : [];
          for( $i=1 ; $i<=8 ; $i++ ){ $_ .= "
            <li class='pos ide-$i'>
              ".Hol::tab('kin','tzo',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // Ciclo de Sirio
      case 'sir':
        $kin = 34;
        $ope['sec']['orb_cir'] = '1';
        Ele::cla($ele['sec'],'hol_cas-cir');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('cas_cir',$ope,$ele)."
          <li class='pos ide-0'>
          </li>";

          foreach( Hol::_('cas') as $_cas ){
            $_kin = Hol::_('kin',$kin); $_ .= "
            <li class='pos ide-".intval($_cas->ide)."'>
              ".Hol::ima("kin",$_kin)."
            </li>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </ul>";        
        break;
      // Anillo de 13 Lunas
      case 'ani':
        Ele::cla($ele['sec'],'hol_ton');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Fig::ima('hol/tab/sol',['eti'=>"li", 'class'=>"sec sol"])."
          ".Fig::ima('hol/tab/pla',['eti'=>"li", 'class'=>"sec lun"])."
          ".Hol::tab_sec('ton',$ope,$ele)
          ;
          if( !in_array('cab_nom',$ope['opc']) ) $ope['opc'] []= 'cab_nom';
          for( $lun = 1; $lun <= 13; $lun++ ){
            $ope['ide'] = $lun; $_ .= "
            <li class='pos ide-{$lun}'>
              ".Hol::tab('psi','ani_lun',$ope,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // - Giro Lunar: 28 días
      case 'ani_lun':
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = Hol::_('psi',$val['psi'])->$atr;
        $_lun = Hol::_($est,$ide);
        $_ton = Hol::_('ton',$ide);

        $ele['pos']['eti'] = 'td';
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);
        
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        Ele::cla($ele['lun'],"dat tab psi {$atr} hol_lun",'ini'); $_ = "
        <table".Ele::atr($ele['lun']).">";
          if( !$cab_ocu ){ $_ .= "
            <thead>";
              // Luna
              $_ .= "
              <tr data-cab='ton'>
                <th colspan='8'>
                  <div class='doc_val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    ".Hol::ima($est,$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-15 mar-1" )])."

                    ".( $cab_nom ? "
                      <p class='tex ide'>".str_replace(',','',explode(' ',$_lun->tot)[1])."</p>
                    " : "
                      <div>
                        <p class='tex tit tex-4'>
                          <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                        </p>
                        <p class='tex tex-3 mar-1'>
                          ".Tex::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                          <br>".Tex::let($_lun->ond_man)."
                        </p>                   
                        <p class='tex tex-3 mar-1'>
                          Totem<c>:</c> $_lun->tot
                          <br>Propiedades<c>:</c> ".Tex::let($_lun->tot_pro)."
                        </p> 
                      </div>                      
                    " )."
                  </div>
                </th>
              </tr>";
              // Plasmas
              if( !$cab_nom ){ $_ .= "
                <tr data-cab='rad'>
                  <th>
                    <span class='tex_ali-der'>Plasma</span>
                    <span class='tex_ali-cen'><c>/</c></span>                    
                    <span class='tex_ali-izq'>Héptada</span>
                  </th>";
                  foreach( Hol::_('rad') as $_rad ){ $_ .= "
                    <th>
                      ".Hol::ima("rad",$_rad,[])."
                    </th>";
                  }$_ .= "                  
                </tr>";
              }$_ .="
            </thead>";
          }$_ .= "
          <tbody>";
            $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
            $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
            for( $arm = 1; $arm <= 4; $arm++ ){ $_ .= "
              <tr>
                <td class='sec hep fon_col-4-{$arm}'>
                  ".Hol::ima("psi_hep_pla",$hep)."
                </td>";
                for( $rad=1; $rad<=7; $rad++ ){
                  $_ .= Dat::tab_pos('hol','psi',$psi,$ope,$ele);
                  $psi++;
                }
                $hep++; 
                $_ .= "
              </tr>";
            }$_ .= "
          </tbody>
        </table>";
        break;
      // - totems Lunares
      case 'ani_lun_tot':
        Ele::cla($ele['sec'],'hol_ton');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('ton',$ope,$ele)
          ;
          $ele_pos = $ele['pos'];
          foreach( Hol::_('psi_ani_lun') as $_lun ){
            $ele['pos'] = $ele_pos;
            Ele::cla($ele['pos'],"pos ide-".intval($_lun->ide)." bor-1 pad-1 bor-luz",'ini');
            $ele['pos']['htm'] = "            
            ".Tex::var('val',$_lun->nom,['class'=>"tit tog mar_arr-0"])."
            ".Tex::var('val',"$_lun->fec_ini - $_lun->fec_fin",['class'=>"des"])."
            ".Tex::var('val',$_lun->tot,['class'=>"tog"])."
            ".Hol::ima('psi_ani_lun',$_lun)
            ;
            $_ .= Dat::tab_pos('hol',$est,$_lun,$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      // Castillo de 52 Heptadas:
      case 'hep':
        Ele::cla($ele['sec'],'hol_cas');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('cas',$ope,$ele)."
          <li class='pos ide-0'>
            ".Fig::ima('hol/tab/pla')."
          </li>";

          foreach( Hol::_('cas') as $_cas ){
            $ope['ide'] = $_cas->ide; $_ .= "
            <li class='pos ide-".intval($_cas->ide)."'>
              ".Hol::tab('psi','hep_pla',$ope,$ele)."
            </li>";            
          } $_ .= "
        </ul>";        
        break;
      // - 1 de 4 Estaciones Solares
      case 'hep_est':
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = Hol::_('psi',$val['psi'])->$atr;        
        $_est = Hol::_('psi_hep_est',$ide); 
        $cas = explode(' - ',$_est->hep_pla)[0];

        Ele::cla($ele['sec'],'hol_ton');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Hol::tab_sec('ton',$ope,$ele);

          foreach( Hol::_('ton') as $_ton ){
            $ope['ide'] = $cas; $_ .= "
            <li class='pos ide-".intval($_ton->ide)."'>
              ".Hol::tab('psi','hep_pla',$ope,$ele)."
            </li>";
            $cas++; 
          } $_ .= "
        </ul>";  
        break;
      // - 7 Plasmas Radiales
      case 'hep_pla':
        if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = Hol::_('psi',$val['psi'])->$atr;        
        $_hep = Hol::_('psi_hep_pla',$ide);
        $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
        
        if( !isset($ele['hep']) ) $ele['hep']=[];
        Ele::cla($ele['hep'],"dat tab psi {$atr} hol_hep",'ini');
        $_ = "
        <ul".Ele::atr($ele['hep']).">";
          foreach( Hol::_('rad') as $_rad ){
            $_ .= Dat::tab_pos('hol','psi',$psi,$ope,$ele);
            $psi++;
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    }
    return $_;
  }// Datos: cargo opciones
  static function tab_dat( string $est, string $atr, array &$ope = [], array &$ele = [] ) : void {
    
    // pulsares por posicion
    $ope['val_pos_pul_dim'] = isset($ope['pos']['pul']) && ( empty($ope['pos']['pul']) || in_array("dim",$ope['pos']['pul']) );
    $ope['val_pos_pul_mat'] = isset($ope['pos']['pul']) && ( empty($ope['pos']['pul']) || in_array("mat",$ope['pos']['pul']) );
    $ope['val_pos_pul_sim'] = isset($ope['pos']['pul']) && ( empty($ope['pos']['pul']) || in_array("sim",$ope['pos']['pul']) );
    
    // pulsares por valor
    $ope['val_pul_dim'] = isset($ope['pul']['dim']);
    $ope['val_pul_mat'] = isset($ope['pul']['mat']);
    $ope['val_pul_sim'] = isset($ope['pul']['sim']);

  }// Seccion: onda encantada + castillo
  static function tab_sec( string $tip, array $ope=[], array $ele=[] ) : string {
    $_ = "";
    $_tip = explode('_',$tip);
    $ele_eti = $ele['pos']['eti'];
    // fondos: imagen y color
    $ele_ite = isset($ele['fon-ima']) ? $ele['fon-ima'] : [];
    Ele::cla($ele_ite,"sec fon ima ".DIS_OCU,'ini'); $_ .= "
    <{$ele_eti}".Ele::atr($ele_ite).">
    </{$ele_eti}>";

    $ele_ite = isset($ele['fon-col']) ? $ele['fon-col'] : [];
    Ele::cla($ele_ite,"sec fon col ".DIS_OCU,'ini'); $_ .= "
    <{$ele_eti}".Ele::atr($ele_ite).">
    </{$ele_eti}>";

    // pulsares
    if( in_array($_tip[0],['ton','cas']) ){
      // cargo imágenes
      $_pul = [ 
        'dim'=>[], 'mat'=>[], 'sim'=>[]
      ];
      foreach( $_pul as $ide => $lis ){
        foreach( Hol::_("ton_{$ide}") as $ton_pul ){
          $_pul[$ide] []= Fig::ima("hol/tab/gal/$ide/{$ton_pul->ide}");
        }
      }
      if( isset($ope['val']['pos']) ){
        $_val = $ope['val']['pos'];
        if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || is_numeric($_val) ||( is_object($_val) && isset($_val->ide) ) ){
          if( is_numeric($_val) ){
            $_ton = Hol::_('ton',Num::val_ran($_val,13));
          }else{
            $_ton = Hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
          }
        }
      }
    }
    switch( $_tip[0] ){
    // onda
    case 'ton':
      // pulsares
      foreach( $_pul as $ide => $lis ){ 
        foreach( $lis as $pul_pos => $pul_ima ){
          $pos = $pul_pos + 1;
          $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
          $cla_agr = " ".DIS_OCU;
          if( isset($_ton) ){
            if( !!$ope["val_pos_pul_$ide"] && $_ton->$ide == $pos ) $cla_agr = "";
          }
          elseif( !!$ope["val_pul_$ide"] && ( empty($ope['pul'][$ide]) || in_array($pos,$ope['pul'][$ide]) ) ){
            $cla_agr = "";
          }
          Ele::cla($ele_ite,"sec fon ond pul {$ide}-{$pos}{$cla_agr}",'ini');
          $_ .= "
          <{$ele_eti}".Ele::atr($ele_ite).">{$pul_ima}</{$ele_eti}>";
        }
      }
      break;
    // castillo
    case 'cas':
      $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : ' dis-ocu';
      $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';
      
      // fondos: imagen
      $ele_fon = isset($ele["fon-ima"]) ? $ele["fon-ima"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_fon;
        Ele::cla($ele_ite,"sec fon ima ond-$i ".DIS_OCU,'ini'); $_ .= "
        <{$ele_eti}".Ele::atr($ele_ite).">
        </{$ele_eti}>";
      }
      // fondos: color
      $ele_fon = isset($ele["fon-col"]) ? $ele["fon-col"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_fon;
        Ele::cla($ele_ite,"sec fon col ond-$i fon_col-4-{$i}{$col_ocu}",'ini'); $_ .= "
        <{$ele_eti}".Ele::atr($ele_ite)."></{$ele_eti}>";
      }        
      // bordes: orbitales
      $ele_orb = isset($ele["orb"]) ? $ele["orb"] : [];
      for( $i = 1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ 
        $ele_ite = $ele_orb;
        Ele::cla($ele_ite,"sec fon orb-{$i}{$orb_ocu}",'ini'); $_ .= "
        <{$ele_eti}".Ele::atr($ele_ite)."></{$ele_eti}>";
      }      
      // fondos: pulsares
      foreach( $_pul as $ide => $lis ){ 
        // reocrro 4 ondas
        for( $i = 1; $i <= 4; $i++ ){
          // recorro pulsares
          foreach( $lis as $pul_pos => $pul_ima ){
            $pos = $pul_pos + 1;
            $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
            $cla_agr = " ".DIS_OCU;
            if( isset($_ton) ){
              if( !!$ope["val_pos_pul_$ide"] && $_ton->$ide == $pos ) $cla_agr = "";
            }
            elseif( !!$ope["val_pul_$ide"] && ( empty($ope['pul'][$ide]) || in_array($pos,$ope['pul'][$ide]) ) ){
              $cla_agr = "";
            }
            Ele::cla($ele_ite,"sec fon ond-{$i} pul {$ide}-{$pos}{$cla_agr}",'ini');
            $_ .= "
            <{$ele_eti}".Ele::atr($ele_ite).">{$pul_ima}</{$ele_eti}>";
          }
        }
      }
      break;      
    }
    return $_;
  }// Posicion: titulos + patrones
  static function tab_pos( string $est, mixed $val, array &$ope, array &$ele ) : string {
    $_ = "";    
    // opciones:
    if( !isset($ope['val_kin_pag']) ) $ope['val_kin_pag'] = !empty($ope['pag']['kin']);
    if( !isset($ope['val_psi_pag']) ) $ope['val_psi_pag'] = !empty($ope['pag']['psi']);
    if( !isset($ope['val_sec_par']) ) $ope['val_sec_par'] = !empty($ope['sec']['par']);
    ///////////////////////////////////////////////////////////////////////////////////
    // armo titulos y cargo operadores ////////////////////////////////////////////////
    $cla_agr = [];
    $pos_tit = [];
    if( isset($ele["data-fec_dat"]) ){
      $pos_tit []= "Calendario: {$ele["data-fec_dat"]}";
    }
    if( isset($ele["data-hol_kin"]) ){
      $_kin = Hol::_('kin',$ele["data-hol_kin"]);
      $pos_tit []= Dat::get_val('tit',"hol.kin",$_kin);
      if( $ope['val_kin_pag'] && !empty($_kin->pag) ) $cla_agr []= "_hol-pag_kin";
    }
    if( isset($ele["data-hol_sel"]) ){
      $pos_tit []= Dat::get_val('tit',"hol.sel",$ele["data-hol_sel"]);
    }
    if( isset($ele["data-hol_ton"]) ){
      $pos_tit []= Dat::get_val('tit',"hol.ton",$ele["data-hol_ton"]);
    }
    if( isset($ele["data-hol_psi"]) ){
      $_psi = Hol::_('psi',$ele["data-hol_psi"]);
      $pos_tit []= Dat::get_val('tit',"hol.psi",$_psi);          
      if( $ope['val_psi_pag'] ){
        $_psi->kin = Hol::_('kin',$_psi->kin);
        if( !empty($_psi->kin->pag) ) $cla_agr []= "_hol-pag_psi";
      }
    }
    if( isset($ele["data-hol_rad"]) ){
      $pos_tit []= Dat::get_val('tit',"hol.rad",$ele["data-hol_rad"]);
    }
    // titulo
    if( !empty($pos_tit) ) $ele['title'] = implode("\n\n",$pos_tit);
    // operadores: .pos.dep + .pos_hol-pag_kin + .pos_hol-pag_psi
    if( !empty($cla_agr) ) Ele::cla($ele,$cla_agr);
    
    ///////////////////////////////////////////////////////////////////////////////////
    // modifico html por patrones: posicion por dependencia
    if( !!$ope['val_sec_par'] ){

      $par_est = $est;
      $par_ele = [ 
        'pos'=>[], // para todas las posiciones: bordes, color, imagen
        'des'=>[] // para el destino : posicion principal
      ];
      
      // cambio clases del operador
      if( !empty($ele['class']) ){
        $par_ele['pos']['class'] = $ele['class'];
        unset($ele['class']);
      }

      // cambio datos del operador
      if( isset($ele["data-fec_dat"]) ){
        // cambio valor 
        if( $est == 'psi' && isset($ele["data-hol_kin"]) ){
          $par_est = "kin";
          $val = $ele["data-hol_kin"];
        }
        // cambio evento
        if( isset($ele['onclick']) ){ 
          $par_ele['pos']['onclick'] = $ele['onclick'];
          unset($ele['onclick']);
        }
        // cargo datos de la fecha por posicion del oraculo
        if( $par_est != $est ){
          $_kin = Hol::_('kin',$val);
          $ele_par = [ 'ana'=>[], 'gui'=>[], 'ant'=>[], 'ocu'=>[] ];
          foreach( $ele_par as $par_ide => &$par_dat ){
            foreach( $ope['dat'] as $ope_dat ){
              if( $ope_dat['hol_kin'] == $_kin->{"par_{$par_ide}"} ){
                $par_dat = $ope_dat;
                break;
              }
            }
          }
          $ele_par['des'] = $ele;
          foreach( ['fec_dat','hol_psi','hol_lun','hol_rad'] as $ele_dat ){
            $par_ele['par-des']["data-{$ele_dat}"] = $ele_par['des']["data-{$ele_dat}"];
            $par_ele['par-ana']["data-{$ele_dat}"] = $ele_par['ana']["{$ele_dat}"];
            $par_ele['par-gui']["data-{$ele_dat}"] = $ele_par['gui']["{$ele_dat}"];
            $par_ele['par-ant']["data-{$ele_dat}"] = $ele_par['ant']["{$ele_dat}"];
            $par_ele['par-ocu']["data-{$ele_dat}"] = $ele_par['ocu']["{$ele_dat}"];
          }
        }
      }
      Ele::cla($ele,'dep');      
      $_ = Hol::tab($par_est,'par',[
        'ide' => $val,
        'sec' => [ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal
        'pos' => isset($ope['pos']) ? $ope['pos'] : [ 'ima'=>"hol.{$par_est}.ide" ]
      ],$par_ele);
    }

    return $_;
  }
}