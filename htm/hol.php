<?php
  // aplicacion
  class _hol_app {

    static string $IDE = "_hol_app-";
    static string $EJE = "_hol_app.";

    // main : app.cab.art/val
    public function __construct( array &$_ ){

      global $_usu, $_app;

      $_uri = $_app->uri;

      // inicializo datos    
      $_val = _hol::val( date('Y/m/d') );

      // proceso y actualizo fecha en sesion
      if( in_array($_uri->cab,[ 'inf', 'tab', 'usu' ]) ){

        $dat_ide = empty($_uri->art) ? 'kin' : explode('-',$_uri->art)[0];

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
      }
            
      // menu principal: agrego valor diario
      $ele_ope = [ 
        'ope' => [ 'class'=>"mar_arr-1" ], 'lis'=>[ 'style'=>"height: 65vh;" ], 'opc'=>['tog','ver'] 
      ];
      $_['nav']['ope'] = [
        'ico'=>"fec_val",
        'nom'=>"Operador",
        'htm'=>
          _hol_val::fec($_val)
          ._doc_val::nav('bar',[
            'kin' => [ 'nom'=>"Orden Sincrónico", 'des'=>"", 'htm'=>_hol_dia::kin( $_val['kin'], $ele_ope) ],
            'psi' => [ 'nom'=>"Orden Cíclico",    'des'=>"", 'htm'=>_hol_dia::psi( $_val['psi'], $ele_ope) ]
            ],[            
            'sel' => "kin"
          ])
      ];

      // inicio : 
      if( empty($_uri->cab) ){

        $_ = $this->ini($_,$_val);
      }
      // por seccion : introduccion
      elseif( empty($_uri->art) ){
        
        $_ = $this->sec($_, $_uri, $_val);
      }
      // por articulo : bibliografía + informes + tableros
      else{
        ob_start();
        switch( $_uri->cab ){
        // tablero
        case 'tab': $_ = $this->tab($_,$_uri,$_val); break;
        // bib + dat + val
        default:    $_ = $this->{$_uri->cab}($_,$_uri,$_app->ope['nav_art'],$_val); break;
        }

        $_['sec'] = ob_get_clean();
        
        // cargo todos los datos utilizados por esquema
        if( $_uri->cab == 'tab' ) $_app->rec['dat']['hol'] = [];
      }

      // recursos del documento
      $_app->rec['htm'] []= $_uri->esq;
      $_app->rec['css'] []= $_uri->esq;

      $_app->rec['eje'] .= "
        var \$_hol_app = new _hol_app( { val : "._obj::cod($_val)." } );
      ";
      return $_;
    }
    // Inicio
    public function ini( array $_, array $_hol ) : array {
      ob_start();            
      ?>
      <article>

        <h2>Inicio</h2>

      </article>
      <?php
      $_['sec'] = ob_get_clean();

      return $_;
    }
    // Menú
    public function sec( array $_, _app_uri $_uri, array $_hol ) : array {

      switch( $_uri->cab ){
      case 'bib': 
        ?>
        <?php
        break;
      case 'inf': 
        ?>      
        <?php
        break; 
      case 'dia': 
        ?>      
        <?php
        break;
      case 'tab': 
        ?>      
        <?php
        break;
      }
      return $_;
    }
    // Bibliografía
    public function bib( array $_, _app_uri $_uri, array $nav, array $_val ) : array {
      // cargo referencia
      $_bib = SYS_NAV."hol/bib/";

      switch( $_uri->art ){
      // glosarios
      case 'ide':
        ?>
        <h2></h2>
        <section>

          <p>En el siguiente listado podés encontrar los términos y sus significados por Libro.</p>

          <form class="ite">

            <?= _doc_val::var('val','ver',[ 
              'des'=>"Filtrar...",
              'ite'=>[ 'class'=>"tam-cre" ],
              'htm'=>_doc_val::ver(['cue'=>0, 'eje'=>"_hol_bib.ide('ver',this)" ], [ 'class'=>"anc-100" ])
            ]) ?>

          </form>

          <div style="height: 75vh; overflow: auto;">
            <table>

              <thead>
                <tr>
                  <th scope="col" data-atr="ide" >Libro</th>
                  <th scope="col" data-atr="nom" >Término</th>
                  <th scope="col" data-atr="des" >Definicion</th>
                </tr>
              </thead>

              <tbody>
              <?php
              $_lib = FALSE;
              foreach( _dat::get("api.app_art_ide",[
                'ver'=>"`esq` = 'hol'", 'ord'=>"`ide` ASC, `nom` ASC"
              ]) as $i => $v ){ 
                if( !$_lib || $_lib->ide != explode('_',$v->ide)[0] ){
                  $_lib = _dat::get("api.app_art",[ 
                    'ver'=>"esq = 'hol' AND cab = 'bib' AND ide = '$v->ide'", 
                    'opc'=>"uni" 
                  ]);
                }
                echo "
                <tr>
                  <td data-atr='ide'><a href='$_bib/$_lib->ide' target='_blank'>"._doc::let($_lib->nom)."</a></td>
                  <td data-atr='nom'>"._doc::let($v->nom)."</td>
                  <td data-atr='des'>"._doc::let($v->des)."</td>            
                </tr>";
              }?>
              </tbody>

            </table>
          </div>

        </section>        
        <?php          
        break;
      // libros html
      default:
        // cargo directorio para imágenes del libro
        $_dir = $_uri->dir($_uri);
        if( !empty( $rec = $_uri->rec($val = "htm/$_uri->esq/$_uri->cab/$_uri->art") ) ){
          
          include( $rec );
        }
        else{ echo "
          <p class='err'><c>{-_-}</c> No existe el archivo <c>'</c><b class='ide'>{$val}</b><>'</c></p>";
        }       
        break;
      }      
      return $_;
    }
    // Datos
    public function dat( array $_, _app_uri $_uri, array $nav, array $_val ) : array {
      // cargo referencia
      $_bib = SYS_NAV."hol/bib/";
      switch( $_uri->art ){
      case 'rad': 
        ?>
        <!-- dìas de la semana -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">El encantamiento del Sueño</a> se divide al año en <n>13</n> lunas de <n>28</n> días cada una<c>.</c> Cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>

          <p>Posteriormente<c>,</c> en el libro de <a href="<?=$_bib?>lun#_02-07-" target="_blank">Las <n>13</n> lunas en movimiento</a>, se mencionan los plasmas para nombrar a cada uno de los días de la semana<c>-</c>heptada<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','nom','pod'] ])?>

        </article>
        <!-- sellos de la profecia -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>

          <p>En <a href="<?=$_bib?>tel#_02-06-" target="_blank">El telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

          <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario<c>:</c> familia portal y familia señal <c>(</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] ])?>

          <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

          <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del <dfn title="Campo Resonante de la Tierra">banco<c>-</c>psi</dfn> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','tel_des','tel_año','rin_des'] ])?>

        </article>
        <!-- heptágono de la mente -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>

          <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

          <p>En el <a href="<?=$_bib?>rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>            

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','nom','hep','hep_pos'] ])?>

        </article>
        <!-- autodeclaraciones diarias -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>

          <p>En <a href="<?=$_bib?>ato#_03-06-" target="_blank">Átomo del Tiempo</a> se describen las afirmaciones correspondientes a las Autodeclaraciones Diarias de Padmasambhava para cada plasma<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 'atr'=>['ide','nom','hum_cha','cha_nom','pla_des'] ])?>

        </article>
        <!-- componenetes electrónicos -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>

          <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

          <?=_doc_lis::tab('api.hol_rad',[ 
            'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_ene','pla_fue_pre','pla_fue_pos'] 
          ])?>

          <p>Desde este paradigma los plasmas son <q>componenees electrónicos</q> constituídos por la combinación de <n>12</n> líneas electrónicas de fuerza que convergen en <n>6</n> tipos de electricidad clasificadas según la cantidad de cargas positivas o negativas que contengan<c>.</c></p>

          <p>Los <n>12</n> tipos de líneas electrónicas<c>:</c></p>

          <?=_doc_lis::tab('api.hol_rad_pla_fue',[ 'atr'=>['ide','nom','ele_pre','ele_ope','ele_pos'] ])?>

          <p>Los <n>6</n> tipos de electricidad son<c>:</c></p>

          <?=_doc_lis::tab('api.hol_rad_pla_ele',[ 'atr'=>['ide','cod','nom','des'] ])?>            

        </article>        
        <?php
        break;
      case 'ton': 
        ?>
        <!-- rayos de pulsación -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <cite>el Factor Maya</cite><c>,</c>
            se introduce el concepto de <a href="<?=$_bib?>fac#_03-03-" target="_blank">secuencias radiales</a> donde se aplican los números del <n>1</n> al <n>13</n> en una serie de ciclos constantes<c>.</c>
            Y se definen como <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">rayos de pulsación</a> dotados con una función radio<c>-</c>resonante en particular<c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton',[ 'atr'=>['ide','gal'] ])?>

        </article>
        <!-- simetría especular -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>

          <p>En el <cite>Factor Maya</cite> 
            se definen los <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">números de simetría especular</a> a partir de la posición del tono <n>7</n> en el Módulo Armónico<c>.</c>
            Luego<c>,</c> se describen sus relaciones aplicando el concepto a los <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">rayos de pulsación</a>>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton_sim',[ 'atr'=>['ide','nom','ton'] ])?>
          
        </article>        
        <!-- principios de la creacion -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>

          <p>En <cite>el Encantamiento del sueño</cite> 
            se introduce el concepto de <a href="<?=$_bib?>enc#_03-10-" target="_blank">onda encantdada</a><c>,</c> y se definenen los <n>13</n> números como los <a href="<?=$_bib?>enc#_03-11-" target="_blank">tonos galácticos de la creación</a><c>.</c> 
            De esta manera se crea el <a href="<?=$_bib?>enc#_03-09-" target="_blank">Módulo de Sincronización Galáctica</a> que sincroniza tanto las <a href="<?=$_bib?>enc#_02-03-09-" target="_blank">lunaciones del ciclo anual</a><c>,</c> como el movimiento a través de <a href="<?=$_bib?>enc#_02-03-10-" target="_blank">los castillos de la nave</a><c>,</c> las <a href="<?=$_bib?>enc#_02-03-07-" target="_blank">trayectorias armónicas</a> y las <a href="<?=$_bib?>enc#_03-16-" target="_blank">estaciones galácticas</a><c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton',[ 'atr'=>['ide','nom','des','acc'] ])?>

        </article>
        <!-- O.E. de la Aventura -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>

          <p>En el <cite>Encantamiento del sueño</cite> 
            se define la estructura de un <a href="<?=$_bib?>enc#_02-03-08-" target="_blank">Castillo del Destino</a> como una serie de <n>4</n> ondas encantadas<c>,</c> de <n>13</n> tonos galácticos cada una<c>.</c>
            Cada posición de la Onda Encantada está cargada con un determinado <a href="<?=$_bib?>enc#_03-12-" target="_blank">mandato de acción</a> definido por la naturaleza de su tono correspondiente<c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton',[ 'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 
            'tit_cic'=>['ond_enc']
          ])?>
          
        </article>        
        <!-- pulsar dimensional -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>

          <p>En <cite>el Encantamiento del sueño</cite> 
            
            <a href="<?=$_bib?>enc#_03-13-" target="_blank"></a>
            <c>.</c>
          </p>

          <?=_doc_lis::tab('api.hol_ton_dim')?>
          
        </article>
        <!-- pulsar matiz -->
        <article>
          <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>

          <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_ton_mat')?>
          
        </article>        
        <?php
        break;
      case 'sel': 
        ?>
        <!-- signos direccionales -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_sel_cic_dir')?>

          <!-- desarrollo del ser -->
          <h3 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel',[ 'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ])?>

          </section>
          <!-- etapas evolutivas de la mente -->
          <h3 id="<?="_{$nav[2]['01']['02']->pos}-"?>"><?=_doc::let($nav[2]['01']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_cic_men',[ 'atr'=>['sel','nom','des','lec'] ])?>

          </section>
          <!-- familias ciclicas -->
          <h3 id="<?="_{$nav[2]['01']['03']->pos}-"?>"><?=_doc::let($nav[2]['01']['03']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel',[ 'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ])?>

          </section>

        </article>
        <!-- colocacion cromática -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>
          
          <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
          
          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>

          <!-- familias -->
          <h3 id="<?="_{$nav[2]['02']['01']->pos}-"?>"><?=_doc::let($nav[2]['02']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_cro_fam',[ 'atr'=>['ide','nom','pla','hum','des','sel'] ])?>

          </section>
          <!-- clanes -->
          <h3 id="<?="_{$nav[2]['02']['02']->pos}-"?>"><?=_doc::let($nav[2]['02']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_cro_ele',[ 'atr'=>['ide','nom','col','men','des','sel'] ])?>

          </section>

        </article>
        <!-- colocación armónica -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>

          <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>

          <?=_doc_lis::tab('api.hol_sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>

          <!-- razas -->
          <h3 id="<?="_{$nav[2]['03']['01']->pos}-"?>"><?=_doc::let($nav[2]['03']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_arm_raz',[ 'atr'=>['ide','nom','pod','dir','sel'] ])?>

          </section>
          <!-- células -->
          <h3 id="<?="_{$nav[2]['03']['02']->pos}-"?>"><?=_doc::let($nav[2]['03']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_arm_cel',[ 'atr'=>['ide','nom','fun','pod','des','sel'] ])?>

          </section>

        </article>            
        <!-- parejas del oráculo -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>

          <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>

          <!-- analogos -->
          <h3 id="<?="_{$nav[2]['04']['01']->pos}-"?>"><?=_doc::let($nav[2]['04']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_par_ana')?>

          </section>
          <!-- antipodas -->
          <h3 id="<?="_{$nav[2]['04']['02']->pos}-"?>"><?=_doc::let($nav[2]['04']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_par_ant')?>

          </section>
          <!-- ocultos -->
          <h3 id="<?="_{$nav[2]['04']['03']->pos}-"?>"><?=_doc::let($nav[2]['04']['03']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_par_ocu')?>

          </section>

        </article>                  
        <!-- holon Solar -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>

          <p>El código 0-19</p>              

          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','sol_pla_des'], 
            'tit_cic'=>['sol_cel','sol_cir','sol_pla'] 
          ])?>

          <!-- orbitas planetarias -->
          <h3 id="<?="_{$nav[2]['05']['01']->pos}-"?>"><?=_doc::let($nav[2]['05']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_sol_pla')?>

          </section>
          <!-- células solares -->
          <h3 id="<?="_{$nav[2]['05']['02']->pos}-"?>"><?=_doc::let($nav[2]['05']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_sol_cel')?>

          </section>
          <!-- circuitos de telepatía -->
          <h3 id="<?="_{$nav[2]['05']['03']->pos}-"?>"><?=_doc::let($nav[2]['05']['03']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_sol_cir')?>

          </section>              

        </article>
        <!-- holon planetario -->
        <article>  
          <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>
          
          <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>

          <!-- centros galácticos -->
          <h3 id="<?="_{$nav[2]['06']['01']->pos}-"?>"><?=_doc::let($nav[2]['06']['01']->nom)?></h3>
          <section>

            <?=_doc_lis::tab('api.hol_sel_pla_cen')?>

          </section>
          <!-- flujos de la fuerza-g -->
          <h3 id="<?="_{$nav[2]['06']['02']->pos}-"?>"><?=_doc::let($nav[2]['06']['02']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_lis::tab('api.hol_sel_pla_res')?>

          </section>              

        </article>
        <!-- holon humano -->
        <article>
          <h2 id="<?="_{$nav[1]['07']->pos}-"?>"><?=_doc::let($nav[1]['07']->nom)?></h2>

          <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 
            'tit_cic'=>['cro_ele'] 
          ])?>

          <!-- Centros Galácticos -->
          <h3 id="<?="_{$nav[2]['07']['01']->pos}-"?>"><?=_doc::let($nav[2]['07']['01']->nom)?></h3>
          <section>

            <?=_doc_lis::tab('api.hol_sel_hum_cen')?>

          </section>
          <!-- Extremidades -->
          <h3 id="<?="_{$nav[2]['07']['02']->pos}-"?>"><?=_doc::let($nav[2]['07']['02']->nom)?></h3>
          <section>

            <?=_doc_lis::tab('api.hol_sel_hum_ext')?>

          </section>                     
          <!-- dedos -->
          <h3 id="<?="_{$nav[2]['07']['03']->pos}-"?>"><?=_doc::let($nav[2]['07']['03']->nom)?></h3>
          <section>            
            
            <?=_doc_lis::tab('api.hol_sel_hum_ded')?>

          </section>
          <!-- lados -->
          <h3 id="<?="_{$nav[2]['07']['04']->pos}-"?>"><?=_doc::let($nav[2]['07']['04']->nom)?></h3>
          <section>
            
            <?=_doc_lis::tab('api.hol_sel_hum_res')?>

          </section>              

        </article>        
        <?php
        break;
      case 'lun': 
        ?>

        <article>
          <h2></h2>

          <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_lis::tab('api.hol_lun',[ 'atr'=>['ide','arm','rad','ato_des'] ])?> 
        </article>

        <!-- 4 heptadas -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <p>En <a href="<?=$_bib?>lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>" target="_blank">el Telektonon</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>" target="_blank">el átomo del tiempo</a><c>.</c></p>

        </article>        
        <?php
        break;
      case 'cas': 
        ?>
        <!-- -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h3>

        </article>        
        <?php
        break;
      case 'kin': 
        ?>
        <!-- -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <!-- -->
          <h4 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h4>
          <section>

          </section>            

        </article>        
        <?php
        break;
      case 'psi': 
        ?>
        <!-- -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <!-- -->
          <h4 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h4>
          <section>

          </section>            

        </article>           
        <?php
        break;
      }
      return $_;
    }
    // Valor
    public function val( array $_, _app_uri $_uri, array $nav, array $_val ) : array {
      $_bib = SYS_NAV."hol/bib/";
      switch( $_uri->art ){
      }
      return $_;
    }
    // Informe
    public function inf( array $_, _app_uri $_uri, array $nav, array $_val ) : array {
      $_bib = SYS_NAV."hol/bib/";
      // galáctico
      $_kin = _hol::_('kin', $_val['kin']);
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      // solar
      $_psi = _hol::_('psi', $_val['psi']);

      switch( $_uri->art ){      
      case 'kin': 
        ?>
        <!-- ficha del encantamiento -->
        <article>
          <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>

          <?= _hol_fic::kin('enc',$_kin) ?>

          <br>

          <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

          <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

        </article>
        <!-- 4 + 1 : parejas -->
        <article>
          <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>

          <?= _hol_tab::kin('par',[ 
            'ide'=>$_kin->ide,
            'sec'=>[ 'par'=>0 ],
            'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
          ],[
            'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
            'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
          ])?>
          <!-- Descripciones -->
          <h3 id="<?="_{$nav[2]['02']['01']->pos}-"?>"><?=_doc::let($nav[2]['02']['01']->nom)?></h3>
          <section>

            <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            

            <?= _hol_fic::kin('par',$_kin) ?>

          </section>
          <!-- Lecturas diarias -->
          <h3 id="<?="_{$nav[2]['02']['02']->pos}-"?>"><?=_doc::let($nav[2]['02']['02']->nom)?></h3>
          <section>

            <p>Puedes descubrir formas de relacionar las energías utilizando las palabras clave<c>,</c> que representan las funciones de cada pareja respecto al destino<c>.</c> Al compararlas<c>,</c> podrás ir incorporando información y comprendimiento sobre los distintos roles que cumplen<c>.</c></p>

            <p>En la siguiente tabla se muestran las principales propiedades y claves para cada pareja del oráculo<c>:</c></p>

            <?= _hol_lis::kin('par',[ 'dat'=>$_kin,  'atr'=>"pro",  'ele'=>[ 'lis'=>['class'=>"anc-100"] ] ]) ?>

            <br>
            
            <p>En <a href="<?=$_bib?>tut#_04-04-" target="_blank">este tutorial</a> puedes encontrar las referencias sobre las aplicaciones de los oráculos y el tiempo net<c>.</c></p>

            <p>De esta manera<c>,</c> puedes armar lecturas conjugando las palabras clave<c>,</c> y ordenarlas según las miradas del oráculo<c>;</c> por ejemplo<c>:</c></p>

            <?= _hol_fic::kin('par_lec',$_kin) ?>

          </section>  
          <!-- Posiciones en el tzolkin -->  
          <h3 id="<?="_{$nav[2]['02']['03']->pos}-"?>"><?=_doc::let($nav[2]['02']['03']->nom)?></h3>
          <section>

            <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

            <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>

            <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"cic", 'ele'=>[ 'lis'=>['class'=>"anc-100"] ] ]) ?>

          </section>  
          <!-- Sincronometría del holon -->
          <h3 id="<?="_{$nav[2]['02']['04']->pos}-"?>"><?=_doc::let($nav[2]['02']['04']->nom)?></h3>
          <section>

            <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

            <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

            <br>

            <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"gru", 'ele'=>[ 'lis'=>['class'=>"anc-100"] ] ]) ?>

          </section>
          
        </article>
        <!-- 4 x 52:13 nave del tiempo -->
        <article>
          <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>
          <!-- x52 : Castillo Fractal -->  
          <h3 id="<?="_{$nav[2]['03']['01']->pos}-"?>"><?=_doc::let($nav[2]['03']['01']->nom)?></h3>
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
                'val'=>[ 'pos'=>$_kin->ide ],
                'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
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
          <h3 id="<?="_{$nav[2]['03']['02']->pos}-"?>"><?=_doc::let($nav[2]['03']['02']->nom)?></h3>
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
              'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
            ], [
              'ond'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
              'pos'=>[ 'style'=>"width:6rem; height:6rem;" ]
            ]) ?>

          </section>  
        </article>
        <!-- 13 x 20:4 Giro Galáctico -->
        <article>
          <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>
          <!-- x20 : Trayectoria Armónica -->  
          <h3 id="<?="_{$nav[2]['04']['01']->pos}-"?>"><?=_doc::let($nav[2]['04']['01']->nom)?></h3>
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
              'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
            ], [
              'tra'=>[ 'class'=>"mar-2 mar_hor-aut pad-3 ali_pro-cen" ],
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
          <h3 id="<?="_{$nav[2]['04']['02']->pos}-"?>"><?=_doc::let($nav[2]['04']['02']->nom)?></h3>
          <section>
            <?php
            $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel);    
            ?>    

            <nav>
              <p>Ver <a href='<?=$_bib?>enc#_03-05-' target='_blank'>Colocación Armónica: Razas Raíz Cósmicas</a> en el Encantamiento del Sueño</p>
            </nav>

          </section>  
        </article>
        <!-- 4 x 13:5 Giro Espectral -->
        <article>
          <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>
          <!-- x65 : Estación Galáctica -->
          <h3 id="<?="_{$nav[2]['05']['01']->pos}-"?>"><?=_doc::let($nav[2]['05']['01']->nom)?></h3>
          <section>
            <?php
            $_est = _hol::_('kin_cro_est',$_kin->cro_est);
            ?>

            <nav>
              <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
            </nav>    

          </section>
          <!-- x5 : Elemento Cromático -->  
          <h3 id="<?="_{$nav[2]['05']['02']->pos}-"?>"><?=_doc::let($nav[2]['05']['02']->nom)?></h3>
          <section>
            <?php
            $_ele = _hol::_('kin_cro_ele',$_kin->cro_ele);
            ?>
            
            <nav>
              <p>Ver <a href='<?=$_bib?>enc#_03-16-' target='_blank'>Colocación Cromática</a> en el Encantamiento del Sueño</p>
            </nav>

          </section>
        </article>
        <!-- Módulo Armónico : pag + ene + chi -->  
        <article>
          <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>
        </article>        
        <?php
        break;
      case 'ton': 
        ?>
        <!-- Ficha -->
        <article>

          <?= _hol_fic::ton([],$_ton) ?>

          <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'>los 13 tonos Galácticos de la Onda Encantada</a> en el Encantamiento del Sueño<c>...</c></p>    

        </article>
        <!-- Aventura de la Onda Encantada -->

        <!-- Simetría Especular -->

        <!-- Pulsares Dimensionales -->

        <!-- Pulsares Matiz Entonado -->         
        <?php
        break;
      case 'sel': 
        ?>
        <!-- Ficha -->
        <article>

          <?= _hol_fic::sel([],$_sel) ?>

          <p><?= _doc::let($_sel->des_pro) ?></p>

          <p>Ver <a href='<?=$_bib?>enc#_03-11-' target='_blank'></a> en </p>

        </article>
        <!-- Desarrollo del ser -->

        <!-- Colocacion Cromática -->

        <!-- Colocacion Armónica -->

        <!-- Holon Solar -->

        <!-- Holon Planetario -->

        <!-- Holon Humano -->
        <?php
        break;
      case 'psi': 
        ?>
        <!-- x91: Estaciones Solares -->

        <!-- x28: Giros Lunares -->

        <!-- x20: Vinales -->

        <!-- x7: Heptadas -->

        <!-- x5: Cromaticas -->        
        <?php
        break;
      case 'lun': 
        ?>
        <?php
        break;
      case 'rad': 
        ?>
        <?php
        break;
      }      
      return $_;
    }
    // Tableros 
    public function tab( array $_, _app_uri $_uri, array $_val ) : array {
          
      $art_tab = explode('-',$_uri->art);
      if( isset($art_tab[1]) && method_exists("_hol_tab",$ide = $art_tab[0]) ){
        
        // operadores del tablero
        $_tab =  _app::tab('hol',str_replace('-','_',$_uri->art));

        $tab_ide = "hol.{$ide}";
        $tab_ele = [];
        $tab_ope = !empty($_tab->ope) ? $_tab->ope : [];

        // inicializo valores
        $tab_ope['val'] = [];
        // fecha => muestro listado por ciclos      
        if( !empty( $_val['fec'] ) ){

          // joins
          if( in_array($ide,['kin','psi']) ){
            // cargo estructuras
            $tab_ope['est'] = [ 'api'=>[ "fec", "hol_kin", "hol_psi" ] ];
            // cargo datos
            $tab_ope['dat'] = _hol::dat( $ide, $_val );
            // acumulados
            $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
            if( !empty($tab_ope['opc']) ){
              $tab_ope['val']['acu']['opc'] = 1;
            }
          }
          // valor seleccionado
          $tab_ope['val']['pos'] = $_val;
        }
        
        // operadores del tablero
        $_ope = _obj::nom(_app_tab::$OPE,'ver',['cue','val','opc']);
        foreach( $_ope as $ope_ide => $ope_tab ){
          if( 
            !empty( $htm = _app_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele ) ) 
          ){
            $_['nav'][$ope_ide] = [ 
              'ico' => $ope_tab['ico'],
              'nom' => $ope_tab['nom'], 
              'nav' => [ 'style'=>"width: 30rem;" ],
              'htm' => $htm
            ];
          }
        }
        // operador de lista
        $_ope = _app_tab::$OPE['lis'];
        $_['win']['est'] = [ 
          'ico' => $_ope['ico'], 
          'nom' => $_ope['nom'],
          'art' => [ 'style'=>"max-width: 55rem; height: 90vh;" ],
          'htm' => _app_tab::ope('lis',"api.hol_{$ide}",$tab_ope)
        ];          
        // imprimo tablero en página principal
        echo "
        <article>
          "._hol_tab::$ide($art_tab[1], $tab_ope, [ 
            'sec'=>[ 'class'=>"mar-aut" ],
            'pos'=>[ 'onclick'=>"_app_tab.val('mar',this);" ]
          ])."
        </article>";
      }
      else{
        echo _doc::let("Error: No existe el tablero del Holon solicitado con '$_uri->art'");
      }
      return $_;
    }
    // Kin Planetario : transitos + firma galàctica
    public function usu( array $_, _app_uri $_uri, array $nav, array $_val ) : array {
      global $_usu;

      return $_;
    }  
  }
  
  // Bibliografìa : glosario + ciclos y cuentas + ...libros
  class _hol_bib { 

    static string $IDE = "_hol_bib-";
    static string $EJE = "_hol_bib.";
    
    // encantamiento
    static function enc( string $ide, array $ele = [] ) : string {      
      $_ = []; $esq = "hol"; $est = str_replace('.','_',$ide); $lis_tip = "val"; $lis_pos = 0;
      $_eje = self::$EJE."enc('$ide',";
      switch( $ide ){
      // encantamiento : libro del kin        
      case 'kin':
        $_ = "
        <!-- libro del kin -->
        <form class='inf' esq='$esq' est='$est'>

          <div class = 'val'>

            <fieldset class='val'>

              "._doc_val::tog_ope()."

              "._doc_val::var('atr',"api.hol_kin.ide",[ 
                'nom'=>"ver kin", 'ope'=>[ 'onchange'=>"{$_eje}this);" ] 
              ])."
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
                'eti'=>'h4', 'id'=>"_04-0{$_cas->ide}-0{$ond}-", 'data-ond'=>$_ond->ide, 
                'htm'=>_doc::let("Onda Encantada {$_ond->ide} {$_ond->nom}")
              ])."
              <section data-kin_nav_ond='{$_ond->ide}'>
                <p class='let-enf' ond='{$_ond->ide}'>Poder "._tex::art_del($_sel->pod)."</p>";
            }
            // célula armónica : titulo + lectura
            if( $_kin->arm_cel != $arm_cel ){
              $arm_cel = $_kin->arm_cel;
              $_cel = _hol::_('kin_arm_cel',$_kin->arm_cel); $_ .= "
              </section>

              "._doc_val::tog([
                'eti'=>'h5','class'=>"tex_ali-izq",'id'=>"kin_arm_cel-{$_cel->ide}-",'data-cel'=>$_cel->ide,
                'htm'=>"<b class='ide'>ARMÓNICA <n>{$_cel->ide}</n></b><c>:</c> {$_cel->nom}<br>"._doc::let(_tex::let_may($_cel->des))
              ])."
              <section data-kin_arm_cel='{$_cel->ide}'>
              ";
            }
            // kin : ficha + nombre + encantamiento
            $_ .= "
            <div data-kin='{$_kin->ide}' id='kin-{$_kin->ide}'>
              <div class='hol-kin'>
                "._hol::ima("kin",$_kin->ide,['class'=>'mar-aut'])."
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
      // encantamiento : índice armónico de 13 trayectorias y 65 células
      case 'kin.arm':
        $arm_cel = 0;
        $_lis = [];
        if( !isset($ele['nav']) ) $ele['nav'] = [];
  
        foreach( _hol::_('kin_arm_tra') as $_tra ){
    
          $_lis_cel = [];
          foreach( _hol::_('sel_arm_cel') as $_cel ){
            $arm_cel++;
            $_cel = _hol::_('kin_arm_cel',$arm_cel); $_lis_cel []= "
            <a class='tex' href='#kin_arm_cel-{$_cel->ide}-'>
              <n>{$_cel->ide}</n><c>.</c> <b class='ide'>{$_cel->nom}</b>"._doc::let(": {$_cel->des}")."
            </a>";
          }
          $_lis []= [
            'ite'=>"Trayectoria $_tra->ide: Tono $_tra->ton, $_tra->ton_des",
            'lis'=>$_lis_cel
          ];
        }
        _ele::cla( $ele['nav'], "dis-ocu" );
        $ele['opc'] = ['tog','ver'];
        $_ = "
  
        "._doc_val::tog(
          [ 'eti'=>'h3', 'htm'=>_doc::let("Índice de las 13 Trayectorias y 65 células armónicas.") ], 
          [ 'ico'=>['class'=>"ocu"] ]
        )."
        
        <nav"._htm::atr($ele['nav']).">
  
          "._doc_lis::val($_lis,$ele)."

        </nav>";    
        break;
      
      }
      return is_array($_) ? _app_dat::lis( $_, $est, $lis_tip, $ele ) : $_;
    }
    // telektonon
    static function tel( string $ide, array $ele = [] ) : string {
      $_ = []; $esq = "hol"; $est = str_replace('.','_',$ide); $lis_tip = "val"; $lis_pos = 0;
      switch( $ide ){
      // libros-cartas
      case 'lib': 
        $ide = isset($ele['ide']) ? $ele['ide'] : 4;
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
              <img src='".SYS_NAV."_/hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta ' class='mar_der-1' style='width:24rem; height: 30rem;'>
              <img src='".SYS_NAV."_/hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta ' class='mar_izq-1' style='width:24rem; height: 30rem;'>              
            </div>
          </figure>";
        }
        $_ = _doc_lis::bar( $_, $ele);
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $est, $lis_tip, $ele ) : $_;
    } 
  }