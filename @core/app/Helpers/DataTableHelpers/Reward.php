<?php


namespace App\Helpers\DataTableHelpers;

class Reward
{
    public static function withdrawInfoColumn($row){
        
        $output = '<ul>';
        $output .= '<li><strong>'.__('Requested By').': </strong> '.optional($row->user)->name.'</li>';

        if(!empty($row->log)){
            $withdraw_able_amount_without_admin_charge = optional($row->log)->reward_amount - $row->where('payment_status' ,'!=', 'reject')->pluck('withdraw_request_amount')->sum();
            $output .= '<li><strong>'.__('Available For Withdraw Amount').' : </strong> '.amount_with_currency_symbol($withdraw_able_amount_without_admin_charge).'</li>';
        }

        $output .= '<li><strong>'.__('Requested Withdraw Amount').': </strong> '.amount_with_currency_symbol($row->withdraw_request_amount).'</li>';
        $output .= '<li><strong>'.__('Payment Gateway').': </strong> '.ucwords(str_replace('_',' ',$row->payment_gateway)).'</li>';
        $output .= '<li><strong>'.__('Payment Status').': </strong> '.$row->payment_status.'</li>';
        $output .= '<li><strong>'.__('Date').': </strong> '.date_format($row->created_at,'d M Y').'</li>';
        if($row->payment_status === 'approved'){
            $output .= '<li><strong>'.__('Approved Date').': </strong> '.date_format($row->updated_at,'d M Y').'</li>';
        }
        $output .= '</ul>';
        return $output;
    }

}