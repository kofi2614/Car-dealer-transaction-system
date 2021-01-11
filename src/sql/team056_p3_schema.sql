CREATE TABLE `Customer` (
  `customer_id` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `street` varchar(125) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(30) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `phone_number` varchar(45) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `phone_number` (`phone_number`)
);

CREATE TABLE `Individual_Person_Customer` (
  `drivers_license_number` varchar(20) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `customer_id` int(30) unsigned NOT NULL,
  PRIMARY KEY (`drivers_license_number`),
  UNIQUE KEY `customer` (`customer_id`),
  CONSTRAINT `fk_individual_person_customer_customer` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`)
);

CREATE TABLE `Business_Customer` (
  `tax_identification_number` varchar(10) NOT NULL,
  `business_name` varchar(75) NOT NULL,
  `primary_contact_first_name` varchar(25) NOT NULL,
  `primary_contact_last_name` varchar(40) NOT NULL,
  `primary_contact_title` varchar(25) DEFAULT NULL,
  `customer_id` int(30) unsigned NOT NULL,
  PRIMARY KEY (`tax_identification_number`),
  UNIQUE KEY `customer` (`customer_id`),
  CONSTRAINT `fk_business_customer_customer` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`)
);

CREATE TABLE `Vehicle` (
  `vin` varchar(20) NOT NULL,
  `vehicle_description` varchar(250) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_year` int(4) NOT NULL,
  `vehicle_condition` set('Excellent','Very Good','Good','Fair') DEFAULT NULL,
  `mileage` int(6) NOT NULL,
  `buyer_customer_id` int(30) unsigned DEFAULT NULL,
  `seller_customer_id` int(30) unsigned DEFAULT NULL,
  `price_sold` decimal(13,2) DEFAULT NULL,
  `sold_date` datetime DEFAULT NULL,
  `price_purchase` decimal(13,2) NOT NULL,
  `purchase_date` datetime NOT NULL,
  PRIMARY KEY (`vin`),
  CONSTRAINT `fk_vehicle_buyer_customer_id_customer_customer_id` FOREIGN KEY (`buyer_customer_id`) REFERENCES `Customer` (`customer_id`),
  CONSTRAINT `fk_vehicle_seller_customer_id_customer_customer_id` FOREIGN KEY (`seller_customer_id`) REFERENCES `Customer` (`customer_id`)
);

CREATE TABLE `Loan` (
  `vin` varchar(20) NOT NULL,
  `start_month` datetime NOT NULL,
  `loan_term` int(2) NOT NULL,
  `monthly_payment` decimal(6,2) NOT NULL,
  `interest_rate` decimal(8,6) NOT NULL,
  `downpayment` decimal(10,2) NOT NULL,
  `customer_id` int(30) unsigned NOT NULL,
  PRIMARY KEY (`vin`),
  CONSTRAINT `fk_loan_customer` FOREIGN KEY (`customer_id`) REFERENCES `Customer` (`customer_id`),
  CONSTRAINT `fk_loan_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`)
);

CREATE TABLE `Vendor` (
  `vendor_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `street` varchar(125) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(30) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  PRIMARY KEY (`vendor_name`)
);

CREATE TABLE `Parts_Order` (
  `vin` varchar(20) NOT NULL,
  `burdells_purchase_order_number` varchar(25) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  PRIMARY KEY (`vin`,`burdells_purchase_order_number`),
  KEY `fk_parts_order_vendor` (`vendor_name`),
  CONSTRAINT `fk_parts_order_vendor` FOREIGN KEY (`vendor_name`) REFERENCES `Vendor` (`vendor_name`),
  CONSTRAINT `fk_parts_order_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`)
);

CREATE TABLE `Parts` (
  `vin` varchar(20) NOT NULL,
  `burdells_purchase_order_number` varchar(25) NOT NULL,
  `part_number` varchar(20) NOT NULL,
  `part_cost` decimal(6,2) NOT NULL,
  `part_status` set('ordered','received','installed') DEFAULT NULL,
  `description_of_the_part` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`vin`,`burdells_purchase_order_number`,`part_number`),
  CONSTRAINT `fk_parts_parts_order` FOREIGN KEY (`vin`, `burdells_purchase_order_number`) REFERENCES `Parts_Order` (`vin`, `burdells_purchase_order_number`)
);

CREATE TABLE `Manufacturer` (
  `manufacturer_name` varchar(20) NOT NULL,
  PRIMARY KEY (`manufacturer_name`)
);

CREATE TABLE `Color` (
  `vehicle_color` varchar(20) NOT NULL,
  PRIMARY KEY (`vehicle_color`)
);

CREATE TABLE `Vehicle_Type` (
  `vehicle_type_name` varchar(40) NOT NULL,
  PRIMARY KEY (`vehicle_type_name`)
);

CREATE TABLE `Manufacturered_By` (
  `manufacturer_name` varchar(40) NOT NULL,
  `vin` varchar(20) NOT NULL,
  UNIQUE KEY `vin` (`vin`),
  KEY `fk_manufacturer_manufacturer_lookup` (`manufacturer_name`),
  CONSTRAINT `fk_manufacturer_manufacturer_lookup` FOREIGN KEY (`manufacturer_name`) REFERENCES `Manufacturer` (`manufacturer_name`),
  CONSTRAINT `fk_manufacturer_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`)
);

