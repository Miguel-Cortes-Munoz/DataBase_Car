
-- EMPLOYEES (3)
INSERT INTO Employee (First_Name, Last_Name, Phone_Number, Email, Position)
VALUES 
('John',   'Smith',  '403-555-1111', 'john.smith@ljauto.com',   'Sales Associate'),
('Emily',  'Brown',  '403-555-2222', 'emily.brown@ljauto.com',  'Finance Manager'),
('Carlos', 'Lopez',  '403-555-3333', 'carlos.lopez@ljauto.com', 'General Manager');

-- CARS (10)
INSERT INTO Car (Make, Model, Year, Color, Miles, Condition_Desc, Book_Price)
VALUES
('Toyota',    'Camry',     2020, 'Black',  45000,  'Used - Excellent',  22000.00),  
('Ford',      'F-150',     2019, 'White',  78000,  'Used - Good',       28000.00), 
('Honda',     'Civic',     2022, 'Blue',   15000,  'Used - Like New',   24000.00),  
('Chevrolet', 'Silverado', 2018, 'Red',    92000,  'Used - Fair',       21000.00),  
('Hyundai',   'Elantra',   2021, 'Silver', 31000,  'Used - Excellent',  19500.00),  
('Jeep',      'Wrangler',  2020, 'Green',  55000,  'Used - Good',       32000.00),  
('Mazda',     'CX-5',      2022, 'White',  12000,  'Used - Like New',   29000.00),  
('Dodge',     'Charger',   2017, 'Black', 110000,  'Used - Fair',       16500.00),  
('Nissan',    'Altima',    2021, 'Grey',   28000,  'Used - Excellent',  21500.00),  
('Kia',       'Sorento',   2020, 'Blue',   42000,  'Used - Good',       25000.00);  

-- CUSTOMERS (5)
INSERT INTO Customer
(First_Name, Last_Name, Phone_Number, Email, Customer_Address,
 Province, City, Gender, DOB, Tax_ID, Num_Late_Payment, Avg_Day_Late)
VALUES
('Sarah',   'Johnson',  '403-555-4444', 'sarah.j@email.com',       '123 5th Ave S',     'Alberta', 'Lethbridge', 'Female',            '1995-06-15', 'TX123456', 1,  3.5), 
('Michael', 'Chen',     '403-555-5555', 'michael.chen@email.com',  '456 7th St N',      'Alberta', 'Lethbridge', 'Male',              '1988-02-10', 'TX654321', 0,  0.0),  
('Aisha',   'Patel',    '403-555-6666', 'aisha.patel@email.com',   '789 Mayor Magrath', 'Alberta', 'Lethbridge', 'Female',            '1992-11-23', 'TX112233', 2,  7.2), 
('Derek',   'Williams', '403-555-7777', 'derek.w@email.com',       '22 Whoop-Up Dr',    'Alberta', 'Lethbridge', 'Male',              '1979-04-05', 'TX445566', 0,  0.0),  
('Priya',   'Nguyen',   '403-555-8888', 'priya.n@email.com',       '310 University Dr', 'Alberta', 'Lethbridge', 'Prefer not to say', '2000-08-30', 'TX778899', 3, 12.1); 

-- CUSTOMER EMPLOYMENT HISTORY
INSERT INTO Customer_Employment (Customer_ID, Employer, Title, Supervisor_Phone, Address, Start_Date)
VALUES
(1, 'Lethbridge School Division', 'Teacher',          '403-555-0101', '433 15 St S, Lethbridge',    '2019-08-01'),
(1, 'Tim Hortons',                'Shift Supervisor', '403-555-0102', '1 Mayor Magrath Dr, Lethbridge', '2016-05-01'),

(2, 'Lethbridge College',         'IT Technician',    '403-555-0201', '3000 College Dr S, Lethbridge',  '2015-03-15'),

