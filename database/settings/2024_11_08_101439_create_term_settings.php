<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $content = <<<EOT
        # Overview :
        
        $this->migrator->add('term.content', [
            'en' => $content,
            'fr' => $content,
            'es' => $content
        ]);

        $this->migrator->add('term.title', [
            'en' => 'Terms of Services',
            'fr' => 'Conditions Générales de Vente',
            'es' => 'Términos de Servicios'
        ]);
    }
};
