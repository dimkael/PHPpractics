<style>
    a {
        text-decoration: none;
    }

    a.active {
        text-decoration: underline;
    }

    a.disabled {
        pointer-events: none;
    }
</style>

<?php

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'test';
    $table = 'workers';

    $link = mysqli_connect($host, $user, $password, $database) or die(mysqli_error($link));
    mysqli_query($link, "SET NAMES 'utf8'");


    if (isset($_REQUEST['page']))
        $page = $_REQUEST['page'];
    else
        $page = 1;

    $notesOnPage = 3;
    $from = ($page - 1) * $notesOnPage;

    $query = "SELECT * FROM `$table` LIMIT $from, $notesOnPage";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    echo '<pre>', var_dump($data), '</pre>';

    $query = "SELECT COUNT(*) FROM `$table`";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $count = mysqli_fetch_row($result)[0];

    $pagesCount = ceil($count / $notesOnPage);

    echo 'Страницы: ';

    $prev = $page - 1;
    $next = $page + 1;

    if ($page > 1)
        echo "<a href=\"?page=$prev\"><<</a> ";
    else
        echo "<a href=\"?page=$prev\" class='disabled'><<</a> ";

    for ($i = 1; $i <= $pagesCount; $i++) {
        $class = '';
        if ($page == $i) $class = ' class="active"';
        echo "<a href=\"?page=$i\"$class>$i</a> ";
    }

    if ($page < $pagesCount)
        echo "<a href=\"?page=$next\">>></a> ";
    else
        echo "<a href=\"?page=$next\" class='disabled'>>></a> ";