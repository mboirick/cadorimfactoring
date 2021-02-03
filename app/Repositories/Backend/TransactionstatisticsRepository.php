<?php


namespace App\Repositories\Backend;


use App\Models\Transactionstatistics;

class TransactionstatisticsRepository
{
    /**
     * @param $email
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getStatByEmail($email)
    {
        return Transactionstatistics::where('email_agence', '=', $email)
            ->orderBy('jours', 'DESC')
            ->paginate(20);
    }

    /**
     * @param $email
     * @return mixed|null
     */
    public function getDateByEmail($email)
    {
        return Transactionstatistics::where('email_agence', '=', $email)
            ->latest('jours')
            ->value('jours');
    }

    public function create(array $params)
    {
        return Transactionstatistics::create([
            'id_agence' => $params['id_agence'],
            'email_agence' => $params['email_agence'],
            'jours' => $params['jours'],
            'nbr_operation' =>  $params['nbr_operation'],
            'total' =>  $params['total'],
            'total_gaza' =>  $params['total_gaza'],
            'total_euro' =>  $params['total_euro']
        ]);
    }

    /**
     * @param $email
     * @param $day
     * @param array $params
     * @return bool
     */
    public function updateStatByEmailANdDay($email, $day, array $params)
    {
        return Transactionstatistics::where('jours', '=', $day)
            ->where('email_agence', '=', $email)
            ->update([
                'id_agence' => $params['id'],
                'email_agence' => $params['email'],
                'jours' => $params['day'],
                'nbr_operation' =>  $params['nbr'],
                'total' =>  $params['somme'],
                'total_gaza' =>  $params['somme_gaza'],
                'total_euro' =>  $params['somme_euro']

            ]);
    }

    /**
     * @param $email
     * @param $day
     * @return Transactionstatistics|\Illuminate\Database\Eloquent\Model|null
     */
    public function getStatByEmailAndDAy($email, $day)
    {
        return Transactionstatistics::where('jours', '=', $day)
            ->where('email_agence', '=', $email)
            ->first();
    }
}