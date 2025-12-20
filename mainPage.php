<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Clearance Management System</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <style>
        /* ================================
           Reset & Body
        ================================= */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://wallpapers.com/images/hd/light-color-background-u5ajon1xr9puabyq.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
           
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 15px;
            margin: 20px;
        }

        h1, h3 {
            color: rgb(48, 5, 89);
            text-align: center;
        }

        #line {
            height: 2px;
            width: 100%;
            max-width: 600px;
            background-color: rgba(91, 17, 147, 1);
            margin: 20px 0;
            border-radius: 1px;
        }

        /* ================================
           Navigation Tabs
        ================================= */
        #tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 10px;
            margin: 20px auto;
            border-radius: 20px;
            background-color: rgba(255,255,255,0.8);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        #tabs a {
            background-color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: #303030;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #tabs a:hover {
            color: rgb(20, 150, 10);
            background-color: rgb(239, 239, 239);
        }

        /* ================================
           Registration Section
        ================================= */
        .register-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .register-item {
            background-color: rgba(71, 56, 160, 0.9);
            color: white;
            border-radius: 15px;
            padding: 20px 25px;
            text-align: center;
            width: 150px;
            transition: transform 0.3s, background 0.3s;
        }

        .register-item i {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }

        .register-item a {
            color: white;
            font-weight: 600;
            text-decoration: none;
        }

        .register-item:hover {
            background-color: rgba(31, 5, 73, 1);
            transform: translateY(-5px);
        }

        /* ================================
           Responsive
        ================================= */
        @media (max-width: 650px) {
            #tabs {
                flex-direction: column;
            }

            .register-section {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Student Clearance System</h1>
        <hr id="line">

        <!-- Navigation Tabs -->
        <div id="tabs">
            <a href="student.php"><i class="fas fa-home"></i> Home</a>
            <a href="Admin_login.php"><i class="fas fa-user-shield"></i> Administration</a>
            <a href="dept_Login.php"><i class="fas fa-building"></i> Department</a>
            <a href="Library_login.php"><i class="fas fa-book"></i> Library</a>
            <a href="Std_Login.php"><i class="fas fa-user-graduate"></i> Student</a>
            <a href="store_login.php"><i class="fas fa-store"></i> Store</a>
        </div>
        <hr id="line">

        <!-- Registration Section -->
        <h1>Register Here..</h1>
        <div class="register-section">
            <div class="register-item">
                <i class="fas fa-user-graduate"></i>
                <a href="Std_register.php">Student</a>
            </div>
            <div class="register-item">
                <i class="fas fa-user-shield"></i>
                <a href="admin_register.php">Admin</a>
            </div>
            <div class="register-item">
                <i class="fas fa-book"></i>
                <a href="Library_register.php">Library</a>
            </div>
            <div class="register-item">
                <i class="fas fa-building"></i>
                <a href="dept_register.php">Department</a>
            </div>
            <div class="register-item">
                <i class="fas fa-store"></i>
                <a href="store_register.php">Store</a>
            </div>
        </div>
    </div>
</body>
</html>
