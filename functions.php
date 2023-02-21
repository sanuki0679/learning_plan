<?php
require_once __DIR__ . '/config.php';

// 接続処理を行う関数
function connect_db()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

// エスケープ処理を行う関数
function h($str)
{
    // ENT_QUOTES: シングルクオートとダブルクオートを共に変換する。
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 学習内容登録時のバリデーション
function insert_validate($title, $due_date)
{
    // 初期化
    $errors = [];

    // 学習内容が入力されているかをチェック
    if (empty($title)) {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    // 期限日が入力されているかをチェック
    if (empty($due_date)) {
        $errors[] = MSG_DUE_DATE_REQUIRED;
    }
    
    return $errors;
}


// 学習内容登録
function insert_plan($title, $due_date)
{
    // データベースに接続
    $dbh = connect_db();

    // レコードを追加
    $sql = <<<EOM
    INSERT INTO
        plans
        (title, due_date)
    VALUES
        (:title, :due_date)
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':due_date', $due_date, PDO::PARAM_STR);

    // プリペアドステートメントの実行
    $stmt->execute();
}


// 学習内容完了
function update_done_by_id($id, $comp_date)
{
    // データベースに接続
    $dbh = connect_db();
    
    // $id を使用してデータを更新
    $sql = <<<EOM
    UPDATE
        plans
    SET
        completion_date = :comp_date
    WHERE
        id = :id
    EOM;
    
    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);
    
    
    // パラメータのバインド
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':comp_date', $comp_date, PDO::PARAM_INT);
    
    // プリペアドステートメントの実行
    $stmt->execute();
    
}

// 未完了レコードを取得
function find_plan_by_done()
{
    // データベースに接続
    $dbh = connect_db();

    // done で該当レコードを取得
    $sql = <<<EOM
    SELECT
        *
    FROM
        plans
    WHERE
        completion_date IS :status;
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    $stmt->bindValue(':status', null, PDO::PARAM_INT);

    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の取得
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function find_plan_by_comp()
{
    // データベースに接続
    $dbh = connect_db();

    // done で該当レコードを取得
    $sql = <<<EOM
    SELECT
        *
    FROM
        plans
    WHERE
        completion_date 
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド


    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の取得
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// 受け取った id のレコードを取得
function find_by_id($id)
{
    // データベースに接続
    $dbh = connect_db();

    // $id を使用してデータを取得
    $sql = <<<EOM
    SELECT
        *
    FROM
        plans
    WHERE
        id = :id;
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の取得
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// プラン更新時のバリデーション
function update_validate($title, $due_date, $plan) {
    // 初期化
    $errors = [];

    if (empty($title)) {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    if (empty($due_date)) {
        $errors[] = MSG_DUE_DATE_REQUIRED;
    }

    if ($title == $plan['title']) {
        $errors[] = MSG_TITLE_NO_CHANGE;
    }

    if ($due_date == $plan['due_date']) {
        $errors[] = MSG_DUE_DATE_REQUIRED;
    }

    return $errors;
}

// プラン更新
function update_plan($id, $title, $due_date)
{
    // データベースに接続
    $dbh = connect_db();

    // $id を使用してデータを更新
    $sql = <<<EOM
    UPDATE
        plans
    SET
        title = :title,
        due_date = :due_date
    WHERE
        id = :id
    EOM;


    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':due_date', $due_date, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // プリペアドステートメントの実行
    $stmt->execute();
}

// タスク削除
function delete_plan($id)
{
    // データベースに接続
    $dbh = connect_db();

    // $id を使用してデータを削除
    $sql = <<<EOM
    DELETE FROM
        plans
    WHERE
        id = :id
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // プリペアドステートメントの実行
    $stmt->execute();
}
