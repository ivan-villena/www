
<?php

  $Cic = $Sincronario->Val;

  $Operadores = Doc_Dat::$Tab['ope'];
  
  $Tableros = [
    // Orden Cíclico
    'orden_sincronico'=>[
      'tzolkin'             =>'kin',
      'nave'                =>'kin_nav',
      'castillo'            =>'kin_nav_cas',
      'onda'                =>'kin_nav_ond',
      'giro_galactico'      =>'kin_arm',
      'trayectoria'         =>'kin_arm_tra',
      'celula'              =>'kin_arm_cel',
      'giro_espectral'      =>'kin_cro',
      'estacion'            =>'kin_cro_est',
      'elemento'            =>'kin_cro_ele',
      'holon_solar'         =>'sol',
      'holon_planetario'    =>'pla',
      'holon_humano'        =>'hum'
    ],    
    // Orden Sincrónico
    'orden_ciclico'=>[
      'banco_psi'         =>'psi_ban',
      'luna'              =>'psi_lun',
      'luna_diario'       =>'psi_lun_dia',
      'estacion'          =>'psi_est',
      'estacion_diario'   =>'psi_est_dia',
      'heptada'           =>'psi_hep',
      'holon_humano'      =>'hum'
    ],
    // Holones
    'holon'=>[
      'solar'         =>'sol',
      'planetario'    =>'pla',
      'humano'        =>'hum'
    ]
  ];

  // identificador del tablero
  $esq = "hol";
  $tab_ide = $Tableros[$Uri->cab][$Uri->art];  

  // imrpimo Diario
  $Doc->Cab['ini']['dia'] = [
    'ico'=>"fec_val", 
    'tip'=>"pan", 
    'nom'=>"Posición Diaria", 
    'nav'=>[ 'eti'=>"article" ], 
    'htm'=>"

    <section class='mar_aba-1'>

      ".Sincronario::dat_var('val',$Cic)."

      <div class='mar-2 tex_ali-cen'>

        ".Doc_Dat::inf('hol.kin',$Cic['kin'],[ 'opc'=>["nom"], 'det'=>"des" ])."

      </div>

    </section>

    ".Doc_Ope::nav('tex',[
      'kin' => [ 
        'ico'=>"fig", 
        'nom'=>"Sincrónico",
        'htm'=>Doc_Dat::pos($esq,"kin",[ 
          'kin'=>$_kin = Dat::_('hol.kin',$Cic['kin']),
          'sel'=>Dat::_('hol.sel',$_kin->arm_tra_dia),
          'ton'=>Dat::_('hol.ton',$_kin->nav_ond_dia)
        ])
      ],
      'psi' => [
        'ico'=>"fig_pun", 
        'nom'=>"Cíclico",
        'htm'=>Doc_Dat::pos($esq,"psi",[ 
          'psi'=>$_psi = Dat::_('hol.psi',$Cic['psi']),
          'est'=>Dat::_('hol.psi_est',$_psi->est),
          'lun'=>Dat::_('hol.psi_lun',$_psi->lun),
          'hep'=>Dat::_('hol.psi_hep',$_psi->hep)
        ])
      ]      
    ],[ 
      'sel' => "kin" 
    ])
  ];

  // cargo operadores de la base
  if( !( $tab_var =  Dat::est($esq,$tab_ide,'tab') ) ) $tab_var = [];  
  
  // inicializo operadores
  $tab_var['val'] = [];            

  // identificadores del ciclo: 365 - 260 - ...
  if( $Uri->cab == 'orden_sincronico' ){

    $est = "kin";
  }
  elseif( $Uri->cab == 'orden_ciclico' ){
    
    $est = "psi";
  }
  elseif( $Uri->cab == 'holon' ){

    $est = "kin";
  }
  else{

    $est = explode('_',$tab_ide)[0];
  }

  // cargo valores principales del ciclo diario
  $tab_val = [ 'fec'=>$Cic['fec'], 'kin'=>$Cic['kin'], 'psi'=>$Cic['psi'] ];

  // cargo joins por estructuras de datos
  $tab_var['est'] = [ 'var'=>[ "fec" ], 'hol'=>[ "kin", "psi" ] ];  
  
  // cargo datos del tablero por estructura principal
  $tab_var['dat'] = Sincronario::dat_tab_val( $est, $tab_val );

  // activo acumulados
  $tab_var['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1, 'atr'=>1 ];

  // valor seleccionado
  $tab_var['val']['pos'] = $tab_val;

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

    if( !empty( $htm = Doc_Dat::tab_ope($ope_ide, "{$esq}.{$tab_ide}", $tab_var, $ope_ele) ) ){

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
  $lis_ide = $est;

  $lis_var = Dat::est($esq,$lis_ide,'lis');

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
    'htm'=>Doc_Dat::tab_ope('lis',"hol.{$lis_ide}",$lis_var) 
  ];  

  // 3- imprimo tablero en página principal
?>

<article>
  <?= Sincronario::dat_tab( $tab_ide, $tab_var, [

    // cargo evento de click por posicion para marcas
    'pos'=>[ 'onclick'=>"Doc_Dat.tab_val('mar',this);" ],
    // anulo evento de ver informe
    'ima'=>[ 'onclick'=>FALSE ]
  ]);?>
</article>

<?php

  // cargo clases css y programa
  $Doc->Cla["Api/{$Uri->esq}"] []= "Operador";

  // cargo todos los datos utilizados por esquema
  $est_fec = [];
  foreach( array_keys( Dat::$Est['var'] ) as $ide ){

    if( preg_match("/^fec_/",$ide) ) $est_fec[] = $ide;
  }
  $Doc->Est['hol'] = array_keys( Dat::$Est['hol'] );
  $Doc->Est['var'] = $est_fec;  