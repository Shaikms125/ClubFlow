# 🚀 ClubFlow: The Ultimate Campus Orchestration Platform

**ClubFlow** is a specialized multi-role management ecosystem designed to empower university clubs with professional-grade organization tools. Built for scalability and security, ClubFlow bridges the gap between campus leadership and student organizations, transforming how club events are coordinated and how tasks are managed within a university.

> **One Platform. Every Club. Zero Friction.**

---

## 🌟 Why ClubFlow?

In a bustling university environment, club communication often gets lost in fragmented emails and social media groups. **ClubFlow** centralizes club operations:
*   **Centralized Oversight:** University Authorities monitor all club activities from one dashboard.
*   **Operational Excellence:** Club Admins delegate tasks and manage events with precision.
*   **Member Engagement:** Students track their contributions and stay informed in real-time.

### 👤 Multi-Role Architecture
*   **Authority (Admin):** Oversees all clubs, assigns club administrators, and manages global notices.
*   **Club Admin:** Manages specific club activities, assigns tasks to members, and publishes club-specific notices.
*   **Club Member:** Participates in events, tracks assigned tasks, and receives real-time notifications.

### 📅 Event Management 
*   **Flexible Organizers:** Events can be organized by specific Clubs, or co-branded with University Authority/Departments.
*   **Visual Content:** Support for event cover images with built-in validation for size and dimensions.
*   **Dynamic UI:** Interactive date badges and responsive layout for viewing event details.

### 📋 Task & Notice Systems
*   **Task Assignment:** Direct task allocation from admins to members with status tracking.
*   **Notice Board:** A centralized location for important announcements with pagination and search.
*   **Notifications:** Real-time feedback and alerts for members on new tasks and updates.

### 🛠️ Technical Highlights
*   **Security:** Built-in CSRF protection and secure password hashing (BCrypt).
*   **Search & Pagination:** Optimized database queries supporting search and paginated views across all major sections (Clubs, Events, Users).
*   **Aesthetics:** A modern, premium UI utilizing subtle glassmorphism, vibrant gradients, and responsive CSS.
*   **Data Integrity:** Centralized database models and core logic for enforcing data standards (e.g., lowercase usernames, unique constraints).

---

## 🚀 Tech Stack

*   **Backend:** PHP 8.x
*   **Database:** MySQL
*   **Frontend:** Vanilla CSS, JavaScript, HTML5
*   **Icons:** Font Awesome 4.7
*   **Security:** Native PHP Session Management & CSRF Helpers

---

## 🛠️ Installation & Setup

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/yourusername/ClubFlow.git
    ```
2.  **Database Configuration:**
    *   Import the provided SQL schema (clubflow_db.sql) into your MySQL database.
    *   Update `DB_connection.php` with your local database credentials:
      ```php
      $sName = "localhost";
      $uName = "root";
      $pass  = "";
      $db_name = "*anyname*";
      ```
3.  **Local Server:**
    *   Move the project to your local server directory (e.g., `xampp/htdocs`).
    *   Access the project via `http://localhost/ClubFlow`.

---

## 🎨 UI Preview

| Section | Feature |
| :--- | :--- |
| **Dashboard** | Statistical overview for different roles |
| **Events** | Dynamic cards with image overlays |
| **Management** | Advanced multi-select organizer UI |

---

## 📝 License
This project is for educational/campus purposes. Check with the administrator for specific licensing details.

---

*Built with ❤️ for campus communities.*
