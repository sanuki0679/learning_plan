<?php
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// 学習内容完了処理の実行
$comp_date = date("Ymd");
update_done_by_id($id, $comp_date);

// index.php にリダイレクト
header('Location: index.php');
exit;
