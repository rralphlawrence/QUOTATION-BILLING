<?php
session_start();
include_once 'dbconnect.php';
require ('fpdf182/fpdf.php');
require_once "vendor/autoload.php";

date_default_timezone_set('Asia/Manila');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;





//Login Process

if(isset($_POST['btn-login'])){
    $Username = $_POST['uname'];
    $Password = $_POST['psw'];

    $sqlforNoAccount = "SELECT * FROM tbluseraccounts WHERE user_name = '$Username' AND user_status = 'Active'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $UserPassword = $row['user_password'];
            $UserRole = $row['user_role'];
            $UserLast = $row['user_lastname'];
            $UserFirst = $row['user_firstname'];
            $UserRealname = $row['user_firstname'] . ' ' . $row['user_lastname'];
            $UserSex = $row['user_sex'];
            $UserDesignation = $row['user_designation'];
            $UserContact = $row['user_contact'];
            $UserEmail = $row['user_email'];
            $UserImage = $row['user_image'];
    }
    
        if(password_verify($Password,$UserPassword)){


            $sqlforNoAccount = "SELECT DISTINCT MAX(YEAR(cbe_date)) AS year, MAX(MONTH(cbe_date)) AS month FROM `tblcashbookentry`";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
            $year = 0;
            $month = 0;
            while ($row = $result->fetch_assoc()) {

                    
                    $month = $row['month'];
                    $year = $row['year'];
            }


            if($UserRole == 'Bookkeeping'){

                header("Location: ../Bookkeeping/Dashboard.php");
                $_SESSION ['user_realname'] = $UserRealname;
                $_SESSION ['user_last'] = $UserLast;
                $_SESSION ['user_first'] = $UserFirst;
                $_SESSION ['user_role'] = $UserRole;
                $_SESSION ['user_designation'] = $UserDesignation;
                $_SESSION ['user_sex'] = $UserSex;
                $_SESSION ['user_image'] = $UserImage;
                $_SESSION ['user_contact'] = $UserContact;
                $_SESSION ['user_email'] = $UserEmail;
                $_SESSION ['user_name'] = $Username;
                $_SESSION ['user_password'] = $UserPassword;
                $_SESSION ['isLoggedin'] = true;
                $_SESSION ['year_is'] = $year;
                $_SESSION ['month_is']= $month;
                $_SESSION ['year'] = $year;
                $_SESSION ['month']= $month;
                $_SESSION['provider_selected'] = "wala";
                $_SESSION['accountno_selected'] = "wala";
                $_SESSION['accountyear_selected'] = "wala";
                                
                //$_SESSION ['ISdetails']= "for the Month ended October 2021";
                $_SESSION ['response'] = "Successfully Login!";
                $_SESSION ['res_type']= "success";

            }elseif($UserRole == 'Billing'){

                header("Location: ../Admin/Dashboard.php");
                $_SESSION ['user_last'] = $UserLast;
                $_SESSION ['user_first'] = $UserFirst;
                $_SESSION ['user_realname'] = $UserRealname;
                $_SESSION ['user_name'] = $Username;
                $_SESSION ['user_role'] = $UserRole;
                $_SESSION ['user_designation'] = $UserDesignation;
                $_SESSION ['user_sex'] = $UserSex;
                $_SESSION ['user_image'] = $UserImage;
                $_SESSION ['user_contact'] = $UserContact;
                $_SESSION ['user_email'] = $UserEmail;
                $_SESSION ['year_is'] = $year;
                $_SESSION ['month_is']= $month;
                $_SESSION ['year'] = $year;
                $_SESSION ['month']= $month;
                $_SESSION ['user_password'] = $UserPassword;
                $_SESSION ['isLoggedin'] = true;
                $_SESSION['selected_supplier'] = 'wala';
                $_SESSION ['selected_provider'] = 'wala';
                $_SESSION['clientNameQ'] = 'No Record';
                $_SESSION['transactionQID'] = 'No Record';
                $_SESSION['clientNameB'] = 'No Record';
                $_SESSION['transactionBID'] = 'No Record'; 
                $_SESSION['date_year_showbill'] = 0;
                $_SESSION['date_month_showbill'] = 0;
                $_SESSION['date_month_show'] = 0;
                $_SESSION['date_year_show'] = 0;
                $_SESSION ['supplier-selectedItem'] = "Quotation";
                $_SESSION ['client-selectedItem'] = "Billing";
                $_SESSION ['supplierName'] = "wala"; 
                $_SESSION['provider_selected'] = "wala";
                $_SESSION['accountno_selected'] = "wala";
                $_SESSION['accountyear_selected'] = "wala";
                                
                //$_SESSION ['ISdetails']= "for the Month ended October 2021";
                $_SESSION ['response'] = "Successfully Login!";
                $_SESSION ['res_type']= "success";

            }else{

                header("Location: ../CEO/dashboard.php");
                $_SESSION ['user_last'] = $UserLast;
                $_SESSION ['user_first'] = $UserFirst;
                $_SESSION ['user_realname'] = $UserRealname;
                $_SESSION ['user_name'] = $Username;
                $_SESSION ['user_role'] = $UserRole;
                $_SESSION ['user_designation'] = $UserDesignation;
                $_SESSION ['user_sex'] = $UserSex;
                $_SESSION ['user_image'] = $UserImage;
                $_SESSION ['user_password'] = $UserPassword;
                $_SESSION ['user_contact'] = $UserContact;
                $_SESSION ['user_email'] = $UserEmail;
                $_SESSION ['year_is'] = $year;
                $_SESSION ['month_is']= $month;
                $_SESSION['transaction_id'] = 'wala'; 
                $_SESSION['transaction_id1'] = 'wala'; 
                $_SESSION['transaction_idB'] = 'wala'; 
                $_SESSION['transaction_idB1'] = 'wala'; 
                $_SESSION ['year'] = $year;
                $_SESSION ['month']= $month;
                $_SESSION ['isLoggedin'] = true;
                                
                //$_SESSION ['ISdetails']= "for the Month ended October 2021";
                $_SESSION ['response'] = "Successfully Login!";
                $_SESSION ['res_type']= "success";


            }

            $timeStamp = date("F j Y h:i A");
            $action = "Login the system";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }
            

           
            

        }else{
            header("Location: ../index.php");
            $_SESSION ['response'] = "The Password you’ve entered is incorrect.";
            $_SESSION ['res_type']= "error";
        }

}


if(isset($_POST['show_table'])){

    $month = $_POST['month'];
    $year = $_POST['year'];


    header("Location: ../Bookkeeping/B-Cashbook-Entry.php");
    $_SESSION ['year'] = $year;
    $_SESSION ['month']= $month;



}


if(isset($_POST['run_dashboard_report'])){

    $year = $_POST['year'];


    header("Location: ../Bookkeeping/Dashboard.php");
    $_SESSION ['year_is'] = $year;

}



//Add and Edit Entry on Cashbook 

if(isset($_POST['add_entry'])){
    
    $Date = mysqli_real_escape_string($conn, $_POST['date']);
    $Month = substr($Date,5,2);
    
    $Year = substr($Date,0,4);
    $ID = mysqli_real_escape_string($conn, $_POST['id']);
    $OD = mysqli_real_escape_string($conn, $_POST['od']);
    $OldDate = mysqli_real_escape_string($conn, $_POST['Olddate']);
    $Description = mysqli_real_escape_string($conn, $_POST['description']);
    $Amount = mysqli_real_escape_string($conn, $_POST['amount']);

    $Type = mysqli_real_escape_string($conn, $_POST['type_of_entry']);

    
    if($Type == "Outflows"){
        $Inflows = 0;
        $Outflows = $Amount;
    }else{
        $Inflows = $Amount;
        $Outflows = 0;
    }



    //Adding entry
    if($ID == ""){


        $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE (MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year') AND cbe_date > '$Date' Order By cbe_date, cbe_order_by ASC";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);
    
        if(mysqli_num_rows($sqlrun)>0){
        //This section ay may date na nalampasan
            
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            $IDS = array();

            $counter = 0;
            while ($row = $result->fetch_assoc()) {
            $IDS[$counter] = $row['cbe_id'];
            $counter++;
            }

            $numberNeedUpdate = count($IDS);


            $sqlforNoAccount = "SELECT cbe_balance, cbe_order_by  FROM tblcashbookentry WHERE cbe_order_by = (SELECT MAX(cbe_order_by) FROM tblcashbookentry WHERE (cbe_date = '$Date') AND  (MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year')) AND cbe_date = '$Date'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                    $prevBal = $row['cbe_balance'];
                    $order = $row['cbe_order_by'];
            }

            if($prevBal == 0){
                $sqlforNoAccount = "SELECT cbe_balance FROM tblcashbookentry WHERE (cbe_order_by = (SELECT MAX(cbe_order_by) from tblcashbookentry WHERE cbe_date = (SELECT MAX(cbe_date) from tblcashbookentry WHERE cbe_date < '$Date')) AND cbe_date = (SELECT MAX(cbe_date) from tblcashbookentry WHERE cbe_date < '$Date')) AND (MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year')";
                $stmt = $conn->prepare($sqlforNoAccount);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $prevBal = $row['cbe_balance'];
                }    
            }

            
            if($Inflows == "0"){
                $Balance = $prevBal - $Outflows;
                $order = $order + 1;
            }else{
                $Balance = $prevBal + $Inflows;
                $order = $order + 1;
            }
        
        
        
            $sqlforAccounts = "INSERT INTO tblcashbookentry(cbe_id, cbe_date, cbe_order_by, cbe_description, cbe_inflows, cbe_outflows, cbe_balance) VALUES ('',?,?,?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssssss",$Date,$order,$Description,$Inflows,$Outflows,$Balance);
                mysqli_stmt_execute($stmt);
            }


            for ($i=0; $i < $numberNeedUpdate ; $i++) { 
                # code...
        
                $sqlforNoAccount = "SELECT * FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
                $stmt = $conn->prepare($sqlforNoAccount);
                $stmt->execute();
                $result = $stmt->get_result();
            
                while ($row = $result->fetch_assoc()) {
                        $update_inflows = $row['cbe_inflows'];
                        $update_outflows = $row['cbe_outflows'];
                        $update_date = $row['cbe_date'];
                        $update_order = $row['cbe_order_by'];
                        $update_description = $row['cbe_description'];
        
                }
        
        
                if($update_inflows == "0"){
                    $Balance = $Balance - $update_outflows;
                }else{
                    $Balance = $Balance + $update_inflows;
                }
        
        
                $sqlforupdateaccount="UPDATE tblcashbookentry SET cbe_id= '$IDS[$i]',cbe_date='$update_date',cbe_order_by='$update_order',cbe_description='$update_description',cbe_inflows='$update_inflows',cbe_outflows='$update_outflows',cbe_balance='$Balance' WHERE cbe_id = ?";
        
                $stmt = mysqli_stmt_init($conn);
                
                        
                        if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"s",$IDS[$i]);
                            mysqli_stmt_execute($stmt);
                        }
            }


            $timeStamp = date("F j Y h:i A");
            $action = "Updated Record to Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }
        
        
            header("Location: ../Bookkeeping/B-Cashbook-Entry.php");
            $_SESSION ['response'] = "Successfully Account Added";
            $_SESSION ['res_type']= "success";
            $_SESSION ['year'] = $Year;
            if($Month < 10){
                $Month = substr($Date,6,1);
            }
            $_SESSION ['month']= $Month;


        }else{
        //This section ay walang date na nalampasan

        $sqlforNoAccount = "SELECT cbe_balance FROM tblcashbookentry WHERE (MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year') Order By cbe_date DESC, cbe_order_by DESC LIMIT 1";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);
    
        //May record
        if(mysqli_num_rows($sqlrun)>0){
            
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $balance = $row['cbe_balance']; 
        }

        if($Type == "Outflows"){
            $balance = $balance - $Amount;
        }else{
            $balance = $balance + $Amount;
        }


        //Check kung may katulad ng date 

        $sqlforNoAccount = "SELECT MAX(cbe_order_by) AS cbe_order_by FROM tblcashbookentry WHERE (cbe_date = '$Date')";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);
    
        if(mysqli_num_rows($sqlrun)>0){
            
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $order = $row['cbe_order_by'];
        }

        $order = $order + 1;


        $sqlforAccounts = "INSERT INTO tblcashbookentry(cbe_id,cbe_date, cbe_order_by, cbe_description, cbe_inflows, cbe_outflows, cbe_balance) VALUES ('',?,?,?,?,?,?);";
        
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssssss",$Date,$order,$Description,$Inflows,$Outflows,$balance);
            mysqli_stmt_execute($stmt);
        }

            $timeStamp = date("F j Y h:i A");
            $action = "Inserted New Record to Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }
           
            header("Location: ../Bookkeeping/B-Cashbook-Entry.php");
            $_SESSION ['year'] = $Year;
            if($Month < 10){
                $Month = substr($Date,6,1);
            }
            $_SESSION ['month']= $Month;
            $_SESSION ['response'] = "Successfully Account Added";
            $_SESSION ['res_type']= "success";

        }else{

            $order = 1;


            $sqlforAccounts = "INSERT INTO tblcashbookentry(cbe_id,cbe_date, cbe_order_by, cbe_description, cbe_inflows, cbe_outflows, cbe_balance) VALUES ('',?,?,?,?,?,?);";
            
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssssss",$Date,$order,$Description,$Inflows,$Outflows,$balance);
                mysqli_stmt_execute($stmt);
            }

            $timeStamp = date("F j Y h:i A");
            $action = "Inserted new record";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

               
                header("Location: ../Bookkeeping/B-Cashbook-Entry.php");
                $_SESSION ['year'] = $Year;
                if($Month < 10){
                    $Month = substr($Date,6,1);
                }
                $_SESSION ['month']= $Month;
                $_SESSION ['response'] = "Successfully Account Added";
                $_SESSION ['res_type']= "success";


        }   



    
    
        
        //New Entry
        }else{
        
            if($Type == "Outflows"){
                $balance = 0 - $Outflows;
            }else{
                $balance = 0 + $Inflows;
            }
        
            
            $order = 1;
            
            $sqlforAccounts = "INSERT INTO tblcashbookentry(cbe_id, cbe_date, cbe_order_by, cbe_description, cbe_inflows, cbe_outflows, cbe_balance) VALUES ('',?,?,?,?,?,?);";
            
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssssss",$Date,$order,$Description,$Inflows,$Outflows,$balance);
                mysqli_stmt_execute($stmt);
            }

            $timeStamp = date("F j Y h:i A");
            $action = "Inserted new record";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }
               
            header("Location: ../Bookkeeping/B-Cashbook-Entry.php");
                $_SESSION ['year'] = $Year;
                if($Month < 10){
                    $Month = substr($Date,6,1);
                }
                $_SESSION ['month']= $Month;
                $_SESSION ['response'] = "Successfully Account Added";
                $_SESSION ['res_type']= "success";
    
            }


        }    
            


    //This Section will be the Edit/Update of Data 
    }else{  



    //Updating Values with no change of date
    if($Date == $OldDate){

    $sqlforNoAccount = "SELECT cbe_id, cbe_order_by FROM tblcashbookentry WHERE MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year' AND cbe_date >= '$Date' Order By cbe_date, cbe_order_by ASC";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    $IDS = array();

    $counter = 0;
    $startToCount = 0;
    $prevID = 0;

    while ($row = $result->fetch_assoc()) {



        if($row['cbe_id'] == $ID  && $row['cbe_order_by'] == $OD){
            $startToCount = 1;
        }

        if($startToCount == 1){
        
            if($row['cbe_id'] != $ID){
                $IDS[$counter] = $row['cbe_id'];
                $counter++;
            }
        }else{
            $prevID = $row['cbe_id'];
        }

    }

    $numberNeedUpdate = count($IDS);

    
    if($prevID == 0){

    $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE (cbe_order_by = (SELECT MAX(cbe_order_by) from tblcashbookentry WHERE cbe_date = (SELECT MAX(cbe_date) from tblcashbookentry WHERE cbe_date < '$Date')) AND cbe_date = (SELECT MAX(cbe_date) from tblcashbookentry WHERE cbe_date < '$Date')) AND (MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year')";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $prevID = $row['cbe_id'];
    }    
    }

    $sqlforNoAccount = "SELECT cbe_balance FROM tblcashbookentry WHERE cbe_id = '$prevID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    $prevBal = 0;

    while ($row = $result->fetch_assoc()) {
            $prevBal = $row['cbe_balance'];
    }


    
    if($Inflows == "0"){
        $balance = $prevBal - $Outflows;
    }else{
        $balance = $prevBal + $Inflows;
    }

    $sqlforupdateaccount="UPDATE tblcashbookentry SET cbe_id= '$ID',cbe_date='$Date',cbe_order_by='$OD',cbe_description='$Description',cbe_inflows='$Inflows',cbe_outflows='$Outflows',cbe_balance='$balance' WHERE cbe_id = ?";

    $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"s",$ID);
                mysqli_stmt_execute($stmt);
            }


    for ($i=0; $i < $numberNeedUpdate ; $i++) { 
        # code...

        $sqlforNoAccount = "SELECT * FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $update_inflows = $row['cbe_inflows'];
                $update_outflows = $row['cbe_outflows'];
                $update_date = $row['cbe_date'];
                $update_order = $row['cbe_order_by'];
                $update_description = $row['cbe_description'];

        }


        if($update_inflows == "0"){
            $balance = $balance - $update_outflows;
        }else{
            $balance = $balance + $update_inflows;
        }


        $sqlforupdateaccount="UPDATE tblcashbookentry SET cbe_id= '$IDS[$i]',cbe_date='$update_date',cbe_order_by='$update_order',cbe_description='$update_description',cbe_inflows='$update_inflows',cbe_outflows='$update_outflows',cbe_balance='$balance' WHERE cbe_id = ?";

        $stmt = mysqli_stmt_init($conn);
        
                
                if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"s",$IDS[$i]);
                    mysqli_stmt_execute($stmt);
                }
    }


    $timeStamp = date("F j Y h:i A");
            $action = "Updated Record to Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

    header("Location: ../Bookkeeping/B-Cashbook-Entry.php");
    $_SESSION ['response'] = "Successfully Account Updated";
    $_SESSION ['res_type']= "success";
    $_SESSION ['year'] = $Year;
    if($Month < 10){
        $Month = substr($Date,6,1);
    }
    $_SESSION ['month']= $Month;

        

    //Change date also
    }else{




    }
    }   
}



//Delete Record

if(isset($_POST['delete_btn'])){


   
    $ID=$_POST['delete_id'];
    $Date=$_POST['delete_date'];
    $OD=$_POST['delete_od'];
    $Month = substr($Date,5,2);
    $Year = substr($Date,0,4);


    $sqlforNoAccount = "SELECT cbe_id, cbe_order_by FROM tblcashbookentry WHERE MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year' AND cbe_date >= '$Date' Order By cbe_date, cbe_order_by ASC";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    $IDS = array();

    $counter = 0;
    $startToCount = 0;
    $prevID = 0;

    while ($row = $result->fetch_assoc()) {



        if($row['cbe_id'] == $ID  && $row['cbe_order_by'] == $OD){
            $startToCount = 1;
        }

        if($startToCount == 1){
        
            if($row['cbe_id'] != $ID){
                $IDS[$counter] = $row['cbe_id'];
                $counter++;
            }
        }else{
            $prevID = $row['cbe_id'];
        }

    }

    $numberNeedUpdate = count($IDS);

    
    if($prevID == 0){

    $sqlforNoAccount = "SELECT cbe_id, cbe_order_by FROM tblcashbookentry WHERE (cbe_order_by = (SELECT MAX(cbe_order_by) from tblcashbookentry WHERE cbe_date = (SELECT MAX(cbe_date) from tblcashbookentry WHERE cbe_date < '$Date')) AND cbe_date = (SELECT MAX(cbe_date) from tblcashbookentry WHERE cbe_date < '$Date')) AND (MONTH(cbe_date) = '$Month' AND YEAR(cbe_date) = '$Year')";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $prevID = $row['cbe_id'];
    }    
    }

    $sqlforNoAccount = "SELECT cbe_balance FROM tblcashbookentry WHERE cbe_id = '$prevID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    $prevBal = 0;

    while ($row = $result->fetch_assoc()) {
            $prevBal = $row['cbe_balance'];
    }

    

    $sqlfordeletfiletask="DELETE FROM tblcashbookentry WHERE cbe_id=?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$ID);
        mysqli_stmt_execute($stmt);
    }



    $balance =  $prevBal;

    for ($i=0; $i < $numberNeedUpdate ; $i++) { 
        # code...

        $sqlforNoAccount = "SELECT * FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $update_inflows = $row['cbe_inflows'];
                $update_outflows = $row['cbe_outflows'];
                $update_date = $row['cbe_date'];
                $update_order = $row['cbe_order_by'];
                $update_description = $row['cbe_description'];

        }


        if($update_inflows == "0"){
            $balance = $balance - $update_outflows;
        }else{
            $balance = $balance + $update_inflows;
        }


        $sqlforupdateaccount="UPDATE tblcashbookentry SET cbe_id= '$IDS[$i]',cbe_date='$update_date',cbe_order_by='$update_order',cbe_description='$update_description',cbe_inflows='$update_inflows',cbe_outflows='$update_outflows',cbe_balance='$balance' WHERE cbe_id = ?";

        $stmt = mysqli_stmt_init($conn);
        
                
                if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"s",$IDS[$i]);
                    mysqli_stmt_execute($stmt);
                }
    }

            $timeStamp = date("F j Y h:i A");
            $action = "Deleted Record to Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

    $_SESSION ['year'] = $Year;
    if($Month < 10){
        $Month = substr($Date,6,1);
    }
    $_SESSION ['month']= $Month;



}


