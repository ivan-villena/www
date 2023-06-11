'use strict';

class Usu {

  // Sesion
  static ses( $tip, $ope, $dat, $val ){
    let $ = {};

    switch( $tip ){
    // pido formulario al servidor
    case 'dat':

      Doc_Ope.nav_bot( $ope, '$Usu.ses', [ 'dat' ] );
      break;
    // pido formulario para nuevo usuario
    case 'agr':
      
      Eje.val('$Usu.ses', [ 'dat' ], $htm => {
        Doc_Ope.win('ope', {
          'cab':"Nuevo Usuario",
          'ico':"usu",
          'htm':$htm
        })
      });
      break;
    // pido formulario para recuperar de contraseña
    case 'pas':

      Eje.val('$Usu.ses', [ 'pas' ], $htm => {
        Doc_Ope.win('ope', {
          'cab':"Recuperar Contraseña",
          'ico':"usu",
          'htm':$htm
        })
      });      
      break;
    // pido formulario para cambiar contraseña    
    case 'mod':

      Eje.val('$Usu.ses', [ 'mod' ], $htm => {
        Doc_Ope.win('ope', {
          'cab':"Cambiar Contraseña",
          'ico':"usu",
          'htm':$htm
        })
      });         
      break;
    // pido confirmacion para eliminar perfil
    case 'eli': 
      // cargo operador
      Doc_Dat.Abm.ope = $ope;
      // pido confirmacion
      Doc_Ope.win_opc( 
        "¿Seguro que desea eliminar tu perfil?\nEsto borrará todos los datos asociados a tu cuenta.", 
        `Doc_Dat.abm_val('eli')` 
      );
      break;
    // pido confirmación para cierre de sesión
    case 'fin': 

      Doc_Ope.win_opc( "¿Confirma cerrar la sesión?", `Usu.ses_fin()` );
      break;
    }

    return $;
  }// proceso inicio de sesion
  static ses_ini( $dat ){

    let $ = Doc_Ope.var($dat);

    // valido datos
    $.mai = $Doc.Ope.var.querySelector('[name="mai"]');
    $.pas = $Doc.Ope.var.querySelector('[name="pas"]');

    // llamar al procedimiento de php
    Eje.val(`$Usu.ses_ini`, [ $.mai.value, $.pas.value ], $dat => {
      
      if( $dat ){
        // mostrar mensajes de error
        console.log( $dat );
      }
      else{
        // actualizar pagina
        window.location.href = window.location.href;        
      }      
    });

  }// finalizo sesion
  static ses_fin(){

    // llamar al procedimiento de php
    Eje.val(`$Usu.ses_fin`, [], $dat => {      
      
      // actualizar pagina
      window.location.href = window.location.href;

    });    

  }// completo datos
  static ses_dat( $tip, $ope ){

    let $ = Doc_Ope.var($ope);

    // completo ubicacion
    if( $.ubi = $Doc.Ope.var.querySelector('[name="ubi"]') ){
      
      $.ubi.value = Fec.ubi();
    }

    // ejecuto proceso
    Doc_Dat.abm( $tip, $ope );

  }
  
}