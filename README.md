# Eshop

An e-commerce website built with html, css, php, java script and sqlite.

## Technologies

- HTML
- CSS
- PHP
- SQLITE
- Java Script
- jquery

## Website Description

- Login Page (login.php)
	- Admin account direct to admin panel
	- Normal user account direct to home page

- Home Page (HomePage.php)
	- Direct to Main Page by clicking the buttons of different categaries.
	
- Main Page (MainPage.php)
	- Products list are displayed using the CSS *table-less* design
	- AJAX infinite scroll is applied. The list will load 6 products each scroll.
	- **Add to Cart** button for each product
	- Direct to Product Page by clicking the thumbnail or the name
	
- Product Page (ProductPage.php)
	- Product picture, details and an **Add to Cart** button are displayed
	- Navigation bar at the top left corner to navigate to the upper level of the hierarchy, ie. *Home > Main Page > Product Page*
	- Shopping list at the top right corner using the CSS *hover* design. There are input boxes for inputting the quantity and a **Check Out** button for submitting the list to paypal but currently no function.

- Profile Page (profile.php)
	- User can change password in this page
	- Need to login again after changing password 
	- Not available when logged in with Guest

- Top Navigation bar
	- Navigate to different pages, ie. Home, All Categaries, Profile, Logout button

- Navigation bar 
	- Navigation bar at the top left corner to navigate to the upper level of the hierarchy, eg. *Home > Main Page*
	- Available in Main Page and Product Pages

- Shopping Cart
	- Shopping list at the top right corner using the CSS *hover* design. 
	- There are input boxes for each product to input the quantity. Change of input will update the cart immediately. 
	- Remove button for each product.
	- Total price is calculated and shown.
	- Click **Clear** button to empty the cart.
	- **Check Out** button for submitting the list to paypal but currently no function.
	- Available in Main Page and Product Page

- Admin Panel (admin.php)
	- To manage database 
	- Operations: ADD, EDIT, DELETE

## Setup

Visit the link for demo: https://secure.s48.ierg4210.ie.cuhk.edu.hk

## Status

Project is: *developing*
