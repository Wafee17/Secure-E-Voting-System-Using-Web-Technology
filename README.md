
# 🗳️ Secure-E-Voting-System-Using-Web-Technology

A secure and user-friendly Online Voting System built using PHP and MySQL. This project supports user registration, login, voting, and a password reset feature using OTP (sent via PHPMailer).

## 🚀 Features

- Voter registration and login  
- Candidate registration and management  
- Voting functionality (one vote per user)  
- OTP-based password reset using PHPMailer  
- Admin dashboard to monitor results  

## 📁 Project Structure

```
/online-voting-system
│
├── config/               # Database configuration
├── includes/             # PHP utility functions
├── phpmailer/            # PHPMailer library
├── assets/               # CSS, JS, images
├── sql/                  # Database schema and data
├── index.php             # Landing page
├── login.php             # Voter login
├── register.php          # Voter registration
├── vote.php              # Voting page
├── forgot-password.php   # OTP-based reset
└── ...
```

## 🧰 Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP (v7+)  
- **Database:** MySQL  
- **Mailer:** PHPMailer  

## ⚙️ Prerequisites

Make sure the following are installed:

- [XAMPP](https://www.apachefriends.org/) / [MAMP](https://www.mamp.info/en/) / [WAMP](https://www.wampserver.com/en/)  
- PHP 7.4 or later  
- MySQL  
- Composer (optional, if PHPMailer is already included)

## 🛠️ Installation & Deployment

### 1. Clone the repository

```bash
git clone https://github.com/Wafee17/Secure-E-Voting-System-Using-Web-Technology.git
```

### 2. Move the project to your server directory

For XAMPP users:

```bash
mv online-voting-system/ C:\xampp\htdocs\
```

### 3. Start Apache and MySQL

Open XAMPP control panel and start **Apache** and **MySQL**.

### 4. Create the database

- Open [phpMyAdmin](http://localhost/phpmyadmin)  
- Create a new database named `voting_system`  
- Import the SQL file from `/sql/voting_system.sql`

### 5. Configure the database

Open `config/db.php` (or relevant file) and update the DB credentials:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "voting_system";
```

### 6. Configure PHPMailer

Open the PHPMailer config file (e.g., `includes/mail.php`) and update SMTP details:

```php
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
```

> ⚠️ Enable "Less Secure Apps" in Gmail or use "App Password" if 2FA is enabled.

### 7. Access the project in browser

Go to:

```
http://localhost/online-voting-system/
```

## 👨‍💻 Default Credentials (Optional)

```text
Admin:
Email: admin@example.com
Password: admin123
```

## 📸 Screenshots

_Add screenshots here if available._

## ✅ To-Do

- [ ] Add two-factor authentication  
- [ ] Deploy to production server  
- [ ] Add election timeline support  

## 📄 License

MIT License - feel free to use, modify, and build upon this project.

## 🙌 Acknowledgements

- [PHPMailer](https://github.com/PHPMailer/PHPMailer)  
- [Bootstrap](https://getbootstrap.com/)  
- [XAMPP](https://www.apachefriends.org/)
