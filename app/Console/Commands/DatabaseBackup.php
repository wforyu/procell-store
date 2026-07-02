<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {--filename= : Nama file backup}';

    protected $description = 'Backup database MySQL ke file SQL';

    public function handle(): int
    {
        $filename = $this->option('filename') ?? 'backup-'.now()->format('Y-m-d-His').'.sql';
        $disk = Storage::build(['driver' => 'local', 'root' => storage_path('app/backups')]);

        if (! File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        $sql = $this->exportDatabase();
        $disk->put($filename, $sql);

        $this->info("Backup created: {$filename}");
        $this->info('Size: '.number_format(strlen($sql) / 1024, 2).' KB');

        return Command::SUCCESS;
    }

    protected function exportDatabase(): string
    {
        $output = [];
        $output[] = '-- ProCell Store Database Backup';
        $output[] = '-- Generated: '.now()->format('Y-m-d H:i:s');
        $output[] = '-- '.DB::connection()->getDatabaseName();
        $output[] = '';
        $output[] = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
        $output[] = 'SET AUTOCOMMIT = 0;';
        $output[] = 'START TRANSACTION;';
        $output[] = 'SET time_zone = "+00:00";';
        $output[] = '';

        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_'.DB::connection()->getDatabaseName();

        foreach ($tables as $table) {
            $tableName = $table->$key;

            $output[] = '--';
            $output[] = "-- Table: {$tableName}";
            $output[] = '--';
            $output[] = '';

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $output[] = 'DROP TABLE IF EXISTS `'.$tableName.'`;';
            $output[] = $createTable[0]->{'Create Table'}.';';
            $output[] = '';

            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $columns = array_keys((array) $rows->first());
                $columnList = '`'.implode('`, `', $columns).'`';

                $chunks = $rows->chunk(100);
                foreach ($chunks as $chunk) {
                    $values = [];
                    foreach ($chunk as $row) {
                        $rowValues = [];
                        foreach ($columns as $col) {
                            $val = $row->$col;
                            if (is_null($val)) {
                                $rowValues[] = 'NULL';
                            } elseif (is_numeric($val) && ! str_starts_with((string) $val, '0') && str_contains((string) $val, '.')) {
                                $rowValues[] = $val;
                            } elseif (is_numeric($val) && ! str_starts_with((string) $val, '0')) {
                                $rowValues[] = $val;
                            } else {
                                $rowValues[] = "'".addslashes((string) $val)."'";
                            }
                        }
                        $values[] = '('.implode(', ', $rowValues).')';
                    }
                    $output[] = "INSERT INTO `{$tableName}` ({$columnList}) VALUES";
                    $output[] = implode(",\n", $values).';';
                }
                $output[] = '';
            }
        }

        $output[] = 'COMMIT;';

        return implode("\n", $output);
    }
}
