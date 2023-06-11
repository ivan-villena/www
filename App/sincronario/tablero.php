
<article>
  <?php

  // Proceso Tableros
  $ope_atr = [
    'kin'=>[
      'tzolkin'=>'tzo',
      'nave'=>'nav',
      'castillo'=>'nav_cas',
      'onda'=>'nav_ond',
      'armonicas'=>'arm',
      'trayectoria'=>'arm_tra',
      'celula'=>'arm_cel',
      'cromaticas'=>'cro',
      'estacion'=>'cro_est',
      'elemento'=>'cro_ele'
    ],
    'psi'=>[
      'banco-psi'=>'psi',
      'anillo_solar'=>'sol',
      'giro_lunar'=>'lun',
      'heptadas'=>'hep',
      'estacion_solar'=>'est',
      'heptada_semanal'=>'hep'
    ]
  ];
  
  if( !( $tab_tip = array_key_exists($Uri->art,$ope_atr['kin']) ? 'kin' : ( array_key_exists($Uri->art,$ope_atr['psi']) ? 'psi' : FALSE ) ) ){

    $Doc->Err []= "No existe el Tablero Seleccionado: {$Uri->art}...";

  }
  else{

    // imrpimo Diario
    $Doc->Cab['ini']['dia'] = [
      'ico'=>"fec_val", 
      'tip'=>"pan", 
      'nom'=>"Posición Diaria", 
      'nav'=>[ 'eti'=>"article" ], 
      'htm'=>"

      <section class='mar_aba-1'>

        ".Sincronario::dat('fec',$Sincronario->Val,[ 'eje'=>"\$Sincronario.diario" ])."

        <div class='mar-2 tex_ali-cen'>

          ".Doc_Dat::inf('hol.kin',$Sincronario->Val['kin'],[ 'opc'=>["nom"], 'det'=>"des" ])."

        </div>

      </section>

      ".Doc_Ope::nav('tex',[
        'kin' => [ 
          'ide'=>"kin", 
          'ico'=>"", 
          'nom'=>"Sincrónico", 
          'des'=>"", 
          'htm'=>Doc_Dat::pos("hol","kin",[ 
            'kin'=>$_kin = Dat::_('hol.kin',$Sincronario->Val['kin']),
            'sel'=>Dat::_('hol.sel',$_kin->arm_tra_dia),
            'ton'=>Dat::_('hol.ton',$_kin->nav_ond_dia)
          ])
        ],
        'psi' => [ 
          'ide'=>"psi", 
          'ico'=>"", 
          'nom'=>"Cíclico", 
          'des'=>"", 
          'htm'=>Doc_Dat::pos("hol","psi",[ 
            'psi'=>$_psi = Dat::_('hol.psi',$Sincronario->Val['psi']),
            'est'=>Dat::_('hol.psi_hep_est',$_psi->hep_est),
            'lun'=>Dat::_('hol.psi_ani_lun',$_psi->ani_lun),
            'hep'=>Dat::_('hol.psi_hep_pla',$_psi->hep_pla)
          ])
        ]      
      ],[ 
        'sel' => "kin" 
      ])
    ];    

    // identificador del tablero
    $tab_cod = $ope_atr[$tab_tip][$Uri->art];
    
    // operadores del tablero
    $tab_ide = "hol.{$tab_tip}";
    
    if( !( $tab_var =  Dat::est('hol',"{$tab_tip}_{$tab_cod}",'tab') ) ) $tab_var = [];
    
    // inicializo operadores
    $tab_var['val'] = [];            

    // fecha => muestro listado por ciclos
    if( !empty( $Sincronario->Val['fec'] ) ){
      
      // joins
      $tab_var['est'] = [ 'var'=>[ 'fec' ], 'hol'=>array_keys($ope_atr) ];
      
      // cargo datos
      $tab_var['dat'] = Sincronario::val_dat( $tab_tip, $Sincronario->Val );      

      // activo acumulados
      $tab_var['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
      
      // agrego opciones
      if( !empty($tab_var['opc']) ) $tab_var['val']['acu']['opc'] = 1;

      // valor seleccionado
      $tab_var['val']['pos'] = $Sincronario->Val;
    }

    // 1- imprimo operadores del tablero

    // agrego sumatorias por aplicacion
    $agr = [];
    if( isset($tab_var['val']['pos']['kin']) ){ $tab_var['htm']['val'] = "
      <form class='ide-sum'>

        <fieldset class='ope_inf -ren' data-esq='hol' data-est='kin'>
          <legend>Sumatorias del Kin</legend>

          ".Doc_Dat::val_sum('hol.kin',$tab_var['val']['pos']['kin'])."

        </fieldset>          
      </form>";
    }

    foreach( ( $ope = Obj::nom( Doc_Dat::$Tab['ope'],'ver',['ver','opc','val']) ) as $ope_ide => $ope_tab ){ 

      if( !empty( $htm = Doc_Dat::tab_ope($ope_ide, $tab_ide, $tab_var) ) ){

        $Doc->Cab['ini'][$ope_ide] = [ 
          'ico'=>$ope_tab['ico'], 
          'tip'=>"pan", 
          'nom'=>$ope_tab['nom'], 
          'nav'=>[ 'eti'=>"article" ], 
          'htm'=>$htm
        ];
      }
    }

    // 2-imprimo operador de lista
    $lis_var = Dat::est("hol",$tab_tip,'lis');

    $lis_var['val'] = $tab_var['val'];
    
    // - cargo estructuras y datos
    if( isset($tab_var['est']) ){

      // Copio datos del tablero
      $lis_var['dat'] = $tab_var['dat'];

      // busco operadores de lista por : esquema_estructura
      $lis_var['est'] = [];
      foreach( $tab_var['est'] as $esq_ide => $esq_lis ){

        $lis_var['est'][$esq_ide] = [];
        foreach( $esq_lis as $est_ide ){

          $lis_var['est'][$esq_ide][$est_ide] = [];
          
          if( $est_ope = Dat::est($esq_ide,$est_ide,'lis') ){

            $lis_var['est'][$esq_ide][$est_ide] = $est_ope;
          }
        }
      }
    }    
    // - pido listado del tablero
    $ope = Doc_Dat::$Tab['ope']['lis'];
    $Doc->Cab['ini']['lis'] = [ 
      'ico'=>$ope['ico'], 
      'tip'=>"win", 
      'nom'=>$ope['nom'], 
      'htm'=>Doc_Dat::tab_ope('lis',"hol.{$tab_tip}",$lis_var) 
    ];

    // 3- imprimo tablero en página principal
    echo 
    Sincronario::dat_tab($tab_tip, $tab_cod, $tab_var, [
      'pos'=>[ 'onclick'=>"Doc_Dat.tab_val('mar',this);" ],
      'ima'=>[ 'onclick'=>FALSE ]
    ]);
  }
  
  // cargo todos los datos utilizados por esquema
  $est_fec = [];
  foreach( array_keys( Dat::$Est['var'] ) as $dat_ide ){

    if( preg_match("/^fec_/",$dat_ide) ) $est_fec[] = $dat_ide;

  }
  $Doc->Est['hol'] = array_keys( Dat::$Est['hol'] );
  $Doc->Est['var'] = $est_fec;

  // codigo inicial
  $Doc->Eje .= '

    // cargo datos de fecha
    $Sincronario.val = '.Obj::val_cod( $Sincronario->Val ).';';
  ;  
  ?>
  
</article>