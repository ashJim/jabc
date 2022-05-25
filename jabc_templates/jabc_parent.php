<?php function jabc_parent() {

    global $wpdb;

    $user = wp_get_current_user();
    $email = $user->user_email;

    $parent = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM jabc_parents
            WHERE parent_wp_user_fk=%d",
            get_current_user_id()
        ),
        ARRAY_N
    );

    $children = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM jabc_children
            JOIN jabc_parents
            ON parent_id_fk = parent_id
            WHERE parent_wp_user_fk=%d",
            get_current_user_id()
        ),
        ARRAY_A
    );

    $parent_id = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT parent_id
            FROM jabc_parents
            WHERE parent_wp_user_fk=%d",
            get_current_user_id()
        ),
        ARRAY_N
    );

?>

    <p>
        <b>Hello <?php echo $parent[0][3] ?>!</b>
    </p>
    <p>
        Your registered email address: <?php echo $email ?><br>
        Your registered phone number: <?php echo $parent[0][5]?>
    </p>
    <p>
        <b>Registered Children:</b><br>
        <?php if (sizeof($children) < 1) { ?>
            <p>You currently have no children attending Breakfast Club.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <th>Name</th>
                    <th>Vegetarian/Vegan?</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    <?php foreach ($children as $child) { ?>
                        <tr>
                            <td><?php echo $child['child_forename'] . " " . $child['child_surname'] ?></td>
                            <?php if ($child['vegetarian'] == 1) { 
                                echo "<td>Yes</td>";
                            } else if ($child['vegetarian'] == 0) {
                                echo "<td>No</td>";
                            } ?>
                            <td><a href="../wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_remove_child.php?id=<?php echo $child['child_id']?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php }?>
    </p>
    <p>
        <a href="../add-child">Add Child</a><br>
        <a href="../wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_remove_parent.php?id=<?php echo $parent_id[0][0] ?>">Delete Account</a>
    </p>

<?php } ?>