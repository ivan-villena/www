<?php

class Doc_Ope {

  static string $IDE = "Doc_Ope-";
  static string $EJE = "Doc_Ope.";
  static string $DAT = "doc_ope";

  // Seccion Principal : main > ...article
  static function sec( string | array $dat, array $ele = [] ) : string {
    $_ = "";
    // contenido directo
    if( is_string($dat) ){
      
      $_ .= $dat;
    }
    // listado de articulos
    else{
      foreach( $dat as $ide => $art ){
        
        if( isset($art['htm'])){
          Ele::cla($art,"ide-$ide",'ini');
          $_ .= "
          <article".Ele::atr($art).">
            {$art['htm']}
          </article>";
        }
      }
    }
    return $_;
  }
  
  // Cabecera : botones de acceso ( url + pan + win )
  static function cab( $dat ) : string {
    $_ = "";      
    $_eje = self::$EJE;
    
    foreach( $dat as $ide => $art ){
      
      $tip = isset($art['tip']) ? $art['tip'] : 'nav';

      $eje_tog = "{$_eje}{$tip}('$ide');";

      $ele = [ 'eti'=>"a", 'onclick'=>$eje_tog, 'data-ide'=>$ide  ];

      if( is_string($art) ){
        $_ .= Doc_Val::ico($art,$ele);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){
          $ele['title'] = isset($art[1]) ? $art[1] : '';
          $_ .= Doc_Val::ico($art[0],$ele);
        }
        elseif( isset($art['ico']) ){
          $ele['title'] = isset($art['nom']) ? $art['nom'] : '';
          $_ .= Doc_Val::ico($art['ico'],$ele);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){
        $ele['title'] = isset($art->nom) ? $art->nom : '';
        $_ .= Doc_Val::ico($art->ico,$ele);
      }
    }
    return $_;
  }

