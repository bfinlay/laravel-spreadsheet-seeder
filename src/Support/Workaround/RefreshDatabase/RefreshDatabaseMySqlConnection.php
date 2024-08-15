<?php

namespace bfinlay\SpreadsheetSeeder\Support\Workaround\RefreshDatabase;

use Illuminate\Database\MySqlConnection as BaseMySqlConnection;

class RefreshDatabaseMySqlConnection extends BaseMySqlConnection
{
    /**
     * Perform a rollback within the database.
     * This method is the Laravel 9.x implementation, which fixes a conflict with using the RefreshDatabase trait during testing.
     * Laravel 6.x, 7.x, 8.x fail testing on PHP 8.x with the default implementation.
     *
     * @param  int  $toLevel
     * @return void
     *
     * @throws \Throwable
     */
    protected function performRollBack($toLevel)
    {
        if ($toLevel == 0) {
            $pdo = $this->getPdo();

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        } elseif ($this->queryGrammar->supportsSavepoints()) {
            $this->getPdo()->exec(
                $this->queryGrammar->compileSavepointRollBack('trans'.($toLevel + 1))
            );
        }
    }
}