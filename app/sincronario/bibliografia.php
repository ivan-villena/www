<?php


function listado( string $lib, string $ide, array $ope = [] ){
  $_ = [];
  $_eje = "listado('$lib','$ide'";
  $lis_pos = 0;
  $est_ope = [ 'opc'=>['htm','cab_ocu'] ];
  switch( $lib ){
  // glosario
  case 'ide':
    switch( $ide ){
    case 'lib':
      $ope['opc'] = ['tog','ver','tog-dep'];
      // busco libros
      foreach( Dat::get("app_art",[ 'ver'=>"esq = 'hol' AND cab = 'bib'" ]) as $_lib ){
        // busco terminos
        if( !empty( $_pal_lis = Dat::get("app_ide",[ 
          'ver'=>"`esq` = 'hol' AND `ide`='$_lib->ide'", 
          'ord'=>"`ide` ASC, `nom` ASC"
        ]) ) ){
          $_pal_ite = [];
          foreach( $_pal_lis as $_pal ){
            $_pal_ite[] = [ 'ite'=>$_pal->nom, 'lis'=>[ Tex::let($_pal->des) ] ];
          }
          $_ []= [
            'ite'=>$_lib->nom, 'lis'=>$_pal_ite
          ];
        }
      }      
      break;
    }
    break;
  // tutorial
  case 'tut':
    switch( $ide ){
    // heptadas
    case 'lun_arm':
      foreach( Hol::_($ide) as $_hep ){ $_ []= 
        Tex::let("$_hep->nom (")."<b class='col-4-$_hep->ide'>$_hep->des_col</b>".Tex::let("): $_hep->des");
      }
      break;
    // plasmas y descripciones
    case 'rad':
      $_ = Dat::inf_des('hol','rad',[ 'des_fue', 'des_pod' ]);
      break;
    // plasmas y afirmaciones
    case 'rad-lec':
      $_ = Dat::inf_des('hol','rad',[ 'pla_lec' ]);
      break;      
    // kines
    case 'kin_ima':
      foreach( $ope as $kin ){
        $_ []= Hol::ima('kin',$kin,['class'=>"mar_der-1"]);
      }
      $ope = [ 'lis'=>['class'=>"doc_val mar-aut"] ];
      break;
    // tonos
    case 'ton':
      $est_ope['atr'] = ['ide','nom','des_car','des_pod','des_acc'];
      $_ = Dat::lis("hol.ton",$est_ope);
      break;
    case 'sel':
      $est_ope['atr'] = ['ide','nom','des_pod','des_acc','des_car'];
      $_ = Dat::lis("hol.sel",$est_ope);
      break;
    // patrones guia
    case 'par_gui':
      $_ = "
      <table class='mar-aut'>

        <thead>
          <tr>
            <th>Para los portales galácticos con tonos:</th>
            <th>El Kin guía es:</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td><n>1</n><c>,</c> <n>6</n> o <n>11</n><c>:</c></td>
            <td>el mismo sello solar como el sello del kin destino</td>
          </tr>
          <tr>
            <td><n>2</n><c>,</c> <n>7</n> o <n>12</n><c>:</c></td>
            <td><n>12</n> sellos adelante <c>(</c>o <n>8</n> sellos atrás<c>)</c> del kin destino</td>
          </tr>
          <tr>
            <td><n>3</n><c>,</c> <n>8</n> o <n>13</n><c>:</c></td>
            <td><n>4</n> sellos adelante <c>(</c>o <n>16</n> sellos atrás<c>)</c> del kin destino</td>
          </tr>
          <tr>
            <td><n>4</n> o <n>9</n><c>:</c></td>
            <td><n>4</n> sellos atrás <c>(</c>o <n>16</n> sellos adelante<c>)</c> del kin destino</td>
          </tr>
          <tr>
            <td><n>5</n> o <n>10</n><c>:</c></td>
            <td><n>8</n> sellos adelante <c>(</c>o <n>12</n> sellos atrás<c>)</c> del kin destino</td>
          </tr>          
        </tbody>
      </table>";
      break;
    // patrones diario
    case 'par-dia':
      $_ = "
      <ul>
        <li>Primera Mirada<c>:</c> De Medianoche al Amanecer <c>(</c>Kin Análogo<c>)</c> </li>
        <li>Segunda Mirada<c>:</c> Amanecer a Mediodía <c>(</c>Kin Guía<c>)</c> </li>
        <li>Tercera Mirada<c>:</c> Mediodía al Atardecer <c>(</c>Kin Antípoda<c>)</c> </li>
        <li>Cuarta Mirada<c>:</c> Atardecer a Medianoche <c>(</c>Kin Oculto)</li>
      </ul>";
      break;
    }    
    break;
  // tierra_en_ascenso
  case 'asc': 
    break;
  // factor_maya
  case 'fac':
    switch( $ide ){
    // tonos : rayo de pulsacion
    case 'ton':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('ton') as $_ton ){ $_ []= "
        ".Hol::ima("ton",$_ton,['class'=>"mar_der-1"])."
        <p>
          <n>".intval($_ton->ide)."</n><c>.</c> El Rayo de Pulsación ".preg_replace("/^(del|de la)/","$1<b class='let-ide'>",Tex::art_del($_ton->gal))."</b>
        </p>";
      }        
      break;
    // tonos : simetría especular
    case 'ton_sim': 
      foreach( Hol::_('ton_sim') as $_sim ){ $_ []= "
        <p>".Tex::let($_sim->des)."</p>";
      }        
      break;
    // sellos : posiciones direccionales
    case 'sel_cic_dir':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_($ide) as $_dir ){ $_ []=
        Hol::ima($ide,$_dir,['class'=>"mar_der-1 tam-11"])."
        <div>
          <p><b class='let-ide'>".explode(' ',$_dir->nom)[1]."</b><c>:</c></p>
          <ul>
            <li><p><c>-></c> ".Tex::let($_dir->des)."</p></li>
            <li><p><c>-></c> Color<c>:</c> <c class='col-4-{$_dir->ide}'>{$_dir->des_col}</c></p></li>
          </ul>
        </div>";
      }
      break;
    // sellos : desarrollo del ser con etapas evolutivas
    case 'sel_cic_ser':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('sel') as $_sel ){
        if( $lis_pos != $_sel->cic_ser ){
          $lis_pos = $_sel->cic_ser;
          $_ser = Hol::_($ide,$lis_pos);
          $des_val = explode(', ',$_ser->des);
          $des = !empty($des_val[1]) ? " <c>-</c> Etapa {$des_val[1]}" : "";
          $_ []= "
          <p class='tit'>
            Desarrollo".Tex::art_del($_ser->nom)."{$des}
          </p>";
        }                
        $_dir = Hol::_('sel_cic_dir',$_sel->arm_raz); $_ []=

        Hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p><n>{$_sel->ide}</n><c>.</c> <b class='let-ide'>{$_sel->nom_may}</b><c>:</c> ".explode(' ',$_dir->nom)[1]."<c>.</c>
          <br>".Tex::let($_sel->cic_ser_des)."
        </p>";
      }        
      break;
    // sellos : familias ciclicas
    case 'sel_cic_luz': 
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('sel') as $_sel ){
        if( $lis_pos != $_sel->cic_luz ){
          $lis_pos = $_sel->cic_luz;
          $_luz = Hol::_($ide,$lis_pos); $_ []= "
          <p><b class='tit'>".Tex::let_may("Familia Cíclica ".Tex::art_del($_luz->nom)."")."</b>
            <br><b class='des'>{$_luz->des}</b><c>.</c>
          </p>";
        }                
        $_dir = Hol::_('sel_cic_dir',$_sel->arm_raz);                 
        
        $_ []= 

        Hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p>".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='let-ide'>{$_sel->nom_may}</b><c>.</c>
          <br>".Tex::let($_sel->cic_luz_des)."
        </p>";                
      }          
      break;
    // kin : katun del kin
    case 'kin':
      $_kin = Hol::_('kin',$ope['ide']);
      $_sel = Hol::_('sel',$_kin->arm_tra_dia);
      $_pol = Hol::_('uni_flu_res',$_sel->flu_res);
      $_pla = Hol::_('uni_sol_pla',$_sel->sol_pla);
      $_ond = Hol::_('kin_nav_ond',$_kin->nav_ond);
      $_arq = Hol::_('sel',$_ond->sel);
      $ton = intval($_kin->nav_ond_dia);
      $_ = "
      <div class='doc_val'>

        ".Hol::ima("kin",$_kin)."

        <p class='tit tex_ali-izq'>
          Katún <n>".intval($_sel->ide-1)."</n><c>:</c> Kin <n>$ton</n> <b class='let-ide'>$_sel->nom_may</b>".( !empty($_kin->pag) ? "<c>(</c> Activación Galáctica <c>)</c>" : '' )."<c>.</c>
        </p>
      
      </div>
      <ul>
        <li>Regente Planetario<c>:</c> $_pla->nom $_pol->des_flu<c>.</c></li>
        <li>Etapa <n>$ton</n><c>,</c> Ciclo $_arq->nom_may<c>.</c></li>
        <li>Índice Armónico <n>".Num::val_int($_kin->fac)."</n><c>:</c> período ".Tex::let($_kin->fac)."</li>
        <li>".Tex::let($_sel->arm_tra_des)."</li>
      </ul>";
      break;
    // kin : portales de activacion
    case 'kin_pag':
      $arm_tra = 0;
      $ope['lis'] = ['class'=>"ite"];
      foreach( array_filter(Hol::_('kin'), function( $ite ){ return !empty($ite->pag); }) as $_kin ){ 
        $lis_pos++; 
        $_sel = Hol::_('sel',$_kin->arm_tra_dia);
        if( $arm_tra != $_kin->arm_tra ){
          $arm_tra = $_kin->arm_tra;
          $_tra = Hol::_('kin_arm_tra',$arm_tra); $_ []= "

          ".Hol::ima("ton",$arm_tra,['class'=>"mar_der-1"])."

          <p class='tit'>".Tex::let("Ciclo ".($num = intval($_tra->ide)).", Baktún ".( $num-1 ))."</p>";
        }
        $_ []= "

        ".Hol::ima("kin",$_kin,['class'=>"mar_der-1"])."

        <p>
          <n>{$lis_pos}</n><c>.</c> <b class='let-ide'>{$_sel->nom_may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
          <br>".Tex::let(Num::val_int($_kin->fac))."
        </p>";
      }          
      break;
    // kin : 1 trayectoria con detalles por katun ( ciclos del modelo morfogenetico )
    case 'kin_fec':
      $ond = 0;
      $_ = "
      <table>";
        if( !empty($ope['tit']) ){ $_.="
          <caption>".( !empty($ope['tit']['htm']) ? "<p class='tit'>".Tex::let($ope['tit']['htm'])."</p>" : '' )."</caption>";
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
        foreach( ( !empty($dat) ? $dat : Hol::_('kin') ) as $_kin ){

          if( $ond != $_kin->nav_ond ){
            $_ond = Hol::_('kin_nav_ond', $ond = $_kin->nav_ond); 
            $_sel = Hol::_('sel', $_ond->sel);
            $_ .= "
            <tr class='tex_ali-izq'>
              <td>
                ".Hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."
              </td>
              <td colspan='3'>{$_sel->nom_may}<c>:</c> ".Tex::let($_ond->fac)." ".Tex::let($_ond->fac_des)."</td>
            </tr>";
          }
          $_sel = Hol::_('sel',$sel = intval($_kin->arm_tra_dia));
          $_ .= "
          <tr data-kin='{$_kin->ide}'>
            <td>
              Etapa <n>".($ton = intval($_kin->nav_ond_dia))."</n>
            </td>
            <td></td>
            <td>
              <n>$sel</n><c>.</c><n>$ton</n> <b class='let-ide'>$_sel->nom_may</b><c>:</c>
              <br><n>".Num::val_int($_kin->fac)."</n><c>,</c> año <n>".Num::val_int($_kin->fac_ini)."</n>
            </td>
            <td>
              ".Tex::let($_sel->arm_tra_des)."
            </td>
          </tr>";
        }$_.="
        </tbody>

      </table>";
      break;
    // kin : 13 baktunes
    case 'kin_arm_tra':

      foreach( Hol::_($ide) as $_tra ){
        $htm = "
        <div class='doc_val'>
          ".Hol::ima("ton",$_tra->ide,['class'=>"mar_der-1"])."
          <p>
            <b class='tit'>Baktún <n>".(intval($_tra->ide)-1)."</n><c>.</c> Baktún ".Tex::art_del($_tra->tit)."</b>
            <br>".Tex::let($_tra->fac)." <c><=></c> ".Tex::let($_tra->may)."
          </p>
        </div>";
        $lis = [];
        foreach( explode('; ',$_tra->lec) as $ite ){
          $lis []= "<c>-></c> ".Tex::let($ite);
        }
        $_[] = $htm.Lis::dep($lis,[ 'lis'=>['class'=>"pun"] ]);
      }          
      break;
    // kin : 20 katunes
    case 'kin_arm_sel':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_('sel') as $_sel ){ $_ [] = "

        ".Hol::ima("sel_arm_tra",$_sel,['class'=>"mar_der-2"])."

        <p>
          <b class='let-ide'>{$_sel->nom_may}</b><c>:</c> Katún <n>".(intval($_sel->ide)-1)."</n>
          <br>{$_sel->arm_tra_des}
        </p>";
      }
      break;
    // kin : sellos guardianes de la evolucion mental
    case 'kin_cro_est':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('kin_cro_est') as $_est ){
        $_sel = Hol::_('sel',$_est->sel); 
        $_dir = Hol::_('sel_cic_dir',$_est->ide); $_ []= 
        
        Hol::ima("sel",$_sel,['class'=>"mar_der-1"])."

        <p><n>".intval($_sel->ide)."</n><c>°</c> Signo<c>.</c> ".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='let-ide'>{$_sel->nom_may}</b><c>.</c>
          <br><b class='let-val des'>{$_est->nom}</b><c>:</c> {$_est->des}<c>.</c>
        </p>";
      }        
      break;
    // kin : guardianes por estacion cromatica
    case 'kin_cro_sel':
      foreach( Hol::_($ide) as $_est ){
        $_sel = Hol::_('sel',$_est->sel); $htm = "
        <div class='doc_val'>
          ".Hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
          <p>
            <b class='tit'>ESTACIÓN ".Tex::let_may(Tex::art_del("el {$_est->des_dir}"))."</b>
            <br>Guardían<c>:</c> <b class='let-ide'>{$_sel->nom_may}</b> <c>(</c> {$_sel->nom} <c>)</c>
          </p>
        </div>";
        $lis = [];
        foreach( Hol::_('kin_cro_ond') as $_ond ){ $lis []= "

          ".Hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."

          <p>{$_ond->fac}<c>.</c>
            <br><n>".intval($_ond->ton)."</n> {$_sel->nom_may}
          </p>";
        }                
        $_[] = $htm.Lis::dep($lis,[ 'lis'=>['class'=>'ite'] ]);
      }          
      break;
    // kin : ciclo ahau / onda encantada
    case 'kin_nav_sel':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_('kin_nav_ond') as $_ond ){ 
        $_sel = Hol::_('sel',$_ond->sel); $_ [] = "

        ".Hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-2"])."

        <p>
          <n>{$_ond->ide}</n><c>.</c> <b class='let-ide'>{$_sel->nom_may}</b><c>:</c> ".Tex::let($_ond->fac)."
          <br>".Tex::let($_ond->fac_des)."
        </p>";
      }            
      break;
    }    
    break;
  // encantamiento_del_sueño
  case 'enc': 
    switch( $ide ){
    // tonos : descripciones
    case 'ton':
      $est_ope['atr'] = ['ide','nom','des','des_acc'];
      $_ = Dat::lis("hol.ton", $est_ope, $ope );
      break;
    // tonos : aventura de la onda encantada 
    case 'ton_ond':
      $_atr = array_merge(
        [ 
          'ima'=>Obj::val_atr(['ide'=>'ima','nom'=>''])
        ], 
        Dat::get_est('hol',"ton",'atr',[ 'ide','ond_pos','ond_pod','ond_man'])
      );
      // cargo valores
      foreach( ( $_dat = Obj::val_atr(Hol::_('ton')) ) as $_ton ){
        $_ton->ima = [ 'htm'=>Hol::ima("ton",$_ton,[ 'class'=>"tam-5 mar-1" ]) ];
        $_ton->ide = "Tono {$_ton->ide}";
      }
      // cargo titulos
      $ond = 0;
      $_tit = [];
      foreach( $_dat as $lis_pos => $_ton ){
        if( $_ton->ond_enc != 0 && $ond != $_ton->ond_enc ){              
          $_ond = Hol::_('ton_ond',$ond = $_ton->ond_enc);
          $_tit[$lis_pos] = $_ond->des;
        }
      }
      $_ = Dat::lis($_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit, 'opc'=>['cab_ocu'] ], $ope);              
      break;
          
    // tonos : pulsares dimensionales
    case 'ton_dim':
      foreach( Hol::_('ton_dim') as $_dat ){ $htm = "
        <p>
          <n>{$_dat->ide}</n><c>.</c> <b class='let-ide'>Pulsar de la {$_dat->des_dim} dimensión</b><c>:</c> <b class='let-val'>Dimensión {$_dat->nom}</b>
          <br>Tonos ".Tex::let("{$_dat->ton}: {$_dat->des_ond}")."
        </p>
        <div class='doc_val'>
          ".Hol::ima("ton_dim",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
            foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= Hol::ima("ton",$ton,['class'=>"mar_hor-2"]); } $htm .= "
          <c class='_lis fin'>}</c>
        </div>
        ";
        $_ []= $htm;
      }        
      break;
    // tonos : pulsares matiz
    case 'ton_mat':
      foreach( Hol::_('ton_mat') as $_dat ){ $htm = "
        <p><n>{$_dat->ide}</n><c>.</c> <b class='let-ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='let-val'>".Tex::let($_dat->des_cod)."</b><c>:</c>
          <br>Tonos ".Tex::let("{$_dat->ton}: {$_dat->des_ond}")."
        </p>
        <div class='doc_val'>
          ".Hol::ima("ton_mat",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
            foreach( explode(', ',$_dat->ton) as $ton ){ 
              $htm .= Hol::ima("ton",$ton,['class'=>"mar_hor-2"]); 
            } $htm .= "
          <c class='_lis fin'>}</c>
        </div>";
        $_ []= $htm;
      }        
      break;
    // sello : colocacion armónica => razas raíz cósmica
    case 'sel_arm_raz':
      $sel = 1;
      foreach( Hol::_($ide) as $_dat ){
        $_raz_pod = Hol::_('sel',$_dat->ide)->des_pod; 
        $htm = "
        <p class='tit'>Familia <b class='col-4-{$_dat->ide}'>{$_dat->nom}</b><c>:</c> de la <b class='let-ide'>Raza Raíz ".Tex::let_min($_dat->nom)."</b></p>
        <p>Los {$_dat->des_pod}dores<c>.</c> Nota clave<c>:</c> ".explode(' ',$_raz_pod)[1]."</p>
        <ul class='ite'>";
        foreach( Hol::_('sel_arm_cel') as $lis_pos ){
          $_sel = Hol::_('sel',$sel); $htm .= "
          <li>
            ".Hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
            <p>
              <n>{$lis_pos->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
              <br>".Tex::let($_sel->arm_raz_des)."
            </p>
          </li>";
          $sel += 4;
          if( $sel > 20 ) $sel -= 20;                  
        }        
        $htm.="
        </ul>
        ".Tex::let(Tex::let_pal($_raz_pod)." ha sido ".Tex::art_gen("realizado",$_raz_pod).".")."";
        $_ []= $htm;
        $sel++;
      }        
      break;
    // sello : colocacion armónica => células del tiempo
    case 'sel_arm_cel':
      $lis_pos = 1;

      foreach( Hol::_($ide) as $_dat ){ $htm = "
        <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='let-ide'>{$_dat->nom}</b></p>
        <p class='des'>".Tex::let($_dat->des)."</p>
        <ul class='ite'>";
        foreach( Hol::_('sel_arm_raz') as $cro ){ $_sel = Hol::_('sel',$lis_pos); $htm .= "
          <li>
            ".Hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
            <p>
              <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
              <br>".Tex::let($_sel->arm_cel_des)."
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
      foreach( Hol::_($ide) as $_dat ){
        $ele_nom = explode(' ',$_dat->nom)[1]; $htm = "
        <p class='tit'>
          <b class='let-ide'>Clan ".Tex::art_del($_dat->nom)."</b>".Tex::let(": Cromática {$_dat->des_col}.")."
        </p>
        ".( !empty($_dat->des_ini) ? "
        <p class='des'>
          ".Tex::let($_dat->des_ini)."
        </p>" : '' )."
        <ul class='ite'>";
          for( $fam=1; $fam<=5; $fam++ ){ 
            $_sel = Hol::_('sel',$sel); 
            $_fam = Hol::_('sel_cro_fam',$fam); $htm .= "
            <li>
              ".Hol::ima("sel",$_sel,[ 'class'=>"mar_der-1" ])."
              <p>
                <n>{$sel}</n><c>.</c> <b class='let-ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
                <br>".Tex::let($_sel->cro_ele_des)."
              </p>
            </li>";
            $sel++;
            if( $sel > 20 ) $sel -= 20;
          }$htm .= "
          <li>
            <p class='des'>".Tex::let($_dat->des)."</p>
          </li>
        </ul>";
        $_ []= $htm;
      }          
      break;
    // sello : colocacion cromática => familias terrestres
    case 'sel_cro_fam':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_('uni_pla_cen') as $_pla ){
        $_hum = Hol::_('uni_hum_cen',$_pla->ide);
        $_fam = Hol::_($ide,$_pla->fam);
        $htm = 
        Hol::ima("uni_pla_cen",$_pla,['class'=>"mar_der-2",'style'=>"min-width: 9rem; height:7rem;"])."
        <div>
          <p><b class='let-ide'>Kin {$_fam->nom}</b><c>:</c> {$_fam->des_rol}</p>
          <div class='doc_val fic mar-2'>
            ".Hol::ima("uni_hum_cen",$_hum)."
            <c class='sep'>=></c>
            <c class='_lis ini'>{</c>";
              foreach( explode(', ',$_fam->sel) as $sel ){
                $htm .= Hol::ima("sel",$sel,['class'=>"mar_hor-2"]);
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

      foreach( Hol::_($ide) as $_dat ){
        if( $val_sel || in_array($_dat->ide,$val) ){ 
          $htm = "
          <p class='tit'><b class='let-ide'>{$_dat->nom}</b><c>:</c> Célula Solar ".Num::dat($_dat->ide,'nom')."<c>.</c></p>                  
          <ul est='sol_pla'>";
          for( $sol_pla=1; $sol_pla<=2; $sol_pla++ ){
            $_pla = Hol::_('uni_sol_pla',$pla);
            $_sel = Hol::_('sel',$sel);
            $_par = Hol::_('sel',$_sel->par_ana); 
            if( $orb != $_pla->orb ){
              $_orb = Hol::_('uni_sol_orb',$orb = $_pla->orb); $htm .= "
              <li>Los <n>5</n> <b class='let-ide'>planetas {$_orb->nom}es</b><c>:</c> ".Tex::let($_orb->des)."</li>";                        
            }
            $htm .= "
            <li>
              <p><b class='let-ide'>{$_pla->nom}</b><c>,</c> <n>{$pla}</n><c>°</c> órbita<c>:</c></p>
              <div class='doc_ite'>

                ".Hol::ima("uni_sol_pla",$_pla,['class'=>"mar_der-1"])."

                <ul class='ite' est='sel'>
                  <li>
                    ".Hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
                    <p>
                      <b class='let-val'>Dentro</b><c>:</c> Sello Solar <n>{$_sel->ide}</n>
                      <br>".Tex::let($_sel->sol_pla_des)."
                    </p>
                  </li>
                  <li>
                    ".Hol::ima("sel",$_par,['class'=>"mar_der-1"])."
                    <p>
                      <b class='let-val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                      <br>".Tex::let($_par->sol_pla_des)."
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
      $lis_pos = 1;
      foreach( Hol::_($ide) as $_dat ){
        $_fam = Hol::_('sel_cro_fam',$_dat->fam); $htm= "
        <div class='doc_val'>
          ".Hol::ima("sel_cro_fam",$_fam,['class'=>"mar_der-1"])."
          <c class='sep'>=></c>
          <c class='_lis ini'>{</c>";
          foreach( explode(', ',$_dat->sel) as $sel ){
            $htm .= Hol::ima("sel",$sel,['class'=>"mar_hor-1"]);
          }$htm.="
          <c class='_lis fin'>}</c>
          <c class='sep'>:</c>
        </div>
        <p>
          <n>{$_dat->ide}</n><c>.</c> El Kin <b class='let-ide'>{$_fam->nom}</b><c>:</c>
          <br><q>{$_dat->des_fun} desde el {$_dat->nom}</q>
        </p>";
        $_ []= $htm;
      }        
      break;
    // sello : holon planetario => rol de familias terrestres
    case 'uni_pla_pos':
      $ope['lis'] = ['class'=>"ite"];
      $_fam_sel = [
        1=>[ 20,  5, 10, 15 ],
        2=>[  1,  6, 11, 16 ],
        3=>[ 17,  2,  7, 12 ],
        4=>[ 18,  3,  8, 13 ],
        5=>[ 14, 19,  4,  9 ]
      ];
      foreach( Hol::_('uni_pla_cen') as $_dat ){
        $_fam = Hol::_('sel_cro_fam',$_dat->fam);
        $htm = "
        <div class='tex_ali-cen'>
          <p>Kin <b class='let-ide'>{$_fam->nom}</b></p>
          ".Hol::ima("uni_pla_cen",$_dat,['class'=>"mar_der-1",'style'=>"min-width: 17rem; height: 11rem;"])."
        </div>
        <ul class='ite'>";
          foreach( $_fam_sel[$_dat->ide] as $sel ){
            $_sel = Hol::_('sel',$sel);
            $_pla_mer = Hol::_('uni_pla_mer',$_sel->pla_mer);
            $_pla_hem = Hol::_('uni_pla_hem',$_sel->pla_hem);
            $htm .= "
            <li>
              ".Hol::ima("sel",$_sel,['class'=>"mar_der-1"])."
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
      $ele_tit = [];
      $col = 4;
      foreach( Hol::_('uni_hum_ext') as $_ext ){
        $_ele = Hol::_('sel_cro_ele',$_ext->ele); 
        $nom = explode(' ',Tex::art_del($_ele->nom)); $cla = array_pop($nom); $nom = implode(' ',$nom);
        $ele_tit[$lis_pos] = [ 
          'eti'=>"div", 'class'=>"ite", 'htm'=> Hol::ima("uni_hum_ext",$_ext,['class'=>"mar_der-1"])."                  
          <p class='tit tex_ali-izq'><b class='let-ide'>$_ext->nom</b><c>:</c>
            <br>Clan {$nom} <c class='col-4-$col'>{$cla} $_ele->des_col</c></p>" 
        ];
        $lis_pos += 5; 
        $col = Num::val_ran($col+1,4);
      }
      $sel_lis = [];
      foreach( Hol::_('sel_cod') as $_sel ){
        $_fam = Hol::_('sel_cro_fam',$_sel->cro_fam);
        $_hum_ded = Hol::_('uni_hum_ded',$_fam->hum_ded);
        $sel_lis []= Obj::val_atr([ 
          'hum_ded'=>$_hum_ded->nom, 
          'nom'=>"Tribu ".Tex::art_del($_sel->nom)." $_sel->des_col", 
          'ima_nom'=>[ 'htm'=>Hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
          'des_cod'=>$_sel->des_cod,
          'ima_cod'=>[ 'htm'=>Hol::ima("sel_cod",$_sel,['class'=>"mar-1"]) ]
        ]);
      }
      $_ = Dat::lis($sel_lis,[ 'tit'=>$ele_tit, 'opc'=>['cab_ocu'] ]);
      break;
    // sello : holon humano => extremidades del humano
    case 'uni_hum_ext':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_($ide) as $_dat ){
        $_ele = Hol::_('sel_cro_ele',$_dat->ele); $_ []= "

          ".Hol::ima("uni_hum_ext",$_dat,['class'=>"mar_der-1"])."

          <p><b class='let-ide'>Cromática ".Tex::art_del($_ele->nom)."</b><c>:</c>
            <br>{$_dat->nom}
          </p>";
      }        
      break;
    // sello : holon humano => dedos del humano
    case 'uni_hum_ded':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_($ide) as $_dat ){
        $_fam = Hol::_('sel_cro_fam',$_dat->fam); $_ []= "

          ".Hol::ima("uni_hum_ded",$_dat,['class'=>"mar_der-1"])."

          <p><b class='let-ide'>Kin {$_fam->nom}</b><c>:</c> <b class='let-val'>{$_fam->des_cod}</b>
            <br>{$_dat->nom}
          </p>";
      }        
      break;
    // sello : holon humano => centros galácticos del humano
    case 'uni_hum_cen':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_($ide) as $_hum ){
        $_fam = Hol::_('sel_cro_fam',$_hum->fam);
        $_ []= "

        ".Hol::ima($ide,$_hum,['class'=>"mar_der-1"])."

        <p><b class='let-ide'>Kin {$_fam->nom}</b><c>:</c> <b class='let-val'>{$_fam->des_cod}</b>
          <br>".Tex::art($_hum->nom)." <c>-></c> {$_hum->des_pod}
        </p>";
      }            
      break;
    // encantamiento : libro del kin        
    case 'kin':
      $_ = "
      <!-- libro del kin -->
      <form class='doc_inf' esq='hol' est='$ide'>

        <div class='doc_val'>

          <fieldset class='doc_val'>

            ".Lis::ope_tog()."

            ".Doc::var('atr',"hol.kin.ide",[ 'nom'=>"ver el kin", 'ope'=>[ 
              'title'=>"Introduce un número de kin...", 'oninput'=>"{$_eje},'val',this);" 
            ]])."
          </fieldset>

          <fieldset class='app_ope'>
            ".Fig::ico('dat_fin',[ 'eti'=>"button", 'type'=>"reset", 'title'=>"Vaciar Casillero...", 'onclick'=>"{$_eje},'fin',this);" ])."
            ".Fig::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"{$_eje},'nav',this);" ])."
          </fieldset>

        </div>

        <output class='hol-kin'></output>
        
      </form>
              
      <nav>";
        $_nav_cas = 0; $_nav_ond = 0;
        $arm_tra = 0; $arm_cel = 0;
        $cro_est = 0; $cro_ele = 0;
        $gen_enc = 0; $gen_cel = 0;
        foreach( Hol::_('kin') as $_kin ){

          // castillo
          if( $_kin->nav_cas != $_nav_cas ){
            $_nav_cas = $_kin->nav_cas;
            $_cas = Hol::_('kin_nav_cas',$_kin->nav_cas); 
            if( $_nav_cas != 1 ){ $_ .= "
                </section>

              </section>
              ";
            }$_ .= "
            ".Doc::val(['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."
            <section data-kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>
              <p cas='{$_cas->ide}'>".Tex::let("Corte {$_cas->des_cor}: {$_cas->des_mis}")."</p>
            ";
          }
          // génesis
          if( $_kin->gen_enc != $gen_enc ){
            $gen_enc = $_kin->gen_enc;
            $_gen = Hol::_('kin_gen_enc',$_kin->gen_enc); $_ .= "
            <p class='tit' data-gen='{$_gen->ide}'>GÉNESIS ".Tex::let_may($_gen->nom)."</p>";
          }
          // onda encantada
          if( $_kin->nav_ond != $_nav_ond ){
            $_nav_ond = $_kin->nav_ond;
            $_ond = Hol::_('kin_nav_ond',$_kin->nav_ond);
            $_sel = Hol::_('sel',$_ond->sel); 
            $ond = Num::val_ran($_ond->ide,4);

            if( $_nav_ond != 1 && $ond != 1 ){ $_ .= "
              </section>";
            }
            $_ .= "
            ".Doc::val([
              'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 
              'htm'=> Tex::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
            ])."
            <section data-kin_nav_ond='{$_ond->ide}'>
              <p class='tex-enf' ond='{$_ond->ide}'>Poder ".Tex::art_del($_sel->des_pod)."</p>";
          }
          // célula armónica : titulo + lectura
          if( $_kin->arm_cel != $arm_cel ){
            $arm_cel = $_kin->arm_cel;
            $_cel = Hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
            </section>

            ".Doc::val([
              'eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,
              'htm'=>"<b class='let-ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>".Tex::let(Tex::let_may($_cel->des))
            ])."
            <section data-kin_arm_cel='{$_cel->ide}'>
            ";
          }
          // kin : ficha + nombre + encantamiento
          $_ .= "
          <div data-kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
            <div class='hol-kin'>
              ".Hol::ima("kin",$_kin->ide,['class'=>'mar-aut'])."
              <p>
                <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='col-4-{$_kin->arm_cel_dia}'>".Tex::let(Tex::let_may($_kin->nom))."</c>
                <br>".Tex::let($_kin->des)."
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

      foreach( Hol::_('kin_arm_tra') as $_tra ){

        $_lis_cel = [];
        foreach( Hol::_('sel_arm_cel') as $_cel ){
          $arm_cel++;
          $_cel = Hol::_('kin_arm_cel',$arm_cel); $_lis_cel []= "
          <a class='tex' href='#kin_arm_cel-{$_cel->ide}-'>
            <n>{$_cel->ide}</n><c>.</c> <b class='let-ide'>{$_cel->nom}</b>".Tex::let(": {$_cel->des}")."
          </a>";
        }        
        $_lis []= [
          'ite'=>"Trayectoria $_tra->ide: Tono $_tra->ton, $_tra->ton_des",
          'lis'=>$_lis_cel
        ];
      }
      // Ele::cla( $ope['nav'], "dis-ocu" );
      $ope['opc'] = ['tog','ver'];
      $_ = "

      ".Doc::val(
        [ 'eti'=>'h3', 'htm'=> Tex::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], 
        [ 'ico'=>['class'=>"ocu"] ]
      )."
      <nav".Ele::atr($ope['nav']).">

        ".Lis::dep($_lis,$ope)."

      </nav>";    
      break;        
    // kin : espectros galácticos
    case 'kin_cro':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('kin_cro_est') as $_est ){ 
        $_sel = Hol::_('sel',$_est->sel); $_ []= "
        ".Hol::ima("sel",$_sel,['class'=>"mar_der-2"])."
        <p>
          <n>{$_est->ide}</n><c>.</c> El espectro galáctico <b class='col-4-{$_est->ide}'>{$_est->des_col}</b><c>:</c> 
          Estación ".Tex::art_del($_sel->nom)."
        </p>";
      }          
      break;
    // kin : aventura por guardián
    case 'kin_cro_sel':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('kin_cro_ond') as $_ond ){ $_ []= "
        ".Hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
        <p>
          Tono <n>".intval($_ond->ton)."</n> de la familia terrestre polar<c>:</c> 
          {$_ond->nom} <n>1</n> de los <n>4</n> Espectros Galácticos<c>.</c>
        </p>";
      }
      break;
    // kin : aventura por estaciones
    case 'kin_cro_ton':
      $ope['lis'] = ['class'=>"ite"];
      foreach( Hol::_('kin_cro_ond') as $_ond ){ $_ []= "
        ".Hol::ima("ton",$_ond->ton,['class'=>"mar_der-2"])."
        <p>
          Tono <n>".intval($_ond->ton)."</n><c>:</c> 
          {$_ond->nom} <n>".($_ond->cue*5)."</n> Kines <c>(</c> <n>{$_ond->cue}</n> cromática".( $_ond->cue > 1 ? "s" : "")." <c>)</c>
        </p>";
      }            
      break;
    // kin : génesis
    case 'kin_gen':
      $_ = [
        "<b class='let-ide'>Génesis del Dragón</b><c>:</c> <n>13.000</n> años del Encantamiento del Sueño<c>,</c> poder del sueño<c>.</c>",
        "<b class='let-ide'>Génesis del Mono</b><c>:</c> <n>7.800</n> años del Encantamiento del Sueño<c>,</c> poder de la magia<c>.</c>",
        "<b class='let-ide'>Génesis de la Luna</b><c>:</c> <n>5.200</n> años del Encantamiento del Sueño<c>,</c> poder del vuelo mágico<c>.</c>",
      ];     
      break;
    // kin : ondas y castillos con células del génesis
    case 'kin_nav':
      $gen = 0;
      $cel = 0;
      $cas = 0;
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_('kin_nav_ond') as $_ond ){
        // génesis
        if( $gen != $_ond->gen_enc ){ 
          $_gen = Hol::_('kin_gen_enc',$gen = $_ond->gen_enc); $_[]="
          <p class='tit'>{$_gen->lec}<c>:</c> <b class='let-ide'>Génesis {$_gen->nom}</b><c>.</c></p>";
        }
        if( $cel != $_ond->gen_cel ){ 
          $_cel = Hol::_('kin_gen_cel',$cel = $_ond->gen_cel); $_[]="
          <p class='tit'>Célula <n>{$_cel->ide}</n> de la memoria del Génesis<c>:</c> <b class='let-val'>{$_cel->nom}</b></p>";
        }
        if( $cas != $_ond->nav_cas ){ 
          $_cas = Hol::_('kin_nav_cas',$cas = $_ond->nav_cas); $_[]="
          <p class='tit'>
            El Castillo <b class='col-5-{$_cas->ide}'>".str_replace('del ','',$_cas->nom)."</b> ".Tex::art_del($_cas->des_acc)."<c>:</c> La corte ".Tex::art_del($_cas->des_cor)."<c>,</c> poder {$_cas->des_pod}
          </p>";
        }              
        $_ []= Hol::ima("kin_nav_ond",$_ond,['class'=>"mar_der-1"])."              
        <p>
          <n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c>
          <br>".Tex::let($_ond->enc_des)."
        </p>";
      }          
      break;
    // psi : por tonos galácticos
    case 'psi_ani_lun':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_($ide) as $_lun ){
        $_ []= Hol::ima("ton",$_lun->ton,['class'=>"mar_der-2"])."
        <p>
          <b class='let-ide'>".Tex::let_pal(Num::dat($_lun->ide,'pas'))." Luna</b>
          <br>Luna ".Tex::art_del($_lun->ton_car)."<c>:</c> ".Tex::let($_lun->ton_pre)."
        </p>";
      }    
      break;        
    // psi : fechas desde - hasta
    case 'psi_lun_fec':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_('psi_ani_lun') as $_lun ){
        $_[] = Hol::ima("ton",$_lun->ton,['class'=>"mar_der-3"])."
        <p>
          <b class='let-ide'>$_lun->nom</b> <n>".intval($_lun->ton)."</n>
          <br>".Tex::let($_lun->fec_ran)."
        </p>";
      }$_[] = "
      <span class='fig_ima'></span>
      <p>
        <b class='let-ide'>Día Verde</b> o Día Fuera del Tiempo
        <br><n>25</n> de Julio
      </p>";        
      break;
    }    
    break;
  // lunas_en_movimiento
  case 'lun': 
    switch( $ide ){
    // luna : heptadas - cuarto armónica
    case 'lun_arm':
      if( isset($_atr[1]) ){
        switch( $_atr[1] ){
        // descripcion
        case 'des': 
          foreach( Hol::_('lun_arm') as $_hep ){
            $_ []= Tex::let("$_hep->nom (")."<c class='col-4-$_hep->ide'>$_hep->des_col</c>".Tex::let("): $_hep->des");
          }
          break;
        case 'pod':
          foreach( Hol::_('lun_arm') as $_hep ){
            $_ []= Tex::let("$_hep->nom: ")."<c class='col-4-$_hep->ide'>$_hep->des_col</c>".Tex::let(", $_hep->des_pod $_hep->des_car");
          }        
          break;            
        }
      }
      break;
    // luna : heptadas lunares
    case 'lun_arm_col':
      $est_ope['atr'] = [ 'ide','nom','col','dia','pod' ];
      $est_ope['opc'] []= 'cab_ocu';
      $_ = Dat::lis("hol.lun_arm", $est_ope, $ope );
      break;
    // kin : castillos del encantamiento
    case 'kin_nav_cas':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_($ide) as $_cas ){ $_ [] = 

        Hol::ima($ide,$_cas,['class'=>"mar_der-2"])."

        <p>
          <b class='let-ide'>Castillo $_cas->des_col $_cas->des_dir ".Tex::art_del($_cas->des_acc)."</b><c>:</c>
          <br>Ondas Encantadas ".Tex::let($_cas->nav_ond)."
        </p>";
      }          
      break;              
    // psi : totems lunares
    case 'psi_lun_tot':
      $ope['lis'] = ['class'=>"ite"];
      $_lis = [
        1=>"El <b class='let-ide'>Murciélago Magnético</b> va con la Primera Luna",
        2=>"El <b class='let-ide'>Escorpión Lunar</b> va con la Segunda Luna",
        3=>"El <b class='let-ide'>Venado Eléctrico</b> va con la Tercera Luna",
        4=>"El <b class='let-ide'>Búho Auto<c>-</c>Existente</b> va con la Cuarta Luna",
        5=>"El <b class='let-ide'>Pavo Real Entonado</b> va con la Quinta Luna",
        6=>"El <b class='let-ide'>Lagarto Rítmico</b> va con la Sexta Luna",
        7=>"El <b class='let-ide'>Mono Resonante</b> va con la Séptima Luna",
        8=>"El <b class='let-ide'>Halcón Galáctico</b> va con la Octava ",
        9=>"El <b class='let-ide'>Jaguar Solar</b> va con la Novena Luna",
        10=>"El <b class='let-ide'>Perro Planetario</b> va con la Décima Luna",
        11=>"La <b class='let-ide'>Serpiente Espectral</b> va con la Undécima Luna",
        12=>"El <b class='let-ide'>Conejo Cristal</b> va con la Duodécima Luna",
        13=>"La <b class='let-ide'>Tortuga Cósmica</b> va con la Decimotercera Luna"
      ];

      foreach( $_lis as $ite => $htm ){
        $_ []= Hol::ima("psi_ani_lun",$_lun = Hol::_('psi_ani_lun',$ite),['class'=>"mar_der-2"])."
        <p>
          $htm
          <br>".Tex::let($_lun->fec_ran)."
        </p>";
      }
      $_ []= "
      <p>
        Día fuera del tiempo
        <br><n>25</n> de Julio
      </p>";        
      break;
    // sello : holon humano => rol de familias terrestres
    case 'sel_cro_fam':

      $fam_tit = [];
      $sel_lis = [];

      foreach( Hol::_('uni_hum_ded') as $_ded ){
        $_fam = Hol::_('sel_cro_fam',$_ded->fam);
        $fam_tit[$lis_pos] = [
          'eti'=>"div", 
          'class'=>"doc_ite", 
          'htm'=> Hol::ima("uni_hum_ded",$_ded,['class'=>"mar_der-1"])."                  
          <p class='tit tex_ali-izq'><b class='let-ide'>Familia Terrestre $_fam->nom</b><c>:</c>
            <br>Familia de $_fam->des_cod<c>:</c> Dedos {$_ded->nom}".( in_array($_ded->nom,['Anular','Pulgar']) ? "es" : "s" )." </p>" 
        ];
        $lis_pos += 4;
        foreach( explode(', ',$_fam->sel) as $_sel ){
          $_sel = Hol::_('sel',$_sel);
          $_hum_ext = Hol::_('uni_hum_ext',$_sel->hum_ext);
          $sel_lis []= Obj::val_atr([
            'nom'=>"Tribu ".Tex::art_del($_sel->nom)." $_sel->des_col", 
            'ima_nom'=>[ 'htm'=>Hol::ima("sel",$_sel,['class'=>"mar-1"]) ],
            'des_cod'=>$_sel->des_cod,
            'ima_cod'=>[ 'htm'=>Hol::ima("sel_cod",$_sel->ord,['class'=>"mar-1"]) ],
            'hum_ext'=>$_hum_ext->nom
          ]);
        }
      }

      $_ = Dat::lis($sel_lis,[ 'tit'=>$fam_tit, 'opc'=>['cab_ocu'] ]);
      break;
    // anillo : años (desde-hasta) por anillos solares
    case 'ani':
      $ini = 1992;
      $cue = 8;
      $_[] = "
      <b class='let-ide'>Año Uno</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',39),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio  <n>1.992</n> <c>-</c> <n>25</n> Julio <n>1.993</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Dos</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',144),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.993</n> <c>-</c> <n>25</n> Julio <n>1.994</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Tres</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',249),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.994</n> <c>-</c> <n>25</n> Julio <n>1.995</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Cuatro</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',94),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.995</n> <c>-</c> <n>25</n> Julio <n>1.996</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Cinco</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',199),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.996</n> <c>-</c> <n>25</n> Julio <n>1.997</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Seis</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',44),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.997</n> <c>-</c> <n>25</n> Julio <n>1.998</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Siete</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',149),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.998</n> <c>-</c> <n>25</n> Julio <n>1.999</n><c>.</c></p>
      </div>"; $_[] = "
      <b class='let-ide'>Año Ocho</b>
      <div class='doc_ite'>
        ".Hol::ima("kin",$_kin = Hol::_('kin',254),['class'=>"mar_der-1"])."
        <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.999</n> <c>-</c> <n>25</n> Julio <n>2.000</n><c>.</c></p>
      </div>";
      break;
    }    
    break;
  // sonda_de_arcturus
  case 'arc': 
    break;
  // tratado_del_tiempo
  case 'tie':
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
      $_ = Dat::lis("hol.psi_ani_vin", $est_ope, $ope);

      break;
    }    
    break;
  // telektonon
  case 'tel':
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
        <div class='doc_ite jus-cen'>";
          if( $opc_ini ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta {$pos}-1' class='mar_der-1' style='width:24rem;'>";
          if( $opc_fin ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta {$pos}-2' class='mar_izq-1' style='width:24rem;'>";
          $htm .= "
        </div>";
        $_ []= $htm;
      }
      $_ = Lis::bar( $_, $ope);          
      break;
    // sello : holon solar => circuitos de telepatía
    case 'uni_sol_cir':
      $ope['lis'] = ['class'=>"ite"];

      foreach( Hol::_($ide) as $_cir ){
        $pla = explode(', ',$_cir->pla);
        $pla_ini = Hol::_('uni_sol_pla',$pla[0]);
        $pla_fin = Hol::_('uni_sol_pla',$pla[1]);
        $htm = 
        Hol::ima($ide,$_cir,['class'=>""])."
        <div>
          <p class='tit'>Circuito <n>$_cir->ide</n><c>:</c> <b class='let-ide'>$pla_ini->nom <c>-</c> $pla_fin->nom</b></p>
          <ul>
            <li>Circuito ".Tex::let($_cir->nom)."</li>
            <li><p>".Tex::let("$_cir->cue unidades - $_cir->des")."</p></li>
            <li><p>Notación Galáctica<c>,</c> números de código ".Tex::let("{$_cir->sel}: ");
            $lis_pos = 0;
            foreach( explode(', ',$_cir->sel) as $sel ){ 
              $lis_pos++; 
              $_sel = Hol::_('sel', $sel == 00 ? 20 : $sel);                      
              $htm .= Tex::let( $_sel->des_pod_tel.( $lis_pos == 3 ? " y " : ( $lis_pos == 4 ? "." : ", " ) ) );
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
      foreach( Hol::_($ide) as $_hep ){
        $_ []= Tex::let("$_hep->nom: ")."<c class='col-4-$_hep->ide'>$_hep->des_col</c>".Tex::let(", $_hep->des_pod $_hep->des_car");
      }        
      break;              
    // luna : lines de fuerza
    case 'lun_fue': 
      foreach( Hol::_($ide) as $_lin ){
        $_ []= Tex::let("{$_lin->nom}: {$_lin->des}");
      }
      break;
    }    
    break;
  // proyecto_rinri
  case 'rin':
    switch( $ide ){
    // plasma : años por oráculos de la profecía
    case 'rad_ani': 
      $ope['lis'] = ['class'=>"ite"];
      $ope['ite'] = ['class'=>"mar_aba-1"];      

      foreach( Hol::_('rad') as $_rad ){ $_ []=
        Hol::ima("rad",$_rad,['class'=>"mar_der-1"])."
        <p>
          <b class='let-ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
          <br>".Tex::let($_rad->rin_des)."
        </p>";
      }
      $_ = Lis::dep($_,$ope);
      break;                    
    // luna : días del cubo
    case 'lun_cub':
      foreach( Hol::_($ide) as $_cub ){
        $_ []= 
        "<div class='doc_ite'>
          ".Hol::ima("sel",$_cub->sel,['class'=>"mar_der-1"])."              
          <div>
            <p class='tit'>Día <n>$_cub->lun</n><c>,</c> CUBO <n>$_cub->ide</n><c>:</c> $_cub->nom</p>
            <p class='des'>$_cub->des</p>
          </div>              
        </div>
        <p class='tex-enf tex_ali-cen'>".Tex::let($_cub->tit)."</p>
        ".( !empty($_cub->lec) ? "<p class='des tex-cur tex_ali-cen'>".Tex::let($_cub->lec)."</p>" : ""  )."
        <p class='des'>".Tex::let($_cub->afi)."</p>";
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
          <table".Ele::atr($ope['lis']).">
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
              foreach( Hol::_($ide) as $_lun ){ $_ .= "
                <tr>
                  <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                  foreach( explode(', ',$_lun->kin_pag) as $kin ){ $_ .= "
                    <td>".Hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                  }$_ .= "   
                </tr>";
              }$_ .= "
            </tbody>
          </table>";        
          break;
        // días psi del cubo - laberinto del guerrero
        case 'cub': 
          $_ = "
          <table".Ele::atr($ope['lis']).">
            <tbody>";
              foreach( Hol::_($ide) as $_lun ){ $_ .= "
                <tr>
                  <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                  foreach( explode('-',$_lun->kin_cub) as $kin ){ $_ .= "
                    <td>".Hol::ima("kin",$kin,['class'=>"mar-1"])."</td>";
                  }$_ .= "
                  <td>Kines ".Tex::let($_lun->kin_cub)."</td>
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
        $_ = Dat::lis("hol.lun", $est_ope, $ope );
      }
      break;
    // psi-cronos : cromaticas entonadas
    case 'psi_cro_arm':
      foreach( [ 1, 2, 3, 4 ] as $arm ){
      
        $cro_arm = Hol::_('psi_ani_cro_arm',$arm);

        $_ []= "Cromática <c class='col-4-$arm'>$cro_arm->des_col</c><br>".Tex::let("$cro_arm->nom: $cro_arm->des");
      }        
      break;      
    }    
    break;
  // dinamicas_del_tiempo
  case 'din': 
    break;
  // tablas_del_tiempo
  case 'tab': 
    break;
  // atomo_del_tiempo
  case 'ato':
    foreach( ['lis'] as $ele ){ if( !isset($ope[$ele]) ) $ope[$ele] = []; }
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
          $cod = Num::val($pos,2);
          $htm = "";
          if( $opc_ini ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/ato/fic/{$cod}-1.gif' alt='Carta {$cod}-1' style='width:20rem;'>";
          if( $opc_fin ) $htm .= "
          <img src='".SYS_NAV."img/hol/bib/ato/fic/{$cod}-2.gif' alt='Carta {$cod}-2' style='width:20rem;'>";
          $_ []= $htm;
        }
        $_ = Lis::bar( $_, $ope);                 
        break;
      }        
      break;        
    // 7 plasmas radiales
    case 'rad_pla':
      $dat_ide = "rad";
      $dat_rel = "{$dat_ide}_pla_fue";
      $pla_qua = [3,4,7];
      Ele::cla($ope['lis'],'ite');
      switch( $_ide[1] ){
      // afirmaciones + quantums
      case 'des': 
        foreach( Hol::_($dat_ide) as $rad ){
          $_ []= 
          Hol::ima($dat_ide,$rad,['class'=>"mar_der-2"])."
          <p><b class='let-ide'>$rad->nom</b>".Tex::let(": $rad->des_fue.")."
            <br>".Tex::let($rad->pla_lec)."
          </p>";            
          if( in_array($rad->ide,$pla_qua) ){
            $qua = Hol::_('rad_pla_qua',$rad->pla_qua);
            $_ []= "
            <div class='doc_val mar-aut mar_ver-2'>
              ".Hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"])."
              <p>".Tex::let($qua->des)."</p>
            </div>";
          }
        }
        break;        
      // atomo: lineas de fuerza + quantums
      case 'ato':
        foreach( Hol::_($dat_ide) as $_rad ){
          $pla_fue = explode(', ',$_rad->pla_fue);
          $fue_pre = Hol::_($dat_rel,$pla_fue[0]);
          $fue_pos = Hol::_($dat_rel,$pla_fue[1]);
          $_ []= 
          Hol::ima($dat_ide,$_rad,['class'=>"mar_der-2"])."
          <div>        
            <p><b class='let-ide'>$_rad->nom</b> <b class='col-".substr($_rad->des_col,0,3)."'>$_rad->des_col</b></p>
            <div class='doc_ite'>
              $fue_pre->nom
              ".Hol::ima($dat_rel,$fue_pre)."
              <c class='sep'>+</c>
              $fue_pos->nom
              ".Hol::ima($dat_rel,$fue_pos)."
              
              <p><c class='sep'>:</c> ".Tex::let($_rad->pla_des)." <c>(</c>Días ".Tex::let($_rad->dia)."<c>)</c></p>
            </div>
          </div>";
          if( in_array($_rad->ide,$pla_qua) ){
            $qua = Hol::_('rad_pla_qua',$_rad->pla_qua);
            $_ []= 
            Hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".Tex::let($qua->lec_ato)."</p>";
          }
        }          
        break;
      // cubo del radion + quantums
      case 'cub':
        $qua = NULL;
        $qua_ide = 0;
        foreach( Hol::_($dat_ide) as $rad ){
          // titulo por quantum
          if( $qua_ide != $rad->pla_qua ){
            $qua = Hol::_('rad_pla_qua',$rad->pla_qua); 
            $qua_ide = $rad->pla_qua; $_ []= "
            <p class='tit anc-100 tex_ali-cen'>".Tex::let($qua->nom)."</p>";
          }
          $cub = Hol::_('rad_pla_cub', $rad->ide);
          $cha = Hol::_('uni_hum_cha', $rad->hum_cha);
          $_ []= 
          "<div>".
            Hol::ima('uni_hum_cha',$cha,['class'=>"mar_der-2"]).
            Hol::ima('rad_pla_cub',$cub,['class'=>"mar_der-2"])."
          </div>
          <div>
            <p>".Tex::let("$rad->nom (Días $rad->dia): $cha->des_pos Chakra, $cha->des_cod o $cha->nom")."</p>
            <p>".Tex::let("Cubo del Radión - $cub->nom")."</p>
          </div>
          ";
          if( in_array($rad->ide,$pla_qua) ){              
            $_ []= 
            Hol::ima('rad_pla_qua',$qua,['class'=>"mar_der-2"]).
            "<p>".Tex::let($qua->lec_cub)."</p>";
          }
        }
        break;
      }
      break;
    // 6 tipos de electricidad
    case 'rad_pla_ele': 
      Ele::cla($ope['lis'],'ite');
      foreach( Hol::_($ide) as $pla_ele ){
        $_ []= 
        Hol::ima($ide,$pla_ele,['class'=>"mar_der-2"])."
        <p>
          <b class='let-ide'>$pla_ele->nom</b> o <b class='let-ide'>$pla_ele->des_cod</b>
          <br>
          ".Tex::let($pla_ele->des)."
        </p>";
      }
      break;
    // 12 lineas de fuerza
    case 'rad_pla_fue': 
      Ele::cla($ope['lis'],'ite');
      foreach( Hol::_($ide) as $_pla_fue ){
        $ele_pre = Hol::_('rad_pla_ele',$_pla_fue->ele_pre);
        $ele_pos = Hol::_('rad_pla_ele',$_pla_fue->ele_pos);
        $_ []= 
        Hol::ima($ide,$_pla_fue,['class'=>"mar_der-2"])."
        <div>
          <p><b class='let-ide'>$_pla_fue->nom</b></p>
          <div class='doc_val'>
            <b class='mar_hor-1'>$ele_pre->des_cod</b>
            ".Hol::ima("rad_pla_ele",$ele_pre)."
            <c class='sep'>$_pla_fue->ele_ope</c>
            <b class='mar_hor-1'>$ele_pos->des_cod</b>              
            ".Hol::ima("rad_pla_ele",$ele_pos)."
          </div>                        
        </div>";
      }        
      break;
    // 4 atómos y 2 tetraedros
    case 'lun_pla_ato':
      switch( $_ide[1] ){
      // Atomo telepatico del tiempo
      case 'tie': 
        Ele::cla($ope['lis'],'ite');
        $pla_tet = [2,4];
        $tet_ide = 0;
        foreach( Hol::_($ide) as $ato ){
          $_ []= 
          Hol::ima($ide,$ato,['class'=>"mar_der-2"])."

          <p>Semana <n>$ato->ide</n><c>:</c> Átomo Telepático del <b class='let-ide'>".Tex::let($ato->nom)."</b></p>";
          // tetraedros
          if( in_array($ato->ide,$pla_tet) ){
            $tet_ide++;
            $tet = Hol::_('lun_pla_tet',$tet_ide); $_ []= 
            Hol::ima('lun_pla_tet',$tet,['class'=>"mar_der-2"])."
            <p>".Tex::let($tet->des.".")."</p>";
          }
        }$_ []= 
        Fig::ima('hol/fic/lun',['class'=>"mar_der-2 tam-15"])."
        <p>También el Día <n>28</n><c>,</c> la transposición fractal de las ocho caras de los dos tetraedros resulta en la creación del Octaedro de Cristal en el centro de la Tierra<c>.</c></p>";            
        break;
      // Cargas por Colores Semanales 
      case 'car':
        Ele::cla($ope['lis'],'ite');
        foreach( Hol::_($ide) as $ato ){
          $col = Hol::_('lun_arm',$ato->ide)->des_col;                        
          $_ []= 
          Hol::ima($ide,$ato,['class'=>"mar_der-2"])."
          <p>
            Semana <n>$ato->ide</n><c>,</c> <b class='col-".substr($col,0,3)."'>{$col}</b>".Tex::let(": $ato->car".".")."
            <br>
            Secuencia ".Tex::let($ato->car_sec.".")."
          </p>";
        }
        break;
      // ficha semanal
      case 'hep':
        $ato = Hol::_($ide,$ope['ide']);
        $_ = "
        <p class='tit tex_ali-izq'>".Tex::let("Semana $ato->ide, Heptágono de la Mente ".Tex::art_del($ato->hep))."</p>
        <div class='doc_ite'>
          ".Hol::ima($ide, $ato, ['class'=>'mar_der-2'])."
          <ul class='mar_arr-0'>
            <li>".Tex::let("Un día = $ato->val.")."</li>
            <li>".Tex::let("Valor lunar = $ato->val_lun.")."</li>
            <li>".Tex::let("Forma $ato->hep_cub en el Holograma Cúbico 7:28.")."</li>
          </ul>                        
        </div>";
        break;          
      }
      break;
    // 4 semanas: cualidad + poder + kin
    case 'lun_arm':
      foreach( Hol::_($ide) as $arm ){
        $ato = Hol::_('lun_pla_ato',$arm->ide);          
        $_[]="
        <p class='tit'>$arm->nom<c>,</c> <b class='col-".substr($arm->des_col,0,3)."'>$arm->des_col</b><c>:</c></p>          
        <div class='doc_ite'>            
          ".Hol::ima($ide,$arm,['class'=>"mar_der-2"])."
          <ul>
            <li>".Tex::let($arm->des)."</li>
            <li>".Tex::let($arm->tel_des)."</li>
            <li>".( count(explode(', ',$ato->val_kin)) == 1 ? "Código del Kin" : "Códigos de Kines" )." ".Tex::let($ato->val_kin)."</li>            
          </ul>
        </div>";
      }
      break;
    // 7 tierras de ur
    case 'lun_pla_tie':
      foreach( Hol::_($ide) as $tie ){
        $rad = Hol::_('rad',$tie->rad);
        $_[]="
        <p class='tit tex_ali-izq'>".Tex::let("$tie->nom, Tierra de UR $tie->ide")."</p>
        <div class='doc_ite'>
          ".Hol::ima('rad',$tie->rad,['class'=>"mar_der-2"])."
          <p>
            $tie->des
            <br>".Tex::let("Día $tie->dia, $tie->tel, Tablero del Plasma.")."
            <br>".Tex::let("Plasma Radial $rad->ide, $rad->nom: $rad->des_fue")."
            <br>".Tex::let("( $tie->pos última Luna, $tie->pos Luna Mística )")."
          </p>
        </div>";
      }
      break;
    // ejes del Cubo Primigenio y el Átomo Telepático del Tiempo
    case 'lun_pla_eje':
      Ele::cla($ope['lis'],'ite');
      foreach( Hol::_($ide) as $eje ){
        $tie = explode(', ',$eje->tie);
        $ini = Hol::_('lun_pla_tie',$tie[0]);
        $fin = Hol::_('lun_pla_tie',$tie[1]);
        $_[]=
        Hol::ima('rad',$ini->rad,['class'=>"mar_der-2"]).
        Hol::ima('rad',$fin->rad,['class'=>"mar_der-2"])."
        <div>
          <p class='tit'>Eje $eje->nom</p>
          <p>".Tex::let("{$ini->ide}° Tierra de UR $ini->nom y {$fin->ide}° Tierra de UR $fin->nom")."</p>
        </div>
        ";
      }
      break;
    }    
    break;
  // sincronotron
  case 'sin': 
    break;
  }
  // devuelvo listado o contenido generado
  return is_array($_) ? Dat::inf_lis( $_, $ide, $ope ) : $_;
}