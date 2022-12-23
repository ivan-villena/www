
<article>
  
  <h2>Buscar</h2>

  <p>En el siguiente listado podés encontrar los términos y sus significados por Libro<c>.</c></p>

  <?php 
  $_lis = [];
  // busco libros
  foreach( api_dat::get("app_art",[ 'ver'=>"esq = 'hol' AND cab = 'bib'" ]) as $_lib ){
    // busco terminos
    if( !empty( $_pal_lis = api_dat::get("app_ide",[ 
      'ver'=>"`esq` = 'hol' AND `ide`='$_lib->ide'", 
      'ord'=>"`ide` ASC, `nom` ASC"
    ]) ) ){
      $_pal_ite = [];
      foreach( $_pal_lis as $_pal ){         
        $_pal_ite[] = [ 
          'ite'=>$_pal->nom, 
          'lis'=>[ api_tex::let($_pal->des) ]
        ];
      }
      $_lis []= [
        'ite'=>$_lib->nom, 'lis'=>$_pal_ite
      ];
    }
  }
  echo api_lis::dep($_lis,[ 'opc'=>['tog','ver','tog-dep'] ]);
  ?>
</article>    