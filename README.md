# ⚡ SparkEnergies

### An Advanced Electricity Bill Management and Distribution Platform

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square\&logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-InnoDB-4479A1?style=flat-square\&logo=mysql\&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square\&logo=bootstrap\&logoColor=white)
![Course](https://img.shields.io/badge/Course-CSE370%3A%20Database%20Systems-red?style=flat-square)

A robust, full-stack database platform designed to eliminate ghost billing, automate utility accounting, and improve transparency across electricity distribution channels.

SparkEnergies is built on a normalized **Third Normal Form (3NF)** relational database architecture with strict integrity constraints, role-based access control, automated billing logic, and digital wallet management.

> 📄 **[Read Academic Project Report](documentation/project_report.pdf)**
> 🗄️ **[Database Schema SQL](database/sparkenergies.sql)**

---

## 👥 Engineering Team & Contributions

| Team Member                    | Student ID | Contribution                                               |
| ------------------------------ | ---------: | ---------------------------------------------------------- |
| **Mir Mohammad Sajedul Islam** |   23201376 | Full-Stack Development — Frontend & Backend Architecture   |
| **Bishal Golder**              |   23201378 | Full-Stack Development — Frontend & Backend Implementation |
| **Salman Naguib**              |   23201031 | Full-Stack Development — Frontend & Backend Integration    |

**Course:** CSE370 — Database Systems
**University:** BRAC University
**Semester:** Spring 2026
**Group:** 06

---

# 📌 Problem & System Overview

Traditional electricity utility workflows often depend on paper-based records, isolated meter readings, and manual billing processes. These limitations can lead to:

* Delayed payment processing
* Limited consumption transparency
* Duplicate or incorrect meter readings
* Billing disputes
* Poor tracking of field operations
* Difficulty managing customer balances and arrears

**SparkEnergies** addresses these challenges through a centralized relational database system with automated validation, billing, wallet reconciliation, and field-operation tracking.

## 🎯 Core System Objectives

### Zero Ghost Billing

Enforce strict validation rules to prevent duplicate, reversed, or invalid meter readings.

### Dynamic Ledger Management

Replace flat billing workflows with normalized tariff structures, automated bill generation, and real-time wallet reconciliation.

### Accountable Field Operations

Provide field technicians with assigned meter-reading tasks, historical reading records, timestamps, and employee-linked audit trails.

### Transparent Customer Billing

Allow customers to view bills, monitor consumption, manage wallet balances, and download detailed PDF invoices.

---

# 🚀 System Architecture

SparkEnergies follows a multi-tier architecture with strict **Role-Based Access Control (RBAC)**.

```text
                         ┌───────────────────────────────┐
                         │     SparkEnergies Core        │
                         │          Engine               │
                         └───────────────┬───────────────┘
                                         │
              ┌──────────────────────────┼──────────────────────────┐
              ▼                          ▼                          ▼
     ┌──────────────────┐      ┌──────────────────┐      ┌──────────────────┐
     │  Administrator   │      │ Field Technician │      │ Customer Portal  │
     │                  │      │                  │      │                  │
     │ Ledger & Node    │      │ Route & Meter    │      │ Wallet & Bills   │
     │ Management       │      │ Readings         │      │ & Analytics      │
     └──────────────────┘      └──────────────────┘      └──────────────────┘
```

The platform provides three primary environments:

1. **Administrator Dashboard**
2. **Field Technician Console**
3. **Customer Self-Service Portal**

---

# 🛡️ Administrator Dashboard

The administrator environment provides centralized control over system operations, customers, meters, employees, and financial records.

### 💰 Wallet & Financial Reconciliation

* Search registered customer profiles
* Inspect historical transactions
* Review customer balances
* Post digital wallet top-ups
* Monitor financial activity

### 👨‍💼 Staff Provisioning

* Register new field employees
* Create authentication profiles
* Assign employee identifiers
* Manage field personnel information

### 👥 Customer Directory

Administrators can search and filter registered customers based on:

* Customer status
* Account balance
* Customer category
* Tariff bracket
* Physical location
* Meter assignment

### ⚡ Smart Meter Inventory

The system maintains centralized meter inventory management.

Meter states dynamically change between:

```text
Available → Assigned
```

when a customer subscribes to a meter.

### 💸 Revenue Arrears Management

Administrators can identify overdue customer accounts using relational queries across billing and customer records.

This allows the system to generate an arrears queue for accounts requiring payment follow-up or service suspension.

---

# 🔧 Field Technician Console

The field technician environment is designed for meter-reading operations and field-level accountability.

### 📍 Assigned Routes & Meter Dispatch

Technicians can view:

* Assigned meter-reading tasks
* Customer locations
* Meter serial numbers
* Reading schedules

### 🕒 Chronological Reading History

Technicians can review previous meter readings and timestamps to:

* Identify neglected routes
* Maintain regular billing intervals
* Verify previous consumption values

### 📋 Audit Trails

Every processed meter reading is linked to the responsible employee, providing traceability for field operations.

---

# 👤 Customer Self-Service Portal

The customer portal provides customers with direct access to their electricity accounts.

### 💳 Real-Time Payment Settlement

Customers can:

* View billing history
* Check payment status
* Monitor wallet balance
* Pay outstanding bills using their digital wallet

Bills display clear status indicators such as:

```text
Paid
Unpaid
```

### ⚡ Dynamic Meter Provisioning

Customers can subscribe to category-specific meter configurations such as:

* Residential
* Commercial

The system automatically:

1. Checks meter availability
2. Assigns the appropriate meter
3. Deducts the required balance
4. Links the meter to the customer account
5. Updates the inventory status

### 📊 Consumption Analytics

The customer dashboard provides interactive **6-month electricity consumption analytics** using time-series charts.

Customers can track month-to-month changes in electricity usage.

### 🧾 Automated PDF Invoices

Customers can generate and download print-ready PDF invoices containing:

* Electricity consumption
* Unit charges
* VAT
* Demand charges
* Meter rent
* Total bill amount
* Payment status

---

# 🔐 Core Business Logic & Database Integrity

One of SparkEnergies' primary goals is to move critical business rules into the database and backend validation layer.

## 📈 Dynamic Multi-Slab Tariff Evaluation

Electricity pricing information is retrieved dynamically based on the customer's category and applicable tariff structure.

The system can evaluate:

* Unit cost
* VAT
* Demand charge
* Meter rent

This prevents tariff information from being unnecessarily duplicated across individual customer records.

---

## 🚫 Strict Meter Reading Validation

The system rejects invalid meter updates where:

```text
Current Reading < Previous Reading
```

This prevents reversed or manipulated meter readings from entering the billing system.

---

## 🔒 28-Day Billing Safety Lock

To prevent duplicate or fraudulent billing, the system compares the timestamp of an incoming meter reading with the latest reading stored for that meter.

If the new reading occurs within **28 days** of the previous reading, the system rejects the operation.

Conceptually:

```text
New Reading Date - Previous Reading Date < 28 days
                ↓
         Reject Reading
```

This provides an additional database-level protection against duplicate billing.

---

## 🔔 Automated Notifications

The system uses event-driven logic to notify customers when important account events occur, such as:

* Wallet top-ups
* Bill generation
* Payment updates
* Account changes

---

# 🗄️ Database Design

SparkEnergies uses a relational database designed according to **Third Normal Form (3NF)** principles.

### Key Database Features

* MySQL / MariaDB
* InnoDB storage engine
* Primary keys
* Foreign keys
* Referential integrity
* Database triggers
* Normalized relational structure
* Transaction management
* Automated validation rules

### Database Documentation

The repository includes:

| File                    | Description                                       |
| ----------------------- | ------------------------------------------------- |
| `ER_diagram.png`        | Entity-Relationship diagram                       |
| `schema_diagram.png`    | Relational schema representation                  |
| `normalized_schema.png` | 3NF database structure                            |
| `sparkenergies.sql`     | Complete database schema, triggers, and seed data |

---

# 🛠️ Technology Stack

| Layer                       | Technologies                                 |
| --------------------------- | -------------------------------------------- |
| **Backend**                 | PHP 8.2+                                     |
| **Database**                | MySQL / MariaDB                              |
| **Storage Engine**          | InnoDB                                       |
| **Frontend**                | Bootstrap 5, HTML5, CSS3, Vanilla JavaScript |
| **Data Visualization**      | Chart.js                                     |
| **PDF Generation**          | FPDF / Native PDF Generation                 |
| **Web Server**              | Apache                                       |
| **Development Environment** | XAMPP                                        |
| **Version Control**         | Git                                          |
| **IDE**                     | Visual Studio Code                           |

The backend uses **PHP prepared statements** to improve database security and reduce SQL injection risks.

---

# 📁 Repository Structure

```text
SparkEnergies/
│
├── database/
│   └── sparkenergies.sql
│
├── documentation/
│   ├── ER_diagram.png
│   ├── schema_diagram.png
│   ├── normalized_schema.png
│   └── project_report.pdf
│
├── src/
│   ├── index.php
│   ├── db.php
│   ├── admin_dashboard.php
│   ├── employee_dashboard.php
│   ├── customer_dashboard.php
│   ├── download_bill.php
│   ├── signup.php
│   └── logout.php
│
├── README.md
└── LICENSE
```

### Important Files

| File                     | Purpose                                        |
| ------------------------ | ---------------------------------------------- |
| `index.php`              | Authentication gateway and landing page        |
| `db.php`                 | Centralized database connection                |
| `admin_dashboard.php`    | Administrator operations and wallet management |
| `employee_dashboard.php` | Field route and meter-reading management       |
| `customer_dashboard.php` | Customer billing, wallet, and analytics        |
| `download_bill.php`      | PDF invoice generation                         |
| `signup.php`             | Customer registration                          |
| `logout.php`             | Secure session termination                     |

---

# 🚀 Local Installation & Setup

## 1. Clone the Repository

Open a terminal and run:

```bash
git clone https://github.com/sajedulislam5840/CSE370.git
cd CSE370
```

---

## 2. Start XAMPP

Launch the XAMPP Control Panel and start:

```text
Apache
MySQL
```

Make sure both services are running before continuing.

---

## 3. Create the Database

Open **phpMyAdmin** or the MySQL/MariaDB command line.

Create a database named:

```text
sparkenergies
```

For example:

```sql
CREATE DATABASE sparkenergies;
```

---

## 4. Import the Database Schema

Import:

```text
database/sparkenergies.sql
```

into the newly created `sparkenergies` database.

The SQL file contains the required:

* Tables
* Relationships
* Constraints
* Triggers
* Seed data

---

## 5. Configure Database Credentials

Open:

```text
src/db.php
```

Verify the database configuration:

```php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sparkenergies";
```

Update the username and password if your local MySQL/MariaDB installation uses different credentials.

---

## 6. Place the Project in XAMPP

Copy the project folder into:

```text
C:\xampp\htdocs\
```

Your directory should look similar to:

```text
C:\xampp\htdocs\CSE370\
```

---

## 7. Run the Application

Open your browser and navigate to:

```text
http://localhost/CSE370/src/index.php
```

The SparkEnergies authentication page should now be available.

---

# 🔑 System Roles

SparkEnergies supports three primary user roles:

| Role                 | Primary Responsibilities                                                        |
| -------------------- | ------------------------------------------------------------------------------- |
| **Administrator**    | Customer management, employee management, meters, wallets, billing, and arrears |
| **Field Technician** | Assigned routes, meter readings, reading history, and field operations          |
| **Customer**         | Bills, payments, wallet, meter subscription, and consumption analytics          |

Each role receives access only to the functionality required for its responsibilities.

---

# 📊 Key Features at a Glance

| Feature                  | Description                                                      |
| ------------------------ | ---------------------------------------------------------------- |
| 🔐 RBAC                  | Role-based access for administrators, technicians, and customers |
| ⚡ Smart Meter Management | Centralized meter inventory and assignment                       |
| 💳 Digital Wallet        | Customer balance and payment management                          |
| 🧾 Automated Billing     | Dynamic electricity bill calculation                             |
| 📈 Consumption Analytics | Six-month electricity usage visualization                        |
| 🔒 Billing Safety Lock   | Prevents readings within the restricted billing interval         |
| 🚫 Reading Validation    | Rejects decreasing meter readings                                |
| 👨‍🔧 Field Operations   | Technician route and meter-reading management                    |
| 🔔 Notifications         | Automated account and billing notifications                      |
| 🧾 PDF Invoices          | Downloadable itemized electricity bills                          |
| 🗄️ 3NF Database         | Normalized relational database architecture                      |
| 🔗 Referential Integrity | Foreign keys and relational constraints                          |

---

# 🎓 Academic Information

**Project:** SparkEnergies — Electricity Bill Management and Distribution Platform

**Course:** CSE370 — Database Systems
**University:** BRAC University
**Semester:** Spring 2026
**Group:** 06

This project was developed as an academic database systems project demonstrating practical implementation of:

* Relational database design
* Entity-Relationship modeling
* Database normalization
* SQL queries
* Constraints and triggers
* Transaction management
* Role-based access control
* Full-stack web development
* Database-driven business logic

---

# 📄 Documentation

Additional project documentation is available in the `documentation/` directory.

* 📄 **[Project Report](documentation/project_report.pdf)**
* 🗄️ **[Database Schema](database/sparkenergies.sql)**
* 🧩 **[ER Diagram](documentation/ER_diagram.png)**
* 🗂️ **[Schema Diagram](documentation/schema_diagram.png)**
* 📐 **[Normalized Schema](documentation/normalized_schema.png)**

---

# 📜 License

This project is developed for academic purposes as part of the **CSE370 — Database Systems** course at BRAC University.

See [`LICENSE`](LICENSE) for the applicable license terms.
