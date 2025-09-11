##Dependencies:
Xampp

## Instructions:

    download and insatll the xampp app on to your machine.
    open the control panel and start the apache and mysql servers.
    enter <localhost/phpmyadmin> in your browser, this will open a page for you.
    on that page open the console tap at the bottom.

    #Creation of database:
        type the following sql to create the database:
            CREATE DATABASE myproducts;
        then select the myproducts database from the databases menu at the left.
        contunue with the following:
            CREATE TABLE products(
                product_id INT AUTO_INCREMENT PRIMARY KEY,
                product_type VARCHAR(20),
                product_name VARCHAR(20) NOT NULL,
                price DECIMAL(10, 2) DEFAULT 0.00,
                product_image VARCHAR(255),
                product_quantity VARCHAR(255),
                pdescription VARCHAR(255),
                brand VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                expire_at DATE
            );
        Then you can see your table by typing:
            SELECT * FROM products;

!!!YOU MIGHT GET ERROR WHEN YOU CHANGE ANY OF THE CONFIGURATIONS!
Post a pull request before u add any changes(Text me)
