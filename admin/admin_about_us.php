<?php include 'navbar_admin.php'; ?>
<?php 
include 'admin_dashboard.php'; 
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure Admin is Logged In
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['sections'] as $section => $content) {
        $escaped_content = mysqli_real_escape_string($conn, $content);
        
        // ✅ Handle Image Upload
        if (!empty($_FILES['images']['name'][$section])) {
            $image_tmp = $_FILES['images']['tmp_name'][$section];
            $image_name = basename($_FILES['images']['name'][$section]);
            $image_path = "assets/" . $image_name;
            move_uploaded_file($image_tmp, "../" . $image_path);
            $conn->query("UPDATE about_us SET content='$escaped_content', image='$image_path' WHERE section_name='$section'");
        } else {
            $conn->query("UPDATE about_us SET content='$escaped_content' WHERE section_name='$section'");
        }
    }

    echo "<script>alert('About Us updated successfully!'); window.location.href='admin_about_us.php';</script>";
}

// ✅ Fetch Current About Us Content
$result = $conn->query("SELECT * FROM about_us");
$about_us_data = [];
while ($row = $result->fetch_assoc()) {
    $about_us_data[$row['section_name']] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Us - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { width: 90%; max-width: 1000px; margin: 80px auto 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .section-title { font-size: 24px; font-weight: bold; color: #007bff; }
        .edit-box { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
        .image-preview { width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
        .file-input { display: block; margin-top: 5px; }
        .save-btn { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .save-btn:hover { background: #0056b3; }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="section-title text-center">Edit About Us Page</h1>

        <form action="admin_about_us.php" method="POST" enctype="multipart/form-data">
            <!-- ✅ Introduction Section -->
            <h2 class="section-title">Introduction</h2>
            <textarea class="edit-box" name="sections[introduction]" rows="4"><?php echo htmlspecialchars($about_us_data['introduction']['content']); ?></textarea>
            <img src="../<?php echo $about_us_data['introduction']['image']; ?>" class="image-preview">
            <input type="file" class="file-input" name="images[introduction]">

            <!-- ✅ Mission Section -->
            <h2 class="section-title">Our Mission</h2>
            <textarea class="edit-box" name="sections[mission]" rows="4"><?php echo htmlspecialchars($about_us_data['mission']['content']); ?></textarea>

            <!-- ✅ How It Works -->
            <h2 class="section-title">How It Works</h2>
            <textarea class="edit-box" name="sections[how_it_works]" rows="4"><?php echo htmlspecialchars($about_us_data['how_it_works']['content']); ?></textarea>
            <img src="../<?php echo $about_us_data['how_it_works']['image']; ?>" class="image-preview">
            <input type="file" class="file-input" name="images[how_it_works]">

            <!-- ✅ Why Choose Us -->
            <h2 class="section-title">Why Choose Us?</h2>
            <textarea class="edit-box" name="sections[why_choose_us]" rows="4"><?php echo htmlspecialchars($about_us_data['why_choose_us']['content']); ?></textarea>

            <!-- ✅ Testimonials -->
            <h2 class="section-title">Testimonials</h2>
            <textarea class="edit-box" name="sections[testimonials]" rows="4"><?php echo htmlspecialchars($about_us_data['testimonials']['content']); ?></textarea>

            <!-- ✅ Contact Information -->
            <h2 class="section-title">Contact Information</h2>
            <textarea class="edit-box" name="sections[contact]" rows="4"><?php echo htmlspecialchars($about_us_data['contact']['content']); ?></textarea>

            <!-- ✅ Save Button -->
            <button type="submit" class="save-btn">Save Changes</button>
        </form>

    </div>

</body>
</html>
