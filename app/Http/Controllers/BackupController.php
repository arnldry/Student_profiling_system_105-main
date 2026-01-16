<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

class BackupController extends Controller
{
    /**
     * Display the backup/restore page.
     */
    public function index()
    {
        return view('superadmin.backup-restore');
    }

    /**
     * Generate and download the FULL database backup (encrypted).
     */
    public function download()
    {
        try {
            // ✅ Use config() instead of env() to reliably get the DB name
            $databaseName = config('database.connections.mysql.database');

            if (empty($databaseName)) {
                throw new \Exception('Database name not found. Please check your .env or config/database.php file.');
            }

            $backupDir = storage_path('app/sql_backups');

            // Ensure directory exists
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Example filename: backup_student_profiling_system_105_2025_10_26_173045.enc
            $backupFileName = "backup_{$databaseName}_" . now()->format('Y_m_d_His') . ".sql";
            $backupFilePath = $backupDir . '/' . $backupFileName;

            // ✅ Generate full SQL dump
            $sqlContent = $this->generateFullBackupSQL($databaseName);

            // ✅ Encrypt the SQL content for security
            $encryptedContent = Crypt::encryptString($sqlContent);

            // Save encrypted file
            File::put($backupFilePath, $encryptedContent);

            // ✅ Download encrypted file
            return response()->download(
                $backupFilePath,
                $backupFileName,
                ['Content-Type' => 'application/octet-stream']
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '⚠️ Failed to generate backup: ' . $e->getMessage());
        }
    }

    /**
     * Restore the database from uploaded .sql file.
     */

public function upload(Request $request)
{
    // Validate uploaded file
    $request->validate([
        'sql_file' => 'required|file|mimes:sql,txt,enc',
    ]);

    try {
        $file = $request->file('sql_file');
        $fileContent = file_get_contents($file->getRealPath());

        // ✅ Try to decrypt the content (for encrypted backup files)
        // If decryption fails, treat it as plain SQL
        try {
            $sqlContent = Crypt::decryptString($fileContent);
        } catch (\Exception $e) {
            // If decryption fails, assume it's a plain SQL file
            $sqlContent = $fileContent;
            \Log::info("File was not encrypted, proceeding with plain SQL");
        }

        $database = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password', '');

        // Connect to MySQL server WITHOUT specifying database
        $pdo = new \PDO("mysql:host={$host}", $username, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_MULTI_STATEMENTS => true, // allow multiple statements
        ]);

        // 1️⃣ Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // 2️⃣ Reconnect Laravel DB to the newly created database
        config(['database.connections.mysql.database' => $database]);
        DB::purge('mysql');       // clear previous connection
        DB::reconnect('mysql');   // reconnect

        // 3️⃣ Disable foreign key checks for restoration
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 4️⃣ Split SQL into individual statements and execute
        $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
        foreach ($statements as $stmt) {
            if (!empty($stmt)) {
                try {
                    DB::unprepared($stmt);
                } catch (\Exception $e) {
                    \Log::error("SQL execution failed: " . $e->getMessage());
                }
            }
        }

        // 5️⃣ Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', ' Database restored successfully!');

    } catch (\PDOException $e) {
        \Log::error("Database connection/creation failed: " . $e->getMessage());
        return redirect()->back()->with('error', '⚠️ Failed to restore database: ' . $e->getMessage());
    } catch (\Exception $e) {
        \Log::error("Database restore failed: " . $e->getMessage());
        return redirect()->back()->with('error', '⚠️ Failed to restore database: ' . $e->getMessage());
    }
}



    /**
     * Generate full SQL dump for all tables in the database.
     */
     private function generateFullBackupSQL(string $databaseName): string
    {
        $sqlContent = "-- Full Database Backup for {$databaseName}\n";
        $sqlContent .= "-- Generated on " . now()->toDateTimeString() . "\n\n";
        $sqlContent .= "CREATE DATABASE IF NOT EXISTS `{$databaseName}`;\n";
        $sqlContent .= "USE `{$databaseName}`;\n\n";

        $tables = DB::select('SHOW TABLES');
        $key = "Tables_in_{$databaseName}";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            if ($tableName === 'sessions') continue;

            $createTableQuery = DB::select("SHOW CREATE TABLE `$tableName`");
            $createStmt = $createTableQuery[0]->{'Create Table'};

            $sqlContent .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $sqlContent .= $createStmt . ";\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $columns = [];
                $values = [];
                foreach ($rowArray as $column => $value) {
                    $columns[] = "`$column`";
                    if (is_null($value)) $values[] = 'NULL';
                    elseif (is_string($value)) $values[] = "'" . addslashes($value) . "'";
                    else $values[] = $value;
                }
                if (!empty($columns)) {
                    $sqlContent .= "INSERT INTO `$tableName` (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ");\n";
                }
            }
            $sqlContent .= "\n";
        }

        return $sqlContent;
    }

}