if(isset($_POST['generate-monthly'])){


    $WhatWillGenerate = $_POST['whatshouldIDOM'];
    $Month = $_POST['monthG'];
    $Year = $_POST['yearM'];
    $last_date = '1999-08-30';
    //$BusinessName = mysqli_real_escape_string($conn,   $_SESSION ['business_name']);
    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);

    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }

        $MonthDetails = array('',
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July ',
        'August',
        'September',
        'October',
        'November',
        'December');

   
    

    if($WhatWillGenerate == "Income Statement"){

            $timeStamp = date("F j Y h:i A");
            $action = "Generated Monthly Income Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

        

        $sqlforupdateaccount="DELETE FROM `tblincomestatement` WHERE `is_month` = ? AND `is_year` = ?";

        $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Month,$Year);
                mysqli_stmt_execute($stmt);
            }

        


        $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Month' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();


        $IDS = array();

        $counter = 0;
        while ($row = $result->fetch_assoc()) {
        $IDS[$counter] = $row['cbe_id'];
        $counter++;
        }

        $numberNeedUpdate = count($IDS);


        for ($i=0; $i < $numberNeedUpdate ; $i++) { 
            # code...
    
            $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                
                    $update_description = $row['cbe_description'];
                    $update_inflows = $row['cbe_inflows'];
                    $update_outflows = $row['cbe_outflows'];
                    $last_date = $row['cbe_date'];
            }

            if($update_description == "Beginning balance" || $update_description == "Investment" || $update_description == "Bank Financing Long Term" || $update_description == "Shareholder Investment" || $update_description == "Other source of cash" || $update_description == "Amortization Expenses" || $update_description == "Loan Payments - Long term" || $update_description == "Other uses of cash" || $update_description == "Equipment" || $update_description == "Vehicle" || $update_description == "Furniture" || $update_description == "Other non-current assets" || $update_description == "Ending Balance"){

            }else{
                
                if($update_description == "Sales" || $update_description == "Service Income" || $update_description == "Interest Income" || $update_description == "Bank Financing Short Term" || $update_description == "Customer Deposits" || $update_description == "Other Income"){
                    $amount = $update_inflows;
                    $category= 'INCOME';
                }
                else{
                    $amount = $update_outflows;
                    $category= 'EXPENSES';
                }
            
            $type= 'Monthly';


            $sqlforNoAccount = "SELECT is_id, is_amount FROM tblincomestatement WHERE is_month = '$Month' AND is_year = '$Year' AND is_description='$update_description'";
            $sqlrun = mysqli_query($conn, $sqlforNoAccount);
    
            if(mysqli_num_rows($sqlrun)>0){
            
                $stmt = $conn->prepare($sqlforNoAccount);
                $stmt->execute();
                $result = $stmt->get_result();
    
                while ($row = $result->fetch_assoc()) {
                    $id = $row['is_id']; 
                    $prev_amount = $row['is_amount'];
                }

                $amount = $prev_amount + $amount;

                $sqlforAccounts = "UPDATE tblincomestatement SET is_amount='$amount' WHERE is_id = ?";
                
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"s",$id);
                    mysqli_stmt_execute($stmt);
                }

            }else{


                $sqlforAccounts = "INSERT INTO tblincomestatement(is_id, is_month, is_year, is_type, is_category, is_description, is_amount,is_details) VALUES ('',?,?,?,?,?,?,'');";
            
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"ssssss",$Month,$Year,$type,$category,$update_description,$amount);
                    mysqli_stmt_execute($stmt);
                }
            }
            }

               
        }

        $date = DateTime::createFromFormat('Y-m-d', $last_date);

        

        $ISdetails = 'for the Month ended ' . $date->format('F d, Y');

        $sqlforAccounts = "UPDATE tblincomestatement SET is_details='$ISdetails' WHERE `is_month` = ? AND `is_year` = ?";
                
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"ss",$Month,$Year);
                    mysqli_stmt_execute($stmt);
                }




                $timeStamp = date("F j Y h:i A");
                $action = "Generated Income Statement";
                $name = $_SESSION ['user_realname'];
                $apptype = $_SESSION ['user_role'];
                
                $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
            
                $stmt = mysqli_stmt_init($conn);
    
                if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                    mysqli_stmt_execute($stmt);
                }

        header("Location: ../Bookkeeping/B-Income-Statement.php");
        $_SESSION ['response'] = "Successfully Income Statement Generated";
        $_SESSION ['res_type']= "success";
        $_SESSION ['year_is'] = $Year;
        $_SESSION ['month_is']= $Month;
        $_SESSION ['ISdetails']= $ISdetails;


    }
    else if($WhatWillGenerate == "Cash Flow"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Monthly Cash Flow Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


        $sqlforupdateaccount="DELETE FROM `tblcashflow` WHERE `cf_date_month` = ? AND `cf_date_year` = ?";

        $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Month,$Year);
                mysqli_stmt_execute($stmt);
            }


            $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Month' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
    
    
            $IDS = array();
    
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
            $IDS[$counter] = $row['cbe_id'];
            $counter++;
            }
    
            $numberNeedUpdate = count($IDS);
    
    
            $f = 0;
            for ($i=0; $i < $numberNeedUpdate ; $i++) { 
                # code...
        
                $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
                $stmt = $conn->prepare($sqlforNoAccount);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    
                        $update_description = $row['cbe_description'];
                        $update_inflows = $row['cbe_inflows'];
                        $update_outflows = $row['cbe_outflows'];
                        $last_date = $row['cbe_date'];

                        if($f == 0){
                            if($update_description == "Beginning balance"){
                                $first_balance = $row['cbe_balance'];
                                
                            }else{
                                $first_balance = 0;
                            }

                            $f++;
                        }
                }

                if($update_description == "Beginning balance"){

                }else{

                    
        
                if($update_inflows == "0"){
                    $amount = $update_outflows;
                    $sign= 'negative';
                }else{
                    $amount = $update_inflows;
                    $sign= 'positive';
                }
                
                $type= 'Monthly';


                if($update_description == "Equipment" || $update_description == "Vehicle" || $update_description == "Furniture"){
                    $category = 'INVESTING';

                }else if($update_description == "Investment" || $update_description == "Other source of cash" || $update_description == "Other uses of cash" || $update_description == "Bank Financing Long Term" || $update_description == "Loan Payments - Long term"){
                    $category = 'FINANCING';
                }else{
                    $category = 'OPERATING';
                }


    
    
                $sqlforNoAccount = "SELECT cf_id, cf_amount FROM tblcashflow WHERE cf_date_month = '$Month' AND cf_date_year = '$Year' AND cf_description='$update_description'";
                $sqlrun = mysqli_query($conn, $sqlforNoAccount);
        
                if(mysqli_num_rows($sqlrun)>0){
                
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();
        
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['cf_id']; 
                        $prev_amount = $row['cf_amount'];
                    }
    
                    $amount = $prev_amount + $amount;
    
                    $sqlforAccounts = "UPDATE tblcashflow SET cf_amount='$amount' WHERE cf_id = ?";
                    
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"s",$id);
                        mysqli_stmt_execute($stmt);
                    }
    
                }else{
    
    
                    $sqlforAccounts = "INSERT INTO tblcashflow(cf_id, cf_date_month, cf_date_year, cf_type, cf_category, cf_description, cf_first_balance, cf_amount, cf_sign, cf_details) VALUES ('',?,?,?,?,?,'',?,?,'');";
                
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"sssssss",$Month,$Year,$type,$category,$update_description,$amount,$sign);
                        mysqli_stmt_execute($stmt);
                    }
                }

                }
            }

            $date = DateTime::createFromFormat('Y-m-d', $last_date);
            $ISdetails = 'for the Month ended ' . $date->format('F d, Y');

    
    
            $sqlforAccounts = "UPDATE tblcashflow SET cf_details='$ISdetails', cf_first_balance='$first_balance' WHERE `cf_date_month` = ? AND `cf_date_year` = ?";
                
            $stmt = mysqli_stmt_init($conn);
    
            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Month,$Year);
                mysqli_stmt_execute($stmt);
            }
    
    
                    $timeStamp = date("F j Y h:i A");
                    $action = "Generated Cash Flow Statement";
                    $name = $_SESSION ['user_realname'];
                    $apptype = $_SESSION ['user_role'];
                    
                    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
                
                    $stmt = mysqli_stmt_init($conn);
        
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                        mysqli_stmt_execute($stmt);
                    }
    
    
    
            header("Location: ../Bookkeeping/B-Cash-flow.php");
            $_SESSION ['response'] = "Successfully Cash Flow Statement Generated";
            $_SESSION ['res_type']= "success";
            $_SESSION ['year_is'] = $Year;
            $_SESSION ['month_is']= $Month;
            $_SESSION ['ISdetails']= $ISdetails;
    
    














    }
    else if($WhatWillGenerate == "Print"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Printable Monthly Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


        $fileName = 'Cashbook Entry Records-' . $MonthDetails[$Month].'-'.$Year.'.pdf';


        
class PDF extends FPDF
{


    function __construct()
		{
			parent::__construct();
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
			parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
		}

		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
			parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
		}

		function Write($h, $txt, $link='')
		{
			parent::Write($h, $this->normalize($txt), $link);
		}

		function Text($x, $y, $txt)
		{
			parent::Text($x, $y, $this->normalize($txt));
		}

		protected function normalize($word)
		{
			// Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
			
			$word = str_replace("@","%40",$word);
			$word = str_replace("`","%60",$word);
			$word = str_replace("¢","%A2",$word);
			$word = str_replace("£","%A3",$word);
			$word = str_replace("¥","%A5",$word);
			$word = str_replace("|","%A6",$word);
			$word = str_replace("«","%AB",$word);
			$word = str_replace("¬","%AC",$word);
			$word = str_replace("¯","%AD",$word);
			$word = str_replace("º","%B0",$word);
			$word = str_replace("±","%B1",$word);
			$word = str_replace("ª","%B2",$word);
			$word = str_replace("µ","%B5",$word);
			$word = str_replace("»","%BB",$word);
			$word = str_replace("¼","%BC",$word);
			$word = str_replace("½","%BD",$word);
			$word = str_replace("¿","%BF",$word);
			$word = str_replace("À","%C0",$word);
			$word = str_replace("Á","%C1",$word);
			$word = str_replace("Â","%C2",$word);
			$word = str_replace("Ã","%C3",$word);
			$word = str_replace("Ä","%C4",$word);
			$word = str_replace("Å","%C5",$word);
			$word = str_replace("Æ","%C6",$word);
			$word = str_replace("Ç","%C7",$word);
			$word = str_replace("È","%C8",$word);
			$word = str_replace("É","%C9",$word);
			$word = str_replace("Ê","%CA",$word);
			$word = str_replace("Ë","%CB",$word);
			$word = str_replace("Ì","%CC",$word);
			$word = str_replace("Í","%CD",$word);
			$word = str_replace("Î","%CE",$word);
			$word = str_replace("Ï","%CF",$word);
			$word = str_replace("Ð","%D0",$word);
			$word = str_replace("Ñ","%D1",$word);
			$word = str_replace("Ò","%D2",$word);
			$word = str_replace("Ó","%D3",$word);
			$word = str_replace("Ô","%D4",$word);
			$word = str_replace("Õ","%D5",$word);
			$word = str_replace("Ö","%D6",$word);
			$word = str_replace("Ø","%D8",$word);
			$word = str_replace("Ù","%D9",$word);
			$word = str_replace("Ú","%DA",$word);
			$word = str_replace("Û","%DB",$word);
			$word = str_replace("Ü","%DC",$word);
			$word = str_replace("Ý","%DD",$word);
			$word = str_replace("Þ","%DE",$word);
			$word = str_replace("ß","%DF",$word);
			$word = str_replace("à","%E0",$word);
			$word = str_replace("á","%E1",$word);
			$word = str_replace("â","%E2",$word);
			$word = str_replace("ã","%E3",$word);
			$word = str_replace("ä","%E4",$word);
			$word = str_replace("å","%E5",$word);
			$word = str_replace("æ","%E6",$word);
			$word = str_replace("ç","%E7",$word);
			$word = str_replace("è","%E8",$word);
			$word = str_replace("é","%E9",$word);
			$word = str_replace("ê","%EA",$word);
			$word = str_replace("ë","%EB",$word);
			$word = str_replace("ì","%EC",$word);
			$word = str_replace("í","%ED",$word);
			$word = str_replace("î","%EE",$word);
			$word = str_replace("ï","%EF",$word);
			$word = str_replace("ð","%F0",$word);
			$word = str_replace("ñ","%F1",$word);
			$word = str_replace("ò","%F2",$word);
			$word = str_replace("ó","%F3",$word);
			$word = str_replace("ô","%F4",$word);
			$word = str_replace("õ","%F5",$word);
			$word = str_replace("ö","%F6",$word);
			$word = str_replace("÷","%F7",$word);
			$word = str_replace("ø","%F8",$word);
			$word = str_replace("ù","%F9",$word);
			$word = str_replace("ú","%FA",$word);
			$word = str_replace("û","%FB",$word);
			$word = str_replace("ü","%FC",$word);
			$word = str_replace("ý","%FD",$word);
			$word = str_replace("þ","%FE",$word);
			$word = str_replace("ÿ","%FF",$word);

			return urldecode($word);
		}
// Page header
function Header()
{
    if ( $this->PageNo() === 1 ) {
    // Logo
    $this->Image('../img/logoheaderrepor.png',25,6,170);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    // Move to the right
    
    $this->Ln(25);
    // Title
    $this->Cell(100);
    $this->Cell(10,10,'CASHBOOK ENTRY RECORDS',0,0,'C');
    $this->Ln(5);
    $this->Cell(100);
    $this->Cell(10,10,'MONTHLY REPORT',0,0,'C');
    $this->Ln(7);
    // Line 
    }
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

// Page footer
function headerTable()
{
  
    // Arial italic 8
    $this->SetFont('Arial','B',10);
    // Page number
    $this->Cell(5);
    $this->Cell(22,5,'Date',1,0,'C');
    $this->Cell(78,5,'Description',1,0,'C');
    $this->Cell(30,5,'Inflows',1,0,'C');
    $this->Cell(30,5,'Outflows',1,0,'C');
    $this->Cell(30,5,'Balance',1,0,'C');
    $this->Ln();
}
function queryTable($Month, $Year,$conn){
        $this->SetFont('Arial','B',10);
       
      
        $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Month' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $this->Cell(5);
                $this->Cell(22,7,$row['cbe_date'],1,0,'L');
                $this->Cell(78,7,$row['cbe_description'],1,0,'L');
                $this->Cell(30,7,number_format($row['cbe_inflows']),1,0,'C');
                $this->Cell(30,7,number_format($row['cbe_outflows']),1,0,'C');
                $this->Cell(30,7,number_format($row['cbe_balance']),1,0,'C');
                $this->Ln();
        }
}

function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    
    
    // Line break
    $this->Ln(20);
}


function Month($MonthDetails, $Year)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(15);
    $this->Cell(0,10,'Month of '.$MonthDetails . ' ' . $Year,0,0,'C');
    // Line break
    $this->Ln(10);
}

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Legal',0);
$pdf->Month($MonthDetails[$Month], $Year);
$pdf->headerTable();
$pdf->queryTable($Month, $Year ,$conn);
$pdf->Signatory($BusinessOwner, $Position, $Sex);
$pdf->Output($fileName, 'D');


    }
    else if($WhatWillGenerate == "Download"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Excel File Monthly Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);


        $spreadsheet->getProperties()->setCreator($BusinessOwner)
        ->setLastModifiedBy($BusinessOwner)
        ->setTitle($BusinessName.'-Cashbook Entry Report-'.$MonthDetails[$Month].'-'.$Year )
        ->setSubject('Cashbook Entry Report')
        ->setDescription('Cashbook Entry Report')
        ->setKeywords('Cashbook Entry Report')
        ->setCategory('Cashbook Entry Report');
          
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();
          
        $activeSheet->setCellValue('A1', 'Date');
        $activeSheet->setCellValue('B1', 'Description');
        $activeSheet->setCellValue('C1', 'Inflows');
        $activeSheet->setCellValue('D1', 'Ouflows');
        $activeSheet->setCellValue('E1', 'Balance');

        $activeSheet->setTitle($MonthDetails[$Month]);

        foreach ($activeSheet->getColumnIterator() as $column) {
            $activeSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'center' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $activeSheet->getStyle('A1:E1')->applyFromArray($styleArray);

          
        $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Month' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        $i = 2;
        while ($row = $result->fetch_assoc()) {
                $activeSheet->setCellValue('A'.$i , $row['cbe_date']);
                $activeSheet->setCellValue('B'.$i , $row['cbe_description']);
                $activeSheet->setCellValue('C'.$i , $row['cbe_inflows']);
                $activeSheet->setCellValue('D'.$i , $row['cbe_outflows']);
                $activeSheet->setCellValue('E'.$i , $row['cbe_balance']);
                $i++;
            }
        
          
        $filename = 'Cashbook Entry Records-' . $MonthDetails[$Month].'-'.$Year.'.xlsx';

          
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='. $filename);
        header('Cache-Control: max-age=0');
        $Excel_writer->save('php://output');

    }else{
        header("Location: ../SEA/cashbook.php");
        $_SESSION ['year'] = date("Y");
        $_SESSION ['month']= date("m");
    }

}



if(isset($_POST['generate-quarterly'])){


    $WhatWillGenerate = $_POST['whatshouldIDOQ'];
    $Quarter = $_POST['quarterG'];
    $Year = $_POST['yearQ'];

    $last_date = '1999-08-30';
    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);

    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }

    
    $Months = array();

    $MonthDetails = array('',
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',);

    
    if($Quarter == "Q1"){
        $Months[0] = 1;
        $Months[1] = 2;
        $Months[2] = 3;
    }
    else if($Quarter == "Q2"){
        $Months[0] = 4;
        $Months[1] = 5;
        $Months[2] = 6;
    }
    else if($Quarter == "Q3"){
        $Months[0] = 7;
        $Months[1] = 8;
        $Months[2] = 9;
    }
    else if($Quarter == "Q4"){
        $Months[0] = 10;
        $Months[1] = 11;
        $Months[2] = 12;
    }









    if($WhatWillGenerate == "Income Statement"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Quarterly Income Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

        $sqlforupdateaccount="DELETE FROM `tblincomestatement` WHERE `is_month` = ? AND `is_year` = ?";

        $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Quarter,$Year);
                mysqli_stmt_execute($stmt);
            }


        $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE ((MONTH(cbe_date) >= '$Months[0]' AND MONTH(cbe_date) <= '$Months[2]') AND (YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();


        $IDS = array();

        $counter = 0;
        while ($row = $result->fetch_assoc()) {
        $IDS[$counter] = $row['cbe_id'];
        $counter++;
        }

        $numberNeedUpdate = count($IDS);

        $f = 0;
        for ($i=0; $i < $numberNeedUpdate ; $i++) { 
            # code...
    
            $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                    $update_description = $row['cbe_description'];
                    $update_inflows = $row['cbe_inflows'];
                    $update_outflows = $row['cbe_outflows'];
                    
                    if($f == 0){
                        //$first_date = $row['date'];
                        $f++;
                    }else{
                        $last_date = $row['cbe_date'];
                    }

            }

            if($update_description == "Beginning balance" || $update_description == "Investment" || $update_description == "Bank Financing Long Term" || $update_description == "Shareholder Investment" || $update_description == "Other source of cash" || $update_description == "Amortization Expenses" || $update_description == "Loan Payments - Long term" || $update_description == "Other uses of cash" || $update_description == "Equipment" || $update_description == "Vehicle" || $update_description == "Furniture" || $update_description == "Other non-current assets" || $update_description == "Ending Balance"){

            }else{

                if($update_description == "Sales" || $update_description == "Service Income" || $update_description == "Interest Income" || $update_description == "Bank Financing Short Term" || $update_description == "Customer Deposits" || $update_description == "Other Income"){
                    $amount = $update_inflows;
                    $category= 'INCOME';
                }
                else{
                    $amount = $update_outflows;
                    $category= 'EXPENSES';
                }
                
                $type= 'Quarterly';
    
    
                $sqlforNoAccount = "SELECT is_id, is_amount FROM tblincomestatement WHERE is_month = '$Quarter' AND is_year = '$Year' AND is_description='$update_description'";
                $sqlrun = mysqli_query($conn, $sqlforNoAccount);
        
                if(mysqli_num_rows($sqlrun)>0){
                
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();
        
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['is_id']; 
                        $prev_amount = $row['is_amount'];
                    }
    
                    $amount = $prev_amount + $amount;
    
                    $sqlforAccounts = "UPDATE tblincomestatement SET is_amount='$amount' WHERE is_id = ?";
                    
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"s",$id);
                        mysqli_stmt_execute($stmt);
                    }
    
                }else{
    
    
                    $sqlforAccounts = "INSERT INTO tblincomestatement(is_id, is_month, is_year, is_type, is_category, is_description, is_amount) VALUES ('',?,?,?,?,?,?);";
                
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"ssssss",$Quarter,$Year,$type,$category,$update_description,$amount);
                        mysqli_stmt_execute($stmt);
                    }
                }
                
            }

    
           
        }


        //$date1 = DateTime::createFromFormat('Y-m-d', $first_date);
        $date2 = DateTime::createFromFormat('Y-m-d', $last_date);

        

        $ISdetails = 'for the Quarter ended ' . $date2->format('F d, Y');

        $sqlforAccounts = "UPDATE tblincomestatement SET is_details='$ISdetails' WHERE `is_month` = ? AND `is_year` = ?";
                
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ss",$Quarter,$Year);
            mysqli_stmt_execute($stmt);
        }



                $timeStamp = date("F j Y h:i A");
                $action = "Generated Income Statement";
                $name = $_SESSION ['user_realname'];
                $apptype = $_SESSION ['user_role'];
                
                $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
            
                $stmt = mysqli_stmt_init($conn);
    
                if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                    mysqli_stmt_execute($stmt);
                }

        header("Location: ../Bookkeeping/B-Income-Statement.php");
        $_SESSION ['response'] = "Successfully Income Statement Generated";
        $_SESSION ['res_type']= "success";
        $_SESSION ['year_is'] = $Year;
        $_SESSION ['month_is']= $Quarter;
        $_SESSION ['ISdetails']= $ISdetails;



       

    }
    else if($WhatWillGenerate == "Cash Flow"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Quarterly Cash Flow Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

        $sqlforupdateaccount="DELETE FROM `tblcashflow` WHERE `cf_date_month` = ? AND `cf_date_year` = ?";

        $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Quarter,$Year);
                mysqli_stmt_execute($stmt);
            }


        $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE ((MONTH(cbe_date) >= '$Months[0]' AND MONTH(cbe_date) <= '$Months[2]') AND (YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();


        $IDS = array();

        $counter = 0;
        while ($row = $result->fetch_assoc()) {
        $IDS[$counter] = $row['cbe_id'];
        $counter++;
        }

        $numberNeedUpdate = count($IDS);

        $f = 0;
        for ($i=0; $i < $numberNeedUpdate ; $i++) { 
            # code...
    
            $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                    $update_description = $row['cbe_description'];
                    $update_inflows = $row['cbe_inflows'];
                    $update_outflows = $row['cbe_outflows'];

                    
                    if($f == 0){
                        if($update_description == "Beginning balance"){
                            $first_balance = $row['cbe_balance'];
                            
                        }else{
                            $first_balance = 0;
                        }

                        $f++;
                    }else{
                        $last_date = $row['cbe_date'];
                    }

            }


            if($update_description == "Beginning balance"){

                

            }else{

                if($update_inflows == "0"){
                    $amount = $update_outflows;
                    $sign= 'negative';
                }else{
                    $amount = $update_inflows;
                    $sign= 'positive';
                }
                
                $type = 'Quarterly';
    
    
                if($update_description == "Equipment" || $update_description == "Vehicle" || $update_description == "Furniture"){
                    $category = 'INVESTING';
    
                }else if($update_description == "Investment" || $update_description == "Other source of cash" || $update_description == "Other uses of cash" || $update_description == "Bank Financing Long Term" || $update_description == "Loan Payments - Long term"){
                    $category = 'FINANCING';
                }else{
                    $category = 'OPERATING';
                }
    
                
    
    
                $sqlforNoAccount = "SELECT cf_id, cf_amount FROM tblcashflow WHERE cf_date_month = '$Quarter' AND cf_date_year = '$Year' AND cf_description='$update_description'";
                $sqlrun = mysqli_query($conn, $sqlforNoAccount);
        
                if(mysqli_num_rows($sqlrun)>0){
                
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();
        
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['cf_id']; 
                        $prev_amount = $row['cf_amount'];
                    }
    
                    $amount = $prev_amount + $amount;
    
                    $sqlforAccounts = "UPDATE tblcashflow SET cf_amount='$amount' WHERE cf_id = ?";
                    
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"s",$id);
                        mysqli_stmt_execute($stmt);
                    }
    
                }else{
    
    
                    $sqlforAccounts = "INSERT INTO tblcashflow(cf_id, cf_date_month, cf_date_year, cf_type, cf_category, cf_description, cf_first_balance, cf_amount, cf_sign, cf_details) VALUES ('',?,?,?,?,?,'',?,?,'');";
                
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"sssssss",$Quarter,$Year,$type,$category,$update_description,$amount,$sign);
                        mysqli_stmt_execute($stmt);
                    }
                }
                
            }

    
            
        }


       // $date1 = DateTime::createFromFormat('Y-m-d', $first_date);
        $date2 = DateTime::createFromFormat('Y-m-d', $last_date);

        

        $ISdetails = 'for the Quarter ended ' . $date2->format('F d, Y');

        $sqlforAccounts = "UPDATE tblcashflow SET cf_details='$ISdetails', cf_first_balance='$first_balance' WHERE `cf_date_month` = ? AND `cf_date_year` = ?";
                
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ss",$Quarter,$Year);
            mysqli_stmt_execute($stmt);
        }



                    $timeStamp = date("F j Y h:i A");
                    $action = "Generated Cash Flow Statement";
                    $name = $_SESSION ['user_realname'];
                    $apptype = $_SESSION ['user_role'];
                    
                    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
                
                    $stmt = mysqli_stmt_init($conn);
        
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                        mysqli_stmt_execute($stmt);
                    }
    
    
    
            header("Location: ../Bookkeeping/B-Cash-flow.php");
            $_SESSION ['response'] = "Successfully Cash Flow Statement Generated";
            $_SESSION ['res_type']= "success";
            $_SESSION ['year_is'] = $Year;
            $_SESSION ['month_is']= $Quarter;
            $_SESSION ['ISdetails']= $ISdetails;


        

    }
    else if($WhatWillGenerate == "Print"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Printable Quarterly Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }
        
        $fileName = 'Cashbook Entry Records-' . $MonthDetails[$Months[0]].'-to-'.$MonthDetails[$Months[2]].'-'.$Year.'.pdf';

        
        
class PDF extends FPDF
{

