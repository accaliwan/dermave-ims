# DermAve IMS

Web-based Inventory Management System with Barcode Scanner for **DermAve Medical Aesthetic & Wellness Clinic**.

A group project for a dermatology study.

## Tech stack

- HTML
- CSS
- JavaScript
- PHP

## Theme

Based on the DermAve logo: black, gold (`#ffe066`) and soft white.

## Project structure

```
dermave-ims/
├── index.php            # redirects to login
├── login.php            # login page
├── logout.php           # ends the session
├── dashboard.php        # dashboard page
├── products.php         # products page (item, batch, expiry, qty, status)
├── includes/
│   ├── config.php       # app + database settings (placeholder)
│   ├── auth.php         # protects pages that need login
│   ├── header.php       # shared top bar + navigation
│   ├── footer.php       # shared scripts + closing tags
│   └── data.php         # placeholder product data + expiry status logic
└── assets/
    ├── css/style.css
    ├── js/main.js       # shared (nav)
    ├── js/login.js
    ├── js/dashboard.js
    ├── js/products.js
    └── img/             # put the DermAve logo here
```

## Running locally

1. Install [XAMPP](https://www.apachefriends.org/) (or any PHP + Apache stack).
2. Put this folder inside `htdocs` (XAMPP) so it lives at `htdocs/dermave-ims`.
3. Start Apache, then open `http://localhost/dermave-ims/`.
4. Demo login (placeholder only): `admin` / `admin123`

## Pages

- [x] Login
- [x] Dashboard
- [x] Products
- [ ] Machines
- [ ] Supplies
- [ ] Barcode

## Team workflow

- Pull before you start working.
- Commit small, with clear messages.
- Work on a branch for each page/feature and open a pull request into `main`.
