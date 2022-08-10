<?php
require_once 'PHPExcel/Classes/PHPExcel.php';
require_once 'functions.php';

$mysql = mysqli_connect("db", "root", "root");
$mysql->query("USE tzp");
$mysql->query("CREATE TABLE IF NOT EXISTS tzp
(
    id       int auto_increment primary key,
    name     varchar(100)     not null,
    category varchar(100)     not null,
    month    varchar(25)      not null,
    price    double default null null
)");

$pExcel = PHPExcel_IOFactory::load('tzp.xlsx');
$sheet = $pExcel->getSheetByName('MA');

$category = '';
for ($i = 4; $sheet->getCellByColumnAndRow(0, $i)->getValue() != 'CO-OP'; ++$i) {
    $color = $sheet->getCellByColumnAndRow(0, $i)->getStyle()->getFill()->getEndColor()->getRGB();
    if ($color != '000000' && $color != 'FFFFFF') {
        $category = $sheet->getCellByColumnAndRow(0, $i)->getValue();
        $nColor = $sheet->getCellByColumnAndRow(0, $i + 1)->getStyle()->getFill()->getEndColor()->getRGB();
        if ($nColor != '000000' && $nColor != 'FFFFFF') {
            insertIntoDB($sheet, $i, $category, $mysql);
        }
    } else {
        insertIntoDB($sheet, $i, $category, $mysql);
    }
}
$mysql->close();