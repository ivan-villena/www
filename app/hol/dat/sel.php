<!-- 20 Sellos Solares -->
<div>  

  <!-- 4 signos direccionales 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['01']->pos}-"?>"><?=api_tex::let($_nav[1]['01']->nom)?></h2>
    <p>En <a href="<?=$_bib?>fac#_04-04-02-03-" target="_blank">el Factor maya</a><c>.</c></p>
    <?=api_lis::est('hol.sel_cic_dir')?>
  </article>

  <!-- 5 familias ciclicas -->
  <article>
    <h2 id="<?="_{$_nav[1]['04']->pos}-"?>"><?=api_tex::let($_nav[1]['04']->nom)?></h2>
    <p>En <a href="<?=$_bib?>fac#_04-04-02-05-" target="_blank">el Factor maya</a><c>.</c></p>
    <?=api_lis::est('hol.sel',[ 'atr'=>['ide','nom_may','cic_dir','cic_luz_des'], 'tit_cic'=>['cic_luz'] ])?>
  </article>

  <!-- 3 etapas del desarrollo 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['02']->pos}-"?>"><?=api_tex::let($_nav[1]['02']->nom)?></h2>
    <p>En <a href="<?=$_bib?>fac#_04-04-02-04-" target="_blank">el Factor maya</a><c>.</c></p>
    <?=api_lis::est('hol.sel',[ 'atr'=>['ide','nom_may','cic_dir','cic_ser_des'], 'tit_cic'=>['cic_ser'] ])?>
  </article>

  <!-- 4 etapas evolutivas de la mente -->
  <article>
    <h2 id="<?="_{$_nav[1]['03']->pos}-"?>"><?=api_tex::let($_nav[1]['03']->nom)?></h2>
    <p>En <a href="<?=$_bib?>fac#_04-04-02-06-" target="_blank">el Factor maya</a><c>.</c></p>
    <?=api_lis::est('hol.kin_cro_est',[ 'atr'=>['sel','nom','des','des_det','fac_des'] ])?>
  </article>
  
  <!-- Colocacion cromática 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['05']->pos}-"?>"><?=api_tex::let($_nav[1]['05']->nom)?></h2>          
    <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>20</n> o <n>00</n> a <n>19</n><c>.</c></p>          
    <?=api_lis::est('hol.sel_cod',[ 'atr'=>['ide','ord','cro_ele_des'], 'tit_cic'=>['cro_ele'] ])?>
  </article>

  <!-- 5 familias terrestres -->        
  <article>
    <h2 id="<?="_{$_nav[1]['06']->pos}-"?>"><?=api_tex::let($_nav[1]['06']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-14-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_cro_fam',[ 'atr'=>['ide','nom','des_cod','des_fun','pla_cen','hum_cen','hum_ded','des','sel'] ])?>
  </article>

  <!-- 4 clanes cromáticos -->
  <article>
    <h2 id="<?="_{$_nav[1]['07']->pos}-"?>"><?=api_tex::let($_nav[1]['07']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-02-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_cro_ele',[ 'atr'=>['ide','nom','des_col','des_men','des','sel'] ])?>
  </article>
  
  <!-- Colocación armónica 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['08']->pos}-"?>"><?=api_tex::let($_nav[1]['08']->nom)?></h2>
    <p>Consiste en ordenar secuencialmente los sellos comenzando desde <n>1</n> a <n>20</n><c>.</c></p>
    <?=api_lis::est('hol.sel',[ 'atr'=>['ide','arm_cel_des'], 'tit_cic'=>['arm_cel'] ])?>
  </article>

  <!-- 4 Razas raiz cósmicas -->        
  <article>
    <h2 id="<?="_{$_nav[1]['09']->pos}-"?>"><?=api_tex::let($_nav[1]['09']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_arm_raz',[ 'atr'=>['ide','nom','des_pod','des_dir','sel'] ])?>
  </article>

  <!-- células del tiempo-->
  <article>
    <h2 id="<?="_{$_nav[1]['10']->pos}-"?>"><?=api_tex::let($_nav[1]['10']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_arm_cel',[ 'atr'=>['ide','nom','des_fun','des_pod','des','sel'] ])?>
  </article>

  <!-- Parejas del oráculo 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['11']->pos}-"?>"><?=api_tex::let($_nav[1]['11']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_02-03-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <p>En <a href="<?=$_bib?>tel#_02-03-04-" target="_blank">el Telektonon</a><c>.</c></p>
  </article>
  
  <!-- parejas analogas -->        
  <article>
    <h2 id="<?="_{$_nav[1]['12']->pos}-"?>"><?=api_tex::let($_nav[1]['12']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_02-03-06-06-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_par_ana')?>
  </article>

  <!-- parejas antípodas -->        
  <article>
    <h2 id="<?="_{$_nav[1]['13']->pos}-"?>"><?=api_tex::let($_nav[1]['13']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_02-03-06-04-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_par_ant')?>
  </article>

  <!-- parejas ocultas -->        
  <article>
    <h2 id="<?="_{$_nav[1]['14']->pos}-"?>"><?=api_tex::let($_nav[1]['14']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_02-03-06-05-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_par_ocu')?>
  </article>
  
  <!-- Holon Solar 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['15']->pos}-"?>"><?=api_tex::let($_nav[1]['15']->nom)?></h2>
    <p>El código 0-19</p>              
    <?=api_lis::est('hol.sel_cod',[ 'atr'=>['ide','sol_pla_des'], 'tit_cic'=>['sol_cel','sol_cir','sol_pla'] ])?>
  </article>

  <!-- orbitas planetarias -->        
  <article>
    <h2 id="<?="_{$_nav[1]['16']->pos}-"?>"><?=api_tex::let($_nav[1]['16']->nom)?></h2>
    <p>En <a href="<?=$_bib?>fac" target="_blank">el Factor Maya</a><c>.</c></p>
    <?=api_lis::est('hol.uni_sol_pla')?>
  </article>

  <!-- células solares -->        
  <article>
    <h2 id="<?="_{$_nav[1]['17']->pos}-"?>"><?=api_tex::let($_nav[1]['17']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-03-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.uni_sol_cel')?>
  </article>

  <!-- circuitos de telepatía -->        
  <article>
    <h2 id="<?="_{$_nav[1]['18']->pos}-"?>"><?=api_tex::let($_nav[1]['18']->nom)?></h2>
    <p>En <a href="<?=$_bib?>tel" target="_blank">Telektonon</a><c>.</c></p>
    <?=api_lis::est('hol.uni_sol_cir')?>
  </article>
  
  <!-- Holon planetario 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['19']->pos}-"?>"><?=api_tex::let($_nav[1]['19']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-07-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_cod',[ 'atr'=>['ide','nom','cro_fam','pla_mer','pla_mer_cod','pla_hem','pla_hem_cod'] ])?>
  </article>

  <!-- campos dimensionales -->        
  <article>
    <h2 id="<?="_{$_nav[1]['20']->pos}-"?>"><?=api_tex::let($_nav[1]['20']->nom)?></h2>
    <p></p>
  </article>

  <!-- centros galácticos -->        
  <article>
    <h2 id="<?="_{$_nav[1]['21']->pos}-"?>"><?=api_tex::let($_nav[1]['21']->nom)?></h2>
    <p></p>
    <?=api_lis::est('hol.uni_pla_cen')?>
  </article>

  <!-- flujos de la fuerza-g -->        
  <article>
    <h2 id="<?="_{$_nav[1]['22']->pos}-"?>"><?=api_tex::let($_nav[1]['22']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-16-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.uni_pla_res')?>
  </article>
  
  <!-- Holon humano 
  -->
  <article>
    <h2 id="<?="_{$_nav[1]['23']->pos}-"?>"><?=api_tex::let($_nav[1]['23']->nom)?></h2>
    <p>En <a href="<?=$_bib?>enc#_03-08-" target="_blank">el Encantamiento del Sueño</a><c>.</c></p>
    <?=api_lis::est('hol.sel_cod',[ 'atr'=>['ide','nom','hum_cen','hum_ext','hum_ded','hum_res'], 'tit_cic'=>['cro_ele'] ])?>
  </article>
  
  <!-- Centros Galácticos -->        
  <article>
    <h2 id="<?="_{$_nav[1]['24']->pos}-"?>"><?=api_tex::let($_nav[1]['24']->nom)?></h2>
    <?=api_lis::est('hol.uni_hum_cen')?>
  </article>

  <!-- Extremidades -->        
  <article>
    <h2 id="<?="_{$_nav[1]['25']->pos}-"?>"><?=api_tex::let($_nav[1]['25']->nom)?></h2>
    <?=api_lis::est('hol.uni_hum_ext')?>
  </article>                     

  <!-- dedos -->
  <article>
    <h2 id="<?="_{$_nav[1]['26']->pos}-"?>"><?=api_tex::let($_nav[1]['26']->nom)?></h2>
    <?=api_lis::est('hol.uni_hum_ded')?>
  </article>

  <!-- lados -->        
  <article>
    <h2 id="<?="_{$_nav[1]['27']->pos}-"?>"><?=api_tex::let($_nav[1]['27']->nom)?></h2>
    <?=api_lis::est('hol.uni_hum_res')?>
  </article>

</div>