    function __construct()
		{
			parent::__construct();
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
			parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
		}

		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
			parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
		}

		function Write($h, $txt, $link='')
		{
			parent::Write($h, $this->normalize($txt), $link);
		}

		function Text($x, $y, $txt)
		{
			parent::Text($x, $y, $this->normalize($txt));
		}

		protected function normalize($word)
		{
			// Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
			
			$word = str_replace("@","%40",$word);
			$word = str_replace("`","%60",$word);
			$word = str_replace("¢","%A2",$word);
			$word = str_replace("£","%A3",$word);
			$word = str_replace("¥","%A5",$word);
			$word = str_replace("|","%A6",$word);
			$word = str_replace("«","%AB",$word);
			$word = str_replace("¬","%AC",$word);
			$word = str_replace("¯","%AD",$word);
			$word = str_replace("º","%B0",$word);
			$word = str_replace("±","%B1",$word);
			$word = str_replace("ª","%B2",$word);
			$word = str_replace("µ","%B5",$word);
			$word = str_replace("»","%BB",$word);
			$word = str_replace("¼","%BC",$word);
			$word = str_replace("½","%BD",$word);
			$word = str_replace("¿","%BF",$word);
			$word = str_replace("À","%C0",$word);
			$word = str_replace("Á","%C1",$word);
			$word = str_replace("Â","%C2",$word);
			$word = str_replace("Ã","%C3",$word);
			$word = str_replace("Ä","%C4",$word);
			$word = str_replace("Å","%C5",$word);
			$word = str_replace("Æ","%C6",$word);
			$word = str_replace("Ç","%C7",$word);
			$word = str_replace("È","%C8",$word);
			$word = str_replace("É","%C9",$word);
			$word = str_replace("Ê","%CA",$word);
			$word = str_replace("Ë","%CB",$word);
			$word = str_replace("Ì","%CC",$word);
			$word = str_replace("Í","%CD",$word);
			$word = str_replace("Î","%CE",$word);
			$word = str_replace("Ï","%CF",$word);
			$word = str_replace("Ð","%D0",$word);
			$word = str_replace("Ñ","%D1",$word);
			$word = str_replace("Ò","%D2",$word);
			$word = str_replace("Ó","%D3",$word);
			$word = str_replace("Ô","%D4",$word);
			$word = str_replace("Õ","%D5",$word);
			$word = str_replace("Ö","%D6",$word);
			$word = str_replace("Ø","%D8",$word);
			$word = str_replace("Ù","%D9",$word);
			$word = str_replace("Ú","%DA",$word);
			$word = str_replace("Û","%DB",$word);
			$word = str_replace("Ü","%DC",$word);
			$word = str_replace("Ý","%DD",$word);
			$word = str_replace("Þ","%DE",$word);
			$word = str_replace("ß","%DF",$word);
			$word = str_replace("à","%E0",$word);
			$word = str_replace("á","%E1",$word);
			$word = str_replace("â","%E2",$word);
			$word = str_replace("ã","%E3",$word);
			$word = str_replace("ä","%E4",$word);
			$word = str_replace("å","%E5",$word);
			$word = str_replace("æ","%E6",$word);
			$word = str_replace("ç","%E7",$word);
			$word = str_replace("è","%E8",$word);
			$word = str_replace("é","%E9",$word);
			$word = str_replace("ê","%EA",$word);
			$word = str_replace("ë","%EB",$word);
			$word = str_replace("ì","%EC",$word);
			$word = str_replace("í","%ED",$word);
			$word = str_replace("î","%EE",$word);
			$word = str_replace("ï","%EF",$word);
			$word = str_replace("ð","%F0",$word);
			$word = str_replace("ñ","%F1",$word);
			$word = str_replace("ò","%F2",$word);
			$word = str_replace("ó","%F3",$word);
			$word = str_replace("ô","%F4",$word);
			$word = str_replace("õ","%F5",$word);
			$word = str_replace("ö","%F6",$word);
			$word = str_replace("÷","%F7",$word);
			$word = str_replace("ø","%F8",$word);
			$word = str_replace("ù","%F9",$word);
			$word = str_replace("ú","%FA",$word);
			$word = str_replace("û","%FB",$word);
			$word = str_replace("ü","%FC",$word);
			$word = str_replace("ý","%FD",$word);
			$word = str_replace("þ","%FE",$word);
			$word = str_replace("ÿ","%FF",$word);

			return urldecode($word);
		}

// Page header
function Header()
{
    if ( $this->PageNo() === 1 ) {
    // Logo
    $this->Image('../img/logoheaderrepor.png',25,6,170);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    // Move to the right
    
    $this->Ln(25);
    // Title
    $this->Cell(100);
    $this->Cell(10,10,'CASHBOOK ENTRY RECORDS',0,0,'C');
    $this->Ln(5);
    $this->Cell(100);
    $this->Cell(10,10,'QUARTERLY REPORT',0,0,'C');
    $this->Ln(7);
    // Line break
    }
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

// Page footer
function headerTable()
{
  
    // Arial italic 8
    $this->SetFont('Arial','B',10);
    // Page number
    $this->Cell(5);
    $this->Cell(22,5,'Date',1,0,'C');
    $this->Cell(78,5,'Description',1,0,'C');
    $this->Cell(30,5,'Inflows',1,0,'C');
    $this->Cell(30,5,'Outflows',1,0,'C');
    $this->Cell(30,5,'Balance',1,0,'C');
    $this->Ln();
}
function queryTable($Month, $Year ,$conn){
        $this->SetFont('Arial','B',10);
       
      
        $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Month' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $this->Cell(5);
                $this->Cell(22,7,$row['cbe_date'],1,0,'L');
                $this->Cell(78,7,$row['cbe_description'],1,0,'L');
                $this->Cell(30,7,number_format($row['cbe_inflows']),1,0,'C');
                $this->Cell(30,7,number_format($row['cbe_outflows']),1,0,'C');
                $this->Cell(30,7,number_format($row['cbe_balance']),1,0,'C');
                $this->Ln();
        }
}

function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    
    
    // Line break
    $this->Ln(20);
}


function Month($MonthDetails, $Year)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(15);
    $this->Cell(0,10,'Month of '.$MonthDetails . ' ' . $Year,0,0,'C');
    // Line break
    $this->Ln(10);
}

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Legal',0);

for ($i=0; $i <=2 ; $i++) { 
    # code...
    $pdf->Month($MonthDetails[$Months[$i]], $Year);
    $pdf->headerTable();
    $pdf->queryTable($Months[$i], $Year,$conn);
}

$pdf->Signatory($BusinessOwner, $Position, $Sex);
$pdf->Output($fileName, 'D');
      
        


    }
    else if($WhatWillGenerate == "Download"){

        $timeStamp = date("F j Y h:i A");
        $action = "Generated Excel File Quarterly Cashbook Entry.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        
        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);


        $spreadsheet->getProperties()->setCreator($BusinessOwner)
        ->setLastModifiedBy($BusinessOwner)
        ->setTitle($BusinessName.'-Cashbook Entry Report-'.$MonthDetails[$Months[0]].'-to-'.$MonthDetails[$Months[2]].'-'.$Year )
        ->setSubject('Cashbook Entry Report')
        ->setDescription('Cashbook Entry Report')
        ->setKeywords('Cashbook Entry Report')
        ->setCategory('Cashbook Entry Report');

        for ($r=0; $r <= 2 ; $r++) { 
            # code...

        $spreadsheet->setActiveSheetIndex($r);
        $activeSheet = $spreadsheet->getActiveSheet();
          
        $activeSheet->setCellValue('A1', 'Date');
        $activeSheet->setCellValue('B1', 'Description');
        $activeSheet->setCellValue('C1', 'Inflows');
        $activeSheet->setCellValue('D1', 'Ouflows');
        $activeSheet->setCellValue('E1', 'Balance');

        $activeSheet->setTitle($MonthDetails[$Months[$r]]);

        foreach ($activeSheet->getColumnIterator() as $column) {
            $activeSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'center' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $activeSheet->getStyle('A1:E1')->applyFromArray($styleArray);

          
        $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Months[$r]' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        $i = 2;
        while ($row = $result->fetch_assoc()) {
                $activeSheet->setCellValue('A'.$i , $row['cbe_date']);
                $activeSheet->setCellValue('B'.$i , $row['cbe_description']);
                $activeSheet->setCellValue('C'.$i , $row['cbe_inflows']);
                $activeSheet->setCellValue('D'.$i , $row['cbe_outflows']);
                $activeSheet->setCellValue('E'.$i , $row['cbe_balance']);
                $i++;
            }
        


            $spreadsheet->createSheet();
        }
          
        $spreadsheet->setActiveSheetIndex(0);
          
        $filename = $BusinessName .'-Cashbook Entry Records-' . $MonthDetails[$Months[0]].'-to-'.$MonthDetails[$Months[2]].'-'.$Year.'.xlsx';

          
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='. $filename);
        header('Cache-Control: max-age=0');
        $Excel_writer->save('php://output');


       

    }else{
        
    }




}


if(isset($_POST['generate-yearly'])){

    
   
    $WhatWillGenerate = $_POST['whatshouldIDOY'];
    $Year = $_POST['yearY'];
    $last_date = '1999-08-30';
    //$BusinessName = mysqli_real_escape_string($conn,   $_SESSION ['business_name']);
    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);

    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }

    $Months = array('',
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '7 ',
    '8',
    '9',
    '10',
    '11',
    '12',);

    $MonthDetails = array('',
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',);




    


    if($WhatWillGenerate == "Income Statement"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Yearly Income Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

        $sqlforupdateaccount="DELETE FROM `tblincomestatement` WHERE `is_month` = ? AND `is_year` = ?";

        $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Year,$Year);
                mysqli_stmt_execute($stmt);
            }


        $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE ((MONTH(cbe_date) >= '$Months[1]' AND MONTH(cbe_date) <= '$Months[12]') AND (YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();


        $IDS = array();

        $counter = 0;
        while ($row = $result->fetch_assoc()) {
        $IDS[$counter] = $row['cbe_id'];
        $counter++;
        }

        $numberNeedUpdate = count($IDS);

        $f = 0;
        for ($i=0; $i < $numberNeedUpdate ; $i++) { 
            # code...
    
            $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                    $update_description = $row['cbe_description'];
                    $update_inflows = $row['cbe_inflows'];
                    $update_outflows = $row['cbe_outflows'];
                    
                    if($f == 0){
                        //$first_date = $row['date'];
                        $f++;
                    }else{
                        $last_date = $row['cbe_date'];
                    }

            }

            if($update_description == "Beginning balance" || $update_description == "Investment" || $update_description == "Bank Financing Long Term" || $update_description == "Shareholder Investment" || $update_description == "Other source of cash" || $update_description == "Amortization Expenses" || $update_description == "Loan Payments - Long term" || $update_description == "Other uses of cash" || $update_description == "Equipment" || $update_description == "Vehicle" || $update_description == "Furniture" || $update_description == "Other non-current assets" || $update_description == "Ending Balance"){

            }else{

                if($update_description == "Sales" || $update_description == "Service Income" || $update_description == "Interest Income" || $update_description == "Bank Financing Short Term" || $update_description == "Customer Deposits" || $update_description == "Other Income"){
                    $amount = $update_inflows;
                    $category= 'INCOME';
                }
                else{
                    $amount = $update_outflows;
                    $category= 'EXPENSES';
                }
                
                $type= 'Yearly';
    
    
                $sqlforNoAccount = "SELECT is_id, is_amount FROM tblincomestatement WHERE is_month = '$Year' AND is_year = '$Year' AND is_description='$update_description'";
                $sqlrun = mysqli_query($conn, $sqlforNoAccount);
        
                if(mysqli_num_rows($sqlrun)>0){
                
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();
        
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['is_id']; 
                        $prev_amount = $row['is_amount'];
                    }
    
                    $amount = $prev_amount + $amount;
    
                    $sqlforAccounts = "UPDATE tblincomestatement SET is_amount='$amount' WHERE is_id = ?";
                    
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"s",$id);
                        mysqli_stmt_execute($stmt);
                    }
    
                }else{
    
    
                    $sqlforAccounts = "INSERT INTO tblincomestatement(is_id, is_month, is_year, is_type, is_category, is_description, is_amount) VALUES ('',?,?,?,?,?,?);";
                
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"ssssss",$Year,$Year,$type,$category,$update_description,$amount);
                        mysqli_stmt_execute($stmt);
                    }
                }
                
            }
        }


       // $date1 = DateTime::createFromFormat('Y-m-d', $first_date);
        $date2 = DateTime::createFromFormat('Y-m-d', $last_date);

        

        $ISdetails = 'for the Year ended ' . $date2->format('F d, Y');

        $sqlforAccounts = "UPDATE tblincomestatement SET is_details='$ISdetails' WHERE `is_month` = ? AND `is_year` = ?";
                
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ss",$Year,$Year);
            mysqli_stmt_execute($stmt);
        }




        $timeStamp = date("F j Y h:i A");
        $action = "Generated Income Statement";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

header("Location: ../Bookkeeping/B-Income-Statement.php");
$_SESSION ['response'] = "Successfully Income Statement Generated";
$_SESSION ['res_type']= "success";
$_SESSION ['year_is'] = $Year;
$_SESSION ['month_is']= $Year;
$_SESSION ['ISdetails']= $ISdetails;



       

    

      

    }
    else if($WhatWillGenerate == "Cash Flow"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Yearly Cash Flow Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

        $sqlforupdateaccount="DELETE FROM `tblcashflow` WHERE `cf_date_month` = ? AND `cf_date_year` = ?";

        $stmt = mysqli_stmt_init($conn);
    
            
            if(!mysqli_stmt_prepare($stmt, $sqlforupdateaccount)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ss",$Year,$Year);
                mysqli_stmt_execute($stmt);
            }


        $sqlforNoAccount = "SELECT cbe_id FROM tblcashbookentry WHERE ((MONTH(cbe_date) >= '$Months[1]' AND MONTH(cbe_date) <= '$Months[12]') AND (YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();


        $IDS = array();

        $counter = 0;
        while ($row = $result->fetch_assoc()) {
        $IDS[$counter] = $row['cbe_id'];
        $counter++;
        }

        $numberNeedUpdate = count($IDS);

        $f = 0;
        for ($i=0; $i < $numberNeedUpdate ; $i++) { 
            # code...
    
            $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE cbe_id = '$IDS[$i]'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                    $update_description = $row['cbe_description'];
                    $update_inflows = $row['cbe_inflows'];
                    $update_outflows = $row['cbe_outflows'];
                    
                    if($f == 0){
                        if($update_description == "Beginning balance"){
                            $first_balance = $row['cbe_balance'];
                            
                        }else{
                            $first_balance = 0;
                        }

                        $f++;
                    }else{
                        $last_date = $row['cbe_date'];
                    }

            }

            if($update_description == "Beginning balance"){

            }else{
                if($update_inflows == "0"){
                    $amount = $update_outflows;
                    $sign= 'negative';
                }else{
                    $amount = $update_inflows;
                    $sign= 'positive';
                }
                
                $type = 'Yearly';
    
    
                if($update_description == "Equipment" || $update_description == "Vehicle" || $update_description == "Furniture"){
                    $category = 'INVESTING';
    
                }else if($update_description == "Investment" || $update_description == "Other source of cash" || $update_description == "Other uses of cash" || $update_description == "Bank Financing Long Term" || $update_description == "Loan Payments - Long term"){
                    $category = 'FINANCING';
                }else{
                    $category = 'OPERATING';
                }
                
    
    
                $sqlforNoAccount = "SELECT cf_id, cf_amount FROM tblcashflow WHERE cf_date_month = '$Year' AND cf_date_year = '$Year' AND cf_description='$update_description'";
                $sqlrun = mysqli_query($conn, $sqlforNoAccount);
        
                if(mysqli_num_rows($sqlrun)>0){
                
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();
        
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['cf_id']; 
                        $prev_amount = $row['cf_amount'];
                    }
    
                    $amount = $prev_amount + $amount;
    
                    $sqlforAccounts = "UPDATE tblcashflow SET cf_amount='$amount' WHERE cf_id = ?";
                    
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"s",$id);
                        mysqli_stmt_execute($stmt);
                    }
    
                }else{
    
    
                    $sqlforAccounts = "INSERT INTO tblcashflow(cf_id, cf_date_month, cf_date_year, cf_type, cf_category, cf_description,cf_first_balance, cf_amount, cf_sign, cf_details) VALUES ('',?,?,?,?,?,'',?,?,'');";
                
                    $stmt = mysqli_stmt_init($conn);
            
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"sssssss",$Year,$Year,$type,$category,$update_description,$amount,$sign);
                        mysqli_stmt_execute($stmt);
                    }
                }
            }
        }


        //$date1 = DateTime::createFromFormat('Y-m-d', $first_date);
        $date2 = DateTime::createFromFormat('Y-m-d', $last_date);

        

        $ISdetails = 'for the Year ended ' . $date2->format('F d, Y');

        $sqlforAccounts = "UPDATE tblcashflow SET cf_details='$ISdetails', cf_first_balance='$first_balance' WHERE `cf_date_month` = ? AND `cf_date_year` = ?";
                
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ss",$Year,$Year);
            mysqli_stmt_execute($stmt);
        }




        $timeStamp = date("F j Y h:i A");
                    $action = "Generated Cash Flow Statement";
                    $name = $_SESSION ['user_realname'];
                    $apptype = $_SESSION ['user_role'];
                    
                    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
                
                    $stmt = mysqli_stmt_init($conn);
        
                    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                        echo "SQL Error";
                    }else{
                        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                        mysqli_stmt_execute($stmt);
                    }
    
    
    
            header("Location: ../Bookkeeping/B-Cash-flow.php");
            $_SESSION ['response'] = "Successfully Cash Flow Statement Generated";
            $_SESSION ['res_type']= "success";
            $_SESSION ['year_is'] = $Year;
            $_SESSION ['month_is']= $Year;
            $_SESSION ['ISdetails']= $ISdetails;

    }
    else if($WhatWillGenerate == "Print"){

        $timeStamp = date("F j Y h:i A");
        $action = "Generated Printable Yearly Cashbook Entry.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        $fileName = 'Cashbook Entry Records Year-of-'.$Year.'.pdf';


                
class PDF extends FPDF
{

    function __construct()
    {
        parent::__construct();
    }

    function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
    {
        parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
    }

    function Write($h, $txt, $link='')
    {
        parent::Write($h, $this->normalize($txt), $link);
    }

    function Text($x, $y, $txt)
    {
        parent::Text($x, $y, $this->normalize($txt));
    }

    protected function normalize($word)
    {
        // Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
        
        $word = str_replace("@","%40",$word);
        $word = str_replace("`","%60",$word);
        $word = str_replace("¢","%A2",$word);
        $word = str_replace("£","%A3",$word);
        $word = str_replace("¥","%A5",$word);
        $word = str_replace("|","%A6",$word);
        $word = str_replace("«","%AB",$word);
        $word = str_replace("¬","%AC",$word);
        $word = str_replace("¯","%AD",$word);
        $word = str_replace("º","%B0",$word);
        $word = str_replace("±","%B1",$word);
        $word = str_replace("ª","%B2",$word);
        $word = str_replace("µ","%B5",$word);
        $word = str_replace("»","%BB",$word);
        $word = str_replace("¼","%BC",$word);
        $word = str_replace("½","%BD",$word);
        $word = str_replace("¿","%BF",$word);
        $word = str_replace("À","%C0",$word);
        $word = str_replace("Á","%C1",$word);
        $word = str_replace("Â","%C2",$word);
        $word = str_replace("Ã","%C3",$word);
        $word = str_replace("Ä","%C4",$word);
        $word = str_replace("Å","%C5",$word);
        $word = str_replace("Æ","%C6",$word);
        $word = str_replace("Ç","%C7",$word);
        $word = str_replace("È","%C8",$word);
        $word = str_replace("É","%C9",$word);
        $word = str_replace("Ê","%CA",$word);
        $word = str_replace("Ë","%CB",$word);
        $word = str_replace("Ì","%CC",$word);
        $word = str_replace("Í","%CD",$word);
        $word = str_replace("Î","%CE",$word);
        $word = str_replace("Ï","%CF",$word);
        $word = str_replace("Ð","%D0",$word);
        $word = str_replace("Ñ","%D1",$word);
        $word = str_replace("Ò","%D2",$word);
        $word = str_replace("Ó","%D3",$word);
        $word = str_replace("Ô","%D4",$word);
        $word = str_replace("Õ","%D5",$word);
        $word = str_replace("Ö","%D6",$word);
        $word = str_replace("Ø","%D8",$word);
        $word = str_replace("Ù","%D9",$word);
        $word = str_replace("Ú","%DA",$word);
        $word = str_replace("Û","%DB",$word);
        $word = str_replace("Ü","%DC",$word);
        $word = str_replace("Ý","%DD",$word);
        $word = str_replace("Þ","%DE",$word);
        $word = str_replace("ß","%DF",$word);
        $word = str_replace("à","%E0",$word);
        $word = str_replace("á","%E1",$word);
        $word = str_replace("â","%E2",$word);
        $word = str_replace("ã","%E3",$word);
        $word = str_replace("ä","%E4",$word);
        $word = str_replace("å","%E5",$word);
        $word = str_replace("æ","%E6",$word);
        $word = str_replace("ç","%E7",$word);
        $word = str_replace("è","%E8",$word);
        $word = str_replace("é","%E9",$word);
        $word = str_replace("ê","%EA",$word);
        $word = str_replace("ë","%EB",$word);
        $word = str_replace("ì","%EC",$word);
        $word = str_replace("í","%ED",$word);
        $word = str_replace("î","%EE",$word);
        $word = str_replace("ï","%EF",$word);
        $word = str_replace("ð","%F0",$word);
        $word = str_replace("ñ","%F1",$word);
        $word = str_replace("ò","%F2",$word);
        $word = str_replace("ó","%F3",$word);
        $word = str_replace("ô","%F4",$word);
        $word = str_replace("õ","%F5",$word);
        $word = str_replace("ö","%F6",$word);
        $word = str_replace("÷","%F7",$word);
        $word = str_replace("ø","%F8",$word);
        $word = str_replace("ù","%F9",$word);
        $word = str_replace("ú","%FA",$word);
        $word = str_replace("û","%FB",$word);
        $word = str_replace("ü","%FC",$word);
        $word = str_replace("ý","%FD",$word);
        $word = str_replace("þ","%FE",$word);
        $word = str_replace("ÿ","%FF",$word);

        return urldecode($word);
    }


// Page header
function Header()
{
    if ( $this->PageNo() === 1 ) {
    // Logo
    $this->Image('../img/logoheaderrepor.png',25,6,170);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    // Move to the right
    
    $this->Ln(25);
    // Title
    $this->Cell(100);
    $this->Cell(10,10,'CASHBOOK ENTRY RECORDS',0,0,'C');
    $this->Ln(5);
    $this->Cell(100);
    $this->Cell(10,10,'YEARLY REPORT',0,0,'C');
    $this->Ln(7);

    }
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

// Page footer
function headerTable()
{
  
    // Arial italic 8
    $this->SetFont('Arial','B',10);
    // Page number
    $this->Cell(5);
    $this->Cell(22,5,'Date',1,0,'C');
    $this->Cell(78,5,'Description',1,0,'C');
    $this->Cell(30,5,'Inflows',1,0,'C');
    $this->Cell(30,5,'Outflows',1,0,'C');
    $this->Cell(30,5,'Balance',1,0,'C');
    $this->Ln();
}
function queryTable($Month, $Year,$conn){
        $this->SetFont('Arial','B',10);
       
      
        $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Month' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $this->Cell(5);
                $this->Cell(22,7,$row['cbe_date'],1,0,'L');
                $this->Cell(78,7,$row['cbe_description'],1,0,'L');
                $this->Cell(30,7,number_format($row['cbe_inflows']),1,0,'C');
                $this->Cell(30,7,number_format($row['cbe_outflows']),1,0,'C');
                $this->Cell(30,7,number_format($row['cbe_balance']),1,0,'C');
                $this->Ln();
        }
}

function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    
    
    // Line break
    $this->Ln(20);
}


function Month($MonthDetails, $Year)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(15);
    $this->Cell(0,10,'Month of '.$MonthDetails . ' ' . $Year,0,0,'C');
    // Line break
    $this->Ln(10);
}

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Legal',0);

for ($i=1; $i <=12 ; $i++) { 
    # code...
    $pdf->Month($MonthDetails[$i], $Year);
    $pdf->headerTable();
    $pdf->queryTable($Months[$i], $Year, $conn);
}

