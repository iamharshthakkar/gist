<?php
namespace Icao\DataSynchronization\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Whitelist
        if (!$installer->tableExists('icao_data_synchronization_whitelist')) {
            $t = $installer->getConnection()->newTable(
                $installer->getTable('icao_data_synchronization_whitelist')
            )->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                    'identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true
                ], 'Entity ID'
            )->addColumn('scope', Table::TYPE_TEXT, 128, ['nullable'=>false], 'Scope')
             ->addColumn('type', Table::TYPE_TEXT, 128, ['nullable'=>false], 'Type')
             ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [
                    'nullable'=>false,'default'=>Table::TIMESTAMP_INIT
                ], 'Created At'
             )->addIndex(
                 $installer->getIdxName('icao_data_synchronization_whitelist', ['scope','type'], \Magento\Framework\DB\Adapter\Pdo\Mysql::INDEX_TYPE_UNIQUE),
                 ['scope','type'], ['type'=>\Magento\Framework\DB\Adapter\Pdo\Mysql::INDEX_TYPE_UNIQUE]
             )->setComment('ICAO Data Synchronization Whitelist');
            $installer->getConnection()->createTable($t);
        }

        // Payloads
        if (!$installer->tableExists('icao_data_synchronization_payload')) {
            $t = $installer->getConnection()->newTable(
                $installer->getTable('icao_data_synchronization_payload')
            )->addColumn('id', Table::TYPE_BIGINT, null, [
                    'identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true
                ], 'ID'
            )->addColumn('scope', Table::TYPE_TEXT, 128, ['nullable'=>false], 'Scope')
             ->addColumn('type', Table::TYPE_TEXT, 128, ['nullable'=>false], 'Type')
             ->addColumn('payload', Table::TYPE_TEXT, '2M', ['nullable'=>false], 'JSON Payload')
             ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [
                    'nullable'=>false,'default'=>Table::TIMESTAMP_INIT
                ], 'Created At'
             )->addIndex(
                 $installer->getIdxName('icao_data_synchronization_payload', ['scope','type']),
                 ['scope','type']
             )->setComment('ICAO Data Synchronization Payloads');
            $installer->getConnection()->createTable($t);
        }

        $installer->endSetup();
    }
}