CREATE TABLE `Has_Color` (
  `vehicle_color` varchar(20) NOT NULL,
  `vin` varchar(20) NOT NULL,
  PRIMARY KEY (`vehicle_color`,`vin`),
  KEY `fk_vehicle_color_vehicle` (`vin`),
  CONSTRAINT `fk_vehicle_color_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`),
  CONSTRAINT `fk_vehicle_color_vehicle_color_lookup` FOREIGN KEY (`vehicle_color`) REFERENCES `Color` (`vehicle_color`)
);

CREATE TABLE `Has_Type` (
  `vehicle_type_name` varchar(40) NOT NULL,
  `vin` varchar(20) NOT NULL,
  UNIQUE KEY `vin` (`vin`),
  KEY `fk_venhicle_type_vehicle_type_lookup` (`vehicle_type_name`),
  CONSTRAINT `fk_venhicle_type_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`),
  CONSTRAINT `fk_venhicle_type_vehicle_type_lookup` FOREIGN KEY (`vehicle_type_name`) REFERENCES `Vehicle_Type` (`vehicle_type_name`)
);

CREATE TABLE `Sales_Management_Staff` (
  `sales_staff_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sales_staff_id`)
);

CREATE TABLE `Inventory_Management_Staff` (
  `inventory_staff_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`inventory_staff_id`)
);

CREATE TABLE `Salesperson` (
  `username` varchar(20) NOT NULL,
  `password` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `sales_staff_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `sales_staff_id` (`sales_staff_id`),
  CONSTRAINT `fk_salesperson_sales_management_staff` FOREIGN KEY (`sales_staff_id`) REFERENCES `Sales_Management_Staff` (`sales_staff_id`)
);

CREATE TABLE `Inventory_Clerk` (
  `username` varchar(20) NOT NULL,
  `password` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `inventory_staff_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `inventory_staff_id` (`inventory_staff_id`),
  CONSTRAINT `fk_inventory_clerk_inventory_management_staff` FOREIGN KEY (`inventory_staff_id`) REFERENCES `Inventory_Management_Staff` (`inventory_staff_id`)
);

CREATE TABLE `Manager` (
  `username` varchar(20) NOT NULL,
  `password` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  PRIMARY KEY (`username`)
);

CREATE TABLE `Owner` (
  `username` varchar(20) NOT NULL,
  `password` varchar(25) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `sales_staff_id` int(5) unsigned NOT NULL,
  `inventory_staff_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `sales_staff_id` (`sales_staff_id`),
  UNIQUE KEY `inventory_staff_id` (`inventory_staff_id`),
  CONSTRAINT `fk_owner_inventory_management_staff` FOREIGN KEY (`inventory_staff_id`) REFERENCES `Inventory_Management_Staff` (`inventory_staff_id`),
  CONSTRAINT `fk_owner_sales_management_staff` FOREIGN KEY (`sales_staff_id`) REFERENCES `Sales_Management_Staff` (`sales_staff_id`)
);

CREATE TABLE `Sold_By` (
  `sales_staff_id` int(5) unsigned NOT NULL,
  `vin` varchar(20) NOT NULL,
  PRIMARY KEY (`sales_staff_id`,`vin`),
  KEY `fk_sold_by_vehicle` (`vin`),
  CONSTRAINT `fk_sold_by_sales_management_staff` FOREIGN KEY (`sales_staff_id`) REFERENCES `Sales_Management_Staff` (`sales_staff_id`),
  CONSTRAINT `fk_sold_by_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`)
);

CREATE TABLE `Checked_In_By` (
  `inventory_staff_id` int(5) unsigned NOT NULL,
  `vin` varchar(20) NOT NULL,
  PRIMARY KEY (`inventory_staff_id`,`vin`),
  KEY `fk_checked_in_by_vehicle` (`vin`),
  CONSTRAINT `fk_checked_in_by_inventory_management_staff` FOREIGN KEY (`inventory_staff_id`) REFERENCES `Inventory_Management_Staff` (`inventory_staff_id`),
  CONSTRAINT `fk_checked_in_by_vehicle` FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`)
);

INSERT INTO `Manufacturer` VALUES ('Acura'),('Alfa Romeo'),('Aston Martin'),('Audi'),('Bentley'),('BMW'),('Buick'),('Cadillac'),('Chevrolet'),('Chrysler'),('Dodge'),('Ferrari'),('FIAT'),('Ford'),('Freightliner'),('Genesis'),('GMC'),('Honda'),('Hyundai'),('INFINITI'),('Jaguar'),('Jeep'),('Kia'),('Lamborghini'),('Land Rover'),('Lexus'),('Lincoln'),('Lotus'),('Maserati'),('MAZDA'),('McLaren'),('Mercedes-Benz'),('MINI'),('Mitsubishi'),('Nissan'),('Porsche'),('Ram'),('Rolls-Royce'),('smart'),('Subaru'),('Tesla'),('Toyota'),('Volkswagen'),('Volvo');

INSERT INTO `Color` VALUES ('Aluminum'),('Beige'),('Black'),('Blue'),('Bronze'),('Brown'),('Claret'),('Copper'),('Cream'),('Gold'),('Gray'),('Green'),('Maroon'),('Metallic'),('Navy'),('Orange'),('Pink'),('Purple'),('Red'),('Rose'),('Rust'),('Silver'),('Tan'),('Turquoise'),('White'),('Yellow');

INSERT INTO `Vehicle_Type` VALUES ('Convertible'),('Coupe'),('Minivan'),('Other'),('Sedan'),('SUV'),('Truck'),('Van');
