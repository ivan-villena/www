<?php

if( !class_exists("Usuario") ){

  require_once("./App/Sincronario/Usuario.php");
}

class Sincronario {

  static string $IDE = "Sincronario-";
  static string $EJE = "Sincronario.";
  
  public function __construct( mixed $fec = "" ){

    if( !empty($fec) ){

      $this->Val = $this->val( $fec );
    }
  }

  /* Valores */
  public array $Val = [
    'kin' => 0,
    'psi' => 0,
    'sin' => ""
  ];// busco valores : fecha - sir - kin - psi
  static function val( mixed $val ) : array | object | string {
    $_=[];
    
    // del sincronario
    if( is_string($val) && preg_match("/\./",$val) ){
        // busco año          
        if( $fec = Sincronario::val_cod($val) ){

          // convierto fecha
          $_ = Sincronario::val($fec);

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

      $_fec = Sincronario::val_dec( $fec );

      $_psi = Dat::get( Dat::_('hol.psi'), [ 
        'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 
        'opc'=>[ 'uni' ]
      ]);

      // giro lunar => mes + día
      if( !empty($_psi) ){

        $_['psi'] = $_psi->ide;

        $_['sin'] = "{$_fec->sir}.".Num::val($_fec->ani,2).".{$_psi->ani_lun}.{$_psi->ani_lun_dia}";
        
        // giro galáctico => kin
        $_kin = Dat::_('hol.kin', Num::ran( $_fec->fam_2 + $_psi->fec_cod + $_fec->dia, 260 ) );

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
      $_psi = Dat::get( Dat::_('hol.psi'), [ 'ver'=>[ ['ani_lun','==',$lun], ['ani_lun_dia','==',$dia] ], 'opc'=>['uni'] ]);      
  
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
  }// genero datos acumulados para un proceso por valor principal
  static function val_dat( string $est, array $dat, array $var = [] ) : array {
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
          $_dat = Sincronario::val($fec);

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
  }

  // forms
  static function dat( string $tip, mixed $dat = NULL, array $var = [], ...$opc ) : string {
    $_ = "";    
    $_ide = self::$IDE."dat_$tip";
    $_eje = self::$EJE."dat_$tip";
    $_tip = explode('-',$tip);
    $atr = isset($_tip[1]) ? $_tip[1] : '';
    switch( $est = $_tip[0] ){
    case 'fec':
      $_eje =  !empty($var['eje']) ? $var['eje'] : self::$EJE."_val";
      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : Dat::_('hol.kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : Dat::_('hol.psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      
  
      $_ = "
      <!-- Fecha del Calendario -->
      <form class='fec -val mar-1'>
  
        ".Doc_Val::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol-val-fec", 'class'=>"mar_hor-1", 
          'title'=>"Desde aquí puedes cambiar la fecha..." 
        ])."
        ".Doc_Var::fec('dia', $_fec, [ 'id'=>"hol-val-fec", 'name'=>"fec", 
          'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
        ])."
        ".Doc_Val::ico('ope_val-ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje( this );", 
          'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
        ])."
  
      </form>
  
      <!-- Fecha del Sincronario -->
      <form class='sin -val mar-1'>
        
        <label>N<c>.</c>S<c>.</c></label>
  
