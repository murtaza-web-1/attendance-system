# 📝 Laravel Attendance & Task Management System

This project is a full-featured **Attendance and Task Management System** built using **Laravel 10**, **MySQL**, and **Postman API Testing**. It supports:

- ✅ Student registration, attendance marking, leave requests
- ✅ Admin panel with user management, task assignment, attendance control
- ✅ Role & Permission control using Spatie
- ✅ API + Blade support (hybrid system)

---

## ⚙️ Features

### 👤 User (Student)

- Register / Login via Blade UI
- Mark daily attendance
- Submit leave requests
- View attendance history
- Submit assigned tasks

### 🛠️ Admin

- Separate admin login
- View and manage all users
- Assign tasks to users
- Approve/reject submitted tasks
- View attendance (Present / Absent / Leave)
- Edit/Delete attendance records
- Filter attendance by date/status
- View leave requests and approve them
- Assign grades based on attendance
- Manage roles and permissions (Spatie package)

---

## 🧑‍💻 Tech Stack

- Laravel 10
- MySQL (phpMyAdmin)
- Blade Templates
- Spatie Laravel Permission
- Bootstrap 5.3
- Postman (API testing)

---

## 📁 Folder Structure

├── app/
├── database/
├── public/
├── resources/
│ └── views/
│ ├── auth/ # Login/Register pages
│ ├── admin/ # Admin panel views
│ ├── user/ # Student panel views
├── routes/
│ └── web.php
├── postman/
│ └── attendance-system-api.postman_collection.json
├── README.md


## 🔑 User Roles & Permissions

| Role     | Permissions                      |
|----------|----------------------------------|
| Admin    | Full access to all modules       |
| Student  | Can mark attendance and tasks    |
| Teacher/HR (optional) | Extendable roles    |

> Roles and permissions are managed using [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission).

---

## 🚀 Setup Instructions

1. Clone the repo:
   ```bash
   git clone https://github.com/murtaza-web-1/attendance-system.git
   cd attendance-system
2. Install dependencies:

bash
Copy
Edit
composer install
npm install && npm run dev
3. Create .env file and setup DB:

bash
Copy
Edit
cp .env.example .env
php artisan key:generate
4. Run migrations and seed default roles:

bash
Copy
Edit
php artisan migrate --seed
5. Start the server:

bash
Copy
Edit
php artisan serve
🧪 Postman API Collection
All APIs (Authentication, Attendance, Task Submissions) are testable in Postman.

📂 File: postman/attendance-system-api.postman_collection.json

👉 📥 Download Postman Collection

Folders in Postman:
Authentication

Register

Login

Student APIs

Mark Attendance

Submit Leave

Submit Task

Admin APIs

Approve/Reject Tasks

View Submissions

Manage Attendance


🙋 Author
Developed by Murtaza Ahmad
📍 university Town, peshawer
📱 0333-5910033
📧 mrkhan77707@gmail.com
🌐 GitHub: murtaza-web-1