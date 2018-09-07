<?php
date_default_timezone_set('Berlin/Europe');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neue Checklistenabfrage</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto');

        html, body{
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
    <h3>Neue Checklistenabfrage</h3>

    <p>
        Hallo,
        <br>
        <br>
        gerade hat ein Kunde eine Checklistenabfrage durchgeführt. Hier findest du die eingegebene Infos:
    </p>
    <h4>Kontaktdaten</h4>
    <p>
        <strong>Name: </strong><?= filter_var($data["woman"], FILTER_VALIDATE_BOOLEAN) ? "Frau" : "Herr" ?> <?= $data["firstname"] ?> <?= $data["lastname"] ?>
        <br>
        <strong>E-Mail: </strong><a href="mailto:<?= $data["email"] ?>"><?= $data["email"] ?></a>
        <br>
        <strong>Bereits Kunde? </strong><?= filter_var($data["customer"], FILTER_VALIDATE_BOOLEAN) ? "Ja" : "Nein" ?>
        <br>
        <strong>Fertigstellung: </strong><?= $date ?>
        <br>
        <strong>Hat ein rechtsgültiges Impressum? </strong><?= filter_var($data["impressum"], FILTER_VALIDATE_BOOLEAN) ? "Ja" : "Nein" ?>
        <br>
        <strong>Hat eine Datenschutzerklärung? </strong><?= $dsgvo ?>
        <br>
        <strong>Möchte ein CMS nutzen? </strong><?= filter_var($data["cms"], FILTER_VALIDATE_BOOLEAN) ? "Ja" : "Nein" ?>
        <br>
        <strong>Was ist dem Kunden besonders wichtig: </strong>
        <br>
        <ol>
        <?php
        $imp = "";
        foreach($data["important"] as $item)
        {
            $item = substr($item, strlen($item) - 1);
            switch($item)
            {
                case "1": $imp = "Projekt soll \"gut\" bei Google ranken";
                    break;
                case "2": $imp =  "Projekt soll einzigartig sein";
                    break;
                case "3": $imp = "Die Kosten sollen so gering wie möglich sein";
                    break;
                case "4": $imp = "Ich muss selber alles ändern können";
                    break;
                case "5": $imp = "Projekt soll auf mobilen Endgeräten dargestellt werden können";
                    break;
                case "6": $imp = "Ich will physische Produkte verkaufen";
                    break;
                default:
                    die(json_encode(array("type" => "error", "msg" => "invalid request")));
            }
        ?>
            <li><?= $imp ?></li>
        <?php
        }
        ?>
        </ol>
    </p>

    Viele Grüße
    <br>
    <br>
    Diese Nachricht wurde um <?= date('H:i:s d/m/Y', time()); ?> generiert.
</body>
</html>