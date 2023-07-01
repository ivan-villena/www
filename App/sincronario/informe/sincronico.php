<?php 

  // cargo datos de los ciclos para tableros
  $dat_kin = Sincronario::dat_val( $Cic['Fec'], 260, $Kin->ide );

  // foreach( $dat_kin as $v ){ var_dump($v); }

  /* busco fechas de ciclos */
  $Cic_kin = [];
  $kin_ini = $dat_kin[0]['var-fec'];
  $kin_fin = $dat_kin[count($dat_kin)-1]['var-fec'];
?>

<article>  
  <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

  <header>
    <h1><?=Doc_Val::let($App->Art->nom);?></h1>
  </header>

  <section class="ope_tex ali-cen">

    <?=Sincronario::dat_var('val',$Cic);?>    
    
  </section>
  
  <!--Módulo Armónico-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p><?=Doc_Val::let("Giro Galáctico desde el {$kin_ini} hasta el {$kin_fin}.");?></p>

    <?=Doc_Dat::fic('hol.kin',$Kin);?>

    <?=Sincronario::dat_tab('kin',[
      'dat'=>$dat_kin,
      'val'=>[ 'pos'=>$Cic ],
      'pos'=>[ 'ima'=>"hol.ton.ide", 'num'=>"hol.kin.ide" ],
      'est-kin'=>[ 'sel'=>1 ],
      'atr-pag'=>[ 'kin'=>1 ]
    ]);?>

    <p>Para saber más<c>,</c> visita el siguiente <a href="<?=$Dir->codigo?>kin#_01-01-" target="_blank">enlace</a><c>...</c></p>

  </section>

  <!--Oráculo del Destino-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <?=Sincronario::dat_inf('kin_par',$Kin);?>

    <!--Propiedades por Palabras Clave-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>    


      <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>
    
      <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>      

      <?php
        $atr_lis = [
          'par' => Dat::est_atr('hol.sel_par','ver',['mis','acc']),
          'ton' => Dat::est_atr('hol.ton','ver',['des_pod','des_acc_lec']),
          'sel' => Dat::est_atr('hol.sel','ver',['des_car','des_acc','des_pod'])
        ];

        $htm = "
        <thead>
          <tr>
            <th rowspan='2'>Kin</th>
            <th colspan='2'>Roles de Pareja</th>
            <th colspan='2'>Tono</th>
            <th colspan='3'>Sello</th>
          </tr>            
          <tr>";
            foreach( $atr_lis as $ide => $est_atr ){                  
              foreach( $est_atr as $Atr ){
                $htm .= "<th>{$Atr->nom}</th>";
              }
            }
            $htm .= "
          </tr>
        </thead>
        
        <tbody>";
          foreach( Dat::_('hol.sel_par') as $Par ){

            $Kin_par = ( $Par->cod == 'des' ) ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$Par->cod}"});

            $htm .= "
            <tr>";
              $htm .= "
              <td>".Doc_Val::ima('hol',"kin",$Kin_par)."</td>";
              // parejas
              foreach( $atr_lis['par'] as $atr => $Atr ){ 
                $htm .= "<td>".Doc_Val::let($Par->$atr)."</td>"; 
              }
              // Tono
              $Ton = Dat::_('hol.ton',$Kin_par->nav_ond_dia);
              foreach( $atr_lis['ton'] as $atr => $Atr ){ 
                $htm .= "<td>".Doc_Val::let($Ton->$atr)."</td>"; 
              }
              // Sello
              $Sel = Dat::_('hol.sel',$Kin_par->arm_tra_dia);            
              foreach( $atr_lis['sel'] as $atr => $Atr ){ 
                $htm .= "<td>".Doc_Val::let($Sel->$atr)."</td>"; 
              }
              $htm .= "
            </tr>";
          }
          $htm .= "
        </tbody>";

        echo Doc_Dat::lis_htm($htm);
      ?>

    </section>

    <!--Lecturas por Palabras Clave-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>    

      <p>En <a href='<?=$Dir->tutorial?>sincronario#_04-04-' target='_blank'>este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>
    
      <p>Puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>      

      <?php
        $_ = [];
        
        foreach( Dat::_('hol.sel_par') as $Par ){

          if( $Par->cod == 'des' ) continue;

          $Kin_par = Dat::_('hol.kin',$Kin->{"par_{$Par->cod}"});
          $Sel = Dat::_('hol.sel',$Kin_par->arm_tra_dia);
          
          $_ []= [
            Doc_Val::ima('hol',"kin",$Kin_par),
            "<div>
              <p><b class='tit'>{$Kin_par->nom}</b> ".Doc_Val::let("( {$Par->dia} )")."</p>
              <p>".Doc_Val::let("{$Sel->des_acc} {$Par->pod} {$Sel->des_car}, que {$Par->mis} {$Sel->des_car}, {$Par->acc} {$Sel->des_pod}.")."</p>
            </div>"            
          ];
        }

        echo Doc_Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ]);
      ?>

    </section>
    
    <!--Posiciones en los Ciclos del kin-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>    

      <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        
    
      <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>      

      <?php
        $_ = [];

        $atr_lis = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];

        foreach( Dat::_('hol.sel_par') as $Par ){
          
          $Kin_par = $Par->cod == 'des' ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$Par->cod}"});

          $ite = [ Doc_Val::ima('hol',"kin",$Kin_par) ];

          foreach( $atr_lis as $atr ){
            $ite []= Doc_Val::ima('hol',"kin_{$atr}",$Kin_par->$atr,[ 'class'=>"tam-5" ]);
          }
          
          $_ []= $ite;
        }

        echo Doc_Dat::lis( $_, [ 'opc'=>['htm','cab_ocu'] ]);
      ?>

    </section>
    
    <!--Sincronometría del Holon-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <p>Puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$Dir->libro?>telektonon#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>
    
      <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

      <?php
        $_ = [];

        $atr_lis = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];  

        foreach( Dat::_('hol.sel_par') as $Par ){
          
          $Kin_par = $Par->cod == 'des' ? $Kin : Dat::_('hol.kin',$Kin->{"par_{$Par->cod}"});                            
  
          $Sel_par = Dat::_('hol.sel',$Kin_par->arm_tra_dia);
  
          $ite = [ Doc_Val::ima('hol',"kin",$Kin_par), $Par->nom, $Sel_par->des_pod ];
  
          foreach( $atr_lis as $atr ){
            $ite []= Doc_Val::ima('hol',"{$atr}",$Sel_par->$atr,[ 'class'=>"tam-5" ]);
          }            
          $_ []= $ite;
        }

        echo Doc_Dat::lis( $_, [ 'opc'=>[ 'htm', 'cab_ocu' ] ]);
      ?>

    </section>    

  </section>

  <!--Nave del Tiempo-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>
    
    <p><?=Doc_Val::let("Desde el {$kin_ini} hasta el {$kin_fin}.");?></p>

    <?=Sincronario::dat_tab('kin_nav',[
      'dat'=>$dat_kin,
      'val'=>[ 'pos'=>$Cic ],
      'pos'=>[ 'ima'=>"hol.kin.ide" ],
      'atr-pul'=>[ ]
    ]);?>

    <!--Castillo de la Nave-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p><?=Doc_Val::let("Desde el x Hasta el x");?></p>

      <?=Doc_Dat::fic('hol.kin_nav_cas',$Kin->nav_cas);?>

      <?=Doc_Dat::fic('hol.cas',$Kin->nav_cas_dia);?>
      
      <?=Sincronario::dat_tab('kin_nav_cas',[
        'dat'=>$dat_kin,
        'ide'=> $Kin->nav_cas,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ],
        'atr-pul'=>[ ]
      ]);?>

      <?=Sincronario::dat_inf('kin_nav_cas',$Cic);?>

    </section>    

    <!--Onda Encantada-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p><?=Doc_Val::let("Desde el x Hasta el x");?></p>      

      <?=Doc_Dat::fic('hol.kin_nav_ond',$Kin->nav_ond);?>

      <?=Doc_Dat::fic('hol.ton',$Kin->nav_ond_dia);?>
      
      <?=Sincronario::dat_tab('kin_nav_ond',[
        'dat'=>$dat_kin,
        'ide'=> $Kin->nav_ond,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ],
        'val-par'=>1,
        'atr-pul'=>[ ]
      ]);?>

      <?=Sincronario::dat_inf('kin_nav_ond',$Cic);?>

    </section>      

  </section> 
  
  <!--Giro Galáctico-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p><?=Doc_Val::let("Desde el {$kin_ini} hasta el {$kin_fin}.");?></p>

    <?=Sincronario::dat_tab('kin_arm',[
      'dat'=>$dat_kin,
      'val'=>[ 'pos'=>$Kin->arm_tra ],
      'opc'=>[ "gru" ],
      'atr-pul'=>[]
    ]);?>  

    <!--Trayectoria Armónica-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p><?=Doc_Val::let("Desde el x Hasta el x");?></p>

      <?=Doc_Dat::fic('hol.kin_arm_tra',$Kin->arm_tra);?>

      <?=Doc_Dat::fic('hol.sel',$Kin->arm_tra_dia);?>

      <?=Sincronario::dat_tab('kin_arm_tra',[
        'dat'=>$dat_kin,
        'ide'=> $Kin->arm_tra,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ],
        'val-par'=>1,
      ]);?>

    </section>
    
    <!--Célula del tiempo-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

      <p><?=Doc_Val::let("Desde el x Hasta el x");?></p>

      <?=Doc_Dat::fic('hol.kin_arm_cel',$Kin->arm_cel);?>

      <?=Doc_Dat::fic('hol.sel_arm_raz',$Kin->arm_cel_dia);?>

      <?=Sincronario::dat_tab('kin_arm_cel',[
        'dat'=>$dat_kin,
        'ide'=>$Kin->arm_cel,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ],
        'val-par'=>1
      ]);?>

    </section>    

  </section> 
  
  <!--Giro Espectral-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <p><?=Doc_Val::let("Desde el {$kin_ini} hasta el {$kin_fin}.");?></p>

    <?=Sincronario::dat_tab('kin_cro',[
      'dat'=>$dat_kin,
      'val'=>[ 'pos'=>$Kin->cro_ele ],
      'opc'=>[ "gru" ],
      'atr-pul'=>[]
    ]);?>       

    <!--Estación Galáctica-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>     

      <p><?=Doc_Val::let("Desde el x Hasta el x");?></p>

      <?=Doc_Dat::fic('hol.kin_cro_est',$Kin->cro_est);?>      

      <?=Doc_Dat::fic('hol.kin_cro_est_dia',$Kin->cro_est_dia);?>

      <?=Sincronario::dat_tab('kin_cro_est',[
        'dat'=>$dat_kin,
        'ide'=>$Kin->cro_est,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ],
        'atr-pul'=>[]
      ]);?>

    </section>
    
    <!--Elemento Cromático-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>     

      <p><?=Doc_Val::let("Desde el x Hasta el x");?></p>

      <?=Doc_Dat::fic('hol.kin_cro_ele',$Kin->cro_ele);?>

      <?=Doc_Dat::fic('hol.sel_cro_fam',$Kin->cro_ele_dia);?>

      <?=Sincronario::dat_tab('kin_cro_ele',[
        'dat'=>$dat_kin,
        'ide'=>$Kin->cro_ele,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.kin.ide" ],
        'val-par'=>1
      ]);?>

    </section>        

  </section>
  
  <!--Flujo G-S de los Holones-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <!--Solar-Interplanetario-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <?=Sincronario::dat_tab('sol',[
        'dat'=>$dat_kin,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.sel.ide" ],
        'est-sol'=>[ 'res'=>1 ]
      ]);?>

      <?=Doc_Dat::fic('hol.sol_pla',$Sol_pla);?>

    </section>

    <!--Planetario-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>
      
      <?=Sincronario::dat_tab('pla',[
        'dat'=>$dat_kin,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.sel.ide" ],
        'est-pla'=>[ 'cen'=>1 ]
      ]);?>

    </section>
    
    <!--Humano-->
    <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
    <section id="<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>">      
      <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>     

      <?=Sincronario::dat_tab('hum',[
        'dat'=>$dat_kin,
        'val'=>[ 'pos'=>$Cic ],
        'pos'=>[ 'ima'=>"hol.sel.ide" ],
        'est-hum'=>[ 'cen'=>1 ]
      ]);?>

    </section>    

  </section>

  <!--Castillo del Destino-->
  <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
  <section id="<?="_{$Nav[1][$nv1]->pos}-"?>">
    <h2><?=Doc_Val::let($Nav[1][$nv1]->nom);?></h2>

    <?=Sincronario::dat_tab('kin_cas',[
      'dat'=>$dat_kin,
      'val'=>[ 'pos'=>$Cic ],
      'pos'=>[ 'ima'=>"hol.kin.ide" ],
      'atr-pul'=>[ ]
    ]);?>    

  </section>
  
</article>