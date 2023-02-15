<?php
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');
$status = filter_input(INPUT_GET, 'status');

// 学習内容完了処理の実行
// 後ほど ここに update_done_by_id関数を呼び出す処理を追記する
$status = date("Ymd");
update_done_by_id($id, $status);
// index.php にリダイレクト
header('Location: index.php');
exit;
