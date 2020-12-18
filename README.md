# clpngs

<h1>Invoicing command challange</h1>

Example usage:

1) Open in web browser on address: https://localhost/index.php
2) Upload .csv file. Example file content:

Customer,Vat number,Document number,Type,Parent document,Currency,Total<br>
Vendor 1,123456789,1000000257,1,,USD,400<br>
Vendor 2,987654321,1000000258,1,,EUR,900<br>
Vendor 3,123465123,1000000259,1,,GBP,1300<br>
Vendor 1,123456789,1000000260,2,1000000257,EUR,100<br>
Vendor 1,123456789,1000000261,3,1000000257,GBP,50<br>
Vendor 2,987654321,1000000262,2,1000000258,USD,200<br>
Vendor 3,123465123,1000000263,3,1000000259,EUR,100<br>
Vendor 1,123456789,1000000264,1,,EUR,1600<br>

3) Set Main Currency, Output Currency and Currency rates
4) Click on Calculate to execute

Example output:

<table class="table_client" data-customer="vendor1"><thead><tr><td></td><td>Customer</td><td>Vat number</td><td>Document number</td><td>Type</td><td>Parent document</td><td>Currency</td><td>Total</td></tr></thead><tbody><tr data-id="1000000257"><td></td><td>Vendor 1</td><td>123456789</td><td>1000000257</td><td>1</td><td></td><td>USD</td><td> 355.83 GBP</td></tr><tr data-id="1000000260" data-parent="1000000257"><td> - </td><td>Vendor 1</td><td>123456789</td><td>1000000260</td><td>2</td><td>1000000257</td><td>EUR</td><td>- 87.8 GBP</td></tr><tr data-id="1000000261" data-parent="1000000257"><td> + </td><td>Vendor 1</td><td>123456789</td><td>1000000261</td><td>3</td><td>1000000257</td><td>GBP</td><td>+ 50 GBP</td></tr><tr data-id="1000000264"><td></td><td>Vendor 1</td><td>123456789</td><td>1000000264</td><td>1</td><td></td><td>EUR</td><td> 1404.8 GBP</td> </tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="total_sub">TOTAL</td><td class="total_sub">1722.83  GBP</td></tr></tbody></table><table class="table_client" data-customer="vendor2"><thead><tr><td></td><td>Customer</td><td>Vat number</td><td>Document number</td><td>Type</td><td>Parent document</td><td>Currency</td><td>Total</td></tr></thead><tbody><tr data-id="1000000258"><td></td><td>Vendor 2</td><td>987654321</td><td>1000000258</td><td>1</td><td></td><td>EUR</td><td> 790.2 GBP</td></tr><tr data-id="1000000262" data-parent="1000000258"><td> - </td><td>Vendor 2</td><td>987654321</td><td>1000000262</td><td>2</td><td>1000000258</td><td>USD</td><td>- 177.91 GBP</td> </tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="total_sub">TOTAL</td><td class="total_sub">612.29  GBP</td></tr></tbody></table><table class="table_client" data-customer="vendor3"><thead><tr><td></td><td>Customer</td><td>Vat number</td><td>Document number</td><td>Type</td><td>Parent document</td><td>Currency</td><td>Total</td></tr></thead><tbody><tr data-id="1000000259"><td></td><td>Vendor 3</td><td>123465123</td><td>1000000259</td><td>1</td><td></td><td>GBP</td><td> 1300 GBP</td></tr><tr data-id="1000000263" data-parent="1000000259"><td> + </td><td>Vendor 3</td><td>123465123</td><td>1000000263</td><td>3</td><td>1000000259</td><td>EUR</td><td>+ 87.8 GBP</td> </tr><tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="total_sub">TOTAL</td><td class="total_sub">1387.8  GBP</td></tr></tbody></table><table class="totals_grand"><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="total">GRAND TOTAL</td><td class="total">3722.92  GBP</td></tr></tbody></table>
