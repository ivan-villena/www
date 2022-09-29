<?php
// aplicacion
class _hol_app {

  // main : app.cab.art/val
  public function __construct( array &$_, _app &$_app ){

    global $_usu;

    $_uri = $_app->uri;

    // inicializo datos    
    $_val = _hol::val( date('Y/m/d') );

    // proceso y actualizo fecha en sesion
    if( in_array($_uri->cab,['val','tab']) ){

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
    $_['nav_htm_ini'] = "
    <section data-ide='dia' class='mar-1'>

      "._hol_val::fec($_val)."

    </section>";

    // panel del usuario
    if( !empty($_usu->ide) ){
    }

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
      case 'bib': $_ = $this->bib($_,$_uri,$_app->ope['nav_art']); break;
      case 'tab': $_ = $this->tab($_,$_uri,$_val); break;
      case 'val': $_ = $this->val($_,$_uri,$_app->ope['nav_art'],$_val); break;
      case 'usu': $_ = $this->usu($_,$_uri,$_app->ope['nav_art']); break;
      }
      $_['sec'][$_uri->art] = [ 'htm'=> ob_get_clean() ];
      
      // cargo todos los datos utilizados por esquema
      if( $_uri->cab == 'tab' ) $_app->dat['hol'] = [];
    }

    // tutoriales de la página
    if( isset( $art_ini ) ) $_['win_fin']['tut'] = [ 'ico'=>"opc", 'nom'=>"Tutorial", 'htm'=>$art_ini ];

    // recursos del documento
    $_app->jso []= "$_uri->esq/app";
    $_app->css []= $_uri->esq;

    $_app->eje .= "
      // pagina
      var \$_hol_app = new _hol_app(".( $_uri->cab == 'tab' ? "{ val : "._obj::cod($_val)." }" : "" ).");

