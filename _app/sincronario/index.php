<?php

  if( !isset($App) ) exit;
    
  $_hol = new stdClass;

  // - cargo Diario: valor por peticion => { hol/$cab/$art/$ide=val }
  $_hol->val = Hol::val( !empty($_SESSION['hol-val']) ? Hol::val_cod($_SESSION['hol-val']) : date('Y/m/d') );

  // proceso fecha para: Tableros + Diario + Kin Planetario
  if( !empty($Uri->val) ){

    $uri_val = explode('=',$Uri->val);

    if( in_array($uri_val[0],[ 'fec', 'sin' ])  ){

      // proceso fecha del sincronario
      $_hol->val = Hol::val($uri_val[1],$uri_val[0]);
      
      // actualizo fecha del sistema
      $_SESSION['hol-val'] = $uri_val[1];

    }
    else{
      $_hol->val[ $uri_val[0] ] = $uri_val[1];
    }
  }
  
  //////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////
  // inicio/s : 
  if( empty($Uri->cab) || empty($Uri->art) ){

    // cargo articulo
    ob_start(); ?>

    <article>
      
      <section>
        <h2>Inicio</h2>
      
        <section class="inicio">

          <?=Doc::tex([ 'tip'=>"adv", 'tit'=>"¡Atención!", 
            'tex'=>[
              "Este sitio aún se está en construcción...", "Puede haber contenido incompleto, errores o faltas."
            ],
            'htm'=>[
              'eti'=>"div", 
              'class'=>"doc_ite jus-cen mar-1", 
              'htm'=>"Contacto<c>:</c> 
                ".Fig::ico('usu_mai',['eti'=>"a",
                  'href'=>"mailto:ivan.pieszko@gmail.com",
                  'class'=>"mar_hor-1",
                  'title'=>"Enviar un correo electrónico..."
                ])."
                ".Fig::ico('usu_tel',['eti'=>"a",
                  'href'=>"tel:+5491131037776",
                  'class'=>"mar_hor-1",
                  'title'=>"Enviar un mensaje al celular..."
                ])
            ]
          ])?>

          <?php
            // inicio de sesion
            if( empty($Usu->ide) ){
          ?>
            <button class="app-ses_ini" onclick="Doc.win('app-ses_ini')">Iniciar Sesión</button>
          <?php
            }
            else{
          ?>

          <?php
            }
          ?>

        </section>
        
      </section>

    </article>
    
    <?php
    $App->Doc['htm']['sec'] = ob_get_clean();

    // cargo tutorial:
    ob_start(); ?>
      <!-- Bibliografía -->
      <section>

        <h3>La bibliografía</h3>

        <div class="doc_val mar-aut">
          <?= Fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Fig::ico('doc_lib',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>Aquí podrás encontrar la mayoría de los libros en los cuales se basa la teoría del Sincronario<c>:</c> <q>La ley del Tiempo</q><c>.</c> Esta fué desarrollada por <a href="https://es.wikipedia.org/wiki/Jos%C3%A9_Arg%C3%BCelles" target="_blank">José Argüelles</a> quien organizó una fundación con el mismo nombre <c>(</c><a href="http://www.lawoftime.org" target="_blank">The Law of Time</a><c>)</c><c>.</c> Todos sus libros y materiales se pueden descargar gratuitamente desde <a href="https://13lunas.net/mapa.htm#biblioteca" target="_blank">La Biblioteca de <n>13</n> Lunas</a><c>.</c></p>

        <p>En este sitio se adaptó el formato de texto de cada libro para una página web<c>,</c> se agregaron los íconos correspondientes a cada símbolo e imágenes que amplían su contenido visualamente<c>.</c> También se muestran en orden cronológico<c>,</c> ya que este conocimiento es incremental y los temas se entrelazan y completan en cada publicación<c>.</c></p>

        <p>En la página de cada libro hay un índice en el panel izquierdo<c>,</c> que puedes ocultar o mostrar haciendo click en el botón Correspondiente<c>.</c> Los items del índice que figuran en el libro son los mismos<c>,</c> pero se agregaron nuevos para segmentar la información y poder acceder desde enlaces<c>.</c></p>

      </section>
      <!-- Apuntes -->
      <section>
        <h3>Los Apuntes</h3>

        <div class="doc_val mar-aut">
          <?= Fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Fig::ico('doc_inf',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>En esta sección podrás encontrar datos que van apareciendo en los distintos libros y están relacionados a cada código y cuenta<c>,</c> junto con sus respectivas agrupaciones y subciclos<c>.</c></p>

        <p>Los sistemas del Sincronario están basados en códigos y cuentas<c>:</c> Los <n>13</n> tonos galácticos crean el módulo de sincronización para las <n>13</n> lunas del giro solar y las <n>13</n> trayectorias armónicas del giro galáctico<c>.</c></p>

        <ul>
          <li>Cada Tono Galáctico se encuentra dentro de uno de los <n>4</n> Pulsares Dimensionales<c>,</c> relacionado por uno de los <n>5</n> Pulsares Matiz y se conecta con otro tono por una de las <n>7</n> Simetrías Inversas<c>.</c> A su vez<c>,</c> dentro de una Onda Encantada cumple uno de los <n>4</n> Mandatos de Acción<c>.</c></li>
          <li>Cada sello Solar pertecene a una de las <n>5</n> Familias Terrestres<c>,</c> a una de las <n>4</n> Razas Cósmicas<c>,</c> a uno de los <n>4</n> Clanes Galácticos y a una de las <n>5</n> Células del Tiempo<c>.</c> Por otro lado<c>,</c> el Sello se relaciona con uno de las <n>10</n> Órbitas Planetarias del Sistema Solar<c>,</c> establece uno de los <n>5</n> Centros Planetarios del Holon Terrestre y codifica uno de los <n>20</n> dedos del Holon Humano<c>.</c></li>
          <li>Cada uno de los <n>260</n> kines está compuesto por uno de los <n>13</n> tonos galácticos<c>,</c> y uno de los <n>20</n> sellos solares<c>.</c></li>
          <li>Cada día del año se encuentra en una de los <n>13</n> Giros Lunares y se asocia a uno de los <n>7</n> Plasma Radiales<c>.</c></li>
          <li>Cada Giro Lunar se divide en <n>4</n> semanas<c>/</c>héptadas de <n>7</n> días cada una<c>,</c> codificados por uno de los <n>7</n> Plasmas Radiales<c>.</c> Durante este ciclo se incluye el viaje del Guerrero por el Cubo de la Ley compuesto de <n>16</n> pasos<c>.</c> El resto de los <n>12</n> días se dividen en <n>2</n> caminatas de <n>6</n> días cada una<c>,</c> al inicio y al final de cada ciclo<c>.</c></li>
          <li>Cada Plasma Radial está compuesto por <n>2</n> de las <n>12</n> Líneas de Fuerza<c>,</c> las cuales se generan por la combinacion de dos de los <n>6</n> Tipos de Electricidad en la energía Cósmica<c>.</c> Por otro lado<c>,</c> a cada Plasma se lo relaciona con uno de los <n>7</n> chakras del Cuerpo Humano<c>,</c> y una de las <n>7</n> posiciones del Heptágono de la Mente<c>.</c></li>
          <li>Un Castillo Fractal está compuesto por <n>52</n> posiciones que se dividen en <n>4</n> ondas encantadas de <n>13</n> unidades cada una<c>.</c> Con el castillo se codifican las <n>4</n> estaciones espectrales del giro galáctico<c>,</c> las <n>4</n> estaciones cíclicas del giro solar<c>,</c> los <n>52</n> anillos solares del ciclo Nuevo Siario y los <n>52</n> años del sendero del destino para el kin planetario<c>.</c> A su vez<c>,</c> la nave del tiempo tierra está compuesta de <n>5</n> castillos para abarcar los <n>260</n> kines del giro galáctico<c>.</c></li>
        </ul>

        <p>Todos estos son ejemplos de las cuentas utilizadas para medir el tiempo con el concepto de Matriz Radial<c>.</c> Cada cuenta va del <n>1</n> al <n>n</n><c>,</c> siendo <n>n</n> el valor total que define la cuenta<c>.</c> De esta manera<c>:</c> los plasmas val del <n>1<c>-</c>7</n><c>,</c> los tonos del <n>1<c>-</c>13</n><c>,</c> los sellos del <n>1<c>-</c>20</n><c>,</c> las lunas del <n>1<c>-</c>28</n><c>,</c>etc<c>.</c></p>

      </section>
      <!-- Tableros -->
      <section>
        <h3>Los Tableros</h3>

        <div class="doc_val mar-aut">
          <?= Fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Fig::ico('dat_tab',['class'=>"mar_hor-1"]) ?>
        </div>
        
        <p>Desde el menú principal puedes acceder a un listado de tableros que representan las cuentas principales del sincronario<c>,</c> a estos los llamaremos módulos<c>.</c></p>

        <p>Para cada módulo se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por Ej<c>:</c> el <a href="<?=SYS_NAV."hol/kin/tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

        <p>Desde allí podrás cambiar la fecha y acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos de las posiciones<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones seleccionadas para comparar sus características y ubicaciones<c>.</c></p>
        
      </section>
      <!-- Diario -->
      <section>
        <h3>El Diario</h3>

        <div class="doc_val mar-aut">
          <?= Fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Fig::ico('fec',['class'=>"mar_hor-1"]) ?>
        </div>
        
        <p></p>

      </section>
      <!-- Kin Planetario -->
      <section>
        <h3>El Kin Planetario</h3>

        <div class="doc_val mar-aut">
          <?= Fig::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Fig::ico('usu',['class'=>"mar_hor-1"]) ?>
        </div>

        <p></p>

      </section>      
    <?php
    $App->Doc['cab']['med']['app_dat']['htm'] = ob_get_clean();

  }
  //////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////
  // por articulo 
  else{
    ob_start();
    //////////////////////////////////////////////////////////////////////////////////
    // Tableros //////////////////////////////////////////////////////////////////////
    if( $Uri->cab == 'tablero' ){

      // imrpimo Diario
      $App->Doc['cab']['ini']['dia'] = [
        'ico'=>"fec_val", 
        'tip'=>"pan", 
        'nom'=>"Posición Diaria", 
        'nav'=>[ 'eti'=>"article" ], 
        'htm'=>"

        <section class='mar_aba-1'>

          ".Hol::var('fec',$_hol->val,[ 'eje'=>"diario" ])."

          <div class='mar-2 tex_ali-cen'>

            ".Dat::inf('hol','kin',$_hol->val['kin'],[ 'opc'=>["nom"], 'det'=>"des" ])."

          </div>

        </section>

        ".Doc::nav('bar',[
          'kin' => [ 
            'ide'=>"kin", 
            'ico'=>"", 
            'nom'=>"Sincrónico", 
            'des'=>"", 
            'htm'=>Dat::inf_pos("hol","kin",[ 
              'kin'=>$_kin = Hol::_('kin',$_hol->val['kin']),
              'sel'=>Hol::_('sel',$_kin->arm_tra_dia),
              'ton'=>Hol::_('ton',$_kin->nav_ond_dia)
            ])
          ],
          'psi' => [ 
            'ide'=>"psi", 
            'ico'=>"", 
            'nom'=>"Cíclico", 
            'des'=>"", 
            'htm'=>Dat::inf_pos("hol","psi",[ 
              'psi'=>$_psi = Hol::_('psi',$_hol->val['psi']),
              'est'=>Hol::_('psi_hep_est',$_psi->hep_est),
              'lun'=>Hol::_('psi_ani_lun',$_psi->ani_lun),
              'hep'=>Hol::_('psi_hep_pla',$_psi->hep_pla)
            ])
          ]      
        ],[ 'sel' => "kin" ])
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
          'banco-psi'=>'',
          'anillo_solar'=>'',
          'giro_lunar'=>'',
          'heptadas'=>'',
          'estacion_solar'=>'',
          'heptagono_semanal'=>''
        ]
      ];

      $tab_tip = array_key_exists($Uri->art,$ope_atr['kin']) ? 'kin' : ( array_key_exists($Uri->art,$ope_atr['psi']) ? 'psi' : FALSE );
      
      if( !$tab_tip ){
        echo "
        <article>
          ".Doc::tex([ 'tip'=>"err", 'tex'=>"No existe el Tablero Seleccionado..." ])."
        </article>";
      }
      else{

        $tab_cod = $ope_atr[$tab_tip][$Uri->art];

        // genero datos
        $_hol->dat = Hol::val_dat($tab_tip, $_hol->val);
        
        // operadores del tablero
        $tab_ide = "hol.{$tab_tip}";
        
        if( !( $tab_ope =  Dat::get_est('hol',"{$tab_tip}_{$tab_cod}",'tab') ) ) $tab_ope = [];
        
        // inicializo valores
        $tab_ope['val'] = [];            

        // fecha => muestro listado por ciclos
        if( !empty( $_hol->val['fec'] ) ){
          // joins
          $tab_ope['est'] = [ 'fec'=>["dat"], 'hol'=>array_keys($ope_atr) ];
          
          // cargo datos
          $tab_ope['dat'] = $_hol->dat;

          // activo acumulados
          $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
          
          // agrego opciones
          if( !empty($tab_ope['opc']) ) $tab_ope['val']['acu']['opc'] = 1;

          // valor seleccionado
          $tab_ope['val']['pos'] = $_hol->val;
        }

        // 1- imprimo operadores del tablero
        foreach( ( $ope = Obj::val_nom(Dat::$Tab['ope'],'ver',['ver','opc','val']) ) as $ope_ide => $ope_tab ){

          if( !empty( $htm = Dat::tab_ope($ope_ide, $tab_ide, $tab_ope) ) ){

            $App->Doc['cab']['ini'][$ope_ide] = [ 
              'ico'=>$ope_tab['ico'], 
              'tip'=>"pan", 
              'nom'=>$ope_tab['nom'], 
              'nav'=>[ 'eti'=>"article" ], 
              'htm'=>$htm
            ];
          }
        }

        // 2-imprimo operador de lista
        $lis_ope = Dat::get_est("hol",$tab_tip,'lis');

        $lis_ope['val'] = $tab_ope['val'];
        
        // - cargo estructuras y datos
        if( isset($tab_ope['est']) ){

          // Copio datos del tablero
          $lis_ope['dat'] = $tab_ope['dat'];

          // busco operadores de lista por : esquema_estructura
          $lis_ope['est'] = [];
          foreach( $tab_ope['est'] as $esq_ide => $esq_lis ){

            $lis_ope['est'][$esq_ide] = [];
            foreach( $esq_lis as $est_ide ){

              $lis_ope['est'][$esq_ide][$est_ide] = [];

              if( $est_ope = Dat::get_est($esq_ide,$est_ide,'lis') ){

                $lis_ope['est'][$esq_ide][$est_ide] = $est_ope;
              }
            }
          }
        }
        $ope = Dat::$Tab['ope']['lis'];

        $App->Doc['cab']['ini']['lis'] = [ 
          'ico'=>$ope['ico'], 
          'tip'=>"win", 
          'nom'=>$ope['nom'], 
          'htm'=>Dat::tab_ope('lis',"hol.{$tab_tip}",$lis_ope) 
        ];

        // 3- imprimo tablero en página principal
        echo "
        <article>
          ".Hol::tab($tab_tip, $tab_cod, $tab_ope, [
            'pos'=>[ 'onclick'=>"Dat.tab_val('mar',this);" ],
            'ima'=>[ 'onclick'=>FALSE ]
          ])."
        </article>";
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
        <!-- Diario -->
        <section>
          <h3>Diario</h3>
          <?= Fig::ico('fec_val',['class'=>"mar_hor-1"]) ?>          

          <p>Desde esta sección podrás cambiar la fecha para la posición principal del tablero<c>,</c> y ver un detalle de cada código correspondiente<c>.</c></p>

          <p>También puedes <i>marcar</i> otras posiciones del tablero haciendo click directamente sobre ellas<c>.</c> Estas serán cargardas en la seccion de acumulados<c>.</c></p>

        </section>        
        <!-- Seleccion -->
        <section>
          <h3>Selección por Valores</h3>
          <?= Fig::ico('dat_ver',['class'=>"mar_hor-1"]) ?>

          <p>Accede a esta sección para seleccionar múltiples posiciones y luego comparar sus datos<c>.</c> Puedes aplicar criterios de selección por estructuras de datos<c>,</c> fechas<c>,</c> o posiciones<c>.</c></p>

          <p>Si buscas por estructuras de datos<c>,</c> deberás seleccionar primero el grupo de cuentas<c>(</c> Kin<c>,</c> Sello<c>,</c> Tono<c>,</c> Psi<c>-</c>Cronos<c>,</c> etc<c>.</c> <c>)</c><c>,</c> luego la cuenta <c>(</c> las ondas encantadas del kin<c>,</c> las familias de los sellos<c>,</c> los pulsares de los tonos<c>,</c> etc<c>.</c> <c>)</c> y por último el valor <c>(</c> la <n>3</n><c>°</c> onda encantada<c>,</c> la familia cardinal<c>,</c> el pulsar dimensional del tiempo<c>,</c> etc<c>.</c> <c>)</c><c>.</c></p>

          <p>También puedes buscar por fechas del calendario o el número de posiciones en el tablero<c>.</c> En estos casos deberás ingresar un valor inicial<c>,</c> y puedes limitar la sección con un valor final<c>.</c> También puedes indicar un incremento<c>,</c> es decir<c>,</c> un salto entre valores seleccionados<c>;</c> una cantidad límite de resultados<c>;</c> y si quieres que sean los primeros o los últimos de la lista<c>.</c></p>

          <p>Los valores seleccionados serán marcados con un recuadro en el tablero<c>.</c> También serán cargados en la sección de acumulados<c>,</c> serán tenidos en cuenta en los totales por estructura<c>,</c> y se mostrarán en el listado<c>.</c></p>

        </section>        
        <!-- Opciones -->
        <section>          
          <h3>Opciones del Tablero</h3>
          <?= Fig::ico('opc_bin',['class'=>"mar_hor-1"]) ?>

          <p>Desde aquí puedes cambiar los colores de fondo<c>,</c> seleccionar el tipo de ficha y ver contenido numérico o textual para cada posición<c>.</c></p>

          <p>Según los atributos del tablero<c>,</c> definidos por su cuenta<c>,</c> podrás activar o desactivar ciertas posiciones clave<c>,</c> como aquellas relacionadas por el oráculo del destino o las que se encuentran en un mismo pulsar de la onda encantada<c>.</c></p>

          <p>Estas posiciones serán tenidas en cuenta en los acumulados del tablero y los elementos de la lista<c>.</c></p>

        </section>
        <!-- Operador -->
        <section>
          <h3>Datos y Valores</h3>
          <?= Fig::ico('est',['class'=>"mar_hor-1"]) ?>

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
          <?= Fig::ico('lis_ite',['class'=>"mar_hor-1"]) ?>

          <p>Se arma con los datos de todas las posiciones acumuladas de los distintos operadores<c>:</c> <b class="ide">Fecha del diario</b><c>,</c> <b class="ide">Marca directa</b><c>,</c> <b class="ide">Selección por Valores</b> y <b class="ide">Opciones del Tablero</b><c>.</c> Puedes elegir entre los distintos critrios de acumulación o ver todas las posiciones<c>.</c></p>

          <p>Los items de la lista contienen los mismos datos sobre los códigos y cuentas que cada posición en el tablero <c>(</c>la Fecha<c>,</c> el Kin y el Psi<c>)</c><c>.</c> Puedes ver y comparar cada atributo que está representado por su ficha correspondiente<c>,</c> al pasar el mouse sobre ellas aparecerá un pequeño contenido descriptivo<c>.</c></p>

          <p>También puedes filtrar las posiciones por las distintas estructuras de códigos y cuentas<c>,</c> de la misma manera que con la selección de posiciones en el Tablero<c>.</c> Tambien puedes aplicarlos por fecha siguiendo los mismos criterios <c>(</c> desde<c>,</c> hasta<c>,</c> cada<c>,</c> cuántos<c>,</c> al<c>...</c> <c>)</c></p>

          <p>Luego puedes seleccionar las columnas que representen a los datos que deseas ver<c>,</c> y revelar los titulos por Ciclos y Agrupaciones o analizar las lecturas disponibles para cada posición<c>.</c></p>

        </section>        
      <?php
      $App->Doc['cab']['med']['app_dat']['htm'] = ob_get_clean();     
      
      // cargo todos los datos utilizados por esquema
      $App->Ses['est']['api']['hol'] = array_keys($App->dat['hol']);
      $App->Ses['est']['api']['fec'] = array_keys($App->dat['fec']);
    }
    //////////////////////////////////////////////////////////////////////////////////
    // Contenido: bibliografia + codigo + kin planetario /////////////////////////////
    else{
      // enlaces
      $Bib = SYS_NAV."sincronario/bibliografia/";
      // cargo directorio
      $Dir = $App->dir();      
      // cargo indice
      $Nav = $App->Nav;

      // cargo módulos
      if( !empty( $rec = Arc::val("./_app/$Uri->esq/$Uri->cab") ) ){

        require_once( $rec );
      }

      // imprimo articulo : html-php
      if( !empty( $rec = Arc::val($val = "./_app/$Uri->esq/$Uri->cab/$Uri->art") ) ){

        include( $rec );
      }
      else{
        echo Doc::tex([ 'tip'=>"err", 'tex'=>"No existe el archivo '$val'" ]);
      }
    }
    // imprimo secciones
    $App->Doc['htm']['sec'] = ob_get_clean();
  }

  // codigo inicial
  $App->Ses['eje'] .= '

    var $_hol = { val : '.Obj::val_cod( $_hol->val ).' };
    
    inicio();';
  ;
