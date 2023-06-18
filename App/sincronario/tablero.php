
<?php

  $Operadores = Doc_Dat::$Tab['ope'];
  
  $Tableros = [
    'kin'=>[
      'tzolkin'             =>'tzo',
      'nave'                =>'nav',
      'castillo'            =>'nav_cas',
      'onda'                =>'nav_ond',
      'giro_galactico'      =>'arm',
      'trayectoria'         =>'arm_tra',
      'celula'              =>'arm_cel',
      'giro_espectral'      =>'cro',
      'estacion'            =>'cro_est',
      'elemento'            =>'cro_ele'
    ],
    'psi'=>[      
      'banco_psi'         =>'ban',
      'anillo'            =>'ani',
      'luna'              =>'lun',
      'luna_diario'       =>'lun_dia',
      'estacion'          =>'est',
      'estacion_diario'   =>'est_dia',
      'heptada'           =>'hep',
      'heptada_diario'    =>'hep_dia'
    ]
  ];

  // valido tablero seleccionado
  $esq = "hol";
  $est = $Uri->cab;
  $atr = $Uri->art;

  if( !isset($Tableros[$est]) || !isset($Tableros[$est][$atr]) ){

    $Doc->Err []= "No existe el Tablero Seleccionado: {$est}-{$atr}...";

    exit;
  }

  // imrpimo Diario
  $Doc->Cab['ini']['dia'] = [
    'ico'=>"fec_val", 
    'tip'=>"pan", 
    'nom'=>"Posición Diaria", 
    'nav'=>[ 'eti'=>"article" ], 
    'htm'=>"

    <section class='mar_aba-1'>

      ".Sincronario::dat('val',$Sincronario->Val)."

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
        'htm'=>Doc_Dat::pos($esq,"kin",[ 
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
        'htm'=>Doc_Dat::pos($esq,"psi",[ 
          'psi'=>$_psi = Dat::_('hol.psi',$Sincronario->Val['psi']),
          'est'=>Dat::_('hol.psi_est',$_psi->est),
          'lun'=>Dat::_('hol.psi_lun',$_psi->lun),
          'hep'=>Dat::_('hol.psi_hep',$_psi->hep)
        ])
      ]      
    ],[ 
      'sel' => "kin" 
    ])
  ];

  // identificador del tablero
  $atr = $Tableros[$est][$atr];
  
  // operadores del tablero
  $dat_ide = "{$esq}.{$est}";
  
  // cargo operadores de la base
  if( !( $tab_var =  Dat::est($esq,"{$est}_{$atr}",'tab') ) ) $tab_var = [];
  
  // inicializo operadores
  $tab_var['val'] = [];            

  // 0- fecha => muestro listado por ciclos

  // joins
  $tab_var['est'] = [ 'var'=>[ "fec" ], 'hol'=>array_keys($Tableros) ];
  
  // cargo datos
  $tab_var['dat'] = Sincronario::dat_val( $est, $Sincronario->Val );      

  // activo acumulados
  $tab_var['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1, 'atr'=>1 ];

  // valor seleccionado
  $tab_var['val']['pos'] = $Sincronario->Val;
  
  // 1- imprimo operadores del tablero  
  foreach( ( $ope = Obj::nom( $Operadores,'ver',[ 'ver', 'opc', 'val' ]) ) as $ope_ide => $ope_tab ){ 

    $ope_ele = [];
    
    // sumatorias del kin
    if( $ope_ide == 'val' ){
      
      $ope_ele['htm'] = "
      <form class='ide-sum'>
  
        <fieldset class='ope_inf -ren' data-esq='hol' data-est='kin'>
          <legend>Sumatorias del Kin</legend>
  
          ".Sincronario::dat_val_sum('hol.kin',$tab_var['val']['pos']['kin'])."
  
        </fieldset>
  
      </form>";
    }

    if( !empty( $htm = Doc_Dat::tab_ope($ope_ide, $dat_ide, $tab_var, $ope_ele) ) ){

      $Doc->Cab['ini'][$ope_ide] = [ 
        'ico'=>$ope_tab['ico'], 
        'tip'=>"pan", 
        'nom'=>$ope_tab['nom'], 
        'nav'=>[ 'eti'=>"article" ], 
        'htm'=>$htm
      ];
    }
  }
  unset($tab_var['htm-ope']);

  // 2-imprimo operador de lista
  $lis_var = Dat::est($esq,$est,'lis');

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
  $ope = $Operadores['lis'];
  $Doc->Cab['ini']['tab_lis'] = [ 
    'ico'=>$ope['ico'], 
    'tip'=>"win",
    'nom'=>$ope['nom'],
    'htm'=>Doc_Dat::tab_ope('lis',"hol.{$est}",$lis_var) 
  ];  

  // 3- imprimo tablero en página principal
?>

<article>
  <?= Sincronario::dat_tab($est, $atr, $tab_var, [

    // cargo evento de click por posicion para marcas
    'pos'=>[ 'onclick'=>"Doc_Dat.tab_val('mar',this);" ],
    // anulo evento de ver informe
    'ima'=>[ 'onclick'=>FALSE ]
  ]);?>
</article>

<?php

  // cargo clases css y programa
  $Doc->Cla["App/{$Uri->esq}"] []= "Tablero";

  // cargo todos los datos utilizados por esquema
  $est_fec = [];
  foreach( array_keys( Dat::$Est['var'] ) as $dat_ide ){

    if( preg_match("/^fec_/",$dat_ide) ) $est_fec[] = $dat_ide;
  }
  $Doc->Est['hol'] = array_keys( Dat::$Est['hol'] );
  $Doc->Est['var'] = $est_fec;

  // Cargo Programa
  $Doc->Eje['app'] = "{ Val : ".Obj::val_cod( $Sincronario->Val )." }";

  $Doc->Eje['ini'] = "";