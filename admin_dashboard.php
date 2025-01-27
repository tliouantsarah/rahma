<?php
session_start();

// Vérification de l'utilisateur connecté
$userProfile = [
    'name' => 'Sarah B.',
    'role' => 'Web Developer',
    'profileImage' => './images/user-profile.png'
];

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
      
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }
  
    
    body {
      overflow-x: hidden;
    }


    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 78px;
      background: #11101D;
      padding: 6px 14px;
      z-index: 1;
      transition: all 0.5s ease;
    }

    .sidebar.open {
      width: 250px;
    }

    .sidebar .logo-details {
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
    }

    .sidebar .logo-details .logo {
      color: #fff;
      background-image: url(photo_2024-12-27_19-59-47.jpg);
      font-size: 20px;
      font-weight: 600;
      opacity: 0;
      transition: all 0.5s ease;
    }

    .sidebar.open .logo-details,
    .sidebar.open .logo-details .logo_name {
      opacity: 1;
    }

    .sidebar .logo-details #btn {
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      font-size: 22px;
      text-align: center;
      cursor: pointer;
      transition: all 0.5s ease;
    }

    .sidebar.open .logo-details #btn {
      text-align: center;
    }

    .sidebar i {
      color: #fff;
      height: 60px;
      min-width: 50px;
      font-size: 28px;
      text-align: center;
      line-height: 60px;
    }

    .sidebar .nav-list {
      margin-top: 20px;
      height: 100%;
    }

    .sidebar li {
      position: relative;
      margin: 8px 0;
      list-style: none;
    }

    .sidebar input {
      font-size: 15px;
      color: #fff;
      font-weight: 400;
      outline: none;
      height: 50px;
      width: 100%;
      border: none;
      border-radius: 12px;
      transition: all 0.5s ease;
      background: #1d1b31;
    }

    .sidebar.open input {
      padding: 0 20px 0 50px;
      width: 100%;
    }

    .sidebar .bx-search {
      position: absolute;
      top: 50%;
      left: 0;
      transform: translateY(-50%);
      font-size: 22px;
      background: #1d1b31;
      color: #fff;
    }

    .sidebar .bx-search:hover {
      background: #fff;
      color: #11101D;
    }

    .sidebar.open .bx-search:hover {
      background: #1d1b31;
      color: #fff;
    }

    .sidebar li i {
      height: 50px;
      line-height: 50px;
      font-size: 18px;
      border-radius: 12px;
    }

    .sidebar li a {
      display: flex;
      height: 100%;
      width: 100%;
      border-radius: 12px;
      align-items: center;
      text-decoration: none;
      transition: all 0.4s ease;
      background: #14476B;
    }

    .sidebar li a:hover {
      background: #fff;
    }

    .sidebar li a .links_name {
      color: #fff;
      font-size: 15px;
      font-weight: 400;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: 0.4s;
    }

    .sidebar.open li a .links_name {
      opacity: 1;
      pointer-events: auto;
    }

    .sidebar li a:hover .links_name,
    .sidebar li a:hover i {
      transition: all 0.5s ease;
      color: #14476B;
    }

    .sidebar li .tooltip {
      position: absolute;
      top: -20px;
      left: calc(100% + 15px);
      background: #fff;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 15px;
      font-weight: 400;
      opacity: 0;
      white-space: nowrap;
      pointer-events: none;
      transition: 0s;
    }

    .sidebar li:hover .tooltip {
      opacity: 1;
      pointer-events: auto;
      transition: all 0.4s ease;
      top: 50%;
      transform: translateY(-50%);
    }

    .sidebar.open li .tooltip {
      display: none;
    }

    .sidebar li.profile {
      position: fixed;
      height: 60px;
      width: 78px;
      left: 0;
      bottom: -8px;
      padding: 10px 14px;
      background: #14476B;
      transition: all 0.5s ease;
      overflow: hidden;
    }

    .sidebar.open li.profile {
      width: 250px;
    }

    .sidebar li .profile-details {
      display: flex;
      align-items: center;
      flex-wrap: nowrap;
    }

    .sidebar li img {
      height: 45px;
      width: 45px;
      object-fit: contain;
      border-radius: 6px;
      margin-right: 10px;
    }

    .sidebar li.profile .name,
    .sidebar li.profile .job {
      font-size: 15px;
      font-weight: 400;
      color: #fff;
      white-space: nowrap;
    }

    .sidebar li.profile .job {
      font-size: 12px;
    }

    .sidebar .profile #log_out {
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      background: #1d1b31;
      width: 100%;
      height: 60px;
      line-height: 60px;
      transition: all 0.5s ease;
    }

    .sidebar.open .profile #log_out {
      width: 50px;
      background: none;
    }

    .home-section {
      position: relative;
      min-height: 100vh;
      top: 0;
      left: 78px;
      width: calc(100% -78px);
      transition: all 0.5s ease;
    }

    .sidebar.open~.home-section {
      left: 250px;
      width: calc(100%-250px);
    }

    .home-section .text {
      display: inline-block;
      color: #c6c5ca;
      font-size: 25px;
      font-weight: 500;
      margin: 18px;
    }

    .my-custom-class ol,
    .my-custom-class ul {
      padding-left: 0 rem;
    }
    .navbar {
  display: flex;
  justify-content: space-between; /* Pour séparer les éléments */
  align-items: center;
  padding: 10px 20px;
  background: #11101D;
  color: #fff;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px; /* Espace entre l'image et le texte */
}

