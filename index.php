<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';

/* 学習内容登録
---------------------------------------------*/
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
    $errors = insert_validate($title, $due_date);

    // エラーチェック
    if (empty($errors)) {
        // 学習内容登録処理の実行
        insert_plan($title, $due_date);
    }
}
// 未完了プランの取得
$notyet_plans = find_plan_by_done();

// 完了プランの取得
$comp_plans = find_plan_by_comp()
?>
<!DOCTYPE html>
<html lang="ja">
<!-- _head.phpの読み込み -->
<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <div class="wrapper">
        <h1 class="title">学習管理アプリ</h1>
        <div class="form-area">
            <!-- エラー表示 -->
            <?php if ($errors) : ?>
                <ul class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="" method="post">
                <label for="title">学習内容</label>
                <?php if (!empty($title)) : ?>
                <input type="text" name="title" value="<?= h($title) ?>">
                <?php $title = '';?>
                <?php else : ?>
                <input name="title" type="text">
                <?php endif; ?>
                <label for="due_date">期限日</label>
                <input name="due_date" type="date">
                <input class="btn submit-btn" type="submit" value="追加">
            </form>
        </div>
        <div class="incomplete-area">
            <h2 class="sub-title">未達成</h2>
            <table class="plan-list">
                <thead>
                    <tr>
                        <th class="plan-title">学習内容</th>
                        <th class="plan-due-date">完了期限</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 未完了のデータを表示 -->
                    <?php foreach ($notyet_plans as $plan) : ?>
                        <tr>
                            <th class="plan-title"><?= h($plan['title']) ?>
                                <!-- 期限切れだったら赤く表示する ためのif文 -->
                            <?php if (strtotime('now') >= strtotime($plan['due_date'])) : ?></th>
                            <th class="expired"><?= h(date('Y/m/d', strtotime($plan['due_date']))) ?></th>
                            <?php else : ?>
                            <th class="plan-due-date"><?= h(date('Y/m/d', strtotime($plan['due_date']))) ?></th>
                            <?php endif; ?>
                            <!-- done.php へのURLを追記 --></th>
                            <th class="done-link-area">
                                <a href="done.php?id=<?= h($plan['id']) ?>">完了</a>
                            </th>
                            <!-- edit.php へのURLを追記 -->
                            <th class="edit-link-area">
                                <a href="edit.php?id=<?= h($plan['id']) ?>">編集</a>
                            </th>
                            <!-- delete.php へのURLを追記 -->
                            <th class="delete-link-area">
                                <a href="delete.php?id=<?= h($plan['id']) ?>">削除</a>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="complete-area">
            <h2 class="sub-title">完了</h2>
            <table class="plan-list">
                <thead>
                    <tr>
                        <th class="plan-title">学習内容</th>
                        <th class="plan-completion-date">完了日</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 完了済のデータを表示 -->
                    <?php foreach ($comp_plans as $plan) : ?>
                        <tr>
                            <th class="plan-title"><?= h($plan['title']) ?></th>
                            <th class="plan-due-date"><?= h(date('Y/m/d', strtotime($plan['completion_date']))) ?></th>
                            <!-- done.php へのURLを追記 -->
                            <th class="done-cancel-link-area">
                                <a href="done_cancel.php?id=<?= h($plan['id']) ?>">未完了</a>
                            </th>
                            <!-- edit.php へのURLを追記 -->
                            <th class="edit-link-area">
                                <a href="edit.php?id=<?= h($plan['id']) ?>">編集</a>
                            </th>
                            <!-- delete.php へのURLを追記 -->
                            <th class="delete-link-area">
                                <a href="delete.php?id=<?= h($plan['id']) ?>">削除</a>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
