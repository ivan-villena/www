
<?php

// datos + articulo + paneles + ventanas
$art = _hol_art::sec($_uri,$_doc,$_dir);

// articulo
echo _doc_art::sec($art['htm']);

// botones de paneles
$_doc->art_nav = _doc_nav::ver($art,'pan');

// imprimo paneles    
foreach( $art['pan'] as $ide => $pan ){

  if(isset($pan['htm'])) $_doc->art_ini .= $pan['htm'];
}

// botones de modales : indice + pantallas ( fecha + est + tab )
$_doc->cab_nav = _doc_nav::ver($art,'win');

// imprimo modales
foreach( $art['win'] as $ide => $win ){ 
  
  $_doc->win .= _doc_art::win($ide,$win);
} 

// recursos del documento 
$_doc->css []= 'hol';
$_doc->jso []= 'hol';

// cargo datos en articulos de dato
$_doc->cod .= "
  var \$_hol = new _hol(".( $_uri->cab == 'dat' ? _dat::cod($_hol) : "" ).");
";

if( $_uri->cab == 'dat' ){
  // inicializo tablero
  $_doc->cod .= "  

  _doc_tab.act('dat');

  _doc_est.act('dat');

  _hol_tab.act('dat');
  ";
}

