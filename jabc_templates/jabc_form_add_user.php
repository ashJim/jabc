<?php function jabc_form_add_user() { ?>
    <form action="../wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_user_processing.php" method="POST">

        <input type="hidden" name="action" value="custom_action_hook">
        
        <input required type="text" name="email" placeholder="email">
        <input required type="password" name="password" placeholder="password">
        
        <input type="submit" name="submit" value="submit">
        </form>
<?php } ?>
