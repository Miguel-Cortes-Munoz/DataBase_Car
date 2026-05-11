# Lethbridge Jones Auto — Dealership Management System

A full-stack dealership management system built with **PHP**, **HTML/CSS**, and **MySQL (WAMP)**. Developed as a capstone project for Database Management Systems, this app is designed to be shown to future employers as a demonstration of relational database design, SQL query writing, and dynamic web development.

---

## Overview

Jones Auto is an internal staff tool for managing all aspects of a car dealership — from purchasing vehicles at auction to recording customer sales, warranties, and payment histories. The system is intentionally staff-facing; no customer-facing access is provided, which informed many of the security and form design decisions.

---

## Features

- **Modular form system** — separate forms for each entity so returning customers don't need to re-register
- **Relational data model** — 9 tables with 9 explicit relationships, reducing redundancy across the system
- **SQL injection prevention** — all queries use prepared statements
- **Auto-routing between forms** — e.g. submitting a car purchase automatically redirects to the car damage form with the car ID pre-filled in the URL
- **15 reporting queries** — covering simple lookups, multi-table joins, subqueries, aggregations, and CASE statements
- **Reusable function library** — a shared `functions.php` file handles common HTML form generation and database I/O

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP |
| Frontend | HTML, CSS |
| Database | MySQL via WAMP (localhost) |
| ER Design | draw.io |

---

## Database Design

The schema contains 9 tables connected by 9 relationships:

| Relationship | Type | Key |
|---|---|---|
| Customer → Sale | One-to-many | `Customer_ID` |
| Customer → Customer_Employment | One-to-many | `Customer_ID` |
| Sale → Payments | One-to-many | `Customer_ID` |
| Sale → Car | One-to-one | `Car_ID` |
| Sale → Warranties | One-to-many | `Sale_ID` |
| Employee → Warranties | One-to-many | `Sale_ID` |
| Employee → Purchase | One-to-many | `Employee_ID` |
| Car → Purchase | One-to-one | `Car_ID` |
| Car → Car_Damage | One-to-many | `Car_ID` |

The ER diagram and SQL schema script are included in the `www/` folder.

---

## Getting Started

### Prerequisites
- [WAMP Server](https://www.wampserver.com/) (or XAMPP/LAMP equivalent)
- PHP 7.4+
- MySQL 5.7+

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/YOUR_USERNAME/jones-auto.git
   ```

2. Copy the project folder into your WAMP `www/` directory.

3. Start WAMP and open **phpMyAdmin** (or your preferred MySQL client).

4. Import the schema:
   ```
   www/schema.sql
   ```

5. (Optional) Load sample data:
   ```
   www/sample_data.sql
   ```

6. Open your browser and navigate to:
   ```
   http://localhost/jones-auto/
   ```

---

## Application Pages

| Page | Purpose |
|---|---|
| `index.php` | Home page / navigation hub |
| `add_employee.php` | Add staff members |
| `add_car_purchase.php` | Log car acquisitions (auction or direct buy) |
| `add_car_damage.php` | Record damage and repair costs per vehicle |
| `add_customer.php` | Register customers + employment history |
| `add_sale.php` | Record a sale, links customer → car |
| `add_warranty.php` | Attach warranties to a completed sale |
| `add_payment.php` | Log customer payments against a sale |
| `view_data.php` | Run and display all 15 reporting queries |

---

## Reporting Queries

The `view_data.php` page includes 15 queries designed to cover the full range of SQL techniques:

1. Cars under $20,000
2. Employee directory
3. Cars by sold status, year, and condition (subquery)
4. Revenue per employee (multi-table join + GROUP BY)
5. Full sale details (multi-table join)
6. Late payment risk (join + aggregation)
7. Damage estimate accuracy by employee (4-table join + calculated difference)
8. All damage records with status (CASE statement)
9. Warranties by sale (4-table join)
10. Payment summary per sale (LEFT JOIN + aggregation)
11–15. Additional operational lookups across remaining tables

---

## Design Decisions

**Staff-only access** — the system exposes internal data like tax IDs, commission, and financing details. No public-facing layer was built by design.

**Modular forms** — a wealthy or repeat customer shouldn't need to re-enter their info. Customer IDs are passed via URL to connect records across forms.

**Shared function library** — once patterns became repetitive across forms, common logic was extracted into `functions.php` to keep individual form files clean.

**Form auto-routing** — submitting a car purchase redirects to car damage with the new `Car_ID` in the URL, and submitting a sale redirects to warranties with `Sale_ID` and `Employee_ID`. This creates a guided workflow without a full session system.

---

## Known Limitations / Future Improvements

- [ ] User authentication — role-based access for employees vs. managers
- [ ] Search and filter — find records without scanning full tables
- [ ] Edit and delete forms — currently insert-only
- [ ] Searchable dropdowns — long car/customer lists need typeahead
- [ ] Automated overdue payment tracking — currently entered manually
- [ ] Enhanced UI polish and responsive layout
- [ ] Warranty template table — avoid re-entering the same warranty terms repeatedly

---

## Project Context

**Course:** Database Management Systems  
**Author:** Miguel Cortes  
**Date:** March 31, 2026  
**Institution:** University of Lethbridge

This project was built from scratch with no prior PHP or HTML experience. The goal was not just to meet course requirements, but to produce something worth including in a professional portfolio.
