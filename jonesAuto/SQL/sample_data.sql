

INSERT INTO Employee (First_Name, Last_Name, Phone_Number, Email, Position)
VALUES 
('John', 'Smith', '403-555-1111', 'john.smith@ljauto.com', 'Sales Associate'),
('Emily', 'Brown', '403-555-2222', 'emily.brown@ljauto.com', 'Finance Manager'),
('Carlos', 'Lopez', '403-555-3333', 'carlos.lopez@ljauto.com', 'General Manager');


INSERT INTO Car (Make, Model, Year, Color, Miles, Condition_Desc, Book_Price)
VALUES
('Toyota', 'Camry', 2020, 'Black', 45000, 'Used - Excellent', 22000.00),
('Ford', 'F-150', 2019, 'White', 78000, 'Used - Good', 28000.00),
('Honda', 'Civic', 2022, 'Blue', 15000, 'Used - Like New', 24000.00);


INSERT INTO Customer
(First_Name, Last_Name, Phone_Number, Email, Customer_Address,
 Province, City, Gender, DOB, Tax_ID, Num_Late_Payment, Avg_Day_Late)
VALUES
('Sarah', 'Johnson', '403-555-4444', 'sarah.j@email.com',
 '123 5th Ave S', 'Alberta', 'Lethbridge',
 'Female', '1995-06-15', 'TX123456', 1, 3.5),

('Michael', 'Chen', '403-555-5555', 'michael.chen@email.com',
 '456 7th St N', 'Alberta', 'Lethbridge',
 'Male', '1988-02-10', 'TX654321', 0, 0.0);


 INSERT INTO Purchase
(Employee_ID, Car_ID, Purchase_Date, Buy_Location, Auction)
VALUES
(1, 1, '2023-01-10', 'Calgary Auction', TRUE),
(1, 2, '2023-02-15', 'Edmonton Dealer Trade', FALSE),
(2, 3, '2023-03-01', 'Calgary Auction', TRUE);

INSERT INTO Sale
(Customer_ID, Employee_ID, Car_ID, Sale_Date,
 Financed_Amount, Downpayment, Commission)
VALUES
(1, 1, 1, '2023-04-01', 18000.00, 4000.00, 800.00),
(2, 2, 2, '2023-05-10', 25000.00, 3000.00, 1200.00);


