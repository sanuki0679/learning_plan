<?php
require_once __DIR__ . '/functions.php';



// index.php から渡された id を受け取る
$id = filter_input(INPUT_GET, 'id');

// 受け取った id のレコードを取得
$plan = find_by_id($id);

/* プラン更新処理
-------------------------------------------*/
// 初期化
$title = '';
$due_date = '';
$errors = [];
// リクエストメソッドの判定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームに入力されたデータを受け取る
    $title = filter_input(INPUT_POST, 'title');
    $due_date = filter_input(INPUT_POST, 'due_date');

    // バリデーション
    $errors = update_validate($title, $due_date, $plan);


    // エラーチェック
    if (empty($errors)) {
        // 学習内容登録処理の実行
        // 後ほど ここに insert_plan関数を呼び出す処理を追記する
        update_plan($id, $title, $due_date);

        // index.php にリダイレクト
        header('Location: index.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <div class="wrapper">
        <h1 class="title">学習管理アプリ</h1>
        <div class="form-area">
            <!-- エラー表示 -->
            <h2 class="sub-title">編集</h2>
            <?php if ($errors) : ?>
                <ul class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="" method="post">
            <div class="edit">
                <label for="title">学習内容</label>
                <input type="text" name="title" value="<?= h($plan['title']) ?>">
                <label for="due_date">期限日</label>
                <input type="date" name="due_date" value="<?= h($plan['due_date']) ?>">
            
                <input type="submit" class="edit-submit-upd" value="更新">
                <input type="button" onclick="location.href='index.php'" value="戻る">
                    
            </div>

            </form>
        </div>
    </div>
</body>

</html>
