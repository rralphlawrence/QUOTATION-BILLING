-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2022 at 05:56 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbmisout`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounting`
--

CREATE TABLE `tblaccounting` (
  `accounting_id` int(255) NOT NULL,
  `accounting_supplier` varchar(100) NOT NULL,
  `accounting_account_number` varchar(100) NOT NULL,
  `accounting_month` varchar(15) NOT NULL,
  `accounting_month_number` int(2) NOT NULL,
  `accounting_year` int(4) NOT NULL,
  `accounting_monthly_bill` varchar(100) NOT NULL,
  `accounting_notes` varchar(255) NOT NULL,
  `accounting_payment_client` decimal(10,2) NOT NULL,
  `accounting_paid_by_misout` decimal(10,2) NOT NULL,
  `accounting_vat` decimal(10,2) NOT NULL,
  `accounting_gross_income` decimal(10,2) NOT NULL,
  `accounting_commission` decimal(10,2) NOT NULL,
  `accounting_total_profit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblbonprogress`
--

CREATE TABLE `tblbonprogress` (
  `onprogressB_id` int(255) NOT NULL,
  `onprogressB_provider` varchar(100) NOT NULL,
  `onprogressB_transaction_id` varchar(100) NOT NULL,
  `onprogressB_date_created` date NOT NULL,
  `onprogressB_billing_summary` varchar(500) NOT NULL,
  `onprogressB_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblbreport`
--

CREATE TABLE `tblbreport` (
  `reportB_id` int(255) NOT NULL,
  `reportB_provider` varchar(100) NOT NULL,
  `reportB_transaction_id` varchar(100) NOT NULL,
  `reportB_transaction_date` date NOT NULL,
  `reportB_billing_summary` varchar(500) NOT NULL,
  `reportB_client_payment` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `cart_id` int(255) NOT NULL,
  `cart_product_name` varchar(100) NOT NULL,
  `cart_product_quantity` int(255) NOT NULL,
  `cart_product_unit` varchar(100) NOT NULL,
  `cart_product_price` decimal(10,2) NOT NULL,
  `cart_product_total` decimal(10,2) NOT NULL,
  `cart_product_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcashbookentry`
--

CREATE TABLE `tblcashbookentry` (
  `cbe_id` int(255) NOT NULL,
  `cbe_date` date NOT NULL,
  `cbe_order_by` int(100) NOT NULL,
  `cbe_description` varchar(255) NOT NULL,
  `cbe_inflows` decimal(10,2) NOT NULL,
  `cbe_outflows` decimal(10,2) NOT NULL,
  `cbe_balance` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcashflow`
--

CREATE TABLE `tblcashflow` (
  `cf_id` int(255) NOT NULL,
  `cf_date_month` varchar(4) NOT NULL,
  `cf_date_year` varchar(4) NOT NULL,
  `cf_type` varchar(100) NOT NULL,
  `cf_category` varchar(100) NOT NULL,
  `cf_description` varchar(100) NOT NULL,
  `cf_first_balance` decimal(10,2) NOT NULL,
  `cf_amount` decimal(10,2) NOT NULL,
  `cf_sign` varchar(10) NOT NULL,
  `cf_details` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblclient`
--

CREATE TABLE `tblclient` (
  `client_id` int(255) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_address` varchar(100) NOT NULL,
  `client_contact_number` varchar(11) NOT NULL,
  `client_contact_email` varchar(100) NOT NULL,
  `client_type` varchar(9) NOT NULL,
  `client_group` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcreatedbill`
--

CREATE TABLE `tblcreatedbill` (
  `cb_id` int(255) NOT NULL,
  `cb_transaction_id` varchar(100) NOT NULL,
  `cb_provider` varchar(100) NOT NULL,
  `cb_account_number` varchar(100) NOT NULL,
  `cb_client_name` varchar(100) NOT NULL,
  `cb_client_group` varchar(10) NOT NULL,
  `cb_service_promo` varchar(100) NOT NULL,
  `cb_billing_period` varchar(100) NOT NULL,
  `cb_billing_price` decimal(10,2) NOT NULL,
  `cb_client_payment` decimal(10,2) NOT NULL,
  `cb_receipt` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblgeneratebill`
--

CREATE TABLE `tblgeneratebill` (
  `gb_id` int(255) NOT NULL,
  `gb_provider` varchar(100) NOT NULL,
  `gb_account_number` varchar(100) NOT NULL,
  `gb_client_name` varchar(100) NOT NULL,
  `gb_client_group` varchar(10) NOT NULL,
  `gb_service_promo` varchar(100) NOT NULL,
  `gb_billing_period` varchar(100) NOT NULL,
  `gb_billing_price` decimal(10,2) NOT NULL,
  `gb_client_payment` decimal(10,2) NOT NULL,
  `gb_receipt` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblhistory`
--

CREATE TABLE `tblhistory` (
  `history_id` int(255) NOT NULL,
  `history_timestamp` varchar(100) NOT NULL,
  `history_action` varchar(100) NOT NULL,
  `history_user` varchar(100) NOT NULL,
  `history_app_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblincomestatement`
--

CREATE TABLE `tblincomestatement` (
  `is_id` int(255) NOT NULL,
  `is_month` varchar(4) NOT NULL,
  `is_year` varchar(4) NOT NULL,
  `is_type` varchar(100) NOT NULL,
  `is_category` varchar(100) NOT NULL,
  `is_description` varchar(100) NOT NULL,
  `is_amount` decimal(10,2) NOT NULL,
  `is_details` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblofficialreceipt`
--

CREATE TABLE `tblofficialreceipt` (
  `or_id` int(255) NOT NULL,
  `or_transaction_id` varchar(100) NOT NULL,
  `or_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

CREATE TABLE `tblorder` (
  `order_id` int(255) NOT NULL,
  `order_transaction_id` varchar(100) NOT NULL,
  `order_client_name` varchar(100) NOT NULL,
  `order_product_name` varchar(100) NOT NULL,
  `order_product_quantity` int(20) NOT NULL,
  `order_product_unit` varchar(100) NOT NULL,
  `order_product_price` int(255) NOT NULL,
  `order_product_total` decimal(10,2) NOT NULL,
  `order_product_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `product_id` int(255) NOT NULL,
  `product_supplier` varchar(100) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_details` varchar(1000) NOT NULL,
  `product_unit` varchar(100) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblqonprogress`
--

CREATE TABLE `tblqonprogress` (
  `onprogressQ_id` int(255) NOT NULL,
  `onprogressQ_client_name` varchar(100) NOT NULL,
  `onprogressQ_transaction_id` varchar(100) NOT NULL,
  `onprogressQ_date_created` date NOT NULL,
  `onprogressQ_order_summary` varchar(500) NOT NULL,
  `onprogressQ_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblqreport`
--

CREATE TABLE `tblqreport` (
  `reportQ_id` int(255) NOT NULL,
  `reportQ_client_name` varchar(100) NOT NULL,
  `reportQ_transaction_id` varchar(100) NOT NULL,
  `reportQ_transaction_date` date NOT NULL,
  `reportQ_summary` varchar(500) NOT NULL,
  `reportQ_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblsupplier`
--

CREATE TABLE `tblsupplier` (
  `supplier_id` int(255) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_address` varchar(100) NOT NULL,
  `supplier_contact_person` varchar(100) NOT NULL,
  `supplier_contact_number` varchar(11) NOT NULL,
  `supplier_contact_email` varchar(100) NOT NULL,
  `supplier_type` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbluseraccounts`
--

CREATE TABLE `tbluseraccounts` (
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_role` varchar(20) NOT NULL,
  `user_status` varchar(8) NOT NULL,
  `user_firstname` varchar(25) NOT NULL,
  `user_lastname` varchar(25) NOT NULL,
  `user_sex` varchar(6) NOT NULL,
  `user_contact` varchar(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_designation` varchar(100) NOT NULL,
  `user_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbluseraccounts`
--

INSERT INTO `tbluseraccounts` (`user_name`, `user_password`, `user_role`, `user_status`, `user_firstname`, `user_lastname`, `user_sex`, `user_contact`, `user_email`, `user_designation`, `user_image`) VALUES
('admin', '$2y$10$u9ao4EDbz54wCc8s4CKSuOw2G.3d8aUY15BCY9mgVA4FqwBmkYkGG', 'Administrator', 'Active', 'Christian', 'Chang', 'Male', '', '', 'President', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounting`
--
ALTER TABLE `tblaccounting`
  ADD PRIMARY KEY (`accounting_id`);

--
-- Indexes for table `tblbonprogress`
--
ALTER TABLE `tblbonprogress`
  ADD PRIMARY KEY (`onprogressB_id`);

--
-- Indexes for table `tblbreport`
--
ALTER TABLE `tblbreport`
  ADD PRIMARY KEY (`reportB_id`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `tblcashbookentry`
--
ALTER TABLE `tblcashbookentry`
  ADD PRIMARY KEY (`cbe_id`);

--
-- Indexes for table `tblcashflow`
--
ALTER TABLE `tblcashflow`
  ADD PRIMARY KEY (`cf_id`);

--
-- Indexes for table `tblclient`
--
ALTER TABLE `tblclient`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `tblcreatedbill`
--
ALTER TABLE `tblcreatedbill`
  ADD PRIMARY KEY (`cb_id`);

--
-- Indexes for table `tblgeneratebill`
--
ALTER TABLE `tblgeneratebill`
  ADD PRIMARY KEY (`gb_id`);

--
-- Indexes for table `tblhistory`
--
ALTER TABLE `tblhistory`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `tblincomestatement`
--
ALTER TABLE `tblincomestatement`
  ADD PRIMARY KEY (`is_id`);

--
-- Indexes for table `tblofficialreceipt`
--
ALTER TABLE `tblofficialreceipt`
  ADD PRIMARY KEY (`or_id`);

--
-- Indexes for table `tblorder`
--
ALTER TABLE `tblorder`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tblqonprogress`
--
ALTER TABLE `tblqonprogress`
  ADD PRIMARY KEY (`onprogressQ_id`);

--
-- Indexes for table `tblqreport`
--
ALTER TABLE `tblqreport`
  ADD PRIMARY KEY (`reportQ_id`);

--
-- Indexes for table `tblsupplier`
--
ALTER TABLE `tblsupplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbluseraccounts`
--
ALTER TABLE `tbluseraccounts`
  ADD PRIMARY KEY (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblaccounting`
--
ALTER TABLE `tblaccounting`
  MODIFY `accounting_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblbonprogress`
--
ALTER TABLE `tblbonprogress`
  MODIFY `onprogressB_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblbreport`
--
ALTER TABLE `tblbreport`
  MODIFY `reportB_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `cart_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcashbookentry`
--
ALTER TABLE `tblcashbookentry`
  MODIFY `cbe_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcashflow`
--
ALTER TABLE `tblcashflow`
  MODIFY `cf_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblclient`
--
ALTER TABLE `tblclient`
  MODIFY `client_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcreatedbill`
--
ALTER TABLE `tblcreatedbill`
  MODIFY `cb_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblgeneratebill`
--
ALTER TABLE `tblgeneratebill`
  MODIFY `gb_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblhistory`
--
ALTER TABLE `tblhistory`
  MODIFY `history_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblincomestatement`
--
ALTER TABLE `tblincomestatement`
  MODIFY `is_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblofficialreceipt`
--
ALTER TABLE `tblofficialreceipt`
  MODIFY `or_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblorder`
--
ALTER TABLE `tblorder`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblqonprogress`
--
ALTER TABLE `tblqonprogress`
  MODIFY `onprogressQ_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblqreport`
--
ALTER TABLE `tblqreport`
  MODIFY `reportQ_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsupplier`
--
ALTER TABLE `tblsupplier`
  MODIFY `supplier_id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
