<?php

  // aplicacion
  class _hol_app {

    // main : app.cab.art/val
    public function __construct( array &$_, _app &$_app ){

      global $_api, $_usu;

      $_uri = $_api->app_uri;

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
        var \$_hol_app = new _hol_app(".( $_uri->cab == 'tab' ? "{ val : "._obj::cod($_val)." }" : "" ).");
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

          <?=_app_est::lis('api.hol_rad',[ 
            'atr'=>['ide','nom','pod'] 
          ], [ 
            'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
          ])?>
          
          <h3 id="<?="_{$nav[2]['01']['01']->pos}-"?>"><?=_doc::let($nav[2]['01']['01']->nom)?></h3>
          <section>

            <p>En el <a href="<?=$_bib?>tel#_02-06-" target="_blank">telektonon</a> se representan como <cite>sellos de la profecía</cite><c>.</c> Estos sellos describen el desarrollo de los acontecimientos para el fin de ciclo y la transición al nuevo paradigma resonante<c>.</c></p>

            <p>Para la lectura anual se crean 3 oráculos en base a los kines que codifican los ciclos del sincronario <c>(</c> familia portal<c>:</c> abren los portales codificando el inicio de los anillos solares<c>;</c> y familia señal<c>:</c> descifran el misterio codificando los días fuera del tiempo<c>.</c> Ver <a href="<?=$_bib?>enc#_03-14-" target="_blank">el encantamiento del sueño</a> <c>)</c><c>.</c></p>

            <?=_app_est::lis('api.hol_rad',[ 
              'atr'=>['ide','tel','tel_des','tel_año','tel_ora_año','tel_ora_ani','tel_ora_gen'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <p>En el <a href="<?=$_bib?>rin#_02-05-01-" target="_blank">Proyecto Rinri</a> se amplía el contenido de los sellos de la profecía del telektonon<c>.</c></p>

            <p>En este caso se utilizan los sellos como liberadores de plasma en la activación del banco-psi <c>(</c> el campo resonante de la tierra <c>)</c> durante la transición biósfera<c>-</c>noosfera<c>.</c></p>

            <?=_app_est::lis('api.hol_rad',[ 
              'atr'=>['ide','tel_des','tel_año','rin_des'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

          </section>

          <h3 id="<?="_{$nav[2]['01']['02']->pos}-"?>"><?=_doc::let($nav[2]['01']['02']->nom)?></h3>
          <section>

            <p>En el <a href="<?=$_bib?>ato#_03-01-" target="_blank">átomo del tiempo</a> se establecen los principios y componentes de los plasmas en el marco de la energía o electricidad cósmica<c>.</c></p>

            <?=_app_est::lis('api.hol_rad',[ 
              'atr'=>['ide','nom','col','pla_qua','pla_pod','pla_ene','pla_fue_pre','pla_fue_pos'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>              

            <p>Desde este paradigma los plasmas son <q>componenees electrónicos</q> constituídos por la combinación de <n>12</n> líneas electrónicas de fuerza que convergen en <n>6</n> tipos de electricidad clasificadas según la cantidad de cargas positivas o negativas que contengan<c>.</c></p>

            <p>Los <n>6</n> tipos de electricidad son<c>:</c></p>

            <?=_app_est::lis('api.hol_rad_pla_ele',[ 
              'atr'=>['ide','cod','nom','des'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <p>Los <n>12</n> tipos de líneas electrónicas<c>:</c></p>

            <?=_app_est::lis('api.hol_rad_pla_fue',[ 
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

            <?=_app_est::lis('api.hol_rad',[ 
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

            <?=_app_est::lis('api.hol_ton',[ 
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

            <?=_app_est::lis('api.hol_ton_sim',[ 
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

            <?=_app_est::lis('api.hol_ton',[ 
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

            <?=_app_est::lis('api.hol_ton',[ 
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

            <?=_app_est::lis('api.hol_ton_dim', [], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
            
          </section>
          <!-- pulsar matiz -->
          <h3 id="<?="_{$nav[2]['02']['06']->pos}-"?>"><?=_doc::let($nav[2]['02']['06']->nom)?></h3>
          <section>

            <p>En el <a href="<?=$_bib?>enc#_03-13-" target="_blank">Encantamiento del sueño</a><c>.</c></p>

            <?=_app_est::lis('api.hol_ton_mat', [ ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>
            
          </section>
        </section>  
        <!-- 20 : sellos solares -->
        <h2 id="<?="_{$nav[1]['03']->pos}-"?>"><?=_doc::let($nav[1]['03']->nom)?></h2>
        <section>
          <!-- signos direccionales -->
          <h3 id="<?="_{$nav[2]['03']['01']->pos}-"?>"><?=_doc::let($nav[2]['03']['01']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>

            <?=_app_est::lis('api.hol_sel_cic_dir',[ ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            <!-- desarrollo del ser -->
            <h4 id="<?="_{$nav[3]['03']['01']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['01']['01']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel',[ 
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

              <?=_app_est::lis('api.hol_sel_cic_men',[ 
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

              <?=_app_est::lis('api.hol_sel',[ 
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
            
            <?=_app_est::lis('api.hol_sel_cod',[ 
              'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <!-- familias -->
            <h4 id="<?="_{$nav[3]['03']['02']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['02']['01']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_cro_fam',[ 
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

              <?=_app_est::lis('api.hol_sel_cro_ele',[ 
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

            <?=_app_est::lis('api.hol_sel',[ 
              'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <!-- razas -->
            <h4 id="<?="_{$nav[3]['03']['03']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['03']['01']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_arm_raz',[ 
                'atr'=>['ide','nom','pod','dir','sel'] 
                ], [ 
                'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])?>

            </section>

            <!-- células -->
            <h4 id="<?="_{$nav[3]['03']['03']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['03']['02']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_arm_cel',[ 
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

              <?=_app_est::lis('api.hol_sel_par_ana',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>

            <!-- antipodas -->
            <h4 id="<?="_{$nav[3]['03']['04']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['04']['02']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_par_ant',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>

            <!-- ocultos -->
            <h4 id="<?="_{$nav[3]['03']['04']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['04']['03']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_par_ocu',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>

          </section>                  
          <!-- holon Solar -->
          <h3 id="<?="_{$nav[2]['03']['05']->pos}-"?>"><?=_doc::let($nav[2]['03']['05']->nom)?></h3>
          <section>

            <p>El código 0-19</p>              

            <?=_app_est::lis('api.hol_sel_cod',[ 
              'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <!-- orbitas planetarias -->
            <h4 id="<?="_{$nav[3]['03']['05']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['05']['01']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_sol_pla',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>
            <!-- células solares -->
            <h4 id="<?="_{$nav[3]['03']['05']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['05']['02']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_sol_cel',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>
            <!-- circuitos de telepatía -->
            <h4 id="<?="_{$nav[3]['03']['05']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['05']['03']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_sol_cir',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>              

          </section>
          <!-- holon planetario -->
          <h3 id="<?="_{$nav[2]['03']['06']->pos}-"?>"><?=_doc::let($nav[2]['03']['06']->nom)?></h3>
          <section>  
            
            <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_app_est::lis('api.hol_sel_cod',[ 
              'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <!-- centros galácticos -->
            <h4 id="<?="_{$nav[3]['03']['06']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['06']['01']->nom)?></h4>
            <section>

              <?=_app_est::lis('api.hol_sel_pla_cen',[  ], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>

            <!-- flujos de la fuerza-g -->
            <h4 id="<?="_{$nav[3]['03']['06']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['06']['02']->nom)?></h4>
            <section>

              <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

              <?=_app_est::lis('api.hol_sel_pla_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>              

          </section>
          <!-- holon humano -->
          <h3 id="<?="_{$nav[2]['03']['07']->pos}-"?>"><?=_doc::let($nav[2]['03']['07']->nom)?></h3>
          <section>

            <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

            <?=_app_est::lis('api.hol_sel_cod',[ 
              'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] 
              ], [ 
              'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] 
              ])
            ?>

            <!-- Centros Galácticos -->
            <h4 id="<?="_{$nav[3]['03']['07']['01']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['01']->nom)?></h4>
            <section>

              <?=_app_est::lis('api.hol_sel_hum_cen',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>

            <!-- Extremidades -->
            <h4 id="<?="_{$nav[3]['03']['07']['02']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['02']->nom)?></h4>
            <section>

              <?=_app_est::lis('api.hol_sel_hum_ext',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>           
            
            <!-- dedos -->
            <h4 id="<?="_{$nav[3]['03']['07']['03']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['03']->nom)?></h4>
            <section>            
              
              <?=_app_est::lis('api.hol_sel_hum_ded',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>

            <!-- lados -->
            <h4 id="<?="_{$nav[3]['03']['07']['04']->pos}-"?>"><?=_doc::let($nav[3]['03']['07']['04']->nom)?></h4>
            <section>
              
              <?=_app_est::lis('api.hol_sel_hum_res',[], [ 'lis'=>[ 'class'=>"mar-aut mar_aba-2" ] ])?>

            </section>              

          </section>

        </section>
        <!-- 28 : días del giro solar -->
        <h2 id="<?="_{$nav[1]['04']->pos}-"?>"><?=_doc::let($nav[1]['04']->nom)?></h2>
        <section>

          <p>En <a href="<?=$_bib?>" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>

          <?=_app_est::lis('api.hol_lun',[ 
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
        $_dir = _api::uri_dir($_uri);
        if( !empty( $rec = _api::uri_rec($val = "php/$_uri->esq/$_uri->cab/$_uri->art") ) ){

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

                <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"pro" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>

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

                <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"cic" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>

              </section>  
              <!-- Sincronometría del holon -->
              <h5 id="<?="_{$nav[4]['01']['01']['02']['04']->pos}-"?>"><?=_doc::let($nav[4]['01']['01']['02']['04']->nom)?></h5>
              <section>

                <p>También puedes determinar la sincronometría en los flujos del oráculo<c>,</c> practicando <a href='<?=$_bib?>tel#_02-03-04-' target='_blank'>el <n>4</n><c>°</c> nivel<c>,</c> juego del oráculo</a> en el tablero del Telektonon<c>...</c></p>

                <p>En la siguiente tabla se muestran los valores respectivos para cada posición del oráculo<c>:</c></p>

                <br>

                <?= _hol_lis::kin('par',[ 'dat'=>$_kin, 'atr'=>"gru" ],[ 'lis'=>['class'=>"anc-100"] ]) ?>

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
            $tab_ope['val']['acu'] = [ 'pos'=>1, 'mar'=>1, 'ver'=>1, 'opc'=>1 ];
          }
          // valor seleccionado
          $tab_ope['val']['pos'] = $_val;
        }
        
        // operadores del tablero
        $_ope = _obj::nom(_app_tab::$OPE,'ver',['cue','val','opc']);
        foreach( $_ope as $ope_ide => $ope_tab ){
          if( 
            !empty( $htm = _app_tab::ope($ope_ide, $tab_ide, $tab_ope, $tab_ele, ...$tab_opc ) ) 
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
        echo _hol_tab::$ide($art_tab[1], $tab_ope, [ 
          'pos'=>[ 'onclick'=>"_app_tab.val('mar',this);" ]
        ], ...$tab_opc);
      }
      else{
        echo _doc::let("Error: No existe el tablero del Holon solicitado con '$_uri->art'");
      }
      return $_;
    }  
  } 

  // Valores : fecha + kin + psi
  class _hol_val {

    static string $IDE = "_hol_val-";
    static string $EJE = "_hol_val.";

    // fecha + ns:kin
    static function fec( array $dat ) : string {
      $_eje = self::$EJE."fec";

      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : _hol::_('kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : _hol::_('psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      

      $_ = "
      <!-- Fecha del Calendario -->
      <form class='val' ide = 'fec'>

        <div class='atr'>
          "._doc_fec::ope('dia', $_fec, [ 'name'=>"fec" ])."
          "._doc::ico('dat_ver',[ 
            'eti'=>"button", 'type'=>"submit", 'title'=>'Buscar en el Calendario...', 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);"
          ])."
        </div>

      </form>

      <!-- Fecha del Sincronario -->
      <form class='val' ide = 'sin'>
        
        <div class='atr'>

          <label>N<c>.</c>S<c>.</c></label>

          "._doc_num::ope('int', $_sin[0], [ 'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."])."

          <c>.</c>
          "._doc_opc::val( _hol::_('ani'), [
            'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): 52 ciclos de 364+1 días...", 'val'=>$_sin[1] ], 
            'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
          ])."
          <c>.</c>
          "._doc_opc::val( _hol::_('psi_lun'), [
            'eti'=>[ 'name'=>"lun", 'title'=>"Giro Lunar (mes): 13 ciclos de 28 días...", 'val'=>$_sin[2] ],
            'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
          ])."
          <c>.</c>
          "._doc_opc::val( _hol::_('lun'), [ 
            'eti'=>[ 'name'=>"dia", 'title'=>"Día Lunar : 1 día de 28 que tiene la luna...", 'val'=>$_sin[3] ], 
            'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
          ])."          
          <c class='sep'>:</c>
      
          <n name='kin'>$_kin->ide</n>

          "._hol::ima("kin",$_kin,['class'=>"mar_hor-1", 'style'=>'min-width:3em; height:3em;'])."
          
        </div>

        "._doc::ico('dat_ver',[ 
          'eti'=>"button", 'title'=>'Buscar en el Sincronario', 'type'=>"submit", 'onclick'=>"$_eje(this);" 
        ])."

      </form>";

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
              <img src='".SYS_REC."hol/bib/tel/{$ide}/{$pos}-1.jpg' alt='Carta ' class='mar_der-1' style='width:24rem; height: 30rem;'>
              <img src='".SYS_REC."hol/bib/tel/{$ide}/{$pos}-2.jpg' alt='Carta ' class='mar_izq-1' style='width:24rem; height: 30rem;'>              
            </div>
          </figure>";
        }
        $_ = _doc_lis::bar( $_, $ele);
        break;
      }
      return is_array($_) ? _app_dat::lis( $_, $est, $lis_tip, $ele ) : $_;
    } 
  }

  // Diario : kin + psi + sin + umb
  class _hol_dia {

    static string $IDE = "_hol_dia-";
    static string $EJE = "_hol_dia.";

    // ciclos del orden sincronico
    static function kin( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ = [];
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_kin = _hol::_('kin',$dat);
      $_sel = _hol::_('sel',$_kin->arm_tra_dia);
      $_ton = _hol::_('ton',$_kin->nav_ond_dia);
      $_kin_atr = _dat::atr('api',"hol_kin");
      
      $_est = [
        'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
        'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
        'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
        'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
        'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
        'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ],
      ];
      
      $_[0] = [ 'ite'=>"Nave del Tiempo", 'lis'=>[] ];
      foreach( [ 'nav_cas'=>52, 'nav_ond'=>13 ] as $atr => $cue ){ 
        $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
        $_[0]['lis'] []= 
        
        _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

        <div class='tam-cre'>
          <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
          <p>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>            
        </div>";          
      }        

      $_[1] = [ 'ite'=>"Giro Galáctico", 'lis'=>[] ];
      foreach( [ 'arm_tra'=>13, 'arm_tra_dia'=>20, 'arm_cel'=>65, 'arm_cel_dia'=>4 ] as $atr => $cue ){ 
        $est = isset($_est[$atr]['est']) ? $_est[$atr]['est'] : "kin_$atr"; 
        $_dat = _hol::_($est,$_kin->$atr); 
        $_[1]['lis'] []= 
        
        _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

        <div class='tam-cre'>
          <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          <p>"._doc::let( _app_dat::val('des',"api.hol_{$est}",$_dat) )."</p>
          <p>"._doc_num::ope('ran',$_kin->$atr,[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>          
        </div>";
      }

      $_[2] = [ 'ite'=>"Giro Espectral", 'lis'=>[] ];
      foreach( [ 'cro_est'=>65, 'cro_ele'=>5 ] as $atr => $cue ){ 
        $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
        
        $_[2]['lis'] []= 
        
          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."

          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
            <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
            <p>"._doc_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>
          </div>          
        ";          
      } 

      $_[3] = [ 'ite'=>"Holon Solar", 'lis'=>[] ];
      foreach( ['sol_pla','sol_cel','sol_cir'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[3]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          </div>              
        ";          
      }

      $_[4] = [ 'ite'=>"Holon Planetario", 'lis'=>[] ];
      foreach( ['pla_cen','pla_hem','pla_mer'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[4]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          </div>              
        ";
      }

      $_[5] = [ 'ite'=>"Holon Humano", 'lis'=>[] ];
      foreach( ['hum_cen','hum_ext','hum_ded','hum_mer'] as $atr ){ 
        $_dat = _hol::_($est = "sel_{$atr}",$_sel->$atr); 
        $_[5]['lis'] []= 

          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
          
          <div class='tam-cre'>
            <p>"._doc::let( _app_dat::val('nom',"api.hol_{$est}",$_dat) )."</p>
          </div>              
        ";
      }              

      $ope['lis-1'] = [ 'class'=>"ite" ];
      return _doc_lis::val($_,$ope);
    }    
    // ciclos del orden ciclico
    static function psi( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ = []; $esq = 'hol';
      if( !isset($ope['lis']) ) $ope['lis'] = [];

      $_psi = _hol::_('psi',$dat);
      $_lun = _hol::_('lun',$_psi->lun);
      $_rad = _hol::_('rad',$_psi->hep_dia);
      $ope['lis']['ide'] = 'psi';         

      $_[0] = [ 'ite'=>"Estación Solar", 'lis'=>[] ];
      $_est = _hol::_('psi_est',$_psi->est); 
      $_[0]['lis'] []= 
        
        _hol::ima("psi_est",$_est,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>"; 
      $_hep = _hol::_('psi_hep',$_psi->hep); 
      $_[0]['lis'] []= 
        
        _hol::ima("psi_hep",$_hep,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";

      $_[1] = [ 'ite'=>"Giro Lunar", 'lis'=>[] ];
      $_lun = _hol::_('psi_lun',$_psi->lun); 
      $_[1]['lis'] []= 
        
        _hol::ima("psi_lun",$_lun,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>";
      $_arm = _hol::_('lun_arm',_num::ran($_psi->hep,'4')); 
      $_[1]['lis'] []= 
        
        _hol::ima("lun_arm",$_arm,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";

      $_[2] = [ 'ite'=>"Héptada", 'lis'=>[] ];
      $_rad = _hol::_('rad',$_psi->hep_dia);
      $_[2]['lis'] []= 
        
        _hol::ima("rad",$_rad,['class'=>"tam-3 mar_der-1"])."

        <div>

        </div>
      ";        
      
      $ope['lis-1'] = [ 'class'=>"ite" ];
      return _doc_lis::val($_,$ope);
    }
  }
  
  // Usuario : ficha + tránsitos + firma galáctica
  class _hol_usu {
      
    // ficha
    static function fic( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;      
      $_fec = _api::_('fec',$_usu->fec);
      $_kin = _hol::_('kin',$_usu->kin);
      $_psi = _hol::_('psi',$_usu->psi);
      // sumatoria : kin + psi
      $sum = $_kin->ide + $_psi->tzo;

      // nombre + fecha : kin + psi
      $_ = "
      <section class='inf ren esp-ara'>

        <div>
          <p class='let-tit let-3 mar_aba-1'>"._doc::let("$_usu->nom $_usu->ape")."</p>
          <p>"._doc::let($_fec->val." ( $_usu->eda años )")."</p>
        </div>        

        <div class='val'>
          "._hol::ima("kin",$_kin,['class'=>"mar_hor-1"])."
          <c>+</c>
          "._hol::ima("psi",$_psi,['class'=>"mar_hor-1"])."
          <c>=></c>
          "._hol::ima("kin",$sum,['class'=>"mar_hor-1"])."
        </div>

      </section>";

      return $_;
    }    

    // tránsitos : listado
    static function cic_nav( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;
      foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }
      $opc_des = !in_array('not-des',$opc);
      // listado
      $_lis = [];
      foreach( _dat::get('_api.usu_cic') as $_arm ){
        $_lis_cic = [];
        foreach( _dat::get("_api.usu_cic_ani",[ 'ver'=>"`usu` = '{$_usu->ide}' AND `arm` = $_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){
          // ciclos lunares
          $_lis_lun = [];
          foreach( _dat::get("_api.usu_cic_lun",[ 'ver'=>"`usu` = '{$_usu->ide}' AND `ani` = $_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
            $_fec = _api::_('fec',$_lun->fec);
            $_lun_ton = _hol::_('ton',$_lun->ide);
            $_kin = _hol::_('kin',$_lun->kin);
            $nav = "<a href='http://localhost/hol/tab/kin-tzo/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>"._doc::let($_lun->sin)."</a>";
            $_lis_lun []= 
            "<div class='ite'>
              "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p>
                "._doc::let(intval($_lun_ton->ide)."° ciclo, ").$nav._doc::let(" ( $_fec->val ).")."
                <br>"._doc::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
              </p>              
            </div>
            <p class='mar-1 tex_ali-cen'>"._hol_des::kin('enc',$_kin)."</p>";
          }
          // ciclo anual
          $_fec = _api::_('fec',$_cic->fec);
          $_cas = _hol::_('cas',$_cic->ide);
          $_cas_ton = _hol::_('ton',$_cic->ton);
          $_cas_arm = _hol::_('cas_arm',$_cic->arm);            
          $_kin = _hol::_('kin',$_cic->kin);            
          $_lis_cic []= [
            'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
              "<div class='ite'>
                "._hol::ima("kin",$_kin,['class'=>"tam-6 mar_der-1"])."
                <p title = '$_cas->des'>
                  "._doc::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                  <br>"._doc::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                  <br>"._doc::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
                </p>
              </div>
              <p class='mar-1 tex_ali-cen'>"._hol_des::kin('enc',$_kin)."</p>"
            ],
            'lis'=>$_lis_lun
          ];
        }
        $_lis []= [ 'ite'=>$_arm->nom, 'lis'=>$_lis_cic ];
      }
      // configuro listado
      _ele::cla($ope['dep'],DIS_OCU);
      $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog_dep' ];
      return _doc_lis::val($_lis,$ope);
    }
    // tránsitos : informe
    static function cic_inf( array $ele = [], ...$opc ) : string {
      $dat = _usu::cic_dat();
      $_ = "
      <section>
        "._hol_usu::cic_inf_ani( $dat, $ele, ...$opc )."
        "._hol_usu::cic_inf_lun( $dat, $ele, ...$opc )."
        "._hol_usu::cic_inf_dia( $dat, $ele, ...$opc )."
      </section>"; 
      return $_;
    }// informe anual
    static function cic_inf_ani( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;      
      $_ani = $dat['ani'];
      $_cas_arm = _hol::_('cas_arm',$dat['ani']->arm);
      $_ani_arm = _dat::get('_api.usu_cic',['ver'=>"`ide` = $_ani->arm",'opc'=>"uni"]);
      $_ani_fec = _api::_('fec',$_ani->fec);      
      $_ani_ton = _hol::_('ton',$dat['ani']->ton);
      $_kin = _hol::_('kin',$_ani->kin);
      $_ = "
      <h3>Tránsito Anual</h3>

      <p>"._doc::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

      "._doc_num::ope('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

      <div class='ite mar_ver-1'>
        "._hol::ima("cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
        <div class='let-3'>
          <p class='let-tit'>"._doc::let($_ani_arm->nom)."</p>
          <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
          <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
          <p>"._doc_num::ope('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
        </div>
      </div>

      "._hol_fic::kin('enc',$_kin,[ 'ima'=>[] ])."

      ";
      return $_;
    }// informe lunar
    static function cic_inf_lun( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;
      $_lun = $dat['lun'];
      $_lun_fec = _api::_('fec',$_lun->fec);
      $_lun_ton = _hol::_('ton',$_lun->ide);
      $_kin = _hol::_('kin',$_lun->kin);
      $_ = "
      <h3>Tránsito Lunar</h3>

      <p>"._doc::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

      "._doc_num::ope('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

      <div class='ite mar_ver-1'>
        "._hol::ima("ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
        <div class='let-3'>
          <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
        </div>
      </div>


      "._hol_fic::kin('enc', $_kin, [ 'atr'=>[] ])."

      ";
      return $_;
    }// informe diario
    static function cic_inf_dia( array $dat, array $ele = [], ...$opc ) : string {
      global $_usu;
      $_dat = _hol::val( date('Y/m/d') );
      $_kin = _hol::_('kin',$dat['dia']->kin);

      $_ = "
      <h3>Tránsito Diario</h3>

      "._hol_fic::kin('enc',$_kin,[ 'ima'=>[] ])."

      ";
      return $_;
    }

    // firma galáctica
    static function val( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;

      return $_;
    }

    // relaciones
    static function rel( array $ope = [], ...$opc ) : string {
      $_ = "";
      global $_usu;

      return $_;
    }

  }