$pdf->Signatory($BusinessOwner, $Position, $Sex);
$pdf->Output($fileName, 'D');
      
        



     


    }
    else if($WhatWillGenerate == "Download"){

        $timeStamp = date("F j Y h:i A");
            $action = "Generated Excel File Yearly Cashbook Entry.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


        
        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);


        $spreadsheet->getProperties()->setCreator($BusinessOwner)
        ->setLastModifiedBy($BusinessOwner)
        ->setTitle($BusinessName.'-Cashbook Entry Report Year-of-'.$Year )
        ->setSubject('Cashbook Entry Report')
        ->setDescription('Cashbook Entry Report')
        ->setKeywords('Cashbook Entry Report')
        ->setCategory('Cashbook Entry Report');

        for ($r=0; $r <= 11 ; $r++) { 
            # code...
        $m = $r + 1;
        $spreadsheet->setActiveSheetIndex($r);
        $activeSheet = $spreadsheet->getActiveSheet();
          
        $activeSheet->setCellValue('A1', 'Date');
        $activeSheet->setCellValue('B1', 'Description');
        $activeSheet->setCellValue('C1', 'Inflows');
        $activeSheet->setCellValue('D1', 'Ouflows');
        $activeSheet->setCellValue('E1', 'Balance');

        $activeSheet->setTitle($MonthDetails[$m]);

        foreach ($activeSheet->getColumnIterator() as $column) {
            $activeSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'center' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $activeSheet->getStyle('A1:E1')->applyFromArray($styleArray);

          
        $sqlforNoAccount = "SELECT cbe_date, cbe_description, cbe_inflows, cbe_outflows, cbe_balance FROM tblcashbookentry WHERE ((MONTH(cbe_date) = '$Months[$m]' AND YEAR(cbe_date)= '$Year')) Order By cbe_date, cbe_order_by ASC";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        $i = 2;
        while ($row = $result->fetch_assoc()) {
                $activeSheet->setCellValue('A'.$i , $row['cbe_date']);
                $activeSheet->setCellValue('B'.$i , $row['cbe_description']);
                $activeSheet->setCellValue('C'.$i , $row['cbe_inflows']);
                $activeSheet->setCellValue('D'.$i , $row['cbe_outflows']);
                $activeSheet->setCellValue('E'.$i , $row['cbe_balance']);
                $i++;
            }
        


            $spreadsheet->createSheet();
        }
          
        $spreadsheet->setActiveSheetIndex(0);
          
        $filename = 'Cashbook Entry Records Year-of-'.$Year.'.xlsx';

          
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='. $filename);
        header('Cache-Control: max-age=0');
        $Excel_writer->save('php://output');


      

    }else{
        header("Location: ../SEA/cashbook.php");
        $_SESSION ['year'] = date("Y");
        $_SESSION ['month']= date("m");
    }




}


if(isset($_POST['print_IS'])){

    $timeStamp = date("F j Y h:i A");
            $action = "Generated Printable Income Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);
    $Yearly =   $_SESSION ['year_is'];
    $Monthly = $_SESSION ['month_is'];
    $Detailsly = $_SESSION ['ISdetails'];

    $MonthDetails = array('',
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',);

    
    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }


    if($Monthly == "Q1" || $Monthly == "Q2" || $Monthly == "Q3" || $Monthly == "Q4"){

        $fileName = $BusinessName .'-Income Statement '.$Monthly.'-of-'.$Yearly.'.pdf';

    }else if(strlen($Monthly) == 4){
        $fileName = $BusinessName .'-Income Statement Year-of-'.$Yearly.'.pdf';
    }else{
        $fileName = $BusinessName .'-Income Statement '. $MonthDetails[$Monthly] .$Yearly.'.pdf';
    }


                
    class PDF extends FPDF
    {

        function __construct()
		{
			parent::__construct();
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
			parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
		}

		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
			parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
		}

		function Write($h, $txt, $link='')
		{
			parent::Write($h, $this->normalize($txt), $link);
		}

		function Text($x, $y, $txt)
		{
			parent::Text($x, $y, $this->normalize($txt));
		}

		protected function normalize($word)
		{
			// Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
			
			$word = str_replace("@","%40",$word);
			$word = str_replace("`","%60",$word);
			$word = str_replace("¢","%A2",$word);
			$word = str_replace("£","%A3",$word);
			$word = str_replace("¥","%A5",$word);
			$word = str_replace("|","%A6",$word);
			$word = str_replace("«","%AB",$word);
			$word = str_replace("¬","%AC",$word);
			$word = str_replace("¯","%AD",$word);
			$word = str_replace("º","%B0",$word);
			$word = str_replace("±","%B1",$word);
			$word = str_replace("ª","%B2",$word);
			$word = str_replace("µ","%B5",$word);
			$word = str_replace("»","%BB",$word);
			$word = str_replace("¼","%BC",$word);
			$word = str_replace("½","%BD",$word);
			$word = str_replace("¿","%BF",$word);
			$word = str_replace("À","%C0",$word);
			$word = str_replace("Á","%C1",$word);
			$word = str_replace("Â","%C2",$word);
			$word = str_replace("Ã","%C3",$word);
			$word = str_replace("Ä","%C4",$word);
			$word = str_replace("Å","%C5",$word);
			$word = str_replace("Æ","%C6",$word);
			$word = str_replace("Ç","%C7",$word);
			$word = str_replace("È","%C8",$word);
			$word = str_replace("É","%C9",$word);
			$word = str_replace("Ê","%CA",$word);
			$word = str_replace("Ë","%CB",$word);
			$word = str_replace("Ì","%CC",$word);
			$word = str_replace("Í","%CD",$word);
			$word = str_replace("Î","%CE",$word);
			$word = str_replace("Ï","%CF",$word);
			$word = str_replace("Ð","%D0",$word);
			$word = str_replace("Ñ","%D1",$word);
			$word = str_replace("Ò","%D2",$word);
			$word = str_replace("Ó","%D3",$word);
			$word = str_replace("Ô","%D4",$word);
			$word = str_replace("Õ","%D5",$word);
			$word = str_replace("Ö","%D6",$word);
			$word = str_replace("Ø","%D8",$word);
			$word = str_replace("Ù","%D9",$word);
			$word = str_replace("Ú","%DA",$word);
			$word = str_replace("Û","%DB",$word);
			$word = str_replace("Ü","%DC",$word);
			$word = str_replace("Ý","%DD",$word);
			$word = str_replace("Þ","%DE",$word);
			$word = str_replace("ß","%DF",$word);
			$word = str_replace("à","%E0",$word);
			$word = str_replace("á","%E1",$word);
			$word = str_replace("â","%E2",$word);
			$word = str_replace("ã","%E3",$word);
			$word = str_replace("ä","%E4",$word);
			$word = str_replace("å","%E5",$word);
			$word = str_replace("æ","%E6",$word);
			$word = str_replace("ç","%E7",$word);
			$word = str_replace("è","%E8",$word);
			$word = str_replace("é","%E9",$word);
			$word = str_replace("ê","%EA",$word);
			$word = str_replace("ë","%EB",$word);
			$word = str_replace("ì","%EC",$word);
			$word = str_replace("í","%ED",$word);
			$word = str_replace("î","%EE",$word);
			$word = str_replace("ï","%EF",$word);
			$word = str_replace("ð","%F0",$word);
			$word = str_replace("ñ","%F1",$word);
			$word = str_replace("ò","%F2",$word);
			$word = str_replace("ó","%F3",$word);
			$word = str_replace("ô","%F4",$word);
			$word = str_replace("õ","%F5",$word);
			$word = str_replace("ö","%F6",$word);
			$word = str_replace("÷","%F7",$word);
			$word = str_replace("ø","%F8",$word);
			$word = str_replace("ù","%F9",$word);
			$word = str_replace("ú","%FA",$word);
			$word = str_replace("û","%FB",$word);
			$word = str_replace("ü","%FC",$word);
			$word = str_replace("ý","%FD",$word);
			$word = str_replace("þ","%FE",$word);
			$word = str_replace("ÿ","%FF",$word);

			return urldecode($word);
		}
        
    // Page header
    function Header()
    {

        if ( $this->PageNo() === 1 ) {
                    // Logo
         $this->Image('../img/logoheaderrepor.png',25,6,170);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        
        $this->Ln(25);
        // Title
        $this->Cell(100);
        $this->Cell(10,10,'INCOME STATEMENT',0,0,'C');
        }

    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    // Page footer
    function headerTable()
    {
        
        
      
        // Arial italic 8
        $this->SetFont('Arial','B',10);
        // Page number
        $this->Cell(5);
        $this->Cell(95,5,'DESCRIPTION',1,0,'C');
        $this->Cell(95,5,'AMOUNT',1,0,'C');
        $this->Ln();
    }
    function queryTableIncome($month, $year, $conn){
            $this->SetFont('Arial','B',10);
           
          
            $sqlforNoAccount = "SELECT `is_description`, `is_amount` FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year' AND `is_category` = 'INCOME'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                    $this->Cell(5);
                    $this->Cell(95,7,$row['is_description'],1,0,'L');
                    $this->Cell(95,7,number_format($row['is_amount']),1,0,'R');
                    $this->Ln();
            }

            $sqlforNoAccount = "SELECT  SUM(is_amount) AS total_amount FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year' AND `is_category` = 'INCOME'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                    $this->Cell(5);
                    $this->Cell(95,7,'TOTAL INCOME',1,0,'L');
                    $this->Cell(95,7,number_format($row['total_amount']),1,0,'R');
                    $this->Ln();
            }


    }

    function queryTableExpenses($month, $year, $conn){

        $this->SetFont('Arial','B',10);
       
      
        $sqlforNoAccount = "SELECT `is_description`, `is_amount` FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year' AND `is_category` = 'EXPENSES'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $this->Cell(5);
                $this->Cell(95,7,$row['is_description'],1,0,'L');
                $this->Cell(95,7,number_format($row['is_amount']),1,0,'R');
                $this->Ln();
        }

        $sqlforNoAccount = "SELECT  SUM(is_amount) AS total_amount FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year' AND `is_category` = 'EXPENSES'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $totalexpenses = $row['total_amount'];
              
        }

        $sqlforNoAccount = "SELECT  SUM(is_amount) AS total_amount FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year' AND `is_category` = 'INCOME'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
               $totalincome = $row['total_amount'];
        }

        $sum = $totalincome - $totalexpenses;

                    if($sum < 0){
                      $text = "NET LOSS";
                    }else{
                        $text = "NET PROFIT";
                    }
        

                    $this->Cell(5);
                    $this->Cell(95,7,"TOTAL EXPENSES",1,0,'L');
                    $this->Cell(95,7,number_format($totalexpenses),1,0,'R');
                    $this->Ln();

                    $this->Cell(5);
                    $this->Cell(95,7,$text,1,0,'L');
                    $this->Cell(95,7,number_format($sum),1,0,'R');
                    $this->Ln();


}
    
function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    
    
    // Line break
    $this->Ln(20);
}

function subhead1($ISdetails)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, $ISdetails ,0,0,'L');
    // Line break
    $this->Ln(12);
}

    function subhead($ISdetails)
{
    $this->Ln(5);
    $this->SetFont('Arial','B',12);
    $this->Cell(15);
    $this->Cell(0,10, $ISdetails ,0,0,'C');
    // Line break
    $this->Ln(12);
}

    
    
    
    }
    
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('P','Legal',0);

    $pdf->subhead($Detailsly);

    $pdf->subhead1("INCOME");
    $pdf->headerTable();
    $pdf->queryTableIncome($Monthly, $Yearly, $conn);

    $pdf->subhead1("EXPENSES");
    $pdf->headerTable();
    $pdf->queryTableExpenses($Monthly, $Yearly, $conn);
    
    
    $pdf->Signatory($BusinessOwner, $Position, $Sex);
    $pdf->Output($fileName, 'D');
          

}





if(isset($_POST['Search_Monthly_IS'])){

    $month = $_POST['monthIShow'];
    $year = $_POST['yearIShow'];

    $sqlforNoAccount = "SELECT DISTINCT is_details FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                $ISdetails = $row['is_details'];

            }


   header("Location: ../Bookkeeping/B-Income-Statement.php");
    $_SESSION ['year_is'] = $year;
    $_SESSION ['month_is']= $month;
    $_SESSION ['ISdetails']= $ISdetails;

}



if(isset($_POST['Search_Quarterly_IS'])){

    $month = $_POST['quarterIShow'];
    $year = $_POST['yearIShowQ'];
    
    $sqlforNoAccount = "SELECT DISTINCT is_details FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                $ISdetails = $row['is_details'];

            }


   header("Location: ../Bookkeeping/B-Income-Statement.php");
    $_SESSION ['year_is'] = $year;
    $_SESSION ['month_is']= $month;
    $_SESSION ['ISdetails']= $ISdetails;

}


if(isset($_POST['Search_Yearly_IS'])){

    $month = $_POST['yearIShowY'];
    $year = $_POST['yearIShowY'];
    
    $sqlforNoAccount = "SELECT DISTINCT is_details FROM `tblincomestatement` WHERE `is_month` = '$month' AND `is_year` = '$year'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                $ISdetails = $row['is_details'];

            }


   header("Location: ../Bookkeeping/B-Income-Statement.php");
    $_SESSION ['year_is'] = $year;
    $_SESSION ['month_is']= $month;
    $_SESSION ['ISdetails']= $ISdetails;

}


if(isset($_POST['Search_Monthly_CF'])){

    $month = $_POST['monthCShow'];
    $year = $_POST['yearCShow'];

    $sqlforNoAccount = "SELECT DISTINCT cf_details FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                $ISdetails = $row['cf_details'];

            }


            header("Location: ../Bookkeeping/B-Cash-flow.php");
            $_SESSION ['year_is'] = $year;
            $_SESSION ['month_is']= $month;
            $_SESSION ['ISdetails']= $ISdetails;

}


if(isset($_POST['Search_Quarterly_CF'])){

    $month = $_POST['quarterCShow'];
    $year = $_POST['yearCShowQ'];
    
    $sqlforNoAccount = "SELECT DISTINCT cf_details FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                $ISdetails = $row['cf_details'];

            }


            header("Location: ../Bookkeeping/B-Cash-flow.php");
            $_SESSION ['year_is'] = $year;
            $_SESSION ['month_is']= $month;
            $_SESSION ['ISdetails']= $ISdetails;

}


if(isset($_POST['Search_Yearly_CF'])){

    $month = $_POST['yearCShowY'];
    $year = $_POST['yearCShowY'];
    
    $sqlforNoAccount = "SELECT DISTINCT cf_details FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {

                $ISdetails = $row['cf_details'];

            }


            header("Location: ../Bookkeeping/B-Cash-flow.php");
            $_SESSION ['year_is'] = $year;
            $_SESSION ['month_is']= $month;
            $_SESSION ['ISdetails']= $ISdetails;

}

if(isset($_POST['print_CF'])){

    $timeStamp = date("F j Y h:i A");
            $action = "Generated Printable Cash Flow Statement.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);
    $Yearly =   $_SESSION ['year_is'];
    $Monthly = $_SESSION ['month_is'];
    $Detailsly = $_SESSION ['ISdetails'];

    $MonthDetails = array('',
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',);

    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }

    if($Monthly == "Q1" || $Monthly == "Q2" || $Monthly == "Q3" || $Monthly == "Q4"){

        $fileName = $BusinessName .'-Cash Flow '.$Monthly.'-of-'.$Yearly.'.pdf';

    }else if(strlen($Monthly) == 4){
        $fileName = $BusinessName .'-Cash Flow Year-of-'.$Yearly.'.pdf';
    }else{
        $fileName = $BusinessName .'-Cash Flow '. $MonthDetails[$Monthly] .$Yearly.'.pdf';
    }


                
    class PDF extends FPDF
    {

        function __construct()
		{
			parent::__construct();
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
			parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
		}

		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
			parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
		}

		function Write($h, $txt, $link='')
		{
			parent::Write($h, $this->normalize($txt), $link);
		}

		function Text($x, $y, $txt)
		{
			parent::Text($x, $y, $this->normalize($txt));
		}

		protected function normalize($word)
		{
			// Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
			
			$word = str_replace("@","%40",$word);
			$word = str_replace("`","%60",$word);
			$word = str_replace("¢","%A2",$word);
			$word = str_replace("£","%A3",$word);
			$word = str_replace("¥","%A5",$word);
			$word = str_replace("|","%A6",$word);
			$word = str_replace("«","%AB",$word);
			$word = str_replace("¬","%AC",$word);
			$word = str_replace("¯","%AD",$word);
			$word = str_replace("º","%B0",$word);
			$word = str_replace("±","%B1",$word);
			$word = str_replace("ª","%B2",$word);
			$word = str_replace("µ","%B5",$word);
			$word = str_replace("»","%BB",$word);
			$word = str_replace("¼","%BC",$word);
			$word = str_replace("½","%BD",$word);
			$word = str_replace("¿","%BF",$word);
			$word = str_replace("À","%C0",$word);
			$word = str_replace("Á","%C1",$word);
			$word = str_replace("Â","%C2",$word);
			$word = str_replace("Ã","%C3",$word);
			$word = str_replace("Ä","%C4",$word);
			$word = str_replace("Å","%C5",$word);
			$word = str_replace("Æ","%C6",$word);
			$word = str_replace("Ç","%C7",$word);
			$word = str_replace("È","%C8",$word);
			$word = str_replace("É","%C9",$word);
			$word = str_replace("Ê","%CA",$word);
			$word = str_replace("Ë","%CB",$word);
			$word = str_replace("Ì","%CC",$word);
			$word = str_replace("Í","%CD",$word);
			$word = str_replace("Î","%CE",$word);
			$word = str_replace("Ï","%CF",$word);
			$word = str_replace("Ð","%D0",$word);
			$word = str_replace("Ñ","%D1",$word);
			$word = str_replace("Ò","%D2",$word);
			$word = str_replace("Ó","%D3",$word);
			$word = str_replace("Ô","%D4",$word);
			$word = str_replace("Õ","%D5",$word);
			$word = str_replace("Ö","%D6",$word);
			$word = str_replace("Ø","%D8",$word);
			$word = str_replace("Ù","%D9",$word);
			$word = str_replace("Ú","%DA",$word);
			$word = str_replace("Û","%DB",$word);
			$word = str_replace("Ü","%DC",$word);
			$word = str_replace("Ý","%DD",$word);
			$word = str_replace("Þ","%DE",$word);
			$word = str_replace("ß","%DF",$word);
			$word = str_replace("à","%E0",$word);
			$word = str_replace("á","%E1",$word);
			$word = str_replace("â","%E2",$word);
			$word = str_replace("ã","%E3",$word);
			$word = str_replace("ä","%E4",$word);
			$word = str_replace("å","%E5",$word);
			$word = str_replace("æ","%E6",$word);
			$word = str_replace("ç","%E7",$word);
			$word = str_replace("è","%E8",$word);
			$word = str_replace("é","%E9",$word);
			$word = str_replace("ê","%EA",$word);
			$word = str_replace("ë","%EB",$word);
			$word = str_replace("ì","%EC",$word);
			$word = str_replace("í","%ED",$word);
			$word = str_replace("î","%EE",$word);
			$word = str_replace("ï","%EF",$word);
			$word = str_replace("ð","%F0",$word);
			$word = str_replace("ñ","%F1",$word);
			$word = str_replace("ò","%F2",$word);
			$word = str_replace("ó","%F3",$word);
			$word = str_replace("ô","%F4",$word);
			$word = str_replace("õ","%F5",$word);
			$word = str_replace("ö","%F6",$word);
			$word = str_replace("÷","%F7",$word);
			$word = str_replace("ø","%F8",$word);
			$word = str_replace("ù","%F9",$word);
			$word = str_replace("ú","%FA",$word);
			$word = str_replace("û","%FB",$word);
			$word = str_replace("ü","%FC",$word);
			$word = str_replace("ý","%FD",$word);
			$word = str_replace("þ","%FE",$word);
			$word = str_replace("ÿ","%FF",$word);

			return urldecode($word);
		}





    // Page header
    function Header()
    {

        if ( $this->PageNo() === 1 ) {
                    // Logo
        $this->Image('../img/logoheaderrepor.png',25,6,170);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        
        $this->Ln(25);
        // Title
        $this->Cell(100);
        $this->Cell(10,10,'STATEMENT OF CASHFLOW',0,0,'C');
        }

    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    // Page footer
    function headerTable()
    {
        
        
      
        // Arial italic 8
        $this->SetFont('Arial','B',10);
        // Page number
        $this->Cell(5);
        $this->Cell(95,5,'DESCRIPTION',1,0,'C');
        $this->Cell(95,5,'AMOUNT',1,0,'C');
        $this->Ln();
    }


   
    function queryTableOP($month, $year,$conn){
            $this->SetFont('Arial','B',10);
           
          
            $sqlforNoAccount = "SELECT `cf_description`, `cf_amount`, `cf_sign` FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                    $this->Cell(5);
                    $this->Cell(95,7,$row['cf_description'],1,0,'L');
                    if($row['cf_sign'] == "negative"){
                        $amount = '('.number_format($row['cf_amount']).')'; 
                      }else{
                        
                        $amount = number_format($row['cf_amount']); 
                      }
                    $this->Cell(95,7,$amount,1,0,'R');
                    $this->Ln();
            }

                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'negative'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                    while ($row = $result->fetch_assoc()) {
                        $totalnegative = $row['total_amount'];
                    }

                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'positive'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                    while ($row = $result->fetch_assoc()) {
                        $totalpositive = $row['total_amount'];
                    }

                    if($totalpositive > $totalnegative){
                        $totalOP = $totalpositive - $totalnegative;
                        $totalOPO = $totalpositive - $totalnegative;
                        $totalOP = number_format($totalOP);
                      }else{
                        $totalOP = $totalnegative - $totalpositive;
                        $totalOPO = $totalpositive - $totalnegative;
                        $totalOP = '('.number_format($totalOP).')';
                      }


                    $this->Cell(5);
                    $this->Cell(95,7,'Net cash provided (used) from operating activities',1,0,'L');
                    $this->Cell(95,7,$totalOP,1,0,'R');
                    $this->Ln();

    }

    function queryTableIN($month, $year,$conn){
        $this->SetFont('Arial','B',10);
       
      
        $sqlforNoAccount = "SELECT `cf_description`, `cf_amount`, `cf_sign` FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'INVESTING'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $this->Cell(5);
                $this->Cell(95,7,$row['cf_description'],1,0,'L');
                if($row['cf_sign'] == "negative"){
                    $amount = '('.number_format($row['cf_amount']).')'; 
                  }else{
                    
                    $amount = number_format($row['cf_amount']); 
                  }
                $this->Cell(95,7,$amount,1,0,'R');
                $this->Ln();
        }

                $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'  AND `cf_category` = 'INVESTING' AND `cf_sign` = 'negative'";    
                $stmt = $conn->prepare($query);
                $stmt-> execute();
                $result = $stmt->get_result();  

                while ($row = $result->fetch_assoc()) {
                    $totalnegative = $row['total_amount'];
                }

                $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'  AND `cf_category` = 'INVESTING' AND `cf_sign` = 'positive'";    
                $stmt = $conn->prepare($query);
                $stmt-> execute();
                $result = $stmt->get_result();  

                while ($row = $result->fetch_assoc()) {
                    $totalpositive = $row['total_amount'];
                }

                if($totalpositive > $totalnegative){
                    $totalOP = $totalpositive - $totalnegative;
                    $totalOPO = $totalpositive - $totalnegative;
                    $totalOP = number_format($totalOP);
                  }else{
                    $totalOP = $totalnegative - $totalpositive;
                    $totalOPO = $totalpositive - $totalnegative;
                    $totalOP = '('.number_format($totalOP).')';
                  }


                $this->Cell(5);
                $this->Cell(95,7,'Net cash provided (used) from investing activities',1,0,'L');
                $this->Cell(95,7,$totalOP,1,0,'R');
                $this->Ln();

}

function queryTableFN($month, $year ,$conn){
    $this->SetFont('Arial','B',10);
   
  
    $sqlforNoAccount = "SELECT `cf_description`, `cf_amount`, `cf_sign` FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $this->Cell(5);
            $this->Cell(95,7,$row['cf_description'],1,0,'L');
            if($row['cf_sign'] == "negative"){
                $amount = '('.number_format($row['cf_amount']).')'; 
              }else{
                
                $amount = number_format($row['cf_amount']); 
              }
            $this->Cell(95,7,$amount,1,0,'R');
            $this->Ln();
    }

            $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING' AND `cf_sign` = 'negative'";    
            $stmt = $conn->prepare($query);
            $stmt-> execute();
            $result = $stmt->get_result();  

            while ($row = $result->fetch_assoc()) {
                $totalnegative = $row['total_amount'];
            }

            $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING' AND `cf_sign` = 'positive'";    
            $stmt = $conn->prepare($query);
            $stmt-> execute();
            $result = $stmt->get_result();  

            while ($row = $result->fetch_assoc()) {
                $totalpositive = $row['total_amount'];
            }

            if($totalpositive > $totalnegative){
                $totalOP = $totalpositive - $totalnegative;
                $totalOPO = $totalpositive - $totalnegative;
                $totalOP = number_format($totalOP);
              }else{
                $totalOP = $totalnegative - $totalpositive;
                $totalOPO = $totalpositive - $totalnegative;
                $totalOP = '('.number_format($totalOP).')';
              }


            $this->Cell(5);
            $this->Cell(95,7,'Net cash provided (used) from financing activities',1,0,'L');
            $this->Cell(95,7,$totalOP,1,0,'R');
            $this->Ln();

}

