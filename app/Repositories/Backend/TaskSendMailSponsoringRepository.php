<?php


namespace App\Repositories\Backend;


use App\Models\TaskSendMailSponsoring;

class TaskSendMailSponsoringRepository
{
    /**
     * @param array $params
     * @return mixed
     */
    public function insert(array $params)
    {
        return TaskSendMailSponsoring::insert(
            ['sendall' => $params['all'], 'datestart' => $params['startdate'], 'dateend' => $params['enddate'],'numbre' => $params['numbre']]
        );
    }

    /**
     * @param $state
     * @return mixed
     */
    public function getByState($state)
    {
        return TaskSendMailSponsoring::where('state', '=', $state)->get();
    }

    /**
     * @param $id
     * @param $state
     */
    public function updateStateById($id, $state)
    {
        TaskSendMailSponsoring::where('id',$id)
            ->update(['state' => $state]);
    }
}