<?php function jabc_manager() {

        global $wpdb;

    $children = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
            FROM jabc_children"
        ),
        ARRAY_A
    );

    ?>

    <p>
        <b>Hello Manager!</b>
    </p>
    <p>
        <b>Registered Children:</b><br>
        <?php if (sizeof($children) <1) { ?>
            <p>There are currently no children attending Breakfast Club.</p>
        <?php } else { ?>
            <table>
                
                <thead>
                    <th>Name</th>
                    <th>Vegetarian/Vegan?</th>
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
                            </tr>        
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
<?php } ?>