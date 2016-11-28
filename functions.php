<?php

//библиотека наших функций, где создаем наши функции или можем обернуть в них 
//функции фреймворка, подключекм его в папке web, файле index.php 

function debug($arr){
    echo '<pre>' . print_r($arr, TRUE) . '</pre>';
}

// выводим во view:
// debug(Yii::$app);

