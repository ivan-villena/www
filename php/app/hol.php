<?php
  // $this = _api

  // biblioteca
  class _app_hol_bib { 

    static string $IDE = "_app_hol_bib-";
    static string $EJE = "_app_hol_bib.";

    // encantamiento
    static function enc( string $tip, array $ope = [] ) : string {
      $esq = "hol";
      $_ = ""; 
      $_ide = self::$IDE."enc('$tip',";
      $_eje = self::$EJE."enc('$tip',";

      switch( $tip ){
      // libro del kin        
      case 'kin':
        $est = "kin";
        $_ = "
        <!-- libro del kin -->
        <form class='inf' esq='$esq' est='$est'>

          <div class = 'val'>

            <fieldset class='val'>

              "._doc_val::tog_ope()."

              "._doc::var('atr',['hol','kin','ide'],[ 'nom'=>"ver kin", 'ope'=>[ 'onchange'=>"{$_eje}this);" ] ])."

            </fieldset>

            <fieldset class='ope'>

              "._doc::ico('nav_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"{$_eje}this,'nav');" ])."
            
            </fieldset>

          </div>

          <output class='hol-kin'></output>
          
        </form>
                
        <nav>";
          $nav_cas = 0; $nav_ond = 0;
          $arm_tra = 0; $arm_cel = 0;
          $cro_est = 0; $cro_ele = 0;
          $gen_enc = 0; $gen_cel = 0;
          foreach( _hol::_('kin') as $_kin ){

            // castillo
            if( $_kin->nav_cas != $nav_cas ){
              $nav_cas = $_kin->nav_cas;
              $_cas = _hol::_('kin_nav_cas',$_kin->nav_cas); 
              if( $nav_cas != 1 ){ $_ .= "
                  </section>

                </section>
                ";
              }$_ .= "

              "._doc_val::tog(['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."

              <section data-kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>

                <p cas='{$_cas->ide}'>"._doc::let("Corte {$_cas->cor}: {$_cas->fun}")."</p>

              ";
            }
            // génesis
            if( $_kin->gen_enc != $gen_enc ){
              $gen_enc = $_kin->gen_enc;
              $_gen = _hol::_('kin_gen_enc',$_kin->gen_enc); $_ .= "
              <p class='tit' data-gen='{$_gen->ide}'>GÉNESIS "._tex::let_may($_gen->nom)."</p>";
            }
            // onda encantada
            if( $_kin->nav_ond != $nav_ond ){
              $nav_ond = $_kin->nav_ond;
              $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
              $_sel = _hol::_('sel',$_ond->sel); 
              $ond = _num::ran($_ond->ide,4);

              if( $nav_ond != 1 && $ond != 1 ){ $_ .= "
                </section>";
              }
              $_ .= "
              "._doc_val::tog([
                'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 'htm'=>_doc::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
              ])."

              <section data-kin_nav_ond='{$_ond->ide}'>

                <p class='let-enf' ond='{$_ond->ide}'>Poder "._tex::art_del($_sel->pod)."</p>";
            }
            // célula armónica : titulo + lectura
            if( $_kin->arm_cel != $arm_cel ){
              $arm_cel = $_kin->arm_cel;
              $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
              </section>

              "._doc_val::tog(['eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>"._doc::let(_tex::let_may($_cel->des))])."

              <section data-kin_arm_cel='{$_cel->ide}'>
              ";
            }
            // kin : ficha + nombre + encantamiento
            $_ .= "
            <div data-kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
              <div class='hol-kin'>
                "._doc::ima('hol','kin',$_kin->ide,['class'=>'mar-aut'])."
                <p>
                  <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='let_col-4-{$_kin->arm_cel_dia}'>"._doc::let(_tex::let_may($_kin->nom))."</c>
                  <br><q>"._doc::let($_kin->des)."</q>                  
                </p>
              </div>
            </div>";
          }$_ .= "
          </section>
        </nav>";        
        break;
      // índice armónico de 13 trayectorias y 65 células
      case 'arm': 
        $est = "kin_arm";
        $htm = [];
        $arm_cel = 0;
        if( !isset($ele['nav']) ) $ele['nav'] = [];
  
        foreach( _hol::_('kin_arm_tra') as $_tra ){
  
          $lis_ide = "Trayectoria $_tra->ide: Tono $_tra->ton, $_tra->ton_des";
          $htm[$lis_ide] = [];
  
          foreach( _hol::_('sel_arm_cel') as $_cel ){
            $arm_cel++;
            $_cel = _hol::_('kin_arm_cel',$arm_cel);
            $htm[$lis_ide][] = "
            <a class='tex' href='#kin_arm_cel-{$_cel->ide}-'>
              <n>{$_cel->ide}</n><c>.</c> <b class='ide'>{$_cel->nom}</b>"._doc::let(": {$_cel->des}")."
            </a>";
          }
        }
        _ele::cla( $ele['nav'], "dis-ocu" );
        $ele['opc'] = ['tog','ver'];
        $_ = "
  
        "._doc_val::tog([ 'eti'=>'h3', 'htm'=>_doc::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], [ 'ico'=>['ocu'=>1] ])."
        
        <nav"._htm::atr($ele['nav']).">
  
          "._doc_lis::val($htm,$ele)."
        </nav>";    
        break;
      }
      return $_;
    }
    // factor maya
    static function fac( string $tip, array $dat = [], array $ele = [] ) : string {      
      $_ = "";
      switch( $tip ){
      case 'kin': 
        $ond = 0;
        $_ = "
        <table>";
          if( !empty($ele['tit']) ){ $_.="
            <caption>
              ".( !empty($ele['tit']['htm']) ? "<p class='tit'>"._doc::let($ele['tit']['htm'])."</p>" : '' )."
            </caption>";
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
                  "._doc::ima('hol','kin_nav_ond',$_ond,['class'=>"mar_der-1"])."
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
      }
      return $_;
    }
    // telektonon
    static function tel( string $tip, string | int $ide, array $ele = [] ) : string {
      $_ = "";
      switch( $tip ){
      case 'rad': 
        $ele['lis'] = ['class'=>"ite"];
        $ele['ite'] = ['class'=>"mar_aba-1"];      

        foreach( _hol::_('rad') as $_rad ){ $_ []=
          _doc::ima('hol','rad',$_rad,['class'=>"mar_der-1"])."
          <p>
            <b class='ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
            <br><q>"._doc::let($_rad->rin_des)."<c>.</c></q>
          </p>";
        }
        $_ = _doc_lis::val($_,$ele);
        break;
      case 'lib': 
        $_dat = [
          4  => ['ide'=> 4, 'tit'=>"Libro de la Forma Cósmica" ],
          7  => ['ide'=> 7, 'tit'=>"Libro de las Siete Generaciones Perdidas" ],
          13 => ['ide'=>13, 'tit'=>"Libro del Tiempo Galáctico" ],
          28 => ['ide'=>28, 'tit'=>"Libro Telepático para la Redención de los Planetas Perdidos" ]
        ];
        $_ = [];
        for( $pos = 1; $pos <= intval($ide); $pos++ ){ $_ []= "
          <figure class='mar-0'>
            <div class='ite'>
              <img src='".SYS_REC."hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta ' class='mar_der-1' style='width:24rem; height: 30rem;'>
              <img src='".SYS_REC."hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta ' class='mar_izq-1' style='width:24rem; height: 30rem;'>              
            </div>
          </figure>";
        }
        $_ = _doc_lis::bar( $_, $ele);
        break;
      }
      return $_;
    }
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // cargo página
  $esq = 'hol';
  $_bib = SYS_NAV."hol/bib/";

  // proceso fecha : informes + tableros
  $_hol_dia = _hol::val( date('Y/m/d') );

  // inicializo datos
  $_val = $_hol_dia;
  $ide = "";
  if( $val_fec = !empty($_uri->cab) && in_array($_uri->cab,['inf','tab']) ){

    $dat_ide = empty($_uri->art) ? 'kin' : $_uri->art;

    $hol_val = !empty($_uri->val) ? $_uri->val : ( !empty($_SESSION['hol-val']) ? $_SESSION['hol-val'] : '' );    

    // proceso valor e identificadores por peticion : ?ide=val
    if( !empty($_uri->val) ){

      $val = explode('=',$hol_val);

      if( isset($val[1]) ){

        $val[1] = isset($val[1]) ? $val[1] : '';

        if( $val[0]=='fec' || $val[0]=='sin' ){
          $_val = _hol::val($val[1],$val[0]);
        }
        else{        
          $_val[ $val[0] ] = $val[1];
        }
      }
      else{
        $_val[ $dat_ide ] = _hol::val( $dat_ide, $val[0] );
      }
    }

    // actualizo valor de sesion
    $_SESSION['hol-val'] = $hol_val;    
    
    // cargo operadores
    $est = explode('-',$dat_ide)[0];
    $ide = "{$esq}.{$est}";
    
    // fecha => muestro listado por ciclos => acumulo posiciones
    if( isset($_val['fec']) ) _hol::val_acu( isset($est) ? $est : 'kin', $_val );
  }
  // cargo datos y estructuras
  $dat_val = _api::_('dat');
  $dat_est = [ 'api'=>[ "fec" ], 'hol'=>[ "kin", "psi" ] ];
  
  // cargo índice por navegador para titulos
  if( !empty($_uri->art) && in_array($_uri->cab,[ 'bib', 'inf' ]) ){
    $_['nav']['art'] = [ 'ico' => "nav_val", 'nom' => "Índice de Contenidos",
      'htm' => _app_nav::art( $nav = _dat::var("_api.doc_nav",[
        'ver'=>"esq = '{$_uri->esq}' AND cab = '{$_uri->cab}' AND ide = '{$_uri->art}'", 'ord'=>"pos ASC", 'nav'=>'pos'
      ]),[        
        'nav'=>[ 'ide'=>'art' ],
      ])
    ];
  }
  
  // menu principal: agrego valor diario
  $nav_htm_ini = "
    <nav class='mar-1'>
      "._hol_val::dia($_val)."
    </nav>
  ";
  $nav_htm_fin = "";
  
  // ficha del usuario
  if( !empty($_usu->ide) ){    
    $htm = "
    <article class='".DIS_OCU."' ide='usu'>

      <h2>Ficha Personal</h2>

      "._usu_hol::fic()."

    </article>";
    $_['nav_fin']['usu'] = [ 'ico'=>"ses_usu", 'nom'=>"Ficha Personal", 'htm'=>$htm ];    
  }    

  // imprimo secciones
  ob_start();  
  // inicio : 
  if( empty($_uri->cab) ){
    $_hol_kin = _hol::_('kin',$_hol_dia['kin']);
    $_ope = [    
      'kin' => [ 'nom'=>"Orden Sincrónico", 'des'=>"" ], 
      'psi' => [ 'nom'=>"Orden Cíclico",    'des'=>"" ]
      ];
    ?>
    
    <h1>Inicio del Sincronario</h1>        

    <?= _hol_fic::kin('enc',$_hol_kin) ?>    

    <?php          
    echo 

    _doc::nav('bar',$_ope)."
    
    <section>";
    foreach( $_ope as $i => $v ){ echo "
      <div ide='$i' class='".(  $i == 'kin' ?  "" : DIS_OCU )."'>
        "._hol_val::$i( $_val[$i], [ 'lis'=>[ 'style'=>"max-height: 70vh; overflow: auto;" ], 'opc'=>['tog','ver'] ])."
      </div>";
    } echo "
    </section>";
  }
  // por menu : introduccion
  elseif( empty($_uri->art) ){
    switch( $_uri->art ){
    // bibliografía
    case 'bib': 
      ?>

      <?php
      break;
    // informe
    case 'inf': 
      ?>

      <?php
      break;
    // tablero
    case 'tab': 
      ?>

      <?php
      break;
    }
  }
  // por articulos : bibliografía + informes + tableros
  else{
    // tableros : valor + opciones + posicion + listado
    if( $_uri->cab == 'tab' ){

      // operadores del tablero
      $_tab =  _app::tab('hol',str_replace('-','_',$_uri->art));
      $tab_ele = [];
      $tab_ope = !empty($_tab->ope) ? $_tab->ope : [];
      $tab_opc = !empty($_tab->opc) ? $_tab->opc : [];
  
      // fecha => muestro listado por ciclos
      $_ide = _dat::ide($ide);
      if( !empty( $_val['fec'] ) ){
        // joins 
        if( in_array($_ide['est'],['kin','psi']) ){
          // datos
          $tab_ope['dat'] = $dat_val;
          // estructuras
          $tab_ope['est'] = $dat_est;
          // operador de valores
          $tab_ope['val']['ver'] = $tab_ope['val']['mar'] = $tab_ope['val']['pos'] = 1;
        }
      }

      // pantalla: listado de posiciones
      $lis_opc = isset($_art['lis']['opc']) ? $_art['lis']['opc'] : [];
      $lis_opc []= "ite_ocu";
      $lis_ele = isset($_art['lis']['ele']) ? $_art['lis']['ele'] : [];
      $lis_ope = isset($_art['est']['ope']) ? $_art['est']['ope'] : [ 'tit'=>['cic','gru'], 'det'=>['des'] ];
      $lis_ope['val'] = isset($tab_ope['val']) ? $tab_ope['val'] : NULL;
      $lis_ope['dat'] = isset($tab_ope['dat']) ? $tab_ope['dat'] : NULL;
      $lis_ope['est'] = isset($tab_ope['est']) ? $tab_ope['est'] : NULL;
          
      $_['win']['est'] = [ 'ico' => "est", 'nom' => "Listado de Posiciones",
        'art' => [ 'style'=>"max-width: 55rem; height: 90vh;" ],
        'htm' => _app_est::ope('tod', $ide, $lis_ope, $lis_ele, ...$lis_opc )
      ];

      // navegacion : operadores del tablero
      $_['nav']['tab'] = [ 'ico' => "tab", 'nom' => "Tablero", 'htm' => "
        <article ide='tab' style='width: 30rem; padding: 0;'>

          "._app_tab::ope('tod', $ide, $tab_ope, $tab_ele, ...$tab_opc )."
          
        </article>"
      ];
      
      // imprimo tablero en página principal
      $tab_ide = explode('-',$_uri->art);
      $tab_ope['val_pos'] = $_val;
      if( isset($tab_ide[1]) && method_exists("_hol_tab",$tab_ide[0]) ){
        echo _hol_tab::{$tab_ide[0]}($tab_ide[1], $tab_ope, [ 
          'pos'=>[ 'onclick'=>"_app_tab.val_mar(this);" ]
        ], ...$tab_opc);
      }else{
        echo _doc::let("Error: No existe el tablero del Holon solicitado con '$_uri->art'");
      }      
    }
    else{
      switch( $_uri->cab ){
      // bibliografía : datos + glosario + libros
      case 'bib': 
        switch( $_uri->art ){
        case 'ide': break;
        // datos : codigos y cuentas
        case 'dat': 
          ?>           
          <h1>Códigos y Cuentas del Sincronario</h1>
          <!-- 7 : plasmas radiales -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['01']->pos}-", 'htm'=>_doc::let($nav[1]['01']->nom) ])?>
          <section>
    
            <p>En <a href="lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a> se divide el año en <n>13</n> lunas de <n>28</n> días cada una. A su vez, cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>
    
            <p>Los plasmas se utilizan para nombrar a los días de cada semana<c>-</c>heptada<c>.</c></p>
    
            <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','pod'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
            
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['01']->pos}-", 'htm'=>_doc::let($nav[2]['01']['01']->nom) ])?>
            <section>
    
              <p>En el <a href="tel#_02-06-" target="_blank">telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>
    
              <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario <c>(</c> familia portal<c>:</c> abren los portales codificando el inicio de los anillos solares<c>;</c> y familia señal<c>:</c> descifran el misterio codificando los días fuera del tiempo<c>.</c> Ver <a href="enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>
    
              <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <p>En el <a href="rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>
    
              <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del banco-psi <c>(</c> el campo resonante de la tierra <c>)</c> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>
    
              <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
            </section>
    
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['02']->pos}-", 'htm'=>_doc::let($nav[2]['01']['02']->nom) ])?>
            <section>
    
              <p>En el <a href="ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>
    
              <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_ene','pla_fue_pre','pla_fue_pos'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>              
    
              <p>Desde este paradigma los plasmas son <q>componenees electrónicos</q> constituídos por la combinación de <n>12</n> líneas electrónicas de fuerza que convergen en <n>6</n> tipos de electricidad clasificadas según la cantidad de cargas positivas o negativas que contengan<c>.</c></p>
    
              <p>Los <n>6</n> tipos de electricidad son<c>:</c></p>
    
              <?=_app_est::lis('hol.rad_pla_ele',[ 'atr'=>['ide','cod','nom','des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <p>Los <n>12</n> tipos de líneas electrónicas<c>:</c></p>
    
              <?=_app_est::lis('hol.rad_pla_fue',[ 'atr'=>['ide','nom','ele_pre','ele_ope','ele_pos'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
            </section>  
            
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['03']->pos}-", 'htm'=>_doc::let($nav[2]['01']['03']->nom) ])?>
            <section>
    
              <p>En el <a href="tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>
    
              <p>En el <a href="rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>
    
              <p>Por otro lado<c>,</c> en las <a href="ato#_03-06-" target="_blank" rel="">Autodeclaraciones Diarias de Padmasambhava</a> se describen las afirmaciones correspondientes a cada plasma<c>.</c></p>
    
              <?=_app_est::lis('hol.rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','hep','hep_pos','pla_des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>      
    
            </section>      
    
          </section>  
          <!-- 13 : tonos galácticos -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['02']->pos}-", 'htm'=>_doc::let($nav[1]['02']->nom) ])?>
          <section>
            <!-- rayos de pulsación -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['02']['01']->pos}-", 'htm'=>_doc::let($nav[2]['02']['01']->nom) ])?>
            <section>
    
              <p>En <a href="fac#_04-04-01-" target="_blank">el Factor Maya</a> se definen como rayos de pulsación<c>,</c> cada uno con una función radio<c>-</c>resonante en particular<c>.</c></p>
    
              <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','nom','gal'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
            </section>
            <!-- simetría especular -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['02']['02']->pos}-", 'htm'=>_doc::let($nav[2]['02']['02']->nom) ])?>
            <section>
    
              <p>En el <a href="fac#_04-04-01-02-" target="_blank">Factor Maya</a><c>.</c></p>
    
              <?=_app_est::lis('hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
              
            </section>        
            <!-- principios de la creacion -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['02']['03']->pos}-", 'htm'=>_doc::let($nav[2]['02']['03']->nom) ])?>
            <section>
    
              <p>En <a href="enc#_03-11-" target="_blank">el Encantamiento del sueño</a> se definene como tonos galácticos de la creación<c>.</c></p>
    
              <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','nom','des','acc'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
            </section>
            <!-- O.E. de la Aventura -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['02']['04']->pos}-", 'htm'=>_doc::let($nav[2]['02']['04']->nom) ])?>
            <section>
    
              <p>En el <a href="enc#_03-12-" target="_blank">Encantamiento del sueño</a><c>.</c></p>
    
              <?=_app_est::lis('hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc'], 'cic'=>['tit'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
              
            </section>        
            <!-- pulsar dimensional -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['02']['05']->pos}-", 'htm'=>_doc::let($nav[2]['02']['05']->nom) ])?>
            <section>
    
              <p>En el <a href="enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>
    
              <?=_app_est::lis('hol.ton_dim', [], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
              
            </section>
            <!-- pulsar matiz -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['02']['06']->pos}-", 'htm'=>_doc::let($nav[2]['02']['06']->nom) ])?>
            <section>
    
              <p>En el <a href="enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>
    
              <?=_app_est::lis('hol.ton_mat', [], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
              
            </section>
          </section>  
          <!-- 20 : sellos solares -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['03']->pos}-", 'htm'=>_doc::let($nav[1]['03']->nom) ])?>
          <section>
            <!-- signos direccionales -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['01']->pos}-", 'htm'=>_doc::let($nav[2]['03']['01']->nom) ])?>
            <section>
    
              <p>En <a href="fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>
    
              <?=_app_est::lis('hol.sel_cic_dir',[ ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- desarrollo del ser -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['01']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['01']['01']->nom) ])?>
              <section>
    
                <p>En <a href="fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
              <!-- etapas evolutivas de la mente -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['01']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['01']['02']->nom) ])?>
              <section>
    
                <p>En <a href="fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_cic_men',[ 'atr'=>['sel','nom','des','lec'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
              <!-- familias ciclicas -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['01']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['01']['03']->nom) ])?>
              <section>
    
                <p>En <a href="fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
            </section>
            <!-- colocacion cromática -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['02']->pos}-", 'htm'=>_doc::let($nav[2]['03']['02']->nom) ])?>
            <section>
              
              <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
              
              <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- familias -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['02']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['02']['01']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- clanes -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['02']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['02']['02']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
            </section>
            <!-- colocación armónica -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['03']->pos}-", 'htm'=>_doc::let($nav[2]['03']['03']->nom) ])?>
            <section>
    
              <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>
    
              <?=_app_est::lis('hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- razas -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['03']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['03']['01']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- células -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['03']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['03']['02']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
            </section>            
            <!-- parejas del oráculo -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['04']->pos}-", 'htm'=>_doc::let($nav[2]['03']['04']->nom) ])?>
            <section>
    
              <p>En <a href="enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
              <p>En <a href="tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>
    
              <!-- analogos -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['04']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['04']['01']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_par_ana',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- antipodas -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['04']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['04']['02']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_par_ant',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- ocultos -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['04']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['04']['03']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_par_ocu',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
            </section>                  
            <!-- holon Solar -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['05']->pos}-", 'htm'=>_doc::let($nav[2]['03']['05']->nom) ])?>
            <section>
    
              <p>El código 0-19</p>              
    
              <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- orbitas planetarias -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['05']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['05']['01']->nom) ])?>
              <section>
    
                <p>En <a href="fac" target="_blank">el Factor Maya</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_sol_pla',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
              <!-- células solares -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['05']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['05']['02']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_sol_cel',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
              <!-- circuitos de telepatía -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['05']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['05']['03']->nom) ])?>
              <section>
    
                <p>En <a href="tel" target="_blank">Telektonon</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_sol_cir',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>              
    
            </section>
            <!-- holon planetario -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['06']->pos}-", 'htm'=>_doc::let($nav[2]['03']['06']->nom) ])?>
            <section>  
              
              <p>En <a href="enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
              <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- centros galácticos -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['06']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['06']['01']->nom) ])?>
              <section>
    
                <?=_app_est::lis('hol.sel_pla_cen',[  ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- flujos de la fuerza-g -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['06']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['06']['02']->nom) ])?>
              <section>
    
                <p>En <a href="enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
                <?=_app_est::lis('hol.sel_pla_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>              
    
            </section>
            <!-- holon humano -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['03']['07']->pos}-", 'htm'=>_doc::let($nav[2]['03']['07']->nom) ])?>
            <section>
    
              <p>En <a href="enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
              <?=_app_est::lis('hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- Centros Galácticos -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['01']->nom) ])?>
              <section>
    
                <?=_app_est::lis('hol.sel_hum_cen',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- Extremidades -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['02']->nom) ])?>
              <section>
    
                <?=_app_est::lis('hol.sel_hum_ext',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>           
              
              <!-- dedos -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['03']->nom) ])?>
              <section>            
                
                <?=_app_est::lis('hol.sel_hum_ded',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <!-- lados -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['04']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['04']->nom) ])?>
              <section>
                
                <?=_app_est::lis('hol.sel_hum_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>              
    
            </section>
    
          </section>
          <!-- 28 : días del giro solar -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['04']->pos}-", 'htm'=>_doc::let($nav[1]['04']->nom) ])?>
          <section>
    
            <p>En <a href="" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    
            <?=_app_est::lis('hol.lun',[ 'atr'=>['ide','arm','rad','ato_des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
            <!-- 4 heptadas -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['04']['01']->pos}-", 'htm'=>_doc::let($nav[2]['04']['01']->nom) ])?>
            <section>
    
              <p>En <a href="lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>
    
              <p>En <a href="" target="_blank">el Telektonon</a><c>.</c></p>
    
              <p>En <a href="" target="_blank">el átomo del tiempo</a><c>.</c></p>
    
            </section>
    
          </section>
          <!-- 52 : posiciones del castillo-g -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['05']->pos}-", 'htm'=>_doc::let($nav[1]['05']->nom) ])?>
          <section>
    
            <!-- -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['05']['01']->pos}-", 'htm'=>_doc::let($nav[2]['05']['01']->nom) ])?>
            <section>
    
            </section>
    
          </section>
          <!-- 64 : hexagramas del i-ching -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['06']->pos}-", 'htm'=>_doc::let($nav[1]['06']->nom) ])?>
          <section>
    
            <!-- -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['06']['01']->pos}-", 'htm'=>_doc::let($nav[2]['06']['01']->nom) ])?>
            <section>
    
            </section>
    
          </section>  
          <!-- 260 : tzolkin -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['07']->pos}-", 'htm'=>_doc::let($nav[1]['07']->nom) ])?>
          <section>
    
            <!-- -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['07']['01']->pos}-", 'htm'=>_doc::let($nav[2]['07']['01']->nom) ])?>
            <section>
    
              <!-- -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['07']['01']['01']->pos}-", 'htm'=>_doc::let($nav[3]['07']['01']['01']->nom) ])?>
              <section>
    
              </section>            
    
            </section>
    
          </section>
          <!-- 364 : banco-psi -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['08']->pos}-", 'htm'=>_doc::let($nav[1]['08']->nom) ])?>    
          <section>
    
            <!-- -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['08']['01']->pos}-", 'htm'=>_doc::let($nav[2]['08']['01']->nom) ])?>    
            <section>
    
            </section>
    
          </section>    
          <?php
          break;
        // libros en html
        default: 
          if( !empty( $rec = _arc::rec( $rec_val = "php/$_dir->art" ) ) ){

            include( $rec );    
          }
          else{ echo "
            <p class='err'><c>{-_-}</c> No existe el archivo <c>'</c><b class='ide'>{$rec_val}</b><>'</c></p>";
          }
          break;
        }  
        break;
      // informes : ciclos diario + kin planetario
      case 'inf': 
        switch( $_uri->art ){
        // ciclos del tiempo
        case 'val':
          // galáctico
          $_kin = _hol::_('kin', $_val['kin']);
          $_sel = _hol::_('sel',$_kin->arm_tra_dia);
          $_ton = _hol::_('ton',$_kin->nav_ond_dia);
          // solar
          $_psi = _hol::_('psi', $_val['psi']);
          ?>
          <!-- sincronico : 260 -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['01']->pos}-", 'htm'=>_doc::let($nav[1]['01']->nom) ])?>
          <section>
            <!-- Kin -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['01']->pos}-", 'htm'=>_doc::let($nav[2]['01']['01']->nom) ])?>
            <section>
              <!-- ficha del encantamiento -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['01']['01']['01']->pos}-", 'htm'=>_doc::let($nav[3]['01']['01']['01']->nom) ])?>
              <section>
    
                <?= _hol_fic::kin('enc',$_kin) ?>
    
                <br>
    
                <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>
    
                <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>
    
              </section>
              <!-- 4 + 1 : parejas -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['01']['01']['02']->pos}-", 'htm'=>_doc::let($nav[3]['01']['01']['02']->nom) ])?>
              <section>

                <?= _hol_tab::kin('par',[ 
                  'ide'=>$_kin->ide,
                  'sec'=>[ 'par'=>0 ],
                  'pos'=>[ 'ima'=>'hol.kin.ide' ]
                ],[
                  'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
                  'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
                ]) ?>
                <!-- Descripciones -->
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['02']['01']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['02']['01']->nom) ])?>
                <section>
    
                  <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            
    
                  <?= _hol_fic::kin('par',$_kin) ?>
    
                </section>  
                <!-- Lecturas diarias -->
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['02']['02']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['02']['02']->nom) ])?>
                <section>
    
                  <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>
    
                  <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>
    
                  <?= _hol_inf::kin('par',$_kin,[ 'atr'=>"pro" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
                  <br>
                  
                  <p>En <a href="<?=$_bib?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>
    
                  <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>
    
                  <?= _hol_fic::kin('par_lec',$_kin) ?>
    
                </section>  
                <!-- Posiciones en el tzolkin -->  
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['02']['03']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['02']['03']->nom) ])?>
                <section>
    
                  <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        
    
                  <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>
    
                  <?= _hol_inf::kin('par',$_kin,[ 'atr'=>"cic" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
                </section>  
                <!-- Sincronometría del holon -->
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['02']['04']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['02']['04']->nom) ])?>
                <section>
    
                  <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>
    
                  <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>
    
                  <br>
    
                  <?= _hol_inf::kin('par',$_kin,[ 'atr'=>"gru" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
                </section>
                
              </section>
              <!-- 4 x 52:13 nave del tiempo -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['01']['01']['03']->pos}-", 'htm'=>_doc::let($nav[3]['01']['01']['03']->nom) ])?>
              <section>
                <!-- x52 : Castillo Fractal -->  
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['03']['01']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['03']['01']->nom) ])?>
                <section>
    
                    <?php
                    $_cas = _hol::_('kin_nav_cas',$_kin->nav_cas);      
                    ?>
                    
                    <nav>
                      <p>Ver el <a href='<?=$_bib?>enc#_01-01-' target='_blank'>Génesis del Encantamiento del Sueño</a> en el encantamiento del sueño<c>...</c></p>
                    </nav>
    
                    <?= _hol_fic::kin('nav_cas',$_cas) ?>
                    
                    <?= _hol_tab::kin('nav_cas',[ 
                      'ide'=>$_cas->ide, 
                      'val_pos'=>$_kin->ide,
                      'pos'=>[ 'ima'=>'hol.kin.ide' ]
                    ], [
                      'cas'=>['class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen"], 
                      'pos'=>['style'=>"width:2.5rem; height:2.5rem;"]  
                    ]) ?>
    
                    <h3>En los <n>26.000</n> años del <a href='<?=$_bib?>enc#_01-' target='_blank'>Génesis del Encantamiento del Sueño</a></h3>
    
                    <nav>    
                      <p>Ver <a href='<?=$_bib?>enc#_03-06-' target='_blank'>el Índice de los castillos</a> en el Encantamiento del Sueño</p>
                    </nav>
    
                    <h3>En los <n>5.200</n> años del <a href='<?=$_bib?>fac#_01-'> Rayo de Sincronización Galáctica</a></h3>
    
                    <nav>
                      <p>Ver <a href='<?=$_bib?>fac#' target='_blank'>los 20 ciclos AHAU</a> en el Factor Maya</p>
                    </nav>  
    
                </section>
                <!-- x13 : Onda Encantada -->  
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['03']['02']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['03']['02']->nom) ])?>
                <section>
                  <?php
                  $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);  
                  ?>
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-12-' target='_blank'>la Onda Encantada de la Aventura</a> en el Encantamiento del Sueño</p>
                  </nav>
    
                  <?= _hol_tab::kin('nav_ond', [
                    'ide'=>$_ond,
                    'sec'=>[ 'par'=>1 ],
                    'pos'=>[ 'ima'=>'hol.kin.ide' ]
                  ], [
                    'ond'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
                    'pos'=>[ 'style'=>"width:6rem; height:6rem;" ]
                  ]) ?>
    
                </section>  
              </section>
              <!-- 13 x 20:4 Giro Galáctico -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['01']['01']['04']->pos}-", 'htm'=>_doc::let($nav[3]['01']['01']['04']->nom) ])?>
              <section>
                <!-- x20 : Trayectoria Armónica -->  
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['04']['01']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['04']['01']->nom) ])?>
                <section>
                  <?php
                  $_tra = _hol::_('kin_arm_tra',$_kin->arm_tra);
                  $_ton = _hol::_('ton',$_kin->nav_ond_dia);
                  ?>    
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>el Gran Ciclo</a> en el <cite>Factor Maya</cite><c>...</c></p>
                  </nav>
    
                  <?= _hol_fic::kin('arm_tra',$_tra) ?>
    
                  <p><?= _doc::let($_tra->lec) ?></p>
    
                  <?= _hol_tab::kin('arm_tra', [
                    'ide'=>$_tra,
                    'sec'=>[ 'par'=>1 ],
                    'pos'=>[ 'ima'=>'hol.kin.ide' ]
                  ], [
                    'tra'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen", 'style'=>"grid-gap: .5rem;" ],
                    'pos'=>[ 'style'=>"width:5rem; height:5rem;" ],
                    'pos-0'=>[ 'style'=>"width:4rem; height:4rem;" ]
                  ]) ?>
    
                  <p class='tit let-4'>Codificado por el tono <?= $_ton->nom ?></p>
    
                  <p><?= $_ton->des ?> del Giro Galáctico.</p>
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Células del Tiempo</a> en el Encantamiento del Sueño<c>...</c></p>
                  </nav>
    
                </section>
                <!-- x4 : Célula del Tiempo -->  
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['04']['01']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['04']['01']->nom) ])?>
                <section>
                  <?php
                  $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel);    
                  ?>    
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Razas Raíz Cósmicas</a> en el Encantamiento del Sueño</p>
                  </nav>
    
                </section>  
              </section>
              <!-- 4 x 13:5 Giro Espectral -->
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['01']['01']['05']->pos}-", 'htm'=>_doc::let($nav[3]['01']['01']['05']->nom) ])?>
              <section>
                <!-- x65 : Estación Galáctica -->
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['05']['01']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['05']['01']->nom) ])?>
                <section>
                  <?php
                  $_est = _hol::_('kin_cro_est',$_kin->cro_est);
                  ?>
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
                  </nav>    
    
                </section>
                <!-- x5 : Elemento Cromático -->  
                <?=_doc_val::tog(['eti'=>"h5", 'id'=>"_{$nav[4]['01']['01']['05']['01']->pos}-", 'htm'=>_doc::let($nav[4]['01']['01']['05']['01']->nom) ])?>
                <section>
                  <?php
                  $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
                  ?>
                  
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-16-' target='_blank'>Colocación Cromática</a> en el Encantamiento del Sueño</p>
                  </nav>
    
                </section>
              </section>
              <!-- Módulo Armónico : pag + ene + chi -->  
              <?=_doc_val::tog(['eti'=>"h4", 'id'=>"_{$nav[3]['01']['01']['06']->pos}-", 'htm'=>_doc::let($nav[3]['01']['01']['06']->nom) ])?>
              <section>
              </section>
            </section>
            <!-- Sello Solar -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['02']->pos}-", 'htm'=>_doc::let($nav[2]['01']['02']->nom) ])?>
            <section>  
              <!-- Ficha -->
              <?= _hol_fic::ton([],$_ton) ?>

              <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    

              <!-- Desarrollo del ser -->

              <!-- Colocacion Cromática -->

              <!-- Colocacion Armónica -->

              <!-- Holon Solar -->

              <!-- Holon Planetario -->

              <!-- Holon Humano -->
    
            </section>
            <!-- Tono Galáctico -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['03']->pos}-", 'htm'=>_doc::let($nav[2]['01']['03']->nom) ])?>
            <section>
              <!-- Ficha -->
              <?= _hol_fic::sel([],$_sel) ?>
    
              <p><?= _doc::let($_sel->des_pro) ?></p>
    
              <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'></a> en </p>

              <!-- Aventura de la Onda Encantada -->

              <!-- Simetría Especular -->

              <!-- Pulsares Dimensionales -->

              <!-- Pulsares Matiz Entonado -->
    
            </section>      
    
          </section>
          <!-- cíclico : 365 -->
          <?=_doc_val::tog(['eti'=>"h2", 'id'=>"_{$nav[1]['02']->pos}-", 'htm'=>_doc::let($nav[1]['02']->nom) ])?>
          <section>
            <!-- PsiCronos -->
            <?=_doc_val::tog(['eti'=>"h3", 'id'=>"_{$nav[2]['01']['01']->pos}-", 'htm'=>_doc::let($nav[2]['01']['01']->nom) ])?>
            <section>
    
            </section>

            <!-- x91: Estaciones Solares -->

            <!-- x28: Giros Lunares -->

            <!-- x20: Vinales -->

            <!-- x7: Heptadas -->

            <!-- x5: Cromaticas -->
    
          </section>
          <?php
          break;
        // firma galáctica
        case 'hum': 
          ?>
          <?php
          break;
        }
        break;
      }      
    }
  }

  // tutoriales de la página
  if( isset( $art_ini ) ){
    $_['win_fin']['tut'] = [ 'ico'=>"opc", 'nom'=>"Tutorial", 'htm'=>$art_ini ];
  }

  // genero articulo por contenido
  $_['sec'] = ob_get_clean();
  $ele['art']['ide'] = !empty($_uri->cab) ? $_uri->cab : 'ini';

  // recursos del documento
  $this->jso []= "app/$_uri->esq";
  $this->css []= $_uri->esq;

  // cargo datos en articulos de dato
  global $_hol;
  $this->eje .= "
    
    // datos 
    var \$_hol = new _hol(".( $_uri->cab == 'tab' ? _obj::cod($_hol) : "").");

    // pagina
    var \$_app_hol = new _app_hol(".( $_uri->cab == 'tab' ? "{ val : "._obj::cod($_val)." }" : "" ).");

    \$_app_hol.ini();
  ";
  // devuelvo contenidos: [ win: modales, pan: paneles, htm: secciones ]