(3, 'Chinook Health Region',      'Nurse',            '403-555-0301', '960 19 St S, Lethbridge',    '2018-06-01'),
(3, 'Superstore',                 'Cashier',          '403-555-0302', '550 University Dr, Lethbridge',  '2013-09-01'),

(4, 'City of Lethbridge',         'Engineer',         '403-555-0401', '910 4 Ave S, Lethbridge',    '2010-01-10'),

(5, 'University of Lethbridge',   'Research Assistant','403-555-0501','4401 University Dr, Lethbridge', '2022-01-01'),
(5, 'Walmart',                    'Sales Associate',  '403-555-0502', '120 Stafford Dr N, Lethbridge',  '2019-06-15');

-- PURCHASES (10 cars, price_paid included)
INSERT INTO Purchase (Employee_ID, Car_ID, Purchase_Date, Buy_Location, Auction, price_paid)
VALUES
(1, 1,  '2023-01-10', 'Calgary Auction',        TRUE,  16000.00),  -- John bought Camry
(1, 2,  '2023-02-15', 'Edmonton Dealer Trade',  FALSE, 21000.00),  -- John bought F-150
(2, 3,  '2023-03-01', 'Calgary Auction',        TRUE,  17500.00),  -- Emily bought Civic
(3, 4,  '2023-03-20', 'Private Seller',         FALSE, 14000.00),  -- Carlos bought Silverado
(1, 5,  '2023-04-05', 'Calgary Auction',        TRUE,  13500.00),  -- John bought Elantra
(2, 6,  '2023-05-12', 'Red Deer Dealer Trade',  FALSE, 24000.00),  -- Emily bought Wrangler
(3, 7,  '2023-06-01', 'Calgary Auction',        TRUE,  20000.00),  -- Carlos bought CX-5
(1, 8,  '2023-06-18', 'Private Seller',         FALSE, 10000.00),  -- John bought Charger
(2, 9,  '2023-07-05', 'Calgary Auction',        TRUE,  15000.00),  -- Emily bought Altima
(3, 10, '2023-07-20', 'Edmonton Dealer Trade',  FALSE, 18000.00);  -- Carlos bought Sorento

-- CAR DAMAGE (damage records for purchased cars)
INSERT INTO car_damage (Car_ID, Damage_Desc, Estimated_Repair_Cost, Actual_Repair_Cost)
VALUES
(1, 'Scratch on rear bumper',             150.00,  130.00),   -- Camry     — closed
(1, 'Cracked windshield',                 400.00,  420.00),   -- Camry     — closed
(2, 'Dent on driver door',                250.00,  275.00),   -- F-150     — closed
(3, 'Interior stain on back seat',         80.00,   65.00),   -- Civic     — closed
(4, 'Broken tail light',                  120.00,  110.00),   -- Silverado — closed
(4, 'Rust on undercarriage',              500.00,   NULL),    -- Silverado — PENDING
(5, 'Paint chip on hood',                  75.00,   70.00),   -- Elantra   — closed
(6, 'Cracked side mirror',               180.00,  190.00),   -- Wrangler  — closed
(7, 'Dent on rear quarter panel',         300.00,   NULL),    -- CX-5      — PENDING
(8, 'Torn driver seat upholstery',        200.00,  185.00),   -- Charger   — closed
(8, 'Broken AC unit',                     650.00,  700.00),   -- Charger   — closed
(9, 'Scratched front bumper',             100.00,   NULL),    -- Altima    — PENDING
(10,'Faded paint on roof',                350.00,  330.00);   -- Sorento   — closed

