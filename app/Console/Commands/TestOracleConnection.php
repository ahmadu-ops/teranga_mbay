<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestOracleConnection extends Command
{
    protected $signature = 'oracle:test';
    protected $description = 'Tester la connexion Oracle';

    public function handle()
    {
        $this->info('🔄 Test de connexion Oracle...');

        try {
            // Test basique
            $result = DB::select("SELECT 'Connexion OK' AS status FROM DUAL");
            $this->info('✅ ' . $result[0]->status);

            // Version Oracle
            $version = DB::select("SELECT * FROM V\$VERSION WHERE ROWNUM = 1");
            $this->info('📌 Version: ' . ($version[0]->banner ?? 'Non disponible'));

            // Test des tables
            $this->newLine();
            $this->info('📋 Tables existantes:');
            
            $tables = DB::select("
                SELECT table_name 
                FROM user_tables 
                WHERE table_name IN ('PRODUIT', 'VARIETE', 'PRIX', 'RECOLTE', 'VENTE', 'PERTE')
                ORDER BY table_name
            ");

            if (count($tables) > 0) {
                foreach ($tables as $table) {
                    $count = DB::table(strtolower($table->table_name))->count();
                    $this->line("   - {$table->table_name}: {$count} enregistrements");
                }
            } else {
                $this->warn('   Aucune table trouvée. Exécutez les migrations.');
            }

            // Test des vues
            $this->newLine();
            $this->info('👁️ Vues existantes:');
            
            $views = DB::select("
                SELECT view_name 
                FROM user_views 
                WHERE view_name LIKE 'V_%'
                ORDER BY view_name
            ");

            if (count($views) > 0) {
                foreach ($views as $view) {
                    $this->line("   - {$view->view_name}");
                }
            } else {
                $this->warn('   Aucune vue trouvée.');
            }

            // Test des séquences
            $this->newLine();
            $this->info('🔢 Séquences existantes:');
            
            $sequences = DB::select("
                SELECT sequence_name, last_number 
                FROM user_sequences 
                WHERE sequence_name LIKE 'SEQ_%'
                ORDER BY sequence_name
            ");

            if (count($sequences) > 0) {
                foreach ($sequences as $seq) {
                    $this->line("   - {$seq->sequence_name}: {$seq->last_number}");
                }
            } else {
                $this->warn('   Aucune séquence trouvée.');
            }

            $this->newLine();
            $this->info('🎉 Tous les tests ont réussi!');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}