  // Panel : nav|article[ide] > header + section
  static function pan( string $ide, array $ope = [] ) : string {
    foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
    $_eje = self::$EJE."pan";
    $_ = "";
    $cab_ico = "";
    if( !empty($ope['ico']) ) $cab_ico = Doc_Val::ico($ope['ico'],['class'=>"mar_hor-1"]);

    $cab_tit = "";
    if( !empty($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
    ";
    // etiquetas
    $eti_nav = 'nav';
    if( isset($ope['nav']['eti']) ){
      $eti_nav = $ope['nav']['eti'];
      unset($ope['nav']['eti']);
    }
    $eti_sec = 'div';
    if( isset($ope['sec']['eti']) ){
      $eti_sec = $ope['sec']['eti'];
      unset($ope['sec']['eti']);
    }

    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = Ele::val_dec( $ope['htm'] );
    }

    // imprimo con identificador
    Ele::cla($ope['nav'],"ide-$ide",'ini');
    Ele::cla($ope['nav'],"dis-ocu");
    $_ = "
    <$eti_nav".Ele::atr($ope['nav']).">

      <header".Ele::atr($ope['cab']).">
      
        {$cab_ico} {$cab_tit} ".Doc_Val::ico('ope_val-fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

      </header>

      <$eti_sec".Ele::atr($ope['sec']).">

        {$ope['htm']}

      </$eti_sec>

    </$eti_nav>";
    
    return $_;
  }

  // Modal : #sis > article[ide] > header + section
  static function win( string $ide, array $ope = [] ) : string {
    foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_eje = self::$EJE."win";
    $_ = "";
    // icono de lado izquierdo
    $cab_ico = "";
    if( isset($ope['ico']) ){
      if( is_string($ope['ico']) ){
        $cab_ico = Doc_Val::ico($ope['ico'],['class'=>"mar_hor-1"]);
      }// con menú
      else{
        $_ .= "
        <div class='ini'>";
          $_.="
        </div>";
      }
    }
    // titulo al centro
    $cab_tit = "";
    if( isset($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? Doc_Val::let($ope['nom']) : "" )."</h2>
    ";
    // botones de flujo
    $cab_bot = "
    <div class='ope_bot'>
      ".Doc_Val::ico('ope_val-fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
    </div>";
    // contenido 
    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = Ele::val_dec( $ope['htm'] );
    }      
    // imprimo con identificador
    Ele::cla($ope['art'],"ide-$ide",'ini');
    Ele::cla($ope['art'],"dis-ocu");
    $_ = "
    <article".Ele::atr($ope['art']).">

      <header".Ele::atr($ope['cab']).">      
        {$cab_ico} 
        {$cab_tit} 
        {$cab_bot}
      </header>

      <div".Ele::atr($ope['sec']).">
        {$ope['htm']}
      </div>
    </article>";
    return $_;
  }

  /* Carteles : advertencia + confirmacion */
  static function tex( array $ope = [], array $ele = [] ) : string {

    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    
    Ele::cla($ele['sec'],'ope_tex'.( !empty($ope['tip']) ? " -{$ope['tip']}" : "" ),'ini');

    $_ = "
    <div".Ele::atr($ele['sec']).">";

      // cabecera: icono + titulo
      if( isset($ope['tip']) || $ope['tit'] ){
        $_.="
        <header>";
          if( isset($ope['tip']) ){
            switch( $ope['tip'] ){
            case 'err': $ele['ico']['title'] = "Error..."; break;
            case 'adv': $ele['ico']['title'] = "Advertencia..."; break;
            case 'opc': $ele['ico']['title'] = "Consultas..."; break;
            case 'val': $ele['ico']['title'] = "Notificación..."; break;
            }
            Ele::cla($ele['ico'],"mar-1");
            $_ .= Doc_Val::ico("ope_tex-{$ope['tip']}", $ele['ico']);
          }
          if( isset($ope['tit']) ){
            if( is_string($ope['tit']) ) $_.="<p class='tit'>".Doc_Val::let($ope['tit'])."</p>";
          }
          $_.="
        </header>";
      }

      // contenido: texto
      if( isset($ope['tex']) ){
        $ope_tex = [];
        if( is_array($ope['tex']) ){
          foreach( $ope['tex'] as $tex ){
            $ope_tex []= Doc_Val::let($tex);
          }
        }else{
          $ope_tex []= $ope['tex'];
        }
        $_ .= "<p".Ele::atr($ele['tex']).">".implode('<br>',$ope_tex)."</p>" ;
      }

      // elementos
      if( isset($ope['htm']) ){

        $_ .= Ele::val( ...Obj::pos_ite($ope['htm']) );
      }

      // botones: aceptar - cancelar
      if( isset($ope['opc']) ){
        $_ .= "
        <form class='ope_bot'>";
        if( isset($ope['opc']['ini']) ){
          
        }
        if( isset($ope['opc']['fin']) ){
        
        }
        $_ .= "
        </form>";
      }

      $_.="
    </div>";

    return $_;
  }

  // Imprimo Artpiculo desde objeto
  static function art( object $nav, array $_art ) : string {
    $_ = "";      

    $agr = Ele::htm($nav->ope);

    $_art = [];

    $_ = "
    <article class='app_art'>";
      // introduccion
      if( !empty($agr['htm_ini']) ){

        $_ .= $agr['htm_ini'];
      }
      else{ $_ .= "
        <h2>{$nav->nom}</h2>";
      }
      // listado de contenidos
      if( !empty($_art) ){ $_ .= "

        <nav class='lis'>";

          foreach( $_art as $art ){

            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".Doc_Val::let($art->nom)."</a>";

            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='ope_val nav'>
                ".Doc_Ope::val_ico()."
                {$art_url}
              </div>
              <div class='dat'>
                ".Ele::val($art->ope['tex'])."
              </div>
              ";
            }else{
              $_ .= $art_url;
            }
            
          }$_.="

        </nav>";
      }
      
      // pie de pagina
      if( !empty($agr['htm_fin']) ){
        $_ .= $agr['htm_fin'];
      }
      $_ .= "
    </article>";          

    return $_;
  }// Secciones del articulo
  static function art_sec() : void {
    $Nav = [];
    ?>
    
    <?php $nv1=0;$nv2=0;$nv3=0;$nv4=0;?>

    <?php $nv1=Num::val($nv1+1,2);$nv2=0;$nv3=0;$nv4=0;?>
    <section id='<?="_{$Nav[1][$nv1]->pos}-"?>'>
      <h2><?=Doc_Val::let($Nav[1][$nv1]->nom)?></h2>

      <?php $nv2=Num::val($nv2+1,2);$nv3=0;$nv4=0;?>    
      <section id='<?="_{$Nav[2][$nv1][$nv2]->pos}-"?>'>      
        <h3><?=Doc_Val::let($Nav[2][$nv1][$nv2]->nom);?></h3>

        <?php $nv3=Num::val($nv3+1,2);$nv4=0;?>
        <section id='<?="_{$Nav[3][$nv1][$nv2][$nv3]->pos}-"?>'>
          <h4><?=Doc_Val::let($Nav[3][$nv1][$nv2][$nv3]->nom)?></h4>

          <?php $nv4=Num::val($nv4+1,2);$nv5=0;?>
          <section id='<?="_{$Nav[4][$nv1][$nv2][$nv3][$nv4]->pos}-"?>'>
            <h5><?=Doc_Val::let($Nav[4][$nv1][$nv2][$nv3][$nv4]->nom)?></h5>

            <?php $nv5=Num::val($nv5+1,2);?>
            <section id='<?="_{$Nav[5][$nv1][$nv2][$nv3][$nv4][$nv5]->pos}-"?>'>
              <h6><?=Doc_Val::let($Nav[5][$nv1][$nv2][$nv3][$nv4][$nv5]->nom)?></h6>

            </section>

          </section>

        </section>

      </section>
    
    </section>

    <?php
  }// Indice con enlaces => a[href] > ...a[href]
  static function art_nav( array $dat, array $ele = [], ...$opc ) : string {
    $_ = "";    
    $_eje = self::$EJE."art_nav";
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // operadores
    Ele::cla( $ele['ope'], "-ren", 'ini' );    
    $_ .= Doc_Ope::lis_ope(self::$EJE."art_nav",['tog','ver'],$ele);
    
    // armo listado de enlaces    
    $opc_ide = in_array('ide',$opc);
    Ele::cla($ele['lis'], "nav");

    // proceso con nivelacion de indices
    $_lis = [];
    foreach( $dat[1] as $nv1 => $_nv1 ){
      $ide = $opc_ide ? $_nv1->ide : $nv1;
      $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv1->nom}") ];
      if( !isset($dat[2][$nv1]) ){
        $_lis []= Ele::val($eti_1);
      }
      else{
        $_lis_2 = [];
        foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
          $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
          $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv2->nom}") ];
          if( !isset($dat[3][$nv1][$nv2])  ){
            $_lis_2 []= Ele::val($eti_2);
          }
          else{
            $_lis_3 = [];              
            foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
              $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
              $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv3->nom}") ];
              if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                $_lis_3 []= Ele::val($eti_3);
              }
              else{
                $_lis_4 = [];                  
                foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                  $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                  $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv4->nom}") ];
                  if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                    $_lis_4 []= Ele::val($eti_4);
                  }
                  else{
                    $_lis_5 = [];                      
                    foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                      $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                      $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv5->nom}") ];
                      if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                        $_lis_5 []= Ele::val($eti_5);
                      }
                      else{
                        $_lis_6 = [];
                        foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                          $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                          $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}('val',this);", 'htm'=> Doc_Val::let("{$_nv6->nom}") ];
                          if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                            $_lis_6 []= Ele::val($eti_6);
                          }
                          else{
                            $_lis_7 = [];
                            // ... continuar ciclo
                            $_lis_6 []= [ 'ite'=>$eti_6, 'lis'=>$_lis_7 ];                              
                          }
                        }
                        $_lis_5 []= [ 'ite'=>$eti_5, 'lis'=>$_lis_6 ];
                      }
                    }
                    $_lis_4 []= [ 'ite'=>$eti_4, 'lis'=>$_lis_5 ];
                  }
                }
                $_lis_3 []= [ 'ite'=>$eti_3, 'lis'=>$_lis_4 ];
              }
            }
            $_lis_2 []= [ 'ite'=>$eti_2, 'lis'=>$_lis_3 ];  
          }
        }
        $_lis []= [ 'ite'=>$eti_1, 'lis'=>$_lis_2 ];
      }
    }

    // pido listado
    $ele['opc'] = [];
    Ele::cla($ele['dep'],"dis-ocu");
    
    return $_ .= Doc_Ope::lis('dep',$_lis,$ele);
  }

  /* Contenedores : nav(pes,tex,bar,bot) + * > ...[nav="ide"] */
  static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
    $_ = "";
    $_eje = self::$EJE."nav";
    foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // por botones de enlaces y contenido
    if( $tip == 'bot' ){

      Ele::cla($ele['lis'],"ope_nav $tip",'ini');

      $ele['lis']['eti'] = "nav";
      $ele['lis']['htm'] = "";
  
      foreach( $dat as $bot ){
  
        if( isset($bot[0]) && isset($bot[1]) ){
          
          $bot_ele = isset($bot[2]) ? $bot[2] : [];
  
          $bot_ele['htm'] = $bot[1];
  
          $bot_ele['eti'] = 'button';
  
          if( preg_match("/^url:/",$bot[0]) ){
  
            $bot_ele['htm'] = Ele::eti([ 'eti'=>"a", 'href'=>SYS_NAV.substr($bot[0],4), 'class'=>"tex", 'htm'=>$bot[1] ]);
          }
          else{
            
            Ele::eje($bot_ele,'cli',"{$_eje}(this);".$bot[0]);
          }
  
          // cargo boton al contenedor
          $ele['lis']['htm'] .= Ele::eti($bot_ele);
        }
      }
      
      $_ = Ele::val($ele['lis']);

    }
    // pes, bar, ico
    else{

      $opc_ico = in_array('ico',$opc);
      $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
      
      // navegador 
      Ele::cla($ele['lis'],"ope_nav $tip",'ini');
  
      $_ .= "
      <nav".Ele::atr($ele['lis']).">";    
      foreach( $dat as $ide => $val ){
  
        if( is_object($val) ) $val = Obj::nom($val);
  
        if( isset($val['ide']) ) $ide = $val['ide'];
  
        $ele_nav = isset($val['nav']) ? $val['nav'] : [];
  
        $ele_nav['eti'] = 'a';
        Ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');
  
        if( $val_sel && $val_sel == $ide ) Ele::cla($ele_nav,"fon-sel");
  
        if( $opc_ico && isset($val['ico']) ){
          $ele_nav['title'] = $val['nom'];
          Ele::cla($ele_nav,"mar-0 pad-1 tam-4 bor_cir-1",'ini');
          $_ .= Doc_Val::ico($val['ico'],$ele_nav);
        }
        else{
          $ele_nav['htm'] = $val['nom'];
          $_ .= Ele::val($ele_nav);
        }        
      }$_.="
      </nav>";
      
      // contenido por secciones
      $eti_sec = 'div';
      if( isset($ele['sec']['eti'])  ){
        $eti_sec = $ele['sec']['eti'];
        unset($ele['sec']['eti']);
      }
      
      $eti_ite = 'section';
      if( isset($ele['ite']['eti']) ){
        $eti_ite = $ele['ite']['eti'];
        unset($ele['ite']['eti']);
      }
  
      if( $tip != 'pes' && !$val_sel ) Ele::cla($ele['sec'],"dis-ocu");
  
      $_ .= "
      <$eti_sec".Ele::atr($ele['sec']).">";
  
        foreach( $dat as $ide => $val ){
  
          $ele_ite = $ele['ite'];
          
          Ele::cla($ele_ite,"ide-$ide",'ini');
          
          if( !$val_sel || $val_sel != $ide ) Ele::cla($ele_ite,"dis-ocu");
          
          $_ .= "
          <$eti_ite".Ele::atr($ele_ite).">
            ".( isset($val['htm']) ? ( is_array($val['htm']) ? Ele::val_dec($val['htm']) : $val['htm'] ) : '' )."
          </$eti_ite>";
  
        }$_.="
      </$eti_sec>";
  
      // Contenedor de la barra con secciones
      if( $tip == 'bar' ){
        $_ = "
        <div>
          {$_}
        </div>";
      }
    }

    return $_;
  }

  /* variable en formulario: div.ope_var > label + ( select,input,textarea,button )[name] */
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
    // parametros
    $par_lis = [
      'ico'=>"", 
      'nom'=>"", 
      'des'=>"", 
      'ite'=>[], 
      'eti'=>[], 
      'ope'=>[], 
      'htm'=>"", 
      'htm_pre'=>"", 
      'htm_med'=>"", 
      'htm_pos'=>"" 
    ];

    // identificadores
    $dat_ide = is_string($ide) ? explode('.',$ide) : $ide;
    if( isset($dat_ide[2]) ){
      $esq = $dat_ide[0]; 
      $est = $dat_ide[1];
      $atr = $dat_ide[2];
    }
    elseif( isset($dat_ide[1]) ){
      $est = $dat_ide[0];
      $atr = $dat_ide[1];
    }
    else{
      $atr = $dat_ide[0];
    }

    // por atributo de la base
    if( $tip == 'dat.atr' ){

      if( !empty($_atr = Dat::est($esq,$est,'atr',$atr)) ) $_var = [
        'nom'=>$_atr->nom,
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != '_' ){ 

      $_var = Dat::var($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        
        $ele['ope'] = Ele::val_jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = Obj::val_jun($ele,$_var);
    }
    // identificadores
    if( empty($ele['ope']['id']) ){

      if( !empty($ele['ide']) ){

        $ele['ope']['id'] = $ele['ide'];
      }
      elseif( isset($esq) && isset($est) && isset($atr) ){

        $ele['ope']['id'] = "{$esq}-{$est}-{$atr}";
      }      
    }
    // nombre en formulario
    if( empty($ele['ope']['name']) ){
      $ele['ope']['name'] = $atr;
    }      
    // proceso html + agregados
    $agr = Ele::htm($ele);

    // etiqueta
    $eti_htm='';
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    // opcion desactivada
    if( !in_array('eti',$opc) ){
      // icono o texto
      if( !empty($ele['ico']) ){
        $eti_htm = Doc_Val::ico($ele['ico']);
      }
      elseif( !empty($ele['nom']) ){    
        $eti_htm = Doc_Val::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      // agrego for/id e imprimo
      if( !empty($eti_htm) ){
        $ele['eti']['for'] =  "";

        if( isset($ele['id']) ){
          $ele['eti']['for'] = $ele['id'];
        }
        elseif( isset($ele['ope']['id']) ){
          $ele['eti']['for'] = $ele['ope']['id'];
        }

        $eti_htm = "<label".Ele::atr($ele['eti']).">{$eti_htm}</label>";
      }
    }

    // contenido medio
    if( !in_array('eti_fin',$opc) ){
      $eti_ini = $eti_htm.( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ); 
      $eti_fin = "";
    }else{
      $eti_ini = ""; 
      $eti_fin = ( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ).$eti_htm;
    }

    // valor: hmtl o controlador
    if( isset($agr['htm']) ){

      $val_htm = $agr['htm'];
    }
    else{

      // actualizo valor variable
      if( isset($ele['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }

      // dehabilito
      if( isset($ele['ope']['val_ope']) && $ele['ope']['val_ope'] == 0 ){
        unset($ele['ope']['val_ope']);
        $ele['ope']['disabled'] = 1;
      }      

      // aseguro identificador de variable
      if( empty($ele['ope']['name']) && isset($ele['ide']) ){
        $ele['ope']['name'] = $ele['ide'];
      }

      $val_htm = Ele::val($ele['ope']);
    }

    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ) $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';

    // oculto
    if( !empty($ele['ope']['val_ocu']) ){

      Ele::cla($ele['ite'],"dis-ocu");
    }

    return "
    <div".Ele::atr(Ele::cla($ele['ite'],"ope_var",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  }

  /* Bloque de Contenido : visible/oculto */
  static function val( string | array $dat = NULL, array $ele = [] ) : string {

    $_eje = self::$EJE."val";    
    foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
    
    // contenido textual
    if( is_string($dat) ) $dat = [ 'eti'=>"p", 'class'=>"tex-enf tex-cur", 'htm'=> Doc_Val::let($dat) ];

    // contenedor : icono + ...elementos          
    Ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".Ele::atr( Ele::cla( $ele['val'],"ope_val",'ini') ).">
    
      ".Doc_Ope::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? Ele::val($ele['htm_ini']) : '' )."
      
      ".Ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? Ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {

    $_eje = self::$EJE."val";

    return Doc_Val::ico('ope_tog', Ele::eje($ele,'cli',"$_eje(this);",'ini'));

  }
  
  /* Listado : ul.ope_lis */
  static function lis( string $tip, mixed $dat, array $var = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."lis";

    switch( $tip ){
    // valores de listas y sublistas
    case 'dep':
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($var[$e]) ){ $var[$e]=[]; } }

      // elementos        
      Ele::cla($var['lis'],"ope_lis $tip",'ini');
      Ele::cla($var['dep'],"ope_lis",'ini');
      
      // operadores
      if( isset($var['opc']) ){

        $_ .= Doc_Ope::lis_ope('dep', Obj::pos_ite($var['opc']), $var);  
      }
      else{
        $var['opc'] = [];
      }
      // listado
      $_ .= "
      <ul".Ele::atr($var['lis']).">";
      $ide = 0;
      foreach( $dat as $val ){
        $ide++;
        $_ .= Doc_Ope::lis_ite($tip, 1, $ide, $val, $var );
      }$_ .= "
      </ul>";      
      break;
    // punteos en vertical
    case 'pos':
      foreach( ['lis','ite','val'] as $i ){ if( !isset($var[$i]) ) $var[$i]=[]; }
      
      // operador
      if( isset($var['opc']) ) 
        $_ .= Doc_Ope::lis_ope('dep', $var['opc'] = Obj::pos_ite($var['opc']), $var);
      
      // listado
      $eti = isset($var['lis']['eti']) ? $var['lis']['eti'] : 'ul';
  
      // por saltos de línea
      if( is_string($dat) ){
        if( empty($var['lis']['class']) ) $var['lis']['class'] = "tex_ali-izq mar-0 mar_ver-2";
        Ele::cla($var['lis'],"ope_lis $tip tex",'ini');
        $_ .= "
        <$eti".Ele::atr($var['lis']).">";
        foreach( explode("\n",$dat) as $val ){ $_ .= "
          <li".Ele::atr($var['ite']).">".Doc_Val::let($val)."</li>";
        }$_ .= "
        </$eti>";
      }
      // por punteo o numerado
      elseif( Obj::pos_val($dat) ){
        Ele::cla($var['lis'],"ope_lis $tip pun",'ini');
        $_ .= "
        <{$eti}".Ele::atr($var['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= Doc_Ope::lis_ite($tip, 1, $pos, $val, $var, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        $eti = "dl";
        Ele::cla($var['lis'],"ope_lis $tip let",'ini');
        // agrego toggle del item
        Ele::eje($var['ite'],'cli',"{$_eje}('{$tip}',this);",'ini');
        $_ .= "
        <$eti".Ele::atr($var['lis']).">";
          foreach( $dat as $nom => $val ){ 
            $var_ite = $var['ite'];
            if( empty($var_ite['id']) ) $var_ite['id'] = "doc_ope_lis ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt".Ele::atr($var_ite).">
              ".Doc_Val::let($nom)."
            </dt>";
            foreach( Obj::pos_ite($val) as $ite ){ $_ .= "
              <dd".Ele::atr($var['val']).">
                ".Doc_Val::let($ite)."
              </dd>";
            }
          }$_.="
        </$eti>";
      }
      
      break;
    // por desplazamiento horizontal
    case 'bar':
      $pos = 0;
      $pos_ver = ( !empty($var['pos_ver']) ? $var['pos_ver'] : 1 );
      if( !isset($var['lis']) ) $var['lis']=[];
      
      $_.="
      <ul".Ele::atr(Ele::cla($var['lis'],"ope_lis $tip",'ini')).">";
        if( !isset($var['ite']) ) $var['ite'] = [];
        foreach( $dat as $ite ){ 
          $pos++;
          $var_ite = $var['ite'];
          $var_ite['data-pos'] = $pos;
          Ele::cla($var_ite,"pos ide-$pos",'ini');
          if( $pos != $pos_ver ) Ele::cla($var_ite,"dis-ocu");
          $_.="
          <li".Ele::atr($var_ite).">";
            // contenido html
            if( is_string($ite) ){
              $_ .= $ite;
            }// elementos html
            elseif( is_array($ite) ){
              $_ .= Ele::val_dec($ite);
            }// modelo : titulo + detalle + imagen
            elseif( is_object($ite) ){
  
            } $_.= "
          </li>";
        }$_.="
      </ul>";
      
      // operadores
      $min = $pos == 0 ? 0 : 1;
      $max = $pos;
      $_ .= "
      <form class='ope_bot anc-100 jus-cen mar_ver-2'>
  
        ".Doc_Val::num($min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('$tip',this,'val');" ])."
                
        ".Doc_Val::ico('ope_lis-pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('$tip',this,'val');"])."
  
        ".Doc_Var::num('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('$tip',this,'val');" ])."
  
        ".Doc_Val::ico('ope_lis-pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('$tip',this,'val');"])."            
  
        ".Doc_Val::num($max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('$tip',this,'val');" ])."          
  
      </form>";      
      break;
    }

    return $_;
  }// - items
  static function lis_ite( string $tip, int $niv, int | string $ide, mixed $val, array $var, string $eti = "ul" ) : string {
    $_ = "";
    // - Item por sublistas con contenido
    if( $tip == 'dep' ){

      $var_ite = $var['ite'];      

      Ele::cla($var_ite,"pos ide-$ide",'ini');
      
      // con dependencia : evalúo rotacion de icono
      if( $val_lis = is_array($val) ){
        
        $var_ico = $var['ico'];
        
        $ele_dep = isset($var["lis-$niv"]) ? Ele::val_jun($var['dep'],$var["lis-$niv"]) : $var['dep'];
        
        if( isset($ele_dep['class']) && preg_match("/dis-ocu"."/",$ele_dep['class']) ) Ele::cla($var_ico,"ocu");
        
        if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
        
        $val['ite_ope']['ico'] = $var_ico;
      }
      // sin dependencias : separo item por icono vacío
      else{
        
        if( !in_array('not-sep',$var['opc']) ) Ele::cla($var_ite,"sep");
      }

      $_ = "
      <li".Ele::atr( isset($var["ite-$ide"]) ? Ele::val_jun($var["ite-$ide"],$var_ite) : $var_ite  ).">
  
        ".( $val_lis ? Doc_Ope::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
        
        if( $val_lis ){
          // sublista
          if( isset($val['lis']) ){
  
            $var['dep']['data-niv'] = $niv;
  
            $_ .= "
            <ul".Ele::atr($ele_dep).">";
              // por elementos
              if( is_array($val['lis'])  ){
                // operador de la dependencia : 1° item de la lista
                if( isset($var['opc'])){
                  $opc = [];
                  foreach( $val['lis'] as $i => $v ){ 
                    $lis_dep = is_array($v); 
                    break; 
                  }
                  // agrego toogle
                  if( in_array('tog-dep',$var['opc']) && $lis_dep ){ 
                    $opc []= "tog";
                  }
                  // agrego filtro
                  if( in_array('tog-ver',$var['opc']) && $lis_dep ){ 
                    $opc []= "ver";
                  }
                  $var['ope'] = isset( $var['ope-dep'] ) ? $var['ope-dep'] : [];
                  if( !empty($opc) ) $_ .= "
                    <li>".Doc_Ope::lis_ope('dep',$opc,$var)."</li>";
                }
                // recorro sublista
                foreach( $val['lis'] as $i => $v ){
                  
                  $_ .= Doc_Ope::lis_ite($tip, $niv+1, $i, $v, $var );
                }
              }
              // listado textual
              elseif( is_string($val['lis']) ){
  
                $_ .= $val['lis'];
                
              }$_ .= "
            </ul>";
          }
          // contenido html directo ( asegurar elemento único )
          elseif( isset($val['htm']) ){
  
            $_ .= is_string($val['htm']) ? $val['htm'] : Ele::val_dec($val['htm']);
          }
        }
        $_ .= "
      </li>";
    }
    // - Item por punteos
    elseif( $tip == 'pos' ){
      $_ = "
      <li".Ele::atr($var['ite']).">";

        if( is_string($val) ){ 

          $_ .= $val;
        }// sublistas
        else{
          $niv++;
          $_ .= "
          <$eti data-niv='$niv'>";
          if( isset($var['opc']) ){
            $opc = [];
            if( in_array('tog-dep',$var['opc']) ){ 
              $opc []= "tog";
            }
            if( in_array('tog-ver',$var['opc']) ){ 
              $opc []= "ver";
            }
            if( !empty($opc) ){
              $_ .= "<li>".Doc_Ope::lis_ope('dep',$opc,$var)."</li>";
            }
          }
          foreach( $val as $ide => $val ){
  
            $_ .= Doc_Ope::lis_ite($tip, $niv, $ide, $val, $var, $eti);
          }
          $_.="
          </$eti>";
        }
        $_ .= "
      </li>";
    }

    return $_;

  }// - Operadores
  static function lis_ope( string $tip, array $opc = [], array $var = [] ) : string {
    $_ = "";
    
    if( in_array($tip,['dep','pos','bar']) ){
      $_eje = self::$EJE."lis_$tip";
      $_ide = self::$IDE."lis_$tip";
    }else{
      $_eje = $tip;
      $_ide = $tip;
    }      

    $tod = empty($opc);
    
    // - expandir-contraer items
    if( $tod || in_array('tog',$opc) ){
      
      $_ .= Doc_Ope::lis_tog([
        'eje'=>"{$_eje}('tog',this," 
      ]);
    }
    // - filtrar items
    if( $tod || in_array('ver',$opc) ){ 
      $ide = "{$_ide}-".Doc::ide($_ide);
      $_ .= Doc_Ope::var('_','ver',[ 
        'ide'=>$ide,
        'nom'=> "Filtrar",
        'ite'=> [ 'class'=>'tam-cre' ],
        'htm'=> Doc_Ope::lis_ver([ 
          'ide'=>$ide,
          'cue'=>in_array('cue',$opc) ? 0 : NULL, 
          'eje'=>"{$_eje}('ver',this);" 
        ])
      ]);
    }

    if( !empty($_) ){ 

      if( !isset($var['ope']) ) $var['ope'] = [];

      Ele::cla($var['ope'],"-ite"); 

      $var['ope']['eti'] = "form";
      $var['ope']['htm'] = $_;

      $_ = Ele::val($var['ope']);
    }      
    return $_;
    
  }// - toggles: expandir / contraer
  static function lis_tog( array $var = [] ) : string {
    
    $_eje = self::$EJE."lis_tog";      

    if( !isset($var['ope']) ) $var['ope'] = [];

    Ele::cla($var['ope'], "ope_bot", 'ini');

    $_eje_val = isset($var['eje']) ? $var['eje'] : "$_eje(this,";

    return "
    <fieldset".Ele::atr($var['ope']).">
      ".Doc_Val::ico('ope_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".Doc_Val::ico('ope_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";

  }// - Filtros : ( operador ) + valor textual + ( totales )
  static function lis_ver( string | array $var = [], array $ele = [] ) : string {
    $_ = "";
    
    // opciones de filtro por texto
    if( isset($var['ope']) ){
      
      if( empty($var['ope']) ) $var['ope'] = "**";

      $_ .= Dat::ope_opc(['ver','tex'],[
        'ite'=>[ 
          'dat'=>"()($)dat()" 
        ],
        'eti'=>[ 
          'name'    =>"ope", 
          'title'   =>"Seleccionar un operador de comparación...", 
          'val'     =>$var['ope'], 
          'class'   =>isset($ele['ope']['class']) ? $ele['ope']['class'] : "mar_hor-1", 
          'onchange'=>$var['eje']
        ]
      ]);
    }

    // ingreso de valor a filtrar
    $_ .= Doc_Var::tex('ora', isset($var['val']) ? $var['val'] : '', [ 
      'id'    =>isset($var['ide']) ? $var['ide'] : NULL, 
      'name'  =>"val",
      'title' =>"Introducir un valor de búsqueda...",
      'oninput'=>!empty($var['eje']) ? $var['eje'] : NULL,
      'class' =>isset($ele['tex']['class']) ? $ele['tex']['class'] : NULL,
      'style' =>isset($ele['tex']['style']) ? $ele['tex']['class'] : NULL
    ]);

    // agrego totales
    if( isset($var['cue']) ){ $_ .= "
      <p class='mar_izq-1' title='Items totales'>
        <c>(</c><n name='tot'>".( is_array($var['cue']) ? count($var['cue']) : $var['cue'] )."</n><c>)</c>
      </p>";
    }
    
    return $_;
  }
}