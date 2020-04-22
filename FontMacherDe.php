<!-- Andrey Shtarev || www.shtarev.com ||
Es ist für die fpdf FPDF-Library zu verwenden.
Stellen Sie diese file in Directory mit der fpdf-Bibliothe und öffnen Sie es im Browser.
Die beladenen Schriften werden in Directory mit den Schriften 'font' download.
-->
<!doctype html>
<?php
$anzeige = '1) Wählen Sie die Schrift, die Sie installieren wollen<br>2) Wählen Sie die Kodierung.<br>&#160;&#160;&#160;&#160;cp1251 - Für die Arbeit mit der kyrillischen Schrift<br>&#160;&#160;&#160;&#160;cp1252 - Für die Arbeit mit den lateinischen Schriften';
$de = '';
require('dump/dd.php');
if (isset($_POST['enc'])){
    if (isset($_FILES['filename']) && $_FILES['filename']['name'] != ''){
        $file = $_FILES['filename']['name'];
        $ext = strrchr($file, '.');
        if($ext == '.ttf' || $ext == '.otf'){
            $enc = $_POST['enc'];
            $name = substr($file, 0, -strlen($ext));
            require('makefont/makefont.php');
            move_uploaded_file($_FILES['filename']['tmp_name'], $file);
            @MakeFont($file, $enc);
            $result = copy ( $name.'.php', 'font/'.$name.'.php' );
            if($result == true) {unlink($name.'.php');} else{ $anzeige = 'Die Störung von Rendering der Datei'; return; }
            $result = copy ( $name.'.z', 'font/'.$name.'.z' );
            if($result == true) {
                unlink($name.'.z');
                unlink($file);
                if($enc == 'cp1252'){$de='В utf-8 Files, für die Umlaute verwenden Sie die Funktion mb_convert_encoding(). Zum Beispiel:<pre>$txt = mb_convert_encoding($txt, \'cp1252\', \'utf-8\');</pre>In der Methode <code>AddFont()</code> fehlt die Möglichkeit der Anlage für Fettdruck und Kursivschrift. Zum Beispiel diese: <code>$pdf->AddFont(\''.ucfirst($name).'\',\'B\',\''.$name.'.php\');</code> gibt Fehler aus.<br>Sie müssen für Fettdruck und Kursivschrift noch ein Font zusätzlich installieren.';}
                if($enc == 'cp1251'){$de='В utf-8 Files, für die kyrillische Schrift verwenden Sie die Funktion mb_convert_encoding(). Zum Beispiel:<pre>$txt = mb_convert_encoding($txt, \'cp1251\', \'utf-8\');</pre>In der Methode <code>AddFont()</code> fehlt die Möglichkeit der Anlage für Fettdruck und Kursivschrift. Zum Beispiel diese: <code>$pdf->AddFont(\''.ucfirst($name).'\',\'B\',\''.$name.'.php\');</code> gibt Fehler aus.<br>Sie müssen für Fettdruck und Kursivschrift noch ein Font zusätzlich installieren.';}
                $anzeige = 'Die Schrift ist erfolgreich installiert<br>Beispiel des Anschließens: <code>$pdf->AddFont(\''.ucfirst($name).'\',\'\',\''.$name.'.php\');</code><br>'.$de;
            }
        }
        else{
            $anzeige = 'Download kann nur die Dateien mit \'ttf\' oder \'otf\'';
        }
    }
    else{ $anzeige = 'Die Datei ist nicht beladen'; }
}
?>
<html>
<head>
<meta charset="utf-8">
<title>SchriftGenerator</title>
</head>

<body>
<center><h3>SchriftGenerator</h3></center>
<?= $anzeige; ?>
<hr><br>
<form method="post" action="" enctype="multipart/form-data">
    Wählen Sie die FontDatei: <input type="file" name="filename"><br><br>
    Wählen Sie die Kodierung für die Datei: <select name="enc">
    <option selected value="cp1252">cp1252-Lateinschrift</option>
    <option value="cp1251">cp1251-Kyrillische Schrift</option>
</select><br><br>
<input type="submit" value="Schaffen">
</form>
</body>
</html>