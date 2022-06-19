<form action="/register" method="post">
    <?php
      foreach((array) $errors as $error) {
        echo $error;
      }
    ?>
<input type="text" name="name" placeholder="user name">
<br>
    <input type="email" name="email" placeholder="email">
    <br>
    <input type="password" name="password" placeholder="password">
    <input type="password" name="password_confirmation" placeholder="password">
    <button type="submit">register </button>

</form>