# 🍕 Pizzeria Backend – PHP MVC Web Application

Backend-focused pizzeria web application built with PHP 8 using a clean MVC architecture.

## 🚀 Overview

This project demonstrates a complete e-commerce backend system with:
- Product catalog
- Shopping cart (session-based)
- Guest & authenticated checkout
- Order processing
- Address management
- Secure authentication

👉 Includes SQL database export (`pizzeria.sql`) to run locally.

---

## 🛠️ Technologies

- PHP 8
- MySQL / MariaDB
- Twig (via Composer)
- HTML / CSS
- PDO (prepared statements)
- Sessions & Cookies

---

## ⚙️ Run Locally

1. Import the database:
   - Use `pizzeria.sql` in your MySQL environment

2. Place project in:

htdocs/Pizzeria-Back-End


3. Start XAMPP:
- Apache ✅
- MySQL ✅

4. Open in browser:

http://localhost/Pizzeria-Back-End/public/


---

## 🧱 Architecture

The application follows MVC:

- **Controllers** → handle requests
- **Services** → business logic
- **DAO** → database access
- **Entities** → data objects
- **Views (Twig)** → presentation

---

## ✨ Features

### 🛍️ Product Catalog
- View pizzas with pricing and details
- Promotional pricing support

### 🛒 Shopping Cart
- Add/remove items
- Adjust quantities
- Custom extras per pizza
- Session-based persistence

### 👤 Checkout System
- Guest checkout supported
- Account creation & login
- Password hashing (`password_hash`)
- Email remembered via cookies

### 📦 Order System
- Orders stored in database
- Order items stored separately
- Includes:
  - quantities
  - prices
  - extras
  - courier notes

### 🏠 Address Management
- Logged-in users → stored in database
- Guests → stored in session

### 📬 Order Confirmation
- Order summary
- Items + extras
- Total price
- Delivery instructions

---

## 🔒 Security

- Prepared statements (PDO)
- Password hashing
- Session handling
- Input validation

---

## 🗄️ Database

Tables:
- products
- customers
- orders
- order_items
- delivery_areas

---

## 💼 Portfolio Value

This project demonstrates:

- MVC architecture
- Backend development
- Database integration
- Authentication systems
- Session management
- E-commerce logic

---

## 👤 Author

**Xander Van Malder**

Developed as part of my Full-Stack PHP Developer training (VDAB).