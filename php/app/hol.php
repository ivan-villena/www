<?php

// Holon 
class app_hol {

  static string $IDE = "app_hol-";
  static string $EJE = "app_hol.";
  
  // operadores
  static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    switch( $tip ){
    }
    return $_;
  }

  // Valor : Fecha + ns:kin
  static function val( array $dat, array $ope ) : string {

    $_eje =  !empty($ope['eje']) ? $ope['eje'] : self::$EJE."val";

    $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : api_hol::_('kin',$dat['kin']) ) : [];
    $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : api_hol::_('psi',$dat['psi']) ) : [];
    $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
    $_fec = isset($dat['fec']) ? $dat['fec'] : [];      

    $_ = "
    <!-- Fecha del Calendario -->
    <form class='val fec mar-1'>

      ".app::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol_val-fec", 'class'=>"mar_hor-1", 
        'title'=>"Desde aquí puedes cambiar la fecha..." 
      ])."
      ".app_var::fec('dia', $_fec, [ 'id'=>"hol_val-fec", 'name'=>"fec", 
        'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
      ])."
      ".app::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);", 
        'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
      ])."

    </form>

    <!-- Fecha del Sincronario -->
    <form class='val sin mar-1'>
      
      <label>N<c>.</c>S<c>.</c></label>

      ".app_var::num('int', $_sin[0], [ 
        'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
      ])."
      <c>.</c>
      ".app_var::opc_val( api_hol::_('ani'), [
        'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
        'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
      ])."
      <c>.</c>
      ".app_var::opc_val( api_hol::_('psi_lun'), [
        'eti'=>[ 'name'=>"lun", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
        'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
      ])."
      <c>.</c>
      ".app_var::opc_val( api_hol::_('lun'), [ 
        'eti'=>[ 'name'=>"dia", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
        'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
      ])."          
      <c class='sep'>:</c>
  
      <n name='kin'>$_kin->ide</n>

      ".app::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);",
        'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
      ])."

    </form>";

    return $_;
  }

  // Ficha
  static function fic( string $est, string $atr, mixed $val, array $ope = [] ) : string {
    $_ = "";            
    switch( $est ){
    case 'kin':
      $dat = api_hol::_($est,$val);
      switch( $atr ){
      // parejas del oráculo
      case 'par':
        $_ ="
        <div class='lis'>";
        foreach( api_hol::_('sel_par') as $_par ){
          $ide = $_par->ide;
          $par_ide = "par_{$ide}";
          $atr_ide = ( $ide=='des' ) ? 'ide' : $par_ide;
          // busco datos de parejas
          $_par = api::dat(api_hol::_('sel_par'),[ 'ver'=>[ ['ide','==',$ide] ], 'opc'=>'uni' ]);
          $kin = api_hol::_('kin',$dat->$atr_ide);
          $_ .= "    
          <p class='mar_arr-2 tex_ali-izq'>
            <b class='ide let-sub'>{$_par->nom}</b><c>:</c>
            <br><q>".app::let($_par->des)."</q>
            ".( !empty($_par->lec) ? "<br><q>".app::let($_par->lec)."</q>" : "" )."
          </p>
          
          ".app_dat::inf('hol','kin',$kin,['cit'=>"des"])
          ;
  
        } $_ .= "
        </div>";
        break;
      // lecturas por parejas
      case 'par-lec':

        $_lis = [];
        $_des_sel = api_hol::_('sel',$dat->arm_tra_dia);

        foreach( api_hol::_('sel_par') as $_par ){

          if( $_par->ide == 'des' ) continue;

          $_kin = api_hol::_('kin',$dat->{"par_{$_par->ide}"});
          $_sel = api_hol::_('sel',$_kin->arm_tra_dia);

          $_lis []=
            api_hol::ima("kin",$_kin)."

            <div>
              <p><b class='tit'>{$_kin->nom}</b> <c>(</c> ".app::let($_par->dia)." <c>)</c></p>
              <p>".app::let("{$_sel->acc} {$_par->pod} {$_sel->car}, que {$_par->mis} {$_des_sel->car}, {$_par->acc} {$_sel->pod}.")."</p>
            </div>";
        }
        
        if( !isset($ope['lis']) ) $ope['lis']=[];

        api_ele::cla($ope['lis'],'ite');
        
        $_ = app_lis::val($_lis,$ope);          
        break;
      // Propiedades : palabras clave del kin + sello + tono
      case 'par-pro':

        $_par_atr = !empty($ope['par']) ? $ope['par'] : ['fun','acc','mis'];
  
        $_ton_atr = !empty($ope['ton']) ? $ope['ton'] : ['acc'];
  
        $_sel_atr = !empty($ope['sel']) ? $ope['sel'] : ['car','des'];
  
        foreach( api_hol::_('sel_par') as $_par ){
          
          $_kin = $_par->ide == 'des' ? $dat : api_hol::_('kin',$dat->{"par_{$_par->ide}"});
  
          $ite = [ api_hol::ima("kin",$_kin) ];
  
          foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= app::let($_par->$atr); }
  
          $_ton = api_hol::_('ton',$_kin->nav_ond_dia);
          foreach( $_ton_atr as $atr ){ if( isset($_ton->$atr) ) $ite []= app::let($_ton->$atr); }
  
          $_sel = api_hol::_('sel',$_kin->arm_tra_dia);            
          foreach( $_sel_atr as $atr ){  if( isset($_sel->$atr) ) $ite []= app::let($_sel->$atr); }
  
          $_ []= $ite;
        }
        break;
      // Ciclos : posiciones en ciclos del kin
      case 'par-cic':
        $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
  
        foreach( api_hol::_('sel_par') as $_par ){
          
          $_kin = $_par->ide == 'des' ? $dat : api_hol::_('kin',$dat->{"par_{$_par->ide}"});
  
          $ite = [ api_hol::ima("kin",$_kin) ];
  
          foreach( $_atr as $atr ){
            $ite []= api_hol::ima("kin_{$atr}",$_kin->$atr,[ 'class'=>"tam-5" ]);
          }
          
          $_ []= $ite;
        }
        break;
      // Grupos : sincronometría del holon por sellos
      case 'par-gru':
        $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];
  
        foreach( api_hol::_('sel_par') as $_par ){
          
          $_kin = $_par->ide == 'des' ? $dat : api_hol::_('kin',$dat->{"par_{$_par->ide}"});                            
  
          $_sel = api_hol::_('sel',$_kin->arm_tra_dia);
  
          $ite = [ api_hol::ima("kin",$_kin), $_par->nom, $_sel->pod ];
  
          foreach( $_atr as $atr ){
            $ite []= api_hol::ima("sel_{$atr}",$_sel->$atr,[ 'class'=>"tam-5" ]);
          }
          
          $_ []= $ite;
        }
        break;
        // castillo de la nave  
      }
      if( is_array($_) ) $_ = app_est::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
      break;
    }
    return $_;
  }
  
  // tablero
  static function tab( string $est, string $atr, array $ope = [], array $ele = [] ) : string {
    extract( app_hol::tab_dat($est,$atr,$ope,$ele) );
    $_ = "";
    switch( $tab ){
    case 'rad': 
      switch( $atr ){
      }
      break;
    case 'ton': 
      switch( $atr ){
      // onda encantada
      case 'ond': 
        $_tab = app_tab::dat('hol','ton')->ele;
        $_ .= "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">
          <li fon='ima'></li>
          ".app_hol::tab_sec('ton',$ope)
          ;
          $ele_pos = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
          
          foreach( api_hol::_('ton') as $_ton ){
            $ide = "pos-{$_ton->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $_ .= app_hol::tab_pos('ton',$_ton,$ope,$ele);
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
        <ul".api_ele::atr($ele['sec']).">";
          foreach( api_hol::_('sel') as $_sel ){ 
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
            $_ .= "
            <li class='sec{$agr}'>
              <ul class='val jus-cen'>
                ".api_hol::ima("sel",$_sel,['eti'=>"li"])."
                ".api_hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
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
        <ul".api_ele::atr($ele['sec']).">
          <li class='pos ide-0'></li>";
          foreach( api_hol::_('sel_cro_fam') as $_dep ){ 
            $_ .= api_hol::ima("sel_cro_fam",$_dep,['fam'=>$_dep->ide]);
          } 
          foreach( api_hol::_('sel_cro_ele') as $_dep ){ 
            $_ .= api_hol::ima("sel_cro_ele",$_dep,['ele'=>$_dep->ide]);
          }
          for( $i=0; $i<=19; $i++ ){ 
            $_sel = api_hol::_('sel', ( $i == 0 ) ? 20 : $i);
            $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_sel->ide)."{$agr}");                         
            $e['hol-sel'] = $_sel->ide; $_ .= "
            <li".api_ele::atr($e).">
              ".api_hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['_eje']) ? $ele['pos']['_eje'] : "" ])."
            </li>";
          } $_ .= "
        </ul>";        
        break;
      // colocacion armónica
      case 'arm':
        $_ .= "
        <ul".api_ele::atr($ele['sec']).">
          <li class='pos ide-0'></li>
          ";
          foreach( api_hol::_('sel_arm_cel') as $_dep ){ 
            $_ .= api_hol::ima("sel_arm_cel",$_dep,['cel'=>$_dep->ide]);
          } 
          foreach( api_hol::_('sel_arm_raz') as $_dep ){ 
            $_ .= api_hol::ima("sel_arm_raz",$_dep,['raz'=>$_dep->ide]);
          }
          foreach( api_hol::_('sel') as $_sel ){
            $agr = ( !empty($ide) && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_sel->ide)."{$agr}",'ini');             
            $e['sel'] = $_sel->ide; $_ .= "
            <li".api_ele::atr($e).">
              ".api_hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['onclick']) ? $ele['pos']['onclick'] : NULL ])."
            </li>";
          }
          $_ .= "
        </ul>";        
        break;
      // tablero del oráculo
      case 'arm_tra':
        $_ .= "
        <ul".api_ele::atr($ele['sec']).">";
          for( $i=1; $i<=5; $i++ ){
            $ope['ide'] = $i;
            $_ .= app_hol::tab('kin','arm_cel',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;      
      // célula del tiempo para el oráculo
      case 'arm_cel':
        $_arm = api_hol::_('sel_arm_cel',$ide);
        
        $ele['cel']['title'] = app_dat::val('tit',"hol.{$est}",$_arm); 
        api_ele::cla($ele['cel'],"app_tab sel {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr($ele['cel']).">
          <li class='pos ide-0'>".api_hol::ima("sel_arm_cel", $_arm, ['htm'=>$_arm->ide,'class'=>'ima'] )."</li>";
          foreach( api_hol::_('sel_arm_raz') as $_raz ){
            $_.= app_hol::tab_pos('sel',$sel,$ope,$ele);
            $sel++;
          } $_ .= "
        </ul>";        
        break;
                
      }
      break;
    case 'lun': 
      switch( $atr ){
      }
      break;
    case 'cas': 
      switch( $atr ){
      // x 4 ondas encantadas
      case 'ond': 
        $_tab = app_tab::dat('hol','cas')->ele;
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">
          <li fon='ima'></li>
          <li".api_ele::atr( isset($ele['pos-00']) ? api_ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></li>
          ".app_hol::tab_sec('cas',$ope)
          ;
          $ele_pos = $ele['pos'];
          foreach( api_hol::_('cas') as $_cas ){
            $ide = "pos-{$_cas->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$dei] : [] ]);
            $_ .= app_hol::tab_pos('cas',$_cas,$ope,$ele);
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
        $ele_ton=[ 'class'=> "sec -ton".( $ton_val ? "" : " dis-ocu" ) ];
        $sel_htm = isset($ope['sec']['kin-sel']);
        $sel_val = !empty($ope['sec']['kin-sel']);
        $ele_sel = [ 'class' => "sec -sel".( $sel_val ? "" : " dis-ocu" ) ];
        // ajusto grilla
        if( $ton_val ) api_ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");

        $_ = "
        <ul".api_ele::atr($ele['sec']).">";
          // 1° columna
          if( $ton_htm && $sel_htm ){ $_ .= "
            <li".api_ele::atr([ 'class' => "sec -ini".( $ton_val && $sel_val ? "" : " dis-ocu" )])."></li>";
          }
          // filas por sellos
          if( $sel_htm ){            
            foreach( api_hol::_('sel') as $_sel ){
              $ele_sel['data-hol_sel'] = $_sel->ide; $_ .= "
              <li".api_ele::atr($ele_sel).">".api_hol::ima("sel",$_sel)."</li>"; 
            }
          }
          // 260 kines por 13 columnas 
          $kin_arm = 0; 
          $ele_pos = $ele['pos']; 
          foreach( api_hol::_('kin') as $_kin ){
            // columnas por tono          
            $kin_arm_tra = intval($_kin->arm_tra);
            if( $ton_htm && $kin_arm != $kin_arm_tra ){
              $ele_ton['data-ide'] = $_kin->arm_tra; $_ .= "
              <li".api_ele::atr($ele_ton).">".api_hol::ima("kin_arm_tra",$_kin->arm_tra)."</li>";
              $kin_arm = $kin_arm_tra;
            }
            // posicion
            if( isset($ele["pos-{$_kin->ide}"]) ) $ele['pos'] = api_ele::jun( $ele["pos-{$_kin->ide}"], $ele['pos'] );
            $_ .= app_hol::tab_pos('kin',$_kin,$ope,$ele);
            $ele['pos'] = $ele_pos;
          } $_ .= "
        </ul>";        
        break;
      // oráculo del destino por tipo de pareja
      case 'par': 
        if( empty($ide) ) $ide = 1;
        $_tab = app_tab::dat('hol','cro')->ele;        
        $_kin = is_object($ide) ? $ide : api_hol::_('kin',$ide);   
        $ele_pos = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">";
          foreach( api_hol::_('sel_par') as $_par  ){
            $par_ide = $_par->ide;
            $par_kin = ( $par_ide == 'des' ) ? $_kin : api_hol::_('kin',$_kin->{"par_{$par_ide}"});
            // combino elementos :
            $ele['pos'] = api_ele::jun($_tab["pos-{$_par->pos}"], [ $ele_pos, isset($ele["pos-{$par_ide}"]) ? $ele["pos-{$par_ide}"] : [] ]);
            api_ele::cla($ele['pos'],"{$par_ide}",'ini');
            $_ .= app_hol::tab_pos('kin',$par_kin,$ope,$ele);
          }$_ .="
        </ul>";        
        break;
      // castillo del destino por familia terrestre
      case 'cas':
        $_tab = app_tab::dat('hol','cas')->ele;      
        $_fam = api_hol::_('sel_cro_fam',$ide);  
        $_fam_kin = [ 1 => 1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ];

        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">
          <li".api_ele::atr( isset($ele['pos-00']) ? api_ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></li>"
          .app_hol::tab_sec('cas',$ope)
          ;
          $ele_pos = $ele['pos'];
          $kin = intval($_fam['kin']);          
          foreach( api_hol::_('cas') as $_cas ){
            $_kin = api_hol::_('kin',$kin);
            $ide = "pos-{$_cas->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide], [ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $_ .= app_hol::tab_pos('kin',$kin,$ope,$ele);
            $kin = api_num::ran($kin + 105, 260);
          } $_ .= "
        </ul>";          
        break;
      // nave del tiempo : 5 castillos + 20 ondas
      case 'nav':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_tab = app_tab::dat('hol','cro')->ele;        
        $ele_cas = $ele['cas'];

        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">";
          foreach( api_hol::_('kin_nav_cas') as $cas => $_cas ){
            $ide = "pos-{$_cas->ide}";
            $ele['cas'] = api_ele::jun($_tab[$ide],[ $ele_cas, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $ope['ide'] = $_cas->ide;
            $_ .= app_hol::tab('kin','nav_cas',$ope,$ele);
          } $_ .= "
        </ul>";
        break;
      case 'nav_cas':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_cas = api_hol::_($est,$ide);        
        $_tab = app_tab::dat('hol','cas')->ele;
        
        $ini = ( ( $ide - 1 ) * 4 ) + 1;
        $ond_fin = $ini + 4;
        $ele['cas']['title'] = app_dat::val('tit',"hol.{$est}",$_cas);
        for( $ond = $ini; $ond < $ond_fin; $ond++ ){ 
          $_ond = api_hol::_('kin_nav_ond',$ond);
          $ele['cas']['title'] .= "\n".$_ond->enc_des;
        }
        api_ele::cla($ele['cas'],"app_tab kin {$atr} fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ),'ini');
        $_ = "
        <ul".api_ele::atr( api_ele::jun($_tab['sec'],$ele['cas']) ).">
          <li".api_ele::atr(api_ele::jun($_tab['pos-00'],[ [ 'class'=>"bor_col-5-{$ide} fon_col-5-{$ide}" ], isset($ele['pos-00']) ? $ele['pos-00'] : [] ])).">
            {$ide}
          </li>
          ".app_hol::tab_sec('cas',$ope);
          
          $kin = ( ( $ide - 1 ) * 52 ) + 1;
          $ele_pos = api_ele::jun($_tab['pos'],$ele['pos']);
          foreach( api_hol::_('cas') as $_cas ){ 
            $ide = "pos-{$_cas->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $_ .= app_hol::tab_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;
      case 'nav_ond':
        foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_ond = api_hol::_($est,$ide); 
        $_cas = api_hol::_('kin_nav_cas',$_ond->nav_cas);
        $_tab = app_tab::dat('hol','ton')->ele;

        $ele['ond']['title'] = app_dat::val('tit',"hol.kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
        api_ele::cla($ele['ond'],"app_tab kin {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['ond'])).">
          ".app_hol::tab_sec('ton',$ope)
          ;
          $kin = ( ( $ide - 1 ) * 13 ) + 1;
          $ele_pos = api_ele::jun($_tab['pos'],$ele['pos']);
          foreach( api_hol::_('ton') as $_ton ){
            $ide = "pos-{$_ton->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $_ .= app_hol::tab_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;      
      // armónicas : 13 trayectorias + 65 células
      case 'arm':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
        $_tab = app_tab::dat('hol','ton')->ele;

        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">
          ".app_hol::tab_sec('ton',$ope)
          ;
          $ele_tra = isset($_tab['pos']) ? api_ele::jun($ele['tra'],$_tab['pos']) : $ele['tra'];
          foreach( api_hol::_('kin_arm_tra') as $_tra ){ 
            $ide = "pos-{$_tra->ide}";
            $ele['tra'] = api_ele::jun($_tab[$ide],[ $ele_tra, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $ope['ide'] = $_tra->ide;
            $_ .= app_hol::tab('kin','arm_tra',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'arm_tra':
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        $_tra = api_hol::_('kin',$ide);
        $_tab = app_tab::dat('hol','cro')->ele;

        api_ele::cla($ele['tra'],"app_tab kin {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['tra'])).">";
          $cel_pos = 0;
          $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
          $cel_fin = $cel_ini + 5; 
          $ele_cel = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['cel']) : $ele['cel'];
          for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
            $cel_pos++;
            $ide = "pos-{$cel_pos}";
            $ele['cel'] = api_ele::jun($_tab[$ide],[ $ele_cel, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $ope['ide'] = $cel;
            $_ .= app_hol::tab('kin','arm_cel',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'arm_cel': 
        foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }         
        $_arm = api_hol::_($est,$ide);
        $_tab = app_tab::dat('hol','arm')->ele;

        $ele_cel = $ele['cel'];
        $ele_cel['title'] = app_dat::val('tit',"hol.{$est}",$_arm);
        api_ele::cla($ele_cel,"app_tab kin {$atr} fon_col-5-$_arm->cel fon-0");
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele_cel)).">";
          $ele_pos = api_ele::jun($_tab['pos-0'], isset($ele['pos-0']) ? $ele['pos-0'] : [] );
          api_ele::cla($ele_pos,"pos-0",'ini'); $_ .= "
          <li".api_ele::atr($ele_pos).">".api_hol::ima("sel_arm_cel", $_arm->cel, [ 
            'htm'=>"<n>$_arm->ide</n>", 'onclick'=>FALSE, 'style'=>"width: 100%; height: 100%;"
          ] )."</li>";
          $kin = ( ( $ide - 1 ) * 4 ) + 1;
          $ele_pos = $ele['pos'];
          for( $arm = 1; $arm <= 4; $arm++ ){
            $ide = "pos-{$arm}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $_ .= app_hol::tab_pos('kin',$kin,$ope,$ele);
            $kin++;
          } $_ .= "
        </ul>";        
        break;
      // cromáticas : 4 estaciones + 52 elementos
      case 'cro': 
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_cas';
        $_tab = app_tab::dat('hol','cas')->ele;

        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">
          <li".api_ele::atr( isset($ele['pos-00']) ? api_ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
            ".app::ima('hol/tab/gal')."
          </li>"
          .app_hol::tab_sec('cas',$ope)
          ;
          $ele_ele = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['ele']) : $ele['ele'];
          foreach( api_hol::_('kin_cro_ele') as $_cro ){                
            $ide = "pos-{$_cro->ide}";
            $ele['ele'] = api_ele::jun($_tab[$ide],[ $ele_ele, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $ope['ide'] = $_cro->ide;
            $_ .= app_hol::tab('kin','cro_ele',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      case 'cro_est':
        foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_ond';
        $_tab = app_tab::dat('hol','ton')->ele;

        api_ele::cla($ele['est'],"app_tab kin {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['est'])).">
          ".app_hol::tab_sec('ton',$ope)
          ;
          $_est = api_hol::_('kin_cro_est',$ide); 
          $cas = $_est->cas;
          $ele_ele = api_ele::jun($_tab['pos'],$ele['ele']);
          foreach( api_hol::_('ton') as $_ton ){                
            $ide = "pos-{$_ton->ide}";
            $ele['ele'] = api_ele::jun($_tab[$ide], [ $ele_ele, isset($ele[$ide]) ? $ele[$ide] : [] ]);                
            $ope['ide'] = $cas;
            $_ .= app_hol::tab('kin','cro_ele',$ope,$ele);
            $cas = api_num::ran($cas + 1, 52);
          } $_ .= "
        </ul>";
        break;
      case 'cro_ele':
        foreach(['ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
        $_tab = app_tab::dat('hol','cro_cir')->ele;
        $_ele = api_hol::_('kin_cro_ele',$ide);
        // cuenta de inicio
        $kin_ini = 185;
        $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";
        // del castillo | onda : rotaciones
        if( in_array('fic_cas',$opc) || in_array('fic_ond',$opc) ){ api_ele::css($ele['ele'],
          "transform: rotate(".(in_array('fic_cas',$opc) ? $ele['rot-cas'][$ide-1] : $ele['rot-ton'][$ide-1])."deg)");
        }
        api_ele::cla($ele['ele'],"app_tab kin {$atr}",'ini');
        $_ .= "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['ele'])).">
          <li".api_ele::atr(api_ele::jun($_tab['pos-0'],isset($ele['pos-0']) ? $ele['pos-0'] : [])).">
            ".app::ima("hol/fic/uni/col/".api_num::ran($_ele->ide,4), [ 'htm'=>$_ele->ide, 'class'=>"alt-100 anc-100" ])."
          </li>";

          $kin = api_num::ran($kin_ini + ( ( $ide - 1 ) * 5 ), 260);
          $ele_pos = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
          foreach( api_hol::_('sel_cro_fam') as $cro_fam ){
            $ide = "pos-{$cro_fam->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $_ .= app_hol::tab_pos('kin',$kin,$ope,$ele);
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
        $_tab = app_tab::dat('hol','ton')->ele;
        api_ele::css($ele['sec'],"grid-gap:.1rem");
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">          
          ".app::ima('hol/tab/sol',['eti'=>"li", 'fic'=>"uni-sol", 
            'style'=>"width:100%; max-width:80px; height:100%; max-height:80px; grid-row:1; grid-column:2; justify-self:end; align-self:end;" 
          ])."
          ".app::ima('hol/tab/pla',['eti'=>"li", 'fic'=>"uni-lun", 
            'style'=>"width:100%; max-width:60px; height:100%; max-height:60px; grid-row:3; grid-column:3; align-self:center; justify-self:center;" 
          ])."
          ".app_hol::tab_sec('ton',$ope)
          ;
          if( !in_array('cab_nom',$ope['opc']) ) $ope['opc'] []= 'cab_nom';
          $ele_lun = $_tab['pos'];
          foreach( api_hol::_('psi_lun') as $_lun ){
            $ide = "pos-{$_lun->ide}";
            $ele_pos = api_ele::jun($_tab[$ide],[$ele_lun, isset($ele["lun-{$ide}"]) ? $ele["lun-{$ide}"] : [] ]);
            $ope['ide'] = $_lun->ide;
            $_ .= "
            <li".api_ele::atr($ele_pos).">".app_hol::tab('psi','lun',$ope,$ele)."</li>";
          } $_ .= "
        </ul>";        
        break;
      // anillos solares por ciclo de sirio
      case 'ani': 
        $_tab = app_tab::dat('hol','cas_cir')->ele;
        $ope['sec']['orb_cir'] = '1';
        $kin = 34;

        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['sec'])).">
  
          ".app_hol::tab_sec('cas_cir',$ope)
          ;
          foreach( api_hol::_('cas') as $_cas ){
            $_kin = api_hol::_('kin',$kin);
            $agr = '';
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_cas->ide)."{$agr}",'ini');
            $e['data-hol_kin'] = $_kin->ide;
            $e['data-hol_ton'] = $_cas['ton'];
            $_ .= "
            <li".api_ele::atr($e).">".api_hol::ima("kin",$_kin,[ 'onclick'=>isset($e['_eje'])?$e['_eje']:NULL ])."</li>";
            $kin += 105; if( $kin >260 ) $kin -= 260;
          } $_ .= "
        </ul>";        
        break;
      // estaciones de 91 días
      case 'est':
        foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        $_tab = app_tab::dat('hol','cas')->ele;

        $_ = "
        <ul".api_ele::atr( api_ele::jun($_tab['sec'],$ele['sec']) ).">
          <li".api_ele::atr( isset($ele['pos-00']) ? api_ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
            ".app::ima('hol/tab/pla',[])."
          </li>
          ".app_hol::tab_sec('cas',$ope)
          ; 
          $ele_hep = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['hep']) : $ele['hep'];
          foreach( api_hol::_('cas') as $_cas ){
            $ide = "hep-{$_cas->ide}";
            $ele['hep'] = api_ele::jun($_tab["pos-{$_cas->ide}"],[ $ele_hep, isset($ele[$ide]) ? $ele[$ide] : [] ]);
            $ope['ide'] = $_cas->ide;
            $_ .= app_hol::tab('psi','hep',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      // luna de 28 días
      case 'lun':
        foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
        if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = api_hol::_('psi',$val['psi'])->lun;
        $_lun = api_hol::_($est,$ide);
        $_ton = api_hol::_('ton',$ide);
        $_tab = app_tab::dat('hol','lun')->ele;
        $cab_ocu = in_array('cab_ocu',$opc);
        $cab_nom = in_array('cab_nom',$opc);        

        api_ele::cla($ele['lun'],"app_tab psi {$atr}",'ini');
        $_ = "
        <table".api_ele::atr($ele['lun']).">";
          if( !$cab_ocu ){ $_ .= "
            <thead>
              <tr data-cab='ton'>
                <th colspan='8'>
                  <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>

                    ".api_hol::ima("{$est}",$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-16 mar-1" )])."

                    ".( $cab_nom ? "
                      <p><n>{$ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."</p>                      
                    " : "
                      <div>
                        <p class='tit let-4'>
                          <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                        </p>
                        <p class='let-3 mar-1'>
                          ".app::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                          <br>".app::let($_lun->ond_man)."
                        </p>                   
                        <p class='let-3 mar-1'>
                          Totem<c>:</c> $_lun->tot
                          <br>Propiedades<c>:</c> ".app::let($_lun->tot_pro)."
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
                  foreach( api_hol::_('rad') as $_rad ){ $_ .= "
                    <th>".api_hol::ima("rad",$_rad,[])."</th>";
                  }$_ .= "                  
                </tr>";
              }$_ .="
            </thead>";
          }
          $dia = 1;    
          $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
          $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
          $ele_pos = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
          $ope['eti']='td'; $_ .= "
          <tbody>";
          for( $arm = 1; $arm <= 4; $arm++ ){
            $_ .= "
            <tr class='ite-$arm'>
              <td".api_ele::atr(api_ele::jun([ 'data-arm'=>$arm, 'data-hep'=>$hep, 'class'=>"sec -hep fon_col-4-{$arm}" ], 
                  isset($ele[$ide = "hep-{$arm}"]) ? $ele[$ide] : []
                )).">";
                if( $cab_ocu || $cab_nom ){
                  $_ .= "<n>$hep</n>";
                }else{
                  $_ .= api_hol::ima("psi_hep",$hep,[]);
                }$_ .= "
              </td>";
              for( $rad=1; $rad<=7; $rad++ ){
                $_dia = api_hol::_('lun',$dia);
                $ide = "pos-{$_dia->ide}";
                $ele['pos'] = api_ele::jun($ele_pos, isset($ele[$ide]) ? $ele[$ide] : []);
                $_ .= app_hol::tab_pos('psi',$psi,$ope,$ele);
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
        if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = api_hol::_('psi',$val['psi'])->hep;     
        $_tab = app_tab::dat('hol','rad')->ele;
        $_hep = api_hol::_('psi_hep',$ide);

        api_ele::cla($ele['hep'],"app_tab psi {$atr}",'ini');
        $_ = "
        <ul".api_ele::atr(api_ele::jun($_tab['sec'],$ele['hep'])).">";

          $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
          $ele_pos = isset($_tab['pos']) ? api_ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'] ;
          foreach( api_hol::_('rad') as $_rad ){
            $ide = "pos-{$_rad->ide}";
            $ele['pos'] = api_ele::jun($_tab[$ide],[ $ele_pos, isset($ele["rad-{$ide}"]) ? $ele["rad-{$ide}"] : [] ]);
            $_ .= app_hol::tab_pos('psi',$psi,$ope,$ele);
            $psi++;
          } $_ .= "
        </ul>";        
        break;
      // banco-psi de 8 tzolkin con psi-cronos
      case 'tzo': 
        $_ = "
        <ul".api_ele::atr($ele['sec']).">";

          $ele_tzo = $ele['sec'];
          for( $i=1 ; $i<=8 ; $i++ ){
            $ele['sec'] = $ele_tzo;
            $ele['sec']['pos'] = $i;
            if( isset($ele["tzo-$i"]) ) $ele['sec'] = api_ele::jun($ele['sec'],$ele["tzo-$i"]);
            $_ .= app_hol::tab('kin','tzo',$ope,$ele);
          } $_ .= "
        </ul>";        
        break;
      }
      break;
    case 'ani': 
      switch( $atr ){
      // telektonon
      case 'tel': 
        $ocu = []; 
        foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
  
        $_ = "
        <ul".api_ele::atr($ele['sec']).">"
          .app::ima("hol/gal",['eti'=>"li",'fic'=>'gal','title'=>'Fin de la Exhalación Solar. Comienzo de la Inhalación Galáctica.'])
          .app::ima("hol/sol",['eti'=>"li",'fic'=>'sol','title'=>'Fin de la Inhalación Galáctica. Comienzo de la Exhalación Solar.']);
          foreach( api_hol::_('sel_pol_flu') as $v ){ 
            $_ .= api_hol::ima("sel_pol_flu",$v,[ 'eti'=>"li",
              'flu'=>$v['ide'],'class'=>$ocu['res'],'title'=> app_dat::val('tit',"hol.sel_pol_flu",$v)
            ]); 
          }
          foreach( api_hol::_('uni_sol_res') as $v ){ 
            $_ .= api_hol::ima("uni_sol_res",$v,[ 'eti'=>"li",
              'res'=>$v['ide'],'class'=>$ocu['res'],'title'=> app_dat::val('tit',"hol.res_flu",$v)
            ]);
          }          
          foreach( api_hol::_('uni_sol_pla') as $v ){
            $_ .= api_hol::ima("sol_pla",$v,[ 'eti'=>"li",
              'pla'=>$v['ide'],'class'=>$ocu['pla'],'title'=> app_dat::val('tit',"hol.sol_pla",$v)
            ]);
          }
          foreach( api_hol::_('sel_cro_ele') as $v ){ $_ .= "
            <li ele='{$v['ide']}' class='{$ocu['cla']}' title='".app_dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
          }
          foreach( api_hol::_('uni_sol_cel') as $v ){ $_ .= "
            <li cel='{$v['ide']}' class='{$ocu['cel']}' title='".app_dat::val('tit',"hol.sol_cel",$v)."'></li>";
          }
          foreach( api_hol::_('uni_sol_cir') as $v ){ $_ .= "
            <li cir='{$v['ide']}' class='{$ocu['cir']}' title='".app_dat::val('tit',"hol.sol_cir",$v)."'></li>";
          }
          foreach( api_hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_sel->ide)."{$ocu['sel']}",'ini'); 
            $_ .= "
            <li".api_ele::atr($e).">".api_hol::ima("sel_cod",$_sel)."</li>";
          }
          $_ .= " 
        </ul>";        
        break;
      // holon solar por flujo vertical
      case 'sol': 
        $ocu = []; 
        foreach( ['cel','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li fon='map'></li>
          <li fon='ato'></li>";
          // circuitos
          foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $_ .= "
            <li fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></li>";  
          }
          foreach( api_hol::_('uni_sol_pla') as $v ){ $_ .= "
            <li pla='{$v->ide}' class='{$ocu['pla']}' title='".app_dat::val('tit',"{$esq}.sol_pla",$v)."'></li>";
          }
          foreach( api_hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_sel->ide),'ini'); $_ .= "
            <li".api_ele::atr($e).">".api_hol::ima("sel_cod",$_sel->cod)."</li>";
          }
          $_ .= " 
        </ul>";        
        break;
      // holon planetario
      case 'pla':
        $ocu = [];
        foreach( ['fam'] as $i ) $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li fon='map'></li>";
          foreach( ['res','flu','cen','sel'] as $i ){ $_ .= "
            <li fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></li>";
          }
          foreach( api_hol::_('sel_cro_fam') as $_dat ){
            $_.=api_hol::ima("sel_cro_fam",$_dat,[ 'fam'=>$_dat->ide, 'class'=>$ocu['fam'] ]);
          }
          foreach( api_hol::_('sel') as $_sel ){
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_sel->ide).'ini'); $_ .= "
            <li".api_ele::atr($e).">".api_hol::ima("sel",$_sel)."</li>";
          }
          $_ .= "
        </ul>";        
        break;
      // holon humano
      case 'hum':
        $ocu = []; 
        foreach( ['ext','cen'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
        $_ = "
        <ul".api_ele::atr($ele['sec']).">
          <li fon='map'></li>";
          foreach( ['res','ext','cir','cen'] as $ide ){ $_ .= "
            <li fon='$ide' class='".isset($ope['sec'][$ide]) ? '' : ' dis-ocu'."'></li>";
          }
          foreach( api_hol::_('sel_cro_ele') as $_dat ){ 
            $_ .= api_hol::ima("sel_cro_ele",$_dat,['ele'=>$_dat->ide,'class'=>$ocu['ext']]);
          }
          foreach( api_hol::_('sel_cro_fam') as $_dat ){ 
            $_ .= api_hol::ima("sel_cro_fam",$_dat,['fam'=>$_dat->ide,'class'=>$ocu['cen']]);
          }
          foreach( api_hol::_('sel') as $_dat ){
            $e = $ele['pos'];
            api_ele::cla($e,"pos-".intval($_sel->ide),'ini'); $_ .= "
            <li".api_ele::atr($e).">".api_hol::ima("sel",$_dat)."</li>";
          }
          $_ .= "
        </ul>";        
        break;                
      }
      break;
    }
    return $_;
  }
  // armo tablero
  static function tab_dat( string $est, string $atr, array $ope = [], array $ele = [] ) : array {

    $_ = [ 'esq'=>"hol", 'tab'=>$est, 'ide'=>$est, 'est'=> $est = $est.( !empty($atr) ? "_$atr" : $atr ) ];
    
    // cargo elementos del tablero
    $ele = app_tab::dat('hol',$est,$ele);
    foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
    if( empty($ele['sec']['class']) || !preg_match("/app_tab/",$ele['sec']['class']) ) api_ele::cla($ele['sec'],
      "app_tab {$_['ide']}_{$atr}",'ini'
    );
    // opciones
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc = $ope['opc'];      
    // contador de posiciones
    $ope['_tab_pos'] = 0;
    // items
    if( !isset($ope['eti']) ) $ope['eti'] = "li";
    // operador de opciones
    if( !empty($ope['pos']['bor']) ) api_ele::cla($ele['pos'],"bor-1");      
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

  }
  // Seccion: onda encantada + castillo => fondos + pulsares + orbitales
  static function tab_sec( string $tip, array $ope=[], array $ele=[] ) : string {
    $_ = "";
    $_tip = explode('_',$tip);
    $_tab = app_tab::dat('hol',$_tip[0])->ele;
    $_pul = ['dim'=>'','mat'=>'','sim'=>''];
    // opciones por seccion
    $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : 'dis-ocu';
    $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';
    // pulsares por posicion
    if( in_array($_tip[0],['ton','cas']) && isset($ope['val']['pos']) ){
      
      $_val = $ope['val']['pos'];

      if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || ( is_object($_val) && isset($_val->ide) ) ){

        $_ton = api_hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
        foreach( $_pul as $i => $v ){
          if( !empty($ope['opc']["ton-pul_{$i}"]) ){
            $_pul[$i] = api_hol::ima("ton_pul_[$i]", $_ton["pul_{$i}"], ['class'=>'fon'] );
          }
        }
      }
    }
    switch( $_tip[0] ){
    // oraculo
    case 'par':
      break;
    // onda
    case 'ton':
      // fondo: imagen
      $_ .= "
      <{$ope['eti']}".api_ele::atr(api_ele::jun($_tab['ima'],[ 'class'=>DIS_OCU ]))."></{$ope['eti']}>";
      // fondos: color
      $_ .= "
      <{$ope['eti']}".api_ele::atr(api_ele::jun($_tab['fon'],[ 'class'=>"{$col_ocu}" ]))."></{$ope['eti']}>";
      // pulsares
      foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
        <{$ope['eti']}".api_ele::atr(api_ele::jun($_tab['ond'],[ 'data-pul'=>$ide ])).">{$_pul[$ide]}</{$ope['eti']}>";
      }
      break;
    // castillo
    case 'cas':
      // fondos: imagen
      for( $i = 1; $i <= 4; $i++ ){ $_ .= "
        <{$ope['eti']}".api_ele::atr(api_ele::jun($_tab["ond-{$i}"],[ $_tab['ima'], [ 'class'=>DIS_OCU ] ]))."></{$ope['eti']}>";
      }
      // fondos: color
      for( $i = 1; $i <= 4; $i++ ){ $_ .= "
        <{$ope['eti']}".api_ele::atr(api_ele::jun($_tab["ond-{$i}"],[ $_tab['fon'], [ 'class'=>"fon_col-4-{$i}{$col_ocu}" ] ]))."></{$ope['eti']}>";
      }        
      // bordes: orbitales
      for( $i = 1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ $_ .= "
        <{$ope['eti']}".api_ele::atr(api_ele::jun(['class'=>$orb_ocu ],[ $_tab['orb'], $_tab["orb-{$i}"] ]))."></{$ope['eti']}>";
      }        
      // fondos: pulsares
      for( $i = 1; $i <= 4; $i++ ){
        foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
          <{$ope['eti']}".api_ele::atr(api_ele::jun($_tab['ond'],[ [ 'data-pul'=>$ide ] , $_tab["ond-{$i}"] ])).">{$_pul[$ide]}</{$ope['eti']}>";
        }
      }
      break;      
    }
    return $_;
  }
  // Posicion: datos + titulos + contenido[ ima, num, tex]
  static function tab_pos( string $est, mixed $val, array &$ope, array $ele ) : string {
    $esq = 'hol';
    // recibo objeto 
    if( is_object( $val_ide = $val ) ){
      $_dat = $val;
      $val_ide = intval($_dat->ide);
    }// o identificador
    else{
      $_dat = api_hol::_($est,$val);
    }
    // seccion
    $_val['sec_par'] = !empty($ope['sec']['par']) ? 'sec_par' : FALSE;
    // posicion
    $_val['pos_dep'] = !empty($ope['sec']['pos_dep']);// patrones
    $_val['pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
    $_val['pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen      
    // portales 
    $_val['kin_pag'] = !empty($ope['pag']['kin']);
    $_val['psi_pag'] = !empty($ope['pag']['psi']);
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
        if( !empty( $dat_est = app::dat($esq,$est,'rel') ) ){

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
    // .posiciones del tablero principal /////////////////////////////////////
      $agr = "";    
      // omito dependencias
      if( !$_val['pos_dep'] ){

        $agr = "app_ope";

        if( $_val['sec_par'] ){
          $agr .= ( !empty($agr) ? ' ': '' ).$_val['sec_par']; 
          $par_ima = !empty($_val['pos_ima']) ? $_val['pos_ima'] : "{$esq}.{$est}.ide";
        }

        if( isset($ope['val']['pos']) ){

          $dat_ide = $ope['val']['pos'];

          if( is_array($dat_ide) && isset($dat_ide[$est]) ){
            $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
          }
          // agrego seleccion
          if( $_dat->ide == $dat_ide ) $agr .= ( !empty($agr) ? ' ': '' ).'_val-pos _val-pos_bor';
        }
      }
    //////////////////////////////////////////////////////////////////////////    
    // armo titulos //////////////////////////////////////////////////////////
      $pos_tit = [];
      if( isset($e["data-fec_dat"]) ){
        $pos_tit []= "Calendario: {$e["data-fec_dat"]}";
      }
      if( isset($e["data-hol_kin"]) ){
        $_kin = api_hol::_('kin',$e["data-hol_kin"]);
        $pos_tit []= app_dat::val('tit',"hol.kin",$_kin);
        if( $_val['kin_pag'] && !empty($_kin->pag) ) $agr .= " _hol-pag_kin";
      }
      if( isset($e["data-hol_sel"]) ){
        $pos_tit []= app_dat::val('tit',"hol.sel",$e["data-hol_sel"]);
      }
      if( isset($e["data-hol_ton"]) ){
        $pos_tit []= app_dat::val('tit',"hol.ton",$e["data-hol_ton"]);
      }
      if( isset($e["data-hol_psi"]) ){
        $_psi = api_hol::_('psi',$e["data-hol_psi"]);
        $pos_tit []= app_dat::val('tit',"hol.psi",$_psi);          
        if( $_val['psi_pag'] ){
          $_psi->tzo = api_hol::_('kin',$_psi->tzo);
          if( !empty($_psi->tzo->pag) ) $agr .= " _hol-pag_psi";
        }
      }
      if( isset($e["data-hol_rad"]) ){
        $pos_tit []= app_dat::val('tit',"hol.rad",$e["data-hol_rad"]);
      }
      $e['title'] = implode("\n\n",$pos_tit);

    //////////////////////////////////////////////////////////////////////////
    // Contenido html ////////////////////////////////////////////////////////
      // clases adicionales
      if( !empty($agr) ){ api_ele::cla($e,$agr,'ini'); }
      $htm = ""; 
      // por patrones: posicion por dependencia
      if( !empty($_dat) && !!$_val['sec_par'] ){

        $ele_sec = $e;
        if( isset($ele_sec['class']) ) unset($ele_sec['class']);
        if( isset($ele_sec['style']) ) unset($ele_sec['style']);
        
        $htm = app_hol::tab($est,'par',[
          'ide'=>$_dat,
          'sec'=>[ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal
          'pos'=>[ 'ima'=>isset($par_ima) ? $par_ima : "hol.{$est}.ide" ]
        ],[
          'sec'=>$ele_sec
        ]);
      }
      // genero posicion
      elseif( !empty($_dat) ){
        // color de fondo
        if( $_val['pos_col'] ){
          $_ide = api_dat::ide($_val['pos_col']);
          if( 
            isset($e["data-{$_ide['esq']}_{$_ide['est']}"]) 
            && 
            !empty( $_dat = api::dat($_ide['esq'],$_ide['est'],$e["data-{$_ide['esq']}_{$_ide['est']}"]) ) 
          ){
            $col = api_dat::opc('col', ...explode('.',$_val['pos_col']));
            if( isset($col['val']) ){
              $col = $col['val'];
              $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
              api_ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : api_num::ran($val,$col) ) );
            }              
          }
        }
        // contenido
        foreach( ['ima','num','tex','fec'] as $tip ){

          if( !empty($ope['pos'][$tip]) ){
            $ide = api_dat::ide($ope['pos'][$tip]);

            $htm .= app_dat::ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
          }
        }
      }
      // agrego posicion automatica-incremental
      $ope['_tab_pos']++;
      api_ele::cla($e,"pos ide-{$ope['_tab_pos']}",'ini');
    //////////////////////////////////////////////////////////////////////////
    // devuelvo posicion /////////////////////////////////////////////////////
    return "
    <{$ope['eti']}".api_ele::atr($e).">{$htm}</{$ope['eti']}>";
  }
}