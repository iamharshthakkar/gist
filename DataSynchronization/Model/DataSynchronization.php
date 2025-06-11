<?php
namespace Icao\DataSynchronization\Model;

use Icao\DataSynchronization\Api\DataSynchronizationInterface;
use Icao\DataSynchronization\Api\DataInterface;
use Icao\DataSynchronization\Api\WhitelistRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;

class DataSynchronization implements DataSynchronizationInterface
{
    private $whitelistRepo;
    private $resource;
    public function __construct(
        WhitelistRepositoryInterface $w,
        ResourceConnection $r
    ){
        $this->whitelistRepo = $w;
        $this->resource      = $r;
    }

    public function append(string $scope, DataInterface $d): bool
    {
        return $this->save($scope,$d,false);
    }
    public function replace(string $scope, DataInterface $d): bool
    {
        return $this->save($scope,$d,true);
    }
    private function save(string $scope, DataInterface $d, bool $replace)
    {
        $type = $d->getType();
        if (!$this->whitelistRepo->isAllowed($scope,$type)) {
            throw new LocalizedException(__('Scope/type not allowed.'));
        }
        $c = $this->resource->getConnection();
        $t = $this->resource->getTableName('icao_data_synchronization_payload');
        $c->beginTransaction();
        try {
            if ($replace) {
                $c->delete($t,['scope = ?'=>$scope,'type = ?'=>$type]);
            }
            $c->insert($t,[
                'scope'=>$scope,
                'type'=>$type,
                'payload'=>json_encode($d->getData()),
                'created_at'=>date('Y-m-d H:i:s')
            ]);
            $c->commit();
            return true;
        } catch (\Exception $e) {
            $c->rollBack();
            throw $e;
        }
    }
}
