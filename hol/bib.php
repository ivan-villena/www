<?php
// biblografía
class hol_bib {

  static string $IDE = "hol_bib-";
  static string $EJE = "hol_bib.";

  // Tierra en ascenso
  static function asc( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Factor Maya
  static function fac( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // tonos : rayo de pulsacion
    case 'ton':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('ton') as $_ton ){ $_ []= "
        ".hol::ima("ton",$_ton,['class'=>"mar_der-1"])."
        <p>
          <n>".intval($_ton->ide)."</n><c>.</c> El Rayo de Pulsación ".preg_replace("/^(del|de la)/","$1<b class='ide'>",tex::art_del($_ton->gal))."</b>
        </p>";
      }        
      break;
    // tonos : simetría especular
    case 'ton_sim': 
      foreach( hol::_('ton_sim') as $_sim ){ $_ []= "
        <p>".tex::let($_sim->des)."</p>";
      }        
      break;
    // sellos : posiciones direccionales
    case 'sel_cic_dir':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_($ide) as $_dir ){ $_ []=
        hol::ima($ide,$_dir,['class'=>"mar_der-1 tam-11"])."
        <div>
          <p><b class='ide'>".explode(' ',$_dir->nom)[1]."</b><c>:</c></p>
          <ul>
            <li><p><c>-></c> ".tex::let($_dir->des)."</p></li>
            <li><p><c>-></c> Color<c>:</c> <c class='let_col-4-{$_dir->ide}'>{$_dir->col}</c></p></li>
          </ul>
        </div>";
      }
      break;
    // sellos : desarrollo del ser con etapas evolutivas
    case 'sel_cic_ser':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('sel') as $_sel ){
        if( $lis_pos != $_sel->cic_ser ){
          $lis_pos = $_sel->cic_ser;
          $_ser = hol::_($ide,$lis_pos);
          $_ []= "
          <p class='tit'>
            DESARROLLO".( tex::let_may( tex::art_del($_ser->nom) ) ).( !empty($_ser->det) ? " <c>-</c> Etapa {$_ser->det}" : '' )."
          </p>";
        }                
        $_dir = hol::_('sel_cic_dir',$_sel->arm_raz); $_ []= 

        hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p><n>{$_sel->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".explode(' ',$_dir->nom)[1]."<c>.</c>
          <br>".tex::let($_sel->cic_ser_des)."
        </p>";
      }        
      break;
    // sellos : familias ciclicas
    case 'sel_cic_luz': 
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('sel') as $_sel ){
        if( $lis_pos != $_sel->cic_luz ){
          $lis_pos = $_sel->cic_luz;
          $_luz = hol::_($ide,$lis_pos); $_ []= "
          <p><b class='tit'>".tex::let_may("Familia Cíclica ".tex::art_del($_luz->nom)."")."</b>
            <br><b class='des'>{$_luz->des}</b><c>.</c>
          </p>";
        }                
        $_dir = hol::_('sel_cic_dir',$_sel->arm_raz);                 
        
        $_ []= 

        hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p>".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
          <br>".tex::let($_sel->cic_luz_des)."
        </p>";                
      }          
      break;
    // kin : katun del kin
    case 'kin':
      $_kin = hol::_('kin',$ope['ide']);
      $_sel = hol::_('sel',$_kin->arm_tra_dia);
      $_pol = hol::_('uni_flu_res',$_sel->flu_res);
      $_pla = hol::_('uni_sol_pla',$_sel->sol_pla);
      $_ond = hol::_('kin_nav_ond',$_kin->nav_ond);
      $_arq = hol::_('sel',$_ond->sel);
      $ton = intval($_kin->nav_ond_dia);
      $_ = "
      <div class='val'>

        ".hol::ima("kin",$_kin)."

        <p class='tit tex_ali-izq'>
          Katún <n>".intval($_sel->ide-1)."</n><c>:</c> Kin <n>$ton</n> <b class='ide'>$_sel->may</b>".( !empty($_kin->pag) ? "<c>(</c> Activación Galáctica <c>)</c>" : '' )."<c>.</c>
        </p>
      
      </div>
      <ul>
        <li>Regente Planetario<c>:</c> $_pla->nom $_pol->tip<c>.</c></li>
        <li>Etapa <n>$ton</n><c>,</c> Ciclo $_arq->may<c>.</c></li>
        <li>Índice Armónico <n>".num::int($_kin->fac)."</n><c>:</c> período ".tex::let($_kin->fac)."</li>
        <li><q>".tex::let($_sel->arm_tra_des)."</q></li>
      </ul>";
      break;
    // kin : portales de activacion
    case 'kin_pag':
      $arm_tra = 0;
      $ope['lis'] = ['class'=>"ite"];
      foreach( array_filter(hol::_('kin'), function( $ite ){ return !empty($ite->pag); }) as $_kin ){ 
        $lis_pos++; 
        $_sel = hol::_('sel',$_kin->arm_tra_dia);
        if( $arm_tra != $_kin->arm_tra ){
          $arm_tra = $_kin->arm_tra;
          $_tra = hol::_('kin_arm_tra',$arm_tra); $_ []= "

          ".hol::ima("ton",$arm_tra,['class'=>"mar_der-1"])."

          <p class='tit'>".tex::let(tex::let_may("CICLO ".($num = intval($_tra->ide)).", Baktún ".( $num-1 )))."</p>";
        }
        $_ []= "

        ".hol::ima("kin",$_kin,['class'=>"mar_der-1"])."

        <p>
          <n>{$lis_pos}</n><c>.</c> <b class='ide'>{$_sel->may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
          <br>".tex::let($_kin->fac)."
        </p>";
      }          
      break;
    // kin : 1 trayectoria con detalles por katun ( ciclos del modelo morfogenetico )
    case 'kin_fec':
      $ond = 0;
      $_ = "
      <table>";
        if( !empty($ope['tit']) ){ $_.="
          <caption>".( !empty($ope['tit']['htm']) ? "<p class='tit'>".tex::let($ope['tit']['htm'])."</p>" : '' )."</caption>";
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
        foreach( ( !empty($dat) ? $dat : hol::_('kin') ) as $_kin ){

          if( $ond != $_kin->nav_ond ){
            $_ond = hol::_('kin_nav_ond', $ond = $_kin->nav_ond); 
            $_sel = hol::_('sel', $_ond->sel);
            $_ .= "
            <tr class='tex_ali-izq'>
              <td>
                ".hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."
              </td>
              <td colspan='3'>{$_sel->may}<c>:</c> ".tex::let($_ond->fac)." <q>".tex::let($_ond->fac_des)."</q></td>
            </tr>";
          }
          $_sel = hol::_('sel',$sel = intval($_kin->arm_tra_dia));
          $_ .= "
          <tr data-kin='{$_kin->ide}'>
            <td>
              Etapa <n>".($ton = intval($_kin->nav_ond_dia))."</n>
            </td>
            <td></td>
            <td>
              <n>$sel</n><c>.</c><n>$ton</n> <b class='ide'>$_sel->may</b><c>:</c>
              <br><n>".num::int($_kin->fac)."</n><c>,</c> año <n>".num::int($_kin->fac_ini)."</n>
            </td>
            <td>
              <q>".tex::let($_sel->arm_tra_des)."</q>
            </td>
          </tr>";
        }$_.="
        </tbody>

      </table>";
      break;
    // kin : 13 baktunes
    case 'kin_arm_tra':

      foreach( hol::_($ide) as $_tra ){
        $htm = "
        <div class='val'>
          ".hol::ima("ton",$_tra->ide,['class'=>"mar_der-1"])."
          <p>
            <b class='tit'>Baktún <n>".(intval($_tra->ide)-1)."</n><c>.</c> Baktún ".tex::art_del($_tra->tit)."</b>
            <br>".tex::let($_tra->fac)." <c><=></c> ".tex::let($_tra->may)."
          </p>
        </div>";
        $lis = [];
        foreach( explode('; ',$_tra->lec) as $ite ){
          $lis []= "<c>-></c> ".tex::let($ite);
        }
        $_[] = $htm.lis::ite($lis,[ 'lis'=>['class'=>"pun"] ]);
      }          
      break;
    // kin : 20 katunes
    case 'kin_arm_sel':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_('sel') as $_sel ){ $_ [] = "

        ".hol::ima("sel_arm_tra",$_sel,['class'=>"mar_der-2"])."

        <p>
          <b class='ide'>{$_sel->may}</b><c>:</c> Katún <n>".(intval($_sel->ide)-1)."</n>
          <br>{$_sel->arm_tra_des}
        </p>";
      }
      break;
    // kin : sellos guardianes de la evolucion mental
    case 'kin_cro_est':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('kin_cro_est') as $_est ){
        $_sel = hol::_('sel',$_est->sel); 
        $_dir = hol::_('sel_cic_dir',$_est->ide); $_ []= 
        
        hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p><n>".intval($_sel->ide)."</n><c>°</c> Signo<c>.</c> ".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
          <br><b class='val des'>{$_est->nom}</b><c>:</c> {$_est->des}<c>.</c>
        </p>";
      }        
      break;
    // kin : guardianes por estacion cromatica
    case 'kin_cro_sel':
      foreach( hol::_($ide) as $_est ){
        $_sel = hol::_('sel',$_est->sel); $htm = "
        <div class='val'>
          ".hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
          <p>
            <b class='tit'>ESTACIÓN ".tex::let_may(tex::art_del("el {$_est->dir}"))."</b>
            <br>Guardían<c>:</c> <b class='ide'>{$_sel->may}</b> <c>(</c> {$_sel->nom} <c>)</c>
          </p>
        </div>";
        $lis = [];
        foreach( hol::_('kin_cro_ond') as $_ond ){ $lis []= "

          ".hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."

          <p>{$_ond->fac}<c>.</c>
            <br><n>".intval($_ond->ton)."</n> {$_sel->may}
          </p>";
        }                
        $_[] = $htm.lis::ite($lis,[ 'lis'=>['class'=>'ite'] ]);
      }          
      break;
    // kin : ciclo ahau / onda encantada
    case 'kin_nav_sel':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_('kin_nav_ond') as $_ond ){ 
        $_sel = hol::_('sel',$_ond->sel); $_ [] = "

        ".hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-2"])."

        <p>
          <n>{$_ond->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".tex::let($_ond->fac)."
          <br><q>{$_ond->fac_des}</q>
        </p>";
      }            
      break;
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Encantamiento del Sueño
  static function enc( string $ide, array $ope = [] ) : string {
    $_ = []; 
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    $_eje = self::$EJE."enc('{$ide}',";
    switch( $ide ){
    // tonos : descripciones
    case 'ton':
      $est_ope['atr'] = ['ide','nom','des','acc'];
      $_ = est::lis("hol.ton", $est_ope, $ope );
      break;
    // tonos : aventura de la onda encantada 
    case 'ton_ond':
      $_atr = array_merge([ 
        'ima'=>obj::atr(['ide'=>'ima','nom'=>''])
        ], dat::atr('hol',"ton", [ 'ide','ond_pos','ond_pod','ond_man' ])
      );
      // cargo valores
      foreach( ( $_dat = obj::atr(hol::_('ton')) ) as $_ton ){
        $_ton->ima = [ 'htm'=>hol::ima("ton",$_ton) ];
        $_ton->ide = "Tono {$_ton->ide}";
      }
      // cargo titulos
      $ond = 0;
      $_tit = [];
      foreach( $_dat as $lis_pos => $_ton ){
        if( $_ton->ond_enc != 0 && $ond != $_ton->ond_enc ){              
          $_ond = hol::_('ton_ond',$ond = $_ton->ond_enc);
          $_tit[$lis_pos] = $_ond->des;
        }
      }

      $_ = est::lis($_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit, 'opc'=>['cab_ocu'] ],$ope);              
      break;
          
    // tonos : pulsares dimensionales
    case 'ton_dim':
      foreach( hol::_('ton_dim') as $_dat ){ $htm = "
        <p>
          <n>{$_dat->ide}</n><c>.</c> <b class='ide'>Pulsar de la {$_dat->pos} dimensión</b><c>:</c> <b class='val'>Dimensión {$_dat->nom}</b>
          <br>Tonos ".tex::let("{$_dat->ton}: {$_dat->ond}")."
        </p>
        <div class='fic ite'>
          ".hol::ima("ton_dim",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
            foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "
          <c class='_lis fin'>}</c>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // tonos : pulsares matiz
    case 'ton_mat':
      foreach( hol::_('ton_mat') as $_dat ){ $htm = "
        <p><n>{$_dat->ide}</n><c>.</c> <b class='ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='val'>".tex::let($_dat->cod)."</b><c>:</c>
          <br>Tonos ".tex::let("{$_dat->ton}: {$_dat->ond}")."
        </p>
        <div class='fic ite'>
          ".hol::ima("ton_mat",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
            foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "              
          <c class='_lis fin'>}</c>
        </div>";
        $_ []= $htm;
      }        
      break;
    // sello : colocacion armónica => razas raíz cósmica
    case 'sel_arm_raz':
      $sel = 1;
      foreach( hol::_($ide) as $_dat ){
        $_raz_pod = hol::_('sel',$_dat->ide)->pod; 
        $htm = "
        <p class='tit'>Familia <b class='let_col-4-{$_dat->ide}'>{$_dat->nom}</b><c>:</c> de la <b class='ide'>Raza Raíz ".tex::let_min($_dat->nom)."</b></p>
        <p>Los {$_dat->pod}dores<c>.</c> Nota clave<c>:</c> ".explode(' ',$_raz_pod)[1]."</p>
        <ul class='ite'>";
        foreach( hol::_('sel_arm_cel') as $lis_pos ){
          $_sel = hol::_('sel',$sel); $htm .= "
          <li>
            ".hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
            <p>
              <n>{$lis_pos->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
              <br><q>".tex::let($_sel->arm_raz_des)."</q>
            </p>
          </li>";
          $sel += 4;
          if( $sel > 20 ) $sel -= 20;                  
        }
        $htm.="
        </ul>
        <q>".tex::let(tex::let_pal($_raz_pod)." ha sido ".tex::art_gen("realizado",$_raz_pod).".")."</q>";
        $_ []= $htm;
      }        
      break;
    // sello : colocacion armónica => células del tiempo
    case 'sel_arm_cel':
      $lis_pos = 1;

      foreach( hol::_($ide) as $_dat ){ $htm = "
        <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='ide'>{$_dat->nom}</b></p>
        <q>".tex::let($_dat->des)."</q>
        <ul class='ite'>";
        foreach( hol::_('sel_arm_raz') as $cro ){ $_sel = hol::_('sel',$lis_pos); $htm .= "
          <li>
            ".hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
            <p>
              <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
              <br><q>".tex::let($_sel->arm_cel_des)."</q>
            </p>
          </li>";
          $lis_pos ++;
        }$htm .= "
        </ul>";
        $_ []= $htm;
      }           
      break;
    // sello : colocacion cromática => clanes galácticos
    case 'sel_cro_ele':
      $sel = 20;      
      foreach( hol::_($ide) as $_dat ){
        $ele_nom = explode(' ',$_dat->nom)[1]; $htm = "
        <p class='tit'><b class='ide'>Clan ".tex::art_del($_dat->nom)."</b>".tex::let(": Cromática {$_dat->col}.")."</p>
        ".( !empty($_dat->des_ini) ? "<p>".tex::let($_dat->des_ini)."</p>" : '' )."
        <ul class='ite'>";
        for( $fam=1; $fam<=5; $fam++ ){ 
          $_sel = hol::_('sel',$sel); 
          $_fam = hol::_('sel_cro_fam',$fam); $htm .= "
          <li sel='{$_sel->ide}' cro_fam='{$fam}'>
            ".hol::ima("sel",$_sel,[ 'class'=>"mar_der-1" ])."
            <p>
              <n>{$sel}</n><c>.</c> <b class='ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
              <br><q>".tex::let($_sel->cro_ele_des)."</q>
            </p>
          </li>";
          $sel++;
          if( $sel > 20 ) $sel -= 20;
        }$htm .= "
        </ul>";
        $_ []= $htm;
      }          
      break;
    // sello : colocacion cromática => familias terrestres
    case 'sel_cro_fam':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_('uni_pla_cen') as $_pla ){
        $_hum = hol::_('uni_hum_cen',$_pla->ide);
        $_fam = hol::_($ide,$_pla->fam);
        $htm = 
        hol::ima("uni_pla_cen",$_pla,['class'=>"mar_der-2",'style'=>"min-width: 9rem; height:7rem;"])."
        <div>
          <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> {$_fam->mis}</p>
          <div class='val fic mar-2'>
            ".hol::ima("uni_hum_cen",$_hum)."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_fam->sel) as $sel ){
                $htm .= hol::ima("sel",$sel,['class'=>"mar_hor-2"]);
              }$htm .= "
            <c class='_lis fin'>}</c>
          </div>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // sello : holon solar => celulas solares y planentas
    case 'uni_sol_cel': 
      $orb = 0;
      $pla = 10;
      $sel = 20;
      $val_sel = empty( $val = isset($ope['val']) ? $ope['val'] : [] );

      foreach( hol::_($ide) as $_dat ){
        if( $val_sel || in_array($_dat->ide,$val) ){ 
          $htm = "
          <p class='tit'><b class='ide'>{$_dat->nom}</b><c>:</c> Célula Solar ".num::dat($_dat->ide,'nom')."<c>.</c></p>                  
          <ul est='sol_pla'>";
          for( $sol_pla=1; $sol_pla<=2; $sol_pla++ ){
            $_pla = hol::_('uni_sol_pla',$pla);
            $_sel = hol::_('sel',$sel);
            $_par = hol::_('sel',$_sel->par_ana); 
            if( $orb != $_pla->orb ){
              $_orb = hol::_('uni_sol_orb',$orb = $_pla->orb); $htm .= "
              <li>Los <n>5</n> <b class='ide'>planetas {$_orb->nom}es</b><c>:</c> ".tex::let($_orb->des)."</li>";                        
            }
            $htm .= "
            <li>
              <p><b class='ide'>{$_pla->nom}</b><c>,</c> <n>{$pla}</n><c>°</c> órbita<c>:</c></p>
              <div class='ite'>

                ".hol::ima("uni_sol_pla",$_pla,['class'=>"mar_der-1"])."

                <ul class='ite' est='sel'>
                  <li>
                    ".hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
                    <p>
                      <b class='val'>Dentro</b><c>:</c> Sello Solar <n>{$_sel->ide}</n>
                      <br><q>".tex::let($_sel->sol_pla_des)."</q>
                    </p>
                  </li>
                  <li>
                    ".hol::ima("sel",$_par,['class'=>"mar_der-1"])."
                    <p>
                      <b class='val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                      <br><q>".tex::let($_par->sol_pla_des)."</q>
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
    // sello : holon planetario => centros planetarios
    case 'uni_pla_cen': 
      $ope['lis'] = ['class'=>"ite"];

      $_fam_sel = [
        1=>[  5, 10, 15, 20 ],
        2=>[  1,  6, 11, 16 ],
        3=>[ 17,  2,  7, 12 ],
        4=>[ 13, 18,  3,  8 ],
        5=>[  9, 14, 19,  4 ]
      ]; 
      $lis_pos = 1;
      foreach( hol::_('uni_pla_cen') as $_dat ){
        $_fam = hol::_('sel_cro_fam',$_dat->fam); $htm= "
        <div class='val'>
          ".hol::ima("sel_cro_fam",$_fam,['class'=>"mar_der-1"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
          foreach( $_fam_sel[$_dat->ide] as $sel ){
            $htm .= hol::ima("sel",$sel,['class'=>"mar_hor-1"]);
          }$htm.="
          <c class='_lis fin'>}</c>
          <c class='sep'>:</c>
        </div>
        <p>
          <n>{$_dat->ide}</n><c>.</c> El Kin <b class='ide'>{$_fam->nom}</b><c>:</c>
          <br><q>{$_dat->fun} desde el {$_dat->nom}</q>
        </p>";
        $_ []= $htm;
      }        
      break;
    // sello : holon planetario => rol de familias terrestres
    case 'uni_pla_pos':
      $_fam_sel = [
        1=>[ 20,  5, 10, 15 ],
        2=>[  1,  6, 11, 16 ],
        3=>[ 17,  2,  7, 12 ],
        4=>[ 18,  3,  8, 13 ],
        5=>[ 14, 19,  4,  9 ]
      ];
      foreach( hol::_('uni_pla_cen') as $_dat ){
        $_fam = hol::_('sel_cro_fam',$_dat->fam);
        $htm = "
        <div class='tex_ali-cen'>
          <p>Kin <b class='ide'>{$_fam->nom}</b></p>
          ".hol::ima("uni_pla_cen",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 17rem; height: 11rem;"])."
        </div>
        <ul class='ite'>";
          foreach( $_fam_sel[$_dat->ide] as $sel ){
            $_sel = hol::_('sel',$sel);
            $_pla_mer = hol::_('uni_pla_mer',$_sel->pla_mer);
            $_pla_hem = hol::_('uni_pla_hem',$_sel->pla_hem);
            $htm .= "
            <li>
              ".hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
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
    // sello : holon humano => colocacion cromática
    case 'uni_hum_ele': 
      $ele_tit = []; $lis_pos = 0; $col = 4;
      foreach( hol::_('uni_hum_ext') as $_ext ){
        $_ele = hol::_('sel_cro_ele',$_ext->ele); 
        $nom = explode(' ',tex::art_del($_ele->nom)); $cla = array_pop($nom); $nom = implode(' ',$nom);
        $ele_tit[$lis_pos] = [ 
          'eti'=>"div", 'class'=>"ite", 'htm'=> hol::ima("uni_hum_ext",$_ext,['class'=>"mar_der-1"])."                  
          <p class='tit tex_ali-izq'><b class='ide'>$_ext->nom</b><c>:</c>
            <br>Clan {$nom} <c class='let_col-4-$col'>{$cla} $_ele->col</c></p>" 
        ];
        $lis_pos += 5; 
        $col = num::ran($col+1,4);
      }
      $sel_lis = [];
      foreach( hol::_('sel_cod') as $_sel ){
        $_fam = hol::_('sel_cro_fam',$_sel->cro_fam);
        $_hum_ded = hol::_('uni_hum_ded',$_fam->hum_ded);
        $sel_lis []= obj::atr([ 
          'hum_ded'=>$_hum_ded->nom, 
          'nom'=>"Tribu ".tex::art_del($_sel->nom)." $_sel->nom_col", 
          'ima_nom'=>[ 'htm'=>hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
          'nom_cod'=>$_sel->nom_cod,
          'ima_cod'=>[ 'htm'=>hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ]
        ]);
      }
      $_ = est::lis($sel_lis,[ 'tit'=>$ele_tit, 'opc'=>['cab_ocu'] ]);
      break;
    // sello : holon humano => rol de familias terrestres
    case 'uni_hum_fam':
      $fam_tit = [];
      $sel_lis = [];

      foreach( hol::_('uni_hum_ded') as $_ded ){
        $_fam = hol::_('sel_cro_fam',$_ded->fam);
        $fam_tit[$lis_pos] = [
          'eti'=>"div", 'class'=>"ite", 'htm'=> hol::ima("uni_hum_ded",$_ded,['class'=>"mar_der-1"])."                  
          <p class='tit tex_ali-izq'><b class='ide'>Familia Terrestre $_fam->nom</b><c>:</c>
            <br>Familia de $_fam->cod<c>:</c> Dedos {$_ded->nom}".( in_array($_ded->nom,['Anular','Pulgar']) ? "es" : "s" )." </p>" 
        ];
        $lis_pos += 4;
        foreach( explode(', ',$_fam->sel) as $_sel ){
          $_sel = hol::_('sel',$_sel);
          $_hum_ext = hol::_('uni_hum_ext',$_sel->hum_ext);
          $sel_lis []= obj::atr([
            'nom'=>"Tribu ".tex::art_del($_sel->nom)." $_sel->nom_col", 
            'ima_nom'=>[ 'htm'=>hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
            'nom_cod'=>$_sel->nom_cod,
            'ima_cod'=>[ 'htm'=>hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ],
            'hum_ext'=>$_hum_ext->nom
          ]);
        }
      }

      $_ = est::lis($sel_lis,[ 'tit'=>$fam_tit, 'opc'=>['cab_ocu'] ]);
      break;
    // sello : holon humano => extremidades del humano
    case 'uni_hum_ext':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_($ide) as $_dat ){
        $_ele = hol::_('sel_cro_ele',$_dat->ele); $_ []= "

          ".hol::ima("uni_hum_ext",$_dat,['class'=>"mar_der-1"])."

          <p><b class='ide'>Cromática ".tex::art_del($_ele->nom)."</b><c>:</c>
            <br>{$_dat->nom}
          </p>";
      }        
      break;
    // sello : holon humano => dedos del humano
    case 'uni_hum_ded':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_($ide) as $_dat ){
        $_fam = hol::_('sel_cro_fam',$_dat->fam); $_ []= "

          ".hol::ima("uni_hum_ded",$_dat,['class'=>"mar_der-1"])."

          <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
            <br>{$_dat->nom}
          </p>";
      }        
      break;
    // sello : holon humano => centros galácticos del humano
    case 'uni_hum_cen':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_($ide) as $_dat ){
        $_fam = hol::_('sel_cro_fam',$_dat->fam); 
        $_hum = hol::_('uni_hum_cen',$_fam->hum_cen);
        $_ []= "

        ".hol::ima("uni_hum_cen",$_dat,['class'=>"mar_der-1"])."

        <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
          <br>".tex::art($_dat->nom)." <c>-></c> {$_hum->fun}
        </p>";
      }            
      break;
    // encantamiento : libro del kin        
    case 'kin':
      $_ = "
      <!-- libro del kin -->
      <form class='inf' esq='hol' est='$ide'>

        <div class = 'val'>

          <fieldset class='val'>

            ".doc::val_ope()."

            ".doc::var('atr',"hol.kin.ide",[ 'nom'=>"ver el kin", 'ope'=>[ 
              'title'=>"Introduce un número de kin...", 'oninput'=>"{$_eje}this);" 
            ]])."
          </fieldset>

          <fieldset class='ope'>
            ".fig::ico('dat_fin',[ 'eti'=>"button", 'type'=>"reset", 'title'=>"Vaciar Casillero...", 'onclick'=>"{$_eje}this,'fin');" ])."
            ".fig::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"{$_eje}this,'nav');" ])."
          </fieldset>

        </div>

        <output class='hol-kin'></output>
        
      </form>
              
      <nav>";
        $_nav_cas = 0; $_nav_ond = 0;
        $arm_tra = 0; $arm_cel = 0;
        $cro_est = 0; $cro_ele = 0;
        $gen_enc = 0; $gen_cel = 0;
        foreach( hol::_('kin') as $_kin ){

          // castillo
          if( $_kin->nav_cas != $_nav_cas ){
            $_nav_cas = $_kin->nav_cas;
            $_cas = hol::_('kin_nav_cas',$_kin->nav_cas); 
            if( $_nav_cas != 1 ){ $_ .= "
                </section>

              </section>
              ";
            }$_ .= "
            ".doc::val(['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."
            <section data-kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>
              <p cas='{$_cas->ide}'>".tex::let("Corte {$_cas->cor}: {$_cas->fun}")."</p>
            ";
          }
          // génesis
          if( $_kin->gen_enc != $gen_enc ){
            $gen_enc = $_kin->gen_enc;
            $_gen = hol::_('kin_gen_enc',$_kin->gen_enc); $_ .= "
            <p class='tit' data-gen='{$_gen->ide}'>GÉNESIS ".tex::let_may($_gen->nom)."</p>";
          }
          // onda encantada
          if( $_kin->nav_ond != $_nav_ond ){
            $_nav_ond = $_kin->nav_ond;
            $_ond = hol::_('kin_nav_ond',$_kin->nav_ond);
            $_sel = hol::_('sel',$_ond->sel); 
            $ond = num::ran($_ond->ide,4);

            if( $_nav_ond != 1 && $ond != 1 ){ $_ .= "
              </section>";
            }
            $_ .= "
            ".doc::val([
              'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 
              'htm'=> tex::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
            ])."
            <section data-kin_nav_ond='{$_ond->ide}'>
              <p class='let-enf' ond='{$_ond->ide}'>Poder ".tex::art_del($_sel->pod)."</p>";
          }
          // célula armónica : titulo + lectura
          if( $_kin->arm_cel != $arm_cel ){
            $arm_cel = $_kin->arm_cel;
            $_cel = hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
            </section>

            ".doc::val([
              'eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,
              'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>".tex::let(tex::let_may($_cel->des))
            ])."
            <section data-kin_arm_cel='{$_cel->ide}'>
            ";
          }
          // kin : ficha + nombre + encantamiento
          $_ .= "
          <div data-kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
            <div class='hol-kin'>
              ".hol::ima("kin",$_kin->ide,['class'=>'mar-aut'])."
              <p>
                <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='let_col-4-{$_kin->arm_cel_dia}'>".tex::let(tex::let_may($_kin->nom))."</c>
                <br><q>".tex::let($_kin->des)."</q>                  
              </p>
            </div>
          </div>";
        }$_ .= "
        </section>
      </nav>";        
      break;
    // encantamiento : índice armónico de 13 trayectorias y 65 células
    case 'kin_arm':
      $arm_cel = 0;
      $_lis = [];
      if( !isset($ope['nav']) ) $ope['nav'] = [];

      foreach( hol::_('kin_arm_tra') as $_tra ){

        $_lis_cel = [];
        foreach( hol::_('sel_arm_cel') as $_cel ){
          $arm_cel++;
          $_cel = hol::_('kin_arm_cel',$arm_cel); $_lis_cel []= "
          <a class='tex' href='#kin_arm_cel-{$_cel->ide}-'>
            <n>{$_cel->ide}</n><c>.</c> <b class='ide'>{$_cel->nom}</b>".tex::let(": {$_cel->des}")."
          </a>";
        }        
        $_lis []= [
          'ite'=>"Trayectoria $_tra->ide: Tono $_tra->ton, $_tra->ton_des",
          'lis'=>$_lis_cel
        ];
      }
      ele::cla( $ope['nav'], "dis-ocu" );
      $ope['opc'] = ['tog','ver'];
      $_ = "

      ".doc::val(
        [ 'eti'=>'h3', 'htm'=> tex::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], 
        [ 'ico'=>['class'=>"ocu"] ]
      )."
      <nav".ele::atr($ope['nav']).">

        ".lis::ite($_lis,$ope)."

      </nav>";    
      break;        
    // kin : espectros galácticos
    case 'kin_cro':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('kin_cro_est') as $_est ){ 
        $_sel = hol::_('sel',$_est->sel); $_ []= "
        ".hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
        <p>
          <n>{$_est->ide}</n><c>.</c> El espectro galáctico <b class='let_col-4-{$_est->ide}'>{$_est->col}</b><c>:</c> 
          Estación ".tex::art_del($_sel->nom)."
        </p>";
      }          
      break;
    // kin : aventura por guardián
    case 'kin_cro_sel':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('kin_cro_ond') as $_ond ){ $_ []= "
        ".hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
        <p>
          Tono <n>".intval($_ond->ton)."</n> de la familia terrestre polar<c>:</c> 
          {$_ond->nom} <n>1</n> de los <n>4</n> Espectros Galácticos<c>.</c>
        </p>";
      }
      break;
    // kin : aventura por estaciones
    case 'kin_cro_ton':
      $ope['lis'] = ['class'=>"ite"];
      foreach( hol::_('kin_cro_ond') as $_ond ){ $_ []= "
        ".hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
        <p>
          Tono <n>".intval($_ond->ton)."</n><c>:</c> 
          {$_ond->nom} <n>".($_ond->cue*5)."</n> Kines <c>(</c> <n>{$_ond->cue}</n> cromática".( $_ond->cue > 1 ? "s" : "")." <c>)</c>
        </p>";
      }            
      break;
    // kin : génesis
    case 'kin_gen':
      $_ = [
        "<b class='ide'>Génesis del Dragón</b><c>:</c> <n>13.000</n> años del Encantamiento del Sueño<c>,</c> poder del sueño<c>.</c>",
        "<b class='ide'>Génesis del Mono</b><c>:</c> <n>7.800</n> años del Encantamiento del Sueño<c>,</c> poder de la magia<c>.</c>",
        "<b class='ide'>Génesis de la Luna</b><c>:</c> <n>5.200</n> años del Encantamiento del Sueño<c>,</c> poder del vuelo mágico<c>.</c>",
      ];     
      break;
    // kin : ondas y castillos con células del génesis
    case 'kin_nav':
      $gen = 0;
      $cel = 0;
      $cas = 0;
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_('kin_nav_ond') as $_ond ){
        // génesis
        if( $gen != $_ond->gen_enc ){ 
          $_gen = hol::_('kin_gen_enc',$gen = $_ond->gen_enc); $_[]="
          <p class='tit'>{$_gen->lec}<c>:</c> <b class='ide'>Génesis {$_gen->nom}</b><c>.</c></p>";
        }
        if( $cel != $_ond->gen_cel ){ 
          $_cel = hol::_('kin_gen_cel',$cel = $_ond->gen_cel); $_[]="
          <p class='tit'>Célula <n>{$_cel->ide}</n> de la memoria del Génesis<c>:</c> <b class='val'>{$_cel->nom}</b></p>";
        }
        if( $cas != $_ond->nav_cas ){ 
          $_cas = hol::_('kin_nav_cas',$cas = $_ond->nav_cas); $_[]="
          <p class='tit'>
            El Castillo <b class='let_col-5-{$_cas->ide}'>".str_replace('del ','',$_cas->nom)."</b> ".tex::art_del($_cas->acc)."<c>:</c> La corte ".tex::art_del($_cas->cor)."<c>,</c> poder {$_cas->pod}
          </p>";
        }              
        $_ []= hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."              
        <p><n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c> <q>".tex::let($_ond->enc_des)."</q></p>";
      }          
      break;
    // psi : por tonos galácticos
    case 'psi_lun':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_($ide) as $_lun ){
        $_ []= hol::ima("ton",$_lun->ton,['class'=>"mar_der-2"])."
        <p>
          <b class='ide'>".tex::let_pal(num::dat($_lun->ide,'pas'))." Luna</b>
          <br>Luna ".tex::art_del($_lun->ton_car)."<c>:</c> ".tex::let($_lun->ton_pre)."
        </p>";
      }    
      break;        
    // psi : fechas desde - hasta
    case 'psi_lun_fec':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_('psi_lun') as $_lun ){
        $_[] = hol::ima("ton",$_lun->ton,['class'=>"mar_der-3"])."
        <p>
          <b class='ide'>$_lun->nom</b> <n>".intval($_lun->ton)."</n>
          <br>".tex::let($_lun->fec_ran)."
        </p>";
      }$_[] = "
      <span ima></span>
      <p>
        <b class='ide'>Día Verde</b> o Día Fuera del Tiempo
        <br><n>25</n> de Julio
      </p>";        
      break;
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // 13 Lunas en Movimiento
  static function lun( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // luna : heptadas - cuarto armónica
    case 'lun_arm':
      if( isset($_atr[1]) ){
        switch( $_atr[1] ){
        // descripcion
        case 'des': 
          foreach( hol::_('lun_arm') as $_hep ){
            $_ []= tex::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>".tex::let("): $_hep->des");
          }
          break;
        case 'pod':
          foreach( hol::_('lun_arm') as $_hep ){
            $_ []= tex::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>".tex::let(", $_hep->pod $_hep->car");
          }        
          break;            
        }
      }
      break;
    // luna : heptadas lunares
    case 'lun_arm_col':
      $est_ope['atr'] = [ 'ide','nom','col','dia','pod' ];
      $est_ope['opc'] []= 'cab_ocu';
      $_ = est::lis("hol.lun_arm", $est_ope, $ope );
      break;
    // kin : castillos del encantamiento
    case 'kin_nav_cas':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_($ide) as $_cas ){ $_ [] = 

        hol::ima($ide,$_cas,['class'=>"mar_der-2"])."

        <p>
          <b class='ide'>Castillo $_cas->col $_cas->dir ".tex::art_del($_cas->acc)."</b><c>:</c>
          <br>Ondas Encantadas ".tex::let($_cas->nav_ond)."
        </p>";
      }          
      break;              
    // psi : totems lunares
    case 'psi_lun_tot':
      $ope['lis'] = ['class'=>"ite"];
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
        $_ []= hol::ima("psi_lun",$_lun = hol::_('psi_lun',$ite),['class'=>"mar_der-2"])."
        <p>
          $htm
          <br>".tex::let($_lun->fec_ran)."
        </p>";
      }
      $_ []= "
      <p>
        Día fuera del tiempo
        <br><n>25</n> de Julio
      </p>";        
      break;
    // anillo : años (desde-hasta) por anillos solares
    case 'ani':
      $ini = 1992;
      $cue = 8;
      $_[] = "
      <b class='ide'>Año Uno</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',39),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio  <n>1.992</n> <c>-</c> <n>25</n> Julio <n>1.993</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Dos</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',144),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.993</n> <c>-</c> <n>25</n> Julio <n>1.994</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Tres</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',249),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.994</n> <c>-</c> <n>25</n> Julio <n>1.995</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Cuatro</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',94),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.995</n> <c>-</c> <n>25</n> Julio <n>1.996</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Cinco</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',199),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.996</n> <c>-</c> <n>25</n> Julio <n>1.997</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Seis</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',44),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.997</n> <c>-</c> <n>25</n> Julio <n>1.998</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Siete</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',149),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.998</n> <c>-</c> <n>25</n> Julio <n>1.999</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='ide'>Año Ocho</b>
      <div class='ite'>
        ".hol::ima("kin",$_kin = hol::_('kin',254),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.999</n> <c>-</c> <n>25</n> Julio <n>2.000</n><c>.</c></p>
      </div>";
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Sonda de Arcturus
  static function arc( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Tratado del Tiempo
  static function tie( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // campos planetarios por agrupacion
    case 'pla_cam': 
      $_ = "
      <ul>
        <li>el gravitacional <c>(</c>las Cuatro Razas Raíz<c>)</c><c>,</c> </li>
        <li>el electromagnético <c>(</c>los Cuatro Clanes<c>)</c> </li>
        <li>y el biopsíquico <c>(</c>las Cinco Familias Terrestres<c>)</c><c>.</c></li>
      </ul>";
      break;
    // 
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
    //
    case 'pla_cam.des': 
      $_ = "
      <ul>
        <li>el campo electromagnético se reconstituye psicofísicamente a través de los sentidos<c>;</c> </li>
        <li>el campo bio<c>-</c>psíquico se reorganiza como orden cósmico telepático de la sociedad humana indistinguible de los órdenes vivos de la naturaleza<c>,</c> </li>
        <li>y el campo gravitacional es conducido a un nuevo nivel de equilibrio a través de una vibrante correlación y simbiosis de los dos órdenes geoquímicos tridimensionales<c>,</c> SiO<sup>2</sup> <c>(</c>dióxido de silicio<c>)</c> y CO<sup>2</sup> <c>(</c>dióxido de carbono<c>)</c><c>.</c></li>
      </ul>";
      break;
    // kin : trayectorias + castillos
    case 'kin':
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
    // psi : vinales
    case 'psi_vin':
      $est_ope['atr'] = ['ide','nom','fec','sin','cro'];
      $est_ope['det_des'] = ['des'];
      //$ope['lis']['class'] = "anc-100 mar-2";
      $_ = est::lis("hol.{$ide}", $est_ope, $ope);

      break;
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Telektonon
  static function tel( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // libros-cartas
    case 'fic-lib':
      $_dat = [
        4  => ['ide'=> 4, 'nom'=>"Libro de la Forma Cósmica" ],
        7  => ['ide'=> 7, 'nom'=>"Libro de las Siete Generaciones Perdidas" ],
        13 => ['ide'=>13, 'nom'=>"Libro del Tiempo Galáctico" ],
        28 => ['ide'=>28, 'nom'=>"Libro Telepático para la Redención de los Planetas Perdidos" ]
      ];
      $ide = isset($ope['ide']) ? $ope['ide'] : 4;
      $opc = isset($ope['opc']) ? $ope['opc'] : [];
      $opc_ini = empty($opc) || in_array('ini',$opc);
      $opc_fin = empty($opc) || in_array('fin',$opc);
      if( !$opc_ini && !$opc_fin ) $opc_ini = $opc_fin = TRUE;
      foreach( ( isset($ope['lis']) && is_array($ope['lis']) ? $ope['lis'] : range(1,$ide) ) as $pos ){ 
        $htm = "
        <div class='ite jus-cen'>";
          if( $opc_ini ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta {$pos}-1' class='mar_der-1' style='width:24rem;'>";
          if( $opc_fin ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta {$pos}-2' class='mar_izq-1' style='width:24rem;'>";
          $htm .= "
        </div>";
        $_ []= $htm;
      }
      $_ = lis::bar( $_, $ope);          
      break;
    // sello : holon solar => circuitos de telepatía
    case 'uni_sol_cir':
      $ope['lis'] = ['class'=>"ite"];

      foreach( hol::_($ide) as $_cir ){
        $pla = explode('-',$_cir->pla);
        $_pla_ini = hol::_('uni_sol_pla',$pla[0]);
        $_pla_fin = hol::_('uni_sol_pla',$pla[1]);
        $htm = 
        hol::ima($ide,$_cir,['class'=>""])."
        <div>
          <p class='tit'>Circuito <n>$_cir->ide</n><c>:</c> <b class='ide'>$_pla_ini->nom <c>-</c> $_pla_fin->nom</b></p>
          <ul>
            <li>Circuito ".tex::let($_cir->nom)."</li>
            <li><p>".tex::let("$_cir->cod unidades - $_cir->des")."</p></li>
            <li><p>Notación Galáctica<c>,</c> números de código ".tex::let("{$_cir->sel}: ");
            $lis_pos = 0;
            foreach( explode(', ',$_cir->sel) as $sel ){ 
              $lis_pos++; 
              $_sel = hol::_('sel', $sel == 00 ? 20 : $sel);                      
              $htm .= tex::let( $_sel->pod_tel.( $lis_pos == 3 ? " y " : ( $lis_pos == 4 ? "." : ", " ) ) );
            } $htm .= "
            </p></li>
          </ul>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // luna : por poderes
    case 'lun_arm': 
      foreach( hol::_($ide) as $_hep ){
        $_ []= tex::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>".tex::let(", $_hep->pod $_hep->car");
      }        
      break;              
    // luna : lines de fuerza
    case 'lun_fue': 
      foreach( hol::_($ide) as $_lin ){
        $_ []= tex::let("{$_lin->nom}: {$_lin->des}");
      }
      break;
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Proyecto Rinri
  static function rin( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    // plasma : años por oráculos de la profecía
    case 'rad_ani': 
      $ope['lis'] = ['class'=>"ite"];
      $ope['ite'] = ['class'=>"mar_aba-1"];      

      foreach( hol::_('rad') as $_rad ){ $_ []=
        hol::ima("rad",$_rad,['class'=>"mar_der-1"])."
        <p>
          <b class='ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
          <br><q>".tex::let($_rad->rin_des)."<c>.</c></q>
        </p>";
      }
      $_ = lis::ite($_,$ope);
      break;                    
    // luna : días del cubo
    case 'lun_cub':
      foreach( hol::_($ide) as $_cub ){
        $_ []= 
        "<div class='ite'>
          ".hol::ima("sel",$_cub->sel,['class'=>"mar_der-1"])."              
          <div>
            <p class='tit'>Día <n>$_cub->lun</n><c>,</c> CUBO <n>$_cub->ide</n><c>:</c> $_cub->nom</p>
            <p class='des'>$_cub->des</p>
          </div>              
        </div>
        <p class='let-enf tex_ali-cen'>".tex::let($_cub->tit)."</p>
        ".( !empty($_cub->lec) ? "<p class='let-cur tex_ali-cen'>".tex::let($_cub->lec)."</p>" : ""  )."
        <q>".tex::let($_cub->afi)."</q>";
      }        
      break;                    
    // psi-cronos : dias pag + cubo
    case 'psi_lun_dia':
      if( isset($est_ope['atr']) && is_string($est_ope['atr']) ){
        foreach( ['lis'] as $e ){ if( !isset($ope[$e]) ) $ope[$e]=[]; }
        switch( $est_ope['atr'] ){
        // días psi de cuartetos ocultos        
        case 'pag':
          $_ = "
          <table".ele::atr($ope['lis']).">
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
              foreach( hol::_($ide) as $_lun ){ $_ .= "
                <tr>
                  <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                  foreach( explode(', ',$_lun->kin_pag) as $kin ){ $_ .= "
                    <td>".hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                  }$_ .= "   
                </tr>";
              }$_ .= "
            </tbody>
          </table>";        
          break;
        // días psi del cubo - laberinto del guerrero
        case 'cub': 
          $_ = "
          <table".ele::atr($ope['lis']).">
            <tbody>";
              foreach( hol::_($ide) as $_lun ){ $_ .= "
                <tr>
                  <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                  foreach( explode('-',$_lun->kin_cub) as $kin ){ $_ .= "
                    <td>".hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                  }$_ .= "
                  <td>Kines ".tex::let($_lun->kin_cub)."</td>
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
      }
      elseif( empty($est_ope['atr']) ){
        $est_ope['atr'] = [];
        $_ = est::lis("hol.lun", $est_ope, $ope );
      }
      break;
    // psi-cronos : cromaticas entonadas
    case 'psi_cro_arm':
      foreach( [ 1, 2, 3, 4 ] as $arm ){
      
        $cro_arm = hol::_('psi_cro_arm',$arm);

        $_ []= "Cromática <c class='let_col-4-$arm'>$cro_arm->col</c><br>".tex::let("$cro_arm->nom: $cro_arm->des");
      }        
      break;      
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Dinámicas del Tiempo
  static function din( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Tablas del Tiempo
  static function tab( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Atomo del tiempo 
  static function ato( string $ide, array $ope = [] ) : string {
    $_ = [];
    foreach( ['lis'] as $ele ){ if( !isset($ope[$ele]) ) $ope[$ele] = []; }
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    $_ide = explode('-',$ide);
    switch( $ide = $_ide[0] ){
    // cartas del plasma
    case 'fic':
      switch( $_ide[1] ){
      case 'lun':
        $ide = isset($ope['ide']) && is_array($ope['ide']) ? $ope['ide'] : range(1,28);
        $opc = isset($ope['opc']) ? $ope['opc'] : [];
        $opc_ini = empty($opc) || in_array('ini',$opc);
        $opc_fin = empty($opc) || in_array('fin',$opc);
        if( !$opc_ini && !$opc_fin ) $opc_ini = $opc_fin = TRUE;
        foreach( $ide as $pos ){ 
          $cod = num::val($pos,2);
          $htm = "
          <div class='ite jus-cen'>";
            if( $opc_ini ) $htm .= "
            <img src='".SYS_NAV."img/hol/bib/ato/fic/{$cod}-1.gif' alt='Carta {$cod}-1' class='mar_der-1' style='width:20rem;'>";
            if( $opc_fin ) $htm .= "
            <img src='".SYS_NAV."img/hol/bib/ato/fic/{$cod}-2.gif' alt='Carta {$cod}-2' class='mar_izq-1' style='width:20rem;'>";
            $htm .= "
          </div>";
          $_ []= $htm;
        }
        $_ = lis::bar( $_, $ope);                 
        break;
      }        
      break;        
    // 7 plasmas radiales
    case 'rad_pla':
      $ide = "rad";
      $pla_qua = [3,4,7];
      ele::cla($ope['lis'],'ite');
      switch( $_ide[1] ){
      // lineas de fuerza + quantums
      case 'fue':
        foreach( hol::_($ide) as $rad ){
          $fue_pre = hol::_('rad_pla_fue',$rad->pla_fue_pre);
          $fue_pos = hol::_('rad_pla_fue',$rad->pla_fue_pos);
          $_ []= 
          hol::ima($ide,$rad,['class'=>"mar_der-2"])."
          <div>        
            <p><b class='ide'>$rad->nom</b> <b class='col-".substr($rad->col,0,3)."'>$rad->col</b></p>
            <div class='ite'>
              $fue_pre->nom
              ".hol::ima("rad_pla_fue",$fue_pre)."
              <c class='sep'>+</c>
              $fue_pos->nom
              ".hol::ima("rad_pla_fue",$fue_pos)."
              
              <p><c class='sep'>:</c> ".tex::let($rad->pla_fue)." <c>(</c>Días ".tex::let($rad->dia)."<c>)</c></p>
            </div>
          </div>";
          if( in_array($rad->ide,$pla_qua) ){
            $qua = hol::_('rad_pla_qua',$rad->pla_qua);
            $_ []= 
            hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".tex::let($qua->ato_des)."</p>";
          }
        }          
        break;
      // afirmaciones + quantums
      case 'des': 
        foreach( hol::_($ide) as $rad ){
          $_ []= 
          hol::ima($ide,$rad,['class'=>"mar_der-2"])."
          <p>
            ".tex::let("$rad->nom: $rad->pla_des.")."
            <br>
            <q>".tex::let($rad->pla_lec)."</q>
          </p>";            
          if( in_array($rad->ide,$pla_qua) ){
            $qua = hol::_('rad_pla_qua',$rad->pla_qua);
            $_ []= 
            hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".tex::let($qua->pla_des)."</p>";
          }
        }
        break;
      // cubo del radion + quantums
      case 'cub':
        $qua = NULL;
        $qua_ide = 0;
        foreach( hol::_($ide) as $rad ){
          // titulo por quantum
          if( $qua_ide != $rad->pla_qua ){
            $qua = hol::_('rad_pla_qua',$rad->pla_qua); 
            $qua_ide = $rad->pla_qua; $_ []= "
            <p class='tit anc-100 tex_ali-cen'>".tex::let($qua->nom)."</p>";
          }
          $cub = hol::_('rad_pla_cub', $rad->ide);
          $cha = hol::_('uni_hum_cha', $rad->hum_cha);
          $_ []= 
          "<div>".              
            hol::ima('uni_hum_cha',$cha,['class'=>"mar_der-2"]).
            hol::ima('rad_pla_cub',$cub,['class'=>"mar_der-2"])."
          </div>
          <div>
            <p>".tex::let("$rad->nom (Días $rad->dia): $cha->pos Chakra, $cha->cod o $cha->nom")."</p>
            <p>".tex::let("Cubo del Radión - $cub->nom")."</p>
          </div>
          ";
          if( in_array($rad->ide,$pla_qua) ){              
            $_ []= 
            hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".tex::let($qua->cub_des)."</p>";
          }
        }
        break;
      }
      break;
    // 6 tipos de electricidad
    case 'rad_pla_ele': 
      ele::cla($ope['lis'],'ite');
      foreach( hol::_($ide) as $pla_ele ){
        $_ []= 
        hol::ima($ide,$pla_ele,['class'=>"mar_der-2"])."
        <p>
          <b class='ide'>$pla_ele->nom</b> o <b class='ide'>$pla_ele->cod</b>
          <br>
          ".tex::let($pla_ele->des)."
        </p>";
      }
      break;
    // 12 lineas de fuerza
    case 'rad_pla_fue': 
      ele::cla($ope['lis'],'ite');
      foreach( hol::_($ide) as $pla_fue ){
        $ele_pre = hol::_('rad_pla_ele',$pla_fue->ele_pre);
        $ele_pos = hol::_('rad_pla_ele',$pla_fue->ele_pos);
        $_ []= 
        hol::ima($ide,$pla_fue,['class'=>"mar_der-2"])."
        <div>
          <p><b class='ide'>$pla_fue->nom</b></p>
          <div class='val'>
            <b class='mar_hor-1'>$ele_pre->cod</b>
            ".hol::ima("rad_pla_ele",$ele_pre)."
            <c class='ope sep'>$pla_fue->ele_ope</c>
            <b class='mar_hor-1'>$ele_pos->cod</b>              
            ".hol::ima("rad_pla_ele",$ele_pos)."
          </div>                        
        </div>";
      }        
      break;
    // 4 atómos y 2 tetraedros
    case 'lun_pla_ato':
      switch( $_ide[1] ){
      // Atomo telepatico del tiempo
      case 'tie': 
        ele::cla($ope['lis'],'ite');
        $pla_tet = [2,4];
        $tet_ide = 0;
        foreach( hol::_($ide) as $ato ){
          $_ []= 
          hol::ima($ide,$ato,['class'=>"mar_der-2"])."

          <p>Semana <n>$ato->ide</n><c>:</c> Átomo Telepático del <b class='ide'>".tex::let($ato->nom)."</b></p>";
          // tetraedros
          if( in_array($ato->ide,$pla_tet) ){
            $tet_ide++;
            $tet = hol::_('lun_pla_tet',$tet_ide); $_ []= 
            hol::ima('lun_pla_tet',$tet,['class'=>"mar_der-2"])."
            <p>".tex::let($tet->des.".")."</p>";
          }
        }$_ []= 
        arc::ima('hol/ima/lun',['class'=>"mar_der-2 tam-15"])."
        <p>También el Día <n>28</n><c>,</c> la transposición fractal de las ocho caras de los dos tetraedros resulta en la creación del Octaedro de Cristal en el centro de la Tierra<c>.</c></p>";            
        break;
      // Cargas por Colores Semanales 
      case 'car':
        ele::cla($ope['lis'],'ite');
        foreach( hol::_($ide) as $ato ){
          $col = hol::_('lun_arm',$ato->ide)->col;                        
          $_ []= 
          hol::ima($ide,$ato,['class'=>"mar_der-2"])."
          <p>
            Semana <n>$ato->ide</n><c>,</c> <b class='col-".substr($col,0,3)."'>{$col}</b>".tex::let(": $ato->car".".")."
            <br>
            Secuencia ".tex::let($ato->car_sec.".")."
          </p>";
        }
        break;
      // ficha semanal
      case 'hep':
        $ato = hol::_($ide,$ope['ide']);
        $_ = "
        <p class='tit tex_ali-izq'>".tex::let("Semana $ato->ide, Heptágono de la Mente ".tex::art_del($ato->hep))."</p>
        <div class='ite'>
          ".hol::ima($ide, $ato, ['class'=>'mar_der-2'])."
          <ul class='mar_arr-0'>
            <li>".tex::let("Un día = $ato->val.")."</li>
            <li>".tex::let("Valor lunar = $ato->val_lun.")."</li>
            <li>".tex::let("Forma $ato->hep_cub en el Holograma Cúbico 7:28.")."</li>
          </ul>                        
        </div>";
        break;          
      }
      break;
    // 4 semanas: cualidad + poder + kin
    case 'lun_arm':
      foreach( hol::_($ide) as $arm ){
        $ato = hol::_('lun_pla_ato',$arm->ide);          
        $_[]="
        <p class='tit'>$arm->nom<c>,</c> <b class='col-".substr($arm->col,0,3)."'>$arm->col</b><c>:</c></p>          
        <div class='ite'>            
          ".hol::ima($ide,$arm,['class'=>"mar_der-2"])."
          <ul>
            <li>".tex::let($arm->des)."</li>
            <li>".tex::let($arm->tel_des)."</li>
            <li>".( count(explode(', ',$ato->val_kin)) == 1 ? "Código del Kin" : "Códigos de Kines" )." ".tex::let($ato->val_kin)."</li>            
          </ul>
        </div>";
      }
      break;
    // 7 tierras de ur
    case 'lun_pla_tie':
      foreach( hol::_($ide) as $tie ){
        $rad = hol::_('rad',$tie->rad);
        $_[]="
        <p class='tit tex_ali-izq'>".tex::let("$tie->nom, Tierra de UR $tie->ide")."</p>
        <div class='ite'>
          ".hol::ima('rad',$tie->rad,['class'=>"mar_der-2"])."
          <p>
            <q>$tie->des</q>
            <br>".tex::let("Día $tie->dia, $tie->tel, Tablero del Plasma.")."
            <br>".tex::let("Plasma Radial $rad->ide, $rad->nom: $rad->pla_fue")."
            <br>".tex::let("( $tie->pos última Luna, $tie->pos Luna Mística )")."
          </p>
        </div>";
      }
      break;
    // ejes del Cubo Primigenio y el Átomo Telepático del Tiempo
    case 'lun_pla_eje':
      ele::cla($ope['lis'],'ite');
      foreach( hol::_($ide) as $eje ){
        $tie = explode(', ',$eje->tie);
        $ini = hol::_('lun_pla_tie',$tie[0]);
        $fin = hol::_('lun_pla_tie',$tie[1]);
        $_[]=
        hol::ima('rad',$ini->rad,['class'=>"mar_der-2"]).
        hol::ima('rad',$fin->rad,['class'=>"mar_der-2"])."
        <div>
          <p class='tit'>Eje $eje->nom</p>
          <p>".tex::let("{$ini->ide}° Tierra de UR $ini->nom y {$fin->ide}° Tierra de UR $fin->nom")."</p>
        </div>
        ";
      }
      break;
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
  // Sincronotron
  static function umb( string $ide, array $ope = [] ) : string {
    $_ = [];
    $lis_tip = "val"; $lis_pos = 0; $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
    switch( $ide ){
    }
    return is_array($_) ? dat::lis( $_, $ide, $lis_tip, $ope ) : $_;
  }
}