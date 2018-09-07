<?php

namespace objects;

use PHPMailer\PHPMailer\PHPMailer;

class App
{

    public static function send_mail($data, $time, $file)
    {

        $date = strftime("%e. %B %Y", $time);
        switch($data["dsgvo"])
        {
            case "y_dsgvo":
                $dsvgo = "Ja (DSGVO konform)";
                break;
            case "n_dsgvo":
                $dsvgo = "Ja (aber nicht DSGVO konform)";
                break;
            case "n_ds":
                $dsgvo = "n_ds";
                break;
            default:
                die(json_encode(array("type" => "error", "msg" => "invalid request")));
        }

        ob_start();
        require "email_template.php";
        $html = ob_get_clean();

        $config = json_decode(file_get_contents(__DIR__ . "/config.json"), true);

        $mail = new PHPMailer(true);
        try{

            $mail->isHTML(true);
            $mail->CharSet = "utf-8";
            $mail->SetLanguage("de");
            $mail->From = "info@" . $_SERVER["HTTP_HOST"];
            $mail->FromName = $config["from"];
            $mail->AddAddress($config["mail"]);
            $mail->Subject = "Neue Checklistenabfrage";
            $mail->Budy = $html;
            $mail->AltBody = strip_tags(($html));

            if($mail->send())
            {
                die(json_encode(array("type" => "success", "msg" => $file, "other" => $data)));
            }
            else
            {
                die(json_encode(array("type" => "error", "msg" => "Mail error")));
            }

        } catch(\Exception $e)
        {
            die(json_encode(array("type" => "error", "msg" => $mail->ErrorInfo)));
        }



    }

    public static function object_to_pdf($prefix, $ans, $lastname, $date, $infos)
    {

        // Delete all old files in tmp directory
        $files = glob(__DIR__ . "/../tmp/*");
        foreach($files as $file)
        {
            if(is_file($file))
            {
                unlink($file);
            }
        }

        // Parse template.php to html site and save string to $template
        ob_start();
        require "template.php";
        $template = ob_get_clean();

        // Render pdf from html and add bootstrap stylesheet
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($template);
        $dompdf->render();
        $dompdf->setBasePath('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');

        // Save to file and set random filename. Return file path relative to plugin dir
        $file = "tmp/" . bin2hex(openssl_random_pseudo_bytes(10)) . ".pdf";
        file_put_contents(__DIR__ . "/../". $file, $dompdf->output());

        return $file;

    }

    public static function create_infos($data)
    {

        $infos = array();

        if(!filter_var($data["customer"], FILTER_VALIDATE_BOOLEAN))
        {
            array_push($infos,
                new Category(
                    "Allgemeine Angaben",
                    "Weil Sie noch kein Kunde sind benötigen wir Ihre Adressdaten."
                )
            );
        }

        if(!filter_var($data["impressum"], FILTER_VALIDATE_BOOLEAN))
        {
            array_push($infos,
                new Category(
                    "Rechtliches",
                    "Weil Sie noch kein Impressum haben benötigen wir Angaben zu:
                 <br>
                 <ol>
                    <li>Gesellschaftsform</li>
                    <li>Gesellschafter</li>
                    <li>zuständiges Amtsgericht etc.</li>
                 </ol>"
                )
            );
        }

        if($data["dsgvo"] !== "y_dsgvo")
        {
            array_push($infos,
                new Category(
                    "Rechtliches",
                    "Weil Sie noch keine aktuelle Datenschutzerklärung haben benötigen wir Angaben zu:
                <br>
                <ol>
                    <li>Datenshutzbeauftragem</li>
                    <li>etc.</li>
                </ol>"
                )
            );
        }

        if(filter_var($data["cms"], FILTER_VALIDATE_BOOLEAN))
        {
            array_push($infos,
                new Category(
                    "Technik",
                    "Weil Sie ein CMS wünschen benötigen wir
                <br>
                <ol>
                    <li>Vertragsunterlagen zum Hostinganbieter</li>
                    <li>Zugang zum Webspace</li>
                    <li>etc.</li>
                </ol>"
                )
            );
        }

        foreach($data["important"] as $imp)
        {
            $imp = substr($imp, strlen($imp) - 1);
            switch($imp)
            {
                case "1":
                    array_push($infos,
                        new Category(
                            "Wichtiges",
                            "Weil Ihnen besonders wichtig ist, dass Ihr Projekt bei Google rankt, benötigen wir:
                                <br>
                                <ol>
                                    <li>Texte zu Ihrem Service (mind. 300 Wörter)</li>
                                    <li>Bilder von Ihrem Service (Dateiformat JPEG)</li>
                                    <li>etc.</li>
                                </ol>"
                        )
                    );
                    break;

                case "2":
                    array_push($infos,
                        new Category(
                            "Wichtiges",
                            "Weil Ihnen besonders wichtig ist, dass Ihr Projekt individuell ist, benötigen wir:
                                <br>
                                <ol>
                                    <li>eine tolle Idee ;)</li>
                                    <li>etc.</li>
                                </ol>"
                        )
                    );
                    break;

                case "6":
                    array_push($infos,
                        new Category(
                            "Wichtiges",
                            "Weil Sie Produkte verkaufen wollen, benötigen wir eine Liste aller Produkte mit folgenden Angaben:
                                <br>
                                <ol>
                                    <li>Artikelnr.</li>
                                    <li>Artikelbez.</li>
                                    <li>Artikelbeschreibung</li>
                                    <li>etc.</li>
                                </ol>"
                        ),
                        new Category(
                            "Wichtiges",
                            "Weil Sie Produkte verkaufen wollen, benötigen wir von allen Artikeln Produktbilder."
                        )
                    );
                    break;

                default:
                    break;
            }
        }

        return $infos;

    }

}