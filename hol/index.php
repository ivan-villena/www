<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// cargo página ///////////////////////////////////////////////////////////////////////////////////////////////////
$_hol = new stdClass;

// Diario : Fecha del Sistema => valor por peticion : hol/$cab/$art/"ide=val"      
$_hol->val = hol::val( !empty($_SESSION['hol-val']) ? hol::cod($_SESSION['hol-val']) : date('Y/m/d') );

// - cargo identificadores
$_hol->ide = !empty($_uri->art) ? explode('_',$_uri->art) : [ "kin" ];

// proceso fecha
if( !empty($_uri->val) ){
      
  if( isset($_uri->val[1]) ){

    if( $_uri->val[0] == 'fec' || $_uri->val[0] == 'sin' ){
      $_hol->val = hol::val($_uri->val[1],$_uri->val[0]);
      // actualizo fecha del sistema
      $_SESSION['hol-val'] = $_uri->val[1];
    }// tomo 
    else{
      $_hol->val[ $_uri->val[0] ] = $_uri->val[1];
    }
  }
}
// imrpimo panel con operadores
$api_app->ope['dia'] = [ 'ico'=>"fec_val", 'tip'=>"pan", 'nom'=>"Diario", 'htm'=>"

  <section class='mar_aba-1'>

    ".hol::var('fec',$_hol->val,[ 'eje'=>"hol_app.dia" ])."

    <div class='mar-1'>
      ".dat::inf('hol','kin',$_hol->val['kin'],['opc'=>"nom",'cit'=>"des"])."
    </div>

  </section>

  ".doc::nav('bar',[
    'kin' => [ 'ide'=>"kin", 'ico'=>"", 'nom'=>"Sincrónico", 'des'=>"", 'htm'=>dat::lis_pos("hol","kin",[ 
      'kin'=>$_kin = hol::_('kin',$_hol->val['kin']),
      'sel'=>hol::_('sel',$_kin->arm_tra_dia),
      'ton'=>hol::_('ton',$_kin->nav_ond_dia)
    ])],
    'psi' => [ 'ide'=>"psi", 'ico'=>"", 'nom'=>"Cíclico", 'des'=>"", 'htm'=>dat::lis_pos("hol","psi",[ 
      'psi'=>$_psi = hol::_('psi',$_hol->val['psi']),
      'est'=>hol::_('est',$_psi->est),
      'lun'=>hol::_('lun',$_psi->lun),
      'hep'=>hol::_('hep',$_psi->hep)
    ])]      
  ],[ 'sel' => "kin" ])
];

