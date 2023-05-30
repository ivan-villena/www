
<article>
  <?php

  // imrpimo Diario
  $Doc->Cab['ini']['dia'] = [
    'ico'=>"fec_val", 
    'tip'=>"pan", 
    'nom'=>"Posición Diaria", 
    'nav'=>[ 'eti'=>"article" ], 
    'htm'=>"

    <section class='mar_aba-1'>

      ".Sincronario::dat('fec',$Hol->val,[ 'eje'=>"diario" ])."

      <div class='mar-2 tex_ali-cen'>

        ".Doc_Dat::inf('hol','kin',$Hol->val['kin'],[ 'opc'=>["nom"], 'det'=>"des" ])."

      </div>

    </section>

    ".Doc_Ope::nav('tex',[
      'kin' => [ 
        'ide'=>"kin", 
        'ico'=>"", 
        'nom'=>"Sincrónico", 
        'des'=>"", 
        'htm'=>Doc_Dat::pos("hol","kin",[ 
          'kin'=>$_kin = Dat::_('hol.kin',$Hol->val['kin']),
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
          'psi'=>$_psi = Dat::_('hol.psi',$Hol->val['psi']),
          'est'=>Dat::_('hol.psi_hep_est',$_psi->hep_est),
          'lun'=>Dat::_('hol.psi_ani_lun',$_psi->ani_lun),
          'hep'=>Dat::_('hol.psi_hep_pla',$_psi->hep_pla)
        ])
      ]      
    ],[ 
      'sel' => "kin" 
    ])
  ];

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
      'anillo_solar'=>'',
      'giro_lunar'=>'',
      'heptadas'=>'hep',
      'estacion_solar'=>'est',
      'heptagono_semanal'=>''
    ]
  ];

  $tab_tip = array_key_exists($Uri->art,$ope_atr['kin']) ? 'kin' : ( array_key_exists($Uri->art,$ope_atr['psi']) ? 'psi' : FALSE );
  
  if( !$tab_tip ){

    echo Doc_Ope::tex([ 'tip'=>"err", 'tex'=>"No existe el Tablero Seleccionado: {$Uri->art}..." ]);

  }
  else{

    $tab_cod = $ope_atr[$tab_tip][$Uri->art];

    // genero datos
    $Hol->dat = Sincronario::dat_ope($tab_tip, $Hol->val);
    
    // operadores del tablero
    $tab_ide = "hol.{$tab_tip}";
    
    if( !( $tab_var =  Dat::est('hol',"{$tab_tip}_{$tab_cod}",'tab') ) ) $tab_var = [];
    
    // inicializo operadores
    $tab_var['ope'] = [];            

    // fecha => muestro listado por ciclos
    if( !empty( $Hol->val['fec'] ) ){
      
      // joins
      $tab_var['est'] = [ 
        'sis'=>[ 'fec' ], 
        'hol'=>array_keys($ope_atr) 
      ];
      
      // cargo datos
      $tab_var['dat'] = $Hol->dat;      

      // activo acumulados
      $tab_var['ope']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
      
      // agrego opciones
      if( !empty($tab_var['opc']) ) $tab_var['ope']['acu']['opc'] = 1;

      // valor seleccionado
      $tab_var['ope']['pos'] = $Hol->val;
    }

    // 1- imprimo operadores del tablero

    // agrego sumatorias por aplicacion
    $agr = [];
    if( isset($tab_var['ope']['pos']['kin']) ){ $tab_var['htm']['ope'] = "
      <form class='ide-sum'>

        <fieldset class='ope_inf -ren' data-esq='hol' data-est='kin'>
          <legend>Sumatorias del Kin</legend>

          ".Doc_Dat::ope_sum('hol.kin',$tab_var['ope']['pos']['kin'])."

        </fieldset>          
      </form>";
    }

    foreach( ( $ope = Obj::nom( Doc_Dat::$Tab['ope'],'ver',['ver','opc','ope']) ) as $ope_ide => $ope_tab ){ 

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

    $lis_var['ope'] = $tab_var['ope'];
    
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
    Sincronario::tab($tab_tip, $tab_cod, $tab_var, [
      'pos'=>[ 'onclick'=>"Doc_Dat.tab_ope('mar',this);" ],
      'ima'=>[ 'onclick'=>FALSE ]
    ]);
  }
  
  // cargo tutorial  
  ob_start(); ?>
    <!-- Introducción --> 
    <section>
      <h3>Introducción</h3>          

      <p>Las posiciones del tablero se arman generando ciclos de tiempo según una fecha seleccionada<c>.</c> La primer fecha que se muestra es la del día<c>,</c> pero puedes cambiarla desde la seccion Diario<c>.</c></p>

      <p>Cada elemento del tablero contiene datos sobre los <n>3</n> principales ciclos de tiempo<c>,</c> y sus correspondientes cuentas<c>:</c></p>

      <ul>
        <li>El Kin <c>(</c>Órden Sincrónico de <n>260</n> días<c>)</c><c>:</c> Número de Kin<c>,</c> Tono Galáctico y Sello Solar</li>
        <li>El Psi<c>-</c>Cronos <c>(</c>Órden Cíclico de <n>364<c>+</c>1</n> días<c>)</c><c>:</c> Número del Día Anual<c>,</c> Día Lunar y Plasma</li>
        <li>La Fecha <c>(</c>Calendario Gregoriano de <n>365</n> días<c>)</c><c>:</c> El año, el mes y el día</li>
      </ul>

      <p>Con estos datos se cargan los componentes de los distintos operadores<c>,</c> de los que se trata a continuación<c>...</c></p>

    </section>

    <!-- Posicion Diaria -->
    <section>
      <h3>Diario</h3>
      <?= Doc_Val::ico('fec_val',['class'=>"mar_hor-1"]) ?>          

      <p>Desde esta sección podrás cambiar la fecha para la posición principal del tablero<c>,</c> y ver un detalle de cada código correspondiente<c>.</c></p>

      <p>También puedes <i>marcar</i> otras posiciones del tablero haciendo click directamente sobre ellas<c>.</c> Estas serán cargardas en la seccion de acumulados<c>.</c></p>

    </section>        

    <!-- Seleccion -->
    <section>
      <h3>Selección por Valores</h3>
      <?= Doc_Val::ico('dat_ver',['class'=>"mar_hor-1"]) ?>

      <p>Accede a esta sección para seleccionar múltiples posiciones y luego comparar sus datos<c>.</c> Puedes aplicar criterios de selección por estructuras de datos<c>,</c> fechas<c>,</c> o posiciones<c>.</c></p>

      <p>Si buscas por estructuras de datos<c>,</c> deberás seleccionar primero el grupo de cuentas<c>(</c> Kin<c>,</c> Sello<c>,</c> Tono<c>,</c> Psi<c>-</c>Cronos<c>,</c> etc<c>.</c> <c>)</c><c>,</c> luego la cuenta <c>(</c> las ondas encantadas del kin<c>,</c> las familias de los sellos<c>,</c> los pulsares de los tonos<c>,</c> etc<c>.</c> <c>)</c> y por último el valor <c>(</c> la <n>3</n><c>°</c> onda encantada<c>,</c> la familia cardinal<c>,</c> el pulsar dimensional del tiempo<c>,</c> etc<c>.</c> <c>)</c><c>.</c></p>

      <p>También puedes buscar por fechas del calendario o el número de posiciones en el tablero<c>.</c> En estos casos deberás ingresar un valor inicial<c>,</c> y puedes limitar la sección con un valor final<c>.</c> También puedes indicar un incremento<c>,</c> es decir<c>,</c> un salto entre valores seleccionados<c>;</c> una cantidad límite de resultados<c>;</c> y si quieres que sean los primeros o los últimos de la lista<c>.</c></p>

      <p>Los valores seleccionados serán marcados con un recuadro en el tablero<c>.</c> También serán cargados en la sección de acumulados<c>,</c> serán tenidos en cuenta en los totales por estructura<c>,</c> y se mostrarán en el listado<c>.</c></p>

    </section>        

    <!-- Opciones -->
    <section>          
      <h3>Opciones del Tablero</h3>
      <?= Doc_Val::ico('opc_bin',['class'=>"mar_hor-1"]) ?>

      <p>Desde aquí puedes cambiar los colores de fondo<c>,</c> seleccionar el tipo de ficha y ver contenido numérico o textual para cada posición<c>.</c></p>

      <p>Según los atributos del tablero<c>,</c> definidos por su cuenta<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

      <p>Estas posiciones serán tenidas en cuenta en los acumulados del tablero y los elementos de la lista<c>.</c></p>

    </section>

    <!-- Operador -->
    <section>
      <h3>Datos y Valores</h3>
      <?= Doc_Val::ico('est',['class'=>"mar_hor-1"]) ?>

      <p>En la parte de acumulados se carga la posición principal por fecha<c>,</c> las posiciones marcadas<c>,</c> seleccionadas y las correspondientes a las opciones del tablero activadas<c>.</c></p>

      <p>En la sección de sumatorias del kin<c>,</c> según la cantidad de posiciones seleccionadas se realizará un cálculo automático que consiste en sumar todos los número de kines y mostrar su valor correspondiente<c>:</c></p>
      <ul>
        <li>El <b class="ide">Kin</b> refleja la sumatoria limitada por <n>260</n><c>:</c> si el total de kines es mayor que <n>260</n> <c>(</c>por ejemplo <n>465</n><c>)</c><c>,</c> se le resta <n>n</n> veces el <n>260</n> hasta obtener un número dentro del ciclo <c>(</c> <n>465 <c>-</c> 260 <c>=</c> 205</n><c>,</c> como este número es menor al límite de <n>260</n> no se le sigue restando ese valor<c>)</c></li>
        <li>El <b class="ide">Psi</b> refleja la sumatoria limitada por <n>365</n><c>:</c> en este caso al total se le va restando de a <n>365</n><c>.</c> Por ejemplo<c>:</c> si la suma de todos los kines da <n>750 <c>=></c> 750 <c>-</c> 365 <c>=</c> 385 <c>-</c> 365 <c>=</c> 20</n><c>.</c> Entonces se mostrará el psi<c>-</c>cronos número <n>20</n></li>
        <li>El <b class="ide">UMB</b> refleja la sumatoria limitada por <n>441</n><c>:</c> se realiza el mismo procedimiento para obtener un valor de <dfn>Unidad de Matriz Base</dfn> correspondiente a la aplicación del Sincronotrón</li>
      </ul>

      <p>También<c>,</c> encontrarás un listado de los códigos y cuentas incluidos en el armado de tablero<c>.</c> Para cada Código de cada Cuenta se muestra un total de elementos activos <c>(</c> por posición<c>,</c> marcas<c>,</c> seleccion y opciones <c>)</c> y su porcentaje sobre el total<c>.</c></p>

    </section>

    <!-- Listado -->
    <section>
      <h3>Listado</h3>
      <?= Doc_Val::ico('lis_ite',['class'=>"mar_hor-1"]) ?>

      <p>Se arma con los datos de todas las posiciones acumuladas de los distintos operadores<c>:</c> <b class="ide">Fecha del diario</b><c>,</c> <b class="ide">Marca directa</b><c>,</c> <b class="ide">Selección por Valores</b> y <b class="ide">Opciones del Tablero</b><c>.</c> Puedes elegir entre los distintos critrios de acumulación o ver todas las posiciones<c>.</c></p>

      <p>Los items de la lista contienen los mismos datos sobre los códigos y cuentas que cada posición en el tablero <c>(</c>la Fecha<c>,</c> el Kin y el Psi<c>)</c><c>.</c> Puedes ver y comparar cada atributo que está representado por su ficha correspondiente<c>,</c> al pasar el mouse sobre ellas aparecerá un pequeño contenido descriptivo<c>.</c></p>

      <p>También puedes filtrar las posiciones por las distintas estructuras de códigos y cuentas<c>,</c> de la misma manera que con la selección de posiciones en el Tablero<c>.</c> Tambien puedes aplicarlos por fecha siguiendo los mismos criterios <c>(</c> desde<c>,</c> hasta<c>,</c> cada<c>,</c> cuántos<c>,</c> al<c>...</c> <c>)</c></p>

      <p>Luego puedes seleccionar las columnas que representen a los datos que deseas ver<c>,</c> y revelar los titulos por Ciclos y Agrupaciones o analizar las lecturas disponibles para cada posición<c>.</c></p>

    </section>
    
  <?php  
  $Doc->Cab['med']['app_dat']['htm'] = ob_get_clean();     
  
  // cargo todos los datos utilizados por esquema
  $est_fec = [];
  foreach( array_keys( Dat::$Est['sis'] ) as $dat_ide ){

    if( preg_match("/^fec_/",$dat_ide) ){

      $est_fec[] = $dat_ide;
    }
  }
  $Doc->Est['hol'] = array_keys( Dat::$Est['hol'] );
  $Doc->Est['sis'] = $est_fec;
  ?>
  
</article>