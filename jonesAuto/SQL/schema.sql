
DROP DATABASE IF EXISTS lethbridge_jones_auto_db;
CREATE DATABASE lethbridge_jones_auto_db;
USE lethbridge_jones_auto_db;

CREATE TABLE Car(
    Car_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Make  VARCHAR(50) NOT NULL,
    Model VARCHAR(50) NOT NULL,
    Year YEAR NOT NULL,
    Color VARCHAR(30),
    Miles INT NOT NULL,
    Condition_Desc VARCHAR(100),
    Book_Price DECIMAL(10,2) NOT NULL,

PRIMARY key (Car_id)
) ENGINE=InnoDB;

CREATE TABLE Employee(
    Employee_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    First_Name VARCHAR(50) NOT NULL,
    Last_Name VARCHAR(50) NOT NULL,
    Phone_Number VARCHAR(20) NOT NULL,
    Email VARCHAR(200) NOT NULL,
    position VARCHAR(100) NOT NULL,

    PRIMARY KEY(Employee_ID)
)  ENGINE=InnoDB;

CREATE TABLE Customer(
    Customer_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    First_Name VARCHAR(50) NOT NULL,
    Last_Name VARCHAR(50) NOT NULL,
    Phone_Number VARCHAR(20) NOT NULL,
    Email VARCHAR(200) NOT NULL,
    Customer_address VARCHAR(50) NOT NULL,
    Province VARCHAR(50) NOT NULL,
    City VARCHAR(50) NOT NULL,
    Gender VARCHAR(50) NOT NULL,
    DOB date NOT NULL,
    Tax_ID VARCHAR(50)  NOT NULL,
    num_Late_Payment INT UNSIGNED NOT NULL,
    Avg_Day_Late float UNSIGNED NOT NULL,

    PRIMARY KEY(Customer_ID)

) ENGINE=InnoDB;


CREATE TABLE Purchase(
    Purchase_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Employee_ID INT UNSIGNED NOT NULL, 
    Car_ID INT UNSIGNED NOT NULL,
    Purchase_Date DATE NOT NULL, 
    Buy_Location VARCHAR(50) NOT NULL,
    Auction BOOLEAN NOT NULL,
    PRIMARY KEY(Purchase_ID),
    Foreign Key (Employee_ID)
    REFERENCES Employee(Employee_ID),
    Foreign Key (Car_ID) REFERENCES Car(Car_ID)
) ENGINE=InnoDB;



Create TABLE sale(
    Sale_ID  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Customer_ID INT UNSIGNED NOT NULL,
    Employee_ID INT UNSIGNED NOT NULL,
    Car_ID INT UNSIGNED NOT NULL,
    Sale_Date date NOT NULL,
    Financed_Amount DECIMAL(10,2),
    downpayment DECIMAL(10,2),
    commission DECIMAL(10,2), 
    PRIMARY KEY(Sale_ID),
    Foreign Key (Customer_ID) REFERENCES Customer(Customer_ID),
    Foreign Key (Employee_ID) REFERENCES Employee(Employee_ID),
    Foreign Key (Car_ID) REFERENCES Car(Car_ID)

) ENGINE=InnoDB;
