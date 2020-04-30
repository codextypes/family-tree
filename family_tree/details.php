<?php
require_once 'connection.php';

$fetch_statement = $connection->prepare("
    SELECT
        person.firstname AS person_firstname,
        person.lastname AS person_lastname,
        person.gender AS person_gender,
        father.id AS father_id,
        father.firstname AS father_firstname,
        father.lastname AS father_lastname,
        mother.id AS mother_id,
        mother.firstname AS mother_firstname,
        mother.lastname AS mother_lastname
    FROM Person person
    LEFT JOIN Person father ON person.father_id = father.id
    LEFT JOIN Person mother ON person.mother_id = mother.id
    WHERE person.id = ?;
");
$success = $fetch_statement->execute(array($_GET['id']));

if (!isset($_GET['id']) || !$success) {
?><!doctype html>
<html lang="en">
    <head>
        <title>Error</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <a href="/family_tree/index.php">< Back</a>
        <br />
        <h2>Failed miserably.</h2>
        <p>Make sure the ID parameter was correct, please.</p>
    </body>
</html>
<?php
}

$row = $fetch_statement->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
    <head>
        <title><?= $row['person_firstname'] . ' ' . $row['person_lastname'] ?> | Details</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <a href="/family_tree/index.php">< Back</a>
        <br />
        <h2><?= $row['person_firstname'] . ' ' . $row['person_lastname'] ?></h2>
        <table>
            <tr>
                <td>Gender</td>
                <td><?= $row['person_gender'] ?></td>
            </tr>
            <tr>
                <td>Father</td>
                <td>
                    <?php if (isset($row['father_id'])) { ?>
                        <a href="/family_tree/details.php?id=<?= $row['father_id'] ?>">
                            <?= $row['father_firstname'] . ' ' . $row['father_lastname'] ?>
                        </a>
                        <?php } else { ?>
                        <em>n/a</em>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>Mother</td>
                <td>
                    <?php if (isset($row['mother_id'])) { ?>
                        <a href="/family_tree/details.php?id=<?= $row['mother_id'] ?>">
                            <?= $row['mother_firstname'] . ' ' . $row['mother_lastname'] ?>
                        </a>
                    <?php } else { ?>
                        <em>n/a</em>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </body>
</html>