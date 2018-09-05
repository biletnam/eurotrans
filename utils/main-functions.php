<?php

// require('db-config.php');

/**
 * Функция подключения к базе данных
 * @return mysqli ресурс подключения
 */
function connect()
{
  $db = new mysqli('localhost', 'etrans_artrudov', 'AKArudov897763', 'etrans_evrotrans');
  mysqli_set_charset($db, 'utf8');
  return $db;
}

///**
// * Функция выполнения запроса
// * @param mysqli $db ресурс базы данных
// * @param string $sql строка запроза
// * @param array $formsData данные из формы
// * @return mysqli_stmt результат добавления задачи в базу данных
// */
//function executeQuery($db, $sql, $formsData)
//{
//  $stmt = db_get_prepare_stmt($db, $sql, $formsData);
//  mysqli_stmt_execute($stmt);
//  return $stmt;
//}
//
///**
// * Функция получения данных из базы данных
// * @param mysqli $db ресурс базы данных
// * @param string $sql строка запроза
// * @param array $condition условие для подстановки запроса
// * @return array массив с данными
// */
//function getData($db, $sql, $condition)
//{
//  $resource = mysqli_stmt_get_result(executeQuery($db, $sql, $condition));
//  $result = mysqli_fetch_all($resource, MYSQLI_ASSOC);
//  return $result;
//}
