<?php
include('connect.php');
$conn = openConnection();
global $conn;
global $result;

// Fetch and display data
global $conn;
$query = "SELECT * FROM categories";
$stm = $conn->prepare($query);
$stm->execute();
$result = $stm->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    if (empty(trim($name))) {
        $nameErr = true;
    }

    if ($status === '2') {
        $statusErr = true;
    }

    if (!$nameErr && !$statusErr) {
        try {
            $query = "INSERT INTO categories (name, description, status) VALUES (:name, :description, :status)";
            $stm = $conn->prepare($query);
            $result = $stm->execute([
                ":name" => $name,
                ":description" => $description,
                ":status" => $status
            ]);

            if ($result) {
                // Data successfully inserted, refresh the page to display the updated table
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit('error');
        }
    }
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $query = "DELETE FROM categories WHERE id = :delete_id";
        $stm = $conn->prepare($query);
        $stm->bindValue(':delete_id', $delete_id);
        $result = $stm->execute();

        if ($result) {
            // Delete successful, refresh the page to update the table
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
        exit('error');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Edit Category</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <input type="submit" value="Submit" class="btn btn-primary">
        </form>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $res) {
                    echo "<tr>";
                    echo "<td>" . $res['name'] . "</td>";
                    echo "<td>" . $res['description'] . "</td>";
                    echo "<td>" . ($res['status'] == 1 ? 'Active' : 'Inactive') . "</td>"; // Display "Active" for status value 1
                    echo "<td>";
                    echo "<a href=\"edit.php?id=" . $res['id'] . "\" class=\"btn btn-primary\">Edit</a> ";
                    echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?delete_id=" . $res['id'] . "\" class=\"btn btn-danger\">Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
