<?php

  if( !isset($App) ) exit;
    
  $Hol = new stdClass;

  if( !empty($Usu->ide) ){
    
    $Usuario = new Usuario();
  }

  // - cargo Diario: valor por peticion => { hol/$cab/$art/$ide=val }
  $Hol->val = Sincronario::val( !empty($_SESSION['hol-val']) ? Sincronario::val_cod($_SESSION['hol-val']) : date('Y/m/d') );

  // proceso fecha para: Tableros + Diario + Kin Planetario
  if( !empty($Uri->val) ){

    $uri_val = explode('=',$Uri->val);

    if( in_array($uri_val[0],[ 'fec', 'sin' ])  ){

      // proceso fecha del sincronario
      $Hol->val = Sincronario::val($uri_val[1]);
      
      // actualizo fecha del sistema
      $_SESSION['hol-val'] = $uri_val[1];
    }
    else{
      $Hol->val[ $uri_val[0] ] = $uri_val[1];
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

          <?=Doc_Ope::tex([ 'tip'=>"adv", 'tit'=>"¡Atención!", 
            'tex'=>[
              "Este sitio aún se está en construcción...", "Puede haber contenido incompleto, errores o faltas."
            ],
            'htm'=>[
              'eti'=>"div", 
              'class'=>"-ite jus-cen mar-1", 
              'htm'=>"Contacto<c>:</c> 
                ".Doc_Val::ico('usu_mai',['eti'=>"a",
                  'href'=>"mailto:ivan.pieszko@gmail.com",
                  'class'=>"mar_hor-1",
                  'title'=>"Enviar un correo electrónico..."
                ])."
                ".Doc_Val::ico('usu_tel',['eti'=>"a",
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
            <button class="app-ses_ini" onclick="Doc_Ope.win('app-ses_ini')">Iniciar Sesión</button>
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
    $Doc->Htm['sec'] = ob_get_clean();

    // cargo tutorial:
    ob_start(); ?>
      <!-- Bibliografía -->
      <section>

        <h3>La bibliografía</h3>

        <div class="-val mar-aut">
          <?= Doc_Val::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Doc_Val::ico('doc_lib',['class'=>"mar_hor-1"]) ?>
        </div>

        <p>Aquí podrás encontrar la mayoría de los libros en los cuales se basa la teoría del Sincronario<c>:</c> <q>La ley del Tiempo</q><c>.</c> Esta fué desarrollada por <a href="https://es.wikipedia.org/wiki/Jos%C3%A9_Arg%C3%BCelles" target="_blank">José Argüelles</a> quien organizó una fundación con el mismo nombre <c>(</c><a href="http://www.lawoftime.org" target="_blank">The Law of Time</a><c>)</c><c>.</c> Todos sus libros y materiales se pueden descargar gratuitamente desde <a href="https://13lunas.net/mapa.htm#biblioteca" target="_blank">La Biblioteca de <n>13</n> Lunas</a><c>.</c></p>

        <p>En este sitio se adaptó el formato de texto de cada libro para una página web<c>,</c> se agregaron los íconos correspondientes a cada símbolo e imágenes que amplían su contenido visualamente<c>.</c> También se muestran en orden cronológico<c>,</c> ya que este conocimiento es incremental y los temas se entrelazan y completan en cada publicación<c>.</c></p>

        <p>En la página de cada libro hay un índice en el panel izquierdo<c>,</c> que puedes ocultar o mostrar haciendo click en el botón Correspondiente<c>.</c> Los items del índice que figuran en el libro son los mismos<c>,</c> pero se agregaron nuevos para segmentar la información y poder acceder desde enlaces<c>.</c></p>

      </section>
      <!-- Apuntes -->
      <section>
        <h3>Los Apuntes</h3>

        <div class="-val mar-aut">
          <?= Doc_Val::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Doc_Val::ico('doc_inf',['class'=>"mar_hor-1"]) ?>
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

        <div class="-val mar-aut">
          <?= Doc_Val::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Doc_Val::ico('dat_tab',['class'=>"mar_hor-1"]) ?>
        </div>
        
        <p>Desde el menú principal puedes acceder a un listado de tableros que representan las cuentas principales del sincronario<c>,</c> a estos los llamaremos módulos<c>.</c></p>

        <p>Para cada módulo se genera un ciclo de tiempo que contiene la fecha y abarca el total de días para el ciclo que representa<c>,</c> por Ej<c>:</c> el <a href="<?=SYS_NAV."hol/kin/tzo"?>" target="_blank">tzolkin</a> genera un ciclo de <n>260</n> días<c>,</c> el <a href="<?=SYS_NAV."hol/tab/psi-ban"?>" target="_blank">banco<c>-</c>psi</a> genera un ciclo de <n>365</n> días<c>,</c> y la <a href="<?=SYS_NAV."hol/tab/psi-lun"?>" target="_blank">luna</a> uno de <n>28</n><c>.</c></p>

        <p>Desde allí podrás cambiar la fecha y acceder a los datos del valor diario<c>,</c> opciones<c>,</c> elementos de las posiciones<c>,</c> un índice de las cuentas incluídas y un listado de las posiciones seleccionadas para comparar sus características y ubicaciones<c>.</c></p>
        
      </section>
      <!-- Diario -->
      <section>
        <h3>El Diario</h3>

        <div class="-val mar-aut">
          <?= Doc_Val::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Doc_Val::ico('fec',['class'=>"mar_hor-1"]) ?>
        </div>
        
        <p></p>

      </section>
      <!-- Kin Planetario -->
      <section>
        <h3>El Kin Planetario</h3>

        <div class="-val mar-aut">
          <?= Doc_Val::ico('app_cab',['class'=>"mar_hor-1"]) ?>
          <c>-></c>
          <?= Doc_Val::ico('usu',['class'=>"mar_hor-1"]) ?>
        </div>

        <p></p>

      </section>      
    <?php
    $Doc->Cab['med']['app_dat']['htm'] = ob_get_clean();

  }
  //////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////
  // por articulo 
  else{
    // cargo indice
    $Nav = $App->Nav;
    // cargo directorio
    $Dir = $App->uri_dir();    
    // enlaces
    $Bib = SYS_NAV."sincronario/bibliografia/";    

    // Cargo Contenido por articulo 
    $doc_rec = $App->art();

    ob_start();

    if( is_string($doc_rec) ){

      // imprimo errores
      echo $doc_rec;
    }
    else{
      // importo modulos
      if( isset($doc_rec['eje']) ){

        require_once( $doc_rec['eje'] );

      }
      // imprimo articulo 
      if( isset($doc_rec['art']) ){

        include( $doc_rec['art'] );
      }
    }
    $Doc->Htm['sec'] = ob_get_clean(); 
  }

  // codigo inicial
  $Doc->Eje .= '

    // cargo datos de fecha
    var $Hol = { val : '.Obj::val_cod( $Hol->val ).' };
    
    inicio();';
  ;