        ".Doc_Var::num('int', $_sin[0], [ 
          'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
        ])."
        <c>.</c>
        ".Doc_Val::opc( Dat::_('hol.psi_ani'), [
          'eti'=>[ 'name'=>"ani", 'class'=>"num", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
          'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        ".Doc_Val::opc( Dat::_('hol.psi_ani_lun'), [
          'eti'=>[ 'name'=>"lun", 'class'=>"num", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
          'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        ".Doc_Val::opc( Dat::_('hol.lun'), [ 
          'eti'=>[ 'name'=>"dia", 'class'=>"num", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
          'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
        ])."          
        <c class='sep'>:</c>
    
        <n name='kin'>$_kin->ide</n>
  
        ".Doc_Val::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);",
          'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
        ])."
  
      </form>";
      
      break;
    }
    return $_;
  }// Informe
  static function dat_inf( string $ide, mixed $dat = NULL, array $var = [], ...$opc ) : string {
    $_ = "";
    
    $Ide = explode('-',$ide);

    $atr = isset($Ide[1]) ? $Ide[1] : '';

    switch( $est = $Ide[0] ){
    case 'kin':
      $_bib = SYS_NAV."sincronario/lir/";
      $_kin = $dat = Dat::_("hol.{$est}",$dat);
      $_sel = Dat::_('hol.sel',$dat->arm_tra_dia);
      $_ton = Dat::_('hol.ton',$dat->nav_ond_dia);
      switch( $atr ){
      // parejas del oráculo
      case 'par':
        $_ = "
          
        ".Sincronario::dat_tab("kin","par",[ 'ide'=>$dat, 'pos'=>[ 'ima'=>"hol.kin.ide"  ] ], [ 'sec'=>[ 'class'=>"mar_aba-1" ] ])."

        <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='{$_bib}enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>

        <div class='lis'>";
        foreach( Dat::_('hol.sel_par') as $_par ){
          // salteo el destino
          if( ( $ide = $_par->cod ) == 'des' ) continue;
          // busco datos de parejas
          $_par = Dat::get( Dat::_('hol.sel_par'), [ 'ver'=>[ ['cod','==',$ide] ], 'opc'=>'uni' ]);
          $kin = Dat::_('hol.kin',$dat->{"par_{$ide}"});
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
          
          $_kin_par = $_par->ide == 'des' ? $_kin : Dat::_('hol.kin',$_kin->{"par_{$_par->ide}"});
  
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
        <p>En <a href='{$_bib}tut#_04-04-' target='_blank'>este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

        <p>Puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>";
        
        foreach( Dat::_('hol.sel_par') as $_par ){

          if( $_par->ide == 'des' ) continue;
          $_kin_par = Dat::_('hol.kin',$_kin->{"par_{$_par->ide}"});
          $_sel_par = Dat::_('hol.sel',$_kin_par->arm_tra_dia);
          $_ []=
          Doc_Val::ima('hol',"kin",$_kin_par)."

          <div>
            <p><b class='tit'>{$_kin_par->nom}</b> <c>(</c> ".Doc_Val::let($_par->dia)." <c>)</c></p>
            <p>".Doc_Val::let("{$_sel_par->acc} {$_par->des_pod} {$_sel_par->des_car}, que {$_par->mis} {$_sel->des_car}, {$_par->acc} {$_sel_par->des_pod}.")."</p>
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

        $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
  
        foreach( Dat::_('hol.sel_par') as $_par ){
          
          $_kin_par = $_par->ide == 'des' ? $_kin : Dat::_('hol.kin',$_kin->{"par_{$_par->ide}"});

          $ite = [ Doc_Val::ima('hol',"kin",$_kin_par) ];

          foreach( $_atr as $atr ){
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
        <p>Puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='{$_bib}tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

        <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>";

        $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];  

        foreach( Dat::_('hol.sel_par') as $_par ){
          
          $_kin_par = $_par->ide == 'des' ? $_kin : Dat::_('hol.kin',$_kin->{"par_{$_par->ide}"});                            
  
          $_sel_par = Dat::_('hol.sel',$_kin_par->arm_tra_dia);
  
          $ite = [ Doc_Val::ima('hol',"kin",$_kin_par), $_par->nom, $_sel_par->des_pod ];
  
          foreach( $_atr as $atr ){
            $ite []= Doc_Val::ima('hol',"sel_{$atr}",$_sel_par->$atr,[ 'class'=>"tam-5" ]);
          }            
          $_ []= $ite;
        }

        $_ = $htm.Doc_Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $var);       
        break;
      }
      break;
    }
    return $_;
  }// Descripciones
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
  }// Tablero
  static function dat_tab( string $est, string $atr, array $var = [], array $ele = [] ) : string {

    extract( Doc_Dat::tab_var("hol",$est,$atr,$var,$ele) );

    $_ = "";
    switch( $tab ){
    // holon interplanetario
    case 'sol':
      switch( $atr ){
      // Sistema Solar ( vertical : T.K. )
      case 'pla': 
        $sec = Doc_Dat::tab_sec($var,['pla','orb','ele','cel','cir']); $_ = "
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
                Doc_Val::ima('hol',"sol_res",$v)."
              </li>";
            }
          }// x 4 flujos : alfa <-> omega
          foreach( Dat::_('hol.flu') as $v ){ $_ .= "
            <li class='sec ima flu-{$v->ide} pod-{$v->pod}'>".
              Doc_Val::ima('hol',"flu_pod",$v->pod)."
            </li>";
          }
          // 10 planetas
          foreach( Dat::_('hol.sol_pla') as $v ){ 
            $cla = ( $sec['pla'] && ( empty($sec['pla']) || in_array($v->ide,$sec['pla']) ) ) ? "" : " ".DIS_OCU;
            $_ .= "
            <li class='sec bor pla-{$v->ide}{$cla}'></li>
            <li class='sec ima pla-{$v->ide}'>".Doc_Val::ima('hol',"sol_pla",$v)."</li>";
          }
          // Secciones por Seleccion
          // - 2 grupos orbitales
          foreach( Dat::_('hol.sol_orb') as $v ){ 
            $cla = ( $sec['orb'] !== FALSE && ( empty($sec['orb']) || in_array($v->ide,$sec['orb']) ) ) ? "" : " ".DIS_OCU; 
            $_ .= "
            <li class='sec bor orb-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_orb",$v)."'></li>";
          }// - 4 elementos/clanes
          foreach( Dat::_('hol.sel_cro_ele') as $v ){ 
            $cla = ( $sec['ele'] !== FALSE && ( empty($sec['ele']) || in_array($v->ide,$sec['ele']) ) ) ? "" : " ".DIS_OCU; 
            $_ .= "
            <li class='sec bor ele-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }// - 5 células solares
          foreach( Dat::_('hol.sol_cel') as $v ){ 
            $cla = ( $sec['cel'] !== FALSE && ( empty($sec['cel']) || in_array($v->ide,$sec['cel']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor cel-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_cel",$v)."'></li>";
          }// - 5 circuitos de telepatía
          foreach( Dat::_('hol.sol_cir') as $v ){ 
            $cla = ( $sec['cir'] !== FALSE && ( empty($sec['cir']) || in_array($v->ide,$sec['cir']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor cir-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_cir",$v)."'></li>";
          }
          // posicion: 20 sellos solares
          foreach( Dat::_('hol.sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel'>
              ".Doc_Val::ima('hol',"sel_cod",$v)."
            </li>";
          }$_ .= " 
        </ul>";        
        break;
      // Sistema Solar ( circular : E.S. )
      case 'cel':
        $sec = Doc_Dat::tab_sec($var,['pla','orb','ele','cel','cir']);
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
          foreach( Dat::_('hol.sol_pla') as $v ){ $_ .= "
            <li class='sec pla-$v->ide'>
              ".Doc_Val::ima('hol','sol_pla',$v)."
            </li>";
          }
          // posicion: sellos
          foreach( Dat::_('hol.sel') as $v ){ $_ .= "
            <li class='pos ide-$v->ide sel'>
              ".Doc_Val::ima('hol','sel_cod',$v)."
            </li>";
          }
          $_ .= " 
        </ul>";            
        break;
      }
      break;
    // holon planetario
    case 'pla':
      switch( $atr ){
      case 'map': 
        $sec = Doc_Dat::tab_sec($var,['res','ele','hem','mer','cen']); $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon map'></li>
          <li class='sec fon sel'></li>";
          // fondos: flujos, 
          foreach( ['res','ele'] as $i ){ 
            $cla = ( $sec[$i] !== FALSE ) ? "" : " ".DIS_OCU; $_ .= "
            <li class='sec fon {$i}{$cla}'></li>";
          }
          // 3 Hemisferios
          foreach( Dat::_('hol.pla_hem') as $v ){
            $cla = ( $sec['hem'] !== FALSE && ( empty($sec['hem']) || in_array($v->ide,$sec['hem']) ) ) ? "" : " ".DIS_OCU;  
            $_ .= "
            <li class='sec bor hem-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_hem",$v)."'></li>";
          }          
          // 2 Meridianos
          foreach( Dat::_('hol.pla_mer') as $v ){
            $cla = ( $sec['mer'] !== FALSE && ( empty($sec['mer']) || in_array($v->ide,$sec['mer']) ) ) ? "" : " ".DIS_OCU;  
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
          // 20 Sellos solares
          foreach( Dat::_('hol.sel') as $v ){ $_ .= "
            <li class='pos ide-{$v->ide} sel'>
              ".Doc_Val::ima('hol',"sel",$v)."
            </li>";
          }
          $_ .= "
        </ul>";          
        break;
      }      
      break;
    // holon humano
    case 'hum':
      switch( $atr ){
      case 'map': 
        $sec = Doc_Dat::tab_sec($var,['res','ext','cen','cha','art','ded']); $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon map'></li>";
          // 2 Lados del Cuerpo : Respiración del Holon
          foreach( Dat::_('hol.hum_res') as $v ){
            $cla = ( $sec['res'] !== FALSE && ( empty($sec['res']) || in_array($v->ide,$sec['res']) ) ) ? "" : " ".DIS_OCU; $_ .= "
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
          // 20 Dedos : Sellos Solares
          foreach( Dat::_('hol.sel') as $v ){ 
            if( $sec['ded'] !== FALSE ){ $cla = in_array($v->ide,$sec['ded']) ? " fon-sel" : ""; }else{ $cla = " dis-ocu"; }
            $_ .= "
            <li class='pos ide-{$v->ide} sel{$cla}'>
              ".Doc_Val::ima('hol',"sel",$v)."
            </li>";
          }
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
        break;      
      }      
      break;
    // telepatia
    case 'tel':
      switch( $atr ){
      case 'map': 
        $sec = Doc_Dat::tab_sec($var,['pla','orb','ele','cel','cir']); $_ = "
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
        break;
      }      
      break;
    // plasma radial
    case 'rad':
      switch( $atr ){
      case 'pla':
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
        break;
      }
      break;
    // tono galáctico
    case 'ton':
      switch( $atr ){
      // onda encantada
      case 'ond':
        Ele::cla($ele['sec'],"dat_tab ton_ond hol-ton",'ini');
        $_ .= "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('ton',$var,$ele)
          ;
          foreach( Dat::_('hol.ton') as $_ton ){
            // cargo datos de la posicion
            $ele['pos']['hol-ton'] = $_ton->ide;
            $_ .= Doc_Dat::tab_pos('hol','ton',$_ton,$var,$ele);
          } $_ .= "
        </ul>";
        break;
      }
      break;
    // sello solar
    case 'sel':
      switch( $atr ){
      // codigo
      case 'cod':
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          foreach( Dat::_('hol.sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _pos' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='-val jus-cen'>
                ".Doc_Val::ima('hol',"sel",$_sel,['eti'=>"li"])."
                ".Doc_Val::ima('hol',"sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
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
        $_sel = is_object($ide) ? $ide : Dat::_('hol.sel',$ide);           
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
            if( $par_ide != 'gui' ){ $par_sel = ( $par_ide == 'des' ) ? $_sel : Dat::_('hol.sel',$_sel->{"par_{$par_ide}"}); }
            $_ .= Doc_Dat::tab_pos('hol','sel',$par_sel,$var,$ele);
          }$_ .= "
        </ul>";
        break;
      // colocacion cromática
      case 'cro':
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
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _pos' : '' ;
            $ele['pos'] = $ele_pos;
            Ele::cla($ele['pos'],"{$agr}");
            $_ .= Doc_Dat::tab_pos('hol','sel',$v,$var,$ele);
          } $_ .= "
        </ul>";
        break;
      // colocacion armónica
      case 'arm':
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
            $agr = ( !empty($ide) && $v->ide == $ide ) ? ' _pos' : '' ;
            $ele['pos'] = $ele_pos;
            Ele::cla($ele['pos'],"{$agr}");
            $_ .= Doc_Dat::tab_pos('hol','sel',$v,$var,$ele);
          }
          $_ .= "
        </ul>";        
        break;
      // tablero del oráculo
      case 'arm_tra':
        Ele::cla($ele['sec'],"hol-cro");
        $_ .= "
        <ul".Ele::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){
            $var['ide'] = $i;            
            $_ .= "
            <li class='pos ide-{$i}'>
              ".Sincronario::dat_tab('sel','arm_cel',$var,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;      
      // célula del tiempo para el oráculo
      case 'arm_cel':
        $_arm = Dat::_("hol.sel_{$atr}",$ide);        
        $ele['cel']['title'] = Doc_Dat::val('tit',"hol.{$est}",$_arm); 
        Ele::cla($ele['cel'],"dat_tab sel_{$atr} hol-arm",'ini');
        $_ = "
        <ul".Ele::atr($ele['cel']).">
          ".Doc_Val::ima('hol',"sel_arm_cel", $_arm, ['eti'=>"li", 'class'=>"pos ide-0", 'htm'=>$_arm->ide ] );
          foreach( explode(', ',$_arm->sel) as $sel ){
            $_ .= Doc_Dat::tab_pos('hol','sel',$sel,$var,$ele);
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    // giro lunar
    case 'lun':
      switch( $atr ){
      // 28 plasmas en 4 heptadas
      case 'pla':
        Ele::cla($ele['sec'],"hol-lun bor-1"); 
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
        break;
      }
      break;
    // castillo fractal
    case 'cas':
      switch( $atr ){
      // ondas encantadas
      case 'ond':
        Ele::cla($ele['sec'],"hol-cas");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          <li class='sec fon-ima'></li>          
          ".Sincronario::dat_tab_sec('cas',$var,$ele)."
          <li class='pos ide-0'></li>";

          foreach( Dat::_('hol.cas') as $_cas ){
            // cargo datos de la posicion
            $ele['pos']['hol-cas'] = $_cas->ide;
            $ele['pos']['hol-ton'] = $_cas->ton;
            $_ .= Doc_Dat::tab_pos('hol','cas',$_cas,$var,$ele);
          } $_ .= "
        </ul>";
        break;
      }
      break;
    // giro galáctico
    case 'kin':
      switch( $atr ){
      // tzolkin
      case 'tzo':
        $ton_htm = isset($var['sec']['kin-ton']);
        $ton_val = !empty($var['sec']['kin-ton']);
        $ele_ton = [ 'class'=> "sec ton" ];
        $sel_htm = isset($var['sec']['kin-sel']);
        $sel_val = !empty($var['sec']['kin-sel']);
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
            foreach( Dat::_('hol.sel') as $v ){ $_ .= "
              <li class='sec sel ide-{$v->ide}".( $sel_val ? "" : " dis-ocu" )."'>".Doc_Val::ima('hol',"sel",$v)."</li>";
            }
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( Dat::_('hol.kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){ $_ .= 
              "<li class='sec ton ide-{$_kin->arm_tra}".( $ton_val ? "" : " dis-ocu" )."'>
                ".Doc_Val::ima('hol',"kin_arm_tra",$_kin->arm_tra)."
              </li>";
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            $_ .= Doc_Dat::tab_pos('hol','kin',$_kin,$var,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </ul>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par':
        if( empty($ide) ) $ide = 1;
        $_kin = is_object($ide) ? $ide : Dat::_('hol.kin',$ide);           
        Ele::cla($ele['sec'],"hol-cro");
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele_pos = $ele['pos'];
          foreach( Dat::_('hol.sel_par') as $_par ){
            
            $par_ide = $_par->cod;
            $par_kin = ( $par_ide == 'des' ) ? $_kin : Dat::_('hol.kin',$_kin->{"par_{$par_ide}"});
            
            // combino elementos :
            $ele['pos'] = isset($ele["par-{$par_ide}"]) ? Ele::val_jun($ele_pos,$ele["par-{$par_ide}"]) : $ele_pos;
            Ele::cla($ele['pos'],"par-{$par_ide}");

            $_ .= Doc_Dat::tab_pos('hol','kin',$par_kin,$var,$ele);

          }$_ .= "
        </ul>";
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_fam = Dat::_('hol.sel_cro_fam',$ide);
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('cas',$var,$ele)."
          <li class='pos ide-0'>
            ".Doc_Val::ima('hol','sel_cro_fam',$_fam)."
          </li>";

          $kin = intval($_fam['kin']);
          foreach( Dat::_('hol.cas') as $_cas ){
            $_kin = Dat::_('hol.kin',$kin);
            $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
            $kin = Num::ran($kin + 105, 260);
          } $_ .= "
        </ul>";          
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        Ele::cla($ele['sec'],"hol-cro");
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          foreach( Dat::_('hol.kin_nav_cas') as $cas => $_cas ){
            $var['ide'] = $_cas->ide; $_ .= "
            <li class='pos ide-".intval($_cas->ide)."'>
              ".Sincronario::dat_tab('kin','nav_cas',$var,$ele)."
            </li>";
          } $_ .= "
        </ul>";
        break;
      case 'nav_cas':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->$atr;
        $_cas = Dat::_('hol.'.$est,$ide);
        // clases del castillo
        if( !isset($ele['cas']) ) $ele['cas'] = [];
        Ele::cla($ele['cas'],"dat_tab kin_{$atr} hol-cas fon_col-5-{$ide}".( empty($var['sec']['cas-col']) ? ' fon-0' : '' ),'ini');
        // titulos
        $ele['cas']['title'] = Doc_Dat::val('tit',"hol.{$est}",$_cas);        
        $ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ini + 4;        
        for( $ond = $ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = Dat::_('hol.kin_nav_ond',$ond);
          $ele['cas']['title'] .= "\n".$_ond->enc_des;
        }
        $_ = "
        <ul".Ele::atr($ele['cas']).">
          ".Sincronario::dat_tab_sec('cas',$var,$ele)."
          <li class='pos ide-0'>
            ".Doc_Val::ima('hol','kin_nav_cas',$ide,[ 'title'=>$ele['cas']['title'] ])."
          </li>";
          $kin = ( ( $ide - 1 ) * 52 ) + 1;
          foreach( Dat::_('hol.cas') as $_cas ){
            $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;
      case 'nav_ond':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->$atr;
        $_ond = Dat::_("hol.$est",$ide);
        // clases
        if( !isset($ele['ond']) ) $ele['ond'] = [];
        Ele::cla($ele['ond'],"dat_tab kin_{$atr} hol-ton",'ini');
        // titulo        
        $ele['ond']['title'] = Doc_Dat::val('tit',"hol.kin_nav_cas",$_ond->nav_cas)." .\n{$_ond->enc_des}"; 
        $_ = "
        <ul".Ele::atr($ele['ond']).">
          ".Sincronario::dat_tab_sec('ton',$var,$ele);

          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          foreach( Dat::_('hol.ton') as $_ton ){
            $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;      
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        Ele::cla($ele['sec'],"hol-ton");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('ton',$var,$ele);

          foreach( Dat::_('hol.kin_arm_tra') as $_tra ){ 
            $var['ide'] = $_tra->ide; $_ .= "
            <li class='pos ide-".intval($_tra->ide)."'>
              ".Sincronario::dat_tab('kin','arm_tra',$var,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      case 'arm_tra':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->$atr;
        $_tra = Dat::_('hol.kin',$ide);
        $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
        $cel_fin = $cel_ini + 5;

        if( !isset($ele['tra']) ) $ele['tra']=[];
        Ele::cla($ele['tra'],"dat_tab kin_{$atr} hol-cro",'ini');
        $_ = "
        <ul".Ele::atr($ele['tra']).">";
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $var['ide'] = $cel; $_ .= "
            <li class='pos ide-".Num::ran($cel,5)."'>
              ".Sincronario::dat_tab('kin','arm_cel',$var,$ele)."
            </li>";            
          } $_ .= "
        </ul>";
        break;
      case 'arm_cel': 
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->$atr;
        $_arm = Dat::_('hol.'.$est,$ide);

        if( !isset($ele['cel']) ) $ele['cel']=[];
        Ele::cla($ele['cel'],"dat_tab kin_{$atr} hol-arm fon_col-5-$_arm->cel fon-0");
        $_ = "
        <ul".Ele::atr($ele['cel']).">
          <li class='pos ide-0 col-bla'>
            ".Doc_Val::ima('hol',"sel_arm_cel",$_arm->cel,[ 'htm'=>$_arm->ide, 'title'=>Doc_Dat::val('tit',"hol.{$est}",$_arm) ])."
          </li>";

          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);
            $kin++;
          } $_ .= "
        </ul>";
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro':
        if( !in_array('fic_cas',$var['opc']) ) $var['opc'] []= 'fic_cas';
        Ele::cla($ele['sec'],"hol-cas");
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('cas',$var,$ele)."

          <li class='pos ide-0'>
            ".Doc_Val::ima('hol/tab/gal')."
          </li>";
          foreach( Dat::_('hol.kin_cro_ele') as $_ele ){
            $var['ide'] = $_ele->ide; $_ .= "
            <li class='pos ide-".intval($_ele->ide)."'>
              ".Sincronario::dat_tab('kin','cro_ele',$var,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      case 'cro_est':
        if( !in_array('fic_cas',$var['opc']) ) $var['opc'] []= 'fic_ond';
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->$atr;
        $_est = Dat::_('hol.kin_cro_est',$ide); 
        $cas = explode(' - ',$_est->cas)[0];
        
        if( !isset($ele['est']) ) $ele['est']=[];
        Ele::cla($ele['est'],"dat_tab kin_{$atr} hol-ton",'ini');
        $_ = "
        <ul".Ele::atr($ele['est']).">
          ".Sincronario::dat_tab_sec('ton',$var,$ele);
          
          foreach( Dat::_('hol.ton') as $_ton ){
            $var['ide'] = $cas; $_ .= "
            <li class='pos ide-".intval($_ton->ide)."'>
              ".Sincronario::dat_tab('kin','cro_ele',$var,$ele)."
            </li>";
            $cas = Num::ran($cas + 1, 52);
          } $_ .= "
        </ul>";
        break;
      case 'cro_ele':
        if( empty($ide) && is_array($val) && isset($val['kin']) ) $ide = Dat::_('hol.kin',$val['kin'])->$atr;
        $_ele = Dat::_('hol.kin_cro_ele',$ide);
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
        Ele::cla($ele['ele'],"dat_tab kin_{$atr} hol-cro-cir",'ini');
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
      break;
    // giro solar
    case 'psi':
      switch( $atr ){
      // Banco-psi
      case 'kin': 
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          $ele['sec'] = isset($ele['tzo']) ? $ele['tzo'] : [];
          for( $i=1 ; $i<=8 ; $i++ ){ $_ .= "
            <li class='pos ide-$i'>
              ".Sincronario::dat_tab('kin','tzo',$var,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // Ciclo de Sirio
      case 'sir':
        $kin = 34;
        $var['sec']['orb_cir'] = '1';
        Ele::cla($ele['sec'],'hol-cas-cir');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('cas_cir',$var,$ele)."
          <li class='pos ide-0'>
          </li>";

          foreach( Dat::_('hol.cas') as $_cas ){
            $_kin = Dat::_('hol.kin',$kin); $_ .= "
            <li class='pos ide-".intval($_cas->ide)."'>
              ".Doc_Val::ima('hol',"kin",$_kin)."
            </li>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </ul>";        
        break;
      // Anillo de 13 Lunas
      case 'ani':
        Ele::cla($ele['sec'],'hol-ton');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Doc_Val::ima('hol/tab/sol',['eti'=>"li", 'class'=>"sec sol"])."
          ".Doc_Val::ima('hol/tab/pla',['eti'=>"li", 'class'=>"sec lun"])."
          ".Sincronario::dat_tab_sec('ton',$var,$ele)
          ;
          if( !in_array('cab_nom',$var['opc']) ) $var['opc'] []= 'cab_nom';
          for( $lun = 1; $lun <= 13; $lun++ ){
            $var['ide'] = $lun; $_ .= "
            <li class='pos ide-{$lun}'>
              ".Sincronario::dat_tab('psi','ani_lun',$var,$ele)."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // - Giro Lunar: 28 días
      case 'ani_lun':
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = Dat::_('hol.psi',$val['psi'])->$atr;
        $_lun = Dat::_('hol.'.$est,$ide);
        $_ton = Dat::_('hol.ton',$ide);

        $ele['pos']['eti'] = 'td';
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);
        
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        Ele::cla($ele['lun'],"dat_tab psi {$atr} hol-lun",'ini'); $_ = "
        <table".Ele::atr($ele['lun']).">";
          if( !$cab_ocu ){ $_ .= "
            <thead>";
              // Luna
              $_ .= "
              <tr data-cab='ton'>
                <th colspan='8'>
                  <div class='-val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    ".Doc_Val::ima('hol',$est,$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-15 mar-1" )])."

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
              if( !$cab_nom ){ $_ .= "
                <tr data-cab='rad'>
                  <th>
                    <span class='tex_ali-der'>Plasma</span>
                    <span class='tex_ali-cen'><c>/</c></span>                    
                    <span class='tex_ali-izq'>Héptada</span>
                  </th>";
                  foreach( Dat::_('hol.rad') as $_rad ){ $_ .= "
                    <th>
                      ".Doc_Val::ima('hol',"rad",$_rad,[])."
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
                  ".Doc_Val::ima('hol',"psi_hep_pla",$hep)."
                </td>";
                for( $rad=1; $rad<=7; $rad++ ){
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
      case 'ani_lun_tot':
        Ele::cla($ele['sec'],'hol-ton');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('ton',$var,$ele)
          ;
          $ele_pos = $ele['pos'];
          foreach( Dat::_('hol.psi_ani_lun') as $_lun ){
            $ele['pos'] = $ele_pos;
            Ele::cla($ele['pos'],"pos ide-".intval($_lun->ide)." bor-1 pad-1 bor-luz",'ini');
            $ele['pos']['htm'] = "            
            ".Doc_Val::tex($_lun->nom,['class'=>"tit tog mar_arr-0"])."
            ".Doc_Val::tex("$_lun->fec_ini - $_lun->fec_fin",['class'=>"des"])."
            ".Doc_Val::tex($_lun->tot,['class'=>"tog"])."
            ".Doc_Val::ima('hol','psi_ani_lun',$_lun)
            ;
            $_ .= Doc_Dat::tab_pos('hol',$est,$_lun,$var,$ele);
          } $_ .= "
        </ul>";
        break;
      // Castillo de 52 Heptadas:
      case 'hep':
        Ele::cla($ele['sec'],'hol-cas');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('cas',$var,$ele)."
          <li class='pos ide-0'>
            ".Doc_Val::ima('hol/tab/pla')."
          </li>";

          foreach( Dat::_('hol.cas') as $_cas ){
            $var['ide'] = $_cas->ide; $_ .= "
            <li class='pos ide-".intval($_cas->ide)."'>
              ".Sincronario::dat_tab('psi','hep_pla',$var,$ele)."
            </li>";            
          } $_ .= "
        </ul>";        
        break;
      // - 1 de 4 Estaciones Solares
      case 'hep_est':
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = Dat::_('hol.psi',$val['psi'])->$atr;        
        $_est = Dat::_('hol.psi_hep_est',$ide); 
        $cas = explode(' - ',$_est->hep_pla)[0];

        Ele::cla($ele['sec'],'hol-ton');
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          ".Sincronario::dat_tab_sec('ton',$var,$ele);

          foreach( Dat::_('hol.ton') as $_ton ){
            $var['ide'] = $cas; $_ .= "
            <li class='pos ide-".intval($_ton->ide)."'>
              ".Sincronario::dat_tab('psi','hep_pla',$var,$ele)."
            </li>";
            $cas++; 
          } $_ .= "
        </ul>";  
        break;
      // - 7 Plasmas Radiales
      case 'hep_pla':
        if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = Dat::_('hol.psi',$val['psi'])->$atr;        
        $_hep = Dat::_('hol.psi_hep_pla',$ide);
        $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
        
        if( !isset($ele['hep']) ) $ele['hep']=[];
        Ele::cla($ele['hep'],"dat_tab psi {$atr} hol-hep",'ini');
        $_ = "
        <ul".Ele::atr($ele['hep']).">";
          foreach( Dat::_('hol.rad') as $_rad ){
            $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);
            $psi++;
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    }
    return $_;
  }// Inicializo: cargo opciones
  static function dat_tab_var( string $est, string $atr, array &$var = [], array &$ele = [] ) : void {
    
    // pulsares por posicion
    $var['pos_pul_dim'] = isset($var['pos']['pul']) && ( empty($var['pos']['pul']) || in_array("dim",$var['pos']['pul']) );
    $var['pos_pul_mat'] = isset($var['pos']['pul']) && ( empty($var['pos']['pul']) || in_array("mat",$var['pos']['pul']) );
    $var['pos_pul_sim'] = isset($var['pos']['pul']) && ( empty($var['pos']['pul']) || in_array("sim",$var['pos']['pul']) );
    
    // pulsares por valor
    $var['pul_dim'] = isset($var['pul']['dim']);
    $var['pul_mat'] = isset($var['pul']['mat']);
    $var['pul_sim'] = isset($var['pul']['sim']);

  }// Seccion: onda encantada + castillo
  static function dat_tab_sec( string $tip, array $var=[], array $ele=[] ) : string {

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
      
      $_pul = [ 'dim'=>[], 'mat'=>[], 'sim'=>[] ];

      foreach( $_pul as $ide => $lis ){
        foreach( Dat::_("hol.ton_{$ide}") as $ton_pul ){
          $_pul[$ide] []= Doc_Val::ima("hol/tab/gal/$ide/{$ton_pul->ide}");
        }
      }

      if( isset($var['val']['pos']) ){

        $_val = $var['val']['pos'];

        if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || is_numeric($_val) ||( is_object($_val) && isset($_val->ide) ) ){

          if( is_numeric($_val) ){
            $_ton = Dat::_('hol.ton',Num::ran($_val,13));
          }else{
            $_ton = Dat::_('hol.ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
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
            if( !!$var["pos_pul_$ide"] && $_ton->$ide == $pos ) $cla_agr = "";
          }
          elseif( !!$var["pul_$ide"] && ( empty($var['pul'][$ide]) || in_array($pos,$var['pul'][$ide]) ) ){
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
      $orb_ocu = !empty($var['sec']['cas-orb']) ? '' : ' dis-ocu';
      $col_ocu = !empty($var['sec']['ond-col']) ? '' : ' fon-0';
      
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
              if( !!$var["pos_pul_$ide"] && $_ton->$ide == $pos ) $cla_agr = "";
            }
            elseif( !!$var["pul_$ide"] && ( empty($var['pul'][$ide]) || in_array($pos,$var['pul'][$ide]) ) ){
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
  static function dat_tab_pos( string $est, mixed $val, array &$var, array &$ele ) : string {
    $_ = "";    
    // opciones:
    if( !isset($var['kin_pag']) ) $var['kin_pag'] = !empty($var['pag']['kin']);
    if( !isset($var['psi_pag']) ) $var['psi_pag'] = !empty($var['pag']['psi']);
    if( !isset($var['sec_par']) ) $var['sec_par'] = !empty($var['sec']['par']);
    ///////////////////////////////////////////////////////////////////////////////////
    // armo titulos y cargo operadores ////////////////////////////////////////////////
    $cla_agr = [];
    $pos_tit = [];
    if( isset($ele["var-fec"]) ){
      $pos_tit []= "Calendario: {$ele["var-fec"]}";
    }
    if( isset($ele["hol-kin"]) ){
      $_kin = Dat::_('hol.kin',$ele["hol-kin"]);
      $pos_tit []= Doc_Dat::val('tit',"hol.kin",$_kin);
      if( $var['kin_pag'] && !empty($_kin->pag) ) $cla_agr []= "pag_kin";
    }
    if( isset($ele["hol-sel"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.sel",$ele["hol-sel"]);
    }
    if( isset($ele["hol-ton"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.ton",$ele["hol-ton"]);
    }
    if( isset($ele["hol-psi"]) ){
      $_psi = Dat::_('hol.psi',$ele["hol-psi"]);
      $pos_tit []= Doc_Dat::val('tit',"hol.psi",$_psi);          
      if( $var['psi_pag'] ){
        $_psi->kin = Dat::_('hol.kin',$_psi->kin);
        if( !empty($_psi->kin->pag) ) $cla_agr []= "pag_psi";
      }
    }
    if( isset($ele["hol-rad"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.rad",$ele["hol-rad"]);
    }
    // titulo
    if( !empty($pos_tit) ) $ele['title'] = implode("\n\n",$pos_tit);
    // operadores: .pos.dep + .pospag_kin + .pospag_psi
    if( !empty($cla_agr) ) Ele::cla($ele,$cla_agr);
    
    ///////////////////////////////////////////////////////////////////////////////////
    // modifico html por patrones: posicion por dependencia
    if( !!$var['sec_par'] ){

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

      // cambio evento
      if( isset($ele['onclick']) ){ 
        $par_ele['pos']['onclick'] = $ele['onclick'];
        unset($ele['onclick']);
      }      

      // cambio datos del operador
      if( isset($ele["var-fec"]) ){

        // cambio valor 
        if( $est == 'psi' && isset($ele["hol-kin"]) ){
          $par_est = "kin";
          $val = $ele["hol-kin"];
        }

        // cargo datos de la fecha por posicion del oraculo
        if( $par_est != $est ){

          $_kin = Dat::_('hol.kin',$val);
          
          $ele_par = [ 'ana'=>[], 'gui'=>[], 'ant'=>[], 'ocu'=>[] ];

          foreach( $ele_par as $par_ide => &$par_dat ){

            foreach( $var['dat'] as $var_dat ){

              if( $var_dat['hol-kin'] == $_kin->{"par_{$par_ide}"} ){

                $par_dat = $var_dat;
                break;
              }
            }
          }

          $ele_par['des'] = $ele;
          foreach( ['var-fec','hol-psi','hol-lun','hol-rad'] as $ele_dat ){
            $par_ele['par-des']["data-{$ele_dat}"] = $ele_par['des']["data-{$ele_dat}"];
            $par_ele['par-ana']["data-{$ele_dat}"] = $ele_par['ana']["{$ele_dat}"];
            $par_ele['par-gui']["data-{$ele_dat}"] = $ele_par['gui']["{$ele_dat}"];
            $par_ele['par-ant']["data-{$ele_dat}"] = $ele_par['ant']["{$ele_dat}"];
            $par_ele['par-ocu']["data-{$ele_dat}"] = $ele_par['ocu']["{$ele_dat}"];
          }
        }
      }

      Ele::cla($ele,'dep');      
      $_ = Sincronario::dat_tab($par_est,'par',[
        'ide' => $val,
        'dep' => 1,
        'sec' => [ 'par'=>$var['sec']['par'] - 1 ],// fuera de posicion principal
        'pos' => isset($var['pos']) ? $var['pos'] : [ 'ima'=>"hol.{$par_est}.ide" ]
      ],
        $par_ele
      );
    }

    return $_;
  }
  
}


