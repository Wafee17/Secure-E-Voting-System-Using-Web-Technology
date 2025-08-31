# 🗳️ Online Voting System

A secure, web-based voting system built with PHP and MySQL that allows administrators to manage elections and voters to cast their votes electronically.

## ✨ Features

### 🔐 Authentication & Security
- **Admin Panel**: Secure admin interface with session-based authentication
- **Voter Authentication**: OTP-based verification system for secure voter access
- **Password Management**: Forgot password functionality with email verification
- **Session Security**: Protected routes and secure session handling

### 🏛️ Election Management
- **Create Elections**: Add new elections with customizable parameters
- **Edit Elections**: Modify existing election details
- **Delete Elections**: Remove elections when needed
- **Election Status**: Automatic status calculation (Active/Inactive) based on dates
- **Candidate Management**: Add and manage candidates for each election

### 👥 Voter System
- **Voter Registration**: Secure voter registration process
- **OTP Verification**: Two-factor authentication via email
- **Voting Interface**: User-friendly voting interface
- **Vote Validation**: Prevents duplicate voting
- **Real-time Results**: Live election results display

### 📊 Results & Analytics
- **Live Results**: Real-time voting results
- **Result Visualization**: Clear presentation of election outcomes
- **Vote Counting**: Accurate vote tallying system

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 4
- **Email Service**: PHPMailer for OTP delivery
- **Server**: Apache (XAMPP/WAMP)

## 📋 Prerequisites

Before running this project, ensure you have:

- **XAMPP** or **WAMP** server installed
- **PHP 7.4** or higher
- **MySQL 5.7** or higher
- **Apache** web server
- **Composer** (for PHPMailer dependencies)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/onlinevotingsystem.git
cd onlinevotingsystem
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Database Setup
1. Start your XAMPP/WAMP server
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `onlinevotingsystem`
4. Import the database schema from `assets/onlinevotingsystem.sql` file
   - In phpMyAdmin, select your database
   - Click on "Import" tab
   - Choose the SQL file and click "Go"
5. **Important**: The SQL file contains only table structure (no sample data)

### 4. Database Configuration
Update the database connection in `admin/inc/config.php`:
```php
$db = mysqli_connect("localhost", "root", "", "onlinevotingsystem") 
    or die("Connectivity Failed");
```

### 5. Email Configuration
Configure PHPMailer settings for OTP delivery in the relevant files:
- `forgot_password_request.php`
- `otp_verification.php`
- `verify_otp.php`

### 6. Admin Account Setup
**Important**: By default, all new user accounts are created with `user_role = 'Voter'`. To access the admin panel, you need to:

1. **Register a new account** through the voter interface
2. **Access phpMyAdmin** and go to the `users` table
3. **Edit the user record** and change `user_role` from `'Voter'` to `'Admin'`
4. **Save the changes**

**Alternative Method (SQL Command):**
```sql
UPDATE users SET user_role = 'Admin' WHERE username = 'your_username';
```

### 7. Access the Application
- **Admin Panel**: `http://localhost/onlinevotingsystem/admin/`
- **Voter Interface**: `http://localhost/onlinevotingsystem/`

## 🗄️ Database Schema

### Elections Table
```sql
CREATE TABLE `elections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `election_topic` varchar(255) DEFAULT NULL,
  `no_of_candidates` int(11) DEFAULT NULL,
  `starting_date` date DEFAULT NULL,
  `ending_date` date DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `inserted_by` varchar(255) DEFAULT NULL,
  `inserted_on` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Candidate Details Table
```sql
CREATE TABLE `candidate_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `election_id` int(11) DEFAULT NULL,
  `candidate_name` varchar(255) DEFAULT NULL,
  `party_details` text DEFAULT NULL,
  `candidate_photo` text DEFAULT NULL,
  `inserted_by` varchar(255) DEFAULT NULL,
  `inserted_on` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Users Table
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `mobile_number` varchar(45) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `user_role` varchar(45) DEFAULT 'Voter',
  `otp` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Votings Table
