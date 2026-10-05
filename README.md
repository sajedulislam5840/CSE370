<div align="center">

# ⚡ SparkEnergies

### **Smart Electricity Billing & Distribution Platform**

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=22&pause=1000&color=00C853&center=true&vCenter=true&width=700&lines=Automated+Electricity+Billing;Smart+Meter+Management;Digital+Wallet+%26+Payments;Field+Technician+Operations;3NF+Database+Architecture;Built+for+CSE370+%7C+BRAC+University" alt="Typing SVG" />

<br>

<img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/MySQL-InnoDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
<img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
<img src="https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">

<br>

<img src="https://img.shields.io/badge/Database-3NF-success?style=flat-square" alt="3NF">
<img src="https://img.shields.io/badge/Architecture-Full--Stack-blue?style=flat-square" alt="Full Stack">
<img src="https://img.shields.io/badge/Course-CSE370-red?style=flat-square" alt="CSE370">
<img src="https://img.shields.io/badge/University-BRAC%20University-orange?style=flat-square" alt="BRAC University">

<br><br>

**A database-driven electricity management ecosystem built to make utility billing smarter, safer, and more transparent.**

<br>

[📖 Project Report](Document/project_report.pdf) •
[🗄️ Database Schema](Database/sparkenergies.sql) •
[🧩 ER Diagram](Document/ER_diagram.png) •
[📊 Schema Diagram](Document/schema_diagram.png)

</div>

---

## 🧭 Navigation

