<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ihre persönliche Checkliste</title>
    <link type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body{
            width: 17cm; !important;
            size: 29cm 29.7cm; !important;
        }
    </style>
</head>
<body>
    <h3>Ihre persönliche Checkliste</h3>
    <br>
    <p>
        Sehr <?= $prefix ?> <strong><?= $ans ?> <?= $lastname ?>,</strong>
        <br>
        dies ist Ihre individuelle Checkliste für das Webprojekt (Fertigstellung: <strong><?= $date ?></strong>).
        <br>
        <br>
        Bitte bringen Sie die folgenden Infromationen zu unserem Termin mit.
    </p>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Typ</th>
                <th>Informationen</th>
                <th>Check</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($infos as $info)
                {
            ?>
            <tr>
                <td><?= $info->getType() ?></td>
                <td><?= $info->getInfo() ?></td>
                <td></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</body>
</html>