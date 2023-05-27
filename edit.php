<?php
include('connect.php');
$conn = openConnection();
global $conn;
global $result;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
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
            $query = "UPDATE categories SET name = :name, description = :description, status = :status WHERE id = :id";
            $stm = $conn->prepare($query);
            $result = $stm->execute([
                ":name" => $name,
                ":description" => $description,
                ":status" => $status,
                ":id" => $id
            ]);

            if ($result) {
                // Data successfully updated, redirect to index.php
                header('Location: index.php');
                exit;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit('error');
        }
    }
} else {
    // Retrieve category details for editing
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            $query = "SELECT * FROM categories WHERE id = :id";
            $stm = $conn->prepare($query);
            $stm->bindValue(':id', $id);
            $stm->execute();
            $category = $stm->fetch();

            if (!$category) {
                exit('not found.');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit('error');
        }
    } else {
        exit('Invalid .');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Edit Category</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $category['name']; ?>">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control"><?php echo $category['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" <?php if ($category['status'] == 1) echo 'selected'; ?>>Active</option>
                    <option value="0" <?php if ($category['status'] == 0) echo 'selected'; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
