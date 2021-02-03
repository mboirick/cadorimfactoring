<?php


namespace App\Repositories\Backend;


use App\Models\Payments;

class PaymentsRepository
{
    /**
     * @param $paymentType
     * @param $paymentStatus
     * @param $paymentCurrency
     * @return mixed
     */
    public function  getBalanceByChange($paymentType, $paymentStatus, $paymentCurrency)
    {
       return Payments::select('payment_amount')
            ->where('payment_type', '=', $paymentType)
            ->where('payment_status', '=', $paymentStatus)
            ->where('payment_currency', '=', $paymentCurrency)
            ->sum('payment_amount');

        $virementUSD = DB::table('paiements')
            ->select('payment_amount')
            ->where('payment_type', '=', 'Virement')
            ->where('payment_status', '=', 'En attente')
            ->where('payment_currency', '=', 'USD')
            ->sum('payment_amount');
    }

    public function getSumByEmailAndStatus($email, $paymentStatus)
    {
        return  Payments::where('payer_email', '=', $email)
                ->Where('payment_status', 'like', '%' . $paymentStatus . '%')
                ->sum('payment_amount');
    }

    public function getNbrByEmailAndStatus($email, $paymentStatus)
    {
        return  Payments::where('payer_email', '=', $email)
                  ->Where('payment_status', 'like', '%' . $paymentStatus . '%')
                  ->count();
    }

    public function getByUpdateDate($email, $paymentStatus, $dateStart, $dateEnd)
    {
        return Payments::where('payer_email', '=', $email)
              ->Where('payment_status', 'like', '%' . $paymentStatus . '%')
              ->where('updated_at', '>=', $dateStart)
              ->where('updated_at', '<=', $dateEnd)
              ->sum('payment_amount');
    }
}