function queryTableNC($month, $year ,$conn){
    $this->SetFont('Arial','B',10);
   
    
    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'  AND `cf_category` = 'OPERATING' AND `cf_sign` = 'negative'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $totalnegative = $row['total_amount'];
    }

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'positive'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $totalpositive = $row['total_amount'];
    }

    
    $totalOPO = $totalpositive - $totalnegative;

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'INVESTING' AND `cf_sign` = 'negative'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $totalnegative = $row['total_amount'];
    }

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'INVESTING' AND `cf_sign` = 'positive'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $totalpositive = $row['total_amount'];
    }

    
    $totalINO = $totalpositive - $totalnegative;


    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING' AND `cf_sign` = 'negative'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $totalnegative = $row['total_amount'];
    }

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year' AND  `cf_category` = 'FINANCING' AND `cf_sign` = 'positive'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $totalpositive = $row['total_amount'];
    }

    
    $totalFNO = $totalpositive - $totalnegative;

    $netC = $totalFNO + $totalINO + $totalOPO;

  
    
    $query = "SELECT  DISTINCT cf_first_balance FROM `tblcashflow` WHERE `cf_date_month` = '$month' AND `cf_date_year` = '$year'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
        $first_balance = $row['cf_first_balance'];
    }   


    $ending_balance = $first_balance + $netC;

    if($netC < 0){
        $netC = '('.number_format(substr($netC,1)).')';
      }else{
        $netC =  number_format($netC);
      }


      if($ending_balance < 0){
        $ending_balance = '('.number_format(substr($ending_balance,1)).')';
      }else{
        $ending_balance =  number_format($ending_balance);
      }

    

            $this->Cell(5);
            $this->Cell(95,7,'NET CHANGE IN CASH',1,0,'L');
            $this->Cell(95,7,$netC,1,0,'R');
            $this->Ln();

            $this->Cell(5);
            $this->Cell(95,7,'CASH BEGINNING',1,0,'L');
            $this->Cell(95,7,number_format($first_balance),1,0,'R');
            $this->Ln();

            $this->Cell(5);
            $this->Cell(95,7,'CASH ENDING',1,0,'L');
            $this->Cell(95,7,$ending_balance,1,0,'R');
            $this->Ln();



}

    
    
function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    
    
    // Line break
    $this->Ln(20);
}

function subhead1($ISdetails)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, $ISdetails ,0,0,'L');
    // Line break
    $this->Ln(12);
}
    

    function subhead($ISdetails)
{
    $this->Ln(5);
    $this->SetFont('Arial','B',12);
    $this->Cell(15);
    $this->Cell(0,10, $ISdetails ,0,0,'C');
    // Line break
    $this->Ln(12);
}


    
    
    }
    
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('P','Legal',0);

    $pdf->subhead($Detailsly);

    $pdf->subhead1("OPERATING");
    $pdf->headerTable();
    $pdf->queryTableOP($Monthly, $Yearly,$conn);

    $pdf->subhead1("INVESTING");
    $pdf->headerTable();
    $pdf->queryTableIN($Monthly, $Yearly,$conn);

    $pdf->subhead1("FINANCING");
    $pdf->headerTable();
    $pdf->queryTableFN($Monthly, $Yearly,$conn);

    $pdf->subhead1("NET CHANGE IN CASH");
    $pdf->headerTable();
    $pdf->queryTableNC($Monthly, $Yearly,$conn);
    
    
    $pdf->Signatory($BusinessOwner, $Position, $Sex);
    $pdf->Output($fileName, 'D');
          

}




//JOJIEEE

if(isset($_GET['print_quotation'])){

    $timeStamp = date("F j Y h:i A");
            $action = "Generated Printable Quotation Form.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

    $TransactionID =   $_GET['print_quotation'];
    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);
    $Yearly = $_SESSION ['year'];
    $Date2Day = date('F d, Y');

    
    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }


    $fileName = $BusinessName .'-QUOTATION '. $Yearly.'.pdf';
   


                
    class PDF extends FPDF
    {

        function __construct()
		{
			parent::__construct();
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
			parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
		}

		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
			parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
		}

		function Write($h, $txt, $link='')
		{
			parent::Write($h, $this->normalize($txt), $link);
		}

		function Text($x, $y, $txt)
		{
			parent::Text($x, $y, $this->normalize($txt));
		}

		protected function normalize($word)
		{
			// Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
			
			$word = str_replace("@","%40",$word);
			$word = str_replace("`","%60",$word);
			$word = str_replace("¢","%A2",$word);
			$word = str_replace("£","%A3",$word);
			$word = str_replace("¥","%A5",$word);
			$word = str_replace("|","%A6",$word);
			$word = str_replace("«","%AB",$word);
			$word = str_replace("¬","%AC",$word);
			$word = str_replace("¯","%AD",$word);
			$word = str_replace("º","%B0",$word);
			$word = str_replace("±","%B1",$word);
			$word = str_replace("ª","%B2",$word);
			$word = str_replace("µ","%B5",$word);
			$word = str_replace("»","%BB",$word);
			$word = str_replace("¼","%BC",$word);
			$word = str_replace("½","%BD",$word);
			$word = str_replace("¿","%BF",$word);
			$word = str_replace("À","%C0",$word);
			$word = str_replace("Á","%C1",$word);
			$word = str_replace("Â","%C2",$word);
			$word = str_replace("Ã","%C3",$word);
			$word = str_replace("Ä","%C4",$word);
			$word = str_replace("Å","%C5",$word);
			$word = str_replace("Æ","%C6",$word);
			$word = str_replace("Ç","%C7",$word);
			$word = str_replace("È","%C8",$word);
			$word = str_replace("É","%C9",$word);
			$word = str_replace("Ê","%CA",$word);
			$word = str_replace("Ë","%CB",$word);
			$word = str_replace("Ì","%CC",$word);
			$word = str_replace("Í","%CD",$word);
			$word = str_replace("Î","%CE",$word);
			$word = str_replace("Ï","%CF",$word);
			$word = str_replace("Ð","%D0",$word);
			$word = str_replace("Ñ","%D1",$word);
			$word = str_replace("Ò","%D2",$word);
			$word = str_replace("Ó","%D3",$word);
			$word = str_replace("Ô","%D4",$word);
			$word = str_replace("Õ","%D5",$word);
			$word = str_replace("Ö","%D6",$word);
			$word = str_replace("Ø","%D8",$word);
			$word = str_replace("Ù","%D9",$word);
			$word = str_replace("Ú","%DA",$word);
			$word = str_replace("Û","%DB",$word);
			$word = str_replace("Ü","%DC",$word);
			$word = str_replace("Ý","%DD",$word);
			$word = str_replace("Þ","%DE",$word);
			$word = str_replace("ß","%DF",$word);
			$word = str_replace("à","%E0",$word);
			$word = str_replace("á","%E1",$word);
			$word = str_replace("â","%E2",$word);
			$word = str_replace("ã","%E3",$word);
			$word = str_replace("ä","%E4",$word);
			$word = str_replace("å","%E5",$word);
			$word = str_replace("æ","%E6",$word);
			$word = str_replace("ç","%E7",$word);
			$word = str_replace("è","%E8",$word);
			$word = str_replace("é","%E9",$word);
			$word = str_replace("ê","%EA",$word);
			$word = str_replace("ë","%EB",$word);
			$word = str_replace("ì","%EC",$word);
			$word = str_replace("í","%ED",$word);
			$word = str_replace("î","%EE",$word);
			$word = str_replace("ï","%EF",$word);
			$word = str_replace("ð","%F0",$word);
			$word = str_replace("ñ","%F1",$word);
			$word = str_replace("ò","%F2",$word);
			$word = str_replace("ó","%F3",$word);
			$word = str_replace("ô","%F4",$word);
			$word = str_replace("õ","%F5",$word);
			$word = str_replace("ö","%F6",$word);
			$word = str_replace("÷","%F7",$word);
			$word = str_replace("ø","%F8",$word);
			$word = str_replace("ù","%F9",$word);
			$word = str_replace("ú","%FA",$word);
			$word = str_replace("û","%FB",$word);
			$word = str_replace("ü","%FC",$word);
			$word = str_replace("ý","%FD",$word);
			$word = str_replace("þ","%FE",$word);
			$word = str_replace("ÿ","%FF",$word);

			return urldecode($word);
		}
        
    // Page header
    function Header()
    {

        if ( $this->PageNo() === 1 ) {
                    // Logo
         $this->Image('../img/logoheaderrepor.png',25,6,170);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        
        $this->Ln(25);
        }

    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    // Page footer
    function headerTable()
    {
        
        // Arial italic 8
        $this->SetFont('Arial','B',10);
        // Page number
        $this->Cell(5);
        $this->Cell(15,5,'ITEM',1,0,'C');
        $this->Cell(85,5,'DESCRIPTION',1,0,'C');
        $this->Cell(15,5,'QTY',1,0,'C');
        $this->Cell(15,5,'UNIT',1,0,'C');
        $this->Cell(30,5,'UNIT COST',1,0,'C');
        $this->Cell(30,5,'TOTAL AMOUNT',1,0,'C');
        $this->Ln();
    }


    function queryTableItems($conn, $TransactionID){
        

        $this->SetFont('Arial','',10);

        $sqlforNoAccount = "SELECT * FROM `tblorder` WHERE `order_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        $num = 1;
        while ($row = $result->fetch_assoc()) {

            $this->Cell(5);
            $this->Cell(15,8,$num,1,0,'C');
            $this->Cell(85,8,$row['order_product_name'],1,0,'L');
            $this->Cell(15,8,$row['order_product_quantity'],1,0,'C');
            $this->Cell(15,8,$row['order_product_unit'],1,0,'C');
            $this->Cell(30,8,'PHP ' . number_format($row['order_product_price']),1,0,'C');
            $this->Cell(30,8,'PHP ' .number_format($row['order_product_total']),1,0,'C');
            $this->Ln();

            $num++;
        }

        $sqlforNoAccount = "SELECT SUM(order_product_total) as Grand_Total FROM `tblorder` WHERE `order_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                $grand = $row['Grand_Total'];
                   
            }

             
            $this->Cell(5);
            $this->SetFillColor(255,255,52);
            $this->Cell(160,8,'Total Project Cost (VAT-excluded)',1,0,'L',true);
            $this->Cell(30,8,'PHP ' .number_format($grand),1,0,'C',true);
            $this->Ln();

    }

    
    
function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    


    $this->Ln(15);
    $this->SetFont('Arial','',12);
    $this->Cell(5);
    $this->Cell(0,10, "Client Conforme:" ,0,0,'L');
    $this->Ln(10);
    $this->Cell(5);
    $this->Cell(0,10,'Name & Signature:',0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10,'Position:',0,0,'L');    
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10,'Date:',0,0,'L');    
    
    // Line break
    $this->Ln(20);
}

function subhead1($ISdetails)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, $ISdetails ,0,0,'L');
    // Line break
    $this->Ln(12);
}

    function subhead($Date2Day,$id)
{
    $this->Ln(5);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, "DATE:" ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Cell(-175);
    $this->Cell(0,10, $Date2Day ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Cell(-45);
    $this->Cell(0,10, $id ,0,0,'L');     
    // Line break
    $this->Ln(20);
    $this->Cell(5);
    $this->Cell(0,10, "Dear Client:" ,0,0,'L');

    
    $this->Ln(10);
    $this->Cell(5);
    $this->Cell(0,10, "In compliance with your request for a quotation on the above subject, we are pleased to submit the" ,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10, "following for your consideration and approval." ,0,0,'L');

    $this->Ln(15);

}


function bodyText()
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, "I.   Scope of Work:" ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10, "     Supply Equipment." ,0,0,'L');
    // Line break
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, "II.  Terms & Conditions:" ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Ln(6);
    $this->Cell(5);
    $this->Cell(0,10, "     1. Delivery Lead Time: (5) days lead time for in-stock items upon down payment." ,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10, "        (15)days for non-stock items." ,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10, "     2. If invoice is needed 12% VAT." ,0,0,'L');

    // Line break
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, "III. Terms & Payment:" ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Ln(6);
    $this->Cell(5);
    $this->Cell(0,10, "     50% Down-payment." ,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10, "     50% Before Delivery." ,0,0,'L');

    
    // Line break
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, "IV. Warranty:" ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Ln(6);
    $this->Cell(5);
    $this->Cell(0,10, "     1-year warranty for door access unit (factory defect)." ,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->Cell(0,10, "     1 month free onsite diagnostics and support. * Additional charges will apply." ,0,0,'L');

  
    // Line break
    $this->Ln(10);
    $this->SetFont('Arial','',12);
    $this->Cell(5);
    $this->Cell(0,10, "We look forward to your confirmation regarding this matter." ,0,0,'L');
    $this->Ln(6);
    $this->Cell(5);
    $this->Cell(0,10, "Thank you and Best Regards," ,0,0,'L');

   
    $this->Ln(10);

}
    
    
    
    }
    
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('P','Legal',0);

    $pdf->subhead($Date2Day, $TransactionID);

    $pdf->headerTable();
    $pdf->queryTableItems($conn, $TransactionID);
    $pdf->bodyText();
    
    $pdf->Signatory($BusinessOwner, $Position, $Sex);
    $pdf->Output($fileName, 'D');

    $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='UPLOAD OR' WHERE `onprogressQ_transaction_id` = ?";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$TransactionID);
        mysqli_stmt_execute($stmt);

    }

    
    header("Location: ../Admin/onprogress.php");


}




if(isset($_GET['print_billing'])){

    $timeStamp = date("F j Y h:i A");
            $action = "Generated Printable Billing Form.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

    $TransactionID =   $_GET['print_billing'];
    $BusinessName = mysqli_real_escape_string($conn,"MISOUT");
    $Position = mysqli_real_escape_string($conn,   $_SESSION ['user_designation']);
    $BusinessOwner = mysqli_real_escape_string($conn,   $_SESSION ['user_realname']);

    $Date2Day = date('F d, Y');

    
    if( $_SESSION ['user_sex'] == "Male"){
        $Sex = mysqli_real_escape_string($conn,   'Mr.');
    }else{
        $Sex = mysqli_real_escape_string($conn,   'Ms.');
    }

    $MonthDetails = array('',
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December');



    
    $sqlforNoAccount = "SELECT DISTINCT cb_provider FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $Provider = $row['cb_provider'];
        }



    $fileName = $Provider .'-MONTHLY BILL'.'.pdf';

    $sqlforNoAccount = "SELECT SUM(cb_billing_price) as total FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $Gtotal = $row['total'];
        }

   


                
    class PDF extends FPDF
    {

        function __construct()
		{
			parent::__construct();
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
			parent::MultiCell($w, $h, $this->normalize($txt), $border, $align, $fill);
		}

		function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
		{
			parent::Cell($w, $h, $this->normalize($txt), $border, $ln, $align, $fill, $link);
		}

		function Write($h, $txt, $link='')
		{
			parent::Write($h, $this->normalize($txt), $link);
		}

		function Text($x, $y, $txt)
		{
			parent::Text($x, $y, $this->normalize($txt));
		}

		protected function normalize($word)
		{
			// Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php
			
			$word = str_replace("@","%40",$word);
			$word = str_replace("`","%60",$word);
			$word = str_replace("¢","%A2",$word);
			$word = str_replace("£","%A3",$word);
			$word = str_replace("¥","%A5",$word);
			$word = str_replace("|","%A6",$word);
			$word = str_replace("«","%AB",$word);
			$word = str_replace("¬","%AC",$word);
			$word = str_replace("¯","%AD",$word);
			$word = str_replace("º","%B0",$word);
			$word = str_replace("±","%B1",$word);
			$word = str_replace("ª","%B2",$word);
			$word = str_replace("µ","%B5",$word);
			$word = str_replace("»","%BB",$word);
			$word = str_replace("¼","%BC",$word);
			$word = str_replace("½","%BD",$word);
			$word = str_replace("¿","%BF",$word);
			$word = str_replace("À","%C0",$word);
			$word = str_replace("Á","%C1",$word);
			$word = str_replace("Â","%C2",$word);
			$word = str_replace("Ã","%C3",$word);
			$word = str_replace("Ä","%C4",$word);
			$word = str_replace("Å","%C5",$word);
			$word = str_replace("Æ","%C6",$word);
			$word = str_replace("Ç","%C7",$word);
			$word = str_replace("È","%C8",$word);
			$word = str_replace("É","%C9",$word);
			$word = str_replace("Ê","%CA",$word);
			$word = str_replace("Ë","%CB",$word);
			$word = str_replace("Ì","%CC",$word);
			$word = str_replace("Í","%CD",$word);
			$word = str_replace("Î","%CE",$word);
			$word = str_replace("Ï","%CF",$word);
			$word = str_replace("Ð","%D0",$word);
			$word = str_replace("Ñ","%D1",$word);
			$word = str_replace("Ò","%D2",$word);
			$word = str_replace("Ó","%D3",$word);
			$word = str_replace("Ô","%D4",$word);
			$word = str_replace("Õ","%D5",$word);
			$word = str_replace("Ö","%D6",$word);
			$word = str_replace("Ø","%D8",$word);
			$word = str_replace("Ù","%D9",$word);
			$word = str_replace("Ú","%DA",$word);
			$word = str_replace("Û","%DB",$word);
			$word = str_replace("Ü","%DC",$word);
			$word = str_replace("Ý","%DD",$word);
			$word = str_replace("Þ","%DE",$word);
			$word = str_replace("ß","%DF",$word);
			$word = str_replace("à","%E0",$word);
			$word = str_replace("á","%E1",$word);
			$word = str_replace("â","%E2",$word);
			$word = str_replace("ã","%E3",$word);
			$word = str_replace("ä","%E4",$word);
			$word = str_replace("å","%E5",$word);
			$word = str_replace("æ","%E6",$word);
			$word = str_replace("ç","%E7",$word);
			$word = str_replace("è","%E8",$word);
			$word = str_replace("é","%E9",$word);
			$word = str_replace("ê","%EA",$word);
			$word = str_replace("ë","%EB",$word);
			$word = str_replace("ì","%EC",$word);
			$word = str_replace("í","%ED",$word);
			$word = str_replace("î","%EE",$word);
			$word = str_replace("ï","%EF",$word);
			$word = str_replace("ð","%F0",$word);
			$word = str_replace("ñ","%F1",$word);
			$word = str_replace("ò","%F2",$word);
			$word = str_replace("ó","%F3",$word);
			$word = str_replace("ô","%F4",$word);
			$word = str_replace("õ","%F5",$word);
			$word = str_replace("ö","%F6",$word);
			$word = str_replace("÷","%F7",$word);
			$word = str_replace("ø","%F8",$word);
			$word = str_replace("ù","%F9",$word);
			$word = str_replace("ú","%FA",$word);
			$word = str_replace("û","%FB",$word);
			$word = str_replace("ü","%FC",$word);
			$word = str_replace("ý","%FD",$word);
			$word = str_replace("þ","%FE",$word);
			$word = str_replace("ÿ","%FF",$word);

			return urldecode($word);
		}
        
    // Page header
    function Header()
    {

        if ( $this->PageNo() === 1 ) {
                    // Logo
         $this->Image('../img/logoheaderrepor.png',25,6,170);
        // Arial bold 15
        $this->SetFont('Arial','B',14);
        // Move to the right
        
        $this->Ln(25);
        }

    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    // Page footer
    function headerTable()
    {
        
        // Arial italic 8
        $this->SetFont('Arial','B',10);
        // Page number
        $this->Cell(5);
        $this->Cell(30,5,'Name',1,0,'C');
        $this->Cell(30,5,'Acct No',1,0,'C');
        $this->Cell(30,5,'Service Promo',1,0,'C');
        $this->Cell(34,5,'Billing Period',1,0,'C');
        $this->Cell(33,5,'Amount',1,0,'C');
        $this->Cell(33,5,'Client Name',1,0,'C');
        $this->Ln();
    }


    function queryTableItems($conn, $TransactionID, $Group){
        
        $query = "SELECT * FROM `tblcreatedbill` WHERE `cb_client_group` = '$Group' AND cb_transaction_id  = '$TransactionID'";    
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                    $this->SetFont('Arial','',9);
                    $this->Cell(5);
                    $this->Cell(30,8,$row['cb_client_name'],1,0,'L');
                    $this->Cell(30,8,$row['cb_account_number'],1,0,'L');
                    $this->Cell(30,8,$row['cb_service_promo'],1,0,'L');
                    $this->Cell(34,8,$row['cb_billing_period'],1,0,'L');
                    $this->Cell(33,8,'PHP ' . number_format($row['cb_billing_price']),1,0,'L');
                    $this->Cell(33,8,'PHP ' . number_format($row['cb_client_payment']),1,0,'L');
                    $this->Ln();
        }


        $query = "SELECT SUM(cb_billing_price) as total FROM `tblcreatedbill` WHERE `cb_client_group` = '$Group' AND cb_transaction_id  = '$TransactionID'";    
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
                    
                    $this->Cell(5);
                    $this->SetFillColor(255,255,52);
                    $this->Cell(157,8,'Total Bills Amount',1,0,'L');
                    $this->Cell(33,8,'PHP ' . number_format($row['total']),1,0,'L');
                    $this->Ln();
        }
    }

    
    
function Signatory($BusinessOwner, $Position, $Sex)
{
    $this->Ln(7);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10,$Sex . ' ' .$BusinessOwner,0,0,'L');
    $this->Ln(5);
    $this->Cell(5);
    $this->SetFont('Arial','',12);
    $this->Cell(0,10,$Position,0,0,'L');    
    
    // Line break
    $this->Ln(20);
}

function subhead1($ISdetails)
{
    $this->Ln(8);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, $ISdetails ,0,0,'L');
    // Line break
    $this->Ln(12);
}

    function subhead($Date2Day, $Provider,$Gtotal)
{
    $this->Ln(5);
    $this->SetFont('Arial','B',12);
    $this->Cell(5);
    $this->Cell(0,10, "DATE:" ,0,0,'L');
    $this->SetFont('Arial','',12);
    $this->Cell(-175);
    $this->Cell(0,10, $Date2Day ,0,0,'L');
    $this->SetFont('Arial','B',12);
    $this->Ln(15);
    $this->Cell(5);
    $this->Cell(0,10, $Provider ." MONTHLY BILL" ,0,0,'C');
    $this->SetFont('Arial','',12);
    $this->Ln(10);
    $this->Cell(5);
    $this->Cell(0,10, "GRAND TOTAL: ". 'PHP ' . number_format($Gtotal)  ,0,0,'C');

    $this->Ln(10);

}


    
    
    
    }
    
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage('P','Legal',0);


    $pdf->subhead($Date2Day,$Provider,$Gtotal);

        
        $groups[] = array();
        $sqlforNoAccount = "SELECT DISTINCT cb_client_group FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();

        $num = 0;
        while ($row = $result->fetch_assoc()) {
            $groups[$num] = $row['cb_client_group'];
            $num++;
        }

        $total = count($groups);

        for ($i=0; $i < $total  ; $i++) { 
             # code...
                $pdf->subhead1($groups[$i]);

                $pdf->headerTable();
                $pdf->queryTableItems($conn, $TransactionID, $groups[$i]);
        }

    

    
    $pdf->Signatory($BusinessOwner, $Position, $Sex);
    $pdf->Output($fileName, 'D');


    $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='UPLOAD OR' WHERE `onprogressB_transaction_id` = ?";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$TransactionID);
        mysqli_stmt_execute($stmt);

    }

    
    header("Location: ../Admin/onprogress-billing.php");
          

}