      \$_hol_app.ini();
    ";
    return $_;
  }
  // inicio
  public function ini( array $_, array $_hol ) : array {

    $_kin = _hol::_('kin',$_hol['kin']);

    $ele_ope = [ 'lis'=>[ 'style'=>"height: 55vh;" ], 'opc'=>['tog','ver'] ];

    $_['sec'] = "

      <h2>Inicio</h2>

      "._hol_fic::kin('enc',$_kin)."

      "._doc_val::nav('bar',[
        'kin' => [ 'nom'=>"Orden Sincrónico", 'des'=>"", 'htm'=>_hol_dia::kin( $_hol['kin'], $ele_ope) ],
        'psi' => [ 'nom'=>"Orden Cíclico",    'des'=>"", 'htm'=>_hol_dia::psi( $_hol['psi'], $ele_ope) ]
      ],[
        'sel' => "kin"
      ])."

    ";
    return $_;
  }
  // cabecera
  public function sec( array $_, object $_uri, array $_hol ) : array {

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
  // bibliografía : libros y tutoriales
  public function bib( array $_, object $_uri, array $nav ) : array {
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
            'htm'=>_doc_val::ver('tex',[ 'cue'=>0, 'eje'=>"_hol_bib.ide('ver',this)" ], [ 'class'=>"anc-100" ])
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
            foreach( _dat::get("_api.app_art_ide",[
              'ver'=>"`esq` = 'hol'", 'ord'=>"`ide` ASC, `nom` ASC"
            ]) as $i => $v ){ 
              if( !$_lib || $_lib->ide != explode('_',$v->ide)[0] ){
                $_lib = _dat::get("_api.app_art",[ 
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
    // datos : codigos y cuentas
    case 'dat':
      ?>
      <!-- 7 : plasmas radiales -->
      <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>
      <section>

        <p>En <a href="<?=$_bib?>lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a> se divide el año en <n>13</n> lunas de <n>28</n> días cada una. A su vez, cada luna se divide en <n>4</n> semanas<c>-</c>héptadas de <n>28</n> días<c>.</c></p>

        <p>Los plasmas se utilizan para nombrar a los días de cada semana<c>-</c>heptada<c>.</c></p>

        <?=_doc_est::lis('api.hol_rad',[ 
          'atr'=>['ide','nom','pod'] 
        ], [ 
          'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
        ])?>
        
        <h3 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

          <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario <c>(</c> familia portal<c>:</c> abren los portales codificando el inicio de los anillos solares<c>;</c> y familia señal<c>:</c> descifran el misterio codificando los días fuera del tiempo<c>.</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

          <?=_doc_est::lis('api.hol_rad',[ 
            'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

          <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del banco-psi <c>(</c> el campo resonante de la tierra <c>)</c> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

          <?=_doc_est::lis('api.hol_rad',[ 
            'atr'=>['ide','tel_des','tel_año','rin_des'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

        </section>

        <h3 id="<?="_{$nav[2]['01']['02']->pos}-"?>"><?=_doc::let($nav[2]['01']['02']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

          <?=_doc_est::lis('api.hol_rad',[ 
            'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_ene','pla_fue_pre','pla_fue_pos'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>              

          <p>Desde este paradigma los plasmas son <q>componenees electrónicos</q> constituídos por la combinación de <n>12</n> líneas electrónicas de fuerza que convergen en <n>6</n> tipos de electricidad clasificadas según la cantidad de cargas positivas o negativas que contengan<c>.</c></p>

          <p>Los <n>6</n> tipos de electricidad son<c>:</c></p>

          <?=_doc_est::lis('api.hol_rad_pla_ele',[ 
            'atr'=>['ide','cod','nom','des'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <p>Los <n>12</n> tipos de líneas electrónicas<c>:</c></p>

          <?=_doc_est::lis('api.hol_rad_pla_fue',[ 
            'atr'=>['ide','nom','ele_pre','ele_ope','ele_pos'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

        </section>  
        
        <h3 id="<?="_{$nav[2]['01']['03']->pos}-"?>"><?=_doc::let($nav[2]['01']['03']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se crea un arreglo en forma de heptágono con los plasmas<c>.</c></p>

          <p>En el <a href="<?=$_bib?>rin#_02-06-01-" target="_blank">Proyecto Rinri</a>...</p>

          <p>Por otro lado<c>,</c> en las <a href="<?=$_bib?>ato#_03-06-" target="_blank" rel="">Autodeclaraciones Diarias de Padmasambhava</a> se describen las afirmaciones correspondientes a cada plasma<c>.</c></p>

          <?=_doc_est::lis('api.hol_rad',[ 
            'atr'=>['ide','nom','hum_cha','cha_nom','hep','hep_pos','pla_des'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

        </section>      

      </section>  
      <!-- 13 : tonos galácticos -->
      <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>
      <section>
        <!-- rayos de pulsación -->
        <h3 id="<?="_{$nav[2]['02']['01']->pos}-"?>"><?=_doc::let($nav[2]['02']['01']->nom)?></h3>
        <section>

          <p>En <a href="<?=$_bib?>fac#_04-04-01-" target="_blank">el Factor Maya</a> se definen como rayos de pulsación<c>,</c> cada uno con una función radio<c>-</c>resonante en particular<c>.</c></p>

          <?=_doc_est::lis('api.hol_ton',[ 
            'atr'=>['ide','nom','gal'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

        </section>
        <!-- simetría especular -->
        <h3 id="<?="_{$nav[2]['02']['02']->pos}-"?>"><?=_doc::let($nav[2]['02']['02']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>fac#_04-04-01-02-" target="_blank">Factor Maya</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_ton_sim',[ 
            'atr'=>['ide','nom','ton'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>
          
        </section>        
        <!-- principios de la creacion -->
        <h3 id="<?="_{$nav[2]['02']['03']->pos}-"?>"><?=_doc::let($nav[2]['02']['03']->nom)?></h3>
        <section>

          <p>En <a href="<?=$_bib?>enc#_03-11-" target="_blank">el Encantamiento del sueño</a> se definene como tonos galácticos de la creación<c>.</c></p>

          <?=_doc_est::lis('api.hol_ton',[ 
            'atr'=>['ide','nom','des','acc'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

        </section>
        <!-- O.E. de la Aventura -->
        <h3 id="<?="_{$nav[2]['02']['04']->pos}-"?>"><?=_doc::let($nav[2]['02']['04']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>enc#_03-12-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_ton',[ 
            'atr'=>['ide','ond_nom','ond_pos','ond_pod','ond_man'], 'tit_cic'=>['ond_enc'], 'cic'=>['tit'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>
          
        </section>        
        <!-- pulsar dimensional -->
        <h3 id="<?="_{$nav[2]['02']['05']->pos}-"?>"><?=_doc::let($nav[2]['02']['05']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_ton_dim', [], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
          
        </section>
        <!-- pulsar matiz -->
        <h3 id="<?="_{$nav[2]['02']['06']->pos}-"?>"><?=_doc::let($nav[2]['02']['06']->nom)?></h3>
        <section>

          <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_ton_mat', [ ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
          
        </section>
      </section>  
      <!-- 20 : sellos solares -->
      <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>
      <section>
        <!-- signos direccionales -->
        <h3 id="<?="_{$nav[2]['03']['01']->pos}-"?>"><?=_doc::let($nav[2]['03']['01']->nom)?></h3>
        <section>

          <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_sel_cic_dir',[ ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          <!-- desarrollo del ser -->
          <h4 id="<?="_{$nav[3]['03']['01']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['01']['01']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel',[ 
              'atr'=>['ide','may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>
          <!-- etapas evolutivas de la mente -->
          <h4 id="<?="_{$nav[3]['03']['01']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['01']['02']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cic_men',[ 
              'atr'=>['sel','nom','des','lec'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>
          <!-- familias ciclicas -->
          <h4 id="<?="_{$nav[3]['03']['01']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['01']['03']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel',[ 
              'atr'=>['ide','may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>

        </section>
        <!-- colocacion cromática -->
        <h3 id="<?="_{$nav[2]['03']['02']->pos}-"?>"><?=_doc::let($nav[2]['03']['02']->nom)?></h3>
        <section>
          
          <p>Consiste en ordenar secuencialmente los sellos comenzando desde 20 o 00 a 19.</p>
          
          <?=_doc_est::lis('api.hol_sel_cod',[ 
            'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <!-- familias -->
          <h4 id="<?="_{$nav[3]['03']['02']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['02']['01']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cro_fam',[ 
              'atr'=>['ide','nom','pla','hum','des','sel'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>

          <!-- clanes -->
          <h4 id="<?="_{$nav[3]['03']['02']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['02']['02']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_cro_ele',[ 
              'atr'=>['ide','nom','col','men','des','sel'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>

        </section>
        <!-- colocación armónica -->
        <h3 id="<?="_{$nav[2]['03']['03']->pos}-"?>"><?=_doc::let($nav[2]['03']['03']->nom)?></h3>
        <section>

          <p>Consiste en ordenar secuencialmente los sellos comenzando desde 01 a 20.</p>

          <?=_doc_est::lis('api.hol_sel',[ 
            'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <!-- razas -->
          <h4 id="<?="_{$nav[3]['03']['03']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['03']['01']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_arm_raz',[ 
              'atr'=>['ide','nom','pod','dir','sel'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])?>

          </section>

          <!-- células -->
          <h4 id="<?="_{$nav[3]['03']['03']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['03']['02']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_arm_cel',[ 
              'atr'=>['ide','nom','fun','pod','des','sel'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>

        </section>            
        <!-- parejas del oráculo -->
        <h3 id="<?="_{$nav[2]['03']['04']->pos}-"?>"><?=_doc::let($nav[2]['03']['04']->nom)?></h3>
        <section>

          <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>

          <!-- analogos -->
          <h4 id="<?="_{$nav[3]['03']['04']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['04']['01']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_par_ana',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>

          <!-- antipodas -->
          <h4 id="<?="_{$nav[3]['03']['04']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['04']['02']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_par_ant',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>

          <!-- ocultos -->
          <h4 id="<?="_{$nav[3]['03']['04']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['04']['03']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_par_ocu',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>

        </section>                  
        <!-- holon Solar -->
        <h3 id="<?="_{$nav[2]['03']['05']->pos}-"?>"><?=_doc::let($nav[2]['03']['05']->nom)?></h3>
        <section>

          <p>El código 0-19</p>              

          <?=_doc_est::lis('api.hol_sel_cod',[ 
            'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <!-- orbitas planetarias -->
          <h4 id="<?="_{$nav[3]['03']['05']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['05']['01']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_sol_pla',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>
          <!-- células solares -->
          <h4 id="<?="_{$nav[3]['03']['05']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['05']['02']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_sol_cel',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>
          <!-- circuitos de telepatía -->
          <h4 id="<?="_{$nav[3]['03']['05']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['05']['03']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_sol_cir',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>              

        </section>
        <!-- holon planetario -->
        <h3 id="<?="_{$nav[2]['03']['06']->pos}-"?>"><?=_doc::let($nav[2]['03']['06']->nom)?></h3>
        <section>  
          
          <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_sel_cod',[ 
            'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <!-- centros galácticos -->
          <h4 id="<?="_{$nav[3]['03']['06']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['06']['01']->nom)?></h4>
          <section>

            <?=_doc_est::lis('api.hol_sel_pla_cen',[  ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>

          <!-- flujos de la fuerza-g -->
          <h4 id="<?="_{$nav[3]['03']['06']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['06']['02']->nom)?></h4>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_doc_est::lis('api.hol_sel_pla_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>              

        </section>
        <!-- holon humano -->
        <h3 id="<?="_{$nav[2]['03']['07']->pos}-"?>"><?=_doc::let($nav[2]['03']['07']->nom)?></h3>
        <section>

          <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_doc_est::lis('api.hol_sel_cod',[ 
            'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] 
            ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
            ])
          ?>

          <!-- Centros Galácticos -->
          <h4 id="<?="_{$nav[3]['03']['07']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['01']->nom)?></h4>
          <section>

            <?=_doc_est::lis('api.hol_sel_hum_cen',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>

          <!-- Extremidades -->
          <h4 id="<?="_{$nav[3]['03']['07']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['02']->nom)?></h4>
          <section>

            <?=_doc_est::lis('api.hol_sel_hum_ext',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>           
          
          <!-- dedos -->
          <h4 id="<?="_{$nav[3]['03']['07']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['03']->nom)?></h4>
          <section>            
            
            <?=_doc_est::lis('api.hol_sel_hum_ded',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>

          <!-- lados -->
          <h4 id="<?="_{$nav[3]['03']['07']['04']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['04']->nom)?></h4>
          <section>
            
            <?=_doc_est::lis('api.hol_sel_hum_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

          </section>              

        </section>

      </section>
      <!-- 28 : días del giro solar -->
      <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>
      <section>

        <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

        <?=_doc_est::lis('api.hol_lun',[ 
          'atr'=>['ide','arm','rad','ato_des'] 
          ], [ 
          'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
          ])
        ?>

        <!-- 4 heptadas -->
        <h3 id="<?="_{$nav[2]['04']['01']->pos}-"?>"><?=_doc::let($nav[2]['04']['01']->nom)?></h3>
        <section>

          <p>En <a href="<?=$_bib?>lun#_02-07-" target="_blank">las <n>13</n> lunas en movimiento</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>" target="_blank">el Telektonon</a><c>.</c></p>

          <p>En <a href="<?=$_bib?>" target="_blank">el átomo del tiempo</a><c>.</c></p>

        </section>

      </section>
      <!-- 52 : posiciones del castillo-g -->
      <h2 id="<?="_{$nav[1]['05']->pos}-"?>"><?=_doc::let($nav[1]['05']->nom)?></h2>
      <section>

        <!-- -->
        <h3 id="<?="_{$nav[2]['05']['01']->pos}-"?>"><?=_doc::let($nav[2]['05']['01']->nom)?></h3>
        <section>

        </section>

      </section>  
      <!-- 260 : tzolkin -->
      <h2 id="<?="_{$nav[1]['06']->pos}-"?>"><?=_doc::let($nav[1]['06']->nom)?></h2>
      <section>

        <!-- -->
        <h3 id="<?="_{$nav[2]['06']['01']->pos}-"?>"><?=_doc::let($nav[2]['06']['01']->nom)?></h3>
        <section>

          <!-- -->
          <h4 id="<?="_{$nav[3]['06']['01']['01']->pos}-"?>"><?=_doc::let($nav[3]['06']['01']['01']->nom)?></h4>
          <section>

          </section>            

        </section>

      </section>
      <!-- 364 : banco-psi -->
      <h2 id="<?="_{$nav[1]['07']->pos}-"?>"><?=_doc::let($nav[1]['07']->nom)?></h2>
      <section>

        <!-- -->
        <h3 id="<?="_{$nav[2]['07']['01']->pos}-"?>"><?=_doc::let($nav[2]['07']['01']->nom)?></h3>
        <section>

        </section>

      </section>    
      <?php
      break;
    // libros html
    default:
      // cargo directorio para imágenes del libro
      $_dir = $_uri->dir();
      if( !empty( $rec = $_uri->rec($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){

        include( $rec );
      }
      else{ echo "
        <p class='err'><c>{-_-}</c> No existe el archivo <c>'</c><b class='ide'>{$val}</b><>'</c></p>";
      }       
      break;
    }      
    return $_;
  }
  // diario : ciclos + firma galáctica
  public function val( array $_, object $_uri, array $nav, array $_val ) : array {

    $_bib = SYS_NAV."hol/bib/";

    // galáctico
    $_kin = _hol::_('kin', $_val['kin']);
    $_sel = _hol::_('sel',$_kin->arm_tra_dia);
    $_ton = _hol::_('ton',$_kin->nav_ond_dia);

    // solar
    $_psi = _hol::_('psi', $_val['psi']);

    switch( $_uri->art ){
    // ciclos del tiempo
    case 'dia': 
      ?>
      <!-- orden sincronico : 260 -->
      <h2 id="<?="_{$nav[1]['01']->pos}-"?>"><?=_doc::let($nav[1]['01']->nom)?></h2>
      <section>
        <!-- Kin -->
        <h3 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h3>
        <section>
          <!-- ficha del encantamiento -->
          <h4 id="<?="_{$nav[3]['01']['01']['01']->pos}-"?>"><?=_doc::let($nav[3]['01']['01']['01']->nom)?></h4>
          <section>

            <?= _hol_fic::kin('enc',$_kin) ?>

            <br>

            <p>Para tener una idea más clara sobre el significado de los encantamientos del kin<c>,</c> ver <a href='<?=$_bib?>enc#_03-17-' target='_blank'>el Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

            <p>Para navegar entre las trayectorias armónicas<c>,</c> génesis de los castillos<c>,</c> ondas encantadas y células del tiempo<c>,</c> ver los <a href='<?=$_bib?>enc#_04-' target='_blank'>Índices del Libro del Kin</a> en el Encantamiento del Sueño<c>...</c></p>

          </section>
          <!-- 4 + 1 : parejas -->
          <h4 id="<?="_{$nav[3]['01']['01']['02']->pos}-"?>"><?=_doc::let($nav[3]['01']['01']['02']->nom)?></h4>
          <section>

            <?= _hol_tab::kin('par',[ 
            'ide'=>$_kin->ide,
            'sec'=>[ 'par'=>0 ],
            'pos'=>[ 'ima'=>'api.hol_kin.ide' ]
            ],[
            'sec'=>[ 'class'=>"mar_ver-2 mar_hor-aut" ],
            'pos'=>[ 'style'=>"width:5rem; height:5rem;" ]
            ])?>
            <!-- Descripciones -->
            <h5 id="<?="_{$nav[4]['01']['01']['02']['01']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['02']['01']->nom)?></h5>
            <section>

              <p>Para realizar una lectura del oráculo<c>,</c> consulta la <a href='<?=$_bib?>enc#_02-03-06-01-' target='_blank'>Guía del Oráculo</a> en el Encantamiento del Sueño<c>...</c></p>            

              <?= _hol_fic::kin('par',$_kin) ?>

            </section>
            <!-- Lecturas diarias -->
            <h5 id="<?="_{$nav[4]['01']['01']['02']['02']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['02']['02']->nom)?></h5>
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
            <h5 id="<?="_{$nav[4]['01']['01']['02']['03']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['02']['03']->nom)?></h5>
            <section>

              <p>Puedes buscar <dfn title='Cuando dos kines pertenecen a un mismo grupo comparten propiedades, por lo que su nivel de sincronización aumenta...'>sincronías posicionales</dfn> relacionando las ubicaciones de cada pareja en los ciclos del tzolkin<c>:</c></p>        

              <p>Dos o más kines pueden pertenecer un mismo grupo<c>.</c> Utiliza la siguente tabla para detectar cuáles son esas coincidencias y hacia dónde te llevan<c>...</c></p>

              <?= _hol_inf::kin('par',$_kin,[ 'atr'=>"cic" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>

            </section>  
            <!-- Sincronometría del holon -->
            <h5 id="<?="_{$nav[4]['01']['01']['02']['04']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['02']['04']->nom)?></h5>
            <section>

              <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

              <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

              <br>

              <?= _hol_inf::kin('par',$_kin,[ 'atr'=>"gru" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>

            </section>
            
          </section>
          <!-- 4 x 52:13 nave del tiempo -->
          <h4 id="<?="_{$nav[3]['01']['01']['03']->pos}-"?>"><?=_doc::let($nav[3]['01']['01']['03']->nom)?></h4>
          <section>
            <!-- x52 : Castillo Fractal -->  
            <h5 id="<?="_{$nav[4]['01']['01']['03']['01']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['03']['01']->nom)?></h5>
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
            <h5 id="<?="_{$nav[4]['01']['01']['03']['02']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['03']['02']->nom)?></h5>
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
          </section>
          <!-- 13 x 20:4 Giro Galáctico -->
          <h4 id="<?="_{$nav[3]['01']['01']['04']->pos}-"?>"><?=_doc::let($nav[3]['01']['01']['04']->nom)?></h4>
          <section>
            <!-- x20 : Trayectoria Armónica -->  
            <h5 id="<?="_{$nav[4]['01']['01']['04']['01']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['04']['01']->nom)?></h5>
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
            <h5 id="<?="_{$nav[4]['01']['01']['04']['01']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['04']['01']->nom)?></h5>
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
          <h4 id="<?="_{$nav[3]['01']['01']['05']->pos}-"?>"><?=_doc::let($nav[3]['01']['01']['05']->nom)?></h4>
          <section>
            <!-- x65 : Estación Galáctica -->
            <h5 id="<?="_{$nav[4]['01']['01']['05']['01']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['05']['01']->nom)?></h5>
            <section>
              <?php
              $_est = _hol::_('kin_cro_est',$_kin->cro_est);
              ?>

              <nav>
                <p>Ver <a href='<?=$_bib?>fac#_04-' target='_blank'>Guardianes Direccionales Evolutivos</a> en el Factor Maya</p>
              </nav>    

            </section>
            <!-- x5 : Elemento Cromático -->  
            <h5 id="<?="_{$nav[4]['01']['01']['05']['01']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['05']['01']->nom)?></h5>
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
          <h4 id="<?="_{$nav[3]['01']['01']['06']->pos}-"?>"><?=_doc::let($nav[3]['01']['01']['06']->nom)?></h4>
          <section>
          </section>
        </section>
        <!-- Sello Solar -->
        <h3 id="<?="_{$nav[2]['01']['02']->pos}-"?>"><?=_doc::let($nav[2]['01']['02']->nom)?></h3>
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
        <h3 id="<?="_{$nav[2]['01']['03']->pos}-"?>"><?=_doc::let($nav[2]['01']['03']->nom)?></h3>
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
      <!-- orden cíclico : 365 -->
      <h2 id="<?="_{$nav[1]['02']->pos}-"?>"><?=_doc::let($nav[1]['02']->nom)?></h2>
      <section>
        <!-- PsiCronos -->
        <h3 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h3>
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
    return $_;
  }
  // usuario : transitos + firma galàctica
  public function usu( array $_, object $_uri, array $nav ) : array {
    global $_usu;

    return $_;
  }
  // tableros 
  public function tab( array $_, object $_uri, array $_val ) : array {
        
    $art_tab = explode('-',$_uri->art);
    if( isset($art_tab[1]) && method_exists("_hol_tab",$ide = $art_tab[0]) ){
      
      // operadores del tablero
      $_tab =  _app::tab('hol',str_replace('-','_',$_uri->art));

      $tab_ide = "hol.{$ide}";
      $tab_ele = [];
      $tab_ope = !empty($_tab->ope) ? $_tab->ope : [];
      $tab_opc = !empty($_tab->opc) ? $_tab->opc : [];

      // fecha => muestro listado por ciclos      
      if( !empty( $_val['fec'] ) ){

        // joins
        if( in_array($ide,['kin','psi']) ){
          // cargo estructuras
          $tab_ope['est'] = [ 'api'=>[ "fec", "hol_kin", "hol_psi" ] ];
          // cargo datos
          $tab_ope['dat'] = _hol::dat( $ide, $_val );
          // acumulados
          $tab_ope['dat_acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1 ];
        }
        // valor seleccionado
        $tab_ope['dat_pos'] = $_val;
      }
      
      // operadores del tablero
      $_ope = _obj::nom(_doc_tab::$OPE,'ver',['cue','dat','opc']);
      foreach( $_ope as $ope_ide => $ope_tab ){
        if( 
          !empty( $htm = _doc_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele, ...$tab_opc ) ) 
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
      $_ope = _doc_tab::$OPE['lis'];
      $_['win']['est'] = [ 
        'ico' => $_ope['ico'], 
        'nom' => $_ope['nom'],
        'art' => [ 'style'=>"max-width: 55rem; height: 90vh;" ],
        'htm' => _doc_tab::ope('lis',"api.hol_{$ide}",$tab_ope)
      ];          
      // imprimo tablero en página principal
      echo _hol_tab::$ide($art_tab[1], $tab_ope, [ 
        'pos'=>[ 'onclick'=>"_doc_tab.dat('mar',this);" ]
      ], ...$tab_opc);
    }
    else{
      echo _doc::let("Error: No existe el tablero del Holon solicitado con '$_uri->art'");
    }
    return $_;
  }  
}