<?php

if( !class_exists("Usuario") ){

  require_once("./Api/sincronario/Usuario.php");
}

class Sincronario {
    
  // Valores
  public array $Val = [
    'kin' => 0,
    'psi' => 0,
    'sin' => ""
  ];  

  public function __construct( mixed $fec = "" ){

    if( !empty($fec) ){

      $this->Val = $this->val( $fec );
    }
  }

  /* Proceso de Valores */
    
  // busco valores : fecha - sir - kin - psi
  static function val( mixed $val ) : array | object | string {
    $_=[];
    
    // del sincronario
    if( is_string($val) && preg_match("/\./",$val) ){
        // busco año          
        if( $fec = Self::val_cod($val) ){

          // convierto fecha
          $_ = Self::val($fec);

          if( is_string($_) ){ 

            $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
          }
        }
        else{ 
          $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
        }
    }
    // del calendario
    elseif( $fec = Fec::dat($val) ){
      
      // giro solar => año
      $_['fec'] = $fec->val;

      $Fec = Self::val_dec( $fec );

      $Psi = Dat::get( Dat::_('hol.psi'), [ 
        'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 
        'opc'=>[ 'uni' ]
      ]);

      // giro lunar => mes + día
      if( !empty($Psi) ){

        $_['psi'] = $Psi->ide;

        $_['sin'] = "{$Fec->sir}.".Num::val($Fec->ani,2).".{$Psi->lun}.{$Psi->lun_dia}";
        
        // giro galáctico => kin
        $Kin = Dat::_('hol.kin', Num::ran( $Fec->fam_2 + $Psi->fec_cod + $Fec->dia, 260 ) );

        if( is_object($Kin) ){

          $_['kin'] = $Kin->ide;
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
      $Psi = Dat::get( Dat::_('hol.psi'), [ 'ver'=>[ ['lun','==',$lun], ['lun_dia','==',$dia] ], 'opc'=>['uni'] ]);      
  
      if( isset($Psi->fec_mes) && isset($Psi->fec_dia) ){

        $_ = $Psi->fec_mes.'/'.$Psi->fec_dia;
      
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
            $_->$atr = Num::ran($_->$atr+105, 260); 
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
            $_->$atr = Num::ran($_->$atr-105, 260); 
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
          $_->$atr = Num::ran($_->$atr-105, 260); 
        }
      }
    }
    else{
      $_ = "{-_-} la Fecha {$val} no es Válida"; 
    }
    return $_;
  }// sumo o resto dias de un fecha dada
  static function val_fec( string $tip, string $sin, int $cue = 1, string $opc = 'dia' ) : string {

    $_ = $sin;

    $val = explode('.',$sin);

    if( isset($val[3]) ){
      
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
            $dia = Num::ran($dia, 28);
            
            if( $lun > 13 ){
              $ani += Num::val_red($lun / 13);
              $lun = Num::ran($lun, 13);
  
              if( $ani > 51 ){
                $sir += Num::val_red($ani / 51);
                $ani = Num::ran($ani, 51, 0);
              }
            }
          }
        }
        elseif( $tip == '-' ){
  
          $dia -= $cue;        
  
          if( $dia < 1 ){
            $lun -= Num::val_red($cue / 28);
            $dia = Num::ran($dia, 28);
            
            if( $lun < 1 ){    
              $ani -= Num::val_red($lun / 13);
              $lun = Num::ran($lun, 13);
  
              if( $ani < 0 ){    
                $sir -= Num::val_red($ani / 51);
                $ani = Num::ran($ani, 51, 0);
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
            $lun = Num::ran($lun, 13);

            if( $ani > 51 ){  
              $sir += Num::val_red($ani / 51);
              $ani = Num::ran($ani, 51, 0);                
            }
          }
        }
        elseif( $tip == '-' ){

          $lun -= $cue;
            
          if( $lun < 1 ){  
            $ani -= Num::val_red($lun / 13);
            $lun = Num::ran($lun, 13);

            if( $ani < 0 ){
              $sir -= Num::val_red($ani / 51);
              $ani = Num::ran($ani, 51, 0);
            }
          }
        }        
        break;
      case 'ani': 
        if( $tip == '+' ){

          $ani += $cue;

          if( $ani > 51 ){
            $sir += Num::val_red($ani / 51);
            $ani = Num::ran($ani, 51, 0);
          }
        }
        elseif( $tip == '-' ){

          $ani -= $cue;

          if( $ani < 0 ){
            $sir -= Num::val_red($ani / 51);
            $ani = Num::ran($ani, 51, 0);
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
  }// cargo Valores de un proceso por ciclos de Fecha para Tableros

  /* Proceso de Datos */

  // formularios
  static function dat( string $tip, mixed $dat = NULL, array $var = [] ) : string {
    
    $_ = "";
    $_eje =  !empty($var['eje']) ? $var['eje'] : "Sincronario.dat('{$tip}',this);";

    switch( $tip ){
    case 'val':
      $Kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : Dat::_('hol.kin',$dat['kin']) ) : [];
      $Psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : Dat::_('hol.psi',$dat['psi']) ) : [];
      $Sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $Fec = isset($dat['fec']) ? $dat['fec'] : [];      
  
      if( empty($var['opc']) || in_array('fec',$var['opc']) ){
        $_ .= "
        <!-- Fecha del Calendario -->
        <form class='-val mar-1' data-est='fec'>
    
          ".Doc_Val::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol-val-fec", 'class'=>"mar_hor-1", 
            'title'=>"Desde aquí puedes cambiar la fecha..." 
          ])."
          ".Doc_Var::fec('dia', $Fec, [ 'id'=>"hol-val-fec", 'name'=>"fec", 
            'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
          ])."

          ".Doc_Val::ico('ope_val-ini',[ 
            'eti'=>"button", 'type'=>"submit", 
            'class'=>"mar_hor-1", 
            'onclick'=>"{$_eje}", 
            'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
          ])."
    
        </form>";
      }

      if( empty($var['opc']) || in_array('sin',$var['opc']) ){
        $_ .= "
        <!-- Fecha del Sincronario -->
        <form class='-val mar-1' data-est='sin'>
          
          <label>N<c>.</c>S<c>.</c></label>
    
          ".Doc_Var::num('int', $Sin[0], [ 
            'maxlength'=>2, 
            'name'=>"gal", 
            'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
          ])."
          <c>.</c>
          ".Doc_Val::opc( Dat::_('hol.sir_ani'), [
            'eti'=>[ 
              'name'=>"ani", 
              'class'=>"num", 
              'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 
              'val'=>$Sin[1]-1
            ], 
            'ite'=>[ 'title'=>'($)nom','htm'=>'($)cod' ]
          ])."
          <c>.</c>
          ".Doc_Val::opc( Dat::_('hol.psi_lun'), [
            'eti'=>[ 
              'name'=>"lun", 
              'class'=>"num", 
              'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 
              'val'=>$Sin[2] 
            ],
            'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
          ])."
          <c>.</c>
          ".Doc_Val::opc( Dat::_('hol.lun'), [ 
            'eti'=>[ 
              'name'=>"dia", 
              'class'=>"num", 
              'title'=>"Día Lunar : los 28 días del Giro Lunar...", 
              'val'=>$Sin[3] 
            ], 
            'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
          ])."          
          <c class='sep'>:</c>
      
          <n name='kin'>$Kin->ide</n>
    
          ".Doc_Val::ico('ope_val-ini',[ 
            'eti'=>"button", 'type'=>"submit",
            'class'=>"mar_hor-1", 
            'onclick'=>"{$_eje}",
            'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
          ])."
    
        </form>";
      }
      
      break;
    }
    return $_;
  }
  // Descripciones
  static function dat_des( string $ide, mixed $val ) : string {

    $_ = "";
    
    $Ide = explode('-',$ide);

    $atr = isset($Ide[1]) ? $Ide[1] : '';
    
    switch( $est = $Ide[0] ){
    case 'kin':

      if( empty($atr) ){
        $Kin = !is_object($val) ? Dat::_('hol.kin',$val) : $val;

        $Sel = Dat::_('hol.sel',$Kin->arm_tra_dia);
    
        $Ton = Dat::_('hol.ton',$Kin->nav_ond_dia);
  
        $_ = Doc_Val::let($Kin->nom.": ")."<q>".Doc_Val::let("$Ton->des ".Tex::art_del($Sel->pod).", $Ton->acc_lec $Sel->car")."</q>";
      }
      break;
    }    

    return $_;    
  }
  // Informe
  static function dat_inf( string $ide, mixed $dat = NULL, array $var = [], ...$opc ) : string {
    $_ = "";
    
    $_ide = explode('-',$ide);

    $atr = isset($_ide[1]) ? $_ide[1] : '';

    switch( $est = $_ide[0] ){
    // ciclos del tiempo
    case 'ciclo': break;    
    // firma galáctica
    case 'firma': break;
    // tzolkin
    case 'kin':
      if( !empty($atr) ){

        $_libro = SYS_NAV."sincronario/libro/";
        $_tutorial = SYS_NAV."sincronario/tutorial/";

        $Kin = Dat::_("hol.{$est}",$dat);
        $Sel = Dat::_('hol.sel',$Kin->arm_tra_dia);
        $Ton = Dat::_('hol.ton',$Kin->nav_ond_dia);
        switch( $atr ){
        // parejas del oráculo
        case 'par':
          $_ = "
            
          ".Self::dat_tab("kin_par",[ 
            'ide'=>$Kin, 
            'pos'=>[ 'ima'=>"hol.kin.ide"  ] 
          ], [ 
            'sec'=>[ 'class'=>"mar_aba-1" ] 
          ])."
  
          <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='{$_libro}encantamiento_del_sueño#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>
  
          <div class='lis'>";
          foreach( Dat::_('hol.sel_par') as $_par ){
            // salteo el destino
            if( ( $ide = $_par->cod ) == 'des' ) continue;
            // busco datos de parejas
            $_par = Dat::get( Dat::_('hol.sel_par'), [ 'ver'=>[ ['cod','==',$ide] ], 'opc'=>'uni' ]);
            $kin = Dat::_('hol.kin',$Kin->{"par_{$ide}"});
            $_ .= "
            <p class='tex mar_arr-2 tex_ali-izq'>
              <b class='ide tex-sub'>{$_par->nom}</b><c>:</c>
              <br>".Doc_Val::let($_par->des)."
              ".( !empty($_par->lec) ? "<br>".Doc_Val::let($_par->lec) : "" )."
            </p>
            
            ".Doc_Dat::inf('hol.kin',$kin,[ 'det'=>"des" ]);
  
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

          foreach( Dat::_('hol.sel_par') as $_par ){
            
            $_kin_par = $_par->ide == 'des' ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$_par->ide}"});
    
            $ite = [ Doc_Val::ima('hol',"kin",$_kin_par) ];
    
            foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= Doc_Val::let($_par->$atr); }
    
            $_ton_par = Dat::_('hol.ton',$_kin_par->nav_ond_dia);
            foreach( $_ton_atr as $atr ){ if( isset($_ton_par->$atr) ) $ite []= Doc_Val::let($_ton_par->$atr); }
    
            $_sel_par = Dat::_('hol.sel',$_kin_par->arm_tra_dia);            
            foreach( $_sel_atr as $atr ){  if( isset($_sel_par->$atr) ) $ite []= Doc_Val::let($_sel_par->$atr); }
    
            $_ []= $ite;
          }
          $_ = $htm.Doc_Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $var);
          break;
        // - Lectura: por palabras clave
        case 'par_lec':
          $_ = [];
          $htm = "
          <p>En <a href='{$_tutorial}sincronario#_04-04-' target='_blank'>este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>
  
          <p>Puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>";
          
          foreach( Dat::_('hol.sel_par') as $_par ){
  
            if( $_par->ide == 'des' ) continue;
            $_kin_par = Dat::_('hol.kin',$Kin->{"par_{$_par->ide}"});
            $_sel_par = Dat::_('hol.sel',$_kin_par->arm_tra_dia);
            $_ []=
            Doc_Val::ima('hol',"kin",$_kin_par)."
  
            <div>
              <p><b class='tit'>{$_kin_par->nom}</b> <c>(</c> ".Doc_Val::let($_par->dia)." <c>)</c></p>
              <p>".Doc_Val::let("{$_sel_par->acc} {$_par->des_pod} {$_sel_par->des_car}, que {$_par->mis} {$Sel->des_car}, {$_par->acc} {$_sel_par->des_pod}.")."</p>
            </div>";
          }
  
          Ele::cla($var['lis'],'ite');
          $_ = $htm.Doc_Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $var);
          break;
        // - Ciclos : Posiciones en ciclos del kin
        case 'par_cic': 
          $_ = [];
          $htm = "
          <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        
  
          <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>";
  
          $atr_lis = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
    
          foreach( Dat::_('hol.sel_par') as $_par ){
            
            $_kin_par = $_par->ide == 'des' ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$_par->ide}"});
  
            $ite = [ Doc_Val::ima('hol',"kin",$_kin_par) ];
  
            foreach( $atr_lis as $atr ){
              $ite []= Doc_Val::ima('hol',"kin_{$atr}",$_kin_par->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
  
          $_ = $htm.Doc_Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $var);
          break;
        // - Grupos : Sincronometría del holon por sellos      
        case 'par_gru': 
          $_ = [];
          $htm = "
          <p>Puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='{$_libro}telektonon#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>
  
          <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>";
  
          $atr_lis = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];  
  
          foreach( Dat::_('hol.sel_par') as $_par ){
            
            $_kin_par = $_par->ide == 'des' ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$_par->ide}"});                            
    
            $_sel_par = Dat::_('hol.sel',$_kin_par->arm_tra_dia);
    
            $ite = [ Doc_Val::ima('hol',"kin",$_kin_par), $_par->nom, $_sel_par->des_pod ];
    
            foreach( $atr_lis as $atr ){
              $ite []= Doc_Val::ima('hol',"{$atr}",$_sel_par->$atr,[ 'class'=>"tam-5" ]);
            }            
            $_ []= $ite;
          }
  
          $_ = $htm.Doc_Dat::lis( $_, [ 'opc'=>[ 'htm', 'cab_ocu' ] ], $var);       
          break;
        }
      }
      break;
    }
    return $_;
  }
  // Fichas y cartas
  static function dat_fic( string $ide, array $var = [], array $ele = [] ){
    
    $_ = "";

    $_ide = explode('-',$ide);

    $opc = isset($var['opc']) ? $var['opc'] : [];
    
    switch( $est = $_ide[0] ){
    // 
    case 'telektonon':
      if( isset($_ide[1]) ){
        switch( $_ide[1] ){
        case 'cartas':
          
          $_dat = [
            4  => ['ide'=> 4, 'nom'=>"Libro de la Forma Cósmica" ],
            7  => ['ide'=> 7, 'nom'=>"Libro de las Siete Generaciones Perdidas" ],
            13 => ['ide'=>13, 'nom'=>"Libro del Tiempo Galáctico" ],
            28 => ['ide'=>28, 'nom'=>"Libro Telepático para la Redención de los Planetas Perdidos" ]
          ];
          
          $ide = isset($var['ide']) ? $var['ide'] : 4;
          
          $opc_ini = empty($opc) || in_array('ini',$opc);
          $opc_fin = empty($opc) || in_array('fin',$opc);
          
          if( !$opc_ini && !$opc_fin ) $opc_ini = $opc_fin = TRUE;
    
          $_ = [];
          foreach( ( isset($var['lis']) && is_array($var['lis']) ? $var['lis'] : range(1,$ide) ) as $pos ){ 
            $pos = Num::val($pos,2);
            $htm = "
            <div class='-ite jus-cen'>";
              if( $opc_ini ) $htm .= "
              <img src='".SYS_NAV."_img/sincronario/libro/telektonon/{$ide}/{$pos}-1.jpg' 
                alt='Carta {$pos}-1' class='mar_der-1' style='width: 20rem; height: 25rem;'>";
              if( $opc_fin ) $htm .= "
              <img src='".SYS_NAV."_img/sincronario/libro/telektonon/{$ide}/{$pos}-2.jpg' 
                alt='Carta {$pos}-2' class='mar_izq-1' style='width: 20rem; height: 25rem;'>";
              $htm .= "
            </div>";
            $_ []= $htm;
          }
          $_ = Doc_Ope::lis('bar', $_, $var);            
          break;
        }
      }  
      break;
    // 
    case 'atomo_del_tiempo':
      if( isset($_ide[1]) ){
        switch( $_ide[1] ){
        case 'cartas':
          
          $opc_ini = empty($opc) || in_array('ini',$opc);
          $opc_fin = empty($opc) || in_array('fin',$opc);
          
          if( !$opc_ini && !$opc_fin ) $opc_ini = $opc_fin = TRUE;
    
          $_ = [];
          foreach( ( isset($var['lis']) && is_array($var['lis']) ? $var['lis'] : range(1,28) ) as $pos ){ 
            $pos = Num::val($pos,2);
            $htm = "
            <div class='-ite jus-cen'>";
              if( $opc_ini ) $htm .= "
              <img src='".SYS_NAV."_img/sincronario/libro/atomo_del_tiempo/fic/{$pos}-1.gif' 
                alt='Carta {$pos}-1' class='mar_der-1' style='width: 15rem; height: 20rem;'>";
              if( $opc_fin ) $htm .= "
              <img src='".SYS_NAV."_img/sincronario/libro/atomo_del_tiempo/fic/{$pos}-2.gif' 
                alt='Carta {$pos}-2' class='mar_izq-1' style='width: 15rem; height: 20rem;'>";
              $htm .= "
            </div>";
            $_ []= $htm;
          }
          $_ = Doc_Ope::lis('bar', $_, $var);            
          break;
        }
      } 
      break;
    }
    
    return $_;
  }
  // - sumatorias por valores
  static function dat_val_sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
      
    $_ = "";
    
    extract( Dat::ide($dat) );
    
    $_est = "dat_val_sum-$esq-$est";
    
    // estructuras por esquema
    foreach( Dat::var($esq,'dat_val','sum') as $ide => $ite ){

      $_ .= Doc_Ope::var($esq,"dat_val.sum.$ide",[
        'ope'=>[ 
          'id'=>"{$_est}-{$ide}" 
        ],
        // busco fichas del operador
        'htm_fin'=> !empty($ite['dat_ima']) ? Doc_Dat::ima($ite['dat_ima'], $val, $ope) : ''
      ]);
    }

    return $_;

  }
  // Tableros
  static function dat_tab( string $est, array $var = [], array $ele = [] ) : string {

    $_ = "";

    extract( Doc_Dat::tab_var("hol",$est,$var,$ele) );

    $_est = explode('_',$est);

    switch( $_est[0] ){
    // holon interplanetario
    case 'sol':
      $sec = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      // Sistema Solar ( vertical : T.K. )
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          
          // imágenes: galaxia + sol
          foreach( ['gal'=>[ 'Galaxia' ],'sol'=>[ 'Sol' ] ] as $i=>$v ){ $_ .= "
            <li class='sec ima $i'>
              ".Doc_Val::ima("hol/tab/$i")."
            </li>";
          }

          // 2 respiraciones : x10 flechas
          foreach( Dat::_('hol.sol_res') as $v ){ 
            for( $i = 1; $i <= 10; $i++ ){ $_ .= "
              <li class='sec ima res-{$v->ide} ide-$i'>".
                Doc_Val::ima('hol',"flu_res",$v)."
              </li>";
            }
          }
          // x 4 flujos : alfa <-> omega
          foreach( Dat::_('hol.flu') as $v ){ $_ .= "
            <li class='sec ima flu-{$v->ide} pod-{$v->pod}'>".
              Doc_Val::ima('hol',"flu_pod",$v->pod)."
            </li>";
          }

          // 10 planetas
          foreach( Dat::_('hol.sol_pla') as $v ){ 
            $cla = ( !isset($sec['pla']) || empty($sec['pla']) ) ? " dis-ocu" : "";
            $_ .= "
            <li class='sec bor pla-{$v->ide}{$cla}'></li>
            <li class='sec ima pla-{$v->ide}'>".Doc_Val::ima('hol',"sol_pla",$v)."</li>";
          }
          // Secciones por Seleccion
          
          // - 2 grupos orbitales
          foreach( Dat::_('hol.sol_orb') as $v ){ 
            $cla = ( !isset($sec['orb']) || empty($sec['orb']) ) ? " dis-ocu" : "";
            $_ .= "
            <li class='sec bor orb-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_orb",$v)."'></li>";
          }
          // - 4 elementos/clanes
          foreach( Dat::_('hol.sel_cro_ele') as $v ){ 
            $cla = ( !isset($sec['ele']) || empty($sec['ele']) ) ? " dis-ocu" : "";
            $_ .= "
            <li class='sec bor ele-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }
          // - 5 células solares
          foreach( Dat::_('hol.sol_cel') as $v ){ 
            $cla = ( !isset($sec['cel']) || empty($sec['cel']) ) ? " dis-ocu" : "";
            $_ .= "
            <li class='sec bor cel-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_cel",$v)."'></li>";
          }
          // - 5 circuitos de telepatía
          foreach( Dat::_('hol.sol_cir') as $v ){ 
            $cla = ( !isset($sec['cir']) || empty($sec['cir']) ) ? " dis-ocu" : "";
            $_ .= "
            <li class='sec bor cir-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_cir",$v)."'></li>";
          }

          // posicion: 20 sellos solares
          $_ .= self::dat_tab_sec('hol_sel',$var,$ele,$val);

          $_ .= " 
        </ul>";   
      }
      else{
        switch( $_est[1] ){
        // Sistema Solar ( circular : E.S. )
        case 'cel':
          $_ = "
          <ul".Ele::atr($ele['sec']).">";
            
            // fondos: 
            foreach( ['map','ato'] as $i ){ $_ .= "
              <li class='sec fon {$i}'></li>";
            }

            // opciones: respiracion, clanes, celulas, circuitos
            foreach( ['res','cel','cir'] as $i ){ 
              $_ .= "
              <li class='sec fon {$i}'></li>";
            }

            // fichas: planetas
            foreach( Dat::_('hol.sol_pla') as $v ){ $_ .= "
              <li class='sec pla-{$v->ide}'>
                ".Doc_Val::ima('hol','sol_pla',$v)."
              </li>";
            }
            
            // posicion: 20 sellos solares
            $_ .= self::dat_tab_sec('hol_sel',$var,$ele,$val);

            $_ .= " 
          </ul>";            
          break;
        }        
      }      
      break;
    // holon planetario
    case 'pla':
      $sec = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon map'></li>
          <li class='sec fon sel'></li>";
          
          // fondos: flujos, 
          foreach( ['res','ele'] as $i ){ 
            $cla = ( $sec[$i] !== FALSE ) ? "" : " dis-ocu"; $_ .= "
            <li class='sec fon {$i}{$cla}'></li>";
          }
          
          // 3 Hemisferios
          foreach( Dat::_('hol.pla_hem') as $v ){
            $cla = ( $sec['hem'] !== FALSE && ( empty($sec['hem']) || in_array($v->ide,$sec['hem']) ) ) ? "" : " dis-ocu";  
            $_ .= "
            <li class='sec bor hem-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_hem",$v)."'></li>";
          }          
          
          // 2 Meridianos
          foreach( Dat::_('hol.pla_mer') as $v ){
            $cla = ( $sec['mer'] !== FALSE && ( empty($sec['mer']) || in_array($v->ide,$sec['mer']) ) ) ? "" : " dis-ocu";  
            $_ .= "
            <li class='sec bor mer-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_mer",$v)."'></li>";
            if( $v->ide == 1 ){ $_ .= "
              <li class='sec bor mer-{$v->ide}-0{$cla}' title='".Doc_Dat::val('tit',"hol.sol_mer",$v)."'></li>";
            }
          }
          
          // 5 Centros galácticos
          foreach( Dat::_('hol.pla_cen') as $v ){
            if( $sec['cen'] !== FALSE ){ $cla = in_array($v->ide,$sec['cen']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='sec ima cen-{$v->ide}{$cla}'>
              ".Doc_Val::ima('hol',"sel_cro_fam",$v->fam)."
            </li>";
          }
          
          // Posicion: 20 sellos solares
          $_ .= self::dat_tab_sec('hol_sel',$var,$ele,$val);

          $_ .= "
        </ul>"; 
      }
      else{
        switch( $_est[1] ){
        }
      }      
      break;
    // holon humano
    case 'hum':
      $sec = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon map'></li>";
          
          // 2 Lados del Cuerpo : Respiración del Holon
          foreach( Dat::_('hol.hum_res') as $v ){
            $cla = ( $sec['res'] !== FALSE && ( empty($sec['res']) || in_array($v->ide,$sec['res']) ) ) ? "" : " dis-ocu"; $_ .= "
            <li class='sec bor res-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.hum_res",$v)."'></li>";
          }          
          
          // 5 Centros Galácticos : Familias Terrestres
          if( $sec['cen'] !== FALSE ){ $_ .= "
            <li class='sec fon cen'></li>
            <li class='sec fon ded'></li>";
          }
          foreach( Dat::_('hol.hum_cen') as $v ){
            if( $sec['cen'] !== FALSE ){ $cla = in_array($v->ide,$sec['cen']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='sec ima cen-{$v->ide}{$cla}'>
              ".Doc_Val::ima('hol',"hum_cen",$v)."
            </li>";
          }
          
          // 4 Extremidades : Clanes Cromáticos
          foreach( Dat::_('hol.hum_ext') as $v ){
            if( $sec['ext'] !== FALSE ){ $cla = in_array($v->ide,$sec['ext']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='sec bor ext-{$v->ide}{$cla}'></li>";
          }          
          
          // Posicion: 20 sellos solares
          $_ .= self::dat_tab_sec('hol_sel',$var,$ele,$val);

          // 13 Articulaciones : Tonos Galácticos
          $_ .= self::dat_tab_sec('hol_ton',$var,$ele,$val);
          
          // 7 Chakras : Plasmas Radiales
          $_ .= self::dat_tab_sec('hol_rad',$var,$ele,$val);          
          
          // 7 Chakras : Plasmas Radiales
          foreach( Dat::_('hol.rad') as $v ){ 
            if( $sec['cha'] !== FALSE ){ $cla = in_array($v->ide,$sec['cha']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='pos ide-{$v->ide} rad{$cla}'>
              ".Doc_Val::ima('hol',"rad",$v)."
            </li>";
          }
          
          // 13 Articulaciones : Tonos Galácticos
          foreach( Dat::_('hol.ton') as $v ){ 
            if( $sec['art'] !== FALSE ){ $cla = in_array($v->ide,$sec['art']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='pos ide-{$v->ide} ton{$cla}'>
              ".Doc_Val::ima('hol',"ton",$v)."
            </li>";
          }

          $_ .= "
        </ul>"; 
      }
      else{
        switch( $_est[1] ){
        }        
      }
      break;
    // telektonon/telepatia
    case 'tel':
      $sec = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          // posicion: 20 sellos del holon solar
          foreach( Dat::_('hol.sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel sol'>
              ".Doc_Val::ima('hol',"sel_cod",$v)."
            </li>";
          }
          // posicion: 20 sellos del circuito de recarga
          foreach( Dat::_('hol.sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel cod'>
              ".Doc_Val::ima('hol',"sel_cod",$v)."
            </li>";
          }
          // posicion: 28 plasmas
          foreach( Dat::_('hol.lun') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} lun'>
              ".Doc_Val::ima('hol',"rad",$v->rad)."
            </li>";
          }          
          $_ .= " 
        </ul>";  
      }
      else{
        switch( $_est[1] ){
        }        
      }
      break;
    // plasma radial
    case 'rad':
      // heptagono
      if( !isset($_est[1]) ){
        Ele::cla($ele['sec'],"hol-hep"); 
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Dat::_('hol.rad') as $_rad ){ 
            $ele['pos'] = $ele_pos;
            // cargo datos de la posicion
            $ele['pos']['hol-rad'] = $_rad->ide;
            $_ .= Doc_Dat::tab_pos('hol',$est,$_rad,$var,$ele);
          }$_ .= "
        </ul>";           
      }
      else{
        switch( $_est[1] ){
        }
      }      
      break;
    // tono galáctico
    case 'ton':
      // onda encantada
      if( !isset($_est[1]) ){

        Ele::cla($ele['sec'],"hol-ton");
        $_ .= "
        <ul".Ele::atr($ele['sec']).">
          ".Self::dat_tab_sec('ton',$var,$ele)
          ;
          foreach( Dat::_('hol.ton') as $Ton ){
            // cargo datos de la posicion
            $ele['pos']['hol-ton'] = $Ton->ide;
            $_ .= Doc_Dat::tab_pos('hol','ton',$Ton,$var,$ele);
          } $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        }
      }
      break;
    // sello solar
    case 'sel':
      // codificacion
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          foreach( Dat::_('hol.sel') as $Sel ){ 
            $agr = ( !!$val && $Sel->ide == $val ) ? ' _pos-' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='-val jus-cen'>
                ".Doc_Val::ima('hol',"sel",$Sel,['eti'=>"li"])."
                ".Doc_Val::ima('hol',"sel_cod",$Sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='mar-0 ali_pro-cen'>
                {$Sel->arm}
                <br>{$Sel->acc}
                <br>{$Sel->des_pod}
              </p>
            </li>";
          } $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        // parejas del oráculo
        case 'par':
          if( empty($ide) ) $ide = 1;
          $Sel = is_object($ide) ? $ide : Dat::_('hol.sel',$ide);           
          Ele::cla($ele['sec'],"hol-cro");
          $_ = "
          <ul".Ele::atr($ele['sec']).">";
            $ele_pos = $ele['pos'];
            foreach( Dat::_('hol.sel_par') as $_par ){
              $par_ide = $_par->cod;
              // combino elementos
              $ele['pos'] = isset($ele["par-{$par_ide}"]) ? Ele::val_jun($ele_pos,$ele["par-{$par_ide}"]) : $ele_pos;
              Ele::cla($ele['pos'],"par-{$par_ide}");            
              // todos menos el guia
              $par_sel = 0;
              if( $par_ide != 'gui' ){ $par_sel = ( $par_ide == 'des' ) ? $Sel : Dat::_('hol.sel',$Sel->{"par_{$par_ide}"}); }
              $_ .= Doc_Dat::tab_pos('hol','sel',$par_sel,$var,$ele);
            }$_ .= "
          </ul>";
          break;
        // colocacion cromática
        case 'cro':
          if( !isset($_est[2]) ){        
            $_ = "
            <ul".Ele::atr($ele['sec']).">
              <li class='pos ide-0'></li>";
              foreach( Dat::_('hol.sel_cro_fam') as $v ){ 
                $_ .= Doc_Val::ima('hol',"sel_cro_fam",$v,[ 'eti'=>"li", 'class'=>"sec fam-{$v->ide}" ]);
              } 
              foreach( Dat::_('hol.sel_cro_ele') as $v ){ 
                $_ .= Doc_Val::ima('hol',"sel_cro_ele",$v,[ 'eti'=>"li", 'class'=>"sec ele-{$v->ide}"]);
              }
              $ele_pos = $ele['pos'];
              for( $i=0; $i<=19; $i++ ){ 
                $v = Dat::_('hol.sel', ( $i == 0 ) ? 20 : $i);
                $agr = ( !empty($val) && $v->ide == $val ) ? ' _pos-' : '' ;
                $ele['pos'] = $ele_pos;
                Ele::cla($ele['pos'],"{$agr}");
                $_ .= Doc_Dat::tab_pos('hol','sel',$v,$var,$ele);
              } $_ .= "
            </ul>";
          }
          else{
            switch( $_est[2] ){
            }
          }
          break;
        // colocacion armónica
        case 'arm':
          if( !isset($_est[2]) ){
            $_ .= "
            <ul".Ele::atr($ele['sec']).">
              <li class='pos ide-0'></li>
              ";
              foreach( Dat::_('hol.sel_arm_cel') as $v ){ 
                $_ .= Doc_Val::ima('hol',"sel_arm_cel",$v,[ 'eti'=>"li", 'class'=>"sec cel-{$v->ide}"]);
              } 
              foreach( Dat::_('hol.sel_arm_raz') as $v ){ 
                $_ .= Doc_Val::ima('hol',"sel_arm_raz",$v,[ 'eti'=>"li", 'class'=>"sec raz-{$v->ide}"]);
              }
              $ele_pos = $ele['pos'];
              foreach( Dat::_('hol.sel') as $v ){
                $agr = ( !empty($val) && $v->ide == $val ) ? ' _pos-' : '' ;
                $ele['pos'] = $ele_pos;
                Ele::cla($ele['pos'],"{$agr}");
                $_ .= Doc_Dat::tab_pos('hol','sel',$v,$var,$ele);
              }
              $_ .= "
            </ul>";              
          }
          else{
            switch( $_est[2] ){
            // tablero del oráculo
            case 'tra':
              Ele::cla($ele['sec'],"hol-cro");
              $_ .= "
              <ul".Ele::atr($ele['sec']).">";
                for( $i=1; $i<=5; $i++ ){
                  $var['ide'] = $i;            
                  $_ .= "
                  <li class='pos ide-{$i}'>
                    ".Self::dat_tab('sel_arm_cel',$var,$ele)."
                  </li>";
                } $_ .= "
              </ul>";        
              break;      
            // célula del tiempo para el oráculo
            case 'cel':
              $Arm = Dat::_("hol.{$est}",$ide);        
              
              $ele['cel']['title'] = Doc_Dat::val('tit',"hol.{$est}",$Arm); 
              Ele::cla($ele['cel'],"dat_tab hol {$est} hol-arm",'ini');
              
              $_ = "
              <ul".Ele::atr($ele['cel']).">
                ".Doc_Val::ima('hol',"sel_arm_cel", $Arm, ['eti'=>"li", 'class'=>"pos ide-0", 'htm'=>$Arm->ide ] );
                foreach( explode(', ',$Arm->sel) as $sel ){
                  $_ .= Doc_Dat::tab_pos('hol','sel',$sel,$var,$ele);
                } $_ .= "
              </ul>";        
              break;              
            }
          }              
          break;
        }
      }
      break;
    // giro lunar
    case 'lun':
      // diario: 28 plasmas en 4 heptadas
      if( !isset($_est[1]) ){
        Ele::cla($ele['sec'],"hol-lun"); 
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Dat::_('hol.lun') as $_lun ){ 
            $ele['pos'] = $ele_pos;
            // cargo datos de la posicion
            $ele['pos']['hol-lun'] = $_lun->ide;
            $ele['pos']['hol-rad'] = $_lun->rad;
            Ele::cla($ele['pos'],"fon_col-4-".$_lun->arm." bor-1 bor-luz");
            $ele['pos']['htm'] = "
              ".Doc_Val::ima('hol','rad',$_lun->rad,['class'=>"mar-1"])."
              ".Doc_Val::num($_lun->ide,['class'=>"tex-3"])."
            ";
            $_ .= Doc_Dat::tab_pos('hol',$est,$_lun,$var,$ele);
          }$_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        }
      }      
      break;
    // castillo fractal
    case 'cas':
      // posiciones del castillo por cuadrantes armonicos
      if( !isset($_est[1]) ){
        Ele::cla($ele['sec'],"hol-cas");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon-ima'></li>          
          ".Self::dat_tab_sec('cas',$var,$ele)."
          <li class='pos ide-0'></li>";

          foreach( Dat::_('hol.cas') as $Cas ){
            // cargo datos de la posicion
            $ele['pos']['hol-cas'] = $Cas->ide;
            $ele['pos']['hol-ton'] = $Cas->ton;
            $_ .= Doc_Dat::tab_pos('hol','cas',$Cas,$var,$ele);
          } $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        }        
      }
      break;
    // giro galáctico
    case 'kin': 
      // tzolkin
      if( !isset($_est[1]) ){
        $ton_val      = !empty($var['est-kin']['ton']) ? "" : " dis-ocu";
        $sel_val      = !empty($var['est-kin']['sel']) ? "" : " dis-ocu";
        $arm_cel_val  = !empty($var['est-kin']['arm_cel']) ? "" : " dis-ocu";

        if( empty($ton_val) ){

          Ele::css($ele['sec'],"gri-template-rows: repeat(21,1fr)");
        }

        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          // 1° columna
          $_ .= "
          <li".Ele::atr([ 'class' => "sec ini".( empty($ton_val) && empty($sel_val) ? "" : " dis-ocu" )])."></li>";
          // filas por sellos
          foreach( Dat::_('hol.sel') as $Sel ){ 
            $_ .= "
            <li class='sec sel ide-{$Sel->ide}{$sel_val}'>".Doc_Val::ima('hol',"sel",$Sel)."</li>";
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( Dat::_('hol.kin') as $Kin ){
            // columnas por tono          
            $kin_arm_tra = intval($Kin->arm_tra);
            if( $kin_arm != $kin_arm_tra ){ $_ .= 
              "<li class='sec ton ide-{$Kin->arm_tra}{$ton_val}'>".Doc_Val::ima('hol',"kin_arm_tra",$Kin->arm_tra)."</li>";
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            $_ .= Doc_Dat::tab_pos('hol','kin',$Kin,$var,$ele);
            $ele['pos'] = $ele_pos;
          }
          // columna
          $_ .= "
          <li".Ele::atr([ 'class' => "sec fin".( empty($ton_val) && empty($arm_cel_val) ? "" : " dis-ocu" )])."></li>";          
          // celulas armonicas
          foreach( Dat::_('hol.sel_arm_cel') as $Cel ){
            $_ .= "
            <li class='sec arm_cel ide-{$Cel->ide}{$arm_cel_val}'>".Doc_Val::ima('hol',"sel_arm_cel",$Cel)."</li>";
          }
          $_ .= "
        </ul>";   
      }
      else{
        switch( $_est[1] ){
        // oráculo del destino por tipo de pareja
        case 'par':
          if( empty($ide) ) $ide = 1;
          $Kin = is_object($ide) ? $ide : Dat::_('hol.kin',$ide);           
          Ele::cla($ele['sec'],"hol-cro");
          $_ = "
          <ul".Ele::atr($ele['sec']).">";
            $ele_pos = $ele['pos'];
            foreach( Dat::_('hol.sel_par') as $_par ){
              
              $par_ide = $_par->cod;
              $par_kin = ( $par_ide == 'des' ) ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$par_ide}"});
              
              // combino elementos :
              $ele['pos'] = isset($ele[$par_ide]) ? Ele::val_jun($ele_pos,$ele[$par_ide]) : $ele_pos;
  
              // agrego identificador de pareja
              $ele['pos']['hol-par'] = $par_ide;
  
              $_ .= Doc_Dat::tab_pos('hol','kin',$par_kin,$var,$ele);
  
            }$_ .= "
          </ul>";
          break;
        // castillo del destino por familia terrestre
        case 'cas':
          $_fam = Dat::_('hol.sel_cro_fam',$ide);
          $_ = "
          <ul".Ele::atr($ele['sec']).">
            ".Self::dat_tab_sec('cas',$var,$ele)."
            <li class='pos ide-0'>
              ".Doc_Val::ima('hol','sel_cro_fam',$_fam)."
            </li>";
  
            $kin = intval($_fam['kin']);
            foreach( Dat::_('hol.cas') as $Cas ){
              $Kin = Dat::_('hol.kin',$kin);
              $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
              $kin = Num::ran($kin + 105, 260);
            } $_ .= "
          </ul>";          
          break;
        // nave del tiempo : 5 castillos + 20 ondas
        case 'nav':
          if( !isset($_est[2]) ){
            Ele::cla($ele['sec'],"hol-cro");
            $_ = "
            <ul".Ele::atr($ele['sec']).">";
              foreach( Dat::_('hol.kin_nav_cas') as $cas => $Cas ){
                $var['ide'] = $Cas->ide; $_ .= "
                <li class='pos ide-".intval($Cas->ide)."'>
                  ".Self::dat_tab('kin_nav_cas',$var,$ele)."
                </li>";
              } $_ .= "
            </ul>";            
          }
          else{
            switch( $_est[2] ){
            // 1 castillo de 4 ondas y 52 kines
            case 'cas':

              if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->nav_cas;

              $Cas = Dat::_("hol.{$est}",$ide);
              
              // clases del castillo
              if( !isset($ele['cas']) ) $ele['cas'] = [];
              Ele::cla($ele['cas'],"dat_tab hol {$est} hol-cas fon_col-5-{$ide}".( empty($var['est-cas']['col']) ? ' fon-0' : '' ),'ini');
              
              // titulos
              $ele['cas']['title'] = Doc_Dat::val('tit',"hol.{$est}",$Cas);        
              $ini = ( ( $ide - 1 ) * 4 ) + 1;
              $ond_fin = $ini + 4;        
              for( $ond = $ini; $ond < $ond_fin; $ond++ ){ 
                $Ond = Dat::_('hol.kin_nav_ond',$ond);
                $ele['cas']['title'] .= "\n".$Ond->enc_des;
              }

              $_ = "
              <ul".Ele::atr($ele['cas']).">
                ".Self::dat_tab_sec('cas',$var,$ele)."
                <li class='pos ide-0'>
                  ".Doc_Val::ima('hol','kin_nav_cas',$ide,[ 'title'=>$ele['cas']['title'] ])."
                </li>";
                $kin = ( ( $ide - 1 ) * 52 ) + 1;
                foreach( Dat::_('hol.cas') as $Cas ){
                  $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
                  $kin++;
                } $_ .= "
              </ul>";

              break;
            // 1 onda encantada de 13 kines
            case 'ond':

              if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->nav_ond;
              $Ond = Dat::_("hol.{$est}",$ide);
              
              // clases
              if( !isset($ele['ond']) ) $ele['ond'] = [];
              Ele::cla($ele['ond'],"dat_tab hol {$est} hol-ton",'ini');
              
              // titulo        
              $ele['ond']['title'] = Doc_Dat::val('tit',"hol.kin_nav_cas",$Ond->nav_cas)." .\n{$Ond->enc_des}"; 
              
              $_ = "
              <ul".Ele::atr($ele['ond']).">
                ".Self::dat_tab_sec('ton',$var,$ele);
      
                $kin = ( ( $ide - 1 ) * 13 ) + 1;
                foreach( Dat::_('hol.ton') as $Ton ){
                  $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
                  $kin++;
                } 
                $_ .= "
              </ul>";
              
              break;                            
            }
          }
          break;
        // armónicas : 13 trayectorias + 65 células
        case 'arm':
          // giro galáctico
          if( !isset($_est[2]) ){

            Ele::cla($ele['sec'],"hol-ton");

            $_ = "
            <ul".Ele::atr($ele['sec']).">
              ".Self::dat_tab_sec('ton',$var,$ele);
    
              foreach( Dat::_('hol.kin_arm_tra') as $Tra ){ 
                $var['ide'] = $Tra->ide; $_ .= "
                <li class='pos ide-".intval($Tra->ide)."'>
                  ".Self::dat_tab('kin_arm_tra',$var,$ele)."
                </li>";
              } $_ .= "
            </ul>";              
          }
          else{
            switch( $_est[2] ){
            // 1 trayectoria de 5 armónicas y 20 kines
            case 'tra':

              if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->arm_tra;
              $Tra = Dat::_('hol.kin',$ide);

              $cel_ini = ( ( intval($Tra->ide) - 1 ) * 5 ) + 1;
              $cel_fin = $cel_ini + 5;
      
              if( !isset($ele['tra']) ) $ele['tra']=[];
              Ele::cla($ele['tra'],"dat_tab hol {$est} hol-cro",'ini');
              
              $_ = "
              <ul".Ele::atr($ele['tra']).">";
                for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
                  $var['ide'] = $cel; $_ .= "
                  <li class='pos ide-".Num::ran($cel,5)."'>
                    ".Self::dat_tab('kin_arm_cel',$var,$ele)."
                  </li>";            
                } $_ .= "
              </ul>";

              break;
            // 1 célula del tiempo de 4 kines
            case 'cel': 

              if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->arm_cel;
              $Arm = Dat::_("hol.{$est}",$ide);
      
              if( !isset($ele['cel']) ) $ele['cel']=[];
              Ele::cla($ele['cel'],"dat_tab hol {$est} hol-arm fon_col-5-$Arm->cel fon-0");

              $_ = "
              <ul".Ele::atr($ele['cel']).">
                <li class='pos ide-0 col-bla'>
                  ".Doc_Val::ima('hol',"sel_arm_cel",$Arm->cel,[ 'htm'=>$Arm->ide, 'title'=>Doc_Dat::val('tit',"hol.{$est}",$Arm) ])."
                </li>";
      
                $kin = ( ( $ide - 1 ) * 4 ) + 1;
                for( $arm = 1; $arm <= 4; $arm++ ){
                  $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
                  $kin++;
                } $_ .= "
              </ul>";

              break;
            }
          }
          break;
        // cromáticas : 4 estaciones + 52 elementos
        case 'cro':
          // castillo del giro espectral
          if( !isset($_est[2]) ){
            if( !in_array('fic_cas',$var['opc']) ) $var['opc'] []= 'fic_cas';
            Ele::cla($ele['sec'],"hol-cas");
            $_ = "
            <ul".Ele::atr($ele['sec']).">
              ".Self::dat_tab_sec('cas',$var,$ele)."
    
              <li class='pos ide-0'>
                ".Doc_Val::ima('hol/tab/gal')."
              </li>";
              foreach( Dat::_('hol.kin_cro_ele') as $_ele ){
                $var['ide'] = $_ele->ide; $_ .= "
                <li class='pos ide-".intval($_ele->ide)."'>
                  ".Self::dat_tab('kin_cro_ele',$var,$ele)."
                </li>";
              } $_ .= "
            </ul>";            
          }
          else{
            switch( $_est[2] ){
            // 1 estacion galáctica de 13 cromáticas y 65 kines
            case 'est':
              if( !in_array('fic_cas',$var['opc']) ) $var['opc'] []= 'fic_ond';

              if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->cro_est;
              $Est = Dat::_('hol.kin_cro_est',$ide); 
              $cas = explode(' - ',$Est->cas)[0];
              
              if( !isset($ele['est']) ) $ele['est']=[];
              Ele::cla($ele['est'],"dat_tab hol {$est} hol-ton",'ini');
              
              $_ = "
              <ul".Ele::atr($ele['est']).">
                ".Self::dat_tab_sec('ton',$var,$ele);
                
                foreach( Dat::_('hol.ton') as $Ton ){
                  $var['ide'] = $cas; $_ .= "
                  <li class='pos ide-".intval($Ton->ide)."'>
                    ".Self::dat_tab('kin_cro_ele',$var,$ele)."
                  </li>";
                  $cas = Num::ran($cas + 1, 52);
                } $_ .= "
              </ul>";
              break;

            // 1 elemento galáctico de 5 kines
            case 'ele':
              
              if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->cro_ele;
              
              $_ele = Dat::_("hol.{$est}",$ide);
              
              $ele_rot = [
                "ton" => [ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
                "cas" => [ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
              ];
              
              if( !isset($ele['ele']) ) $ele['ele']=[];
              $ele['ele']['title'] = "{$_ele->ide}: {$_ele->nom}";
              
              // del castillo | onda : rotaciones
              if( in_array('fic_cas',$var['opc']) || in_array('fic_ond',$var['opc']) ){ Ele::css($ele['ele'],
                "transform: rotate(".(in_array('fic_cas',$var['opc']) ? $ele_rot['cas'][$ide-1] : $ele_rot['ton'][$ide-1])."deg)");
              }
              Ele::cla($ele['ele'],"dat_tab hol {$est} hol-cro-cir",'ini');
      
              $_ .= "
              <ul".Ele::atr($ele['ele']).">
                <li class='pos ide-0'>
                  ".Doc_Val::ima('hol','kin_cro_ele',$_ele->ide)."
                </li>";
                // cuenta desde : kin = 185
                $kin = Num::ran( 185 + ( ( $ide - 1 ) * 5 ), 260);
                
                foreach( Dat::_('hol.sel_cro_fam') as $cro_fam ){
      
                  $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
                  $kin++;// por verdad eléctrica
                  if( $kin > 260 ) $kin = 1;
                }$_ .= "
              </ul>";
      
              break;
            }
          }          
          break;
        }        
      }      
      break;
    // giro solar
    case 'psi':
      if( !isset($_est[1]) ){
      }
      else{
        switch( $_est[1] ){
        // Banco-psi
        case 'ban': 
          $_ = "
          <ul".Ele::atr($ele['sec']).">";
            $ele['sec'] = isset($ele['tzo']) ? $ele['tzo'] : [];
            for( $i=1 ; $i<=8 ; $i++ ){ $_ .= "
              <li class='pos ide-$i'>
                ".Self::dat_tab('kin',$var,$ele)."
              </li>";
            } $_ .= "
          </ul>";        
          break;
        // Ciclo de Sirio
        case 'ani':
          $kin = 34;
          $var['est-cas']['cir'] = '1';
          Ele::cla($ele['sec'],'hol-cas-cir');
          $_ = "
          <ul".Ele::atr($ele['sec']).">
            ".Self::dat_tab_sec('cas_cir',$var,$ele)."
            <li class='pos ide-0'>
            </li>";
  
            foreach( Dat::_('hol.cas') as $Cas ){
              $Kin = Dat::_('hol.kin',$kin); $_ .= "
              <li class='pos ide-".intval($Cas->ide)."'>
                ".Doc_Val::ima('hol',"kin",$Kin)."
              </li>";
              $kin += 105; if( $kin >260 ) $kin -= 260;
            } $_ .= "
          </ul>";
                  
          break;
        // Anillo Solar de 13 Lunas
        case 'lun':
          // onda encantada
          if( !isset($_est[2]) ){        
            Ele::cla($ele['sec'],'hol-ton');
            $_ = "
            <ul".Ele::atr($ele['sec']).">
              ".Doc_Val::ima('hol/tab/sol',['eti'=>"li", 'class'=>"sec sol"])."
              ".Doc_Val::ima('hol/tab/pla',['eti'=>"li", 'class'=>"sec lun"])."
              ".Self::dat_tab_sec('ton',$var,$ele)
              ;
    
              if( !in_array("cab-nom",$var['opc']) ) $var['opc'] []= 'cab-nom';
    
              for( $lun = 1; $lun <= 13; $lun++ ){
                
                $var['ide'] = $lun; 
                
                $_ .= "
                <li class='pos ide-{$lun}'>
                  ".Self::dat_tab('psi_lun_dia',$var,$ele)."
                </li>";
              } $_ .= "
            </ul>";            
          }
          else{
            switch( $_est[2] ){
            // - Giro Lunar: 28 días
            case 'dia':
      
              foreach( ['lun','cab','cab-ton','cab-rad','hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
              
              $ele['pos']['eti'] = 'td';
              
              $ver_ton = !empty($var['est-lun']['ton']);
              $ver_hep = !empty($var['est-lun']['hep']);
              $ver_rad = !empty($var['est-lun']['rad']);
              
              if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = Dat::_('hol.psi',$val['psi'])->lun;
      
              $_lun = Dat::_('hol.psi_lun',$ide);
      
              Ele::cla($ele['lun'],"dat_tab hol {$est} hol-lun",'ini');         
              
              $_ = "
              <table".Ele::atr($ele['lun']).">";
                
                if( !$ver_ton && !$ver_rad ) Ele::cla($ele['cab'],"dis-ocu"); 
                $_ .= "
                <thead".Ele::atr($ele['cab']).">";
                  
                  // Tono y Totem Luna: por nombre o descripcion
                  $cab_nom = in_array('cab-nom',$var['opc']);
                  $ele['cab-ton']['data-cab'] = "ton";
                  if( !$ver_ton ) Ele::cla($ele['cab-ton'],"dis-ocu");
                  $_ .= "
                  <tr".Ele::atr($ele['cab-ton']).">
      
                    <th colspan='".( $ver_hep ? "8" : "7" )."'>
      
                      <div class='-val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>
      
                      ".Doc_Val::ima('hol','psi_lun',$_lun,['class'=>( $cab_nom ? "tam-2 mar_der-1" : "tam-15 mar-1" )])."
      
                      ".( $cab_nom ? "
                        <p class='tex ide'>".str_replace(',','',explode(' ',$_lun->tot)[1])."</p>
                      " : "
                        <div>
                          <p class='tex tit tex-4'>
                            <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                          </p>
                          <p class='tex tex-3 mar-1'>
                            ".Doc_Val::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                            <br>".Doc_Val::let($_lun->ond_man)."
                          </p>                   
                          <p class='tex tex-3 mar-1'>
                            Totem<c>:</c> $_lun->tot
                            <br>Propiedades<c>:</c> ".Doc_Val::let($_lun->tot_pro)."
                          </p> 
                        </div>                      
                      " )."
      
                      </div>
      
                    </th>
      
                  </tr>";
                  
                  // Plasmas
                  $ele['cab-rad']['data-cab'] = "rad";
                  if( !$ver_rad ) Ele::cla($ele['cab-rad'],"dis-ocu");            
                  $_ .= "
                  <tr".Ele::atr($ele['cab-rad']).">
      
                    <th class='col-tit'>
                      <span class='tex_ali-der'>Plasma</span>
                      <span class='tex_ali-cen'><c>/</c></span>
                      <span class='tex_ali-izq'>Héptada</span>
                    </th>";
      
                    foreach( Dat::_('hol.rad') as $_rad ){ $_ .= "
                      <th>
                        ".Doc_Val::ima('hol',"rad",$_rad)."
                      </th>";
                    }$_ .= "                  
                  </tr>";
                  
                  $_ .="
                </thead>
      
                <tbody>";
                  $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
                  $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
                  
                  if( !$ver_hep ) Ele::cla($ele['hep'],"dis-ocu"); 
      
                  for( $arm = 1; $arm <= 4; $arm++ ){ 
                    $_ .= "
                    <tr data-arm='{$arm}' data-hep='{$hep}'>";
                      
                      // heptadas
                      $ele_hep = $ele['hep'];
                      Ele::cla($ele_hep,"sec hep fon_col-4-{$arm}",'ini');              
                      $_ .= "
                      <td".Ele::atr($ele_hep).">
      
                        ".Doc_Val::ima('hol',"psi_hep",$hep)."                  
                      </td>";
      
                      // días de la semana
                      for( $rad = 1; $rad <= 7; $rad++ ){
      
                        $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);
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
            case 'tot':
              Ele::cla($ele['sec'],'hol-ton');
              $_ = "
              <ul".Ele::atr($ele['sec']).">
                ".Self::dat_tab_sec('ton',$var,$ele)
                ;
                $ele_pos = $ele['pos'];
                foreach( Dat::_('hol.psi_lun') as $_lun ){
      
                  $ele['pos'] = $ele_pos;
                  Ele::cla($ele['pos'],"pos ide-".intval($_lun->ide)." bor-1 pad-1 bor-luz",'ini');
                  $ele['pos']['htm'] = "            
                  ".Doc_Val::tex($_lun->nom,['class'=>"tit tog mar_arr-0"])."
                  ".Doc_Val::tex("$_lun->fec_ini - $_lun->fec_fin",['class'=>"des"])."
                  ".Doc_Val::tex($_lun->tot,['class'=>"tog"])."
                  ".Doc_Val::ima('hol','psi_lun',$_lun)
                  ;
      
                  $_ .= Doc_Dat::tab_pos('hol',$est,$_lun,$var,$ele);
      
                } $_ .= "
              </ul>";
      
              break;
            }
          }
          break;
        // Castillo de 4 estaciones de 52 Heptadas:
        case 'est':
          // castillo de giro solar
          if( !isset($_est[2]) ){

            Ele::cla($ele['sec'],'hol-cas');

            $_ = "
            <ul".Ele::atr($ele['sec']).">
              ".Self::dat_tab_sec('cas',$var,$ele)."
    
              <li class='pos ide-0'>
                ".Doc_Val::ima('hol/tab/sol')."
              </li>";
    
              foreach( Dat::_('hol.cas') as $Cas ){
                $var['ide'] = $Cas->ide; $_ .= "
                <li class='pos ide-".intval($Cas->ide)."'>
                  ".Self::dat_tab('psi_hep',$var,$ele)."
                </li>";            
              } $_ .= "
            </ul>";
          }
          else{
            switch( $_est[2] ){
            // - 1 de 4 Estaciones Solares
            case 'est_dia':
      
              if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = Dat::_('hol.psi',$val['psi'])->est;
      
              $Est = Dat::_('hol.psi_est',$ide); 
              $cas = explode(' - ',$Est->hep)[0];
      
              Ele::cla($ele['sec'],'hol-ton');
              $_ = "
              <ul".Ele::atr($ele['sec']).">
                ".Self::dat_tab_sec('ton',$var,$ele);
      
                foreach( Dat::_('hol.ton') as $Ton ){
                  $var['ide'] = $cas; $_ .= "
                  <li class='pos ide-".intval($Ton->ide)."'>
                    ".Self::dat_tab('psi_hep',$var,$ele)."
                  </li>";
                  $cas++; 
                } $_ .= "
              </ul>";  
              break;
            }
          }        
          break;
        // Heptada por heptágono de 7 Plasmas Radiales
        case 'hep':
  
          if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = Dat::_('hol.psi',$val['psi'])->hep;        
  
          $Hep = Dat::_("hol.{$est}",$ide);
          
          $psi = ( ( intval($Hep->ide) - 1 ) * 7 ) + 1;
          
          if( !isset($ele['hep']) ) $ele['hep']=[];
          Ele::cla($ele['hep'],"dat_tab hol {$est} hol-hep",'ini');
          $_ = "
          <ul".Ele::atr($ele['hep']).">";
            foreach( Dat::_('hol.rad') as $_rad ){
              $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);
              $psi++;
            } $_ .= "
          </ul>";        
          break;
        }        
      }      
      break;
    }

    return $_;
  }// - cargo Valores de un proceso por fechas
  static function dat_tab_val( string $est, array $dat, array $var = [] ) : array {
    $_ = [];

    $cue = 0;
    $ini = isset($var['ini']) ? intval($var['ini']) : 1;
    $inc = isset($var['inc']) ? intval($var['inc']) : 1;
    $val = isset($var['val']) ? intval($var['val']) : "+";
    $est_kin = ( $est == 'kin' && isset($dat['kin']) );
    $est_psi = ( $est == 'psi' && isset($dat['psi']) );
      
    if( isset($dat['fec']) ){

      // x 260 dias por kin 
      if( $est_kin ){
        $cue = 260;
        $fec = Fec::ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
      }
      // x 364+1 dias por psi-cronos
      elseif( $est_psi ){
        $cue = 364;
        $fec = Fec::ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
      }
      // recorro datos    
      for( $pos = 0; $pos < $cue; $pos++ ){

        // salteo el 29/02: no tiene ni kin, ni psicronos ( día hunab ku )
        if( preg_match("/^29-02/",$fec) ){
          $pos--;
        }
        else{

          // pido datos por fecha
          $_dat = Self::val($fec);

          // cargo item en el operador de datos
          $_ []= Doc_Dat::val_var([
            'var'=>[ 
              'fec'=>Fec::dat($fec),
            ],
            'hol'=>[
              'kin'=>Dat::_('hol.kin',$_dat['kin']),
              'psi'=>Dat::_('hol.psi',$_dat['psi']) 
            ]
          ]);
        }
        // ajusto fecha
        $fec = Fec::ope($fec, $inc, $val);
      }
    }

    return $_;
  }// - inicializo parametros 
  static function dat_tab_var( string $esq, string $est, array &$var = [], array &$ele = [] ) : void {

    // parejas del oraculo
    $var['dep_par'] = !empty($var['dep-par']);    

    // portales:
    $var['kin_pag'] = !empty($var['atr-pag']['kin']);
    $var['psi_pag'] = !empty($var['atr-pag']['psi']);    

    // pulsares por valor
    $var['pul_dim'] = isset($var['atr-pul']['dim']);
    $var['pul_mat'] = isset($var['atr-pul']['mat']);
    $var['pul_sim'] = isset($var['atr-pul']['sim']);
    
    // pulsares por posicion
    $var['pos_pul_dim'] = isset($var['pos']['pul']) && ( empty($var['pos']['pul']) || in_array("dim",$var['pos']['pul']) );
    $var['pos_pul_mat'] = isset($var['pos']['pul']) && ( empty($var['pos']['pul']) || in_array("mat",$var['pos']['pul']) );
    $var['pos_pul_sim'] = isset($var['pos']['pul']) && ( empty($var['pos']['pul']) || in_array("sim",$var['pos']['pul']) );
    
  }// - Seccion: onda encantada + castillo
  static function dat_tab_sec( string $tip, array $var=[], array $ele=[], mixed $val = NULL ) : string {

    $_ = "";
    
    $_tip = explode('_',$tip);  

    $ele_eti = $ele['pos']['eti'];

    // ondas encantadas: fondo + pulsares
    if( in_array($_tip[0],['ton','cas']) ){
    
      // fondos: imagen y color
      $ele_ite = isset($ele['fon-ima']) ? $ele['fon-ima'] : [];
      
      Ele::cla($ele_ite,"sec fon ima dis-ocu",'ini'); $_ .= "
      <{$ele_eti}".Ele::atr($ele_ite).">
      </{$ele_eti}>";
  
      $ele_ite = isset($ele['fon-col']) ? $ele['fon-col'] : [];
      Ele::cla($ele_ite,"sec fon col dis-ocu",'ini'); $_ .= "
      <{$ele_eti}".Ele::atr($ele_ite).">
      </{$ele_eti}>";      
      
      $Pulsares = [ 'dim'=>[], 'mat'=>[], 'sim'=>[] ];

      foreach( $Pulsares as $ide => $lis ){

        foreach( Dat::_("hol.ton_{$ide}") as $ton_pul ){

          $Pulsares[$ide] []= Doc_Val::ima("hol/tab/gal/$ide/{$ton_pul->ide}");
        }
      }

      if( isset($var['val']['pos']) ){

        $_val = $var['val']['pos'];

        if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || is_numeric($_val) ||( is_object($_val) && isset($_val->ide) ) ){

          if( is_numeric($_val) ){
            $Ton = Dat::_('hol.ton',Num::ran($_val,13));
          }
          else{
            $Ton = Dat::_('hol.ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
          }
        }
      }
    }

    switch( $_tip[0] ){
    // holon
    case 'hol':
      switch( $_tip[1] ){
      // sellos: solar (planetas) + planetario (regiones) + humano (dedos)
      case 'sel':
        // por kines 
        if( isset($val) && is_array($val) && isset($val['kin']) ){

          Ele::cla($ele['pos'],"sel");
          
          $Tra = Dat::_('hol.kin_arm_tra', Dat::_('hol.kin',$val['kin'])->arm_tra);

          $kin = $kin_ini = explode('-',$Tra->kin)[0];
          
          for( $sel = 1; $sel <= 19; $sel++ ){

            $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);

            $kin = Num::ran($kin + 1,260);
          }
          // imprimo el sol anterior
          $_ .= Doc_Dat::tab_pos('hol','kin',Num::ran($kin_ini - 1, 260),$var,$ele);

        }
        // posicion: sellos
        else{
          foreach( Dat::_('hol.sel') as $Sel ){ 
            $_ .= "
            <{$ele_eti} class='pos sel ide-{$Sel->ide}'>
              ".Doc_Val::ima('hol','sel_cod',$Sel)."
            </{$ele_eti}>";
          }
        }
        break;
      // tonos: articulaciones del humano
      case 'ton': 
        // por kines 
        if( isset($val) && is_array($val) && isset($val['kin']) ){

          Ele::cla($ele['pos'],"ton");
          
          $Ond = Dat::_('hol.kin_nav_ond', Dat::_('hol.kin',$val['kin'])->nav_ond);

          $kin = $kin_ini = explode('-',$Ond->kin)[0];
          
          for( $ton = 1; $ton <= 13; $ton++ ){

            $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);

            $kin++;
          }
        }
        // posicion: tonos de articulaciones
        else{
          foreach( Dat::_('hol.ton') as $Ton ){ 
            $_ .= "
            <{$ele_eti} class='pos ton ide-{$Ton->ide}'>
              ".Doc_Val::ima('hol','ton',$Ton)."
            </{$ele_eti}>";
          }
        }
        break;
      // plasmas: chakras del humano
      case 'rad': 
        // por psi-cronos 
        if( isset($val) && is_array($val) && isset($val['psi']) ){

          Ele::cla($ele['pos'],"ton");
          
          $Hep = Dat::_('hol.psi_hep', Dat::_('hol.psi',$val['psi'])->hep);

          $psi = explode('-',$Hep->psi)[0];
          
          for( $rad = 1; $rad <= 7; $rad++ ){

            $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);

            $psi++;
          }
        }
        // posicion: plasmas radiales de chakras
        else{
          foreach( Dat::_('hol.rad') as $Rad ){ 
            $_ .= "
            <{$ele_eti} class='pos rad ide-{$Rad->ide}'>
              ".Doc_Val::ima('hol','rad',$Rad)."
            </{$ele_eti}>";
          }
        }        
        break;
      }   
      break;
    // onda
    case 'ton':
      // pulsares
      foreach( $Pulsares as $ide => $lis ){ 

        foreach( $lis as $pul_pos => $pul_ima ){

          $pos = $pul_pos + 1;
          $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
          $cla_agr = " dis-ocu";

          if( isset($Ton) ){
            if( $var["pos_pul_$ide"] && $Ton->$ide == $pos ) $cla_agr = "";
          }
          elseif( $var["pul_$ide"] && ( empty($var['pul'][$ide]) || in_array($pos,$var['pul'][$ide]) ) ){

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
      $orb_ocu = !empty($var['est-cas']['orb']) ? '' : ' dis-ocu';
      $col_ocu = !empty($var['est-ton']['col']) ? '' : ' fon-0';
      
      // fondos: imagen
      $ele_fon = isset($ele["fon-ima"]) ? $ele["fon-ima"] : [];
      for( $i = 1; $i <= 4; $i++ ){ 
        $ele_ite = $ele_fon;
        Ele::cla($ele_ite,"sec fon ima ond-$i dis-ocu",'ini'); $_ .= "
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
      foreach( $Pulsares as $ide => $lis ){ 
        
        // reocrro 4 ondas
        for( $i = 1; $i <= 4; $i++ ){
          
          // recorro pulsares
          foreach( $lis as $pul_pos => $pul_ima ){
            
            $pos = $pul_pos + 1;
            
            $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
            $cla_agr = " dis-ocu";
            
            if( isset($Ton) && $var["pos_pul_$ide"] && $Ton->$ide == $pos ){

              $cla_agr = "";
            }
            elseif( $var["pul_$ide"] && ( empty($var['pul'][$ide]) || in_array($pos,$var['pul'][$ide]) ) ){

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
  }// - Posicion: titulos + patrones
  static function dat_tab_pos( string $est, mixed $val, array &$var, array $ele, array &$Ele = [] ) : string {
    
    $_ = "";

    // armo titulos y cargo operadores
    if( empty($Ele) && isset($ele['pos']) ) $Ele = $ele['pos'];    
    $cla_agr = [];
    $pos_tit = [];
    
    if( isset($Ele["var-fec"]) ){
      $pos_tit []= "Calendario: {$Ele["var-fec"]}";
    }
    if( isset($Ele["hol-kin"]) ){
      $Kin = Dat::_('hol.kin',$Ele["hol-kin"]);
      $pos_tit []= Doc_Dat::val('tit',"hol.kin",$Kin);
      if( $var['kin_pag'] && !empty($Kin->pag) ) $cla_agr []= "pag-kin";
    }
    if( isset($Ele["hol-sel"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.sel",$Ele["hol-sel"]);
    }
    if( isset($Ele["hol-ton"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.ton",$Ele["hol-ton"]);
    }
    if( isset($Ele["hol-psi"]) ){
      $Psi = Dat::_('hol.psi',$Ele["hol-psi"]);
      $pos_tit []= Doc_Dat::val('tit',"hol.psi",$Psi);          
      if( $var['psi_pag'] ){
        $Psi->kin = Dat::_('hol.kin',$Psi->kin);
        if( !empty($Psi->kin->pag) ) $cla_agr []= "pag-psi";
      }
    }
    if( isset($Ele["hol-rad"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.rad",$Ele["hol-rad"]);
    }

    // titulo
    if( !empty($pos_tit) ) $Ele['title'] = implode("\n\n",$pos_tit);
    
    // operadores: .pos.dep + .pag_kin + .pag_psi
    if( !empty($cla_agr) ) Ele::cla($Ele,$cla_agr);
    
    // modifico html por patrones: posicion por dependencia
    
    if( !!$var['dep_par'] ){
      
      $par_est = $est;

      $par_ele = [ 
        'pos'=>[], // para todas las posiciones: bordes, color, imagen
        'des'=>[], // para el destino : posicion principal
        'ima'=> isset($ele['ima']) ? $ele['ima'] : []
      ];
      
      // cambio clases del operador
      if( !empty($Ele['class']) ){

        if( preg_match("/ope/",$Ele['class']) ){

          $par_ele['pos']['class'] = "ope";
          $Ele['class'] = str_replace("  ", " ", str_replace("ope","",$Ele['class']));
        }

        // cargo clases principales al destino
        $par_ele['des']['class'] = $Ele['class'];          
        unset($Ele['class']);
      }

      // copio eventos
      if( isset($Ele['onclick']) ){

        $par_ele['pos']['onclick'] = $Ele['onclick'];
        unset($Ele['onclick']);
      }

      // cambio datos del operador
      if( isset($Ele["var-fec"]) ){

        // cambio valor 
        if( $est == 'psi' && isset($Ele["hol-kin"]) ){
          $par_est = "kin";
          $val = $Ele["hol-kin"];
        }

        // cargo datos de la fecha por posicion del oraculo
        if( $par_est != $est ){

          $Kin = Dat::_('hol.kin',$val);
          
          $ele_par = [ 'ana'=>[], 'gui'=>[], 'ant'=>[], 'ocu'=>[] ];

          foreach( $ele_par as $par_ide => &$par_dat ){

            foreach( $var['dat'] as $var_dat ){

              if( $var_dat['hol-kin'] == $Kin->{"par_{$par_ide}"} ){

                $par_dat = $var_dat;
                break;
              }
            }
          }

          $ele_par['des'] = $Ele;
          foreach( ['var-fec','hol-psi','hol-lun','hol-rad'] as $ele_dat ){
            $par_ele['par-des']["{$ele_dat}"] = $ele_par['des']["{$ele_dat}"];
            $par_ele['par-ana']["{$ele_dat}"] = $ele_par['ana']["{$ele_dat}"];
            $par_ele['par-gui']["{$ele_dat}"] = $ele_par['gui']["{$ele_dat}"];
            $par_ele['par-ant']["{$ele_dat}"] = $ele_par['ant']["{$ele_dat}"];
            $par_ele['par-ocu']["{$ele_dat}"] = $ele_par['ocu']["{$ele_dat}"];
          }
        }
      }

      // agrego clase por dependencia/anidacion
      Ele::cla($Ele,'dep');
      
      $_ = Self::dat_tab("{$par_est}_par",[
        'ide'     => $val,
        'dep'     => 1,
        'dep-par' => $var['dep-par'] - 1,
        'sec'     => isset($var['sec-dep']) ? $var['sec-dep'] : [],
        'pos'     => isset($var['pos']) ? $var['pos'] : [ 'ima'=>"hol.{$par_est}.ide" ]
      ],
        $par_ele
      );
    }

    return $_;
  }
}


