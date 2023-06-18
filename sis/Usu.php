<?php

class Usu {

  // para sesion
  public int    $key;
  public string $mai;
  public string $pas;
  public string $ubi;

  // perfil
  public string $nombre;
  public string $apellido;
  public string $fecha;
  public string $imagen;
  public string $genero;
  public string $telefono;
  public string $ubicacion;
  public string $estudio;
  public string $profesion;
  public string $pasatiempo;
  public string $biografia;


  public function __construct( int | string $ide = 0 ){

    $this->key = 0;
    
    if( !empty($ide) ){

      // cargo datos
      foreach( Dat::get('sis-usu', [
        'ver'=>is_numeric($ide) ? "`key` = '{$ide}'" : "`mai` = '{$ide}'", 
        'opc'=>'uni'
      ]) as $atr => $val ){

        if( isset($val) ) $this->$atr = $val;
      }
    }
  }

  /* -- Sesion por Aplicacion -- */

  // Imprimo formularios 
  public function ses( string $ide ) : string {

    $_ = "";
    $esq = "sis";
    $est = "usu";

    switch( $ide ){
    // inicio de Sesión
    case 'ini':
      $_ = Doc_Dat::abm("{$esq}.{$est}",[
        
        'atr' => [
          "mai", "pas" 
        ],
        'ope' => [ 
          'ini' => "Usu.ses_ini(this);"
        ],
        'htm-med' => "
        <fieldset class='ope_var'>
          <p>".Doc_Val::let("¿No tienes usuario?")." <a onclick=\"Usu.ses('agr',this);\">".Doc_Val::let("¡creáte uno!")."</a></p>
        </fieldset>
        
        <fieldset class='ope_var'>
          <a onclick=\"Usu.ses('pas',this);\">".Doc_Val::let("¿Olvidaste la contraseña?")."</a>
        </fieldset>"
      ]);
      break;
    // recuperar contraseña
    case 'pas':
      $_ = Doc_Dat::abm("{$esq}.{$est}",[
        
        'atr' => [
          "mai"
        ],
        'ope' => [ 
          'ini' => "\$Usu.ses_pas"
        ],
        'htm-ini' => "
        <p>Una vez que confirmes te enviaremos la contraseña por correo electrónico. Si quieres, podrás cambiarla desde tu perfil.</p>"
      ]);      
      break;
    // cambiar contraseña      
    case 'mod':
      $_ = Doc_Dat::abm("{$esq}.{$est}",[
        
        'atr' => [
          "pas"
        ],
        'ope' => [ 
          'ini' => "\$Usu.ses_mod"
        ],
        'htm-ini' => "
        <p>Para cambiar la contraseña primer introduce la actual y luego puedes ingresar la nueva.</p>",

        'htm-med' => Doc_Ope::var('_',"pas_mod",[
          'nom' => "Nuevo Password",
          'ope'=>[ 
            'id'=>"sis-usu-pas_mod",
            'tip'=>'tex_ora',
            'type'=>'password',
            'val_req'=>'1'
          ]
         ]),
      ]);      
      break;
    // actualizar datos
    case 'dat':
      
      $tip = empty($this->key) ? "agr" : "mod";

      $atr = [ 
        "nombre", "apellido", "fecha", "mai", "ubi"
      ];
      $ope = [
        'ini' => [ 'onclick'=>"Usu.ses_dat('{$tip}',this)", 'data-eje' => "\$Usu.ses_dat"  ]
      ];
      $htm = "";
      if( $tip == 'mod' ){

        $ope['pas'] = [ 'nom' => "Cambiar Contraseña", 'onclick' => "Usu.ses('mod',this)" ];
        $ope['eli'] = [ 'nom' => "Eliminar Perfil", 'onclick' => "Usu.ses('eli',this)", 'data-eje' => "\$Usu.ses_dat" ];
      }
      else{
        $atr[] = "pas";
        $htm = Doc_Ope::var('_',"pas_rep",[
          'nom' => "Repite el Password",
          'ope'=>[ 
            'id'=>"sis-usu-pas_rep",
            'tip'=>'tex_ora',
            'type'=>'password',
            'val_req'=>'1'
          ]
        ]);
      }

      $_ = Doc_Dat::abm("{$esq}.{$est}",[

        'dat' => $this,
        'atr' => $atr,
        'ope' => $ope,
        'htm-med' => $htm
      ]);      
      break;
    }

    return $_;
  }// - proceso inicio de sesion 
  public function ses_ini( string $mai, string $pas ) : string {

    $_ = "";

    $Usu = new Usu( $mai );

    if( isset($Usu->pas) ){
      
      if( $Usu->pas == $pas ){
        
        // actualizo sesion con usuario
        $_SESSION['usu'] = $Usu->key;
        $_SESSION['ubi'] = isset($Usu->ubi) ? $Usu->ubi : $_SESSION['ubi'];
      }
      else{

        $_ = "Password Incorrecto";
      }
    }
    else{

      $_ = "Usuario Inexistente";
    }

    return $_;

  }// - proceso finalizar la sesion
  public function ses_fin() : string {

    // elimino datos de la sesion
    session_destroy();

    // reinicio  
    session_start();

    return "La sesión ha sido finalizada correctamente";

  }// - proceso recuperar contraseña
  public function ses_pas( object $dat ) : string | array {

    $_ = [];

    // recibo una direccion de mail
    if( isset($dat->mai) ){

      // busco password
      $usu = new Usu($dat->mai);

      if( !empty($usu->pas) ){

        // envío mail
        mail( $usu->mai, "Recuperación de Password", "¡Hola!\n\n Hemos recibido tu solicitud para recuperar el password.\n\n \"{$usu->pas}\" \n\nSi quieres, puedes cambiarlo desde Administrar el perfil en la sesión del sitio.\n\n ¡Saludos!");
      }
      else{

        $_ = "<p class='err'>No existe un usuario registrado con el mail '{$dat->mai}'...</p>";
      }
    }
    else{
      $_ = "<p class='err'>Error en la recepción de datos, el mail no es válido...</p>";
    }
    
    return $_;

  }// - proceso cambiar contraseña
  public function ses_mod( object $dat ) : string | array {

    $_ = [];

    if( isset($dat->pas) && isset($dat->pas_mod) ) {

      $dat->key = $this->key;

      if( $dat->pas != $this->pas ){

        $_['pas'][] = "Es incorrecto..."; 
      }
      elseif( $dat->pas == $dat->pas_mod ){

        $_['pas_mod'][] = "Debe ser distinto al password actual..."; 
      }
      else{
        $dat->pas = $dat->pas_mod;
  
        // ejecuto operaciones
        $_ = Dat::est_abm("mod","sis-usu",$dat);

        if( is_numeric($_) && !empty($_) ){
  
          $_ = "<p>El password fué actualizado correctamente...</p>";
        }
        else{

          $_ = "<p class='err'>Hubo un error al actualizar el password...</p>";
        }
      }
    }
    else{

      $_ = "<p class='err'>Hubo un error en la recepción de datos, no fueron enviados correctamente...</p>";
    }

    return $_;

  }// - proceso datos 
  public function ses_dat( object | int $dat, string $ope = "mod" ) : mixed {
    
    $_ = [];

    // aseguro identificar del usuario activo
    $dat->key = $this->key;

    // elimino usuario y todos los registros de todas las tabla asociadas
    if( $ope == "eli" ){

      // cambio claves primaria 
      $dat_rel = new stdClass;
      $dat_rel->usu = $dat->key;

      // elimino registros asociados
      foreach( Dat::sql_est('nom','sis-usu','tab') as $est ){
        
        if( $est != 'sis-usu' ) Dat::est_abm($ope,$est,$dat_rel);
      }       

      // elimino usuario              
      $_ = Dat::est_abm($ope,"sis-usu",$dat);

    }
    else{

      // valido email
      $val_usu = new Usu( $dat->mai );

      // valido password
      if( isset($dat->pas_rep) && $dat->pas_rep != $dat->pas ){

        $_['pas'][] = "No coincide con el campo repetir password...";

      }// valido email
      elseif( !empty($val_usu->key) && $val_usu->key != $dat->key ){

        $_['mai'][] = "Ya existe otro usuario con este mail. Si no recuerdas el password, puedes recuperarlo desde Iniciar Sesion...";
      }// ejecuto operacion en base de datos
      else{
        
        // ejecuto operaciones
        $_ = Dat::est_abm( $tip = empty($dat->key) ? "agr" : "mod", "sis-usu", $dat );

        // evalúo resultados
        if( is_numeric($_) && !empty($_) ){

          if( $tip == 'mod' ){

            $_ = "<p>Los datos se actualizaron correctamente...</p>";
          }
          // inicio sesion
          elseif( $tip == 'agr' ){

            $this->ses_ini( $dat->mai, $dat->pas );
          }        
        }        
      }
    }

    return $_;
  }

}