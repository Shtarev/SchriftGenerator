<!-- Andrey Shtarev || www.shtarev.com ||
Разместить этот файл внутри FPDF-Библиотеки и раскрыть его в браузере
Инсталированные файлы будут загружаться в директорию библиотеки 'font'
-->
<!doctype html>
<?php
$anzeige = '1) Выберите штрифт, который хотите установить<br>2) Выберите кодировку.<br>&#160;&#160;&#160;&#160;cp1251 - для работы с кириллицой<br>&#160;&#160;&#160;&#160;cp1252 - используется для работы с латинскими шрифтами';
$de = '';
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
            if($result == true) {unlink($name.'.php');} else{ $anzeige = 'Сбой рендеринга файла'; return; }
            $result = copy ( $name.'.z', 'font/'.$name.'.z' );
            if($result == true) {
                unlink($name.'.z');
                unlink($file);
                if($enc == 'cp1252'){$de='В utf-8 файлах, для отображения умляутов в немецкоязычных текстах, перед выводом текста пропускайте его через функцию mb_convert_encoding(). Например:<pre>$txt = mb_convert_encoding($txt, \'cp1252\', \'utf-8\');</pre>В методе <code>AddFont()</code> отсутствует возможность установки жирного текста и курсивного текста, например такая попытка установить жирный шрифт: <code>$pdf->AddFont(\''.ucfirst($name).'\',\'B\',\''.$name.'.php\');</code> вызовет ошибку.<br>Для использования возможностей выделять текст жрным или курсивом установите отдельные файлы жирного и курсивного текста и подключайте их в нужных местах. Подчеркивание установки дополнительных файлов не требует.';}
                if($enc == 'cp1251'){$de='В utf-8 файлах, для отображения кириллических текстов, а не кракозябр, перед выводом текста пропускайте его через функцию mb_convert_encoding(). Например:<pre>$txt = mb_convert_encoding($txt, \'cp1251\', \'utf-8\');</pre>В методе <code>AddFont()</code> отсутствует возможность установки жирного текста и курсивного текста, например такая попытка установить жирный шрифт: <code>$pdf->AddFont(\''.ucfirst($name).'\',\'B\',\''.$name.'.php\');</code> вызовет ошибку.<br>Для использования возможностей выделять текст жрным или курсивом установите отдельные файлы жирного и курсивного текста и подключайте их в нужных местах. Подчеркивание установки дополнительных файлов не требует.';}
                $anzeige = 'Шрифт успешно установлен<br>Пример подключения: <code>$pdf->AddFont(\''.ucfirst($name).'\',\'\',\''.$name.'.php\');</code><br>'.$de;
            }
        }
        else{
            $anzeige = 'Загружать можно только файлы с расширением \'ttf\' или \'otf\'';
        }
    }
    else{ $anzeige = 'Файл не загружен'; }
}
?>
<html>
<head>
<meta charset="utf-8">
<title>Генератор шрифта</title>
</head>

<body>
<center><h3>Установщик шрифтов</h3></center>
<?= $anzeige; ?>
<hr><br>
<form method="post" action="" enctype="multipart/form-data">
    Выбрать файл со шрифтом: <input type="file" name="filename"><br><br>
    Выбрать кодировку для файла: <select name="enc">
    <option selected value="cp1252">cp1252-Западная Европа</option>
    <option value="cp1251">cp1251-кириллица</option>
</select><br><br>
<input type="submit" value="Создать">
</form>
</body>
</html>