if(isset($_POST['searchRecordQ'])){
    $TransactionID = $_POST['searchRecordQ_ID'];

    $sqlforNoAccount = "SELECT `order_transaction_id`, `order_client_name` FROM `tblorder` WHERE `order_transaction_id` = '$TransactionID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    $ClientName = "No Record";
    $IDQ = "No Record";

    while ($row = $result->fetch_assoc()) {
            $ClientName = $row['order_client_name'];
            $IDQ = $row['order_transaction_id'];
        }


        $_SESSION['clientNameQ'] =  $ClientName;
        $_SESSION['transactionQID'] =  $IDQ;
         header("Location: ../Admin/record.php");
    
}



if(isset($_POST['searchRecordB'])){
    $TransactionID = $_POST['searchRecordB_ID'];

    $sqlforNoAccount = "SELECT `cb_transaction_id`, `cb_provider` FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$TransactionID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    $ClientName = "No Record";
    $IDQ = "No Record";

    while ($row = $result->fetch_assoc()) {
            $ProviderName = $row['cb_provider'];
            $IDB = $row['cb_transaction_id'];
        }


        $_SESSION['clientNameB'] =  $ProviderName;
        $_SESSION['transactionBID'] =  $IDB;
         header("Location: ../Admin/billing-record.php");
    
}

if(isset($_POST['qreport_show'])){
    $_SESSION ['date_month_show'] = $_POST['month'];
    $_SESSION ['date_year_show']=  $_POST['year'];
    header("Location: ../Admin/report.php");
    
}

if(isset($_POST['breport_show'])){
    $_SESSION ['date_month_showbill'] = $_POST['month_bill'];
    $_SESSION ['date_year_showbill']=  $_POST['year_bill'];
    header("Location: ../Admin/report-billing.php");
    
}




if(isset($_POST['deletefiles_btn_confirm'])){

    $timeStamp = date("F j Y h:i A");
            $action = "Deleted Quotation on Progress Record.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


    $FileID = $_POST['deletefiles_id_confirm'];

    $sqlfordeletfiletask="DELETE FROM `tblorder` WHERE `order_transaction_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }

    $sqlfordeletfiletask="DELETE FROM `tblqonprogress` WHERE `onprogressQ_transaction_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }


        $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$FileID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt-> execute();
        $result = $stmt->get_result();  
        while ($row = $result->fetch_assoc()) {
            $filenameO = $row['or_image'];

            
        $fil = '../Admin/img/or/' . $filenameO;   

        unlink($fil);

        }


    $sqlfordeletfiletask="DELETE FROM `tblofficialreceipt` WHERE `or_transaction_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }

}



if(isset($_GET['done_quotation'])){

    $TransactionID = $_GET['done_quotation'];
    


    
    $sqlforNoAccount = "SELECT * FROM `tblqonprogress` WHERE `onprogressQ_transaction_id` = '$TransactionID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $ClientName = $row['onprogressQ_client_name'];
            $Date = $row['onprogressQ_date_created'];
            $Summary = $row['onprogressQ_order_summary'];
        }


        $sqlforNoAccount = "SELECT SUM(order_product_total) as Grand_Total FROM `tblorder` WHERE `order_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $Amount = $row['Grand_Total'];
                   
            }
    
            $sqlforUploadProf = "INSERT INTO tblqreport(reportQ_id, reportQ_client_name, reportQ_transaction_id, reportQ_transaction_date, reportQ_summary, reportQ_amount) VALUES ('',?,?,?,?,?);";
    
            $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"sssss",$ClientName, $TransactionID, $Date, $Summary, $Amount);
                mysqli_stmt_execute($stmt);
    
            }


    $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='DONE' WHERE `onprogressQ_transaction_id` = ?";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$TransactionID);
        mysqli_stmt_execute($stmt);
    }

   


        $_SESSION['date_year_show'] = substr($Date,0,4);
        $_SESSION['date_month_show'] = substr($Date,6,-3);

        header("Location: ../Admin/report.php");

}



if(isset($_GET['done_bill'])){

    $TransactionID = $_GET['done_bill'];
    


    
    $sqlforNoAccount = "SELECT * FROM `tblbonprogress` WHERE `onprogressB_transaction_id` = '$TransactionID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $Provide_name = $row['onprogressB_provider'];
            $Date = $row['onprogressB_date_created'];
            $Summary = $row['onprogressB_billing_summary'];
        }


        $sqlforNoAccount = "SELECT SUM(cb_billing_price) as Grand_Total FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$TransactionID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $Amount = $row['Grand_Total'];
                   
            }
    
            $sqlforUploadProf = "INSERT INTO `tblbreport`(`reportB_id`, `reportB_provider`, `reportB_transaction_id`, `reportB_transaction_date`, `reportB_billing_summary`, `reportB_client_payment`) VALUES('',?,?,?,?,?);";
    
            $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"sssss",$Provide_name, $TransactionID, $Date, $Summary, $Amount);
                mysqli_stmt_execute($stmt);
    
            }


            $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='DONE' WHERE `onprogressB_transaction_id` = ?";

            $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"s",$TransactionID);
                mysqli_stmt_execute($stmt);
            }

   


        $_SESSION['date_year_showbill'] = substr($Date,0,4);
        $_SESSION['date_month_showbill'] = substr($Date,6,-3);

        header("Location: ../Admin/report-billing.php");

}



if(isset($_POST['upload_or'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Uploaded Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $fileID = $_POST['transactionID'];
   

        
    $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$fileID'";
    $sqlrun = mysqli_query($conn, $sqlforNoAccount);

    if(mysqli_num_rows($sqlrun)>0){

        $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$fileID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt-> execute();
        $result = $stmt->get_result();  
        while ($row = $result->fetch_assoc()) {
            $filenameO = $row['or_image'];
        }
        
        $image = $_FILES ['fileupload_or']['name'];
    // $fileupname = "";
     if($image == ""){
         header("Location: ../Admin/onprogress.php");
         $_SESSION ['response'] = "Please select your file first!";
         $_SESSION ['res_type']= "warning";
     }else{

        $status = "VERIFYING OR";

        $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='$status' WHERE `onprogressQ_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$fileID);
             mysqli_stmt_execute($stmt);
         }


         $filetemploc = $_FILES ['fileupload_or']['tmp_name'];
         $fileExt = explode('.', $image);
         $OldFile = explode('.', $filenameO);

         $fileActualExt = strtolower(end($fileExt));

         $nameOld = $OldFile[0].  '.'  .  $fileActualExt;      


        

            $fil = '../Admin/img/or/' . $filenameO;   

            unlink($fil);
   
         

         
        
         $filedestination = '../Admin/img/or/' . $nameOld;

        move_uploaded_file($filetemploc, $filedestination);
 
        $filename = mysqli_real_escape_string($conn, $nameOld);




 
     $sqlforUploadProf = "UPDATE `tblofficialreceipt` SET `or_image`='$filename' WHERE `or_transaction_id` =?";
 
     $stmt = mysqli_stmt_init($conn);
 
     if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
         echo "SQL Error";
     }else{
         mysqli_stmt_bind_param($stmt,"s",$fileID);
         mysqli_stmt_execute($stmt);
 
     }
 
     
     header("Location: ../Admin/onprogress.php");
     $_SESSION ['response'] = "Successfully Uploaded your Official Receipt!";
     $_SESSION ['res_type']= "success";

     }


    
    }else{

        $status = "VERIFYING OR";

        $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='$status' WHERE `onprogressQ_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$fileID);
             mysqli_stmt_execute($stmt);
         }


         
    $fileupname = $_FILES ['fileupload_or']['name'];
    // $fileupname = "";
     if($fileupname == ""){
         header("Location: ../Admin/onprogress.php");
         $_SESSION ['response'] = "Please select your file first!";
         $_SESSION ['res_type']= "warning";
     }else{
     $filetemploc = $_FILES ['fileupload_or']['tmp_name'];
     $fileExt = explode('.', $fileupname);
     $fileActualExt = strtolower(end($fileExt));
 
   
        
     $merona = true;
     while($merona){

        $filenameNEW = $fileID."-".rand(10000,90000).".".$fileActualExt ;
        $filename = mysqli_real_escape_string($conn, $filenameNEW);

            
        $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_image`= '$filename'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

        if(mysqli_num_rows($sqlrun)==0){
            $sqlforNoAccount = "SELECT gb_receipt FROM `tblgeneratebill` WHERE `gb_receipt`= '$filename'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

        if(mysqli_num_rows($sqlrun)==0){
            $merona = false;
            }
        }
     }

     $filedestination = '../Admin/img/or/' . $filenameNEW;
     move_uploaded_file($filetemploc, $filedestination);
 
     $filename = mysqli_real_escape_string($conn, $filenameNEW);
 
 
 
 
     $sqlforUploadProf = "INSERT INTO `tblofficialreceipt`(`or_id`, `or_transaction_id`, `or_image`) VALUES ('',?,?)";
 
     $stmt = mysqli_stmt_init($conn);
 
     if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
         echo "SQL Error";
     }else{
         mysqli_stmt_bind_param($stmt,"ss",$fileID, $filename);
         mysqli_stmt_execute($stmt);
 
     }
 
     
     header("Location: ../Admin/onprogress.php");
     $_SESSION ['response'] = "Successfully Uploaded your Official Receipt!";
     $_SESSION ['res_type']= "success";
 
     }
 

    }

  
    
    


}



if(isset($_POST['upload_bill_or'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Uploaded Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $fileID = $_POST['bill_transactionID'];
   

        
    $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$fileID'";
    $sqlrun = mysqli_query($conn, $sqlforNoAccount);

    if(mysqli_num_rows($sqlrun)>0){

        $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$fileID'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt-> execute();
        $result = $stmt->get_result();  
        while ($row = $result->fetch_assoc()) {
            $filenameO = $row['or_image'];
        }

        
    $image = $_FILES ['fileupload_bill_or']['name'];
    // $fileupname = "";
     if($image == ""){
         header("Location: ../Admin/onprogress.php");
         $_SESSION ['response'] = "Please select your file first!";
         $_SESSION ['res_type']= "warning";
     }else{

        $status = "VERIFYING OR";

        $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='$status' WHERE `onprogressB_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$fileID);
             mysqli_stmt_execute($stmt);
         }


         $filetemploc = $_FILES ['fileupload_bill_or']['tmp_name'];
         $fileExt = explode('.', $image);
         $OldFile = explode('.', $filenameO);

         $fileActualExt = strtolower(end($fileExt));

         $nameOld = $OldFile[0].  '.'  .  $fileActualExt;      
         
        

            $fil = '../Admin/img/or/' . $filenameO;   

            unlink($fil);
   
        
         $filedestination = '../Admin/img/or/' . $nameOld;

        move_uploaded_file($filetemploc, $filedestination);
 
        $filename = mysqli_real_escape_string($conn, $nameOld);



 
     $sqlforUploadProf = "UPDATE `tblofficialreceipt` SET `or_image`='$filename' WHERE `or_transaction_id` =?";
 
     $stmt = mysqli_stmt_init($conn);
 
     if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
         echo "SQL Error";
     }else{
         mysqli_stmt_bind_param($stmt,"s",$fileID);
         mysqli_stmt_execute($stmt);
 
     }
 
     
     header("Location: ../Admin/onprogress-billing.php");
     $_SESSION ['response'] = "Successfully Uploaded your Official Receipt!";
     $_SESSION ['res_type']= "success";

     }


    
    }else{

        $status = "VERIFYING OR";

        $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='$status' WHERE `onprogressB_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$fileID);
             mysqli_stmt_execute($stmt);
         }


         
    $fileupname = $_FILES ['fileupload_bill_or']['name'];
    // $fileupname = "";
     if($fileupname == ""){
         header("Location: ../Admin/onprogress-billing.php");
         $_SESSION ['response'] = "Please select your file first!";
         $_SESSION ['res_type']= "warning";
     }else{
     $filetemploc = $_FILES ['fileupload_bill_or']['tmp_name'];
     $fileExt = explode('.', $fileupname);
     $fileActualExt = strtolower(end($fileExt));
 
   
        
     $merona = true;
     while($merona){

        $filenameNEW = $fileID."-".rand(10000,90000).".".$fileActualExt ;
        $filename = mysqli_real_escape_string($conn, $filenameNEW);

            
        $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_image`= '$filename'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

        if(mysqli_num_rows($sqlrun)==0){
            $sqlforNoAccount = "SELECT gb_receipt FROM `tblgeneratebill` WHERE `gb_receipt`= '$filename'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

        if(mysqli_num_rows($sqlrun)==0){
            $merona = false;
            }
        }
     }

     $filedestination = '../Admin/img/or/' . $filenameNEW;
     move_uploaded_file($filetemploc, $filedestination);
 
     $filename = mysqli_real_escape_string($conn, $filenameNEW);
 
 
 
 
     $sqlforUploadProf = "INSERT INTO `tblofficialreceipt`(`or_id`, `or_transaction_id`, `or_image`) VALUES ('',?,?)";
 
     $stmt = mysqli_stmt_init($conn);
 
     if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
         echo "SQL Error";
     }else{
         mysqli_stmt_bind_param($stmt,"ss",$fileID, $filename);
         mysqli_stmt_execute($stmt);
 
     }
 
     
     header("Location: ../Admin/onprogress-billing.php");
     $_SESSION ['response'] = "Successfully Uploaded your Official Receipt!";
     $_SESSION ['res_type']= "success";
 
     }
 

    }

  
    
    


}


if(isset($_GET['download_quotationOR'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Downloaded Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    
    $ID = $_GET['download_quotationOR'];
    $Path = 'nodata.png';


    $sqlforNoAccount = "SELECT `or_image` FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$ID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $Path = $row['or_image'];
               
        }

    $filepath = "../Admin/img/or/" . $Path;

    if(file_exists($filepath)){
        header('Content-Type: application/octet-stream');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma:public');
        header('Content-Length:'. filesize('../Admin/img/or/'.$Path));

        readfile('../Admin/img/or/'.$Path);
       exit;
   
      
    }else{
        header("Location: ../Admin/record.php");
        $_SESSION['response']="File does not Exist!";
        $_SESSION['res_type']="error";
    }
   
}




if(isset($_GET['download_quotationIndivdualOR'])){

    
    $timeStamp = date("F j Y h:i A");
    $action = "Downloaded Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $ID = $_GET['download_quotationIndivdualOR'];
    $Path = 'nodata.png';


    $sqlforNoAccount = "SELECT `cb_receipt` FROM `tblcreatedbill` WHERE `cb_receipt`= '$ID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
            $Path = $row['cb_receipt'];
               
        }

    $filepath = "../Admin/img/or/" . $Path;

    if(file_exists($filepath)){
        header('Content-Type: application/octet-stream');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma:public');
        header('Content-Length:'. filesize('../Admin/img/or/'.$Path));

        readfile('../Admin/img/or/'.$Path);
       exit;
   
      
    }else{
        header("Location: ../Admin/billing-record.php");
        $_SESSION['response']="File does not Exist!";
        $_SESSION['res_type']="error";
    }
   
}

if(isset($_POST['edit_user'])){


    $timeStamp = date("F j Y h:i A");
    $action = "Updated User Account Information.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $Username = $_POST['username'];
    $Designation = $_POST['designation'];
    $Role = $_POST['role'];
    $Status = $_POST['status'];
    $Reset = $_POST['reset'];

    if($Reset == 'Yes'){
        $PassW = mysqli_real_escape_string($conn, password_hash ('misout_default',PASSWORD_DEFAULT));


        $sqlforUploadProf = "UPDATE `tbluseraccounts` SET `user_password`='$PassW',`user_role`='$Role',`user_status`='$Status',`user_designation`='$Designation' WHERE `user_name` = ?";

        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"s",$Username);
            mysqli_stmt_execute($stmt);
        }

    }else{

        
        $sqlforUploadProf = "UPDATE `tbluseraccounts` SET `user_role`='$Role',`user_status`='$Status',`user_designation`='$Designation' WHERE `user_name` = ?";

        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"s",$Username);
            mysqli_stmt_execute($stmt);
        }

    }
    $_SESSION ['response'] = "Successfully Account Updated!";
    $_SESSION ['res_type']= "success";
    header("Location: ../CEO/account.php");


}



if(isset($_POST['btn_add_user'])){

    
    $timeStamp = date("F j Y h:i A");
    $action = "Added New User Account.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $Username = $_POST['username1'];
    $Designation = $_POST['designation1'];
    $Role = $_POST['role1'];
    $Sex = $_POST['sex1'];
    $Firstname = $_POST['firstname1'];
    $Lastname = $_POST['lastname1'];
    $Status = "Active";
    $Image = "default.png";
    $Contact = "09XXXXXXXX";
    $Email = "example@gmail.com";
    $PassW = mysqli_real_escape_string($conn, password_hash ($_POST['password1'],PASSWORD_DEFAULT));

    $sqlforNoAccount = "SELECT * FROM `tbluseraccounts` WHERE `user_name`= '$Username'";
    $sqlrun = mysqli_query($conn, $sqlforNoAccount);

    if(mysqli_num_rows($sqlrun)>0){
        header("Location: ../CEO/account.php");
        $_SESSION ['response'] = "Credentials Already Exists!";
        $_SESSION ['res_type']= "error";
    }else{
    
    $sqlforAccounts = "INSERT INTO `tbluseraccounts`(`user_name`, `user_password`, `user_role`, `user_status`, `user_firstname`, `user_lastname`, `user_sex`, `user_contact`, `user_email`, `user_designation`, `user_image`) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
    
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"sssssssssss",$Username,$PassW,$Role,$Status,$Firstname,$Lastname,$Sex,$Contact,$Email,$Designation,$Image);
        mysqli_stmt_execute($stmt);
    }
       



    header("Location: ../CEO/account.php");
        $_SESSION ['response'] = "Successfully Account Created!";
        $_SESSION ['res_type']= "success";
    

    }



}



if(isset($_POST['delete_user'])){


    $timeStamp = date("F j Y h:i A");
    $action = "Deleted User Account.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $uname = $_POST['delete_username'];

    $sqlfordeletfiletask="DELETE FROM `tbluseraccounts` WHERE `user_name`=?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$uname);
        mysqli_stmt_execute($stmt);
    }

}


if(isset($_POST['show_product'])){
    $_SESSION ['selected_supplier'] = $_POST['supplier_selected'];
    
}


if(isset($_POST['show_bill'])){
    $_SESSION ['selected_provider'] = $_POST['selected_provider'];
    
}

if(isset($_POST['btn_add_cart'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Added Product to Cart.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $id = $_POST['product_id'];
    $qty = $_POST['product_quantity'];


    
    $sqlforNoAccount = "SELECT * FROM `tblproduct` WHERE `product_id` = '$id'";
     $stmt = $conn->prepare($sqlforNoAccount);
     $stmt->execute();
     $result = $stmt->get_result();
 
     while ($row = $result->fetch_assoc()) {
             $Product_name = $row['product_name'];
             $Product_unit = $row['product_unit'];
             $Product_price = $row['product_price'];
             $Product_image = $row['product_image'];
         }


    
    $sqlforNoAccount = "SELECT * FROM `tblcart` WHERE `cart_product_name`= '$Product_name'";
    $sqlrun = mysqli_query($conn, $sqlforNoAccount);

    if(mysqli_num_rows($sqlrun)>0){


    $sqlforNoAccount = "SELECT cart_product_quantity  FROM `tblcart` WHERE `cart_product_name`= '$Product_name'";
     $stmt = $conn->prepare($sqlforNoAccount);
     $stmt->execute();
     $result = $stmt->get_result();
 
     while ($row = $result->fetch_assoc()) {
             $oldQTY = $row['cart_product_quantity'];
             
         }

         $newqty = $oldQTY + $qty;

         $Total =  $newqty * $Product_price;

        $sqlforUploadProf = "UPDATE `tblcart` SET `cart_product_quantity`='$newqty',`cart_product_total`='$Total'  WHERE `cart_product_name` = ?";


     $stmt = mysqli_stmt_init($conn);
 
     if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
         echo "SQL Error";
     }else{
         mysqli_stmt_bind_param($stmt,"s",$Product_name);
         mysqli_stmt_execute($stmt);
     }

    }else{



        $Total =  $qty * $Product_price;
 
 
        $sqlforUploadProf = "INSERT INTO `tblcart`(`cart_id`, `cart_product_name`, `cart_product_quantity`, `cart_product_unit`, `cart_product_price`, `cart_product_total`, `cart_product_image`) VALUES ('',?,?,?,?,?,?)";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssssss",$Product_name, $qty, $Product_unit, $Product_price, $Total, $Product_image);
        mysqli_stmt_execute($stmt);

    }

    }
 
 
 

 
     header("Location: ../Admin/Quotation.php");
         $_SESSION ['response'] = "Added to Cart Successfully!";
         $_SESSION ['res_type']= "success";
     
 
     
 }

 
if(isset($_POST['update_cart'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Updated Cart Item Information.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $id = $_POST['update_product_id'];
    $qty = $_POST['update_product_qty'];

    $Product_price = $_POST['update_product_price'];
 
         $Total =  $qty * $Product_price;
         

         $sqlforUploadProf = "UPDATE `tblcart` SET `cart_product_quantity`='$qty',`cart_product_total`='$Total' WHERE `cart_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$id);
             mysqli_stmt_execute($stmt);
         }
        
}


if(isset($_POST['delete_cart'])){
    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Cart Item.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $id = $_POST['delete_cart_id'];

    $sqlfordeletfiletask="DELETE FROM `tblcart` WHERE `cart_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

}


if(isset($_POST['proceed_quotation'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Generated Quotation Items.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }
    

    $sqlforNoAccount = "SELECT * FROM `tblcart`";
    $sqlrun = mysqli_query($conn, $sqlforNoAccount);

    if(mysqli_num_rows($sqlrun)>0){

        
    $Client = $_POST['client'];
   
    
    $merona = true;
     while($merona){

        $TransactionID = 'MISOUT-'.rand(10000,99999).'-'. chr(65 + rand(0, 25)).strtoupper(substr($Client,-2)). chr(65 + rand(0, 25));
        $filename = mysqli_real_escape_string($conn, $TransactionID);

            
        $sqlforNoAccount = "SELECT order_transaction_id FROM tblorder WHERE order_transaction_id = '$filename'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

        if(mysqli_num_rows($sqlrun)==0){
                 
        $sqlforNoAccount = "SELECT cb_transaction_id FROM tblcreatedbill WHERE cb_transaction_id = '$filename'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

        if(mysqli_num_rows($sqlrun)==0){
            $merona = false;
        }
          
        }
     }
    
    
    $dateCreated = date('Y-m-d');
    $Status = "ON APPROVAL";

     $sqlforNoAccount = "SELECT `cart_id` FROM `tblcart`";
     $stmt = $conn->prepare($sqlforNoAccount);
     $stmt->execute();
     $result = $stmt->get_result();
     $ID[] = array(); 
     $a = 0;
     while ($row = $result->fetch_assoc()) {
             $ID[$a] = $row['cart_id'];
             $a++;
         }
 

         $total_no = count($ID);


    for ($i=0; $i < $total_no; $i++) { 
        # code...


        $sqlforNoAccount = "SELECT * FROM `tblcart` WHERE `cart_id` = ' $ID[$i]'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $cart_product_name = $row['cart_product_name'];
                $cart_product_quantity = $row['cart_product_quantity'];
                $cart_product_unit = $row['cart_product_unit'];
                $cart_product_price = $row['cart_product_price'];
                $cart_product_total = $row['cart_product_total'];
                $cart_product_image = $row['cart_product_image'];
            }


          

        
        $sqlforUploadProf = "INSERT INTO `tblorder`(`order_id`, `order_transaction_id`, `order_client_name`, `order_product_name`, `order_product_quantity`, `order_product_unit`, `order_product_price`, `order_product_total`, `order_product_image`) VALUES  ('',?,?,?,?,?,?,?,?)";
 
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
            
        header("Location: ../Admin/add2cart.php");
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssssssss",$TransactionID,$Client,$cart_product_name, $cart_product_quantity, $cart_product_unit, $cart_product_price, $cart_product_total, $cart_product_image);
            mysqli_stmt_execute($stmt);
    
        }

          $Summary .= "\r\n" . $cart_product_quantity ." x " .  $cart_product_unit . " - " . $cart_product_name; 
    }  


    $sqlforNoAccount = "SELECT SUM(`cart_product_total`) as total FROM `tblcart`";
     $stmt = $conn->prepare($sqlforNoAccount);
     $stmt->execute();
     $result = $stmt->get_result();
 
     while ($row = $result->fetch_assoc()) {
             $total = $row['total'];
         }


    $Summary .= "\r\nTotal of ₱" . number_format($total). " worth of items.";

    $sqlforUploadProf = "INSERT INTO `tblqonprogress`(`onprogressQ_id`, `onprogressQ_client_name`, `onprogressQ_transaction_id`, `onprogressQ_date_created`, `onprogressQ_order_summary`, `onprogressQ_status`) VALUES ('',?,?,?,?,?)";
 
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"sssss",$Client,$TransactionID,$dateCreated, $Summary, $Status);
        mysqli_stmt_execute($stmt);

    }


    $sqlforNoAccount = "DELETE FROM `tblcart`";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
 
     header("Location: ../Admin/onprogress.php");
         $_SESSION ['response'] = "Ordered Successfully!";
         $_SESSION ['res_type']= "success";
     
 

    }else{

        header("Location: ../Admin/add2cart.php");
        $_SESSION ['response'] = "No item available!";
        $_SESSION ['res_type']= "warning";

    }



     
 }


 if(isset($_POST['show_on_approval'])){

    $_SESSION ['transaction_id'] = $_POST['transaction_id'];
    
}


