<?php
// update_to_full_prompt.php
include 'includes/db.php';

echo "<h2>Migrating Database to Full Project Prompt Requirements...</h2>";

// 1. Create Categories Table
$sql1 = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql1)) {
    echo "Categories table created/verified.<br>";
}

// 2. Add Level and Category Link to Courses
// Note: We'll add level as an enum
$sql2 = "ALTER TABLE courses 
    ADD COLUMN IF NOT EXISTS level ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner',
    ADD COLUMN IF NOT EXISTS category_id INT";
if ($conn->query($sql2)) {
    echo "Courses table updated with Level and category_id.<br>";
}

// 3. Update Payments for Manual Support
$sql3 = "ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS receipt_url VARCHAR(255),
    ADD COLUMN IF NOT EXISTS admin_note TEXT";
if ($conn->query($sql3)) {
    echo "Payments table updated for manual payment support.<br>";
}

// 4. Populate some default categories
$categories = ['Development', 'Design', 'Business', 'Marketing', 'Photography', 'Music'];
foreach ($categories as $cat) {
    $conn->query("INSERT IGNORE INTO categories (name) VALUES ('$cat')");
}
echo "Default categories populated.<br>";

// 5. Link existing courses to category IDs if possible
$conn->query("UPDATE courses c JOIN categories cat ON c.category = cat.name SET c.category_id = cat.id");
echo "Existing courses linked to category IDs.<br>";

// Create Upload Folders
$folders = [
    'uploads/thumbnails',
    'uploads/videos',
    'uploads/pdfs',
    'uploads/receipts',
    'config'
];
foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
        echo "Created folder: $folder<br>";
    }
}

echo "<h3>Migration Complete!</h3>";
?>
