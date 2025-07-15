# Laravel Attendance & Task Management System

This Laravel project is a full-featured **student attendance and task submission system** with role-based access control using Spatie. It includes an admin panel, user panel, task submission and grading system, leave approvals, and attendance export functionality.

---

## 📂 Features

### ✅ Authentication
- Admin and User authentication system.
- Separate login/register for Admin and Users.
- Role-based access using Spatie Permission package.

### ✅ Admin Panel
- View all users and assign roles.
- Manage attendance (view, edit, delete).
- Approve/reject leave requests.
- View attendance summary (Present, Absent, Leave counts).
- Filter attendance by date range or status.
- Export attendance to Excel.
- Assign tasks to users.
- View submitted tasks from students.
- Approve/reject task submissions with feedback.
- Grade students based on attendance.
- Generate attendance reports between date ranges.

### ✅ User Panel
- View assigned tasks.
- Submit task responses (with feedback field).
- Mark attendance (Present, Absent, Leave).
- View personal attendance history.
- See attendance report.
- Auto-role assignment on registration (e.g., Student).

---

## 🔐 Roles & Permissions (via Spatie)

| Role     | Abilities                                       |
|----------|-------------------------------------------------|
| Admin    | Full access: attendance, tasks, grading, users. |
| Student  | Submit tasks, mark attendance, view reports.    |

Permissions are dynamically assigned using a role management panel.

---

## ⚙️ Tech Stack

- Laravel 10+
- MySQL
- Spatie/laravel-permission
- Bootstrap 5 (for UI)
- jQuery & AJAX (for dynamic content load)
- CKEditor (for rich text task description)
- Postman (for API testing)

---

## 📁 Folder Structure

- `resources/views/admin/` → Admin Blade views (dashboard, attendance, grading, etc.)
- `resources/views/user/` → User dashboard and task views.
- `app/Http/Controllers/` → Controllers for Admin, Auth, Attendance, Tasks.
- `routes/web.php` → Blade routes for admin & users.
- `routes/api.php` → API routes for task submission & mobile integration.

---

## 🚀 How to Run Locally

1. **Clone the repo:**
   ```bash
   git clone https://github.com/murtaza-web-1/attendance-system.git
   cd attendance-system

1- Install dependencies:
composer install
npm install && npm run dev

2- Environment setup:
cp .env.example .env
php artisan key:generate

3- Database setup:

Create a MySQL DB (e.g., attendance_system)

Update .env DB credentials

Run migrations and seed roles/permissions:

php artisan migrate
php artisan db:seed


4- run the app
php artisan serve

Admin Credentials (Sample)

Email: admin@gmail.com
Password: admin123


 Developer Notes
Uses admin_feedback field for task feedback.

Prevents duplicate task submissions.

Auto-role assignment on registration.

AJAX used in admin dashboard for partial updates.

Blade-based frontend and optional REST API support.


 Author
Name: Murtaza Ahmad

Email: mrkhan77707@gmail.com

GitHub: murtaza-web-1

