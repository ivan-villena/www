<?php

  if( !isset($App) ) exit;

  // inicio/s : 
  if( empty($Uri->cab) || empty($Uri->art) ){

    // cargo articulo
    ob_start(); ?>

    <article>
      
      <p>Hola mundo!</p>

    </article>
    
    <?php
    $App->Doc['sec'] = ob_get_clean();

  }