```sql
CREATE TABLE `votings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `election_id` int(11) DEFAULT NULL,
  `voters_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) NOT NULL,
  `vote_date` date DEFAULT NULL,
  `vote_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## 👨‍💼 Admin Panel Usage

### 1. Login
- Access: `http://localhost/onlinevotingsystem/admin/`
- Default credentials: Set during initial setup

### 2. Manage Elections
- **Add Election**: Create new elections with start/end dates
- **Edit Election**: Modify existing election details
- **Delete Election**: Remove elections (use with caution)

### 3. Manage Candidates
- **Add Candidates**: Upload candidate photos and details
- **Edit Candidates**: Modify candidate information
- **View Results**: Monitor election progress and results

## 👤 Voter Interface Usage

### 1. Registration
- Voters register with email and phone number
- System sends OTP for verification

### 2. Voting Process
- Login with verified credentials
- Select active elections
- Cast votes for preferred candidates
- View real-time results

## 🔧 Configuration

### Email Settings
Update PHPMailer configuration in the relevant files:
```php
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

### Security Settings
- Update session keys in admin files
- Configure proper file permissions
- Set secure database credentials

## 🚨 Security Features

- **SQL Injection Protection**: Prepared statements throughout
- **XSS Prevention**: Input sanitization and output escaping
- **Session Security**: Secure session management
- **Password Hashing**: Secure password storage
- **OTP Verification**: Two-factor authentication
- **Access Control**: Role-based access restrictions

## 📁 Project Structure

```
onlinevotingsystem/
├── admin/                 # Admin panel files
│   ├── inc/              # Admin includes
│   │   ├── config.php    # Database configuration
│   │   ├── header.php    # Admin header
│   │   ├── navigation.php # Admin navigation
│   │   ├── add_elections.php # Add elections
│   │   ├── editElection.php # Edit elections
│   │   ├── add_candidates.php # Add candidates
│   │   └── viewResults.php # View results
│   └── index.php         # Admin main page
├── voters/                # Voter interface
│   ├── inc/              # Voter includes
│   └── index.php         # Voter main page
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Images and photos
├── vendor/                # Composer dependencies
├── forgot_password_request.php # Password reset
├── otp_verification.php  # OTP verification
├── reset_password.php     # Password reset
├── verify_otp.php        # OTP validation
└── index.php             # Main entry point
```

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify XAMPP/WAMP is running
   - Check database credentials in `config.php`
   - Ensure MySQL service is active
   - **Note**: The database starts empty - you need to create your first admin account

2. **Email Not Sending**
   - Verify PHPMailer configuration
   - Check SMTP settings
   - Ensure proper email credentials

3. **Session Issues**
   - Check PHP session configuration
   - Verify file permissions
   - Clear browser cookies

4. **File Upload Errors**
   - Check upload directory permissions
   - Verify file size limits in PHP configuration
   - Ensure proper file types

## 🔄 Updates & Maintenance

### Regular Maintenance
- Monitor database performance
- Update dependencies regularly
- Backup database and files
- Check error logs

### Security Updates
- Keep PHP version updated
- Monitor security advisories
- Regular security audits
- Update third-party libraries

## 📝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Developer

**Developed at** [canaraengineering.in](https://canaraengineering.in)

## 🤝 Support

For support and questions:
- Create an issue on GitHub
- Contact: [Your Contact Information]
- Documentation: [Your Documentation URL]

## 📊 System Requirements

- **Server**: Apache 2.4+
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Memory**: Minimum 128MB RAM
- **Storage**: Minimum 100MB free space
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge)

## 🚀 Deployment

### Production Deployment
1. Update database credentials
2. Configure production email settings
3. Set proper file permissions
4. Enable HTTPS
5. Configure backup systems
6. Set up monitoring

### Docker Deployment (Optional)
```dockerfile
FROM php:7.4-apache
COPY . /var/www/html/
RUN docker-php-ext-install mysqli
EXPOSE 80
```

---

**Note**: This is a development version. For production use, ensure proper security measures, SSL certificates, and regular security audits.
