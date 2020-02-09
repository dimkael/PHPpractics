<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'test';
$table = 'guests';
$notesOnPage = 5;

$link = mysqli_connect($host, $user, $password, $database) or die(mysqli_error($link));
mysqli_query($link, "SET NAMES 'utf8'");

if (isset($_REQUEST['save']) && $_REQUEST['name'] != '' && $_REQUEST['text'] != '') {
    $_SESSION['name'] = $_REQUEST['name'];
    $name = $_REQUEST['name'];
    $text = $_REQUEST['text'];

    $query = "INSERT INTO `$table` SET `name` = '$name', `text` = '$text'";
    $_SESSION['check'] = mysqli_query($link, $query);
    header('Location: /');
}

if (isset($_REQUEST['page'])) {
    $currentPage = $_REQUEST['page'];
} else {
    $currentPage = 1;
}

$query = "SELECT COUNT(*) FROM `$table`";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$count = mysqli_fetch_row($result)[0];

$pages = ceil($count / $notesOnPage);
$from = ($currentPage - 1) * $notesOnPage;

$prev = $currentPage - 1;
$next = $currentPage + 1;

$query = "SELECT * FROM `$table` ORDER BY `date` DESC LIMIT $from, $notesOnPage";
$result = mysqli_query($link, $query);
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Guests book</h1>
            <div>
                <nav>
                    <ul class="pagination">
                        <?php
                            if ($currentPage > 1)
                                echo '<li><a href="?page='.$prev.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                            else
                                echo '<li class="disabled"><a href="?page='.$currentPage.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';

                            $class = '';
                            for ($i = 1; $i <= $pages; $i++) {
                                if ($i == $currentPage) $class = ' class="active"';
                                else $class = '';
                                echo '<li'.$class.'><a href="?page='.$i.'">'.$i.'</a></li>';
                            }

                            if ($currentPage < $pages)
                                echo '<li><a href="?page='.$next.'" aria-label="Previous"><span aria-hidden="true">&raquo;</span></a></li>';
                            else
                                echo '<li class="disabled"><a href="?page='.$currentPage.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                        ?>
                    </ul>
                </nav>
            </div>
            <?php
            foreach ($data as $row) {
                echo '<div class="note"><p><span class="date">'.$row['date'].'</span> <span class="name">'.$row['name'].'</span></p><p>'.$row['text'].'</p></div>';
            }
            if ($_SESSION['check'] == 1) {
                echo '<div class="info alert alert-info">Your note was successfully saved!</div>';
            }
            ?>
			<div id="form">
				<form action="" method="POST">
					<p><input class="form-control" placeholder="Your name" name="name" value="<?=$_SESSION['name']?>"></p>
					<p><textarea class="form-control" placeholder="Your feedback" name="text"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" name="save" value="Save"></p>
				</form>
			</div>
		</div>
	</body>
</html>