-- SALES (6 sales — cars 4, 7, 9, 10 remain unsold)
INSERT INTO Sale
(Customer_ID, Employee_ID, Car_ID, Sale_Date, Sale_Price, Financed_Amount, Downpayment, Commission)
VALUES
(1, 1, 1, '2023-04-01', 22000.00, 18000.00,  4000.00,  800.00),  -- Sarah  / Camry    / John
(2, 2, 2, '2023-05-10', 28000.00, 25000.00,  3000.00, 1200.00),  -- Michael/ F-150    / Emily
(3, 1, 3, '2023-06-15', 24000.00, 20000.00,  4000.00,  950.00),  -- Aisha  / Civic    / John
(4, 3, 5, '2023-07-22', 19500.00,     0.00, 19500.00,  700.00),  -- Derek  / Elantra  / Carlos (cash)
(5, 2, 6, '2023-08-05', 32000.00, 28000.00,  4000.00, 1400.00),  -- Priya  / Wrangler / Emily
(1, 1, 8, '2023-09-10', 16500.00, 12000.00,  4500.00,  600.00);  -- Sarah  / Charger  / John (repeat)

-- WARRANTIES 
INSERT INTO warranties
(Sale_ID, Employee_ID, Warranty_Desc, Warranty_Cost, Monthly_Cost, Start_Date, Length_Months, deductible, itemized_coverage)
VALUES
(1, 1, 'Basic Powertrain', 699.00, 32.00, '2023-04-01', 24, 100.00, 'Engine, Transmission, Drivetrain'),
(2, 2, 'Comprehensive Coverage', 1299.00, 48.00, '2023-05-10', 36, 50.00, 'Engine, Transmission, Electrical, AC, Brakes'),
(3, 1, 'Basic Powertrain', 699.00, 32.00, '2023-06-15', 24, 100.00, 'Engine, Transmission, Drivetrain'),
(5, 2, 'Comprehensive Coverage', 1299.00, 48.00, '2023-08-05', 36, 50.00, 'Engine, Transmission, Electrical, AC, Brakes'),
(4, 3, 'Exterior Only', 399.00, 18.00, '2023-07-22', 24, 150.00, 'Paint, Body Panels, Glass'),
(6, 1, 'Basic Powertrain', 699.00, 32.00, '2023-09-10', 24, 100.00, 'Engine, Transmission, Drivetrain');



-- PAYMENTS (with Due_Date and Bank_Account)
INSERT INTO payments (Sale_ID, Payment_Date, Due_Date, Payment_Amount, Bank_Account)
VALUES

-- Sale 1: Sarah / Camry
(1, '2023-05-01', '2023-05-01',  450.00, 'TD-****4321'),
(1, '2023-06-01', '2023-06-01',  450.00, 'TD-****4321'),
(1, '2023-07-12', '2023-07-01',  450.00, 'TD-****4321'),  

-- Sale 2: Michael / F-150 — on time every month
(2, '2023-06-10', '2023-06-10',  600.00, 'RBC-****8765'),
(2, '2023-07-10', '2023-07-10',  600.00, 'RBC-****8765'),
(2, '2023-08-10', '2023-08-10',  600.00, 'RBC-****8765'),
(2, '2023-09-10', '2023-09-10',  600.00, 'RBC-****8765'),

-- Sale 3: Aisha / Civic
(3, '2023-07-15', '2023-07-15',  500.00, 'BMO-****1122'),
(3, '2023-08-20', '2023-08-15',  500.00, 'BMO-****1122'), 
(3, '2023-09-22', '2023-09-15',  500.00, 'BMO-****1122'), 

-- Sale 5: Priya / Wrangler — large balance, high risk customer
(5, '2023-09-05', '2023-09-05',  750.00, 'Scotiabank-****9999'),
(5, '2023-10-05', '2023-10-05',  750.00, 'Scotiabank-****9999'),
(5, '2023-11-20', '2023-11-05',  750.00, 'Scotiabank-****9999'),  
(5, '2023-12-18', '2023-12-05',  750.00, 'Scotiabank-****9999'), 

-- Sale 6: Sarah / Charger (repeat buyer)
(6, '2023-10-10', '2023-10-10',  380.00, 'TD-****4321'),
(6, '2023-11-10', '2023-11-10',  380.00, 'TD-****4321'),
(6, '2023-12-14', '2023-12-10',  380.00, 'TD-****4321');          