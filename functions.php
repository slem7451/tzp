<?php
function insertIntoDB($sheet, $i, $category, $mysql)
{
    $month = 1;
    $name = $sheet->getCellByColumnAndRow(0, $i)->getValue();
    if ($name) {
        for ($j = 1; 14 > $j; ++$j) {
            if ($month > 12)
                $month = 'TOTAL';
            $price = $sheet->getCellByColumnAndRow($j, $i)->getValue();
            if ((substr($price, 0, 1) === '=') && (strlen($price) > 1)) {
                $price = round($sheet->getCellByColumnAndRow($j, $i)->getOldCalculatedValue(), 2);
            }
            if (!$price) {
                $price = 0;
            }
            $res = $mysql->query("select price from tzp where name = '$name' and month = '$month' and category = '$category'");
            $res = $res->fetch_assoc();
            if (!$res) {
                $mysql->query("insert into tzp (name, category, month, price) values ('$name', '$category', '$month', '$price')");
            } else {
                if ($price != $res['price']) {
                    $mysql->query("update tzp set price = '$price' where name = '$name' and month = '$month' and category = '$category'");
                }
            }
            $month++;
        }
    }
}