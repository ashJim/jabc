<?php function jabc_form_add_child() { ?>
        
        <form action="../wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_child_processing.php" method="POST">
            
            <input required type="text" name="forename" placeholder="Child Forename">
            <input required type="text" name="surname" placeholder="Child Surname">
            <input required type="date" name="dob" placeholder="Date of Birth">
            <label for="vegetarian">Vegetarian/Vegan?</label>
            <input type="hidden" name="vegetarian" value="0">
            <input type="checkbox" name="vegetarian" value="1">
            <!-- <input required type="text" name="school" placeholder="School" onkeyup="csur_show_hint(this.value)"> -->
            
            
            <input type="submit" name="submit" value="submit">

        </form>
        <!-- <p>Suggestions: <span id="txtHint"></span></p> -->
    </body>
</html>
<?php } ?>
