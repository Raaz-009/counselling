<?php
include("./config.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT student_details.id, student_details.name, student_details.usn, student_details.branch, student_details.semester, student_details.section";
$sql .= " FROM student_details";
$whereClause = "";

if ($searchTerm) {
  $whereClause .= " (name LIKE '%$searchTerm%' OR usn LIKE '%$searchTerm%' OR branch = '$searchTerm' OR semester = '$searchTerm')"; // Use = for numeric comparison
}

// Move GROUP BY after WHERE clause (if any)
if ($whereClause) {
  $sql .= " WHERE $whereClause";
}

$sql .= " GROUP BY branch, semester, section";

$result = $conn->query($sql);
$students = '';

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $branch = $row['branch'];
    $section = $row['section'];
    $semester = $row['semester'];
    $id = $row['id'];
    $students .= '
      <div class="col-md">
        <div class="solution_cards_box sol_card_top_3">
          <div class="solution_card">
            <div class="hover_color_bubble"></div>
            <div class="so_top_icon">
            <h3>batch: ' . $branch . '</h3>

            </div>
            <div class="solu_title">
              <h3>Semester ' . $semester . '</h3>
            </div>
            <div class="solu_description">
              <p> ' . $section . ' Section</p>
              <a href="./students.php?branch=' . $branch . '&sem='.$semester.'&sec='.$section.'" type="button" class="read_more_btn">View More</a>
            </div>
          </div>
        </div>
      </div>';
  }
} else {
  $students = "<p>No student found</p>";
}

$conn->close();
?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="./files/css/index.css">
  <link rel="stylesheet" href="./files/css/style.css">

</head>
<body>
  <nav class="navbar navBar navbar-expand-lg navbar-dark" style="background-color: #d8e6c3;">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">Orientation</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Add
            </a>
            <div class="dropdown-menu">
              <ul>
                <li><a class="dropdown-item" href="./add_student.php">Add Student Details</a></li>
                <li><a class="dropdown-item" href="#">Mega Menu Link</a></li>

              </ul>
            </div>
          </li>
          <li>
            &nbsp;

            &nbsp;
            &nbsp;
          </li>
          <li class="nav-item">
            <a class="btn btn-danger" href="./logout.php">Logout</a>
          </li>

        </ul>
        <form class="d-flex" role="search" id="searchForm">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="searchBar">
          <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container cardsContainer">
    <div class="section_our_solution">
      <div class="row" id="results">
        <?php echo $students; ?>
      </div>
    </div>
  </div>
  <div class="addbtn">
    
  <a href="./add_student.php" class="btn btn-primary btn-add-student">
    <p>Add Student</p>
    
    </svg>
  </a>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./files/js/main.js"></script></body>
</html>