// inicio/s : 
if( empty($_uri->cab) || empty($_uri->art) ){

  // cargo articulo
  ob_start(); ?>

  <?=doc::tex('adv', "Este sitio aún se está en construcción, puede haber contenido incompleto, errores o fallas.")?>

  <img src="./img/hol/fon/sis.png" alt="Entrada" style="max-width:45rem;" title="Entrada al Sincronario... Accede al menú en la barra superior, o busca ayuda con el ícono que tiene el signo de pregunta ( ? )">
  
  <?php
  $api_app->htm['sec'] = ob_get_clean();

  // cargo tutorial:
  ob_start(); ?>
    <!-- Diario -->
    <section>
      <h3>Fecha del Sistema</h3>
      <?= fig::ico('fec_val',['class'=>"mar_hor-1"]) ?>

      <p>Desde este panel podrás buscar fechas en el Calendario Gregoriano o en El sincronario<c>.</c></p>

      <p>La página se inicia con la fecha corresponde al día actual<c>,</c> pero puedes cambiarla en cualquier momento<c>.</c></p>

      <p>Según la fecha seleccionada podrás ver los datos correspondiente a<c>:</c></p>

      <ul>
        <li>El <b class="ide">Orden Sincrónico</b> de <n>260</n> días</li>
        <li>El <b class="ide">Oredn Cíclico</b> de <n>365</n> días</li>            
      </ul>

      <p>La información se presenta en forma listado con fichas<c>,</c> que contienen los datos propios de la cuenta que representa<c>,</c> y sus valores posicionales correspondientes a su ciclo de tiempo<c>.</c></p>

    </section>      
    <!-- Bibliografía -->
    <section>

      <h3>La bibliografía</h3>

      <div class="val jus-cen">
        <?= fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
        <c>-></c>
        <?= fig::ico('tex_lib',['class'=>"mar_hor-1"]) ?>
      </div>

      <p>Aquí podrás encontrar la mayoría de los libros en los cuales se basa la teoría del Sincronario<c>:</c> <q>La ley del Tiempo</q><c>.</c> Esta fué desarrollada por <a href="https://es.wikipedia.org/wiki/Jos%C3%A9_Arg%C3%BCelles" target="_blank">José Argüelles</a> quien organizó una fundación con el mismo nombre <c>(</c><a href="http://www.lawoftime.org" target="_blank">The Law of Time</a><c>)</c><c>.</c> Todos sus libros y materiales se pueden descargar gratuitamente desde <a href="https://13lunas.net/mapa.htm#biblioteca" target="_blank">La Biblioteca de <n>13</n> Lunas</a><c>.</c></p>

      <p>En este sitio se adaptó el formato de texto de cada libro para una página web<c>,</c> se agregaron los íconos correspondientes a cada símbolo e imágenes que amplían su contenido visualamente<c>.</c> También se muestran en orden cronológico<c>,</c> ya que este conocimiento es incremental y los temas se entrelazan y completan en cada publicación<c>.</c></p>

      <p>En la página de cada libro hay un índice en el panel izquierdo<c>,</c> que puedes ocultar o mostrar haciendo click en el botón Correspondiente<c>.</c> Los items del índice que figuran en el libro son los mismos<c>,</c> pero se agregaron nuevos para segmentar la información y poder acceder desde enlaces<c>.</c></p>

    </section>
    <!-- Códigos -->
    <section>
      <h3>Códigos y Cuentas</h3>

      <div class="val jus-cen">
        <?= fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
        <c>-></c>
        <?= fig::ico('num_cod',['class'=>"mar_hor-1"]) ?>
      </div>

      <p>En esta sección podrás encontrar datos que van apareciendo en los distintos libros y están relacionados a cada código y cuenta<c>,</c> junto con sus respectivas agrupaciones y subciclos<c>.</c></p>

      <p>Haciendo referencia a la fuente<c>,</c> se describen brevemente para introducir al lector en sus conceptos y bridarle acceso directo al material donde puede encontrarlo<c>.</c> A partir de su comprensión se pueden realizar lecturas y relaciones entre distintas posiciones<c>,</c> fechas o firmas galácticas<c>.</c></p>

    </section>
    <!-- Módulos -->
    <section>
      <h3>Los Módulos</h3>

      <div class="val jus-cen">
        <?= fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
        <c>-></c>
        <?= fig::ico('tab',['class'=>"mar_hor-1"]) ?>
      </div>
      
      <p>Desde el menú principal puedes acceder a un listado de tableros que representan las cuentas principales del sincronario<c>,</c> a estos los llamaremos módulos<c>.</c></p>

      <p>Para cada módulo se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por Ej<c>:</c> el <a href="<?=SYS_NAV."hol/tab/kin-tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

      <p>Desde allí podrás cambiar la fecha y acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos de las posiciones<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones seleccionadas para comparar sus características y ubicaciones<c>.</c></p>
      
    </section>
  <?php
  $api_app->htm['dat'] = ob_get_clean();

}
// por articulo 
else{
  ob_start();
  // operador : modulos
  if( $_uri->cab == 'ope' ){
    // genero datos
    $_hol->dat = hol::dat($_hol->ide[0], $_hol->val);
    // cargo todos los datos utilizados por esquema
    $api_app->rec['dat']['hol'] = [];

    $_val = $_hol->val;
    $_ide = $_hol->ide;
    // valido tablero      
    if( !isset($_ide[1]) ){
      echo doc::tex('err',"No existe el tablero '$_uri->art'");
      return;
    }
    // operadores del tablero
    $tab_ide = "hol.{$_ide[0]}";  
    if( !( $tab_ope =  dat::ope('hol',$_uri->art,'tab') ) ) $tab_ope = [];    
    // inicializo valores
    $tab_ope['val'] = [];
    $ope_atr = ['kin','psi'];
    // fecha => muestro listado por ciclos
    if( !empty( $_val['fec'] ) ){
      // joins
      $tab_ope['est'] = [ 
        'fec'=>["dat"],
        'hol'=>$ope_atr
      ];
      // cargo datos
      $tab_ope['dat'] = $_hol->dat;
      // kin + psi : activo acumulados
      if( in_array($_ide[0],$ope_atr) ){
        $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
        // agrego opciones
        if( !empty($tab_ope['opc']) ) $tab_ope['val']['acu']['opc'] = 1;
      }
      // valor seleccionado
      $tab_ope['val']['pos'] = $_val;
    }
    // imprimo operadores del tablero
    $ope = obj::nom(tab::$OPE,'ver',['ver','opc','val']);
    foreach( $ope as $ope_ide => $ope_tab ){

      if( !empty( $htm = tab::ope($ope_ide, $tab_ide, $tab_ope) ) ){

        $api_app->ope[$ope_ide] = [ 'ico'=>$ope_tab['ico'], 'tip'=>"pan", 'nom'=>$ope_tab['nom'], 'htm'=>$htm];
      }
    }
    // imprimo operador de lista
    $ope = tab::$OPE['lis'];
    $api_app->ope['est'] = [ 'ico'=>$ope['ico'], 'tip'=>"win", 'nom'=>$ope['nom'], 'htm'=>tab::ope('lis',"hol.{$_ide[0]}",$tab_ope) ];
    // imprimo tablero en página principal
    echo "
    <article>
      ".hol::tab($_ide[0], $_ide[1], $tab_ope, [
        'pos'=>[ 'onclick'=>"tab.val('mar',this);" ], 
        'ima'=>[ 'onclick'=>FALSE ]
      ])."
    </article>";
    $api_app->htm['sec'] = ob_get_clean();
    
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
      <!-- Diario -->
      <section>
        <h3>Diario</h3>
        <?= fig::ico('fec_val',['class'=>"mar_hor-1"]) ?>          

        <p>Desde esta sección podrás cambiar la fecha para la posición principal del tablero<c>,</c> y ver un detalle de cada código correspondiente<c>.</c></p>

        <p>También puedes <i>marcar</i> otras posiciones del tablero haciendo click directamente sobre ellas<c>.</c> Estas serán cargardas en la seccion de acumulados<c>.</c></p>

      </section>        
      <!-- Seleccion -->
      <section>          
        <h3>Selección por Valores</h3>
        <?= fig::ico('dat_ver',['class'=>"mar_hor-1"]) ?>

        <p>Accede a esta sección para seleccionar múltiples posiciones y luego comparar sus datos<c>.</c> Puedes aplicar criterios de selección por estructuras de datos<c>,</c> fechas<c>,</c> o posiciones<c>.</c></p>

        <p>Si buscas por estructuras de datos<c>,</c> deberás seleccionar primero el grupo de cuentas<c>(</c> Kin<c>,</c> Sello<c>,</c> Tono<c>,</c> Psi<c>-</c>Cronos<c>,</c> etc<c>.</c> <c>)</c><c>,</c> luego la cuenta <c>(</c> las ondas encantadas del kin<c>,</c> las familias de los sellos<c>,</c> los pulsares de los tonos<c>,</c> etc<c>.</c> <c>)</c> y por último el valor <c>(</c> la <n>3</n><c>°</c> onda encantada<c>,</c> la familia cardinal<c>,</c> el pulsar dimensional del tiempo<c>,</c> etc<c>.</c> <c>)</c><c>.</c></p>

        <p>También puedes buscar por fechas del calendario o el número de posiciones en el tablero<c>.</c> En estos casos deberás ingresar un valor inicial<c>,</c> y puedes limitar la sección con un valor final<c>.</c> También puedes indicar un incremento<c>,</c> es decir<c>,</c> un salto entre valores seleccionados<c>;</c> una cantidad límite de resultados<c>;</c> y si quieres que sean los primeros o los últimos de la lista<c>.</c></p>

        <p>Los valores seleccionados serán marcados con un recuadro en el tablero<c>.</c> También serán cargados en la sección de acumulados<c>,</c> serán tenidos en cuenta en los totales por estructura<c>,</c> y se mostrarán en el listado<c>.</c></p>

      </section>        
      <!-- Opciones -->
      <section>          
        <h3>Opciones del Tablero</h3>
        <?= fig::ico('opc_bin',['class'=>"mar_hor-1"]) ?>

        <p>Desde aquí puedes cambiar los colores de fondo<c>,</c> seleccionar el tipo de ficha y ver contenido numérico o textual para cada posición<c>.</c></p>

        <p>Según los atributos del tablero<c>,</c> definidos por su cuenta<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

        <p>Estas posiciones serán tenidas en cuenta en los acumulados del tablero y los elementos de la lista<c>.</c></p>

      </section>
      <!-- Operador -->
      <section>
        <h3>Datos y Valores</h3>
        <?= fig::ico('est',['class'=>"mar_hor-1"]) ?>

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
        <?= fig::ico('lis_ite',['class'=>"mar_hor-1"]) ?>

        <p>Se arma con los datos de todas las posiciones acumuladas de los distintos operadores<c>:</c> <b class="ide">Fecha del diario</b><c>,</c> <b class="ide">Marca directa</b><c>,</c> <b class="ide">Selección por Valores</b> y <b class="ide">Opciones del Tablero</b><c>.</c> Puedes elegir entre los distintos critrios de acumulación o ver todas las posiciones<c>.</c></p>

        <p>Los items de la lista contienen los mismos datos sobre los códigos y cuentas que cada posición en el tablero <c>(</c>la Fecha<c>,</c> el Kin y el Psi<c>)</c><c>.</c> Puedes ver y comparar cada atributo que está representado por su ficha correspondiente<c>,</c> al pasar el mouse sobre ellas aparecerá un pequeño contenido descriptivo<c>.</c></p>

        <p>También puedes filtrar las posiciones por las distintas estructuras de códigos y cuentas<c>,</c> de la misma manera que con la selección de posiciones en el Tablero<c>.</c> Tambien puedes aplicarlos por fecha siguiendo los mismos criterios <c>(</c> desde<c>,</c> hasta<c>,</c> cada<c>,</c> cuántos<c>,</c> al<c>...</c> <c>)</c></p>

        <p>Luego puedes seleccionar las columnas que representen a los datos que deseas ver<c>,</c> y revelar los titulos por Ciclos y Agrupaciones o analizar las lecturas disponibles para cada posición<c>.</c></p>

      </section>        
    <?php
    $api_app->htm['dat'] = ob_get_clean();     
  }
  // contenido : bibliografía + articulos
  else{      
    // cargo indice, directorio y enlaces
    $_nav = $api_app->doc['nav'];
    $_dir = $api_app->uri_dir();
    $_bib = SYS_NAV."hol/bib/";
    
    // módulos: bibliografía
    require_once("./hol/bib.php");

    // busco archivos : html-php
    if( !empty( $rec = arc::ide($val = "./$_uri->esq/$_uri->cab/$_uri->art") ) ){

      include( $rec );
    }
    else{
      echo doc::tex('err',"No existe el archivo '$val'");
    }
  }
  // imprimo secciones
  $api_app->htm['sec'] = ob_get_clean();      
}

// recursos del documento
$api_app->rec['cla']['hol'] = [];
if( !empty($_uri->cab) ) $api_app->rec['cla']['hol'] []= $_uri->cab;

$api_app->rec['eje'] .= '

  var $_hol = { val : '.obj::val_cod( $_hol->val ).' };

  if( $api_app.uri.cab == "ope" ){

    $api_app.tab.cla = ( $api_app.tab.dep = $api_app.tab.lis.querySelector(`.pos.ope.sec_par`) ) 
      ? `.pos.ope.sec_par > .tab[class*="_par"] > .pos[class*="ide-"]`
      : `.pos.ope`
    ;
    
    // muestro diario
    /*
    let $bot_ini = $api_doc.bot.querySelector(`.ico.fec_val`);
    if( $bot_ini ) $bot_ini.click();
    */
  }';
