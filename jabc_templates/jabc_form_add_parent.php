<?php function jabc_form_add_parent() { ?>
    <form action="../wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_parent_processing.php" method="POST">
        
        <input required type="text" name="title" placeholder="Parent Title">
        <input required type="text" name="forename" placeholder="Parent Forename">
        <input required type="text" name="surname" placeholder="Parent Surname">
        <input required type="text" name="tel" placeholder="Parent Tel">
        
        <input type="submit" name="submit" value="submit">
        </form>
<?php } ?>
