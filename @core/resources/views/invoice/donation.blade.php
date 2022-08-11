<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{__('invoice')}}</title>
<link href="https://fonts.googleapis.com/css2?family=Baloo+Tamma+2:wght@400;600;700&display=swap" rel="stylesheet">
<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
  tfoot tr td img{
  width: 200px;
  }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top">
          {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
      	</td>
        <td align="right">
            <pre align="left">
                <strong>Billing Information:</strong>
                {{sprintf(__('Name: %s'),$donation_details->name)}}
                {{sprintf(__('Email: %s'),$donation_details->email)}}
                {{sprintf(__('Payment Gateway: %s'),str_replace('_',' ',$donation_details->payment_gateway))}}
                {{sprintf(__('Payment Status: %s'),$donation_details->status)}}
                {{sprintf(__('Transaction ID: %s'),$donation_details->transaction_id)}}
            </pre>
        </td>
    </tr>

  </table>
  <br/>
  <br/>  
  <br/>
  <br/>
  <br/>
  <br/>
  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>{{__('Case')}}</th>
        <th>{{__('Amount')}}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">{{$donation_details->id}}</th>
        <td>{{optional($donation_details->cause)->title}}</td>
        <td align="right">{{amount_with_currency_symbol($donation_details->amount,true)}}</td>
      </tr>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="1"></td>
            <td align="right">{{__('Total')}}</td>
            <td align="right" class="gray">{{amount_with_currency_symbol($donation_details->amount,true)}}</td>
        </tr>
    </tfoot>
  </table>

</body>
</html>