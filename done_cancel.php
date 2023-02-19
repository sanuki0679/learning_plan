<?php
require_once __DIR__ . '/functions.php';

// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// 学習内容完了処理の実行
$status = NULL;
update_done_by_id($id, $status);

// index.php にリダイレクト
header('Location: index.php');
exit;
