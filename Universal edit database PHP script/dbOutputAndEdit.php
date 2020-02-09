
<h3>Current table</h3>
<table>
    <?php
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'test';
        $table = 'users';


       $link = mysqli_connect($host, $user, $password, $database) or die(mysqli_error($link));
        mysqli_query($link, "SET NAMES 'utf8'");


        function createQuery($keys, $values, $table) {
            $query = "INSERT INTO `$table` SET";
            for ($i = 0; $i < count($keys); $i++) {
                $query .= " `$keys[$i]` = '$values[$i]'";
                if ($i !== count($keys) - 1)
                    $query .= ",";
            }
            return $query;
        }

        function input($name, $type = 'text', $value = '') {
            if (isset($_REQUEST['addNew']))
                $value = $_REQUEST[$name];
            return '<p>'.ucfirst($name).': <input type="'.$type.'" name="'.$name.'" value="'.$value.'" style="float: right;"></p>';
        }

        if (isset($_POST['addNew'])) {
            foreach ($_POST as $key => $value) {
                if ($key == 'addNew') continue;
                $keys[] = $key;
                $values[] = $value;
            }

            //$query = "INSERT INTO `$table` SET `name` = '$name', `age` = $age, `salary` = $salary";
            $query = createQuery($keys, $values, $table);
            mysqli_query($link, $query) or die(mysqli_error($link));
        }

        if (isset($_REQUEST['del'])) {
            $del = $_REQUEST['del'];
            $query = "DELETE FROM `$table` WHERE `id` = $del";
            mysqli_query($link, $query) or die(mysqli_error($link));
        }

        $query = "SELECT * FROM `$table`";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

        echo '<tr>';
        $keys = array_keys($data[0]);
        foreach ($keys as $key) {
            echo '<th>'.ucfirst($key).'</th>';
        }
        echo '<th>Delete</th>';
        echo '</tr>';

        foreach ($data as $row) {
            echo '<tr>';
            for ($i = 0; $i < count($keys); $i++)
                echo '<td>'.$row[$keys[$i]].'</td>';
            echo '<td><a href="?del='.$row['id'].'"><input type="submit" value="Delete"></a></td>';
            echo '</tr>';
        }
    ?>
</table>

<h3>Add new row</h3>
<form action="" method="POST" style="width: 250px">
    <?php
        for ($i = 1; $i < count($keys); $i++)
            echo input($keys[$i]);
    ?>
    <input type="submit" name="addNew" value="Add new">
</form>