# FinalStep – Student Clearance Management System

FinalStep is a **web-based Student Clearance Management System** developed using **PHP and MySQL**.  
It digitizes the traditional student clearance process and includes **OTP-based Email Verification** for secure authentication.

---

## 📌 Project Overview

In many colleges, the clearance process is manual, time-consuming, and paper-based.  
**FinalStep** provides a centralized, secure, and automated platform where students can apply for clearance online and departments can approve it digitally.

---

## 🔐 Key Feature: OTP Email Verification

The system uses **OTP (One-Time Password) Email Verification** during registration to ensure security.

### OTP Workflow:
1. User enters email during registration
2. System generates a 6-digit OTP
3. OTP is sent to the user's email using **PHPMailer**
4. User verifies OTP
5. Registration is completed only after successful verification

This prevents fake accounts and ensures valid user access.

---

## 👥 User Roles
| Role              | Responsibilities                                                                                                                                                                         |
| ----------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 👨‍🎓 **Student** | • Register with **OTP email verification**<br>• Login securely<br>• Apply for clearance<br>• Track clearance status<br>• Download **Clearance Certificate / Transfer Certificate (PDF)** |
| 👨‍💼 **Admin**   | • Monitor entire system<br>• View all students<br>• Provide **final clearance approval**<br>• Generate **Transfer Certificates**                                                         |
| 🏫 **Department** | • Verify departmental clearance<br>• Check departmental dues<br>• Approve or reject clearance requests                                                                                   |
| 📚 **Library**    | • Check library dues<br>• Approve library clearance                                                                                                                                      |
| 🏪 **Store**      | • Verify equipment returns<br>• Check store dues<br>• Approve clearance                                                                                                                  |

---

## 🚀 Features

- Secure Registration & Login
- OTP-based Email Verification
- Role-based Dashboards
- Online Clearance Application
- Department-wise Approval
- PDF Generation (Clearance Certificate & TC)
- Session-based Authentication
- Clean & User-Friendly UI

---

## 🛠️ Technologies Used

| Category            | Technologies                                 |
| ------------------- | -------------------------------------------- |
| **Frontend**        | HTML, CSS, JavaScript                        |
| **Backend**         | PHP                                          |
| **Database**        | MySQL                                        |
| **Server**          | XAMPP (Apache & MySQL)                       |
| **Libraries**       | PHPMailer (OTP Email), FPDF (PDF Generation) |
| **Version Control** | Git & GitHub                                 |
| **Code Editor**     | Visual Studio Code (VS Code)                 |

---

## 📂 Project Structure

```

FinalStep/
│
├── admin/
├── department/
├── library/
├── store/
├── student/
├── uploads/        # ignored using .gitignore
├── style/
├── db.php
├── index.php
├── README.md
└── .gitignore

````

---

## ⚙️ Installation & Setup

1. Install **XAMPP**
2. Clone the repository:
   ```bash
   git clone https://github.com/SHARWARI-647/FinalStep.git
````

3. Move project to:

   ```
   C:\xampp\htdocs\
   ```
4. Start **Apache** and **MySQL**
5. Import database into **phpMyAdmin**
6. Configure database in `db.php`
7. Open browser:

   ```
   http://localhost/FinalStep
   ```

---

## 🔒 Security Features

* OTP Email Verification
* Session-based login
* Role-based access control
* Restricted unauthorized access

---

## 📈 Future Enhancements

* Password hashing (bcrypt)
* SMS OTP verification
* Mobile responsive UI
* Email notifications
* Analytics dashboard
* REST API integration

---

## 👩‍💻 Developed By

**Sharwari Rahangdale**
Student | Web Developer

---

## 📄 License

This project is developed for **educational purposes only**.

````

---

## ✅ Final Git Commands

```bash
git add README.md
git commit -m "Added complete project README"
git push
````

