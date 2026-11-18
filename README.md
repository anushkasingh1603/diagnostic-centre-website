# 🏥 Burdwan Scan Centre

A full-stack web application for a diagnostic centre, built with PHP and MySQL. The platform lets patients book appointments, securely download their reports, and make online payments, while giving administrators a complete dashboard to manage the centre's day-to-day operations.

🔗 **Live Site:** [diagnosticscancentre.freepage.cc](http://diagnosticscancentre.freepage.cc)

---

## ✨ Features

### Patient-Facing
- 📅 **Online Appointment Booking** — patients can book scans and tests with available time slots
- 📄 **Secure Report Download** — patients retrieve reports using unique, randomly generated report codes
- 💳 **Online Payments** — integrated Razorpay payment gateway for booking fees
- 🖼️ **Services & Gallery** — browse available diagnostic services and centre gallery
- 💼 **Careers** — view and apply for open positions
- 📝 **Feedback** — patients can share their experience

### Admin Panel
- 🔐 **Secure Login** — admin authentication with bcrypt password hashing
- 📊 **Dashboard** — quick overview of appointments, notices, doctors, and centre data
- 🗂️ **Full Management** — control appointments, reports, doctors, gallery, careers, notices, and feedback
- 📤 **Report Upload** — attach patient reports to their unique codes

---

## 🛠️ Tech Stack

| Category | Technologies |
|----------|-------------|
| **Backend** | PHP |
| **Database** | MySQL |
| **Frontend** | HTML, CSS, JavaScript, Bootstrap 5 |
| **Payments** | Razorpay API |
| **Security** | Bcrypt password hashing, random report codes |
| **Hosting** | InfinityFree |
| **Local Dev** | XAMPP (Apache + MySQL) |

---

## 🔒 Security Notes

Sensitive configuration files (database credentials, payment API keys) are excluded from this repository via `.gitignore`. To run the project, copy `payment_config.example.php` to `payment_config.php` and add your own keys, and configure your database connection in `db.php`.

---

## 📌 About

This project was designed and developed for **Burdwan Scan Centre Pvt. Ltd.**, Burdwan, West Bengal — a real diagnostic centre — to bring their services online with appointment management, report delivery, and digital payments.

---

## 👩‍💻 Author

**Anushka Singh**  
B.Tech, Information Technology  
[GitHub](https://github.com/anushkasingh1603)
