<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportShiphedDatabase extends Migration
{
    private $deleteTables = [
        'rpm_sessions',
        'rpm_users',
    ];

    private $renameTables = [
        'rpm_assignees',
        'rpm_config',
        'rpm_dirty',
        'rpm_receipts',
        'rpm_receipts_batch',
        'rpm_rentals',
        'rpm_rental_utilities',
        'rpm_tenants',
        'rpm_todo',
        'rpm_trans_def_rental',
        'rpm_utilities',
        'rpm_utilities_batch',
        'rpm_utilities_reimburse',
        'rpm_vendors',
    ];

    private $downTables = [
        'assignees',
        'config',
        'dirty',
        'receipts',
        'receipts_batch',
        'properties',
        'property_utilities',
        'tenants',
        'todo',
        'trans_def_rental',
        'utilities',
        'utilities_batch',
        'utilities_reimburse',
        'vendors',
    ];

    private $properties = [
        'rental_id' => "mediumint(8) unsigned NOT NULL AUTO_INCREMENT",
        'rental_street_number' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_street_name' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_apt_no' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_city' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_state' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_zip' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_tenant_id' => "mediumint(8) unsigned NOT NULL DEFAULT '1'",
        'rental_commence_date' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_prorata' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_rent_amount' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_rent_begins' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_rent_frequency' => "tinyint(1) NOT NULL DEFAULT '-1'",
        'rental_deposit' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_fees' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_notes' => "text NOT NULL",
        'rental_status' => "enum('Yes','No') NOT NULL DEFAULT 'No'",
        'rental_contract' => "enum('Yes','No') NOT NULL DEFAULT 'No'",
        'rental_housing' => "enum('Yes','No') NOT NULL DEFAULT 'No'",
        'rental_question' => "enum('Yes','No') NOT NULL DEFAULT 'No'",
        'rental_cur_rent_due' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_rent_paid' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_deposit_due' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_deposit_paid' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_fees_due' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_fees_paid' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_util_due' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_util_paid' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_other_due' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_other_paid' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_latefees_due' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_cur_latefees_paid' => "double(16,2) NOT NULL DEFAULT '0.00'",
        'rental_class' => "varchar(64) NOT NULL DEFAULT ''",
        'rental_active' => "enum('Yes','No') NOT NULL DEFAULT 'Yes'",
    ];

    private $property_utilities = [
        'rental_utility_id' => "mediumint(8) unsigned NOT NULL DEFAULT '0'",
        'rental_utility_elec_service' => "enum('On','Off','None','Unknown') NOT NULL DEFAULT 'Unknown'",
        'rental_utility_gas_service' => "enum('On','Off','None','Unknown') NOT NULL DEFAULT 'Unknown'",
        'rental_utility_water_service' => "enum('On','Off','None','Unknown') NOT NULL DEFAULT 'Unknown'",
        'rental_utility_cable_service' => "enum('On','Off','None','Unknown') NOT NULL DEFAULT 'Unknown'",
        'rental_utility_elec_shared_with' => "varchar(64) NOT NULL DEFAULT ''",
        'rental_utility_gas_shared_with' => "varchar(64) NOT NULL DEFAULT ''",
        'rental_utility_water_shared_with' => "varchar(64) NOT NULL DEFAULT ''",
        'rental_utility_cable_shared_with' => "varchar(64) NOT NULL DEFAULT ''",
        'rental_utility_elec_acctno' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_utility_gas_acctno' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_utility_water_acctno' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_utility_cable_acctno' => "varchar(32) NOT NULL DEFAULT ''",
        'rental_utility_elec_reimburse' => "enum('No','Yes') NOT NULL DEFAULT 'No'",
        'rental_utility_gas_reimburse' => "enum('No','Yes') NOT NULL DEFAULT 'No'",
        'rental_utility_water_reimburse' => "enum('No','Yes') NOT NULL DEFAULT 'No'",
        'rental_utility_cable_reimburse' => "enum('No','Yes') NOT NULL DEFAULT 'No'",
        'rental_utility_elec_transfer_by' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_gas_transfer_by' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_water_transfer_by' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_cable_transfer_by' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_elec_transferred_on' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_gas_transferred_on' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_water_transferred_on' => "date NOT NULL DEFAULT '1970-01-01'",
        'rental_utility_cable_transferred_on' => "date NOT NULL DEFAULT '1970-01-01'",
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->importDump();
        $this->deleteTables();
        $this->renameTables();
        $this->renameColumns('properties');
        $this->renameColumns('property_utilities');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->downTables as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * Import the dump sql.
     *
     * @return void
     */
    private function importDump()
    {
        $fileName = database_path('sql/paulboco_rpm.sql');

        $command = sprintf('mysql %s %s < %s',
            $this->credentials(),
            env('DB_DATABASE'),
            $fileName
        );

        shell_exec($command);
    }

    /**
     * Delete tables.
     *
     * @return void
     */
    private function deleteTables()
    {
        foreach ($this->deleteTables as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * Rename tables.
     *
     * @return void
     */
    private function renameTables()
    {
        foreach ($this->renameTables as $table) {
            Schema::rename($table, str_replace('rpm_', '', $table));
        }

        Schema::rename('rentals', 'properties');
        Schema::rename('rental_utilities', 'property_utilities');
    }

    /**
     * Rename columns.
     *
     * @param  string  $tableName
     * @return void
     */
    private function renameColumns($tableName)
    {
        $statements[] = sprintf('USE %s;', env('DB_DATABASE'));

        // Build an array of ALTER statements
        foreach ($this->$tableName as $column => $definition) {
            $statements[] = sprintf("ALTER TABLE %s CHANGE %s %s %s;",
                $tableName,
                $column,
                str_replace('rental_', 'property_', $column),
                $definition
            );
        }

        // Build the shell command
        $command = sprintf('mysql %s -e "%s"',
            $this->credentials(),
            implode("\n", $statements)
        );

        shell_exec($command);
    }

    /**
     * Return a credentials string for mysql command.
     *
     * @return string
     */
    private function credentials()
    {
        return sprintf('-u%s -p%s',
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
    }
}
