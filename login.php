<?php
session_start();
require 'includes/db_connect.php';

if (isset($_SESSION['user_type'])) {
    header('Location: ' . $_SESSION['user_type'] . '/dashboard.php');
    exit();
}

$module = isset($_GET['module']) ? $_GET['module'] : '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    if ($module === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
        $stmt->execute([$id]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['admin_password'])) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = 'admin';
            header('Location: admin/dashboard.php');
            exit();
        } else {
            $error = 'Invalid Admin ID or Password';
        }
    } elseif ($module === 'faculty') {
        $stmt = $conn->prepare("SELECT * FROM faculty WHERE faculty_id = ?");
        $stmt->execute([$id]);
        $faculty = $stmt->fetch();
        if ($faculty && password_verify($password, $faculty['faculty_password'])) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = 'faculty';
            header('Location: faculty/dashboard.php');
            exit();
        } else {
            $error = 'Invalid Faculty ID or Password';
        }
    } elseif ($module === 'student') {
        $stmt = $conn->prepare("SELECT * FROM students WHERE enrollment_num = ?");
        $stmt->execute([$id]);
        if ($stmt->fetch()) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = 'student';
            header('Location: student/dashboard.php');
            exit();
        } else {
            $error = 'Invalid Enrollment Number';
        }
    } else {
        $error = 'Invalid Module';
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center"><?php echo ucfirst($module); ?> Login</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="id" class="form-label"><?php echo $module === 'student' ? 'Enrollment Number' : 'ID'; ?></label>
                        <input type="text" class="form-control" id="id" name="id" required>
                    </div>
                    <?php if ($module !== 'student'): ?>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>