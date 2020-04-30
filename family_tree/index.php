<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // save form data
    $save_statement = $connection->prepare('INSERT INTO Person(firstname, lastname, gender, mother_id, father_id) VALUES(?,?,?,?,?);');
    $save_statement->execute(
        array(
            $_POST['firstname'], 
            $_POST['lastname'], 
            $_POST['gender'], 
            isset($_POST['mother']) ? $_POST['mother'] : null,
            isset($_POST['father']) ? $_POST['father'] : null
        )
    );
}

// get entire family tree so we can render it as well as make available on the list
$fetch_statement = $connection->prepare("SELECT * FROM Person person ORDER BY person.id;");

$fetch_success = $fetch_statement->execute();
if ($fetch_success) {
    $rows = $fetch_statement->fetchAll();
}
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Family Tree</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <h1>Family Tree</h1>
        <?php if ($fetch_success) { ?>
            <ul>
            <?php foreach($rows as $row) { ?>
                <li><a href="/family_tree/details.php?id=<?= $row['id'] ?>"><?= $row['firstname'] . ' ' . $row['lastname'] ?></a></li>
            <?php } ?>
            </ul>
        <?php } else { ?>
            <span><strong>Fetching people failed miserably.</strong></span>
        <?php } ?>
        <h2>Add Person</h2>
        <form action="/family_tree/index.php" method="post">
            <table>
                <tr>
                    <td>First Name:</td>
                    <td><input name="firstname" type="text" /></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input name="lastname" type="text" /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <select name="gender">
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Mother (optional):</td>
                    <td>
                        <select name="mother">
                            <option disabled selected></option>
                            <?php foreach ($rows as $row) { if ($row['gender'] == 'F') { ?>
                                <option value="<?= $row['id'] ?>"><?= $row['firstname'] . ' ' . $row['lastname'] ?></option>
                            <?php }} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Father (optional):</td>
                    <td>
                        <select name="father">
                        <option disabled selected></option>
                            <?php foreach ($rows as $row) { if ($row['gender'] == 'M') { ?>
                                <option value="<?= $row['id'] ?>"><?= $row['firstname'] . ' ' . $row['lastname'] ?></option>
                            <?php }} ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</form>
</html>