.user-profile img {
  width: 40px;
  height: 40px;
  border-radius: 50%; /* Image ronde */
  object-fit: cover;
}

.user-info {
  text-align: right;
}

.user-info span {
  font-size: 16px;
  font-weight: 600;
  color: #fff;
}

.user-info small {
  font-size: 14px;
  color: #ccc;
}
.navbar {
  display: flex;
  justify-content: space-between; /* Pour séparer les éléments */
  align-items: center;
  padding: 10px 20px;
  background: #11101D;
  color: #fff;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px; /* Espace entre l'image et le texte */
}

.user-profile img {
  width: 40px;
  height: 40px;
  border-radius: 50%; /* Image ronde */
  object-fit: cover;
}

.user-info {
  text-align: right;
}

.user-info span {
  font-size: 16px;
  font-weight: 600;
  color: #fff;
}

.user-info small {
  font-size: 14px;
  color: #ccc;
}

.navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between; 
  padding: 10px 20px;
  background: #11101d38;
  z-index: 100;
}

.user-profile {
  margin-left: auto; 
}
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <div class="logo">
                <img src="image-removebg-preview (2).png" alt="">
            </div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <li>
            <i class='bx bx-search'></i>
            <input type="text" placeholder="Search...">
            <span class="tooltip">Search</span>
        </li>
        <li>
            <a href="?page=dash">
                <i class='bx bx-grid-alt'></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="?page=patients">
                <i class='bx bx-user'></i>
                <span class="links_name">Patients</span>
            </a>
            <span class="tooltip">Patients</span>
        </li>
        <li>
            <a href="?page=stock">
                <i class='bx bx-pie-chart-alt-2'></i>
                <span class="links_name">Stock</span>
            </a>
            <span class="tooltip">Stock</span>
        </li>
        <li>
            <a href="?page=protheses">
                <i class='bx bx-pie-chart-alt-2'></i>
                <span class="links_name">Prothèses</span>
            </a>
            <span class="tooltip">Prothèses</span>
        </li>
        <li>
            <a href="?page=rendez_vous">
                <i class='bx bx-folder'></i>
                <span class="links_name">Rendez-vous</span>
            </a>
            <span class="tooltip">Rendez-vous</span>
        </li>
        <li class="profile">
            <div class="profile-details">
                <img src="<?php echo $userProfile['profileImage']; ?>" alt="profileImg">
                <div class="name_job">
                    <div class="name"><?php echo $userProfile['name']; ?></div>
                    <div class="job"><?php echo $userProfile['role']; ?></div>
                </div>
            </div>
            <a href="?logout=true" id="log_out">
                <i class='bx bx-log-out'></i>
            </a>
        </li>
    </div>
    <section class="home-section">
        <div id="content-area">
            <?php
            // Gestion du contenu dynamique
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $file = $page . '.php';
                if (file_exists($file)) {
                    include $file;
                } else {
                    echo "<h2>Page non trouvée !</h2>";
                }
            } else {
                echo "<h2>Bienvenue sur le tableau de bord !</h2>";
            }
            ?>
        </div>
    </section>
    <script>
        const sidebar = document.querySelector(".sidebar");
        const closeBtn = document.querySelector("#btn");
        const logoutBtn = document.querySelector("#log_out");

        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange();
        });

        function menuBtnChange() {
            closeBtn.classList.toggle("bx-menu-alt-right", sidebar.classList.contains("open"));
            closeBtn.classList.toggle("bx-menu", !sidebar.classList.contains("open"));
        }

        logoutBtn.addEventListener("click", () => {
            const confirmLogout = confirm("Voulez-vous vraiment vous déconnecter ?");
            if (!confirmLogout) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>
