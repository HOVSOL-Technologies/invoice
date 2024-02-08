<?php
 require_once 'pdf/dompdf/autoload.inc.php'; 
 use Dompdf\Dompdf; 
 $dompdf = new Dompdf();
include_once("lib/indexclass.php");
include_once("lib/customer.php");
include_once("lib/additem.php");
// Create an instance of the ClientDatabase class
$databaseindex = new ClientDatabaseindex(); 
$databaseitem = new itemDatabase();
$database = new ClientDatabase();  
$id = isset($_GET['id'])? $_GET['id']:'';
$invoice_details = $databaseindex->get_invoice_details($id);
$customer_details = $database->get_customer_details($invoice_details->cust_id);
$currency = $customer_details->currency=="Dollar"?"$":"Rs. ";
 ob_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="width:100%;margin: 0 auto;height:auto; background: #FFF;box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.08), 0px 0px 4px 0px rgba(0, 0, 0, 0.04);">
  
  <table style="width:100%;background-color:#FBF8FC;border-collapse:collapse;">
    <thead></thead>
    <tbody>
      <tr style="width:100%;">
        
        <?php $banner_logo = base64_encode(file_get_contents('assets/images/banner.png')); ?>
        <td conspan="1"><img src="data:image/jpg;base64,<?php echo $banner_logo; ?>" width="80px" height="350px"></td>
       
        <td colspan="" style="width:94%; margin:0;">
           <p style="width: 100%; margin-bottom:10px;font-family: Inter;font-size: 14px;"><span>Bill To:</span><span style="padding-left:264px">Bill From:</span></p>
           <div class="row1" style="width: 100%;">
             
             <table style="width:100%;background-color:#FBF8FC;border-collapse:collapse;margin:30px auto;">
                <tr style="width:100%;">
                    <?php $client_img = base64_encode(file_get_contents("uploads/customer/" .$customer_details->image)); ?>
                    <td conspan="1"><img src="data:image/jpg;base64,<?php echo $client_img; ?>" style="width:80px; height:80px;"></td>
                    <td colspan="2" style="text-align:left;font-size: 12px;font-family: Inter;margin: 0;padding:0 10px;"><p style="margin: 0;padding:2px 0"><?php echo $customer_details->name; ?></p><p style="margin: 0;padding:2px 0"><?php echo $customer_details->address; ?></p><p style="margin: 0;padding:2px 0"><?php echo $customer_details->phone; ?></p><p style="margin: 0;padding:2px 0"><?php echo $customer_details->email; ?></p></td>
                    <?php $company_img = base64_encode(file_get_contents('assets/images/hovsol_logo.png')); ?>
                    <td conspan="1"><img src="data:image/jpg;base64,<?php echo $company_img; ?>" style="width:80px; height:80px;"></td>
                    <td colspan="2" style="text-align:left;font-size: 12px;font-family: Inter;margin: 0;padding:0 10px;"><p style="margin: 0;padding:2px 0">Hovsol Technologies</p><p style="margin: 0;padding:2px 0">Malbazar, South Colony, Pin - 735221</p><p style="margin: 0;padding:2px 0">(+91) 90389 85021</p><p style="margin: 0;padding:2px 0">hovsoltechnologies@gmail.com</p></td>
                  </tr>
             </table>
           </div>

           <div class="row2" style="width: 100%;">
               <table style="width:100%;background-color:#FBF8FC;border-collapse:collapse;margin:10px auto">
                  <tr style="width:100%;">
                    <td colspan="2" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;vertical-align: text-top;">Project Name:</td>
                    <td colspan="3" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter; width:160px; word-wrap:break-word;"><?php echo $invoice_details->project_name; ?></td>
                    <td colspan="2" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;">Invoice No:</td>
                    <td colspan="1" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;"><?php echo $invoice_details->invoice_id; ?></td>
                  </tr>
                  <tr>
                  <td colspan="2" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;">Company GST No. :</td>
                    <td colspan="3" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;"><?php echo $invoice_details->cost; ?></td>
                    <td colspan="2" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;">Date:</td>
                    <td colspan="1" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;"><?php echo $invoice_details->date; ?></td>
                  </tr>
                  <tr>
                  <td colspan="2" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;">Payment Status:</td>
                    <td colspan="3" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;/*color: #18A558;*/"><?php echo $invoice_details->payment_status; ?></td>
                    <td colspan="2" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;">Due Date:</td>
                    <td colspan="1" style="text-align:left; padding-bottom: 10px; font-size: 14px;font-family: Inter;"><?php echo $invoice_details->due_date; ?></td>
                  </tr>
               </table>
            </div>
        </td>
     </tr>
      
    </tbody>
  </table>
  <table style="width:100%;background-color:#fff;padding:20px;border-collapse:collapse;margin:10px auto">
    <thead style="width:100%;text-align:left; padding-top: 10px; background: #FFF; border-top: 1px solid #898989; border-bottom: 1px solid #898989 ">
      <tr style="width:100%;">
        <th colspan="3" style="text-align: left;padding: 10px 0;font-size: 14px;font-family: Inter; "><span>Item Name</span></th>
        <th colspan="1" style="text-align: left;padding: 10px 0; font-size: 14px;font-family: Inter;"><span>Qty/hours</span></th>
        <th colspan="1" style="text-align: left;padding: 10px 0; font-size: 14px;font-family: Inter;"><span>Price/Units</span></th>
        <th colspan="1" style="text-align: left;padding: 10px 0; font-size: 14px;font-family: Inter;"><span>Discount(%)</span></th>
        <th colspan="1" style="text-align: center;padding: 10px 0;font-size: 14px;font-family: Inter;"><span>Total Price</span></th>
      </tr>
    </thead>
    <tbody>
    <?php 
        $items = json_decode($invoice_details->items,true);
        foreach($items as $item){ ?>
      <tr style="width:100%; box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.08), 0px 0px 4px 0px rgba(0, 0, 0, 0.04);">
        <td colspan="3" style="padding: 10px 5px;font-size: 12px;font-family: Inter;word-wrap:break-word;">
        <?php
        $product = $databaseitem->get_item_details($item['product']);
        echo $product->item_name;
        ?></td>
        <td colspan="1" style="padding: 10px 5px;font-size: 12px;font-family: Inter;"><?php echo $item['quantity']; ?></td>
        <td colspan="1" style="padding: 10px 5px;font-size: 12px;font-family: Inter;"><?php echo $currency.number_format($item['price']); ?></td>
        <td colspan="1" style="padding: 10px 5px;font-size: 12px;font-family: Inter;"><?php echo $item['discount']; ?>%</td>
        <td colspan="1" style="padding: 10px 5px;text-align:center;font-size: 12px;font-family: Inter;"><?php echo $currency.number_format($item['totalPrice']); ?></td>
        
      </tr>
      <?php } ?>
    </tbody>
    <tfoot style="width:100%;text-align:left;">
      <tr style="width:100%;background: #FFF; border-top: 1px solid #898989; border-bottom: 1px solid #898989">
         <td colspan="6" style="padding: 10px 0;font-size: 14px;font-family: Inter;">GST</td>
         <td colspan="1" style="padding: 10px 0;text-align:center;font-size: 14px;font-family: Inter;"><?php echo $invoice_details->gst."%"; ?></td>
      </tr>
     </tfoot>
  </table>
  <table style="width:100%;background: #FBF8FC;border-radius: 8px;padding:20px;border-collapse:collapse;margin:10px auto">
    <tbody style="width:100%;text-align:left; padding-top: 10px;">
      
      <tr style="width:100%;">
         <td colspan="6" style="padding: 10px 0;font-size: 16px;font-family: Inter;">Subtotal</td>
         <td colspan="1" style="padding: 10px 10px;text-align:right;font-size: 16px;font-family: Inter;"><?php echo $currency.number_format($invoice_details->subtotal); ?></td>
      </tr>
      <tr style="width:100%;">
         <td colspan="6" style="padding: 10px 0;font-size: 16px;font-family: Inter;">Taxes</td>
         <td colspan="1" style="padding: 10px 10px;text-align:right;font-size: 16px;font-family: Inter;"><?php echo $currency.number_format($invoice_details->tax); ?></td>
      </tr>
      <tr style="width:100%;">
         <td colspan="6" style="padding: 10px 0;color: #964FA7;font-family: Inter;font-size: 24px;font-weight: 600;font-style: normal;">Total</td>
         <td colspan="1" style="padding: 10px 10px;text-align:right;color: #964FA7;font-family: Inter;font-size: 24px;font-weight: 700;font-style: normal;"><?php echo $currency.number_format($invoice_details->total); ?></td>
      </tr>
    </tbody>
      <tr>
          
      </tr>
  </table>
  <table style="width:100%;background-color:#fff;padding:20px;border-collapse:collapse;margin:20px auto">
    <tbody style="width:100%;text-align:left; padding-top: 10px;border-radius: 8px;">
     <tr style="width:100%;">
         <td colspan="1" style="padding: 10px 10px;font-size: 12px;font-family: Inter;">Signature From:</td>
         <?php $company_signature = base64_encode(file_get_contents("uploads/invoice/image_sign_from/" .$invoice_details->sign_from)); ?>
         <td colspan="2"><img src="data:image/jpg;base64,<?php echo $company_signature; ?>" width="100px"></td>
         <td colspan="1" style="padding: 10px 10px;font-size: 12px;font-family: Inter;">Signature To:</td>
         <?php $client_signature = base64_encode(file_get_contents("uploads/invoice/image_sign_to/" .$invoice_details->sign_to)); ?>
         <td colspan="2"><img src="data:image/jpg;base64,<?php echo $client_signature; ?>" width="100px"></td>
     </tr>
    </tbody>
  </table>
  <table style="width:100%;background-color:#fff;padding:20px;border-collapse:collapse;">
     <tr style="width:100%;">
        <td><p style="color: #964FA7;font-family: Inter;font-size: 24px;font-style: normal;font-weight: 700; margin:0">Thank you for choosing us!</p></td>
     </tr>
     <tr>
         <td><p style="color: #1E1E1E;font-family: Inter;font-size: 16px;font-style: normal;font-weight: 400;line-height: normal;">We appreciate your business and hope to keep up this collaboration in future!</p></td>
     </tr>
  </table>
    
</body>
</html>

<?php
$html = ob_get_clean();
// echo $html;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render(); 
$dompdf->stream('Order-summary-'.'-Hovsol-Invoice.pdf',array('Attachment'=>0));

?>
