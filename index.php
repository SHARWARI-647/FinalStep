<!-- test change -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Clearance System</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
/* ===== RESET ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, sans-serif;
}

html {
    scroll-behavior: smooth;
}

/* ===== BODY ===== */
body {
    min-height: 100vh;
  background:
        linear-gradient(rgba(136, 219, 249, 0.6), rgba(55, 18, 18, 0.6));
    background-position: center;
}

/* ===== HEADER ===== */
.page-header {
    background: rgba(0, 62, 112, 0.95);
    padding: 15px 50px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 18px rgba(0,0,0,0.3);
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h1 {
    color: white;
    font-size: 1.8rem;
    letter-spacing: 1px;
}

/* NAVBAR */
.page-header nav a {
    color: white;
    text-decoration: none;
    margin-left: 22px;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 20px;
    transition: 0.3s ease;
}

.page-header nav a:hover {
    background: #ffdd57;
    color: #003e70;
}

/* ===== HERO SECTION ===== */
.hero {
    height: 90vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background:
        linear-gradient(rgba(249, 218, 136, 0.6), rgba(55, 18, 18, 0.6)),
        url('https://media.istockphoto.com/photos/diverse-international-students-celebrating-graduation-picture-id488520507?k=6&m=488520507&s=612x612&w=0&h=9kJKT6sOx5LQuEDi0R4cjG5w4sx3rrrXMImm6u6BhjI=');
    background-size: cover;
    background-position: center;
     position: relative;
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(
        rgba(0,0,0,0.65),
        rgba(0,77,122,0.65)
    );
}

/* HERO CARD */
.hero-card {
    position: relative;
    z-index: 1;
    text-align: center;
    color: white;
    max-width: 850px;
    padding: 50px;
    animation: fadeUp 1.2s ease;
}

.hero-card h2 {
    font-size: 2.8rem;
    margin-bottom: 20px;
    text-shadow: 0 4px 12px rgba(0,0,0,0.4);
}

.hero-card p {
    font-size: 1.1rem;
    line-height: 1.7;
    margin-bottom: 35px;
}

/* BUTTONS */
.btn-group a {
    display: inline-block;
    padding: 12px 28px;
    margin: 8px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.primary-btn {
    background: #00b894;
    color: white;
}

.secondary-btn {
    background: white;
    color: #003e70;
}

.btn-group a:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

/* ===== ABOUT SECTION ===== */
.about {
    max-width: 1000px;
    margin: 90px auto;
    padding: 70px 50px;
    background: white;
    border-radius: 25px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.2);
    text-align: center;
}

.about h2 {
    font-size: 2.3rem;
    color: #003e70;
    margin-bottom: 20px;
}

.about p {
    font-size: 1.05rem;
    color: #334155;
    margin-bottom: 30px;
    line-height: 1.7;
}

.about ul {
    list-style: none;
}

.about ul li {
    font-size: 1.05rem;
    margin: 12px 0;
}

/* ===== FOOTER ===== */
.footer {
    background: #003e70;
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: 80px;
}

/* ===== ANIMATION ===== */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        gap: 12px;
    }

    .hero-card h2 {
        font-size: 2rem;
    }

    .hero-card {
        padding: 30px;
    }

    .page-header nav a {
        margin: 6px;
    }
}
    </style>
</head>

<body>

<!-- ===== HEADER ===== -->
<header class="page-header">
    <div class="header-container">
        <h1>🎓 FinalStep...</h1>
        <nav>
            <a href="login_mainpage.php">Login</a>
            <a href="mainpage.php">Register</a>
            <a href="#about">About</a>
            <a href="contact.php">Contact</a>
        </nav>
    </div>
</header>

<!-- ===== HERO SECTION ===== -->
<section class="hero">
    <div class="hero-card">
        <h2>Online College Clearance System</h2>
        <p>
            Apply for college clearance digitally.  
            Get approvals from Department, Library & Store,  
            and receive your Clearance Certificate & Transfer Certificate (TC)
            after final Admin approval.
        </p>

        <div class="btn-group">
            <a href="login_mainpage.php" class="primary-btn">👨 Processed here...</a>
         </div>
    </div>
</section>

<!-- ===== ABOUT SECTION ===== -->
<section id="about" class="about">
    <h2>About Student Clearance System</h2>
    <p>
        The <strong>Student Clearance System</strong> is a web-based platform
        designed to automate the college clearance process.  
        It replaces manual paperwork with a fast, transparent, and secure system.
    </p>

    <ul>
        <li>✔ Online clearance application</li>
        <li>✔ Department, Library & Store approvals</li>
        <li>✔ Payment status verification</li>
        <li>✔ Automatic Clearance Certificate & TC generation</li>
        <li>✔ Admin final approval</li>
    </ul>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <p>© 2025 Student Clearance System | Academic Project</p>
</footer>

</body>
</html>