if(isset($_POST['show_on_approvalB'])){

    $_SESSION ['transaction_idB'] = $_POST['transaction_idB'];
    
}

if(isset($_POST['show_on_approvalB1'])){

    $_SESSION ['transaction_idB1'] = $_POST['transaction_idB1'];
    
}

if(isset($_POST['show_on_approval1'])){

    $_SESSION ['transaction_id1'] = $_POST['transaction_id1'];
    
}


if(isset($_POST['update_order_ceo'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Updated Quotation Items.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $id = $_POST['update_product_id'];
    $qty = $_POST['update_product_qty'];
    $qttransaction_id_approvey = $_POST['transaction_id_approve'];

    $Product_price = $_POST['update_product_price'];
 
         $Total =  $qty * $Product_price;
         

         $sqlforUploadProf = "UPDATE `tblorder` SET `order_product_price`='$Product_price',`order_product_total`='$Total' WHERE `order_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$id);
             mysqli_stmt_execute($stmt);
         }

        $sqlforNoAccount = "SELECT * FROM `tblorder` WHERE `order_transaction_id` = '$qttransaction_id_approvey'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $order_product_name = $row['order_product_name'];
                $order_product_quantity = $row['order_product_quantity'];
                $order_product_unit = $row['order_product_unit'];
                $order_product_price = $row['order_product_price'];

                $Summary .= "\r\n" . $order_product_quantity ." x " .  $order_product_unit . " - " . $order_product_name; 
            }

    
            $sqlforNoAccount = "SELECT SUM(`order_product_total`) as total FROM `tblorder`  WHERE `order_transaction_id` = '$qttransaction_id_approvey'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                    $total = $row['total'];
                }
       
       
           $Summary .= "\r\nTotal of ₱" . number_format($total). " worth of items.";


           $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_order_summary`='$Summary' WHERE `onprogressQ_transaction_id` = ?";

           $stmt = mysqli_stmt_init($conn);
       
           if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
               echo "SQL Error";
           }else{
               mysqli_stmt_bind_param($stmt,"s",$qttransaction_id_approvey);
               mysqli_stmt_execute($stmt);
           }

        
}



if(isset($_POST['delete_order_ceo'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Quotation Items.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $id = $_POST['delete_cart_id'];
    $qttransaction_id_approvey = $_POST['transaction_id_approve'];

    $sqlfordeletfiletask="DELETE FROM `tblorder` WHERE `order_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }


    $sqlforNoAccount = "SELECT * FROM `tblorder` WHERE `order_transaction_id` = '$qttransaction_id_approvey'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                $order_product_name = $row['order_product_name'];
                $order_product_quantity = $row['order_product_quantity'];
                $order_product_unit = $row['order_product_unit'];
                $order_product_price = $row['order_product_price'];

                $Summary .= "\r\n" . $order_product_quantity ." x " .  $order_product_unit . " - " . $order_product_name; 
            }

    
            $sqlforNoAccount = "SELECT SUM(`order_product_total`) as total FROM `tblorder`  WHERE `order_transaction_id` = '$qttransaction_id_approvey'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                    $total = $row['total'];
                }
       
       
           $Summary .= "\r\nTotal of ₱" . number_format($total). " worth of items.";


           $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_order_summary`='$Summary' WHERE `onprogressQ_transaction_id` = ?";

           $stmt = mysqli_stmt_init($conn);
       
           if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
               echo "SQL Error";
           }else{
               mysqli_stmt_bind_param($stmt,"s",$qttransaction_id_approvey);
               mysqli_stmt_execute($stmt);
           }


}


if(isset($_POST['delete_bill_ceo'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Billing Items.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $id = $_POST['delete_cart_id'];
    $qttransaction_id_approvey = $_POST['transaction_id_approve'];



    $sqlfordeletfiletask="DELETE FROM `tblcreatedbill` WHERE `cb_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

    
    $sqlforNoAccount = "SELECT * FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$qttransaction_id_approvey'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
                
            $gb_client_name = $row['cb_client_name'];
            $gb_client_payment = $row['cb_billing_price'];
            $gb_client_service = $row['cb_service_promo'];

            $Summary .=  "\r\n" .  $gb_client_name ." - ".$gb_client_service." - ₱" . number_format($gb_client_payment); 

            }

    
            $sqlforNoAccount = "SELECT SUM(`cb_billing_price`) as total FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$qttransaction_id_approvey'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt->execute();
            $result = $stmt->get_result();
        
            while ($row = $result->fetch_assoc()) {
                    $total = $row['total'];
                }
       
       
                $Summary = "\r\nTotal of ₱" . number_format($total). " Billing Amount." . $Summary;


           $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_billing_summary`='$Summary' WHERE `onprogressB_transaction_id` = ?";

           $stmt = mysqli_stmt_init($conn);
       
           if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
               echo "SQL Error";
           }else{
               mysqli_stmt_bind_param($stmt,"s",$qttransaction_id_approvey);
               mysqli_stmt_execute($stmt);
           }

}


if(isset($_POST['btn_approve'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Approved Quotation Information.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $status = $_POST['status'];
    $transaction_id_approve = $_POST['transaction_id_approve'];

         $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='$status' WHERE `onprogressQ_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$transaction_id_approve);
             mysqli_stmt_execute($stmt);
         }

         $_SESSION['transaction_id'] = "wala";
         $_SESSION['transaction_id1'] = "wala";

         $_SESSION ['response'] = "Approved Successfully!";
         $_SESSION ['res_type']= "success";
        
}

if(isset($_POST['btn_approve_bill'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Approved Billing Information.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $status = $_POST['status'];
    $transaction_id_approve = $_POST['transaction_id_approve'];

         $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='$status' WHERE `onprogressB_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$transaction_id_approve);
             mysqli_stmt_execute($stmt);
         }

         $_SESSION['transaction_idB'] = "wala";
         $_SESSION['transaction_idB1'] = "wala";
         $_SESSION ['response'] = "Approved Successfully!";
         $_SESSION ['res_type']= "success";
        
}




if(isset($_POST['btn_deny'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Denied Quotation Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $status = $_POST['status'];
    $transaction_id_approve = $_POST['transaction_id_deny'];

         $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='$status' WHERE `onprogressQ_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$transaction_id_approve);
             mysqli_stmt_execute($stmt);
         }

         $_SESSION['transaction_id1'] = "wala";
         $_SESSION ['response'] = "Denied Successfully!";
         $_SESSION ['res_type']= "success";
        
}


if(isset($_POST['btn_deny_bill'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Denied Billing Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $status = $_POST['status_bill'];
    $transaction_id_approve = $_POST['transaction_id_deny_bill'];

         $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='$status' WHERE `onprogressB_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$transaction_id_approve);
             mysqli_stmt_execute($stmt);
         }

         $_SESSION['transaction_idB1'] = "wala";
         $_SESSION ['response'] = "Denied Successfully!";
         $_SESSION ['res_type']= "success";
        
}


if(isset($_POST['btn_verify'])){
    $timeStamp = date("F j Y h:i A");
    $action = "Verified Quotation Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $status = $_POST['status'];
    $transaction_id_approve = $_POST['transaction_id_approve'];

         $sqlforUploadProf = "UPDATE `tblqonprogress` SET `onprogressQ_status`='$status' WHERE `onprogressQ_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$transaction_id_approve);
             mysqli_stmt_execute($stmt);
         }

         $_SESSION['transaction_id1'] = "wala";
         $_SESSION ['response'] = "Approved Successfully!";
         $_SESSION ['res_type']= "success";
        
}


if(isset($_POST['btn_verify_bill'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Verified Billing Official Receipt.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $status = $_POST['status_bill'];
    $transaction_id_approve = $_POST['transaction_id_approve_bill'];

         $sqlforUploadProf = "UPDATE `tblbonprogress` SET `onprogressB_status`='$status' WHERE `onprogressB_transaction_id` = ?";

         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$transaction_id_approve);
             mysqli_stmt_execute($stmt);
         }

         $_SESSION['transaction_idB1'] = "wala";
         $_SESSION ['response'] = "Approved Successfully!";
         $_SESSION ['res_type']= "success";
        
}


