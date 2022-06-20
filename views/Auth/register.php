<form action="/register" method="post">

    <?php
   
      foreach((array) errors() as $error) {
         foreach((array) $error as $error) {
            echo '<p>' . $error . '</p>';
         }
      }
    ?>
<input type="text" name="name" placeholder="user name" value="<?php echo old('name') ?>">
<br>
    <input type="email" name="email" placeholder="email" value="<?php echo old('email') ?>">
    <br>
    <input type="password" name="password" placeholder="password" value="<?php echo old('password') ?>">
    <input type="password" name="password_confirmation" placeholder="password">
    <button type="submit">register </button>

</form>