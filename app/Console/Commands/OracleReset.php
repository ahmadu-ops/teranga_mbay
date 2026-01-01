<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OracleReset extends Command
{
    protected $signature = 'oracle:reset {--force : Forcer sans confirmation}';
    protected $description = 'Réinitialiser complètement la base Oracle';

    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️ Ceci va SUPPRIMER toutes les données. Continuer?')) {
                $this->info('Opération annulée.');
                return Command::SUCCESS;
            }
        }

        $this->info('🗑️ Nettoyage de la base Oracle...');

        // Vues
        $views = ['v_top_ventes', 'v_stats_mensuelles', 'v_rentabilite_variete', 
                  'v_mouvements_stock', 'v_dashboard_resume', 'v_stock_actuel'];
        foreach ($views as $view) {
            $this->dropObject('VIEW', $view);
        }
        $this->info('   ✓ Vues supprimées');

        // Tables (incluant migrations)
        $tables = ['perte', 'vente', 'recolte', 'prix', 'variete', 'produit', 'migrations'];
        foreach ($tables as $table) {
            $this->dropObject('TABLE', $table, 'CASCADE CONSTRAINTS');
        }
        $this->info('   ✓ Tables supprimées');

        // Séquences (incluant MIGRATIONS_ID_SEQ de Laravel)
        $sequences = [
            'seq_produit', 
            'seq_variete', 
            'seq_prix', 
            'seq_recolte', 
            'seq_vente', 
            'seq_perte',
            'migrations_id_seq'  // Séquence Laravel pour migrations
        ];
        foreach ($sequences as $seq) {
            $this->dropObject('SEQUENCE', $seq);
        }
        $this->info('   ✓ Séquences supprimées');

        // Supprimer TOUS les triggers restants
        $this->dropAllTriggers();
        $this->info('   ✓ Triggers supprimés');

        $this->newLine();
        $this->info('🎉 Base nettoyée! Exécutez maintenant: php artisan migrate');

        return Command::SUCCESS;
    }

    private function dropObject(string $type, string $name, string $options = ''): void
    {
        try {
            DB::unprepared("
                BEGIN
                    EXECUTE IMMEDIATE 'DROP {$type} {$name} {$options}';
                EXCEPTION
                    WHEN OTHERS THEN NULL;
                END;
            ");
        } catch (\Exception $e) {
            // Ignorer les erreurs
        }
    }

    private function dropAllTriggers(): void
    {
        try {
            $triggers = DB::select("SELECT trigger_name FROM user_triggers");
            foreach ($triggers as $trigger) {
                $this->dropObject('TRIGGER', $trigger->trigger_name);
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs
        }
    }
}