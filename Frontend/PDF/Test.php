<?php
//   require '../../Backend/Authorized/UserAuthorized.php';
  include '../../Backend/Export/ReceiptDetail.php';
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../Components', 'config.env');
  $dotenv->load();
  $encryptionKey = $_ENV['ENCRYPT_KEY'];
  $iv = $_ENV['IV'];
  $tag = $_ENV['TAG'];

  use Farzai\PromptPay\Generator;
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/tmp',
    ]),
    'fontdata' => $fontData + [ // lowercase letters only in font key
        'kodchasan' => [
            'R' => 'TH Kodchasal.ttf',
            'I' => 'TH Kodchasal Italic.ttf',
            'B' => 'TH Kodchasal Bold.ttf',
            'BI' => 'TH Kodchasal Bold Italic.ttf',
        ]
    ],
    'default_font' => 'kodchasan'
  ]);
  ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
  <title></title>
<style>
  * {
    font-family: 'Kodchasan';
  }

  body {
    text-align: center;
    color: #000;
  }

  .invoice-box table {
    width: 100%;
    line-height: inherit;
    border-collapse: collapse;
  }

  .invoice-box table td {
    padding: 10px;
  }

  .invoice-box table tr.heading td {
    border-bottom: 1px solid #ddd;
    font-weight: bold;
  }

  .invoice-box table tr.item td {
    border-bottom: 1px solid #eee;
  }

  @media only screen and (max-width: 600px) {
    .invoice-box table tr.top table td {
      width: 100%;
      display: block;
      text-align: center;
    }

    .invoice-box table tr.information table td {
      width: 100%;
      display: block;
      text-align: center;
    }
  }
  .copy {
    font-size: 12px;
    color: #4b5563;
  }
  .header {
    font-size: 24px;
    font-weight: bold;
    color: #000;
  }
  .company {
    font-size: 15px;
    font-weight: bold;
    color: #000;
  }
  .address {
    font-size: 13px;
    color: #4b5563;
    letter-spacing: 0.5px;
    font-weight: normal;
  }
  .bold {
    font-size: 13px;
    color: #000;
    letter-spacing: 0.5px;
    font-weight: bold;
  }
  .descrip {
    font-size: 12px;
    color: #4b5563;
    font-weight: normal;
  }
  .gray {
    font-size: 14px;
    color: #4b5563;
  }
  .black {
    font-size: 14px;
    color: #000;
    font-weight: bold;
  }

  .left {
    text-align: left;
  }

  .right {
    text-align: right;
  }

  .center {
    text-align: center;
  }

  .headerTable {
    font-size: 13px;
    color: #000;
    font-weight: bold;
  }

  .price {
    font-size: 13px;
    color: #000;
  }

  .bgColor {
    background-color: #f3f4f6;
  }
</style>
</head>
<body>		
  <div class="invoice-box">
    <table>
      <tr class="top">
		<td colspan="5">
		  <table>
			<tr>
              <td class="title">
                <img src="../../Pictures/logo2.png" alt="logo" style="width: 10%;" />
              </td>
              <td class="right">
                <p class="copy">(ต้นฉบับ)</p>
                <p class="header">ใบกำกับภาษี/ใบเสร็จรับเงิน</p>
                <p class="company">บริษัท เอสมิติช้อป จำกัด (สำนักงานใหญ่)</p>
                <p class="address">999 หมู่ 999 ถ.ฉลองกรุง 9999 แขวงลาดกระบัง</p>
                <p class="address">เขตลาดกระบัง กรุงเทพมหานคร 10520</p>
                <p class="address">เลขประจำตัวผู้เสียภาษี: 12345678909999</p>
                <p class="address">โทร. 0123456789 อีเมล smiti@test.com</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr class="information">
        <td colspan="5">
          <table>
            <tr>
              <?php
                $payer = PayerDetail($receiptID);
                while($row = $payer->fetch_assoc()) { 
                  $address = $row['PayerAddress'];
                  $indexDist = strpos($address, "ตำบล/แขวง");
                  $indexSubdist = strpos($address, "อำเภอ/เขต");
                  $district = trim(substr($address, $indexDist, $indexSubdist - $indexDist));
                  $subdistrict = trim(substr($address, $indexSubdist));
                  $address = trim(substr($address, 0, $indexDist));
                  if ($row['PayerTaxID'] != null) {
                    $taxID = openssl_decrypt($row['PayerTaxID'], 'aes-256-gcm', $encryptionKey, $options = 0, $iv);
                  }
              ?>
              <td>
                <p class="company">ลูกค้า</p>
                <p class="address"><?php echo $row['PayerFName'] . $row['PayerLName']?></p>
                <p class="address"><?php echo $address . " " . $district?></p>
                <p class="address"><?php echo $subdistrict . " " . $row['PayerProvince'] . " " . $row['PayerPostcode'] ?></p>
                <?php if (!empty($taxID)) {
                  echo "<p class='address'>เลขประจำตัวผู้เสียภาษี: {$taxID}</p>";
                  } 
                }?>
              </td>
              <?php
                $invoice = ReceiptDetail($receiptID);
                while($row = $invoice->fetch_assoc()) { ?>
              <td class="right">
                <p class="bold">เลขที่เอกสาร: <span class="address"><?php echo $receiptID ?></span></p>
                <p class="bold">วันที่ออก: <span class="address"><?php echo $row['PayTime'] ?></span></p>
              <?php } ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr class="heading">
        <td class="headerTable">รายละเอียด</td>
        <td class="headerTable center">จำนวน</td>
        <td class="headerTable center">ราคาต่อหน่วย</td>
        <td class="headerTable center">VAT</td>
        <td class="headerTable center">มูลค่าก่อนภาษี</td>
      </tr>
      <?php 
        $product = ProductDetail($receiptID);
        $noVat = 0;
        $vat = 0;
        $total = 0;
        while($row = $product->fetch_assoc()) {
          $totalOnProduct = $row['Qty'] * $row['PricePerUnit'];
          $noVat = $row['TotalPrice'];
          $vat = $row['VAT'];
          $total = $noVat + $vat;
          $generator = new Generator();
          $qrCode = $generator->generate(
            target: "098-888-8888",
            amount: number_format($total, 2)
        );
      ?>
	  <tr class="item">
        <td>
          <div class="bold left"><?php echo $row['ProName'];?></div>
          <div class="descrip left">ผู้แต่ง: <?php echo $row['Author']?></div>
        </td>
        <td class="center price"><?php echo $row['Qty']?></td>
        <td class="center price"><?php echo $row['PricePerUnit']?></td>
        <td class="center price">7 %</td>
        <td class="center price"><?php echo $totalOnProduct;?></td>
	  </tr>
      <?php } ?>
    <tr>
      <td></td>
      <td></td>
      <td class="gray">ราคาไม่รวมภาษีมูลค่าเพิ่ม</td>
      <td></td>
      <td class="gray center"><?php echo $noVat;?></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td class="gray">ภาษีมูลค่าเพิ่ม 7%</td>
      <td></td>
      <td class="gray center"><?php echo number_format($vat, 2);?></td>
    </tr>
    <tr class="item">
      <td></td>
      <td></td>
      <td class="black">จำนวนเงิมรวมทั้งสิ้น</td>
      <td></td>
      <td class="black center"><?php echo number_format($total, 2);?></td>
    </tr>
	</table>
  </div>
</body>
</html>
<?php
  $file = "Invoice_{$receiptID}.pdf";
  $html = ob_get_contents();
  ob_end_clean();
  $mpdf->WriteHTML($html);
  $mpdf->Output($file, 'I');
?>