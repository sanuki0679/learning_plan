<?php
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// プラン削除処理の実行
// 後ほど ここに delete_plan関数を呼び出す処理を追記する
delete_plan($id);
// index.php にリダイレクト
header('Location: index.php');
exit;
