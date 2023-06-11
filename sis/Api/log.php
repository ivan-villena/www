<?php    
    
// Ejecucion desde consola
function adm_log() : string {
  
  $_ = "<h2>hola desde php<c>!</c></h2>";

  foreach( Dat::sql_est('nom','var-tip','tab') as $est ){

    $_ .= "RENAME TABLE `$est` TO `".str_replace('sis-','var-',$est)."`;<br>";

  }

  /* Recorrer tablas de un esquema:

  foreach( sql::est('nom','hol-uni','tab') as $est ){

    $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";

  } 
  */
  
  /*  Invocando funciones 
    include("./_sql/hol/sel.php");
    $_ = hol_sel_par_gui();
  */
  
  return $_;
}