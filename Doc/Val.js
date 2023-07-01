// WINDOW
'use strict';

class Doc_Val {

  /* Icono : .val_ico.ide-$ide */
  static ico( $ide, $ele = {} ){

    let $_="<span class='val_ico'></span>", $ = {};

    $.ico = Dat._("var.tex_ico");

    if( !!($.ico[$ide]) ){

      // identificador del boton
      Ele.cla($ele,`val_ico ide-${$ide}`,'ini');

      $ele['htm'] = $.ico[$ide].val;

      $_ = Ele.val($ele);
    }
    return $_;
  }  

  /* imagen : .app_ima.$ide */
  static ima( ...$dat ){  

    let $_="", $={}, $ele={};
    
    // por dato de la base
    if( !!$dat[2] ){

      $_ = Doc_Dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], !!($dat[3]) ? $dat[3] : {});

    }
    // por objeto o directorio
    else{
      
      $ele = !!$dat[1] ? $dat[1] : {};
      
      $.tip = typeof($dat = $dat[0]);
      
      // por estilos : bkg
      if( $.tip == 'object' ){

        $ele = Ele.val_jun( $dat, $ele );
      }
      // por directorio : localhost/_/esq/ima/...
      else if( $.tip == 'string' ){    
        
        $.ima = $dat.split('.');
        
        $dat = $.ima[0];
        
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        
        $.dir = `_img/${$dat}`;
        
        Ele.css( $ele, Ele.css_fon($.dir,{'tip':$.tip}) );
      }    
      
      // contenido 
      if( !!($ele['htm']) ) Ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');

      // aseguro identificador
      Ele.cla($ele,`val_ima`,'ini');      
      
      $_ = Ele.val($ele);
    }
    return $_;
  }
  static ima_lis( $arc, $pad ){
    
    let $={
      'lis':[]
    };  
    
    Obj.pos_ite($arc).forEach( $Arc => {

      if( typeof($Arc) == 'object' ){
        $.ima = document.createElement('img');
        $.ima.src = URL.createObjectURL($Arc);
        $.lis.push($.ima);
      }
    });

    if( !!$pad && $pad.nodeName ){
      Doc.eli($pad);
      Doc.agr($.lis,$pad);
    }

    return $;
  }

  // letras : c - n
  static let( $dat, $ele={} ){

    let $_ = [], $pal = [], $let = [], $num = 0, $tex_let = Dat._("var.tex_let");

    if( $dat !== null && $dat !== undefined && $dat !== NaN ){

      if( typeof($dat) != 'string' ) $dat = $dat.toString();

      // saltos de linea
      $dat.split('\n').forEach( $tex_pal => {


        $pal = [];

        // espacios
        $tex_pal.split(' ').forEach( $pal_val => {
          
          // numero completo
          if( ( $num = Num.val($pal_val) ) || $num == 0 ){
            
            $pal.push( `<n>${$pal_val}</n>` );
          }
          // caracteres
          else{
            $let = [];
            $pal_val.split('').forEach( $car => {
              
              if( ( $num = Num.val($car) ) || $num == 0 ){
                $let.push( `<n>${$car}</n>` );
              }
              else if( !!$tex_let[$car] ){
                $let.push( `<c>${$car}</c>` );
              }
              else{
                $let.push( $car );
              }
            });

            $pal.push( $let.join('') );
          }
        });


        $_.push( $pal.join(' ') );
      });

    }
    return $_.join('<br>');
  }

  // Selector 
  static opc( $dat, $ope, ...$opc ){
    let $_, $={};

    if( $ope && !$ope.nodeName ){
      $.opc = document.createElement('select');
      for( const $atr in $ope ){ $.opc.setAttribute($atr,$_[$atr]); }
      $_ = $.opc;
    }else{
      $_ = $ope;
    }
    $.val = '';
    if( $ope.value ){
      $.val = $ope.value;
    }else if( $ope.val ){
      $.val = $ope.val;
    }

    Doc_Val.opc_ite($dat,$.val,...$opc).forEach( 
      $e => $_.appendChild($e) 
    );

    return $_;      
  }// ...Opciones
  static opc_ite( $dat, $val, ...$opc ){
    let $_=[], $={};    

    if( $opc.includes('nad') ){
      $.ite = document.createElement('option');
      $.ite.value = ''; 
      $.ite.innerHTML = '{-_-}';
      $_.push($.ite);
    }

    $.val_ide = $opc.includes('ide');

    for( const $ide in $dat ){ 
      $.obj_tip = Obj.tip($dat[$ide]); 
      break; 
    }
    $.obj_pos = ( $.obj_tip == 'pos' );

    for( const $ide in $dat ){ const $ite = $dat[$ide];

      $.ite = document.createElement('option');
      $.val = $ide;
      $.htm = "";
      if( !$.obj_tip ){        
        $.htm = $ite;
      }
      else if( $.obj_pos ){        
        $.htm = $ite.join(', ');
      }
      else{
        // valor
        if( $ite.ide ){ $.val = $ite.ide; }else if( $ite.pos ){ $.val = $ite.pos; }
        // titulo
        if( !!$ite.des || !!$ite.tit ) $.ite.setAttribute('title', !! $ite.des ? $ite.des : $ite.tit );
        // contenido        
        if( !!$.val_ide && !!$ite.ide ) $.htm += `${$ite.ide}: `;
        if( !!$ite.nom ) $.htm += $ite.nom;
      }      
      $.ite.setAttribute('value',$.val);
      if( $val == $.val ) $.ite.setAttribute('selected',"");
      $.ite.innerHTML = $.htm;
      $_.push($.ite);
    }
    return $_;
  }
  
  /* Listados : contenido, posicion, desplazamiento */
  static lis( $tip, $ele, $ope, $val ){
    let $ = {};

    switch( $tip ){
    // punteos
    case 'pos': 
      $ = Doc_Ope.var($ele);
      // toggles
      if( $ele.nodeName == 'DT' ){

        $.dd = $ele.nextElementSibling;

        while( $.dd && $.dd.nodeName == 'DD' ){
          $.dd.classList.toggle("dis-ocu");
          $.dd = $.dd.nextElementSibling;
        }
      }
      break;
    // desplazamiento horizontal
    case 'bar': 
      $ = Doc_Ope.var($ele);

      if( $ope == 'val' ){

        $.lis = $Doc.Ope.var.previousElementSibling;

        $.val = $Doc.Ope.var.querySelector('[name="val"]');

        $.pos = Num.val($.val.value);

        switch( $ele.getAttribute('name') ){
        case 'ini': $.pos = Num.val($.val.min);
          break;
        case 'pre': $.pos = $.pos > Num.val($.val.min) ? $.pos-1 : $.pos;
          break;
        case 'pos': $.pos = $.pos < Num.val($.val.max) ? $.pos+1 : $.pos;
          break;
        case 'fin': $.pos = Num.val($.val.max);
          break;
        }
        
        // valido y muestro item
        $.val.value = $.pos;

        Doc.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.dis-ocu)`),"dis-ocu");

        if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove("dis-ocu");
      }
      break;
    }

    return $;
  }// contenido
  static lis_dep( $tip, $ele, $ope, $val ){

    let $ = {};

    // - Toggles
    if( $tip == 'tog' ){

      Doc_Ope.val($ele,$ope);
    }
    // Filtros
    else if( $tip == 'ver' ){

      if( !$ope ) $ope = 'p:first-of-type';
      if( !$val ) $val = 'tex-luz';

      $ = Doc_Ope.var($ele);

      // busco listado
      if( $Doc.Ope.var ){

        $.lis = !! $Doc.Ope.var.nextElementSibling ? $Doc.Ope.var.nextElementSibling : $Doc.Ope.var.parentElement;

        if( $.lis.nodeName == 'LI' ){
          $.lis = $.lis.parentElement;
          $.val_dep = true;
        }
      }
  
      // ejecuto filtros
      if( $.lis && ( $.val = $Doc.Ope.var.querySelector(`[name="val"]`) ) ){

        // elimino marcas anteriores
        if( $val ) $.lis.querySelectorAll(`li.pos ${$ope}.${$val}`).forEach( $ite => $ite.classList.remove($val) );        

        // operador de comparacion
        $.ope = $Doc.Ope.var.querySelector(`[name="ope"]`);
        $.ope = $.ope ? $.ope.value : "**";
  
        // 1- muestro u oculto por coincidencias
        $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {
          
          // capturo item : li > [.val] (p / a)
          $.ite = Doc.ver($ite,{'eti':'li'});
          
          // ejecuto comparacion por elemento selector ( p / a )
          if( !($.tex = $.val.value.trim()) || Dat.ver($ite.innerText.trim(), $.ope, $.tex) ){
            
            // oculto/mustro item
            $.ite.classList.contains("dis-ocu") && $.ite.classList.remove("dis-ocu");
            
            // agrego brillo
            if( !!$val && !!$.tex ) $ite.classList.add($val);
          }
          else{
            
            $.ite.classList.add("dis-ocu");
          }
        });
        
        // 2- por cada item mostrado, muestro ascendentes
        $.tot = 0;
        if( $.val.value ){
          
          $.lis.querySelectorAll(`li.pos:not(.dis-ocu) ${$ope}`).forEach( $ite => {
            
            $.tot ++;
            
            // subo desde el listado
            $.val = $ite.parentElement.parentElement;
            
            while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI' && $.ite.classList.contains('pos') ){

              $.ite.classList.contains("dis-ocu") && $.ite.classList.remove("dis-ocu");

              $.val = $.ite;
            }
          });
        }
        
        // actualizo toggle: muestro todos
        if( $.val_ico = $Doc.Ope.var.querySelector(`.val_ico.ide-ope_tog-tod`) ){

          Doc_Ope.val($.val_ico,'tod');
        }
        
        // actualizo total
        if( $.tot_val = $Doc.Ope.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;

      }        
    }

  }// posicion: punteos y terminos
  static lis_pos( $tip, $ele, $ope, $val ){

    let $ = {};

    // - Toggles
    if( $tip == 'tog' ){

      $ = Doc_Ope.var($ele);

      if( !$ele || !$ope ){
        Doc.act('cla_tog',$.lis.children,"dis-ocu"); 
      }
      else{
        Obj.pos($.lis.children).forEach( $ite => {
  
          if( $ite.nodeName == 'DT' && !$ite.classList.contains("dis-ocu") ){
  
            if( $ite.nextElementSibling ){
              if( 
                ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains("dis-ocu") )
                ||
                ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains("dis-ocu") )
              ){
                Doc_Val.lis('pos',$ite);
              }
            }
          }
        } );
      }
    }

    // - Filtro
    else if( $tip == 'ver' ){

      $ = Doc_Ope.var($ele);
    
      // filtro por valor textual        
      if( !$ope ){
        $.lis = $Doc.Ope.var.nextElementSibling;
        // muestro por coincidencias
        if( $.val = $Doc.Ope.var.querySelector('[name="val"]').value ){
          // oculto todos
          Doc.act('cla_agr',$.lis.children,"dis-ocu"); 
  
          $.ope = $Doc.Ope.var.querySelector('[name="ope"]').value;
          
          if( $.lis.nodeName == 'DL' ){
            $.lis.querySelectorAll(`dt`).forEach( $e => {
              // valido coincidencia
              $.ope_val = Dat.ver($e.innerHTML,$.ope,$.val) ? $e.classList.remove("dis-ocu") : $e.classList.add("dis-ocu");
              $.dd = $e.nextElementSibling;
              while( $.dd && $.dd.nodeName == 'DD' ){
                $.ope_val ? $.dd.classList.remove("dis-ocu") : $.dd.classList.add("dis-ocu");
                $.dd = $.dd.nextElementSibling;
              }
            });
          }
          else{
            Obj.pos($.lis.children).forEach( $e => 
              Dat.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove("dis-ocu") 
            );
          }
        }
        else{
          Doc.act('cla_eli',$.lis.children,"dis-ocu");
        }
      }
      // operadores
      else{
        switch( $ope ){
        case 'tod': Doc.act('cla_eli',$.lis.children,"dis-ocu"); break;
        case 'nad': Doc.act('cla_agr',$.lis.children,"dis-ocu"); break;
        }
      }
  
      // actualizo cuenta
      if( $.tot = $Doc.Ope.var.querySelector('[name="tot"]') ){
        if( $.lis.nodeName == 'DL' ){
          $.tot.innerHTML = Obj.pos($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains("dis-ocu") ).length;
        }else{
          $.tot.innerHTML = Obj.pos($.lis.children).filter( $ite => !$ite.classList.contains("dis-ocu") ).length;
        }
      }  
    }
  }
}