if(isset($_POST['generate_bill'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Added Billing Items.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $itemID = $_POST['itemID'];
    $provider = $_POST['client_provide'];
    $client = $_POST['client_name'];
    $account = $_POST['client_accountnumber'];
    $service = $_POST['client_loadpromo'];
    $client_payment = $_POST['client_payment'];
    $billing_period = $_POST['client_billperiod'];
    $billing_amount = $_POST['client_billamount'];


    if($itemID == 'new'){

        if($provider == 'wala'){

            header("Location: ../Admin/create-billing.php");
            $_SESSION ['response'] = "Please input billing to specified provider.";
            $_SESSION ['res_type']= "warning";
        }else{
    
            $sqlforNoAccount = "SELECT client_group FROM `tblclient` WHERE `client_name`= '$client'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt-> execute();
            $result = $stmt->get_result();  
            while ($row = $result->fetch_assoc()) {
                $client_group = $row['client_group'];
            }
    
            
        $image = $_FILES ['client_receipt']['name'];
        // $fileupname = "";
         if($image == ""){
             header("Location: ../Admin/create-billing.php");
             $_SESSION ['response'] = "Please select your file first!";
             $_SESSION ['res_type']= "warning";
         }else{
    
    
         $filetemploc = $_FILES ['client_receipt']['tmp_name'];
         $fileExt = explode('.', $image);
         $fileActualExt = strtolower(end($fileExt));
    
         $merona = true;
         while($merona){
    
            $filenameNEW = $provider."-".  $client ."-".rand(10000,90000).".".$fileActualExt ;
          
            $filename = mysqli_real_escape_string($conn, $filenameNEW);
    
                
            $sqlforNoAccount = "SELECT gb_receipt FROM `tblgeneratebill` WHERE `gb_receipt`= '$filename'";
            $sqlrun = mysqli_query($conn, $sqlforNoAccount);
    
            if(mysqli_num_rows($sqlrun)==0){
                $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_image`= '$filename'";
                $sqlrun = mysqli_query($conn, $sqlforNoAccount);
    
                    if(mysqli_num_rows($sqlrun)==0){
                        $merona = false;
                    }
            }
         }
        
         $filedestination = '../Admin/img/or/' . $filenameNEW;
         move_uploaded_file($filetemploc, $filedestination);
         $filename = mysqli_real_escape_string($conn, $filenameNEW);
    
    
    
     
         $sqlforUploadProf = "INSERT INTO `tblgeneratebill`(`gb_provider`, `gb_account_number`, `gb_client_name`, `gb_client_group`, `gb_service_promo`, `gb_billing_period`, `gb_billing_price`, `gb_client_payment`, `gb_receipt`)  VALUES (?,?,?,?,?,?,?,?,?)";
     
         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"sssssssss",$provider,$account,$client,$client_group,$service,$billing_period,$billing_amount,$client_payment,$filename);
             mysqli_stmt_execute($stmt);
     
         }
     
         
         header("Location: ../Admin/create-billing.php");
         $_SESSION['selected_provider'] = $provider;
         $_SESSION ['response'] = "Added Bill Successfully!";
         $_SESSION ['res_type']= "success";
    
         }
    
        }
    
      
    }else{

        $image = $_FILES ['client_receipt']['name'];
        // $fileupname = "";
         if($image == ""){

            $sqlforNoAccount = "SELECT client_group FROM `tblclient` WHERE `client_name`= '$client'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt-> execute();
            $result = $stmt->get_result();  
            while ($row = $result->fetch_assoc()) {
                $client_group = $row['client_group'];
            }
             
         $sqlforUploadProf = "UPDATE `tblgeneratebill` SET `gb_provider`='$provider',`gb_account_number`='$account',`gb_client_name`='$client',`gb_client_group`='$client_group',`gb_service_promo`='$service',`gb_billing_period`='$billing_period',`gb_billing_price`='$billing_amount',`gb_client_payment`='$client_payment' WHERE `gb_id` =?";
     
         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$itemID);
             mysqli_stmt_execute($stmt);
     
         }
         
        }else{

            $sqlforNoAccount = "SELECT client_group FROM `tblclient` WHERE `client_name`= '$client'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt-> execute();
            $result = $stmt->get_result();  
            while ($row = $result->fetch_assoc()) {
                $client_group = $row['client_group'];
            }

            
            $sqlforNoAccount = "SELECT gb_receipt FROM `tblgeneratebill` WHERE `gb_id`= '$itemID'";
            $stmt = $conn->prepare($sqlforNoAccount);
            $stmt-> execute();
            $result = $stmt->get_result();  
            while ($row = $result->fetch_assoc()) {
                $name = $row['gb_receipt'];
            }

            
            $fil = '../Admin/img/or/' . $name;   

            unlink($fil);

            $filetemploc = $_FILES ['client_receipt']['tmp_name'];
            $fileExt = explode('.', $image);
            $OldFile = explode('.', $name);

            $fileActualExt = strtolower(end($fileExt));

            $nameOld = $OldFile[0].  '.'  .  $fileActualExt;       
           
            $filedestination = '../Admin/img/or/' . $nameOld;
            move_uploaded_file($filetemploc, $filedestination);
            $filename = mysqli_real_escape_string($conn, $nameOld);


            $sqlforUploadProf = "UPDATE `tblgeneratebill` SET `gb_provider`='$provider',`gb_account_number`='$account',`gb_client_name`='$client',`gb_client_group`='$client_group',`gb_service_promo`='$service',`gb_billing_period`='$billing_period',`gb_billing_price`='$billing_amount',`gb_client_payment`='$client_payment' ,`gb_receipt`='$filename' WHERE `gb_id` =?";
     
         $stmt = mysqli_stmt_init($conn);
     
         if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
             echo "SQL Error";
         }else{
             mysqli_stmt_bind_param($stmt,"s",$itemID);
             mysqli_stmt_execute($stmt);
     
         }



         }




         header("Location: ../Admin/create-billing.php");
         $_SESSION['selected_provider'] = $provider;
         $_SESSION ['response'] = "Data Updated Successfully!";
         $_SESSION ['res_type']= "success";
    


    }

    
    


}

if(isset($_POST['delete_record_gbill'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Billing Item.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $FileID = $_POST['deletefiles_id_confirm'];

     
    $sqlforNoAccount = "SELECT gb_receipt FROM `tblgeneratebill` WHERE `gb_id`= '$FileID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt-> execute();
    $result = $stmt->get_result();  
    while ($row = $result->fetch_assoc()) {
        $name = $row['gb_receipt'];
    }

    $fil = '../Admin/img/or/' . $name;   

    unlink($fil);


    $sqlfordeletfiletask="DELETE FROM `tblgeneratebill` WHERE `gb_id`=?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }

}


if(isset($_POST['btn_generate_bill'])){





    $Provider = $_POST['client_provider'];

    $sqlforNoAccount = "SELECT * FROM `tblgeneratebill` WHERE gb_provider = '$Provider'";
    $sqlrun = mysqli_query($conn, $sqlforNoAccount);

    if(mysqli_num_rows($sqlrun)>0){

    

    $Date_gbill = mysqli_real_escape_string($conn, $_POST['selected_date']);
    $Status = "ON APPROVAL";
    $dateCreated = date('Y-m-d');

  

     $sqlforNoAccount = "SELECT `gb_id` FROM `tblgeneratebill` WHERE gb_provider = '$Provider'";
     $stmt = $conn->prepare($sqlforNoAccount);
     $stmt->execute();
     $result = $stmt->get_result();
     $ID[] = array(); 
     $a = 0;
     while ($row = $result->fetch_assoc()) {
             $ID[$a] = $row['gb_id'];
             $a++;
         }
 

         $total_no = count($ID);


         
    $merona = true;
    while($merona){

       $TransactionID = 'MISOUT-'.rand(10000,99999).'-'. chr(65 + rand(0, 25)).strtoupper(substr("RRMR",-2)).chr(65 + rand(0, 25));
       $filename = mysqli_real_escape_string($conn, $TransactionID);

           
       $sqlforNoAccount = "SELECT order_transaction_id FROM tblorder WHERE order_transaction_id = '$filename'";
       $sqlrun = mysqli_query($conn, $sqlforNoAccount);

       if(mysqli_num_rows($sqlrun)==0){
                
       $sqlforNoAccount = "SELECT cb_transaction_id FROM tblcreatedbill WHERE cb_transaction_id = '$filename'";
       $sqlrun = mysqli_query($conn, $sqlforNoAccount);

       if(mysqli_num_rows($sqlrun)==0){
           $merona = false;
       }
         
       }
    }


    for ($i=0; $i < $total_no; $i++) { 
        # code...


        $sqlforNoAccount = "SELECT * FROM `tblgeneratebill` WHERE `gb_id` = '$ID[$i]'";
        $stmt = $conn->prepare($sqlforNoAccount);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {

                $gb_client_number = $row['gb_account_number'];
                $gb_client_name = $row['gb_client_name'];
                $gb_client_group = $row['gb_client_group'];
                $gb_client_service = $row['gb_service_promo'];
                $gb_billing_period = $row['gb_billing_period'];
                $gb_billing_price = $row['gb_billing_price'];
                $gb_client_payment = $row['gb_client_payment'];
                $gb_receipt = $row['gb_receipt'];
                
            }


          

        
        $sqlforUploadProf = "INSERT INTO `tblcreatedbill`(`cb_id`, `cb_transaction_id`, `cb_provider`, `cb_account_number`, `cb_client_name`, `cb_client_group`, `cb_service_promo`, `cb_billing_period`, `cb_billing_price`, `cb_client_payment`, `cb_receipt`) VALUES ('',?,?,?,?,?,?,?,?,?,?)";
 
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssssssssss",$TransactionID,$Provider,$gb_client_number,$gb_client_name, $gb_client_group,$gb_client_service, $gb_billing_period, $gb_billing_price, $gb_client_payment, $gb_receipt);
            mysqli_stmt_execute($stmt);
    
        }

          $Summary .=  "\r\n" .  $gb_client_name ." - ".$gb_client_service." - ₱" . number_format($gb_client_payment); 
    }
    


    $sqlforNoAccount = "SELECT SUM(`cb_billing_price`) as total FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$TransactionID'";
     $stmt = $conn->prepare($sqlforNoAccount);
     $stmt->execute();
     $result = $stmt->get_result();
 
     while ($row = $result->fetch_assoc()) {
             $total = $row['total'];
         }


    $Summary = "\r\nTotal of ₱" . number_format($total). " Billing Amount." . $Summary;

    $sqlforUploadProf = "INSERT INTO `tblbonprogress`(`onprogressB_id`, `onprogressB_provider`, `onprogressB_transaction_id`, `onprogressB_date_created`, `onprogressB_billing_summary`, `onprogressB_status`) VALUES ('',?,?,?,?,?)";
 
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforUploadProf)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"sssss",$Provider,$TransactionID,$dateCreated, $Summary, $Status);
        mysqli_stmt_execute($stmt);

    }


    $sqlforNoAccount = "DELETE FROM `tblgeneratebill` WHERE gb_provider = '$Provider'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt->execute();
 



    $timeStamp = date("F j Y h:i A");
    $action = "Generated Billing Information.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }
     

    header("Location: ../Admin/onprogress-billing.php");
    $_SESSION ['selected_supplier'] = "wala";
     $_SESSION ['response'] = "Created Bill Successfully!";
     $_SESSION ['res_type']= "success";

     


    }else{

        header("Location: ../Admin/create-billing.php");
        $_SESSION ['response'] = "No item available!";
        $_SESSION ['res_type']= "warning";

    }






}



if(isset($_POST['deletebill_btn_confirm'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Billing on Progress.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


    $FileID = $_POST['deletefiles_id_confirm'];

    $sqlforNoAccount = "SELECT cb_receipt FROM `tblcreatedbill` WHERE `cb_transaction_id`= '$FileID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt-> execute();
    $result = $stmt->get_result();  
    while ($row = $result->fetch_assoc()) {
        $filenameO = $row['cb_receipt'];

        $fil = '../Admin/img/or/' . $filenameO;   

        unlink($fil);
    }

   
    

    $sqlfordeletfiletask="DELETE FROM `tblcreatedbill` WHERE `cb_transaction_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }

    $sqlfordeletfiletask="DELETE FROM `tblbonprogress` WHERE `onprogressB_transaction_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }


   
    $sqlforNoAccount = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id`= '$FileID'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt-> execute();
    $result = $stmt->get_result();  
    while ($row = $result->fetch_assoc()) {
        $filenameO = $row['or_image'];

        
            $fil = '../Admin/img/or/' . $filenameO;   
            unlink($fil);
    }
    

    
    $sqlfordeletfiletask="DELETE FROM `tblofficialreceipt` WHERE `or_transaction_id` =?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$FileID);
        mysqli_stmt_execute($stmt);
    }

}



//-------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------- SUPPLIER


if(isset($_POST['btnSave'])){

   

    $Name = $_POST['supplier-name'];
    $ID = $_POST['id_sup'];
    $Address = $_POST['supplier-address'];
    $Contactp = $_POST['supplier-contact-person'];
    $Contactn = $_POST['supplier-contact-number'];
    $Email = $_POST['supplier-email'];
    $Suppliertype = $_POST['supplier-type'];


    if($ID == ''){

        $timeStamp = date("F j Y h:i A");
        $action = "Added Supplier Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        $sql = "INSERT INTO `tblsupplier`(`supplier_id`, `supplier_name`,`supplier_address`,`supplier_contact_person`, `supplier_contact_number`, `supplier_contact_email`, `supplier_type`) VALUES ('',?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssssss",$Name,$Address,$Contactp,$Contactn,$Email,$Suppliertype);
                mysqli_stmt_execute($stmt);
            }
    
            $_SESSION['selected-item'] = $Suppliertype;
            $_SESSION ['response'] = "Added Supplier Successfully!";
            $_SESSION ['res_type']= "success";
            header("Location: ../Admin/supplier.php");
    }else{

        $timeStamp = date("F j Y h:i A");
        $action = "Updated Supplier Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        $sql = "UPDATE `tblsupplier` SET `supplier_name`='$Name',`supplier_address`='$Address',`supplier_contact_person`='$Contactp',`supplier_contact_number`='$Contactn',`supplier_contact_email`='$Email',`supplier_type`='$Suppliertype' WHERE `supplier_id` = ?";
        $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"s", $ID);
                mysqli_stmt_execute($stmt);
            }
    
            $_SESSION['selected-item'] = $Suppliertype;
            header("Location: ../Admin/supplier.php");
            
            $_SESSION ['response'] = "Update Info Successfully!";
            $_SESSION ['res_type']= "success";



    }


   
}


if(isset($_POST['supplier-showData'])){
    $_SESSION['supplier-selectedItem'] = $_POST['selectedType'];
} 


if(isset($_POST['delete_btn_supplier'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Supplier Item.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $id = $_POST['delete_id_supplier'];

    $sqlfordeletfiletask="DELETE FROM `tblsupplier` WHERE `supplier_id` = ?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

}

//------------------------------------------------------------------------------------------- PRODUCTS

if(isset($_POST['showdataProducts'])){
    $_SESSION['supplierName'] = $_POST['selectedsupplier'];
} 

if(isset($_POST['btnSaveproduct'])){

 

    $id = $_POST['productID'];
    $images = $_POST['productImage'];
    $supplier = $_POST['productSupplier'];
    $name1 = $_POST['productName'];
    $details = $_POST['productDetails'];
    $unit = $_POST['productUnit'];
    $price = $_POST['productPrice'];

    if($id != ''){
        //update

        if(isset($_FILES['file'])){

        
            $file = $_FILES['file'];
    
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
    
            $file_ext = explode('.', $file_name);
            $file_old = explode('.', $images);
            $file_ext = strtolower(end($file_ext));
    
            $allowed = array('jpg', 'img', 'jpeg', 'png');
    
            if(in_array($file_ext, $allowed)){
                if($file_error == 0){
                    if ($file_size <= 10485760) {
                        
                        $file_name_new =  $file_old[0]. '.' .$file_ext;
                        $file_destination = '../Admin/img/products/' . $file_name_new;

                              
                        $fil = '../Admin/img/products/' . $images;   

                        unlink($fil);
    
                        $_SESSION['imageFile'] = $file_name_new;
    
                        if(move_uploaded_file($file_tmp, $file_destination)){
    
                        }else{
    
                        }
                    }
                }
            }

            
        $image = $_SESSION['imageFile'];
    
        $sql = "UPDATE `tblproduct` SET `product_supplier`='$supplier',`product_name`='$name1',`product_details`='$details',`product_unit`='$unit',`product_price`='$price',`product_image`='$image' WHERE `product_id` = ? ";
        $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"s",$id);
                mysqli_stmt_execute($stmt);
            }
    
        $_SESSION['supplierName'] = $supplier;
        header("Location: ../Admin/product.php");
        
        }else{

            $timeStamp = date("F j Y h:i A");
            $action = "Updated Product Information.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }

            $sql = "UPDATE `tblproduct` SET `product_supplier`='$supplier',`product_name`='$name1',`product_details`='$details',`product_unit`='$unit',`product_price`='$price' WHERE `product_id` = ? ";
            $stmt = mysqli_stmt_init($conn);
            
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo "SQL Error";
                }else{
                    mysqli_stmt_bind_param($stmt,"s",$id);
                    mysqli_stmt_execute($stmt);
                }
        
                $_SESSION ['response'] = "Updated Successfully!";
                $_SESSION ['res_type']= "success";
            $_SESSION['supplierName'] = $supplier;
            header("Location: ../Admin/product.php");



        }



    
    }else{

        $timeStamp = date("F j Y h:i A");
        $action = "Added Product Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }
        //add
        if(isset($_FILES['file'])){

        
            $file = $_FILES['file'];
    
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
    
            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));
    
            $allowed = array('jpg', 'img', 'jpeg', 'png');
    
            if(in_array($file_ext, $allowed)){
                if($file_error == 0){
                    if ($file_size <= 10485760) {

                        $merona = true;
                        while($merona){

                         $file_name_new = $supplier."-".$name."-".rand(10000,90000).".".$file_ext ;
                         $filename = mysqli_real_escape_string($conn, $file_name_new);

            
                                $sqlforNoAccount = "SELECT gb_receipt FROM `tblgeneratebill` WHERE `gb_receipt`= '$filename'";
                                $sqlrun = mysqli_query($conn, $sqlforNoAccount);

                            if(mysqli_num_rows($sqlrun)==0){
                                $merona = false;
                            }
        
                        }

                        $file_destination = '../Admin/img/products/' . $file_name_new;
    
                        $_SESSION['imageFile'] = $file_name_new;
    
                        if(move_uploaded_file($file_tmp, $file_destination)){
    
                        }else{
    
                        }
                    }
                }
            }
        }
    
        
        $image = $_SESSION['imageFile'];
    
        $sql = "INSERT INTO `tblproduct`(`product_id`, `product_supplier`, `product_name`, `product_details`, `product_unit`, `product_price`, `product_image`) VALUES ('',?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssssis",$supplier,$name1,$details,$unit,$price,$image);
                mysqli_stmt_execute($stmt);
            }
    
        $_SESSION['supplierName'] = $supplier;
        $_SESSION ['response'] = "Added Successfully!";
        $_SESSION ['res_type']= "success";
        header("Location: ../Admin/product.php");
    
    
    
    }



    
}



if(isset($_POST['delete_btn_product'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Product Data.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $id = $_POST['delete_id_product'];




    $sqlforNoAccount = "SELECT product_image FROM `tblproduct` WHERE `product_id`= '$id'";
    $stmt = $conn->prepare($sqlforNoAccount);
    $stmt-> execute();
    $result = $stmt->get_result();  
    while ($row = $result->fetch_assoc()) {
        $filenameO = $row['product_image'];

        
            $fil = '../Admin/img/products/' . $filenameO;   
            unlink($fil);
    }


    $sqlfordeletfiletask="DELETE FROM `tblproduct` WHERE `product_id` = ?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

}


//------------------------------------------------------------------------------------------- CLIENTS

if (isset($_POST['client-billing-btnSaveclient'])) {

    

    $id = $_POST['client-billing-id'];

    $name1 = $_POST['client-billing-supplierName'];
    $address = $_POST['client-billing-address'];
    $contact = $_POST['client-billing-contactNumber'];
    $email = $_POST['client-billing-email'];
    $type = "Billing";
    $group = $_POST['client-billing-group'];


    if($id != ''){

        $timeStamp = date("F j Y h:i A");
        $action = "Updated Client Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        $sql = "UPDATE `tblclient` SET `client_name`='$name1',`client_address`='$address',`client_contact_number`='$contact',`client_contact_email`='$email',`client_type`='$type',`client_group`='$group' WHERE `client_id` = ?";
        $stmt = mysqli_stmt_init($conn);
    
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

    $_SESSION ['response'] = "Updated Successfully!";
    $_SESSION ['res_type']= "success";
    $_SESSION['client-selectedItem'] = $type;
    header("Location: ../Admin/client.php");




    }else{

        $timeStamp = date("F j Y h:i A");
    $action = "Added Client Information.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }


        
    $sql = "INSERT INTO `tblclient`(`client_id`, `client_name`, `client_address`, `client_contact_number`, `client_contact_email`, `client_type`, `client_group`) VALUES ('',?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssisss",$name1,$address,$contact,$email,$type,$group);
        mysqli_stmt_execute($stmt);
    }

    $_SESSION ['response'] = "Added Successfully!";
    $_SESSION ['res_type']= "success";
    $_SESSION['client-selectedItem'] = $type;
    header("Location: ../Admin/client.php");

    }


}




if (isset($_POST['client-quotation-btnSaveclient'])) {
    $id = $_POST['client-quotation-id'];
    $name1 = $_POST['client-quotation-clientName'];
    $address = $_POST['client-quotation-address'];
    $contact = $_POST['client-quotation-contactNumber'];
    $type = "Quotation";
    $email = $_POST['client-quotation-email'];


    if($id != ''){

        $timeStamp = date("F j Y h:i A");
        $action = "Added Client Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        $sql = "UPDATE `tblclient` SET `client_name`='$name1',`client_address`='$address',`client_contact_number`='$contact',`client_contact_email`='$email',`client_type`='$type' WHERE `client_id` = ?";
        $stmt = mysqli_stmt_init($conn);
    
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }
    $_SESSION ['response'] = "Updated Successfully!";
    $_SESSION ['res_type']= "success";
    $_SESSION['client-selectedItem'] = $type;
    header("Location: ../Admin/client.php");
        

    }else{
        $timeStamp = date("F j Y h:i A");
        $action = "Updated Client Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

        $sql = "INSERT INTO `tblclient`(`client_id`, `client_name`, `client_address`, `client_contact_number`, `client_contact_email`, `client_type`) VALUES ('',?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssiss",$name1,$address,$contact,$email,$type);
            mysqli_stmt_execute($stmt);
        }

        $_SESSION ['response'] = "Added Successfully!";
        $_SESSION ['res_type']= "success";
        $_SESSION['client-selectedItem'] = $type;
        header("Location: ../Admin/client.php");
        
    }
   
}


if(isset($_POST['showdata-clients'])){
    $_SESSION['client-selectedItem'] = $_POST['selectedtype'];
} 


if(isset($_POST['delete_btn_client'])){

    $timeStamp = date("F j Y h:i A");
        $action = "Deleted Client Data.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }

    $id = $_POST['delete_id_client'];

    $sqlfordeletfiletask="DELETE FROM `tblclient` WHERE `client_id` = ?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

}




// ======================= Accounting ============================//




if(isset($_POST['show_accounts'])){
    $_SESSION['provider_selected'] = $_POST['provider_selected'];
} 


if(isset($_POST['shows_accounts'])){
    $_SESSION['accountyear_selected'] = $_POST['account_year'];
    $_SESSION['accountno_selected'] = $_POST['account_no'];
} 


if(isset($_POST['ac_save'])){

        $ac_id = $_POST['ac_id'];
        $ac_month_num = $_POST['ac_month'];
        $ac_year = $_POST['ac_year'];
        $ac_provider = $_POST['ac_provider'];
        $ac_number = $_POST['ac_number'];
        $ac_commission = $_POST['ac_commission'];
        $ac_mbill = $_POST['ac_mbill'];
        $ac_notes = $_POST['ac_notes'];        
        $ac_mpayment = $_POST['ac_mpayment'];
        $ac_cpayment = $_POST['ac_cpayment'];

        
        $ac_vat = ($ac_cpayment/1.12) * 0.12; 
        $ac_gincome = $ac_cpayment-$ac_vat;
        $ac_profit = ($ac_cpayment - $ac_vat) - $ac_mpayment - $ac_commission;

        $MonthDetails = array('',
        'JANUARY',
        'FEBRUARY',
        'MARCH',
        'APRIL',
        'MAY',
        'JUNE',
        'JULY ',
        'AUGUST',
        'SEPTEMBER',
        'OCTOBER',
        'NOVEMBER',
        'DECEMBER');

        $ac_month = $MonthDetails[$ac_month_num];




        if($ac_id != ''){


            $timeStamp = date("F j Y h:i A");
        $action = "Updated Accounting Information.";
        $name = $_SESSION ['user_realname'];
        $apptype = $_SESSION ['user_role'];
        
        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
    
        $stmt = mysqli_stmt_init($conn);
    
        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
            echo "SQL Error";
        }else{
            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
            mysqli_stmt_execute($stmt);
        }


            $sql = "UPDATE `tblaccounting` SET `accounting_supplier`='$ac_provider',`accounting_account_number`='$ac_number',`accounting_month`='$ac_month',`accounting_month_number`='$ac_month_num',`accounting_year`='$ac_year',`accounting_monthly_bill`='$ac_mbill',`accounting_notes`='$ac_notes',`accounting_payment_client`='$ac_cpayment',`accounting_paid_by_misout`='$ac_mpayment',`accounting_vat`='$ac_vat',`accounting_gross_income`='$ac_gincome',`accounting_commission`='$ac_commission',`accounting_total_profit`='$ac_profit' WHERE `accounting_id` =?";
            $stmt = mysqli_stmt_init($conn);
            
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"s",$ac_id);
                mysqli_stmt_execute($stmt);
            }
        
            $_SESSION['provider_selected'] = $ac_provider;
            $_SESSION['accountno_selected'] = $ac_number;
            $_SESSION['accountyear_selected'] = $ac_year;
    
    
            $_SESSION ['response'] = "Updated Successfully!";
            $_SESSION ['res_type']= "success";
            header("Location: ../Admin/accounting.php");
    
        }else{

            $timeStamp = date("F j Y h:i A");
            $action = "Inserted Accounting Information.";
            $name = $_SESSION ['user_realname'];
            $apptype = $_SESSION ['user_role'];
            
            $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
        
            $stmt = mysqli_stmt_init($conn);
        
            if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                mysqli_stmt_execute($stmt);
            }


            $sql = "INSERT INTO `tblaccounting`(`accounting_id`, `accounting_supplier`, `accounting_account_number`, `accounting_month`, `accounting_month_number`, `accounting_year`, `accounting_monthly_bill`, `accounting_notes`, `accounting_payment_client`, `accounting_paid_by_misout`, `accounting_vat`, `accounting_gross_income`, `accounting_commission`, `accounting_total_profit`) VALUES ('',?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = mysqli_stmt_init($conn);
            
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo "SQL Error";
            }else{
                mysqli_stmt_bind_param($stmt,"sssssssssssss",$ac_provider,$ac_number,$ac_month,$ac_month_num,$ac_year,$ac_mbill,$ac_notes, $ac_cpayment, $ac_mpayment, $ac_vat, $ac_gincome, $ac_commission, $ac_profit);
                mysqli_stmt_execute($stmt);
            }
        
            $_SESSION['provider_selected'] = $ac_provider;
            $_SESSION['accountno_selected'] = $ac_number;
            $_SESSION['accountyear_selected'] = $ac_year;
    
    
            $_SESSION ['response'] = "Added Successfully!";
            $_SESSION ['res_type']= "success";
            header("Location: ../Admin/accounting.php");
    
        }




}


if(isset($_POST['delete_btn_account'])){

    $timeStamp = date("F j Y h:i A");
    $action = "Deleted Accounting Data.";
    $name = $_SESSION ['user_realname'];
    $apptype = $_SESSION ['user_role'];
    
    $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
        mysqli_stmt_execute($stmt);
    }

    $id = $_POST['delete_id_supplier'];

    $sqlfordeletfiletask="DELETE FROM `tblaccounting` WHERE `accounting_id` = ?";

    $stmt = mysqli_stmt_init($conn);


    if(!mysqli_stmt_prepare($stmt, $sqlfordeletfiletask)){
        echo "SQL Error";
    }else{
        mysqli_stmt_bind_param($stmt,"s",$id);
        mysqli_stmt_execute($stmt);
    }

}

if(isset($_POST['update_bookkeeping_profile'])){

    $UserRealname =  $_POST ['user_first'] . " " . $_POST ['user_last'];
    $UserFirst =  $_POST ['user_first'];
    $UserLast =   $_POST ['user_last'];
    $UserSex =  $_POST ['user_sex'] ;
    $UserContact = $_POST ['user_contact'];
    $UserEmail = $_POST ['user_email'] ;
    $Username =  $_POST ['user_name'] ;
    $UserPassword = $_POST ['user_password'];

   

    if($UserPassword == ''){
        $UserPassword = $_SESSION ['user_password'];
    }else{
        $UserPassword = mysqli_real_escape_string($conn, password_hash ($_POST['user_password'],PASSWORD_DEFAULT));
    }

    $UserImage = $_FILES ['user_image']['name'];
    if($UserImage == ""){
         
        $UserImage = $_SESSION['user_image'];

     }else{

         $filetemploc = $_FILES ['user_image']['tmp_name'];
         $fileExt = explode('.', $UserImage);
         $fileActualExt = strtolower(end($fileExt));

         $UserImage = $UserFirst . '-' . $UserLast ."-".rand(10000,90000).'.'  .  $fileActualExt;      
        
            if($_SESSION['user_image'] != 'default.png'){

                $fil = '../Bookkeeping/img/profile-picture/' . $_SESSION['user_image'];   

                unlink($fil);
            }
         
        
         $filedestination = '../Bookkeeping/img/profile-picture/' . $UserImage;

        move_uploaded_file($filetemploc, $filedestination);

     }




        $sqlforNoAccount = "SELECT `user_name` FROM `tbluseraccounts` WHERE `user_name` = '$Username'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

                if(mysqli_num_rows($sqlrun)>0){

                    $sqlforNoAccount = "SELECT `user_name` FROM `tbluseraccounts` WHERE `user_name` = '$Username'";
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $old = $row['user_name'];
                    }


                    if($old == $_SESSION['user_name']){

                        $sqlforAccounts = "UPDATE `tbluseraccounts` SET `user_name`='$Username',`user_password`='$UserPassword',`user_firstname`='$UserFirst',`user_lastname`='$UserLast',`user_sex`='$UserSex',`user_contact`='$UserContact',`user_email`='$UserEmail',`user_image`='$UserImage' WHERE `user_name` = ?";

                        $stmt = mysqli_stmt_init($conn);
                    
                        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"s",$Username);
                            mysqli_stmt_execute($stmt);
                        }

                        
                        $timeStamp = date("F j Y h:i A");
                        $action = "Updated User Account Information.";
                        $name = $_SESSION ['user_realname'];
                        $apptype = $_SESSION ['user_role'];
                        
                        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
                    
                        $stmt = mysqli_stmt_init($conn);
                    
                        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                            mysqli_stmt_execute($stmt);
                        }

                                $_SESSION ['user_realname'] = $UserRealname;
                                $_SESSION ['user_last'] = $UserLast;
                                $_SESSION ['user_first'] = $UserFirst;
                                $_SESSION ['user_sex'] = $UserSex;
                                $_SESSION ['user_image'] = $UserImage;
                                $_SESSION ['user_contact'] = $UserContact;
                                $_SESSION ['user_email'] = $UserEmail;
                                $_SESSION ['user_name'] = $Username;
                                $_SESSION ['user_password'] = $UserPassword;

                                $_SESSION ['response'] = "User Already Exist!";
                                $_SESSION ['res_type']= "success";
                                header("Location: ../Bookkeeping/Profile.php");


                    }else{

                        $_SESSION ['response'] = "User Already Exist!";
                        $_SESSION ['res_type']= "error";
                        header("Location: ../Bookkeeping/Profile.php");

                    }

                }
   






}



if(isset($_POST['update_billing_profile'])){

    $UserRealname =  $_POST ['user_first'] . " " . $_POST ['user_last'];
    $UserFirst =  $_POST ['user_first'];
    $UserLast =   $_POST ['user_last'];
    $UserSex =  $_POST ['user_sex'] ;
    $UserContact = $_POST ['user_contact'];
    $UserEmail = $_POST ['user_email'] ;
    $Username =  $_POST ['user_name'] ;
    $UserPassword = $_POST ['user_password'];

   

    if($UserPassword == ''){
        $UserPassword = $_SESSION ['user_password'];
    }else{
        $UserPassword = mysqli_real_escape_string($conn, password_hash ($_POST['user_password'],PASSWORD_DEFAULT));
    }

    $UserImage = $_FILES ['user_image']['name'];
    if($UserImage == ""){
         
        $UserImage = $_SESSION['user_image'];

     }else{

         $filetemploc = $_FILES ['user_image']['tmp_name'];
         $fileExt = explode('.', $UserImage);
         $fileActualExt = strtolower(end($fileExt));

         $UserImage = $UserFirst . '-' . $UserLast.  "-".rand(10000,90000). '.'  .  $fileActualExt;      
        
            if($_SESSION['user_image'] != 'default.png'){

                $fil = '../Admin/img/profile-picture/' . $_SESSION['user_image'];   

                unlink($fil);
            }
         
        
         $filedestination = '../Admin/img/profile-picture/' . $UserImage;

        move_uploaded_file($filetemploc, $filedestination);

     }




        $sqlforNoAccount = "SELECT `user_name` FROM `tbluseraccounts` WHERE `user_name` = '$Username'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

                if(mysqli_num_rows($sqlrun)>0){

                    $sqlforNoAccount = "SELECT `user_name` FROM `tbluseraccounts` WHERE `user_name` = '$Username'";
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $old = $row['user_name'];
                    }


                    if($old == $_SESSION['user_name']){

                        $sqlforAccounts = "UPDATE `tbluseraccounts` SET `user_name`='$Username',`user_password`='$UserPassword',`user_firstname`='$UserFirst',`user_lastname`='$UserLast',`user_sex`='$UserSex',`user_contact`='$UserContact',`user_email`='$UserEmail',`user_image`='$UserImage' WHERE `user_name` = ?";

                        $stmt = mysqli_stmt_init($conn);
                    
                        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"s",$Username);
                            mysqli_stmt_execute($stmt);
                        }

                        
                        $timeStamp = date("F j Y h:i A");
                        $action = "Updated User Account Information.";
                        $name = $_SESSION ['user_realname'];
                        $apptype = $_SESSION ['user_role'];
                        
                        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
                    
                        $stmt = mysqli_stmt_init($conn);
                    
                        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                            mysqli_stmt_execute($stmt);
                        }

                                $_SESSION ['user_realname'] = $UserRealname;
                                $_SESSION ['user_last'] = $UserLast;
                                $_SESSION ['user_first'] = $UserFirst;
                                $_SESSION ['user_sex'] = $UserSex;
                                $_SESSION ['user_image'] = $UserImage;
                                $_SESSION ['user_contact'] = $UserContact;
                                $_SESSION ['user_email'] = $UserEmail;
                                $_SESSION ['user_name'] = $Username;
                                $_SESSION ['user_password'] = $UserPassword;

                                $_SESSION ['response'] = "Successfully Updated!";
                                $_SESSION ['res_type']= "success";
                                header("Location: ../Admin/profile.php");


                    }else{

                        $_SESSION ['response'] = "User Already Exist!";
                        $_SESSION ['res_type']= "error";
                        header("Location: ../Admin/profile.php");

                    }

                }
   






}



if(isset($_POST['update_admin_profile'])){

    $UserRealname =  $_POST ['user_first'] . " " . $_POST ['user_last'];
    $UserFirst =  $_POST ['user_first'];
    $UserLast =   $_POST ['user_last'];
    $UserSex =  $_POST ['user_sex'] ;
    $UserContact = $_POST ['user_contact'];
    $UserEmail = $_POST ['user_email'] ;
    $Username =  $_POST ['user_name'] ;
    $UserPassword = $_POST ['user_password'];

   

    if($UserPassword == '' || $UserPassword == ' '){
        $UserPassword = $_SESSION ['user_password'];
    }else{
        $UserPassword = mysqli_real_escape_string($conn, password_hash ($_POST['user_password'],PASSWORD_DEFAULT));
    }

    $UserImage = $_FILES ['user_image']['name'];
    if($UserImage == ""){
         
        $UserImage = $_SESSION['user_image'];

     }else{

         $filetemploc = $_FILES ['user_image']['tmp_name'];
         $fileExt = explode('.', $UserImage);
         $fileActualExt = strtolower(end($fileExt));

         $UserImage = $UserFirst . '-' . $UserLast ."-".rand(10000,90000).   '.'. $fileActualExt;      
        
            if($_SESSION['user_image'] != 'default.png'){

                $fil = '../CEO/img/profile-picture/' . $_SESSION['user_image'];   

                unlink($fil);
            }
         
        
         $filedestination = '../CEO/img/profile-picture/' . $UserImage;

        move_uploaded_file($filetemploc, $filedestination);

     }




        $sqlforNoAccount = "SELECT `user_name` FROM `tbluseraccounts` WHERE `user_name` = '$Username'";
        $sqlrun = mysqli_query($conn, $sqlforNoAccount);

                if(mysqli_num_rows($sqlrun)>0){

                    $sqlforNoAccount = "SELECT `user_name` FROM `tbluseraccounts` WHERE `user_name` = '$Username'";
                    $stmt = $conn->prepare($sqlforNoAccount);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $old = $row['user_name'];
                    }


                    if($old == $_SESSION['user_name']){

                        $sqlforAccounts = "UPDATE `tbluseraccounts` SET `user_name`='$Username',`user_password`='$UserPassword',`user_firstname`='$UserFirst',`user_lastname`='$UserLast',`user_sex`='$UserSex',`user_contact`='$UserContact',`user_email`='$UserEmail',`user_image`='$UserImage' WHERE `user_name` = ?";

                        $stmt = mysqli_stmt_init($conn);
                    
                        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"s",$Username);
                            mysqli_stmt_execute($stmt);
                        }

                        $timeStamp = date("F j Y h:i A");
                        $action = "Updated User Account Information.";
                        $name = $_SESSION ['user_realname'];
                        $apptype = $_SESSION ['user_role'];
                        
                        $sqlforAccounts = "INSERT INTO tblhistory(history_id, history_timestamp, history_action, history_user, history_app_type) VALUES ('',?,?,?,?);";
                    
                        $stmt = mysqli_stmt_init($conn);
                    
                        if(!mysqli_stmt_prepare($stmt, $sqlforAccounts)){
                            echo "SQL Error";
                        }else{
                            mysqli_stmt_bind_param($stmt,"ssss",$timeStamp,$action,$name,$apptype);
                            mysqli_stmt_execute($stmt);
                        }
                                

                                $_SESSION ['user_realname'] = $UserRealname;
                                $_SESSION ['user_last'] = $UserLast;
                                $_SESSION ['user_first'] = $UserFirst;
                                $_SESSION ['user_sex'] = $UserSex;
                                $_SESSION ['user_image'] = $UserImage;
                                $_SESSION ['user_contact'] = $UserContact;
                                $_SESSION ['user_email'] = $UserEmail;
                                $_SESSION ['user_name'] = $Username;
                                $_SESSION ['user_password'] = $UserPassword;

                                $_SESSION ['response'] = "Successfully Updated!";
                                $_SESSION ['res_type']= "success";
                                header("Location: ../CEO/profile.php");


                    }else{

                        $_SESSION ['response'] = "User Already Exist!";
                        $_SESSION ['res_type']= "error";
                        header("Location: ../CEO/profile.php");

                    }

                }
   






}