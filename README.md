# ⚡ SparkEnergies

### An Advanced Electricity Bill Management and Distribution Platform

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square\&logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-InnoDB-4479A1?style=flat-square\&logo=mysql\&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square\&logo=bootstrap\&logoColor=white)
![Course](https://img.shields.io/badge/Course-CSE370%3A%20Database%20Systems-red?style=flat-square)

**SparkEnergies** is a full-stack electricity bill management and distribution platform developed for **CSE370 — Database Systems** at **BRAC University**.

The system is designed to automate electricity billing, manage customer wallets, track meter readings, support field technicians, and maintain transparent utility operations through a normalized **Third Normal Form (3NF)** relational database.

---

## 👥 Team Members

| Name                           | Student ID | Contribution                                              |
| ------------------------------ | ---------: | --------------------------------------------------------- |
| **Mir Mohammad Sajedul Islam** |   23201376 | Full-Stack Development, Frontend & Backend Architecture   |
| **Bishal Golder**              |   23201378 | Full-Stack Development, Frontend & Backend Implementation |
| **Salman Naguib**              |   23201031 | Full-Stack Development, Frontend & Backend Integration    |

**Course:** CSE370 — Database Systems
**University:** BRAC University
**Semester:** Spring 2026
**Group:** 06

---

# 📌 Project Overview

Traditional electricity billing systems often depend on manual meter readings, paper-based records, and disconnected financial processes. These approaches can result in:

* Incorrect or duplicate meter readings
* Delayed billing and payment processing
* Limited visibility into electricity consumption
* Billing disputes
* Difficulty tracking field technicians
* Poor management of customer balances and arrears

**SparkEnergies** addresses these challenges by providing a centralized, database-driven platform for electricity distribution and billing management.

The system combines:

* Automated electricity billing
* Smart meter management
* Digital wallet management
* Customer consumption analytics
* Field technician operations
* Role-Based Access Control (RBAC)
* Database-level validation
* Automated PDF invoice generation
* Normalized relational database design

---

# 🎯 Core Objectives

### ⚡ Automated Billing

Generate electricity bills based on meter readings and dynamically evaluated tariff information.

### 🔒 Billing Integrity

Prevent invalid or duplicate meter readings through strict validation rules and database constraints.

### 💳 Digital Wallet Management

Allow customers to maintain balances and settle electricity bills digitally.

### 👨‍🔧 Field Accountability

Provide technicians with assigned meter-reading tasks and maintain historical records of field operations.

### 📊 Consumption Transparency

Provide customers with historical electricity consumption analytics.

### 🗄️ Normalized Database

Implement the system using a structured **3NF relational database** with primary keys, foreign keys, constraints, and triggers.

---

# 🏗️ System Architecture

SparkEnergies follows a role-based multi-user architecture.

```text
                         ┌──────────────────────────────┐
                         │      SparkEnergies Core      │
                         │            Engine            │
                         └──────────────┬───────────────┘
                                        │
              ┌─────────────────────────┼─────────────────────────┐
              │                         │                         │
              ▼                         ▼                         ▼
      ┌─────────────────┐      ┌─────────────────┐      ┌─────────────────┐
      │  Administrator  │      │ Field Technician│      │    Customer     │
      │                 │      │                 │      │                 │
      │ System & Ledger │      │ Meter & Route   │      │ Bills & Wallet  │
      │ Management      │      │ Management      │      │ & Analytics     │
      └─────────────────┘      └─────────────────┘      └─────────────────┘
```

The platform consists of three major environments:

1. **Administrator Dashboard**
2. **Field Technician Console**
3. **Customer Self-Service Portal**

---

# 🛡️ Administrator Dashboard

The administrator dashboard provides centralized control over customers, employees, meters, billing, and financial operations.

### 💰 Wallet & Financial Management

Administrators can:

* Search customer accounts
* View customer balances
* Review transaction history
* Add wallet balance
* Monitor financial activity

### 👨‍💼 Employee Management

Administrators can:

* Register field employees
* Create employee authentication profiles
* Assign employee identifiers
* Manage employee information

### 👥 Customer Management

Administrators can search and manage customers based on:

* Customer category
* Account status
* Account balance
* Location
* Meter assignment
* Tariff category

### ⚡ Smart Meter Inventory

The system maintains centralized meter inventory.

Meters transition between states such as:

```text
Available → Assigned
```

when a meter is successfully assigned to a customer.

### 💸 Revenue Arrears

Administrators can identify customers with unpaid bills and monitor overdue accounts for further action.

---

# 🔧 Field Technician Console

The field technician console supports meter-reading operations and field-level accountability.

### 📍 Assigned Meter Routes

Technicians can view:

* Assigned meter-reading tasks
* Customer locations
* Meter serial numbers
* Reading assignments

### 🕒 Reading History

Technicians can review previous readings and timestamps to verify consumption and maintain regular billing intervals.

### 📋 Audit Trail

Meter readings are associated with the responsible employee, providing accountability and traceability for field operations.

---

# 👤 Customer Portal

The customer portal provides customers with direct access to their electricity accounts.

## 💳 Digital Wallet & Payments

Customers can:

* View wallet balance
* View billing history
* Check bill status
* Pay outstanding bills
* Monitor payment transactions

Bills are displayed using clear statuses:

```text
Paid
Unpaid
```

---

## ⚡ Meter Subscription

Customers can subscribe to available meter categories such as:

* Residential
* Commercial

The system automatically handles:

1. Meter availability checking
2. Meter assignment
3. Balance deduction
4. Customer-meter relationship creation
5. Inventory status updates

---

## 📊 Consumption Analytics

The customer dashboard provides interactive **6-month electricity consumption analytics** using time-series charts.

This allows customers to monitor month-to-month changes in electricity usage.

---

## 🧾 PDF Invoice Generation

Customers can generate downloadable PDF invoices containing:

* Previous meter reading
* Current meter reading
* Electricity consumption
* Unit charges
* VAT
* Demand charges
* Meter rent
* Total bill
* Payment status

---

# 🔐 Business Logic & Database Integrity

SparkEnergies implements important business rules at both the application and database levels.

## 📈 Dynamic Tariff Evaluation

Electricity pricing is dynamically evaluated according to the customer's category and applicable tariff structure.

The system supports pricing components such as:

* Unit cost
* VAT
* Demand charge
* Meter rent

This approach avoids unnecessarily storing duplicated tariff information within individual customer records.

---

## 🚫 Meter Reading Validation

The system rejects invalid meter readings where:

```text
Current Reading < Previous Reading
```

This prevents reversed meter readings and helps protect the billing process from invalid input.

---

## 🔒 28-Day Billing Safety Lock

The system checks the time difference between the incoming meter reading and the most recent reading stored for that meter.

If the new reading occurs within the restricted billing interval, the system rejects the reading.

```text
New Reading Date - Previous Reading Date < 28 Days
                         ↓
                  Reading Rejected
```

This prevents duplicate billing within the defined billing period.

---

## 🔔 Automated Notifications

The system provides notification functionality for important account events, including:

* Wallet balance updates
* Bill generation
* Payment updates
* Account-related events

---

# 🗄️ Database Design

SparkEnergies uses a normalized relational database designed according to **Third Normal Form (3NF)** principles.

### Database Features

* MySQL / MariaDB
* InnoDB storage engine
* Primary keys
* Foreign keys
* Referential integrity
* Database constraints
* Database triggers
* Normalized relational structure
* Transaction management
* Automated validation

---

# 🧩 Database Documentation

The repository contains database design documentation in the **`Document/`** directory.

| File                    | Description                      |
| ----------------------- | -------------------------------- |
| `ER_diagram.png`        | Entity-Relationship Diagram      |
| `schema_diagram.png`    | Relational Schema Diagram        |
| `normalized_schema.png` | 3NF Database Structure           |
| `project_report.pdf`    | Complete Academic Project Report |

The database SQL file is available in the **`Database/`** directory.

---

# 🛠️ Technology Stack

| Layer                       | Technology                   |
| --------------------------- | ---------------------------- |
| **Backend**                 | PHP 8.2+                     |
| **Database**                | MySQL / MariaDB              |
| **Storage Engine**          | InnoDB                       |
| **Frontend**                | HTML5, CSS3, Bootstrap 5     |
| **JavaScript**              | Vanilla JavaScript           |
| **Charts**                  | Chart.js                     |
| **PDF Generation**          | FPDF / Native PDF Generation |
| **Web Server**              | Apache                       |
| **Development Environment** | XAMPP                        |
| **Version Control**         | Git                          |
| **IDE**                     | Visual Studio Code           |

The backend uses **PHP prepared statements** for safer database interaction and improved protection against SQL injection.

---

# 📁 Repository Structure

The repository is organized as follows:

```text
CSE370/
│
├── Database/
│   └── sparkenergies.sql
│
├── Document/
│   ├── ER_diagram.png
│   ├── schema_diagram.png
│   ├── normalized_schema.png
│   └── project_report.pdf
│
├── source/
│   ├── index.php
│   ├── db.php
│   ├── admin_dashboard.php
│   ├── employee_dashboard.php
│   ├── customer_dashboard.php
│   ├── download_bill.php
│   ├── signup.php
│   └── logout.php
│
├── LAB 01.pdf
├── LAB 02.pdf
├── LAB 03.pdf
├── README.md
├── LICENSE
└── gitignore.txt
```

> **Note:** The repository currently uses the folder names `Database`, `Document`, and `source`. The README intentionally matches the actual repository structure.

---

# 📄 Source Code

The main PHP application files are located inside:

```text
source/
```

### Important Files

| File                     | Purpose                                 |
| ------------------------ | --------------------------------------- |
| `index.php`              | Authentication gateway and landing page |
| `db.php`                 | Database connection                     |
| `admin_dashboard.php`    | Administrator dashboard                 |
| `employee_dashboard.php` | Field technician dashboard              |
| `customer_dashboard.php` | Customer dashboard                      |
| `download_bill.php`      | PDF invoice generation                  |
| `signup.php`             | Customer registration                   |
| `logout.php`             | Session termination                     |

---

# 🚀 Installation & Setup

## 1. Clone the Repository

```bash
git clone https://github.com/sajedulislam5840/CSE370.git
cd CSE370
```

---

## 2. Install XAMPP

Install **XAMPP** with:

* Apache
* MySQL

Start both services from the XAMPP Control Panel.

---

## 3. Create the Database

Open **phpMyAdmin** or the MySQL command line.

Create a database named:

```sql
CREATE DATABASE sparkenergies;
```

---

## 4. Import the SQL File

Import the following file into the `sparkenergies` database:

```text
Database/sparkenergies.sql
```

The SQL file contains the required:

* Tables
* Relationships
* Constraints
* Triggers
* Seed data

---

## 5. Configure Database Connection

Open:

```text
source/db.php
```

Configure the database credentials according to your local MySQL setup.

Example:

```php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sparkenergies";
```

If your MySQL installation uses a password, replace the empty password with your configured password.

---

## 6. Move the Repository to XAMPP

Copy the repository into:

```text
C:\xampp\htdocs\
```

The final directory should look like:

```text
C:\xampp\htdocs\CSE370\
```

---

## 7. Run the Application

Open your browser and visit:

```text
http://localhost/CSE370/source/index.php
```

The SparkEnergies login/authentication page should appear.

---

# 🔑 User Roles

| Role                       | Responsibilities                                                       |
| -------------------------- | ---------------------------------------------------------------------- |
| 👨‍💼 **Administrator**    | Customer, employee, meter, wallet, billing, and arrears management     |
| 👨‍🔧 **Field Technician** | Meter-reading assignments, field operations, and reading history       |
| 👤 **Customer**            | Bills, payments, wallet, meter subscription, and consumption analytics |

Each role has access only to the functionality required for its responsibilities.

---

# 📊 Key Features

| Feature                          | Description                                                          |
| -------------------------------- | -------------------------------------------------------------------- |
| 🔐 **Role-Based Access Control** | Separate environments for administrators, technicians, and customers |
| ⚡ **Smart Meter Management**     | Centralized meter inventory and customer assignment                  |
| 💳 **Digital Wallet**            | Customer balance and payment management                              |
| 🧾 **Automated Billing**         | Dynamic electricity bill calculation                                 |
| 📈 **Consumption Analytics**     | Six-month consumption visualization                                  |
| 🔒 **Billing Safety Lock**       | Prevents readings within the restricted billing period               |
| 🚫 **Reading Validation**        | Rejects decreasing meter readings                                    |
| 👨‍🔧 **Field Operations**       | Technician route and meter-reading management                        |
| 🔔 **Notifications**             | Automated account and billing notifications                          |
| 🧾 **PDF Invoices**              | Downloadable itemized electricity bills                              |
| 🗄️ **3NF Database**             | Normalized relational database architecture                          |
| 🔗 **Referential Integrity**     | Foreign-key-based relational consistency                             |

---

# 🎓 Academic Information

**Project:** SparkEnergies — Electricity Bill Management and Distribution Platform

**Course:** CSE370 — Database Systems
**University:** BRAC University
**Semester:** Spring 2026
**Group:** 06

### Academic Concepts Demonstrated

This project demonstrates practical implementation of:

* Relational database design
* Entity-Relationship modeling
* Database normalization
* Third Normal Form (3NF)
* SQL queries
* Primary and foreign keys
* Database constraints
* Database triggers
* Transaction management
* Role-Based Access Control
* Full-stack web development
* Database-driven business logic

---

# 📚 Project Documentation

All major project documentation is available inside the `Document/` directory.

* 📄 **Project Report:** `Document/project_report.pdf`
* 🧩 **ER Diagram:** `Document/ER_diagram.png`
* 🗂️ **Schema Diagram:** `Document/schema_diagram.png`
* 📐 **Normalized Schema:** `Document/normalized_schema.png`
* 🗄️ **Database SQL:** `Database/sparkenergies.sql`

---

# 📜 License

This project was developed for academic purposes as part of the **CSE370 — Database Systems** course at **BRAC University**.

See the [`LICENSE`](LICENSE) file for the applicable license terms.

---

## ⭐ Acknowledgment

Developed by **Group 06**
**CSE370 — Database Systems**
**BRAC University | Spring 2026**

### ⚡ SparkEnergies — Powering Smarter Utility Management
