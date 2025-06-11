<?php
namespace Icao\DataCollection\Model;

use Icao\DataCollection\Api\DataCollectionInterface;
use Icao\DataCollection\Api\DataInterface;
use Icao\DataCollection\Api\WhitelistRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;

class DataCollection implements DataCollectionInterface
{
    private $whitelistRepository;
    private $resource;

    public function __construct(
        WhitelistRepositoryInterface $whitelistRepository,
        ResourceConnection $resource
    ) {
        $this->whitelistRepository = $whitelistRepository;
        $this->resource            = $resource;
    }

    public function append(string $scope, DataInterface $dataWrapper): bool
    {
        return $this->save($scope, $dataWrapper, false);
    }

    public function replace(string $scope, DataInterface $dataWrapper): bool
    {
        return $this->save($scope, $dataWrapper, true);
    }

    private function save(string $scope, DataInterface $dataWrapper, bool $replace): bool
    {
        $type = $dataWrapper->getType();
        $data = $dataWrapper->getData();

        if (!$this->whitelistRepository->isAllowed($scope, $type)) {
            throw new LocalizedException(__('Scope "%1" and type "%2" not allowed.', $scope, $type));
        }

        $conn  = $this->resource->getConnection();
        $table = $this->resource->getTableName('icao_data_collection_payload');

        $conn->beginTransaction();
        try {
            if ($replace) {
                $conn->delete($table, ['scope = ?' => $scope, 'type = ?' => $type]);
            }
            $conn->insert($table, [
                'scope'      => $scope,
                'type'       => $type,
                'payload'    => json_encode($data),
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}
