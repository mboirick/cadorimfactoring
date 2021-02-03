<?php


namespace App\Repositories\Backend;


use App\Models\CoordinatedOrders;
use Illuminate\Support\Facades\DB;

class CoordinatedOrdersRepository
{
    public function getNbreBenefitByEmail($email)
    {
        return CoordinatedOrders::where('mail_exp', '=', $email)
          ->distinct('phone_benef')
          ->count('phone_benef');
    }

    public function getStatusById($id)
    {
      return CoordinatedOrders::select('tracker_status')->where('id_commande',  $id)->value('tracker_status');
    }

    public function updateById($id, $params)
    {
      return CoordinatedOrders::where('id_commande', $id)->update($params);
    }

    public function getByEmailAndPaymentStatus($email, $paymentStatus)
    {
    	return CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
				->select(
				  'coordonnes_commandes.*',
				  'paiements.payment_date',
				  'paiements.payment_currency',
				  'paiements.somme_mru',
				  'paiements.payment_amount'
				)
				->Where('paiements.payment_status', 'like', '%' . $paymentStatus . '%')
				->where('paiements.payer_email', 'like',  $email)
				->orderBy('paiements.payment_date', 'DESC')
				->paginate(20);
    }

    public function getById($id)
    {
    
    	return  CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
	      ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
	      ->select(
	        'coordonnes_commandes.*',
	        'paiements.payment_date',
	        'paiements.payment_currency',
	        'paiements.somme_mru',
	        'paiements.payment_amount',
	        'paiements.payment_type',
	        'paiements.payment_status',
	        'abonnes.*'
	      )
	      ->where('coordonnes_commandes.id_commande', '=', $id)->get();
   }

   public function getOrderByPaymentStatus($paymentStatus, $trackerStatus)
   {
        return CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
                ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
                ->select(
                'coordonnes_commandes.*',
                'paiements.payment_date',
                'paiements.payment_currency',
                'paiements.somme_mru',
                'paiements.payment_amount',
                'abonnes.id',
                'abonnes.kyc'
                )
                ->Where('paiements.payment_status', 'like', '%' . $paymentStatus . '%')
                ->where('coordonnes_commandes.tracker_status', '!=', $trackerStatus)
                ->orderBy('paiements.payment_date', 'DESC')
                ->paginate(20);
    }

    public function getOrderByCriterion($constraints)
    {
        return CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
            ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
            ->select(
                'coordonnes_commandes.*',
                'paiements.payment_date',
                'paiements.payment_currency',
                'paiements.somme_mru',
                'paiements.payment_amount',
                'abonnes.id',
                'abonnes.kyc'
            )
            ->where(function ($query) use ($constraints) {
                $query->orwhere('coordonnes_commandes.phone_exp', 'like', '%' . $constraints['expediteur'] . '%')
                  ->orWhere('coordonnes_commandes.mail_exp', 'like', '%' . $constraints['expediteur'] . '%')
                  ->orWhere('coordonnes_commandes.nom_exp', 'like', '%' . $constraints['expediteur'] . '%');
                })
            ->where(function ($query) use ($constraints) {
                $query->orwhere('coordonnes_commandes.nom_benef', 'like', '%' . $constraints['beneficiaire'] . '%')
                  ->orWhere('coordonnes_commandes.phone_benef', 'like', '%' . $constraints['beneficiaire'] . '%');
            })  
            ->where('coordonnes_commandes.tracker_status', 'like', '%' . $constraints['statut'] . '%')
            ->Where('paiements.payment_status', 'like', '%omplet%')
            ->where('coordonnes_commandes.date_commande', '>=', str_replace('-', '/', $constraints['from']))
            ->where('coordonnes_commandes.date_commande', '<=', str_replace('-', '/', $constraints['to']))
            ->orderBy('coordonnes_commandes.date_commande', 'DESC')
            ->paginate(20);
  }

  public function getByIdOrder($idOrder)
  {
  	return CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
	      ->select('coordonnes_commandes.*', 'paiements.*')
	      ->where('coordonnes_commandes.id_commande', '=', $idOrder)
	      ->get();
  }

  /**
     * @param $TrackerStatus
     * @param $paymentStatus
     * @return mixed
     */
    public function getBalanceCashByStatus($TrackerStatus, $paymentStatus)
    {
        return CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
            ->where('coordonnes_commandes.tracker_status', '=', $TrackerStatus)
            ->Where('paiements.payment_status', 'like', '%' . $paymentStatus . '%')
            ->sum('paiements.somme_mru');
    }

    public function getByDateOperator($date, $idCLient)
    {
      return CoordinatedOrders::leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
        ->select(
          'coordonnes_commandes.*',
          'paiements.payment_date',
          'paiements.somme_mru'
        )
        ->Where('point_retrait', 'like', '%' . $idCLient . '%')
        ->whereDate('coordonnes_commandes.updated_at', '=', $date)
        ->orderBy('coordonnes_commandes.gaza_confirm', 'ASC')
        ->orderBy('coordonnes_commandes.updated_at', 'ASC')
        ->paginate(20);
    }

    public function getByDateOperatorType($date, $idCLient, $type)
    {
      return CoordinatedOrders::leftJoin('paiements as p', 'coordonnes_commandes.id_commande', '=', 'p.id_commande')
        ->Where('coordonnes_commandes.point_retrait', 'like', '%' . $idCLient . '%')
        ->whereDate('coordonnes_commandes.updated_at', '=', $date)
        ->select(array(
          DB::Raw('count(*) as nbr'),
          DB::Raw('sum(CASE WHEN (coordonnes_commandes.gaza_confirm = "' . $type . '") THEN 0 ELSE p.somme_mru END) as attente'),
          DB::Raw('sum(CASE WHEN (coordonnes_commandes.gaza_confirm = "'. $type .'") THEN p.somme_mru ELSE 0 END) as livre'),
          DB::Raw('sum(coordonnes_commandes.frais_gaza) as frais_gaza'),
          DB::Raw('DATE(coordonnes_commandes.updated_at) day')
        ))
        ->groupBy('day') 
        ->first();
    }
}