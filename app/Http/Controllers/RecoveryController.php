<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class RecoveryController extends Controller
{
    public function index()
    {
        $staticLogged = session()->get('static_admin_logged_in', false);

        // Detect missing database
        try {
            DB::connection()->getPdo();
            $tables = DB::select("SHOW TABLES");
            $tableNames = array_map('current', $tables);
            $dbMissing = !in_array('users', $tableNames);
        } catch (\Throwable $e) {
            $dbMissing = true;
        }

        return view('system.recovery', [
            'staticLogged' => $staticLogged,
            'dbMissing'    => $dbMissing
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Static admin credentials
        $staticUser = ['username'=>'admin@ocnhs','password'=>'onchs@admin-2025'];

        if ($request->username === $staticUser['username'] &&
            $request->password === $staticUser['password']) {

            session(['static_admin_logged_in' => true]);

            return redirect()->route('recovery.index')
                ->with('success', 'Static admin logged in. You may now upload a backup.');
        }

        return redirect()->back()->withErrors(['username'=>'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Session::forget('static_admin_logged_in');
        return redirect()->route('landing')->with('success','Logged out successfully.');
    }

    public function upload(Request $request)
    {
        if (!$request->session()->has('static_admin_logged_in')) {
            return redirect()->route('recovery.index')
                ->withErrors(['username'=>'You must log in first.']);
        }

        $request->validate([
            'sql_file'=>'required|file|mimes:sql,txt,enc',
        ]);

        $database = preg_replace('/[^a-zA-Z0-9_]/','',env('DB_DATABASE','student_profiling_system_105'));
        $username = env('DB_USERNAME','root');
        $password = env('DB_PASSWORD','');
        $host = env('DB_HOST','127.0.0.1');
        $port = env('DB_PORT',3306);

        $backupDir = storage_path('app/sql_backups');
        if (!File::exists($backupDir)) File::makeDirectory($backupDir,0755,true);

        $file = $request->file('sql_file');
        $fileName = 'restore_'.time().'.sql';
        $filePath = $backupDir.'/'.$fileName;
        $file->move($backupDir,$fileName);

        try {
            $pdo = new \PDO("mysql:host={$host};port={$port}",$username,$password);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo = new \PDO("mysql:host={$host};port={$port};dbname={$database}",$username,$password);

            // ✅ Get file content
            $fileContent = File::get($filePath);
            
            // ✅ Try to decrypt the content (for encrypted backup files)
            // If decryption fails, treat it as plain SQL
            try {
                $sql = Crypt::decryptString($fileContent);
                Log::info("Encrypted backup file decrypted successfully");
            } catch (\Exception $e) {
                // If decryption fails, assume it's a plain SQL file
                $sql = $fileContent;
                Log::info("File was not encrypted, proceeding with plain SQL");
            }
            
            $sql = preg_replace('/DROP TABLE IF EXISTS .*?;/i','',$sql);
            $sql = preg_replace('/CREATE DATABASE .*?;/i','',$sql);
            $sql = preg_replace('/USE .*?;/i','',$sql);

            $statements = array_filter(array_map('trim',explode(';',$sql)));

            $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
            foreach($statements as $stmt){
                if(!empty($stmt)){
                    try { $pdo->exec($stmt); } 
                    catch(\Exception $e) { Log::error($e->getMessage()); }
                }
            }
            $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');

            File::delete($filePath);

            // Auto logout after successful upload
            return redirect()->route('recovery.index')
                ->with('success',"Database restored successfully!")
                ->with('auto_logout', true);

        } catch(\Exception $e){
            Log::error($e->getMessage());
            File::delete($filePath);
            return redirect()->route('recovery.index')
                ->with('error','Database restore failed: '.$e->getMessage());
        }
    }
}
