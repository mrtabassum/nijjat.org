<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ __('Tax Certificate') }} </title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300&display=swap" rel="stylesheet">

</head>

<body>

<style>
    * {
        font-family: 'Roboto', sans-serif;
        line-height: 26px;
        font-size: 15px;
    }

    ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    /*=========================================================
      [ Table ]
    =========================================================*/

    .custom--table {
        width: 100%;
        color: inherit;
        vertical-align: top;
        font-weight: 400;
        border-collapse: collapse;
        border-bottom: 2px solid #ddd;
        margin-top: 0;
    }
    .table-title{
        font-size: 24px;
        font-weight: 600;
        line-height: 32px;
        margin-bottom: 10px;
    }
    .custom--table thead {
        font-weight: 700;
        background: inherit;
        color: inherit;
        font-size: 16px;
        font-weight: 500;
    }

    .signature_image{
        margin-top: 100px;
    }

    .signature img{
        height: 40px;
    }

    .custom--table tbody {
        border-top: 0;
        overflow: hidden;
        border-radius: 10px;
    }
    .custom--table thead tr {
        border-top: 2px solid #ddd;
        border-bottom: 2px solid #ddd;
        text-align: left;
    }
    .custom--table thead tr th {
        border-top: 2px solid #ddd;
        border-bottom: 2px solid #ddd;
        text-align: left;
        font-size: 16px;
        padding: 10px 0;
    }
    .custom--table tbody tr {
        vertical-align: top;
    }
    .custom--table tbody tr td {
        font-size: 14px;
        line-height: 18px
        vertical-align: top;
        padding: 0 5px;
    }
    .custom--table tbody tr td .data-span {
        font-size: 14px;
        font-weight: 500;
        line-height: 18px;
    }
    .custom--table tbody .table_footer_row {
        border-top: 2px solid #ddd;
        margin-bottom: 10px !important;
        padding-bottom: 10px !important;

    }
    /* invoice area */
    .invoice-area {
        padding: 10px 0;
    }

    .invoice-wrapper {
        max-width: 650px;
        margin: 0 auto;
        box-shadow: 0 0 10px #f3f3f3;
        padding: 0px;
    }

    .invoice-header {
        margin-bottom: 40px;
    }

    .invoice-flex-contents {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .invoice-logo {}

    .invoice-logo img {}

    .invoice-header-contents {
        text-align: center;
    }

    .invoice-header-contents .invoice-title {
        font-size: 40px;
        font-weight: 700;
    }


    .details-list .list {
        font-size: 14px;
        font-weight: 400;
        line-height: 18px;
        color: #666;
        margin: 0;
        padding: 0;
        transition: all .3s;
    }
    .details-list .list strong {
        font-size: 14px;
        font-weight: 500;
        line-height: 18px;
        color: #666;
        margin: 0;
        padding: 0;
        transition: all .3s;
    }

    .details-list .list a {
        display: inline-block;
        color: #666;
        transition: all .3s;
        text-decoration: none;
        margin: 0;
        line-height: 18px
    }

    .item-description {
        margin-top: 10px;
    }

    .products-item {
        text-align: left;
    }

    .invoice-total-count {}

    .invoice-total-count .list-single {
        display: flex;
        align-items: center;
        gap: 30px;
        font-size: 16px;
        line-height: 28px;
    }

    .invoice-total-count .list-single strong {}

    .invoice-subtotal {
        border-bottom: 2px solid #ddd;
        padding-bottom: 15px;
    }

    .invoice-total {
        padding-top: 10px;
    }

    .terms-condition-content {
        margin-top: 30px;
    }

    .terms-flex-contents {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .terms-left-contents {
        flex-basis: 50%;
    }

    .terms-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .terms-para {
        margin-top: 10px;
    }

    .invoice-footer {}

    .invoice-flex-footer {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 30px;
    }

    .single-footer-item {
        flex: 1;
    }

    .single-footer {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .single-footer .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 30px;
        width: 30px;
        font-size: 16px;
        background-color: #000e8f;
        color: #fff;
    }

    .icon-details {
        flex: 1;
    }

    .footer-text{
        text-align: center;
        margin-top: 50px;
    }

    .icon-details .list {
        display: block;
        text-decoration: none;
        color: #666;
        transition: all .3s;
        line-height: 24px;
    }

    .icon-details .list:hover {
        color: #000e8f;
    }
    .seller_admin_notes_wrapper {
        margin-top: 20px;
    }
    .seller_admin_notes h6 {
        margin: 0;
    }
    .seller_admin_notes p {
        margin: 0;
    }

    .parent_details{
        display: flex;
        justify-content: space-between;
    }

    @media (min-width: 300px) and (max-width: 991px) {
        .single-footer-item {
            flex-basis: 45%;
        }
        .custom--table tr th {
            font-size: 16px;
        }
    }

    @media (min-width: 300px) and (max-width: 575px) {
        .products-item {
            text-align: right;
            width: 260px;
            margin-left: auto;
        }
    }

    @media (min-width: 300px) and (max-width: 520px) {
        .item-description-list .list:first-child {
            width: 160px;
        }
        .item-products-list .list:first-child {
            width: 160px;
        }
        .single-footer-item {
            flex-basis: 45%;
        }
    }

    @media (min-width: 300px) and (max-width: 500px) {
        .payment-flex-contents {
            flex-direction: column-reverse;
        }
        .invoice-total-count {
            margin-left: auto;
        }
    }

    @media (min-width: 300px) and (max-width: 420px) {
        .invoice-wrapper {
            box-shadow: none;
        }
        .terms-left-contents {
            flex-basis: 100%;
        }
        .products-item {
            width: 170px;
        }
    }
</style>

<!-- Invoice area Starts -->
<div class="invoice-area">
    <div class="invoice-wrapper">
        <div class="invoice-header">

            <div class="invoice-flex-contents">

                <div class="invoice-logo">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </div>

                <div class="company_details"style="float:right; padding-top: 0">
                    <ul>
                        <li>{{get_static_option('company_name')}}</li>
                        <li><small>{{get_static_option('company_address')}}</small></li>
                        <li><small>{{get_static_option('company_contact')}}</small></li>
                        <li><small>Date : {{ date('d M, Y', strtotime($certificate_date)) }}</small></li>
                    </ul>
                </div>

            </div>

        </div>


           <div class="invoice-header-contents">
               <h2 class="invoice-title">{{ $tax_title  }} </h2>
           </div>

           <div class="item-description">
               <p>{{$tax_description}}</p>
           </div>



        <div class="item-description">
            <h5 class="table-title">{{ __('Your Transaction Details') }}</h5>
            <table class="custom--table">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Order ID') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Gateway') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($donation_details as $data)
                <tr>
                    <td>{{$data->name}}</td>
                    <td>{{ date('d-m-Y',strtotime($data->created_at)) }}</td>
                    <td>{{$data->cause_id}}</td>
                    <td>{{ amount_with_currency_symbol($data->amount) }}</td>
                    <td>{{$data->payment_gateway}}</td>
                </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
        <div class="seller_admin_notes_wrapper">

                <div class="seller_admin_notes">
                    <h6>{{ __('Note : ') }}</h6>
                    <p>{{$tax_note}}</p>
                </div>

            <div class="seller_admin_notes signature_image">
                <h6>{{ __('Signature : ') }}</h6><br>

                <div class="signature">
                    {!! render_image_markup_by_attachment_id(get_static_option('company_signature_image')) !!}<br>
                </div>

                <span class="signature_underline">{{__('_______________')}}</span>
            </div>


        </div>

    </div>
</div>


</body>

</html>

