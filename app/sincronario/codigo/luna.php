<!-- 28 Días Lunares -->
<?php 
  $nv1 = "00"; $nv2 = "00"; $nv3 = "00"; $nv4 = "00";
  ?>
  <!--  -->
  <article>
    <?php $nv1 = Num::val(intval($nv1) + 1,2); $nv2 = 0; $nv3 = 0; $nv4 = 0; ?>
    <h2 id="<?="_{$_nav[1][$nv1]->pos}-"?>"><?=Tex::let($_nav[1][$nv1]->nom)?></h2>

  </article>