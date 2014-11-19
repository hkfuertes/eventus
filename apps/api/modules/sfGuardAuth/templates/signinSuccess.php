<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <table>
      <?php echo $form['username']->renderRow(); ?>
      <?php echo $form['password']->renderRow(); ?>
      <?php echo $form->renderHiddenFields(); ?>
  </table>

  <input type="submit" value="ENTRAR" />
</form>
