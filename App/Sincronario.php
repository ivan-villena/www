<?php

if( !class_exists("Usuario") ){

  require_once("./Api/sincronario/Usuario.php");
}

class Sincronario {
    
  // Ciclos-Hol: fec - sir - ani - lun - dia - kin - psi
  public array $Val = [];

  // busco valores por fecha del calendario
  static function dat( string $ide, mixed $val, mixed $var = NULL ) : mixed {
    $_ = FALSE;
    
    // proceso fecha del sincronario
    $Cic = $val;
    if( !is_array($Cic) || !isset($Cic['sir']) ) $Cic = Hol::val($val);

    switch( $ide ){
    // Ciclo Nuevo Sirio
    case 'sir':
      
      // calculo valores: codigo del ciclo, año inicial, año final, anillos, orbitas
      if( !is_object($_ = Dat::get('hol-psi_sir',[ 'ver'=>"`ide` = {$Cic['sir']}", 'opc'=>[ 'uni' ] ])) ){

        // valores iniciales
        $ani = $Cic['sir'] * 52;
        $año_fin = 1987 + $ani;
        
        $_ = new stdClass;
        $_->ide = $Cic['sir'];
        $_->año_ini = $año_fin - 52;
        $_->año_fin = $año_fin;
        $_->ani = $ani;
        $_->orb = intval($Cic['sir']);
      }

      break;
    // Anillo Solar
    case 'ani':
      $psi_ani = Dat::_('hol.psi_ani');
      // cargo 1 anillo
      if( empty($var) ){

        $_ = Dat::get( $psi_ani, [ 'ver'=>[ ["cod","==",$Cic['ani']] ], 'opc'=>[ 'uni' ] ]);

        // ajusto y reemplazo el año
        $_->año = $Cic['Fec']->año;
        if( $Cic['Fec']->mes <= 7 && $Cic['Fec']->dia <= 25 ){
          $_->año--;
        }

        // calculo fechas
        $_->fec_ini = "26/07/{$_->año}";
        $_->fec_fin = "24/07/".( $_->año + 1 );

      }
      // todos los anillos del ciclo
      elseif( $var == 'lis' ){

        // calculo año incial del ciclo
        $año = $Cic['Fec']->año - ( $Cic['ani'] + 1 );
        
        // ajusto año inicial por fecha específica
        if( $Cic['Fec']->mes <= 7 && $Cic['Fec']->dia <= 25 ){
          $año--;
        }

        // recorro y cargo anillos del ciclo siriano
        $_ = [];
        foreach( $psi_ani as $Ani ){
          
          // ajusto y reemplazo el año
          $Ani->año = $año;

          // calculo fechas
          $Ani->fec_ini = "26/07/{$Ani->año}";
          $Ani->fec_fin = "24/07/".( $Ani->año + 1 );

          $año++;
          $_ []= $Ani;
        }
      }
      break;
    // Orden Ciclico de 365 días
    case 'psi':
      $psi = $Cic['psi'];
      $_ = [
        'psi' => [ ],
        'ani' => [ ],
        'est' => [ ],
        'lun' => [ ],
        'hep' => [ ],
        'cro' => [ ],
        'vin' => [ ]
      ];      
      break;
    // Orden Sincrónico de 365 días
    case 'kin':
      $kin = $Cic['kin'];
      $_ = [
        'fec'     => [ ],
        'nav_cas' => [ ],
        'nav_ond' => [ ],
        'arm_tra' => [ ],
        'arm_cel' => [ ],
        'cro_est' => [ ],
        'cro_ele' => [ ]
      ];
      break;
    }

    return $_;
  }
  // Formularios
  static function dat_var( string $tip, mixed $val = NULL, array $var = [] ) : string {
    
    $_ = "";
    $_eje =  !empty($var['eje']) ? $var['eje'] : "Sincronario.dat('{$tip}',this);";

    switch( $tip ){
    // valor diario por fecha del calendario ( "aaa/mm/dd" ) o del sincronario ( sir.ani.lun.dia )
    case 'val':

      // proceso valores
      if( !isset($val) ){

        $val = Hol::val( date('Y/m/a') );
      }
      elseif( !is_array($val) ){

        $val = Hol::val( $val );
      }

      // proceso errores
      if( is_string($val) ){

        $_ = $val;

      }
      // imprimo formularios
      else{

        if( empty($var['opc']) || in_array('fec',$var['opc']) ){
          $_ .= "
          <!-- Fecha del Calendario -->
          <form class='-val mar-1' data-est='fec'>
      
            ".Doc_Val::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol-val-fec", 'class'=>"mar_hor-1", 
              'title'=>"Desde aquí puedes cambiar la fecha..." 
            ])."
            ".Doc_Var::fec('dia', $val['fec'], [ 'id'=>"hol-val-fec", 'name'=>"fec", 
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
  
        if( empty($var['opc']) || in_array('val',$var['opc']) ){
          $_ .= "
          <!-- Fecha del Sincronario -->
          <form class='-val mar-1' data-est='val'>
            
            <label>N<c>.</c>S<c>.</c></label>
      
            ".Doc_Var::num('int', $val['sir'], [ 
              'maxlength'=>2, 
              'name'=>"gal", 
              'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
            ])."
            <c>.</c>
            ".Doc_Val::opc( Dat::_('hol.psi_ani'), [
              'eti'=>[ 
                'name'=>"ani", 
                'class'=>"num", 
                'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 
                'val'=>$val['ani']+1
              ], 
              'ite'=>[ 'title'=>'($)kin_nom', 'htm'=>'($)cod' ]
            ])."
            <c>.</c>
            ".Doc_Val::opc( Dat::_('hol.psi_lun'), [
              'eti'=>[ 
                'name'=>"lun", 
                'class'=>"num", 
                'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 
                'val'=>$val['lun'] 
              ],
              'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
            ])."
            <c>.</c>
            ".Doc_Val::opc( Dat::_('hol.lun'), [ 
              'eti'=>[ 
                'name'=>"dia", 
                'class'=>"num", 
                'title'=>"Día Lunar : los 28 días del Giro Lunar...", 
                'val'=>$val['dia'] 
              ], 
              'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
            ])."          
            <c class='sep'>:</c>
        
            <n name='kin'>{$val['kin']}</n>
      
            ".Doc_Val::ico('ope_val-ini',[ 
              'eti'=>"button", 'type'=>"submit",
              'class'=>"mar_hor-1", 
              'onclick'=>"{$_eje}",
              'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
            ])."
      
          </form>";
        }
      }

      break;
    }
    return $_;
  }
  // Descripciones por Atributos
  static function dat_des( string $ide, mixed $val, array $var = [] ) : string {

    $_ = "";
    
    $_ide = explode('_',$ide);
    
    switch( $_ide[0] ){
    case 'kin':
      
      $Kin = !is_object($val) ? Dat::_('hol.kin',$val) : $val;

      $Sel = Dat::_('hol.sel',$Kin->arm_tra_dia);
  
      $Ton = Dat::_('hol.ton',$Kin->nav_ond_dia);

      if( empty($_ide[1]) ){
  
        $_ = Doc_Val::let($Kin->nom.": ")."<q>".Doc_Val::let("$Ton->des ".Tex::art_del($Sel->pod).", $Ton->acc_lec $Sel->car")."</q>";
      }
      else{
        switch( $_ide[1] ){
        }
      }
      break;
    }    

    return $_;    
  }
  // Informe por Estructura
  static function dat_inf( string $ide, mixed $val, array $var = [] ) : string {
    $_ = "";
    
    $_ide = explode('_',$ide);

    // cargo directorios
    $_libro    = SYS_NAV."sincronario/libro/";
    
    // fecha del ciclo
    $Fec = is_array($val) && isset($val['Fec']) ? $val['Fec'] : FALSE;
    // datos del ciclo
    $dat = isset($var['dat']) ? $var['dat'] : [];
    // opciones 
    $opc = isset($var['opc']) ? $var['opc'] : [];    
    // elementos
    $ele = isset($var['ele']) ? $var['ele'] : [];

    switch( $_ide[0] ){
    // orden ciclico
    case 'psi': 
      $Psi = Dat::_('hol.psi', is_array($val) && isset($val['psi']) ? $val['psi'] : $val );
      switch( $_ide[1] ){
      case 'sir': 
        break;
      case 'ani': 
        break;
      case 'lun': 
        break;
      case 'est': 
        break;
      case 'hep': 
        break;
      case 'cro': 
        break;
      case 'vin': 
        break;
      case 'kin':
        break;        
      }
      break;
    // orden sincrónico
    case 'kin':
      
      $Kin = Dat::_('hol.kin', is_array($val) && isset($val['kin']) ? $val['kin'] : $val );

      switch( $_ide[1] ){
      // Oráculo del Destino
      case 'par':

        $Sel = Dat::_('hol.sel',$Kin->arm_tra_dia);
        $Ton = Dat::_('hol.ton',$Kin->nav_ond_dia);
        // tablero + fichas por pareja
        $_ = "
          
        ".Sincronario::dat_tab("kin_par",[ 
          'ide'=>$Kin, 
          'pos'=>[ 'ima'=>"hol.kin.ide"  ] 
        ])."

        <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='{$_libro}encantamiento_del_sueño#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>

        <div class='lis'>";
          foreach( Dat::_('hol.sel_par') as $Par ){
            
            // salteo el destino
            if( ( $ide = $Par->cod ) == 'des' ) continue;
            
            // busco datos de parejas
            $Par = Dat::get( Dat::_('hol.sel_par'), [ 'ver'=>[ ['cod','==',$ide] ], 'opc'=>'uni' ]);
            
            $_ .= "
            <p class='tex'>
              <b class='ide tex-sub'>{$Par->nom}</b><c>:</c>
              <br>".Doc_Val::let($Par->des)."
              ".( !empty($Par->lec) ? "<br>".Doc_Val::let($Par->lec) : "" )."
            </p>
            
            ".Doc_Dat::fic('hol.kin',$Kin->{"par_{$ide}"});
  
          } 
          $_ .= "
        </div>";
        
        break;
      // Nave del Tiempo
      case 'nav':
        if( empty($_ide[2]) ){
        }else{
          switch( $_ide[2] ){
          case 'cas': 
            $_ = "
            <section class='dat_inf hol {$ide}'>";
              
              $Cas = Dat::_("hol.{$ide}",$Kin->nav_cas);

              $_ .= Doc_Dat::fic('hol.kin_nav_ond',$Cas->nav_ond);

              $_ .= "
            </section>";              
            break;
          case 'ond':
            $_ = "
            <section class='dat_inf hol {$ide}'>";
              
              $Ond = Dat::_("hol.{$ide}",$Kin->nav_ond);
              
              // recorro las posiciones y agrego la ficha de la aventura correspondiente
              $ond = 0;
              $ond_kin = explode('-',$Ond->kin);
              
              // buscar fechas del ciclo

              foreach( range( ...$ond_kin ) as $kin ){
  
                $Kin = Dat::_('hol.kin',$kin);
                $Ton = Dat::_('hol.ton',$Kin->nav_ond_dia);
                
                // posicion de la onda
                if( $ond != $Ton->ond ){
                  $ond = $Ton->ond;
                  $_ .= Doc_Dat::fic('hol.ton_ond',$Ton->ond);
                }
  
                // mandato
                $_ .= Doc_Dat::fic('hol.ton_ond_ave',$Kin->nav_ond_dia);
                $_ .= Doc_Dat::fic('hol.sel',$Kin->arm_tra_dia);
  
              }
              $_ .= "
            </section>";
            break;
          }
        }
      // Giro Galáctico
      case 'arm':
        if( empty($_ide[2]) ){
        }else{
          switch( $_ide[2] ){
          case 'tra': 
            $_ = "
            <section class='dat_inf hol {$ide}'>";
            
              $Tra = Dat::_("hol.{$ide}",$Kin->arm_tra);

              $_ .= Doc_Dat::fic('hol.kin_arm_cel',$Tra->cel);
              
              $_ .= "
            </section>";              
            break;
          case 'cel':
            $_ = "
            <section class='dat_inf hol {$ide}'>";
              
              $Cel = Dat::_("hol.{$ide}",$Kin->arm_cel);

              $_ .= Doc_Dat::fic('hol.kin',$Cel->kin);

              $_ .= "
            </section>";
            break;
          }
        }          
        break;
      // Giro Espectral
      case 'cro':
        if( empty($_ide[2]) ){
        }else{
          switch( $_ide[2] ){
          case 'est': 
            $_ = "
            <section class='dat_inf hol {$ide}'>";
            
              $Est = Dat::_("hol.{$ide}",$Kin->cro_est);

              $_ .= Doc_Dat::fic('hol.kin_cro_ele',$Est->ele);
              
              $_ .= "
            </section>";              
            break;
          case 'ele':
            $_ = "
            <section class='dat_inf hol {$ide}'>";
              
              $Ele = Dat::_("hol.{$ide}",$Kin->cro_ele);

              $_ .= Doc_Dat::fic('hol.kin',$Ele->kin);

              $_ .= "
            </section>";
            break;
          }
        }              
        break;
      }
      
      break;
    }
    return $_;
  }
  // Fichas y Cartas por Imágenes
  static function dat_fic( string $ide, array $var = [] ) : string {
    
    $_ = "";

    $opc = isset($var['opc']) ? $var['opc'] : [];
    $ele = isset($var['ele']) ? $var['ele'] : [];
    
    switch( $ide ){
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

            if( $ide > 9 ) $pos = Num::val($pos,2);
            
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
          $_ = Doc_Val::lis('bar', $_, $var);            
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
          $_ = Doc_Val::lis('bar', $_, $var);            
          break;
        }
      } 
      break;
    }
    
    return $_;
  }  
  // Cargo valores de ciclo desde fecha dada
  static function dat_val( string | object $fec, int $tot, int $ini = 0, int $inc = 1, string $ope = "+" ) : array {

    $_ = [];  
      
    // calculo fecha inicial ( para el kin 105, resto 105 días de la fecha recibida )
    if( $ini ) $fec = Fec::val_ope( $fec, $ini, '-');
    
    /* 
    $dat_ini = Hol::val($fec);    
    $kin = $dat_ini['kin'];
    $psi = $dat_ini['psi'];
    $fec = is_object($fec) ? $fec->val : $fec;
    */

    // recorro todo el ciclo    
    for( $pos = 1; $pos <= $tot; $pos++ ){

      // salteo el 29/02: no tiene ni kin, ni psicronos ( día hunab ku )
      if( preg_match("/^29-02/",$fec) ){

        $pos--;
      }
      else{
        
        // pido datos por fecha
        $dat = Hol::val($fec);        

        // cargo item en el operador de datos
        $_ []= Doc_Dat::val_var([
          'var'=>[ 
            'fec'=>$dat['Fec'],
          ],
          'hol'=>[
            'kin'=>Dat::_('hol.kin',$dat['kin']),
            'psi'=>Dat::_('hol.psi',$dat['psi'])
          ]
        ]);

        /* 
        $kin = Num::ran($kin + $inc, 260);
        $psi = Num::ran($psi + $inc, 365);
        */
      }

      // ajusto fecha
      $fec = Fec::val_ope($fec, $inc, $ope);
    }

    return $_;
  }// - sumatorias por valores
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
      // proceso opciones
      $est_opc = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      $atr_opc = [ 'res'=>[], 'orb'=>[], 'ele'=>[], 'cel'=>[], 'cir'=>[], 'pla'=>[] ];

      // Sistema Solar ( vertical : T.K. )
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          
          // imágenes: galaxia + sol
          foreach( ['gal'=>[ 'Galaxia' ],'sol'=>[ 'Sol' ] ] as $i=>$v ){
            $_ .= "
            <li class='sec ima {$i}'>".Doc_Val::ima("hol/tab/{$i}")."</li>";
          }

          // 2 respiraciones : x10 flechas
          $cla = ( !isset($est_opc['res']) || empty($est_opc['res']) ) ? " dis-ocu" : "";
          foreach( Dat::_('hol.sol_res') as $v ){ 
            for( $i = 1; $i <= 10; $i++ ){
              $_ .= "
              <li class='sec ima res-{$v->ide} ide-{$i}{$cla}'>".Doc_Val::ima('hol',"flu_res",$v)."</li>";
            }
          }
          // - 4 flujos de la respiracion : alfa <-> omega
          foreach( Dat::_('hol.flu') as $v ){ 
            $_ .= "
            <li class='sec ima res flu-{$v->ide} pod-{$v->pod}{$cla}'>".Doc_Val::ima('hol',"flu_pod",$v->pod)."</li>";
          }          
          
          // 2 grupos orbitales
          $cla = ( !isset($est_opc['orb']) || empty($est_opc['orb']) ) ? " dis-ocu" : "";
          foreach( Dat::_('hol.sol_orb') as $v ){ 
            $_ .= "
            <li class='sec bor orb-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_orb",$v)."'></li>";
          }

          // 4 elementos/clanes
          $cla = ( !isset($est_opc['ele']) || empty($est_opc['ele']) ) ? " dis-ocu" : "";
          foreach( Dat::_('hol.sel_cro_ele') as $v ){ 
            $_ .= "
            <li class='sec bor ele-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }

          // 5 células solares
          $cla = ( !isset($est_opc['cel']) || empty($est_opc['cel']) ) ? " dis-ocu" : "";
          foreach( Dat::_('hol.sol_cel') as $v ){ 
            $_ .= "
            <li class='sec bor cel-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_cel",$v)."'></li>";
          }

          // 5 circuitos de telepatía
          $cla = ( !isset($est_opc['cir']) || empty($est_opc['cir']) ) ? " dis-ocu" : "";
          foreach( Dat::_('hol.sol_cir') as $v ){ 
            $_ .= "
            <li class='sec bor cir-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_cir",$v)."'></li>";
          }

          // 10 planetas
          $cla = ( !isset($est_opc['pla']) || empty($est_opc['pla']) ) ? " dis-ocu" : "";
          foreach( Dat::_('hol.sol_pla') as $v ){ 
            $_ .= "
            <li class='sec bor pla-{$v->ide}{$cla}'></li>
            <li class='sec ima pla-{$v->ide}'>".Doc_Val::ima('hol',"sol_pla",$v)."</li>";
          }

          // posicion: 20 sellos solares
          $_ .= Sincronario::dat_tab_sec('sel',$var,$ele);

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
            $_ .= Sincronario::dat_tab_sec('sel',$var,$ele);

            $_ .= " 
          </ul>";            
          break;
        }        
      }      
      break;
    // holon planetario
    case 'pla':
      $est_opc = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      $_ = "
      <ul".Ele::atr($ele['sec']).">
        <li class='sec fon map'></li>
        <li class='sec fon sel'></li>";
        
        // fondos: flujos, elementos, razas
        foreach( ['res','fam','ele','raz'] as $ide ){ 
          $cla = ( !isset($est_opc[$ide]) || empty($est_opc[$ide]) ) ? " dis-ocu" : "";
          $_ .= "
          <li class='sec fon {$ide}{$cla}'></li>";
        }
        
        // 3 Hemisferios
        $cla = ( !isset($est_opc['hem']) || empty($est_opc['hem']) ) ? " dis-ocu" : "";
        foreach( Dat::_('hol.pla_hem') as $Dat ){
          $_ .= "
          <li class='sec bor hem-{$Dat->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_hem",$Dat)."'></li>";
        }          
        
        // 2 Meridianos
        $cla = ( !isset($est_opc['mer']) || empty($est_opc['mer']) ) ? " dis-ocu" : "";
        foreach( Dat::_('hol.pla_mer') as $Dat ){
          $_ .= "
          <li class='sec bor mer-{$Dat->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.sol_mer",$Dat)."'></li>";
          if( $Dat->ide == 1 ){ $_ .= "
            <li class='sec bor mer-{$Dat->ide}-0{$cla}' title='".Doc_Dat::val('tit',"hol.sol_mer",$Dat)."'></li>";
          }
        }
        
        // 5 Centros galácticos
        $cla = ( !isset($est_opc['cen']) || empty($est_opc['cen']) ) ? " dis-ocu" : "";
        foreach( Dat::_('hol.pla_cen') as $Dat ){
          $_ .= "
          <li class='sec ima cen-{$Dat->ide}{$cla}'>".Doc_Val::ima('hol',"sel_cro_fam",$Dat->fam)."</li>";
        }
        
        // Posicion: 20 sellos solares
        $_ .= Sincronario::dat_tab_sec('sel',$var,$ele);

        $_ .= "
      </ul>"; 
      break;
    // holon humano
    case 'hum':
      $est_opc = isset($var["est-{$_est[0]}"]) ? $var["est-{$_est[0]}"] : [];
      $_ = "
      <ul".Ele::atr($ele['sec']).">
        <li class='sec fon map'></li>";
        
        // 2 Lados del Cuerpo : Respiración del Holon
        $cla = ( !isset($est_opc['res']) || empty($est_opc['res']) ) ? " dis-ocu" : "";
        $_ .= "
        <li class='sec fon res{$cla}'></li>";

        foreach( Dat::_('hol.hum_res') as $v ){
          $_ .= "
          <li class='sec bor res-{$v->ide}{$cla}' title='".Doc_Dat::val('tit',"hol.hum_res",$v)."'></li>";
        }          
        
        // 5 Centros Galácticos : Familias Terrestres
        $cla = ( !isset($est_opc['cen']) || empty($est_opc['cen']) ) ? " dis-ocu" : "";
        $_ .= "
        <li class='sec fon cen{$cla}'></li>";

        foreach( Dat::_('hol.hum_cen') as $v ){
          $_ .= "
          <li class='sec ima cen-{$v->ide}{$cla}'>".Doc_Val::ima('hol',"hum_cen",$v)."</li>";
        }
        
        // 4 Extremidades : Clanes Cromáticos
        $cla = ( !isset($est_opc['ext']) || empty($est_opc['ext']) ) ? " dis-ocu" : "";
        foreach( Dat::_('hol.hum_ext') as $v ){
          $_ .= "
          <li class='sec bor ext-{$v->ide}{$cla}'></li>";
        }
        
        // 13 Articulaciones : Tonos por Trayectorias Armónicas
        $cla = ( !isset($est_opc['art']) || empty($est_opc['art']) ) ? " dis-ocu" : "";
        foreach( Dat::_('hol.ton') as $v ){ 
          $_ .= "
          <li class='sec ima ton-{$v->ide}{$cla}'>".Doc_Val::ima('hol',"kin_arm_tra",$v->ide)."</li>";
        }

        // Posicion: 20 sellos solares
        $_ .= Sincronario::dat_tab_sec('sel',$var,$ele);        

        $_ .= "
      </ul>"; 
      break;
    // plasma radial
    case 'rad':
      // heptagono
      if( !isset($_est[1]) ){
        Ele::cla($ele['sec'],"hep"); 
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
        // quántums
        case 'qua': break;
        // átomo telepático del tiempo
        case 'ato': break;        
        // heptágono de la mente
        case 'hep': break;
        // Chakras del humano
        case 'hum': break;
        }
      }      
      break;
    // tono galáctico
    case 'ton':
      // onda encantada
      if( !isset($_est[1]) ){
        $_ .= "
        <ul".Ele::atr($ele['sec']).">

          ".Sincronario::dat_tab_sec('ton',$var,$ele);

          foreach( Dat::_('hol.ton') as $Ton ){
            // cargo datos de la posicion
            $ele['pos']['hol-ton'] = $Ton->ide;
            $_ .= Doc_Dat::tab_pos('hol','ton',$Ton,$var,$ele);
          } 
          $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        // aventura de la onda
        case 'ond': break;
        // pulsar dimensional
        case 'dim': break;
        // pulsar matiz
        case 'mat': break;
        // simetria inversa
        case 'sim': break;
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
            // seleccion
            $agr = ( !!$val && $Sel->ide == $val ) ? ' _pos- _pos-bor' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='-val jus-cen'>
                ".Doc_Val::ima('hol',"sel",$Sel,['eti'=>"li"])."
                ".Doc_Val::ima('hol',"sel_cod",$Sel->cod,['eti'=>"li",'class'=>'tam-2'])."
              </ul>
              <p class='ali_pro-cen'>
                {$Sel->arm}
                <br>{$Sel->acc}
                <br>{$Sel->des_pod}
              </p>
            </li>";
          } 
          $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        // parejas del oráculo
        case 'par':

          if( empty($var['ide']) ) $var['ide'] = 1;

          $Sel = is_object($var['ide']) ? $var['ide'] : Dat::_('hol.sel',$var['ide']);

          Ele::cla($ele['sec'],"cro");
          $_ = "
          <ul".Ele::atr($ele['sec']).">";

            $ele_pos = $ele['pos'];
            foreach( Dat::_('hol.sel_par') as $Par ){
              
              // combino elementos
              $ele['pos'] = isset($ele["par-{$Par->cod}"]) ? Ele::val_jun($ele_pos,$ele["par-{$Par->cod}"]) : $ele_pos;
              Ele::cla($ele['pos'],"par-{$Par->cod}");            
              
              // todos menos el guia
              $par_sel = 0;
              if( $Par->cod != 'gui' ){ 
                
                $par_sel = ( $Par->cod == 'des' ) ? $Sel : Dat::_('hol.sel',$Sel->{"par_{$Par->cod}"}); 
              }
              $_ .= Doc_Dat::tab_pos('hol',$_est[0],$par_sel,$var,$ele);
            }
            $_ .= "
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
              
              for( $i=0; $i<=19; $i++ ){

                $_ .= Doc_Dat::tab_pos('hol',$_est[0],( $i == 0 ) ? 20 : $i,$var,$ele);
              } 
              $_ .= "
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

              <li class='pos ide-0'></li>";

              foreach( Dat::_('hol.sel_arm_cel') as $Cel ){ 
                $_ .= Doc_Val::ima('hol',"sel_arm_cel",$Cel,[ 'eti'=>"li", 'class'=>"sec cel-{$Cel->ide}"]);
              } 

              foreach( Dat::_('hol.sel_arm_raz') as $Raz ){ 
                $_ .= Doc_Val::ima('hol',"sel_arm_raz",$Raz,[ 'eti'=>"li", 'class'=>"sec raz-{$Raz->ide}"]);
              }
              
              foreach( Dat::_('hol.sel') as $Sel ){

                $_ .= Doc_Dat::tab_pos('hol',$_est[0],$Sel,$var,$ele);
              }
              $_ .= "
            </ul>";              
          }
          else{
            switch( $_est[2] ){
            // tablero del oráculo
            case 'tra':
              Ele::cla($ele['sec'],"cro");
              $_ .= "
              <ul".Ele::atr($ele['sec']).">";
                for( $i=1; $i<=5; $i++ ){
                  $var['ide'] = $i;            
                  $_ .= "
                  <li class='pos ide-{$i}'>
                    ".Sincronario::dat_tab('sel_arm_cel',$var,$ele)."
                  </li>";
                } $_ .= "
              </ul>";        
              break;      
            // célula del tiempo para el oráculo
            case 'cel':
              $Arm = Dat::_("hol.{$est}",$var['ide']);        
              
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
        $_ = "
        <ul".Ele::atr($ele['sec']).">";
          
          $ele_pos = $ele['pos'];
          foreach( Dat::_('hol.lun') as $Lun ){ 
            
            $ele['pos'] = $ele_pos;
            // cargo datos de la posicion
            $ele['pos']['hol-lun'] = $Lun->ide;
            $ele['pos']['hol-rad'] = $Lun->rad;
            Ele::cla($ele['pos'],"fon_col-4-".$Lun->arm." bor-1 bor-luz");
            
            $ele['pos']['htm'] = "
              ".Doc_Val::ima('hol','rad',$Lun->rad,[ 'class'=>"mar-1" ])."
              ".Doc_Val::num($Lun->ide,[ 'class'=>"tex-3" ])."
            ";

            $_ .= Doc_Dat::tab_pos('hol',$_est[0],$Lun,$var,$ele);
          }
          $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        // tubo por el que habla la tierra
        case 'tel': break;
        // heptágono de la mente
        case 'hep': break;
        // átomo telepático del tiempo
        case 'ato': break;
        }
      }      
      break;
    // castillo fractal
    case 'cas':
      // posiciones del castillo por cuadrantes armonicos
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">
          
          <li class='sec fon-ima'></li>          
          
          ".Sincronario::dat_tab_sec('cas',$var,$ele)."
          
          <li class='pos ide-0'></li>";

          foreach( Dat::_('hol.cas') as $Cas ){
            
            // cargo datos de la posicion
            $ele['pos']['hol-cas'] = $Cas->ide;
            $ele['pos']['hol-ton'] = $Cas->ton;

            $_ .= Doc_Dat::tab_pos('hol',$_est[0],$Cas,$var,$ele);
          } 
          $_ .= "
        </ul>";
      }
      else{
        switch( $_est[1] ){
        // cuadrante
        case 'arm': break;
        // aventura de la onda
        case 'ond': break;
        // pulsar dimensional
        case 'dim': break;
        // pulsar matiz
        case 'mat': break;
        // simetria inversa
        case 'sim': break;
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

        if( empty($ton_val) ) Ele::css($ele['sec'],"gri-template-rows: repeat(21,1fr)");

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
          <li".Ele::atr([ 'class'=>"sec fin".( empty($ton_val) && empty($arm_cel_val) ? "" : " dis-ocu" )])."></li>";          
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
          
          if( empty($var['ide']) ) $var['ide'] = 1;
          
          $Kin = Dat::_('hol.kin',$var['ide']);
          
          Ele::cla($ele['sec'],"cro");
          $_ = "
          <ul".Ele::atr($ele['sec']).">";

            $ele_pos = $ele['pos'];
            foreach( Dat::_('hol.sel_par') as $Par ){
              
              $par_kin = ( $Par->cod == 'des' ) ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$Par->cod}"});
              
              // combino elementos
              $ele['pos'] = isset($ele["par-{$Par->cod}"]) ? Ele::val_jun($ele_pos,$ele["par-{$Par->cod}"]) : $ele_pos;
  
              // agrego identificador de pareja
              $ele['pos']['hol-par'] = $Par->cod;
  
              $_ .= Doc_Dat::tab_pos('hol','kin',$par_kin,$var,$ele);
  
            }
            $_ .= "
          </ul>";

          break;
        // castillo del destino por familia terrestre
        case 'cas':          

          $Kin = is_array($val) && isset($val['kin']) ? Dat::_('hol.kin',$val['kin']) : FALSE;

          $Fam = Dat::_('hol.sel_cro_fam',$var['ide']);

          Ele::cla($ele['sec'],"cas ond");
          $_ = "
          <ul".Ele::atr($ele['sec']).">

            ".Sincronario::dat_tab_sec('cas',$var,$ele)."

            <li class='pos ide-0'>".Doc_Val::ima('hol','sel_cro_fam',$Fam)."</li>";
  
            $kin = $Kin && in_array('ini',$var['opc']) ? intval($Kin->ide) : intval($Fam->kin_ini);

            foreach( Dat::_('hol.cas') as $Cas ){
              
              $_ .= Doc_Dat::tab_pos('hol',$_est[0],$kin,$var,$ele);
              
              $kin = Num::ran($kin + 105, 260);
            }
            $_ .= "
          </ul>";

          break;
        // nave del tiempo : 5 castillos + 20 ondas
        case 'nav':
          // tablero del viaje
          if( !isset($_est[2]) ){
            
            Ele::cla($ele['sec'],"cro");
            $_ = "
            <ul".Ele::atr($ele['sec']).">";

              $ele['sec'] = isset($ele['cas']) ? $ele['cas'] : [];

              $est = 'kin_nav_cas';

              foreach( Dat::_("hol.{$est}") as $Cas ){

                if( $var['opc_gru'] ){
                  
                  $ele['pos']['htm'] =
                  Doc_Val::ima('hol',$est,$Cas,$ele['sec'])
                  ;
                  $_ .= Doc_Dat::tab_pos('hol',$est,$Cas,$var,$ele);
                }
                else{
                  $var['ide'] = $Cas->ide;
                  $_ .= "
                  <li class='pos ide-".intval($Cas->ide)."'>
                  
                    ".Sincronario::dat_tab($est,$var,$ele)."
                  </li>";
                }
              } 
              $_ .= "
            </ul>";            
          }
          // Castillo + Onda Encantada
          else{
            switch( $_est[2] ){
            // 1 castillo de 4 ondas y 52 kines
            case 'cas':

              $Cas = Dat::_("hol.{$est}",$var['ide']);
              
              // titulos
              $cas_tit = Doc_Dat::val('tit',"hol.{$est}",$Cas);
              foreach( range( ...explode('-',$Cas->nav_ond) ) as $ond ){
                
                $cas_tit .= "\n".Dat::_('hol.kin_nav_ond',$ond)->enc_des;
              }
              if( !isset($ele['sec']['title']) ) $ele['sec']['title'] = $cas_tit;

              Ele::cla($ele['sec'],"cas ond fon_col-5-{$var['ide']}".( empty($var['est-cas']['col']) ? ' fon-0' : '' ));
              $_ = "
              <ul".Ele::atr($ele['sec']).">
                
                ".Sincronario::dat_tab_sec('cas',$var,$ele)."
                
                <li class='pos ide-0'>".Doc_Val::ima('hol','kin_nav_cas',$Cas,[ 'title'=>$cas_tit ])."</li>";
                
                $kin = intval(explode('-',$Cas->kin)[0]);

                for( $pos = 1; $pos <= 52; $pos++ ){

                  $_ .= Doc_Dat::tab_pos('hol',$_est[0],$kin,$var,$ele);
                  
                  $kin++;
                } 
                $_ .= "
              </ul>";
              break;
            // 1 onda encantada de 13 kines
            case 'ond':

              $Ond = Dat::_("hol.{$est}",$var['ide']);
              // titulo        
              if( !isset($ele['sec']['title']) ) 
                $ele['sec']['title'] = Doc_Dat::val('tit',"hol.kin_nav_cas",$Ond->nav_cas)." .\n{$Ond->enc_des}"; 
              Ele::cla($ele['sec'],"ton ond");
              $_ = "
              <ul".Ele::atr($ele['sec']).">

                ".Sincronario::dat_tab_sec('ton',$var,$ele);
                      
                $kin = intval(explode('-',$Ond->kin)[0]);

                for( $pos = 1; $pos <= 13; $pos++ ){

                  $_ .= Doc_Dat::tab_pos('hol',$_est[0],$kin,$var,$ele);

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

            Ele::cla($ele['sec'],"ton".( $var['opc_gru'] ? " ond" : ""));
            $_ = "
            <ul".Ele::atr($ele['sec']).">

              ".Sincronario::dat_tab_sec('ton',$var,$ele);

              $est = 'kin_arm_tra';
    
              $ele['sec'] = isset($ele['tra']) ? $ele['tra'] : [];
              foreach( Dat::_("hol.{$est}") as $Tra ){ 

                if( $var['opc_gru'] ){
                  
                  $ele['pos']['htm'] =
                    Doc_Val::tex($Tra->nom)
                    .Doc_Val::tex($Tra->kin)
                    .Doc_Val::ima('hol',$est,$Tra,$ele['sec'])
                  ;

                  $_ .= Doc_Dat::tab_pos('hol',$est,$Tra,$var,$ele);                  
                }
                else{
                  $var['ide'] = $Tra->ide;
                  $_ .= "
                  <li class='pos ide-".intval($Tra->ide)."'>
                    ".Sincronario::dat_tab($est,$var,$ele)."
                  </li>";
                }
              } 
              $_ .= "
            </ul>";              
          }
          // Trayectoria + Célula
          else{
            switch( $_est[2] ){
            // 1 trayectoria de 5 armónicas y 20 kines
            case 'tra':
              
              $Tra = Dat::_("hol.{$est}",$var['ide']);
                    
              Ele::cla($ele['sec'],"cro");
              $_ = "
              <ul".Ele::atr($ele['sec']).">";

                $est = 'kin_arm_cel';
                $cel = intval(explode('-',$Tra->cel)[0]);

                $ele['sec'] = isset($ele['cel']) ? $ele['cel'] : [];
                for( $pos = 1; $pos <= 5; $pos++ ){

                  if( $var['opc_gru'] ){

                    $Cel = Dat::_("hol.{$est}",$cel);
                    
                    $ele['pos']['htm'] =
                    Doc_Val::ima('hol',$est,$Cel,$ele['sec']);

                    $_ .= Doc_Dat::tab_pos('hol',$est,$Cel,$var,$ele);
                  }
                  else{
                    $var['ide'] = $cel; 
                    $_ .= "
                    <li class='pos ide-{$pos}'>
                      ".Sincronario::dat_tab($est,$var,$ele)."
                    </li>";
                  }
                  $cel++;
                }                
                $_ .= "
              </ul>";

              break;
            // 1 célula del tiempo de 4 kines
            case 'cel':

              $Arm = Dat::_("hol.{$est}",$var['ide']);
                    
              Ele::cla($ele['sec'],"arm fon_col-5-{$Arm->cel} fon-0");
              $_ = "
              <ul".Ele::atr($ele['sec']).">

                <li class='pos ide-0 col-bla'>
                  ".Doc_Val::ima('hol',"sel_arm_cel",$Arm->cel,[ 'htm'=>$Arm->ide, 'title'=>Doc_Dat::val('tit',"hol.{$est}",$Arm) ])."
                </li>";
      
                $kin = intval(explode('-',$Arm->kin)[0]);

                for( $arm = 1; $arm <= 4; $arm++ ){

                  $_ .= Doc_Dat::tab_pos('hol',$_est[0],$kin,$var,$ele);
                  $kin++;
                } 
                $_ .= "
              </ul>";
              break;
            }
          }
          break;
        // cromáticas : 4 estaciones + 52 elementos
        case 'cro':
          // castillo del giro espectral
          if( !isset($_est[2]) ){

            Ele::cla($ele['sec'],"cas".( $var['opc_gru'] ? " ond" : ""));         
            $_ = "
            <ul".Ele::atr($ele['sec']).">

              ".Sincronario::dat_tab_sec('cas',$var,$ele)."
    
              <li class='pos ide-0'>".Doc_Val::ima('hol/tab/gal')."</li>";

              if( !in_array('fic_cas',$var['opc']) ) $var['opc'] []= 'fic_cas';

              $est = 'kin_cro_ele';
              $ele['sec'] = isset($ele['ele']) ? $ele['ele'] : [];
              
              foreach( Dat::_("hol.{$est}") as $Ele ){
                
                // por elementos
                if( $var['opc_gru'] ){

                  $ele['pos']['htm'] =
                  Doc_Val::ima('hol',$est,$Ele,$ele['sec'])
                  .Doc_Val::num($Ele->ide);

                  $_ .= Doc_Dat::tab_pos('hol',$est,$Ele,$var,$ele);
                }// por kines
                else{
                  $var['ide'] = $Ele->ide; 
                  $_ .= "
                  <li class='pos ide-".intval($Ele->ide)."'>
                    ".Sincronario::dat_tab($est,$var,$ele)."
                  </li>";
                }
              } 
              $_ .= "
            </ul>";
          }
          // Estación + Elemento
          else{
            switch( $_est[2] ){
            // 1 estacion espectral de 13 cromáticas y 65 kines
            case 'est':
              if( !in_array('fic_cas',$var['opc']) ) $var['opc'] []= 'fic_ond';

              $Est = Dat::_('hol.kin_cro_est',$var['ide']);
              
              Ele::cla($ele['sec'],"ton".( $var['opc_gru'] ? " ond" : ""));
              $_ = "
              <ul".Ele::atr($ele['sec']).">

                ".Sincronario::dat_tab_sec('ton',$var,$ele);

                $est = 'kin_cro_ele';
                $cas = intval(explode('-',$Est->cas)[0]);
                
                $ele['sec'] = isset($ele['ele']) ? $ele['ele'] : [];
                for( $pos = 1; $pos <= 13; $pos++ ){
                  // por elementos
                  if( $var['opc_gru'] ){
                    
                    $Ele = Dat::_("hol.{$est}",$cas);
                    
                    $ele['pos']['htm'] =
                    Doc_Val::ima('hol',$est,$Ele,$ele['sec'])
                    .Doc_Val::num($Ele->ide);
  
                    $_ .= Doc_Dat::tab_pos('hol',$est,$Ele,$var,$ele);
                  }// por kines
                  else{
                    $var['ide'] = $cas;
                    $_ .= "
                    <li class='pos ide-".intval($pos)."'>
                      ".Sincronario::dat_tab($est,$var,$ele)."
                    </li>";
                  }
                  $cas++;
                } 
                $_ .= "
              </ul>";
              break;
            // 1 elemento galáctico de 5 kines
            case 'ele':
              // del castillo | onda : rotaciones
              $ele_rot = [
                "ton" => [ "147", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160", "140", "070", "074", "071", "074", "330", "352", "335", "350", "230", "270", "240", "160" ],
                "cas" => [ "025", "000", "340", "345", "340", "250", "255", "250", "255", "155", "170", "160", "065", "290", "290", "290", "290", "220", "170", "160", "165", "160", "070", "075", "070", "335", "180", "150", "170", "160", "165", "070", "075", "070", "073", "330", "350", "340", "245", "095", "070", "075", "073", "073", "330", "350", "340", "345", "250", "255", "250", "160" ]
              ];

              if( in_array('fic_cas',$var['opc']) || in_array('fic_ond',$var['opc']) ){ 
                Ele::css($ele['sec'],
                  "transform: rotate(".(in_array('fic_cas',$var['opc']) ? $ele_rot['cas'][$var['ide']-1] : $ele_rot['ton'][$var['ide']-1])."deg)"
                );
              }

              $Ele = Dat::_("hol.{$est}",$var['ide']);

              Ele::cla($ele['sec'],"cro-cir");
              $ele['sec']['title'] = "{$Ele->ide}: {$Ele->nom}";
              $_ .= "
              <ul".Ele::atr($ele['sec']).">

                <li class='pos ide-0'>".Doc_Val::ima('hol','kin_cro_ele',$Ele->ide)."</li>";

                $kin = intval(explode('-',$Ele->kin)[0]);
                
                for( $pos = 1; $pos <= 5; $pos++ ){
      
                  $_ .= Doc_Dat::tab_pos('hol',$_est[0],$kin,$var,$ele);
                  
                  $kin++;
                  // por verdad eléctrica
                  if( $kin > 260 ) $kin = 1;
                }
                $_ .= "
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
      // Banco-psi
      if( !isset($_est[1]) ){
        $_ = "
        <ul".Ele::atr($ele['sec']).">";

          $ele['sec'] = isset($ele['tzo']) ? $ele['tzo'] : [];
          for( $pos = 1 ; $pos <= 8 ; $pos++ ){ 
            $_ .= "
            <li class='pos ide-{$pos}'>
              ".Sincronario::dat_tab('kin',$var,$ele)."
            </li>";
          } 
          $_ .= "
        </ul>";        
      }
      else{
        switch( $_est[1] ){
        // Castillo de 52 años
        case 'ani': 

          $ani_lis = [];
          // recibo listado de anillos
          if( isset($var['lis']) ){
    
            $ani_lis = $var['lis'];
          }
          // recibo anillo y cargo ciclos del sincronario
          else{
    
            if( !is_array($val) ) $val = Hol::val( !empty($val) ? $val : Fec::dat() );
    
            $ani_lis = Sincronario::dat('ani',$val,'lis');
          }

          // seleccion de posicion
          $val_pos = !empty($val['ani']) ? $val['ani'] : FALSE;
    
          Ele::cla($ele['sec'],"cas ond");
          $_ = "
          <ul".Ele::atr($ele['sec']).">
          
            ".Sincronario::dat_tab_sec('cas',$var,$ele)."
    
            <li class='pos ide-0'>
              ".Doc_Val::ima('hol/tab/pla')."
            </li>";
    
            foreach( $ani_lis as $Ani ){
              
              $_ .= "
              <li class='pos ide-".intval($Ani->ide).( $val_pos && $val_pos == $Ani->cod ? ' _pos- _pos-bor' : '' )."'>
    
                ".Doc_Val::ima('hol',"kin",Dat::_('hol.kin',$Ani->fam_4))."
    
                <time value='{$Ani->fec_año}-07-26'>{$Ani->fec_año}</time>
    
              </li>";
            } $_ .= "
          </ul>";             
          break;
        // Anillo Solar de 13 Lunas
        case 'lun':
          // Onda Encantada
          if( !isset($_est[2]) ){

            // muestro kin lunar y cargo datos del anillo
            if( ($opc_kin = in_array('kin',$var['opc'])) && is_array($val) && isset($val['ani']) ){
  
              // obtener el kin del anillo                  
              $Kin_ani = Dat::_('hol.kin',$val['ani_fam'][4]);
              
              // buscar la onda encantada de ese kin
              $Kin_ani_ond = Dat::_('hol.kin_nav_ond',$Kin_ani->nav_ond);
              $kin_lis = explode('-',$Kin_ani_ond->kin_lis);                              

              // recorrer kines de la onda para cada posicion
              $Kin = range($kin_lis[0],$kin_lis[1]);
            }            

            Ele::cla($ele['sec'],"ton".( $var['opc_gru'] ? " ond" : ""));
            $_ = "
            <ul".Ele::atr($ele['sec']).">";

              // por psi-cronos
              if( !$var['opc_gru'] ) { 
                
                $_ .= "
                ".Doc_Val::ima('hol/tab/sol',['eti'=>"li", 'class'=>"sec sol"])."
                ".Doc_Val::ima('hol/tab/pla',['eti'=>"li", 'class'=>"sec lun"]);

                if( !in_array("cab-nom",$var['opc']) ) $var['opc'] []= 'cab-nom';
              }
              // por tonos lunares
              else{

                // bordes y relleno en las posiciones
                Ele::cla($ele['pos'],"bor-1 pad-1 bor-luz");
              }

              $_ .= Sincronario::dat_tab_sec('ton',$var,$ele);

              $ele['sec'] = isset($ele['lun']) ? $ele['lun'] : [];
              foreach( Dat::_("hol.{$est}") as $ton => $Lun ){
                
                // por posiciones de tonos: kines o totems
                if( $var['opc_gru'] ){

                  // muestro kin lunar
                  if( $opc_kin && isset($Kin[$ton]) ){

                    $ele['pos']['htm'] = "
                    ".Doc_Val::tex("{$Lun->fec_ini} - {$Lun->fec_fin}",[ 'class'=>"des" ])."

                    ".Doc_Val::tex($Lun->tot,['class'=>"tog"])."

                    ".Doc_Val::ima('hol','kin',$Kin[$ton]);

                  }
                  // muestro totem
                  else{
                    $ele['pos']['htm'] = "

                    ".Doc_Val::tex($Lun->nom,[ 'class'=>"nom tog" ])."

                    ".Doc_Val::tex("{$Lun->fec_ini} - {$Lun->fec_fin}",[ 'class'=>"des" ])."

                    ".Doc_Val::tex($Lun->tot,[ 'class'=>"tog" ])."

                    ".Doc_Val::ima('hol','psi_lun',$Lun);
                  }

                  $_ .= Doc_Dat::tab_pos('hol',$est,$Lun,$var,$ele);

                }
                // por subtablero: lunas con psi-cronos
                else{
                  $var['ide'] = $Lun->ide;                 
                  $_ .= "
                  <li class='pos ide-{$Lun->ide}'>

                    ".Sincronario::dat_tab('psi_lun_dia',$var,$ele)."

                  </li>";
                }
              }
              $_ .= "
            </ul>";            
          }
          else{
            switch( $_est[2] ){
            // - Giro Lunar de 28 días
            case 'dia':
              // cargo elementos
              foreach( ['lun','cab','cab-ton','cab-rad','hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }          
              $ele['pos']['eti'] = 'td';
      
              Ele::cla($ele['sec'],"lun",'ini');
              $_ = "
              <table".Ele::atr($ele['sec']).">";

                // cargo objeto de la posicion
                $Lun = Dat::_('hol.psi_lun',$var['ide']);

                // cargo opciones
                $cab_nom = in_array('cab-nom',$var['opc']);
                $ver_ton = !empty($var['est-lun']['ton']);
                $ver_hep = !empty($var['est-lun']['hep']);
                $ver_rad = !empty($var['est-lun']['rad']);
                
                if( !$ver_ton && !$ver_rad ) Ele::cla($ele['cab'],"dis-ocu");
                $_ .= "
                <thead".Ele::atr($ele['cab']).">";
                  
                  // Tono y Totem Luna: por nombre o descripcion
                  $ele['cab-ton']['data-cab'] = "ton";
                  if( !$ver_ton ) Ele::cla($ele['cab-ton'],"dis-ocu");
                  $_ .= "
                  <tr".Ele::atr($ele['cab-ton']).">

                    <th colspan='".( $ver_hep ? "8" : "7" )."'>
      
                      <div class='-val tex_ali-izq' title='{$Lun->nom}: {$Lun->tot}'>
      
                        ".Doc_Val::ima('hol','psi_lun',$Lun,['class'=>( $cab_nom ? "tam-2 mar_der-1" : "tam-15 mar-1" )])."
      
                      ".( $cab_nom ? "

                        <p class='ide'>".str_replace(',','',explode(' ',$Lun->tot)[1])."</p>

                      " : "
                        <div>
                          <p class='tit tex-4'>
                            ".Doc_Val::let("{$var['ide']}° Luna: Tono ".explode(' ',$Lun->nom)[1])."
                          </p>
                          <p class='tex-3 mar-1'>
                            ".Doc_Val::let("{$Lun->ond_nom} ( $Lun->ond_pos ) : ".$Lun->ond_pod)."
                            <br>".Doc_Val::let($Lun->ond_man)."
                          </p>                   
                          <p class='tex-3 mar-1'>
                            ".Doc_Val::let("Totem: {$Lun->tot}")."                            
                            <br>".Doc_Val::let("Propiedades: {$Lun->tot_pro}")."
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
      
                    foreach( Dat::_('hol.rad') as $Rad ){ 

                      $_ .= "<th>".Doc_Val::ima('hol',"rad",$Rad)."</th>";
                    }
                    $_ .= "
                  </tr>";
                  $_ .="
                </thead>
      
                <tbody>";

                  $hep = ( ( intval($Lun->ide) - 1 ) * 4 ) + 1;
                  $psi = ( ( intval($Lun->ide) - 1 ) * 28 ) + 1;
                  
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
                      for( $pos = 1; $pos <= 7; $pos++ ){
      
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
            }
          }
          break;
        // Castillo de 4 estaciones de 52 Heptadas:
        case 'est':
          // Castillo 
          if( !isset($_est[2]) ){

            Ele::cla($ele['sec'],"cas".( $var['opc_gru'] ? " ond" : ""));
            $_ = "
            <ul".Ele::atr($ele['sec']).">

              ".Sincronario::dat_tab_sec('cas',$var,$ele)."
    
              <li class='pos ide-0'>".Doc_Val::ima('hol/tab/sol')."</li>";
    
              $ele['sec'] = isset($ele['hep']) ? $ele['hep'] : [];
              foreach( Dat::_('hol.cas') as $Cas ){

                if( $var['opc_gru'] ){

                  $ele['pos']['htm'] =
                  Doc_Val::ima('hol','psi_hep',$Cas->ide)
                  .Doc_Val::num($Cas->ide);

                  $_ .= Doc_Dat::tab_pos('hol','psi_hep',$Cas,$var,$ele);
                }
                else{

                  $var['ide'] = $Cas->ide;
                  $_ .= "
                  <li class='pos ide-".intval($Cas->ide)."'>
                    ".Sincronario::dat_tab('psi_hep',$var,$ele)."
                  </li>";
                }
              } 
              $_ .= "
            </ul>";
          }
          else{
            switch( $_est[2] ){
            // - Onda Encantada
            case 'dia':
              // por posiciones o por Héptadas
              Ele::cla($ele['sec'],"ton".( $var['opc_gru'] ? " ond" : ""));
              $_ = "
              <ul".Ele::atr($ele['sec']).">

                ".Sincronario::dat_tab_sec('ton',$var,$ele);

                $Dat = Dat::_('hol.psi_est',$var['ide']); 

                $cas = intval(explode('-',$Dat->hep)[0]);                
      
                $ele['sec'] = isset($ele['hep']) ? $ele['hep'] : [];
                foreach( Dat::_('hol.ton') as $Ton ){

                  if( $var['opc_gru'] ){
                    
                    $ele['pos']['htm'] =                         
                      Doc_Val::ima('hol','psi_hep',$cas)
                      .Doc_Val::num($Ton->ide);

                    $_ .= Doc_Dat::tab_pos('hol','psi_hep',$cas,$var,$ele);
                  }
                  else{
                    $var['ide'] = $cas;                                     
                    $_ .= "
                    <li class='pos ide-".intval($Ton->ide)."'>
                      ".Sincronario::dat_tab('psi_hep',$var,$ele)."
                    </li>";
                  }
                  $cas++; 
                } 
                $_ .= "
              </ul>";  
              break;
            }
          }        
          break;
        // Héptada de 7 Plasmas Radiales
        case 'hep':
          // heptágono o linea horizontal
          Ele::cla($ele['sec'], in_array('rad',$var['opc']) ? " val" : " hep");
          $_ = "
          <ul".Ele::atr($ele['sec']).">";

            $Dat = Dat::_("hol.{$est}",$var['ide']);
            
            $psi = intval(explode('-',$Dat->psi)[0]);

            for( $pos = 1; $pos <= 7; $pos++ ){

              $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);

              $psi++;

            } 
            $_ .= "
          </ul>";
          break;
        // Cromática Entonada de 5 kines
        case 'cro':
          // linea horizontal
          Ele::cla($ele['sec'],"val");
          $_ = "
          <ul".Ele::atr($ele['sec']).">";

            $Dat = Dat::_("hol.{$est}",$var['ide']);

            $psi = intval(explode('-',$Dat->psi)[0]);

            for( $pos = 1; $pos <= 5; $pos++ ){

              $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);

              $psi++;
            } 
            $_ .= "
          </ul>";
          break;
        // vinal del haab: 19 x 20 + 5 kin
        case 'vin':

          $Dat = Dat::_("hol.{$est}",$var['ide']);
          // grilla de 4 x 5
          $_ = "
          <ul".Ele::atr($ele['sec']).">";            

            $psi = intval(explode('-',$Dat->psi)[0]);

            $pos_fin = ( $var['ide'] == 19 ) ? 5 : 20;

            for( $pos = 1; $pos <= $pos_fin; $pos++ ){

              $_ .= Doc_Dat::tab_pos('hol','psi',$psi,$var,$ele);

              $psi++;
            }
            $_ .= "
          </ul>";
          break;
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
    }

    return $_;
  }// - inicializo parametros 
  static function dat_tab_var( string $est, array &$var = [], array &$ele = [] ) : void {    

    // parejas del oraculo
    if( $var['val_par'] = !empty($var['val-par']) ){

      Ele::cla($ele['sec'],'dep');
    }

    // portales:
    $var['atr_pag_kin'] = !empty($var['atr-pag']['kin']);
    $var['atr_pag_psi'] = !empty($var['atr-pag']['psi']);    
    
    // pulsares por posicion    
    $var['atr_pul_dim'] = isset($var['atr-pul']) && ( empty($var['atr-pul']) || !empty($var['atr-pul']['dim']) );
    $var['atr_pul_mat'] = isset($var['atr-pul']) && ( empty($var['atr-pul']) || !empty($var['atr-pul']['mat']) );
    $var['atr_pul_sim'] = isset($var['atr-pul']) && ( empty($var['atr-pul']) || !empty($var['atr-pul']['sim']) );
    $var['atr_pul']     = $var['atr_pul_dim'] || $var['atr_pul_mat'] || $var['atr_pul_sim'];

    // pulsares por valores
    $var['atr_dim'] = isset($var['atr-dim']);
    $var['atr_mat'] = isset($var['atr-mat']);
    $var['atr_sim'] = isset($var['atr-sim']);

    // proceso datos, agrupamientos y seleccion de valores por posicion
    if( $var['val']['pos'] != FALSE ){

      // Cargo estructura principal: kin - psi ...
      $est_ide = substr($est,0,3);      
      if( is_array($var['val']['pos']) && isset($var['val']['pos'][$est_ide]) ){

        $Dat = Dat::_("hol.{$est_ide}",$var['val']['pos'][$est_ide]);

        // cargo identificadores de subestructuras
        if( $var['ide'] == FALSE ){
          switch( $est ){
          // Orden Sincrónico
          case 'kin_par':     $var['ide'] = $Dat->ide;          break;
          case 'kin_cas':     $var['ide'] = $Dat->cro_ele_dia;  break;
          case 'kin_nav_cas': $var['ide'] = $Dat->nav_cas;      break;
          case 'kin_nav_ond': $var['ide'] = $Dat->nav_ond;      break;
          case 'kin_arm_tra': $var['ide'] = $Dat->arm_tra;      break;
          case 'kin_arm_cel': $var['ide'] = $Dat->arm_cel;      break;
          case 'kin_cro_est': $var['ide'] = $Dat->cro_est;      break;
          case 'kin_cro_ele': $var['ide'] = $Dat->cro_ele;      break;
          // Orden Cíclico
          case 'psi_lun_dia': $var['ide'] = $Dat->lun; break;
          case 'psi_est_dia': $var['ide'] = $Dat->est; break;
          case 'psi_hep':     $var['ide'] = $Dat->hep; break;
          case 'psi_cro':     $var['ide'] = $Dat->cro; break;
          case 'psi_vin':     $var['ide'] = $Dat->vin; break;
          }
        }        
      }

      // Cargo Valor Seleccionado por agrupamiento
      if( $var['opc_gru'] ){
      
        if( is_array($var['val']['pos']) ){

          switch( $est ){
          case 'psi_ani': $var['val_pos'] = $var['val']['pos']['ani'] + 1; break;
          case 'psi_lun': $var['val_pos'] = $Dat->lun; break;
          case 'psi_est': $var['val_pos'] = $Dat->hep; break;
          case 'kin_arm': $var['val_pos'] = $Dat->arm_tra; break;
          case 'kin_cro': $var['val_pos'] = $Dat->cro_ele; break;   
          }
        }
        // valor por indice
        else{
          $var['val_pos'] = $var['val']['pos'];
        }
      }
      // Cargo dato por ciclos
      elseif( is_array($var['val']['pos']) && !isset($var['dat']) && isset($var['val']['pos']['fec']) && isset($Dat) ){
        
        $Fec = $var['val']['pos']['Fec'];
        switch( $est ){
        // holones por sellos solares
        case 'sol':
        case 'pla':
        case 'hum':
          $var['dat'] = Sincronario::dat_val( $Fec, 20, ( $Dat->arm_tra_dia == 20 ? 1 : $Dat->arm_tra_dia - 1 ) );          
          break;
        // orden sincrónico
        case 'kin': 
        case 'kin_nav':
        case 'kin_arm':
        case 'kin_cro':
          $var['dat'] = Sincronario::dat_val( $Fec, 260, $Dat->ide ); break;
        case 'kin_cro_est': $var['dat'] = Sincronario::dat_val( $Fec, 61, $Dat->cro_est_dia ); break;          
        case 'kin_nav_cas': $var['dat'] = Sincronario::dat_val( $Fec, 52, $Dat->nav_cas_dia ); break;
        case 'kin_arm_tra': $var['dat'] = Sincronario::dat_val( $Fec, 20, $Dat->arm_tra_dia ); break;
        case 'kin_nav_ond': $var['dat'] = Sincronario::dat_val( $Fec, 13, $Dat->nav_ond_dia ); break;
        case 'kin_cro_ele': $var['dat'] = Sincronario::dat_val( $Fec, 5, $Dat->cro_ele_dia ); break;
        case 'kin_arm_cel': $var['dat'] = Sincronario::dat_val( $Fec, 4, $Dat->arm_cel_dia ); break;
        // orden cíclico
        case 'psi':         
          $var['dat'] = Sincronario::dat_val( $Fec, 260, $var['val']['pos']['kin'] ); break;
        case 'psi_est':
        case 'psi_lun':     
          $var['dat'] = Sincronario::dat_val( $Fec, 365, $Dat->ide ); break;
        case 'psi_est_dia': $var['dat'] = Sincronario::dat_val( $Fec, 91, $Dat->est_dia ); break;
        case 'psi_lun_dia': $var['dat'] = Sincronario::dat_val( $Fec, 28, $Dat->lun_dia ); break;
        case 'psi_vin':     $var['dat'] = Sincronario::dat_val( $Fec, 20, $Dat->vin_dia ); break;          
        case 'psi_hep':     $var['dat'] = Sincronario::dat_val( $Fec, 7, $Dat->hep_dia ); break;
        case 'psi_cro':     $var['dat'] = Sincronario::dat_val( $Fec, 5, $Dat->cro_dia ); break;
        }
      }

      // Cargo pulsares
      if( $var['atr_pul'] && !isset($var['val']['ton']) && isset($Dat) ){

        switch( $est ){
        case 'psi_ani': $var['val']['ton'] = $var['val']['pos']['ani'] + 1; break;
        case 'psi_lun': $var['val']['ton'] = $Dat->lun; break;
        case 'psi_est': $var['val']['ton'] = $Dat->hep; break;
        case 'kin_arm': $var['val']['ton'] = $Dat->arm_tra; break;
        case 'kin_cro':
        case 'kin_cro_est': 
          $var['val']['ton'] = $Dat->cro_ele;
          break;
        // posiciones
        default:          
          if( isset($var['val']['pos']['kin']) ){

            $var['val']['ton'] = Dat::_('hol.kin',$var['val']['pos']['kin'])->nav_ond_dia; 
          }
          elseif( isset($var['val']['pos']['psi']) ){

            $var['val']['ton'] = Dat::_('hol.kin', Dat::_('hol.psi',$var['val']['pos']['psi'])->kin )->nav_ond_dia; 
          }          
          break;
        }
      }

    }

  }// - Seccion: onda encantada + castillo
  static function dat_tab_sec( string $tip, array $var=[], array $ele=[] ) : string {

    $_ = "";
    
    $_tip = explode('_',$tip);  

    $ele_eti = $ele['pos']['eti'];

    // ondas encantadas: fondo + pulsares
    if( in_array($_tip[0],['ton','cas']) ){
    
      $Ton = FALSE;

      // fondos: imagen y color
      $ele_ite = isset($ele['fon-ima']) ? $ele['fon-ima'] : [];
      
      Ele::cla($ele_ite,"sec fon ima dis-ocu",'ini'); 
      $_ .= "
      <{$ele_eti}".Ele::atr($ele_ite).">
      </{$ele_eti}>";
  
      $ele_ite = isset($ele['fon-col']) ? $ele['fon-col'] : [];
      Ele::cla($ele_ite,"sec fon col dis-ocu",'ini'); 
      $_ .= "
      <{$ele_eti}".Ele::atr($ele_ite).">
      </{$ele_eti}>";      
      
      $Pulsares = [ 'dim'=>[], 'mat'=>[], 'sim'=>[] ];
      
      // cargo imágenes
      foreach( $Pulsares as $ide => &$lis ){
        
        foreach( Dat::_("hol.ton_{$ide}") as $ton_pul ){

          $lis []= Doc_Val::ima("hol/tab/gal/$ide/{$ton_pul->ide}");
        }
      }

      // cargo valor seleccionado
      if( isset($var['val']['ton']) ){

        $Ton = Dat::_('hol.ton', is_numeric($var['val']['ton']) ? Num::ran($var['val']['ton'],13) : $var['val']['ton']->ide );
      }      
      elseif( isset($var['val']['pos']) ){

        $val_pos = $var['val']['pos'];

        if( is_numeric($val_pos) || ( is_object($val_pos) && isset($val_pos->ide) ) ) {

          $Ton = Dat::_('hol.ton', is_numeric($val_pos) ? Num::ran($val_pos,13) : $val_pos->ide );
        }
      }      
      
      // cargo proceso
      $_pul_ite = function( $ide, $pul_lis, $var, $ele, $Ton, $cas_arm = 0 ){
        
        $_ = "";
        $ele_eti = $ele['pos']['eti'];
        
        // recorro pulsares
        foreach( $pul_lis as $pul_pos => $pul_ima ){

          $pul_ide = $pul_pos + 1;

          $ele_ite = isset($ele["pul-$ide"]) ? $ele["pul-$ide"] : [];
          $cla_agr = " dis-ocu";

          // cargo pulsar por posicion
          if( $var['atr_pul'] ){

            if( !!$Ton && $var["atr_pul_$ide"] && $Ton->{"{$ide}"} == $pul_ide ){

              $cla_agr = "";
            }
          }// cargo pulsares por valor
          elseif( $var["atr_{$ide}"] && ( empty($var["atr-{$ide}"]) || !empty($var["atr-{$ide}"][$pul_ide]) ) ){

            $cla_agr = "";
          }
          
          Ele::cla($ele_ite,"sec fon ond".( !empty($cas_arm) ? "-{$cas_arm}" : "" )." pul {$ide}-{$pul_ide}{$cla_agr}",'ini');
          $_ .= "
          <{$ele_eti}".Ele::atr($ele_ite).">{$pul_ima}</{$ele_eti}>";
        }

        return $_;
      };
    }

    switch( $_tip[0] ){
    // sellos: solar (planetas) + planetario (regiones) + humano (dedos)
    case 'sel':
      $val = $var['val']['pos'];
      $Kin = Dat::_("hol.kin",$val['kin']);        
      // por fechas : 20 kines 
      if( is_array($val) && isset($val['Fec']) ){

        // busco kin inicial          
        $kin = explode('-',Dat::_('hol.kin_arm_tra', $Kin->arm_tra)->kin)[0];

        Ele::cla($ele['pos'],"sel");
        
        // imprimo el sol anterior
        $_ .= Doc_Dat::tab_pos('hol','kin',Num::ran($kin - 1, 260),$var,$ele);

        // dragon - tormenta
        for( $sel = 1; $sel <= 19; $sel++ ){

          $_ .= Doc_Dat::tab_pos('hol','kin',$kin,$var,$ele);

          $kin = Num::ran($kin + 1,260);
        }
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
    // onda
    case 'ton':
      
      // cargo pulsares
      foreach( $Pulsares as $ide => $pul_lis ){ 

        $_ .= $_pul_ite($ide,$pul_lis,$var,$ele,$Ton);
      }
      break;
    // castillo
    case 'cas':

      // cargo ondas
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
      
      // cargo pulsares
      foreach( $Pulsares as $ide => $pul_lis ){ 
        
        // reocrro 4 ondas
        for( $i = 1; $i <= 4; $i++ ){
          
          $_ .= $_pul_ite($ide,$pul_lis,$var,$ele,$Ton,$i);
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
      if( $var['atr_pag_kin'] && !empty($Kin->pag) ) $cla_agr []= "pag-kin";
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
      if( $var['atr_pag_psi'] ){
        $Psi->kin = Dat::_('hol.kin',$Psi->kin);
        if( !empty($Psi->kin->pag) ) $cla_agr []= "pag-psi";
      }
    }
    if( isset($Ele["hol-rad"]) ){
      $pos_tit []= Doc_Dat::val('tit',"hol.rad",$Ele["hol-rad"]);
    }

    // titulo
    if( !empty($pos_tit) ) $Ele['title'] = implode("\n\n",$pos_tit);
    
    // operadores: .pos + .pag_kin + .pag_psi
    if( !empty($cla_agr) ) Ele::cla($Ele,$cla_agr);
    
    // modifico html por patrones: posicion por dependencia
    
    if( $var['val_par'] ){
      
      $par_est = $est;

      $par_ele = [ 
        'pos'=>[], // para todas las posiciones: bordes, color, imagen
        'par-des'=>[], // para el destino : posicion principal
        'ima'=> isset($ele['ima']) ? $ele['ima'] : []
      ];
      
      // cambio clases del operador
      if( !empty($Ele['class']) ){

        if( preg_match("/ope/",$Ele['class']) ){

          $par_ele['pos']['class'] = "ope";
          $Ele['class'] = str_replace("  ", " ", str_replace("ope","",$Ele['class']));
        }

        // cargo clases principales al destino
        $par_ele['par-des']['class'] = $Ele['class'];          
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
      
      $_ = Sincronario::dat_tab("{$par_est}_par",[
        'ide'     => $val,
        'pos_dep' => 1,// anulo operadores
        'val-par' => $var['val-par'] - 1,// sigo expandiendo
        'sec'     => isset($var['sec-dep']) ? $var['sec-dep'] : [],
        'pos'     => isset($var['pos']) ? $var['pos'] : [ 'ima'=>"hol.{$par_est}.ide" ]
      ],
        $par_ele
      );
    }

    return $_;
  }
}