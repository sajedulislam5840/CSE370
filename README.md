# ⚡ SparkEnergies
### An Advanced Electricity Bill Management and Distribution Platform

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-InnoDB-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![Course](https://img.shields.io/badge/Course-CSE370:_Database_Systems-red?style=flat-square)

A robust, full-stack database platform engineered to eliminate ghost billing, automate utility accounting, and enforce transparency across power distribution channels. Built with a normalized **Third Normal Form (3NF)** relational database engine.

> 📄 **[Read Academic Project Report](documentation/project_report.pdf)** · 🗄️ **[Database Schema SQL](database/sparkenergies.sql)**

---

## 👥 Engineering Team & Contributions
- **Mir Mohammad Sajedul Islam** (ID: 23201376) — Full-Stack Development (Frontend & Backend Architecture)
- **Bishal Golder** (ID: 23201378) — Full-Stack Development (Frontend & Backend Implementation)
- **Salman Naguib** (ID: 23201031) — Full-Stack Development (Frontend & Backend Integration)

*Course: CSE370 (Database Systems) | BRAC University | Spring 2026 | Group 06*

---

## 📌 Problem & System Overview

Traditional utility workflows rely heavily on paper records and isolated field readings, leading to delayed payments, zero consumption transparency, and arbitrary billing disputes. **SparkEnergies** resolves this by enforcing centralized relational constraints, real-time balance settlements, and digital field validations.

### Core System Objectives:
- **Zero Ghost-Billing:** Enforcing strict mathematical safety locks preventing duplicate or inverted meter inputs.
- **Dynamic Ledger Management:** Replacing flat billing with normalized multi-slab utility tariffs and instant wallet reconciliations.
- **Accountable Field Operations:** Equipping mobile technicians with location-aware dispatch queues and historical timestamp auditing.

---

## 🚀 System Architecture & Multi-Tier RBAC

SparkEnergies is engineered around strict **Role-Based Access Control (RBAC)** across three isolated functional environments:

```text
                      ┌───────────────────────────────┐
                      │    SparkEnergies Core Engine  │
                      └──────────────┬────────────────┘
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
┌──────────────────┐       ┌──────────────────┐       ┌──────────────────┐
│   Administrator  │       │  Field Employee  │       │  Customer Portal │
│  (Ledger & Node) │       │ (Route & Inputs) │       │ (Wallet & Invoices)│
└──────────────────┘       └──────────────────┘       └──────────────────┘
1. Administrator Dashboard (System Operations & Node Control)Wallet & Financial Reconciliation: Search registered consumer profiles, inspect historical transactions, and post digital wallet balance top-ups.Dynamic Staff Provisioning: Onboard field personnel and bind authentication profiles to automated employee sequence generators.Master Customer Directory: Filter across active utility subscribers, account standings, normalized tariff brackets, and physical locations.Smart Meter Inventory Management: Register incoming hardware meters to the grid; database states dynamically toggle (Available vs Assigned) upon user subscription.Revenue Arrears Queue: Execute relational multi-table inner joins across overdue ledgers to target non-paying accounts for service suspension.2. Field Technician Console (Dispatch & Audited Intake)Assigned Route & Meter Dispatch: Review real-time task allocations showing geographical locations and active meter serials scheduled for reading.Chronological Reading Log: Evaluate historical timestamps per meter to isolate neglected routes and maintain strict billing regularity.Audit Trails: Maintain an immutable log of processed entries linked directly to the active employee identifier.3. Customer Self-Service Portal (Consumer Experience)Real-Time Payment Settlement: Access comprehensive billing records with dynamic status badges (Paid / Unpaid) and settle invoices immediately via digital wallets.Dynamic Meter Provisioning: Subscribe to category-specific meter blueprints (e.g., Residential, Commercial) with automated balance deductions and backend inventory linking.6-Month Visual Consumption Analytics: Interactive time-series visual charts tracking month-over-month power consumption trends.Automated PDF Invoice Generator: Instant print-ready document streaming containing itemized billing breakdowns, local tax deductions, and demand charges.🛡️ Core Business Logic & Database Integrity RulesDynamic Multi-Slab Tariff Evaluation: Pricing rates (Unit_cost, VAT, Demand_Charge, Meter_rent) are queried dynamically at execution time based on customer categories rather than stored statically.Strict Numerical Anti-Tamper Check: Input fields automatically intercept and reject any meter updates where Current_reading < Prev_reading.28-Day Billing Safety Lock: Compares incoming timestamp against the target meter's MAX(Date) in Unix seconds. If the difference is below 28 days, a database safety exception triggers to prevent fraudulent double-billing.Asynchronous Notification Routing: Trigger listeners intercept state changes (e.g., balance top-up, bill generation) and dispatch structured alerts directly into the customer portal.🛠️ Technology StackLayerTechnologiesBackend ArchitecturePHP 8.2+ (Secure mysqli Prepared Statements)Database EngineMySQL / MariaDB (InnoDB, Foreign Key Constraints, 3NF Normalized)Client InterfaceBootstrap 5, Vanilla JavaScript, Custom CSS3Data Visualization & DocsChart.js, FPDF / Native PDF GenerationTooling & EnvironmentApache (XAMPP), Git, VS Code📁 Repository StructurePlaintextSparkEnergies/
├── database/
│   └── sparkenergies.sql          # Full relational schema, triggers, and seed data
├── documentation/
│   ├── ER_diagram.png             # Complete Entity-Relationship architectural map
│   ├── schema_diagram.png         # Relational schema mapping
│   ├── normalized_schema.png      # 3NF structural layout
│   └── project_report.pdf         # Comprehensive academic system documentation
├── src/
│   ├── index.php                  # Authentication gateway and landing page
│   ├── db.php                     # Centralized DB connector with prepared statement wrapper
│   ├── admin_dashboard.php        # Operations management and balance ledger
│   ├── employee_dashboard.php     # Route tracking and verified meter input console
│   ├── customer_dashboard.php     # Self-service billing, wallet, and visual analytics
│   ├── download_bill.php          # PDF invoice compilation script
│   ├── signup.php                 # Customer registration handler
│   └── logout.php                 # Secure session termination pipeline
├── README.md
└── LICENSE
🚀 Local Installation & SetupClone the repository:Bashgit clone https://github.com/sajedulislam5840/CSE370.git
cd CSE370
Database Import:Launch your MySQL server (via XAMPP / MariaDB).Create an empty database: sparkenergies.Import the schema file located at database/sparkenergies.sql.Configure Environment:Open src/db.php and verify database credentials:PHP$host = "localhost";
$user = "root";
$password = "";
$dbname = "sparkenergies";
Run Application:Place the project directory inside your local server directory (htdocs for XAMPP).Navigate to http://localhost/CSE370/src/index.php in your browser.
