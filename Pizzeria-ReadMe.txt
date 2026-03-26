Pizzeria Web Application – PHP MVC Backend Project

This project is a backend-focused pizzeria web application built with PHP 8, MVC architecture, and Twig.
Users can browse pizzas, manage a session-based shopping cart, and place orders with optional extras.
The system supports guest checkout and authenticated users with login, logout, and session handling.
Orders, order items, and customer data are stored in a MySQL database using DAO and Service layers.
Users can edit their address, which is persisted in the database or session depending on account status.
Cookies are used to remember the user's email for improved login experience.
The project demonstrates strong backend skills including MVC design, database integration, and session management.
Clean separation of concerns is implemented using Controllers, Services, and Data Access Objects.
Developed as part of my Full-Stack PHP Developer training (VDAB).
Technologies Used

-   PHP 8
-   MySQL / MariaDB
-   Twig templating engine ( composer )
-   HTML / CSS
-   MVC Architecture
-   Sessions and Cookies
-   PDO for database access

Architecture

The application is built using MVC:

-   Controller layer: Handles HTTP requests and routing
-   Service layer: Contains business logic
-   DAO layer: Handles database access
-   Entity layer: Represents database objects
-   Presentation layer: Twig templates

Main Features

1.  Product Catalog

-   Displays available pizzas with name, description, image, and price
-   Supports promotional pricing

2.  Shopping Cart

-   Add pizzas to cart
-   Increase and decrease quantities
-   Remove items
-   Clear cart
-   Supports custom extras per pizza (example: extra cheese, no onion)
-   Extras are stored separately per cart item

3.  Guest Checkout

-   Customers can order without creating an account
-   Required information:
    -   First name
    -   Last name
    -   Address
    -   Postal code
    -   City
    -   Phone number

4.  Account System

-   Customers can create an account during checkout
-   Passwords are securely hashed using password_hash()
-   Login system using password_verify()
-   Email is remembered using cookies
-   Session-based authentication

5.  Address Management

-   Logged-in users can edit their address
-   Guest users can edit temporary address stored in session

6.  Order System

-   Creates orders in database
-   Stores:
    -   Customer ID
    -   Order date and time
    -   Total price
    -   Courier information
-   Stores order items separately
-   Includes quantity, price, and extras

7.  Courier Notes

-   Customers can enter delivery instructions
-   Stored in database and displayed in order confirmation

8.  Session Management

-   Sessions automatically expire after inactivity
-   Secure login persistence
-   Logout functionality

9.  Order Confirmation

-   Displays:
    -   Order number
    -   Order date
    -   Items ordered
    -   Extras
    -   Total price
    -   Courier instructions

10. User Experience Features

-   Persistent shopping cart
-   Visible cart on product page
-   Clear navigation
-   Modern button-based UI

Security Features

-   Prepared statements (PDO)
-   Password hashing
-   Session protection
-   Input validation

Database Structure

Tables: - products - customers - orders - order_items - delivery_areas

Each order stores items separately for scalability and accuracy.

Professional Practices Used

-   MVC architecture
-   Separation of concerns
-   Object-oriented programming
-   Service layer abstraction
-   DAO pattern
-   Twig templating
-   Secure authentication
-   Clean code principles

Portfolio Value

This project demonstrates real-world skills required for professional
PHP development:

-   Backend development
-   Database design
-   Authentication systems
-   MVC architecture
-   Session and state management
-   E-commerce logic

Author

Xander Van Malder

This project was developed as part of my professional training and is
included in my portfolio to demonstrate my capabilities as a full-stack
PHP developer.