* [✨ Overview](#-overview)
* [🎯 Objectives](#-objectives)
* [🏗️ Architecture](#️-architecture)
* [👨‍💼 Administrator](#-administrator-dashboard)
* [👨‍🔧 Technician](#-field-technician-console)
* [👤 Customer](#-customer-portal)
* [🔐 Business Logic](#-business-logic--database-integrity)
* [🗄️ Database](#️-database-design)
* [🛠️ Technology Stack](#️-technology-stack)
* [📁 Repository](#-repository-structure)
* [🚀 Installation](#-installation--setup)
* [👥 Team](#-team)
* [🎓 Academic Information](#-academic-information)

---

# ✨ Overview

**SparkEnergies** is a full-stack electricity bill management and distribution platform developed for **CSE370 — Database Systems** at **BRAC University**.

The platform combines electricity billing, smart meter management, digital wallets, field technician operations, customer analytics, and database-level integrity rules into one centralized system.

### ⚡ The Core Idea

```text
          METER READING
                │
                ▼
       ┌─────────────────┐
       │   VALIDATION    │
       │  & SAFETY LOCK  │
       └────────┬────────┘
                │
                ▼
       ┌─────────────────┐
       │  TARIFF ENGINE  │
       └────────┬────────┘
                │
                ▼
       ┌─────────────────┐
       │  BILL GENERATOR │
       └────────┬────────┘
                │
                ▼
       ┌─────────────────┐
       │ DIGITAL WALLET  │
       │    PAYMENT      │
       └────────┬────────┘
                │
                ▼
       ┌─────────────────┐
       │ CUSTOMER PORTAL │
       └─────────────────┘
```

---

# 🎯 Objectives

SparkEnergies was designed around four major goals:

| ⚡ Objective                   | 💡 Purpose                                                     |
| ----------------------------- | -------------------------------------------------------------- |
| **Zero Ghost Billing**        | Prevent duplicate, reversed, and invalid meter readings        |
| **Dynamic Ledger Management** | Automate tariffs, billing, payments, and wallet reconciliation |
| **Field Accountability**      | Track technicians, routes, meter readings, and timestamps      |
| **Customer Transparency**     | Provide bills, payments, consumption analytics, and invoices   |

---

# 🏗️ Architecture

SparkEnergies follows a multi-role architecture based on **Role-Based Access Control (RBAC)**.

```text
                           ┌─────────────────────────────┐
                           │     ⚡ SPARKENERGIES ⚡     │
                           │       CORE ENGINE           │
                           └──────────────┬──────────────┘
                                          │
              ┌───────────────────────────┼───────────────────────────┐
              │                           │                           │
              ▼                           ▼                           ▼
   ┌────────────────────┐      ┌────────────────────┐      ┌────────────────────┐
   │   👨‍💼 ADMINISTRATOR │      │   👨‍🔧 TECHNICIAN   │      │     👤 CUSTOMER    │
   ├────────────────────┤      ├────────────────────┤      ├────────────────────┤
   │ • Customer Mgmt    │      │ • Assigned Routes  │      │ • View Bills       │
   │ • Employee Mgmt    │      │ • Meter Readings   │      │ • Wallet           │
   │ • Meter Inventory  │      │ • Reading History  │      │ • Payments         │
   │ • Wallet Control   │      │ • Audit Trail      │      │ • Analytics        │
   │ • Arrears          │      │ • Field Operations │      │ • PDF Invoices     │
   └────────────────────┘      └────────────────────┘      └────────────────────┘
```

### 🔄 System Flow

```text
Customer
   │
   ├──► Meter Subscription
   │
   ├──► Meter Assigned
   │
   ▼
Field Technician
   │
   ├──► Meter Reading
   │
   ├──► Validation
   │
   ▼
Billing Engine
   │
   ├──► Tariff Calculation
   ├──► VAT
   ├──► Demand Charge
   ├──► Meter Rent
   │
   ▼
Electricity Bill
   │
   ▼
Digital Wallet
   │
   ▼
Payment Settlement
   │
   ▼
Customer Dashboard
```

---

# 👨‍💼 Administrator Dashboard

The administrator controls the core operational and financial components of SparkEnergies.

### 💰 Wallet & Financial Management

* Search customer accounts
* View wallet balances
* Review transaction history
* Perform wallet top-ups
* Monitor financial activity

### 👥 Customer Management

Administrators can search and manage customers according to:

* Customer category
* Account status
* Balance
* Location
* Meter assignment
* Tariff category

### 👨‍💼 Employee Management

* Register field employees
* Create authentication profiles
* Assign employee identifiers
* Maintain employee information

### ⚡ Smart Meter Inventory

Meters are centrally managed and their status changes automatically:

```text
┌───────────┐
│ AVAILABLE │
└─────┬─────┘
      │
      │ Customer Subscription
      ▼
┌───────────┐
│ ASSIGNED  │
└───────────┘
```

### 💸 Revenue Arrears

Administrators can identify customers with unpaid bills and generate an arrears queue for follow-up and potential service suspension.

---

# 👨‍🔧 Field Technician Console

The technician dashboard is designed for real-world meter-reading operations.

### 📍 Assigned Routes

Technicians can access:

* Assigned meter-reading tasks
* Customer locations
* Meter serial numbers
* Reading schedules

### 🕒 Reading History

Historical readings and timestamps allow technicians to:

* Verify previous readings
* Maintain billing intervals
* Identify neglected routes
* Monitor consumption progression

### 📋 Audit Trail

Every processed meter reading is associated with the responsible employee, creating a traceable field-operation history.

---

# 👤 Customer Portal

The customer dashboard provides a complete self-service electricity management experience.

### 💳 Digital Wallet

Customers can:

* View current balance
* Add balance
* Review transactions
* Pay outstanding bills

### 🧾 Billing

Customers can view:

* Current bills
* Previous bills
* Payment status
* Consumption
* Charges
* Total amount

```text
       ┌─────────────┐
       │   💳 WALLET │
       └──────┬──────┘
              │
              ▼
       ┌─────────────┐
       │  🧾 INVOICE │
       └──────┬──────┘
              │
       ┌──────▼──────┐
       │   PAYMENT   │
       └─────────────┘
```

### ⚡ Meter Subscription

Customers can subscribe to available meter categories such as:

* 🏠 Residential
* 🏢 Commercial

The system automatically:

1. Checks meter availability
2. Assigns the meter
3. Deducts the required balance
4. Links the meter to the customer
5. Updates inventory status

### 📊 Consumption Analytics

Interactive **6-month electricity consumption charts** allow customers to monitor month-to-month usage.

### 🧾 PDF Invoices

Customers can generate print-ready invoices containing:

* Previous reading
* Current reading
* Consumption
* Unit charges
* VAT
* Demand charges
* Meter rent
* Total bill
* Payment status

---

# 🔐 Business Logic & Database Integrity

SparkEnergies does not rely solely on the frontend for validation. Critical business rules are enforced through backend logic and database constraints.

## 🚫 Meter Reading Protection

The system rejects:

```text
Current Reading < Previous Reading
```

This prevents reversed or invalid consumption data.

---

## 🔒 28-Day Billing Safety Lock

The system compares the incoming meter-reading timestamp against the latest stored reading.

```text
New Reading Date
       │
       ▼
Previous Reading Date
       │
       ▼
Difference < 28 Days?
       │
   ┌───┴───┐
  YES      NO
   │        │
   ▼        ▼
REJECT    ACCEPT
```

This prevents duplicate billing within the restricted billing interval.

---

## 📈 Dynamic Tariff Calculation

Tariff information is evaluated dynamically according to the customer's category.

The system supports:

* Unit cost
* VAT
* Demand charge
* Meter rent

This avoids unnecessary duplication of pricing information across customer records.

---

## 🔔 Notifications

Important account events can trigger notifications, including:

* Wallet top-ups
* Bill generation
* Payment updates
* Account events

---

# 🗄️ Database Design

SparkEnergies follows **Third Normal Form (3NF)** principles.

### 🔗 Database Characteristics

* MySQL / MariaDB
* InnoDB
* Primary Keys
* Foreign Keys
* Referential Integrity
* Database Constraints
* Triggers
* Normalized Relations
* Transaction Management
* Automated Validation

### 📐 Database Documentation

| Resource             | Location                         |
| -------------------- | -------------------------------- |
| 🧩 ER Diagram        | `Document/ER_diagram.png`        |
| 🗂️ Schema Diagram   | `Document/schema_diagram.png`    |
| 📐 Normalized Schema | `Document/normalized_schema.png` |
| 🗄️ SQL Database     | `Database/sparkenergies.sql`     |
| 📄 Project Report    | `Document/project_report.pdf`    |

---

# 🛠️ Technology Stack

<div align="center">

| Layer                  | Technology                   |
| ---------------------- | ---------------------------- |
| 💻 **Backend**         | PHP 8.2+                     |
| 🗄️ **Database**       | MySQL / MariaDB              |
| 🔗 **Storage Engine**  | InnoDB                       |
| 🎨 **Frontend**        | HTML5, CSS3, Bootstrap 5     |
| ⚙️ **Client Logic**    | Vanilla JavaScript           |
| 📊 **Visualization**   | Chart.js                     |
| 🧾 **PDF**             | FPDF / Native PDF Generation |
| 🌐 **Server**          | Apache                       |
| 🧪 **Environment**     | XAMPP                        |
| 🔀 **Version Control** | Git                          |
| 📝 **IDE**             | Visual Studio Code           |

</div>

---

# 📁 Repository Structure

The current repository structure is:

```text
CSE370/
│
├── 📁 Database/
│   └── 🗄️ sparkenergies.sql
│
├── 📁 Document/
│   ├── 🧩 ER_diagram.png
│   ├── 🗂️ schema_diagram.png
│   ├── 📐 normalized_schema.png
│   └── 📄 project_report.pdf
│
├── 📁 source/
│   ├── 🔐 index.php
│   ├── 🔗 db.php
│   ├── 👨‍💼 admin_dashboard.php
│   ├── 👨‍🔧 employee_dashboard.php
│   ├── 👤 customer_dashboard.php
│   ├── 🧾 download_bill.php
│   ├── 📝 signup.php
│   └── 🚪 logout.php
│
├── 📄 LAB 01.pdf
├── 📄 LAB 02.pdf
├── 📄 LAB 03.pdf
├── 📜 LICENSE
├── 📘 README.md
└── ⚙️ gitignore.txt
```

---

# 🚀 Installation & Setup

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/sajedulislam5840/CSE370.git
cd CSE370
```

---

## 2️⃣ Start XAMPP

Open XAMPP Control Panel and start:

```text
Apache
MySQL
```

Make sure both services are running.

---

## 3️⃣ Create the Database

Open **phpMyAdmin** or MySQL CLI.

Create:

```sql
CREATE DATABASE sparkenergies;
```

---

## 4️⃣ Import the Database

Import:

```text
Database/sparkenergies.sql
```

into the `sparkenergies` database.

The SQL file contains:

* Tables
* Relationships
* Constraints
* Triggers
* Seed data

---

## 5️⃣ Configure Database Connection

Open:

```text
source/db.php
```

Example configuration:

```php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sparkenergies";
```

Update the credentials if your local MySQL/MariaDB configuration differs.

---

## 6️⃣ Move Project to XAMPP

Copy the repository into:

```text
C:\xampp\htdocs\
```

The final path should be:

```text
C:\xampp\htdocs\CSE370\
```

---

## 7️⃣ Launch SparkEnergies 🚀

Open:

```text
http://localhost/CSE370/source/index.php
```

🎉 **SparkEnergies is ready to run!**

---

# 🔑 User Roles

| Role                       | Access                                                       |
| -------------------------- | ------------------------------------------------------------ |
| 👨‍💼 **Administrator**    | Customers • Employees • Meters • Wallets • Billing • Arrears |
| 👨‍🔧 **Field Technician** | Routes • Meter Readings • History • Field Operations         |
| 👤 **Customer**            | Bills • Payments • Wallet • Meter Subscription • Analytics   |

Each role operates within its own authorized environment.

---

# 📊 Feature Matrix

|          Feature          | Admin | Technician | Customer |
| :-----------------------: | :---: | :--------: | :------: |
|   👥 Customer Management  |   ✅   |      ❌     |     ❌    |
| 👨‍💼 Employee Management |   ✅   |      ❌     |     ❌    |
|     ⚡ Meter Management    |   ✅   |     👁️    |    👁️   |
|      📍 Field Routes      |   ❌   |      ✅     |     ❌    |
|     📋 Meter Readings     |  👁️  |      ✅     |    👁️   |
|         🧾 Billing        |   ✅   |     👁️    |     ✅    |
|         💳 Wallet         |   ✅   |      ❌     |     ✅    |
|        💰 Payments        |  👁️  |      ❌     |     ✅    |
|        📊 Analytics       |  👁️  |     👁️    |     ✅    |
|       🧾 PDF Invoice      |   ❌   |      ❌     |     ✅    |
|      🔔 Notifications     |   ✅   |      ❌     |     ✅    |

**Legend:**
✅ Full Access    👁️ View/Related Access    ❌ No Access

---

# 📈 Project Highlights

<div align="center">

### ⚡ Smart

Automated meter validation and dynamic billing.

### 🔐 Secure

Role-based access and database integrity controls.

### 📊 Transparent

Customer billing and six-month consumption analytics.

### 🗄️ Normalized

Designed using a structured **3NF relational database**.

### 👨‍🔧 Accountable

Employee-linked field operations and reading history.

### 💳 Convenient

Digital wallet and real-time bill settlement.

</div>

---

# 👥 Team

<div align="center">

| 👨‍💻 Member                   |      🆔 ID | 💼 Role                                         |
| ------------------------------ | ---------: | ----------------------------------------------- |
| **Mir Mohammad Sajedul Islam** | `23201376` | Full-Stack Development & Backend Architecture   |
| **Bishal Golder**              | `23201378` | Full-Stack Development & Backend Implementation |
| **Salman Naguib**              | `23201031` | Full-Stack Development & Backend Integration    |

<br>

**CSE370 — Database Systems**
**BRAC University**
**Spring 2026 • Group 06**

</div>

---

# 🎓 Academic Information

### 📚 Course

**CSE370 — Database Systems**

### 🏫 University

**BRAC University**

### 📅 Semester

**Spring 2026**

### 👥 Group

**06**

### 🧠 Concepts Demonstrated

* Relational Database Design
* Entity-Relationship Modeling
* Database Normalization
* Third Normal Form
* SQL Queries
* Primary & Foreign Keys
* Referential Integrity
* Database Constraints
* Database Triggers
* Transaction Management
* Role-Based Access Control
* Full-Stack Web Development
* Database-Driven Business Logic

---

# 📚 Documentation

All project documentation is available in the `Document/` directory.

### 📄 Project Report

[**Open Project Report →**](Document/project_report.pdf)

### 🗄️ Database Schema

[**Open SQL Schema →**](Database/sparkenergies.sql)

### 🧩 ER Diagram

[**View ER Diagram →**](Document/ER_diagram.png)

### 🗂️ Schema Diagram

[**View Schema Diagram →**](Document/schema_diagram.png)

### 📐 Normalized Schema

[**View 3NF Schema →**](Document/normalized_schema.png)

---

# ⭐ Project Status

<div align="center">

**🟢 Academic Project**

**⚡ SparkEnergies is designed as a complete database-driven electricity management platform.**

<br>

<img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&height=120&section=footer&text=SparkEnergies%20⚡&fontSize=32&fontAlignY=70&animation=twinkling" alt="SparkEnergies Footer">

</div>

---

# 📜 License

This project was developed for academic purposes as part of the **CSE370 — Database Systems** course at **BRAC University**.

See [`LICENSE`](LICENSE) for the applicable license terms.

---

<div align="center">

### ⚡ **SparkEnergies**

#### *Powering Smarter Utility Management*

**Built with PHP • MySQL • Bootstrap • JavaScript • ☕**

<br>

<img src="https://img.shields.io/badge/Made%20for-CSE370-red?style=for-the-badge">
<img src="https://img.shields.io/badge/BRAC%20University-Spring%202026-blue?style=for-the-badge">

</div>
