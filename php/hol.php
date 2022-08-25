<?php

  // holon del sincronario
  class _hol {

    // valor seleccionado
    public array $_val = [];
    // valores acumulados
    public array $_val_acu = [];
    

    public function __construct(){
    }

    // estructuras por posicion
    static function _( string $ide, mixed $val = NULL ) : string | array | object {
      $_ = [];
      global $_hol;
      $est = "_$ide";

      // aseguro carga
      if( !isset($_hol->$est) ) $_hol->$est = _dat::ini('hol',$ide);

      // busco dato
      if( !empty($val) ){
        
        $_ = $val;
        
        if( !is_object($val) ){
          switch( $ide ){
          case 'fec':
            $fec = _api::_('fec',$val);
            if( isset($fec->dia)  ) $_ = _dat::var( _hol::_('psi'), [ 'ver'=>[ ['fec_dia','==',$fec->dia], ['fec_mes','==',$fec->mes] ], 'opc'=>['uni'] ]);
            break;
            
          case 'kin':
            $_ = $_hol->$est[ _num::ran( _num::sum($val), 260 ) - 1 ];
            break;

          default:
            if( isset($_hol->$est[ $val = intval($val) - 1 ]) ) $_ = $_hol->$est[$val]; 
            break;
          }
        }
      }
      // devuelvo toda la lista
      else{
        $_ = $_hol->$est;
      }
      return $_;    
    }
    // consults sql
    static function sql( string $ide ) : string {
      $_ = "";
      switch( $ide ){
      case 'kin': 
        foreach( _hol::_('kin') as $kin => $_kin ){
          $sel = _hol::_('sel',$_kin->arm_tra_dia);
          $cel = _hol::_('sel_arm_cel',$sel->arm_cel);
          $ton = _hol::_('ton',$_kin->nav_ond_dia);      
          // poder del sello x poder del tono
          if( preg_match("/(o|a)$/i",$ton->nom) ){
            $pod = explode(' ',$sel->pod);
            $art = $pod[0];
            if( preg_match("/agua/i",$pod[1]) ){ 
              $art = 'la';
            }
            $pod = "{$sel->pod} ".( ( strtolower($art) == 'la' ) ? substr($ton->nom,0,-1).'a' : substr($ton->nom,0,-1).'o' );
          }else{
            $pod = "{$sel->pod} {$ton->nom}";
          }
          // encantamiento del kin
          $enc = "Yo ".($ton->pod_lec)." con el fin de ".ucfirst($sel->acc).", \n".($ton->acc_lec)." {$sel->car}. 
            \nSello {$cel->nom} "._tex::art_del($sel->pod)." con el tono {$ton->nom} "._tex::art_del($ton->pod).". ";
          $enc .= "\nMe guía ";
          if( $ton->pul_mat == 1 ){
            $enc .= "mi propio Poder duplicado. ";
          }else{
            $gui = _hol::_('sel', _hol::_('kin',$_kin->par_gui)->arm_tra_dia );
            $enc .= " el poder "._tex::art_del($gui->pod).".";
          }
          if( in_array($kin+1, $_kin_val['est']) ){
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
            $_arm = _hol::_('kin_cro_ond',_hol::_('ton',$_ele['ton'])->ond_arm);
            $enc .= "\nSoy un Kin Polar, {$_arm->enc} el Espectro Galáctico {$_est->col}. ";
          }
          if( in_array($kin+1, $_kin_val['pag']) ){
            $enc .= "\nSoy un Portal de Activación Galáctica, entra en mí.";
          }
          $_ .= "
          <p>
            UPDATE `_`.`hol_kin` SET 
              `pod` = '{$pod}', 
              `des` = '{$enc}'
            WHERE 
              `ide` = '{$_kin->ide}';
          </p>";
        }
        break;
      case 'kin_fac':
        $_lim = [ 20, 20, 19, 20, 20, 19, 20, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20, 20, 19, 20 ];
        $_add = [ '052','130','208' ];
        $fac_ini = -3113;
        foreach( _hol::_('kin') as $_kin ){    
    
          $fac_fin = $fac_ini + $_lim[intval($_kin->arm_tra_dia)-1];
    
          if( in_array($_kin->ide,$_add) ){
            $fac_fin ++;
          }
    
          $_ .= "
          UPDATE `_`.`hol_kin` SET `fac_ini` = $fac_ini, `fac_fin` = $fac_fin, `fac_ran` = '"._fec::ran($fac_ini,$fac_fin)."' WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $fac_ini = $fac_fin;
    
        }
        break;
      case 'kin_enc':
    
        $enc_ini = -26000;    
        foreach( _hol::_('kin') as $_kin ){    
    
          $enc_fin = $enc_ini + 100;
    
          $_ .= "
          UPDATE `_`.`hol_kin` SET `enc_ini` = $enc_ini, `enc_fin` = $enc_fin, `enc_ran` = '"._fec::ran($enc_ini,$enc_fin)."' WHERE `ide`='$_kin->ide'; 
          <br>";
    
          $enc_ini = $enc_fin;
    
        }
        break;
      case 'kin_cro_ele':
        foreach( _hol::_('kin_cro_ele') as $_ele ){
          $_cas = _hol::_('cas',$_ele->ide);
          $_est = _hol::_('kin_cro_est',$_cas->arm);
          $_ton = _hol::_('ton',$_ele->ton);        
          $_ .= "
          UPDATE _hol.kin_cro_ele SET
            des = '$_ton->des del Espectro Galáctico "._tex::let_ora($_est->col)."'
          WHERE ide = $_ele->ide;<br>";
        }
        break;
      }
      return $_;
    }
    // NS => d/m/a
    static function cod( mixed $val ) : bool | string {
      global $_hol;
      $_ = FALSE;

      if( is_string($val) ) $val = explode('.',$val);

      if( isset($val[3]) ){
        // mes y día
        $_psi = _dat::var( _hol::_('psi'), [ 'ver'=>[ ['lun','==',$val[2]], ['lun_dia','==',$val[3]] ], 'opc'=>['uni'] ]);
    
        if( isset($_psi->fec_mes) && isset($_psi->fec_dia) ){

          $_ = $_psi->fec_mes.'/'.$_psi->fec_dia;
          $año = 1987;
          $ini = [ 'sir'=>1, 'ani'=>0, 'año'=>1987, 'mes'=>7, 'dia'=>25 ];
          // ns.
          if( ( $sir = intval($val[0]) ) != $ini['sir'] ){          
            // compensar 0/- : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
            if( $sir < 0 ) $sir++;
            $año = $año + ( 52 * ( $sir - $ini['sir'] ) );
          }// ns.ani.        
          if( ( $ani = intval($val[1]) ) != $ini['ani'] ){          
            $año = $año + ( $ani - $ini['sir'] ) + 2;
          }

          $_ = $año.'/'.$_;
        }
      }
      return $_;
    }
    // d/m/a => NS
    static function dec( mixed $val ) : object | string {

      $_ = !is_object($val) ? _fec::dat($val) : $val ;

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

              $_->$atr = _num::ran($_->$atr+105, 260); 
            }

            if ($_->ani > 51){ 
              $_->ani = 0; 
              $_->sir++; 
            }
          }
        }
        elseif( $año > $_->año ){ 
          // sin considerar 0, directo a -1 : https://www.lawoftime.org/esp/IIG/esp-rinri/esp-rinriIII3.1.html
          $_->sir = 0;

          while( $_->año < $año ){ 

            $año--; 
            
            $_->ani--;

            foreach( ['fam_2','fam_3','fam_4'] as $atr ){ 
              
              $_->$atr = _num::ran($_->$atr-105, 260); 
            }

            if ($_->ani < 0){ 
              $_->ani = 51; 
              $_->sir--; 
            }
          } 
        }      
        if( $_->dia <= 25 && $_->mes <= 7){
          
          $_->ani--;
          
          foreach( ['fam_3','fam_4'] as $atr ){ 

            $_->$atr = _num::ran($_->$atr-105, 260); 
          }
        }
      }
      else{
        $_ = "{-_-} la Fecha {$val} no es Válida"; 
      }
      return $_;
    }
    // genero acumulados por valor principal
    static function acu( string $est, array $dat, int $ini = 1, int $inc = 1, string $ope = '+' ) : array {
      global $_hol;

      $_ = [];

      $cue = 0;

      // x 260 dias por kin 
      if( $est == 'kin' && isset($dat['kin']) && isset($dat['fec']) ){

        $cue = 260;

        $fec = _fec::ope( $dat['fec'], intval( is_object($dat['kin']) ? $dat['kin']->ide : $dat['kin'] ) - 1, '-');
      }
      // x 364+1 dias por psi-cronos
      elseif( $est == 'psi' && isset($dat['psi']) && isset($dat['fec']) ){

        $cue = 364;

        $fec = _fec::ope( $dat['fec'], intval( is_object($dat['psi']) ? $dat['psi']->ide : $dat['psi'] ) - 1, '-');
      }

      if( isset($fec) ){
    
        for( $pos = 0; $pos < $cue; $pos++ ){

          $_dat = _hol::ver($fec);

          $_ []= _api::dat([
            'api'=>[ 'fec'=>_api::_('fec',$fec) ],
            'hol'=>[ 'kin'=>_hol::_('kin',$_dat['kin']), 'psi'=>_hol::_('psi',$_dat['psi']) ]
          ]);

          $fec = _fec::ope($fec, $inc, $ope);
        }      

      }

      return $_;
    }
    // busco datos
    static function ver( mixed $val, string $tip='', array $ope=[] ) : array | object | string {    
      $_=[];
      // por tipo
      if( !empty($tip) ){
        // proceso fecha
        if( $tip == 'fec' ){
          $fec = $val;
          $_ = _hol::ver($fec);
          if( is_string($_) ){ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Calendario... {$_}</p>"; 
          }
        }
        // decodifico N.S.( cod.ani.lun.dia:kin )
        elseif( $tip == 'sin' ){
          // busco año
          $_fec = _hol::cod($val);
          if( !!$_fec ){
            $_ = _hol::ver($_fec);
            if( is_string($_) ){ 
              $_ = "<p class='err'>Error de Cálculo con la Fecha del ciclo NS... {$_}</p>"; 
            } 
          }
          else{ 
            $_ = "<p class='err'>Error de Cálculo con la Fecha del Sincronario...</p>";
          }
        }
      }
      // de una fecha
      elseif( $fec = _fec::dat($val) ){
        global $_hol;
        // giro solar => año
        $_['fec'] = $fec->val;
        $_fec = _hol::dec($fec);    
        // giro lunar => mes + día
        $_psi = _hol::_('fec',$_['fec']);
        if( !!$_psi ){
          $_['psi'] = $_psi->ide;
          $_['sin'] = "NS.{$_fec->sir}.{$_fec->ani}.{$_psi->lun}.{$_psi->lun_dia}";
          // giro galáctico => kin
          $_kin = _hol::_('kin',[ $_fec->fam_2, $_psi->fec_cod, $_fec->dia ]);
          if( is_object($_kin) ){
            $_['kin'] = $_kin->ide;
          }else{
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
    }
    // proceso datos
    static function val( $dat ) : array {
      return [
        '_kin' => isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : _hol::_('kin',$dat['kin']) ) : [],
        '_psi' => isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : _hol::_('psi',$dat['psi']) ) : [],
        '_sin' => isset($dat['sin']) ? explode('.',$dat['sin']) : [],
        '_fec' => isset($dat['fec']) ? $dat['fec'] : []
      ];
    }  
  }

  // pagina : paneles + pantallas + contenido
  class _hol_art {
    
    // seccion principal
    static function sec( object $_uri, object &$_doc, object $_dir, array $ele = [] ) : array {
      global 
        $_hol; 
      $esq = 'hol';    
      $_bib = SYS_NAV."hol/bib/";

      // cabecera inicial: ficha del día
      $_hol_dia = _hol::ver( date('Y/m/d') );

      $_hol_kin = _hol::_('kin',$_hol_dia['kin']);

      $_doc->cab_ini = _doc::ima($esq,'kin',$_hol_kin,[ 'style'=>"min-width: 2.5rem; height: 2.5rem;" ]);

      // inicializo datos
      $_hol->_val = $_hol_dia;

      // proceso fecha : informes + tableros
      if( $val_fec = !empty($_uri->cab) && in_array($_uri->cab,['inf','dat']) ){

        $dat_ide = empty($_uri->art) ? 'kin' : $_uri->art;

        $hol_val = !empty($_uri->val) ? $_uri->val : ( !empty($_SESSION['hol-val']) ? $_SESSION['hol-val'] : '' );
    
        // proceso valor por peticion
        if( !empty($_uri->val) ){
    
          $val = explode('=',$hol_val);
    
          if( isset($val[1]) ){
            $val[1] = isset($val[1]) ? $val[1] : '';
    
            if( $val[0]=='fec' || $val[0]=='sin' ){      
        
              $_hol->_val = _hol::ver($val[1],$val[0]);
            }
            else{
        
              $_hol->_val[$val[0]] = $val[1];
            }
          }
          else{
    
            $_hol->_val[$dat_ide] = _hol::ver( $dat_ide, $val[0] );;
          }
        }

        // actualizo valor de sesion
        $_SESSION['hol-val'] = $hol_val;
    
        // cargo operadores
        $est = explode('_',$dat_ide)[0];
        $ide = "{$esq}.{$est}";
    
        // fecha => muestro listado por ciclos  
        if( isset($_api_hohol_val['fec']) ){
          
          // acumulo posiciones
          _hol::acu($est,$_api_hohol_val);
        }
      }

      // genero paneles : bibliografía + tableros + informes
      $_ = [ 'htm'=>"", 
        // pantallas
        'win'=>[], 
        // paneles
        'pan'=>[
          'bib' => [ 'ico'=>"art_bib", 'nom'=>"Bibliografía", 'htm'=>
            _doc_nav::lis( _dat::var("_api.doc_art",[ 'ver'=>"`esq`='hol' AND `cab`='bib'", 'ord'=>"`pos` ASC" ]), 
              [ 'ico'=>"art_bib", 'nom'=>"Bibliografía", 'nav'=>[ 'ide'=>"bib", 'class'=>DIS_OCU ] ]
            )
          ],
          'dat' => [ 'ico'=>"art_tab", 'nom'=>"Tableros por Ciclos", 'htm' => 
            _doc_nav::lis( _dat::var("_api.doc_art",[ 'ver'=>"`esq`='hol' AND `cab`='dat'", 'ord'=>"`pos` ASC" ]), 
              [ 'ico'=>"art_tab", 'nom'=>"Tableros", 'nav'=>[ 'ide'=>"dat", 'class'=>DIS_OCU ] ]
            )
          ],
          'inf' => [ 'ico'=>"art_inf", 'nom'=>"Informes por Posición", 'htm' => 
            _doc_nav::lis( _dat::var("_api.doc_art",[ 'ver'=>"`esq`='hol' AND `cab`='inf'", 'ord'=>"`pos` ASC" ]), 
              [ 'ico'=>"art_inf", 'nom'=>"Informes", 'nav'=>[ 'ide'=>"inf", 'class'=>DIS_OCU ] ]
            )
          ],
          'val' => [ 'ico'=>"ope_val", 'nom'=>"Valores del Operador", 'htm'=>"

            <nav class='".( empty($_uri->val) ? DIS_OCU : '' )."' ide='val'>

              <div class='lis'>

                "._hol_val::ver('val', $_hol->_val)."
                
              </div>
                  
              "._hol_val::ver('dat', $_hol->_val, [ 'lis'=>['class'=>"anc-100"] ])."

            </nav>"
          ]
        ] 
      ];

      // imprimo secciones
      ob_start();
      // inicio
      if( empty($_uri->cab) ){
        ?>      
        <h1>Bienvenido al Sincronario</h1>

        <p>Hoy es <?= $_hol_kin->nom ?></p>

        <section>          

        </section>
        <?php
      }
      // por cabecera
      elseif( empty($_uri->art) ){

        if( $_uri->cab != 'val' ){
          ?> 

          <?= _doc::art('cab',[ $_doc->cab, $_uri->esq, $_uri->cab]) ?>
    
          <?php
        }
      }
      // datos: tablero principal 
      elseif( $_uri->cab == 'dat' ){
    
        $_art = isset($_doc->art->ope) ? $_doc->art->ope : [];      
        $tab_opc = isset($_art['tab']['opc']) ? $_art['tab']['opc'] : [];
        $tab_ele = isset($_art['tab']['ele']) ? $_art['tab']['ele'] : [];
        $tab_ope = isset($_art['tab']['ope']) ? $_art['tab']['ope'] : [];
    
        // fecha => muestro listado por ciclos    
        if( isset($_api_hohol_val['fec']) ){        
    
          if( in_array($est,['kin','psi']) ){

            // datos
            $tab_ope['dat'] = _api::_('dat');
            // estructuras
            $tab_ope['est'] = [ 'api'=>[ "fec" ], 'hol'=>[ "kin", "psi" ] ];
            // valores
            $tab_ope['val']['ver'] = $tab_ope['val']['mar'] = $tab_ope['val']['pos'] = 1;
          }
        }
                
        // modal: listado de posiciones
          $lis_opc = isset($_art['lis']['opc']) ? $_art['lis']['opc'] : [];
          $lis_ele = isset($_art['lis']['ele']) ? $_art['lis']['ele'] : [];
          $lis_ope = isset($_art['est']['ope']) ? $_art['est']['ope'] : [ 'tit'=>['cic','gru'], 'det'=>['des'] ];
          $lis_ope['val'] = isset($tab_ope['val']) ? $tab_ope['val'] : NULL;
          $lis_ope['dat'] = isset($tab_ope['dat']) ? $tab_ope['dat'] : NULL;
          $lis_ope['est'] = isset($tab_ope['est']) ? $tab_ope['est'] : NULL;      
      
        $_['win']['est'] = [
          'ico' => "est", 
          'nom' => "Listado de Posiciones",
          'art' => [ 'style'=>"max-width: 75rem;" ],
          'htm' => _doc_est::ope('', $ide, $lis_ope, $lis_ele, ...$lis_opc )
        ];

        // modal : operadores del tablero
        $_['win']['tab'] = [ 
          'ico' => "tab", 
          'nom' => "Tablero",        
          'htm' => _doc_tab::ope('', $ide, $tab_ope, $tab_ele, ...$tab_opc )
        ];      

        // imprimo tablero en página principal
        $ele['class'] = "pad-0 mar_ver-1";
        ?>

          <?= _hol_tab::ver($_uri->art, $_hol->_val, $tab_ope, [ 'pos'=>[ 'onclick'=>"_doc_tab.val('mar',this);" ] ]) ?>

        <?php
      }    
      // Articulos : bibliografía + informes 
      else{

        // panel : "índice de contenidos"
        $_['pan']['nav'] = [ 'ico' => "nav", 'nom' => "Índice de Contenidos",
          'htm' => _doc_nav::art( $nav = _dat::var("_api.doc_nav",[
            'ver'=>"esq = '{$_uri->esq}' AND cab = '{$_uri->cab}' AND ide = '{$_uri->art}'", 'ord'=>"pos ASC", 'nav'=>'pos'
          ]),[ 'nav'=>[ 'ide'=>'nav' ] ])
        ];

        switch( $_uri->cab ){
        // bibliografía
        case 'bib':
          switch( $_uri->art ){
          // ciclos y cuentas
          case 'dat':
            ?>           
            <h1>Códigos y Cuentas del Sincronario</h1>
            <!-- 7 : plasmas radiales -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['01']->pos}-", 'htm'=>_doc::let($nav[1]['01']->nom) ])?>
            <section>
    
              <p>En <a href="lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a> se divide el año en <n>13</n> lunas de <n>28</n> días cada una. A su vez, cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>
    
              <p>Los plasmas se utilizan para nombrar a los días de cada semana<c>-</c>heptada<c>.</c></p>
    
              <?=_doc_est::ope('lis','hol.rad',[ 'atr'=>['ide','nom','pod'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
              
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['01']['01']->pos}-", 'htm'=>_doc::let($nav[2]['01']['01']->nom) ])?>
              <section>
    
                <p>En el <a href="tel#_02-06-" target="_blank">telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>
    
                <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario <c>(</c> familia portal<c>:</c> abren los portales codificando el inicio de los anillos solares<c>;</c> y familia señal<c>:</c> descifran el misterio codificando los días fuera del tiempo<c>.</c> Ver <a href="enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>
    
                <?=_doc_est::ope('lis','hol.rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
                <p>En el <a href="rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>
    
                <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del banco-psi <c>(</c> el campo resonante de la tierra <c>)</c> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>
    
                <?=_doc_est::ope('lis','hol.rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>
    
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['01']['02']->pos}-", 'htm'=>_doc::let($nav[2]['01']['02']->nom) ])?>
              <section>
    
                <p>En el <a href="ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

                <?=_doc_est::ope('lis','hol.rad',[ 'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_ene','pla_fue_pre','pla_fue_pos'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>              
    
                <p>Desde este paradigma los plasmas son <q>componenees electrónicos</q> constituídos por la combinación de <n>12</n> líneas electrónicas de fuerza que convergen en <n>6</n> tipos de electricidad clasificadas según la cantidad de cargas positivas o negativas que contengan<c>.</c></p>
    
                <p>Los <n>6</n> tipos de electricidad son<c>:</c></p>
    
                <?=_doc_est::ope('lis','hol.rad_pla_ele',[ 'atr'=>['ide','cod','nom','des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
                <p>Los <n>12</n> tipos de líneas electrónicas<c>:</c></p>
    
                <?=_doc_est::ope('lis','hol.rad_pla_fue',[ 'atr'=>['ide','nom','ele_pre','ele_ope','ele_pos'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              </section>  
              
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['01']['03']->pos}-", 'htm'=>_doc::let($nav[2]['01']['03']->nom) ])?>
              <section>

                <p>En el <a href="tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

                <p>En el <a href="rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>

                <p>Por otro lado<c>,</c> en las <a href="ato#_03-06-" target="_blank" rel="">Autodeclaraciones Diarias de Padmasambhava</a> se describen las afirmaciones correspondientes a cada plasma<c>.</c></p>
      
                <?=_doc_est::ope('lis','hol.rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','hep','hep_pos','pla_des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>      

              </section>      
    
            </section>  
            <!-- 13 : tonos galácticos -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['02']->pos}-", 'htm'=>_doc::let($nav[1]['02']->nom) ])?>
            <section>
    
              <!-- rayos de pulsación -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['02']['01']->pos}-", 'htm'=>_doc::let($nav[2]['02']['01']->nom) ])?>
              <section>

                <p>En <a href="fac#_04-04-01-" target="_blank">el Factor Maya</a> se definen como rayos de pulsación<c>,</c> cada uno con una función radio<c>-</c>resonante en particular<c>.</c></p>

                <?=_doc_est::ope('lis','hol.ton',[ 'atr'=>['ide','nom','gal'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

              </section>

              <!-- simetría especular -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['02']['02']->pos}-", 'htm'=>_doc::let($nav[2]['02']['02']->nom) ])?>
              <section>

                <p>En el <a href="fac#_04-04-01-02-" target="_blank">Factor Maya</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.ton_sim',[ 'atr'=>['ide','nom','ton'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
                
              </section>
              
              <!-- principios de la creacion -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['02']['03']->pos}-", 'htm'=>_doc::let($nav[2]['02']['03']->nom) ])?>
              <section>

                <p>En <a href="enc#_03-11-" target="_blank">el Encantamiento del sueño</a> se definene como tonos galácticos de la creación<c>.</c></p>

                <?=_doc_est::ope('lis','hol.ton',[ 'atr'=>['ide','nom','des','acc'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

              </section>

              <!-- O.E. de la Aventura -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['02']['04']->pos}-", 'htm'=>_doc::let($nav[2]['02']['04']->nom) ])?>
              <section>

                <p>En el <a href="enc#_03-12-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
                
              </section>
              
              <!-- pulsar dimensional -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['02']['05']->pos}-", 'htm'=>_doc::let($nav[2]['02']['05']->nom) ])?>
              <section>

                <p>En el <a href="enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.ton_dim', [], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
                
              </section>

              <!-- pulsar matiz -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['02']['06']->pos}-", 'htm'=>_doc::let($nav[2]['02']['06']->nom) ])?>
              <section>

                <p>En el <a href="enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.ton_mat', [], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
                
              </section>
    
            </section>  
            <!-- 20 : sellos solares -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['03']->pos}-", 'htm'=>_doc::let($nav[1]['03']->nom) ])?>
            <section>
              <!-- signos direccionales -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['01']->pos}-", 'htm'=>_doc::let($nav[2]['03']['01']->nom) ])?>
              <section>

                <p>En <a href="fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.sel_cic_dir',[ ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                <!-- desarrollo del ser -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['01']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['01']['01']->nom) ])?>
                <section>

                  <p>En <a href="fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>
                <!-- etapas evolutivas de la mente -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['01']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['01']['02']->nom) ])?>
                <section>

                  <p>En <a href="fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_cic_men',[ 'atr'=>['sel','nom','des','lec'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>
                <!-- familias ciclicas -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['01']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['01']['03']->nom) ])?>
                <section>

                  <p>En <a href="fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

              </section>
              <!-- colocacion cromática -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['02']->pos}-", 'htm'=>_doc::let($nav[2]['03']['02']->nom) ])?>
              <section>
                
                <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
                
                <?=_doc_est::ope('lis','hol.sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                <!-- familias -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['02']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['02']['01']->nom) ])?>
                <section>

                  <p>En <a href="enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- clanes -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['02']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['02']['02']->nom) ])?>
                <section>

                  <p>En <a href="enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

              </section>
              <!-- colocación armónica -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['03']->pos}-", 'htm'=>_doc::let($nav[2]['03']['03']->nom) ])?>
              <section>

                <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>

                <?=_doc_est::ope('lis','hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                <!-- razas -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['03']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['03']['01']->nom) ])?>
                <section>

                  <p>En <a href="enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- células -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['03']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['03']['02']->nom) ])?>
                <section>

                  <p>En <a href="enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

              </section>            
              <!-- parejas del oráculo -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['04']->pos}-", 'htm'=>_doc::let($nav[2]['03']['04']->nom) ])?>
              <section>

                <p>En <a href="enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                <p>En <a href="tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>

                <!-- analogos -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['04']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['04']['01']->nom) ])?>
                <section>

                  <p>En <a href="enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_par_ana',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- antipodas -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['04']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['04']['02']->nom) ])?>
                <section>

                  <p>En <a href="enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_par_ant',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- ocultos -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['04']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['04']['03']->nom) ])?>
                <section>

                  <p>En <a href="enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_par_ocu',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

              </section>                  
              <!-- holon Solar -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['05']->pos}-", 'htm'=>_doc::let($nav[2]['03']['05']->nom) ])?>
              <section>

                <p>El código 0-19</p>              

                <?=_doc_est::ope('lis','hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                <!-- orbitas planetarias -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['05']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['05']['01']->nom) ])?>
                <section>

                  <p>En <a href="fac" target="_blank">el Factor Maya</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_sol_pla',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>
                <!-- células solares -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['05']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['05']['02']->nom) ])?>
                <section>

                  <p>En <a href="enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_sol_cel',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>
                <!-- circuitos de telepatía -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['05']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['05']['03']->nom) ])?>
                <section>

                  <p>En <a href="tel" target="_blank">Telektonon</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_sol_cir',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>              

              </section>
              <!-- holon planetario -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['06']->pos}-", 'htm'=>_doc::let($nav[2]['03']['06']->nom) ])?>
              <section>  
                
                <p>En <a href="enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                <!-- centros galácticos -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['06']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['06']['01']->nom) ])?>
                <section>

                  <?=_doc_est::ope('lis','hol.sel_pla_cen',[  ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- flujos de la fuerza-g -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['06']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['06']['02']->nom) ])?>
                <section>

                  <p>En <a href="enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                  <?=_doc_est::ope('lis','hol.sel_pla_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>              

              </section>
              <!-- holon humano -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['03']['07']->pos}-", 'htm'=>_doc::let($nav[2]['03']['07']->nom) ])?>
              <section>

                <p>En <a href="enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

                <?=_doc_est::ope('lis','hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                <!-- Centros Galácticos -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['01']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['01']->nom) ])?>
                <section>

                  <?=_doc_est::ope('lis','hol.sel_hum_cen',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- Extremidades -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['02']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['02']->nom) ])?>
                <section>

                  <?=_doc_est::ope('lis','hol.sel_hum_ext',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>           
                
                <!-- dedos -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['03']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['03']->nom) ])?>
                <section>            
                  
                  <?=_doc_est::ope('lis','hol.sel_hum_ded',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>

                <!-- lados -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['03']['07']['04']->pos}-", 'htm'=>_doc::let($nav[3]['03']['07']['04']->nom) ])?>
                <section>
                  
                  <?=_doc_est::ope('lis','hol.sel_hum_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

                </section>              

              </section>

            </section>
            <!-- 28 : días del giro solar -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['04']->pos}-", 'htm'=>_doc::let($nav[1]['04']->nom) ])?>
            <section>

              <p>En <a href="" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_doc_est::ope('lis','hol.lun',[ 'atr'=>['ide','arm','rad','ato_des'] ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
    
              <!-- 4 heptadas -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['04']['01']->pos}-", 'htm'=>_doc::let($nav[2]['04']['01']->nom) ])?>
              <section>

                <p>En <a href="lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>

                <p>En <a href="" target="_blank">el Telektonon</a><c>.</c></p>

                <p>En <a href="" target="_blank">el átomo del tiempo</a><c>.</c></p>
    
              </section>
    
            </section>
            <!-- 52 : posiciones del castillo-g -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['05']->pos}-", 'htm'=>_doc::let($nav[1]['05']->nom) ])?>
            <section>
    
              <!-- -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['05']['01']->pos}-", 'htm'=>_doc::let($nav[2]['05']['01']->nom) ])?>
              <section>
    
              </section>
    
            </section>
            <!-- 64 : hexagramas del i-ching -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['06']->pos}-", 'htm'=>_doc::let($nav[1]['06']->nom) ])?>
            <section>
    
              <!-- -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['06']['01']->pos}-", 'htm'=>_doc::let($nav[2]['06']['01']->nom) ])?>
              <section>
    
              </section>
    
            </section>  
            <!-- 260 : tzolkin -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['07']->pos}-", 'htm'=>_doc::let($nav[1]['07']->nom) ])?>
            <section>
    
              <!-- -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['07']['01']->pos}-", 'htm'=>_doc::let($nav[2]['07']['01']->nom) ])?>
              <section>

                <!-- -->
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_{$nav[3]['07']['01']['01']->pos}-", 'htm'=>_doc::let($nav[3]['07']['01']['01']->nom) ])?>
                <section>

                </section>            
    
              </section>
    
            </section>
            <!-- 364 : banco-psi -->
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_{$nav[1]['08']->pos}-", 'htm'=>_doc::let($nav[1]['08']->nom) ])?>    
            <section>
    
              <!-- -->
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_{$nav[2]['08']['01']->pos}-", 'htm'=>_doc::let($nav[2]['08']['01']->nom) ])?>    
              <section>
    
              </section>
    
            </section>    
            <?php      
            break;
          // glosario
          case 'ide': 
            break;
          // libros
          default:
            if( !empty( $rec = _arc::rec( $rec_val = "php/$_dir->art" ) ) ){
    
              include( $rec );
            }
            else{ ?>
              <p class='err'><c>{-_-}</c> No existe el archivo <c>'</c><b class='ide'><?=$rec_val?></b><>'</c></p>
              <?php          
            }
            break;
          }
          break;
        // informes
        case 'inf':
          $dat = $_hol->_val;
          if( isset($dat['kin']) ){
            $_kin = _hol::_('kin',$dat['kin']);
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);
            $_ton = _hol::_('ton',$_kin->nav_ond_dia);
          }
          if( isset($dat['psi']) ){
            $_psi = _hol::_('psi',$dat['psi']);
          }
          switch( $_uri->art ){
          // kin diario
          case 'gal': 
            ?>
            <h1>Informe de Ciclo Galáctico</h1>
    
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_01-", 'htm'=>"El Kin"])?>
            <section>
    
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_01-01-", 'htm'=>"Encantamiento del Kin"])?>
              <section>
    
                <?= _hol_fic::ver('kin',$_kin) ?>
    
                <br>
    
                <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>
    
                <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>
    
              </section>
              
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_01-02-", 'htm'=>"Parejas del Oráculo"])?>
              <section>
    
                <?= _hol_tab::ver('kin_par',$_kin->ide,[ 
                  'sec'=>[ 'par'=>0 ],
                  'pos'=>[ 'ima'=>'hol.kin.ide' ]
                ],[
                  'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
                  'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
                ]) ?>
    
                
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-02-01-", 'htm'=>"Descripciones"])?>
                <section>
    
                  <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            
    
                  <?= _hol_fic::ver('kin_par_des',$_kin) ?>
    
                </section>
    
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-02-02-", 'htm'=>"Lecturas"])?>
                <section>
    
                  <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c></p>
    
                  <p>Utiliza la siguiente tabla comparativa con las palabras clave según las propiedades de cada pareja del oráculo<c>:</c></p>
    
                  <?= _hol_lis::ver('kin_par_lec',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
                  <br>
                  
                  <p>En <a href="<?=$_bib?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>
    
                  <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>
    
                  <?= _hol_fic::ver('kin_par_lec',$_kin) ?>
    
                </section>
    
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-02-03-", 'htm'=>"Posiciones"])?>
                <section>
    
                  <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        
    
                  <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>
    
                  <?= _hol_lis::ver('kin_par_pos',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>
    
                  <ul class="lis">
                    <li>
                      En el módelo energético del Módulo Armónico<c>:</c>
                      <ul>
                        <li><c>-></c> los 5 Campos de Energía</li>
                        <li><c>-></c> los 14 subcampos</li>
                        <li><c>-></c> los 52 portales de activación galáctica</li>
                      </ul>
                    </li>
                    <li>
                      En el giro espectral<c>:</c>
                      <ul>
                        <li><c>-></c> las 4 estaciones galácticas</li>
                        <li><c>-></c> los 52 elementos cromáticos</li>
                      </ul>
                    </li>
                    <li>
                      En el giro galáctico<c>:</c>
                      <ul>
                        <li><c>-></c> las 13 trayectorias armónicas</li>
                        <li><c>-></c> las 65 células del tiempo</li>              
                      </ul>
                    </li>
                    <li>
                      En la Nave del Tiempo<c>:</c>
                      <ul>
                        <li><c>-></c> los 5 castillos direccionales</li>
                        <li><c>-></c> las 20 ondas encantadas de la aventura</li>
                      </ul>
                    </li>
                  </ul>
    
                </section>
    
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-02-04-", 'htm'=>"Sincronometría"])?>
                <section>
    
                  <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>
    
                  <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>
    
                  <br>
    
                  <?= _hol_lis::ver('kin_par_sel',$_kin,[ 'lis'=>['class'=>"anc-100"] ]) ?>      
    
                  <ul class="lis">
                    <li>
                      En el Holon Solar<c>-</c>Interplanetario
                      <ul>
                        <li><c>-></c> Las <n>10</n> órbitas planetarias</li>
                        <li><c>-></c> Las <n>5</n> células solares</li>
                        <li><c>-></c> Los <n>5</n> circuitos de telepatía</li>
                      </ul>
                    </li>
                    <li>
                      En el Holon Terrestre<c>-</c>Planetario
                      <ul>              
                        <li><c>-></c> Los <n>2</n> hemisferios</li>
                        <li><c>-></c> Los <n>2</n> meridianos</li>
                        <li><c>-></c> Las <n>5</n> familias</li>
                      </ul>
                    </li>
                    <li>
                      En el Holon Humano
                      <ul>              
                        <li><c>-></c> Las <n>4</n> extremidades</li>
                        <li><c>-></c> Los <n>5</n> centros galácticos</li>              
                        <li><c>-></c> Los <n>10</n> meridianos</li>
                      </ul>
                    </li>
                  </ul>        
    
                </section>
                
              </section>
    
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_01-03-", 'htm'=>"Nave del Tiempo"])?>
              <section>
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-03-01-", 'htm'=>"Castillo del Génesis"])?>
                <section>
    
                    <?php
                    $_cas = _hol::_('kin_nav_cas',$_kin->nav_cas);      
                    ?>
                    
                    <nav>
                      <p>Ver el <a href='<?=$_bib?>enc#_01-01-' target='_blank'>Génesis del Encantamiento del Sueño</a> en el encantamiento del sueño<c>...</c></p>
                    </nav>
    
                    <?= _hol_fic::ver('kin_nav_cas',$_cas) ?>
                    
                    <?= _hol_tab::ver('kin_nav_cas',[ 'ide'=>$_cas->ide, 'kin'=>$_kin ], [
                      'pos'=>[ 'ima'=>'hol.kin.ide' ]
                    ], [ 
                      'cas'=>['class'=>"mar-2 pad-3 ali_pro-cen"], 
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
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-03-02-", 'htm'=>"Onda Encantada"])?>
                <section>
                  <?php
                  $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);  
                  ?>
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-12-' target='_blank'>la Onda Encantada de la Aventura</a> en el Encantamiento del Sueño</p>
                  </nav>
    
                  <?= _hol_tab::ver('kin_nav_ond',$_ond, [
                    'sec'=>[ 'par'=>1 ],
                    'pos'=>[ 'ima'=>'hol.kin.ide' ]
                  ], [
                    'cas'=>[ 'class'=>"mar-2 pad-3 ali_pro-cen" ],
                    'pos'=>[ 'style'=>"width:6rem; height:6rem;" ]
                  ]) ?>
    
                </section>
    
              </section>
    
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_01-04-", 'htm'=>"Giro Galáctico"])?>
              <section>
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-04-01-", 'htm'=>"Trayectoria Armónica"])?>
                <section>
                  <?php
                  $_tra = _hol::_('kin_arm_tra',$_kin->arm_tra);
                  $_ton = _hol::_('ton',$_kin->nav_ond_dia);
                  ?>    
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>el Gran Ciclo</a> en el <cite>Factor Maya</cite><c>...</c></p>
                  </nav>
    
                  <?= _hol_fic::ver('kin_arm_tra',$_tra) ?>
    
                  <p><?= _doc::let($_tra->lec) ?></p>
    
                  <?= _hol_tab::ver('kin_arm_tra',$_tra, [
                    'sec'=>[ 'par'=>1 ],
                    'pos'=>[ 'ima'=>'hol.kin.ide' ]
                  ], [
                    'tra'=>[ 'class'=>"mar-2 pad-3 ali_pro-cen", 'style'=>"grid-gap: .5rem;" ],
                    'pos'=>[ 'style'=>"width:5rem; height:5rem;" ],
                    'pos-0'=>[ 'style'=>"width:4rem; height:4rem;" ]
                  ]) ?>
    
                  <p class='tit let-4'>Codificado por el tono <?= $_ton->nom ?></p>
    
                  <p><?= $_ton->des ?> del Giro Galáctico.</p>
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Células del Tiempo</a> en el Encantamiento del Sueño<c>...</c></p>
                  </nav>
    
                </section>
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-04-02-", 'htm'=>"Célula del Tiempo"])?>
                <section>
                  <?php
                  $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel);    
                  ?>    
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Razas Raíz Cósmicas</a> en el Encantamiento del Sueño</p>
                  </nav>
    
                </section>
    
              </section>
    
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_01-05-", 'htm'=>"Giro Espectral"])?>
              <section>
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-05-01-", 'htm'=>"Estación Galáctica"])?>
                <section>
                  <?php
                  $_est = _hol::_('kin_cro_est',$_kin->cro_est);
                  ?>
    
                  <nav>
                    <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
                  </nav>    
    
                </section>  
    
                <?=_doc::ope('tog',['eti'=>"h4", 'id'=>"_01-05-02-", 'htm'=>"Elemento Cromático"])?>    
                <section>
                  <?php
                  $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
                  ?>
                  
                  <nav>
                    <p>Ver <a href='<?=$_bib?>enc#_03-16-' target='_blank'>Colocación Cromática</a> en el Encantamiento del Sueño</p>
                  </nav>
    
                </section>        
    
              </section>
    
              <?=_doc::ope('tog',['eti'=>"h3", 'id'=>"_01-06-", 'htm'=>"Módulo Armónico"])?>
              <section>
              </section>
    
            </section>
    
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_02-", 'htm'=>"El Tono Galáctico"])?>  
            <section>  
                
              <?= _hol_fic::ver('ton',$_ton) ?>
    
              <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    
    
            </section>
    
            <?=_doc::ope('tog',['eti'=>"h2", 'id'=>"_03-", 'htm'=>"El Sello Solar"])?>  
            <section>
              
              <?= _hol_fic::ver('sel',$_sel) ?>
    
              <p><?= _doc::let($_sel->des_pro) ?></p>
    
              <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'></a> en </p>
    
            </section>
    
            <?php
            break;
          // psi-cronos
          case 'sol': 
            ?>
            <h1>Informe de Ciclo Solar</h1>
    
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

      // genero articulo por contenido
      $ele['ide'] = !empty($_uri->cab) ? $_uri->cab : 'ini';
      $_['htm'] = "
      <article"._htm::atr($ele).">

        ".ob_get_clean()."

      </article>";
      return $_;
    }
    static function bib(){

    }
    static function tab(){

    }
    static function inf(){

    }
  }

  // valores : kin[260] + psi[365] + fec(año/mes/dia) + sin(sir-ani-lun-dia)
  class _hol_val {

    // fechas del calendario y el sincronario
    static function ver( string $tip, array | string $dat = [], array $ope = [], ...$opc ) : string {
      global $_hol; $esq = 'hol'; $eje = "val"; $fun = "ver";
      $_ = "";     
      $_ide = "_{$esq}_{$eje}.{$fun}";
      $_eje = "_{$esq}_{$eje}.{$fun}";
      extract( _hol::val($dat) );
      switch( $tip ){
      // fechas : calendario + sincronario
      case 'val':
        $_ = "
        <!-- Fecha del Calendario -->
        <form class='val' ide='fec'>

          <div class='atr'>
            "._doc_fec::ope('dia', $_fec, [ 'name'=>"fec" ])."
            "._doc::ico('fec',[ 'eti'=>"button", 'title'=>'Buscar en el Calendario', 
              'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('$tip',this);"
            ])."
          </div>

        </form>

        <!-- Fecha del Sincronario -->
        <form class='val' ide='sin'>
          
          <div class='atr'>

            <label>N<c>.</c>S<c>.</c></label>

            "._doc_num::ope('int', $_sin[1], [ 'maxlength'=>2, 'name'=>'psi_gal', 'title'=>'Portales Galácticos, Ciclos NS de 52 años...'])."

            <c>.</c>
            "._doc_opc::ope('val', $_sin[2], [ 
                'dat'=>_hol::_('ani'),
              'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): 52 ciclos de 364+1 días..." ], 
              'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
            ])."
            <c>.</c>
            "._doc_opc::ope('val', $_sin[3], [ 
                'dat'=>_hol::_('psi_lun'),
              'eti'=>[ 'name'=>"psi_lun", 'title'=>"Giro Lunar (mes): 13 ciclos de 28 días..." ],
              'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
            ])."
            <c>.</c>
            "._doc_opc::ope('val', $_sin[4], [ 
                'dat'=>_hol::_('lun'),
                'eti'=>[ 'name'=>"psi_dia", 'title'=>"Día Lunar : 1 día de 28 que tiene la luna..." ], 
                'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
            ])."          
            <c class='sep'>:</c>
        
            <n name='kin'>{$_kin->ide}</n>

            "._doc::ima('hol','kin',$_kin,['class'=>"mar_hor-1", 'style'=>'min-width:3em; height:3em;'])."

          </div>  

          "._doc::ico('dat_ver',[ 'eti'=>"button", 'title'=>'Buscar en el Sincronario', 
            'type'=>"submit", 'onclick'=>"$_eje('$tip',this);" 
          ])."

        </form>";      
        break;
      // ciclos
      case 'dat':
        if( !isset($ope['lis']) ) $ope['lis'] = [];
        $_ope = [
          'kin' => [ 'nom'=>"Orden Sincrónico" ], 
          'psi' => [ 'nom'=>"Orden Cíclico" ]      
        ];
        $atr_tot = count($dat_atr = [ 'ima', 'nom', 'val' ]);
        $atr_ite = $atr_tot - 1;
        $atr_htm = function( array $dat_atr, array $ope=[] ) : string {
          $_ = "

          <thead>
            <tr>";
            foreach( $dat_atr as $ide ){ $_.="
              <th atr='$ide'></th>";
            }$_ .= "
            </tr>
          </thead>";

          return $_;
        };

        $htm = "";
        // ciclos del kin-galáctico
        if( !empty($_kin) ){
          $_sel = _hol::_('sel',$_kin->arm_tra_dia);
          $_ton = _hol::_('ton',$_kin->nav_ond_dia);
          $_kin_atr = _dat::atr('hol','kin');
          $_est = [
            'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
            'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
            'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
            'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
            'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
            'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ],
          ];       
          $ope['lis']['ide'] = 'kin'; $htm .= "
          <table"._htm::atr($ope['lis']).">
            <caption class='let-tit let-4'>Cuentas del Kin Galáctico</caption>
            
            {$atr_htm($dat_atr)}

            <tbody>";
              $htm .= "
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Giro Galáctico</p></td>
              </tr>";
              foreach( [ 'arm_tra'=>13, 'arm_tra_dia'=>20, 'arm_cel'=>65, 'arm_cel_dia'=>4 ] as $atr => $cue ){ 
                $est = isset($_est[$atr]['est']) ? $_est[$atr]['est'] : "kin_$atr"; $htm.="
                <tr>"; 
                  $_dat = _hol::_($est,$_kin->$atr); $htm.="
                  <td rowspan='2'>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
                </tr>
                <tr>            
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('des',"$esq.$est",$_dat) )."</td>
                </tr>
                <tr>
                  <td></td>
                  <td colspan='$atr_ite'>"._doc_num::ope('ran',$_kin->$atr,[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</td>
                </tr>";          
              }
              $htm.="
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Nave del Tiempo</p></td>
              </tr>";
              foreach( ['nav_cas'=>52,'nav_ond'=>13] as $atr => $cue ){ $htm.="
                <tr>"; $_dat = _hol::_($est="kin_$atr",$_kin->$atr); $htm.="
                  <td rowspan='2'>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
                </tr>
                <tr>          
                  <td>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></td>
                  <td>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</td>
                </tr>";          
              }
              $htm.="
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Giro Espectral</p></td>
              </tr>";
              foreach( ['cro_est'=>65,'cro_ele'=>5] as $atr => $cue ){ $htm.="
                <tr>"; $_dat = _hol::_($est="kin_$atr",$_kin->$atr); $htm.="
                  <td rowspan='2'>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
                </tr>
                <tr>          
                  <td>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></td>
                  <td>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</td>
                </tr>";          
              } 
              $htm.="           
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Holon Solar</p></td>
              </tr>";
              foreach( ['sol_pla','sol_cel','sol_cir'] as $atr ){ $htm.="
                <tr>"; $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); $htm.="
                  <td>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
                </tr>";          
              }
              $htm.="
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Holon Planetario</p></td>
              </tr>";
              foreach( ['pla_cen','pla_hem','pla_mer'] as $atr ){ $htm.="
                <tr>"; $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); $htm.="
                  <td>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
                </tr>";          
              }
              $htm.="
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Holon Humano</p></td>
              </tr>";
              foreach( ['hum_cen','hum_ext','hum_ded','hum_mer'] as $atr ){ $htm.="
                <tr>"; $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); $htm.="
                  <td>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                  <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
                </tr>";          
              }
              $htm .= "
            </tbody>
          </table>";
        }
        // ciclos del psi-solar
        _ele::cla($ope['lis'],DIS_OCU);
        if( !empty($_psi) ){
          $_lun = _hol::_('lun',$_psi->lun);
          $_rad = _hol::_('rad',$_psi->hep_dia);
          $ope['lis']['ide'] = 'psi';         
          $htm .= "
          <table"._htm::atr($ope['lis']).">
            <caption class='let-tit let-4'>Cuentas del Psi-cronos Solar</caption>
            
            {$atr_htm($dat_atr)}

            <tbody>";
              $htm .= "
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Estación Solar</p></td>
              </tr>
              <tr>"; $_est = _hol::_('psi_est',$_psi->est); $htm.="
                <td>"._doc::ima($esq,'psi_est',$_est,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'></td>
              </tr>
              <tr>"; $_hep = _hol::_('psi_hep',$_psi->hep); $htm.="
                <td>"._doc::ima($esq,'psi_hep',$_hep,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'></td>
              </tr>  
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Giro Lunar</p></td>
              </tr>
              <tr>"; $_lun = _hol::_('psi_lun',$_psi->lun); $htm.="
                <td>"._doc::ima($esq,'psi_lun',$_lun,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'></td>
              </tr>
              <tr>"; $_arm = _hol::_('lun_arm',_num::ran($_psi->hep,'4')); $htm.="
                <td>"._doc::ima($esq,'lun_arm',$_arm,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'></td>
              </tr>            
              <tr>
                <td colspan='$atr_tot'><p class='tit'>Héptada</p></td>
              </tr>
              <tr>"; $_rad = _hol::_('rad',$_psi->hep_dia); $htm.="
                <td>"._doc::ima($esq,'rad',$_rad,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'></td>
              </tr>";               
              $htm .= "
            </tbody>

          </table>";   
        }
        // pestañas
        $ope_lis = ['kin','psi'];
        $_ =       
          _doc_nav::val('bar', _obj::nom($_ope,'ver',$ope_lis), [])."
          
          <div class='pad_arr-1'>
            {$htm}      
          </div>
        ";
        
        break;
      }
      return $_;
    }

    // datos de ciclos por valores
    static function dat( string $tip, array | string $dat = [], array $ope = [], ...$opc ) : string {
      global $_hol; $esq = 'hol'; $eje = "val"; $fun = "dat";
      $_ = "";
      $_ide = "_{$esq}_{$eje}.{$fun}";
      $_eje = "_{$esq}_{$eje}.{$fun}";
      extract( _hol::val($dat) );
      if( !isset($ope['lis']) ) $ope['lis'] = [];
      $_ope = [
        'kin' => [ 'nom'=>"Orden Sincrónico" ], 
        'psi' => [ 'nom'=>"Orden Cíclico" ]      
      ];
      $atr_tot = count($dat_atr = [ 'ima', 'nom', 'val' ]);
      $atr_ite = $atr_tot - 1;
      $atr_htm = function( array $dat_atr, array $ope=[] ) : string {
        $_ = "

        <thead>
          <tr>";
          foreach( $dat_atr as $ide ){ $_.="
            <th atr='$ide'></th>";
          }$_ .= "
          </tr>
        </thead>";

        return $_;
      };

      $htm = "";
      // ciclos del kin-galáctico
      if( !empty($_kin) ){
        $_sel = _hol::_('sel',$_kin->arm_tra_dia);
        $_ton = _hol::_('ton',$_kin->nav_ond_dia);
        $_kin_atr = _dat::atr('hol','kin');
        $_est = [
          'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
          'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
          'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
          'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
          'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
          'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ],
        ];       
        $ope['lis']['ide'] = 'kin'; $htm .= "
        <table"._htm::atr($ope['lis']).">
          <caption class='let-tit let-4'>Cuentas del Kin Galáctico</caption>
          
          {$atr_htm($dat_atr)}

          <tbody>";
            $htm .= "
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Giro Galáctico</p></td>
            </tr>";
            foreach( [ 'arm_tra'=>13, 'arm_tra_dia'=>20, 'arm_cel'=>65, 'arm_cel_dia'=>4 ] as $atr => $cue ){ 
              $est = isset($_est[$atr]['est']) ? $_est[$atr]['est'] : "kin_$atr"; $htm.="
              <tr>"; 
                $_dat = _hol::_($est,$_kin->$atr); $htm.="
                <td rowspan='2'>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
              </tr>
              <tr>            
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('des',"$esq.$est",$_dat) )."</td>
              </tr>
              <tr>
                <td></td>
                <td colspan='$atr_ite'>"._doc_num::ope('ran',$_kin->$atr,[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</td>
              </tr>";          
            }
            $htm.="
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Nave del Tiempo</p></td>
            </tr>";
            foreach( ['nav_cas'=>52,'nav_ond'=>13] as $atr => $cue ){ $htm.="
              <tr>"; $_dat = _hol::_($est="kin_$atr",$_kin->$atr); $htm.="
                <td rowspan='2'>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
              </tr>
              <tr>          
                <td>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></td>
                <td>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</td>
              </tr>";          
            }
            $htm.="
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Giro Espectral</p></td>
            </tr>";
            foreach( ['cro_est'=>65,'cro_ele'=>5] as $atr => $cue ){ $htm.="
              <tr>"; $_dat = _hol::_($est="kin_$atr",$_kin->$atr); $htm.="
                <td rowspan='2'>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
              </tr>
              <tr>          
                <td>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></td>
                <td>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</td>
              </tr>";          
            } 
            $htm.="           
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Holon Solar</p></td>
            </tr>";
            foreach( ['sol_pla','sol_cel','sol_cir'] as $atr ){ $htm.="
              <tr>"; $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); $htm.="
                <td>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
              </tr>";          
            }
            $htm.="
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Holon Planetario</p></td>
            </tr>";
            foreach( ['pla_cen','pla_hem','pla_mer'] as $atr ){ $htm.="
              <tr>"; $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); $htm.="
                <td>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
              </tr>";          
            }
            $htm.="
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Holon Humano</p></td>
            </tr>";
            foreach( ['hum_cen','hum_ext','hum_ded','hum_mer'] as $atr ){ $htm.="
              <tr>"; $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); $htm.="
                <td>"._doc::ima($esq,$est,$_dat,['class'=>"tam-3 mar_der-1"])."</td>
                <td colspan='$atr_ite'>"._doc::let( _doc_dat::val('nom',"$esq.$est",$_dat) )."</td>
              </tr>";          
            }
            $htm .= "
          </tbody>
        </table>";
      }
      // ciclos del psi-solar
      _ele::cla($ope['lis'],DIS_OCU);
      if( !empty($_psi) ){
        $_lun = _hol::_('lun',$_psi->lun);
        $_rad = _hol::_('rad',$_psi->hep_dia);
        $ope['lis']['ide'] = 'psi';         
        $htm .= "
        <table"._htm::atr($ope['lis']).">
          <caption class='let-tit let-4'>Cuentas del Psi-cronos Solar</caption>
          
          {$atr_htm($dat_atr)}

          <tbody>";
            $htm .= "
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Estación Solar</p></td>
            </tr>
            <tr>"; $_est = _hol::_('psi_est',$_psi->est); $htm.="
              <td>"._doc::ima($esq,'psi_est',$_est,['class'=>"tam-3 mar_der-1"])."</td>
              <td colspan='$atr_ite'></td>
            </tr>
            <tr>"; $_hep = _hol::_('psi_hep',$_psi->hep); $htm.="
              <td>"._doc::ima($esq,'psi_hep',$_hep,['class'=>"tam-3 mar_der-1"])."</td>
              <td colspan='$atr_ite'></td>
            </tr>  
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Giro Lunar</p></td>
            </tr>
            <tr>"; $_lun = _hol::_('psi_lun',$_psi->lun); $htm.="
              <td>"._doc::ima($esq,'psi_lun',$_lun,['class'=>"tam-3 mar_der-1"])."</td>
              <td colspan='$atr_ite'></td>
            </tr>
            <tr>"; $_arm = _hol::_('lun_arm',_num::ran($_psi->hep,'4')); $htm.="
              <td>"._doc::ima($esq,'lun_arm',$_arm,['class'=>"tam-3 mar_der-1"])."</td>
              <td colspan='$atr_ite'></td>
            </tr>            
            <tr>
              <td colspan='$atr_tot'><p class='tit'>Héptada</p></td>
            </tr>
            <tr>"; $_rad = _hol::_('rad',$_psi->hep_dia); $htm.="
              <td>"._doc::ima($esq,'rad',$_rad,['class'=>"tam-3 mar_der-1"])."</td>
              <td colspan='$atr_ite'></td>
            </tr>";               
            $htm .= "
          </tbody>

        </table>";   
      }
      // pestañas
      $ope_lis = ['kin','psi'];

      return        

        _doc_nav::val('bar', _obj::nom($_ope,'ver',$ope_lis), [])."
        
        <div class='pad_arr-1'>
          {$htm}      
        </div>
      ";
    }
  }

  // fichas : nombre + descripcion + atributos + detalles
  class _hol_fic {
    
    static function ver( string $ide, int | string | object $val ) : string {
      global $_hol; $esq = 'hol';
      $_ = "";
      $_ide = explode('_',$ide);    
      $dat = _hol::_($_ide[0],$val);
      switch( $_ide[0] ){
      case 'rad': 
        if( empty($_ide[1]) ){

        }else{
          switch( $_ide[1] ){
          case '': break;          
          }
        }
        break;            
      case 'ton': 
        if( empty($_ide[1]) ){
          $_ = "

          <font class='des'>Tono <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->nom}</font>
          
          <div class='val jus-cen'>
    
            "._doc::ima('hol','ton',$dat,['class'=>'mar_der-2'])."

            "._doc_lis::atr($dat,[ 'est'=>'hol.ton', 'atr'=>['car','pod','acc'] ])."

          </div>";
        }else{
          switch( $_ide[1] ){
          case '': break;          
          }
        }
        break;
      case 'sel': 
        if( empty($_ide[1]) ){
          $_ = "

          <font class='des'>Sello <c>#</c><n>{$dat->ide}</n><c>:</c> {$dat->arm}</font>

          <div class='val jus-cen'>

            "._doc::ima('hol','sel',$dat,['class'=>'mar_der-2'])."

            "._doc_lis::atr($dat,['est'=>'hol.sel', 'atr'=>['car','acc','pod']])."

          </div>";
        }else{
          switch( $_ide[1] ){
          case '': break;          
          }
        }
        break;
      case 'kin': 
        if( empty($_ide[1]) ){
          $_ = "
          <p class='des'>Kin <c>#</c><n>{$dat->ide}</n><c>:</c> "._doc::let($dat->nom)."</p>

          <div class='val jus-cen'>
        
            "._doc::ima('hol','kin',$dat,['class'=>"mar_der-2"])."

            <q>"._doc::let("{$dat->des}")."</q>

          </div>";
        }else{
          switch( $_ide[1] ){
          case 'fac':
            $_kin = $dat;
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);
            $_pol = _hol::_('sel_res',$_sel->res_flu);
            $_pla = _hol::_('sel_sol_pla',$_sel->sol_pla);
            $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
            $_arq = _hol::_('sel',$_ond->sel);
            $ton = intval($_kin->nav_ond_dia);
            $_ = "
            <div class='val'>

              "._doc::ima('hol','kin',$_kin)."

              <p class='tit tex_ali-izq'>
                Katún <n>".intval($_sel->ide-1)."</n><c>:</c> Kin <n>$ton</n> <b class='ide'>$_sel->may</b>".( !empty($_kin->pag) ? "<c>(</c> Activación Galáctica <c>)</c>" : '' )."<c>.</c>
              </p>
            
            </div>
            <ul>
              <li>Regente Planetario<c>:</c> $_pla->nom $_pol->tip<c>.</c></li>
              <li>Etapa <n>$ton</n><c>,</c> Ciclo $_arq->may<c>.</c></li>
              <li>Índice Armónico <n>"._num::int($_kin->fac)."</n><c>:</c> período "._doc::let($_kin->fac_ran)."</li>
              <li><q>"._doc::let($_sel->arm_tra_des)."</q></li>
            </ul>";
            break;
          case 'par': 
            $dat = _hol::_($_ide[0],$val);          
            // listado de parejas
            if( empty($_ide[2]) ){
              break;
            }
            else{
              switch( $_ide[2] ){
              // ficha del kin por pareja
              case 'des':
                if( empty($_ide[3]) ){
                  $_ .="
                  <div class='lis'>";
                  foreach( _hol::_('sel_par') as $_par ){
      
                    $_ .= _hol_fic::ver($ide."_$_par->ide",$dat);
      
                  } $_ .= "
                  </div>";
                }else{
                  $par_ide = "par_{$_ide[3]}";
                  $atr_ide = ( $_ide[3]=='des' ) ? 'ide' : $par_ide;
      
                  // busco datos de parejas
                  $_par = _dat::var(_hol::_('sel_par'),[ 'ver'=>[ ['ide','==',$_ide[3]] ], 'opc'=>'uni' ]);
                  $kin = _hol::_('kin',$dat->$atr_ide);
                  $_ .= "
      
                  <p class='mar_arr-2 tex_ali-izq'>
                    <b class='ide let-sub'>{$_par->nom}</b><c>:</c>
                    <br><q>"._doc::let($_par->des)."</q>
                    ".( !empty($_par->lec) ? "<br><q>"._doc::let($_par->lec)."</q>" : "" )."
                  </p>
                  
                  "._hol_fic::ver('kin',$kin)
                  ;
                }
                break;
              // lecturas por relacion con momentos del día
              case 'lec': 
                $_des_sel = _hol::_('sel',$dat->arm_tra_dia);
                $_des_ton = _hol::_('ton',$dat->nav_ond_dia);

                foreach( _hol::_('sel_par') as $_par ){

                  if( $_par->ide == 'des' ) continue;

                  $_kin = _hol::_('kin',$dat->{"par_{$_par->ide}"});
                  $_par_sel = _hol::_('sel',$_kin->arm_tra_dia);
                  $_par_ton = _hol::_('ton',$_kin->nav_ond_dia);

                  $_lis []=                 
                    _doc::ima('hol','kin',$_kin)."

                    <div>
                      <p><b class='tit'>{$_kin->nom}</b> <c>(</c> "._doc::let($_par->dia)." <c>)</c></p>
                      <p>"._doc::let("{$_par_sel->acc} {$_par->pod} {$_par_sel->car}, que {$_par->mis} {$_des_sel->car}, {$_par->acc} {$_par_sel->pod}.")."</p>
                    </div>";
                }
                if( !isset($ope['lis']) ) $ope['lis']=[];
                _ele::cla($ope['lis'],'ite');
                $_ = _doc_lis::val($_lis,$ope);            
                break;
              }
            }
            break;
          case 'cro': 
            switch( $_ide[2] ){
            case 'est':
              $_ .= "
              <div class='val jus-cen'>
          
                "._doc::ima('hol','kin_cro_est',$dat,['class'=>"mar_der-2"])."
        
                <ul>        
                  <li><p><b class='ide'>Guardían</b><c>:</c> {$dat->may}</p></li>
                  <li><p><b class='ide'>Etapa Evolutiva</b><c>:</c> "._doc::let("{$dat->nom}, {$dat->des}")."</p></li>
                </ul>
        
              </div>                
              ";
              break;
            case 'ele': 
              $_ .= "
              
              ";
              break;          
            }
            break;          
          case 'arm': 
            switch( $_ide[2] ){
            case 'tra': 
              $_ .= "
              <div class='val jus-cen'>
                
                "._doc::ima('hol','kin_arm_tra',$dat,['class'=>"mar_der-2"])."

                <ul>          
                  <li><p><b class='ide'>Baktún</b><c>:</c> {$dat->may}</p></li>
                  <li><p><b class='ide'>Período</b><c>:</c> {$dat->ran}</p></li>
                </ul>

              </div>            
              ";
              break;
            case 'cel': 
              $_ .= "
              
              ";
              break;          
            }
            break;
          case 'nav': 
            switch( $_ide[2] ){
            case 'cas':             
              $_ = "
              <div class='val jus-cen'>
                  
                "._doc::ima('hol',$ide,$dat,['style'=>'margin: 0 2em;'])."

                <ul>
                  <li><b class='ide'>Corte</b><c>:</c> {$dat->cor}</li>
                  <li><b class='ide'>Poder</b><c>:</c> {$dat->pod}</li>
                  <li><b class='ide'>Acción</b><c>:</c> {$dat->acc}</li>
                </ul>

              </div>";
              break;
            case 'ond': 
              $_="";
              break;          
            }
            break;
          }
        }
        break;
      case 'psi': 
        if( empty($_ide[1]) ){

        }else{
          switch( $_ide[1] ){
          case '': break;          
          }
        }
        break;
      }
      return $_;
    }
  }

  // listados : tablas + items
  class _hol_lis {

    static function ver( string $est, mixed $dat=[], array $ele = [] ) : string {
      global $_hol; $esq = 'hol'; $pos = 0; $cla = ''; $tip = 'val';
      $_ = [];      
      $_ide = explode('_',$est);
      switch( $_ide[0] ){    
      // tablero
      case 'tel':
        if( empty($_ide[1]) ){

        }
        else{
          switch( $_ide[1] ){
          // libros: 2 cartas, anverso y reverso
          case 'lib':
            $_dat = [
              4 => _obj::atr(['ide'=> 4, 'tit'=>"Libro de la Forma Cósmica" ]),
              7 => _obj::atr(['ide'=> 7, 'tit'=>"Libro de las Siete Generaciones Perdidas" ]),
              13 => _obj::atr(['ide'=>13, 'tit'=>"Libro del Tiempo Galáctico" ]),
              28 => _obj::atr(['ide'=>28, 'tit'=>"Libro Telepático para la Redención de los Planetas Perdidos" ])
            ];
            $_ = [];
            $tip = "bar";
            for( $pos = 1; $pos <= intval($_ide[2]); $pos++ ){ $_ []= "
              <figure class='mar-0'>
                <div class='ite'>
                  <img src='".SYS_REC."hol/bib/tel/{$_ide[2]}/{$pos}-1.jpg' alt='Carta ' class='mar_der-1' style='width:24rem; height: 30rem;'>
                  <img src='".SYS_REC."hol/bib/tel/{$_ide[2]}/{$pos}-2.jpg' alt='Carta ' class='mar_izq-1' style='width:24rem; height: 30rem;'>              
                </div>
              </figure>";
            }          
            break;
          }
        }
        break;
      // cuentas
      case 'ani': 
        if( empty($_ide[1]) ){
        }else{
          switch( $_ide[1] ){
          case 'lun':
            $_[] = "
            <b class='ide'>Año Uno</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',39),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio  <n>1.992</n> <c>-</c> <n>25</n> Julio <n>1.993</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Dos</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',144),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.993</n> <c>-</c> <n>25</n> Julio <n>1.994</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Tres</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',249),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.994</n> <c>-</c> <n>25</n> Julio <n>1.995</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Cuatro</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',94),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.995</n> <c>-</c> <n>25</n> Julio <n>1.996</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Cinco</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',199),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.996</n> <c>-</c> <n>25</n> Julio <n>1.997</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Seis</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',44),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.997</n> <c>-</c> <n>25</n> Julio <n>1.998</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Siete</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',149),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.998</n> <c>-</c> <n>25</n> Julio <n>1.999</n><c>.</c></p>
            </div>";
            $_[] = "
            <b class='ide'>Año Ocho</b>
            <div class='ite'>
              "._doc::ima('hol','kin',$_kin = _hol::_('kin',254),['class'=>"mar_der-1"])."
              <p>$_kin->nom<c>:</c><br><n>26</n> de Julio <n>1.999</n> <c>-</c> <n>25</n> Julio <n>2.000</n><c>.</c></p>
            </div>";      
            break;
          }
        }
        break;
      // códigos      
      case 'rad': 
        if( empty($_ide[1]) ){

        }else{
          $cla = 'ite';
          switch( $_ide[1] ){
          case 'des':           
            foreach( _hol::_('rad') as $_rad ){ $_ []=
              _doc::ima('hol','rad',$_rad,['class'=>"mar_der-1"])."
              <p>
                <b class='ide'>{$_rad->nom}</b><c>:</c>
                <br>{$_rad->pla_ene} <c>(</c> {$_rad->pla_pod} <c>)</c>
              </p>";
            }
            break;
          case 'rin':
            $_lis_ite = ['class'=>"mar_aba-1"];
            foreach( _hol::_('rad') as $_rad ){ $_ []=
              _doc::ima('hol','rad',$_rad,['class'=>"mar_der-1"])."
              <p>
                <b class='ide'>{$_rad->nom}</b><c>:</c> $_rad->tel_des<c>,</c> <n>$_rad->tel_año</n> <c>-</c> <n>".($_rad->tel_año+1)."</n>
                <br><q>"._doc::let($_rad->rin_des)."<c>.</c></q>
              </p>";
            }
            break;          
          default:
            array_shift($_ide);
            $ide = implode('_',$_ide);
            foreach( _hol::_('rad') as $_rad ){ $_ []=
              _doc::ima('hol','rad',$_rad,['class'=>"mar_der-2"])."
              <p>
                <b class='ide'>{$_rad->nom}</b><c>:</c>
                <br>".( preg_match("/_des/",$ide) ? "<q>"._doc::let($_rad->$ide)."</q>" : _doc::let($_rad->$ide) )."
              </p>";
            }
            break;
          }
        }
        break;      
      case 'sel': 
        if( empty($_ide[1]) ){
        }else{
          switch( $_ide[1] ){
          case 'cic':
            if( empty($_ide[2]) ){
            }else{
              $cla = 'ite';
              switch( $_ide[2] ){
              case 'dir':
                $cla = 'ite';
                foreach( _hol::_('sel_cic_dir') as $_dir ){ $_ []= 
                  
                  _doc::ima('hol','sel_cic_dir',$_dir,['class'=>"mar_der-1 tam-11"])."
      
                  <div>
                    <p><b class='ide'>".explode(' ',$_dir->nom)[1]."</b><c>:</c></p>
                    <ul>
                      <li><p><c>-></c> "._doc::let($_dir->des)."</p></li>
                      <li><p><c>-></c> Color<c>:</c> <c class='let_col-4-{$_dir->ide}'>{$_dir->col}</c></p></li>
                    </ul>
                  </div>";
                }
                break;
              case 'ser':
                foreach( _hol::_('sel') as $_sel ){
                  if( $pos != $_sel->cic_ser ){
                    $pos = $_sel->cic_ser;
                    $_ser = _hol::_($est,$pos);
                    $_ []= "
                    <p class='tit'>
                      DESARROLLO".( _tex::let_may( _tex::art_del($_ser->nom) ) ).( !empty($_ser->det) ? " <c>-</c> Etapa {$_ser->det}" : '' )."
                    </p>";
                  }                
                  $_dir = _hol::_('sel_cic_dir',$_sel->arm_raz); $_ []= 

                  _doc::ima('hol','sel',$_sel,['class'=>"mar_der-1"])."

                  <p><n>{$_sel->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> ".explode(' ',$_dir->nom)[1]."<c>.</c>
                    <br>"._doc::let($_sel->cic_ser_des)."
                  </p>";
                }
                break;
              case 'luz':
                foreach( _hol::_('sel') as $_sel ){
                  if( $pos != $_sel->cic_luz ){
                    $pos = $_sel->cic_luz;
                    $_luz = _hol::_($est,$pos); $_ []= "
                    <p><b class='tit'>"._tex::let_may("Familia Cíclica "._tex::art_del($_luz->nom)."")."</b>
                      <br><b class='des'>{$_luz->des}</b><c>.</c>
                    </p>";
                  }                
                  $_dir = _hol::_('sel_cic_dir',$_sel->arm_raz);                 
                  
                  $_ []= 

                  _doc::ima('hol','sel',$_sel,['class'=>"mar_der-1"])."

                  <p>".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
                    <br>"._doc::let($_sel->cic_luz_des)."
                  </p>";                
                }              
                break;
              case 'men':
                foreach( _hol::_($est) as $_est ){
                  $_sel = _hol::_('sel',$_est->sel); 
                  $_dir = _hol::_('sel_cic_dir',$_est->ide); $_ []= 
                  
                  _doc::ima('hol','sel',$_sel,['class'=>"mar_der-1"])."

                  <p><n>".intval($_sel->ide)."</n><c>°</c> Signo<c>.</c> ".explode(' ',$_dir->nom)[1]."<c>:</c> <b class='ide'>{$_sel->may}</b><c>.</c>
                    <br><b class='val des'>{$_est->nom}</b><c>:</c> {$_est->des}<c>.</c>
                  </p>";
                }                  
                break;
              }
            }
            break;
          case 'arm': 
            if( empty($_ide[2]) ){
            }else{
              switch( $_ide[2] ){
              case 'raz':
                $sel = 1;
                foreach( _hol::_($est) as $_dat ){
                  $_raz_pod = _hol::_('sel',$_dat->ide)->pod; 
                  $htm = "
                  <p class='tit'>Familia <font class='let_col-4-{$_dat->ide}'>{$_dat->nom}</font><c>:</c> de la <b class='ide'>Raza Raíz "._tex::let_min($_dat->nom)."</b></p>
                  <p>Los {$_dat->pod}dores<c>.</c> Nota clave<c>:</c> ".explode(' ',$_raz_pod)[1]."</p>
                  <ul class='ite'>";
                  foreach( _hol::_('sel_arm_cel') as $pos ){
                    $_sel = _hol::_('sel',$sel); $htm .= "
                    <li>
                      "._doc::ima('hol','sel',$_sel,['class'=>"mar_der-2"])."
                      <p>
                        <n>{$pos->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                        <br><q>"._doc::let($_sel->arm_raz_des)."</q>
                      </p>
                    </li>";
                    $sel += 4;
                    if( $sel > 20 ) $sel -= 20;                  
                  }
                  $htm.="
                  </ul>
                  <q>"._doc::let(_tex::let_ora($_raz_pod)." ha sido "._tex::art_gen("realizado",$_raz_pod).".")."</q>";
                  $_ []= $htm;
                }
                break;
              case 'cel':
                $pos = 1;              
                foreach( _hol::_($est) as $_dat ){ $htm = "
                  <p class='tit'>Célula del Tiempo <n>{$_dat->ide}</n>: <b class='ide'>{$_dat->nom}</b></p>
                  <q>"._doc::let($_dat->des)."</q>
                  <ul class='ite'>";
                  foreach( _hol::_('sel_arm_raz') as $cro ){ $_sel = _hol::_('sel',$pos); $htm .= "
                    <li>
                      "._doc::ima('hol','sel',$_sel,['class'=>"mar_der-1"])."
                      <p>
                        <n>{$cro->ide}</n><c>.</c> Sello Solar <n>{$_sel->ide}</n><c>:</c>
                        <br><q>"._doc::let($_sel->arm_cel_des)."</q>
                      </p>
                    </li>";
                    $pos ++;
                  }$htm .= "
                  </ul>";
                  $_ []= $htm;
                }
                break;
              }
            }
            break;
          case 'cro': 
            if( empty($_ide[2]) ){
            }else{
              switch( $_ide[2] ){
              case 'ele':              
                $sel = 20;
                $val_dat = empty($dat);
                foreach( _hol::_($est) as $_dat ){
                  $ele = $_dat->ide;
                  if( $val_dat || in_array($ele,$dat) ){
                    $ele_nom = explode(' ',$_dat->nom)[1]; $htm = "
                    <p class='tit'><b class='ide'>Clan "._tex::art_del($_dat->nom)."</b>"._doc::let(": Cromática {$_dat->col}.")."</p>
                    ".( !empty($_dat->des_ini) ? "<p>"._doc::let($_dat->des_ini)."</p>" : '' )."
                    <ul class='ite'>";
                    for( $fam=1; $fam<=5; $fam++ ){ 
                      $_sel = _hol::_('sel',$sel); 
                      $_fam = _hol::_('sel_cro_fam',$fam); $htm .= "
                      <li sel='{$_sel->ide}' cro_fam='{$fam}'>
                        "._doc::ima('hol','sel',$_sel,[ 'class'=>"mar_der-1" ])."
                        <p>
                          <n>{$sel}</n><c>.</c> <b class='ide'>{$ele_nom} {$_fam->nom}</b><c>:</c>
                          <br><q>"._doc::let($_sel->cro_ele_des)."</q>
                        </p>
                      </li>";
                      $sel++;
                      if( $sel > 20 ) $sel -= 20;
                    }$htm .= "
                    </ul>";
                    $_ []= $htm;
                  }
                }
                break;
              case 'fam':
                foreach( _hol::_('sel_pla_cen') as $_pla ){
                  $_hum = _hol::_('sel_hum_cen',$_pla->ide);
                  $_fam = _hol::_($est,$_pla->fam);
                  $htm = 
                  _doc::ima('hol','sel_pla_cen',$_pla,['class'=>"mar_der-2",'style'=>"min-width: 9rem; height:7rem;"])."
                  <div>
                    <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> {$_fam->enc_fun}</p>
                    <div class='val fic mar-2'>
                      "._doc::ima('hol','sel_hum_cen',$_hum)."
                      <c class='sep'>=></c>
                      <c class='_lis ini'>{</c>";
                        foreach( explode(', ',$_fam->sel) as $sel ){
                          $htm .= _doc::ima('hol','sel',$sel,['class'=>"mar_hor-2"]);
                        }$htm .= "
                      <c class='_lis fin'>}</c>
                    </div>
                  </div>
                  ";
                  $_ []= $htm;
                }
                $_ = _doc_lis::val($_,[ 'lis'=>['est'=>$est, 'class'=>"ite anc_max-fit mar-aut mar_ver-3"] ]);
                break;
              }
            }
            break;
          case 'sol':
            if( empty($_ide[2]) ){
            }else{
              switch( $_ide[2] ){
              case 'cel': 
                $orb = 0;
                $pla = 10;
                $sel = 20;
                $val_cel = empty($dat);
                foreach( _hol::_($est) as $_dat ){
                  if( $val_cel || in_array($_dat->ide,$dat) ){ 
                    $htm = "
                    <p class='tit'><b class='ide'>{$_dat->nom}</b><c>:</c> Célula Solar "._num::dat($_dat->ide,'nom')."<c>.</c></p>                  
                    <ul est='sol_pla'>";
                    for( $sol_pla=1; $sol_pla<=2; $sol_pla++ ){
                      $_pla = _hol::_('sel_sol_pla',$pla);
                      $_sel = _hol::_('sel',$sel);
                      $_par = _hol::_('sel',$_sel->par_ana); 
                      if( $orb != $_pla->orb ){
                        $_orb = _hol::_('sel_sol_orb',$orb = $_pla->orb); $htm .= "
                        <li>Los <n>5</n> <b class='ide'>planetas {$_orb->nom}es</b><c>:</c> "._doc::let($_orb->des)."</li>";                        
                      }
                      $htm .= "
                      <li>
                        <p><b class='ide'>{$_pla->nom}</b><c>,</c> <n>{$pla}</n><c>°</c> órbita<c>:</c></p>
                        <div class='ite'>

                          "._doc::ima('hol','sel_sol_pla',$_pla,['class'=>"mar_der-1"])."

                          <ul class='ite' est='sel'>
                            <li>
                              "._doc::ima('hol','sel',$_sel,['class'=>"mar_der-1"])."
                              <p>
                                <b class='val'>Dentro</b><c>:</c> Sello Solar <n>{$_sel->ide}</n>
                                <br><q>"._doc::let($_sel->sol_pla_des)."</q>
                              </p>
                            </li>
                            <li>
                              "._doc::ima('hol','sel',$_par,['class'=>"mar_der-1"])."
                              <p>
                                <b class='val'>Fuera</b><c>:</c> Sello Solar <n>{$_par->ide}</n>
                                <br><q>"._doc::let($_par->sol_pla_des)."</q>
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
              case 'cir':
                $cla .= "ite";
                foreach( _hol::_($est) as $_cir ){
                  $pla = explode('-',$_cir->pla);
                  $_pla_ini = _hol::_('sel_sol_pla',$pla[0]);
                  $_pla_fin = _hol::_('sel_sol_pla',$pla[1]);
                  $htm = 
                  _doc::ima('hol',$est,$_cir,['class'=>""])."
                  <div>
                    <p class='tit'>Circuito <n>$_cir->ide</n><c>:</c> <b class='ide'>$_pla_ini->nom <c>-</c> $_pla_fin->nom</b></p>
                    <ul>
                      <li>Circuito "._doc::let($_cir->nom)."</li>
                      <li><p>"._doc::let("$_cir->cod unidades - $_cir->des")."</p></li>
                      <li><p>Notación Galáctica<c>,</c> números de código "._doc::let("{$_cir->sel}: ");
                      $pos = 0;
                      foreach( explode(', ',$_cir->sel) as $sel ){ 
                        $pos++; 
                        $_sel = _hol::_('sel', $sel == 00 ? 20 : $sel);                      
                        $htm .= _doc::let( $_sel->pod_tel.( $pos == 3 ? " y " : ( $pos == 4 ? "." : ", " ) ) );
                      } $htm .= "
                      </p></li>
                    </ul>
                  </div>
                  ";
                  $_ []= $htm;
                }
                break;              
              }
            }
            break;
          case 'pla':
            if( empty($_ide[2]) ){
            }else{
              $cla = 'ite';
              switch( $_ide[2] ){
              case 'cen':
                $_fam_sel = [
                  1=>[  5, 10, 15, 20 ],
                  2=>[  1,  6, 11, 16 ],
                  3=>[ 17,  2,  7, 12 ],
                  4=>[ 13, 18,  3,  8 ],
                  5=>[  9, 14, 19,  4 ]
                ]; 
                $pos = 1;
                foreach( _hol::_('sel_pla_cen') as $_dat ){
                  $_fam = _hol::_('sel_cro_fam',$_dat->fam); $htm= "
                  <div class='val'>
                    "._doc::ima('hol','sel_cro_fam',$_fam,['class'=>"mar_der-1"])."
                    <c class='sep'>=></c>
                    <c class='_lis ini'>{</c>";
                    foreach( $_fam_sel[$_dat->ide] as $sel ){
                      $htm .= _doc::ima('hol','sel',$sel,['class'=>"mar_hor-1"]);
                    }$htm.="
                    <c class='_lis fin'>}</c>
                    <c class='sep'>:</c>
                  </div>
                  <p>
                    <n>{$_dat->ide}</n><c>.</c> El Kin <b class='ide'>{$_fam->nom}</b><c>:</c>
                    <br><q>{$_fam->pla} desde el {$_dat->nom}</q>
                  </p>";
                  $_ []= $htm;
                }
                break;
              case 'pos':
                $_fam_sel = [
                  1=>[ 20,  5, 10, 15 ],
                  2=>[  1,  6, 11, 16 ],
                  3=>[ 17,  2,  7, 12 ],
                  4=>[ 18,  3,  8, 13 ],
                  5=>[ 14, 19,  4,  9 ]
                ];
                foreach( _hol::_('sel_pla_cen') as $_dat ){
                  $_fam = _hol::_('sel_cro_fam',$_dat->fam);
                  $htm = "
                  <div class='tex_ali-cen'>
                    <p>Kin <b class='ide'>{$_fam->nom}</b></p>
                    "._doc::ima('hol','sel_pla_cen',$_dat,['class'=>"mar_der-1",'style'=>"min-width: 17rem; height: 11rem;"])."
                  </div>
                  <ul class='ite'>";
                    foreach( $_fam_sel[$_dat->ide] as $sel ){
                      $_sel = _hol::_('sel',$sel);
                      $_pla_mer = _hol::_('sel_pla_mer',$_sel->pla_mer);
                      $_pla_hem = _hol::_('sel_pla_hem',$_sel->pla_hem);
                      $htm .= "
                      <li>
                        "._doc::ima('hol','sel',$_sel,['class'=>"mar_der-1"])."
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
              }
            }
            break;
          case 'hum':
            if( empty($_ide[2]) ){
            }else{    
              $cla = 'ite';        
              switch( $_ide[2] ){
              case 'ele':
                $ele = []; $pos = 0; $col = 4;
                foreach( _hol::_('sel_hum_ext') as $_ext ){
                  $_ele = _hol::_('sel_cro_ele',$_ext->ele); 
                  $nom = explode(' ',_tex::art_del($_ele->nom)); $cla = array_pop($nom); $nom = implode(' ',$nom);
                  $ele[$pos] = [ 
                    'eti'=>"div", 'class'=>"ite", 'htm'=> _doc::ima('hol','sel_hum_ext',$_ext,['class'=>"mar_der-1"])."                  
                    <p class='tit tex_ali-izq'><b class='ide'>$_ext->nom</b><c>:</c>
                      <br>Clan {$nom} <c class='let_col-4-$col'>{$cla} $_ele->col</c></p>" 
                  ];
                  $pos += 5; 
                  $col = _num::ran($col+1,4);
                }
                $sel = [];
                foreach( _hol::_('sel_cod') as $_sel ){
                  $_fam = _hol::_('sel_cro_fam',$_sel->cro_fam);
                  $_hum_ded = _hol::_('sel_hum_ded',$_fam->hum_ded);
                  $sel []= _obj::atr([ 
                    'hum_ded'=>$_hum_ded->nom, 
                    'nom'=>"Tribu "._tex::art_del($_sel->nom)." $_sel->nom_col", 
                    'ima_nom'=>[ 'htm'=>_doc::ima('hol','sel',$_sel,['class'=>"mar-1"]) ],
                    'nom_cod'=>$_sel->nom_cod,
                    'ima_cod'=>[ 'htm'=>_doc::ima('hol','sel_cod',$_sel,['class'=>"mar-1"]) ]
                  ]);
                }
                $_ = _doc_est::ope('lis',$sel,[ 'tit'=>$ele ],[ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ],'cab_ocu');
                break;
              case 'fam': 
                $pos = 0; 
                $fam = [];
                $sel = [];
                foreach( _hol::_('sel_hum_ded') as $_ded ){
                  $_fam = _hol::_('sel_cro_fam',$_ded->fam);
                  $fam[$pos] = [
                    'eti'=>"div", 'class'=>"ite", 'htm'=> _doc::ima('hol','sel_hum_ded',$_ded,['class'=>"mar_der-1"])."                  
                    <p class='tit tex_ali-izq'><b class='ide'>Familia Terrestre $_fam->nom</b><c>:</c>
                      <br>Familia de $_fam->cod<c>:</c> Dedos {$_ded->nom}".( in_array($_ded->nom,['Anular','Pulgar']) ? "es" : "s" )." </p>" 
                  ];
                  $pos += 4;
                  foreach( explode(', ',$_fam->sel) as $_sel ){
                    $_sel = _hol::_('sel',$_sel);
                    $_hum_ext = _hol::_('sel_hum_ext',$_sel->hum_ext);
                    $sel []= _obj::atr([
                      'nom'=>"Tribu "._tex::art_del($_sel->nom)." $_sel->nom_col", 
                      'ima_nom'=>[ 'htm'=>_doc::ima('hol','sel',$_sel,['class'=>"mar-1"]) ],
                      'nom_cod'=>$_sel->nom_cod,
                      'ima_cod'=>[ 'htm'=>_doc::ima('hol','sel_cod',$_sel,['class'=>"mar-1"]) ],
                      'hum_ext'=>$_hum_ext->nom
                    ]);
                  }
                }
                $_ = _doc_est::ope('lis',$sel,[ 'tit'=>$fam ],[ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ],'cab_ocu');              
                break;              
              case 'ext':
                foreach( _hol::_('sel_hum_ext') as $_dat ){
                  $_ele = _hol::_('sel_cro_ele',$_dat->ele); $_ []= "

                    "._doc::ima('hol','sel_hum_ext',$_dat,['class'=>"mar_der-1"])."

                    <p><b class='ide'>Cromática "._tex::art_del($_ele->nom)."</b><c>:</c>
                      <br>{$_dat->nom}
                    </p>";
                }
                break;
              case 'ded':
                foreach( _hol::_('sel_hum_ded') as $_dat ){
                  $_fam = _hol::_('sel_cro_fam',$_dat->fam); $_ []= "

                    "._doc::ima('hol','sel_hum_ded',$_dat,['class'=>"mar_der-1"])."

                    <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
                      <br>{$_dat->nom}
                    </p>";
                }              
                break;              
              case 'cen':
                foreach( _hol::_('sel_hum_cen') as $_dat ){
                  $_fam = _hol::_('sel_cro_fam',$_dat->fam); $_ []= "

                  "._doc::ima('hol','sel_hum_cen',$_dat,['class'=>"mar_der-1"])."

                  <p><b class='ide'>Kin {$_fam->nom}</b><c>:</c> <b class='val'>{$_fam->cod}</b>
                    <br>"._tex::art($_dat->nom)." <c>-></c> {$_fam->hum}
                  </p>";
                }              
                break;
              }
            }
            break;
          }
        }
        break;
      case 'ton':
        if( empty($_ide[1]) ){
        }else{
          switch( $_ide[1] ){
          case 'gal':
            $cla = 'ite';
            foreach( _hol::_('ton') as $_ton ){ $_ []= "
              "._doc::ima('hol','ton',$_ton,['class'=>"mar_der-1"])."
              <p>
                <n>".intval($_ton->ide)."</n><c>.</c> El Rayo de Pulsación ".preg_replace("/^(del|de la)/","$1<b class='ide'>",_tex::art_del($_ton->gal))."</b>
              </p>";
            }          
            break;                    
          case 'ond':
            $_atr = array_merge([ 
              'ima'=>_obj::atr(['ide'=>'ima','nom'=>''])
              ], _dat::atr('hol','ton', [ 'ide','ond_pos','ond_pod','ond_man' ])
            );
            // cargo valores
            foreach( ( $_dat = _obj::atr(_hol::_('ton')) ) as $_ton ){
              $_ton->ima = [ 'htm'=>_doc::ima('hol',$_ide[0],$_ton) ];
              $_ton->ide = "Tono {$_ton->ide}";
            }
            // cargo titulos
            $ond = 0;
            $_tit = [];
            foreach( $_dat as $pos => $_ton ){
              if( $_ton->ond_enc != 0 && $ond != $_ton->ond_enc ){              
                $_ond = _hol::_('ton_ond',$ond = $_ton->ond_enc);
                $_tit[$pos] = $_ond->des;
              }
            }
            $_ = _doc_est::ope('lis',$_dat,[ 'atr_dat'=>$_atr, 'tit'=>$_tit ],$dat,'cab_ocu');          
            break;
          case 'dim':
            foreach( _hol::_($est) as $_dat ){ $htm = "
              <p>
                <n>{$_dat->ide}</n><c>.</c> <b class='ide'>Pulsar de la {$_dat->pos} dimensión</b><c>:</c> <b class='val'>Dimensión {$_dat->nom}</b>
                <br>Tonos "._doc::let("{$_dat->ton}: {$_dat->ond}")."
              </p>
              <div class='fic ite'>
                "._doc::ima('hol',$est,$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
                <c class='sep'>=></c>
                <c class='_lis ini'>{</c>";
                  foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= _doc::ima('hol','ton',$ton,['class'=>"mar_hor-2"]); } $htm .= "
                <c class='_lis fin'>}</c>
              </div>
              ";
              $_ []= $htm;
            }
            break;
          case 'mat':
            foreach( _hol::_($est) as $_dat ){ $htm = "
              <p><n>{$_dat->ide}</n><c>.</c> <b class='ide'>Matiz {$_dat->nom}</b><c>,</c> <b class='val'>"._doc::let($_dat->cod)."</b><c>:</c>
                <br>Tonos "._doc::let("{$_dat->ton}: {$_dat->ond}")."
              </p>
              <div class='fic ite'>
                "._doc::ima('hol',$est,$_dat,['class'=>"mar_der-1",'style'=>"min-width: 5rem; height: 5rem"])."
                <c class='sep'>=></c>
                <c class='_lis ini'>{</c>";
                  foreach( explode(', ',$_dat->ton) as $ton ){ $htm .= _doc::ima('hol','ton',$ton,['class'=>"mar_hor-2"]); } $htm .= "              
                <c class='_lis fin'>}</c>
              </div>";
              $_ []= $htm;
            }
            break;
          case 'sim':
            foreach( _hol::_('ton_sim') as $_sim ){ $_ []= "
              <p>"._doc::let($_sim->des)."</p>";
            }
            break;
          }
        }
        break;
      case 'lun':
        if( empty($_ide[1]) ){
          // 13 lunas con totems
          $_ = _doc_est::ope('lis','hol.psi_lun',[ 'atr'=>[ 'ide','nom','ton','tot','ton_des' ] ],[ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ], 'cab_ocu');
        }
        else{
          switch( $_ide[1] ){
          // 4 semanas                 
          case 'arm':
            $_ = _doc_est::ope('lis','hol.lun_arm',[ 'atr'=>[ 'ide','nom','col','dia','pod' ] ],[ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ], 'cab_ocu');
            break;
          // poderes semanales
          case 'arm-pod': 
            foreach( _hol::_('lun_arm') as $_hep ){            
              $_ []= _doc::let("$_hep->nom: ")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let(", $_hep->pod $_hep->car");
            }
            break;
          case 'arm-des': 
            foreach( _hol::_('lun_arm') as $_hep ){            
              $_ []= _doc::let("$_hep->nom (")."<c class='let_col-4-$_hep->ide'>$_hep->col</c>"._doc::let("): $_hep->des");
            }
            break;
          // lineas de fuerza vertical en el telektonon
          case 'fue': 
            foreach( _hol::_($est) as $_lin ){
              $_ []= _doc::let("{$_lin->nom}: {$_lin->des}");
            }
            break;
          // laberinto del guerrero por el cubo de la ley
          case 'cub':           
            foreach( _hol::_('lun_cub') as $_cub ){
              $_ []= 
              "<div class='ite'>
                "._doc::ima('hol','sel',$_cub->sel,['class'=>"mar_der-1"])."              
                <div>
                  <p class='tit'>Día <n>$_cub->lun</n><c>,</c> CUBO <n>$_cub->ide</n><c>:</c> $_cub->nom</p>
                  <p class='des'>$_cub->des</p>
                </div>              
              </div>
              <p class='let-enf tex_ali-cen'>"._doc::let($_cub->tit)."</p>
              ".( !empty($_cub->lec) ? "<p class='let-cur tex_ali-cen'>"._doc::let($_cub->lec)."</p>" : ""  )."
              <q>"._doc::let($_cub->afi)."</q>";
            }
            break;
          }
        }
        break;
      case 'kin':
        if( empty($_ide[1]) ){

          $_ = "
          <!-- libro del kin -->
          <form class='inf' esq='hol' est='kin'>

            <div class = 'val'>

              <fieldset class='val'>

                "._doc::ope('tog_ver')."

                "._doc::var('atr',['hol','kin','ide'],[ 'nom'=>"ver kin", 'ope'=>[ 'onchange'=>"_hol_lis.val('kin',this);" ] ])."
              </fieldset>

              <fieldset class='ope'>

                "._doc::ico('nav_fin',[ 'eti'=>"button", 'title'=>"Ir al Kin...", 'onclick'=>"_hol_lis.val('kin',this,'nav');" ])."
              
              </fieldset>

            </div>

            <output class='hol-kin'></output>
            
          </form>
                  
          <nav dat='_hol.kin'>";
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

                "._doc::ope('tog',['eti'=>'h3','id'=>"_04-0{$_cas->ide}-",'cas'=>$_cas->ide,'htm'=>"Castillo {$_cas->nom}"])."

                <section kin_nav_cas='{$_cas->ide}' class='pad_izq-3'>

                  <p cas='{$_cas->ide}'>"._doc::let("Corte {$_cas->cor}: {$_cas->fun}")."</p>

                ";
              }
              // génesis
              if( $_kin->gen_enc != $gen_enc ){
                $gen_enc = $_kin->gen_enc;
                $_gen = _hol::_('kin_gen_enc',$_kin->gen_enc); $_ .= "

                <p class='tit' gen='{$_gen->ide}'>
                  GÉNESIS "._tex::let_may($_gen->nom)."
                </p>";

              }
              // onda encantada
              if( $_kin->nav_ond != $nav_ond ){
                $nav_ond = $_kin->nav_ond;
                $_ond = _hol::_('kin_nav_ond',$_kin->nav_ond);
                $_sel = _hol::_('sel',$_ond->sel); 
                $ond = _num::ran($_ond->ide,4);
                if( $nav_ond != 1 && $ond != 1 ){ $_ .= "
                  </section>
                  ";
                }$_ .= "

                "._doc::ope('tog',['eti'=>'h4','id'=>"_04-0{$_cas->ide}-0{$ond}-",'ond'=>$_ond->ide,'htm'=>_doc::let("Onda Encantada {$_ond->ide} {$_ond->nom}")])."

                <section kin_nav_ond='{$_ond->ide}'>

                  <p class='let-enf' ond='{$_ond->ide}'>Poder "._tex::art_del($_sel->pod)."</p>";
              }
              // célula armónica : titulo + lectura
              if( $_kin->arm_cel != $arm_cel ){
                $arm_cel = $_kin->arm_cel;
                $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
                </section>

                "._doc::ope('tog',['eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'cel'=>$_cel->ide,'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>"._doc::let(_tex::let_may($_cel->des))])."

                <section kin_arm_cel='{$_cel->ide}'>
                ";
              }
              // kin : ficha + nombre + encantamiento
              $_ .= "
              <div kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
                <div class='hol-kin'>
                  "._doc::ima('hol','kin',$_kin->ide,['class'=>'mar-aut'])."
                  <p>
                    <b>KIN</b> <n>{$_kin->ide}</n><c>:</c> <c class='let_col-4-{$_kin->arm_cel_dia}'>"._doc::let(_tex::let_may($_kin->nom))."</c>
                    <br><q>"._doc::let($_kin->des)."</q>                  
                  </p>
                </div>
              </div>
              ";
            }$_ .= "
            </section>
          </nav>";
        }else{
          switch( $_ide[1] ){          
          case 'pag':
            $arm_tra = 0;
            $cla = 'ite';
            foreach( array_filter(_hol::_('kin'), function( $ite ){ return !empty($ite->pag); }) as $_kin ){ 
              $pos++; 
              $_sel = _hol::_('sel',$_kin->arm_tra_dia);
              if( $arm_tra != $_kin->arm_tra ){
                $arm_tra = $_kin->arm_tra;
                $_tra = _hol::_('kin_arm_tra',$arm_tra); $_ []= "

                "._doc::ima('hol','ton',$arm_tra,['class'=>"mar_der-1"])."

                <p class='tit'>"._doc::let(_tex::let_may("CICLO ".($num = intval($_tra->ide)).", Baktún ".( $num-1 )))."</p>";
              }
              $_ []= "

              "._doc::ima('hol','kin',$_kin,['class'=>"mar_der-1"])."

              <p>
                <n>{$pos}</n><c>.</c> <b class='ide'>{$_sel->may}</b> <n>".intval($_kin->nav_ond_dia)."</n>
                <br>"._doc::let($_kin->fac_ran)."
              </p>";
            }
            break;
          case 'fac':
            if( empty($_ide[2]) ){
              $ond = 0;
              $_ = "
              <table>";
                if( !empty($ele['tit']) ){ $_.="
                  <caption>".( !empty($ele['tit']['htm']) ? "<p class='tit'>"._doc::let($ele['tit']['htm'])."</p>" : '' )."</caption>";
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
            }else{
              $cla = 'ite';
              switch( $_ide[2] ){
              case 'sel':
                foreach( _hol::_('sel') as $_sel ){ $_ [] = "

                  "._doc::ima('hol','sel_arm_tra',$_sel,['class'=>"mar_der-2"])."

                  <p>
                    <b class='ide'>{$_sel->may}</b><c>:</c> Katún <n>".(intval($_sel->ide)-1)."</n>
                    <br>{$_sel->arm_tra_des}
                  </p>";
                }              
                break;
              case 'ton':
                foreach( _hol::_('kin_nav_ond') as $_ond ){ 
                  $_sel = _hol::_('sel',$_ond->sel); $_ [] = "

                  "._doc::ima('hol','kin_nav_ond',$_ond,['class'=>"mar_der-2"])."

                  <p>
                    <n>{$_ond->ide}</n><c>.</c> <b class='ide'>{$_sel->may}</b><c>:</c> "._doc::let($_ond->fac_ran)."
                    <br><q>{$_ond->fac_des}</q>
                  </p>";
                }              
                break;
              }
            }
            break;
          case 'par':
            $ele = $ele;
            $_lis = [];
            $dat = !is_object($dat) ? _hol::_('kin',$dat) : $dat;
            switch( $_ide[2] ){
            // datos de palabras clave + 
            case 'lec':
              $_par_atr = !empty($ele['par']) ? $ele['par'] : ['fun','acc','mis'];
              $_ton_atr = !empty($ele['ton']) ? $ele['ton'] : ['acc'];
              $_sel_atr = !empty($ele['sel']) ? $ele['sel'] : ['car','des'];            
              foreach( _hol::_('sel_par') as $_par ){
                
                $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});

                $ite = [ _doc::ima('hol','kin',$_kin) ];

                foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= _doc::let($_par->$atr); }

                $_ton = _hol::_('ton',$_kin->nav_ond_dia);
                foreach( $_ton_atr as $atr ){ if( isset($_ton->$atr) ) $ite []= _doc::let($_ton->$atr); }

                $_sel = _hol::_('sel',$_kin->arm_tra_dia);            
                foreach( $_sel_atr as $atr ){  if( isset($_sel->$atr) ) $ite []= _doc::let($_sel->$atr); }

                $_lis []= $ite;
              }
              break;
            // posiciones en tabla comparativa            
            case 'pos': 
              $_atr = ['ene','ene_cam','pag','cro_est','cro_ele','arm_tra','arm_cel','nav_cas','nav_ond'];
              foreach( _hol::_('sel_par') as $_par ){
                
                $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});

                $ite = [ _doc::ima('hol','kin',$_kin) ];

                foreach( $_atr as $atr ){
                  $ite []= _doc::ima('hol',"kin_{$atr}",$_kin->$atr,[ 'class'=>"tam-5" ]);
                }
                
                $_lis []= $ite;
              }
              break;
            // datos del holon : poder, sistem solar, planeta tierra, circuito humano
            case 'sel':
              $_atr = ['sol_pla','sol_cel','sol_cir','pla_hem','pla_mer','hum_cen','hum_ext','hum_mer'];
              foreach( _hol::_('sel_par') as $_par ){
                
                $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});                            

                $_sel = _hol::_('sel',$_kin->arm_tra_dia);

                $ite = [ _doc::ima('hol','kin',$_kin), $_par->nom, $_sel->pod ];

                foreach( $_atr as $atr ){
                  $ite []= _doc::ima('hol',"sel_{$atr}",$_sel->$atr,[ 'class'=>"tam-5" ]);
                }
                
                $_lis []= $ite;
              }
            }
            if( !empty($_lis) ){
              if( !isset($ele['tab']) ) $ele['tab']=[];
              _ele::cla($ele['tab'],"anc-100");
              $_ = _doc_est::ope('lis',$_lis,[],$ele,'htm','cab_ocu');
            }
            break;
          case 'cro':
            if( empty($_ide[2]) ){
            }
            else{
              $cla = 'ite';
              switch( $_ide[2] ){
              case 'est':
                $cla = '';
                foreach( _hol::_('kin_cro_est') as $_est ){

                  $_sel = _hol::_('sel',$_est->sel); $htm = "

                  <div class='val'>
                    "._doc::ima('hol','sel',$_sel,['class'=>"mar_der-2"])."

                    <p>
                      <b class='tit'>ESTACIÓN "._tex::let_may(_tex::art_del("el {$_est->nom}"))."</b>
                      <br>Guardían<c>:</c> <b class='ide'>{$_sel->may}</b> <c>(</c> {$_sel->nom} <c>)</c>
                    </p>
                  </div>";

                  $lis = [];
                  foreach( _hol::_('kin_cro_ond') as $_ond ){ $lis []= "

                    "._doc::ima('hol','ton',$_ond->ton,['class'=>"mar_der-2"])."

                    <p>El quemador {$_ond->que} el Fuego<c>.</c>
                      <br><n>".intval($_ond->ton)."</n> {$_sel->may}
                    </p>";
                  }                
                  $_[] = $htm._doc_lis::val($lis,['lis'=>['class'=>'ite']]);
                }
                break;
              case 'ele':
                break;
              case 'sel':
                foreach( _hol::_('kin_cro_est') as $_est ){ 
                  $_sel = _hol::_('sel',$_est->sel); $_ []= "
                  "._doc::ima('hol','sel',$_sel,['class'=>"mar_der-2"])."
                  <p>
                    <n>{$_est->ide}</n><c>.</c> El espectro galáctico <b class='let_col-4-{$_est->ide}'>{$_est->col}</b><c>:</c> 
                    Estación "._tex::art_del($_sel->nom)."
                  </p>";
                }              
                break;
              case 'ton':
                foreach( _hol::_('kin_cro_ond') as $_ond ){ $_ []= "
                  "._doc::ima('hol','ton',$_ond->ton,['class'=>"mar_der-2"])."
                  <p>
                    Tono <n>".intval($_ond->ton)."</n><c>:</c> 
                    {$_ond->nom} <n>".($_ond->cue*5)."</n> Kines <c>(</c> <n>{$_ond->cue}</n> cromática".( $_ond->cue > 1 ? "s" : "")." <c>)</c>
                  </p>";
                }              
                break;
              case 'ond':
                foreach( _hol::_('kin_cro_ond') as $_ond ){ $_ []= "
                  "._doc::ima('hol','ton',$_ond->ton,['class'=>"mar_der-2"])."
                  <p>
                    Tono <n>".intval($_ond->ton)."</n> de la familia terrestre polar<c>:</c> 
                    {$_ond->nom} <n>1</n> de los <n>4</n> Espectros Galácticos<c>.</c>
                  </p>";
                }              
                break;
              }            
            }
            break;
          case 'arm':
            if( empty($_ide[2]) ){
              if( !isset($ele['nav']) ) $ele['nav']=[];            
              $htm = [];
              $arm_cel = 0;
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
              $_ = "

              "._doc::ope('tog',[ 'eti'=>'h3', 'htm'=>_doc::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], [ 'ico'=>['ocu'=>1] ])."
              
              <nav"._htm::atr($ele['nav']).">

                "._doc_lis::val($htm,$ele,'tog','ver')."
              </nav>";
            }else{          
              switch( $_ide[2] ){
              case 'tra': 
                foreach( _hol::_($est) as $_tra ){ 
                  
                  $htm = "
                  <div class='val'>

                    "._doc::ima('hol','ton',$_tra->ide,['class'=>"mar_der-1"])."

                    <p>
                      <b class='tit'>Baktún <n>".(intval($_tra->ide)-1)."</n><c>.</c> Baktún "._tex::art_del($_tra->lec)."</b>
                      <br>"._doc::let($_tra->ran)." <c><=></c> "._doc::let($_tra->may)."
                    </p>
                  </div>";
                  $lis = [];
                  foreach( explode('; ',$_tra->lec) as $ite ){
                    $lis []= "<c>-></c> "._doc::let($ite);
                  }
                  $_[] = $htm._doc_lis::val($lis,['lis'=>['class'=>"pun"]]);
                }
                break;
              case 'cel': 
                break;
              }
            }
            break;
          case 'nav':
            if( empty($_ide[2]) ){
              $gen = 0;
              $cel = 0;
              $cas = 0;  
              $cla = 'ite';
              foreach( _hol::_('kin_nav_ond') as $_ond ){
                // génesis
                if( $gen != $_ond->gen_enc ){ 
                  $_gen = _hol::_('kin_gen_enc',$gen = $_ond->gen_enc); $_[]="
                  <p class='tit'>{$_gen->lec}<c>:</c> <b class='ide'>Génesis {$_gen->nom}</b><c>.</c></p>";
                }
                if( $cel != $_ond->gen_cel ){ 
                  $_cel = _hol::_('kin_gen_cel',$cel = $_ond->gen_cel); $_[]="
                  <p class='tit'>Célula <n>{$_cel->ide}</n> de la memoria del Génesis<c>:</c> <b class='val'>{$_cel->nom}</b></p>";
                }
                if( $cas != $_ond->nav_cas ){ 
                  $_cas = _hol::_('kin_nav_cas',$cas = $_ond->nav_cas); $_[]="
                  <p class='tit'>
                    El Castillo <b class='let_col-5-{$_cas->ide}'>".str_replace('del ','',$_cas->nom)."</b> "._tex::art_del($_cas->acc)."<c>:</c> La corte "._tex::art_del($_cas->cor)."<c>,</c> poder {$_cas->pod}
                  </p>";
                }              
                $_ []= _doc::ima('hol','kin_nav_ond',$_ond,['class'=>"mar_der-1"])."              
                <p><n>".intval($_ond->ide)."</n><c>°</c> Onda encantada<c>:</c> <q>"._doc::let($_ond->enc_des)."</q></p>";
              }
              break;            
            }else{
              switch( $_ide[2] ){
              case 'cas': 
                $cla .= "ite";
                foreach( _hol::_($est) as $_cas ){ $_ [] = 
                  _doc::ima('hol',$est,$_cas,['class'=>"mar_der-2"])."

                  <p>
                    <b class='ide'>Castillo $_cas->col $_cas->dir "._tex::art_del($_cas->acc)."</b><c>:</c>
                    <br>Ondas Encantadas <n>"._num::int($_cas->ond_ini)."</n> <c>-</c> <n>"._num::int($_cas->ond_fin)."</n>
                  </p>";
                }
                break;
              case 'ond': break;
              case 'gen': break;
              case 'cel': break;
              }
            }          
            break;
          case 'gen':
            $_ = [
              "<b class='ide'>Génesis del Dragón</b><c>:</c> <n>13.000</n> años del Encantamiento del Sueño<c>,</c> poder del sueño<c>.</c>",
              "<b class='ide'>Génesis del Mono</b><c>:</c> <n>7.800</n> años del Encantamiento del Sueño<c>,</c> poder de la magia<c>.</c>",
              "<b class='ide'>Génesis de la Luna</b><c>:</c> <n>5.200</n> años del Encantamiento del Sueño<c>,</c> poder del vuelo mágico<c>.</c>",
            ];
            break;
          }
        }
        break;
      case 'psi':
        if( empty($_ide[1]) ){
        }else{
          switch( $_ide[1] ){
          case 'est': 
            break;      
          case 'lun': 
            if( empty($_ide[2]) ){
              $cla .= "ite";
              foreach( _hol::_('psi_lun') as $_lun ){
                $_ []= _doc::ima('hol','ton',$_lun->ton,['class'=>"mar_der-2"])."
                <p>
                  <b class='ide'>"._tex::let_ora(_num::dat($_lun->ide,'pas'))." Luna</b>
                  <br>Luna "._tex::art_del($_lun->ton_car)."<c>:</c> "._doc::let($_lun->ton_pre)."
                </p>";
              }            
            }
            else{
              switch( $_ide[2] ){
              // 13 cuartetos ocultos de 4 kin
              case 'kin':
                foreach( ['lis','cab','cue'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
                $_hol_kin = _hol::_('kin');
                if( $_ide[3] == 'pag' ){
                  $_ = "
                  <table"._htm::atr($ele['lis']).">
                    <thead"._htm::atr($ele['cab']).">
                      <tr>
                        <th></th>
                        <th>Torre Día <n>1</n></th>
                        <th>Torre Día <n>6</n></th>
                        <th>Torre Día <n>23</n></th>
                        <th>Torre Día <n>28</n></th>
                      </tr>
                      <tr>
                        <th></th>
                        <th><c>(</c>Pareado con día <n>28</n><c>)</c></th>
                        <th><c>(</c>Pareado con día <n>23</n><c>)</c></th>
                        <th><c>(</c>Pareado con día <n>6</n><c>)</c></th>
                        <th><c>(</c>Pareado con día <n>1</n><c>)</c></th>
                      </tr>
                    </thead>
                    <tbody"._htm::atr($ele['cue']).">";
                      foreach( _hol::_('psi_lun') as $_lun ){ $_ .= "
                        <tr>
                          <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                          foreach( explode(', ',$_lun->kin_pag) as $kin ){ $_ .= "
                            <td>"._doc::ima('hol','kin',$kin,['class'=>"mar-1"])."</td>";
                          }$_ .= "   
                        </tr>";
                      }$_ .= "
                    </tbody>
                  </table>";
                }else{                
                  $_ = "
                  <table"._htm::atr($ele['lis']).">
                    <tbody"._htm::atr($ele['cue']).">";
                      foreach( _hol::_('psi_lun') as $_lun ){ $_ .= "
                        <tr>
                          <td><n>".intval($_lun->ide)."</n><c>°</c> Luna</td>";
                          foreach( explode('-',$_lun->kin_cub) as $kin ){ $_ .= "
                            <td>"._doc::ima('hol','kin',$kin,['class'=>"mar-1"])."</td>";
                          }$_ .= "
                          <td>Kines "._doc::let(_tex::mod($_lun->kin_cub,"-"," - "))."</td>
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
                }
                break;
              // 13 lunas con fechas del calendario
              case 'fec':
                $cla .= "ite";
                foreach( _hol::_('psi_lun') as $_lun ){
                  $_[] = _doc::ima($esq,'ton',$_lun->ton,['class'=>"mar_der-3"])."
                  <p>
                    <b class='ide'>$_lun->nom</b> <n>".intval($_lun->ton)."</n>
                    <br>"._doc::let($_lun->fec_ran)."
                  </p>";
                }$_[] = "
                <span ima></span>
                <p>
                  <b class='ide'>Día Verde</b> o Día Fuera del Tiempo
                  <br><n>25</n> de Julio
                </p>";
                break;
              // totems animales
              case 'tot':
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
                $cla .= "ite";
                foreach( $_lis as $ite => $htm ){                
                  $_ []= _doc::ima('hol','psi_lun',$_lun = _hol::_('psi_lun',$ite),['class'=>"mar_der-2"])."
                  <p>
                    $htm
                    <br>"._doc::let($_lun->fec_ran)."
                  </p>";
                }
                $_ []= "
                <p>
                  Día fuera del tiempo
                  <br><n>25</n> de Julio
                </p>";
                break;
              }
            }
            break;
          case 'hep': 
            break;
          case 'vin': 
            $_ = _doc_est::ope('lis','hol.psi_vin',[ 'atr'=>['ide','nom','fec','sin','cro'], 'det_des'=>['des'] ],[ 'lis'=>[ 'class'=>"anc-100 mar-2" ] ] );
            break;
          case 'cro':
            if( empty($_ide[2]) ){

            }else{
              switch( $_ide[2] ){
              case 'arm':
                if( empty($dat) ){
                  $dat = [1,2,3,4];
                }
                foreach( $dat as $arm ){
                  $cro_arm = _hol::_('psi_cro_arm',$arm);
                  $_ []= "Cromática <c class='let_col-4-$arm'>$cro_arm->col</c><br>"._doc::let("$cro_arm->nom: $cro_arm->des");
                }
                break;
              }
            }
            break;
          }
        }
        break;
      }
      if( is_array($_) ){

        $_ = _doc_lis::$tip($_,[ 
          'lis'=>[ 'est'=>$est, 'class'=>$cla.( !empty($cla) ? " " : "" )."anc_max-fit mar-aut mar_ver-3" ],
          'dep'=> isset($_lis_dep) ? $_lis_dep : [],
          'ite'=> isset($_lis_ite) ? $_lis_ite : []
        ]);
      }
      return $_;
    }
  }

  // tableros : seccion + posicion
  class _hol_tab {
    
    // por tipo
    static function ver( string $ide, mixed $dat = FALSE, array $ope=[], array $ele=[] ) : string {    
      global $_hol; $esq = 'hol'; 
      foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
      $_ = "";
      $_ide = explode('_',$ide);        
      $ele = _api::tab($esq,$ide,$ele);
      if( !isset($ope['opc']) ){ $ope['opc']=[]; }
      if( isset($ope["sec_{$esq}"]) ){ $ope['sec'] = array_merge( isset($ope['sec']) ? $ope['sec'] : [], $ope["sec_{$esq}"]); }
      // identificadores    
      $dat_ide = 0;
      if( !empty($dat) ){

        if( is_array($dat) ){
          $dat_ide = isset($dat['ide']) ? $dat['ide'] : 0;
          if( is_object($dat_ide) && isset($dat_ide->ide) ){
            $dat_ide = $dat_ide->ide;
          }
          $dat_kin = isset($dat['kin']) ? $dat['kin'] : FALSE;
          if( $dat_kin && !is_object($dat_kin) ){ 
            $dat_kin = $dat['kin'] = _hol::_('kin',$dat['kin']); 
          }
          $dat_psi = isset($dat['psi']) ? $dat['psi'] : FALSE;
          if( $dat_psi && !is_object($dat_psi) ){ 
            $dat_psi = $dat['psi'] = _hol::_('psi',$dat['psi']); 
          }
          // dependencias
          $dat_fec = isset($dat['fec']) ? $dat['fec'] : FALSE;
          $dat_sin = isset($dat['sin']) ? $dat['sin'] : FALSE;
        }
        elseif( is_object($dat) ){
          if( isset($dat->ide) ){
            $dat_ide = intval($dat->ide);
          }        
        }
        else{
          $dat_ide = intval($dat);
        }

      }
      switch( $_ide[0] ){
      // tableros
      case 'tel': // telektonon - telepatía
        break;
      case 'sol': // holon solar interplanetario
        $ocu = []; foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
        $e = $ele['sec']; $_="
        <div"._htm::atr($e).">
          <div fon='cel' class='{$ocu['cel']}'></div>
          <div fon='map'></div>
          <div fon='ato'></div>
          <div fon='pla' class='{$ocu['pla']}'></div>
          <div fon='cla' class='{$ocu['cla']}'></div>
          <div fon='cir' class='{$ocu['cir']}'></div>
          <div fon='res' class='{$ocu['res']}'></div>";
          foreach( _hol::_('sel_sol_pla') as $v ){ $_ .= "
            <div pla='{$v->ide}' class='{$ocu['pla']}' title='"._doc_dat::val('tit',"{$esq}.sol_pla",$v)."'></div>";
          }
          foreach( _hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            _ele::cla($e,"pos"); 
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._doc::ima($esq,'sel_cod',$_sel->cod)."
            </div>";
          }
          $_ .= " 
        </div>";
        break;
      case 'pla': // holon planetario
        $ocu = []; foreach( ['res','flu','cen','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
        $e = $ele['sec']; $_="
        <div"._htm::atr($e).">
          <div fon='map'></div>
          <div fon='sel' class='{$ocu['sel']}'></div>
          <div fon='cen' class='{$ocu['cen']}'></div>          
          <div fon='flu' class='{$ocu['flu']}'></div>
          <div fon='res' class='{$ocu['res']}'></div>
          ";
          foreach( _hol::_('sel_cro_fam') as $_dat ){             
            $_.=_doc::ima($esq,'sel_cro_fam',$_dat,[ 'fam'=>$_dat->ide, 'class'=>$ocu['cen'] ]);
          }
          foreach( _hol::_('sel') as $_sel ){
            $e = $ele['pos'];
            _ele::cla($e,"pos");
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._doc::ima($esq,'sel',$_sel)."
            </div>";
          }
          $_ .= "
        </div>";
        break;
      case 'hum': // holon humano
        $ocu = []; foreach( ['res','ext','cir','cen'] as $i ){  $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
        if( isset($ope['kin']) ){
          $_sel = _hol::_('sel',$ope['kin']['arm_tra_dia']);
          $_ton = _hol::_('ton',$ope['kin']['nav_ond_dia']);
        }$e = $ele['sec']; $_="
        <div"._htm::atr($e).">
          <div fon='map'></div>
          <div fon='cir' class='{$ocu['cir']}'></div>
          <div fon='cen' class='{$ocu['cen']}'></div>
          <div fon='ext' class='{$ocu['ext']}'></div>
          <div fon='res' class='{$ocu['res']}'></div>";
          
          foreach( _hol::_('sel_cro_ele') as $_dat ){ 
            $_ .= _doc::ima($esq,'sel_cro_ele',$_dat,['ele'=>$_dat->ide,'class'=>$ocu['ext']]);
          }
          foreach( _hol::_('sel_cro_fam') as $_dat ){ 
            $_ .= _doc::ima($esq,'sel_cro_fam',$_dat,['fam'=>$_dat->ide,'class'=>$ocu['cen']]);
          }
          foreach( _hol::_('sel') as $_sel ){
            $e = $ele['pos'];
            _ele::cla($e,"pos");
            $e['pos'] = $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._doc::ima($esq,'sel',$_sel)."
            </div>";
          }
          $_ .= "
        </div>";
        break;
      // ciclos
      case 'arm': break;
      case 'cro': break;
      case 'rad':
        if( empty($_ide[1]) ){
        }
        else{      
          switch( $_ide[1] ){
          case 'hum':
            if( isset($dat['kin']) ){
              $_ton = _hol::_('ton',$dat['kin']['nav_ond_dia']);
            }$_ .= "
            <div"._htm::atr($ele['sec']).">
              <div fon='map'></div>";
              foreach( _hol::_('ton') as $_ton ){ 
                $e = $ele['pos'];
                _ele::cla($e,"pos{$ocu['ton']}"); 
                $e['ton'] = $_ton->ide; $_ .= "
                <div"._htm::atr($e).">
                  "._doc::ima($esq,'ton',$_ton)."
                </div>";
              }
              $_ .= "
            </div>";
            break;          
          }
        }
        break;
      case 'ton':
        if( empty($_ide[1]) ){
          $_tab = _api::tab('hol','ton'); $_ .= "
          <div"._htm::atr(_ele::jun($ele['sec'],$_tab['sec'])).">
            <div fon='ima'></div>
            "._hol_tab::sec('ton',$dat,$ope)
            ;
            $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
            foreach( _hol::_('ton') as $_ton ){
              $i = "pos-{$_ton->ide}";
              $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
              $_ .= _hol_tab::pos($ide,$_ton,$ope,$ele,$dat);
            } $_ .= "
          </div>";
        }
        else{
          switch( $_ide[1] ){
          case 'hum':
            $_ .= "
            <div"._htm::atr($ele['sec']).">
              <div fon='map'></div>";
              foreach( _hol::_('ton') as $_ton ){
                $e = $ele['pos'];
                _ele::cla($e,"pos"); 
                $e['pos'] = $_ton->ide; $_ .= "
                <div"._htm::atr($e).">"._doc::ima($esq,'ton',$_ton)."</div>";
              }$_ .= "
            </div>";
            break;
          }
        }
        break;
      case 'sel':
        if( empty($_ide[1]) ){ $_ = "
          <div"._htm::atr($ele['sec']).">";
            foreach( _hol::_('sel') as $_sel ){ 
              $agr = ( !!$dat_ide && $_sel->ide == $dat_ide ) ? ' _val-pos' : '';
              $_ .= "
              <div class='sec{$agr}'>
                <ul class='val jus-cen'>
                  "._doc::ima($esq,'sel',$_sel,['eti'=>"li"])."
                  "._doc::ima("cod/{$_sel->cod}",['eti'=>"li",'class'=>'tam-2'])."
                </ul>
                <p class='mar-0 ali_pro-cen'>
                  {$_sel->arm}
                  <br>{$_sel->acc}
                  <br>{$_sel->pod}
                </p>
              </div>";
            } $_ .= "
          </div>";
        }else{
          switch( $_ide[1] ){
          case 'par':
            $_sel = is_array($dat) ? $dat : _hol::_('sel',$dat); 
            $ele_pos = $ele['pos']; $_ = "
            <div"._htm::atr($ele['sec']).">";
              foreach( _hol::_('sel_par') as $_par  ){
                $_ide[1] = "par_{$_par->ide}";
                $_dat = [];
                if( $_ide[1] == 'par_des' ){
                  $_dat = $_sel;
                }elseif( $_ide[1] != 'par_gui' ){
                  $_dat = _hol::_('sel',$_sel[$_ide[1]]);
                }
                $cla_agr = " fon_col-4-".( isset($_dat->arm_raz) ? $_dat->arm_raz : $_sel->arm_raz );
                $ele['pos'] = _ele::jun($ele_pos, [ 
                  'pos'=>$_par->pos, 
                  'class'=>"{$_par->ide}{$cla_agr}", 
                  'onclick'=>isset($ele['sec']['_eje']) ? $ele['sec']['_eje'] : NULL
                ]);
                if( isset($ele[$_par->ide]) ){ 
                  $ele['pos'] = _ele::jun($ele['pos'],$ele[$_par->ide]);
                }
                $_ .= _hol_tab::pos('sel',$_dat,$ope,$ele,$dat);
              }$_ .="
            </div>";
            break;                
          case 'arm':
            // colocación armónica
            if( empty($_ide[2]) ){
              $_ .= "
              <div"._htm::atr($ele['sec']).">
                <div pos='0'></div>
                ";
                foreach( _hol::_('sel_arm_cel') as $_dep ){ 
                  $_ .= _doc::ima($esq,'sel_arm_cel',$_dep,['cel'=>$_dep->ide]);
                } 
                foreach( _hol::_('sel_arm_raz') as $_dep ){ 
                  $_ .= _doc::ima($esq,'sel_arm_raz',$_dep,['raz'=>$_dep->ide]);
                }
                foreach( _hol::_($_ide[0]) as $_sel ){
                  $agr = ( isset($dat_ide) && $_sel->ide == $dat_ide ) ? ' _val-pos' : '' ;
                  $e = $ele['pos'];
                  _ele::cla($e,"pos{$agr}"); 
                  $e['pos'] = $_sel->ide;
                  $e['sel'] = $_sel->ide; $_ .= "
                  <div"._htm::atr($e).">
                    "._doc::ima($esq,$_ide[0],$_sel,[ 
                      'onclick'=>isset($ele['pos']['_eje']) ? $ele['pos']['_eje'] : NULL 
                    ])."
                  </div>";
                }
                $_ .= "
              </div>";
            }// 1 trayectoria de 5 células por 20 sellos
            elseif( $_ide[2]=='tra' ){ 
              $_ .= "
              <div"._htm::atr($ele['sec']).">";
                for( $i=1; $i<=5; $i++ ){             
                  $dat['ide'] = $i;
                  $_ .= _hol_tab::ver('kin_arm_cel',$dat,$ope,$ele);
                } $_ .= "
              </div>";
            }// 1 célula de 5 sellos
            elseif( $_ide[2]=='cel' ){
              $_arm = _hol::_('sel_arm_cel',$dat_ide);
              $e = isset($ope['cel']) ? $ope['cel'] : [];
              _ele::cla($e,"{$ide}");
              $e['title'] = _doc_dat::val('ver',"{$esq}.sel_arm_cel",$_arm); $_ = "
              <div"._htm::atr($e).">
                <div pos='00'>
                  "._doc::ima($esq,'sel_arm_cel', $_arm, ['htm'=>$_arm->ide,'class'=>'ima'] )."
                </div>
                ";
                $sel = ( $dat_ide * 4 ) + 1;
                foreach( _hol::_('sel_arm_raz') as $_raz ){
                  $_.= _hol_tab::pos($ide,$sel,$ope,$ele,$dat);
                  $sel++;
                } $_ .= "
              </div>";
            }
            break;   
          case 'cro':
            // colocacion cromática
            if( empty($_ide[2]) ){
              $e = $ele['sec'];
              _ele::cla($e,"{$ide}"); $_ = "
              <div"._htm::atr($e).">
                <div pos='0'></div>";
                foreach( _hol::_('sel_cro_fam') as $_dep ){ 
                  $_ .= _doc::ima($esq,'sel_cro_fam',$_dep,['fam'=>$_dep->ide]);
                } 
                foreach( _hol::_('sel_cro_ele') as $_dep ){ 
                  $_ .= _doc::ima($esq,'sel_cro_ele',$_dep,['ele'=>$_dep->ide]);
                }
                for( $i=0; $i<=19; $i++ ){ 
                  $_sel = _hol::_('sel', ( $i == 0 ) ? 20 : $i);
                  $agr = ( !!$dat_ide && $_sel->ide == $dat_ide ) ? ' _val-pos' : '' ;
                  $e = $ele['pos'];
                  _ele::cla($e,"pos{$agr}");             
                  $e['pos'] = $_sel->ide;
                  $e['hol-sel'] = $_sel->ide; $_ .= "
                  <div"._htm::atr($e).">
                    "._doc::ima($esq,'sel',$_sel,[
                      'onclick'=>isset($ele['pos']['_eje']) ? $ele['pos']['_eje'] : ""
                    ])."
                  </div>";
                } $_ .= "
              </div>";
            }// 1 elementro de 5 sellos
            elseif( $_ide[2]=='ele' ){
              if( !isset($ope['ele']) ) $ope['ele']=[];
              $_cro = _hol::_('sel_cro_ele',$dat_ide);
              $e = $ope['ele'];
              _ele::cla($e,"{$ide}");
            }
            break;   
          }     
        }
        break;
      case 'lun':
        if( empty($_ide[1]) ){        
        }
        else{
        }break;            
      case 'cas':
        if( empty($_ide[1]) ){
          $_tab = _api::tab('hol','cas');
          $_ .= "
          <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            <div fon='ima'></div>
            <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></div>
            "._hol_tab::sec('cas',$dat,$ope)
            ;
            $ele_pos = $ele['pos'];
            foreach( _hol::_('cas') as $_cas ){
              $i = "pos-{$_cas->ide}";
              $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
              $_ .= _hol_tab::pos($ide,$_cas,$ope,$ele,$dat);
            } $_ .= "
          </div>";
        }
        else{
        }
        break;    
      case 'kin':
        if( empty($_ide[1]) ){
          if( !empty($ope['sec']['ton']) ){ _ele::css($ele['sec'],"grid-: repeat(21,1fr) / repeat(13,1fr);"); }        
          $_ .= "
          <div"._htm::atr($ele['sec']).">";
            // 1° columna
            $ele_ini=[ 'sec'=>'ini', 'class'=>"dis-ocu" ]; $_ .= "
            <div"._htm::atr(_ele::jun($ele_ini,$ele['pos-0']))."></div>";
            // filas por sellos
            $ele_sel=[ 'sec'=>'sel', 'class'=>"dis-ocu" ];          
            foreach( _hol::_('sel') as $_sel ){
              $ele_sel['ide'] = $_sel->ide; $_ .= "
              <div"._htm::atr($ele_sel).">
                "._doc::ima($esq,'sel',$_sel)."
              </div>"; 
            }
            // columnas por tono
            $ele_ton=[ 'sec'=>'ton', 'class'=>"dis-ocu" ];

            // 260 kines por 13 columnas
            $kin_arm = 0;
            $ele_pos = $ele['pos'];
            foreach( _hol::_('kin') as $_kin ){
              $kin_arm_tra = intval($_kin->arm_tra);
              if( $kin_arm != $kin_arm_tra ){
                $ele_ton['ide'] = $_kin->arm_tra; $_ .= "
                <div"._htm::atr(_ele::jun($ele_ton,$ele["ton-{$_kin->arm_tra}"])).">
                  "._doc::ima($esq,'ton',$_kin->arm_tra)."
                </div>";
                $kin_arm = $kin_arm_tra;
              }
              if( isset($ele["pos-{$_kin->ide}"]) ){
                $ele['pos'] = _ele::jun( $ele["pos-{$_kin->ide}"], $ele['pos'] );
              }
              $_ .= _hol_tab::pos($ide,$_kin,$ope,$ele,$dat);
              $ele['pos'] = $ele_pos;
            } $_ .= "
          </div>";
        }
        else{
          switch( $_ide[1] ){
          case 'par':
            $_tab = _api::tab('hol','cro');          
            $_kin = isset($dat_kin) ? $dat_kin : ( is_object($dat) ? $dat : _hol::_($_ide[0],$dat_ide) ); 
            $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
            $_ = "
            <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
              foreach( _hol::_('sel_par') as $_par  ){               
                $par_ide = $_par->ide;
                $par_kin = ( $par_ide=='des' ) ? $_kin : _hol::_('kin',$_kin->{"par_{$par_ide}"});
                $ele['pos'] = _ele::jun($_tab["pos-{$_par->pos}"], [ 
                  [ 'class'=>"pos-{$par_ide}", 'onclick'=>isset($ele['sec']['_eje']) ? $ele['sec']['_eje'] : NULL ],
                  $ele_pos, isset($ele["pos-{$par_ide}"]) ? $ele["pos-{$par_ide}"] : []
                ]);
                $_ .= _hol_tab::pos($ide,$par_kin,$ope,$ele,$dat);
              }$_ .="
            </div>";
            break;        
          case 'cas':
            $_tab = _api::tab('hol','cas');
            $_fam = _hol::_('sel_cro_fam',$dat_ide);          
            $_fam_kin = [ 1=>1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ]; $_="
            <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
              <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
              </div>"
              ._hol_tab::sec('cas',$dat,$ope)
              ;
              $kin = intval($_fam['kin']);
              $ele_pos = $ele['pos'];
              foreach( _hol::_('cas') as $_cas ){
                $_kin = _hol::_('kin',$kin);
                $i = "pos-{$_cas->ide}";
                $ele['pos'] = _ele::jun($_tab[$i], [ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
                $_ .= _hol_tab::pos($ide,$kin,$ope,$ele,$dat);
                $kin = $kin + 105; 
                if( $kin > 260 ){ $kin = $kin - 260; }
              } $_ .= "
            </div>";          
            break;
          case 'cro':
            foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
            if( empty($_ide[2]) ){            
              $_tab = _api::tab('hol','cas');
              $ope['opc'] []= 'fic_cas'; $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
                <div"._htm::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
                  "._doc::ima('hol/gal')."
                </div>"
                ._hol_tab::sec('cas',$dat,$ope)
                ;
                $ele_ele = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['ele']) : $ele['ele'];
                foreach( _hol::_('kin_cro_ele') as $_cro ){                
                  $i = "pos-{$_cro->ide}";
                  $ele['ele'] = _ele::jun($_tab[$i],[ $ele_ele, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $dat['ide'] = $_cro->ide;
                  $_ .= _hol_tab::ver("kin_cro_ele",$dat,$ope,$ele);                
                } $_ .= "
              </div>";
            }// 1 estación espectral de 52 elementos cromáticos
            elseif( $_ide[2]=='est' ){
              $_tab = _api::tab('hol','ton');
              if( !in_array('fic_cas',$ope['opc']) ){  
                $ope['opc'] []= 'fic_ond'; 
              } $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['est'])).">
                "._hol_tab::sec('ton',$dat,$ope)
                ;
                $_est = _hol::_('kin_cro_est',$dat_ide); 
                $cas = $_est->cas;
                $ele_ele = isset($_tab['ele']) ? _ele::jun($_tab['ele'],$ele['ele']) : $ele['ele'];
                foreach( _hol::_('ton') as $_ton ){                
                  $i = "pos-{$_ton->ide}";
                  $ele['ele'] = _ele::jun($_tab[$i], [ $ele_ele, isset($ele[$i]) ? $ele[$i] : [] ]);                
                  $dat['ide'] = $cas;
                  $_ .= _hol_tab::ver("kin_cro_ele",$dat,$ope,$ele);
                  $cas++; if( $cas > 52 ){ $cas = 1; }
                } $_ .= "
              </div>";
            }// 1 elemento de 5 kin
            elseif( $_ide[2]=='ele' ){
              $_tab = _api::tab('hol','cro_cir');
              $_ele = _hol::_('kin_cro_ele',$dat_ide);
              // cuenta de inicio
              $kin_ini = 185;
              $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";
              // viene del castillo o de onda
              if( isset($ele['ele']['pos']) ){
                // rotaciones
                $ele_rot = in_array('fic_cas',$ope['opc']) ? $ele['rot-cas'][$dat_ide-1] : $ele['rot-ond'][$dat_ide-1]; 
                _ele::css($ele['ele'],"transform: rotate({$ele_rot}deg)");
              }$_ .= "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ele'])).">
                <div"._htm::atr(_ele::jun($_tab['pos-0'],isset($ele['pos-0']) ? $ele['pos-0'] : [])).">
                  "._doc::ima("hol/cro/{$_ele->arm}", [ 'htm'=>$_ele->ide, 'class'=>"alt-100 anc-100" ])."
                </div>";
                $kin = $kin_ini + ( ( $dat_ide - 1 ) * 5 ) + 1;                
                if( $kin > 260 ){ $kin = $kin - 260; }
                $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
                foreach( _hol::_('sel_cro_fam') as $cro_fam ){
                  $i = "pos-{$cro_fam->ide}";
                  $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $_ .= _hol_tab::pos($ide,$kin,$ope,$ele,$dat);
                  $kin++;// por verdad eléctrica
                  if( $kin > 260 ){ $kin = 1; }                  
                }$_ .= "
              </div>";
            }
            break;                    
          case 'arm':
            foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
            // 13 trayectorias armónicas
            if( empty($_ide[2]) ){
              $_tab = _api::tab('hol','ton');            
              $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
                "._hol_tab::sec('ton',$dat,$ope)
                ;
                $ele_tra = isset($_tab['pos']) ? _ele::jun($ele['tra'],$_tab['pos']) : $ele['tra'];
                foreach( _hol::_('kin_arm_tra') as $_tra ){ 
                  $i = "pos-{$_tra->ide}";
                  $ele['tra'] = _ele::jun($_tab[$i],[ $ele_tra, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $dat['ide'] = $_tra->ide;
                  $_ .= _hol_tab::ver("kin_arm_tra",$dat,$ope,$ele);
                } $_ .= "
              </div>";
            }// 1 trayectoria de 5 células
            elseif( $_ide[2]=='tra' ){
              $_tab = _api::tab('hol','cro');
              $_tra = _hol::_($ide,$dat_ide); $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['tra'])).">";
                $cel_pos = 0;
                $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
                $cel_fin = $cel_ini + 5; 
                $ele_cel = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['cel']) : $ele['cel'];
                for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
                  $cel_pos++;                
                  $i = "pos-{$cel_pos}";
                  $ele['cel'] = _ele::jun($_tab[$i],[ $ele_cel, isset($ele[$i]) ? $ele[$i] : [] ]);
                  if( is_array($dat) ){
                    $dat['ide'] = $cel; 
                  }else{
                    $dat = $cel;
                  }
                  $_ .= _hol_tab::ver('kin_arm_cel',$dat,$ope,$ele);
                } $_ .= "
              </div>";
            }// 1 célula de 4 kin
            elseif( $_ide[2]=='cel' ){
              $_tab = _api::tab('hol','arm');
              $_arm = _hol::_($ide,$dat_ide);
              $ele['cel']['title'] = _doc_dat::val('ver',"{$esq}.{$ide}",$_arm); $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['cel'])).">";
                $pos_cen = isset($ele['pos-0']) ? _ele::jun($_tab['pos-0'],$ele['pos-0']) : $_tab['pos-0'];
                if( isset($ele['pos']['style']) ){
                  _ele::css($ele['pos']);
                  if( isset($ele_art['width']) ){ _ele::css($pos_cen,['width'=>'']); }
                  if( isset($ele_art['height']) ){ _ele::css($pos_cen,['height'=>'']); }
                }
                $_ .= "
                <div"._htm::atr($pos_cen).">
                  "._doc::ima($esq,'sel_arm_cel', $_arm->cel, [ 'htm'=>$_arm->ide, 'class'=>'ima' ] )."
                </div>";
                $kin = ( ( $dat_ide - 1 ) * 4 ) + 1;
                $ele_pos = $ele['pos'];
                for( $arm=1; $arm<=4; $arm++ ){
                  $i = "pos-{$arm}";
                  $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $_ .= _hol_tab::pos($ide,$kin,$ope,$ele,$dat);
                  $kin++;
                } $_ .= "
              </div>";
            }
            break;   
          case 'nav':
            foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
            if( empty($_ide[2]) ){
              $_tab = _api::tab('hol','cro');
              $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
                ";
                $ele_cas = $ele['cas'];
                foreach( _hol::_('kin_nav_cas') as $cas => $_cas ){                 
                  $i = "pos-{$_cas->ide}";
                  $ele['cas'] = _ele::jun($_tab[$i],[ $ele_cas, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $dat['ide'] = $_cas->ide;
                  $_ .= _hol_tab::ver('kin_nav_cas',$dat,$ope,$ele);
                } $_ .= "
              </div>";
            }
            elseif( $_ide[2]=='cas' ){
              $_tab = _api::tab('hol','cas');
              $_cas = _hol::_($ide,$dat_ide);
              _ele::cla( $ele['cas'], "fon_col-5-{$dat_ide}".( empty($ope['sec']['col']) ? ' fon-0' : '' ) );
              $ele['cas']['title'] = _doc_dat::val('ver',"{$esq}.{$ide}",$_cas);            
              $ond_ini = ( ( $dat_ide - 1 ) * 4 ) + 1;
              $ond_fin = $ond_ini + 4;
              for( $ond = $ond_ini; $ond < $ond_fin; $ond++ ){ 
                $_ond = _hol::_('kin_nav_ond',$ond);
                $ele['cas']['title'].="\n".$_ond->enc_des;
              }
              $_ = "
              <div"._htm::atr( _ele::jun($_tab['sec'],$ele['cas']) ).">
                <div"._htm::atr(_ele::jun($_tab['pos-00'],[ 
                  [ 'class'=>"bor_col-5-{$dat_ide} fon_col-5-{$dat_ide}" ], isset($ele['pos-00']) ? $ele['pos-00'] : [] 
                ])).">
                  {$dat_ide}
                </div>
                "._hol_tab::sec('cas',$dat,$ope)
                ;
                $kin = ( ( $dat_ide - 1 ) * 52 ) + 1;
                $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);
                foreach( _hol::_('cas') as $_cas ){ 
                  $i = "pos-{$_cas->ide}";
                  $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $_ .= _hol_tab::pos($ide,$kin,$ope,$ele,$dat);
                  $kin++;
                } $_ .= "
              </div>";
            }
            elseif( $_ide[2]=='ond' ){
              $_tab = _api::tab('hol','ton');
              $_ond = _hol::_($ide,$dat_ide); 
              $_cas = _hol::_('kin_nav_cas',$_ond->nav_cas);            
              $ele['ond']['title'] = _doc_dat::val('ver',"{$esq}.kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; $_ = "
              <div"._htm::atr(_ele::jun($_tab['sec'],$ele['ond'])).">
                "._hol_tab::sec('ton',$dat,$ope)
                ;
                $kin = ( ( $dat_ide - 1 ) * 13 ) + 1;
                $ele_pos = $ele['pos'];
                foreach( _hol::_('ton') as $_ton ){
                  $i = "pos-{$_ton->ide}";
                  $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele[$i]) ? $ele[$i] : [] ]);
                  $_ .= _hol_tab::pos($ide,$kin,$ope,$ele,$dat);
                  $kin++;
                } $_ .= "
              </div>";
            }
            break;
          }
        }
        break;
      case 'psi':
        if( empty($_ide[1]) ){
          foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }        
          $_tab = _api::tab('hol','ton'); $_ .= "
          <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            <div sec='uni-sol' style='width:100px; height:100px; grid-row:1; grid-column:2; justify-self:end; align-self:end;'>
              "._doc::ima('hol/tab/sol')."
            </div>
            <div sec='uni-lun' style='width:60px; height:60px; grid-row:3; grid-column:3; align-self:center; justify-self:center;'>
              "._doc::ima('hol/tab/pla')."
            </div>
            "._hol_tab::sec('ton',$dat,$ope)
            ;
            if( !in_array('cab',$ope['opc']) ){ 
              $ope['opc'][]='cab';
            }
            $ele_lun = $ele['lun'];
            foreach( _hol::_('psi_lun') as $_lun ){
              $i = "pos-{$_lun->ide}";
              $ele['lun'] = _ele::jun($_tab[$i],[ $ele_lun, isset($ele["lun_{$i}"]) ? $ele["lun_{$i}"] : [] ]);
              $dat['ide'] = $_lun->ide;
              $_ .= _hol_tab::ver("psi_lun",$dat,$ope,$ele);
            } $_ .= "
          </div>";
        }else{
          switch( $_ide[1] ){
          case 'ani':
            $_tab = _api::tab('hol','cas_cir');
            $kin = 34;
            if( !isset($ope['sec']['orb_cir']) ){ 
              $ope['sec']['orb_cir']='1'; 
            }
            $_ = "
            <div"._htm::atr(_ele::jun($_tab['sec'],$ele['sec'])).">

              "._hol_tab::sec('cas_cir',$dat,$ope)
              ;
              foreach( _hol::_('cas') as $_cas ){
                $_kin = _hol::_('kin',$kin);
                $agr = '';
                $e = $ele['pos'];
                _ele::cla($e,"pos{$agr}"); 
                $e['pos'] = $_cas->ide;         
                $e['kin'] = $_kin->ide;
                $e['ton'] = $_cas['ton'];
                $_ .= "
                <div"._htm::atr($e).">
                  "._doc::ima($esq,'kin',$_kin,[ 'onclick'=>isset($e['_eje'])?$e['_eje']:NULL ])."
                </div>";
                $kin += 105; if( $kin >260 ) $kin -= 260;
              } $_ .= "
            </div>";
            break;
          case 'est':
            foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
            $_tab = _api::tab('hol','cas');
            $_ = "
            <div"._htm::atr( _ele::jun($_tab['sec'],$ele['sec']) ).">
              "._hol_tab::sec('cas',$dat,$ope)
              ; 
              $ele_hep = $ele['hep'];
              foreach( _hol::_('cas') as $_cas ){
                $dat['ide'] = $_cas->ide;
                $i = "hep-{$_cas->ide}";
                $ele['hep'] = _ele::jun($_tab["pos-{$_cas->ide}"],[ $ele_hep, isset($ele[$i]) ? $ele[$i] : [] ]);
                $_ .= _hol_tab::ver('psi_hep',$dat,$ope,$ele);
              } $_ .= "
            </div>";
            break;
          case 'lun':
            foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
            if( empty($dat_ide) && is_array($dat) && isset($dat['psi']) ){
              $dat_ide = _hol::_('psi',$dat['psi'])->lun;
            }
            $_tab = _api::tab('hol','lun');
            $_lun = _hol::_($ide,$dat_ide);
            $_ton = _hol::_('ton',$dat_ide);
            $_ = "
            <table"._htm::atr(_ele::jun($_tab['sec'],$ele['lun'])).">
    
              <thead>
                <tr sec='ton'>
                  <th colspan='8'>
                    <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>
                      "._doc::ima($esq,$ide,$_lun,['class'=>"tam-1 mar_der-1"])."
                      <p>
                        <n>{$dat_ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."
                      </p> 
                    </div>
                  </th>
                </tr>";
                if( in_array('rad',$ope['opc']) ){ $_ .= "
                  <tr sec='rad'>                  
                    <th></th>";
                    foreach( _hol::_('rad') as $_rad ){ $_ .= "
                      <th>
                        "._doc::ima($esq,'rad',$_rad,[])."
                      </th>";
                    }$_ .= "                  
                  </tr>";
                }$_ .="
              </thead>";

              $dia = 1;    
              $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
              $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
              $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
              $ope['eti']='td'; $_ .= "
              <tbody>";
              for( $arm = 1; $arm <= 4; $arm++ ){
                $_ .= "
                <tr>";
                  $_cas = _hol::_('cas',$hep); 
                  $i = "hep-{$arm}"; $_ .= "
                  <td"._htm::atr(_ele::jun(['class'=>"fon_col-4-{$arm}", 'title'=>$_cas->des], isset($ele[$i]) ? $ele[$i] : [] )).">
                    {$_cas->ide}
                  </td>";
                  for( $rad=1; $rad<=7; $rad++ ){
                    $_dia = _hol::_('lun',$dia);
                    $i = "pos-{$_dia->ide}";
                    $ele['pos'] = _ele::jun($ele_pos, isset($ele[$i]) ? $ele[$i] : []);
                    $_ .= _hol_tab::pos($ide,$psi,$ope,$ele,$dat);
                    $dia++;
                    $psi++;
                  }
                  $hep++; $_ .= "
                </tr>";
              }$_ .= "
              </tbody>

            </table>";
            break;
          case 'hep':
            foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
            if( empty($dat_ide) && is_array($dat) && isset($dat['psi']) ){
              $dat_ide = _hol::_('psi',$dat['psi'])->hep;
            }          
            $_tab = _api::tab('hol','rad');
            $_hep = _hol::_('psi_hep',$dat_ide);        
            $_ .= "
            <div"._htm::atr(_ele::jun($_tab['sec'],$ele['hep'])).">";
              $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
              $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'] ;
              foreach( _hol::_('rad') as $_rad ){
                $_psi = _hol::_('psi',$psi);            
                $i = "pos-{$_rad->ide}";
                $ele['pos'] = _ele::jun($_tab[$i],[ $ele_pos, isset($ele["rad_{$i}"]) ? $ele["rad_{$i}"] : [] ]);
                $_ .= _hol_tab::pos($ide,$psi,$ope,$ele,$dat);
                $psi++;
              } $_ .= "
            </div>";
            break;
          case 'tzo':
            $_ = "
            <div"._htm::atr($ele['sec']).">";
              for( $i=1 ; $i<=8 ; $i++ ){
                $_ .= _hol_tab::ver('kin',[ 'kin'=>!empty($dat_kin)?$dat_kin:NULL ], [], ['sec'=>[ 'pos'=>$i ]]);    
              } $_ .= "
            </div>";
            break;
          }
        }
        break;
      case 'ani':
        $ocu = []; foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; } $_ .= "
        <div"._htm::atr($ele['sec']).">"
          ._doc::ima("hol/gal",['eti'=>"div",'fic'=>'gal','title'=>'Fin de la Exhalación Solar. Comienzo de la Inhalación Galáctica.'])
          ._doc::ima("hol/sol",['eti'=>"div",'fic'=>'sol','title'=>'Fin de la Inhalación Galáctica. Comienzo de la Exhalación Solar.']);
          foreach( _hol::_('sel_pol_flu') as $v ){ 
            $_ .= _doc::ima($esq,'sel_pol_flu',$v,['eti'=>"div",'flu'=>$v['ide'],'class'=>$ocu['res'],'title'=> _doc_dat::val('ver',"{$esq}.sel_pol_flu",$v)]);
          }
          foreach( _hol::_('sel_sol_res') as $v ){ 
            $_ .= _doc::ima($esq,'sel_sol_res',$v,['eti'=>"div",'res'=>$v['ide'],'class'=>$ocu['res'],'title'=> _doc_dat::val('ver',"{$esq}.res_flu",$v)]);
          }          
          foreach( _hol::_('sel_sol_pla') as $v ){
            $_ .= _doc::ima($esq,'sol_pla',$v,['eti'=>"div",'pla'=>$v['ide'],'class'=>$ocu['pla'],'title'=> _doc_dat::val('ver',"{$esq}.sol_pla",$v)]);
          }
          foreach( _hol::_('sel_cro_ele') as $v ){ $_ .= "
            <div ele='{$v['ide']}' class='{$ocu['cla']}' title='"._doc_dat::val('ver',"{$esq}.sel_cro_ele",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cel') as $v ){ $_ .= "
            <div cel='{$v['ide']}' class='{$ocu['cel']}' title='"._doc_dat::val('ver',"{$esq}.sol_cel",$v)."'></div>";
          }
          foreach( _hol::_('sel_sol_cir') as $v ){ $_ .= "
            <div cir='{$v['ide']}' class='{$ocu['cir']}' title='"._doc_dat::val('ver',"{$esq}.sol_cir",$v)."'></div>";
          }
          foreach( _hol::_('sel') as $_sel ){ 
            $e = $ele['pos'];
            _ele::cla($e,"pos{$ocu['sel']}"); 
            $e['sel'] = $_sel->ide; $_ .= "
            <div"._htm::atr($e).">
              "._doc::ima($esq,'sel_cod',$_sel)."
            </div>";
          }
          $_ .= " 
        </div>";
        break;                
      }
      return $_;
    }
    // secciones: onda encantada + castillo => fondos + pulsares + orbitales
    static function sec( string $tip, mixed $dat, array $ope=[], array $ele=[] ) : string {
      global $_hol; $esq = 'hol';
      $_ = "";
      $_tip = explode('_',$tip);
      $_tab = _api::tab('hol',$_tip[0]);
      $orb_ocu = !empty($ope['sec']['orb']) ? '' : 'dis-ocu';
      $col_ocu = !empty($ope['sec']['col']) ? '' : ' fon-0';
      // pulsares
      if( in_array($_tip[0],['ton','cas']) ){

        $pul = ['dim'=>'','mat'=>'','sim'=>''];

        if( ( is_array($dat) && isset($dat['kin']->nav_ond_dia) ) || ( is_object($dat) && isset($dat->ide) ) ){

          $_ton = _hol::_('ton', is_object($dat) ? intval($dat->ide) : intval($dat['kin']->nav_ond_dia) );

          // por posicion
          foreach( $pul as $i=>$v ){

            if( !empty($ope['pos']["pul_{$i}"]) ){
              $pul[$i] = _doc::ima($esq,"ton_pul_[$i]", $_ton["pul_{$i}"], ['class'=>'fon'] ); 
            }
          }
        }
      }
      switch( $_tip[0] ){
      // onda encantada
      case 'ton':
        // pulsares
        foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
          <div"._htm::atr($_tab['ond'],[ 'pul'=>$ide ]).">
            {$pul[$ide]}
          </div>";
        }
        break;
      // castillo del destino
      case 'cas':
        // 1-orbitales
        for( $i=1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun(['class'=>$orb_ocu ],[ $_tab['orb'], $_tab["orb-{$i}"] ])).">
          </div>";
        }
        // 2-fondos: por color
        for( $i=1; $i<=4; $i++ ){ $_ .= "
          <div"._htm::atr(_ele::jun($_tab['fon'],[ $_tab["ond-{$i}"], [ 'class'=>"fon_col-4-{$i}{$col_ocu}" ] ])).">
          </div>";
        }
        // pulsares
        for( $i=1; $i<=4; $i++ ){
          foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
            <div"._htm::atr(_ele::jun($_tab['ond'],[ [ 'data-pul'=>$ide ] , $_tab["ond-{$i}"] ])).">
              {$pul[$ide]}
            </div>";
          }
        }
        break;      
      }
      return $_;
    }
    // posiciones: datos + titulos + contenido[ ima, num, tex]
    static function pos( string $ide, mixed $val, array &$ope=[], array $ele=[], $dat=FALSE ) : string {
      global $_hol; $esq = 'hol';
      $est = explode('_',$ide)[0];
      
      // recibo objeto o identificador
      $val_ide = $val;
      if( is_object($val) ){
        $_dat = $val;
        $val_ide = intval($_dat->ide);
      }
      else{
        $_dat = _hol::_($est,$val);
      }
      // operadores    
      $_val['sec_par'] = !empty($ope['sec']['par']) ? 'sec_par' : FALSE;    
      $_val['pos_dep'] = !empty($ope['sec']['pos_dep']);
      $_val['pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;

      //////////////////////////////////////////////////////////////////////////
      // cargo datos ///////////////////////////////////////////////////////////

        $e = isset($ele['pos']) ? $ele['pos'] : [];      
        // por acumulados
        if( isset($ope['dat']) ){

          foreach( $ope['dat'] as $pos => $_ref ){

            if( isset($_ref["{$esq}-{$est}"]) && intval($_ref["{$esq}-{$est}"]) == $val_ide ){

              foreach( $_ref as $ref => $ref_dat ){

                $e["{$ref}"] = $ref_dat;
              }            
              break;
            }
          }
        }
        // por dependencias estructura
        else{
          $dat_opc = _api::dat_est($esq,$est)->ope;
          if( isset($dat_opc->est) ){

            foreach( $dat_opc->est as $atr => $ref ){
    
              if( empty($e["{$esq}-{$ref}"]) ){
    
                $e["{$esq}-{$ref}"] = $_dat->$atr;
              }        
            }
          }// pos posicion
          elseif( empty($e["{$esq}-{$est}"]) ){    
            $e["{$esq}-{$est}"] = $_dat->ide;
          }
        }    
      //////////////////////////////////////////////////////////////////////////
      // posiciones del tablero principal //////////////////////////////////////    

        $agr = "";    
        // omito dependencias
        if( !$_val['pos_dep'] ){

          $agr = "pos";

          if( $_val['sec_par'] ){ 
            $agr .= !empty($agr) ? ' ': '';
            $agr .= $_val['sec_par']; 
            $par_ima = !empty($_val['pos_ima']) ? $_val['pos_ima'] : "{$esq}.{$est}.ide";
          }

          if( $dat ){

            if( is_array($dat) && isset($dat[$est]) ){
              $dat_ide = is_object($dat[$est]) ? $dat[$est]->ide : $dat[$est];
            }
            elseif( is_object($dat) && isset($dat->ide) ){
              $dat_ide = $dat->ide;
            }  
            if( isset($dat_ide) && $_dat->ide == $dat_ide ){
              $agr .= !empty($agr) ? ' ': ''; 
              $agr .= '_val-pos _val-pos-bor';
            }
          }
        }
      //////////////////////////////////////////////////////////////////////////    
      // armo titulos //////////////////////////////////////////////////////////

        $pos_tit = [];

        if( isset($e['api-fec']) ){
          $pos_tit []= "Calendario: {$e['api-fec']}";
        }

        if( isset($e['hol-kin']) ){
          $_kin = _hol::_('kin',$e['hol-kin']);
          $pos_tit []= _doc_dat::val('ver',"{$esq}.kin",$_kin);
        }

        if( isset($e['hol-sel']) ){
          $pos_tit []= _doc_dat::val('ver',"{$esq}.sel",$e['hol-sel']);
        }

        if( isset($e['hol-ton']) ){
          $pos_tit []= _doc_dat::val('ver',"{$esq}.ton",$e['hol-ton']);
        }

        if( isset($e['hol-psi']) ){
          $_psi = _hol::_('psi',$e['hol-psi']);
          $pos_tit []= _doc_dat::val('ver',"{$esq}.psi",$_psi);
        }

        if( isset($e['hol-rad']) ){
          $pos_tit []= _doc_dat::val('ver',"{$esq}.rad",$e['hol-rad']);
        }

        $e['title'] = implode("\n\n",$pos_tit);

        // clases adicionales
        if( !empty($agr) ){ _ele::cla($e,$agr,'ini'); }

      //////////////////////////////////////////////////////////////////////////
      // Contenido html ////////////////////////////////////////////////////////

        $htm = ""; 
        // por patrones: posicion por dependencia
        if( !empty($_dat) && !!$_val['sec_par'] ){

          $ele_sec = $e;

          if( isset($ele_sec['class']) ){
            unset($ele_sec['class']);
          }
          if( isset($ele_sec['style']) ){ 
            unset($ele_sec['style']);
          }
          
          // $ope['sec']['par'] = $ope['sec']['par'] - 1;

          $htm = _hol_tab::ver("{$est}_par",$_dat,[
            'sec'=>[ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal ( [pos].pos )
            'pos'=>[ 'ima'=>isset($par_ima) ? $par_ima : "hol.{$est}.ide" ]
          ],[
            'sec'=>$ele_sec
          ]);

        }// genero posicion
        elseif( !empty($_dat) ){
          
          foreach( ['ima','num','tex','fec'] as $tip ){

            if( !empty($ope['pos'][$tip]) ){                        
              $ide = _var::ide($ope['pos'][$tip]);
              $htm .= _doc_dat::ver($tip, $ope['pos'][$tip], $e["{$ide['esq']}-{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
            }
          }
        }
        // agrego posicion automatica-incremental
        if( !$_val['pos_dep'] ){

          if( !isset($e['pos']) ){
            if( empty($ope['_tab_pos']) ){
              $ope['_tab_pos'] = 0;
            }
            $ope['_tab_pos']++;
            $e['pos'] = $ope['_tab_pos'];
          }
        }
      //////////////////////////////////////////////////////////////////////////
      // devuelvo posicion /////////////////////////////////////////////////////
      
      $pos_eti = isset($ope['eti']) ? $ope['eti'] : 'div';

      return "
      <{$pos_eti}"._htm::atr($e).">
        {$htm}
      </{$pos_eti}>";
    }  
  }