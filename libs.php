<?php

$pdo = new SQLite3('data.db');
$pdo->busyTimeout(10000);

function getProblems($lang, $complexity, $topic_id, $block, $class) {
    global $pdo;
    $query = 'SELECT * FROM problems WHERE block = :block';
    if ($lang != -1) {
        $query .= ' AND lang = :lang';
    }
    if ($complexity != -1) {
        $query .= ' AND complexity = :complexity';
    }
    if ($topic_id != -1) {
        $query .= ' AND topic_id = :topic_id';
    }
    if ($class != -1) {
        $query .= ' AND class = :class';
    }
    $stmt = $pdo->prepare($query." Order by date DESC");
    $stmt->bindValue(':block', $block, SQLITE3_INTEGER);
    if ($lang != -1) {
        $stmt->bindValue(':lang', $lang);
    }
    if ($complexity != -1) {
        $stmt->bindValue(':complexity', $complexity, SQLITE3_INTEGER);
    }
    if ($topic_id != -1) {
        $stmt->bindValue(':topic_id', $topic_id, SQLITE3_INTEGER);
    }
    if ($class != -1) {
        $stmt->bindValue(':class', $class, SQLITE3_INTEGER);
    }

    $result = $stmt->execute();
    $problems = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $problems[] = $row;
    }
    return $problems;
}

function getProblem($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM problems WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':block', $block, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
}

function newProblem($problem_text, $problem_photo, $answer_text, $answer_photo, $solution_text, $solution_photo, $author, $lang, $complexity, $topic_id, $class) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO problems (problem_text, problem_photo, answer_text, answer_photo, solution_text, solution_photo, date, author, lang, complexity, block, topic_id, class) VALUES (:problem_text, :problem_photo, :answer_text, :answer_photo, :solution_text, :solution_photo, datetime(\'now\'), :author, :lang, :complexity, 1, :topic_id, :class)');
    
    // Bind values
    $stmt->bindValue(':problem_text', $problem_text);
    $stmt->bindValue(':problem_photo', $problem_photo);
    $stmt->bindValue(':answer_text', $answer_text);
    $stmt->bindValue(':answer_photo', $answer_photo);
    $stmt->bindValue(':solution_text', $solution_text);
    $stmt->bindValue(':solution_photo', $solution_photo);
    $stmt->bindValue(':author', $author, SQLITE3_INTEGER);
    $stmt->bindValue(':lang', $lang);
    $stmt->bindValue(':complexity', $complexity, SQLITE3_INTEGER);
    $stmt->bindValue(':topic_id', $topic_id, SQLITE3_INTEGER);
    $stmt->bindValue(':class', $class, SQLITE3_INTEGER);

    $stmt->execute();
    return true;
}

function toggleProblemBlock($id) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE problems SET block = NOT block WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}

function deleteProblem($id) {
    global $pdo;
    // need to delet file in uploads/problem['probplem_photo'] and uploads/problem['answer_photo']
    $problem =  getProblem($id);
    unlink("uploads/".$problem['problem_photo']);
    unlink("uploads/".$problem['answer_photo']);
    unlink("uploads/".$problem['solution_photo']);
    $stmt = $pdo->prepare('DELETE FROM problems WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();
}

function getTopics() {
    global $pdo;
    $result = $pdo->query('SELECT * FROM topics');
    $topics = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $topics[] = $row;
    }
    return $topics;
}

function getTopic($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM topics WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
}

function newUser($username, $password, $position, $admin) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO users (username, password, position, admin, date) VALUES (:username, :password, :position, :admin, datetime(\'now\'))');
    
    // Bind values
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password); // Consider using password hashing
    $stmt->bindValue(':position', $position, SQLITE3_INTEGER);
    $stmt->bindValue(':admin', $admin, SQLITE3_INTEGER);

    $stmt->execute();
}

function getUser($username) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
}

function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
}


function userExsist($username) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute();
    return $result->fetchArray(SQLITE3_ASSOC);
}

?>
