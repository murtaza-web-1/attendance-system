# 📲 Laravel Attendance System – API Documentation

This file documents all the available API endpoints for the **Laravel Attendance & Task Management System**, which are used for mobile apps or Postman-based testing.

---
## 🧪 Postman API Collection

All APIs (Authentication, Student, Admin) are documented and testable using Postman.

📁 Download or import this collection into your Postman:
[📥 Click here to import](postman/attendance-system-api.postman_collection.json)


## 🔗 API Testing with Postman

A complete Postman collection is available in the `postman/` folder.

### 🔹 Includes:

- User Registration & Login
- Admin Login
- Attendance Submission
- Leave Marking
- Task Submission
- Admin Approval

### 📩 Import to Postman:
[📥 Download Postman Collection](postman/attendance-system-api.postman_collection.json)


## 🔐 Authentication

### 👉 POST `/api/login`

**Description:** Authenticate a student and get an API token.

**Request Body:**
```json
{
  "email": "user@gmail.com",
  "password": "user123"
}

Response: json
{
  "message": "Login successful",
  "token": "YOUR_API_TOKEN"
}

Use this token in Authorization header for protected API routes:
Authorization: Bearer YOUR_API_TOKEN


GET /api/attendance
Get the logged-in user's attendance records.
Headers:
Authorization: Bearer <token>
Response: json
[
  {
    "date": "2025-07-14",
    "status": "Present"
  }
]
 POST /api/attendance/mark
 Mark attendance (Present/Absent/Leave).
 Request Body: json
 {
  "status": "Present"
}


 Leave APIs
  POST /api/leave
    Submit a leave request. 
json
{
  "date": "2025-07-15",
  "reason": "Not feeling well"
}


 Task Submission
 POST /api/tasks/{id}/submit
Submit a response to a task.
Headers:
Authorization: Bearer <token>
Request Body:
json
{
  "response": "My completed task response."
}

Response:
json
{
  "message": "Task submitted successfully",
  "data": {
    "task_id": 12,
    "user_id": 5,
    "response": "My completed task response.",
    "status": "pending"
  }
}

GET /api/my-submissions
View all task submissions made by the logged-in user.
Response:
json
[
  {
    "task": {
      "title": "Complete React Project"
    },
    "response": "Submitted solution",
    "status": "approved"
  }
]

 Admin-Only APIs
👉 GET /api/admin/submissions
Get all task submissions from all students.
Response:
json
[
  {
    "user": {
      "name": "user"
    },
    "task": {
      "title": "Create Login Page"
    },
    "response": "Done",
    "status": "pending"
  }
]

 POST /api/admin/submission/{id}
Approve or reject a task submission.
Request Body:
json
{
  "status": "approved",
  "admin_feedback": "Good work!"
}

Response:
json
{
  "message": "Status updated successfully",
  "data": {
    "status": "approved",
    "admin_feedback": "Good work!"
  }
}


🧪 Postman Collection
You can import the full Postman collection from the exported file in your project:

Collection Name: Attendance System API

Folders:

Authentication

Student APIs

Admin APIs



🧠 Notes
All API responses are in JSON.

Tokens are required for all protected endpoints.

Admin and user APIs are separated.

Admin tasks must be performed by users with proper roles.


👨‍💻 Developer Info
Developer: Murtaza Ahmad

Email: mrkhan77707@gmail.com

GitHub: murtaza-web-1