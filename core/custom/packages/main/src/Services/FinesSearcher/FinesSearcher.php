<?php
namespace EvolutionCMS\Main\Services\FinesSearcher;

use EvolutionCMS\Main\Services\GovPay\Contracts\IFineSearchCommand;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use Illuminate\Support\Carbon;

class FinesSearcher
{



    public function searchFines(IFineSearchCommand $searchCommand)
    {
        $fines = $searchCommand->execute();


        $fineList = [];

        foreach ($fines as $fine) {

            $updateData = [
                'protocol_series'=>$fine['sprotocol'],
                'protocol_number'=>$fine['nprotocol'],

                'data'=>$fine,
                'command'=> get_class($searchCommand),
                'command_info' => $searchCommand->toArray(),
                'dsignPost' => \Carbon\Carbon::createFromTimestamp(strtotime($fine['dsignPost'])),
                'd_perpetration' => \Carbon\Carbon::createFromTimestamp(strtotime($fine['dperpetration']))
            ];

            $fineList[] = Fine::updateOrCreate([
                'protocol_series'=>$fine['sprotocol'],
                'protocol_number'=>$fine['nprotocol'],
            ],$updateData);

        }
        return $fineList;
    }

    public function findFineById($fineId)
    {
        $this->updateFine($fineId);
        return Fine::findOrFail($fineId);
    }

    public function updateFine($fineId)
    {
        $fineModel = Fine::findOrFail($fineId);

        $commandClass = $fineModel->command;
        /** @var IFineSearchCommand $searchCommand */
        $searchCommand = $commandClass::fromArray($fineModel->command_info);


        $fines = $searchCommand->execute();

        $filteredFines = array_filter($fines, function ($fine) use ($fineModel) {
            return $fineModel->protocol_series == $fine['sprotocol'] && $fineModel->protocol_number == $fine['nprotocol'];
        });

        if (count($filteredFines) !== 1) {
            throw new \Exception('Can not find fine');
        }

        $searchedFine = array_shift($filteredFines);


        $fineModel->dsignPost = \Carbon\Carbon::createFromTimestamp(strtotime($searchedFine['dsignPost']));
        $fineModel->d_perpetration = \Carbon\Carbon::createFromTimestamp(strtotime($searchedFine['dperpetration']));
        $fineModel->data = $searchedFine;
        $fineModel->updated_at = Carbon::now();
        $fineModel->save();
    }



}